<?php
    include ("conn.php");

    $data= array();
  
    try {
        $id = $_POST['lic'];
        $name = $_POST['name'];
        $psw = $_POST['psw'];





        $sql = 
            "INSERT INTO boss_info (
                boss_identity, boss_name, boss_psw
            ) VALUES (
                '$id', '$name', '$psw'
            )";
        
        if (mysqli_query($con, $sql)) {
            $data['result'] = 'OK';
            $data['message'] = '老闆資料儲存成功';
        }else {
            $data['result'] = 'OK';
            $data['message'] = "Error: " . $sql . "<br>" . mysqli_error($con);
        }

    } catch (Exception $e) {
        $data['result'] = 'NG';
        $data['message'] = $e->getMessage();
    }

    // return all our data to an AJAX call
    echo json_encode($data);
?>