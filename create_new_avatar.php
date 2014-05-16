<?php

session_start();

if ($_SESSION['user_id'] == "") {
    header('Location: login.php');
}

require_once 'app/ProcessForm.php';
require_once 'app/Avatars.php';

if (isset($_POST['btnCreate'])) {
    $objAvatars = new Avatars();
    $objAvatars->CreateNewAvatar('NewAvatarForm');
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

        <title>Kheyos: Create Avatar.</title>

        <link href="css/kheyos-style.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">

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
        <!-- Example row of columns -->
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10">

                <h2>Create New Avatar.</h2>
                <br/>

                <form enctype="multipart/form-data" name="NewAvatarForm" method="POST" action="create_new_avatar.php">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">

                    <div class="row">
                        <div class="col-md-7">
                            <div class="width_50 default_profile_50 add_display_inline_block">
                                <img src="" class="width_50"/>
                            </div>
                            <input type="file" id="exampleInputFile" name="profPicture" required>
                        </div>
                        <div class="col-md-5">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <input type="text" name="txtName" class="form-control" placeholder="Enter your Full Name"
                                   onFocus="Info_Over('#name_on_focus_info')" onBlur="Info_Out('#name_on_focus_info')"
                                   required>
                        </div>
                        <div class="col-md-5">
								<span id="name_on_focus_info" class="text-info add_display_none">
									Enter your name. As this is your first avatar, it is highly recommended that you give real credentials. <br/>
								</span>
								<span id="name_on_error" class="error_message">     
									<em>FirstName LastName or <br/>
                                        FirstName MiddleName LastName</em> <br/>
								</span>
                            <br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="text" name="txtHandle" class="form-control" placeholder="Enter your Handle"
                                       onFocus="Info_Over('#handle_on_focus_info')"
                                       onBlur="Info_Out('#handle_on_focus_info')" required>
                            </div>
                        </div>
                        <div class="col-md-5">
								<span id="handle_on_focus_info" class="text-info add_display_none">
									Enter your valid Email Id. We will send you a mail with link to verify. <br/>
								</span> 
								<span id="handle_on_error" class="error_message">     
									<em>[username]@[domain].com</em> <br/>
								</span>
                            <br/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <textarea name="txtBio" class="form-control" rows="2" placeholder="Enter Bio."
                                      onFocus="Info_Over('#bio_on_focus_info')" onBlur="Info_Out('#bio_on_focus_info')"
                                      required></textarea>
                        </div>
                        <div class="col-md-5">
								<span id="bio_on_focus_info" class="text-info add_display_none">
									Enter a brief description about yourself. <br/>
								</span>       
								<span id="bio_on_error" class="error_message">     
									<em>Password should be 8 characters or more.</em> <br/>
								</span>
                            <br/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <input type="date" name="txtDate" class="form-control" placeholder="Enter your Birthday"
                                   onFocus="Info_Over('#birthday_on_focus_info')"
                                   onBlur="Info_Out('#birthday_on_focus_info')" required>
                        </div>
                        <div class="col-md-5">
								<span id="birthday_on_focus_info" class="text-info add_display_none">
									Enter your birthday. <br/>
								</span>       
								<span id="birthday_on_error" class="error_message">     

								</span>
                            <br/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <input type="text" name="txtLocation" class="form-control" placeholder="Enter your Location"
                                   onFocus="Info_Over('#location_on_focus_info')"
                                   onBlur="Info_Out('#location_on_focus_info')" required>
                        </div>
                        <div class="col-md-5">
								<span id="location_on_focus_info" class="text-info add_display_none">
									Enter your location. <br/>
								</span>       
								<span id="location_on_error" class="error_message">     
									<em>City, Country</em> <br/>
									<em>Chennai, India</em> <br/>
									<em>Madurai, India</em> <br/>
								</span>
                            <br/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <button class="btn btn-lg btn-create-avatar btn-block" name="btnCreate" type="submit">
                                Create
                            </button>
                        </div>
                        <div class="col-md-5">
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-md-1">
            </div>
        </div>

        <br/><br/>

        <div class="row fillers_max_780">
            <hr/>
            <h2 class="text-center">Previously created Avatars.</h2>
            <br/>

            <div class="col-sm-4">
                <div class="list-group avatars_page_avatars_list">
                    <a href="#" class="list-group-item active">
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
                <div class="col-xs-12 post_display_block">
                    <div class="width_50 default_profile_50 add_display_inline_block">
                        <img src="img/pic1.png" class="width_50"/>
                    </div>
                    <h4>Mithun Sivagurunathan</h4>
                    <h5>@mg1390</h5>
                </div>
                <div class="col-xs-12 post_display_block">
                    Part-time writer | Full-time bum | Small-time filmmaker | All-time scum | Seriously, Film critic for
                    The Hindu | Three-film young director | Reluctant superhero
                </div>
                <div class="col-xs-12 post_display_block">
                    Chennai, India
                </div>
                <div class="col-xs-12 post_display_block">
                    20th March 1990
                </div>
            </div>
        </div>
        <?php

        require_once 'footer.php';

        ?>
    </div>
    <!-- /container -->

    <?php
    require_once 'core-javascript.php';
    ?>

    </body>
    </html>
<?php
}