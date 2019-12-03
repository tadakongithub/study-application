<?php
include 'db.php';

if (isset($_POST['edit_id'])){

    $edit_id = $_POST['edit_id'];
    $hour = $_POST['hour'];
    $minute = $_POST['minute'];

    $new_goal = strval($hour * 3600 + $minute * 60);
}

$update = $db->query("UPDATE subject SET goal = $new_goal WHERE id = $edit_id");
?>