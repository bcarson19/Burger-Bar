console.log("in index javascript");
var rootURL = "http://localhost:8888/Burger-Bar/index.php";
//var rootURL = "http://localhost/Burger-Bar/index.php";

/*Display either login form or past order*/
changeLoginOrRecent();
showCart();

function changeLoginOrRecent(){
	console.log(localStorage.getItem("username"));
    if (localStorage.getItem("username")) {

    	console.log("username exists"); //display recent order
        $("#lastOrder").css("display", "block");
        $("#loginForm").css("display", "none");
        $('#usernameShow').html("Welcome " + localStorage.username + "!");
    }
    else {
    	console.log("no username"); //display Login screen
        $("#lastOrder").css("display", "none");
        $("#loginForm").css("display", "block");
    }
}

function showCart(){
	//console.log($("#cart").children("ul").length > 0);

	$("#cart").html("");
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

	//alert("about to run login");
	
	var send = new Object();
	var username = $("#usernameField").val();//whatever username is
	var password = $("#passwordField").val();//watever password is;

	send = {"username": username, "password": password};
	send = JSON.stringify(send);

	console.log(send);
	//alert(username + "  " + password);

	$.ajax({
      type: 'POST',
      url: rootURL+"/login",
      dataType: "json", // data type of response
      data: send,
      success: function(data, textStatus, jqXHR){
         console.log(data);
         localStorage.username = data['username'];
         var nameString = ""+data['username']+"";
         window.location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown){
      	alert("Login invalid!");
         console.log(jqXHR, textStatus, errorThrown);
      }
   });
	//alert("wait");
	//window.location.reload();
	//changeLoginOrRecent();

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
	send.password = $("#passwordCAField").val();

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
		//console.log(list[i]["defaultValue"] + "  " + list[i]["name"]);
		var name = list[i]["defaultValue"];
		var type = list[i]["name"];
		send[i] = {name: name, type: type};
		//console.log(send[i]);
	}
	
	send.quantity = +$("#quantity_textField").val();
	console.log(send);
	
	$.ajax({
      type: 'GET',
      url: rootURL+"/addBurger",
      dataType: "json", // data type of response
      data: send,
      success: function(data, textStatus, jqXHR){
         console.log(data);
         showCart();
      },
      error: function(jqXHR, textStatus, errorThrown){
         console.log(jqXHR, textStatus, errorThrown);
      }
   });

	//console.log(list);
});

var quantity = +document.getElementById("quantity_textField").value;

function changeQuantity() {
	var plus = document.getElementsByTagName('img')[0]
	var minus = document.getElementsByTagName('img')[1]
	
	plus.onclick=function() {
	//console.log("+");
	quantity = quantity + 1;
	
	if (quantity <= 30) {
			document.getElementById("quantity_textField").value = quantity;
		}
		else {
			document.getElementById("quantity_textField").value = 30;
			quantity = 30;
		}
	}
	minus.onclick=function() {
	//console.log("-");
	quantity = quantity - 1;
		if (quantity >= 0) {
			document.getElementById("quantity_textField").value = quantity;
		}
		else {
			document.getElementById("quantity_textField").value = 0;
			quantity = 0;
		}
	}
}
changeQuantity();


$("#logoutButton").click(function(){

$.ajax({
      type: 'GET',
      url: rootURL+"/logout",
      dataType: "json", // data type of response
      success: function(data, textStatus, jqXHR){
        localStorage.clear();
        window.location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown){
         console.log(jqXHR, textStatus, errorThrown);
      }
   });

});