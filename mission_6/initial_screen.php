<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>initial_screen</title>
    </head>
    <body>
        <?php
            // DB接続設定
            $dsn = 'mysql:dbname=データベース名;host=localhost';
            $user = 'ユーザ名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            $org_pass = "";

            if ( isset($_SESSION['id']) or isset($_SESSION['register']) ) {
                session_destroy();
            }
        
            $session_lifetime = 30 * 60; // セッションの有効期限を30分に設定(他のページでも適用される)
            //$session_lifetime = 10;
            session_set_cookie_params($session_lifetime);
            session_start(); 
        ?>

        <div style="text-align:center">
        <h1>💵 貯金額予測ツール 💵</h1>
        <span style="background-color:yellow">
        このツールはユーザー限定で使用可能です。<br>
        未登録の方はユーザー登録してください！<br>
        </span>
        <br>
        <div style="display:inline-flex">
        <form action="" method="post">
            <h2><u>ログイン</u></h2>
            メールアドレス<br>
            <input type="text" name="mail" required><br>
            パスワード<br>
            <input type="password" name="password" required><br><br>
            <input type="submit" name="login" value="ログイン"><br>
        </form>
        <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2>
        <form action="register.php" method="post">
            <h2><u>初めての方はこちら</u></h2>
            <input type="submit" name="register" value="新規会員登録"><br><br>
        </form>
        </div>
        <br><br>

        <h1>
        <?php
            if ( isset($_POST["login"]) ) {
                /*
                //$session_lifetime = 30 * 60; // セッションの有効期限を30分に設定(他のページでも適用される)
                $session_lifetime = 10;
                session_set_cookie_params($session_lifetime);
                session_start(); */

                $input_mail = $_POST["mail"];
                $input_pass = $_POST["password"];

                $sql = "SELECT *FROM user_list WHERE mail=:mail";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(":mail", $input_mail, PDO::PARAM_STR);
                $stmt -> execute();
                $result = $stmt -> fetch();
                
                // 入力されたメールアドレスが存在するかを確認
                if ( $result == "" ) { // 空文字はfalse扱い　データがない場合に空文字の方が直感的にわかりやすいと考えた
                    echo "入力されたメールアドレスは登録されていません";
                } else {
                    $org_pass = $result['password'];
                    
                    // 入力されたパスワードが正しいかを確認
                    if ( $input_pass == $org_pass) {
                        // ログイン成功時にセッションIDを再生成することでセッションハイジャックを防ぐ
                        session_regenerate_id(TRUE);
                        $_SESSION['id'] = $result['id'];
                        $_SESSION['name'] = $result['name'];
                        $_SESSION['mail'] = $result['mail'];
                        header("Location: mypage.php");
                    } else {
                        echo "パスワードが間違っています";
                    }
                } 
            } 
        ?>
        </h1>
        </div>
    </body>
</html>