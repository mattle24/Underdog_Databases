<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <!-- Source Sans Pro font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet' type='text/css' href="styles/all.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
   <?php include 'includes/navbar_loggedin.php' ?>
   <div id = "page-header1">
       <div class = 'spacer'></div>
       <div id = 'landing-container'>
           <div id = 'my-form'>
           <h3>Change user position</h3>
            <p>You can change the positions of users with lower roles than you. You can select a role equal to or lower than your role.</p>
           <form action = "change_user.php" method = "post">
               <label>User email</label>
               <input type = 'text' name = 'change_email' required></input>
               <br>
                <label>New Position</label>
                <select name = 'new_pos' required>
                    <option value = 8>Field Director</option>
                    <option value = 6>Senior Staff</option>
                    <option value = 4>Field Organizer</option>
                    <option value = 1>Volunteer</option>
                </select>
           </form>
       </div>
           <h3 align = 'center'>Campaign Team</h3>
        <?php
    // Get all the users for the given campaign and list them in order
    // of position then name
    if (!isset($_SESSION['cmp'])) {
        echo "<p><a href='choose_campaign.php'>Please choose your campaign.</a></p>";
        exit();
    }
    $cmp = $_SESSION['cmp'];
    include("configs/config.php");
    $db = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASSWORD,
        DB_NAME) or die("Failed to connect.");
    $query = "SELECT users.first, users.last, users.email, positions_reference.name 
    FROM users, user_campaign_bridge, campaigns, positions_reference 
    WHERE campaigns.table_name = ?
    AND user_campaign_bridge.campaignID = campaigns.campaignID 
    AND positions_reference.number = user_campaign_bridge.Position 
    AND users.userID = user_campaign_bridge.userID
    ORDER BY positions_reference.number DESC, users.last, users.first;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $cmp);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($first, $last, $email, $position);
    
    echo '<center>
        <table>
        <thead id = "QLhead">
        <tr>
          <th>First</th>
          <th>Last</th>
          <th>Email</th>
          <th>Position</th>
        </tr>
        </thead>
        <tbody id = "QLbody">';
    while($stmt->fetch()) {
      echo "
        <tr>
          <td>$first</td>
          <td>$last</td>
          <td>$email</td>
          <td>$position</td>
        </tr>";
    }
    echo '</tbody></center>'; 
        ?>
       </div>
    </div>
</body>
</html>