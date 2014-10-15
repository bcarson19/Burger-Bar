console.log("in index javascript");
var rootURL = "http://localhost:8888/Burger-Bar/index.php";

/*Display either login form or past order*/
changeLoginOrRecent();
showCart();

function changeLoginOrRecent(){
    if (localStorage.username) {
    	console.log("username exists"); //display recent order
        $("#lastOrder").css("display", "block");
        $("#loginForm").css("display", "none");


    }
    else {
    	console.log("no username"); //display Login screen
        $("#lastOrder").css("display", "none");
        $("#loginForm").css("display", "block");
    }
}

function showCart(){
	//console.log($("#cart").children("ul").length > 0);
	if($("#cart").children("ul").length > 0){
		//console.log($("#cart").children("ul"));
		$("#cart button").show();
	}
	else{
		$("#cart").append("<p>there are no items in your cart!</p>");
		$("#cart button").hide();
	}
}


function addToCart(data){

	$("#orderSummary").append("<ul id='orderList'");

	for (var i = data.length; i >= 0; i--) {

		var list = "<ul id='order" + j + "'></ul>";
		var burger = "<ul class='burger'></ul>";
		var topping = "<ul class='topping'></ul>";
		var bun = "<ul class='bun'></ul>";
		var sauces = "<ul class='sauces'></ul>";
		var cheese = "<ul class='cheese'></ul>";

		list.append(burger).append(topping).append(bun).append(sauces).append(cheese);

		$('$orderList').append(list);

		$.each(data[i], function(k,v){
			alert(k + "  " + v);
			var order = "order"+j;
			$("#"+order + "> li ."+k).append("<li>"+ v +"</li>");
		});
	}
}

//to login
$("#loginButton").click(function(){
	
	var send = new Object();
	var username = $("#usernameField").val();//whatever username is
	var password = $("#passwordField").val();//watever password is;

	send = {"username": username, "password": password};
	send = JSON.stringify(send);

	console.log(send);

	$.ajax({
      type: 'POST',
      url: rootURL+"/login",
      dataType: "json", // data type of response
      data: send,
      success: function(data, textStatus, jqXHR){
         console.log(data);
         localStorage.loginInfo = data;
      },
      error: function(jqXHR, textStatus, errorThrown){
      	alert("Login invalid!");
         console.log(jqXHR, textStatus, errorThrown);
      }
   });
	//window.location.reload();


});


//to go to checkout screen
$("#checkoutButton").click(function(){
	window.location.href = "checkout.html";

});

//to show create account form
$("#createAccountButton").click(function(){
	$("#loginForm").hide();
	$("#createAccountForm").show();
});


//To create account
$("#createAccountSubmitButton").click(function(){
	var send = new Object();
	send.firstName = $("#firstNameField").val();
	send.lastName = $("#lastNameField").val();
	send.username = $("#userNameField").val();
	send.password = $("#passwordField").val();

	console.log(send);

	$.ajax({
      type: 'POST',
      url: rootURL+"/createAccount",
      dataType: "json", // data type of response
      data: send,
      success: function(data, textStatus, jqXHR){
         console.log(data);
         localStorage.loginInfo = data;
      },
      error: function(jqXHR, textStatus, errorThrown){
      	alert("Account invalid!");
         console.log(jqXHR, textStatus, errorThrown);
      }
   });

});



/*Add Burger function starter - Brandon C*/
$('#addBurger').click(function(){
console.log("test");
});
