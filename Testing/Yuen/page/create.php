<?php
    session_start();
    include "../bin/conn.php";

    $identity = $_GET["boss_identity"];
    $store_id = $_GET["store_id"];

    $staff_id=$_GET['staff_id'];

    $sql ="select count(staff_id)+1 staff_id FROM store_staff "; // sql語法存在變數中
    //$sql ="select count(staff_id)+1 staff_id FROM store_staff where boss_identity = '$identity' and store_id = '$store_id'"; // sql語法存在變數中
    //echo $sql;

    $result = mysqli_query($con,$sql);
    //下一個員工編號
    $row_result = mysqli_fetch_assoc($result);
    $emp_staff_id = $row_result['staff_id'];

    //echo $emp_staff_id;
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
			<span style="font-size: 11px;">返回</span>
		</div>
	</div>
    <div class="container-wrapper">
        <form id="main" action="../bin/staff_in.php"  method="POST">
            <div class="container1">
                <div align="center">
                    <font size="20">員工資料管理</font><br><br>
                </div>

                <div class="insidebox">
                    <div class="ininsidebox">
                    <input type="hidden" id="boss_identity" name="boss_identity" value="">
                    <input type="hidden" id="store_id" name="store_id" value="">
                    <input type="hidden" id="data_type" name="data_type" value="staff">

                        <div class="input-box">
                            <span class="details">員工編號：</span>
                            <input type="text" class="form-control" value="<?php echo $emp_staff_id ?>" name="staff_id" id="staff_id"><br>                        </div>

                        <div class="input-box">
                            <span class="details">密碼：</span>
                            <input id="staff_psw" name="staff_psw" type="password" placeholder="格式：含英數至少六個字元"
                                pattern="^(?=.*[a-zA-Z])(?=.*[0-9]).{6,}$" required="required"
                                oninput="setCustomValidity('');"
                                oninvalid="setCustomValidity('請輸入正確的密碼格式：含英數至少六個字元');" />
                        </div>

                        <div class="input-box">
                            <span class="details">姓名：</span>
                            <input type="text" oninput="value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')"
                                name="staff_name" id="staff_name" placeholder="請輸入員工姓名" required>
                        </div>

                        <div class="input-box">
                            <span class="details">生日：</span>
                            <input type="date" name="staff_birth" id="staff_birth" placeholder="請輸員工生日" required>
                        </div>

                        <script>
                            // 獲取日期選擇器
                            var selectedDateInput = document.getElementById("staff_birth");

                            // 獲取今天的日期
                            var currentDate = new Date();

                            // 把今天日期减去16年
                            currentDate.setFullYear(currentDate.getFullYear() - 16);

                            // 格式化為 yyyy-MM-dd，並設置為日期選擇器最小值
                            var formattedMinDate = currentDate.toISOString().split('T')[0];
                            selectedDateInput.setAttribute("max", formattedMinDate);
                        </script>


                        <div class="input-box">
                            <span class="details">性別：</span>
                            <select name = "staff_gender" id="staff_gender" value="1">
                                <option value="男">男</option>
                                <option value="女">女</option>
                            </select>
                        </div>

                        <div class="input-box">
                            <span class="details">聯絡電話：</span>
                            <input type="text" name="staff_tel" id="staff_tel" placeholder="09xx-xxxxxx"
                                required="required" maxlength="11" pattern="09\d{2}-\d{6}"
                                oninput="setCustomValidity('');"
                                oninvalid="setCustomValidity('請輸入正確的手機號瑪格式：09xx-xxxxxx');" />
                        </div>

                        <div class="input-box">
                            <span class="details">地址：</span>
                            <input type="text" name="staff_address" id="staff_address" placeholder="請輸入員工的地址" required>
                        </div>

                        <div class="input-box">
                            <span class="details">到職日期：</span>
                            <input type="date" name="due_date" id="due_date" placeholder="請輸員工的到職日期" required>
                        </div>
                        <script>
                            // 獲取今天的日期
                            var today = new Date().toISOString().split('T')[0];

                            // 設置最小日期為今天
                            document.getElementById("due_date").max = today;
                        </script>

                        <div class="input-box">
                            <span class="details">緊急聯絡人：</span>
                            <input type="text" oninput="value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')" name="em_name"
                                id="em_name" placeholder="請輸入員工的緊急聯絡人姓名" required>
                        </div>

                        <div class="input-box">
                            <span class="details">緊急連絡人電話：</span>
                            <!-- <input type="text" oninput="value=this.value.replace(/\D/g,'')" name="em_tel" id="em_tel" placeholder="請輸入員工的緊急聯絡人電話" required> -->
                            <input type="text" name="em_tel" id="em_tel" placeholder="09xx-xxxxxx" required="required"
                                maxlength="11" pattern="09\d{2}-\d{6}" oninput="setCustomValidity('');"
                                oninvalid="setCustomValidity('請輸入正確的手機號瑪格式：09xx-xxxxxx');" />
                        </div>

                        <div class="input-box">
                            <span class="details">與緊急連絡人關係：</span>
                            <input type="text" oninput="value=this.value.replace(/[^\u4e00-\u9fa5]/g,'')"
                                name="relation" id="relation" placeholder="請輸入員工與緊急連絡人的關係" required>
                        </div>

                        <div class="input-box">
   
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
            <label class="submitbutton" onclick="saveData();" style="font-size: 16px;">儲存</label>

            <!-- <input id="sqlText"></input> -->

            <!-- <div class="checkbutton" type="check" name="按鈕名稱" onclick="location.href='employee.html'">
                <span style="font-size: 14px;">查看全部員工資料</span>
            </div> -->
        </form>
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

        var dataString = $("form#main").serialize();

        // alert(dataString);

        $.ajax({
            //HTTP的通訊模式有：GET、POST、DELETE。這次採用POST的模式，僅傳遞該傳遞的資料，不是整個網頁送回去
            type: "POST",
            //指定要連接的PHP位址
            url: "../bin/staff_in.php",
            //要傳送的資料內容
            data: dataString,
            //獲得正確回應時，要做的事情
            success: function (response) {
                // alert(response);
                var json = $.parseJSON(response);
                var msgIcon = 'success';
                if (json.result != 'OK') msgIcon = 'error';

                // document.getElementById("sqlText").value = json.message;

                Swal.fire(
                    '員工', //標題
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
        location.href="employee.php?boss_identity=" + boss_identity + "&store_id=" + store_id+ "&boss_name=" + boss_name;
    }
</script>
</html>