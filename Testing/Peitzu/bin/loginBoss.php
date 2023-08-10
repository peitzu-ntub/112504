<?php
    include ("conn.php");

    $data= array();
  
    try {
        $id = $_POST['boss_identity'];
        $psw = $_POST['boss_pwd'];

        //檢查此老闆是否已經存在
        //1.產生查詢字串
        $sql = "SELECT * FROM boss_info WHERE boss_identity='$id' and boss_psw='$psw'";
        //2.查下去，並取得查詢結果
        $result = mysqli_query($con, $sql);
        //3.查詢結果的筆數
        $count = mysqli_num_rows($result);
        //如果筆數大於0，表示這個老闆ID已經存在
        if ($count > 0) {
            $data['result'] = 'OK';
            $data['message'] = "登入成功！";
            echo json_encode($data);
        }

        else {
            $data['result'] = 'NG';
            $data['message'] = "帳號或密碼輸入錯誤，請重新登入";
            echo json_encode($data);
        }

    } catch (Exception $e) {
        $data['result'] = 'NG';
        $data['message'] = $e->getMessage();
        //回傳執行結果test
        echo json_encode($data);
    }
?>