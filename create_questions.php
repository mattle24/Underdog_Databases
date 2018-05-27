<?php
session_start();
include 'includes/check_logged_in.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Questions</title>
    <?php include "includes/head.php"; ?>
</head>
<body>
<?php include('includes/navbar_loggedin.php'); ?>
<div id = 'page-header1'>
    <div class = 'spacer'></div>
    <div id = 'white-container-medium'>
        <div class = 'row'>
            <h2>Manage Questions</h2>
            <?php
            if (isset($_GET["msg"])) {
                $msg = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING);
                echo "<p class='alert alert-warning' role='alert'>$msg</p>";
            }
            ?>
        </div>
        <div class = 'row'>
            <h3>Current Questions</h3>
        </div>

        <?php
        // get list of questions
        if (!isset($_SESSION['cmp'])) {
            $msg = "Please set you campaign before looking at questions.";
            header("Location: choose_campaign.php?msg=$msg");
        } else {
            $cmp = $_SESSION['cmp'];
        }
        include_once 'includes/questions_in_array.php';
        $questions = get_questions($cmp);
        if (count($questions) > 0) {
            echo "<ul class = 'list-group'>";
            foreach($questions as $q) {
                echo "<li class = 'list-group-item'>$q</li>";
            }
            echo "</ul>";
        } else {
            echo "No questions found for your campaign.";
        }

        // If user is high enough level to change questions, allow them
        // to add and remove questions.
        include 'includes/check_level.php';
        $user_pos = checkLevel($_SESSION['logged_user'], $cmp);

        // Can play around with level required to add questions
        if ($user_pos > 1) { ?>
            <div class = 'row'> <!-- Add and remove question forms -->
                <div class = 'col-xs-6'> <!-- div for create question -->
                    <h3>New Question</h3>
                    <form action = 'new_question.php' method = 'post'>
                        <div class = 'form-group'>
                            <input type = 'text' name = 'new_question' placeholder = 'New question' required> </input>
                        </div>
                        <button type = 'submit' class = 'btn btn-primary'>Add</button>
                    </form>
                </div> <!-- End create question -->

                <div class = 'col-xs-6'> <!-- div for remove quesion -->
                    <h3>Remove Question</h3>
                    <form action = 'remove_question.php' method='post'>
                        <div class = 'form-group'>
                            <select aira-describedby = 'rmvHelp' name = 'remove_question' required>
                                <?php
                                foreach($questions as $q) {
                                    echo "<option value = '$q'>$q</option>";
                                }
                                ?>
                            </select>
                            <!-- <input aira-describedby = 'rmvHelp' type = 'text' name = 'remove_question' required></input> -->
                            <small id = 'rmvHelp' class = 'form-text text-muted'>
                                This will prevent people from creating lists based on responses to this
                                question and uploading new lists with responses to this question. It will not
                                remove the existing responses to this question.
                            </small>
                        </div>
                        <button class = 'btn btn-primary' type = 'submit'>Remove</button>
                    </form>
                </div> <!-- End remove quesion -->
            </div>
        <?php
        } else {
            echo "You do not have permission to edit questions for this campaign.";
        }
        ?>
    </div> <!-- end white container -->
    <div class = 'spacer'></div>
</div>

<footer></footer>
</body>
</html>
