<?php
    $id = $_REQUEST["id"];

    require_once("db/eventTable.php");
    $obj = new EventTable("Event");
    $obj->deleteEvent($id);

    echo "The event has been deleted.";