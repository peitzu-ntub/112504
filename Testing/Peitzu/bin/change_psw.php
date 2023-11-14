<?php
    include "conn.php";

    $data= array();
  
    try {
        $boss_identity = $_POST['boss_identity'];
        $boss_psw = $_POST['newPassword'];

        //檢查老闆是否已經存在
        //1.產生查詢字串
        $sql = "update boss_info set boss_psw = '$boss_psw' where boss_identity = '$boss_identity'";
        //2.改下去
        mysqli_query($con, $sql);
            $data['result'] = 'OK';
            $data['message'] = "密碼已修改";
            echo json_encode($data);
    } catch (Exception $e) {
        $data['result'] = 'NG';
        $data['message'] = $e->getMessage();
        //回傳執行結果
        echo json_encode($data);
    }
?>