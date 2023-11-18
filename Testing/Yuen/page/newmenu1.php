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

    <title>新增餐點類型</title>
    <script src="../js/jquery-3.6.4.min.js"></script>
    <link href="../js/m.css" rel="stylesheet">

    <!--取代alert的工具-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- 若需相容 IE11，要加載 Promise Polyfill-->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>    
</head>
<?php

// 設置一個空陣列來放資料
$datas = array();

$sql = "SELECT type_name FROM food_type where boss_identity = '$identity' and store_id = '$store_id'";

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
            <span style="font-size: 15px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
        <nav>
            <ul>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goAllmenu()">全部餐點</a></li>
                <li><a>餐點類型</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goMenu2()">新增餐點</a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/nm3.html">呈現方法</a></li>
            </ul>
        </nav>

        <div class="insidebox">
            <form id="main" action="../bin/menu1.php" method="POST">            
                <div class="input-box">
                    <input type="hidden" id="boss_identity" name="boss_identity" value="">
                    <input type="hidden" id="store_id" name="store_id" value="">
                    <input type="hidden" id="data_type" name="data_type" value="menu1">
                    <div class="topinput" style="font-size: 15px;">
                        <img src="../images/edit.png" />
                        <font color="#bf6900" size="5">餐點類型：</font>
                        <input name="type_name" id="type_name" placeholder="請輸入您欲新增的餐點類型">
                        <button class="checkbutton" value="儲存" onclick="saveData();">新增</button>
                    </div>
                </div><br>

                <div class="ininsidebox" style="width:700px;height:330px; overflow:auto;">
                <table width ="500" align="center" >
                            <tr>
                                <th><font size="5">刪除</th>
                                <th><font size="5">類型名稱</th>
                                <th><font size="5">編輯</th>
                            </tr>
                            <tbody>
                            <?php
                            for ($i = 0; $i < $datas_len; $i++) {
                                echo "<tr>";
                                echo "<td align='center'>
                                <a href='type_del.php?type_name=".$datas[$i]['type_name']."'><img src=../images/trash1.png></img></a></td>";
                                echo "<td style='font-size: 25px;' align='center'> ". $datas[$i]['type_name'] . "</td>";
                                echo "<td align='center'>
                                <a href='type_edit.php?boss_identity=$identity&store_id=$store_id&type_name=".$datas[$i]['type_name']."'><img src=../images/signature.png></img></a></td>";
                                echo "</br>";
                            }
                            ?>

                            </tbody>  
                    </table>

                </div>
            </form>
        </div>
    </div>
</body>
<script>
    //儲存餐點資料、圖檔
    function saveData() {
        //把老闆身份證號、店代號，塞進隱藏欄位，一起送到後端
        var urlParams = new URLSearchParams(window.location.search);
        var bossIdentity = urlParams.get('boss_identity');
        var storeId = urlParams.get('store_id');
        document.getElementById("boss_identity").value = bossIdentity;
        document.getElementById("store_id").value = storeId;

        //透過 jQuery + ajax 進行 post 的動作
        $("form#main").submit(function(e) {
            e.preventDefault();    
            var formData = new FormData(this);
            $.ajax({
                url: "../bin/menu1.php",
                type: 'POST',
                data: formData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json.result == 'OK') {
                        //alert(json.message);
                        Swal.fire(
                            '餐點類型', //標題
                            '您所輸入的餐點類型已儲存完畢', //訊息容
                            'success' // 圖示 (success/info/warning/error/question)
                        );
                        //成功後，讓畫面重新呼叫一次
                        document.getElementById("main").reset();
                    } else {
                        Swal.fire(
                            '餐點類型', //標題
                            json.message, //訊息容
                            'error' // 圖示 (success/info/warning/error/question)
                        );
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });        
        });
    }

    function goAllmenu() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="allmenu.php?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
    function goBack() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="boss_management.html?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
    function goMenu1() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="newmenu1.php?boss_identity=" + boss_identity + "&store_id=" + store_id+ "&boss_name=" + boss_name;
    }
    function goMenu2() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="newmenu2.php?boss_identity=" + boss_identity + "&store_id=" + store_id+ "&boss_name=" + boss_name;
    }
    function goSearch() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="search_type.php?boss_identity=" + boss_identity + "&store_id=" + store_id+ "&boss_name=" + boss_name;
    }
    function goTypeedit() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="type_edit.php?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
</script>
</html>