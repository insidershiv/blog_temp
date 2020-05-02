<?php

namespace usermodel;

require_once __DIR__.'/../../util/autoloader.php';
require_once  __DIR__ . "/../../vendor/autoload.php";

use \Firebase\JWT\JWT;

class UserManager
{
    private $tbname;
    private $conn;
    private $querybuilder;
    private $secret_key = "thisismykey";
    
    public function __construct()
    {
        $this->tbname = "user";
        $this->conn  = \Connection::getConnection();
        
        $this->querybuilder = new \QueryBuilder($this->tbname, $this->conn);
        $this->error = "No Error";
    }

    /*
      *** Error Function TO get the error if operation is not performed ***

    */
    public function get_error()
    {
        return $this->error;
    }

    


    public function add_user($user)
    {
        $user["password"] = password_hash($user["password"], PASSWORD_DEFAULT);
        $exits = array("email"=>$user["email"]);
        if ($this->get_user($exits)) {
            $this->error = "user already exists";
            return false;
        } else {
        }
        $this->querybuilder->insert($user);
        if ($this->querybuilder->execute()) {
            return true;
        } else {
            $this->error = "user could not be created";
            return false;
        }
    }


    public function get_user($conditions_for_fetch)
    {
        $fields_to_fetch = array("name","email", "password", "id", "is_active");
        $this->querybuilder->get($conditions_for_fetch, $fields_to_fetch);
        $result = $this->querybuilder->execute();
        if ($result) {
            return $result;
        } else {
            $this->error="user does not exist";
            return false;
        }
    }


    public function get_all_user()
    {
        $this->querybuilder->getall();
        $result = $this->querybuilder->execute();
        if ($result) {
            return $result;
        } else {
            $this->error="Could Not Get User";
            return false;
        }
    }

    public function delete_user($condtions)
    {
        $this->querybuilder->delete($condtions);
        $result = $this->querybuilder->execute();
        if ($result) {
            return true;
        } else {
            $this->error="could not delete";
        }
    }

    public function verify_user($user)
    {
        $fetch_condition = array();
        if ($user["email"]) {
            $fetch_condition["email"] = $user["email"];
        } else {
            $fetch_condition["id"] = $user["id"];
        }
        $this->querybuilder->get($fetch_condition);
        $result = $this->querybuilder->execute();
       
        if ($result) {
            $password = $user["password"];
            if (password_verify($password, $result["password"])) {
                if ($result["is_active"]) {
                    $token_data = array("id"=>$result["id"], "email"=>$result["email"]);
                    $token = \Token::generate_token($token_data);
                    if ($token) {
                        return $token;
                    } else {
                        $this->error = "Token could not be created";
                    }
                } else {
                    $this->error = "User not active";
                }
            } else {
                $this->error = "password Mismatch";
                return false;
            }
        } else {
            $this->error = "User Does Not exist";
            return false;
        }
    }


    public function update_password($user_password, $user_constraint)
    {
        $password = array();
        $user_password = password_hash($user_password, PASSWORD_DEFAULT);
        $password["password"] = $user_password;
        $user_constraint = array("id"=>$user_constraint);
        $this->querybuilder->update($password, $user_constraint);
        if ($this->querybuilder->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>