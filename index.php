<?php

// 
// Date: 03/31/2014

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$app = new \Slim\Slim(); //using the slim API
$user = "Karoline"; //REMEBER TO REMOVE THIS

$app->post('/addBurger', 'addBurger'); //K add burger to the FoodOrder table, it has not yet been checked out 
$app->get('/getRecentOrder', 'getRecentOrder'); //M get the most recent order from the the table and return it with price 
$app->get('/getCart', 'getCart'); //M get everything in the order table that is not yet checked out, return JSON
$app->get('/getPaymentInfo', 'getPaymentInfo'); //B public 
$app->get('/logout', 'logOut'); //end session and log out user 

$app->put('/deleteBurger/:burgerID', 'deleteBurger'); //delete 

$app->get('/startOrder', 'startOrder'); //B public 

$app->post('/login', 'validateLogin'); //K remeber to set the user value 
$app->get('/createAccount', 'createAccount'); //N remeber to set the user value, udate table with new user info

$app->run();

function getConnection() {
	$dbConnection = new mysqli("localhost", "root", "root", "DBBurger"); //put in your password
  // Check mysqli connection
  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
  return $dbConnection;
}

function deleteBurger($burgerID)
{
    $con = getConnection();
    $app = \Slim\Slim::getInstance();
    $request = $app->request()->getBody();
    
    
    $sql = "delete from burger where burgerID = '".$burgerID."'";
    //echo $sql;
    $con->query($sql);

    $con->close();
}

function startOrder()
{
    global $user;
    $con = getConnection();
    
    $stmt = "insert into foodOrder(username) values ('".$user."')";
    $con->query($stmt);
}

function addBurger() 
{

	global $user;
    $mysqli = getConnection(); //establish connection
    $app = \Slim\Slim::getInstance();      
    $request = $app->request()->getBody();
    $order = json_decode($request, true); //decode the request needs a double because it is an JSON array of objects 
    $quantity;
    foreach ($order as $part)
    { 
        if(array_key_exists("quantity", $part ))
        {
            $quantity = $part['quantity'];
        }
    }
    //adding a burger and quantity 
    $sql = "insert into burger(orderID, quantity) values ((select max(orderID) from FoodOrder where username = '".$user."') , '".$quantity."')";
    $mysqli->query($sql);
    

    $sql = "select max(burgerID) from burger"; //get the current burgerID
    $result= $mysqli->query($sql);
    $row = mysqli_fetch_row($result);
    $burgerID = $row[0];
    
   $sql = $mysqli->prepare("INSERT INTO BurgerDetail(name, burgerID) values (?, ?)");         

    foreach ($order as $part)
    {
        if(array_key_exists("name", $part ))
        {
        $name = $part['name'];
        $sql->bind_param('si', $name, $burgerID);
        $sql->execute();
        //printf("%d rows ", $sql->affected_rows);
        }

    }

    $mysqli->close(); //close instance of mysql 
} //addBurger 

function validateLogin() 
{ //this is done
    $mysqli = getConnection(); //establish connection
    $app = \Slim\Slim::getInstance();
    $request = $app->request()->getBody();
    $loginInfo = json_decode($request, true);

    $username = $loginInfo['username'];
    $password = $loginInfo['password'];
  
   	$sql = "SELECT username, firstname, lastname, email FROM users WHERE username ='".$username."' AND pw ='".$password."'";

   	$result= mysqli_query($mysqli, $sql);

   	if (mysqli_num_rows($result) > 0) 
   	{
   		global $user;
   		$user = $username;
   		echo $request;
        startOrder();
   	}

   	mysqli_close($mysqli);
}


function getRecentOrder() 
{ //get the most recent order from that user but also get the price, the most recent order will be the one with the highest orderId because of autoincrement 

    global $user;
    $con = getConnection();     
    $rows = array();
    $quantities = array();
    $prices = array();
    $prices['totalPrice'] = 0;
    
    //if you are not logged in it will return an empty cart
        if ($user == "unloggedIn")
        {
            echo json_encode($rows);
            exit;
        }
 
//get content of recent order 

	$sql = "select name, type from BurgerDetail natural join Food natural join burger where orderID = (select recentorder from users where username = '".$user."')";
	$result= $con->query($sql);
    
    if (mysqli_num_rows($result) == 0)
    {
        echo json_encode($rows);
        exit;
    }
 
    
   	while($r = mysqli_fetch_assoc($result)) 
   	{
         $rows[] = $r;
   	}

//get quantity of each of the burgers 
	$sql = "select quantity from BurgerDetail natural join Food natural join burger where orderID = (select recentorder from users where username = '".$user."') group by burgerID";

	$result= $con->query($sql);
	$counter = 1;
   	while($r = mysqli_fetch_array($result)) 
   	{
        $quantities[$counter] = $r[0];
        $counter = $counter + 1;
   	}
//echo json_encode($quantities);

//get the price of each of the orders 
	$counter = 1;
	$sql = "select sum(price), burgerID from BurgerDetail natural join Food natural join burger where orderID = (select recentorder from users where username = '".$user."') group by burgerID";
	$result= $con->query($sql);

   	while($r = mysqli_fetch_array($result)) 
   	{
        $prices[$counter] = $r[0] * $quantities[$counter];
        $price = ($r[0] * $quantities[$counter]); 
        $burgerID = $r[1];
    	$counter = $counter + 1;

    	$prices['totalPrice'] = $prices['totalPrice'] + $price;
        
   	}

	$rows['prices'] = $prices;
	$rows['quantities'] = $quantities;
    $rows['burgerID'] = $burgerID;
    
    echo json_encode($rows);
    mysqli_close($con);
}

function getCart() { //get items in the cart with the most recent order, gets the highest orderId without regard to the user 
	
    global $user;
    $mysqli = getConnection(); //establish connection
    $app = \Slim\Slim::getInstance();
    $request = $app->request()->getBody();
    $info = json_decode($request, true);
    $rows = array();
    $quantities = array();
    $prices = array();
    $burgerID = array();
    
    //GETCART 

    $sql = "select name, type, burgerID from BurgerDetail natural join Food natural join burger where orderID = (select max(orderID) from burger natural join foodOrder where username = '".$user."')";

 	$result= $mysqli->query($sql);
    
    if (mysqli_num_rows($result) == 0)
    {
        echo json_encode($rows);
        exit;
    }

    
   	while($r = mysqli_fetch_assoc($result)) 
   	{
   		$rows[] = $r;
   	}

    //get quanity of burger 
    
	$sql = "select quantity from BurgerDetail natural join Food natural join burger where orderID = (select max(orderID) from burger) group by burgerID";
	$counter = 1;

    $result = $mysqli->query( $sql); 

    $counter = 1;
   	while($r = mysqli_fetch_array($result)) 
   	{
        $quantities[$counter] = $r[0];
        $counter = $counter + 1;
   	}

//get price of everything in order
	$sql = "select sum(price) from BurgerDetail natural join Food natural join burger where orderID = (select max(orderID) from burger) group by burgerID";
	$counter = 1;

	$result = $mysqli->query($sql); 

	$prices['totalPrice'] = 0;
    
   	while($r = mysqli_fetch_array($result)) 
   	{
        $prices[$counter] = $r[0] * $quantities[$counter];
        $price = ($r[0] * $quantities[$counter]);       
    	$counter = $counter + 1;

    	$prices['totalPrice'] = $prices['totalPrice'] + $price;
   	}

	$rows['prices'] = $prices;
	$rows['quantities'] = $quantities;
    $rows['burgerID'] = $burgerID;
   
	echo json_encode($rows);
	mysqli_close($mysqli);
 } //getCart end 

function getPaymentInfo() { //return the different types of cards 
    $mysqli = getConnection();     
    $rows = array();
    $query = "select typeOfCard from paymentInfo where username = '".$user."'";
    $result = $mysqli->query($query);
    
   while ($r = mysql_fetch_assoc($result)) 
    {
        $rows[] = $r;
    }
    
    echo json_encode($rows);
    mysql_close($mysqli);
} //end

function logOut(){
    $mysqli = getConnection(); 
    global $user; 
    //update the most recent order for that user 
    if ($user != "")
    {
    	$sql = "update users set recentorder = (select max(orderID) from FoodOrder where username = '".$user."') where username = '".$user."'";
        $mysqli->query($sql);
        
    }
    
    $user = "";
    startOrder();
    echo true;
    
}

function deleteOrder($orderID){
    
    global $user;
    $mysqli = getConnection(); 
    $query = "delete from FoodOrders where  orderID ='".$orderID."'";
    $mysqli->query($sql);
    mysqli_close($mysqli);
}


function createAccount()
{
    global $user;
    $con = getConnection();
    $app = \Slim\Slim::getInstance();
    $request = $app->request()->getBody();
    $userInfo = json_decode($request, true);
            $username = $userInfo['username'];
            $firstname = $userInfo['firstname'];
            $lastname = $userInfo['lastname'];
            $password = $userInfo['password'];
            $phonenumber= $userInfo['phonenumber'];
            $creditcardnumber = $userInfo['creditcardnumber'];
            $creditcardtype = $userInfo['creditcardtype'];
            $email = $userInfo['email'];
        
    //chech if username is already in use  
    $sql = "SELECT username FROM users WHERE username ='".$username."'";

    $result = $con->query($sql); 


    if (mysqli_num_rows($result) != 0)
    {          
        echo "username already in use"; 
        return false;
    }
    else 
    {
        $user = $username;
        $sql = $con->prepare("INSERT INTO users(username, pw, firstname, lastname, email, phonenumber) values (?,?,?,?,?,?)");
        $sql->bind_param('ssssss', $username, $password, $firstname, $lastname,     $email, $phonenumber);
        $sql->execute();
         $sql = $con->prepare("INSERT INTO paymentInfo(username, cardNumber, typeOfCard) values (?,?,?)");
        $sql->bind_param('sss', $username, $creditcardnumber, $creditcardtype);
        $sql->execute();
        echo true;
    }  


?>