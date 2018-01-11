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
<!-- <script type = 'text/javascript' src = 'scripts/add_rm_fields.js'></script> -->
    <script>
    $(document).ready(function() {
        $(document).on('click', '.btn-add', function(e) {
            e.preventDefault();

            var controlForm = $('.controls form:first'),
                currentEntry = $(this).parents('.entry:first'),
                newEntry = $(currentEntry.clone()).appendTo(controlForm);

            newEntry.find('input').val('');
            controlForm.find('.entry:not(:last) .btn-add')
                .removeClass('btn-add').addClass('btn-remove')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="glyphicon glyphicon-minus"></span>');
        }).on('click', '.btn-remove', function(e) {
            $(this).parents('.entry:first').remove();

            e.preventDefault();
            return false;
        });
    });
    </script>

</head>
<body>
  <?php
  if (!isset($_SESSION['logged_user'])){header('Location: index.php');}
  include 'includes/navbar_loggedin.php';
  ?>
    <div id = "page-header1">
        <div class="spacer"></div>
        <div id = 'white-container-large' class = 'container'>
            <form action = 'list_results.php' method = 'post'>
                <div class = 'row'>
                    <h2>Create List</h2>
                </div>
                <br>
                <div class = 'row'>
                    <h3>Geography</h3>
                </div>
<!--           <button data-toggle = 'collapse' data-target='#zip'>Zip Code</button>
          <div id='zip' class = 'collapse'> </div>
          This would create collapsable sections of the form
 -->          
            <?php
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

            echo "<div class = 'row'>
            <h3>Personal Demography</h3>
            </div>";
            
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
            $query = "SELECT DISTINCT(Affiliation) FROM $cmp;"; # Party Reg
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
          
            // Survey Responses //
            echo "<div class = 'row'>
                    <h3>Survey Responses</h3>
                </div>";
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
                    echo "<select id = 'formQ' class = 'form-control'>";
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
                <div class="container">
                    <div class="row">
                        <div class="control-group" id="fields">
                            <label class="control-label" for="field1">Responses</label>
                            <div class="controls"> 
                                <form role="form" autocomplete="off">
                                    <div class="entry input-group col-xs-3">
                                        <input class="form-control" name="fields[]" type="text" placeholder="Type something" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-success btn-add" type="button">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> 
                <button class = 'btn btn-primary' type = 'submit'>Make List</button>
            </form>
        </div>
        <div class = "spacer"></div>
    </div>
<footer>
</footer>
</body>
</html>