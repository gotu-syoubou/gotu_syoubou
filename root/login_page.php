<?php
    
    /**
     * 2013/10/30
     * 仲矢憲司
     * ログイン画面
     */

    ini_set( 'error_reporting', E_ALL );
    require_once (dirname(__FILE__)."/common/SessionC.php");
    require_once (dirname(__FILE__)."/common/PostChar.php");
    
    //sessionの開始
    $sessionC = new SessionC;
    
    if(isset($_SESSION["SESSION_ID"]) && $sessionC->sessionLogin($_SESSION["SESSION_ID"])){
        //USER_AGENT取得
        $ua=$_SERVER['HTTP_USER_AGENT'];
        if((strpos($ua,'iPhone')!==false)||(strpos($ua,'iPod')!==false)||(strpos($ua,'Android')!==false)) {
                header("location:/emergency/list.php");
                die();
        }elseif ($_SESSION["SESSION_ID"] == "消防署用ID") {
            //緊急招集（消防署用Page）へリダイレクト
            
        }else{
            header("location:top_page.php");
            die();
        }
    }
    
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" type="text/css" href="css/login_page.css" media="all">
        <title>江津市消防団</title>
    </head>
    <body>
       <header>江津市消防団会計システム</header>
       <br><br>
       <?php
       //ログインに失敗するとsession変数のSESSION_MSGにエラーメッセージが入って
       //返ってくるのでそれを表示
       if(isset($_SESSION["SESSION_MSG"])){
          $msg = $sessionC->sessionMsg();
          echo "<p align='center'>".$msg."</p>";
       }
       ?>
       <div id = "content">
          <div align="center">
             <form autocomplete="off" action="common/Login.php" method="post">
                <table border="1">
                   <tr>
                      <td class="title">ID</td>
                   </tr>
                   <tr>
                      <td><input type="text" size="30" name="mail" maxlength=30></td>
                   </tr>
                   <tr>
                      <td class ="title">パスワード</td> 
                   </tr>
                   <tr>
                       <td><input type="text" size="30" name="pass" maxlength="12"></td>
                   </tr> 
                   <tr align="center">
                      <td colspan="2">
                      <p>
                      <button class = "menuBtn" type = "submit" name="loginbtn" value="login_btn">
                          ログイン
                      </button>
                      </p>
                      </td>
                   </tr>
               </table>  
            </form>
         </div>
      </div>
   </body>
</html>
