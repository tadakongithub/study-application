<?php
require 'db.php';

if(isset($_POST['delete_id'])){
    $delete_id = $_POST['delete_id'];
    $stmt = $db->query("SELECT * FROM subject WHERE id = $delete_id");
}
$record = $stmt->fetch();
?>
<form action="" id="delete_form">
    <div>Are you sure you want to delete <?php echo $record['subject'];?>?</div>
    <input type="hidden" name="hidden_delete_id" value="<?php echo $record['id'];?>">
</form>
