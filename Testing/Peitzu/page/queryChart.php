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

    $date_s = '';
    if (isset($_POST['date_s']) && $_POST['date_s'] != '')
        $date_s = $_POST['date_s'];
    
    $date_e = '';
    if (isset($_POST['date_e']) && $_POST['date_e'] != '')
            $date_e = $_POST['date_e'];

    $sql = "
    select 
        date(b.start_time) as date, b.table_number, time(b.start_time) start_time, 
        b.customer_count, time(b.end_time)end_time, c.meal_name
    FROM store_order_item as a 
    left join store_order as b 
         on a.boss_identity = b.boss_identity 
         and a.store_id = b.store_id and a.order_no = b.order_no
    left join store_food as c
         on a.meal_id = c.meal_id
    where a.boss_identity = '$identity' 
    and a.store_id = '$store_id' 
    and b.boss_identity = '$identity' 
    and b.store_id = '$store_id'
    $date_s_str
    $date_e_str
    ";

    $result = mysqli_query($con, $sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>消費總額月別統計</title>

    <link href="../js/queryChart.css" rel="stylesheet">
    <!-- Chart.js v2.9.3 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
</head>

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
                        <span style="font-size: 28px;">星星排行榜</span>
                    </div>
                </div>
                <div class="twosmall">
                    
                    <p class="inline-form">
                        日期區間：
<?php 
    if ($date_s != '') 
        echo "
                        <input type=\"date\" name=\"date_s\" style=\"font-size: 20px;\" value=\"$date_s\">";
        else echo "
                        <input type=\"date\" name=\"date_s\" style=\"font-size: 20px;\">";
?> 
                        &nbsp;至&nbsp;&nbsp;
<?php 
    if ($date_e != '') 
        echo "
                        <input type=\"date\" name=\"date_e\" style=\"font-size: 20px;\" value=\"$date_e\">";
        else echo "
                        <input type=\"date\" name=\"date_e\" style=\"font-size: 20px;\">";
?> 
                        <button type="button"  onclick="createChart();">查詢</button>
                    </p>

                    <canvas id="myChart" width="400" height="200"></canvas>                     

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

    function createChart() {
        var ctx = document.getElementById("myChart");
        var chart = new Chart(ctx, {
            type: "bar", // 圖表類型
            data: {
                labels: ["漢堡", "雞排飯", "味噌湯", "肉包", "大腸包小腸", "紅茶拿鐵"], //顯示區間名稱
                datasets: [
                {
                    label: "星星數", // tootip 出現的名稱
                    data: [12, 19, 3, 5, 6, 3], // 資料
                    backgroundColor: [
                    "rgba(255, 99, 132, 0.2)", // 第一個 bar 顏色
                    "rgba(54, 162, 235, 0.2)",
                    "rgba(255, 206, 86, 0.2)",
                    "rgba(75, 192, 192, 0.2)",
                    "rgba(153, 102, 255, 0.2)",
                    "rgba(255, 159, 64, 0.2)"
                    ],
                    borderColor: [
                    "rgba(255,99,132,1)",
                    "rgba(54, 162, 235, 1)",
                    "rgba(255, 206, 86, 1)",
                    "rgba(75, 192, 192, 1)",
                    "rgba(153, 102, 255, 1)",
                    "rgba(255, 159, 64, 1)"
                    ],
                    borderWidth: 1
                }
                ]
            },
        });
    }


</script>

</html>