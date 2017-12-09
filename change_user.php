<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Users</title>
</head>
<body>
<?php
    if (!(isset($_POST['change_email']) and isset($_POST['new_pos']))) {
        echo "<script type=\"text/javascript\">
		alert(\"Make sure to fill out all fields.\");
		window.location = \"manage_users.php\"
		</script>";		
        header("Location: index.php"); // if JS disabled
    }
    // We know both change email and new pos are set
    $change_email = filter_input(INPUT_POST, 'change_email', FILTER_SANITIZE_STRING);
    $new_pos = filter_input(INPUT_POST, 'new_pos', FILTER_SANITIZE_NUMBER_INT);

    if (!isset($_SESSION['change_email'])) {
        echo "<script type=\"text/javascript\">
		alert(\"Please select your campaign.\");
		window.location = \"choose_campaign.php\"
		</script>";	
        header("Location: index.php"); // if JS disabled
    }
    $cmp = $_SESSION['cmp'];
    $user_email = $_SESSION['logged_user'];
    
    // Two requirements: 
    // 1) user must be above the level of the user they are changing
    // 2) user must not change other level to above their own rank
    include("configs/config.php");
    $db = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASSWORD,
        DB_NAME) or die("Failed to connect");
    $query = "SELECT MAX(positions_reference.number), MIN(positions_reference.number)
    FROM positions_reference, users, user_campaign_bridge, campaigns
    WHERE campaigns.table_name = ?
    AND users.email = ?
    AND user_campaign_bridge.campaignID = campaigns.campaignID
    AND user_campaign_bridge.userID = users.userID 
    AND positions_reference.number <= user_campaign_bridge.Position 
    AND positions_reference.number > ?;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssi', $cmp, $user_email, $new_pos);
    
</body>
</html>