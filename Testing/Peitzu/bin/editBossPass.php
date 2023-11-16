<?php
    include ("conn.php");

    $data= array();

    try {        
        $boss_identity = $_POST['boss_identity'];
        $new_pass = $_POST['new_pass'];

        $sql = "
            update boss_info set boss_psw = '$new_pass'
            where boss_identity = '$boss_identity'";
        $result = mysqli_query($con, $sql);
        $data['result'] = 'OK';
        $data['message'] = '儲存成功';
        echo json_encode($data);
    } catch (Exception $e) {
        $data['result'] = 'NG';
        $data['message'] = $e->getMessage();
        echo json_encode($data);
    }
?>