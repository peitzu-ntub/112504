<?php //單查詢
include "../bin/conn.php";

$Sname="staff_id";
$sql="select * from store_staff";

if(!empty($_POST["staff_id"]))//確定是否存在資料
{
    $Sname=$_POST["staff_id"];
    $sql="select * from store_staff where staff_id like '%{$Sname}%' ";//模糊查詢
}
?>