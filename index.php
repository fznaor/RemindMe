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
  </head>

  <body>
    <a href="scripts/php/logout.php">ODJAVA</a>
  </body>
</html>