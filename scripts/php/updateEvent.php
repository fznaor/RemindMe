<?php
    session_start();
    require_once("db/eventTable.php");

    $obj = new EventTable("event");

    function updateEventFailure(){
        echo("An error occurred, please try again.");
        exit();
    }

    $title = $date = $location = $category = $importance = $description = "";
    if (empty($_POST['name'])) {
        updateEventFailure();
    } else {
        $title = trim(htmlspecialchars($_POST['name']));
    }
    if (empty($_POST['date'])) {
        updateEventFailure();
    } else {
        $date = trim(htmlspecialchars($_POST['date']));
    }
    if (empty($_POST['location'])) {
        updateEventFailure();
    } else {
        $location = trim(htmlspecialchars($_POST['location']));
    }
    if (empty($_POST['category'])) {
        updateEventFailure();
    } else {
        $category = trim(htmlspecialchars($_POST['category']));
    }
    if (empty($_POST['importance'])) {
        updateEventFailure();
    } else {
        $importance = trim(htmlspecialchars($_POST['importance']));
    }
    $description = trim(htmlspecialchars($_POST['description']));

    $insertedDate = new DateTime($_POST['date']);
    $now = new DateTime();

    if($insertedDate < $now) {
        echo "The date of the event can't be in the past!";
        exit();
    }
    
    $obj->updateEvent($_POST['id'], $title, $date, $importance, $category, $location, $description);
    echo("The event was successfully updated!");