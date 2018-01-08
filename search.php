<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
   <title>Search</title>
   <?php include "includes/head.php"; ?>
</head>
<body>
    <?php
    include 'includes/navbar_loggedin.php';
    if (!isset($_SESSION['cmp'])) {
        $msg = "Error. Please select your campaign before searching.";
        header("Location: choose_campaign.php?msg=$msg");
    }
    ?>
    <div id = 'page-header1'>
        <div class="spacer"></div>
        <div id = 'white-container-medium'>
            <div class = 'row'>
                <h2>Search</h2>
                <p align = 'left'>Use one or more of the search terms below. Results will display the top 25 voters that match your terms. If you are looking for someone else please use more specific search terms. </p>
            </div>
            <form action = "search_results.php" method = "post">
                <p><strong>Choose Search Type(s):</strong></p>
                <!-- Voter ID -->
                <div class = 'form-group'>
                    <label for = "formVID">VoterID</label>
                    <input id = 'formVID' class = 'form-control' type = 'text' name = 'searchid'/>
                </div>
                <!-- Name -->
                <div class = 'form-group'>
                    <label for = 'formFirst'>First name</label>
                    <input id = 'firmFirst' class = 'form-control' type = 'text' name = 'searchfirst'/>
                    <label for = 'formLast'>Last name: </label>
                    <input id = 'formLast' class = 'form-control' type = 'text' name = 'searchlast'/>
                </div>
                <!-- Location -->
                <div class = 'form-group'>
                    <label for = 'formCity'>City</label>
                    <input id = 'formCity' class = 'form-control' type = 'searchterm' name = 'searchcity'>
                    <label for = 'formSt'>Street Name</label>
                    <input id = 'formSt' class = 'form-control' type = 'searchterm' name = 'searchstreet'>
                    <label for = 'formNum'>Street Number</label>
                    <input id = 'formNum' class = 'form-control' type="searchterm" name="searchnumber"> 
                </div>
                <button class = 'btn btn-primary' type = 'submit'>Search</button>
            </form>
        </div>
        <div class = 'spacer'></div>
    </div>
<footer>
</footer>
</body>
</html>