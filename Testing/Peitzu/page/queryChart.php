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

    $chartType = $_POST["chartType"];
    //echo $chartType;
    //echo "<br>";


    $date_s = '';
    $date_s_str = '';
    if (isset($_POST['date_s']) && $_POST['date_s'] != '') {        
        $date_s = $_POST['date_s'];
    }
    if ($date_s != '') {
        $date_s_str = " and o.start_time >= '$date_s'";
    }
    
    $date_e = '';
    $date_e_str = '';
    if (isset($_POST['date_e']) && $_POST['date_e'] != '') {
        $date_e = $_POST['date_e'];
    }
    if ($date_e != '') {
        $date_e_str = " and o.start_time <= '$date_e'";
    }

    $order_by = "";
    if ($chartType == "1")
        $order_by = " order by sum(oi.count) desc";
    else if ($chartType == "2")
        $order_by = " order by sum(oi.score) desc";
    else if ($chartType == "3")
        $order_by = " order by avg(oi.score) desc";

    $sql = "
    select f.meal_name, sum(oi.count) as count, sum(oi.score) as sum, avg(oi.score) as avg
    from store_order_item oi
    join store_food f on f.boss_identity = oi.boss_identity and f.store_id = oi.store_id and f.meal_id = oi.meal_id
    where oi.boss_identity = '$identity' and oi.store_id = '$store_id' 
    $date_s_str
    $date_e_str
    group by f.meal_name
    $order_by    
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
    <script src="../js/jquery-3.6.4.min.js"></script>
</head>

<body>
    <div class="logout" type="button" name="按鈕名稱" onclick="goBack()">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 15px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
    <form id="main" method="POST" action="queryChart.php" >
                    <input type="hidden" id="boss_identity" name="boss_identity" value="<?php echo $identity; ?>" />
                    <input type="hidden" id="store_id" name="store_id" value="<?php echo $store_id; ?>" />
                    <input type="hidden" id="boss_name" name="boss_name" value="<?php echo $boss_name; ?>" />
            <div class="subject">
                <div class="title">
                    <div align="left">
                        <img src="../images/bar-chart.png" alt="icon圖片" />
                        <span style="font-size: 28px;">報表</span>
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
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                         <select id="chartType" name="chartType">
                             <!--<option value="0" >請選擇</option>-->
                            <option value="1" <?php if ($chartType == "1") echo "selected=\"selected\""; ?>>點餐量統計</option>
                            <!-- <option value="2" <?php if ($chartType == "2") echo "selected=\"selected\""; ?>>星星數統計</option> -->
                            <option value="3" <?php if ($chartType == "3") echo "selected=\"selected\""; ?>>星星數平均</option>
                        </select>
                        <input type="submit" value="查詢">
                    </p>
                    <!-- 要放置圖形的位置 -->
                    <canvas id="myChart" width="400" height="200"></canvas>                     
                </div>
        </form>
    </div>
</body>

<script>
    $(document).ready(function(){
<?php 
    if (isset($chartType)) {
        echo "
        createChart();
        ";
    }
?>        
    });                


     function goBack() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = '<?php echo $identity; ?>'; 
        var store_id = '<?php echo $store_id; ?>';
        var boss_name = '<?php echo $boss_name; ?>';
        location.href="boss_management.html?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }

<?php
    $backgroundColor = [
        "rgba(255, 99, 132, 0.2)",
        "rgba(54, 162, 235, 0.2)",
        "rgba(255, 206, 86, 0.2)",
        "rgba(75, 192, 192, 0.2)",
        "rgba(153, 102, 255, 0.2)",
        "rgba(255, 159, 64, 0.2)"
    ];

    $borderColor = [
        "rgba(255,99,132,1)",
        "rgba(54, 162, 235, 1)",
        "rgba(255, 206, 86, 1)",
        "rgba(75, 192, 192, 1)",
        "rgba(153, 102, 255, 1)",
        "rgba(255, 159, 64, 1)"
    ];

    $bgIndex = 0;
    $bdIndex = 0;

    $chartLabels = "";
    $chartData = "";
    $chartBackgroundColor = "";
    $chartBorderColor = "";    
    $label = "";

    if ($chartType == "1") {
        $label = "點餐量統計";
    } else if ($chartType == "2") {
        $label = "星星數統計";
    } else {
        $label = "星星數平均";
    }

    while ($row = mysqli_fetch_assoc($result)) {
        //X軸的資料
        $chartLabels = $chartLabels . ', "' . $row['meal_name'] . '"';
        //值
        $d = 0;
        if ($chartType == "1") {
            $d = $row['count'];
            $label = "點餐量統計";
        } else if ($chartType == "2") {
            $d = $row['sum'];
            $label = "星星數統計";
        } else {
            $d = $row['avg'];
            $label = "星星數平均";
        }
        if (!isset($d) or $d == "") {
            $d = "0";
        }
        $d = round($d, 1);
        //資料
        $chartData = $chartData . ',' . $d;
        //背景的顏色
        $chartBackgroundColor = $chartBackgroundColor . ', "' . $backgroundColor[$bgIndex] . '"';        
        $bgIndex++;
        if ($bgIndex == 5) { $bgIndex = 0; }
        //邊界的顏色
        $chartBorderColor = $chartBorderColor . ', "' . $borderColor[$bdIndex] . '"';
        $bdIndex++;
        if ($bdIndex == 5) { $bdIndex = 0; }
    }

    $chartLabels = substr($chartLabels, 1);
    $chartData = substr($chartData, 1);
    $chartBackgroundColor = substr($chartBackgroundColor, 1);
    $chartBorderColor = substr($chartBorderColor, 1);
?>    
    function createChart() {
        var ctx = document.getElementById("myChart");
        var chart = new Chart(ctx, {
            type: "bar", // 圖表類型
            data: {
                labels: [
                    <?php echo $chartLabels; ?>
                ], //顯示區間名稱
                datasets: [
                {
                    label: "<?php echo $label; ?>", // tootip 出現的名稱
                    data: [
                        <?php echo $chartData; ?>
                    ], // 資料
                    backgroundColor: [
                        <?php echo $chartBackgroundColor; ?>
                    ],
                    borderColor: [
                        <?php echo $chartBorderColor; ?>
                    ],
                    borderWidth: 1
                }
                ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            //Y軸的值，從零開始顯示
                            beginAtZero:true,
                            //Y軸的間格，以1為單位
<?php 
    if ($chartType == '1')  
        echo "                           
                            stepSize: 1";
    else
        echo "
                            stepSize: 0.5";                            
?>
                        }
                    }]
                }
            },            
        });
    }
</script>

</html>