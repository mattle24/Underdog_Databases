<?php 
session_start();
include("includes/check_logged_in.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import</title>
    <?php include "includes/head.php"; ?>
</head>
<body>
    <?php
    if (!isset($_SESSION['cmp'])){
        $msg = "Error. Please choose your campaign before importing a list.";
        header("Location: choose_campaign.php?msg=$msg");
    }
    include 'includes/navbar_loggedin.php';
    ?>
    <div id = 'page-header1'>
        <div class = 'spacer'></div>
        <div id = 'white-container-medium'>
            <div class = 'row'>
                <h2>Import Responses</h2>
                <p><a href = 'help.php'>Help</a> on importing voter responses.</p>
            </div>
            <?php
            // Ask user how many survery questions she wants to enter
            echo "<a href = 'import_list.php' class = 'button-link'><button class = 'btn btn-secondary'>Reset</button></a>";
            if (!isset($_POST['Qnum']) && !isset($_POST['questions'])){
                echo "<form action = 'import_list.php' method = 'post'>
                        <div class = 'form-group'>
                            <label for = 'nQ'>Number of survey questions</label>
                            <input id = 'nQ' class = 'form-control' type = 'number' name = 'Qnum' required></input>
                            <label for = 'nC'>What number column is the voter ID in?</label>
                            <input id = 'nC' class = 'form-control' type = 'number' name = 'id_col' required></input>
                            <label for = 'nC2'>What number column is the first question in?</label>
                            <input id = 'nC2' class = 'form-control' type = 'number' name = 'Qstart' required></input>
                        </div>
                        <button class = 'btn btn-primary' type = 'submit'>Submit</button>
                    </form> ";
            } else {
                if (!isset($_POST['questions'])) {
                    $_SESSION['Qnum'] = filter_input(INPUT_POST, 'Qnum', FILTER_SANITIZE_NUMBER_INT); 
                    $_SESSION['Qstart'] = filter_input(INPUT_POST, 'Qstart', FILTER_SANITIZE_NUMBER_INT) - 1;  // because php uses 0 index
                    $_SESSION['id_col'] = filter_input(INPUT_POST, 'id_col', FILTER_SANITIZE_NUMBER_INT) - 1;
                    // TODO: change so that each question is a drop down of available questions
                     require_once('configs/config.php');
                     $db = new mysqli(
                     DB_HOST, 
                     DB_USER, 
                     DB_PASSWORD, 
                     DB_NAME
                     )or die('Failed to connect.'); 
                     $cmp = $_SESSION['cmp'];
                     $query = "SELECT DISTINCT(question) FROM survey_questions, campaigns 
                     WHERE campaigns.table_name = ?
                     AND survey_questions.campaignid = campaigns.campaignid;";
                     $stmt = $db->prepare($query);
                     $stmt->bind_param('s', $cmp);
                     $stmt->execute();
                     $stmt->store_result();
                     $stmt->bind_result($question);
                    $survey_questions = array();
                    while ($stmt->fetch()) {
                        array_push($survey_questions, $question);
                    }

                    // Create form where for each of the number of questions there 
                    // is a dropdown menu populated with each possible question
                    echo "<form action = 'import_list.php' method = 'post'>";
                    echo "<div class = 'form-group'>";
                    for ($num = 1; $num <= $_SESSION['Qnum']; $num ++) {
                        //  because no one wants question 0
                        echo "<label>Question $num</label>";
                        echo "<select class = 'form-control' name = 'questions[]'>";
                        foreach ($survey_questions as $q) {
                            echo "<option value = $q>$q</option>";
                        }
                        echo "</select>";
                    }
                    echo "</div>";
                    echo "<button class = 'btn btn-primary' type = 'submit'>Submit</button>
                    </form>";
                }
                else {
                    $_SESSION['questions'] = $_POST['questions']; // filter_input_array(INPUT_POST, 'questions');
                    // TODO: improve security above
                    if (!is_array($_SESSION['questions'])) {
                        header("Location: logout.php");
                        exit();
                    }
                    echo "<form action='includes/functions.php' method = 'post' name = 'upload_csv' enctype = 'multipart/form-data'>
                        <label>Select File</label>
                        <input type = 'file' name = 'file' id = 'file'></input><br>
                        <button type='submit' id='submit' name='Import' class='btn btn-primary button-loading' data-loading-text='Loading...'>Import</button>
                    </form>";
                    // get_some_records(); // custom function from https://www.cloudways.com/blog/import-export-csv-using-php-and-mysql/
                }
            }
            ?>
  </div>
</div>
<footer></footer>
</body>
</html>