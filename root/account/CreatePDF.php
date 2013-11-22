<?php
    ini_set( 'error_reporting', E_ALL );

    // ライブラリを読み込む
    require_once('/tcpdf/config/tcpdf_config.php');
    require_once('/tcpdf/tcpdf.php');
    require_once (dirname(__FILE__)."../../common/SessionC.php");
    require_once (dirname(__FILE__)."../../common/GotuMySQL.php");
    
//    $pdf = new CreatePDF();
//        $pdf->writeStatementPDF();

class CreatePDF {
    
    var $pdf;
    var $sessionC;
    var $mysql;
    var $group_id = 0;
    var $in_total = 0;
    var $out_total = 0;
    var $culumn_name = 0;
    var $val = 0;
    var $row;
    var $css = <<<EOF
<style>
   .detail{
        font-size: 12px;
        text-align: right;        
   }
   .errorCell{
        background-color: #ff6633;
   }
   .left_table{
        float: left;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
   }
   .right_table{
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        border-left: none;
        border-right: 1px solid #000;
   }
   table.kessan{
        float: left;
        width: 230px;
        font-size: 15px;
   }
   .head-th{
        text-align: center;
        border-bottom: 1px solid #000;
        font-size:15px;
        height: 23px;
   }
   .body-th{
        text-align: left;
        vartical-align: middle;
        border-right: 1px solid #000;
        width: 130px;
        height:24px;
   }
   .body-td{
        width: 100px;
        height:24px;
        text-align: right;
        vertical-align: middle;
   }
   .foot-th{      
        border-top: 1px solid #000;
        border-right: 1px solid #000;
        width: 130px;
        height: 25px;
        text-align: center;
   }
   .foot-td{
        width: 100px;
        border-top: 1px solid #000;
        text-align: right;
   }
</style>
EOF;
    
    function __construct($cons_group_id = 0) {
        
        $this->group_id = $cons_group_id;
//        $this->group_id = 1;
        $this->mysql = new GotuMySQL;
        
        // PDF オブジェクトを作成
        /*
            PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT は /tcpdf/config/tcpdf_config.php ファイルで定義されています。
            PDF_PAGE_ORIENTATION はページの向き ( P = 縦, L = 横 )
            PDF_UNIT は単位 ( pt = point, mm = millimeter, cm = centimeter, in = inch )
            PDF_PAGE_FORMAT はページフォーマット ( デフォルトは A4 )
        */
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');
        
        // ヘッダーフッターの設定
        // デフォルトでヘッダーに余計な線が出るので削除
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetMargins(0.0,0.0,0.0);
        // 1ページ目を準備
        $this->pdf->AddPage();

        // フォントを指定 ( 小塚ゴシックPro M を指定 )
        // 日本語を使う場合は、日本語に対応しているフォントを使う
        $this->pdf->SetFont('kozgopromedium', '', 20);
    }
    
    function writeStatementPDF(){

            if($this->group_id === 0){
                return $this->defaultPDF();
            }

            $heisei = date('Y')-1988;
            $sql = "SELECT tbl_member.name, mst_group.group_name"
                        ." FROM tbl_member, mst_group"
                        ." WHERE tbl_member.group_id = mst_group.group_id"
                        ." AND tbl_member.group_id = ".$this->group_id.""
                        ." AND tbl_member.rank_id =3";
            $this->mysql->query($sql);
            $this->row = $this->mysql->fetch();
            $group_leader_name = $this->row["name"];
            $group_name = $this->row["group_name"];

            $this->pdf->setXY(25,0);

            $this->pdf->SetFont('kozgopromedium', 'U', 15);
            $this->pdf->Cell(50,10,$group_leader_name."分団長　殿",0,1,"L");
            $this->pdf->setXY(0,5);
            $this->pdf->SetFont('kozgopromedium', '', 22);
            $this->pdf->cell(210,15,$group_name."消防分団決算報告書",0,1,'C');
            $this->pdf->SetFont('kozgopromedium', '', 15);
            $this->pdf->Text(115,18,"(平成".$heisei."年4月1日～平成".($heisei+1)."年3月31日)");
            $this->pdf->Text(140,25,"平成".$heisei."年".date("m")."月".date("d")."日");
            $this->pdf->Text(135,35,"江津市消防団".$group_name."分団");

            $sql = "SELECT name"
                        ." FROM tbl_member"
                        ." WHERE group_id = $this->group_id"
                        ." AND bill_flg = true";
            $this->mysql->query($sql);
            $this->row = $this->mysql->fetch();
            $bill_name = $this->row["name"];
            $this->pdf->Text(140,42,"会計　　".$bill_name);

            $this->pdf->setXY(25,55);

            $heisei += 1988;
            $html = "<table class=\"kessan left_table\" border=\"0\" cellspacing=\"0\">"
            ."<thead>"
            ."<tr>"
            ."<th class=\"head-th\" colspan=\"2\">収入</th>"
            ."</tr>"
            ."</thead>"
            ."<tbody>";
            $sql = "SELECT itm.name, SUM( cash.val ) as val "
                    . " FROM tbl_cash AS cash, mst_itm AS itm"
                    . " WHERE cash.itm_id = itm.itm_id"
                    . " AND cash.group_id = $this->group_id"
                    . " AND cash.date > '$heisei-04-01 00:00:00'"
                    . " AND cash.date < '".($heisei+1)."-03-31 23:59:59'"
                    . " AND cash.io_flg = false"
                    . " GROUP BY cash.itm_id";
            if(!$this->mysql->query($sql)){
                $this->mysql->close();
                echo 'ERROR 1';
                die();
            }else{
                if($this->mysql->rows() == 0){
                    echo 'ERROR3'.$this->mysql->rows().$heisei;
                    $this->mysql->close();
                    
                    die();
                }
            }
            $this->row = $this->mysql->fetch();
            $this->culumn_names = $this->row["name"];
            $this->val = $this->row["val"];
            $this->in_total = 0;
            $this->in_total += $this->val;
            $html .= "<tr>"
                ."<th class=\"body-th\">".$this->culumn_names."</th>"
                ."<td class=\"body-td\">".number_format($this->val)."</td>"
                ."</tr>"
                ."<tr>";
            $culumn_array = array();
            $val_array = array();
            $detail_total = 0;
            for($i=0;$i<4;$i++){
                $row = $this->mysql->fetch();
                $culumn_array[$i] = $row["name"];
                $val_array[$i] = $row["val"];
                $detail_total += $val_array[$i];
            }
            $this->in_total += $detail_total;
            $html .= "<th class=\"body-th\">市より年報酬</th>"
                ."<td class=\"body-td\">".number_format($detail_total)."</td>"
                ."</tr>";
            $html .= "<tr>"
                ."<th class=\"body-th detail\">".$culumn_array[0]."(".number_format($val_array[0]).")</th>"
                ."<td class=\"body-td\"></td>"
                ."</tr>";
            $html .= "<tr>"
                ."<th class=\"body-th detail\">".$culumn_array[1]."(".number_format($val_array[1]).")</th>"
                ."<td class=\"body-td\"></td>"
                ."</tr>";    
            $html .= "<tr>"
                ."<th class=\"body-th detail\">".$culumn_array[2]."(".number_format($val_array[2]).")</th>"
                ."<td class=\"body-td\"></td>"
                ."</tr>";
            $html .= "<tr>"
                ."<th class=\"body-th detail\">".$culumn_array[3]."(".number_format($val_array[3]).")</th>"
                ."<td class=\"body-td\"></td>"
                ."</tr>"
                ."<tr>"
                ."<th class=\"body-th\">　</th>"
                ."<td class=\"body-td\"></td>"
                ."</tr>"
                ."<tr>"
                ."<th class=\"body-th\">　</th>"
                ."<td class=\"body-td\"></td>"
                ."</tr>";
            $count = 8;
            while($this->row = $this->mysql->fetch()){
                $this->culumn_name = $this->row["name"];
                $this->val = number_format($this->row["val"]);
                $this->in_total += $this->row["val"];
                $html .= "<tr>"
                        . "<th class=\"body-th\">".$this->culumn_name."</th>"
                        . "<td class=\"body-td\">".$this->val."</td>"
                        . "</tr>";
                $count++;
            }      
            while($count<20){
                $html .= "<tr>"
                        . "<th class=\"body-th\">　</th>"
                        . "<td class=\"body-td\"></td>"
                        . "</tr>";
                $count++;
            }
            $total = $this->in_total;
            $html .= "</tbody>"
                    . "<tfoot>"
                    . "<tr>"
                    . "<th class=\"foot-th\">計</th>"
                    . "<td class=\"foot-td\">".number_format($total)."</td>"
                    . "</tr>"
                    . "</tfoot>"
                    . "</table>";

            $this->pdf->writeHTML($this->css.$html,true,0,false,false,'I');

            $sql = "SELECT itm.itm_id,itm.name, SUM( cash.val ) as val "
                    . " FROM tbl_cash AS cash, mst_itm AS itm"
                    . " WHERE cash.itm_id = itm.itm_id"
                    . " AND cash.group_id = $this->group_id"
                    . " AND cash.date > '".$heisei."-04-01 00:00:00'"
                    . " AND cash.date < '".($heisei+1)."-03-31 23:59:59'"
                    . " AND cash.io_flg = true"
                    . " GROUP BY cash.itm_id";

            if(!$this->mysql->query($sql)){
                $this->mysql->close();
                echo 'ERROR 2';
                die();
            }else{
                if($this->mysql->rows() == 0){
                    $this->mysql->close();
                    echo 'ERROR4';
                    die();
                }
            }

            $html = "<table class=\"kessan right_table\" border=\"0\" cellspacing=\"0\">"
                        ."<thead>"
                        ."<tr>"
                        ."<th class=\"head-th\" colspan=\"2\">支出</th>"
                        ."</tr>"
                        ."</thead>"
                        ."<tbody>";
            $count = 0;
            $cash_vals = $this->in_total;
            $credit_vals = 0;
            while($this->row = $this->mysql->fetch()){
                    $this->culumn_names = $this->row["name"];
                    $this->val = $this->row["val"];
                    if($this->row["itm_id"] == 28){
                        $credit_vals = $this->val - $this->out_total;
                        $this->out_total += $credit_vals;
                        $count++;
                        continue;
                    }
                    $html .= "<tr>"
                          . "<th class=\"body-th\">".$this->culumn_names."</th>"
                          . "<td class=\"body-td\">".number_format($this->val)."</td>"
                          . "</tr>";         
                    $this->out_total += $this->val;
                    $count++;
            }
            $cash_vals -= $this->out_total;
            $this->out_total += $cash_vals;
            $html .= "<tr>"
                  . "<th class=\"body-th\">通帳繰越残高</th>"
                  . "<td class=\"body-td\">".number_format($cash_vals)."</td>"
                  . "</tr>"
                    . "<tr>"
                    . "<th class=\"body-th\">現金繰越残高</th>"
                    . "<td class=\"body-td\">".number_format($credit_vals)."</td>"
                    . "</tr>";
            $count += 2;
            while($count<21){
                    $html .= "<tr>"
                                . "<th class=\"body-th\"> </th>"
                                . "<td class=\"body-td\"> </td>"
                                . "</tr>";
                    $count++;
            }

            $total_error = "";
            if($this->in_total != $this->out_total){
                $total_error = "errorCell";
            }
            $html .= "</tbody>"
                        ."<tfoot>"
                            ."<tr>"
                                ."<th class=\"foot-th\" valign=\"middle\">計</th>"
                                ."<td class=\"foot-td $total_error\">".number_format($this->out_total)."</td>"
                            ."</tr>"
                        ."</tfoot>"
                    ."</table>";
            $this->pdf->setXY(106,55);
            $this->pdf->writeHTML($this->css.$html,true,0,false,false,'I');
            $this->pdf->setXY(25,255);
            $this->pdf->setLineWidth(0.2);
            $this->pdf->Cell(65,10,"次年度繰越金","TLR",1,"C",0);
            $y = $this->pdf->getY();
            $this->pdf->setXY(25,$y);
            $nextcash = number_format($cash_vals+$credit_vals);
            $this->pdf->SetFont('kozgopromedium', '', 20);
            $this->pdf->Cell(65,10,"$nextcash",'LRB',1,"C",0);
            $this->pdf->SetFont('kozgopromedium', '', 15);
            $this->pdf->Text(120,250,"平成".($heisei-1988)."年".date("m")."月".date("d")."日  ".$group_name."分団");

            $sql = "SELECT name"
                        ." FROM tbl_member"
                        ." WHERE group_id = $this->group_id"
                        ." AND inspection_flg = true";
            $this->mysql->query($sql);
            if($this->mysql->rows() == 2){
                $this->row = $this->mysql->fetch();
                $bill_name = $this->row["name"];
                $this->pdf->Text(130,260,"会計監査　".$bill_name);

                $this->row = $this->mysql->fetch();
                $bill_name = $this->row["name"];
                $this->pdf->Text(130,270,"会計監査　".$bill_name);

            }elseif($this->mysql->rows() == 1){
                $this->row = $this->mysql->fetch();
                $bill_name = $this->row["name"];
                $this->pdf->Text(130,260,"会計監査　".$bill_name);
            }                          

            ob_end_clean();
            // PDF を出力 ( I = ブラウザ出力, D = ダウンロード, F = ローカルファイルとして保存, S = 文字列として出力 )
            $file_name = mb_convert_encoding("pdf/".$heisei."_".$group_name.".pdf","SJIS","UTF-8");
            if(file_exists($file_name)){
                unlink(dirname(__FILE__)."/".$file_name);
            }
            $this->pdf->Output($file_name,"F");
            
            return array('file_name' => $file_name , 'error' => $total_error);
        }
        
        function defaultPDF(){


            $this->pdf->setXY(25,0);
            $this->pdf->SetFont('kozgopromedium', 'U', 15);
            $this->pdf->Cell(50,10,"○○○○分団長　殿",0,1,"L");
            $this->pdf->setXY(0,5);
            $this->pdf->SetFont('kozgopromedium', '', 25);
            $this->pdf->cell(210,15,"○○○消防分団決算報告書",0,1,'C');
            $this->pdf->SetFont('kozgopromedium', '', 15);
            $this->pdf->Text(110,18,"(平成○○年4月1日～平成○○年3月31日)");
            $this->pdf->Text(135,25,"平成○○年○○月○○日");
            $this->pdf->Text(135,33,"江津市消防団○○○分団");

            $this->pdf->Text(140,40,"会計　　○○○○");

            $this->pdf->setXY(25,55);

            $html = "<table class=\"kessan left_table\" border=\"0\" cellspacing=\"0\">"
            ."<thead>"
            ."<tr>"
            ."<th class=\"head-th\" colspan=\"2\">収入</th>"
            ."</tr>"
            ."</thead>"
            ."<tbody>";

            $count = 0;
    
            while($count<20){
                $html .= "<tr>"
                        . "<th class=\"body-th\">　</th>"
                        . "<td class=\"body-td\"></td>"
                        . "</tr>";
                $count++;
            }
            $html .= "</tbody>"
                    . "<tfoot>"
                    . "<tr>"
                    . "<th class=\"foot-th\">計</th>"
                    . "<td class=\"foot-td\"></td>"
                    . "</tr>"
                    . "</tfoot>"
                    . "</table>";

            $this->pdf->writeHTML($this->css.$html,true,0,false,false,'I');


            $html = "<table class=\"kessan right_table\" border=\"0\" cellspacing=\"0\">"
                        ."<thead>"
                        ."<tr>"
                        ."<th class=\"head-th\" colspan=\"2\">支出</th>"
                        ."</tr>"
                        ."</thead>"
                        ."<tbody>";
            $count = 0;

            while($count<20){

                    $html .= "<tr>"
                          . "<th class=\"body-th\"></th>"
                          . "<td class=\"body-td\"></td>"
                          . "</tr>";         
                    $count++;
            }

            $html .= "</tbody>"
                        ."<tfoot>"
                            ."<tr>"
                                ."<th class=\"foot-th\" valign=\"middle\">計</th>"
                                ."<td class=\"foot-td\"></td>"
                            ."</tr>"
                        ."</tfoot>"
                    ."</table>";
            $this->pdf->setXY(106,55);
            $this->pdf->writeHTML($this->css.$html,true,0,false,false,'I');
            $this->pdf->setXY(25,255);
            $this->pdf->setLineWidth(0.5);
            $this->pdf->Cell(65,10,"次年度繰越金",'L,T,R',1,"C",0);
            $y = $this->pdf->getY();
            $this->pdf->setXY(25,$y);
            $this->pdf->SetFont('kozgopromedium', '', 20);
            $this->pdf->Cell(65,10,"",'LRB',1,"C",0);
            $this->pdf->SetFont('kozgopromedium', '', 15);
            $this->pdf->Text(120,250,"平成○○年○○月○○日  ○○○分団");

            $this->pdf->Text(140,260,"会計監査　○○○○");
            $this->pdf->Text(140,270,"会計監査　○○○○");
            
            ob_end_clean();
            /**
             * ファイルの存在チェック
             */
            if(file_exists(dirname(__FILE__)."/pdf/default.pdf")){
                unlink(dirname(__FILE__)."/pdf/default.pdf");
            }
            $this->pdf->Output("pdf/default.pdf", "F");
            return array('file_name' => "pdf/default.pdf" , 'error' => "");
        }
}
?>