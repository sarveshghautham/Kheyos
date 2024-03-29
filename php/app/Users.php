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

    function AuthVerify()
    {	//update validating inputs to prevent sql injection
        $email = addslashes($_POST['email']);
        $password = $_POST['password'];

        if ($email == null || $password == null) {
            echo "N";
        } else if ($this->EmailExists($email)) {

            $query = "SELECT password, salt FROM Users WHERE email='$email'";
            $row = $this->ObjDBConnection->SelectQuery($query);

            $salt = $row['salt'];
            $password = crypt($password, '$6$' . $salt);

            if ($password == $row['password'] && $password != null && $row != null) {
                echo "Y";
            } else {
                echo "N";
            }
        } else {
            echo "X";
        }
    }
    
    function login($form)
    {

        $whiteList = array('token', 'txtEmail', 'txtPassword', 'btnLogin');

        if ($this->ObjProcessForm->FormPOST($form, 'btnLogin', $whiteList)) {
			//update validating inputs to prevent sql injection
            $email = addslashes($_POST['txtEmail']);
            if (!$this->EmailExists($email)) {
                header('Location: login.php');
            } else {

                $query = "SELECT user_id, salt, password, active FROM Users WHERE email='$email'";
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
                    $_SESSION['email'] = $_POST['txtEmail']);

                    $CountAvatar = $this->ObjAvatars->FirstAvatarCreatedCheck($row['user_id']);

                    if ($CountAvatar == 0) {
                        $_SESSION['first_avatar'] = 1;
                        header('Location: create_first_avatar.php');
                    } else {
                        //$this->ObjDBConnection->DBClose();
                        header('Location: home.php');
                    }
                } else {
                    //$this->ObjDBConnection->DBClose();
                    header('Location: login.php');
                }
            }
        } else {
            //$this->ObjDBConnection->DBClose();
            header('Location: error.php');
        }
    }

    function LoginFirst($form)
    {
        $whiteList = array('token', 'txtEmail', 'txtPassword', 'uid', 'btnLogin');

        if ($this->ObjProcessForm->FormPOST($form, 'btnLogin', $whiteList)) {
			//update validating inputs to prevent sql injection
			$email=addslashes($_POST[txtEmail]);
            $query = "SELECT user_id, salt, password, active FROM Users WHERE email='$email'";
            $result = mysqli_query($this->ObjDBConnection->link, $query);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

            //$options['cost'] = 11;
            //$options['salt'] = $row['salt'];
            //$password = password_hash($_POST['txtPassword'], PASSWORD_BCRYPT, $options);

            $salt = $row['salt'];
            $password = crypt($_POST['txtPassword'], '$6$' . $salt);

            if ($password == $row['password'] && $password != null && $row != null) {

                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['email'] = $_POST['txtEmail'];
                if ($this->ConfirmRegistration($_POST['txtEmail'], $_POST['uid'])) {
                    $_SESSION['first_avatar'] = 1;
                    //$this->ObjDBConnection->DBClose();
                    header('Location: create_first_avatar.php');
                } else {
                    echo "Something wrong";
                    //$this->ObjDBConnection->DBClose();
                }

            } else {
                //$this->ObjDBConnection->DBClose();
                header('Location: login.php');
            }
        } else {
            //$this->ObjDBConnection->DBClose();
            header('Location: error.php');
        }
    }

    function register($form)
    {

        $whiteList = array('token',
            'txtEmail',
            'txtPassword',
            'txtRePassword',
            'btnRegister'
        );
		//update
		//Validate email
        if ($this->ObjProcessForm->FormPOST($form, 'btnRegister', $whiteList) && filter_var($_POST['txtEmail'], FILTER_VALIDATE_EMAIL)) {

            //$options['cost'] = 11;
            //$options['salt'] = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);

            //$password = password_hash($_POST['txtPassword'], PASSWORD_BCRYPT, $options);

            $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
            $password = addslashes(crypt($_POST['txtPassword'], '$6$' . $salt));

            //$salt = $options['salt'];

            //Input is free from hacks. Now insert into DB.
			//Inputs is free from sql injection
			//update			
			$email=addslashes($_POST['txtEmail']);
            $query = "INSERT INTO Users VALUES (DEFAULT, '$email', '$password', '$salt', NOW(), NOW(), NOW(), '0')";

            if (mysqli_query($this->ObjDBConnection->link, $query)) {
                //temp session variables
                $_SESSION['registered'] = 1;
                $_SESSION['email'] = $_POST['txtEmail'];
                //$this->ObjDBConnection->DBClose();
                header('Location: registered.php');
            } else {
                //$this->ObjDBConnection->DBClose();
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
		//Validating session values 
		//update
		$email_session=addslashes($_SESSION[email]);
        $query = "INSERT INTO UnverifiedUsers VALUES ('$email_session','$activation', NOW())";

        if (mysqli_query($this->ObjDBConnection->link, $query)) {

            $message = "Click on the link to activate your account \n\n";
            $message .= "http://www.kheyos.com/login_confirmation.php?email=" . $_SESSION['email'] . "&uid=" . $activation;
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

            //$this->ObjDBConnection->DBClose();

        } else {
            //TODO: What to do if an error is thrown?

        }
    }

    function ConfirmRegistration($email, $uid)
    {
		//update
		$email=addslashes($email);
        $query = "SELECT activation_code FROM UnverifiedUsers WHERE email ='$email'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($row['activation_code'] == $uid) {

            $query = "UPDATE Users SET active = '1' WHERE email='$email'";
            mysqli_query($this->ObjDBConnection->link, $query);

            $del_query = "DELETE FROM UnverifiedUsers WHERE email='$email'";
            mysqli_query($this->ObjDBConnection->link, $del_query);

            //$this->ObjDBConnection->DBClose();

            return true;
        } else {
            //$this->ObjDBConnection->DBClose();
            return false;
        }
    }

    function CheckActivation($email, $activation_code)
    {
		//update
		$email=addslashes($email);
		$activation_code=addslashes($activation_code);
        $query = "SELECT COUNT(activation_code) AS count_activation_code FROM UnverifiedUsers WHERE email ='$email' AND activation_code='$activation_code'";
        $result = mysqli_query($this->ObjDBConnection->link, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($row['count_activation_code'] == 0) {
            //$this->ObjDBConnection->DBClose();
            return false;
        } else {
            //$this->ObjDBConnection->DBClose();
            return true;
        }
    }

    function ForgotPassword()
    {

//        $email = filter_input($_POST['email'], FILTER_SANITIZE_EMAIL);
        $email = addslashes($_POST['email']);
        if ($email == null) {
            echo "N";
        } else {

            if ($this->EmailExists($email) == false) {
                echo "N";
            } else {

                $code = md5(uniqid(rand(), true));

                $query = "INSERT INTO ResetPasswordUsers VALUES ('$email', '$code', NOW())";
                $this->ObjDBConnection->InsertQuery($query);

                $message = "Clink on the <a href=http://www.kheyos.com/reset_password.php?email=" . $email . "&code=" . $code . "> link </a> to reset your password. \n\n";
                $message .= "\n\nRegards, \n";
                $message .= "Kheyos Team";

                //Send a mail with a code. User has to verify.
                $to = $email;

                $headers = 'From: Kheyos@kheyos.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                //Send mail using PHP mail function. Replace it later with SMTP.
                mail($to, 'Reset your password', $message, $headers);

                echo "Y";
                //header ('Location: login.php');
            }
        }
    }

    function EmailExists($email)
    {
		//update
		$email=addslashes($email);
        $query = "SELECT COUNT(*) AS EntryCount FROM Users WHERE email='$email'";
        $row = $this->ObjDBConnection->SelectQuery($query);

        if ($row['EntryCount'] == 0) {
            return false;
        } else {
            return true;
        }
    }

    function CodeVerify()
    {
		//update
        $email = addslashes($_POST['email']);
        $code = addslashes($_POST['code']);

        if ($code == null || $email == null) {
            echo "N";
        } else {
            $query = "SELECT reset_code FROM ResetPasswordUsers WHERE email='$email'";
            $row = $this->ObjDBConnection->SelectQuery($query);

            if ($row['reset_code'] == $code) {
                echo "Y";
            } else {
                echo "N";
            }
        }
    }

    function ChangePassword()
    {
        $user_id = $_SESSION['user_id'];
        $email = $this->GetEmail($user_id);
        $oldPass = $_POST['oldPass'];

        if ($this->Auth($email, $oldPass)) {
            $newPass = $_POST['newPass'];
            $rePass = $_POST['rePass'];

            //echo $newPass."--- ".$rePass;

            if ($newPass == $rePass && $newPass != null && $rePass != null) {

                $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
                $newPass = addslashes(crypt($newPass, '$6$' . $salt));
				//update
				$email=addslashes($email);
                $query = "UPDATE Users SET password='$newPass', salt='$salt' WHERE email='$email'";
                if ($this->ObjDBConnection->UpdateQuery($query)) {
                    echo "Y";
                } else {
                    echo "N";
                }
            } else {
                echo "N";
            }
        } else {
            echo "N";
        }
    }

    function UpdatePassword()
    {
		
		//update
        $email = addslashes($_POST['email']);
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
		//update
		$user_id = $_SESSION['user_id'];
        if ($password != $repassword || $password == null || $email == null || $repassword == null) {
            echo "N";
        } else {

            $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
            $password = addslashes(crypt($password, '$6$' . $salt));
			//update
			//Preventing users from changing other user's password.
            $query = "UPDATE Users SET password = '$password', salt = '$salt' WHERE email='$email' and user_id='$user_id'";
            if ($this->ObjDBConnection->UpdateQuery($query)) {
                $del_query = "DELETE FROM ResetPasswordUsers WHERE email='$email'";
                if ($this->ObjDBConnection->DeleteQuery($del_query)) {
                    echo "Y";
                } else {
                    echo "N";
                }
            } else {
                echo "N";
            }
        }
    }

    function CheckResetCode($email, $code)
    {
		//update
		$email=addslashes($email);
        $query = "SELECT reset_code FROM ResetPasswordUsers WHERE email='$email'";
        $row = $this->ObjDBConnection->SelectQuery($query);

        if ($row['reset_code'] == $code) {
            return true;
        } else {
            return false;
        }

    }

    function GetEmail($user_id)
    {
		
        $query = "SELECT email FROM Users WHERE user_id='$user_id'";
        $row = $this->ObjDBConnection->SelectQuery($query);

        return $row['email'];
    }

    function Auth($email, $password)
    {   //update
		$email=addslashes($email);
        if ($email == null || $password == null) {
            return false;
        } else if ($this->EmailExists($email)) {

            $query = "SELECT password, salt FROM Users WHERE email='$email'";
            $row = $this->ObjDBConnection->SelectQuery($query);

            $salt = $row['salt'];
            $password = addslashes(crypt($password, '$6$' . $salt));

            if ($password == $row['password'] && $password != null && $row != null) {
                return true;
            } else {
                //echo "Returning false";
                return false;
            }
        } else {
            return false;
        }
    }

    function ChangeEmail()
    {
		//update
        $oldEmail = addslashes($_POST['oldEmail']);
        $newEmail = addslashes($_POST['email']);
        $password = $_POST['password'];
		//update
		$user_id = $_SESSION['user_id'];
        if ($this->Auth($oldEmail, $password)) {
			//update
			//preventing users from taking control of other user's account through their email ID.
            $query = "UPDATE Users SET email='$newEmail' WHERE email='$oldEmail' and user_id='$user_id'";
            if ($this->ObjDBConnection->UpdateQuery($query)) {
                echo "Y";
            }
        } else {
            echo "N";
        }
    }
}
