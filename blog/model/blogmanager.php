<?php
namespace userblog;

require_once  __DIR__ . '/../../util/autoloader.php';


class BlogManager
{
    private $blog_post;
    private $blog_title;
    private $user_id;
    private $post_data = array();



    public function __construct()
    {
        $this->tbname = "blog_post";
        $this->conn = \Connection::getConnection();
        $this->querybuilder = new \QueryBuilder($this->tbname, $this->conn);
        $this->error = "NO ERROR";
    }

    public function get_error()
    {
        return $this->error;
    }


    public function add_post($post_title, $post_content, $user_id)
    {
        $this->post_data["post_title"] = $post_title;
        $this->post_data["post_content"] = $post_content;
        $this->post_data["user_id"] = $user_id;
        $this->querybuilder->insert($this->post_data);

        if ($this->querybuilder->execute()) {
            return true;
        } else {
            $this->error = "could not insert";
            return false;
        }
    }


    public function delete_post($user_id, $post_id)
    {
        $this->post_data["user_id"] = $user_id;
        $this->post_data["post_id"] = $post_id;

        $this->querybuilder->delete($this->post_data);

        if ($this->querybuilder->execute()) {
            return true;
        } else {
            $this->error = "could not be deleted";
            return false;
        }
    }


    public function update_post($user_id, $post_id, $post_content)
    {
        $field_to_update = array("post_content"=>$post_content);
        $conditions_for_update = array("user_id"=>$user_id, "post_id"=>$post_id);

        $this->querybuilder->update($field_to_update, $conditions_for_update);
        if ($this->querybuilder->execute()) {
            return true;
        } else {
            $this->error = "Could Not Update Post";
            return false;
        }
    }


    public function get_post($post_id) {
        $fetch_condition = array("post_id"=>$post_id);
        $fetch_fields = array("post_content", "user_id", "post_id", "post_title");

        $this->querybuilder->get($fetch_condition, $fetch_fields);
        $data = $this->querybuilder->execute();
        if($data) {
            return $data;
        }else {
            return false;

        }

    }


    public function get_all_post($user_id) {
        $fetch_condition = array("user_id"=>$user_id);
        $fetch_fields = array("post_content", "post_id", "user_id", "post_title");
        $this->querybuilder->getall($fetch_fields,$fetch_condition);
        $data = $this->querybuilder->execute();
        if($data){
            return $data;
        }else {
            $this->error = "No data associated with given user_id";
            return false;
        }
    }
}
?>