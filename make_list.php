<?php session_start();
include 'includes/check_logged_in.php';
if (!isset($_SESSION['cmp'])) {
    header("Location: choose_campaign.php");
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Make List</title>
   <!-- Source Sans Pro font -->
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
   <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
   <link rel='stylesheet' type='text/css' href="styles/all.css">
   <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
   <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
  <?php
  if (!isset($_SESSION['logged_user'])){header('Location: index.php');}
  include 'includes/navbar_loggedin.php';
  ?>
<div id = "page-header1">
  <div class="spacer"></div>
  <div id = 'make-list-container'>
      <form action = 'list_results.php' method = 'post' id = 'make-list-form'>
          <h2>Create List</h2>
          <h3>Geography</h3>
<!--           <button data-toggle = 'collapse' data-target='#zip'>Zip Code</button>
          <div id='zip' class = 'collapse'> </div>
          This would create collapsable sections of the form
 -->          <?php
//            include("includes/get_credentials.php");
            include("configs/config.php");
            # Get the user's password and the campaign table name
            $username = $_SESSION['logged_user'];
            if (!isset($_SESSION['cmp'])) {
                $msg = "Error. Please select your campaign before creating a list.";
                header("Location: choose_campaign.php?msg=$msg");
            }
            $cmp = $_SESSION['cmp'];
            # TODO: change this so that it uses user credentials, not default
            $db = new mysqli(
              DB_HOST, 
              DB_USER,
              DB_PASSWORD, 
              DB_NAME)or die('Failed to connect.'); 
            $query = "SELECT DISTINCT(Zip) FROM $cmp;"; # Zip Code
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($zip_code);

            echo "<fieldset><legend>Zip Code</legend>";
            while ($stmt->fetch()) {
               echo "<input type = 'checkbox' name = 'zip[]' value = $zip_code>$zip_code";
               echo "   "; // three spaces
            }
            echo "</fieldset>";

            echo "<fieldset><legend>City</legend>";
            $query = "SELECT DISTINCT(City) FROM $cmp;"; # City
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($city);
            while ($stmt->fetch()) {
               echo "<input type = 'checkbox' name = 'city[]' value = $city>$city";
               echo "   "; // three spaces
            }
            echo "</fieldset>";

            echo "<h3>Personal Demography</h3>";

            echo "<fieldset><legend>Age</legend>"; # Age
            echo "<label>Minimum Age</label>
            <input type = 'number' name = 'minage' min='18' placeholder = '18'>";
            echo "<label>Maximum Age</label>
            <input type = 'number' name = 'maxage' max=$maxage>";
            echo "</fieldset>";

            echo "<fieldset><legend>Party</legend>";
            $query = "SELECT DISTINCT(Affiliation) FROM $cmp;"; # Party Reg
            $stmt = $db->prepare($query);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($party);
            while ($stmt->fetch()) {
            	echo "<input type = 'checkbox' name = 'party[]' value = $party>$party";
              echo "   "; // three spaces
            }
            echo "</fieldset>";
          
          // Survey Responses //
          // Code from: http://formvalidation.io/examples/adding-dynamic-field/
          echo "<h3>Survey Responses</h3>";
          echo "
          <div class = 'form-group'>
              <label class = 'col-xs-3 control-label'>Question</label>
              <div class = 'col-xs-5'>
                <input type = 'text' class = 'form-control' name = 'question' />
            </div>
        </div>
        
        <div class = 'form-group'>
            <label class = 'col-xs-3 control-label'>Responses</label>
            <div class = 'col=xs-5'>
                <input type = 'text' class = 'form-control' name = 'response[]' />
            </div>
        </div>
        
        <!-- The option field template containing an option field and a Remove button -->
        
        <div class = 'form-group hide' id = 'responseTemplate'>
            <div c;ass = 'col-xs'offset col-xs-5'>
                <input class = 'form-control' type = 'text' name = 'response[]' />
            </div>
            <div class = 'col-xs-4'>
                <button type = 'button' class = 'btn btn-default removeButton><i lass = 'fa fa-minus'></i></button>
            </div>
        </div>

          ";
            ?>
            <input type = 'reset'>
            <input type = 'submit' name = 'Submit'>
      </form>
</div>
<div class = "spacer"></div>
    </div>
<footer>
</footer>
</body>
</html>