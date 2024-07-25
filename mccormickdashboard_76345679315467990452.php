<?php
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
require("inc/functions_mysqli.php");


if ($_REQUEST["action"] == "clockin") {
    if ($_REQUEST["worker"] > 0) {

        $sql3ud = "INSERT INTO loop_timeclock (`worker_id` ,`warehouse_id` ,`time_in`, `type`, `ipaddress`) VALUES ('" . $_REQUEST["worker"] . "', '15', NOW() + INTERVAL 1 HOUR, '" . $_REQUEST["type"] . "', '" . $_SERVER["REMOTE_ADDR"] . "')";

        db();
        $result3ud = db_query($sql3ud);


        echo $sql3ud;

        redirect("mccormickdashboard_76345679315467990452.php");
    }
}

if ($_REQUEST["action"] == "clockout") {

    $sql3ud = "UPDATE loop_timeclock SET time_out = NOW() + INTERVAL 1 HOUR, ipaddress_clkout = '" . $_SERVER["REMOTE_ADDR"] . "' WHERE id = " . $_REQUEST["id"];

    db();
    $result3ud = db_query($sql3ud);


    redirect("mccormickdashboard_76345679315467990452.php");
}

function mccormickwarehousepage(): string
{
    return "mccormickdashboard_76345679315467990452.php";
}


if ($_REQUEST["action"] == "confirm") {
    $str_email = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td><p align=\"center\"><a href=\"http://loops.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"http://loops.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";
    $str_email .= "Dear UsedCardboardBoxes.com,<br><br>This email is to confirm delivery of Trailer # " . $_REQUEST["trailer_no"] . " from McCormick & Company. Details below:<br><br>";
    $str_email .= "McCormick Dock #:  <b>" . $_REQUEST["dock"] . "</b> <br>";
    $str_email .= "Trailer #:  <b>" . $_REQUEST["trailer_no"] . "</b> <br><br>";
    $str_email .= "Delivered to:<br><b>Used Cardboard Boxes<br>350 Clubhouse Rd<br>Suite F-G<br>Hunt Valley, MD 21031</b><br>";
    $str_email .= "Best Regards<br>";
    $str_email .= "UsedCardboardBoxes.com<br>";
    $str_email .= "</font></td></tr><tr><td><p align=\"center\"><img width=\"650\" height=\"87\" src=\"http://loops.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr></table></body></html>";

    $recipient = "davidkrasnow@usedcardboardboxes.com, martymetro@usedcardboardboxes.com";
    $subject = "Notification: Trailer Delivered to UCB - MC";
    $mailheadersadmin = "From: UsedCardboardBoxes.com <operations@UsedCardboardBoxes.com>\n";
    $mailheadersadmin .= "MIME-Version: 1.0\r\n";
    $mailheadersadmin .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $resp = sendemail_php_function(null, '', $recipient, "", "", "ucbemail@usedcardboardboxes.com", "Operations Usedcardboardboxes", "operations@UsedCardboardBoxes.com", $subject, $str_email);

    $sql3ud = "UPDATE loop_transaction SET bol_file = 'No BOL', bol_employee = 'UCB-MC', bol_date = '" . date("m/d/Y") . "', pa_pickupdate = '" . date("m/d/Y") . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);

    $sql3ud = "UPDATE loop_transaction SET cp_notes = 'Delivery Confirmed via Warehouse Dashboard', cp_employee = 'UCB-MC', cp_date = '" . date("m/d/Y") . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);
    //
    $ucbunloaded_note = "Entered via warehouse[MC] dashboard";
    $sql3ud = "UPDATE loop_transaction SET ucbunloaded_flg = 1, ucbunloaded_note = '" . $ucbunloaded_note . "', ucbunloaded_by= '" . $_COOKIE['userinitials'] . "', ucbunloaded_dt = '" . date("Y-m-d H:i:s") . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);
    //
    mccormickwarehousepage();
}

if ($_REQUEST["action"] == "undodelivery") {
    $sql3ud = "UPDATE loop_transaction SET ucbunloaded_flg = 0, ucbunloaded_note ='', area_unloaded='', ucbunloaded_by ='', ucbunloaded_dt = null, cp_notes= '', cp_employee = '', cp_date ='' WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);
    //
    mccormickwarehousepage();
}

if ($_REQUEST["action"] == "undorecycling") {
    $sql3ud = "UPDATE loop_transaction SET pr_recycling = 0, mark_as_recycling_by = '', mark_as_recycling_on = '' WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);

    mccormickwarehousepage();
}


if ($_REQUEST["action"] == "recycling") {
    $sql3ud = "UPDATE loop_transaction SET pr_recycling = 1, mark_as_recycling = '1', mark_as_recycling_by = '" . $_COOKIE['employeeid'] . "', mark_as_recycling_on ='" . date("Y-m-d H:i:s") . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);

    mccormickwarehousepage();
}

if ($_REQUEST["action"] == "undoucblot") {
    $sql3ud = "UPDATE loop_transaction SET pr_ucblot = 0, pr_ucblot_note='', pr_ucblot_by='' WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);

    mccormickwarehousepage();
}

if ($_REQUEST["action"] == "undoucbdockdoor") {
    //$sql3ud = "UPDATE loop_transaction SET srt_dockdoors_flg = 0, srt_dock_doors = '' , srt_ucbdockdoor_note = '', srt_ucbdockdoor_by = '' WHERE id = '". $_REQUEST["conf_id"] ."'";
    //$result3ud = db_query($sql3ud,db() );
    //

    $sql3ud = "UPDATE loop_transaction SET srt_dockdoors_flg = 0, srt_dock_doors = '' , srt_ucbdockdoor_note = '', srt_ucbdockdoor_by = '', cp_notes = '', bol_employee = '', pr_recycling = 0, pr_ucblot = 0, srt_dockdoors_flg=0 WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);
    //
    mccormickwarehousepage();
}

if ($_REQUEST["action"] == "ucblot") {
    $ucblot_dt = date("Y-m-d H:i:s");
    $pr_ucblot_note = "Entered via warehouse [MC] dashboard";
    //
    $sql3ud = "UPDATE loop_transaction SET pr_ucblot = 1, pr_ucblot_by='" . $_COOKIE['userinitials'] . "', pr_ucblot_dt='" . $ucblot_dt . "' , pr_ucblot_note='" . $pr_ucblot_note . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);

    mccormickwarehousepage();
}
if ($_REQUEST["action"] == "ucbunloaded") {
    $ucbunloaded_note = "Entered from warehouse[MC] dashboard";
    $sql3ud = "UPDATE loop_transaction SET ucbunloaded_flg = 1, ucbunloaded_note = '" . $ucbunloaded_note . "', ucbunloaded_by= '" . $_COOKIE['userinitials'] . "', ucbunloaded_dt = '" . date("Y-m-d H:i:s") . "' WHERE id = '" . $_REQUEST["conf_id"] . "'";
    db();
    $result3ud = db_query($sql3ud);

    mccormickwarehousepage();
}
?>
<!DOCTYPE HTML>

<html>

<head>

    <title>McCormick HVP - Hunt Valley, MD - Dashboard</title>




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
        var x;
        var total = 0;
        var order_total;
        for (x = 1; x <= 10; x++) {
            item_total = document.getElementById("weight_" + x);
            total = total + item_total.value * 1;
        }
        //alert('total -> '+total)
        order_total = document.getElementById("order_total");
        document.getElementById("order_total").value = total.toFixed(0);
    }

    function generateBolTrailer() {
        var trailer_no = document.getElementById('trailer_no');
        var ddDock = document.getElementById('dock');
        var fullname = document.getElementById('fullname');

        //alert('trailer_no ->'+trailer_no.value+' / ddDock -> '+ddDock.value+' / '+' / fullname -> '+fullname.value)
        if (trailer_no.value == '') {
            alert('Trailer # is required for a trailer swap request.');
            trailer_no.focus();
            return false;
        }

        if (ddDock.value == '-') {
            alert('Dock is required for a trailer swap request.');
            ddDock.focus();
            return false;
        }

        if (fullname.value == '') {
            alert('Name is required for a trailer swap request.');
            fullname.focus();
            return false;
        }

        show_loading();

        document.frmSubmitBOL.submit();
    }

    function show_loading() {
        document.getElementById('display_loading').style.display = 'block';
    }

    function remove_loading() {
        document.getElementById('display_loading').style.display = 'none';
    }
</script>
<style type="text/css">
    #display_loading {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 250px;
        padding-left: 550px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);

    }

    .white_content {
        display: none;
        position: absolute;
        padding: 5px;
        border: 2px solid black;
        background-color: white;
        z-index: 1002;
        overflow: auto;
    }
</style>

<body>

    <div id="display_loading">
        <font color="black"></font><img src="images/wait_animated.gif" height="50px" width="50px">
    </div>
    <div id="light" class="white_content"> </div>


    <!---- TABLE TO FORMAT ----------->
    <table>
        <tr>
            <td>
                <img src="mccormick.jpg">
            </td>
            <td align=center colspan="3">
                <font face="Ariel" size="5">
                    <b>UsedCardboardBoxes.com<br></b>
                    Dashboard Report for:<br>
                    <b><i>McCormick & Company, Inc. - HVP</i></b>
                    </i>
            </td>
            <td colspan="20" align="right">
                <img src="new_interface_help.gif">
            </td>
        </tr>
        <tr>
            <td valign="top">
                <!--------------------- BEGIN BOL REQUEST ----------------------------------------------->
                <FORM METHOD="POST" ACTION="BOLpickupsubmit.php" name="frmSubmitBOL" id="frmSubmitBOL">
                    <!-- <FORM METHOD="POST" ACTION="#"> -->

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
                                <b>Dock:</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12left">
                                <select name="dock" id="dock">
                                    <option value="-">--- Please Select ---</option>
                                    <option value="B">Dock B</option>
                                    <option value="C">Dock C</option>
                                    <option value="T">Trash Room</option>
                                    <option value="D">Drums</option>
                                </select>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                                <b>Seal Number:</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <input name="seal_no" id="seal_no" size=20 style="float: left"></font>
                                <font size="2" face="Arial"> </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12" colspan="2" style="text-align: right">
                                <b>Your Name:</b>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR'>
                                <font face="Arial">
                                    <input name="fullname" id="fullname" size=20 style="float: left">
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
                                    Pallets of Berry Plastic Boxes<input type="hidden" name="item_1" value="Pallets of Berry Plastic Boxes">
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
                                <font face="Arial" size="2">Gaylord Boxes Flattened</font><input type="hidden" name="item_2" value="Gaylord Boxes Flattened">
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
                                <font face="Arial" size="2">Gaylord Boxes with Spice Liner Bags
                                </font><input type="hidden" name="item_3" value="Gaylord Boxes with Spice Liner Bags">
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
                                <font face="Arial" size="2">Gaylord Boxes with Loose Boxes </font><input type="hidden" name="item_4" value="Gaylord Boxes with Loose Boxes">
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
                                    <input name="weight_5" size=10 style="float: left" id="weight_5" onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font face="Arial" size="2">Bales of Supersacks </font><input type="hidden" name="item_5" value="Bales of Supersacks">
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_6" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_6" size=10 style="float: left" id="weight_6" onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font face="Arial" size="2">Bales of Shrink Film </font><input type="hidden" name="item_6" value="Bales of Shrink Film">
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_7" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_7" size=10 style="float: left" id="weight_7" onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font face="Arial" size="2">Bales of OCC</font>
                                <input type="hidden" size="55" value="Bales of OCC" name="item_7">
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_11" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_11" size=10 style="float: left" id="weight_11" onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font face="Arial" size="2">Drums</font>
                                <input type="hidden" size="55" value="Drums" name="item_11">
                            </TD>
                        </TR>

                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_8" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_8" size=10 style="float: left" id="weight_8" onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font size="2" face="Arial">
                                    <input type=text size="55" value="Other: Check box &amp; replace text with item description" name="item_8">
                                </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_9" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_9" size=10 style="float: left" id="weight_9" onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font size="2" face="Arial">
                                    <input type=text size="55" value="Other: Check box &amp; replace text with item description" name="item_9">
                                </font>
                            </TD>
                        </TR>
                        <TR>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <b>
                                        <font size="2"><input name="check_10" type="checkbox" value="1"></font>
                                    </b>
                                    <font size="2"> </font>
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12">
                                <font face="Arial">
                                    <input name="weight_10" size=10 style="float: left" id="weight_10" onchange="update_cart()">
                                </font>
                                <font size="2" face="Arial">
                                </font>
                            </TD>
                            <TD bgColor="#e4e4e4" class="style12" CLASS='TBL_ROW_HDR' style="text-align: left">
                                <font size="2" face="Arial">
                                    <input type=text size="55" value="Other: Check box &amp; replace text with item description" name="item_10">
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
                                    <input name="order_total" id="order_total" size=10 style="float: left">
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
                                <!-- <input type=submit value="Generate Bill of Lading and Request Trailer"> -->
                                <input type="button" name="btnSubmitBOL" id="btnSubmitBOL" value="Generate Bill of Lading and Request Trailer" onclick="generateBolTrailer()">
                            </TD>
                        </TR>
                    </table>
                </form>


                <!---------- END BOL ------------------->

            </td>
            <td width="100">&nbsp; </td>
            <td valign="top">
                <!--------------- EMPLOYEE TABLE ---------------->
                <form onsubmit="return clockin_form_submit();" method="post" action="mccormickdashboard_76345679315467990452.php">
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
                            <td style="width: 150" class="style17" align="center"> <b>TYPE</b></td>
                            <td style="width: 150" class="style17" align="center"> <b>IP</b></td>
                            <td class="style5" style="width: 100" align="center">
                                <b>LOGOUT</b>
                            </td>

                        </tr>

                        <tr vAlign="center">
                            <td bgColor="#e4e4e4" class="style3" align="center">
                                <select id="worker" name="worker">
                                    <option value="-1">Select Worker</option>
                                    <?php
                                    //$query = "SELECT * FROM loop_workers WHERE warehouse_id = 15 AND active = 1 ORDER BY name ASC";
                                    $this_warehouse_id = 15;
                                    $query = " SELECT * FROM loop_workers WHERE warehouse_id = " . $this_warehouse_id . " and active = 1 and ";
                                    $query .= " id not in (select worker_id from loop_timeclock where warehouse_id = " . $this_warehouse_id . " and time_out = '0000-00-00 00:00:00') ORDER BY name ASC";
                                    db();
                                    $res = db_query($query);
                                    while ($row = array_shift($res)) {

                                    ?>
                                        <option value="<?php echo $row["id"] ?>"><?php echo $row["name"] ?></option>

                                    <?php
                                    }
                                    ?>
                                </select>
                                <select id="type" name="type">

                                    <option value="McC_Baling">Trash Room</option>
                                </select>
                            </td>
                            <?php
                            //$date1=mktime(date("H")+3, date("i"), date("s"), date("m"), date("d"), date("Y")); 
                            $date1 = mktime(
                                (int) date("H") + 3,
                                (int) date("i"),
                                (int) date("s"),
                                (int) date("m"),
                                (int) date("d"),
                                (int) date("Y")
                            );

                            ?>
                            <td bgColor="#e4e4e4" class="style3" align="center"> <input type=submit value="CLOCK IN">
                            </td>
                            <td bgColor="#e4e4e4" class="style3" align="center">
                            </td>
                            <td bgColor="#e4e4e4" class="style3" align="center">
                            </td>
                            <td bgColor="#e4e4e4" class="style3" align="center">
                            </td>
                        </tr>
                </form>
                <?php
                $query = "SELECT loop_timeclock.id AS A, loop_workers.name AS B, loop_timeclock.time_in AS C, loop_timeclock.type AS D, loop_timeclock.ipaddress AS IP FROM loop_timeclock INNER JOIN loop_workers ON loop_timeclock.worker_id = loop_workers.id WHERE loop_timeclock.time_out = '0000-00-00 00:00:00' AND loop_workers.warehouse_id = 15 ORDER BY loop_timeclock.time_in ASC";
                db();
                $res = db_query($query);
                while ($row = array_shift($res)) {

                ?>



                    <form onsubmit="return clockout_form_submit();" method="post" action="mccormickdashboard_76345679315467990452.php">
                        <input type=hidden name="action" value="clockout">
                        <input type=hidden name="id" value="<?php echo $row["A"] ?>">
        <tr vAlign="center">
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo $row["B"]; ?>
            </td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo date('h:i:s A m/d/Y', strtotime($row["C"])); ?>
            </td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <?php echo $row["D"]; ?>
            </td>
            <td bgColor="#e4e4e4" class="style3" align="center">
                <a href="http://whatismyipaddress.com/ip/<?php echo $row[" IP"]; ?>" target="_blank">
                    <?php echo $row["IP"]; ?>
            </td>
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

    <!--------------- INBOUND TABLES START ---------------->
    <?php
    function mccormickdashboardpage(): string
    {
        return "mccormickdashboard_76345679315467990452.php";
    }
    //$warehouse_id_list_str  = "15, 79, 32, 185, 111, 738 ,899 ,1191 ,1027 ,747 ,1514 ,1806 ,1472 ,1473 ,1527 ,1503 ,1972, 1491, 2134 ,2343, 2449, 2636, 2609"; 
    //$warehouse_id_list_str2 = "15 ,79 ,32 ,185 ,111 ,738 ,899 ,1191 ,1027 ,747 ,1514 ,1806, 1472 ,1473 ,1527 ,1503 ,1972, 1491, 2134, 2343, 2449, 2636, 2609";

    $warehouse_id_list_str  = "15";
    $warehouse_id_list_str2 = "15";

    $location_address = "Hunt Valley Production";

    $urlRefresh = mccormickdashboardpage();

    $get_all_red_row_cnt = 0;
    $HV_red_row_loop_ids_str = "";
    $wh_name = "MC";
    ?>
    <script type="text/javascript">
        function display_file(filename, formtype) {
            document.getElementById("light").innerHTML =
                "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" +
                formtype + "</center><br/> <embed src='" + filename + "' width='800' height='800'>";
            document.getElementById('light').style.display = 'block';
            document.getElementById('fade').style.display = 'block';

            document.getElementById('light').style.left = '200px';
            document.getElementById('light').style.top = 50 + 'px';

        }

        function fn_delivered_to_dock(trailer, recid, editflg, wh_name) {
            console.log("ok");
            selectobject = document.getElementById("dockdoor_" + recid);

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');
                    document.getElementById("light_dd").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_dd').style.display = 'block';
                    //document.getElementById('fade').style.display='block';

                    document.getElementById('light_dd').style.left = (n_left - 300) + 'px'; //(n_left - 300) + 'px';
                    document.getElementById('light_dd').style.height = 370 + 'px';
                    document.getElementById('light_dd').style.width = 600 + 'px';
                    document.getElementById('light_dd').style.top = n_top + 20 + 'px';
                }
            }

            xmlhttp.open("POST", "mccormickdashboard_dock_door_delivered.php?recid=" + recid + "&trailer=" + trailer +
                "&dd_flg=1" + "&editflg=" + editflg + "&wh_name=" + wh_name, true);
            xmlhttp.send();
        }

        function confirmationUcblot(a, b, c) {
            var answer = confirm("Confirm Trailer #" + a + " is UCB Lot?")
            if (answer) {
                window.location = "<?php echo mccormickdashboardpage(); ?>?action=ucblot&conf_id=" + b + "&trailer_no=" + a +
                    "&dock=" + c;
            } else {
                alert("Cancelled");
            }
        }

        function confirmationRecycling(a, b, c) {
            var answer = confirm("Confirm Trailer #" + a + " is recycling?")
            if (answer) {
                window.location = "<?php echo mccormickdashboardpage() ?>?action=recycling&conf_id=" + b + "&trailer_no=" +
                    a +
                    "&dock=" + c;
            } else {
                alert("Cancelled");
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

        function save_dockdoor_val(recid) {
            var srt_dock_doors = document.getElementById("srt_dock_doors").value;
            var warehouse_id = document.getElementById("warehouse_id").value;
            var wa_dd_save = document.getElementById("wa_dd_save").value;
            var wh_name = document.getElementById("wh_name").value;
            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("DockDoor added successfully");
                    document.getElementById('light_dd').style.display = 'none';
                    //document.getElementById('fade').style.display='none';
                    window.location = "<?php echo mccormickwarehousepage() ?>";
                    //$('#indockdoor').load(window.location.href + '#indockdoor');
                }
            }
            xmlhttp.open("POST", "mccormickdashboard_dock_door_delivered.php?updatedockdoor=1&wa_dd_save=" + wa_dd_save +
                "&srt_dock_doors=" + srt_dock_doors + "&warehouse_id=" + warehouse_id + "&rec_id=" + recid +
                "&wh_name=" + wh_name, true);
            xmlhttp.send();

        }

        function confirmationUnloaded_new(trailer, recid, dock, warehouse_id) {
            selectobject = document.getElementById("btnDelivered" + recid);

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    n_left = f_getPosition(selectobject, 'Left');
                    n_top = f_getPosition(selectobject, 'Top');
                    document.getElementById("light_dd").innerHTML = xmlhttp.responseText;
                    document.getElementById('light_dd').style.display = 'block';
                    //document.getElementById('fade').style.display='block';

                    document.getElementById('light_dd').style.left = (n_left - 400) + 'px'; //(n_left - 300) + 'px';
                    document.getElementById('light_dd').style.height = 250 + 'px';
                    document.getElementById('light_dd').style.width = 450 + 'px';
                    document.getElementById('light_dd').style.top = n_top + 20 + 'px';
                }
            }

            xmlhttp.open("POST", "mccormickdashboard_unloaded_popup.php?recid=" + recid + "&dock=" + dock +
                "&warehouse_id=" + warehouse_id + "&trailer=" + trailer + "&dd_flg=1", true);
            xmlhttp.send();

        }

        function confirmationUnloaded(a, b, c) {
            var answer = confirm("Confirm Trailer #" + a + " is UCB Unloaded?")
            if (answer) {
                window.location = "<?php echo mccormickdashboardpage() ?>?action=confirm&conf_id=" + b + "&trailer_no=" + a +
                    "&dock=" + c;

            } else {
                alert("Cancelled");
            }
        }

        function save_unloaded_val(recid) {
            var txtunloadedwhere = document.getElementById("txtunloadedwhere").value;
            var warehouse_id = document.getElementById("warehouse_id").value;
            var wa_dd_save = document.getElementById("wa_dd_save").value;

            var rec_id = document.getElementById("rec_id").value;
            var dock = document.getElementById("dock").value;
            var trailer = document.getElementById("trailer").value;

            if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else { // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("DockDoor added successfully");
                    document.getElementById('light_dd').style.display = 'none';
                    window.location = "<?php echo mccormickdashboardpage() ?>";
                }
            }

            xmlhttp.open("POST", "mccormickdashboard_unloaded_popup.php?updatedockdoor=1&wa_dd_save=" + wa_dd_save +
                "&txtunloadedwhere=" + txtunloadedwhere + "&warehouse_id=" + warehouse_id + "&rec_id=" + rec_id +
                "&dock=" + dock + "&trailer=" + trailer, true);
            xmlhttp.send();


        }

        function clockin_form_submit() {
            //const ipAddress = "136.226.80.200";
            const ipAddress = '<?php echo $_SERVER['REMOTE_ADDR']; ?>';
            const ipRegex =
                /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$|^([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}$|^([0-9a-fA-F]{1,4}:){1,7}:|^([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}$|^([0-9a-fA-F]{1,4}:){1,5}(?::[0-9a-fA-F]{1,4}){1,2}$|^([0-9a-fA-F]{1,4}:){1,4}(?::[0-9a-fA-F]{1,4}){1,3}$|^([0-9a-fA-F]{1,4}:){1,3}(?::[0-9a-fA-F]{1,4}){1,4}$|^([0-9a-fA-F]{1,4}:){1,2}(?::[0-9a-fA-F]{1,4}){1,5}$|^[0-9a-fA-F]{1,4}:(?:(?::[0-9a-fA-F]{1,4}){1,6})$|:((?::[0-9a-fA-F]{1,4}){1,7}|:)$/;
            // List of allowed IP addresses
            const allowedIPs = ['136.226.80.200', '136.226.60.200', '136.226.60.180', '165.225.220.184', '165.225.220.164',
                '165.225.38.127', '96.83.83.70', '136.226.80.192', '136.226.80.180', '165.225.220.146',
                '136.226.60.168', '165.225.38.194'
            ];
            if (ipRegex.test(ipAddress) && allowedIPs.includes(ipAddress)) {
                return true;
            } else {
                alert(`Clock in is not allowed from '${ipAddress}' IP address.`);
                return false;
            }
        }

        function clockout_form_submit() {
            //const ipAddress = "136.226.80.200";
            const ipAddress = '<?php echo $_SERVER['REMOTE_ADDR']; ?>';
            const ipRegex =
                /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$|^([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}$|^([0-9a-fA-F]{1,4}:){1,7}:|^([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}$|^([0-9a-fA-F]{1,4}:){1,5}(?::[0-9a-fA-F]{1,4}){1,2}$|^([0-9a-fA-F]{1,4}:){1,4}(?::[0-9a-fA-F]{1,4}){1,3}$|^([0-9a-fA-F]{1,4}:){1,3}(?::[0-9a-fA-F]{1,4}){1,4}$|^([0-9a-fA-F]{1,4}:){1,2}(?::[0-9a-fA-F]{1,4}){1,5}$|^[0-9a-fA-F]{1,4}:(?:(?::[0-9a-fA-F]{1,4}){1,6})$|:((?::[0-9a-fA-F]{1,4}){1,7}|:)$/;
            // List of allowed IP addresses
            const allowedIPs = ['136.226.80.200', '136.226.60.200', '136.226.60.180', '165.225.220.184', '165.225.220.164',
                '165.225.38.127', '96.83.83.70', '136.226.80.192', '136.226.80.180', '165.225.220.146',
                '136.226.60.168', '165.225.38.194'
            ];
            if (ipRegex.test(ipAddress) && allowedIPs.includes(ipAddress)) {
                return true;
            } else {
                alert(`Clock out is not allowed from '${ipAddress}' IP address.`);
                return false;
            }
        }
    </script>
    <style type="text/css">
        .white_content_dd {
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

        .white_content {
            display: none;
            position: absolute;
            padding: 5px;
            border: 2px solid black;
            background-color: white;
            z-index: 1002;
            overflow: auto;
        }
    </style>

    <div id="light" class="white_content"> </div>
    <div id="fade" class="black_overlay"></div>
    <div id="light_dd" class="white_content_dd"></div>

    <br><br>
    <!--------------- REQUESTED TABLE ---------------->
    <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

        <tr align="middle">
            <td colSpan="10" class="style7">
                <b>Inbound Trailers: Requested, Not Delivered to UCB</b>
            </td>
        </tr>
        <tr>
            <td style="width:75" class="style17" align="center">
                <b>DATE REQUEST</b>
            </td>
            <td style="width: 75" class="style17" align="center">
                <b>TRANS #</b>
            </td>
            <td style="width: 75" class="style17" align="center">
                <b>TRAILER #</b>
            </td>
            <td class="style5" align="center">
                <b>SUPPLIER DOCK</b>
            </td>
            <td class="style5" align="center">
                <b>BOL</b>
            </td>
            <td style="width:100" valign="middle" class="style16" align="center">
                <b>REQUESTED BY</b>
            </td>
            <td style="width:300" valign="middle" class="style16" align="center">
                <b> COMPANY </b>
            </td>
        </tr>

        <?php
        $query = "INSERT INTO loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
        $query .= "SELECT loop_transaction.id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE warehouse_id IN ( $warehouse_id_list_str) AND bol_employee LIKE '' and pr_recycling = 0 AND srt_dockdoors_flg=0 and pr_ucblot = 0 and loop_transaction.ignore = 0";
        //AND loop_transaction.pa_warehouse =''
        //echo $query . "<br>";
        db();
        db_query($query);

        $query = "SELECT *, loop_transaction.id AS A, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE (loop_transaction.pa_warehouse = 15) and loop_transaction.cp_notes = '' AND bol_employee LIKE '' and pr_ucblot = 0 and srt_dockdoors_flg=0 and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
        db();
        $res = db_query($query);
        //echo $query;
        while ($row = array_shift($res)) {
            db();
            $res_rechk = db_query("SELECT rec_id FROM loop_mcc_dash_tobeprc WHERE rec_id = '" . $row["A"] . "'");
            $rechk = "no";
            while ($row_rechk = array_shift($res_rechk)) {
                $rechk = "yes";
            }

            if ($rechk == "no") {
                $query = "INSERT INTO loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
                $query .= "SELECT '" . $row["A"] . "', '" . str_replace("'", "\'", $row["pr_requestby"]) . "', '" . str_replace("'", "\'", $row["pr_requestdate"]) . "', '" . str_replace("'", "\'", $row["pr_pickupdate"]) . "', '" . str_replace("'", "\'", $row["pr_dock"]) . "', '" . str_replace("'", "\'", $row["pr_trailer"]) . "', '" . str_replace("'", "\'", $row["pa_employee"]) . "', '" . str_replace("'", "\'", $row["bol_filename"]) . "', '" . $row["company_name"] . "', '" . $row["wid"] . "'";
                //echo $query . "<br>";
                db();
                db_query($query);
            }
        }

        $query = "SELECT * FROM loop_mcc_dash_tobeprc ORDER BY rec_id ASC";
        db();
        $res = db_query($query);
        while ($row = array_shift($res)) {
            $b2bid = 0;
            $company_name = '';
            $query1 = "SELECT b2bid, company_name FROM loop_warehouse WHERE id = '" . $row["wid"] . "'";
            db();
            $res1 = db_query($query1);
            while ($row1 = array_shift($res1)) {
                $b2bid = $row1["b2bid"];
                $company_name = $row1["company_name"];
            }
            $today = strtotime(date("m/d/Y"));
            $request_date = strtotime($row["pr_requestdate"]);
            $diff = ($today - $request_date) / 60 / 60 / 24;
            //echo $diff."<br>";

            $tablerow_color = "#e4e4e4";

        ?>
            <tr align="center">
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["rec_id"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_trailer"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_dock"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php
                    if ($row["bol_filename"] != "") { ?>
                        <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                            <font color="blue"><u>View BOL</u></font>
                        </div>
                    <?php } ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3">
                    <?php echo $row["pr_requestby"]; ?>
                </td>

                <td align="left" bgColor="<?php echo $tablerow_color ?>" class="style3">
                    <?php echo get_nickname_val($row["company_name"], $b2bid); ?>
                </td>
            </tr>
        <?php
        }

        $query = "DELETE FROM loop_mcc_dash_tobeprc";
        db();
        db_query($query);
        ?>
    </table>
    <!--------------- END REQUESTED TABLE ---------------->
    <br><br>
    <!--------------- UCB Lot TABLE ---------------->
    <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

        <tr align="center">
            <td colSpan="10" class="style7">
                <b>Inbound Trailers: Delivered to Lot, Needs Put in UCB Dock Door</b>
            </td>
        </tr>
        <tr>
            <td style="width:75" class="style17" align="center">
                <b>DATE REQUEST</b>
            </td>
            <td style="width: 75" class="style17" align="center">
                <b>TRANS #</b>
            </td>
            <td style="width: 100" class="style17" align="center">
                <b>TRAILER #</b>
            </td>
            <td class="style5" align="center">
                <b>SUPPLIER DOCK</b>
            </td>
            <td class="style5" align="center">
                <b>BOL</b>
            </td>
            <td style="width:100" valign="middle" class="style16" align="center">
                <b>REQUESTED BY</b>
            </td>
            <td style="width:300" valign="middle" class="style16" align="center">
                <b>COMPANY</b>
            </td>
        </tr>

        <?php

        $query = "Insert into loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
        $query .= "SELECT loop_transaction.id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE warehouse_id IN ( $warehouse_id_list_str2) AND srt_dockdoors_flg=0 AND pr_ucblot = 1 AND bol_employee LIKE '' and loop_transaction.ignore = 0";
        db();
        db_query($query);

        $query = "SELECT *, loop_transaction.id AS A, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE loop_transaction.pa_warehouse = 15 and loop_transaction.cp_notes = '' AND srt_dockdoors_flg=0 AND bol_employee LIKE '' AND pr_ucblot = 1 and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
        db();
        $res = db_query($query);
        while ($row = array_shift($res)) {
            db();
            $res_rechk = db_query("SELECT rec_id FROM loop_mcc_dash_tobeprc WHERE rec_id = '" . $row["A"] . "'");
            $rechk = "no";
            while ($row_rechk = array_shift($res_rechk)) {
                $rechk = "yes";
            }

            if ($rechk == "no") {
                $query = "INSERT INTO loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
                $query .= "SELECT '" . $row["A"] . "', '" . str_replace("'", "\'", $row["pr_requestby"]) . "', '" . str_replace("'", "\'", $row["pr_requestdate"]) . "', '" . str_replace("'", "\'", $row["pr_pickupdate"]) . "', '" . str_replace("'", "\'", $row["pr_dock"]) . "', '" . str_replace("'", "\'", $row["pr_trailer"]) . "', '" . str_replace("'", "\'", $row["pa_employee"]) . "', '" . str_replace("'", "\'", $row["bol_filename"]) . "', '" . str_replace("'", "\'", $row["company_name"]) . "', " . $row["wid"];
                db();
                db_query($query);
            }
        }

        $query = "SELECT * FROM loop_mcc_dash_tobeprc ORDER BY rec_id ASC";
        db();
        $res = db_query($query);
        while ($row = array_shift($res)) {

            $b2bid = 0;
            $query1 = "SELECT b2bid FROM loop_warehouse WHERE id = '" . $row["wid"] . "'";
            db();
            $res1 = db_query($query1);
            while ($row1 = array_shift($res1)) {
                $b2bid = $row1["b2bid"];
            }
            //$oldest_date
            $date_arr = explode(",", $oldest_date);
            $trns_arr = explode(",", $trns_id);
            for ($i = 0; $i < count($trns_arr); $i++) {
                if ($trns_arr[$i] == $row["rec_id"]) {
                    if ($date_arr[$i] != "") {
                        $ucblot_dt = strtotime($date_arr[$i]);
                        $today = strtotime(date("m/d/Y"));
                        $diff = ($today - $ucblot_dt) / 60 / 60 / 24;
                        //echo $diff."<br>";

                        if ($diff > 3) {
                            $tablerow_color = "#f5dddc";
                            $get_all_red_row_cnt = $get_all_red_row_cnt + 1;
                            $HV_red_row_loop_ids_str = $HV_red_row_loop_ids_str . $row["rec_id"] . ",";
                        } elseif ($diff == 3) {
                            $tablerow_color = "#FFFF99";
                        } elseif ($diff < 3) {
                            $tablerow_color = "#e4e4e4";
                        }
                    }
                }
            }

            $tablerow_color = "#e4e4e4";
        ?>
            <tr align="center">
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["rec_id"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_trailer"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_dock"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php
                    if ($row["bol_filename"] != "") { ?>
                        <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                            <font color="blue"><u>View BOL</u></font>
                        </div>
                    <?php } ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3">
                    <?php echo $row["pr_requestby"]; ?>
                </td>
                <td align="left" bgColor="<?php echo $tablerow_color ?>" class="style3">
                    <?php echo get_nickname_val($row["company_name"], $b2bid); ?>
                </td>

            </tr>
        <?php
        }

        $query = "DELETE FROM loop_mcc_dash_tobeprc";
        db();
        db_query($query);
        ?>
    </table>
    <!--------------- END UCBlot TABLE ---------------->
    <br><br>
    <!--------------- In dock door TABLE ---------------->
    <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

        <tr align="middle">
            <td colSpan="10" class="style7">
                <b>Inbound Trailers: In UCB Dock Door, Not Unloaded</b>
            </td>
        </tr>
        <tr>
            <td style="width:75" class="style17" align="center">
                <b>UCB DOCK</b>
            </td>
            <td style="width:75" class="style17" align="center">
                <b>DATE REQUEST</b>
            </td>
            <td style="width: 75" class="style17" align="center">
                <b>TRANS #</b>
            </td>
            <td style="width: 100" class="style17" align="center">
                <b>TRAILER #</b>
            </td>
            <td class="style5" align="center">
                <b>SUPPLIER DOCK</b>
            </td>
            <td class="style5" align="center">
                <b>BOL</b>
            </td>
            <td style="width:300" valign="middle" class="style16" align="center">
                <b> COMPANY </b>
            </td>
        </tr>

        <?php
        $query = "SELECT *, loop_warehouse.warehouse_name AS CN, loop_warehouse.id as wid , loop_transaction.id AS LTID FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE warehouse_id = 15 and srt_dockdoors_flg =1 AND ucbunloaded_flg=0 and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
        db();
        $res = db_query($query);
        while ($row = array_shift($res)) {
            $rec_id = $row["LTID"];
            $b2bid = 0;
            $query1 = "Select b2bid from loop_warehouse where id = '" . $row["wid"] . "'";
            db();
            $res1 = db_query($query1);
            while ($row1 = array_shift($res1)) {
                $b2bid = $row1["b2bid"];
            }

            $date_arr = explode(",", $oldest_date);
            $trns_arr = explode(",", $trns_id);
            for ($i = 0; $i < count($trns_arr); $i++) {
                if ($trns_arr[$i] == $row["LTID"]) {
                    if ($date_arr[$i] != "") {
                        $ucblot_dt = strtotime($date_arr[$i]);
                        $today = strtotime(date("m/d/Y"));
                        $diff = ($today - $ucblot_dt) / 60 / 60 / 24;
                        //echo $diff."<br>";

                        if ($diff > 3) {
                            $tablerow_color = "#f5dddc";
                            $get_all_red_row_cnt = $get_all_red_row_cnt + 1;
                            $HV_red_row_loop_ids_str = $HV_red_row_loop_ids_str . $row["LTID"] . ",";
                        } elseif ($diff == 3) {
                            $tablerow_color = "#FFFF99";
                        } elseif ($diff < 3) {
                            $tablerow_color = "#e4e4e4";
                        }
                    }
                }
            }
            $tablerow_color = "#e4e4e4";
        ?>
            <tr align="center">
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["srt_dock_doors"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["LTID"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_trailer"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_dock"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php
                    if ($row["bol_filename"] != "") { ?>
                        <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                            <font color="blue"><u>View BOL</u></font>
                        </div>
                    <?php } ?>
                </td>

                <td align="left" bgColor="<?php echo $tablerow_color ?>" class="style3">
                    <?php echo get_nickname_val($row["company_name"], $b2bid); ?>
                </td>
            </tr>
        <?php
        }
        //
        ?>
    </table>
    <!--------------- END In dock door TABLE ---------------->
    <br><br>
    <!------------------New Unloaded table------------------>
    <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

        <tr align="middle">
            <td class="style7" colSpan="11">
                <b>Inbound Trailers: Unloaded, Not Processed (Sorted or Entered)</b>
            </td>
        </tr>
        <tr>
            <td style="width: 75" class="style17" align="center">
                <b>Area Unloaded</b>
            </td>
            <td style="width: 75" class="style17" align="center">
                <b>DATE REQUEST</b>
            </td>
            <td style="width: 75" class="style17" align="center">
                <b>TRANS #</b>
            </td>
            <td style="width: 75" class="style17" align="center">
                <b>TRAILER #</b>
            </td>
            <td class="style5" align="center">
                <b>SUPPLIER DOCK</b>
            </td>
            <td class="style5" align="center">
                <b>BOL</b>
            </td>
            <td style="width: 350" class="style16" align="center">
                <b>COMPANY</b>
            </td>
        </tr>
        <script LANGUAGE="JavaScript">
            function printsortrep(id, printstatus) {
                if (printstatus == "Y") {
                    var answer = confirm("Report already printed. Do you wish to re-print?");
                    if (answer) {
                        window.location = "sortreport2.php?rec_id=" + id;
                    }
                } else {
                    window.location = "sortreport2.php?rec_id=" + id;
                }
            }

            function PrintPickListRep(id, printstatus) {
                if (printstatus == "Y") {
                    var answer = confirm("Report already printed. Do you wish to re-print?");
                    if (answer) {
                        window.location = "picklist.php?rec_id=" + id;
                    }
                } else {
                    window.location = "picklist.php?rec_id=" + id;
                }
            }

            function PrintPickListRep_so(id, printstatus) {
                if (printstatus == "Y") {
                    var answer = confirm("Report already printed. Do you wish to re-print?");
                    if (answer) {
                        window.location = "picklist.php?rec_id=" + id;
                    }
                } else {
                    window.location = "picklist.php?rec_id=" + id;
                }
            }

            function undodelivery(trailerno, recid, dockno) {
                var answer = confirm("Do you wish to Undo the Confirm Delivery of Trailer #" + trailerno + "?")
                if (answer) {
                    window.location = "<?php echo mccormickdashboardpage() ?>?action=undodelivery&conf_id=" + recid +
                        "&trailer_no=" + trailerno + "&dock=" + dockno;
                } else {
                    alert("Request Cancelled");
                }
            }

            function undorecycling(trailerno, recid, dockno) {
                var answer = confirm("Do you wish to Undo the RECYCLING Flag of Trailer #" + trailerno + "?")
                if (answer) {
                    window.location = "<?php echo mccormickdashboardpage() ?>?action=undorecycling&conf_id=" + recid +
                        "&trailer_no=" + trailerno + "&dock=" + dockno;
                } else {
                    alert("Request Cancelled");
                }
            }

            function undoucblot(trailerno, recid, dockno) {
                var answer = confirm("Do you wish to Undo the UCBLot Flag of Trailer #" + trailerno + "?")
                if (answer) {
                    window.location = "<?php echo mccormickdashboardpage() ?>?action=undoucblot&conf_id=" + recid +
                        "&trailer_no=" +
                        trailerno + "&dock=" + dockno;
                } else {
                    alert("Request Cancelled");
                }
            }

            function undoucbdockdoor(trailerno, recid) {
                var answer = confirm("Do you wish to Undo the UCBDockDoor Flag of Trailer #" + trailerno + "?")
                if (answer) {
                    //window.location = "<? //=mccormickdashboardpage()
                                            ?>?action=undoucbdockdoor&conf_id="+recid+"&trailer_no="+trailerno+"&dock="+dockno;
                    window.location = "<?php echo mccormickdashboardpage() ?>?action=undoucbdockdoor&conf_id=" + recid +
                        "&trailer_no=" + trailerno;
                } else {
                    alert("Request Cancelled");
                }
            }
        </script>

        <?php
        $query = "SELECT *, loop_warehouse.warehouse_name AS CN, loop_warehouse.id as wid , loop_transaction.id AS LTID FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE (warehouse_id = 15) and pa_pickupdate <> '' AND ucbunloaded_flg=1  AND (sort_entered = 0 or usr_file LIKE '') AND transaction_date > '2010-10-28' and loop_transaction.ignore = 0 ORDER BY loop_transaction.ID ASC";
        db();
        $res = db_query($query);
        while ($row = array_shift($res)) {

            $date_arr = explode(",", $oldest_date);
            $trns_arr = explode(",", $trns_id);
            for ($i = 0; $i < count($trns_arr); $i++) {
                if ($trns_arr[$i] == $row["LTID"]) {
                    //echo $trns_arr[$i];
                    if ($date_arr[$i] != "") {
                        $ucblot_dt = strtotime($date_arr[$i]);
                        $today = strtotime(date("m/d/Y"));
                        $diff = ($today - $ucblot_dt) / 60 / 60 / 24;
                        //echo $diff."<br>";

                        if ($diff > 3) {
                            $tablerow_color = "#f5dddc";
                            $get_all_red_row_cnt = $get_all_red_row_cnt + 1;
                            $HV_red_row_loop_ids_str = $HV_red_row_loop_ids_str . $row["LTID"] . ",";
                        } elseif ($diff == 3) {
                            $tablerow_color = "#FFFF99";
                        } elseif ($diff < 3) {
                            $tablerow_color = "#e4e4e4";
                        }
                    }
                }
            }
            $tablerow_color = "#e4e4e4";
        ?>
            <tr align="center">
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["area_unloaded"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["LTID"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_trailer"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_dock"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php
                    if ($row["bol_filename"] != "") { ?>
                        <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                            <font color="blue"><u>View BOL</u></font>
                        </div>
                    <?php } ?>
                </td>
                <td align="left" bgColor="<?php echo $tablerow_color ?>" class="style3">
                    <?php echo get_nickname_val($row["company_name"], $row["b2bid"]); ?>
                </td>
                <!-- Added by Mooneem 07-14-12  -->
            </tr>
        <?php
        }
        ?>
    </table>
    <!------------------End New Unloaded table-------------->
    <br><br>
    <!--------------- RECYCLING TABLE ---------------->
    <table cellSpacing="1" cellPadding="1" border="0" width="700px;">

        <tr align="middle">
            <td colspan="10" class="style7">
                <b>Inbound Trailers: Delivered Directly to Recycler, Pending Receipt and Scale Ticket</b>
            </td>
        </tr>
        <tr>
            <td style="width:75" class="style17" align="center">
                <b>DATE REQUEST</b>
            </td>
            <td style="width: 75" class="style17" align="center">
                <b>TRANS #</b>
            </td>
            <td style="width:75" class="style17" align="center">
                <b>TRAILER #</b>
            </td>
            <td class="style5" align="center">
                <b>SUPPLIER DOCK</b>
            </td>
            <td class="style5" align="center">
                <b>BOL</b>
            </td>
            <td style="width:100" valign="middle" class="style16" align="center">
                <b>REQUESTED BY</b>
            </td>
            <td style="width:300" valign="middle" class="style16" align="center">
                <b> COMPANY </b>
            </td>
        </tr>

        <?php
        $query = "Insert into loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
        $query .= "SELECT loop_transaction.id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE warehouse_id IN ( $warehouse_id_list_str2) and pr_recycling = 1 and bol_employee LIKE '' AND ucbunloaded_flg=0 and loop_transaction.ignore = 0";
        db();
        db_query($query);

        $query = "SELECT *, loop_transaction.id AS A, loop_warehouse.id as wid FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id WHERE loop_transaction.pa_warehouse = 15 and pr_recycling = 1 and loop_transaction.cp_notes = '' AND bol_employee LIKE '' and loop_transaction.ignore = 0 AND ucbunloaded_flg=0 ORDER BY loop_transaction.ID ASC";
        db();
        $res = db_query($query);
        while ($row = array_shift($res)) {
            db();
            $res_rechk = db_query("Select rec_id from loop_mcc_dash_tobeprc where rec_id = '" . $row["A"] . "'");
            $rechk = "no";
            while ($row_rechk = array_shift($res_rechk)) {
                $rechk = "yes";
            }

            if ($rechk == "no") {
                $query = "Insert into loop_mcc_dash_tobeprc (rec_id, pr_requestby, pr_requestdate, pr_pickupdate, pr_dock, pr_trailer, pa_employee, bol_filename, company_name, wid) ";
                $query .= "SELECT '" . $row["A"] . "', '" . $row["pr_requestby"] . "', '" . $row["pr_requestdate"] . "', '" . $row["pr_pickupdate"] . "', '" . $row["pr_dock"] . "', '" . $row["pr_trailer"] . "', '" . $row["pa_employee"] . "', '" . $row["bol_filename"] . "', '" . $row["company_name"] . "', '" . $row["wid"] . "'";
                db();
                db_query($query);
            }
        }

        $query = "Select * from loop_mcc_dash_tobeprc order by rec_id asc";
        db();
        $res = db_query($query);
        while ($row = array_shift($res)) {

            $b2bid = 0;
            $query1 = "Select b2bid from loop_warehouse where id = '" . $row["wid"] . "'";
            db();
            $res1 = db_query($query1);
            while ($row1 = array_shift($res1)) {
                $b2bid = $row1["b2bid"];
            }

            $today = strtotime(date("m/d/Y"));
            $request_date = strtotime($row["pr_requestdate"]);
            $diff = ($today - $request_date) / 60 / 60 / 24;
            //echo $diff."<br>";

            if ($diff > 14) {
                $tablerow_color = "#f5dddc";
                $get_all_red_row_cnt = $get_all_red_row_cnt + 1;
                $HV_red_row_loop_ids_str = $HV_red_row_loop_ids_str . $row["rec_id"] . ",";
            } elseif ($diff == 14) {
                $tablerow_color = "#FFFF99";
            } elseif ($diff < 14) {
                $tablerow_color = "#e4e4e4";
            }

            $tablerow_color = "#e4e4e4";
        ?>
            <tr align="center">
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["rec_id"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_trailer"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php echo $row["pr_dock"]; ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3" align="center">
                    <?php
                    if ($row["bol_filename"] != "") { ?>
                        <div style="cursor: pointer;" onclick="display_file('files/<?php echo $row["bol_filename"]; ?>', 'BOL')">
                            <font color="blue"><u>View BOL</u></font>
                        </div>
                    <?php } ?>
                </td>
                <td bgColor="<?php echo $tablerow_color ?>" class="style3">
                    <?php echo $row["pr_requestby"]; ?>
                </td>
                <td align="left" bgColor="<?php echo $tablerow_color ?>" class="style3">
                    <?php echo get_nickname_val($row["company_name"], $b2bid); ?>
                </td>

            </tr>
        <?php
        }

        $query = "delete from loop_mcc_dash_tobeprc";
        db();
        db_query($query);
        ?>
    </table>
    <!--------------- END Recycling TABLE ---------------->

    <!--------------- INBOUND TABLES END ---------------->


    <!--------------- REQUESTED TABLE ---------------->
    <!-- <table cellSpacing="1" cellPadding="1" border="0">

	<tr align="middle">
		<td colSpan="10" class="style7">
		<b>View Trailers TO BE PROCESSED</b></td>
	</tr>
	<tr>
		<td style="width: 150" class="style17" align="center">
			<b>DATE REQUEST</b></td>
		<td style="width: 150" class="style17" align="center">
			<b>TRAILER #</b></td>
		<td class="style5" style="width: 100" align="center">
			<b>DOCK</b></td>
		<td class="style5" style="width: 100" align="center">
			<b>BOL</b></td>
		<td align="middle" style="width: 150" class="style16" align="center">
			<b>REQUESTED BY</b>
		</td>
	</tr>		

	
	<?php
    $query = "SELECT * FROM loop_transaction WHERE warehouse_id = 15 AND pa_employee LIKE '' AND cp_employee LIKE '' ORDER BY ID ASC";
    db();
    $res = db_query($query);
    while ($row = array_shift($res)) {

    ?>
			<tr vAlign="center">
				<td bgColor="#e4e4e4" class="style3"  align="center">	
					<?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
				<td bgColor="#e4e4e4" class="style3"  align="center">	
					<?php echo $row["pr_trailer"]; ?>
				</td>
				<td bgColor="#e4e4e4" class="style3"  align="center">	
					<?php echo $row["pr_dock"]; ?>
				</td>
				<td bgColor="#e4e4e4" class="style3"  align="center">	
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
</table> -->
    <!--------------- END REQUESTED TABLE ---------------->
    <br>
    <!--------------- BEGIN IN PROCESS TABLE ---------------->
    <!-- <table cellSpacing="1" cellPadding="1" border="0">

	<tr align="middle">
		<td colSpan="10" class="style7">
		<b>View Trailers IN PROCESS</b></td>
	</tr>
	<tr>
		<td style="width: 150" class="style17" align="center">
			<b>DATE REQUEST</b></td>
		<td style="width: 150" class="style17" align="center">
			<b>TRAILER #</b></td>
		<td class="style5" style="width: 100" align="center">
			<b>DOCK</b></td>
		<td align="middle" style="width: 150" class="style16" align="center">
			<b>REQUESTED BY</b></td>
	</tr>		

	
	<?php
    $query = "SELECT * FROM loop_transaction WHERE warehouse_id = 15 AND (pa_employee NOT LIKE '' OR bol_employee NOT LIKE '') AND sort_entered = 0 ORDER BY ID ASC";
    db();
    $res = db_query($query);
    while ($row = array_shift($res)) {

    ?>
			<tr vAlign="center">
				<td bgColor="#e4e4e4" class="style3"  align="center">
					<?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
				<td bgColor="#e4e4e4" class="style3"  align="center">	
					<?php echo $row["dt_trailer"]; ?>
				</td>
				<td bgColor="#e4e4e4" class="style3"  align="center">
					<?php echo $row["pr_dock"]; ?>
				</td>
				<td bgColor="#e4e4e4" class="style3">
					<?php echo $row["pr_requestby"]; ?></td>
				</td>
			</tr>
	<?php
    }
    ?>
</table> -->
    <!--------------- END IN PROCESS TABLE ---------------->
    <br>
    <br>
    <!------------------------- BEGIN PROCESSED TRAILERS ----------------------->
    <form name="rptSearch" action="processedtrailerreport.php" method="GET" target="_blank">
        <input type="hidden" name="action" value="run">
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


                                <input type="text" name="start_date" size="11" value="<?php echo (isset($_REQUEST["start_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $start_date) : date('m/01/Y') ?>">
                                <a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="x-small">and:
                                    <input type="text" name="end_date" size="11" value="<?php echo (isset($_REQUEST["end_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $end_date) : date('m/d/Y') ?>">
                                    <a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
                                    <input type=radio <? if (
                                                            $_REQUEST["reportview"] == "1" || $_REQUEST["reportview"] == ""
                                                        ) {
                                                            echo "checked";
                                                        } ?> name="reportview" value="1">Show By Weight
                                    <input type=radio name="reportview" <? if (
                                                                            $_REQUEST["reportview"] == "0"
                                                                        ) {
                                                                            echo "checked";
                                                                        } ?> value="0"> Show By Trailer
                            </td>
                        </tr>
                        <tr>
                            <td bgColor="#e4e4e4" class="style12center">
                                &nbsp; <input type="submit" value="Search">
                                <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
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
    <!------------------------- BEGIN EMPLOYEE REPORT ----------------------->
    <form name="emprpt" action="report_staffing_timeclock.php" method="POST" target="_blank">
        <input type="hidden" name="action" value="run">
        <input type="hidden" name="warehouse_id" value="15">
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


                                <input type="text" name="start_date" size="11" value="<?php echo (isset($_REQUEST["start_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $start_date) : date('m/01/Y') ?>">
                                <a href="#" onclick="cal3xx.select(document.emprpt.start_date,'anchor3xx','MM/dd/yyyy'); return false;" name="anchor3xx" id="anchor3xx"><img border="0" src="images/calendar.jpg"></a>
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="x-small">and:
                                    <input type="text" name="end_date" size="11" value="<?php echo (isset($_REQUEST["end_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $end_date) : date('m/d/Y') ?>">
                                    <a href="#" onclick="cal4xx.select(document.emprpt.end_date,'anchor4xx','MM/dd/yyyy'); return false;" name="anchor4xx" id="anchor4xx"><img border="0" src="images/calendar.jpg"></a>
                                    <select id="worker" name="worker">
                                        <option value="-1">ALL</option>
                                        <?php
                                        $query = "SELECT * FROM loop_workers WHERE warehouse_id = 15 AND active = 1 ORDER BY name ASC";
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
                                <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                </div>
                            </td>
                        </tr>
                    </table>
    </form>

    <!------------------ END EMPLOYEE REPORT-------------------->

    <!------------------------- BEGIN Berry ----------------------->
    <form name="berrySearch" action="mccormickdashboard_76345679315467990452.php" method="GET">
        <input type="hidden" name="action" value="berry">
        <span class="style2">


            <span class="style13"><span class="style15">

                    <table cellSpacing="1" cellPadding="1" border="0" width="550">

                        <tr align="middle">
                            <td colSpan="10" class="style7">
                                <b>View Pallets Shipped to Berry</b>
                            </td>
                        </tr>
                        <tr align="middle">
                            <td colSpan="10" class="style17">



                                <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
                                <script LANGUAGE="JavaScript">
                                    document.write(getCalendarStyles());
                                </script>
                                <script LANGUAGE="JavaScript">
                                    var cal1yy = new CalendarPopup("listdiv");
                                    cal1yy.showNavigationDropdowns();
                                    var cal2yy = new CalendarPopup("listdiv");
                                    cal2yy.showNavigationDropdowns();
                                </script>
                                <?php
                                $start_date = isset($_REQUEST["start_date"]) ? strtotime($_REQUEST["start_date"]) : strtotime(date('m/d/Y'));
                                $end_date = isset($_REQUEST["end_date"]) ? strtotime($_REQUEST["end_date"]) : strtotime(date('m/d/Y'));
                                ?>


                                <input type="text" name="start_date" size="11" value="<?php echo (isset($_REQUEST["start_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $start_date) : date('m/01/Y') ?>">
                                <a href="#" onclick="cal1yy.select(document.berrySearch.start_date,'anchor1yy','MM/dd/yyyy'); return false;" name="anchor1yy" id="anchor1yy"><img border="0" src="images/calendar.jpg"></a>
                                <font face="Arial, Helvetica, sans-serif" color="#333333" size="x-small">and:
                                    <input type="text" name="end_date" size="11" value="<?php echo (isset($_REQUEST["end_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $end_date) : date('m/d/Y') ?>">
                                    <a href="#" onclick="cal1yy.select(document.berrySearch.end_date,'anchor2yy','MM/dd/yyyy'); return false;" name="anchor2yy" id="anchor2yy"><img border="0" src="images/calendar.jpg"></a>

                            </td>
                        </tr>
                        <tr>
                            <td bgColor="#e4e4e4" class="style12center">
                                &nbsp; <input type="submit" value="Search">
                                <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colSpan="10" class="style17">
                                Pallets shipped:
                                <?php

                                $berryqry = "SELECT sum(pallets) AS A FROM `loop_bol_tracking` WHERE `warehouse_id` LIKE '35' AND bol_pickupdate BETWEEN '" . $_REQUEST["start_date"] . "' AND '" . $_REQUEST["end_date"] . "'";
                                db();
                                $res = db_query($berryqry);
                                while ($row = array_shift($res)) {
                                    echo $row["A"];
                                }

                                ?>
                            </td>
                        </tr>
                    </table>
    </form>

    <!------------------ END BERRY-------------------->
    <br>

    </td>
    <td width="100">&nbsp;

    </td>
    <td valign="top">
        <table cellSpacing="1" cellPadding="1" border="0" width="200">
            <tr align="middle">
                <td class="style7">
                    <b>QUICK LINKS</b>
                </td>
            </tr>
            <tr>
                <td bgColor="#e4e4e4" class="style12center">
                    <a target="_blank" href="http://loops.usedcardboardboxes.com/hr_page.php?warehouse_id=15"><b>WRITE
                            TO HR</b></a>
                </td>
            </tr>
        </table>
        <br>
        <!--------------------- BEGIN TRAILER SEARCH  ----------------------------------------------->
        <form name="trlSearch" action="trailersearchresults.php" method="POST" target="_blank">
            <input type=hidden name=action value="run">
            <table cellSpacing="1" cellPadding="1" border="0" width="300">
                <tr align="middle">
                    <td colSpan="10" class="style7">
                        <b>TRAILER SEARCH</b>
                    </td>
                </tr>
                <tr>
                    <td bgColor="#e4e4e4" class="style12center">
                        <input type=text name="trailer_no" size="20"> <input type=submit value="Search">
                    </td>
                </tr>

            </table>
        </form>
        <!--------------------- END TRAILER SEARCH  ----------------------------------------------->
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

                    <input type=hidden name="reportview" value="<?php echo $_REQUEST[" reportview"]; ?>">
                    <input type=hidden name="start_date" value="<?php echo $_REQUEST[" start_date"]; ?>">
                    <input type=hidden name="end_date" value="<?php echo $_REQUEST[" end_date"]; ?>">
                    <input type=hidden name="action" value="run">
                    <table cellSpacing="1" cellPadding="1" width="550" border="0">

                        <tr align="middle">
                            <td colSpan="10" class="style7">
                                <b>McCORMICK TRAILER REPORT FROM
                                    <?php echo $_REQUEST["start_date"]; ?> -
                                    <?php echo $_REQUEST["end_date"]; ?>
                                </b>
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
                        $query = "SELECT * FROM loop_transaction WHERE warehouse_id = 15 AND";
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
                                    <?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?>
                                </td>
                                <td bgColor="#e4e4e4" class="style3" align="center">
                                    <a href="http://loops.usedcardboardboxes.com/mccormickdashboard_76345679315467990452.php?action=run&start_date=<?php echo htmlspecialchars($_REQUEST["
                                start_date"]); ?>&end_date=
                                <?php echo $_REQUEST["end_date"]; ?>&reportview=
                                <?php echo $_REQUEST["reportview"]; ?>&trailer=
                                <?php echo $row["id"]; ?>&trlsub=1">
                                        <?php echo $row["dt_trailer"]; ?>
                                    </a>
                                </td>
                                <td bgColor="#e4e4e4" class="style3" align="center">
                                    <?php echo $row["pr_dock"]; ?>
                                </td>
                                <td bgColor="#e4e4e4" class="style3">
                                    <?php echo $row["pr_requestby"]; ?>
                                </td>
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
                                    $
                                    <?php
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
                                <b>
                                    <?php echo $grandtotal; ?>
                                </b></font>
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
                            <td class="style7" colspan="10" style="height: 16px"><strong>SORT REPORT FOR TRAILER #
                                    <?php echo $dt_view_trl_row["pr_trailer"]; ?>
                                </strong></td>
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
                                    <td bgColor="#e4e4e4" class="style12right">
                                        <?php echo $dt_view_row["boxgood"]; ?>
                                    </td>
                                    <td bgColor="#e4e4e4" class="style12right">
                                        <?php echo $dt_view_row["boxbad"]; ?>
                                    </td>
                                    <td bgColor="#e4e4e4" class="style12left">
                                        <?php echo $dt_view_row["blength"]; ?>
                                        <?php echo $dt_view_row["blength_frac"]; ?> x
                                        <?php echo $dt_view_row["bwidth"]; ?>
                                        <?php echo $dt_view_row["bwidth_frac"]; ?> x
                                        <?php echo $dt_view_row["bdepth"]; ?>
                                        <?php echo $dt_view_row["bdepth_frac"]; ?>
                                        <?php echo $dt_view_row["bdescription"]; ?>
                                    </td>
                                    <td bgColor="#e4e4e4" class="style12right">
                                        <?php echo $dt_view_row["bweight"]; ?>
                                    </td>
                                    <td bgColor="#e4e4e4" class="style12right">
                                        <?php echo $dt_view_row["sort_boxgoodvalue"]; ?>
                                    </td>
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
                            <td bgColor="#e4e4e4" class="style12right"><strong>
                                    <?php echo $gb; ?>
                                </strong></td>
                            <td bgColor="#e4e4e4" class="style12right"><strong>
                                    <?php echo $bb; ?>
                                </strong></td>
                            <td bgColor="#e4e4e4" class="style12"><strong>BOX TOTALS</strong></td>
                            <td bgColor="#e4e4e4" class="style12right"><strong>
                                    <?php echo number_format($gbw, 2); ?>
                                </strong></td>
                            <td bgColor="#e4e4e4" class="style12"> </td>
                            <td bgColor="#e4e4e4" class="style12right"><strong>$
                                    <?php echo number_format($vob, 2); ?>
                                </strong></td>
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
                                    <td bgColor="#e4e4e4" colspan="2" class="style12right">
                                        <?php echo $dt_view_row["boxgood"]; ?>
                                    </td>
                                    <td bgColor="#e4e4e4" class="style12left">
                                        <?php echo $dt_view_row["bdescription"]; ?>
                                    </td>
                                    <td bgColor="#e4e4e4" class="style12right">
                                        <?php echo $dt_view_row["bunit"]; ?>
                                    </td>
                                    <td bgColor="#e4e4e4" class="style12right">
                                        <?php echo number_format($dt_view_row["sort_boxgoodvalue"], 3); ?>
                                    </td>
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

                            <td bgColor="#e4e4e4" class="style12right"><strong>$
                                    <?php echo number_format($voo, 2); ?>
                                </strong></td>
                        </tr>
                        <tr align="middle">
                            <td bgColor="88EEEE" colspan="10" class="style17"><strong>TOTALS</strong></td>
                        </tr>
                        <tr>
                            <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>GROSS EARNINGS</strong></td>
                            <td bgColor="#e4e4e4" class="style12right"><strong>$
                                    <?php echo number_format($vob + $voo, 2); ?>
                                </strong></td>
                        </tr>
                        <?php if ($dt_view_trl_row["othercharge"] != 0) { ?>
                            <tr>
                                <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>
                                        <?php echo $dt_view_trl_row["otherdetails"]; ?>
                                    </strong></td>
                                <td bgColor="#e4e4e4" class="style12right"><strong>$
                                        <?php echo number_format($dt_view_trl_row["othercharge"], 2); ?>
                                    </strong></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>FREIGHT</strong></td>
                            <td bgColor="#e4e4e4" class="style12right"><strong>$
                                    <?php echo number_format($dt_view_trl_row["freightcharge"], 2); ?>
                                </strong></td>
                        </tr>
                        <tr>
                            <td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>TOTAL EARNED</strong></td>
                            <td bgColor="#e4e4e4" class="style12right"><strong>$
                                    <?php echo number_format($vob + $voo + $dt_view_trl_row["othercharge"] + $dt_view_trl_row["freightcharge"], 2); ?>
                                </strong></td>
                        </tr>




                    <?php } ?>


                </td>
            </tr>
        </table>


</body>

</html>