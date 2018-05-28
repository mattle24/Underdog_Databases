<?php
session_start();
include 'includes/check_logged_in.php';
?>
<!DOCTYPE html>
<head>
    <title>Survey Results</title>
    <?php include "includes/head.php"; ?>
</head>
<body>
    <?php include 'includes/navbar_loggedin.php'; ?>
    <div id = 'page-header1'>
        <div class = 'spacer'></div>
        <div id = 'white-container-small'>
            <div class = 'row'>
                <h2>Survey Results</h2>
            </div>
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

            // Add a dropdown menu of every question currently associated with this campaign
            echo"<form action = 'survey_results.php' method = 'post'>
                <div class = 'form-group'>
                    <label>Choose Question</label>";

            $query = "SELECT question FROM survey_questions, campaigns
            WHERE campaigns.table_name = ?
            AND campaigns.campaignid = survey_questions.campaignid
            ORDER BY question;";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $cmp);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($question);
            echo "<select class = 'selectpicker' name = 'question' required>";
            while ($stmt->fetch()) {
                echo "<option value =$question>$question</option>";
            }
            echo "</select>
                </div>
                <button class = 'btn btn-primary' type = 'submit'>Select</button>
                <a href = 'survey_results.php'><button class = 'btn btn-secondary'>Reset</button></a>
           </form>";
            if (isset($_POST['question'])) {
                echo "<br><br>";
                // get campaign id number
                include_once 'includes/functions.php';
                $cmp_number = getCampaignNumber($cmp);
                // Show a quick analysis of the survey responses
                $question = filter_input(INPUT_POST, 'question', FILTER_SANITIZE_STRING);
                $query = "SELECT response, COUNT(response) FROM responses
                          JOIN (SELECT voter_id as vid, MAX(date) as max_date FROM responses
                                WHERE question = ?
                                AND campaign = ?
                                GROUP BY voter_id) sub1 ON voter_id = vid AND date = max_date
                          GROUP BY response
                          ORDER BY response;";

                // TODO: this counts the same voters multiple times
                // This will be much easier to do after making the switch
                // to AWS and being able to use "WITH" clauses
                $stmt = $db->prepare($query);
                $stmt->bind_param('si', $question, $cmp_number);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($response, $number);
                // Output a list of the number of responses for the given question
                if ($stmt->num_rows() == 0) {
                    echo "<p class ='alert alert-warning' role = 'alert'>No responses were found for $question.</p>";
                } else {
                    echo "<h4>$question</h4>";
                    echo "<table>
                    <thead id = 'QLhead'>
                    <tr>
                    <th>Response</th>
                    <th><!--Leave blank for spacing --></th>
                    <th>Count</th>
                    </tr>
                    </tread>
                    <tbody id = 'QLbody'>";
                    while ($stmt->fetch()) {
                        echo "<tr>
                        <td>$response</td>
                        <td>
                        <td>$number</td>
                        </tr>";
                    }
                    echo "</tbody>
                    </table>";
                }
            }
            ?>

        </div>
        <div class = 'spacer'></div>
    </div>
<footer></footer>
</body>
</html>
