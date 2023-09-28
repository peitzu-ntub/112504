<html>

<?php 
  include "../bin/conn.php";
  $identity = $_GET['identity'];
  $store_id = $_GET['store_id'];
  $order_no = $_GET["order_no"];
  $del = $_GET["del"];
  $inc = $_GET["inc"];
  $dec = $_GET["dec"];
  $qty = $_GET["qty"];

  //刪除指定的商品
  if (isset($del)) {
    $sql = "delete from store_cart
            where boss_identity='$identity'
            and store_id='$store_id'
            and order_no='$order_no'
            and meal_id='$del'";
    mysqli_query($con, $sql);
  }

  //增加指定商品的數量
  if (isset($inc)) {
    $sql = "update store_cart
            set meal_qty=meal_qty+1
            where boss_identity='$identity'
            and store_id='$store_id'
            and order_no='$order_no'
            and meal_id='$inc'";
    mysqli_query($con, $sql);
  }

  //減少指定商品的數量。若數量已經是1，就不能再減少
  if (isset($dec) && $qty > 1) {
    $sql = "update store_cart
            set meal_qty=meal_qty-1
            where boss_identity='$identity'
            and store_id='$store_id'
            and order_no='$order_no'
            and meal_id='$dec'";
    mysqli_query($con, $sql);
  }

  //查詢購物車內容
  $sql = "select c.*, f.meal_name, f.meal_price
          from store_cart c
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
  <title>購物車</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="../js/bootstrap.min.4.6.2.css">
  <link rel="stylesheet" href="../js/pickfood.css">
  <link rel="stylesheet" href="../js/cart_style.css">

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
          <div align="left"><img src="../images/購物車.png" />　<font size="5">購物車</font></div>
        </center>
      </div>
    </div>
    <div>
      <table width="100%">
        <tr>
          <td style="background-color:#4e7fe0" align="center" width="3%"></td>
          <td style="background-color:#4e7fe0" align="center" ><font color="black"><b>餐點</b></font></td>
          <td style="background-color:#4e7fe0" align="center" width="15%"><font color="black"><b>單價</b></font></td>
          <td style="background-color:#4e7fe0" align="center" width="35%"><font color="black"><b>數量</b></font></td>
          <td style="background-color:#4e7fe0" align="center" width="15%"><font color="black"><b>金額</b></font></td>
        </tr>
<?php
    $total=0;
    while ($food = mysqli_fetch_array($cart_data, MYSQLI_ASSOC)) {
        $meal_id = $food['meal_id'];
        $meal_name = $food['meal_name'];
        $qty = $food['meal_qty'];
        $meal_price = $food['meal_price'];
        $subTotal=$meal_price * $qty;
        $total = $total + $subTotal;      
        echo "
        <tr>
          <td align='center'>
            <a href='cart.php?identity=$identity&store_id=$store_id&order_no=$order_no&del=$meal_id'>
              <img src=../images/trash.png></img>
            </a>
          </td>
          <td>$meal_name</td>
          <td align='right'>$meal_price</td>
          <td align='right'>
            $qty            
            <button type='button'>
              <a href='cart.php?identity=$identity&store_id=$store_id&order_no=$order_no&inc=$meal_id'>
                <font color='black'>
                  +
                </font>
              </a>
            </button>
            <button type='button'>
              <a href='cart.php?identity=$identity&store_id=$store_id&order_no=$order_no&dec=$meal_id&qty=$qty'>
                <font color='black'>
                  -
                </font>
              </a>
            </button>
          </td>
          <td align='right'>$subTotal</td>
        </tr>
        ";
    }
    echo "
        <tr>
            <td style='background-color:#4e7fe0' align='right' colspan='4'><b>合計</b></td>
            <td style='background-color:#4e7fe0' align='right'><b>$total</b></td>
        </tr>
    "
?>        
      </table>
    </div><br>
<?php
    $pickUrl = "pickFood.html?identity=$identity&store_id=$store_id&order_no=$order_no";
    echo "        
    
        <a href='$pickUrl&cart=1'>
            <button type='button' style='background-color:#80a0e2' class='registbutton'>
                確定點餐
            </button></a>&emsp;
        <a href='$pickUrl'>
            <button type='button' style='background-color:#80a0e2' class='registbutton'>
                我要繼續點
            </button>
        </a>
    ";
?>      
  </div>
</body>

</html>