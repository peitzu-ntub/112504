<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>員工資料</title>

	<link href="../js/create.css" rel="stylesheet">
</head>
<?php

include "../bin/conn.php";

// 設置一個空陣列來放資料
$datas = array();

$sql = "SELECT staff_id, staff_name FROM store_staff";

$result = mysqli_query($con, $sql); // 用mysqli_query方法執行(sql語法)將結果存在變數中

// 如果有資料
if ($result) {
    // mysqli_num_rows方法可以回傳我們結果總共有幾筆資料
    if (mysqli_num_rows($result) > 0) {
        // 取得大於0代表有資料
        // while迴圈會根據資料數量，決定跑的次數
        // mysqli_fetch_assoc方法可取得一筆值
        while ($row = mysqli_fetch_assoc($result)) {
            // 每跑一次迴圈就抓一筆值，最後放進data陣列中
            $datas[] = $row;
        }
    }
    // 釋放資料庫查到的記憶體
    mysqli_free_result($result);
} else {
    echo "{$sql} 語法執行失敗，錯誤訊息: " . mysqli_error($link);
}
// 處理完後印出資料
if (!empty($result)) {
    // 如果結果不為空，就利用print_r方法印出資料
    //print_r($datas);
} else {
    // 為空表示沒資料
    echo "查無資料";
}
echo "<br><br>";
//echo $datas[0]['sf_name']; // 印出第0筆資料中的sf_name欄位值

//使用表格排版用while印出
$datas_len = count($datas); //目前資料筆數

?>
<body>
	<div class="logout" type="button" name="按鈕名稱" onclick="location.href='create.html'">
		<div align="left">
			<img src="../images/back.png" alt="返回icon" />
			<span style="font-size: 13px;">返回</span>
		</div>
	</div>
	<div class="container-wrapper">
		<form action="#">
			<div class="container1">
				<div align="center">
					<font size="20">員工資料管理</font>
				</div><br><br>

				<div class="input-box">
					<span class="details" style="font-size: 19px;">員工編號：</span>
					<input type="search" name="staff_id" id="staff_id" placeholder="請輸入員工編號" style="font-size: 15px;">

					<button class="searchbutton" type="search"
						style="font-size: 17px; width: 68px; height: 34px; background-color: #8cb87c; border-radius: 20px; border: 3px solid #8cb87c;">搜尋</button>
				</div><br>

				<div class="ininsidebox">
					<table width="50%">
						<tr>
							<td style="text-align:center">
								<div class="sidebar_left">刪除</div>
							</td>
							<td style="text-align:center">
								<div class="content1">員工編號</div>
							</td>
							<td style="text-align:center">
								<div class="content2">員工姓名</div>
							</td>
							<td style="text-align:center">
								<div class="sidebar_right">編輯</div>
							</td>
						</tr>
                        <tbody>
                        <?php
                        for ($i = 0; $i < $datas_len; $i++) {
                            echo "<tr>";
                            echo "<td>		
                            <a href='staff_del.php?staff_id=".$datas[$i]['staff_id']."'><img src=../images/trash.png></img></a></td>";
							echo "<td>"; 
							echo "<span style='font-size: 25px;' align='center' > " .  $datas[$i]['staff_id']. "</span>";
							
                            echo "<td>" ;
							echo "<span style='font-size: 25px;' text-align='center'> " . $datas[$i]['staff_name'] .  "</span>";
		

                            echo "<td>
                            <a href='staff_edit.php?staff_id=".$datas[$i]['staff_id']."'><img src=../images/signature.png></img></a></td>";
                        } 
                        ?>

                    </tbody>
					</table>
				</div>
				<!-- </div> -->
			</div>
		</form>
	</div>
</body>

</html>