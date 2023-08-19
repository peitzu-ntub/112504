<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>員工資料</title>

    <link href="../js/create.css" rel="stylesheet">
</head>
<?php
include "../bin/conn.php";

$staff_id=$_GET['staff_id'];

// 設置一個空陣列來放資料
$datas = array();

$sql ="select * FROM store_staff WHERE staff_id = '".$staff_id."'"; // sql語法存在變數中

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
    // print_r($datas);
    //echo($datas[0]['adm_name']);
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
                        <form method="post" action="staff_up.php?staff_id=<?php echo $datas[0]['staff_id']?>">
                        
                            <span class="details"></span>
                            員工編號：<input type="text" class="form-control" value="<?php echo $datas[0]['staff_id'] ?>" name="staff_id"><br>
                        </div>
                        <div class="input-box">
                            <span class="details"></span>
                            姓名：<input type="text" class="form-control" value="<?php echo $datas[0]['staff_name'] ?>" name="staff_name"><br>
                        </div>

                        <div class="input-box">
                            <span class="details"></span>
                            生日：<input type="date" class="form-control" value="<?php echo $datas[0]['staff_birth'] ?>" name="staff_birth"><br>
                        </div>

                        <div class="input-box">
                            <span class="details"></span>
                            性別：<input type="text" class="form-control" value="<?php echo $datas[0]['staff_gender'] ?>" name="staff_gender"><br>
                        </div>

                        <div class="input-box">
                            <span class="details"></span>
                            聯絡電話：<input type="text" class="form-control" value="<?php echo $datas[0]['staff_tel'] ?>" name="staff_tel"><br>
                        </div>

                        <div class="input-box">
                            <span class="details"></span>
                            地址：<input type="text" class="form-control" value="<?php echo $datas[0]['staff_address'] ?>" name="staff_address"><br>
                        </div>

                        <div class="input-box">
                            <span class="details"></span>
                            緊急聯絡人：<input type="text" class="form-control" value="<?php echo $datas[0]['em_name'] ?>" name="em_name"><br>
                        </div>

                        <div class="input-box">
                            <span class="details"></span>
                            緊急聯絡人電話：<input type="text" class="form-control" value="<?php echo $datas[0]['em_tel'] ?>" name="em_tel"><br>
                        </div>

                        <div class="input-box">
                            <span class="details"></span>
                            緊急聯絡人關係：<input type="text" class="form-control" value="<?php echo $datas[0]['relation'] ?>" name="relation"><br>
                        </div>

                        <div class="input-box">
                            <span class="details"></span>
                            到職日期：<input type="date" class="form-control" value="<?php echo $datas[0]['due_date'] ?>" name="due_date"><br>
                        </div>

                        <div class="input-box">
                            <span class="details"></span>
                            密碼：<input type="text" class="form-control" value="<?php echo $datas[0]['staff_psw'] ?>" name="staff_psw"><br>

                        </div>
                    </div>
                </div>
            </div>

            <input class="submit" type="submit" value="儲存" style="font-size: 15px;"></input>
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