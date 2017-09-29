<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
<title>New User</title>
 <!-- Source Sans Pro font -->
 <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
 <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
 <link rel='stylesheet' type='text/css' href="styles/all.css">
 <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
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
    $email = $_POST['email'];
    $passwd = $_POST['new_password'];
    $confm_passwd = $_POST['confirm_password'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    if (!isset($email) || !isset($passwd) || !isset($first) || !isset($last)) {
        exit();
    }
    if (isset($passwd) && $passwd != $confm_passwd) {
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