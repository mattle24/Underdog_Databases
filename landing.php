<?php session_start();
include 'includes/check_logged_in.php';
// reset the cookie each time the user comes to the landing
setcookie('logged_user', $_SESSION['logged_user'], time() + 60 * 60);
// check for the post campaign variable
if (isset($_POST['choose_cmp'])) {
  $_SESSION['cmp'] = filter_input(INPUT_POST, 'choose_cmp', FILTER_SANITIZE_STRING);
}
?>
<!DOCTYPE html>
<html>
<head>
   <title>Toolbox</title>
   <!-- Source Sans Pro font -->
    <link href="https://fonts.googleapis.com/css?family=Rubik" rel="stylesheet">
   <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
   <link rel='stylesheet' type='text/css' href="styles/all.css">
   <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
   <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
<?php
include 'includes/navbar_loggedin.php';
?>
  <div id = 'page-header1'>
    <div class = 'spacer'></div>
    <div id = 'landing-container'>
        <h2 align = 'center'>Toolbox</h2>
        <ul style = 'list-style-type:none'>
            <li><a href = 'make_list.php'>Create List of Voters</a></li>
            <li><a href = 'search.php'>Quick Search Voters</a></li>
            <li><a href = 'import_list.php'>Import Responses</a></li>
            <li><a href = 'create_questions.php'>Add or Remove Questions</a></li>
            <li><a href = 'survey_results.php'>View Survey Results</a></li>
            <li><a href = 'add_remove_users.php'>Add or Remove Users</a></li>
            <li><a href = 'manage_users.php'>Manage Users</a></li>
            <li><a href = 'settings.php'>Settings</a></li>
            <li><a href = 'help'></a>Help</li>
            <li><a href = 'contact'></a>Contact</li>
        </ul>
    </div>
</div>
<footer>
</footer>
</body>
</html>