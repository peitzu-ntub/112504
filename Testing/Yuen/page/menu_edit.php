<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>新增餐點</title>

    <link href="../js/edit.css" rel="stylesheet">
</head>
<?php
include "../bin/conn.php";

$meal_name=$_GET['meal_name'];

// 設置一個空陣列來放資料
$datas = array();

$sql ="select * FROM store_food WHERE meal_name = '".$meal_name."'"; // sql語法存在變數中

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
    <div class="logout" type="button" name="按鈕名稱" onclick="location.href='boss_management.html'">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 15px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
        <nav>
            <ul>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/allmenu.php">全部餐點</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/newmenu1.php">餐點類型</a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/newmenu2.php">新增餐點</a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li><li><a></a></li>
                <li><a style="background-color: #f4eac2;color: #5e5e5e;" href="../page/nm3.html">呈現方法</a></li>
            </ul>
        </nav>

        <div class="insidebox">
            <form method="post" action="menu_up.php?meal_name=<?php echo $datas[0]['meal_name']?>">
                <div style="width:320px;">
                    <img src="../images/add.png" />
                    <font color="#bf6900" size="5" >修改餐點</font>
                </div><br>

                <div class="ininsidebox">
                    <div class="input-box">
                        <div class="input-row">
                            <?php
                                    echo "<font color=#bf6900 size='4';>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原餐點類型：";
                                    echo $datas[0]['type_name'] ;
                                    echo "</br>";
                                    $query = "SELECT type_name FROM food_type";
                                    $result = mysqli_query($con, $query);
                                ?>
                            <span class="details">餐點類型：</span>
                            <select  name="type_name" >

                                    <?php while($row = mysqli_fetch_array($result)):;?>

                                        <!--下面 $row9['(資料表(add_role) 的欄位(STAFF_ROLE) )']; ---------->
                                        <option value="<?php echo $row['type_name'];?>"  
                                            <?PHP 
                                            
                                                if($value == $row['type_name']){echo "selected";} 
                                            ?> 
                                        >
                                            <?php echo $row['type_name'];?>
                                        </option>
                                    <?php endwhile;?>

                                </select>
                        </div>
                        <div class="input-row">
                            <span class="details">餐點名稱：</span>
                            <input type="text"  class="form-control" value="<?php echo $datas[0]['meal_name'] ?>" name="meal_name" >

                        </div>
                        <div class="input-row">
                            <span class="details">餐點介紹：</span>
                            <input type="text" class="form-control" value="<?php echo $datas[0]['meal_note'] ?>" name="meal_note" >

                        </div>
                        <div class="input-row">
                            <span class="details">餐點價格：</span>
                            <input type="number" class="form-control" value="<?php echo $datas[0]['meal_price'] ?>" name="meal_price" >                                
                        </div>
                        <div class="input-row">
                            <span class="details">餐點圖片：</span>
                            <input type="file" class="form-control" value="<?php echo $datas[0]['meal_pic'] ?>" name="meal_pic" >                     
                           </div>
                    </div>
                </div>
                <input class="submitbutton" type="submit" value="儲存"></input>
            </form>
        </div>
    </div>
</body>

</html>