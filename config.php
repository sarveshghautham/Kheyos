<?php
//Setup a db connection.

// Two options for connecting to the database:
define('HOST_DIRECT', 'localhost'); // Standard connection
// Only username and password are encrypted

define('DB_HOST', HOST_DIRECT); // Choose HOST_DIRECT or HOST_STUNNEL, depending on your application's requirements

define('DB_USER', 'root'); // MySQL account username
define('DB_PASS', 'nextpage'); // MySQL account password
define('DB_NAME', 'Kheyos'); // Name of database

// Connect to the database
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$db) {
    // Handle error
    echo "<p>Unable to connect to database</p>";
}
	