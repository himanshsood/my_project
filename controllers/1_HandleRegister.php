<?php

session_start();
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../modules/db.php'); 

// Response array
$response = array();

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

try {
    if ($connection->connect_error) {
        $response['success'] = false;
        $response['message'] = 'Database connection failed: ' . $connection->connect_error;
        echo json_encode($response);
        exit;
    }
    
    // Check if users_info table exists
    $tableCheck = $connection->query("SHOW TABLES LIKE 'users_info'");
    if ($tableCheck->num_rows == 0) {
        $response['success'] = false;
        $response['message'] = 'users_info table does not exist. Please create the table first.';
        echo json_encode($response);
        exit;
    }
    
    // Get and sanitize input data
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    // Server-side validation
    $errors = array();
    
    // Validate First Name
    if (empty($firstName)) {
        $errors['firstName'] = 'First name is required';
    } elseif (strlen($firstName) < 2) {
        $errors['firstName'] = 'First name must be at least 2 characters';
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $firstName)) {
        $errors['firstName'] = 'First name can only contain letters and spaces';
    }
    
    // Validate Last Name
    if (empty($lastName)) {
        $errors['lastName'] = 'Last name is required';
    } elseif (strlen($lastName) < 2) {
        $errors['lastName'] = 'Last name must be at least 2 characters';
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $lastName)) {
        $errors['lastName'] = 'Last name can only contain letters and spaces';
    }
    
    // Validate Email
    if (empty($email)) {
        $errors['email'] = 'Email address is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    } elseif (strlen($email) > 255) {
        $errors['email'] = 'Email address is too long';
    }
    
    // Validate Password
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long';
    }
    
    // Validate Confirm Password
    if (empty($confirmPassword)) {
        $errors['confirmPassword'] = 'Please confirm your password';
    } elseif ($password !== $confirmPassword) {
        $errors['confirmPassword'] = 'Passwords do not match';
    }
    
    // If there are validation errors, return them
    if (!empty($errors)) {
        $response['success'] = false;
        $response['message'] = 'Please fix the errors and try again';
        $response['errors'] = $errors;
        echo json_encode($response);
        exit;
    }
    
    $checkEmailStmt = $connection->prepare("SELECT id, is_email_verified FROM users_info WHERE email = ?");
    if (!$checkEmailStmt) {
        throw new Exception("Prepare failed for email check: " . $connection->error);
    }

    $checkEmailStmt->bind_param("s", $email);
    if (!$checkEmailStmt->execute()) {
        throw new Exception("Execute failed for email check: " . $checkEmailStmt->error);
    }

    $result = $checkEmailStmt->get_result();
    $userExists = $result->fetch_assoc();
    $checkEmailStmt->close();

    if ($userExists && $userExists['is_email_verified'] == 1) {
        // Email already verified, don't allow reuse
        $response['success'] = false;
        $response['message'] = 'Email already registered and verified. Please login or use a different email.';
        echo json_encode($response);
        $connection->close();
        exit;
    }

    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT); 
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($userExists && $userExists['is_email_verified'] == 0) {
        // Email exists but not verified — update existing record
        $updateStmt = $connection->prepare("UPDATE users_info SET first_name = ?, last_name = ?, password = ?, created_at = NOW() WHERE email = ?");
        if (!$updateStmt) {
            throw new Exception("Prepare failed for update: " . $connection->error);
        }
        $updateStmt->bind_param("ssss", $firstName, $lastName, $hashedPassword, $email);
        if (!$updateStmt->execute()) {
            throw new Exception("Error updating user data: " . $updateStmt->error);
        }
        $updateStmt->close();

        $userId = $userExists['id'];
    } else {
        $insertStmt = $connection->prepare("INSERT INTO users_info (first_name, last_name, email, password, is_email_verified, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
        if (!$insertStmt) {
            throw new Exception("Prepare failed for user insert: " . $connection->error);
        }
        $insertStmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
        if (!$insertStmt->execute()) {
            throw new Exception("Error inserting user data: " . $insertStmt->error);
        }
        $userId = $connection->insert_id;
        $insertStmt->close();
    }

    // Store session data
    $_SESSION['temp_user_id'] = $userId;
    $_SESSION['temp_otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 300;
    $_SESSION['temp_email'] = $email;
    $_SESSION['temp_name'] = $firstName . ' ' . $lastName;

    // Send OTP email
    require __DIR__ . '/../vendor/autoload.php';
    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'himanshsood311@gmail.com';  // your gmail
        $mail->Password = 'zkrj euwx dfnl tdwy';       // your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('himanshsood311@gmail.com', 'Himansh');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP is: <b>$otp</b>";
        $mail->send();
        
        $response['success'] = true;
        $response['message'] = 'Registration successful! Please check your email for the OTP to verify your account.';
        $response['redirect'] = 'verify_otp.php';
        
    } catch (Exception $e) {
        $response['success'] = true;
        $response['message'] = 'Registration successful! However, there was an issue sending the verification email. Your OTP is: ' . $otp . ' (This is for testing only)';
        $response['redirect'] = 'verify_otp.php';
    }
    
    echo json_encode($response);
    $connection->close();
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Registration failed. Please try again.';
    error_log("Registration Error: " . $e->getMessage());
    echo json_encode($response);
    exit;
}

?>