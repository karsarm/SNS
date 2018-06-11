<!DOCTYPE html>
<?php
	session_start();
	include ('dbconnect.php');
?>
<head>
<link rel="apple-touch-icon" href="apple-touch-icon150.png" sizes="150x150">
<link rel="icon" href="./favicon.png" sizes="150x150">
<meta http-equiv="content-type" charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/test.css">
<link rel="stylesheet" type="text/css" href="css/lightbox.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<script src="jquery/lightbox.js"></script>
</head>
<body>
    <?php include('header.php'); ?>
        <?php
                //お問い合わせ一覧取得処理
                $muid = $pdo -> prepare("select * from inquiry");
                $muid -> execute();
                echo "<div class='maincenter' style='padding:0 0 16px 0'>";
                //お問い合わせ一覧表示処理
                while($inq = $muid -> fetch(PDO::FETCH_ASSOC)){
                    echo "<div style= 'margin:16px 0 0 0; width:100%;display:flex;flex-direction:column;width:95%; background-color:white;font-size:32px;'>";
                        echo "<div style='margin:0 0 16px 0;padding:16px;'>"; 
                            echo "<div>@".$inq['InqID']."</div>";
                            echo "<div>".$inq['InqLog']."</div>";
                            echo "<div>".$inq['InqDate']."</div>";
                        echo "</div>";
                    echo "</div>";
                }
                echo "</div>"
        ?>
</body>