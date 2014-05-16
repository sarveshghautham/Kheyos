<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/16/14
 * Time: 1:06 AM
 */

$pageName = $_SERVER['REQUEST_URI'];

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

                if ($pageName == '/Kheyos/home.php') {
                    echo "active";
                }

                ?>
                    ">
                    <a href="home.php">
                        <span class="glyphicon glyphicon-home"></span>
                        Home
                    </a>
                </li>
                <li class="
                    <?php

                if ($pageName == '/Kheyos/my_avatars.php') {
                    echo "active";
                }

                ?>
                    ">
                    <a href="my_avatars.php">
                        <span class="glyphicon glyphicon-user"></span>
                        Avatars
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="glyphicon glyphicon-comment"></span>
                        Inbox
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a id="navbar_search" href="#Search">
                        <span class="glyphicon glyphicon-search"></span>
                        Search
                    </a>
                </li>
                <li>
                    <a href="update_cover_page.html" data-toggle="modal" data-target=".update-cover-modal"
                       data-remote="update_cover_page.html #update_status_page_form">
                        <span class="glyphicon glyphicon-picture"></span>
                        <span id="navbar-text-update-cover">Update Cover</span>
                    </a>
                </li>
                <li>
                    <a href="update_status_page.php" data-toggle="modal" data-target=".update-status-modal"
                       data-remote="update_status_page.php #update_status_page_form">
                        <span class="glyphicon glyphicon-edit"></span>
                        <span id="navbar-text-update-status">Update Status</span>
                    </a>
                </li>
                <li>
                    <a id="navbar_settings" href="#Settings">
                        <span class="glyphicon glyphicon-cog"></span>
                        <span id="navbar-text-settings">Settings</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- /.collapse -->
    </div>
    <!-- /.container -->
</div><!-- /.navbar -->
<div class="modal fade update-status-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <div class="modal-content text-center">
            <img src="ico/ajax-loader.gif"/>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade update-cover-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <img src="ico/ajax-loader.gif"/>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -
		<!-- /navbar.html -->