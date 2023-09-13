<?php
include "../bin/conn.php";


$type_name=$_GET['type_name'];

$sql ="DELETE FROM food_type WHERE type_name = '".$type_name."'";

if ($con->query($sql) === TRUE) {
    header("location:newmenu1.php");
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }


  //關閉資料庫
  $con->close();


?>