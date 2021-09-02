<?php

    session_start();
    require_once("db/userTable.php");

    $obj = new UserTable("User");
    $obj->deleteUser($_COOKIE['activeUser']);
    header('Location: logout.php');
?>