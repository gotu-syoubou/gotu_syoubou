<?php
    
    /**
     * 2013/10/30　仲矢 憲司
     * セッションの開始とセッションの有無チェック
     * 及びセッション変数の取得を行うクラス
     */
    require_once (dirname(__FILE__)."/GotuMySQL.php");
    
    class SessionC{
        
        /**
         * コンストラクタ
         * sessionのスタートを行う
         */
        function SessionC(){
            session_set_cookie_params(60*60*24*30*6);
            session_start();
        }
        
        /**
         * session変数からセッションの有無をチェック
         * セッション有ならtrueを返す
         * @return boolean
         */
        function sessionCheck(){

                if(isset($_SESSION["SESSION_ID"]) && isset($_SESSION["SESSION_GROUP_ID"])){
                    return true;
                }else{
                    return false;
                }
        }

        /**
         * ログイン時にブラウザのクッキーでログインする
         * ユーザーが退団している場合はsessionとcookieを削除する
         * @param type $id
         * @return boolean
         */
        function sessionLogin($id){
            $mysql = new GotuMySQL();
            
            $sql = "SELECT member_id,group_id,writer_flg,bill_flg,inspection_flg"
                   . " FROM tbl_member"
                   . " WHERE member_id = '$id'"
                   . " AND out_date IS NULL";
            if($mysql->query($sql)){
                if($mysql->rows() == 1){
                  $mysql->close();
                  return true;
                }else{
                    $mysql->close();
                    $this->sessionDestroy();
                    return false;
                }
            }else{
                $mysql->close();
                return false;
            }
        }
        /**
         * sessionとcookieを削除する
         */
        function sessionDestroy(){
            // セッション変数を全て解除する
            $_SESSION = array();

            // セッションを切断するにはセッションクッキーも削除する。
            // Note: セッション情報だけでなくセッションを破壊する。
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time()-60*60*24*30*6, '/');
            }

            // 最終的に、セッションを破壊する
            session_destroy();
            
        }
        /**
         * session変数の事務局長権限（SESSION_WRITER）
         * の内容を返す
         * @return type
         */
        function sessionWriter(){
            return $_SESSION["SESSION_WRITER"];
        }
        /**
         * session変数の会計長権限（SESSION_BILL）
         * の内容を返す
         * @return type
         */
        function sessionBill(){
            return $_SESSION["SESSION_BILL"];
        }
        /**
         * session変数の監査権限（SESSION_INSPECTION）
         * の内容を返す
         * @return type
         */
        function sessionInspection(){
            return $_SESSION["SESSION_INSPECTION"];
        }
        /**
         * session変数のユーザーネーム（SESSION_USER_NAME）
         * の内容を返す
         * @return type
         */
        function sessionUserName(){
            return $_SESSION["SESSION_USER_NAME"];
        }
        /**
         * session変数の団員ID（SESSION_ID）
         * の内容を返す
         * @return type
         */
        function sessionId(){
            return $_SESSION["SESSION_ID"];
        }
        /**
         * session変数の分団ID（SESSION_GROUP_ID）
         * の内容を返す
         * @return type
         */
        function sessionGroupId(){
            return $_SESSION["SESSION_GROUP_ID"];
        }
        /**
         * session変数の分団名（SESSION_GROUP_NAME）
         * の内容を返す
         * @return type
         */
        function sessionGroupName(){
            return $_SESSION["SESSION_GROUP_NAME"];
        }
        /**
         * session変数のメッセージ（SESSION_MSG）
         * の内容を返す
         * @return type
         */
        function sessionMsg(){
            return $_SESSION["SESSION_MSG"];
        }
        
        
    }
?>


