<?php

session_start();

if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
}

require_once 'app/Follow.php';
$objFollow = new Follow();

if (isset ($_POST['btnFollow'])) {

    $objFollow->StartFollowing('FollowForm');

} else {

    if ($_GET['avatar_id'] == null) {
        header('Location: my_avatars.php');
    }

    $avatar_id = filter_input(INPUT_GET, 'avatar_id', FILTER_SANITIZE_NUMBER_INT);
    $user_id = $_SESSION['user_id'];

    require_once 'app/Users.php';
    require_once 'app/Avatars.php';
    require_once 'app/Pictures.php';
    require_once 'app/Status.php';
    require_once 'app/ProcessForm.php';
    require_once 'app/Activity.php';

    $objUsers = new Users();
    $objActivity = new Activity();
    $objPictures = new Pictures();
    $objAvatar = new Avatars();
    $objStatus = new Status();


    /* Visitor info */

    $name = $objUsers->GetName($user_id);
    $avatar_list = $objAvatar->AvatarList($user_id);
    $follow_avatar_list = $objFollow->GetMyFollowingAvatars($avatar_list, $avatar_id);


    /* Profile Owner info */
    $avatar_info = $objAvatar->GetAvatarInfo($avatar_id);
    $picture_id = $objPictures->GetProfilePictureId($avatar_id);
    $cover_picture_id = $objPictures->GetCoverPictureId($avatar_id);
    $pic_timestamp = $objPictures->GetPicTimeStamp($picture_id);

    $dob = $avatar_info['dob'];
    $dob = explode("-", $dob);
    $month = date("F", mktime(0, 0, 0, $dob[1], 10));

    $status_info = $objStatus->GetStatus($avatar_id);
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

    $dateTime = explode(" ", $pic_timestamp);
    $pic_date = $dateTime[0];
    $pic_date = explode("-", $pic_date);
    $pic_time = $dateTime[1];

    $pic_time = explode(":", $pic_time);
    if ($pic_time[0] > 12) {
        $pic_time[0] = $pic_time[0] - 12;
        $pic_time[1] .= " PM";
    } else {
        $pic_time[1] .= " AM";
    }

    $statusMonth = date("F", mktime(0, 0, 0, $date[1], 10));
    $monthName = date("F", mktime(0, 0, 0, $pic_date[1], 10));

    $objProcessForm = new ProcessForm();
    $token = $objProcessForm->GenerateFormToken('FollowForm');

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

    <script>
        // Attach a submit handler to the form
        $("#FollowForm1").submit(function (event) {

            // Stop form from submitting normally
            event.preventDefault();

            // Get some values from elements on the page:
            var $form = $(this),
                term = $form.find("input[name='s']").val(),
                url = $form.attr("action");

            // Send the data using post
            var posting = $.post(url, { s: term });

            // Put the results in a div
            posting.done(function (data) {
                var content = $(data).find("#content");
                $("#result").empty().append(content);
            });
        });
    </script>

    <![endif]-->
</head>

<body class="primary_page_body">
<?php
require_once 'navbar.php';
?>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="well well-sm col-sm-12">
                <h4><?php echo $name; ?></h4>
                <h5>Followed with <?php echo count($follow_avatar_list); ?> Avatars</h5>
            </div>
            <form id="FollowForm" name="FollowForm" method="POST" action="profile.php">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="hidden" name="avatar_id" value="<?php echo $avatar_id; ?>">
                <input type="hidden" name="my_avatar_count" value="<?php echo count($avatar_list); ?>">

                <div class="col-sm-12 post_display_block">

                    <?php
                    for ($i = 0; $i < count($avatar_list); $i++) {

                        $my_avatar_info = $objAvatar->GetAvatarInfo($avatar_list[$i]);
                        $my_picture_id = $objPictures->GetProfilePictureId($avatar_list[$i]);

                        ?>
                        <div class="checkbox">
                            <label>
                                <input type="hidden" name="my_avatar_id_<?php echo $i; ?>"
                                       value="<?php echo $avatar_list[$i]; ?>">
                                <input type="checkbox" name="my_avatar_ids[]" value="<?php echo $avatar_list[$i]; ?>"
                                    <?php
                                    if (count($follow_avatar_list) != 0) {
                                        if (in_array($avatar_list[$i], $follow_avatar_list)) {
                                            echo "Checked";
                                        }
                                    }
                                    ?>

                                    >
                                <img src="get_profile_pic.php?picture_id=<?php echo $my_picture_id; ?>"
                                     class="width_20"/>
                                <?php
                                echo $my_avatar_info['handle'];
                                ?>
                            </label>
                        </div>

                    <?php
                    }
                    ?>

                </div>
                <div class="col-sm-12 text-right post_display_block">
                    <button type="button" class="btn btn-default">Deselect All</button>
                    <button type="submit" name="btnFollow" class="btn btn-primary">Save</button>
                </div>
            </form>
            <hr/>
        </div>
        <div class="col-sm-8">
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
            </div>
            <?php
            if ($status_info != null) {
                ?>
                <div class="col-xs-12 post_display_block">
                    <a><?php echo $time[0] . ":" . $time[1] . " - " . $date[2] . " " . $statusMonth . " " . $date[0]; ?></a>
                    <br/>
                    <?php
                    echo $status_info['text'];
                    ?>
                    <br/>
                    <a href="#">Like</a>
                    &middot;
                    <a href="#">Comment</a>
                    <br/>
                </div>
            <?php
            }

            if ($cover_picture_id != 0) {
                ?>

                <div class="col-xs-12 post_display_block">
                    <a><?php echo $pic_time[0] . ":" . $pic_time[1] . " - " . $pic_date[2] . " " . $monthName . " " . $pic_date[0]; ?></a>

                    <div class="item active">
                        <img src="get_profile_pic.php?picture_id=<?php echo $cover_picture_id; ?>" class="width_100pc">

                        <div class="carousel-caption">
                            <p class="text-right">
                                <span class="label label-transparent" style="font-size:130%;">Chennai, India</span>
                            </p>
                        </div>
                        </div>
                        <a href="#">Like</a>
                        &middot;
                        <a href="#">Comment</a>
                    </div>
            <?php
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