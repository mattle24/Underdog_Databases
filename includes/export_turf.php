<?php
session_start();
include_once 'includes/check_logged_in.php';
if (!isset($_SESSION['cmp'])) {
    header("Location: choose_campaign.php");
}

// Check if query is set
if (!isset($_POST["data"]))
$obj = json_decode($json);

?>
