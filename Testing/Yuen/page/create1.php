<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>員工資料</title>

    <link href="../js/create.css" rel="stylesheet">
</head>

<body>
    <div class="container-wrapper">
    <form action="staff_in.php" method="POST" enctype="multipart/form-data">
            <div class="container1">
                <div class="logout" type="button" name="按鈕名稱" onclick="location.href='newmenu1.html'">
                    <div align="left">
                        <img src="../images/back.png" alt="返回icon" />
                        <span style="font-size: 18px;">返回</span>
                    </div>
                </div>
                <div align="center">
                    <font size="20">員工資料管理</font>
                </div>

                <div class="insidebox">
                    <div class="ininsidebox">
                        <div class="input-box">
                            <span class="details">員工編號：</span>
                            <input type="text" name="staff_id" id="staff_id" placeholder="員工編號" required>
                        </div>
                        <div class="input-box">
                            <span class="details">姓名：</span>
                            <input type="text" name="staff_name" id="staff_name" placeholder="請輸入員工姓名" required>
                        </div>

                        <div class="input-box">
                            <span class="details">生日：</span>
                            <input type="date" name="staff_birth" id="staff_birth" placeholder="請輸員工生日" required>
                        </div>

                        <div class="input-box">
                            <span class="details">性別：</span>
                            <input type="radio" name="staff_gender" value="male"> 男<input type="radio" name="staff_gender" value="female"> 女
                        </div>

                        <div class="input-box">
                            <span class="details">聯絡電話：</span>
                            <input type="text" name="staff_tel" id="staff_tel" placeholder="請輸入員工的聯絡電話" required>
                        </div>

                        <div class="input-box">
                            <span class="details">地址：</span>
                            <input type="text" name="staff_address" id="staff_address" placeholder="請輸入員工的地址"
                                required>
                        </div>

                        <div class="input-box">
                            <span class="details">緊急聯絡人：</span>
                            <input type="text" name="em_name" id="em_name" placeholder="請輸入員工的緊急聯絡人姓名" required>
                        </div>

                        <div class="input-box">
                            <span class="details">緊急連絡人電話：</span>
                            <input type="text" name="em_tel" id="em_tel" placeholder="請輸入員工的緊急聯絡人電話" required>
                        </div>

                        <div class="input-box">
                            <span class="details">與緊急連絡人關係：</span>
                            <input type="text" name="relation" id="relation" placeholder="請輸入員工與緊急連絡人的關係" required>
                        </div>

                        <div class="input-box">
                            <span class="details">到職日期：</span>
                            <input type="date" name="due_date" id="due_date" placeholder="請輸員工的到職日期" required>
                        </div>

                        <div class="input-box">
                            <span class="details">密碼：</span>
                            <input type="text" name="staff_psw" id="staff_psw" placeholder="請輸入員工的密碼" required>
                        </div>
                    </div>
                </div>
            </div>
        
        <div class="button">
            <input value="儲存" type="submit" />
        </div>
        <!-- <div class="submit" type="submit" name="按鈕名稱" >
            <span style="font-size: 15px;">儲存</span>
        </div> -->
        <div class="laststep" type="return" name="按鈕名稱" onclick="location.href='newmenu1.html'">
            <span style="font-size: 15px;">上一步</span>
        </div>
        <div class="nextstep" type="next" name="按鈕名稱" onclick="location.href='newmenu3.html'">
            <span style="font-size: 15px;">下一步</span>
        </div>
        <div class="checkbutton" type="check" name="按鈕名稱" onclick="location.href='#.html'">
            <span style="font-size: 14px;">查看全部員工資料</span>
        </div>
    </div>
    </div>
    </form>
</body>
</html>