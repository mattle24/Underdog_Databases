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
            <form action='join_the_team.php' method='post'>
                <!-- Email input -->
                <div class = 'form-group'>
                    <?php echo "<p>$err_msg</p>"; ?>
                    <label for ='loginEmail'>Email</label>
                    <input type = 'email' class = 'form-control' id = 'loginEmail' placeholder = 'Enter email' name = 'username' required>
                </div>
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
                </div>
                
                <!-- How do you want to contibute -->
                <div class = 'form-group'>
                    <label>How do you want to contribute?</label>
                    <textarea rows = '5' class = 'form-control' name = 'comments' form = 'new_campaign' required> </textarea>
                           
                </div>
                <button type = 'submit' class = 'btn btn-primary' name='submit' value = 'Submit' formid = 'login'>Login</button>
            </form>
        </div>
        <div class = 'spacer'></div>
    </div>
    <footer>
    </footer>
</body>
</html>
