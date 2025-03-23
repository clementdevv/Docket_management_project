<?php
$host = "localhost"; // Define the host before using it
$user = "root"; 
$password = ""; 
$db = "law_firm";  // Ensure database name is correct

$con = mysqli_connect($host, $user, $password, $db);

// Check connection
if (!$con) {
    die(json_encode([
        "code" => 0,
        "message" => "Database connection failed: " . mysqli_connect_error()
    ]));
}
?>
