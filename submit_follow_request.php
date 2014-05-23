<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/22/14
 * Time: 12:58 AM
 */

session_start();

if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
} else {

    require_once 'app/Follow.php';


}