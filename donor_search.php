<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
   <title>Search Donors</title>
   <?php include "includes/head.php"; ?>
</head>
<body>
    <?php
    include 'includes/navbar_loggedin.php';
    if (!isset($_SESSION['cmp'])) {
        $msg = "Error. Please select your campaign before searching.";
        header("Location: choose_campaign.php?msg=$msg");
    }
    ?>
    <div id = 'page-header1'>
        <div class="spacer"></div>
        <div id = 'white-container-medium'>
            <div class = 'row'>
                <h2>Search Donors</h2>
                <p align = 'left'>Use one or more of the search terms below.
                    Searches are conjuctive. Using multiple search terms will return only donors who meet all of the requirements.</p>
            </div>

            <div class = 'row'> <!-- Start form -->
                <form action = "donor_search_results.php" method = "post">
                    <div class = 'row'>
                        <p><strong>Choose Search Type(s):</strong></p>
                    </div>

                    <div class = 'row'> <!-- DonorID  -->
                        <div class = 'form-group col-sm-6'> <!-- Donor ID -->
                            <label for = "formDID">Donor ID</label>
                            <input id = 'formDID' class = 'form-control' type = 'text' name = 'donorID'/>
                        </div>
                    </div> <!-- End Donor ID -->

                    <div class = 'row'> <!-- First and last name -->
                        <div class = 'form-group col-sm-6'> <!-- First -->
                            <label for = 'formFirst'>First name</label>
                            <input id = 'firmFirst' class = 'form-control' type = 'text' name = 'searchfirst'/>
                        </div>
                        <div class = 'form-group col-sm-6'> <!-- Last -->
                            <label for = 'formLast'>Last name: </label>
                            <input id = 'formLast' class = 'form-control' type = 'text' name = 'searchlast'/>
                        </div>
                    </div> <!-- End name -->

                    <div class = 'row'> <!-- Location -->
                        <div class = 'form-group col-sm-6'>
                            <label for = 'formCity'>City</label>
                            <input id = 'formCity' class = 'form-control' type = 'searchterm' name = 'searchcity'>
                        </div>
                        <div class = 'form-group col-sm-6'>
                            <label for = 'formZip'>Zip Code</label>
                            <input id = 'formZip' class = 'form-control' type = 'searchterm' name = 'searchzip'>
                        </div>
                        <div class = 'form-group col-sm-6'>
                            <label for = 'formSt'>Street Name</label>
                            <input id = 'formSt' class = 'form-control' type = 'searchterm' name = 'searchstreet'>
                        </div>
                        <div class = 'form-group col-sm-6'>
                            <label for = 'formNum'>Street Number</label>
                            <input id = 'formNum' class = 'form-control' type="searchterm" name="searchnumber">
                        </div>
                    </div> <!-- End Location -->

                    <div class = 'row'>
                        <button class = 'btn btn-primary' type = 'submit'>Search</button>
                    </div>
                </form>
            </div> <!-- End form -->

        </div> <!-- End of white container -->
        <div class = 'spacer'></div>
    </div> <!-- End of header -->
<footer>
</footer>
</body>
</html>
