<?php
session_start();
        
//this file receives the $counter parameter through a 
//GET and outputs the username, message and messageID

//define variables for later use

$tableArray = array();
$messagesArray = array();
$arr = array();
$jsonResponse = "";
$lastSeenArr = array();
$lastElement = "";
$userID = "";

//store the user's ID in a variable
$userID = $_SESSION['userID'];

//connect to database
include_once("connect_db_chatroom.php");

//select the last seen message's id from the user table
$query = $conn->query("SELECT lastseen FROM users WHERE users.id = $userID");

//set the $lastSeen variable to the id of the last message seen
$lastSeen = 0;
$lastSeenRow = mysqli_fetch_assoc($query);
if($lastSeenRow){
    $lastSeen = $lastSeenRow['lastseen'];
}

//select all messages with an id greater than $lastSeen
$query = $conn->query("SELECT username, text, messages.id FROM users, messages WHERE users.id = messages.userid AND messages.id>($lastSeen-10)");


//create an array containing messages and user id's
while($row = mysqli_fetch_assoc($query)){
    array_push($tableArray,$row);
}

//pushes the username of the user making the get to the messagesArray
$username = $_SESSION['username'];
array_push($messagesArray,$username);

//go through the array and stores the username, message and messageID in variables declared at beginning of file

foreach($tableArray as $row){
    
    //store the username in a variable
    $username = $row['username'];
    //store the message in a variable
    $message = $row['text'];
    //store the messageID in a variable
    $messageID = $row['id'];
    
    //create an array containing the username, message and messageID
    $arr = array('username' => $username, 'message' => $message, 'messageID' => $messageID);

    //push the array created above into an array which contains all messages
    array_push($messagesArray,$arr);
        
}


    
//update the last seen column in the database if there are new messages
if (count($messagesArray)>1){
    $userID = $_SESSION['userID'];
    $lastElement = end(array_values($messagesArray));
    $lastSeen = $lastElement['messageID'];
    $query = $conn->query("UPDATE users SET lastseen = $lastSeen WHERE id = $userID");
}


//close the connection to the database
$conn->close();

//encode the messagesArray into JSON format, store it in the $JSON variable and echo it
$jsonResponse = json_encode($messagesArray);
echo $jsonResponse;


?>