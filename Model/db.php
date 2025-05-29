<?php

    $host = "localhost";
    $port = 3307;
    $dbname = "school_db";
    $dbuser = "root";
    $dbpass = "";

    function getConnection() {
        global $host, $port, $dbname, $dbuser, $dbpass;

        $con = mysqli_connect($host, $dbuser, $dbpass, $dbname, $port);

        if (!$con) {
            die("Cannot connect to database: " . mysqli_connect_error());
        }

        return $con; //db is here git change 2gi
    }

?>
