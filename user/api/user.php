<?php
use usermodel as model;

require_once __DIR__."/../../util/autoloader.php";

$req_method = $_SERVER["REQUEST_METHOD"];

$usermanager = new model\UserManager();

if ($req_method == "POST") {
    $user_data = array();
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data["name"]) && isset($data["email"]) && isset($data["password"])) {
        $user_data["name"] = $data["name"];
        $user_data["email"] = $data["email"];
        $user_data["password"] = $data["password"];
    
        $search_constraint = array();
        $search_constraint["email"] = $data["email"];


        if ($user_data) {
            if ($usermanager->get_user($search_constraint)) {
                http_response_code(409);                          // response code shows that the server points duplicate data
                $usermanager->error = "user already exist";
            } elseif ($usermanager->add_user($user_data)) {
                $usermanager->error = "User Created";
            } else {
                {
                http_response_code(400);
                $usermanager->error = "User Not Created";
            }
            }
        } else {
            http_response_code(400);
            $usermanager->error = "Specify All Fields";
        }
    } else {
        $usermanager->error = "Required data Missing";
    }
    
    return json_encode(($usermanager->get_error()));
}


/*           GET Method                 */


if ($req_method == "GET") {
    $data = $_GET["id"];
 
    $params = explode('/', $data);
   
   
    if (count($params) >2) {
        http_response_code(404);
        $usermanager->error = "Invalid Address";
    } elseif (count($params) == 2) {
        $id = $params[1];
        $fetch_fields = array("name", "email");
        $fetch_conditions = array("id"=>$id);
    }
}
/*               PATCH                      */

if ($req_method == "PATCH") {
    $id = '';
    $url_data = $_GET["id"];
    $params = explode("/", $url_data);
    if (count($params) >2) {
        http_response_code(404);
        $usermanager->error = "NO such User to update ";
    } elseif (count($params) == 2) {
        $id = $params[1];
    }
   
    if ($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        $password = $data["password"];
        if ($usermanager->update_password($password, $id)) {
            $usermanager->error = "Password Updated";
        } else {
            $usermanager->error = "password Not updated";
        }
    } else {
        $usermanager->error = "User Id Not Specified";
    }
    return json_encode(($usermanager->get_error()));
}
?>