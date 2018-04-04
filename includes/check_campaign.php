<?php
// check if a user is part of a specific campaign.
function checkCampaign($username, $cmp) {
    $db = new mysqli(
      DB_HOST,
      DB_USER,
      DB_PASSWORD,
      DB_NAME
      )or die('Failed to connect.');
    $username = $_SESSION['logged_user'];
    $query = "SELECT DISTINCT campaigns.table_name FROM campaigns, user_campaign_bridge, users
    WHERE users.email = ?
    AND users.userID = user_campaign_bridge.userID
    AND user_campaign_bridge.campaignID = campaigns.campaignID
    AND campaigns.table_name = ?;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $username, $cmp);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows < 1) {
        return (FALSE);
    } else {
        return (TRUE);
    }
}
?>
