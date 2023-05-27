<?php
    $user = 'root';
    $pass = '12345678';
    $db = '112504';

    $con = mysqli_connect("127.0.0.1", $user, $pass, $db);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);      
    }
?>