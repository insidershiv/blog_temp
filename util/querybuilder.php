<?php

class QueryBuilder
{
    public $query = '';          //Will Contain the Query to be Executed
    private $tbname;              //Name of the table
    private $conn=null;           //DB Connection
    private $is_insert = false;   // If called function is INSERT Operation
    private $data;                //Stores parameter given to be globally Accessible
    private $is_get = false;      //If called function is Get Operation
    private $is_getall = false;   //If called function is GetAll Operation
    private $is_delete = false;   //If called function is DELETE Opereation
    private $is_update = false;

    /*

    @param : $tbaname = Table Name
             $conn    = DataBase Connection
    */
    public function __construct($tbname, $conn)
    {
        $this->tbname = $tbname;
        $this->conn = $conn;
    }


   

    /* ************* insert **************

    FUNCTION------------> INSERTS The Row To The Table as per given Parameter


      ########## @params ###############


    ----- Mandatory Paramater ------
    @data ::::::: ARRAY (ASSOCIATIVE ARRAY) of fields name and its value

    */

    public function insert($data)
    {
        $this->data = $data;
        $keys = array_keys($data);
        $this->query = "INSERT INTO $this->tbname ( ";
        foreach ($keys as $key) {
            $this->query = $this->query . $key . ' , ';
        }
        $this->query = rtrim($this->query, ', ');
        $this->query = $this->query . " )  VALUES ( ";
        foreach ($keys as $key) {
            $this->query = $this->query . ' ' .    ':' . $key  . " , "  ;
        }
        $this->query = rtrim($this->query, ', ');
        $this->query = $this->query .  " ) ";
        $this->is_insert = true;
    }





    /* ************* get **************

    FUNCTION------------> Fetches The Rows From The Table as per given Parameter


      ########## @params ###############



    ----- Optinal Paramater ------

    @fetch_fields ::::::: ARRAY () of columns you want to get

    IF Parameter Given :::::=> fetches the specified columns given in the ARRAY

    IF Parameter is NOT Given ::::=>  Fetches all the columns


    ------- Mandatory Parameter ----------

    @conditions_for_fetch :::::: ARRAY() of WHERE CLAUSE Conditions
    */



    public function get($conditions_for_fetch, $fetch_fields = array())
    {
        $this->data = $conditions_for_fetch;
       
        $this->query = "SELECT ";
  
        if (count($fetch_fields)==0) {
            $this->query = $this->query . " * ";
        } else {
            foreach ($fetch_fields as $field) {
                $this->query = $this->query . $field . ' , ' ;
            }

            $this->query = rtrim($this->query, ', ');
        }
        $this->query = $this->query . " FROM $this->tbname WHERE ";

        $keys = array_keys($conditions_for_fetch);
        foreach ($keys as $key) {
            $this->query= $this->query . $key . " = " . ":" . $key . " AND " ;
        }

        $this->query = rtrim($this->query, ' AND ');

        $this->is_get = true;
    }


   

    /* *************************** getAll ******************************

    FUNCTION------------> Fetches All The Rows From The Table


        ########################## @params #####################



      ----- Optinal Paramater ------
      @fetch_fields ::::::: ARRAY ()

      IF Parameter Given :::::=> fetches the specified columns given in the ARRAY

      IF Parameter is NOT Given ::::=>  Fetches all the columns



    */
    public function getall($fetch_fields = array())
    {
        $this->query = "SELECT ";
       

        if (count($fetch_fields)==0) {
            $this->query = $this->query . " * ";
        } else {
            foreach ($fetch_fields as $field) {
                $this->query = $this->query . $field . ' , ' ;
            }

            $this->query = rtrim($this->query, ', ');
        }
        $this->query = $this->query . " FROM " . $this->tbname;
        $this->is_getall = true;
    }



    /* *************** DELETE ******************************

    FUNCTION---------------------->Deletes the Row as per given Parameter
       ###### @params #########

       ------- @Mandatory Parameter ----------

       @fields::::::: Array(ASSOCIATIVE ARRAY) :::=> WHERE CLAUSE of Conditions to delete


    */
    public function delete($fields)
    {
        $this->data = $fields;
        $this->query = $this->query . "DELETE FROM $this->tbname WHERE ";
        $keys = array_keys($fields);
        foreach ($keys as $key) {
            $this->query = $this->query . $key . " = " . ":" . $key  . " AND ";
        }
        $this->query = rtrim($this->query, "AND ");
      
        $this->is_insert = false;
        $this->bindparam = true;
        $this->is_delete = true;
    }



    /* *************** UPDATE ******************************

      FUNCTION---------------------->Updates the Row as per given Parameter
      ###### @params #########

      ------- @Mandatory Parameter ----------

      @columns_to_update ::::::: Array(ASSOCIATIVE ARRAY) :::=> Field names which are to be updated
      @conditions_to_update::: Array(ASSOCIATIVE ARRAY) :::=>   WHERE CLAUSE Conditions


    */

    public function update($columns_to_update, $conditions_to_update)
    {
        $this->data = array_merge($columns_to_update, $conditions_to_update);

        $keys = array($conditions_to_update);

       
        $this->query = $this->query . "UPDATE $this->tbname SET ";
        $keys = array_keys($columns_to_update);

        foreach ($keys as $key) {
            $this->query = $this->query . $key . " = " . ":" .$key . " , " ;
        }
        $this->query = rtrim($this->query, ", ") ;
       
        $this->query = $this->query . " WHERE ";
        $keys = array_keys($conditions_to_update);
        foreach ($keys as $key) {
            $this->query = $this->query . $key . " = " . ":" . $key . " AND ";
        }
        $this->query = rtrim($this->query, " AND ");
        $this->is_update = true;
    }




    /* *************** EXCEUTE  ******************************

    FUNCTION---------------------->This Function Executes the QUERY


    */
    public function execute()
    {
        $stmt = $this->conn->prepare($this->query);

        if ($this->is_insert) {
            $stmt->execute($this->data);
            if ($stmt->rowCount()) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($this->is_get) {
                $stmt->execute($this->data);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($result) {
                    return $result;
                } else {
                    return false;
                }
            }
            if ($this->is_delete) {
                $stmt->execute($this->data);
                $row = $stmt->rowCount();
                if ($row) {
                    return true;
                } else {
                    return false;
                }
            }
            if ($this->is_getall) {
                $stmt->execute($this->data);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($result) {
                    return $result;
                } else {
                    return false;
                }
            }
            if ($this->is_update) {
                $stmt->execute($this->data);
                if ($stmt->rowCount()) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}
?>