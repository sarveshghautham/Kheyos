<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/16/14
 * Time: 1:20 AM
 */
list($width, $height, $type, $attr) = getimagesize("img/batman-large.png  ");

echo "Image width " . $width;
echo "Image height " . $height;
echo "Image type " . $type;
echo "Attribute " . $attr;
