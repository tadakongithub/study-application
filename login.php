<?php

require 'session.php';

require 'db.php';

$errorMessage = "";

if (isset($_POST["login"])) {
    if (empty($_POST["username"])) {
        $errorMessage = 'User name not entered';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'Password not entered';
    } 
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $stmt = $db->prepare('SELECT * FROM user WHERE name = :username');
        $stmt->execute(array(':username' => $username));
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $row['password'])) {             
                $_SESSION["ID"] = $row['id'];
                header("Location: index.php");
            } else {
                $errorMessage = 'Wrong password';
            }
        } else {
            $errorMessage = 'This User Name does not exist';
        }
    }
}
?>

<!doctype html>
<html>
    <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="style.css">
            <meta name="viewport" content="width=device-width, initial-scale=1.0"> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <script src="jquery-3.4.1.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

            <title>LOGIN</title>
    </head>
    <body>
    
        <header>
            <img src="img/logo.png" alt="" id="logo_2">
            <button onclick="location.href = 'sign-up.php';" id="login">SIGN UP</button>
        </header>

        
        <form id="loginForm" name="loginForm" action="" method="POST">
           
                <legend>LOGIN FORM</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div class="form-group">
                <label for="username">USERNAME</label><input type="text" id="username" class="form-control" name="username" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                </div>
                <br>
                <div class="form-group">
                <label for="password">PASSWORD</label><input type="password" id="password" class="form-control" name="password" >
                </div>
                <br>
                <input type="submit" id="login_2" name="login" value="LOGIN">
       
        </form>

       

    </body>
</html>