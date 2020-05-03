<?php
namespace userblog;
require_once  __DIR__ . '/../../util/autoloader.php';


class BlogManager {


private $blog_post;
private $blog_title;
private $user_id;



public function __construct() {

    $this->tbname = "posts";
    $this->conn = \Connection::getConnection();
    $this->querybuilder = new \QueryBuilder($this->tbname, $this->conn);
    $this->error = "NO ERROR";


}


public function add_post() {
    
}



    



}







?>