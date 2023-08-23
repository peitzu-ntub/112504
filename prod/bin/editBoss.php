<?php
    include ("conn.php");

    $data= array();

    /**
     * 判斷身分證字號
     * @param  string $id [身分證字號]
     */
    function id_card($cardid){
        $err ='';
        //先將字母數字存成陣列
        $alphabet =['A'=>'10','B'=>'11','C'=>'12','D'=>'13','E'=>'14','F'=>'15','G'=>'16','H'=>'17','I'=>'34',
                    'J'=>'18','K'=>'19','L'=>'20','M'=>'21','N'=>'22','O'=>'35','P'=>'23','Q'=>'24','R'=>'25',
                    'S'=>'26','T'=>'27','U'=>'28','V'=>'29','W'=>'32','X'=>'30','Y'=>'31','Z'=>'33'];
        //檢查字元長度
        if(strlen($cardid) !=10){//長度不對
            $err = '1';
            return false;
        }

        //驗證英文字母正確性
        $alpha = substr($cardid,0,1);//英文字母
        $alpha = strtoupper($alpha);//若輸入英文字母為小寫則轉大寫
        if(!preg_match("/[A-Za-z]/",$alpha)){
            $err = '2';
            return false;
        }else{
            //計算字母總和
            $nx = $alphabet[$alpha];
            $ns = $nx[0]+$nx[1]*9;//十位數+個位數x9
        }

        //驗證男女性別
        $gender = substr($cardid,1,1);//取性別位置
        //驗證性別
        if($gender !='1' && $gender !='2'){
            $err = '3';
            return false;
        }

        //N2x8+N3x7+N4x6+N5x5+N6x4+N7x3+N8x2+N9+N10
        if($err ==''){
            $i = 8;
            $j = 1;
            $ms =0;
            //先算 N2x8 + N3x7 + N4x6 + N5x5 + N6x4 + N7x3 + N8x2
            while($i >= 2){
                $mx = substr($cardid,$j,1);//由第j筆每次取一個數字
                $my = $mx * $i;//N*$i
                $ms = $ms + $my;//ms為加總
                $j+=1;
                $i--;
            }
            //最後再加上 N9 及 N10
            $ms = $ms + substr($cardid,8,1) + substr($cardid,9,1);
            //最後驗證除10
            $total = $ns + $ms;//上方的英文數字總和 + N2~N10總和
            if( ($total%10) !=0){
                $err = '4';
                return false;
            }
        }
        //錯誤訊息返回
        // switch($err){
        //     case '1':$msg = '字元數錯誤';break;
        //     case '2':$msg = '英文字母錯誤';break;
        //     case '3':$msg = '性別錯誤';break;
        //     case '4':$msg = '驗證失敗';break;
        //     default:$msg = '驗證通過';break;
        // }
        // \App\Library\CommonTools::writeErrorLogByMessage('身份字號：'.$cardid);
        // \App\Library\CommonTools::writeErrorLogByMessage($msg);
        return true;
    }
  
    try {
        $boss_identity = $_POST['boss_identity'];
        $boss_name = $_POST['boss_name'];
        $boss_psw = $_POST['boss_psw'];

        if (!id_card($boss_identity)){
            $data['result'] = 'NG';
            $data['message'] = "身分證號格式有誤,請重新輸入";
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