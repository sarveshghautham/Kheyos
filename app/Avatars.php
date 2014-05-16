<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/7/14
 * Time: 9:04 AM
 */

session_start();

require_once 'ProcessForm.php';
require_once 'DBConnection.php';
require_once 'Pictures.php';

class Avatars
{

    private $ObjProcessForm;
    private $ObjDBConnection;

    public function __construct()
    {
        $this->ObjDBConnection = new DBConnection();
        $this->ObjDBConnection->DBconnect();
        $this->ObjProcessForm = new ProcessForm();
    }

    public function CreateFirstAvatar($form)
    {

        $whiteList = array('token',
            'profPicture',
            'txtName',
            'txtHandle',
            'txtBio',
            'txtDate',
            'txtLocation',
            'btnCreate'
        );

        if ($this->ObjProcessForm->FormPOST($form, 'btnCreate', $whiteList)) {

            $user_id = $_SESSION['user_id'];
            $bio = addslashes($_POST['txtBio']);

            //Input is free from hacks. Now insert into DB.
            $query = "INSERT INTO Avatars VALUES (DEFAULT, '$user_id', '$_POST[txtName]', '$_POST[txtHandle]', '$bio', '$_POST[txtDate]', '$_POST[txtLocation]', NOW())";

            if (mysqli_query($this->ObjDBConnection->link, $query)) {

                $avatar_id = mysqli_insert_id($this->ObjDBConnection->link);
                $_SESSION['avatar_id'] = $avatar_id;

                $objPictures = new Pictures();
                $picture_id = $objPictures->UploadProfilePicture();

                if ($picture_id != 0) {

                    $_SESSION['picture_id'] = $picture_id;
                    unset ($_SESSION['first_avatar']);
                    header('Location: crop.php');

                } else {
                    header('Location: create_new_avatar_ready.php');
                }
            } else {
                echo mysqli_error($this->ObjDBConnection->link);
            }
        } else {
            header('Location: error.php');
        }
    }

    public function CreateNewAvatar($form)
    {

        $whiteList = array('token',
            'profPicture',
            'txtName',
            'txtHandle',
            'txtBio',
            'txtDate',
            'txtLocation',
            'btnCreate'
        );

        if ($this->ObjProcessForm->FormPOST($form, 'btnCreate', $whiteList)) {

            $user_id = $_SESSION['user_id'];
            $bio = addslashes($_POST['txtBio']);

            //Input is free from hacks. Now insert into DB.
            $query = "INSERT INTO Avatars VALUES (DEFAULT, '$user_id', '$_POST[txtName]', '$_POST[txtHandle]', '$bio', '$_POST[txtDate]', '$_POST[txtLocation]', NOW())";

            if (mysqli_query($this->ObjDBConnection->link, $query)) {

                $avatar_id = mysqli_insert_id($this->ObjDBConnection->link);
                $_SESSION['avatar_id'] = $avatar_id;

                $objPictures = new Pictures();
                $picture_id = $objPictures->UploadProfilePicture();

                if ($picture_id != 0) {

                    $_SESSION['picture_id'] = $picture_id;
                    header('Location: crop.php');

                } else {
                    header('Location: create_new_avatar_ready.php');
                }
            } else {
                echo mysqli_error($this->ObjDBConnection->link);
            }
        } else {
            header('Location: error.php');
        }
    }

    function EditAvatar($form)
    {

        $whiteList = array('token',
            'profPicture',
            'txtName',
            'txtHandle',
            'txtBio',
            'txtDate',
            'txtLocation',
            'avatar_id',
            'btnEdit'
        );


        if ($this->ObjProcessForm->FormPOST($form, 'btnEdit', $whiteList)) {

            $avatar_id = $_POST['avatar_id'];
            $_SESSION['avatar_id'] = $avatar_id;

            $objPictures = new Pictures();
            $picture_id = $objPictures->UploadProfilePicture();

            $bio = addslashes($_POST['txtBio']);

            //Input is free from hacks. Now insert into DB.
            $query = "UPDATE Avatars SET name = '$_POST[txtName]', handle = '$_POST[txtHandle]', bio = '$bio', dob='$_POST[txtDate]', location='$_POST[txtLocation]' WHERE avatar_id='$avatar_id'";
            if (mysqli_query($this->ObjDBConnection->link, $query)) {
                if ($picture_id != 0) {
                    $_SESSION['picture_id'] = $picture_id;
                    header('Location: crop.php');
                } else {
                    header('Location: my_avatars.php');
                }
            } else {
                echo mysqli_error($this->ObjDBConnection->link);
            }

        } else {
            header('Location: error.php');
        }
    }


    function FirstAvatarCreatedCheck($user_id)
    {
        $query = "SELECT COUNT(avatar_id) AS AvatarCount FROM Avatars WHERE user_id = '$user_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $row['AvatarCount'];
    }

    function GetAvatarInfo($avatar_id)
    {

        $query = "SELECT * FROM Avatars WHERE avatar_id = '$avatar_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $row;
    }

    function AvatarList($user_id)
    {

        $query = "SELECT avatar_id FROM Avatars WHERE user_id='$user_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $i = 0;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            $avatar_ids[$i] = $row['avatar_id'];
            $i++;
        }

        return $avatar_ids;
    }

    function GetHandle($avatar_id)
    {
        $query = "SELECT handle FROM Avatars WHERE avatar_id='$avatar_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $row['handle'];
    }

    function CheckPictureAccess($user_id, $picture_id)
    {

        $query = "SELECT picture_id FROM Avatars WHERE user_id = '$user_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            if ($row['picture_id'] == $picture_id) {
                return true;
            }
        }

        return false;

    }

} 