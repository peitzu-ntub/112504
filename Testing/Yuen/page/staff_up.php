<?php
include "../bin/conn.php";

$staff_id=$_GET['staff_id'];
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

$sql = "UPDATE admin_manager SET adm_name = '".$adm_name."', adm_account = '".$adm_account."',update_user = '".$_COOKIE['name']."' WHERE adm_pk = '".$adm_pk."'";


if ($conn->query($sql) === TRUE) {
    echo "更新成功";
    header("location:listdetail.php?pk=".$adm_pk);
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }


  //關閉資料庫
  $conn->close();


?>