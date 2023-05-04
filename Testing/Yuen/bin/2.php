<?php
$user = 'root';
$pass = '12345678';
$db = '112504';
//Replace 'sample tutorial' with the name of your database

$con = mysqli_connect("localhost", $user, $pass, $db);

if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);

}

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
      header("Location: upload.html");
  }else {
      echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
?>