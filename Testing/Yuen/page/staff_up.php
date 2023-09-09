<?php
include "../bin/conn.php";

$staff_id=$_GET['staff_id'];

$staff_name = $_POST['staff_name'];
$staff_birth = $_POST['staff_birth'];
$staff_tel = $_POST['staff_tel'];
$staff_address = $_POST['staff_address'];
$em_name = $_POST['em_name'];
$em_tel = $_POST['em_tel'];
$relation = $_POST['relation'];
$due_date = $_POST['due_date'];
$staff_psw = $_POST['staff_psw'];

$sql = "UPDATE store_staff SET staff_name = '".$staff_name."',staff_birth = '".$staff_birth."', 
staff_tel = '".$staff_tel."',  staff_address = '".$staff_address."', 
em_name = '".$em_name."', em_tel = '".$em_tel."', relation = '".$relation."', due_date = '".$due_date."', 
staff_psw = '".$staff_psw."' WHERE staff_id = '".$staff_id."'";


if ($con->query($sql) === TRUE) {
  echo "更新成功";
  header("location:staff_edit.php?staff_id=".$staff_id);
} else {
  echo "Error: " . $sql . "<br>" . $con->error;
}


//關閉資料庫
$con->close();


?>