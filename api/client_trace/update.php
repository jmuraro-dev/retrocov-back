<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/client_trace.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare restaurant object
$clientTrace = new ClientTrace($db);

// get id of restaurant to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of restaurant to be edited
$clientTrace->id = $data->id;

// set client trace property values
$clientTrace->date = $data->date;
$clientTrace->tableNumber = $data->tableNumber;
$clientTrace->firstname = $data->firstname;
$clientTrace->lastname = $data->lastname;
$clientTrace->phone = $data->phone;
$clientTrace->postalCode = $data->postalCode;
$clientTrace->restaurantId = $data->restaurantId;

// update the restaurant
if ($clientTrace->update()) {

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Client trace was updated."));
} // if unable to update the restaurant, tell the user
else {

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to update the client trace."));
}
?>