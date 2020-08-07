<?php

class Restaurant
{
    // database connection and table name
    private $conn;
    private $table_name = "restaurant";

    // object properties
    public $id;
    public $name;
    public $address;
    public $password;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read restaurants
    function readAll()
    {
        // select all query
        $query = "SELECT * FROM " . $this->table_name;
        //$query = "SELECT * FROM restaurant";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // used when filling up the update product form
    function readById()
    {

        // query to read single record
        $query = "SELECT * FROM " . $this->table_name . " r 
            WHERE r.id = ? LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->name = $row['name'];
        $this->address = $row['address'];
        $this->password = $row['password'];
    }

    // create restaurant
    function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, address=:address, password=:password";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":password", $this->password);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;

    }

    // update the product
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " r
            SET
                r.name = :name,
                r.address = :address,
                r.password = :password
            WHERE
                r.id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // delete the restaurant
    function delete()
    {

        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        // bind id of record to delete
        $stmt->bindParam(':id', $this->id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

}