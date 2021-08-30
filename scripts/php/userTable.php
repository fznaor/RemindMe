<?php
require_once("dbconfig.php");

class UserTable
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

    public function createUserTable()
    {
        $sql = <<<EOSQL
        CREATE TABLE IF NOT EXISTS $this->tableName (
            user_id     INT AUTO_INCREMENT   PRIMARY KEY,
            email       VARCHAR (255)        UNIQUE NOT NULL,
            username    VARCHAR (30)         UNIQUE NOT NULL,
            password    CHAR (32)            NOT NULL
        );
        EOSQL;

        $this->conn->exec($sql);
    }

    public function insertRow($email, $username, $password)
    {
        if($this->checkIfEmailExists($email)->rowCount()>0){
            $_SESSION['duplicateEmail'] = TRUE;
            return false;
        }

        $sql = <<<EOSQL
            INSERT INTO $this->tableName (email, username, password) VALUES (:email, :username, :password);
        EOSQL;

        $query = $this->conn->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);

        try {
            $query->execute();
            return true;
        } catch (Exception $e) {
            $_SESSION['duplicateUsername'] = TRUE;
            return false;
        }
    }

    public function validateLogin($username, $password){
        $loginInfo = array(
            ':username' => $username,
            ':password' => $password,
        );

        $sql = <<<EOSQL
            SELECT * FROM $this->tableName WHERE username=:username AND password=:password;
        EOSQL;

        $query = $this->conn->prepare($sql);

        try {
            $query->execute($loginInfo);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            return $query;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function checkIfEmailExists($email){
        $loginInfo = array(
            ':email' => $email
        );

        $sql = <<<EOSQL
            SELECT * FROM $this->tableName WHERE email=:email;
        EOSQL;

        $query = $this->conn->prepare($sql);

        try {
            $query->execute($loginInfo);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            return $query;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}