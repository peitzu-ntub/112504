<?php
    include "../bin/conn.php";
    $identity = $_GET["boss_identity"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>add store</title>

    <link href="../js/regist_store.css" rel="stylesheet">
    <script src="../js/jquery-3.6.4.min.js"></script>

</head>

<body>
    <div class="logout" type="button" name="按鈕名稱" onclick="back();">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 15px;">返回</span>
        </div>
    </div>
    <div class="who">
        <div align="left">
            <img src="../images/worker64.png" alt="老闆圖片" />
            <span style="font-size: 28px;"><div id="boss_name">老闆名字</div></span>
        </div>
    </div>
    <div class="title">
        <div align="left">
            <img src="../images/restaurant128.png" alt="店家圖片" />
            <span style="font-size: 28px;">新增店家資訊</span>
        </div>
    </div>

    <form action="#">
        <div class="container-wrapper">
            <div class="container1">
                <div class="user-details">
                        <div class="input-box">
                            <span class="details" style="font-size: 20px;">店家代號：</span>
                            <input type="text" name="store_id" id="store_id" placeholder="請輸入英文或數字最多5碼" required maxlength="5" pattern="[A-Za-z0-9]+">
                            <input type="hidden" name="boss_identity" id="boss_identity" value="boss">
                            <!-- 之後跳進來的時候要把身分證資料放進去boss_identity這裡，現在是假的老闆身分證 -->
                        </div>
                        <div class="input-box">
                            <span class="details" style="font-size: 20px;">店家名稱：</span>
                            <input type="text" name="store_name" id="store_name" placeholder="請輸入店家名稱" required>
                        </div>
                        <div class="input-box">
                            <span class="details" style="font-size: 20px;">店家電話：</span>
                            <input type="text" name="store_tel" id="store_tel" placeholder="請輸入店家電話" required oninput="javascript: if (this.value.length > 10) this.value = this.value.slice(0, 10);">

                        </div>
                        <div class="input-box">
                            <span class="details" style="font-size: 20px;">店家地址：</span>                       
                            <input type="text" name="store_address" id="store_address" placeholder="請輸入正確地址" required>
                        </div>
                        <div class="input-box">
                            <span class="details" style="font-size: 20px;">店家桌數：</span>
                            <input type="number" min=1 name="table_count" id="table_count" placeholder="請輸入桌數" required>
                        </div>
                </div>
            </div>
        </div>
        <div class="input-box">
            <input class="addstore" type="submit" value="確定" style="font-size: 15px;"></input>
            <!--<input class="addstore" type="submit" value="確定" style="font-size: 15px;"></input>-->
        </div>
    </form>
</body>


<script>
    function back() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var boss_name = urlParams.get('boss_name');
        var back = urlParams.get('back');
        var url = '?boss_identity=' + boss_identity + '&boss_name=' + boss_name
        if (back == '0') {
            url = "select_store.php" + url;
        } else {
            url = "select_store_two.php" + url;
        }
        location.href = url;
    }

    //當網頁準備好的時候，做以下的動作(函式)
    $(document).ready(function () {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_name = urlParams.get('boss_name');       
        document.getElementById("boss_name").innerHTML = boss_name;

        //form的submit按鈕按下去的動作
        $("form").on("submit", function (e) {
            var urlParams = new URLSearchParams(window.location.search);
            var boss_identity = urlParams.get('boss_identity');
            document.getElementById("boss_identity").value = boss_identity;
            var boss_name = urlParams.get('boss_name');       

            //1.先把準備拋回去的資料「序列化」整理成json格式的字串
            var dataString = $(this).serialize();

            //可以把字串顯示出來看看是否正確
            alert(dataString);               

            //2.透過ajax(非同步JavaScript)把字串送給後端的PHP網站
            $.ajax({
                //HTTP的通訊模式有：GET、POST、DELETE。這次採用POST的模式，僅傳遞該傳遞的資料，不是整個網頁送回去
                type: "POST",
                //指定要連接的PHP位址
                url: "../bin/editStore.php",
                //要傳送的資料內容
                data: dataString,
                //獲得正確回應時，要做的事情
                success: function (response) {
                    //alert(response);
                    var json = $.parseJSON(response);
                    if (json.result == "OK") {
                        var url = 'select_store_two.php?boss_identity=' + boss_identity + '&boss_name=' + boss_name;
                        location.href = url;                        
                    }
                },
                //獲得不正確的回應時，要做的事情
                error: function (response) {
                    $("#message").html(response);
                }
            });

            e.preventDefault();
        });
    });
</script>
</html>