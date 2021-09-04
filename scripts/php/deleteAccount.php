<?php

    session_start();
    require_once("db/userTable.php");

    $obj = new UserTable("user");
    $obj->deleteUser($_COOKIE['activeUser']);
    header('Location: logout.php');
?>