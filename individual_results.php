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
      $CountyID = filter_input(INPUT_GET, 'countyid', FILTER_SANITIZE_NUMBER_INT);
    } 
    else {
      echo '<p> Value not set, please try again. </p>';
      exit();
    }
      
    include("configs/config.php");  
    $db = new mysqli(DB_HOST, 
                     DB_USER, 
                     DB_PASSWORD, 
                     'voter_file'
                    )or die('Failed to connect.');
  
    if (mysqli_connect_errno()) {
       echo '<p>Error: Could not connect to database. Please try again later!</p>';
       exit;
    }
    $cmp = $_SESSION['cmp'];
    $query = "SELECT FirstName, LastName, Age, StreetNumber, StreetName, City, AreaCode, TelephoneNumber, Affiliation FROM $cmp
              WHERE countyid = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $CountyID);
    $stmt->execute();
    $stmt->store_result();

    $stmt->bind_result($FirstName, $LastName, $Age, $StreetNumber, $StreetName, $City, $AreaCode, $TelephoneNumber, $Affiliation);

    while($stmt->fetch()) {
      echo '<p><strong>Name: '.$FirstName.' '.$LastName.'</strong>';
      echo '<br />Voter ID: '.$CountyID;
      echo '<br />Address: '.$StreetNumber.' '.$StreetName.', '.$City;
      echo '<br />Party: '.$Affiliation; 
      echo '<br />Age: '.$Age;
      echo '<br />Phone Number: '.$AreaCode.'-'.$TelephoneNumber.'</p>';
    }
    $stmt->free_result();
    $db->close();
  ?>

</div>
</body>
</html>