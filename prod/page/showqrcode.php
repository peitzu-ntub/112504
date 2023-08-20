<?php
    include "../bin/conn.php";

    //從url傳進來的參數
    $identity = $_GET['identity'];
    $store_id = $_GET['store_id'];
    $order_no = $_GET["order_no"];
    $desk = $_GET['desk']; 
    $persons = $_GET['persons'];
    $emp = $_GET['emp'];
    

    //取得目前URL的「目錄」結構
    $appRoot = __DIR__;
    $httpHost = $_SERVER['HTTP_HOST'];
    $baseUrl = "http://". $httpHost. substr($appRoot, strlen($_SERVER['DOCUMENT_ROOT']));
    $baseUrl = str_replace("\\", "/", $baseUrl);
    //echo "$baseUrl<br>";

    //組合出「選擇餐點」的URL
    //---注意---
    //透過這個URL可以直接點選而進入選餐的畫面。不過這是測試時的用法
    //真實操作時，是把Qrcode列印出來(或顯示在畫面)。
    //而列印或顯示時，是透過參數，把curQrcodeUrl傳給newQrcode.php。所以，必須先把curQrcodeUrl做好編碼
    //若沒有先做編碼，會得到這樣的結果：
    //newQrcode.php?data=http://xxx.xxx/pickFood.php?identity=A123456789&store_id=S01&....
    //這個錯誤的url裡面，有兩個?，會造成誤判，第二個?後的參數，都會消失
    //所以，在進行url組合時，必要時，得urlencode()
    $curQrcodeUrl = urlencode("$baseUrl/pickFood.php?identity=$identity&store_id=$store_id&order_no=$order_no");
    //echo "$curQrcodeUrl<br>";

    //2023.05.30 完成開桌，等同於新增一張訂單。因此，可以把開桌的相關資訊，當成一張訂單，記錄起來
    $sql = "insert into store_order (
                boss_identity, store_id, order_no, table_number, customer_count, employee_no, start_time
            ) values (
                '$identity', '$store_id', '$order_no', '$desk', $persons, '$emp', now()
            )";
    //echo $sql;
    mysqli_query($con, $sql);

?>

<html>
<head>
    <style>
        table {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>    
</head>
<body>
    <h2>完成開桌</h2>
    <table>
        <tr><td width="330">訂單：<?php echo "$order_no"; ?></td></tr>
        <tr><td>桌號：<?php echo "$desk"; ?></td></tr>
        <tr><td>人數：<?php echo "$persons"; ?></td></tr>
        <tr><td>
            請掃描以下Qrcode，進入點餐畫面 <br>
            祝您用餐愉快～
            <center>
                <?php echo "<img src='newqrcode.php?data=$curQrcodeUrl' />"; ?>
            </center>
        </td></tr>

        <tr><td>
<?php
    //$date = new DateTime();
    $date = new DateTime('now +8 hours');
    $result = $date->format('Y-m-d H:i:s');
        echo "<hr>服務人員：$emp <br>印單時間：$result";
?>            
        </td></tr>

    </table>
    <br>
    <br>
    <br>
    <br>
    以下測試用<br>
<?php
    $decoded = urldecode($curQrcodeUrl);
    echo "模擬點餐  <a href=\"$decoded\">點餐功能</a><br>";
    echo "回上一頁  <a href='selectdesk.php'>選擇桌號</a> <br><br>";
?>
</body>
</html>