<html><body>
<?php
$con=mysqli_connect("example.com","peter","abc123","my_db");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// escape variables for security
$username = mysqli_real_escape_string($con, $_POST['username']);
$pw = mysqli_real_escape_string($con, $_POST['pw']);

$sql = "SELECT * FROM users WHERE username = $userNname";
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

echo "1 record added";
	?>
</body></html>