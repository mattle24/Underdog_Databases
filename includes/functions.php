<?php
// from https://www.cloudways.com/blog/import-export-csv-using-php-and-mysql/

if(isset($_POST["Import"])){

	$filename=$_FILES["file"]["tmp_name"];		
	if(!isset($_SESSION['cmp']) || !isset($_SESSION['logged_user'])){
		header('Location: index.php'); // if not set get the user out of here
		exit();
	}
	$cmp = $_SESSION['cmp'];
	if(!isset($_SESSION['questions'])) {
		header('Location: import_list.php');
		// if questions aren't set, redirect to import list
	}
	$questions = $_SESSION['questions'];
	// make sure the question start and the id column are set
	// todo: id col
	if(!isset($_SESSION['Qstart'])) {
		header('Location: import_list.php');
	}
	$Qstart = $_SESSION['Qstart'];

	// connect to database
	$db = new mysqli(
	DB_HOST, 
	DB_USER, #$_SESSION['logged_user'], 
	DB_PASSWORD, 
	'voter_file'
	)or die('Failed to connect.'); 

	if($_FILES["file"]["size"] > 0) {
		$file = fopen($filename, "r");
		while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
        	// for each question, insert data
        	$ite = 0;
        	foreach ($questions as $q) {
        		// loop through each question
        		$col = $ite + $Qstart; // column of current question
        		$ite = $ite + 1;
        		$query = "INSERT INTO responses (voter_id, question, response, campaign) 
        		VALUES ($getData[$id_col], $q, $getData[$col], $cmp);";
        		$stmt = $db->prepare($query);
        		$stmt -> execute();
        	}	
		}
	fclose($file);	
	}
}	 
else {
	echo "There was an error";
}
?>