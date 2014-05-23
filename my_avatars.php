<?php

session_start();
if ($_SESSION['user_id'] == null) {
    header('Location: index.php');
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

if ($_SESSION['avatar_id'] != null) {
    $avatar_id = $_SESSION['avatar_id'];
    $temp_avatar_id = $avatar_id;
} else {
    $avatar_id = 0;
    $temp_avatar_id = $avatar_ids[0];
}

$handle = $objAvatars->GetHandle($temp_avatar_id);

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
                <form action="logout.php" method="POST">
                    <button type="submit" class="btn btn-default">Sign Out</button>
                </form>

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
