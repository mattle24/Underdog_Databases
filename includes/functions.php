<?php session_start();
// from https://www.cloudways.com/blog/import-export-csv-using-php-and-mysql/

echo "I'm here!";
if(isset($_POST["Import"])){
	echo "Import was set!";
	$filename=$_FILES["file"]["tmp_name"];		
	if(!isset($_SESSION['cmp']) || !isset($_SESSION['logged_user'])){
		header('Location: choose_campaign.php'); // if not set get the user out of here
		exit();
	}
	$cmp = $_SESSION['cmp'];
	if(!isset($_SESSION['questions'])) {
		header('Location: import_list.php');
		// if questions aren't set, redirect to import list
	}
	$questions = $_SESSION['questions'];
	echo(gettype($questions));
	// make sure the question start and the id column are set
	// todo: id col
	if(!isset($_SESSION['Qstart']) || !isset($_SESSION['id_col'])) {
		header('Location: import_list.php');
	}
	$Qstart = $_SESSION['Qstart'];
	$id_col = $_SESSION['id_col'];

	// connect to database
	$db = new mysqli(
	DB_HOST, 
	DB_USER, #$_SESSION['logged_user'], 
	DB_PASSWORD, 
	'voter_file'
	)or die('Failed to connect.'); 
	echo "Connected to database!";

	try {
		echo "In try block";
		if($_FILES["file"]["size"] > 0) {
			echo "File size greater than 0";
			if (($handle = fopen($filename, "r")) !== FALSE) {
				while (($getData = fgetcsv($handle, 1000, ",")) !== FALSE) {
					echo "Data obtained";
		        	// for each question, insert data
		        	$col = 0; // init col as zero from Qstart
		        	foreach ($questions as $q) {
		        		// loop through each question
		        		echo "Loop started";
		        		$col = $col + $Qstart; // column of current question
		        		$col = $col + 1; // for the next question
		        		$query = "INSERT INTO responses (voter_id, question, response, campaign) VALUES (?, ?, ?, ?);";
		        		$stmt = $db->prepare($query);
						if ( !$stmt ) {
						echo $db->;
						die;
						}
						echo "Statment prepared.";
		        		$b = $stmt->bind_param('isss', (int)$getData[$id_col], $q, $getData[$col], $cmp);
						if ( !$b ) {
						printf('errno: %d, error: %s', $stmt->errno, $stmt->error);
						}

		        		echo "Statement prepared";
		        		$stmt->execute();
		        		echo "Data was uploaded.";
			        }
			    }
				fclose($handle);
			}	
		}
		else {echo "File size was 0.";}
		// echo "<script type=\"text/javascript\">
		// alert(\"CSV File has been successfully Imported.\");
		// window.location = \"../import_list.php\"
		// </script>";
	}
	catch (Exception $e) {
		echo "Error. Caught except $e.";
		// echo "<script type=\"text/javascript\">
		// alert(\"Invalid File:Please Upload CSV File.\");
		// window.location = \"import_list.php\"
		// </script>";		
	}
}	 
else {
	echo "There was an error";
}
?>