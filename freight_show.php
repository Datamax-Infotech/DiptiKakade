<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();
// function getnickname($warehouse_name, $b2bid)
// {
//     $nickname = "";
//     if ($b2bid > 0) {
//         db_b2b();
//         $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = ?";
//         $result_comp = db_query($sql, array("i"), array($b2bid));
//         while ($row_comp = array_shift($result_comp)) {
//             if ($row_comp["nickname"] != "") {
//                 $nickname = $row_comp["nickname"];
//             } else {
//                 $tmppos_1 = strpos($row_comp["company"], "-");
//                 if ($tmppos_1 != false) {
//                     $nickname = $row_comp["company"];
//                 } else {
//                     if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
//                         $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
//                     } else {
//                         $nickname = $row_comp["company"];
//                     }
//                 }
//             }
//         }
//         db();
//     } else {
//         $nickname = $warehouse_name;
//     }
//     return $nickname;
// }
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
        font-size: x-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        font-weight: bold;
        text-align: right !important;
        line-height: 12px;
    }

    .style12_tot_left {
        font-size: x-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        font-weight: bold;
        text-align: left !important;
        line-height: 12px;
    }
    </style>
</head>

<body>
    <table cellpadding="3">
        <tr>
            <th class="th_style">
                ID
            </th>
            <th class="th_style">
                Rep
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

        $date_from = $_REQUEST["date_from_val"];
        $date_to = $_REQUEST["date_to_val"];
        $date_from_val = date("Y-m-d", strtotime($date_from));
        $date_to_val_org = date("Y-m-d", strtotime($date_to));
        $date_to_val = date("Y-m-d", strtotime('+1 day', strtotime($date_to)));

        //
        //$sql = "SELECT *,sum(estimated_cost)as actual_amount, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer_payments.id AS A, loop_transaction_buyer.id AS I, loop_transaction_buyer.po_freight AS freight_budget from loop_transaction_buyer INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer.id = loop_transaction_buyer_payments.transaction_buyer_id INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id left JOIN loop_employees ON loop_transaction_buyer_payments.employee_id=loop_employees.id WHERE loop_transaction_buyer_payments.typeid in (2,13) and loop_transaction_buyer_payments.date between '" . $date_from_val. "' AND '" . $date_to_val. "' group by transaction_buyer_id";
        $sql = "SELECT sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount, po_employee, broker_id, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.id AS I, loop_transaction_buyer.po_freight AS freight_budget from loop_transaction_buyer_freightview INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_transaction_buyer_freightview.trans_rec_id WHERE loop_transaction_buyer.ignore = 0 and loop_transaction_buyer_freightview.dt between '" . $date_from_val . "' AND '" . $date_to_val . "' group by trans_rec_id";

        //echo $dt_view_qry;
        db();
        $dt_view_res = db_query($sql);
        while ($fb_rec = array_shift($dt_view_res)) {
            if ($fb_rec["freight_budget"] > 0) {
                if ($_REQUEST["showquotedata"] == "1") {
                    if ($fb_rec["actual_amount"] > $fb_rec["freight_budget"]) {
                        $freight_budget_spent = isset($freight_budget_spent) + ($fb_rec["freight_budget"] - $fb_rec["actual_amount"]);
                        $wqry = "select * from loop_warehouse where id=" . $fb_rec["D"];
                        db();
                        $wres = db_query($wqry);
                        $wrow = array_shift($wres);
                        $comp_id = $wrow["b2bid"];
                        $comp_name = $wrow["company_name"];
                        $company_name = getnickname($comp_name, $comp_id);
                        $rec_display = "buyer_ship";
                        //
                        $tot_actual_amount = isset($tot_actual_amount) + $fb_rec["actual_amount"];
                        $tot_freight_budget = isset($tot_freight_budget) + $fb_rec["freight_budget"];
                        //

        ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                                echo $fb_rec["po_employee"];
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
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                                echo "$" . number_format($fb_rec["freight_budget"], 2);
                                ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                                echo "$" . number_format($fb_rec["actual_amount"], 2);
                                ?>
            </td>

            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                                $diffn = $fb_rec["freight_budget"] - $fb_rec["actual_amount"];
                                echo "$" . number_format($diffn, 2);
                                ?>
            </td>
        </tr>
        <?php
                    }
                }
                if ($_REQUEST["showquotedata"] == "2") {
                    if ($fb_rec["actual_amount"] <= $fb_rec["freight_budget"]) {
                        $freight_budget_saved = isset($freight_budget_saved) + ($fb_rec["freight_budget"] - $fb_rec["actual_amount"]);
                        $wqry = "select * from loop_warehouse where id=" . $fb_rec["D"];
                        db();
                        $wres = db_query($wqry);
                        $wrow = array_shift($wres);
                        $comp_id = $wrow["b2bid"];
                        $comp_name = $wrow["company_name"];
                        $company_name = getnickname($comp_name, $comp_id);
                        $rec_display = "buyer_ship";
                        //
                        $tot_actual_amount = isset($tot_actual_amount) + $fb_rec["actual_amount"];
                        $tot_freight_budget = isset($tot_freight_budget) + $fb_rec["freight_budget"];
                        //

                    ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_n">
                <u><a target="_blank"
                        href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $comp_id; ?>&show=transactions&warehouse_id=<?php echo $fb_rec["D"]; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $fb_rec["D"]; ?>&rec_id=<?php echo $fb_rec['I']; ?>&display=<?php echo $rec_display; ?>"><?php echo $fb_rec['I']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                                echo $fb_rec["po_employee"];
                                ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_n">
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
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                                echo "$" . number_format($fb_rec["freight_budget"], 2);
                                ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                                echo "$" . number_format($fb_rec["actual_amount"], 2);
                                ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_num">
                <?php
                                $diffn = $fb_rec["freight_budget"] - $fb_rec["actual_amount"];
                                echo "$" . number_format($diffn, 2);
                                ?>
            </td>
        </tr>
        <?php
                    }
                }
            }
        }
        ?>
        <tr>
            <td bgColor="#e4e4e4" class="style12_tot" colspan="4">
                Total
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                $tot_freight_budget = $tot_freight_budget ?? 0;
                echo "$" . number_format($tot_freight_budget, 2);
                ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                $tot_actual_amount = $tot_actual_amount ?? 0;
                echo "$" . number_format($tot_actual_amount, 2);
                ?>
            </td>
            <td bgColor="#e4e4e4" class="style12_tot">
                <?php
                $total_diff = $tot_freight_budget - $tot_actual_amount;
                echo "$" . number_format($total_diff, 2);
                ?>
            </td>
        </tr>
        <?php
        if ($_REQUEST["showquotedata"] == "1") {
        ?>
        <!--<tr>
								<td bgColor="#e4e4e4" class="style12_tot" colspan=2>
									Total Amount Spent Over Freight Budgets on Lanes Booked
								</td>
								<td bgColor="#e4e4e4" class="style12_tot_left" colspan=3>
									<?php //echo "$".number_format($freight_budget_spent,2); 
                                    ?>
								</td>
							</tr>-->
        <?php
        }
        if ($_REQUEST["showquotedata"] == "2") {
        ?>
        <!--<tr>
								<td bgColor="#e4e4e4" class="style12_tot" colspan=2>
									<strong>Total Amount Saved Under Freight Budgets on Lanes Booked</strong>
								</td>
								<td bgColor="#e4e4e4" class="style12_tot_left" colspan=2>
									<strong><?php //echo "$".number_format($freight_budget_saved,2); 
                                            ?></strong>
								</td>
							</tr>-->
        <?php
        }
        ?>
    </table>
    <br><br>
</body>

</html>