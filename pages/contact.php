<?php
    session_start();
    $loggedIn = false ; 
    include '../views/navbar.php' ;
    // CREATE A CSRF TOKEN 
    if(empty($_SESSION['csrfToken'])) {
        $_SESSION['csrfToken'] = bin2hex(random_bytes(32)) ; 
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contatc-us</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./validationEngine.jquery.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../js/jquery.validationEngine.js"></script>
        <script src="../js/jquery.validationEngine-en.js"></script>
        <script src="../js/1_HandleContactUs.js"></script>
        <style>
            .span {
                color : red ; 
                display : none ; 
            }
            .display {
                display : inline ; 
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <img src="https://orientaloutsourcing.com/images/contact.png" class="img-fluid mb-3" alt="Contact">
                    <h5>Email: <a href="mailto:gouravgarg2@orientaloutsourcing.com">gouravgarg2@orientaloutsourcing.com</a></h5>
                    <h5>Phone: <a href="tel:1234567890">123-456-7890</a></h5>
                </div>
                <div class="col-md-6">
                    <!-- <?php if ($success): ?>
                        <div class="alert alert-success">Your message has been sent successfully!</div>
                    <?php endif; ?> -->

                
                    <form id="contactUsForm" action="submit_contact.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="csrfToken" name="csrfToken" value="<?php echo $_SESSION['csrfToken'] ;?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control validate[required]" id="name" name="name">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control validate[required,custom[email]]" id="email" name="email">
                        </div>
                    
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control validate[required,custom[phone],minSize[10],maxSize[15]]" id="phone" name="phone">
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control validate[required]" id="message" name="message" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">Upload File</label>
                            <input type="file" class="validate[required]" id="file" name="file" accept=".pdf,.docx,.xlsx">
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top:15px;">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <?php include "../views/loader.php" ; ?>  
    <?php include "../views/footer.php" ; ?>  
</html>