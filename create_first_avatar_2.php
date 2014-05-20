<?php

session_start();

require_once 'app/ProcessForm.php';
require_once 'app/Avatars.php';

if (isset($_POST['btnCreate2'])) {
    $objAvatars = new Avatars();
    $objAvatars->CreateNewAvatarStep2('NewAvatarForm2');
} else {

    $objProcessForm = new ProcessForm();
    $token = $objProcessForm->GenerateFormToken('NewAvatarForm2');
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

        <title>Kheyos: Create your first Avatar.</title>

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

    <body>
    <div class="container">
        <!-- Example row of columns -->
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <!-- header_intro.html -->
                <header>
                    <div id="header_img"></div>
                    <hr/>
                </header>
                <!-- /header_intro.html -->
                <header>
                    <div id="header_create_avatar_img"></div>
                </header>
                <div class="row">
                    <form enctype="multipart/form-data" data-toggle="validator" role="form" name="NewAvatarForm2"
                          method="POST" action="create_new_avatar_2.php">
                        <div class="col-md-12">
                            <h2>... Create Avatar</h2>
                            <br/>
                        </div>

                        <input type="hidden" name="token" value="<?php echo $token; ?>">

                        <div class="form-group">
                            <div class="col-md-7">
                                <a class='btn btn-default btn-xs' href='javascript:;'>
                                    Choose Profile Picture
                                    <input type="file" name="profPicture" class="btn-file" size="40"
                                           onchange='$("#upload-file-info").html($(this).val());' required>
                                </a>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <input type="date" name="txtDate" class="form-control" placeholder="Enter your Birthday"
                                       onFocus="Info_Over('#birthday_on_focus_info')"
                                       onBlur="Info_Out('#birthday_on_focus_info')" required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="birthday_on_focus_info" class="text-info add_display_none">
										Enter your birthday. <br/>
									</span>
                                <br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <div class="radio" onFocus="Info_Over('#gender_on_focus_info')"
                                     onBlur="Info_Out('#gender_on_focus_info')">
                                    <label>
                                        <input type="radio" name="radioGender" id="optionsRadios1" value="2" required
                                               checked> Female
                                    </label>
                                </div>
                                <div class="radio" onFocus="Info_Over('#gender_on_focus_info')"
                                     onBlur="Info_Out('#gender_on_focus_info')">
                                    <label>
                                        <input type="radio" name="radioGender" id="optionsRadios2" value="1" required>
                                        Male
                                    </label>
                                </div>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="gender_on_focus_info" class="text-info add_display_none">
										Enter your location. <br/>
									</span>
                                <br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button name="btnCreate2" class="btn btn-lg btn-create-avatar btn-block"
                                                type="submit">Create Avatar
                                        </button>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="width_100pc text-center">
                                            <a href="create_new_avatar_ready.php">Skip this step for now.</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                            </div>
                        </div>
                    </form>
                </div>
                <br/><br/>

                <?php
                require_once 'footer.php';
                ?>

            </div>
            <div class="col-md-1">
            </div>
        </div>
    </div>
    <!-- /container -->

    <?php
    require_once 'core-javascript.php';
    ?>

    </body>
    </html>
<?php
}