<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$response = [];

if (!isset($_SESSION['temp_user_id']) || !isset($_SESSION['temp_email'])) {
    $response['success'] = false;
    $response['message'] = 'Session expired. Please register again.';
    $response['redirect'] = 'register.php';
    echo json_encode($response);
    exit;
}

$otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT); 
$_SESSION['temp_otp'] = $otp;
$_SESSION['otp_expiry'] = time() + 300; 

$email = $_SESSION['temp_email'];

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';          
    $mail->SMTPAuth   = true;
    $mail->Username = 'himanshsood311@gmail.com';  // your gmail
        $mail->Password = 'zkrj euwx dfnl tdwy';       // your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('himanshsood311@gmail.com', 'Himansh');
        $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your New OTP for Email Verification';
    $mail->Body    = "<p>Your new OTP is: <strong>$otp</strong></p><p>This code will expire in 5 minutes.</p>";

    $mail->send();

    $response['success'] = true;
    $response['message'] = 'OTP resent successfully to your email.';
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = 'Failed to resend OTP. Please try again.';
    error_log("PHPMailer Error: " . $mail->ErrorInfo);
}

echo json_encode($response);
