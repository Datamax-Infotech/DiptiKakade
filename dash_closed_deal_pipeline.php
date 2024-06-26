<?php


require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


$initials = $_REQUEST['initials'];
$dashboard_view = $_REQUEST['dashboard_view'];

$emp_filter = " and loop_transaction_buyer.po_employee = '$initials'";
if ($dashboard_view == "Operations" || $dashboard_view == "Executive") {
    $emp_filter = "";
}

?>

<form name="frmclosedeal" action="dashboardnew_testchk.php" method="post">
    <table cellSpacing="1" cellPadding="1" border="0" width="500">
        <tr>
            <td bgColor='#CFE7FF'>&nbsp;</td>
            <td bgColor='#CFE7FF' align="center">Deals</td>
            <td bgColor='#CFE7FF' align="center">Amount</td>
        </tr>
        <?php
        $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount as SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "WHERE loop_transaction_buyer.shipped = 0 $emp_filter AND loop_transaction_buyer.po_date = '' and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];

                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>PO Not Entered Yet</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdiv1'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdiv1' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT loop_warehouse.warehouse_name, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "WHERE po_sent_to_supplier_flg = 0 $emp_filter and so_entered = 0 and sent_to_supplier = 0 and loop_transaction_buyer.shipped = 0 AND loop_transaction_buyer.po_date <> '' and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 and Preorder = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";

        //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>PO Uploaded, Initial Steps Incomplete</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdiv2'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdiv2' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, loop_warehouse.warehouse_name, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "WHERE loop_transaction_buyer.po_date <> '' $emp_filter and loop_transaction_buyer.ignore = 0 and good_to_ship = 0 and Preorder = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";

        //commented for Pre-paid order as per Zac Team Caht - AND loop_transaction_buyer.no_invoice = 0
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>Customer Not Ready (Pre-Order)</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdiv3'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdiv3' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_employee, ops_delivery_date, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "WHERE so_entered = 1 $emp_filter and loop_transaction_buyer.shipped = 0 AND  loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>Customer Ready, Checking Inventory</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdiv4'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdiv4' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_employee, customerpickup_ucbdelivering_flg, loop_warehouse.warehouse_name, booking_freight_email_ignore, lane_tms_ignore, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "WHERE customerpickup_ucbdelivering_flg = 0 $emp_filter and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight group by trans_rec_id) and so_entered = 1 and loop_transaction_buyer.shipped = 0 AND loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";

        //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>Need to Enter into TMS</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdivn5'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn5' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_employee, ops_delivery_date, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "WHERE customerpickup_ucbdelivering_flg = 2 $emp_filter and (tender_lane_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id)) and so_entered = 1 and loop_transaction_buyer.shipped = 0 AND loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";

        //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>Need to Tender Lane</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdivn6'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn6' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_employee, ops_delivery_date, booked_delivery_cost, loop_warehouse.warehouse_name, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "WHERE customerpickup_ucbdelivering_flg > 0 $emp_filter and (tender_lane_ignore = 1 or loop_transaction_buyer.id in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id)) ";
        $dt_view_qry .= " and ((freight_assign_eml_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_ship_eml_data where freight_assigned_email_flg = 1 group by trans_rec_id)) and (broker_needs_pickup_eml_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_buyer_ship_eml_data where broker_needs_pickup_email_flg = 1 group by trans_rec_id)) ";
        $dt_view_qry .= " and (dock_appt_eml_ignore = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_transaction_freight group by trans_rec_id))) and so_entered = 1 and loop_transaction_buyer.shipped = 0 AND loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.ops_delivery_date";
        //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>Lane Tendered, Set Dock Appointments</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdivn7'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn7' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT loop_freightvendor.company_name as freightbroker, loop_warehouse.warehouse_name, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= " left JOIN loop_transaction_freight ON loop_transaction_buyer.id = loop_transaction_freight.trans_rec_id ";
        $dt_view_qry .= " left JOIN loop_freightvendor ON loop_transaction_freight.broker_id = loop_freightvendor.id ";
        $dt_view_qry .= "WHERE (bol_create = 0 or loop_transaction_buyer.shipped = 0) $emp_filter and so_entered = 1 AND loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_freightvendor.company_name, loop_transaction_freight.date";
        //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0

        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>BOL Needs Created and Shipped</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdivn8'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn8' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT loop_freightvendor.company_name as freightbroker, loop_warehouse.warehouse_name, po_employee, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
        $dt_view_qry .= " right JOIN loop_transaction_freight ON loop_transaction_buyer.id = loop_transaction_freight.trans_rec_id ";
        $dt_view_qry .= " left JOIN loop_freightvendor ON loop_transaction_freight.broker_id = loop_freightvendor.id ";
        $dt_view_qry .= " WHERE loop_bol_files.bol_shipped = 1 $emp_filter and loop_bol_files.bol_shipment_received = 0 and bol_create = 1 and loop_transaction_buyer.shipped = 1 AND loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 GROUP BY loop_transaction_buyer.id ORDER BY loop_freightvendor.company_name, loop_transaction_freight.booked_delivery_date";

        //commented for Pre-paid order as per Zac Team Caht - AND inv_entered = 0 and loop_transaction_buyer.no_invoice = 0
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>On The Road</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdivn9'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn9' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT tender_lane_additional_freight_costs, po_employee, loop_warehouse.warehouse_name, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
        $dt_view_qry .= "WHERE loop_bol_files.bol_shipment_received = 1 $emp_filter and loop_bol_files.bol_shipment_followup = 0 and bol_create = 1 and so_entered = 1 and loop_transaction_buyer.shipped = 1 AND inv_entered = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>Delivered, Needs Survey</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdivn10'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn10' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
        $dt_view_qry .= "WHERE loop_transaction_buyer.recycling_flg = 0 $emp_filter and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and inv_entered = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id not in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#ABC5DF'>Request Invoice</td>
            <td bgColor='#E4EAEB' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#E4EAEB' align="right">
                <a href='#' onclick="load_div('closedealdivn11'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn11' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
        $dt_view_qry .= "WHERE loop_transaction_buyer.inv_amount = 0 $emp_filter and loop_transaction_buyer.shipped = 1 and loop_bol_files.bol_shipment_received = 1 and inv_entered = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 and loop_transaction_buyer.id in (select trans_rec_id from loop_invoice_details) GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $amt = $amt + $dt_view_row["SUMPO"];
            $tot_trans = $tot_trans + 1;

            $nickname = "";
            if ($dt_view_row["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $dt_view_row["warehouse_name"];
            }

            $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#FFF2D0'>Need QB Invoice Uploaded</td>
            <td bgColor='#FFF2D0' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#FFF2D0' align="right">
                <a href='#' onclick="load_div('closedealdivn13'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn13' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
        $dt_view_qry .= "WHERE loop_transaction_buyer.shipped = 1 $emp_filter AND loop_transaction_buyer.inv_amount > 0 and pmt_entered = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.invoice_paid = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $add_in_array = "yes";

            $payment_val = 0;
            $invoice_paid = 0;
            $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["id"];

            db();
            $payment_qry = db_query($payments_sql);
            while ($payment = array_shift($payment_qry)) {
                $payment_val = $payment["A"];
            }
            $payment1 = number_format($dt_view_row["F"], 2);
            $payment2 = number_format($payment_val, 2);
            $payment1 = str_replace(",", "", $payment1);
            $payment2 = str_replace(",", "", $payment2);
            if ($payment1 == $payment2 && $payment1 > 0) {
                $invoice_paid = 1;
            }
            if ($dt_view_row["no_invoice"] == 1) {
                $invoice_paid = 1;
            }

            if ($invoice_paid == 1) {
                $add_in_array = "no";
            }

            //echo "Rec id " .  $dt_view_row["id"] . " " . $payment1 . " " .$payment2 . "<br>";

            if ($add_in_array == "yes") {
                $amt = $amt + $dt_view_row["SUMPO"];
                $tot_trans = $tot_trans + 1;

                $nickname = "";
                if ($dt_view_row["b2bid"] > 0) {
                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                    db_b2b();
                    $result_comp = db_query($sql);
                    while ($row_comp = array_shift($result_comp)) {
                        if ($row_comp["nickname"] != "") {
                            $nickname = $row_comp["nickname"];
                        } else {
                            $tmppos_1 = strpos($row_comp["company"], "-");
                            if ($tmppos_1 != false) {
                                $nickname = $row_comp["company"];
                            } else {
                                if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                    $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                                } else {
                                    $nickname = $row_comp["company"];
                                }
                            }
                        }
                    }
                } else {
                    $nickname = $dt_view_row["warehouse_name"];
                }

                $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
            }
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#FFF2D0'>Awaiting Payment</td>
            <td bgColor='#FFF2D0' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#FFF2D0' align="right">
                <a href='#' onclick="load_div('closedealdivn14'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn14' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_file, ops_delivery_date, loop_warehouse.warehouse_name, po_employee, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "WHERE loop_transaction_buyer.double_checked = 0 $emp_filter and loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $add_in_array = "yes";

            $payment_val = 0;
            $invoice_paid = 0;
            $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["id"];
            db();
            $payment_qry = db_query($payments_sql);
            while ($payment = array_shift($payment_qry)) {
                $payment_val = $payment["A"];
            }
            $payment1 = number_format($dt_view_row["F"], 2);
            $payment2 = number_format($payment_val, 2);
            $payment1 = str_replace(",", "", $payment1);
            $payment2 = str_replace(",", "", $payment2);
            if ($payment1 == $payment2 && $payment1 > 0) {
                $invoice_paid = 1;
            }
            if ($dt_view_row["no_invoice"] == 1) {
                $invoice_paid = 1;
            }

            if ($invoice_paid == 1) {
                $add_in_array = "yes";
            } else {
                $add_in_array = "no";
            }

            //echo "Rec id " .  $dt_view_row["id"] . " " . $payment1 . " " .$payment2 . "<br>";

            if ($add_in_array == "yes") {
                $amt = $amt + $dt_view_row["SUMPO"];
                $tot_trans = $tot_trans + 1;

                $nickname = "";
                if ($dt_view_row["b2bid"] > 0) {
                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                    db_b2b();
                    $result_comp = db_query($sql);
                    while ($row_comp = array_shift($result_comp)) {
                        if ($row_comp["nickname"] != "") {
                            $nickname = $row_comp["nickname"];
                        } else {
                            $tmppos_1 = strpos($row_comp["company"], "-");
                            if ($tmppos_1 != false) {
                                $nickname = $row_comp["company"];
                            } else {
                                if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                    $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                                } else {
                                    $nickname = $row_comp["company"];
                                }
                            }
                        }
                    }
                } else {
                    $nickname = $dt_view_row["warehouse_name"];
                }

                $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
            }
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#FFF2D0'>Double Checks for Payroll</td>
            <td bgColor='#FFF2D0' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#FFF2D0' align="right">
                <a href='#' onclick="load_div('closedealdivn15'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn15' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>

        <?php
        $dt_view_qry = "SELECT po_file, ops_delivery_date, po_employee, loop_warehouse.warehouse_name, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount AS SUMPO, loop_transaction_buyer.po_freight, loop_transaction_buyer.virtual_inventory_company_id, loop_transaction_buyer.good_to_ship_action_dt, loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery, ";
        $dt_view_qry .= "loop_transaction_buyer.po_delivery_dt, loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered , ";
        $dt_view_qry .= "loop_transaction_buyer.good_to_ship AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
        $dt_view_qry .= "WHERE loop_transaction_buyer.double_checked = 1 $emp_filter and loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 0 AND loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.no_invoice = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        db();
        $dt_view_res = db_query($dt_view_qry);
        $amt = 0;
        $tot_trans = 0;

        $lisofdetails = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        while ($dt_view_row = array_shift($dt_view_res)) {
            $add_in_array = "yes";

            $payment_val = 0;
            $invoice_paid = 0;
            $payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["id"];
            db();
            $payment_qry = db_query($payments_sql);
            while ($payment = array_shift($payment_qry)) {
                $payment_val = $payment["A"];
            }
            $payment1 = number_format($dt_view_row["F"], 2);
            $payment2 = number_format($payment_val, 2);
            $payment1 = str_replace(",", "", $payment1);
            $payment2 = str_replace(",", "", $payment2);
            if ($payment1 == $payment2 && $payment1 > 0) {
                $invoice_paid = 1;
            }
            if ($dt_view_row["no_invoice"] == 1) {
                $invoice_paid = 1;
            }

            if ($invoice_paid == 1) {
                $add_in_array = "yes";
            } else {
                $add_in_array = "no";
            }

            //echo "Rec id " .  $dt_view_row["id"] . " " . $payment1 . " " .$payment2 . "<br>";

            if ($add_in_array == "yes") {
                $amt = $amt + $dt_view_row["SUMPO"];
                $tot_trans = $tot_trans + 1;

                $nickname = "";
                if ($dt_view_row["b2bid"] > 0) {
                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $dt_view_row["b2bid"];
                    db_b2b();
                    $result_comp = db_query($sql);
                    while ($row_comp = array_shift($result_comp)) {
                        if ($row_comp["nickname"] != "") {
                            $nickname = $row_comp["nickname"];
                        } else {
                            $tmppos_1 = strpos($row_comp["company"], "-");
                            if ($tmppos_1 != false) {
                                $nickname = $row_comp["company"];
                            } else {
                                if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                    $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                                } else {
                                    $nickname = $row_comp["company"];
                                }
                            }
                        }
                    }
                } else {
                    $nickname = $dt_view_row["warehouse_name"];
                }

                $lisofdetails .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_view'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($dt_view_row["SUMPO"], 0) . "</td></tr>";
            }
        }
        if ($amt > 0) {
            $lisofdetails .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt, 0) . "</td></tr>";
        }
        $lisofdetails .= "</table></span>";

        $amt = number_format($amt, 0);
        ?>
        <tr>
            <td bgColor='#FFF2D0'>Completed Double Checks for Payroll</td>
            <td bgColor='#FFF2D0' align="right">
                <font size="2"><?php echo $tot_trans; ?></font>
            </td>
            <td bgColor='#FFF2D0' align="right">
                <a href='#' onclick="load_div('closedealdivn16'); return false;">$<?php echo $amt; ?></font></a>
                <span id='closedealdivn16' style='display:none;'><a href='#'
                        onclick="close_div(); return false;">Close</a><?php echo $lisofdetails; ?></span>
            </td>
        </tr>
    </table>
</form>

<br /><br />

<?
//for the B2b op team calc
function leadertbl($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid, $displaytxt)
{

    $tot_quota = 0;
    $tot_quotaytd = 0;
    $tot_quotaactual = 0;
    $tot_quota_mtd = 0;
    $tot_quota_deal_mtd = 0;
    $tot_quotaactual_mtd = 0;
    $quota_one_day = 0;
    $lisoftrans_tot = "";

    $dt_year_value = date('Y', strtotime($start_Dt));
    $dt_month_value = date('m', strtotime($start_Dt));
    $current_year_value = date('Y');

    $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));
    $sql = "SELECT * FROM loop_employees WHERE quota > 0 and leaderboard = 1 ORDER BY quota DESC";

    db();
    $result = db_query($sql);
    while ($rowemp = array_shift($result)) {
        $quota = 0;
        $quotadate = "";
        $deal_quota = 0;
        $monthly_qtd = 0;
        db();
        $result_empq = db_query("Select quota_year, quota_month from employee_quota where emp_id = " . $rowemp["id"] . " order by quota_year, quota_month limit 1");
        while ($rowemp_empq = array_shift($result_empq)) {
            $quotadate = date($rowemp_empq["quota_year"] . "-" . str_pad($rowemp_empq["quota_month"], 2, "0", STR_PAD_LEFT) . "-01");
        }

        //echo "<br>headtxt: " . $headtxt  . "<br>";
        $quota_days_TD = 0;
        if ($start_Dt > $quotadate) {
            $quota_days_TD = 1 + floor((strtotime($end_Dt) - strtotime($start_Dt)) / (60 * 60 * 24));
        } else {
            $quota_days_TD = 1 + floor((strtotime($end_Dt) - strtotime($quotadate)) / (60 * 60 * 24));
        }
        if ($tilltoday == "Y") {
            if ($start_Dt > $quotadate) {
                $dim = 1 + floor((strtotime(Date('Y-m-d')) - strtotime($start_Dt)) / (60 * 60 * 24));
            } else {
                $dim = 1 + floor((strtotime(Date('Y-m-d')) - strtotime($quotadate)) / (60 * 60 * 24));
            }
        } else {
            $dim = 1 + floor((strtotime($end_Dt) - strtotime($start_Dt)) / (60 * 60 * 24));
        }

        if ($headtxt == "B2B Leaderboard") {
            $begin = new DateTime($start_Dt);
            $end   = new DateTime($end_Dt);
            $quota = 0;
            for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
                $start_Dt_tmp = $datecnt->format("Y-m-d");
                $quota_mtd = 0;
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
                db();
                $result_empq = db_query($newsel);
                while ($rowemp_empq = array_shift($result_empq)) {
                    $quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
                    $quota = $quota + $quota_one_day;
                }
            }
            $monthly_qtd = $quota;
        }

        if (
            $headtxt == "TODAY" || $headtxt == "YESTERDAY" || $headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR"
            || $headtxt == "THIS MONTH" || $headtxt == "LAST MONTH" || $headtxt == "THIS MONTH LAST YEAR"
        ) {

            db();
            $result_empq = db_query("Select sum(quota) as sumquota, sum(deal_quota) as deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value);
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = $rowemp_empq["sumquota"];
                //$quotadate = $rowemp_empq["quota_date"];
                $deal_quota = $rowemp_empq["deal_quota"];
            }

            if ($headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
                if ($start_Dt > $quotadate) {
                    $dim = 1 + floor((strtotime(Date($dt_year_value . '-m-d')) - strtotime($start_Dt)) / (60 * 60 * 24));
                } else {
                    $dim = 1 + floor((strtotime(Date($dt_year_value . '-m-d')) - strtotime($quotadate)) / (60 * 60 * 24));
                }
            }
            $st_date_t = Date('Y-m-01', strtotime($start_Dt));
            $end_date_t = Date('Y-m-t', strtotime($start_Dt));

            if ($headtxt == "THIS MONTH LAST YEAR") {
                $dim = 1 + floor((strtotime(Date($dt_year_value . '-m-d')) - strtotime($start_Dt)) / (60 * 60 * 24));

                $st_date_t = Date($dt_year_value . '-m-01', strtotime($start_Dt));
                $end_date_t = Date($dt_year_value . '-m-t', strtotime($start_Dt));

                if ($st_date_t > $quotadate) {
                    $quota_days_TD = 1 + floor((strtotime($end_date_t) - strtotime($st_date_t)) / (60 * 60 * 24));
                } else {
                    $quota_days_TD = 1 + floor((strtotime($end_date_t) - strtotime($quotadate)) / (60 * 60 * 24));
                }
            }
            $quota_days = 1 +  floor((strtotime($end_date_t) - strtotime($st_date_t)) / (60 * 60 * 24));
            $quota_one_day = $quota / $quota_days;

            $quota_in_st_en = $quota_one_day * $quota_days_TD;
            $quota_days = date("t", strtotime($start_Dt));
            //$monthly_qtd = $quota*$dim/$quota_days;

            if ($headtxt == "TODAY" || $headtxt == "THIS WEEK" || $headtxt == "THIS MONTH") {
                $monthly_qtd = (date("d") * $quota) / date("t");
            } else {
                $monthly_qtd = $quota * $dim / $quota_days;
            }
            //echo "test : " . $headtxt. " " . $start_Dt . " " . $quotadate . " " . $end_Dt. " " . $st_date_t . " " . $end_date_t . " " . $quota_days . " " . $quota_one_day . " " . $quota_days_TD . " " . $dim. "<br>";
            //echo "test : " . $headtxt. " " . $start_Dt . " " . date("d") . " " . $end_Dt. " " . date("t") . " " . $end_date_t . " " . $quota	 . " " . $quota_one_day . " " . $quota_days_TD . " " . $dim. "<br>";
        }

        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") {
            $begin = new DateTime($start_Dt);
            $end   = new DateTime($end_Dt);
            $currentdate  = new DateTime(date("Y-m-d"));
            $quota = 0;
            $quota_to_date = 0;
            for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
                $start_Dt_tmp = $datecnt->format("Y-m-d");
                $quota_mtd = 0;
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
                db();
                $result_empq = db_query($newsel);
                while ($rowemp_empq = array_shift($result_empq)) {
                    $quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
                    $quota = $quota + $quota_one_day;
                    if ($datecnt <= $currentdate) {
                        $quota_to_date = $quota_to_date + $quota_one_day;
                    }
                }
            }
            $quota_in_st_en = $quota;
            $monthly_qtd = $quota_to_date;
        }

        if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
            $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
            if ($current_qtr == 1) {

                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {

                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {

                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {

                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
                }
            }
            $quota_mtd = 0;
            $donot_add = "";
            $days_in_month = 30;
            $dt_month_value_1 = date('m');
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = $quota + $rowemp_empq["quota"];
                //$deal_quota = $rowemp_empq["deal_quota"];
                if ($headtxt == "THIS QUARTER") {
                    $todays_dt = date('m/d/Y');
                    $days_today = 1 + dateDiff($todays_dt, date('Y-m-01'));
                    $days_in_month = 1 + dateDiff(date('Y-m-t'), date('Y-m-01'));
                }
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $todays_dt = date($dt_year_value . "-m-d");
                    $days_today = 1 + dateDiff($todays_dt, date($dt_year_value . "-m-01"));
                    $days_in_month = 1 + dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01'));
                }
                if ($headtxt == "LAST QUARTER") {
                    $days_today = 91;
                }
                if ($donot_add == "") {
                    if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                        if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                            $donot_add = "yes";
                            $monthly_qtd_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

                            $quota_mtd = $quota_mtd + $monthly_qtd_1;
                        } else {
                            $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                        }
                    }
                }
            }

            $quota_in_st_en = $quota;
            if ($headtxt == "LAST QUARTER") {
                $monthly_qtd = ($days_today * $quota) / 91;
            } else {
                $monthly_qtd = $quota_mtd;
            }
        }

        if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
            $quota_mtd = 0;
            $donot_add = "";
            $days_in_month = 0;
            $dt_month_value_1 = date('m');

            db();
            $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = $quota + $rowemp_empq["quota"];
                //$deal_quota = $rowemp_empq["deal_quota"];

                if ($headtxt == "THIS YEAR") {
                    $todays_dt = date('m/d/Y');
                    $days_today = 1 + dateDiff($todays_dt, date('Y-m-01'));
                    $days_in_month = 1 + dateDiff(date('Y-m-t'), date('Y-m-01'));
                }
                if ($headtxt == "LAST TO LAST YEAR") {
                    $todays_dt = date($dt_year_value . "-m-d");
                    $days_today = 1 + dateDiff($todays_dt, date($dt_year_value . "-m-01"));
                    $days_in_month = 1 + dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01'));
                }
                if ($headtxt == "LAST YEAR") {
                    $days_today = 365;
                }
                if ($donot_add == "") {
                    if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                        if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                            $donot_add = "yes";
                            $monthly_qtd_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

                            $quota_mtd = $quota_mtd + $monthly_qtd_1;
                        } else {
                            $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                        }
                    }
                }
            }
            $quota_in_st_en = $quota;

            if ($headtxt == "LAST YEAR") {
                $monthly_qtd = ($days_today * $quota) / 365;
            } else {
                $monthly_qtd = $quota_mtd;
            }
        }

        $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        if ($po_flg == "yes") {
            $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        } else {
            $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
        }
        if ($tilltoday == "Y") {
            //$sqlmtd = "SELECT SUM(inv_amount) AS SUMPO, count(inv_amount) as dealcnt  FROM loop_transaction_buyer WHERE inv_amount > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . $start_Dt . "'  AND SYSDATE()";
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "'  AND '" . $end_Dt . " 23:59:59'";
        } else {
            //$sqlmtd = "SELECT SUM(inv_amount) AS SUMPO, count(inv_amount) as dealcnt FROM loop_transaction_buyer WHERE inv_amount > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . $start_Dt . "'  AND '" . $end_Dt . " 23:59:59'";
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "'  AND '" . $end_Dt . " 23:59:59'";
        }

        if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
            $sqlmtd = "SELECT po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        }

        if ($headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "LAST TO LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
            if ($headtxt == "LAST TO LAST YEAR") {
                $dt_year_value_1 = $dt_year_value;
                $end_Dtn = Date($dt_year_value_1 . '-12-31');
                $start_Dtn = Date($dt_year_value_1 . '-01-01');
            } else {
                $start_Dtn = $start_Dt;
                $end_Dtn = Date($dt_year_value . '-m-d');
            }
            //$sqlmtd = "SELECT SUM(inv_amount) AS SUMPO, count(inv_amount) as dealcnt FROM loop_transaction_buyer WHERE inv_amount > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND STR_TO_DATE(inv_date_of, '%m/%d/%Y') BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
        }
        //echo "Sql Qry " . $sqlmtd . "<br>";
        db();
        $resultmtd = db_query($sqlmtd);
        $summtd_SUMPO = 0;
        $summtd_dealcnt = 0;
        while ($summtd = array_shift($resultmtd)) {
            //if ($summtd["SUMPO"] > 0) {
            //	$summtd_SUMPO = $summtd["SUMPO"];
            //}
            //$summtd_dealcnt = $summtd["dealcnt"];
            $nickname = "";
            if ($summtd["b2bid"] > 0) {
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $summtd["b2bid"];
                db_b2b();
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
            } else {
                $nickname = $summtd["warehouse_name"];
            }

            $finalpaid_amt = 0;

            db();
            $result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]);
            while ($summtd_finalpmt = array_shift($result_finalpmt)) {
                $finalpaid_amt = $summtd_finalpmt["amt"];
            }

            $inv_amt_totake = 0;
            if ($finalpaid_amt > 0) {
                $inv_amt_totake = $finalpaid_amt;
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                $inv_amt_totake = $summtd["invsent_amt"];
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                $inv_amt_totake = $summtd["inv_amount"];
            }

            $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
            $summtd_dealcnt = $summtd_dealcnt + 1;
            if ($po_flg == "yes") {
                $lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";
            } else {
                $lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
            }
        }
        if ($summtd_SUMPO > 0) {
            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
        }
        $lisoftrans .= "</table></span>";

        $rev_lastyr_tilldt = 0;
        if ($headtxt == "LAST YEAR") {
            $dt_year_value_1 = date('Y') - 1;
            $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
            $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');
            //$sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND loop_invoice_details.timestamp BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
            db();
            $resultmtd = db_query($sqlmtd);
            while ($summtd = array_shift($resultmtd)) {
                //if ($summtd["SUMPO"] > 0) {
                //$rev_lastyr_tilldt = $summtd["SUMPO"];
                //}

                $finalpaid_amt = 0;
                db();
                $result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]);
                while ($summtd_finalpmt = array_shift($result_finalpmt)) {
                    $finalpaid_amt = $summtd_finalpmt["amt"];
                }

                $inv_amt_totake = 0;

                if ($finalpaid_amt > 0) {
                    $inv_amt_totake = $finalpaid_amt;
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                    $inv_amt_totake = $summtd["invsent_amt"];
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                    $inv_amt_totake = $summtd["inv_amount"];
                }

                $rev_lastyr_tilldt = $rev_lastyr_tilldt + $inv_amt_totake;
            }
        }

        if ($headtxt == "LAST TO LAST YEAR") {
            $monthly_qtd = $quota_in_st_en;
        }
        if ($summtd_SUMPO >= $monthly_qtd) {
            $color = "green";
        } elseif ($summtd_SUMPO < $monthly_qtd && $summtd_SUMPO > 0) {
            $color = "red";
        } else {
            $color = "black";
        };
        if ($monthly_qtd == 0) {
            $color = "black";
        }
        $MGArray[] = array(
            'name' => $rowemp["name"], 'name_initial' => $rowemp["initials"], 'empid' => $rowemp["id"], 'start_Dt' => $start_Dt, 'end_Dt' => $end_Dt, 'deal_count' => $summtd_dealcnt, 'quota' => $quota_in_st_en,
            'quotatodate' => $monthly_qtd, 'po_entered' => $summtd_SUMPO, 'percent_val' => $color, 'rev_lastyr_tilldt' => $rev_lastyr_tilldt, 'lisoftrans' => $lisoftrans
        );
    }

    $_SESSION['sortarrayn'] = $MGArray;

    $sort_order_pre = "ASC";
    if ($_POST['sort_order_pre'] == "ASC") {
        $sort_order_pre = "DESC";
    } else {
        $sort_order_pre = "ASC";
    }


    //$monthly_qtd = number_format($monthly_qtd,0);
    //$summtd_SUMPO = number_format($summtd_SUMPO,0);
    $monthly_deal_qtd = $monthly_deal_qtd ?? '';
    $monthly_deal_qtd = number_format($monthly_deal_qtd, 0);

    $tot_quota_mtd = $tot_quota_mtd + $monthly_qtd;
    $tot_quota_mtd_TD = isset($tot_quota_mtd_TD) + isset($monthly_qtd_TD);
    $tot_quotaactual_mtd = $tot_quotaactual_mtd + $summtd_SUMPO;
    $tot_quota_deal_mtd = $tot_quota_deal_mtd + $monthly_deal_qtd;
    $tot_rev_lastyr_tilldt = isset($tot_rev_lastyr_tilldt) + isset($MGArraytmp2["rev_lastyr_tilldt"]);

    $tot_monthper = 100 * $tot_quotaactual_mtd / $tot_quota_mtd;
    if ($tot_monthper >= 100) {
        $color = "green";
    } elseif ($tot_monthper >= 80) {
        $color = "red";
    } else {
        $color = "black";
    };

    $quota_ov = 0;
    if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
        $sql_ovdata = "SELECT quota FROM employee_quota_overall WHERE b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";

        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov = $rowemp_ovdata["quota"];
        }
    }

    if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") {
        $begin = new DateTime($start_Dt);
        $end   = new DateTime($end_Dt);
        $currentdate  = new DateTime(date("Y-m-d"));
        $quota = 0;
        $quota_to_date = 0;
        for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
            $start_Dt_tmp = $datecnt->format("Y-m-d");
            $quota_mtd = 0;
            $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
            db();
            $result_empq = db_query($newsel);
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
                $quota = $quota + $quota_one_day;
                //echo "week quota: " . $quota . "<br>";
            }
        }
        $quota_ov = $quota;
    }

    if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
        $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
        db();
        if ($current_qtr == 1) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
        }
        if ($current_qtr == 2) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
        }
        if ($current_qtr == 3) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
        }
        if ($current_qtr == 4) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
        }
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 30;
        $quota = 0;
        $dt_month_value_1 = date('m');
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = $quota + $rowemp_empq["quota"];
        }

        $quota_ov = $quota;
    }

    if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
        $sql_ovdata = "SELECT quota FROM employee_quota_overall WHERE b2borb2c = 'b2b' and quota_year = " . $dt_year_value;
        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov = $quota_ov + $rowemp_ovdata["quota"];
        }
    }

    //for the B2b op team calc
    $quota_days_TD = 0;
    $quota_days_TD = 1 + floor((strtotime($end_Dt) - strtotime($start_Dt)) / (60 * 60 * 24));
    if ($tilltoday == "Y") {
        $dim = 1 + floor((strtotime(Date('Y-m-d')) - strtotime($start_Dt)) / (60 * 60 * 24));
    } else {
        $dim = 1 + floor((strtotime($end_Dt) - strtotime($start_Dt)) / (60 * 60 * 24));
    }

    if ($headtxt == "B2B Leaderboard") {
        $begin = new DateTime($start_Dt);
        $end   = new DateTime($end_Dt);
        $quota = 0;
        for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
            $start_Dt_tmp = $datecnt->format("Y-m-d");
            $quota_one_day = $quota_ov / date('t', strtotime($start_Dt_tmp));
            $quota = $quota + $quota_one_day;
        }
        $monthly_qtd = $quota;
    }

    if (
        $headtxt == "TODAY" || $headtxt == "YESTERDAY" || $headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR"
        || $headtxt == "THIS MONTH" || $headtxt == "LAST MONTH" || $headtxt == "THIS MONTH LAST YEAR"
    ) {
        $quota = $quota_ov;

        if ($headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
            $dim = 1 + floor((strtotime(Date($dt_year_value . '-m-d')) - strtotime($start_Dt)) / (60 * 60 * 24));
        }
        $st_date_t = Date('Y-m-01', strtotime($start_Dt));
        $end_date_t = Date('Y-m-t', strtotime($start_Dt));

        if ($headtxt == "THIS MONTH LAST YEAR") {
            $dim = 1 + floor((strtotime(Date($dt_year_value . '-m-d')) - strtotime($start_Dt)) / (60 * 60 * 24));

            $st_date_t = Date($dt_year_value . '-m-01', strtotime($start_Dt));
            $end_date_t = Date($dt_year_value . '-m-t', strtotime($start_Dt));

            $quota_days_TD = 1 + floor((strtotime($end_date_t) - strtotime($st_date_t)) / (60 * 60 * 24));
        }
        $quota_days = 1 +  floor((strtotime($end_date_t) - strtotime($st_date_t)) / (60 * 60 * 24));
        $quota_one_day = $quota / $quota_days;

        $quota_in_st_en = $quota_one_day * $quota_days_TD;
        $quota_days = date("t", strtotime($start_Dt));
        //$monthly_qtd = $quota*$dim/$quota_days;

        if ($headtxt == "TODAY" || $headtxt == "THIS WEEK" || $headtxt == "THIS MONTH") {
            $monthly_qtd = (date("d") * $quota) / date("t");
        } else {
            $monthly_qtd = $quota * $dim / $quota_days;
        }
    }

    if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") {
        $begin = new DateTime($start_Dt);
        $end   = new DateTime($end_Dt);
        $currentdate  = new DateTime(date("Y-m-d"));
        $quota = 0;
        $quota_to_date = 0;
        $days_today = 0;
        for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
            $start_Dt_tmp = $datecnt->format("Y-m-d");

            $quota_one_day = $quota_ov * 1 / 7;
            $quota = $quota + $quota_one_day;
            if ($datecnt <= $currentdate) {
                //echo $quota_ov . " " . date('t', strtotime($start_Dt_tmp)) . " " . $quota_one_day . "<br>";
                $quota_to_date = $quota_to_date + $quota_one_day;
            }
        }

        $monthly_qtd = $quota_to_date;
    }

    if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
        $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 30;
        $dt_month_value_1 = date('m');

        if ($current_qtr == 1) {

            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
            }
        }
        if ($current_qtr == 2) {

            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
            }
        }
        if ($current_qtr == 3) {

            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
            }
        }
        if ($current_qtr == 4) {

            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
            }
        }


        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = $quota + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];
            if ($headtxt == "THIS QUARTER") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + dateDiff($todays_dt, date('Y-m-01'));
                $days_in_month = 1 + dateDiff(date('Y-m-t'), date('Y-m-01'));
            }
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + dateDiff($todays_dt, date($dt_year_value . "-m-01"));
                $days_in_month = 1 + dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01'));
            }
            if ($headtxt == "LAST QUARTER") {
                $days_today = 91;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }

        $quota_in_st_en = $quota;
        if ($headtxt == "LAST QUARTER") {
            $monthly_qtd = ($days_today * $quota) / 91;
        } else {
            $monthly_qtd = $quota_mtd;
        }
    }



    if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 0;
        $dt_month_value_1 = date('m');
        db();
        $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_overall where b2borb2c = 'b2b' and quota_year = " . $dt_year_value . " order by quota_month");
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = $quota + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];

            if ($headtxt == "THIS YEAR") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + dateDiff($todays_dt, date('Y-m-01'));
                $days_in_month = 1 + dateDiff(date('Y-m-t'), date('Y-m-01'));
            }
            if ($headtxt == "LAST TO LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + dateDiff($todays_dt, date($dt_year_value . "-m-01"));
                $days_in_month = 1 + dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01'));
            }
            if ($headtxt == "LAST YEAR") {
                $days_today = 365;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }
        $quota_in_st_en = $quota;

        if ($headtxt == "LAST YEAR") {
            $monthly_qtd = ($days_today * $quota) / 365;
        } else {
            $monthly_qtd = $quota_mtd;
        }
    }

    $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
    if ($po_flg == "yes") {
        $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
    } else {
        $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
    }
    if ($tilltoday == "Y") {
        $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "'  AND '" . $end_Dt . " 23:59:59'";
    } else {
        $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "'  AND '" . $end_Dt . " 23:59:59'";
    }

    if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
        $sqlmtd = "SELECT po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
    }

    if ($headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "LAST TO LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
        if ($headtxt == "LAST TO LAST YEAR") {
            $dt_year_value_1 = $dt_year_value;
            $end_Dtn = Date($dt_year_value_1 . '-12-31');
            $start_Dtn = Date($dt_year_value_1 . '-01-01');
        } else {
            $start_Dtn = $start_Dt;
            $end_Dtn = Date($dt_year_value . '-m-d');
        }
        $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
    }
    if ($headtxt == "THIS YEAR") {
        //echo $sqlmtd . "<br>";
    }

    db();
    $resultmtd = db_query($sqlmtd);
    $summtd_SUMPO = 0;
    $summtd_dealcnt = 0;
    while ($summtd = array_shift($resultmtd)) {
        $nickname = "";
        if ($summtd["b2bid"] > 0) {
            $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $summtd["b2bid"];
            db_b2b();
            $result_comp = db_query($sql);
            while ($row_comp = array_shift($result_comp)) {
                if ($row_comp["nickname"] != "") {
                    $nickname = $row_comp["nickname"];
                } else {
                    $tmppos_1 = strpos($row_comp["company"], "-");
                    if ($tmppos_1 != false) {
                        $nickname = $row_comp["company"];
                    } else {
                        if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                            $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                        } else {
                            $nickname = $row_comp["company"];
                        }
                    }
                }
            }
        } else {
            $nickname = $summtd["warehouse_name"];
        }

        $finalpaid_amt = 0;

        db();
        $result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]);
        while ($summtd_finalpmt = array_shift($result_finalpmt)) {
            $finalpaid_amt = $summtd_finalpmt["amt"];
        }

        $inv_amt_totake = 0;


        if ($finalpaid_amt > 0) {
            $inv_amt_totake = $finalpaid_amt;
        }
        if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
            $inv_amt_totake = $summtd["invsent_amt"];
        }
        if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
            $inv_amt_totake = $summtd["inv_amount"];
        }

        $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;
        $summtd_dealcnt = $summtd_dealcnt + 1;
        if ($po_flg == "yes") {
            $lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";
        } else {
            $lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
        }
    }
    if ($summtd_SUMPO > 0) {
        $tot_quotaactual_mtd = $summtd_SUMPO;
        $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
    }
    $lisoftrans .= "</table></span>";

    $rev_lastyr_tilldt = 0;
    if ($headtxt == "LAST YEAR") {
        $dt_year_value_1 = date('Y') - 1;
        $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
        $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');
        $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
        db();
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $finalpaid_amt = 0;
            db();
            $result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]);
            while ($summtd_finalpmt = array_shift($result_finalpmt)) {
                $finalpaid_amt = $summtd_finalpmt["amt"];
            }

            $inv_amt_totake = 0;


            if ($finalpaid_amt > 0) {
                $inv_amt_totake = $finalpaid_amt;
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                $inv_amt_totake = $summtd["invsent_amt"];
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                $inv_amt_totake = $summtd["inv_amount"];
            }

            $rev_lastyr_tilldt = $rev_lastyr_tilldt + $inv_amt_totake;
        }
        //$tot_quotaactual_mtd = $rev_lastyr_tilldt;
    }

    if ($headtxt == "LAST TO LAST YEAR") {
        //$monthly_qtd = $quota_in_st_en;
    }

    //for the B2b op team calc
    echo "<tr><td bgColor='$tbl_color' align = 'right'>$displaytxt</td>";
    echo "<td bgColor='$tbl_color' align = 'right'>";
    if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
        echo "$" . number_format($quota_ov, 0);
        echo "</td><td bgColor='$tbl_color' align = 'right'>";
        echo "$" . number_format($monthly_qtd, 0);
        if ($tot_quotaactual_mtd >= $monthly_qtd) {
            $color = "green";
        } elseif ($tot_quotaactual_mtd < $monthly_qtd && $tot_quotaactual_mtd > 0) {
            $color = "red";
        } else {
            $color = "black";
        };
    } else {
        if ($tot_quotaactual_mtd >= $quota_ov) {
            $color = "green";
        } elseif ($tot_quotaactual_mtd < $quota_ov && $tot_quotaactual_mtd > 0) {
            $color = "red";
        } else {
            $color = "black";
        };
        echo "$" . number_format($quota_ov, 0);
    }
    echo "</strong></td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

    echo "<a href='#' onclick='load_div(99" . $unqid . "); return false;'><font color='" . $color . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
    echo "<span id='99" . $unqid . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans . "</span>";

    echo "</font></td>";
    if ($headtxt == "LAST YEAR") {
        echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($rev_lastyr_tilldt, 0);
        echo "</font></td>";
    }
    echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";
    if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
        echo number_format($tot_quotaactual_mtd * 100 / $quota_ov, 2);
    } else {
        //echo number_format($tot_quotaactual_mtd*100/$tot_quota_mtd_TD,2);
        echo number_format($tot_quotaactual_mtd * 100 / $monthly_qtd, 2);
    }
    echo "%</font></td>";
    if ($tot_quotaactual_mtd >= $quota_ov) {
        $newcolor = 'green';
    } else {
        $newcolor = 'red';
    }
    echo "<td bgColor='$tbl_color' align = right style='font-size:10pt;'><font color=" . $newcolor . ">$" . number_format($tot_quotaactual_mtd - $quota_ov, 0) . "</font></td>";

    echo "</tr>";
}
?>