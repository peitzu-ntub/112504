<?php 
include ("../bin/conn.php"); //這是引入剛剛寫完，用來連線的.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>員工資料</title>

	<link href="../js/create.css" rel="stylesheet">
</head>

<body>

<?php 
	$sql = "SELECT staff_id, staff_name FROM store_staff  "; 

	

	$result = mysqli_query($con,$sql);
?>
<div class="container-wrapper">
		<form action="#">
			<div class="container1">
				<div class="logout" type="button" name="按鈕名稱" onclick="location.href='newmenu1.html'">
					<div align="left">
						<img src="../images/back.png" alt="返回icon" />
						<span style="font-size: 18px;">返回</span>
					</div>
				</div>
				<div align="center">
					<font size="20">員工資料管理</font>
				</div>

				<!-- <div class="insidebox"> -->

				<div class="ininsidebox">
                    
					<table width="50%">
						<tr>
							<th>
								<div class="sidebar_left">刪除</div>
							</th>
							<td>
								<div class="content1">員工編號</div>
							</td>
							<td>
								<div class="content2">員工姓名</div>
							</td>
							<td>
								<div class="sidebar_right">編輯</div>
							</td>
						</tr>
					</table>
				</div>

			<!-- 大括號的上、下半部分 分別用 PHP 拆開 ，這樣中間就可以純用HTML語法-->
			<?php
				if(mysqli_num_rows($result) > 0)
				{
					foreach($result as $row)
					{
                        echo "<tr>";
                        echo "<td>
                        <a href='dbdeletelist.php?pk=".$datas[$i]['adm_pk']."'><button class='btn btn-success'>刪除</button></a>
                        </td>";
        
                        echo "<td>".$row['staff_id']."</td>";
                        echo "<td>".$row['staff_name']."</td>";
                        echo "</tr>";
			
				  }
				}
			?>

        </div>
		</form>
	</div>
</body>

</html>