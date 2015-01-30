<?php
session_start();
if (!isset ($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once 'app/Avatars.php';
require_once 'app/Pictures.php';

$avatar_id = $_SESSION['avatar_id'];

$objAvatars = new Avatars();
$objPictures = new Pictures();

$avatar_info = $objAvatars->GetAvatarInfo($avatar_id);
$avatar_ids = $objAvatars->AvatarList($_SESSION['user_id']);

$picture_id = $_SESSION['picture_id'];
$handle = $objAvatars->GetHandleFromAvatarId($avatar_id);

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

    <title>Kheyos: Avatar Created.</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/kheyos-style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <?php
    require_once 'core-javascript.php';
    ?>

    <script>

        $(document).ready(function () {

            $(".prev-created-avatars").hide();
            $("#<?php echo $handle; ?>").show();

            $(".avatar-list").click(function () {
                $("a.active").removeClass("active").addClass("inactive");
                $(this).removeClass("inactive").addClass('active');
                $(".prev-created-avatars").hide();

                theDiv = $(this).attr("href");
                $(theDiv).show();
            });

        });

    </script>

</head>

<body class="primary_page_body">
<?php
require_once 'navbar.php';
?>
<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-md-12">
            <form>
                <h2>Ready - Avatar Created</h2>
                <br/>

                <div class="row">
                    <div class="col-md-12">
                        <div class="imgLiquidFill imgLiquid default_profile_50">
                            <img alt="<?php echo "@" . $avatar_info['handle']; ?>"
                                 src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h4><?php echo htmlspecialchars($avatar_info['name']); ?></h4>
                        <h5><?php echo "@" . $avatar_info['handle']; ?></h5>
                    </div>
                    <br/>

                    <div class="col-md-12">
                        <p>
                        	<span class="add_word_wrap_break_word ">
                            <?php echo htmlspecialchars($avatar_info['bio']); ?>
                            	</span>
                        </p>
                    </div>
                    <br/>
                </div>

            </form>

            <div class="row">
                <form name="edit_details" method="GET" action="edit_avatars.php">
                    <div class="col-md-5">
                        <input type="hidden" name="avatar_id" value="<?php echo $_SESSION['avatar_id']; ?>"/>
                        <button class="btn btn-lg btn-default btn-block" type="submit">
                            Edit Details
                        </button>
                    </div>
                </form>

                <div class="col-md-1 span_center">
                    <h4>or</h4>
                </div>
                <div class="col-md-6">
                    <form action="my_avatars.php" method="POST">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                            Go!
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <br/><br/>

    <div class="row fillers_max_780">
        <hr/>
        <h2 class="text-center">Previously created Avatars.</h2>
        <br/>

        <div class="col-sm-4">
            <div class="list-group avatars_page_avatars_list">
                <?php
                for ($i = 0; $i < count($avatar_ids); $i++) {
                    $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
                    $picture_id = $objPictures->GetProfilePictureId($avatar_ids[$i]);
                    ?>
                    <a href="#<?php echo $avatar_info['handle']; ?>" class="avatar-list list-group-item
            <?php
                    if ($avatar_id != 0) {

                        if ($avatar_ids[$i] == $avatar_id)
                            echo "active";
                    } else {
                        if ($i == 0)
                            echo "active";
                    }
                    ?>

            ">
                        <div class="imgLiquidFill imgLiquid default_profile_20 add_display_inline_block">
                            <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"/>
                        </div>
                        <?php echo htmlspecialchars($avatar_info['name']); ?>
                    </a>
                <?php
                }
                ?>
            </div>
            <br/>
        </div>

        <?php
        require_once 'prev_created_avatars.php';
        ?>
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
<!-- /container -->

<?php

require_once 'core-javascript.php';

?>
</body>
</html>
