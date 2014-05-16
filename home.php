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

<body class="primary_page_body">
<?php
require_once 'navbar.php';
?>
<div class="container">
    <div class="row post_display_block">
        <div class="col-sm-4 text-right post_owner_block">
            <div
                class="user_info_popover add_link_blue"
                data-container="body"
                data-trigger="click"
                data-toggle="popover"
                data-placement="bottom"
                data-html="true"
                data-title=
                "
							<a>
								@sarvghau
							</a> 
							<a class='pull-right'>
								<span class='glyphicon glyphicon-comment'></span>
							</a>
						"
                data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."
                >
                <div class="width_30 default_profile_30 add_display_inline_block">
                    <img src="" class="width_30"/>
                </div>
                <br/>
                Sarghau
            </div>
            <div class="btn-group">
                <div class="btn btn-default btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm">
                    Followed By
                </div>
            </div>
            <!-- /btn-group -->
        </div>
        <div class="col-sm-8">
            <a href="#">10:50 AM - 9 March 2014</a>
            <br/>
            My article, "Who is #Thegidi's Vallaba?" comes tomorrow @behindwoods. Till then, give your speculations..
            @AshokSelvan @jan_iyer @vijayvyoma @AshokSelvan @jan_iyer @vijayvyoma
            <br/>
            <a href="#">Like</a>
            &middot;
            <a href="#">Comment</a>
        </div>
    </div>
    <div class="row post_display_block">
        <div class="col-sm-4 text-right post_owner_block">
            <div
                class="user_info_popover add_link_blue"
                data-container="body"
                data-trigger="click"
                data-toggle="popover"
                data-placement="bottom"
                data-html="true"
                data-title=
                "
							<a>
								@sarvii
							</a> 
							<a class='pull-right'>
								<span class='glyphicon glyphicon-comment'></span>
							</a>
						"
                data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus."
                >
                <div class="width_30 default_profile_30 add_display_inline_block">
                    <img src="" class="width_30"/>
                </div>
                <br/>
                Sargy
            </div>
            <div class="btn-group">
                <div class="btn btn-default btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm">
                    Followed By
                </div>
            </div>
            <!-- /btn-group -->
        </div>
        <div class="col-sm-8">
            <a>10:50 AM - 9 March 2014</a>

            <div class="item">
                <img src="img/cover-photo0.jpg" alt="Chennai, India" class="width_100pc">

                <div class="carousel-caption">
                    <p class="text-right">
                        <span class="carousel_img_location">Chennai, India</span>
                    </p>
                </div>
            </div>
            <a href="#">Like</a>
            &middot;
            <a href="#">Comment</a>
        </div>
    </div>
    <hr>
    <div class="input-group" id="Search">
        <input id="Search_Input" type="text" class="form-control" placeholder="@Kheyos_Handle or Full Name">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
				</span>
    </div>
    <!-- /input-group -->
    <br/>

    <div class="btn-group btn-group-justified" id="Settings">
        <div class="btn-group">
            <button type="button" class="btn btn-default">Account</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-default">Help</button>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-default">Sign Out</button>
        </div>
    </div>
    <!-- footer_links.html -->
    <hr>
    <ul class="footer-default-links muted">
        <li>&copy; Kheyos 2014</li>
        <li>&middot;</li>
        <li><a href="https://github.com/twbs/bootstrap">GitHub</a></li>
        <li>&middot;</li>
        <li><a href="../getting-started/#examples">Examples</a></li>
        <li>&middot;</li>
        <li><a href="../2.3.2/">v2.3.2 docs</a></li>
        <li>&middot;</li>
        <li><a href="../about/">About</a></li>
        <li>&middot;</li>
        <li><a href="http://expo.getbootstrap.com">Expo</a></li>
        <li>&middot;</li>
        <li><a href="http://blog.getbootstrap.com">Blog</a></li>
        <li>&middot;</li>
        <li><a href="https://github.com/twbs/bootstrap/issues?state=open">Issues</a></li>
        <li>&middot;</li>
        <li><a href="https://github.com/twbs/bootstrap/releases">Releases</a></li>
    </ul>
    <!-- /footer_links.php -->
</div>
<!--/.container-->
<!-- core-javascript.html -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type='text/javascript'>
    function Info_Over(x) {
        $(x).show();
    }

    function Info_Out(x) {
        $(x).hide();
    }

    $('#navbar_search').click(function () {
        $('#Search_Input').show().focus();
    });

    <!-- If logged in -->
    $('.user_info_popover').popover();

    $('.user_info_popover').click(function (e) {
        e.stopPropagation();
    });

    $(document).click(function (e) {
        if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
            $('.user_info_popover').popover('hide');
        }
    });

    $(window).resize(function () {
        $('.user_info_popover').popover('hide');
    });
    <!-- /If logged in -->
</script>
<!-- /core-javascript.html -->
</body>
</html>
