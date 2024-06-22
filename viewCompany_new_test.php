<?php
//ini_set("display_errors", "1");
//error_reporting(E_ALL);

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

require("tablefunctions_mrg_purchasing.php");

require("inc/functions_mysqli.php");

echo "<LINK rel='stylesheet' type='text/css' href='one_style_mrg.css' >";
//

//
if (isset($_REQUEST["warehouse_id"])) {
    $warehouse_id = $_REQUEST["warehouse_id"];
}
if (isset($_REQUEST["id"])) {
    $warehouse_id = $_REQUEST["id"];
}
if (isset($_REQUEST["ID"])) {
    $warehouse_id = $_REQUEST["ID"];
}
$rec_id = 0;
if (isset($_REQUEST["rec_id"])) {
    $rec_id = $_REQUEST["rec_id"];
}

$display_val = "";
if (isset($_REQUEST["display"])) {
    $display_val = $_REQUEST["display"];
}

if (isset($_REQUEST["response_transId"])) {
    if ($_REQUEST["response_transId"] != "") { ?>
<script>
alert("Payment is Successful.");
</script>
<?php

        // $trans = "";
        $trans_type = "";
        if ($_REQUEST["trans_type"] == "authCaptureTransaction") {
            $trans_type = "Authorize and Capture";
        }
        if ($_REQUEST["trans_type"] == "authOnlyTransaction") {
            $trans_type = "Authorize";
        }
        if ($_REQUEST["trans_type"] == "captureOnlyTransaction") {
            $trans_type = "Capture";
        }
        if ($_REQUEST["trans_type"] == "voidTransaction") {
            $trans_type = "Void";
        }

        $qry = "INSERT INTO loop_transaction_buyer_cc SET trans_rec_id = " . $_REQUEST["rec_id"] . ", transaction_type = '" . $trans_type . "' , transaction_id = '" . $_REQUEST["response_transId"] . "', employee_id = " . $_COOKIE["employeeid"] . ", amount = '"  . $_REQUEST["response_amt"] . "'";
        db();
        $res_newtrans = db_query($qry);

        if ($_REQUEST["trans_type"] == "captureOnlyTransaction" || $_REQUEST["trans_type"] == "authCaptureTransaction") {
            $qry = "Update loop_transaction_buyer SET trans_status = 4 where id = " . $_REQUEST["rec_id"] . "";
            db();
            $res_newtrans = db_query($qry);

            $cc_fees = amt_roundup($_REQUEST["response_amt"] * 0.03, 2);
            //$sql_ins = "INSERT INTO loop_transaction_buyer_payments (`transaction_buyer_id` ,`company_id` ,`typeid`  ,`fileid` ,`employee_id` ,`date` ,`estimated_cost` ,`status` ,`notes` ,`notes2` ) VALUES ( ";
            //$sql_ins .= " '". $_REQUEST['rec_id'] ."', '232', '8', '0', '" . $_COOKIE["employeeid"] . "', '". date("Y-m-d H:i:s") ."', '". $cc_fees ."', 6, '', '')";
            //$result = db_query($sql_ins);

            //added the code to update the invoice_paid flag
            $to_chk_amount = 0;
            $inv_qry = "SELECT * FROM loop_buyer_payments WHERE trans_rec_id = " . $_REQUEST['rec_id'];
            db();
            $inv_res = db_query($inv_qry);
            while ($inv_row = array_shift($inv_res)) {
                $to_chk_amount += $inv_row["amount"];
            }

            $to_chk_invoice_amt = 0;
            $inv_qry = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $_REQUEST['rec_id'] . " ORDER BY id ASC";
            db();
            $inv_res = db_query($inv_qry);
            while ($inv_row = array_shift($inv_res)) {
                $to_chk_invoice_amt += $inv_row["quantity"] * $inv_row["price"];
            }
            db();
            db_query("Update loop_transaction_buyer set invoice_paid = 0 where id = " . $_REQUEST['rec_id']);
            if ($to_chk_amount > 0 && $to_chk_invoice_amt > 0) {
                if ($to_chk_amount >= $to_chk_invoice_amt) {
                    db();
                    db_query("Update loop_transaction_buyer set invoice_paid = 1 where id = " . $_REQUEST['rec_id']);
                }
            }
        }

        $warehouse_id = 0;
        $sql1 = "Select loop_warehouse.id, loop_warehouse.company_name from loop_warehouse inner join loop_transaction_buyer on loop_transaction_buyer.warehouse_id = loop_warehouse.id where loop_transaction_buyer.id = " . $_REQUEST["rec_id"];
        db();
        $warehousedet = db_query($sql1);
        $warehousenm = "";
        while ($dt_view_row = array_shift($warehousedet)) {
            $warehouse_id = $dt_view_row["id"];
            $warehousenm = $dt_view_row["company_name"];
        }

        if ($_REQUEST["trans_type"] == "captureOnlyTransaction" || $_REQUEST["trans_type"] == "authCaptureTransaction") {
            $msg_trans = "System generated log - CC Captured on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'];
            db();
            db_query("Insert into loop_transaction_notes(company_id, rec_type, rec_id, message, employee_id) select '" . $warehouse_id . "', 'Supplier' , '" . $_REQUEST["rec_id"] . "', '" . $msg_trans . "', '" . $_COOKIE['employeeid'] . "'");
        }

        //$emlstatus = sendemail_attachment(null, "", "davidkrasnow@usedcardboardboxes.com,", "bk@mooneem.com,creditcard@usedcardboardboxes.com", "", "admin@usedcardboardboxes.com", "Admin UCB","", "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm , "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm . " Transaction type: " . $_REQUEST["trans_type"] . " <br/><br/> Loop <a href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=".$_REQUEST['ID']."&warehouse_id=$warehouse_id&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=$warehouse_id&rec_id=".$_REQUEST["rec_id"] ."&display=buyer_payment'>Link</a>"  );
        $emlstatus = sendemail_php_function(null, "", "davidkrasnow@usedcardboardboxes.com,", "bk@mooneem.com,creditcard@usedcardboardboxes.com", "", "admin@usedcardboardboxes.com", "Admin UCB", "", "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm, "Loop - CC information edited for id# " . $_REQUEST["rec_id"] . " Company: " . $warehousenm . " Transaction type: " . $_REQUEST["trans_type"] . " <br/><br/> Loop <a href='http://loops.usedcardboardboxes.com/viewCompany_new_test.php?ID=" . $_REQUEST['ID'] . "&warehouse_id=$warehouse_id&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id=$warehouse_id&rec_id=" . $_REQUEST["rec_id"] . "&display=buyer_payment'>Link</a>");
    }
}

$nickname_title = "";
$duplicate_chk = 0;
$qry_1 = "Select company, nickname, haveNeed from companyInfo Where ID = " . $_REQUEST["ID"];
db_b2b();
$dt_view_1 = db_query($qry_1);
while ($rows = array_shift($dt_view_1)) {
    if ($rows["nickname"] == "") {
        $nickname_title = $rows["company"];
    } else {
        $nickname_title = $rows["nickname"];
    }

    if ($rows["haveNeed"] == "Have Boxes") {
        $action_str =  "";
        if (isset($_REQUEST["action"])) {
            $action_str = "&action=" . $_REQUEST["action"];
        }

        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"viewCompany-purchasing.php?ID=" . $_REQUEST["ID"] . "&show=" . $_REQUEST["show"] . "&warehouse_id=" . $_REQUEST["warehouse_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&proc=" . $_REQUEST["proc"] . "&searchcrit=&id=" . $_REQUEST["warehouse_id"] . "&rec_id=" . $_REQUEST["rec_id"] . $action_str . "&display=" . $_REQUEST["display"] . "\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=viewCompany-purchasing.php?ID=" . $_REQUEST["ID"] . "&show= " . $_REQUEST["show"] . "&warehouse_id=" . $_REQUEST["warehouse_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&proc=" . $_REQUEST["proc"] . "&searchcrit=&id=" . $_REQUEST["warehouse_id"] . "&rec_id=" . $_REQUEST["rec_id"] . $action_str . "&display=" . $_REQUEST["display"] . "\" />";
        echo "</noscript>";
        exit;
    }
}

$qry_2 = "Select b2bid from loop_warehouse where b2bid = '" . $_REQUEST["ID"] . "'";
db();
$dt_view_2 = db_query($qry_2);
$duplicate_chk = tep_db_num_rows($dt_view_2);

//viewCompany-purchasing
?>

<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
    pre {
        /* height: 200px; */
        width: 380px;
        overflow: auto;
        font-size: 8pt;
        text-align: left;
        overflow-x: auto;
        /* Use horizontal scroller if needed; for Firefox 2, 
	notwhite-space: pre-wrap;	/* css-3 */
        white-space: -moz- pre-wrap !important;
        /* Mozilla, since 1999 */
        word-wrap: break-word;
        /* Internet Explorer 5.5+ */
        margin: 0px 0px 0px 0px;
        padding: 5px 5px 3px 5px;
        white-space: normal;
        /* crucial for IE 6, maybe 7? */
    }

    .main_data_css {
        margin: 0 auto;
        width: 100%;
        height: auto;
        clear: both !important;
        padding-top: 35px;
        margin-left: 10px;
        margin-right: 10px;
    }

    .input-color {
        width: 40px;
        height: 40px;
        display: inline-block;
        background-color: #ccc;
    }

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

    .white_content_gaylord_new {
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 1200px;
        height: 520px;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        -moz-box-shadow: 6px 6px 6px 6px #888888;
        -webkit-box-shadow: 6px 6px 6px 6px #888888;
        box-shadow: 6px 6px 6px 6px #888888;
        filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
    }

    .white_content_gaylord_new1 {
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 1200px;
        height: 520px;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        -moz-box-shadow: 6px 6px 6px 6px #888888;
        -webkit-box-shadow: 6px 6px 6px 6px #888888;
        box-shadow: 6px 6px 6px 6px #888888;
        filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
    }

    .white_content_gaylord {
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 600px;
        height: 520px;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        -moz-box-shadow: 6px 6px 6px 6px #888888;
        -webkit-box-shadow: 6px 6px 6px 6px #888888;
        box-shadow: 6px 6px 6px 6px #888888;
        filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
    }

    .white_content_quota {
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        -moz-box-shadow: 6px 6px 6px 6px #888888;
        -webkit-box-shadow: 6px 6px 6px 6px #888888;
        box-shadow: 6px 6px 6px 6px #888888;
        filter: progid:DXImageTransform.Microsoft.DropShadow(OffX=6, OffY=6, Color=#888888);
        width: 800px;
    }

    .txt_style12 {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        text-align: center;
    }

    .txt_style12_bold {
        font-size: 8pt;
        font-family: Arial, Helvetica, sans-serif;
        font-weight: bold;
        text-align: center;
    }

    .scrollit {
        overflow: auto;
        width: 1200px;
        height: 450px;
    }
    </style>

    <title>
        <?php echo $nickname_title; ?>
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" src="js/jquery.js"></script>
    <script language="JavaScript" src="gen_functions.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="js/calendar-win2k-1.css" title="win2k-1">
    <script language="JavaScript" type="text/javascript" src="js/calendar.js"></script>
    <script language="JavaScript" type="text/javascript" src="js/calendar-en.js"></script>
    <script type="text/javascript" src="js/calendar-setup.js"></script>

    <link rel="stylesheet" type="text/css" href="css/tooltip_style.css" />

    <link rel="stylesheet" type="text/css" href="tcal.css" />
    <script type="text/javascript" src="tcal.js"></script>

    <script type="text/javascript">
    function resizeIframe(iframe) {
        iframe.height = iframe.contentWindow.document.body.scrollHeight + 400 + "px";
    }

    function resizeIframe_crm(iframe) {
        iframe.height = iframe.contentWindow.document.body.scrollHeight + 400 + "px";
        iframe.width = iframe.contentWindow.document.body.scrollWidth + 100 + "px";
    }

    function resizeIframe1(iframe) {
        iframe.height = iframe.contentWindow.document.body.scrollHeight + 100 + "px";
    }
    </script>

    <style>
    div#expand {
        display: none;
    }

    #ex_co_div {
        display: inline-block;
        text-decoration: underline;
        color: rgb(0, 0, 255);
    }

    .container {
        width: 98%;
        background-color: #C0CDDA;
        padding: 5px 0px 5px 5px;
        text-decoration: none;
        text-align: center;
    }

    .container a {
        text-decoration: none;
        color: #333333;
        font-family: 'Arial', Helvetica, sans-serif;
        font-size: 12px;
    }

    .container .header {
        background-color: #d3d3d3;
        padding: 2px;
        cursor: pointer;
        font-weight: bold;
    }

    .container .content {
        display: none;
        padding: 5px;
    }

    div#expand_bill {
        display: none;
    }

    #ex_co_div_bill {
        display: inline-block;
        text-decoration: underline;
        color: rgb(0, 0, 255);
    }
    </style>
    <script>
    window.onload = function() {
        var url = new URL(window.location.href);
        var search_params = new URLSearchParams(url.search);
        // this will be true
        if (search_params.has('Edit')) {
            var id = search_params.get('Edit');
            // output : 100
            console.log(id);
            if (id == 3) {
                document.getElementById('expand').style.display = 'block';
                document.getElementById("ex_co_div").innerHTML = "Collapse";
                document.getElementById('expand1').style.display = 'none';
            }

            //
            if (id == 4) {
                document.getElementById('expand_bill').style.display = 'block';
                document.getElementById("ex_co_div_bill").innerHTML = "Collapse";
                document.getElementById('expand1_bill').style.display = 'none';
            }
        }
        if (search_params.has('nmind')) {
            var id = search_params.get('nmind');
            // output : 100
            console.log(id);
            if (id == "yes") {
                document.getElementById('expand').style.display = 'block';
                document.getElementById("ex_co_div").innerHTML = "Collapse";
                document.getElementById('expand1').style.display = 'none';
            }
        }
        if (search_params.has('pnmind')) {
            var id = search_params.get('pnmind');
            // output : 100
            console.log(id);
            if (id == "yes") {
                document.getElementById('expand_bill').style.display = 'block';
                document.getElementById("ex_co_div_bill").innerHTML = "Collapse";
                document.getElementById('expand1_bill').style.display = 'none';
            }
        }
        if (search_params.has('selltoinaddmode')) {
            var id = search_params.get('selltoinaddmode');
            // output : 100
            console.log(id);
            if (id == "yes") {
                document.getElementById('expand1').style.display = 'none';
                document.getElementById('expand').style.display = 'block';
                document.getElementById("ex_co_div").innerHTML = "Collapse";
            }
        }
        if (search_params.has('edited') || search_params.has('added')) {
            var id = search_params.get('edited');
            // output : 100
            console.log(id);
            if (id == "yes") {
                document.getElementById('expand1').style.display = 'none';
                document.getElementById('expand').style.display = 'block';
                document.getElementById("ex_co_div").innerHTML = "Collapse";
            }
            if (id == "pyes") {
                document.getElementById('expand1_bill').style.display = 'none';
                document.getElementById('expand_bill').style.display = 'block';
                document.getElementById("ex_co_div_bill").innerHTML = "Collapse";
            }
        }
        //code for Bill to

    };

    function selltotoggle() {
        if (document.getElementById('expand').style.display == 'block') {
            document.getElementById('expand1').style.display = 'block';
            document.getElementById('expand').style.display = 'none';
            document.getElementById("ex_co_div").innerHTML = "Expand";
        } else {
            document.getElementById('expand1').style.display = 'none';
            document.getElementById('expand').style.display = 'block';
            document.getElementById("ex_co_div").innerHTML = "Collapse";
        }

    }

    function Billtotoggle() {
        if (document.getElementById('expand_bill').style.display == 'block') {
            document.getElementById('expand1_bill').style.display = 'block';
            document.getElementById('expand_bill').style.display = 'none';
            document.getElementById("ex_co_div_bill").innerHTML = "Expand";
        } else {
            document.getElementById('expand1_bill').style.display = 'none';
            document.getElementById('expand_bill').style.display = 'block';
            document.getElementById("ex_co_div_bill").innerHTML = "Collapse";
        }

    }
    </script>

    <!--
<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">document.write(getCalendarStyles());</script>
-->
    <script language="javascript">
    //var calshipdt = new CalendarPopup("listdiv_new");
    //calshipdt.showNavigationDropdowns();

    //var calshipdt1 = new CalendarPopup("listdiv_new1");
    //calshipdt1.showNavigationDropdowns();

    function show_ops_dt() {
        document.getElementById('tbl_ops_delivery_dt_display').style.display = 'none';
        document.getElementById('tbl_ops_delivery_dt').style.display = 'block';
    }

    function update_ops_delivery_dt(trans_rec_id) {
        document.getElementById('tbl_ops_delivery_dt_display').style.display = 'block';
        document.getElementById('tbl_ops_delivery_dt').style.display = 'none';
        document.getElementById("tbl_ops_delivery_dt_display").innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_ops_delivery_dt_display").innerHTML = xmlhttp.responseText;
            }
        }
        var po_delivery_dt_tmp = document.getElementById('ops_delivery_dt').value;
        xmlhttp.open("GET", "ops_deliverydt_update.php?trans_rec_id=" + trans_rec_id + "&ops_delivery_dt=" +
            po_delivery_dt_tmp, true);
        xmlhttp.send();
    }

    function show_po_delivery_dt() {
        document.getElementById('tbl_po_delivery_dt_display').style.display = 'none';
        document.getElementById('tbl_po_delivery_dt').style.display = 'block';
    }

    function update_po_delivery_dt(trans_rec_id) {
        document.getElementById('tbl_po_delivery_dt_display').style.display = 'block';
        document.getElementById('tbl_po_delivery_dt').style.display = 'none';
        document.getElementById("tbl_po_delivery_dt_display").innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("tbl_po_delivery_dt_display").innerHTML = xmlhttp.responseText;
            }
        }
        var po_delivery_dt_tmp = document.getElementById('po_delivery_dt').value;

        xmlhttp.open("GET", "planned_deliverydt_update.php?trans_rec_id=" + trans_rec_id + "&planned_delivery_dt=" +
            po_delivery_dt_tmp, true);
        xmlhttp.send();
    }

    function display_sales_order_sel(tmpcnt, box_id) {
        if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') {
            document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'none';
        } else {
            document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'table-row';
        }

        document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
            "<br><br>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "sales_order_inv_orders_data.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt, true);
        xmlhttp.send();
    }


    function savetranslog(warehouse_id, transid, tmpcnt, box_id) {
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

        logdetail = document.getElementById("trans_notes" + transid + tmpcnt).value;
        opsdate = document.getElementById("ops_delivery_date" + transid + tmpcnt).value;

        xmlhttp.open("GET", "gaylordstatus_savetranslog.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt + "&warehouse_id=" +
            warehouse_id + "&transid=" + transid + "&logdetail=" + logdetail + "&opsdate=" + opsdate, true);
        xmlhttp.send();
    }

    function expandinvitems(selval) {
        if (selval == 1) {
            document.getElementById('div_inv_items').style.display = 'block';
        } else {
            document.getElementById('div_inv_items1').style.display = 'none';
        }
    }

    function expandinvitems1(d, wid, comid, rectype) {
        document.getElementById("div_inv_items1").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById("div_inv_items1").innerHTML = xmlhttp.responseText;
                document.getElementById('div_inv_items1').style.display = 'block';

            }
        }

        xmlhttp.open("POST", "search_result_show_include_box.php?id=" + wid + "&comid=" + comid + "&rectype=" + rectype,
            true);
        xmlhttp.send();
    }

    function collapse_CRM(selval) {
        if (selval == 1) {
            document.getElementById('div_CRM_child').style.display = 'block';
        } else {
            document.getElementById('div_CRM_child').style.display = 'none';
        }
    }

    function expand_CRM(d, wid, comid, rectype, show_trans) {
        document.getElementById("div_CRM_child").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById("div_CRM_child").innerHTML = xmlhttp.responseText;
                document.getElementById('div_CRM_child').style.display = 'block';

            }
        }

        xmlhttp.open("POST", "viewCompany-CRM.php?loopid=" + wid + "&b2bid=" + comid + "&rec_type=" + rectype +
            "&show_trans=" + show_trans, true);
        xmlhttp.send();
    }

    function timedCount() {
        //initv = strTrim(document.intNotes.green_initiative.value);
        intnotes = strTrim(document.intNotes.int_notes.value);
        companyID = strTrim(document.intNotes.companyID.value);
        if (intnotes == "") {} else {
            url = "updateIntNotesAjax.asp?int_notes=" + intnotes + "&companyID=" + companyID;
            $.get(url, function(data) {
                document.getElementById('msgNote').innerHTML = data;
            });
        }
    }

    function delete_box(id) {
        if (confirm('Are you sure to delete the Box?')) {
            self.location = 'viewCompany.asp?action=dbox&id=<?php echo $_REQUEST["ID"] ?>&bid=' + id;
        }
    }

    function DK() {
        document.intNotes.submit();
    }

    function displayemail(id) {
        document.getElementById("light").innerHTML = document.getElementById("emlmsg" + id).innerHTML;
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';
    }

    function editcompany() {
        document.getElementById('display_cmp').style.display = 'none';
        document.getElementById('display_cmp_edit').style.display = 'block';
    }

    function updateactiveflg(flg) {
        if (flg == 0) {
            if (confirm("Do you wish to mark the company as Inactive?") == true) {
                frmactiveflg.submit();
            }
        } else {
            if (confirm("Do you wish to mark the company as Active?") == true) {
                frmactiveflg.submit();
            }
        }
    }

    function updateonholdflg(flg) {
        if (flg == 0) {
            if (confirm("Do you want to update On hold flag?") == true) {
                document.comp_putonhold.submit();
            }
        } else {
            if (confirm("Do you want to remove On hold flag?") == true) {
                document.comp_putonhold.submit();
            }
        }
    }

    function updateacc_issue(flg) {
        if (flg == 0) {
            if (confirm("Do you want to mark 'Accounting Issue'?") == true) {
                document.comp_acc_issue.submit();
            }
        } else {
            if (confirm("Do you want to remove 'Accounting Issue' flag?") == true) {
                document.comp_acc_issue.submit();
            }
        }
    }

    function Add_quote_manual_fun() {
        var cnt = document.getElementById("add_quote_manual_cnt").value;
        cnt = parseInt(cnt);
        cnt = cnt + 1;
        var sstr = "<tr align='center' bgcolor='#E4E4E4'>";
        sstr = sstr + "<td >";
        sstr = sstr + "<input type='text' name='box_desc_" + cnt + "' id='box_desc_" + cnt + "' size='70' />";
        sstr = sstr + "</td>";
        sstr = sstr + "<td>";
        sstr = sstr + "<input type='text' name='box_salesp_" + cnt + "' id='box_salesp_" + cnt + "' size='10' />";
        sstr = sstr + "</td>";
        sstr = sstr + "<td>";
        sstr = sstr + "<input type='text' name='box_qty_" + cnt + "' id='box_qty_" + cnt + "' size='10' />";
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";

        document.getElementById("quote_manual").innerHTML = document.getElementById("quote_manual").innerHTML + " " +
            sstr;
        document.getElementById("add_quote_manual_cnt").value = cnt;
    }


    function toggleContent() {
        var contentId = document.getElementById("quote_content");
        var contenttxt = document.getElementById("btn_quote");

        if (contentId.style.display == "none") {
            contentId.style.display = "block";
            contenttxt.innerHTML = "Hide";
        } else {
            contentId.style.display = "none";
            contenttxt.innerHTML = "Show";
        }
    }

    function industry_chg() {
        var industry_txt = document.getElementById("industry_id").value;

        if (industry_txt == 13 || industry_txt == 19) {
            industry_txt_td.style.display = "block";
        } else {
            industry_txt_td.style.display = "none";
        }
    }

    function parent_ch_chg() {
        var parent_child_txt = document.getElementById("parent_child").value;

        if (parent_child_txt == "Child") {
            parent_child_td.style.display = "block";
        }
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

    function btnsendemlclick(tmpcnt) {
        var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

        tmp_element1 = document.getElementById("txtemailto").value;

        tmp_element2 = document.getElementById("email_reminder");

        tmp_element3 = document.getElementById("txtemailcc").value;

        tmp_element4 = document.getElementById("txtemailsubject").value;

        tmp_element5 = document.getElementById("hidden_reply_eml");

        if (tmp_element1.value == "") {
            alert("Please enter the To Email address.");
            return false;
        }

        if (tmp_element4.value == "") {
            alert("Please enter the Email Subject.");
            return false;
        }

        if (tmp_element3.value == "") {
            alert("Please enter the Cc Email address.");
            return false;
        }


        var inst = FCKeditorAPI.GetInstance("txtemailbody");
        var emailtext = inst.GetHTML();

        tmp_element5.value = emailtext;
        //alert(tmp_element5.value);
        document.getElementById("hidden_sendemail").value = "inemailmode";
        tmp_element2.submit();

    }

    function btnsendeml_sendrecordlinkbtn() {
        var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

        tmp_element1 = document.getElementById("txtemailto").value;

        tmp_element2 = document.getElementById("email_reminder");

        tmp_element3 = document.getElementById("txtemailcc").value;

        tmp_element4 = document.getElementById("txtemailsubject").value;

        tmp_element5 = document.getElementById("hidden_reply_eml");

        if (tmp_element1.value == "") {
            alert("Please enter the To Email address.");
            return false;
        }

        if (tmp_element4.value == "") {
            alert("Please enter the Email Subject.");
            return false;
        }

        if (tmp_element3.value == "") {
            alert("Please enter the Cc Email address.");
            return false;
        }


        var inst = FCKeditorAPI.GetInstance("txtemailbody");
        var emailtext = inst.GetHTML();

        tmp_element5.value = emailtext;
        //alert(tmp_element5.value);
        document.getElementById("hidden_sendemail").value = "inemailmode";
        tmp_element2.submit();

    }

    function updategaylordbox() {
        bid = document.getElementById("bid").value;
        //alert(bid);
        id = document.getElementById("id").value;

        shape_rect = "";
        if (document.getElementById("shape_rect").checked == true) {
            shape_rect = 'on';
        }
        shape_oct = "";
        if (document.getElementById("shape_oct").checked == true) {
            shape_oct = 'on';
        }
        wall_2 = "";
        if (document.getElementById("wall_2").checked == true) {
            wall_2 = 'on';
        }
        wall_3 = "";
        if (document.getElementById("wall_3").checked == true) {
            wall_3 = 'on';
        }
        wall_4 = "";
        if (document.getElementById("wall_4").checked == true) {
            wall_4 = 'on';
        }
        wall_5 = "";
        if (document.getElementById("wall_5").checked == true) {
            wall_5 = 'on';
        }
        wall_6 = "";
        if (document.getElementById("wall_6").checked == true) {
            wall_6 = 'on';
        }
        wall_7 = "";
        if (document.getElementById("wall_7").checked == true) {
            wall_7 = 'on';
        }
        wall_8 = "";
        if (document.getElementById("wall_8").checked == true) {
            wall_8 = 'on';
        }
        top_nolid = "";
        if (document.getElementById("top_nolid").checked == true) {
            top_nolid = 'on';
        }
        top_partial = "";
        if (document.getElementById("top_partial").checked == true) {
            top_partial = 'on';
        }
        top_full = "";
        if (document.getElementById("top_full").checked == true) {
            top_full = 'on';
        }
        top_hinged = "";
        if (document.getElementById("top_hinged").checked == true) {
            top_hinged = 'on';
        }
        top_remove = "";
        if (document.getElementById("top_remove").checked == true) {
            top_remove = 'on';
        }
        bottom_no = "";
        if (document.getElementById("bottom_no").checked == true) {
            bottom_no = 'on';
        }
        bottom_partial = "";
        if (document.getElementById("bottom_partial").checked == true) {
            bottom_partial = 'on';
        }
        bottom_partialsheet = "";
        if (document.getElementById("bottom_partialsheet").checked == true) {
            bottom_partialsheet = 'on';
        }
        bottom_fullflap = "";
        if (document.getElementById("bottom_fullflap").checked == true) {
            bottom_fullflap = 'on';
        }
        bottom_interlocking = "";
        if (document.getElementById("bottom_interlocking").checked == true) {
            bottom_interlocking = 'on';
        }
        bottom_tray = "";
        if (document.getElementById("bottom_tray").checked == true) {
            bottom_tray = 'on';
        }
        vents_no = "";
        if (document.getElementById("vents_no").checked == true) {
            vents_no = 'on';
        }

        vents_yes = "";
        if (document.getElementById("vents_yes").checked == true) {
            vents_yes = 'on';
        }
        box_pallet = "";
        if (document.getElementById("box_pallet").checked == true) {
            box_pallet = 'on';
        }
        if (document.getElementById("shape")) {
            shape = document.getElementById("shape").value;
        } else {
            shape = "";
        }
        if (document.getElementById("wall")) {
            wall = document.getElementById("wall").value;
        } else {
            wall = "";
        }
        if (document.getElementById("thetop")) {
            thetop = document.getElementById("thetop").value;
        } else {
            thetop = "";
        }
        if (document.getElementById("bottom")) {
            bottom = document.getElementById("bottom").value;
        } else {
            bottom = "";
        }
        if (document.getElementById("vents")) {
            vents = document.getElementById("vents").value;
        } else {
            vents = "";
        }
        if (document.getElementById("box_condition")) {
            box_condition = document.getElementById("box_condition").value;
        } else {
            box_condition = "";
        }
        if (document.getElementById("quantity")) {
            quantity = document.getElementById("quantity").value;
        } else {
            quantity = "";
        }
        if (document.getElementById("frequency")) {
            frequency = document.getElementById("frequency").value;
        } else {
            frequency = "";
        }
        if (document.getElementById("previous_contents")) {
            previous_contents = document.getElementById("previous_contents").value;
        } else {
            previous_contents = "";
        }
        if (document.getElementById("largest_qty")) {
            largest_qty = document.getElementById("largest_qty").value;
        } else {
            largest_qty = "";
        }
        if (document.getElementById("loading")) {
            loading = document.getElementById("loading").value;
        } else {
            loading = "";
        }
        if (document.getElementById("price_beat")) {
            price_beat = document.getElementById("price_beat").value;
        } else {
            price_beat = "";
        }
        if (document.getElementById("delivery_date")) {
            delivery_date = document.getElementById("delivery_date").value;
        } else {
            delivery_date = "";
        }
        height_range_min = "";
        height_range_max = "";
        if (document.getElementById("height_range_min")) {
            height_range_min = document.getElementById("height_range_min").value;
        } else {
            height_range_min = "";
        }
        if (document.getElementById("height_range_max")) {
            height_range_max = document.getElementById("height_range_max").value;
        } else {
            height_range_max = "";
        }

        //
        if (document.getElementById("glength")) {
            glength = document.getElementById("glength").value;
        } else {
            glength = "";
        }
        if (document.getElementById("gwidth")) {
            gwidth = document.getElementById("gwidth").value;
        } else {
            gwidth = "";
        }
        if (document.getElementById("gheight")) {
            gheight = document.getElementById("gheight").value;
        } else {
            gheight = "";
        }
        if (document.getElementById("gbox_size_id")) {
            gbox_size_id = document.getElementById("gbox_size_id").value;
        } else {
            gbox_size_id = "";
        }
        //alert(gbox_size_id);
        //

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("divGaylordBox").innerHTML = xmlhttp.responseText;
                document.getElementById("divGaylordBox").style.display = 'block';
                document.getElementById("divGaylordBoxEdit").style.display = 'none';
            }
        }

        xmlhttp.open("POST", "editTables_dynamic.php?editTable=boxesGaylord&bid=" + bid + "&id=" + id + "&shape=" +
            shape + "&wall=" + wall + "&thetop=" + thetop + "&bottom=" + bottom + "&vents=" + vents +
            "&box_condition=" + box_condition + "&quantity=" + quantity + "&frequency=" + frequency +
            "&previous_contents=" + previous_contents + "&largest_qty=" + largest_qty + "&loading=" + loading +
            "&price_beat=" + price_beat + "&delivery_date=" + delivery_date + "&shape_rect=" + shape_rect +
            "&shape_oct=" + shape_oct + "&wall_2=" + wall_2 + "&wall_3=" + wall_3 + "&wall_4=" + wall_4 +
            "&wall_5=" + wall_5 + "&wall_6=" + wall_6 + "&wall_7=" + wall_7 + "&wall_8=" + wall_8 + "&top_nolid=" +
            top_nolid + "&top_partial=" + top_partial + "&top_full=" + top_full + "&top_hinged=" + top_hinged +
            "&top_remove=" + top_remove + "&bottom_no=" + bottom_no + "&bottom_partial=" + bottom_partial +
            "&bottom_partialsheet=" + bottom_partialsheet + "&bottom_fullflap=" + bottom_fullflap +
            "&bottom_interlocking=" + bottom_interlocking + "&bottom_tray=" + bottom_tray + "&vents_no=" +
            vents_no + "&vents_yes=" + vents_yes + "&box_pallet=" + box_pallet + "&height_range_min=" +
            height_range_min + "&height_range_max=" + height_range_max + "&glength=" + glength + "&gwidth=" +
            gwidth + "&gheight=" + gheight + "&gbox_size_id=" + gbox_size_id, true);
        //xmlhttp.open("POST","editTables_dynamic.php?editTable=boxesGaylord&bid="+bid+"&id="+id+"&shape_rect="+shape_rect,true);			
        xmlhttp.send();
    }

    function loadmainpg() {
        selectboxes = document.getElementById("selectboxes");
        var value = selectboxes.options[selectboxes.selectedIndex].value;
        document.getElementById("txt").value = value;

        if (value == 'Gaylord Boxes') {
            document.getElementById("Gaylord").style.display = "block";
            document.getElementById("Shipping").style.display = "none";
        } else if (value == 'Shipping Boxes') {
            document.getElementById("Shipping").style.display = "block";
            document.getElementById("Gaylord").style.display = "none";
        } else if (value == 'Please Select') {
            document.getElementById("Shipping").style.display = "none";
            document.getElementById("Gaylord").style.display = "none";
        }
    }

    function updateBoxRescueAddnew() {
        compid = document.getElementById("editcompanyid").value;

        if (document.getElementById("selectboxes")) {
            selectboxes = document.getElementById("selectboxes").value;
        } else {
            selectboxes = "";
        }
        if (document.getElementById("shape")) {
            shape = document.getElementById("shape").value;
        } else {
            shape = "";
        }
        if (document.getElementById("top")) {
            top1 = document.getElementById("top").value;
        } else {
            top1 = "";
        }
        if (document.getElementById("bottom")) {
            bottom1 = document.getElementById("bottom").value;
        } else {
            bottom1 = "";
        }
        if (document.getElementById("vents")) {
            vents = document.getElementById("vents").value;
        } else {
            vents = "";
        }
        if (document.getElementById("wall")) {
            wall = document.getElementById("wall").value;
        } else {
            wall = "";
        }
        if (document.getElementById("previous_contents")) {
            previous_contents = document.getElementById("previous_contents").value;
        } else {
            previous_contents = "";
        }
        if (document.getElementById("box_condition")) {
            box_condition = document.getElementById("box_condition").value;
        } else {
            box_condition = "";
        }
        if (document.getElementById("frequency")) {
            frequency = document.getElementById("frequency").value;
        } else {
            frequency = "";
        }
        if (document.getElementById("no_of_rescue")) {
            no_of_rescue = document.getElementById("no_of_rescue").value;
        } else {
            no_of_rescue = "";
        }

        if (document.getElementById("length_lside")) {
            length_lside = document.getElementById("length_lside").value;
        } else {
            length_lside = "";
        }
        if (document.getElementById("width")) {
            width1 = document.getElementById("width").value;
        } else {
            width1 = "";
        }
        if (document.getElementById("height")) {
            height1 = document.getElementById("height").value;
        } else {
            height1 = "";
        }
        if (document.getElementById("wall_sh")) {
            wall_sh = document.getElementById("wall_sh").value;
        } else {
            wall_sh = "";
        }
        if (document.getElementById("box_condition_sh")) {
            box_condition_sh = document.getElementById("box_condition_sh").value;
        } else {
            box_condition_sh = "";
        }
        if (document.getElementById("req_another_box")) {
            req_another_box = document.getElementById("req_another_box").value;
        } else {
            req_another_box = "";
        }
        if (document.getElementById("frequency_sh")) {
            frequency_sh = document.getElementById("frequency_sh").value;
        } else {
            frequency_sh = "";
        }
        if (document.getElementById("no_of_rescue_sh")) {
            no_of_rescue_sh = document.getElementById("no_of_rescue_sh").value;
        } else {
            no_of_rescue_sh = "";
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("divBoxRescue").innerHTML = xmlhttp.responseText;
                document.getElementById("divBoxRescue").style.display = 'block';
                document.getElementById("divBoxRescueNew").style.display = 'none';
            }
        }
        xmlhttp.open("POST", "editTables_dynamic.php?boxrescue_add=yes&companyid=" + compid + "&selectboxes=" +
            selectboxes + "&shape=" + shape + "&top1=" + top1 + "&bottom1=" + bottom1 + "&vents=" + vents +
            "&wall=" + wall + "&previous_contents=" + previous_contents + "&box_condition=" + box_condition +
            "&frequency=" + frequency + "&no_of_rescue=" + no_of_rescue + "&length_lside=" + length_lside +
            "&width1=" + width1 + "&height1=" + height1 + "&wall_sh=" + wall_sh + "&box_condition_sh=" +
            box_condition_sh + "&req_another_box=" + req_another_box + "&frequency_sh=" + frequency_sh +
            "&no_of_rescue_sh=" + no_of_rescue_sh, true);

        xmlhttp.send();
    }

    function updateBoxRescueEditnew(ctrlid, recid) {
        compid = document.getElementById("editcompanyid_new").value;

        if (document.getElementById("selectboxes" + ctrlid)) {
            selectboxes = document.getElementById("selectboxes" + ctrlid).value;
        } else {
            selectboxes = "";
        }
        if (document.getElementById("shape" + ctrlid)) {
            shape = document.getElementById("shape" + ctrlid).value;
        } else {
            shape = "";
        }
        if (document.getElementById("top" + ctrlid)) {
            top1 = document.getElementById("top" + ctrlid).value;
        } else {
            top1 = "";
        }
        if (document.getElementById("bottom" + ctrlid)) {
            bottom1 = document.getElementById("bottom" + ctrlid).value;
        } else {
            bottom1 = "";
        }
        if (document.getElementById("vents" + ctrlid)) {
            vents = document.getElementById("vents" + ctrlid).value;
        } else {
            vents = "";
        }
        if (document.getElementById("wall" + ctrlid)) {
            wall = document.getElementById("wall" + ctrlid).value;
        } else {
            wall = "";
        }
        if (document.getElementById("previous_contents" + ctrlid)) {
            previous_contents = document.getElementById("previous_contents" + ctrlid).value;
        } else {
            previous_contents = "";
        }
        if (document.getElementById("box_condition" + ctrlid)) {
            box_condition = document.getElementById("box_condition" + ctrlid).value;
        } else {
            box_condition = "";
        }
        if (document.getElementById("frequency" + ctrlid)) {
            frequency = document.getElementById("frequency" + ctrlid).value;
        } else {
            frequency = "";
        }
        if (document.getElementById("no_of_rescue" + ctrlid)) {
            no_of_rescue = document.getElementById("no_of_rescue" + ctrlid).value;
        } else {
            no_of_rescue = "";
        }

        if (document.getElementById("length_lside" + ctrlid)) {
            length_lside = document.getElementById("length_lside" + ctrlid).value;
        } else {
            length_lside = "";
        }
        if (document.getElementById("width" + ctrlid)) {
            width1 = document.getElementById("width" + ctrlid).value;
        } else {
            width1 = "";
        }
        if (document.getElementById("height" + ctrlid)) {
            height1 = document.getElementById("height" + ctrlid).value;
        } else {
            height1 = "";
        }
        if (document.getElementById("wall_sh" + ctrlid)) {
            wall_sh = document.getElementById("wall_sh" + ctrlid).value;
        } else {
            wall_sh = "";
        }
        if (document.getElementById("box_condition_sh" + ctrlid)) {
            box_condition_sh = document.getElementById("box_condition_sh" + ctrlid).value;
        } else {
            box_condition_sh = "";
        }
        if (document.getElementById("req_another_box" + ctrlid)) {
            req_another_box = document.getElementById("req_another_box" + ctrlid).value;
        } else {
            req_another_box = "";
        }
        if (document.getElementById("frequency_sh" + ctrlid)) {
            frequency_sh = document.getElementById("frequency_sh" + ctrlid).value;
        } else {
            frequency_sh = "";
        }
        if (document.getElementById("no_of_rescue_sh" + ctrlid)) {
            no_of_rescue_sh = document.getElementById("no_of_rescue_sh" + ctrlid).value;
        } else {
            no_of_rescue_sh = "";
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("divBoxRescue_child" + ctrlid).innerHTML = xmlhttp.responseText;
                document.getElementById("divBoxRescue_child" + ctrlid).style.display = 'block';
                document.getElementById("divBoxRescue_child_edit" + ctrlid).style.display = 'none';
            }
        }

        xmlhttp.open("POST", "editTables_dynamic.php?boxrescue_edit=yes&companyid=" + compid + "&recid=" + recid +
            "&ctrlid=" + ctrlid + "&selectboxes=" + selectboxes + "&shape=" + shape + "&top1=" + top1 +
            "&bottom1=" + bottom1 + "&vents=" + vents + "&wall=" + wall + "&previous_contents=" +
            previous_contents + "&box_condition=" + box_condition + "&frequency=" + frequency + "&no_of_rescue=" +
            no_of_rescue + "&length_lside=" + length_lside + "&width1=" + width1 + "&height1=" + height1 +
            "&wall_sh=" + wall_sh + "&box_condition_sh=" + box_condition_sh + "&req_another_box=" +
            req_another_box + "&frequency_sh=" + frequency_sh + "&no_of_rescue_sh=" + no_of_rescue_sh, true);
        xmlhttp.send();
    }
    //Ship edit
    function updateShipBoxRescueEditnew(ctrlid, recid) {
        //
        var s_length = "",
            s_width = "",
            s_height = "";
        var ship_length = document.getElementsByName('ship_length[]');
        var ship_width = document.getElementsByName('ship_width[]');
        var ship_height = document.getElementsByName('ship_height[]');
        for (var i = 0, iLen = ship_length.length; i < iLen; i++) {
            s_length += ship_length[i].value + "-";
        }
        for (var i = 0, iLen = ship_width.length; i < iLen; i++) {
            s_width += ship_width[i].value + "-";
        }
        for (var i = 0, iLen = ship_height.length; i < iLen; i++) {
            s_height += ship_height[i].value + "-";
        }

        //
        var compid = document.getElementById("comp_id").value;
        //alert(compid);
        var shipping_box_id = document.getElementById("shipping_box_id").value;

        var wall = document.getElementById("wall" + ctrlid).value;

        var box_condition = document.getElementById("box_condition" + ctrlid).value;
        var req_another_box = document.getElementById("req_another_box" + ctrlid).value;
        var frequency = document.getElementById("frequency" + ctrlid).value;
        var quantity = document.getElementById("quantity" + ctrlid).value;
        var s_box_size_id = document.getElementById("s_box_size_id").value;

        //frequency = document.getElementById("frequency"+ctrlid).value;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // alert(xmlhttp.responseText);
                //document.getElementById("divBoxShipRescue_child"+ctrlid).innerHTML = xmlhttp.responseText;

                document.getElementById("divBoxShipRescue_child" + ctrlid).style.display = 'block';
                document.getElementById("divBoxShipRescue_child_edit" + ctrlid).style.display = 'none';
                $("#divBoxShipRescue_child" + ctrlid).load(window.location.href + " #divBoxShipRescue_child" +
                    ctrlid);
            }
        }

        xmlhttp.open("POST", "editTables_dynamic_new.php?boxrescue_edit=yes&companyid=" + compid + "&shipping_box_id=" +
            shipping_box_id + "&ctrlid=" + ctrlid + "&wall=" + wall + "&box_condition=" + box_condition +
            "&req_another_box=" + req_another_box + "&frequency=" + frequency + "&quantity=" + quantity +
            "&s_box_size_id=" + s_box_size_id + "&s_length=" + s_length + "&s_width=" + s_width + "&s_height=" +
            s_height, true);
        xmlhttp.send();
    }
    //
    //Gaylord edit
    function updateGaylordBoxRescueEditnew(ctrlid, recid) {
        //
        var g_length = "",
            g_width = "",
            g_height = "";
        var gay_length = document.getElementsByName('gay_length[]');
        var gay_width = document.getElementsByName('gay_width[]');
        var gay_height = document.getElementsByName('gay_height[]');
        for (var i = 0, iLen = gay_length.length; i < iLen; i++) {
            g_length += gay_length[i].value + "-";
        }
        for (var i = 0, iLen = gay_width.length; i < iLen; i++) {
            g_width += gay_width[i].value + "-";
        }
        for (var i = 0, iLen = gay_height.length; i < iLen; i++) {
            g_height += gay_height[i].value + "-";
        }

        //

        var compid = document.getElementById("comp_id").value;
        var gaylord_box_id = document.getElementById("gaylord_box_id").value;
        var shape = document.getElementById("shape" + ctrlid).value;
        var top = document.getElementById("top" + ctrlid).value;
        var bottom = document.getElementById("bottom" + ctrlid).value;
        var vents = document.getElementById("vents" + ctrlid).value;
        var wall = document.getElementById("wall" + ctrlid).value;
        var previous_contents = document.getElementById("previous_contents" + ctrlid).value;

        var box_condition = document.getElementById("box_condition" + ctrlid).value;
        //alert(gaylord_box_id);
        //var req_another_box = document.getElementById("req_another_box"+ctrlid).value;

        var frequency = document.getElementById("frequency" + ctrlid).value;

        var no_of_rescue = document.getElementById("no_of_rescue" + ctrlid).value;
        var g_box_size_id = document.getElementById("g_box_size_id").value;

        //frequency = document.getElementById("frequency"+ctrlid).value;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // alert(xmlhttp.responseText);
                //document.getElementById("divBoxShipRescue_child"+ctrlid).innerHTML = xmlhttp.responseText;

                document.getElementById("divBoxGaylordRescue_child" + ctrlid).style.display = 'block';
                document.getElementById("divBoxGaylordRescue_child_edit" + ctrlid).style.display = 'none';
                $("#divBoxGaylordRescue_child" + ctrlid).load(window.location.href + " #divBoxGaylordRescue_child" +
                    ctrlid);
            }
        }

        xmlhttp.open("POST", "editTables_dynamic_new.php?boxrescuegaylord_edit=yes&companyid=" + compid +
            "&gaylord_box_id=" + gaylord_box_id + "&ctrlid=" + ctrlid + "&shape=" + shape + "&top=" + top +
            "&bottom=" + bottom + "&vents=" + vents + "&wall=" + wall + "&previous_contents=" + previous_contents +
            "&box_condition=" + box_condition + "&frequency=" + frequency + "&no_of_rescue=" + no_of_rescue +
            "&g_box_size_id=" + g_box_size_id + "&g_length=" + g_length + "&g_width=" + g_width + "&g_height=" +
            g_height, true);
        xmlhttp.send();
    }
    //
    //pallet edit
    function updatePalletBoxRescueEditnew(ctrlid, recid) {
        //
        var p_length = "",
            p_width = "",
            p_height = "";
        var pal_length = document.getElementsByName('pal_length[]');
        var pal_width = document.getElementsByName('pal_width[]');

        for (var i = 0, iLen = pal_length.length; i < iLen; i++) {
            p_length += pal_length[i].value + "-";
        }
        for (var i = 0, iLen = pal_width.length; i < iLen; i++) {
            p_width += pal_width[i].value + "-";
        }


        //
        var compid = document.getElementById("comp_id").value;

        var pallet_box_id = document.getElementById("pallet_box_id").value;

        var wall = document.getElementById("wall" + ctrlid).value;

        var box_condition = document.getElementById("box_condition" + ctrlid).value;
        var req_another_box = document.getElementById("req_another_box" + ctrlid).value;
        var frequency = document.getElementById("frequency" + ctrlid).value;
        var quantity = document.getElementById("quantity" + ctrlid).value;
        var p_box_size_id = document.getElementById("p_box_size_id").value;

        //frequency = document.getElementById("frequency"+ctrlid).value;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // alert(xmlhttp.responseText);
                //document.getElementById("divBoxShipRescue_child"+ctrlid).innerHTML = xmlhttp.responseText;

                document.getElementById("divBoxPalletRescue_child" + ctrlid).style.display = 'block';
                document.getElementById("divBoxPalletRescue_child_edit" + ctrlid).style.display = 'none';
                $("#divBoxPalletRescue_child" + ctrlid).load(window.location.href + " #divBoxPalletRescue_child" +
                    ctrlid);
            }
        }

        xmlhttp.open("POST", "editTables_dynamic_new.php?boxpalrescue_edit=yes&companyid=" + compid +
            "&pallet_box_id=" + pallet_box_id + "&ctrlid=" + ctrlid + "&wall=" + wall + "&box_condition=" +
            box_condition + "&req_another_box=" + req_another_box + "&frequency=" + frequency + "&quantity=" +
            quantity + "&p_box_size_id=" + p_box_size_id + "&p_length=" + p_length + "&p_width=" + p_width, true);
        xmlhttp.send();
    }
    //
    //supersack edit
    function updateSuperBoxRescueEditnew(ctrlid, recid) {
        //
        var sk_length = "",
            sk_width = "",
            sk_height = "";
        var sup_length = document.getElementsByName('sup_length[]');
        var sup_width = document.getElementsByName('sup_width[]');

        for (var i = 0, iLen = sup_length.length; i < iLen; i++) {
            sk_length += sup_length[i].value + "-";
        }
        for (var i = 0, iLen = sup_width.length; i < iLen; i++) {
            sk_width += sup_width[i].value + "-";
        }


        //
        var compid = document.getElementById("comp_id").value;

        var super_box_id = document.getElementById("super_box_id").value;

        var wall = document.getElementById("wall" + ctrlid).value;

        var box_condition = document.getElementById("box_condition" + ctrlid).value;
        var req_another_box = document.getElementById("req_another_box" + ctrlid).value;
        var frequency = document.getElementById("frequency" + ctrlid).value;
        var quantity = document.getElementById("quantity" + ctrlid).value;
        var sup_box_size_id = document.getElementById("sup_box_size_id").value;

        //frequency = document.getElementById("frequency"+ctrlid).value;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // alert(xmlhttp.responseText);
                //document.getElementById("divBoxShipRescue_child"+ctrlid).innerHTML = xmlhttp.responseText;

                document.getElementById("divBoxSuperRescue_child" + ctrlid).style.display = 'block';
                document.getElementById("divBoxSuperRescue_child_edit" + ctrlid).style.display = 'none';
                $("#divBoxSuperRescue_child" + ctrlid).load(window.location.href + " #divBoxSuperRescue_child" +
                    ctrlid);
            }
        }

        xmlhttp.open("POST", "editTables_dynamic_new.php?boxsuprescue_edit=yes&companyid=" + compid + "&super_box_id=" +
            super_box_id + "&ctrlid=" + ctrlid + "&wall=" + wall + "&box_condition=" + box_condition +
            "&req_another_box=" + req_another_box + "&frequency=" + frequency + "&quantity=" + quantity +
            "&sup_box_size_id=" + sup_box_size_id + "&sk_length=" + sk_length + "&sk_width=" + sk_width, true);
        xmlhttp.send();
    }
    //
    //
    function updateBoxReqAddnew() {
        compid = document.getElementById("editcompanyid").value;

        /*if (document.getElementById("length")){ 		length = document.getElementById("length").value; } else { length= "";}
        if (document.getElementById("length_max")){ 		length_max = document.getElementById("length_max").value; } else { length_max= "";}
        if (document.getElementById("width")){ 		width = document.getElementById("width").value; } else { width= "";}
        if (document.getElementById("width_max")){ 		width_max = document.getElementById("width_max").value; } else { width_max= "";}
        if (document.getElementById("height")){ 		height = document.getElementById("height").value; } else { height= "";}
        if (document.getElementById("height_max")){ 		height_max = document.getElementById("height_max").value; } else { height_max= "";}*/
        if (document.getElementById("wall")) {
            wall = document.getElementById("wall").value;
        } else {
            wall = "";
        }
        if (document.getElementById("quantityshipbox")) {
            quantity = document.getElementById("quantityshipbox").value;
        } else {
            quantity = "";
        }
        if (document.getElementById("frequency_boxreq")) {
            frequency = document.getElementById("frequency_boxreq").value;
        } else {
            frequency = "";
        }
        if (document.getElementById("delivery_date_boxreq")) {
            delivery_date = document.getElementById("delivery_date_boxreq").value;
        } else {
            delivery_date = "";
        }
        //
        if (document.getElementById("length")) {
            length = document.getElementById("length").value;
        } else {
            length = "";
        }
        if (document.getElementById("width")) {
            width = document.getElementById("width").value;
        } else {
            width = "";
        }
        if (document.getElementById("heigth")) {
            heigth = document.getElementById("heigth").value;
        } else {
            heigth = "";
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("divBoxReq").innerHTML = xmlhttp.responseText;
                document.getElementById("divBoxReq").style.display = 'block';
                document.getElementById("divBoxReqNew").style.display = 'none';
            }
        }

        xmlhttp.open("POST", "editTables_dynamic.php?boxreq_add=yes&companyid=" + compid + "&length=" + length +
            "&length_max=" + length_max + "&width=" + width + "&width_max=" + width_max + "&height=" + height +
            "&height_max=" + height_max + "&wall=" + wall + "&quantity=" + quantity + "&frequency=" + frequency +
            "&delivery_date=" + delivery_date + "&length=" + length + "&width=" + width + "&height=" + height, true);
        xmlhttp.send();
    }

    function shippingbox_addnew() {
        document.getElementById('length').value = '';
        //document.getElementById('length_max').value=''; 
        document.getElementById('width').value = '';
        //document.getElementById('width_max').value=''; 
        document.getElementById('height').value = '';
        //document.getElementById('height_max').value=''; 
        document.getElementById('wall').value = '';
        document.getElementById('quantityshipbox').value = '';
        document.getElementById('frequency_boxreq').value = '';
        document.getElementById('delivery_date_boxreq').value = '';
        document.getElementById('quantityshipbox').value = '';


        document.getElementById('divBoxReqNew').style.display = 'block';
        document.getElementById('divBoxReq').style.display = 'none';
    }


    function updateBoxReqEditnew(ctrlid, recid) {
        compid = document.getElementById("editcompanyid_new").value;

        var length = document.getElementById("length" + ctrlid).value;
        var width = document.getElementById("width" + ctrlid).value;
        var height = document.getElementById("height" + ctrlid).value;
        var box_size_id = document.getElementById("box_size_id" + ctrlid).value;
        /*if (document.getElementById("length"+ctrlid)){ 		length1 = document.getElementById("length"+ctrlid).value; } else { length1= "";}
        if (document.getElementById("length_max"+ctrlid)){ 		length_max = document.getElementById("length_max"+ctrlid).value; } else { length_max= "";}
        if (document.getElementById("width"+ctrlid)){ 		width1 = document.getElementById("width"+ctrlid).value; } else { width1= "";}
        if (document.getElementById("width_max"+ctrlid)){ 		width_max = document.getElementById("width_max"+ctrlid).value; } else { width_max= "";}
        if (document.getElementById("height"+ctrlid)){ 		height1 = document.getElementById("height"+ctrlid).value; } else { height1= "";}
        if (document.getElementById("height_max"+ctrlid)){ 		height_max = document.getElementById("height_max"+ctrlid).value; } else { height_max= "";}*/
        if (document.getElementById("wall" + ctrlid)) {
            wall = document.getElementById("wall" + ctrlid).value;
        } else {
            wall = "";
        }
        if (document.getElementById("quantity" + ctrlid)) {
            quantity = document.getElementById("quantity" + ctrlid).value;
        } else {
            quantity = "";
        }
        if (document.getElementById("frequency" + ctrlid)) {
            frequency = document.getElementById("frequency" + ctrlid).value;
        } else {
            frequency = "";
        }
        if (document.getElementById("delivery_date" + ctrlid)) {
            delivery_date = document.getElementById("delivery_date" + ctrlid).value;
        } else {
            delivery_date = "";
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("divBoxReq_child" + ctrlid).innerHTML = xmlhttp.responseText;
                document.getElementById("divBoxReq_child" + ctrlid).style.display = 'block';
                document.getElementById("divBoxReq_child_edit" + ctrlid).style.display = 'none';
            }
        }

        //xmlhttp.open("POST","editTables_dynamic.php?boxreq_edit=yes&companyid="+compid+"&recid="+recid+"&ctrlid="+ctrlid+"&length="+length1+"&length_max="+length_max+"&width="+width1+"&width_max="+width_max+"&height="+height1+"&height_max="+height_max+"&wall="+wall+"&quantity="+quantity+"&frequency="+frequency+"&delivery_date="+delivery_date,true);	
        xmlhttp.open("POST", "editTables_dynamic.php?boxreq_edit1=yes&companyid=" + compid + "&recid=" + recid +
            "&ctrlid=" + ctrlid + "&length=" + length + "&width=" + width + "&height=" + height + "&wall=" + wall +
            "&quantity=" + quantity + "&box_size_id=" + box_size_id + "&frequency=" + frequency +
            "&delivery_date=" + delivery_date, true);
        xmlhttp.send();
    }
    //pallet request
    function updateBoxReqEditPallet(ctrlid, recid) {
        var compid = document.getElementById("editcompanyid_new").value;

        var length = document.getElementById("plength" + ctrlid).value;
        var width = document.getElementById("pwidth" + ctrlid).value;

        var pallet_boxid = document.getElementById("pallet_boxid" + ctrlid).value;
        var pbox_size_id = document.getElementById("pbox_size_id" + ctrlid).value;

        if (document.getElementById("quantity" + ctrlid)) {
            quantity = document.getElementById("quantity" + ctrlid).value;
        } else {
            quantity = "";
        }


        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // alert(xmlhttp.responseText);
                //document.getElementById("divBoxReqP_child"+ctrlid).innerHTML = xmlhttp.responseText;
                document.getElementById("divBoxReqP_child" + ctrlid).style.display = 'block';
                document.getElementById("divBoxReqP_child_edit" + ctrlid).style.display = 'none';
                $("#divBoxReqP_child" + ctrlid).load(window.location.href + " #divBoxReqP_child" + ctrlid);
            }
        }

        xmlhttp.open("POST", "editTables_dynamic_new.php?pboxreq_edit1=yes&companyid=" + compid + "&recid=" + recid +
            "&ctrlid=" + ctrlid + "&length=" + length + "&width=" + width + "&quantity=" + quantity +
            "&pbox_size_id=" + pbox_size_id + "&pallet_boxid=" + pallet_boxid, true);
        xmlhttp.send();
    }
    //
    //supersack request
    function updateBoxReqEditSupersk(ctrlid, recid) {
        var compid = document.getElementById("editcompanyid_new").value;

        var length = document.getElementById("slength" + ctrlid).value;
        var width = document.getElementById("swidth" + ctrlid).value;

        var sup_boxid = document.getElementById("sup_boxid" + ctrlid).value;
        var sbox_size_id;

        if (document.getElementById("sbox_size_id" + ctrlid)) {
            sbox_size_id = document.getElementById("sbox_size_id" + ctrlid).value;
        } else {
            sbox_size_id = "";
        }

        if (document.getElementById("quantity" + ctrlid)) {
            quantity = document.getElementById("quantity" + ctrlid).value;
        } else {
            quantity = "";
        }
        //alert(ctrlid);

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // alert(xmlhttp.responseText);
                //document.getElementById("divBoxReqP_child"+ctrlid).innerHTML = xmlhttp.responseText;
                document.getElementById("divBoxReqSup_child" + ctrlid).style.display = 'block';
                document.getElementById("divBoxReqSup_child_edit" + ctrlid).style.display = 'none';
                $("#divBoxReqSup_child" + ctrlid).load(window.location.href + " #divBoxReqSup_child" + ctrlid);
            }
        }

        xmlhttp.open("POST", "editTables_dynamic_new.php?supboxreq_edit1=yes&companyid=" + compid + "&recid=" + recid +
            "&ctrlid=" + ctrlid + "&length=" + length + "&width=" + width + "&quantity=" + quantity +
            "&sbox_size_id=" + sbox_size_id + "&sup_boxid=" + sup_boxid, true);
        xmlhttp.send();
    }
    //

    function reminder_popup_set5(compid, rec_id, warehouse_id, rec_type) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("reminder_popup_set5_btn");
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';
                document.getElementById('light_reminder').style.top = n_top - 40 + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "sendemail_b2bsurvey.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
            warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }

    function show_reply(tmpcnt) {
        //alert(document.getElementById("reminder_details" + tmpcnt));
        var selectobject = document.getElementById("reminder_details" + tmpcnt);

        //selectobject.innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />"; 				

        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        document.getElementById("light_details").innerHTML = document.getElementById("remider_rep_popup" + tmpcnt)
            .innerHTML;
        document.getElementById('light_details').style.display = 'block';
        //document.getElementById('fade').style.display='block';

        document.getElementById('light_details').style.left = n_left + 'px';
        document.getElementById('light_details').style.top = n_top + 20 + 'px';
    }

    function reminder_popup(unq_quote_id, quote_id) {
        var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
        skillsSelect = document.getElementById("quote_status" + quote_id);
        selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
        selectobject = document.getElementById("B1" + "_" + quote_id);
        id = document.getElementById("companyID").value;
        rec_type = document.getElementById("rec_type").value;

        if (selectedText == 10) {

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');
                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';
                    //document.getElementById('fade').style.display='block';

                    document.getElementById('light_reminder').style.left = n_left + 'px';
                    document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                }
            }

            xmlhttp.open("POST", "fckeditor_reminder_email.php?unq_quote_id=" + unq_quote_id + "&quote_id=" + quote_id +
                "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type, true);
            xmlhttp.send();
        } else {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Data Updated");
                }
            }

            xmlhttp.open("POST", "updateQuoteStatus_mrg.php?quote_id=" + quote_id + "&quote_status=" + selectedText +
                "&B1=B1", true);
            xmlhttp.send();

        }
    }

    function reminder_popup_newtest(unq_quote_id, quote_id) {
        var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
        skillsSelect = document.getElementById("quote_status" + quote_id);
        selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;

        selectobject = document.getElementById("B1" + "_" + quote_id);
        id = document.getElementById("companyID").value;
        rec_type = document.getElementById("rec_type").value;

        if (selectedText == 10) {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');
                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';
                    //document.getElementById('fade').style.display='block';

                    document.getElementById('light_reminder').style.left = n_left + 'px';
                    document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                }
            }

            xmlhttp.open("POST", "fckeditor_reminder_email.php?unq_quote_id=" + unq_quote_id + "&quote_id=" + quote_id +
                "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type, true);
            xmlhttp.send();
        }
        if (selectedText == 8) {

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');
                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';
                    document.getElementById('light_reminder').style.width = '450';
                    document.getElementById('light_reminder').style.height = '800';
                    //document.getElementById('fade').style.display='block';

                    document.getElementById('light_reminder').style.left = n_left + 'px';
                    document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
                }
            }

            xmlhttp.open("POST", "createpo_from_quote.php?unq_quote_id=" + unq_quote_id + "&quote_id_org=" + quote_id +
                "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type, true);
            xmlhttp.send();
        } else {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Data Updated");
                }
            }

            xmlhttp.open("POST", "updateQuoteStatus_mrg.php?quote_id=" + quote_id + "&quote_status=" + selectedText +
                "&B1=B1", true);
            xmlhttp.send();

        }
    }

    function onsubmitform_quotepo() {
        var msgstr = "";

        var form, list, index, item, checkedCount;

        checkedCount = 0;
        quotesel = "no";
        form = document.getElementById('addpo_fromquote');
        list = form.getElementsByTagName('input');
        for (index = 0; index < list.length; ++index) {
            item = list[index];
            if (item.getAttribute('type') === "checkbox" &&
                item.checked &&
                item.name === "txtquotesel[]") {
                ++checkedCount;
            }
            if (item.getAttribute('type') === "checkbox" &&
                item.name === "txtquotesel[]") {
                quotesel = "yes";
            }
        }

        if (document.getElementById("file").value == "") {
            msgstr = msgstr + "Purchase Order\r\n";
        }
        if (document.getElementById("txtponumber").value == "") {
            msgstr = msgstr + "PO Number\r\n";
        }
        if (document.getElementById("cmbpoterms").value == "") {
            msgstr = msgstr + "PO Terms\r\n";
        }
        if (document.getElementById("txtpoorderamount").value == "") {
            msgstr = msgstr + "PO Amount\r\n";
        }
        if (checkedCount == 0 && quotesel == "yes") {
            msgstr = msgstr + "Please select any one quote item\r\n";
        }

        if (msgstr != "") {
            alert("Following required fields needs to be filled out:\r\n" + msgstr);
            return false;
        } else {
            return true;
        }
    }

    function showccfields() {
        if (document.getElementById('po_payment_method').value == "Credit Card") {
            document.getElementById('ccfield_1').style.display = 'block';
            document.getElementById('ccfield_2').style.display = 'block';
            document.getElementById('ccfield_3').style.display = 'block';
            document.getElementById('ccfield_4').style.display = 'block';
        } else {
            document.getElementById('ccfield_1').style.display = 'none';
            document.getElementById('ccfield_2').style.display = 'none';
            document.getElementById('ccfield_3').style.display = 'none';
            document.getElementById('ccfield_4').style.display = 'none';
        }
    }

    function sendemail_popup_sendrecordlink_publichandoff(compid) {
        var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
        selectobject = document.getElementById("sendemailtrfacc");
        employeed = document.getElementById("employeed_publich").value;
        n_left = f_getPosition(selectobject, 'Left');
        n_top = f_getPosition(selectobject, 'Top');

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left - 250 + 'px';
                document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
            }
        }

        xmlhttp.open("POST", "fckeditor_email_sendrecordlink_publichandoff.php?companyID=" + compid + "&employeed=" +
            employeed, true);
        xmlhttp.send();
    }

    function quote_request_send_email(quote_id, compid, quote_rq_id) {
        var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
        selectobject = document.getElementById("demand_entry_list");
        //selectobject = pop_loc;
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        //alert(quote_id+quote_rq_id);
        document.getElementById('light').style.left = 300 + 'px';
        document.getElementById('light').style.top = n_top + 30 + 'px';
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;

                document.getElementById('light').style.display = 'block';

            }
        }
        xmlhttp.open("POST", "quote_request_to_customer_email.php?company_id=" + compid + "&quote_item_id=" + quote_id +
            "&quote_rq_item=" + quote_rq_id, true);
        xmlhttp.send();
    }
    //deny email
    function quote_deny_send_email(quote_id, compid) {
        var selectedText, selectobject, skillsSelect, n_left, n_top;
        selectobject = document.getElementById("button_status" + quote_id);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        //alert(quote_id+quote_rq_id);
        document.getElementById('light').style.left = 300 + 'px';
        document.getElementById('light').style.top = n_top + 30 + 'px';
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;

                document.getElementById('light').style.display = 'block';
                document.getElementById('light').style.width = 730 + 'px';
                document.getElementById('light').style.height = 550 + 'px';
                document.getElementById('light').style.left = n_left + 10 + 'px';
                document.getElementById('light').style.top = n_top + 10 + 'px';


            }
        }
        xmlhttp.open("POST", "quote_deny_send_email.php?company_id=" + compid + "&quote_id=" + quote_id, true);
        xmlhttp.send();
    }
    //
    function quote_to_customer(unq_quote_id, quote_id) {
        var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
        skillsSelect = document.getElementById("quote_status" + quote_id);
        selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
        selectobject = document.getElementById("B1" + "_" + quote_id);
        id = document.getElementById("companyID").value;
        rec_type = document.getElementById("rec_type").value;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');
                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left + 'px';
                document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
            }
        }

        xmlhttp.open("POST", "fckeditor_quote_to_customer_email.php?unq_quote_id=" + unq_quote_id + "&quote_id=" +
            quote_id + "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type,
            true);
        xmlhttp.send();
    }

    function sendemail_popup_sendrecordlink(compid) {
        var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
        selectobject = document.getElementById("sendemailtrfacc");
        employeed = document.getElementById("employeed").value;
        n_left = f_getPosition(selectobject, 'Left');
        n_top = f_getPosition(selectobject, 'Top');

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left - 250 + 'px';
                document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
            }
        }

        xmlhttp.open("POST", "fckeditor_email_sendrecordlink.php?companyID=" + compid + "&employeed=" + employeed,
            true);
        xmlhttp.send();
    }

    function quote_to_customer(unq_quote_id, quote_id) {
        var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;
        skillsSelect = document.getElementById("quote_status" + quote_id);
        selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
        selectobject = document.getElementById("B1" + "_" + quote_id);
        id = document.getElementById("companyID").value;
        rec_type = document.getElementById("rec_type").value;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');
                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = n_left + 'px';
                document.getElementById('light_reminder').style.top = n_top + 20 + 'px';
            }
        }

        xmlhttp.open("POST", "fckeditor_quote_to_customer_email.php?unq_quote_id=" + unq_quote_id + "&quote_id=" +
            quote_id + "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type,
            true);
        xmlhttp.send();
    }

    function btnsendemlclick_eml_p4() {
        var tmp_element1, tmp_element2, tmp_element3, tmp_element4, tmp_element5;

        tmp_element1 = document.getElementById("txtemailto").value;

        tmp_element2 = document.getElementById("email_reminder_sch_p4");

        tmp_element3 = document.getElementById("txtemailcc").value;

        tmp_element4 = document.getElementById("txtemailsubject").value;

        tmp_element5 = document.getElementById("hidden_reply_eml");

        if (tmp_element1.value == "") {
            alert("Please enter the To Email address.");
            return false;
        }

        if (tmp_element4.value == "") {
            alert("Please enter the Email Subject.");
            return false;
        }

        if (tmp_element3.value == "") {
            alert("Please enter the Cc Email address.");
            return false;
        }


        var inst = FCKeditorAPI.GetInstance("txtemailbody");
        var emailtext = inst.GetHTML();

        tmp_element5.value = emailtext;
        //alert(tmp_element5.value);
        document.getElementById("hidden_sendemail").value = "inemailmode";
        tmp_element2.submit();

    }

    function reminder_popup_set5(compid, rec_id, warehouse_id, rec_type) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                selectobject = document.getElementById("reminder_popup_set5_btn");
                n_left = f_getPosition(selectobject, 'Left');
                n_top = f_getPosition(selectobject, 'Top');

                document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                document.getElementById('light_reminder').style.display = 'block';

                document.getElementById('light_reminder').style.left = (n_left - 100) + 'px';
                document.getElementById('light_reminder').style.top = n_top - 40 + 'px';
                document.getElementById('light_reminder').style.width = 1100 + 'px';
            }
        }

        xmlhttp.open("POST", "sendemail_b2bsurvey.php?compid=" + compid + "&rec_id=" + rec_id + "&warehouse_id=" +
            warehouse_id + "&rec_type=" + rec_type, true);
        xmlhttp.send();
    }

    function quotaitem(id, boxid) {
        var selectobject = document.getElementById("lightbox_pos" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        document.getElementById('light_quota').style.left = n_left + 'px';
        document.getElementById('light_quota').style.top = n_top + 20 + 'px';

        document.getElementById("light_quota").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light_quota').style.display = 'block';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {

            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_quota").innerHTML = xmlhttp.responseText;
                document.getElementById('light_quota').style.display = 'block';

            }
        }

        xmlhttp.open("GET", "viewItem_new_mrg.php?companyID=" + id + "&itemID=" + boxid, true);
        xmlhttp.send();

    }

    function settheflg() {
        if (document.getElementById("item").value == "Delivery") {
            document.getElementById("item_delivery_flg").value = "yes";
            document.getElementById("tr_shipping_quote1").style.display = "none";
        } else {
            document.getElementById("item_delivery_flg").value = "no";
            document.getElementById("tr_shipping_quote1").style.display = "inline";
        }
    }

    function display_gaylords_child(id, flg, n_left, n_top) {
        var selectobject = document.getElementById("lightbox");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";
        if (flg == 0) {
            sstr = sstr +
                "Below list only display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
            sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id +
                ", 1," + n_left + "," + n_top + ")'>Display Only Available Boxes</a>";
        } else {
            sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
            sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id +
                ", 0," + n_left + "," + n_top + ")'>Display All Boxes</a>";
        }
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        var selectobject = document.getElementById("lightbox");
        //var n_left = f_getPosition(selectobject, 'Left');
        //var n_top  = f_getPosition(selectobject, 'Top');
        document.getElementById('light_gaylord_new').style.display = 'block';
        document.getElementById('light_gaylord_new').style.left = n_left + 20 + 'px';
        document.getElementById('light_gaylord_new').style.top = n_top + 20 + 'px';

        document.getElementById("light_gaylord_new").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_gaylord_new").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "gaylords_mrg.php?ID=" + id + "&display-allrec=" + flg, true);
        xmlhttp.send();
    }

    function display_gaylords_autoload(id, flg) {
        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";
        if (flg == 0) {
            sstr = sstr +
                "Below list display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
            sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id +
                ", 1 ,0,0)'>Display Only Available Boxes</a>";
        } else {
            sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
            sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" + id +
                ", 0,0,0)'>Display All Boxes</a>";
        }
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttpauto = new XMLHttpRequest();
        } else {
            xmlhttpauto = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttpauto.onreadystatechange = function() {
            if (xmlhttpauto.readyState == 4 && xmlhttpauto.status == 200) {
                document.getElementById("light_gaylord_new").innerHTML = sstr + xmlhttpauto.responseText;
                document.getElementById("gayloardtoolautoload").innerHTML = "Data loaded.";
            }
        }
        xmlhttpauto.open("GET", "gaylords_mrg.php?ID=" + id + "&display-allrec=" + flg, true);
        xmlhttpauto.send();
    }

    function display_gaylords(id, flg) {
        if (document.getElementById("light_gaylord_new").innerHTML == "") {
            var selectobject = document.getElementById("lightbox");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_gaylord_new').style.display = 'block';
            document.getElementById('light_gaylord_new').style.left = n_left + 20 + 'px';
            document.getElementById('light_gaylord_new').style.top = n_top + 20 + 'px';

            document.getElementById("light_gaylord_new").innerHTML =
                "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

            var sstr = "";
            sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
            sstr = sstr + "<tr align='center'>";
            sstr = sstr + "<td bgcolor='#FF9900'>";
            sstr = sstr +
                "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
            sstr = sstr + "&nbsp;&nbsp;";
            sstr = sstr +
                "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
            sstr = sstr + "<br>";
            if (flg == 0) {
                sstr = sstr +
                    "Below list display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" +
                    id + ", 1 ," + n_left + "," + n_top + ")'>Display Only Available Boxes</a>";
            } else {
                sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
                sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_gaylords_child(" +
                    id + ", 0," + n_left + "," + n_top + ")'>Display All Boxes</a>";
            }
            sstr = sstr + "</td>";
            sstr = sstr + "</tr>";
            sstr = sstr + "</table>";

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("light_gaylord_new").innerHTML = sstr + xmlhttp.responseText;
                }
            }
            xmlhttp.open("GET", "gaylords_mrg.php?ID=" + id + "&display-allrec=" + flg, true);
            xmlhttp.send();
        } else {
            var selectobject = document.getElementById("lightbox");
            var n_left = f_getPosition(selectobject, 'Left');
            var n_top = f_getPosition(selectobject, 'Top');
            document.getElementById('light_gaylord_new').style.display = 'block';
            document.getElementById('light_gaylord_new').style.left = n_left + 20 + 'px';
            document.getElementById('light_gaylord_new').style.top = n_top + 20 + 'px';
        }
    }

    //New gaylord matching tool
    function display_new_gaylords_autoload(id, flg) {
        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";
        sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
        sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_new_gaylords_child(" + id + "," +
            this.value + ",0,0)'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
        if (flg == 5) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">All Boxes (No filter)</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttpauto_new = new XMLHttpRequest();
        } else {
            xmlhttpauto_new = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttpauto_new.onreadystatechange = function() {
            if (xmlhttpauto_new.readyState == 4 && xmlhttpauto_new.status == 200) {
                document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttpauto.responseText;
                document.getElementById("gayloardtoolautoload1").innerHTML = "Data loaded.";
            }
        }


        xmlhttpauto_new.open("GET", "sales_gaylords.php?ID=" + id + "&display-allrec=" + flg, true);
        xmlhttpauto_new.send();
    }
    //

    function display_new_gaylords(id, boxid, flg) {
        //if (document.getElementById("light_gaylord_new1").innerHTML == "") 
        //{

        var selectobject = document.getElementById("lightbox" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_gaylord_new1').style.display = 'block';
        document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
        document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
        document.getElementById('light_gaylord_new1').style.height = 580 + 'px';

        document.getElementById("light_gaylord_new1").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW GAYLORD MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
        sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_new_gaylords_child(" +
            id + "," + this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
        if (flg == 5) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">All Boxes (No filter)</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "sales_gaylords.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg, true);
        xmlhttp.send();
        //}
        /*else 
        {
        	var selectobject = document.getElementById("lightbox"); 
        	var n_left = f_getPosition(selectobject, 'Left');
        	var n_top  = f_getPosition(selectobject, 'Top');
        	document.getElementById('light_gaylord_new1').style.display='block';
        	document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
        	document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
        }*/
    }

    function display_new_gaylords_child(id, flg, boxid, n_left, n_top) {
        var flgs = document.getElementById("sort_g_tool").value;
        //alert(flgs);
        //
        var selectobject = document.getElementById("lightbox" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>GAYLORD MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        //
        if (flgs == 1) {
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
        }
        if (flgs == 2) {
            sstr = sstr +
                "Below list only display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available <= 1 TL' and UCB Owned inventory &nbsp;&nbsp;";
        }

        if (flgs == 5) {
            sstr = sstr + "Below list display all boxes &nbsp;&nbsp;";
        }
        //
        sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_new_gaylords_child(" +
            id + "," + this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flgs == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria</option><option value='2'";
        if (flgs == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
        if (flgs == 5) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">All Boxes (No filter)</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        /*if(flgs==2 || flgs==5){
        	sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
        	sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
        	sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) + "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";
        	if(flgs==2){
        		sstr = sstr + "And shown boxes based on Matching Criteria & Available NOW.')";
        	} 
        	if(flgs==5){
        		sstr = sstr + "And shown All Boxes (No filter).')";
        	} 
        	sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
        }*/

        var selectobject = document.getElementById("lightbox" + boxid);
        //var n_left = f_getPosition(selectobject, 'Left');
        //var n_top  = f_getPosition(selectobject, 'Top');
        document.getElementById('light_gaylord_new1').style.display = 'block';
        document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
        document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';

        document.getElementById("light_gaylord_new1").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "sales_gaylords.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs, true);
        xmlhttp.send();
    }

    //Gaylord matching tool - Bottom links

    //------------------------------------------------------------------------------------------
    //Quote request matching tool for Gaylord
    function display_request_gaylords(id, boxid, flg, viewflg, load_all = 0) {
        //if (document.getElementById("light_gaylord_new1").innerHTML == "") 
        //{
        //alert(viewflg);
        var selectobject = document.getElementById("lightbox_g" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_gaylord_new1').style.display = 'block';
        document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
        document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
        document.getElementById('light_gaylord_new1').style.height = 580 + 'px';

        document.getElementById("light_gaylord_new1").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td class='display_maintitle'>";
        sstr = sstr + "GAYLORD MATCHING TOOL";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        sstr = sstr + "Timing&nbsp;&nbsp;";
        sstr = sstr +
            "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child(" + id +
            "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Rdy Now + Presell</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">FTL Rdy Now ONLY</option>";
        sstr = sstr + "</select>";
        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
        //
        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching</option><option value='2' ";
        if (flg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        sstr = sstr + "View&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1' ";
        if (viewflg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">UCB View</option><option value='2' ";
        if (viewflg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Customer Facing View</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        var sstr_load_all = "";
        sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr_load_all = sstr_load_all + "<tr align='center'>";
        sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
        sstr_load_all = sstr_load_all + "GAYLORD MATCHING TOOL";
        sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
        sstr_load_all = sstr_load_all +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

        sstr_load_all = sstr_load_all + "</td>";
        sstr_load_all = sstr_load_all + "</tr>";
        sstr_load_all = sstr_load_all + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (load_all == 0) {
                    document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
                } else {
                    document.getElementById("light_gaylord_new1").innerHTML = sstr_load_all + xmlhttp.responseText;
                }
            }
        }

        xmlhttp.open("GET", "quote_request_gaylords_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg +
            "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&load_all=" + load_all, true);
        xmlhttp.send();
        //}
        /*else 
        {
        	var selectobject = document.getElementById("lightbox"); 
        	var n_left = f_getPosition(selectobject, 'Left');
        	var n_top  = f_getPosition(selectobject, 'Top');
        	document.getElementById('light_gaylord_new1').style.display='block';
        	document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
        	document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';
        }*/
    }

    function display_request_gaylords_child(id, flg, boxid, viewflg, n_left, n_top) {
        var flgs = document.getElementById("sort_g_tool").value;
        var flgs_org = document.getElementById("sort_g_tool").value;
        var viewflgs = document.getElementById("sort_g_view").value;
        //alert(flgs);
        //

        var g_timing = document.getElementById("g_timing").value;
        var sort_g_tool2 = document.getElementById("sort_g_tool2").value;

        var selectobject = document.getElementById("lightbox_g" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td class='display_maintitle'>";
        sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >GAYLORD MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_gaylord_new1').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
        //
        if (flgs == 1) {
            sstr = sstr +
                "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
        if (flgs == 2) {
            sstr = sstr +
                "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }

        /* if(sort_g_tool2 == 1){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
		if(sort_g_tool2 == 2){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</font></span></div>";
        }*/
        //
        sstr = sstr + "<br>";
        //if (flg == 0) {
        //  alert(flgs);

        //new code		  
        sstr = sstr + "Timing&nbsp;&nbsp;";
        sstr = sstr +
            "<select class='basic_style' name='g_timing' id='g_timing' onChange='display_request_gaylords_child(" + id +
            "," + this.value + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

        if (g_timing == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Rdy Now + Presell</option><option value='2'";
        if (g_timing == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">FTL Rdy Now ONLY</option>";
        sstr = sstr + "</select>";
        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool' id='sort_g_tool' onChange='display_request_gaylords_child(" +
            id + "," + this.value + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flgs_org == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell</option><option value='2'";
        if (flgs_org == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
        //
        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool2' id='sort_g_tool2' onChange='display_request_gaylords_child(" +
            id + "," + this.value + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

        if (sort_g_tool2 == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching</option><option value='2'";
        if (sort_g_tool2 == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        sstr = sstr + "View&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_view' id='sort_g_view' onChange='display_request_gaylords_child(" +
            id + "," + flgs + "," + boxid + "," + this.value + "," + n_left + "," + n_top + ")'><option value='1'";
        if (viewflgs == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">UCB View</option><option value='2'";
        if (viewflgs == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Customer Facing View</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";
        //New code

        /*if(flgs==1 || flgs==2 || sort_g_tool2 == 1 || sort_g_tool2 == 2){
        	sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
        	sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
        	sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) + "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";
        	if(flgs==1){
        		sstr = sstr + "And shown boxes based on Matching Criteria & Available only.')";
        	} 
        	if(flgs==2){
        		sstr = sstr + "And shown boxes based on Matching Criteria & Available & Potential.')";
        	} 
        	if(sort_g_tool2 == 1){
        		sstr = sstr + "And shown All Boxes (Available).')";
        	} 
        	if(sort_g_tool2 == 2){
        		sstr = sstr + "And shown All Boxes (Available & Potential).')";
        	} 
        	sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
        }*/

        var selectobject = document.getElementById("lightbox");
        //var n_left = f_getPosition(selectobject, 'Left');
        //var n_top  = f_getPosition(selectobject, 'Top');
        document.getElementById('light_gaylord_new1').style.display = 'block';
        document.getElementById('light_gaylord_new1').style.left = n_left + 20 + 'px';
        document.getElementById('light_gaylord_new1').style.top = n_top + 20 + 'px';

        document.getElementById("light_gaylord_new1").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_gaylord_new1").innerHTML = sstr + xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "quote_request_gaylords_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flgs +
            "&display_view=" + viewflgs + "&g_timing=" + g_timing + "&sort_g_tool2=" + sort_g_tool2, true);
        xmlhttp.send();
    }

    //End quote request matching tool for Gaylord
    //---------------------------------------------------------------------------
    //Display quote request shipping matching tool
    function display_request_shipping_tool(id, flg, viewflg, boxid, load_all = 0) {
        var selectobject = document.getElementById("lightbox_req_shipping" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_shipping').style.display = 'block';
        document.getElementById('light_new_shipping').style.left = n_left + 20 + 'px';
        document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';
        document.getElementById('light_new_shipping').style.height = 580 + 'px';

        document.getElementById("light_new_shipping").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td class='display_maintitle'>";
        sstr = sstr + "SHIPPING BOX MATCHING TOOL";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        sstr = sstr + "Timing&nbsp;&nbsp;";
        sstr = sstr +
            "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Rdy Now + Presell</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">FTL Rdy Now ONLY</option>";
        sstr = sstr + "</select>";
        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
        //
        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1' ";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching</option><option value='2' ";
        if (flg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        sstr = sstr + "View&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child(" +
            id + "," + flg + "," + boxid + "," + this.value + "," + n_left + "," + n_top + ")'><option value='1' ";
        if (viewflg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">UCB View</option><option value='2'";
        if (viewflg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Customer Facing View</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        var sstr_load_all = "";
        sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr_load_all = sstr_load_all + "<tr align='center'>";
        sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
        sstr_load_all = sstr_load_all + "SHIPPING BOX MATCHING TOOL";
        sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
        sstr_load_all = sstr_load_all +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

        sstr_load_all = sstr_load_all + "</td>";
        sstr_load_all = sstr_load_all + "</tr>";
        sstr_load_all = sstr_load_all + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (load_all == 0) {
                    document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
                } else {
                    document.getElementById("light_new_shipping").innerHTML = sstr_load_all + xmlhttp.responseText;
                }

            }
        }

        xmlhttp.open("GET", "quote_request_shipping_tool_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" +
            flg + "&display_view=" + viewflg + "&sort_g_tool2=" + flg + "&load_all=" + load_all, true);
        xmlhttp.send();
    }

    function display_request_shipping_child(id, flg, boxid, viewflg, n_left, n_top) {
        var flgs = document.getElementById("sort_g_tool_shipping").value;
        var flgs_org = document.getElementById("sort_g_tool_shipping").value;
        var viewflgs = document.getElementById("sort_g_view_shipping").value;

        var g_timing_shipping = document.getElementById("g_timing_shipping").value;
        var sort_g_tool_shipping2 = document.getElementById("sort_g_tool_shipping2").value;

        var selectobject = document.getElementById("lightbox_req_shipping" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td class='display_maintitle'>";
        sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SHIPPING BOX MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
        //
        if (flgs == 1) {
            sstr = sstr +
                "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
        if (flgs == 2) {
            sstr = sstr +
                "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }

        sstr = sstr + "<br>";

        //new code		  
        sstr = sstr + "Timing&nbsp;&nbsp;";
        sstr = sstr +
            "<select class='basic_style' name='g_timing_shipping' id='g_timing_shipping' onChange='display_request_shipping_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

        if (g_timing_shipping == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Rdy Now + Presell</option><option value='2'";
        if (g_timing_shipping == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">FTL Rdy Now ONLY</option>";
        sstr = sstr + "</select>";
        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_shipping' id='sort_g_tool_shipping' onChange='display_request_shipping_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flgs_org == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell</option><option value='2'";
        if (flgs_org == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
        //
        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_shipping2' id='sort_g_tool_shipping2' onChange='display_request_shipping_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

        if (sort_g_tool_shipping2 == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching</option><option value='2'";
        if (sort_g_tool_shipping2 == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        sstr = sstr + "View&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_view_shipping' id='sort_g_view_shipping' onChange='display_request_shipping_child(" +
            id + "," + flgs + "," + boxid + "," + this.value + "," + n_left + "," + n_top + ")'><option value='1'";
        if (viewflgs == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">UCB View</option><option value='2'";
        if (viewflgs == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Customer Facing View</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        //New code
        var selectobject = document.getElementById("lightbox");
        document.getElementById('light_new_shipping').style.display = 'block';
        document.getElementById('light_new_shipping').style.left = n_left + 20 + 'px';
        document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

        document.getElementById("light_new_shipping").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "quote_request_shipping_tool_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" +
            flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing_shipping + "&sort_g_tool2=" +
            sort_g_tool_shipping2, true);
        xmlhttp.send();
    }
    //end quote request shipping matching tool
    //
    //------------------------------------------------------------------
    //Supersack Matching tool
    //Display quote request supersacks matching tool
    function display_request_supersacks_tool(id, flg, viewflg, boxid, load_all = 0) {
        var selectobject = document.getElementById("lightbox_req_supersacks" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_supersacks').style.display = 'block';
        document.getElementById('light_new_supersacks').style.left = n_left + 20 + 'px';
        document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';
        document.getElementById('light_new_supersacks').style.height = 580 + 'px';

        document.getElementById("light_new_supersacks").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td class='display_maintitle'>";
        sstr = sstr + "SUPERSACK MATCHING TOOL";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        sstr = sstr + "Timing&nbsp;&nbsp;";
        sstr = sstr +
            "<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Rdy Now + Presell</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">FTL Rdy Now ONLY</option>";
        sstr = sstr + "</select>";
        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
        //
        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        sstr = sstr + "View&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + ")'><option value='1'";
        if (viewflg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">UCB View</option><option value='2'";
        if (viewflg == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Customer Facing View</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        var sstr_load_all = "";
        sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr_load_all = sstr_load_all + "<tr align='center'>";
        sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
        sstr_load_all = sstr_load_all + "SUPERSACK MATCHING TOOL";
        sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
        sstr_load_all = sstr_load_all +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

        sstr_load_all = sstr_load_all + "</td>";
        sstr_load_all = sstr_load_all + "</tr>";
        sstr_load_all = sstr_load_all + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (load_all == 0) {
                    document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText;
                } else {
                    document.getElementById("light_new_supersacks").innerHTML = sstr_load_all + xmlhttp
                        .responseText;
                }
            }
        }

        xmlhttp.open("GET", "quote_req_supersacks_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" +
            flg + "&display_view=" + viewflg + "&display_view=" + viewflg + "&load_all=" + load_all, true);
        xmlhttp.send();
    }

    function display_request_supersack_child(id, flg, client_flg, boxid, n_left, n_top) {
        var flgs = document.getElementById("sort_g_tool_supersack").value;
        var flgs_org = document.getElementById("sort_g_tool_supersack").value;
        var viewflgs = document.getElementById("sort_g_view_supersack").value;
        //alert(flgs);
        //

        var g_timing_supersack = document.getElementById("g_timing_supersack").value;
        var sort_g_tool_supersack2 = document.getElementById("sort_g_tool_supersack2").value;

        var selectobject = document.getElementById("lightbox_req_supersacks" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td class='display_maintitle'>";
        sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >SUPERSACK MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
        //
        if (flgs == 1) {
            sstr = sstr +
                "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
        if (flgs == 2) {
            sstr = sstr +
                "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }

        /* if(sort_g_tool_supersack2 == 1){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
		if(sort_g_tool_supersack2 == 2){
            sstr = sstr + "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display all boxes with status 'Available', <br>'Available, but Need Approval to Sell', <br>'Available, Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</font></span></div>";
        }*/
        //
        sstr = sstr + "<br>";
        //if (flg == 0) {
        //  alert(flgs);

        //new code		  
        sstr = sstr + "Timing&nbsp;&nbsp;";
        sstr = sstr +
            "<select class='basic_style' name='g_timing_supersack' id='g_timing_supersack' onChange='display_request_supersack_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

        if (g_timing_supersack == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Rdy Now + Presell</option><option value='2'";
        if (g_timing_supersack == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">FTL Rdy Now ONLY</option>";
        sstr = sstr + "</select>";
        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_supersack' id='sort_g_tool_supersack' onChange='display_request_supersack_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flgs_org == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell</option><option value='2'";
        if (flgs_org == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
        //
        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_supersack2' id='sort_g_tool_supersack2' onChange='display_request_supersack_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";

        if (sort_g_tool_supersack2 == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching</option><option value='2'";
        if (sort_g_tool_supersack2 == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        sstr = sstr + "View&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_view_supersack' id='sort_g_view_supersack' onChange='display_request_supersack_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + ")'><option value='1'";
        if (viewflgs == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">UCB View</option><option value='2'";
        if (viewflgs == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Customer Facing View</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        var selectobject = document.getElementById("lightbox");
        //var n_left = f_getPosition(selectobject, 'Left');
        //var n_top  = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_supersacks').style.display = 'block';
        document.getElementById('light_new_supersacks').style.left = n_left + 20 + 'px';
        document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';

        document.getElementById("light_new_supersacks").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "quote_req_supersacks_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" +
            flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing_supersack + "&sort_g_tool2=" +
            sort_g_tool_supersack2, true);
        xmlhttp.send();
    }

    //end quote request supersack matching tool		
    //------------------------------------------------------------------
    //Pallet Matching tool
    //Display quote request Pallet matching tool
    function display_request_Pallet_tool(id, flg, viewflg, boxid, pallet_height = 0, pallet_width = 0, ctrlid = 0,
        load_all = 0) {

        if (boxid == 0) {
            var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
        } else {
            var selectobject = document.getElementById("lightbox_req_pal" + boxid);
        }
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_pal').style.display = 'block';
        document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
        document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';
        document.getElementById('light_new_pal').style.height = 580 + 'px';

        document.getElementById("light_new_pal").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td class='display_maintitle'>";
        sstr = sstr + "PALLET MATCHING TOOL";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        sstr = sstr + "Timing&nbsp;&nbsp;";
        sstr = sstr +
            "<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + "," + pallet_height + "," +
            pallet_width + "," + ctrlid + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Rdy Now + Presell</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">FTL Rdy Now ONLY</option>";
        sstr = sstr + "</select>";
        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + "," + pallet_height + "," +
            pallet_width + "," + ctrlid + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Available to Sell + Potential to Get</option></select>";

        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child(" +
            id + "," + flg + "," + boxid + "," + viewflg + "," + n_left + "," + n_top + "," + pallet_height + "," +
            pallet_width + "," + ctrlid + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        sstr = sstr + "View&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" +
            id + "," + flg + "," + boxid + "," + this.value + "," + n_left + "," + n_top + "," + pallet_height + "," +
            pallet_width + "," + ctrlid + ")'><option value='1'";
        if (viewflg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">UCB View</option><option value='2'";
        if (viewflg == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Customer Facing View</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        var sstr_load_all = "";
        sstr_load_all = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr_load_all = sstr_load_all + "<tr align='center'>";
        sstr_load_all = sstr_load_all + "<td class='display_maintitle'>";
        sstr_load_all = sstr_load_all + "PALLET MATCHING TOOL";
        sstr_load_all = sstr_load_all + "&nbsp;&nbsp;";
        sstr_load_all = sstr_load_all +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";

        sstr_load_all = sstr_load_all + "</td>";
        sstr_load_all = sstr_load_all + "</tr>";
        sstr_load_all = sstr_load_all + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (load_all == 0) {
                    document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
                } else {
                    document.getElementById("light_new_pal").innerHTML = sstr_load_all + xmlhttp.responseText;
                }
            }
        }

        xmlhttp.open("GET", "quote_req_pallet_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" + flg +
            "&display_view=" + viewflg + "&pallet_height=" + pallet_height + "&pallet_width=" + pallet_width +
            "&load_all=" + load_all, true);
        xmlhttp.send();
    }

    function display_request_pallet_child(id, flg, boxid, viewflg, n_left, n_top, pallet_height = 0, pallet_width = 0,
        ctrlid = 0) {
        var flgs = document.getElementById("sort_g_tool_pallet").value;
        var flgs_org = document.getElementById("sort_g_tool_pallet").value;
        var viewflgs = document.getElementById("sort_g_view_pallet").value;
        //alert(flgs);
        //

        var g_timing_pallet = document.getElementById("g_timing_pallet").value;
        var sort_g_tool_pallet2 = document.getElementById("sort_g_tool_pallet2").value;

        if (boxid == 0) {
            var selectobject = document.getElementById("lightbox_req_pal" + ctrlid);
        } else {
            var selectobject = document.getElementById("lightbox_req_pal" + boxid);
        }

        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' class='basic_style'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td class='display_maintitle'>";
        sstr = sstr + "<font face='Arial, Helvetica, sans-serif' size='1' >PALLET MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_pal').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br><font face='Arial, Helvetica, sans-serif' size='1'>";
        //
        if (flgs == 1) {
            sstr = sstr +
                "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
        if (flgs == 2) {
            sstr = sstr +
                "Items Displayed <div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i><span class='tooltiptext'>Below list display 'Available', 'Available, <br>but Need Approval to Sell', 'Available, <br>Currently Recycling, Sell w/ Lead Time!', <br>'Unavailable (New Lead', 'Unavailable (Qualified, In Process)', <br>'Unavailable (Qualified, No Customers)' <br>and UCB Owned inventory &nbsp;&nbsp;</span></div>";
        }
        sstr = sstr + "<br>";

        //new code		  
        sstr = sstr + "Timing&nbsp;&nbsp;";
        sstr = sstr +
            "<select class='basic_style' name='g_timing_pallet' id='g_timing_pallet' onChange='display_request_pallet_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + "," + pallet_height + "," +
            pallet_width + "," + ctrlid + ")'><option value='1'";

        if (g_timing_pallet == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Rdy Now + Presell</option><option value='2'";
        if (g_timing_pallet == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">FTL Rdy Now ONLY</option>";
        sstr = sstr + "</select>";
        sstr = sstr + "&nbsp;&nbsp;Status&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_pallet' id='sort_g_tool_pallet' onChange='display_request_pallet_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + "," + pallet_height + "," +
            pallet_width + "," + ctrlid + ")'><option value='1'";

        if (flgs_org == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Available to Sell</option><option value='2'";
        if (flgs_org == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Available to Sell + Potential to Get</option></select>";
        //
        sstr = sstr + "&nbsp;&nbsp;Criteria&nbsp;&nbsp;";
        //sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr +
            "<select class='basic_style' name='sort_g_tool_pallet2' id='sort_g_tool_pallet2' onChange='display_request_pallet_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + "," + pallet_height + "," +
            pallet_width + "," + ctrlid + ")'><option value='1'";

        if (sort_g_tool_pallet2 == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching</option><option value='2'";
        if (sort_g_tool_pallet2 == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">All Items (Ignore Criteria)</option></select>";

        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        sstr = sstr + "View&nbsp;&nbsp;";

        sstr = sstr +
            "<select class='basic_style' name='sort_g_view_pallet' id='sort_g_view_pallet' onChange='display_request_pallet_child(" +
            id + "," + flgs + "," + boxid + "," + viewflgs + "," + n_left + "," + n_top + "," + pallet_height + "," +
            pallet_width + "," + ctrlid + ")'><option value='1'";
        if (viewflgs == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">UCB View</option><option value='2'";
        if (viewflgs == 2) {
            sstr = sstr + "selected";
        }
        sstr = sstr + ">Customer Facing View</option></select>";

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        var selectobject = document.getElementById("lightbox");
        //var n_left = f_getPosition(selectobject, 'Left');
        //var n_top  = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_pal').style.display = 'block';
        document.getElementById('light_new_pal').style.left = n_left + 20 + 'px';
        document.getElementById('light_new_pal').style.top = n_top + 20 + 'px';

        document.getElementById("light_new_pal").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_new_pal").innerHTML = sstr + xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "quote_req_pallet_matching_new.php?ID=" + id + "&gbox=" + boxid + "&display-allrec=" +
            flgs + "&display_view=" + viewflgs + "&g_timing=" + g_timing_pallet + "&sort_g_tool2=" +
            sort_g_tool_pallet2 + "&pallet_height=" + pallet_height + "&pallet_width=" + pallet_width, true);
        xmlhttp.send();
    }

    //end quote request pallet matching tool		
    //------------------------------------------------------------------
    //

    //Pallets matching tool
    function display_new_pallets(id, boxid, flg) {
        var selectobject = document.getElementById("lightbox_new_pallets");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_pallets_new').style.display = 'block';
        document.getElementById('light_pallets_new').style.left = n_left + 20 + 'px';
        document.getElementById('light_pallets_new').style.top = n_top + 20 + 'px';
        document.getElementById('light_pallets_new').style.height = 580 + 'px';

        document.getElementById("light_pallets_new").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW PALLETS MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_pallets_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        /*sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
        sstr = sstr + "<br>";
        //if (flg == 0) {
        //alert(boxid);
        sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_new_pallets_child(" + id + "," + this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";
        if(flg==1){
           sstr = sstr + " selected ";
        } 
        sstr = sstr +">Matching Criteria</option><option value='2'";
        if(flg==2){
           sstr = sstr +"selected";
        }  
        sstr = sstr +">Matching Criteria & Available NOW</option><option value='5'";
        if(flg==5){
           sstr = sstr +"selected";
        }  
        sstr = sstr +">All Boxes (No filter)</option></select>";*/

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        //sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_pallets_new").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "sales_pallets.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
        xmlhttp.send();
    }

    function display_new_pallets_child(id, flg, boxid, n_left, n_top) {
        var flgs = document.getElementById("sort_g_tool").value;
        //alert(flgs);
        //
        var selectobject = document.getElementById("lightbox");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW PALLETS MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_pallets_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        //
        /*if(flgs==1){
            sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
        }
        if(flgs==2){
            sstr = sstr + "Below list only display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available <= 1 TL' and UCB Owned inventory &nbsp;&nbsp;";
        }
        if(flgs==5){
            sstr = sstr + "Below list display all boxes &nbsp;&nbsp;";
        }
        //
		sstr = sstr + "<br>";
		//if (flg == 0) {
            
        sstr = sstr + "<select name='sort_g_tool' id='sort_g_tool' onChange='display_new_pallets_child(" + id + "," + this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'"; 
                
       if(flgs==1){
           sstr = sstr + " selected ";
       } 
       sstr = sstr +">Matching Criteria</option><option value='2'";
       if(flgs==2){
           sstr = sstr +"selected";
       }  
        sstr = sstr +">Matching Criteria & Available NOW</option><option value='5'";
        if(flgs==5){
           sstr = sstr +"selected";
       }  
        sstr = sstr +">All Boxes (No filter)</option></select>";*/

        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        /*if(flgs==2 || flgs==5){
        	sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
        	sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
        	sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) + "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";
        	if(flgs==2){
        		sstr = sstr + "And shown boxes based on Matching Criteria & Available NOW.')";
        	} 
        	if(flgs==5){
        		sstr = sstr + "And shown All Boxes (No filter).')";
        	} 
        	//sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
        	sstr = sstr + "</table>";
        }*/

        var selectobject = document.getElementById("lightbox_new_pallets");
        //var n_left = f_getPosition(selectobject, 'Left');
        //var n_top  = f_getPosition(selectobject, 'Top');
        document.getElementById('light_pallets_new').style.display = 'block';
        document.getElementById('light_pallets_new').style.left = n_left + 20 + 'px';
        document.getElementById('light_pallets_new').style.top = n_top + 20 + 'px';

        document.getElementById("light_pallets_new").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_pallets_new").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "sales_pallets.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flgs, true);
        xmlhttp.send();
    }
    //
    function display_new_pallets_all(id, boxid, flg) {
        var selectobject = document.getElementById("lightbox_new_pallets0");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_pallets_new').style.display = 'block';
        document.getElementById('light_pallets_new').style.left = n_left + 20 + 'px';
        document.getElementById('light_pallets_new').style.top = n_top + 20 + 'px';
        document.getElementById('light_pallets_new').style.height = 580 + 'px';

        document.getElementById("light_pallets_new").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW PALLETS MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_pallets_new').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        sstr = sstr + "</td></tr>";
        //sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_pallets_new").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "sales_pallets.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
        xmlhttp.send();
    }
    //End pallets matching tool
    //
    function upd_boxes_warehouse_data(comp_id, warehouse_id) {
        var selectobject = document.getElementById("updatelist");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_boxupd').style.display = 'block';
        document.getElementById('light_boxupd').style.left = n_left - 200 + 'px';
        document.getElementById('light_boxupd').style.top = n_top + 20 + 'px';

        document.getElementById("light_boxupd").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr =
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_boxupd').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_boxupd").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "boxes_warehouse_data.php?ID=" + comp_id + "&warehouse_id=" + warehouse_id, true);
        xmlhttp.send();
    }

    function Add_boxes_warehouse_data(warehouse_id, box_id) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                alert("Box Added.");
                document.getElementById("div_inv_items").innerHTML = xmlhttp.responseText;
                document.getElementById("updbox_action_div" + box_id).innerHTML =
                    "<input type='button' name='btnadd' value='Add' onclick='Remove_boxes_warehouse_data(" +
                    warehouse_id + ", " + box_id + ")'>";
            }
        }
        xmlhttp.open("GET", "upd_boxes_warehouse_data.php?warehouse_id=" + warehouse_id + "&boxid=" + box_id +
            "&upd_action=1", true);
        xmlhttp.send();
    }

    function Remove_boxes_warehouse_data(warehouse_id, box_id) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                alert("Box Removed.");
                document.getElementById("div_inv_items").innerHTML = xmlhttp.responseText;
                document.getElementById("updbox_action_div" + box_id).innerHTML =
                    "<input type='button' name='btnadd' value='Add' onclick='Add_boxes_warehouse_data(" +
                    warehouse_id + ", " + box_id + ")'>";
            }
        }
        xmlhttp.open("GET", "upd_boxes_warehouse_data.php?warehouse_id=" + warehouse_id + "&boxid=" + box_id +
            "&upd_action=2", true);
        xmlhttp.send();
    }

    function addgaylord(companyid, inventoryid) {
        document.getElementById('light_gaylord').style.display = 'none';
        //document.getElementById("quota_boxes_maindiv").style.display = "none";
        document.getElementById("quota_boxes_maindiv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?ID=" + companyid + "&inventoryID=" + inventoryid +
            "&addgayloard=yes", true);
        xmlhttp.send();
    }

    function addgaylordMultiple(companyid, inventoryids) {
        document.getElementById('light_gaylord').style.display = 'none';
        //document.getElementById("quota_boxes_maindiv").style.display = "none";
        document.getElementById("quota_boxes_maindiv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?ID=" + companyid + "&inventoryIDs=" + inventoryids +
            "&addgayloardMultiple=yes", true);
        xmlhttp.send();
    }

    function popup_general_update(frm, flg, transid, warehouse_id) {
        notesdata = document.getElementById('frm_orderissue_notes').value;
        est_cost = 0;
        reason = 0;
        var revenue_loss;
        var vendorsolution = '';
        if (flg == 0 && frm == "orderissue") {
            est_cost = document.getElementById('frm_orderissue_est_cost').value;
            revenue_loss = document.getElementById('frm_revenue_loss').value;
            reason = document.getElementById('frm_orderissue_reason').value;
            vendorsolution = document.getElementById('frm_order_vendor_solution').value; // new line added
        }
        document.getElementById('popup_window').style.height = '350px';
        document.getElementById("popup_window").innerHTML =
            "<br/>Updating .....<img src='images/wait_animated.gif' />" + document.getElementById("popup_window")
            .innerHTML;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                alert("Data updated");
                document.getElementById('hd_frm_orderissue_close').onclick();

                if (frm == "accounting_issue") {
                    document.getElementById('frm_accounting_issue_td').innerHTML = xmlhttp.responseText;
                }
                if (frm == "fulfillmentissue") {
                    document.getElementById('frm_fulfillment_issue_td').innerHTML = xmlhttp.responseText;
                }
                if (frm == "orderissue") {
                    document.getElementById('frm_orderissue_td').innerHTML = xmlhttp.responseText;
                }

                document.getElementById('frm_trans_notes_td').innerHTML =
                    "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

                if (window.XMLHttpRequest) {
                    xmlhttp_child = new XMLHttpRequest();
                } else {
                    xmlhttp_child = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp_child.onreadystatechange = function() {
                    if (xmlhttp_child.readyState == 4 && xmlhttp_child.status == 200) {
                        document.getElementById('frm_trans_notes_td').innerHTML = xmlhttp_child.responseText;
                    }
                }

                xmlhttp_child.open("GET", "search_result_include_crm_forajax.php?warehouse_id=" + warehouse_id +
                    "&rec_id=" + transid + "&rec_type=Supplier", true);
                xmlhttp_child.send();

            }
        }

        xmlhttp.open("GET", "loop_popup_general_update.php?warehouse_id=" + warehouse_id + "&rec_id=" + transid +
            "&hd_frm_orderissue_frm=" + frm + "&hd_frm_orderissue_flg=" + flg + "&hd_frm_orderissue_transid=" +
            transid + "&hd_frm_orderissue_warehouse_id=" + warehouse_id + "&frm_orderissue_notes=" + notesdata +
            "&est_cost=" + est_cost + "&revenue_loss=" + revenue_loss + "&reason=" + reason + "&vendorsolution=" +
            vendorsolution, true); // new variable also passed.			
        xmlhttp.send();
    }

    function show_popup_general(frm, flg, ctrlid, transid, warehouse_id) {
        var selectobject = document.getElementById(ctrlid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('popup_window').style.display = 'block';
        document.getElementById('popup_window').style.left = n_left + 50 + 'px';
        document.getElementById('popup_window').style.top = n_top + 20 + 'px';
        document.getElementById('popup_window').style.width = '300px';
        document.getElementById('popup_window').style.height = '300px';

        document.getElementById("popup_window").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("popup_window").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "loop_show_popup_general.php?frm=" + frm + "&flg=" + flg + "&transid=" + transid +
            "&warehouse_id=" + warehouse_id, true);
        xmlhttp.send();

    }

    function display_shipping_tool(id, flg, boxid, ctrlid) {
        var selectobject = document.getElementById("lightbox_shipping" + ctrlid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_shipping').style.display = 'block';
        document.getElementById('light_shipping').style.left = n_left - 200 + 'px';
        document.getElementById('light_shipping').style.top = n_top + 20 + 'px';

        document.getElementById("light_shipping").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#C0CDDA'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";
        if (flg == 0) {
            sstr = sstr +
                "Below list display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
            sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_shipping_child(" + id +
                ", 1 ," + boxid + "," + ctrlid + "," + n_left + "," + n_top + ")'>Display only Available boxes</a>";
        } else {
            sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
            sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_shipping_child(" + id +
                ", 0," + boxid + "," + ctrlid + "," + n_left + "," + n_top + ")'>Display All Boxes</a>";
        }
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_shipping").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
        xmlhttp.send();
    }

    function display_shipping_child(id, flg, boxid, ctrlid, n_left, n_top) {
        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#C0CDDA'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";
        if (flg == 0) {
            sstr = sstr +
                "Below list only display 'Available Now', 'Available & Urgent', 'Available >= 1 TL', 'Available < 1 TL', 'Check Loops' boxes &nbsp;&nbsp;";
            sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_shipping_child(" + id +
                ", 1," + boxid + "," + ctrlid + "," + n_left + "," + n_top + ")'>Display only Available boxes</a>";
        } else {
            sstr = sstr + "Below list display all the boxes &nbsp;&nbsp;";
            sstr = sstr + "<a style='color:#0000FF;' href='javascript:void(0);' onclick='display_shipping_child(" + id +
                ", 0," + boxid + "," + ctrlid + "," + n_left + "," + n_top + ")'>Display All Boxes</a>";
        }
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        var selectobject = document.getElementById("lightbox");
        //var n_left = f_getPosition(selectobject, 'Left');
        //var n_top  = f_getPosition(selectobject, 'Top');
        document.getElementById('light_shipping').style.display = 'block';
        document.getElementById('light_shipping').style.left = n_left - 200 + 'px';
        document.getElementById('light_shipping').style.top = n_top + 20 + 'px';

        document.getElementById("light_shipping").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_shipping").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
        xmlhttp.send();
    }
    //Function for new shipping tool
    function display_new_shipping_tool(id, flg, boxid) {
        //alert(boxid);
        var selectobject = document.getElementById("lightbox_new_shipping" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_shipping').style.display = 'block';
        document.getElementById('light_new_shipping').style.left = n_left + 'px';
        document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

        document.getElementById("light_new_shipping").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#C0CDDA'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none'; document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";
        sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
        sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr + "<select name='sort_sb_tool' id='sort_sb_tool' onChange='display_new_shipping_child(" + id + "," +
            this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
        if (flg == 5) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">All Boxes (No filter)</option></select>";
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "sales_shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg, true);
        xmlhttp.send();
    }

    function display_new_shipping_child(id, flg, boxid, n_left, n_top) {
        var flgs = document.getElementById("sort_sb_tool").value;
        var selectobject = document.getElementById("lightbox");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        //
        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#C0CDDA'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none';document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";
        sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
        sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr + "<select name='sort_sb_tool' id='sort_sb_tool' onChange='display_new_shipping_child(" + id + "," +
            this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flgs == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria</option><option value='2'";
        if (flgs == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
        if (flgs == 5) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">All Boxes (No filter)</option></select>";
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        if (flgs == 2 || flgs == 5) {
            sstr = sstr + "<table width='100%' border='0' cellspacing='1' cellpadding='1' ><tr>";
            sstr = sstr + "<td colspan='14'><font face='Arial, Helvetica, sans-serif' size='1'>";
            sstr = sstr + "<a href='#' onmouseover=" + String.fromCharCode(34) +
                "Tip('Program search for the neareast Box location based on the Ship To Zipcode. This is done based on the Zip code Latitude and longitude values stored in the database. ";

            if (flgs == 2) {
                sstr = sstr + "And shown boxes based on Matching Criteria & Available NOW.')";
            }
            if (flgs == 5) {
                sstr = sstr + "And shown All Boxes (No filter).')";
            }
            sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
        }

        var selectobject = document.getElementById("lightbox");
        //var n_left = f_getPosition(selectobject, 'Left');
        //var n_top  = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_shipping').style.display = 'block';
        document.getElementById('light_new_shipping').style.left = n_left + 'px';
        document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

        document.getElementById("light_new_shipping").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "sales_shipping_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flgs, true);
        xmlhttp.send();
    }
    //
    //Function for new shipping tool
    function display_new_shipping_tool_all(id, flg, boxid, viewflg) {
        //alert(boxid);
        var selectobject = document.getElementById("lightbox_new_shipping" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_shipping').style.display = 'block';
        document.getElementById('light_new_shipping').style.left = n_left + 'px';
        document.getElementById('light_new_shipping').style.top = n_top + 20 + 'px';

        document.getElementById("light_new_shipping").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#C0CDDA'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>SHIPPING BOX MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_shipping').style.display='none'; document.getElementById('fade').style.display='none'>Close</a>";
        sstr = sstr + "<br>";
        sstr = sstr + "Matching Criteria&nbsp;&nbsp;";
        sstr = sstr + "<br>";
        //if (flg == 0) {

        sstr = sstr + "<select name='sort_sb_tool' id='sort_sb_tool' onChange='display_new_shipping_child(" + id + "," +
            this.value + "," + boxid + "," + n_left + "," + n_top + ")'><option value='1'";

        if (flg == 1) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria</option><option value='2'";
        if (flg == 2) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">Matching Criteria & Available NOW</option><option value='5'";
        if (flg == 5) {
            sstr = sstr + " selected ";
        }
        sstr = sstr + ">All Boxes (No filter)</option></select>";
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_new_shipping").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "quote_request_shipping_match_all.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" +
            flg, true);
        xmlhttp.send();
    }
    //End new shipping tool
    //
    //

    //
    //------------------------------------------------------------------
    //Function for new supersacks tool
    //
    function display_new_supersacks(id, flg, boxid) {
        var selectobject = document.getElementById("lightbox_new_supersacks" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_supersacks').style.display = 'block';
        document.getElementById('light_new_supersacks').style.left = n_left + 'px';
        document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';

        document.getElementById("light_new_supersacks").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW SUPERSACKS MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert(xmlhttp.responseText);
                document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "supersacks_matching_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg,
            true);
        xmlhttp.send();
    }

    function display_new_supersacks_child(id, flg, boxid, n_left, n_top) {
        var flgs = document.getElementById("sort_g_tool").value;
        //alert(flgs);
        //
        var selectobject = document.getElementById("lightbox_new_supersacks");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW SUPERSACKS MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        //
        sstr = sstr + "</td>";
        sstr = sstr + "</tr>";
        sstr = sstr + "</table>";
        //
        var selectobject = document.getElementById("lightbox_new_supersacks");
        //var n_left = f_getPosition(selectobject, 'Left');
        //var n_top  = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_supersacks').style.display = 'block';
        document.getElementById('light_new_supersacks').style.left = n_left + 20 + 'px';
        document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';

        document.getElementById("light_new_supersacks").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "supersacks_matching_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flgs,
            true);
        xmlhttp.send();
    }
    //
    function display_new_supersacks_all(id, flg, boxid) {
        var selectobject = document.getElementById("lightbox_new_supersacks0");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_new_supersacks').style.display = 'block';
        document.getElementById('light_new_supersacks').style.left = n_left + 20 + 'px';
        document.getElementById('light_new_supersacks').style.top = n_top + 20 + 'px';
        document.getElementById('light_new_supersacks').style.height = 580 + 'px';

        document.getElementById("light_new_supersacks").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        var sstr = "";
        sstr = "<table width='100%' border='0' cellspacing='2' cellpadding='2' bgcolor='#E4E4E4'>";
        sstr = sstr + "<tr align='center'>";
        sstr = sstr + "<td bgcolor='#FF9900'>";
        sstr = sstr +
            "<font face='Arial, Helvetica, sans-serif' size='1' color='#333333'>NEW SUPERSACKS MATCHING TOOL</font>";
        sstr = sstr + "&nbsp;&nbsp;";
        sstr = sstr +
            "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_new_supersacks').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>";
        sstr = sstr + "</td></tr>";
        //sstr = sstr + String.fromCharCode(34) + " onmouseout='UnTip()'>How it Works</a></font></table>";
        sstr = sstr + "</table>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_new_supersacks").innerHTML = sstr + xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "supersacks_matching_tool.php?ID=" + id + "&boxid=" + boxid + "&display-allrec=" + flg,
            true);
        xmlhttp.send();
    }
    //End Supersacks Matching tool
    //--------------------------------------------------------------------
    //
    function display_add_invitem(companyID, salesflg) {
        var selectobject = document.getElementById("lightbox");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_addinventoryitem').style.display = 'block';
        document.getElementById('light_addinventoryitem').style.left = n_left + 20 + 'px';
        document.getElementById('light_addinventoryitem').style.top = n_top + 20 + 'px';
        document.getElementById('light_addinventoryitem').style.width = '1200px';

        document.getElementById("light_addinventoryitem").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_addinventoryitem").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "display_add_invitem.php?companyID=" + companyID + "&salesflg=y", true);
        xmlhttp.send();
    }

    function display_add_invitem_rescue(companyID, rescueflg) {
        var selectobject = document.getElementById("lightbox");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_addinventoryitem').style.display = 'block';
        document.getElementById('light_addinventoryitem').style.left = n_left + 20 + 'px';
        document.getElementById('light_addinventoryitem').style.top = n_top + 20 + 'px';

        document.getElementById("light_addinventoryitem").innerHTML =
            "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_addinventoryitem").innerHTML = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "display_add_invitem.php?companyID=" + companyID + "&rescueflg=y", true);
        xmlhttp.send();
    }

    function add_invitem(inv_id, companyID) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 1) {
                    updateQuotingcart(companyID);
                }
            }
        }
        xmlhttp.open("GET", "add_invitem.php?inv_id=" + inv_id + "&companyID=" + companyID, true);
        xmlhttp.send();
    }

    function updateQuotingcart(companyid) {
        document.getElementById('light_quota').style.display = 'none';
        //document.getElementById("quota_boxes_maindiv").style.display = "none";
        document.getElementById("quota_boxes_maindiv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?ID=" + companyid, true);

        xmlhttp.send();

    }

    function show_file_inviewer(filename, formtype) {
        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" +
            formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';
    }

    function show_file_inviewer_pos_in_iframe(filename, formtype, ctrlnm, iframectrl) {

        var selectobject = document.getElementById(iframectrl);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var getele = document.getElementById(iframectrl).contentWindow.document.getElementById(ctrlnm);

        n_left1 = f_getPosition(selectobject, 'Left');
        n_top1 = f_getPosition(selectobject, 'Top');
        popupbox = getele.getBoundingClientRect();

        n_top = popupbox.top;
        n_right = popupbox.right + window.pageXOffset;
        n_bottom = popupbox.bottom + window.pageYOffset;
        n_left = popupbox.left + window.pageXOffset;

        n_top_cnt = n_top1 + n_top;

        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').innerHTML='';document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" +
            formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
        document.getElementById('light').style.display = 'block';

        document.getElementById('light').style.left = n_left + 10 + 'px';
        document.getElementById('light').style.top = n_top_cnt + 10 + 'px';
    }

    function show_file_inviewer_pos(filename, formtype, ctrlnm) {

        var selectobject = document.getElementById(ctrlnm);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').innerHTML='';document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" +
            formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
        document.getElementById('light').style.display = 'block';

        document.getElementById('light').style.left = n_left + 10 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
    }

    function show_data_inviewer(urlstr, formtype, ctrlnm) {
        var selectobject = document.getElementById(ctrlnm);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';

                document.getElementById('light').style.left = n_left + 10 + 'px';
                document.getElementById('light').style.top = n_top + 10 + 'px';
            }
        }

        xmlhttp.open("POST", urlstr, true);

        xmlhttp.send();

    }
    //

    function show_quote_req_inviewer_pos(companyid, ctrlnm) {

        var selectobject = document.getElementById(ctrlnm);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // alert(ctrlnm);
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';

                document.getElementById('light').style.left = n_left + 10 + 'px';
                document.getElementById('light').style.top = n_top + 10 + 'px';
            }
        }

        xmlhttp.open("GET", "quote_requested_add.php?quoteid=" + ctrlnm + "&companyid=" + companyid, true);

        xmlhttp.send();
    }
    //
    function deny_quote_req(companyid, ctrlnm) {

        var selectobject = document.getElementById(ctrlnm);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // alert(ctrlnm);
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
                document.getElementById('light').style.width = 430 + 'px';
                document.getElementById('light').style.height = 180 + 'px';

                document.getElementById('light').style.left = n_left + 10 + 'px';
                document.getElementById('light').style.top = n_top + 10 + 'px';
            }
        }

        xmlhttp.open("GET", "quote_deny_reason.php?quoteid=" + ctrlnm + "&companyid=" + companyid, true);

        xmlhttp.send();
    }
    //
    function show_deny_info(ctrlnm, companyid) {

        var selectobject = document.getElementById("quotesdeny" + ctrlnm);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // alert(ctrlnm);
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
                document.getElementById('light').style.width = 430 + 'px';
                document.getElementById('light').style.height = 180 + 'px';

                document.getElementById('light').style.left = n_left + 10 + 'px';
                document.getElementById('light').style.top = n_top + 10 + 'px';
            }
        }

        xmlhttp.open("GET", "quote_deny_reason_info.php?quoteid=" + ctrlnm + "&companyid=" + companyid, true);

        xmlhttp.send();
    }
    //
    //


    function edit_quote(id, quote_id) {
        document.location = "viewCompany_new_test.php?ID=" + id + "&quote_id=" + quote_id;
    }

    $(document).keydown(function(e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            $('#light_quota').hide();
        }
    })

    $(document).keydown(function(e) {
        // ESCAPE key pressed
        if (e.keyCode == 27) {
            $('#light_gaylord').hide();
        }
    });

    function addFreightfun(companyid) {
        //var val= document.getElementById("update").value = "yes";	
        document.getElementById('light_quota').style.display = 'none';
        //document.getElementById("quota_boxes_maindiv").style.display = "none";
        document.getElementById("quota_boxes_maindiv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?ID=" + companyid + "&addFreight=yes", true);

        xmlhttp.send();

    }

    function update_quotnew(companyid, itemID, lengthInch, lengthNumerator, lengthDenominator, widthInch,
        widthNumerator,
        widthDenominator, depthInch, depthNumerator, depthDenominator, description, burstECT, item, boxNumber, newUsed,
        salePrice,
        cost, vendor, taxable, quantity, quantity_per_pallet, shipfinalvendor, shipfinal, ctrlidcart) {
        //var val= document.getElementById("update").value = "yes";	
        document.getElementById('light_quota').style.display = 'none';
        //document.getElementById("quota_boxes_maindiv").style.display = "none";
        document.getElementById("quota_boxes_maindiv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
                //alert(xmlhttp.responseText);
            }
        }

        var lengthInch_min = "";
        var lengthInch_max = "";
        var widthInch_min = "";
        var widthInch_max = "";
        var depthInch_min = "";
        var depthInch_max = "";

        var lengthInch = "";
        var lengthNumerator = "";
        var lengthDenominator = "";
        var widthInch = "";
        var widthNumerator = "";
        var widthDenominator = "";
        var depthInch = "";
        var depthNumerator = "";
        var depthDenominator = "";

        if (document.getElementById("uniform_mixed_load").value == "Mixed") {
            var lengthInch_min = encodeURIComponent(document.getElementById("lengthInch_min").value);
            var lengthInch_max = encodeURIComponent(document.getElementById("lengthInch_max").value);
            var widthInch_min = encodeURIComponent(document.getElementById("widthInch_min").value);
            var widthInch_max = encodeURIComponent(document.getElementById("widthInch_max").value);
            var depthInch_min = encodeURIComponent(document.getElementById("depthInch_min").value);
            var depthInch_max = encodeURIComponent(document.getElementById("depthInch_max").value);
        } else {
            var lengthInch = encodeURIComponent(document.getElementById("lengthInch").value);
            var lengthNumerator = encodeURIComponent(document.getElementById("lengthNumerator").value);
            var lengthDenominator = encodeURIComponent(document.getElementById("lengthDenominator").value);
            var widthInch = encodeURIComponent(document.getElementById("widthInch").value);
            var widthNumerator = encodeURIComponent(document.getElementById("widthNumerator").value);
            var widthDenominator = encodeURIComponent(document.getElementById("widthDenominator").value);
            var depthInch = encodeURIComponent(document.getElementById("depthInch").value);
            var depthNumerator = encodeURIComponent(document.getElementById("depthNumerator").value);
            var depthDenominator = encodeURIComponent(document.getElementById("depthDenominator").value);
        }

        var description = encodeURIComponent(document.getElementById("description").value);
        var burstECT = 0;
        var item = encodeURIComponent(document.getElementById("item").value);
        var boxNumber = 0;
        var newUsed = encodeURIComponent(document.getElementById("newUsed").value);
        var salePrice = encodeURIComponent(document.getElementById("salePrice" + ctrlidcart).value);
        var cost = 0;
        var vendor = 0;
        var taxable = encodeURIComponent(document.getElementById("Taxable").value);
        var quantity = encodeURIComponent(document.getElementById("quantitycart" + ctrlidcart).value);
        var quantity_per_pallet = 0;
        /*var shipquote1 = encodeURIComponent(document.getElementById("shipquote1").value);
        var shipquote1vendor = encodeURIComponent(document.getElementById("shipquote1vendor").value);
        var shipquote2 = encodeURIComponent(document.getElementById("shipquote2").value);
        var shipquote2vendor = encodeURIComponent(document.getElementById("shipquote2vendor").value);
        var shipquote3 = encodeURIComponent(document.getElementById("shipquote3").value);
        var shipquote3vendor = encodeURIComponent(document.getElementById("shipquote3vendor").value);
        */
        var shipfinalvendor = encodeURIComponent(document.getElementById("shipfinalvendor").value);
        var shipfinal = encodeURIComponent(document.getElementById("shipfinal" + ctrlidcart).value);

        var tmpvar = ("ID=" + companyid + "&itemID=" + itemID + "&lengthInch=" + lengthInch + "&lengthNumerator=" +
            lengthNumerator + "&lengthDenominator=" + lengthDenominator + "&widthInch=" + widthInch +
            "&widthNumerator=" + widthNumerator + "&widthDenominator=" + widthDenominator + "&depthInch=" +
            depthInch + "&depthNumerator=" + depthNumerator + "&depthDenominator=" + depthDenominator +
            "&description=" + description + "&burstECT=" + burstECT + "&item=" + item + "&boxNumber=" + boxNumber +
            "&newUsed=" + newUsed + "&salePrice=" + salePrice + "&cost=" + cost + "&vendor=" + vendor +
            "&taxable=" + taxable + "&quantity=" + quantity + "&quantity_per_pallet=" + quantity_per_pallet +
            "&shipfinalvendor=" + shipfinalvendor + "&shipfinal=" + shipfinal + "&lengthInch_min=" +
            lengthInch_min + "&lengthInch_max=" + lengthInch_max + "&widthInch_min=" + widthInch_min +
            "&widthInch_max=" + widthInch_max + "&depthInch_min=" + depthInch_min + "&depthInch_max=" +
            depthInch_max + "&update=yes");

        xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?" + tmpvar, true);

        xmlhttp.send();
    }


    function createquote(companyID) {

        //document.getElementById("quota_boxes_maindiv").style.display = "none";
        document.getElementById("quota_boxes_maindiv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;

            }
        }

        var quoteType = document.getElementById("quoteType").value;
        var poNumber = document.getElementById("poNumber").value;
        var terms = document.getElementById("terms").value;
        var free_shipping = document.getElementById("free_shipping").value;
        var rep = document.getElementById("rep").value;
        var TBD = document.getElementById("TBD").value;
        var shipdate = document.getElementById("date_of_activity").value;
        var via = document.getElementById("via").value;
        var notes = document.getElementById("notes").value;

        //var qstatus = document.getElementById("qstatus").value;
        //var quoteDate = document.getElementById("quoteDate").value;

        /*alert("box_quoting_mrg_mysqli.php?ID="+companyID+"&quoteType="+quoteType+"&poNumber="+poNumber+
        "&terms='"+terms+"'&free_shipping="+free_shipping+"&rep="+rep+"&TBD="+TBD+"&shipdate="+shipdate+
        "&via='"+via+"'&notes='"+notes+"'&create_quote=yes");*/

        //xmlhttp.open("POST","box_quoting_mrg_mysqli.php?ID="+companyID+"&quoteType="+quoteType+"&poNumber="+poNumber+"&terms="+terms+"&free_shipping="+free_shipping+"&rep="+rep+"&TBD="+TBD+"&shipdate="+shipdate+"&via="+via+"&notes="+notes+"&selectItem="+ selectItem + "&create_quote=yes",true);
        //xmlhttp.send();
    }

    function deleterecord(companyid, inventoryid) {
        var val = document.getElementById("delete").value = "yes";
        document.getElementById('light_quota').style.display = 'none';
        //document.getElementById("quota_boxes_maindiv").style.display = "none";
        document.getElementById("quota_boxes_maindiv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?ID=" + companyid + "&inventoryID=" + inventoryid +
            "&delete=yes", true);
        xmlhttp.send();
    }


    function duplicaterecord(companyid, inventoryid) {
        var val = document.getElementById("duplicate").value = "yes";
        document.getElementById('light_quota').style.display = 'none';
        //document.getElementById("quota_boxes_maindiv").style.display = "none";
        document.getElementById("quota_boxes_maindiv").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("quota_boxes_maindiv").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("POST", "box_quoting_mrg_mysqli.php?ID=" + companyid + "&inventoryID=" + inventoryid +
            "&duplicate=yes", true);
        xmlhttp.send();
    }

    function calcualteprofitloss(form, ctrlidcart) {
        if (document.getElementById("item_delivery_flg").value == "no") {
            qty = document.getElementById("quantitycart" + ctrlidcart).value;
            cost = document.getElementById("cost" + ctrlidcart).value;
            ship_quote = document.getElementById("shipfinal" + ctrlidcart).value;
            salePrice = parseFloat(document.getElementById("salePrice" + ctrlidcart).value.replace(/,/g, ''));
            minfob = document.getElementById("minfob" + ctrlidcart).value;

            val_1 = (qty * salePrice);
            val_2 = (qty * cost);

            cal = val_1 - val_2;
            //alert(val_1);

            finalcal = cal - ship_quote;

            if (qty != "" && minfob != "" && ship_quote != "") {
                min_delv_cost_tmp = ship_quote / qty;
                min_delv_cost = parseFloat(minfob) + min_delv_cost_tmp;
                min_delv_cost = min_delv_cost.toFixed(2);
                salePrice = parseFloat(salePrice);
                if (salePrice >= min_delv_cost) {
                    document.getElementById("min_delv_cost" + ctrlidcart).innerHTML =
                        "<font face='Arial, Helvetica, sans-serif' size='1' color='green'>$" + min_delv_cost +
                        "</font>";
                } else {
                    document.getElementById("min_delv_cost" + ctrlidcart).innerHTML =
                        "<font face='Arial, Helvetica, sans-serif' size='1' color='red'>$" + min_delv_cost + "</font>";
                }
            }

            if (qty != "" && salePrice != "" && ship_quote != "") {
                document.getElementById("profit" + ctrlidcart).value = finalcal.toFixed(2);
            }

            if (val_1 != "" && document.getElementById("profit" + ctrlidcart).value != "") {
                margin_val = (document.getElementById("profit" + ctrlidcart).value / (qty * salePrice)).toFixed(2);
                document.getElementById("margin" + ctrlidcart).value = margin_val * 100;
            }

            //30% Margin Target
            ship_margin = 0.3 * ship_quote;
            qty_margin = 0.7 * qty;
            //alert(ship_margin + " + " + qty_margin + " + " + cost);
            margin_target30 = (ship_margin / qty_margin) + (cost / 0.7) + (ship_quote / qty);
            //alert(margin_target30);
            margin_target30 = margin_target30.toFixed(2);
            salePrice = parseFloat(salePrice);

            if (margin_target30 <= min_delv_cost) {
                if (salePrice >= min_delv_cost) {
                    document.getElementById("margin_target_cost" + ctrlidcart).innerHTML =
                        "<font face='Arial, Helvetica, sans-serif' size='1' color='green'>See Min FOB Target</font>";
                } else {
                    document.getElementById("margin_target_cost" + ctrlidcart).innerHTML =
                        "<font face='Arial, Helvetica, sans-serif' size='1' color='red'>See Min FOB Target</font>";
                }

            } else {
                if (salePrice >= margin_target30) {
                    document.getElementById("margin_target_cost" + ctrlidcart).innerHTML =
                        "<font face='Arial, Helvetica, sans-serif' size='1' color='green'>$" + margin_target30 +
                        "</font>";
                } else {
                    document.getElementById("margin_target_cost" + ctrlidcart).innerHTML =
                        "<font face='Arial, Helvetica, sans-serif' size='1' color='red'>$" + margin_target30 +
                        "</font>";
                }
            }
        }
    }

    function email_body(temp) {
        if (document.getElementById('email_div' + temp).style.display == 'none') {
            document.getElementById('email_div' + temp).style.display = 'block';
        } else if (document.getElementById('email_div' + temp).style.display == 'block') {
            document.getElementById('email_div' + temp).style.display = 'none';
        }
    }

    function isNumberKey_neg(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            if (charCode == 46 || charCode == 44 || charCode == 45) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    function freight_cal(b2bid) {
        //alert(b2bid);

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("ifr").contentDocument.write(xmlhttp.responseText);
            }
        }

        xmlhttp.open("POST", "freight_calculator.php?b2bid=" + b2bid, true);

        xmlhttp.send();

    }

    function isSpace(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode == 32)
            return false;

        return true;
    }
    // added for table quote generated in quoting Tab


    function show_file_inviewer_pos_in_iframe(filename, formtype, ctrlnm, iframectrl) {

        var selectobject = document.getElementById(iframectrl);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var getele = document.getElementById(iframectrl).contentWindow.document.getElementById(ctrlnm);

        n_left1 = f_getPosition(selectobject, 'Left');
        n_top1 = f_getPosition(selectobject, 'Top');
        popupbox = getele.getBoundingClientRect();

        n_top = popupbox.top;
        n_right = popupbox.right + window.pageXOffset;
        n_bottom = popupbox.bottom + window.pageYOffset;
        n_left = popupbox.left + window.pageXOffset;

        n_top_cnt = n_top1 + n_top;

        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').innerHTML='';document.getElementById('light').style.display='none'>Close</a> &nbsp;<center>" +
            formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
        document.getElementById('light').style.display = 'block';

        document.getElementById('light').style.left = n_left + 10 + 'px';
        document.getElementById('light').style.top = n_top_cnt + 10 + 'px';
    }

    //
    function show_quote_req_inviewer_pos_in_iframe(companyid, ctrlnm, iframectrl) {

        var selectobject = document.getElementById(iframectrl);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var getele = document.getElementById(iframectrl).contentWindow.document.getElementById(ctrlnm);

        n_left1 = f_getPosition(selectobject, 'Left');
        n_top1 = f_getPosition(selectobject, 'Top');
        popupbox = getele.getBoundingClientRect();

        n_top = popupbox.top;
        n_right = popupbox.right + window.pageXOffset;
        n_bottom = popupbox.bottom + window.pageYOffset;
        n_left = popupbox.left + window.pageXOffset;

        n_top_cnt = n_top1 + n_top;


        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';

                document.getElementById('light').style.left = n_left - 150 + 'px';
                document.getElementById('light').style.top = n_top_cnt + 15 + 'px';
            }
        }

        xmlhttp.open("GET", "quote_requested_add.php?quoteid=" + ctrlnm + "&companyid=" + companyid, true);

        xmlhttp.send();
    }
    //
    function reminder_popup_newtest_in_iframe(unq_quote_id, quote_id, iframectrl) {
        var selectedText, selectobject, rec_type, skillsSelect, n_left, n_top;

        var selifra = document.getElementById(iframectrl);
        var n_left = f_getPosition(selifra, 'Left');
        var n_top = f_getPosition(selifra, 'Top');

        var skillsSelect = document.getElementById(iframectrl).contentWindow.document.getElementById("quote_status" +
            quote_id);

        n_left1 = f_getPosition(selifra, 'Left');
        n_top1 = f_getPosition(selifra, 'Top');
        popupbox = skillsSelect.getBoundingClientRect();

        n_top = popupbox.top;
        n_right = popupbox.right + window.pageXOffset;
        n_bottom = popupbox.bottom + window.pageYOffset;
        n_left = popupbox.left + window.pageXOffset;

        n_top_cnt = n_top1 + n_top;


        selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;

        selectobject = document.getElementById(iframectrl).contentWindow.document.getElementById("B1" + "_" + quote_id);
        id = document.getElementById(iframectrl).contentWindow.document.getElementById("companyID").value;
        rec_type = document.getElementById(iframectrl).contentWindow.document.getElementById("rec_type").value;

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (selectedText == 10) {
                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';
                    document.getElementById('light_reminder').style.left = n_left + 'px';
                    document.getElementById('light_reminder').style.top = n_top_cnt + 20 + 'px';
                } else if (selectedText == 8) {
                    document.getElementById("light_reminder").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_reminder').style.display = 'block';
                    document.getElementById('light_reminder').style.width = '450';
                    document.getElementById('light_reminder').style.height = '800';
                    document.getElementById('light_reminder').style.left = n_left + 'px';
                    document.getElementById('light_reminder').style.top = n_top_cnt + 20 + 'px';
                } else {
                    alert("Data Updated");
                }
            }
        }

        if (selectedText == 10) {
            xmlhttp.open("POST", "fckeditor_reminder_email.php?unq_quote_id=" + unq_quote_id + "&quote_id=" + quote_id +
                "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type, true);
        } else if (selectedText == 8) {
            xmlhttp.open("POST", "createpo_from_quote.php?unq_quote_id=" + unq_quote_id + "&quote_id_org=" + quote_id +
                "&quote_status=" + selectedText + "&companyid=" + id + "&B1=B1" + "&rec_type=" + rec_type, true);

        } else {
            xmlhttp.open("POST", "updateQuoteStatus_mrg.php?quote_id=" + quote_id + "&quote_status=" + selectedText +
                "&B1=B1", true);
        }

        xmlhttp.send();


    }

    //
    function show_quote_in_iframe(id, quote_id, iframectrl) {
        var quoteType_val, selectobject, skillsSelect, selifra, n_left, n_top, n_left1, n_top1, n_top_cnt, popupbox;
        selifra = document.getElementById(iframectrl);
        n_left = f_getPosition(selifra, 'Left');
        n_top = f_getPosition(selifra, 'Top');

        skillsSelect = document.getElementById(iframectrl).contentWindow.document.getElementById("dup_quote" +
            quote_id);

        n_left1 = f_getPosition(selifra, 'Left');
        n_top1 = f_getPosition(selifra, 'Top');
        popupbox = skillsSelect.getBoundingClientRect();

        n_top = popupbox.top;
        n_right = popupbox.right + window.pageXOffset;
        n_bottom = popupbox.bottom + window.pageYOffset;
        n_left = popupbox.left + window.pageXOffset;

        n_top_cnt = n_top1 + n_top;

        quoteType_val = document.getElementById(iframectrl).contentWindow.document.getElementById("dup_quoteType" +
            quote_id).value;
        if (quoteType_val == "PO") {
            var quote_billto_add_val = "";
            var quote_sellto_add_val = "";

        } else {
            var quote_billto_add_val = document.getElementById('quote_billto_add').value;
            var quote_sellto_add_val = document.getElementById('quote_sellto_add').value;
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
                document.getElementById('light').style.width = 350 + 'px';
                document.getElementById('light').style.height = 250 + 'px';
                document.getElementById('light').style.left = n_left + 10 + 'px';
                document.getElementById('light').style.top = n_top_cnt + 10 + 'px';
            }
        }

        xmlhttp.open("GET", "dup_quote_shipdate.php?companyID=" + id + "&dup_quote_id=" + quote_id + "&quoteType=" +
            quoteType_val + "&quote_billto_add=" + quote_billto_add_val + "&quote_sellto_add=" +
            quote_sellto_add_val, true);

        xmlhttp.send();
    } //End function update ship date

    function show_reply_in_iframe(tmpcnt, iframectrl) {
        var selectobject = document.getElementById(iframectrl);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');

        var getele = document.getElementById(iframectrl).contentWindow.document.getElementById("reminder_details" +
            tmpcnt);

        n_left1 = f_getPosition(selectobject, 'Left');
        n_top1 = f_getPosition(selectobject, 'Top');
        popupbox = getele.getBoundingClientRect();

        n_top = popupbox.top;
        n_right = popupbox.right + window.pageXOffset;
        n_bottom = popupbox.bottom + window.pageYOffset;
        n_left = popupbox.left + window.pageXOffset;

        n_top_cnt = n_top1 + n_top;



        document.getElementById("light_details").innerHTML = document.getElementById(iframectrl).contentWindow.document
            .getElementById("remider_rep_popup" + tmpcnt).innerHTML;
        document.getElementById('light_details').style.display = 'block';
        document.getElementById('light_details').style.left = n_left + 'px';
        document.getElementById('light_details').style.top = n_top_cnt + 20 + 'px';
    }


    function email_body_in_iframe(temp) {
        if (document.getElementById("iframe_tbl_generate").contentWindow.document.getElementById('email_div' + temp)
            .style.display == 'none') {
            document.getElementById("iframe_tbl_generate").contentWindow.document.getElementById('email_div' + temp)
                .style.display = 'block';
        } else if (document.getElementById("iframe_tbl_generate").contentWindow.document.getElementById('email_div' +
                temp).style.display == 'block') {
            document.getElementById("iframe_tbl_generate").contentWindow.document.getElementById('email_div' + temp)
                .style.display = 'none';
        }
    }
    </script>

</head>

<!-- style="background-color:#f4f9fd;" -->

<body>

    <script type="text/javascript" src="wz_tooltip.js"></script>
    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>
    <div id="light_details" class="white_content_details"></div>
    <div id="light_gaylord" class="white_content_gaylord_new"></div>
    <div id="light_gaylord_new" class="white_content_gaylord_new"></div>
    <!--For new Gaylord tool-->
    <div id="light_gaylord_new1" class="white_content_gaylord_new1"></div>
    <div id="light_pallets_new" class="white_content_gaylord_new1"></div>

    <!--\\\\\\\\\\\\-->
    <div id="light_boxupd" class="white_content_gaylord_new"></div>

    <div id="popup_window" class="white_content_gaylord_new"></div>

    <div id="light_shipping" class="white_content_gaylord_new"></div>
    <div id="light_new_shipping" class="white_content_gaylord_new"></div>
    <div id="light_new_supersacks" class="white_content_gaylord_new"></div>
    <div id="light_new_pal" class="white_content_gaylord_new"></div>
    <div id="light_addinventoryitem" class="white_content_gaylord_new"></div>
    <div id="light_quota" class="white_content_quota"></div>
    <div id="light_reminder" class="white_content_reminder"></div>
    <div id="sendemailtrfacc" class="white_content_reminder"></div>

    <?php include("inc/header.php"); ?>
    <br>
    <div class="main_data_css">

        <?php
        //echo Date("m/d/Y H:i:s") . "<br>";

        if (isset($_REQUEST["quoteid"])) {
            $quote_filename = "";
            $sql = "SELECT companyID, filename FROM quote WHERE ID = " . $_REQUEST["quoteid"];
            db_b2b();
            $result = db_query($sql);
            if ($myrowsel = array_shift($result)) {
                $quote_filename = $myrowsel["filename"];
            }

            echo "<h4>Quote Search result &nbsp; <a target='_blank' href='quotes/" . $quote_filename . "'>" . $quote_filename . "</a></h4>";
        } ?>

        <table width="100%">

            <tr>
                <td align="left" valign="middle">
                    <?php

                    $status = "";
                    $freightupdates = 1;
                    $negotiated_rate = "";
                    $qry_1 = "Select company,active,haveNeed, loopid, on_hold, freightupdates from companyInfo Where ID = " . $_REQUEST["ID"];
                    //echo $qry_1;
                    db_b2b();
                    $dt_view_1 = db_query($qry_1);
                    if (tep_db_num_rows($dt_view_1) > 0) {
                        while ($rows = array_shift($dt_view_1)) {
                            $company = $rows['company'];
                            $active_1 = $rows["active"];
                            $status = $rows["haveNeed"];
                            $freightupdates = $rows["freightupdates"];

                    ?>

                    <?php if ($status == "Need Boxes") { ?>
                    <a
                        href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=summary&rec_type=<?php echo isset($rec_type); ?>">
                        <font face="Arial, Helvetica, sans-serif" size="5" color='#00008b'><b>
                                <?php echo $company; ?>
                            </b></font>
                    </a>
                    <?php } else { ?>
                    <a
                        href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=summary&rec_type=<?php echo isset($rec_type); ?>">
                        <font face="Arial, Helvetica, sans-serif" size="5" color="#3EA99F"><b>
                                <?php echo $company; ?>
                            </b></font>
                    </a>
                    <?php } ?>

                    <?php if ($active_1 == 0) { ?>
                    <Font Face='Arial, Helvetica, sans-serif' size='5' color="red"><b>&nbsp;INACTIVE</b>
                        <font>
                            <?php }

                                if ($rows["on_hold"] == 1) { ?>
                            <Font Face='Arial, Helvetica, sans-serif' size='5' color="red"><b>&nbsp;ON HOLD</b>
                                <font>
                                    <?php }

                                        if ($duplicate_chk > 1) { ?>
                                    <Font Face='Arial, Helvetica, sans-serif' size='2' color="red"><b>&nbsp;Something is
                                            wrong with this Company Record, please inform Zac Fratkin or Marina Pianucci
                                            via email</b>
                                        <font>
                                            <?php }

                                                    if ($rows['loopid'] > 0) {

                                                        if ($status == "Need Boxes") { ?>
                                            <Font Face='Arial, Helvetica, sans-serif' color='#625D5D' size='5'>
                                                <b>&nbsp;[Sales]</b>
                                                <font>
                                                    <?php } else if ($status == "Water") { ?>
                                                    <Font Face='Arial, Helvetica, sans-serif' color='#625D5D' size='5'>
                                                        <b>&nbsp;[Water]</b>
                                                        <font>
                                                            <?php } else { ?>
                                                            <Font Face='Arial, Helvetica, sans-serif' color='#625D5D'
                                                                size='5'><b>&nbsp;[Purchasing]</b>
                                                                <font>
                                                                    <?php }
                                                                        } else {
                                                                            if ($status == "Need Boxes") { ?>
                                                                    <Font Face='Arial, Helvetica, sans-serif'
                                                                        color='#625D5D' size='5'><b>&nbsp;[Sales]</b>
                                                                        <font>
                                                                            <?php } else if ($status == "Water") { ?>
                                                                            <Font Face='Arial, Helvetica, sans-serif'
                                                                                color='#625D5D' size='5'>
                                                                                <b>&nbsp;[Water]</b>
                                                                                <font>
                                                                                    <?php } else { ?>
                                                                                    <Font
                                                                                        Face='Arial, Helvetica, sans-serif'
                                                                                        color='#625D5D' size='5'>
                                                                                        <b>&nbsp;[Purchasing]</b>
                                                                                        <font>
                                                                                            <?php }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        $qry_2 = "Select company_name,active,rec_type from loop_warehouse where id = " . $_REQUEST["ID"];
                                                                                        //echo $qry_2;
                                                                                        db();
                                                                                        $dt_view_2 = db_query($qry_2);
                                                                                        while ($myrow = array_shift($dt_view_2)) {
                                                                                            $status = '';
                                                                                            $active_2 = $myrow["active"];
                                                                                            $company_name = $myrow['company_name'];
                                                                                            $rec_type = $myrow['rec_type'];
                                                                                                    ?>

                                                                                            <?php if ($rec_type == "Supplier") { ?>
                                                                                            <a
                                                                                                href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=summary&rec_type=<?php echo $rec_type; ?>">
                                                                                                <font
                                                                                                    face="Arial, Helvetica, sans-serif"
                                                                                                    size="5"
                                                                                                    color="#00008b"><b>
                                                                                                        <?php echo $company_name; ?>
                                                                                                    </b>
                                                                                            </a>
                                                                                        </font>
                                                                                        <?php } else { ?>
                                                                                        <a
                                                                                            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=summary&rec_type=<?php echo $rec_type; ?>">
                                                                                            <font
                                                                                                face="Arial, Helvetica, sans-serif"
                                                                                                size="5"
                                                                                                color="#3EA99F"><b>
                                                                                                    <?php echo $company_name; ?>
                                                                                                </b>
                                                                                        </a>
                                                                                    </font>
                                                                                    <?php } ?>


                                                                                    <?php if ($active_2 == 0) { ?>
                                                                                    <Font
                                                                                        Face='Arial, Helvetica, sans-serif'
                                                                                        size='5' color="red">
                                                                                        <b>&nbsp;INACTIVE</b>
                                                                                        <font>
                                                                                            <?php }

                                                                                                    if ($rec_type == "Supplier") { ?>
                                                                                            <Font
                                                                                                Face='Arial, Helvetica, sans-serif'
                                                                                                color='#625D5D'
                                                                                                size='5'>
                                                                                                <b>&nbsp;[Sales]</b>
                                                                                                <font>
                                                                                                    <?php } else { ?>
                                                                                                    <Font
                                                                                                        Face='Arial, Helvetica, sans-serif'
                                                                                                        color='#625D5D'
                                                                                                        size='5'>
                                                                                                        <b>&nbsp;[Purchasing]</b>
                                                                                                        <font>
                                                                                                            <?php }
                                                                                                        }
                                                                                                    }
                                                                                                                ?>
                </td>
            </tr>
        </table>

        <?php

        //$b2bid = $_REQUEST["ID"];
        $parent_child_flg = "";
        $recfound = "no";
        $addl_select_crit = "AND bs_status != 'Neither' ORDER BY warehouse_name";
        $sql = "SELECT * FROM loop_warehouse WHERE b2bid=" . $_REQUEST["ID"] . " $addl_select_crit ";
        //echo $sql."<br>";
        db();
        $result = db_query($sql);

        $numofrows = 0;
        if ($result) {
            $numofrows = tep_db_num_rows($result);
        } else {
            //die(mysql_error());
        }

        //echo $numofrows;
        if ($numofrows > 0) {
            $loopid = 0;
            $recfound = "no";
            if ($myrowsel = array_shift($result)) {
                $recfound = "yes";
                $loopid = $myrowsel["id"];
                $b2bid = $myrowsel["b2bid"];
                //echo $b2bid."**";
                $company_name = $myrowsel["company_name"];
                $company_address1 = $myrowsel["company_address1"];
                $company_address2 = $myrowsel["company_address2"];
                $company_city = $myrowsel["company_city"];
                $company_state = $myrowsel["company_state"];
                $company_zip = $myrowsel["company_zip"];
                $company_phone = $myrowsel["company_phone"];
                $company_email = $myrowsel["company_email"];

                $acc_email = $myrowsel["accounting_email"];
                $acc_contact = $myrowsel["accounting_contact"];
                $acc_phone = $myrowsel["accounting_phone"];

                $company_terms = $myrowsel["company_terms"];
                $company_contact = $myrowsel["company_contact"];
                $warehouse_name = $myrowsel["warehouse_name"];
                $warehouse_address1 = $myrowsel["warehouse_address1"];
                $warehouse_address2 = $myrowsel["warehouse_address2"];
                $warehouse_city = $myrowsel["warehouse_city"];
                $warehouse_state = $myrowsel["warehouse_state"];
                $warehouse_zip = $myrowsel["warehouse_zip"];
                $warehouse_contact = $myrowsel["warehouse_contact"];
                $warehouse_contact_phone = $myrowsel["warehouse_contact_phone"];
                $warehouse_contact_email = $myrowsel["warehouse_contact_email"];
                $warehouse_manager = $myrowsel["warehouse_manager"];
                $warehouse_manager_phone = $myrowsel["warehouse_manager_phone"];
                $warehouse_manager_email = $myrowsel["warehouse_manager_email"];
                $dock_details = $myrowsel["dock_details"];
                $warehouse_notes = $myrowsel["warehouse_notes"];
                $rec_type = $myrowsel["rec_type"];
                $bs_status = $myrowsel["bs_status"];
                $last_activity = $myrowsel["last_activity"];
                $other1 = $myrowsel["other1"];
                $other2 = $myrowsel["other2"];
                $other3 = $myrowsel["other3"];

                $overall_revenue_comp = $myrowsel["overall_revenue_comp"];
                $noof_location = $myrowsel["noof_location"];
            }
        } else {
            $sql = "SELECT * FROM loop_warehouse WHERE id=" . $_REQUEST["ID"] . " $addl_select_crit ";
            db();
            $result = db_query($sql);
            if ($result) {
                $numofrows = tep_db_num_rows($result);
            } else {
                //die(mysql_error());
            }
            if ($numofrows > 0) {
                $loopid = 0;
                $recfound = "no";
                if ($myrowsel = array_shift($result)) {
                    $recfound = "yes";
                    //$loopid = $myrowsel["id"];

                    $b2bid = $myrowsel["id"];
                    //echo $b2bid."$$";
                    $company_name = $myrowsel["company_name"];
                    $company_address1 = $myrowsel["company_address1"];
                    $company_address2 = $myrowsel["company_address2"];
                    $company_city = $myrowsel["company_city"];
                    $company_state = $myrowsel["company_state"];
                    $company_zip = $myrowsel["company_zip"];
                    $company_phone = $myrowsel["company_phone"];
                    $company_email = $myrowsel["company_email"];

                    $acc_email = $myrowsel["accounting_email"];
                    $acc_contact = $myrowsel["accounting_contact"];
                    $acc_phone = $myrowsel["accounting_phone"];

                    $company_terms = $myrowsel["company_terms"];
                    $company_contact = $myrowsel["company_contact"];
                    $warehouse_name = $myrowsel["warehouse_name"];
                    $warehouse_address1 = $myrowsel["warehouse_address1"];
                    $warehouse_address2 = $myrowsel["warehouse_address2"];
                    $warehouse_city = $myrowsel["warehouse_city"];
                    $warehouse_state = $myrowsel["warehouse_state"];
                    $warehouse_zip = $myrowsel["warehouse_zip"];
                    $warehouse_contact = $myrowsel["warehouse_contact"];
                    $warehouse_contact_phone = $myrowsel["warehouse_contact_phone"];
                    $warehouse_contact_email = $myrowsel["warehouse_contact_email"];
                    $warehouse_manager = $myrowsel["warehouse_manager"];
                    $warehouse_manager_phone = $myrowsel["warehouse_manager_phone"];
                    $warehouse_manager_email = $myrowsel["warehouse_manager_email"];
                    $dock_details = $myrowsel["dock_details"];
                    $warehouse_notes = $myrowsel["warehouse_notes"];
                    $rec_type = $myrowsel["rec_type"];
                    $bs_status = $myrowsel["bs_status"];
                    $last_activity = $myrowsel["last_activity"];
                    $other1 = $myrowsel["other1"];
                    $other2 = $myrowsel["other2"];
                    $other3 = $myrowsel["other3"];

                    $overall_revenue_comp = $myrowsel["overall_revenue_comp"];
                    $noof_location = $myrowsel["noof_location"];
                }
            } else {
                $b2bid = 0;
                $sql = "SELECT ID, parent_child FROM companyInfo Where ID =" . $_REQUEST["ID"];
                db_b2b();
                $result_tmp = db_query($sql);
                while ($myrowsel_tmp = array_shift($result_tmp)) {
                    $b2bid = $_REQUEST["ID"];
                }
            }
        }
        $id = isset($loopid);

        $haveNeed_flg = "";
        $loopidfromcomp = 0;
        if (isset($b2bid) > 0) {
            $parent_child_compid = 0;
            if (isset($b2bid) > 0) {
                $sql = "SELECT parent_child,parent_comp_id, haveNeed, loopid FROM companyInfo Where ID =" . isset($b2bid);
                db_b2b();
                $result_tmp = db_query($sql);
                while ($myrowsel_tmp = array_shift($result_tmp)) {
                    $parent_child_flg = $myrowsel_tmp["parent_child"];
                    $parent_child_compid = $myrowsel_tmp["parent_comp_id"];
                    $haveNeed_flg = $myrowsel_tmp["haveNeed"];
                    $loopidfromcomp = $myrowsel_tmp["loopid"];
                }
            }

            //Check the Parent child
            $parent_child_cnt = "";
            $parent_child_cnt_cal = 0;
            if ($parent_child_flg != "") {
                if ($parent_child_flg == "Parent") {
                    $sql_pc = "SELECT count(*) as cnt FROM companyInfo Where status <> 31 and parent_comp_id =" . isset($b2bid) . " and parent_child = 'Child'";

                    db_b2b();
                    $result_pc = db_query($sql_pc);
                    while ($myrowsel_pc = array_shift($result_pc)) {
                        $parent_child_cnt = "(" . ($myrowsel_pc["cnt"] + 1) . ")";
                        $parent_child_cnt_cal  = $myrowsel_pc["cnt"];
                    }
                } else {
                    if ($parent_child_compid > 0) {
                        $sql_pc = "SELECT count(*) as cnt FROM companyInfo Where status <> 31 and parent_comp_id =" . $parent_child_compid;
                        db_b2b();
                        $result_pc = db_query($sql_pc);
                        while ($myrowsel_pc = array_shift($result_pc)) {
                            $parent_child_cnt = "(" . ($myrowsel_pc["cnt"] + 1) . ")";
                            $parent_child_cnt_cal  = $myrowsel_pc["cnt"];
                        }
                    }
                }
            }
        }

        //count opportunity recordd
        $opp_rec = "(0)";
        db_b2b();
        $opp_sql = db_query("SELECT count(opp_id) as oppcnt FROM opportunity_master WHERE opp_companyid =" . $_REQUEST['ID'] . " AND opp_status != 9");
        $opp_res = array_shift($opp_sql);
        $opp_rec = "(" . $opp_res['oppcnt'] . ")";

        ?>


        <br>

        <a style="color:#0000FF;"
            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=Quoting&rec_type=<?php echo isset($rec_type); ?>">Quoting</a>
        &nbsp;&nbsp;&nbsp;
        <?php if ($recfound == "no") {
        } else { ?>
        <a style="color:#0000FF;"
            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=transactions&rec_type=<?php echo isset($rec_type); ?>">Transactions</a>
        &nbsp;&nbsp;&nbsp;
        <?php } ?>
        <a style="color:#0000FF;"
            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=accounting&rec_type=<?php echo isset($rec_type); ?>">Accounting</a>
        &nbsp;&nbsp;&nbsp;
        <?php if ($status == "Have Boxes") { ?>
        <a style="color:#0000FF;"
            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=watertransactions&rec_type=<?php echo isset($rec_type); ?>">Water</a>
        &nbsp;&nbsp;&nbsp;
        <?php } ?>

        <a style="color:#0000FF;"
            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=recycling&rec_type=<?php echo isset($rec_type); ?>">Recycling</a>
        &nbsp;&nbsp;&nbsp;

        <a target="_blank" style="color:#0000FF;"
            href="report_inbound_inventory_summary.php?warehouse_id=<?php echo $loopidfromcomp; ?>">Inbound Summary</a>
        &nbsp;&nbsp;&nbsp;

        <?php if ($recfound == "no") {
        ?>
        <a href="converttoloops_mrg.php?ID=<?php echo isset($b2bid); ?>&show=summary">Start First
            Transaction</a>&nbsp;&nbsp;&nbsp;
        <?php }

        //if ($parent_child_flg != "") {
        ?>
        <a style="color:#0000FF;"
            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=parentchild&rec_type=<?php echo isset($rec_type); ?>">Family
            Tree
            <?php echo isset($parent_child_cnt); ?>
        </a> &nbsp;&nbsp;&nbsp;
        <?php //}
        ?>

        <?php

        $client_dash_nm = "";
        $client_dash_displaynm = "";
        $client_dash_displaynm1 = "";
        if ($haveNeed_flg == "Need Boxes") {
            $client_dash_nm = "clientdashboard_setup.php";
            $client_dash_displaynm = "Setup Boomerang Portal";
            $client_dash_displaynm1 = "Manage Boomerang Portal";

            //Client dashboard Setup - added my MNM on July-31-14	
            db();
            $res = db_query("Select user_name from clientdashboard_usermaster where companyid = " . $_REQUEST["ID"]);
            $rec_found = "no";
            while ($fetch_data = array_shift($res)) {
                $rec_found = "yes";
            }

            if ($rec_found == "no") {
        ?>
        <a style='color:#0000FF;' href="<?php echo $client_dash_nm; ?>?ID=<?php echo $_REQUEST[" ID"]; ?>">
            <font color="blue">
                <?php echo $client_dash_displaynm; ?>
            </font>
        </a>
        <?php
            } else {
            ?>
        <a style='color:#0000FF;' href="<?php echo $client_dash_nm; ?>?ID=<?php echo $_REQUEST[" ID"]; ?>">
            <font color="blue">
                <?php echo $client_dash_displaynm1; ?>
            </font>
        </a>
        <?php     }
        }

        db();
        $qryGetData = db_query("SELECT loop_warehouse.b2bid FROM loop_transaction_buyer_order_issue INNER JOIN loop_transaction_buyer ON loop_transaction_buyer_order_issue.trans_id = loop_transaction_buyer.id INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.ignore = 0 AND loop_warehouse.b2bid = '" . $_REQUEST["ID"] . "' order by order_issue_start_date_time");
        $rowsGetData = tep_db_num_rows($qryGetData);
        ?>
        &nbsp;&nbsp;&nbsp;<a style="color:#0000FF;"
            href="report_search_order_issue.php?ID=<?php echo $_REQUEST["ID"]; ?>" target="_blank">Order Issue History
            (<?php echo $rowsGetData; ?>)</a>

        <?php

        if ($haveNeed_flg == "Have Boxes") {
            $client_dash_nm = "water_supplierdashboard_setup.php";
            $client_dash_displaynm = "Setup SUPPLIER Dashboard";
            $client_dash_displaynm1 = "Manage SUPPLIER Dashboard";

            $loop_id_tmp = 0;
            db();
            $res = db_query("Select id from loop_warehouse where b2bid = " . $_REQUEST["ID"]);
            while ($fetch_data = array_shift($res)) {
                $loop_id_tmp = $fetch_data["id"];
            }
            db();
            $res = db_query("Select * from loop_dashboards where company_id = '" . $loop_id_tmp . "'");
            $rec_found = "no";
            $dash_link = "";
            while ($fetch_data = array_shift($res)) {
                $rec_found = "yes";
                $dash_link = $fetch_data["webaddress"];
            }

            if ($rec_found == "yes") {
        ?>
        <a style='color:#0000FF;' target="_blank" href="<?php echo $dash_link; ?>">
            <font color="blue">View Supplier Dashboard</font>
        </a>
        <?php
            }
            ?>
        &nbsp;&nbsp;<a style='color:#0000FF;'
            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=freightsetup&rec_type=<?php echo isset($rec_type); ?>">
            <font color="blue">Inbound Freight Setup</font>
        </a>
        <?php      }
        ?>

        &nbsp;&nbsp;<a style="color:#0000FF;"
            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"]; ?>&proc=View&searchcrit=&show=Opportunity&rec_type=<?php echo isset($rec_type); ?>">Opportunity
            <?php echo $opp_rec; ?></a> &nbsp;
        <br><br>
        <?php

        if ($_GET["rec_type"] == 'Supplier' || $_GET["rec_type"] == '') {
        ?>
        <table width="100%" border="0">
            <tr>
                <td valign="top">
                    <iframe frameborder="0" onload="iframemainCompLoaded()" scrolling="auto" style="border: none"
                        height="450" class="show_iframe_compinfo" id="show_compinfo"
                        src="viewComp_info.php?id=<?php echo isset($loopid); ?>&ID=<?php echo isset($b2bid); ?>&status=<?php echo $status; ?>"></iframe>
                </td>
            </tr>
        </table>
        <?php }

        if ($_GET["rec_type"] == 'Manufacturer') {
        ?>
        <table width="100%" border="0">
            <tr>
                <td>
                    <iframe frameborder="0" onload="iframemainCompLoaded()" scrolling="auto" style="border: none"
                        height="400" class="show_iframe_compinfo" id="show_compinfo"
                        src="viewComp_info.php?id=<?php echo isset($loopid); ?>&ID=<?php echo isset($b2bid); ?>&status=<?php echo $status; ?>"></iframe>
                </td>
            </tr>
            <?php
        } ?>
            </font>

            <script>
            function iframemainCompLoaded() {
                ifrmaeobj = document.getElementById("show_compinfo");
                var objheight = ifrmaeobj.contentWindow.document.body.offsetHeight;
                objheight = objheight + 50;
                ifrmaeobj.style.height = objheight + 'px';
            }
            </script>
            </td>
            </tr>
        </table>

        <table border="0">
            <?php
                if ($_REQUEST["show"] == "Opportunity") {
                ?>
            <iframe frameborder="0" scrolling="auto" onload="resizeIframe(this)" style="border: none;" height="1000"
                class="show_iframe" id="show_opportunity"
                src="viewCompany_opportunity.php?ID=<?php echo $_REQUEST['ID']; ?>&show=<?php echo $_REQUEST['show']; ?>&rec_type=<?php echo $_REQUEST['rec_type']; ?>"></iframe>


            <?php

                } elseif ($_REQUEST["show"] == "OpportunityTask") {

                ?>
            <iframe frameborder="0" scrolling="auto" onload="resizeIframe(this)" style="border: none;" height="1000"
                class="show_iframe" id="show_opportunity"
                src="viewCompany_opportunityTask.php?ID=<?php echo $_REQUEST['ID']; ?>&show=<?php echo $_REQUEST['show']; ?>&rec_type=<?php echo $_REQUEST['rec_type']; ?>"></iframe>

            <?php

                } else {

                ?>
            <tr>
                <?php
                        if ($_REQUEST["show"] == "transactions") {
                        ?>
                <td valign="top">
                    <?php
                        } else {
                            ?>
                <td valign="top">
                    <?php
                        }

                        if ($_REQUEST["show"] == "parentchild") {
                            require("viewCompany_parentchild_mysqli.php"); //Parent Child
                        } else {
                            if ($_REQUEST["show"] != "watertransactions" && $_REQUEST["show"] != "recycling" && $_REQUEST["show"] != "freightsetup") {
                                require("viewCompany_func2_mysqli.php"); //Quoting
                            }

                            require("viewCompany_func1_mysqli.php"); //Transactions

                            require("viewCompany_funcaccounting_mysqli.php"); //Accounting
                        }

                        require("viewCompany_func_water_mysqli.php"); //Water Transactions

                        require("viewCompany_func_recycling_mysqli.php"); //Recycling tab

                        require("viewCompany_func_freightsetup_mysqli.php"); //Recycling tab
                            ?>

                    <?php
                            if ($_REQUEST["show"] == "transactions") {
                            ?>
                    <br>
                    <table cellSpacing="1" cellPadding="1" border="0" style="width: 750px">
                        <tr>
                            <td valign="top" align="center" bgColor="#c0cdda">
                                <span style="text-align:middle;">
                                    <font size="1">CRM</font>
                                    <a href='#'
                                        onclick="expand_CRM(1, <?php echo $id ?>, <?php echo $_REQUEST["ID"] ?>, '<?php echo isset($rec_type) ?>', '<?php echo $_REQUEST["show"] ?>'); return false;">Expand</a>/
                                    <a href='#' onclick="collapse_CRM(2); return false;">Collapse</a>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" align="center">
                                <div id='div_CRM_child'>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <?php
                            }
                            ?>

                </td>

                <?php
                            if ($_REQUEST["show"] != "transactions") {
                            ?>
                <td valign="top" width="800px">
                    <?php
                                    if ($_REQUEST["show"] != "parentchild" && $_REQUEST["show"] != "transactions") {
                                    ?>
                    <iframe frameborder="0" scrolling="auto" onload="resizeIframe(this)" style="border: none;"
                        height="1000" class="show_iframe" id="show_crm"
                        src="viewCompany-purchasing_func3.php?id=<?php echo isset($loopid); ?>&b2bid=<?php echo isset($b2bid); ?>&ID=<?php echo $_REQUEST['ID']; ?>&show=<?php echo $_REQUEST['show']; ?>&rec_type=<?php echo $_REQUEST['rec_type']; ?>&show=communications"></iframe>
                    <?php
                                    }

                                    $id = isset($loopid); //get_loop_id($_REQUEST["ID"]);
                                    ?>
                </td>
                <?php
                            }
                            ?>
            </tr>

            <?php     }    //show == opportunity else part close      
                ?>

        </table>



        <?php
            if (($_REQUEST["show"] == "accounting") && 0 > 1) {
                echo "<Font Face='arial' size='4' color='#333333'><b>Accounting</b><br><br>";
            ?>
        <!----------------- CREDIT APPLICATION TABLE ------------------------------------>
        <table cellSpacing="1" cellPadding="1" width="435" border="0" colspan="8">
            <tr align="middle" valign="bottom">
                <td bgColor="#c0cdda">
                    <font size="1">UPLOAD CREDIT APPLICATION</font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td style="width: 150px; height: 13px" class="style1" align="left">

                    <form METHOD="POST" ENCTYPE="multipart/form-data" action="creditappfile_mrg.php">
                        <input type="hidden" name="companyID" value="<?php echo $id; ?>">
                        <input type="hidden" name="creditapp_companyID" value="<?php echo $_REQUEST["ID"]; ?>">
                        <input type="file" size="40" name="userFile" id="userFile" />
                        <input type="submit" name="submit" value="Upload" style="cursor:pointer;">
                    </form>
                    <?php
                            $dt_view_qry = "SELECT * from loop_warehouse WHERE id=" . $id . " AND credit_application_file != ''";
                            db();
                            $dt_view_res = db_query($dt_view_qry);
                            ?>

                    <form method="post" action="creditapplication_approve_mrg.php">
                        <input type="hidden" name="companyID" value="<?php echo $id; ?>">
                        <table border="0" cellSpacing="2" cellPadding="2" width="435">
                            <tr>
                                <td colSpan="3" bgColor="#c0cdda">
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Filename</font>
                                </td>
                                <td bgColor="#c0cdda">
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">User</font>
                                </td>
                                <td colSpan="2" bgColor="#c0cdda">
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Date</font>
                                </td>
                            </tr>
                            <?php
                                    while ($dt_view_row = array_shift($dt_view_res)) {
                                    ?>

                            <tr>
                                <td colSpan="3">
                                    <a target="_blank" href="credit_application/<?php echo preg_replace(
                                                                                                " /'/",
                                                                                                "\'",
                                                                                                $dt_view_row["credit_application_file"]
                                                                                            ); ?>">
                                        <?php echo $dt_view_row["credit_application_file"]; ?>
                                    </a>
                                </td>
                                <td>
                                    <font size="1" color="#333333">
                                        <?php echo $dt_view_row["credit_application_by"]; ?>
                                    </font>
                                </td>
                                <td colSpan="2">
                                    <font size="1" color="#333333">
                                        <?php echo $dt_view_row["credit_application_date"]; ?>
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td bgColor="#c0cdda">
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Net Term</font>
                                </td>
                                <td bgColor="#c0cdda">
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Credit Amount
                                    </font>
                                </td>
                                <td bgColor="#c0cdda">
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Notes</font>
                                </td>
                                <td bgColor="#c0cdda">
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Approve</font>
                                </td>
                                <td bgColor="#c0cdda">
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Approved By
                                    </font>
                                </td>
                                <td bgColor="#c0cdda">
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Approved On
                                    </font>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?php if ($dt_view_row["credit_application_approved"] == 1) { ?>
                                    <font size="1" color="#333333">
                                        <?php echo $dt_view_row["credit_application_net_term"]; ?>
                                    </font>
                                    <?php } else { ?>
                                    <input type="text" name="credit_netterm" id="credit_netterm" style="width:65px;" />
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($dt_view_row["credit_application_approved"] == 1) { ?>
                                    <font size="1" color="#333333">
                                        <?php echo $dt_view_row["credit_application_credit_amt"]; ?>
                                    </font>
                                    <?php } else { ?>
                                    <input type="text" name="credit_amt" id="credit_amt" style="width:75px;" />
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($dt_view_row["credit_application_approved"] == 1) { ?>
                                    <font size="1" color="#333333">
                                        <?php echo $dt_view_row["credit_application_notes"]; ?>
                                    </font>
                                    <?php } else { ?>
                                    <input type="text" name="credit_notes" maxlength=255 id="credit_notes"
                                        style="width:100px;" />
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($dt_view_row["credit_application_approved"] == 1) { ?>
                                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Approved</font>
                                    <?php } else { ?>
                                    <input style="cursor:pointer;" type="submit" name="btn_credit_apprv"
                                        value="Approve Credit application" />
                                    <?php } ?>
                                </td>
                                <td>
                                    <font size="1" color="#333333">
                                        <?php echo $dt_view_row["credit_application_apprv_by"]; ?>
                                    </font>
                                </td>
                                <td>
                                    <font size="1" color="#333333">
                                        <?php echo $dt_view_row["credit_application_apprv_dt"]; ?>
                                    </font>
                                </td>

                            </tr>

                            <?php } ?>
                        </table>
                </td>
            </tr>
            </form>

        </table>


        <!----------------- END CREDIT APPLICATION TABLE ------------------------------------>
        <br />

        <!-- Display the Pending Invoice details -->
        <?php

                if ($_GET["rec_type"] == "Supplier") {

                    $display_info = "no";
                    $total_balance = 0;
                    if ($display_info == "no") { ?>
        <br />
        <table>
            <tr>
                <td colspan=16 bgColor="red" style="height: 16px" align="middle">
                    <font size="1" color="white">INVOICED AND NOT PAID IN FULL</font>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="txt_style12" style="height: 16px" align="middle"><strong>ID</strong></td>
                <td bgColor="#e4e4e4" class="txt_style12" style="height: 16px" align="middle"><strong>Ship Date</strong>
                </td>
                <td bgColor="#e4e4e4" class="txt_style12" style="height: 16px" align="middle"><strong>Last
                        Action</strong></td>
                <td bgColor="#e4e4e4" class="txt_style12" style="height: 16px" align="middle"><strong>Invoiced
                        Amount</strong></td>
                <td bgColor="#e4e4e4" class="txt_style12" style="height: 16px" align="middle"><strong>Balance</strong>
                </td>
                <td bgColor="#e4e4e4" class="txt_style12" style="height: 16px" align="middle"><strong>Invoice
                        Age</strong></td>
            </tr>
            <?php }

                        $display_info = "yes";
                        $dt_view_qry = "SELECT loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J, loop_transaction_buyer.no_invoice FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_warehouse.id = " . $id . " and loop_transaction_buyer.shipped = 1 AND pmt_entered = 0 AND loop_transaction_buyer.ignore = 0  GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                        db();
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {

                            //This is the payment Info for the Customer paying UCB
                            $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
                            db();
                            $payment_qry = db_query($payments_sql);
                            $payment = array_shift($payment_qry);

                            //This is the payment info for UCB paying the related vendors
                            $vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
                            db();
                            $vendor_qry = db_query($vendor_sql);
                            $vendor = array_shift($vendor_qry);

                            //Info about Shipment
                            $bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $dt_view_row["I"] . "' ORDER BY id DESC";
                            db();
                            $bol_file_res = db_query($bol_file_qry);
                            $bol_file_row = array_shift($bol_file_res);

                            $fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $dt_view_row["I"];
                            db();
                            $fbookresult = db_query($fbooksql);
                            $freightbooking = array_shift($fbookresult);

                            $vendors_paid = 0; //Are the vendors paid
                            $vendors_entered = 0; //Has a vendor transaction been entered?
                            $invoice_paid = 0; //Have they paid their invoice?
                            $invoice_entered = 0; //Has the inovice been entered
                            $signed_customer_bol = 0;     //Customer Signed BOL Uploaded
                            $courtesy_followup = 0;     //Courtesy Follow Up Made
                            $delivered = 0;     //Delivered
                            $signed_driver_bol = 0;     //BOL Signed By Driver
                            $shipped = 0;     //Shipped
                            $bol_received = 0;     //BOL Received @ WH
                            $bol_sent = 0;     //BOL Sent to WH"
                            $bol_created = 0;     //BOL Created
                            $freight_booked = 0; //freight booked
                            $sales_order = 0;   // Sales Order entered
                            $po_uploaded = 0;  //po uploaded 

                            //Are all the vendors paid?
                            if ($vendor["B"] == 2 && $vendor["C"] == 2) {
                                $vendors_paid = 1;
                            }

                            //Have we entered a vendor transaction?
                            if ($vendor["A"] > 0) {
                                $vendors_entered = 1;
                            }

                            //Have they paid their invoice?
                            if (number_format($dt_view_row["F"], 2) == number_format($payment["A"], 2) && $dt_view_row["F"] != "") {
                                $invoice_paid = 1;
                            }
                            if ($dt_view_row["no_invoice"] == 1) {
                                $invoice_paid = 1;
                            }

                            //Has an invoice amount been entered?
                            if ($dt_view_row["F"] > 0) {
                                $invoice_entered = 1;
                            }

                            if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") {
                                $signed_customer_bol = 1;
                            }    //Customer Signed BOL Uploaded
                            if ($bol_file_row["bol_shipment_followup"] > 0) {
                                $courtesy_followup = 1;
                            }    //Courtesy Follow Up Made
                            if ($bol_file_row["bol_shipment_received"] > 0) {
                                $delivered = 1;
                            }    //Delivered
                            if ($bol_file_row["bol_signed_file_name"] != "") {
                                $signed_driver_bol = 1;
                            }    //BOL Signed By Driver
                            if ($bol_file_row["bol_shipped"] > 0) {
                                $shipped = 1;
                            }    //Shipped
                            if ($bol_file_row["bol_received"] > 0) {
                                $bol_received = 1;
                            }    //BOL Received @ WH
                            if ($bol_file_row["bol_sent"] > 0) {
                                $bol_sent = 1;
                            }    //BOL Sent to WH"
                            if ($bol_file_row["id"] > 0) {
                                $bol_created = 1;
                            }    //BOL Created

                            if ($freightbooking["id"] > 0) {
                                $freight_booked = 1;
                            } //freight booked

                            if (($dt_view_row["G"] == 1)) {
                                $sales_order = 1;
                            } //sales order created
                            if ($dt_view_row["H"] != "") {
                                $po_uploaded = 1;
                            } //po uploaded 



                            $boxsource = "";
                            $box_qry = "SELECT loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.typeid = 1 AND loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
                            db();
                            $box_res = db_query($box_qry);
                            while ($box_row = array_shift($box_res)) {
                                $boxsource = $box_row["C"];
                            }


                            if ($invoice_entered == 1 && $invoice_paid == 0) {

                                $dt_view_qry2 = "SELECT SUM(loop_bol_tracking.qty) AS A, loop_bol_tracking.bol_STL1 AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.warehouse_id AS D, loop_bol_tracking.bol_pickupdate AS E, loop_bol_tracking.quantity1 AS Q1, loop_bol_tracking.quantity2 AS Q2, loop_bol_tracking.quantity3 AS Q3 FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $dt_view_row["I"];
                                db();
                                $dt_view_res2 = db_query($dt_view_qry2);
                                $dt_view_row2 = array_shift($dt_view_res2);

                            ?>
            <tr>

                <td bgColor="#e4e4e4" class="txt_style12">
                    <a
                        href="viewCompany_new_test.php?warehouse_id=<?php echo $dt_view_row["D"]; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $dt_view_row["D"]; ?>&rec_id=<?php echo $dt_view_row["I"]; ?>&display=buyer_payment">
                        <a
                            href="viewCompany_new_test.php?ID=<?php echo $_REQUEST["ID"] ?>&show=companyinfo&rec_id=<?php echo $dt_view_row["I"]; ?>">
                            <?php echo $dt_view_row["I"]; ?>
                        </a>
                </td>
                <td bgColor="#e4e4e4" class="txt_style12">
                    <?php echo $dt_view_row2["E"]; ?>
                </td>

                <!---- Last Action ------->
                <td bgColor="#e4e4e4" class="txt_style12">
                    <?php

                                        if ($invoice_paid == 1) {
                                            if ($vendors_paid == 1) {
                                                echo "Vendors Paid";
                                            } elseif ($vendors_entered == 1) {
                                                echo "Vendors Invoiced";
                                            } else {
                                                echo "Customer Paid";
                                            }
                                        } elseif ($invoice_entered == 1) {
                                            echo "Customer Invoiced";
                                        } elseif ($signed_customer_bol == 1) {
                                            echo "Customer Signed BOL";
                                        } elseif ($courtesy_followup == 1) {
                                            echo "Courtesy Followup Made";
                                        } elseif ($delivered == 1) {
                                            echo "Delivered";
                                        } elseif ($signed_driver_bol == 1) {
                                            echo "Shipped - Driver Signed";
                                        } elseif ($shipped == 1) {
                                            echo "Shipped";
                                        } elseif ($bol_received == 1) {
                                            echo "BOL @ Warehouse";
                                        } elseif ($bol_sent == 1) {
                                            echo "BOL Sent to Warehouse";
                                        } elseif ($bol_created == 1) {
                                            echo "BOL Created";
                                        } elseif ($freight_booked == 1) {
                                            echo "Freight Booked";
                                        } elseif ($sales_order == 1) {
                                            echo "Sales Order Entered";
                                        } elseif ($po_uploaded == 1) {
                                            echo "PO Uploaded";
                                        }


                                        ?>
                </td>

                <td bgColor="#e4e4e4" class="txt_style12">
                    <?php echo number_format($dt_view_row["F"], 2); ?>
                </td>
                <?php

                                    $dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
                                    db();
                                    $dt_view_res3 = db_query($dt_view_qry3);
                                    $dt_view_row3 = array_shift($dt_view_res3);
                                    $blalnce_col_bg = "txt_style12";
                                    if (($dt_view_row["F"] - $dt_view_row3["PAID"]) < 0) {
                                        $blalnce_col_bg  = "txt_style12_bold";
                                    }
                                    ?>
                <td bgColor="#e4e4e4" class="<?php echo $blalnce_col_bg; ?>">
                    <?php echo number_format(($dt_view_row["F"] - $dt_view_row3["PAID"]), 2);
                                        $total_balance += $dt_view_row["F"] - $dt_view_row3["PAID"];
                                        ?>
                </td>
                <?php
                                    $start_t = strtotime($dt_view_row["J"]);
                                    $end_time =  strtotime('now');
                                    if (number_format(($end_time - $start_t) / (3600 * 24), 0) > 30 && number_format(($end_time - $start_t) / (3600 * 24), 0) < 1000) {
                                    ?>
                <td bgColor="#ff0000" class="txt_style12">
                    <?php echo number_format(($end_time - $start_t) / (3600 * 24), 0); ?>
                </td>

                <?php
                                    } elseif (number_format(($end_time - $start_t) / (3600 * 24000), 0) > 10) {
                                    ?>
                <td bgColor="#e4e4e4" class="txt_style12">&nbsp;

                </td>

                <?php
                                    } else {
                                    ?>
                <td bgColor="#e4e4e4" class="txt_style12">
                    <?php echo number_format(($end_time - $start_t) / (3600 * 24), 0); ?>
                </td>
                <?php
                                    }
                                    ?>
            </tr>
            <?php
                            }    //if not paid
                        }    //while loop

                        if ($display_info == "yes") {
                            ?>
            <tr>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12">Total:</td>
                <td bgColor="#e4e4e4" class="txt_style12"><?php echo number_format($total_balance, 2); ?></td>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
            </tr>
        </table><br><br>
        <?php }
                    }
                }

                //$tmpforfirstload = strpos($_SERVER["QUERY_STRING"],'&') ;
                //if ($_REQUEST["show"] == "summary" || $tmpforfirstload == 0) 
                if ($_REQUEST["show"] == "summary") {

                    echo "<Font Face='arial' size='4' color='#333333'><b>Summary</b><br><br>";

                    if (isset($b2bid) > 0) {
                        $x = "Select * from companyInfo Where ID = " . isset($b2bid);
                        db_b2b();
                        $dt_view_res = db_query($x);
                    } else {
                        $x = "Select * from companyInfo Where ID = " . $_REQUEST["ID"];
                        db_b2b();
                        $dt_view_res = db_query($x);
                    }
                ?>

        <!-- First table start-->
        <table width="600" border="0" cellspacing="1" cellpadding="1">
            <tr align="center">
                <td colspan="2">
                    <font face="Arial, Helvetica, sans-serif" size="2"><b>SELL TO INFORMATION</b></font>
                </td>
            </tr>
            <tr align="center">
                <td bgcolor="#C0CDDA">
                    <font face="Arial, Helvetica, sans-serif" size="1">Sell To Name</font>
                </td>
                <td bgcolor="#C0CDDA">
                    <font face="Arial, Helvetica, sans-serif" size="1">Sell To Title</font>
                </td>

            </tr>

            <?php
                    if (tep_db_num_rows($dt_view_res) > 0) {
                        while ($row = array_shift($dt_view_res)) { ?>
            <tr>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo $row["contact"]; ?>
                    </font>
                </td>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo $row["contactTitle"]; ?>
                    </font>
                </td>
            </tr>
            <?php }
                    } else {
                        echo "<b><font color='red' face='Arial, Helvetica, sans-serif' size=4><span style=padding-left:200px;>Record not found in database</span></font></b><br><br>";
                    }
                    if (isset($b2bid) > 0) {
                        $x = "Select * from b2bsellto where companyid = " . isset($b2bid) . " order by selltoid";
                        db_b2b();
                        $dt_view_res = db_query($x);
                    } else {
                        $x = "Select * from b2bsellto where companyid = " . $_REQUEST["ID"] . " order by selltoid";
                        db_b2b();
                        $dt_view_res = db_query($x);
                    }
                    while ($row = array_shift($dt_view_res)) { ?>
            <tr>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo $row["name"]; ?>
                    </font>
                </td>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo $row["title"]; ?>
                    </font>
                </td>
            </tr>
            <?php } ?>

        </table>

        <!-- First table end-->


        <!-- Second table start-->
        <br>
        <table width="600" border="0" cellspacing="1" cellpadding="1">
            <tr align="center">
                <td colspan="2">
                    <font face="Arial, Helvetica, sans-serif" size="2"><b>QUOTES</b>
                </td>
            </tr>
            <tr align="center">
                <td bgcolor="#C0CDDA">
                    <font face="Arial, Helvetica, sans-serif" size="1">Quote Type</font>
                </td>
                <td bgcolor="#C0CDDA">
                    <font face="Arial, Helvetica, sans-serif" size="1">Quote Number</font>
                </td>
            </tr>

            <tr>
                <?php


                        if (isset($b2bid) > 0) {
                            $x = "SELECT quote_status.status_name,count(*) as  quote_number FROM quote";
                            $x .= " inner join quote_status on quote.qstatus = quote_status.qid";
                            $x .= " where companyId = " . isset($b2bid) . " and status = 1";
                            $x .= " group by status_name";
                            db_b2b();
                            $dt_view_res = db_query($x);
                        } else {
                            $x = "SELECT quote_status.status_name,count(*) as  quote_number FROM quote";
                            $x .= " inner join quote_status on quote.qstatus = quote_status.qid";
                            $x .= " where companyId = " . $_REQUEST["ID"] . " and status = 1";
                            $x .= " group by status_name";
                            db_b2b();
                            $dt_view_res = db_query($x);
                        }
                        //echo $x;

                        while ($row = array_shift($dt_view_res)) {
                            //db_query("INSERT INTO temp ( `status_name` , `quote_number`) values('" . $row["status_name"] ."'," . $row["quote_number"] . ")",db_b2b());
                            //echo "INSERT INTO temp ( `status_name` , `quote_number`) values('" . $row["status_name"] ."'," . $row["quote_number"] . "";
                        ?>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo $row["status_name"]; ?>
                    </font>
                </td>

                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo $row["quote_number"]; ?>
                    </font>
                </td>
            </tr>
            <?php }
                        //db_query("DELETE FROM temp ",db_b2b());	
                ?>

        </table>

        <!-- Second table end-->

        <!-- Third table start-->
        <br>
        <table width="600" border="0" cellspacing="1" cellpadding="1">
            <tr align="center">
                <td colspan="3">
                    <font face="Arial, Helvetica, sans-serif" size="2"><b>ACCOUNT OWNER, ACCOUNT STATUS, AND NEXT
                            STEP<b>
                </td>
            </tr>
            <tr align="center">
                <td bgcolor="#C0CDDA">
                    <font face="Arial, Helvetica, sans-serif" size="1">Account Owner</font>
                </td>
                <td bgcolor="#C0CDDA">
                    <font face="Arial, Helvetica, sans-serif" size="1">Account Status</font>
                </td>
                <td bgcolor="#C0CDDA">
                    <font face="Arial, Helvetica, sans-serif" size="1">Next Step</font>
                </td>
            </tr>

            <tr>
                <?php

                        if (isset($b2bid) > 0) {
                            $x = "Select * from companyInfo Where ID = " . isset($b2bid);
                            //echo $x;
                            db_b2b();
                            $dt_view_res = db_query($x);
                        } else {
                            $x = "Select * from companyInfo Where ID = " . $_REQUEST["ID"];
                            //echo $x;
                            db_b2b();
                            $dt_view_res = db_query($x);
                        }

                        $row = array_shift($dt_view_res);

                        $arr = explode(",", $row["assignedto"]);
                        $qassign = "SELECT * FROM employees WHERE status='Active'";
                        db_b2b();
                        $dt_view_res_assign = db_query($qassign);
                        ?>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php
                                while ($res_assign = array_shift($dt_view_res_assign)) {
                                    if ($row["assignedto"] != $res_assign["employeeID"]) {
                                    } else {
                                        echo $res_assign["name"];
                                    }
                                }
                                ?>
                    </font>
                </td>
                <?php
                        if (($row["haveNeed"] == "Need Boxes") || ($row["haveNeed"] == "Looking / Have Boxes") || ($row["haveNeed"] == "Have Boxes")) { ?>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php
                                $status = "Select * from status where id = '" . $row["status"] . "'";
                                db_b2b();
                                $dt_view_res4 = db_query($status);
                                while ($objStatus = array_shift($dt_view_res4)) {
                                    echo $objStatus["name"];
                                }
                            }
                                ?>
                    </font>
                </td>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo $row["next_step"]; ?>
                    </font>
                </td>
            </tr>
        </table>

        <!-- Third table start-->

        <!-- Fourth table start-->
        <br>
        <table width="600" border="0" cellspacing="1" cellpadding="1">
            <tr align="center">
                <td>
                    <font face="Arial, Helvetica, sans-serif" size="2"><b>INTERNAL NOTES 6390<b></font>
                </td>
            </tr>
            <tr>
                <td bgcolor="#E4E4E4" height="20px">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo $row["int_notes"] ?>
                    </font>
                </td>
            </tr>
        </table>
        <!-- Fourth table end-->


        <!-- Fifth table start-->

        <?php
                    $crm_numberof_chr = 0;
                    $crm_rows_per_page = 0;
                    $crm_numberof_chr_divheight = 0;
                    $sql = "SELECT * FROM tblvariable ";
                    db_email();
                    $result = db_query($sql);

                    while ($myrowsel = array_shift($result)) {
                        if (strtoupper($myrowsel["variablename"]) == strtoupper("crm_numberof_chr")) {
                            $crm_numberof_chr = $myrowsel["variablevalue"];
                        }
                        if (strtoupper($myrowsel["variablename"]) == strtoupper("crm_rows_per_page")) {
                            $crm_rows_per_page = $myrowsel["variablevalue"];
                        }
                        if (strtoupper($myrowsel["variablename"]) == strtoupper("crm_numberof_chr_divheight")) {
                            $crm_numberof_chr_divheight = $myrowsel["variablevalue"];
                        }
                    }

                ?>
        <br>

        <!-- Fifth table end-->

        <!-- test table end-->
        <?php if ($_REQUEST["rec_type"] == "Supplier") { ?>
        <table width="600" border="0" cellspacing="1" cellpadding="1">
            <tr align="center">
                <td colspan="6">
                    <font face="Arial, Helvetica, sans-serif" size="2"><b>TRANSACTIONS<b>
                </td>
            </tr>
            <tr align="center" bgcolor="#C0CDDA">
                <td>
                    <font face="Arial, Helvetica, sans-serif" size="1"><b>Purchase Order Amount<b>
                </td>
                <td>
                    <font face="Arial, Helvetica, sans-serif" size="1"><b>Invoice Amount<b>
                </td>
                <td>
                    <font face="Arial, Helvetica, sans-serif" size="1"><b>Sent Invoice Amount<b>
                </td>
                <td>
                    <font face="Arial, Helvetica, sans-serif" size="1"><b>Paid Amount<b>
                </td>
                <td>
                    <font face="Arial, Helvetica, sans-serif" size="1"><b>Vendor Payments<b>
                </td>
                <td>
                    <font face="Arial, Helvetica, sans-serif" size="1"><b>Profit Amount<b>
                </td>
            </tr>
            <?php
                        $get_trans_sql = "SELECT * FROM loop_transaction_buyer WHERE warehouse_id = " . $id . " ORDER BY transaction_date DESC LIMIT 0, 1000";
                        //echo $get_trans_sql;
                        db();
                        $tran = db_query($get_trans_sql);
                        while ($tranlist = array_shift($tran)) {
                            $trans .= $tranlist['id'] . ",";
                            //echo $trans;
                        }
                        $rec_id_trans = rtrim($trans, ',');

                        $dt_view_qry = "SELECT (po_poorderamount),(inv_amount) from loop_transaction_buyer WHERE id in(" . $rec_id_trans . ") AND po_file != ''";
                        //echo $dt_view_qry;
                        db();
                        $dt_view_res = db_query($dt_view_qry);
                        $po_amt = 0;
                        $inv_amount = 0;
                        while ($num_rows = array_shift($dt_view_res)) {
                            $po_amt += $num_rows["po_poorderamount"];
                            $inv_amount += $num_rows["inv_amount"];
                        }

                        $invoice_amt = 0;
                        $inv_qry = "SELECT * FROM loop_invoice_items WHERE trans_rec_id in(" . $rec_id_trans . ") ORDER BY id ASC";
                        //echo $inv_qry;
                        db();
                        $inv_res = db_query($inv_qry);
                        while ($inv_row = array_shift($inv_res)) {
                            $invoice_amt += $inv_row["quantity"] * $inv_row["price"];
                        }
                        //echo $invoice_amt;

                        if ($invoice_amt == 0) {
                            $invoice_amt = $inv_amount;
                        }
                        //echo $inv_amount;

                        $dt_view_qry = "SELECT * from loop_buyer_payments WHERE trans_rec_id in(" . $rec_id_trans . ")";
                        $paid_amount = 0;
                        db();
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {
                            $paid_amount += $dt_view_row["amount"];
                        }


                        $vendor_pay = 0;
                        $dt_view_1 = "SELECT amount from loop_transaction_buyer_payments INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id in(" . $rec_id_trans . ")";
                        //echo $dt_view_1;
                        db();
                        $dt_view_res_1 = db_query($dt_view_1);
                        while ($inv_row_1 = array_shift($dt_view_res_1)) {
                            $vendor_pay += $inv_row_1["amount"];
                        }


                        $profit_val = $invoice_amt - $vendor_pay;
                        //echo $profit_val."<br>";
                        $profit_val_per = "";
                        if ($profit_val > 0) {
                            $profit_val_per = ($profit_val * 100) / $invoice_amt;

                            if ($profit_val_per > 50) {
                                $profit_val_per = " (<font color='#F46DD9'>" . number_format((($profit_val * 100) / $invoice_amt), 2) . "%</font>)";
                            } else if ($profit_val_per < 50) {
                                $profit_val_per = " (<font color='#EE3838'>" . number_format((($profit_val * 100) / $invoice_amt), 2) . "%</font>)";
                            }
                        }
                        $profit_val = number_format($invoice_amt - $vendor_pay, 2);

                        ?>

            <tr>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                        <?php echo number_format($po_amt, 2); ?>
                    </font>
                </td>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                        <?php echo number_format($invoice_amt, 2); ?>
                    </font>
                </td>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                        <?php echo number_format($inv_amount, 2); ?>
                    </font>
                </td>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                        <?php echo number_format($paid_amount, 2); ?>
                    </font>
                </td>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                        <?php echo number_format($vendor_pay, 2); ?>
                    </font>
                </td>
                <td align="center" bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">$
                        <?php if ($profit_val > 0) {
                                        echo '<font color=green>' . $profit_val . '</font>' . $profit_val_per;
                                    } else {
                                        echo '<font color=red>' . $profit_val . '</font>' . $profit_val_per;
                                    } ?>
                    </font>
                </td>
            </tr>
        </table>
        <?php } ?>
        <!-- test table end-->

        <!-- Sixth table start-->
        <br>
        <?php

                    if ($recfound == "yes") {
                ?>
        <table cellSpacing="1" cellPadding="1" width="435" border="0">
            <br>
            <tr align="middle" valign="bottom">
                <td colspan="2">
                    <font face="Arial, Helvetica, sans-serif" size="2"><b>CREDIT APPLICATION</b></font>
                </td>
            </tr>

            <tr>
                <td bgColor="#c0cdda">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Filename</font>
                </td>
                <td bgColor="#c0cdda">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Yes/No</font>
                </td>
            </tr>

            <?php
                        $dt_view_qry = "SELECT * from loop_warehouse WHERE id=" . $id . "";
                        db();
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {
                        ?>

            <tr>
                <?php if ($dt_view_row["credit_application_file"] != "") { ?>
                <td bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <a target="_blank" href="credit_application/<?php echo preg_replace(
                                                                                            " /'/",
                                                                                            "\'",
                                                                                            $dt_view_row["credit_application_file"]
                                                                                        ); ?>">
                            <?php echo $dt_view_row["credit_application_file"]; ?>
                        </a>
                    </font>
                </td>
                <td bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo "Yes"; ?>
                    </font>
                </td>
                <?php } else { ?>
                <td bgcolor="#E4E4E4">&nbsp;</td>
                <td bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
                        <?php echo "No"; ?>
                    </font>
                </td>
                <?php } ?>
            </tr>
        </table>
        <!-- Sixth table end-->

        <!-- Seventh table start-->
        <br>

        <table cellSpacing="1" cellPadding="1" width="435" border="0">
            <tr align="middle" valign="bottom">
                <td colspan="2">
                    <font face="Arial, Helvetica, sans-serif" size="2"><b>CREDIT APPLICATION APPROVED</b></font>
                </td>
            </tr>
            <tr>
                <td bgColor="#c0cdda">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Credit Application Approved
                    </font>
                </td>
                <td bgColor="#c0cdda">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Yes/No</font>
                </td>
            </tr>

            <tr>
                <td bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Approved</font>
                </td>
                <?php if ($dt_view_row["credit_application_approved"] == 1) { ?>
                <td bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">Yes</font>
                </td>
                <?php } else { ?>
                <td bgcolor="#E4E4E4">
                    <font face="Arial, Helvetica, sans-serif" size="1" color="#333333">No</font>
                </td>
                <?php } ?>
            </tr>

            <?php } ?>
        </table>
        <!-- Seventh table end-->

        <!-- Eight table start-->
        <?php

                        $display_info = "no";
                        $total_balance = 0;
                        if ($display_info == "no") {

                    ?>
        <br />
        <table cellSpacing="1" cellPadding="1" width="435" border="0">
            <tr>
                <td colspan=16 align="middle" valign="bottom" style="height: 16px" align="middle">
                    <font face="Arial, Helvetica, sans-serif" size="2"><b>INVOICED AND NOT PAID IN FULL</b></font>
                </td>
            </tr>
            <tr>
                <td bgColor="#c0cdda" class="txt_style12" style="height: 16px" align="middle"><strong>ID</strong></td>
                <td bgColor="#c0cdda" class="txt_style12" style="height: 16px" align="middle"><strong>Ship Date</strong>
                </td>
                <td bgColor="#c0cdda" class="txt_style12" style="height: 16px" align="middle"><strong>Last
                        Action</strong></td>
                <td bgColor="#c0cdda" class="txt_style12" style="height: 16px" align="middle"><strong>Invoiced
                        Amount</strong></td>
                <td bgColor="#c0cdda" class="txt_style12" style="height: 16px" align="middle"><strong>Balance</strong>
                </td>
                <td bgColor="#c0cdda" class="txt_style12" style="height: 16px" align="middle"><strong>Invoice
                        Age</strong></td>
            </tr>
            <?php }

                        $display_info = "yes";
                        $dt_view_qry = "SELECT loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J, loop_transaction_buyer.no_invoice FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_warehouse.id = " . $id . " and loop_transaction_buyer.shipped = 1 AND pmt_entered = 0 AND loop_transaction_buyer.ignore = 0  GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                        db();
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {

                            //This is the payment Info for the Customer paying UCB
                            $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
                            db();
                            $payment_qry = db_query($payments_sql);
                            $payment = array_shift($payment_qry);

                            //This is the payment info for UCB paying the related vendors
                            $vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
                            db();
                            $vendor_qry = db_query($vendor_sql);
                            $vendor = array_shift($vendor_qry);

                            //Info about Shipment
                            $bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $dt_view_row["I"] . "' ORDER BY id DESC";
                            db();
                            $bol_file_res = db_query($bol_file_qry);
                            $bol_file_row = array_shift($bol_file_res);

                            $fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $dt_view_row["I"];
                            db();
                            $fbookresult = db_query($fbooksql);
                            $freightbooking = array_shift($fbookresult);

                            $vendors_paid = 0; //Are the vendors paid
                            $vendors_entered = 0; //Has a vendor transaction been entered?
                            $invoice_paid = 0; //Have they paid their invoice?
                            $invoice_entered = 0; //Has the inovice been entered
                            $signed_customer_bol = 0;     //Customer Signed BOL Uploaded
                            $courtesy_followup = 0;     //Courtesy Follow Up Made
                            $delivered = 0;     //Delivered
                            $signed_driver_bol = 0;     //BOL Signed By Driver
                            $shipped = 0;     //Shipped
                            $bol_received = 0;     //BOL Received @ WH
                            $bol_sent = 0;     //BOL Sent to WH"
                            $bol_created = 0;     //BOL Created
                            $freight_booked = 0; //freight booked
                            $sales_order = 0;   // Sales Order entered
                            $po_uploaded = 0;  //po uploaded 

                            //Are all the vendors paid?
                            if ($vendor["B"] == 2 && $vendor["C"] == 2) {
                                $vendors_paid = 1;
                            }

                            //Have we entered a vendor transaction?
                            if ($vendor["A"] > 0) {
                                $vendors_entered = 1;
                            }

                            //Have they paid their invoice?
                            if (number_format($dt_view_row["F"], 2) == number_format($payment["A"], 2) && $dt_view_row["F"] != "") {
                                $invoice_paid = 1;
                            }
                            if ($dt_view_row["no_invoice"] == 1) {
                                $invoice_paid = 1;
                            }

                            //Has an invoice amount been entered?
                            if ($dt_view_row["F"] > 0) {
                                $invoice_entered = 1;
                            }

                            if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") {
                                $signed_customer_bol = 1;
                            }    //Customer Signed BOL Uploaded
                            if ($bol_file_row["bol_shipment_followup"] > 0) {
                                $courtesy_followup = 1;
                            }    //Courtesy Follow Up Made
                            if ($bol_file_row["bol_shipment_received"] > 0) {
                                $delivered = 1;
                            }    //Delivered
                            if ($bol_file_row["bol_signed_file_name"] != "") {
                                $signed_driver_bol = 1;
                            }    //BOL Signed By Driver
                            if ($bol_file_row["bol_shipped"] > 0) {
                                $shipped = 1;
                            }    //Shipped
                            if ($bol_file_row["bol_received"] > 0) {
                                $bol_received = 1;
                            }    //BOL Received @ WH
                            if ($bol_file_row["bol_sent"] > 0) {
                                $bol_sent = 1;
                            }    //BOL Sent to WH"
                            if ($bol_file_row["id"] > 0) {
                                $bol_created = 1;
                            }    //BOL Created

                            if ($freightbooking["id"] > 0) {
                                $freight_booked = 1;
                            } //freight booked

                            if (($dt_view_row["G"] == 1)) {
                                $sales_order = 1;
                            } //sales order created
                            if ($dt_view_row["H"] != "") {
                                $po_uploaded = 1;
                            } //po uploaded 



                            $boxsource = "";
                            $box_qry = "SELECT loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.typeid = 1 AND loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
                            db();
                            $box_res = db_query($box_qry);
                            while ($box_row = array_shift($box_res)) {
                                $boxsource = $box_row["C"];
                            }


                            if ($invoice_entered == 1 && $invoice_paid == 0) {

                                $dt_view_qry2 = "SELECT SUM(loop_bol_tracking.qty) AS A, loop_bol_tracking.bol_STL1 AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.warehouse_id AS D, loop_bol_tracking.bol_pickupdate AS E, loop_bol_tracking.quantity1 AS Q1, loop_bol_tracking.quantity2 AS Q2, loop_bol_tracking.quantity3 AS Q3 FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $dt_view_row["I"];
                                db();
                                $dt_view_res2 = db_query($dt_view_qry2);
                                $dt_view_row2 = array_shift($dt_view_res2);

                            ?>
            <tr>

                <td bgColor="#e4e4e4" class="txt_style12">
                    <?php echo $dt_view_row["I"]; ?>
                </td>
                <td bgColor="#e4e4e4" class="txt_style12">
                    <?php echo $dt_view_row2["E"]; ?>
                </td>

                <!---- Last Action ------->
                <td bgColor="#e4e4e4" class="txt_style12">
                    <?php
                                        if ($invoice_paid == 1) {
                                            if ($vendors_paid == 1) {
                                                echo "Vendors Paid";
                                            } elseif ($vendors_entered == 1) {
                                                echo "Vendors Invoiced";
                                            } else {
                                                echo "Customer Paid";
                                            }
                                        } elseif ($invoice_entered == 1) {
                                            echo "Customer Invoiced";
                                        } elseif ($signed_customer_bol == 1) {
                                            echo "Customer Signed BOL";
                                        } elseif ($courtesy_followup == 1) {
                                            echo "Courtesy Followup Made";
                                        } elseif ($delivered == 1) {
                                            echo "Delivered";
                                        } elseif ($signed_driver_bol == 1) {
                                            echo "Shipped - Driver Signed";
                                        } elseif ($shipped == 1) {
                                            echo "Shipped";
                                        } elseif ($bol_received == 1) {
                                            echo "BOL @ Warehouse";
                                        } elseif ($bol_sent == 1) {
                                            echo "BOL Sent to Warehouse";
                                        } elseif ($bol_created == 1) {
                                            echo "BOL Created";
                                        } elseif ($freight_booked == 1) {
                                            echo "Freight Booked";
                                        } elseif ($sales_order == 1) {
                                            echo "Sales Order Entered";
                                        } elseif ($po_uploaded == 1) {
                                            echo "PO Uploaded";
                                        }


                                        ?>
                </td>

                <td bgColor="#e4e4e4" class="txt_style12">
                    <?php echo number_format($dt_view_row["F"], 2); ?>
                </td>

                <?php

                                    $dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
                                    db();
                                    $dt_view_res3 = db_query($dt_view_qry3);
                                    $dt_view_row3 = array_shift($dt_view_res3);
                                    $blalnce_col_bg = "txt_style12";
                                    if (($dt_view_row["F"] - $dt_view_row3["PAID"]) < 0) {
                                        $blalnce_col_bg  = "txt_style12_bold";
                                    }
                                    ?>
                <td bgColor="#e4e4e4" class="<?php echo $blalnce_col_bg; ?>">
                    <?php echo number_format(($dt_view_row["F"] - $dt_view_row3["PAID"]), 2);
                                        $total_balance += $dt_view_row["F"] - $dt_view_row3["PAID"];
                                        ?>
                </td>
                <?php
                                    $start_t = strtotime($dt_view_row["J"]);
                                    $end_time =  strtotime('now');
                                    if (number_format(($end_time - $start_t) / (3600 * 24), 0) > 30 && number_format(($end_time - $start_t) / (3600 * 24), 0) < 1000) {
                                    ?>
                <td bgColor="#ff0000" class="txt_style12">
                    <?php echo number_format(($end_time - $start_t) / (3600 * 24), 0); ?>
                </td>

                <?php
                                    } elseif (number_format(($end_time - $start_t) / (3600 * 24000), 0) > 10) {
                                    ?>
                <td bgColor="#e4e4e4" class="txt_style12">&nbsp;

                </td>

                <?php
                                    } else {
                                    ?>
                <td bgColor="#e4e4e4" class="txt_style12">
                    <?php echo number_format(($end_time - $start_t) / (3600 * 24), 0); ?>
                </td>
                <?php
                                    }
                                    ?>
            </tr>
            <?php
                            }    //if not paid
                        }    //while loop

                        if ($display_info == "yes") {
                            ?>
            <tr>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
                <td bgColor="#e4e4e4" class="txt_style12">Total:</td>
                <td bgColor="#e4e4e4" class="txt_style12"><?php echo number_format($total_balance, 2); ?></td>
                <td bgColor="#e4e4e4" class="txt_style12"></td>
            </tr>
        </table><br><br>
        <?php } ?>

        <!-- Eight table start-->
        <?php }
                }

            ?>

        <!--<div ID="listdiv_new" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
<div ID="listdiv_new1" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
-->

        <?php
            if ($_REQUEST["billtoinaddmode"] == "yes") { ?>
        <script>
        document.getElementById('expand_bill').style.display = "none";
        Billtotoggle();
        </script>
        <?php     }
            ?>

        <?php
            if ($_REQUEST["selltoinaddmode"] == "yes") { ?>
        <script>
        document.getElementById('expand').style.display = "none";
        selltotoggle();
        </script>
        <?php     }

            tep_db_close();
            ?>

    </div>

</body>

</html>