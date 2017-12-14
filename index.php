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
	<meta charset="utf-8">
	<title>Underdog Data</title>
    <link href="https://fonts.googleapis.com/css?family=Barlow" rel="stylesheet">
<!--    <link href="https://fonts.googleapis.com/css?family=Rubik:400,500" rel="stylesheet">-->
    <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
	<link rel='stylesheet' type='text/css' href="styles/all.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>

	<!-- TODO: change nav bar so elements are all to the left/ right with small padding -->
	<?php 
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>

	<div id='home-banner'>
		<img id='home-hero-image' src='images/background.png' alt='Blue Graph Background'>
		<div id='home-hero-wrapper'>
			<div id='home-hero-title'>
				<span>Underdog Data</span>
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
				<h3 class='block-header page-header'>Why Underdog Data</h3>
				<p>
					Underdog is a data management and analytics platform built for small campaigns. We believe data contains an underlying truth and every campaign can smarter decisions by understanding your data. Our goal is to create a platform so that any progressive campaign, no matter the size, can utilize data. Currently, we offer a data management platform. We are developing turf cutting tools and predictive modeling. 
				</p>
			</article>

			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>The Importance of Data</h3>
				<p>
					Time is the most precious resource on a campaign. While your total voter universe might be large, Underdog will help shrink it to only the most important people to target. The Underdog platform makes it easy to keep track of your supporters, optimizing the impact of your field operations. Underdog also offers tools to cut turf and create calls lists that yield optimal impact. By using a data-driven approach you can maximize your efforts and win crucial votes.
				</p>
			</article>

			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>How to Sign Up</h3>
				<p>
					We created Underdog as a platform that any progressive campaign or group could use. The platform is free to use, although donations keep it running. To begin using the Underdog platform, <a href= 'new_campaign.php'>contact us.</a> We'll be in touch shortly about how we can help you bring a data-driven approach to your campaign.
				</p>
			</article>
		</div>
	</div> <!-- end container -->

	<footer>
	</footer>

</body>
</html>