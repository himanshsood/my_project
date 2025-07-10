<?php
session_start();
include '../views/navbar.php' ;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success-message {
            color: #198754;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
    </style>
    <link rel="stylesheet" href="../css/validationEngine.jquery.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6" style="display:flex; justify-content: center;">
                <img src="../images/Copilot_20250701_164820.png" class="img-fluid" style="width: 60%; height: 80%; margin-top:10%" alt="Registration">
            </div>
            <div class="col-md-6">
                <h2 class="text-center mb-1">Register Account</h2>
                <p class="text-center text-muted mb-2">Join us! Please fill in the details below to register.</p>
                
                <div id="alert-container"></div>
                
                <form id="registrationForm" method="POST">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control validate[required,minSize[2]]" id="firstName" name="firstName">
                    </div>

                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control validate[required,minSize[2]]" id="lastName" name="lastName">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control validate[required,custom[email]]" id="email" name="email">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control validate[required,minSize[8]]" id="password" name="password">
                    </div>

                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control validate[required,equals[password]]" id="confirmPassword" name="confirmPassword">
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="registerBtn">
                        <span id="btnText">Create Account</span>
                        <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Required for alert dismissal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Validation Engine -->
    <script src="../js/jquery.validationEngine.js"></script>
    <script src="../js/jquery.validationEngine-en.js"></script>
    <!-- Custom Registration Handler -->
    <script src="../js/2_HandleSignup.js"></script>
</body>
    <?php include "../views/footer.php" ; ?>  
</html>