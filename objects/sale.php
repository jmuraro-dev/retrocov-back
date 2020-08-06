<?php
/**
 * Created by PhpStorm.
 * User: jeffmuraro
 * Date: 16.04.19
 * Time: 09:35
 */

class Sale {

    private $conn;
    private $table_name = "sales";

    // Object properties
    public $user_id;
    public $product_id;
    public $date;
    public $quantity;
    public $price;
    public $week;

    public function __construct($db) {
        $this->conn = $db;
    }

    function insert() {
        $query = "INSERT INTO " . $this->table_name . " SET sale_date = now(), sale_quantity = :quantity, sale_price = :price, product_id = :product_id, user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam('quantity', $this->quantity);
        $stmt->bindParam('price', $this->price);
        $stmt->bindParam('product_id', $this->product_id);
        $stmt->bindParam('user_id', $this->user_id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    function findSales() {
        $query = "SELECT product_name, SUM(sale_quantity) AS quantity, sale_price, SUM(sale_quantity) * sale_price AS benef, product_unit  FROM " . $this->table_name . " INNER JOIN products ON sales.product_id = products.product_id WHERE WEEK(sale_date, 5) LIKE ? GROUP BY product_name";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->week);

        $stmt->execute();

        return $stmt;
    }
}