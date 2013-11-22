<?php





$server = "localhost";
$user_name = "root";
$passwd = "";
$db_name = "gotu_syoubou";

$mysqli = new mysqli($server,$user_name,$passwd,$db_name);
$mysqli->set_charset('utf8');




//SQLでmst_groupテーブル呼び出し
$query = "SELECT * FROM mst_group";
//mst_groupのデータを$mst_groupへ
$mst_group = mysqli_query($mysqli,$query) or die('Error querying database.');

$query1 = "SELECT * FROM mst_area";
$mst_area = mysqli_query($mysqli,$query1) or die('Error querying database.');
 
//更新のためのデータ送信
/* $query2 = "SELECT cho_name FROM mst_area";
$date =   mysqli_query($mysqli,$query2) or die('Error querying database.');
 */
/* $query3 = "SELECT group_name FROM mst_group";
$date2 =  mysqli_query($mysqli,$query3) or die('Error querying database.'); 
  */


//追加処理
if(isset($_POST['insert'])){
		
		$stmt = $mysqli->prepare("INSERT INTO mst_area (cho_name, group_id) VALUES(?,?)");
		$stmt->bind_param('si', $_POST['cho_name'], $_POST['bundan']);
		$stmt->execute();
}

//更新処理
if(isset($_POST['update'])){
		//$_POST['cho_name']->execute(array($date);
		
	
		$stmt = $mysqli->prepare("UPDATE mst_area SET id =?,cho_name=?,group_id=? WHERE $id=?");
		$stmt->bind_param('si', $_POST['cho_name'], $_POST['bundan']);
		$stmt->execute();
		
		
		//$stmt = $mysqli->execute(array($_POST['cho_name'],$_POST['banti'],$_POST['bundan']));
}


//削除処理

if(isset($_POST['delete'])){

		$stmt=$mysqli->prepare("DELETE FROM mst_area WHERE id=?");
		$stmt->execute(array($_POST['cho_name']));
	
}



$dbh = null;



?>	
	
	
	
<html>
<head>

<meta charset="UTF-8" />
<title>テスト</title>
</head>
<body>
	<form action="test.php" method="post">
	    町名<INPUT type="text"  name="cho_name"size="20"><br>
	    
	    分団名 <select name="bundan" size="1"> 
	   
	    <!-- mst_groupのデータを一行ずつ抽出(mst_groupのデータが終わるまでwhileを回す) -->
		<?php while($row = mysqli_fetch_array($mst_group)): ?>
		
		<!-- phpからhtmlを動的に作成 1 津山市等 -->
		<option value="<?php echo $row['group_id'] ?>"><?php echo $row['group_name'] ?></option>
		
		<?php endwhile; ?>
			
		</select>
		<input type="submit" name = "insert" value="追加">
		<hr>
		
		
	</form>
	
	
	
	<form action="test.php" method="post">
		<table border="1">
		<tr>
		<td>ID</td>
		<td>町名</td>
		<td>担当分団名</td>
		<td>/</td>
		<td>/</td>
		</tr>
		<tr>
			<?php while($row = mysqli_fetch_array($mst_area)): ?>
		<td><"<?php echo $row['id'] ?>"><td><?php echo $row['cho_name']?><td>
			<input type="submit" name ="update" value="更新"><td><input type="submit" name = "delete" value="削除"></td>
		</tr>
		<?php endwhile; ?>
		</table>
		
			
		
		<!-- <input type="submit" name ="update" value="更新"> -->
	</form>
	
	
	
	<form action="test.php" method="post">
		<!-- <input type="submit" name = "delete" value="削除"> -->
	</form>
 	

    
    
    
    
</body>
</html>

