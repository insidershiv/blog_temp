<?php

class Connection
{
    private static $conn = null;
    private static $host = "localhost";
    private static $db_name = "blog";
    private static $username = "root";
    private static $password = "";


    private function __construct()
    {
    }

  
    public static function getConnection()
    {
        try {
            if (self::$conn == null) {
                self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
                self::$conn->exec("set names utf8");
            }

            return self::$conn;
        } catch (PDOException $error) {
            die();
        }
    }
}
?>