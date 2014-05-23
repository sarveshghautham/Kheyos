<?php

session_start();
require_once 'app/Avatars.php';
$objAvatar = new Avatars();

if ($_SESSION['user_id'] == null) {
    header('Location: index.php');
} else if ($_GET['avatar_id'] == null) {
    header('Location: my_avatars.php');
} else if ($objAvatar->GetUserId($_GET['avatar_id']) != $_SESSION['user_id']) {
    header('Location: my_avatars.php');
} else {

    $avatar_id = filter_input(INPUT_GET, 'avatar_id', FILTER_SANITIZE_NUMBER_INT);

    require_once 'app/Pictures.php';
    require_once 'app/Status.php';
    require_once 'app/Activity.php';

    $objActivity = new Activity();
    $objPictures = new Pictures();
    $objStatus = new Status();

    $avatar_info = $objAvatar->GetAvatarInfo($avatar_id);
    $picture_id = $objPictures->GetProfilePictureId($avatar_id);

    $activities = $objActivity->AvatarActivities($avatar_id);

    $dob = $avatar_info['dob'];
    $dob = explode("-", $dob);

    $month = date("F", mktime(0, 0, 0, $dob[1], 10));

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="ico/favicon.png">

        <title>Kheyos: One login for the entire WEB.</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/kheyos-style.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="primary_page_body">
    <?php

    require_once 'navbar.php';

    ?>

    <div class="container">
        <div class="row">
            <div class="col-sm-2 text-center">
                <a href="javascript:history.back()" class="add_link_gray">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                </a>
                <hr class="fillers_min_768"/>
            </div>
            <div class="col-sm-10">
                <div class="col-xs-12 post_display_block">
                    <div class="width_35 default_profile_35 add_display_inline_block">
                        <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>" class="width_35"/>
                    </div>
                    <h4><?php echo $avatar_info['name']; ?></h4>
                    <h5><?php echo "@" . $avatar_info['handle']; ?></h5>
                </div>
                <div class="col-xs-12 post_display_block">
                    <?php echo $avatar_info['bio']; ?>
                </div>
                <div class="col-xs-12 post_display_block">
                    <?php echo $avatar_info['location']; ?>
                </div>
                <div class="col-xs-12 post_display_block">
                    <?php echo $dob[2] . "th " . $month . " " . $dob[0]; ?>
                </div>
                <div class="col-xs-12 post_display_block">
                    <hr/>
                    <h4>
                        Activity Log
                    </h4>
                </div>

                <?php
                for ($i = 0; $i < count($activities); $i++) {

                    if ($activities[$i]['type'] == "status") {

                        $status_id = $activities[$i]['id'];
                        $status_info = $objStatus->GetStatusInfo($status_id);
                        ?>
                        <div class="col-xs-12 post_display_block">
                            <a><?php echo $status_info['time']; ?></a>
                            <br/>
                            <?php echo $status_info['text']; ?>
                            <br/>
                            <a href="#">Like</a>
                            &middot;
                            <a href="#">Comment</a>
                            <br/>
                        </div>

                    <?php
                    } else if ($activities[$i]['type'] == "picture") {
                        $picture_id = $activities[$i]['id'];
                        $timestamp = $objPictures->GetPicTimeStamp($picture_id);
                        ?>
                        <div class="col-xs-12 post_display_block">
                            <a><?php echo $timestamp; ?></a>

                            <div class="item active">
                                <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"
                                     style="width: 100%;"
                                     class="img-thumbnail">

                                <div class="container">
                                    <div class="carousel-caption">
                                        <p class="text-right">
                                    <span class="label label-transparent" style="font-size:130%;">
                                        Chennai, India
                                    </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <a href="#">Like</a>
                            &middot;
                            <a href="#">Comment</a>
                        </div>

                    <?php
                    }
                }
                ?>

            </div>
        </div>
        <hr/>
        <div class="input-group" id="Search">
            <input id="Search_Input" type="text" class="form-control" placeholder="@Kheyos_Handle or Full Name">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
				</span>
        </div>
        <!-- /input-group -->
        <br/>

        <div class="btn-group btn-group-justified" id="Settings">
            <div class="btn-group">
                <button type="button" class="btn btn-default">Account</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-default">Help</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-default">Sign Out</button>
            </div>
        </div>
        <?php
        require_once 'footer.php';
        ?>
    </div>
    <!--/.container-->
    <!-- core-javascript.html -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type='text/javascript'>
        function Info_Over(x) {
            $(x).show();
        }

        function Info_Out(x) {
            $(x).hide();
        }

        $('#navbar_search').click(function () {
            $('#Search_Input').show().focus();
        });

        <!-- If logged in -->
        $('.user_info_popover').popover();

        $('.user_info_popover').click(function (e) {
            e.stopPropagation();
        });

        $(document).click(function (e) {
            if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
                $('.user_info_popover').popover('hide');
            }
        });

        $(window).resize(function () {
            $('.user_info_popover').popover('hide');
        });
        <!-- /If logged in -->
    </script>
    <!-- /core-javascript.html -->
    </body>
    </html>
<?php
}