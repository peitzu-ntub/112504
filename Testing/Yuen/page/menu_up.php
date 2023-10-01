<?php
include "../bin/conn.php";

$meal_name=$_GET['meal_name'];

$type_id = $_POST['type_id'];
$meal_name = $_POST['meal_name'];
$meal_price = $_POST['meal_price'];
$meal_note = $_POST['meal_note'];

$sql = "UPDATE store_food SET type_id = '".$type_id."',meal_name = '".$meal_name."', 
meal_price = '".$meal_price."',  meal_note = '".$meal_note."' WHERE meal_name = '".$meal_name."'";


if ($con->query($sql) === TRUE) {
  echo "<script>alert('更新成功!');location.href='allmenu.php';</script>"; 
  // header("location:menu_edit.php?meal_name=".$meal_name);
} else {
  echo "<script>alert('餐點重複!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"; 
}


//關閉資料庫
$con->close();


?>