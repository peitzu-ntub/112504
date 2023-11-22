<?php
    session_start();
    include "../bin/conn.php";

    $boss_identity = $_GET["boss_identity"];
    $store_id = $_GET["store_id"];
    $staff_id=$_GET['staff_id'];
    $staff_name = $_GET['staff_name'];


    $data = array();


//儲存
if (isset($_POST["boss_identity"])) {
    $boss_identity = $_POST["boss_identity"];
    $store_id = $_POST["store_id"];
    $staff_id = $_POST['staff_id'];
    $staff_name = $_POST['staff_name'];
    $staff_birth = $_POST['staff_birth'];
    $staff_gender = $data['staff_gender'];
    $staff_tel = $_POST['staff_tel'];
    $staff_address = $_POST['staff_address'];
    $due_date = $data['due_date'];
    $em_name = $_POST['em_name'];
    $em_tel = $_POST['em_tel'];
    $relation = $_POST['relation'];
    $staff_psw = $_POST['staff_psw'];

    // Check for duplicate name
    $duplicateCheckQuery = "SELECT COUNT(*) as count FROM store_staff 
                            WHERE boss_identity = '$boss_identity' 
                            AND store_id = '$store_id' 
                            AND staff_name = '$staff_name' 
                            AND staff_id != '$staff_id'";
    $duplicateCheckResult = mysqli_query($con, $duplicateCheckQuery);
    $duplicateCheckData = mysqli_fetch_array($duplicateCheckResult);
    $duplicateCount = $duplicateCheckData['count'];

    if ($duplicateCount > 0) {
        // Duplicate name found
        $data['result'] = 'ERROR';
        $data['message'] = '員工姓名重複，請使用其他姓名';
        echo json_encode($data);
        return;
    }

    // No duplicate, proceed with the update
    $sql = "UPDATE store_staff SET 
    staff_name = '$staff_name',staff_birth = '$staff_birth', 
    staff_tel = '$staff_tel',  staff_address = '$staff_address', 
    em_name = '$em_name', em_tel = '$em_tel', relation = '$relation', 
    staff_psw = '$staff_psw' 
    WHERE boss_identity = '$boss_identity' AND store_id = '$store_id' AND staff_id = '$staff_id'";
    //執行
    mysqli_query($con, $sql);

    $data['result'] = 'OK';
    $data['message'] = '員工資料已更新';        
    echo json_encode($data);
    return;
    exit;
}


    //打網址
    if (isset($_GET["boss_identity"])) {
        //Step(1)透過queryString取得老闆身份證號($boss)以及店代號($store)
        $boss_identity = $_GET["boss_identity"];
        $store_id = $_GET["store_id"];
        $staff_id = $_GET['staff_id'];
        $staff_name = '';
        $staff_birth = '';
        $staff_gender = '';
        $staff_tel = '';
        $staff_address = '';
        $due_date = '';
        $em_name = '';
        $em_tel = '';
        $relation = '';
        $due_date = '';
        $staff_psw = '';

        //Step(2)查出這個員工的基本資料，以顯示在畫面上，供老闆修改
        //查詢語法
        $sql ="select * FROM store_staff 
                WHERE boss_identity = '$boss_identity' 
                and store_id = '$store_id' 
                and staff_id = '$staff_id'"; // sql語法存在變數中
        //取得查詢結果
        $result = mysqli_query($con, $sql);
        //個別取得欄位值
        if (isset($result)) {
            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $staff_name = $data['staff_name'];
            $staff_birth = $data['staff_birth'];
            $staff_gender = $data['staff_gender'];
            $staff_tel = $data['staff_tel'];
            $staff_address = $data['staff_address'];
            $due_date = $data['due_date'];
            $em_name = $data['em_name'];
            $em_tel = $data['em_tel'];
            $relation = $data['relation'];
            $staff_psw = $data['staff_psw'];    
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>員工資料</title>

    <script src="../js/jquery-3.6.4.min.js"></script>
    <link href="../js/create_new.css" rel="stylesheet">
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
        <form id="main">
            <div class="container1">
                <div align="center">
                    <font size="20">員工資料管理</font><br><br>
                </div>

                <div class="insidebox">
                    <div class="ininsidebox">
                        <div class="input-box">
                        <?php
echo "
                                <input type='hidden' id='boss_identity' name='boss_identity' value='$boss_identity'>
                                <input type='hidden' id='store_id' name='store_id' value='$store_id'>
                                <input type='hidden' id='staff_id' name='staff_id' value='$staff_id'>
                                <input type='hidden' id='staff_name' name='staff_name' value='$staff_name'>
                                

";
?>  

                            <span class="details">員工編號：</span>
                            <?php                                
echo "
                            <input type='text' name='staff_id' id='staff_id' value='$staff_id' disabled>
";
?> 
                            <br>
                            </div>

                        <div class="input-box">
                            <span class="details">密碼：</span>
                            <?php                                
echo "
                            <input type='text' pattern='^(?=.*[a-zA-Z])(?=.*[0-9]).{6,}$' required='required' 
                            oninput='setCustomValidity('');' oninvalid='setCustomValidity('請輸入正確的密碼格式：含英數至少六個字元');'
                            name='staff_psw' id='staff_psw' placeholder='格式：含英數至少六個字元' value='$staff_psw'>
";
?> 
                        <br></div>
                        <div class="input-box">
                            <span class="details">姓名：</span>
                            <?php                                
echo "
                            <input type='text' oninput='value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')'
                            placeholder=請輸入員工姓名 name='staff_name' id='staff_name' value='$staff_name' required>
";
?> 
                        <br></div>

                        <div class="input-box">
                            <span class="details">生日：</span>
                            <?php                                
echo "
                            <input type='date' name='staff_birth' id='staff_birth' value='$staff_birth' disabled>
";
?> 
                        <br></div>

                        <div class="input-box">
                            <span class="details">性別：</span>
                            <?php                                
echo "
                            <input type='text' name='staff_gender' id='staff_gender' value='$staff_gender' disabled>
";
?> 
                        <br></div>

                        <div class="input-box">
                            <span class="details">聯絡電話：</span>
                            <?php                                
echo "
                            <input type='text'  maxlength='11' pattern='09\d{2}-\d{6}' required='required' 
                            oninput='setCustomValidity('');' oninvalid='setCustomValidity('請輸入正確的手機號瑪格式：09xx-xxxxxx');'
                            name='staff_tel' id='staff_tel' placeholder='09xx-xxxxxx' value='$staff_tel'>
";
?> 
                        <br></div>

                        <div class="input-box">
                            <span class="details">地址：</span>
                            <?php                                
echo "
                            <input type='text' placeholder=請輸入員工的地址 name='staff_address' id='staff_address' value='$staff_address' required>
";
?> 
                        <br></div>


                        
                        <div class="input-box">
                            <span class="details">到職日期：</span>
                            <?php                                
echo "
                            <input type='date' name='due_date' id='due_date' value='$due_date' disabled>
";
?> 
                        <br></div>

                        
                        <div class="input-box">
                            <span class="details">緊急聯絡人：</span>
                            <?php                                
echo "
                            <input type='text' oninput='value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')'
                            placeholder='請輸入員工的緊急聯絡人姓名' name='em_name' id='em_name' value='$em_name' required>
";
?> 
                        <br></div>

                        <div class="input-box">
                            <span class="details">緊急連絡人電話：</span>
                            <?php                                
echo "
                            <input type='text'  maxlength=11' pattern='09\d{2}-\d{6}' required='required' maxlength='11' 
                            oninput='setCustomValidity('');' oninvalid='setCustomValidity('請輸入正確的手機號瑪格式：09xx-xxxxxx');'
                            name='em_tel' id='em_tel' placeholder='09xx-xxxxxx' value='$em_tel'>
";
?> 
                        <br></div>

                        <div class="input-box">
                            <span class="details">與緊急連絡人關係：</span>
                            <?php                                
echo "
                            <input type='text' oninput='value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')'
                            placeholder='請輸入員工與緊急連絡人的關係' name='relation' id='relation' value='$relation' required>
";
?> 
                        <br></div>

                        <div class="input-box">
                            <span class="details"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="laststep" type="return" name="按鈕名稱" onclick="location.href='boss_management.html'">
                <span style="font-size: 15px;">上一步</span>
            </div>
            <div class="nextstep" type="next" name="按鈕名稱" onclick="location.href='employee.html'">
                <span style="font-size: 15px;">下一步</span>
            </div> -->
            </div>
                <label class="checkbutton" value="儲存" onclick="doSave();">儲存</label>
            </div>


            <!-- <div class="checkbutton" type="check" name="按鈕名稱" onclick="location.href='employee.html'">
                <span style="font-size: 14px;">查看全部員工資料</span>
            </div> -->
    </form>
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
            url: "staff_edit.php",
            //要傳送的資料內容
            data: dataString,
            //獲得正確回應時，要做的事情
            success: function (response) {
                // alert(response);
                var json = $.parseJSON(response);
                var msgIcon = 'success';
                if (json.result != 'OK') msgIcon = 'error';
                Swal.fire(
                    '員工資料', //標題
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
        location.href="employee.php?boss_identity=" + boss_identity + "&boss_name=" + boss_name + "&store_id=" + store_id;;
    }
</script>
</html>