<?php

	session_start();
	include ('dbconnect.php');
        
	try {
                //個人のつぶやき削除処理
		 if(isset($_POST['trash'])){
                        $uid2 = $_SESSION["USERID"];
                        $delmut2 = $pdo -> prepare("DELETE FROM mutter WHERE MutterUserID = :delmu && MutterDate = :muda");
                        $delmut2 -> bindParam(':delmu', $uid2);
                        $delmut2 -> bindParam(':muda', $_POST['deldat']);
                        $delmut2 -> execute();
                }
		//トークンが一致すればユーザ情報取得処理を行い、一致しなければログインページへリダイレクトを行います。
		if(isset($_COOKIE['token'])){
		
			$reload1 = $pdo -> prepare("SELECT * FROM user WHERE Token = :reltok1 ");
			$reload1 -> bindParam(':reltok1', $_COOKIE['token']);
			$reload1 -> execute();

			$rel1 = $reload1 -> fetch(PDO::FETCH_ASSOC);
			$uid = $rel1["UserID"];
			$firstname = $rel1["1stName"];
			$secondname = $rel1["2ndName"];

			
			if(empty($rel1)){
				setCookie("token", '', -1);
				session_destroy();
				header("Location: login.php");
			}
		} else {
			header("Location: login.php");
                }
                

		//つぶやきIDを日付順に変更して各テーブルから値を取得する
		$muid = $pdo -> prepare("select UserID,1stName,2ndName,Icon,MutterUserID,MutterLog,MutterDate from user,mutter where UserID = MutterUserID ORDER BY MutterDate DESC");
		$muid -> execute();
                
		$dtime = $pdo -> prepare("select DATE_FORMAT(MutterDate,'%Y/%m/%d %H:%i:%S') as MutterDate from mutter  ORDER BY MutterDate DESC");
		$dtime -> execute();

		//個人の限界までつぶやける処理（1人につき60件まで、それ以上は古い順に消されていきます）
		$uid2 = $_SESSION["USERID"]; 
		$mu = $pdo -> prepare("SELECT * FROM mutter WHERE MutterUserID = :mc");
		$mu -> bindParam(':mc', $uid2);
		$mu -> execute();
		$count = $mu -> rowCount();
		if($limit = 60 - $count < 0){
			$delmutter = $pdo -> prepare("DELETE FROM mutter WHERE MutterUserID = :delmu ORDER BY MutterUserID DESC LIMIT 1");
			$delmutter -> bindParam(':delmu', $uid2);
			$delmutter -> execute();
		}
	      
		//書き込み処理
		if(isset($_POST['Mutter'])){
			$mwrite = $_POST['MutterWrite'];
			$mw1 = $pdo -> prepare("INSERT INTO mutter (MutterUserID,MutterLog,MutterDate) VALUES (:muid,:mw1,now())");
			$mw1 -> bindParam(':muid', $uid);
			$mw1 -> bindParam(':mw1', $mwrite);
			$mw1 -> execute();
			
			header('Location:main.php');
		}
                $pdo = null;
                 
	} catch (PDOException $e) {
		exit('データベース接続失敗。'.$e->getMessage());
		die();
	}
	
?>


<!DOCTYPE html>
<head>
<link rel="apple-touch-icon" href="apple-touch-icon150.png" sizes="150x150">
<link rel="icon" href="./favicon.png" sizes="150x150">
<meta http-equiv="content-type" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/test.css">
<link rel="stylesheet" type="text/css" href="css/lightbox.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<script src="jquery/lightbox.js"></script>
<style>
input[type=text]	{
	font-size : 16px;
	border : 3px solid #ccc;
	-webkit-border-radius : 8px;
	-webkit-tap-highlight-color : rgba(0,0,0,0);
	box-shadow: 0px 1px rgba(0, 0, 0, 0.5);
}
</style>
</head>
<body>
<?php include('header.php'); ?>
<div class="maincenter">

	<form action="" method="post" style="width:96%; margin:12px;">
		<div class="fcolumn">
			<div class="wid"><input type="text" class="form-control" name="MutterWrite" placeholder="なんか適当に" required style ="width:100%; height:60px; font-size:50px;" /></div>
			<div><button type="submit" class="btn btn-default" name="Mutter" style ="height:60px; width:120px; font-size:24px;">つぶやく</button></div>
		</div>
	</form>
	<div class="mucolumn">
		<?php
                        $deldate = "del";
			$iconnum = 0;
                        //つぶやき一覧表示
			while($rec = $muid -> fetch(PDO::FETCH_ASSOC)){
				$iconnum++;
				$micon = $rec['Icon'];
                                //アイコン情報がNULLかどうかの確認
				if($micon != null){
					$mainicon = "icon/".$rec[UserID]."/icon.jpg";
				} else {
					$mainicon = "icon/"."default.jpg";
				}
				
				$row1 = $dtime -> fetch(PDO::FETCH_ASSOC);
				echo "<div class='mutterspace'>";
					echo "<div class='muttericon'>";
						echo "<div><a href='$mainicon' data-lightbox='$iconnum' rel='lightbox'><img src = '$mainicon' style='width:120px; height:120px; border-radius:50%; '></a></div>";
					echo "</div>";
					echo "<div style='width:76%;'>";
						echo "<div class='muside fdcolumn'>";
							echo "<div class='muside'>";
								echo "<div class='stname'>";
									print htmlspecialchars($rec['1stName']);
								echo "</div>";
								echo "<div class='the'>・ザ・</div>";
								echo "<div class='ndname'>";
									print htmlspecialchars($rec['2ndName']);
								echo "</div>";
							echo "</div>";
							echo "<div class='muside iddate'>";
								echo "<div class='muside idhyouji'>";
									echo "<div>@</div>";
									echo "<div>";
										print htmlspecialchars($rec['MutterUserID']);
									echo "</div>";
								echo "</div>";
								echo "<div class='mudate'>";
									echo ':';
									echo $row1['MutterDate'];
								echo "</div>";
							echo "</div>";
						echo "</div>";
						echo "<div class='mutter'>";
							print htmlspecialchars($rec['MutterLog']);
						echo "</div>";
					echo "</div>";
                                        //自分のつぶやきだけゴミ箱アイコン表示
                                        if($rel1["UserID"] ==$rec['MutterUserID']){
                                                $deldate= $row1['MutterDate'];                                                
                                                echo "<div class='trash'>";
                                                        echo "<form action='main.php' method='post'>";
                                                                echo "<a href='main.php'><input type='submit' value='' name='trash' / style='background-image: url(img/trash.png); background-position: center center;background-size: cover;width:60px;height:60px;'></a>";
                                                                echo "<input type='hidden' value='$deldate' name='deldat'>";
                                                        echo "</form>";
                                                echo "</div>";
                                        } else {
                                                echo "<div style='width:60px;height:60px;'></div>";
                                        }
				echo "</div>";
			}
		?>
	</div>
	
</div>
</body>
</html>
