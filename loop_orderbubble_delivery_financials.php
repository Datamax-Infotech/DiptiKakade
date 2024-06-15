<div id="table_loop_orderbubble_delivery_financials">
    Loading .....<img src='images/wait_animated.gif' />
</div>

<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();


?>
<style>
hr {
    border: none;
    border-top: 1px dashed #000;
    color: #e4e4e4;
    background-color: #e4e4e4;
    height: 1px;
    width: 90%;
}
</style>
<br>
<font size="1">
    <?php

    $min_FOB_of_sales = "";
    $fob_difference = "";


    if (isset($_REQUEST["btnConfirm"])) {
        if ($_REQUEST["rec_id"] != "") {
            $sql = "Update loop_transaction_buyer set confirm_delivery_financials = 1, confirm_delivery_financials_user = '" . $_COOKIE['userinitials'] . "', confirm_delivery_financials_dt = '" . date("Y-m-d H:i:s") . "' WHERE id = " . $_REQUEST["rec_id"];
            //echo $sql;
            $result = db_query($sql);
        }
    }

    if (isset($_REQUEST["btnConfirmundo"])) {
        if ($_REQUEST["rec_id"] != "") {
            $sql = "Update loop_transaction_buyer set confirm_delivery_financials = 0, confirm_delivery_financials_user = '', confirm_delivery_financials_dt = '' WHERE id = " . $_REQUEST["rec_id"];
            $result = db_query($sql);
        }
    }

    $so_edit = "";
    if (isset($_REQUEST["btnsoedit"])) {
        $so_edit = "yes";
    }

    $confirm_delivery_financials = "";
    $confirm_delivery_financials_user = "";
    $confirm_delivery_financials_dt = "";

    $dtt_view_qry = "SELECT * from loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
    $dtt_view_res1 = db_query($dtt_view_qry);
    while ($dtt_view_res = array_shift($dtt_view_res1)) {
        $confirm_delivery_financials = $dtt_view_res["confirm_delivery_financials"];

        if ($dtt_view_res["confirm_delivery_financials"] == 1) {
            $confirm_delivery_financials_user = $dtt_view_res["confirm_delivery_financials_user"];
            $confirm_delivery_financials_dt = $dtt_view_res["confirm_delivery_financials_dt"];
        }
    }

    $quote_number = 0;
    $freight_cost = 0;
    $po_poorderamount = 0;
    $dtt_view_qry = "SELECT quote_number, po_freight, po_poorderamount from loop_transaction_buyer WHERE id = " . $_REQUEST["rec_id"];
    $dtt_view_res1 = db_query($dtt_view_qry);
    while ($dtt_view_res = array_shift($dtt_view_res1)) {
        $quote_number = $dtt_view_res["quote_number"];
        $freight_cost = $dtt_view_res["po_freight"];
        $po_poorderamount = $dtt_view_res["po_poorderamount"];
    }

    $salesorder_qty = 0;
    $weighted_average = 0;
    $get_sales_order = db_query("Select loop_salesorders.qty, loop_boxes.b2b_id, loop_boxes.boxgoodvalue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $_REQUEST["rec_id"]);
    while ($dtt_view_res = array_shift($get_sales_order)) {
        $salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];

        $boxgoodvalue = $dtt_view_res["boxgoodvalue"];

        $boxgoodvaluearray    = explode(".", $boxgoodvalue);
        $boxgoodvalueDollar = $boxgoodvaluearray[0];
        $boxgoodvalueCents    = "0." . $boxgoodvaluearray[1];

        //get Min FOB
        db_b2b();
        $min_fob = 0;
        $get_box_data = db_query("Select ulineDollar, ulineCents, costDollar, costCents, overhead_costDollar, overhead_costCents  from inventory where ID = " .  $dtt_view_res["b2b_id"]);
        while ($box_data_res = array_shift($get_box_data)) {
            $b2b_ulineDollar = round($box_data_res["ulineDollar"]);
            $b2b_ulineCents = $box_data_res["ulineCents"];
            $min_fob = $b2b_ulineDollar + $b2b_ulineCents;

            $min_FOB_of_sales = $box_data_res["ulineDollar"] + $box_data_res["ulineCents"];

            $b2b_ovh_costDollar = round($box_data_res["overhead_costDollar"]);
            $b2b_ovh_costCents = $box_data_res["overhead_costCents"];

            $b2b_costDollar = floatval($boxgoodvalueDollar) + floatval($b2b_ovh_costDollar);
            $b2b_costCents = $boxgoodvalueCents + $b2b_ovh_costCents;

            $b2b_cost = $b2b_costDollar + $b2b_costCents;
            $b2b_cost_val = "$" . number_format($b2b_cost, 2);
        }
        if ($min_fob > 0) {
            $weighted_average = $weighted_average + ($dtt_view_res["qty"] * $min_fob);
        }
        db();
    }

    $weighted_average_fin =  $weighted_average / $salesorder_qty;

    $get_sales_order = db_query("Select qty from loop_salesorders_manual WHERE trans_rec_id = " .  $_REQUEST["rec_id"]);
    while ($dtt_view_res = array_shift($get_sales_order)) {
        $salesorder_qty = $salesorder_qty + $dtt_view_res["qty"];
    }

    //
    $quote_total = 0;
    /*if ($quote_number > 0) {
	db_b2b();
	$query = "SELECT quote_total FROM quote WHERE ID=" . $quote_number;
	$dt_view_res3 = db_query($query);
	while ($objQuote= array_shift($dt_view_res3)) {
		$quote_total = $objQuote["quote_total"];
	}
	db();
}

if ($quote_total == 0)
{	*/
    $quote_total = $po_poorderamount;
    //}	
    //
    $cogs_val = isset($b2b_cost) * $salesorder_qty;
    $cogs = (-$freight_cost) - $cogs_val;
    //
    ?>
    <form action="loop_orderbubble_delivery_financials.php" method="post">
        <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
        <input type="hidden" name="ID" value="<?php echo $_REQUEST["ID"]; ?>" />
        <input type="hidden" name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />
        <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
        <input type="hidden" name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
        <table cellSpacing="1" cellPadding="1" border="0" style="width: 444px">
            <tr align="middle">
                <?php if ($confirm_delivery_financials == 1) { ?>
                <td bgColor="#99FF99" colSpan="2">
                    <?php } ?>
                    <?php if ($confirm_delivery_financials == 0) { ?>
                <td bgColor="#fb8a8a" colSpan="2">
                    <?php } ?>
                    <font size="1"><?php echo strtoupper("Confirm Delivery Financials"); ?></font>
                </td>
            </tr>

            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 100px" class="style1">
                    <font size="1">Quote Total Price</font>
                </td>
                <td align="left" height="13" style="width: 235px" class="style1">
                    <font size="1">$<?php echo number_format($quote_total, 2); ?></font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 100px" class="style1">
                    <font size="1">Delivery Budget</font>
                </td>
                <td align="left" height="13" style="width: 235px" class="style1">
                    <font size="1">
                        $<?php echo number_format($freight_cost, 2); ?>
                    </font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" colspan="2" style="width: 100px" class="style1">
                    <hr />
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 100px" class="style1">
                    <font size="1">FOB Origin Price</font>
                </td>
                <td align="left" height="13" style="width: 235px" class="style1">
                    <font size="1">$<?php echo number_format(($quote_total - $freight_cost), 2); ?></font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 100px" class="style1">
                    <font size="1">Sales Order Quantity</font>
                </td>
                <td align="left" height="13" style="width: 235px" class="style1">
                    <font size="1"><?php echo number_format($salesorder_qty, 0); ?></font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" colspan="2" style="width: 100px" class="style1">
                    <hr />
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 100px" class="style1">
                    <font size="1">FOB Origin per Unit</font>
                </td>
                <td align="left" height="13" style="width: 235px" class="style1">
                    <?php
                    //echo number_format((($quote_total - $freight_cost)/$salesorder_qty),2) . " | " . number_format($weighted_average_fin,2) . "<br>";
                    if (number_format((($quote_total - $freight_cost) / $salesorder_qty), 2) >= number_format($weighted_average_fin, 2)) { ?>
                    <?php
                        $minfob_val1 = ($quote_total - $freight_cost);
                        $minfob_val = $minfob_val1 / $salesorder_qty;
                        $fob_difference_val = $minfob_val - $weighted_average_fin;
                        if ($fob_difference_val > 0) {
                            $fob_difference = "(+" . number_format($fob_difference_val, 2) . ")";
                        } elseif ($fob_difference_val < 0) {
                            $fob_difference = "(-" . number_format($fob_difference_val, 2) . ")";
                        } elseif ($fob_difference_val == 0) {
                            $fob_difference = "(" . $fob_difference_val . ")";
                        }

                        ?>
                    <font size="1" color="green">
                        $<?php echo number_format((($quote_total - $freight_cost) / $salesorder_qty), 2) . " " . $fob_difference; ?>
                    </font>
                    <?php } else { ?>
                    <?php
                        $minfob_val1 = ($quote_total - $freight_cost);
                        $minfob_val = $minfob_val1 / $salesorder_qty;
                        $fob_difference_val = number_format($minfob_val - $weighted_average_fin);
                        if ($fob_difference_val > 0) {
                            $fob_difference = "(+" . $fob_difference_val . ")";
                        } elseif ($fob_difference_val < 0) {
                            $fob_difference = "(-" . $fob_difference_val . ")";
                        } elseif ($fob_difference_val == 0) {
                            $fob_difference = "(" . $fob_difference_val . ")";
                        }

                        ?>
                    <font size="1" color="red">
                        $<?php echo number_format((($quote_total - $freight_cost) / $salesorder_qty), 2) . " " . $fob_difference; ?>
                    </font>
                    <?php } ?>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 100px" class="style1">
                    <font size="1">Min FOB of Sales Order</font>
                </td>
                <td align="left" height="13" style="width: 235px" class="style1">
                    <font size="1">$<?php echo number_format($min_FOB_of_sales, 2); ?></font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" colspan="2" style="width: 100px" class="style1">
                    <hr />
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 100px" class="style1">
                    <font size="1">Revenue</font>
                </td>
                <td align="left" height="13" style="width: 235px" class="style1">
                    <font size="1">$<?php echo number_format($quote_total); ?></font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 100px;" class="style1">
                    <font size="1">COGS</font>
                </td>
                <td align="left" height="13" style="width: 235px" class="style1">
                    <font size="1">$<?php echo number_format($cogs); ?></font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" style="width: 100px" class="style1">
                    <font size="1">Gross Profit</font>
                </td>
                <td align="left" height="13" style="width: 235px" class="style1">
                    <?php

                    $gross_profit = $quote_total + $cogs;
                    $margin_calc1 = $gross_profit / $quote_total;
                    $margin_calc = $margin_calc1 * 100;

                    $margin_color = "";
                    if ($margin_calc >= 30) {
                        $margin = "(" . number_format($margin_calc, 2) . "%)";
                        $margin_color = "<span style='color:green;'>";
                    } elseif ($margin_calc < 30 || $margin_calc >= 27) {
                        $margin = "(" . number_format($margin_calc, 2) . "%)";
                        $margin_color = "<span style='color:#FFBE00;'>";
                    } elseif ($margin_calc < 27) {
                        $margin = "(" . number_format($margin_calc, 2) . "%)";
                        $margin_color = "<span style='color:red;'>";
                    }
                    ?>
                    <font size="1">
                        <?php echo $margin_color . " $" . number_format($gross_profit) . " " . isset($margin); ?>
                    </font>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td height="13" colspan="2" style="width: 100px" class="style1">&nbsp;

                </td>
            </tr>



            <tr bgColor="#e4e4e4">
                <td colspan=2 align=center>
                    <font size="1">
                        Click this button to confirm that min FOB has been met or approval has been given in the
                        transaction log by an authorized user<br>
                        <?php
                        if ($confirm_delivery_financials == 1) {
                            echo "Action taken by " . $confirm_delivery_financials_user . " on " . date("m/d/Y H:i:s", strtotime($confirm_delivery_financials_dt)) . " CT"; ?>

                        <input type="submit" value="Undo Confirm" id="btnConfirmundo" name="btnConfirmundo">
                        <?php  } else {
                        ?>
                        <input type="submit" value="Confirm" id="btnConfirm" name="btnConfirm">
                        <?php  }
                        ?>
                    </font>
                </td>
            </tr>
        </table>
    </form>

    <script>
    document.getElementById("table_loop_orderbubble_delivery_financials").style.display = "none";
    </script>