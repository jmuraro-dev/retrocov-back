<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/restaurant.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare restaurant object
$restaurant = new Restaurant($db);

// get id of restaurant to be edited
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->id) &&
    !empty($data->token) &&
    !empty($data->password)
) {

// set ID property of restaurant to be edited
    $restaurant->id = $data->id;
    $restaurant->token = $data->token;
    $restaurant->password = $data->password;


// update the restaurant
    if ($restaurant->updatePassword()) {

        // set response code - 200 ok
        http_response_code(200);

        // tell the user
        echo json_encode(
            array("message" => "Restaurant password was updated.")
        );
    } // if unable to update the restaurant, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to update restaurant password."));
    }
} else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to update Restaurant. Data is incomplete."));
}
?>