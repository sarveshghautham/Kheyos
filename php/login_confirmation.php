<?php

session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
}
require_once 'app/Users.php';
$objUsers = new Users();

if (!isset ($_POST['btnLogin'])) {
    if ($_GET['email'] == "" || $_GET['uid'] == "") {
        header('Location: error.php');
    }
}


if (isset($_POST['btnLogin'])) {

    $objUsers->LoginFirst('LoginForm');

} else {

    $objProcessForm = new ProcessForm();
    $token = $objProcessForm->GenerateFormToken('LoginForm');

    $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
    $uid = $_GET['uid'];

    if (!$objUsers->CheckActivation($email, $uid)) {
        //TODO: Replace with some specific error page.
        header('Location: error.php');
    }
    /*
        if (!$objUsers->ConfirmRegisteration($email, $uid)) {
            //TODO: Replace with some specific error page.
            header('Location: error.php');
        }
*/
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

        <title>Kheyos: Login Confirmation.</title>

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
                    <div id="header_login_confirmation_img"></div>
                </header>

                <form name="LoginForm" data-toggle="validator" action="login_confirmation.php" method="POST">
                    <h2>
                        Account Confirmed.<span class="text-muted"> Log In now to enter Kheyos.</span></h2>
                    <br/>

                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <input type="hidden" name="uid" value="<?php echo $uid; ?>">

                    <div class="row">
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
                                <input type="password" name="txtPassword" class="form-control"
                                       placeholder="Enter your Password" onFocus="Info_Over('#password_on_focus_info')"
                                       onBlur="Info_Out('#password_on_focus_info')" required>
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
                                <button class="btn btn-lg btn-login-confirm btn-block" name="btnLogin" type="submit">
                                    Log In
                                </button>
                            </div>
                            <div class="col-md-5">
                            </div>
                        </div>
                    </div>

                    <label class="checkbox">
                        <a href="../css/">Forgot your password?</a>
                    </label>
                </form>
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