<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>result</title>
        <link rel="stylesheet" href="arrangement.css">
    </head>
    <body>
        <?php
            // セッションの有効期限を管理する関数を用意
            function isLoggedIn() {
                return isset($_SESSION['id']);
            }
        
            session_start();

            if (!isLoggedIn()) {
                session_destroy();
                // 有効期限切れの場合はタイムアウト画面に遷移
                header("Location: timeout.php");
                exit();
            }

            // DB接続設定
            $dsn = 'mysql:dbname=データベース名;host=localhost';
            $user = 'ユーザ名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

            // 予測の際に登録した情報を記録するフォームを作成
            $sql = "CREATE TABLE IF NOT EXISTS user_input"
            ."(" 
            ."mail varchar(50) PRIMARY KEY,"
            ."savings INT,"
            ."start_working INT,"
            ."to_25 INT,"
            ."to_30 INT,"
            ."to_40 INT,"
            ."to_50 INT,"
            ."to_60 INT,"
            ."to_65 INT,"
            ."over_65 INT,"
            ."target_age INT,"
            ."target_amount INT,"
            ."living_expenses INT,"
            ."house INT,"
            ."first_child INT,"
            ."second_child INT,"
            ."third_child INT"
            .");";
            $stmt = $pdo -> query($sql);

            # 予測の際に登録した情報をDBに登録

            // 予測用の情報入力フォームから渡された値を代入
            $savings = $_POST['savings'];
            $start_working = $_POST['start_working'];
            $to_25 = $_POST['to_25'];
            $to_30 = $_POST['to_30'];
            $to_40 = $_POST['to_40'];
            $to_50 = $_POST['to_50'];
            $to_60 = $_POST['to_60'];
            $to_65 = $_POST['to_65'];
            $over_65 = $_POST['over_65'];
            $living_expenses = $_POST['living_expenses'];
            $house = $_POST['house'];
            $first_child = $_POST['first_child'];
            $second_child = $_POST['second_child'];
            $third_child = $_POST['third_child'];
            $target_age = $_POST['target_age'];
            $target_amount = $_POST['target_amount'];

            // メールアドレスが登録済み(予測を行なったことがある)かを確認 
            $mail = $_SESSION['mail'];
            $sql = "SELECT *FROM user_input WHERE mail=:mail";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(":mail", $mail, PDO::PARAM_STR);
            $stmt -> execute();
            $result = $stmt -> fetch();

            // 入力されたメールアドレスが登録済みの場合、そのメールアドレスの行のデータを更新
            if ($result != "") {
                
                // メールアドレスが一致する行に、フォームから受け取った値を上書き
                $sql = $pdo -> prepare('UPDATE user_input SET savings=:savings, start_working=:start_working, to_25=:to_25, to_30=:to_30, to_40=:to_40, to_50=:to_50, to_60=:to_60, to_65=:to_65, over_65=:over_65,
                living_expenses=:living_expenses, house=:house, first_child=:first_child, second_child=:second_child, third_child=:third_child, target_age=:target_age, target_amount=:target_amount WHERE mail=:mail');

                $sql -> bindParam(':savings', $savings, PDO::PARAM_INT); 
                $sql -> bindParam(':start_working', $start_working, PDO::PARAM_INT); 
                $sql -> bindParam(':to_25', $to_25, PDO::PARAM_INT); 
                $sql -> bindParam(':to_30', $to_30, PDO::PARAM_INT); 
                $sql -> bindParam(':to_40', $to_40, PDO::PARAM_INT); 
                $sql -> bindParam(':to_50', $to_50, PDO::PARAM_INT); 
                $sql -> bindParam(':to_60', $to_60, PDO::PARAM_INT); 
                $sql -> bindParam(':to_65', $to_65, PDO::PARAM_INT); 
                $sql -> bindParam(':over_65', $over_65, PDO::PARAM_INT); 
                $sql -> bindParam(':living_expenses', $living_expenses, PDO::PARAM_INT);
                $sql -> bindParam(':house', $house, PDO::PARAM_INT);
                $sql -> bindParam(':first_child', $first_child, PDO::PARAM_INT);
                $sql -> bindParam(':second_child', $second_child, PDO::PARAM_INT);
                $sql -> bindParam(':third_child', $third_child, PDO::PARAM_INT);
                $sql -> bindParam(':target_age', $target_age, PDO::PARAM_INT); 
                $sql -> bindParam(':target_amount', $target_amount, PDO::PARAM_INT);
                $sql -> bindParam(':mail', $mail, PDO::PARAM_STR); 
                $sql -> execute(); 

            // 入力されたメールアドレスが新規のものであれば、DBに登録
            } else {
                // フォームから受け取った値をDBに代入
                $sql = $pdo -> prepare('INSERT INTO user_input (mail, savings, start_working, to_25, to_30, to_40, to_50, to_60, to_65, over_65, target_age, target_amount, living_expenses, house, first_child, second_child, third_child)
                VALUES (:mail, :savings, :start_working, :to_25, :to_30, :to_40, :to_50, :to_60, :to_65, :over_65, :target_age, :target_amount, :living_expenses, :house, :first_child, :second_child, :third_child)');

                $sql -> bindParam(':mail', $mail, PDO::PARAM_STR); 
                $sql -> bindParam(':savings', $savings, PDO::PARAM_INT); 
                $sql -> bindParam(':start_working', $start_working, PDO::PARAM_INT); 
                $sql -> bindParam(':to_25', $to_25, PDO::PARAM_INT); 
                $sql -> bindParam(':to_30', $to_30, PDO::PARAM_INT); 
                $sql -> bindParam(':to_40', $to_40, PDO::PARAM_INT); 
                $sql -> bindParam(':to_50', $to_50, PDO::PARAM_INT); 
                $sql -> bindParam(':to_60', $to_60, PDO::PARAM_INT); 
                $sql -> bindParam(':to_65', $to_65, PDO::PARAM_INT); 
                $sql -> bindParam(':over_65', $over_65, PDO::PARAM_INT); 
                $sql -> bindParam(':living_expenses', $living_expenses, PDO::PARAM_INT);
                $sql -> bindParam(':house', $house, PDO::PARAM_INT);
                $sql -> bindParam(':first_child', $first_child, PDO::PARAM_INT);
                $sql -> bindParam(':second_child', $second_child, PDO::PARAM_INT);
                $sql -> bindParam(':third_child', $third_child, PDO::PARAM_INT);
                $sql -> bindParam(':target_age', $target_age, PDO::PARAM_INT); 
                $sql -> bindParam(':target_amount', $target_amount, PDO::PARAM_INT);
                $sql -> execute(); 
            }    

            // ユーザの情報を取り出す
            $id = $_SESSION['id'];
            $sql = "SELECT *FROM user_list WHERE id=:id";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(":id", $id, PDO::PARAM_INT);
            $stmt -> execute();
            $result = $stmt -> fetch();

            // 年齢の計算
            $birth_date = $result['birth_date'];
            // ハイフンを除いた文字列に変換
            $birth_date = str_replace("-", "", $birth_date);
            $now = date('Ymd');
            // 年の部分だけ残して少数部分を切り捨て
            $age = floor(($now - $birth_date) / 10000);
            // 計算するとき用に整数に変換
            $age = intval($age);
            
            // 年齢の配列と年齢別の予測貯金額用の配列を表示
            $age_array = range($age, 100); 
            $amount_array = array();

            foreach ( $age_array as $i) {

                # 年齢に応じて収入を加算
                if ($i == $age) {
                    $amount = $savings;
                } elseif ($i <= $start_working) {
                    $amount += 0;
                } elseif ($i <= 25) {
                    $amount += $to_25;
                } elseif ($i <= 30) {
                    $amount += $to_30;
                } elseif ($i <= 40) {
                    $amount += $to_40;
                } elseif ($i <= 50) {
                    $amount += $to_50;
                } elseif ($i <= 60) {
                    $amount += $to_60;
                } elseif ($i <= 65) {
                    $amount += $to_65;
                } elseif ($i > 65) {
                    $amount += $over_65;
                }
                
                if ($i > 22) {
                    # 生活費を減算
                    $amount -= $living_expenses;
                }

                # 住宅ローン(住宅ローンシミュレーションで4000万円、30年に設定)
                if ( $house != "" ) {
                    if ( $i >= $house && $i<= $house+29 ) {
                        $amount -= $house;
                    }
                }


                # 子どもの年齢に応じてかかる費用を引き算(三井住友カードのデータから取得)
                // 第一子(入力した場合)
                if ( $first_child != "" ) {
                    // 未就学児
                    if ( $i >= $first_child && $i<= $first_child+5 ) {
                        $amount -= 122; 
                    // 小学生
                    } elseif ( $i >= $first_child+6 && $i <= $first_child+11 ) {
                        $amount -= 32; 
                    // 中学生
                    } elseif ( $i >= $first_child+12 && $i <= $first_child+14 ) {
                        $amount -= 49;
                    // 高校生
                    } elseif ( $i >= $first_child+15 && $i <= $first_child+17 ) {
                        $amount -= 46;
                    // 大学生
                    } elseif ( $i >= $first_child+18 && $i <= $first_child+21 ) {
                        $amount -= 54; 
                    }
                }

                // 第二子(入力した場合)
                if ( $second_child != "" ) {
                    // 未就学児
                    if ( $i >= $second_child && $i <= $second_child+5 ) {
                        $amount -= 122; 
                    // 小学生
                    } elseif ( $i >= $second_child+6 && $i <= $second_child+11 ) {
                        $amount -= 32; 
                    // 中学生
                    } elseif ( $i >= $second_child+12 && $i <= $second_child+14 ) {
                        $amount -= 49;
                    // 高校生
                    } elseif ( $i >= $second_child+15 && $i <= $second_child+17 ) {
                        $amount -= 46;
                    // 大学生
                    } elseif ( $i >= $second_child+18 && $i <= $second_child+21 ) {
                        $amount -= 54; 
                    }
                }


                // 第三子(入力した場合)
                if ( $third_child != "" ) {
                    // 未就学児
                    if ( $i >= $third_child && $i <= $third_child+5 ) {
                        $amount -= 122; 
                    // 小学生
                    } elseif ( $i >= $third_child+6 && $i <= $third_child+11 ) {
                        $amount -= 32; 
                    // 中学生
                    } elseif ( $i >= $third_child+12 && $i <= $third_child+14 ) {
                        $amount -= 49;
                    // 高校生
                    } elseif ( $i >= $third_child+15 && $i <= $third_child+17 ) {
                        $amount -= 46;
                    // 大学生
                    } elseif ( $i >= $third_child+18 && $i <= $third_child+21 ) {
                        $amount -= 54; 
                    }
                }

                # 年齢に応じて医療費を減算(厚生労働省より)
                if ($i <= 44) {
                    $amount -= 10;
                } elseif ($i <= 64) {
                    $amount -= 25;
                } elseif ($i <= 69) {
                    $amount -= 67;
                } elseif ($i <= 74) {
                    $amount -= 76;
                } else {
                    $amount -= 83;
                } 

                // 年齢別の予測貯金額を配列に追加
                array_push($amount_array, $amount);

            }
        ?>
        
        <div style="text-align:center">
        <h2>   
        <?php

            // ターゲット年齢での貯金予測額を取得
            $index = array_search($target_age, $age_array);
            $target_predict = $amount_array[$index];

            // 目標額と予測額の差額を計算
            $gap = $target_predict - $target_amount;
            
            # 予測結果の視覚化
            // 目標額を表示するための配列を作成
            $target_array = [];
            $len = count($age_array);
            $target_amount = intval($target_amount);

            for ( $i = 0; $i < $len; $i++) {
                array_push($target_array, $target_amount);
            }

            //var_dump($age_array);
            //var_dump($amount_array);
            //var_dump($target_array);

            //javascriptに配列を渡す
            $x = json_encode($age_array);
            $y1 = json_encode($amount_array);
            $y2 = json_encode($target_array);

        ?>
        
        <!-- 結果を文で表示 -->
        <?php echo $target_age; ?>歳における貯金予測額：
        <u><?php echo $target_predict; ?>万円</u><br>

        <br>

        <?php if ( $gap >= 0 ) { echo "目標額との差額：";}  ?>
        <span style="color:blue"><?php if ( $gap >= 0 ) { echo "+{$gap}万円<br><br>";}  ?></span>
        
        <?php if ( $gap < 0 ) { echo "目標額との差額：";}  ?>
        <span style="color:red"><?php if ( $gap < 0 ) { echo "{$gap}万円<br><br>";}  ?></span>
        </h2>
        
        <!-- グラフを表示 -->
        <h2><span style="background-color:yellow">&nbsp;貯金額の推移(万円)&nbsp;</span></h2>
        <div class="chart">
        <div style="position:relative;width:600px;height:480px;">
            <canvas id="myChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
        // PHPから値を受け取る
        let x = JSON.parse('<?php echo $x; ?>');
        let y1 = JSON.parse('<?php echo $y1; ?>');
        let y2 = JSON.parse('<?php echo $y2; ?>');
    
        // グラフを表示
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
            labels: x,
            label: '年齢',
                datasets: [
                    {
                    label: '予測された貯金額',
                    data: y1,
                    borderColor: "black",
                    backgroundColor: "white",
                    },
                    {
                    label: '目標貯金額',
                    data: y2,
                    borderColor: "red",
                    backgroundColor: "white",
                    }
                ],
            },
            options: {
                title: {
                    display: false,
                    text: '予測された貯金額の推移'
                },
                elements: {
                    point:{
                    radius: 0
                    }
                },
                legend: {
                    display: true,
                    position: "right",     
                }
            }
         });
        </script>
        </div>

        </div> 

    </body>
</html>