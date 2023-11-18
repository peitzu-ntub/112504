<?php
    session_start();
    include "../bin/conn.php";

    //PHP是在後端(Server)運作的程式，Html與JavaScript則是在前端(Client)運作的程式
    //在Server端，透過PHP將身份證與店代號，保留於隱藏欄位中，以傳到前端，做後續的應用
    $identity = $_GET["boss_identity"];
    $store = $_GET["store_id"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>新增餐點類型</title>

    <link href="../js/m.css" rel="stylesheet">
</head>
<?php

include "../bin/conn.php";

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
    <div class="logout" type="button" name="按鈕名稱" onclick="goBack();">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 15px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
        <nav>
            <ul>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goAll();">全部餐點</a></li>
                <li><a>餐點類型</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goMenu();">新增餐點</a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/nm3.html">呈現方法</a></li>
            </ul>
        </nav>

        <div class="insidebox">
            <form action="menu1.php" method="POST">            
                <div class="input-box">
                    <div class="topinput" style="font-size: 15px;">
                        <img src="../images/edit.png" />
                        <font color="#bf6900" size="5">餐點類型：</font>
                        <input name="type_name" id="type_name" placeholder="請輸入您欲新增的餐點類型">
                        <button class="checkbutton" type="submit" value="儲存">新增</button>
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
                                <a href='type_edit.php?type_name=".$datas[$i]['type_name']."'><img src=../images/signature.png></img></a></td>";
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
    function goBack() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var boss_name = urlParams.get('boss_name');
        location.href="boss_management.html?boss_identity=" + boss_identity + "&boss_name=" + boss_name;
    }
    function goType() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        location.href="newmenu1.php?boss_identity=" + boss_identity + "&store_id=" + store_id;
    }
    function goMenu() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        location.href="newmenu2.php?boss_identity=" + boss_identity + "&store_id=" + store_id;
    }
    function goAll() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        location.href="allmenu.php?boss_identity=" + boss_identity + "&store_id=" + store_id;
    }
</script>

</html>