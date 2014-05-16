<?php

session_start();

if ($_SESSION['user_id'] != null) {
    header('Location: index.php');
}

require_once 'app/ProcessForm.php';
require_once 'app/Users.php';
//$obj_users = new Users();

if (isset($_POST['btnLogin'])) {
    $objUsers = new Users();
    $objUsers->login('LoginForm');
} else {

    $objProcessForm = new ProcessForm();
    $token = $objProcessForm->GenerateFormToken('LoginForm');

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

        <title>Kheyos: Log In now.</title>

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
                <div class="row">
                    <div class="col-md-12">
                        <h2>Log In to Kheyos</h2>

                        <form name="LoginForm" action="login.php" method="POST">
                            <label class="checkbox">
                                (Or <a href="register.php">Create an Account</a>)
                            </label>
                            <input type="hidden" name="token" value="<?php echo $token; ?>">

                            <div class="row">
                                <div class="col-md-7">
                                    <input type="email" name="txtEmail" class="form-control"
                                           placeholder="Enter your Email id" onFocus="Info_Over('#email_on_focus_info')"
                                           onBlur="Info_Out('#email_on_focus_info')" required>
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

                            <div class="row">
                                <div class="col-md-7">
                                    <input type="password" name="txtPassword" class="form-control"
                                           placeholder="Enter your Password"
                                           onFocus="Info_Over('#password_on_focus_info')"
                                           onBlur="Info_Out('#password_on_focus_info')" required>
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
                            <br/>

                            <div class="row">
                                <div class="col-md-3">
                                    <label class="checkbox">
                                        <input type="checkbox" value="remember-me"> Remember me
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-lg btn-primary btn-block" name="btnLogin" type="submit">Log
                                        in
                                    </button>
                                </div>
                            </div>
                            <br/>
                            <label class="checkbox">
                                <a href="../css/">Forgot your password?</a>
                            </label>
                        </form>
                    </div>
                </div>

                <br/>
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