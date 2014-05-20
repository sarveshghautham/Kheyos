<?php

session_start();

require_once 'app/ProcessForm.php';
require_once 'app/Avatars.php';

if (isset($_POST['btnCreate'])) {
    $_SESSION['first_avatar'] = 1;
    $objAvatars = new Avatars();
    $objAvatars->CreateNewAvatarStep1('NewAvatarForm');
} else {

    $objProcessForm = new ProcessForm();
    $token = $objProcessForm->GenerateFormToken('NewAvatarForm');
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
                    <form data-toggle="validator" role="form" enctype="multipart/form-data" name="NewAvatarForm"
                          method="POST" action="create_new_avatar_1.php">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">

                        <div class="col-md-12">
                            <h2>Create your first Avatar</h2>
                            <br/>
                        </div>
                        <div class="form-group">
                            <div class="col-md-7">
                                <input type="text" name="txtName" class="form-control" pattern="^([_A-z ]){1,}$"
                                       data-minlength="4" maxlength="50" placeholder="Enter your Full Name"
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
                                    <input type="text" name="txtHandle" class="form-control" pattern="^([_A-z0-9]){1,}$"
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
                                <textarea class="form-control" rows="2" maxlength="80" name="txtBio"
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
                                <input type="text" class="form-control" name="txtLocation" pattern="^([_A-z, ]){1,}$"
                                       placeholder="Enter your Location" onFocus="Info_Over('#location_on_focus_info')"
                                       onBlur="Info_Out('#location_on_focus_info')" required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="location_on_focus_info" class="text-info add_display_none">
										Enter your location. <br/>
									</span>
                                <br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <div class="radio" onFocus="Info_Over('#account_type_on_focus_info')"
                                     onBlur="Info_Out('#account_type_on_focus_info')">
                                    <label>
                                        <input type="radio" name="radioType" id="optionsRadios1" value="0" checked>
                                        Public Account - Anyone can follow you (Default)
                                    </label>
                                </div>
                                <div class="radio" onFocus="Info_Over('#account_type_on_focus_info')"
                                     onBlur="Info_Out('#account_type_on_focus_info')">
                                    <label>
                                        <input type="radio" name="radioType" id="optionsRadios2" value="1">
                                        Protected Account - You decide who follows you
                                    </label>
                                </div>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="account_type_on_focus_info" class="text-info add_display_none">
										Enter your location. <br/>
									</span>
                                <br/>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-7">
                                <button class="btn btn-lg btn-create-avatar btn-block" name="btnCreate" type="submit">
                                    Next
                                </button>
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