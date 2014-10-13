console.log("in checkout js");
var rootURL = "http://localhost:8888/Burger-Bar/index.php";

$(document).ready(function(){
	
	var send = new Object();
	send.username = "Tom";
	send.password = "password";


	$.ajax({
      type: 'POST',
      url: rootURL+"/loginIn",
      dataType: "json", // data type of response
      data: send,
      success: function(data, textStatus, jqXHR){
         console.log(data);
      },
      error: function(jqXHR, textStatus, errorThrown){
         console.log(jqXHR, textStatus, errorThrown);
      }
   });

});

$('#checkoutButton').click(function(){
	var send = new Object();
	send.firstName = $('#firstNameField').val();
	send.lastName = $('#lastNameField').val();
	send.email = $('#emailField').val();
	send.password = $('#passwordField').val();

	console.log(send);

	var badField = false;
	var badFieldString = "";
	for (var key in send) {
		if(!send[key]){
			badFieldString += $("#" + key + "Field").attr("placeholder").slice(1) + ",";
			badField = true;
		}
	}
	if (badField){
		alert("Make sure to input" + badFieldString.substring(0, badFieldString.length-1) + "!");
		return 0;
	}

   $.ajax({
      type: 'POST',
      url: rootURL+"/checkout",
      dataType: "json", // data type of response
      data: send,
      success: function(data, textStatus, jqXHR){
         
      },
      error: function(jqXHR, textStatus, errorThrown){
         console.log(jqXHR, textStatus, errorThrown);
      }
   });



});