<?php
include 'db.php';

if(isset($_POST['delete_id'])){
    $delete_id = $_POST['delete_id'];
}

$selected = $db->query("SELECT * FROM subject WHERE id = $delete_id");
?>


<?php while ($fetched = $selected->fetch()):?>
<form action="" id="delete_form">
<div>Are you sure you want to delete <?php echo $fetched['subject'];?>?</div>
<input type="hidden" name="hidden_delete_id" value="<?php echo $fetched['id'];?>">
</form>

<?php endwhile ;?>
