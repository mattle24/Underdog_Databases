<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
   <title>Search</title>
   <!-- Source Sans Pro font -->
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
   <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
   <link rel='stylesheet' type='text/css' href="styles/all.css">
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
    // Find which POST variables were set and prepare them for queries.
    if (isset($_POST['searchid'])) {
        $searchid = filter_input(INPUT_POST,'searchid',FILTER_SANITIZE_NUMBER_INT);
    } else {$searchid = FALSE;}
    if (isset($_POST['searchfirst'])) {
        $searchfirst = filter_input(INPUT_POST, 'searchfirst', FILTER_SANITIZE_STRING);
        $searchfirst = '%'.trim($searchfirst).'%'; // for wildcards
    } else {$searchfirst = '%';}                      
    if (isset($_POST['searchlast'])) {
        $searchlast = filter_input(INPUT_POST, 'searchlast', FILTER_SANITIZE_STRING);
        $searchlast = '%'.trim($searchlast).'%'; // for wildcards
    } else {$searchlast = '%';}                      
    if (isset($_POST['searchcity'])) {
        $searchcity = filter_input(INPUT_POST, 'searchcity', FILTER_SANITIZE_STRING);
        $searchcity = '%'.trim($searchcity).'%'; // for wildcards
    } else {$searchcity = '%';}                      
    if (isset($_POST['searchstreet'])) {
        $searchstreet = filter_input(INPUT_POST, 'searchstreet', FILTER_SANITIZE_STRING);
        $searchstreet = '%'.trim($searchstreet).'%'; // for wildcards
    } else {$searchstreet = '%';}                      
    if (isset($_POST['searchnumber'])) {
        $searchnumber = filter_input(INPUT_POST, 'searchumber', FILTER_SANITIZE_STRING);
        $searchnumber = '%'.trim($searchnumber).'%'; // for wildcards
    } else {$searchnumber = '%';}                      
                    

   if (!$searchlast && !$searchcity && !$searchstreet &&!$searchnumber && !$searchid && !$searchfirst) {
       echo '<p>You have not entered search details. Please go back and try again.</p>';
       exit();
    }

    include('configs/config.php');
    $db = new mysqli(DB_HOST, 
                     DB_USER, 
                     DB_PASSWORD, 
                     'voter_file')or die('Failed to connect.');
  
    if (mysqli_connect_errno()) {
       echo '<p>Error: Could not connect to database.<br/>
       Please try again later!</p>';
       exit;
    }
      
    $cmp = $_SESSION['cmp'];  
    if ($searchid) {
      $query = "SELECT CountyID, FirstName, LastName, Age, StreetNumber, StreetName, City
                FROM $cmp WHERE COUNTYID = ? AND FirstName LIKE ? AND LastName LIKE ? AND City LIKE ? AND StreetName LIKE ? AND StreetNumber LIKE ?";
      $stmt = $db->prepare($query);
      $stmt->bind_param('isssss', $searchid, $searchfirst, $searchlast, $searchcity, $searchstreet, $searchnumber);
    }
    else { //countyid can't utilize '%' wildcard
      $query = "SELECT CountyID, FirstName, LastName, Age, StreetNumber, StreetName, City
                FROM $cmp WHERE FirstName LIKE ? AND LastName LIKE ? AND City LIKE ? AND StreetName LIKE ? AND StreetNumber LIKE ?;";
      $stmt = $db->prepare($query);
      $stmt->bind_param('sssss', $searchfirst, $searchlast, $searchcity, $searchstreet, $searchnumber);      
    }
    $stmt->execute();
    $stmt->store_result();

    $stmt->bind_result($CountyID, $FirstName, $LastName, $Age, $StreetNumber,$StreetName, $City);

    echo "<center><p>Number of records found: ".$stmt->num_rows.". Showing  ".min($stmt->num_rows,75).".<br /></p>";

    echo '<table>
            <thead id = "QLhead">
            <tr>
              <th>COUNTY ID</th>
              <th>NAME</th>
              <th>ADDRESS</th>
              <th>CITY</th>
              <th>AGE</th>
            </tr>
            </thead>
            <tbody id = "QLbody">';

    $row = 0;
    while($stmt->fetch() & $row < 75) {
      echo '
        <tr>
          <td>
            <a href = "ind_results.php?countyid='.$CountyID.'">'.$CountyID.'</a>
          </td>
          <td>'.$FirstName.' '.$LastName.'</td>
          <td>'.$StreetNumber.' '.$StreetName.'</td>
          <td>'.$City.'</td>
          <td>'.$Age.'</td>
        </tr>';
        $row = $row + 1;
    }
    echo '</tbody></center>';
    $stmt->free_result();
    $db->close();
  ?>

</div>
</body>
</html>