<?php
session_start();
include "../bin/conn.php";

$boss_identity = $_GET["boss_identity"];
$store_id = $_GET["store_id"];
$meal_id = $_GET['meal_id'];
$meal_name = $_GET['meal_name'];

$data = array();

//儲存
if (isset($_POST["boss_identity"])) {
    $boss_identity = $_POST["boss_identity"];
    $store_id = $_POST["store_id"];
    $meal_id = $_POST['meal_id'];
    $type_name = $_POST['type_name'];
    $meal_name = $_POST['meal_name'];
    $meal_price = $_POST['meal_price'];
    $meal_note = $_POST['meal_note'];

    // Check for duplicate name
    $duplicateCheckQuery = "SELECT COUNT(*) as count FROM store_food 
                            WHERE boss_identity = '$boss_identity' 
                            AND store_id = '$store_id' 
                            AND meal_name = '$meal_name' 
                            AND meal_id != '$meal_id'";
    $duplicateCheckResult = mysqli_query($con, $duplicateCheckQuery);
    $duplicateCheckData = mysqli_fetch_array($duplicateCheckResult);
    $duplicateCount = $duplicateCheckData['count'];

    if ($duplicateCount > 0) {
        // Duplicate name found
        $data['result'] = 'ERROR';
        $data['message'] = '餐點名稱重複，請使用其他名稱';
        echo json_encode($data);
        return;
    }

    // No duplicate, proceed with the update
    $sql = "UPDATE store_food SET 
                type_name = '$type_name',
                meal_name = '$meal_name', 
                meal_price = '$meal_price',  
                meal_note = '$meal_note' 
                WHERE boss_identity = '$boss_identity' AND store_id = '$store_id' AND meal_id = '$meal_id'";
    //執行
    mysqli_query($con, $sql);

    $data['result'] = 'OK';
    $data['message'] = '餐點類型已更新';
    echo json_encode($data);
    return;
}

//打網址
if (isset($_GET["boss_identity"])) {
    //Step(1)透過queryString取得老闆身份證號($boss)以及店代號($store)
    $boss_identity = $_GET["boss_identity"];
    $store_id = $_GET["store_id"];
    $meal_id = $_GET['meal_id'];
    $type_name = '';
    $meal_name = '';
    $meal_price = '';
    $meal_note = '';

    //Step(2)查出這個餐點資料，以顯示在畫面上，供老闆修改
    //查詢語法
    $sql = "SELECT * FROM store_food 
            WHERE boss_identity = '$boss_identity' 
            and store_id = '$store_id' 
            and meal_id = '$meal_id'";
    // sql語法存在變數中
    //取得查詢結果
    $result = mysqli_query($con, $sql);
    //個別取得欄位值
    if (isset($result)) {
        $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $type_name = $data['type_name'];
        $meal_name = $data['meal_name'];
        $meal_price = $data['meal_price'];
        $meal_note = $data['meal_note'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>新增餐點</title>
    <script src="../js/jquery-3.6.4.min.js"></script>
    <link href="../js/edit.css" rel="stylesheet">
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
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goAllmenu()">全部餐點</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goMenu1()">餐點類型</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goMenu2()">新增餐點</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goNM3();">菜單呈現設定</a></li>
            </ul>
        </nav>

        <div class="insidebox">
            <form id="main">
                <div style="width:320px;">
                    <img src="../images/add.png" />
                    <font color="#bf6900" size="5" >修改餐點</font>
                </div><br>

                <div class="ininsidebox">
                    <div class="input-box">
                        <div class="input-row">
                        <?php
echo "
                                <input type='hidden' id='boss_identity' name='boss_identity' value='$boss_identity'>
                                <input type='hidden' id='store_id' name='store_id' value='$store_id'>
                                <input type='hidden' id='meal_id' name='meal_id' value='$meal_id'>
                                <input type='hidden' id='meal_name' name='meal_name' value='$meal_name'>
                                

";
?>  
                            <?php
                                    echo "<font color=#bf6900 size='4';>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原餐點類型：";
                                    echo $type_name;
                                    echo "</br>";
                                    $query = "SELECT type_name FROM food_type where boss_identity = '$identity' and store_id = '$store_id'";
                                    $result = mysqli_query($con, $query);
                                ?>
                            <span class="details">餐點類型：</span>
                            <select  name="type_name" >

                            <?php
                                    $sql = "
                                        select * from food_type
                                        where boss_identity = '$boss_identity' and store_id = '$store_id'";
                                    $meal_type = mysqli_query($con, $sql);
                                    while ($cat = mysqli_fetch_array($meal_type,MYSQLI_ASSOC)) {

                                        $type_name=$cat['type_name'];
                                        echo "  <option value='$type_name'>$type_name</option>";
                                    }
                                    ?> 

                                </select>
                        </div>
                        <div class="input-row">
                            <span class="details">餐點名稱：</span>
                            <?php                                
echo "
                        <input type='text' name='meal_name' id='meal_name' placeholder='請輸入餐點名稱' value='$meal_name'> 
";
?>   
                        </div>
                        <div class="input-row">
                            <span class="details">餐點介紹：</span>
                            <?php                                
echo "
                        <input type='text' name='meal_note' id='meal_note' value='$meal_note'> 
";
?>                       
                        </div>
                        <div class="input-row">
                            <span class="details">餐點價格：</span>
                            <?php                                
echo "
                        <input type='text' name='meal_price' id='meal_price' placeholder='請輸入餐點價格' value='$meal_price'> 
";
?>                                   
                        </div>
                        <div class="input-row">
                            <span class="details">餐點圖片：</span>
                            <input type="file" class="form-control" value="<?php echo $datas[0]['meal_pic'] ?>" name="meal_pic" >                     
                           </div>
                    </div>
                </div>
                <input class="submitbutton" onclick="doSave();" value="儲存"></input>
            </form>
        </div>
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
            url: "menu_edit.php",
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
        location.href="allmenu.php?boss_identity=" + boss_identity + "&boss_name=" + boss_name + "&store_id=" + store_id;;
    }
    function goAllmenu() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="allmenu.php?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
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
    function goNM3() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="nm3.php?boss_identity=" + boss_identity + "&store_id=" + store_id+ "&boss_name=" + boss_name;
    }
</script>
</html>