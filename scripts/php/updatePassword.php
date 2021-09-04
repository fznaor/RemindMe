<?php

    session_start();
    require_once("db/userTable.php");

    $obj = new UserTable("user");
    //$obj->createUserTable();

    function passwordChangeFailure(){
        $_SESSION['passwordChangeFailure'] = TRUE;
        header('Location: profile.php');
    }

    $password = $newPassword = $newPasswordConfirm = '';
    if (empty($_POST['current'])) {
        passwordChangeFailure();
    } else {
        $password = trim(htmlspecialchars($_POST['current']));
    }
    if (empty($_POST['new'])) {
        passwordChangeFailure();
    } else {
        $newPassword = trim(htmlspecialchars($_POST['new']));
    }
    if (empty($_POST['newConfirm'])) {
        passwordChangeFailure();
    } else {
        $newPasswordConfirm = trim(htmlspecialchars($_POST['newConfirm']));
    }

    if($obj->validateLogin($_COOKIE['activeUser'], md5($password))->rowCount() == 0){
        $_SESSION['wrongPassword'] = TRUE;
        header('Location: profile.php');
    }
    else if($newPassword != $newPasswordConfirm){
        $_SESSION["wrongPasswordConfirm"] = TRUE;
        header('Location: profile.php');
    }
    else{
        $obj->updatePassword($_COOKIE['activeUser'], md5($newPassword));
        $_SESSION["passwordChanged"] = TRUE;
        header('Location: profile.php');
    }
?>