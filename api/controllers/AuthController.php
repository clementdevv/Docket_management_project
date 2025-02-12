<?php
require_once './models/User.php';

class AuthController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->username) && !empty($data->email) && !empty($data->password)) {
            $user = new User($this->db);
            $user->username = $data->username;
            $user->email = $data->email;
            $user->password = $data->password;

            if($user->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "User created successfully."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create user."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        }
    }
}