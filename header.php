<!DOCTYPE html>

<head>
<link rel="apple-touch-icon" href="apple-touch-icon150.png" sizes="150x150">
<link rel="icon" href="favicon.png" sizes="150x150">
<meta http-equiv="content-type" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/test.css">
<link rel="stylesheet" type="text/css" href="css/drawer.css">

<script src="jquery/iscroll.js" type="text/javascript"></script>
<script src="jquery/drawer.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
	 $('.drawer').drawer();
});
</script>
</head>

<body class="drawer drawer--right">
<?php include('dbconnect.php'); ?>


<div class="head">
	<div class="abc">
	
		<?php
			session_start();
                        //cookieを確認後、必要な情報の取得。
			if(isset($_COOKIE['token'])){
				$cookie_token = $_COOKIE['token'];
				$usid = $pdo -> prepare("SELECT * FROM user WHERE Token = :token ");
				$usid -> bindParam(':token', $cookie_token);
				$usid -> execute();
	
                                $row4 = $usid -> fetch(PDO::FETCH_ASSOC);
				$uid = $row4['UserID'];
				$stname = $row4['1stName'];
				$ndname = $row4['2ndName'];
				$ricon = $row4['Icon'];
                                $adm = $row4['Admin'];
                                //アイコン設定がNULLの場合はデフォルトアイコンを設定。
				if($ricon != null){
					$icon = "icon/".$row4[UserID]."/icon.jpg";
				} else {
					$icon = "icon/"."default.jpg";
				}
                                //cookieを確認後、ファーストネーム、セカンドネーム、アイコンの表示を行っています。
				if($_COOKIE['token'] = $row4['Token']){
					echo "<div class='flex'>";
						echo "<div class='icradi'><img src = '$icon' style='width:120px; height:120px; border-radius:50%; '></div>";
						echo "<div class='prof pcprof'>";
							echo "<div>";
								echo $stname;
								echo "・ザ・";
								echo $ndname;
							echo "</div>";
							echo "<div>";
								echo "@";
								echo $uid;
							echo "</div>";
						echo "</div>";
					echo "</div>";
				}
			} else {
                            header('login.php');
                        }
		?>
		
		<div class="title1 spnone">アウトレイジ</div>
		<div class="title2 spnone">---dark site---</div>
		<button type="button" class="drawer-toggle drawer-hamburger none">
			<span class="sr-only">toggle navigation</span>
			<span class="drawer-hamburger-icon"></span>
		</button>
	</div>

	
	<nav class="drawer-nav" role="navigation">
		<ul class="global drawer-menu">
			<li class="glcenter"><a href="index2.php">ホーム</a></li>
			<li class="glcenter"><a href="login.php">SNS</a></li>
			<?php
                                //cookieが無い場合は表示されます。
				if(!isset($_COOKIE['token'])){
					echo "<li class='glcenter'><a href='new_regist.php'>アカウント作成</a></li>";
				}
                                //cookieがある場合に表示されます。
				if(isset($_COOKIE['token'])){
					echo "<li class='glcenter'><a href='uichange.php'>ユーザ情報変更</a></li>";
				}
                                //cookieがある場合に表示されます。
				if(isset($_COOKIE['token'])){
					echo "<li class='glcenter'><a href='logout.php'>ログアウト</a></li>";
				}
                                //cookieがあり、かつ管理者アカウント・非管理者アカウントで表示の切り替え。
                                if(isset($_COOKIE['token'])){
                                    if($adm == 'true'){
                                        //管理者権限保有アカウントのみ表示
                                        echo "<li class='glcenter'><a href='allinquiry.php'>問い合わせ一覧<br>（管理者）</a></li>";
                                    } else {
                                        //非管理者アカウントのみ表示
                                        echo "<li class='glcenter'><a href='inquiry.php'>問い合わせ</a></li>";
                                    }
                                }
				
			?>
		</ul>
	</nav>
</div>
</body>
</html>