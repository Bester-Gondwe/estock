<?php
require_once __DIR__ . '/config/bootstrap.php';

class Database
{
    private string $host;
    private string $username;
    private ?string $passwd;
    private string $dbname;
    private string $port;
    protected ?PDO $conn = null;

    public function __construct()
    {
        $this->host = (string) env('DB_HOST', 'localhost');
        $this->port = (string) env('DB_PORT', '3306');
        $this->username = (string) env('DB_USER', 'root');
        $pass = env('DB_PASS', '');
        $this->passwd = ($pass === null || $pass === '') ? null : (string) $pass;
        $this->dbname = (string) env('DB_NAME', 'estock');

        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->passwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            if (env('APP_DEBUG', false)) {
                die('Database connection failed: ' . $ex->getMessage());
            }
            die('Database connection failed. Please check your configuration.');
        }
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }

    public function beginTransaction(): bool
    {
        return $this->conn->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->conn->commit();
    }

    public function rollBack(): bool
    {
        if ($this->conn->inTransaction()) {
            return $this->conn->rollBack();
        }
        return false;
    }

    public function query($sql)
    {
        try {
            return $this->conn->query($sql);
        } catch (PDOException $ex) {
            if (env('APP_DEBUG', false)) {
                echo 'Query error: ' . $ex->getMessage();
            }
            return false;
        }
    }

    public function prepare($sql)
    {
        try {
            return $this->conn->prepare($sql);
        } catch (PDOException $ex) {
            if (env('APP_DEBUG', false)) {
                echo 'Prepare error: ' . $ex->getMessage();
            }
            return false;
        }
    }

    protected function executeQuery($query, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params ?? []);
            return $stmt;
        } catch (PDOException $ex) {
            if (env('APP_DEBUG', false)) {
                echo 'Execution error: ' . $ex->getMessage();
            }
            throw $ex;
        }
    }

    public function fetchAll($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params ?? []);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            if (env('APP_DEBUG', false)) {
                echo 'Fetch error: ' . $ex->getMessage();
            }
            return false;
        }
    }

    protected function closeConnection(): void
    {
        $this->conn = null;
    }
}
