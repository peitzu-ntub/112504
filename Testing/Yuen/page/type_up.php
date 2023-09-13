<?php
include "../bin/conn.php";

$type_name=$_GET['type_name'];


$type_name = $_POST['type_name'];


$sql = "UPDATE food_type SET type_name = '".$type_name."' WHERE type_name = '".$type_name."'";


if ($con->query($sql) === TRUE) {
  echo "更新成功";
  header("location:type_edit.php?type_name=".$type_name);
} else {
  echo "Error: " . $sql . "<br>" . $con->error;
}


//關閉資料庫
$con->close();


?>