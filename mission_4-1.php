<?php

header('Content-Type: text/html; charset=UTF-8');

//データベースへ接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo= new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_WARNING));

$name=$_POST['name'];
$comment=$_POST['comment'];
$pass=$_POST['pass'];
$edit=$_POST['edit'];
$pass2=$_POST['pass2'];
$number=$_POST['number'];
$delete=$_POST['delete'];
$pass3=$_POST['pass3'];


//テーブル作成
$sql="CREATE TABLE IF NOT EXISTS table2"
."("
."id INT PRIMARY KEY AUTO_INCREMENT,"
."name char(32),"
."comment TEXT,"
."date datetime,"
."pass char(32)"
.");";
$stmt=$pdo->query($sql);



//テーブル確認
$sql='SHOW TABLES';
$result=$pdo->query($sql);
foreach($result as $row){
	echo $row[0];
	echo '<br>';
}
echo "<hr>";



//中身を確認
$sql='SHOW CREATE TABLE table2';
$result=$pdo->query($sql);
foreach ($result as $row){
	print_r($row);
}
echo "<hr>";



	

if(!empty($name) and !empty($comment) and !empty($pass) and empty($number)){
//データ入力
	$sql=$pdo->prepare("INSERT INTO table2(name,comment,date,pass) VALUES(:name,:comment,NOW(),:pass)");
	$sql->bindParam(':name',$name,PDO::PARAM_STR);
	$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
	$sql->bindParam(':pass',$pass,PDO::PARAM_STR);
	$name=$_POST['name'];
	$comment=$_POST['comment'];
	$pass=$_POST['pass'];
	$sql->execute();
}




//編集の表示
if(!empty($edit)){
	$sql='SELECT*FROM table2';
	$results=$pdo->query($sql);
	$all=$results->fetchAll();
	var_dump($all);
	foreach($all as $row){
	 	if($row['id']==$edit){
			if($row['pass']==$pass2){
			$edit_num=$row['id'];
			$edit_name=$row['name'];
			$edit_comment=$row['comment'];
			}
			else{
			$error="パスワードが違います！";
			}
		}
	}
}

//データ編集
if(!empty($number)){
	$sql='SELECT*FROM table2';
	$results=$pdo->query($sql);
	$all=$results->fetchAll();
	foreach($all as $row){
		if($number==$row['id']){
		$id=$row['id'];
		$name=$_POST['name'];
		$comment=$_POST['comment'];
		$pass=$_POST['pass'];
		$sql="update table2 set name='$name',comment='$comment',pass='$pass' where id=$id";
		$result=$pdo->query($sql);
		}
	}
}


//データ削除
if(!empty($delete)){
	$sql='SELECT*FROM table2';
	$results=$pdo->query($sql);
	foreach($results as $row){
		if($row['id']==$delete){
			if($row['pass']==$pass3){
				$id=$row['id'];
				$sql="delete from table2 where id=$id";
				$result=$pdo->query($sql);
			}
			else{
			$error="パスワードが違います！";
			}
		}
	}
}

?>



<html>
<head>
<meta http-equiv="Content-Type" carset="utf-8">
<?php echo "$error"; ?>
</head>

<body>

<form method="post" action="mission_4-1.php">
	<input type="text" name="name" placeholder="名前" value="<?php echo $edit_name; ?>"><br>
	<input type="text" name="comment" placeholder="コメント"value="<?php echo $edit_comment; ?>" ><br>
	<input type="text" name="pass" placeholder="パスワード">
	<input type="submit" value="送信"><br><br>
	<input type="text" name="number" style="visibility:hidden" value="<?php echo $edit_num; ?>"><br>
	<input type="text" name="delete" placeholder="削除対象番号"><br>
	<input type="text" name="pass3" placeholder="パスワード">
	<input type="submit" value="削除"><br><br>
	<input type="text" name="edit" placeholder="編集対象番号"><br>
	<input type="text" name="pass2" placeholder="パスワード">
	<input type="submit" value="編集">
</form>
</body>
</html>


<?php
//データを表示
$sql='SELECT*FROM table2';
$results=$pdo->query($sql);
foreach($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date'].'<br>';
}
?>

