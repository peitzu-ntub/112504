<?php
    session_start();
    include "../bin/conn.php";

    $boss = $_GET["boss_identity"];
    $store = $_GET["store_id"];

    $data = array();

    //儲存
    if (isset($_POST["identity"])) {
        $boss = $_POST["identity"];
        $store = $_POST["store_id"];
        $data_store_name = $_POST["store_name"];
        $data_store_tel = $_POST["store_tel"];
        $data_store_address = $_POST["store_address"];
        $data_table_count = $_POST["table_count"];
        //更新store_info
        $sql = "update store_info set
            store_name = '$data_store_name',
            store_tel = '$data_store_tel',
            store_address = '$data_store_address',
            table_count = $data_table_count
        where boss_identity = '$boss' and store_id = '$store'";
        //執行
        mysqli_query($con, $sql);

        //刪除舊的store_table
        $sql = "delete from store_table
        where boss_identity = '$boss' and store_id = '$store'";
        //執行
        mysqli_query($con, $sql);

        //逐一新增store_table資料
        for ($i = 1; $i <= $data_table_count; $i++) {
            $sql = "insert into store_table (
                        boss_identity, store_id, table_number, seat_count, is_open
                    ) values (
                        '$boss', '$store', $i, 4, 'N'
                    )";
            //執行
            mysqli_query($con, $sql);                            
        }

        $data['result'] = 'OK';
        $data['message'] = '店舖資訊已更新';        
        echo json_encode($data);
        return;
        exit;
    }

    //打網址
    if (isset($_GET["boss_identity"])) {
        //Step(1)透過queryString取得老闆身份證號($boss)以及店代號($store)
        $boss = $_GET["boss_identity"];
        $store = $_GET["store_id"];    
        //Step(2)查出這個店舖的基本資料，以顯示在畫面上，供老闆修改
        //查詢語法
        $sql = "select * from store_info where boss_identity = '$boss' and store_id = '$store'";
        //取得查詢結果
        $result = mysqli_query($con, $sql);
        //個別取得欄位值，包含store_name, store_tel, store_address, table_count
        if (isset($result)) {
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $data_store_name = $data["store_name"];
            $data_store_tel = $data["store_tel"];
            $data_store_address = $data["store_address"];
            $data_table_count = $data["table_count"];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>店鋪資料更改</title>

    <link href="../js/store_inf_edit.css" rel="stylesheet">

    <script src="../js/jquery-3.6.4.min.js"></script>

    <!--取代alert的工具-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- 若需相容 IE11，要加載 Promise Polyfill-->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>    
</head>

<body>
    <div class="logout" type="button" name="按鈕名稱" onclick="goBack();">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 15px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
        <form id="main">
            <div class="container1">
                <font color="#000" size="15" style="align-items: center;">店鋪資料更改</font>
                <div class="insidebox">
                    <div class="ininsidebox">
                        <div class="input-box">
                            <div class="input-row">
<?php
echo "
                                <input type='hidden' id='identity' name='identity' value='$boss'>
                                <input type='hidden' id='store_id' name='store_id' value='$store'>
";
?>                                
                                <span class="details">店家名稱：</span>

<?php                                
//2023.10.01 顯示店家名稱
echo "
                                <input 
                                    type='text'  
                                    name='store_name'
                                    id='store_name'
                                    placeholder='請輸入店家名稱'
                                    value='$data_store_name'
                                    required
                                > 
";
?>                              
<!--
                                <input type="text" oninput="value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')" name="meal_name" id="meal_name" placeholder="請輸入餐點名稱" required>
-->
                                
                            </div>
                            <div class="input-row">
                                <span class="details">店家電話：</span>
                                <?php                                
//2023.10.01 顯示店家電話
echo "
                                <input 
                                    type='text'  
                                    name='store_tel'
                                    id='store_tel'
                                    placeholder='請輸入店家電話'
                                    value='$data_store_tel'
                                    required
                                > 
";
?>                              
<!--
                                <input type="text" oninput="value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')" name="meal_name" id="meal_name" placeholder="請輸入餐點名稱" required>
-->                                
                                
                            </div>
                            <div class="input-row">
                                <span class="details">店家地址：</span>
<?php                                
//2023.10.01 顯示店家地址
echo "
                                <input
                                    type='text'
                                    name='store_address'
                                    id='store_address'
                                    placeholder='請輸入店家地址'
                                    value='$data_store_address'
                                    style='resize: none;'
                                > 
";
?>                              
<!--                               
                             <textarea id="meal_note" oninput="value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')" name="meal_note" placeholder="請輸入餐點介紹" style="resize: none;" ></textarea>
-->                               
                            </div>
                            <div class="input-row">
                                <span class="details">店家桌數：</span>
                                <?php                                
//2023.10.01 顯示店家桌數
echo "
                                <input
                                    type='number
                                    min='1'                                    
                                    name='table_count'
                                    id='table_count'
                                    placeholder='請輸入桌數'
                                    value='$data_table_count'
                                > 
";
?>                              
<!--                                
                                <input type="number" min="0" name="meal_price" id="meal_price" placeholder="請輸入正確價格" required>
-->                                                                
                            </div>
                       </div>
                </div>
                <label class="submit" type="submit" style="font-size: 15px;" onclick="doSave();">儲存</label>
            </div>
        </form>
    </div>
</body>

<script>
    function doSave() {
        var dataString = $("form#main").serialize();
        // alert('submiting: ' + dataString);
        $.ajax({
            //HTTP的通訊模式有：GET、POST、DELETE。這次採用POST的模式，僅傳遞該傳遞的資料，不是整個網頁送回去
            type: "POST",
            //指定要連接的PHP位址
            url: "store_info_edit.php",
            //要傳送的資料內容
            data: dataString,
            //獲得正確回應時，要做的事情
            success: function (response) {
                // alert(response);
                var json = $.parseJSON(response);
                var msgIcon = 'success';
                if (json.result != 'OK') msgIcon = 'error';
                Swal.fire(
                    '店家資訊', //標題
                    json.message, //訊息容
                    msgIcon // 圖示 (success/info/warning/error/question)
                );
            },
            //獲得不正確的回應時，要做的事情
            error: function (response) {
                alert ('錯誤');
            },
        });
    }

    function goBack() {
        var urlParams = new URLSearchParams(window.location.search);        
        var boss_identity = urlParams.get('boss_identity');
        var boss_name = urlParams.get('boss_name');
        var store_id = urlParams.get('store_id');
        location.href="boss_management.html?boss_identity=" + boss_identity + "&boss_name=" + boss_name + "&store_id=" + store_id;;
    }
</script>
</html>