<?php session_start();
include "check_logged_in.php";
setcookie('logged_user', $_SESSION['logged_user'], time() + 60 * 60);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Choose Campaign</title>
    <?php include "includes/head.php"; ?>
</head>
<body>
  <?php
  include 'includes/navbar_loggedin.php';
  ?>
  <div id = 'page-header1' class = 'container-fluid'>
    <div class = 'spacer'></div>
    <div id = 'white-container-small' class = 'container'>
      <h2>Choose Campaign</h2>
      <?php
        // check for error messages
      if (isset($_GET['msg'])) {
          $msg = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING);
          echo "<p class = 'error'>$msg</p>";
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
        echo "<select class = 'selectpicker' name = 'choose_cmp'>";
        while ($stmt->fetch()) {
            echo "<option value = $table_name>$campaign</option>";
        }
        echo "</select>";
        echo "</div>";
        ?>
        <button type = 'submit' class = 'btn btn-primary'>Select</button>
      </form>

    </div>
    <div class = 'spacer'> </div>
  </div>
  <footer>
  </footer>
</body>
</html>
