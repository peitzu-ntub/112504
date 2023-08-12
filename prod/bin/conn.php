<?php
    //如果資料庫沒辦法正常登入的話，就把這行貼在workbench執行
    //ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '12345678';

    $user = 'root';
    $pass = '12345678';
    $db = '112504';

    $con = mysqli_connect("127.0.0.1", $user, $pass, $db);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);      
    }
?>