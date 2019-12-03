<?php
    session_start();

    $new_subject = $_SESSION['added_subject'];
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="inserted_container">
            <div id="subject_inserted"><?php echo $new_subject;?> added successfully!</div>
            <div id="inserted_back"><a href="add.php">Back</a></div>
        </div>
    </body>
</html>