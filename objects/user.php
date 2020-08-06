<?php
/**
 * Created by PhpStorm.
 * User: jeffmuraro
 * Date: 29.04.19
 * Time: 16:42
 */

class User {

    private $conn;
    private $table_name = "users";

    public $id;
    public $nickname;
    public $password;
    public $is_admin;

    public function __construct($db){
        $this->conn = $db;
    }


    function create() {

        $query = "INSERT INTO " . $this->table_name . " SET user_nickname = :nickname, user_password = :password";

        $stmt = $this->conn->prepare($query);

        $this->nickname = htmlspecialchars(strip_tags($this->nickname));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':nickname', $this->nickname);
        $stmt->bindParam(':password', password_hash($this->password, PASSWORD_BCRYPT));

        if($stmt->execute()) {
            return true;
        }

        return false;

    }

    function nicknameExists()
    {

        $query = "SELECT user_id, user_nickname, user_password, user_is_admin FROM " . $this->table_name . " WHERE user_nickname LIKE ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->nickname = htmlspecialchars(strip_tags($this->nickname));

        $stmt->bindParam(1, $this->nickname);

        $stmt->execute();

        $num = $stmt->rowCount();

        if($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['user_id'];
            $this->nickname = $row['user_nickname'];
            $this->password = $row['user_password'];
            $this->is_admin = $row['user_is_admin'];

            return true;
        }

        return false;
    }

    function update() {

        $password_set = !empty($this->password) ? ", user_password = :password" : "";

        $query = "UPDATE " . $this->table_name . "
            SET
                user_nickname = :nickname
                {$password_set}
            WHERE user_id = :id";

        $stmt = $this->conn->prepare($query);

        $this->nickname=htmlspecialchars(strip_tags($this->nickname));

        $stmt->bindParam(':nickname', $this->nickname);

        if(!empty($this->password)) {

            $this->password=htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);

        }

        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()){

            return true;

        }

        return false;

    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    function readAll() {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

}