<?php session_start();?>

<!DOCTYPE html>
<html>
<head>
   <title>List Results</title>
   <!-- Source Sans Pro font -->
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
   <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
   <link rel='stylesheet' type='text/css' href="styles/all.css">
   <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
   <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body id = 'make-list-body'>
  <?php
  if (!isset($_SESSION['logged_user'])){header('Location: index.php');}
  include 'includes/navbar_loggedin.php';
  ?>
  <div id = 'make-list-container'>
    <?php
    // Ask user how many survery questions she wants to enter
    echo "<a href = 'import_list.php'><button type = 'button'>Reset</button></a>";
    if (!isset($_POST['Qnum']) && !isset($_POST['questions'])){
        echo "<form action = 'import_list.php' method = 'post'>
        <label>Number of survey questions</label><input type = 'numeric' name = 'Qnum' required></input><br />
        <label>What number column is the voter ID in?</label><input type = 'numeric' name = 'id_col' required></input><br />
        <label>What number column in the first question in?</label><input type = 'numeric' name = 'Qstart' required></input><br />
        <input type = 'submit'></input>
        </form ";
    } else {
        if (!isset($_POST['questions'])) {
            $_SESSION['Qnum'] = filter_input(INPUT_POST, 'Qnum', FILTER_SANITIZE_STRING);
            $_SESSION['Qstart'] = filter_input(INPUT_POST, 'Qstart', FILTER_SANITIZE_STRING);
            $_SESSION['id_col'] = filter_input(INPUT_POST, 'id_col', FILTER_SANITIZE_STRING);
            echo "<form action = 'import_list.php' method = 'post'>";
            for ($num = 0; $num < $_SESSION['Qnum']; $num ++) {
                $index_1 = $num + 1; //  because no one wants question 0
                echo "<label>Question $index_1</label>
                <input type = 'text' name = 'questions[]'></input> <br>";
            }
            echo "<input type = 'submit'></input>
            </form>";
        }
        else {
            $_SESSION['questions'] = filter_input(INPUT_POST, 'questions', FILTER_SANITIZE_STRING);
            echo "<form action='includes/functions.php' method = 'post' name = 'upload_csv' enctype = 'multipart/form-data'>
                    <label>Select File</label>
                    <input type = 'file' name = 'file' id = 'file'>
                    <label>Import Data</label>
                    <button type='submit' id='submit' name='Import' class='btn btn-primary button-loading' data-loading-text='Loading...'>Import</button>
                    </form>";
            get_all_records(); // custom function from https://www.cloudways.com/blog/import-export-csv-using-php-and-mysql/


            // $db = new mysqli (
            //     DB_HOST,
            //     DB_USER,
            //     DB_PASSWORD,
            //     DB_NAME)or die('Failed to connect.'); 
            // $cmp = $_SESSION['cmp']; \
            // $query = "SET autocommit = 0;
            // BULK INSERT survey_responses
            // WITH (
                
            // )
            // VALUES(?, ?, ?, ?);
            // COMITT;";
            // $stmt = $db->prepare($query);
            // $stmt->bind_param('ssss', $email, $passwd, $first, $last);
            // $stmt->execute();
            // BULK INSET
            // foreach ($questions as $q){
            // }
        }
    }
    ?>
  </div>
</body>
</html>