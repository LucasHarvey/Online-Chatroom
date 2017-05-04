/* global $ */

$(document).ready(function () {
 var jsonResponse;
 $('#login').click(function(){
  $('#login').prop("disabled", true);
  var username = $('#username').val();
  var password = $('#password').val();
  var myVar = JSON.stringify({'username': username,"password": password}); 
  $.post("/API/php/postLogin.php", myVar,
  function(data){
   console.log("received data", data);
   jsonResponse = JSON.parse(data);
   $('#username-error').text(jsonResponse['usernameErr']);
   $('#password-error').text(jsonResponse['passwordErr']);
   $('#login-fail').text(jsonResponse['loginFail']);
   $('#login').prop("disabled", false);
   $('#username').val("");
   $('#password').val("");
   if(jsonResponse['usernameErr'] == "" && jsonResponse['passwordErr'] == "" && jsonResponse['loginFail'] == ""){
    window.location = '/API/html/home.php';
   }
  });
  
 });
});