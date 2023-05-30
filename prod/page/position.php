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

    //PHP是在後端(Server)運作的程式，Html與JavaScript則是在前端(Client)運作的程式
    //在Server端，透過PHP將身份證與店代號，保留於隱藏欄位中，以傳到前端，做後續的應用
    $boss = $_SESSION["identity"];
    $store = $_SESSION["store_id"];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="UTF-8">
	<title>職位</title>
	<link rel="stylesheet" href="../js/position.css">
	<script src="../js/jquery-3.6.4.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
	<div class="container">
		<div class="title">請問您的店家有哪些職位？</div><br>
<hr>
		<div class="content">
			<form action="menu1.php" method="POST">
				<div class="user-details">
					<div class="input-box">
						<span class="details">職位：</span>
						<input type="text" name="staff_identity" id="staff_identity" placeholder="正職、兼職" required>
					</div>
				</div>
				<div class="button">
					<input value="儲存" type="submit"/>
				</div>
				<div class="button">
					<input type="reset" value="返回" onclick="location.href='position.php'">
				</div>
			</form>
		</div>
		<div class="button-container">
			<button class="registbutton" onclick="location.href='../page/create.php'">
				<span>下一步</span>
			</button>
		</div>
	</div>
</body>
</html>