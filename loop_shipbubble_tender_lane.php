<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


db();

if ($_REQUEST["inadd"] == "yes") {
    $qry = "INSERT INTO loop_transaction_buyer_freightview SET trans_rec_id = " . $_REQUEST["rec_id"] . ", broker_id = '" . $_REQUEST["freight_booking_broker"] . "', booked_delivery_cost = '" . $_REQUEST["txtbooked_delivery_cost"] . "', link = '" . $_REQUEST["freight_ent_link"] . "' , employeeid = " . $_COOKIE["employeeid"];
    $result2 = db_query($qry);
}

if ($_REQUEST["inedit"] == "yes") {
    $qry = "Update loop_transaction_buyer_freightview SET link = '" . $_REQUEST["freight_ent_link"] . "', broker_id = '" . $_REQUEST["freight_booking_broker"] . "', booked_delivery_cost = '" . $_REQUEST["txtbooked_delivery_cost"] . "',  employeeid = " . $_COOKIE["employeeid"] . " where trans_rec_id = " . $_REQUEST["rec_id"];
    $result2 = db_query($qry);
}

if ($_REQUEST["indelete"] == "yes") {
    $qry = "delete from loop_transaction_buyer_freightview where trans_rec_id = " . $_REQUEST["rec_id"];
    $result2 = db_query($qry);
}

function get_initials_from_id_new(int $id): string
{
    $dt_so = "SELECT * FROM loop_employees WHERE id = " . $id;
    $dt_res_so = db_query($dt_so);

    while ($so_row = array_shift($dt_res_so)) {
        return $so_row["initials"];
    }

    return "";
}

function timestamp_to_datetime_new(string $d): string
{
    $da = explode(" ", $d);
    $dp = explode("-", $da[0]);
    $dh = explode(":", $da[1]);

    $x = $dp[1] . "/" . $dp[2] . "/" . $dp[0];


    if ((int)$dh[0] - 2 > 12) {
        $x = $x . " " . ((int)$dh[0] - 12) . ":" . $dh[1] . "PM CT";
    } else {
        $x = $x . " " . ($dh[0]) . ":" . $dh[1] . "AM CT";
    }

    return $x;
}


?>
<LINK rel='stylesheet' type='text/css' href='one_style_mrg.css'>

<!------------------------------ Enterprise TMS Freight Uploaded Data ------------------------------>

<?php
//Tender the lane table
$po_delivery_dt = "";
$freight_cost = 0;
$so_view_qry = "SELECT * FROM loop_transaction_buyer WHERE id = '" .  $_REQUEST['rec_id'] . "'";
$so_view_res = db_query($so_view_qry);
while ($trans_buyer_row = array_shift($so_view_res)) {
    $ponumber = $trans_buyer_row["po_ponumber"];
    $virtual_inventory_company_id = $trans_buyer_row["virtual_inventory_company_id"];
    $virtual_inventory_trans_id = $trans_buyer_row["virtual_inventory_trans_id"];
    $po_delivery_dt = $trans_buyer_row["po_delivery_dt"];
    $freight_cost = $trans_buyer_row["po_freight"];
}

$sql = "SELECT * FROM loop_transaction_buyer_freightview WHERE trans_rec_id=" . $_REQUEST["rec_id"];

$result2 = db_query($sql);
$rows = array_shift($result2);
$tmp_link = "";
$tmp_timeframe = "";
$tmp_delivery_cost = "";
$broker_id = 0;

if ($rows["id"] > 0) {

    $sql = "SELECT * FROM loop_transaction_buyer_freightview WHERE trans_rec_id = " . $_REQUEST["rec_id"];
    $sql_res = db_query($sql);
    while ($row = array_shift($sql_res)) {
        $tmp_link = $row["link"];
        $tmp_timeframe = $row["timeframe"];
        $tmp_delivery_cost = $row["booked_delivery_cost"];
        $broker_id = $row["broker_id"];

        if (!isset($_REQUEST["edit"])) {
?>
<form action="loop_shipbubble_pickup_or_ucb_delivering.php" method="post">
    <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST[" warehouse_id"]; ?>" />
    <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>" />
    <input type="hidden" name="rec_type" value="<?php echo $_REQUEST[" rec_type"]; ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />
    <input type="hidden" name="trans_rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
        <tr align="middle">
            <td bgColor="#99FF99" colSpan="2">
                <font size="1">Tender the Lane</font>
                <!-- &nbsp;<input type="submit" name="btntenderlane_edit" id="btntenderlane_edit" value="Edit" />
							&nbsp;<input type="submit" name="btntenderlane_delete" id="btntenderlane_delete" value="Delete" onclick="return confirm('Are you sure you want to delete this record?')" />
							-->

                &nbsp;<input type="button" name="btntenderlane_edit" id="btntenderlane_edit" value="Edit" onclick="tender_lane_add('editflg' , <?php echo $_REQUEST[" ID"]; ?>,
                <?php echo $_REQUEST["rec_id"]; ?>,
                <?php echo $_REQUEST["warehouse_id"]; ?>,'
                <?php echo $_REQUEST["rec_type"]; ?>')" />
                &nbsp;<input type="button" name="btntenderlane_delete" id="btntenderlane_delete" value="Delete" onclick="tender_lane_add('deleteflg' , <?php echo $_REQUEST[" ID"]; ?>,
                <?php echo $_REQUEST["rec_id"]; ?>,
                <?php echo $_REQUEST["warehouse_id"]; ?>,'
                <?php echo $_REQUEST["rec_type"]; ?>')" />
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
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $row["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"]; ?>
                </font>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Delivery Budget</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1">$
                    <?php echo $freight_cost; ?>
                </font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Booked Delivery Cost</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1">$
                    <?php echo $row["booked_delivery_cost"]; ?>
                </font>
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
                <font size="1"><?php echo timestamp_to_datetime_new($row["dt"]) ?></font>
            </td>
        </tr>
    </table>
</form>
<?php
        }
    } //while loop

    if (isset($_REQUEST["edit"])) {
        if ($_REQUEST["edit"] == "freight_enterprise") {


        ?>
<br /><br />
<form action="loop_shipbubble_tender_lane.php" method="post">
    <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST[" warehouse_id"]; ?>" />
    <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>" />
    <input type="hidden" name="rec_type" value="<?php echo $_REQUEST[" rec_type"]; ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />
    <input type="hidden" name="trans_rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />
    <input type="hidden" name="inedit" id="inedit" value="yes" />

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
        <tr align="middle">
            <td bgColor="#fb8a8a" colSpan="2">
                <font size="1">Tender the Lane</font>
                <input type="button" name="btntenderlane_delete" id="btntenderlane_delete" value="Delete" onclick="tender_lane_add('deleteflg' , <?php echo $_REQUEST[" ID"]; ?>,
                <?php echo $_REQUEST["rec_id"]; ?>,
                <?php echo $_REQUEST["warehouse_id"]; ?>,
                <?php echo $_REQUEST["rec_type"]; ?>)" />
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
                                                }
                                                ?> value="
                            <?php echo $fmyrowsel["id"]; ?>">
                            <?php echo $fmyrowsel["company_name"]; ?>
                        </option>
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
                <font size="1">$
                    <?php echo $freight_cost; ?>
                </font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Booked Delivery Cost</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <font size="1"><input type="text" name="txtbooked_delivery_cost" id="txtbooked_delivery_cost"
                        value="<?php echo $tmp_delivery_cost; ?>" size=15></font>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">View In Freightview</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <input type="text" id="freight_ent_link" name="freight_ent_link" value="<?php echo $tmp_link; ?>">
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" colspan="2" align="center"><input type="button" value="Submit" onclick="tender_lane_add('editdataflg' , <?php echo $_REQUEST[" ID"]; ?>,
                <?php echo $_REQUEST["rec_id"]; ?>,
                <?php echo $_REQUEST["warehouse_id"]; ?>,'
                <?php echo $_REQUEST["rec_type"]; ?>')" />
            </td>
        </tr>

    </table>
</form>
<?php

        }
    }
} else {
    $broker_id = 0;
    ?>
<form action="add_freightview_mrg_new.php" method="post">
    <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST[" warehouse_id"]; ?>" />
    <input type="hidden" name="ID" value="<?php echo $_REQUEST[" ID"]; ?>" />
    <input type="hidden" name="rec_type" value="<?php echo $_REQUEST[" rec_type"]; ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />
    <input type="hidden" name="trans_rec_id" value="<?php echo $_REQUEST[" rec_id"]; ?>" />

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
        <tr align="middle">
            <td bgColor="#c0cdda" colSpan="2">
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
                                        }
                                        ?> value="
                            <?php echo $fmyrowsel["id"]; ?>">
                            <?php echo $fmyrowsel["company_name"]; ?>
                        </option>
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
                <font size="1">$
                    <?php echo $freight_cost; ?>
                </font>
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
                <font size="1"><input type="button" onclick="tender_lane_add('addflg' ,<?php echo $_REQUEST[" ID"]; ?>,
                    <?php echo $_REQUEST["rec_id"]; ?>,
                    <?php echo $_REQUEST["warehouse_id"]; ?>,'
                    <?php echo $_REQUEST["rec_type"]; ?>')" value="Add">
                </font>
            </td>
        </tr>
    </table>
</form>
<?php

}

?>