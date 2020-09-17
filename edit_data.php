<?php
require 'db.php';

if(isset($_POST['edit_id'])){
    $edit_id = $_POST['edit_id'];
    $stmt = $db->prepare("SELECT * FROM subject WHERE id = :id");
    $stmt->execute(array(
        ':id' => $edit_id
    ));
    $record = $stmt->fetch();             
    $h = $record['goal'] / 3600;
    $h = floor($h);
    $m = ($record['goal'] - 3600*$h) / 60;
}
?>
<form id="edit_form">
    <h3><?php echo $record['subject'];?></h3>
    <input type="hidden" name="edit_id" value="<?php echo $record['id'];?>">

    <div class="modal_current">
        Current Daily Target:
        <?php if ($h == 0) {
            echo "<span class='modal_current_time'>$m</span> minutes";
        } else {
            echo "<span class='modal_current_time'>$h</span> h <span class='modal_current_time'>$m</span> m";
        }
        ?>
    </div>

    <div class="modal_new">
        New Daily Target:
        <select class="form-control" id="hour" name="hour">
            <option class="dropdown-item"value="0">0</option>
            <option class="dropdown-item"value="1">1</option>
            <option class="dropdown-item"value="2">2</option>
            <option class="dropdown-item"value="3">3</option>
            <option class="dropdown-item"value="4">4</option>
            <option class="dropdown-item"value="5">5</option>
            <option class="dropdown-item"value="6">6</option>
            <option class="dropdown-item"value="7">7</option>
            <option class="dropdown-item"value="8">8</option>
        </select>
        <label for="hour">h</label>

        <select class="form-control" id="minute" name="minute">
            <option value="0">0</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="40">40</option>
            <option value="50">50</option>
        </select>
        <label for="minute">m</label>
    </div>

</form>





