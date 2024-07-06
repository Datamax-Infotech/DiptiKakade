<?php

session_start();
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

?>
<!DOCTYPE HTML>

<html>

<head>
    <title>Manually Create a BOL Tool | UsedCardboardBoxes</title>

    <style>
    .style1 {
        font-family: 'arial';
        font-size: xx-small;
    }
    </style>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();
    </script>
    <style>
    .bol_table {
        font-family: 'arial';
        font-size: x-small;
        border-collapse: collapse;
        width: 50%;
    }

    .bol_table td {
        border: 1px solid #000;
        padding: 5px;
        text-transform: uppercase;
    }

    .left_textbox {
        border: 0;
        outline: 0;
        background: transparent;
        border: 1px solid #A6A6A6;
        width: 80%;
        margin: 3px;
        padding: 3px;
    }

    .right_textbox {
        border: 0;
        outline: 0;
        background: transparent;
        border: 1px solid #A6A6A6;
        margin: 3px;
        padding: 3px;
    }

    input[type="submit"] {
        background: #B0B0B0;
        border: 1px solid #A4A4A4;
    }

    .info_table {
        font-family: 'arial';
        font-size: x-small;
        border-collapse: collapse;
        width: 100%;
    }

    .info_table tr:first-child td {
        border-top: none;
    }

    .info_table tr:last-child td {
        border-bottom: none;
    }

    .info_table tr td:first-child {
        border-left: none;
    }

    .info_table tr td:last-child {
        border-right: none;
    }
    </style>


    <LINK rel='stylesheet' type='text/css' href='one_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>

<body>
    <?php include("inc/header.php"); ?>

    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">Manually Create a BOL Tool
                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">
                        This tool allows the user to create a BOL from scratch.
                    </span>
                </div>

                <div style="height: 13px;">&nbsp;</div>
            </div>
        </div>
        <form action="shippbol_create.php" method="post" encType="multipart/form-data" name="BOL">
            <table align="center" class="bol_table">
                <tr>
                    <td bgColor="#c0cdda" colSpan="2" align="center">
                        <font size="1"><strong>CREATE BOL</strong> </font>
                    </td>
                </tr>
                <tr>
                    <td colSpan="2" align="left">
                        <font size="1"><strong>Pickup Date: </strong> </font>
                        <font Face='arial' size='2'>

                            <input type=text name="bol_pickupdate" value="<?php echo date('m/d/Y'); ?>" size="20"
                                class="right_textbox"> <a style="color:#0000FF;" href="#"
                                onclick="cal2xx.select(document.BOL.bol_pickupdate,'anchor2xx','MM/dd/yyyy'); return false;"
                                name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>

                        </font>
                        <div ID="listdiv"
                            STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#e6e6e6">
                        <strong>Ship From:</strong>
                    </td>
                    <td>
                        <strong>BOL Number: <input type=text name="bol_number" value="" class="right_textbox"></strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input size="40" type=text name="ship_from1" value="Used Cardboard Boxes, Inc"
                            class="left_textbox"><br><br>
                        <font Face='arial' size='2'>
                            <input size="40" type=text name="ship_from2" value="4032 Wilshire Blvd, Ste 402"
                                class="left_textbox">
                        </font><br><br>
                        <font Face='arial' size='2'>
                            <input size="40" type=text name="ship_from3" value="Los Angeles, CA 90010"
                                class="left_textbox">
                        </font><br><br>
                        <font Face='arial' size='2'>
                            <input name="ship_from4" type=text class="left_textbox" placeholder="Ship From Line 4"
                                size="40">
                        </font>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td bgcolor="#e6e6e6">
                        <strong>Ship To:</strong>
                    </td>
                    <td>
                        <strong>Carrier Name:<input size="40" type="text" name="carrier_name" class="right_textbox">
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name="stl1" type=text placeholder="Ship to Line 1" size="40"
                            class="left_textbox"><br><br>
                        <input name="stl2" type=text placeholder="Ship to Line 2" size="40"
                            class="left_textbox"><br><br>
                        <input name="stl3" type=text placeholder="Ship to Line 3" size="40"
                            class="left_textbox"><br><br>
                        <input name="stl4" type=text placeholder="Ship to Line 4" size="40" class="left_textbox">
                    </td>
                    <td valign="top">
                        <strong>Trailer #:</strong><input size="5" type="text" name="trailer_number"
                            class="right_textbox">
                        <br>
                        <p>
                            <strong>Serial Number(s):</strong><input size="5" type="text" name="serial_no"
                                class="right_textbox">
                        </p>
                        <br>

                        <strong>Class:</strong><input size="5" type="text" name="class" class="right_textbox"
                            value="125">
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#e6e6e6" style="height: 30px">
                        <strong><span style="text-transform: lowercase;">3<sup>rd</sup></span> Party Biller:</strong>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <select size="1" name="bol_freight_biller" class="left_textbox">
                            <option value="">Please Select</option>
                            <?php
                            $fsql = "SELECT * FROM loop_freightvendor ORDER BY company_name ASC";
                            db();
                            $fresult = db_query($fsql);
                            while ($fmyrowsel = array_shift($fresult)) {
                            ?>
                            <option value="<?php echo $fmyrowsel["id"]; ?>"><?php echo $fmyrowsel["company_name"]; ?>
                            </option>
                            <?php } ?>
                            <option value="0">No 3rd Party Biller
                        </select>&nbsp; <br> <br>
                    </td>
                    <td>

                    </td>
                </tr>



                <tr>
                    <td>
                        <strong>Special Instructions:</strong><br>
                        <textarea cols=40 rows=3 name="bol_instructions" class="left_textbox"></textarea>
                        <br><br>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#e6e6e6" style="height: 30px">
                        <strong> CUSTOMER ORDER / CARRIER INFORMATION</strong>
                    </td>

                </tr>
                <tr>
                    <td colspan="2" style="padding: 0px;">
                        <table width="100%" cellpadding="0" cellspacing="0" class="info_table">
                            <tr>
                                <td height="13" class="style1" align="center">
                                    <strong>QUANTITY</strong>
                                </td>
                                <td height="13" class="style1" align="center">
                                    <strong>PALLETS</strong>
                                </td>
                                <td height="13" class="style1" align="center">
                                    <strong>WEIGHT</strong>
                                </td>
                                <td height="13" class="style1" align="center">
                                    <strong>DESCRIPTION</strong>
                                </td>
                                <td height="13" class="style1" align="center" colspan=6>
                                    <strong>ADDITIONAL SHIPPER INFO</strong>
                                </td>

                            </tr>
                            <tr>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="quantity1" class="right_textbox">
                                </td>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="pallet1" class="right_textbox">
                                </td>
                                <td height="13" style="width: 78px" class="style1" align="center">
                                    <input size="5" type="text" name="weight1" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1">
                                    <input type=text size=25 name="description1" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1" colspan=6>
                                    <input type=text size=25 name="add_shipp_info1" class="right_textbox">
                                </td>
                            </tr>
                            <tr>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="quantity2" class="right_textbox">
                                </td>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="pallet2" class="right_textbox">
                                </td>
                                <td height="13" style="width: 78px" class="style1" align="center">
                                    <input size="5" type="text" name="weight2" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1">
                                    <input type=text size=25 name="description2" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1" colspan=6>
                                    <input type=text size=25 name="add_shipp_info2" class="right_textbox">
                                </td>
                            </tr>
                            <tr>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="quantity3" class="right_textbox">
                                </td>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="pallet3" class="right_textbox">
                                </td>
                                <td height="13" style="width: 78px" class="style1" align="center">
                                    <input size="5" type="text" name="weight3" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1">
                                    <input type=text size=25 name="description3" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1" colspan=6>
                                    <input type=text size=25 name="add_shipp_info3" class="right_textbox">
                                </td>
                            </tr>
                            <tr>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="quantity4" class="right_textbox">
                                </td>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="pallet4" class="right_textbox">
                                </td>
                                <td height="13" style="width: 78px" class="style1" align="center">
                                    <input size="5" type="text" name="weight4" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1">
                                    <input type=text size=25 name="description4" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1" colspan=6>
                                    <input type=text size=25 name="add_shipp_info4" class="right_textbox">
                                </td>
                            </tr>
                            <tr>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="quantity5" class="right_textbox">
                                </td>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="pallet5" class="right_textbox">
                                </td>
                                <td height="13" style="width: 78px" class="style1" align="center">
                                    <input size="5" type="text" name="weight5" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1">
                                    <input type=text size=25 name="description5" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1" colspan=6>
                                    <input type=text size=25 name="add_shipp_info5" class="right_textbox">
                                </td>
                            </tr>
                            <tr>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="quantity6" class="right_textbox">
                                </td>
                                <td height="13" class="style1" align="center">
                                    <input size="5" type="text" name="pallet6" class="right_textbox">
                                </td>
                                <td height="13" style="width: 78px" class="style1" align="center">
                                    <input size="5" type="text" name="weight6" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1">
                                    <input type=text size=25 name="description6" class="right_textbox">
                                </td>
                                <td align="left" height="13" class="style1" colspan=6>
                                    <input type=text size=25 name="add_shipp_info6" class="right_textbox">
                                </td>
                            </tr>
                        </table>

                    </td>

                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="hidden" name="count" value="<?php echo isset($i); ?>">
                        <input type=submit value="Create BOL" style="cursor:pointer;">
                    </td>
                </tr>
            </table>

            <br>

        </form>
    </div>
</body>

</html>