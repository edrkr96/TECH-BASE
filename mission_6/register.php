<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>register</title>
    </head>
    <body>
        <div style="text-align:center">
        <h1><u>基本情報登録</u></h1>
        <form action="register_finished.php" method="post">
            名前(ニックネームでもOK)<br>
            <!-- requiredを入れることで、未入力の状態で遷移できないように-->
            <input type="text" name="name" required><br><br> 
            メールアドレス<br>
            <input type="text" name="mail" required><br><br>
            生年月日<br>
            <input type="date" name="birth_date" required><br><br>
            性別<br>
            <select name= "gender" required>
                <option value = "男性">男性</option>
                <option value = "女性">女性</option>
                <option value = "回答しない">回答しない</option>
            </select><br><br>
            パスワード<br>
            <input type="password" name="password" required><br><br>
            <input type="submit" name="register" value="完了"><br><br>
        </form>
        </div>
    </body>
</html>