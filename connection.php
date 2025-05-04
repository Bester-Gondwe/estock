<?php

class Database
{
    private $host = "localhost";
    private $username = "root";
    private $passwd = null;
    private $dbname = "estock";
    protected $conn;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->passwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            die("Database connection failed: " . $ex->getMessage());
        }
    }

    // Get the current connection instance
    public function getConnection()
    {
        return $this->conn;
    }

    // Query the database and return the result
    public function query($sql)
    {
        try {
            return $this->conn->query($sql);
        } catch (PDOException $ex) {
            echo "Query error: " . $ex->getMessage();
            return false;
        }
    }

    // Prepare a statement
    public function prepare($sql)
    {
        try {
            return $this->conn->prepare($sql);
        } catch (PDOException $ex) {
            echo "Prepare error: " . $ex->getMessage();
            return false;
        }
    }

    // Execute a prepared query with parameters
    protected function executeQuery($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $ex) {
            echo "Execution error: " . $ex->getMessage();
            return false;
        }
    }

    // Fetch all results from a query
    public function fetchAll($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            echo "Fetch error: " . $ex->getMessage();
            return false;
        }
    }

    // Close the database connection
    protected function closeConnection()
    {
        $this->conn = null;
    }
}
