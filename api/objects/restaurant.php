<?php

class Restaurant
{
    // database connection and table name
    private $conn;
    private $table_name = "restaurant";

    // object properties
    public $id;
    public $name;
    public $email;
    public $urlName;
    public $address;
    public $password;
    public $admin;
    public $token;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // read restaurants
    function readAll()
    {
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE admin LIKE 0";
        //$query = "SELECT * FROM restaurant";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // used when filling up the update restaurant form
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
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->urlName = $row['urlName'];
        $this->address = $row['address'];
        $this->password = $row['password'];
    }

    // get a restaurant with his complete name
    function readByUrlName()
    {

        // query to read single record
        $query = "SELECT * FROM " . $this->table_name . " r 
            WHERE r.urlName = ? LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->urlName = htmlspecialchars(strip_tags($this->urlName));

        // bind id of restaurant to be updated
        $stmt->bindParam(1, $this->urlName);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->urlName = $row['urlName'];
        $this->address = $row['address'];
        $this->admin = $row['admin'];
    }

    // get a restaurant with his complete email
    function readByEmail()
    {

        // query to read single record
        $query = "SELECT * FROM " . $this->table_name . " r 
            WHERE r.email = ? LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind id of restaurant to be updated
        $stmt->bindParam(1, $this->email);

        // execute query
        $stmt->execute();

        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // set values to object properties
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->urlName = $row['urlName'];
        $this->address = $row['address'];
        $this->admin = $row['admin'];
        $this->token = $row['token'];
    }

    function nameExists()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name LIKE ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));

        $stmt->bindParam(1, $this->name);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->urlName = $row['urlName'];
            $this->admin = $row['admin'];

            return true;
        }

        return false;
    }

    // create restaurant
    function create()
    {

        // query to insert record
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, email=:email, urlName=:urlName, address=:address, password=:password";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->urlName = htmlspecialchars(strip_tags($this->urlName));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":urlName", $this->urlName);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":password", $this->password);

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
        $query = "UPDATE " . $this->table_name . " r
            SET
                r.name = :name,
                r.email = :email,
                r.urlName = :urlName,
                r.address = :address,
                r.password = :password
            WHERE
                r.id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->urlName = htmlspecialchars(strip_tags($this->urlName));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':urlName', $this->urlName);
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

    // update the restaurant
    function updateToken()
    {
        // update query
        $query = "UPDATE " . $this->table_name . " r
            SET
                r.token = :token
            WHERE
                r.id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->token = htmlspecialchars(strip_tags($this->token));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind new values
        $stmt->bindParam(':token', $this->token);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

}
