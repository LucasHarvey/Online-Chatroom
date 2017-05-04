<?php
session_start();

if (!isset($_SESSION['userID'])) {
 header("Location: /API/html/login.html");
} else if (isset($_SESSION['userID'])!="") {
 header("Location: /API/html/home.php");
}

if (isset($_GET['logout'])) {
 session_destroy();
 unset($_SESSION['userID']);
 unset($_SESSION['username']);
 header("Location: /API/html/login.html");
}
?>