<?php

use usermodel as model;

require_once '../../util/autoloader.php';

$usermanager = new model\usermanager();

$req_method = $_SERVER["REQUEST_METHOD"];

if ($req_method == "POST") {
        $data = json_decode(file_get_contents('php://input'), true);
        $login_credentials = array();
       
        if(isset($data["email"]) && isset($data["password"])) {
        $email = $data["email"];
        $password = $data["password"];
        $login_credentials["email"] = $email;
        $login_credentials["password"] = $password;
        $token = $usermanager->verify_user($login_credentials);
        if($token){
            http_response_code(200);
            return $token;
        }else {
            http_response_code(400);
            return json_encode(($usermanager->get_error()));
        }

        }else {
            $usermanager->error = "Required fields missing";
        }
        return json_encode(($usermanager->get_error()));
    }

?>
