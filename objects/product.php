<?php
/**
 * Created by PhpStorm.
 * User: jeffmuraro
 * Date: 16.04.19
 * Time: 09:35
 */

class Product {

    private $conn;
    private $table_name = "products";

    // Object properties
    public $id;
    public $name;
    public $description;
    public $unit_price;
    public $type;
    public $stock;
    public $picture;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY product_type";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE product_id = ?";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

}