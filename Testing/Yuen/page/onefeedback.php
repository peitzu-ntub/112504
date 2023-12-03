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

    $ord = "ASC";
    if (isset($_POST['ord']) && $_POST["ord"] == 'high')
        $ord = "DESC";

    $date_s = '';
    if (isset($_POST['date_s']) && $_POST['date_s'] != '')
        $date_s = $_POST['date_s'];

?>
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

// 設置一個空陣列來放資料
$datas = array();

$dateS = "";
if (isset($_POST['date_s']) && $_POST['date_s'] != '')
    $dateS = " and c.meal_name = '" . $_POST["date_s"] . "' ";

$sql = "SELECT date(b.start_time) as date, c.meal_name, a.score, a.evaluate
    FROM store_order_item as a
    left join store_order as b
    on a.boss_identity = b.boss_identity and a.store_id = b.store_id and a.order_no = b.order_no
    left join store_food as c
    on a.meal_id = c.meal_id
    where a.boss_identity = '$identity' 
        and a.store_id = '$store_id' 
        and b.boss_identity = '$identity' 
        and b.store_id = '$store_id'
        $dateS
        order by a.score $ord
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
    <div class="logout" type="button" name="按鈕名稱" onclick="goBack()">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 15px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
        <nav>
            <ul>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goAll()">全品項評價查詢</a></li>
                <li><a>單一品項評價查詢</a></li>
            </ul>
        </nav>
        <div class="insidebox">
        <form id="main" method="POST" action="onefeedback.php" >
                    <input type="hidden" id="boss_identity" name="boss_identity" value="<?php echo $identity; ?>" />
                    <input type="hidden" id="store_id" name="store_id" value="<?php echo $store_id; ?>" />
                    <input type="hidden" id="boss_name" name="boss_name" value="<?php echo $boss_name; ?>" />
                <div class="input-box">
                    <span class="details" style="font-size: 19px;">餐點名稱：</span>
                    <select name="date_s">
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

                        while ($cat = mysqli_fetch_array($meal, MYSQLI_ASSOC)) {
                            $meal_name = $cat['meal_name'];
                            $selected = ($meal_name == $_POST['date_s']) ? 'selected' : '';

                            echo "<option value='$meal_name' $selected>$meal_name</option>";
                        }
                        ?> 
                    </select>
                    
                    <button class="searchbutton" type="search"
                        style="font-size: 17px; width: 68px; height: 34px; background-color: #8cb87c; border-radius: 20px; border: 3px solid #8cb87c;">查詢</button>
                </div>
                <?php
                                    echo "<font size='4';>平均星星數：";
                                    $sql = "SELECT round(avg(a.score),1) score
                                    FROM store_order_item as a
                                    left join store_order as b
                                    on a.boss_identity = b.boss_identity and a.store_id = b.store_id and a.order_no = b.order_no
                                    left join store_food as c
                                    on a.meal_id = c.meal_id
                                    where a.boss_identity = '$identity' 
                                    and a.store_id = '$store_id' 
                                    and b.boss_identity = '$identity' 
                                    and b.store_id = '$store_id'
                                    $dateS
                                    ";
                                    
                                    $result = mysqli_query($con, $sql); 
                                    
                                    while($row_result = mysqli_fetch_assoc($result)) {
                                        echo $row_result['score'];}
                                ?>
                                <br>
                <?php 
    if ($ord == 'asc') 
        echo "
                        <input type=\"radio\" name=\"ord\" value=\"high\" style=\"font-size: 15px;\" checked=\"checked\">由高到低&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                        <input type=\"radio\" name=\"ord\" value=\"low\" style=\"font-size: 15px;\" checked=checked >由低到高
            ";
    else
        echo "
                        <input type=\"radio\" name=\"ord\" value=\"high\" style=\"font-size: 15px;\">由高到低&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                        <input type=\"radio\" name=\"ord\" value=\"low\" style=\"font-size: 15px;\" checked=\"checked\" >由低到高
                        <br>";
?>
                    <br>
                <div class="ininsidebox" style="width:700px;height:330px; overflow:auto;">
                    <!-- <span class="details" style="font-size: 19px;">期間平均星星數：</span> -->
                    <table id="myTable" class="tablesorter" align="center">
                    <thead>    
                    <tr>
                            <th>日期 / 時間</th>
                            <th>星星數</th>
                            <th>文字評價</th>
                        </tr>
                                </thead>
                        <tbody>
                                <?php
                                for ($i = 0; $i < $datas_len; $i++) {
                                    echo "<tr>";
                                    echo "<td style='font-size: 20px;' align='center'>". $datas[$i]['date'] . "</span>";
                                    echo "<td style='font-size: 20px;' align='center'>". $datas[$i]['score'] . "</span>";
                                    echo "<td style='font-size: 20px;' align='center'>". $datas[$i]['evaluate'] . "</span>";
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
        var boss_identity = '<?php echo $identity; ?>'; 
        var store_id = '<?php echo $store_id; ?>';
        var boss_name = '<?php echo $boss_name; ?>';
        location.href="boss_management.html?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
    function goAll() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = '<?php echo $identity; ?>'; 
        var store_id = '<?php echo $store_id; ?>';
        var boss_name = '<?php echo $boss_name; ?>';
        location.href="allfeedback.php?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
</script>
</html>