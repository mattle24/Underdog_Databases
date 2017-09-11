<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
	<title>New User</title>
   <link href="styles/style.css" type="text/css" rel="stylesheet">

    <!-- Source Sans Pro font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
</head>
<body>
    <div class="navigation">
      <div class = 'logo'>
      <!-- logo here -->
      </div>

      <div class = 'navItems'>
        <a href="..">Home</a> 
      </div>
    </div>

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