<?php

session_start();

if (isset($_SESSION['picture_id'])) {
    $picture_id = $_SESSION['picture_id'];
} else {
    header('Location: error.php');
}

require_once 'app/Pictures.php';
$objPictures = new Pictures();


if (isset($_POST['btnCrop'])) {
    $objPictures->CropImage("300", "300");
} else if (isset($_POST['btnDisable'])) {
    $objPictures->DeletePic($picture_id);
}

// If not a POST request, display page below:

?><!DOCTYPE html>
<html lang="en">
<head>
    <title>Crop your picture</title>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <script src="jcrop/js/jquery.min.js"></script>
    <script src="jcrop/js/jquery.Jcrop.js"></script>

    <link rel="stylesheet" href="jcrop/css/jquery.Jcrop.css" type="text/css"/>

    <script type="text/javascript">

        $(function () {

            $('#cropbox').Jcrop({
                aspectRatio: 1,
                minSize: [ 300, 300 ],
                onSelect: updateCoords

            });

        });

        function updateCoords(c) {
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        }
        ;

        function checkCoords() {
            if (parseInt($('#w').val())) return true;
            alert('Please select a crop region then press submit.');
            return false;
        }
        ;

    </script>

</head>
<body>

<!-- This is the image we're attaching Jcrop to -->
<img src="get_profile_pic.php?picture_id=<?php echo $picture_id; ?>" id="cropbox"/>

<!-- This is the form that our event handler fills -->
<form action="crop.php" method="post" onsubmit="return checkCoords();">
    <input type="hidden" id="x" name="x"/>
    <input type="hidden" id="y" name="y"/>
    <input type="hidden" id="w" name="w"/>
    <input type="hidden" id="h" name="h"/>
    <input type="submit" name="btnCrop" value="Crop Image" class="btn btn-large btn-inverse"/>
</form>

<form name="disable-pic" action="crop.php" method="POST">

    <button type="submit" name="btnDisable">Cancel</button>

</form>


</body>

</html>