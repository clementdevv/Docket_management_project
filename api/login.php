<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once 'connect.php';
session_start();

// Debugging: Log received data
error_log("Received POST Data: " . print_r($_POST, true));

// Retrieve data safely
$username = isset($_POST["username"]) ? trim($_POST["username"]) : null;
$password = isset($_POST["password"]) ? md5(trim($_POST["password"])) : null;

if (!$username || !$password) {
    echo json_encode([
        "code" => 0,
        "message" => "Username and password are required.",
    ]);
    exit;
}

// Debugging: Check database connection
if (!$con) {
    echo json_encode([
        "code" => 0,
        "message" => "Database connection failed: " . mysqli_connect_error()
    ]);
    exit;
}

// Query the database securely using prepared statements
$query = mysqli_prepare($con, "SELECT ID, fname, sname, email, contact FROM users WHERE username = ? AND password = ?");
mysqli_stmt_bind_param($query, "ss", $username, $password);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    
    echo json_encode([
        "code" => 1,
        "message" => "Login successful.",
        "userdetails" => $user
    ]);
} else {
    echo json_encode([
        "code" => 0,
        "message" => "Invalid username or password."
    ]);
}

mysqli_close($con);
?>
