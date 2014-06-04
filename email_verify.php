<?php
session_start();

if ($_SESSION['user_id'] != null) {
    header('Location: home.php');
}

require_once 'app/Users.php';

$obj_users = new Users();
//$obj_avatar = new avatar();

//$email = "sarvesh.kaladhar@gmail.com";
$email = $_POST['email'];

if ($obj_users->EmailExists($email)) {
    echo "N";
    //echo "N".$email;
} else {
    echo "Y";
    //  echo "Y".$email;
}