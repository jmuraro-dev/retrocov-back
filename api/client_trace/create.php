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
include_once '../objects/client_trace.php';

$database = new Database();
$db = $database->getConnection();

$clientTrace = new ClientTrace($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->tableNumber) &&
    !empty($data->firstname) &&
    !empty($data->lastname) &&
    !empty($data->phone) &&
    !empty($data->postalCode) &&
    !empty($data->restaurantId)
) {

    // set restaurant property values
    $clientTrace->date = date('Y-m-d H:i:s');
    $clientTrace->tableNumber = $data->tableNumber;
    $clientTrace->firstname = $data->firstname;
    $clientTrace->lastname = $data->lastname;
    $clientTrace->phone = $data->phone;
    $clientTrace->postalCode = $data->postalCode;
    $clientTrace->restaurantId = $data->restaurantId;

    // create the restaurant
    if ($clientTrace->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Client trace was created."));
    } // if unable to create the restaurant, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create client trace."));
    }
} // tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create client trace. Data is incomplete."));
}
?>
