console.log("in checkout js");
var rootURL = "http://localhost:8888/Burger-Bar/index.php";

displayCheckoutForms();
getCart();

function getCart(){
	
	/*
	var send = new Object();
	send.username = "Tom";
	send.password = "password";
	*/

	$.ajax({
      type: 'GET',
      url: rootURL+"/getCart",
      dataType: "json", // data type of response
      success: function(data, textStatus, jqXHR){
         console.log(data);
         addToCart(data);
      },
      error: function(jqXHR, textStatus, errorThrown){
         console.log(jqXHR, textStatus, errorThrown);
      }
   });
}

function addToCart(data){

	console.log(data.length);
	$("#orderSummary").append("<ul id='orderList'");

	for(var i =0; i<data.length; i++){

		var list = "<ul id='order" + i + "'></ul>";
		var burger = "<ul class='burger'></ul>";
		var topping = "<ul class='topping'></ul>";
		var bun = "<ul class='bun'></ul>";
		var sauces = "<ul class='sauces'></ul>";
		var cheese = "<ul class='cheese'></ul>";

		$("#order"+i).append(burger).append(topping).append(bun).append(sauces).append(cheese);

		$('#orderList').append(list);

		console.log(data[i]);
		$.each(data[i], function(k,v){
			alert(k + "  " + v);
			var order = "order"+j;
			$("#"+order + " ."+k).append("<li>"+ v +"</li>");
		});
	}
}

//Display either login confirmation or guest info forms
function displayCheckoutForms(){

    //test object - DELETE this
    var dummy = new Object;
    dummy.username = "testFirstName"
    dummy.lastname = "testLastName"
    dummy.cardNum = "testCardNumber"
    dummy.cardType = "testCardType"
    dummy.email = "testEmail"
    dummy.phone = "testPhone"
    //

    //if logged in
    if (localStorage.getItem("username")) {
        $("#userInfoLogin").css("display", "block");
        $("#userInfoGuest").css("display", "none");
        $('#usernameShow').html("Review and checkout, " + localStorage.username);
        //fill out forms using account data
        $("#firstNameFieldLog").val(dummy.username);
        $("#lastNameFieldLog").val(dummy.lastname);
        $("#cardNumFieldLog").val(dummy.cardNum);
        $("#cardTypeFieldLog").val(dummy.cardType);
        $("#emailFieldLog").val(dummy.email);
        $("#phoneFieldLog").val(dummy.phone);

    }
    //if not logged in
    else {
        $("#userInfoGuest").css("display", "block");
        $("#userInfoLogin").css("display", "none");
    }
}

//return to main screen
$("#checkoutBackButton").click(function(){
    window.location.href = "index.html";

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
