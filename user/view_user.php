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
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$stmt = $user->readAll();
$num = $stmt->rowCount();

if($num > 0) {
    $users_arr = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $user_item = array(
            'id' => $user_id,
            'nickname' => $user_nickname,
            'is_admin' => $user_is_admin
        );

        array_push($users_arr, $user_item);
    }

    http_response_code(200);

    echo json_encode($users_arr);
} else {
    http_response_code(404);

    echo json_encode(array("message" => "No users found."));
}