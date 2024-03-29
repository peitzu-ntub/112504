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

    $meal_type = $_POST['type_id'];
    $meal_name = $_POST['meal_name'];
    $meal_price = $_POST['meal_price'];
    $meal_note = $_POST['meal_note'];

    $sql = "
        insert into store_food (
            boss_identity, store_id, meal_id, type_id, meal_name,
            meal_price, meal_note
        ) values (
            '$identity', '$store', '$meal_name', '$meal_type', '$meal_name',
            $meal_price, '$meal_note'
        );    
    ";
    mysqli_query($con, $sql);

    //上傳的檔案    
    if ($_FILES['meal_pic']['error'] === UPLOAD_ERR_OK){
        $tmpFile = $_FILES['meal_pic']['tmp_name'];
        $newFile = "../images/$meal_name.upload.jpg";
        move_uploaded_file($tmpFile, $newFile);
    }
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