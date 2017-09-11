<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
	<title>New User</title>
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
   <div class = "header" id = 'headerOnly'>
   	<div class = 'signin'>
      <form action = 'login.php' method = 'post'>
        <label>Email</label>
        <input type = 'text' name = 'email'required> <br><br>
        <label>Password</label>
        <input type = 'password' name = 'new_password'> <br> <br>
        <!-- TODO: second password field to double check entry
        and some regex to make sure certain conditions are met -->
        <label>First</label>
        <input type = 'text' name = 'first'> <br> <br>
        <label>Last</label>
        <input type = 'text' name = 'last'> <br> <br>
        <button type = 'submit' value = 'Submit' formid = 'newusr'>Submit</button>
      </form>
	    <?php
	    # regex for email: ([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){3}
	    $email = $_POST['email'];
	    $passwd = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
	    $first = $_POST['first'];
	    $last = $_POST['last'];

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
	    ?>
	  </div>
  </div>
</body>
</html>