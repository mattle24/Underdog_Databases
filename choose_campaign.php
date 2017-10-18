<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
 <title>Choose Campaign</title>
 <!-- Source Sans Pro font -->
 <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
 <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
 <link rel='stylesheet' type='text/css' href="styles/all.css">
 <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
 <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
  <?php
  if (!isset($_SESSION['logged_user'])){header('Location: index.php');}
  include 'includes/navbar_loggedin.php';
  ?>
  <div id = 'page-header1'>
    <div class = 'spacer'></div>
    <div id = 'my-form'>
      <label>Choose Campaign</label>
      <form action = 'landing.php' method = 'post' id ='choose_cmp'>
        <?php
        include("configs/config.php");
        $db = new mysqli(
          DB_HOST, 
          DB_USER, 
          DB_PASSWORD, 
          DB_NAME
          )or die('Failed to connect.'); 
        $username = $_SESSION['logged_user'];
        $query = "SELECT campaigns.campaign_name FROM campaigns INNER JOIN users ON userID WHERE users.email = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($campaign);
        if ($stmt->num_rows == 1) {
          $stmt->fetch();
          $_SESSION['cmp'] = $campaign;
          header('Location: landing.php');
        }
        echo "<select name = 'choose_cmp'>";
        while($stmt->fetch()){
          echo "<option value = $campaign>$campaign</option>";
        }
        echo "</select>";
        ?>
        <input type='submit'>
      </form>

    </div>
  </div>
  <footer>
  </footer>
</body>
</html>