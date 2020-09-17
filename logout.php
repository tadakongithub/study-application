<?php
require 'session.php';

if (isset($_SESSION["NAME"])) {
    $errorMessage = "You've been logged out";
} else {
    $errorMessage = "You've been logged out";
}

// セッションの変数のクリア
$_SESSION = array();

// セッションクリア
@session_destroy();
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>LOGOUT</title>
    </head>
    <body id="logout_body">
        <div><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
        <button onclick="location.href = 'login.php';" id="login_3">LOGIN</button>
    </body>
</html>