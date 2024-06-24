<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();
$initials = $_REQUEST['initials'];
$dashboard_view = $_REQUEST['dashboard_view'];

$emp_filter = " and loop_transaction_buyer.po_employee = '$initials'";
if ($dashboard_view == "Operations" || $dashboard_view == "Executive") {
    $emp_filter = "";
}

?>
<table border="0">
    <tr>

        <td valign="top">
            <table width="200" cellSpacing="1" cellPadding="1" border="0">
                <tr>
                    <td class="header_td_style" align="center"><strong>Planned Delivery Date Passed</strong></td>
                </tr>
                <?php

                $sql = "SELECT loop_transaction_buyer.warehouse_id, loop_transaction_buyer.id, loop_warehouse.warehouse_name, loop_warehouse.b2bid FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id ";
                $sql .= " WHERE loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 and po_delivery_dt <= DATE_FORMAT(curdate() , '%Y-%m-%d') $emp_filter "; //and bol_shipped_employee =  '$initials'

                //echo $sql . "<br>";
                db();
                $result = db_query($sql);
                while ($row = array_shift($result)) {
                    echo "<tr><td bgColor='#E4EAEB'><a target='_blank' href='https://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["id"] . "&display=buyer_ship'><font color=red>" . get_nickname_val($row["warehouse_name"], $row["b2bid"]) . "&nbsp;[" . $row["id"] . "]" . "</font></a>";
                    echo "</td></tr>";
                }
                ?>
            </table>
        </td>

        <td valign="top">
            <table width="200" cellSpacing="1" cellPadding="1" border="0">
                <tr>
                    <td class="header_td_style" align="center"><strong>Deals Shipped Today</strong></td>
                </tr>
                <?php

                $sql = "SELECT loop_bol_files.*, loop_warehouse.warehouse_name, loop_warehouse.b2bid FROM loop_bol_files INNER JOIN loop_warehouse ON loop_warehouse.id = loop_bol_files.warehouse_id ";
                $sql .= " inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE STR_TO_DATE(bol_shipped_date, '%m/%d/%Y') = DATE_FORMAT(curdate() , '%Y-%m-%d') $emp_filter "; //and bol_shipped_employee =  '$initials'

                //echo $sql . "<br>";
                db();
                $result = db_query($sql);
                while ($row = array_shift($result)) {
                    echo "<tr><td bgColor='#E4EAEB'><a target='_blank' href='https://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["trans_rec_id"] . "&display=buyer_ship'>" . get_nickname_val($row["warehouse_name"], $row["b2bid"]) . " &nbsp;[" . $row["trans_rec_id"] . "]" . "</a>";
                    if ($row["bol_shipment_received"] == 1) {
                        echo " (and delivered)";
                    }
                    echo "</td></tr>";
                }
                ?>
            </table>
        </td>

        <td valign="top">
            <table width="200" cellSpacing="1" cellPadding="1" border="0">
                <tr>
                    <td class="header_td_style" align="center"><strong>Deals Currently on the Road</strong></td>
                </tr>
                <?php

                $sql = "SELECT loop_bol_files.*, loop_warehouse.warehouse_name, loop_warehouse.b2bid FROM loop_bol_files INNER JOIN loop_warehouse ON loop_warehouse.id = loop_bol_files.warehouse_id ";
                $sql .= " inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE bol_shipped = 1 $emp_filter and bol_shipment_received = 0 AND trans_rec_id > 1000 AND  bol_shipped_date <> DATE_FORMAT(curdate() , '%m/%d/%Y')"; //loop_bol_files.bol_shipped_employee = '$initials'

                db();
                $result = db_query($sql);
                while ($row = array_shift($result)) {
                    echo "<tr><td bgColor='#E4EAEB'> <a target='_blank' href='https://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["trans_rec_id"] . "&display=buyer_ship'>" . get_nickname_val($row["warehouse_name"], $row["b2bid"]) . " &nbsp;[" . $row["trans_rec_id"] . "]" . "</a></td></tr>";
                } ?>
            </table>
        </td>

        <td valign="top">
            <table width="200" cellSpacing="1" cellPadding="1" border="0">
                <tr>
                    <td class="header_td_style" align="center"><strong>Deals Delivered Today</strong></td>
                </tr>
                <?php
                $sql = "SELECT loop_bol_files.*, loop_warehouse.warehouse_name, loop_warehouse.b2bid FROM loop_bol_files INNER JOIN loop_warehouse ON loop_warehouse.id = loop_bol_files.warehouse_id ";
                $sql .= " inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE bol_shipment_received_date = DATE_FORMAT(curdate() , '%m/%d/%Y') $emp_filter"; // bol_shipment_received_employee = '$initials'
                db();
                $result = db_query($sql);
                while ($row = array_shift($result)) {
                    echo "<tr><td bgColor='#E4EAEB'><a target='_blank' href='https://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["trans_rec_id"] . "&display=buyer_received'>" . get_nickname_val($row["warehouse_name"], $row["b2bid"]) . "&nbsp; [" . $row["trans_rec_id"] . "]" . "</a></td></tr>";
                } ?>
            </table>
        </td>

        <td valign="top">
            <table width="200" cellSpacing="1" cellPadding="1" border="0">
                <tr>
                    <td colspan=2 style='font-size:10pt;' bgcolor="#FFF2D0" align=center><strong>Payment
                            Received</strong></td>
                </tr>
                <?php


                $inv_amt_tot = 0;
                $sql = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid , loop_warehouse.warehouse_name, loop_warehouse.company_name ";
                $sql .= " AS B, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, ";
                $sql .= " loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id left join ";
                $sql .= " loop_bol_files on loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 ";
                $sql .= " and loop_transaction_buyer.id in (SELECT trans_rec_id FROM loop_buyer_payments where date = '" . date("m/d/Y") . "' group by trans_rec_id) ";
                $sql .= " and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 $emp_filter GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";

                db();
                $result = db_query($sql);
                while ($row = array_shift($result)) {
                    //This is the payment Info for the Customer paying UCB
                    $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A, date FROM loop_buyer_payments WHERE trans_rec_id = " . $row["I"];
                    db();
                    $payment_qry = db_query($payments_sql);
                    $payment = array_shift($payment_qry);

                    $invoice_paid = 0; //Have they paid their invoice?

                    //Have they paid their invoice?
                    if (number_format($row["F"], 2) == number_format($payment["A"], 2) && $row["F"] != "") {
                        $invoice_paid = 1;
                    }

                    $display_rec = "no";
                    if ($invoice_paid == 1 && $payment["date"] == date("m/d/Y")) {
                        $display_rec = "yes";
                    }

                    if ($display_rec == "yes") {
                        echo "<tr><td bgColor='#FFF2D0' align='left' width='170px'><a color=blue target='_blank' href='https://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["I"] . "&display=buyer_payment'>" . get_nickname_val($row["warehouse_name"], $row["b2bid"]) . "&nbsp; [" . $row["I"] . "]" . "</a></td><td width='100px' align='right' bgColor='#FFF2D0'><a target='_blank' href='https://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["I"] . "&display=buyer_payment'>$" . number_format($row["F"], 0) . "</a></td></tr>";
                        $inv_amt_tot = $inv_amt_tot + $row["F"];
                    }
                }
                if ($inv_amt_tot > 0) {
                    echo "<tr><td style='font-size:10pt;' bgColor='#FFF2D0'><b>Total</b></td><td style='font-size:10pt;' align='right' bgColor='#FFF2D0'><b>$" . number_format($inv_amt_tot, 0) . "</b></td></tr>";
                }

                ?>

            </table>
        </td>
    </tr>

</table>