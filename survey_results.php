<?php
session_start();
include 'includes/check_logged_in.php';
?>
<!DOCTYPE html>
<head>
    <title>Survey Results</title>
    <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet' type='text/css' href="styles/all.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
    <?php include 'includes/navbar_loggedin.php'; ?>
    <div id = 'page-header1'>
        <div class = 'spacer'></div>
        <div id = 'my-form'>
            <h2>Survey Results</h2>
            <a href = 'survey_results.php'><button>Reset</button></a>
            <?php
            if (!isset($_SESSION['cmp'])) {
                $msg = "Error. Please select a campaign before looking at survey results.";
                header("Location: choose_campaign.php?msg=$msg");
            }
            $cmp = $_SESSION['cmp'];
            
            // Connect to DB
            require_once('configs/config.php');
            $db = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASSWORD,
            DB_NAME) or die('Failed to connect.');
            
            if (!isset($_POST['question'])) {                
                // Add a dropdown menu of every question currently associated with this campaign
                echo"<form action = 'survey_results.php' method = 'post'>
                <label>Select Question</label>";

                $query = "SELECT question FROM survey_questions, campaigns
                WHERE campaigns.table_name = ?
                AND campaigns.campaignid = survey_questions.campaignid;";
                $stmt = $db->prepare($query);
                $stmt->bind_param('s', $cmp);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($question);
                echo "<select name = 'question' required>";
                while ($stmt->fetch()) {
                    echo "<option value =$question>$question</option>";
                }
                echo "</select>
                    <input type = 'submit'></input>
                </form>";
            } else {
                // Show a quick analysis of the survey responses
                $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING);
                // Get count of distinct responses
                $query = "SELECT response, COUNT(response) FROM responses, campaigns 
                WHERE campaigns.table_name = ?
                AND campaigns.campaignID = responses.campaign 
                AND responses.question = ? 
                GROUP BY response";
                // TODO: this counts the same voters multiple times
                // This will be much easier to do after making the switch
                // to AWS and being able to use "WITH" clauses
                $stmt = $db->prepare($query);
                $stmt->bind_param('ss', $cmp, $question);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($response, $number);
                echo "<table>
                <thead id = 'QLhead'>
                <tr>
                <th>Response</th>
                <th>Number of Responses</th>
                </tr>
                </tread>
                <tbody id = 'QLbody'>";
                while ($stmt->fetch()) {
                    echo "<tr>
                    <td>$response</td>
                    <td>$number</td>
                    </tr>";
                }
                echo "</tbody>
                </table>";


            }
            ?>
        
        </div>
        <div class = 'spacer'></div>
    </div>
<footer></footer>
</body>
</html>
