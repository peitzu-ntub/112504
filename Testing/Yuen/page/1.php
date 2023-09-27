<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>查看全部餐點</title>

    <link href="../js/allmenu.css" rel="stylesheet">

</head>
<?php

include "../bin/conn.php";

// 設置一個空陣列來放資料
$datas = array();

$sql = "SELECT meal_name FROM store_food";

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
    <div class="logout" type="button" name="按鈕名稱" onclick="location.href='newmenu2.html'">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 10px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
        <form action="menu_del.php" method="POST">
            <div class="container1">
                <div class="topinput" style="font-size: 15px;">
                    <font color="#bf6900" size="5">全部餐點</font></div>
                <div class="insidebox">
                    <div class="ininsidebox" style="width:680px;height:300px; overflow:auto;">
                        <div class="countainer">
                            <nav>
                                <div class="content1">刪除</div>
                                <div class="content2">餐點名稱</div>
                                <div class="content3">確定</div>
                            </nav>
                        </div>
                        <tbody>
                            <?php
                            for ($i = 0; $i < $datas_len; $i++) {
                                echo "<tr>";
                                echo "<td>
                                <a href='menu_del.php?meal_name=".$datas[$i]['meal_name']."'><img src=../images/trash.png></img></a></td>";
                                echo "<td>" . $datas[$i]['meal_name'] . "</td>";
                                echo "<td>
                                <a href='menu_edit.php?meal_name=".$datas[$i]['meal_name']."'><img src=../images/signature.png></img></a></td>";
                             }
                            ?>

                            </tbody>                    </div>
                </div>
            </div>
        </form>
    </div>
</body>


<script>
    //當網頁準備好的時候，做以下的動作(函式)
    $(document).ready(function () {
        //form的submit按鈕按下去的動作
        $("form").on("submit", function (e) {
            //1.先把準備拋回去的資料「序列化」整理成json格式的字串
            var dataString = $(this).serialize();

            //可以把字串顯示出來看看是否正確
            //alert(dataString);               

            //2.透過ajax(非同步JavaScript)把字串送給後端的PHP網站
            $.ajax({
                //HTTP的通訊模式有：GET、POST、DELETE。這次採用POST的模式，僅傳遞該傳遞的資料，不是整個網頁送回去
                type: "POST",
                //指定要連接的PHP位址
                url: "../bin/menu_2.php",
                //要傳送的資料內容
                data: dataString,
                //獲得正確回應時，要做的事情
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json.result == 'OK') {
                        $("#message").html('成功：\n' + json.message);
                    } else {
                        $("#message").html('失敗：\n' + json.message);
                    }
                },
                //獲得不正確的回應時，要做的事情
                error: function (response) {
                    $("#message").html(response);
                }
            });

            e.preventDefault();
        });
    });
</script>

</html>