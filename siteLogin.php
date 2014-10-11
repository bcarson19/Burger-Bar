<html><body>
	<?php

    $con = mysql_connect('localhost', 'root', 'Svalrak1');
    session_start(); //try to connect 
    if(!$con)
    {
        die('could not connect:'.mysql_error());
    }
    mysql_select_db("phptest", $con) //choose the right database
        or die ("unable to connect".mysql_error());

    $query = "select * from users where id = '";
    $query = $query.$_POST['id']."' and pw = '".$_POST['pw']."'";
    $result = mysql_query($query);
  
    if (mysql_num_rows($result) == 0)
    {
        $_SESSION['id'] = false;
        header ('Location: http://54.69.70.135/error.html'); //set Location
        exit;
    }
    else
    {
        $_SESSION['id'] = true;
        header ('Location: http://54.69.70.135/success.php'); //set Location
        exit;
    }
    mysql_close($con);
	?>
</body></html>