<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>register_finished</title>
        <link rel="stylesheet" href="arrangement.css">
    </head>
    <body>
        <?php
            // DB接続設定
            $dsn = 'mysql:dbname=データベース名;host=localhost';
            $user = 'ユーザ名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            $blank = True;

            $sql = "CREATE TABLE IF NOT EXISTS user_list"
            ."(" 
            ."id INT AUTO_INCREMENT PRIMARY KEY,"
            ."name varchar(20) NOT NULL," 
            ."mail varchar(50) NOT NULL,"
            ."birth_date varchar(11) NOT NULL,"
            ."gender enum('男性','女性', '回答しない') NOT NULL,"
            ."password varchar(20) NOT NULL" 
            .");";
            $stmt = $pdo -> query($sql);

            // 入力されたメールアドレスが存在しているかを確認
            $input_mail = $_POST["mail"];

            $sql = "SELECT *FROM user_list WHERE mail=:mail";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(":mail", $input_mail, PDO::PARAM_STR);
            $stmt -> execute();
            $result = $stmt -> fetch();

            // 入力されたメールアドレスが重複していた場合、その旨を表示する画面へ遷移
            if ($result != "") {
                header("Location:incorrect_register.php");
            // 入力されたメールアドレスが新規のものであれば、DBに代入し、登録完了画面に遷移
            } else {
                // フォームから受け取った値をDBに代入
                $sql = $pdo -> prepare("INSERT INTO user_list (name, mail, birth_date, gender, password)VALUES(:name, :mail, :birth_date, :gender, :password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':mail', $mail, PDO::PARAM_STR);
                $sql -> bindParam(':birth_date', $birth_date, PDO::PARAM_STR);
                $sql -> bindParam(':gender', $gender, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);

                $name = $_POST["name"]; 
                $mail = $_POST["mail"];
                $birth_date = $_POST["birth_date"];
                $gender = $_POST["gender"];
                $password = $_POST["password"];
                $sql -> execute(); // テーブルへの値の代入
            }    
            
        ?>
        <div class="upper">
        登録が完了しました<br>
        ログイン画面に戻ってログインしてください<br><br>
        </div>

        <div class="lower">
        <form action="initial_screen.php" method="post">
        <input type="submit" value="ログイン画面に戻る"><br>
        </form>
        </div>

    </body>
</html>