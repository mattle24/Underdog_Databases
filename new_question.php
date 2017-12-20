<?php
session_start();
include 'includes/check_logged_in.php';
if (!isset($_SESSION['cmp'])) {
    header("Location: choose_campaign.php");
}
if (!isset($_POST['new_question'])) {
    header("Location: create_questions.php");
}
$cmp = $_SESSION['cmp'];
require_once('configs/config.php');
$db = new mysqli(
DB_HOST,
DB_USER,
DB_PASSWORD,
DB_NAME) or die('Failed to connect.');

// Get the campaign id
$query = "SELECT campaignid FROM campaigns WHERE table_name = ?;";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $cmp);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($cmpid);
$stmt->fetch();

// Insert new question and campaign id into survey_questions
$new_question = trim(filter_input(INPUT_POST, 'new_question', FILTER_SANITIZE_STRING));
$query = "INSERT INTO survey_questions (campaignid, question)
VALUES(?, ?);";
$stmt = $db->prepare($query);
$stmt->bind_param('is', $cmpid, $new_question);
$stmt->execute();
if ($stmt) {
    echo "B";
    header("Location: create_questions.php");
} else {
    echo "Failed.";
}
?>