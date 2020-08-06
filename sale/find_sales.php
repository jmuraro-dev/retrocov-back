<?php
/**
 * Created by PhpStorm.
 * User: jeffmuraro
 * Date: 16.04.19
 * Time: 15:05
 */

// Required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/sale.php';

$database = new Database();
$db = $database->getConnection();

$sale = new Sale($db);

$data = json_decode(file_get_contents("php://input"));

$sale->week = $data->weeks;

$stmt = $sale->findSales();
$num = $stmt->rowCount();

if($num > 0) {
    $sales_arr = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $sale_item = array(
            'product_name' => $product_name,
            'product_unit' => $product_unit,
            'quantity' => $quantity,
            'sale_price' => $sale_price,
            'benefice' => $benef
        );

        array_push($sales_arr, $sale_item);
    }

    http_response_code(200);

    echo json_encode($sales_arr);
} else {
    http_response_code(404);

    echo json_encode(array("message" => "No products found."));
}