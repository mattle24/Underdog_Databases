<?php
session_start();
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Join the Team</title>
        <?php include "includes/head.php"; ?>
    </head>
<body>
	<?php
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>
    <div id = 'page-header0'>
        <div class='spacer'></div>
        <div id='white-container-medium'>
            <div class = 'row'>
                <h2>Join the Team</h2>
                <p>Can you code? Are you passionate about progressive politics? Apply to join the Underdog Databases team!</p>
            </div>
			<?php
		   // Check if form was filled out
			if (isset($_POST['first']) && isset($_POST['last']) && isset($_POST['email']) && isset($_POST['comments'])) {
				$first = filter_input(INPUT_POST, 'first', FILTER_SANITIZE_STRING);
				$last = filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING);
				$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
				$comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);
				$msg = "$first $last with email: $email asked:\n $comments";
                if (isset($POST['skills'])) {
                    $skills = "'".implode("','", $_POST['skills'])."'";
                    $msg = $msg."\n Person has skills: $skills.";
                }
				mail("mhlehman24@gmail.com", "Underdog Databases application from $email", $msg);
				echo "Your form has been submitted. We will be in touch as soon as possible!";
			}
			else {
				echo "<form action='join_team.php' method='post'>
                    <!-- First Name -->
                    <div class = 'form-group'>
                        <label for = 'firstName'>First Name</label>
                        <input id = 'firstName' class = 'form-control' type = 'text' name = 'first' placeholder = 'First Name' required>
                    </div> <!-- End first name -->

                    <!-- Last Name -->
                    <div class = 'form-group'>
                        <label for = 'lastName'>Last Name</label>
                        <input id = 'lastName' class = 'form-control' type = 'text' name = 'last' placeholder = 'Last Name' required>
                    </div> <!-- End last Name -->

                    <!-- Email input -->
                    <div class = 'form-group'>
                        <label for ='loginEmail'>Email Address</label>
                        <input type = 'email' class = 'form-control' id = 'loginEmail' placeholder = 'Enter email' name = 'username' required>
                    </div><!-- End email -->

                    <!-- Skill Section -->
                    <label>Area(s) of Interest</label>
                    <br>
                    <div class = 'form-check join-team'>
                        <label class = 'form-check-label'>Front End Development</label>
                        <input type = 'checkbox' name = 'skills[]' value ='FrontEnd' />
                    </div>

                    <div class = 'form-check join-team'>
                        <label class = 'form-check-label'>Back End Development</label>
                        <input type = 'checkbox' name = 'skills[]' value ='BackEnd' />
                    </div>

                    <div class = 'form-check join-team'>
                        <label class = 'form-check-label'>Design</label>
                        <input type = 'checkbox' name = 'skills[]' value ='Design' />
                    </div>

                    <div class = 'form-check join-team'>
                        <label class = 'form-check-label'>Marketing</label>
                        <input type = 'checkbox' name = 'skills[]' value ='Marketing' />
                    </div>

                    <div class = 'form-check join-team'>
                        <label class = 'form-check-label'>Other</label>
                        <input type = 'checkbox' name = 'skills[]' value ='Other' />
                    </div> <!-- End of skills section -->

                    <!-- How do you want to contibute -->
                    <div class = 'form-group'>
                        <label>How do you want to contribute?</label>
                        <textarea rows = '5' class = 'form-control' name = 'comments' form = 'new_campaign' required> </textarea>
                    </div> <!-- End contribute -->

                    <button type = 'submit' class = 'btn btn-primary' name='submit' value = 'Submit'>Send</button>
                </form> <!-- End form -->";
            }
            ?>
        </div> <!-- End of content box -->
        <div class = 'spacer'></div>
    </div> <!-- End of header -->
    <footer>
    </footer>
</body>
</html>
