<?php
require 'password.php';   // password_hash()はphp 5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
// セッション開始
session_start();

$db['host'] = "localhost";  // DBサーバのURL
$db['user'] = "root";  // ユーザー名
$db['pass'] = "root";  // ユーザー名のパスワード
$db['dbname'] = "study_app";  // データベース名

$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

$sthandler = $pdo->prepare("SELECT * FROM user WHERE name = :name");
            $sthandler->bindParam(':name', $_POST["username"]);
            $sthandler->execute();
// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'Enter username';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'Enter password';
    } else if (empty($_POST["password2"])) {
        $errorMessage = 'Enter password';
    } else if($sthandler->rowCount() > 0){
        $errorMessage = "This username is taken";
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"] && $sthandler->rowCount() === 0) {
        // 入力したユーザIDとパスワードを格納
        $username = $_POST["username"];
        $password = $_POST["password"];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        

        // 3. エラー処理
        try {
            
            $stmt = $pdo->prepare("INSERT INTO user(name, password) VALUES (?, ?)");

            $stmt->execute(array($username, password_hash($password, PASSWORD_DEFAULT)));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            $userid = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

            $signUpMessage = 'You have successfully signed up! Your username is '. $username. ' . Your password is '. $password. ' .';  // ログイン時に使用するIDとパスワード
        } catch (PDOException $e) {
            $errorMessage = 'Database Error';
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
    } else if($_POST["password"] != $_POST["password2"]) {
        $errorMessage = 'Your first and second password didn\'t match';
    }
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="style.css">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <script src="jquery-3.4.1.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
            <title>新規登録</title>
    </head>

    <body>
        <header>
            <img src="img/logo.png" alt="" id="logo_2">
            <button onclick="location.href = 'login.php';" id="login">LOGIN</button>
        </header>

        <form id="loginForm" name="loginForm" action="" method="POST">
            
                <legend>Registration Form</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                <div class="form-group">
                <label for="username">USERNAME</label>
                <input type="text" id="username" class="form-control" name="username" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                </div>
                <br>
                <div class="form-group">
                <label for="password">PASSWORD</label>
                <input type="password" id="password" class="form-control" name="password" value="">
                </div>
                <br>
                <div class="form-group">
                <label for="password2">PASSWORD RE-ENTER</label>
                <input type="password" id="password2" class="form-control" name="password2" value="">
                </div>
                <br>
                <input type="submit" id="signUp" name="signUp" value="SIGN UP">
            
        </form>
        <br>

    </body>
</html>