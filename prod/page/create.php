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

	<title>新增員工資料</title>
	<link href="../js/test3.css" rel="stylesheet">
	<link rel="stylesheet" href="../js/bootstrap.min.css">
	
	<script src="../js/jquery-3.6.4.min.js"></script>
</head>

<body>
	<div class="container">
		<div class="button">
			<input type="reset" value="返回" onclick="location.href='##'">
		</div>
		<div align="center"><font size="5">員工資料管理</font></div>
		<hr>
		<div class="content">
			<form action="newFood.php" method="POST" enctype="multipart/form-data">
				<div class="user-details">
					<input type='hidden' id='nexturl' name='C' value='menu_2.php'>
			
					<div class="col-md-4">
						<div class="input-box">
							<span class="details">員工編號：</span>
							<input type="text" name="staff_no" id="staff_no" placeholder="請輸入員工編號" required>
						</div>
						<div class="input-box">
							<span class="details">員工姓名：</span>
							<input type="text" name="staff_name" id="staff_name" placeholder="請輸入員工姓名" required>
						</div>
			
						<div>
							<table width="100%">
								<tr>
								<td style="background-color:#B27AC2" align="center" width="3%"></td>
								<td style="background-color:#B27AC2" align="center" width="15%"><font color="white"><b>員工編號</b></font></td>
								<td style="background-color:#B27AC2" align="center" width="15%"><font color="white"><b>員工姓名</b></font></td>
								</tr>
							</table>	
						</div>
					    <br><br>
						<div class="button">
							<input value="新增" type="submit"/>
						</div>
						<div class="button">
							<input value="修改" type="submit"/>
						</div>
					</div>



					 <div class="col-md-8">
							<div class="input-box">
							<span class="details">員工編號：</span>
							<input type="text" name="staff_no" id="staff_no" placeholder="員工編號" required>
							</div>
							<div class="input-box">
							<span class="details">姓名：</span>
							<input type="text" name="staff_name" id="staff_name" placeholder="請輸入員工姓名" required>
							</div>

							<div class="input-box">
							<span class="details">生日：</span>
							<input type="date" name="staff_birth" id="staff_birth" placeholder="請輸員工生日" required>
							</div>

							<p>性別：
							<input type="radio" name="staff_gender" value="male"> 男
							<input type="radio" name="staff_gender" value="female"> 女
							</p>

							<div class="input-box">
							<span class="details">聯絡電話：</span>
							<input type="text" name="staff_tel" id="staff_tel" placeholder="請輸入員工的聯絡電話" required>
							</div>

							<div class="input-box">
							<span class="details">電子郵件：</span>
							<input type="text" name="staff_address" id="staff_address" placeholder="請輸入員工的電子郵件" required>
							</div>

							<div class="input-box">
							<span class="details">緊急聯絡人：</span>
							<input type="text" name="em_name" id="em_name" placeholder="請輸入員工的緊急聯絡人姓名" required>
							</div>

							<div class="input-box">
							<span class="details">緊急連絡人電話：</span>
							<input type="text" name="em_tel" id="em_tel" placeholder="請輸入員工的緊急聯絡人電話" required>
							</div>

							<div class="input-box">
							<span class="details">與緊急連絡人關係：</span>
							<input type="text" name="relation" id="relation" placeholder="請輸入員工與緊急連絡人的關係" required>
							</div>

							<div class="input-box">
							<span class="details">到職日期：</span>
							<input type="date" name="due_date" id="due_date" placeholder="請輸員工的到職日期" required>
							</div>
							<div class="button">
							<input value="儲存" type="submit"/>
							</div>
						</div>
			</form>
			
		</div>
	</div>
</body>

</html>