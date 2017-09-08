<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
   <title>Login</title>

   <link href="styles/style.css" type="text/css" rel="stylesheet">

    <!-- Source Sans Pro font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

    <div class="navigation">
      <div class = 'logo'>
      <!-- logo here -->
      </div>

      <div class = 'navItems'>
        <a href="..">Home</a> 
      </div>
    </div>

</head>
<body>

   <div class = "header" id = 'headerOnly'>
      <div class = 'signin'>
      <?php 
        $username = filter_input( INPUT_POST, 'username', FILTER_SANITIZE_STRING );
        $password = filter_input( INPUT_POST, 'password', FILTER_SANITIZE_STRING );

        if (empty($username) || empty($password)) { 
        ?>
          <form action = 'login.php' method = 'post' id = 'login'>
            <label>Email</label>
            <input type = 'text' name = 'username'required> <br><br>
            <label>Password</label>
            <input type = 'password' name = 'password'> <br> <br>
            <button type = 'submit' value = 'Submit' formid = 'login'>Login</button>
          </form>
          <button>Forgot Password?</button>
          <button>New Account</button>
         <?php
        } else {
            if ($username == 'smohlke' && $password == 'mine') {
              echo "<p>Congratulations ".$username."</p>";
              $_SESSION['logged_user'] = $username;
          }
          else {
            echo '<p>You did not login successfully.</p>';
            echo '<p>Please <a href="login.html">login</a></p>';
          }
        }
        ?>
      </div>
   </div>

</body>
</html>