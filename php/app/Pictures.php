<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/12/14
 * Time: 10:35 AM
 */

session_start();

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

            $this->CropProfilePicture($newName, $path);

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
		$picture_id=addslashes($picture_id);
        $query = "SELECT * FROM Pictures WHERE picture_id = '$picture_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row;
    }

    function GetProfilePictureId($avatar_id)
    {
		$avatar_id=addslashes($avatar_id);
        $query = "SELECT picture_id FROM Pictures WHERE avatar_id='$avatar_id' AND active='1' AND profile_pic='1'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row['picture_id'];
    }

    public function UploadStatusPicture()
    {
        if (isset($_FILES['statusPic']) && $_FILES['statusPic']['size'] > 0 &&
            preg_match('/png|x-png|jpeg/', $_FILES['statusPic']['type'])
        ) {

            $type = $_FILES['statusPic']['type'];
            $extension = explode("/", $type);
            $extension = end($extension);

            // Temporary file name stored on the server
            $tmpName = $_FILES['statusPic']['tmp_name'];
            $newName = sha1($tmpName) . "." . $extension;
            $path = "/home/kheyosco/images/" . $newName;
            $avatar_id = $_SESSION['avatar_id'];

            $_SESSION['newName'] = $newName;
            $_SESSION['path'] = $path;
            $_SESSION['imgType'] = $extension;

            move_uploaded_file($tmpName, $path);

            $type = addslashes($_FILES['statusPic']['type']);
            $_SESSION['prev_pic_id'] = $this->GetCoverPictureId($avatar_id);

            $avatar_id = addslashes($_POST['radioAvatars']);
            //for ($i = 0; $i < count($avatar_ids); $i++) {
            $prev_pic_id = $this->GetCoverPictureId($avatar_id);
            $disable_old_pic = "UPDATE Pictures SET active='0' WHERE picture_id='$prev_pic_id' AND profile_pic='0'";
            mysqli_query($this->ObjDBConnection->link, $disable_old_pic);

            $query = "INSERT INTO Pictures VALUES (DEFAULT, '$avatar_id', '$type', '$path', '0', '1', NOW())";
            //echo $query;
            mysqli_query($this->ObjDBConnection->link, $query);
            //}
            $picture_id = mysqli_insert_id($this->ObjDBConnection->link);
            //$this->ObjDBConnection->DBClose();
            return $picture_id;
        } else {
            //$this->ObjDBConnection->DBClose();
            return 0;
        }


    }

    function GetCoverPictureId($avatar_id)
    {
		$avatar_id=addslashes($avatar_id);
        $query = "SELECT picture_id FROM Pictures WHERE avatar_id='$avatar_id' AND active='1' AND profile_pic='0'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row['picture_id'];
    }

    function GetPicTimeStamp($picture_id)
    {
		$picture_id=addslashes($picture_id);
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
        header('Location: avatar_ready.php');
    }

    function CropProfilePicture($newName, $path)
    {

        $tmpPath = 'tmp/' . $newName;
        rename($path, $tmpPath);

        list($img_width, $img_height) = getimagesize($tmpPath);

        if ($img_width > $img_height) {
            $new_height = 250;
            $new_width = ($img_width * 250) / $img_height;
        } else if ($img_width < $img_height) {
            $new_width = 250;
            $new_height = ($img_height * 250) / $img_width;
        } else {
            $new_height = 250;
            $new_width = 250;
        }


        $thumb = new Imagick($tmpPath);

        $thumb->resizeImage($new_width, $new_height, Imagick::FILTER_LANCZOS, 1);
        $thumb->writeImage($tmpPath);
        $thumb->destroy();

        rename($tmpPath, $path);

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
		$picture_id=addslashes($picture_id);	
        $query = "DELETE FROM Pictures WHERE picture_id='$picture_id'";
        mysqli_query($this->ObjDBConnection->link, $query);

        $image_path = $_SESSION['path'];
        unlink($image_path);

        $picture_id = addslashes($_SESSION['prev_pic_id']);
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
		$avatar_id=addslashes($avatar_id);
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

    function GetAllCoverPictureIdsList($user_id)
    {
		$user_id=addslashes($user_id);
        $i = 0;

        $query = "SELECT DISTINCT picture_id, MAX(dateUploaded) AS temp FROM Pictures
                  WHERE avatar_id IN
                  (SELECT DISTINCT avatar_id_2 FROM Follow
                  WHERE avatar_id_1 IN
                  (SELECT DISTINCT avatar_id FROM Avatars
                  WHERE user_id = '$user_id')) AND profile_pic='0' AND active='1'
                  GROUP BY avatar_id
                  ORDER BY temp DESC";

        $result = mysqli_query($this->ObjDBConnection->link, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $picture_ids[$i] = $row['picture_id'];
            $i++;
        }

        //$this->ObjDBConnection->DBClose();
        return $picture_ids;
    }

    function GetAvatarId($picture_id)
    {
		$picture_id=addslashes($picture_id);
        $query = "SELECT avatar_id FROM Pictures WHERE picture_id = '$picture_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);

        return $row['avatar_id'];
    }

    function CheckPictureAccess($user_id, $picture_id)
    {
		$picture_id=addslashes($picture_id);
		$user_id=addslashes($user_id);
        $query = "SELECT picture_id FROM Pictures
                  WHERE (avatar_id IN
                  (SELECT avatar_id FROM Avatars
                  WHERE user_id = '$user_id')) OR
                  (SELECT avatar_id_2 FROM Follow
                  WHERE avatar_id_1 IN
                  (SELECT avatar_id FROM Avatars
                  WHERE user_id = '$user_id'))";

        $result = mysqli_query($this->ObjDBConnection->link, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            if ($row['picture_id'] == $picture_id) {
                //$this->ObjDBConnection->DBClose();
                return true;
            }
        }

        //$this->ObjDBConnection->DBClose();
        return false;

    }

    public function UnusedUploadCoverPicture($form)
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

                $type = addslashes($_FILES['coverPicture']['type']);
                $_SESSION['prev_pic_id'] = $this->GetCoverPictureId($avatar_id);

                $avatar_ids = addslashes($_POST['chkAvatars']);
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


}
