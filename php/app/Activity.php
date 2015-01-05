<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/16/14
 * Time: 7:04 PM
 */

require_once 'app/Pictures.php';
require_once 'app/Avatars.php';
require_once 'app/Status.php';
require_once 'app/ProcessForm.php';
require_once 'app/Follow.php';

class Activity
{

    private $ObjProcessForm;
    private $ObjDBConnection;
    private $ObjPictures;
    private $ObjFollow;

    public function __construct()
    {
        $this->ObjDBConnection = new DBConnection();
        $this->ObjDBConnection->DBconnect();
        $this->ObjProcessForm = new ProcessForm();
        $this->ObjPictures = new Pictures();
        $this->ObjStatus = new Status();
        $this->ObjFollow = new Follow();
    }

    public function AvatarActivities($avatar_id)
    {
        $follow_ids = $this->ObjFollow->GetFollowActivityForAvatar($avatar_id);
        $status_ids = $this->ObjStatus->GetAllStatusIds($avatar_id);

        $result = $this->GetActivities($follow_ids, $status_ids);
        return $result;
    }

    function GetActivities($follow_ids, $status_ids)
    {
        $i = 0;
        $j = 0;
        $k = 0;

        while (1) {

            if (count($follow_ids) == 0 && count($status_ids) == 0) {
                break;
            } else if ($follow_ids[$i] != 0 && $status_ids[$j] != 0) {
                $follow_timestamp = $this->ObjFollow->GetFollowTimeStamp($follow_ids[$i]);
                $status_timestamp = $this->ObjStatus->GetStatusTimeStamp($status_ids[$j]);

                //echo "Comparing ".$pic_timestamp." with ";
                //echo $status_timestamp."<br>";

                $ret = $this->CompareTimeStamps($follow_timestamp, $status_timestamp);
                //echo "Return value: ".$ret."<br>";

                if ($ret == "1" || $ret == "0") {

                    $result[$k]['id'] = $follow_ids[$i];
                    $result[$k]['type'] = 0;
                    $k++;
                    $i++;
                    //$result[$k]['id'] = $status_ids[$j];
                    //$result[$k]['type'] = "status";
                } else {
                    $result[$k]['id'] = $status_ids[$j];
                    $result[$k]['type'] = 1;
                    $k++;
                    $j++;
                    //$result[$k]['id'] = $picture_ids[$i];
                    //$result[$k]['type'] = "picture";
                }

            } else if ($follow_ids[$i] != 0 && $status_ids[$j] == 0) {

                $result[$k]['id'] = $follow_ids[$i];
                $result[$k]['type'] = 0;
                $i++;
                $k++;
            } else {

                $result[$k]['id'] = $status_ids[$j];
                $result[$k]['type'] = 1;
                $j++;
                $k++;
            }

            //echo $i."<br>".$j."<br>".count($picture_ids)."<br>".count($status_ids);

            if (($i == count($follow_ids)) && ($j == count($status_ids))) {
                break;
            }

        }

        return $result;
    }

    public function AvatarListActivities()
    {
        $user_id = $_SESSION['user_id'];
        $follow_ids = $this->ObjFollow->GetFollowActivity($user_id);
        $status_ids = $this->ObjStatus->GetAllStatusIdsList($user_id);

        $result = $this->GetActivities($follow_ids, $status_ids);
        return $result;
    }

    function CompareTimeStamps($timestamp1, $timestamp2)
    {

        $dateTime1 = explode(" ", $timestamp1);
        $dateTime2 = explode(" ", $timestamp2);

        $date1 = $dateTime1[0];
        $date2 = $dateTime2[0];

        $ret1 = $this->CompareDates($date1, $date2);

        if ($ret1 == "1" || $ret1 == "2") {
            return $ret1;
        } else {
            $time1 = $dateTime1[1];
            $time2 = $dateTime2[1];

            $ret2 = $this->CompareTimes($time1, $time2);
            return $ret2;
        }
    }

    function CompareDates($date1, $date2)
    {

        $date1 = explode("-", $date1);
        $date2 = explode("-", $date2);

        $day1 = $date1[2];
        $month1 = $date1[1];
        $year1 = $date1[0];

        $day2 = $date2[2];
        $month2 = $date2[1];
        $year2 = $date2[0];

        if ($year1 > $year2) {
            return "1";
        } else if ($year2 > $year1) {
            return "2";
        } else {

            if ($month1 > $month2) {
                return "1";
            } else if ($month2 > $month1) {
                return "2";
            } else {

                if ($day1 > $day2) {
                    return "1";
                } else if ($day2 > $day1) {
                    return "2";
                } else {
                    return "0";
                }
            }
        }
    }

    function CompareTimes($time1, $time2)
    {

        $time1 = explode(":", $time1);
        $time2 = explode(":", $time2);

        $hour1 = $time1[0];
        $minute1 = $time1[1];
        $seconds1 = $time1[2];

        $hour2 = $time2[0];
        $minute2 = $time2[1];
        $seconds2 = $time2[2];

        if ($hour1 > $hour2) {
            return "1";
        } else if ($hour2 > $hour1) {
            return "2";
        } else {
            if ($minute1 > $minute2) {
                return "1";
            } else if ($minute2 > $minute1) {
                return "2";
            } else {
                if ($seconds1 > $seconds2) {
                    return "1";
                } else if ($seconds2 > $seconds1) {
                    return "2";
                } else {
                    return "0";
                }
            }
        }
    }
}


function UnusedGetActivities($picture_ids, $status_ids)
{
    $i = 0;
    $j = 0;
    $k = 0;

    while (1) {

        if (count($picture_ids) == 0 && count($status_ids) == 0) {
            break;
        } else if ($picture_ids[$i] != 0 && $status_ids[$j] != 0) {
            $pic_timestamp = $this->ObjPictures->GetPicTimeStamp($picture_ids[$i]);
            $status_timestamp = $this->ObjStatus->GetStatusTimeStamp($status_ids[$j]);

            //echo "Comparing ".$pic_timestamp." with ";
            //echo $status_timestamp."<br>";

            $ret = $this->CompareTimeStamps($pic_timestamp, $status_timestamp);
            //echo "Return value: ".$ret."<br>";

            if ($ret == "1" || $ret == "0") {

                $result[$k]['id'] = $picture_ids[$i];
                $result[$k]['type'] = 0;
                $k++;
                $i++;
                //$result[$k]['id'] = $status_ids[$j];
                //$result[$k]['type'] = "status";
            } else {
                $result[$k]['id'] = $status_ids[$j];
                $result[$k]['type'] = 1;
                $k++;
                $j++;
                //$result[$k]['id'] = $picture_ids[$i];
                //$result[$k]['type'] = "picture";
            }

        } else if ($picture_ids[$i] != 0 && $status_ids[$j] == 0) {

            $result[$k]['id'] = $picture_ids[$i];
            $result[$k]['type'] = 0;
            $i++;
            $k++;
        } else {

            $result[$k]['id'] = $status_ids[$j];
            $result[$k]['type'] = 1;
            $j++;
            $k++;
        }

        //echo $i."<br>".$j."<br>".count($picture_ids)."<br>".count($status_ids);

        if (($i == count($picture_ids)) && ($j == count($status_ids))) {
            break;
        }

    }

    return $result;
}