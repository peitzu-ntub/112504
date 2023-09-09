<?php
    include ("conn.php");

    $data= array();

    /**
     * 判斷身分證字號
     * @param  string $id [身分證字號]
     */
    function id_card($id){
       // 去空白&轉大寫
       $id = strtoupper(trim($id));

       // 英文字母與數值對照表
       $alphabetTable = [
           'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16,
           'H' => 17, 'I' => 34, 'J' => 18, 'K' => 19, 'L' => 20, 'M' => 21, 'N' => 22,
           'O' => 35, 'P' => 23, 'Q' => 24, 'R' => 25, 'S' => 26, 'T' => 27, 'U' => 28,
           'V' => 29, 'X' => 30, 'Y' => 31, 'Z' => 33
       ];

       // 檢查身份證字號格式
       // ps. 第二碼的例外條件ABCD，在這裡未實作，僅提供需要的人參考，實作方式是A對應10，只取個位數0去加權即可
       // 臺灣地區無戶籍國民、大陸地區人民、港澳居民：
       // 男性使用A、女性使用B
       // 外國人：
       // 男性使用C、女性使用D
       if (!preg_match("/^[A-Z]{1}[12ABCD]{1}[0-9]{8}$/", $id)){
           // ^ 是開始符號
           // $ 是結束符號
           // [] 中括號內是正則條件
           // {} 是要重複執行幾次
           return false;
       }

       // 切開字串
       $idArray = str_split($id);

       // 英文字母加權數值
       $alphabet = $alphabetTable[$idArray[0]];
       $point = substr($alphabet, 0, 1) * 1 + substr($alphabet, 1, 1) * 9;

       // 數字部分加權數值
       for ($i = 1; $i <= 8; $i++) {
           $point += $idArray[$i] * (9 - $i);
       }
       $point = $point + $idArray[9];

       return $point % 10 == 0 ? true : false;
    }
  
    try {
        $boss_identity = $_POST['boss_identity'];
        $boss_name = $_POST['boss_name'];
        $boss_psw = $_POST['boss_psw'];

        if (!id_card($boss_identity)){
            $data['result'] = 'NG';
            $data['message'] = "身分證號格式有誤,請重新輸入...";
            echo json_encode($data);
            exit();
        }


        //檢查老闆是否已經存在
        //1.產生查詢字串
        $sql = "select * from boss_info where boss_identity = '$boss_identity'";


        //2.查下去，並取得查詢結果
        $result = mysqli_query($con, $sql);
        //3.查詢結果的筆數
        $count = mysqli_num_rows($result);
        //如果筆數大於0，表示這個身分證字號已經存在
        if ($count > 0) {
            $data['result'] = 'NG';
            $data['message'] = "身分證號已經存在,請重新輸入";
            echo json_encode($data);
            exit();
        }

        //註冊老闆資料
        $sql = 
            "INSERT INTO boss_info (
                boss_identity, boss_name, boss_psw
            ) VALUES (
                '$boss_identity', '$boss_name', '$boss_psw'
            )";
        if (mysqli_query($con, $sql)) {
            $data['result'] = 'OK';
            $data['message'] = '老闆資料儲存成功';
        }else {
            $data['result'] = 'NG';
            $data['message'] = "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        echo json_encode($data);

    } catch (Exception $e) {
        $data['result'] = 'NG';
        $data['message'] = $e->getMessage();
        //回傳執行結果
        echo json_encode($data);
    }
?>