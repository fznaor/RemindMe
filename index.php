<?php  
  session_start();

  if(!isset($_COOKIE['activeUser'])) {
    header('Location: scripts/php/login.php');
  }

  $_SESSION["filterCategory"] = "All";
  $_SESSION["filterImportance"] = "All";
  $_SESSION["filterDate"] = "Upcoming";

  require_once("scripts/php/db/userTable.php");
  $userTable = new UserTable("User");
  $userData = $userTable->getUserByUsername($_COOKIE['activeUser']);
  $user = '';
  while ($row = $userData->fetch()){
      $user = $row['user_id'];
  }

  require_once("scripts/php/db/eventTable.php");
  $obj = new EventTable("Event");
  $data = $obj->getAllUsersEvents($user);

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
?>

<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RemindMe</title>
    <meta name="Description" content="Manage your personal events."/>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Yantramanav&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <meta property="og:type" content="website" />
    <meta property="og:description" content="The number one page for personal event management." />
    <meta property="og:image:type" content="images/icon.png" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="400" />
    <meta property="og:image:alt" content="RemindMe logo." />
    <link rel="icon" 
      type="image/png" 
      href="images/icon.png">
  </head>

  <body id="indexBody">
    <div id="content">
      <header>
        <a href="index.php"><img src="images/home.png" alt="Home icon"></a>
        <h1 id="pageTitle"><a id="pageTitleLink" href="index.php">RemindMe</a></h1>
        <div id="headerUser">
          <img src="images/user.png" alt="User icon" loading="lazy" id="userIcon">
          <p id="headerUsername"><strong><? echo $_COOKIE['activeUser'] ?></strong></p>
          <img src="images/arrowDown.png" alt="Arrow down icon" id="arrowDown" loading="lazy">
        </div>
        <section id="menu" class="invisibleHidden">
          <img src="images/triangle.svg" id="triangle" alt="Triangle icon" loading="lazy">
          <div id="menuBody">
            <a href="scripts/php/profile.php">See profile</a>
            <a href="scripts/php/logout.php">Log out</a>
          </div>
      </header>
      <button id="addEventBtn" type="button">Add an event</button>
      <section id="addEventPage" class="noHeight">
        <div id="createEventHeader">
          <img style="visibility:hidden;" src="images/close.png" alt="Button for closing create product prompt" class="closeCreateFormBtn">
          <h2>Add an event</h2>
          <img src="images/close.png" alt="Button for closing create product prompt" class="closeCreateFormBtn" id="closeCreateForm">
        </div>
        <form id="addEventForm" action="scripts/php/addEventToDB.php">
          <div><label for="title">Title:</label><input type="text" name="title" required maxlength="100"></div>
          <div><label for="date">Date:</label><input type="datetime-local" name="date" required id="createDate"></div>
          <div><label for="location">Location:</label><input type="text" name="location" required></div>
          <div><label for="category">Category:</label><select name="category">
            <option value="Work">Work</option>
            <option value="Education">Education</option>
            <option value="Family">Family</option>
            <option value="Food and drink">Food and drink</option>
            <option value="Entertainment">Entertainment</option>
            <option value="Leisure">Leisure</option>
            <option value="Other">Other</option>
          </select></div>
          <div><label for="importance">Importance:</label><select name="importance">
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
          </select></div>
          <div><label for="description">Description:</label><textarea name="description"></textarea></div>
          <input type="submit" value="Create event" class="submit" id="addEvent">
        </form>
      </section>
      <h2 id="myEventsHeader">My events</h2>
      <section id="filters">
        <h3>Filters</h3>
        <div id="filterList">
          <div>
            <span>Category</span>
            <select id="categorySelect" onchange="filterData()">
              <option value="All">All</option>
              <option value="Work">Work</option>
              <option value="Education">Education</option>
              <option value="Family">Family</option>
              <option value="Food and drink">Food and drink</option>
              <option value="Entertainment">Entertainment</option>
              <option value="Leisure">Leisure</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div>
            <span>Importance</span>
            <select id="importanceSelect" onchange="filterData()">
              <option value="All">All</option>
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
            </select>
          </div>
          <div>
            <span>Date</span>
            <select id="dateSelect" onchange="filterData()">
              <option value="Upcoming">Upcoming</option>
              <option value="Past">Past</option>
            </select>
          </div>
        </div>
      </section>
      <section id="events" class="visible">
        <?php while ($row = $data->fetch()) : ?>
          <div class="eventCard" onclick='openDetails(this)'>
            <div class="eventHeader <?= getPriorityClass($row['importance'])?>">
              <?= getHeaderImage($row['importance'])?>
              <h2 class="eventTitle"><?= $row["name"]?></h2>
              <?= getHeaderImage($row['importance'])?>
            </div>
            <div class="eventCategory">
              <img src=<?= getImageName($row["category"])?>>
              <p><?= $row["category"]?></p>
            </div>
            <div class="eventTime">
              <img src="images/calendar.png">
              <p><?= date("d.m.Y. G:i", strtotime($row["date"]))?></p>
            </div>
            <div class="eventLocation">
              <img src="images/location.png">
              <p><?= $row["location"]?></p>
            </div>
            <p style="display:none;" id="eventID"><?= $row["event_id"]?></p>
          </div>
        <?php endwhile; ?>
      </section>
      <div id="modalWindow">
        <div id="eventDetails">
        </div>
      </div>
      <div id="deleteCheck">
        <div id="deleteCheckBody">
          <p>Are you sure you want to delete this event?</p>
          <div id="deleteCheckBtns">
              <button type="button" onclick="deleteEvent()">Yes</button>
              <button type="button" onclick="cancelDelete()">No</button>
          </div>
        </div>
      </div>
    </div>
    <footer>
      <p style="visibility:hidden;">Dummy</p>
      <h3 id="pageTitleFooter"><a id="pageTitleLink" href="index.php">RemindMe</a></h3>
      <div><p>© Lemuri 2021.</p></div>
    </footer>
    <div id="snackbar"></div>
    <script src="scripts/js/headerDropDownMenu.js" defer></script> 
    <script src="scripts/js/addEvent.js" defer></script> 
    <script src="scripts/js/modalWindow.js" defer></script>
    <script src="scripts/js/filterData.js" defer></script> 
  </body>
</html>