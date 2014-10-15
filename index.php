<?php

// 
// Date: 03/31/2014

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$app = new \Slim\Slim(); //using the slim API
$user = "Karoline";

$app->get('/addBurger', 'addBurger'); //K add burger to the foodOrder table, it has not yet been checked out 
$app->get('/getRecentOrder', 'getRecentOrder'); //M get the most recent order from the the table and return it with price 
$app->get('/getCart', 'getCart'); //M get everything in the order table that is not yet checked out, return JSON
$app->get('/getPaymentInfo', 'getPaymentInfo'); //B public 
 $app->get('/getlogOut', 'logOut'); //end session and log out user 
// $app->get('/deleteOrder', 'deleteOrder'); //delete item no longer in cart

$app->post('/login', 'validateLogin'); //K remeber to set the user value 
$app->post('/createAccount', 'createAccount'); //N remeber to set the user value, udate table with new user info
$app->post('/addPaymentInfo', 'addPaymentInfo'); //N add payment info to the table 

// $app->put('/checkOut/:id', 'updateCheckedOut'); //B checked out update variables 


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

function addBurger() {
    
    $mysqli = getConnection(); //establish connection
    $app = \Slim\Slim::getInstance();
    $request = $app->request()->getBody();
    $order = json_decode($request, true); //decode the request needs a double because it is an JSON array of objects 
    $bool = 1;
    $query = "SELECT MAX(orderID) as orderID from foodOrders WHERE username= '".$user."'";
    $orderID = mysql_query($query);
    
   while ($r = mysql_fetch_assoc($orderID)) //find the max orderID and increment it
    {
       //echo $r["orderID"];   
        $orderID = $r["orderID"] +1 ;
        //echo $orderID;
    }
    
   foreach($order as $item) 
    {
        foreach ($item as $key => $val)
        {
            if ($val != 0) //insert only if that item has been selected 
            {
               $query = "INSERT INTO foodOrders (username, orderID, name, inCart) VALUES '".$user."'".$orderID."'".$key."'".$bool."'";
                mysql_query($query);
            }
        }
   }
    
    $mysqli->close(); //close instance of mysql 
} //addBurger 

function validateLogin() { //this is done
    global $user;
    $mysqli = getConnection(); //establish connection
    $app = \Slim\Slim::getInstance();
    $request = $app->request()->getBody();
        
    $loginInfo = json_decode($request, true);

    $username = $loginInfo['username'];
    $password = $loginInfo['password'];

    //echo $username;
    //echo $password;
  
   $sql = "SELECT username, firstname, lastname, email FROM USERS WHERE username ='".$username."' AND pw ='".$password."'";
   //echo $sql;

   $result= $mysqli->query( $sql);

   if (mysqli_num_rows($result) == 0)
   {  
       echo '{"error":{"text": "Login Info was not set" }}'; 
   }
   else
   {
       echo $request;
       $user = $username;
   }
   

   	
}


function getRecentOrder() { //get the most recent order from that user but also get the price 
    global $user;
    $con = getConnection();     
    $rows = array();
    $quantities = array();
    $prices = array();
    $TP = array();
 
//get content of recent order 

$sql = "select name, type from BurgerDetail natural join food natural join burger where orderID = (select recentorder from users where username = '".$user."')";
$result = mysqli_query($con, $sql); 
   	while($r = mysqli_fetch_assoc($result)) 
   	{
         $rows[] = $r;
   	 //$rows[$r[1]] = $r[0];
   	}

//get quantity of each of the burgers 
$sql = "select quantity from burgerdetail natural join food natural join burger where orderID = (select recentorder from users where username = '".$user."') group by burgerID";

$result = mysqli_query($con, $sql); 
$counter = 1;
   	while($r = mysqli_fetch_array($result)) 
   	{
        $quantities[$counter] = $r[0];
        $counter = $counter + 1;
   	}
//echo json_encode($quantities);

//get the price of each of the orders 
$counter = 1;
$sql = "select sum(price) from burgerdetail natural join food natural join burger where orderID = (select recentorder from users where username = '".$user."') group by burgerID";
$result = mysqli_query($con, $sql); 
   	while($r = mysqli_fetch_array($result)) 
   	{
    if( $quantities[$counter] > 1)
    {
        $prices[$counter] = $r[0] * $quantities[$counter];
        $price = ($r[0] * $quantities[$counter]);
    }
        else 
        {
            $prices[$counter] = $r[0];
            $price = $r[0];
        }        
        
    $counter = $counter + 1;
    $TP[0] = $TP[0] + $price;
   	}
$rows['prices'] = $prices;
$rows['quantities'] = $quantities;
$rows['priceOfOrder'] = $TP;
    
    echo json_encode($rows);
    mysqli_close($mysqli);
    
}

function getCart() { //get items in the cart with the most recent order 
	
    global $user;
    $connection = getConnection(); 
    $rows = array();
    $quantities = array();
    $prices = array();
    $TP = array();
    
    
    //GETCART 

    $sql = "select name, type from burgerdetail natural join food natural join burger where orderID = (select max(orderID) from burger)";

//GET THE CONTENT OF THE CART
 $result = mysqli_query($con, $sql); 
   	while($r = mysqli_fetch_assoc($result)) 
   	{
   	 $rows[] = $r;
   	}

//get quanity of burger 
$sql = "select quantity from BurgerDetail natural join food natural join burger where orderID = (select max(orderID) from burger) group by burgerID";
$counter = 1;

    $result = mysqli_query($con, $sql); 
    $counter = 1;
   	while($r = mysqli_fetch_array($result)) 
   	{
        $quantities[$counter] = $r[0];
        $counter = $counter + 1;
   	}

//get price of everything in order
$sql = "select sum(price) from BurgerDetail natural join food natural join burger where orderID = (select max(orderID) from burger) group by burgerID";
$counter = 1;
$result = mysqli_query($con, $sql); 
   	while($r = mysqli_fetch_array($result)) 
   	{
    if( $quantities[$counter] > 1)
    {
        $prices[$counter] = $r[0] * $quantities[$counter];
        $price = ($r[0] * $quantities[$counter]);
    }
        else 
        {
            $prices[$counter] = $r[0];
            $price = $r[0];
        }        
        
    $counter = $counter + 1;
    $TP[0] = $TP[0] + $price;
   	}
$rows['prices'] = $prices;
$rows['quantities'] = $quantities;
$rows['priceOfOrder'] = $TP;

   
echo json_encode($rows);

//    //get the order that has not yet been checked out 
//
//    $query = "select name, type from foodOrders natural join food where inCart and username ='".$user."' order by orderID";
//
//
//    $result = mysqli_query($connection, $query);
//
//   	while($r = mysqli_fetch_assoc($result)) 
//   	{
//   	 	$rows[] = $r;
//   	}
//    
//    $q1 = "select sum(price) from foodOrders natural join food where inCart = '1' and username ='".$user."' group by orderID";
//    $tp = mysqli_query($connection, $q1);
//    
//    while ($r = mysqli_fetch_assoc($tp)) 
//    {
//        $rows[] = $r;   
//    }

// echo json_encode($rows);
    mysqli_close($connection);
 } //getCart end 

function getPaymentInfo() { //return the different types of cards 
    $mysqli = getConnection();     
    $rows = array();
    $query = "select typeOfCard from paymentInfo where username = '".$user."'";
    $result = mysql_query($query);

   while ($r = mysql_fetch_assoc($result)) 
    {
        //echo $r["typeOfCard"];
        $rows[] = $r;
    }
    
    echo json_encode($rows);
    mysql_close($mysqli);
} //end

function logOut(){
    global $user; 
    //update the most recent order for that user 
    if ($user != "")
    {
    $sql = "update users set recentorder = (select max(orderID) from foodOrder where username = '".$user."') where username = '".user."'";
    }
    
    $user = "";
    echo true;
    
}

function deleteOrder($orderID){
    
    global $user;
    $mysqli = getConnection(); 
    $query = "delete from foodOrders where  orderID ='".$orderID."'";
    mysqli_query($mysqli, $query);
    mysqli_close($mysqli);
    
}

function addPaymentInfo()
{
    global $user;
    $con = getConnection();
    $app = \Slim\Slim::getInstance();
    $request = $app->request()->getBody();
    
    $paymentInfo = json_decode($request, true); //need to change this when we get it to work

    $TOC = $paymentInfo['typeOfCard'];
    $CN = $paymenInfo['cardNumber'];
    $A =$paymenInfo['adress'];
    $ZC = $paymenInfo['zipCode'];
    $S = $paymenInfo['state'];
    $ED = $paymenInfo['expireDate'];


    $stmt = $con->prepare("INSERT INTO paymentInfo (username, typeOfCard, cardNumber, adress, zipCode, state, expireDate) VALUES (?,?,?,?,?,?,?)"); 
    $stmt->bind_param('ssissss', $user, $TOC, $CN, $A, $ZC, $S, $ED);
    $stmt->execute();

}


function createAccount()
{
    global $user;
    $con = getConnection();
    $app = \Slim\Slim::getInstance();
    $request = $app->request()->getBody();
    $userInfo = json_decode($request, true);
    
    $user = $userInfo['username'];
    $pw = $userInfo['pw'];
    $firstname = $userInfo['firstname'];
    $lastname = $userInfo['lastname'];
    $email = $userInfo['email'];

    $sql = "SELECT username FROM USERS WHERE username ='".$user."'";

    $result = mysqli_query($con, $sql); 
	
    if (mysqli_num_rows($result) != 0)
    {          
        echo "username already in use"; 
        return false;
    }
    else
    {
        $stmt = $con->prepare("INSERT INTO users (username, pw, firstname, lastname, email) VALUES (?,?,?,?,?)"); 
        $stmt->bind_param('sssss', $user, $pw, $firstname, $lastname, $email);
    $stmt->execute();   
    }

}