<?php
try{
    // データベースへ接続する
    $pdo = new PDO('mysql:dbname=ppftech_db1;host=mysql1.php.xdomain.ne.jp;charset=utf8','ppftech_user1','user1234',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,]);

    //DBへのデータ追加
    date_default_timezone_set('japan');//タイムゾーン設定
    $sql = $pdo->prepare('insert into post_nakamoto(name,post_date,post_text) values(?,?,?)');

    if($sql->execute([htmlspecialchars($_REQUEST['name']),
    date('Y/m/d H:i:s'),
    nl2br(htmlspecialchars($_REQUEST['post_text']))])){
        $comment="投稿しました。";
    }else{
        $comment="投稿に失敗しました。";
    }

}catch(PDOException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>C_B_BBS</title>
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <h1><?php echo $comment ?></h1>
        <a href="index.php">戻る</a>
    </body>
</html>