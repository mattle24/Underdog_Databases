<?php session_start();
include 'includes/check_logged_in.php';


// check for the post campaign variable
if (isset($_POST['choose_cmp'])) {
    // Make sure this is a valid campaign for the user.
    require_once 'configs/config.php';
    $cmp = filter_input(INPUT_POST, 'choose_cmp', FILTER_SANITIZE_STRING);
    $username = $_SESSION['logged_user'];
    include('includes/check_campaign.php');
    if (!checkCampaign($username, $cmp)) {
        // Then the campaign is invalid, redirect to choose campaign
        header("Location: choose_campaign.php");
    }
    else {
        $_SESSION['cmp'] = $cmp;
        header("Location: landing.php");
    }
    // This way, the SESSION variable is set and the user is redirected to the Landing
    // This means that landing.php will be stored in the cache, allowing users to go
    // back after navigating away from the landing.
}

// reset the cookie each time the user comes to the landing
// This helps timing out inactive users.
setcookie('logged_user', $_SESSION['logged_user'], time() + 60 * 60);

// TODO: make sure that user has access to the campaign. Otherwise,
// can spoof with a curl(?) POST input
?>
<!DOCTYPE html>
<html>
<head>
    <title>Toolbox</title>
    <?php include "includes/head.php";
    // prevent document from expiring
    ini_set('session.cache_limiter','public');
    session_cache_limiter(false);
    ?>
</head>
<body>
    <?php include 'includes/navbar_loggedin.php'; ?>
    <div id = 'page-header1' class = 'container-fluid'>
        <div class = 'spacer'></div>

        <div id = 'white-container-medium' class = 'container'> <!-- Content container -->
            <div class = 'row'> <!-- All of the tools -->
                <h2 align = 'center'>Toolbox</h2>
                <div class = 'col-sm-6'> <!-- Voter Tools -->
                    <h4>Voter Outreach</h4>
                    <div class = 'list-group tools' id= 'voter_outreach'>
                        <a href = 'make_list.php'class = 'list-group-item list-group-item-action'>Create List of Voters</a>
                        <a href = 'search.php'class = 'list-group-item list-group-item-action'>Quick Search Voters</a>
                        <a href = 'import_list.php'class = 'list-group-item list-group-item-action'>Import Responses</a>
                        <a href = 'create_questions.php'class = 'list-group-item list-group-item-action'>Add or Remove Questions</a>
                        <a href = 'survey_results.php'class = 'list-group-item list-group-item-action'>View Survey Results</a>
                    </div>
                </div> <!-- End Voter tools -->

                <div class = 'col-sm-6'> <!-- Administrative -->
                    <h4>Admin</h4>
                    <div class = 'list-group tools' id = 'admin'>
                        <a href = 'add_remove_users.php'class = 'list-group-item list-group-item-action'>Add or Remove Users</a>
                        <a href = 'manage_users.php'class = 'list-group-item list-group-item-action'>Manage User Positions</a>
                        <a href = 'settings.php'class = 'list-group-item list-group-item-action'>Account Settings</a>
                        <a href = 'help.php'class = 'list-group-item list-group-item-action'>Help</a>
                        <a href = 'contact_us.php'class = 'list-group-item list-group-item-action'>Contact</a>
                    </div>
                </div> <!-- End of admin -->
            </div> <!-- End of tools -->
        </div> <!-- End of content -->

        <div class = 'spacer'></div>
    </div>
    <footer>
    </footer>
</body>
</html>
