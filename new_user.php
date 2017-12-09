<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
<title>New User</title>
 <!-- Source Sans Pro font -->
 <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
 <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
 <link rel='stylesheet' type='text/css' href="styles/all.css">
<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/><script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
 <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
 <?php include 'includes/navbar.php' ?>
 <div id = "page-header1">
     <div class = 'spacer'></div>
     <div id = 'my-form'>
      <form action = 'new_user.php' method = 'post'>
        <label>Email</label>
        <input type = 'text' name = 'email' pattern = "([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){2,3}" required> <br><br>
        <p>Your password must contain at least 8 characters, including one number, one capital letter, and one lowercase letter</p>
        <label>Password</label>
        <input type = 'password' name = 'new_password' pattern = "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required> <br> <br>
        <label>Confirm Password</label>
        <input type = 'password' name = 'confirm_password' required> <br> <br>
        <label>First</label>
        <input type = 'text' name = 'first' required> <br> <br>
        <label>Last</label>
        <input type = 'text' name = 'last' required> <br> <br>
        <button type = 'submit' value = 'Submit' formid = 'newusr'>Submit</button>
    </form>
    <?php
    if (!isset($_POST['email']) || !isset($_POST['new_password']) || !isset($_POST['confirm_password']) || !isset($_POST['first']) || !isset($_POST['last'])) {
        echo "Make sure to fill out all fields!";
        exit();
    }
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $passwd = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
    $confm_passwd = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);
    $first = filter_input(INPUT_POST, 'first', FILTER_SANITIZE_STRING);
    $last = filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING);
    if ($passwd != $confm_passwd) {
        echo "Passwords do not match";
        exit();
    }    
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
        exit();
    }
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
    header('Location: index.php');
    ?>
</div>
</div>
<footer>
</footer>
</body>
</html>