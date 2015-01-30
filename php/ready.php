<?php
session_start();

require_once 'app/Avatars.php';
$avatar_id = $_SESSION['avatar_id'];

unset($_SESSION['first_avatar']);

$objAvatars = new Avatars();
$avatar_info = $objAvatars->GetAvatarInfo($avatar_id);

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

    <title>Kheyos: All Set.</title>

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
                <div id="header_ready_img"></div>
            </header>

            <form>
                <h2>Ready - Your first Avatar</h2>
                <br/>

                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="imgLiquidFill imgLiquid default_profile_50">
                                    <img
                                        alt="<?php echo "@" . $avatar_info['handle']; ?>"
                                        src="get_profile_pic.php?picture_id=<?php echo $_SESSION['picture_id']; ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4><?php echo htmlspecialchars($avatar_info['name']); ?></h4>
                                <h5><?php echo "@" . $avatar_info['handle']; ?></h5>
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            <div class="col-md-12">
                                <p>
                                	<span class='add_word_wrap_break_word'>
                                    		<?php echo htmlspecialchars($avatar_info['bio']); ?>
                                    	</span>
                                </p>
                            </div>
                        </div>
                        <br/>
            </form>

            <div class="row">
                <form name="edit_details" method="POST" action="edit_avatars.php">
                    <div class="col-md-5">
                        <input type="hidden" name="avatar_id" value="<?php echo $_SESSION['avatar_id']; ?>"/>
                        <button class="btn btn-lg btn-default btn-block" type="submit">
                            Edit Details
                        </button>
                    </div>
                </form>

                <div class="col-md-1 span_center">
                    <h4>or</h4>
                </div>
                <div class="col-md-6">
                    <form name="avatar_created" method="POST" action="my_avatars.php">
                        <button class="btn btn-lg btn-ready btn-block">
                            Go!
                        </button>
                </div>
            </div>
        </div>
    </div>


    <br/><br/>

    <?php
    require_once 'footer.php'
    ?>


</div>
<div class="col-md-1">
</div>
</div>
</div> <!-- /container -->


<?php
require_once 'core-javascript.php'
?>
</body>
</html>
