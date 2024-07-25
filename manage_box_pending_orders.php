<?php
require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

$no_of_rec = 0;

if (isset($_REQUEST['id'])) {
    $qry_vendor = "select id, box_warehouse_id,vendor_b2b_rescue from loop_boxes where id=" . $_REQUEST["id"];
    //echo "<br /> qry_vendor -> ".$qry_vendor;	
    db();
    $dt_view = db_query($qry_vendor);
    $row = array_shift($dt_view);
    $vendorid = $row["vendor_b2b_rescue"];
    //
    $ship_qry = "SELECT loop_salesorders.so_date, planned_delivery_dt_customer_confirmed, loop_salesorders.warehouse_id, loop_salesorders.qty AS QTY, loop_warehouse.b2bid, loop_warehouse.company_name AS NAME, 
	loop_transaction_buyer.id as transid, loop_transaction_buyer.mark_unavailable , loop_transaction_buyer.po_delivery, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.Preorder, loop_transaction_buyer.ops_delivery_date FROM loop_salesorders ";
    $ship_qry .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
    $ship_qry .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
    $ship_qry .= " WHERE loop_salesorders.box_id = " . $_REQUEST["id"] . " and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0";

    //echo "<br /> ship_qry -> ".$ship_qry;
    db();
    $dt_res_so = db_query($ship_qry);

    $no_of_rec = tep_db_num_rows($dt_res_so);
}
//echo "<br /> no_of_rec -> ".$no_of_rec;
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
    <title>Loops - Pending Orders</title>

    <script type="text/javascript">
        function savetranslog(warehouse_id, transid, tmpcnt, box_id) {
            var logdetail = document.getElementById("trans_notes" + transid + tmpcnt).value;
            var po_delivery_date = document.getElementById("po_delivery_date" + transid + tmpcnt).value;

            document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
                "<br><br>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Data saved.");
                    //document.location = "manage_box_pending_orders.php?id=" + box_id;

                    document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "manage_box_pending_orders_save.php?donotsave_dt=1&box_id=" + box_id + "&tmpcnt=" + tmpcnt +
                "&warehouse_id=" + warehouse_id + "&transid=" + transid + "&logdetail=" + logdetail +
                "&po_delivery_date=" + po_delivery_date, true);
            xmlhttp.send();
        }

        function save_pl_dt(warehouse_id, transid, tmpcnt, box_id) {
            var customer_confirmed_flg;
            var planned_delivery_dt_confirmed_flg = 0;
            if (document.getElementById('planned_delivery_dt_confirmed' + transid + tmpcnt)) {
                planned_delivery_dt_confirmed_flg = 1;
            }
            if (planned_delivery_dt_confirmed_flg == 1 && document.getElementById('planned_delivery_dt_confirmed' +
                    transid + tmpcnt).value == 0) {
                if (confirm(
                        "Is the customer aware and confirmed the new planned delivery date? If yes, press 'OK' or if no, press 'Cancel.'"
                    ) == false) {
                    //return false ;
                    customer_confirmed_flg = 0;
                } else {
                    customer_confirmed_flg = 1;
                    //return true ;
                }
            }

            var logdetail = document.getElementById("trans_notes" + transid + tmpcnt).value;
            var po_delivery_date = document.getElementById("po_delivery_date" + transid + tmpcnt).value;

            document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
                "<br><br>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Data saved.");
                    //document.location = "manage_box_pending_orders.php?id=" + box_id;
                    document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
                }
            }


            xmlhttp.open("GET", "manage_box_pending_orders_save.php?donotsave_dt=0&mark_as_unavailable=0&box_id=" + box_id +
                "&tmpcnt=" + tmpcnt + "&warehouse_id=" + warehouse_id + "&transid=" + transid + "&logdetail=" +
                logdetail + "&po_delivery_date=" + po_delivery_date + "&customer_confirmed_flg=" +
                customer_confirmed_flg, true);
            xmlhttp.send();

        }

        function btn_mark_as_unavailable(warehouse_id, transid, tmpcnt, box_id, mark_as_unavailable_flg) {

            document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
                "<br><br>Loading .....<img src='images/wait_animated.gif' />";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Data saved.");
                    document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "manage_box_pending_orders_save.php?donotsave_dt=0&mark_as_unavailable=1&tmpcnt=" + tmpcnt +
                "&box_id=" + box_id + "&warehouse_id=" + warehouse_id + "&transid=" + transid +
                "&mark_as_unavailable_flg=" + mark_as_unavailable_flg, true);
            xmlhttp.send();
        }
    </script>

</head>

<body>
    <script language="JavaScript" SRC="inc/CalendarPopup.js"></script>
    <script language="JavaScript" SRC="inc/general.js"></script>
    <script language="JavaScript">
        document.write(getCalendarStyles());
    </script>
    <script language="JavaScript">
        var cal1xx = new CalendarPopup("listdiv");
        cal1xx.showNavigationDropdowns();
    </script>

    <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
    </div>
    <BR />

    <?php if ($no_of_rec == 0) { ?>
        <div style="font-size:18px; font-family: Arial, Helvetica, sans-serif; height: 26px; text-align: center;">
            No pending orders for this inventory item at this time
        </div>
    <?php } else {

        $imgasc  = '<img src="images/sort_asc.png" width="6px;" height="12px;">';
        $imgdesc = '<img src="images/sort_desc.png" width="6px;" height="12px;">';
        $sorturl = "manage_box_pending_orders.php?id=" . $_REQUEST['id'] . "&sort_order=";
    ?>

        <form name="gaylordstatuspg<?php echo $_REQUEST[" id"]; ?>" id="gaylordstatuspg
        <?php echo $_REQUEST["id"]; ?>" action="#">
            <table width="100%">
                <tr align="middle">
                    <td>&nbsp;</td>
                    <td colspan="13" style="font-size:14px; font-family: Arial, Helvetica, sans-serif; background-color: #C0CDDA; height: 16px">
                        <b>Orders</b>
                    </td>
                </tr>
                <tr align="middle">
                    <td>&nbsp;</td>
                    <td bgColor='#ABC5DF' class="style12_new">Trans ID
                        <a href="<?php echo  $sorturl; ?>ASC&sort=transid"><?php echo  $imgasc; ?></a>
                        <a href="<?php echo  $sorturl; ?>DESC&sort=transid"><?php echo  $imgdesc; ?></a>
                    </td>
                    <td bgColor='#ABC5DF' class="style12_new">Sales Order Qty
                        <a href="<?php echo  $sorturl; ?>ASC&sort=soqty"><?php echo  $imgasc; ?></a>
                        <a href="<?php echo  $sorturl; ?>DESC&sort=soqty"><?php echo  $imgdesc; ?></a>
                    </td>
                    <td bgColor='#ABC5DF' class="style12_new">Sales Order Date
                        <a href="<?php echo  $sorturl; ?>ASC&sort=sodate"><?php echo  $imgasc; ?></a>
                        <a href="<?php echo  $sorturl; ?>DESC&sort=sodate"><?php echo  $imgdesc; ?></a>
                    </td>
                    <td bgColor='#ABC5DF' class="style12_new">Planned Delivery Date
                        <a href="<?php echo  $sorturl; ?>ASC&sort=podate"><?php echo  $imgasc; ?></a>
                        <a href="<?php echo  $sorturl; ?>DESC&sort=podate"><?php echo  $imgdesc; ?></a>
                    </td>
                    <td bgColor='#ABC5DF' class="style12_new">Mark As Unavailble
                        <a href="<?php echo  $sorturl; ?>ASC&sort=markunavailble"><?php echo  $imgasc; ?></a>
                        <a href="<?php echo  $sorturl; ?>DESC&sort=markunavailble"><?php echo  $imgdesc; ?></a>
                    </td>
                    <!-- <td bgColor='#ABC5DF' class="style12_new" >Ops Delivery Date
						<a href="<?php echo  $sorturl; ?>ASC&sort=opdelidt"><?php echo  $imgasc; ?></a>
						<a href="<?php echo  $sorturl; ?>DESC&sort=opdelidt"><?php echo  $imgdesc; ?></a>
					</td> -->
                    <td bgColor='#ABC5DF' width="20%" class="style12_new">Client
                        <a href="<?php echo  $sorturl; ?>ASC&sort=cname"><?php echo  $imgasc; ?></a>
                        <a href="<?php echo  $sorturl; ?>DESC&sort=cname"><?php echo  $imgdesc; ?></a>
                    </td>
                    <td bgColor='#ABC5DF' class="style12_new">Transaction log
                        <a href="<?php echo  $sorturl; ?>ASC&sort=tslog"><?php echo  $imgasc; ?></a>
                        <a href="<?php echo  $sorturl; ?>DESC&sort=tslog"><?php echo  $imgdesc; ?></a>
                    </td>
                    <td bgColor='#ABC5DF' class="style12_new">Initials
                        <a href="<?php echo  $sorturl; ?>ASC&sort=emp_int"><?php echo  $imgasc; ?></a>
                        <a href="<?php echo  $sorturl; ?>DESC&sort=emp_int"><?php echo  $imgdesc; ?></a>
                    </td>
                    <td bgColor='#ABC5DF' class="style12_new">Date/Time
                        <a href="<?php echo  $sorturl; ?>ASC&sort=dtime"><?php echo  $imgasc; ?></a>
                        <a href="<?php echo  $sorturl; ?>DESC&sort=dtime"><?php echo  $imgdesc; ?></a>
                    </td>
                    <td bgColor='#ABC5DF' class="style12_new" colspan="2">Submit</td>
                </tr>

                <?php
                $MGarray = array();
                $cnt_no = 0;
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
                        //$po_deli_date = $so_row["po_delivery"];
                        $po_deli_date = strtotime($so_row["po_delivery"]);
                    } else {
                        //$po_deli_date = date("m/d/Y" , strtotime($so_row["po_delivery_dt"]));
                        $po_deli_date = strtotime($so_row["po_delivery_dt"]);
                    }

                    $cnt_no = $cnt_no + 1;

                    $MGarray[] = array(
                        'box_id' => $_REQUEST["id"], 'transid' => $so_row["transid"], 'mark_unavailable' => $so_row["mark_unavailable"],
                        'qty' => $so_row["QTY"], 'so_date' => $so_row["so_date"], 'po_deli_date' => $po_deli_date, 'Preorder' => $so_row["Preorder"],
                        'comp_nm' => $comp_nm, 'ops_delivery_date' => $so_row["ops_delivery_date"],
                        'warehouse_id' => $so_row["warehouse_id"], 'notes' => $trans_log_notes, 'planned_delivery_dt_confirmed' => $so_row["planned_delivery_dt_customer_confirmed"],
                        'emp_int' => $trans_log_emp, 'log_dt' => $trans_log_dt
                    );
                }

                if ($_REQUEST['sort'] == "transid") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['transid'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGarray);
                    }
                }

                if ($_REQUEST['sort'] == "soqty") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['qty'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGarray);
                    }
                }

                if ($_REQUEST['sort'] == "sodate") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['so_date'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGarray);
                    }
                }

                if ($_REQUEST['sort'] == "podate") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['po_deli_date'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGarray);
                    }
                }

                if ($_REQUEST['sort'] == "markunavailble") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['mark_unavailable'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGarray);
                    }
                }

                if ($_REQUEST['sort'] == "opdelidt") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['ops_delivery_date'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGarray);
                    }
                }

                if ($_REQUEST['sort'] == "cname") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['comp_nm'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGarray);
                    }
                }

                if ($_REQUEST['sort'] == "tslog") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['notes'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGarray);
                    }
                }

                if ($_REQUEST['sort'] == "emp_int") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['emp_int'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGarray);
                    }
                }

                if ($_REQUEST['sort'] == "dtime") {
                    $MGArraysort_I = array();

                    foreach ($MGarray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['log_dt'];
                    }

                    if ($_REQUEST['sort_order'] == "ASC") {
                        array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGarray);
                    } else {
                        array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGarray);
                    }
                }

                $cnt_no = 1;
                foreach ($MGarray as $MGArraytmp2) {
                    if (($cnt_no % 2) == 0) {
                        $bg = "#E4E4E4";
                    } else {
                        $bg = "#CCCCCC";
                    }
                ?>
                    <tr id="inventory_preord_middle_div_<?php echo $cnt_no; ?>">
                        <td> &nbsp;</td>
                        <td bgColor="<?php echo  $bg; ?>"><?php echo  $MGArraytmp2["transid"]; ?></td>
                        <td bgColor="<?php echo  $bg; ?>"><?php echo  $MGArraytmp2["qty"]; ?></td>
                        <td bgColor="<?php echo  $bg; ?>"><?php echo  $MGArraytmp2["so_date"]; ?></td>
                        <td bgColor="<?php echo  $bg; ?>">
                            <?php //=$MGArraytmp2["po_deli_date"];
                            ?>
                            <input type="hidden" name="planned_delivery_dt_confirmed<?php echo $MGArraytmp2["transid"] . $cnt_no; ?>" id="planned_delivery_dt_confirmed
                    <?php echo $MGArraytmp2["transid"] . $cnt_no; ?>" size="11" value="
                    <?php echo $MGArraytmp2["planned_delivery_dt_confirmed"]; ?>">
                            <input type="text" name="po_delivery_date<?php echo $MGArraytmp2["transid"] . $cnt_no; ?>" id="po_delivery_date
                    <?php echo $MGArraytmp2["transid"] . $cnt_no; ?>" size="11" value="<?php if ($MGArraytmp2["po_deli_date"] == "") {
                                                                                            echo "";
                                                                                        } else {
                                                                                            echo date("m/d/Y", $MGArraytmp2["po_deli_date"]);
                                                                                        } ?>">
                            <a href="#" onclick="cal1xx.select(document.gaylordstatuspg<?php echo $MGArraytmp2["box_id"];
                                                                                        ?>.po_delivery_date
                        <?php echo $MGArraytmp2["transid"] . $cnt_no; ?>, 'anchor1xx
                        <?php echo $MGArraytmp2["transid"] . $cnt_no; ?>','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx
                        <?php echo $MGArraytmp2["transid"] . $cnt_no; ?>">
                                <img border="0" src="images/calendar.jpg">
                            </a>
                            <input type="button" id="btnsave_pl_dt" name="btnsave_pl_dt" value="Save Planned Delivery Date" onclick="save_pl_dt(<?php
                                                                                                                                                echo $MGArraytmp2["warehouse_id"]; ?>,
                    <?php echo $MGArraytmp2["transid"]; ?>,<?php echo  $cnt_no; ?>, <?php echo  $MGArraytmp2['box_id']; ?>)" /><br>
                            <?php echo (($MGArraytmp2["Preorder"] == 1) ? "Pre-" : "Active ") . "Order"; ?>
                        </td>
                        <td bgColor="<?php echo  $bg; ?>">
                            <?php
                            $mark_unavailable_val = $MGArraytmp2["mark_unavailable"];

                            if ($mark_unavailable_val == 0) { ?>
                                <input type="button" id="btnmark_as_unavailable" name="btnmark_as_unavailable" value="Mark as Unavailable" onclick="btn_mark_as_unavailable(<?php
                                                                                                                                                                            echo $MGArraytmp2["warehouse_id"]; ?>,
                    <?php echo $MGArraytmp2["transid"]; ?>,<?php echo  $cnt_no; ?>, <?php echo  $MGArraytmp2['box_id']; ?>, 1)" /><br>
                            <?php } else { ?>
                                <input type="button" id="btnmark_as_unavailable" name="btnmark_as_unavailable" value="Unmark as Unavailable" onclick="btn_mark_as_unavailable(<?php
                                                                                                                                                                                echo $MGArraytmp2["warehouse_id"]; ?>,
                    <?php echo $MGArraytmp2["transid"]; ?>,<?php echo  $cnt_no; ?>, <?php echo  $MGArraytmp2['box_id']; ?>, 2)" /><br>
                            <?php }

                            if ($mark_unavailable_val == 1) {
                                echo "Action : 'Mark as Unavailable'";
                            }
                            ?>
                        </td>

                        <!-- <td bgColor="<?php echo  $bg; ?>">
							<input type="text" name="ops_delivery_date<?php echo $MGArraytmp2["transid"] . $cnt_no; ?>" id="ops_delivery_date<?php echo $MGArraytmp2["transid"] . $cnt_no; ?>" size="11" value="<?php if ($MGArraytmp2["ops_delivery_date"] == "") {
                                                                                                                                                                                                                    echo "";
                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                    echo date("m/d/Y", strtotime($MGArraytmp2["ops_delivery_date"]));
                                                                                                                                                                                                                } ?>"> 
							<a href="#" onclick="cal1xx.select(document.gaylordstatuspg<?php echo $MGArraytmp2["box_id"]; ?>.ops_delivery_date<?php echo $MGArraytmp2["transid"] . $cnt_no; ?>, 'anchor1xx<?php echo $MGArraytmp2["transid"] . $cnt_no; ?>','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx<?php echo $MGArraytmp2["transid"] . $cnt_no; ?>">
							<img border="0" src="images/calendar.jpg"></a>
						</td> -->
                        <td bgColor="<?php echo  $bg; ?>"><a target="_blank" href="search_results.php?warehouse_id=<?php echo  $MGArraytmp2["warehouse_id"]; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo  $MGArraytmp2["warehouse_id"]; ?>&rec_id=<?php echo  $MGArraytmp2["transid"]; ?>&display=buyer_view"><?php echo  $MGArraytmp2["comp_nm"]; ?></a>
                        </td>
                        <td bgColor="<?php echo  $bg; ?>"><textarea id="trans_notes<?php echo $MGArraytmp2["transid"] . $cnt_no; ?>" name="trans_notes" cols="35" rows="4"><?php echo  $MGArraytmp2["notes"]; ?></textarea></td>
                        <td bgColor="<?php echo  $bg; ?>"><?php echo  $MGArraytmp2["emp_int"]; ?></td>
                        <td bgColor="<?php echo  $bg; ?>" style="font-size: xx-small;	font-family: Arial, Helvetica, sans-serif;">
                            <?php echo  $MGArraytmp2["log_dt"]; ?></td>
                        <td bgColor="<?php echo  $bg; ?>" colspan="2"><input type="button" id="logsave" name="logsave" value="Save" onclick="savetranslog(<?php echo $MGArraytmp2["warehouse_id"]; ?>,
                    <?php echo $MGArraytmp2["transid"]; ?>,<?php echo  $cnt_no; ?>, <?php echo  $MGArraytmp2['box_id']; ?>)" />
                        </td>
                    </tr>

                <?php
                    $cnt_no += 1;
                } ?>
                <tr align="middle">
                    <td colspan="13" style="font-size: 14px; font-family: Arial, Helvetica, sans-serif; height: 16px"></td>
                </tr>
            </table>
        </form>
    <?php } ?>

</body>

</html>