<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/12/14
 * Time: 10:35 AM
 */

session_start();
define ('SITE_ROOT', realpath(dirname(__FILE__)));

require_once 'ProcessForm.php';
require_once 'DBConnection.php';

class Pictures
{

    private $ObjProcessForm;
    private $ObjDBConnection;

    public function __construct()
    {
        $this->ObjDBConnection = new DBConnection();
        $this->ObjDBConnection->DBconnect();
        $this->ObjProcessForm = new ProcessForm();
    }

    public function UploadProfilePicture()
    {

        if (isset($_FILES['profPicture']) && $_FILES['profPicture']['size'] > 0 &&
            preg_match('/png|x-png|jpeg/', $_FILES['profPicture']['type'])
        ) {

            $type = $_FILES['profPicture']['type'];
            $extension = explode("/", $type);
            $extension = end($extension);

            // Temporary file name stored on the server
            $tmpName = $_FILES['profPicture']['tmp_name'];
            $newName = sha1($tmpName) . "." . $extension;
            $path = "/home/kheyosco/images/" . $newName;
            $avatar_id = $_SESSION['avatar_id'];

            $_SESSION['newName'] = $newName;
            $_SESSION['path'] = $path;
            $_SESSION['imgType'] = $extension;

            move_uploaded_file($tmpName, $path);

            $type = $_FILES['profPicture']['type'];

            $_SESSION['prev_pic_id'] = $this->GetPictureId($avatar_id);

            $disable_old_pic = "UPDATE Pictures SET active='0' WHERE avatar_id='$avatar_id'";
            mysqli_query($this->ObjDBConnection->link, $disable_old_pic);

            $query = "INSERT INTO Pictures VALUES (DEFAULT, '$avatar_id', '$type', '$path', '1', '1', NOW())";
            if (mysqli_query($this->ObjDBConnection->link, $query)) {
                $picture_id = mysqli_insert_id($this->ObjDBConnection->link);


                return $picture_id;
            }

        } else {

            return 0;
        }
    }

    function GetPictureId($avatar_id)
    {

        $query = "SELECT picture_id FROM Pictures WHERE avatar_id='$avatar_id' AND active='1'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $row['picture_id'];
    }

    function GetProfilePicture($picture_id)
    {
        $query = "SELECT * FROM Pictures WHERE picture_id = '$picture_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $row;
    }

    function CropImage($width, $height)
    {

        $path = $_SESSION['path'];
        $newName = $_SESSION['newName'];
        $type = $_SESSION['imgType'];
        $tmpPath = 'tmp/' . $newName;
        rename($path, $tmpPath);

        $img_r = "";

        switch ($type) {
            case 'jpeg':
                $img_r = imagecreatefromjpeg($tmpPath);
                break;
            case 'png':
                $img_r = imagecreatefrompng($tmpPath);
                break;
        }

        $dst_r = ImageCreateTrueColor($width, $height);

        imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'],
            $width, $height, $_POST['w'], $_POST['h']);

        switch ($type) {
            case 'jpeg':
                $jpeg_quality = 100;
                header('Content-type: image/jpeg');
                imagejpeg($dst_r, $tmpPath, $jpeg_quality);
                break;
            case 'png':
                $png_quality = 9;
                header('Content-type: image/png');
                imagepng($dst_r, $tmpPath, $png_quality);
                break;
        }

        rename($tmpPath, $path);

        unset($_SESSION['path']);
        unset($_SESSION['newName']);
        unset($_SESSION['imgType']);
        unset($_SESSION['prev_pic_id']);

        header('Location: my_avatars.php');
    }

    function DeletePic($picture_id)
    {
        $query = "DELETE FROM Pictures WHERE picture_id='$picture_id'";
        mysqli_query($this->ObjDBConnection->link, $query);

        $image_path = $_SESSION['path'];
        unlink($image_path);

        $picture_id = $_SESSION['prev_pic_id'];
        $query = "UPDATE Pictures SET active='1' WHERE picture_id='$picture_id'";
        mysqli_query($this->ObjDBConnection->link, $query);

        unset($_SESSION['path']);
        unset($_SESSION['newName']);
        unset($_SESSION['imgType']);
        unset($_SESSION['prev_pic_id']);

        header('Location: edit_avatars.php');
    }
}