<?php
$user = 'root';
$pass = '12345678';
$db = '112504';

$con = mysqli_connect("localhost", $user, $pass, $db);

  $sql = "SELECT type_name FROM food_type;";
  $food_type = mysqli_query ($con, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>input food</title>
	<link href="../js/menu.css" rel="stylesheet">

</head>


<body>
	<div class="container">
		<div class="title">輸入所有餐點類型</div><br>
		<hr>
		
		<div class="content">
		<form action="../bin/2.php" method="POST"> 
				<div class="user-details">
					<div class="input-box">
						<span class="details">餐點類型：</span>
						<select name="type_id" id="type_id">
						<option value="">--- Select ---</option>
                        <?php
                        while ($cat = mysqli_fetch_array($food_type,MYSQLI_ASSOC)) {
									echo "<option value='" . $cat['type_name'] . "'>" . $cat['type_name'] . "</option>";
						}
                        ?>
						</select>
					</div>
					<div class="input-box">
						<span class="details">餐點名稱：</span>
						<input type="text" name="meal_name" id="meal_name" placeholder="請輸入餐點名稱" required>
					</div>
					<div class="input-box">
						<span class="details">價格：</span>
						<input type="number" min="0" name="meal_price" id="meal_price" placeholder="請輸入正確價格" required>
					</div><br>
					<div class="input-box">
						<span class="details">圖片：</span>
						<input type="file" name="meal_pic" id="meal_pic">
					</div>
					<div class="input-box">
						<span class="details">餐點介紹：</span>
						<textarea id="meal_note" name="meal_note" rows="2" cols="20" placeholder="請輸入餐點介紹"
							style="height: 50px;width: 300px;resize: none;"></textarea>
					</div>
					<div class="input-box">
						<span class="details">注意事項：</span>
						<!--textarea id="meal_note" name="meal_note" rows="2" cols="20" placeholder="注意事項"></textarea> -->
						<textarea id='meal_note' name="meal_note" placeholder="請輸入注意事項"
							style="height: 50px;width: 300px;resize: none;"></textarea>
					</div>
				</div>
				<div class="button">
					<input value="確定" type="submit" onclick= />
				</div>
				<div class="button">
					<input type="reset" value="返回" onclick="location.href='../page/menu_1.html'">
				</div>
			</form>
			<div class="button-container">
				<button class="registbutton" onclick="location.href='../page/menu_3.html'">
					<span>下一步!</span>
				</button>
			</div>
		</div>
</body>


</html>
