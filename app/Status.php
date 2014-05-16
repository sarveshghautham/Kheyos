<?php
/**
 * Created by PhpStorm.
 * User: sarvesh
 * Date: 5/12/14
 * Time: 7:05 PM
 */

require_once 'app/DBConnection.php';
require_once 'app/ProcessForm.php';

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
            'chkAvatars',
            'btnUpdate'
        );

        if ($this->ObjProcessForm->FormPOST($form, 'btnUpdate', $whiteList)) {
            $avatar_ids = $_POST['chkAvatars'];
            //          var_dump($avatar_ids);
            //        echo "<br>".count($avatar_ids);

            for ($i = 0; $i < count($avatar_ids); $i++) {

                $inactive_query = "UPDATE Status SET active = '0' WHERE avatar_id='$avatar_ids[$i]'";
                mysqli_query($this->ObjDBConnection->link, $inactive_query);

                $query = "INSERT INTO Status VALUES (DEFAULT, '$avatar_ids[$i]', '$_POST[txtStatus]', '1', NOW())";
//                echo $query;
                mysqli_query($this->ObjDBConnection->link, $query);
            }
            $_SESSION['avatar_id'] = $avatar_ids[0];
            header('Location: my_avatars.php');
        } else {
            echo "Failed";
        }
    }

    function GetStatus($avatar_id)
    {

        $query = "SELECT * FROM Status WHERE avatar_id='$avatar_id' AND active='1'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $row;
    }

} 