<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/12/14
 * Time: 7:05 PM
 * Last Edited: 29/1/2015
 */

require_once 'app/DBConnection.php';
require_once 'app/ProcessForm.php';
require_once 'app/Pictures.php';

class Status
{

    private $ObjProcessForm;
    private $ObjDBConnection;

    public function __construct()
    {
        $this->ObjDBConnection = new DBConnection();
        $this->ObjDBConnection->DBconnect();
        $this->ObjProcessForm = new ProcessForm();
    }

    public function UpdateStatus($form)
    {
        $whiteList = array('token',
            'txtStatus',
            'radioAvatars',
            'statusPic',
            'selBgColor',
            'selFontColor',
            'btnUpdate'
        );

        if ($this->ObjProcessForm->FormPOST($form, 'btnUpdate', $whiteList)) {

            $avatar_id = addslashes($_POST['radioAvatars']);
            $bgColor = addslashes($_POST['selBgColor']);
            $fontColor = addslashes($_POST['selFontColor']);
            $status = addslashes($_POST['txtStatus']);

            $picture_id = 0;

            if (isset ($_FILES['statusPic'])) {

                $objPictures = new Pictures();
                $picture_id = $objPictures->UploadStatusPicture();
            }

            //for ($i = 0; $i < count($avatar_ids); $i++) {

            $current_active_status_id = $this->GetActiveStatusId($avatar_id);
            $inactive_query = "UPDATE Status SET active = '0' WHERE status_id='$current_active_status_id'";
            mysqli_query($this->ObjDBConnection->link, $inactive_query);

            $query = "INSERT INTO Status VALUES (DEFAULT, '$avatar_id', '$picture_id', '$status', '1', '$bgColor','$fontColor', NOW())";
            //echo $query;
            mysqli_query($this->ObjDBConnection->link, $query);
            //}

            $_SESSION['avatar_id'] = $avatar_id;
            //$this->ObjDBConnection->DBClose();
            header('Location: home.php');

        } else {
            //$this->ObjDBConnection->DBClose();
            echo "Failed";
        }

    }

    function GetStatus($avatar_id)
    {
		$avatar_id=addslashes($avatar_id);
        $query = "SELECT * FROM Status WHERE avatar_id='$avatar_id' AND active='1'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row;
    }

    function GetActiveStatusId($avatar_id)
    {
		$avatar_id=addslashes($avatar_id);
        $query = "SELECT status_id FROM Status WHERE avatar_id='$avatar_id' AND active='1'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row['status_id'];
    }

    function GetAllStatusIds($avatar_id)
    {
		$avatar_id=addslashes($avatar_id);
        $i = 0;
        $query = "SELECT status_id FROM Status WHERE avatar_id='$avatar_id' ORDER BY time DESC";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $status_ids[$i] = $row['status_id'];
            $i++;
        }
        //$this->ObjDBConnection->DBClose();
        return $status_ids;
    }

    function GetStatusTimeStamp($status_id)
    {
		$status_id=addslashes($status_id);
        $query = "SELECT time FROM Status WHERE status_id = '$status_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row['time'];
    }

    function GetStatusInfo($status_id)
    {
		$status_id=addslashes($status_id);
        $query = "SELECT * FROM Status WHERE status_id='$status_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        //$this->ObjDBConnection->DBClose();
        return $row;
    }

    function GetAllStatusIdsList($user_id)
    {

        $i = 0;
		$user_id=addslashes($user_id);
        $query = "SELECT DISTINCT status_id, MAX(time) AS temp FROM Status
                  WHERE (avatar_id IN
                  (SELECT DISTINCT avatar_id_2 FROM Follow
                  WHERE avatar_id_1 IN
                  (SELECT DISTINCT avatar_id FROM Avatars
                  WHERE user_id = '$user_id')) OR
                  avatar_id IN (SELECT DISTINCT avatar_id FROM Avatars
                  WHERE user_id = '$user_id'))
                  AND active='1'
                  GROUP BY avatar_id
                  ORDER BY temp DESC";

        $result = mysqli_query($this->ObjDBConnection->link, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $status_ids[$i] = $row['status_id'];
            $i++;
        }

        //$this->ObjDBConnection->DBClose();
        return $status_ids;
    }

    function GetAvatarId($status_id)
    {
		$status_id=addslashes($status_id);
        $query = "SELECT avatar_id FROM Status WHERE status_id = '$status_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);

        return $row['avatar_id'];
    }
} 
