<?php

// 
// Date: 03/31/2014

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(); //using the slim API
$user = "";

$app->get('/getOrder', 'getOrder'); //public 
$app->get('/recentOrder', 'getRecentOrder'); //public 
$app->get('/recentOrder', 'getPaymentInfo'); //public 

$app->post('/loginIn', 'validateLogin'); //remeber to set the user value 

$app->put('/dropoff/:id', 'addUser');
$app->put('/dropoff/:id', 'addOrder');
$app->put('/dropoff/:id', 'addPaymentInfo');




//$app->get('/recentOrder', 'getRecentOrder'); //public 
//$app->get('/activerides', 'getActiveRides');
//$app->get('/categories', 'getCategories');
//
//$app->post('/order', 'addOrder'); //login payment security privat 
//$app->post('/push/', 'pushTest');
//$app->post('/feedback', 'addFeedback');
//
//$app->put('/pickup/:id', 'addUser'); //add to table 
//$app->put('/dropoff/:id', 'dropoff');


$app->run();
function getRecentOrder() {

  $mysqli = getConnection();

  // Get the instance, necessary for Slim
  $app = \Slim\Slim::getInstance();

  // Execute query
  $result = $mysqli->query("SELECT * FROM Locations ORDER BY location_name");
  $location_array = array();

  // Return json
  while($row = mysqli_fetch_assoc($result)){
    //array_push($location_array, $row);
    $location_array[$row['location_name']] = $row;
  }
      
  echo json_encode($location_array);

  // Close mysqli connection
  $mysqli->close();
}

function getNewOrders() {
  $mysqli = getConnection();

  $query = "SELECT `order_id`, `time`, (SELECT `location_name` FROM Locations WHERE `location_id` = `pickup_location_id`) AS `location`, (SELECT `location_name` AS `Destination` FROM Locations WHERE `location_id` = `dropoff_location_id`) AS `destination`, `pickedUp`, `droppedOff`, `party_size`, `notes` FROM `Order` WHERE `pickedUp`=0 ";

  $result = $mysqli->query($query);
  
  $array = array();

  while ($row = $result->fetch_assoc()) {
    $array[] = $row;
  }

  echo json_encode($array);

  $mysqli->close();
}

function getActiveRides() {
  $mysqli = getConnection();

  $query = "SELECT `order_id`, `time`, (SELECT `location_name` FROM Locations WHERE `location_id` = `pickup_location_id`) AS `location`, (SELECT `location_name` AS `Destination` FROM Locations WHERE `location_id` = `dropoff_location_id`) AS `destination`, `pickedUp`, `droppedOff`, `party_size`, `notes` FROM `Order` WHERE `pickedUp`=1 AND `droppedOff`=0";

  $result = $mysqli->query($query);

  $array = array();
  
  while ($row = $result->fetch_assoc()) {
    $array[] = $row;
  }

  echo json_encode($array);

  $mysqli->close();
}

function addOrder() {
  $mysqli = getConnection();
  $app = \Slim\Slim::getInstance();
  $request = $app->request()->getBody();
  $order = json_decode($request, true);
  $query = "INSERT INTO iGiddyUp.Order (pickup_location_id, dropoff_location_id, party_size) 
            VALUES ((SELECT location_id FROM Locations WHERE location_name = "."\"".$order['pickup_location'] ."\")," . "(SELECT location_id FROM Locations WHERE location_name = "."\"".$order['dropoff_location'] ."\")," . $order['party_size'] . ")";
  writeToLog($query);
  $mysqli->query($query);

  $return['result']="success";

  echo json_encode($return);

  $mysqli->close();
}

function pickUp($id) {
  $mysqli = getConnection();
  $app = \Slim\Slim::getInstance();
  $request = $app->request()->getBody();
  $rideInfo = json_decode($request, true);

  $query = "UPDATE iGiddyUp.Order SET pickedUp=1 WHERE order_id=$id";

  $mysqli->query($query);

  $alert = 'Your ride has arrived';
  $body['aps'] = array(
    'alert' => $alert, 
    'sound' => 'default'
  );

  $payload = json_encode($body);
  $query = "INSERT INTO PushQueue (device_token, payload, time_queued) VALUES ('" . $rideInfo['deviceToken'] . "', '" . $payload . "', CURRENT_TIMESTAMP())";
  $mysqli->query($query);

  $mysqli->close();

  echo json_encode($query);
}

function dropOff($id) {
  $mysqli = getConnection();
  $app = \Slim\Slim::getInstance();
  $request = $app->request()->getBody();
  $rideInfo = json_decode($request, true);

  $query = "UPDATE iGiddyUp.Order SET droppedOff=1 WHERE order_id=$id";

  $mysqli->query($query);

  $mysqli->close();

  echo json_encode($query);
}

function pushTest() {
  $mysqli = getConnection();
  $app = \Slim\Slim::getInstance();
  $request = $app->request()->getBody();
  $notificationInfo = json_decode($request, true);

  $deviceToken = $notificationInfo['deviceToken'];

  $alert = 'Your ride has arrived';
  $body['aps'] = array(
    'alert' => $alert, 
    'sound' => 'default'
  );

  $payload = json_encode($body);
  $query = "INSERT INTO PushQueue (device_token, payload, time_queued) VALUES ('" . $deviceToken . "', '" . $payload . "', CURRENT_TIMESTAMP())";
  $mysqli->query($query);

  $mysqli->close();

  echo json_encode($query);
}

function getCategories() {
  $mysqli = getConnection();
  $app = \Slim\Slim::getInstance();

  $query = "SELECT * FROM Feedback_Categories";

  $result = $mysqli->query($query) or trigger_error($mysqli->error);
  writeToLog($query);

  $index = 0;

  while ($row = $result->fetch_assoc()) {
    $categories[$index] = $row['category'];
    $index++;
  }

  $finalResults['categories'] = $categories;

  $finalResults['result']='success';
  echo json_encode($finalResults, JSON_FORCE_OBJECT);

  $mysqli->close();
}

function addFeedback() {
  $mysqli = getConnection();
  $app = \Slim\Slim::getInstance();
  $request = $app->request()->getBody();
  $feedbackInfo = json_decode($request, true);
  
  // Add the incoming like to the database
  $query = "INSERT INTO Feedback (user_id, title, feedback_category_id, messageText)
            VALUES ('" . $mysqli->escape_string($feedbackInfo['user_id'])     . "', '" .
                         $mysqli->escape_string($feedbackInfo['title'])       . "',
                         (SELECT feedback_category_id FROM Feedback_Categories WHERE category='" . $mysqli->escape_string($feedbackInfo['category']) . "'), '" .
                         $mysqli->escape_string($feedbackInfo['messageText']) . "')";
  writeToLog($query);
  $mysqli->query($query) or trigger_error($mysqli->error);

  $finalResults['result']='success';
  echo json_encode($finalResults, JSON_FORCE_OBJECT);

  $mysqli->close();
}

function getConnection() {
	$db = new mysqli("localhost", "root", "root", "DBBurger"); //put in your password
  // Check mysqli connection
  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
  return $db;
}


?>