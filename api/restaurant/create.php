<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate restaurant object
include_once '../objects/restaurant.php';

$database = new Database();
$db = $database->getConnection();

$restaurant = new Restaurant($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->urlName) &&
    !empty($data->address) &&
    !empty($data->password)
) {

    // set restaurant property values
    $restaurant->name = $data->name;
    $restaurant->email = $data->email;
    $restaurant->urlName = $data->urlName;
    $restaurant->address = $data->address;
    $restaurant->password = $data->password;

    // create the restaurant
    if ($restaurant->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Restaurant was created.", "urlName" => $restaurant->urlName));
    } // if unable to create the restaurant, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create restaurant."));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create Restaurant. Data is incomplete."));
}
?>
