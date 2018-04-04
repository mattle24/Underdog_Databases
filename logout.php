<?php
session_start();
// erase Session variables and logged user cookie
$_SESSION = array();
setcookie('logged_user', 'nothing', time() - 60);
// echo "<script type=\"text/javascript\">
// alert(\"You have been logged out.\");
// window.location = \"login.php\"
// </script>";
$msg = '';
if (!isset($_GET['reason'])) {
    $msg = $_GET['reason'];
}
header("Location: login.php?msg = $msg"); // if javascript disabled
?>
