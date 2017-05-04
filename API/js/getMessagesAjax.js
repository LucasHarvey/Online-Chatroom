/* global $ */

$(document).ready(function () {
    window.setInterval(function(){
    $.ajax({
      url: "/API/php/getMessages.php",
      cache: false, 
      type: "GET",
      success: function(data)
      {
        console.log("received data", data);
        var potentialLast = $("#messages").children().last();
        var lastIdUserSaw = 0;
        if(potentialLast.length > 0){
          lastIdUserSaw = potentialLast.attr('data-messageid');
        }
        var json = $.parseJSON(data);
        var messagesNeverSeen = json.filter(function(element){
          return element.messageID > lastIdUserSaw;
        });
        
        document.getElementById('messages').innerHTML += messagesToHTML(messagesNeverSeen, json[0]);
        
        function messagesToHTML(messagesNeverSeen, username){
          var newMessagesHTML = "";
          for (var i = 0; i<messagesNeverSeen.length;i++){
          
            if (messagesNeverSeen[i]['username'] != username){
              var audio = new Audio('/API/iphonenoti_cRjTITC7.mp3');
              audio.play();
            }
            
            var username = messagesNeverSeen[i]['username'];
            var message = messagesNeverSeen[i]['message'];
            var messageID = messagesNeverSeen[i]['messageID'];
            
            newMessagesHTML += "<p data-messageid = " + messageID + ">" + username + ":  " + message + "\n" + "</p>";
          }
          return newMessagesHTML;
        }
      }
    });
  },500);      
});
