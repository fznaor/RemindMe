<?php

    session_start();
    require_once("userTable.php");

    $obj = new UserTable("User");
    //$obj->createUserTable();

    function loginFailure(){
        $_SESSION['loginFailure'] = TRUE;
        header('Location: login.php');
    }

    $username = $password = '';
    if (empty($_POST['username'])) {
        loginFailure();
    } else {
        $username = trim(htmlspecialchars($_POST['username']));
    }
    if (empty($_POST['password'])) {
        loginFailure();
    } else {
        $password = trim(htmlspecialchars($_POST['password']));
    }

    if($obj->validateLogin($username, md5($password))->rowCount() == 0){
        loginFailure();
    }
    else{
        setcookie("activeUser", $_POST["username"], time() + 30*1440*60, '/');
        header('Location: ../../index.php');
    }
?>