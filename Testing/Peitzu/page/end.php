<?php
    include "../bin/conn.php";
    $identity = $_POST["identity"];
    $store_id = $_POST["store_id"];
    $order_no = $_POST["order_no"];

    if ($_POST['action_type'] == 'fb') {
        //查詢訂單內容
        $sql = "select c.meal_id
            from store_order_item c
            where c.boss_identity='$identity' 
            and c.store_id='$store_id' 
            and c.order_no = '$order_no'";
        $ords = mysqli_query($con, $sql);
        while ($o = mysqli_fetch_array($ords, MYSQLI_ASSOC)) {
            $meal_id = $o['meal_id'];

            $p = "p_$meal_id";
            $p_value = $_POST[$p];
            $r = "r_$meal_id";
            $r_value = $_POST[$r];
            if (!isset($r_value)) {
                $r_value = "";
            }
            $sql = "
                update store_order_item 
                set score = $p_value, evaluate='$r_value'
                where boss_identity='$identity'
                and store_id='$store_id'
                and order_no = '$order_no'
                and meal_id = '$meal_id'
            ";        
            mysqli_query($con, $sql);
        }
    }
//2023.12.08因為要在結帳時才關桌
//所以這兩段SQL就不要在給出評價的時候執行
/*
    //關桌
    $sql = "
        update store_table
            set is_open = 'N'
        where boss_identity = '$identity' 
        and store_id = '$store_id' 
        and table_number = (
            select table_number from store_order
            where boss_identity = '$identity' 
            and store_id = '$store_id' 
            and order_no = '$order_no'
            )";
    mysqli_query($con, $sql);

// echo $sql;
// echo "<br>";

    //更新訂單的關桌時間
    $sql = "
        update store_order 
            set end_time = now()
        where boss_identity = '$identity' 
        and store_id = '$store_id' 
        and order_no = '$order_no'";
    mysqli_query($con, $sql);    
// echo $sql;
*/

?>                


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>點餐</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="../js/bootstrap.min.css" rel="stylesheet">
    <link href="../js/end.css" rel="stylesheet">
    <script src="../js/jquery-3.6.4.min.js"></script>

</head>

<body>
    <div class="container">
        <h1>訂單結束</h1><br>

        <div align="center">
            <p><div id='message'>
                <!-- 感謝您的評價！您的評價已成功提交。 -->
<?php
    if ($_POST['action_type'] == 'fb') {
        echo "感謝您的評價！您的評價已成功提交。";
    }
    else {
        echo "感謝您今日的用餐！歡迎再度光臨。";
    }
?>                
            </div></p>
        </div>
        <br>
        <!-- <div class="button" type="return" name="按鈕名稱" onclick="location.href='home.html'">
            <span style="font-size: 20px; font-weight:bolder;">返回首頁</span>
        </div> -->
    </div>
</body>

<script>
    //當網頁準備好的時候，做以下的動作(函式)
    $(document).ready(function () {
        /*
        var urlParams = new URLSearchParams(window.location.search);
        var action_type = urlParams.get('action_type');
        if (action_type == 'fb') {
            document.getElementById("message").innerHTML = '感謝您的評價！您的評價已成功提交。';
        }
        else {
            document.getElementById("message").innerHTML = '感謝您今日的用餐！歡迎再度光臨。';
        }
        */
    });
</script>

</html>