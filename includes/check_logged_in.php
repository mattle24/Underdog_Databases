<?php
// Check if the cookie has expired
if(!isset($_COOKIE['logged_user'])) {
	$reason = "Your session has expired due to inactivity.";
	header("Location: ../logout.php?reason=$reason");
}
// Check if Session variable is set
if(!isset($_SESSION['logged_user'])) {
	header('Location: ../logout.php');
}
// Check if the Session variable matches the cookie
// variable
if($_SESSION['logged_user'] != $_COOKIE['logged_user']) {
	header('Location: ../logout.php');
}

// Delete temporary stored survey response uploads that have taken too long
// since check_logged_in is always called, I will just piggyback on this

// if (isset($_SESSION["survey_responses_upload_name"])) {
// 	// validate cookie is still active
// 	if (!isset($_COOKIE["uploaded_file"])) {
// 		// Delete temp files
// 		unlink($_SESSION["survey_responses_upload_name"]);
// 		unset($_SESSION["survey_responses_upload_name"]);
// 	}
// }
?>
