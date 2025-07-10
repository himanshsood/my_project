<?php 
    $currentPage = basename($_SERVER['REQUEST_URI']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> 
        .nav-link:hover {
            cursor:pointer ; 
            transition: 0.3s ; 
        }
        @media screen and (max-width: 342px) {
            #img {
                width: 60%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-light mb-0">
        <div class="container-fluid">
            <img src="https://orientaloutsourcing.com/wp-content/uploads/2020/12/logo-dark.png" alt="" id='img'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                 <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="/my_project/task8/index.php#Home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="/my_project/task8/index.php#About">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="/my_project/task8/index.php#Services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?>" href="/my_project/task8/pages/contact.php"> Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'register.php') ? 'active' : ''; ?>" href="/my_project/task8/pages/register.php">Registration</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'login.php') ? 'active' : ''; ?>" href="/my_project/task8/pages/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($currentPage == 'logout.php') ? 'active' : ''; ?>" href="/my_project/task8/pages/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>