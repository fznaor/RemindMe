<?php
  session_start();
  if(!isset($_COOKIE['activeUser'])) {
    header('Location: scripts/php/login.php');
  }
?>

<!doctype html>

<html lang="hr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RemindMe</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Yantramanav&display=swap" rel="stylesheet">
  </head>

  <body id="indexBody">
    <div id="content">
      <header>
        <a href="index.php"><img src="images/home.png" alt="Home icon"></a>
        <h1 id="pageTitle"><a id="pageTitleLink" href="index.php">RemindMe</a></h1>
        <div id="headerUser">
          <img src="images/user.png" alt="User icon" loading="lazy" id="userIcon">
          <p id="headerUsername"><? echo $_COOKIE['activeUser'] ?></p>
          <img src="images/arrowDown.png" alt="Arrow down icon" id="arrowDown" loading="lazy">
        </div>
        <section id="menu" class="invisibleHidden">
          <img src="images/triangle.svg" id="triangle" alt="Triangle icon" loading="lazy">
          <div id="menuBody">
            <a href="scripts/php/profile.php">See profile</a>
            <a href="scripts/php/logout.php">Log out</a>
          </div>
      </header>
    </div>
    <footer>
      <p style="visibility:hidden;">Dummy</p>
      <h3 id="pageTitleFooter"><a id="pageTitleLink" href="index.php">RemindMe</a></h3>
      <div><p>© Lemuri 2021.</p></div>
    </footer>
    <script src="scripts/js/headerDropDownMenu.js" defer></script> 
  </body>
</html>