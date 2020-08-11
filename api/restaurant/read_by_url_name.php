<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/restaurant.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare restaurant object
$restaurant = new Restaurant($db);

// set ID property of record to read
$restaurant->urlName = isset($_GET['urlName']) ? $_GET['urlName'] : die();

// read the details of restaurant to be edited
$restaurant->readByUrlName();

if($restaurant->urlName!=null){
    // create array
    $restaurant_arr = array(
        "id" =>  $restaurant->id,
        "name" => $restaurant->name,
        "urlName" => $restaurant->urlName,
        "address" => $restaurant->address,
        "password" => $restaurant->password
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
    echo json_encode(array("message" => "Restaurant does not exist."));
}
?>