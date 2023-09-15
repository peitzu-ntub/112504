<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>消費紀錄</title>

    <link href="../js/newrecord.css" rel="stylesheet">

</head>

<body>
    <div class="logout" type="button" name="按鈕名稱" onclick="location.href='#.html'">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 10px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
        <form action="#">
            <div class="subject">
                <div class="title">
                    <div align="left">
                        <img src="../images/money-bag.png" alt="icon圖片" />
                        <span style="font-size: 28px;">消費紀錄</span>
                    </div>
                </div>
                <div class="twosmall">
                    <form action="record.php" method="POST">
                        <p class="inline-form">
                            查詢：<input type="date" name="查詢日期">
                            <input type="submit" value="確認">
                        </p>
                    </form>

                    <table border="1" align="center">
                        <tr>
                            <!-- <th>日期</th> -->
                            <th>桌號</th>
                            <th>開桌時間</th>
                            <th>人數</th>
                            <th>結帳時間</th>
                            <th>餐點明細</th>
                        </tr>
                    </table>
                </div>
            </div>
        </form>
    </div>
</body>


</html>