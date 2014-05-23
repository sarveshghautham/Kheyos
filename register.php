<?php

session_start();

if ($_SESSION['user_id'] != null) {
    header('Location: index.php');
}

require_once 'app/Users.php';
require_once 'app/ProcessForm.php';

if (isset($_POST['btnRegister'])) {
    $objUsers = new Users();
    $objUsers->register('RegisterForm');
} else {
    $objProcessForm = new ProcessForm();
    $token = $objProcessForm->GenerateFormToken('RegisterForm');

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

        <title>Kheyos: Register.</title>

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
                    <div id="header_register_img"></div>
                </header>

                <form name="RegisterForm" data-toggle="validator" method="POST" action="register.php">
                    <div class="row">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">

                        <h2>Join Kheyos. <span class="text-muted">Its free :D</span></h2>
                        <br/>

                        <div class="form-group">
                            <div class="col-md-7">
                                <input type="text" name="txtName" class="form-control"
                                       placeholder="Enter your Full Name"
                                       pattern="^([_A-z ]){1,}$" maxlength="21"
                                       onFocus="Info_Over('#name_on_focus_info')"
                                       onBlur="Info_Out('#name_on_focus_info')"
                                       required>
                                <span class="help-block with-errors"></span>
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
                        <div class="form-group">
                            <div class="col-md-7">
                                <input type="email" name="txtEmail" class="form-control"
                                       placeholder="Enter your Email id"
                                       onFocus="Info_Over('#email_on_focus_info')"
                                       onBlur="Info_Out('#email_on_focus_info')"
                                       required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="email_on_focus_info" class="text-info add_display_none">
										Enter your valid Email Id. We will send you a mail with link to verify. <br/>
									</span> 
									<span id="email_on_error" class="error_message">     
										<em>[username]@[domain].com</em> <br/>
									</span>
                                <br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <input name="txtPassword" type="password" class="form-control"
                                       placeholder="Enter your Password"
                                       data-minlength="8" maxlength="21" id="RegisterFormPassword"
                                       onFocus="Info_Over('#password_on_focus_info')"
                                       onBlur="Info_Out('#password_on_focus_info')"
                                       required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="password_on_focus_info" class="text-info add_display_none">
										Password should be 8 characters or more. Choose a strong password which is a combination of Capital and Small case alphabets, Symbols and Numbers. <br/>
									</span>       
									<span id="password_on_error" class="error_message">     
										<em>Password should be 8 characters or more.</em> <br/>
									</span>
                                <br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <input type="password" name="txtRePassword" class="form-control"
                                       placeholder="Re-enter your Password"
                                       data-match="#RegisterFormPassword" maxlength="21"
                                       onFocus="Info_Over('#confirm_password_on_focus_info')"
                                       onBlur="Info_Out('#confirm_password_on_focus_info')"
                                       required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-md-5">
									<span id="confirm_password_on_focus_info" class="text-info add_display_none">
										Confirm the password entered in previous step. <br/>
									</span>   
									<span id="confirm_password_on_error" class="error_message">     
										<em>Passwords do not match!</em> <br/>
									</span>
                                <br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-7">
                                <button class="btn btn-lg btn-register btn-block" name="btnRegister" type="submit">
                                    Register
                                </button>
                            </div>
                            <div class="col-md-5">
                            </div>
                        </div>
                    </div>

                    <label class="checkbox">
                        Already have an Account? <a href="login.php">Log In</a> Now.
                    </label>

                </form>

                <br/>

                <?php require_once('footer.php'); ?>

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