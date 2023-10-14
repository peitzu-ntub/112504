<?php 
include ("../bin/conn.php");



$sql ="select staff_id FROM store_staff order by staff_id desc limit 1"; // sql語法存在變數中
$result = mysqli_query($con,$sql);
$total_records = mysqli_num_rows($result);
while($row_result = mysqli_fetch_assoc($result)) {
echo "<td>".$row_result['staff_id']."</td>";

}


?>