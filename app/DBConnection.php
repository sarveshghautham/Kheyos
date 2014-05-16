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
        mysqli_close($this->connection);
    }
}