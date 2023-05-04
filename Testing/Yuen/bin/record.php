<?php

    $查詢 = $_POST["查詢日期"];

        include ("conn.php");

            $sql = "select date(b.start_time) as date, b.table_number, b.start_time, b.customer_count, b.end_time, a.meal_id
                            FROM store_order_item as a 
                            left join store_order as b
                            on a.boss_identity = b.boss_identity and a.store_id = b.store_id and a.order_no = b.order_no
                            where date(b.start_time) = '$查詢'"
            ;

        
            $result = mysqli_query($con,$sql);
            $total_records = mysqli_num_rows($result);

            
            echo  "<table>";
            echo "<tr>";
                echo "<th>日期</th>";
                echo "<th>桌號</th>";
                echo "<th>開桌時間</th>";
                echo "<th>人數</th>";
                echo "<th>結帳時間</th>";
                echo "<th>餐點明細</th>";
            echo "</tr>";
            while($row_result = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row_result['date']."</td>";
                echo "<td>".$row_result['table_number']."</td>";
                echo "<td>".$row_result['start_time']."</td>";
                echo "<td>".$row_result['customer_count']."</td>";
                echo "<td>".$row_result['end_time']."</td>";
                echo "<td>".$row_result['meal_id']."</td>";
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

<!-- $sql_query = "select date(b.start_time), b.table_number, b.start_time, b.customer_count, b.end_time, a.meal_id
            FROM store_order_item as a left join store_order as b
            on a.boss_identity = b.boss_identity,a.store_id = b.store_id,a.order_no = b.order_no
            where date(b.start_time) = $查詢" -->

