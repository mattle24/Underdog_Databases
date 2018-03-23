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
            $searchid = filter_input(INPUT_POST,'searchid',FILTER_SANITIZE_STRING);
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
           // TODO: this is coded terribly. It should not exit. Also, this does not do anything because all this terms are set.
           // Look at donor search results for something better
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
           exit();
        }

        $cmp = $_SESSION['cmp'];
        if ($searchid) {
            $query = "SELECT voter_id, First_Name, Last_Name, Age, Street_Number, Street_Name, City
            FROM $cmp
            WHERE voter_id = ?
            AND First_Name LIKE ?
            AND Last_Name LIKE ?
            AND City LIKE ?
            AND Street_Name LIKE ?
            AND Street_Number LIKE ?;";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ssssss', $searchid, $searchfirst, $searchlast, $searchcity, $searchstreet, $searchnumber);
        }
        else {
			//voter_id shouldn't utilize '%' wildcard so we can't include a voter_id LIKE '%'
            // because voter_id is unique and shouldn't search for similar numbers
            // Argument could be made the same is true for age and street number --> TODO!
			$query = "SELECT voter_id, First_Name, Last_Name, Age, Street_Number, Street_Name, City
			FROM $cmp
			WHERE First_Name LIKE ?
			AND Last_Name LIKE ?
			AND City LIKE ?
			AND Street_Name LIKE ?
			AND Street_Number LIKE ?;";
          $stmt = $db->prepare($query);
          $stmt->bind_param('sssss', $searchfirst, $searchlast, $searchcity, $searchstreet, $searchnumber);
        }
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($voter_id, $First_Name, $Last_Name, $Age, $Street_Number,$Street_Name, $City);

        echo "<div class = 'row'>
                <center><p>Number of records found: ".$stmt->num_rows.". Showing  ".min($stmt->num_rows,75).".<br /></p>
            </div>";

        echo "<table class = 'table'>
                <thead id = 'QLhead'>
                <tr>
                  <th scope = 'col'>VOTER ID</th>
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
                <a href = 'individual_results.php?voter_id=$voter_id'>$voter_id</a>
              </td>
              <td>$First_Name"." "."$Last_Name</td>
              <td>$Street_Number"." "."$Street_Name</td>
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
