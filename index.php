<?php
try{
    // データベースへ接続
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
        <title>C_B_BBS</title>
        <script tipe="text/javascript">
        function check(){
            var flag = 0;
            if(document.form1.name.value == ""){
                flag = 1;
            }else if(document.form1.post_text.value == ""){
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
        <div class="header">
            <form name="form1" action="post_finish.php" method="post" onsubmit="return check()">
                <p>名前</p>
                <p><input type="text" name="name" placeholder="吾輩は猫である"></p>
                <p>スレッドタイトル</p>
                <p><textarea name="post_text" rows="5" cols="60"></textarea></p>
                <input type="submit" value="投稿する">
                <p></p>
            </form>
        </div>
        <div class="main">
            <h2>スレッド一覧</h2>
            <table border="1" width="100%">
                <tr>
                    <th>スレ主</th>
                    <th>投稿日時</th>
                    <th colspan="2">スレッド名</th>
                </tr>
                <?php
                define('MAX',5); // 一頁あたりの表示数

                $i=0;
                $index = array();
                foreach($pdo->query('select * from post_nakamoto where reply_id is null order by id desc') as $row){
                    $index[$i] = $row;
                    $i++;
                } // $row内に、テーブルの内容すべてを行ごとに代入し、それを$indexでarray化

                $index_num = count($index); // $indexの件数
                $max_page = ceil($index_num / MAX); // を表示数で割って頁数

                // アドレス末尾のpage_id=部分から、今何ページ目を表示しているかを参照
                if(!isset($_GET['page_id'])){
                    $now = 1;
                }else{
                    $now = $_GET['page_id'];
                }

                $start_no = ($now - 1) * MAX; // 画面に何件目から表示するか(1頁目は(1-1)*5で[0]、2頁目は(2-1)*5で[5]から)

                // 一項ごとに$rowが配列された$index[]を、MAX件ごとに腑分けして$start_no件目から表示する
                $index_data = array_slice($index,$start_no,MAX,true);
                    foreach($index_data as $val):?>
                <form action="reply.php?panel_id=<?php echo $val['id']?>" method="post">
                    <tr> <!-- $val内の各カラム名を指定して、その内容を表示 -->
                        <input type="hidden" name="id" value="<?php echo $val['id']?>">
                        <td class="name" width="15%"><?php echo $val['name'];?></td>
                        <td class="post_date" width="20%"><?php echo $val['post_date']; ?></td>
                        <td class="post_text" width="60%"><?php echo $val['post_text']; ?></td>
                        <td class="reply" width="5%"><input type="submit" value="返信"></td>
                    </tr>
                </form>
                <?php endforeach ?>
            </table>
        </div>

        <div class="footer">
            <?php
            for($i=1;$i<=$max_page;$i++){
                if($i==$now){
                    echo $now.' ';
                }else{
                    echo '<a href=\'/nakamoto/index.php?page_id='.$i.'\')>'.$i.'</a>'.' ';
                }
            }
            ?>
        </div>
    </body>
</html>