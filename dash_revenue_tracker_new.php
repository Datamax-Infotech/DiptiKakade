<style>
a {
    font-size: 10pt !important
}
</style>
<?php


require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

$result_empq = array();
$initials = $_REQUEST['initials'];
$dashboard_view = $_REQUEST['dashboard_view'];

$emp_filter = " and loop_transaction_buyer.po_employee = '$initials'";
if ($dashboard_view == "Operations" || $dashboard_view == "Executive") {
    $emp_filter = "";
}


function leadertbl(
    string $start_Dt,
    string $end_Dt,
    string $headtxt,
    string $tilltoday,
    int $currentyr,
    string $tbl_head_color,
    string $tbl_color,
    string $po_flg,
    string $unqid,
    string $ttylyesno,
    string $coltxt,
    string $empfilter,
    string $activity_tracker_flg = "no"
): void {

    db();

    $lisoftrans_detail_list = "<span style='width:1300px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'><tr style='height:50px;'>";
    $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Transaction ID</u></th>";
    $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='200px'><u>Company</u></th>";
    $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>PO Date</u></th>";
    $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Employee</u></th>";
    $lisoftrans_detail_list .= "<th width='100px' bgColor='$tbl_head_color' align=center><u>PO Amount</u></th>";
    $lisoftrans_detail_list .= "</tr>";

    $tot_quota = 0;
    $tot_quotaytd = 0;
    $tot_quotaactual = 0;
    $tot_quota_mtd = 0;
    $tot_quota_deal_mtd = 0;
    $tot_quotaactual_mtd = 0;
    $tot_summtd_ttly = 0;
    $quota_one_day = 0;
    $lisoftrans_tot = "";

    $dt_year_value = date('Y', strtotime($start_Dt));
    $dt_month_value = date('m', strtotime($start_Dt));
    $current_year_value = date('Y');

    $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));
    $dashboard_view = 'Sales';

    if ($empfilter == "All") {
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard, dashboard_view FROM loop_employees WHERE  activity_tracker_flg = 1 and status = 'Active'";
    } else {
        //(leaderboard = 1 or purchasing_leaderboard = 1) and
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard, purchasing_leaderboard, dashboard_view FROM loop_employees WHERE initials = '" . $empfilter . "'";
    }

    $emp_initials_list = '';
    $emp_b2bid_list = '';
    $emp_id_list = '';
    $emp_eml_list = '';
    $result = db_query($sql);
    while ($rowemp = array_shift($result)) {
        $emp_initials_list .= "'" . $rowemp["initials"] . "',";
        $emp_b2bid_list .= "'" . $rowemp["b2b_id"] . "',";
        $emp_id_list .= "'" . $rowemp["id"] . "',";
        $emp_eml_list .= "'" . $rowemp["email"] . "',";
        $emp_purchasing = $rowemp["purchasing_leaderboard"] . ",";
    }
    $emp_initials_list = rtrim($emp_initials_list, ",");
    $emp_b2bid_list = rtrim($emp_b2bid_list, ",");
    $emp_id_list = rtrim($emp_id_list, ",");
    $emp_eml_list = rtrim($emp_eml_list, ",");
    $emp_purchasing = $emp_purchasing ?? 0;
    $emp_purchasing = rtrim($emp_purchasing, ",");

    if ($empfilter == "All") {
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees WHERE  activity_tracker_flg = 1 and status = 'Active'";
    } else {
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE initials = '" . $empfilter . "'";
    }
    //quota > 0 and 
    //echo $sql . "<br>";

    $result = db_query($sql);
    while ($rowemp = array_shift($result)) {
        $lisoftrans_for_total = "";
        $quota = 0;
        $quotadate = "";
        $deal_quota = 0;
        $monthly_qtd = 0;
        if ($emp_purchasing == 1) {
            $employee_quota = " employee_quota_purchasing ";
        } else {
            $employee_quota = " employee_quota ";
        }

        db();
        $result_empq = db_query("Select quota_year, quota_month from $employee_quota where emp_id = " . $rowemp["id"] . " order by quota_year, quota_month limit 1");
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
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from $employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
                //echo $newsel . "<br>";
                db();
                $result_empq = db_query($newsel);
                while ($rowemp_empq = array_shift($result_empq)) {
                    $quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
                    $quota = $quota + $quota_one_day;
                }
                //echo "Q: " . $quota;
            }
            $monthly_qtd = $quota;
        }

        if (
            $headtxt == "TODAY" || $headtxt == "YESTERDAY" || $headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR"
            || $headtxt == "THIS MONTH" || $headtxt == "LAST MONTH" || $headtxt == "THIS MONTH LAST YEAR"
        ) {

            db();
            $result_empq = db_query("Select sum(quota) as sumquota, sum(deal_quota) as deal_quota from $employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value);
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
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from $employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
                    $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                    $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
                }
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $todays_dt = date($dt_year_value . "-m-d");
                    $days_today = 1 + (int)dateDiff($todays_dt, date($dt_year_value . "-m-01"));
                    $days_in_month = 1 + (int)dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01'));
                }
                if ($headtxt == "LAST QUARTER") {
                    $days_today = 91;
                }
                if ($donot_add == "") {
                    if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                        if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                            $donot_add = "yes";
                            $monthly_qtd_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                            $quota_mtd = $quota_mtd + $monthly_qtd_1;
                        } else {
                            $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                        }
                    }
                }
            }

            $quota_in_st_en = $quota;
            if ($headtxt == "LAST QUARTER") {
                $monthly_qtd = (isset($days_today) * $quota) / 91;
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
            $result_empq = db_query("Select quota_month, quota, deal_quota from $employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = $quota + $rowemp_empq["quota"];
                //$deal_quota = $rowemp_empq["deal_quota"];

                if ($headtxt == "THIS YEAR") {
                    $todays_dt = date('m/d/Y');
                    $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                    $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
                }
                if ($headtxt == "LAST TO LAST YEAR") {
                    $todays_dt = date($dt_year_value . "-m-d");
                    $days_today = 1 + intval(dateDiff($todays_dt, date($dt_year_value . "-m-01")));
                    $days_in_month = 1 + intval(dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01')));
                }
                if ($headtxt == "LAST YEAR") {
                    $days_today = 365;
                }
                if ($donot_add == "") {
                    if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                        if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                            $donot_add = "yes";
                            $monthly_qtd_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                            $quota_mtd = $quota_mtd + $monthly_qtd_1;
                        } else {
                            $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                        }
                    }
                }
            }
            $quota_in_st_en = $quota;

            if ($headtxt == "LAST YEAR") {
                $monthly_qtd = (isset($days_today) * $quota) / 365;
            } else {
                $monthly_qtd = $quota_mtd;
            }
        }

        $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
        if ($po_flg == "yes") {
            //$lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
            $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
        } else {
            //$lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
            $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>G.Profit Amount</td><td class='txtstyle_color'>Profit Margin</td></tr>";
        }
        if ($po_flg == "yes") {
            if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee LIKE '" . $rowemp["initials"] . "'  and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
            }
        } else {
            if ($tilltoday == "Y") {
                if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt,  loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                }
            } else {
                if ($rowemp["leaderboard"] == 0 && $headtxt != "B2B Leaderboard" && $rowemp["id"] == 0) {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                }
            }
        }

        //if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
        //	if ($rowemp["leaderboard"] == 0){
        //		$sqlmtd = "SELECT loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        //	}else{
        //		$sqlmtd = "SELECT loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee LIKE '" . $rowemp["initials"] . "'  and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        //	}				
        //}

        if ($headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "LAST TO LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
            if ($headtxt == "LAST TO LAST YEAR") {
                $dt_year_value_1 = $dt_year_value;
                $end_Dtn = Date($dt_year_value_1 . '-12-31');
                $start_Dtn = Date($dt_year_value_1 . '-01-01');
            } else {
                $start_Dtn = $start_Dt;
                $end_Dtn = Date($dt_year_value . '-m-d');
            }
            if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
            }
        }
        //echo $headtxt . "|" . $sqlmtd . "<br>";
        $resultmtd = db_query($sqlmtd);
        $summtd_SUMPO = 0;
        $summtd_dealcnt = 0;
        $summtd_SUMPO_activity = 0;
        $sr_no = 0;
        $summtd_SUMPO_sale_rev = 0;
        while ($summtd = array_shift($resultmtd)) {
            $nickname = "";
            $industry_nm = "";
            $industry_id = "";
            if ($summtd["b2bid"] > 0) {
                db_b2b();
                $sql = "SELECT nickname, company, shipCity, shipState, industry_id FROM companyInfo where ID = " . $summtd["b2bid"];
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_id = $row_comp["industry_id"];
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

                $sql = "SELECT industry FROM industry_master where industry_id = '" . $industry_id . "'";
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_nm = $row_comp["industry"];
                }
                db();
            } else {
                $nickname = $summtd["warehouse_name"];
            }

            $finalpaid_amt = 0;
            /*$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]  );
			while ($summtd_finalpmt = array_shift($result_finalpmt)) {
				$finalpaid_amt = $summtd_finalpmt["amt"];
			}*/

            $inv_amt_totake = 0;

            if ($finalpaid_amt > 0) {
                $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
            }

            $summtd_SUMPO_sale_rev = $summtd_SUMPO_sale_rev + str_replace(",", "", number_format($inv_amt_totake, 0));

            $estimated_cost = 0;
            if ($po_flg != "yes") {
                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                db();
                $resB2bCogs = db_query($qryB2bCogs);

                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
                }
            }

            $summtd_SUMPO = $summtd_SUMPO + ($inv_amt_totake - $estimated_cost);

            $summtd_dealcnt = $summtd_dealcnt + 1;
            $po_delivery_dt = "";
            if ($summtd["po_delivery_dt"] != "") {
                $po_delivery_dt = date("m/d/Y", strtotime($summtd["po_delivery_dt"]));
            }

            $actual_delivery_date = "";
            $sql = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $summtd["id"];
            $sql_res = db_query($sql);
            while ($row = array_shift($sql_res)) {
                $actual_delivery_date = $row["bol_shipment_received_date"];
            }

            $sr_no = $sr_no + 1;
            if ($po_flg == "yes") {
                $summtd_SUMPO_activity = $summtd_SUMPO_activity + str_replace(",", "", number_format($summtd["inv_amount"], 0));
                //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $summtd["warehouse_id"] ."&rec_id=" . $summtd["id"] . "&display=buyer_view'>". $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"],0) . "</td></tr>";

                $lisoftrans_for_total .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='left'>" . $summtd["po_date"] . "</td><td bgColor='#E4EAEB' align='left'>" . $rowemp["name"] . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";

                $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";
            } else {
                //echo $summtd["id"] . " inv_amt_totake ". $inv_amt_totake . " | " . $estimated_cost . "<br>";
                //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $summtd["warehouse_id"] ."&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>". $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost),0) . "</td></tr>";

                $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
                <td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td><td bgColor='#E4EAEB' align='right'>" . number_format(($inv_amt_totake - $estimated_cost) * 100 / (float)str_replace(",", "", number_format($inv_amt_totake, 0)), 2) . "%</td></tr>";
            }
        }

        if ($summtd_SUMPO > 0) {
            if ($po_flg == "yes") {
                //$lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO_activity,0) . "</td></tr>";
                $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO_activity, 0) . "</td></tr>";
            } else {
                //$lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO,0) . "</td></tr>";
                $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
				<td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO_sale_rev, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td>
                <td bgColor='#ABC5DF' align='right'>" . number_format($summtd_SUMPO * 100 / floatval(str_replace(",", "", number_format($summtd_SUMPO_sale_rev, 0))), 2) . "%</td></tr>";
            }
        }
        $lisoftrans .= "</table></span>";

        //For TTLY
        $summ_ttly = 0;
        $summ_ttly_g_profit = 0;
        $summ_ttly_sales_rev = 0;
        $sr_no = 0;
        if ($ttylyesno == "ttylyes") {
            $start_Date = strtotime('-1 year', strtotime($start_Dt));
            //if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
            //	$end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));		
            //}else{
            $end_Date = strtotime('-1 year', strtotime($end_Dt));
            //$end_Date = strtotime('-1 year', strtotime(date("Y-m-t")));		
            //}

            $start_Dt_ttly = Date('Y-m-d', $start_Date);
            $end_Dt_ttly = Date('Y-m-d', $end_Date);

            $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
            if ($po_flg == "yes") {
                $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
            } else {
                $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
				<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
				<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>G.Profit Amount</td><td class='txtstyle_color'>Profit Margin</td></tr>";

                //"<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>G.Profit Amount</td><td class='txtstyle_color'>Profit Margin</td></tr>";
            }

            $lisoftrans_ttly_g_profit = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
            $lisoftrans_ttly_g_profit .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>G.Profit Amount</td><td class='txtstyle_color'>Profit Margin</td></tr>";

            if ($tilltoday == "Y" && $po_flg != "yes") {
                if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                }
            } else if ($po_flg == "yes") {
                if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_transaction_buyer.po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND transaction_date BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_transaction_buyer.po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND transaction_date BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                }
            } else {
                if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                }
            }
            //echo $headtxt . " | " . $sqlmtd . "<br>";
            $resultmtd = db_query($sqlmtd);
            while ($summtd = array_shift($resultmtd)) {
                $finalpaid_amt = 0;


                $industry_nm = "";
                $industry_id = "";
                db_b2b();
                $sql = "SELECT industry_id FROM companyInfo where ID = " . $summtd["b2bid"];
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_id = $row_comp["industry_id"];
                }

                $sql = "SELECT industry FROM industry_master where industry_id = '" . $industry_id . "'";
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_nm = $row_comp["industry"];
                }
                db();

                $inv_amt_totake = 0;
                if ($finalpaid_amt > 0) {
                    $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
                }
                $summ_ttly_sales_rev = $summ_ttly_sales_rev + $inv_amt_totake;

                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";

                db();
                $resB2bCogs = db_query($qryB2bCogs);

                $estimated_cost = 0;
                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
                }

                $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                $po_delivery_dt = "";
                if ($summtd["po_delivery_dt"] != "") {
                    $po_delivery_dt = date("m/d/Y", strtotime($summtd["po_delivery_dt"]));
                }

                $actual_delivery_date = "";
                $sql = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $summtd["id"];
                $sql_res = db_query($sql);
                while ($row = array_shift($sql_res)) {
                    $actual_delivery_date = $row["bol_shipment_received_date"];
                }

                $sr_no = $sr_no + 1;
                if ($po_flg == "yes") {
                    $summ_ttly = $summ_ttly + str_replace(",", "", number_format(($summtd["inv_amount"]), 0));
                    $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($summtd["inv_amount"]), 0) . "</td></tr>";
                } else {
                    $summ_ttly_g_profit = $summ_ttly_g_profit + ($inv_amt_totake - $estimated_cost);
                    $summ_ttly = $summ_ttly + ($inv_amt_totake);

                    $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
					<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
					<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
					<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
                    <td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake - $estimated_cost, 0) . "</td><td bgColor='#E4EAEB' align='right'>" . number_format(($inv_amt_totake - $estimated_cost) * 100 / (float)str_replace(",", "", number_format($inv_amt_totake, 0)), 2) . "%</td></tr>";

                    $lisoftrans_ttly_g_profit .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
					<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
					<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
					<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
                    <td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake - $estimated_cost, 0) . "</td><td bgColor='#E4EAEB' align='right'>" . number_format(($inv_amt_totake - $estimated_cost) * 100 / (float)str_replace(",", "", number_format($inv_amt_totake, 0)), 2) . "%</td></tr>";

                    //$lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $summtd["warehouse_id"] ."&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>". $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td>
                    //<td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake,0) . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost),0) . "</td>
                    //<td bgColor='#E4EAEB' align='right'>" . number_format(str_replace(",", "" ,number_format(($inv_amt_totake - $estimated_cost),0))*100/str_replace(",", "" ,number_format($inv_amt_totake,0)),2) . "%</td></tr>";
                }
            }

            if ($summ_ttly > 0) {
                if ($po_flg == "yes") {
                    $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td></tr>";
                } else {
                    $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
					<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_sales_rev, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_g_profit, 0) . "</td>
                    <td bgColor='#ABC5DF' align='right'>" . number_format($summ_ttly_g_profit * 100 / (float)str_replace(",", "", number_format($summ_ttly_sales_rev, 0)), 2) . "%</td></tr>";

                    //$lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_sales_rev,0) . "</td>
                    //<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly,0) . "</td>
                    //<td bgColor='#ABC5DF' align='right'>" . number_format(str_replace(",", "" ,number_format($summ_ttly,0))*100/str_replace(",", "" ,number_format($summ_ttly_sales_rev,0)),2) . "%</td>
                    //</tr>";
                }
            }
            $lisoftrans_ttly .= "</table></span>";

            if ($summ_ttly_g_profit > 0) {
                $lisoftrans_ttly_g_profit .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
				<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_sales_rev, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_g_profit, 0) . "</td>
                <td bgColor='#ABC5DF' align='right'>" . number_format($summ_ttly_g_profit * 100 / (float)str_replace(",", "", number_format($summ_ttly_sales_rev, 0)), 2) . "%</td></tr>";
            }
            $lisoftrans_ttly_g_profit .= "</table></span>";
        }
        //For TTLY

        $rev_lastyr_tilldt = 0;
        if ($headtxt == "LAST YEAR") {
            $dt_year_value_1 = date('Y') - 1;
            $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
            $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');

            $lisoftrans_lastyear = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
            $lisoftrans_lastyear .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";

            if ($rowemp["leaderboard"] == 0 && $rowemp["id"]) {
                $sqlmtd = "SELECT inv_number, loop_warehouse.warehouse_name, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT inv_number, loop_warehouse.warehouse_name, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
            }
            $resultmtd = db_query($sqlmtd);
            while ($summtd = array_shift($resultmtd)) {

                $finalpaid_amt = 0;
                /*$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]  );
				while ($summtd_finalpmt = array_shift($result_finalpmt)) {
					$finalpaid_amt = $summtd_finalpmt["amt"];
				}*/

                $industry_nm = "";
                $industry_id = "";
                db_b2b();
                $sql = "SELECT industry_id FROM companyInfo where ID = " . $summtd["b2bid"];
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_id = $row_comp["industry_id"];
                }

                $sql = "SELECT industry FROM industry_master where industry_id = '" . $industry_id . "'";
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_nm = $row_comp["industry"];
                }
                db();

                $inv_amt_totake = 0;

                if ($finalpaid_amt > 0) {
                    $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
                }

                $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";

                db();
                $resB2bCogs = db_query($qryB2bCogs);

                $estimated_cost = 0;
                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
                }
                //$rev_lastyr_tilldt = $rev_lastyr_tilldt + ($inv_amt_totake - $estimated_cost);
                $rev_lastyr_tilldt = $rev_lastyr_tilldt + ($inv_amt_totake);

                $lisoftrans_lastyear .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
            }
            if ($rev_lastyr_tilldt > 0) {
                $lisoftrans_lastyear .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($rev_lastyr_tilldt, 0) . "</td></tr>";
            }
            $lisoftrans_lastyear .= "</table></span>";
        }

        if ($headtxt == "LAST TO LAST YEAR") {
            $monthly_qtd = isset($quota_in_st_en);
        }

        if ($summtd_SUMPO >= $monthly_qtd) {
            $color = "green";
        } elseif (($summtd_SUMPO < $monthly_qtd && $summtd_SUMPO > 0) || $summtd_SUMPO < 0) {
            $color = "red";
        } else {
            $color = "black";
        };
        //commented as per team chat 25Jul2022  if ($monthly_qtd == 0) { $color = "black";}

        $add_entry = "yes";
        if ($headtxt == "B2B Leaderboard") {
            if ($summtd_SUMPO == 0) {
                $add_entry = "no";
            }
        }

        if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
            $summtd_SUMPO_tweek = -99999;
        } else {
            $summtd_SUMPO_tweek = $summtd_SUMPO_activity;
        }

        if ($add_entry == "yes") {
            $MGArray[] = array(
                'name' => $rowemp["name"], 'name_initial' => $rowemp["initials"], 'emp_initials_list' => $emp_initials_list, 'emp_b2bid_list' => $emp_b2bid_list,  'emp_id_list' => $emp_id_list,
                'emp_email' => $rowemp["email"], 'b2bempid' => $rowemp["b2b_id"], 'leaderboard' => $rowemp["leaderboard"], 'summtd_SUMPO_activity' => $summtd_SUMPO_activity,
                'empid' => $rowemp["id"], 'start_Dt' => $start_Dt, 'end_Dt' => $end_Dt, 'deal_count' => $summtd_dealcnt, 'quota' => isset($quota_in_st_en),
                'quotatodate' => $monthly_qtd, 'po_entered' => $summtd_SUMPO, 'summtd_SUMPO_sale_rev' => $summtd_SUMPO_sale_rev, 'po_entered_other_tweek' => $summtd_SUMPO_tweek, 'ttly' => $summ_ttly, 'percent_val' => $color,
                'rev_lastyr_tilldt' => $rev_lastyr_tilldt, 'lisoftrans' => $lisoftrans, 'summ_ttly_g_profit' => isset($summ_ttly_g_profit), 'lisoftrans_ttly_g_profit' => isset($lisoftrans_ttly_g_profit),
                'lisoftrans_ttly' => isset($lisoftrans_ttly), 'lisoftrans_lastyear' => isset($lisoftrans_lastyear), 'lisoftrans_for_total' => $lisoftrans_for_total
            );
        }
    }

    $MGArray = $MGArray ?? [];
    $_SESSION['sortarrayn'] = $MGArray;

    $sort_order_pre = "ASC";
    if ($_POST['sort_order_pre'] == "ASC") {
        $sort_order_pre = "DESC";
    } else {
        $sort_order_pre = "ASC";
    }

    if (isset($_REQUEST["sort"])) {
        $MGArray = $_SESSION['sortarrayn'];
        if ($_POST['sort'] == "name" && $_POST['sort_order_pre'] == "ASC") {
            $MGArraysort_I = array();

            foreach ($MGArray as $MGArraytmp) {
                $MGArraysort_I[] = $MGArraytmp['companyID'];
            }
            array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
        }
    } else if ($po_flg == "yes") {
        $MGArray = $_SESSION['sortarrayn'];
        $MGArraysort_I = array();

        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp['po_entered_other_tweek'];
        }
        array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
    } else {
        $MGArray = $_SESSION['sortarrayn'];
        $MGArraysort_I = array();

        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp['po_entered'];
        }
        array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
    }


    $tot_quota_mtd = 0;
    $tot_quotaactual_mtd = 0;
    $tot_summtd_ttly = 0;
    $tot_quota_deal_mtd = 0;
    $tot_rev_lastyr_tilldt = 0;
    foreach ($MGArray as $MGArraytmp2) {

        $name = $MGArraytmp2["name"];
        $monthly_deal_qtd = $MGArraytmp2["deal_count"];
        $monthly_qtd = $MGArraytmp2["quota"];
        $monthly_qtd_TD = $MGArraytmp2["quotatodate"];
        $summtd_SUMPO_sale_rev = $MGArraytmp2["summtd_SUMPO_sale_rev"];
        if ($po_flg == "yes") {
            $summtd_SUMPO = $MGArraytmp2["summtd_SUMPO_activity"];
        } else {
            $summtd_SUMPO = $MGArraytmp2["po_entered"];
        }
        $summtd_ttly = $MGArraytmp2["ttly"];
        $color_y = $MGArraytmp2["percent_val"];
        $summ_ttly_g_profit = $MGArraytmp2["summ_ttly_g_profit"];

        echo "<tr><td bgColor='$tbl_color' width='100px' align ='left'>" . $coltxt . "</td>";
        echo "<td bgColor='$tbl_color' align = 'right'>";
        echo "$" . number_format(floatval($monthly_qtd), 0) . "</td>";
        //if ($headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER" || $headtxt == "THIS YEAR" ) {
        echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($monthly_qtd_TD, 0) . "</td>";
        //}

        if ($summtd_SUMPO_sale_rev >= $monthly_qtd_TD && $monthly_qtd_TD != 0) {
            $revenue_color = "green";
        } elseif ($summtd_SUMPO_sale_rev > 0 && $monthly_qtd_TD == 0) {
            $revenue_color = "green";
        } elseif ($summtd_SUMPO_sale_rev < $monthly_qtd_TD) {
            $revenue_color = "red";
        } elseif ($monthly_qtd_TD == 0) {
            $revenue_color = "black";
        }

        echo "<td bgColor='$tbl_color' align = 'right'>";
        echo "<a href='#' style='font-size: 10pt !important;' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . isset($revenue_color) . "'>$" . number_format($summtd_SUMPO_sale_rev, 0) . "</font></a>";
        echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
        echo "</td>";


        if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            if ($summtd_SUMPO >= $monthly_qtd) {
                $color = "green";
            } elseif ($summtd_SUMPO < $monthly_qtd && $summtd_SUMPO > 0) {
                $color = "red";
            } else {
                $color = "black";
            };
        }
        $color_y_new = "black";
        //if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR"){
        //	if (($summtd_SUMPO_sale_rev*100/$monthly_qtd) >= 100) { $color_y_new = "green"; } elseif ((($summtd_SUMPO_sale_rev*100/$monthly_qtd) < 100 && $monthly_qtd > 0) || $summtd_SUMPO_sale_rev < 0) { $color_y_new = "red"; } elseif ($summtd_SUMPO_sale_rev > 0 && $monthly_qtd == 0) { $color_y_new = "green"; } elseif ($summtd_SUMPO_sale_rev == 0 && $monthly_qtd > 0) { $color_y_new = "red"; } else { $color_y_new = "black"; };
        //}else{
        if (($summtd_SUMPO_sale_rev * 100 / $monthly_qtd_TD) >= 100) {
            $color_y_new = "green";
        } elseif ((($summtd_SUMPO_sale_rev * 100 / $monthly_qtd_TD) < 100 && $monthly_qtd_TD > 0) || $summtd_SUMPO_sale_rev < 0) {
            $color_y_new = "red";
        } elseif ($summtd_SUMPO_sale_rev > 0 && $monthly_qtd_TD == 0) {
            $color_y_new = "green";
        } elseif ($summtd_SUMPO_sale_rev == 0 && $monthly_qtd_TD > 0) {
            $color_y_new = "red";
        } else {
            $color_y_new = "black";
        };
        //}			

        echo "<td style='border-right-style:solid; border-right-width: thin; border-right-color: black;' bgColor='$tbl_color' align = 'right'><font color='" . $color_y_new . "'> ";
        //if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR"){
        //	if ($summtd_SUMPO_sale_rev > 0 && $monthly_qtd > 0){
        //		echo number_format($summtd_SUMPO_sale_rev*100/$monthly_qtd,2) . "%";
        //	}	
        //}else{
        if ($summtd_SUMPO_sale_rev > 0 && $monthly_qtd_TD > 0) {
            echo number_format($summtd_SUMPO_sale_rev * 100 / $monthly_qtd_TD, 2) . "%";
        }
        //}
        echo "</font></td>";

        echo "</td><td bgColor='$tbl_color' align = 'right'>";

        echo "<a href='#' style='font-size: 10pt !important;' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y_new . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
        echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
        echo "</td>";
        /*if ($headtxt == "LAST YEAR"){			
			echo "<td bgColor='$tbl_color' align = 'right'>";
			echo "<a href='#' style='font-size:medium !important;' onclick='load_div(66". $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($MGArraytmp2["rev_lastyr_tilldt"],0) . "</font></a>";
			echo "<span id='66". $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_lastyear"] . "</span>";
			echo "</td>";
			
		}*/

        $profit_mrg = "";
        if ($summtd_SUMPO_sale_rev > 0) {
            $profit_mrg = ($summtd_SUMPO * 100 / $summtd_SUMPO_sale_rev);
        }
        if ($profit_mrg >= 30) {
            $profit_mrg_color = "green";
        } else {
            $profit_mrg_color = "red";
        };

        if ($profit_mrg <> "") {
            echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $profit_mrg_color . "'>" . number_format($profit_mrg, 2) . "%</td>";
        } else {
            echo "<td bgColor='$tbl_color' align = 'right'></td>";
        }

        if ($ttylyesno == "ttylyes") {
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a href='#' style='font-size: 10pt !important;' onclick='load_div(77" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summtd_ttly, 0) . "</font></a>";
            echo "<span id='77" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_ttly"] . "</span>";
            echo "</td>";

            echo "<td bgColor='$tbl_color' align = 'right'>";
            if (is_float($summ_ttly_g_profit)) {
                echo "<a href='#' style='font-size: 10pt !important;' onclick='load_div(177" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summ_ttly_g_profit, 0) . "</font></a>";
            }
            echo "<span id='177" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_ttly_g_profit"] . "</span>";
            echo "</td>";
        }
        echo "</tr>";




        $monthly_deal_qtd = number_format($monthly_deal_qtd, 0);

        $tot_quota_mtd = $tot_quota_mtd + $monthly_qtd;
        $tot_quota_mtd_TD = isset($tot_quota_mtd_TD) + $monthly_qtd_TD;
        $tot_quotaactual_mtd = $tot_quotaactual_mtd + str_replace(",", "", number_format($summtd_SUMPO, 0));
        $tot_quota_deal_mtd = $tot_quota_deal_mtd + $monthly_deal_qtd;
        $tot_rev_lastyr_tilldt = $tot_rev_lastyr_tilldt + str_replace(",", "", number_format($MGArraytmp2["rev_lastyr_tilldt"], 0));
        $tot_summtd_ttly = $tot_summtd_ttly + str_replace(",", "", number_format($summtd_ttly, 0));
    }
}



function leadertbl_all(
    string $start_Dt,
    string $end_Dt,
    string $headtxt,
    string $tilltoday,
    int $currentyr,
    string $tbl_head_color,
    string $tbl_color,
    string $po_flg,
    string $unqid,
    string $ttylyesno,
    string $timeperiod,
    string $dashboard_view,
    string $activity_tracker_flg = "no"
): void {

    db();

    $tot_quota = 0;
    $tot_quotaytd = 0;
    $tot_quotaactual = 0;
    $tot_quota_mtd = 0;
    $tot_quota_deal_mtd = 0;
    $tot_quotaactual_mtd = 0;
    $tot_summtd_ttly = 0;
    $quota_one_day = 0;
    $lisoftrans_tot = "";

    $dt_year_value = date('Y', strtotime($start_Dt));
    $dt_month_value = date('m', strtotime($start_Dt));
    $current_year_value = date('Y');

    $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));

    $employee_quota_tbl = " employee_quota_overall ";
    $employee_quota_tbl_str = " b2borb2c = 'b2b' and ";
    $employee_quota_tbl_stretch_g = " employee_quota_overall_stretch_g ";
    //$employee_quota_tbl_stretch_g_str = " b2borb2c = 'b2bgp' and ";
    $employee_quota_overall_sales_gp = " employee_quota_overall_sales_gp ";
    $Leaderboard_filter = "Leaderboard = 'B2B'";

    if ($dashboard_view == "Sales") {
        $employee_quota_tbl = " employee_quota_overall ";
        $employee_quota_tbl_str = " b2borb2c = 'b2b' and ";
        $employee_quota_tbl_stretch_g = " employee_quota_overall_stretch_g ";
        $employee_quota_overall_sales_gp = " employee_quota_overall_sales_gp ";
        $Leaderboard_filter = "Leaderboard = 'B2B'";
    }

    if ($dashboard_view == "Pallet Sales") {
        $employee_quota_tbl = " employee_quota_overall_pallet_sale ";
        //$employee_quota_tbl_str = "";
        //$employee_quota_tbl_stretch_g_str = "";
        $employee_quota_tbl_stretch_g = " employee_quota_overall_pallet_sale_stretch ";
        $Leaderboard_filter = "Leaderboard = 'PALLETS'";
    }

    if ($dashboard_view == "UCBZW") {
        $employee_quota_tbl = " employee_quota_gprofit ";
        //$employee_quota_tbl_str = "";
        //$employee_quota_tbl_stretch_g_str = "";
        $employee_quota_tbl_stretch_g = " employee_quota_gprofit ";
        $Leaderboard_filter = "Leaderboard = 'UCBZW'";
    }

    //For B2B Revenue calculation
    $quota_ov = 0;
    if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
        $sql_ovdata = "SELECT quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";

        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov = $rowemp_ovdata["quota"];
        }
    }

    if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "B2B Leaderboard") {
        $begin = new DateTime($start_Dt);
        $end   = new DateTime($end_Dt);
        $currentdate  = new DateTime(date("Y-m-d"));
        $quota = 0;
        $quota_to_date = 0;
        for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
            $start_Dt_tmp = $datecnt->format("Y-m-d");
            $quota_mtd = 0;
            $newsel = "Select quota_month, quota , deal_quota, quota_year  FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");

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
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
        }
        if ($current_qtr == 2) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
        }
        if ($current_qtr == 3) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
        }
        if ($current_qtr == 4) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
        }
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 30;
        $quota = 0;
        $dt_month_value_1 = date('m');
        // $result_empq = isset($result_empq) ?? '';
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = $quota + $rowemp_empq["quota"];
        }

        $quota_ov = $quota;
    }

    if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
        $sql_ovdata = "SELECT quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value;
        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov = $quota_ov + $rowemp_ovdata["quota"];
        }
    }
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
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
            }
        }
        if ($current_qtr == 2) {
            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
            }
        }
        if ($current_qtr == 3) {
            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
            }
        }
        if ($current_qtr == 4) {

            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
            }
        }
        $result_empq = $result_empq ?? '';
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];
            if ($headtxt == "THIS QUARTER") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + (int)dateDiff($todays_dt, date('Y-m-01'));
                $days_in_month = 1 + (int)dateDiff(date('Y-m-t'), date('Y-m-01'));
            }
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + (int)dateDiff($todays_dt, date($dt_year_value . "-m-01"));
                $days_in_month = 1 + (int)dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01'));
            }
            if ($headtxt == "LAST QUARTER") {
                $days_today = 91;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }

        $quota_in_st_en = isset($quota);
        if ($headtxt == "LAST QUARTER") {
            $monthly_qtd = (isset($days_today) * isset($quota)) / 91;
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
        $result_empq = db_query("Select quota_month, quota, deal_quota FROM $employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " order by quota_month");
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];

            if ($headtxt == "THIS YEAR") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + (int)dateDiff($todays_dt, date('Y-m-01'));
                $days_in_month = 1 + (int)dateDiff(date('Y-m-t'), date('Y-m-01'));
            }
            if ($headtxt == "LAST TO LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + (int)dateDiff($todays_dt, date($dt_year_value . "-m-01"));
                $days_in_month = 1 + (int)dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01'));
            }
            if ($headtxt == "LAST YEAR") {
                $days_today = 365;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }
        $quota_in_st_en = isset($quota);

        if ($headtxt == "LAST YEAR") {
            $monthly_qtd = (isset($days_today) * isset($quota)) / 365;
        } else {
            $monthly_qtd = $quota_mtd;
        }
    }

    $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
    $lisoftrans_b2b_rev = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
    if ($po_flg == "yes") {
        $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
    } else {
        $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>G.Profit Amount</td><td class='txtstyle_color'>Profit Margin</td></tr>";

        $lisoftrans_b2b_rev .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
    }

    if ($tilltoday == "Y") {
        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
    } else {
        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
    }

    if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1 and Leaderboard = 'B2B' AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
    }
    if ($headtxt == "THIS YEAR") {
        //echo $sqlmtd . "<br>";
    }
    $resultmtd = db_query($sqlmtd);
    $summtd_SUMPO = 0;
    $summtd_dealcnt = 0;
    $sr_no = 0;
    $revenue_sales_b2b = 0;
    while ($summtd = array_shift($resultmtd)) {
        $nickname = "";
        $industry_nm = "";
        $industry_id = "";
        if ($summtd["b2bid"] > 0) {
            db_b2b();
            $sql = "SELECT nickname, company, shipCity, shipState, industry_id FROM companyInfo where ID = " . $summtd["b2bid"];
            $result_comp = db_query($sql);
            while ($row_comp = array_shift($result_comp)) {
                $industry_id = $row_comp["industry_id"];
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

            $sql = "SELECT industry FROM industry_master where industry_id = '" . $industry_id . "'";
            $result_comp = db_query($sql);
            while ($row_comp = array_shift($result_comp)) {
                $industry_nm = $row_comp["industry"];
            }
            db();
        } else {
            $nickname = $summtd["warehouse_name"];
        }

        $finalpaid_amt = 0;


        $inv_amt_totake = 0;
        if ($finalpaid_amt > 0) {
            $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
        }
        if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
            $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
        }
        if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
            $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
        }

        $revenue_sales_b2b = $revenue_sales_b2b + str_replace(",", "", number_format($inv_amt_totake, 0));

        $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
			FROM loop_transaction_buyer 
			LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
			INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
			WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
			and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        db();
        $resB2bCogs = db_query($qryB2bCogs);

        $estimated_cost = 0;
        while ($resB2bCogs_row = array_shift($resB2bCogs)) {
            $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
        }
        $summtd_SUMPO = $summtd_SUMPO + str_replace(",", "", number_format(($inv_amt_totake - $estimated_cost), 0));

        $summtd_dealcnt = $summtd_dealcnt + 1;
        $po_delivery_dt = "";
        if ($summtd["po_delivery_dt"] != "") {
            $po_delivery_dt = date("m/d/Y", strtotime($summtd["po_delivery_dt"]));
        }

        $actual_delivery_date = "";
        $sql = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $summtd["id"];
        $sql_res = db_query($sql);
        while ($row = array_shift($sql_res)) {
            $actual_delivery_date = $row["bol_shipment_received_date"];
        }

        $sr_no = $sr_no + 1;
        if ($po_flg == "yes") {
            //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $summtd["warehouse_id"] ."&rec_id=" . $summtd["id"] . "&display=buyer_view'>". $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"],0) . "</td></tr>";

            $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";
        } else {
            //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $summtd["warehouse_id"] ."&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>". $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost),0) . "</td></tr>";

            $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td>
				<td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
				<td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td>
                <td bgColor='#E4EAEB' align='right'>" . number_format((float)str_replace(",", "", number_format($inv_amt_totake - $estimated_cost, 0)) * 100 / (float)str_replace(",", "", number_format($inv_amt_totake, 0)), 0) . "%</td>
				</tr>";

            $lisoftrans_b2b_rev .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td>
				<td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
				</tr>";
        }
    }
    if ($summtd_SUMPO > 0) {
        $tot_quotaactual_mtd = $summtd_SUMPO;
        $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
			<td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
			<td bgColor='#ABC5DF' align='right'>$" . number_format($revenue_sales_b2b, 0) . "</td>
			<td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td>
            <td bgColor='#ABC5DF' align='right'>" . number_format((float) str_replace(",", "", number_format($summtd_SUMPO, 0)) * 100 / (float) str_replace(",", "", number_format($revenue_sales_b2b, 0)), 0) . "%</td>
			
			</tr>";
    }
    $lisoftrans .= "</table></span>";

    if ($revenue_sales_b2b > 0) {
        $lisoftrans_b2b_rev .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
			<td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
			<td bgColor='#ABC5DF' align='right'>$" . number_format($revenue_sales_b2b, 0) . "</td>
			</tr>";
    }
    $lisoftrans_b2b_rev .= "</table></span>";

    //This Time Last Year TTLY
    $summ_ttly = 0;
    $tot_quotaactual_mtd_ttly = 0;
    $summ_ttly_sales_rev = 0;
    $sr_no = 0;
    if ($ttylyesno == "ttylyes") {
        $start_Date = strtotime('-1 year', strtotime($start_Dt));
        //if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
        //	$end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));		
        //}else{
        $end_Date = strtotime('-1 year', strtotime($end_Dt));
        //}

        $start_Dt_ttly = Date('Y-m-d', $start_Date);
        $end_Dt_ttly = Date('Y-m-d', $end_Date);

        $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>G.Profit Amount</td><td class='txtstyle_color'>Profit Margin</td></tr>";

        $lisoftrans_ttly_b2b_rev = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisoftrans_ttly_b2b_rev .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";

        if ($tilltoday == "Y") {
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
        } else {
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
        }

        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $nickname = "";
            $industry_nm = "";
            $industry_id = "";
            if ($summtd["b2bid"] > 0) {
                db_b2b();
                $sql = "SELECT nickname, company, shipCity, shipState, industry_id FROM companyInfo where ID = " . $summtd["b2bid"];
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_id = $row_comp["industry_id"];
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

                $sql = "SELECT industry FROM industry_master where industry_id = '" . $industry_id . "'";
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_nm = $row_comp["industry"];
                }
                db();
            } else {
                $nickname = $summtd["warehouse_name"];
            }

            $finalpaid_amt = 0;


            $inv_amt_totake = 0;
            if ($finalpaid_amt > 0) {
                $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
            }
            $summ_ttly_sales_rev = $summ_ttly_sales_rev + $inv_amt_totake;

            $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            db();
            $resB2bCogs = db_query($qryB2bCogs);

            $estimated_cost = 0;
            while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
            }
            $summ_ttly = $summ_ttly + ($inv_amt_totake - $estimated_cost);

            $po_delivery_dt = "";
            if ($summtd["po_delivery_dt"] != "") {
                $po_delivery_dt = date("m/d/Y", strtotime($summtd["po_delivery_dt"]));
            }

            $actual_delivery_date = "";
            $sql = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $summtd["id"];
            $sql_res = db_query($sql);
            while ($row = array_shift($sql_res)) {
                $actual_delivery_date = $row["bol_shipment_received_date"];
            }

            $sr_no = $sr_no + 1;

            $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
                <td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake - $estimated_cost, 0) . "</td><td bgColor='#E4EAEB' align='right'>" . number_format(($inv_amt_totake - $estimated_cost) * 100 / (float)str_replace(",", "", number_format($inv_amt_totake, 0)), 2) . "%</td></tr>";

            $lisoftrans_ttly_b2b_rev .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
				</tr>";
        }
        if ($summ_ttly > 0) {
            $tot_quotaactual_mtd_ttly = $summ_ttly;

            $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
				<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_sales_rev, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td>
                <td bgColor='#ABC5DF' align='right'>" . number_format($summ_ttly * 100 / floatval(str_replace(",", "", number_format($summ_ttly_sales_rev, 0))), 2) . "%</td></tr>";
        }
        $lisoftrans_ttly .= "</table></span>";

        if ($summ_ttly_sales_rev > 0) {
            $lisoftrans_ttly_b2b_rev .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
				<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_sales_rev, 0) . "</td></tr>";
        }
        $lisoftrans_ttly_b2b_rev .= "</table></span>";
    }
    //This Time Last Year TTLY

    $rev_lastyr_tilldt = 0;
    $sales_rev_lastyr_tilldt = 0;
    if ($headtxt == "LAST YEAR") {
        $dt_year_value_1 = date('Y') - 1;
        $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
        $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');
        $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0  and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $finalpaid_amt = 0;
            /*$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"]  );
				while ($summtd_finalpmt = array_shift($result_finalpmt)) {
					$finalpaid_amt = $summtd_finalpmt["amt"];
				}*/

            $inv_amt_totake = 0;
            if ($finalpaid_amt > 0) {
                $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
            }
            $sales_rev_lastyr_tilldt = $sales_rev_lastyr_tilldt + $inv_amt_totake;

            $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            db();
            $resB2bCogs = db_query($qryB2bCogs);

            $estimated_cost = 0;
            while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
            }
            $rev_lastyr_tilldt = $rev_lastyr_tilldt + ($inv_amt_totake - $estimated_cost);
        }
        //$tot_quotaactual_mtd = $rev_lastyr_tilldt;
    }





    if ($headtxt != "THIS WEEK" && $headtxt != "LAST WEEK") {
        //leadertbl_ucbzw($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid , $ttylyesno, $in_dt_range);
    }

    //For B2B Revenue calculation
    $quota_ov_b2b_rev = 0;
    if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
        $sql_ovdata = "SELECT quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov_b2b_rev = $rowemp_ovdata["quota"];
        }
    }

    if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "B2B Leaderboard") {
        $begin = new DateTime($start_Dt);
        $end   = new DateTime($end_Dt);
        $currentdate  = new DateTime(date("Y-m-d"));
        $quota = 0;
        $quota_to_date = 0;
        for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
            $start_Dt_tmp = $datecnt->format("Y-m-d");
            $quota_mtd = 0;
            $newsel = "Select quota_month, quota , deal_quota, quota_year  FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
            db();
            $result_empq = db_query($newsel);
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
                $quota = $quota + $quota_one_day;
                //echo "week quota: " . $quota . "<br>";
            }
        }
        $quota_ov_b2b_rev = $quota;
    }

    if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
        $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
        db();
        if ($current_qtr == 1) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
        }
        if ($current_qtr == 2) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
        }
        if ($current_qtr == 3) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
        }
        if ($current_qtr == 4) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
        }
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 30;
        $quota = 0;
        $dt_month_value_1 = date('m');
        $result_empq = $result_empq ?? 0;
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = $quota + $rowemp_empq["quota"];
        }

        $quota_ov_b2b_rev = $quota;
    }

    if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
        $sql_ovdata = "SELECT quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value;
        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov_b2b_rev = $quota_ov_b2b_rev + $rowemp_ovdata["quota"];
        }
    }

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
            $quota_one_day = $quota_ov_b2b_rev / date('t', strtotime($start_Dt_tmp));
            $quota = $quota + $quota_one_day;
        }
        $monthly_qtd_b2b_rev = $quota;
    }

    if (
        $headtxt == "TODAY" || $headtxt == "YESTERDAY" || $headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR"
        || $headtxt == "THIS MONTH" || $headtxt == "LAST MONTH" || $headtxt == "THIS MONTH LAST YEAR"
    ) {
        $quota = $quota_ov_b2b_rev;

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
        //$monthly_qtd_b2b_rev = $quota*$dim/$quota_days;

        if ($headtxt == "TODAY" || $headtxt == "THIS WEEK" || $headtxt == "THIS MONTH") {
            $monthly_qtd_b2b_rev = (date("d") * $quota) / date("t");
        } else {
            $monthly_qtd_b2b_rev = $quota * $dim / $quota_days;
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

            $quota_one_day = $quota_ov_b2b_rev * 1 / 7;
            $quota = $quota + $quota_one_day;
            if ($datecnt <= $currentdate) {
                //echo $quota_ov_b2b_rev . " " . date('t', strtotime($start_Dt_tmp)) . " " . $quota_one_day . "<br>";
                $quota_to_date = $quota_to_date + $quota_one_day;
            }
        }

        $monthly_qtd_b2b_rev = $quota_to_date;
    }

    if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
        $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 30;
        $dt_month_value_1 = date('m');

        if ($current_qtr == 1) {

            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
            }
        }
        if ($current_qtr == 2) {
            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
            }
        }
        if ($current_qtr == 3) {
            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
            }
        }
        if ($current_qtr == 4) {
            db();
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
            }
        }
        $result_empq = $result_empq ?? '';
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];
            if ($headtxt == "THIS QUARTER") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
            }
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + intval(dateDiff($todays_dt, date($dt_year_value . "-m-01")));
                $days_in_month = 1 + intval(dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01')));
            }
            if ($headtxt == "LAST QUARTER") {
                $days_today = 91;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_b2b_rev_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_b2b_rev_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }

        $quota_in_st_en = isset($quota);
        if ($headtxt == "LAST QUARTER") {
            $monthly_qtd_b2b_rev = (isset($days_today) * isset($quota)) / 91;
        } else {
            $monthly_qtd_b2b_rev = $quota_mtd;
        }
    }

    if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 0;
        $dt_month_value_1 = date('m');
        if ($headtxt == "LAST YEAR") {
            $dt_year_value_tmp = date("Y");
            $dt_year_value_tmp = $dt_year_value_tmp - 1;
            db();
            $result_empq = db_query("Select quota_month, quota, deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value_tmp . " order by quota_month");
        } else {
            $result_empq = db_query("Select quota_month, quota, deal_quota FROM $employee_quota_tbl WHERE $employee_quota_tbl_str quota_year = " . $dt_year_value . " order by quota_month");
        }
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];

            if ($headtxt == "THIS YEAR") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
            }
            if ($headtxt == "LAST TO LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + intval(dateDiff($todays_dt, date($dt_year_value . "-m-01")));
                $days_in_month = 1 + intval(dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01')));
            }
            if ($headtxt == "LAST YEAR") {
                $days_today = 365;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_b2b_rev_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_b2b_rev_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }
        $quota_in_st_en = isset($quota);

        if ($headtxt == "LAST YEAR") {
            //echo "days_today" . $days_today . " " . $quota . "<br>";

            $monthly_qtd_b2b_rev = (isset($days_today) * isset($quota)) / 365;
        } else {
            $monthly_qtd_b2b_rev = $quota_mtd;
        }
    }



    echo "<table cellSpacing='1' cellPadding='1' border='0' width='750'>";
    echo "	<tr>";
    echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[UCB] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";

    echo "	</tr>";
    echo "</table>";

    echo "<table cellSpacing='1' cellPadding='1' border='0' width='750' id='table9' class='tablesorter'>";
    echo "	<tr style='height:50px;'>";
    echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Department</u></th>";
    //if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR")  {
    echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
    echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";

    echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Actual</u></th>";

    echo "		<th width='90px' bgColor='$tbl_head_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align=center><u>% of Quota</u></th>";
    echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>Profit Margin</u></th>";

    if ($ttylyesno == "ttylyes") {
        echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>TRLY</u></th>";
    }
    echo "	</tr>";

    echo "<tr><td bgColor='$tbl_color' align ='left'>B2B Revenue</td>";
    echo "<td bgColor='$tbl_color' align ='right'>";
    echo "$" . number_format($quota_ov_b2b_rev, 0);
    echo "</td><td bgColor='$tbl_color' align = 'right'>";
    $monthly_qtd_b2b_rev = $monthly_qtd_b2b_rev ?? 0;
    echo "$" . number_format($monthly_qtd_b2b_rev, 0);

    if ($revenue_sales_b2b >= isset($monthly_qtd_b2b_rev)) {
        $color = "green";
    } elseif (($revenue_sales_b2b < isset($monthly_qtd_b2b_rev) && $revenue_sales_b2b > 0) || $revenue_sales_b2b < 0) {
        $color = "red";
    } else {
        $color = "black";
    };

    echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

    echo "<a href='#' onclick='load_div(789" . $unqid . isset($MGArraytmp2["empid"]) . "); return false;'><font color='" . $color . "'>$" . number_format($revenue_sales_b2b, 0) . "</font></a>";
    echo "<span id='789" . $unqid . isset($MGArraytmp2["empid"]) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_b2b_rev . "</span>";

    echo "</font></td>";
    echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>";
    echo number_format($revenue_sales_b2b * 100 / isset($monthly_qtd_b2b_rev), 2);
    echo "%</font></td>";
    $per_val = number_format((float)str_replace(",", "", number_format($tot_quotaactual_mtd, 0)) * 100 / (float)str_replace(",", "", number_format($revenue_sales_b2b, 0)), 2);
    if ($per_val >= 30) {
        $per_val_color = "green";
    } else {
        $per_val_color = "red";
    }

    echo "<td bgColor='$tbl_color' align='right'><font color='" . $per_val_color . "'>";
    if ($tot_quotaactual_mtd > 0 && $revenue_sales_b2b > 0) {
        echo $per_val . "%";
    }
    echo "</td>";

    if ($ttylyesno == "ttylyes") {
        echo "<td bgColor='$tbl_color' align = 'right'>";

        echo "<a href='#' onclick='load_div(887" . $unqid . isset($MGArraytmp2["empid"]) . "); return false;'><font color=black>$" . number_format($summ_ttly_sales_rev, 0) . "</font></a>";
        echo "<span id='887" . $unqid . isset($MGArraytmp2["empid"]) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . isset($lisoftrans_ttly_b2b_rev) . "</span>";

        echo "</font></td>";
    }
    echo "</tr>";
    //This is for B2B table B2B Revenue	

    //This is for B2B STRETCH table B2B Revenue	calculation
    $quota_ov_b2b_rev = 0;
    if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
        $sql_ovdata = "SELECT quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov_b2b_rev = $rowemp_ovdata["quota"];
        }
    }

    if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "B2B Leaderboard") {
        $begin = new DateTime($start_Dt);
        $end   = new DateTime($end_Dt);
        $currentdate  = new DateTime(date("Y-m-d"));
        $quota = 0;
        $quota_to_date = 0;
        for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
            $start_Dt_tmp = $datecnt->format("Y-m-d");
            $quota_mtd = 0;
            $newsel = "Select quota_month, quota , deal_quota, quota_year  FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
            db();
            $result_empq = db_query($newsel);
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
                $quota = $quota + $quota_one_day;
                //echo "week quota: " . $quota . "<br>";
            }
        }
        $quota_ov_b2b_rev = $quota;
    }

    if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
        $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
        db();
        if ($current_qtr == 1) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
        }
        if ($current_qtr == 2) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
        }
        if ($current_qtr == 3) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
        }
        if ($current_qtr == 4) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
        }
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 30;
        $quota = 0;
        $dt_month_value_1 = date('m');
        $result_empq = $result_empq ?? '';
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = $quota + $rowemp_empq["quota"];
        }

        $quota_ov_b2b_rev = $quota;
    }

    if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
        $sql_ovdata = "SELECT quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value;
        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov_b2b_rev = $quota_ov_b2b_rev + $rowemp_ovdata["quota"];
        }
    }

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
            $quota_one_day = $quota_ov_b2b_rev / date('t', strtotime($start_Dt_tmp));
            $quota = $quota + $quota_one_day;
        }
        $monthly_qtd_b2b_rev = $quota;
    }

    if (
        $headtxt == "TODAY" || $headtxt == "YESTERDAY" || $headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR"
        || $headtxt == "THIS MONTH" || $headtxt == "LAST MONTH" || $headtxt == "THIS MONTH LAST YEAR"
    ) {
        $quota = $quota_ov_b2b_rev;

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
        //$monthly_qtd_b2b_rev = $quota*$dim/$quota_days;

        if ($headtxt == "TODAY" || $headtxt == "THIS WEEK" || $headtxt == "THIS MONTH") {
            $monthly_qtd_b2b_rev = (date("d") * $quota) / date("t");
        } else {
            $monthly_qtd_b2b_rev = $quota * $dim / $quota_days;
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

            $quota_one_day = $quota_ov_b2b_rev * 1 / 7;
            $quota = $quota + $quota_one_day;
            if ($datecnt <= $currentdate) {
                //echo $quota_ov_b2b_rev . " " . date('t', strtotime($start_Dt_tmp)) . " " . $quota_one_day . "<br>";
                $quota_to_date = $quota_to_date + $quota_one_day;
            }
        }

        $monthly_qtd_b2b_rev = $quota_to_date;
    }

    if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
        $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 30;
        $dt_month_value_1 = date('m');
        db();
        if ($current_qtr == 1) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
            }
        }
        if ($current_qtr == 2) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
            }
        }
        if ($current_qtr == 3) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
            }
        }
        if ($current_qtr == 4) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
            }
        }
        $result_empq = $result_empq ?? '';
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];
            if ($headtxt == "THIS QUARTER") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
            }
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + intval(dateDiff($todays_dt, date($dt_year_value . "-m-01")));
                $days_in_month = 1 + intval(dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01')));
            }
            if ($headtxt == "LAST QUARTER") {
                $days_today = 91;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_b2b_rev_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_b2b_rev_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }

        $quota_in_st_en = isset($quota);
        if ($headtxt == "LAST QUARTER") {
            $monthly_qtd_b2b_rev = (isset($days_today) * isset($quota)) / 91;
        } else {
            $monthly_qtd_b2b_rev = $quota_mtd;
        }
    }

    if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 0;
        $dt_month_value_1 = date('m');
        db();
        $result_empq = db_query("Select quota_month, quota, deal_quota FROM $employee_quota_tbl_stretch_g WHERE quota_year = " . $dt_year_value . " order by quota_month");
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];

            if ($headtxt == "THIS YEAR") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
            }
            if ($headtxt == "LAST TO LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + intval(dateDiff($todays_dt, date($dt_year_value . "-m-01")));
                $days_in_month = 1 + intval(dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01')));
            }
            if ($headtxt == "LAST YEAR") {
                $days_today = 365;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_b2b_rev_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_b2b_rev_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }
        $quota_in_st_en = isset($quota);

        if ($headtxt == "LAST YEAR") {
            $monthly_qtd_b2b_rev = (isset($days_today) * isset($quota)) / 365;
        } else {
            $monthly_qtd_b2b_rev = $quota_mtd;
        }
    }
    //This is for B2B STRETCH table B2B Revenue	calculation

    //This is for B2B Revenue STRETCH row in table
    echo "<tr><td bgColor='$tbl_color' align ='left'>B2B Revenue STRETCH</td>";
    echo "<td bgColor='$tbl_color' align ='right'>";

    echo "$" . number_format($quota_ov_b2b_rev, 0);
    echo "</td><td bgColor='$tbl_color' align = 'right'>";
    echo "$" . number_format($monthly_qtd_b2b_rev, 0);
    if ($revenue_sales_b2b >= isset($monthly_qtd_b2b_rev)) {
        $color = "green";
    } elseif (($revenue_sales_b2b < isset($monthly_qtd_b2b_rev) && $revenue_sales_b2b > 0) || $revenue_sales_b2b < 0) {
        $color = "red";
    } else {
        $color = "black";
    };

    echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

    echo "<a href='#' onclick='load_div(787" . $unqid . isset($MGArraytmp2["empid"]) . "); return false;'><font color='" . $color . "'>$" . number_format($revenue_sales_b2b, 0) . "</font></a>";
    echo "<span id='787" . $unqid . isset($MGArraytmp2["empid"]) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_b2b_rev . "</span>";

    echo "</font></td>";
    echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>";
    if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
        echo number_format($revenue_sales_b2b * 100 / $quota_ov_b2b_rev, 2);
    } else {
        echo number_format($revenue_sales_b2b * 100 / $monthly_qtd_b2b_rev, 2);
    }
    echo "%</font></td>";
    echo "<td bgColor='$tbl_color' align='right'>";
    //if ($revenue_sales_b2b > 0 && $revenue_sales_b2b > 0){
    //	echo "<font color='" . $per_val_color . "'>" .  $per_val . "%";
    //}	
    echo "</td>";

    if ($ttylyesno == "ttylyes") {
        echo "<td bgColor='$tbl_color' align = 'right'>";

        //echo "<a href='#' onclick='load_div(886". $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summ_ttly_sales_rev,0) . "</font></a>";
        //echo "<span id='886". $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_ttly_b2b_rev . "</span>";

        echo "</td>";
    }
    echo "</tr>";
    echo "<tr><td bgColor='$tbl_color' colspan=7><hr></td></tr>";


    //For B2B Gross Profit
    $tot_monthper = $tot_quota_mtd !== 0 ? 100 * $tot_quotaactual_mtd / $tot_quota_mtd : 0;
    if ($tot_monthper >= 100) {
        $color = "green";
    } elseif ($tot_monthper >= 80) {
        $color = "red";
    } else {
        $color = "black";
    };

    $quota_ov = 0;
    if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
        $sql_ovdata = "SELECT quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov = $rowemp_ovdata["quota"];
        }
    }

    if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "B2B Leaderboard") {
        $begin = new DateTime($start_Dt);
        $end   = new DateTime($end_Dt);
        $currentdate  = new DateTime(date("Y-m-d"));
        $quota = 0;
        $quota_to_date = 0;
        for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
            $start_Dt_tmp = $datecnt->format("Y-m-d");
            $quota_mtd = 0;
            $newsel = "Select quota_month, quota , deal_quota, quota_year  FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
        }
        if ($current_qtr == 2) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
        }
        if ($current_qtr == 3) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
        }
        if ($current_qtr == 4) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
        }
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 30;
        $quota = 0;
        $dt_month_value_1 = date('m');
        $result_empq = $result_empq ?? '';
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = $quota + $rowemp_empq["quota"];
        }

        $quota_ov = $quota;
    }

    if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
        $sql_ovdata = "SELECT quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value;
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
        db();
        if ($current_qtr == 1) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
            }
        }
        if ($current_qtr == 2) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
            }
        }
        if ($current_qtr == 3) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
            }
        }
        if ($current_qtr == 4) {
            $result_empq = db_query("Select quota_month, quota , deal_quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
            }
        }

        $result_empq = $result_empq ?? '';
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];
            if ($headtxt == "THIS QUARTER") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
            }
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + intval(dateDiff($todays_dt, date($dt_year_value . "-m-01")));
                $days_in_month = 1 + intval(dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01')));
            }
            if ($headtxt == "LAST QUARTER") {
                $days_today = 91;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }

        $quota_in_st_en = isset($quota);
        if ($headtxt == "LAST QUARTER") {
            $monthly_qtd = (isset($days_today) * isset($quota)) / 91;
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
        $result_empq = db_query("Select quota_month, quota, deal_quota FROM $employee_quota_overall_sales_gp WHERE  quota_year = " . $dt_year_value . " order by quota_month");
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];

            if ($headtxt == "THIS YEAR") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
            }
            if ($headtxt == "LAST TO LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + intval(dateDiff($todays_dt, date($dt_year_value . "-m-01")));
                $days_in_month = 1 + intval(dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01')));
            }
            if ($headtxt == "LAST YEAR") {
                $days_today = 365;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }
        $quota_in_st_en = isset($quota);

        if ($headtxt == "LAST YEAR") {
            $monthly_qtd = (isset($days_today) * isset($quota)) / 365;
        } else {
            $monthly_qtd = $quota_mtd;
        }
    }

    echo "<tr><td bgColor='$tbl_color' align ='left'>B2B Gross Profit</td>";
    echo "	<td bgColor='$tbl_color' align ='right'>";

    echo "$" . number_format($quota_ov, 0);
    echo "</td><td bgColor='$tbl_color' align = 'right'>";
    $monthly_qtd = $monthly_qtd ?? "";
    echo "$" . number_format($monthly_qtd, 0);
    if ($tot_quotaactual_mtd >= isset($monthly_qtd)) {
        $color = "green";
    } elseif (($tot_quotaactual_mtd < isset($monthly_qtd) && $tot_quotaactual_mtd > 0) || $tot_quotaactual_mtd < 0) {
        $color = "red";
    } else {
        $color = "black";
    };

    echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

    echo "<a href='#' onclick='load_div(9911" . $unqid . isset($MGArraytmp2["empid"]) . "); return false;'><font color='" . $color . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
    echo "<span id='9911" . $unqid . isset($MGArraytmp2["empid"]) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans . "</span>";

    echo "</font></td>";
    echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>";
    if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
        echo number_format($tot_quotaactual_mtd * 100 / $quota_ov, 2);
    } else {
        $monthly_qtd = $monthly_qtd ?? "";
        echo number_format($tot_quotaactual_mtd * 100 / $monthly_qtd, 2);
    }
    echo "%</font></td>";
    $tot_quotaactual_mtd_numeric = is_numeric($tot_quotaactual_mtd) ? $tot_quotaactual_mtd : 0;
    $revenue_sales_b2b_numeric = is_numeric($revenue_sales_b2b) ? $revenue_sales_b2b : 0;
    $per_val = number_format((float)str_replace(",", "", number_format($tot_quotaactual_mtd_numeric, 0)) * 100 / (float)str_replace(",", "", number_format($revenue_sales_b2b_numeric, 0)), 2);
    if ($per_val >= 30) {
        $per_val_color = "green";
    } else {
        $per_val_color = "red";
    }

    echo "<td bgColor='$tbl_color' align='right'><font color='" . $per_val_color . "'>";
    if ($tot_quotaactual_mtd > 0 && $revenue_sales_b2b > 0) {
        echo $per_val . "%";
    }
    echo "</td>";

    if ($ttylyesno == "ttylyes") {
        echo "<td bgColor='$tbl_color' align = 'right'>";

        echo "<a href='#' onclick='load_div(88" . $unqid . isset($MGArraytmp2["empid"]) . "); return false;'><font color=black>$" . number_format($summ_ttly, 0) . "</font></a>";
        echo "<span id='88" . $unqid . isset($MGArraytmp2["empid"]) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . isset($lisoftrans_ttly) . "</span>";

        echo "</font></td>";
    }
    echo "</tr>";


    leadertbl_stretch($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid, $ttylyesno, $timeperiod, $dashboard_view);
}


// New function for Stretch
function leadertbl_stretch(
    string $start_Dt,
    string $end_Dt,
    string $headtxt,
    string $tilltoday,
    int $currentyr,
    string $tbl_head_color,
    string $tbl_color,
    string $po_flg,
    string $unqid,
    string $ttylyesno,
    string $timeperiod,
    string $dashboard_view,
    string $activity_tracker_flg = "no"
): void {
    db();



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


    $employee_quota_tbl = " employee_quota ";
    $employee_quota_overall_stretch_gprofit = " employee_quota_overall_stretch_gprofit ";


    if ($dashboard_view == "Pallet Sales") {
        $employee_quota_tbl = " employee_quota_pallet_sale ";
        $employee_quota_overall_stretch_gprofit = " employee_quota_overall_pallet_gprofit_stretch ";
    }

    if ($dashboard_view == "UCBZW") {
        $employee_quota_tbl = " employee_quota_gprofit ";
        //$employee_quota_overall_stretch_gprofit = " employee_quota_overall_stretch_gprofit ";
    }

    $quota_ov = 0;
    if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
        $sql_ovdata = "SELECT quota FROM $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
        db();
        $result_ovdata = db_query($sql_ovdata);
        while ($rowemp_ovdata = array_shift($result_ovdata)) {
            $quota_ov = $rowemp_ovdata["quota"];
        }
    }

    if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "GMI Leaderboard") {
        $begin = new DateTime($start_Dt);
        $end   = new DateTime($end_Dt);
        $currentdate  = new DateTime(date("Y-m-d"));
        $quota = 0;
        $quota_to_date = 0;
        for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
            $start_Dt_tmp = $datecnt->format("Y-m-d");
            $quota_mtd = 0;
            $newsel = "Select quota_month, quota , deal_quota, quota_year  from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
            $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
        }
        if ($current_qtr == 2) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
        }
        if ($current_qtr == 3) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
        }
        if ($current_qtr == 4) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
        $sql_ovdata = "SELECT quota FROM $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value;
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

    if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
        $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
        $quota_mtd = 0;
        $donot_add = "";
        $days_in_month = 30;
        $dt_month_value_1 = date('m');
        db();
        if ($current_qtr == 1) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
            }
        }
        if ($current_qtr == 2) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
            }
        }
        if ($current_qtr == 3) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
            }
        }
        if ($current_qtr == 4) {
            $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
            } else {
                $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
            }
        }

        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];
            if ($headtxt == "THIS QUARTER") {

                $todays_dt = date('m/d/Y');
                $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
            }
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + intval(dateDiff($todays_dt, date($dt_year_value . "-m-01")));
                $days_in_month = 1 + intval(dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01')));
            }
            if ($headtxt == "LAST QUARTER") {
                $days_today = 91;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }

        $quota_in_st_en = isset($quota);
        if ($headtxt == "LAST QUARTER") {
            $monthly_qtd = (isset($days_today) * isset($quota)) / 91;
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
        $result_empq = db_query("Select quota_month, quota, deal_quota from $employee_quota_overall_stretch_gprofit WHERE quota_year = " . $dt_year_value . " order by quota_month");
        while ($rowemp_empq = array_shift($result_empq)) {
            $quota = isset($quota) + $rowemp_empq["quota"];
            //$deal_quota = $rowemp_empq["deal_quota"];

            if ($headtxt == "THIS YEAR") {
                $todays_dt = date('m/d/Y');
                $days_today = 1 + intval(dateDiff($todays_dt, date('Y-m-01')));
                $days_in_month = 1 + intval(dateDiff(date('Y-m-t'), date('Y-m-01')));
            }
            if ($headtxt == "LAST TO LAST YEAR") {
                $todays_dt = date($dt_year_value . "-m-d");
                $days_today = 1 + intval(dateDiff($todays_dt, date($dt_year_value . "-m-01")));
                $days_in_month = 1 + intval(dateDiff(date($dt_year_value . '-m-t'), date($dt_year_value . '-m-01')));
            }
            if ($headtxt == "LAST YEAR") {
                $days_today = 365;
            }
            if ($donot_add == "") {
                if ($rowemp_empq["quota_month"] <= $dt_month_value_1) {
                    if ($rowemp_empq["quota_month"] == $dt_month_value_1) {
                        $donot_add = "yes";
                        $monthly_qtd_1 = (isset($days_today) * $rowemp_empq["quota"]) / $days_in_month;

                        $quota_mtd = $quota_mtd + $monthly_qtd_1;
                    } else {
                        $quota_mtd = $quota_mtd + $rowemp_empq["quota"];
                    }
                }
            }
        }
        $quota_in_st_en = isset($quota);

        if ($headtxt == "LAST YEAR") {
            $monthly_qtd = (isset($days_today) * isset($quota)) / 365;
        } else {
            $monthly_qtd = $quota_mtd;
        }
    }

    $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
    if ($po_flg == "yes") {
        //$lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
    } else {
        //$lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
        $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>G.Profit Amount</td><td class='txtstyle_color'>Profit Margin</td></tr>";
    }

    if ($tilltoday == "Y") {
        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
    } else {
        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
    }

    if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1 and Leaderboard = 'B2B' AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
    }
    //if ($headtxt == "THIS YEAR") {
    //echo $sqlmtd . "<br>";
    //}
    $resultmtd = db_query($sqlmtd);
    $summtd_SUMPO = 0;
    $summtd_dealcnt = 0;
    $sr_no = 0;
    $revenue_sales_b2b_str = 0;
    while ($summtd = array_shift($resultmtd)) {
        $nickname = "";
        $industry_nm = "";
        $industry_id = "";
        if ($summtd["b2bid"] > 0) {
            db_b2b();
            $sql = "SELECT nickname, company, shipCity, shipState, industry_id FROM companyInfo where ID = " . $summtd["b2bid"];
            $result_comp = db_query($sql);
            while ($row_comp = array_shift($result_comp)) {
                $industry_id = $row_comp["industry_id"];
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

            $sql = "SELECT industry FROM industry_master where industry_id = '" . $industry_id . "'";
            $result_comp = db_query($sql);
            while ($row_comp = array_shift($result_comp)) {
                $industry_nm = $row_comp["industry"];
            }
            db();
        } else {
            $nickname = $summtd["warehouse_name"];
        }

        $finalpaid_amt = 0;
        $finalpaid_amt_discount = 0;

        $inv_amt_totake = 0;
        if ($finalpaid_amt > 0) {
            $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
        }
        if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
            $inv_amt_totake = floatval(str_replace(",", "", $summtd["invsent_amt"])) - floatval(str_replace(",", "", strval($finalpaid_amt_discount)));
        }
        if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
            $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
        }
        $revenue_sales_b2b_str = $revenue_sales_b2b_str + $inv_amt_totake;

        $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
			FROM loop_transaction_buyer 
			LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
			INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
			WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
			and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
        db();
        $resB2bCogs = db_query($qryB2bCogs);

        $estimated_cost = 0;
        while ($resB2bCogs_row = array_shift($resB2bCogs)) {
            $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
        }
        $summtd_SUMPO = $summtd_SUMPO + str_replace(",", "", number_format(($inv_amt_totake - $estimated_cost), 0));

        $summtd_dealcnt = $summtd_dealcnt + 1;

        $po_delivery_dt = "";
        if ($summtd["po_delivery_dt"] != "") {
            $po_delivery_dt = date("m/d/Y", strtotime($summtd["po_delivery_dt"]));
        }

        $actual_delivery_date = "";
        $sql = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $summtd["id"];
        $sql_res = db_query($sql);
        while ($row = array_shift($sql_res)) {
            $actual_delivery_date = $row["bol_shipment_received_date"];
        }

        $sr_no = $sr_no + 1;
        if ($po_flg == "yes") {
            //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $summtd["warehouse_id"] ."&rec_id=" . $summtd["id"] . "&display=buyer_view'>". $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"],0) . "</td></tr>";

            $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";
        } else {
            //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $summtd["warehouse_id"] ."&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>". $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost),0) . "</td></tr>";

            $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td>
				<td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
				<td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td>
                <td bgColor='#E4EAEB' align='right'>" . number_format((floatval(str_replace(",", "", number_format($inv_amt_totake - $estimated_cost, 0))) * 100) / floatval(str_replace(",", "", number_format($inv_amt_totake, 0))), 0) . "%</td>
                </tr>";
        }
    }
    if ($summtd_SUMPO > 0) {
        $tot_quotaactual_mtd = $summtd_SUMPO;
        $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
			<td bgColor='#ABC5DF' align='right'>$" . number_format($revenue_sales_b2b_str, 0) . "</td>
			<td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td>
            <td bgColor='#ABC5DF' align='right'>" . number_format((floatval(str_replace(",", "", number_format($summtd_SUMPO, 0))) * 100) / floatval(str_replace(",", "", number_format($revenue_sales_b2b_str, 0))), 0) . "%</td>
			
			</tr>";
    }
    $lisoftrans .= "</table></span>";

    //This Time Last Year TTLY
    $summ_ttly = 0;
    $tot_quotaactual_mtd_ttly = 0;
    $summ_ttly_sales_rev = 0;
    if ($ttylyesno == "ttylyes") {
        $start_Date = strtotime('-1 year', strtotime($start_Dt));
        //if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
        //	$end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));		
        //}else{
        $end_Date = strtotime('-1 year', strtotime($end_Dt));
        //}

        $start_Dt_ttly = Date('Y-m-d', $start_Date);
        $end_Dt_ttly = Date('Y-m-d', $end_Date);

        $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td>
			<td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>G.Profit Amount</td><td class='txtstyle_color'>Profit Margin</td></tr>";
        if ($tilltoday == "Y") {
            $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
        } else {
            $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
        }

        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $nickname = "";
            $industry_nm = "";
            $industry_id = "";
            if ($summtd["b2bid"] > 0) {
                db_b2b();
                $sql = "SELECT nickname, company, shipCity, shipState, industry_id FROM companyInfo where ID = " . $summtd["b2bid"];
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_id = $row_comp["industry_id"];
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

                $sql = "SELECT industry FROM industry_master where industry_id = '" . $industry_id . "'";
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_nm = $row_comp["industry"];
                }
                db();
            } else {
                $nickname = $summtd["warehouse_name"];
            }

            $finalpaid_amt = 0;
            $finalpaid_amt_discount = 0;

            $inv_amt_totake = 0;
            if ($finalpaid_amt > 0) {
                $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                $inv_amt_totake = (float)str_replace(",", "", (string)$summtd["invsent_amt"]) - (float)str_replace(",", "", (string)$finalpaid_amt_discount);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
            }
            $summ_ttly_sales_rev = $summ_ttly_sales_rev + $inv_amt_totake;

            $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            db();
            $resB2bCogs = db_query($qryB2bCogs);

            $estimated_cost = 0;
            while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
            }
            $summ_ttly = $summ_ttly + ($inv_amt_totake - $estimated_cost);

            $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td>
				<td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
				<td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td>
                <td bgColor='#E4EAEB' align='right'>" . number_format((float)str_replace(",", "", number_format($inv_amt_totake - $estimated_cost, 0)) * 100 / (float)str_replace(",", "", number_format($inv_amt_totake, 0)), 0) . "%</td>
                </tr>";
        }
        if ($summ_ttly > 0) {
            $tot_quotaactual_mtd_ttly = $summ_ttly;
            $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
				<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_sales_rev, 0) . "</td>
				<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td>
                <td bgColor='#ABC5DF' align='right'>" . number_format((float)str_replace(",", "", number_format($summ_ttly, 0)) * 100 / (float)str_replace(",", "", number_format($summ_ttly_sales_rev, 0)), 0) . "%</td>
				</tr>";
        }
        $lisoftrans_ttly .= "</table></span>";
    }
    //This Time Last Year TTLY

    $rev_lastyr_tilldt = 0;
    if ($headtxt == "LAST YEAR") {
        $dt_year_value_1 = date('Y') - 1;
        $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
        $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');
        $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0  and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $finalpaid_amt = 0;
            $finalpaid_amt_discount = 0;

            $inv_amt_totake = 0;
            if ($finalpaid_amt > 0) {
                $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                $inv_amt_totake = floatval(str_replace(",", "", (string)$summtd["invsent_amt"])) - floatval(str_replace(",", "", (string)$finalpaid_amt_discount));
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
            }

            $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            db();
            $resB2bCogs = db_query($qryB2bCogs);

            $estimated_cost = 0;
            while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
            }
            $rev_lastyr_tilldt = $rev_lastyr_tilldt + ($inv_amt_totake - $estimated_cost);
        }
        //$tot_quotaactual_mtd = $rev_lastyr_tilldt;
    }

    if ($headtxt == "GMI Leaderboard") {
        echo "<tr><td bgColor='$tbl_color' align ='left'>B2B Gross Profit STRETCH</td>";
        echo "<td bgColor='$tbl_color' align = 'right'>";
        echo "$" . number_format($quota_ov, 0);
        echo "</td>";
        if ($tot_quotaactual_mtd >= $quota_ov) {
            $color = "green";
        } elseif ($tot_quotaactual_mtd < $quota_ov) {
            $color = "red";
        } else {
            $color = "black";
        };

        echo "<td bgColor='$tbl_color' align = 'right'><a href='#' onclick='load_div(919" . $unqid . isset($MGArraytmp2["empid"]) . "); return false;'><font color='" . $color . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
        echo "<span id='919" . $unqid . isset($MGArraytmp2["empid"]) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans . "</span></td>";

        echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>" . number_format(($tot_quotaactual_mtd / $quota_ov) * 100, 2);
        echo "%</font></td>";

        $per_val = number_format((float)str_replace(",", "", number_format($tot_quotaactual_mtd, 0)) * 100 / (float)str_replace(",", "", number_format($revenue_sales_b2b_str, 0)), 2);
        if ($per_val >= 30) {
            $per_val_color = "green";
        } else {
            $per_val_color = "red";
        }

        //if ($tot_quotaactual_mtd > 0 && $revenue_sales_b2b_str > 0){
        //	echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $per_val_color . "'>" . $per_val . "%</font></td>";
        //}else{
        echo "<td bgColor='$tbl_color' align = 'right'></td>";
        //}							
        echo "</tr>";

        //} else if ($po_flg == "yes"){
        //	echo "</table>";
    } else {

        //echo number_format($tot_quota_deal_mtd, 0);
        echo "<tr><td bgColor='$tbl_color' align ='left'>B2B Gross Profit STRETCH</td>";
        echo "<td bgColor='$tbl_color' align = 'right'>";
        if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
            echo "$" . number_format($quota_ov, 0);
            echo "</td><td bgColor='$tbl_color' align = 'right'>";
            $monthly_qtd = $monthly_qtd ?? "";
            echo "$" . number_format($monthly_qtd, 0);
            if ($tot_quotaactual_mtd >= isset($monthly_qtd)) {
                $color = "green";
            } elseif ($tot_quotaactual_mtd < isset($monthly_qtd) && $tot_quotaactual_mtd > 0) {
                $color = "red";
            } else {
                $color = "black";
            };
        } else {
            if ($tot_quotaactual_mtd >= $quota_ov) {
                $color = "green";
            } elseif ($tot_quotaactual_mtd < $quota_ov) {
                $color = "red";
            } else {
                $color = "black";
            };
            echo "$" . number_format($quota_ov, 0);
        }
        echo "</strong></td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

        echo "<a href='#' onclick='load_div(99" . $unqid . isset($MGArraytmp2["empid"]) . "); return false;'><font color='" . $color . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
        echo "<span id='99" . $unqid . isset($MGArraytmp2["empid"]) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans . "</span>";

        echo "</font></td>";
        echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align='right'><font color='" . $color . "'>";
        if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            echo number_format($tot_quotaactual_mtd * 100 / $quota_ov, 2);
        } else {

            $monthly_qtd = $monthly_qtd ?? "";
            //echo number_format($tot_quotaactual_mtd*100/$tot_quota_mtd_TD,2);
            echo number_format($tot_quotaactual_mtd * 100 / $monthly_qtd, 2);
        }
        echo "%</font></td>";

        echo "<td bgColor='$tbl_color' align='right'>&nbsp;</td>";

        if ($headtxt == "LAST YEAR") {
            //echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($rev_lastyr_tilldt,0);
            //echo "</font></td>";
            //echo "<td bgColor='$tbl_color' align='right'>&nbsp;</td>";
        }

        if ($ttylyesno == "ttylyes") {
            echo "<td bgColor='$tbl_color' align = 'right'>&nbsp;";

            //echo "<a href='#' onclick='load_div(88". $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summ_ttly,0) . "</font></a>";
            //echo "<span id='88". $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_ttly . "</span>";

            echo "</td>";
        }

        echo "</tr>";
    }
}





$unqid = 1;
?>

<table cellSpacing="1" cellPadding="1" border="0" width="900">
    <?php if ($dashboard_view == "Operations" || $dashboard_view == "Executive") {    ?>

    <?php

        $st_date = Date('Y-01-01');
        $end_date = Date('Y-12-31');
        $unqid = $unqid + 1;

        $currentyr = $currentyr ?? "";
        //leadertbl_all($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', strval($unqid), intval($currentyr), "Year", $dashboard_view);
        leadertbl_all($st_date, $end_date, "THIS YEAR", 'Y', intval($currentyr), '#ABC5DF', '#E4EAEB', 'no', strval($unqid), 'Y', "Year", $dashboard_view);

        function getCurrentQuarter(int $timestamp = null): int
        {
            if (!$timestamp) $timestamp = time();
            $day = date('n', $timestamp);
            $quarter = (int)ceil($day / 3);
            return $quarter;
        }

        function getPreviousQuarter(int $timestamp = null): int
        {
            if (!$timestamp) $timestamp = time();
            //$quarter = getCurrentQuarter($timestamp) - 1;
            $quarter = getCurrentQuarter($timestamp);
            if ($quarter < 0) {
                $quarter = 4;
            }
            return $quarter;
        }

        $quarter = getCurrentQuarter();
        $year = date('Y');
        $st_date_n = new DateTime($year . '-' . ($quarter * 3 - 2) . '-1');
        //Get first day of first month of next quarter
        $endMonth = $quarter * 3 + 1;
        if ($endMonth > 12) {
            $endMonth = 1;
            $year++;
        }
        $end_date_n = new DateTime($year . '-' . $endMonth . '-1');

        //Subtract 1 second to get last day of prior month
        $end_date_n->sub(new DateInterval('PT1S'));
        $st_date = $st_date_n->format('Y-m-d');
        $end_date = $end_date_n->format('Y-m-d');

        $unqid = $unqid + 1;
        //leadertbl_all($st_date, $end_date, "THIS QUARTER", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', strval($unqid), intval($currentyr), "Quarter", $dashboard_view);
        leadertbl_all($st_date, $end_date, "THIS QUARTER", 'Y', intval($currentyr), '#ABC5DF', '#E4EAEB', 'no', strval($unqid), 'Y', "Quarter", $dashboard_view);

        $st_date = Date('Y-m-01');
        $end_date = Date('Y-m-t');

        $unqid = $unqid + 1;
        leadertbl_all($st_date, $end_date, "THIS MONTH", 'Y', intval($currentyr), '#ABC5DF', '#E4EAEB', 'no', strval($unqid), 'Y', "Month", $dashboard_view);

        // leadertbl_all($st_date, $end_date, "THIS MONTH", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', strval($unqid), intval($currentyr), "Month", $dashboard_view);

        $time = strtotime(Date('Y-m-d'));

        /*if (date('l',$time) != "Friday") {
					$st_friday = strtotime('last friday', $time);
				} else {
					$st_friday = $time;
				}*/
        if (date('l', $time) != "Sunday") {
            $st_friday = strtotime('last sunday', $time);
        } else {
            $st_friday = $time;
        }

        $st_friday_last = strtotime('-7 days', $st_friday);
        $st_thursday_last = strtotime('+6 days', $st_friday_last);
        $st_thursday = strtotime('+6 days', $st_friday);

        $st_date = Date('Y-m-d', $st_friday);
        $end_date = Date('Y-m-d', $st_thursday);
        $last_yr = $last_yr ?? '';
        $st_date_last_y = Date($last_yr . '-m-d', $st_friday);
        $end_date_last_y = Date($last_yr . '-m-d', $st_thursday);

        $st_friday_last = Date('Y-m-d', $st_friday_last);
        $st_thursday_last = Date('Y-m-d', $st_thursday_last);

        $unqid = $unqid + 1;
        //leadertbl($st_date, $end_date , "THIS WEEK", 'Y', 'Y','#ABC5DF', '#E4EAEB', 'no', $unqid, "Week"); 
        ?>

    <?php

    } else {

    ?>
    <tr>
        <td class="header_td_style" align="center" style="width:50px;"><strong>Time Period</strong></td>
        <td class="header_td_style" align="center"><strong>Revenue Quota</strong></td>
        <td class="header_td_style" align="center"><strong>Quota To Date</strong></td>
        <td class="header_td_style" align="center"><strong>Revenue</strong></td>
        <td class="header_td_style" align="center"><strong>% of Quota</strong></td>
        <td class="header_td_style" align="center"><strong>G.Profit</strong></td>
        <td class="header_td_style" align="center"><strong>Profit Margin</strong></td>
        <td class="header_td_style" align="center"><strong>Revenue TRLY</strong></td>
        <td class="header_td_style" align="center"><strong>G.Profit TRLY</strong></td>
    </tr>
    <?php

        $st_date = Date('Y-01-01');
        $end_date = Date('Y-12-31');
        $unqid = 1;
        $currentyr = $currentyr ?? "";
        // leadertbl($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', strval($unqid), intval($currentyr), "This Year", $initials);
        leadertbl($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', strval($unqid), strval($currentyr), "This Year", $initials);

        function getCurrentQuarter(int $timestamp = null): int
        {
            if (!$timestamp) $timestamp = time();
            $day = date('n', $timestamp);
            $quarter = ceil($day / 3);
            return $quarter;
        }

        function getPreviousQuarter(?int $timestamp = null): int
        {
            if (!$timestamp) $timestamp = time();
            //$quarter = getCurrentQuarter($timestamp) - 1;
            $quarter = getCurrentQuarter($timestamp);
            if ($quarter < 0) {
                $quarter = 4;
            }
            return $quarter;
        }

        $quarter = getCurrentQuarter();
        $year = date('Y');
        $st_date_n = new DateTime($year . '-' . ($quarter * 3 - 2) . '-1');
        //Get first day of first month of next quarter
        $endMonth = $quarter * 3 + 1;
        if ($endMonth > 12) {
            $endMonth = 1;
            $year++;
        }
        $end_date_n = new DateTime($year . '-' . $endMonth . '-1');

        //Subtract 1 second to get last day of prior month
        $end_date_n->sub(new DateInterval('PT1S'));
        $st_date = $st_date_n->format('Y-m-d');
        $end_date = $end_date_n->format('Y-m-d');
        $unqid = $unqid + 1;
        $currentyr = $currentyr ?? [];
        // leadertbl($st_date, $end_date, "THIS QUARTER", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', strval($unqid), intval($currentyr), "This Quarter", $initials);
        leadertbl($st_date, $end_date, "THIS QUARTER", 'Y', intval($currentyr), '#ABC5DF', '#E4EAEB', 'no', strval($unqid), 'Y', "This Quarter", $initials);

        $st_date = Date('Y-m-01');
        $end_date = Date('Y-m-t');
        $currentyr = $currentyr ?? '';
        $unqid = $unqid + 1;
        //leadertbl($st_date, $end_date, "THIS MONTH", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', intval($unqid), strval($currentyr), "This Month", $initials);
        leadertbl($st_date, $end_date, "THIS MONTH", 'Y', intval($currentyr), '#ABC5DF', '#E4EAEB', 'no', strval($unqid), "Y", "This Month", $initials);

        $time = strtotime(Date('Y-m-d'));

        /*if (date('l',$time) != "Friday") {
				$st_friday = strtotime('last friday', $time);
			} else {
				$st_friday = $time;
			}*/
        if (date('l', $time) != "Sunday") {
            $st_friday = strtotime('last sunday', $time);
        } else {
            $st_friday = $time;
        }

        $st_friday_last = strtotime('-7 days', $st_friday);
        $st_thursday_last = strtotime('+6 days', $st_friday_last);
        $st_thursday = strtotime('+6 days', $st_friday);

        $st_date = Date('Y-m-d', $st_friday);
        $end_date = Date('Y-m-d', $st_thursday);
        $last_yr = $last_yr ?? '';
        $st_date_last_y = Date($last_yr . '-m-d', $st_friday);
        $end_date_last_y = Date($last_yr . '-m-d', $st_thursday);

        $st_friday_last = Date('Y-m-d', $st_friday_last);
        $st_thursday_last = Date('Y-m-d', $st_thursday_last);

        $unqid = $unqid + 1;
        //leadertbl($st_date, $end_date , "THIS WEEK", 'Y', 'Y','#ABC5DF', '#E4EAEB', 'no', $unqid, "Week"); 		

        ?>
    <tr>
        <td colspan="9">&nbsp;</td>
    </tr>

    <tr>
        <td class="header_td_style" align="center"><strong>Time Period</strong></td>
        <td class="header_td_style" align="center"><strong>Revenue Quota</strong></td>
        <td class="header_td_style" align="center"><strong>Quota To Date</strong></td>
        <td class="header_td_style" align="center"><strong>Revenue</strong></td>
        <td class="header_td_style" align="center"><strong>% of Quota</strong></td>
        <td class="header_td_style" align="center"><strong>G.Profit</strong></td>
        <td class="header_td_style" align="center"><strong>Profit Margin</strong></td>
    </tr>
    <?php
        $last_yr = date('Y') - 1;
        $st_date = Date($last_yr . '-01-01');
        $end_date = Date($last_yr . '-12-31');
        $unqid = $unqid + 1;
        $currentyr = $currentyr ?? '';
        //  leadertbl($st_date, $end_date, "LAST YEAR", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', strval($unqid), intval($currentyr), "Last Year", $initials);
        leadertbl($st_date, $end_date, "LAST YEAR", 'Y', intval($currentyr), '#ABC5DF', '#E4EAEB', 'no', strval($unqid), 'Y', "Last Year", $initials);

        $current_month = date('m');
        $current_year = date('Y');

        if ($current_month >= 1 && $current_month <= 3) {
            $st_lastqtr = date('Y-m-d', strtotime('1-October-' . ($current_year - 1)));
            $end_lastqtr = date('Y-m-d', strtotime('31-December-' . ($current_year - 1)));
        } else if ($current_month >= 4 && $current_month <= 6) {
            $st_lastqtr = date('Y-m-d', strtotime('1-January-' . $current_year));
            $end_lastqtr = date('Y-m-d', strtotime('31-March-' . $current_year));
        } else  if ($current_month >= 7 && $current_month <= 9) {
            $st_lastqtr = date('Y-m-d', strtotime('1-April-' . $current_year));
            $end_lastqtr = date('Y-m-d', strtotime('30-June-' . $current_year));
        } else  if ($current_month >= 10 && $current_month <= 12) {
            $st_lastqtr = date('Y-m-d', strtotime('1-July-' . $current_year));
            $end_lastqtr = date('Y-m-d', strtotime('30-September-' . $current_year));
        }

        $st_lastqtr_lastyr = date($last_yr . '-m-01', strtotime($st_date));
        $end_lastqtr_lastyr = date($last_yr . '-m-t', strtotime($end_date));

        $unqid = $unqid + 1;
        $st_lastqtr = $st_lastqtr ?? '';
        $end_lastqtr = $end_lastqtr ?? '';
        // leadertbl($st_lastqtr, $end_lastqtr, "LAST QUARTER", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', intval($unqid), "ttylno", "Last Quarter", strval($initials));
        leadertbl($st_lastqtr, $end_lastqtr, "LAST QUARTER", 'Y', intval($currentyr), '#ABC5DF', '#E4EAEB', 'no', strval($unqid), "ttylno", "Last Quarter", strval($initials));

        $st_lastmonth = date("Y-n-j", strtotime("first day of previous month"));
        $end_lastmonth = date("Y-n-j", strtotime("last day of previous month"));

        $st_lastmonth_lastyr = date($last_yr . '-m-01', strtotime($st_date));
        $end_lastmonth_lastyr = date($last_yr . '-m-t', strtotime($end_date));

        $unqid = $unqid + 1;
        // leadertbl($st_lastmonth, $end_lastmonth, "LAST MONTH", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', strval($unqid), intval($currentyr), "Last Month", $initials);
        leadertbl($st_lastmonth, $end_lastmonth, "LAST MONTH", 'Y', intval($currentyr), '#ABC5DF', '#E4EAEB', 'no', strval($unqid), 'Y', "Last Month", $initials);
    }
    ?>
</table>
<br /><br />

<table cellSpacing='1' cellPadding='1' border='0' width='820'>
    <tr>
        <div id="hide_tr">
            <td align='center' class='txtstyle_color' style="background:<?php echo isset($tbl_head_color); ?>">
                <strong>PO
                    Entered this week[
                    <?php echo Date("m/d/Y", strtotime($st_date)) . " - " . Date("m/d/Y", strtotime($end_date)); ?>]
                </strong>&nbsp;&nbsp;<a href="javascript:void(0);"
                    onclick="ex_dash_po_enter('<?php echo $st_date; ?>', '<?php echo $end_date; ?>', 'po_this', '<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
                / <a href="javascript:void(0);" onclick="colp_dash_po_enter('po_this');">Collapse</a>
            </td>
            <td>&nbsp;</td>
            <td align='center' class='txtstyle_color' style="background:<?php echo isset($tbl_head_color); ?>">
                <strong>PO
                    Entered last week [
                    <?php echo Date("m/d/Y", strtotime($st_friday_last)) . " - " . Date("m/d/Y", strtotime($st_thursday_last)); ?>]
                </strong>&nbsp;&nbsp;<a href="javascript:void(0);"
                    onclick="ex_dash_po_enter('<?php echo $st_friday_last; ?>', '<?php echo $st_thursday_last; ?>', 'po_last', '<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
                / <a href="javascript:void(0);" onclick="colp_dash_po_enter('po_last');">Collapse</a>
            </td>
        </div>
    </tr>
    <tr>
        <td>
            <div id="po_entered_display_po_this"></div>
        </td>
        <td>&nbsp;</td>
        <td>
            <div id="po_entered_display_po_last"></div>
        </td>
    </tr>
</table>