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

    <title>客製化2</title>

    <link href="../js/newmenu2.css" rel="stylesheet">

</head>

<body>
    <div class="container-wrapper">
        <form action="newfood.php" method="POST">
            <div class="container1">
                <font color="#e8a95b" size="6"style="align-items: center;">新增餐點</font>
                <div class="insidebox">
                    <div class="ininsidebox">
                        <div class="input-box">
                            <div class="input-row">
                                <span class="details">餐點類型：</span>
                                <select name="type_name" id="type_name">
                                    <?php
                                    $sql = "
                                        select * from food_type
                                        where boss_identity = '$boss' and store_id = '$store'";
                                    $meal_type = mysqli_query($con, $sql);
                                    while ($cat = mysqli_fetch_array($meal_type,MYSQLI_ASSOC)) {
                                        $type_id=$cat['type_id'];
                                        $type_name=$cat['type_name'];
                                        echo "  <option value='$type_id'>$type_name</option>";
                                    }
                                    ?> 
                                </select>
                            </div>
                            <div class="input-row">
                                <span class="details">餐點名稱：</span>
                                <input type="text" name="meal_name" id="meal_name" placeholder="請輸入餐點名稱" required>
                            </div>
                            <div class="input-row">
                                <span class="details">餐點介紹：</span>
                                <textarea id="meal_note" name="meal_note" rows="2" cols="20" placeholder="請輸入餐點介紹(上限50字)" style="resize: none;" maxlength="50"></textarea>
                            </div>
                            <div class="input-row">
                                <span class="details">餐點價格：</span>
                                <input type="number" min="0" name="meal_price" id="meal_price" placeholder="請輸入正確價格" required>
                            </div>
                            <div class="input-row">
                                <span class="details">餐點圖片：</span>
                                <input type="file" name="meal_pic" id="meal_pic">
                            </div>
                        </div>

                        <input class="submit" type="submit" value="儲存" style="font-size: 5px;"></input>
                        <div class="laststep" type="return" onclick="location.href='newmenu1.php'">
                            <span style="font-size: 5px;">上一步</span>
                        </div>
                        <div class="nextstep" type="next" onclick="location.href='newmenu3.html'">
                            <span style="font-size: 5px;">下一步</span>
                        </div>
                        <input class="checkbutton" type="check" value="查看全部餐點" style="font-size: 5px;"onclick="location.href='allmenu.php'"></input>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>