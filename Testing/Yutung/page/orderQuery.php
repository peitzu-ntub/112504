<html>

<?php 
  include "../bin/conn.php";
  $identity = $_GET['identity'];
  $store_id = $_GET['store_id'];
  $order_no = $_GET["order_no"];

  //查詢訂單內容
  $sql = "select f.meal_name, c.count as meal_qty, c.price as meal_price, c.subtotal
          from store_order_item c
          left join store_food f 
            on f.boss_identity = c.boss_identity 
            and f.store_id = c.store_id
            and f.meal_id = c.meal_id
          where c.boss_identity='$identity' 
          and c.store_id='$store_id' 
          and c.order_no = '$order_no'";

    try {
        $cart_data = mysqli_query($con, $sql);
        if (!$cart_data) {
            throw new Exception(mysqli_error($con));
        }
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
?>


<head>
  <title>我的訂單</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="../js/bootstrap.min.4.6.2.css">
  <link rel="stylesheet" href="../js/pickmenu.css">

  <script src="../js/jquery-3.6.4.min.js"></script>

  <style>
    td {
      border: 1px solid rgba(113, 109, 109, 0.401);
      border-collapse: collapse;
    }
  </style>

</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <center>
        <div align="left"><img src="../images/我的訂單.png" />　<font size="6">我的訂單</font></div>
        </center>
      </div>
    </div>
    <div>
      <table width="100%">
        <tr>
          <td style="background-color:#b5c4b1" align="center"><font color="black"><b>餐點</b></font></td>
          <td style="background-color:#b5c4b1" align="center" width="15%"><font color="black"><b>單價</b></font></td>
          <td style="background-color:#b5c4b1" align="center" width="15%"><font color="black"><b>數量</b></font></td>
          <td style="background-color:#b5c4b1" align="center" width="15%"><font color="black"><b>金額</b></font></td>
        </tr>
<?php
    $total=0;
    while ($food = mysqli_fetch_array($cart_data, MYSQLI_ASSOC)) {
        $meal_name = $food['meal_name'];
        $qty = $food['meal_qty'];
        $meal_price = $food['meal_price'];
        $subTotal= $food['subtotal'];
        $total = $total + $subTotal;
        echo "
        <tr>
          <td>$meal_name</td>
          <td align='right'>$meal_price</td>
          <td align='right'>$qty</td>
          <td align='right'>$subTotal</td>
        </tr>
        ";
    }
    echo "
        <tr>
            <td style='background-color:#b5c4b1' align='right' colspan='3'><b>合計</b></td>
            <td style='background-color:#b5c4b1' align='right'><b>$total</b></td>
        </tr>
    "
?>        
      </table>
    </div><br>
<?php
    $pickUrl = "pickFood.html?identity=$identity&store_id=$store_id&order_no=$order_no";
    echo "        
        <a href='$pickUrl'>
            <button type='button' style='background-color:#b5c4b1' class='registbutton'>
                回餐點選單
            </button></a>
    ";
?>  
<?php
    $pickUrl = "cusfeedback.html?identity=$identity&store_id=$store_id&order_no=$order_no";
    echo "        
        <a href='$pickUrl'>
            <button type='button' style='background-color:#b5c4b1' class='registbutton'>
                去評價
            </button></a>
    ";
?>     
  </div>
</body>

</html>