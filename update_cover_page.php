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

if (isset($_POST['btnUpload'])) {
    $picture_id = $objPictures->UploadCoverPicture('FormUploadCover');
    $_SESSION['picture_id'] = $picture_id;
    $_SESSION['cover_pic'] = 1;

    header('Location: crop.php');

} else {

    $objProcessForm = new ProcessForm();
    $token = $objProcessForm->GenerateFormToken('FormUploadCover');

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

    <div class="container" id="update_status_page_form">

        <div class="row">
            <div class="col-sm-2">
                <div class="form-group text-center">
                    <a href="javascript:history.back()" class="add_link_gray" data-dismiss="modal" aria-hidden="true">
                        <span class="glyphicon glyphicon-arrow-left"></span>
                    </a>
                </div>
                <hr class="fillers_min_768"/>
            </div>

            <form class="form-horizontal text-left" role="form" enctype="multipart/form-data" name="FormUploadCover"
                  method="POST" action="update_cover_page.php">
                <input type="hidden" name="token" value="<?php echo $token; ?>">

                <div class="col-sm-10">
                    <div class="form-group col-sm-12">
                        <div class="width_45 default_cover_45_25 add_display_inline_block">
                            <img src="" class="width_45"/>
                        </div>
                        <input type="file" name="coverPicture">
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
                        <button type="button" class="btn btn-default">No Cover</button>
                        <button name="btnUpload" type="submit" class="btn btn-primary">Update Cover</button>
                    </div>
                </div>
            </form>
        </div>
        </form>
    </div>
    <div class="container fillers_max_780">
        <br/>
        <hr/>
        <div class="row">
            <div class="col-sm-4">
                <div class="list-group avatars_page_avatars_list">
                    <a href="#" class="list-group-item">
                        <div class="width_20 default_profile_20 add_display_inline_block">
                            <img src="" class="width_20"/>
                        </div>
                        Mithun Avatar 1
                    </a>
                    <a href="#" class="list-group-item">
                        <div class="width_20 default_profile_20 add_display_inline_block">
                            <img src="" class="width_20"/>
                        </div>
                        Mithun Avatar 2
                    </a>
                    <a href="#" class="list-group-item">
                        <div class="width_20 default_profile_20 add_display_inline_block">
                            <img src="" class="width_20"/>
                        </div>
                        Mithun Avatar 3
                    </a>
                    <a href="#" class="list-group-item">
                        <div class="width_20 default_profile_20 add_display_inline_block">
                            <img src="" class="width_20"/>
                        </div>
                        Mithun Avatar 4
                    </a>
                    <a href="#" class="list-group-item">
                        Create New Avatar
                    </a>
                </div>
                <br/>
            </div>
            <div class="col-sm-8">
                <h4 class="text-center">
                    <a href="">
                        <div class="width_30 default_profile_30 add_display_inline_block">
                            <img src="img/pic.png" class="width_30"/>
                        </div>
                        Mithun Sivagurunathan
                    </a>
                </h4>
                <br/>

                <div class="col-xs-12 post_display_block">
                    <a>10:50 AM - 9 March 2014</a>
                    <br/>
                    My article, "Who is #Thegidi's Vallaba?" comes tomorrow @behindwoods. Till then, give your
                    speculations.. @AshokSelvan @jan_iyer @vijayvyoma @AshokSelvan @jan_iyer @vijayvyoma
                    <br/>
                    <a href="#">Like</a>
                    &middot;
                    <a href="#">Comment</a>
                    <br/>
                </div>
                <div class="col-xs-12 post_display_block">
                    <a>10:50 AM - 9 March 2014</a>

                    <div class="item active">
                        <img src="img/cover-photo.jpg" style="width: 100%;" class="img-thumbnail">

                        <div class="container">
                            <div class="carousel-caption">
                                <p class="text-right">
										<span class="label label-transparent" style="font-size:130%;">
											Chennai, India
										</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <a href="#">Like</a>
                    &middot;
                    <a href="#">Comment</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <hr>
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
<?php
}