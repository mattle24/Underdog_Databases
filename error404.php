<?php
session_start();
// Check if the cookie has expired
$bool1 = isset($_COOKIE['logged_user']);
// Check if Session variable is set
$bool2 = isset($_SESSION['logged_user']);
if ($bool1 and $bool2) {
	// do further checks
	if ($_SESSION['logged_user'] != $_COOKIE['logged_user']) {
		$_SESSION = array();
		setcookie('logged_user', 'nothing', time() - 90);
        // we boolin
	}
}
else {
	$_SESSION = array();
	setcookie('logged_user', 'nothing', time() - 90);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Underdog Databases 404</title>
    <?php include "includes/head.php"; ?>
</head>
<body>

	<?php
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>

	<div id = 'home-header'>

        <div class = 'spacer'> </div>
        <div id = 'white-container-medium'>
            <h1>404 Error</h1>
            <p>
                The page you are looking for does not exist. <a href = '/'>
                Return to the home page.</a>
            </p>
        </div>
        <div class = 'spacer'> </div>
	</div>

	<footer>
	</footer>

</body>
</html>
