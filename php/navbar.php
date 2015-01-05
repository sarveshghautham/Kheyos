<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/16/14
 * Time: 1:06 AM
 */

$pageName = $_SERVER['REQUEST_URI'];

//$pageName = $_SERVER['REQUEST_URI'];
$page = explode('/', $pageName);

?>

<!-- navbar.html -->
<div class="navbar navbar-static-top navbar-default text-center" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <header style="text-align:center">
                <div id="header_navbar_logo">
                </div>
            </header>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="
                    <?php

                if ($page[1] == 'home.php') {
                    echo "active";
                }

                ?>
                    ">
                    <a href="/home.php">
                        <span class="glyphicon glyphicon-home"></span>
                        Home
                    </a>
                </li>
                <li class="
                    <?php

                if ($page[1] == 'my_avatars.php' || $page[1] == 'my_avatars') {
                    echo "active";
                }

                ?>
                    ">
                    <a href="/my_avatars.php">
                        <span class="glyphicon glyphicon-user"></span>
                        Avatars
                    </a>
                </li>
                <!--                <li>-->
                <!--                    <a href="#">-->
                <!--                        <span class="glyphicon glyphicon-comment"></span>-->
                <!--                        Inbox-->
                <!--                    </a>-->
                <!--                </li>-->
            </ul>
            <ul class="nav navbar-nav navbar-right fillers_min_768">
                <li>
                    <a href="update_status_page.php">
                        <span class="glyphicon glyphicon-edit"></span>
                        <span id="navbar-text-update-status">Update Status</span>
                    </a>
                </li>
                <li>
                    <a id="navbar_settings" href="#Settings">
                        <span class="glyphicon glyphicon-cog"></span>
                        <span id="navbar-text-settings" data-toggle="collapse"
                              data-target=".navbar-collapse">Settings</span>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right fillers_max_768">
                <li>
                    <a href="update_status_page.php">
                        <span class="glyphicon glyphicon-edit"></span>
                        <span id="navbar-text-update-status">Update Status</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-cog"></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu text-left">
                        <li><a href="#">Account Settings</a></li>
                        <li><a href="#">Help</a></li>
                        <li class="divider"></li>
                        <li><a href="/logout.php">Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.collapse -->
    </div>
    <!-- /.container -->
</div><!-- /.navbar -->
<!-- /navbar.html -->
		
