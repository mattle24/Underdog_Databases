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
<?php
if (!isset($_SESSION['logged_user'])){header('Location: index.php');} # if not logged in, redirect to index
    if (isset($_POST['choose_cmp'])) {
      // if the post variable is set, reset
      // set campaign = to campaign table name
      include('configs/config.php');
      $db = new mysqli(
          DB_HOST, 
          DB_USER, 
          DB_PASSWORD, 
          DB_NAME
          )or die('Failed to connect.'); 
      $cmp_name = filter_input(INPUT_POST, 'choose_cmp');      
      $query = "SELECT table_name FROM campaigns
      WHERE campaign_name = ?;";
      $stmt = $db->prepare($query);
      $stmt->bind_param('s', $cmp_name);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($campaign);
      if ($stmt->num_rows == 1) {
          $stmt->fetch();
          $_SESSION['cmp'] = $campaign;
      }
      $db->close();
    }  
include 'includes/navbar_loggedin.php';
?>
  <div id = 'page-header1'>
    <div class = 'spacer'></div>
    <div id = 'landing-container'>
        <ul style = 'list-style-type:none'>
            <li><a href = 'make_list.php'>Make a List</a></li>
            <li><a href = 'search.php'>Search</a></li>
            <li><a href = 'manage_users.php'>Manage Users</a></li>
            <li><a href = 'import_list.php'>Import</a></li>
            <li><a href = 'help'>Help</a></li>
            <li><a href = 'contact'></a></li>
        </ul>
    </div>
</div>
<footer>
</footer>
</body>
</html>