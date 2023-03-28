<?php

class Database {

    protected $connection;
    public function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection(){
        return $this->connection;
    }
}

?>