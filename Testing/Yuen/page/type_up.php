<?php
include "../bin/conn.php";

$type_id=$_GET['type_id'];


$type_name = $_POST['type_name'];


$sql = "UPDATE food_type SET type_name = '".$type_name."' WHERE type_id = '".$type_id."'";


if ($con->query($sql) === TRUE) {
  echo "<script>alert('更新成功!');location.href='newmenu1.php';</script>"; 
  // header("location:type_edit.php?type_name=".$type_name);
} else {
  echo "<script>alert('類型重複!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"; 
}


//關閉資料庫
$con->close();


?>