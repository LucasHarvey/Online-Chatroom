<?php

//this file receives the myMessage and id parameters through a 
//POST and outputs messageErr and messageConfirmation

//continue using the session
session_start();

//connect to database
include_once "connect_db_chatroom.php";

//define variables for later use
$messageErr = "";
$message = "";
$messageConfirmation = "";
$userID = "";

//store the user's ID in a variable
$userID = $_SESSION['userID'];

//validate inputs
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  
  //get post data, decode it and store it in the $jsonReceipt variable
 
  $jsonReceipt=json_decode(file_get_contents('php://input'),TRUE);
    
  if (empty($jsonReceipt["myMessage"])){
    $messageErr = "No message submitted.";
  }
  else{
      $message = test_input($jsonReceipt["myMessage"]);
  }
}

//insert the message into the messages table if there is no error and there is a message
if ($messageErr == "" && $message != "") {
  
  $sql = "INSERT INTO messages (text, userid) VALUES ('".$message."','".$userID."')";

  if ($conn->query($sql) === TRUE) {
    //change $messageConfirmation if the insertion is successful
    $messageConfirmation = "Your message has been sent!";
    //empty the $message variable
    $message = "";
    }
  else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  //close the connection to the database
  $conn->close();
}
  
//encode the $messageErr and $messageConfirmation variables into JSON format, store it in the $jsonResponse variable and echo it
$jsonResponse = json_encode(array('messageErr' => $messageErr, 'messageConfirmation' => $messageConfirmation));
echo $jsonResponse;


//function which tests the different inputs, sanitizes data and prevents sql injection
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
  
?>
