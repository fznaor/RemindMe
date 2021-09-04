<?php
  session_start();
  if(!isset($_COOKIE['activeUser'])) {
    header('Location: login.php');
  }
  else{
    require_once("db/userTable.php");

    $obj = new UserTable("user");
    $user = $obj->getUserByUsername($_COOKIE['activeUser']);
  }
?>

<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RemindMe - Profile</title>
    <meta name="Description" content="Manage your personal events."/>
    <link rel="stylesheet" href="../../styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Yantramanav&display=swap" rel="stylesheet">
    <meta name="og:title" property="og:title" content="RemindMe">
    <meta property="og:type" content="website" />
    <meta property="og:description" content="The number one page for personal event management." />
    <meta property="og:image:type" content="../../images/icon.png" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="400" />
    <meta property="og:image:alt" content="RemindMe logo." />
    <link rel="icon" 
          type="image/png" 
          href="../../images/icon.png">
  </head>

  <body id="profileBody">
    <div id="content">
      <header>
        <a href="../../index.php"><img src="../../images/home.png" alt="Home icon"></a>
        <h1 id="pageTitle"><a id="pageTitleLink" href="../../index.php">RemindMe</a></h1>
        <div id="headerUser">
          <img src="../../images/user.png" alt="User icon" loading="lazy" id="userIcon">
          <p id="headerUsername"><strong><? echo $_COOKIE['activeUser'] ?></strong></p>
          <img src="../../images/arrowDown.png" alt="Arrow down icon" id="arrowDown" loading="lazy">
        </div>
        <section id="menu" class="invisibleHidden">
          <img src="../../images/triangle.svg" id="triangle" alt="Triangle icon" loading="lazy">
          <div id="menuBody">
            <a href="profile.php">See profile</a>
            <a href="logout.php">Log out</a>
          </div>
      </header>
      <div id="profileMain">
        <div id="accountInfo">
          <?php while ($row = $user->fetch()) : ?>
              <h2>Account info</h2>
              <p>Username: <?= $row["username"] ?></p>
              <p id="emailParagraph">E-mail: <?= $row["email"] ?></p>
          <?php endwhile; ?>
        </div>
        <div id="profileRight">
          <div id="editPassword">
            <h2>Edit password</h2>
            <form action="updatePassword.php" method="post">
                Current password:<br><input type="password" class="inputText" name="current" placeholder="Enter current password" minlength="6" maxlength="30" required><br>
                New password:<br><input type="password" class="inputText" name="new" placeholder="Enter new password" minlength="6" maxlength="30" required><br>
                Confirm new password:<br><input type="password" class="inputText" name="newConfirm" placeholder="Re-enter new password" minlength="6" maxlength="30" required><br>
                <input type="submit" value="Change password" id="updatePasswordSubmit">
            </form>
            <?php if(isset($_SESSION['wrongPassword'])) : ?>
                <p id="passwordUpdateErrorMsg">You entered an incorrect password!</p>
                <?php unset($_SESSION['wrongPassword']); ?>
            <?php endif; ?>
            <?php if(isset($_SESSION['wrongPasswordConfirm'])) : ?>
                <p id="passwordUpdateErrorMsg">The new passwords don't match! Please try again.</p>
                <?php unset($_SESSION['wrongPasswordConfirm']); ?>
            <?php endif; ?>
            <?php if(isset($_SESSION['passwordChangeFailure'])) : ?>
                <p id="passwordUpdateErrorMsg">Something went wrong, please try again.</p>
                <?php unset($_SESSION['passwordChangeFailure']); ?>
            <?php endif; ?>
            <?php if(isset($_SESSION['passwordChanged'])) : ?>
                <p id="passwordUpdateSuccessMsg">You have successfully updated your password.</p>
                <?php unset($_SESSION['passwordChanged']); ?>
            <?php endif; ?>
          </div>
          <div id="deleteAccount">
            <h2>Delete account</h2>
            <p>Click this button if you wish to delete your account.</p>
            <form action="deleteAccount.php" method="post">
                <input type="submit" onclick="return confirm('Are you sure you want to delete your account? This action can not be undone!');" value="Delete account" id="deleteAccountSubmit">
            </form>
          </div>
        </div>
      </div>
    </div>
    <footer>
      <p style="visibility:hidden;">Dummy</p>
      <h3 id="pageTitleFooter"><a id="pageTitleLink" href="../../index.php">RemindMe</a></h3>
      <div><p>© Lemuri 2021.</p></div>
    </footer>
    <script src="../js/headerDropDownMenu.js" defer></script> 
  </body>
</html>