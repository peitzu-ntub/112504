<?php

    include "../bin/conn.php";

    $identity = $_GET["identity"];
    $store_id = $_GET["store_id"];
    $order_no = $_GET["order_no"];

    //查詢訂單內容
    $sql = "select f.meal_id, f.meal_name, c.price as meal_price
          from store_order_item c
          left join store_food f 
            on f.boss_identity = c.boss_identity 
            and f.store_id = c.store_id
            and f.meal_id = c.meal_id
          where c.boss_identity='$identity' 
          and c.store_id='$store_id' 
          and c.order_no = '$order_no'";
    try {
        $ords = mysqli_query($con, $sql);
        if (!$ords) {
            throw new Exception(mysqli_error($con));
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    //2023.11.17 顯示店舖名稱、決定顯示的方式
    $sql = "
        select * from store_info
        where boss_identity = '$identity' and store_id = '$store_id'";
    $result = mysqli_query($con, $sql);
    $cat = mysqli_fetch_array($result, MYSQLI_ASSOC);   
    //店舖名稱
    $store_name = $cat['store_name'];


    header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");        
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>評價</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="../js/bootstrap.min.css" rel="stylesheet">
    <link href="../js/cusfeedback.css" rel="stylesheet">
    <script src="../js/jquery-3.6.4.min.js"></script>

</head>

<body>
    <div class="main">
        <div class="header">
<?php
    $u = "location.href='pickfood.php?identity=$identity&store_id=$store_id&order_no=$order_no'";
    $s = "<div class=\"logout\" type=\"button\" name=\"按鈕名稱\" onclick=\"$u\">";
    echo $s;
?>            
            <!--
            <div class="logout" type="button" name="按鈕名稱" onclick="location.href='pickfood.html'">
            -->
                <div align="left">
                    <img src="../images/back.png" alt="返回icon" />
                    <span style="font-size: 11px;">返回</span>
                </div>
            </div>
            <div align="center">
                <font size="20"><b><?php echo $store_name; ?></b></font><br><br>
                <!-- style="color: white; font-weight:bolder; font:25px;" -->
            </div>
        </div>

        <div class="content">            
            <form id="myForm" action="end.php" method="POST">
 
            <!-- <div class="scrollable-content"> -->
                <div class="menu">
<?php
    while ($o = mysqli_fetch_array($ords, MYSQLI_ASSOC)) {
        $meal_id = $o['meal_id'];
        $meal_name = $o['meal_name'];
        $meal_price = $o['meal_price'];

        $h = "
        <div class=\"menu-item\">
        <!-- 圖片容器 -->
        <div class=\"menu-item-img\">
            <img src=\"../images/$meal_name.upload.jpg\" alt=\"$meal_name\">
        </div>
        <!-- 右側容器 -->
        <div class=\"menu-item-center\">
            <h3>$meal_name</h3><br>
            <p>$ $meal_price</p>
        </div>
        <div class=\"menu-item-center\">
            <label for=\"rating\">評價</label>
            <select id=\"p_$meal_id\" name=\"p_$meal_id\" required>
                <option value=\"\"></option>
                <option value=\"5\">5星</option>
                <option value=\"4\">4星</option>
                <option value=\"3\">3星</option>
                <option value=\"2\">2星</option>
                <option value=\"1\">1星</option>
            </select><br>
        </div>
        <div>
            <label for=\"review\">評論</label><br>
            <textarea id=\"r_$meal_id\" name=\"r_$meal_id\" rows=\"2\" cols=\"18\" style=\"resize: none;\" maxlength=\"25\"></textarea>
        </div>
    </div>
        ";
        echo $h;

    }

?>                    
                </div>
            </div>

            <div class="footer">
                <div class="centered-container">
                    <button type='submit' style='font-size: 16px; font-weight:bolder; width: 140px;' class='button' onclick="goEnd('fb');">
                        送出評價
                    </button>&emsp;
                    <button type='submit' style='font-size: 16px; font-weight:bolder; width: 140px;' class='button' onclick="goEnd('bye');">
                        不用了，謝謝
                    </button>
                </div>
            </div>
            <input type="hidden" id="action_type" name="action_type" value="">
            <input type="hidden" id="identity" name="identity" value="">
            <input type="hidden" id="store_id" name="store_id" value="">
            <input type="hidden" id="order_no" name="order_no" value="">
        </form>
        </div>
</body>

<script>
    function goEnd(actionType) {
        var urlParams = new URLSearchParams(window.location.search);
        var identity = urlParams.get('identity');
        var store_id = urlParams.get('store_id');
        var order_no = urlParams.get('order_no');
        document.getElementById("identity").value = identity;
        document.getElementById("store_id").value = store_id;
        document.getElementById("order_no").value = order_no;

        document.getElementById("action_type").value = actionType;
    }
</script>

</html>