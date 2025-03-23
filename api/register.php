<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST"); // Allow only POST
header("Access-Control-Allow-Headers: Content-Type");

require 'connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Ensure the request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => 0, "message" => "Invalid request method."]);
    exit;
}

// ✅ Get raw POST data (fix for missing $_POST issue)
$postData = json_decode(file_get_contents("php://input"), true);

// ✅ Fallback if form data is sent normally
if (!$postData) {
    $postData = $_POST;
}

// ✅ Validate input (check if values exist)
if (!isset($postData['username'], $postData['fname'], $postData['sname'], $postData['email'], $postData['contact'], $postData['password'])) {
    echo json_encode(["success" => 0, "message" => "Missing required fields."]);
    exit;
}

// ✅ Retrieve and sanitize data
$username = mysqli_real_escape_string($con, $postData['username']);
$fname = mysqli_real_escape_string($con, $postData['fname']);
$sname = mysqli_real_escape_string($con, $postData["sname"]);
$email = mysqli_real_escape_string($con, $postData["email"]);
$contact = mysqli_real_escape_string($con, $postData["contact"]);
$password = md5($postData["password"]);

// ✅ Check if the username already exists
$checkQuery = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
if (mysqli_num_rows($checkQuery) > 0) {
    echo json_encode(["success" => 0, "message" => "Username already exists."]);
    exit;
}

// ✅ Insert user data into the database
$query = "INSERT INTO users (username, fname, sname, email, contact, password, created_at) 
          VALUES ('$username', '$fname', '$sname', '$email', '$contact', '$password', NOW())";

if (!mysqli_query($con, $query)) {
    echo json_encode(["success" => 0, "message" => "Database Error: " . mysqli_error($con)]);
} else {
    echo json_encode(["success" => 1, "message" => "User registered successfully."]);
}

// ✅ Close the database connection
mysqli_close($con);
?>
