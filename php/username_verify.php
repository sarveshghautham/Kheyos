<?php
session_start();

if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
}

require_once 'app/Avatars.php';

$ObjAvatars = new Avatars();
//$obj_avatar = new avatar();

if ($ObjAvatars->UsernameExists($_POST['username'])) {
    echo "N";
} else {
    echo "Y";
}
