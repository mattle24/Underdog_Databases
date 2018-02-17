<?php session_start();
if (!isset($_SESSION['logged_user'])) {
    header('Location: index.php');
}
setcookie('logged_user', $_SESSION['logged_user'], time() + 60 * 60);
?>
<!DOCTYPE html>
<html>
<head>
 <title>Choose Campaign</title>
 <!-- Source Sans Pro font -->
 <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
 <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
 <link rel='stylesheet' type='text/css' href="styles/all.css">
 <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
 <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
  <?php
  include 'includes/navbar_loggedin.php';
  ?>
  <div id = 'page-header1'>
    <div class = 'spacer'></div>
    <div id = 'my-form'>
      <h2>Choose Campaign</h2>
      <?php
        // check for error messages
      if (isset($_GET['msg'])) {
          echo filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING);
      }
      echo "<form action = 'landing.php' method = 'post' id ='choose_cmp'>";
        // BS Dropdown
        echo "<div class = 'form-group'>";
        include("configs/config.php");
        $db = new mysqli(
          DB_HOST,
          DB_USER,
          DB_PASSWORD,
          DB_NAME
          )or die('Failed to connect.');
        $username = $_SESSION['logged_user'];
        $query = "SELECT DISTINCT(campaigns.campaign_name), campaigns.table_name FROM campaigns, user_campaign_bridge, users
        WHERE users.email = ?
        AND users.userID = user_campaign_bridge.userID
        AND user_campaign_bridge.campaignID = campaigns.campaignID";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($campaign, $table_name);
        echo "<select class = 'form-control' name = 'choose_cmp'>";
        while ($stmt->fetch()) {
            echo "<option value = $table_name>$campaign</option>";
        }
        echo "</select>";
        echo "</div>";
        ?>
        <button type = 'submit' class = 'btn btn-primary'>Choose</button>
      </form>

    </div>
  </div>
  <footer>
  </footer>
</body>
</html>
