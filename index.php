<?php

    session_start();

    if ($_SESSION['user_id'] != null) {

        if ($_SESSION['first_avatar'] == 1) {
            header('Location: create_first_avatar.php');
        } else {
            header('Location: home.php');
        }
    }


    require_once 'app/ProcessForm.php';
    require_once 'app/Users.php';

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
			<div class="col-md-6 fillers_min_768">
							<div class="col-xs-2">
							</div>
							<div class="col-xs-10">
								<blockquote>
									<p id="kheyos_promo_quote"></p>
								</blockquote>
							</div>
						</div>
						<div class="col-sm-6 fillers_max_768">
							<div class="item active">
								<img id="kheyos_promo_img">
								<div class="carousel-caption">
									<h3 id="kheyos_promo_img_quote"></h3>
								</div>
							</div>
						
							<script>
								function kheyos_promo_quote_choose()
								{
									var random_number = Math.floor((Math.random()*10));
									switch(random_number)
									{
										case 0:
										return "Introduce a little anarchy. Upset the established order, and everything becomes chaos. I'm an agent of chaos. Oh, and you know the thing about chaos? It's fair! – The joker, <i>The Dark Knight</i>";
										break;
										
										case 1:
										return "This Summer, Let Us All Happily Suffer from Multiple Personality Syndrome !!!";
										break;
										
										case 2:
										return "I am, A Great Friend, A Lovable Spouse, A Caring Parent, A Hardcore Gamer, A Determined Person, A Chatty Co-Worker, A Potential Leader AND A Party Animal. Why Should I Choose ONLY One ??";
										break;
										
										case 3:
										return "In a world, Where I can practically be anyone I want, Why should I be JUST ME ?";
										break;
										
										case 4:
										return "If I say something to my friends and my GF/BF has a problem with it, Why did He/She know about it in the first place?";
										break;
										
										case 5:
										return "Everyone calls me #nickname before. Now it is #firstname#lastname. Why this Kolaveri ?";
										break;
										
										case 6:
										return "Introduce a little anarchy. Upset the established order, and everything becomes chaos. I'm an agent of chaos. Oh, and you know the thing about chaos? It's fair! – The joker, <i>The Dark Knight</i>";
										break;                  
										
										case 7:
										return "No more fibbing about What I said to Who I said to anyone, I say.";
										break;
										
										case 8:
										return "Be Yourself and Yet be You, You, You, You And You !";
										break;
										
										case 9:
										return "Humans deserve Complexity. Simple as that.";
										break;              
										
										default:
										return "Introduce a little anarchy. Upset the established order, and everything becomes chaos. I'm an agent of chaos. Oh, and you know the thing about chaos? It's fair! – The joker, <i>The Dark Knight</i>";

									}
								}
								document.getElementById("kheyos_promo_quote").innerHTML=kheyos_promo_quote_choose();
								document.getElementById("kheyos_promo_img_quote").innerHTML=kheyos_promo_quote_choose();

								function kheyos_promo_img_choose()
								{
									var random_number = Math.floor((Math.random()*10));
									switch(random_number)
									{
										case 0:
											return "img/index_superman.png";
											break;
										case 1:
											return "img/index_batman.png";
											break;	
										case 2:
											return "img/index_enthiran.png";
											break;	
										case 3:
											return "img/index_mask.png";
											break;	
										case 4:
											return "img/index_spiderman.png";
											break;												
										case 5:
											return "img/index_goku.png";
											break;	
										case 6:
											return "img/index_alba.png";
											break;
										case 7:
											return "img/index_elastic_girl.png";
											break;	
										case 8:
											return "img/index_power_puff_girls.png";
											break;	
										case 9:
											return "img/index_wonder_woman.png";
											break;											
										default:
											return "img/index_enthiran.png";

									}
								}
								document.getElementById("kheyos_promo_img").src=kheyos_promo_img_choose();
							</script>
						</div>
						<div class="col-sm-6">
							<br/>

                        <h2>Log In</h2>

                        <form name="LoginForm" action="index.php" method="POST" data-toggle="validator">
                            <input type="hidden" name="token" value="<?php echo $token; ?>">
                            <label class="checkbox">
                                (Or <a href="register.php">Create an Account</a>)
                            </label>
                            <br/>

                            <div class="form-group">
                                <input type="email" name="txtEmail" class="form-control" placeholder="Email address"
                                       autofocus required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="txtPassword" class="form-control" placeholder="Password"
                                       required>
                                <span class="help-block with-errors"></span>
                            </div>
                            <br/>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="checkbox">
                                        <input type="checkbox" value="remember-me"> Remember me
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-lg btn-primary btn-block" name="btnLogin" type="submit">Log
                                        in
                                    </button>
                                </div>
                            </div>
                            <br/>
                            <label class="checkbox">
                                <a href="forgot_password.php">Forgot your password?</a>
                            </label>
                        </form>
                    </div>
                </div>

                <hr/>

                <div class="row featurette text-center">
                    <div class="col-sm-12">
                        <h2 class="featurette-heading">Why Kheyos?</h2>
                    </div>
                </div>

                <br/><br/>

                <div class="row featurette">
                    <div class="col-sm-7">
                        <h2 class="featurette-heading">First featurette heading. <span class="text-muted">It'll blow your mind.</span>
                        </h2>

                        <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta
                            felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce
                            dapibus, tellus ac cursus commodo.</p>
                    </div>
                    <div class="col-sm-5">
                        <img class="featurette-image img-responsive" src="img/pic500.png"
                             alt="Generic placeholder image">
                    </div>
                </div>

                <br/><br/><br/>

                <div class="row featurette">
                    <div class="col-sm-5">
                        <img class="featurette-image img-responsive" src="img/pic500.png"
                             alt="Generic placeholder image">
                    </div>
                    <div class="col-sm-7">
                        <h2 class="featurette-heading">First featurette heading. <span class="text-muted">It'll blow your mind.</span>
                        </h2>

                        <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta
                            felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce
                            dapibus, tellus ac cursus commodo.</p>
                    </div>
                </div>

                <br/><br/><br/>

                <div class="row featurette">
                    <div class="col-sm-7">
                        <h2 class="featurette-heading">First featurette heading. <span class="text-muted">It'll blow your mind.</span>
                        </h2>

                        <p class="lead">Donec ullamcorper nulla non metus auctor fringilla. Vestibulum id ligula porta
                            felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur. Fusce
                            dapibus, tellus ac cursus commodo.</p>
                    </div>
                    <div class="col-sm-5">
                        <img class="featurette-image img-responsive" src="img/pic500.png"
                             alt="Generic placeholder image">
                    </div>
                </div>

                <br/><br/><br/>

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
