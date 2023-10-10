<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>員工資料</title>

    <link href="../js/create_new.css" rel="stylesheet">
</head>
<?php

include "../bin/conn.php";

// 設置一個空陣列來放資料

$sql ="select staff_id FROM store_staff order by staff_id "; // sql語法存在變數中
$result = mysqli_query($con,$sql);
$total_records = mysqli_num_rows($result);
$total = $total_records + 1;


?>
<body>
    <div class="logout" type="button" name="按鈕名稱" onclick="location.href='employee.html'">
		<div align="left">
			<img src="../images/back.png" alt="返回icon" />
			<span style="font-size: 11px;">返回</span>
		</div>
	</div>
    <div class="container-wrapper">
        <form action="staff_in.php" method="POST" enctype="multipart/form-data">
            <div class="container1">
                <div align="center">
                    <font size="20">員工資料管理</font><br><br>
                </div>

                <div class="insidebox">
                    <div class="ininsidebox">
                        <div class="input-box">
                            <span class="details">員工編號：</span>
                            <input type="text" class="form-control" value="<?php echo $total ?>" name="staff_id" disabled><br>                        </div>

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

            <input class="submit" type="submit" value="儲存" style="font-size: 16px;"></input>

            <!-- <div class="checkbutton" type="check" name="按鈕名稱" onclick="location.href='employee.html'">
                <span style="font-size: 14px;">查看全部員工資料</span>
            </div> -->
        </form>
    </div>
</body>

</html>