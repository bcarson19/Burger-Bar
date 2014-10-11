<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(); //using the slim API
$user = "";

$app->post('/createAccount', 'createAccount'); //N remeber to set the user value, udate table with new user info
$app->post('/addPaymentInfo', 'addPaymentInfo'); //N add payment info to the table 

function createAccount() {  // Note: Need to get individual values using code for order
	$mysqli = getConnection();
	$app = \Slim\Slim::getInstance();
	$request = $app->request()->getBody();
	$order = json_decode($request, true);
	$query = "INSERT INTO DBBurger.users
	VALUES (),()";
	writeToLog($query);
	$mysqli->query($query);

	$return['result']="successfully created";

	echo json_encode($return);

	$mysqli->close();

}

function addPaymentInfo() {
	$mysqli = getConnection();
	$return['result']="successfully created";
	echo json_encode($return);
	$mysqli->close();
}

?>