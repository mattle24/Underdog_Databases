<?php
echo '
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">
                        <img src = "images/logo_und.png" alt = "Underdog Databases">
                    </a>
                    <button class="navbar-toggle callapsed"
                            data-toggle="collapse"
                            data-target="#collapsemenu">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> <!-- end navbar-header -->

                <div id="collapsemenu" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="centered"><a href="login.php" class="nav-ele">Login</a></li>
                        <li class="centered"><a href="contact_us.php" class="nav-ele">Contact</a></li>
                        <li class="centered"><a href="help.php" class="nav-ele">Help</a></li>
                        <li class="centered"><a href="new_user.php" class="nav-ele">Sign Up</a></li>
                    </ul>
                </div> <!-- end collapsemenu -->
            </div> <!-- end container -->
        </nav>
';
?>