<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
	$error = "Username or Password is invalid";
	echo $error;
	}
	else {
	// Define $username and $password
	$username=$_POST['username'];
	$password=$_POST['password'];
	// Establishing Connection with Server by passing server_name, user_id and password as a parameter
	$connection = mysql_connect("localhost", "root", "");
	// To protect MySQL injection for Security purpose
	  $query = "SELECT * FROM user WHERE password = $password AND us, FirstName, LastName, Age, StreetNumber, StreetName, City
              FROM cornell WHERE $searchtype LIKE ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $searchterm);
    $stmt->execute();
    $stmt->store_result();

	$username = stripslashes($username);
	$password = stripslashes($password);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	// Selecting Database
	$db = mysql_select_db("mysql", $connection);
	// SQL query to fetch information of registerd users and finds user match.
	$query = mysql_query("select * from user where password='$password' AND username='$username'", $connection);
	$rows = mysql_num_rows($query);
		if ($rows == 1) {
		$_SESSION['login_user']=$username; // Initializing Session
		header("location: profile.php"); // Redirecting To Other Page
		} 
		else {
		$error = "Username or Password is invalid";
		}
		mysql_close($connection); // Closing Connection
	}
}
else {
	echo'<p>An error has occured, return to the main page.</p>';
}
?>