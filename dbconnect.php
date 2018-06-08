<?php
$dsn = 'mysql:dbname=outrage_sns;host=localhost';
$user = 'testuser';
$password = 'testuser';

try{
    $pdo = new PDO($dsn, $user, $password);
}catch (PDOException $e){
    print('Error:'.$e->getMessage());
    die();
}

?>