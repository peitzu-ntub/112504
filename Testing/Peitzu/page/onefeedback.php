<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>評價查詢</title>

    <link href="../js/feedback.css" rel="stylesheet">
</head>
<?php

include "../bin/conn.php";

// 設置一個空陣列來放資料
$datas = array();



$sql = "SELECT date(b.start_time) as date, c.meal_name, a.score, a.evaluate
FROM store_order_item as a
left join store_order as b
on a.boss_identity = b.boss_identity and a.store_id = b.store_id and a.order_no = b.order_no
left join store_food as c
on a.meal_id = c.meal_id
";

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
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/allfeedback.php">全品項評價查詢</a></li>
                <li><a>單一品項評價查詢</a></li>
            </ul>
        </nav>
        <div class="insidebox">
            <form action="search_onefeedback.php" method="POST">
                <div class="input-box">
                    <span class="details" style="font-size: 19px;">餐點名稱：</span>
                    <select name="查詢" id="查詢">
                    <?php
                                    $sql = "
                                    SELECT c.meal_name
                                    FROM `112504`.store_order_item as a
                                    left join `112504`.store_order as b
                                    on a.boss_identity = b.boss_identity and a.store_id = b.store_id and a.order_no = b.order_no
                                    left join `112504`.store_food as c
                                    on a.meal_id= c.meal_id
                                    group by c.meal_name";
                                    $meal = mysqli_query($con, $sql);
                                    while ($cat = mysqli_fetch_array($meal,MYSQLI_ASSOC)) {

                                        $meal_name=$cat['meal_name'];
                                        echo "  <option value='$meal_name'>$meal_name</option>";
                                    }
                                    ?> 
                                </select>
                    <button class="searchbutton" type="search"
                        style="font-size: 17px; width: 68px; height: 34px; background-color: #8cb87c; border-radius: 20px; border: 3px solid #8cb87c;">查詢</button>
                </div><br>
                <div>
                    <input type="radio" name="score" value="high"
                        style="font-size: 15px;">由高到低&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                    <input type="radio" name="score" value="low" style="font-size: 15px;">由低到高
                </div><br>

                <div class="ininsidebox" style="width:700px;height:330px; overflow:auto;">
                    <!-- <span class="details" style="font-size: 19px;">期間平均星星數：</span> -->
                    <table align="center">
                        <tr>
                            <th>日期 / 時間</th>
                            <th>餐點</th>
                            <th>平均星星數</th>
                            <th>文字評價</th>
                        </tr>
                        <tbody>
                                <?php
                                for ($i = 0; $i < $datas_len; $i++) {
                                    echo "<tr>";
                                    echo "<td style='font-size: 25px;' align='center'>". $datas[$i]['date'] . "</span>";
                                    echo "<td style='font-size: 25px;' align='center'>". $datas[$i]['meal_name'] . "</span>";
                                    echo "<td style='font-size: 25px;' align='center'>". $datas[$i]['score'] . "</span>";
                                    echo "<td style='font-size: 25px;' align='center'>". $datas[$i]['evaluate'] . "</span>";
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

</html>