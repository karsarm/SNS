<?php

	session_start();
	include('dbconnect.php');

	echo $_SESSION["USERID"];
	echo $_SESSION["ST-NAME"];
	echo $_SESSION["ND-NAME"];
	
	$id = $_SESSION["USERID"];
	$alpass = $_SESSION["PASSWORD"];
	$cookie_token = $_COOKIE['token'];

	//パラメータ取得
	if(isset($_COOKIE['token'])){
		$usid = $pdo -> prepare("SELECT * FROM user WHERE Token = :token ");
		$usid -> bindParam(':token', $cookie_token);
		$usid -> execute();
	
		$row4 = $usid -> fetch(PDO::FETCH_ASSOC);
		$id = $row4['UserID'];
		$alpass = $row4['Password'];
	}


	$auto = true;
	
	echo $_COOKIE['token'];

	//ログイン判定フラグ
	$normal_result = false;
	$auto_result = false;


	//簡易ログイン
	if (!isset($cookie_token)) {
		if (check_user($id)) {
			echo '簡易ログインできています';
			$normal_result = true;
		}
	}

	//自動ログイン
	if (isset($cookie_token) ) {
		echo 'クッキートークンあります';
		if (check_auto_login($cookie_token)) {
			$auto_result = true;
			$id = $_SESSION['USERID'];
		}
	}

	if ($normal_result || $auto_result) {
		//ログイン成功
    	//セッション ID の振り直し
		session_regenerate_id(true);

		//トークン生成処理
		if (($normal_result && $auto == true) || $auto_result) {

			//トークンの作成
			$token = get_token();

			//トークンの登録
			register_token($id, $token);

			echo $token;
			//自動ログインのトークンを２週間の有効期限でCookieにセット
			setcookie("token", $token, time()+60*60*24*14);
			echo 'トークンをセットしました。';

 			/*
			if ($auto_result) {
				//古いトークンの削除
				delete_old_token($cookie_token);
				echo 'トークン削除できました';
			}
			*/
		}

		//リダイレクト
		//("HTTP/1.1 301 Moved Permanently");
		echo ("リダイレクトできます");
		echo $_SESSION["USERID"];
		echo $_SESSION["ST-NAME"];
		echo $_SESSION["ND-NAME"];
		header("Location: main.php");
		exit;
		$pdo = null;
	} else {
		//ログイン失敗
		//リダイレクト
		//header("HTTP/1.1 301 Moved Permanently");
		//header("Location: login.php");
		$pdo = null;
	}


//----------------------------
//ログイン処理
//----------------------------
function check_user($id) {
	//DB接続処理
	echo '1';
	include('dbconnect.php');
	echo '1';

	//SQL
	$login = $pdo -> prepare("UPDATE user SET LoginFlag = 'true' WHERE UserID = :id ");
	$login -> bindParam(':id', $id);
	$login -> execute();
	echo '2';

	$login = $pdo -> prepare("SELECT * FROM user WHERE UserID = :id ");
	$login -> bindParam(':id', $id);
	$login -> execute();
	echo '3';
	$row2 = $login -> fetch(PDO::FETCH_ASSOC);
	$count2 = $row2['LoginFlag'];

	echo '4';
	if ($count2 == true) {
		echo '成功しました';
		return true;
	} else {
		echo '失敗しました';
		return false;
	}
}



//--------------------------
//自動ログイン処理
//--------------------------
function check_auto_login($id) {

	include('dbconnect.php');

	$alogin = $pdo -> prepare("SELECT Token FROM user WHERE UserID = :lf ");
	echo 'あ';
	$alogin -> bindParam(':lf', $id);
	echo 'い';
	$alogin -> execute();
	echo 'う';
	$row3 = $alogin -> fetch(PDO::FETCH_ASSOC);
	$count3 = $row3['Token'];
	
	echo 'え';
	if ($cookie_token == $count3) {
		echo '自動ログイン成功です';
		return true;
	} else {
		echo '自動ログイン失敗です';
		setCookie("token", '', -1);
		return false;
	}
	$pdo = null;
}



//---------------------------------------------------------------------------//
//トークンの登録
//---------------------------------------------------------------------------//
 function register_token($id, $token) {

	//DB接続
	include('dbconnect.php');

    //プレースホルダで SQL 作成
    $tokgen = $pdo -> prepare("UPDATE user SET Token = :tok,LastLogin = now() WHERE UserID = :uid ");
	$tokgen -> bindParam(':tok',$token);
	$tokgen -> bindParam(':uid', $id);
	$tokgen -> execute();

	echo("トークンを登録しましたよ");
	$pdo = null;
}


/*
//---------------------------------------------------------------------------//
//トークンの削除
//---------------------------------------------------------------------------//
function delete_old_token($id) {
    //DB接続
    include ('dbconnect.php');
    $deltok = $pdo -> prepare("UPDATE user SET Token = null WHERE UserID = :id");
	$tokgen -> bindParam(':id', $id);
	$tokgen -> execute();
	echo("トークンを削除しました");
	$pdo = null;
}
*/


//---------------------------------------------------------------------------//
// トークンを作成
//---------------------------------------------------------------------------//
function get_token() {
  $TOKEN_LENGTH = 16;//16*2=32桁
  $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
  echo ("トークンを作成しました");
  return bin2hex($bytes);

}


?>


<!DOCTYPE html>
<head>
<meta charset="utf-8" />

</head>
<body>
</body>
</html>