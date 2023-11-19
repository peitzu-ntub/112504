<?php
    include "../bin/conn.php";

    $identity = $_GET["boss_identity"];
    $store_id = $_GET["store_id"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>消費紀錄</title>

    <link href="../js/newrecord.css" rel="stylesheet">

</head>
<?php

// 設置一個空陣列來放資料
$datas = array();

$sql = "select date(b.start_time) as date, b.table_number, time(b.start_time) start_time, b.customer_count, time(b.end_time)end_time, c.meal_name
                                FROM store_order_item as a 
                                left join store_order as b
                                on a.boss_identity = b.boss_identity and a.store_id = b.store_id and a.order_no = b.order_no
                                left join store_food as c
                                on a.meal_id = c.meal_id
                                where a.boss_identity = '$identity' and a.store_id = '$store_id' and b.boss_identity = '$identity' and b.store_id = '$store_id'

                               "
                                ;
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
    <div class="logout" type="button" name="按鈕名稱" onclick="goBack()">
        <div align="left">
            <img src="../images/back.png" alt="返回icon" />
            <span style="font-size: 10px;">返回</span>
        </div>
    </div>
    <div class="container-wrapper">
    <form action="record.php" method="POST">
            <div class="subject">
                <div class="title">
                    <div align="left">
                        <img src="../images/money-bag.png" alt="icon圖片" />
                        <span style="font-size: 28px;">消費紀錄</span>
                    </div>
                </div>
                <div class="twosmall">
                    <form  method="POST">
                    <input type="hidden" id="boss_identity" name="boss_identity" 
<?php      
                //把老闆身份證號、店代號，放進隱藏欄位。供POST時使用
                echo "value=\"$identity\">";
?>                
                <input type="hidden" id="store_id" name="store_id" 
<?php                
                //把老闆身份證號、店代號，放進隱藏欄位。供POST時使用
                echo "value=\"$store_id\">";
                ?> 
                        <p class="inline-form">
                            查詢：<input type="search" name="查詢日期" class="light-table-filter" data-table="order-table" placeholder="請輸入日期">
                            <input type="submit" value="確認" >
                        </p>
                        <div class="ininsidebox" style="width:700px;height:330px; overflow:auto;">
                        <table border = '1' align = 'center' class='order-table'>
                    <thead>    
                    <tr>
                       <th>日期</th>
                                    <th>桌號</th>
                                    <th>開桌時間</th>
                                    <th>人數</th>
                                    <th>關桌時間</th>
                                    <th>餐點</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < $datas_len; $i++) {
                                echo "<tr>";
                                echo "<td>". $datas[$i]['date'] . "</span>";
                                echo "<td>". $datas[$i]['table_number'] . "</span>";
                                echo "<td>". $datas[$i]['start_time'] . "</span>";
                                echo "<td>". $datas[$i]['start_time'] . "</span>";
                                echo "<td>". $datas[$i]['end_time'] . "</span>";
                                echo "<td>". $datas[$i]['meal_name'] . "</span>";
                                echo "</br>";
                            }
                            ?>

                            </tbody>  
                    </table>

                </div>
                        
                    </form>

                    
            </div>
        </form>
    </div>
</body>
<script>
    (function(document) {
  'use strict';

  // 建立 LightTableFilter
  var LightTableFilter = (function(Arr) {

    var _input;

    // 資料輸入事件處理函數
    function _onInputEvent(e) {
      _input = e.target;
      var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
      Arr.forEach.call(tables, function(table) {
        Arr.forEach.call(table.tBodies, function(tbody) {
          Arr.forEach.call(tbody.rows, _filter);
        });
      });
    }

    // 資料篩選函數，顯示包含關鍵字的列，其餘隱藏
    function _filter(row) {
      var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
      row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
    }

    return {
      // 初始化函數
      init: function() {
        var inputs = document.getElementsByClassName('light-table-filter');
        Arr.forEach.call(inputs, function(input) {
          input.oninput = _onInputEvent;
        });
      }
    };
  })(Array.prototype);

  // 網頁載入完成後，啟動 LightTableFilter
  document.addEventListener('readystatechange', function() {
    if (document.readyState === 'complete') {
      LightTableFilter.init();
    }
  });

})(document);

    function goBack() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="boss_management.html?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
    function goRecord() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var store_id = urlParams.get('store_id');
        var boss_name = urlParams.get('boss_name');
        location.href="record.html?boss_identity=" + boss_identity + "&store_id=" + store_id + "&boss_name=" + boss_name;
    }
</script>

</html>