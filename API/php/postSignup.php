<?php

//this file receives the username, password and confirm_password parameters through a 
//POST and outputs the usernameErr, passwordErr, confirmpasswordErr and registration_confirmation

//connect to database
include_once("connect_db_chatroom.php");
  
//define variables for later use
$usernameErr = $passwordErr = $confirmpasswordErr = "";
$username = $password = $confirmpassword = "";
$registration_confirmation = "";
$jsonReceipt = "";



//validate inputs
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  
  //get post data, decode it and store it in the $jsonReceipt variable
  $jsonReceipt = json_decode(file_get_contents('php://input'),TRUE);

  if (empty($jsonReceipt["username"])){
    $usernameErr = "*Please enter a username.";
  }
  elseif (strlen($jsonReceipt["username"])>16){
    $usernameErr = "Please enter a shorter username";
  }
  else{
    $username = test_input($jsonReceipt["username"]);
    
    //ensure that the username does not already exist
    $sql = "SELECT username FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $usernameErr = "Username $username has already been taken.";
    }
  }
    
  if (empty($jsonReceipt["password"])){
    $passwordErr = "*Please enter a password.";
  }
  
  else{
      $password = (test_input($jsonReceipt["password"]));
      //encrypt the password
      $encrypted_password = md5(test_input($jsonReceipt["password"]));
  }

  if (empty($jsonReceipt["confirm_password"])){
    $confirmpasswordErr = "Please confirm password.";
  }
  
  else{
    $confirmpassword = (test_input($jsonReceipt["confirm_password"]));
    if($confirmpassword != "" && $password != $confirmpassword){
      $confirmpasswordErr = "Passwords do not match.";
    }
  }

}


//if the $username, $encrypted_password and $confirmpassword variables are not empty and the error variables are empty, 
//insert the data into the users table

if ($usernameErr == "" && $passwordErr == "" && $confirmpasswordErr == "" && $username != "" && $encrypted_password != "" && $confirmpassword != ""){
  $sql = "INSERT INTO users (username, password) VALUES ('".$username."','".$encrypted_password."')";

  if ($conn->query($sql) === TRUE) {
    $registration_confirmation = "You have been registered successfully!";
    $username = $lastname = $password = $confirmpassword = "";
  }
  else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  //close the connection to the database
  $conn->close();
}

//encode the error variables and signup confirmation into JSON format, store it in the $jsonResponse variable and echo it
$jsonResponse = json_encode(array('usernameErr' => $usernameErr, 'passwordErr' => $passwordErr, 'confirmpasswordErr' => $confirmpasswordErr, 'registration_confirmation' => $registration_confirmation));
echo $jsonResponse;

//function which tests the different inputs, sanitizes data and prevents sql injection
function test_input($data) { 
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>



