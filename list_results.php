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

        <h2>List Results</h2>
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
        $query = "SELECT $cmp.voter_id, $cmp.First_Name, $cmp.Last_Name, YEAR(CURDATE()) - YEAR(dob) as age, $cmp.Street_Number, $cmp.Street_Name, $cmp.City FROM $cmp ";
        # if not where, add "WHERE"
        # if where, add "AND"
        $where = False;

        // Survey Responses //
        // This needs to be first
        // problem: question and response are always set
        $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING);

        if ( !(empty($_POST['question'])) and isset($_POST['responses']) ) {
            $question = trim(filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING));
            // Validate each part of the responses array
//            $respWhere = array();
//            foreach ($_POST['responses'] as $resp) {
//
//            }
            $respWhere = "('".implode("','", $_POST['responses'])."')";
            // Determine if we need to append WHERE or AND
            $query = $query."LEFT JOIN responses ON responses.voter_id = $cmp.voter_id";
            // Break this up to really emphasis the query steps
            $query = $query." WHERE responses.question = '$question' AND responses.response IN $respWhere AND responses.campaign = '$cmp'";
            $where = True;
        }

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
                $query = $query."WHERE city IN $cityWhere";
                $where = True;
            }else{
                $query = $query." AND city in $cityWhere";
            }
        }

        // PARTY //
        if (isset($_POST['party'])) {
            $partyWhere = "('".implode("','", $_POST['party'])."')";
            if ($where == False){
                $query = $query."WHERE party in $partyWhere";
                $where = True;
            }else{
                $query = $query." AND party in $partyWhere";
            }
        }

        // AGE //
        if (isset($_POST['minage'])) {
            $minage = max(18, $_POST['minage']); // weird handling issue
            if (!empty($_POST['maxage'])){$maxage = filter_input(INPUT_POST, 'maxage', FILTER_SANITIZE_NUMBER_INT);}
            else{ $maxage = 200; } // fewer conditions to code through, I can just use BETWEEN
            if ($where == False){
                $query = $query."WHERE YEAR(CURDATE()) - YEAR(dob) BETWEEN $minage AND $maxage";
                $where = True;
            }else{
                $query = $query." AND YEAR(CURDATE()) - YEAR(dob) BETWEEN $minage AND $maxage";
            }
        } elseif (isset($_POST['maxage'])) {
            $maxage = $_POST['maxage'];
            if ($where == False){
                $query = $query."WHERE YEAR(CURDATE()) - YEAR(dob) <= $maxage";
                $where = True;
            }else{
                $query = $query." AND YEAR(CURDATE()) - YEAR(dob) <= $maxage";
            }
        }
        // echo $query;
        $_SESSION['query'] = $query;
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($Voter_ID, $First_Name, $Last_Name, $Age, $Street_Number,$Street_Name, $City);
        echo "<center><p>Number of records found: ".$stmt->num_rows.". Showing  ".min($stmt->num_rows,75).".
        <a href='make_list.php'>Back to make list</a></p>";?>

        <div class = 'btn-group'>
            <div class = 'col-xs-4'>
                <form action = 'export_list.php' method = 'post'>
                    <button class = 'btn btn-secondary' type = 'submit'>Export List</button>
                </form>
            </div>

            <div class = 'col-xs-4'>
                <a href='canvassing.php'><button type='button' class = 'btn btn-outline-primary'>Cut Turf</button></a>
            </div>
        </div> <!-- End buttons -->
        <?php
        echo '<div class="table-responsive">
                <table class="table">
                <thead id = "QLhead">
                <tr>
                  <th scope="col">VOTER ID</th>
                  <th scope="col">NAME</th>
                  <th scope="col">ADDRESS</th>
                  <th scope="col">CITY</th>
                  <th scope="col">AGE</th>
                </tr>
                </thead>
                <tbody id = "QLbody">';

        $row = 0;
        while($stmt->fetch() & $row < 75) {
          echo "
            <tr>
              <td>$Voter_ID</td>
              <td>$First_Name $Last_Name</td>
              <td>$Street_Number $Street_Name</td>
              <td>$City</td>
              <td>$Age</td>
            </tr>";
            $row = $row + 1;
        }
        echo '</tbody>
        </table>
        </div>
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
