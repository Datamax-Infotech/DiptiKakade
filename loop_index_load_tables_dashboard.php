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

$tablenm = $_REQUEST["tablenm"];

$dashboardflg = "n";
if (isset($_REQUEST["dashboardflg"])) {
    if ($_REQUEST["dashboardflg"] == "yes") {
        $dashboardflg = "y";
    }
}
?>

<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<script LANGUAGE="JavaScript">
document.write(getCalendarStyles());
</script>
<script LANGUAGE="JavaScript">
var cal2xx = new CalendarPopup("listdiv");
cal2xx.showNavigationDropdowns();
</script>

<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
</div>

<form name="frmnewpage" id="frmnewpage" method="post" action="">

    <?php


    if ($tablenm == "all_inbound") {
        $tbl_array = array("poentered", "pouploaded", "customernotready", "customerready", "enterintoTMS", "tenderlane", "lanetendered_a", "lanetendered_b", "bolcreated", "onroad", "delivered", "requestinvoice", "requestinvoice_10b", "qbinvoice", "awaitingpayment", "doublechecksforpayroll", "compdoublechecksforpayroll");
        //$tbl_array = array("poentered","pouploaded");

        $count = 0;
        $arrlength = count($tbl_array);
        for ($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {
            showtbls($tbl_array[$arrycnt], true);
        }
    }

    if ($tablenm == "all_03") {
        $tbl_array = array("poentered", "pouploaded", "customernotready", "customerready");

        $count = 0;
        $arrlength = count($tbl_array);
        for ($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {
            showtbls($tbl_array[$arrycnt], true);
        }
    }
    if ($tablenm == "all_410") {
        $tbl_array = array("enterintoTMS", "tenderlane", "lanetendered_a", "lanetendered_b", "bolcreated", "onroad", "delivered", "requestinvoice", "requestinvoice_10b");

        $count = 0;
        $arrlength = count($tbl_array);
        for ($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {
            showtbls($tbl_array[$arrycnt], true);
        }
    }
    if ($tablenm == "all_1014") {
        $tbl_array = array("requestinvoice", "requestinvoice_10b", "qbinvoice", "awaitingpayment", "doublechecksforpayroll", "compdoublechecksforpayroll");

        $count = 0;
        $arrlength = count($tbl_array);
        for ($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {
            showtbls($tbl_array[$arrycnt], true);
        }
    }

    if ($tablenm == "requestinvoice_10a10b") {
        $tbl_array = array("requestinvoice", "requestinvoice_10b");

        $count = 0;
        $arrlength = count($tbl_array);
        for ($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {
            showtbls($tbl_array[$arrycnt], true);
        }
    }

    if (
        $tablenm == "poentered" || $tablenm == "pouploaded" || $tablenm == "customernotready" || $tablenm == "customerready" || $tablenm == "enterintoTMS"
        || $tablenm == "tenderlane" || $tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "onroad" || $tablenm == "delivered"
        || $tablenm == "requestinvoice" || $tablenm == "requestinvoice_10b" || $tablenm == "qbinvoice" || $tablenm == "awaitingpayment" || $tablenm == "doublechecksforpayroll"
        || $tablenm == "compdoublechecksforpayroll" || $tablenm == "orderissue" || $tablenm == "cancelorders" || $tablenm == "blankopsdeliverydt"
    ) {
        $count = 0;
        showtbls($tablenm, false);
    }

    function showtbls(string $tablenm, bool $alltblflg): void
    {

        global $count, $MGArray;

        if ($tablenm == "poentered") {
            $toprowcolspan = 19;
            $toprowtxt = "0. PO Not Entered Yet - Orders where a transaction was created, but no PO uploaded yet";
        }
        if ($tablenm == "pouploaded") {
            $toprowcolspan = 20;
            $toprowtxt = "1. PO Uploaded, Initial Steps Incomplete - Orders where the Ops Team Needs to Finish All Steps in Ordered Bubble";
        }
        if ($tablenm == "customernotready") {
            $toprowcolspan = 20;
            $toprowtxt = "2. Customer Not Ready (Pre-Order) - Orders where the customer placed their order but isn't going to take it yet";
        }
        if ($tablenm == "customerready") {
            $toprowcolspan = 20;
            $toprowtxt = "3. Customer Ready, Checking Inventory - Orders where UCB needs to confirm the inventory is ready, but the customer is ready";
        }
        if ($tablenm == "enterintoTMS") {
            $toprowcolspan = 20;
            $toprowtxt = "4. Need to Enter into TMS - Orders that are ready to have the shipping setup for them";
        }

        if ($tablenm == "tenderlane") {
            $toprowcolspan = 20;
            $toprowtxt = "5. Need to Tender Lane - Orders where the freight lane has been entered into TMS and has a link, but lane has not been booked yet to a broker/carrier";
        }
        if ($tablenm == "lanetendered_a") {
            $toprowcolspan = 21;
            $toprowtxt = "6a. Lane Tendered, Set Dock Appointments - Orders where the freight is tendered and need to set dock appointment with Shipper";
        }
        if ($tablenm == "lanetendered_b") {
            $toprowcolspan = 21;
            $toprowtxt = "6b. Delivery Dock Appointments – Orders where the delivery dock appointment is not set yet";
        }
        if ($tablenm == "bolcreated") {
            $toprowcolspan = 20;
            $toprowtxt = "7. BOL Needs Created and Shipped - Orders where freight is tendered and dock appointment set, now need to make the BOL for the driver upon being loaded";
        }
        if ($tablenm == "onroad") {
            $toprowcolspan = 20;
            $toprowtxt = "8. On The Road - Orders that confirmed being picked up and waiting for confirmation they have been delivered";
        }
        if ($tablenm == "delivered") {
            $toprowcolspan = 20;
            $toprowtxt = "9. Delivered, Needs Survey - Orders that confirmed delivered and UCB needs to send a B2B Survey Email";
        }
        if ($tablenm == "requestinvoice") {
            $toprowcolspan = 20;
            $toprowtxt = "10 a. Request Invoice - Orders that have been confirmed as delivered/B2B Survey sent and UCB needs to request an invoice be created in QuickBooks";
        }
        if ($tablenm == "requestinvoice_10b") {
            $toprowcolspan = 20;
            $toprowtxt = "10 b. Request Invoice [Mark as Recycling]- Orders that have been confirmed as delivered/B2B Survey sent and UCB needs to request an invoice be created in QuickBooks";
        }
        if ($tablenm == "qbinvoice") {
            $toprowcolspan = 20;
            $toprowtxt = "11. Need QB Invoice Uploaded - Order where the invoice was sent to accounting, but needs the actual QB invoice file uploaded";
        }
        if ($tablenm == "awaitingpayment") {
            $toprowcolspan = 20;
            $toprowtxt = "12. Awaiting Payment - Orders that have been invoiced in QB, but waiting for the customer to pay for it in full";
        }
        if ($tablenm == "doublechecksforpayroll") {
            $toprowcolspan = 20;
            $toprowtxt = "13. Double Checks for Payroll - Orders where the customer has paid their invoice in full and UCB needs to complete the vendor tab for payroll";
        }
        if ($tablenm == "compdoublechecksforpayroll") {
            $toprowcolspan = 20;
            $toprowtxt = "14. Completed Double Checks for Payroll - Orders where the double check is completed, but the rep has not been paid out yet";
        }
        if ($tablenm == "orderissue") {
            $toprowcolspan = 20;
            $toprowtxt = "Order Issues";
        }
        if ($tablenm == "cancelorders") {
            $toprowcolspan = 20;
            $toprowtxt = "Cancel Orders";
        }
        if ($tablenm == "blankopsdeliverydt") {
            $toprowcolspan = 20;
            $toprowtxt = "Need Ops Delivery Date Update";
        }

    ?>
    <br>
    <table>
        <tr>
            <td class="style24" colspan="<?php echo isset($toprowcolspan); ?>" align="middle">
                <strong><?php echo isset($toprowtxt); ?></strong>
            </td>
        </tr>
        <tr>

            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=ID&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>ID</strong></a>
            </th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=company_name&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>Company</strong></a>
            </th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=last_note_text&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>Last
                        Note</strong></a></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=last_note_date&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>Last
                        Note Date</strong></a></th>
            <?php if ($tablenm == "requestinvoice" || $tablenm == "requestinvoice_10b" || $tablenm == "qbinvoice" || $tablenm == "awaitingpayment" || $tablenm == "doublechecksforpayroll" || $tablenm == "compdoublechecksforpayroll") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Actual Delivery Date</strong></th>
            <?php     }

                if ($tablenm == "pouploaded" || $tablenm == "customernotready" || $tablenm == "customerready" || $tablenm == "blankopsdeliverydt" || $tablenm == "enterintoTMS" || $tablenm == "tenderlane") {
                ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=po_upload_date&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>PO
                        Upload Date</strong></a></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=po_delivery_dt&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>Planned
                        Delivery Date</strong></a></th>
            <?php     }    ?>

            <?php
                if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "onroad") {
                ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=po_delivery_dt&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>Planned
                        Delivery Date</strong></a></th>
            <?php     }    ?>

            <?php

                if ($tablenm == "customernotready" || $tablenm == "customerready" || $tablenm == "blankopsdeliverydt" || $tablenm == "enterintoTMS" || $tablenm == "tenderlane" || $tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "blankopsdeliverydt" || $tablenm == "onroad") {
                    //if ($tablenm == "blankopsdeliverydt" || $tablenm == "onroad"){
                ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=po_delivery_dt&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>OPS
                        Delivery Date</strong></a></th>
            <?php     }    ?>

            <?php if ($tablenm == "onroad") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Actual Pickup Date</strong></th>
            <?php     }    ?>
            <?php if ($tablenm == "delivered" || $tablenm == "onroad") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Expected Delivery Date</strong></th>
            <?php     }    ?>
            <?php if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated") {    ?>
            <!-- <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Booked Pick Up Date</strong></th>
					<th bgColor="#e4e4e4" class="style12" align="middle"><strong>Booked Delivery Date</strong></th> -->
            <?php if ($tablenm == "bolcreated") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Booked Pick Up Date</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Booked Delivery Date</strong></th>
            <?php } ?>
            <?php if ($tablenm == "lanetendered_a") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Booked Pickup Date </strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Dock Appointment </strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Planned Delivery Date </strong></th>
            <?php } ?>
            <?php if ($tablenm == "lanetendered_b") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Booked Delivery Date</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Dock Appointment </strong></th>
            <?php } ?>
            <?php     }    ?>
            <?php if ($tablenm != "poentered" && $tablenm != "pouploaded" && $tablenm != "delivered") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=source&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>Source</strong></a>
            </th>
            <?php     }    ?>

            <?php if ($tablenm == "requestinvoice" || $tablenm == "requestinvoice_10b" || $tablenm == "qbinvoice" || $tablenm == "awaitingpayment" || $tablenm == "doublechecksforpayroll" || $tablenm == "compdoublechecksforpayroll") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>PO File</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>BOL File</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Credit Terms</strong></th>
            <?php     }    ?>
            <?php if ($tablenm == "qbinvoice") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Invoice Request Date</strong></th>
            <?php     }    ?>
            <?php if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "onroad") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>BOL Created?</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Freight Broker</strong></th>
            <?php if ($tablenm == "bolcreated" || $tablenm == "onroad") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Freight Budget</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Tendered Amount</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>TMS Link</strong></th>
            <?php } ?>
            <?php     }    ?>
            <?php if ($tablenm == "delivered") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>BOL Created?</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Freight Broker</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Additional Cost</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>TMS Link</strong></th>
            <?php     }    ?>
            <?php if ($tablenm == "awaitingpayment") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Total Amount</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Invoice Age</strong></th>
            <?php     }    ?>

            <?php if ($tablenm != "poentered") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><a
                    href='index.php?tablenm=<?php echo $tablenm; ?>&sort=rep&sort_order_pre=<?php echo $_REQUEST["sort_order_pre"]; ?>'><strong>Rep</strong></a>
            </th>
            <?php     }    ?>
            <?php if ($tablenm == "delivered") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>B2B Survey</strong></th>
            <?php     }    ?>
            <?php if ($tablenm == "enterintoTMS" || $tablenm == "tenderlane") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Freight Budget</strong></th>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>TMS Link</strong></th>
            <?php     }    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Update Row</strong></th>


            <?php     //}

                $dashboardflg = isset($dashboardflg) ?? 0;
                if ($dashboardflg == "n") {
                ?>
            <?php if ($tablenm == "pouploaded" || $tablenm == "customernotready") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Move to Pending</strong></th>
            <?php     }    ?>
            <?php if ($tablenm == "customerready" || $tablenm == "blankopsdeliverydt") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Good to Ship</strong></th>
            <?php     }    ?>
            <?php if ($tablenm == "doublechecksforpayroll") {    ?>
            <th bgColor="#e4e4e4" class="style12" align="middle"><strong>Double Checked?</strong></th>
            <?php     }
                }
                ?>
        </tr>
        <?php

            if ((isset($_REQUEST["emp_list_selected"])) && ($_REQUEST["emp_list_selected"] != "all")) {
                $emp_query = " loop_transaction_buyer.po_employee='" . $_REQUEST["emp_list_selected"] . "' and ";
            } else {
                $emp_query = "";
            }
            //$emp_query="";

            $rec_display = "buyer_view";
            if ($tablenm == "poentered") {
                $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query loop_transaction_buyer.shipped = 0 AND loop_transaction_buyer.po_date = '' and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
            }
            if ($tablenm == "pouploaded") {
                $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query loop_transaction_buyer.Leaderboard <> 'UCBZW' and po_sent_to_supplier_flg = 0 and so_entered = 0 and sent_to_supplier = 0 and loop_transaction_buyer.shipped = 0 AND loop_transaction_buyer.po_date <> '' and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 and Preorder = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
            }
            if ($tablenm == "customernotready") {
                $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.po_date <> '' and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 and Preorder = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                //commented for Pre-paid order as per Zac Team Caht - and loop_transaction_buyer.no_invoice = 0
            }
            if ($tablenm == "customerready") {
                $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                //and sent_to_supplier = 1
                //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
            }
            if ($tablenm == "enterintoTMS") {
                $rec_display = "buyer_ship";
                $dt_view_qry = "SELECT po_employee, customerpickup_ucbdelivering_flg, booking_freight_email_ignore, lane_tms_ignore, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                //$dt_view_qry .= "WHERE $emp_query (customerpickup_ucbdelivering_flg = 0 or (booking_freight_email_ignore = 0 or loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_scheduleeml group by trans_rec_id)) or (lane_tms_ignore = 0 or loop_transaction_buyer.id not in (select trans_rec_id from loop_enterprise_tms_data group by trans_rec_id))) and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight group by trans_rec_id) and so_entered = 1 and loop_transaction_buyer.shipped = 0 AND inv_entered = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and customerpickup_ucbdelivering_flg = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight group by trans_rec_id) and so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
                //echo $dt_view_qry;
                //and sent_to_supplier = 1

                //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
            }
            if ($tablenm == "tenderlane") {
                $rec_display = "buyer_ship";
                $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and customerpickup_ucbdelivering_flg = 2 and (tender_lane_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id)) and so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
                //and sent_to_supplier = 1

                //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
            }
            if ($tablenm == "lanetendered_a") {
                $rec_display = "buyer_ship";

                $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and customerpickup_ucbdelivering_flg > 0 and loop_transaction_buyer.shipped = 0 ";
                $dt_view_qry .= " and so_entered = 1 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0  GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
                //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
            }

            if ($tablenm == "lanetendered_b") {
                $rec_display = "buyer_ship";
                $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and customerpickup_ucbdelivering_flg = 2 ";
                /*condition a : UCB Delivery and Tender the Lane complete, but no delivery appointment table values*/
                $dt_view_qry .= "and ( loop_transaction_buyer.id in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id) AND loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight_delivery group by trans_rec_id) ) ";
                /*condition b : Do not show any customer pickups in table 6b (if a customer picks up themselves, we don’t need a delivery appointment in this case).*/
                //$dt_view_qry .= "OR (good_to_ship = 1 or loop_transaction_buyer.id in (select rec_id from loop_transaction_notes group by rec_id AND loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight_delivery group by trans_rec_id) AND customerpickup_ucbdelivering_flg = 1 )  ) ";
                $dt_view_qry .= "and so_entered = 1 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
            }

            if ($tablenm == "bolcreated") {
                $rec_display = "buyer_ship";
                $dt_view_qry = "SELECT loop_freightvendor.company_name as freightbroker, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= " left JOIN loop_transaction_freight ON loop_transaction_buyer.id = loop_transaction_freight.trans_rec_id ";
                $dt_view_qry .= " left JOIN loop_freightvendor ON loop_transaction_freight.broker_id = loop_freightvendor.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and (bol_create = 0 or loop_transaction_buyer.shipped = 0) and so_entered = 1 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_freightvendor.company_name, loop_transaction_freight.date";
                //customerpickup_ucbdelivering_flg <> 0 and
                //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
            }
            if ($tablenm == "onroad") {
                $rec_display = "buyer_received";


                $dt_view_qry = "SELECT loop_freightvendor.company_name as freightbroker, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
                $dt_view_qry .= " right JOIN loop_transaction_freight ON loop_transaction_buyer.id = loop_transaction_freight.trans_rec_id ";
                $dt_view_qry .= " left JOIN loop_freightvendor ON loop_transaction_freight.broker_id = loop_freightvendor.id ";
                $dt_view_qry .= " WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_bol_files.bol_shipped = 1 and loop_bol_files.bol_shipment_received = 0 and bol_create = 1 and loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_freightvendor.company_name, loop_transaction_freight.booked_delivery_date";

                //customerpickup_ucbdelivering_flg <> 0 and

                //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
            }

            if ($tablenm == "delivered") {
                $rec_display = "buyer_received";
                $dt_view_qry = "SELECT tender_lane_additional_freight_costs, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_bol_files.bol_shipment_received = 1 and loop_bol_files.bol_shipment_followup = 0 and bol_create = 1 and so_entered = 1 and loop_transaction_buyer.shipped = 1 AND inv_entered = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                //customerpickup_ucbdelivering_flg <> 0 and
            }

            if ($tablenm == "requestinvoice") {
                $rec_display = "buyer_payment";
                $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.recycling_flg = 0 and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and inv_entered = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            }

            if ($tablenm == "requestinvoice_10b") {
                $rec_display = "buyer_payment";
                $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.recycling_flg = 1 and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and inv_entered = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            }

            if ($tablenm == "qbinvoice") {
                $rec_display = "buyer_payment";
                $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
                $dt_view_qry .= "WHERE $emp_query loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.inv_amount = 0 and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and inv_entered = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            }
            if ($tablenm == "awaitingpayment") {
                $rec_display = "buyer_payment";
                $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.shipped = 1 AND loop_transaction_buyer.inv_amount > 0 and pmt_entered = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.invoice_paid = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            }
            if ($tablenm == "doublechecksforpayroll") {
                $rec_display = "buyer_invoice";
                $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.double_checked = 0 and loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            }
            if ($tablenm == "compdoublechecksforpayroll") {
                $rec_display = "buyer_invoice";
                $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.double_checked = 1 and loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            }
            if ($tablenm == "orderissue") {
                $dt_view_qry = "SELECT po_employee,ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.order_issue = 1 and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            }
            if ($tablenm == "cancelorders") {

                $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and loop_transaction_buyer.ignore = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            }
            if ($tablenm == "blankopsdeliverydt") {
                $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
                $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
                $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                $dt_view_qry .= "WHERE $emp_query  loop_transaction_buyer.Leaderboard <> 'UCBZW' and (ops_delivery_date is null or ops_delivery_date = '' or ops_delivery_date < CURDATE()) and loop_transaction_buyer.id >= 8728 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            }

            //echo $dt_view_qry;
            $freightbroker_chg = "";
            $dt_view_qry = $dt_view_qry ?? '';
            $dt_view_res = db_query($dt_view_qry);
            while ($dt_view_row = array_shift($dt_view_res)) {

                $b2b_survey_ignore = 0;
                $b2b_survey_ignore_by = "";
                $b2b_survey_ignore_on = "";
                $sql_ignore = "SELECT * from loop_transaction_buyer where id = " . $dt_view_row["I"];
                $sql_res_ignore = db_query($sql_ignore);
                while ($dt_view_row_ignore = array_shift($sql_res_ignore)) {
                    $b2b_survey_ignore = $dt_view_row_ignore["b2b_survey_ignore"];
                    $b2b_survey_ignore_by = $dt_view_row_ignore["b2b_survey_ignore_by"];
                    $b2b_survey_ignore_on = $dt_view_row_ignore["b2b_survey_ignore_on"];

                    $customerpickup_ucbdelivering_flg = $dt_view_row_ignore["customerpickup_ucbdelivering_flg"];
                    $good_to_ship = $dt_view_row_ignore["good_to_ship"];
                }

                $activeflg_str = "";
                if ($dt_view_row["Active"] == 0) {
                    $activeflg_str = "<font face='arial' size='2' color='red'><b>&nbsp;INACTIVE</b><font>";
                }

                //Last tansaction Note
                $sql_ln = "SELECT message, date FROM loop_transaction_notes WHERE loop_transaction_notes.company_id = " . $dt_view_row["D"] . " and loop_transaction_notes.rec_id = " . $dt_view_row["I"] . " ORDER BY id DESC LIMIT 0,1";
                $result_ln = db_query($sql_ln);
                $last_note = array_shift($result_ln);

                $last_note_text = stripslashes(stripslashes($last_note["message"]));
                $last_note_date = $last_note["date"];

                /*if ($dt_view_row["po_delivery_dt"] <> "") {
					$Planned_delivery_date = $dt_view_row["po_delivery"]." ". date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
				}else {
					$Planned_delivery_date = $dt_view_row["po_delivery"];
				}*/

                if ($dt_view_row["po_delivery_dt"] == "") {
                    if ($dt_view_row["po_delivery_dt"] == "") {
                        $Planned_delivery_date = "";
                    } else {
                        $Planned_delivery_date = date("m/d/Y", strtotime($dt_view_row["po_delivery"]));
                    }
                } else {
                    $Planned_delivery_date = date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
                }
                //echo "Planned_delivery_date: " . $Planned_delivery_date . "<br>";
                $vendors_paid = 0; //Are the vendors paid
                $vendors_entered = 0; //Has a vendor transaction been entered?
                $invoice_paid = 0; //Have they paid their invoice?
                $invoice_entered = 0; //Has the inovice been entered
                $signed_customer_bol = 0;     //Customer Signed BOL Uploaded
                $courtesy_followup = 0;     //Courtesy Follow Up Made
                $delivered = 0;     //Delivered
                $signed_driver_bol = 0;     //BOL Signed By Driver
                $shipped = 0;     //Shipped
                $bol_received = 0;     //BOL Received @ WH
                $bol_sent = 0;     //BOL Sent to WH"
                $bol_created = 0;     //BOL Created
                $freight_booked = 0; //freight booked
                $sales_order = 0;   // Sales Order entered
                $goodtoship = 0; //Good To Ship
                $po_uploaded = 0;  //po uploaded 

                //Has an invoice amount been entered?
                if ($dt_view_row["F"] > 0) {
                    $invoice_entered = 1;
                }

                if (($dt_view_row["so_entered"] == 1)) {
                    $sales_order = 1;
                } //sales order created
                if (($dt_view_row["G"] == 1)) {
                    $goodtoship = 1;
                } //sales order created
                if ($dt_view_row["H"] != "") {
                    $po_uploaded = 1;
                } //po uploaded 

                //$freight_booked_delivery_date = $dt_view_row["freight_booked_delivery_date"];
                $booked_delivery_cost = $dt_view_row["booked_delivery_cost"];


                $quotedamount = $dt_view_row["po_poorderamount"];
                $freight_cost = $dt_view_row["po_freight"];

                $entinfo_link = "";
                $sql = "SELECT link FROM loop_transaction_buyer_freightview WHERE trans_rec_id = " . $dt_view_row["I"];
                $sql_res = db_query($sql);
                while ($row = array_shift($sql_res)) {
                    $entinfo_link = $row["link"];
                }

                $fr_pickup_date = "";
                $broker_id = 0;
                $freight_booked_delivery_date = "";
                $fr_dock_appointment = '';
                $sql = "SELECT date, time, broker_id, booked_delivery_date FROM loop_transaction_freight WHERE trans_rec_id = " . $dt_view_row["I"];
                $sql_res = db_query($sql);
                while ($row = array_shift($sql_res)) {
                    if ($row["date"] != "") {
                        $fr_pickup_date = $row["date"];
                    }
                    $broker_id = $row["broker_id"];
                    if ($row["booked_delivery_date"] != "") {
                        $freight_booked_delivery_date = $row["booked_delivery_date"];
                    }
                    $fr_dock_appointment = $row["time"];
                }

                $fr_pickup_date_delivery = "";
                $broker_id_delivery = 0;
                $fr_dock_appointment_delivery = '';
                $sql1 = "SELECT date, time FROM loop_transaction_freight_delivery WHERE trans_rec_id = " . $dt_view_row["I"];
                $sql_res1 = db_query($sql1);
                while ($row1 = array_shift($sql_res1)) {
                    if ($row1["date"] != "") {
                        $fr_pickup_date_delivery = $row1["date"];
                    }
                    $fr_dock_appointment_delivery = $row1["time"];
                }

                $unsign_bol_file = "";
                $actual_pickup_date = "";
                $actual_delivery_date = "";
                $bol_shipment_received = 0;
                //$sql = "SELECT file_name, bol_shipped_date, bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["I"];
                $sql = "SELECT file_name, bol_shipped_date, bol_shipment_received, bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $dt_view_row["I"];
                $sql_res = db_query($sql);
                while ($row = array_shift($sql_res)) {
                    $unsign_bol_file = $row["file_name"];
                    $actual_pickup_date = $row["bol_shipped_date"];
                    $actual_delivery_date = $row["bol_shipment_received_date"];
                    $bol_shipment_received = $row["bol_shipment_received"];
                }



                $rep = $dt_view_row["po_employee"];


                $po_term = "";
                $inv_date_req = "";
                $sql = "SELECT terms, timestamp FROM loop_invoice_details WHERE trans_rec_id = " . $dt_view_row["I"];
                $sql_res = db_query($sql);
                while ($row = array_shift($sql_res)) {
                    $po_term = $row["terms"];
                    $inv_date_req = $row["timestamp"];
                }

                $sort_source = "";
                if ($dt_view_row["virtual_inventory_company_id"] > 0) {
                    $sqlw = "SELECT company_name, b2bid FROM loop_warehouse WHERE id = " . $dt_view_row["virtual_inventory_company_id"];
                    $sql_resw = db_query($sqlw);
                    $source_vendor_name = "";
                    $comp_id_b2b = 0;
                    while ($roww = array_shift($sql_resw)) {
                        if ($roww["b2bid"] > 0) {
                            $comp_id_b2b = $roww["b2bid"];
                        }
                        $source_vendor_name = $roww["company_name"];
                    }

                    $sort_source = getnickname($source_vendor_name, $comp_id_b2b);
                } else {
                    $get_sales_order = db_query("Select location_warehouse_id, loop_boxes.vendor_b2b_rescue from loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " .  $dt_view_row["I"]);

                    while ($boxes = array_shift($get_sales_order)) {
                        $get_wh = "SELECT company_name FROM loop_warehouse WHERE id = " . $boxes["vendor_b2b_rescue"];
                        $get_wh_res = db_query($get_wh);
                        while ($the_wh = array_shift($get_wh_res)) {
                            $sort_source = $the_wh["company_name"];
                        }

                        if ($sort_source == "") {
                            $get_wh = "SELECT warehouse_name FROM loop_warehouse WHERE id = " . $boxes["location_warehouse_id"];
                            $get_wh_res = db_query($get_wh);
                            while ($the_wh = array_shift($get_wh_res)) {
                                $sort_source = $the_wh["warehouse_name"];
                            }
                        }
                    }
                }
                //echo $box_qry;

                $add_in_array = "yes";
                if ($tablenm == "awaitingpayment" || $tablenm == "doublechecksforpayroll" || $tablenm == "compdoublechecksforpayroll") {
                    $payment_val = 0;
                    $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
                    $payment_qry = db_query($payments_sql);
                    while ($payment = array_shift($payment_qry)) {
                        $payment_val = $payment["A"];
                    }
                    $payment1 = number_format($dt_view_row["F"], 2);
                    $payment2 = number_format($payment_val, 2);
                    $payment1 = str_replace(",", "", $payment1);
                    $payment2 = str_replace(",", "", $payment2);
                    //					echo $dt_view_row["I"] . " Payment: " . number_format($dt_view_row["F"],2) . " " . number_format($payment_val,2) . " - " . $payment1 . " " . $payment2 . "<br>";

                    if ($payment1 == $payment2 && $payment1 > 0) {
                        $invoice_paid = 1;
                    }
                    if ($dt_view_row["no_invoice"] == 1) {
                        $invoice_paid = 1;
                    }

                    if ($tablenm == "awaitingpayment") {
                        if ($invoice_paid == 1) {
                            $add_in_array = "no";
                        }
                    }
                    if ($tablenm == "doublechecksforpayroll" || $tablenm == "compdoublechecksforpayroll") {
                        if ($invoice_paid == 1) {
                            $add_in_array = "yes";
                        } else {
                            $add_in_array = "no";
                        }
                    }
                }

                //for table 4, if customer pickup then do not check for TMS data				
                if ($tablenm == "enterintoTMS") {
                    if ($dt_view_row["customerpickup_ucbdelivering_flg"] == 0) {
                        $add_in_array = "yes";
                    }

                    if ($dt_view_row["customerpickup_ucbdelivering_flg"] == 1) {
                        $freight_eml_found = "n";
                        $payments_sql = "Select trans_rec_id from loop_transaction_buyer_scheduleeml WHERE trans_rec_id = " . $dt_view_row["I"] . " group by trans_rec_id";
                        $payment_qry = db_query($payments_sql);
                        while ($payment = array_shift($payment_qry)) {
                            $freight_eml_found = "y";
                        }

                        if (($freight_eml_found == "y" || $dt_view_row["booking_freight_email_ignore"] == 1)) {
                            $add_in_array = "no";
                        }
                    }

                    if ($dt_view_row["customerpickup_ucbdelivering_flg"] == 2) {
                        $tmsdata_found = "n";
                        $payments_sql = "Select trans_rec_id from loop_enterprise_tms_data WHERE trans_rec_id = " . $dt_view_row["I"] . " group by trans_rec_id";
                        $payment_qry = db_query($payments_sql);
                        while ($payment = array_shift($payment_qry)) {
                            $tmsdata_found = "y";
                        }
                        $freight_eml_found = "n";
                        $payments_sql = "Select trans_rec_id from loop_transaction_buyer_scheduleeml WHERE trans_rec_id = " . $dt_view_row["I"] . " group by trans_rec_id";
                        $payment_qry = db_query($payments_sql);
                        while ($payment = array_shift($payment_qry)) {
                            $freight_eml_found = "y";
                        }

                        if (($freight_eml_found == "y" || $dt_view_row["booking_freight_email_ignore"] == 1) && ($tmsdata_found == "y" || $dt_view_row["lane_tms_ignore"] == 1)) {
                            $add_in_array = "no";
                        }
                    }
                }

                //6a table 
                if ($tablenm == "lanetendered_a") {
                    if (isset($customerpickup_ucbdelivering_flg) == 2) {
                        $rec_found_tender_lane1 = "no";
                        $payments_sql = "Select trans_rec_id from loop_transaction_buyer_freightview WHERE trans_rec_id = " . $dt_view_row["I"] . " ";
                        $payment_qry = db_query($payments_sql);
                        while ($payment = array_shift($payment_qry)) {
                            $rec_found_tender_lane2 = "no";
                            $sql_rec2 = "Select trans_rec_id from loop_transaction_freight WHERE trans_rec_id = " . $dt_view_row["I"] . " ";
                            $payment_qry = db_query($sql_rec2);
                            while ($payment = array_shift($payment_qry)) {
                                $rec_found_tender_lane2 = "yes";
                            }
                            if ($rec_found_tender_lane2 == "no") {
                                $rec_found_tender_lane1 = "yes";
                            }
                        }
                        if ($rec_found_tender_lane1 == "no" && isset($good_to_ship) == 0) {
                            $add_in_array = "no";
                        }
                    }

                    if (isset($customerpickup_ucbdelivering_flg) == 1 && isset($good_to_ship) == 0) {
                        $add_in_array = "no";
                    }

                    if (isset($customerpickup_ucbdelivering_flg) == 1 && isset($good_to_ship) == 1) {
                        $rec_found_tender_lane1 = "no";

                        $payments_sql = "Select trans_rec_id from loop_transaction_freight WHERE trans_rec_id = " . $dt_view_row["I"] . " ";
                        $payment_qry = db_query($payments_sql);
                        while ($payment = array_shift($payment_qry)) {
                            $rec_found_tender_lane1 = "yes";
                        }
                        //echo "Rec id - " . $dt_view_row["I"] . " - " . $rec_found_tender_lane1 . "<br>";
                        if ($rec_found_tender_lane1 == "yes") {
                            $add_in_array = "no";
                        }
                    }
                }

                //6b table 
                if ($tablenm == "lanetendered_b") {
                    if ($bol_shipment_received == 1) {
                        $add_in_array = "no";
                    }
                }

                //if paid
                $sort_warehouse_id = $dt_view_row["D"];
                $sort_id = $dt_view_row["I"];
                //$sort_company_name = $dt_view_row["B"];
                $sort_company_name = getnickname($dt_view_row["B"], $dt_view_row["b2bid"]);

                $sort_last_note = stripslashes(stripslashes($last_note["message"]));
                if ($dt_view_row["H"] == "") {
                    $sort_last_note_dt = "";
                } else {
                    $sort_last_note_dt = date("Y-m-d", strtotime($dt_view_row["H"]));
                }
                $sort_po_delivery_dt = $Planned_delivery_date;

                $sort_quantity =  0;
                $sort_ship_date = "";
                $sort_last_action = "";
                $sort_next_action = "";
                $sort_invoice_amount = 0;
                $sort_balance = 0;
                $sort_invoice_age = 0;
                $sort_flag = $activeflg_str;

                $start_t = strtotime($dt_view_row["J"]);
                $end_time =  strtotime('now');
                $invoice_age = number_format(($end_time - $start_t) / (3600 * 24), 0);

                if ($add_in_array == "yes") {
                    $count = $count + 1;

                    $ops_delivery_date = "";
                    if ($dt_view_row["ops_delivery_date"] != "") {
                        $ops_delivery_date = date("m/d/Y", strtotime($dt_view_row["ops_delivery_date"]));
                    }

                    $freightbroker = "";
                    $freight_booking_vendor = $freight_booking_vendor ?? '';
                    if ($tablenm == "bolcreated" || $tablenm == "onroad") {
                        $freightbroker = $dt_view_row["freightbroker"];
                    }

                    $MGArray[] = array(
                        'count' => $count, 'compid' => $dt_view_row["b2bid"], 'rep' => $rep, 'warehouse_id' => $sort_warehouse_id, 'ID' => $sort_id, 'company_name' => $sort_company_name, 'fr_pickup_date' => $fr_pickup_date, 'ops_delivery_date' => $ops_delivery_date,
                        'last_note_date' => $last_note_date, 'last_note_text' => $sort_last_note, 'po_upload_date' => $sort_last_note_dt, 'po_delivery_dt' => $sort_po_delivery_dt, 'source' => $sort_source, 'booked_delivery_cost' => $booked_delivery_cost, 'freightbroker' => $freightbroker,
                        'quantity' => $sort_quantity, 'ship_date' => $sort_ship_date, 'last_action' => $sort_last_action, 'next_action' => $sort_next_action, 'broker_id' => $broker_id, 'tender_lane_additional_freight_costs' => $dt_view_row["tender_lane_additional_freight_costs"],
                        'actual_pickup_date' => $actual_pickup_date, 'actual_delivery_date' => $actual_delivery_date, 'invoice_amount' => number_format($dt_view_row["F"], 2), 'invoice_age' => $invoice_age, 'inv_date_req' => $inv_date_req,
                        'balance' => $sort_balance, 'active' => $sort_flag, 'quotedamount' => $quotedamount, 'freight_cost' => $freight_cost, 'entinfo_link' => $entinfo_link, 'freight_booked_delivery_date' => $freight_booked_delivery_date, 'po_term' => $po_term,
                        'sales_order' => $goodtoship, 'po_uploaded' => $po_uploaded, 'shipped' => $shipped, 'bol_created' => $bol_created, 'courtesy_followup' => $courtesy_followup, 'unsign_bol_file' => $unsign_bol_file, 'po_file' => $dt_view_row["po_file"],
                        'delivered' => $delivered, 'invoice_paid' => $invoice_paid, 'invoice_entered' => $invoice_entered, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered, 'fr_dock_appointment' => $fr_dock_appointment, 'fr_pickup_date_delivery' => $fr_pickup_date_delivery, 'fr_dock_appointment_delivery' => $fr_dock_appointment_delivery, 'freight_booking_vendor' => $freight_booking_vendor
                    );

                    if ($_REQUEST['sort'] <> "") {
                    } else {

                        if ($tablenm == "bolcreated" || $tablenm == "onroad") {
                            if ($freightbroker_chg != $MGArray[$count - 1]['freightbroker']) { ?>
        <tr>
            <td colspan="17">&nbsp;</td>
        </tr>
        <?php }
                        }

                        $freightbroker_chg = $MGArray[$count - 1]['freightbroker'];
                        ?>
        <tr id="tbl_div<?php echo $MGArray[$count - 1]["count"]; ?>">

            <td bgColor="#e4e4e4" class="style12">
                <u><a target="_blank"
                        href="viewCompany.php?ID=<?php echo $MGArray[$count - 1]['compid']; ?>&show=transactions&warehouse_id=<?php echo $MGArray[$count - 1]['warehouse_id']; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $MGArray[$count - 1]['warehouse_id']; ?>&rec_id=<?php echo $MGArray[$count - 1]['ID']; ?>&display=<?php echo $rec_display; ?>"><?php echo $MGArray[$count - 1]['ID']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <p align="center">
                    <span class="infotxt"><u><a target="_blank"
                                href="viewCompany.php?ID=<?php echo $MGArray[$count - 1]['compid']; ?>&show=transactions&warehouse_id=<?php echo $MGArray[$count - 1]['warehouse_id']; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $MGArray[$count - 1]['warehouse_id']; ?>&rec_id=<?php echo $MGArray[$count - 1]['ID']; ?>&display=<?php echo $rec_display; ?>"><?php echo $MGArray[$count - 1]["company_name"] . $MGArray[$count - 1]["active"]; ?></a></u>
                        <span style="width:570px;">
                            <table cellSpacing="1" cellPadding="1" border="0" width="570">
                                <tr align="middle">
                                    <td class="style7" colspan="3" style="height: 16px"><strong>SALE ORDER DETAILS FOR
                                            ORDER ID: <?php echo $MGArray[$count - 1]['ID']; ?></strong></td>
                                </tr>

                                <tr vAlign="center">
                                    <td bgColor="#e4e4e4" width="70" class="style17">
                                        <font size=1>
                                            <strong>QTY</strong>
                                        </font>
                                    </td>
                                    <td bgColor="#e4e4e4" width="100" class="style17">
                                        <font size=1>
                                            <strong>Warehouse</strong>
                                        </font>
                                    </td>
                                    <td bgColor="#e4e4e4" width="400" class="style17">
                                        <font size=1>
                                            <strong>Box Description</strong>
                                        </font>
                                    </td>
                                </tr>
                                <?php

                                                $get_sales_order = db_query("Select *, loop_salesorders.notes AS A, loop_salesorders.pickup_date AS B, loop_salesorders.freight_vendor AS C, loop_salesorders.time AS D, loop_boxes.isbox AS I From loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " . $MGArray[$count - 1]['ID']);

                                                while ($boxes = array_shift($get_sales_order)) {
                                                    $so_notes = $boxes["A"];
                                                    $so_pickup_date = $boxes["B"];
                                                    $so_freight_vendor = $boxes["C"];
                                                    $so_time = $boxes["D"];
                                                ?>
                                <tr bgColor="#e4e4e4">
                                    <td height="13" class="style1" align="right">
                                        <Font Face='arial' size='1'><?php echo $boxes["qty"]; ?>
                                    </td>
                                    <td height="13" style="width: 94px" class="style1" align="right">
                                        <Font Face='arial' size='1'><?php
                                                                                        $get_wh = "SELECT warehouse_name FROM loop_warehouse WHERE id = " . $boxes["location_warehouse_id"];
                                                                                        $get_wh_res = db_query($get_wh);
                                                                                        while ($the_wh = array_shift($get_wh_res)) {
                                                                                            echo $the_wh["warehouse_name"];
                                                                                        }
                                                                                        ?>
                                    </td>

                                    <td align="left" height="13" style="width: 578px" class="style1">
                                        <?php if ($boxes["I"] == "Y") { ?>
                                        <font size="1" Face="arial"><?php echo $boxes["blength"]; ?>
                                            <?php echo $boxes["blength_frac"]; ?> x <?php echo $boxes["bwidth"]; ?>
                                            <?php echo $boxes["bwidth_frac"]; ?> x <?php echo $boxes["bdepth"]; ?>
                                            <?php echo $boxes["bdepth_frac"]; ?> <?php echo $boxes["bdescription"]; ?>
                                        </font>
                                        <?php } else { ?>
                                        <font size="1" Face="arial"><?php echo $boxes["bdescription"]; ?></font>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php  } ?>

                                <?php

                                                $soqry = "Select * From loop_salesorders_manual WHERE trans_rec_id = " . $MGArray[$count - 1]['ID'];
                                                $get_sales_order2 = db_query($soqry);
                                                while ($boxes2 = array_shift($get_sales_order2)) {

                                                ?>
                                <tr bgColor="#e4e4e4">
                                    <td height="13" class="style1" align="right">
                                        <Font Face='arial' size='1'><?php echo $boxes2["qty"]; ?>
                                    </td>
                                    <td height="13" class="style1" align="right">&nbsp;</td>

                                    <td align="left" height="13" style="width: 578px" class="style1" colspan=2>
                                        <font size="1" Face="arial">&nbsp;&nbsp;<?php echo $boxes2["description"]; ?>
                                        </font>
                                    </td>
                                </tr>

                                <?php     }    ?>
                            </table>
                        </span>
                    </span>
                </p>
            </td>
            <td bgColor="#e4e4e4" class="stylenew">
                <textarea rows=3 cols=35 name="note" align="left"
                    id="note<?php echo $MGArray[$count - 1]["count"]; ?>"><?php echo trim($MGArray[$count - 1]['last_note_text']); ?></textarea>
            </td>
            <?php
                            $show_red = "";
                            if ($tablenm == "customerready" || $tablenm == "enterintoTMS" || $tablenm == "tenderlane" || $tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "onroad" || $tablenm == "delivered") {
                                if (trim($MGArray[$count - 1]['last_note_text']) == "" || (date("Y-m-d", strtotime($MGArray[$count - 1]['last_note_date'])) < date("Y-m-d"))) {
                                    $show_red = "red";
                                }
                            }
                            ?>
            <td bgColor="#e4e4e4" class="style12">
                <a id='translog<?php echo $MGArray[$count - 1]["count"]; ?>' href='#'
                    onclick='displaytrans_log(<?php echo $MGArray[$count - 1]["count"]; ?>, <?php echo $MGArray[$count - 1]['warehouse_id']; ?>, <?php echo $MGArray[$count - 1]['ID']; ?>); return false;'>
                    <font color="<?php echo $show_red; ?>"><?php echo $MGArray[$count - 1]['last_note_date']; ?></font>
                </a>
            </td>
            <?php if ($tablenm == "requestinvoice" || $tablenm == "requestinvoice_10b" || $tablenm == "qbinvoice" || $tablenm == "awaitingpayment" || $tablenm == "doublechecksforpayroll" || $tablenm == "compdoublechecksforpayroll") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArray[$count - 1]['actual_delivery_date']; ?>
            </td>
            <?php     }

                            if ($tablenm == "pouploaded" || $tablenm == "customernotready" || $tablenm == "customerready" || $tablenm == "blankopsdeliverydt" || $tablenm == "enterintoTMS" || $tablenm == "tenderlane") {
                            ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArray[$count - 1]['po_upload_date'] != "") {
                                        echo date("m/d/Y", strtotime($MGArray[$count - 1]['po_upload_date']));
                                    } ?>
            </td>
            <?php }

                            if ($tablenm == "pouploaded" || $tablenm == "customernotready" || $tablenm == "customerready" || $tablenm == "blankopsdeliverydt" || $tablenm == "enterintoTMS" || $tablenm == "tenderlane" || $tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated") {
                            ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtpo_delivery_dt"
                    id="txtpo_delivery_dt<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['po_delivery_dt']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtpo_delivery_dt<?php echo $MGArray[$count - 1]["count"]; ?>,'dtanchor1xx<?php echo $MGArray[$count - 1]["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor1xx" id="dtanchor1xx<?php echo $MGArray[$count - 1]["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
                <?php //if ($MGArray[$count-1]['po_delivery_dt'] != "") { echo date("m/d/Y" , strtotime($MGArray[$count-1]['po_delivery_dt'])); }
                                    ?>
            </td>
            <?php     }    ?>

            <?php
                            if ($tablenm == "onroad") {
                            ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArray[$count - 1]['po_delivery_dt'] != "") {
                                        echo date("m/d/Y", strtotime($MGArray[$count - 1]['po_delivery_dt']));
                                    } ?>
            </td>
            <?php     }    ?>

            <?php
                            if ($tablenm == "blankopsdeliverydt") {
                            ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtops_delivery_dt"
                    id="txtops_delivery_dt<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['ops_delivery_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtops_delivery_dt<?php echo $MGArray[$count - 1]["count"]; ?>,'dtanchor1xx<?php echo $MGArray[$count - 1]["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor1xx" id="dtanchor1xx<?php echo $MGArray[$count - 1]["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <?php     }    ?>
            <?php
                            if ($tablenm == "customernotready" || $tablenm == "customerready" || $tablenm == "enterintoTMS" || $tablenm == "tenderlane" || $tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "onroad") {
                            ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArray[$count - 1]['ops_delivery_date'] != "") {
                                        echo date("m/d/Y", strtotime($MGArray[$count - 1]['ops_delivery_date']));
                                    } ?>
            </td>
            <?php     }    ?>

            <?php if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated") {    ?>
            <?php if ($tablenm == "bolcreated") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfr_pickup_date"
                    id="txtfr_pickup_date<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['fr_pickup_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfr_pickup_date<?php echo $MGArray[$count - 1]["count"]; ?>,'dtanchor2xx<?php echo $MGArray[$count - 1]["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor2xx" id="dtanchor2xx<?php echo $MGArray[$count - 1]["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfreight_booked_delivery_date"
                    id="txtfreight_booked_delivery_date<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['freight_booked_delivery_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfreight_booked_delivery_date<?php echo $MGArray[$count - 1]["count"]; ?>,'dtanchor3xx<?php echo $MGArray[$count - 1]["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor3xx" id="dtanchor3xx<?php echo $MGArray[$count - 1]["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <?php     }    ?>
            <?php if ($tablenm == "lanetendered_a") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfr_pickup_date"
                    id="txtfr_pickup_date<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['fr_pickup_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfr_pickup_date<?php echo $MGArray[$count - 1]["count"]; ?>,'dtanchor2xx<?php echo $MGArray[$count - 1]["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor2xx" id="dtanchor2xx<?php echo $MGArray[$count - 1]["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <input type=text name="txtfr_dock_appointment" size="20"
                    value="<?php echo $MGArray[$count - 1]['fr_dock_appointment']; ?>"
                    id="txtfr_dock_appointment<?php echo $MGArray[$count - 1]["count"]; ?>">
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php if (isset($customerpickup_ucbdelivering_flg) == 2) { ?>
                <input type="text" size="8" name="txtfreight_booked_delivery_date"
                    id="txtfreight_booked_delivery_date<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['freight_booked_delivery_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfreight_booked_delivery_date<?php echo $MGArray[$count - 1]["count"]; ?>,'dtanchor3xx<?php echo $MGArray[$count - 1]["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor3xx" id="dtanchor3xx<?php echo $MGArray[$count - 1]["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
                <?php     } else {
                                            echo "Customer Pickup";
                                        } ?>
            </td>
            <?php     }    ?>
            <?php if ($tablenm == "lanetendered_b") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfr_pickup_date_delivery"
                    id="txtfr_pickup_date_delivery<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['fr_pickup_date_delivery']; ?>">
                <a href="#"
                    onclick="cal2_bxx.select(document.frmnewpage_b.txtfr_pickup_date_delivery<?php echo $MGArray[$count - 1]["count"]; ?>,'dtanchor2_bxx<?php echo $MGArray[$count - 1]["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor2_bxx" id="dtanchor2_bxx<?php echo $MGArray[$count - 1]["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <input type=text name="txtfr_dock_appointment_delivery" size="20"
                    value="<?php echo $MGArray[$count - 1]['fr_dock_appointment_delivery']; ?>"
                    id="txtfr_dock_appointment_delivery<?php echo $MGArray[$count - 1]["count"]; ?>">
            </td>
            <?php     }    ?>

            <?php     }    ?>

            <?php if ($tablenm == "onroad") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArray[$count - 1]['actual_pickup_date']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfreight_booked_delivery_date"
                    id="txtfreight_booked_delivery_date<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['freight_booked_delivery_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfreight_booked_delivery_date<?php echo $MGArray[$count - 1]["count"]; ?>,'dtanchor3xx<?php echo $MGArray[$count - 1]["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor3xx" id="dtanchor3xx<?php echo $MGArray[$count - 1]["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <?php     }    ?>
            <?php if ($tablenm == "delivered") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArray[$count - 1]['freight_booked_delivery_date']; ?>
            </td>
            <?php     }    ?>

            <?php if ($tablenm != "poentered"  && $tablenm != "pouploaded" && $tablenm != "delivered") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo ucfirst($MGArray[$count - 1]['source']); ?>
            </td>
            <?php     }    ?>

            <?php if ($tablenm == "requestinvoice" || $tablenm == "requestinvoice_10b" || $tablenm == "qbinvoice" || $tablenm == "awaitingpayment" || $tablenm == "doublechecksforpayroll" || $tablenm == "compdoublechecksforpayroll") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArray[$count - 1]['po_file'] != "") { ?>
                <a href="po/<?php echo $MGArray[$count - 1]['po_file']; ?>" target="_blank" />View</a>
                <?php  } ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArray[$count - 1]['unsign_bol_file'] != "") { ?>
                <a href="bol/<?php echo $MGArray[$count - 1]['unsign_bol_file']; ?>" target="_blank" />View</a>
                <?php  } else { ?>
                <?php echo "No"; ?>
                <?php  } ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArray[$count - 1]['po_term']; ?>
            </td>
            <?php     }    ?>

            <?php if ($tablenm == "qbinvoice") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArray[$count - 1]['inv_date_req']; ?>
            </td>
            <?php     }    ?>

            <?php if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "onroad") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArray[$count - 1]['unsign_bol_file'] != "") { ?>
                <a href="bol/<?php echo $MGArray[$count - 1]['unsign_bol_file']; ?>" target="_blank" />View</a>
                <?php  } else { ?>
                <?php echo "No"; ?>
                <?php  } ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php
                                    if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b") {
                                        echo $MGArray[$count - 1]['freight_booking_vendor'];
                                    } else {
                                        $fsql = "SELECT id, company_name FROM loop_freightvendor where id = " . $MGArray[$count - 1]['broker_id'];
                                        $fresult = db_query($fsql);
                                        while ($fmyrowsel = array_shift($fresult)) {
                                    ?>
                <?php echo $fmyrowsel["company_name"]; ?>
                <?php } ?>
                <input type="hidden" name="freight_booking_vendor"
                    id="freight_booking_vendor<?php echo $MGArray[$count - 1]["count"]; ?>">
                <?php } ?>

            </td>


            <?php if ($tablenm == "bolcreated" || $tablenm == "onroad") {    ?>
            <td bgColor="#e4e4e4" class="style12"><?php echo number_format($MGArray[$count - 1]['freight_cost'], 2); ?>
            </td>

            <td bgColor="#e4e4e4" class="style12"><?php echo $MGArray[$count - 1]['booked_delivery_cost']; ?></td>


            <td bgColor="#e4e4e4" class="style12">

                <?php if ($MGArray[$count - 1]['entinfo_link'] != "") { ?>
                <a href="<?php echo $MGArray[$count - 1]['entinfo_link']; ?>" target="_blank" />View</a>
                <?php  } ?>
            </td>
            <?php     }    ?>
            <?php     }    ?>

            <?php if ($tablenm == "delivered") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArray[$count - 1]['unsign_bol_file'] != "") { ?>
                <a href="bol/<?php echo $MGArray[$count - 1]['unsign_bol_file']; ?>" target="_blank" />View</a>
                <?php  } else { ?>
                <?php echo "No"; ?>
                <?php  } ?>
            </td>

            <td bgColor="#e4e4e4" class="style12">
                <?php
                                    $fsql = "SELECT id, company_name FROM loop_freightvendor where id = " . $MGArray[$count - 1]['broker_id'];
                                    $fresult = db_query($fsql);
                                    while ($fmyrowsel = array_shift($fresult)) {
                                    ?>
                <?php echo $fmyrowsel["company_name"]; ?>
                <?php } ?>
                <input type="hidden" name="freight_booking_vendor"
                    id="freight_booking_vendor<?php echo $MGArray[$count - 1]["count"]; ?>">
            </td>

            <td bgColor="#e4e4e4" class="style12">

                <?php echo number_format($MGArray[$count - 1]['tender_lane_additional_freight_costs'], 2); ?>
            </td>

            <td bgColor="#e4e4e4" class="style12">
                <!--<input type="text" size="20" name="txt_entinfo_link" id="txt_entinfo_link<?php echo $MGArray[$count - 1]["count"]; ?>" value="<?php echo $MGArray[$count - 1]['entinfo_link']; ?>">-->
                <?php if ($MGArray[$count - 1]['entinfo_link'] != "") { ?>
                <a href="<?php echo $MGArray[$count - 1]['entinfo_link']; ?>" target="_blank" />View</a>
                <?php  } ?>
            </td>
            <?php     }    ?>

            <?php if ($tablenm == "awaitingpayment") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo "$" . $MGArray[$count - 1]['invoice_amount']; ?>
            </td>
            <?php
                                if ($MGArray[$count - 1]["invoice_age"] > 30) {
                                ?>
            <td bgColor="#ff0000" class="style12">
                <?php echo $MGArray[$count - 1]["invoice_age"]; ?>
            </td>
            <?php
                                } elseif (number_format(($end_time - $start_t) / (3600 * 24000), 0) > 10) {
                                ?>
            <td bgColor="#e4e4e4" class="style12">
                &nbsp;
            </td>
            <?php
                                } else {
                                ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArray[$count - 1]["invoice_age"]; ?>
            </td>
            <?php
                                }
                                ?>
            <?php     }    ?>

            <?php if ($tablenm != "poentered") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArray[$count - 1]['rep']; ?>
            </td>
            <?php     }    ?>

            <?php if ($tablenm == "enterintoTMS" || $tablenm == "tenderlane") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                $<?php echo number_format($MGArray[$count - 1]['freight_cost'], 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">

                <?php if ($MGArray[$count - 1]['entinfo_link'] != "") { ?>
                <a href="<?php echo $MGArray[$count - 1]['entinfo_link']; ?>" target="_blank" />View</a>
                <?php  } ?>
            </td>
            <?php     }    ?>
            <?php if ($tablenm == "delivered") { ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php
                                    if ($b2b_survey_ignore == 1) {
                                        echo "B2B Survey ignore by " . $b2b_survey_ignore_by . " on " . $b2b_survey_ignore_on . "<br>";
                                    } else {

                                        $dashboardflg = $dashboardflg ?? '';
                                        if ($dashboardflg == "n") {
                                    ?>
                <form action="bol_confirmshipmentfollowup_mrg.php" method=post>
                    <input type="hidden" name="ID" value="<?php echo $MGArray[$count - 1]["compid"]; ?>">
                    <input type="hidden" name="rec_type" value="Supplier">
                    <input type="hidden" name="rec_id" value="<?php echo $MGArray[$count - 1]['ID']; ?>">
                    <input type="hidden" name="bol_id" value="<?php echo $MGArray[$count - 1]['ID']; ?>">
                    <input type="hidden" name="userinitials" value="<?php echo $_COOKIE["userinitials"]; ?>">
                    <input type="hidden" name="location" value="loopsreceived">
                    <input type="hidden" name="warehouse_id"
                        value="<?php echo $MGArray[$count - 1]['warehouse_id']; ?>">

                    <input type="button" value="B2B Survey" style="cursor:pointer;"
                        id="reminder_popup_set5_btn<?php echo $MGArray[$count - 1]["count"]; ?>"
                        onclick="reminder_popup_set5(<?php echo $MGArray[$count - 1]["compid"]; ?>,<?php echo $MGArray[$count - 1]['ID']; ?>,<?php echo $MGArray[$count - 1]['warehouse_id']; ?>,'Supplier', <?php echo $MGArray[$count - 1]["count"]; ?>)">
                </form>
                <input type="button" name="btnignoreb2bsurvey" id="btnignoreb2bsurvey" value="Ignore B2B Survey"
                    onclick="update_details_b2bsurvey_ignore(<?php echo $MGArray[$count - 1]["count"]; ?>)">
                <?php
                                        }
                                    } ?>
            </td>
            <?php     }    ?>
            <td bgColor="#e4e4e4" class="style12">

                <input type="hidden" name="transid" id="transid<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['ID']; ?>">
                <input type="hidden" name="warehouseid" id="warehouseid<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $MGArray[$count - 1]['warehouse_id']; ?>">
                <input type="hidden" name="tablenm" id="tablenm<?php echo $MGArray[$count - 1]["count"]; ?>"
                    value="<?php echo $tablenm; ?>">

                <?php if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated") {    ?>
                <?php if ($tablenm == "lanetendered_a") {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details_6(<?php echo $MGArray[$count - 1]["count"]; ?>)">
                <?php } else if ($tablenm == "lanetendered_b") {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details_6_7_b(<?php echo $MGArray[$count - 1]["count"]; ?>)">
                <?php } else if ($tablenm == "bolcreated") {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details_7(<?php echo $MGArray[$count - 1]["count"]; ?>)">
                <?php } ?>
                <!-- <input type="button" name="btnupdate" id="btnupdate" value="Update" onclick="update_details_6_7(<?php echo $MGArray[$count - 1]["count"]; ?>)"> -->
                <?php } else if ($tablenm == "onroad" || $tablenm == "delivered") {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details_8_9(<?php echo $MGArray[$count - 1]["count"]; ?>)">
                <?php } else {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details(<?php echo $MGArray[$count - 1]["count"]; ?>)">
                <?php }    ?>
            </td>

            <?php

                            if (isset($dashboardflg) == "n") {

                            ?>
            <?php if ($tablenm == "pouploaded") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btnmovepending" id="btnmovepending" value="Pending"
                    onclick="update_movepending(<?php echo $MGArray[$count - 1]["count"]; ?>)">
            </td>
            <?php }    ?>
            <?php if ($tablenm == "customernotready") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btnmovepending" id="btnmovepending" value="Pending"
                    onclick="update_movepending_preorder(<?php echo $MGArray[$count - 1]["count"]; ?>)">
            </td>
            <?php }    ?>
            <?php if ($tablenm == "customerready" || $tablenm == "blankopsdeliverydt") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btnmovepending" id="btnmovepending" value="Good to Ship"
                    onclick="update_goodtoship(<?php echo $MGArray[$count - 1]["count"]; ?>)">
            </td>
            <?php }    ?>
            <?php if ($tablenm == "doublechecksforpayroll") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btndoublecheck" id="btndoublecheck" value="Checked"
                    onclick="update_checked(<?php echo $MGArray[$count - 1]["count"]; ?>)">
            </td>
            <?php }    ?>
            <?php if ($tablenm == "compdoublechecksforpayroll") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btndoublecheck_undo" id="btndoublecheck_undo" value="Undo Checked"
                    onclick="update_checked_undo(<?php echo $MGArray[$count - 1]["count"]; ?>)">
            </td>
            <?php }
                            }
                            ?>
        </tr>

        <?php  }
                }
            }

            if ($_REQUEST['sort'] <> "") {
                if ($_REQUEST['sort'] == "ID" && $_REQUEST['sort_order_pre'] == "ASC") {
                    $MGArraysort_I = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['ID'];
                    }
                    array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                }
                if ($_REQUEST['sort'] == "ID" && $_REQUEST['sort_order_pre'] == "DESC") {
                    $MGArraysort_I = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_I[] = $MGArraytmp['ID'];
                    }
                    array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                }

                if ($_REQUEST['sort'] == "company_name" && $_REQUEST['sort_order_pre'] == "ASC") {
                    $MGArraysort_B = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_B[] = $MGArraytmp['company_name'];
                    }
                    array_multisort($MGArraysort_B, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "company_name" && $_REQUEST['sort_order_pre'] == "DESC") {
                    $MGArraysort_B = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_B[] = $MGArraytmp['company_name'];
                    }
                    array_multisort($MGArraysort_B, SORT_DESC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "last_note_text" && $_REQUEST['sort_order_pre'] == "ASC") {
                    $MGArraysort_B = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_B[] = $MGArraytmp['last_note_text'];
                    }
                    array_multisort($MGArraysort_B, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "last_note_text" && $_REQUEST['sort_order_pre'] == "DESC") {
                    $MGArraysort_B = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_B[] = $MGArraytmp['last_note_text'];
                    }
                    array_multisort($MGArraysort_B, SORT_DESC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "last_note_date" && $_REQUEST['sort_order_pre'] == "ASC") {
                    $MGArraysort_B = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_B[] = $MGArraytmp['last_note_date'];
                    }
                    array_multisort($MGArraysort_B, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "last_note_date" && $_REQUEST['sort_order_pre'] == "DESC") {
                    $MGArraysort_B = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_B[] = $MGArraytmp['last_note_date'];
                    }
                    array_multisort($MGArraysort_B, SORT_DESC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "po_upload_date" && $_REQUEST['sort_order_pre'] == "ASC") {
                    $MGArraysort_D = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_D[] = $MGArraytmp['po_upload_date'];
                    }
                    array_multisort($MGArraysort_D, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "po_upload_date" && $_REQUEST['sort_order_pre'] == "DESC") {
                    $MGArraysort_D = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_D[] = $MGArraytmp['po_upload_date'];
                    }
                    array_multisort($MGArraysort_D, SORT_DESC, SORT_STRING, $MGArray);
                }
                ///////
                if ($_REQUEST['sort'] == "po_delivery_dt" && $_REQUEST['sort_order_pre'] == "ASC") {
                    $MGArraysort_E = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_E[] = $MGArraytmp['po_delivery_dt'];
                    }
                    array_multisort($MGArraysort_E, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "po_delivery_dt" && $_REQUEST['sort_order_pre'] == "DESC") {
                    $MGArraysort_E = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_E[] = $MGArraytmp['po_delivery_dt'];
                    }
                    array_multisort($MGArraysort_E, SORT_DESC, SORT_STRING, $MGArray);
                }
                /////////
                if ($_REQUEST['sort'] == "source" && $_REQUEST['sort_order_pre'] == "ASC") {
                    $MGArraysort_F = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_F[] = $MGArraytmp['source'];
                    }
                    array_multisort($MGArraysort_F, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "source" && $_REQUEST['sort_order_pre'] == "DESC") {
                    $MGArraysort_F = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_F[] = $MGArraytmp['source'];
                    }
                    array_multisort($MGArraysort_F, SORT_DESC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "rep" && $_REQUEST['sort_order_pre'] == "ASC") {
                    $MGArraysort_B = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_B[] = $MGArraytmp['rep'];
                    }
                    array_multisort($MGArraysort_B, SORT_ASC, SORT_STRING, $MGArray);
                }
                if ($_REQUEST['sort'] == "rep" && $_REQUEST['sort_order_pre'] == "DESC") {
                    $MGArraysort_B = array();

                    foreach ($MGArray as $MGArraytmp) {
                        $MGArraysort_B[] = $MGArraytmp['rep'];
                    }
                    array_multisort($MGArraysort_B, SORT_DESC, SORT_STRING, $MGArray);
                }

                foreach ($MGArray as $MGArraytmp2) { ?>

        <tr id="tbl_div<?php echo $MGArraytmp2["count"]; ?>">

            <td bgColor="#e4e4e4" class="style12">
                <u><a target="_blank"
                        href="viewCompany.php?ID=<?php echo $MGArraytmp2['compid']; ?>&show=transactions&warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=<?php echo $rec_display; ?>"><?php echo $MGArraytmp2['ID']; ?></a></u>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <p align="center">
                    <span class="infotxt"><u><a target="_blank"
                                href="viewCompany.php?ID=<?php echo $MGArraytmp2['compid']; ?>&show=transactions&warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&proc=View&searchcrit=&rec_type=Supplier&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=<?php echo $rec_display; ?>"><?php echo $MGArraytmp2["company_name"] . $MGArraytmp2["active"]; ?></a></u>
                        <span style="width:570px;">
                            <table cellSpacing="1" cellPadding="1" border="0" width="570">
                                <tr align="middle">
                                    <td class="style7" colspan="3" style="height: 16px"><strong>SALE ORDER DETAILS FOR
                                            ORDER ID: <?php echo $MGArraytmp2['ID']; ?></strong></td>
                                </tr>

                                <tr vAlign="center">
                                    <td bgColor="#e4e4e4" width="70" class="style17">
                                        <font size=1>
                                            <strong>QTY</strong>
                                        </font>
                                    </td>
                                    <td bgColor="#e4e4e4" width="100" class="style17">
                                        <font size=1>
                                            <strong>Warehouse</strong>
                                        </font>
                                    </td>
                                    <td bgColor="#e4e4e4" width="400" class="style17">
                                        <font size=1>
                                            <strong>Box Description</strong>
                                        </font>
                                    </td>
                                </tr>
                                <?php

                                            $get_sales_order = db_query("Select *, loop_salesorders.notes AS A, loop_salesorders.pickup_date AS B, loop_salesorders.freight_vendor AS C, loop_salesorders.time AS D, loop_boxes.isbox AS I From loop_salesorders Inner Join loop_boxes ON loop_salesorders.box_id = loop_boxes.id WHERE trans_rec_id = " . $MGArraytmp2['ID']);

                                            while ($boxes = array_shift($get_sales_order)) {
                                                $so_notes = $boxes["A"];
                                                $so_pickup_date = $boxes["B"];
                                                $so_freight_vendor = $boxes["C"];
                                                $so_time = $boxes["D"];
                                            ?>
                                <tr bgColor="#e4e4e4">
                                    <td height="13" class="style1" align="right">
                                        <Font Face='arial' size='1'><?php echo $boxes["qty"]; ?>
                                    </td>
                                    <td height="13" style="width: 94px" class="style1" align="right">
                                        <Font Face='arial' size='1'><?php
                                                                                    $get_wh = "SELECT warehouse_name FROM loop_warehouse WHERE id = " . $boxes["location_warehouse_id"];
                                                                                    $get_wh_res = db_query($get_wh);
                                                                                    while ($the_wh = array_shift($get_wh_res)) {
                                                                                        echo $the_wh["warehouse_name"];
                                                                                    }
                                                                                    ?>
                                    </td>

                                    <td align="left" height="13" style="width: 578px" class="style1">
                                        <?php if ($boxes["I"] == "Y") { ?>
                                        <font size="1" Face="arial"><?php echo $boxes["blength"]; ?>
                                            <?php echo $boxes["blength_frac"]; ?> x <?php echo $boxes["bwidth"]; ?>
                                            <?php echo $boxes["bwidth_frac"]; ?> x <?php echo $boxes["bdepth"]; ?>
                                            <?php echo $boxes["bdepth_frac"]; ?> <?php echo $boxes["bdescription"]; ?>
                                        </font>
                                        <?php } else { ?>
                                        <font size="1" Face="arial"><?php echo $boxes["bdescription"]; ?></font>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php  } ?>

                                <?php
                                            $soqry = "Select * From loop_salesorders_manual WHERE trans_rec_id = " . $MGArraytmp2['ID'];
                                            $get_sales_order2 = db_query($soqry);
                                            while ($boxes2 = array_shift($get_sales_order2)) {
                                            ?>
                                <tr bgColor="#e4e4e4">
                                    <td height="13" class="style1" align="right">
                                        <Font Face='arial' size='1'><?php echo $boxes2["qty"]; ?>
                                    </td>
                                    <td height="13" class="style1" align="right">&nbsp;</td>

                                    <td align="left" height="13" style="width: 578px" class="style1" colspan=2>
                                        <font size="1" Face="arial">&nbsp;&nbsp;<?php echo $boxes2["description"]; ?>
                                        </font>
                                    </td>
                                </tr>

                                <?php }    ?>
                            </table>
                        </span>
                    </span>
                </p>
            </td>
            <td bgColor="#e4e4e4" class="stylenew">
                <textarea rows=3 cols=35 name="note" align="left"
                    id="note<?php echo $MGArraytmp2["count"]; ?>"><?php echo trim($MGArraytmp2['last_note_text']); ?></textarea>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['last_note_date']; ?>
            </td>
            <?php if ($tablenm == "requestinvoice" || $tablenm == "requestinvoice_10b" || $tablenm == "qbinvoice" || $tablenm == "awaitingpayment" || $tablenm == "doublechecksforpayroll" || $tablenm == "compdoublechecksforpayroll") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['actual_delivery_date']; ?>
            </td>
            <?php }

                        if ($tablenm == "pouploaded" || $tablenm == "customernotready" || $tablenm == "customerready" || $tablenm == "blankopsdeliverydt" || $tablenm == "enterintoTMS" || $tablenm == "tenderlane") {
                        ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArraytmp2['po_upload_date'] != "") {
                                    echo date("m/d/Y", strtotime($MGArraytmp2['po_upload_date']));
                                } ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <!-- <input type="text" size="8" name="txtpo_delivery_dt" id="txtpo_delivery_dt<?php echo $MGArraytmp2["count"]; ?>" value="<?php echo $MGArraytmp2['po_delivery_dt']; ?>">
									<a href="#" onclick="cal2xx.select(document.frmnewpage.txtpo_delivery_dt<?php echo $MGArraytmp2["count"]; ?>,'dtanchor1xx<?php echo $MGArraytmp2["count"]; ?>','yyyy-MM-dd'); return false;" name="dtanchor1xx" id="dtanchor1xx<?php echo $MGArraytmp2["count"]; ?>"><img border="0" src="images/calendar.jpg"></a> -->
                <?php if ($MGArraytmp2['po_delivery_dt'] != "") {
                                    echo date("m/d/Y", strtotime($MGArraytmp2['po_delivery_dt']));
                                } ?>
            </td>
            <?php }    ?>

            <?php
                        if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "onroad" || $tablenm == "delivered") {
                        ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArraytmp2['po_delivery_dt'] != "") {
                                    echo date("m/d/Y", strtotime($MGArraytmp2['po_delivery_dt']));
                                } ?>
            </td>
            <?php }    ?>

            <?php
                        if ($tablenm == "customernotready" || $tablenm == "customerready" || $tablenm == "blankopsdeliverydt") {
                        ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtops_delivery_dt"
                    id="txtops_delivery_dt<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['ops_delivery_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtops_delivery_dt<?php echo $MGArraytmp2["count"]; ?>,'dtanchor1xx<?php echo $MGArraytmp2["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor1xx" id="dtanchor1xx<?php echo $MGArraytmp2["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <?php }    ?>
            <?php
                        if ($tablenm == "enterintoTMS" || $tablenm == "tenderlane" || $tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "onroad" || $tablenm == "delivered") {
                        ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['ops_delivery_date']; ?>
            </td>
            <?php }    ?>

            <?php if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated") {    ?>
            <?php if ($tablenm == "bolcreated") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfr_pickup_date"
                    id="txtfr_pickup_date<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['fr_pickup_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfr_pickup_date<?php echo $MGArraytmp2["count"]; ?>,'dtanchor2xx<?php echo $MGArraytmp2["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor2xx" id="dtanchor2xx<?php echo $MGArraytmp2["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfreight_booked_delivery_date"
                    id="txtfreight_booked_delivery_date<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['freight_booked_delivery_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfreight_booked_delivery_date<?php echo $MGArraytmp2["count"]; ?>,'dtanchor3xx<?php echo $MGArraytmp2["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor3xx" id="dtanchor3xx<?php echo $MGArraytmp2["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <?php } ?>
            <?php if ($tablenm == "lanetendered_a") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfr_pickup_date"
                    id="txtfr_pickup_date<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['fr_pickup_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfr_pickup_date<?php echo $MGArraytmp2["count"]; ?>,'dtanchor2xx<?php echo $MGArraytmp2["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor2xx" id="dtanchor2xx<?php echo $MGArraytmp2["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <input type=text name="txtfr_dock_appointment" size="20"
                    value="<?php echo $MGArraytmp2["count"]['fr_dock_appointment']; ?>"
                    id="txtfr_dock_appointment<?php echo $MGArraytmp2["count"]; ?>">
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArraytmp2["customerpickup_ucbdelivering_flg"] == 2) { ?>
                <input type="text" size="8" name="txtfreight_booked_delivery_date"
                    id="txtfreight_booked_delivery_date<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['freight_booked_delivery_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfreight_booked_delivery_date<?php echo $MGArraytmp2["count"]; ?>,'dtanchor3xx<?php echo $MGArraytmp2["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor3xx" id="dtanchor3xx<?php echo $MGArraytmp2["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
                <?php } else {
                                        echo "Customer Pickup";
                                    } ?>
            </td>
            <?php }    ?>
            <?php if ($tablenm == "lanetendered_b") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfr_pickup_date_delivery"
                    id="txtfr_pickup_date_delivery<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['fr_pickup_date_delivery']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfr_pickup_date_delivery<?php echo $MGArraytmp2["count"]; ?>,'dtanchor2xx<?php echo $MGArraytmp2["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor2xx" id="dtanchor2xx<?php echo $MGArraytmp2["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <input type=text name="txtfr_dock_appointment_delivery" size="20"
                    value="<?php echo $MGArraytmp2['fr_dock_appointment_delivery']; ?>"
                    id="txtfr_dock_appointment_delivery<?php echo $MGArraytmp2["count"]; ?>">
            </td>
            <?php } ?>
            <?php }    ?>

            <?php if ($tablenm == "onroad" || $tablenm == "delivered") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['actual_pickup_date']; ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="8" name="txtfreight_booked_delivery_date"
                    id="txtfreight_booked_delivery_date<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['freight_booked_delivery_date']; ?>">
                <a href="#"
                    onclick="cal2xx.select(document.frmnewpage.txtfreight_booked_delivery_date<?php echo $MGArraytmp2["count"]; ?>,'dtanchor3xx<?php echo $MGArraytmp2["count"]; ?>','yyyy-MM-dd'); return false;"
                    name="dtanchor3xx" id="dtanchor3xx<?php echo $MGArraytmp2["count"]; ?>"><img border="0"
                        src="images/calendar.jpg"></a>
            </td>
            <?php }    ?>

            <?php if ($tablenm != "poentered") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo ucfirst($MGArraytmp2['source']); ?>
            </td>
            <?php }    ?>

            <?php if ($tablenm == "requestinvoice" || $tablenm == "requestinvoice_10b" || $tablenm == "qbinvoice" || $tablenm == "awaitingpayment" || $tablenm == "doublechecksforpayroll" || $tablenm == "compdoublechecksforpayroll") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArraytmp2['po_file'] != "") { ?>
                <a href="po/<?php echo $MGArraytmp2['po_file']; ?>" target="_blank" />View</a>
                <?php  } ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArraytmp2['unsign_bol_file'] != "") { ?>
                <a href="bol/<?php echo $MGArraytmp2['unsign_bol_file']; ?>" target="_blank" />View</a>
                <?php  } else { ?>
                <?php echo "No"; ?>
                <?php  } ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['po_term']; ?>
            </td>
            <?php }    ?>

            <?php if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated" || $tablenm == "onroad" || $tablenm == "delivered") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php if ($MGArraytmp2['unsign_bol_file'] != "") { ?>
                <a href="bol/<?php echo $MGArraytmp2['unsign_bol_file']; ?>" target="_blank" />View</a>
                <?php  } else { ?>
                <?php echo "No"; ?>
                <?php  } ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <select name="freight_booking_vendor" id="freight_booking_vendor<?php echo $MGArraytmp2["count"]; ?>"
                    style="width:100px;">
                    <option value="0">Please Select</option>
                    <?php
                                    $fsql = "SELECT id, company_name FROM loop_freightvendor where company_name <> '' ORDER BY company_name";
                                    $fresult = db_query($fsql);
                                    while ($fmyrowsel = array_shift($fresult)) {
                                    ?>
                    <option <?php if ($MGArraytmp2['broker_id'] == $fmyrowsel["id"]) {
                                                    echo " selected ";
                                                } ?> value="<?php echo $fmyrowsel["id"]; ?>">
                        <?php echo $fmyrowsel["company_name"]; ?></option>
                    <?php } ?>
                </select>
            </td>


            <td bgColor="#e4e4e4" class="style12"><?php echo number_format($MGArraytmp2['freight_cost'], 2); ?>"</td>

            <td bgColor="#e4e4e4" class="style12"><?php echo $MGArraytmp2['booked_delivery_cost']; ?></td>

            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="20" name="txt_entinfo_link"
                    id="txt_entinfo_link<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['entinfo_link']; ?>">
                <?php if ($MGArraytmp2['entinfo_link'] != "") { ?>
                <a href="<?php echo $MGArraytmp2['entinfo_link']; ?>" target="_blank" />View</a>
                <?php  } ?>
            </td>
            <?php }    ?>

            <?php if ($tablenm == "awaitingpayment") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo "$" . $MGArraytmp2['invoice_amount']; ?>
            </td>
            <?php
                            if ($MGArraytmp2["invoice_age"] > 30) {
                            ?>
            <td bgColor="#ff0000" class="style12">
                <?php echo $MGArraytmp2["invoice_age"]; ?>
            </td>
            <?php
                                $end_time = $end_time ?? '';
                                $start_t = $start_t ?? '';
                            } elseif (number_format((isset($end_time) - isset($start_t)) / (3600 * 24000), 0) > 10) {

                            ?>
            <td bgColor="#e4e4e4" class="style12">
                &nbsp;
            </td>
            <?php
                            } else {
                            ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2["invoice_age"]; ?>
            </td>
            <?php
                            }
                            ?>
            <?php }    ?>

            <?php if ($tablenm != "poentered") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php echo $MGArraytmp2['rep']; ?>
            </td>
            <?php }    ?>

            <?php if ($tablenm == "enterintoTMS" || $tablenm == "tenderlane") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                $<?php echo number_format($MGArraytmp2['freight_cost'], 2); ?>
            </td>
            <td bgColor="#e4e4e4" class="style12">
                <input type="text" size="20" name="txt_entinfo_link"
                    id="txt_entinfo_link<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['entinfo_link']; ?>">
                <?php if ($MGArraytmp2['entinfo_link'] != "") { ?>
                <a href="<?php echo $MGArraytmp2['entinfo_link']; ?>" target="_blank" />View</a>
                <?php  } ?>
            </td>
            <?php }    ?>
            <?php if ($tablenm == "delivered") { ?>
            <td bgColor="#e4e4e4" class="style12">
                <?php
                                if (isset($b2b_survey_ignore) == 1) {
                                    echo "B2B Survey ignore by " . isset($b2b_survey_ignore_by) . " on " . isset($b2b_survey_ignore_on) . "<br>";
                                } else { ?>

                <form action="bol_confirmshipmentfollowup_mrg.php" method=post>
                    <input type="hidden" name="ID" value="<?php echo $MGArraytmp2["compid"]; ?>">
                    <input type="hidden" name="rec_type" value="Supplier">
                    <input type="hidden" name="rec_id" value="<?php echo $MGArraytmp2['ID']; ?>">
                    <input type="hidden" name="bol_id" value="<?php echo $MGArraytmp2['ID']; ?>">
                    <input type="hidden" name="userinitials" value="<?php echo $_COOKIE["userinitials"]; ?>">
                    <input type="hidden" name="location" value="loopsreceived">
                    <input type="hidden" name="warehouse_id" value="<?php echo $MGArraytmp2['warehouse_id']; ?>">

                    <input type="button" value="B2B Survey" style="cursor:pointer;"
                        id="reminder_popup_set5_btn<?php echo $MGArraytmp2["count"]; ?>"
                        onclick="reminder_popup_set5(<?php echo $MGArraytmp2["compid"]; ?>,<?php echo $MGArraytmp2['ID']; ?>,<?php echo $MGArraytmp2['warehouse_id']; ?>,'Supplier', <?php echo $MGArraytmp2["count"]; ?>)">
                </form>
                <input type="button" name="btnignoreb2bsurvey" id="btnignoreb2bsurvey" value="Ignore B2B Survey"
                    onclick="update_details_b2bsurvey_ignore(<?php echo $MGArray[$count - 1]["count"]; ?>)">
                <?php  } ?>
            </td>
            <?php }    ?>
            <td bgColor="#e4e4e4" class="style12">

                <input type="hidden" name="transid" id="transid<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['ID']; ?>">
                <input type="hidden" name="warehouseid" id="warehouseid<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $MGArraytmp2['warehouse_id']; ?>">
                <input type="hidden" name="tablenm" id="tablenm<?php echo $MGArraytmp2["count"]; ?>"
                    value="<?php echo $tablenm; ?>">

                <?php if ($tablenm == "lanetendered_a" || $tablenm == "lanetendered_b" || $tablenm == "bolcreated") {    ?>
                <?php if ($tablenm == "lanetendered_a") {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details_6(<?php echo $MGArraytmp2["count"]; ?>)">
                <?php } else if ($tablenm == "lanetendered_b") {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details_6_7_b(<?php echo $MGArraytmp2["count"]; ?>)">
                <?php } else if ($tablenm == "bolcreated") {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details_7(<?php echo $MGArraytmp2["count"]; ?>)">
                <?php } ?>

                <?php } else if ($tablenm == "onroad" || $tablenm == "delivered") {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details_8_9(<?php echo $MGArraytmp2["count"]; ?>)">
                <?php } else {    ?>
                <input type="button" name="btnupdate" id="btnupdate" value="Update"
                    onclick="update_details(<?php echo $MGArraytmp2["count"]; ?>)">
                <?php }    ?>
            </td>


            <?php //}	
                        ?>
            <?php if ($tablenm == "pouploaded") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btnmovepending" id="btnmovepending" value="Pending"
                    onclick="update_movepending(<?php echo $MGArraytmp2["count"]; ?>)">
            </td>
            <?php }    ?>
            <?php if ($tablenm == "customernotready") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btnmovepending" id="btnmovepending" value="Pending"
                    onclick="update_movepending_preorder(<?php echo $MGArraytmp2["count"]; ?>)">
            </td>
            <?php }    ?>
            <?php if ($tablenm == "customerready" || $tablenm == "blankopsdeliverydt") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btnmovepending" id="btnmovepending" value="Good to Ship"
                    onclick="update_goodtoship(<?php echo $MGArraytmp2["count"]; ?>)">
            </td>
            <?php }    ?>
            <?php if ($tablenm == "doublechecksforpayroll") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btndoublecheck" id="btndoublecheck" value="Checked"
                    onclick="update_checked(<?php echo $MGArraytmp2["count"]; ?>)">
            </td>
            <?php }    ?>
            <?php if ($tablenm == "compdoublechecksforpayroll") {    ?>
            <td bgColor="#e4e4e4" class="style12">
                <input type="button" name="btndoublecheck_undo" id="btndoublecheck_undo" value="Undo Checked"
                    onclick="update_checked_undo(<?php echo $MGArraytmp2["count"]; ?>)">
            </td>
            <?php }    ?>

        </tr>
        <?php
                } //loop
            }
            ?>

    </table>
    <?php
    }

    ?>
</form>