<?php

session_start();

if (!isset ($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once 'app/ProcessForm.php';
require_once 'app/Avatars.php';
require_once 'app/Pictures.php';

$objPictures = new Pictures();
$objAvatars = new Avatars();

if ($_POST != null) {

    $objAvatars->CreateNewAvatar('NewAvatarForm');
} else {

    $objProcessForm = new ProcessForm();
    $token = $objProcessForm->GenerateFormToken('NewAvatarForm');

    $avatar_ids = $objAvatars->AvatarList($_SESSION['user_id']);
    $handle = $objAvatars->GetHandle($avatar_ids[0]);

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

        <title>Kheyos: Create Avatar.</title>

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

                $('#error-msg').hide();

                $('#btnCreate').click(function (event) {
                    //$('#txtHandle').focusout(function(event) {

                    $('#error-msg').hide();
                    event.preventDefault();
                    var username = $('#txtHandle').val();

                    $.ajax({
                        type: "POST",
                        url: "username_verify.php",

                        data: {username: username},
                        cache: false,
                        success: function (response) {
                            if (response == "Y") {
                                $('#error-msg').hide();
                                $('#NewAvatarForm').submit();
                            }
                            else {
                                $('#error-msg').show();
                            }
                        }

                    });

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
                <div class="row">
                    <form data-toggle="validator" role="form" enctype="multipart/form-data" id="NewAvatarForm"
                          name="NewAvatarForm"
                          method="POST" action="create_new_avatar.php">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">

                        <div class="col-md-12">
                            <h2>Create your new Avatar</h2>
                            <br/>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <div id="error-msg" class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                    <strong>Error!</strong> Username already exists. Please choose a different one.
                                </div>
                            </div>
                            <div class="col-md-5">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <input type="file" name="profPicture" size="40" id="Profile_Pic_Input_File"
                                       onchange='$("#upload-file-info").html($(this).val());'
                                       onFocus="Info_Over('#profile_pic_on_focus_info')"
                                       onBlur="Info_Out('#profile_pic_on_focus_info')" autofocus required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
                            	<span id="profile_pic_on_focus_info" class="text-info">
                                    Choose your profile picture. <br/>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <input type="text" name="txtName" class="form-control" pattern="([a-zA-Z]+ +)*[a-zA-Z]+"
                                       maxlength="21" placeholder="Enter your Full Name"
                                       onFocus="Info_Over('#name_on_focus_info')"
                                       onBlur="Info_Out('#name_on_focus_info')" required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="name_on_focus_info" class="text-info add_display_none">
										Enter your name. As this is your first avatar, it is highly recommended that you give real credentials. <br/>
									</span>
                                <br/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-addon">@</span>
                                    <input type="text" id="txtHandle" name="txtHandle" class="form-control"
                                           pattern="^([_a-zA-Z0-9]){1,}$"
                                           data-minlength="4" maxlength="20" placeholder="Enter your Handle"
                                           onFocus="Info_Over('#handle_on_focus_info')"
                                           onBlur="Info_Out('#handle_on_focus_info')" required>
                                </div>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="handle_on_focus_info" class="text-info add_display_none">
										Enter your valid Email Id. We will send you a mail with link to verify. <br/>
									</span>
									<span id="handle_on_error" class="help-block with-errors error_message">
										<em>[username]@[domain].com</em> <br/>
									</span>
                                <br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <textarea class="form-control" rows="2" maxlength="140" name="txtBio"
                                          placeholder="Enter Bio." onFocus="Info_Over('#bio_on_focus_info')"
                                          onBlur="Info_Out('#bio_on_focus_info')" required></textarea>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="bio_on_focus_info" class="text-info add_display_none">
										Enter a brief description about yourself. <br/>
									</span>
                                <br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <button class="btn btn-lg btn-create-avatar btn-block" id="btnCreate" name="btnCreate"
                                        type="submit">
                                    Create Avatar
                                </button>
                            </div>
                            <div class="col-md-5">
                            </div>
                        </div>

                    </form>
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
                            <?php echo $avatar_info['name']; ?>
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

    <script type='text/javascript'>
        function displayPreview(files) {
            var ext = $('#Profile_Pic_Input_File').val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                alert('invalid extension!');
                reset_form_element($('#Profile_Pic_Input_File'));
            }
            else {
                var reader = new FileReader();
                var img = new Image();
                reader.onload = function (e) {
                    img.src = e.target.result;
                    fileSize = Math.round(files.size / 1024);
                    if (fileSize > 4096) {
                        alert("The image is larger than 4 MB. Choose a smaller image.");
                        reset_form_element($('#Profile_Pic_Input_File'));
                    }
                    else {
                        img.onload = function () {
                            if (this.width > 250) {
                                if (this.height > 250) {
                                }
                                else {
                                    alert("The image should be larger than 250 x 250!");
                                    reset_form_element($('#Profile_Pic_Input_File'));
                                }
                            }
                            else {
                                alert("The image should be larger than 250 x 250!");
                                reset_form_element($('#Profile_Pic_Input_File'));
                            }
                        };
                    }
                };
            }
            reader.readAsDataURL(files);
        }

        $("#Profile_Pic_Input_File").change(function () {
            var file = this.files[0];
            displayPreview(file);
        });

        function reset_form_element(e) {
            e.wrap('<form>').parent('form').trigger('reset');
            e.unwrap();
            document.getElementById('Profile_Pic_Input_File').focus();
        }

    </script>
    </body>
    </html>
<?php
}