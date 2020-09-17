<?php 

require 'session.php';

if (!isset($_SESSION["ID"])) {
    header("Location: login.php");
}

require 'db.php';

$stmt = $db->prepare("SELECT * FROM subject WHERE user_id = :userID");
$stmt->execute(array(
    ':userID' => $_SESSION['ID']
));
$allSubjectsOfCurrentUser = $stmt->fetchAll();
$countOfAllSubjects = count($allSubjectsOfCurrentUser);

if(isset($_POST['passed']) && isset($_POST['subject'])){
    $passed = $_POST['passed'];
    $subject = $_POST['subject'];

    //pulling current study time from database
    $stmt = $db->prepare("SELECT * from subject WHERE subject = ? AND user_id = ?");
    $stmt->bindParam(1, $subject);
    $stmt->bindParam(2, $_SESSION['ID'], PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $current = $record['passed'];

    //converting so-far time and this time from string to seconds
    $current_seconds = strtotime($current) - strtotime('today');
    $passed_seconds = strtotime($passed) - strtotime('today');

    $total_seconds = $current_seconds + $passed_seconds;
        
    //function to convert seconds into hh:mm:ss
    function convertTotalIntoString($seconds) {
      $t = round($seconds);
      return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
    }

    //converting total time into string
    $new_time = convertTotalIntoString($total_seconds);

    $stmt2 = $db->prepare('UPDATE subject SET passed = ? WHERE subject = ? AND user_id = ?');
    $stmt2->bindParam(1, $new_time);
    $stmt2->bindParam(2, $subject);
    $stmt2->bindParam(3, $_SESSION['ID'], PDO::PARAM_INT);
    $stmt2->execute();

    header('Location: daily.php');
}

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.css" integrity="sha256-QVBN0oT74UhpCtEo4Ko+k3sNo+ykJFBBtGduw13V9vw=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.js" integrity="sha256-qs5p0BFSqSvrstBxPvex+zdyrzcyGdHNeNmAirO2zc0=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php require 'header.php'; ?>

        <div class="subject_wrapper">
            <label for="study_subject" class="label_subject">Subject</label>
            <select id="study_subject" name="subject" class="ui dropdown">
                <?php foreach ($allSubjectsOfCurrentUser as $row) :?>
                <option value="<?php echo $row['subject'];?>"><?php echo $row['subject'];?></option>
                <?php endforeach ;?>
            </select>
        </div>
        
        <div id="display">00:00:00</div>

        <div class="buttons">
            <button id="startStop">Start</button> 
            <button id="done" class="disabled">Done</button>            
        </div>

    
<!-- modal to show when there are no subjects for the current user -->
<div class="ui basic modal">
  <div class="content">
    <p>You haven't added any subject yet. Add a subject you wanna study!</p>
    <a href="./add.php">Add a Subject</a>
  </div>
</div>

<!-- modal to warn that data will be lost if user closes tab -->
<div class="ui modal warning">
  <i class="close icon"></i>
  <div class="header">
    <i class="exclamation triangle icon"></i>Warning
  </div>
  <div class="content">
    <p>Data will be lost if you close the tab or the browser before submitting.</p>
  </div>
  <div class="actions">
    <div class="ui positive right labeled icon button">
      Got it!
      <i class="checkmark icon"></i>
    </div>
  </div>
</div>

<!-- modal that has form to submit time you spent on studing -->
<div class="ui modal confirm">
  <i class="close icon"></i>
  <div class="content">
    <form action="" method="post" class="ui form">
        <div id="subject_to_show_in_modal"></div>
        <div id="time_to_show_in_modal"></div>
        <input type="hidden" name="subject" id="subject_in_modal">
        <input type="hidden" name="passed" id="hidden">
        <input type="submit" value="send" class="ui button green">
    </form>
  </div>
</div>
    


    <script src="stopwatch.js"></script>
    <script>
        $(document).ready(function(){
            $('#study_subject').dropdown();
            $(window).on('load', function(){
                var countOfSubjects = <?php echo $countOfAllSubjects;?>;
                if(countOfSubjects === 0) {
                    $('.ui.basic.modal').modal({closable: false}).modal('show');
                } else {
                    $('.ui.modal.warning').modal('show');
                }
            });
        });

        // const id  = <?php echo $_SESSION['ID']; ?>;
        // const updateSession  = () => {
        //   $.ajax({
        //     url: 'update_session.php',
        //     type: 'post',
        //     data: {id:id},
        //     success: function(data){
        //         console.log('success');
        //     }
        //   });
        // }
        
        // window.setInterval(updateSession, 300000);
    </script>
    
    <script src="menu.js"></script>
</body>
</html>