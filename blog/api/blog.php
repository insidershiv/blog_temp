<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use userblog as blog;
use usermodel as model;

require_once __DIR__ . "/../../util/autoloader.php";
$blogmanager = new blog\blogmanager();
$usermanager = new model\usermanager();

$res = array("msg" => " ");

$req_method = $_SERVER["REQUEST_METHOD"];
$header = apache_request_headers();

if ($req_method == "POST") {
    $header = apache_request_headers();
    $data = json_decode(file_get_contents('php://input'), true);
    if ((isset($data["post_title"]) && strlen($data["post_title"])!=0)  && (isset($data["post_content"]) && strlen($data["post_content"]) != 0)){
        if (isset($header["Authorization"])) {
            $post_title = $data["post_title"];
            $post_content = $data["post_content"];
            $token = $header["Authorization"];
            $decoded_data  = json_decode(Token::verify_token($token), true);
            if ($decoded_data) {
                $id = $decoded_data["data"]["user_id"];
                if ($blogmanager->add_post($post_title, $post_content, $id)) {
                    $res =  array("msg"=>"post created");
                    echo json_encode($res);
                   
                } else {
                    
                    http_response_code(400);
                    $res  = array("msg"=> $blogmanager->get_error());
                    echo json_encode($res);
                }
            } else {
                http_response_code(401);
                $res = array("msg"=> "Unauthorized Access");
                echo json_encode($res);
            }
        } else {
            http_response_code(401);
            $res = array("msg"=> "Authorization Token missing");
            echo json_encode($res);
        }
    } else {
        http_response_code(401);
        $res = array("msg"=> "Please specify required inputs");
        echo json_encode($res);
    }
   
    
}


/***************** DELETE REQUEST*************** */


elseif ($req_method == "DELETE") {
    $header = apache_request_headers();
    $data = $_GET["post_id"];
    $params = explode('/', $data);
    
   
    if (count($params) !=2) {
        http_response_code(404);
        $res = array("msg"=> "Post_id missing");
        echo json_encode($res);
    } else {
        $post_id = $params[1];

        if (isset($header["Authorization"])) {
            $token = $header["Authorization"];
            $decoded_data  = json_decode(Token::verify_token($token), true);
            if ($decoded_data) {
                $user_id = $decoded_data["data"]["user_id"];
                
                if ($blogmanager->delete_post($user_id, $post_id)) {
                    http_response_code(200);
                    $res = array("msg" => "Post deleted");
                    echo json_encode($res);

                } else {
                    http_response_code(400);
                    $res = array("msg" => "Could not delete user post");
                    echo json_encode($res);

                }
            } else {
                http_response_code(401);
                $res = array("msg"=> "User Not authorized");
                echo json_encode($res);

            }
        } else {
            http_response_code(400);
            $res = array("msg"=> "Token missing in the header");
            echo json_encode($res);

        }
    }
   
}

/*  *******************PATCH*******************     */


elseif ($req_method == "PATCH") {
    $header = apache_request_headers();
    
    $post_data = json_decode(file_get_contents('php://input'), true);

    if (isset($post_data["post_content"]) && isset($post_data["post_title"])) {
        $post_content = $post_data["post_content"];
        $post_title   = $post_data["post_title"];
        $data = $_GET["post_id"];
        $params = explode('/', $data);
        if (count($params) !=2) {
            http_response_code(404);
            $res = array("msg"=> "Post_id missing");
        } else {
            $post_id = $params[1];
           
            if (isset($header["Authorization"])) {
                $token = $header["Authorization"];
                $decoded_data  = json_decode(Token::verify_token($token), true);
                if ($decoded_data) {
                    $user_id = $decoded_data["data"]["user_id"];
                    
                    if ($blogmanager->update_post($user_id, $post_id, $post_content,$post_title)) {
                        $res = array("msg" => "Post updated");
                        echo json_encode($res);
                    } else {
                        http_response_code(400);
                        $res = array("msg" => "Could not update post");
                        echo json_encode($res);
                    }
                } else {
                    http_response_code(401);
                    $res = array("msg" => "User Not authorized");
                    echo json_encode($res);
                }
            } else {
                http_response_code(400);
                $res = array("msg" => "Token missing in the header");
                echo json_encode($res);
            }
        }
    } else {
        http_response_code(422);
        $res = array("msg" => "please specify Post content");
        echo json_encode($res);
    }
   
   
}


/* *****************GET******************* */

elseif ($req_method == "GET") {
    if (isset($_GET["post_id"])) {
        $header = apache_request_headers();
        if (isset($header["Authorization"])) {
            $token = $header["Authorization"];
            $decoded_data  = json_decode(Token::verify_token($token), true);
            if ($decoded_data) {
                
                $user_id = $decoded_data["data"]["user_id"];


                $data = $_GET["post_id"];
                $data = rtrim($data, '/');
        
       
                $params = explode('/', $data);
                if (count($params) > 2) {
                    // invalid parameters more than 2 
                    http_response_code(404);
                    $res = array("msg" => "Post_id missing");
                    echo json_encode($res);
                } elseif (count($params)==1) {
                    //get agell posts
                   $data = $blogmanager->get_all_post($user_id);
                   $res = $data ? $data : array();
                   echo json_encode($res);
                   
                } else {
                    $post_id = $params[1];
                    $data =  $blogmanager->get_post($post_id);
                    
                    if ($data) {
                        http_response_code(200);
                        echo json_encode($data);
                       
                        
                    } else {
                        http_response_code(404);
                        $res = array("msg"=>"No such blog exists");
                        echo json_encode($res);
                    }
                }
            } else {
                http_response_code(401);
                $res = array("msg"=>"Unauthorized Access");
                echo json_encode($res);
            }
        } else {
            $res = array("msg"=>"Token not set in Header");
            echo json_encode($res);
        }
    }
    
   
}
?>