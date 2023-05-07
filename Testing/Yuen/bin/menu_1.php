<?php
$user = 'root';
$pass = '12345678';
$db = '112504';
//Replace 'sample tutorial' with the name of your database

$con = mysqli_connect("localhost", $user, $pass, $db);

if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);

}

  $name = $_POST['type_name'];


  $sql = "INSERT INTO food_type (type_name) 
  VALUES ('$name')";
	 
    // header("Location: upload.html"); // Return to where our form is stored

    //對資料庫執行查訪的動作
    if (mysqli_query($con, $sql)) {
      header("Location: ../page/new_succ.html");
  }else {
      echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
?>