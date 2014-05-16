<?php

session_start();

class ProcessForm
{

    function __construct()
    {

    }

    public function getRealIp()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) { //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


    public function GenerateFormToken($form)
    {
        // generate a token from an unique value, took from microtime, you can also use salt-values, other crypting methods...
        $token = md5(uniqid(microtime(), true));

        // Write the generated token to the session variable to check it against the hidden field when the form is sent
        $_SESSION[$form . '_token'] = $token;
        return $token;
    }

    public function verifyFormToken($form)
    {

        // check if a session is started and a token is transmitted, if not return an error
        if (!isset($_SESSION[$form . '_token'])) {
            return false;
        }

        // check if the form is sent with token in it
        if (!isset($_POST['token'])) {
            return false;
        }

        // compare the tokens against each other if they are still the same
        if ($_SESSION[$form . '_token'] !== $_POST['token']) {
            return false;
        }

        return true;
    }

    public function writeLog($where)
    {

        $ip = $this->getRealIp(); // Get the IP from superglobal
        $host = gethostbyaddr($ip); // Try to locate the host of the attack
        $date = date("d M Y");

        // create a logging message with php heredoc syntax
        $logging = <<<LOG
                \n
                << Start of Message >>
                There was a hacking attempt on your form. \n 
                Date of Attack: {$date}
                IP-Adress: {$ip} \n
                Host of Attacker: {$host}
                Point of Attack: {$where}
                << End of Message >>
LOG;
        // Awkward but LOG must be flush left

        // open log file
        if ($handle = fopen('hacklog.log', 'a')) {

            fputs($handle, $logging); // write the Data to file
            fclose($handle); // close the file

        } else { // if first method is not working, for example because of wrong file permissions, email the data

            $to = 'ADMIN@gmail.com';
            $subject = 'HACK ATTEMPT';
            $header = 'From: ADMIN@gmail.com';
            if (mail($to, $subject, $logging, $header)) {
                echo "Sent notice to admin.";
            }
        }
    }

    public function FormPOST($form, $postBtn, $whitelist)
    {

        if ($this->verifyFormToken($form)) {

            // CHECK TO SEE IF THIS IS A MAIL POST
            if (isset($_POST[$postBtn])) {

                // Building an array with the $_POST-superglobal
                foreach ($_POST as $key => $item) {

                    // Check if the value $key (fieldname from $_POST) can be found in the whitelisting array, if not, die with a short message to the hacker
                    if (!in_array($key, $whitelist)) {
                        echo $key;
                        $this->writeLog('Unknown form fields');
                        die("Hack-Attempt detected. Please use only the fields in the form");
                    }
                }
            }

            return true;
        } else {

            if (!isset($_SESSION[$form . '_token'])) {

            } else {
                echo "Hack-Attempt detected. Got ya!.";
                $form = $form . "_token";
                $this->writeLog($form);
            }
        }
        return false;
    }
}
