<?php
  include ("conn.php");

  $id = $_POST['type_id'];
  $name = $_POST['meal_name'];
  $note = $_POST['meal_note'];
  $price = $_POST['meal_price'];
  $pic = $_POST['meal_pic'];

  $sql = "INSERT INTO store_food (type_id, meal_name, meal_note, meal_price, meal_pic) 
  VALUES ('$id', '$name', '$note', '$price', '$pic')";
	 
    header("Location: upload.html"); // Return to where our form is stored

    //對資料庫執行查訪的動作
    if (mysqli_query($con, $sql)) {
      header("Location: ../page/new_succ.html");
  }else {
      echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
?>