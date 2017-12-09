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
   <link rel="shortcut icon" href="images/dog.png" type="image/x-icon"/>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
   <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body id = 'make-list-body'>
  <?php
  include 'includes/navbar_loggedin.php';
    if (!isset($_SESSION['cmp'])) {
        header("Location: choose_campaign.php");
    }
  ?>
  <div class="spacer"></div>
  <div id = 'make-list-container'>
      <p align = 'left'>Use one or more of the search terms below. Results will display the top 25 voters that match your terms. If you are looking for someone else please use more specific search terms. </p>
		<form action = "search_results.php" method = "post">
        <p><strong>Choose Search Type(s):</strong></p>
    	<div align="left">
    	<label>VoterID</label>
    	<input type = 'searchterm' name = 'searchid'>
    	<label>First name</label>
    	<input type = 'searchterm' name = 'searchfirst'><br>
    	<label>Last name: </label>
    	<input type = 'searchterm' name = 'searchlast'>
    	<label>City</label>
    	<input type = 'searchterm' name = 'searchcity'><br>
    	<label>Street Name</label>
    	<input type = 'searchterm' name = 'searchstreet'>
    	<label>Street Number</label>
    	<input type="searchterm" name="searchnumber"> 
    	</div>
     <p><input type="submit" name="submit" value="Search"></p>
    </form>

</div>
<div class = "spacer"></div>
<footer>
</footer>
</body>
</html>