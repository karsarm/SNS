<!DOCTYPE html>
<?php 
	session_start();
?>
<head>
<link rel="apple-touch-icon" href="apple-touch-icon150.png" sizes="150x150">
<link rel="icon" href="./favicon.png" sizes="150x150">
<meta http-equiv="content-type" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/test.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<script src="jquery/jquery.bgswitcher.js"></script>
<script>
jQuery(function($) {
    $('.bg-slider').bgSwitcher({
        images: ['img/bg1.jpg','img/bg2.jpg','img/bg3.jpg','img/bg4.jpg'], // 切替背景画像を指定
		interval: 4000, // 背景画像を切り替える間隔を指定 3000=3秒
        loop: true, // 切り替えを繰り返すか指定 true=繰り返す　false=繰り返さない
        shuffle: true, // 背景画像の順番をシャッフルするか指定 true=する　false=しない
        effect: "fade", // エフェクトの種類をfade,blind,clip,slide,drop,hideから指定
        duration: 800, // エフェクトの時間を指定します。
        easing: "swing" // エフェクトのイージングをlinear,swingから指定
    });
});
</script>
<title>アウトレイジ---dark site---</title>
</head>

<body>
<?php include('header.php'); ?>
<div class="posi">
	<div class="bgsize">
		<div class="bg-slider">
			<h1 class="bg-slider__title"></h1>
			<div class="bgposi">
				<div class="fsize">Hello.</div>
				<div class="fsize1">We are yakinicross </br>a.k.a</br>outrage -gluttony's-</div>
			</div>
		</div>
	</div>
</div>
<div class = "foot">
<?php include('footer.php'); ?>
</div>

</body>
</html>
