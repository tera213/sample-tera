<?php
error_reporting('E_ALL');
ini_set('display_errors', 'On');


if(!empty($_POST)){

    define('MSG01', '※メールアドレスまたはパスワードが間違っています。');

    $err_msg = array();

    //メールアドレス、パスワードともに値が入っているかのチェック
    if(empty($_POST['email']) && !empty($_POST['email'])){
        $err_msg['msg01'] = MSG01;
    }

    if(empty($err_msg['msg01'])){

        $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);

        //メールのバリデーションチェック
        if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)){
            $err_msg['msg01'] = MSG01;
        }

        if(empty($err_msg)){
            
            //DBへの接続
            $dsn = 'mysql:dbname=sample_op;host=localhost;charset=utf8';
            $user = 'root';
            $dbPassword = 'root';
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
            );

            $dbh = new PDO($dsn, $user, $dbPassword, $options);

            $stmt = $dbh -> prepare('SELECT * FROM users WHERE email = :email AND password = :password');

            $stmt -> execute(array(':email' => $email, ':password' => $password));

            $result = 0;

            $result = $stmt -> fetch(PDO::FETCH_ASSOC);

            //結果が0ではない場合
            if(!empty($result)){

                session_start();
                $_SESSION['login'] = true;

                //マイページに遷移
                header("Location:mypage.html");
            }else{
                $err_msg['msg01'] = MSG01;
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
    <title>ログイン</title>
    <link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="reset.css">
</head>
<body>
    <div class="wrap">
        <h1>ログイン</h1>
        <form action="login.php" method="POST">
            <div class="form-item">
                <input type="text" name="email" placeholder="メールアドレス" class="valid-email" value="<?php if(!empty($_POST['email'])) echo htmlspecialchars($_POST['email']) ?>">
            </div>
            <div class="form-item">
                <input type="password" name="password" placeholder="パスワード" class="valid-password" value="<?php if(!empty($_POST['password'])) echo htmlspecialchars($_POST['password']) ?>">
            </div>
            <div class="form-buton">
                <input type="submit" value="ログイン">
                <span class="err_msg"><?php if(!empty($err_msg['msg01'])) echo $err_msg['msg01'] ?></span>
            </div>
        </form>
        <div class="form-footer">
            <p><a href="regist.php">新規登録</a></p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="login.js"></script>
</body>
</html>