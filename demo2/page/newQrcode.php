<?php
    include "../qrcode/phpqrcode.php";
    $param = isset($_GET['data']) ? urldecode($_GET['data']) : 'Test Sample.';
    $codeText = $param;
    QRcode::png($codeText);
?>