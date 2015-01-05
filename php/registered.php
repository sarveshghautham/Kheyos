<?php

require_once 'app/Users.php';

if (!isset($_SESSION['registered'])) {
    header('Location: error.php');
}

$objUsers = new Users();
$objUsers->SendConfirmationMail();
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

    <title>Kheyos: Registered.</title>

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
            <h3>Account successfully registered!<br/></h3>
            <h4>You have officially agreed to have a Kheyos Account from this day forward, for better and for worse, for
                richer and for poorer, in sickness and in health, forever and forever and forever. </h4>
            <h4>PS, We have sent a mail to the Email Id registered. Click on the link to activate your account.</h4>

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