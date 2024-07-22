<?php
//require ("inc/header_session.php");
?>

<?php
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


if ($_REQUEST["action"] == "clockin") {
    if ($_REQUEST["worker"] > 0) {

        $sql3ud = "INSERT INTO loop_timeclock (`worker_id` ,`warehouse_id` ,`time_in`, `type`) VALUES ('" . $_REQUEST["worker"] . "', '79', NOW() + INTERVAL 1 HOUR, '" . $_REQUEST["type"] . "')";

        db();
        $result3ud = db_query($sql3ud);


        echo $sql3ud;

        redirect("dashboard_BD_76348234234.php");
    }
}

if ($_REQUEST["action"] == "clockout") {

    $sql3ud = "UPDATE loop_timeclock SET time_out = NOW() + INTERVAL 1 HOUR WHERE id = " . $_REQUEST["id"];

    db();
    $result3ud = db_query($sql3ud);


    redirect("dashboard_BD_76348234234.php");
}
?>
<!DOCTYPE HTML>

<html>

<head>

    <title>BD - Dashboard</title>




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
    for (x = 1; x <= 4; x++) {
        item_total = document.getElementById("weight_" + x)
        total = total + item_total.value * 1
    }
    order_total = document.getElementById("order_total")
    document.getElementById("order_total").value = total.toFixed(0)
}
</script>

<body>




    <!---- TABLE TO FORMAT ----------->
    <table>
        <tr>
            <td>
                <img src="images/BD.jpg">
            </td>
            <td align=center colspan="3">
                <font face="Ariel" size="5">
                    <b>UsedCardboardBoxes.com<br></b>
                    Dashboard Report for:<br>
                    <b><i>Becton Dickinson</i></b>
                    </i>
            </td>
            <td colspan="20" align="right">
                <img src="new_interface_help.gif">
            </td>
        </tr>
        <tr>
            <td valign="top">

                <!--------------- EMPLOYEE TABLE ---------------->
                <form method="post" action="dashboard_BD_76348234234.php">
                    <input type=hidden name="action" value="clockin">
                    <table cellSpacing="1" cellPadding="1" border="0">

                        <tr align="middle">
                            <td colSpan="10" class="style7">
                                <b>WHO'S WORKING?</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 250" class="style17" align="center">
                                <b>NAME</b>
                            </td>
                            <td style="width: 150" class="style17" align="center">
                                <b>TIME IN</b>
                            </td>
                            <td style="width: 150" class="style17" align="center">
                                <b>TYPE</b>
                            </td>
                            <td class="style5" style="width: 100" align="center">
                                <b>LOGOUT</b>
                            </td>

                        </tr>

                        <tr vAlign="center">
                            <td bgColor="#e4e4e4" class="style3" align="center">
                                <select id="worker" name="worker">
                                    <option value="-1">Select Worker</option>
                                    <?php
                                    $query = "SELECT * FROM loop_workers WHERE warehouse_id = 79 AND active = 1 ORDER BY name ASC";
                                    db();
                                    $res = db_query($query);
                                    while ($row = array_shift($res)) {

                                    ?>
                                    <option value="<?php echo $row["id"] ?>"><?php echo $row["name"] ?></option>

                                    <?php
                                    }
                                    ?>
                                </select> <select id="type" name="type">

                                    <option value="Training">Training</option>
                                </select>
                            </td>
                            <?php
                            $date1 = mktime((int) date("H") + 3, (int) date("i"), (int) date("s"), (int) date("m"), (int) date("d"), (int) date("Y"));
                            ?>

                            <td bgColor="#e4e4e4" class="style3" align="center"> <input type=submit value="CLOCK IN">
                            </td>
                            <td bgColor="#e4e4e4" class="style3" align="center">
                            </td>
                            <td bgColor="#e4e4e4" class="style3" align="center">
                            </td>
                        </tr>
                </form>
                <?php
                $query = "SELECT loop_timeclock.id AS A, loop_workers.name AS B, loop_timeclock.time_in AS C, loop_timeclock.type AS D FROM loop_timeclock INNER JOIN loop_workers ON loop_timeclock.worker_id = loop_workers.id WHERE loop_timeclock.time_out = '0000-00-00 00:00:00' AND loop_workers.warehouse_id = 79 ORDER BY loop_timeclock.time_in ASC";
                db();
                $res = db_query($query);
                while ($row = array_shift($res)) {

                ?>



                <form method="post" action="dashboard_BD_76348234234.php">
                    <input type=hidden name="action" value="clockout">
                    <input type=hidden name="id" value="<?php echo $row["A"] ?>">
        <tr vAlign="center">
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo $row["B"]; ?></td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo date('h:i:s A m/d/Y', strtotime($row["C"])); ?>
            </td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo $row["D"]; ?></td>
            <td bgColor="#e4e4e4" align=middle class="style3">
                <input type=submit value="Clock Out">
            </td>
        </tr>
        </form>

        <?php
                }
    ?>
    </table>
    <!--------------- END EMPLOYEE TABLE ---------------->
    <br>
    <!--------------------- BEGIN BOL REQUEST ----------------------------------------------->


    <FORM METHOD="POST" ACTION="BOLpickupsubmitNEW.php">
        <input type=hidden value="79" name="warehouse_id">
        <input type=hidden value="dashboard_BD_76348234234.php" name="return_url">
        <?php
        $query = "SELECT * FROM loop_warehouse WHERE id = 79";
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
                <TD class="style17" colspan="3">
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
                <TD bgColor="#e4e4e4" class="style12">
                    <input name="trailer_no" id="trailer_no" size=20 style="float: left">
                </TD>
            </TR>
            <TR>
                <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                    <b>Seal Number:</b>
                </TD>
                <TD bgColor="#e4e4e4" class="style12">
                    <input name="seal_no" size=20 style="float: left"></font>
                    <font size="2" face="Arial"> </font>
                </TD>
            </TR>
            <TR>
                <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                    <b>Your Name:</b>
                </TD>
                <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR'>
                    <font face="Arial">
                        <input name="fullname" size=20 style="float: left">
                    </font>
                    <font size="2" face="Arial"> </font>
                </TD>
            </TR>


            <TR>
                <TD class="style17" colspan="3">
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
                <td colspan="3" class="style17" align="center">
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
                        <input name="weight_1" size=10 style="float: left" id="weight_1" onchange="update_cart()">
                    </font>
                    <font size="2" face="Arial">
                    </font>
                </TD>
                <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                    <font size="2" face="Arial">
                        Pallets of Boxes<input type="hidden" name="item_1" value="Pallets of Boxes">
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
                        <input name="weight_2" size=10 style="float: left" id="weight_2" onchange="update_cart()">
                    </font>
                    <font size="2" face="Arial">
                    </font>
                </TD>
                <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                    <font size="2" face="Arial">
                        <input type=text size="55" value="Other: Check box &amp; replace text with item description"
                            name="item_2">
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
                        <input name="weight_3" size=10 style="float: left" id="weight_3" onchange="update_cart()">
                    </font>
                    <font size="2" face="Arial">
                    </font>
                </TD>
                <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                    <font size="2" face="Arial">
                        <input type=text size="55" value="Other: Check box &amp; replace text with item description"
                            name="item_3">
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
                        <input name="weight_4" size=10 style="float: left" id="weight_4" onchange="update_cart()">
                    </font>
                    <font size="2" face="Arial">
                    </font>
                </TD>
                <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                    <font size="2" face="Arial">
                        <input type=text size="55" value="Other: Check box &amp; replace text with item description"
                            name="item_4">
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
                        <input name="order_total" size=10 style="float: left">
                    </font>
                    <font size="2" face="Arial">
                    </font>
                </TD>
                <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                    <font size="2" face="Arial"><strong>Total Weight</strong></font>
                </TD>
            </TR>
            <TR>
                <TD bgColor="#e4e4e4" class="style12center" colspan="3">
                    <input type=submit value="Generate Bill of Lading and Reqest Trailer">
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
                    <b>DOCK</b>
                </td>
                <td class="style5" style="width: 100" align="center">
                    <b>BOL</b>
                </td>
                <td align="middle" style="width: 150" class="style16" align="center">
                    <b>REQUESTED BY</b>
                </td>
            </tr>


            <?php
            $query = "SELECT * FROM loop_transaction WHERE warehouse_id = 79 AND pa_employee LIKE '' AND cp_employee LIKE '' ORDER BY ID ASC";
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
                    <?php echo $row["pr_dock"]; ?>
                </td>
                <td bgColor="#e4e4e4" class="style3" align="center">
                    <?php
                        if ($row["bol_filename"] != "")
                            echo "<a href=files/" . $row["bol_filename"] . ">View BOL</a>";
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
                <b>DOCK</b>
            </td>
            <td align="middle" style="width: 150" class="style16" align="center">
                <b>REQUESTED BY</b>
            </td>
        </tr>


        <?php
    $query = "SELECT * FROM loop_transaction WHERE warehouse_id = 79 AND (pa_employee NOT LIKE '' OR bol_employee NOT LIKE '') AND sort_entered = 0 ORDER BY ID ASC";
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
                <?php echo $row["pr_dock"]; ?>
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
        <input type="hidden" name="warehouse_id" value="79">
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
    <br>
    <!------------------------- BEGIN EMPLOYEE REPORT ----------------------->
    <form name="emprpt" action="report_staffing_timeclock.php" method="POST" target="_blank">
        <input type="hidden" name="action" value="run">
        <input type="hidden" name="warehouse_id" value="79">
        <span class="style2">


            <span class="style13"><span class="style15">

                    <table cellSpacing="1" cellPadding="1" border="0" width="550">

                        <tr align="middle">
                            <td colSpan="10" class="style7">
                                <b>View Employee Timesheets (will appear in a new window)</b>
                            </td>
                        </tr>
                        <tr align="middle">
                            <td colSpan="10" class="style17">



                                <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
                                <script LANGUAGE="JavaScript">
                                document.write(getCalendarStyles());
                                </script>
                                <script LANGUAGE="JavaScript">
                                var cal3xx = new CalendarPopup("listdiv");
                                cal3xx.showNavigationDropdowns();
                                var cal4xx = new CalendarPopup("listdiv");
                                cal4xx.showNavigationDropdowns();
                                </script>
                                <?php
                            $start_date = isset($_REQUEST["start_date"]) ? strtotime($_REQUEST["start_date"]) : strtotime(date('m/d/Y'));
                            $end_date = isset($_REQUEST["end_date"]) ? strtotime($_REQUEST["end_date"]) : strtotime(date('m/d/Y'));
                            ?>


                                <input type="text" name="start_date" size="11"
                                    value="<?php echo (isset($_REQUEST["start_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $start_date) : date('m/01/Y') ?>">
                                <a href="#"
                                    onclick="cal3xx.select(document.emprpt.start_date,'anchor3xx','MM/dd/yyyy'); return false;"
                                    name="anchor3xx" id="anchor3xx"><img border="0" src="images/calendar.jpg"></a>
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="x-small">and:
                                    <input type="text" name="end_date" size="11"
                                        value="<?php echo (isset($_REQUEST["end_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $end_date) : date('m/d/Y') ?>">
                                    <a href="#"
                                        onclick="cal4xx.select(document.emprpt.end_date,'anchor4xx','MM/dd/yyyy'); return false;"
                                        name="anchor4xx" id="anchor4xx"><img border="0" src="images/calendar.jpg"></a>
                                    <select id="worker" name="worker">
                                        <option value="-1">ALL</option>
                                        <?php
                                    $query = "SELECT * FROM loop_workers WHERE warehouse_id = 79 AND active = 1 ORDER BY name ASC";
                                    db();
                                    $res = db_query($query);
                                    while ($row = array_shift($res)) {

                                    ?>
                                        <option value="<?php echo $row["id"] ?>"><?php echo $row["name"] ?></option>

                                        <?php
                                    }
                                    ?>
                                    </select>
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

    <!------------------ END EMPLOYEE REPORT-------------------->

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





    <!------------- McCormick Trailer Report ---------------->

    <?php


if ($_REQUEST["action"] == 'run') {

    $start_date = date('Ymd', $start_date);
    $end_date = date('Ymd', $end_date + 86400);

    if ($start_date > $end_date) {
        echo "<font size=4 color=red>Error: End Date before Start Date</font>";
    }

?>

    <table width=1400>
        <tr>
            <td>

                <input type=hidden name="reportview" value="<?php echo $_REQUEST["reportview"]; ?>">
                <input type=hidden name="start_date" value="<?php echo $_REQUEST["start_date"]; ?>">
                <input type=hidden name="end_date" value="<?php echo $_REQUEST["end_date"]; ?>">
                <input type=hidden name="action" value="run">
                <table cellSpacing="1" cellPadding="1" width="550" border="0">

                    <tr align="middle">
                        <td colSpan="10" class="style7">
                            <b>McCORMICK TRAILER REPORT FROM <?php echo $_REQUEST["start_date"]; ?> -
                                <?php echo $_REQUEST["end_date"]; ?></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 150" class="style17" align="center">
                            <b>DATE REQUEST</b></font>
                        </td>
                        <td style="width: 100" class="style17" align="center">
                            <b>TRAILER #</b></font>
                        </td>
                        <td style="width: 50" class="style17" align="center">
                            <b>DOCK</b></font>
                        </td>
                        <td class="style5" style="width: 150" align="center">
                            <b>REQUESTED BY</b>
                        </td>
                        <td align="middle" style="width: 100" class="style16" align="center">
                            <b>VALUE</b>
                        </td>
                        <td align="middle" style="width: 100" class="style16" align="center">
                            <b>STATUS</b>
                        </td>
                    </tr>


                    <?php
                    $query = "SELECT * FROM loop_transaction WHERE warehouse_id = 79 AND";
                    if ($_REQUEST["start_date"] != "") {
                        $query .= " pr_requestdate BETWEEN '" . $_REQUEST["start_date"] . "'";
                    }
                    if ($_REQUEST["end_date"] != "") {
                        $query .= " AND '" . $_REQUEST["end_date"] . "' ORDER BY pr_requestdate DESC";
                    }

                    $grandtotal = 0;
                    db();
                    $res = db_query($query);
                    while ($row = array_shift($res)) {

                    ?>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <a
                                href="http://loops.usedcardboardboxes.com/mccormickdashboard_76345679315467990452.php?action=run&start_date=<?php echo htmlspecialchars($_REQUEST["start_date"]); ?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&reportview=<?php echo $_REQUEST["reportview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1"><?php echo $row["dt_trailer"]; ?></a>
                        </td>
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php echo $row["pr_dock"]; ?></td>
                        <td bgColor="#e4e4e4" class="style3">
                            <?php echo $row["pr_requestby"]; ?></td>
                        <td bgColor="#e4e4e4" class="style3" align="right">

                            <?php
                                $gbw = 0;
                                $vob = 0;

                                $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_boxes_sort.trans_rec_id = " . $row["id"];
                                db();
                                $dt_view_res = db_query($dt_view_qry);

                                while ($dt_view_row = array_shift($dt_view_res)) {
                                    if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
                                        $gbw += $dt_view_row["bweight"] * $dt_view_row["boxgood"];
                                        $vob += $dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"];
                                    }
                                }

                                $voo = 0;

                                $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $row["id"] . " AND loop_boxes.isbox LIKE 'N'";
                                db();
                                $dt_view_res = db_query($dt_view_qry);

                                while ($dt_view_row = array_shift($dt_view_res)) {
                                    if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
                                        $voo += $dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"];
                                    }
                                }
                                ?>
                            $<?php
                                    $grandtotal += number_format($vob + $voo + $row["othercharge"] + $row["freightcharge"], 2);
                                    echo number_format($vob + $voo + $row["othercharge"] + $row["freightcharge"], 2); ?>



                        </td>
                        <td bgColor="#e4e4e4" class="style3" align="center">
                            <?php
                                if ($row["pmt_entered"] != 0) {
                                    echo "Paid";
                                } elseif ($row["sort_entered"] == 1) {
                                    echo "Sorted";
                                } elseif ($row["pa_employee"] != "") {
                                    echo "In Process";
                                } else {
                                    echo "Requested";
                                }
                                ?>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td bgColor="#e4e4e4" class="style3" colspan="4" align="right">
                            <b>TOTAL</b></font>
                        </td>
                        <td bgColor="#e4e4e4" class="style3" align="right">
                            <b><?php echo $grandtotal; ?></b></font>
                        </td>
                        <td bgColor="#e4e4e4" class="style3">
                        </td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <?php
        }

        if ($_REQUEST["trailer"] > 0) {
            $dt_view_qry = "SELECT * FROM loop_transaction WHERE id = " . $_REQUEST["trailer"];
            db();
            $dt_view_res = db_query($dt_view_qry);

            $dt_view_trl_row = array_shift($dt_view_res)
            ?>
                <table cellSpacing="1" cellPadding="1" border="0" width="800">
                    <tr align="middle">
                        <td class="style7" colspan="10" style="height: 16px"><strong>SORT REPORT FOR TRAILER
                                #<?php echo $dt_view_trl_row["pr_trailer"]; ?></strong></td>
                    </tr>
                    <tr align="middle">
                        <td bgColor="88EEEE" colspan="10" class="style17"><strong>BOXES</strong></td>
                    </tr>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" class="style12">Good Boxes</td>
                        <td bgColor="#e4e4e4" class="style12">Bad Boxes</td>
                        <td bgColor="#e4e4e4" width="350" class="style12">Description</td>
                        <td bgColor="#e4e4e4" class="style12">Box Weight</td>
                        <td bgColor="#e4e4e4" class="style12">Value Per Box</td>
                        <td bgColor="#e4e4e4" class="style12">Value of Boxes</td>
                    </tr>
                    <?php
                    $gb = 0;
                    $bb = 0;
                    $gbw = 0;
                    $vob = 0;


                    $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_boxes_sort.trans_rec_id = " . $_REQUEST["trailer"];
                    db();
                    $dt_view_res = db_query($dt_view_qry);

                    while ($dt_view_row = array_shift($dt_view_res)) {

                        if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
                    ?>
                    <tr>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["boxgood"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["boxbad"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12left">
                            <?php echo $dt_view_row["blength"]; ?> <?php echo $dt_view_row["blength_frac"]; ?> x
                            <?php echo $dt_view_row["bwidth"]; ?> <?php echo $dt_view_row["bwidth_frac"]; ?> x
                            <?php echo $dt_view_row["bdepth"]; ?> <?php echo $dt_view_row["bdepth_frac"]; ?>
                            <?php echo $dt_view_row["bdescription"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["bweight"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["sort_boxgoodvalue"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <?php echo number_format($dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"], 2); ?>
                        </td>
                    </tr>


                    <?php
                            $gb += $dt_view_row["boxgood"];
                            $bb += $dt_view_row["boxbad"];
                            $gbw += $dt_view_row["bweight"] * $dt_view_row["boxgood"];
                            $vob += $dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"];
                        }
                    } ?>

                    <tr>
                        <td bgColor="#e4e4e4" class="style12right"><strong><?php echo $gb; ?></strong></td>
                        <td bgColor="#e4e4e4" class="style12right"><strong><?php echo $bb; ?></strong></td>
                        <td bgColor="#e4e4e4" class="style12"><strong>BOX TOTALS</strong></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong><?php echo number_format($gbw, 2); ?></strong>
                        </td>
                        <td bgColor="#e4e4e4" class="style12"> </td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($vob, 2); ?></strong>
                        </td>
                    </tr>

                    <tr align="middle">
                        <td bgColor="88EEEE" colspan="10" class="style17"><strong>OTHER ITEMS</strong></td>
                    </tr>
                    <tr vAlign="center">
                        <td bgColor="#e4e4e4" colspan="2" class="style12">Quantity</td>
                        <td bgColor="#e4e4e4" class="style12left">Description</td>
                        <td bgColor="#e4e4e4" class="style12right">Units</td>
                        <td bgColor="#e4e4e4" class="style12right">Value Per Unit</td>
                        <td bgColor="#e4e4e4" class="style12right">Total Value</td>
                    </tr>
                    <?php


                    $voo = 0;


                    $dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $_REQUEST["trailer"] . " AND loop_boxes.isbox LIKE 'N'";
                    db();
                    $dt_view_res = db_query($dt_view_qry);

                    while ($dt_view_row = array_shift($dt_view_res)) {

                        if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
                    ?>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="2" class="style12right"><?php echo $dt_view_row["boxgood"]; ?>
                        </td>
                        <td bgColor="#e4e4e4" class="style12left">
                            <?php echo $dt_view_row["bdescription"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["bunit"]; ?></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <?php echo number_format($dt_view_row["sort_boxgoodvalue"], 3); ?></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <?php echo number_format($dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"], 2); ?>
                        </td>
                    </tr>


                    <?php
                            $voo += $dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"];
                        }
                    } ?>

                    <tr>

                        <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>OTHER ITEM TOTALS</strong></td>

                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($voo, 2); ?></strong>
                        </td>
                    </tr>
                    <tr align="middle">
                        <td bgColor="88EEEE" colspan="10" class="style17"><strong>TOTALS</strong></td>
                    </tr>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>GROSS EARNINGS</strong></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($vob + $voo, 2); ?></strong>
                        </td>
                    </tr>
                    <?php if ($dt_view_trl_row["othercharge"] != 0) { ?>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="5" class="style12right">
                            <strong><?php echo $dt_view_trl_row["otherdetails"]; ?></strong>
                        </td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($dt_view_trl_row["othercharge"], 2); ?></strong>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>FREIGHT</strong></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($dt_view_trl_row["freightcharge"], 2); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>TOTAL EARNED</strong></td>
                        <td bgColor="#e4e4e4" class="style12right">
                            <strong>$<?php echo number_format($vob + $voo + $dt_view_trl_row["othercharge"] + $dt_view_trl_row["freightcharge"], 2); ?></strong>
                        </td>
                    </tr>




                    <?php } ?>


            </td>
        </tr>
    </table>


</body>

</html>