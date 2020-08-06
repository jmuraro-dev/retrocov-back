<?php
/**
 * Created by PhpStorm.
 * User: jeffmuraro
 * Date: 29.04.19
 * Time: 16:59
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
include_once '../objects/user.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

$user->nickname = $data->user_nickname;
$nickname_exists = $user->nicknameExists();

if($nickname_exists && password_verify($data->user_password, $user->password)) {

    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "id" => $user->id,
            "nickname" => $user->nickname,
            "password" => $user->password,
            "is_admin" => $user->is_admin
        )
    );

    http_response_code(200);

    $jwt = JWT::encode($token, $key);

    echo json_encode(array("isLogged" => true, "isAdmin" => $user->is_admin, "message" => "Login réussi.", "jwt" => $jwt, "id" => $user->id));

} else {

    http_response_code(401);

    echo json_encode(array("isLogged" => false, "message" => "Login non-réussi."));

}