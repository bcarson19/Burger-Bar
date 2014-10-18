console.log("in checkout js");
var rootURL = "http://localhost:8888/Burger-Bar/index.php";

displayCheckoutForms();
getCart();

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


function addToCart(addTo, name, type, burgerID){

	//$("#cart p").hide();
	//console.log("showing this ");
	//console.log(this);

	if($("#orderSummary").children("ul").length === 0){
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
      }
   });

	
}
