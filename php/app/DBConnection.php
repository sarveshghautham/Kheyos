<?php

class DBConnection
{

    private $host;
    private $user;
    private $password;
    private $dbName;
    public $link;

    function __construct()
    {
        $this->host = "localhost";
        $this->user = "kheyosco_db";
        $this->password = "8)8@+S#0AWq(";
        $this->dbName = "kheyosco_kheyos";
    }

    function DBconnect()
    {
        $link = mysqli_connect($this->host, $this->user, $this->password, $this->dbName);

        if (!$link) {
            echo "DB Connection failed";
        }
        $this->link = $link;
        @mysqli_select_db($this->link, $this->dbName) or die("Unable to select database");
    }

    function DBClose()
    {
        mysqli_close($this->link);
    }

    function SelectQuery($query)
    {
        $result = mysqli_query($this->link, $query);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($this->link));
            exit();
        }
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        return $row;
    }

    function InsertQuery($query)
    {
        if (mysqli_query($this->link, $query)) {
            return true;
        } else {
            return false;
        }
    }

    function UpdateQuery($query)
    {
        $result = mysqli_query($this->link, $query);
        if ($result) {
            return true;
        } else {
            printf("Error: %s\n", mysqli_error($this->link));
            //exit();
            return false;
        }
    }

    function DeleteQuery($query)
    {
        if (mysqli_query($this->link, $query)) {
            return true;
        } else {
            return false;
        }
    }
}