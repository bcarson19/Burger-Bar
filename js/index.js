console.log("in index javascript");
var rootURL = "http://localhost:8888/Burger-Bar/index.php";

/*Display either login form or past order*/
changeLoginOrRecent();

function changeLoginOrRecent(){
    if (localStorage.username) {
    	console.log("username exists"); //display recent order
        document.getElementById("login").style.display = "none";
        document.getElementById("lastOrder").style.display = "block";


    }
    else {
    	console.log("no username"); //display Login screen
        document.getElementById("lastOrder").style.display = "none";
        document.getElementById("login").style.display = "block";
    }
}


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
});
