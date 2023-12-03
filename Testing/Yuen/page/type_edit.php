<?php
    session_start();
    include "../bin/conn.php";

    $boss_identity = $_GET["boss_identity"];
    $store_id = $_GET["store_id"];
    $type_id = $_GET['type_id'];
    $type_name = $_GET['type_name'];
    $data = array();

    //儲存
    if (isset($_POST["boss_identity"])) {
        $boss_identity = $_POST["boss_identity"];
        $store_id = $_POST["store_id"];
        $type_id = $_POST['type_id'];
        $type_name = $_POST['type_name'];

        // Check if the updated type_name already exists for the same store_id and boss_identity
        $checkSql = "SELECT * FROM food_type WHERE boss_identity = '$boss_identity' AND store_id = '$store_id' AND type_name = '$type_name' AND type_id != '$type_id'";
        $checkResult = mysqli_query($con, $checkSql);

        if (mysqli_num_rows($checkResult) == 0) {
            // If the type_name doesn't exist, update the record
            $sql = "UPDATE food_type SET type_name = '$type_name' WHERE boss_identity = '$boss_identity' AND store_id = '$store_id' AND type_id = '$type_id'";
            mysqli_query($con, $sql);

            $data['result'] = 'OK';
            $data['message'] = '餐點類型已更新';
            echo json_encode($data);
            return;
            exit;
        } else {
            // If the type_name already exists, return an error message
            $data['result'] = 'NG';
            $data['message'] = '餐點類型已存在';
            echo json_encode($data);
            return;
            exit;
        }
    }

    // 打網址
    if (isset($_GET["boss_identity"])) {
        // Step(1)透過queryString取得老闆身份證號($boss)以及店代號($store)
        $boss_identity = $_GET["boss_identity"];
        $store_id = $_GET["store_id"];
        $type_id = $_GET['type_id'];
        $type_name = '';
        // Step(2)查出這個類型的基本資料，以顯示在畫面上，供老闆修改
        // 查詢語法
        $sql ="SELECT type_name FROM food_type WHERE boss_identity = '$boss_identity' AND store_id = '$store_id' AND type_id = '$type_id'";
        // 取得查詢結果
        $result = mysqli_query($con, $sql);
        // 個別取得欄位值，包含type_name
        if (isset($result)) {
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $type_name = $data['type_name'];
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>編輯類型</title>

    <script src="../js/jquery-3.6.4.min.js"></script>
    <link href="../js/edittype.css" rel="stylesheet">
    <!--取代alert的工具-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- 若需相容 IE11，要加載 Promise Polyfill-->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>    
</head>

<body>

    <div class="container-wrapper">
        <form id="main">            
            <div class="container1">
                <div class="upsidebox">
                    <span style="font-size: 40px;">
                        <img src="../images/menu.png" />
                        編輯餐點類型
                        <!-- <img src="../images/menu.png" /> -->
                    </span>
                </div>
    
                <div class="downsidebox"></div>
                <div class="insidebox">
<?php
echo "
                                <input type='hidden' id='boss_identity' name='boss_identity' value='$boss_identity'>
                                <input type='hidden' id='store_id' name='store_id' value='$store_id'>
                                <input type='hidden' id='type_id' name='type_id' value='$type_id'>
                                <input type='hidden' id='type_name' name='type_name' value='$type_name'>

";
?>  
                    <div class="topinput" style="font-size: 15px;">
                        <font color="#5db6f1" size="5">餐點類型：</font>
                        <?php                                

echo "
                        <input type='text' name='type_name' id='type_name' placeholder='請輸入店家名稱' value='$type_name'> 
";
?>              
                    </div>
                </div>
                <div class="logout" type="button" name="按鈕名稱" onclick="goBack();">
                    <img src="../images/back.png" alt="返回icon" />
                    <span style="font-size: 15px;">返回</span>
                </div>
                <label class="checkbutton" value="儲存" onclick="doSave();">儲存</label>
            </div>
        </form>
    </div>
</body>
                               
                                
<script>
    function doSave() {
        //alert('x');
        var dataString = $("form#main").serialize();
        //alert('submiting: ' + dataString);
        $.ajax({
            //HTTP的通訊模式有：GET、POST、DELETE。這次採用POST的模式，僅傳遞該傳遞的資料，不是整個網頁送回去
            type: "POST",
            //指定要連接的PHP位址
            url: "type_edit.php",
            //要傳送的資料內容
            data: dataString,
            //獲得正確回應時，要做的事情
            success: function (response) {
                // alert(response);
                var json = $.parseJSON(response);
                var msgIcon = 'success';
                if (json.result != 'OK') msgIcon = 'error';
                Swal.fire(
                    '餐點類型', //標題
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
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="newmenu1.php?boss_identity=" + boss_identity + "&boss_name=" + boss_name + "&store_id=" + store_id;;
    }
</script>
</html>