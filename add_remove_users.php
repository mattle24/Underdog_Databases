<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>New User</title>
    <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet' type='text/css' href="styles/all.css">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
   <?php include 'includes/navbar_loggedin.php' ?>
   <div id = "page-header1">
       <div class = 'spacer'></div>
       <!-- Add user to campaign -->
       <div id = 'my-form'>
           <h3>Add an existing user to your campaign</h3>
           <p>The user must already have an account with Underdog</p>
           <form action = 'add_remove_users.php' method = 'post'>
            <label>User Email</label>
            <input type = 'text' name = 'new_email' pattern = "([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){2,3}" required> <br>
            <label>New Position</label>
                <select name = 'new_pos'>
                    <option value = 1>Volunteer</option>
                    <option value = 4>Field Organizer</option>
                    <option value = 6>Senior Staff</option>
                    <option value = 8>Field Director</option>
                </select>
               <br>
            <button type = 'submit' value = 'Submit' formid = 'newusr'>Add to Campaign</button>
        </form>
           <br><br>
           
        <!-- Remove user from campaign -->
        <h3>Remove an existing user from your campaign</h3>
        <form action = 'add_remove_users.php' method = 'post'>
            <label>User Email</label>
            <input type = 'text' name = 'old_email' pattern = "([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){2,3}" required> <br>
            <button type = 'submit' value = 'Submit'>Remove from Campaign</button>
        </form>
        <?php
        if (!isset($_SESSION['cmp'])) {
            // if cmp not set, make them set it
            $msg = "Error: You must set your campaign before adding or removing users.";
            header("Location: choose_campaign.php?msg=$msg");
            exit();
        }
        // Get campaignid
        include('configs/config.php');
        $db = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASSWORD,
            DB_NAME) or die('Failed to connect.');
        $query = "SELECT campaignid FROM campaigns WHERE table_name = ?;";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $_SESSION['cmp']);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($cmp_id);
        $stmt->fetch();
        $cmp_id = (int)$cmp_id;

        if (!isset($_POST['new_email'])) {
            if (isset($_POST['old_email'])) {
                $email = filter_input(INPUT_POST, 'old_email');
                
                // make sure the user has an account
                $query = "SELECT userID FROM users WHERE email = ?;";
                $stmt = $db->prepare($query);
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows != 1) {
                    echo "Error. There are $stmt->num_rows accounts associated with this email address.";
                    exit();
                }
                // get the user id
                $stmt->bind_result($user_id);
                $stmt->fetch();

                // Check if the user was in the campaign
                $query = "SELECT userid FROM user_campaign_bridge
                        WHERE campaignid = ?
                        AND userid = ?;";
                $stmt = $db->prepare($query);
                $stmt->bind_param('ii', $cmp_id, $user_id);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows != 1) {
                    echo "Error. There were $stmt->num_rows accounts associated with user $email for your campaign.";
                    exit();
                }
                // Delete the user
                $query = "DELETE FROM user_campaign_bridge WHERE campaignid = ? AND userid = ?;";
                $stmt = $db->prepare($query);
                $stmt->bind_param('ii', $cmp_id, $user_id);
                $stmt->execute();
                if ($stmt) {
                    echo "User succesfully deleted.";
                }
                else { echo "Error. Please try again or contact the administrator."; }
            } else { exit(); }
        }
        else { 
//            echo "Adding user";
            $email = filter_input(INPUT_POST, 'new_email');
            // check to make sure this email already has an account
            $query = "SELECT userID FROM users WHERE email = ?;";
            $stmt = $db->prepare($query);
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_id);
            if ($stmt->num_rows != 1) {
                echo "There was an issue adding this account. There were $stmt->num_rows accounts associated with $email.";
                exit();
            }
            $stmt->fetch(); // stores user id in $user_id

            // Make sure this user isn't already associated with this campaign
            // Check if the user was in the campaign
            $query = "SELECT userid FROM user_campaign_bridge
                    WHERE campaignid = ?
                    AND userid = ?;";
            $stmt = $db->prepare($query);
            $stmt->bind_param('ii', $cmp_id, $user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                echo "Error. There were $stmt->num_rows accounts associated with user $email for your campaign.";
                exit();
            }

            // insert new data into bridge table
            // If a position was specified, add as that position. 
            // Otherwise, add as Vol
            if (isset($_POST['new_pos'])) {
                $new_pos = filter_input(INPUT_POST, 'new_pos', FILTER_SANITIZE_NUMBER_INT);
            } else {$new_pos = 1;}
            $query = "INSERT INTO user_campaign_bridge (userID, campaignID, position)
            VALUES(?, ?, ?);";
            $stmt = $db->prepare($query);
            $stmt->bind_param('iii', $user_id, $cmp_id, $new_pos);
//            echo "point 2";
            $stmt->execute();
            if ($stmt) { echo "User $email succesfully added.";}
            else {echo "Error. Please try again or contact the administrator.";}
        }
    ?>
    </div>
</div>
<footer>
</footer>
</body>
</html>