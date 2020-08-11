<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

include_once '../objects/restaurant.php';

$database = new Database();
$db = $database->getConnection();

$restaurant = new Restaurant($db);

$data = json_decode(file_get_contents("php://input"));

$restaurant->name = $data->name;
$restaurant_exists = $restaurant->nameExists();

if ($restaurant_exists && $data->password == $restaurant->password) {
    http_response_code(200);

    echo json_encode(array("isLogged" => true, "message" => "Login réussi.", "id" => $restaurant->id));
} else {
    http_response_code(401);
    echo json_encode(array("isLogged" => false, "message" => "Login non-réussi."));
}