<?php

//this file receives the username and password parameters through a 
//POST and outputs the username, userID and loginFail 

//connect to database
include_once("connect_db_chatroom.php");
    
//define variables for later use
$usernameErr = $passwordErr = $confirmpasswordErr = "";
$username = $password = "";
$encrypted_password = "";
$loginFail = "";
$userID = "";
$lastSeenArr = array();

//validate inputs
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    
    //get post data, decode it and store it in the $jsonReceipt variable
    $jsonReceipt=json_decode(file_get_contents('php://input'),TRUE);

    if (empty($jsonReceipt["username"])){
      $usernameErr = "*Please enter a username.";
    }
    else{
      $username = test_input($jsonReceipt["username"]);
    }

    if (empty($jsonReceipt["password"])){
      $passwordErr = "*Please enter a password.";
    }
    else{
      $password = (test_input($jsonReceipt["password"]));
      $encrypted_password = md5(test_input($jsonReceipt["password"]));
    }
}


//if the $username and $encrypted_password variables are not empty and the error variables are empty, 
//verify login data
if ($usernameErr == "" && $passwordErr == "" && $username != "" && $encrypted_password != ""){
    //check to see if the username exists
    $query = $conn->query("SELECT id, username, password FROM users WHERE username='$username'");
    //stores the row associated to the username in the $row variable
    $row=$query->fetch_array();
    //stores the amount of rows with that username in the $count variable
    $count = $query->num_rows; 

    //verify that the encrypted password matches the encrypted password in the database
    //and that there is only one row with that username and password
    if ($encrypted_password == $row['password'] && $count==1) {

      //start a new session if the login is successful
      session_start();
      //store the user's ID in a session variable
      $_SESSION['userID'] = $row['id'];
      //store the user's ID in the $userID variable to send back to the user 
      $userID = $row['id'];
      //store the user's username in a session variable
      $_SESSION['username'] = $row['username'];
      //empty the $loginFail variable
      $loginFail = "";
      
      //find the id of the last message seen
      $query = $conn->query("SELECT id FROM messages ORDER BY id DESC LIMIT 1");

      //create an array 
      while($row = mysqli_fetch_assoc($query)){
        array_push($lastSeenArr,$row);
      }

      foreach($lastSeenArr as $row){
        //store the username in a variable
        $lastSeen = $row['id'];
      }
      
      //store the id of the last message seen in the user's variable before they sign in
      $query = $conn->query("UPDATE users SET lastseen = $lastSeen WHERE id = $userID");
      

    }
    
    //if the login fails, store a login fail message in the $loginFail variable
    else{
        $loginFail = "Login Failed: Username or Password is incorrect.";
    }
    
//close the connection to the database
$conn->close();

//encode the $username, $userID and $loginFail variables into JSON format, store it in the $jsonResponse variable and echo it
$jsonResponse = json_encode(array('usernameErr' => $usernameErr, 'passwordErr' => $passwordErr, 'username' => $username, 'userID' => $userID, 'loginFail' => $loginFail));
echo $jsonResponse;
}

//if the error variables are not empty, return them
else{
  $jsonResponse = json_encode(array('usernameErr' => $usernameErr, 'passwordErr' => $passwordErr));
  echo $jsonResponse;
}

//function which tests the different inputs, sanitizes data and prevents sql injection
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
  
?>


