<html>

    <head>
        <title>桌數設定</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../js/bootstrap.min.css" >
        <link rel="stylesheet" href="../js/table_setting.css" >
    </head>
    
    <body>
        <div class="caption">
            <div class="col-md-5">	
                <h1 class="myTitle"><img src="../images/dinner-table.png" /></h1>
                <span>修改桌數</span>
            </div>
        </div>
        <div class="logout" type="button" name="按鈕名稱" onclick="location.href='../page/boss_management.html'">
            <div align="left">
                <img src="../images/back.png" alt="返回icon" />
                <span style="font-size: 14px;">返回</span>
            </div>
        </div>

        <form>                   
            <div class='container'>
    
                <div class='row'>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A1' style='height: 80;'>A1</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A2' style='height: 80;'>A2</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A3' style='height: 80;'>A3</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A4' style='height: 80;'>A4</div>
                    </div>
                </div>
                
                <div class="w-100"></div>

                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A5' style='height: 80;'>A5</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A6' style='height: 80;'>A6</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A7' style='height: 80;'>A7</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A8' style='height: 80;'>A8</div>
                    </div>
                </div>
                
                <div class="w-100"></div>

                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A9' style='height: 80;'>A9</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A10' style='height: 80;'>A10</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A11' style='height: 80;'>A11</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A12' style='height: 80;'>A12</div>
                    </div>
                </div>
                
                <div class="w-100"></div>

                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A13' style='height: 80;'>A13</div>
                    </div>
                </div>

                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A14' style='height: 80;'>A14</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A15' style='height: 80;'>A15</div>
                    </div>
                </div>
                
                <div class='col-md-2'>
                    <div class='card' >
                        <div id='A16' style='height: 80;'>A16</div>
                    </div>
                </div>
                
                <div class="w-100"></div>
                </div>
            </div> 

            <form id="tableForm">
                <label for="table_count">桌數：<input type="number" id="table_count" name="table_count" min="1" required></label>
                <br>
                <label for="table_name">桌名：<input type="text" id="table_name" name="table_name" required></label>
                <br>
                <input type="submit" value="儲存"></input>
            </form>
        </form>
    </body>
</html>