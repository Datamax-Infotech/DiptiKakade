<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

?>
<style>
.style12rep {
    font-size: xx-small;
    font-family: Arial, Helvetica, sans-serif;
    color: #333333;
}

.table-wrapper {
    /*border: 1px solid red;*/
    width: 645px;
    height: 500px;
    overflow: auto;
}

table.tbstyle {
    width: 1200px;
}
</style>

<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black'
    onclick="document.getElementById('light_addinventoryitem').style.display='none'"
    ;document.getElementById('fade').style.display='none'>Close</a>
<div class="table-wrapper">
    <table cellSpacing="1" cellPadding="1" border="0" class="tbstyle">
        <tr align="middle">
            <td colspan="14" class="style24" style="height: 16px"><strong>Select Inventory Items</strong></td>
        </tr>
        <tr vAlign="left">

            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>Add</font>
            </td>

            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>Actual</font>
            </td>
            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>After PO</font>
            </td>
            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>Last Month Quantity</font>
            </td>
            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>Availability</font>
            </td>

            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>Vendor</font>
            </td>
            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>Ship From</font>
            </td>

            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>L x W x H</font>
            </td>
            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>Description</font>
            </td>
            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>SKU</font>
            </td>
            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>Per Pallet</font>
            </td>
            <td bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>Per Trailer</font>
            </td>
            <td width="50px" bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>MIN FOB</font>
            </td>
            <td width="50px" bgColor="#FF9900">
                <font face='Arial, Helvetica, sans-serif' size='1'>COST</font>
            </td>

        </tr>

        <?php

        $x = 0;
        $sql = "";

        if (($_REQUEST["salesflg"] == "y")) {
            $sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory left JOIN vendors ON inventory.vendor = vendors.id WHERE quantity > 0 and inventory.Active LIKE 'A' ORDER BY L";
            //$sql = "Select * from inventory WHERE quantity > 0 AND active LIKE 'A' ORDER BY LengthInch, LengthFraction ASC";
        }
        if (($_REQUEST["rescueflg"] == "y")) {
            //$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.Active LIKE 'A' ORDER BY inventory.availability DESC, vendors.name ASC";
            $sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory left JOIN vendors ON inventory.vendor = vendors.id WHERE quantity > 0 and inventory.Active LIKE 'A' ORDER BY L";
        }

        db_b2b();
        $dt_view_res = db_query($sql);

        $tipStr = "";

        while ($inv = array_shift($dt_view_res)) {
            $tipStr = "B2B ID: " . $inv["I"] . "<br>";
            $tipStr .= "Notes: " . $inv["N"] . "<br>";

            if ($x == 0) {
                $x = 1;
                $bg = "#e4e4e4";
            } else {
                $x = 0;
                $bg = "#f4f4f4";
            }

            if ($inv["shape_rect"] == "1")

                $tipStr = $tipStr . " Rec ";



            if ($inv["shape_oct"] == "1")

                $tipStr = $tipStr . " Oct ";



            if ($inv["wall_2"] == "1")

                $tipStr = $tipStr . " 2W ";



            if ($inv["wall_3"] == "1")

                $tipStr = $tipStr . " 3W ";



            if ($inv["wall_4"] == "1")

                $tipStr = $tipStr . " 4W ";



            if ($inv["wall_5"] == "1")

                $tipStr = $tipStr . " 5W ";



            if ($inv["top_nolid"] == "1")

                $tipStr = $tipStr . " No Top,";



            if ($inv["top_partial"] == "1")

                $tipStr = $tipStr . " Flange Top, ";



            if ($inv["top_full"] == "1")

                $tipStr = $tipStr . " FFT, ";



            if ($inv["top_hinged"] == "1")

                $tipStr = $tipStr . " Hinge Top, ";



            if ($inv["top_remove"] == "1")

                $tipStr = $tipStr . " Lid Top, ";



            if ($inv["bottom_no"] == "1")

                $tipStr = $tipStr . " No Bottom";



            if ($inv["bottom_partial"] == "1")

                $tipStr = $tipStr . " PB w/o SS";



            if ($inv["bottom_partialsheet"] == "1")

                $tipStr = $tipStr . " PB w/ SS";



            if ($inv["bottom_fullflap"] == "1")

                $tipStr = $tipStr . " FFB";



            if ($inv["bottom_interlocking"] == "1")

                $tipStr = $tipStr . " FB";



            if ($inv["bottom_tray"] == "1")

                $tipStr = $tipStr . " Tray Bottom";



            if ($inv["vents_no"] == "1")

                $tipStr = $tipStr . "";



            if ($inv["vents_yes"] == "1")

                $tipStr = $tipStr . ", Vents";
        ?>
        <?php

            $b2b_ulineDollar = round($inv["ulineDollar"]);
            $b2b_ulineCents = $inv["ulineCents"];
            $b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
            $b2b_fob = "$ " . number_format($b2b_fob, 2);

            $b2b_costDollar = round($inv["costDollar"]);
            $b2b_costCents = $inv["costCents"];
            $b2b_cost = $b2b_costDollar + $b2b_costCents;
            $b2b_cost = "$ " . number_format($b2b_cost, 2);

            $bpallet_qty = 0;
            $boxes_per_trailer = 0;
            $box_type = "";
            $qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type from loop_boxes where b2b_id=" . $inv["I"];
            $sku = "";
            $loop_id = 0;
            db();
            $dt_view_sku = db_query($qry_sku);
            while ($sku_val = array_shift($dt_view_sku)) {
                $loop_id = $sku_val['id'];
                $sku = $sku_val['sku'];
                $bpallet_qty = $sku_val['bpallet_qty'];
                $boxes_per_trailer = $sku_val['boxes_per_trailer'];
                $box_type = $sku_val['type'];
            }

            $rec_found_box = "n";
            $dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $loop_id . " order by warehouse, type_ofbox, Description";
            db_b2b();
            $dt_view_res_box = db_query($dt_view_qry);

            $actual_val = 0;
            $after_po_val = 0;
            $last_month_qty = 0;
            $pallet_val = "";
            $pallet_val_afterpo = "";
            $tmp_noofpallet = 0;
            $ware_house_boxdraw = "";
            $preorder_txt = "";
            $preorder_txt2 = "";
            while ($dt_view_row = array_shift($dt_view_res_box)) {
                $rec_found_box = "y";
                $sales_order_qty = $dt_view_row["sales_order_qty"];
                $actual_val = $dt_view_row["actual"];
                $last_month_qty = $dt_view_row["lastmonthqty"];

                if ($dt_view_row["actual"] != 0 or $dt_view_row["actual"] - $sales_order_qty != 0) {
                    $lastmonth_val = $dt_view_row["lastmonthqty"];

                    $preorder_txt = "";
                    $preorder_txt2 = "";

                    if (isset($reccnt) > 0) {
                        $preorder_txt = "<u>";
                        $preorder_txt2 = "</u>";
                    }

                    if (($dt_view_row["actual"] >= $dt_view_row["per_trailer"]) && ($dt_view_row["per_trailer"] > 0)) {
                        $bg = "yellow";
                    }

                    $pallet_val = 0;
                    $pallet_val_afterpo = 0;
                    $actual_po = $dt_view_row["actual"] - $sales_order_qty;
                    $after_po_val = $actual_po;

                    if ($dt_view_row["per_pallet"] > 0) {
                        $pallet_val = number_format($dt_view_row["actual"] / $dt_view_row["per_pallet"], 1, '.', '');
                        $pallet_val_afterpo = number_format($actual_po / $dt_view_row["per_pallet"], 1, '.', '');
                    }

                    $pallet_space_per = "";

                    if ($pallet_val > 0) {
                        $tmppos_1 = strpos($pallet_val, '.');
                        if ($tmppos_1 != false) {
                            if (intval(substr($pallet_val, strpos($pallet_val, '.') + 1, 1)) > 0) {
                                $pallet_val_temp = $pallet_val;
                                $pallet_val = " (" . $pallet_val_temp . ")";
                            } else {
                                $pallet_val_format = number_format(floatval($pallet_val), 0);
                                $pallet_val = " (" . $pallet_val_format . ")";
                            }
                        } else {
                            $pallet_val_format = number_format(floatval($pallet_val), 0);
                            $pallet_val = " (" . $pallet_val_format . ")";
                        }
                    } else {
                        $pallet_val = "";
                    }

                    if ($pallet_val_afterpo > 0) {
                        $tmppos_1 = strpos($pallet_val_afterpo, '.');
                        if ($tmppos_1 != false) {
                            if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo, '.') + 1, 1)) > 0) {
                                $pallet_val_afterpo_temp = $pallet_val_afterpo;
                                $pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
                            } else {
                                $pallet_val_afterpo_format = number_format(floatval($pallet_val_afterpo), 0);
                                $pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
                            }
                        } else {
                            $pallet_val_afterpo_format = number_format(floatval($pallet_val_afterpo), 0);
                            $pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
                        }
                    } else {
                        $pallet_val_afterpo = "";
                    }

                    $pallet_space_per = "";
                }
            }
            if ($rec_found_box == "n") {
                $actual_val = $inv["actual_inventory"];
                $after_po_val = $inv["after_actual_inventory"];
                $last_month_qty = $inv["lastmonthqty"];

                if (($actual_val >= $boxes_per_trailer) && ($boxes_per_trailer > 0)) {
                    $bg = "yellow";
                }
            }

            //$to_show_rec = "y";
            //if ($rec_found_box == "n" && ($inv["box_type"] == 'Box' || $inv["box_type"] == 'GaylordUCB')){
            //	$to_show_rec = "n";
            //}
            ?>

        <tr vAlign="center">

            <td bgColor="<?php echo $bg; ?>" class="style12rep">
                <font size=1><a href="#"
                        onclick="add_invitem(<?php echo $inv["I"]; ?>, <?php echo $_REQUEST["companyID"]; ?>);return false;">Add</a>
                </font>
            </td>

            <td bgColor="<?php echo $bg; ?>" class="style12rep">
                <font size=1><?php if ($inv["availability"] == "3") echo "<b>"; ?>
                    <?php if ($actual_val < 0) { ?>
                    <font color='red'> <?php echo $actual_val . $pallet_val; ?></font>
            </td>
            <?php     } else { ?>
            <font color='green'><?php echo $actual_val . $pallet_val; ?></font>
            </td>
            <?php  } ?>
            <td bgColor="<?php echo $bg; ?>" class="style12rep">
                <font size=1><?php if ($inv["availability"] == "3") echo "<b>"; ?>
                    <?php if ($after_po_val < 0) { ?>
                    <font color='blue'><?php echo $after_po_val . $pallet_val_afterpo . $preorder_txt2; ?> </font>
            </td>
            <?php } else { ?>
            <font color='green'><?php echo $after_po_val . $pallet_val_afterpo . $preorder_txt2; ?> </font>
            </td>
            <?php } ?>

            <td bgColor="<?php echo $bg; ?>" class="style12rep">
                <font size=1><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $last_month_qty; ?></a>
                </font>
            </td>

            <td bgColor="<?php echo $bg; ?>" class="style12rep"><?php if ($inv["availability"] == "3") echo "<b>"; ?>
                <?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
                <?php if ($inv["availability"] == "2") echo "Available Now"; ?>
                <?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
                <?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
                <?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
                <?php if ($inv["availability"] == "-1") echo "Presell"; ?>
                <?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
                <?php if ($inv["availability"] == "-3") echo "Potential"; ?>
                <?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
            </td>
            <td bgColor="<?php echo $bg; ?>" class="style12rep">
                <font size=1>
                    <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["VN"]; ?></a>
                </font>
            </td>
            <td bgColor="<?php echo $bg; ?>" class="style12rep">
                <font size=1>
                    <?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
                </font>
            </td>

            <td bgColor="<?php echo $bg; ?>" width="60" class="style12rep">
                <font size=1>
                    <?php echo $inv["L"] . " " . $inv["LF"] . " x " . $inv["W"] . " " . $inv["WF"] . " x " . $inv["D"] . " " . $inv["DF"]; ?>
                </font>
            </td>

            <td bgColor="<?php echo $bg; ?>" class="style12rep">
                <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a
                    href="http://loops.usedcardboardboxes.com/manage_box_b2bloop.php?id=<?php echo $loop_id; ?>&proc=View&"
                    target="_blank" <?php echo " onmouseover=\"Tip('" . $tipStr . "')\" onmouseout=\"UnTip()\""; ?>>
                    <font face='Arial, Helvetica, sans-serif' size='1'><?php echo $inv["description"]; ?></font>
                </a></td>

            <td bgColor="<?php echo $bg; ?>" class="style12rep">
                <font size=1>
                    <?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $sku; ?>
                </font>
            </td>

            <td bgColor="<?php echo $bg; ?>">
                <font face='Arial, Helvetica, sans-serif' size='1'><?php echo $bpallet_qty; ?></font>
            </td>
            <td bgColor="<?php echo $bg; ?>">
                <font face='Arial, Helvetica, sans-serif' size='1'><?php echo $boxes_per_trailer ?></font>
            </td>
            <td bgColor="<?php echo $bg; ?>">
                <font face='Arial, Helvetica, sans-serif' size='1'><?php echo $b2b_fob ?></font>
            </td>
            <td bgColor="<?php echo $bg; ?>">
                <font face='Arial, Helvetica, sans-serif' size='1'><?php echo $b2b_cost ?></font>
            </td>
        </tr>
        <?php
        }
        ?>

    </table>
</div>