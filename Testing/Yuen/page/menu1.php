<?php
  session_start();
  include "../bin/conn.php";

  //todo, 這是假的資料
  //預設的資料來源，是從登入而來。登入、選擇店家後，就會把以下這兩個資訊，放進SESSION裡，保留在Server端
  //讓同一個人的接續連線，可以直接拿來用
  if (!isset($_SESSION["identity"])) {
      $_SESSION["identity"] = "A123456789";
  }
  if (!isset($_SESSION["store_id"])) {
      $_SESSION["store_id"] = "S01";
  }

  $nexturl=$_POST['nexturl'];

  //擷取準備要新增時使用的各欄位資料
  $identity = $_SESSION["identity"];
  $store = $_SESSION['store_id'];

  $name = $_POST['type_name'];


  $sql = "INSERT INTO food_type (boss_identity, store_id, type_id, type_name) 
  VALUES ('$identity', '$store','$name', '$name')";

  mysqli_query($con, $sql);
	 
?>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Menu customization</title>
	<link href="../js/menu.css" rel="stylesheet">

</head>


<body>
	<div class="cabinet">
		<label for="type_id">
			<font style="vertical-align: inherit;">
				<font style="vertical-align: inherit;">
					新增成功!
				</font>
			</font>
		</label>
	</div><br>
	<div class="button-container">
		<button class="Nextstepbutton" onclick="location.href='menu_2.php'">
			<span>繼續</span>
		</button>
	</div>
</body>
</html>