<?php
  session_start();
  if(isset($_COOKIE['activeUser'])) {
    header('Location: ../../index.php');
  }
?>

<!doctype html>

<html lang="hr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RemindMe</title>
        <link rel="stylesheet" href="../../styles/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Yantramanav&display=swap" rel="stylesheet">
    </head>

  <body>
    <h1>RemindMe</h1>
    <?php if(isset($_SESSION['duplicateEmail']) || isset($_SESSION['duplicateUsername']) || isset($_SESSION['wrongPasswordConfirm']) || isset($_SESSION['registrationFailure'])) : ?>
      <section id="loginPage" class = "invisible">
    <? else: ?>
      <section id="loginPage">
    <?php endif; ?>
      <h2>Login</h2>
      <form action="loginCheck.php" method="post">
          Username:<br><input type="text" placeholder="Enter username" name="username" required><br>
          Password:<br><input type="password" placeholder="Enter password" name="password" required><br>
          <input type="submit" value="Log in" id="loginSubmit" class="submit">
      </form>
      <a href="#" class="loginToggle">Don't have an account? Register now.</a>
      <?php if(isset($_SESSION['loginFailure'])) : ?>
          <p id="loginErrorMsg">The login was unsuccessful! Please try again.</p>
          <?php unset($_SESSION['loginFailure']); ?>
      <?php endif; ?>
    </section>
    <?php if(isset($_SESSION['duplicateEmail']) || isset($_SESSION['duplicateUsername']) || isset($_SESSION['wrongPasswordConfirm']) || isset($_SESSION['registrationFailure'])) : ?>
      <section id="registrationPage">
    <? else: ?>
      <section id="registrationPage" class = "invisible">
    <?php endif; ?>
      <h2>Register</h2>
      <form action="registerCheck.php" method="post">
        E-mail:<br><input type="email" placeholder="Enter email" name="email" required><br>
        Username:<br><input type="text" placeholder="Enter username" minlength="3" maxlength="30" name="username" required><br>
        Password:<br><input type="password" placeholder="Enter password" minlength="6" maxlength="30" name="password" required><br>
        Confirm password:<br><input type="password" placeholder="Re-enter password" minlength="6" maxlength="30" name="passwordConfirm" required><br>
        <input type="submit" value="Register" id="loginSubmit" class="submit">
      </form>
        <a href="#" class="loginToggle">Already have an account? Log in now.</a>
        <?php if(isset($_SESSION['duplicateEmail'])) : ?>
            <p id="loginErrorMsg">An account with this email already exists!</p>
            <?php unset($_SESSION['duplicateEmail']); ?>
        <?php endif; ?>
        <?php if(isset($_SESSION['duplicateUsername'])) : ?>
            <p id="loginErrorMsg">Sorry, this username has already been taken.</p>
            <?php unset($_SESSION['duplicateUsername']); ?>
        <?php endif; ?>
        <?php if(isset($_SESSION['wrongPasswordConfirm'])) : ?>
            <p id="loginErrorMsg">The passwords don't match, please try again.</p>
            <?php unset($_SESSION['wrongPasswordConfirm']); ?>
        <?php endif; ?>
        <?php if(isset($_SESSION['registrationFailure'])) : ?>
            <p id="loginErrorMsg">The registration was unsuccessful, please try again.</p>
            <?php unset($_SESSION['registrationFailure']); ?>
        <?php endif; ?>
    </section>
    <script src="../js/login.js" defer></script> 
    <script> </script>
  </body>
</html>