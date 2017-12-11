<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
   <title>Import</title>
   <!-- Source Sans Pro font -->
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
   <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
   <link rel='stylesheet' type='text/css' href="styles/all.css">
   <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
   <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body id = 'make-list-body'>
  <?php
  if (!isset($_SESSION['logged_user'])){header('Location: index.php');}
  include 'includes/navbar_loggedin.php';
  ?>
  <div class = 'spacer'><</div>
  <div id = 'make-list-container'>
      <h2>Import Responses</h2>
      <p><a href = 'help.php'>Help</a> on importing voter responses.</p>
    <?php
    // Ask user how many survery questions she wants to enter
    echo "<a href = 'import_list.php'><button type = 'button'>Reset</button></a>";
    if (!isset($_POST['Qnum']) && !isset($_POST['questions'])){
        echo "<form action = 'import_list.php' method = 'post'>
        <label>Number of survey questions</label><input type = 'numeric' name = 'Qnum' required></input><br />
        <label>What number column is the voter ID in?</label><input type = 'numeric' name = 'id_col' required></input><br />
        <label>What number column is the first question in?</label><input type = 'numeric' name = 'Qstart' required></input><br />
        <input type = 'submit'></input>
        </form ";
    } else {
        if (!isset($_POST['questions'])) {
            $_SESSION['Qnum'] = filter_input(INPUT_POST, 'Qnum', FILTER_SANITIZE_NUMBER_INT); 
            $_SESSION['Qstart'] = filter_input(INPUT_POST, 'Qstart', FILTER_SANITIZE_NUMBER_INT) - 1;  // because php uses 0 index
            $_SESSION['id_col'] = filter_input(INPUT_POST, 'id_col', FILTER_SANITIZE_NUMBER_INT) - 1;
            // TODO: change so that each question is a drop down of available questions
            // include('configs/config.php');
   //          $db = new mysqli(
			// DB_HOST, 
			// DB_USER, #$_SESSION['logged_user'], 
			// DB_PASSWORD, 
			// 'voter_file'
			// )or die('Failed to connect.'); 
			// $cmp = $_SESSION['cmp'];
			// $query = "SELECT DISTINCT(question) FROM survey_questions WHERE campaign = ?;";
			// $stmt = $db->prepare($query);
			// $stmt->bind_param('s', $cmp);
			// echo "We made it this far";
			// $stmt->execute();
			// $stmt->store_result();
			// $possible_qs = $stmt->fetch_all();
            echo "<form action = 'import_list.php' method = 'post'>";
            for ($num = 1; $num <= $_SESSION['Qnum']; $num ++) {
                //  because no one wants question 0
                echo "<label>Question $num</label>";
                echo "<input type = 'text' name = 'questions[]'></input> <br>";
            }
            echo "<input type = 'submit'></input>
            </form>";
        }
        else {
            $_SESSION['questions'] = $_POST['questions']; // filter_input_array(INPUT_POST, 'questions');
            // TODO: improve security above
            if (!is_array($_SESSION['questions'])) {
            	session_destroy();
            	header("Location: index.php");
            }
            echo "<form action='includes/functions.php' method = 'post' name = 'upload_csv' enctype = 'multipart/form-data'>
                    <label>Select File</label>
                    <input type = 'file' name = 'file' id = 'file'>
                    <label>Import Data</label>
                    <button type='submit' id='submit' name='Import' class='btn btn-primary button-loading' data-loading-text='Loading...'>Import</button>
                    </form>";
            // get_some_records(); // custom function from https://www.cloudways.com/blog/import-export-csv-using-php-and-mysql/
        }
    }
    ?>
  </div>
</body>
</html>