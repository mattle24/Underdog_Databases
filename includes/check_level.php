<?php
// Useful helper functions //

// Get the position given 
// an email address and campaign
function checkLevel($email, $cmp) {
    require_once('configs/config.php');
    $db = new mysqli(
    DB_HOST,
    DB_USER,
    DB_PASSWORD,
    DB_NAME) or die('Failed to connect.');
    $query = "SELECT position FROM users, user_campaign_bridge, campaigns
    WHERE campaigns.table_name = ?
    AND campaigns.campaignid = user_campaign_bridge.campaignid
    AND users.email = ? 
    AND users.userid = user_campaign_bridge.userid;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $cmp, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows != 1) {
        echo("$stmt->num_rows accounts for this user on the campaign.");
    } else {
        $stmt->bind_result($position);
        $stmt->fetch();
        $stmt->free_result();
        $db->close();
        return ($position);
    }
}

?>
