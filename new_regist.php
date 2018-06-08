<!DOCTYPE HTML>
<html lang="ja">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<meta charset="utf-8" />
<title>アウトレイジSNS登録所</title>
<link rel="stylesheet" href="css/test.css">

</head>
<body>
<?php include('header.php') ?>
<div>
    <div class="nrcolumn">
<form method="post">
	<h1 class="logflex" style="font-size:40px;">会員登録</h1>
	<div class="logflex">
            <input type="text" class="form-control" name="user_id" placeholder="ユーザーIDを入力！英数字のみ受け付けるぞ" pattern="^[0-9A-Za-z]+$" required style="width:80%;height:80px;font-size:150%;"/>
        </div>
	<div class="logflex">
	    <input type="text"  class="form-control" name="1st_name" placeholder="ニックネーム（日本語でOKです）" required style="width:80%;height:80px;font-size:150%;"/>
        </div>
        <div class="logflex">
	    <input type="text"  class="form-control" name="2nd_name" placeholder="座右の銘（日本語でOKです）" required style="width:80%;height:80px;font-size:150%;"/>
	</div>
	<div class="logflex">
	    <input type="password" class="form-control" name="password" placeholder="パスワードを入れるのです。これも英数字のみ" pattern="^[0-9A-Za-z]+$" required style="width:80%;height:80px;font-size:150%;"/>
	</div>
        <div class="logflex">
            <button type="submit" name="signup" style="height:60px; width:80%; font-size:32px; background-color:#CCC;">会員登録する</button>
        </div>
</form>
    <div class="logflex">
<?php
	session_start();

	try{
		include ('dbconnect.php');
	} catch (PDOException $e) {
		exit('データベース接続失敗。'.$e->getMessage());
		die();
	}

	$uid = $_POST['user_id'];
	
	//echo "$uid";
	
	$ss = $pdo -> prepare("SELECT * FROM user WHERE UserID = :uid");
	$ss -> bindParam(':uid', $uid);
	$ss -> execute();
	$count = $ss -> fetchColumn();

	/*
	if(empty($uid)){
		echo '空文字です';
		echo "$uid";
	} else {
		echo '中身ありまぁす';
		echo "$uid";
	}
	*/
	if($uid == null){
		echo "ウェェルカーム";
		echo "$uid";
	} else {
		echo "$uid";
		if($count > 0){
			echo "IDが被ってるから他に変えるんやぞ";
		} else {
			$stmt = $pdo -> prepare("INSERT INTO user (UserID,1stName,2ndName,Pssword) VALUES (:name1,:name2,:day,:pass)");
			$stmt -> bindParam(':name1', $name1);
			$stmt -> bindParam(':name2', $name2);
			$stmt -> bindParam(':day', $day);
			$stmt -> bindParam(':pass', $pass);
		
			$name1 = $_POST['user_id'];
			$name2 = $_POST['1st_name'];
			$day = $_POST['2nd_name'];
			$pass = $_POST['password'];
			
			$stmt->execute();
		
			echo "登録成功じゃぞ！";
		}
	}





?>
        </div>
    </div>
</div>
</body>
</html> 