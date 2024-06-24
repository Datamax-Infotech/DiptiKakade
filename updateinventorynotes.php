<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();
$sql = "SELECT * FROM loop_inventory_notes ORDER BY dt DESC LIMIT 0,1";
$res = db_query($sql);
$row = array_shift($res);
?>
<form method=post action="add_inventory_notes.php">
    <textarea cols=150 rows=40 name="notes">
		<?= $row["notes"]; ?> </textarea><br>

    <input type="submit" value="Update Notes">
</form>