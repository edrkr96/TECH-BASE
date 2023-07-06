<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mypage</title>
    </head>
    <body>
        <?php
            // セッションの有効期限を管理する関数を用意
            function isLoggedIn() {
                return isset($_SESSION['id']);
            }
        ?>
        <div style="text-align:center">
        <?php
            session_start();         

            if (!isLoggedIn()) {
                session_destroy();
                // 有効期限切れの場合はタイムアウト画面に遷移(有効期限はページごとにカウントし直される)
                header("Location: timeout.php");
                exit();
            }
            
            $id = $_SESSION['id'];
            $name = $_SESSION['name'];

            // DB接続設定
            $dsn = 'mysql:dbname=データベース名;host=localhost';
            $user = 'ユーザ名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

            $sql = "SELECT *FROM user_list WHERE id=:id";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
            $stmt -> execute();
            $result = $stmt -> fetch();

        ?>

        
        <h1><u><?php echo "{$name}"?>さんのマイページ</u></h1>
        
        <h2><span style="background-color:alice blue">メニュー</span></h2>
        <form action="predict.php" method="post">
            <input type="submit" value="貯金額の予測を行う"><br><br>
        </form>
        </div>
    </body>
</html>