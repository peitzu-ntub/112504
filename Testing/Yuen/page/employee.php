<?php
    session_start();
    include "../bin/conn.php";

    $identity = $_GET["boss_identity"];
    $store_id = $_GET["store_id"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>員工資料</title>
<!--取代alert的工具-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- 若需相容 IE11，要加載 Promise Polyfill-->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>    
	<link href="../js/employee.css" rel="stylesheet">
</head>
<?php

// 設置一個空陣列來放資料
$datas = array();

$sql = "SELECT staff_id, staff_name FROM store_staff where boss_identity = '$identity' and store_id = '$store_id'";


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
	<div class="logout" type="button" name="按鈕名稱" onclick="goBack()">
		<div align="left">
			<img src="../images/back.png" alt="返回icon" />
			<span style="font-size: 13px;">返回</span>
		</div>
	</div>
	<div class="container-wrapper">
        <form id="main">
        <input type="hidden" id="boss_identity" name="boss_identity" 
<?php      
                //把老闆身份證號、店代號，放進隱藏欄位。供POST時使用
                echo "value=\"$identity\">";
?>                
                <input type="hidden" id="store_id" name="store_id" 
<?php                
                //把老闆身份證號、店代號，放進隱藏欄位。供POST時使用
                echo "value=\"$store_id\">";
?>                
                <input type="hidden" id="data_type" name="data_type" value="staff_in">

                <input type="hidden" id="data_value" name="data_value" value="">
			<div class="container1">
				<div align="center">
					<font size="19">員工資料管理</font>
				</div><br>

				<div class="input-box">
					<div class="details" style="font-size: 19px;">員工編號：</span>
						<input type="search" name="查詢" id="查詢" placeholder="請輸入員工編號"
							style="font-size: 15px;">

						<button class="searchbutton" type="search"
							style="font-size: 17px; width: 68px; height: 34px; background-color: #8cb87c; border-radius: 20px; border: 3px solid #8cb87c;">搜尋</button>
					</div>
				</div><br>

				<div class="insidebox">
					<div class="ininsidebox" style="width:680px;height:290px; overflow:auto;">
                        <table width ="500" align="center" >
							<tr>
								<th><font size="5">刪除</th>
								<th><font size="5">員工編號</th>
								<th><font size="5">員工姓名</th>
								<th><font size="5">編輯</th>
							</tr>
                            <tbody>
                            <?php
                            for ($i = 0; $i < $datas_len; $i++) {
                                $staff_name = $datas[$i]['staff_name'];
                                $staff_id = $datas[$i]['staff_id'];
                                echo "
                            <tr>
                                <td align='center'>
                                <img src=../images/trash1.png onclick='deleteData(\"$staff_id\");'></img>
                                <!--
                                <button id=\"btnSave\" name=\"btnSave\" class=\"checkbutton\" onclick='deleteData(\"$staff_name\");'>刪</button>
                                <a href='type_del.php?boss_identity=$identity&store_id=$store_id&staff_name=$staff_name'>
                                        <img src=../images/trash1.png></img>
                                    </a>
                                -->
                                </td>
                                <td style='font-size: 25px;' align='center'>
                                    $staff_id
                                </td>
                                </td>
                                <td style='font-size: 25px;' align='center'>
                                    $staff_name
                                </td>
                                <td align='center'>
                                    <a href='staff_edit.php?boss_identity=$identity&store_id=$store_id&staff_name=$staff_name'>
                                        <img src=../images/signature.png></img>
                                    </a>
                                </td>
                            </tr>";
                            }
                            ?>

                        </tbody>						</table>
					</div>
				</div>
			</div>
			<div class="addemployee" type="button" name="按鈕名稱" onclick="goCreate()">
				<div align="right">
					<!-- <img src="../images/employee.png" alt="新增員工icon" /> -->
					<span style="font-size: 25px;">新增員工</span>
				</div>
			</div>
		</form>
	</div>
	
</body>
<script>
        function doSubmit() {
        var dataString = $("form#main").serialize();
        // alert('submiting: ' + dataString);
        $.ajax({
            //HTTP的通訊模式有：GET、POST、DELETE。這次採用POST的模式，僅傳遞該傳遞的資料，不是整個網頁送回去
            type: "POST",
            //指定要連接的PHP位址
            url: "../bin/staff_in.php",
            //要傳送的資料內容
            data: dataString,
            //獲得正確回應時，要做的事情
            success: function (response) {
                // alert(response);
                var json = $.parseJSON(response);
                var msgIcon = 'success';
                if (json.result != 'OK') msgIcon = 'error';
                Swal.fire(
                    '餐點', //標題
                    json.message, //訊息容
                    msgIcon // 圖示 (success/info/warning/error/question)
                ).then((result) => {
                    location.reload();
                });
            },
            //獲得不正確的回應時，要做的事情
            error: function (response) {
                alert ('錯誤');
            },
        });
    }

    function deleteData(staffName) {
        document.getElementById("data_type").value = "staff_delete";
        document.getElementById("data_value").value = staffName;

        // alert(typeName);

        Swal.fire({
            title: "餐點類型",
            text: "確定要刪除 " + staffName +" 嗎？",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "是",
            cancelButtonText: "取消",
        }).then((result) => {
            if (result.isConfirmed) {
                doSubmit();
            }
        });

    }
    function goBack() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var boss_name = urlParams.get('boss_name');
        var store_id = urlParams.get('store_id');
        location.href="boss_management.html?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
    function goCreate() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="create.php?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }

</script>
</html>