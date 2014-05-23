<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/22/14
 * Time: 12:28 AM
 */

session_start();

require_once 'ProcessForm.php';
require_once 'DBConnection.php';
require_once 'Avatars.php';
require_once 'Pictures.php';

class Follow
{

    private $ObjProcessForm;
    private $ObjDBConnection;

    public function __construct()
    {
        $this->ObjDBConnection = new DBConnection();
        $this->ObjDBConnection->DBconnect();
        $this->ObjProcessForm = new ProcessForm();
    }

    public function StartFollowing($form)
    {

        $whiteList = array('token',
            'my_avatar_ids',
            'avatar_id',
            'btnFollow'
        );

        //if ($this->ObjProcessForm->FormPOST($form, 'btnFollow', $whiteList)) {

        $myAvatarIds = $_POST['my_avatar_ids'];
        $avatar_id = $_POST['avatar_id'];

        $my_avatar_count = $_POST['my_avatar_count'];

        for ($i = 0; $i < $my_avatar_count; $i++) {
            $my_avatar_id = $_POST['my_avatar_id_' . $i];

            if ($myAvatarIds != null) {
                if (!in_array($my_avatar_id, $myAvatarIds)) {

                    $delQuery = "DELETE FROM Follow WHERE avatar_id_1='$my_avatar_id' AND avatar_id_2='$avatar_id'";
                    if (!$this->ObjDBConnection->DeleteQuery($delQuery)) {
                        echo "There seems to be an error";
                    }

                }
            } else {
                $delQuery = "DELETE FROM Follow WHERE avatar_id_1='$my_avatar_id' AND avatar_id_2='$avatar_id'";
                if (!$this->ObjDBConnection->DeleteQuery($delQuery)) {
                    echo "There seems to be an error";
                }
            }
        }

        for ($i = 0; $i < count($myAvatarIds); $i++) {

            $CheckQuery = "SELECT COUNT(*) AS CheckFollow FROM Follow WHERE avatar_id_1='$myAvatarIds[$i]' AND avatar_id_2='$avatar_id'";
            $row = $this->ObjDBConnection->SelectQuery($CheckQuery);

            if ($row['CheckFollow'] == 0) {

                $FollowQuery = "INSERT INTO Follow VALUES (DEFAULT, '$myAvatarIds[$i]', '$avatar_id', NOW())";

                if (!$this->ObjDBConnection->InsertQuery($FollowQuery)) {

                    echo "There seems to be an error. Please try again after sometime.";
                }
            }
        }

        //$this->ObjDBConnection->DBClose();
        header('Location: profile.php?avatar_id=' . $avatar_id);
        //}
    }

    public function GetMyFollowingAvatars($myAvatarList, $avatar_id)
    {
        $j = 0;
        for ($i = 0; $i < count($myAvatarList); $i++) {
            $query = "SELECT COUNT(*) AS Following FROM Follow WHERE (avatar_id_1='$myAvatarList[$i]' AND avatar_id_2='$avatar_id')";
            $row = $this->ObjDBConnection->SelectQuery($query);

            if ($row['Following'] != 0) {
                $following_avatar_ids[$j] = $myAvatarList[$i];
                $j++;
            }
        }
        //$this->ObjDBConnection->DBClose();
        return $following_avatar_ids;
    }

    function FollowingFrom($avatar_id, $my_avatar_id)
    {
        $j = 0;
        $query = "SELECT avatar_id_1 FROM Follow WHERE avatar_id_1='$my_avatar_id' AND avatar_id_2='$avatar_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $avatar_ids[$j] = $row['avatar_id_1'];
            $j++;
        }

        return $avatar_ids;
    }

    public function GetMyFollowers($avatar_id)
    {

        $j = 0;
        $query = "SELECT avatar_id_1 FROM Follow WHERE avatar_id_2='$avatar_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $avatar_ids[$j] = $row['avatar_id_1'];
            $j++;
        }

        return $avatar_ids;

    }

    public function GetMyFollowing($avatar_id)
    {

        $j = 0;
        $query = "SELECT avatar_id_2 FROM Follow WHERE avatar_id_1='$avatar_id'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $avatar_ids[$j] = $row['avatar_id_2'];
            $j++;
        }

        return $avatar_ids;

    }

    public function MyFollowersCount($avatar_id)
    {

        $query = "SELECT COUNT(*) AS FollowersCount FROM Follow WHERE avatar_id_2='$avatar_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);
        return $row['FollowersCount'];
    }

    public function MyFollowingCount($avatar_id)
    {

        $query = "SELECT COUNT(*) AS FollowingCount FROM Follow WHERE avatar_id_1='$avatar_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);
        return $row['FollowingCount'];
    }

} 