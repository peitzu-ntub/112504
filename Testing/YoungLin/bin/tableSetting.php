<?php
    include ("conn.php");

    $data= array();
  
    try {
        $id = $_POST['lic'];
        $store_id = $_POST['store_id'];
        $order_no = "202304301220";
        $table_num = $_POST['table_num'];
        $customer_count = $_POST['customer_count'];
        $employee_no = $_POST['employee_no'];

        //新增一張新的訂單
        $sql = 
            "INSERT INTO store_order (
                boss_identity, store_id, order_no, table_num, customer_count, start_time, employee_no
            ) VALUES (
                '$id', '$store_id', '$order_no', '$table_num', $customer_count, '202304301230', '$employee_no'
            )";
        if (mysqli_query($con, $sql)) {
            $data['result'] = 'OK';
            $data['message'] = '開桌資料儲存成功';
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