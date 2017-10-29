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
    if (!isset($_GET['Qnum'])){
        echo "<form action = 'import_list.php' method = 'get'>
        <input type = 'numeric' name = 'Qnum'>Number of survery questions</input>
        <input type = 'submit'></input>
        </form ";
    } else {
        if (!isset('questions')) {
            echo "<form action = 'import_list.php method = 'post'>";
            for ($num = 0; $num < $_GET['Qnum']; $num ++) {
                echo "<input type = 'text' name = 'questions[]'>Question $num</input>";
            }
            echo "<input type = 'submit></input>
            </form>";
        }
        else {
            $questions = $_POST['questions'];
            $db = new mysqli (
                DB_HOST,
                DB_USER,
                DB_PASSWORD,
                DB_NAME)or die('Failed to connect.'); 
            $cmp = $_SESSION['cmp']; // todo: this won't work because string intepr.      
            $query = "SET autocommit = 0;
            BULK INSERT survey_responses
            WITH (
                
            )
            VALUES(?, ?, ?, ?);
            COMITT;";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ssss', $email, $passwd, $first, $last);
            $stmt->execute();
            BULK INSET
            foreach ($questions as $q){

            }
        }
    }
    ?>
  </div>
</body>
</html>