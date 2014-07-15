<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 7/13/14
 * Time: 1:19 PM
 */

session_start();

if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
}

require_once 'app/Users.php';

$objUsers = new Users();
$objUsers->ChangePassword();