<?php 
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.html");
}

?>

<html>
<head>
<title>Chatroom</title>
<script type = "text/javascript" src = "/API/js/jquery.js" ></script>
<script type = "text/javascript" src = "/API/js/postMessageAjax.js" ></script>
<script type = "text/javascript" src = "/API/js/getMessagesAjax.js" ></script>
<link rel="stylesheet" type="text/css" href="chatroom_style.css"></style>

</head>
<body>
<div class = "right">
    <a href="/API/php/logout.php?logout" class = "general">Logout</a>
</div>

<div class = "home">
    
            <h1 class = "chatroom-title" style = "margin-left:40">Chatroom:</h1>
            <form>
            <input style = "font-family: verdana, sans-serif" type = "text" size = "30" id = "input_text"/>
            <input type= "submit" class="button" id = "sendMessage" value = "Send">
            </form>
            </br></br>
            <span id = "message-error" class = "error"></span>
            <span id = "message-confirmation"></span>
            <div id = "messages" class = "chatbox">
            </div>   
</div>
</body>
</html>
<html>
<head>

