<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>New Campaign</title>
    <?php include 'includes/head.php'; ?>
    
</head>
<body>
	<?php 
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>

	<div id = "page-header1">
		<div class = 'spacer'></div>
		<div id = 'white-container-medium'>
            <div class = 'row'>
                <h2>New Campaign</h2>
            </div>
			<?php
		   // if isset submit do things
		   // then make sure fields are set
			if (isset($_POST['first']) && isset($_POST['last']) && isset($_POST['email']) && isset($_POST['campaign']) && isset($_POST['comments'])) {
				$first = filter_input(INPUT_POST, 'first', FILTER_SANITIZE_STRING);
				$last = filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING);
				$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
				$campaign = filter_input(INPUT_POST, 'campaign', FILTER_SANITIZE_STRING);
				$comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);
				$msg = "Campaign: $campaign\nContact: $first $last\nEmail: $email\n\n $comments";
				$receipt = "$first, thank you for your interest in Underdog Databases. We will be in touch as soon as possible.";	
				echo "Your request has been submitted. We will be in touch as soon as possible!";
				mail("mhlehman24@gmail.com", "New Underdog Databases interest from $campaign", $msg);
				mail($email, "Underdog Databases", $receipt);
			}
			else {
				echo 
				"
                <form action = 'new_campaign.php' method = 'post' id = 'new_campaign'>
                    <!-- First Name -->
                    <div class = 'form-group'>
                        <label for = 'firstName'>First Name</label>
                        <input id = 'firstName' class = 'form-control' type = 'text' name = 'first' placeholder = 'First Name' required>
                    </div>
                    <!-- Last Name -->
                    <div class = 'form-group'>
                        <label for = 'lastName'>Last Name</label>
                        <input id = 'lastName' class = 'form-control' type = 'text' name = 'last' placeholder = 'Last Name' required>
                    </div>
                    <!-- Email -->
                    <div class = 'form-group'>
                        <label for = 'Email'>Email Address</label>
                        <input id = 'Email' class = 'form-control' type = 'email' name = 'email' pattern = '([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){2,3}' placeholder = 'Email' required>
                    </div>
                    <!-- Campaign/ Organization -->
                    <div class = 'form-group'>
                        <label for = 'cmpOrg'>Campaign/ Organization</label>
                        <input id = 'cmpOrg' class = 'form-control' type = 'text' name = 'campaign' required> 
                    </div>
                    <!-- Statment of intent -->
                    <div class = 'form-group'>
                        <label for = 'intent'>How do you plan to use Underdog Databases?</label>
                        <textarea rows = '5' id = 'intent' class = 'form-control' name = 'comments' form = 'new_campaign' required> </textarea>
                    </div>
                    <button type = 'submit' class = 'btn btn-primary' value = 'Submit' formid = 'newusr'>Get Started</button>
				</form>
                ";
		}
		?>
	</div>
    <div class = 'spacer'></div> 
</div>
<footer>
</footer>
</body>
</html>