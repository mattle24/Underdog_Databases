<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <?php include "includes/head.php"; ?>
</head>
<body>
    <?php include 'includes/navbar_loggedin.php'; ?>
    <div id = 'page-header1'>
    <div class="spacer"></div>
        <div id = 'white-container-large' style = "overflow-x:auto;">
        <div class = 'row'>
            <h2 align = 'center'>Search Results</h2>
        </div>

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

        require_once('configs/config.php');
        $db = new mysqli(DB_HOST, 
                         DB_USER, 
                         DB_PASSWORD, 
                         DB_NAME)or die('Failed to connect.');

        if (mysqli_connect_errno()) {
           echo '<p>Error: Could not connect to database.<br/>
           Please try again later!</p>';
           exit;
        }

        $cmp = $_SESSION['cmp'];  
        if ($searchid) {
          $query = "SELECT CountyID, FirstName, LastName, Age, StreetNumber, StreetName, City
                    FROM $cmp WHERE COUNTYID = ? AND FirstName LIKE ? AND LastName LIKE ? AND City LIKE ? AND StreetName LIKE ? AND StreetNumber LIKE ?;";
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

        echo "<div class = 'row'>
                <center><p>Number of records found: ".$stmt->num_rows.". Showing  ".min($stmt->num_rows,75).".<br /></p>
            </div>";

        echo "<table class = 'table'>
                <thead id = 'QLhead'>
                <tr>
                  <th scope = 'col'>Voter ID</th>
                  <th scope = 'col'>NAME</th>
                  <th scope = 'col'>ADDRESS</th>
                  <th scope = 'col'>CITY</th>
                  <th scope = 'col'>AGE</th>
                </tr>
                </thead>
                <tbody id = 'QLbody'>";

        $row = 0;
        while($stmt->fetch() & $row < 75) {
          echo "
            <tr>
              <td>
                <a href = 'individual_results.php?countyid=$CountyID'>$CountyID</a>
              </td>
              <td>$FirstName"." "."$LastName</td>
              <td>$StreetNumber"." "."$StreetName</td>
              <td>$City</td>
              <td>$Age</td>
            </tr>";
            $row = $row + 1;
        }
        echo '</tbody>
        </table>
        </center>';
        $stmt->free_result();
        $db->close();
      ?>

    </div>
    <div class = 'spacer'></div>
</div>
<footer></footer>
</body>
</html>