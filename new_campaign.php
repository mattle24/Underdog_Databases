<!DOCTYPE html>
<html>
<head>
	<title>New Campaign</title>
   <!-- Source Sans Pro font -->
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
   <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
   <link rel='stylesheet' type='text/css' href="styles/all.css">
   <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
   <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
</head>
<body>
   <?php include 'includes/navbar.php' ?>
   <div id = "page-header1">
       <div class = 'spacer'></div>
       <div id = 'my-form'>
           <?php
           // if isset submit
           // then make sure fields are set
           $first = $_POST['first'];
           $last = $_POST['last'];
           $email = $_POST['email'];
           $campaign = $_POST['campaign'];
           $comments = $_POST['comments'];
           $msg = "Campaign: $campaign\nContact: $first $last\nEmail: $email\n\n $comments";
           $receipt = "$first, thank you for your interest in Grassroots Analytics. We will be in touch as soon as possible.";
           if (isset($msg) && isset($first) && isset($last) && isset($campaign)) {
            mail("mhlehman24@gmail.com", "New Grassroots interest from $campaign", $msg);
            mail($email, "Grassroots Analytics", $receipt);
            echo "Your request has been submitted. We will be in touch as soon as possible!";
        }
        else {
            echo 
            "<form action = 'new_campaign.php' method = 'post' id = 'new_campaign'>
            <label>First</label>
            <input type = 'text' name = 'first' required> <br> <br>
            <label>Last</label>
            <input type = 'text' name = 'last' required> <br> <br>
            <label>Email</label>
            <input type = 'text' name = 'email' pattern = '([a-z]|\d|_)+(@)([a-z])+(\.)([a-z]){2,3}' required> <br><br>
            <label>Campaign/ Organization</label>
            <input type = 'text' name = 'campaign' required> <br> <br>
            <label>How do you plan to use Grassroots Analytics?</label>
            <textarea name = 'comments' form = 'new_campaign' required> </textarea> <br> <br>
            <button type = 'submit' value = 'Submit' formid = 'newusr'>Get Started</button>
        </form>";
    }
    ?>
</div>
</div>
<footer>
</footer>
</body>
</html>