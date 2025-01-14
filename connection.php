<?php

class Database{
    private $host = "localhost";
    private $username = "root";
    private $passwd = null;
    private $dbname = "estock";
    protected $conn;

    public function __construct()
    {
        try{
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname",$this->username,$this->passwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $ex){
            die("Connection failed ".$ex->getMessage());
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    protected function executeQuery($query,$params=[]){
        try{
           $stmt = $this->conn->prepare($query);
           $stmt->execute($params);
           return $stmt;
        }catch(PDOException $ex){
            echo "Prepared query failed ".$ex->getMessage();
        }
    }

    protected function closeConnecton(){
        $this->conn = null;
    }
}

