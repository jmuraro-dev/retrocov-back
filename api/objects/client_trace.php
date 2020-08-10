<?php

class ClientTrace
{
    // database connection and table name
    private $conn;
    private $table_name = "clienttrace";

    // object properties
    public $id;
    public $date;
    public $tableNumber;
    public $firstname;
    public $lastname;
    public $phone;
    public $postalCode;
    public $restaurantId;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read all client trace
    function readAll()
    {
        // select all query
        //$query = "SELECT * FROM clienttrace";
        $query = "SELECT
c.id, c.date, c.tableNumber, c.firstname, c.lastname, c.phone,
c.postalCode, c.restaurantId, r.name as restaurantName FROM `clienttrace` as c, `restaurant` as r 
WHERE r.id = c.restaurantId";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // used when filling up the update restaurant form
    function readByRestaurantId()
    {

        // query to read trace depending of the restaurant id
        $query = "SELECT 
c.id, c.date, c.tableNumber, c.firstname, c.lastname, c.phone,
c.postalCode, c.restaurantId, r.name as restaurantName FROM `clienttrace` as c, `restaurant` as r 
WHERE r.id = c.restaurantId AND c.restaurantId = ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of restaurant to be updated
        $stmt->bindParam(1, $this->restaurantId);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function readById()
    {

        // query to read single record
        $query = "SELECT * FROM " . $this->table_name . " r 
            WHERE r.id = ? LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of restaurant to be updated
        $stmt->bindParam(1, $this->id);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->date = $row['date'];
        $this->tableNumber = $row['tableNumber'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->phone = $row['phone'];
        $this->postalCode = $row['postalCode'];
        $this->restaurantId = $row['restaurantId'];
    }


    // create client trace
    function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                date=:date, tableNumber=:tableNumber, firstname=:firstname, lastname=:lastname,
                phone=:phone, postalCode=:postalCode, restaurantId=:restaurantId";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->tableNumber = htmlspecialchars(strip_tags($this->tableNumber));
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->postalCode = htmlspecialchars(strip_tags($this->postalCode));
        $this->restaurantId = htmlspecialchars(strip_tags($this->restaurantId));

        //print_r($this);

        // bind values
        $stmt->bindParam(":date", $this->date);
        $stmt->bindParam(":tableNumber", $this->tableNumber);
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":postalCode", $this->postalCode);
        $stmt->bindParam(":restaurantId", $this->restaurantId);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;

    }

    // update the restaurant
    function update()
    {

        // update query
        $query = "UPDATE " . $this->table_name . " c
            SET
                c.date = :date,
                c.tableNumber = :tableNumber,
                c.firstname = :firstname,
                c.lastname = :lastname,
                c.phone = :phone,
                c.postalCode = :postalCode,
                c.restaurantId = :restaurantId
            WHERE
                c.id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->date = htmlspecialchars(strip_tags($this->date));
        $this->tableNumber = htmlspecialchars(strip_tags($this->tableNumber));
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->postalCode = htmlspecialchars(strip_tags($this->postalCode));
        $this->restaurantId = htmlspecialchars(strip_tags($this->restaurantId));

        // bind new values
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':tableNumber', $this->tableNumber);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':postalCode', $this->postalCode);
        $stmt->bindParam(':restaurantId', $this->restaurantId);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // delete the client trace
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
