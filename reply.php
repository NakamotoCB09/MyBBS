<?php
$select=array();
$id = $_POST['id'];
try{
    // データベースへ接続する
    $pdo = new PDO('mysql:dbname=ppftech_db1;host=mysql1.php.xdomain.ne.jp;charset=utf8','ppftech_user1','user1234',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,]);

}catch(PDOException $e){
    //エラーが発生した場合、500エラーを返す
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>リプライ</title>
        <script tipe="text/javascript">
        function check(){
            var flag = 0;
            if(document.form1.name.value == ""){ // 名前が入力されているか判定
                flag = 1;
            }else if(document.form1.post_text.value == ""){ // 本文が入力されているか判定
                flag = 2;
            }
            if(flag == 1){
                window.alert('その方、名を名乗れぃ（上様）');
                return false;
            }else if(flag == 2){
                window.alert('何か申してみよ（寛容）');
                return false;
            }else{
                return true;
            }
        }
        </script>
    </head>
    <body>
        <h2>親スレッド</h2>
        <table border="1" width="100%">
            <?php if(!isset($_GET['panel_id'])){
                $id=$_POST['id'];
            }else{
                $id=$_GET['panel_id'];
            } ?>
            <!--index.phpからpostで送られてきたidから、返信先のIDになるレコード情報を取得-->
            <?php foreach($pdo->query('select * from post_nakamoto where id='.$id) as $column):?>
            <td width="15%"><?php echo $column['name'] ?></td>
            <td width="20%"><?php echo $column['post_date'] ?></td>
            <td width="65%"><?php echo $column['post_text'] ?></td>
            <?php endforeach ?>
        </table>
        <h3>返信内容</h3>
        <table border="1" width="100%">
            <!--index.phpで投稿一覧を表示するコード-->
            <?php
            define('MAX',10);

            $i=0;
            $index=array();
            foreach($pdo->query('select * from post_nakamoto where reply_id='.$id.' order by id desc') as $row){
                $index[$i] = $row;
                $i++;
                }

            $index_num = count($index);
            $max_page = ceil($index_num/MAX);
            if(!isset($_GET['page_id'])){
                $now = 1;
            }else{
                $now = $_GET['page_id'];
            }
            $start_no = ($now - 1) * MAX;

            $index_data = array_slice($index,$start_no,MAX,true);
            foreach($index_data as $val):
            ?>
            <tr>
                <td width="15%"><?php echo $val['name'] ?></td>
                <td width="20%"><?php echo $val['post_date'] ?></td>
                <td width="65%"><?php echo $val['post_text'] ?></td>
            </tr>
            <?php endforeach ?>
            <!--ここまで-->
        </table>
            <?php
            for($i=1;$i<=$max_page;$i++){
                if($i==$now){
                    echo $now.' ';
                }else{
                    echo '<a href=\'/nakamoto/reply.php?panel_id='.$id.'&page_id='.$i.'\')>'.$i.'</a>'.' ';
                }
            }
            ?>

        <!--投稿したい情報をDBにinsertするためにpost_reply_finish.phpに渡す-->
        <form name="form1" action="post_reply_finish.php" method="post" onsubmit="return check()">
            <input type="hidden" name="reply_id" value="<?php echo $column['reply_id']?>">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <input type="hidden" name="panel_id" value="<?php echo $_GET['panel_id']?>">
            <input type="hidden" name="column_id" value="<?php echo $column['id']?>">
            <p>名前</p>
            <p><input type="text" name="name" placeholder="吾輩は猫である"></p>
            <p>本文</p>
            <p><textarea name="post_text" rows="10" cols="60"></textarea></p>
            <input type="submit" value="返信する">
        </form>
        <a href="index.php">戻る</a>
    </body>
</html>