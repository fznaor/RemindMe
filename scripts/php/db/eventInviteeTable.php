<?php
require_once("dbconfig.php");

class EventInviteeTable
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

    public function createEventOrganizerTable()
    {
        $sql = <<<EOSQL
        CREATE TABLE IF NOT EXISTS $this->tableName (
            user_id     INT,
            event_id    INT,
            CONSTRAINT  eoPk PRIMARY KEY(user_id,event_id),
            CONSTRAINT  eoFkUser FOREIGN KEY(user_id) REFERENCES user(user_id),
            CONSTRAINT  eoFkEvent FOREIGN KEY(event_id) REFERENCES event(event_id)
        );
        EOSQL;

        $this->conn->exec($sql);
    }

    public function insertRow($user, $event)
    {
        $sql = <<<EOSQL
            INSERT INTO $this->tableName (user_id, event_id) VALUES (:user, :event);
        EOSQL;

        $query = $this->conn->prepare($sql);
        $query->bindParam(':user', $user, PDO::PARAM_STR);
        $query->bindParam(':event', $event, PDO::PARAM_STR);

        $query->execute();
    }
}