<?php
include("conn.php");

$data = array();

if (isset($_POST['data_type']) && $_POST['data_type'] == 'menu2') {
    try {
        $boss_identity = $_POST['boss_identity'];
        $store_id = $_POST['store_id'];
        $type_id = $_POST['type_id'];
        $type_name = $_POST['type_name'];
        $meal_name = $_POST['meal_name'];
        $meal_note = $_POST['meal_note'];
        $meal_price = $_POST['meal_price'];

        // Check if the meal already exists
        $checkSql = "SELECT * FROM store_food WHERE boss_identity = '$boss_identity' AND store_id = '$store_id' AND meal_name = '$meal_name'";
        $checkResult = mysqli_query($con, $checkSql);

        if (mysqli_num_rows($checkResult) == 0) {
            // If the meal doesn't exist, insert it
            $sql = "
                INSERT INTO store_food (
                    boss_identity, store_id, type_id, type_name, meal_name, meal_price, meal_note
                ) VALUES (
                    '$boss_identity', '$store_id', '$type_id', '$type_name', '$meal_name', $meal_price, '$meal_note'
                )";

            $result = mysqli_query($con, $sql);

            // Upload the file
            if ($_FILES['meal_pic']['error'] === UPLOAD_ERR_OK) {
                $tmpFile = $_FILES['meal_pic']['tmp_name'];
                $newFile = "../images/$meal_name.upload.jpg";
                move_uploaded_file($tmpFile, $newFile);
                $data['result'] = 'OK';
                $data['message'] = $tmpFile . ", " . $newFile;
            } else {
                // Return the result
                $data['result'] = 'OK';
                $data['message'] = '儲存成功';
                echo json_encode($data);
            }
        } else {
            // If the meal already exists, return an error message
            $data['result'] = 'NG';
            $data['message'] = '餐點已存在';
            echo json_encode($data);
        }
    } catch (Exception $e) {
        $data['result'] = 'NG';
        $data['message'] = $e->getMessage();
        echo json_encode($data);
    }
} else if (isset($_POST['data_type']) && $_POST['data_type'] == 'menu2_delete') {
    try {        
        $boss_identity = $_POST['boss_identity'];
        $store_id = $_POST['store_id'];
        //準備要刪除的typeName，放在這個隱藏欄位
        $meal_name = $_POST['data_value'];

        // If the food type doesn't exist, insert it
        $sql = "
            delete from store_food
            where boss_identity = '$boss_identity'
            and store_id = '$store_id'
            and meal_name = '$meal_name'";
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

mysqli_close($con);
?>
