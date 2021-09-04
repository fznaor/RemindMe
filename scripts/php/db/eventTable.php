<?php
require_once("dbconfig.php");
session_start();

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
            user_id      INT                  NOT NULL,
            name         VARCHAR(100)         NOT NULL,
            date         DATETIME             NOT NULL,
            importance   VARCHAR(20)          NOT NULL,
            category     VARCHAR(20)          NOT NULL,
            location     VARCHAR(100)         NOT NULL,
            description  VARCHAR(1000),
            FOREIGN KEY(user_id) REFERENCES User(user_id)
        );
        EOSQL;

        $this->conn->exec($sql);
    }

    public function insertRow($name, $user, $date, $importance, $category, $location, $description)
    {
        $sql = <<<EOSQL
            INSERT INTO $this->tableName (name, user_id, date, importance, category, location, description) VALUES (:name, :user, :date, :importance, :category, :location, :description);
        EOSQL;

        $query = $this->conn->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':user', $user, PDO::PARAM_INT);
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->bindParam(':importance', $importance, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':location', $location, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);

        try {
            $query->execute();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
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

    public function updateEvent($id, $name, $date, $importance, $category, $location, $description){
        $sql = <<<EOSQL
            UPDATE $this->tableName SET name = :name, date = :date, importance = :importance, category = :category, location = :location, description = :description WHERE event_id = :id;
        EOSQL;

        $query = $this->conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
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
        $query->bindParam(':id', $id, PDO::PARAM_INT);

        $query->execute();
    }

    function getAllUsersEvents($user){
        $sql = "";

        if($_SESSION["filterDate"] == "Upcoming"){
            $sql = <<<EOSQL
                SELECT * FROM $this->tableName WHERE user_id=:user AND date >= CURRENT_DATE() ORDER BY date;
            EOSQL;
        }
        else{
            $sql = <<<EOSQL
                SELECT * FROM $this->tableName WHERE user_id=:user AND date < CURRENT_DATE() ORDER BY date;
            EOSQL;        
        }

        $query = $this->conn->prepare($sql);
        $query->bindParam(':user', $user, PDO::PARAM_INT);

        try {
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            return $query;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function getAllUsersEventsWithCategoryFilter($user){
        $sql = "";

        if($_SESSION["filterDate"] == "Upcoming"){
            $sql = <<<EOSQL
                SELECT * FROM $this->tableName WHERE user_id=:user AND category=:category AND date >= CURRENT_DATE() ORDER BY date;
            EOSQL;
        }
        else{
            $sql = <<<EOSQL
                SELECT * FROM $this->tableName WHERE user_id=:user AND category=:category AND date < CURRENT_DATE() ORDER BY date;
            EOSQL;        
        }

        $query = $this->conn->prepare($sql);
        $query->bindParam(':user', $user, PDO::PARAM_INT);
        $query->bindParam(':category', $_POST["filterCategory"], PDO::PARAM_STR);

        try {
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            return $query;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function getAllUsersEventsWithImportanceFilter($user){
        $sql = "";

        if($_SESSION["filterDate"] == "Upcoming"){
            $sql = <<<EOSQL
                SELECT * FROM $this->tableName WHERE user_id=:user AND importance=:importance AND date >= CURRENT_DATE() ORDER BY date;
            EOSQL;
        }
        else{
            $sql = <<<EOSQL
                SELECT * FROM $this->tableName WHERE user_id=:user AND importance=:importance AND date < CURRENT_DATE() ORDER BY date;
            EOSQL;        
        }

        $query = $this->conn->prepare($sql);
        $query->bindParam(':user', $user, PDO::PARAM_INT);
        $query->bindParam(':importance', $_POST["filterImportance"], PDO::PARAM_STR);

        try {
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            return $query;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function getAllUsersEventsWithBothFilters($user){
        $sql = "";

        if($_SESSION["filterDate"] == "Upcoming"){
            $sql = <<<EOSQL
                SELECT * FROM $this->tableName WHERE user_id=:user AND importance=:importance AND category=:category AND date >= CURRENT_DATE() ORDER BY date;
            EOSQL;
        }
        else{
            $sql = <<<EOSQL
                SELECT * FROM $this->tableName WHERE user_id=:user AND importance=:importance AND category=:category AND date < CURRENT_DATE() ORDER BY date;
            EOSQL;        
        }

        $query = $this->conn->prepare($sql);
        $query->bindParam(':user', $user, PDO::PARAM_INT);
        $query->bindParam(':category', $_POST["filterCategory"], PDO::PARAM_STR);
        $query->bindParam(':importance', $_POST["filterImportance"], PDO::PARAM_STR);

        try {
            $query->execute();
            $query->setFetchMode(PDO::FETCH_ASSOC);
            return $query;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}