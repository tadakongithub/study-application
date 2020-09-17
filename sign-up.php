<?php

require 'session.php';

require 'db.php';

$errorMessage = "";
$signUpMessage = "";

if (isset($_POST["sign-up"])) {
    if (empty($_POST["username"])) {
        $errorMessage = 'Enter username';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'Enter password';
    } else if (empty($_POST["password2"])) {
        $errorMessage = 'Enter password';
    } else if($_POST["password"] !== $_POST["password2"]) {
        $errorMessage = 'Your first and second password didn\'t match';
    }
    $stmt = $db->prepare('SELECT * FROM user WHERE name = :username');
    $stmt->execute(array(
        ':username' => $_POST['username']
    ));
    $userAlreadyExist = $stmt->fetchAll();
    $countOfSameUserName = count($userAlreadyExist);
    if($countOfSameUserName > 0){
        $errorMessage = "This username is taken";
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"] && $countOfSameUserName === 0) {
        $username = $_POST["username"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO user(name, password) VALUES (:name, :password)");
        $stmt->execute(array(
            ':name' => $username,
            ':password' => $password
        ));
        header('Location: login.php');
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
                <input type="submit" id="signUp" name="sign-up" value="SIGN UP">
            
        </form>
        <br>

    </body>
</html>