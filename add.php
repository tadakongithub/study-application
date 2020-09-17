<?php
    require 'session.php';

    if (!isset($_SESSION["ID"])) {
        header("Location: login.php");
    }

    require('db.php');

    if(isset($_POST['new_subject']) &&  isset($_POST['goal_hour']) &&  isset($_POST['goal_minute'])) {
        $stmt = $db->prepare('SELECT * FROM subject WHERE user_id = :user_id AND subject = :subject');
        $stmt->execute(array(
            ':user_id' => $_SESSION['ID'],
            ':subject' => $_POST['new_subject']
        ));
        $subjectsAlreadyRegistered = $stmt->fetchAll();
        if(count($subjectsAlreadyRegistered) > 0){
           $count = count($subjectsAlreadyRegistered);
        } else {
            $new_subject = $_POST['new_subject'];
            $hour = $_POST['goal_hour'] * 3600;
            $minute = $_POST['goal_minute'] * 60;
            $goal_seconds = $hour + $minute;//ggoal in seconds
            $goal = strval($goal_seconds);

            $statement = $db->prepare('INSERT INTO subject SET subject=?, goal=?, user_id=?');
            $statement->bindParam(1, $new_subject);
            $statement->bindParam(2, $goal);
            $statement->bindParam(3, $_SESSION['ID'], PDO::PARAM_INT);
            $statement->execute();

            $_SESSION['added_subject'] = $new_subject;

            header('Location: inserted.php');
        }
    }
?>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.css" integrity="sha256-QVBN0oT74UhpCtEo4Ko+k3sNo+ykJFBBtGduw13V9vw=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.js" integrity="sha256-qs5p0BFSqSvrstBxPvex+zdyrzcyGdHNeNmAirO2zc0=" crossorigin="anonymous"></script>
</head>
<body id="add-bd">
    <?php require 'header.php'; ?>

    <form id="add_form" action="" method="post" class="ui form">
        <div class="add_form_label add_form_label_1">What's your new subject?</div>
        <input type="text" id="new_subject" name="new_subject">
        <div class="add_form_label add_form_label_3">Set your goal for the day</div>

        <div class="field field_h">
        <label for="goal_hour">HOUR</label>
        <select name="goal_hour" id="goal_hour" class="ui dropdown select_hour">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
        </select>
        </div>

        <div class="field">
        <label for="goal_minute">MINUTE</label>
        <select name="goal_minute" id="goal_minute" class="ui dropdown select_minute">
            <option value="0">0</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
        </select>
        </div>
        

        <input id="add_submit" type="submit" value="Add">
    </form>

    <!-- modal to show when a user tries to register a subject that already exists-->
    <div class="ui basic modal">
        <div class="content">
            <p>This subject is already registered.</p>
        </div>
        <div class="actions">
            <div class="ui green ok inverted button"><i class="checkmark icon"></i>Got it</div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#goal_hour, #goal_minute').dropdown();
            $(window).on('load', function(){
                var count = <?php echo $count; ?>;
                if(count > 0){
                    $('.ui.basic.modal').modal('show');
                }
            });
        });
    </script>

    <script src="menu.js"></script>
</body>
</html>