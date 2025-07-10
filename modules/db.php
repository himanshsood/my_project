<?php
$servername='localhost'; 
$username='root'; 
$password='' ; 
$database='task'; 

$connection = mysqli_connect($servername,$username,$password,$database) ; 
if(!$connection) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . mysqli_connect_error()
    ]);
    exit;
}
?>