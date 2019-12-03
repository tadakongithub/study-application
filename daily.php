<?php
require 'db.php';

session_start();

$user_id = $_SESSION['ID'];

$q = $db->prepare("SELECT * from subject WHERE user_id = ?");
$q->bindParam(1, $user_id, PDO::PARAM_INT);
$q->execute();


//declaring an array that later will receive subjects one by one
$ids = [];
$percentage = [];
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <!-- include header -->
    <?php require 'header.php';?>

    <!-- loop each subject to show each subject's current daily study status with a bar -->
    <?php while($each_sub = $q->fetch()):?>
        <div class="daily_con">
            <span><?php echo $each_sub['subject'];?> (Goal : 
            <?php 
                $h = $each_sub['goal'] / 3600;
                $h = floor($h);
                $m = ($each_sub['goal'] - 3600*$h) / 60;
              
                if ($h == 0) {
                  echo "$m minutes";
                } else {
                  echo "$h h $m m";
                }
            ?>)</span>
            
            <!-- pushing subject into an array that will later be used in script tag to differentiate each hider class -->
            <?php
                array_push($ids, $each_sub['id']);
                $passed_seconds = strtotime($each_sub['passed']) - strtotime('today');
                $percent = $passed_seconds / $each_sub['goal'] * 100;
                array_push($percentage, $percent);
            ?>
            <div class="bar"></div>
            <div class="hider" id="<?php echo $each_sub['id'];?>"></div><!-- set each subject's name to hider (actually made id for it) -->
        </div>
    <?php endwhile ;?>

    <button class="reset">Reset</button>

<!-- modal for reset -->
    <div class="modal" id="resetModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reset the time you spent for each subject</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="reset_time">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save_reset">RESET</button>
      </div>
    </div>
  </div>
</div>

<script>
var ids = [];
<?php foreach($ids as $id):?>
    ids.push('<?php echo $id;?>');
<?php endforeach ;?>
var percentage = [];
<?php foreach($percentage as $each_percent):?>
    percentage.push('<?php echo $each_percent;?>');
<?php endforeach ;?>

$(document).ready(function(){
        
    for(var i = 0; i < ids.length; i++){
        $("#"+ids[i]).css({
            backgroundColor: '#c9ffd2'
        }).animate({
                left: percentage[i] + "%"
            }, 1000);
    }
});

$(document).ready(function(){
    $(".reset").on('click', function(){     
                $("#reset_time").html('Are you sure you want to reset the time?');
                $("#resetModal").modal('show');       
    });
});

$(document).ready(function(){
    $("#save_reset").on('click', function(){ 
        var user_id = <?php echo $user_id;?>;     
        $.ajax({
            url: 'reset.php',
            type: 'post',
            data: {id:user_id},
            success: function(data){
                $("#resetModal").modal('hide');
                location.reload();
            }
        });
    });
});


</script>
<script src="menu.js"></script>
</body>
</html>