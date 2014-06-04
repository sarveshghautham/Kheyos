<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/25/14
 * Time: 9:23 PM
 */

session_start();

if ($_SESSION['user_id'] != null) {
    header('Location: home.php');
}

require_once 'app/Users.php';
$objUsers = new Users();
$objUsers->login('LoginForm');
