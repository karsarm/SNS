<?php
	session_start();
	include ('dbconnect.php');
        
        //古いディレクトリパス取得用
        $stoken = $pdo -> prepare("SELECT * FROM user WHERE Token = :stoken");
	$stoken -> bindParam(':stoken',$_COOKIE['token']);
	$stoken -> execute();
	$sto = $stoken -> fetch(PDO::FETCH_ASSOC);
	$userid = $sto['UserID'];
        
        //アイコンの古いディレクトリパス
	$directory_path = "./icon/".$userid;
	//アイコンの新しいディレクトリパス
	$directory_path1 = "./icon/".$_POST['userid_change'];
        
        //ユーザID更新処理
        if(isset($_POST['uichange'])){
            $stoken = $pdo -> prepare("UPDATE user SET UserID = :us WHERE Token = :stoken");
            $stoken -> bindParam(':us',$_POST['userid_change']);
	    $stoken -> bindParam(':stoken',$_COOKIE['token']);
	    $stoken -> execute();
            
            //つぶやきのユーザIDも変更させる。
            $stoken = $pdo -> prepare("UPDATE mutter SET MutterUserID = :us WHERE MutterUserID = :mutteruid");
            $stoken -> bindParam(':us',$_POST['userid_change']);
	    $stoken -> bindParam(':mutteruid',$userid);
	    $stoken -> execute();
            
            //お問い合わせユーザIDも変更させる。
            $stoken = $pdo -> prepare("UPDATE inquiry SET InqID = :in WHERE InqID = :inquid");
            $stoken -> bindParam(':in',$_POST['userid_change']);
            $stoken -> bindParam(':inquid',$userid);
            $stoken -> execute();
            
        }
        //ディレクトリが無ければ作られて、パーミッションも変更される
	mkdir($directory_path1, 0776);
	chmod($directory_path1, 0776);
	
        if(isset($_POST['uichange'])){
		$token = $_COOKIE['token'];
		$ss = $pdo -> prepare("SELECT * FROM user WHERE Token = :tok ");
		$ss -> bindParam(':tok', $token);
		$ss -> execute();

		$rec1 = $ss -> fetch(PDO::FETCH_ASSOC);
		$usid = $rec1['UserID'];

	}

	$stoken = $pdo -> prepare("SELECT * FROM user WHERE Token = :stoken");
	$stoken -> bindParam(':stoken',$_COOKIE['token']);
	$stoken -> execute();
	$sto1 = $stoken -> fetch(PDO::FETCH_ASSOC);
	$us = $sto1['UserID'];
	$st = $sto1['1stName'];
	$nd = $sto1['2ndName'];
	$pw = $sto1['Pssword'];
        $ic = $sto1['Icon'];
        
        $renam = $_FILES["upfile"]["name"];
        $testrenam = "./icon/".$us."/".$renam;
        $ext = pathinfo($testrenam, PATHINFO_EXTENSION);

        //アップロード処理、JPGなら処理せず、PNGであれば変換処理
        if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
            if (move_uploaded_file ($_FILES["upfile"]["tmp_name"], "icon/".$us."/".$_FILES["upfile"]["name"])) {
                if($ext == "png"){
                    $renam1 = imagecreatefrompng($testrenam);
                    $renam = imagejpeg($renam1, "./icon/".$us."/icon.jpg",100);
                }
            } 
        }
        chmod("./icon/".$us."/".$renam, 0770);
        rename("./icon/".$us."/".$renam,"icon/".$us."/icon.jpg");

        list($orgImageWidth, $orgImageHeight) = getimagesize("./icon/".$us."/icon.jpg");
                //echo $orgImageWidth;
                //echo $orgImageHeight;
                //echo $_FILES["upfile"]["name"];
                //echo $_POST['profileImageW'];
                //echo $_POST['profileImageH'];
                //echo $_POST['profileImageX'];
                //echo $_POST['profileImageY'];
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
        $imageInstance = imagecreatefromjpeg("./icon/".$us."/icon.jpg");
 
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
            "./icon/".$us."/icon.jpg",
            100                // 画像精度（この例だと100%で作成）
        );

        // メモリを開放する
        imagedestroy($canvas);
        
        

	//トークンがあれば実行されて、なければログイン画面へ遷移する
	if (isset($_COOKIE['token'])) {

		//ユーザIDが変更されてもしていなくても実行される。NULLなら実行されない。
		if(isset($_POST['userid_change'])){

			//アップされたファイルがnullと空文字の場合は実行されない
			if($_FILES["upfile"]["name"] != null || ''){
				//画像がアップされていたら実行
				if(isset($_FILES["upfile"]["name"])){
					//ファイル名を取得
					$iname = basename($_FILES["upfile"]["name"]);

					//古いディレクトリ先のアイコンの名前取得
					$img_path = $directory_path."/".$iname;
					$img = basename("$img_path");
					
					//古いディレクトリのアイコンを一旦全削除(新規登録時またはID未変更時は処理しない)
                                        if($directory_path != $directory_path1){
                                            //echo '古いディレクトリ削除しました';
                                            $filename2 = $directory_path."/*.*";
                                            foreach (glob($filename2) as $file) {
                                                //echo "ファイルが見つかりました";
						unlink ($file);
                                            }
                                            rmdir($directory_path);
                                        }
                                        
					//ディレクトリが無ければ作られて、パーミッションも変更される
					mkdir($directory_path1, 0776);
					chmod($directory_path1, 0776);
                                        
					//アイコンのテキスト名を格納する
					$iconup = $pdo -> prepare("UPDATE user SET Icon = :img WHERE UserID = :id ");
					$iconup->bindParam(':img', $img);
					$iconup->bindParam(':id', $usid);
					$iconup->execute();
				}else {
                                        //画像変更が無く、IDのみ変更があった場合処理される                                    
                                        //フォルダのリネーム
                                        rename($directory_path,$directory_path1);
                                }
			}



			//アイコンはアップされないで、IDだけ変更があった場合、フォルダ名の変更を行う。
			rename($directory_path,$directory_path1);
/*
			//cookieで一致するものを検索して新しいユーザIDへ更新する
			$stoken = $pdo -> prepare("UPDATE user SET UserID = :us WHERE Token = :stoken");
			$stoken -> bindParam(':us',$_POST['userid_change']);
			$stoken -> bindParam(':stoken',$_COOKIE['token']);
			$stoken -> execute();

			$stoken = $pdo -> prepare("SELECT * FROM user WHERE Token = :stoken");
			$stoken -> bindParam(':stoken',$_COOKIE['token']);
			$stoken -> execute();
			$sto = $stoken -> fetch(PDO::FETCH_ASSOC);
			$us = $sto['UserID'];
*/
		}

		if(isset($_POST['1stname_change'])){
			$stoken = $pdo -> prepare("UPDATE user SET 1stName = :st WHERE Token = :stoken");
			$stoken -> bindParam(':st',$_POST['1stname_change']);
			$stoken -> bindParam(':stoken',$_COOKIE['token']);
			$stoken -> execute();

			$stoken = $pdo -> prepare("SELECT * FROM user WHERE Token = :stoken");
			$stoken -> bindParam(':stoken',$_COOKIE['token']);
			$stoken -> execute();
			$sto = $stoken -> fetch(PDO::FETCH_ASSOC);
			$st = $sto['1stName'];
		}

		if(isset($_POST['2ndname_change'])){
			$stoken = $pdo -> prepare("UPDATE user SET 2ndName = :nd WHERE Token = :stoken");
			$stoken -> bindParam(':nd',$_POST['2ndname_change']);
			$stoken -> bindParam(':stoken',$_COOKIE['token']);
			$stoken -> execute();

			$stoken = $pdo -> prepare("SELECT * FROM user WHERE Token = :stoken");
			$stoken -> bindParam(':stoken',$_COOKIE['token']);
			$stoken -> execute();
			$sto = $stoken -> fetch(PDO::FETCH_ASSOC);
			$nd = $sto['2ndName'];
		}

		if($_POST['password_change']!= ''){
			if(isset($_POST['password_change'])){
				$stoken = $pdo -> prepare("UPDATE user SET Pssword = :pw WHERE Token = :stoken");
				$stoken -> bindParam(':pw',$_POST['password_change']);
				$stoken -> bindParam(':stoken',$_COOKIE['token']);
				$stoken -> execute();

				$stoken = $pdo -> prepare("SELECT * FROM user WHERE Token = :stoken");
				$stoken -> bindParam(':stoken',$_COOKIE['token']);
				$stoken -> execute();
				$sto = $stoken -> fetch(PDO::FETCH_ASSOC);
				$pw = $sto['Pssword'];
			}
		}
	}else {
		header("Location: login.php");
	}

?>

<!DOCTYPE HTML>
<html lang="ja">
<head>
<link href="css/cropper.css" rel="stylesheet" type="text/css" media="all"/>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/cropper/1.0.0/cropper.min.js"></script>
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
<link rel="apple-touch-icon" href="apple-touch-icon150.png" sizes="150x150">
<link rel="icon" href="./favicon.png" sizes="150x150">
<meta charset="utf-8" />
<title>ログイン情報ペーーーージ</title>
<link rel="stylesheet" href="css/test.css">
</head>
<body>
<?php include('header.php'); ?>
<form method="post" enctype="multipart/form-data">
	<div class="column">
		<h1 class="logsize">ユーザ情報変更ページだぞ</h1>
		<div class="logflex">
    		<input type="text" class="form-control" name="userid_change" value="<?php echo $us;?>" placeholder="変更したいユーザIDを入力" pattern="^[0-9A-Za-z]+$" required style="width:80%;height:80px;font-size:150%;"/>
		</div>
		<div class="logflex">
			<input type="text" class="form-control" name="1stname_change" value="<?php echo $st;?>" placeholder="変更したいニックネームを入れるのだ"  required style="width:80%;height:80px;font-size:150%;"/>
		</div>
		<div class="logflex">
			<input type="text" class="form-control" name="2ndname_change" value="<?php echo $nd;?>" placeholder="変更したい座右の銘を入れるのだ"  required style="width:80%;height:80px;font-size:150%;"/>
		</div>
		<div class="logflex">
			<input type="password" class="form-control" name="password_change" placeholder="変更したいパスワードを入れるのだ" pattern="^[0-9A-Za-z]+$" style="width:80%;height:80px;font-size:150%;"/>
		</div>
		<div class="upload">
                    <div class="file">アイコン変更
                        <input type="file" id="profile-image" name="upfile" accept="image/jpg"/>
                    </div>
                	<img id="select-image" style="max-width:500px;">

                　　　　<!-- 切り抜き範囲をhiddenで保持する -->
                        <input type="hidden" id="upload-image-x" name="profileImageX" value="0"/>
                        <input type="hidden" id="upload-image-y" name="profileImageY" value="0"/>
                        <input type="hidden" id="upload-image-w" name="profileImageW" value="0"/>
                        <input type="hidden" id="upload-image-h" name="profileImageH" value="0"/>
		</div>
		<div style ="width:80%; display:flex; justify-content:center;">
			<button type="submit" class="btn btn-default" name="uichange" style="height:120px; width:80%; font-size:32px; background-color:#CCC;">情報を更新します</button>
		</div>
	</div>
</form>


</div>
</body>
</html>