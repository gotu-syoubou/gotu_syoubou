<?php

    /**
     * 2013/10/30
     * 仲矢憲司
     * ログインクラス及びログイン処理
     */
   ini_set( 'error_reporting', E_ALL );

   require_once (dirname(__FILE__)."/GotuMySQL.php");
   require_once (dirname(__FILE__)."/PostChar.php");
   require_once (dirname(__FILE__)."/SessionC.php");
   require_once (dirname(__FILE__)."/Log.php");
   //session開始
   $sessionC = new SessionC();
   
   //ログ出力クラスのインスタンス作成
   $log = new Log();
   
   //USER_AGENT取得
   $ua=$_SERVER['HTTP_USER_AGENT'];

   //POSTされた値をチェック
   $post_char = new PostChar();
   $pass = $post_char->postEnglish("pass");
   $mailaddres = $post_char->postEnglish("mail");
   $id = "";
   //POSTデータに値が入力されていなかった場合
   //Login画面へリダイレクト
   if(!$mailaddres || !$pass){
      $_SESSION["SESSION_MSG"] = "IDとパスワードを入力してください";
      if(!$log->accFailLog($mailaddres,$pass,$_SERVER["REMOTE_ADDR"])){
          echo "error";
      }
      header("location:../Login_Page.php");
      die();
   }

   $login = new Login;
   
   //POSTされた値のユーザが存在するかチェック
   if($id = $login->loginCheck($mailaddres, $pass)){
       //存在する場合session変数に値をセットして
       //mainページへ移動
       if($login->selectParam($id)){
           
           if(!$log->accSuccessLog($mailaddres, $pass,$_SERVER["REMOTE_ADDR"])){
               echo "error";
           }
           //アクセス元がスマートフォンの場合
           //cookieを設定後緊急招集サイトへ移動
           if((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false)) {
               header("Location:../emergency/list.php");
               die();
           }
           header("location:../Top_Page.php");
           die();
       //存在しない場合session変数にエラーメッセージをセットして
       //Login画面へリダイレクト
       }else{
           $_SESSION["SESSION_MSG"] = "IDかパスワードが間違っています";
           
           if(!$log->accFailLog($mailaddres,$pass,$_SERVER["REMOTE_ADDR"])){
                echo "error";
           }
           header("location:../Login_Page.php");
           die();
       }
   //存在しない場合session変数にエラーメッセージをセットして
   //Login画面へリダイレクト
   }else{
       $_SESSION["SESSION_MSG"] = "そのユーザは存在しません".$id;
       
       if(!$log->accFailLog($mailaddres,$pass,$_SERVER["REMOTE_ADDR"])){
          echo "error";
       }
       header("location:../Login_Page.php");
       die();
   }
   
   /**
    * Loginに関するクラス
    * IDとPASSWARDでログインチェック
    * 成功時はsession変数にユーザーの情報と権限をセット
    */
   class Login{
       private $mysql;
       
       /**
        * コンストラクタ
        * MYSQLへ接続
        */
       function Login(){
           //DB接続
           $this->mysql = new GotuMySQL();
       }
       /**
        * IDとパスワードでチェックする
        * @param type $id
        * @param type $pass
        * @return boolean
        */
       function loginCheck($check_mailaddres,$check_pass){
           $sql = "SELECT member_id FROM tbl_login WHERE mail = '$check_mailaddres' AND pass = '$check_pass'";
           if($this->mysql->query($sql)){
               if($this->mysql->rows() != 1){
                   $this->mysql->close();
                   return false;
               }
               $rows = $this->mysql->fetch();
               $check_id = $rows["member_id"];
               $this->mysql->close();
               return $check_id;
           }else{
               $this->mysql->close();
               return false;
           }
       }
       /**
        * ログインに成功したユーザの情報をセッションに登録する
        * @param type $id
        * @return boolean
        */
       function selectParam($id){
           $sql = "SELECT tbl_member.name, tbl_member.member_id, tbl_member.group_id, mst_group.group_name,"
                   . " tbl_member.writer_flg, tbl_member.bill_flg, tbl_member.inspection_flg"
                   . " FROM tbl_member, mst_group"
                   . " WHERE tbl_member.group_id = mst_group.group_id"
                   . " AND tbl_member.member_id = '$id'"
                   . " AND out_date IS NULL";
            
           if($this->mysql->query($sql)){
              if($this->mysql->rows() != 1){
                  $this->mysql->close();
                  return false;
              }else{
                  
                  $row = $this->mysql->fetch();
                  $_SESSION["SESSION_USER_NAME"] = $row["name"];
                  $_SESSION["SESSION_ID"] = $row["member_id"];
                  $_SESSION["SESSION_GROUP_ID"] = $row["group_id"];
                  $_SESSION["SESSION_GROUP_NAME"] = $row["group_name"];
                  $_SESSION["SESSION_WRITER"] = $row["writer_flg"];
                  $_SESSION["SESSION_BILL"] = $row["bill_flg"];
                  $_SESSION["SESSION_INSPECTION"] = $row["inspection_flg"];

                  $this->mysql->close();
                  return true;
              }
           }else{
               return false;
           }
       }
      
   }
?>



