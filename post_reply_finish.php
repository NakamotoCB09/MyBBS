<?php
try{
    // データベースへ接続する
    $pdo = new PDO('mysql:dbname=ppftech_db1;host=mysql1.php.xdomain.ne.jp;charset=utf8','ppftech_user1','user1234',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,]);

    //DBへのデータ追加
    date_default_timezone_set('japan');//タイムゾーン設定
    $spl=$pdo->prepare('insert into post_nakamoto(reply_id,name,post_date,post_text) values(?,?,?,?)');
    if($_REQUEST['reply_id']!=null){ // すでに返信先IDが設定されている（返信先が返信コメントだった）場合
        if($spl->execute([htmlspecialchars($_REQUEST['reply_id']),htmlspecialchars($_REQUEST['name']),date('Y/m/d H:i:s'),
        nl2br(htmlspecialchars($_REQUEST['post_text']))])){
            $comment="返信しました。";
        }else{
            $comment="投稿に失敗しました。";
        }
    }else{ // 返信先IDがnull（返信先が大本のコメント）だった場合
        if($spl->execute([htmlspecialchars($_REQUEST['column_id']),htmlspecialchars($_REQUEST['name']),date('Y/m/d H:i:s'),
        nl2br(htmlspecialchars($_REQUEST['post_text']))])){
            $comment="返信しました。";
        }else{
            $comment="投稿に失敗しました。";
        }
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
        <form name="form1" action="reply.php?panel_id=<?php echo $_POST['id']?>" method="post" onsubmit="return check()">
            <input type="hidden" name="reply_id" value="<?php echo $_POST['reply_id']?>">
            <input type="hidden" name="panel_id" value="<?php echo $_POST['panel_id']?>">
            <input type="hidden" name="id" value="<?php echo $_POST['id']?>">
            <input type="submit" value="戻る">
        </form>
    </body>
</html>