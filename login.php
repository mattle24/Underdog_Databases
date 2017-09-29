<?php session_start();?>

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
  <div class = 'spacer'></div>
  <div id = 'my-form'>
        <?php 
        $post_username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
        $post_password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );
        if (empty($post_username) || empty($post_password)) { 
          ?>
          <form action = 'login.php' method = 'post' id = 'login'>
              <label>Email</label>
              <input type = 'text' name = 'username'required> <br><br>
              <label>Password</label>
              <input type = 'password' name = 'password'> <br> <br>
              <button type = 'submit' value = 'Submit' formid = 'login'>Login</button>
          </form>
          <button>Forgot Password?</button>
          <!-- TODO: add a forgot password action series -->
          <a href = 'new_user.php'><button >New Account</button></a>
          <?php
      } else {
        include("configs/config.php");
        $db = new mysqli(
          DB_HOST, 
          DB_USER, 
          DB_PASSWORD, 
          DB_NAME
          )or die('Failed to connect.'); 
        $query = "SELECT email, hashpassword, first FROM users WHERE email = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $post_username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($email, $hashpassword, $first);
        $stmt->fetch();
        if ($stmt->num_rows == 1 && password_verify($post_password, $hashpassword)) {
          echo "<p>You have logged in $first!</p>";
          $_SESSION['logged_user'] = $post_username;
          header('Location: choose_campaign.php');
      }
      else {
          echo '<br><p>You did not login successfully.</p>';
          echo '<p>Please <a href="login.php">login</a></p>';
      }
  }
  ?>
</div>
</div>
<footer>
</footer>
</body>
</html>