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
require_once 'Follow.php';

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

    public function CreateNewAvatar($form)
    {
        $whiteList = array('token',
            'profPicture',
            'txtName',
            'txtHandle',
            'txtBio',
            'btnCreate'

        );

        //var_dump($_POST);

        //if ($this->ObjProcessForm->FormPOST($form, 'btnCreate', $whiteList)) {

        $user_id = $_SESSION['user_id'];
        $bio = addslashes($_POST['txtBio']);

        //Input is free from hacks. Now insert into DB.
        $query = "INSERT INTO Avatars VALUES (DEFAULT, '$user_id', '$_POST[txtName]', '$_POST[txtHandle]', '$bio', NOW())";

        if (mysqli_query($this->ObjDBConnection->link, $query)) {

            $avatar_id = mysqli_insert_id($this->ObjDBConnection->link);
            $_SESSION['avatar_id'] = $avatar_id;
            //$this->ObjDBConnection->DBClose();

            $objPictures = new Pictures();
            $picture_id = $objPictures->UploadProfilePicture();

            $_SESSION['picture_id'] = $picture_id;

            //if ($picture_id != 0) {
            //$this->ObjDBConnection->DBClose();
            //header('Location: crop.php');
            //}
            //else {
            if (isset($_SESSION['first_avatar'])) {
                header('Location: ready.php');
            } else {
                header('Location: avatar_ready.php');
            }
            //}
        } else {
            //$this->ObjDBConnection->DBClose();
            echo mysqli_error($this->ObjDBConnection->link);
        }
        //} else {
        //$this->ObjDBConnection->DBClose();
        //    header('Location: error.php');
        //}
    }


    function EditAvatar($form)
    {

        //var_dump($_POST);

        $whiteList = array('token',
            'profPicture',
            'txtName',
            'txtBio',
            'avatar_id',
            'my_avatar_count',
            'my_avatar_ids',
            'btnEdit'
        );

        $my_avatar_count = $_POST['my_avatar_count'];
        for ($i = 0; $i < $my_avatar_count; $i++) {

            $my_avatar_id = "my_avatar_id_" . $i;
            array_push($whiteList, $my_avatar_id);
        }

        //var_dump($whiteList);
        //echo "<br>";
        //var_dump($_POST);

        if ($this->ObjProcessForm->FormPOST($form, 'btnEdit', $whiteList)) {

            $avatar_id = $_POST['avatar_id'];
            $_SESSION['avatar_id'] = $avatar_id;

            $objPictures = new Pictures();
            $picture_id = $objPictures->UploadProfilePicture();

            $bio = addslashes($_POST['txtBio']);

            // Input is free from hacks. Now insert into DB.
            $query = "UPDATE Avatars SET name = '$_POST[txtName]', bio = '$bio' WHERE avatar_id='$avatar_id'";
            if (mysqli_query($this->ObjDBConnection->link, $query)) {

                $objFollow = new Follow();
                $objFollow->FollowSameUserAvatars();

                if ($picture_id != 0) {
                    $_SESSION['picture_id'] = $picture_id;
                    //$this->ObjDBConnection->DBClose();
                    //header('Location: crop.php');

                }
                //$this->ObjDBConnection->DBClose();
                header('Location: my_avatars.php');

            } else {
                //$this->ObjDBConnection->DBClose();
                echo mysqli_error($this->ObjDBConnection->link);
            }

        } else {
            //$this->ObjDBConnection->DBClose();
            header('Location: error.php');
        }
    }


    function FirstAvatarCreatedCheck($user_id)
    {
        $query = "SELECT COUNT(avatar_id) AS AvatarCount FROM Avatars WHERE user_id = '$user_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        //$this->ObjDBConnection->DBClose();
        return $row['AvatarCount'];
    }

    function GetAvatarInfo($avatar_id)
    {

        $query = "SELECT * FROM Avatars WHERE avatar_id = '$avatar_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
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

        //$this->ObjDBConnection->DBClose();
        return $avatar_ids;
    }

    function GetHandle($avatar_id)
    {
        $query = "SELECT handle FROM Avatars WHERE avatar_id='$avatar_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row['handle'];
    }

    function GetUserId($avatar_id)
    {
        $query = "SELECT user_id FROM Avatars WHERE avatar_id='$avatar_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row['user_id'];
    }

    function UsernameExists($handle)
    {
        $query = "SELECT COUNT(handle) AS EntryCount FROM Avatars WHERE handle='$handle'";
        $row = $this->ObjDBConnection->SelectQuery($query);

        if ($row['EntryCount'] == 0) {
            return false;
        } else {
            return true;
        }
    }

    function GetAvatarIdFromHandle($handle)
    {
        $query = "SELECT avatar_id FROM Avatars WHERE handle='$handle'";
        $row = $this->ObjDBConnection->SelectQuery($query);
        return $row['avatar_id'];
    }

    function GetHandleFromAvatarId($avatar_id)
    {
        $query = "SELECT handle FROM Avatars WHERE avatar_id='$avatar_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);
        return $row['handle'];
    }
}