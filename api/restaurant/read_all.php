<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/restaurant.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
// initialize object
$restaurant = new Restaurant($db);

// query products
$stmt = $restaurant->readAll();
$num = $stmt->rowCount();
// check if more than 0 record found
if ($num > 0) {

    // restaurants array
    $restaurants_arr = array();
    $restaurants_arr["records"] = array();

    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $restaurant_item = array(
            "id" => $id,
            "name" => $name,
            "address" => $address,
            "password" => $password
        );
        array_push($restaurants_arr["records"], $restaurant_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show restaurants data in json format
    echo json_encode($restaurants_arr);
} else {

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no restaurants found
    echo json_encode(
        array("message" => "No restaurants found.")
    );
}