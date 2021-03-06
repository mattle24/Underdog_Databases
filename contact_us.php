<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Contact Us</title>
    <?php include 'includes/head.php'; ?>

</head>
<body>
	<?php
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>

	<div id = "page-header0"> <!-- Background header -->
		<div class = 'spacer'></div> <!-- Spacer -->
		<div id = 'white-container-medium'> <!-- Content box -->
            <div class = 'row'> <!-- Page title -->
                <h2>Contact Us</h2>
                <p>Have questions or comments? Submit them here and we will get back to you as soon as possible. If you are interested in joining the Underdog team, <a href = 'join_team.php'>click here.</a></p>
			</div> <!-- End page header -->
			<?php
		   // Check if form was filled out
			if (isset($_POST['first']) && isset($_POST['last']) && isset($_POST['email']) && isset($_POST['comments'])) {
				$first = filter_input(INPUT_POST, 'first', FILTER_SANITIZE_STRING);
				$last = filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING);
				$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
				$comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);
				$msg = "$first $last with email: $email asked:\n $comments";
	        if (isset($_POST['beta_tester'])) {
	          $msg = wordwrap($msg."\n THIS PERSON WANTS TO BE A BETA TESTER.", 70);
	        }
				mail("mhlehman24@gmail.com", "New Underdog Databases comment from $email", $msg);
				echo "Your comment has been submitted. We will be in touch as soon as possible!";
			}
			else {
				echo
				"
        <form action = 'contact_us.php' method = 'post'>
			<div class = 'row'> <!-- Row for name -->
	            <!-- First Name -->
	            <div class = 'form-group col-sm-6'>
	                <label for = 'firstName'>First Name</label>
	                <input id = 'firstName' class = 'form-control' type = 'text' name = 'first' placeholder = 'First Name' required>
	            </div> <!-- End first name -->

	            <!-- Last Name -->
	            <div class = 'form-group col-sm-6'>
	                <label for = 'lastName'>Last Name</label>
	                <input id = 'lastName' class = 'form-control' type = 'text' name = 'last' placeholder = 'Last Name' required>
	            </div> <!-- End last Name -->
			</div> <!-- End name -->

            <div class = 'row'> <!-- Row for email -->
	            <div class = 'form-group col-sm-6'>
	                <label for = 'Email'>Email Address</label>
	                <input id = 'Email' class = 'form-control' type = 'email' name = 'email' pattern = '([a-z]|\d|_|.)+(@)([a-z])+(\.)([a-z]){2,3}' placeholder = 'Email' required>
	            </div> <!-- End email -->
			</div>

            <div class = 'row'> <!-- Row for comments -->
	            <div class = 'form-group col-xs-12'>
	                <label for = 'intent'>Questions/ comments</label>
	                <textarea rows = '5' id = 'intent' class = 'form-control' name = 'comments' required> </textarea>
				</div>
			</div> <!-- End comments -->

            <div class = 'row'> <!-- Row for beta interest -->
	            <div class = 'form-group col-xs-12'>
	                <label class = 'form-check'>I want to be a beta tester</label>
	                <input type = 'checkbox' name = 'beta_tester' value = 'True' class = 'form-check' />
				</div>
			</div <!-- End of beta interest -->

            <div class = 'row'>
				<button type = 'submit' class = 'btn btn-primary' value = 'Submit'>Send</button>
			</div>
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
