<?php session_start();
include 'includes/check_logged_in.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
    <?php include "includes/head.php" ; ?>
</head>
<body>
    <?php include 'includes/navbar_loggedin.php'; ?>
    <div id = 'page-header1'>
        <div class = 'spacer'></div>
        <div id = 'white-container-small'>
            <div class = 'row'>
                <h2>Settings</h2>
            </div>
            <?php
            // Get userid, because it is quick and we will need it for both changing email
            // or password
            if (sizeof($_POST) > 0){
                require_once('configs/config.php');
                $db = new mysqli(
                    DB_HOST,
                    DB_USER,
                    DB_PASSWORD,
                    DB_NAME) or die('Failed to connect.');
                $query = "SELECT userID FROM users WHERE email = ?;";
                $stmt = $db->prepare($query);
                $curr_email = $_SESSION['logged_user'];
                $stmt->bind_param('s', $curr_email);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($user_id);
                $stmt->fetch();
                $user_id = (int)$user_id;

              // Validate the current password

                if(!isset($_POST['current_password'])) {
                    echo "Please enter your current password.";
                    exit();
                }
                $post_password = filter_input(INPUT_POST, 'current_password', FILTER_SANITIZE_STRING);
                $query = "SELECT hashpassword FROM users WHERE email = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param('s', $_SESSION['logged_user']);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($hashpassword);
                $stmt->fetch();
                if ( !($stmt->num_rows === 1 && password_verify($post_password, $hashpassword)) ) {
                    echo "Password incorrect. Please reenter your password.";
                }
              // Password verified

                if(isset($_POST['new_email'])) {
                    if(!isset($_POST['confirm_email'])) {
                        echo "Please confirm your new email.";
                    }
                // We now know both new email and confirm email are set
                // Make sure the two match
                    if ($_POST['new_email'] !== $_POST['confirm_email']) {
                        echo "Error. The email addresses did not match.";
                    }
                    $new_email = filter_input(INPUT_POST, 'new_email', FILTER_SANITIZE_STRING);
                // Make sure this is not already an account with the new email
                    $query = "SELECT email FROM users WHERE email = ?";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param('s', $new_email);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows != 0) {
                        echo "There is already an account for this email";
                    } else {
                    // Change the email in the record for this user_id
                        $query = "UPDATE users
                        SET email = ?
                        WHERE userid = ?;";
                        $stmt = $db->prepare($query);
                        $stmt->bind_param('si', $new_email, $user_id);
                        $stmt->execute();
                        if ($stmt) {
                            echo "The email now associated with this account is $new_email";
                            $_SESSION['logged_user'] = $new_email;
                            setcookie('logged_user', $new_email, time() + 60 * 60);
                            $_POST = array();
                        }
                        else {
                            echo "Error. Please try again or contact the administrator.";
                        }
                    }
                }
                elseif (isset($_POST['new_password'])) {
                    if(!isset($_POST['confirm_password'])) {
                        echo "Please confirm your new password.";
                    }
                // We now know both new password and cofirm password are set
                // Make sure the entries match
                    if ($_POST['new_password'] !== $_POST['confirm_password']) {
                        echo "Error. The passwords did not match.";
                    }
                // Passwords match. Change the password.
                // Hash password
                    $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
                    $passwd = password_hash($new_password, PASSWORD_DEFAULT);
                    $query = "UPDATE users
                    SET hashpassword = ?
                    WHERE userid = ?;";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param('si', $passwd, $user_id);
                    $stmt->execute();
                    if ($stmt) {
                        echo "The password now associated with this account has been changed.";
                        $_POST = array();
                    }
                    else {
                        echo "Error. Please try again or contact the administrator.";
                    }
                }
            }
            ?>
            <div class = 'row'>
                <h3>Change Email</h3>
            </div>

            <!-- Form to change email -->
            <form action = 'settings.php' method = 'post'>
                <div class = 'form-group'>
                    <label for = 'newEmail'>New Email</label>
                    <input id = 'newEmail' class = 'form-control' type = 'email' name = 'new_email' pattern = "([a-z]|\d|_|.)+(@)([a-z])+(\.)([a-z]){2,3}" required/>
                </div>

                <div class = 'form-group'>
                    <label for = 'cnfEmail'>Confirm New Email</label>
                    <input id = 'cnfEmail' class = 'form-control' type = 'email' name = 'confirm_email' pattern = "([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){2,3}" required/>
                </div>

                <div class = 'form-group'>
                    <label for = 'pwd1'>Current Password</label>
                    <input id = 'pwd1' class = 'form-control' type = 'password' name = 'current_password' required></input>
                </div>
                <button class = 'btn btn-primary' type='submit'>Change Email</button>
            </form>
        <br />

        <!-- Form to change password -->
        <div class = 'row'>
            <h3>Change Password</h3>
        </div>
        <form action = 'settings.php' method = 'post'>
            <div class = 'form-group'>
                <label for = 'pwd2'>Current Password</label>
                <input id = 'pwd2' class = 'form-control' type = 'password' name = 'current_password' required/>
            </div>

            <div class = 'form-group'>
                <label for = 'newPwd'>New Password</label>
                <input id = 'newPwd' class = 'form-control' type = 'password' name = 'new_password' pattern = "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" aria-describedby= 'pwdHelp' required/>
                <small id = 'pwdHelp' class = 'form-text text-muted'>Your password must contain at least 8 characters, including one number, one capital letter, and one lowercase letter.</small>
            </div>

            <div class = 'form-group'>
                <label for = 'cnfPwd'>Confirm New Password</label>
                <input id = 'cnfPwd' class = 'form-control' type = 'password' name = 'confirm_password' required/>
            </div>
            <button class = 'btn btn-primary' type = 'submit'>Change Password</button>
      </form>
        </div>
        <div class = "spacer"></div>
    </div>
    <footer>
    </footer>
</body>
</html>
