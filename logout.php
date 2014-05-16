<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/6/14
 * Time: 5:10 PM
 */
session_start();
session_destroy();

header('Location: index.php');