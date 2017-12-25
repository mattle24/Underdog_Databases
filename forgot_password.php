<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css?family=Rubik" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
	<link rel='stylesheet' type='text/css' href="styles/all.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
	<?php 
	if (isset($_SESSION['logged_user'])){
        // Logged in users don't need to recover password
		header("Location: choose_campaign.php");
        exit();
	}
	else {
        include 'includes/navbar.php';
    }
	?>

	<div id = "page-header1">
		<div class = 'spacer'></div>
		<div id = 'my-form'>
            <h2>Forgot password</h2>
			<?php
		   // if isset submit do things
		   // then make sure fields are set
			if (isset($_POST['first']) && isset($_POST['last']) && isset($_POST['email'])) {
				$first = filter_input(INPUT_POST, 'first', FILTER_SANITIZE_STRING);
				$last = filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING);
				$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
                // Check if first, last, and email matches records
                require_once("configs/config.php");
                $db = new mysqli(
                    DB_HOST,
                    DB_USER,
                    DB_PASSWORD,
                    DB_NAME) or die("Failed to connect");
                $query = "SELECT * FROM users
                WHERE first = ?
                AND last = ?
                AND email = ?;";
                $stmt = $db->prepare($query);
                $stmt->bind_param('sss', $first, $last, $email);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows !== 1) {
                    echo "Error. There were $stmt->num_rows accounts matching this information.<br>";
                    echo "<a href = 'forgot_password.php'><button>Reset</button</a>";
                } else {
                    // Step 1: generate random string of numbers and characters to add to url
                    $length = 24;
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomString = ''; // init
                    for ($i = 0; $i < $length; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }
                    
                    // Step 1.5: delete all previous reset links for this user
                    $query = "DELETE FROM reset_password
                    WHERE email = ?;";
                    $stmt= $db->prepare($query);
                    $stmt->bind_param('s', $email);
                    $stmt->execute();
                    
                    // Step 2: store the link with a date so it can expire
                    $time = time();
                    $query = "INSERT INTO reset_password (email, url_ext, time)
                    VALUES(?, ?, ?);";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param('ssi', $email, $randomString, $time);
                    $stmt->execute();
                    
                    // Step 3: send the user an email with the link to reset the password
                    $msg = "$first, Someone asked us to reset your password. If this was you, click here: localhost:8890/reset_password.php?code=$randomString.\nIf this wasn't you, please contact us.";
                    echo $msg;
                    mail($email, "Grassroots Analytics", $receipt);
                    echo "Your request has been submitted.";
                    
                    // Step 4: validate GET data, validate the correct user is using the link (email), and reset password
                    // on reset_password.php
                }
			}
			else {
				echo 
				"<form action = 'forgot_password.php' method = 'post'>
					<label>First Name</label>
						<input type = 'text' name = 'first' required> <br> <br>
					<label>Last Name</label>
						<input type = 'text' name = 'last' required> <br> <br>
					<label>Email</label>
						<input type = 'text' name = 'email' pattern = '([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){2,3}' required> <br><br>
					<button type = 'submit'>Reset password</button>
				</form>";
		}
		?>
	</div>
</div>
<footer>
</footer>
</body>
</html>