<?php
session_start();

if ($_SESSION['user_id'] != null) {
    header('Location: home.php');
}

require_once 'app/Users.php';

$obj_users = new Users();
//$obj_avatar = new avatar();

$obj_users->AuthVerify();
