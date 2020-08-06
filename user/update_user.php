<?php
/**
 * Created by PhpStorm.
 * User: jeffmuraro
 * Date: 29.04.19
 * Time: 21:21
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/user.php';
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$jwt = isset($data->jwt) ? $data->jwt : "";

if($jwt) {

    try {

        $decoded = JWT::decode($jwt, $key, array('HS256'));

        $user->nickname = $data->user_nickname;
        $user->password = $data->user_password;
        $user->id = $decoded->data->id;

        if($user->update()) {

            $token = array(
                "iss" => $iss,
                "aud" => $aud,
                "iat" => $iat,
                "nbf" => $nbf,
                "data" => array(
                    "id" => $user->id,
                    "nickname" => $user->nickname,
                )
            );

            $jwt = JWT::encode($token, $key);

            http_response_code(200);

            echo json_encode(array("message" => "User was updated.", "jwt" => $jwt));

        } else {

            http_response_code(401);

            echo json_encode(array("message" => "Unable to update user."));

        }

    } catch (Exception $e) {

        http_response_code(401);

        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));

    }

} else {

    http_response_code(401);

    echo json_encode(array("message" => "Access denied."));

}