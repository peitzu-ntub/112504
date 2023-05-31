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
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>修改員工資料</title>
	<link href="../js/position.css" rel="stylesheet">
</head>

<body>
	<div class="container">
	<div align="left"><font size="5">修改員工資料</font></div>
		<hr>
		<div class="content">
			<form action="newFood.php" method="POST" enctype="multipart/form-data">
				<div class="user-details">
					<input type='hidden' id='nexturl' name='C' value='menu_2.php'>
					<div class="input-box">

					<span class="details" >員工編號：</span>
						<select name="staff_identity" id="staff_identity">
						<!-- 動態載入的選項會放在這裡 -->
						<option value="none">(空)</option>

<?php
	$sql = "
		select * from food_type
		where boss_identity = '$boss' and store_id = '$store'";
	$meal_type = mysqli_query($con, $sql);
	while ($cat = mysqli_fetch_array($meal_type,MYSQLI_ASSOC)) {
		$type_id=$cat['type_id'];
		$type_name=$cat['type_name'];
		echo "<option value='$type_id'>$type_name</option>";
	}
?>
					</select>
					</div>

					<select size="5">
						<option>請選擇你最愛的寵物</option>
						<option>Dog</option>
					</select>

					<div class="input-box">
						<span class="details">姓名：</span>
						<input type="text" name="staff_name" id="staff_name" placeholder="請輸入員工姓名" required>
					</div>

					<p>性別：<br><br>
						<input type="radio" name="staff_gender" value="male"> 男
						<input type="radio" name="staff_gender" value="female"> 女
					</p>

					<div class="input-box">
						<span class="details">出生日期：</span>
						<input type="date" name="staff_birth" id="staff_birth" placeholder="請輸員工出生日期" required>
					</div>

					<div class="input-box">
						<span class="details">聯絡電話：</span>
						<input type="text" name="staff_tel" id="staff_tel" placeholder="請輸入員工的聯絡電話" required>
					</div>

					<div class="input-box">
						<span class="details">電子郵件：</span>
						<input type="text" name="staff_address" id="staff_address" placeholder="請輸入員工的電子郵件" required>
					</div>
					
				</div>
				<div class="button">
					<input value="修改" type="submit"/>
				</div>
				<div class="button">
					<input type="reset" value="返回" onclick="location.href='../page/employee.html'">
				</div>
			</form>
			
		</div>
	</div>
</body>

</html>