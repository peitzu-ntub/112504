<?php
    session_start();
    include "../bin/conn.php";

    //todo, 這是假的資料
    //預設的資料來源，是從登入而來。登入、選擇店家後，就會把以下這兩個資訊，放進SESSION裡，保留在Server端
    //讓同一個人的接續連線，可以直接拿來用
    if (!isset($_SESSION["identity"])) {
        $_SESSION["identity"] = "A123456789";
    }
    if (!isset($_SESSION["store_id"])) {
        $_SESSION["store_id"] = "S01";
    }

    //PHP是在後端(Server)運作的程式，Html與JavaScript則是在前端(Client)運作的程式
    //在Server端，透過PHP將身份證與店代號，保留於隱藏欄位中，以傳到前端，做後續的應用
    $boss = $_SESSION["identity"];
    $store = $_SESSION["store_id"];
?>
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

// 設置一個空陣列來放資料
$datas = array();

$sql = "SELECT type_name FROM food_type";

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
    <div class="logout" type="button" name="按鈕名稱" onclick="location.href='boss_management.html'">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 10px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
		<form action="menu1.php" method="POST">            <div class="container1">
            <div class="container1">
                <div class="topinput" style="font-size: 15px;">
                    <font color="#bf6900" size="5">餐點類型：</font>
                    <input name="type_id" id="type_id" placeholder="請輸入您欲新增的餐點類型">
                </div>

                <div class="insidebox">
                    <div class="ininsidebox" style="width:680px;height:300px; overflow:auto;">
                        <table width ="500" align="center" >
                            <tr>
                                <th><font size="5" text-align="center">刪除</th>
                                <th><font size="5">類型名稱</th>
                                <th><font size="5">編輯</th>
                            </tr>
							<tbody>
                            <?php
                            for ($i = 0; $i < $datas_len; $i++) {
                                echo "<tr>";
                                echo "<td align='center'>
                                <a href='type_del.php?type_name=".$datas[$i]['type_name']."'><img src=../images/trash1.png></img></a></td>";
                                echo "<td style='font-size: 25px;' align='center'> ". $datas[$i]['type_name'] . "</td>";
                                echo "<td align='center'>
                                <a href='type_edit.php?type_name=".$datas[$i]['type_name']."'><img src=../images/signature.png></img></a></td>";
                                echo "</br>";
                            }
                            ?>

                            </tbody>                        
						</table>
                    </div>

                    <input class="submit" type="submit" value="新增" style="font-size: 5px;"></input>
                    <div class="nextstep" type="next" onclick="location.href='newmenu2.html'">
                        <span style="font-size: 5px;">下一步</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>



</html>