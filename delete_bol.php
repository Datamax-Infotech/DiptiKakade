<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


db();

$sql_remove = "DELETE FROM loop_bol_tracking WHERE trans_rec_id = " . $_REQUEST['trans_rec_id'];
$result_remove = db_query($sql_remove);

$sql_remove = "DELETE FROM loop_bol_files WHERE trans_rec_id = " . $_REQUEST['trans_rec_id'];
$result_remove = db_query($sql_remove);

$sql_remove = "DELETE FROM loop_inventory WHERE in_out = 1 AND trans_rec_id = " . $_REQUEST['trans_rec_id'];
$result_remove = db_query($sql_remove);

$sql_remove = "Update loop_transaction_buyer set bol_create = 0 WHERE id = " . $_REQUEST['trans_rec_id'];
$result_remove = db_query($sql_remove);

redirect($_SERVER['HTTP_REFERER']);