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

            $_SESSION['prev_pic_id'] = $this->GetProfilePictureId($avatar_id);

            $disable_old_pic = "UPDATE Pictures SET active='0' WHERE picture_id='$_SESSION[prev_pic_id]' AND profile_pic='1'";
            mysqli_query($this->ObjDBConnection->link, $disable_old_pic);

            $query = "INSERT INTO Pictures VALUES (DEFAULT, '$avatar_id', '$type', '$path', '1', '1', NOW())";
            if (mysqli_query($this->ObjDBConnection->link, $query)) {
                $picture_id = mysqli_insert_id($this->ObjDBConnection->link);

                //$this->AutoCropImage("300", "300", $path, $newName, $type);
                //$this->ObjDBConnection->DBClose();
                return $picture_id;
            }

        } else {
            //$this->ObjDBConnection->DBClose();
            return 0;
        }
    }

    function GetProfilePicture($picture_id)
    {
        $query = "SELECT * FROM Pictures WHERE picture_id = '$picture_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row;
    }

    function GetProfilePictureId($avatar_id)
    {

        $query = "SELECT picture_id FROM Pictures WHERE avatar_id='$avatar_id' AND active='1' AND profile_pic='1'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row['picture_id'];
    }

    public function UploadCoverPicture($form)
    {
        $whiteList = array('token',
            'coverPicture',
            'chkAvatars',
            'btnUpload'
        );

        if ($this->ObjProcessForm->FormPOST($form, 'btnUpload', $whiteList)) {

            if (isset($_FILES['coverPicture']) && $_FILES['coverPicture']['size'] > 0 &&
                preg_match('/png|x-png|jpeg/', $_FILES['coverPicture']['type'])
            ) {

                $type = $_FILES['coverPicture']['type'];
                $extension = explode("/", $type);
                $extension = end($extension);

                // Temporary file name stored on the server
                $tmpName = $_FILES['coverPicture']['tmp_name'];
                $newName = sha1($tmpName) . "." . $extension;
                $path = "/home/kheyosco/images/" . $newName;
                $avatar_id = $_SESSION['avatar_id'];

                $_SESSION['newName'] = $newName;
                $_SESSION['path'] = $path;
                $_SESSION['imgType'] = $extension;

                move_uploaded_file($tmpName, $path);

                $type = $_FILES['coverPicture']['type'];
                $_SESSION['prev_pic_id'] = $this->GetCoverPictureId($avatar_id);

                $avatar_ids = $_POST['chkAvatars'];
                for ($i = 0; $i < count($avatar_ids); $i++) {
                    $prev_pic_id = $this->GetCoverPictureId($avatar_ids[$i]);
                    $disable_old_pic = "UPDATE Pictures SET active='0' WHERE picture_id='$prev_pic_id' AND profile_pic='0'";
                    mysqli_query($this->ObjDBConnection->link, $disable_old_pic);

                    $query = "INSERT INTO Pictures VALUES (DEFAULT, '$avatar_ids[$i]', '$type', '$path', '0', '1', NOW())";
                    mysqli_query($this->ObjDBConnection->link, $query);
                }
                $picture_id = mysqli_insert_id($this->ObjDBConnection->link);
                //$this->ObjDBConnection->DBClose();
                return $picture_id;
            } else {
                //$this->ObjDBConnection->DBClose();
                return 0;
            }

        }
    }

    function GetCoverPictureId($avatar_id)
    {

        $query = "SELECT picture_id FROM Pictures WHERE avatar_id='$avatar_id' AND active='1' AND profile_pic='0'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row['picture_id'];
    }

    function GetPicTimeStamp($picture_id)
    {
        $query = "SELECT dateUploaded FROM Pictures WHERE picture_id='$picture_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row['dateUploaded'];
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
        unset($_SESSION['cover_pic']);
        //$this->ObjDBConnection->DBClose();
        header('Location: create_new_avatar_ready.php');
    }

    function AutoCropImage($width, $height, $path, $newName, $type)
    {

        $tmpPath = 'tmp/' . $newName;
        rename($path, $tmpPath);

        list($img_width, $img_height) = getimagesize($tmpPath);

        if ($img_width == $img_height) {
            //resize the image to 300x300
            $top_left = $width;
            $top_right = $height;
        } else if ($img_width > $img_height) {
            $a = ($img_width - $img_height) / 2;
            $top_left = $a;
            $top_right = $img_width - $a;

        } else {
            //$a = ($img_height - $img_width)/2;
            $top_left = 0;
            $top_right = $img_width;

        }

        echo $top_left . "<br>" . $top_right;


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

        imagecopyresampled($dst_r, $img_r, 0, 0, 300, 300,
            $width, $height, $img_width, $img_height);

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
        unset($_SESSION['cover_pic']);
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

        //$this->ObjDBConnection->DBClose();
        header('Location: edit_avatars.php');
    }

    function GetAllCoverPictureIds($avatar_id)
    {
        $i = 0;
        $query = "SELECT picture_id FROM Pictures WHERE avatar_id='$avatar_id' AND profile_pic='0' ORDER BY dateUploaded DESC";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $picture_ids[$i] = $row['picture_id'];
            $i++;
        }
        //$this->ObjDBConnection->DBClose();
        return $picture_ids;
    }

}