<?php
$servername = getenv("IP");
$serverusername = getenv(C9_USER);
$password = "";
$db = "webchat";

// Create connection
$conn = new mysqli($servername, $serverusername, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
