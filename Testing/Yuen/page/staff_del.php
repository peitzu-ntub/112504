<?php
include "../bin/conn.php";


$staff_id=$_GET['staff_id'];

$sql ="DELETE FROM store_staff WHERE staff_id = '".$staff_id."'";

if ($con->query($sql) === TRUE) {
    echo "刪除成功";
    header("location:employee.php");
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }


  //關閉資料庫
  $conn->close();


?>