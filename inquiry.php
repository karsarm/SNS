<!DOCTYPE html>
<?php
    session_start();
    include('header.php');
    //ユーザ名、ファーストネーム、セカンドネームの取得を行う。
    $cookie_token = $_COOKIE['token'];
    $usid = $pdo -> prepare("SELECT * FROM user WHERE Token = :token ");
    $usid -> bindParam(':token', $cookie_token);
    $usid -> execute();
        
    $row4 = $usid -> fetch(PDO::FETCH_ASSOC);
    $uid = $row4['UserID'];
    $stname = $row4['1stName'];
    $ndname = $row4['2ndName'];

    //お問い合わせ格納処理
    if(isset($_POST['inquiry'])){        
        $inq = $pdo -> prepare("INSERT INTO inquiry (InqID,InqLog,InqDate) VALUES (:inqid,:inqlog,now())");
        $inq -> bindParam(':inqid', $row4['UserID']);
        $inq -> bindParam(':inqlog', $_POST['inquiry']);
        $inq -> execute();
    }
    //フォームの改行コード削除処理。
    $inq1 = $pdo -> prepare("update inquiry set InqLog = replace(InqLog,Char(13),'')");
    $inq1 -> execute();
    $inq2 = $pdo -> prepare("update inquiry set InqLog = replace(InqLog,Char(10),'');");
    $inq2 -> execute();
?>

<html>
    <head>
        <link rel="apple-touch-icon" href="apple-touch-icon150.png" sizes="150x150">
        <link rel="icon" href="./favicon.png" sizes="150x150">
        <meta http-equiv="content-type" charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/test.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
        <script src="jquery/iscroll.js" type="text/javascript"></script>
        <script src="jquery/drawer.min.js" type="text/javascript"></script>
        <script>
        $(document).ready(function() {
        	 $('.drawer').drawer();
        });
        </script>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div class="column">
            <h1 style="font-size: 48px">問い合わせフォーム</h1>
            <div style="font-size:20px;">こんにちは、<?php echo $stname;?>・ザ・<?php echo $ndname;?>さん</div>
            <form method="post" style="display:flex;justify-content: center;align-items: center;flex-direction: column;width:100%;">
                <textarea type="text" name="inquiry" style="width:90%;height:76vh;font-size:30px;"></textarea>
                <button type="submit" style="height:120px; width:80%; font-size:48px; background-color:#CCC; margin:16px 0px">問い合わせ送信</button>
            </form>
        </div>
    </body>
</html>
