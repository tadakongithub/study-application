<?php
require 'db.php';

if(isset($_POST['hidden_delete_id'])){
    $hidden_delete_id = $_POST['hidden_delete_id'];
    $deleted = $db->query("DELETE FROM subject WHERE id = $hidden_delete_id");
}
?>