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
  echo "更新成功";
  header("location:menu_edit.php?meal_name=".$meal_name);
} else {
  echo "Error: " . $sql . "<br>" . $con->error;
}


//關閉資料庫
$con->close();


?>