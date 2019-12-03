<?php 

session_start();

// ログイン状態チェック
if (!isset($_SESSION["ID"])) {
    header("Location: login.php");/*元々logout.phpに飛ぶんだったが、変えた*/
    exit;
}


require 'db.php';

$stmt = $db->prepare("SELECT * FROM subject WHERE user_id = ?");
$stmt->bindParam(1, $_SESSION['ID'], PDO::PARAM_INT);
$stmt->execute();


if($_POST['passed']){
    $passed = $_POST['passed'];
    $subject = $_POST['subject'];

    //pulling current study time from database

    $q = $db->prepare("SELECT * from subject WHERE subject = ? AND user_id = ?");
    $q->bindParam(1, $subject);
    $q->bindParam(2, $_SESSION['ID'], PDO::PARAM_INT);
    $q->execute();
    while ($record = $q->fetch()){
        $current = $record['passed'];

        //converting so-far time and this time from string to seconds
        $current_seconds = strtotime($current) - strtotime('today');
        $passed_seconds = strtotime($passed) - strtotime('today');

        $total_seconds = $current_seconds + $passed_seconds;
        
        //function to convert seconds into hh:mm:ss
        function foo($seconds) {
            $t = round($seconds);
            return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
        }

        //converting total time into string
        $new_time = foo($total_seconds);

        $stmt2 = $db->prepare('UPDATE subject SET passed = ? WHERE subject = ? AND user_id = ?');
        $stmt2->bindParam(1, $new_time);
        $stmt2->bindParam(2, $subject);
        $stmt2->bindParam(3, $_SESSION['ID'], PDO::PARAM_INT);
        $stmt2->execute();
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
<body>
    <?php require 'header.php'; ?>
    <div id="display">
            00:00:00
    </div>

    <div class="buttons">
            <button id="startStop" onclick="startStop()">Start</button> 
            <button id="done" class="disabled" onclick="done()">Done</button>            
            <!--<button id="reset" onclick="reset()">Reset</button>-->
    </div>

    <form action="" method="post" id="study_form">

        <label for="study_subject" class="label_subject">Subject</label>
        <select id="study_subject" name="subject" class="ui dropdown">
            <?php foreach ($stmt as $row) :?>
            <option value="<?php echo $row['subject'];?>"><?php echo $row['subject'];?></option>
            <?php endforeach ;?>
        </select>

        <input type="hidden" name="passed" id="hidden">

        <input type="submit" value="SEND" id="study_submit">
    </form>


    <script src="stopwatch.js"></script>
    <script src="menu.js"></script>
    <script src="done_click.js"></script>
    <script>$(document).ready(function(){
        $('#study_subject').dropdown();
    });


</script>
    
    
</body>
</html>