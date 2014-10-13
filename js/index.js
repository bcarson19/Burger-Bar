console.log("in index javascript");
var rootURL = "http://localhost:8888/Burger-Bar/index.php";

$("#loginButton").click(function(){
	
	var send = new Object();
	send.username = "";//whatever username is
	send.password = "";//watever password is;

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
}
