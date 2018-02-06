<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password</title>
    <?php include 'includes/head.php'; ?>
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
		<div id = 'white-container-small'>
            <div class = 'row'>
                <h2>Forgot password</h2>
			</div>
            <?php
		   // if isset submit do things
		   // then make sure fields are set
			if (isset($_POST['first']) && isset($_POST['last']) && isset($_POST['email'])) {
                // TODO: should we store everything in lowercase?
                // Problem: right now this check is case-sensitive
				$first = trim(filter_input(INPUT_POST, 'first', FILTER_SANITIZE_STRING));
				$last = trim(filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING));
				$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING));
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
                // If not set, then give the form
				echo
				"<form action = 'forgot_password.php' method = 'post'>
                    <!-- First Name -->
                    <div class = 'form-group'>
                        <label for = 'formFirst'>First Name</label>
                        <input id = 'formFirst' class = 'form-control' type = 'text' name = 'first' placeholder = 'First' required>
                    </div>
                    <!-- Last Name -->
                    <div class = 'form-group'>
					   <label for = 'formLast'>Last Name</label>
				       <input id = 'formLast' class = 'form-control' type = 'text' name = 'last' placeholder = 'Last' required>
					</div>
                    <!-- Email -->
                    <div class = 'form-group'>
                        <label for = 'formEmail'>Email Address</label>
						<input id = 'formEmail' class = 'form-control' type = 'email' name = 'email' placeholder = 'Email' required> 
                    </div>
					<button class = 'btn btn-primary' type = 'submit'>Reset password</button>
				</form>";
		}
		?>
	</div>
</div>
<footer>
</footer>
</body>
</html>
