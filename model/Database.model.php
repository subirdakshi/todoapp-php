<?php

class Database{

    private $hostname;
    private $username;
    private $pass;
    private $dbname;
    private $conn;

    protected function connect()
    {
        // $this->hostname = "localhost";
        // $this->username = "root";
        // $this->pass = "";
        // $this->dbname = "todoapp_php";

        //REMOTE MYSQL
        $this->hostname = "remotemysql.com";
        $this->username = "ZdjHTpvQGW";
        $this->pass = "JL6Ir3uCCb";
        $this->dbname = "ZdjHTpvQGW";

        $this->conn = new Mysqli($this->hostname,$this->username,$this->pass,$this->dbname);

        if(mysqli_errno($this->conn)){
            die('Error : '.$this->conn->connect_error);
        }
        else{
            return $this->conn;
        }
        
    } 

}
    