<?php

session_start();

if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
} else {

    if ($_GET['avatar_id'] == null) {
        header('Location: my_avatars.php');
    }

    require_once 'app/Avatars.php';
    require_once 'app/Follow.php';
    require_once 'app/Pictures.php';

    $objAvatar = new Avatars();
    $objPictures = new Pictures();
    $objFollow = new Follow();

    $avatar_id = filter_input(INPUT_GET, 'avatar_id', FILTER_SANITIZE_NUMBER_INT);
    $user_id = $_SESSION['user_id'];

    $avatar_list = $objAvatar->AvatarList($user_id);
    $avatar_info = $objAvatar->GetAvatarInfo($avatar_id);
    $picture_id = $objPictures->GetProfilePictureId($avatar_id);
}
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
            <div class="col-xs-12">
                <div class="width_35 default_profile_35 add_display_inline_block">
                    <?php
                    if ($picture_id == null) {
                        ?>
                        <img src="img/pic.png" class="width_35"/>
                    <?php
                    } else {
                        ?>
                        <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>" class="width_35"/>
                    <?php
                    }
                    ?>
                </div>
                <h4><?php echo $avatar_info['name']; ?></h4>
                <h5><?php echo $avatar_info['handle']; ?></h5>
                <hr/>
            </div>
            <div class="col-xs-12 post_display_block">
                <h4 class="text-center">
                    Following
                </h4>
            </div>
            <div class="col-xs-12 post_display_block">

                <?php
                $followers = $objFollow->GetMyFollowing($avatar_id);
                for ($i = 0; $i < count($followers); $i++) {

                    $follower_info = $objAvatar->GetAvatarInfo($followers[$i]);
                    $follower_pic_id = $objPictures->GetProfilePictureId($followers[$i]);

                    $follow_avatar_list = $objFollow->FollowingFrom($followers[$i], $avatar_id);
                    ?>

                    <div class="well well-sm col-xs-12">
                        <h4>
                            <a class="add_link_black" href="profile.php?avatar_id=<?php echo $followers[$i]; ?>">
                                <div class="width_30 default_profile_30 add_display_inline_block">
                                    <?php
                                    if ($picture_id == null) {
                                        ?>
                                        <img src="img/pic.png" class="width_30"/>
                                    <?php
                                    } else {
                                        ?>
                                        <img src="get_profile_pic.php?picture_id=<?php echo $follower_pic_id; ?>"
                                             class="width_30"/>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <?php echo $follower_info['name']; ?>
                            </a>
                        </h4>
                        <hr/>
                        <p>
                            <?php echo $follower_info['bio']; ?>
                        </p>

                        <p>
                            <?php echo $follower_info['location']; ?>
                        </p>
                        <button type="button" class="btn btn-default btn-xs">Followed
                            By: <?php echo count($follow_avatar_list); ?> Avatars
                        </button>
                    </div>
                <?php
                }
                ?>
            </div>
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
<?php
require_once 'core-javascript.php';

?>
</body>
</html>
