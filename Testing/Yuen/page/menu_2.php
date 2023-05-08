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

	<title>Food</title>
	<link href="../js/menu.css" rel="stylesheet">

</head>


<body>
	<header class="main-header">
		<div class="header">
			<h1><a>輸入所有餐點品項</a></h1>
			<hr>
			<button class="registbutton" onclick="location.href='../page/menu_3.html'"> 
				<span>下一步</span></button>
		</div>
	</header>

	<div class="menucabinet">
		<form>
			餐點類型：
			<select name="type_id" id="type_id">
				<!-- 動態載入的選項會放在這裡 -->
				<option value="">--- Select ---</option>
				<?php
                        while ($cat = mysqli_fetch_array($car_brands,MYSQLI_ASSOC)) {
									echo "<option value='" . $cat['type_name'] . "'>" . $cat['type_name'] . "</option>";
						}
                        ?>
			</select>
		</form>


		<div class="form-group">
			<label for="boss_identity">
				<font style="vertical-align: inherit;">
					<font style="vertical-align: inherit;">
						餐點名稱：
					</font>
				</font>
			</label>
			<input type="text" class="form-control" id="meal_name" name="meal_name" placeholder="餐點名稱">
		</div>

		<div class="form-group">
			<label for="meal_note">
				<font style="vertical-align: inherit;">
					<font style="vertical-align: inherit;">
						餐點介紹：
					</font>
				</font>
			</label>
			<textarea id="meal_note" name="meal_note" rows="2" cols="20" placeholder="餐點介紹"></textarea><br>
		</div>

		<div class="form-group">
			<label for="meal_note">
				<font style="vertical-align: inherit;">
					<font style="vertical-align: inherit;">
						餐點介紹：
					</font>
				</font>
			</label>
			<textarea id="meal_note" name="meal_note" rows="2" cols="20" placeholder="餐點介紹"></textarea><br>
		</div>

		<div class="form-group">
			<label for="meal_note">
				<font style="vertical-align: inherit;">
					<font style="vertical-align: inherit;">
						注意事項：
					</font>
				</font>
			</label>
			<textarea id="meal_note" name="meal_note" rows="2" cols="20" placeholder="注意事項"></textarea>
		</div>

		<div class="form-group">
			<label for="meal_price">
				<font style="vertical-align: inherit;">
					<font style="vertical-align: inherit;">
						價格：
					</font>
				</font>
			</label>
			<input type="number" min="0" class="form-control" id="meal_price" name="meal_price">
		</div>
		<div class="form-group">
			<label for="meal_pic">
				<font style="vertical-align: inherit;">
					<font style="vertical-align: inherit;">
						圖片：
					</font>
				</font>
			</label>
			<input type="file" class="form-control" id="meal_pic">
		</div>
		<button type="submit" onclick="location.href=''">儲存</button>
		<button type="reset" onclick="location.href='../page/index.html'">返回</button>
	</div>
</body>

</html>