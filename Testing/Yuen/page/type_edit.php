<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>客製化1</title>

    <link href="../js/newmenu1.css" rel="stylesheet">

</head>

<?php
include "../bin/conn.php";

$type_name=$_GET['type_name'];

// 設置一個空陣列來放資料
$datas = array();

$sql ="select * FROM food_type WHERE type_name = '".$type_name."'"; // sql語法存在變數中

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
    <div class="logout" type="button" name="按鈕名稱" onclick="location.href='newmenu1.html'">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 10px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
        <form action="menu1.php" method="POST">
            <div class="container1">
                <div class="topinput" style="font-size: 15px;">
                    <font color="#bf6900" size="5">餐點類型：</font>
                    <input type="text" class="form-control" value="<?php echo $datas[0]['type_name'] ?>" name="type_name" ><br>
                </div>
                <div class="insidebox">
                    <div class="ininsidebox">
                        <table width="50%">
                            <tr>
                                <td>
                                    <div class="sidebar_left">刪除</div>
                                </td>
                                <td>
                                    <div class="content1">類型名稱</div>
                                </td>
                                <td>
                                    <div class="content2">編輯</div>
                                </td>
                                <td>
                                    <div class="sidebar_right">確定</div>
                                </td>
                            </tr>
                            <tbody>
                            <?php
                            for ($i = 0; $i < $datas_len; $i++) {
                                echo "<tr>";
                                echo "<td>
                                <a href='type_del.php?type_name=".$datas[$i]['type_name']."'><img src=../images/trash.png></img></a></td>";
                                echo "<td>" . $datas[$i]['type_name'] . "</td>";
                                echo "<td>
                                <a href='type_edit.php?type_name=".$datas[$i]['type_name']."'><img src=../images/signature.png></img></a></td>";
                             }
                            ?>

                            </tbody>
                        </table>
<!--                         <div class="input-box">
                            <div class="input-row"><span>類型名稱</span></div>
                            <div class="input-row"> <img src="../images/trash.png" /> <input type="" name="" id=""></div>
                        </div> -->
                        <input class="submit" type="submit" value="新增" style="font-size: 5px;">
                        </input>
                        <div class="nextstep" type="next" onclick="location.href='newmenu2.html'">
                            <span style="font-size: 5px;">下一步</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>



</html>