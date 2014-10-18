console.log("in index javascript5");
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
        showRecentOrder();
    }
    else {
    	console.log("no username"); //display Login screen
        $("#lastOrder").css("display", "none");
        $("#loginForm").css("display", "block");
    }
}

function showRecentOrder(){

	var send = new Object();
	send.username = localStorage.getItem("username");

	$.ajax({
      type: 'GET',
      url: rootURL+"/getRecentOrder",
      dataType: "json", // data type of response
      data: send,
      success: function(data, textStatus, jqXHR){
         console.log(data);
         //console.log(data[0]);
         var i = 0;
         for(var item in data){
         	i++;
         	//console.log(data[item]);
         	if(data[item].name){
         		//console.log(data[item].name + "  " + data[item].type + "  " + data[item].burgerID);
         		var name = data[item].name;
         		var type = data[item].type;
         		//var burgerID = +data[item].burgerID;
         		//console.log(burgerID);
         		addToRecentOrder($("#lastOrder"), name, type, Math.floor(i/4));
         	}

         	//console.log(data[i].name + "  " + data[i].type + "  " + data[i].burgerID);
         }
         $('#lastOrder').prepend("<h4 id='welcome'>Welcome " + localStorage.username + "!</h4>");

         //console.log(data[quantity].totalPrice);
         //addToCart( $("#cart") ,data);
         
      },
      error: function(jqXHR, textStatus, errorThrown){
      	alert("getRO error!");
         console.log(jqXHR, textStatus, errorThrown);
      }
   });

}

function addToRecentOrder(addTo, name, type, burgerID){
	//console.log(burgerID);
	console.log("in recent order");

	if(addTo.children("ul").length === 0){
		addTo.prepend("<ul id='recentOrderList'>");
	}

	//console.log(addTo.append("<ul id='orderList'"));

	if($("#recentOrderList").children("#recentOrder"+burgerID).length === 0){
		var list = "<ul id='recentOrder" + burgerID + "'></ul>";
		//console.log(list);
		var burger = "<ul class='Burger'></ul>";
		var topping = "<ul class='Topping'></ul>";
		var bun = "<ul class='Bun'></ul>";
		var sauces = "<ul class='Sauce'></ul>";
		var cheese = "<ul class='Cheese'></ul>";
		$('#recentOrderList').append(list);
		//console.log($('#orderList'));
		$("#recentOrder"+burgerID).append("Burger "+burgerID).append(burger).append(topping).append(bun).append(sauces).append(cheese);
	}
	
	$("#recentOrder"+burgerID + ">  ."+type).append("<li>"+ name +"</li>");

}

function showCart(){
	//console.log($("#cart").children("ul").length > 0);

	$("#cart ul").remove();
	$("#cart h4").remove();
	$.ajax({
      type: 'GET',
      url: rootURL+"/getCart",
      dataType: "json", // data type of response
      success: function(data, textStatus, jqXHR){
         console.log(data);
         //console.log(data[0]);
         for(var item in data){
         	//console.log(data[item]);
         	if(data[item].name){
         		//console.log(data[item].name + "  " + data[item].type + "  " + data[item].burgerID);
         		var name = data[item].name;
         		var type = data[item].type;
         		var burgerID = data[item].burgerID;
         		addToCart($("#cart"), name, type, burgerID);
         	}

         	//console.log(data[i].name + "  " + data[i].type + "  " + data[i].burgerID);
         }
         //console.log(data.prices.totalPrice);
         if(data.prices){
         	var price = data.prices.totalPrice.toFixed(2);
         	$("#cart > ul").append("<ul class='totalPrice'><li>Total Price:  $"+ price +"</li></ul>");
         }
         //console.log(data[quantity].totalPrice);
         //addToCart( $("#cart") ,data);
         $("#cart").prepend("<h4>Your cart</h4>");
      },
      error: function(jqXHR, textStatus, errorThrown){
      	alert("getCart error!");
         console.log(jqXHR, textStatus, errorThrown);
      }
   });


}

function showCartButton(){
	if($("#cart").children("ul").length > 0){
		//console.log($("#cart").children("ul"));
		$("#checkoutButton").show();
		//console.log($("#checkoutButton"));
		$("#cart p").hide();
	}
	else{
		$("#cart").append("<p>there are no items in your cart!</p>");
		$("#checkoutButton").hide();
	}
}


function addToCart(addTo, name, type, burgerID){

	//$("#cart p").hide();
	//console.log("showing this ");
	//console.log(this);

	if($("#cart").children("ul").length === 0){
		addTo.prepend("<ul id='orderList'>");
	}

	//console.log(addTo.append("<ul id='orderList'"));

	if($("#orderList").children("#order"+burgerID).length === 0){
		var list = "<ul id='order" + burgerID + "'><a class='x'><img src='img/x.png'></a></ul>";
		//console.log(list);
		var burger = "<ul class='Burger'></ul>";
		var topping = "<ul class='Topping'></ul>";
		var bun = "<ul class='Bun'></ul>";
		var sauces = "<ul class='Sauce'></ul>";
		var cheese = "<ul class='Cheese'></ul>";
		$('#orderList').append(list);
		//console.log($('#orderList'));
		$("#order"+burgerID).append("Burger "+burgerID).append(burger).append(topping).append(bun).append(sauces).append(cheese);
	}

	$("#foo").unbind('click');
	$('.x').on('click', function(){
		var id = $(this).closest("ul").attr("id");
		id = id.slice(-1);
		clickedDelete(id);

	});
	
	$("#order"+burgerID + ">  ."+type).append("<li>"+ name +"</li>");
	//console.log($("#order"+burgerID + ">  ."+type + " li"));

	showCartButton();
	
}

function clickedDelete(id){

	var send = new Object();
	send.burgerID = id;

	console.log(send);

	$.ajax({
      type: 'PUT',
      url: rootURL+"/deleteBurger/"+id,
      dataType: "json", // data type of response
      //data: send,
      success: function(data, textStatus, jqXHR){
         console.log(data);
         window.location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown){
      	alert("Burger not deleted");
         console.log(jqXHR, textStatus, errorThrown);
         window.location.reload();
      }
   });

	
}

//to login
$("#loginButton").click(function(){

	//alert("about to run login");
	
	var send = new Object();
	var username = $("#usernameField").val();//whatever username is
	var password = $("#passwordField").val();//watever password is;

	send = {"username": username, "password": password};

    //check for default
    for (var key in send) {
    	//alert(send[key]);
        if (send[key] == "") {
            $("#" + key + "Field").css("background-color", "red");
            //return 0;
        }
        else {
            $("#" + key + "Field").css("background-color", "white");
        }
    }

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

//go back from create account form
$("#createAccountBackButton").click(function(){
    $("#createAccountForm").hide();
    $("#loginForm").show();
});

//To create account
$("#createAccountSubmitButton").click(function(){
	var send = new Object();
	send.firstname = $("#firstNameField").val();
	send.lastname = $("#lastNameField").val();
	send.username = $("#userNameField").val();
	send.password = $("#passwordCAField").val();
	send.email = $("#emailField").val();
	send.phonenumber = $("#phoneNumberField").val();
	send.creditcard = $("#cardNumberField").val();
	send.cardtype = $('#cardType>option:selected').text();

	send = JSON.stringify(send);
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
	
	var len = 0;

	for(var i =0; i<list.length; i++){
		//console.log(list[i]["defaultValue"] + "  " + list[i]["name"]);
		var name = list[i]["defaultValue"];
		var type = list[i]["name"];
		send[i] = {name: name, type: type};
		//console.log(send[i]);
		len = i;
	}

	var quan = +$("#quantity_textField").val();
	send[len+1] = {quantity: quan}
	
	//send.quantity = +$("#quantity_textField").val();

	send = JSON.stringify(send);
	console.log(send);
	
	$.ajax({
      type: 'POST',
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
		if (quantity >= 1) {
			document.getElementById("quantity_textField").value = quantity;
		}
		else {
			document.getElementById("quantity_textField").value = 1;
			quantity = 1;
		}
	}
}
changeQuantity();


$("#logoutButton").click(function(){

	startOrder();

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


function startOrder(){

	$.ajax({
      type: 'GET',
      url: rootURL+"/startOrder",
      dataType: "json", // data type of response
      success: function(data, textStatus, jqXHR){
        localStorage.clear();
        window.location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown){
         console.log(jqXHR, textStatus, errorThrown);
      }
   });
}



