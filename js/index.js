console.log("in index javascript");
var rootURL = "http://localhost:8888/Burger-Bar/index.php";

/*Display either login form or past order*/
changeLoginOrRecent();

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


$("#loginButton").click(function(){
	
	var send = new Object();
	send.username = $("#usernameField").val();//whatever username is
	send.password = $("#passwordField").val();//watever password is;

	console.log(send);

	$.ajax({
      type: 'POST',
      url: rootURL+"/login",
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



/*Add Burger function starter - Brandon C*/
$('#addBurger').click(function(){
console.log("test");
});
