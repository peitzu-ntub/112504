<?php
include "../bin/conn.php";


$meal_name=$_GET['meal_name'];

$sql ="DELETE FROM store_food WHERE meal_name = '".$meal_name."'";

if ($con->query($sql) === TRUE) {
    echo "刪除成功";
    header("location:allmenu.php");
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }


  //關閉資料庫
  $con->close();


?>