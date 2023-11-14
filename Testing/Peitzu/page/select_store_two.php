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

    <title>選擇店家</title>

    <link href="../js/select_store_two.css" rel="stylesheet">
    <script src="../js/jquery-3.6.4.min.js"></script>

</head>

<body>
    <div class="logout" type="button" name="按鈕名稱" onclick="location.href='worker.html'">
        <div align="left">
            <img src="../images/logout.png" alt="登出icon" />
            <span style="font-size: 15px;">登出</span>
        </div>
    </div>

    <div class="title">
        <div align="left">
            <img src="../images/restaurant.png" alt="店家圖片" />
            <span style="font-size: 28px;">請選擇您的店家</span>
        </div>
    </div>

    <div class="who">
        <div align="left">
            <img src="../images/worker64.png" alt="老闆圖片" />
            <span style="font-size: 28px;"><div id="boss_name">老闆名字</div></span>
        </div>
    </div>

    <div class="container-wrapper">          
<!--         <div class="container1">
            <div class="intostore" onclick="location.href='boss_management.html'">
                <span style="font-size: 40px;">好吃店家</span>
            </div>
        </div> -->
        <nav id="menu-container" class="arrow">
            <div id="btn-nav-previous" >
                <svg xmlns="http://www.w3.org/2000/svg" width="32"
                    height="32" viewBox="0 0 24 24">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                    <path d="M0 0h24v24H0z" fill="none" /></svg>
            </div>
            <div id="btn-nav-next">
                <svg xmlns="http://www.w3.org/2000/svg" width="32"
                    height="32" viewBox="0 0 24 24">
                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                    <path d="M0 0h24v24H0z" fill="none" /></svg>
            </div>
            <div class="menu-inner-box">
                <div class="menu">
<?php
    $boss_name = $_GET["boss_name"];
    //查詢老闆的店舖資料  
    $sql = "select * from store_info where boss_identity = '$identity'";
    $stores = mysqli_query($con, $sql);

    //把整併後的資料重新寫入Store_order_item    
    while ($store = mysqli_fetch_array($stores, MYSQLI_ASSOC)) {
        $store_id = $store['store_id'];
        $store_name = $store['store_name'];
        $d = "
        <a class=\"menu-item\" href=\"../page/boss_management.html?boss_identity=$identity&boss_name=$boss_name&store_id=$store_id\">$store_name</a>";
        echo $d;
    }
?>                  <!--
                    <a class="menu-item" href="../page/boss_management.html">好吃店家</a>
                    <a class="menu-item" href="../page/boss_management.html">不好吃店家</a>
                    <a class="menu-item" href="../page/boss_management.html">薩利YEAH</a>
                    <a class="menu-item" href="../page/boss_management.html">水底撈</a>
                    <a class="menu-item" href="../page/boss_management.html">九方雲集</a>
                    <a class="menu-item" href="../page/boss_management.html">小豐牛肉麵</a>
                    <a class="menu-item" href="../page/boss_management.html">池下便當</a>
                    <a class="menu-item" href="../page/boss_management.html">起司馬鈴薯</a>
                    <a class="menu-item" href="../page/boss_management.html">阿嬌滷味</a>
                    <a class="menu-item" href="../page/boss_management.html">大GG鹹水G</a>
                    <a class="menu-item" href="../page/boss_management.html">白糖早餐店</a>
                    <a class="menu-item" href="../page/boss_management.html">SUKIYAKISI</a>
                    <a class="menu-item" href="../page/boss_management.html">甕搖雞</a>
                    <a class="menu-item" href="../page/boss_management.html">壽司狼</a>
                    <a class="menu-item" href="../page/boss_management.html">葬壽司</a>
                    -->
                </div>
            </div>
        </nav>
    </div>


        <div class="addstore" type="button" name="按鈕名稱" onclick="goRegister();">
            <div align="right">
                <img src="../images/addstore.png" alt="新增店家icon" />
                <span style="font-size: 25px;">新增店家</span>
            </div>
        </div>
</body>
<!--
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
-->
<script>
    function goRegister() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var boss_name = urlParams.get('boss_name');

        location.href='store_register_copy.php?boss_identity=' + boss_identity + '&boss_name=' + boss_name + "&back=1";
    }

    //當網頁準備好的時候，做以下的動作(函式)
    $(document).ready(function () {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_name = urlParams.get('boss_name');
        document.getElementById("boss_name").innerHTML = boss_name;
    });

    $('#btn-nav-previous').click(function(){
        $(".menu-inner-box").animate({scrollLeft: "-=700px"});
    });

    $('#btn-nav-next').click(function(){
        $(".menu-inner-box").animate({scrollLeft: "+=700px"});
    });
</script>

</html>