<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/22/14
 * Time: 12:28 AM
 * Last Edited: 29/1/2015
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
        $avatar_id = addslashes($_POST['avatar_id']);

        $my_avatar_count = $_POST['my_avatar_count'];

        //For unfollow
        for ($i = 0; $i < $my_avatar_count; $i++) {
            $my_avatar_id = addslashes($_POST['my_avatar_id_' . $i]);

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

        //For follow
        for ($i = 0; $i < count($myAvatarIds); $i++) {
			$myAvatarIds[$i]=addslashes($myAvatarIds[$i]);
            $CheckQuery = "SELECT COUNT(*) AS CheckFollow FROM Follow WHERE avatar_id_1='$myAvatarIds[$i]' AND avatar_id_2='$avatar_id'";
            $row = $this->ObjDBConnection->SelectQuery($CheckQuery);

            if ($row['CheckFollow'] == 0) {

                $FollowQuery = "INSERT INTO Follow VALUES (DEFAULT, '$myAvatarIds[$i]', '$avatar_id', NOW())";

                if (!$this->ObjDBConnection->InsertQuery($FollowQuery)) {

                    echo "There seems to be an error. Please try again after sometime.";
                }
            }
        }

        $objAvatar = new Avatars();
        $handle = $objAvatar->GetHandleFromAvatarId($avatar_id);

        //$this->ObjDBConnection->DBClose();
        header('Location: profile.php?handle=' . $handle);
        //}
    }

    public function FollowSameUserAvatars()
    {

        $myAvatarIds = $_POST['my_avatar_ids'];
        $avatar_id = addslashes($_POST['avatar_id']);

        //var_dump($myAvatarIds);
        //echo "<br>";
        //echo $avatar_id;
        $my_avatar_count = $_POST['my_avatar_count'];

        //For unfollow
        for ($i = 0; $i < $my_avatar_count; $i++) {
            $my_avatar_id = addslashes($_POST['my_avatar_id_' . $i]);

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

        //For follow
        for ($i = 0; $i < count($myAvatarIds); $i++) {
			$myAvatarIds[$i]=addslashes($myAvatarIds[$i]);
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

    }

    public function GetMyFollowingAvatars($myAvatarList, $avatar_id)
    {
	$myAvatarList[$i]=addslashes($myAvatarList[$i]);
	$avatar_id=addslashes($avatar_id);
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
	$my_avatar_id=addslashes($my_avatar_id);
	$avatar_id=addslashes($avatar_id);
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
	$avatar_id=addslashes($avatar_id);
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
	$avatar_id=addslashes($avatar_id);
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
	$avatar_id=addslashes($avatar_id);
        $query = "SELECT COUNT(*) AS FollowersCount FROM Follow WHERE avatar_id_2='$avatar_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);
        return $row['FollowersCount'];
    }

    public function MyFollowingCount($avatar_id)
    {
	$avatar_id=addslashes($avatar_id);
        $query = "SELECT COUNT(*) AS FollowingCount FROM Follow WHERE avatar_id_1='$avatar_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);
        return $row['FollowingCount'];
    }


    public function GetAllFollowers($avatar_ids)
    {
		$avatar_ids[$i]=addslashes($avatar_ids[$i]);
        $j = 0;
        //My avatar ids - $avatar_ids
        for ($i = 0; $i < count($avatar_ids); $i++) {
            $query = "SELECT avatar_id_2 FROM Follow WHERE avatar_id_1='$avatar_ids[$i]'";
            $result = mysqli_query($this->ObjDBConnection->link, $query);
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if ($i == 0) {
                    $following_ids[$j] = $row['avatar_id_2'];
                    $j++;
                } else {
                    if (!in_array($row['avatar_id_2'], $following_ids)) {
                        $following_ids[$j] = $row['avatar_id_2'];
                        $j++;
                    }
                }
            }
        }
        return $following_ids;
    }

    public function GetFollowActivity($user_id)
    {
	$user_id=addslashes($user_id);
        $query = "SELECT * FROM Follow
                  WHERE (avatar_id_1 IN
                  (SELECT avatar_id FROM Avatars
                  WHERE user_id='$user_id') AND
                  avatar_id_2 NOT IN
                  (SELECT avatar_id FROM Avatars
                  WHERE user_id='$user_id'))
                  OR
                  (avatar_id_2 IN
                  (SELECT avatar_id FROM Avatars
                  WHERE user_id='$user_id')
                  AND
                  avatar_id_1 NOT IN
                  (SELECT avatar_id FROM Avatars
                  WHERE user_id='$user_id'))
                  ORDER BY time DESC";

        $result = mysqli_query($this->ObjDBConnection->link, $query);

        $i = 0;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $follow_ids[$i] = $row['follow_id'];
            $i++;

        }
        return $follow_ids;

    }

    public function GetFollowActivityForAvatar($avatar_id)
    {
	$avatar_id=addslashes($avatar_id);
        $query = "SELECT * FROM Follow
                  WHERE avatar_id_1 = '$avatar_id'
                  OR
                  avatar_id_2 = '$avatar_id'
                  ORDER BY time DESC";

        $result = mysqli_query($this->ObjDBConnection->link, $query);

        $i = 0;
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $follow_ids[$i] = $row['follow_id'];
            $i++;

        }
        return $follow_ids;

    }

    public function GetFollowTimeStamp($follow_id)
    {
		$follow_id=addslashes($follow_id);
        $query = "SELECT time FROM Follow WHERE follow_id='$follow_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);
        return $row['time'];
    }

    public function GetFollowInfo($follow_id)
    {
		$follow_id=addslashes($follow_id);
        $query = "SELECT * FROM Follow WHERE follow_id = '$follow_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);
        return $row;
    }
}
