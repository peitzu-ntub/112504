<?php
$user = 'root';
$pass = '12345678';
$db = '112504';

$con = mysqli_connect("localhost", $user, $pass, $db);

  $sql = "SELECT type_name FROM food_type;";
  $car_brands = mysqli_query ($con, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Expenses record2</title>

	<link href="food.css" rel="stylesheet">

</head>

<body>

	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			<form action="2.php" method="POST"> 
				<h3 class="text-center">
					輸入所有餐點品項
				</h3>
				<hr>
				<!--<div class="form-group">
						<label for="type_id"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
							餐點類型：
						</font></font></label>
						<input type="text" class="form-control" id="type_id">
					</div>-->
				
				<div class="form-group">
					<label for="type_id">
						<font style="vertical-align: inherit;">
							<font style="vertical-align: inherit;">
                            餐點類型:
							</font>
						</font>
					</label>
					<form id="form" name="form" method="post">
                    <select name="type_id" id="type_id">
						<!-- 動態載入的選項會放在這裡 -->
						<option value="">--- Select ---</option>
                        <?php
                        while ($cat = mysqli_fetch_array($car_brands,MYSQLI_ASSOC)) {
									echo "<option value='" . $cat['type_name'] . "'>" . $cat['type_name'] . "</option>";
						}
                        ?>
					</select>
				</div>
                

				<div class="form-group">
					<label for="meal_name">
						<font style="vertical-align: inherit;">
							<font style="vertical-align: inherit;">
								餐點名稱：
							</font>
						</font>
					</label>
					<input type="text" class="form-control" id="meal_name" name="meal_name" placeholder="餐點名稱">
				</div>
				<label for="meal_note">
					<font style="vertical-align: inherit;">
						<font style="vertical-align: inherit;" >
							餐點介紹：
						</font>
					</font>
				</label>
				<textarea id="meal_note" name="meal_note" rows="2" cols="20" placeholder="餐點介紹"></textarea><br>
				<!-- <div class="form-group">
						<label for="meal_note"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
							注意事項：
						</font></font></label>
						<input type="text" class="form-control" id="meal_note">
					</div>
				<label for="meal_note">
					<font style="vertical-align: inherit;">
						<font style="vertical-align: inherit;">
							注意事項：
						</font>
					</font>
				</label>
				<textarea id="meal_note" name="meal_note" rows="2" cols="20" placeholder="注意事項"></textarea> -->


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
				<input type="submit" value="確認">
				<input type="reset" value="重新設定">
      
                </table>

			</div>
		</div>
	</div>
</body>

</html>