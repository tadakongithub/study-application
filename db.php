<?php
    $dsn = 'mysql:dbname=study_app;host=localhost';
    $user = 'root';
    $password = 'root';
    
    try {
        $db = new PDO($dsn, $user, $password);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
    
?>