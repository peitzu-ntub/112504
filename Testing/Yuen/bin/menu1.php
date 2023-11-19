<?php

    include ("conn.php");
    $data = array();

    if (isset($_POST['data_type']) && $_POST['data_type'] == 'menu1') {
        try {        
            $boss_identity = $_POST['boss_identity'];
            $store_id = $_POST['store_id'];
            $type_name = $_POST['type_name'];

            // Check if the food type already exists
            $checkSql = "
                SELECT * FROM food_type 
                WHERE boss_identity = '$boss_identity' 
                AND store_id = '$store_id' 
                AND type_name = '$type_name'";
            $checkResult = mysqli_query($con, $checkSql);

            if (mysqli_num_rows($checkResult) == 0) {
                // If the food type doesn't exist, insert it
                $sql = "
                    INSERT INTO food_type (
                        boss_identity, store_id, type_id, type_name
                    ) VALUES (
                        '$boss_identity', '$store_id', '$type_name', '$type_name'
                    )";
                    
                $result = mysqli_query($con, $sql);

                $data['result'] = 'OK';
                $data['message'] = '新增成功';
                echo json_encode($data);
            } else {
                // If the food type already exists, return an error message
                $data['result'] = 'NG';
                $data['message'] = '餐點類型已存在';
                echo json_encode($data);
            }
        } catch (Exception $e) {
            $data['result'] = 'NG';
            $data['message'] = $e->getMessage();
            echo json_encode($data);
        }
    } else if (isset($_POST['data_type']) && $_POST['data_type'] == 'menu1_delete') {
        try {        
            $boss_identity = $_POST['boss_identity'];
            $store_id = $_POST['store_id'];
            //準備要刪除的typeName，放在這個隱藏欄位
            $type_name = $_POST['data_value'];

            // If the food type doesn't exist, insert it
            $sql = "
                delete from food_type
                where boss_identity = '$boss_identity'
                and store_id = '$store_id'
                and type_name = '$type_name'";
            $result = mysqli_query($con, $sql);

            $data['result'] = 'OK';
            $data['message'] = '刪除成功';
            echo json_encode($data);
        } catch (Exception $e) {
            $data['result'] = 'NG';
            $data['message'] = $e->getMessage();
            echo json_encode($data);
        }    
    } else {
        $data['result'] = 'NG';
        $data['message'] = 'none';
        echo json_encode($data);
    }
?>
