<?php
    include "../bin/conn.php";
    $identity = $_GET["boss_identity"];

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>選擇店家</title>

	<link href="../js/select_store.css" rel="stylesheet">
    <script src="../js/jquery-3.6.4.min.js"></script>

</head>

<body>
	<div class="logout" type="button" name="按鈕名稱" onclick="location.href='worker.html'">
        <div align="left">
            <img src="../images/logout.png" alt="登出icon" />
            <span style="font-size: 15px;">登出</span>
        </div>
	</div>

	<div class="title">
        <div align="left">
            <img src="../images/restaurant.png" alt="店家圖片" />
            <span style="font-size: 28px;">請選擇您的店家</span>
        </div>
	</div>

	<div class="who">
        <div align="left">
            <img src="../images/worker64.png" alt="老闆圖片" />
            <span style="font-size: 28px;"><div id="boss_name">老闆名字</div></span>
        </div>
	</div>

	<div class="container-wrapper">
        <div class="container1">
            <font size="25">還沒有店家嗎?快去新增吧!</font>
       </div>
<!--         <button class="left-button">左邊按鈕</button>
        <button class="right-button">右邊按鈕</button> -->
    </div>

	<div class="addstore" type="button" name="按鈕名稱" onclick="goRegister();">
        <div align="right">
            <img src="../images/addstore.png" alt="新增店家icon" />
            <span style="font-size: 25px;">新增店家</span>
        </div>
	</div>
</body>

<script>
    function goRegister() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var boss_name = urlParams.get('boss_name');

        location.href='store_register_copy.php?boss_identity=' + boss_identity + '&boss_name=' + boss_name + "&back=0";
    }

    //當網頁準備好的時候，做以下的動作(函式)
    $(document).ready(function () {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_name = urlParams.get('boss_name');
        document.getElementById("boss_name").innerHTML = boss_name;
    });
</script>
<!--<div class="button1">
				<input value="港式飲茶店"onclick="location.href='management.html'">
			</div> -->
</html>