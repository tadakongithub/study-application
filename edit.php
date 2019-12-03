<?php
require 'db.php';

session_start();

$q = $db->prepare('SELECT * from subject WHERE user_id = ?');
$q->bindParam(1, $_SESSION['ID'], PDO::PARAM_INT);
$q->execute();

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
<body id="edit_body">
    <?php require 'header.php'; ?>
    <table class="edit_table">
        <tr>
            <th id="edit_head_sub">SUBJECT</th>
            <th class="delete"></th>
            <th class="edit"></th>
        </tr>

        <?php while($each_sub = $q->fetch()):?>
        <tr class="edit_row"> 
            <td><?php echo $each_sub['subject'];?></td>
            <td class="delete"><button id="<?php echo $each_sub['id'];?>" class="ui icon red button delete_class" data-toggle="modal" data-target="#deleteModal"><i class="eraser icon"></i></button></td>
            <td class="edit"><button id="<?php echo $each_sub['id'];?>" class="ui icon blue button edit_class" data-toggle="modal" data-target="#editModal"><i class="edit icon"></i></button></td>
        </tr>
        <?php endwhile ;?>
    </table>

<!-- modal for edit -->
    <div class="modal" id="editModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change the daily goal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="update_details">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- modal for delete -->
<div class="modal" id="deleteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete subject</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="delete_subject">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save_delete">DELETE</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).on('click', ".edit_class", function(){
        var edit_id = $(this).attr('id');

        $.ajax({
            url: "edit_data.php",
            type: "post",
            data: {edit_id:edit_id},
            success:function(data){
                $("#update_details").html(data);
                $("#editModal").modal('show');
            }
        });
    });

$(document).ready(function(){
  $("#save").on('click', function(){
    $.ajax({
      url: "update_data.php",
      type: "post",
      data:$("#edit_form").serialize(),
      success: function(data){
        $("#editModal").modal('hide');
        location.reload();
      }
    });
  });
});

$(document).ready(function(){
  $(".delete_class").on('click', function(){
    var delete_id = $(this).attr('id');

    $.ajax({
      url: 'delete_data.php',
      type: "post",
      data: {delete_id:delete_id},
      success: function(data){
        $("#delete_subject").html(data);
        $("#deleteModal").modal('show');
      }
    });
  });
});

$(document).ready(function(){
  $("#save_delete").on('click', function(){
    $.ajax({
      url: "delete.php",
      type: "post",
      data: $("#delete_form").serialize(),
      success:function(data){
        $("#deleteModal").modal('hide');
        location.reload();
      }
    });
  });
});
</script>
   


<script src="menu.js"></script>
</body>
</html>