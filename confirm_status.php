<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/12/14
 * Time: 11:58 PM
 */
session_start();

if ($_SESSION['user_id'] == null) {
    header('Location: login.php');
} else {
    require_once 'app/Status.php';

    $objStatus = new Status();
    $objStatus->UpdateStatus("FormUpdateStatus");
}