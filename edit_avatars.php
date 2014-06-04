<?php
session_start();

if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
}

require_once 'app/Avatars.php';
require_once 'app/Pictures.php';
require_once 'app/Follow.php';

if (!isset($_POST['btnEdit'])) {
    //if ($_POST == null) {
    if ($_GET['handle'] == null) {
        //header('Location: my_avatars.php');
    }
}

//$avatar_id = filter_input(INPUT_GET, 'avatar_id', FILTER_SANITIZE_NUMBER_INT);

$objAvatars = new Avatars();
$objPictures = new Pictures();
$objFollow = new Follow();

$handle = $_GET['handle'];
$avatar_id = $objAvatars->GetAvatarIdFromHandle($handle);

//if (isset($_POST['btnEdit'])) {
if ($_POST != null) {
    $objAvatars->EditAvatar('EditAvatarForm');
} else {

    $avatar_ids = $objAvatars->AvatarList($_SESSION['user_id']);
    $avatar_list = $avatar_ids;

    $objProcessForm = new ProcessForm();
    $token = $objProcessForm->GenerateFormToken('EditAvatarForm');
    $avatar_info = $objAvatars->GetAvatarInfo($avatar_id);

    if ($avatar_id == 0) {
        $avatar_id = $avatar_ids[0];
    }

    $handle = $objAvatars->GetHandle($avatar_id);
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

        <title>Kheyos: Edit Avatars.</title>

        <!-- Bootstrap core CSS -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="/css/kheyos-style.css" rel="stylesheet">

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
                $(".edit-avatar-form").hide();
                $("#<?php echo $handle; ?>").show();

                $(".avatar-list").click(function () {
                    $("a.active").removeClass("active").addClass("inactive");
                    $(this).removeClass("inactive").addClass('active');
                    $(".edit-avatar-form").hide();

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
            <div class="col-sm-4">
                <?php
                require_once 'avatar_list.php';
                ?>
                <br/>
            </div>


            <?php
            require_once 'edit_avatar_form.php';
            ?>
        </div>
        <?php

        require_once 'footer.php';

        ?>
    </div>
    <!-- /container -->

    </body>
    </html>
<?php
}