<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use usermodel as model;

require_once '../../util/autoloader.php';

$usermanager = new model\usermanager();

$req_method = $_SERVER["REQUEST_METHOD"];

$res = array("msg"=>" ");


if ($req_method == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $login_credentials = array();
    //echo isset($data["email"]);
    if ((isset($data["email"]) && isset($data["password"]))    &&    strlen($data["email"]) !=0 && strlen($data["password"]) !=0) {
        $email = $data["email"];
        $password = $data["password"];
        $login_credentials["email"] = $email;
        $login_credentials["password"] = $password;
        
        $token = $usermanager->verify_user($login_credentials);
        if ($token) {
            http_response_code(200);
            echo json_encode($token);
        } else {
            http_response_code(400);
            // print_r(($usermanager->get_error()));
            $res = array("msg"=>$usermanager->get_error());
            echo json_encode($res);
        }
    } else {
        http_response_code(422);
        $res = array("msg"=>"Required fields missing");
        echo json_encode($res);
    }
}

?>