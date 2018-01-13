<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Help</title>
    <?php include 'includes/head.php'; ?>
 <script>
  $(function () {
    $('#myList a:last-child').tab('show')
  })
    
$('#myList a').on('click', function (e) {
  e.preventDefault()
  $(this).tab('show')
})

</script>
   
</head>
<body>
	<?php 
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>

	<div id = "page-header0">
		<div class = 'spacer'></div>
		<div class = 'container' id = 'white-container-medium'>
            <div class = 'row'>
                <h2>Help</h2>
            </div>
            
            <!-- TOC -->
            <div class = 'row'>
                <h3>Table of Contents</h3>
                <div class = 'list-group col-xs-8' id = 'toc'>
                    <a href = '#create_acount' class = 'list-group-item list-group-item-action'>Create Account</a>
                    <a href = '#new_campaign' class = 'list-group-item list-group-item-action'>Start a New Campaign</a>
                    <a href = '#add_user' class = 'list-group-item list-group-item-action'>Add and Remove Users from Campaign</a>
                    <a href = '#forgot_password' class = 'list-group-item list-group-item-action'>Forgot Password</a>
                    <a href = '#create_list' class = 'list-group-item list-group-item-action'>Create List of Voters</a>
                    <a href = '#export_list' class = 'list-group-item list-group-item-action'>Export List</a>
                    <a href = '#manage_questions' class = 'list-group-item list-group-item-action'>Add and Remove Questions</a>
                    <a href = '#import_responses' class = 'list-group-item list-group-item-action'>Import Survey Responses</a>
                    <a href = '#quick_search' class = 'list-group-item list-group-item-action'>Quick Search Voters</a>
                    <a href = '#view_responses' class = 'list-group-item list-group-item-action'>View Survey Responses</a>
                    <a href = '#manage_users' class = 'list-group-item list-group-item-action'>Manage User Positions</a>
                    <a href = '#account_settings' class = 'list-group-item list-group-item-action'>Account Settings</a>
                </div> <!-- End of list group -->
            </div> <!-- End of TOC
            
            <!-- Create Account -->
            <hr /> <!-- Section dividing line -->
            <div class = 'row help-section' id = 'create_acount'>
                <h3>Create Account</h3>
                <p>Anyone can create an account, even before you are part of a campaign (although you will not be able to do anything if you are not part of a campaign). To create an account, <a href ='new_user.php'>click here.</a> You will be prompted to give your name, email, and create a secure password.</p>
            </div> <!-- End of create account -->
            
            <!-- Start Campaign -->
            <hr />
            <div class = 'row help-section' id = 'new_campaign'>
                <h3>Start a New Campaign</h3>
                <p>Underdog Databases is currently in beta. If you are interested in using Underdog for your campaign or organization, please <a href = 'new_campaign.php'>contact us!</a></p>
            </div> <!-- End of start campaign -->
            
            <!-- Add and Remove Users from Campaign -->
            <hr />
            <div class = 'row help-section' id = 'add_user'>
                <h3>Add and Remove Users from Campaign</h3>
                <p>To add a user, you must be signed in to Underdog. Then, go to <a href='add_remove_users.php'>the page to add and remove users.</a> To add a user, go to the Add User section and type in the new user's email. NOTE: this person must have already created an account with Underdog. If not, they should create an account now.</p>
                <p>To remove a user, <a href = 'add_remove_users.php'>go to the same page.</a> In the Remove User section, type in the email of the user you want to remove from the campaign. This will mean the user no longer has access to your campaign. It will not delete their Underdog account.</p>
            </div> <!-- End add/remove users -->
            
            <!-- Forgot password -->
            <hr />
            <div class = 'row help-section' id = 'forgot_password'>
                <h3>Forgot Password</h3>
                <p>If you forgot your password, you can <a href ='forgot_password.php'>reset it here.</a> After confirming your information, you will get an email detailing how to reset it.</p>
            </div> <!-- End forgot password -->
            
            <!-- Create list -->
            <hr />
            <div class = 'row help-section' id = 'create_list'>
                <h3>Create a List of Voters</h3>
                <p>Creating a list of voters is meant to help campaigns and organizations make call lists and lists for canvassing. To create a list, you must be logged in. Then, go to <a href='make_list.php'>create a list.</a> There are a number of different fields you can use in your search. For example, zip code, age, and political party. When you select options in the same field, like two zip codes, the search will look for voters that fit at least one option. When you select options in different fields, the search will look voters that fit all of the options. For example, selecting zip codes '00001' and '00002' and party 'DEMOCRAT' will return a list of voters who live in either zip code and are Democrats.</p>
            </div> <!-- End of create list -->

            <!-- Export list -->
            <hr />
            <div class = 'row help-section' id = 'export_list'>
                <h3>Export List</h3>
                <p>To export a list, create a list and then click the export list button at the top of the page. You will download a spreadsheet with everyone in the list you made, not just the 75 voters the page displays. To collect survey responses, add columns to the end of the spreadsheet and fill in the responses.</p>
            </div> <!-- End of export list -->
            
            <!-- Manage Questions -->
            <hr />
            <div class = 'row help-section' id = 'manage_questions'>
                <h3>Add and Remove Questions</h3>
                <p>Survey questions are designed to add organization to voter outreach by creating a list of questions your campaigns asks voters. To add a question, go to the <a href = 'create_questions.php'>add and remove questions page.</a> It will display a list of current questions and give you the option to add and remove questions. Simply type your new question or the question you want to remove into the form.</p>
            </div> <!-- End of manage questions -->     
            
            <!-- Import Responses -->
            <hr />
            <div class = 'row help-section' id = 'import_responses'>
                <h3>Import Survey Responses</h3>
                <p>Only field organizers and above are able to import lists. To import a list, you must be logged in. It is recommended to use a list that was exported from Underdog, but not necessary. To import a list, you must have a .csv file. One of the columns must contain the unique voter ID the government assigns voters. When importing a list, delete the header row of the spreadsheet. To import a list, go to <a href='import_list.php'>the import list page.</a> Fill in which column the voter ID is in (where the first column is Column 1), the number of survey questions in the file, and what number the first question is in. The questions must be in sequential columns. Then, select the questions you asked. If you need to add new survey questions to Underdog, <a href='#manage_questions'> do so.</a></p>
                <p>Importing lists is a developing tool. If you have trouble, please do not hesitate to <a href = 'contact_us'>contact us.</a></p>
            </div> <!-- End of import responses -->
            
            <!-- Quick Search -->
            <hr />
            <div class = 'row help-section' id = 'quick_search'>
                <h3>Quick Search Voters</h3>
                <p>Quick search is designed to be a way to find information on a specific voter. For example, while canvassing you meet someone interested in volunteering but forgot to get their number. In the future, you will also be able to change contact information for a voter.</p>
            </div> <!-- End quick search -->
            
            <!-- View Survey Responses -->
            <hr />
            <div class = 'row help-section' id = 'view_responses'>
                <h3>View Survey Responses</h3>
                <p>The <a href ='survey_results.php'>survey responses</a> page allows you to get a quick breakdown of how voters have answered your questions. Choose a question from the dropdown menu, click the select button, and the results will come up.</p>
            </div>
            
            <!-- Manage User Positions -->
            <hr />
            <div class = 'row help-section' id = 'manage_users'>
                <h3>Manage User Positions</h3>
                <p>To manage the roles your campaign team members have, go to the <a href='manage_users.php'>manage user positions page.</a> This page also shows you your full campaign team and everyones' positions. You can manage the role of anyone who has a lower position than you do. You can adjust their role below or at the same level as your position. Type in their email and select their new role from the dropdown.</p>
            </div> <!-- End manage user positions -->
            
            <!-- Account Settings -->
            <hr />
            <div class = 'row help-section' id = 'account_settings'>
                <h3>Account Settings</h3>
                <p>In <a href='settings.php'>account settings</a> you can adjust the email and password associated with your account.</p>
            </div> <!-- End account settings -->
            
 	   </div>
        <div class = 'spacer'></div> 
    </div>
    <footer>
    </footer>
</body>
</html>