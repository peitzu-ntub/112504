<?php
    include ("conn.php");

    $data= array();
  
    try {
        $bossid = $_POST['lic'];
        $storeid = $_POST['store_id'];
        $name = $_POST['store_name'];
        $tel = $_POST['store_tel'];
        $address = $_POST['store_address'];
        $table = $_POST['table_count'];

        //檢查店家是否已經存在
        //1.產生查詢字串
        $sql = "select * from store_info where store_id = '$id'";
        //2.查下去，並取得查詢結果
        $result = mysqli_query($con, $sql);
        //3.查詢結果的筆數
        $count = mysqli_num_rows($result);
        //如果筆數大於0，表示這個店家已經存在
        if ($count > 0) {
            $data['result'] = 'NG';
            $data['message'] = "店家已經存在,請重新輸入";
            echo json_encode($data);
            exit();
        }

        //註冊店家資料
        $sql = 
            "INSERT INTO store_info (
                boss_identity, store_id, store_name, store_tel, store_address, table_count
            ) VALUES (
                '$bossid', '$storeid', '$name', '$tel', '$address', '$table'
            )";
        if (mysqli_query($con, $sql)) {
            $data['result'] = 'OK';
            $data['message'] = '店家資料儲存成功';
        }else {
            $data['result'] = 'NG';
            $data['message'] = "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        echo json_encode($data);

    } catch (Exception $e) {
        $data['result'] = 'NG';
        $data['message'] = $e->getMessage();
        //回傳執行結果
        echo json_encode($data);
    }
?>