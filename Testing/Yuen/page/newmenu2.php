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

    <!--取代alert的工具-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- 若需相容 IE11，要加載 Promise Polyfill-->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>    
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
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goAllmenu();">全部餐點</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goMenu1();">餐點類型</a></li>
                <li><a>新增餐點</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goNM3();">菜單呈現設定</a></li>
            </ul>
        </nav>

        <div class="insidebox">
            <form id="main" action="../bin/newfood.php" method="POST" enctype="multipart/form-data">
                <div style="width:320px;">
                    <img src="../images/add.png" />
                    <font color="#bf6900" size="5" >新增餐點</font>
                    <font color="#ff0000" size="2">您需先新增類型才能新增菜色!</font>
                </div><br>

                <div class="ininsidebox">
                    <div class="input-box">
                    <input type="hidden" id="boss_identity" name="boss_identity" value="">
                    <input type="hidden" id="store_id" name="store_id" value="">
                    <input type="hidden" id="data_type" name="data_type" value="menu2">

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
                <!--不要直接submit，透過 jQuery + Ajax 把餐點資料跟圖檔一起 post 到後端-->
                <button class="submitbutton" onclick="saveData();" value="儲存">儲存</button>
            </form>
        </div>
    </div>
</body>

<script>
    //儲存餐點資料、圖檔
    function saveData() {
        //把老闆身份證號、店代號，塞進隱藏欄位，一起送到後端
        var urlParams = new URLSearchParams(window.location.search);
        var bossIdentity = urlParams.get('boss_identity');
        var storeId = urlParams.get('store_id');
        document.getElementById("boss_identity").value = bossIdentity;
        document.getElementById("store_id").value = storeId;

        //透過 jQuery + ajax 進行 post 的動作
        $("form#main").submit(function(e) {
            e.preventDefault();    
            var formData = new FormData(this);
            $.ajax({
                url: "../bin/newfood.php",
                type: 'POST',
                data: formData,
                success: function (response) {
                    var json = $.parseJSON(response);
                    if (json.result == 'OK') {
                        //alert(json.message);
                        Swal.fire(
                            '餐點', //標題
                            '您所輸入的新餐點資料已儲存', //訊息容
                            'success' // 圖示 (success/info/warning/error/question)
                        );
                        //成功後，清除畫面上所輸入的資料內容
                        document.getElementById("main").reset();
                    } else {
                        Swal.fire(
                            '餐點', //標題
                            json.message, //訊息容
                            'error' // 圖示 (success/info/warning/error/question)
                        );
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });        
        });
    }

    function goAllmenu() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="allmenu.php?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
    function goBack() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="boss_management.html?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
    function goMenu1() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="newmenu1.php?boss_identity=" + boss_identity + "&store_id=" + store_id+ "&boss_name=" + boss_name;
    }
    function goMenu2() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="newmenu2.php?boss_identity=" + boss_identity + "&store_id=" + store_id+ "&boss_name=" + boss_name;
    }
    function goSearch() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="search_type.php?boss_identity=" + boss_identity + "&store_id=" + store_id+ "&boss_name=" + boss_name;
    }
    function goNM3() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="nm3.php?boss_identity=" + boss_identity + "&store_id=" + store_id+ "&boss_name=" + boss_name;
    }
</script>

</html>