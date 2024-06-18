<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


db_b2b();

$status = "";
$freightupdates = 1;
$negotiated_rate = "";
$qry_1 = "Select company,active,haveNeed, loopid, on_hold, freightupdates from companyInfo Where ID = " . $_REQUEST["ID"];
$dt_view_1 = db_query($qry_1);
while ($rows = array_shift($dt_view_1)) {
    $company = $rows['company'];
    $active_1 = $rows["active"];
    $status = $rows["haveNeed"];
    $freightupdates = $rows["freightupdates"];
}

db();

if (isset($_REQUEST["tender_lane_ignore"])) {
    if ($_REQUEST["tender_lane_ignore"] == 1) {

        $qry = "Update loop_transaction_buyer set `tender_lane_ignore` = 1, `tender_lane_ignore_dt` = ?, `tender_lane_ignore_user` = ? WHERE id = ?";
        $res1 = db_query($qry, array("s", "s", "i"), array(date("Y-m-d H:i:s"), $_COOKIE['userinitials'], $_REQUEST["rec_id"]));

        //Tender the lane table
        $po_delivery_dt = "";
        $so_view_qry = "SELECT * FROM loop_transaction_buyer WHERE id = '" .  $_REQUEST['rec_id'] . "'";
        $so_view_res = db_query($so_view_qry);
        while ($trans_buyer_row = array_shift($so_view_res)) {
            $ponumber = $trans_buyer_row["po_ponumber"];
            $virtual_inventory_company_id = $trans_buyer_row["virtual_inventory_company_id"];
            $virtual_inventory_trans_id = $trans_buyer_row["virtual_inventory_trans_id"];
            $po_delivery_dt = $trans_buyer_row["po_delivery_dt"];
        }

        $sql = "SELECT * FROM loop_transaction_buyer_freightview WHERE trans_rec_id=" . $_REQUEST["rec_id"];

        $result2 = db_query($sql);
        $rows = array_shift($result2);
        $tmp_link = "";
        $tmp_timeframe = "";

        if ($rows["id"] > 0) {
            $sql = "SELECT * FROM loop_transaction_buyer_freightview WHERE trans_rec_id = " . $_REQUEST["rec_id"];
            $sql_res = db_query($sql);
            while ($row = array_shift($sql_res)) {
                $tmp_link = $row["link"];
                $tmp_timeframe = $row["timeframe"];
?>
<form action="loop_shipbubble_pickup_or_ucb_delivering.php" method="post">
    <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
    <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
    <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
    <input type="hidden" name="trans_rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 500px">
        <tr align="middle">
            <td bgColor="#99FF99" colSpan="2">
                <font size="1">Tender the Lane</font>
                &nbsp;<input type="button" name="btntenderlane_edit" id="btntenderlane_edit" value="Edit"
                    onclick="tender_lane_add('editflg' , <?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,<?php echo $_REQUEST["rec_type"]; ?>)" />
                &nbsp;<input type="button" name="btntenderlane_delete" id="btntenderlane_delete" value="Delete"
                    onclick="tender_lane_add('deleteflg' , <?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,<?php echo $_REQUEST["rec_type"]; ?>)" />
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Freight Broker</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1">
                    <?php
                                    if ($row["broker_id"] > 0) {
                                        $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $row["broker_id"];
                                        $freightresult = db_query($freight_sql);
                                        $freightrow = array_shift($freightresult);
                                        echo $freightrow["company_name"];
                                    }
                                    ?>
                </font>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Delivery Budget</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1">$<?php echo isset($freight_cost); ?></font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Booked Delivery Cost</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1">$<?php echo $row["booked_delivery_cost"]; ?></font>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">View In Freightview</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1"><a style="color:#0000FF;" href="<?php echo $row["link"]; ?>" target="_blank">Link</a>
                </font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Submitted By</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1"><?php echo get_initials_from_id_new($row["employeeid"]) ?></font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Date/Time</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1"><?php echo timestamp_to_datetime_new($row["dt"]); ?></font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" colspan="2" class="style1">
                <?php
                                echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT";
                                ?>
            </td>
        </tr>

    </table>
</form>
<?php

            } //while loop

        } else {

            $broker_id = 0;
            ?>
<form action="add_freightview_mrg_new.php" method="post">
    <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
    <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
    <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
    <input type="hidden" name="trans_rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 500px">
        <tr align="middle">
            <td bgColor="#99FF99" colSpan="2">
                <font size="1">Tender the Lane</font>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Freight Broker</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1">
                    <select name="freight_booking_broker" id="freight_booking_broker" onchange="displaycontentbrk()">
                        <option value="-1">Please Select</option>
                        <option <?php if ($broker_id == 0) {
                                                echo " selected ";
                                            } ?> value=0>No Freight Broker</option>
                        <?php
                                    $sel_broker_id = 0;
                                    $fsql = "SELECT * FROM loop_freightvendor ORDER BY company_name ASC";
                                    $fresult = db_query($fsql);
                                    while ($fmyrowsel = array_shift($fresult)) {
                                    ?>
                        <script language="javascript">
                        arrfreight["<?php echo  $fmyrowsel["id"]; ?>"] =
                            "<?php echo  'Address: ' . $fmyrowsel["company_address1"] . ' ' . $fmyrowsel["company_address2"] . ' ' . $fmyrowsel["company_city"] . ' ' . $fmyrowsel["company_state"] . ' ' . $fmyrowsel["company_zip"] . '<br/> Phone: ' . $fmyrowsel["company_phone"] . '<br/> Email: ' . $fmyrowsel["company_email"]; ?>";
                        </script>

                        <option <?php if ($broker_id == $fmyrowsel["id"]) {
                                                    echo " selected ";
                                                    $sel_broker_id = $broker_id;
                                                } ?> value="<?php echo $fmyrowsel["id"]; ?>">
                            <?php echo $fmyrowsel["company_name"]; ?></option>
                        <?php } ?>
                    </select>

                    <br />
                    <div name="freightdetbrk" id="freightdetbrk"></div>
                    <br />
                    &nbsp;<a style="color:#0000FF;" target=_blank href="manage_freightvendor_mrg.php?proc=New&">Add
                        New</a>
                    <?php if ($broker_id == $sel_broker_id) {
                                ?>
                    <script language="javascript">
                    document.getElementById("freightdetbrk").innerHTML = arrfreight[<?php echo $sel_broker_id ?>];
                    </script>
                    <?php
                                }
                                ?>
                </font>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Delivery Budget</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1">$<?php echo isset($freight_cost); ?></font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Booked Delivery Cost</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1"><input type="text" name="txtbooked_delivery_cost" id="txtbooked_delivery_cost" size=15>
                </font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Link</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1"><input type=text name="link" id="link" size=15></font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td colspan=2 align=center>
                <font size="1">
                    <input type="button"
                        onclick="tender_lane_add('addflg' ,<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,<?php echo $_REQUEST["rec_type"]; ?>)"
                        value="Add">
                    &nbsp;&nbsp;
                    <font size="1" Face="arial">
                        <?php
                                    echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT";
                                    ?>
                    </font>
            </td>
        </tr>
    </table>
</form>
<?php
        }
    }
}

if (isset($_REQUEST["lane_tms_ignore"])) {
    if ($_REQUEST["lane_tms_ignore"] == 1) {

        $qry = "Update loop_transaction_buyer set `lane_tms_ignore` = 1, `lane_tms_ignore_dt` = ?, `lane_tms_ignore_user` = ? WHERE id = ?";
        $res1 = db_query($qry, array("s", "s", "i"), array(date("Y-m-d H:i:s"), $_COOKIE['userinitials'], $_REQUEST["rec_id"]));
        ?>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 540px">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="2">
            <font size="1">Enter Lane into TMS</font>
        </td>
    </tr>

    <tr>
        <td bgColor="#e4e4e4" colSpan="2">
            <font size="1">
                <?php echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT"; ?>
            </font>
        </td>
    </tr>
</table>
<?php

    }
}

if (isset($_REQUEST["freight_assign_eml_ignore"])) {
    if ($_REQUEST["freight_assign_eml_ignore"] == 1) {

        $qry = "Update loop_transaction_buyer set `freight_assign_eml_ignore` = 1, `freight_assign_eml_ignore_dt` = ?, `freight_assign_eml_ignore_user` = ? WHERE id = ?";
        $res1 = db_query($qry, array("s", "s", "i"), array(date("Y-m-d H:i:s"), $_COOKIE['userinitials'], $_REQUEST["rec_id"]));
    ?>

<?php

        $rec_found = "n";
        $freight_assigned_email_sendon = "";
        $freight_assigned_email_sendby = "";
        $getdata = db_query("Select freight_assigned_email_sendby, freight_assigned_email_sendon from loop_transaction_buyer_ship_eml_data where trans_rec_id = " . $_REQUEST["rec_id"] . " and freight_assigned_email_flg = 1");
        while ($getdata_row = array_shift($getdata)) {
            $rec_found = "y";
            $freight_assigned_email_sendon = $getdata_row["freight_assigned_email_sendon"];
            $freight_assigned_email_sendby = $getdata_row["freight_assigned_email_sendby"];
        }

        if ($rec_found == "n") { ?>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 400px">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="2">
            <font size="1">Freight Assigned E-mail</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td align="center" colspan="2" height="13" class="style1">
            <?php if ($freightupdates == 0) {
                            echo "<font size=1 color=red>OPT OUT</font>";
                        } ?>
            <input type="button" name="btnsendfreml" id="btnsendfreml" value="Send Email"
                onclick="parent.reminder_popup_set3(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,'<?php echo $_REQUEST["rec_type"]; ?>')" />
            &nbsp;&nbsp;
            <font size="1" Face="arial">
                <?php
                            echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT";
                            ?>
            </font>

        </td>
    </tr>
</table>
<br><br>
<?php

        } else { ?>

<table cellSpacing="1" cellPadding="1" border="0" style="width: 400px">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="2">
            <font size="1">Freight Assigned E-mail</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td colspan="2" class="style1">
            <?php echo "Email sent on: " . date("m/d/Y H:i:s", strtotime($freight_assigned_email_sendon)) . " CT" . " by: " . $freight_assigned_email_sendby; ?>
            &nbsp;
            <?php
                        echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT";
                        ?>
        </td>
    </tr>
</table>
<br><br>
<?php

        }

        ?>

<?php     }
}

if (isset($_REQUEST["broker_needs_pickup_eml_ignore"])) {
    if ($_REQUEST["broker_needs_pickup_eml_ignore"] == 2) {

        $qry = "Update loop_transaction_buyer set `broker_needs_pickup_eml_ignore` = 1, `broker_needs_pickup_eml_ignore_dt` = ?, `broker_needs_pickup_eml_ignore_user` = ? WHERE id = ?";
        $res1 = db_query($qry, array("s", "s", "i"), array(date("Y-m-d H:i:s"), $_COOKIE['userinitials'], $_REQUEST["rec_id"]));
    ?>

<?php

        $rec_found = "n";
        $broker_needs_pickup_email_sendon = "";
        $broker_needs_pickup_email_sendby = "";
        $getdata = db_query("Select broker_needs_pickup_email_sendby, broker_needs_pickup_email_sendon from loop_transaction_buyer_ship_eml_data where trans_rec_id = " . $_REQUEST["rec_id"] . " and broker_needs_pickup_email_flg = 2");
        while ($getdata_row = array_shift($getdata)) {
            $rec_found = "y";
            $broker_needs_pickup_email_sendon = $getdata_row["broker_needs_pickup_email_sendon"];
            $broker_needs_pickup_email_sendby = $getdata_row["broker_needs_pickup_email_sendby"];
        }

        if ($rec_found == "n") { ?>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 400px">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="2">
            <font size="1">Broker Needs Pickup Dock Appt E-mail</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td align="center" colspan="2" height="13" class="style1">
            <?php if ($freightupdates == 0) {
                            echo "<font size=1 color=red>OPT OUT</font>";
                        } ?>
            <input type="button" name="btnsendfreml" id="btnsendfreml" value="Send Email"
                onclick="parent.reminder_popup_set4_cust(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,'<?php echo $_REQUEST["rec_type"]; ?>')" />
            &nbsp;&nbsp;
            <font size="1" Face="arial">
                <?php
                            echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT";
                            ?>
            </font>

        </td>
    </tr>
</table>
<br><br>
<?php

        } else {

        ?>

<table cellSpacing="1" cellPadding="1" border="0" style="width: 400px">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="2">
            <font size="1">Broker Needs Pickup Dock Appt E-mail</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td colspan="2" class="style1">
            <?php echo "Email Sent by " . $broker_needs_pickup_email_sendby . " on " . $broker_needs_pickup_email_sendon . " CT";  ?>
            &nbsp;
            <?php
                        echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT";
                        ?>
        </td>
    </tr>
</table>
<br><br>
<?php

        }

        ?>

<?php

    }
    if ($_REQUEST["broker_needs_pickup_eml_ignore"] == 1) {

        $qry = "Update loop_transaction_buyer set `broker_needs_pickup_eml_ignore` = 1, `broker_needs_pickup_eml_ignore_dt` = ?, `broker_needs_pickup_eml_ignore_user` = ? WHERE id = ?";
        $res1 = db_query($qry, array("s", "s", "i"), array(date("Y-m-d H:i:s"), $_COOKIE['userinitials'], $_REQUEST["rec_id"]));
    ?>

<?php
        $rec_found = "n";
        $broker_needs_pickup_email_sendon = "";
        $broker_needs_pickup_email_sendby = "";
        $getdata = db_query("Select broker_needs_pickup_email_sendby, broker_needs_pickup_email_sendon from loop_transaction_buyer_ship_eml_data where trans_rec_id = " . $_REQUEST["rec_id"] . " and broker_needs_pickup_email_flg = 1");
        while ($getdata_row = array_shift($getdata)) {
            $rec_found = "y";
            $broker_needs_pickup_email_sendon = $getdata_row["broker_needs_pickup_email_sendon"];
            $broker_needs_pickup_email_sendby = $getdata_row["broker_needs_pickup_email_sendby"];
        }

        if ($rec_found == "n") { ?>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 400px">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="2">
            <font size="1">Broker Needs Pickup Dock Appt E-mail</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td align="center" colspan="2" height="13" class="style1">
            <?php if ($freightupdates == 0) {
                            echo "<font size=1 color=red>OPT OUT</font>";
                        } ?>
            <input type="button" name="btnsendfreml" id="btnsendfreml" value="Send Email"
                onclick="parent.reminder_popup_set3(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,'<?php echo $_REQUEST["rec_type"]; ?>')" />
            &nbsp;&nbsp;
            <font size="1" Face="arial">
                <?php
                            echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT";
                            ?>
            </font>

        </td>
    </tr>
</table>
<br><br>
<?php
        } else { ?>

<table cellSpacing="1" cellPadding="1" border="0" style="width: 400px">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="2">
            <font size="1">Broker Needs Pickup Dock Appt E-mail</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td colspan="2" class="style1">
            <?php echo "Email Sent by " . $broker_needs_pickup_email_sendby . " on " . $broker_needs_pickup_email_sendon . " CT";  ?>
            &nbsp;
            <?php
                        echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT";
                        ?>
        </td>
    </tr>
</table>
<br><br>
<?php     } ?>

<?php     }
}

if (isset($_REQUEST["ship_bubble_book_freight_ignore"])) {
    if ($_REQUEST["ship_bubble_book_freight_ignore"] == 1) {

        $qry = "Update loop_transaction_buyer set `booking_freight_email_ignore` = 1, `booking_freight_email_ignore_dt` = ?, `booking_freight_email_ignore_user` = ? WHERE id = ?";
        $res1 = db_query($qry, array("s", "s", "i"), array(date("Y-m-d H:i:s"), $_COOKIE['userinitials'], $_REQUEST["rec_id"]));

        $pickup_or_ucb_delivering = 0;
        $getdata = db_query("Select customerpickup_ucbdelivering_flg from loop_transaction_buyer where id = " . $_REQUEST["rec_id"]);
        while ($getdata_row = array_shift($getdata)) {
            $pickup_or_ucb_delivering = $getdata_row["customerpickup_ucbdelivering_flg"];
        }

        if ($pickup_or_ucb_delivering != "") {
            $rec_found = "n";
            $eml_sendon = "";
            $eml_sendby = "";
            $getdata = db_query("Select email_sendon, email_sendby From loop_transaction_buyer_scheduleeml where trans_rec_id = " . $_REQUEST["rec_id"] . " limit 1");
            while ($getdata_row = array_shift($getdata)) {
                $rec_found = "y";
                $eml_sendon = $getdata_row["email_sendon"];
                $eml_sendby = $getdata_row["email_sendby"];
            }

            if ($rec_found == "n") { ?>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="2">
            <font size="1">Booking Freight Email</font>
        </td>
    </tr>
    <?php if ($pickup_or_ucb_delivering == 1) { ?>
    <tr bgColor="#e4e4e4">
        <td align="left" height="13" colspan="2" class="style1">
            <?php if ($freightupdates == 0) {
                                    echo "<font size=1 color=red>OPT OUT</font>";
                                } ?>
            <input type="button" id="sch_eml_p1" value="Send Email (Customer Pickup)"
                onclick="parent.reminder_popup_set2_customer_pickup(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,<?php echo $_REQUEST["rec_type"]; ?>)">
            &nbsp;&nbsp;
            <font size="1" Face="arial">
                <?php echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT"; ?>
            </font>
        </td>
    </tr>
    <?php } ?>
    <?php if ($pickup_or_ucb_delivering == 2) { ?>
    <tr bgColor="#e4e4e4">
        <td align="left" height="13" colspan="2" class="style1">
            <?php if ($freightupdates == 0) {
                                    echo "<font size=1 color=red>OPT OUT</font>";
                                } ?>
            <input type="button" id="sch_eml_p1" value="Send Email (UCB Delivering)"
                onclick="parent.reminder_popup_set2(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,<?php echo $_REQUEST["rec_type"]; ?>)">
            &nbsp;&nbsp;
            <font size="1" Face="arial">
                <?php echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT"; ?>
            </font>

        </td>
    </tr>
    <?php } ?>
</table>
<br><br>

<?php
            } else { ?>

<table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="2">
            <font size="1">Booking Freight Email</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td colspan="2" class="style1">
            <?php echo "Email sent on: " . date("m/d/Y H:i:s", strtotime($eml_sendon)) . " CT" . " by: " . $eml_sendby; ?>
            &nbsp;

            <?php if ($pickup_or_ucb_delivering == 1) { ?>
            <?php if ($freightupdates == 0) {
                                    echo "<font size=1 color=red>OPT OUT</font>";
                                } ?>
            <input type="button" id="sch_eml_p1" value="Re-Send Email (Customer Pickup)"
                onclick="parent.reminder_popup_set2_customer_pickup(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,<?php echo $_REQUEST["rec_type"]; ?>)">
            <?php } ?>
            <?php if ($pickup_or_ucb_delivering == 2) { ?>
            <?php if ($freightupdates == 0) {
                                    echo "<font size=1 color=red>OPT OUT</font>";
                                } ?>
            <input type="button" id="sch_eml_p1" value="Re-Send Email (UCB Delivering)"
                onclick="parent.reminder_popup_set2(<?php echo $_REQUEST["ID"]; ?>,<?php echo $_REQUEST["rec_id"]; ?>,<?php echo $_REQUEST["warehouse_id"]; ?>,<?php echo $_REQUEST["rec_type"]; ?>)">
            <?php } ?>

            <?php
                            echo "Step is ignore by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s") . " CT";
                            ?>
        </td>
    </tr>
</table>
<br><br>

<?php


            }
        }
    }
}