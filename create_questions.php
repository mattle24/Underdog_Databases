<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Questions</title>
    <?php include "includes/head.php"; ?>
</head>
<body>
<?php include('includes/navbar_loggedin.php'); ?>
<div id = 'page-header1'>
    <div class = 'spacer'></div>
    <div id = 'white-container-medium'>
        <div class = 'row'>
            <h2>Manage Questions</h2>
        </div>
        <div class = 'row'>
            <h3>Current Questions</h3>
        </div>
    <?php
    // get list of questions
    if (!isset($_SESSION['cmp'])) {
        $msg = "Please set you campaign before looking at questions.";
        header("Location: choose_campaign.php?msg=$msg");
    } else {
        $cmp = $_SESSION['cmp'];
    }
    require_once('configs/config.php');
    $db = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME) or die('Failed to connect.');
    $query = "SELECT question FROM survey_questions, campaigns
    WHERE campaigns.table_name = ?
    AND survey_questions.campaignid = campaigns.campaignid;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $cmp);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($question);
    if ($stmt->num_rows > 0) {
    //    echo "<table>
    //    <thead id = 'QLhead'>
    //    <tr>
    //        <th>Question</th>
    //    </tr>
    //    </tread>
    //    <tbody id = 'QLbody'>";
        echo "<ul class = 'list-group'>";
        while ($stmt->fetch()) {
            echo "<li class = 'list-group-item'>$question</li>";
        }
        echo "</ul>";
    //    echo "</tbody>
    //    </table>";
    } else {
        echo "No questions found for your campaign.";
    }
    $stmt->free_result();

    // If user is high enough level to change questions, allow them
    // to add and remove questions.
    include 'includes/check_level.php';
    $user_pos = checkLevel("mhl84@cornell.edu", $cmp);

    // Can play around with level required to add questions
    if ($user_pos > 1) {
    echo "
        <div> <!-- Add and remove question forms -->
            <div class = 'col-xs-6'> <!-- div for create question -->
                <h3>New Question</h3>
                <form action = 'new_question.php' method = 'post'>
                    <div class = 'form-group'>
                        <input type = 'text' name = 'new_question' placeholder = 'New question' required/>
                    </div>
                    <button type = 'submit' class = 'btn btn-primary'>Add</button>
                </form>
            </div> <!-- End create question -->

            <div class = 'col-xs-6'> <!-- div for remove quesion -->
                <h3>Remove Question</h3>
                <div class = 'form-group'>
                    <input aira-describedby = 'rmvHelp' type = 'text' name = 'remove_question' required/>
                    <small id = 'rmvHelp' class = 'form-text text-muted'>
                        This will prevent people from creating lists based on responses to this
                        question and uploading new lists with responses to this question. It will not
                        remove the existing responses to this question.
                    </small>
                </div>
                <button class = 'btn btn-primary' type = 'submit'>Remove</button>
            </div> <!-- End remove quesion -->
        </div>
        ";
    } else {
        echo "You do not have permission to edit questions for this campaign.";
    }


    ?>
    </div>
    <div class = 'spacer'></div>
</div>

<footer></footer>
</body>
</html>
