<?php
/**
 * Created by PhpStorm.
 * User: valerianhaury
 * Date: 07.08.2020
 */

class Database
{

    // database credentials
    private $host = "aw2g3.myd.infomaniak.com";
    private $db_name = "aw2g3_retrocov";
    private $username = "aw2g3_valerian";
    private $password = "vale19965218";
    /*
     // Local
    private $host = "localhost";
    private $db_name = "aw2g3_retrocov";
    private $username = "root";
    private $password = "";
    public $conn;
*/
    // get the database connection
    public function getConnection()
    {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

?>
