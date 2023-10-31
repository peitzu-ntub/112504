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
            //4.找出老闆的姓名
            $row_result = mysqli_fetch_assoc($result);
            $data['boss_name'] = $row_result["boss_name"];
            //5.檢查店舖數量，決定要顯示的下一個頁面
            $sql = "SELECT * FROM store_info WHERE boss_identity='$id'";
            $result = mysqli_query($con, $sql);
            $count = mysqli_num_rows($result);
            if ($count == 0) {
                $data['next'] = 'register';
            } else {
                $data['next'] = 'choose';
            }
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
        //回傳執行結果
        echo json_encode($data);
    }
?>