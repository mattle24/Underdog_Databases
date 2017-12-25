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
        <!-- Source Sans Pro font -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
        <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
        <link rel='stylesheet' type='text/css' href="styles/all.css">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
    </head>
    <body>
        <?php include 'includes/navbar.php' ?>
        <div id = 'page-header1'>
            <div class='spacer'></div>
            <div id='my-form'>
                <h2>Login</h2>
                <p>This site uses cookies to enhance security.</p>
                <form action='login.php' method='post' id='login'>
                    <?php echo "<p>$err_msg</p>"; ?>
                    <label>Email</label>
                    <input type = 'text' name = 'username'required> <br><br>
                    <label>Password</label>
                    <input type = 'password' name = 'password' required> <br> <br>
                    <button type = 'submit' name='submit' value = 'Submit' formid = 'login'>Login</button>
                </form>
                <a href = "forgot_password.php">Forgot Password?</a>
                <!-- TODO: add a forgot password action series -->
            </div>
        </div>
        <footer>
        </footer>
    </body>
</html>
