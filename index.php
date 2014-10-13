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
$app->get('/updateOrder', 'updateOrder'); //delete item no longer in cart

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
    $query = "SELECT MAX(orderID) from foodOrders WHERE username= '".$user."'";
    $orderID = mysql_query($query);
    $orderID = $orderID + 1; //find the max orderId and increment it 
        

   foreach($order as $item) 
    {
        foreach ($item as $key => $val)
        {
            if ($val != 0) //insert only if that item has been selected 
            {
                $query = "INSERT INTO foodOrder (username, orderID, name, inCart) VALUES ($user, $orderID, $key, $bool)";
                
            }
        }
   }
    
    //what do you want returned
    
    $mysqli->close(); //close instance of mysql 
}

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
				$stmt = $mysqli->prepare($sql);
				$stmt->bindParam("username", $loginInfo->username);
				$stmt->bindParam("pw",$loginInfo->pw);
				$stmt->execute();
				$userinfo = $stmt->fetch(PDO::FETCH_ASSOC);
				$mysqli = null;
				$response = array('username' => $userinfo['username'], 'firstame' => $userinfo['firstname'], 'lastname' => $userinfo['lastname'], 'email' => $userinfo['email']);
				echo json_encode($response);		
            }
			else
				echo '{"error":{"text": "Login Info was not set" }}'; 		
		  } 
		catch(PDOException $e) 
		{
			echo '{"error":{"text":' . "\"" . $e->getMessage() . "\"" . '}}'; 
		}
    

    $user = $loginInfo->username; //set the username 
    
}

//
//
//
//function getRecentOrder() {
//
//  $mysqli = getConnection();
//
//  // Get the instance, necessary for Slim
//  $app = \Slim\Slim::getInstance();
//
//  // Execute query
//  $result = $mysqli->query("SELECT * FROM Locations ORDER BY location_name");
//  $location_array = array();
//
//  // Return json
//  while($row = mysqli_fetch_assoc($result)){
//    //array_push($location_array, $row);
//    $location_array[$row['location_name']] = $row;
//  }
//      
//  echo json_encode($location_array);
//
//  // Close mysqli connection
//  $mysqli->close();
//}
//
//function getNewOrders() {
//  $mysqli = getConnection();
//
//  $query = "SELECT `order_id`, `time`, (SELECT `location_name` FROM Locations WHERE `location_id` = `pickup_location_id`) AS `location`, (SELECT `location_name` AS `Destination` FROM Locations WHERE `location_id` = `dropoff_location_id`) AS `destination`, `pickedUp`, `droppedOff`, `party_size`, `notes` FROM `Order` WHERE `pickedUp`=0 ";
//
//  $result = $mysqli->query($query);
//  
//  $array = array();
//
//  while ($row = $result->fetch_assoc()) {
//    $array[] = $row;
//  }
//
//  echo json_encode($array);
//
//  $mysqli->close();
//}
//
//function getActiveRides() {
//  $mysqli = getConnection();
//
//  $query = "SELECT `order_id`, `time`, (SELECT `location_name` FROM Locations WHERE `location_id` = `pickup_location_id`) AS `location`, (SELECT `location_name` AS `Destination` FROM Locations WHERE `location_id` = `dropoff_location_id`) AS `destination`, `pickedUp`, `droppedOff`, `party_size`, `notes` FROM `Order` WHERE `pickedUp`=1 AND `droppedOff`=0";
//
//  $result = $mysqli->query($query);
//
//  $array = array();
//  
//  while ($row = $result->fetch_assoc()) {
//    $array[] = $row;
//  }
//
//  echo json_encode($array);
//
//  $mysqli->close();
//}
//
//function addOrder() {
//  $mysqli = getConnection();
//  $app = \Slim\Slim::getInstance();
//  $request = $app->request()->getBody();
//  $order = json_decode($request, true);
//  $query = "INSERT INTO iGiddyUp.Order (pickup_location_id, dropoff_location_id, party_size) 
//            VALUES ((SELECT location_id FROM Locations WHERE location_name = "."\"".$order['pickup_location'] ."\")," . "(SELECT location_id FROM Locations WHERE location_name = "."\"".$order['dropoff_location'] ."\")," . $order['party_size'] . ")";
//  writeToLog($query);
//  $mysqli->query($query);
//
//  $return['result']="success";
//
//  echo json_encode($return);
//
//  $mysqli->close();
//}
//
//function pickUp($id) {
//  $mysqli = getConnection();
//  $app = \Slim\Slim::getInstance();
//  $request = $app->request()->getBody();
//  $rideInfo = json_decode($request, true);
//
//  $query = "UPDATE iGiddyUp.Order SET pickedUp=1 WHERE order_id=$id";
//
//  $mysqli->query($query);
//
//  $alert = 'Your ride has arrived';
//  $body['aps'] = array(
//    'alert' => $alert, 
//    'sound' => 'default'
//  );
//
//  $payload = json_encode($body);
//  $query = "INSERT INTO PushQueue (device_token, payload, time_queued) VALUES ('" . $rideInfo['deviceToken'] . "', '" . $payload . "', CURRENT_TIMESTAMP())";
//  $mysqli->query($query);
//
//  $mysqli->close();
//
//  echo json_encode($query);
//}
//
//function dropOff($id) {
//  $mysqli = getConnection();
//  $app = \Slim\Slim::getInstance();
//  $request = $app->request()->getBody();
//  $rideInfo = json_decode($request, true);
//
//  $query = "UPDATE iGiddyUp.Order SET droppedOff=1 WHERE order_id=$id";
//
//  $mysqli->query($query);
//
//  $mysqli->close();
//
//  echo json_encode($query);
//}
//
//function pushTest() {
//  $mysqli = getConnection();
//  $app = \Slim\Slim::getInstance();
//  $request = $app->request()->getBody();
//  $notificationInfo = json_decode($request, true);
//
//  $deviceToken = $notificationInfo['deviceToken'];
//
//  $alert = 'Your ride has arrived';
//  $body['aps'] = array(
//    'alert' => $alert, 
//    'sound' => 'default'
//  );
//
//  $payload = json_encode($body);
//  $query = "INSERT INTO PushQueue (device_token, payload, time_queued) VALUES ('" . $deviceToken . "', '" . $payload . "', CURRENT_TIMESTAMP())";
//  $mysqli->query($query);
//
//  $mysqli->close();
//
//  echo json_encode($query);
//}
//
//function getCategories() {
//  $mysqli = getConnection();
//  $app = \Slim\Slim::getInstance();
//
//  $query = "SELECT * FROM Feedback_Categories";
//
//  $result = $mysqli->query($query) or trigger_error($mysqli->error);
//  writeToLog($query);
//
//  $index = 0;
//
//  while ($row = $result->fetch_assoc()) {
//    $categories[$index] = $row['category'];
//    $index++;
//  }
//
//  $finalResults['categories'] = $categories;
//
//  $finalResults['result']='success';
//  echo json_encode($finalResults, JSON_FORCE_OBJECT);
//
//  $mysqli->close();
//}
//
//function addFeedback() {
//  $mysqli = getConnection();
//  $app = \Slim\Slim::getInstance();
//  $request = $app->request()->getBody();
//  $feedbackInfo = json_decode($request, true);
//  
//  // Add the incoming like to the database
//  $query = "INSERT INTO Feedback (user_id, title, feedback_category_id, messageText)
//            VALUES ('" . $mysqli->escape_string($feedbackInfo['user_id'])     . "', '" .
//                         $mysqli->escape_string($feedbackInfo['title'])       . "',
//                         (SELECT feedback_category_id FROM Feedback_Categories WHERE category='" . $mysqli->escape_string($feedbackInfo['category']) . "'), '" .
//                         $mysqli->escape_string($feedbackInfo['messageText']) . "')";
//  writeToLog($query);
//  $mysqli->query($query) or trigger_error($mysqli->error);
//
//  $finalResults['result']='success';
//  echo json_encode($finalResults, JSON_FORCE_OBJECT);
//
//  $mysqli->close();
//}



?>