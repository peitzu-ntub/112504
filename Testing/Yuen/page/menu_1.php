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
	<title>input type</title>
	<link rel="stylesheet" href="../js/menu.css">
	<script src="../js/jquery-3.6.4.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
	<div class="container">
		<div class="title">請問您的菜單有些什麼類型?</div><br>
<hr>
		<div class="content">
			<form action="menu1.php" method="POST">
				<div class="user-details">
					<div class="input-box">
						<span class="details">類型：</span>
						<input type="text" name="type_name" id="type_name" placeholder="主菜、附餐、飲品" required>
					</div>
				</div>
				<div class="button">
					<input value="儲存" type="submit" onclick= />
				</div>
				<div class="button">
					<input type="reset" value="返回" onclick="location.href='../page/前台管理介面3-3.html'">
				</div>
			</form>
		</div>
		<div class="button-container">
			<button class="registbutton" onclick="location.href='../page/menu_2.php'">
				<span>下一步</span>
			</button>
		</div>
	</div>
</body>
<!-- <script>
	function addLabel() {
		// 創建新的標籤元素
		var newLabel = document.createElement("label");
		newLabel.textContent = "類型：";
		//newLabel.textContent = "類型：";

		// 找到標籤容器元素
		var labelContainer = document.getElementById("labelContainer");

		// 將新標籤插入到容器元素中
		labelContainer.insertBefore(newLabel, labelContainer.firstChild);
	}
</Script>
 -->
<!--<script>
	function addSelect() {
		// 获取第一个下拉式选择菜单和添加按钮的元素
		var select1 = document.getElementById("select1");
		var addButton = document.getElementById("addButton");

		// 创建一个新的下拉式选择菜单元素
		var select2 = document.createElement("select");

		// 设置新元素的属性和选项
		select2.id = "select2";
		select2.name = "select2";
		

		// 将新元素添加到文档中
		addButton.parentNode.insertBefore(select2, addButton);

		// 将与之前选择的选项不同的选项添加到新下拉式选择菜单中
		for (var i = 0; i < select1.options.length; i++) {
			if (select1.options[i].selected === false) {
				var option = document.createElement("option");
				option.value = select1.options[i].value;
				option.text = select1.options[i].text;
				select2.appendChild(option);
			}
		}
		// 将新元素添加到文档中
	addButton.parentNode.insertBefore(select2, addButton);
	}-->

</html>