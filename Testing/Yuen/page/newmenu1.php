<?php
    session_start();
    include "../bin/conn.php";

    $identity = $_GET["boss_identity"];
    $store_id = $_GET["store_id"];
    $boss_name= $_GET["boss_name"];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>新增餐點類型</title>
    <script src="../js/jquery-3.6.4.min.js"></script>
    <link href="../js/m.css" rel="stylesheet">

    <!--取代alert的工具-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- 若需相容 IE11，要加載 Promise Polyfill-->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>    
</head>
<?php

// 設置一個空陣列來放資料
$datas = array();

$sql = "SELECT type_id, type_name FROM food_type where boss_identity = '$identity' and store_id = '$store_id'";

$result = mysqli_query($con, $sql); // 用mysqli_query方法執行(sql語法)將結果存在變數中

// 如果有資料
if ($result) {
    // mysqli_num_rows方法可以回傳我們結果總共有幾筆資料
    if (mysqli_num_rows($result) > 0) {
        // 取得大於0代表有資料
        // while迴圈會根據資料數量，決定跑的次數
        // mysqli_fetch_assoc方法可取得一筆值
        while ($row = mysqli_fetch_assoc($result)) {
            // 每跑一次迴圈就抓一筆值，最後放進data陣列中
            $datas[] = $row;
        }
    }
    // 釋放資料庫查到的記憶體
    mysqli_free_result($result);
} else {
    echo "{$sql} 語法執行失敗，錯誤訊息: " . mysqli_error($link);
}
// 處理完後印出資料
if (!empty($result)) {
    // 如果結果不為空，就利用print_r方法印出資料
    //print_r($datas);
} else {
    // 為空表示沒資料
    echo "查無資料";
}
echo "<br><br>";
//echo $datas[0]['sf_name']; // 印出第0筆資料中的sf_name欄位值

//使用表格排版用while印出
$datas_len = count($datas); //目前資料筆數

?>
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
                <li><a>餐點類型</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goMenu2()">新增餐點</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" onclick="goNM3();">菜單呈現設定</a></li>
            </ul>
        </nav>

        <div class="insidebox">
            <form id="main">            
                <input type="hidden" id="boss_identity" name="boss_identity" 
<?php      
                //把老闆身份證號、店代號，放進隱藏欄位。供POST時使用
                echo "value=\"$identity\">";
?>                
                <input type="hidden" id="store_id" name="store_id" 
<?php                
                //把老闆身份證號、店代號，放進隱藏欄位。供POST時使用
                echo "value=\"$store_id\">";
?>                
                <input type="hidden" id="data_type" name="data_type" value="menu1">
                <input type="hidden" id="data_value" name="data_value" value="">

                <div class="input-box">
                    <div class="topinput" style="font-size: 15px;">
                        <img src="../images/edit.png" />
                        <font color="#bf6900" size="5">餐點類型：</font>
                        <input name="type_name" id="type_name" placeholder="請輸入您欲新增的餐點類型" required>
                        <label id="btnSave" name="btnSave" class="checkbutton" value="新增" onclick="saveData();">新增</label>
                    </div>
                </div><br>

                <div class="ininsidebox" style="width:700px;height:330px; overflow:auto;">
                <table width ="500" align="center" >
                            <tr>
                                <th><font size="5">刪除</th>
                                <th><font size="5">類型名稱</th>
                                <th><font size="5">編輯</th>
                            </tr>
                            <tbody>
<?php
                            for ($i = 0; $i < $datas_len; $i++) {
                                $type_name = $datas[$i]['type_name'];
                                $type_id = $datas[$i]['type_id'];
                                echo "
                            <tr>
                                <td align='center'>
                                <img src=../images/trash1.png onclick='deleteData(\"$type_name\");'></img>
                                </td>
                                <td style='font-size: 25px;' align='center'>
                                    $type_name
                                </td>
                                <td align='center'>
                                <a href='type_edit.php?boss_identity=$identity&store_id=$store_id&type_id=$type_id&boss_name=$boss_name'>
                                        <img src=../images/signature.png></img>
                                    </a>

                                    </a>
                                </td>
                            </tr>";
                            }
?>

                            </tbody>  
                    </table>

                </div>
            </form>
        </div>
    </div>
</body>

<script>
    function doSubmit() {
        var dataString = $("form#main").serialize();
        // alert('submiting: ' + dataString);
        $.ajax({
            //HTTP的通訊模式有：GET、POST、DELETE。這次採用POST的模式，僅傳遞該傳遞的資料，不是整個網頁送回去
            type: "POST",
            //指定要連接的PHP位址
            url: "../bin/menu1.php",
            //要傳送的資料內容
            data: dataString,
            //獲得正確回應時，要做的事情
            success: function (response) {
                // alert(response);
                var json = $.parseJSON(response);
                var msgIcon = 'success';
                if (json.result != 'OK') msgIcon = 'error';
                Swal.fire(
                    '餐點', //標題
                    json.message, //訊息容
                    msgIcon // 圖示 (success/info/warning/error/question)
                ).then((result) => {
                    location.reload();
                });
            },
            //獲得不正確的回應時，要做的事情
            error: function (response) {
                alert ('錯誤');
            },
        });
    }

    //儲存餐點資料、圖檔
    function saveData() {
        document.getElementById("data_type").value = "menu1";

        doSubmit();
    }

    function deleteData(typeName) {
        document.getElementById("data_type").value = "menu1_delete";
        document.getElementById("data_value").value = typeName;

        // alert(typeName);

        Swal.fire({
            title: "餐點類型",
            text: "確定要刪除 " + typeName +" 嗎？",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "是",
            cancelButtonText: "取消",
        }).then((result) => {
            if (result.isConfirmed) {
                doSubmit();
            }
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
    function goTypeedit() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="type_edit.php?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
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