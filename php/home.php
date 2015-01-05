<?php

session_start();

if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
} else {

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

    <title>Kheyos: Home.</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/kheyos-style.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>

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

<div class="container">

    <div class="row">
        <div id="home-feed" class="col-sm-7">

            <?php require_once 'home_feed.php'; ?>
        </div>
        <!-- col-sm-7 -->

        <div class="col-sm-5 post_owner_block">
            <div class="panel panel-default fillers_max_768">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-edit"></span>
                    Update Status
                </div>
                <div class="panel-body add_background_none">

                    <form enctype="multipart/form-data" data-toggle="validator" role="form" class="text-left"
                          name="FormUpdateStatus" id="FormUpdateStatus" method="POST" action="confirm_status.php">

                        <input type="hidden" name="token" value="<?php echo $token; ?>">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <textarea name="txtStatus" class="form-control" rows="5"
                                              placeholder="What's going on?" maxLength="145" required></textarea>
                                    <br/>
                                </div>
                                <div class="form-group">
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

                                <div class="form-group">
                                    <span class="add_color_gray">Choose the font color:</span><br/>
                                    <select name="selFontColor" class="colorpicker-longlist" required>
                                        <option value="#000000">Black</option>
                                        <option value="#ffffff">White</option>
                                    </select>
                                    <br/>
                                    <br/>
                                </div>
                                <div class="form-group">
                                    <span class="add_color_gray">Post the status with:</span><br/>

                                    <?php
                                    for ($i = 0; $i < count($avatar_list); $i++) {
                                        $avatar_info = $objAvatar->GetAvatarInfo($avatar_list[$i]);
                                        $picture_id = $objPictures->GetProfilePictureId($avatar_list[$i]);
                                        ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="radioAvatars"
                                                       value="<?php echo $avatar_list[$i]; ?> " required>

                                                <div
                                                    class="imgLiquidFill imgLiquid default_profile_20 add_display_inline_block">

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
                                    <button class="btn btn-primary" name="btnUpdate" id="btnUpdate" type="submit">Update
                                        Status
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- row -->

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

<script>

    $(document).ready(function () {

        $('#btnUpdate').click(function (event) {

            event.preventDefault();
            var form = $('#FormUpdateStatus').serializeArray();

            $.ajax({
                type: "POST",
                url: "confirm_status.php",

                data: form,
                cache: false,
                success: function (data, textStatus, jqXHR) {
                    $('#home-feed').fadeOut(800, function () {
                        //$('#home-feed').fadeIn().delay(2000);
                        //$('#home-feed').load('home_feed.php');
                        window.location.reload();
                        $('#FormUpdateStatus')[0].reset();
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Failed");
                }

            });

        });

    });
</script>

<script type='text/javascript'>
    $("#Status_Update_Input_File").change(function () {
        var fthis = this;
        var f = this.files[0];
        var ext = $(this).val().split('.').pop().toLowerCase();
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            alert('invalid extension!');
            reset_form_element($(this));
        }
        else {
            fSize = Math.round(f.fileSize / 1024);
            if (fSize > 8192) {
                alert("The image is larger than 8 MB. Choose a smaller image.");
                reset_form_element($(fthis));
            }
            else {
                var _URL = window.URL || window.webkitURL;
                var image, file;
                if ((file = this.files[0])) {
                    image = new Image();
                    image.onload = function () {
                        if (this.width > 899) {
                            if (this.height > 499) {

                            }
                            else {
                                alert("The image should be larger than 900 x 500!");
                                reset_form_element($(fthis));
                            }
                        }
                        else {
                            alert("The image should be larger than 900 x 500!");
                            reset_form_element($(fthis));
                        }
                    };
                    image.src = _URL.createObjectURL(file);
                }
            }
        }
    });

    function reset_form_element(e) {
        e.wrap('<form>').parent('form').trigger('reset');
        e.unwrap();
    }
</script>

</body>

</html>