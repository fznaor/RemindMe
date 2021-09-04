<?php
    session_start();
    require_once("db/eventTable.php");

    $obj = new EventTable("event");
    //$obj->createEventTable();

    function createEventFailure(){
        echo("An error occurred, please try again.");
        exit();
    }

    $title = $date = $location = $category = $importance = $description = "";
    if (empty($_POST['title'])) {
        createEventFailure();
    } else {
        $title = trim(htmlspecialchars($_POST['title']));
    }
    if (empty($_POST['date'])) {
        createEventFailure();
    } else {
        $date = trim(htmlspecialchars($_POST['date']));
    }
    if (empty($_POST['location'])) {
        createEventFailure();
    } else {
        $location = trim(htmlspecialchars($_POST['location']));
    }
    if (empty($_POST['category'])) {
        createEventFailure();
    } else {
        $category = trim(htmlspecialchars($_POST['category']));
    }
    if (empty($_POST['importance'])) {
        createEventFailure();
    } else {
        $importance = trim(htmlspecialchars($_POST['importance']));
    }
    $description = trim(htmlspecialchars($_POST['description']));

    $insertedDate = new DateTime($_POST['date']);
    $now = new DateTime();

    if($insertedDate < $now) {
        echo "You can't add an event in the past!";
        exit();
    }

    require_once("db/userTable.php");
    $userTable = new UserTable("user");
    $userData = $userTable->getUserByUsername($_COOKIE['activeUser']);
    $user = '';
    while ($row = $userData->fetch()){
        $user = $row['user_id'];
    }
    
    $obj->insertRow($title, $user, $date, $importance, $category, $location, $description);
    echo("The event was successfully added!");