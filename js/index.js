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
	$.ajax({
      type: 'GET',
      url: rootURL+"/getCart",
      dataType: "json", // data type of response
      success: function(data, textStatus, jqXHR){
         console.log(data);
         addToCart( $("#cart") ,data);
         /*for(var i=0; i<data.length; i++){
         	console.log(data[i].name + "  " + data[i].type);
         }*/
      },
      error: function(jqXHR, textStatus, errorThrown){
      	alert("getCart error!");
         console.log(jqXHR, textStatus, errorThrown);
      }
   });



	if($("#cart").children("ul").length > 0){
		//console.log($("#cart").children("ul"));
		$("#cart button").show();
	}
	else{
		$("#cart").append("<p>there are no items in your cart!</p>");
		$("#cart button").hide();
	}
}


function addToCart(addTo, data){

	addTo.append("<ul id='orderList'");
	console.log(addTo);

	for (var i = 1; i > 0; i--) {

		var list = "<ul id='order" + i + "'></ul>";
		var burger = "<ul class='Burger'></ul>";
		var topping = "<ul class='Topping'></ul>";
		var bun = "<ul class='Bun'></ul>";
		var sauces = "<ul class='Sauces'></ul>";
		var cheese = "<ul class='Cheese'></ul>";

		$('#orderList').append(list);
		console.log($('#orderList'));
		$("#order"+i).append(burger);
		//.append(topping).append(bun).append(sauces).append(cheese);

		/*for(var j=0; j<data.length; j++){
			console.log(data[j].name + "  " + data[j].type);
			var food = data[j].name;
			var type = data[j].type;
			var order = "order"+i;
			console.log($("#"+order + "> ."+type));
			$("#"+order + ">  ."+type).append("<li>"+ food +"</li>");
		}*/
	}
}

//to login
$("#loginButton").click(function(){

	alert("about to run login");
	
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
         localStorage.username = data['username'];
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
         console.log(jqXHR, textStatus, errorThrown);
      }
   });

});



/*Add Burger function starter - Brandon C*/
$('#addBurger').click(function(){
	var list = $(":checked");
	var send = new Object();
	
	for(var i =0; i<list.length; i++){
		console.log(list[i]["defaultValue"] + "  " + list[i]["name"]);
		send.name = list[i]["defaultValue"];
		send.type = list[i]["name"];
		console.log(send);
	}
	
	
	$.ajax({
      type: 'POST',
      url: rootURL+"/cart",
      dataType: "json", // data type of response
      data: send,
      success: function(data, textStatus, jqXHR){
         console.log(data);
      },
      error: function(jqXHR, textStatus, errorThrown){
         console.log(jqXHR, textStatus, errorThrown);
      }
   });

	//console.log(list);
});
