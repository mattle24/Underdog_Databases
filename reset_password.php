<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Reset Password</title>
    <?php include 'includes/head.php'; ?>
    
    <!-- Google CAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
    function onSubmit(token) {
         document.getElementById("reset-form").submit();
       }
    </script>
    <!-- END Google Captcha -->
</head>
<body>
	<?php 
	if (isset($_SESSION['logged_user'])){
        // Logged in users don't need to reset password
		header("Location: choose_campaign.php");
        exit();
	}
	else {
        include 'includes/navbar.php';
    }
	?>

	<div id = "page-header1">
		<div class = 'spacer'></div>
		<div id = 'white-container-small'>
            <div class = 'row'>
                <h2>Reset Password</h2>
			</div>
            <?php
            // Step 4: validate GET data, validate the correct user is using the link (email), 
            // validate the request is not timed out and reset password
			if (isset($_GET['code'])) {
				$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);
                // Check if code matches record
                require_once("configs/config.php");
                $db = new mysqli(
                    DB_HOST,
                    DB_USER,
                    DB_PASSWORD,
                    DB_NAME) or die("Failed to connect");
                $query = "SELECT url_ext, time FROM reset_password
                WHERE url_ext = ?;";
                $stmt = $db->prepare($query);
                $stmt->bind_param('s', $code);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($url_ext, $time);
                $stmt->fetch();
                // Make sure there is a record for the code
                if ($stmt->num_rows < 1) {
                    echo "Error. This is not a valid link.";
                }
                else {
                    // Make sure the time is within the allowed time
                    // Get the difference between now and then
                    $dif = time() - $time;
                    // Difference is in seconds. Requests time out after one hour, or 60 * 60 = 3600 seconds
                    if ($dif > 3600) {
                        echo "Error. This link has timed out. Please <a href = 'forgot_password.php'>request to reset your password now.</a>";
                    }
                    else {
                        // Validate email
                        // Set SESSION variable so we can track after form is submitted
                        $_SESSION['code'] = $code;
                        $site_key = CAPTCHA_SITE_KEY;
                        echo "
                        <div class = 'row'>
                            <p><strong>Please confirm your email and reset your password.</strong></p>
                        </div>
                        <form id = 'reset-form' action = 'reset_password.php' method = 'POST'>
                            <!-- Confirm Email -->
                            <div class = 'form-group'>
                                <label for = 'formEmail'>Email Address</label>
                                <input id = 'formEmail' class = 'form-control' type = 'text' name = 'email' placeholder = 'Email' required />
                            </div>
                            <!-- New Password -->
                            <div class = 'form-group'>
                                <label for = 'newPwd'>New Password</label>
                                <input id = 'newPwd' class = 'form-control' type = 'password' name = 'new_password' pattern = '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}' placeholder = 'Secure password' aria-describedby = 'pwdHelp' required />
                                <small id = 'pwdHelp' class = 'form-text text-muted'>Your password must contain at least 8 characters, including one number, one capital letter, and one lowercase letter.</small>
                            </div>
                            <!-- Confirm Password -->
                            <div class = 'form-group'>
                                <label for ='cnfPwd'>Confirm Password</label>
                                <input id = 'cnfPwd' class = 'form-control' type = 'password' name = 'confirm_password' placeholder = 'Confirm Password' required></input>
                            </div>
                            <button class = 'btn btn-primary g-recaptcha' data-sitekey=$site_key data-callback='onSubmit' name = 'code2' value =$code>Submit</button>
                        </form>";
                    }
                }
            } elseif (isset($_POST['email']) and isset($_POST['new_password']) and isset($_POST['confirm_password'])) {
                // If form was filled out then validate responses
                // Make sure email is right for the code and passwords match
                // And then update the password
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
                $code = $_SESSION['code']; // TODO: validate using POST code, not SESSION
                require_once('configs/config.php');
                $db = new mysqli(
                    DB_HOST, 
                    DB_USER, 
                    DB_PASSWORD, 
                    DB_NAME
                    )or die('Failed to connect.');       
                $query = "SELECT * FROM reset_password
                WHERE email = ?
                AND url_ext = ?;";
                $stmt = $db->prepare($query);
                $stmt->bind_param('ss', $email, $code);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows < 1) {
                    echo "Error. There were 0 matches for your email address using this password reset link.";
                } else {
                    $passwd = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
                    $pattern = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/';
                    $matches = preg_match($pattern, $passwd);
                    $confm_passwd = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
                    if ($passwd != $confm_passwd) {
                        echo "Passwords do not match";
                    } elseif ($matches < 1) {
                        echo "Your password must contain at least 8 characters, including one number, one capital letter, and one lowercase letter.";
                    } else {
                        $passwd = password_hash($passwd, PASSWORD_DEFAULT);
                        $query = "UPDATE users
                        SET hashpassword = ?
                        WHERE email = ?;";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('ss', $passwd, $email);
                        $stmt->execute();
                        if ($stmt) {
                            echo "Your password has been changed. <a href = 'login.php'>Login.</a>";
							$_SESSION['code'] = ''; // reset
                        } else {
                            echo "Error. An unknown error has occurred. Please contact an administrator or try again.";
                        }
                    }
                }
            }
            else {
                // If code isn't set, get the user out of here
                header("Location: index.php");
            }
		?>
	</div>
</div>
<footer>
</footer>
</body>
</html>