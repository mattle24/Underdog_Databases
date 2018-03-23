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
    <?php include "includes/head.php"; ?>
    <!-- To add and remove responses -->
    <!-- import custom script for adding and removing fields -->
    <script src='scripts/add_rm_rsp.js'></script>


</head>
<body>
    <?php
    if (!isset($_SESSION['logged_user'])){header('Location: index.php');}
    include 'includes/navbar_loggedin.php';

    ?>
    <div id = "page-header1">
        <div class="spacer"></div>
        <div id = 'white-container-large' class = 'container'>
            <div class = 'row'>
                <h2>Create List</h2>
                <p>Select the search criteria you want to use to create a list of voters.
                    Multiple search terms in the same group will look for any matches.
                    Search terms in different groups will return voters who meet
                    both critera (ie Democrats in a certain set of cities).
                    You can click on a group header to collapse or expand the options.
                </p>
            </div>
            <form action = 'list_results.php' method = 'post'>
                <br>
                <div class = 'row'>
                    <a data-toggle="collapse" href='#geo'><h3>Geography</h3></a>
                </div>

                <div class = 'row collapse in' id = 'geo'>
                <?php
                require_once("configs/config.php");
                # Get the user's password and the campaign table name
                $username = $_SESSION['logged_user'];
                if (!isset($_SESSION['cmp'])) {
                    $msg = "Error. Please select your campaign before creating a list.";
                    header("Location: choose_campaign.php?msg=$msg");
                }
                $cmp = $_SESSION['cmp'];
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

                // ZIP CODE //
                echo "<div class = 'form-check'>";
                echo "<fieldset>
                <legend>Zip Code</legend>";
                while ($stmt->fetch()) {
                   echo "<label class = 'form-check-label' for = 'zipChk'>$zip_code</label>
                   <input id = 'zipChk' class = 'form-check-input' type = 'checkbox' name = 'zip[]' value = $zip_code>";
                   echo "   "; // three spaces
                }
                echo "</fieldset>";
                echo "</div>";

                // CITY //
                echo "<div class = 'form-check'>";
                echo "<fieldset><legend>City</legend>";
                $query = "SELECT DISTINCT(City) FROM $cmp;";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($city);
                while ($stmt->fetch()) {
                   echo "
                   <label class = 'form-check-label' for = 'cityChk'>$city</label>
                   <input id = 'cityChk' class = 'form-check=input' type = 'checkbox' name = 'city[]' value = $city>";
                }
                echo "</fieldset>";
                echo "</div>";
                echo "</div>"; // End of Geography

                echo "<div class = 'row'>
                <a href='#personal_demography' data-toggle='collapse'><h3>Personal Demography</h3></a>
                </div>";

                echo "<div id ='personal_demography' class='collapse in'>";

                    // AGE //
                    echo "<div class = 'form-group'>";
                        echo "<fieldset><legend>Age</legend>";
                            echo "<div class = 'col-xs-4'>";
                                echo "<label for = 'mnAge'>Minimum Age</label>
                                <input id = 'mnAge' class = 'form-control' type = 'number' name = 'minage' min='18' placeholder = '18' aria-describedby = 'minHelp'>";
                                echo "<small id = 'minHelp' class = 'form-text text-muted'>Minimum age cannot be below 18.</small>";
                            echo "</div>";
                            echo "<div class = 'col-xs-4'>";
                                echo "<label for = 'maxAge'>Maximum Age</label>
                                <input id = 'maxAge' class = 'form-control' class = 'col-xs-2' type = 'number' name = 'maxage' max=$maxage>";
                            echo "</div>";
                        echo "</fieldset>";
                    echo "</div>";

                    // PARTY //
                    echo "<div class = 'form-check'>";
                    echo "<fieldset><legend>Party</legend>";
                    $query = "SELECT DISTINCT(Party) FROM $cmp;"; # Party Reg
                    $stmt = $db->prepare($query);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($party);
                    while ($stmt->fetch()) {
                        echo "
                        <label class = 'form-check-label' for = 'ptyChk'>$party</label>
                        <input type = 'checkbox' name = 'party[]' value = $party>";
                      echo "   "; // three spaces
                    }
                    echo "</fieldset>";
                    echo "</div>";
                echo "</div>"; // End of personal demography

                // Survey Responses //
                echo "<div class = 'row'>
                        <a href ='#responses' data-toggle='collapse'><h3>Survey Responses</h3></a>
                    </div>";
                echo "<div id ='responses' class ='collapse in'>";
                    echo "
                    <div class = 'form-group'>";
                        // get list of questions
                        $query = "SELECT question FROM survey_questions, campaigns
                        WHERE campaigns.table_name = ?
                        AND survey_questions.campaignid = campaigns.campaignid;";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('s', $cmp);
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->bind_result($question);
                        if ($stmt->num_rows > 0) {
                            echo "<label for = 'formQ'>Question</label>";
                            echo "<select id = 'formQ' class = 'form-control' name = 'question'>";
                            echo "<option></option>";
                            while ($stmt->fetch()) {
                                echo "<option value = $question>$question</option>";
                            }
                            echo "</select>";
                        //    echo "</tbody>
                        //    </table>";
                        } else {
                            echo "No questions found for your campaign.";
                        }
                        $stmt->free_result();
                    echo "</div>";
                    // Need to update possible responses with AJAX
                    // Need to give option to add more responses and more questions
                    ?>

                    <!-- Add dynamic response fields (add and remove fields) -->
                    <label>Add Response</label>
                        <button class = 'btn btn-success js-add-button' type = 'button'>
                            <span class ='glyphicon glyphicon-plus'></span>
                        </button>
                    <div class = 'row'>
                        <div class = 'entry input-group col-xs-5 js-inputs-container'>
                            <input class = 'form-control' name = 'responses[]' type = 'text' placeholder='Response' />
                        </div>
                    </div>
                </div>
                <div class = 'row'>
                    <button class = 'btn btn-primary' type = 'submit'>Make List</button>
                </div>
            </form>
        </div>
        <div class = "spacer"></div>
    </div>
<footer>
</footer>
</body>
</html>
