<?php
    include "../bin/conn.php";
    
    if (isset($_GET["boss_identity"]))
        $identity = $_GET["boss_identity"];
    if (isset($_GET['store_id']))
        $store_id = $_GET["store_id"];
    if (isset($_GET["boss_name"]))
        $boss_name = $_GET["boss_name"];

    if (isset($_POST["boss_identity"]))
        $identity = $_POST["boss_identity"];
    if (isset($_POST['store_id']))
        $store_id = $_POST["store_id"];
    if (isset($_POST["boss_name"]))
        $boss_name = $_POST["boss_name"];

    $date_e = '';
    if (isset($_POST['date_e']) && $_POST['date_e'] != '')
            $date_e = $_POST['date_e'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>消費總額月別統計</title>

    <link href="../js/queryChart.css" rel="stylesheet">

</head>
<?php


// 設置一個空陣列來放資料
$datas = array();
$dateE = "";
if (isset($_POST['date_e']) && $_POST['date_e'] != '')
    $dateE = " and date(b.start_time) = '" . $_POST["date_e"] . "' ";

$sql = "select date(b.start_time) as date, b.table_number, time(b.start_time) start_time, b.customer_count, time(b.end_time)end_time, c.meal_name
FROM store_order_item as a 
left join store_order as b
on a.boss_identity = b.boss_identity and a.store_id = b.store_id and a.order_no = b.order_no
left join store_food as c
on a.meal_id = c.meal_id
where a.boss_identity = '$identity' and a.store_id = '$store_id' and b.boss_identity = '$identity' and b.store_id = '$store_id'
$dateE
";


$result = mysqli_query($con, $sql);


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
    <form id="main" method="POST" action="record.php" >
                    <input type="hidden" id="boss_identity" name="boss_identity" value="<?php echo $identity; ?>" />
                    <input type="hidden" id="store_id" name="store_id" value="<?php echo $store_id; ?>" />
                    <input type="hidden" id="boss_name" name="boss_name" value="<?php echo $boss_name; ?>" />
            <div class="subject">
                <div class="title">
                    <div align="left">
                        <img src="../images/bar-chart.png" alt="icon圖片" />
                        <span style="font-size: 28px;">消費總額月別統計</span>
                    </div>
                </div>
                <div class="twosmall">
                    
                        <p class="inline-form">
                            日期區間：
                            <?php 
                        if ($date_e != '') 
                        echo "
                        <input type=\"date\" name=\"date_s\" style=\"font-size: 20px;\" value=\"$date_e\">";
                        else echo "
                        <input type=\"date\" name=\"date_s\" style=\"font-size: 20px;\">";
?> 
至&nbsp;
<input type="date" name="date_e" style="font-size: 20px;">
                            <input type="submit" value="查詢">
                        </p>
                        <div class="ininsidebox" style="width:900px;height:330px; overflow:auto;">
                            <table border='1' align='center' class='order-table'>
                                <thead>
                                    <!-- <tr>
                                        <th>日期</th>
                                        <th>桌號</th>
                                        <th>開桌時間</th>
                                        <th>人數</th>
                                        <th>關桌時間</th>
                                        <th>餐點</th>
                                    </tr> -->
                                </thead>
                                <tbody>
                             <?php
                            for ($i = 0; $i < $datas_len; $i++) {
                                echo "<tr>";
                                echo "<td>". $datas[$i]['date'] . "</span>";
                                echo "<td>". $datas[$i]['table_number'] . "</span>";
                                echo "<td>". $datas[$i]['start_time'] . "</span>";
                                echo "<td>". $datas[$i]['customer_count'] . "</span>";
                                echo "<td>". $datas[$i]['end_time'] . "</span>";
                                echo "<td>". $datas[$i]['meal_name'] . "</span>";
                                echo "</br>";
                            }
                            ?>
                                </tbody>
                            </table>
                        </div>
        </form>
    </div>
</body>
<script>
     function goBack() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = '<?php echo $identity; ?>'; 
        var store_id = '<?php echo $store_id; ?>';
        var boss_name = '<?php echo $boss_name; ?>';
        location.href="boss_management.html?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
</script>

</html>