<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Questions</title>
    <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet' type='text/css' href="styles/all.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
<?php include('includes/navbar_loggedin.php'); ?>
<div id = 'page-header1'>
    <div class = 'spacer'></div>
    <div id = 'my-form'>
    <h3>Current Questions</h3>
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
        while ($stmt->fetch()) {
            echo "<p>$question</p>";
        }
    //    echo "</tbody>
    //    </table>";
    } else {
        echo "No questions found for your campaign.";
    }
    $stmt->free_result();

    // If user is high enough level to change questions, allow them
    // to add and remove questions.
    include 'includes/check_level.php';
    $user_pos = checkLevel("mhl84@cornell.edu", "cornell");
    
    // Can play around with level required to add questions
    if ($user_pos > 3) {
        echo "<br><br>
        <form action = 'new_question.php' method = 'post'>
        <h3>New Question</h3>
        <input type = 'text' name = 'new_question' required></input>
        <input type = 'submit'></input>
        </form>
        <br>
        <form action = 'remove_question.php' method = 'post'>
        <h3>Remove Question</h3>
        <p>This will prevent people from creating lists based on responses to this question and uploading new lists with responses to this question. It will not remove the existing responses to this question</p>
        <input type = 'text' name = 'remove_question' required></input>
        <input type = 'submit'></input>
        </form>";
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