<?php
session_start();
include "includes/check_logged_in.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Import</title>
    <?php include_once "includes/head.php"; ?>
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
                // TODO: error handling
                // - set a max file size
                // - other things?

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
						// Solution: read one line. --> what about foreach? BUG
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
                            <!-- form for qquestionuestion-column pairing -->
    						<form id = 'upload_csv' name = 'upload_csv' action ='import_list.php' method='post' onsubmit='return validateForm()'>
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
                    							echo "<option>Column $i</option>";
                    							echo "<option name = '$i' value = 'voter_id'>Voter ID</option>";
                    							foreach ($questions as $q) {
                    								echo "<option name = '$i' value = '$q'>$q</option>";
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
                        // Now that the table is there for the user, move the file to
                        // temporary storage so we can access it when this form is complete.
                        // Storage name is partly random so multiple files can be uploaded at once.
                        $storage_name = "survey_responses".strval(rand()).".csv";
                        $_SESSION["survey_responses_upload_name"] = $storage_name;
                        // Give user three minutes to fill out column form
                        setcookie('uploaded_file', $storage_name, time() + 180);
                        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/".$storage_name);
					}
					catch (Exception $e) {
						$msg = $e->getMessage();
						echo "Error. Caught except $msg.";
					}
				}
				elseif (isset($_POST["questions"])) {
                    // TODO: user $_POST["header_check"] to know whether or not
                    // to skip the first row (some logic will have to be applied above)

					// Case when the user has selected valid columns
                    if (!isset($_SESSION["survey_responses_upload_name"])) {
                        $msg = "No valid file found. File may have timed out.";
                        header("Location: import_list.php?msg=$msg");
                    }
                    $filename= "upload/".$_SESSION["survey_responses_upload_name"];
                    $cmp = $_SESSION['cmp'];

                    // Get question and column positions in two separate arrays
                    $questions = array();
                    $colPositions = array();
                    $userInput = $_POST['questions'];
                    for ($i = 0; $i < sizeof($userInput); $i ++) {
                        $current = trim($userInput[$i]);
                        if ($current == "voter_id") {
                            $voterIdCol = $i;
                            // var_dump($current);
                        }
                        elseif ($current != "Column $i") {
                            // var_dump($current);
                            array_push($colPositions, $i);
                            array_push($questions, $current);
                        }
                    }

                    try {
                		// echo "In try block";
            			// echo "File size greater than 0";
            			include_once 'configs/config.php';
            			$db = new mysqli(
            			DB_HOST,
            			DB_USER,
            			DB_PASSWORD,
            			DB_NAME
            			)or die('Failed to connect.');

                        // Get campaign number
                        include_once 'includes/functions.php';
                        $cmp_number = getCampaignNumber($cmp);

            			if (($handle = fopen($filename, "r")) !== FALSE) {
            				while (($getData = fgetcsv($handle, 1000, ",")) !== FALSE) {
            					// echo "Data obtained. ";
            		        	// for each question, insert data
                                $voter_id = $getData[$voterIdCol];
                                for ($i = 0; $i < sizeof($questions); $i ++) {
                                    // loop through each question
                                    // TODO: make this a bulk insert by just appending
                                    // one very long query
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
                                    $bind = $stmt->bind_param('ssss', $voter_id, $questions[$i], $getData[$colPositions[$i]], $cmp_number);
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
                        // TODO: fix success message, then delete temp file
                		echo "<script type=\'text/javascript\'>
                		alert(\'CSV File has been successfully Imported.\';
                		window.location = \'../import_list.php\'
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
                            <h2>Upload Survey Responses</h2>

                            <?php
                            if (isset($_GET["msg"])) {
                                $msg = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING);
                                echo "<p class ='alert alert-danger' role='alert'>$msg</p>";
                            }
                            ?>

                            <!-- File Button -->
                            <div class='form-group'>
                                <label class='col-md-4 control-label' for='filebutton'>Select File</label>
                                <div class='col-md-4'>
                                    <input type='file' name='file' id='file' class='input-large'>
                                </div>
                            </div>

                            <!--- Header -->
                            <div class = "form-group">
                                <label class='col-md-4 control-label' for='headerCheck'>Does the file have a header? (File cannot have more than one header line)</label>
                                <div class='col-md-4'>
                                    <input type='checkbox' name='headerCheck' id='headerCheck' class='form-check-input' value = 1>Yes</input>
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

    <script>
    // GOALS:
    // - Validate selection (no duplicates, voter ID selected, at least one other data field)
    // - When the form is validate, allow the user to click on the submit button (it should
    //   be disabled before).
    // - When the user clicks on the submit button it should upload the responses given
    //   the data fields matched to columns.

    // How will this last part work?
    // When the user clicks on the form, JS will assess whether the input is valid
    // If not, it will return false and a warning message.
    // If it is valid, then it will make an AJAX request to upload_responses.php
    function uploadResponses() {
        // get the form input
        var  formInput = document.forms['upload_csv'];
        // Go through each input. Check to make sure it is not a duplicate
        // by validating against an array of previous inputs.
        // If it is a duplicate, return false. Else, return true.
        // This is going to be implemented as O(n^2), but n should always be small
        // Since there should never be a large number of columns
        // Also, the user needs to select a voter id column
        var inputs = []; // empty array
        var voterIdSelected = false; // init
        formInput.foreach(function(element) {
            console.log(element);
            // Check if voter Id Selected
            // or if the current element is not a selected data field (user did
            // select as data to import)
            if (element.value == "voter_id") { voterIdSelected = true; }
            else if (element.value == -1) { continue; }
            // voter id and non-null inputs should follow the next steps

            if (inputs.length === 0) {
                // if this is the first element we are assessing, then add it the
                // validation array
                inputs.push(element.value)
            } else if (inputs.includes(inputsElement)) {
                // check this element against the previous elements for duplicates
                return (false);
            }

            // If we get here then nothing was equal to anything else
            // There should be a voterIdSelected
            // There should be at least two data fields selected (the ID and one other field)
            if (!(voterIdSelected && input.length >= 2)) { return (false); }

            // If input is valid, then
            // upload responses
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                document.get
            }
        });

    }

    </script>
</body>
</html>
