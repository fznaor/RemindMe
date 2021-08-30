<?php
    setcookie("activeUser", "email", time() - 60, '/');
    header('Location: login.php');