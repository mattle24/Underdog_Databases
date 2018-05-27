<?php
function get_questions($cmp) {
    // Given campaign, return array of questions
    require_once('configs/config.php');
    $db = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME
    )or die('Failed to connect.');
    $query = "SELECT DISTINCT(question) FROM survey_questions, campaigns
    WHERE campaigns.table_name = ?
    AND survey_questions.campaignid = campaigns.campaignid
    ORDER BY question;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $cmp);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($question);
    $survey_questions = array();
    while ($stmt->fetch()) {
       array_push($survey_questions, $question);
    }
    return($survey_questions);
}
?>
