<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');


if(!empty($_POST)){

    define('MSG01', '入力必須です。');
    define('MSG02', 'Emailの形式で入力してください。');
    define('MSG03', 'パスワード（再入力）が合っていません。');
    define('MSG04', '半角英数字のみご利用いただけます。');
    define('MSG05', '6文字以上で入力してください。');
    define('MSG06', 'このメールアドレスはすでに登録されています。');

    $err_msg = array();

    //必須項目が入力されていない場合
    if(empty($_POST['name'])){
        $err_msg['name'] = MSG01;
    }
    
    if(empty($_POST['email'])){
        $err_msg['email'] = MSG01;
    }
    
    if(empty($_POST['password'])){
        $err_msg['password'] = MSG01;
    }
    
    if(empty($_POST['password-retype'])){
        $err_msg['password_retype'] = MSG01;
    }
    var_dump($err_msg);

    if(empty($err_msg)){

        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $passwordRetype = $_POST['password-retype'];

        //3.emailの形式でない場合
        if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)){
            $err_msg['email'] = MSG02;
        }

        //4.パスワードとパスワード再入力が合っていない場合
        if($password !== $passwordRetype){
            $err_msg['password'] = MSG03;
        }

        if(empty($err_msg)){

            //5.パスワードとパスワード再入力が半角英数字ではない場合
            if(!preg_match("/^[a-zA-Z0-9]+$/", $password)){
                $err_msg['password'] = MSG04;
            }elseif(mb_strlen($password) < 6){
            //6.パスワードとパスワード再入力が6文字以上でない場合
                $err_msg['password'] = MSG05;
            }

            if(empty($err_msg)){

                //DBへの接続準備
                $dsn = 'mysql:dbname=sample_op;host=localhost;charset=utf8';
                $user = 'root';
                $dbPassword = 'root';
                $options = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                );

                //PDDオブジェクト生成
                $dbh = new PDO($dsn, $user, $dbPassword, $options);

                //メール重複チェック
                $stmt = $dbh -> prepare('SELECT * FROM users WHERE email = :email');
                $stmt -> execute(array(':email' => $email));

                $result = false;
                $result = $stmt->fetch(PDO::FETCH_ASSOC);


                //結果が0ではない場合メールアドレスが重複している
                if($result !== false){
                    $err_msg['email'] = MSG06;
                }else{
                    //登録処理
                    //SQL文（クエリー作成）
                    $stmt = $dbh -> prepare('INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)');

                    //プレースホルダーに値をセットし、SQL文を実行
                    $stmt->execute(array(':name' => $name, ':email' => $email, ':phone' => $phone, ':password' => $password));

                    //header("Location:mypage.html");
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録画面</title>
    <link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="reset.css">
</head>
<body>
    <div class="wrap wrap-index">
        <h1>登録</h1>
        <form action="redist.php" method="POST">
            <div class="form-item">
                <span class="help-block"><?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?></span>
                <input type="text" name="name" placeholder="お名前  ※必須" class="valid-name" value="<?php if(!empty($_POST['name'])) echo $_POST['name'] ?>">
            </div>
            <div class="form-item">
                <span class="help-block"><?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?></span>
                <input type="text" name="email" placeholder="メールアドレス  ※必須" class="valid-email" value="<?php if(!empty($_POST['email'])) echo $_POST['email'] ?>">
            </div>
            <div class="form-item">
                <span class="help-block"><?php if(!empty($err_msg['phone'])) echo $err_msg['phone']; ?></span>
                <input type="text" name="phone" placeholder="電話番号" class="valid-phone" value="<?php if(!empty($_POST['phone'])) echo $_POST['phone'] ?>">
            </div>
            <div class="form-item">
                <span class="help-block"><?php if(!empty($err_msg['password'])) echo $err_msg['password']; ?></span>
                <input type="password" name="password" placeholder="パスワード  ※必須" class="valid-password" value="<?php if(!empty($_POST['password'])) echo $_POST['password'] ?>">
            </div>

            <div class="form-item">
                <span class="help-block"></span>
                <input type="password" name="password-retype" placeholder="パスワード(再入力)  ※必須" class="valid-password-retype" value="<?php if(!empty($_POST['password-retype'])) echo $_POST['password-retype'] ?>">
            </div>
            <div class="form-buton">
                <input type="submit" value="登録">
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="regist.js"></script>
</body>
</html>