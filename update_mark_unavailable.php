<?php


require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


db();

if ($_REQUEST["mark_unavailable_flg"] == "yes") {

    $strQuery = "UPDATE loop_transaction_buyer SET mark_unavailable=1, mark_unavailable_by = '" . $_COOKIE['userinitials'] . "', mark_unavailable_on = '" . date("Y-m-d H:i:s") . "' WHERE id=" . $_REQUEST["mark_unavailable_rec_id"];
} else {

    $strQuery = "UPDATE loop_transaction_buyer SET mark_unavailable=0, mark_unavailable_by = '" . $_COOKIE['userinitials'] . "', mark_unavailable_on = '" . date("Y-m-d H:i:s") . "' WHERE id=" . $_REQUEST["mark_unavailable_rec_id"];
}

$r = db_query($strQuery);

$msg_trans = "System generated log - The box item 'Availability' change on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "<br>";

if ($_REQUEST["mark_unavailable_flg"] == "yes") {

    $msg_trans .= "Action : 'Mark as Unavailable'.";
} else {

    $msg_trans .= "Action : 'Unmark as Unavailable'";
}

$warehouse_id = 0;
$sql_preord = "SELECT warehouse_id FROM loop_transaction_buyer WHERE id = " . $_REQUEST["mark_unavailable_rec_id"];
$rec_preord = db_query($sql_preord);

while ($rec_preordrow = array_shift($rec_preord)) {

    $warehouse_id = $rec_preordrow["warehouse_id"];
}

$query1 = db_query('Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) select ?, ?, ?, ?, ?', array("i", "s", "i", "s", "s"), array($warehouse_id, 'Supplier', $_REQUEST["mark_unavailable_rec_id"], str_replace("'", "\'", $msg_trans), $_COOKIE['employeeid']));

//Send Transaction log
send_transactionlog_email($warehouse_id, $_REQUEST["mark_unavailable_rec_id"], "Supplier", "buyer_view");
//

redirect($_SERVER['HTTP_REFERER']);