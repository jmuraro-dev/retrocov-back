<?php
/**
 * Created by PhpStorm.
 * User: jeffmuraro
 * Date: 16.04.19
 * Time: 09:27
 */

define('BDD_HOST', 'pous.myd.infomaniak.com');
define('BDD_USER', 'pous_checkout');
define('BDD_PASS', 'cpmj3ff1E97');
define('BDD_NAME', 'pous_checkout');

class Database {

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . BDD_HOST . "; dbname=" . BDD_NAME, BDD_USER, BDD_PASS);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error : " . $exception->getMessage();
        }

        return $this->conn;
    }

}
