<?php

    session_start();

    if ($_SESSION['user_id'] != null) {
        header('Location: home.php');
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

		<title>Kheyos: Forgot Password.</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="css/kheyos-style.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->

        <style type="text/css">

            label.error { color: red;  }

            #status { color: green; }



        </style>

        <?php
        require_once 'core-javascript.php';
        ?>

        <script>

            $(document).ready(function(){

                $('#code_form').hide();

                $('#password_form').hide();

                $('#email_send').click(function(event) {

                    event.preventDefault();

                    var email = $('#email').val();

                    $.ajax({
                        type: "POST",
                        url: "reset_code.php",

                        data: {email: email},
                        cache: false,
                        success: function(response){


                            if(response == "Y")
                            {
                                $('#code_form').show();
                                $('#email_form').hide();
                                $('#status').show();
                                $('#status').html("Check your mail for the reset code.");
                            }
                            else
                            {
                            	$('#status').show();
                                $('#status').html("Looks like you have entered a wrong email ID.");
                            }
                        }

                    });

                });


                $('#code_send').click(function(event) {

                    event.preventDefault();

                    var email = $('#email').val();
                    var code = $('#code').val();


                    $.ajax({
                        type: "POST",
                        url: "code_verify.php",

                        data: {email: email, code: code},
                        cache: false,
                        success: function(response){


                            if(response == "Y")
                            {
                                $('#password_form').show();
                                $('#code_form').hide();
                                $('#status').show();
                                $('#status').html("Ok, now enter your password twice before you forget again.");

                            }
                            else
                            {
                            	$('#status').show();
                                $('#status').html("Please enter the code received in your mail.");
                            }

                        }


                    });

                });

                $('#password_send').click(function(event) {

                    event.preventDefault();

                    var email = $('#email').val();
                    var password = $('#password').val();
                    var repassword = $('#repassword').val();

                    $.ajax({
                        type: "POST",
                        url: "update_password.php",

                        data: {email: email, password: password, repassword: repassword},
                        cache: false,
                        success: function(response){

                            if(response == "Y")
                            {

                                $('#email_form').hide();
                                $('#code_form').hide();
                                $('#password_form').hide();
                                $('#status').show();
                                $('#status').html("Password reset successful. Proceed to <a href=index.php>login</a> page. ");


                            }
                            else
                            {
                            	$('#status').show();
                                $('#status').html("Oops. Something went wrong. Try again.");

                            }

                        }

                    });

                });

            });

        </script>

	</head>

	<body>

		<div class="container">
			<!-- Example row of columns -->
			<div class="row">

				<div class="col-md-1">
				</div>

				<div class="col-md-10">

					<header style="text-align:center">
						<div id="header_img"></div>
						<hr/>
					</header>

					<div class="row">

						<div class="col-md-12">

							
                            

								<div class="row">

                           	<div class="col-md-7">
                                    	<h2>Forgot your Password</h2>
                                    	<br/>
                                    	<div id="status" class="alert alert-success add_display_none">			                        
                    			</div>
				</div>
				<div class="col-md-5">
				</div>
				</div>
				<form name="email_form" id="email_form" method="POST" action="" data-toggle="validator">
				<div class="row">

                           	<div class="col-md-7">
                           		<div class="form-group">
						<input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email id" onFocus="Info_Over('#email_on_focus_info')" onBlur="Info_Out('#email_on_focus_info')" required>
						<span class="help-block with-errors"></span>
					</div>
				</div>

				<div class="col-md-5">
					<span id="email_on_focus_info" class="text-info add_display_none">
						Enter the Email Id with which you registered. We will send you a code, which you have to enter in the next step. <br/>
					</span> 									
					<br/>
				</div> 
			</div>                             
				
			<br/>

			<div class="row">

				<div class="col-md-3">
				</div>

				<div class="col-md-4">
					<button class="btn btn-lg btn-primary btn-block" type="submit" id="email_send">Go</button>
				</div>
			</div>

                            </form>

                            <form name="code_form" id="code_form" method="POST" action="" data-toggle="validator">

                                <div class="row">

                                    <div class="col-md-7">
                                    	<div class="form-group">
                                        	<input type="text" name="code" id="code" class="form-control" placeholder="Enter the code" onFocus="Info_Over('#code_on_focus_info')" onBlur="Info_Out('#code_on_focus_info')" required>
                                        	<span class="help-block with-errors"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
					<span id="code_on_focus_info" class="text-info add_display_none">
						Enter the code mailed to your registered Email Id. <br/>
					</span>
                                        <br/>
                                    </div>
                                </div>

                                <br/>

                                <div class="row">

                                    <div class="col-md-3">
                                    </div>

                                    <div class="col-md-4">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit" id="code_send">Go</button>
                                    </div>
                                </div>

                            </form>

                            <form name="password_form" id="password_form" method="POST" action=""  data-toggle="validator">

                                <div class="row">

                                    <div class="col-md-7">
                                    	<div class="form-group">
	                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter a new password" onFocus="Info_Over('#password_on_focus_info')" onBlur="Info_Out('#password_on_focus_info')" data-minlength="8" maxlength="21" required>
	                                        <span class="help-block with-errors"></span>
					</div>	                         
                                    </div>

                                    <div class="col-md-5">
					<span id="email_on_focus_info" class="text-info add_display_none">
						Password should be 8 characters or more. Choose a strong password which is a combination of Capital and Small case alphabets, Symbols and Numbers. <br/>
					</span>
                                        <br/>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-7">
                                    	<div class="form-group">
                                        	<input type="password" name="repassword" id="repassword" class="form-control" placeholder="Enter your Email id" data-match="#password" maxlength="21" onFocus="Info_Over('#confirm_password_on_focus_info')" onBlur="Info_Out('#confirm_password_on_focus_info')" required>
                                        	<span class="help-block with-errors"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
					<span id="confirm_password_on_focus_info" class="text-info add_display_none">
						Confirm the password entered in previous step. <br/>
					</span>
                                        <br/>
                                    </div>
                                </div>

                                <br/>

                                <div class="row">

                                    <div class="col-md-3">
                                    </div>

                                    <div class="col-md-4">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit" id="password_send">Go</button>
                                    </div>
                                </div>

                            </form>


                            <br/>

                            <label class="checkbox">
                                (Or <a href="register.php">Create an Account</a>)
                            </label>

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
		</div> <!-- /container -->

	</body>
</html>
