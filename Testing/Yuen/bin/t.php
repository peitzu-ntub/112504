<?php
$user = 'root';
$pass = '12345678';
$db = '112504';

$con = mysqli_connect("localhost", $user, $pass, $db);

  $sql = "SELECT type_name FROM food_type;";
  $car_brands = mysqli_query ($con, $sql);

?>
<html>
    <head>
    <title>Dynamic Drop Down Box</title>
    </head>
    <BODY bgcolor ="yellow">
        <form id="form" name="form" method="post">
            Car Brands:
            <select Brand Name='NEW'>
            <option value="">--- Select ---</option>

        <?php

            while ($cat = mysqli_fetch_array(
                                $car_brands,MYSQLI_ASSOC)):;

                ?>
                    <option value="<?php echo $cat['type_name'];
                    ?>">
                               <?php echo $cat['type_name'];?>
                    </option>
                <?php
              endwhile;
                ?>
            </select>
            <input type="submit" name="Submit" value="Select" />
        </form>
    </body>
</html>