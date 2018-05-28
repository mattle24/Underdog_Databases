<?php

// This is the new file for helper functions. All helper functions should migrate here.
// TOC:
// - get campaign number
// - get user credentials

function getCampaignNumber($cmp) {
    require_once 'configs/config.php';
    $db = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASSWORD,
        DB_NAME) or die("Failed to connect");
    $query = "SELECT campaignid FROM campaigns WHERE table_name = ?;";
    $stmt = $db->prepare($query);
    $stmt -> bind_param('s', $cmp);
    $stmt -> execute();
    $stmt -> store_result();
    $stmt -> bind_result($campaign_number);
    if ($stmt->fetch()) {
        $db->close();
        return($campaign_number);
    }
    $db->close();
    // TODO: Error handling
    return("SomethingBad");
}




// Get the userid and password given
// an email address
function getCredentials($email) {
    require_once "configs/config.php";
    $db = new mysqli(
        DB_HOST,
        DB_USER,
        DB_PASSWORD,
        DB_NAME) or die("Failed to connect");
    $query = "SELECT userid, hashpassword FROM users
    WHERE email = ?;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashpassword);
    $stmt->fetch();
    $stmt->free_result();
    $db->close();
    return array($user_id, $hashpassword);
}
