<?php

$server = "localhost";
$user_name = "root";
$passwd = "sys505";
$db_name = "gotu_syoubou";
//mysqliに接続
$mysqli = new mysqli($server, $user_name, $passwd, $db_name);


//火災・風水害継続中案件表示SQL
$query = "SELECT *
		FROM mst_case_type,tbl_case
		WHERE mst_case_type.case_type_id = tbl_case.case_type_id AND mst_case_type.case_type_id BETWEEN 0 AND 1
                      AND tbl_case.end_date IS NULL
		GROUP BY mst_case_type.case_type_id,mst_case_type.case_type_name,tbl_case.outbreak_place,tbl_case.case_id,tbl_case.reg_date,tbl_case.end_date
                ORDER BY tbl_case.reg_date DESC";

$emergency = mysqli_query($mysqli, $query) or die('Error querying database.');

//警戒継続中案件表示SQL
$query2 = "SELECT *
		FROM mst_case_type,tbl_case
		WHERE mst_case_type.case_type_id = tbl_case.case_type_id AND mst_case_type.case_type_id = 2
                     AND tbl_case.end_date IS NULL
		GROUP BY mst_case_type.case_type_id,mst_case_type.case_type_name,tbl_case.outbreak_place,tbl_case.case_id,tbl_case.reg_date,tbl_case.end_date
                ORDER BY tbl_case.reg_date DESC";

$warning = mysqli_query($mysqli, $query2) or die('Error querying database.');


//通常招集継続中案件表示SQL
$query3 = "SELECT *
		FROM mst_case_type,tbl_case
		WHERE mst_case_type.case_type_id = tbl_case.case_type_id AND mst_case_type.case_type_id BETWEEN 3 AND 4
                     AND tbl_case.end_date IS NULL
		GROUP BY mst_case_type.case_type_id,mst_case_type.case_type_name,tbl_case.outbreak_place,tbl_case.case_id,tbl_case.reg_date,tbl_case.end_date
                ORDER BY tbl_case.reg_date DESC";

$normal = mysqli_query($mysqli, $query3) or die('Error querying database.');


//火災・風水害終了案件表示SQL
$query4 = "SELECT *
		FROM mst_case_type,tbl_case
		WHERE mst_case_type.case_type_id = tbl_case.case_type_id AND mst_case_type.case_type_id BETWEEN 0 AND 1
                      AND tbl_case.end_date IS NOT NULL AND TO_DAYS(NOW()) - TO_DAYS(tbl_case.end_date) <= 1
		GROUP BY mst_case_type.case_type_id,mst_case_type.case_type_name,tbl_case.outbreak_place,tbl_case.case_id,tbl_case.reg_date,tbl_case.end_date
                ORDER BY tbl_case.reg_date DESC";

$emergency_end = mysqli_query($mysqli, $query4) or die('Error querying database.');

//警戒終了案件表示SQL
$query5 = "SELECT *
		FROM mst_case_type,tbl_case
		WHERE mst_case_type.case_type_id = tbl_case.case_type_id AND mst_case_type.case_type_id = 2
                     AND tbl_case.end_date IS NOT NULL AND TO_DAYS(NOW()) - TO_DAYS(tbl_case.end_date) <= 1
		GROUP BY mst_case_type.case_type_id,mst_case_type.case_type_name,tbl_case.outbreak_place,tbl_case.case_id,tbl_case.reg_date,tbl_case.end_date
                ORDER BY tbl_case.reg_date DESC";

$warning_end = mysqli_query($mysqli, $query5) or die('Error querying database.');

//通常招集終了案件表示SQL
$query6 = "SELECT *
		FROM mst_case_type,tbl_case
		WHERE mst_case_type.case_type_id = tbl_case.case_type_id AND mst_case_type.case_type_id BETWEEN 3 AND 4
                     AND tbl_case.end_date IS NOT NULL  AND TO_DAYS(NOW()) - TO_DAYS(tbl_case.end_date) <= 1
		GROUP BY mst_case_type.case_type_id,mst_case_type.case_type_name,tbl_case.outbreak_place,tbl_case.case_id,tbl_case.reg_date,tbl_case.end_date
                ORDER BY tbl_case.reg_date DESC";

$normal_end = mysqli_query($mysqli, $query6) or die('Error querying database.');


$query7 ="SELECT *
            FROM mst_case_type,tbl_case 
           WHERE  mst_case_type.case_type_id = tbl_case.case_type_id 
           AND tbl_case.end_date IS NULL AND tbl_case.case_type_id BETWEEN 0 AND 1";

$emergency_count = mysqli_query($mysqli, $query7) or die('Error querying database.');

$query8 ="SELECT *
            FROM mst_case_type,tbl_case 
           WHERE  mst_case_type.case_type_id = tbl_case.case_type_id 
           AND tbl_case.end_date IS NULL AND tbl_case.case_type_id =2";

$warning_count = mysqli_query($mysqli, $query8) or die('Error querying database.');
 
$query9 ="SELECT *
            FROM mst_case_type,tbl_case 
           WHERE  mst_case_type.case_type_id = tbl_case.case_type_id 
           AND tbl_case.end_date IS NULL AND tbl_case.case_type_id BETWEEN 3 AND 4";

$normal_count = mysqli_query($mysqli, $query9) or die('Error querying database.');
 
$query10 ="SELECT *
            FROM mst_case_type,tbl_case 
           WHERE  mst_case_type.case_type_id = tbl_case.case_type_id  AND TO_DAYS(NOW()) - TO_DAYS(tbl_case.end_date) <= 1
           AND tbl_case.end_date IS NOT NULL AND tbl_case.case_type_id BETWEEN 0 AND 1";

$emergency_end_count = mysqli_query($mysqli, $query10) or die('Error querying database.');
 
$query11 ="SELECT *
            FROM mst_case_type,tbl_case 
           WHERE  mst_case_type.case_type_id = tbl_case.case_type_id AND TO_DAYS(NOW()) - TO_DAYS(tbl_case.end_date) <= 1
           AND tbl_case.end_date IS NOT NULL AND tbl_case.case_type_id BETWEEN 2 AND 4";

$normal_end_count = mysqli_query($mysqli, $query11) or die('Error querying database.');


 


?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta charset="UTF-8" />
        <title>list</title>
        <link rel="stylesheet" href="list.css" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <h1>案件一覧</h1>
        <h2>緊急招集</h2><br>
        <form action="" method="post">
            
            
            <?php ($row2 = mysqli_num_rows($emergency_count)) ?>
            
            
             <h3>継続中案件 <?php echo $row2 ?>件</h3>
            <?php while ($row = mysqli_fetch_array($emergency)): ?>
            <a href="detail.php?id=<?php echo $row['case_id'] ?>">
                <?php echo "<font class='emergency'>" . $row['case_type_name'] . "</font>" ?><br>
                【<?php echo date("Y年m月d日 H時i分",strtotime($row['reg_date'])) ?>】<br>
                   <?php echo $row['outbreak_place'] ?><br>
            </a>
            <br> 
            <hr style="border-top: 2px dotted #ff9d9d;width: 100%;">
            <?php endwhile; ?>


               <?php ($row2 = mysqli_num_rows($emergency_end_count)) ?>
            <h3><?php 
                            if($row2 == 0){
                                echo"終了案件はありません";
                            }  else {
                               echo"終了案件".$row2."件" ;
                             }?>
            </h3>
                            
            <?php while ($row = mysqli_fetch_array($emergency_end)): ?>
            <a href="detail.php?id=<?php echo $row['case_id'] ?>">
                <?php echo "<font class='emergency'>" . $row['case_type_name'] . "</font>" ?><br>
                【<?php echo date("Y年m月d日 H時i分",strtotime($row['reg_date'])) ?>】<br>↓<br> 【<?php echo date("Y年m月d日 H時i分",strtotime($row['end_date'])) ?>】<br>
                   <?php echo $row['outbreak_place'] ?><br>
            </a>
            <br> 
            <hr style="border-top: 2px dotted #ff9d9d;width: 100%;">
            <?php endwhile; ?>

        </form>
        
    <hr style="border-top:20px inset #c0c0c0;width: 100%;height:10;">
        <h2>通常招集</h2><br>
        
        <form action="" method="post">
            
             <?php ($row2 = mysqli_num_rows($warning_count)) ?>
           <h3>警戒案件<?php echo $row2 ?>件</h3>
           <?php while ($row = mysqli_fetch_array($warning)): ?>
               <a href="detail.php?id=<?php echo $row['case_id'] ?>"> 
                   <?php echo "<font class='warning'>" . $row['case_type_name'] . "</font>" ?><br>
                    【<?php echo date("Y年m月d日 H時i分",strtotime($row['reg_date'])) ?>】<br>
                     <?php echo $row['outbreak_place'] ?></br>
                </a>
                <br> 
                <hr style="border-top: 2px dotted #ff9d9d;width: 100%;">
           <?php endwhile; ?>
                
            
            
            <?php ($row2 = mysqli_num_rows($normal_count)) ?>
            <h3>継続中案件<?php echo $row2 ?>件</h3>
            <?php while ($row = mysqli_fetch_array($normal)): ?>
                <a href="detail.php?id=<?php echo $row['case_id'] ?>">
                    <?php echo $row['case_type_name'] ?><br>
                     【<?php echo date("Y年m月j日 H時i分",strtotime($row['reg_date'])) ?>】<br> 
                       <?php echo $row['outbreak_place'] ?><br>
                </a>
                 <br>
                 <hr style="border-top: 2px dotted #ff9d9d;width: 100%;">
            <?php endwhile; ?>
                 
           
                 <?php ($row2 = mysqli_num_rows($normal_end_count)) ?>
             <h3><?php if($row2 == 0){
                                echo"終了案件はありません";
                            }  else {
                               echo"終了案件".$row2."件" ;
                             }?>
             </h3>
             <?php while ($row = mysqli_fetch_array($normal_end)): ?>
                <a href="detail.php?id=<?php echo $row['case_id'] ?>">
                    <?php echo $row['case_type_name'] ?><br>
                     【<?php echo date("Y年m月d日 H時i分",strtotime($row['reg_date'])) ?>】<br>↓<br>【<?php echo date("Y年m月d日 H時i分",strtotime($row['end_date'])) ?>】<br>
                      <?php echo $row['outbreak_place'] ?><br>
                </a>
                 <br>
                 <hr style="border-top: 2px dotted #ff9d9d;width: 100%;">
            <?php endwhile; ?>
                 
                  <?php while ($row = mysqli_fetch_array($warning_end)): ?>
                <a href="detail.php?id=<?php echo $row['case_id'] ?>">
                     <?php echo "<font class='warning'>" . $row['case_type_name'] . "</font>" ?><br>
                    【<?php echo date("Y年m月d日 H時i分",strtotime($row['reg_date'])) ?>】<br>↓<br> 【<?php echo date("Y年m月d日 H時i分",strtotime($row['end_date'])) ?>】 <br>
                  <?php echo $row['outbreak_place'] ?></br>
                </a>
                <br> 
                <hr style="border-top: 2px dotted #ff9d9d;width: 100%;">
            <?php endwhile; ?>
                
               <?php while ($row = mysqli_fetch_array($warning_end)): ?>
                <a href="detail.php?id=<?php echo $row['case_id'] ?>">
                    <?php echo "<font class='warning'>" . $row['case_type_name'] . "</font>" ?><br>
                    【<?php echo date("Y年m月d日 H時i分",strtotime($row['reg_date'])) ?>】<br>↓<br> 【<?php echo date("Y年m月d日 H時i分",strtotime($row['end_date'])) ?>】<br>
                    <?php echo $row['outbreak_place'] ?></br>
                </a>
                <br> 
                <hr style="border-top: 2px dotted #ff9d9d;width: 100%;">
            <?php endwhile; ?>  
              
            <hr>
             
            <hr>
        </form>
    </body>
</html>
