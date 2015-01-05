<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/8/14
 * Time: 10:46 PM
 */

session_start();

if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
}

require_once 'app/Pictures.php';

if ($_GET['picture_id'] == null) {
    header('Location: error.php');
} else {

    $objPictures = new Pictures();
    $picture_id = filter_input(INPUT_GET, 'picture_id', FILTER_SANITIZE_NUMBER_INT);

    //if ($objPictures->CheckPictureAccess($_SESSION['user_id'], $picture_id)) {

    $pic_info = $objPictures->GetProfilePicture($picture_id);
    $path = $pic_info['path'];
    $image = file_get_contents($path);
    header('Content-Type: ' . $pic_info['type']);
    echo $image;
    //}
    //else {
    //  header('Location: error.php');
    //}
}