<html>

<?php
    /*
    url的長相跟範例：http://xxx.xxx/final/page/pickFood.php?identity=A123456789&store_id=S01&order_no=20231001xxxx&food_type=A
    可能從開桌的網頁跳過來
    可能從自己pickFood.php選擇餐點類型後跳到自己
    可能從購物車cart.php回來這裡繼續點餐
    */

    include "../bin/conn.php";

    //老闆身份證號
    $identity = $_GET["identity"];
    //店代號
    $store_id = $_GET['store_id'];
    //訂單單號，用系統時間決定訂單單號，格式：yyyyMMddHHmmss
    $order_no = $_GET["order_no"];
    //餐點類型，用來呈現全部或者指定的餐點內容
    $food_type=$_GET["food_type"];

    $result = mysqli_query($con, $sql);
    $row_result = mysqli_fetch_assoc($result);
    $_SESSION["boss_identity"] = $row_result['boss_identity'];
    $_SESSION["store_id"] = $row_result['store_id'];
    $_SESSION["order_no"] = $row_result['order_no'];
    //--------------------------------------------------------

    $pick_meal_id = $_GET['pick_meal_id'];
    $pick_qty = $_GET['pick_qty'];
    $cart = $_GET['cart'];

    //如果是從自己pickFood.php過來，則
    //檢查「選好餐點」的資訊，包含餐點代號與數量。
    //如果有，就更新購物車的Table
    if (isset($pick_meal_id) && isset($pick_qty)) {
        //先刪除購物車中、這張訂單、已經存在的這個商品
        $sql = "delete from store_cart 
                where boss_identity='$identity'
                and store_id = '$store_id'
                and order_no = '$order_no'
                and meal_id = '$pick_meal_id'
        ";
        //echo $sql;
        if (!mysqli_query($con, $sql)) {
            echo "delete error, ", mysqli_error($con);
        }

        //再新增這次選擇的數量到購物車裡
        $sql = "insert into store_cart (
               boss_identity, store_id, order_no, meal_id, meal_qty
               ) values (
               '$identity', '$store_id', '$order_no', '$pick_meal_id', $pick_qty
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

    header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");        
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>點餐</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="../js/bootstrap.min.css" rel="stylesheet">
    <link href="../js/pick.css" rel="stylesheet">

</head>

<body>
    <div class="container-wrapper">
        <!-- <form action="staff_in.php" method="POST" enctype="multipart/form-data"> -->

        <div class="header">
            <div align="center">
                <font size="20"><b>港式飲茶店</b></font><br><br>  
                <!-- style="color: white; font-weight:bolder; font:25px;" -->
            </div>
        </div>

        <div class="content">
            <div class="left">
<?php
            $d = "<b><a href=\"pickfood.php?identity=$identity&store_id=$store_id&order_no=$order_no\">全部</a></b> 
            ";
            echo $d;

    //查詢餐點類協，並逐一顯示出來    
    $sql = "select * from food_type where boss_identity = '$identity' and store_id = '$store_id'";
    $types = mysqli_query($con, $sql);
    //把整併後的資料重新寫入Store_order_item    
    while ($type = mysqli_fetch_array($types, MYSQLI_ASSOC)) {
        $type_id = $type['type_id'];
        $type_name = $type['type_name'];
        $d = "<b><a href=\"pickfood.php?identity=$identity&store_id=$store_id&food_type=$type_id&order_no=$order_no\">$type_name</a></b> 
        ";
        echo $d;
    }

?>
            </div>

            <div class="menu">
<?php
    //查詢餐點內容，並逐一顯示出來
    $sql = "
    select F.*, c.meal_qty
    from store_food F
    left join store_cart C 
      on c.boss_identity = F.boss_identity 
      and c.store_id = F.store_id 
      and F.meal_id = C.meal_id
      and C.meal_id = '$order_no'
    where F.boss_identity = '$identity' and F.store_id = '$store_id'";
    if (isset($food_type)) {
        $sql = $sql . " and F.type_id = '$food_type'";
    }
    $foods = mysqli_query($con, $sql);

    $count = 1;

    while ($food = mysqli_fetch_array($foods, MYSQLI_ASSOC)) {
        $meal_id = $food['meal_id'];
        $meal_name = $food['meal_name'];
        $meal_price = $food['meal_price'];
        //$meal_pic = $food['meal_pic'];
        $meal_note = $food['meal_note'];
        $meal_up = $food['meal_up'];
        $meal_qty = $food['meal_qty'];

        if (!isset($meal_qty)) {
            $meal_qty = 1;
        }

        $p = "";
        if ($count == 1) {
            $count == 2;
            $p = "
            <div class=\"popup-container\" id=\"popup\">
                <!-- @@關閉彈跳視窗 -->
                <span class=\"close-button\" id=\"closePopup\" onclick=\"closePickingDialog();\">X</span>
                <img src=\"../images/1-2.png\" id=\"pop_food_img\" alt=\"菜單\" class=\"product-image\">

                <div class=\"product-details\">
                    <h2 id=\"pop_food_name\"></h2>
                    <b><p id=\"pop_price\"></p></b>
                    <p id=\"pop_desc\"></p>
                </div>

                <div class=\"cart-item\">
                    <!-- 必要的隱藏欄位 -->
                    <input type=\"hidden\" id=\"pick_identity\" name=\"pick_identity\" value=\"$identity\">
                    <input type=\"hidden\" id=\"pick_store_id\" name=\"pick_store_id\" value=\"$store_id\">
                    <input type=\"hidden\" id=\"pick_order_no\" name=\"pick_order_no\" value=\"$order_no\">
                    <input type=\"hidden\" id=\"pick_meal_id\" name=\"pick_meal_id\" value=\"\">

                    <button class=\"button\" onclick=\"decrementItem()\">-</button>
                    <span class=\"item-quantity\" id=\"quantity1\">1</span>
                    <button class=\"button\" onclick=\"incrementItem()\">+</button>
                    <button class=\"button\" onclick=\"addToCart()\">加入購物車</button>
                </div>
            </div>
            ";
        }

        $d = "
        <!--菜單-->
        <div class='menu-item'>
            <!--圖片容器-->
            <div class='menu-item-img'>
                <img src='../images/$meal_name.upload.jpg' alt='菜單'>
            </div>
            <!--右側容器-->
            <div class='menu-item-center'>
                <h3> $meal_name </h3>
                <p>$ $meal_price </p>
            </div>
            <div class='menu-item-right'>
                <button onclick=\"openPickingDialog('$meal_id', '$meal_name', '../images/$meal_name.upload.jpg', $meal_qty, $meal_price, '$meal_note');\">點選</button>
                $p
            </div>
        </div>
";
        echo $d;
    }

?>
            </div>
        </div>
    
        <div class="footer">
            <div class="centered-container">

<?php
    //顯示購物車畫面，顯示指定的訂單的購物車現況
    $cart_url = "location.href='cart.php?identity=$identity&store_id=$store_id&order_no=$order_no'";
    $cart_div = "
                <button type='return' style='font-size: 18px; font-weight:bolder;' class='cartbutton' onclick=\"$cart_url\">
                    購物車
                </button>&emsp;";
    echo $cart_div;

    $qr_url = "location.href='orderQuery.php?identity=$identity&store_id=$store_id&order_no=$order_no'";
    $qr_div = "
                <button type='return' style='font-size: 18px; font-weight:bolder;' class='allbutton' onclick=\"$qr_url\">
                    我的訂單
                </button>&emsp;";
    echo $qr_div;
    $fc_url = "location.href='cusfeedback.php?identity=$identity&store_id=$store_id&order_no=$order_no'";
    $fc_div = "
                <button type='return' style='font-size: 18px; font-weight:bolder;' class='feedbackbutton' onclick=\"$fc_url\">
                    去評價
                </button>";
    echo $fc_div;

?>  
            </div>      
        </div>

        <!-- </form> -->
    </div>
</body>

<script>
    //@@開啟視窗的function
    //fName : 餐點名稱
    //imgSrc : 餐點圖片
    //fPrice : 餐點價格
    //fDesc : 餐點介紹
    //---
    function openPickingDialog(fid, fName, imgSrc, fqty, fPrice, fDesc) {
        //將餐點代號記錄在隱藏欄位
        document.getElementById('pick_meal_id').value = fid;

        //將餐點數量改回預設1
        const quantityElement = document.getElementById('quantity1');
        quantityElement.textContent = fqty;

        //DOM元素
        const popup = document.getElementById('popup');
        const foodName = document.getElementById('pop_food_name');
        var img = document.getElementById('pop_food_img');
        var prc = document.getElementById('pop_price');
        var desc =  document.getElementById('pop_desc');

        //餐點名稱
        foodName.innerHTML = fName;
        //餐點圖片
        var imgSrc = img.setAttribute("src", imgSrc);
        //價格
        prc.innerHTML = "$ " + fPrice;
        //介紹
        desc.innerHTML = fDesc;
        
        //顯示視窗
        popup.style.display = 'block';
    }

    //@@關閉視窗的function
    function closePickingDialog() {
        //DOM元素
        const popup = document.getElementById('popup');
        popup.style.display = 'none';
    }

    //@@减少商品數量
    function decrementItem() {
        const quantityElement = document.getElementById('quantity1');
        let quantity = parseInt(quantityElement.textContent);
        if (quantity > 1) {
            quantity--;
            quantityElement.textContent = quantity;
        }
    }

    //@@增加商品數量
    function incrementItem() {
        const quantityElement = document.getElementById('quantity1');
        let quantity = parseInt(quantityElement.textContent);
        quantity++;
        quantityElement.textContent = quantity;
    }

    //@@加入購物車
    function addToCart() {
        //必要欄位
        var pick_identity = document.getElementById('pick_identity').value; 
        var pick_store_id = document.getElementById('pick_store_id').value; 
        var pick_order_no = document.getElementById('pick_order_no').value; 
        //餐點代號
        var pick_meal_id = document.getElementById('pick_meal_id').value;
        //餐點數量
        var quantityElement = document.getElementById('quantity1');
        var qty = parseInt(quantityElement.textContent);

        //重新導頁,並將餐點數量加入購物車
        var url = 'pickFood.php?identity='+pick_identity + '&store_id='+pick_store_id + '&order_no='+ pick_order_no + '&pick_meal_id=' + pick_meal_id + '&pick_qty=' + qty;
        //alert(url);
        window.location.href = url;
    }

</script>

<script>
    // 使用JavaScript来实现导航链接选中时的样式更改
    const navLinks = document.querySelectorAll('.left a');

    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            // 移除所有导航链接的 "active" 类
            navLinks.forEach(navLink => navLink.classList.remove('active'));
            // 将当前点击的链接添加 "active" 类
            link.classList.add('active');
        });
    });
</script>

<!--
    <script>
        // 获取弹窗的元素
        const popup = document.getElementById('popup');

        // 获取显示弹窗的按钮和关闭弹窗的按钮
        const showPopupButton = document.getElementById('showPopup');
        const closePopupButton = document.getElementById('closePopup');

        // 当点击显示弹窗的按钮时，显示弹窗
        showPopupButton.addEventListener('click', () => {
            popup.style.display = 'block';
        });

        // 当点击关闭按钮时，关闭弹窗
        closePopupButton.addEventListener('click', () => {
            popup.style.display = 'none';
        });
    </script>

    
<script>
    const cartItems = document.getElementById('cartItems');

    // 减少商品数量的函数
    function decrementItem(itemId) {
        const quantityElement = document.getElementById(`quantity${itemId}`);
        let quantity = parseInt(quantityElement.textContent);
        if (quantity > 1) {
            quantity--;
            quantityElement.textContent = quantity;
        }
    }

    // 增加商品数量的函数
    function incrementItem(itemId) {
        const quantityElement = document.getElementById(`quantity${itemId}`);
        let quantity = parseInt(quantityElement.textContent);
        quantity++;
        quantityElement.textContent = quantity;
    }

    // 加入购物车的函数
    function addToCart(itemId) {
        const quantityElement = document.getElementById(`quantity${itemId}`);
        const itemName = `商品 ${itemId}`;
        const itemQuantity = parseInt(quantityElement.textContent);
        const cartItem = document.createElement('li');
        cartItem.textContent = `${itemName} x ${itemQuantity}`;
        cartItems.appendChild(cartItem);
    }
</script> -->


</html>