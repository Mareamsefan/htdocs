<?php

$host = "localhost";
$dbname = "sanvac_1";
$username = "MrRobot";
$password = "17%PepsiMaxDrikker";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

return $mysqli;