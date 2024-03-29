<?php
include("conn.php");

$data = array();

if (isset($_POST['data_type']) && $_POST['data_type'] == 'staff') {
    try {
        $boss_identity = $_POST['boss_identity'];
        $store_id = $_POST['store_id'];
        $staff_id = $_POST['staff_id'];
        $staff_name = $_POST['staff_name'];
        $staff_birth = $_POST['staff_birth'];
        $staff_gender = $_POST['staff_gender'];
        $staff_tel = $_POST['staff_tel'];
        $staff_address = $_POST['staff_address'];
        $em_name = $_POST['em_name'];
        $em_tel = $_POST['em_tel'];
        $relation = $_POST['relation'];
        $due_date = $_POST['due_date'];
        $staff_psw = $_POST['staff_psw'];

        // Check if the staff already exists
        $checkSql = "SELECT * FROM store_staff WHERE boss_identity = '$boss_identity' AND store_id = '$store_id' AND staff_name = '$staff_name'";
        $checkResult = mysqli_query($con, $checkSql);
        
        if (mysqli_num_rows($checkResult) == 0) {
            // If the staff doesn't exist, insert it
            $sql = "INSERT INTO store_staff (
                staff_id, boss_identity, store_id, staff_name, staff_gender, staff_birth, 
                staff_tel, staff_address, em_name, em_tel, relation, due_date, staff_psw) 
                VALUES (
                '$staff_id', '$boss_identity', '$store_id', '$staff_name', '$staff_gender', '$staff_birth', 
                '$staff_tel', '$staff_address', '$em_name', '$em_tel', '$relation', '$due_date', '$staff_psw')";
            
            $result = mysqli_query($con, $sql);

            $data['result'] = 'OK';
            $data['message'] = '新員工資料儲存成功';
            // $data['message'] = $sql;
            echo json_encode($data);
        } else {
            // If the staff already exists, return an error message
            $data['result'] = 'NG';
            $data['message'] = '員工已存在';
            echo json_encode($data);
        }
    } catch (Exception $e) {
        $data['result'] = 'NG';
        $data['message'] = $e->getMessage();
        echo json_encode($data);
    }

} else if (isset($_POST['data_type']) && $_POST['data_type'] == 'staff_delete') {
    try {        
        $boss_identity = $_POST['boss_identity'];
        $store_id = $_POST['store_id'];
        //準備要刪除的typeName，放在這個隱藏欄位
        $staff_name = $_POST['data_value'];

        // If the food type doesn't exist, insert it
        $sql = "
            delete from store_staff
            where boss_identity = '$boss_identity'
            and store_id = '$store_id'
            and staff_name = '$staff_name'";
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
