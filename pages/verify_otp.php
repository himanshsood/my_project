<?php
session_start();

// Check if user has valid session data
if (!isset($_SESSION['temp_user_id']) || !isset($_SESSION['temp_otp']) || !isset($_SESSION['temp_email'])) {
    header('Location: register.php');
    exit;
}

$userEmail = $_SESSION['temp_email'];
$userName = $_SESSION['temp_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Your Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .verification-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .otp-input {
            font-size: 1.5rem;
            text-align: center;
            letter-spacing: 0.5rem;
            font-weight: bold;
        }
        .email-info {
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
        }
        .success-message {
            display: none;
            text-align: center;
            padding: 20px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-container">
            <h2 class="text-center mb-4">Verify Your Email</h2>
            
            <div class="email-info">
                <h6 class="mb-2">Hello <?php echo htmlspecialchars($userName); ?>!</h6>
                <p class="mb-0">We've sent a 6-digit verification code to:</p>
                <strong><?php echo htmlspecialchars($userEmail); ?></strong>
            </div>
            
            <div id="alert-container"></div>
            
            <div id="verification-form">
                <form id="otpForm" novalidate>
                    <div class="mb-4">
                        <label for="otp" class="form-label">Enter Verification Code</label>
                        <input type="text" class="form-control otp-input" id="otp" name="otp" maxlength="6" placeholder="000000" required>
                        <div class="form-text">Please enter the 6-digit code sent to your email</div>
                        <div class="invalid-feedback" id="otp-error"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn">
                            <span id="btnText">Verify OTP</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p class="mb-2">Didn't receive the code?</p>
                    <button type="button" id="resendOtpBtn" class="btn">Resend OTP</button>

                </div>
                
                <div class="text-center mt-3">
                    <a href="register.php" class="btn btn-outline-secondary">Back to Registration</a>
                </div>
            </div>
            
            <div class="success-message" id="success-message">
                <h4 class="text-success mb-3">✓ Email Successfully Verified!</h4>
                <p class="mb-3">Your email has been successfully verified. You can now login to your account.</p>
                <a href="login.php" class="btn btn-success">Click here to login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/3_HandleOTP.js"></script>
</body>
</html>

