<?php


require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Untitled Document</title>
    <style>
    .th_style {
        font-size: xx-small;
        background-color: #FF9900;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
    }

    .style12_n {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: left !important;
    }

    .style12_num {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: right !important;
    }

    .style12_tot {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        font-weight: bold;
        text-align: right !important;
    }

    .style12_tot_left {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        font-weight: bold;
        text-align: left !important;
    }
    </style>
</head>

<body>
    <?php

    $date_from = $_REQUEST["date_from_val"];
    $date_to = $_REQUEST["date_to_val"];
    $date_from_val = date("Y-m-d", strtotime($date_from));
    $date_to_val_org = date("Y-m-d", strtotime($date_to));
    $date_to_val = date("Y-m-d", strtotime('+1 day', strtotime($date_to)));

    if ($_REQUEST["showquotedata"] == "add_fr") {
    ?>

    <table cellpadding="3">
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Booked
            </th>
            <th class="th_style">
                Company Name
            </th>

            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Additional Costs
            </th>
            <th class="th_style">
                Notes
            </th>
        </tr>
        <?php
            //
            //$dt_view_qry = "SELECT *,loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.ignore = 0 And (transaction_date BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "') GROUP BY loop_transaction_buyer.id";
            $dt_view_qry = "SELECT *, files_companies.name as vendor_nm, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.id AS I from loop_transaction_buyer  
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer.id = loop_transaction_buyer_payments.transaction_buyer_id 
				left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  
				left JOIN loop_employees ON loop_transaction_buyer_payments.employee_id=loop_employees.id 
				WHERE loop_transaction_buyer_payments.typeid in (13) and loop_transaction_buyer_payments.date between '" . $date_from_val . "' AND '" . $date_to_val . "' group by transaction_buyer_id";

            //				$dt_view_qry = "SELECT *, sum(estimated_cost)as actual_amount, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.id AS I, sum(estimated_cost)as actual_amount from loop_transaction_buyer  INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer.id = loop_transaction_buyer_payments.transaction_buyer_id INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id left JOIN loop_employees ON loop_transaction_buyer_payments.employee_id=loop_employees.id WHERE loop_transaction_buyer_payments.typeid in (13) and loop_transaction_buyer_payments.date between '" . $date_from_val. "' AND '" . $date_to_val. "' group by transaction_buyer_id";
            //echo $dt_view_qry;
            $additional_freight_total = 0;
            db();
            $dt_view_res = db_query($dt_view_qry);
            while ($fb_rec = array_shift($dt_view_res)) {
                $wqry = "select * from loop_warehouse where id=" . $fb_rec["D"];
                db();
                $wres = db_query($wqry);
                $wrow = array_shift($wres);
                $comp_id = $wrow["b2bid"];
                $comp_name = $wrow["company_name"];
                $company_name = get_nickname_val($comp_name, $comp_id);
                $rec_display = "buyer_invoice";
                $additional_freight_total = $additional_freight_total + $fb_rec["estimated_cost"];

                $wqry = "select * from loop_transaction_buyer_freightview where trans_rec_id=" . $fb_rec["I"];
                db();
                $wres = db_query($wqry);
                $wrow = array_shift($wres);
                $date_booked = $wrow["dt"];


            ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['initials']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                        $booked_date = date("m/d/Y", strtotime($date_booked));
                        echo $booked_date;
                        ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                            echo $company_name;
                            ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                        /*$freight_sql_b = "Select broker_id from loop_transaction_buyer_freightview WHERE trans_rec_id = " . $fb_rec["I"];
						$freightresult_b = db_query($freight_sql_b);
						$freightrow_b = array_shift($freightresult_b);
						//
						if ($freightrow_b["broker_id"] >0) {
						$freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $freightrow_b["broker_id"];
						$freightresult = db_query($freight_sql);
						$freightrow = array_shift($freightresult);
						echo $freightrow["company_name"]; 
						}*/
                        echo $fb_rec["vendor_nm"];
                        ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="center">
                <?php echo "-$" . number_format($fb_rec["estimated_cost"], 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['notes']; ?>
            </td>
        </tr>

        <?php
            }
            ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" align="center" colspan="5">
                Total Cost of All Additional Charges
            </td>
            <td bgColor="#e4e4e4" class="style12_tot" align="center">
                <?php echo "-$" . number_format($additional_freight_total, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">

            </td>
        </tr>
    </table>
    <br><br>
    <?php
    }
    ?>
    <?php
    if ($_REQUEST["showquotedata"] == "booked_lane") {
    ?>
    <?php
        //------------booked lane - Display UCB delivering (non recycling)-----------------------
        $fsql = "SELECT *,loop_transaction_buyer.warehouse_id AS D, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount, loop_transaction_buyer.id AS I FROM loop_transaction_buyer_freightview INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_transaction_buyer_freightview.trans_rec_id WHERE loop_transaction_buyer.ignore = 0  and (dt >='" . $date_from_val . "') AND (dt <= '" . $date_to_val . " 23:59:59') and recycling_flg=0 and customerpickup_ucbdelivering_flg=2 GROUP BY loop_transaction_buyer.id";
        //echo $dt_view_qry;
        db();
        $dt_view_new = db_query($fsql);
        $total_rows = tep_db_num_rows($dt_view_new);
        if ($total_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $total_diff = 0;
            $total_diff_val = 0;
        ?>
    <table cellpadding="3" cellspacing="1">
        <tr>
            <td colspan="8" align="center" class="th_style">
                UCB Delivery (Non-Recycling)
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Booked
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                while ($fb_rec = array_shift($dt_view_new)) {

                    $wqry = "select * from loop_warehouse where id=" . $fb_rec["D"];
                    db();
                    $wres = db_query($wqry);
                    $wrow = array_shift($wres);
                    $comp_id = $wrow["b2bid"];
                    $comp_name = $wrow["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    //
                    $tot_actual_amount = $tot_actual_amount + $fb_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $booked_date = date("m/d/Y", strtotime($fb_rec['dt']));
                            echo $booked_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?
                            echo "$" . number_format($fb_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $fb_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>
        <?php
                    $total_diff_val = $total_diff_val + ($diffn);
                } //End while
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        echo "$" . number_format($total_diff_val, 2);
                        ?>
            </td>
        </tr>
    </table>
    <br><br>
    <?php
        } //end if rows>0
        //------------End Display UCB delivering (non recycling)-----------------------
        //------------Display UCB delivering (recycling)-----------------------
        $fsql = "SELECT *,loop_transaction_buyer.warehouse_id AS D, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount, loop_transaction_buyer.id AS I FROM loop_transaction_buyer_freightview INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_transaction_buyer_freightview.trans_rec_id WHERE loop_transaction_buyer.ignore = 0  and (dt >='" . $date_from_val . "') AND (dt <= '" . $date_to_val . " 23:59:59') and recycling_flg=1 and customerpickup_ucbdelivering_flg=2 GROUP BY loop_transaction_buyer.id";
        //echo $dt_view_qry;
        db();
        $dt_view_new = db_query($fsql);
        $total_rows = tep_db_num_rows($dt_view_new);
        if ($total_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $total_diff = 0;
            $total_diff_val = 0;
        ?>
    <table cellpadding="3" cellspacing="1">
        <tr>
            <td colspan="8" align="center" class="th_style">
                UCB Delivery (Recycling)
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Booked
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                while ($fb_rec = array_shift($dt_view_new)) {

                    $wqry = "select * from loop_warehouse where id=" . $fb_rec["D"];
                    db();
                    $wres = db_query($wqry);
                    $wrow = array_shift($wres);
                    $comp_id = $wrow["b2bid"];
                    $comp_name = $wrow["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    //
                    $tot_actual_amount = $tot_actual_amount + $fb_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //

                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $booked_date = date("m/d/Y", strtotime($fb_rec['dt']));
                            echo $booked_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $fb_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>
        <?php
                    $total_diff_val = $total_diff_val + ($diffn);
                } //End while
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff_val, 2);
                        ?>
            </td>
        </tr>
    </table>
    <br><br>
    <?php
        } //end if rows>0
        //------------End Display UCB delivering (recycling)-----------------------
        //
        //------------Display Customer Pickup------------------------------------------
        $fsql = "SELECT *,loop_transaction_buyer.warehouse_id AS D, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount, loop_transaction_buyer.id AS I FROM loop_transaction_buyer_freightview INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_transaction_buyer_freightview.trans_rec_id WHERE loop_transaction_buyer.ignore = 0  and (dt >='" . $date_from_val . "') AND (dt <= '" . $date_to_val . " 23:59:59') and customerpickup_ucbdelivering_flg=1 GROUP BY loop_transaction_buyer.id";
        //echo $dt_view_qry;
        db();
        $dt_view_new = db_query($fsql);
        $total_rows = tep_db_num_rows($dt_view_new);
        if ($total_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $diffn = 0;
            $total_diff = 0;
        ?>
    <table cellpadding="3" cellspacing="1">
        <tr>
            <td colspan="8" align="center" class="th_style">
                Customer Pickup
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Booked
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                while ($fb_rec = array_shift($dt_view_new)) {

                    $wqry = "select * from loop_warehouse where id=" . $fb_rec["D"];
                    db();
                    $wres = db_query($wqry);
                    $wrow = array_shift($wres);
                    $comp_id = $wrow["b2bid"];
                    $comp_name = $wrow["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    //
                    $tot_actual_amount = $tot_actual_amount + $fb_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //

                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $booked_date = date("m/d/Y", strtotime($fb_rec['dt']));
                            echo $booked_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $fb_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>
        <?php
                } //End while
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff, 2);
                        ?>
            </td>
        </tr>
    </table>
    <br><br>
    <?php
        } //end if rows>0
        //------------End Display UCB delivering (non recycling)-----------------------
    } //End if Lane booked
    ?>
    <?php
    //Total Lanes Shipped (Picked Up)
    if ($_REQUEST["showquotedata"] == "lane_shipped") {
        //
        //------------lane shipped- Display UCB delivering (non recycling)-----------------------
        $ship_qry = "SELECT *,loop_transaction_buyer.warehouse_id AS D,loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.ignore = 0  and recycling_flg=0 and customerpickup_ucbdelivering_flg=2 and (STR_TO_DATE(loop_bol_files.bol_shipped_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "  23:59:59')";
        db();
        $dt_view_new = db_query($ship_qry);
        $num_rows = tep_db_num_rows($dt_view_new);
        if ($num_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $diffn = 0;
            $total_diff = 0;
    ?>

    <table cellpadding="3">
        <tr>
            <td colspan="8" align="center" class="th_style">
                UCB Delivery (Non-Recycling)
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Shipped
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                // 
                while ($fb_rec = array_shift($dt_view_new)) {
                    $amt_qry = db_query("SELECT *, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount from loop_transaction_buyer_freightview where loop_transaction_buyer_freightview.trans_rec_id=" . $fb_rec["I"]);
                    $amt_rec = array_shift($amt_qry);
                    /*$wqry="select * from loop_warehouse where id=".$fb_rec["D"];
						$wres=db_query($wqry,db());
						$wrow=array_shift($wres);*/
                    $comp_id = $fb_rec["b2bid"];
                    $comp_name = $fb_rec["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    $tot_actual_amount = $tot_actual_amount + $amt_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $shipped_date = date("m/d/Y", strtotime($fb_rec['bol_shipped_date']));
                            echo $shipped_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($amt_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $amt_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>

        <?php
                }
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff, 2);
                        ?>
            </td>
        </tr>
        <br><br>
    </table>
    <?php
        } //end if num>0
        //------------End lane shipped- Display UCB delivering (non recycling)---------------
        //
        //------------lane shipped- Display UCB delivering (recycling)-----------------------
        $ship_qry = "SELECT *,loop_transaction_buyer.warehouse_id AS D,loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.ignore = 0  and recycling_flg=1 and customerpickup_ucbdelivering_flg=2 and (STR_TO_DATE(loop_bol_files.bol_shipped_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "  23:59:59')";
        db();
        $dt_view_new = db_query($ship_qry);
        $num_rows = tep_db_num_rows($dt_view_new);
        if ($num_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $diffn = 0;
            $total_diff = 0;
        ?>

    <table cellpadding="3">
        <tr>
            <td colspan="8" align="center" class="th_style">
                UCB Delivery (Recycling)
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Shipped
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                // 
                while ($fb_rec = array_shift($dt_view_new)) {
                    $amt_qry = db_query("SELECT *, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount from loop_transaction_buyer_freightview where loop_transaction_buyer_freightview.trans_rec_id=" . $fb_rec["I"]);
                    $amt_rec = array_shift($amt_qry);
                    /*$wqry="select * from loop_warehouse where id=".$fb_rec["D"];
						$wres=db_query($wqry,db());
						$wrow=array_shift($wres);*/
                    $comp_id = $fb_rec["b2bid"];
                    $comp_name = $fb_rec["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    $tot_actual_amount = $tot_actual_amount + $amt_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $shipped_date = date("m/d/Y", strtotime($fb_rec['bol_shipped_date']));
                            echo $shipped_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($amt_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $amt_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>

        <?php
                }
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff, 2);
                        ?>
            </td>
        </tr>
        <br><br>
    </table>
    <?php
        } //end if num>0
        //------------End lane shipped- Display UCB delivering (recycling)---------------
        //
        //------------lane shipped- Customer Pickup-----------------------
        $ship_qry = "SELECT *,loop_transaction_buyer.warehouse_id AS D,loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.ignore = 0 and customerpickup_ucbdelivering_flg=1 and (STR_TO_DATE(loop_bol_files.bol_shipped_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "  23:59:59')";
        db();
        $dt_view_new = db_query($ship_qry);
        $num_rows = tep_db_num_rows($dt_view_new);
        if ($num_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $diffn = 0;
            $total_diff = 0;
        ?>

    <table cellpadding="3">
        <tr>
            <td colspan="8" align="center" class="th_style">
                Customer Pickup
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Shipped
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                // 
                while ($fb_rec = array_shift($dt_view_new)) {
                    $amt_qry = db_query("SELECT *, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount from loop_transaction_buyer_freightview where loop_transaction_buyer_freightview.trans_rec_id=" . $fb_rec["I"]);
                    $amt_rec = array_shift($amt_qry);
                    /*$wqry="select * from loop_warehouse where id=".$fb_rec["D"];
						$wres=db_query($wqry,db());
						$wrow=array_shift($wres);*/
                    $comp_id = $fb_rec["b2bid"];
                    $comp_name = $fb_rec["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    $tot_actual_amount = $tot_actual_amount + $amt_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $shipped_date = date("m/d/Y", strtotime($fb_rec['bol_shipped_date']));
                            echo $shipped_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($amt_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $amt_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>

        <?php
                }
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff, 2);
                        ?>
            </td>
        </tr>
        <br><br>
    </table>
    <?php
        } //end if num>0
        //------------End Customer Pickup---------------
        //------------lane shipped------------------------
        $ship_qry = "SELECT *,loop_transaction_buyer.warehouse_id AS D,loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.ignore = 0 and customerpickup_ucbdelivering_flg=0 and (STR_TO_DATE(loop_bol_files.bol_shipped_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "  23:59:59')";
        db();
        $dt_view_new = db_query($ship_qry);
        $num_rows = tep_db_num_rows($dt_view_new);
        if ($num_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $diffn = 0;
            $total_diff = 0;
        ?>

    <table cellpadding="3">
        <tr>
            <td colspan="8" align="center" class="th_style">
                Customer Pickup or UCB Delivering? - Flag not set
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Shipped
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                // 
                while ($fb_rec = array_shift($dt_view_new)) {
                    $amt_qry = db_query("SELECT *, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount from loop_transaction_buyer_freightview where loop_transaction_buyer_freightview.trans_rec_id=" . $fb_rec["I"]);
                    $amt_rec = array_shift($amt_qry);
                    /*$wqry="select * from loop_warehouse where id=".$fb_rec["D"];
						$wres=db_query($wqry,db());
						$wrow=array_shift($wres);*/
                    $comp_id = $fb_rec["b2bid"];
                    $comp_name = $fb_rec["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    $tot_actual_amount = $tot_actual_amount + $amt_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $shipped_date = date("m/d/Y", strtotime($fb_rec['bol_shipped_date']));
                            echo $shipped_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($amt_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $amt_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>

        <?php
                }
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff, 2);
                        ?>
            </td>
        </tr>
        <br><br>
    </table>
    <br><br>
    <?php
        } //end if num>0
        //------------End shipped---------------
    }
    ?>

    <?php
    //Total Lanes Delivered
    if ($_REQUEST["showquotedata"] == "lane_delivered") {
        //------------lane delivered - Display UCB delivering (non recycling)---------------
        $so_view_qry = "SELECT *, STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') as deliver_date, loop_transaction_buyer.id as I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $so_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
        $so_view_qry .= "WHERE loop_bol_files.bol_shipment_received = 1 and bol_create = 1 and so_entered = 1 and loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.UCBZeroWaste_flg = 0
		and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 and loop_transaction_buyer.no_invoice = 0 and recycling_flg=0 and customerpickup_ucbdelivering_flg=2 And (STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "') GROUP BY loop_transaction_buyer.id";
        //echo $so_view_qry;
        db();
        $dt_view_new = db_query($so_view_qry);
        $num_rows = tep_db_num_rows($dt_view_new);
        if ($num_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $diffn = 0;
            $total_diff = 0;
    ?>

    <table cellpadding="3">
        <tr>
            <td colspan="8" align="center" class="th_style">
                UCB Delivery (Non-Recycling)
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Delivered
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                //
                while ($fb_rec = array_shift($dt_view_new)) {
                    $amt_qry = db_query("SELECT *, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount from loop_transaction_buyer_freightview where loop_transaction_buyer_freightview.trans_rec_id=" . $fb_rec["I"]);
                    $amt_rec = array_shift($amt_qry);
                    /*$wqry="select * from loop_warehouse where id=".$fb_rec["D"];
					$wres=db_query($wqry,db());
					$wrow=array_shift($wres);*/
                    $comp_id = $fb_rec["b2bid"];
                    $comp_name = $fb_rec["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    $tot_actual_amount = $tot_actual_amount + $amt_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $shipped_date = date("m/d/Y", strtotime($fb_rec['bol_shipped_date']));
                            echo $shipped_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($amt_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $fb_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>
        </tr>

        <?php
                }
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff, 2);
                        ?>
            </td>
        </tr>
        <br><br>
    </table>
    <?php
        }
        //------------End lane delivered - Display UCB delivering (non recycling)---------------
        //
        //------------lane delivered - Display UCB delivering (recycling)---------------
        $so_view_qry = "SELECT *, STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') as deliver_date, loop_transaction_buyer.id as I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $so_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
        $so_view_qry .= "WHERE loop_bol_files.bol_shipment_received = 1 and bol_create = 1 and so_entered = 1 and loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.UCBZeroWaste_flg = 0
		and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 and loop_transaction_buyer.no_invoice = 0 and recycling_flg=1 and customerpickup_ucbdelivering_flg=2 And (STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "') GROUP BY loop_transaction_buyer.id";
        //echo $dt_view_qry;
        db();
        $dt_view_new = db_query($so_view_qry);
        $num_rows = tep_db_num_rows($dt_view_new);
        if ($num_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $diffn = 0;
            $total_diff = 0;
        ?>

    <table cellpadding="3">
        <tr>
            <td colspan="8" align="center" class="th_style">
                UCB Delivery (Recycling)
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Delivered
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                //
                while ($fb_rec = array_shift($dt_view_new)) {
                    $amt_qry = db_query("SELECT *, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount from loop_transaction_buyer_freightview where loop_transaction_buyer_freightview.trans_rec_id=" . $fb_rec["I"]);
                    $amt_rec = array_shift($amt_qry);
                    /*$wqry="select * from loop_warehouse where id=".$fb_rec["D"];
					$wres=db_query($wqry,db());
					$wrow=array_shift($wres);*/
                    $comp_id = $fb_rec["b2bid"];
                    $comp_name = $fb_rec["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    $tot_actual_amount = $tot_actual_amount + $amt_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $shipped_date = date("m/d/Y", strtotime($fb_rec['bol_shipped_date']));
                            echo $shipped_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($amt_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $fb_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>
        </tr>

        <?php
                }
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff, 2);
                        ?>
            </td>
        </tr>
        <br><br>
    </table>
    <?php
        }
        //------------End lane delivered - Display UCB delivering (non recycling)---------------
        //
        //------------lane delivered - Display Customer Pickup---------------
        $so_view_qry = "SELECT *, STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') as deliver_date, loop_transaction_buyer.id as I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $so_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
        $so_view_qry .= "WHERE loop_bol_files.bol_shipment_received = 1 and bol_create = 1 and so_entered = 1 and loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.UCBZeroWaste_flg = 0
		and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 and loop_transaction_buyer.no_invoice = 0 and customerpickup_ucbdelivering_flg=1 And (STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "') GROUP BY loop_transaction_buyer.id";
        //echo $dt_view_qry;
        db();
        $dt_view_new = db_query($so_view_qry);
        $num_rows = tep_db_num_rows($dt_view_new);
        if ($num_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $diffn = 0;
            $total_diff = 0;
        ?>

    <table cellpadding="3">
        <tr>
            <td colspan="8" align="center" class="th_style">
                Customer Pickup
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Delivered
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                //
                while ($fb_rec = array_shift($dt_view_new)) {
                    $amt_qry = db_query("SELECT *, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount from loop_transaction_buyer_freightview where loop_transaction_buyer_freightview.trans_rec_id=" . $fb_rec["I"]);
                    $amt_rec = array_shift($amt_qry);
                    /*$wqry="select * from loop_warehouse where id=".$fb_rec["D"];
					$wres=db_query($wqry,db());
					$wrow=array_shift($wres);*/
                    $comp_id = $fb_rec["b2bid"];
                    $comp_name = $fb_rec["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    $tot_actual_amount = $tot_actual_amount + $amt_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $shipped_date = date("m/d/Y", strtotime($fb_rec['bol_shipped_date']));
                            echo $shipped_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($amt_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $fb_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>
        </tr>

        <?php
                }
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff, 2);
                        ?>
            </td>
        </tr>
        <br><br>
    </table>
    <?php
            //
        } //End if num>0
        //------------End lane delivered - Display UCB delivering (non recycling)---------------
        //
        //------------lane delivered ---------------
        $so_view_qry = "SELECT *, STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') as deliver_date, loop_transaction_buyer.id as I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $so_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
        $so_view_qry .= "WHERE loop_bol_files.bol_shipment_received = 1 and bol_create = 1 and so_entered = 1 and loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.UCBZeroWaste_flg = 0
		and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 and loop_transaction_buyer.no_invoice = 0 and customerpickup_ucbdelivering_flg=0 And (STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "') GROUP BY loop_transaction_buyer.id";
        //echo $dt_view_qry;
        db();
        $dt_view_new = db_query($so_view_qry);
        $num_rows = tep_db_num_rows($dt_view_new);
        if ($num_rows > 0) {
            $tot_freight_budget = 0;
            $tot_actual_amount = 0;
            $diffn = 0;
            $total_diff = 0;
        ?>

    <table cellpadding="3">
        <tr>
            <td colspan="8" align="center" class="th_style">
                Customer Pickup or UCB Delivering? - Flag not set
            </td>
        </tr>
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date Delivered
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Broker
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Booked Freight Cost
            </th>
            <th class="th_style">
                Difference
            </th>
        </tr>
        <?php
                //
                while ($fb_rec = array_shift($dt_view_new)) {
                    $amt_qry = db_query("SELECT *, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount from loop_transaction_buyer_freightview where loop_transaction_buyer_freightview.trans_rec_id=" . $fb_rec["I"]);
                    $amt_rec = array_shift($amt_qry);
                    /*$wqry="select * from loop_warehouse where id=".$fb_rec["D"];
					$wres=db_query($wqry,db());
					$wrow=array_shift($wres);*/
                    $comp_id = $fb_rec["b2bid"];
                    $comp_name = $fb_rec["company_name"];
                    $company_name = get_nickname_val($comp_name, $comp_id);
                    $rec_display = "buyer_ship";
                    //
                    $tot_actual_amount = $tot_actual_amount + $amt_rec["actual_amount"];
                    $tot_freight_budget = $tot_freight_budget + $fb_rec["po_freight"];
                    //
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            $shipped_date = date("m/d/Y", strtotime($fb_rec['bol_shipped_date']));
                            echo $shipped_date;
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                                echo $company_name;
                                ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                            if ($fb_rec["broker_id"] > 0) {
                                $freight_sql = "Select company_name from loop_freightvendor WHERE id = " . $fb_rec["broker_id"];
                                $freightresult = db_query($freight_sql);
                                $freightrow = array_shift($freightresult);
                                echo $freightrow["company_name"];
                            }
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($fb_rec["po_freight"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                            echo "$" . number_format($amt_rec["actual_amount"], 2);
                            ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                            $diffn = $fb_rec["po_freight"] - $fb_rec["actual_amount"];
                            echo "$" . number_format($diffn, 2);
                            ?>
            </td>
        </tr>
        </tr>

        <?php
                }
                ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="5">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_freight_budget, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php echo "$" . number_format($tot_actual_amount, 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                        $total_diff = $tot_freight_budget - $tot_actual_amount;
                        echo "$" . number_format($total_diff, 2);
                        ?>
            </td>
        </tr>
        <br><br>
    </table>
    <br><br>
    <?php
            //
        } //End if num>0
        //------------End lane delivered ---------------
        //
    }
    ?>

    <?php
    //Total Lanes Delivered
    if ($_REQUEST["showquotedata"] == "uber_freight_per") {
    ?>

    <table cellpadding="3">
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
            </th>
            <th class="th_style">
                Date
            </th>
            <th class="th_style">
                Company Name
            </th>
            <th class="th_style">
                Freight Budget
            </th>
            <th class="th_style">
                Freight Actual
            </th>

        </tr>
        <?php
            //
            $so_view_qry = "SELECT *,loop_transaction_buyer.id AS I, sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id inner join loop_transaction_buyer_freightview on loop_transaction_buyer_freightview.trans_rec_id = loop_transaction_buyer.id WHERE broker_id=1711 and loop_transaction_buyer.ignore = 0  and (tender_lane_ignore = 0 or loop_transaction_buyer.id in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id)) and (ops_delivery_date >='" . $date_from_val . "') AND (ops_delivery_date <= '" . $date_to_val . " 23:59:59') GROUP BY loop_transaction_buyer.id";
            //echo $dt_view_qry;
            db();
            $dt_view_new = db_query($so_view_qry);
            while ($fb_rec = array_shift($dt_view_new)) {
                /*$wqry="select * from loop_warehouse where id=".$fb_rec["D"];
					$wres=db_query($wqry,db());
					$wrow=array_shift($wres);*/
                $comp_id = $fb_rec["b2bid"];
                $comp_name = $fb_rec["company_name"];
                $company_name = get_nickname_val($comp_name, $comp_id);
                $rec_display = "buyer_ship";
                //
                $freight_budget = isset($freight_budget) + $fb_rec["po_freight"];
                $freight_actual = isset($freight_actual) + $fb_rec["actual_amount"];
                //
            ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php echo $fb_rec['po_employee']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <?php
                        $ops_delivery_date = date("m/d/Y", strtotime($fb_rec['ops_delivery_date']));
                        echo $ops_delivery_date;
                        ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n" align="center">
                <a target="_blank"
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>">
                    <?php
                            echo $company_name;
                            ?>
                </a>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                        echo "$" . number_format($fb_rec["po_freight"], 2);
                        ?>
            </td>
            <td bgColor="#e4e4e4" class="style12" align="right">
                <?php
                        echo "$" . number_format($fb_rec["actual_amount"], 2);
                        ?>
            </td>
        </tr>

        <?php
            }
            //
            $total_freight_diff = isset($freight_budget) - isset($freight_actual);
            //
            ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan=4>
                Total Freight Difference
            </td>
            <td bgColor="#e4e4e4" class="style12_tot_left" colspan=2>
                <?php echo "$" . number_format($total_freight_diff, 2); ?>
            </td>
        </tr>
    </table>
    <br><br>
    <?php
    }
    ?>
</body>

</html>