<?php
require_once "autoloader.php";
require __DIR__ . "/../vendor/autoload.php";
use \Firebase\JWT\JWT;

class Token
{
    private static $key = "thisismykey";

    private function __construct()
    {
    }

    // To verify if the token is valid for the current user
    public static function verify_token($authHeader)
    {
        $arr = explode(" ", $authHeader);
        $jwt = $arr[1];
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, self::$key, array('HS256'));
            } catch (Exception $e) {
                return false;
            }
        } else {
            return false;
        }
        return json_encode($decoded);
    }

    /*
        Function ::=> Genarates JWT Token

        @Parameter
            token_data => ASSOCIATIVE Array ::=> Data which is encoded as payload in JWT Token


    */
    public static function generate_token($token_data)
    {
        $token = array("iat"=>time());
        $data = $token_data;
        $token["data"] = $data;


        $jwt = JWT::encode($token, self::$key);

        $result = array();

        $keys = array_keys($token_data);
        foreach ($keys as $key) {
            $result[$key] = $data[$key];
        }

        $result["jwt"] = $jwt;

        return json_encode(
            $result
        );
    }
}
?>