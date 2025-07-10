<?php
session_start() ; 
header('Content-Type: application/json') ;
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Access Denied"]);
    exit ; 
}
include '../modules/db.php' ; 

if (!isset($_POST['csrfToken']) || $_POST['csrfToken'] !== $_SESSION['csrfToken']) {
    echo json_encode(["status" => "error", "message" => "Invalid CSRF token"]);
    exit;
}


// VALLIDATIONS
function sanatize($data) {
    return htmlspecialchars(strip_tags(trim($data))) ; 
}
$name = sanatize($_POST["name"]) ; 
$email = sanatize($_POST["email"]) ; 
$phone = sanatize($_POST["phone"]) ; 
$message = sanatize($_POST["message"]) ; 
$filePath = null ; 

if(empty($name) || empty($email) || empty($phone) || empty($message)) {
    echo json_encode(["status" => "error", "message" => "Please Enter Valid Fields !"]) ; 
    exit ; 
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
    echo json_encode(["status" => "error", "message" => "Invalid Email Format !"]) ; 
    exit ; 
}
if(strlen($phone) < 10 || strlen($phone) > 15 || preg_match('/[a-zA-Z]/', $phone) || preg_match('/[^0-9+]/', $phone)) {
    echo json_encode(["status" => "error", "message" => "Invalid Phone Number !"]) ; 
    exit ; 
}
// FILE HANDLING !
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {                                     // RECIVING FILE
    $allowedExt = ['pdf','docx','xlsx'] ;                                                         // EXTENSIONS ALLOWED 
    $allowedMime = [                                                                              // MIME PROTECTION
        'application/pdf',  
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];
    $fileTmpPath = $_FILES['file']['tmp_name'] ;                                                  // FILE TEMPORARY PATH    
    $fileName = basename($_FILES['file']['name']) ;                                               // FILE NAME
    $fileSize = $_FILES['file']['size'] ; 
    $fileType = mime_content_type($fileTmpPath) ;                                                 // FILE TYPE
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) ;                                  // FILE EXT.

    if(in_array($ext, $allowedExt) && in_array($fileType, $allowedMime) && $fileSize <= 5*1024*1024) {       // FILE VALIDATION BASED ON ENTENSION, MIME, AND SIZE
        $uploadPath = "../uploads/". time() . "$fileName";                                        // SAVING IT IN UPLOADS FOLDER OF SERVER
        if(move_uploaded_file($fileTmpPath, $uploadPath)) {
            $filePath = $uploadPath ; 
        } else {
            echo json_encode(["status" => "error" , "message" => "Failed to upload File !"]) ; 
            exit ; 
        }
    } else {
        echo json_encode(["status" => "error" , "message" => "Invalid File Upload !"]) ; 
        exit ; 
    }
}

// DB LOGIC WITH PREPARED STATEMENTS
$stmt = $connection->prepare("INSERT INTO contact_us (name,email,phone,message,file_path) VALUES(?,?,?,?,?)") ;
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database Error: Prepare failed"]);
    exit;
}



$stmt->bind_param("sssss", $name, $email , $phone, $message, $filePath) ; 

if($stmt->execute()) { 
    $MailContent = "✅ Thanks for reaching out! We've received your message and will be in touch soon." ; 
    include './3_sendMail.php' ; 
    echo json_encode(["status" => "success" , "message" => "Your Message is saved Successfully !"]) ; 
}
else{ 
    http_response_code(500);
    echo json_encode(["status"=>"error", "message" => "Error in saving message in DB !"]) ; 
}

$stmt->close();

?>