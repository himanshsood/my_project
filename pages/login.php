<?php include '../views/navbar.php' ; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .construction-container {
            max-width: 600px;
            margin: 100px auto;
            text-align: center;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .im {
            height: 220px;
            width: 220px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="construction-container">
            <img src="../images/pngimg.com - under_construction_PNG46.png" alt="Page Under Construction" class="im">
            <p class="lead mb-4">The login page is currently being developed and will be available soon.</p>
            <p class="text-muted">Thank you for your patience!</p>
            
            <div class="mt-4">
                <a href="/my_project/task8/pages/register.php" class="btn btn-primary me-2">Back to Registration</a>
                <a href="/my_project/task8/index.php#Home" class="btn btn-outline-secondary">Go to Homepage</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include '../views/footer.php' ; ?>
</html>