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
          <button>New Account</button>
        <?php
        } else {
          $db = new mysqli(
            '127.0.0.1', 
            'lehman', 
            'password', 
            'meta'
            )or die('Failed to connect.'); 
          $query = "SELECT email, hashpassword, first FROM users WHERE email = ?;";
          $stmt = $db->prepare($query);
          $stmt->bind_param('s', $post_username);
          $stmt->execute();
          $stmt->store_result();
          $stmt->bind_result($email, $hashpassword, $first);
          $stmt->fetch();
          echo password_verify($post_password, $hashpassword) == TRUE;
          # TODO: fix this. It is not matching password and has correctly
          if ($stmt->num_rows == 1 && password_verify($post_password, $hashpassword)) {
            echo "<p>You have logged in ".$first."!</p>";
            $_SESSION['logged_user'] = $post_username;
          }
          else {
            echo '<br><p>You did not login successfully.</p>';
            echo '<p>Please <a href="login.php">login</a></p>';
          }
        }
        ?>

        <!-- This will go on a separate page, but I needed to create a user -->
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
      </div>
      <?php
      $email = $_POST['email'];
      $passwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $first = $_POST['first'];
      $last = $_POST['last'];

      $db = new mysqli(
        '127.0.0.1', 
        'lehman', 
        'password', 
        'meta'
        )or die('Failed to connect.');       
      $query = "INSERT INTO users (email, hashpassword, first, last)
                VALUES(?, ?, ?, ?);";
      $stmt = $db->prepare($query);
      $stmt->bind_param('ssss', $email, $passwd, $first, $last);
      $stmt->execute();
    
      ?>
   </div>

</body>
</html>