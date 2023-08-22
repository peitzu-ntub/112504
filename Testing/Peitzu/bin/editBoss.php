<?php
    include ("conn.php");

    $data= array();
  
    try {
        $boss_identity = $_POST['boss_identity'];
        $boss_name = $_POST['boss_name'];
        $boss_psw = $_POST['boss_psw'];

        //檢查老闆是否已經存在
        //1.產生查詢字串
        $sql = "SELECT * from boss_info where boss_identity = '$boss_identity'";
        //2.查下去，並取得查詢結果
        $result = mysqli_query($con, $sql);
        //3.查詢結果的筆數
        $count = mysqli_num_rows($result);
        //如果筆數大於0，表示這個身分證字號已經存在
        if ($count > 0) {
            $data['result'] = 'NG';
            $data['message'] = "身分證號已經存在,請重新輸入";
            echo json_encode($data);
            exit();
        }

        //註冊老闆資料
        $sql = 
            "INSERT INTO boss_info (
                boss_identity, boss_name, boss_psw
            ) VALUES (
                '$boss_identity', '$boss_name', '$boss_psw'
            )";
        if (mysqli_query($con, $sql)) {
            $data['result'] = 'OK';
            $data['message'] = '老闆資料儲存成功';
            echo json_encode($data);
        }else {
            $data['result'] = 'NG';
            $data['message'] = "Error... " . mysqli_error($con);
            echo json_encode($data);
        }
        

    } catch (Exception $e) {
        $data['result'] = 'NG';
        $data['message'] = $e->getMessage();
        //回傳執行結果
        echo json_encode($data);
    }
?>