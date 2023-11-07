<?php
    session_start();
    include "../bin/conn.php";

    //todo, 這是假的資料
    //預設的資料來源，是從登入而來。登入、選擇店家後，就會把以下這兩個資訊，放進SESSION裡，保留在Server端
    //讓同一個人的接續連線，可以直接拿來用
    if (!isset($_SESSION["identity"])) {
        $_SESSION["identity"] = "A123456789";
    }
    if (!isset($_SESSION["store_id"])) {
        $_SESSION["store_id"] = "S01";
    }

    //PHP是在後端(Server)運作的程式，Html與JavaScript則是在前端(Client)運作的程式
    //在Server端，透過PHP將身份證與店代號，保留於隱藏欄位中，以傳到前端，做後續的應用
    $boss = $_SESSION["identity"];
    $store = $_SESSION["store_id"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>查看全部餐點</title>

    <link href="../js/m.css" rel="stylesheet">
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
    <div class="logout" type="button" name="按鈕名稱" onclick="location.href='boss_management.html'">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 15px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
        <nav>
            <ul>
                <li><a>全部餐點</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/newmenu1.php">餐點類型</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/newmenu2.php">新增餐點</a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/nm3.html">呈現方法</a></li>
            </ul>
        </nav>

        <div class="insidebox">
            <form action="search_type.php" method="POST">
                <div class="input-box">
                    <img src="../images/loupe.png" />
                    <font color="#bf6900" size="5">餐點類型：</font>
                    <select name="查詢" id="查詢">
                                <?php
                                    $sql = "
                                        select * from food_type
                                        where boss_identity = '$boss' and store_id = '$store'";
                                    $meal_type = mysqli_query($con, $sql);
                                    while ($cat = mysqli_fetch_array($meal_type,MYSQLI_ASSOC)) {

                                        $type_name=$cat['type_name'];
                                        echo "  <option value='$type_name'>$type_name</option>";
                                    }
                                    ?> 
                                </select>
                    <button class="searchbutton" type="search">查詢</button>
                </div><br>

                <div class="ininsidebox" style="width:700px;height:330px; overflow:auto;">
                    <table width ="500" align="center">
                        <tr>
                            <th><font size="5">刪除</th>
                            <th><font size="5">餐點名稱</th>
                            <th><font size="5">編輯</th>
                        </tr>
                        <tbody>
                            <?php
                            for ($i = 0; $i < $datas_len; $i++) {
                                echo "<tr>";
                                echo "<td align='center'>   
                                <a href='menu_del.php?meal_name=".$datas[$i]['meal_name']."'><img src=../images/trash1.png></img></a></td>";
                                echo "<td style='font-size: 25px;' align='center'>". $datas[$i]['meal_name'] . "</span>";
                                echo "<td align='center'>
                                <a href='menu_edit.php?meal_name=".$datas[$i]['meal_name']."'><img src=../images/signature.png></img></a></td>";
                                echo "</br>";
                            }
                            ?>

                            </tbody>  
                    </table>

                </div>
            </form>
        </div>
    </div>
    </div>
</body>
</html>