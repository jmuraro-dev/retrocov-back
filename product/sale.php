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
include_once '../objects/product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$stmt = $product->read();
$num = $stmt->rowCount();

if($num > 0) {
    $products_arr = array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $product_item = array(
            'id' => $product_id,
            'name' => $product_name,
            'unit_price' => $product_unit_price,
            'product_unit' => $product_unit,
            'type' => $product_type,
            'stock' => $product_stock,
            'picture' => $product_picture
        );

        array_push($products_arr, $product_item);
    }

    http_response_code(200);

    echo json_encode($products_arr);
} else {
    http_response_code(404);

    echo json_encode(array("message" => "No products found."));
}