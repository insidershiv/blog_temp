<?php

use usermodel as model;

require_once '../../util/autoloader.php';

$usermanager = new model\usermanager();

$req_method = $_SERVER["REQUEST_METHOD"];

if ($req_method == "POST") {
        $login_credentials = array();
        $email = $_POST["email"];
        $password = $_POST["password"];
        $login_credentials["email"] = $email;
        $login_credentials["password"] = $password;
        $token = $usermanager->verify_user($login_credentials);
        if($token){
            http_response_code(200);
            session_start();
            $_SESSION["token"] = $token;
            print_r($_SESSION["token"]);
            return $token;

        }else {
            http_response_code(400);
            print_r($usermanager->get_error());
        }
    }



?>
