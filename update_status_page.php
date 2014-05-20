<?php

session_start();
if ($_SESSION['user_id'] == null) {
    header('Location: index.php');
}

require_once 'app/Avatars.php';
require_once 'app/Pictures.php';
require_once 'app/ProcessForm.php';

$objPictures = new Pictures();
$objAvatars = new Avatars();
$avatar_ids = $objAvatars->AvatarList($_SESSION['user_id']);

$objProcessForm = new ProcessForm();
$token = $objProcessForm->GenerateFormToken('FormUpdateStatus');

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

<div class="container" id="update_status_page_form">
    <form class="form-horizontal text-left" role="form" name="FormUpdateStatus" method="POST"
          action="confirm_status.php">
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group text-center">
                    <a href="javascript:history.back()" class="add_link_gray" data-dismiss="modal" aria-hidden="true">
                        <span class="glyphicon glyphicon-arrow-left"></span>
                    </a>
                    <br/>
                    <h4>Update Status</h4>
                </div>
            </div>

            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <div class="col-sm-10">
                <div class="form-group col-sm-12">
                    <textarea class="form-control" name="txtStatus" rows="3"></textarea>
                </div>
                <div class="form-group col-sm-12">

                    <?php
                    for ($i = 0; $i < count($avatar_ids); $i++) {
                        $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
                        $picture_id = $objPictures->GetProfilePictureId($avatar_ids[$i]);
                        ?>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="chkAvatars[]" value="<?php echo $avatar_ids[$i]; ?> ">

                                <?php
                                if ($picture_id == "") {

                                    ?>
                                    <img src="img/default.png" class="width_20"/>
                                <?php
                                } else {
                                    ?>
                                    <img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"
                                         class="width_20"/>
                                <?php
                                }
                                echo $avatar_info['name'];
                                ?>
                            </label>
                        </div>
                    <?php
                    }
                    ?>

                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-default">No Status</button>
                    <button type="submit" name="btnUpdate" class="btn btn-primary">Update Status</button>
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>
