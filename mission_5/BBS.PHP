<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>BBS</title>  
        <style>
            .class1{
                background-color:aliceblue;
                text-align:center;
            }
        </style>  
    </head>
    <body>
        <?php
            // DB接続設定
            $dsn = 'mysql:dbname=データベース名;host=localhost';
            $user = 'ユーザ名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

            // DB内にテーブルを作成
            $sql = "CREATE TABLE IF NOT EXISTS tb_bbs" // ハイフンはテーブル名に含めない
            ."("
            ."id INT AUTO_INCREMENT PRIMARY KEY,"
            ."name char(32),"
            ."comment TEXT,"
            ."date_and_time TEXT,"
            ."password char(32)"
            .");";
            $stmt = $pdo -> query($sql);

            // 表示用の変数に初期値を代入
            $dis_name = "";
            $dis_comment = "";
            $dis_pass = "";

            // 投稿か編集かを判断するための変数
            $mode_num = "";

            // 不適切な入力のコメントをフォームの下に表示するための変数
            $pass_correct = "";
            $ed_num_exists = "";

            // 編集時に入力フォームへ値を受け渡す
            if ( !empty($_POST["ed_num"]) && !empty($_POST["ed_pass"]) ) {
                $ed_num = $_POST["ed_num"];
                $ed_pass = $_POST["ed_pass"];

                // 投稿内容の取得
                $sql = 'SELECT *FROM tb_bbs';
                $stmt = $pdo -> query($sql);
                $results = $stmt -> fetchAll();

                foreach ($results as $row) {
                    
                    // 入力された番号が存在するか確認
                    if ( $row['id'] == $ed_num ) {
                        $ed_num_exists = "T";
                        $org_name = $row['name']; // 該当番号の投稿の名前を取得
                        $org_comment = $row['comment']; // 該当番号の投稿のコメントを取得
                        $org_pass = $row['password']; // 該当番号の投稿のパスワードを取得
                    }
                }

                if ( $ed_num_exists == "T" ) { // 編集対象番号が存在し、パスワードが一致した時のみ編集
                
                    if ( $ed_pass == $org_pass ) {

                        $dis_name = $org_name;
                        $dis_comment = $org_comment;
                        $dis_pass = $org_pass;
                        $mode_num = $ed_num;
                        $pass_correct = "T";
                    
                    } else {
                        $pass_correct = "F"; // 初めからこのエラー文が出ないように3種類のパターン分け
                    }
                } else {
                    $ed_num_exists = "F";
                }
            }
        ?>

        <div class="class1">
        <hr>
        <h1>
        <marquee direction="right" scrollAmount="10" loop="10" height="30">&#x1f6eb;</marquee>
        <span style="color:rgb(104, 117, 233">
        🌎&nbsp;行ってみたい国を書き込んでみてね&nbsp;🌏
        </span>
        </h1>
        <div style="display:inline-flex"> <!-- フォームを横並びに -->
        <form action="" method="post">
            <h2><u>投稿フォーム</u></h2>
            名前<br>
            <input type="text" name="name" value="<?php echo $dis_name; ?>"><br>
            コメント<br>
            <input type="text" name="comment" value="<?php echo $dis_comment; ?>"><br>
            パスワード<br>
            <input type="password" name="pass" value="<?php echo $dis_pass; ?>"><br>
            <input type="submit" name="submit"><br>
            <!-- モードの判定 -->
            <input type="hidden" name="mode" value="<?php echo $mode_num; ?>"><br>
        </form>
        <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2>
        <form action="" method="post">
            <h2><u>削除フォーム</u></h2>
            削除対象番号<br>
            <input type="number" name="del_num"><br>
            パスワード<br>
            <input type="password" name="del_pass"><br>
            <input type="submit" value="削除"><br>
        </form>
        <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2>
        <form action="" method="post">
            <h2><u>編集フォーム</u></h2>
            編集対象番号<br>
            <input type="number" name="ed_num"><br>
            パスワード<br>
            <input type="password" name="ed_pass"><br>
            <input type="submit" value="編集"><br>
        </div>
        <br>


        <?php
            // 編集時の入力間違いや空入力へのエラー表示
            if ( $pass_correct == "F") {
                echo "---パスワードが間違っています---<br><br>";
            } elseif ( $ed_num_exists == "F" ) {
                echo "---入力された番号は存在しません---<br><br>";
            } 
            if ( isset($_POST["ed_num"]) && empty($_POST["ed_num"]) ) {
                echo "---編集対象番号が入力されていません---<br><br>";
            } elseif ( isset($_POST["ed_pass"]) && empty($_POST["ed_pass"]) ) {
                echo "---パスワードが入力されていません---<br><br>";
            } 

            // 投稿時の入力間違いや空入力へのエラー表示
            if (( isset($_POST["name"]) && empty($_POST["name"]) )) {
                echo "---名前が入力されていません---<br><br>";
            } elseif ( isset($_POST["comment"]) && empty($_POST["comment"])) {
                echo "---コメントが入力されていません---<br><br>";
            } elseif ( isset($_POST["pass"]) && empty($_POST["pass"])) {
                echo "---パスワードが入力されていません---<br><br>";
            }

            // 投稿 or 編集
            if ( !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) ) {

                // 投稿
                // モード番号が空
                if ( $_POST["mode"] == "" ) {

                    // フォームから受け取った値をテーブルに代入
                    $sql = $pdo -> prepare("INSERT INTO tb_bbs (name, comment, date_and_time, password)VALUES(:name, :comment, :date_and_time, :pass)");
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':date_and_time', $date_and_time, PDO::PARAM_STR);
                    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

                    // 入力フォームから値が送信され、それぞれの値が空ではない時に、値をテーブルに代入
                    if ( isset($_POST["name"]) && !empty($_POST["name"]) ) {
                        $name = $_POST["name"]; 
                        if ( isset($_POST["comment"]) && !empty($_POST["comment"]) ) {
                            $comment = $_POST["comment"];
                            if ( isset($_POST["pass"]) && !empty($_POST["pass"])) {
                                $date_and_time = date("Y/m/d H:i:s");
                                $pass = $_POST["pass"];
                                $sql -> execute(); // テーブルへの値の代入
                                echo "{$name}さんが「{$comment}」を投稿しました<br><br>";
                            } 
                        }
                    }

                // 編集
                // モード番号が空でない
                } else {

                    $id = $_POST["mode"];
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $date_and_time = date("Y/m/d H:i:s");
                    $pass = $_POST["pass"];

                    $sql = "UPDATE tb_bbs SET name=:name, comment=:comment, date_and_time=:date_and_time, password=:pass WHERE id=:id";
                    $stmt = $pdo -> prepare($sql);
                    $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt -> bindParam(':date_and_time', $date_and_time, PDO::PARAM_STR);
                    $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt -> execute();
                    echo "{$id}番の投稿が編集されました<br><br>";
                }


            // 削除
            // 入力フォームから値が送信され、それぞれの値が空ではない時に、値をテーブルに代入
            } elseif ( isset($_POST["del_num"]) && !empty($_POST["del_pass"]) ) {

                $del_num = $_POST["del_num"];
                $del_pass = $_POST["del_pass"];
                $del_num_exists = False;
                $org_pass = "";

                // 投稿内容の取得
                $sql = 'SELECT *FROM tb_bbs';
                $stmt = $pdo -> query($sql);
                $results = $stmt -> fetchAll();

                foreach ($results as $row) {

                    // 入力された番号が存在するかを確認
                    if ( $row['id'] == $del_num ) {
                        $del_num_exists = True;
                        $org_pass = $row['password']; // 該当番号の投稿のパスワードを取得
                    } 
                }

                if ( $del_num_exists == True) {
                    if ( $del_pass == $org_pass ) { // 削除対象番号が存在し、パスワードが一致した時のみ削除

                        $id = $del_num; // 全ての条件を満たして初めてidを代入
                        $sql = "DELETE FROM tb_bbs WHERE id=:id";
                        $stmt = $pdo -> prepare($sql);
                        $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt -> execute();
                        echo "{$id}番の投稿が削除されました<br><br>";

                    } else {
                        echo "---パスワードが間違っています---<br><br>";
                    }
                } else {
                    echo "---入力された番号は存在しません---<br><br>";
                }

            } elseif ( isset($_POST["del_num"]) && empty($_POST["del_num"]) ) {
                echo "---削除対象番号が入力されていません---<br><br>";
            }  elseif ( isset($_POST["del_pass"]) && empty($_POST["del_pass"])) {
                echo "---パスワードが入力されていません---<br><br>";
            }


            // 投稿内容の表示
            $sql = 'SELECT *FROM tb_bbs';

            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();

            echo "【投稿内容一覧】<br>";

            foreach ($results as $row) {
                echo $row['id']. ' ';
                echo $row['name']. ' ';
                echo $row['comment']. ' ';
                echo $row['date_and_time']. '<br>';
            }
        ?>
        <h1>
        <marquee direction="left" scrollAmount="10" loop="10" height="30">&#x1f6f3;</marquee>
        </h1>
        <?php
            echo '<hr>';
        ?>
        </div>
    </body>
</html>