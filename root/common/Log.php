<?php

    /**
     * 2013/10/31
     * 仲矢憲司
     * ログイン時及びテーブル操作を行ったとき
     * ログ出力をするクラス
     */

    require_once (dirname(__FILE__)."/PostChar.php");
    
    class Log{
    
       private $acc_Log = "../../acc_log.txt";
       private $tbl_Log = "../../tbl_log.txt";
       
       var $user_Id = "";
       var $passward = "";
       var $success_flg = "";

       /**
        * ログイン失敗した時のログを
        * "acc_log.txt"に書き込み
        * @param type $id
        * @param type $pass
        * @param type $ip_Addres
        * @return boolean
        */
       function accFailLog($id,$pass,$ip_Addres){
           
           $this->success_flg = "Failed";
           
           try{
                //ファイルオープン
                $fp = fopen($this->acc_Log, "a");

                //日付取得
                $date_Time = date( "Y/m/d (D) H:i:s", time() );

                if($id === false){
                    $this->user_Id = "__noInput"; 
                }else{
                    $this->user_Id = $id;
                }
                
                if(!$pass || $pass === ""){
                   $this->passward = "__noInput";
                }else{
                   $this->passward = $pass;
                }
                fwrite($fp, "[--".$date_Time."--]  ".$this->success_flg."  AccessUser-> [".$this->user_Id
                        ."]  Passward-> [".$this->passward."]  IPAddres-> [".$ip_Addres."]\r\n");
                fclose($fp);
                return true;
                
           }catch(Exception $e){
               return false;
           }
       }

       /**
        * ログイン成功した時のログを
        * "acc_log.txt"に書き込み
        * @param type $id
        * @param type $pass
        * @param type $ip_Addres
        * @return boolean
        */
       function accSuccessLog($id,$pass,$ip_Addres){
           $this->success_flg = "Success";
           
           try{
                //ファイルオープン
                $fp = fopen($this->acc_Log, "a");

                //日付取得
                $date_Time = date( "Y/m/d (D) H:i:s", time() );

                $this->user_Id = $id;
                $this->passward = $pass;
                
                fwrite($fp, "[--".$date_Time."--]  ".$this->success_flg." AccessUser-> [".$this->user_Id
                        ."]  Passward-> [".$this->passward."]  IPAddres-> [".$ip_Addres."]\r\n");
                fclose($fp);
                return true;
                
           }catch(Exception $e){
               return false;
           }
       }

    }
?>


