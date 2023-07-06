<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>predict</title>
    </head>
    <body>
        <?php
            // セッションの有効期限を管理する関数を用意
            function isLoggedIn() {
                return isset($_SESSION['id']);
            }
            
            // セッションを再開
            session_start();

            if (!isLoggedIn()) {
                // 有効期限切れの場合はタイムアウト画面に遷移(有効期限はページごとにカウントし直される)
                session_destroy();
                header("Location: timeout.php");
                exit();
            }

            // DB接続設定
            $dsn = 'mysql:dbname=データベース名;host=localhost';
            $user = 'ユーザ名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


            // テーブルが作成済みかを確認
            $sql = 'SHOW TABLES'; 
            $tables = $pdo -> query($sql); 
            $table_exist = "";
            $result = "";

            foreach ($tables as $table) {
                if ( $table[0] == "user_input" ) {
                    $table_exist = True;
                }
            }

            if ( $table_exist == True ) {
                // メールアドレスが登録済み(予測を行なったことがある)かを確認 
                $mail = $_SESSION['mail'];
                $sql = "SELECT *FROM user_input WHERE mail=:mail";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(":mail", $mail, PDO::PARAM_STR);
                $stmt -> execute();
                $result = $stmt -> fetch();
            }

            // フォームに表示するための変数を定義
            $savings = "";
            $start_working = "";
            $to_25 = "";
            $to_30 = "";
            $to_40 = "";
            $to_50 = "";
            $to_60 = "";
            $to_65 = "";
            $over_65 = "";
            $living_expenses = "";
            $house="";
            $first_child = "";
            $second_child = "";
            $third_child = "";
            $target_age = "";
            $target_amount = "";


            if ($result != "") {
                $savings = $result['savings'];
                $start_working = $result['start_working'];
                $to_25 = $result['to_25'];
                $to_30 = $result['to_30'];
                $to_40 = $result['to_40'];
                $to_50 = $result['to_50'];
                $to_60 = $result['to_60'];
                $to_65 = $result['to_65'];
                $over_65 = $result['over_65'];
                $living_expenses = $result['living_expenses'];
                $house = $result['house'];
                $first_child = $result['first_child'];
                $second_child = $result['second_child'];
                $third_child = $result['third_child'];
                $target_age = $result['target_age'];
                $target_amount = $result['target_amount'];
            }
        ?>

        <div style="text-align:center">
        <h2>予測に必要な情報を入力してください<br></h2>
        <!-- 入力されたメールアドレスが登録済みの場合、前回の入力をフォームに表示 -->
        <form action="result.php" method="post">
            現在までの貯金額
            <input type="number" name="savings" value="<?php echo $savings; ?>" required>
            万円<br><br>
            働き始める年齢
            <input type="number" name="start_working" value="<?php echo $start_working; ?>" required>
            歳<br><br>
            25歳までの年収
            <input type="number" name="to_25" value="<?php echo $to_25; ?>" required>
            万円<br>
            30歳までの年収
            <input type="number" name="to_30" value="<?php echo $to_30; ?>" required>
            万円<br>
            40歳までの年収
            <input type="number" name="to_40" value="<?php echo $to_40; ?>" required>
            万円<br>
            50歳までの年収
            <input type="number" name="to_50" value="<?php echo $to_50; ?>" required>
            万円<br>
            60歳までの年収
            <input type="number" name="to_60" value="<?php echo $to_60; ?>" required>
            万円<br>
            65歳までの年収
            <input type="number" name="to_65" value="<?php echo $to_65; ?>" required>
            万円<br>
            1年あたりの年金
            <input type="number" name="over_65" value="<?php echo $over_65; ?>" required>
            万円<br><br>
            一年あたりの生活費(子育て費用は除く)
            <input type="number" name="living_expenses" value="<?php echo $living_expenses; ?>" required>
            万円<br><br>
            マイホーム購入(希望)年齢
            <input type="number" name="house" value="<?php echo $house; ?>">
            歳<br><br>
            第一子を産みたい(産んだ)年齢
            <input type="number" name="first_child" value="<?php echo $first_child; ?>">
            歳<br>
            第二子を産みたい(産んだ)年齢
            <input type="number" name="second_child" value="<?php echo $second_child; ?>">
            歳<br>
            第三子を産みたい(産んだ)年齢
            <input type="number" name="third_child" value="<?php echo $third_child; ?>">
            歳<br><br>
            <input type="number" name="target_age" value="<?php echo $target_age; ?>" required>
            歳までの目標額
            <input type="number" name="target_amount" value="<?php echo $target_amount; ?>" required>
            万円<br><br>
            <input type="submit" name="predict" value="予測結果を表示"><br>
        </form>

        </div>
    </body>
</html>