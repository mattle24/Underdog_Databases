<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
   <title>Login</title>

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
          }
          else {
            echo '<br><p>You did not login successfully.</p>';
            echo '<p>Please <a href="login.php">login</a></p>';
          }
        }
        ?>


</body>
</html>