<?php
  session_start();
  if(!isset($_COOKIE['activeUser'])) {
    header('Location: login.php');
  }

  $category = $_REQUEST["category"];
  $importance = $_REQUEST["importance"];
  $_SESSION["filterDate"] = $_REQUEST["date"];

  require_once("db/userTable.php");
  $userTable = new UserTable("User");
  $userData = $userTable->getUserByUsername($_COOKIE['activeUser']);
  $user = '';
  while ($row = $userData->fetch()){
      $user = $row['user_id'];
  }

  require_once("db/eventTable.php");
  $obj = new EventTable("Event");

  if($category=="All" && $importance=="All"){
    $data = $obj->getAllUsersEvents($user);
  }
  else if($category!="All" && $importance=="All"){
    $_POST["filterCategory"] = $category;
    $data = $obj->getAllUsersEventsWithCategoryFilter($user);
  }
  else if($category=="All" && $importance!="All"){
    $_POST["filterImportance"] = $importance;
    $data = $obj->getAllUsersEventsWithImportanceFilter($user);
  }
  else{
    $_POST["filterCategory"] = $category;
    $_POST["filterImportance"] = $importance;
    $data = $obj->getAllUsersEventsWithBothFilters($user);
  }


  function getImageName($category){
    switch($category){
      case "Work" : return "images/work.png"; break;
      case "Education" : return "images/education.png"; break;
      case "Family" : return "images/family.png"; break;
      case "Food and drink" : return "images/foodanddrink.png"; break;
      case "Entertainment" : return "images/entertainment.png"; break;
      case "Leisure" : return "images/leisure.png"; break;
      case "Other" : return "images/other.png"; 
    }
  }

  function getPriorityClass($priority){
    switch($priority){
      case "High" : return "high"; break;
      case "Medium" : return "medium"; break;
      case "Low" : return "low";
    }
  }

  function getHeaderImage($priority){
    switch($priority){
      case "High" : return "<img src='images/warning.png' style='width:40px;height:40px'>"; break;
      case "Medium" : return "<img src='images/warningmedium.png' style='width:40px;height:40px'>"; break;
      case "Low" : return "";
    }
  }

  $response = '';
  $rowCount = 0;

  while ($row = $data->fetch()){
    $rowCount += 1;
    $response .= '<div class="eventCard" onclick="openDetails(this)">
      <div class="eventHeader '. getPriorityClass($row['importance']). '">'
        .getHeaderImage($row['importance']).
        '<h2 class="eventTitle">'.$row["name"].'</h2>'
        .getHeaderImage($row['importance']).
      '</div>
      <div class="eventCategory">
        <img src='.getImageName($row["category"]).'>
        <p>'.$row["category"].'</p>
      </div>
      <div class="eventTime">
        <img src="images/calendar.png">
        <p>'.date("d.m.Y. G:i", strtotime($row["date"])).'</p>
      </div>
      <div class="eventLocation">
        <img src="images/location.png">
        <p>'.$row["location"].'</p>
      </div>
      <p style="display:none;" id="eventID">' .$row["event_id"].'</p>
    </div>';
  }

  if($rowCount != 0)
    echo $response;
  else echo "<p id='noEventsFoundMsg'>No events found!</p>";