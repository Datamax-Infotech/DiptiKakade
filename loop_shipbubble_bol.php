<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


$po_delivery_dt = "";
$bol_create = 0;
$pickup_or_ucb_delivering = "";
$sent_to_supplier_po_no = "";
$po_date = "";
$so_view_qry = "SELECT * FROM loop_transaction_buyer WHERE id = '" .  $_REQUEST['rec_id'] . "'";
db();
$so_view_res = db_query($so_view_qry);
while ($trans_buyer_row = array_shift($so_view_res)) {
    $pickup_or_ucb_delivering = $trans_buyer_row["customerpickup_ucbdelivering_flg"];
    $bol_create = $trans_buyer_row["bol_create"];
    $ponumber = $trans_buyer_row["po_ponumber"];
    $sent_to_supplier_po_no = $trans_buyer_row["sent_to_supplier_po_no"];

    $po_file = $trans_buyer_row["po_file"];
    $po_date = $trans_buyer_row["po_date"];

    $virtual_inventory_company_id = $trans_buyer_row["virtual_inventory_company_id"];
    $virtual_inventory_trans_id = $trans_buyer_row["virtual_inventory_trans_id"];
    if ($trans_buyer_row["po_delivery_dt"] != "") {
        $po_delivery_dt = date("m/d/Y", strtotime($trans_buyer_row["po_delivery_dt"]));
    }
}

$shipto_name = "";
$shipto_email = "";
$assignedto = "";
$account_owner_email = "";
$freightupdates = "";
$sql_x = "Select shipemail, shipContact, assignedto, freightupdates from companyInfo Where ID = " . $_REQUEST["ID"];
db_b2b();
$dt_view_res_n = db_query($sql_x);
while ($row_forb2b = array_shift($dt_view_res_n)) {
    $shipto_name = $row_forb2b["shipContact"];
    $shipto_email = $row_forb2b["shipemail"];
    $assignedto = $row_forb2b["assignedto"];
    $freightupdates = $row_forb2b["freightupdates"];
}

$sql_x = "SELECT email FROM employees where employeeID=" . $assignedto;
db_b2b();
$dt_view_res_n = db_query($sql_x);
while ($row_forb2b = array_shift($dt_view_res_n)) {
    $account_owner_email = $row_forb2b["email"];
}

$b2bid = $_REQUEST["ID"];

if ($_REQUEST["btnundoconfirmship"] == "Undo Confirm Shipped") {
    $sql3ud = "UPDATE loop_bol_files SET bol_shipped = '0', bol_shipped_employee = '',  bol_shipped_date = '' WHERE id = " . $_REQUEST["bol_id"];
    db();
    $result3ud = db_query($sql3ud);

    $sql3ud = "UPDATE loop_transaction_buyer SET shipped = '0' WHERE id = " . $_REQUEST["rec_id"];
    db();
    $result3ud = db_query($sql3ud);

    $so_view = "SELECT id,warehouse_id,rec_type,employee,virtual_inventory_company_id,virtual_inventory_trans_id, customerpickup_ucbdelivering_flg from loop_transaction_buyer WHERE id = '" . $_REQUEST["rec_id"] . "'";
    $trans_id_virtual = 0;
    $customerpickup_ucbdelivering_flg = "";
    db();
    $view_res = db_query($so_view);
    while ($so_view_row = array_shift($view_res)) {
        $trans_id_virtual = $so_view_row['virtual_inventory_trans_id'];
        $customerpickup_ucbdelivering_flg = $so_view_row['customerpickup_ucbdelivering_flg'];
    }

    //Virtual Link
    if ($trans_id_virtual > 0) {
        $sql = "UPDATE loop_transaction SET cp_notes = '',  cp_employee = '', cp_date = '' WHERE id = '" . $trans_id_virtual . "'";
        db();
        $result = db_query($sql);

        $sql = "UPDATE loop_transaction SET bol_file = '', bol_employee = '', bol_date = '' WHERE id = '" . $trans_id_virtual . "'";
        db();
        $result = db_query($sql);
        $view_res = db_query("Select warehouse_id from loop_transaction where id = " . $trans_id_virtual);
        $manf_warehouse_id = 0;
        while ($so_view_row = array_shift($view_res)) {
            $manf_warehouse_id = $so_view_row['warehouse_id'];
        }
    }

    if ($customerpickup_ucbdelivering_flg == "1") {
        $sql3ud = "UPDATE loop_bol_files SET bol_shipment_received = '0', bol_shipment_received_employee = '',  bol_shipment_received_date = '' WHERE trans_rec_id = " . $_REQUEST["rec_id"];
        db();
        $result3ud = db_query($sql3ud);
    }
}
?>
<LINK rel='stylesheet' type='text/css' href='one_style_mrg.css'>

<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">
document.write(getCalendarStyles());
</script>
<script LANGUAGE="JavaScript">
var cal1xx = new CalendarPopup("listdiv");
cal1xx.showNavigationDropdowns();
var cal2xx = new CalendarPopup("listdiv");
cal2xx.showNavigationDropdowns();
var cal3xx = new CalendarPopup("listdiv");
cal3xx.showNavigationDropdowns();
var cal4xx = new CalendarPopup("listdiv");
cal4xx.showNavigationDropdowns();
var cal5xx = new CalendarPopup("listdiv");
cal5xx.showNavigationDropdowns();

function display_file(val) {
    document.getElementById('fileview').innerHTML = "<embed src='" + val + "' width='700' height='800'>";
}

function check_form(uploadBOL) {
    var error = 0;
    var checkstring = "Please take a moment to complete required fields:\n";

    if (document.uploadBOL.file.value == "") {
        checkstring = checkstring + "Upload Signed BOL\n";
        error = 1;
    }

    if (error == 1) {
        alert(checkstring);
        return false;
    }
}

function disable_btn() {
    document.getElementById('btncreatebol').style.display = "none";
}

function f_getPosition(e_elemRef, s_coord) {
    var n_pos = 0,
        n_offset,
        //e_elem = selectobject;
        e_elem = e_elemRef;
    while (e_elem) {
        n_offset = e_elem["offset" + s_coord];
        n_pos += n_offset;
        e_elem = e_elem.offsetParent;

    }
    e_elem = e_elemRef;
    //e_elem = selectobject;
    while (e_elem != document.body) {
        n_offset = e_elem["windows" + s_coord];
        if (n_offset && e_elem.style.overflow == 'windows')
            n_pos -= n_offset;
        e_elem = e_elem.parentNode;
    }

    return n_pos;

}
</script>

<form action="addshippbol_mrg_new.php" method="post" encType="multipart/form-data" name="BOL">
    <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST[" warehouse_id"]; ?>" />
    <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>" />
    <input type="hidden" name="rec_type" value="<?php echo $_REQUEST[" rec_type"]; ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />
    <input type="hidden" name="virtual_inventory_trans_id" value="<?php echo isset($virtual_inventory_trans_id); ?>" />
    <input type="hidden" name="virtual_inventory_company_id"
        value="<?php echo isset($virtual_inventory_company_id); ?>" />

    <table cellSpacing="1" cellPadding="1" border="0" id="table4">
        <tr align="middle">
            <?php if ($bol_create == 1) { ?>
            <td bgColor="#99FF99" colSpan="10">
                <?php } else { ?>
            <td bgColor="#fb8a8a" colSpan="10">
                <?php } ?>
                <font size="1">CREATE BOL
                    <?php if (isset($virtual_inventory_trans_id) > 0) {
                        echo " with SORT REPORT ENTRY id = ";
                        echo isset($virtual_inventory_trans_id);
                    } ?>
                </font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="center">
                QUANTITY</td>
            <td height="13" style="width: 98px" class="style1" align="center">
                PALLETS</td>
            <?php if (isset($virtual_inventory_trans_id) > 0) { ?>
            <td height="13" style="width: 78px" class="style1" align="center">
                COST</td>
            <?php } ?>
            <td height="13" style="width: 25px" class="style1" align="center">
                ORDERED</td>
            <td height="13" style="width: 78px" class="style1" align="center">
                WAREHOUSE</td>
            <td height="13" class="style1" align="center">
                PER PALLET</td>
            <td height="13" class="style1" align="center">
                PER TRAILER</td>
            <td align="left" height="13" class="style1">
                DESCRIPTION</td>
            <td align="left" height="13" class="style1">
                ADDITIONAL SHIPPER INFO.</td>
        </tr>


        <?php

        $i = 0;
        $loc_warehouse_id = 0;
        $query_so = "SELECT * FROM loop_salesorders WHERE trans_rec_id = " . $_REQUEST['rec_id'];
        //echo $query_so;
        db();
        $result_so = db_query($query_so);
        while ($so_row = array_shift($result_so)) {
            $loc_warehouse_id = $so_row["location_warehouse_id"];
            $so_initials = $so_row["employee"]; // initials of the rep
            $get_boxes_query = db_query("SELECT * FROM loop_boxes WHERE id = " . $so_row["box_id"]);
            $count = tep_db_num_rows($get_boxes_query);
            $boxcost_val = 0;
            $count_and_one = $count + 1;
            $i++;
            $str_boxdesc = "";
            $bpallet_qty = 0;
            $boxes_per_trailer = 0;
            while ($boxes = array_shift($get_boxes_query)) {

                $notes = $so_row["notes"];
                $boxcost_val = $boxes["boxgoodvalue"];
                $str_boxdesc = "<font size='1' Face='arial'>" . $boxes["bdescription"] . "</font>";
                $str_boxsku = "<font size='1' Face='arial'>" . $boxes["sku"] . "</font>";
                $bpallet_qty = $boxes["bpallet_qty"];
                $boxes_per_trailer = $boxes["boxes_per_trailer"];
            }

            $qty = "";
            $pallets = "";
            $add_info = "";
            $quantity1 = "";
            $pallet1 = "";
            $weight1 = "";
            $description1 = "";
            $add_shipp_info1 = "";
            $quantity2 = "";
            $pallet2 = "";
            $weight2 = "";
            $description2 = "";
            $add_shipp_info2 = "";
            $quantity3 = "";
            $pallet3 = "";
            $weight3 = "";
            $description3 = "";
            $add_shipp_info3 = "";
            $quantity4 = "";
            $pallet4 = "";
            $weight4 = "";
            $description4 = "";
            $add_shipp_info4 = "";
            $bol_pickupdate = "";
            $bol_STL1 = "";
            $bol_STL2 = "";
            $bol_STL3 = "";
            $seal_no_box = "";
            $bol_STL4 = "";
            $add_shipfrom_info1 = "";
            $add_shipfrom_info2 = "";
            $add_shipfrom_info3 = "";
            $carrier_name = "";
            $trailer_no = "";
            $bol_class = "";
            $bol_freight_biller = "";
            $bol_instructions = "";
            $get_boxes_query = db_query("SELECT * FROM loop_bol_tracking WHERE trans_rec_id = " . $_REQUEST['rec_id'] . " and box_id = " . $so_row["box_id"]);
            while ($boxes = array_shift($get_boxes_query)) {
                $qty = $boxes["qty"];
                $pallets = $boxes["pallets"];
                $add_info = $boxes["add_shipp_info"];

                $quantity1 = $boxes["quantity1"];
                $pallet1 = $boxes["pallet1"];
                $weight1 = $boxes["weight1"];
                $description1 = $boxes["description1"];
                $add_shipp_info1 = $boxes["add_shipp_info1"];

                $quantity2 = $boxes["quantity2"];
                $pallet2 = $boxes["pallet2"];
                $weight2 = $boxes["weight2"];
                $description2 = $boxes["description2"];
                $add_shipp_info2 = $boxes["add_shipp_info2"];

                $quantity3 = $boxes["quantity3"];
                $pallet3 = $boxes["pallet3"];
                $weight3 = $boxes["weight3"];
                $description3 = $boxes["description3"];
                $add_shipp_info3 = $boxes["add_shipp_info3"];

                $quantity4 = $boxes["quantity4"];
                $pallet4 = $boxes["pallet4"];
                $weight4 = $boxes["weight4"];
                $description4 = $boxes["description4"];
                $add_shipp_info4 = $boxes["add_shipp_info4"];

                $bol_pickupdate = $boxes["bol_pickupdate"];
                $bol_STL1 = $boxes["bol_STL1"];
                $bol_STL2 = $boxes["bol_STL2"];
                $bol_STL3 = $boxes["bol_STL3"];
                $bol_STL4 = $boxes["bol_STL4"];
                $add_shipfrom_info1 = $boxes["add_shipfrom_info1"];
                $add_shipfrom_info2 = $boxes["add_shipfrom_info2"];
                $add_shipfrom_info3 = $boxes["add_shipfrom_info3"];
                $carrier_name = $boxes["carrier_name"];
                $trailer_no = $boxes["trailer_no"];
                $seal_no_box = $boxes["seal_no_box"];
                $bol_class = $boxes["bol_class"];
                $bol_freight_biller = $boxes["bol_freight_biller"];
                $bol_instructions = $boxes["bol_instructions"];
            }

        ?>
        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="right"><input size="5" type="text" name="qty[]"
                    value="<?php echo $qty; ?>"></td>
            <td height="13" class="style1" align="right"><input size="5" type="text" name="pallets[]"
                    value="<?php echo $pallets; ?>"></td>
            <?php if (isset($virtual_inventory_trans_id) > 0) { ?>
            <td height="13" class="style1" align="right"><input size="5" type="text" name="cost[]"
                    value="<?php echo $boxcost_val; ?>"></td>
            <?php } ?>
            <td height="13" class="style1" align="right">
                <?php echo $so_row["qty"]; ?>
            </td>
            <td height="13" class="style1" align="center"><a style="color:#0000FF;" target="_blank"
                    href="manage_sortwh_mrg.php?id=<?php echo $so_row[" location_warehouse_id"]; ?>&proc=Edit&">
                    <?php
                        $gsql = "SELECT * FROM loop_warehouse WHERE id = " . $so_row["location_warehouse_id"];
                        db();
                        $gresult = db_query($gsql);
                        while ($gmyrowsel = array_shift($gresult)) {
                            echo $gmyrowsel["warehouse_name"];
                        } ?>
                </a><input type="hidden" name="location_warehouse_id[]" value="<?php echo $so_row["
                    location_warehouse_id"]; ?>"></td>

            <td height="13" class="style1" align="right">
                <?php echo $bpallet_qty; ?>
            </td>
            <td height="13" class="style1" align="right">
                <?php echo $boxes_per_trailer; ?>
            </td>

            <td align="left" height="13" class="style1"><input type="hidden" name="box_id[]" value="<?php echo $so_row["
                    box_id"]; ?>" />
                <font size="1" Face="arial"><a style="color:#0000FF;" target="_blank"
                        href="manage_box_b2bloop.php?id=<?php echo $so_row[" box_id"]; ?>&proc=View&">
                        <?php
                            echo $str_boxdesc; ?>
                    </a></font>
            </td>

            <td height="13" class="style1" align="right">
                <!-- <input size="10" type="text" name="add_shipp_info[]" value="<?php echo $add_info; ?>"> -->
                <?php
                    if ($sent_to_supplier_po_no == "") {
                        echo '<input type="text" size="10" name="add_shipp_info[]" value="' . $add_info . '">';
                    } else {
                        echo '<input type="text" size="10" name="add_shipp_info[]" value="UCB PO # ' . $sent_to_supplier_po_no . '">';
                    }
                    ?>

            </td>
        </tr>

        <?php

        }

        ?>
        <!---------------
<?php
//$i=0;
$query_so = "SELECT * FROM loop_salesorders_manual WHERE trans_rec_id = " . $_REQUEST['rec_id'];

db();
$result_so = db_query($query_so);
while ($so_row = array_shift($result_so)) {
?>
<tr bgColor="#e4e4e4">
<td height="13" class="style1" align="right"><input size="5" type="text" name="qty[]"></td>
<td height="13" class="style1" align="right"><input size="5" type="text" name="pallets[]"></td>
<td height="13" style="width: 78px" class="style1" align="right"><?php echo $so_row["qty"]; ?></td>
<td align="left" height="13" class="style1" colspan="2"> <?php echo $so_row["description"]; ?></td>
	</tr>

<?php

}

?>
------------------->
        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="center">
                QUANTITY</td>
            <td height="13" class="style1" align="center">
                PALLETS</td>
            <td height="13" class="style1" align="center">
                WEIGHT</td>
            <td height="13" class="style1" align="center">
                DESCRIPTION</td>
            <td height="13" class="style1" align="center" colspan=6>
                ADDITIONAL SHIPPER INFO</td>

        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="right"><input size="5" type="text" name="quantity1"
                    value="<?php echo isset($quantity1); ?>"></td>
            <td height="13" class="style1" align="right"><input size="5" type="text" name="pallet1"
                    value="<?php echo isset($pallet1); ?>"></td>
            <td height="13" style="width: 78px" class="style1" align="right"><input size="5" type="text" name="weight1"
                    value="<?php echo isset($weight1); ?>"></td>
            <td align="left" height="13" class="style1">
                <input type=text size=25 name="description1" value="<?php echo isset($description1); ?>">
            </td>
            <td align="left" height="13" class="style1" colspan=6>
                <input type=text size=25 name="add_shipp_info1" value="<?php echo isset($add_shipp_info1); ?>">
            </td>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="right"><input size="5" type="text" name="quantity2"
                    value="<?php echo isset($quantity2); ?>"></td>
            <td height="13" class="style1" align="right"><input size="5" type="text" name="pallet2"
                    value="<?php echo isset($pallet2); ?>"></td>
            <td height="13" style="width: 78px" class="style1" align="right"><input size="5" type="text" name="weight2"
                    value="<?php echo isset($weight2); ?>"></td>
            <td align="left" height="13" class="style1">
                <input type=text size=25 name="description2" value="<?php echo isset($description2); ?>">
            </td>
            <td align="left" height="13" class="style1" colspan=6>
                <input type=text size=25 name="add_shipp_info2" value="<?php
                                                                        echo isset($add_shipp_info2); ?>">
            </td>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="right"><input size="5" type="text" name="quantity3"
                    value="<?php echo isset($quantity3); ?>"></td>
            <td height="13" class="style1" align="right"><input size="5" type="text" name="pallet3"
                    value="<?php echo isset($pallet3); ?>"></td>
            <td height="13" style="width: 78px" class="style1" align="right"><input size="5" type="text" name="weight3"
                    value="<?php echo isset($weight3); ?>"></td>
            <td align="left" height="13" class="style1">
                <input type=text size=25 name="description3" value="<?php echo isset($description3); ?>">
            </td>
            <td align="left" height="13" class="style1" colspan=6>
                <input type=text size=25 name="add_shipp_info3" value="<?php echo isset($add_shipp_info3); ?>">
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" class="style1" align="right"><input size="5" type="text" name="quantity4"
                    value="<?php echo isset($quantity4); ?>"></td>
            <td height="13" class="style1" align="right"><input size="5" type="text" name="pallet4"
                    value="<?php echo isset($pallet4); ?>"></td>
            <td height="13" style="width: 78px" class="style1" align="right"><input size="5" type="text" name="weight4"
                    value="<?php echo isset($weight4); ?>"></td>
            <td align="left" height="13" class="style1">
                <input type=text size=25 name="description4" value="<?php echo isset($description4); ?>">
            </td>
            <td align="left" height="13" class="style1" colspan=6>
                <input type=text size=25 name="add_shipp_info4" value="<?php echo isset($add_shipp_info4); ?>">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td colspan=10 style="width: 391px" height="13" class="style1" align="left">
                <b><?php echo  isset($notes); ?></b>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Pickup Date</td>
            <td height="13" class="style1" align="left" colspan="8">

                <Font Face='arial' size='2'>

                    <Font size='1' Face="arial">
                        <?php if ($_GET["bol_edit"] == "true") { ?>

                        <input type=text name="bol_pickupdate"
                            value="<?php echo isset($dt_view_row[" bol_pickupdate"]); ?>" size="20"> <a
                            style="color:#0000FF;" href="#"
                            onclick="cal2xx.select(document.BOL.bol_pickupdate,'anchor2xx','MM/dd/yyyy'); return false;"
                            name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>

                        <?php } else { ?>
                        <input type=text name="bol_pickupdate" value="<?php
                                                                            if (isset($bol_pickupdate) == "") {
                                                                                echo date('m/d/Y');
                                                                            } else {
                                                                                echo isset($bol_pickupdate);
                                                                            } ?>" size="20"> <a style="color:#0000FF;"
                            href="#"
                            onclick="cal2xx.select(document.BOL.bol_pickupdate,'anchor2xx','MM/dd/yyyy'); return false;"
                            name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
                        <?php } ?>
                    </font>
                    <div ID="listdiv"
                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
            </td>
        </tr>
        <?php

        if ($b2bid > 0) {
            $sql_x = "SELECT * FROM loop_warehouse WHERE id = " . $loc_warehouse_id;
            db();
            $dt_view_res_n = db_query($sql_x);
            while ($warehouse_row = array_shift($dt_view_res_n)) {
        ?>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship From Line 1</td>
            <td height="13" class="style1" align="left" colspan="8">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="shipfroml1" value="<?php if (isset($add_shipfrom_info1) == "") {
                                                                                    echo $warehouse_row["warehouse_name"];
                                                                                } else {
                                                                                    echo isset($add_shipfrom_info1);
                                                                                } ?>">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship From Line 2</td>
            <td height="13" class="style1" align="left" colspan="8">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="shipfroml2" value="<?php
                                                                                if (isset($add_shipfrom_info2) == "") {
                                                                                    echo $warehouse_row["warehouse_address1"];
                                                                                    if ($warehouse_row["warehouse_address2"] != "") echo ", " .
                                                                                        $warehouse_row["warehouse_address2"];
                                                                                } else {
                                                                                    echo isset($add_shipfrom_info2);
                                                                                } ?> ">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship From Line 3</td>
            <td height="13" class="style1" align="left" colspan="8">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="shipfroml3" value="<?php
                                                                                if (isset($add_shipfrom_info3) == "") {
                                                                                    echo $warehouse_row["warehouse_city"] . ", " . $warehouse_row["warehouse_state"] . " " .
                                                                                        $warehouse_row["warehouse_zip"];
                                                                                } else {
                                                                                    echo isset($add_shipfrom_info3);
                                                                                } ?>">
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" colspan="9">&nbsp;</td>
        </tr>
        <?php

            }

            $sql_x = "Select * from companyInfo Where ID = " . $b2bid;
            db_b2b();
            $dt_view_res_n = db_query($sql_x);
            while ($row_forb2b = array_shift($dt_view_res_n)) {
            ?>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship to Line 1</td>
            <td height="13" class="style1" align="left" colspan="3">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="stl1" value="<?php
                                                                            if (isset($bol_STL1) == "") {
                                                                                echo $row_forb2b[" company"];
                                                                            } else {
                                                                                echo $bol_STL1;
                                                                            } ?>">
            </td>
            <td height="13" style="width: 98px" class="style1" align="left">
                Carrier Name
            </td>
            <td height="13" class="style1" align="left" colspan="4">
                <input size="20" type="text" name="carriername" value="<?php echo isset($carrier_name); ?>">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship to Line 2</td>
            <td height="13" class="style1" align="left" colspan="3">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="stl2" value="<?php if (isset($bol_STL2) == "") {
                                                                                echo $row_forb2b[" shipAddress"];
                                                                                if ($row_forb2b["shipAddress2"] != "") echo ", " . $row_forb2b["shipAddress2"];
                                                                            } else {
                                                                                echo isset($bol_STL2);
                                                                            } ?>">
            </td>
            <td height="13" class="style1" align="left">
                Trailer #
            </td>
            <td height="13" class="style1" align="left" colspan="4">
                <input size="5" type="text" name="trailer_number" value="<?php echo isset($trailer_no); ?>">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship to Line 3</td>
            <td height="13" class="style1" align="left" colspan="3">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="stl3" value="<?php if (isset($bol_STL3) == "") {
                                                                                echo $row_forb2b[" shipCity"] . ", " .
                                                                                    $row_forb2b["shipState"] . " " . $row_forb2b["shipZip"];
                                                                            } else {
                                                                                echo isset($bol_STL3);
                                                                            } ?>">
            </td>
            <td height="13" class="style1" align="left">
                Class
            </td>
            <td height="13" class="style1" align="left" colspan="4">
                <input size="5" type="text" name="bol_class" value="<?php if (isset($bol_class) == "") {
                                                                                echo " 125";
                                                                            } else {
                                                                                echo isset($bol_class);
                                                                            } ?>">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship To Line 4</td>
            <td height="13" class="style1" align="left" colspan="3">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="stl4" value="<?php if (isset($bol_STL4) == "") {
                                                                                echo " Contact:" .
                                                                                    $row_forb2b["shipContact"] . ", " . $row_forb2b["shipPhone"];
                                                                            } else {
                                                                                echo isset($bol_STL4);
                                                                            } ?>">
            </td>
            <td height="13" class="style1" align="left">
                Seal #
            </td>
            <td height="13" class="style1" align="left" colspan="4">
                <input size="20" type="text" name="seal_no_box" id="seal_no_box"
                    value="<?php
                                                                                                echo isset($seal_no_box); ?>">
            </td>
        </tr>
        <?php }
        } else { ?>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship to Line 1</td>
            <td height="13" class="style1" align="left" colspan="3">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="stl1" value="<?php if (isset($bol_STL1) == "") {
                                                                            echo isset($warehouse_name);
                                                                        } else {
                                                                            echo $bol_STL1;
                                                                        } ?>">
            </td>
            <td height="13" style="width: 98px" class="style1" align="left">
                Carrier Name
            </td>
            <td height="13" class="style1" align="left" colspan="4">
                <input size="20" type="text" name="carriername" value="<?php echo isset($carrier_name); ?>">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship to Line 2</td>
            <td height="13" class="style1" align="left" colspan="3">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="stl2" value="<?php if (isset($bol_STL2) == "") {
                                                                            echo isset($warehouse_address1);
                                                                            if (isset($warehouse_address2) != "") echo " , " . isset($warehouse_address2);
                                                                        } else {
                                                                            echo isset($bol_STL2);
                                                                        } ?>">
            </td>
            <td height="13" class="style1" align="left">
                Trailer #
            </td>
            <td height="13" class="style1" align="left" colspan="4">
                <input size="5" type="text" name="trailer_number" value="<?php echo isset($trailer_no); ?>">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship to Line 3</td>
            <td height="13" class="style1" align="left" colspan="3">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="stl3" value="<?php if (isset($bol_STL3) == "") {
                                                                            echo isset($warehouse_city) . "
                        , " . isset($warehouse_state) . " " . isset($warehouse_zip);
                                                                        } else {
                                                                            echo isset($bol_STL3);
                                                                        } ?>">
            </td>
            <td height="13" class="style1" align="left">
                Class
            </td>
            <td height="13" class="style1" align="left" colspan="4">
                <input size="5" type="text" name="bol_class" value="<?php if (isset($bol_class) == "") {
                                                                            echo " 125";
                                                                        } else {
                                                                            echo isset($bol_class);
                                                                        }
                                                                        ?>">
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Ship To Line 4</td>
            <td height="13" class="style1" align="left" colspan="3">
                <Font Face='arial' size='2'>
                    <input size="40" type=text name="stl4" value="<?php if (isset($bol_STL4) == "") {
                                                                            if (isset($warehouse_manager) != "")
                                                                                echo "Contact: " . isset($warehouse_manager) . " , " .
                                                                                    isset($warehouse_manager_phone);
                                                                        } else {
                                                                            echo $bol_STL4;
                                                                        } ?>">
            </td>
            <td height="13" class="style1" align="left">
                Seal #
            </td>
            <td height="13" class="style1" align="left" colspan="4">
                <input size="20" type="text" name="seal_no_box" id="seal_no_box"
                    value="<?php echo isset($seal_no_box); ?>">
            </td>

        </tr>
        <?php } ?>

        <?php

        $bol_freight_biller_tmp = 0;
        $sql = "SELECT broker_id FROM loop_transaction_buyer_freightview WHERE trans_rec_id = " . $_REQUEST["rec_id"];
        db();
        $sql_res = db_query($sql);
        while ($row = array_shift($sql_res)) {
            $bol_freight_biller_tmp = $row["broker_id"];
        }

        $sql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $_REQUEST["rec_id"];
        db();
        $result2 = db_query($sql);
        $myrowsel = array_shift($result2);

        if (isset($bol_freight_biller) == "" && $pickup_or_ucb_delivering == 1) {
            $bol_freight_biller = 298;
        }

        if (isset($bol_freight_biller) == "" && $pickup_or_ucb_delivering == 2) {
            $bol_freight_biller = $bol_freight_biller_tmp;
        }

        $tms_freight_global = "";

        $sql_upd = "Select * from loop_enterprise_tms_data where trans_rec_id = " . $_REQUEST["rec_id"];
        $result_comp_upd = db_query($sql_upd);
        while ($row_comp_upd = array_shift($result_comp_upd)) {
            $tms_freight_global = $row_comp_upd["assign_freight"];
        }

        ?>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Freight Broker
            </td>
            <td height="13" class="style1" align="left" colspan="8">
                <select size="1" name="bol_freight_biller" id="bol_freight_biller">
                    <?php if ($_GET["bol_edit"] == "true") { ?>
                    <option value="<?php echo isset($dt_view_row["id"]); ?>" SELECTED>
                        <?php echo isset($dt_view_row["bol_freight_biller"]); ?>
                    </option>
                    <?php } else { ?>
                    <option value="">Please Select</option>
                    <?php } ?>
                    <?php

                    $fsql = "SELECT * FROM loop_freightvendor ORDER BY company_name ASC";
                    db();
                    $fresult = db_query($fsql);
                    while ($fmyrowsel = array_shift($fresult)) {
                    ?>
                    <option <?php if (isset($bol_freight_biller) == $fmyrowsel["id"]) {
                                    echo " selected ";
                                } elseif ($tms_freight_global == $fmyrowsel["company_name"]) {
                                    echo " selected ";
                                } ?> value="
                        <?php echo $fmyrowsel["id"]; ?>">
                        <?php echo $fmyrowsel["company_name"]; ?>
                    </option>
                    <?php } ?>
                    <option value="0">No 3rd Party Biller
                </select>&nbsp;
            </td>
        </tr>

        <?php

        // Shipper SOPO PHP Code
        $shipper_get_loop_id_query = db_query("Select loop_salesorders.box_id from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $_REQUEST["rec_id"]);
        $shipper_get_loop_id_data = array_shift($shipper_get_loop_id_query);

        $running_loops_id = $shipper_get_loop_id_data['box_id'];

        $require_externalSOPO_sql_query = "SELECT externalSOPO_shipper_input FROM `inventory` WHERE loops_id = '" . $running_loops_id . "' && require_externalSOPO = 1";
        db_b2b();
        $require_externalSOPO_fetch = db_query($require_externalSOPO_sql_query);
        $require_externalSOPO_data = array_shift($require_externalSOPO_fetch);

        $shipper_txt = isset($require_externalSOPO_data['externalSOPO_shipper_input']) ? $require_externalSOPO_data['externalSOPO_shipper_input'] : '';

        ?>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 98px" class="style1" align="left">
                Special Instructions
            </td>
            <td height="13" class="style1" align="right" colspan="8">
                <Font Face='arial' size='2'>
                    <p align="left">

                        <textarea cols=40 rows=3 name="bol_instructions"><?php
                                                                            if (isset($bol_instructions) == "") {
                                                                                echo  "Receiver PO #: " . isset($ponumber);
                                                                            } else {
                                                                                echo isset($bol_instructions);
                                                                            } ?>
		<?php if ($shipper_txt != "") { ?>
			&#013;&#010;Shipper SO/PO#: <?php echo  $shipper_txt ?>
		<?php } ?>	
	</textarea>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td colspan=6 align="left" height="19" class="style1">
                <p align="center"> <input type="hidden" name="count" value="<?php echo $i; ?>">

                    <input type="submit" name="btncreatebol" id="btncreatebol" value="Create BOL"
                        style="cursor:pointer;" onclick="disable_btn()">

</form>
</td>
</tr>

</table>

<br><br>




<?php

$so_view_qry = "SELECT * from loop_bol_files WHERE trans_rec_id = '" . $_REQUEST["rec_id"] . "' ORDER BY id DESC";
db();
$so_view_res = db_query($so_view_qry);
$num_rows = tep_db_num_rows($so_view_res);
if ($num_rows > 0) {

?>


<?php

    $no = 0;
    //echo $so_view_qry."<br>";
    $unassign_bol_file = "";
    while ($so_view_row = array_shift($so_view_res)) {
        $unassign_bol_file = $so_view_row["file_name"];
        $bol_signed_file_name = $so_view_row["bol_signed_file_name"];
        $no++;
    ?>

<br>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 100%" id="table5">
    <tr align="middle">
        <?php if ($so_view_row["bol_shipped"] == 0) { ?>
        <td bgColor="#fb8a8a" colSpan="8">
            <?php } else { ?>
        <td bgColor="#99FF99" colSpan="8">
            <?php } ?>
            <p align="left">
                <font size="1">PREVIOUSLY CREATED/UPLOADED BILLS OF LADING</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td height="13" class="style1" align="center">
            <p align="left">BOL
        </td>
        <td height="13" class="style1" align="center">
            <p align="left">DATE
        </td>
        <td align="center" height="13" class="style1">
            <p align="left">CREATED BY
        </td>
        <td align="center" height="13" class="style1">
            Email BOL to Shipper/Broker</td>
        <td align="center" height="13" class="style1">
            SHIPPED</td>
        <td align="center" height="13" class="style1">
            CONFIRM SHIPPED EMAIL</td>
        <td align="center" height="13" class="style1">
            SIGNED BOL BY DRIVER</td>
        <td align="center" height="13" class="style1">
            DELETE</td>
    </tr>





    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 75px" class="style1" align="right">
            <p align="left">
                <a style="color:#0000FF;" target="_blank" href="./bol/<?php echo $so_view_row[" file_name"]; ?>">View
                    Unsigned BOL</a>
        </td>


        <Font size='1'>

            <td height="13" style="width: 80px" class="style1" align="center">
                <p align="left">
                    <?php echo date("m/d/Y H:i:s", strtotime($so_view_row["bo_date"])) . " CT"; ?>
            </td>


            <Font Face='arial' size='2'>

                <td align="center" height="13" style="width: 96px" class="style1">
                    <p align="left">
                        <font size="1" Face="arial">
                            <?php echo $so_view_row["employee"]; ?>
                        </font>
                </td>



                <td align="center" height="13" style="width: 137px" class="style1">



                    <?php if ($so_view_row["bol_received"] == 0) {

                            ?>
                    <form action="bol_confirmreceipt_mrg.php" method=post>
                        <input type="hidden" name="filename" value="<?php echo $so_view_row[" file_name"]; ?>">
                        <input type="hidden" name="rec_id" value="<?php echo $so_view_row[" trans_rec_id"]; ?>">
                        <input type="hidden" name="bol_id" value="<?php echo $so_view_row[" id"]; ?>">
                        <input type="hidden" name="userinitials" value="<?php echo $_COOKIE[" userinitials"]; ?>">
                        <input type="hidden" name="location" value="loops">
                        <input type="hidden" name="warehouse_id" value="<?php echo $so_view_row[" warehouse_id"]; ?>">
                        <!-- <input style="cursor:pointer;" type=submit value="Email BOL to Shipper/Broker"> -->

                        <input type="button" id="emailBOL" value="Email BOL to Shipper/Broker" onclick="parent.reminder_popup_bol_shipper(<?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $_REQUEST["warehouse_id"]; ?>,
                        <?php echo $_REQUEST["rec_type"]; ?>,
                        <?php echo $pickup_or_ucb_delivering; ?>)">
                    </form>

                    <div id="bol_div_ignore">
                        <font size="1" Face="arial">
                            <?php
                                        if ($so_view_row["bol_received_ignore"] == 1) {
                                            echo "Step is ignore by " . $so_view_row["bol_received_ignore_by"] . " on " . date("m/d/Y H:i:s", strtotime($so_view_row["bol_received_ignore_date"])) . " CT";
                                        } else {
                                        ?>
                            <input style="cursor:pointer;" id="btnpo_ignore" type="button" value="Ignore this Step"
                                onclick="parent.bol_ignore('bol_received_ignore', <?php echo $_REQUEST[" rec_id"]; ?>,
                            <?php echo $_REQUEST["warehouse_id"]; ?>);">
                            <?php    }
                                        ?>
                        </font>
                    </div>
                    <?php
                            } else {
                            ?>
                    Confirmed By:
                    <?php echo  $so_view_row["bol_received_employee"]; ?><br><?php echo  date("m/d/Y H:i:s", strtotime($so_view_row["bol_received_date"])) . " CT"; ?>

                    <input type="button" id="emailBOL" value="Re-Send Email BOL to Shipper/Broker" onclick="parent.reminder_popup_bol_shipper(<?php echo $_REQUEST[" ID"]; ?>,
                    <?php echo $_REQUEST["rec_id"]; ?>,
                    <?php echo $_REQUEST["warehouse_id"]; ?>,
                    <?php echo $_REQUEST["rec_type"]; ?>,
                    <?php echo $pickup_or_ucb_delivering; ?>)">
                    <br>
                    <div id="bol_div_ignore">
                        <font size="1" Face="arial">
                            <?php
                                        if ($so_view_row["bol_received_ignore"] == 1) {
                                            echo "Step is ignore by " . $so_view_row["bol_received_ignore_by"] . " on " . date("m/d/Y H:i:s", strtotime($so_view_row["bol_received_ignore_date"])) . " CT";
                                        } else {
                                        ?>
                            <input style="cursor:pointer;" id="btnpo_ignore" type="button" value="Ignore this Step"
                                onclick="parent.bol_ignore('bol_received_ignore', <?php echo $_REQUEST[" rec_id"]; ?>,
                            <?php echo $_REQUEST["warehouse_id"]; ?>);">
                            <?php    }
                                        ?>
                        </font>
                    </div>
                    <?php
                            }
                            ?>
                </td>

                <td align="center" height="13" style="width: 139px" class="style1">


                    <?php if ($so_view_row["bol_shipped"] == 0) {
                            ?>
                    <form action="bol_confirmshipped_mrg.php" method="post">
                        <input type=hidden name="ID" value="<?php echo  $_REQUEST["ID"]; ?>">
                        <input type=hidden name="rec_type" value="<?php echo  $_REQUEST["rec_type"]; ?>">
                        <input type=hidden name="id" value="<?php echo  $_REQUEST["warehouse_id"]; ?>">
                        <input type=hidden name="b2bid" value="<?php echo  $b2bid; ?>">

                        <input type="hidden" name="filename" value="<?php echo $so_view_row[" file_name"]; ?>">
                        <input type="hidden" name="rec_id" value="<?php echo $so_view_row[" trans_rec_id"]; ?>">
                        <input type="hidden" name="bol_id" value="<?php echo $so_view_row[" id"]; ?>">
                        <input type="hidden" name="userinitials" value="<?php echo $_COOKIE[" userinitials"]; ?>">
                        <input type="hidden" name="location" value="loops">
                        <input type="hidden" name="warehouse_id" value="<?php echo $so_view_row[" warehouse_id"]; ?>">
                        <input style="cursor:pointer;" type=submit value="Confirm Shipped">
                    </form>
                    <?php
                            } else {
                            ?>
                    Confirmed By:
                    <?php echo  $so_view_row["bol_shipped_employee"]; ?><br><?php echo  date("m/d/Y H:i:s", strtotime($so_view_row["bol_shipped_date"])) . " CT"; ?>
                    <form action="loop_shipbubble_bol.php" name="frmbol_confirmshipped_reverse"
                        id="frmbol_confirmshipped_reverse" method="post">
                        <input type=hidden name="ID" value="<?php echo  $_REQUEST["ID"]; ?>">
                        <input type=hidden name="id" value="<?php echo  $_REQUEST["warehouse_id"]; ?>">
                        <input type=hidden name="rec_type" value="<?php echo  $_REQUEST["rec_type"]; ?>">
                        <input type=hidden name="rec_id" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                        <input type=hidden name="b2bid" value="<?php echo  $b2bid; ?>">

                        <input type="hidden" name="filename" value="<?php echo $so_view_row[" file_name"]; ?>">
                        <input type="hidden" name="bol_id" value="<?php echo $so_view_row[" id"]; ?>">
                        <input type="hidden" name="userinitials" value="<?php echo $_COOKIE[" userinitials"]; ?>">
                        <input type="hidden" name="warehouse_id" value="<?php echo $so_view_row[" warehouse_id"]; ?>">

                        <input type="submit" name="btnundoconfirmship" id="btnundoconfirmship"
                            value="Undo Confirm Shipped">
                    </form>

                    <?php
                            }
                            ?>
                </td>

                <td align="center" height="13" style="width: 139px" class="style1">

                    <?php if ($so_view_row["bol_shipped"] == 1) {
                                $rec_found = "n";
                                $eml_sendon = "";
                                $email_sendby = "";
                                db();
                                $getdata = db_query("Select email_sendon, email_sendby From loop_transaction_buyer_cnfrmshipeml where trans_rec_id = " . $_REQUEST["rec_id"] . " limit 1");
                                while ($getdata_row = array_shift($getdata)) {
                                    $rec_found = "y";
                                    $eml_sendon = $getdata_row["email_sendon"];
                                    $email_sendby = $getdata_row["email_sendby"];
                                }

                                if ($rec_found == "n") {
                            ?>
                    <form action="bol_confirmshipped_sendemail.php" name="frmbol_confirmshipped_sendemail"
                        id="frmbol_confirmshipped_sendemail" method="post">
                        <input type=hidden name="rec_id" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                        <input type="hidden" name="account_owner_email" id="account_owner_email"
                            value="<?php echo $account_owner_email; ?>">

                        <!-- <input type="text" name="shiptoname" id="shiptoname" value="<?php echo $shipto_name; ?>">
					<input type="text" name="shiptoeml" id="shiptoeml" value="<?php echo $shipto_email; ?>">-->
                        <input type="text" name="delivery_date" id="delivery_date"
                            value="<?php echo $po_delivery_dt; ?>">
                        <br>
                        <?php if ($freightupdates == 0) {
                                            echo "<font color=red>OPT OUT</font>";
                                        } ?>
                        <input type="button" value="Send Confirm Shipped Email" id="reminder_popup_set4_btn" onclick="parent.reminder_popup_set_confrm_ship(<?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $_REQUEST["warehouse_id"]; ?>,
                        <?php echo $_REQUEST["rec_type"]; ?>,
                        <?php echo $pickup_or_ucb_delivering; ?>)">
                    </form>
                    <?php
                                } else {
                                    echo "Email sent on: " . date("m/d/Y H:i:s", strtotime($eml_sendon)) . " CT" . " by: " . $email_sendby;
                                ?>
                    <br />
                    <form action="#" name="frmbol_confirmshipped_sendemail" id="frmbol_confirmshipped_sendemail"
                        method="post">
                        <input type=hidden name="rec_id" value="<?php echo  $_REQUEST["rec_id"]; ?>">
                        <input type="hidden" name="account_owner_email" id="account_owner_email"
                            value="<?php echo $account_owner_email; ?>">
                        <input type="hidden" name="delivery_date" id="delivery_date"
                            value="<?php echo $po_delivery_dt; ?>">
                        <br>
                        <?php if ($freightupdates == 0) {
                                            echo "<font color=red>OPT OUT</font>";
                                        } ?>
                        <input type="button" value="Resend Confirm Shipped Email" id="reminder_popup_set4_btn" onclick="parent.reminder_popup_set_confrm_ship(<?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $_REQUEST["warehouse_id"]; ?>,
                        <?php echo $_REQUEST["rec_type"]; ?>,
                        <?php echo $pickup_or_ucb_delivering; ?>)">
                    </form>
                    <?php
                                }
                            } ?>
                </td>

                <td align="center" height="13" class="style1">
                    <?php if ($so_view_row["bol_signed_file_name"] == "") {
                            ?>
                    <form name="uploadBOL" action="addsignedbol_mrg.php" method="post"
                        onSubmit="return check_form(uploadBOL);" encType="multipart/form-data">
                        <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST[" warehouse_id"]; ?>" />
                        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
                        <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />
                        <input type="hidden" name="bol_id" value="<?php echo $so_view_row[" id"]; ?>">
                        <input type=file name="file" style="width:150px;">
                        <input style="cursor:pointer;" value="Upload Signed BOL" type=submit>
                    </form>
                    <?php
                            } else {
                            ?>
                    <a style="color:#0000FF;" target="_blank"
                        href="signedbols/<?php echo  $so_view_row["bol_signed_file_name"]; ?>">View Signed BOL</a><br>
                    Confirmed By:
                    <?php echo  $so_view_row["bol_signed_employee"]; ?><br><?php echo  date("m/d/Y H:i:s", strtotime($so_view_row["bol_signed_date"])) . " CT"; ?><br>
                    <form name="uploadBOL" action="addsignedbol_mrg.php" method="post"
                        onSubmit="return check_form(uploadBOL);" encType="multipart/form-data">
                        <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST[" warehouse_id"]; ?>" />
                        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
                        <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />
                        <input type="hidden" name="bol_id" value="<?php echo $so_view_row[" id"]; ?>">
                        <input type=file name="file" style="width:150px;">
                        <input style="cursor:pointer;" value="Re-Upload Signed BOL" type=submit>
                    </form>

                    <?php
                            }
                            ?>
                </td>


                <td align="center" height="13" style="width: 131px" class="style1">
                    <a style="color:#0000FF;" onclick="return confirm('Are you sure you want to delete this bol?')"
                        href="delete_bol.php?trans_rec_id=<?php echo $_REQUEST[" rec_id"]; ?>">X</a>
                </td>
    </tr>


    <?php } ?>
</table>
<?php } ?>

<br><br>
<table cellSpacing="1" cellPadding="1" border="0" id="table5">
    <tr>
        <td bgColor="#c0cdda" align="center" colspan="2">
            <font size="1">SHIPPING DOCUMENTS</font>
        </td>
    </tr>
    <tr>
        <td bgColor="#e4e4e4">
            <font size=1>Purchase Order</font>
        </td>
        <td bgColor="#e4e4e4">
            <?php if (isset($po_file) != "") {

                    $archeive_date = new DateTime(date("Y-m-d", strtotime($po_archive_date)));
                    $po_date_new = new DateTime(date("Y-m-d", strtotime($po_date)));

                    if ($po_date_new < $archeive_date) {
                        echo "<a target='_blank' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/Loopsfilebackup/Shared%20Documents/po/" . isset($po_file) . "'>" . isset($po_file) . "</a>";
                    } else {
                ?>
            <a target="_blank" href='https://loops.usedcardboardboxes.com/po/<?php echo $po_file; ?>'>
                <font size=1>
                    <?php echo $po_file; ?>
                </font>
            </a>
            <?php
                    }
                    ?>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td bgColor="#e4e4e4">
            <font size=1>Unsigned BOL</font>
        </td>
        <td bgColor="#e4e4e4">
            <?php if (isset($unassign_bol_file) != "") { ?>
            <a target="_blank" href='https://loops.usedcardboardboxes.com/bol/<?php echo isset($unassign_bol_file); ?>'>
                <font size=1>
                    <?php echo isset($unassign_bol_file); ?>
                </font>
            </a>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td bgColor="#e4e4e4">
            <font size=1>Signed BOL</font>
        </td>
        <td bgColor="#e4e4e4">
            <?php if (isset($bol_signed_file_name) != "") { ?>
            <a target="_blank"
                href='https://loops.usedcardboardboxes.com/signedbols/<?php echo isset($bol_signed_file_name); ?>'>
                <font size=1>
                    <?php echo isset($bol_signed_file_name); ?>
                </font>
            </a>
            <?php } ?>
        </td>
    </tr>

</table>