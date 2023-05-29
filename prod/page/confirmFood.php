<html>

<?php 
    include "../bin/conn.php";
    $identity = $_GET['identity'];
    $store_id = $_GET['store_id'];
    $order_no = $_GET["order_no"];
    $meal_id = $_GET['meal_id']; 
    $cart_qty = 1;

    //查詢購物車裡面有沒有數量
    try {
        $sql = "select * from store_cart 
                where boss_identity = '$identity' 
                and store_id = '$store_id'
                and order_no = '$order_no'
                and meal_id = '$meal_id'
                ";
        $cart_data = mysqli_query($con, $sql);
        if (!$cart_data) {
            throw new Exception(mysqli_error($con));
        }
        if ($cart = mysqli_fetch_array($cart_data, MYSQLI_ASSOC)) {
            $cart_qty = $cart['meal_qty'];
        }
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }

?>

<head>
    <title>點餐</title>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../js/bootstrap.min.css" >
	<link rel="stylesheet" href="../js/bootstrap.min.4.6.2.css">
	<link rel="stylesheet" href="../js/pickFood.css" >

    <script src="../js/jquery-3.6.4.min.js"></script>

	<script type="text/javascript">
		$(function () {
			// This button will increment the value
			$('.qtyplus').click(function (e) {
				// Stop acting like a button
				e.preventDefault();
				// Get the field name
				fieldName = $(this).attr('field');
				// Get its current value
				var currentVal = parseInt($('input[name=' + fieldName + ']').val());
				// If is not undefined
				if (!isNaN(currentVal)) {
					// Increment
					$('input[name=' + fieldName + ']').val(currentVal + 1);
				} else {
					// Otherwise put a 0 there
					$('input[name=' + fieldName + ']').val(1);
				}
			});
			// This button will decrement the value till 0
			$(".qtyminus").click(function (e) {
				// Stop acting like a button
				e.preventDefault();
				// Get the field name
				fieldName = $(this).attr('field');
				// Get its current value
				var currentVal = parseInt($('input[name=' + fieldName + ']').val());
				// If it isn't undefined or its greater than 0
				if (!isNaN(currentVal) && currentVal > 1) {
					// Decrement one
					$('input[name=' + fieldName + ']').val(currentVal - 1);
				} else {
					// Otherwise put a 0 there
					$('input[name=' + fieldName + ']').val(1);
				}
			});
		});

        //「選好了」的click，把餐點的數量傳回去
        function saveQty() {
            var qty = parseInt($('input[name=quantity]').val());
            var identity= $('input[name=identity]').val();
            var store_id= $('input[name=store_id]').val();
            var order_no= $('input[name=order_no]').val();
            var meal_id= $('input[name=meal_id]').val();
            var newUrl = "pickFood.php?identity="+identity+"&store_id="+store_id+"&order_no="+order_no+"&meal_id="+meal_id+"&qty="+qty;
            window.location.replace(newUrl);
        }
	</script>

</head>

<?php 
    //查詢餐點的詳細資料，準備用來呈現於畫面上
    try {
        $sql = "select * from store_food where boss_identity = '$identity' and store_id = '$store_id' and meal_id='$meal_id'";
        $food_data = mysqli_query($con, $sql);
        if (!$food_data) {
            throw new Exception(mysqli_error($con));
        }

        $food = mysqli_fetch_array($food_data, MYSQLI_ASSOC);
        $meal_name = $food['meal_name'];
        $meal_note = $food['meal_note'];
        $meal_price = $food['meal_price'];
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
?>

<body>
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
			<center><h3>
					<?php echo "菜名：$meal_name"; ?>
				</h3>
                <?php echo "<img alt='$meal_name' src='../images/$meal_id.upload.jpg' width=300 />"; ?>
				<div class="row">
					<div class="col-md-6">
					<h3>
							$<?php echo $meal_price; ?>
						</h3>
					</div>
					<div class="col-md-6">
						<h3>
							⭐⭐⭐⭐⭐
						</h3>
					</div>
				</div>
				<p>
					介紹：<?php echo $meal_note; ?>
				</p>

				<form id='myform' method='POST' action='#'>
                <?php echo "
					<input type='button' value='-' class='qtyminus' field='quantity' />
                    <input type='text' name='quantity' value='$cart_qty' class='qty' />
                    <input type='button' value='+' class='qtyplus' field='quantity' />
                    <input type='hidden' id='identity' name='identity' value='$identity'>
                    <input type='hidden' id='store_id' name='store_id' value='$store_id'>
                    <input type='hidden' id='order_no' name='order_no' value='$order_no'>
                    <input type='hidden' id='meal_id' name='meal_id' value='$meal_id'>";
?>                    
				</form></center>


		<div class="row" align='right'>
			<div class="col-md-6">
                <button name="check" type="button" class="registbutton" onclick=saveQty()>
                    加入購物車
				</button>
			<?php echo "	
				<a href='pickFood.php?identity=$identity&store_id=$store_id&order_no=$order_no'>";
			?>							
				<button type="button" class="registbutton">
					取消
				</button>
						
			</div>
		</div>
		
	</div>

</body>

</html>