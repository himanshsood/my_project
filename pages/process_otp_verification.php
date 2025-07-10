<?php
session_start();
header('Content-Type: application/json');

require_once('../modules/db.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['success'] = false;
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

if (!isset($_SESSION['temp_user_id']) || !isset($_SESSION['temp_otp'])) {
    $response['success'] = false;
    $response['message'] = 'Session expired. Please register again.';
    $response['redirect'] = 'register.php';
    echo json_encode($response);
    exit;
}
if (!isset($_SESSION['temp_otp']) || !isset($_SESSION['otp_expiry'])) {
    echo json_encode([
        'success' => false,
        'message' => 'OTP not found. Please request a new one.',
    ]);
    exit;
}

if (time() > $_SESSION['otp_expiry']) {
    echo json_encode([
        'success' => false,
        'message' => 'OTP has expired. Please request a new one.',
    ]);
    exit;
}

try {
    
    if ($connection->connect_error) {
        throw new Exception("Connection failed: " . $connection->connect_error);
    }
    
    $enteredOTP = trim($_POST['otp'] ?? '');
    
    if (empty($enteredOTP)) {
        $response['success'] = false;
        $response['message'] = 'Please enter the verification code';
        $response['field_error'] = true;
        $response['clear_inputs'] = false;
        echo json_encode($response);
        exit;
    }
    
    if (!preg_match('/^\d{6}$/', $enteredOTP)) {
        $response['success'] = false;
        $response['message'] = 'Please enter a valid 6-digit code';
        $response['field_error'] = true;
        $response['clear_inputs'] = true;
        echo json_encode($response);
        exit;
    }

    $tempUserId = $_SESSION['temp_user_id'];
    $sessionOTP = $_SESSION['temp_otp'];
    
    if ($enteredOTP !== $sessionOTP) {
        $response['success'] = false;
        $response['message'] = 'Invalid verification code. Please check and try again.';
        $response['field_error'] = true;
        $response['clear_inputs'] = true;
        echo json_encode($response);
        exit;
    }
    
    $updateStmt = $connection->prepare("UPDATE users_info SET is_email_verified = 1 WHERE id = ?");
    $updateStmt->bind_param("i", $tempUserId);
    
    if ($updateStmt->execute()) {
        if ($updateStmt->affected_rows > 0) {
            unset($_SESSION['temp_user_id']);
            unset($_SESSION['temp_otp']);
            unset($_SESSION['temp_email']);
            unset($_SESSION['temp_name']);
            
            $response['success'] = true;
            $response['message'] = 'Your email has been successfully verified! You can now login to your account.';
            $response['redirect'] = 'login.php';
        } else {
            throw new Exception("No user found with the provided ID");
        }
    } else {
        throw new Exception("Error updating user verification status: " . $updateStmt->error);
    }
    
    $updateStmt->close();
    $connection->close();
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'An error occurred during verification. Please try again.';
    error_log("OTP Verification Error: " . $e->getMessage());
}

echo json_encode($response);
?>