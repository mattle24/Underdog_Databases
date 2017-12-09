<?php 
session_start();
// erase Session variables and logged user cookie
$_SESSION = array();
setcookie('logged_user', 'nothing', time() - 60);
echo "<script type=\"text/javascript\">
alert(\"You have been logged out.\");
window.location = \"index.php\"
</script>";		
header('Location: index.php'); // if javascript disabled
?>
