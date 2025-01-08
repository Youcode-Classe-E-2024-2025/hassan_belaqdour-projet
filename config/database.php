<?php
class Database
{
    private $host = "localhost";
    private $dbname = "gestion_projet";
    private $username = "root";
    private $password = "";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
?>