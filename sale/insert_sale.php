<?php
/**
 * Created by PhpStorm.
 * User: jeffmuraro
 * Date: 29.04.19
 * Time: 16:36
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/sale.php';

$database = new Database();
$db = $database->getConnection();

$sale = new Sale($db);

$data = json_decode(file_get_contents("php://input"));

var_dump($data);

$sale->product_id = $data->product_id;
$sale->user_id = $data->user_id;
$sale->date = date("Y-m-d");
$sale->quantity = $data->quantity;
$sale->price = $data->price;

if($sale->insert()) {
    http_response_code(200);

    echo json_encode(array("message" => "La vente à bien été ajoutée."));
} else {
    http_response_code(400);

    echo json_encode(array("message" => "La vente n'a pas pu être ajoutée."));
}