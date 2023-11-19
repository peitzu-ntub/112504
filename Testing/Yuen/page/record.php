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
                        搜尋：<input type="search" class="light-table-filter" data-table="order-table" placeholder="請輸入關鍵字">

                        </p>
                        <?php

                        $查詢 = $_POST["查詢日期"];

                            include ("../bin/conn.php");

                                $sql = "select date(b.start_time) as date, b.table_number, time(b.start_time) start_time, b.customer_count, time(b.end_time)end_time, c.meal_name
                                FROM store_order_item as a 
                                left join store_order as b
                                on a.boss_identity = b.boss_identity and a.store_id = b.store_id and a.order_no = b.order_no
                                left join store_food as c
                                on a.meal_id = c.meal_id
                                where a.boss_identity = '$identity' and a.store_id = '$store_id' and b.boss_identity = '$identity' and b.store_id = '$store_id'

                               "
                                ;

                            
                                $result = mysqli_query($con,$sql);
                                $total_records = mysqli_num_rows($result);

                                
                                echo  "<table border = '1' align = 'center' class='order-table'>";
                                echo "<tr>";
                                    echo "<th>日期</th>";
                                    echo "<th>桌號</th>";
                                    echo "<th>開桌時間</th>";
                                    echo "<th>人數</th>";
                                    echo "<th>關桌時間</th>";
                                    echo "<th>餐點</th>";
                        
                                
                                while($row_result = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>".$row_result['date']."</td>";
                                    echo "<td>".$row_result['table_number']."</td>";
                                    echo "<td>".$row_result['start_time']."</td>";
                                    echo "<td>".$row_result['customer_count']."</td>";
                                    echo "<td>".$row_result['end_time']."</td>";
                                    echo "<td>".$row_result['meal_name']."</td>";
                                    echo "</tr>";
                                }
                                for($row=0;$row<count($contact1);$row++)
                                {
                                    echo '<tr>';
                                    //使用內層迴圈遍歷陣列$contact1 中 子陣列的每個元素,使用count()函數控制迴圈次數
                                    for($col=0;$col<count($result[$row]);$col++)
                                    {
                                        echo '<td>'.$result[$row][$col].'</td>';
                                    }
                                    echo '</tr>';
                                }

                                echo "</table>";
                                
                        ?>
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