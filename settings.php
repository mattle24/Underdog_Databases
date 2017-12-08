<?php session_start();
include 'includes/check_logged_in.php';
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
    <?php
    include 'includes/navbar_loggedin.php';
    ?>
    <div id = 'page-header1'>
     <div class = 'spacer'></div>
     <?php echo $err_msg ?>
     <div id = 'my-form'>
       <h3>Change Email</h3>
       <form action = 'settings.php' method = 'post'>
        <label>New Email</label>
        <input type = 'text' name = 'new_email' pattern = "([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){2,3}" required> <br><br>
        <label>Confirm Email</label>
        <input type = 'text' name = 'confirm_email' pattern = "([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){2,3}" required> <br><br>
        <label>Current Password</label>
        <input type = 'password' name = 'current_password' required> <br><br>
        <input type='submit'>
    </form>
    <h3>Change Password</h3>
    <form action = 'setting.php' method = 'post'>
      <label>Current Password</label>
      <input type = 'password' name = 'current_password' required> <br><br>
      <p>Your password must contain at least 8 characters, including one number, one capital letter, and one lowercase letter</p>
      <label>New Password</label>
      <input type = 'password' name = 'new_password' pattern = "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required> <br> <br>
      <label>Confirm Password</label>
      <input type = 'password' name = 'confirm_password' required> <br> <br>
      <input type = 'submit'>
  </form>
  <br> <br>
    <?php
  // Get userid, because it is quick and we will need it for both changing email
  // or password
    include('configs/config.php');
    $db = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASSWORD,
        DB_NAME) or die('Failed to connect.');
    $query = "SELECT userid FROM users WHERE userid = ?;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $_SESSION['logged_user']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_param($user_id);
    $stmt->fetch();
    $user_id = (int)$user_id;

  // Validate the current password
    if (sizeof($_POST) == 0){exit();}
    if(!isset($_POST['current_password'])) {
        echo "Please enter your current password.";
        exit();
    }
    else
    $post_password = filter_input(INPUT_POST, 'current_password', FILTER_SANITIZE_STRING);
    $query = "SELECT hashpassword FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $_SESSION['logged_user']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashpassword);
    $stmt->fetch();
    if ( !($stmt->num_rows === 1 && password_verify($post_password, $hashpassword)) ) { 
        echo "Password incorrect. Please reenter your password.";
        exit();
    }
  // Password verified

    if(isset($_POST['new_email'])) {
        if(!isset($_POST['confirm_email'])) {
            echo "Please confirm your new email.";
        }
    // We now know both new email and confirm email are set
    // Make sure the two match
        if ($_POST['new_email'] !== $_POST['confirm_email']) {
            echo "Error. The email addresses did not match.";
        }
        $new_email = filter_input(INPUT_POST, 'new_email', FILTER_SANITIZE_STRING);
    // Make sure this is not already an account with the new email
        $query = "SELECT email FROM users WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $new_email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows != 0) {
            echo "There is already an account for this email";
            stop();
        }
    // Change the email in the record for this user_id
        $query = "UPDATE users
        SET email = ?
        WHERE userid = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('si', $new_email, $user_id);
        $stmt->execute();
        if ($stmt) {
            echo "The email now associated with this account is $new_email";
            $_SESSION['logged_user'] = $new_email;
            setcookie('logged_user', $new_email, time() + 60 * 60);
        }
        else {
            echo "Error. Please try again or contact the administrator.";
        }
    } 
    elseif (isset($_POST['new_password'])) {
        if(!isset($_POST['confirm_password'])) {
            echo "Please confirm your new password.";
            exit();
        }
    // We now know both new password and cofirm password are set
    // Make sure the entries match
        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            echo "Error. The passwords did not match.";
            exit();
        }
    // Passwords match. Change the password. 
    // Hash password
        $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
        $passwd = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users
        SET hashpassword = ?
        WHERE userid = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('si', $passwd, $user_id);
        $stmt->execute();
        if ($stmt) {
            echo "The password now associated with this account has been changed.";
        }
        else {
            echo "Error. Please try again or contact the administrator.";
        }            
    }
    ?>
    </div>
</div>
<footer>
</footer>
</body>
</html>