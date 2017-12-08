<?php
// Get the userid and password given 
// an email address
function getCredentials($email) {
    include("configs/config.php");
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
    return array($user_id, $hashpassword);
    $stmt->free_result();
    $db->close();
}
