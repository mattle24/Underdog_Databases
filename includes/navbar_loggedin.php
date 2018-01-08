<?php
echo '
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php"><img src = "images/logo_und.png" alt = "Underdog Databases"></a>
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
                        <li class="centered"><a href="landing.php" class="nav-ele">Toolbox</a>
                        <!-- Add dropdown menu -->
                        </li>

                        <li class="centered"><a href="choose_campaign.php" class="nav-ele">Choose Campaign</a></li>
                        <li class="centered"><a href="settings.php" class="nav-ele">Settings</a></li>
                        <li class="centered"><a href="logout.php" class="nav-ele">Logout</a></li>
                    </ul>
                </div> <!-- end collapsemenu -->
            </div> <!-- end container -->
        </nav>
';
?>