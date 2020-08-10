<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/client_trace.php';

// instantiate database and client trace object
$database = new Database();
$db = $database->getConnection();
// initialize object
$clientTrace = new ClientTrace($db);
// query client trace
$stmt = $clientTrace->readAll();
$num = $stmt->rowCount();
// check if more than 0 record found
if ($num > 0) {

    // client trace array
    $clienttrace_arr = array();
    $clienttrace_arr["records"] = array();

    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
        $clienttrace_item = array(
            "id" => $id,
            "date" => $date,
            "tableNumber" => $tableNumber,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "phone" => $phone,
            "postalCode" => $postalCode,
            "restaurantId" => $restaurantId,
            "restaurantName" => $restaurantName
        );
        array_push($clienttrace_arr["records"], $clienttrace_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show client trace data in json format
    echo json_encode($clienttrace_arr);
} else {

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no client traces found
    echo json_encode(
        array("message" => "No client trace found.")
    );
}