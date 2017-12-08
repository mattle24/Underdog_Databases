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
	<!-- Source Sans Pro font -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,500" rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
	<link rel='stylesheet' type='text/css' href="styles/all.css">
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
					Underdog is a data management and analytics platform built for small campaigns. We believe that data holds an underlying truth and every campaign, from village council to president, can make more effective decisions by understanding the data you collect. Our goal is to create a platform so that any progressive campaign, no matter the size, can utilize data.
				</p>
			</article>

			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>The Importance of Data</h3>
				<p>
					Time spent on voters that do not support your candidate is time you cannot spend talking to your potential supporters. Underdog also make it easier to keep track of your supporters to make GOTV easier. Underdog also offers tools to cut turf and create calls lists that yield optimal impact. By using a data-driven approach you can maximize your efforts and win crucial votes.
				</p>
			</article>

			<article class='home-text-block col-md-4'>
				<h3 class='block-header page-header'>How to Sign Up</h3>
				<p>
					We created Underdog as a platform that any progressive campaign or group could use. The platform is free to use, although donations keep it running. To begin using the Underdog platform, contact us by clicking the new campaign button above. We'll be in touch shortly about how we can help you bring a data-driven approach to your campaign.
				</p>
			</article>
		</div>
	</div> <!-- end container -->

	<footer>
	</footer>

</body>
</html>