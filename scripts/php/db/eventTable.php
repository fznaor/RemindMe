<?php
require_once("dbconfig.php");

class EventTable
{
    private $conn;
    private $tableName;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $connStr = sprintf("mysql:host=%s;dbname=%s", DBConfig::HOST, DBConfig::DB_NAME);

        try {
            $this->conn = new PDO($connStr, DBConfig::USERNAME, DBConfig::PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function __destruct()
    {
        $this->conn = null;
    }

    public function createEventTable()
    {
        $sql = <<<EOSQL
        CREATE TABLE IF NOT EXISTS $this->tableName (
            event_id     INT AUTO_INCREMENT   PRIMARY KEY,
            name         VARCHAR(100)         NOT NULL,
            date         DATETIME             NOT NULL,
            importance   VARCHAR(20)          NOT NULL,
            category     VARCHAR(20)          NOT NULL,
            location     VARCHAR(100),
            description  VARCHAR(1000)        
        );
        EOSQL;

        $this->conn->exec($sql);
    }

    public function insertRow($name, $date, $importance, $category, $location, $description)
    {
        $sql = <<<EOSQL
            INSERT INTO $this->tableName (name, date, importance, category, location) VALUES (:name, :date, :importance, :category, :location);
        EOSQL;

        $query = $this->conn->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->bindParam(':importance', $importance, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':location', $location, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);

        $query->execute();
    }

    public function getEventById($id){
        $sql = <<<EOSQL
            SELECT * FROM $this->tableName WHERE event_id=:id;
        EOSQL;

        $query = $this->conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);

        try {
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            return $query;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function updateEvent($name, $date, $importance, $category, $location, $description){
        $sql = <<<EOSQL
            UPDATE $this->tableName SET name = :name, importance = :importance, category = :category, location = :location, description = :description WHERE event_id = :id;
        EOSQL;

        $query = $this->conn->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->bindParam(':importance', $importance, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':location', $location, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);

        $query->execute();
    }

    public function deleteEvent($id){
        $sql = <<<EOSQL
            DELETE FROM $this->tableName WHERE event_id = :id;
        EOSQL;

        $query = $this->conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);

        $query->execute();
    }
}