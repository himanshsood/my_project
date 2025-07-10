<?php
session_start();
header('Content-Type: application/json');

require_once('../modules/db.php');

// Response array
$response = array();

// Enable error logging for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

try {
    // Check database connection
    if ($connection->connect_error) {
        $response['success'] = false;
        $response['message'] = 'Database connection failed: ' . $connection->connect_error;
        echo json_encode($response);
        exit;
    }
    
    // DEBUG: Log session and POST data
    error_log("SESSION csrf_token: " . ($_SESSION['csrf_token'] ?? 'NOT SET'));
    error_log("POST csrf_token: " . ($_POST['csrf_token'] ?? 'NOT SET'));
    error_log("POST data: " . print_r($_POST, true));
    
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $response['success'] = false;
        $response['message'] = 'Invalid security token. Please refresh the page and try again.';
        $response['debug'] = [
            'session_token' => $_SESSION['csrf_token'] ?? 'NOT SET',
            'post_token' => $_POST['csrf_token'] ?? 'NOT SET',
            'tokens_match' => ($_POST['csrf_token'] ?? '') === ($_SESSION['csrf_token'] ?? '')
        ];
        echo json_encode($response);
        exit;
    }
    
    // Get and sanitize input data
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['rememberMe']) ? (bool)$_POST['rememberMe'] : false;
    
    // DEBUG: Log input data
    error_log("Email: " . $email);
    error_log("Password length: " . strlen($password));
    error_log("Remember Me: " . ($rememberMe ? 'true' : 'false'));
    
    // Server-side validation
    $errors = array();
    
    // Validate Email
    if (empty($email)) {
        $errors['email'] = 'Email address is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    }
    
    // Validate Password
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long';
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        $response['success'] = false;
        $response['message'] = 'Please fix the errors and try again';
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }
    
    // Check if user exists and get user data
    $loginStmt = $connection->prepare("SELECT id, first_name, last_name, email, password, is_email_verified FROM users_info WHERE email = ?");
    if (!$loginStmt) {
        throw new Exception("Prepare failed for login: " . $connection->error);
    }
    
    $loginStmt->bind_param("s", $email);
    if (!$loginStmt->execute()) {
        throw new Exception("Execute failed for login: " . $loginStmt->error);
    }
    
    $result = $loginStmt->get_result();
    $user = $result->fetch_assoc();
    $loginStmt->close();
    
    // DEBUG: Log user lookup results
    error_log("User found: " . ($user ? 'YES' : 'NO'));
    if ($user) {
        error_log("User ID: " . $user['id']);
        error_log("User email: " . $user['email']);
        error_log("Email verified: " . $user['is_email_verified']);
    }
    
    // Check if user exists
    if (!$user) {
        $response['success'] = false;
        $response['message'] = 'Invalid email or password';
        $response['debug'] = 'User not found';
        echo json_encode($response);
        exit;
    }
    
    // Check if email is verified
    if ($user['is_email_verified'] == 0) {
        $response['success'] = false;
        $response['message'] = 'Please verify your email before logging in. Check your inbox for the verification email.';
        $response['debug'] = 'Email not verified';
        echo json_encode($response);
        exit;
    }
    
    // Verify password
    $passwordMatch = password_verify($password, $user['password']);
    error_log("Password match: " . ($passwordMatch ? 'YES' : 'NO'));
    
    if (!$passwordMatch) {
        $response['success'] = false;
        $response['message'] = 'Invalid email or password';
        $response['debug'] = 'Password mismatch';
        echo json_encode($response);
        exit;
    }
    
    // Login successful - set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['user_first_name'] = $user['first_name'];
    $_SESSION['user_last_name'] = $user['last_name'];
    $_SESSION['is_logged_in'] = true;
    
    // Handle remember me functionality
    if ($rememberMe) {
        // Set cookies for 30 days
        $cookieExpire = time() + (30 * 24 * 60 * 60); // 30 days
        setcookie('remember_user_id', $user['id'], $cookieExpire, '/', '', true, true);
        setcookie('remember_token', hash('sha256', $user['id'] . $user['email']), $cookieExpire, '/', '', true, true);
    }
    
    // Update last login time (optional)
    $updateLoginStmt = $connection->prepare("UPDATE users_info SET last_login = NOW() WHERE id = ?");
    if ($updateLoginStmt) {
        $updateLoginStmt->bind_param("i", $user['id']);
        $updateLoginStmt->execute();
        $updateLoginStmt->close();
    }
    
    // Generate new CSRF token for next request
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    
    $response['success'] = true;
    $response['message'] = 'Login successful! Redirecting to your profile...';
    $response['redirect'] = '/my_project/task8/index.php';
    
    echo json_encode($response);
    $connection->close();
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Login failed. Please try again.';
    $response['error_details'] = $e->getMessage();
    $response['debug'] = 'Exception caught in login handler';
    error_log("Login Error: " . $e->getMessage());
    echo json_encode($response);
    exit;
}
?>