<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}
require_once 'app/Avatars.php';
$objAvatar = new Avatars();

$handle = $_GET['handle'];
$avatar_id = $objAvatar->GetAvatarIdFromHandle($handle);

if ($_SESSION['user_id'] == null) {
    header('Location: index.php');
} else if ($_GET['handle'] == null) {
    header('Location: my_avatars.php');
} else if ($objAvatar->GetUserId($avatar_id) != $_SESSION['user_id']) {
    header('Location: my_avatars.php');
} else {

    //$avatar_id = filter_input(INPUT_GET, 'avatar_id', FILTER_SANITIZE_NUMBER_INT);

    require_once 'app/Pictures.php';
    require_once 'app/Status.php';
    require_once 'app/Activity.php';

    $objActivity = new Activity();
    $objPictures = new Pictures();
    $objStatus = new Status();

    $avatar_info = $objAvatar->GetAvatarInfo($avatar_id);
    $picture_id = $objPictures->GetProfilePictureId($avatar_id);

    $activities = $objActivity->AvatarActivities($avatar_id);

    //var_dump ($activities);

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
        <link href="/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="/css/kheyos-style.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>

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
            <div class="col-sm-4 text-center">
                <h4>
                    <?php echo $avatar_info['name']; ?><br/>
                    <span class="text-muted">The Activity Log</span>
                </h4>
                <hr class="fillers_min_768"/>
            </div>
            <div class="col-sm-8">
                <?php

                for ($i = 0; $i < count($activities); $i++) {
                    //echo $activities[$i]['type']."<br>";
                    if ($activities[$i]['type'] == 1) {

                        $status_id = $activities[$i]['id'];
                        $status_info = $objStatus->GetStatusInfo($status_id);
                        $timestamp = $status_info['time'];
                        $dateTime = explode(" ", $timestamp);
                        $date = $dateTime[0];
                        $date = explode("-", $date);
                        $time = $dateTime[1];
                        $time = explode(":", $time);
                        if ($time[0] > 12) {
                            $time[0] = $time[0] - 12;
                            $time[1] .= " PM";
                        } else {
                            $time[1] .= " AM";
                        }
                        $statusMonth = date("F", mktime(0, 0, 0, $date[1], 10));


                        ?>
                        <div class="row post_display_block">
                            <div class="col-xs-12">
                                <a class="add_link_gray add_font_size_12"><?php echo $time[0] . ":" . $time[1] . " - " . $date[2] . " " . $statusMonth . " " . $date[0]; ?></a>

                                <div class="item">
                                    <div class="imgLiquidFill imgLiquid status_cover_photo"
                                         style="background-color: <?php echo $status_info['bg_color']; ?>;">
                                        <!-- Fill Background Color from the Form -->
                                        <img alt="Woody"
                                            <?php
                                            if ($status_info['picture_id'] == 0) {


                                                ?>
                                                src=""
                                            <?php
                                            } else {
                                                ?>
                                                src="/get_profile_pic.php?picture_id=<?php echo $status_info['picture_id']; ?>"
                                            <?php
                                            }
                                            ?>
                                            /> <!-- If pic is chosen, fill source. Or else empty -->
                                    </div>
                                    <div class="container carousel-container">
                                        <div class="carousel-caption">
                                            <p class="text-center"
                                               style="color: <?php echo $status_info['font_color']; ?>;">
                                                <!-- Font Colour from form -->
                                                <?php echo $status_info['text']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                    <?php
                    }
                    ?>
                <?php
                }
                ?>

            </div>
        </div>
        <div class="fillers_min_768">
            <hr>
            <?php
            require_once 'settings_bar.php';
            ?>
        </div>
        <?php
        require_once 'footer.php';
        ?>
    </div>
    <!--/.container-->
    <?php
    require_once 'core-javascript.php';
    ?>
    </body>
    </html>
<?php
}