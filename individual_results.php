<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Individual Search Results</title>
    <?php include "includes/head.php";?>
</head>
<body>
    <?php include 'includes/navbar_loggedin.php'; ?>
    <div id = 'page-header1'>
        <div class="spacer"></div>
        <div id = 'white-container-medium' style = "overflow-x:auto;">
        <?php
        if (isset($_GET["countyid"])) {
          $VoterID = filter_input(INPUT_GET, 'countyid', FILTER_SANITIZE_NUMBER_INT);
        } 
        else {
          echo '<p> Value not set, please try again. </p>';
          exit();
        }

        require_once("configs/config.php");  
        $db = new mysqli(DB_HOST, 
                         DB_USER, 
                         DB_PASSWORD, 
                         DB_NAME)or die('Failed to connect.');

        if (mysqli_connect_errno()) {
           echo '<p>Error: Could not connect to database. Please try again later!</p>';
           exit;
        }
        $cmp = $_SESSION['cmp'];
        $query = "SELECT First_Name, Last_Name, Age, Street_Number, Street_Name, City, Area_Code, Phone_Number, Party FROM $cmp
                  WHERE voter_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $VoterID);
        $stmt->execute();
        $stmt->store_result();

        $stmt->bind_result($First_Name, $Last_Name, $Age, $Street_Number, $Street_Name, $City, $AreaCode, $Phone_Number, $Party);

        if ($stmt->num_rows > 0) {
            echo "<h3>Voter Information</h3>";
        }
        else {
            echo "<p>No voter information was found for this Voter ID.";
        }
        while($stmt->fetch()) {
          echo '<p><strong>Name: '.$First_Name.' '.$Last_Name.'</strong>';
          echo '<br />Voter ID: '.$VoterID;
          echo '<br />Address: '.$Street_Number.' '.$Street_Name.', '.$City;
          echo '<br />Party: '.$Party; 
          echo '<br />Age: '.$Age;
          echo '<br />Phone Number: '.$AreaCode.'-'.$Phone_Number.'</p>';
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
        <div class = 'spacer'></div>
    </div>
    <footer></footer>
</body>
</html>