<?php
    //session_start();
    include "../bin/conn.php";

    //todo, 這是假的資料
    //預設的資料來源，是從登入而來。登入、選擇店家後，就會把以下這兩個資訊，放進SESSION裡，保留在Server端
    //讓同一個人的接續連線，可以直接拿來用
    if (!isset($_SESSION["identity"])) {
        $_SESSION["identity"] = "A123456789";
    }
    if (!isset($_SESSION["store_id"])) {
        $_SESSION["store_id"] = "yeah";
    }

    $id = $_SESSION['boss_identity'];
    $storeid = $_SESSION["store_id"];

    $sql = "SELECT * FROM store_info where boss_identity = '$id' and store_id = '$storeid'";
    $result = mysqli_query($con, $sql);
    $row_result = mysqli_fetch_assoc($result);

    //todo, 這個要從資料庫撈出來
    $table_count = $row_result['table_count'];
?>

<html>

<head>
    <title>開桌點餐</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="../js/bootstrap.min.css" >
    <link rel="stylesheet" href="../js/bootstrap.min.4.6.2.css">
    <link rel="stylesheet" href="../js/selectDesk.css" >
    
    <script src="../js/jquery-3.6.4.min.js"></script>
    <script>
    //每個桌號按下時，onClick事件的執行內容
    function change(id) {
<?php
    //todo, id應該要採用實際的tableId，不是'A'...
    //如果要做到「已經開桌的要保持ping」就不能這麼簡單的全部換成白色
    for ($x=1; $x<=$table_count; $x++) {
        $sql = 
            "SELECT is_open from store_table (
            where boss_identity = '$id' and store_id = '$storeid' and table_number = $i";
        
        $result = mysqli_query($con, $sql);
        $row_result = mysqli_fetch_assoc($result);
        $is_open = $row_result['is_open'];

        if ($is_open == 'Y') {
            echo "
            document.getElementById('A$x').style.backgroundColor = 'black';";
        }
        
        else{
            echo "
            document.getElementById('A$x').style.backgroundColor = 'white';";
        }

        
    }
    echo "\n";
?>
            //將選擇的桌號，改顏色
            document.getElementById('A'+id).style.backgroundColor = '#f3cc5f';
            //將選擇的桌號，記錄在隱藏欄位
            document.getElementById('desk_selected').value = 'A'+id;
        }

        //開桌
        function newOrder() {
            //以系統時間當作訂單編號內容：yyyyMMddHHmmss
            var date = new Date();
            var order_no = 
                date.getFullYear().toString() +
                ('0' + (date.getMonth() + 1)).slice(-2) +
                ('0' + date.getDate()).slice(-2) +
                ('0' + date.getHours()).slice(-2) +
                ('0' + date.getMinutes()).slice(-2) +
                ('0' + date.getSeconds()).slice(-2);
            //隱藏欄位：身份證號、店代號
            var identity= $('input[name=identity]').val();
            var store_id= $('input[name=store_id]').val();
            //畫面上選擇的桌號、輸入的人數
            var desk= $('input[name=desk_selected]').val();
            var persons = $('input[name=persons]').val();    
            var urlParams = new URLSearchParams(window.location.search);
            var emp = urlParams.get('staff_id');        
            //var emp = $_SESSION["staff_id"];

            //若桌號或者人數沒有值，則顯示錯誤訊息後離開
            if (!desk) {
                alert('請選擇桌號');
                exit;
            }
            if (!persons) {
                alert('請輸入用餐人數');
                exit;
            }
            //若沒有選擇員工，則顯示錯誤訊息後離開
            //if (!emp || emp == 'none') {
                //alert('請選擇開桌的員工');
                //exit;
            //}
            
            //跳到「模擬」列印qrCode的頁面，並且帶入必要的參數資訊
            var newUrl = "showqrcode.php?identity="+identity+
                "&store_id="+store_id+
                "&desk="+desk+
                "&order_no="+order_no+
                "&persons="+persons+
                "&emp="+emp;
             //alert(newUrl);
            window.location.replace(newUrl);
        }

        function print_value() {
                document.getElementById("result").innerHTML = document.getElementById("staff_id").value
        }

    </script>
</head>

<body>
    <form>
    <div class="caption">
            <div class="col-md-5">	
                <h1 class="myTitle"><img src="../images/choose.png" /></h1>
                <span>選擇桌號</span>
            </div>
    </div>      
    <div class="logout" type="button" name="按鈕名稱" onclick="location.href='../page/staff_management.html'">
            <div align="left">
                <img src="../images/back.png" alt="返回icon" />
                <span style="font-size: 14px;">返回</span>
            </div>
    </div>      
<?php
    //PHP是在後端(Server)運作的程式，Html與JavaScript則是在前端(Client)運作的程式
    //在Server端，透過PHP將身份證與店代號，保留於隱藏欄位中，以傳到前端，做後續的應用
    $boss = $_SESSION["identity"];
    $store = $_SESSION["store_id"];
?>
        <!--隱藏欄位-->
        <input type='hidden' id='identity' name='identity' value=<?php echo "'$boss'"; ?>>
        <input type='hidden' id='store_id' name='store_id' value=<?php echo "'$store'"; ?>>
        <input type='hidden' id='desk_count' name='desk_count' value='10'>
        <input type='hidden' id='desk_selected' name='desk_selected' value=''>
        <input type='hidden' id='order_no' name='order_no' value=''>
        
        <div class='container'>

<?php
    echo "
            <div class='row'>\n";
    for ($x=1; $x <= $table_count; $x++) {
    echo "
            <div class='col-md-2'>
                <div class='card' >
                    <div id='A$x' style='height: 80;' onClick=change('$x')>A$x</div>
                </div>
            </div>
            ";
    }
    echo "
            </div>\n";
?>            
            <div class='row'>
                <!-- <div class='col-md-12'>
                    <div style='height:10;'></div>
                </div> -->
                <div class="col-md-12">
                    <div class='section'>
                        <label for="store_name">人數</label>
                        <input type="text"  name="persons" id="persons" placeholder="用餐人數" required>
                        <!-- <label for="store_name">員工</label>
                            <select name="staff_id" id="staff_id" onchange="print_value();"> 
                                <option value='none'>(空)</option>
<?php
        //查詢店舖的員工資料
        $sql = "
            select * from store_staff
            where boss_identity = '$boss' and store_id = '$store'";
        $emp_data = mysqli_query($con, $sql);
        //逐筆資料處理，放進下拉選單
        while ($emp = mysqli_fetch_array($emp_data, MYSQLI_ASSOC)) {
            $staff_id = $emp["staff_id"];
            $staff_name = $emp["staff_name"];
            echo "  <option value='$staff_id'>$staff_name</option>";
        }
?>                             
                            </select>-->
                        <input name="createOrder" type="button" value="開桌" onclick=newOrder() ></input>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</body>


</html>