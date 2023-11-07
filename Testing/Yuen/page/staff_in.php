<?php
    include "../bin/conn.php";

    //擷取準備要新增時使用的各欄位資料
    $identity = $_GET["identity"];
    $store_id = $_GET["store_id"];

    $staff_name = $_POST['staff_name'];
    $staff_birth = $_POST['staff_birth'];
    $staff_gender = $_POST['staff_gender'];
    $staff_tel = $_POST['staff_tel'];
    $staff_address = $_POST['staff_address'];
    $em_name = $_POST['em_name'];
    $em_tel = $_POST['em_tel'];
    $relation = $_POST['relation'];
    $due_date = $_POST['due_date'];
    $staff_psw = $_POST['staff_psw'];

    $sql = "INSERT INTO store_staff (boss_identity, store_id, staff_name, staff_gender, staff_birth, 
        staff_tel, staff_address, em_name, em_tel, relation, due_date, staff_psw) 
        values (
        '$identity', '$store', '$staff_name', '$staff_gender', '$staff_birth', '$staff_tel',
        '$staff_address', '$em_name', '$em_tel', '$relation', '$due_date', '$staff_psw')";
    // mysqli_query($con, $sql);
    $result = mysqli_query($con,$sql);
    // 如果有異動到資料庫數量(更新資料庫)
    if (mysqli_affected_rows($con)>0) {
    // 如果有一筆以上代表有更新
    // mysqli_insert_id可以抓到第一筆的id
    $new_id= mysqli_insert_id ($con);
    echo "<script>alert('新增成功!');location.href='employee.php';</script>"; 
    }
else {
    echo "<script>alert('新增失敗!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>"; 
}
 mysqli_close($con); 

?>

<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Menu customization</title>
	<link href="../js/menu.css" rel="stylesheet">

</head>


<body>
	<div class="cabinet">
		<label for="type_id">
		</label>
	</div><br>
	<div class="button-container">
		<button class="Nextstepbutton" onclick="location.href='menu_2.php'">
			<span>繼續</span>
		</button>
	</div>
</body>
<script>
    function goRegister() {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_identity = urlParams.get('boss_identity');
        var boss_name = urlParams.get('boss_name');

        location.href='store_register_copy.php?boss_identity=' + boss_identity + '&boss_name=' + boss_name + "&back=1";
    }
    $(document).ready(function () {
        var urlParams = new URLSearchParams(window.location.search);
        var boss_name = urlParams.get('boss_name');       
        document.getElementById("boss_name").innerHTML = boss_name;
    });
</script>
</html>