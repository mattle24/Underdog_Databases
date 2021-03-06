<?php session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Users</title>
    <?php include "includes/head.php"; ?>
</head>
<body>
<?php
    if (!(isset($_POST['change_email']) and isset($_POST['new_pos']))) {
        echo "<script type=\"text/javascript\">
		alert(\"Make sure to fill out all fields.\");
		window.location = \"manage_users.php\"
		</script>";
        header("Location: manage_users.php"); // if JS disabled
    }
    echo "A";
    // We know both change email and new pos are set
    $change_email = filter_input(INPUT_POST, 'change_email', FILTER_SANITIZE_STRING);
    $new_pos = filter_input(INPUT_POST, 'new_pos', FILTER_SANITIZE_NUMBER_INT);

    if (!isset($_SESSION['cmp'])) {
        $err = "Error. Please select your campaign before managing users.";
        echo "<script type=\"text/javascript\">
		alert(\"$err.\");
		window.location = \"manage_users.php\"
		</script>";
        header("Location: choose_campaign.php?msg=$err"); // if JS disabled
        // TODO: add GET message
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
        DB_NAME
    ) or die("Failed to connect");
    // Get user rank
    $query = "SELECT position FROM user_campaign_bridge, users, campaigns
    WHERE users.email = ?
    AND campaigns.table_name = ?
    AND user_campaign_bridge.userid = users.userid
    AND user_campaign_bridge.campaignid = campaigns.campaignid;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $user_email, $cmp);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_rank);
    $stmt->fetch();
    if ($stmt->num_rows != 1) {
        $err = "Error. There were $stmt->num_rows accounts associated with this campaign for your email.";
        echo "<script type=\"text/javascript\">
        alert(\"$err\");
        window.location = \"manage_users.php\"
        </script>";
        header("Location: manage_users.php?err=$err"); // if javascript disabled
    }
    if ($user_rank < $new_pos) {
        $err = "Error. You cannot promote a user above your rank.";
        echo "<script type=\"text/javascript\">
        alert(\"$err\");
        window.location = \"manage_users.php\"
        </script>";
        header("Location: manage_users.php?err=$err"); // if javascript disabled
    }
//    echo $user_rank;
//    echo $new_pos;
//    exit();
    // Get change user's current rank and ID
    // Also get the campaign id for the next query
    $query = "SELECT user_campaign_bridge.userid, position, user_campaign_bridge.campaignid FROM user_campaign_bridge, users, campaigns
    WHERE users.email = ?
    AND campaigns.table_name = ?
    AND user_campaign_bridge.userid = users.userid
    AND user_campaign_bridge.campaignid = campaigns.campaignid;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ss', $change_email, $cmp);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($change_user_id, $change_user_rank, $cmp_id);
    if ($stmt->num_rows !== 1) {
        $err = "Error. There were $stmt->num_rows accounts associated with this campaign for $change_email.";
        echo "<script type=\"text/javascript\">
        alert(\"$err\");
        window.location = \"manage_users.php\"
        </script>";
        header("Location: manage_users.php?err=$err"); // if javascript disabled
        exit();
    }
    $stmt->fetch();
    echo $user_rank;
    echo $change_user_rank;
//    exit();
    if ($user_rank < $change_user_rank) {
        $err = "Error. You cannot change the role of a user with a more senior role.";
        echo "<script type=\"text/javascript\">
        alert(\"$err\");
        window.location = \"manage_users.php\"
        </script>";
        header("Location: manage_users.php?err=$err"); // if javascript disabled
        exit();
    } elseif ($user_rank == $change_user_rank) {
        $err = "Error. You cannot change the role of a user with an equal role.";
        echo "<script type=\"text/javascript\">
        alert(\"$err\");
        window.location = \"manage_users.php\"
        </script>";
        header("Location: manage_users.php?err=$err"); // if javascript disabled
        exit();
    }
    echo "D";
    // Conditions met, change user rank
    $query = "UPDATE user_campaign_bridge
    SET position = ?
    WHERE userid = ?
    AND campaignid = ?;";
    $stmt = $db->prepare($query);
    $stmt->bind_param('iii', $new_pos, $change_user_id, $cmp_id);
    $stmt->execute();
    if ($stmt) {
        $err = "User role successfully changed.";
        echo "<script type=\"text/javascript\">
        alert(\"$err\");
        window.location = \"manage_users.php\"
        </script>";
        header("Location: manage_users.php?err=$err"); // if javascript disabled
    } else {
        $err = "Unknown error. Please try again or contact the administrator.";
        echo "<script type=\"text/javascript\">
        alert(\"$err\");
        window.location = \"manage_users.php\"
        </script>";
        header("Location: manage_users.php?err=$err"); // if javascript disabled
    }
    $db->close();
?>

</body>
</html>
