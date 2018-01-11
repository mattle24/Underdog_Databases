<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>List Results</title>
    <?php include "includes/head.php"; ?>
</head>
<body>
    <?php include 'includes/navbar_loggedin.php'; ?>
<div id = 'page-header1'>
    <div class="spacer"></div>
    <div id = 'white-container-large'>
        <a href="make_list.php">Back to Make List</a>
        <h2 align='center'>List Results</h2>
        <?php
        include("configs/config.php");
        # Get the user's password and the campaign table name
        $username = $_SESSION['logged_user'];
        $cmp = $_SESSION['cmp'];
        # TODO: change this so that it uses user credentials, not default
        $db = new mysqli(
        DB_HOST, 
        DB_USER, #$_SESSION['logged_user'], 
        DB_PASSWORD, 
        DB_NAME)or die('Failed to connect.'); 
        $query = "SELECT CountyID, FirstName, LastName, Age, StreetNumber, StreetName, City FROM $cmp ";
        # if not where, add "WHERE"
        # if where, add "AND"
        $where = False;
        // ZIP CODE //
        if (isset($_POST['zip'])) {
            $zip = $_POST['zip'];
            $zipWhere = "(".implode(",", $zip).")";
            if ($where == False){
                $query = $query."WHERE zip in $zipWhere";
                $where = True;
            }else{
                $query = $query." AND zip in $zipWhere";
            } 
        }
        // CITY //
        if (isset($_POST['city'])) {
            $city = $_POST['city'];
            $cityWhere = "('".implode("','", $city)."')";
            if ($where == False){
                $query = $query."WHERE city in $cityWhere";
                $where = True;
            }else{
                $query = $query." AND city in $cityWhere";
            } 
        }
        // PARTY //
        if (isset($_POST['party'])) {
            $partyWhere = "('".implode("','", $_POST['party'])."')";
            if ($where == False){
                $query = $query."WHERE affiliation in $partyWhere";
                $where = True;
            }else{
                $query = $query." AND affiliation in $partyWhere";
            } 
        }
        // AGE //
        if (isset($_POST['minage'])) {
            $minage = max(18, $_POST['minage']); // weird handling issue
            if (!empty($_POST['maxage'])){$maxage = filter_input(INPUT_POST, 'maxage', FILTER_SANITIZE_NUMBER_INT);}
            else{ $maxage = 200; } // fewer conditions to code through 
            if ($where == False){
                $query = $query."WHERE age BETWEEN $minage AND $maxage";
                $where = True;
            }else{
                $query = $query." AND age BETWEEN $minage AND $maxage";
            } 
        } elseif (isset($_POST['maxage'])) {
            $maxage = $_POST['maxage'];
            if ($where == False){
                $query = $query."WHERE age <= $maxage";
                $where = True;
            }else{
                $query = $query." AND age <= $maxage";
            }          
        }
        $_SESSION['query'] = $query;
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($CountyID, $FirstName, $LastName, $Age, $StreetNumber,$StreetName, $City);
        echo "<center><p>Number of records found: ".$stmt->num_rows.". Showing  ".min($stmt->num_rows,75).".<br /></p>";
        echo "<form action = 'export_list.php' method = 'post'>
            <button type = 'submit'>Export List</button>
            </form>";

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
          echo "
            <tr>
              <td>$CountyID</td>
              <td>$FirstName $LastName</td>
              <td>$StreetNumber $StreetName</td>
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
    <div class="spacer"></div>
</div>
<footer>
</footer>
</body>
</html>