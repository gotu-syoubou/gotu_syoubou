<?php
        ini_set( 'error_reporting', E_ALL );
        require_once (dirname(__FILE__)."/CreatePDF.php");
        require_once (dirname(__FILE__)."../../common/SessionC.php");
        require_once (dirname(__FILE__)."../../common/PostChar.php");
        
        $session = new SessionC();
        
        $postChar = new PostChar();
        if($postChar->postEnglish("create") == "決算報告書作成"){
            $pdf = new CreatePDF(1);
        }else{
            $pdf = new CreatePDF(0);
        }
        $result_array = $pdf->writeStatementPDF();
        $file_name = $result_array["file_name"];
        $file_name =  mb_convert_encoding($file_name,"UTF-8","SJIS");
        
//        $file_name = "pdf/".date("Y")."_"."$g_name".".pdf";
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
    </head> 
    <body>
        <div style="margin-left: 0;margin-right: 0;width:100%;height:900px;border: 0px solid #000;">
        <object style="border: 1px solid #000;" src="<?php echo $file_name;?>" type="application/pdf" width="66%" height="1000"></object>        
        <div style="border: 1px solid #000;float: right;width: 33%;height: 1000px;">
            <?php
                if($postChar->postEnglish("create") == "決算報告書作成"){
                    if($result_array["error"] == ""){
                       echo "<p style=\"text-align: center;height: 60px;margin: 0px;font-size: 30px;position: relative;top:40%;\">この内容でよろしければ<br>確定ボタンを押してください</p>";
                    }else{
                       echo "<p style=\"text-align: center;height: 60px;margin: 0px;font-size: 30px;position: relative;top:50%;\">合計が一致していません</p>"; 
                    }
                }else{
                    echo "<form method=\"post\" action=\"CreateStatementPDF.php\" style=\"text-align: center;height: 60px;margin: 0px;font-size: 30px;position: relative;top:50%;\">";
                    echo "<input style=\"font-size: 30px;padding: 10px;\" type=\"submit\" name=\"create\" value=\"決算報告書作成\">";
                    echo "</form>";
                }
            ?>
            
        </div>
        </div>
    </body>
</html>




