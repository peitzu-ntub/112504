<?php
//從資料庫取得圖片
$conn=mysql_pconnect("localhost","root","12345678");         
    mysql_select_db("pic");
    mysql_query("SET NAMES'utf8'");
                    
    $sql=sprintf("select * from imageDB where id=1");
            
    $result=mysql_query($sql);        
    
    //顯示圖片
    if($row=mysql_fetch_assoc($result)){    
        header("Content-type: image/jpeg");     
        print $row['image'];
    }
    
    mysql_close($conn);
?>