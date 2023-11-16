<?php
    session_start();
    include "../bin/conn.php";

    $identity = $_GET["boss_identity"];
    $store = $_GET["store_id"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>新增餐點</title>

    <link href="../js/edit.css" rel="stylesheet">
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
        <nav>
            <ul>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goAll()">全部餐點</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goType()">餐點類型</a></li>
                <li><a>新增餐點</a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/nm3.html">呈現方法</a></li>
            </ul>
        </nav>

        <div class="insidebox">
            <form action="#" method="POST" enctype="multipart/form-data">
                <div style="width:320px;">
                    <img src="../images/add.png" />
                    <font color="#bf6900" size="5" >新增餐點</font>
                    <font color="#ff0000" size="2">您需先新增類型才能新增菜色!</font>
                </div><br>

                <div class="ininsidebox">
                    <div class="input-box">
                    <input type="hidden" id="boss_identity" name="boss_identity" value="">
                    <input type="hidden" id="store_id" name="store_id" value="">

                        <div class="input-row">
                            <span class="details">餐點類型：</span>
                            <select name="type_id" id="type_id">
                                <?php
                                    $sql = "
                                        select * from food_type
                                        where boss_identity = '$identity' and store_id = '$store'";
                                    $meal_type = mysqli_query($con, $sql);
                                    while ($cat = mysqli_fetch_array($meal_type,MYSQLI_ASSOC)) {
                                        $type_id=$cat['type_id'];
                                        $type_name=$cat['type_name'];
                                        echo "  <option value='$type_id'>$type_name</option>";
                                    }
                                    ?> 
                                </select>
                        </div>
                        <div class="input-row">
                            <span class="details">餐點名稱：</span>
                            <input type="text" name="meal_name" id="meal_name" placeholder="請輸入餐點名稱" required>
                        </div>
                        <div class="input-row">
                            <span class="details">餐點介紹：</span>
                            <textarea id="meal_note" name="meal_note" rows="2" cols="20" placeholder="請輸入餐點介紹(上限50字)"
                                style="resize: none;" maxlength="50"></textarea>
                        </div>
                        <div class="input-row">
                            <span class="details">餐點價格：</span>
                            <input type="number" min="0" name="meal_price" id="meal_price" placeholder="請輸入正確價格"
                                required>
                        </div>
                        <div class="input-row">
                            <span class="details">餐點圖片：</span>
                            <input type="file" name="meal_pic" id="meal_pic">
                        </div>
                    </div>
                </div>
                <button class="submitbutton" type="submit" value="儲存">儲存</button>
            </form>
        </div>
    </div>
</body>

<script>
    //當網頁準備好的時候，做以下的動作(函式)
    $(document).ready(function () {

        //form的submit按鈕按下去的動作
        $("form").on("submit", function (e) {
            var urlParams = new URLSearchParams(window.location.search);
            var bossIdentity = urlParams.get('boss_identity');
            var storeId = urlParams.get('store_id');
            document.getElementById("boss_identity").value = bossIdentity;
            document.getElementById("store_id").value = storeId;

            //1.先把準備拋回去的資料「序列化」整理成json格式的字串
            var dataString = $(this).serialize();

            //可以把字串顯示出來看看是否正確
            //alert(dataString);               

            //2.透過ajax(非同步JavaScript)把字串送給後端的PHP網站
            $.ajax({
                //HTTP的通訊模式有：GET、POST、DELETE。這次採用POST的模式，僅傳遞該傳遞的資料，不是整個網頁送回去
                type: "POST",
                //指定要連接的PHP位址
                url: "../bin/newfood.php",
                //要傳送的資料內容
                data: dataString,
                //獲得正確回應時，要做的事情
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json.result == 'OK') {
                        alert (json.message);
                    } else {
                        alert (json.message);
                    }
                },
                //獲得不正確的回應時，要做的事情
                error: function (response) {
                    alert ('錯誤');
                    //$("#message").html(response);
                }
            });

            e.preventDefault();
        });
    });


    function goBack() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var boss_name = urlParams.get('boss_name');
        location.href="boss_management.html?boss_identity=" + boss_identity + "&boss_name=" + boss_name;
    }
    function goType() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        location.href="newmenu1.php?boss_identity=" + boss_identity + "&store_id=" + store_id;
    }
    function goAll() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        location.href="allmenu.php?boss_identity=" + boss_identity + "&store_id=" + store_id;
    }
</script>
</html>