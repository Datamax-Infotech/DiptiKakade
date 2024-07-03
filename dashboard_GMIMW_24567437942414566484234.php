<?php
//require ("inc/header_session.php");
?>

<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


$wid = 718;
$carrier_name = "";
$destination_id = 17; //This is in loop_mccormick_dock. Wellston has 2 docks 
$title_name = "General Mills, Milwuakee - Dashboard";
$return_url = "dashboard_GMIMW_24567437942414566484234.php";
$items = 5;


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

    <title><?php echo $title_name; ?></title>




    <style type="text/css">
    .style7 {
        font-size: x-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        background-color: #FFCC66;
    }

    .style5 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        text-align: center;
        background-color: #99FF99;
    }

    .style6 {
        text-align: center;
        background-color: #99FF99;
    }

    .style2 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
    }

    .style3 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
    }

    .style8 {
        text-align: left;
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
    }

    .style11 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: center;
    }

    .style10 {
        text-align: left;
    }

    .style12 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: right;
    }

    .style12center {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: center;
    }

    .style12right {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: right;
    }

    .style12left {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: left;
    }

    .style13 {
        font-family: Arial, Helvetica, sans-serif;
    }

    .style14 {
        font-size: x-small;
    }

    .style15 {
        font-size: x-small;
    }

    .style16 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        background-color: #99FF99;
    }

    .style17 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        background-color: #99FF99;
    }

    select,
    input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000000;
        font-weight: normal;
    }
    </style>


</head>
<script language="JavaScript">
function FormCheck() {
    if (document.BOLForm.trailer_no.value == "" |
        document.BOLForm.dock.value == "" |
        document.BOLForm.fullname.value == "") {
        alert("Please Complete All Field.\n Need help? Call 1-888-BOXES-88");
        return false;
    }
}
</SCRIPT>
<script type="text/javascript">
function update_cart() {
    var x
    var total = 0
    var order_total
    for (x = 1; x <= <?php echo $items; ?>; x++) {
        item_total = document.getElementById("weight_" + x)
        total = total + item_total.value * 1
    }
    order_total = document.getElementById("order_total")
    document.getElementById("order_total").value = total.toFixed(0)
    var totalcount = 0
    var count_total
    for (x = 1; x <= 4; x++) {
        count_total = document.getElementById("count_" + x)
        totalcount = totalcount + count_total.value * 1
    }
    count_total = document.getElementById("count_total")
    document.getElementById("count_total").value = totalcount.toFixed(0)
}
</script>

<body>




    <!---- TABLE TO FORMAT ----------->
    <table>
        <tr>
            <td>
                <?php
                $query = "SELECT * FROM loop_warehouse WHERE id = " . $wid;
                db();
                $res = db_query($query);
                while ($row = array_shift($res)) {
                    $warehouse_name = $row["warehouse_name"];
                ?>
                <img src="images/<?php echo $row["logo"]; ?>">
                <?php
                }
                ?>
            </td>
            <td align=center colspan="3">
                <font face="Ariel" size="5">
                    <b>UsedCardboardBoxes.com<br></b>
                    Dashboard Report for:<br>
                    <b><i><?php echo isset($warehouse_name); ?></i></b>
                    </i>
            </td>
            <td colspan="20" align="right">
                <img src="new_interface_help.gif">
            </td>
        </tr>
        <tr>
            <td valign="top">


                <!--------------------- BEGIN BOL REQUEST ----------------------------------------------->


                <FORM METHOD="POST" ACTION="BOLpickupsubmitNEW.php" name="BOL" id="BOL">
                    <input type=hidden value="<?php echo $carrier_name; ?>" name="carrier">
                    <input type=hidden value="<?php echo $wid; ?>" name="warehouse_id">
                    <input type=hidden value="<?php echo $return_url; ?>" name="return_url">
                    <!--	<input type=hidden value="<?php echo $destination_id; ?>" name="destination_id"> -->
                    <?php
                    $query = "SELECT * FROM loop_warehouse WHERE id = " . $wid;
                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {
                    ?>
                    <input type=hidden value="<?php echo $row["logo"]; ?>" name="logo">
                    <?php
                    }
                    ?>
                    <TABLE ALIGN='LEFT' width="450">
                        <tr align="middle">
                            <td colSpan="10" class="style7">
                                <b>Request a Trailer WITH a Bill of Lading (BOL)</b>
                            </td>
                        </tr>
                        <TR>
                            <TD class="style17" colspan="4">
                                <p align="center">
                                    <font face="Arial">
                                        <b>
                                            <font size="2">Trailer Information</font>
                                        </b>
                                        <font size="2">
                                        </font>
                                    </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" align="right">
                                <b>Trailer Number:</font></b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2">
                                <input name="trailer_no" id="trailer_no" size=20 style="float: left">
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                                <b>Dock:</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12left" colspan="2">
                                <select name="destination_id">
                                    <option value="8">OCC Dock</option>
                                    <option value="9">Gaylord Dock</option>
                                </select>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                                <b>GMI_Order #:</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12left" colspan="2">
                                <input name="GMI_Order" size=20 value="">
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                                <b>GMI_Delivery #:</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12left" colspan="2">
                                <input name="GMI_Delivery" size=20 value="">
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                                <b>GMI_Shipment #:</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12left" colspan="2">
                                <input name="GMI_Shipment" size=20 value="">
                            </TD>
                        </TR>

                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                                <b>Seal Number:</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2">
                                <input name="seal_no" size=20 style="float: left"></font>
                                <font size="2" face="Arial"> </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                                <b>Your Name:</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' colspan="2">
                                <font face="Arial">
                                    <input name="fullname" size=20 style="float: left">
                                </font>
                                <font size="2" face="Arial"> </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                                <b>Pickup Date:</b>
                            </TD>


                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' colspan="2"
                                style="text-align: left">
                                <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
                                <script LANGUAGE="JavaScript">
                                document.write(getCalendarStyles());
                                </script>
                                <script LANGUAGE="JavaScript">
                                var cal0xx = new CalendarPopup("listdiv");
                                cal0xx.showNavigationDropdowns();
                                </script>
                                <?php
                                $pickup_date = isset($_REQUEST["pickup_date"]) ? strtotime($_REQUEST["pickup_date"]) : strtotime(date('m/d/Y'));
                                ?>


                                <input type="text" name="pickup_date" size="11"
                                    value="<?php echo (isset($_REQUEST["pickup_date"]) && $_REQUEST["pickup_date"] != "") ? date('m/d/Y', $pickup_date) : date('m/d/Y') ?>">
                                <a href="#"
                                    onclick="cal0xx.select(document.BOL.pickup_date,'anchor0xx','MM/dd/yyyy'); return false;"
                                    name="anchor0xx" id="anchor0xx"><img border="0" src="images/calendar.jpg"></a>
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="x-small">
                            </td>
                        </tr>


                        <TR>
                            <TD class="style17" colspan="4">
                                <p align="center">
                                    <font face="Arial">
                                        <b>
                                            <font size="2">Item Information</font>
                                        </b>
                                        <font size="2">
                                        </font>
                                    </font>
                            </TD>
                        </TR>
                        <tr>
                            <td colspan="4" class="style17" align="center">
                                <font size="1">Please check the items below if they are being
                                    shipped and enter weights below if known.</font>
                            </td>
                        </tr>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12center">
                                <b>Check
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12center">
                                <b>
                                    Count</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12center">
                                <b>
                                    Weight</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12center">
                                <b>
                                    Item </b>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_1" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="count_1" size=10 style="float: left" id="count_1"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_1" size=10 style="float: left" id="weight_1"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font size="2" face="Arial">
                                    Bales of OCC<input type="hidden" name="item_1" value="HPT-41 Totes">
                                </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_2" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="count_2" size=10 style="float: left" id="count_2"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_2" size=10 style="float: left" id="weight_2"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font size="2" face="Arial">
                                    Gaylord Totes<input type="hidden" name="item_2" value="Gaylord Totes">
                                </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_3" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="count_3" size=10 style="float: left" id="count_3"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_3" size=10 style="float: left" id="weight_3"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font size="2" face="Arial">
                                    Bales of Plastic<input type="hidden" name="item_3" value="Bales of Plastic">
                                </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_4" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="count_4" size=10 style="float: left" id="count_4"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_4" size=10 style="float: left" id="weight_4"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font size="2" face="Arial">
                                    <input type=text size="55"
                                        value="Other: Check box &amp; replace text with item description" name="item_4">
                                </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_5" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="count_5" size=10 style="float: left" id="count_5"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_5" size=10 style="float: left" id="weight_5"
                                        onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font size="2" face="Arial">
                                    <input type=text size="55"
                                        value="Other: Check box &amp; replace text with item description" name="item_5">
                                </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="count_total" size=10 style="float: left">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="order_total" size=10 style="float: left">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font size="2" face="Arial"><strong>TOTALS</strong></font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12center" colspan="4">
                                <input type=submit value="Generate Bill of Lading and Request Trailer">
                            </TD>
                        </TR>
                    </table>
                </form>


                <!---------- END BOL ------------------->

            </td>
            <td width="100">
                &nbsp;
            </td>
            <td valign="top">


                <!--------------- REQUESTED TABLE ---------------->
                <table cellSpacing="1" cellPadding="1" border="0">

                    <tr align="middle">
                        <td colSpan="10" class="style7">
                            <b>View Trailers TO BE PROCESSED</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 150" class="style17" align="center">
                            <b>DATE REQUEST</b>
                        </td>
                        <td style="width: 150" class="style17" align="center">
                            <b>TRAILER #</b>
                        </td>
                        <td class="style5" style="width: 100" align="center">
                            <b>BOL</b>
                        </td>
                        <td align="middle" style="width: 150" class="style16" align="center">
                            <b>REQUESTED BY</b>
                        </td>
                    </tr>


                    <?php
                    $query = "SELECT * FROM loop_transaction WHERE warehouse_id = " . $wid . " AND pa_employee LIKE '' AND cp_employee LIKE '' ORDER BY ID ASC";
                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {

                    ?>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php echo $row["pr_trailer"]; ?>
                        </td>
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php
                                if ($row["bol_filename"] != "")
                                    echo "<a href=files/" . $row["bol_filename"] . " target=_blank>View BOL</a>";
                                ?>
                        </td>
                        <td bgColor="#e4e4e4" class="style3">
                            <?php echo $row["pr_requestby"]; ?></td>
            </td>
        </tr>
        <?php
                    }
    ?>
    </table>
    <!--------------- END REQUESTED TABLE ---------------->
    <br>
    <!--------------- BEGIN IN PROCESS TABLE ---------------->
    <table cellSpacing="1" cellPadding="1" border="0">

        <tr align="middle">
            <td colSpan="10" class="style7">
                <b>View Trailers IN PROCESS</b>
            </td>
        </tr>
        <tr>
            <td style="width: 150" class="style17" align="center">
                <b>DATE REQUEST</b>
            </td>
            <td style="width: 150" class="style17" align="center">
                <b>TRAILER #</b>
            </td>
            <td class="style5" style="width: 100" align="center">
                <b>BOL</b>
            </td>
            <td align="middle" style="width: 150" class="style16" align="center">
                <b>REQUESTED BY</b>
            </td>
        </tr>


        <?php
        $query = "SELECT * FROM loop_transaction WHERE warehouse_id = " . $wid . " AND (pa_employee NOT LIKE '' OR bol_employee NOT LIKE '') AND sort_entered = 0 ORDER BY ID ASC";
        db();
        $res = db_query($query);
        while ($row = array_shift($res)) {

        ?>
        <tr vAlign="center">
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo $row["dt_trailer"]; ?>
            </td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php
                    if ($row["bol_filename"] != "")
                        echo "<a href=files/" . $row["bol_filename"] . " target=_blank>View BOL</a>";
                    ?>
            </td>
            <td bgColor="#e4e4e4" class="style3">
                <?php echo $row["pr_requestby"]; ?></td>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
    <!--------------- END IN PROCESS TABLE ---------------->

    <br>
    <!------------------------- BEGIN PROCESSED TRAILERS ----------------------->
    <form name="rptSearch" action="processedtrailerreporttrl.php" method="POST" target="_blank">
        <input type="hidden" name="action" value="run">
        <input type="hidden" name="warehouse_id" value="<?php echo $wid; ?>">
        <span class="style2">


            <span class="style13"><span class="style15">

                    <table cellSpacing="1" cellPadding="1" border="0" width="550">

                        <tr align="middle">
                            <td colSpan="10" class="style7">
                                <b>View Trailers ALREADY PROCESSED (will appear in a new window)</b>
                            </td>
                        </tr>
                        <tr align="middle">
                            <td colSpan="10" class="style17">



                                <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
                                <script LANGUAGE="JavaScript">
                                document.write(getCalendarStyles());
                                </script>
                                <script LANGUAGE="JavaScript">
                                var cal1xx = new CalendarPopup("listdiv");
                                cal1xx.showNavigationDropdowns();
                                var cal2xx = new CalendarPopup("listdiv");
                                cal2xx.showNavigationDropdowns();
                                </script>
                                <?php
                                $start_date = isset($_REQUEST["start_date"]) ? strtotime($_REQUEST["start_date"]) : strtotime(date('m/d/Y'));
                                $end_date = isset($_REQUEST["end_date"]) ? strtotime($_REQUEST["end_date"]) : strtotime(date('m/d/Y'));
                                ?>


                                <input type="text" name="start_date" size="11"
                                    value="<?php echo (isset($_REQUEST["start_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $start_date) : date('m/01/Y') ?>">
                                <a href="#"
                                    onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;"
                                    name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="x-small">and:
                                    <input type="text" name="end_date" size="11"
                                        value="<?php echo (isset($_REQUEST["end_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $end_date) : date('m/d/Y') ?>">
                                    <a href="#"
                                        onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;"
                                        name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
                                    <input type=radio <?php if ($_REQUEST["reportview"] == "1" || $_REQUEST["reportview"] == "") {
                                                            echo "checked";
                                                        } ?> name="reportview" value="1">Show By Weight
                                    <input type=radio name="reportview" <?php if ($_REQUEST["reportview"] == "0") {
                                                                            echo "checked";
                                                                        } ?> value="0"> Show By Trailer
                            </td>
                        </tr>
                        <tr>
                            <td bgColor="#e4e4e4" class="style12center">
                                &nbsp; <input type="submit" value="Search">
                                <div ID="listdiv"
                                    STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                </div>
                            </td>
                        </tr>
                    </table>
    </form>

    <!------------------ END PROCESSED TRAILERS -------------------->
    <!------------------------- BEGIN FILES----------------------->
    <?php
    include("view_yellow_sheets.php");
    ?>
    <!------------------ END FILES-------------------->

    <br>

    </td>
    <td width="100">
        &nbsp;
    </td>
    <td valign="top">

        <BR>
        <!--------------------- BEGIN QUICK LINKS  ----------------------------------------------->
        <!----------------------- END QUICK LINKS ------------>
    </td>
    </tr>
    </table>







</body>

</html>