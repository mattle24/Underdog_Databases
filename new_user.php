<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
    <title>New User</title>
    <?php include 'includes/head.php'; ?>
</head>
<body>
 <?php
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
?>
 <div id = "page-header0">
     <div class = 'spacer'></div>
     <div id = 'white-container-small'>
         <div class = 'row'>
             <h2>New User</h2>
         </div>
      <form action = 'new_user.php' method = 'post'>
        <div class = 'form-group'>
            <label for = 'formFirst'>First Name</label>
            <input id = 'formFirst' class = 'form-control' type = 'text' name = 'first' placeholder = 'First' required>
        </div>
        <div class = 'form-group'>
            <label for = 'formLast'>Last</label>
            <input id = 'formLast' class = 'form-control' type = 'text' name = 'last' placeholder = 'Last' required>
        </div>
        <div class = 'form-group'>
            <label for = 'newEmail'>Email Address</label>
            <input id = 'newEmail' class = 'form-control' type = 'email' name = 'email' pattern = '([a-z]|\d|_|.)+(@)([a-z])+(\.)([a-z]){2,3}' placeholder='Email' required>
        </div>
        <div class = 'form-group'>
            <label for = 'newPwd'>Password</label>
            <input id = 'newPwd' class = 'form-control' type = 'password' name = 'new_password' pattern = '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}' aria-describedby="pwdHelp" placeholder = 'Secure password' required>
            <small id = 'pwdHelp' class = 'form-text text-muted'>Your password must contain at least 8 characters, including one number, one capital letter, and one lowercase letter.</small>
        </div>
        <div class = 'form-group'>
            <label for = 'cnfPwd'>Confirm Password</label>
            <input id = 'cnfPwd' class = 'form-control' type = 'password' name = 'confirm_password' placeholder = 'Confirm Password' required>
        </div>

        <div class = 'form-group join-team'>
            <label for = 'isnot13'>I certify I am least 13 years old.</label>
            <input id = 'isnot13' name = 'isnot13' class = 'form-check' type =  'checkbox' value = 'True' required />

            <label for = 'terms'>I have read and agree to the <a href = 'terms.php'>terms and conditions.</a></label>
            <input for = 'terms' name = 'terms' class = 'form-check' type = 'checkbox' value = 'True' required />
        </div>

        <button class = 'btn btn-primary' type = 'submit' value = 'Submit' formid = 'newusr'>Submit</button>
    </form>
    <?php
    if (!isset($_POST['email']) and !isset($_POST['new_password']) and !isset($_POST['confirm_password']) and !isset($_POST['first']) and !isset($_POST['last'])) {
        // If none of the fields are set, do nothing
        echo "";
    }
    else if (!(isset($_POST['email']) and isset($_POST['new_password']) and isset($_POST['confirm_password']) and isset($_POST['first']) and isset($_POST['last']) and isset($_POST['isnot13']) and isset($_POST['terms']))) {
        // If some but not all fields are set, send an error message
        echo "Make sure to fill out all fields!";
    }
    else {
        // If all fields are set, validate and then create a new user
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $passwd = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
        $confm_passwd = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
        $first = filter_input(INPUT_POST, 'first', FILTER_SANITIZE_STRING);
        $last = filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING);
        if ($passwd != $confm_passwd) {
            echo "Passwords do not match";
        }
        else {
            $passwd = password_hash($passwd, PASSWORD_DEFAULT);
            include('configs/config.php');
            $db = new mysqli(
              DB_HOST,
              DB_USER,
              DB_PASSWORD,
              DB_NAME
              )or die('Failed to connect.');
            // check to make sure this email doesn't already have an account
            $query = "SELECT email FROM users WHERE email = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows != 0) {
                echo "There is already an account for this email";
            }
            else {
                // Create a new user in the SQL database: initialize with no privledges
                // $query = "CREATE USER ?@'localhost' IDENTIFIED BY ?;";
                // $stmt = $db->prepare($query);
                // $stmt->bind_param('ss', $email, $passwd);
                // $stmt->execute();

                // Create a record for the user for contact purposes etc.
                $query = "INSERT INTO users (email, hashpassword, first, last)
                VALUES(?, ?, ?, ?);";
                $stmt = $db->prepare($query);
                $stmt->bind_param('ssss', $email, $passwd, $first, $last);
                $stmt->execute();
                header('Location: login.php');
            }
        }
    }
    ?>
     </div>
     <div class = 'spacer'></div>
    </div>
<footer>
</footer>
</body>
</html>
