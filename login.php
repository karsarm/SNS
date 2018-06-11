<?php
	session_start();
	include ('dbconnect.php');

	echo $_COOKIE['token'];
	if (isset($_COOKIE['token'])) {
		$stoken = $pdo -> prepare("SELECT * FROM user WHERE Token = :stoken");
		$stoken -> bindParam(':stoken',$_COOKIE['token']);
		$stoken -> execute();
		$st = $stoken -> fetch(PDO::FETCH_ASSOC);
		$_SESSION["ST-NAME"] = $st['1stName'];
		$_SESSION["ND-NAME"] = $st['2ndName'];
		$_SESSION["USERID"] = $st['UserID'];
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: autologin.php");
	}

	
	if(isset($_POST["login"])){
		$uid = $_POST['user_id'];
		$pw = $_POST['password'];
		$ss = $pdo -> prepare("SELECT * FROM user WHERE UserID = :uid AND Pssword = :pw");
		$ss -> bindParam(':uid', $uid);
		$ss -> bindParam(':pw', $pw);
		$ss -> execute();
		$count = $ss -> fetchColumn();
	
	
			// 認証成功
		//echo $count;
		if ($count > 0){
			// セッションIDを新規に発行する
			session_regenerate_id(TRUE);
			$_SESSION["USERID"] = $_POST["user_id"];
			$_SESSION["PASSWORD"] = $_POST["password"];
			$handle_name = $pdo -> prepare("SELECT * FROM user WHERE UserID = :uid");
			$handle_name -> bindParam(':uid', $uid);
			$handle_name -> execute();
			
			$lastlogin = $pdo -> prepare("UPDATE user SET LastLogin = NOW() WHERE UserID = :uid1");
			$lastlogin -> bindParam(':uid1', $uid);
			$lastlogin -> execute();
			
			$rec = $handle_name -> fetch(PDO::FETCH_ASSOC);
			$_SESSION["ST-NAME"] = $rec['1stName'];
			$_SESSION["ND-NAME"] = $rec['2ndName'];
			
			header("Location: autologin.php");
			exit;
		} else {
			$errorMessage = "ユーザIDあるいはパスワードに誤りがあります。";
			echo $errorMessage;
		}
	}




?>

<!DOCTYPE HTML>
<html lang="ja">
<head>
<link rel="apple-touch-icon" href="apple-touch-icon150.png" sizes="150x150">
<link rel="icon" href="./favicon.png" sizes="150x150">
<meta charset="utf-8" />
<title>ログインペーーーージ</title>
<link rel="stylesheet" href="css/test.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<?php include('header.php'); ?>
<form method="post">
	<div class="column">
		<h1 class="logsize">ログイン画面ですの</h1>
		<div class="logflex">
    		<input type="text" class="form-control" name="user_id" placeholder="登録したユーザーIDを入力～" pattern="^[0-9A-Za-z]+$" required style="width:80%;height:80px;font-size:150%;"/>
		</div>
		<div class="logflex">
			<input type="password" class="form-control" name="password" placeholder="登録したパスワードを入れる" pattern="^[0-9A-Za-z]+$" required style="width:80%;height:80px;font-size:150%;"/>
		</div>
		<button type="submit" name="login" style="height:120px; width:80%; font-size:48px; background-color:#CCC; margin:16px 0px">ログイン</button>
	</div>
</form>


</div>
</body>
</html> 