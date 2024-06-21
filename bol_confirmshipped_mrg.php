<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


$sql3ud = "UPDATE loop_bol_files SET bol_shipped = '1', bol_shipped_employee = '" . $_REQUEST['userinitials'] . "',  bol_shipped_date = '" .  date("m/d/Y H:i:s") . "' WHERE id = " . $_REQUEST["bol_id"];

db();
$result3ud = db_query($sql3ud);

$so_view = "SELECT id,warehouse_id,rec_type,employee,virtual_inventory_company_id,virtual_inventory_trans_id, customerpickup_ucbdelivering_flg from loop_transaction_buyer WHERE id = '" . $_REQUEST["rec_id"] . "'";
//echo $so_view;
$trans_id_virtual = 0;
$customerpickup_ucbdelivering_flg = "";

db();
$view_res = db_query($so_view);
while ($so_view_row = array_shift($view_res)) {
    $trans_id_virtual = $so_view_row['virtual_inventory_trans_id'];
    $customerpickup_ucbdelivering_flg = $so_view_row['customerpickup_ucbdelivering_flg'];
}

//Virtual Link
//echo $trans_id_virtual . "<br>";
if ($trans_id_virtual > 0) {
    $unassign_bol_file = "";
    db();
    $view_res = db_query("Select file_name from loop_bol_files where trans_rec_id = '" . $_REQUEST["rec_id"] . "'");
    while ($so_view_row = array_shift($view_res)) {
        $unassign_bol_file = $so_view_row['file_name'];
    }

    $sql = "UPDATE loop_transaction SET usr_file = '" . $unassign_bol_file . "', cp_notes = 'Virtual - Confirm Pickup',  cp_employee = '" . $_REQUEST['userinitials'] . "', cp_date = '" . date("m/d/Y") . "' WHERE id = '" . $trans_id_virtual . "'";
    db();
    $result = db_query($sql);

    $sql = "UPDATE loop_transaction SET bol_file = 'No BOL', bol_employee = '" . $_REQUEST['userinitials'] . "', bol_date = '" . date("m/d/Y") . "' WHERE id = '" . $trans_id_virtual . "'";
    db();
    $result = db_query($sql);

    db();
    $view_res = db_query("Select warehouse_id from loop_transaction where id = " . $trans_id_virtual);
    $manf_warehouse_id = 0;
    while ($so_view_row = array_shift($view_res)) {
        $manf_warehouse_id = $so_view_row['warehouse_id'];
    }

    $message = "<strong>Note for Transaction # ";
    $message .=  $trans_id_virtual;
    $message .= "</strong>.  There is no BOL for this record.  Entered by ";
    $message .=  $_REQUEST['userinitials'];
    $message .= " on ";
    $message .= date("m/d/Y");
    $message .= ".";

    $sql_crm = "INSERT INTO loop_crm  ( warehouse_id, message_date, employee, comm_type, message) VALUES ( '" . $manf_warehouse_id . "', '" . date("Ymd") . "', '" . $_REQUEST['userinitials'] . "', '5', '" . $message . "')";
    db();
    $result_crm = db_query($sql_crm);
}

//--------
$sql3ud = "UPDATE loop_transaction_buyer SET shipped = '1' WHERE id = " . $_REQUEST["rec_id"];
db();
$result3ud = db_query($sql3ud);

$sql3ud = "UPDATE loop_next_load_available_history SET inactive_delete_flg = 4 WHERE trans_rec_id = " . $_REQUEST["rec_id"];
db();
$result3ud = db_query($sql3ud);

if ($customerpickup_ucbdelivering_flg == "1") {
    $sql3ud = "UPDATE loop_bol_files SET bol_shipment_received = '1', bol_shipment_received_employee = '" . $_COOKIE['userinitials'] . "',  bol_shipment_received_date = '" .  date("m/d/Y") . "' WHERE trans_rec_id = " . $_REQUEST["rec_id"];
    db();
    $result3ud = db_query($sql3ud);

    //$sql3ud = "UPDATE loop_bol_files SET bol_shipment_followup = '1', bol_shipment_followup_employee = '". $_COOKIE['userinitials'] ."',  bol_shipment_followup_date = '".  date("m/d/Y") ."' WHERE trans_rec_id = ". $_REQUEST["rec_id"];
    //$result3ud = db_query($sql3ud,db() );

}

$sql = "SELECT * FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.id = " . $_REQUEST["rec_id"];
db();
$result = db_query($sql);
while ($dresult = array_shift($result)) {
    feed_mysqli($_REQUEST['userinitials'] . " has confirmed the order to " . $dresult["company_name"] . " has shipped. <a href= 'search_results.php?warehouse_id=" . $dresult["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dresult["warehouse_id"] . "&rec_id=" . $_REQUEST["rec_id"] . "&display=buyer_ship'>Details</a>", $_COOKIE['employeeid'], 0);
}






//redirect($_SERVER['HTTP_REFERER']);

echo "<script type=\"text/javascript\">";
echo "window.location.href=\"loop_shipbubble_bol.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_ship\";";
echo "</script>";
echo "<noscript>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=loop_shipbubble_bol.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_ship\" />";
echo "</noscript>";
exit;