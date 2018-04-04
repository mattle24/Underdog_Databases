<?php
// Check if the cookie has expired
if(!isset($_COOKIE['logged_user'])) {
	$reason = "Your session has expired due to inactivity.";
	header("Location: ../logout.php?reason=$reason");
}
// Check if Session variable is set
if(!isset($_SESSION['logged_user'])) {
	header('Location: ../logout.php');
}
// Check if the Session variable matches the cookie
// variable
if($_SESSION['logged_user'] != $_COOKIE['logged_user']) {
	header('Location: ../logout.php');
}
?>
