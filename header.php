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
					if($ricon != null){
						$icon = "icon/".$row4[UserID]."/icon.jpg";
					} else {
						$icon = "icon/"."default.jpg";
					}
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
				if(!isset($_COOKIE['token'])){
					echo "<li class='glcenter'><a href='new_regist.php'>アカウント作成</a></li>";
				}
			
				if(isset($_COOKIE['token'])){
					echo "<li class='glcenter'><a href='uichange.php'>ユーザ情報変更</a></li>";
				}
			
				if(isset($_COOKIE['token'])){
					echo "<li class='glcenter'><a href='logout.php'>ログアウト</a></li>";
				}
				
			?>
		</ul>
	</nav>
</div>
</body>
</html>