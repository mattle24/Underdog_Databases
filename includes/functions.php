<?php session_start();
// from https://www.cloudways.com/blog/import-export-csv-using-php-and-mysql/

if(isset($_POST["Import"])){
	// echo "Import was set!";
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
	// make sure the question start and the id column are set
	// todo: id col
	if(!isset($_SESSION['Qstart']) || !isset($_SESSION['id_col'])) {
		header('Location: import_list.php');
	}
	$Qstart = $_SESSION['Qstart'];
	$id_col = $_SESSION['id_col'];

	// connect to database

	try {
		// echo "In try block";
		if($_FILES["file"]["size"] > 0) {
			// echo "File size greater than 0";
			include('../configs/config.php');
			$db = new mysqli(
			DB_HOST, 
			DB_USER, #$_SESSION['logged_user'], 
			DB_PASSWORD, 
			'voter_file'
			)or die('Failed to connect.'); 
			// echo "Connected to database!";
			if (($handle = fopen($filename, "r")) !== FALSE) {
				while (($getData = fgetcsv($handle, 1000, ",")) !== FALSE) {
					// echo "Data obtained. ";
		        	// for each question, insert data
		        	$col = -1; // init col to be zero from Qstart during first loop
		        	foreach ($questions as $q) {
		        		// loop through each question
		        		// echo "Loop started";
		        		$col = $col + 1; // for the next question
				        // $query = "SELECT DISTINCT(question) FROM responses;";
				        // printf($db->error);
				        // $stmt = $db->prepare($query);
				        // printf($db->error);
				        // $stmt->execute();
				        // // printf($db->error);
		        		// echo "The test worked";
		        		$query = "INSERT INTO responses 
		        		(voter_id, question, response, campaign) 
		        		VALUES(?, ?, ?, ?);";
		        		$stmt = $db->prepare($query);
						if ( !$stmt ) {
							echo "Fatal prepare error...";
							printf("Error: %s.\n", $stmt->error);
							die;
						}
						// echo "Statment prepared.";
						$voter_id = (int)$getData[$id_col];
						$response = $getData[$col + $Qstart]; // column of current question
		        		$bind = $stmt->bind_param('isss', $voter_id, $q, $response, $cmp);
						if ( !$bind ) {
							echo "Fatal binding error...";
							// printf("Error: %s.\n", $stmt->error);
							die;
						}
		        		// echo "Statement binded";
		        		$stmt->execute();
		        		// echo "Data was uploaded.";   		
			        }
			    }
			    // Close connections
				$db->close();
				fclose($handle);
			}	
		}
		else {echo "File size was 0.";}
		echo "<script type=\"text/javascript\">
		alert(\"CSV File has been successfully Imported.\");
		window.location = \"../landing.php\"
		</script>";
	}
	catch (Exception $e) {
		echo "Error. Caught except $e.";
		echo "<script type=\"text/javascript\">
		alert(\"Invalid File:Please Upload CSV File.\");
		window.location = \"import_list.php\"
		</script>";		
	}
}	 
else {
	echo "There was an error";
}
?>