/* global $ */

$(document).ready(function () {
  $('#submit').click(function(){
      $('#submit').prop("disabled", true);
      var username = $('#username').val();
      var password = $('#password').val();
      var confirm_password = $('#confirm_password').val();
      var myVar = JSON.stringify({'username': username,'password': password, 'confirm_password': confirm_password}); 
      $.post("/API/php/postSignup.php", myVar,
      function(data){
          console.log("received data", data);
          var jsonResponse = JSON.parse(data);
          $('#username-error').text(jsonResponse['usernameErr']);
          $('#password-error').text(jsonResponse['passwordErr']);
          $('#password-confirmation-error').text(jsonResponse['confirmpasswordErr']);
          $('#registration-confirmation').text(jsonResponse['registration_confirmation']);
          
          if (jsonResponse['usernameErr'] == "" && jsonResponse['passwordErr'] == "" && jsonResponse['confirmpasswordErr'] == "" && jsonResponse['registration_confirmation'] == "You have been registered successfully!"){
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
            document.getElementById('confirm_password').value = '';
          }
          $('#submit').prop("disabled", false);
         
      });
      });
  });
