<?php

require 'db.php';

$id = $_POST['id'];

$stmt = $db->query("UPDATE subject SET passed = '00:00:00' WHERE user_id = $id");

?>
