<?php

/**
 * Created by PhpStorm.
 * User: jeffmuraro
 * Date: 21.05.2019
 * Time: 09:00
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/product.php';
include_once '../config/core.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$data = json_decode(file_get_contents("php://input"));

$product->id = $data->product_id;

if ($product->delete()) {
    http_response_code(200);

    echo json_encode(array("message" => "Le produit a bien été supprimé."));
} else {
    http_response_code(503);

    echo json_encode(array("message" => "Le produit ne peut pas être supprimé."));
}