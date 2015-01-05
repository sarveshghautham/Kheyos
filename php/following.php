<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
} else {

    if ($_GET['handle'] == null) {
        header('Location: my_avatars.php');
    }

    require_once 'app/Avatars.php';
    require_once 'app/Follow.php';
    require_once 'app/Pictures.php';

    $objAvatar = new Avatars();
    $objPictures = new Pictures();
    $objFollow = new Follow();

    //$avatar_id = filter_input(INPUT_GET, 'avatar_id', FILTER_SANITIZE_NUMBER_INT);
    $handle = $_GET['handle'];
    $avatar_id = $objAvatar->GetAvatarIdFromHandle($handle);
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
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/kheyos-style.css" rel="stylesheet">

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
                <a href="../<?php echo $avatar_info['handle']; ?>">
                    <?php echo $avatar_info['name']; ?><br/>
                </a>
                <span class="text-muted">Following</span>
            </h4>
            <hr class="fillers_min_768"/>
        </div>
        <div class="col-sm-8">
            <?php
            $followers = $objFollow->GetMyFollowing($avatar_id);
            for ($i = 0; $i < count($followers); $i++) {

                $follower_info = $objAvatar->GetAvatarInfo($followers[$i]);
                $follower_pic_id = $objPictures->GetProfilePictureId($followers[$i]);

                $follow_avatar_list = $objFollow->FollowingFrom($followers[$i], $avatar_id);
                ?>
                <div class="row post-display-block">
                    <div class="col-xs-12">
                        <a href="../<?php echo $follower_info['handle']; ?>">
                            <div class="imgLiquidFill imgLiquid default_profile_35">
                                <?php
                                if ($picture_id == null) {
                                    ?>
                                    <img src="/img/pic.png" class="width_30"/>
                                <?php
                                } else {
                                    ?>
                                    <img src="/get_profile_pic.php?picture_id=<?php echo $follower_pic_id; ?>"/>
                                <?php
                                }
                                ?>
                            </div>
                                <span class="add_font_size_18">
                                <?php echo $follower_info['name']; ?>
                                </span>
                        </a>

                        <p>
                        	<span class="add_word_wrap_break_word fillers_max_768">
                            <?php echo $follower_info['bio']; ?>
                            	</span>
                        </p>

                        <form action="/start_following/<?php echo $follower_info['handle']; ?>" method="POST">
                            <button type="submit" class="btn btn-default btn-xs">Followed
                                With: <?php echo count($follow_avatar_list); ?> Avatars
                            </button>
                        </form>
                    </div>
                </div>
                <hr/>
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