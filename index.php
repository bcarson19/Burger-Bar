<?php

// 
// Date: 03/31/2014

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(); //using the slim API
$user = "";

$app->get('/addBurger', 'addBurger'); //K add burger to the foodOrder table, it has not yet been checked out 
$app->get('/recentOrder', 'getRecentOrder'); //M get the most recent order from the the table and return it with price 
$app->get('/getCart', 'getCart'); //M get everything in the order table that is not yet checked out, return JSON
$app->get('/getPaymentInfo', 'getPaymentInfo'); //B public 
$app->get('/logOut', 'logOut'); //end session and log out user 
$app->get('/deleteOrder', 'deleteOrder'); //delete item no longer in cart

$app->post('/loginIn', 'validateLogin'); //K remeber to set the user value 
$app->post('/createAccount', 'createAccount'); //N remeber to set the user value, udate table with new user info
$app->post('/addPaymentInfo', 'addPaymentInfo'); //N add payment info to the table 

$app->put('/checkOut/:id', 'updateCheckedOut'); //B checked out update variables 


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
    $sql = "SELECT username, firstname, lastname, email FROM USERS WHERE username = :username AND pw = :pw";
    
    try 
		{
			if(isset($loginInfo))
			{
                $user = $loginInfo->username; //set the username 
				$stmt = $mysqli->prepare($sql);
				$stmt->bindParam("username", $loginInfo->username);
				$stmt->bindParam("pw",$loginInfo->pw);
				$stmt->execute();
				$userinfo = $stmt->fetch(PDO::FETCH_ASSOC);
				$mysqli = null;
				$response = array('username' => $userinfo['username'], 'firstame' => $userinfo['firstname'], 'lastname' => $userinfo['lastname'], 'email' => $userinfo['email']);
				echo json_encode($response);
                echo true; 
            }
			else
				echo '{"error":{"text": "Login Info was not set" }}'; 		
		  } 
		catch(PDOException $e) 
		{
			echo '{"error":{"text":' . "\"" . $e->getMessage() . "\"" . '}}'; 
		}
}


function getRecentOrder() { //get the most recent order from that user but also get the price 
    
    $mysqli = getConnection();     
    
        $rows = array();

    $q1 = "SELECT MAX(orderID) as orderID from foodOrders WHERE username= '".$user."'";
    $orderID = mysql_query($q1);

   while ($r = mysql_fetch_assoc($orderID)) //find the max orderID and increment it
    { 
        $orderID = $r["orderID"];
    }
    $query = "select name from foodOrders where inCart = 0 and orderID ='".$orderID."'";

    $result = mysql_query($query);
    
   while($r = mysql_fetch_assoc($result)) 
   {
    $rows[] = $r;
   }
    
    $q1 = "select sum(price) from foodOrders natural join food where inCart = 0 and orderID ='".$orderID."'";
    $tp = mysql_query($q1);
    
    while ($r = mysql_fetch_assoc($tp)) 
    {
        $rows[] = $r;   
    }
    
    echo json_encode($rows);
    mysql_close($mysqli);
    
}



function getCart() { //get items in the cart that is not checked out
      
    $mysqli = getConnection(); 
    $rows = array();
    //get the order that has not yet been checked out 
    $query = "select name from foodOrders where inCart and username ='".$user."'";

    $result = mysql_query($query);
    //echo $result;
    
   while($r = mysql_fetch_assoc($result)) 
   {
    $rows[] = $r;
   }
    
    $q1 = "select sum(price) from foodOrders natural join food where inCart = '1' and username ='".$user."'";
    $tp = mysql_query($q1);
    
    while ($r = mysql_fetch_assoc($tp)) 
    {
        $rows[] = $r;   
    }

    echo json_encode($rows);
    mysql_close($mysqli);
 } //getCart end 


function getPaymentInfo() { //return the different types of cards 
    $mysqli = getConnection();     $rows = array();
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

