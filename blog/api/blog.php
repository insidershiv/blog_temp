<?php
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
    if (isset($data["post_title"]) && isset($data["post_content"])) {
        if (isset($header["Authorization"])) {
            $post_title = $data["post_title"];
            $post_content = $data["post_content"];
            $token = $header["Authorization"];
            $decoded_data  = json_decode(Token::verify_token($token), true);
            if ($decoded_data) {
                $id = $decoded_data["data"]["id"];
                if ($blogmanager->add_post($post_title, $post_content, $id)) {
                    return true;
                } else {
                    $res  = array("msg"=> $blogmanager->get_error());
                    echo json_encode($res);
                }
            } else {
                $res = array("msg"=> "Unauthorized Access");
                echo json_encode($res);
            }
        } else {
            $res = array("msg"=> "Authorization Token missing");
            echo json_encode($res);
        }
    } else {
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
    } else {
        $post_id = $params[1];

        if (isset($header["Authorization"])) {
            $token = $header["Authorization"];
            $decoded_data  = json_decode(Token::verify_token($token), true);
            if ($decoded_data) {
                $user_id = $decoded_data["data"]["id"];
                
                if ($blogmanager->delete_post($user_id, $post_id)) {
                    $res = array("msg" => "Post deleted");
                    echo json_encode($res);

                } else {
                    $res = array("msg" => "Could not delete user post");
                    echo json_encode($res);

                }
            } else {
                $res = array("msg"=> "User Not authorized");
                echo json_encode($res);

            }
        } else {
            $res = array("msg"=> "Token missing in the header");
            echo json_encode($res);

        }
    }
   
}

/*  *******************PATCH*******************     */


elseif ($req_method == "PATCH") {
    $header = apache_request_headers();
    
    $post_data = json_decode(file_get_contents('php://input'), true);

    if (isset($post_data["post_content"])) {
        $post_content = $post_data["post_content"];

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
                    $user_id = $decoded_data["data"]["id"];
                    
                    if ($blogmanager->update_post($user_id, $post_id, $post_content)) {
                        $res = array("msg" => "Post updated");
                    } else {
                        $res = array("msg" => "Could not update post");
                    }
                } else {
                    $res = array("msg" => "User Not authorized");
                }
            } else {
                $res = array("msg" => "Token missing in the header");
            }
        }
    } else {
        $res = array("msg" => "please specify Post content");
    }
   
    echo json_encode($res);
}


/* *****************GET******************* */

elseif ($req_method == "GET") {
    if (isset($_GET["post_id"])) {
        $header = apache_request_headers();
        if (isset($header["Authorization"])) {
            $token = $header["Authorization"];
            $decoded_data  = json_decode(Token::verify_token($token), true);
            if ($decoded_data) {
                $user_id = $decoded_data["data"]["id"];


                $data = $_GET["post_id"];
                $data = rtrim($data, '/');
        
       
                $params = explode('/', $data);
                if (count($params) > 2) {
                    http_response_code(404);
                    $res = array("msg" => "Post_id missing");
                } elseif (count($params)==1) {
                    //get agell posts
                   $data = $blogmanager->get_all_post($user_id);
                   if($data){
                       echo($data);
                   }else {
                       echo "error";
                   }

                } else {
                    $post_id = $params[1];
                    $data =  $blogmanager->get_post($post_id);
                    if ($data) {
                       echo($data);
                        $res=array("msg" =>"Data Retrieved");
                    } else {
                        $res = array("msg"=>"could not get Post_data");
                    }
                }
            } else {
                $res = array("msg"=>"Unauthorized Access");
            }
        } else {
            $res = array("msg"=>"Token not set in Header");
        }
    }
    
    echo(json_encode($res));
}
?>