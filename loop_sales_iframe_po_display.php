<script>
    function chkpofile() {
        var recid = document.getElementById('rec_id').value;
        var existingpo = "";

        if (document.getElementById('viewentrypodiv' + recid)) {
            existingpo = document.getElementById('viewentrypodiv' + recid).value;
        }

        if (existingpo == "") {
            if (document.getElementById('po_file').value == '') {
                alert("Please select the PO file.");
                return false;
            }
        }
        if (document.getElementById('txtpoorderamount').value == "" || document.getElementById('txtpoorderamount').value ==
            "0") {
            alert("Please enter amount");
            return false;
        }


        if (document.getElementById("po_delivery_dt").value == "") {
            alert('Please enter the "Planned Delivery Date"');
            return false;
        }

        if (document.getElementById("add_freight").value == "") {
            alert('Please select an option for "Add Freight line to invoice".');
            return false;
        }

        return true;

    }

    function f_getPosition(e_elemRef, s_coord) {
        var n_pos = 0,
            n_offset,
            e_elem = e_elemRef;

        while (e_elem) {
            n_offset = e_elem["offset" + s_coord];
            n_pos += n_offset;
            e_elem = e_elem.offsetParent;
        }

        e_elem = e_elemRef;
        while (e_elem != document.body) {
            n_offset = e_elem["scroll" + s_coord];
            if (n_offset && e_elem.style.overflow == 'scroll')
                n_pos -= n_offset;
            e_elem = e_elem.parentNode;
        }
        return n_pos;
    }

    function reminder_popup_set(compid, rec_id, warehouse_id, rec_type) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("btnposendemail");
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';
                document.getElementById('light_reminder').style.top = n_top - 40 + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "sendemail_add_po.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
            warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }

    function po_ignore(po_ignore_flg, compid, rec_id, warehouse_id, rec_type) {
        if (po_ignore_flg == 'posendemail_ignore') {
            document.getElementById("tbl_po_send_email").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        }
        if (po_ignore_flg == 'po_ignore') {
            document.getElementById("table_po_display").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        }
        if (po_ignore_flg == 'virtual_ignore') {
            document.getElementById("table_virtual_display").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        }
        if (po_ignore_flg == 'Shipper_eml_ignore') {
            document.getElementById("table_Shipper_eml_display").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (po_ignore_flg == 'posendemail_ignore') {
                    document.getElementById("tbl_po_send_email").innerHTML = xmlhttp.responseText;
                }
                if (po_ignore_flg == 'po_ignore') {
                    document.getElementById("table_po_display").innerHTML = xmlhttp.responseText;
                }
                if (po_ignore_flg == 'virtual_ignore') {
                    document.getElementById("table_virtual_display").innerHTML = xmlhttp.responseText;
                }
                if (po_ignore_flg == 'Shipper_eml_ignore') {
                    document.getElementById("table_Shipper_eml_display").innerHTML = xmlhttp.responseText;
                }

            }
        }

        if (po_ignore_flg == 'posendemail_ignore') {
            xmlhttp.open("POST", "po_order_bubble_actions.php?po_posendemail_ignore=1&ID=" + compid + "&rec_id=" + rec_id +
                "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        }
        if (po_ignore_flg == 'po_ignore') {
            xmlhttp.open("POST", "po_order_bubble_actions.php?po_ignore=1&ID=" + compid + "&rec_id=" + rec_id +
                "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        }
        if (po_ignore_flg == 'virtual_ignore') {
            xmlhttp.open("POST", "po_order_bubble_actions.php?virtual_ignore=1&ID=" + compid + "&rec_id=" + rec_id +
                "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        }
        if (po_ignore_flg == 'Shipper_eml_ignore') {
            xmlhttp.open("POST", "po_order_bubble_actions.php?Shipper_eml_ignore=1&ID=" + compid + "&rec_id=" + rec_id +
                "&warehouse_id=" + warehouse_id + "&rec_type=" + rec_type, true);
        }

        xmlhttp.send();
    }
</script>
<?php
require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

$po_poterm_data  = "";
$quoteDate = "";
$quote_file = "";
$files = "";
$termselval1 = "";
$paymentselval1 = "";
$paymentselval2 = "";
$termselval2 = "";
$termselval2b = "";
$paymentselval3 = "";
$termselval3 = "";
$termselval13 = "";
$termselval17 = "";
$termselval16 = "";
$termselval4 = "";
$termselval14 = "";
$paymentselval4 = "";
$termselval5 = "";
$termselval8 = "";
$termselval9 = "";
$termselval10 = "";
$termselval11 = "";
$termselval12 = "";
$termselval6 = "";
$termselval15 = "";
$termselval7 = "";
$leaderboard1 = "";
$leaderboard2 = "";
$leaderboard3 = "";
$leaderboard4 = "";
$leaderboard5 = "";
$po_preorder_chk = "";
$finalAmt = "";
$rec_type = "";
$po_delivery_dt = "";
$add_freight = "";
$po_freight = "";
?>
<style type="text/css">
    .black_overlay {
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: gray;
        z-index: 1001;
        -moz-opacity: 0.8;
        opacity: .80;
        filter: alpha(opacity=80);
    }

    .white_content {
        display: none;
        position: absolute;
        top: 5%;
        left: 10%;
        width: 60%;
        height: 90%;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
    }

    .white_content_details {
        display: none;
        position: absolute;
        top: 0%;
        left: 10%;
        width: 50%;
        height: auto;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
        box-shadow: 8px 8px 5px #888888;
    }


    .white_content_reminder {
        display: none;
        position: absolute;
        top: 10%;
        left: 10%;
        width: 70%;
        height: 85%;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
        box-shadow: 8px 8px 5px #888888;
    }
</style>

<LINK rel="stylesheet" type="text/css" href='one_style_mrg.css'>

<div id="fade" class="black_overlay"></div>
<div id="light_reminder" class="white_content_reminder"></div>

<div id="table_po_display_loading">
    Loading .....<img src='images/wait_animated.gif' />
</div>

<?php
if (isset($_REQUEST["btnConfirm"])) {
    if ($_REQUEST["rec_id"] != "") {
        $sql = "Update loop_transaction_buyer set customerpickup_ucbdelivering_flg = '" . $_REQUEST['pickup_or_ucb_delivering'] . "' where id = " . $_REQUEST["rec_id"];
        db();
        $result = db_query($sql);
?>

        <script>
            parent.document.getElementById("iframe_good_to_ship").contentWindow.location.reload(true);
        </script>
<?php
    }
}

$po_archive_date = "";
$query = "SELECT variablevalue FROM tblvariable where variablename = 'po_archive_date'";
db();
$dt_view_res3 = db_query($query);
while ($objQuote = array_shift($dt_view_res3)) {
    $po_archive_date = $objQuote["variablevalue"];
}

?>
<div id="table_po_display">
    <?php
    db_b2b();
    $freightupdates = 1;
    $qry_1 = "Select company,active,haveNeed, loopid, on_hold, freightupdates from companyInfo Where ID = " . $_REQUEST["ID"];
    $dt_view_1 = db_query($qry_1);
    while ($rows = array_shift($dt_view_1)) {
        $freightupdates = $rows["freightupdates"];
    }

    $parent_child_compid = "";
    $parent_comp_loopid = 0;
    $credit_application_net_term = "";
    $dt_view_qry = "SELECT on_hold, parent_child, parent_comp_id from companyInfo WHERE ID=" . $_REQUEST["ID"];
    $dt_view_res = db_query($dt_view_qry);
    while ($dt_view_row = array_shift($dt_view_res)) {
        $parent_child_compid = $dt_view_row["parent_comp_id"];
    }

    if ($parent_child_compid > 0) {
        $dt_view_qry = "SELECT on_hold, loopid from companyInfo WHERE ID = " . $parent_child_compid;
        $dt_view_res = db_query($dt_view_qry);
        $parent_comp_loopid = $_REQUEST["warehouse_id"];
        while ($dt_view_row = array_shift($dt_view_res)) {
            //	$on_hold = $dt_view_row["on_hold"];
            if ($dt_view_row["loopid"] > 0) {
                $parent_comp_loopid = $dt_view_row["loopid"];
            }
        }
    } else {
        $parent_comp_loopid = $_REQUEST["warehouse_id"];
    }

    $quotes_archive_date = "";
    $query = "SELECT variablevalue FROM tblvariable where variablename = 'quotes_archive_date'";
    db();
    $dt_view_res3 = db_query($query);
    while ($objQuote = array_shift($dt_view_res3)) {
        $quotes_archive_date = $objQuote["variablevalue"];
    }

    db();

    $login_id = $_COOKIE["employeeid"];
    $ew = "SELECT id, b2b_id, level, initials FROM `loop_employees` WHERE id= " . $login_id;
    db();
    $ew_res1 = db_query($ew);
    $ew_row = array_shift($ew_res1);
    $login_initial    = $ew_row["initials"];
    $login_level    = $ew_row["level"];



    $dt_view_qry = "SELECT credit_application_net_term from loop_warehouse WHERE id=" . $parent_comp_loopid . " AND credit_application_file != ''";
    $dt_view_res = db_query($dt_view_qry);
    while ($dt_view_row = array_shift($dt_view_res)) {
        $credit_application_net_term = $dt_view_row["credit_application_net_term"];
    }

    if ($credit_application_net_term == "") {
        $get_warehouse = db_query("Select credit_application_net_term from loop_warehouse where id = " . $_REQUEST["warehouse_id"]);
        while ($warehouse_data = array_shift($get_warehouse)) {
            $credit_application_net_term = $warehouse_data["credit_application_net_term"];
        }
    }

    $ponumber = "";
    $poorderamount = 0;
    $quote_number = 0;
    $po_employee_data = $_COOKIE['userinitials'];
    $notes_for_ops_team = "";
    $dt_view_qry = "SELECT * from loop_transaction_buyer WHERE id = '" . $_REQUEST["rec_id"] . "' AND po_file != ''";
    //echo $dt_view_qry;
    $dt_view_res = db_query($dt_view_qry);

    $po_edit = "";
    $pickup_or_ucb_delivering = "";
    if (isset($_REQUEST["btnpoedit"])) {
        $po_edit = "yes";
    }

    $num_rows = tep_db_num_rows($dt_view_res);
    if (($num_rows < 1) || ($po_edit == "yes")) { ?>
        <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
        <form action="addpo_mrg.php" method="post" name="addpo" encType="multipart/form-data" onsubmit="return chkpofile();">
            <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST[" warehouse_id"]; ?>" />
            <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>" />
            <input type="hidden" name="rec_type" value="<?php echo (isset($rec_type) ? $rec_type : ''); ?>" />
            <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
            <input type="hidden" name="rec_id" id="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />
            <input type="hidden" name="po_edit_flg" value="" />

            <?php if ($po_edit == "yes") {
                while ($dt_view_row = array_shift($dt_view_res)) { ?>
                    <input type="hidden" name="po_old" value="<?php echo $dt_view_row[" po_file"]; ?>" />
                    <input type=hidden name="po_employee_old" value="<?php echo $dt_view_row[" po_employee"]; ?>">
                    <input type="hidden" name="po_date_old" value="<?php echo $dt_view_row[" po_date"]; ?>" />
                    <input type="hidden" name="updatecrm" value="yes" />
                    <input type="hidden" name="po_edit_flg" value="yes" />
                    <!-- Added by Mooneem team on Mar-30-12 to input Po # -->
            <?php
                    $ops_delivery_dt = $dt_view_row["ops_delivery_date"];
                    $company_name = $dt_view_row["company_name"];
                    $ponumber = $dt_view_row["po_ponumber"];
                    $po_freight = $dt_view_row["po_freight"];
                    $add_freight = $dt_view_row["add_freight"];
                    $po_preorder_chk = $dt_view_row["is_preorder"];
                    $po_poterm_data = $dt_view_row["po_poterm"];
                    $pickup_or_ucb_delivering = $dt_view_row["customerpickup_ucbdelivering_flg"];

                    $termselval1 = "";
                    $termselval2 = "";
                    $termselval3 = "";
                    $termselval4 = "";
                    $termselval5 = "";
                    $termselval6 = "";
                    $termselval7 = "";
                    $termselval8 = "";
                    $termselval9 = "";
                    $termselval10 = "";
                    $termselval11 = "";
                    $termselval12 = "";
                    $termselval15 = "";
                    $termselval16 = "";
                    $termselval17 = "";
                    if ($po_poterm_data == 'PrePaid')
                        $termselval1 = " selected ";
                    if ($po_poterm_data == 'Due On Receipt')
                        $termselval2 = " selected ";
                    if ($po_poterm_data == 'Net 10' || $po_poterm_data == 'Net10')
                        $termselval2b = " selected ";
                    if ($po_poterm_data == 'Net 15'  || $po_poterm_data == 'Net15')
                        $termselval3 = " selected ";
                    if ($po_poterm_data == 'Net 20'  || $po_poterm_data == 'Net20')
                        $termselval16 = " selected ";
                    if ($po_poterm_data == 'Net 25'  || $po_poterm_data == 'Net25')
                        $termselval17 = " selected ";
                    if ($po_poterm_data == 'Net 30'  || $po_poterm_data == 'Net30')
                        $termselval4 = " selected ";
                    if ($po_poterm_data == 'Net 45'  || $po_poterm_data == 'Net45')
                        $termselval5 = " selected ";
                    if ($po_poterm_data == '1% 10 Net 30')
                        $termselval6 = " selected ";
                    if ($po_poterm_data == '1% 15 Net 30')
                        $termselval15 = " selected ";
                    if ($po_poterm_data == 'Other-See Notes')
                        $termselval7 = " selected ";
                    if ($po_poterm_data == 'Net 60'  || $po_poterm_data == 'Net60')
                        $termselval8 = " selected ";
                    if ($po_poterm_data == 'Net 75'  || $po_poterm_data == 'Net75')
                        $termselval9 = " selected ";
                    if ($po_poterm_data == 'Net 90'  || $po_poterm_data == 'Net90')
                        $termselval10 = " selected ";
                    if ($po_poterm_data == 'Net 120'  || $po_poterm_data == 'Net120')
                        $termselval11 = " selected ";
                    if ($po_poterm_data == 'Net 120 EOM +1'  || $po_poterm_data == 'Net120EOM+1')
                        $termselval12 = " selected ";

                    $termselval13 = "";
                    $termselval14 = "";
                    if ($po_poterm_data == 'Net 30 EOM +1'  || $po_poterm_data == 'Net30EOM+1')
                        $termselval13 = " selected ";
                    if ($po_poterm_data == 'Net 45 EOM +1'  || $po_poterm_data == 'Net45EOM+1')
                        $termselval14 = " selected ";



                    if ($dt_view_row["po_payment_method"] == 'Check')
                        $paymentselval1 = " selected ";
                    if ($dt_view_row["po_payment_method"] == 'Credit Card')
                        $paymentselval2 = " selected ";
                    if ($dt_view_row["po_payment_method"] == 'EFT')
                        $paymentselval3 = " selected ";
                    if ($dt_view_row["po_payment_method"] == 'Other-See Notes')
                        $paymentselval4 = " selected ";

                    $poorderamount = $dt_view_row["po_poorderamount"];
                    $po_source = $dt_view_row["po_source"];

                    if ($dt_view_row["Leaderboard"] == 'B2B')
                        $leaderboard1 = " selected ";
                    if ($dt_view_row["Leaderboard"] == 'B2C')
                        $leaderboard2 = " selected ";
                    if ($dt_view_row["Leaderboard"] == 'UCBZW')
                        $leaderboard3 = " selected ";
                    if ($dt_view_row["Leaderboard"] == 'GMI')
                        $leaderboard4 = " selected ";
                    if ($dt_view_row["Leaderboard"] == 'PALLETS')
                        $leaderboard5 = " selected ";


                    $po_cc_number = "";
                    $po_cc_owner = "";
                    $po_cc_expiration = "";
                    $po_cc_cvv = "";


                    if ($dt_view_row["po_delivery_dt"] <> "") {
                        $po_delivery_dt = date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
                    } else {
                        $po_delivery_dt = "";
                    }
                    $po_delivery = $dt_view_row["po_delivery"];

                    $files = $dt_view_row["po_file"];
                    $divisionval1 = "";
                    $divisionval2 = "";
                    $divisionval3 = "";
                    $divisionval4 = "";
                    $divisionval5 = "";
                    $divisionval6 = "";
                    $divisionval7 = "";
                    if ($dt_view_row["po_division"] == 'Loop')
                        $divisionval1 = " selected ";
                    if ($dt_view_row["po_division"] == 'B2B')
                        $divisionval2 = " selected ";
                    if ($dt_view_row["po_division"] == 'B2C')
                        $divisionval3 = " selected ";
                    if ($dt_view_row["po_division"] == 'Hunt Valley')
                        $divisionval5 = " selected ";
                    if ($dt_view_row["po_division"] == 'Hannibal')
                        $divisionval6 = " selected ";
                    if ($dt_view_row["po_division"] == 'Milwaukee')
                        $divisionval7 = " selected ";
                    if ($dt_view_row["po_division"] == 'Recycling')
                        $divisionval4 = " selected ";

                    if ($dt_view_row["quote_number"] != "") {
                        $quote_number = $dt_view_row["quote_number"];
                    }
                    $po_employee_data = $dt_view_row["po_employee"];
                    $notes_for_ops_team = $dt_view_row["notes_for_ops_team"];
                }
            } else {
                $po_poterm_data  = "";
            }

            if ($po_poterm_data == "") {
                $po_poterm_data = $credit_application_net_term;

                $termselval1 = "";
                $termselval2 = "";
                $termselval3 = "";
                $termselval4 = "";
                $termselval5 = "";
                $termselval6 = "";
                $termselval7 = "";
                $termselval8 = "";
                $termselval9 = "";
                $termselval10 = "";
                $termselval11 = "";
                $termselval12 = "";
                $termselval15 = "";
                $termselval16 = "";
                $termselval17 = "";
                if ($po_poterm_data == 'PrePaid')
                    $termselval1 = " selected ";
                if ($po_poterm_data == 'Due On Receipt')
                    $termselval2 = " selected ";
                if ($po_poterm_data == 'Net 10' || $po_poterm_data == 'Net10')
                    $termselval2b = " selected ";
                if ($po_poterm_data == 'Net 15'  || $po_poterm_data == 'Net15')
                    $termselval3 = " selected ";
                if ($po_poterm_data == 'Net 20'  || $po_poterm_data == 'Net20')
                    $termselval16 = " selected ";
                if ($po_poterm_data == 'Net 25'  || $po_poterm_data == 'Net25')
                    $termselval17 = " selected ";
                if ($po_poterm_data == 'Net 30'  || $po_poterm_data == 'Net30')
                    $termselval4 = " selected ";
                if ($po_poterm_data == 'Net 45'  || $po_poterm_data == 'Net45')
                    $termselval5 = " selected ";
                if ($po_poterm_data == '1% 10 Net 30')
                    $termselval6 = " selected ";
                if ($po_poterm_data == '1% 15 Net 30')
                    $termselval15 = " selected ";
                if ($po_poterm_data == 'Other-See Notes')
                    $termselval7 = " selected ";
                if ($po_poterm_data == 'Net 60'  || $po_poterm_data == 'Net60')
                    $termselval8 = " selected ";
                if ($po_poterm_data == 'Net 75'  || $po_poterm_data == 'Net75')
                    $termselval9 = " selected ";
                if ($po_poterm_data == 'Net 90'  || $po_poterm_data == 'Net90')
                    $termselval10 = " selected ";
                if ($po_poterm_data == 'Net 120'  || $po_poterm_data == 'Net90')
                    $termselval11 = " selected ";
                if ($po_poterm_data == 'Net 120 EOM +1'  || $po_poterm_data == 'Net90')
                    $termselval12 = " selected ";
                //*********************************************************************************************************************//
                //*********************************************************************************************************************//				
                $termselval13 = "";
                $termselval14 = "";
                if ($po_poterm_data == 'Net 30 EOM +1'  || $po_poterm_data == 'Net 30 EOM 1')
                    $termselval13 = " selected ";
                if ($po_poterm_data == 'Net 45 EOM +1'  || $po_poterm_data == 'Net 45 EOM 1')
                    $termselval14 = " selected ";
                //*********************************************************************************************************************//
                //*********************************************************************************************************************//				
            }

            ?>

            <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
            <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
            <script LANGUAGE="JavaScript">
                document.write(getCalendarStyles());
            </script>
            <script LANGUAGE="JavaScript">
                var cal2xx = new CalendarPopup("listdiv_new");
                cal2xx.showNavigationDropdowns();
            </script>

            <!-- Added by Mooneem team on Mar-30-12 to input Po # -->
            <table cellSpacing="1" cellPadding="1" border="0" id="table14" width="444px">
                <tr align="middle">
                    <td bgColor="#fb8a8a" colSpan="3">
                        <font size="1">UPLOAD PURCHASE ORDER</font>
                    </td>
                </tr>
                <?php
                db_b2b();
                $query = "SELECT filename, quoteDate FROM quote WHERE ID=" . $quote_number;
                $dt_view_res3 = db_query($query);
                while ($objQuote = array_shift($dt_view_res3)) {
                    $quote_file = $objQuote["filename"];
                    $quoteDate = $objQuote["quoteDate"];
                }
                db();
                ?>
                <?php if ($quote_number > 0) { ?>
                    <tr bgColor="#e4e4e4">
                        <td height="13" style="width: 86px" class="style1" align="left">Quote #</td>
                        <td height="13" class="style1" align="left" colspan="2"><input type="text" name="txtquotenumber" size="32" value="<?php echo $quote_number + 3770; ?>"> </td>
                    </tr>
                    <tr bgColor="#e4e4e4">
                        <td height="13" style="width: 86px" class="style1" align="left">
                            Unsigned Quote</td>
                        <td height="13" class="style1" align="left" colspan="2">
                            <?php
                            $archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
                            $quote_date = new DateTime(date("Y-m-d", strtotime($quoteDate)));

                            if ($quote_date < $archeive_date) {
                            ?>
                                <a target="_blank" id='viewqotediv<?php echo $_REQUEST["rec_id"]; ?>' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/<?php echo  $quote_file; ?>'>
                                    <font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>
                                        <?php echo $quote_file; ?>
                                    </font>
                                </a>
                            <?php
                            } else {
                            ?>
                                <a href='#' id="viewqotediv<?php echo $_REQUEST[" rec_id"]; ?>" onclick="parent.show_file_inviewer_pos_in_iframe('quotes/
                        <?php echo $quote_file; ?>', 'Unsigned Quote', 'viewqotediv
                        <?php echo $_REQUEST["rec_id"]; ?>', 'iframe_po_display'); return false;">
                                    <font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>
                                        <?php echo $quote_file; ?>
                                    </font>
                                </a>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 85px" class="style1" align="right">
                        <font color=red>*</font>Signed Quote/PO:
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <?php if ($files == 'No po' or $files == "") { ?>
                                <input type=file name="file" id="po_file" size="32">
                                <?php } else {

                                $archeive_date = new DateTime(date("Y-m-d", strtotime($po_archive_date)));
                                // $po_date = new DateTime(date("Y-m-d", strtotime($dt_view_row["po_date"])));
                                if (isset($dt_view_row["po_date"])) {
                                    $po_date = new DateTime(date("Y-m-d", strtotime($dt_view_row["po_date"])));
                                    // Further code using $po_date...
                                } else {
                                    $po_date = "";
                                }

                                if ($po_date < $archeive_date) {

                                    echo "<a target='_blank' id='viewentrypodiv" . $_REQUEST["rec_id"] . "' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/Loopsfilebackup/Shared%20Documents/po/" . $files . "'>" . $files . "</a>";
                                } else {
                                ?>
                                    <a href='#' id="viewentrypodiv<?php echo $_REQUEST[" rec_id"]; ?>" onclick="parent.show_file_inviewer_pos_in_iframe('po/
                            <?php echo $files; ?>', 'Signed Quote/PO', 'viewentrypodiv
                            <?php echo $_REQUEST["rec_id"]; ?>', 'iframe_po_display'); return false;">
                                        <font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>
                                            <?php echo $files; ?>
                                        </font>
                                    </a>
                                <?php
                                }
                                ?>
                                <input type=file name="file" id="po_file" size="32">
                            <?php } ?>
                        </font>
                    </td>
                </tr>
                <!-- Added by Mooneem team on Mar-30-12 to input Po # -->
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="right">
                        Purchase Order #: </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <input type="text" name="txtponumber" size="32" value="<?php echo  $ponumber ?>">
                        </font>
                    </td>
                </tr>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="right">
                        PO Term: </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <select name="cmbpoterms">
                                <option></option>
                                <option <?php echo  $termselval1 ?>>PrePaid</option>
                                <option <?php echo  $termselval2 ?>>Due On Receipt</option>
                                <option <?php echo  $termselval2b ?>>Net 10</option>
                                <option <?php echo  $termselval3 ?>>Net 15</option>
                                <option <?php echo  $termselval16 ?>>Net 20</option>
                                <option <?php echo  $termselval17 ?>>Net 25</option>
                                <option <?php echo  $termselval4 ?>>Net 30</option>
                                <option <?php echo  $termselval5 ?>>Net 45</option>
                                <option <?php echo  $termselval8 ?>>Net 60</option>
                                <option <?php echo  $termselval9 ?>>Net 75</option>
                                <option <?php echo  $termselval10 ?>>Net 90</option>
                                <option <?php echo  $termselval11 ?>>Net 120</option>
                                <option <?php echo  $termselval12 ?>>Net 120 EOM +1</option>
                                <option <?php echo  $termselval6 ?>>1% 10 Net 30</option>
                                <option <?php echo  $termselval15 ?>>1% 15 Net 30</option>
                                <option <?php echo  $termselval7 ?>>Other-See Notes</option>
                                <?php

                                ?>
                                <option <?php echo  $termselval13 ?>>Net 30 EOM +1</option>
                                <option <?php echo  $termselval14 ?>>Net 45 EOM +1</option>
                                <?php

                                ?>
                            </select>
                        </font>
                    </td>
                </tr>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="right">
                        Payment Tye: </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <select name="po_payment_method">
                                <option></option>
                                <option <?php echo  $paymentselval1 ?>>Check</option>
                                <option <?php echo  $paymentselval2 ?>>Credit Card</option>
                                <option <?php echo  $paymentselval3 ?>>EFT</option>
                                <option <?php echo  $paymentselval4 ?>>Other-See Notes</option>
                            </select>
                        </font>
                    </td>
                </tr>

                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="right">
                        <font color=red>*</font>PO Order Amount:
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <input type="text" name="txtpoorderamount" id="txtpoorderamount" size="32" onkeypress="return isNumberKey(event)" value="<?php echo  $poorderamount ?>">
                        </font>
                    </td>
                </tr>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="right">
                        Freight Cost:
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <input type="text" name="po_freight" size="32" onkeypress="return isNumberKey(event)" value="<?php echo  $po_freight ?>">
                        </font>
                    </td>
                </tr>
                <?php if ($po_edit != "yes") { ?>
                    <tr bgColor="#e4e4e4">
                        <td height="13" style="width: 120px;" class="style1" align="right">
                            <font color=red>*</font>Planned Delivery Date:
                        </td>
                        <td height="13" class="style1" align="left" colspan="2">
                            <Font size='1' Face="arial">
                                <input type="text" name="po_delivery_dt" id="po_delivery_dt" size="10" value="<?php echo  $po_delivery_dt; ?>">
                                <a href="#" onclick="cal2xx.select(document.addpo.po_delivery_dt,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx">
                                    <img border="0" src="images/calendar.jpg">
                                </a>
                                <div ID="listdiv_new" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                </div>
                            </font>
                        </td>
                    </tr>
                <?php } ?>

                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="right">
                        Leaderboard:
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <select name="sel_leaderboard" id="sel_leaderboard">
                            <option <?php echo  $leaderboard1 ?>>B2B</option>
                            <option <?php echo  $leaderboard2 ?>>B2C</option>
                            <option <?php echo  $leaderboard4 ?>>GMI</option>
                            <option <?php echo  $leaderboard3 ?>>UCBZW</option>
                            <option <?php echo  $leaderboard5 ?>>PALLETS</option>
                        </select>
                    </td>
                </tr>



                <?php if ($po_edit != "yes") { ?>
                    <tr bgColor="#e4e4e4">
                        <td height="13" style="width: 120px" class="style1">
                            <font size="1">Customer Pickup or UCB Delivering?</font>
                            </font>
                        </td>
                        <td align="left" height="13" class="style1" colspan="2">
                            <select name="pickup_or_ucb_delivering" id="pickup_or_ucb_delivering">
                                <option value="0">Please select</option>
                                <option value="1" <?php if ($pickup_or_ucb_delivering == "1") {
                                                        echo " selected ";
                                                    } ?>>Customer
                                    Pick-Up</option>
                                <option value="2" <?php if ($pickup_or_ucb_delivering == "2") {
                                                        echo " selected ";
                                                    } ?>>UCB
                                    Delivering</option>
                            </select>
                        </td>
                    </tr>
                <?php } ?>

                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="right">
                        Notes for Ops Team:
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <textarea id="notes_for_ops_team" name="notes_for_ops_team" cols="35" rows="4"><?php echo $notes_for_ops_team; ?></textarea>
                        </font>
                    </td>
                </tr>

                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="right">
                        Employee:
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <?php
                            $disable_employee_opt = " disabled";
                            if (($po_employee_data == $login_initial) || ($login_level == 2)) {
                                $disable_employee_opt = "";
                            }

                            echo '<select name="po_employee"' . $disable_employee_opt . '><option> </option>';

                            $emp_db = "SELECT initials FROM loop_employees where status = 'Active' order by initials";
                            $emp_result = db_query($emp_db);
                            while ($emp_data = array_shift($emp_result)) {
                                echo "<option value='" . $emp_data["initials"] . "'";
                                if ($emp_data["initials"] == $po_employee_data) {
                                    echo " selected ";
                                }
                                echo ">" . $emp_data["initials"] . "</option>";
                            }
                            ?>
                            </select>
                        </font>
                    </td>
                </tr>

                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="right">
                        <font color=red>*</font>Add Freight Line to Invoice?:
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <select name="add_freight" id="add_freight">
                            <option value="">Select One</option>
                            <option value="Y" <?php echo (($add_freight == 'Y') ? "selected" : ""); ?>>Yes</option>
                            <option value="N" <?php echo (($add_freight == 'N') ? "selected" : ""); ?>>No</option>
                        </select>


                    </td>
                </tr>

                <?php if ($po_edit != "yes") { ?>
                    <tr bgColor="#e4e4e4">
                        <td height="13" style="width: 120px;" class="style1" align="right">
                            Is this a pre-order?:
                        </td>
                        <td height="13" class="style1" align="left" colspan="2">
                            <Font size='1' Face="arial">
                                <input type="checkbox" name="po_preorder_chk" id="po_preorder_chk" value="Yes" <?php if ($po_preorder_chk != "" && $po_preorder_chk == "Yes") { ?> checked <?php } else {
                                                                                                                                                                                        }
                                                                                                                                                                                            ?>>

                            </font>
                        </td>
                    </tr>
                <?php } ?>

                <tr bgColor="#e4e4e4">
                    <td colspan="2" height="10" style="width: 217px" class="style1" align="center">
                        <input style="cursor:pointer;" type="submit" value="Upload">

                    </td>

                </tr>
            </table>

        </form>

    <?php } ?>


    <?php
    $planned_delivery_dt_customer_confirmed = 0;
    $dt_view_qry = "SELECT * from loop_transaction_buyer WHERE id = '" .  $_REQUEST["rec_id"] . "' AND po_file != ''";
    $result = db_query($dt_view_qry);
    $num_rows = tep_db_num_rows($result);
    if ($num_rows > 0) {

        $quote_file = "";
        $po_send_eml_ignore = 0;
        $po_send_eml_date_time = "";
        $po_display_tmp_flg = "y";
        $warehouse_id = 0;
        $rec_type = "";

        while ($dt_view_row = array_shift($result)) {
            $pickup_or_ucb_delivering = $dt_view_row["customerpickup_ucbdelivering_flg"];
            $planned_delivery_dt_customer_confirmed = $dt_view_row["planned_delivery_dt_customer_confirmed"];

            $warehouse_id = $dt_view_row["warehouse_id"];
            $rec_type = $dt_view_row["rec_type"];
            $leaderboard = $dt_view_row["Leaderboard"];


            if ($po_display_tmp_flg == "y") {
    ?>
                <table cellSpacing="1" cellPadding="1" border="0" width="444px">
                    <tr align="middle">
                        <td bgColor="#99FF99" colSpan="3">
                            <form action="loop_sales_iframe_po_display.php" method="post" encType="multipart/form-data" name="po_edit">
                                <input type="hidden" name="warehouse_id" value="<?php echo $dt_view_row[" warehouse_id"]; ?>" />
                                <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>" />
                                <input type="hidden" name="rec_type" value="<?php echo $dt_view_row[" rec_type"]; ?>" />
                                <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
                                <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />

                                <font size="1">UPLOAD PURCHASE ORDER</font>
                                <input type="submit" name="btnpoedit" id="btnpoedit" value="Edit" />
                            </form>
                        </td>
                    </tr>
                <?php
                $po_display_tmp_flg = "n";
            }

            if ($dt_view_row["quote_number"] != "") {
                $quote_number = $dt_view_row["quote_number"];
            } else {
                $quote_number = 0;
            }

            $po_send_eml_ignore = $dt_view_row["po_send_eml_ignore"];
            $po_send_eml_date_time = $dt_view_row["po_send_eml_date_time"];

            db_b2b();
            $quoteDate = "";
            $query = "SELECT filename, quoteDate FROM quote WHERE ID=" . $quote_number;
            $dt_view_res3 = db_query($query);
            while ($objQuote = array_shift($dt_view_res3)) {
                $quote_file = $objQuote["filename"];
                $quoteDate = $objQuote["quoteDate"];
            }
            db();

            if ($dt_view_row["ops_delivery_date"] <> "") {
                $ops_delivery_dt = date("m/d/Y", strtotime($dt_view_row["ops_delivery_date"]));
            } else {
                $ops_delivery_dt = "";
            }

            $payment_type = $dt_view_row["po_payment_method"];

            if ($dt_view_row["po_delivery_dt"] == "" || $dt_view_row["po_delivery_dt"] == "0000-00-00 00:00:00") {
                $po_delivery_dt = "";
            } else {
                $po_delivery_dt = date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
            }


            $po_delivery = $dt_view_row["po_delivery"];
                ?>
                <?php if ($quote_number > 0) { ?>
                    <tr bgColor="#e4e4e4">
                        <td height="13" style="width: 86px" class="style1" align="left">Quote #</td>
                        <td height="13" class="style1" align="left" colspan="2">
                            <?php echo $quote_number + 3770; ?>
                        </td>
                    </tr>
                    <tr bgColor="#e4e4e4">
                        <td height="13" style="width: 86px" class="style1" align="left">
                            Unsigned Quote</td>
                        <td height="13" class="style1" align="left" colspan="2">
                            <?php
                            $archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
                            $quote_date = new DateTime(date("Y-m-d", strtotime($quoteDate)));

                            if ($quote_date < $archeive_date) {
                            ?>
                                <a target="_blank" id='viewquotesdiv<?php echo $_REQUEST["rec_id"]; ?>' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/<?php echo  $quote_file; ?>'>
                                    <font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>
                                        <?php echo $quote_file; ?>
                                    </font>
                                </a>
                            <?php
                            } else {
                            ?>
                                <a href='#' id="viewquotesdiv<?php echo $_REQUEST[" rec_id"]; ?>" onclick="parent.show_file_inviewer_pos_in_iframe('quotes/
                    <?php echo $quote_file; ?>', 'Unsigned Quote', 'viewquotesdiv
                    <?php echo $_REQUEST["rec_id"]; ?>', 'iframe_po_display'); return false;">
                                    <font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>
                                        <?php echo $quote_file; ?>
                                    </font>
                                </a>
                            <?php
                            }
                            ?>

                        </td>
                    </tr>
                <?php } ?>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px" class="style1" align="left">
                        Signed Quote/PO
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <?php if ($dt_view_row["po_file"] == 'No po') { ?>
                            PO Ignored
                            <?php } elseif ($dt_view_row["online_order"] != '') {
                            if ($dt_view_row["po_file"] != "B2B online order") {

                                $archeive_date = new DateTime(date("Y-m-d", strtotime($po_archive_date)));
                                $po_date = new DateTime(date("Y-m-d", strtotime($dt_view_row["po_date"])));

                                if ($po_date < $archeive_date) {

                                    echo "<a target='_blank' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/Loopsfilebackup/Shared%20Documents/po/" . $dt_view_row["po_file"] . "'>" . $dt_view_row["po_file"] . "</a>";
                                } else {
                            ?>
                                    <a href='#' id="viewpodiv<?php echo $_REQUEST[" rec_id"]; ?>" onclick="parent.show_file_inviewer_pos_in_iframe('po/
                    <?php echo $dt_view_row["po_file"]; ?>', 'Signed Quote/PO', 'viewpodiv
                    <?php echo $_REQUEST["rec_id"]; ?>', 'iframe_po_display'); return false;">
                                        <font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>
                                            <?php echo $dt_view_row["po_file"]; ?>
                                        </font>
                                    </a><br>
                                <?php
                                }
                                ?>
                            <?php } ?>
                            <a target="_blank" href='b2becommerce_reports.php?order_id=<?php echo $dt_view_row["online_order"]; ?>' id="viewpodiv<?php echo $_REQUEST[" rec_id"]; ?>">
                                <font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>B2B online order</font>
                            </a>
                            <?php } else {

                            $archeive_date = new DateTime(date("Y-m-d", strtotime($po_archive_date)));
                            $po_date = new DateTime(date("Y-m-d", strtotime($dt_view_row["po_date"])));

                            if (($po_date < $archeive_date) && $dt_view_row["po_file_reupload"] == 0) {

                                echo "<a target='_blank' href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/Loopsfilebackup/Shared%20Documents/po/" . $dt_view_row["po_file"] . "'>" . $dt_view_row["po_file"] . "</a>";
                            } else {
                            ?>
                                <a href='#' id="viewpodiv<?php echo $_REQUEST[" rec_id"]; ?>" onclick="parent.show_file_inviewer_pos_in_iframe('po/
                    <?php echo $dt_view_row["po_file"]; ?>', 'Signed Quote/PO', 'viewpodiv
                    <?php echo $_REQUEST["rec_id"]; ?>', 'iframe_po_display'); return false;">
                                    <font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>
                                        <?php echo $dt_view_row["po_file"]; ?>
                                    </font>
                                </a>
                            <?php
                            }
                            ?>
                        <?php } ?>
                    </td>
                </tr>

                <!-- Added by Mooneem team on Mar-30-12 to input Po # -->
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px" class="style1" align="left">
                        PO # </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <?php echo $dt_view_row["po_ponumber"]; ?>
                    </td>
                </tr>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px" class="style1" align="left">
                        PO Term </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <?php echo $dt_view_row["po_poterm"]; ?>
                    </td>
                </tr>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px" class="style1" align="left">
                        Payment Type
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <?php echo $dt_view_row["po_payment_method"]; ?>
                    </td>
                </tr>

                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px" class="style1" align="left">PO Order Amount </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <?php if ($dt_view_row["po_payment_method"] == "Credit Card") {
                            $cc_fees = 0;
                            if ($dt_view_row["po_poorderamount"] > 0) {
                                $cc_fees = $dt_view_row["po_poorderamount"] * 0.03;
                                $finalAmt = $dt_view_row["po_poorderamount"] + $cc_fees;
                            }
                            echo "$" . number_format($dt_view_row["po_poorderamount"], 2);
                        ?>
                            <span style="font-size:9px;">&nbsp;&nbsp;($
                                <?php echo $finalAmt; ?> w/ 3% CC fee)
                            </span>
                        <?php
                        } else {
                            echo "$" . number_format($dt_view_row["po_poorderamount"], 2);
                        } ?>
                    </td>
                </tr>

                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px" class="style1" align="left"> Freight Cost</td>
                    <td height="13" class="style1" align="left" colspan="2">$
                        <?php echo number_format($dt_view_row["po_freight"], 2); ?>
                    </td>
                </tr>



                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px;" class="style1" align="left">
                        Leaderboard
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <?php echo $leaderboard; ?>
                    </td>
                </tr>



                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px" class="style1" align="left">
                        Date/Time Entered</td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <?php
                        if ($dt_view_row["po_date_time"] != "") {
                            echo date("m/d/Y H:i:s", strtotime($dt_view_row["po_date_time"])) . " CT";
                        } else {
                            echo $dt_view_row["po_date"];
                        }
                        ?>
                    </td>
                </tr>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px" class="style1" align="left">
                        Employee</td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <?php echo $dt_view_row["po_employee"]; ?>
                    </td>
                </tr>

                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="left">
                        Add Freight Line to Invoice?
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">

                            <?php if ($dt_view_row["add_freight"] == 'Y') {
                                echo 'Yes';
                            } else {
                                echo 'No';
                            }
                            ?>
                        </font>
                    </td>
                </tr>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 86px" class="style1" align="left">Original Planned Delivery Date</td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <?php
                        if ($dt_view_row["original_planned_delivery_dt"] == "" || $dt_view_row["original_planned_delivery_dt"] == "0000-00-00") {
                        } else {
                            echo date("m/d/Y", strtotime($dt_view_row["original_planned_delivery_dt"]));
                        }

                        ?>
                    </td>
                </tr>
                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="left">
                        Customer Pickup or UCB Delivering?
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <?php
                            if ($dt_view_row["customerpickup_ucbdelivering_flg"] == 1) {
                                echo "Customer Pick-Up";
                            } ?>
                            <?php if ($dt_view_row["customerpickup_ucbdelivering_flg"] == 2) {
                                echo "UCB Delivering";
                            } ?>
                        </font>
                    </td>
                </tr>


                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="left">
                        Is this a pre-order?
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <?php echo $dt_view_row["is_preorder"]; ?>

                        </font>
                    </td>
                </tr>

                <tr bgColor="#e4e4e4">
                    <td height="13" style="width: 120px;" class="style1" align="left">
                        Notes for Ops Team
                    </td>
                    <td height="13" class="style1" align="left" colspan="2">
                        <Font size='1' Face="arial">
                            <?php echo $dt_view_row["notes_for_ops_team"]; ?>
                        </font>
                    </td>
                </tr>

                </table>

                <?php if ($dt_view_row["po_payment_method"] == "Credit Card") { ?>

                <?php    }

                ?>
            <?php } ?>

            <br><br>

            <form action="loop_sales_iframe_po_display.php" method="post">
                <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST[" warehouse_id"]; ?>" />
                <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>" />
                <input type="hidden" name="rec_type" value="<?php echo $_REQUEST[" rec_type"]; ?>" />
                <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
                <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />
                <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px">
                    <tr align="middle">
                        <?php if ($pickup_or_ucb_delivering == "") { ?>
                            <td bgColor="#fb8a8a" colSpan="2">
                            <?php } else { ?>
                            <td bgColor="#99FF99" colSpan="2">
                            <?php }  ?>
                            <font size="1">Customer Pickup or UCB Delivering?</font>
                            </td>
                    </tr>

                    <tr bgColor="#e4e4e4">
                        <td height="13" style="width: 100px" class="style1">
                            <font size="1">Select One</font>
                            </font>
                        </td>
                        <td align="left" height="13" style="width: 235px" class="style1">
                            <select name="pickup_or_ucb_delivering" id="pickup_or_ucb_delivering">
                                <option value="0">Please select</option>
                                <option value="1" <?php if ($pickup_or_ucb_delivering == "1") {
                                                        echo " selected ";
                                                    } ?>>Customer
                                    Pick-Up</option>
                                <option value="2" <?php if ($pickup_or_ucb_delivering == "2") {
                                                        echo " selected ";
                                                    } ?>>UCB
                                    Delivering</option>
                            </select>
                        </td>
                    </tr>
                    <tr bgColor="#e4e4e4">
                        <td colspan=2 align=center>
                            <font size="1">
                                <input type="submit" value="Update" id="btnConfirm" name="btnConfirm">
                            </font>
                        </td>
                    </tr>
                </table>
            </form>


            <?php        //if ($_REQUEST["poadded"] == "yes")
            //{ 

            $rec_found = "n";
            $eml_sendon = "";
            $eml_sendby = "";
            $getdata = db_query("Select email_sendon, email_sendby From loop_transaction_buyer_poeml where trans_rec_id = " . $_REQUEST["rec_id"] . " and email_sendon <> '' limit 1");
            while ($getdata_row = array_shift($getdata)) {
                if ($getdata_row["email_sendon"] != "") {
                    $rec_found = "y";
                }
                $eml_sendon = $getdata_row["email_sendon"];
                $eml_sendby = $getdata_row["email_sendby"];
            }
            if ($rec_found == "n") { ?>
                <div id="tbl_po_send_email">
                    <br>
                    <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px">
                        <tr align="middle">
                            <?php if ($po_send_eml_ignore == 1) { ?>
                                <td bgColor="#99FF99">
                                <?php } else { ?>
                                <td bgColor="#fb8a8a">
                                <?php } ?>
                                <font size="1">
                                    <?php echo strtoupper("PO - Send Email"); ?>
                                </font>
                                </td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td align="center">
                                <font size="1">
                                    <?php if ($freightupdates == 0) {
                                        echo "<font color=red>OPT OUT</font>";
                                    } ?><input style="cursor:pointer;" id="btnposendemail" type="button" value="Send Email" onclick="parent.reminder_popup_set_new(<?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $warehouse_id; ?>,'
                        <?php echo $rec_type; ?>');">
                                    &nbsp;
                                    <?php
                                    if ($po_send_eml_ignore == 1) {
                                        echo "This Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s", strtotime($po_send_eml_date_time)) . " CT";
                                    } else {
                                    ?>
                                        <input style="cursor:pointer;" id="btnposendemail_ignore" type="button" value="Ignore this Step" onclick="po_ignore('posendemail_ignore', <?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $warehouse_id; ?>,'
                        <?php echo $rec_type; ?>');">
                                    <?php
                                    }
                                    ?>
                                </font>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php
            } else { ?>

                <div id="tbl_po_send_email">
                    <br>
                    <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px">
                        <tr align="middle">
                            <td bgColor="#99FF99">
                                <font size="1">
                                    <?php echo strtoupper("PO - Send Email"); ?>
                                </font>
                            </td>
                        </tr>
                        <tr bgColor="#e4e4e4">
                            <td>
                                <font size="1">
                                    <?php echo "Email sent on: " . date("m/d/Y H:i:s", strtotime($eml_sendon)) . " CT" . " by: " . $eml_sendby; ?>
                                </font> <br>
                                <font size="1">
                                    <?php if ($freightupdates == 0) {
                                        echo "<font color=red>OPT OUT</font>";
                                    } ?><input style="cursor:pointer;" id="btnposendemail" type="button" value="Re-Send Email" onclick="parent.reminder_popup_set_new(<?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $warehouse_id; ?>,'
                        <?php echo $rec_type; ?>');">
                                    &nbsp;
                                    <?php
                                    if ($po_send_eml_ignore == 1) {
                                        echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s", strtotime($po_send_eml_date_time)) . " CT";
                                    } else {
                                    ?>
                                        <input style="cursor:pointer;" id="btnposendemail_ignore" type="button" value="Ignore this Step" onclick="po_ignore('posendemail_ignore',<?php echo $_REQUEST[" ID"]; ?>,
                        <?php echo $_REQUEST["rec_id"]; ?>,
                        <?php echo $warehouse_id; ?>,'
                        <?php echo $rec_type; ?>');">
                                    <?php    }
                                    ?>
                                </font>
                            </td>
                        </tr>
                    </table>
                </div>
            <?php    } ?>


            <br>
            <!------ Planned Delivery History ----->
            <?php
            $h_qry = "select * from planned_delivery_date_history where trans_id=" . $_REQUEST["rec_id"] . " order by id desc";
            $h_res = db_query($h_qry);
            $h_num_rows = tep_db_num_rows($h_res);
            $h_row = array_shift($h_res);
            $date_log = date("m/d/Y H:i:s", strtotime($h_row["date_log"]));
            $po_confirm_flg = $h_row["planned_delivery_dt_customer_confirmed"];
            $user_log = $h_row["user_log"];

            if ($h_num_rows > 0) {
            ?>
                <br>
                <table id="cc_table" cellSpacing="1" cellPadding="1" border="0" style="width: 444px">
                    <tr bgColor="#e4e4e4">
                        <td colspan="4" align="center" bgcolor="#c0cdda">
                            <font size="1">Planned Delivery Date History</font>
                        </td>
                    </tr>
                    <tr bgColor="#e4e4e4">
                        <td height="13" style="width: 120px;" class="style1" align="left">Planned Delivery Date</td>
                        <td class="style1" align="left">Updated by</td>
                        <td class="style1" align="left">Updated on</td>
                        <td class="style1" align="left">Customer Confirmed</td>
                        <!--<td class="style1" align="left">Customer Confirmed by</td>
				<td class="style1" align="left">Customer Confirmed date</td>-->
                    </tr>
                    <?php
                    $h_qry = "select * from planned_delivery_date_history where trans_id=" . $_REQUEST["rec_id"] . " order by id desc";
                    $h_res = db_query($h_qry);
                    while ($h_row = array_shift($h_res)) {
                        $planned_delivery_dt = date("m/d/Y", strtotime($h_row["planned_delivery_dt"]));
                        $date_log = date("m/d/Y H:i:s", strtotime($h_row["date_log"]));

                        $planned_delivery_dt = date("m/d/Y", strtotime($h_row["planned_delivery_dt"]));


                        if ($h_row["planned_delivery_dt_customer_confirmed"] == 1) {
                            $customer_confirmed_flg = "Yes";
                        } else {
                            $customer_confirmed_flg = "No";
                        }
                    ?>
                        <tr bgColor="#e4e4e4">
                            <td class="style1" align="left">
                                <?php echo $planned_delivery_dt; ?>
                            </td>
                            <td class="style1" align="left">
                                <?php echo $h_row["user_log"]; ?>
                            </td>
                            <td class="style1" align="left">
                                <?php echo $date_log; ?>
                            </td>
                            <td class="style1" align="left">
                                <?php echo $customer_confirmed_flg; ?>
                            </td>
                            <!--<td class="style1" align="left"><?php echo $h_row["dt_customer_confirmed_by"]; ?></td>
					<td class="style1" align="left"><?php echo $customer_confirmed_flg; ?></td>-->
                        </tr>
                    <?php
                    }
                    ?>

                </table>
            <?php
            }
            ?>
            <br>
            <!------ Planned Delivery History ----->
        <?php
    } ?>

</div>

<script>
    document.getElementById("table_po_display_loading").style.display = "none";

    parent.delivery_financials_reload(<?php echo $_REQUEST["ID"]; ?>,
        <?php echo $_REQUEST["rec_id"]; ?>,
        <?php echo $_REQUEST["warehouse_id"]; ?>, '<?php echo $rec_type; ?>');
</script>