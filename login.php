<?php
session_start();
$err_msg = "";
if ( isset($_SESSION['logged_user']) ) {
    // User is already logged in, redirect
    header("Location: choose_campaign.php");
}
if ( isset($_POST['submit']) ) {
    // submit button pressed, evaluate the form
    if ( isset($_POST['username']) && isset($_POST['password']) ) {
        // Both fields were filled in, evaluate them
        $post_username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
        $post_password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );

        require_once("configs/config.php");
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Failed to conn.');
        $query = "SELECT email, hashpassword, first FROM users WHERE email = ?;";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $post_username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($email, $hashpassword, $first);
        $stmt->fetch();

        if ( $stmt->num_rows === 1 && password_verify($post_password, $hashpassword) ) {
            // Successful login, set logged_user in $_SESSION and redirect
            $_SESSION['logged_user'] = $post_username;
            header('Location: choose_campaign.php');
        } else {
            // Invalid username or password
            $err_msg = "Invalid username or password.";
        }
    } else {
        // Required fields were not filled out properly
        $err_msg = "Please fill out all required fields below.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<?php include "includes/head.php"; ?>

	<!-- Google CAPTCHA -->
    <!-- END Google Captcha -->

</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <div id = 'page-header0' class = 'container-fluid'>
        <div class='spacer'></div>
        <div id='white-container-small' class = 'container'>
            <div class = 'row'>
                <h2>Login</h2>
                <p>This site uses cookies to enhance security.</p>
                <p>Don't have an account yet? <a href='new_user.php'>Sign up!</a></p>
            </div>

            <div class = 'row'>
                <form id='login-form' action='login.php' method='post'>
                    <?php
                    if (isset($_GET['msg'])) {
                        $err_msg = $_GET['msg'];
                    }
                    echo "<p class = 'error'>$err_msg</p>"; ?>
                    <div class = 'row'>
                        <!-- Email input -->
                        <div class = 'form-group col-sm-6'>
                            <label for ='loginEmail'>Email</label>
                            <input type = 'email' class = 'form-control' id = 'loginEmail' placeholder = 'Enter email' name = 'username' required>
                        </div>
                        <!-- Password input -->
                        <div class = 'form-group col-sm-6'>
                            <label for = 'loginPassword'>Password</label>
                            <input type = 'password' class = 'form-control' id = 'loginPassword' placeholder = 'Password' name = 'password' required>
                        </div>
                    </div>

                    <div class = 'row'>
                        <button type = 'submit' class = 'btn btn-primary col-md-3' name='submit'>Login</button>
                        <a href = 'forgot_password.php'><button class = 'btn btn-secondary col-md-7'>Forgot Password</button></a>
                        <small class = 'col-xs-12'>By logging in you agree to the <a href='terms.php'>terms and conditions.</a></small>
                    </div>
                </form>
            </div>
        </div>

        <div class = 'spacer'> </div>
    </div>
    <footer>
    </footer>
</body>
</html>
