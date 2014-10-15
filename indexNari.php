<?php

echo "Hello";

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(); //using the slim API
$user = "";

$app->post('/createAccount', 'createAccount'); //N remeber to set the user value, udate table with new user info
$app->post('/addPaymentInfo', 'addPaymentInfo'); //N add payment info to the table 

$app->run();

function createAccount() {  // Note: Need to get individual values using code for order
	echo "in createAccount";
	$mysqli = getConnection();
	$app = \Slim\Slim::getInstance();
	$request = $app->request()->getBody();

	// escape variables for security
	$username = mysqli_real_escape_string($con, $_POST['username']);
	$pw = mysqli_real_escape_string($con, $_POST['pw']);

	$sql = "SELECT * FROM users WHERE username = $username";
	$result = mysql_query($sql);
	$num_row = mysql_num_rows($result);
	if($num_row > 0)
	{
		$errorMessage = "Username Already Taken";
		exit; 
	}

	$sql="INSERT INTO users VALUES 
	($username, $pw)";

	if (!mysqli_query($con,$sql)) 
	{
		die('Error: ' . mysqli_error($con));
	}

	echo "1 user added";
}

/*
function createAccount() {  // Note: Need to get individual values using code for order
$mysqli = getConnection();
$app = \Slim\Slim::getInstance();
$request = $app->request()->getBody();
$user = json_decode($request, true);
$query = "INSERT INTO DBBurger.users
VALUES ('" .$mysqli ->escape_string($user['username']) ."', '" .
$mysqli ->escape_string($user['pw']) ."', '" .
$mysqli ->escape_string($user['firstname']) ."', '" .
$mysqli ->escape_string($user['lastname']) ."', '" .
$mysqli ->escape_string($user['email']) ."')";

writeToLog($query);
$mysqli->query($query) or trigger_error($mysqli->error);

$return['result']="successfully created";

echo json_encode($return);

$mysqli->close();

}
*/

function addPaymentInfo() {
	echo "in addPaymentInfo";
	$mysqli = getConnection();
	$app = \Slim\Slim::getInstance();
	$request = $app->request()->getBody();

	// escape variables for security
	$username = mysqli_real_escape_string($con, $_POST['username']);
	$paymentId = mysqli_real_escape_string($con, $_POST['paymentId']);
	$cardNumber = mysqli_real_escape_string($con, $_POST['cardNumber']);
	$typeOfCard = mysqli_real_escape_string($con, $_POST['typeOfCard']);
	$address = mysqli_real_escape_string($con, $_POST['address']);
	$zipCode = mysqli_real_escape_string($con, $_POST['zipCode']);
	$state = mysqli_real_escape_string($con, $_POST['state']);
	$expireDate = mysqli_real_escape_string($con, $_POST['expireDate']);	

	$sql = "SELECT * FROM paymentInfo WHERE username = $username";
	$result = mysql_query($sql);
	$num_row = mysql_num_rows($result);
	// update this with more robust options later, i.e. same CC exists for user
	if($num_row > 0)
	{
		echo "Payment method already exists" 
	}

	$sql="INSERT INTO paymentInfo VALUES 
	($username, $paymentId, $cardNumber, $typeOfCard, $address, $zipCode, $state, $expireDate)";

	if (!mysqli_query($con,$sql)) 
	{
		die('Error: ' . mysqli_error($con));
	}

	echo "1 payment method added";
}

?>