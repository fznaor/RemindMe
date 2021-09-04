<?php

    session_start();
    require_once("db/userTable.php");

    $obj = new UserTable("user");
    //$obj->createUserTable();

    function loginFailure(){
        $_SESSION['registrationFailure'] = TRUE;
        header('Location: login.php');
    }

    $email = $username = $password = $passwordConfirm = '';
    if (empty($_POST['email'])) {
        loginFailure();
    } else {
        $email = trim(htmlspecialchars($_POST['email']));
    }
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
    if (empty($_POST['passwordConfirm'])) {
        loginFailure();
    } else {
        $passwordConfirm = trim(htmlspecialchars($_POST['passwordConfirm']));
    }
    
    if($password != $passwordConfirm){
        $_SESSION["wrongPasswordConfirm"] = TRUE;
        header('Location: login.php');
    }
    else if($obj->insertRow($email, $username, md5($password))){
        setcookie("activeUser", $_POST["username"], time() + 30*1440*60, '/');
        header('Location: ../../index.php');
    }
    else{
        header('Location: login.php');
    }
?>