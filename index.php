<!DOCTYPE html>
<head>
<link rel="apple-touch-icon" href="apple-touch-icon150.png" sizes="150x150">
<link rel="icon" href="./favicon.png" sizes="150x150">
<meta http-equiv="content-type" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/test.css">
<link rel="stylesheet" type="text/css" href="css/index.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

<script type="text/javascript">
$(function(){
 
    // サイトアクセス時に非表示にしてから、フェードインさせる
    $('.wrapper').hide();
    $('.wrapper').fadeIn(2000);
     
    // リンククリック時にフェードアウトしてから、画面遷移する
    $('a').click(function(){
        // URLを取得する
        var url = $(this).attr('href');
 
        // URLが空ではない場合
        if (url != '') {
            // フェードアウトしてから、取得したURLにリンクする
            $('.wrapper').fadeOut(1000);
            setTimeout(function(){
                location.href = url;
            }, 1000);
        }
        return false;
 
    });
});
</script>

</head>
<body>

<div class="wtug">
	<a class="wrapper bluelight" href="index2.php"><p class="cap">Enter</p></a>
	<p class="wrapper caption">Welcome to underground</p>
</div>


</body>
</html>