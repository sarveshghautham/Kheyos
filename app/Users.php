<?php

session_start();

require_once 'ProcessForm.php';
require_once 'DBConnection.php';
require_once 'Avatars.php';

class Users
{

    private $ObjProcessForm;
    private $ObjDBConnection;
    private $ObjAvatars;

    function __construct()
    {
        $this->ObjDBConnection = new DBConnection();
        $this->ObjDBConnection->DBconnect();
        $this->ObjProcessForm = new ProcessForm();
        $this->ObjAvatars = new Avatars();
    }

    function login($form)
    {

        $whiteList = array('token', 'txtEmail', 'txtPassword', 'btnLogin');

        if ($this->ObjProcessForm->FormPOST($form, 'btnLogin', $whiteList)) {

            $query = "SELECT user_id, salt, password, active FROM Users WHERE email='$_POST[txtEmail]'";
            $result = mysqli_query($this->ObjDBConnection->link, $query);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            //$options['cost'] = 11;
            //$options['salt'] = $row['salt'];
            //$password = password_hash($_POST['txtPassword'], PASSWORD_BCRYPT, $options);

            $salt = $row['salt'];
            $password = crypt($_POST['txtPassword'], '$6$' . $salt);

            if ($row['active'] == 0) {
                header('Location: error.php');
            } else if ($password == $row['password'] && $password != null && $row != null) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['email'] = $_POST['txtEmail'];

                $CountAvatar = $this->ObjAvatars->FirstAvatarCreatedCheck($row['user_id']);

                if ($CountAvatar == 0) {
                    $_SESSION['first_avatar'] = 1;
                    header('Location: create_first_avatar.php');
                } else {
                    header('Location: home.php');
                }
            } else {
                header('Location: login.php');
            }
        } else {
            header('Location: error.php');
        }
    }

    function register($form)
    {

        $whiteList = array('token',
            'txtName',
            'txtEmail',
            'txtPassword',
            'txtRePassword',
            'btnRegister'
        );

        if ($this->ObjProcessForm->FormPOST($form, 'btnRegister', $whiteList)) {

            //$options['cost'] = 11;
            //$options['salt'] = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);

            //$password = password_hash($_POST['txtPassword'], PASSWORD_BCRYPT, $options);

            $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
            $password = addslashes(crypt($_POST['txtPassword'], '$6$' . $salt));

            //$salt = $options['salt'];

            //Input is free from hacks. Now insert into DB.
            $query = "INSERT INTO Users VALUES (DEFAULT, '$_POST[txtEmail]', '$password', '$_POST[txtName]', '$salt', NOW(), NOW(), NOW(), '0')";

            if (mysqli_query($this->ObjDBConnection->link, $query)) {
                //temp session variables
                $_SESSION['registered'] = 1;
                $_SESSION['email'] = $_POST['txtEmail'];
                header('Location: registered.php');
            } else {
                echo mysqli_error($this->ObjDBConnection->link);
            }
        } else {
            //header('Location: error.php');
        }
    }

    function SendConfirmationMail()
    {

        //Construct a link.
        $activation = md5(uniqid(rand(), true));

        $query = "INSERT INTO UnverifiedUsers VALUES ('$_SESSION[email]','$activation', NOW())";

        if (mysqli_query($this->ObjDBConnection->link, $query)) {

            $message = "Click on the link to activate your account \n\n";
            $message .= "http://162.253.224.4/~kheyosco/Kheyos/login_confirmation.php?email=" . $_SESSION['email'] . "&uid=" . $activation;
            $message .= "\n\nRegards, \n";
            $message .= "Kheyos Team";

            //Send a mail with a code. User has to verify.
            $to = $_SESSION['email'];

            $headers = 'From: Kheyos@kheyos.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            //Send mail using PHP mail function. Replace it later with SMTP.
            mail($to, 'Activate your account', $message, $headers);

            //Destroy the temp session variables
            unset($_SESSION['registered']);
            unset($_SESSION['email']);

        } else {
            //TODO: What to do if an error is thrown?

        }
    }

    function ConfirmRegisteration($email, $uid)
    {

        $query = "SELECT activation_code FROM UnverifiedUsers WHERE email ='$email'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($row['activation_code'] == $uid) {

            $query = "UPDATE Users SET active = '1' WHERE email='$email'";
            $result = mysqli_query($this->ObjDBConnection->link, $query);

            $query = "DELETE FROM UnverifiedUsers WHERE email='$email'";
            $result = mysqli_query($this->ObjDBConnection->link, $query);

            return true;
        } else {
            return false;
        }
    }

    function CheckActivation($email, $activation_code)
    {
        $query = "SELECT COUNT(activation_code) AS count_activation_code FROM UnverifiedUsers WHERE email ='$email' AND activation_code='$activation_code'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($row['count_activation_code'] == 0) {
            return false;
        } else {
            return true;
        }
    }
}