<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
   <title>Individual Search Results</title>
   <!-- Source Sans Pro font -->
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
   <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
   <link rel='stylesheet' type='text/css' href="styles/all.css">
   <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
   <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body id = 'make-list-body'>
  <?php
  include 'includes/navbar_loggedin.php';
  ?>
  <div class="spacer"></div>
  <div id = 'make-list-container' style = "overflow-x:auto;">
    <?php
    if (isset($_GET["countyid"])) {
      $VoterID = filter_input(INPUT_GET, 'countyid', FILTER_SANITIZE_NUMBER_INT);
    } 
    else {
      echo '<p> Value not set, please try again. </p>';
      exit();
    }

    include("configs/config.php");  
    $db = new mysqli(DB_HOST, 
                     DB_USER, 
                     DB_PASSWORD, 
                     DB_NAME)or die('Failed to connect.');

    if (mysqli_connect_errno()) {
       echo '<p>Error: Could not connect to database. Please try again later!</p>';
       exit;
    }
    $cmp = $_SESSION['cmp'];
    $query = "SELECT FirstName, LastName, Age, StreetNumber, StreetName, City, AreaCode, TelephoneNumber, Affiliation FROM $cmp
              WHERE countyid = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $VoterID);
    $stmt->execute();
    $stmt->store_result();

    $stmt->bind_result($FirstName, $LastName, $Age, $StreetNumber, $StreetName, $City, $AreaCode, $TelephoneNumber, $Affiliation);
    
    if ($stmt->num_rows > 0) {
        echo "<h3>Voter Information</h3>";
    }
    else {
        echo "<p>No voter information was found for this Voter ID.";
    }
    while($stmt->fetch()) {
      echo '<p><strong>Name: '.$FirstName.' '.$LastName.'</strong>';
      echo '<br />Voter ID: '.$VoterID;
      echo '<br />Address: '.$StreetNumber.' '.$StreetName.', '.$City;
      echo '<br />Party: '.$Affiliation; 
      echo '<br />Age: '.$Age;
      echo '<br />Phone Number: '.$AreaCode.'-'.$TelephoneNumber.'</p>';
    }
    $stmt->free_result();
    
    // Get survey responses
    $query = "SELECT question, response, date FROM responses
    WHERE voter_id = ?
    AND campaign = ?;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('is', $VoterID, $cmp);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($question, $response, $date);
    
    if ($stmt->num_rows > 0) {
        echo "<h3>Contact History</h3>";
        while ($stmt->fetch()) {
            echo "<p><strong>Question:</strong> $question<br><strong>Response:</strong> $response <br>
            <strong>Date:</strong> $date</p>";
        }
    }
    $stmt->free_result();
    $db->close();
    ?>

</div>
</body>
</html>