<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/client_trace.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare restaurant object
$clientTrace = new ClientTrace($db);

// set ID property of record to read
$clientTrace->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of restaurant to be edited
$clientTrace->readById();

if($clientTrace->date!=null){
    // create array
    $restaurant_arr = array(
        "id" =>  $clientTrace->id,
        "date" => $clientTrace->date,
        "tableNumber" => $clientTrace->tableNumber,
        "firstname" => $clientTrace->firstname,
        "lastname" => $clientTrace->lastname,
        "phone" => $clientTrace->phone,
        "postalCode" => $clientTrace->postalCode,
        "restaurantId" => $clientTrace->restaurantId
    );

    // set response code - 200 OK
    http_response_code(200);

    // make it in json format
    echo json_encode($restaurant_arr);
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user restaurant does not exist
    echo json_encode(array("message" => "Client trace does not exist."));
}
?>