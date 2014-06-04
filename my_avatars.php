<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once 'app/Avatars.php';
require_once 'app/Status.php';
require_once 'app/Pictures.php';
require_once 'app/Follow.php';

$objStatus = new Status();
$objAvatars = new Avatars();
$objPictures = new Pictures();
$objFollow = new Follow();

$avatar_ids = $objAvatars->AvatarList($_SESSION['user_id']);


if ($_GET['handle'] != null) {
    $handle = $_GET['handle'];
    $avatar_id = $objAvatars->GetAvatarIdFromHandle($handle);
    $temp_avatar_id = $avatar_id;

} else {
    $avatar_id = 0;
    $temp_avatar_id = $avatar_ids[0];
    $handle = $objAvatars->GetHandle($temp_avatar_id);
}
//echo $_SESSION['avatar_id'];


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

    <link rel="stylesheet" href="css/vendor/jquery.simplecolorpicker.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script>

        $(document).ready(function () {
            $(".avatar-summary").hide();
            $("#<?php echo $handle; ?>").show();

            $(".avatar-list").click(function () {
                $("a.active").removeClass("active").addClass("inactive");
                $(this).removeClass("inactive").addClass('active');
                $(".avatar-summary").hide();

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
    <div class="row">
        <div class="col-sm-4">
            <?php
            require_once 'avatar_list.php';
            ?>
            <br/>
        </div>

        <?php
        require_once 'avatar_summary.php';
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
<!--/.container-->
<?php
require_once 'core-javascript.php';
?>
</body>
</html>