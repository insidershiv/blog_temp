<?php

namespace usermodel;

require_once __DIR__.'/../../util/autoloader.php';

class UserManager
{
    private $tbname;
    private $conn;
    private $querybuilder;
    
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
        $this->querybuilder->insert($user);
        $this->querybuilder->execute();
    }


    public function get_user($conditions_for_fetch)
    {
        $fields_to_fetch = array("name","email");
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

    

}
?>