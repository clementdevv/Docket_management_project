<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once './config/database.php';
include_once './controllers/AuthController.php';

$database = new Database();
$db = $database->getConnection();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Basic routing
switch($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if($uri[2] == 'register') {
            $auth = new AuthController($db);
            $auth->register();
        }
        elseif($uri[2] == 'login') {
            $auth = new AuthController($db);
            $auth->login();
        }
        break;
}