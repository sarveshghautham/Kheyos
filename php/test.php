<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/16/14
 * Time: 1:20 AM
 */


//echo $_SERVER['PHP_SELF'];
$pageName = $_SERVER['REQUEST_URI'];
$arr = explode('/', $pageName);
echo $arr[1];