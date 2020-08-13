<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once '../config/database.php';

include_once '../objects/restaurant.php';

$database = new Database();
$db = $database->getConnection();

$restaurant = new Restaurant($db);

$data = json_decode(file_get_contents("php://input"));

$restaurant->name = $data->name;
$restaurant_exists = $restaurant->nameExists();
$data->password = htmlspecialchars(strip_tags($data->password));

if ($restaurant_exists && $data->password == $restaurant->password) {
    http_response_code(200);
    echo json_encode(array("isLogged" => true, "message" => "Login réussi.", "urlName" => $restaurant->urlName, "isAdmin" => $restaurant->admin));
} else {
    http_response_code(401);

    echo json_encode(array("isLogged" => false, "message" => "Login non-réussi."));
}
