<?php
require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

// function make_insert_query($table_name, $arr_data)
// {
//     $fieldname = "";
//     $fieldvalue = "";
//     foreach ($arr_data as $fldname => $fldval) {
//         $fieldname = ($fieldname == "") ? $fldname : $fieldname . ',' . $fldname;
//         $fieldvalue = ($fieldvalue == "") ? "'" . formatdata($fldval) . "'" : $fieldvalue . ",'" . formatdata($fldval) . "'";
//     }
//     $query1 = "INSERT INTO " . $table_name . " ($fieldname) VALUES($fieldvalue)";
//     return $query1;
// }

// function formatdata($data)
// {
//     return addslashes(trim($data));
// }

//if (isset($_REQUEST['btnSave']) && $_REQUEST['btnSave'] == 'yes') {
$b2bid = 0;
$sql_transnotes = "SELECT b2bid FROM loop_warehouse where id = " . $_REQUEST["warehouse_id"];
db();
$result_transnotes = db_query($sql_transnotes);
while ($myrowsel_transnotes = array_shift($result_transnotes)) {
    $b2bid = $myrowsel_transnotes["b2bid"];
}
if ($_REQUEST["logdetail"] != "" && $_REQUEST["donotsave_dt"] == 1) {
    $sql = "UPDATE companyInfo SET last_date = '" . date("Y-m-d") . "' WHERE ID = " . $b2bid;
    db_b2b();
    $result = db_query($sql);
    //echo $sql;
    $arr_data = array(
        'company_id' => formatdata($_REQUEST["warehouse_id"]),
        'rec_type' => 'Supplier',
        'rec_id' => formatdata($_REQUEST["transid"]),
        'message' => formatdata($_REQUEST["logdetail"]),
        'employee_id' => $_COOKIE['employeeid']
    );

    db();
    $query1 = make_insert_query('loop_transaction_notes', $arr_data);
    //echo $query1;
    db_query($query1);
    //----------------------------------
    //Send transaction log email

    $sql = "SELECT b2bid, warehouse_name from loop_warehouse where id = ?";
    db();
    $result_comp = db_query($sql, array("i"), array($_REQUEST["warehouse_id"]));
    while ($row_comp = array_shift($result_comp)) {
        $b2bid = $row_comp["b2bid"];
    }

    send_transactionlog_email($_REQUEST["warehouse_id"], $_REQUEST["transid"], "Supplier", "buyer_view");
}

if ($_REQUEST["donotsave_dt"] == 0 && $_REQUEST["mark_as_unavailable"] == 0) {
    $date_log = date("Y-m-d H:i:s");
    $rec_id = $_REQUEST["transid"];
    $po_delivery_dt = date("Y-m-d", strtotime($_REQUEST["po_delivery_date"]));
    $customer_confirmed_flg = $_REQUEST["customer_confirmed_flg"];

    $sql = "UPDATE loop_transaction_buyer SET po_delivery_dt = '" . $po_delivery_dt . "', planned_delivery_dt_customer_confirmed=0 WHERE id = '" . $rec_id . "'";
    //echo $sql;
    db();
    $result = db_query($sql);

    if ($customer_confirmed_flg == 1) {
        $sql = "UPDATE loop_transaction_buyer SET planned_delivery_dt_customer_confirmed='" . $customer_confirmed_flg . "', dt_customer_confirmed_by='" . $_COOKIE['userinitials'] . "', dt_customer_confirmed_on='" . $date_log . "' WHERE id = '" . $rec_id . "'";
        db();
        $result = db_query($sql);
    }

    if ($customer_confirmed_flg == 0) {
        $customer_confirmed_flgval = "Yes";
    }
    if ($customer_confirmed_flg == 1) {
        $customer_confirmed_flgval = "No";
    }

    /**Transaction log maintain start**/
    $messageTransLog = "System generated log - Planned Delivery Date submitted on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . " and Customer Confirmed - " . $customer_confirmed_flgval . " updated on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'];
    //maintain_transaction_log($rec_id, $messageTransLog );
    /**Transaction log maintain end**/

    $date_log = date("Y-m-d H:i:s");
    $savelog_qry = "INSERT INTO `planned_delivery_date_history` (`comp_id`, `trans_id`, `planned_delivery_dt`, `user_log`, `date_log`, `planned_delivery_dt_customer_confirmed`, `dt_customer_confirmed_by`, `dt_customer_confirmed_on`) VALUES ('', '" . $rec_id . "', '" . $po_delivery_dt . "', '" . $_COOKIE['userinitials'] . "', '" . $date_log . "','" . $customer_confirmed_flg . "', '" . $_COOKIE['userinitials'] . "', '" . $date_log . "')";
    db();
    $result_dt = db_query($savelog_qry);
}

if ($_REQUEST["donotsave_dt"] == 0 && $_REQUEST["mark_as_unavailable"] == 1) {
    $rec_id = $_REQUEST["transid"];
    if ($_REQUEST["mark_as_unavailable_flg"] == 1) {
        $strQuery = "UPDATE loop_transaction_buyer SET mark_unavailable=1, mark_unavailable_by = '" . $_COOKIE['userinitials'] . "', mark_unavailable_on = '" . date("Y-m-d H:i:s") . "' WHERE id= '" . $rec_id . "'";
    } else {
        $strQuery = "UPDATE loop_transaction_buyer SET mark_unavailable=0, mark_unavailable_by = '" . $_COOKIE['userinitials'] . "', mark_unavailable_on = '" . date("Y-m-d H:i:s") . "' WHERE id= '" . $rec_id . "'";
    }

    $result = db_query($strQuery);

    $msg_trans = "System generated log - The box item 'Availability' change on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "<br>";
    if ($_REQUEST["mark_as_unavailable_flg"] == 1) {
        $msg_trans .= "Action : 'Mark as Unavailable'.";
    } else {
        $msg_trans .= "Action : 'Unmark as Unavailable'";
    }

    $warehouse_id = 0;
    $sql_preord = "SELECT warehouse_id FROM loop_transaction_buyer WHERE id = '" . $rec_id . "'";
    $rec_preord = db_query($sql_preord);
    while ($rec_preordrow = array_shift($rec_preord)) {
        $warehouse_id = $rec_preordrow["warehouse_id"];
    }

    $query1 = db_query('Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) select ?, ?, ?, ?, ?', array("i", "s", "i", "s", "s"), array($warehouse_id, 'Supplier', $rec_id, str_replace("'", "\'", $msg_trans), $_COOKIE['employeeid']));

    //Send Transaction log
    send_transactionlog_email($warehouse_id, $rec_id, "Supplier", "buyer_view");
}

$cnt_no = 0;
$ship_qry = "SELECT loop_salesorders.so_date, planned_delivery_dt_customer_confirmed, loop_salesorders.warehouse_id, loop_salesorders.qty AS QTY, loop_warehouse.b2bid, loop_warehouse.company_name AS NAME, 
	loop_transaction_buyer.id as transid, loop_transaction_buyer.mark_unavailable , loop_transaction_buyer.po_delivery, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.ops_delivery_date FROM loop_salesorders ";
$ship_qry .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
$ship_qry .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
$ship_qry .= " WHERE loop_salesorders.box_id = '" . $_REQUEST["box_id"] . "' and loop_transaction_buyer.id = '" . $_REQUEST["transid"] . "' and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0";

//echo "<br /> ship_qry -> ".$ship_qry;
db();
$dt_res_so = db_query($ship_qry);
$cnt_no = $_REQUEST["tmpcnt"];

if (($cnt_no % 2) == 0) {
    $bg = "#E4E4E4";
} else {
    $bg = "#CCCCCC";
}
while ($so_row = array_shift($dt_res_so)) {
    $sql_transnotes = "SELECT *, loop_employees.initials AS EI FROM loop_transaction_notes INNER JOIN loop_employees ON loop_transaction_notes.employee_id = loop_employees.id WHERE loop_transaction_notes.company_id = " . $so_row["warehouse_id"] . " AND  loop_transaction_notes.rec_id = " . $so_row["transid"] . " ORDER BY loop_transaction_notes.id DESC limit 1";
    db();
    $result_transnotes = db_query($sql_transnotes);

    $trans_log_notes = "";
    $trans_log_emp = "";
    $trans_log_dt = "";
    while ($myrowsel_transnotes = array_shift($result_transnotes)) {
        $trans_log_notes  = $myrowsel_transnotes["message"];
        $trans_log_emp  = $myrowsel_transnotes["EI"];
        if ($myrowsel_transnotes["date"] != "") {
            $trans_log_dt = $myrowsel_transnotes["date"] . " CT";
        } else {
            $trans_log_dt = "";
        }
    }

    $comp_nm = get_nickname_val($so_row["NAME"], $so_row["b2bid"]);
    //$bg = '#E4EAEB';
    if ($so_row["po_delivery_dt"] == "") {
        $po_deli_date = $so_row["po_delivery"];
    } else {
        $po_deli_date = date("m/d/Y", strtotime($so_row["po_delivery_dt"]));
    }

?>
    <td> &nbsp;</td>
    <td bgColor="<?php echo $bg; ?>"><?php echo $_REQUEST["transid"]; ?></td>
    <td bgColor="<?php echo $bg; ?>"><?php echo $so_row["QTY"]; ?></td>
    <td bgColor="<?php echo $bg; ?>"><?php echo $so_row["so_date"]; ?></td>
    <td bgColor="<?php echo $bg; ?>">
        <input type="hidden" name="planned_delivery_dt_confirmed<?php echo $_REQUEST["transid"] . $cnt_no; ?>" id="planned_delivery_dt_confirmed<?php echo $_REQUEST["transid"] . $cnt_no; ?>" size="11" value="<?php echo $so_row["planned_delivery_dt_customer_confirmed"]; ?>">
        <input type="text" name="po_delivery_date<?php echo $_REQUEST["transid"] . $cnt_no; ?>" id="po_delivery_date<?php echo $_REQUEST["transid"] . $cnt_no; ?>" size="11" value="<?php if ($po_deli_date == "") {
                                                                                                                                                                                        echo "";
                                                                                                                                                                                    } else {
                                                                                                                                                                                        echo date("m/d/Y", strtotime($po_deli_date));
                                                                                                                                                                                    } ?>">
        <a href="#" onclick="cal1xx.select(document.gaylordstatuspg<?php echo $_REQUEST["box_id"]; ?>.po_delivery_date<?php echo $_REQUEST["transid"] . $cnt_no; ?>, 'anchor1xx<?php echo $_REQUEST["transid"] . $cnt_no; ?>','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx<?php echo $_REQUEST["transid"] . $cnt_no; ?>">
            <img border="0" src="images/calendar.jpg"></a>
        <input type="button" id="btnsave_pl_dt" name="btnsave_pl_dt" value="Save Planned Delivery Date" onclick="save_pl_dt(<?php echo $so_row["warehouse_id"]; ?>,<?php echo $_REQUEST["transid"]; ?>,<?php echo $cnt_no; ?>, <?php echo $_REQUEST['box_id']; ?>)" />
        <?php echo (($so_row["Preorder"] == 1) ? "Pre-" : "Active ") . "Order"; ?>
    </td>
    <td bgColor="<?php echo $bg; ?>">
        <?php
        $mark_unavailable_val = $so_row["mark_unavailable"];

        if ($mark_unavailable_val == 0) { ?>
            <input type="button" id="btnmark_as_unavailable" name="btnmark_as_unavailable" value="Mark as Unavailable" onclick="btn_mark_as_unavailable(<?php echo $so_row["warehouse_id"]; ?>,<?php echo $_REQUEST["transid"]; ?>,<?php echo $cnt_no; ?>, <?php echo $_REQUEST['box_id']; ?>, 1)" /><br>
        <?php } else { ?>
            <input type="button" id="btnmark_as_unavailable" name="btnmark_as_unavailable" value="Unmark as Unavailable" onclick="btn_mark_as_unavailable(<?php echo $so_row["warehouse_id"]; ?>,<?php echo $_REQUEST["transid"]; ?>,<?php echo $cnt_no; ?>, <?php echo $_REQUEST['box_id']; ?>, 2)" /><br>
        <?php }

        if ($mark_unavailable_val == 1) {
            echo "Action : 'Mark as Unavailable'";
        }
        ?>
    </td>

    <td bgColor="<?php echo $bg; ?>"><a target="_blank" href="search_results.php?warehouse_id=<?php echo $so_row["warehouse_id"]; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $so_row["warehouse_id"]; ?>&rec_id=<?php echo $_REQUEST["transid"]; ?>&display=buyer_view"><?php echo $comp_nm; ?></a>
    </td>
    <td bgColor="<?php echo $bg; ?>"><textarea id="trans_notes<?php echo $_REQUEST["transid"] . $cnt_no; ?>" name="trans_notes" cols="35" rows="4"><?php echo $trans_log_notes; ?></textarea></td>
    <td bgColor="<?php echo $bg; ?>"><?php echo $trans_log_emp; ?></td>
    <td bgColor="<?php echo $bg; ?>" style="font-size: xx-small;	font-family: Arial, Helvetica, sans-serif;">
        <?php echo $trans_log_dt; ?></td>
    <td bgColor="<?php echo $bg; ?>" colspan="2"><input type="button" id="logsave" name="logsave" value="Save" onclick="savetranslog(<?php echo $so_row["warehouse_id"]; ?>,<?php echo $_REQUEST["transid"]; ?>,<?php echo $cnt_no; ?>, <?php echo $_REQUEST['box_id']; ?>)" />
    </td>
<?php
} ?>