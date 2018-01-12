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
                    <a href = '#import_list' class = 'list-group-item list-group-item-action'>Import List</a>
                    <a href = '#quick_search' class = 'list-group-item list-group-item-action'>Quick Search Voters</a>
                    <a href = '#create_list' class = 'list-group-item list-group-item-action'>Create List of Voters</a>
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
            <div class = 'row help-section' id = 'forgot_password'>
                <h3>Create a List of Voters</h3>
                <p>Creating a list of voters is meant to help campaigns and organizations make call lists and lists for canvassing. To create a list, you must be logged in. Then, go to <a href='make_list.php'>create a list.</a> There are a number of different fields you can use in your search. For example, zip code, age, and political party. When you select options in the same field, like two zip codes, the search will look for voters that fit at least one option. When you select options in different fields, the search will look voters that fit all of the options. For example, selecting zip codes '00001' and '00002' and party 'DEMOCRAT' will return a list of voters who live in either zip code and are Democrats.</p>
            </div> <!-- End of create list -->

            <!-- Create list -->
            <hr />
            <div class = 'row help-section' id = 'forgot_password'>
                
            
	   </div>
        <div class = 'spacer'></div> 
    </div>
    <footer>
    </footer>
</body>
</html>