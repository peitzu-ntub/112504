<html>

<?php
    include "../bin/conn.php";

    //老闆身份證號
    $identity = $_GET["identity"];
    //店代號
    $store_id = $_GET['store_id'];
    //訂單單號，用系統時間決定訂單單號，格式：yyyyMMddHHmmss
    $order_no = $_GET["order_no"];
    //--------------------------------------------------------

    $confirm_meal_id = $_GET['meal_id'];
    $confirm_qty = $_GET['qty'];
    $cart = $_GET['cart'];

    //如果是從 confirmFood.php 過來，則
    //檢查「選好餐點」的資訊，包含餐點代號與數量。
    //如果有，就更新購物車的Table
    if ($confirm_meal_id && $confirm_qty) {
        //先刪除購物車中、這張訂單、已經存在的這個商品
        $sql = "delete from store_cart 
                where boss_identity='$identity'
                and store_id = '$store_id'
                and order_no = '$order_no'
                and meal_id = '$confirm_meal_id'
        ";
        //echo $sql;
        if (!mysqli_query($con, $sql)) {
            echo "delete error, ", mysqli_error($con);
        }

        //再新增這次選擇的數量到購物車裡
        $sql = "insert into store_cart (
               boss_identity, store_id, order_no, meal_id, meal_qty
               ) values (
               '$identity', '$store_id', '$order_no', '$confirm_meal_id', $confirm_qty
               )
        ";
        //echo $sql;
        if (!mysqli_query($con, $sql)) {
            echo "insert error: ", mysqli_error($con);
        }
    }

    //如果是從 cart.php 過來，則
    //檢查「確認訂單」的資訊
    //如果有，更新訂單、把購物車的資料合併到訂單明細
    if (isset($cart) && $cart == '1') {
        //購物車與目前Store_order_item的資料先全部撈出來
        $sql = "
            select a.meal_id, sum(a.meal_qty) as meal_qty, sf.meal_price
            from (
                select c.meal_id, c.meal_qty
                from store_cart c
                where c.boss_identity = '$identity'
                and c.store_id = '$store_id' 
                and c.order_no = '$order_no'
                union all
                select oi.meal_id, oi.count as meal_qty
                from store_order_item oi
                where oi.boss_identity = '$identity'
                and oi.store_id = '$store_id' 
                and oi.order_no = '$order_no'
            ) a
            left join store_food sf
                on sf.boss_identity = '$identity'
                and sf.store_id = '$store_id'
                and sf.meal_id = a.meal_id
            group by a.meal_id, sf.meal_price;
        ";
        $merge_oi = mysqli_query($con, $sql);
        //刪除原本的訂單
        $sql = "
            delete from store_order_item 
            where boss_identity = '$identity'
            and store_id = '$store_id' 
            and order_no = '$order_no'
            ";
        mysqli_query($con, $sql);
        //把整併後的資料重新寫入Store_order_item
        while ($oi = mysqli_fetch_array($merge_oi, MYSQLI_ASSOC)) {
            $meal_id = $oi['meal_id'];
            $qty = $oi['meal_qty'];
            $price = $oi['meal_price'];
            $subtotal = $qty * $price;
            $sql = 
                "insert into store_order_item (
                    boss_identity, store_id, order_no, meal_id, count, price, subtotal
                ) values (
                    '$identity', '$store_id', '$order_no', '$meal_id', $qty, $price, $subtotal
                )
                ";
            mysqli_query($con, $sql);        
        }
        //2023.05.30 根據目前的訂單明細，更新訂單主檔的欄位，主要是「訂單金額」欄位
        $sql = "
            update store_order m
            set m.total_price = (
                select sum(d.subtotal) from store_order_item d
                where d.boss_identity = '$identity'
                and d.store_id = '$store_id'
                and d.order_no = '$order_no'
                )
            where m.boss_identity = '$identity'
            and m.store_id = '$store_id'
            and m.order_no = '$order_no'
        ";
        mysqli_query($con, $sql);                    

        //清空「指定的訂單單號」的購物車
        $sql = "
            delete from store_cart
            where boss_identity = '$identity'
            and store_id = '$store_id' 
            and order_no = '$order_no'
            ";
        mysqli_query($con, $sql);
    }

?>

<head>
    <title>點餐</title>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../js/bootstrap.min.css" >
	<link rel="stylesheet" href="../js/bootstrap.min.4.6.2.css">
	<link rel="stylesheet" href="../js/pickFood.css" >

    <script src="../js/jquery-3.6.4.min.js"></script>
</head>

<body>
    <!--最外層-->
    <div >

    <!--功能標題-->
    <!-- <div class="row"> -->
        <div class="col-md-12">
            <table>
                <tr>
                    <td>
                        <img src="../images/菜單.png" />            
                    </td>        
                    <td>
                        <font size='5'>
                            <?php echo "選擇餐點"; ?>            
                        </font>
                        <font size='3'>
                            <?php echo "<br>訂單:$order_no<p>"; ?>            
                        </font>
                    </td>
                </tr>
            </table>
        </div>
    <!-- </div>     -->

<?php 
    //查詢所有的餐點，準備產生畫面
    try {
        $sql = "select * from store_food where boss_identity = '$identity' and store_id = '$store_id'";
        $food_data = mysqli_query($con, $sql);
        if (!$food_data) {
            throw new Exception(mysqli_error($con));
        }
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }


    //-----
    //以下是以php產生餐點的畫面
    //餐點資料，開始組合出頁面的html內容
    echo "
		<div class='row'>
";
    //餐點資料，逐一顯示。目前沒有「餐點類別」的過濾
    while ($food = mysqli_fetch_array($food_data, MYSQLI_ASSOC)) {
        $meal_id = $food['meal_id'];
        $meal_name = $food['meal_name'];
        $meal_price = $food['meal_price'];

        //第二種呈現方式：橫條長方形、小圖
        echo "
        <div class='col-xs-6 col-sm-6 col-md-6 col-lg-6'>
            <div class='card'>
                <table>
                    <tr>
                        <td  width='30%'>
                            <a href='confirmFood.php?identity=$identity&store_id=$store_id&meal_id=$meal_id&order_no=$order_no'>
                                <img class='card-img-top' src='../images/$meal_id.upload.jpg' alt='$meal_name' />
                            </a>
                        </td>
                        <td align='right'>
                            <font size=5>$meal_name</font><br>
                            <font size=5>$$meal_price</font>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        ";

        //第一種呈現方式：正方形、大圖
        // echo "
        // <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
        //     <div class='card'>
        //         <a href='confirmFood.php?identity=$identity&store_id=$store_id&meal_id=$meal_id&order_no=$order_no'>
        //             <img class='card-img-top' src='../images/$meal_id.upload.jpg' alt='$meal_name' />
        //         </a>
        //         <table>
        //             <tr>
        //                 <td width='80%'>
        //                     <font size=5>$meal_name</font>
        //                 </td>
        //                 <td align='right'>
        //                     <font size=5>$$meal_price</font>
        //                 </td>
        //             </tr>
        //         </table>
        //     </div>
        // </div>
        // ";
    }
    //餐點資料，結尾
    echo "
        </div>
    ";
?>
            <div>
                <div class='col-md-12'>
                    <div style='height:10;'></div>
                </div>
                <div class='col-md-12'>
<?php                 
    echo "
                <table width='100%'>
                <tr>
                    <td width='30%' align='center'>
                    <a href='cart.php?identity=$identity&store_id=$store_id&order_no=$order_no'>
                        <button type='button' class='registbutton'>
                            購物車
                        </button>
                    </a>
                    </td>

                    <td width='40%' align='center'>
                    <a href='orderQuery.php?identity=$identity&store_id=$store_id&order_no=$order_no'>
                        <button type='button' class='registbutton'>
                            我的訂單
                        </button>
                    </a>
                    </td>
    ";
?>                
                    <td align='center'>
                    <a href='orderClose.html'>
                        <button type="button" class="registbutton">
                        給評價
                        </button></a>
                    </td>
                    </tr>
                    </table>
                </div>
                <div class='col-md-12'>
                    <div style='height:20;'></div>
                </div>
            </div>

    <!--最外層，結尾-->
    </div>

</body>

</html>