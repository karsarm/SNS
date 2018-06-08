<!DOCTYPE html>
<head>
<!-- CDN -->
<!-- CSS -->
<meta charset="utf-8" />
<link href="css/cropper.css" rel="stylesheet" type="text/css" media="all"/>

<!-- JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/cropper/1.0.0/cropper.min.js"></script>
<script src="jquery/iscroll.js" type="text/javascript"></script>
<script src="jquery/drawer.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(function(){
        // 初期設定
    var options =
    {
         aspectRatio: 1 / 1,
         viewMode:1,
        crop: function(e) {
                cropData = $('#select-image').cropper("getData");
                $("#upload-image-x").val(Math.floor(cropData.x));
                $("#upload-image-y").val(Math.floor(cropData.y));
                $("#upload-image-w").val(Math.floor(cropData.width));
                $("#upload-image-h").val(Math.floor(cropData.height));
        },
        zoomable:false,
        minCropBoxWidth:162,
        minCropBoxHeight:162
    }
 
        // 初期設定をセットする
    $('#select-image').cropper(options);
 
    $("#profile-image").change(function(){
                // ファイル選択変更時に、選択した画像をCropperに設定する
        $('#select-image').cropper('replace', URL.createObjectURL(this.files[0]));
    });
});
</script>




<?php

    $renam = $_FILES["upfile"]["name"];
    echo $renam;

    if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
	if (move_uploaded_file ($_FILES["upfile"]["tmp_name"], "icon/".$_FILES["upfile"]["name"])) {
        } 
        
    }
    chmod("icon/".$renam, 0770);
    rename("icon/".$renam,"icon/uptest.jpg");

    list($orgImageWidth, $orgImageHeight) = getimagesize("icon/uptest.jpg");
    echo $_FILES["upfile"]["name"];
    echo $_POST['profileImageW'];
    echo $_POST['profileImageH'];
    echo $_POST['profileImageX'];
    echo $_POST['profileImageY'];
    //最終画像のサイズ
    
    $lastImageHeight = $orgImageHeight;
    $lastImageWidth = $orgImageWidth;
    
    // サイズを指定して、背景用画像を生成
    $canvas = imagecreatetruecolor($_POST['profileImageW'], $_POST['profileImageH']);
    //中央点の計算
    $centerX = $orgImageWidth;
    $centerY = $orgImageHeight;
    
    //開始点の算出
    $startPointX = $_POST['profileImageX'];
    $startPointY = $_POST['profileImageY'];
 
    // コピー元画像の指定
    $imageInstance = imagecreatefromjpeg("icon/uptest.jpg");
 
    // ファイル名から、画像インスタンスを生成
    $hasImageResampled = imagecopyresampled($canvas,  // 背景画像
	$imageInstance, // コピー元画像
	0,        // 背景画像の x 座標
	0,        // 背景画像の y 座標
	$startPointX,        // コピー元の x 座標
	$startPointY,        // コピー元の y 座標
	$lastImageWidth,   // 背景画像の幅
	$lastImageHeight,  // 背景画像の高さ
	$lastImageWidth, // コピー元画像ファイルの幅
	$lastImageHeight  // コピー元画像ファイルの高さ
    );
    if( $hasImageResampled === false ){
     	return false;
    }
    // 画像を出力する
    $hasOutputImage = imagejpeg($canvas,   // 出力するファイル名（省略すると画面に表示する）
    "./icon/testicon.jpg",
	100                // 画像精度（この例だと100%で作成）
    );

    // メモリを開放する
    imagedestroy($canvas);

?>


</head>
<body>


    <form method="POST" enctype="multipart/form-data">
	<input type="file" id="profile-image" name="upfile"/>

	<img id="select-image" style="max-width:500px;">

　　　　<!-- 切り抜き範囲をhiddenで保持する -->
	<input type="hidden" id="upload-image-x" name="profileImageX" value="0"/>
	<input type="hidden" id="upload-image-y" name="profileImageY" value="0"/>
	<input type="hidden" id="upload-image-w" name="profileImageW" value="0"/>
	<input type="hidden" id="upload-image-h" name="profileImageH" value="0"/>
        <button type="submit">変換～</button>
    </form>

</body>
</html>