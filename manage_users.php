<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <?php include "includes/head.php"; ?>
</head>
<body>
    <?php include 'includes/navbar_loggedin.php'; ?>
    <div id = "page-header1" class = 'container-fluid'>
       <div class = 'spacer'></div>
           <div id = 'white-container-medium'>
               <div class = 'row'>
                   <h2>Change user position</h2>
                </div>

                <div class = 'row'>
                <?php
                // See if there was an error on a previous attempted change
                if (isset($_GET['err'])) {
                    $err = filter_input(INPUT_GET, 'err', FILTER_SANITIZE_STRING);
                    echo "<p class = 'error'>$err</p>";
                    $_GET = array();
                }
                ?>
                   <p>You can change the positions of users with lower roles than you.</p>
                   <p>You can change their role to a position equal to or lower than the role you have.</p>
               </div>

               <div class = 'row'> <!-- Start form -->
                   <form action = "change_user.php" method = "post">
                       <div class = 'form-group'>
                           <label for = 'formEmail'>User email</label>
                           <input id = 'formEmail' class = 'form-control' type = 'email' name = 'change_email' placeholder = 'jbiden1@gmail.com' required></input>
                            <label for = 'newPos'>New Position</label>
                            <select id = 'newPos' class = 'form-control' name = 'new_pos' required>
                                <option value = 1>Volunteer</option>
                                <option value = 4>Field Organizer</option>
                                <option value = 6>Senior Staff</option>
                                <option value = 8>Field Director</option>
                            </select>
                        </div>
                        <button class = 'btn btn-primary' type = 'submit'>Submit</button>
                    </form>
                </div> <!-- End form -->

                <div class = 'row my_table'> <!-- User table -->
                    <h3 align = 'center'>Campaign Team</h3>
                    <?php
                    // Get all the users for the given campaign and list them in order
                    // of position then name
                    if (!isset($_SESSION['cmp'])) {
                        $msg = "Error: Please choose your campaign before managing users.";
                        header("Location: choose_campaign.php?msg=$msg");
                        exit();
                    }
                    $cmp = $_SESSION['cmp'];
                    include("configs/config.php");
                    $db = new mysqli(
                        DB_HOST,
                        DB_USER,
                        DB_PASSWORD,
                        DB_NAME
                    ) or die("Failed to connect.");
                    $query = "SELECT users.first, users.last, users.email, positions_reference.name
                    FROM users, user_campaign_bridge, campaigns, positions_reference
                    WHERE campaigns.table_name = ?
                    AND user_campaign_bridge.campaignID = campaigns.campaignID
                    AND positions_reference.number = user_campaign_bridge.Position
                    AND users.userID = user_campaign_bridge.userID
                    ORDER BY positions_reference.number DESC, users.last, users.first;";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param('s', $cmp);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($first, $last, $email, $position);

                    echo '
                    <div class = "table-responsive">
                        <table class = "table">
                        <thead id = "QLhead">
                        <tr>
                          <th scope = "col">First</th>
                          <th scope = "col">Last</th>
                          <th scope = "col">Email</th>
                          <th scope = "col">Position</th>
                        </tr>
                        </thead>
                        <tbody id = "QLbody">';
                    while ($stmt->fetch()) {
                        echo "
                        <tr>
                          <td>$first</td>
                          <td>$last</td>
                          <td>$email</td>
                          <td>$position</td>
                        </tr>";
                    }
                    echo '</tbody>
                        </table>
                    </div>
                    ';
                    ?>
                </div> <!-- End of table -->
            </div> <!-- End of white container -->
            <div class="spacer"></div>
    </div> <!-- End of header -->
    <footer></footer>
</body>
</html>
