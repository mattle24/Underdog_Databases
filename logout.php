<?php 
session_start();
$_SESSION = array();
setcookie('logged_user', 'nothing', time() - 60);
header('Location: index.php');
?>
