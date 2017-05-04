/* global $ */

$(document).ready(function () {
  $('#sendMessage').click(function(){
    $('#sendMessage').prop("disabled", true);
    var message = $('#input_text').val();
    var myVar = JSON.stringify({'myMessage': message}); 
    $.post("/API/php/postMessage.php", myVar,
    function(data){
        console.log("received data", data);
        var jsonResponse = JSON.parse(data);
        $('#message-error').text(jsonResponse['messageErr']);
        //$('#message-confirmation').text(jsonResponse['messageConfirmation']);
        $('#sendMessage').prop("disabled", false);
        $('#input_text').val("");
        
    });
  });
});


