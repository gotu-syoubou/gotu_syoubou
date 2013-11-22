<!DOCTYPE html>
<?php
    require_once (dirname(__FILE__)."/common/SessionC.php");

    $sessionC = new SessionC();
    //ログインに成功しているかのチェック
    //成功時はmainページへリダイレクト
    if(!$sessionC->sessionCheck()){
        header("location:Login_Page.php");
        die();
    }
    var_dump(session_get_cookie_params());

?>
<html>
<head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="top_page.css" media="all">
	<title>TOP画面</title>
</head>

<body>

<header align="center">TOP画面</header><br>
<p align="center"></p>
<br><br>
<div id="contents">
    
    <div align="center">
        <form>
            <table border="0">
            <?php

                if($sessionC->sessionWriter() == true){
                    echo "<tr><td><button class='menuBtn' type='button'>団員管理</button></td></tr>";
                }

                if($sessionC->sessionBill() == true || $sessionC->sessionInspection() == true){
                    echo "<tr><td><button class='menuBtn' type='button'>会計</button></td></tr>";
                }

            ?>

            <tr><td><button class='menuBtn' type='button'>データ閲覧</button></td></tr>
            </table>
        </form>
    </div>
</div>
</body>
</html>

