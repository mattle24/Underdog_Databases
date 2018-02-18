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
	<title>Underdog Databases</title>
    <?php include "includes/head.php"; ?>
</head>
<body>

	<?php
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>

	<div id='home-banner' class = 'row'>
		<img id='home-hero-image' src='images/background.png' alt='Background'>
		<div id='home-hero-wrapper'>
			<div id='home-hero-title'>
				<span>Underdog Databases</span>
			</div>
			<div id='home-hero-subtitle'>
				<span>Bringing Big Data to Small Campaigns</span>
			</div>
			<div id='home-hero-buttons'>
				<a class='cta-button' href= 'new_campaign.php'>New Campaign</a>
				<a class='cta-button' href = 'new_user.php'>New User</a>
			</div>
		</div>
	</div>

	<div id='home-container' class='container'>
		<div class='row'>
			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>Why Underdog Databases</h3>
				<p>
                    Underdog Databases is a database management platform especially designed for local campaigns and progressive organizations. Underdog’s goal is to allow any progressive campaign or group, no matter the size or funding, to have full access to data they need to succeed. Underdog develops software specially designed for progressives who cannot access expensive tools.
                    <!-- Not ready yet
                    <a href = 'underdog_workshop'>Take a look at what we offer and our developing programs.</a>	-->
                </p>
			</article>

			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>The Importance of Data</h3>
				<p>
					At the core of Underdog Databases, we believe data contains an important underlying truth. For your campaign or organization to make the largest possible impact, you need to maximize your organizers’ and volunteers’ efforts. Underdog helps you keep track of your supporters and determine which voters should be contacted. Today, no campaign can afford to ignore data.
				</p>
			</article>

			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>How to Sign Up</h3>
				<p>
                    Underdog Databases is currently in beta testing. If you are interested in using the platform when the full version is released, <a href='new_campaign.php'>contact us!</a> If you would like to be a beta tester, you can <a href = 'contact_us.php'>sign up here.</a>
				</p>
			</article>
		</div>
	</div> <!-- end container -->

	<footer>
	</footer>

</body>
</html>
