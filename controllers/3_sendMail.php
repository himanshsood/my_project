<?php
    use PHPMailer\PHPMailer\PHPMailer ; 
    use PHPMailer\PHPMailer\Exception ; 
    require '../vendor/autoload.php' ;
    $mail = new PHPMailer(true) ; 

    try {
        $mail->isSMTP() ; 
        $mail->Host = 'em2.pwh-r1.com' ; 
        $mail->SMTPAuth = true ;
        $mail->Username = 'gouravgarg2@orientaloutsourcing.com' ;
        $mail->Password = 'JaiMataDi123#' ; 
        $mail->SMTPSecure = 'SSL' ; 
        $mail->Port = 587 ;
        $mail->setFrom('gouravgarg2@orientaloutsourcing.com', 'Gourav Garg') ; 
        $mail->addAddress("$email","$name") ; 
        if(!empty($filePath) && file_exists($filePath)) {
            $mail->addAttachment($filePath) ; 
        }
        $mail->isHTML(true) ; 
        $mail->Subject = "From Oriental Outsourcing !" ; 
        $mail->Body = "$MailContent" ; 

        $mail->send() ; 
    } catch (Exception $e) {
        echo "<script>console.log('Mailer Error: {$mail->ErrorInfo}');</script>" ;
    }
?>