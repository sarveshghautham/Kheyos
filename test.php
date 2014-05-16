<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/16/14
 * Time: 1:20 AM
 */

$url = '//www.kheyos.com/Kheyos/my_avatars.php';

// Prior to 5.4.7 this would show the path as "//www.example.com/path"
// var_dump(parse_url($url));


//echo $_SERVER['HTTP_HOST'];

echo $_SERVER['REQUEST_URI'];