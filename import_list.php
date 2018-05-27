<?php
session_start();
include "includes/check_logged_in.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import</title>
    <?php include_once "includes/head.php"; ?>


    <script>
    // GOALS:
    // - Validate selection (no duplicates, voter ID selected, at least one other data field)
    // - When the form is validate, allow the user to click on the submit button (it should
    //   be disabled before).
    // - When the user clicks on the submit button it should upload the responses given
    //   the data fields matched to columns.

    // How will this last part work?
    // The file is stored in temp memorary, and will not be available on the next page.


    loadDoc("url-1", myFunction1); // Validate form

    loadDoc("url-2", myFunction2); // Upload file

    function loadDoc(url, cFunction) {
     var xhttp;
     xhttp=new XMLHttpRequest();
     xhttp.onreadystatechange = function() {
       if (this.readyState == 4 && this.status == 200) {
         cFunction(this);
       }
    };
     xhttp.open("GET", url, true);
     xhttp.send();
    }

    function myFunction1(xhttp) {
     // Validate form
     // get the form input
     var  formInput = document.forms['upload_csv'];

     // Go through each input. Check to make sure it is not a duplicate
     // by validating against an array of previous inputs.
     // If it is a duplicate, return false. Else, return true.
     // This is going to be implemented as O(n^2), but n should always be small
     // Since there should never be a large number of columns
     // Also, the user needs to select a voter id column

     var inputs = [];
     var voterIdSelected = false;
     formInput.foreach(function(element) {
         // Check if voter Id Selected
         // or if the current element is not a selected data field (user did
         // select as data to import)
         if (element.value == "voter_id") { voterIdSelected = true; }
         else if (element.value == -1) { continue; }
         // voter id and non-null should follow the next steps

         if (inputs.length == 0) {
             // if this is the first element in the form, then add it the
             // validation array
             inputs.push(element.value)
         } else {
             // check this element against the previous elements for duplicates
             input.foreach(function(inputsElement) {
                 if (element.value == inputsElement.value) {
                     return (false);
                 }
                 // if it doesn't equal something we've already seen then
                 // just continue on
             });
         }
         // If we get here then nothing was equal to anything else
         // There should be a voterIdSelected
         // There should be at least two data fields selected (the ID and one other field)
         if (voterIdSelected && input.length >= 2) { return (true); }
         return (false);
     });
    }
    function myFunction2(xhttp) {
     // action goes here
    }
    </script>

    <script>
    // Check that the user did not select the same data field for multiple columns
    // and check that voter ID is selected and check that at least one other data
    // field is selected

    // There are many ways to pick a DOM node; here we get the form itself and the email
    // input box, as well as the span element into which we will place the error message.

    var form = document.getElementById('upload_csv');

    form.addEventListener("submit", function (event) {
      // Each time the user tries to send the data, we check
      // if the email field is valid.
      if (!validateColumns()) {

        // If the field is not valid, we display a custom
        // error message.
        // error.innerHTML = "Make sure to select a column for voter ID and at least one other data field.";
        // And we prevent the form from being sent by canceling the event
        event.preventDefault();
      }
    }, false);

    function validateColumns() {
        return (false);
        // get the form input
        var  formInput = document.forms['upload_csv'];

        // Go through each input. Check to make sure it is not a duplicate
        // by validating against an array of previous inputs.
        // If it is a duplicate, return false. Else, return true.
        // This is going to be implemented as O(n^2), but n should always be small
        // Since there should never be a large number of columns
        // Also, the user needs to select a voter id column

        var inputs = [];
        var voterIdSelected = false;
        formInput.foreach(function(element) {
            // Check if voter Id Selected
            // or if the current element is not a selected data field (user did
            // select as data to import)
            if (element.value == "voter_id") { voterIdSelected = true; }
            else if (element.value == -1) { continue; }
            // voter id and non-null should follow the next steps

            if (inputs.length == 0) {
                // if this is the first element in the form, then add it the
                // validation array
                inputs.push(element.value)
            } else {
                // check this element against the previous elements for duplicates
                input.foreach(function(inputsElement) {
                    if (element.value == inputsElement.value) {
                        return (false);
                    }
                    // if it doesn't equal something we've already seen then
                    // just continue on
                });
            }
            // If we get here then nothing was equal to anything else
            // There should be a voterIdSelected
            // There should be at least two data fields selected (the ID and one other field)
            if (voterIdSelected && input.length >= 2) { return (true); }
            return (false);
        });
    }
    </script>

</head>
<body>
    <?php
    if (!isset($_SESSION['cmp'])){
        $msg = "Error. Please choose your campaign before importing a list.";
        header("Location: choose_campaign.php?msg=$msg");
    }
    include_once 'includes/navbar_loggedin.php';
    ?>
    <div id = 'page-header1'>
        <div class = 'spacer'></div>
        <div id = 'white-container-medium'>
            <div class="row">

                <?php
                if (isset($_POST["Import"])) {
                    // echo "Import was set!";
					$filename=$_FILES["file"]["tmp_name"];
					$cmp = $_SESSION['cmp'];
					// Make sure there's data in the file
					if ($_FILES['file']['size'] <= 0) {
						$msg = 'This is an invalid file.';
						header("Location: ../import_list.php?msg=$msg");
					}

					// TODO: validate file extension

					try {
						// echo "In try block";
						$file = fopen($filename, 'r');
						$max_rows = 10;
						$curr_row = 0; // current row
						// Echo table header //
						// But for that I need to get the number of columns
						// Solution: read one line.
						$line = fgetcsv($file);
						$numCols = count($line);

						// Get the possible survey responses
						include_once 'includes/questions_in_array.php';
						$questions = get_questions($cmp);

						// Instructions
                        ?>
						<div class = 'row'>
							<h3>Import Survey Responses</h3>
							<p>Use the dropdown menus to select the columns that contain
							your survey responses and the voter id. You must select the
							column that contains the voter id and at least one question.
							You may not select two columns that contain responses to the
							same question.</p>
						</div>
						<div class = 'table-responsive'>
                            <!-- form for question-column pairing -->
    						<form id = 'upload_csv' name = 'upload_csv' action ='import_list.php' method='post' onsubmit='return validateForm()'>";
    						 	<table class = 'table'>
    								<thead id = 'QLHead'>
    									<tr>
    						                <!-- Each column header will be a dropdown menu -->
                                            <?php
                    						for ($i = 0; $i < $numCols; $i++) {
                                                // for each option, use column
                                                // number '~' data field so
                                                // that I can get where the column is
                                                // and what field it should be
                    							echo "<th scope = 'col'>";
                    							echo "<select class='selectpicker' name = 'questions[]'>";
                    							echo "<option value = -1>Column $i</option>";
                    							echo "<option value = '$i ~ voter_id'>Voter ID</option>";
                    							foreach ($questions as $q) {
                    								echo "<option value = '$i ~ $q'>$q</option>";
                    							}
                    							echo "</select>";
                    							echo "</th>";
                    						} ?>
                                        </tr>
                                    </thead>
						            <tbody id = 'QLbody'> <!-- table body with upload data -->
                                        <?php
                						while ($line = fgetcsv($file) and $curr_row < $max_rows) {
                							$curr_row = $curr_row + 1; // increment
                							// echo the data
                							echo "<tr>";
                							for ($i = 0; $i < $numCols; $i++) {
                								$data = $line[$i];
                								echo "<td>$data</td>";
                							}
                							echo "</tr>";
                						} ?>
						            </tbody> <!-- end data -->
                                </table> <!-- end table -->
                            </div> <!-- end responsive table div -->
						    <button type = 'submit' name = 'upload_csv' class = 'btn btn-primary'>Submit</button>
					    </form>
                    <?php
					}
					catch (Exception $e) {
						$msg = $e->getMessage();
						echo "Error. Caught except $msg.";
					}
				}
				elseif (isset($_POST["upload_csv"])) {
					// Case when the user has selected valid columns
                    $filename=$_FILES["file"]["tmp_name"];
                    $cmp = $_SESSION['cmp'];

                    // Get question and column positions in two separate arrays
                    $questions = array();
                    $Colpositions = array();
                    $userInput = $_POST['upload_csv'];
                    foreach($userInput as $ite) {
                        $pieces = explode("~", $ite);
                        if (trim($pieces[1]) == "voter_id") {
                            $voterIdCol = (int)trim($pieces[0]);
                        } else {
                        array_push($Colpositions, (int)trim($pieces[0])); // add col position, cast to int
                        array_push($questions, trim($pieces[1])); // add question, remove whitespace
                        }
                    }

                    try {
                		// echo "In try block";
                		if($_FILES["file"]["size"] > 0) {
                			// echo "File size greater than 0";
                			include_once ('configs/config.php');
                			$db = new mysqli(
                			DB_HOST,
                			DB_USER,
                			DB_PASSWORD,
                			DB_NAME
                			)or die('Failed to connect.');
                			if (($handle = fopen($filename, "r")) !== FALSE) {
                				while (($getData = fgetcsv($handle, 1000, ",")) !== FALSE) {
                					// echo "Data obtained. ";
                		        	// for each question, insert data
                                    $voter_id = $getData[$id_col];
                                    for ($i = 0; $i < count($questions); $i ++) {
                                        // loop through each question
                                        $query = "INSERT INTO responses
                		        		(voter_id, question, response, campaign)
                		        		VALUES(?, ?, ?, ?);";
                		        		$stmt = $db->prepare($query);
                						if ( !$stmt ) {
                							echo "Fatal prepare error...";
                							printf("Error: %s.\n", $stmt->error);
                							die;
                						}

                                        // now bind question and response to query
                                        $bind = $stmt->bind_param('ssss', $voter_id, $questions[$i], $getData[$Colpositions[$i]], $cmp);
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
                		else { echo "File size was 0."; }
                		echo "<script type=\'text/javascript\'>
                		alert(\'CSV File has been successfully Imported.\';
                		window.location = \'../landing.php\'
                		</script>";
                	}
                	catch (Exception $e) {
                		echo "Error. Caught except $e.";
                		echo "<script type=\'text/javascript\'>
                		alert(\'Invalid File:Please Upload CSV File.\');
                		window.location = \'import_list.php\'
                		</script>";
                	}
				}
				else {
                    ?>
                    <form class='form-horizontal' action='import_list.php' method='post' name='Import' enctype='multipart/form-data'>
                        <fieldset>

                            <!-- Form Name -->
                            <legend>Form Name</legend>

                            <!-- File Button -->
                            <div class='form-group'>
                                <label class='col-md-4 control-label' for='filebutton'>Select File</label>
                                <div class='col-md-4'>
                                    <input type='file' name='file' id='file' class='input-large'>
                                </div>
                            </div>

                            <!-- Submit utton -->
                            <div class='form-group'>
                            </form>
                                <label class='col-md-4 control-label' for='singlebutton'>Import data</label>
                                <div class='col-md-4'>
                                    <button type='submit' id='submit' name='Import' class='btn btn-primary button-loading' data-loading-text='Loading...'>Import</button>
                                </div>
                            </div>

                        </fieldset>
                    </form>
                        <?php
                }
				?>

            </div> <!-- end row -->
        </div>
        <div class = 'spacer'> </div>
    </div>
    <footer></footer>
</body>
</html>
