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
	<meta name = 'keywords' content = "underdog databases, politics, data, progressive, target, voters, analytics, campaign">
	<meta name = 'description' content = 'Data management for small political campaigns and movements.'>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-114403761-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-114403761-1');
	</script>
</head>
<body>

	<?php
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>

	<div id = 'page-header0'>
		<div class = 'spacer'> </div>
		<div id = 'home-hero-wrapper'>
			<div id = 'home-hero-title'>
				<span>Underdog Databases</span>
			</div>
			<div id = 'home-hero-subtitle'>
				<span>Bringing Big Data to Small Campaigns</span>
			</div>
			<div id='home-hero-buttons'>
				<a href = 'new_campaign.php'><button type='button' class='btn btn-secondary cta-button'>New Campaign</button></a>
				<a href = 'new_user.php'><button type='button' class='btn btn-secondary cta-button'>New User</button></a>
				<!-- <a class='cta-button' href= 'new_campaign.php'>New Campaign</a>
				<a class='cta-button' href = 'new_user.php'>New User</a> -->
			</div>
			<div class = 'spacer'> </div>

		</div>
	</div>

	<div id='home-container' class='container'>
		<div class='row'>
			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>Why Underdog Databases</h3>
				<p>
                    Underdog Databases is a database management platform designed for local campaigns and progressive organizations. Our goal is to allow any progressive campaign or group, no matter the size or funding, to have full access to data management technology they need to succeed.
                    <!-- Not ready yet
                    <a href = 'underdog_workshop'>Take a look at what we offer and our developing programs.</a>	-->
                </p>
			</article>

			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>The Importance of Data</h3>
				<p>
					At the core of Underdog Databases, we believe data contains an important underlying truth. For your campaign or organization to make the largest possible impact, you need to maximize your organizers’ and volunteers’ efforts. Underdog helps you keep track of your supporters and determine who to call next.
				</p>
			</article>

			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>How to Sign Up</h3>
				<p>
                    Underdog Databases is currently in beta testing. If you are interested in using the platform when the full version is released, <a href='new_campaign.php'>contact us!</a> If you would like help us test the platform, you can <a href = 'contact_us.php'>sign up here.</a>
				</p>
			</article>
		</div>
	</div> <!-- end container -->

	<footer>
	</footer>

</body>
</html>
