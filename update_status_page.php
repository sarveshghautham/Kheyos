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

    <link rel="stylesheet" href="css/vendor/jquery.simplecolorpicker.css">

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
<div class="container" id="update_status_page_form">
    <form enctype="multipart/form-data" data-toggle="validator" role="form" class="text-left" name="FormUpdateStatus"
          method="POST" action="confirm_status.php">

        <input type="hidden" name="token" value="<?php echo $token; ?>">

        <div class="row">
            <div class="col-sm-2">
                <div class="form-group text-center">
                    <span class="glyphicon glyphicon-edit"></span><br/>
                    Update Status
                </div>
                <hr class="fillers_min_768"/>
            </div>
            <div class="col-sm-10">
                <div class="form-group col-sm-12">
                    <textarea name="txtStatus" class="form-control" rows="2" placeholder="What's going on?"
                              maxLength="145" required></textarea>
                    <br/>
                </div>
                <div class="form-group col-sm-12">
                    <span class="add_color_gray">Choose the background:</span><br/>
                    <select name="selBgColor" class="colorpicker-longlist" required>
                        <option value="#FFFFFF">White</option>
                        <option value="#000000">Black</option>
                        <option value="#FF0000">Red</option>
                        <option value="#FB9902">Orange</option>
                        <option value="#FFFF00">Yellow</option>
                        <option value="#d0ea2b">Light Green</option>
                        <option value="#66b032">Dark Green</option>
                        <option value="#0391ce">Light Blue</option>
                        <option value="#0247fe">Navy Blue</option>
                        <option value="#3d01a4">Dark Blue</option>
                        <option value="#990099">Violet</option>
                        <option value="#990033">Purple</option>
                    </select>
                    <br/>
                    <span class="add_color_gray">Or - Select an image (Image should be larger than 900x500)</span>
                    <br/>
                    <input type="file" id="Status_Update_Input_File" name="statusPic">
                    <br/>
                </div>

                <div class="form-group col-sm-12">
                    <span class="add_color_gray">Choose the font color:</span><br/>
                    <select name="selFontColor" class="colorpicker-longlist" required>
                        <option value="#000000">Black</option>
                        <option value="#ffffff">White</option>
                    </select>
                    <br/>
                    <br/>
                </div>
                <div class="form-group col-sm-12">
                    <span class="add_color_gray">Post the status with:</span><br/>

                    <?php
                    for ($i = 0; $i < count($avatar_ids); $i++) {
                        $avatar_info = $objAvatars->GetAvatarInfo($avatar_ids[$i]);
                        $picture_id = $objPictures->GetProfilePictureId($avatar_ids[$i]);
                        ?>
                        <div class="radio">
                            <label>
                                <input type="radio" name="radioAvatars" value="<?php echo $avatar_ids[$i]; ?> "
                                       required>

                                <div class="imgLiquidFill imgLiquid default_profile_20 add_display_inline_block">

                                    <?php
                                    if ($picture_id == "") {

                                        ?>

                                        <img alt="Woody" src="img/pic.png"/>
                                    <?php
                                    } else {
                                        ?>
                                        <img alt="<?php echo "@" . $avatar_info['handle']; ?>"
                                             src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>"/>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <?php echo $avatar_info['name']; ?>
                            </label>
                        </div>
                    <?php
                    }
                    ?>

                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-default">No Status</button>
                    <button class="btn btn-primary" name="btnUpdate" type="submit">Update Status</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container">

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
</div><!--/.container-->
<?php
require_once 'core-javascript.php';
?>
</body>
</html>