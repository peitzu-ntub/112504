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
                <?php echo "<br>訂單:$order_no<p>"; ?> 
                <!-- style="color: white; font-weight:bolder; font:25px;" -->
            </div>
        </div>

        <div class="content">
            <div class="left">
                <b><a href="../page/pickfood.html">主餐</a></b>
                <b><a href="../page/pickfood2.html">點心</a></b>
                <b><a href="../page/pickfood3.html">飲料</a></b>
                <b><a href="#">套餐</a></b>
            </div>

            <div class="menu">

                <!-- 菜單 1 -->
                <div class="menu-item">
                    <!-- 图片容器 -->
                    <div class="menu-item-img">
                        <img src="../images/food1.jpg" alt="菜單 1">
                    </div>
                    <!-- 右侧容器 -->
                    <div class="menu-item-center">
                        <h3>港式炒飯</h3>
                        <p>$ 60</p>
                    </div>
                    <div class="menu-item-right">
                        <button id="showPopup">點選</button>

                        <!-- 彈跳式視窗 -->
                        <div class="popup-container" id="popup">

                            <span class="close-button" id="closePopup">×</span>
                            <img src="../images/food1.jpg" alt="菜單1" class="product-image">

                            <div class="product-details">
                                <h2>港式炒飯</h2>
                                <p>價格：$60</p>
                                <p>介紹：豐富配料滿足味蕾，香味撲鼻，口感滑順，讓您品味無窮享受！</p>
                            </div>

                            <div class="cart-item">
                                <button class="button" onclick="decrementItem(1)">-</button>
                                <span class="item-quantity" id="quantity1">1</span>
                                <button class="button" onclick="incrementItem(1)">+</button>
                                <button class="button" onclick="addToCart(1)">加入購物車</button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 菜單 2 -->
                <div class="menu-item">
                    <!-- 图片容器 -->
                    <div class="menu-item-img">
                        <img src="../images/m01.upload.jpg" alt="菜單 2">
                    </div>
                    <!-- 右侧容器 -->
                    <div class="menu-item-center">
                        <h3>皮蛋瘦肉粥</h3>
                        <p>$ 60</p>
                    </div>
                    <div class="menu-item-right">
                        <button>點選</button>
                    </div>
                </div>

                <!-- 菜單 3 -->
                <div class="menu-item">
                    <!-- 图片容器 -->
                    <div class="menu-item-img">
                        <img src="../images/m03.upload.jpg" alt="菜單 3">
                    </div>
                    <!-- 右侧容器 -->
                    <div class="menu-item-center">
                        <h3>XO醬撈麵</h3>
                        <p>$ 60</p>
                    </div>
                    <div class="menu-item-right">
                        <button>點選</button>
                    </div>
                </div>

                <!-- 菜單 4 -->
                <div class="menu-item">
                    <!-- 图片容器 -->
                    <div class="menu-item-img">
                        <img src="../images/m03.upload.jpg" alt="菜單 3">
                    </div>
                    <!-- 右侧容器 -->
                    <div class="menu-item-center">
                        <h3>港式臘味煲仔飯</h3>
                        <p>$ 60</p>
                    </div>
                    <div class="menu-item-right">
                        <button>點選</button>
                    </div>
                </div>

                <!-- 菜單 5 -->
                <div class="menu-item">
                    <!-- 图片容器 -->
                    <div class="menu-item-img">
                        <img src="../images/m03.upload.jpg" alt="菜單 3">
                    </div>
                    <!-- 右侧容器 -->
                    <div class="menu-item-center">
                        <h3>公仔湯麵</h3>
                        <p>$ 60</p>
                    </div>
                    <div class="menu-item-right">
                        <button>點選</button>
                    </div>
                </div>

                <!-- 菜單 6 -->
                <div class="menu-item">
                    <!-- 图片容器 -->
                    <div class="menu-item-img">
                        <img src="../images/m03.upload.jpg" alt="菜單 3">
                    </div>
                    <!-- 右侧容器 -->
                    <div class="menu-item-center">
                        <h3>港式豬扒飯</h3>
                        <p>$ 60</p>
                    </div>
                    <div class="menu-item-right">
                        <button>點選</button>
                    </div>
                </div>
                <!-- 菜單 7 -->
                <div class="menu-item">
                    <!-- 图片容器 -->
                    <div class="menu-item-img">
                        <img src="../images/m03.upload.jpg" alt="菜單 3">
                    </div>
                    <!-- 右侧容器 -->
                    <div class="menu-item-center">
                        <h3>蜜汁叉燒撈麵</h3>
                        <p>$ 60</p>
                    </div>
                    <div class="menu-item-right">
                        <button>點選</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="footer">
            <div class="cartbutton" type="return" name="按鈕名稱" onclick="location.href='cart.php'">
                <span style="font-size: 20px; font-weight:bolder;">購物車</span>
            </div>
            <div class="allbutton" type="return" name="按鈕名稱" onclick="location.href='orderQuery.php'">
                <span style="font-size: 20px; font-weight:bolder;">我的訂單</span>
            </div>
        </div>

        <!-- </form> -->
    </div>

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
</script>
</body>

</html>