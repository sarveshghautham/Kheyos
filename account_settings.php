<?php

session_start();

if (!isset ($_SESSION['user_id'])) {
    header('Location: login.php');
}

require_once 'app/ProcessForm.php';
require_once 'app/Users.php';

$objUsers = new Users();
$objProcessForm = new ProcessForm();
$token1 = $objProcessForm->GenerateFormToken('ChangeEmailForm');
$token2 = $objProcessForm->GenerateFormToken('ChangePasswordForm');

$user_id = $_SESSION['user_id'];
$email = $objUsers->GetEmail($user_id);

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

    <title>Kheyos: Account Settings.</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/kheyos-style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <?php
    require_once 'core-javascript.php';
    ?>

    <script>

        $(document).ready(function () {

            $("#edit-password").hide();
            $("#edit-email").show();

            $("#edit-toggle-password").click(function () {
                $("#edit-toggle-email").removeClass("active").addClass("inactive");
                $("#edit-toggle-password").removeClass("inactive").addClass("active");
                $("#edit-email").hide();
                $("#edit-password").show();
            });

            $("#edit-toggle-email").click(function () {
                $("#edit-toggle-password").removeClass("active").addClass("inactive");
                $("#edit-toggle-email").removeClass("inactive").addClass("active");
                $("#edit-email").show();
                $("#edit-password").hide();
            });


            //callback handler for form submit
            $("#edit-email-form").submit(function (e) {
                e.preventDefault(); //STOP default action
                var postData = $(this).serializeArray();
                var formURL = $(this).attr("action");

                $.ajax(
                    {
                        url: formURL,
                        type: "POST",
                        data: postData,
                        success: function (data, textStatus, jqXHR) {
                            //data: return data from server
                            alert(data);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            //if fails     
                        }
                    });

                e.unbind(); //unbind. to stop multiple form submit.
            });

            $("#edit-email-form").submit(); //Submit  the FORM
        });

    </script>

</head>

<body class="primary_page_body">

<?php

require_once 'navbar.php';
?>

<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-sm-4">
            <div class="list-group avatars_page_avatars_list">
                <a href="#" id="edit-toggle-email" class="list-group-item active">
                    Change Log In Email
                </a>
                <a href="#" id="edit-toggle-password" class="list-group-item inactive">
                    Change Log In Password
                </a>
            </div>
            <br/>
        </div>
        <div class="col-sm-8">
            <div id="edit-email">
                <h3 class="text-center">Account Settings: <span class="text-muted"> Change Log In Email</span></h3>
                <br/>

                <form id="edit-email-form" action="change_email.php" name="ChangeEmailForm" class="form-horizontal"
                      data-toggle="validator" role="form">
                    <input type="hidden" name="token" value="<?php echo $token1; ?>">

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Current Email Address</label>
                            <input class="form-control" name="txtOldEmail" value="<?php echo $email; ?>" disabled>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>New Email Address</label>
                            <input class="form-control" name="txtEmail" type="email" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Enter Password</label>
                            <input class="form-control" name="txtPassword" type="password" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" name="btnChangeEmail" class="btn btn-primary btn-lg">Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="edit-password">

                <h3 class="text-center">Account Settings: <span class="text-muted"> Change Log In Password</span></h3>
                <br/>

                <form id="edit-password-form" name="ChangePasswordForm" class="form-horizontal" data-toggle="validator"
                      role="form">
                    <input type="hidden" name="token" value="<?php echo $token2; ?>">

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Current Password</label>
                            <input class="form-control" type="password" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>New Password</label>
                            <input class="form-control" type="password" id="AccountSettingsNewPassword" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Confirm New Password</label>
                            <input class="form-control" type="password" data-match="#AccountSettingsNewPassword"
                                   required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    require_once 'footer.php';
    ?>
</div>
<!-- /container -->
</body>
</html>