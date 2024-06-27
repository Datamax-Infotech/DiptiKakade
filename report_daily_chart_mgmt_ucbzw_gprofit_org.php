<?php

// ini_set("display_errors", "1");

// error_reporting(E_ERROR);

if ($_REQUEST["no_sess"] == "yes") {
} else {
    //require("inc/header_session.php");
}
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
db();

?>
<!DOCTYPE HTML>

<html>

<head>
    <title>UCBZW Share Review Report</title>

    <link rel="stylesheet" href="sorter/style_rep.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
    /*Tooltip style*/
    .tooltip {
        position: relative;
        display: inline-block;

    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 250px;
        background-color: #464646;
        color: #fff;
        text-align: left;
        border-radius: 6px;
        padding: 5px 7px;
        position: absolute;
        z-index: 1;
        top: -5px;
        left: 110%;
        /*white-space: nowrap;*/
        font-size: 12px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
    }

    .tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 35%;
        right: 100%;
        margin-top: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent black transparent transparent;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }

    .fa-info-circle {
        font-size: 9px;
        color: #767676;
    }

    .txtstyle_color {
        font-family: arial;
        font-size: 12;
        height: 16px;
        background: #ABC5DF;
    }

    .txtstyle {
        font-family: arial;
        font-size: 12;
    }

    .style7 {
        font-size: xx-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        background-color: #FFCC66;
    }

    .style5 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        text-align: center;
        background-color: #99FF99;
    }

    .style6 {
        text-align: center;
        background-color: #99FF99;
    }

    .style2 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
    }

    .style3 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
    }

    .style8 {
        text-align: left;
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
    }

    .style11 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: center;
    }

    .style10 {
        text-align: left;
    }

    .style12 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: right;
    }

    .style13 {
        font-family: Arial, Helvetica, sans-serif;
    }

    .style14 {
        font-size: x-small;
    }

    .style15 {
        font-size: small;
    }

    .style16 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        background-color: #99FF99;
    }

    .style17 {
        background-color: #99FF99;
    }

    select,
    input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
        color: #000000;
        font-weight: normal;
    }

    span.infotxt:hover {
        text-decoration: none;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt span {
        position: absolute;
        left: -9999px;
        margin: 20px 0 0 0px;
        padding: 3px 3px 3px 3px;
        z-index: 6;
    }

    span.infotxt:hover span {
        left: 45%;
        background: #ffffff;
    }

    span.infotxt span {
        position: absolute;
        left: -9999px;
        margin: 0px 0 0 0px;
        padding: 3px 3px 3px 3px;
        border-style: solid;
        border-color: black;
        border-width: 1px;
    }

    span.infotxt:hover span {
        margin: 18px 0 0 170px;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt_freight:hover {
        text-decoration: none;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt_freight span {
        position: absolute;
        left: -9999px;
        margin: 20px 0 0 0px;
        padding: 3px 3px 3px 3px;
        z-index: 6;
    }

    span.infotxt_freight:hover span {
        left: 0%;
        background: #ffffff;
    }

    span.infotxt_freight span {
        position: absolute;
        width: 850px;
        overflow: auto;
        height: 300px;
        left: -9999px;
        margin: 0px 0 0 0px;
        padding: 10px 10px 10px 10px;
        border-style: solid;
        border-color: white;
        border-width: 50px;
    }

    span.infotxt_freight:hover span {
        margin: 5px 0 0 50px;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt_freight2:hover {
        text-decoration: none;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt_freight2 span {
        position: absolute;
        left: -9999px;
        margin: 20px 0 0 0px;
        padding: 3px 3px 3px 3px;
        z-index: 6;
    }

    span.infotxt_freight2:hover span {
        left: 0%;
        background: #ffffff;
    }

    span.infotxt_freight2 span {
        position: absolute;
        width: 850px;
        overflow: auto;
        height: 300px;
        left: -9999px;
        margin: 0px 0 0 0px;
        padding: 10px 10px 10px 10px;
        border-style: solid;
        border-color: white;
        border-width: 50px;
    }

    span.infotxt_freight2:hover span {
        margin: 5px 0 0 500px;
        background: #ffffff;
        z-index: 6;
    }

    .black_overlay {
        display: none;
        position: absolute;
    }

    .white_content {
        display: none;
        position: absolute;
        padding: 5px;
        border: 2px solid black;
        background-color: white;
        overflow: auto;
        height: 600px;
        width: 1000px;
        z-index: 1002;
        margin: 0px 0 0 0px;
        padding: 10px 10px 10px 10px;
        border-color: black;
        border-width: 2px;
        overflow: auto;
    }
    </style>
    <script type="text/javascript" src="sorter/jquery-latest.js"></script>
    <script type="text/javascript" src="sorter/jquery.tablesorter.js"></script>

    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();

    function loadmainpg() {
        if (document.getElementById('date_from').value != "" && document.getElementById('date_to').value != "") {
            //document.frmactive.action = "adminpg.php";
        } else {
            alert("Please select date From/To.");
            return false;
        }
    }

    function expand_activity_tracker() {
        if (document.getElementById("table_activity_tracker").style.display == "none") {
            document.getElementById("table_activity_tracker").style.display = "block";
        } else {
            document.getElementById("table_activity_tracker").style.display = "none";
        }

    }

    function collapse_activity_tracker() {
        document.getElementById("table_activity_tracker").style.display = "none";
    }

    function expand_b2b_activity_tracker() {
        if (document.getElementById("table_b2b_activity_tracker").style.display == "none") {
            document.getElementById("table_b2b_activity_tracker").style.display = "block";
        } else {
            document.getElementById("table_b2b_activity_tracker").style.display = "none";
        }

    }

    function collapse_b2b_activity_tracker() {
        document.getElementById("table_b2b_activity_tracker").style.display = "none";
    }

    function load_div(id) {

        var element = document.getElementById(id); //replace elementId with your element's Id.
        var rect = element.getBoundingClientRect();
        var elementLeft, elementTop; //x and y
        var scrollTop = document.documentElement.scrollTop ?
            document.documentElement.scrollTop : document.body.scrollTop;
        var scrollLeft = document.documentElement.scrollLeft ?
            document.documentElement.scrollLeft : document.body.scrollLeft;
        elementTop = rect.top + scrollTop;
        elementLeft = rect.left + scrollLeft;

        document.getElementById("light").innerHTML = document.getElementById(id).innerHTML;
        document.getElementById('light').style.display = 'block';

        document.getElementById('light').style.left = '100px';
        document.getElementById('light').style.top = elementTop + 100 + 'px';
    }


    function close_div() {
        document.getElementById('light').style.display = 'none';
    }
    </script>
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>

<body>

    <div id="light" class="white_content">
    </div>
    <div id="fade" class="black_overlay"></div>
    <br />
    <table border="0">
        <tr>
            <td width="700px" align="center" style="font-size:24pt;">
                <div class="dashboard_heading" style="float: left;">
                    <div style="float: left;">UCBZW Share Review Report</div>
                    <div style="float: left;">&nbsp;
                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="tooltiptext">This report shows the user UCBZW share data itemized by employee
                                and time frame, including a date range selector a the top</span>
                        </div>
                    </div>
                    <div style="height: 13px;">&nbsp;</div>
                </div>
            </td>
            <td width="200px" align="right"><img src="images/image001.jpg" width="70" height="70" /></td>
        </tr>
    </table>

    <?php

    $result_empq = "";
    $date_from_val = "";
    $inv_amount = "";
    function b2ctbl(string $start_Dt, string $end_Dt): string
    {
        db();

        $total_revenue_mtd = 0;
        $query_mtd = "SELECT O.*, OT.value AS order_total FROM orders O INNER JOIN orders_total OT ON O.orders_id=OT.orders_id";
        $query_mtd .= " WHERE class='ot_total' AND (date_purchased>= '" . $start_Dt . "' AND date_purchased <= '" . $end_Dt . "')";
        //echo $query_mtd . "<br>";
        $res = db_query($query_mtd);
        while ($row_mtd = array_shift($res)) {
            $total_revenue_mtd += $row_mtd["order_total"];
        }

        $query_mtd = "SELECT SUM(discount_value) AS T FROM gift_certificate_to_orders WHERE  orders_id > 0 AND(entry_date>= '" . $start_Dt . "' AND entry_date<= '" . $end_Dt . "')";
        //echo $query_mtd . "<br>";
        $res = db_query($query_mtd);
        while ($row_mtd = array_shift($res)) {
            $total_revenue_mtd += $row_mtd["T"];
        }

        return number_format($total_revenue_mtd, 2);
    }

    function leadertbl_purchasing(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        bool $tilltoday,
        int $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        bool $po_flg,
        string $unqid,
        bool $ttylyesno,
        bool $in_dt_range
    ): void {

        global $global_tot_quota_deal_mtd;
        global $global_quota_ov;
        global $global_monthly_qtd;
        global $global_tot_quotaactual_mtd;
        global $global_unqid;
        global $global_empid;
        global $global_empid;
        global $global_rev_lastyr_tilldt;
        global $global_summ_ttly;
        global $global_lisoftrans_ttly;

        db();

        $lisoftrans_detail_list = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'><tr style='height:50px;'>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Transaction ID</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='200px'><u>Company</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>PO Date</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Employee</u></th>";
        $lisoftrans_detail_list .= "<th width='100px' bgColor='$tbl_head_color' align=center><u>PO Amount</u></th>";
        $lisoftrans_detail_list .= "</tr>";

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Purchasing Reps] TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999111";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Purchasing Reps] PO ENTERED THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999222";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Purchasing Reps] PO ENTERED LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999333";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Purchasing Reps] THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999444";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Purchasing Reps] LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Purchasing Reps] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650' id='table9' class='tablesorter' >";
        echo "<thead>";
        if ($headtxt == "B2B Leaderboard") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>% of G.Profit</u></th>";
            echo "	</tr>";
        } elseif ($po_flg == "yes") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='200px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Leads</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Emails</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Calls</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Purchase Orders</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>1st Time Supplier</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>PO Totals</u></th>";
            echo "	</tr>";
        } else {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Deals</u></th>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            }
            if ($headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit to Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            }
            if ($headtxt == "LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit To Date</u></th>";
            }
            echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>%</u></th>";
            if ($ttylyesno == "ttylyes") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>TTLY</u></th>";
            }
            echo "	</tr>";
        }
        echo "</thead>";
        echo "<tbody>";
        $result_empq = "";
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
        if ($in_dt_range == "yes" || $headtxt == "B2B Leaderboard") {
            $sql = "SELECT * FROM loop_employees ORDER BY quota DESC";
        } else {
            $sql = "SELECT * FROM loop_employees WHERE purchasing_leaderboard = 1 ORDER BY quota DESC";
        }
        //echo $sql . "<br>";
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {
            $quota = 0;
            $quotadate = "";
            $deal_quota = 0;
            $monthly_qtd = 0;
            db();
            $result_empq = db_query("Select quota_year, quota_month from employee_quota_purchasing_gprofit where emp_id = " . $rowemp["id"] . " order by quota_year, quota_month limit 1");
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
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_purchasing_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select sum(quota) as sumquota, sum(deal_quota) as deal_quota from employee_quota_purchasing_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value);
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
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_purchasing_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                db();
                if ($current_qtr == 1) {

                    db();
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_purchasing_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                    }
                }
                if ($current_qtr == 2) {
                    db();
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_purchasing_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                    }
                }
                if ($current_qtr == 3) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_purchasing_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                    }
                }
                if ($current_qtr == 4) {

                    db();
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_purchasing_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
                        $days_today = 1 + (int)dateDiff($todays_dt, date('Y-m-01'));
                        $days_in_month = 1 + (int)dateDiff(date('Y-m-t'), date('Y-m-01'));
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
                $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_purchasing_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
                while ($rowemp_empq = array_shift($result_empq)) {
                    $quota = $quota + $rowemp_empq["quota"];
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
                $quota_in_st_en = $quota;

                if ($headtxt == "LAST YEAR") {
                    $monthly_qtd = (isset($days_today) * $quota) / 365;
                } else {
                    $monthly_qtd = $quota_mtd;
                }
            }

            $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'>";
            if ($po_flg == "yes") {
                $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
            } else {
                $lisoftrans .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Supplier</td><td class='txtstyle_color'>Customer</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Quantity</td><td class='txtstyle_color'>Price</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>Profit</td><td class='txtstyle_color'>Margin</td></tr>";
            }

            if ($headtxt == "THIS YEAR") {
                //echo $sqlmtd . "<br>";
            }

            $quote_amount = 0;
            if ($po_flg == "yes") {
                db_b2b();
                $qry = db_query("Select sum(quote_total) as quoteamount from quote where quoteType = 'PO' and quoteDate between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and rep = '" . $rowemp["b2b_id"] . "'");

                while ($row_rs_tmprs = array_shift($qry)) {
                    $quote_amount = $row_rs_tmprs["quoteamount"];
                }
                db();
            }

            $str_box_list_ids = "";
            $summtd_SUMPO = 0;
            $summtd_dealcnt = 0;
            $profit_val_org = 0;
            $str_box_list_transids = "";
            //$qry = db_query("Select id from loop_boxes where owner = " . $rowemp["id"]);
            $qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id  FROM loop_invoice_items WHERE box_item_founder_emp_id=" . $rowemp["id"]);
            while ($row_rs_tmprs = array_shift($qry)) {
                $str_box_list_ids .= $row_rs_tmprs["id"] . ",";
                $str_box_list_transids .= $row_rs_tmprs["trans_rec_id"] . ",";
            }
            if ($str_box_list_ids != "") {
                $str_box_list_ids = substr($str_box_list_ids, 0, strlen($str_box_list_ids) - 1);
            }
            if ($str_box_list_transids != "") {
                $str_box_list_transids = substr($str_box_list_transids, 0, strlen($str_box_list_transids) - 1);
            }

            //echo $headtxt . "<br>" . $rowemp["id"] . "<br>";
            $tot_profit = 0;
            if ($str_box_list_ids != "") {
                $row_no = 0;
                $tmp_trans_id = "";
                $vendor_b2b_rescue = 0;
                $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id FROM loop_bol_tracking inner join loop_invoice_details on 
			loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id inner join loop_transaction_buyer on loop_transaction_buyer.id = 
			loop_bol_tracking.trans_rec_id where loop_transaction_buyer.ignore = 0 and loop_invoice_details.trans_rec_id in (" . $str_box_list_transids . ")
			and loop_invoice_details.timestamp between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");

                while ($row_rs_tmprs = array_shift($qry)) {

                    if ($row_rs_tmprs["trans_rec_id"] != $tmp_trans_id) {
                        $row_no    = 0;
                    } else {
                        $row_no    = $row_no + 1;
                    }

                    $box_qry = "SELECT * FROM loop_boxes WHERE id = " . $row_rs_tmprs["box_id"];
                    $box_res = db_query($box_qry);
                    $boxdesc = "";
                    while ($box_row = array_shift($box_res)) {

                        $boxdesc = round($box_row["blength"]) . " ";
                        if ($box_row["blength_frac"] != "")
                            $boxdesc .= $box_row["blength_frac"] . " ";
                        $boxdesc .= "x " . round($box_row["bwidth"]) . " ";
                        if ($box_row["bwidth_frac"] != "")
                            $boxdesc .= $box_row["bwidth_frac"] . " ";
                        $boxdesc .= "x " . round($box_row["bdepth"]) . " ";
                        if ($box_row["bdepth_frac"] != "")
                            $boxdesc .= $box_row["bdepth_frac"] . " ";
                        $boxdesc .= $box_row["bdescription"];
                        $vendor_b2b_rescue = $box_row["vendor_b2b_rescue"];
                    }

                    $price = 0;
                    $total = 0;
                    $quantity = 0;
                    $invoice_amt = 0;
                    $box_desc = "";
                    $invoice_amt_ind = 0;
                    $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and loop_box_id = '" . $row_rs_tmprs["box_id"] . "'");

                    while ($row_rs_data_main = array_shift($qry_box_main)) {
                        $quantity = $quantity + $row_rs_data_main["quantity"];
                        $price = $row_rs_data_main["price"];
                        $total = $total + str_replace(",", "", $row_rs_data_main["total"]);
                        $box_desc = $row_rs_data_main['description'];
                        $invoice_amt_ind = $invoice_amt_ind + $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                    }

                    if ($quantity > 0) {

                        $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ");
                        while ($row_rs_data_main = array_shift($qry_box_main)) {
                            $invoice_amt += $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                        }

                        $gr_total = str_replace(",", "", $total);

                        $summtd_SUMPO = $summtd_SUMPO + $gr_total;
                        $inv_amount = "";
                        $b2bid = 0;
                        $company_name = "";
                        $wid = 0;
                        $inv_number = "";
                        $double_checked = 0;
                        $virtual_inventory_trans_id = 0;
                        $virtual_inventory_company_id = 0;
                        $q1 = "SELECT loop_warehouse.b2bid, inv_number , inv_amount, loop_warehouse.id as wid, virtual_inventory_company_id, virtual_inventory_trans_id, double_checked, company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id where loop_transaction_buyer.id = " . $row_rs_tmprs["trans_rec_id"];
                        $query = db_query($q1);
                        while ($fetch = array_shift($query)) {
                            $b2bid = $fetch['b2bid'];
                            $wid = $fetch['wid'];
                            $double_checked = $fetch['double_checked'];
                            $company_name = $fetch['company_name'];
                            $inv_number = $fetch['inv_number'];
                            $inv_amount = $fetch["inv_amount"];
                            $virtual_inventory_trans_id = $fetch['virtual_inventory_trans_id'];
                            $virtual_inventory_company_id = $fetch['virtual_inventory_company_id'];
                        }

                        $finalpaid_amt = 0;


                        $invoice_amt = 0;
                        if ($finalpaid_amt > 0) {
                            $invoice_amt = str_replace(",", "", $finalpaid_amt);
                        }
                        if ($finalpaid_amt == 0 && isset($inv_amount) > 0) {
                            $invoice_amt = str_replace(",", "", $inv_amount);
                        }
                        if ($finalpaid_amt == 0 && isset($inv_amount) == 0 && $row_rs_tmprs["loop_inv_amount"] > 0) {
                            $invoice_amt = str_replace(",", "", $row_rs_tmprs["loop_inv_amount"]);
                        }



                        $nickname = get_nickname_val($company_name, $b2bid);
                        $nickname_supplier = "";

                        $supplier_b2bid = 0;
                        $supplier_wid = 0;
                        $supplier_company_name = "";
                        if ($virtual_inventory_trans_id != -1) {
                            $q1 = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, company_name FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id where loop_transaction.id = " . $virtual_inventory_trans_id;
                            $query = db_query($q1);
                            while ($fetch = array_shift($query)) {
                                $supplier_b2bid = $fetch['b2bid'];
                                $supplier_wid = $fetch['wid'];
                                $supplier_company_name = $fetch['company_name'];
                            }

                            $nickname_supplier = get_nickname_val($supplier_company_name, $supplier_b2bid);

                            $supp_nm = $virtual_inventory_trans_id . "-" . $nickname_supplier;
                        } else {
                            $virtual_inventory_trans_id = "";
                            $supp_nm = "";

                            $q1_supp = "SELECT * FROM loop_warehouse where id = " . $vendor_b2b_rescue;
                            db();
                            $query_supp = db_query($q1_supp);
                            while ($fetch_supp = array_shift($query_supp)) {
                                $supp_nm = get_nickname_val($fetch_supp['company_name'], $fetch_supp['b2bid']);

                                $supplier_b2bid = $fetch_supp['b2bid'];
                                $supplier_wid = $fetch_supp['id'];
                                $supplier_company_name = $fetch_supp['company_name'];
                            }
                        }

                        $vendor_pay = 0;
                        $profit_val = 0;
                        $profit_val_per = 0;
                        $profit_val_str = "";
                        if ($double_checked == 0) {
                            $profit_val_str = "style='color:red;'";
                        }

                        $to_quantity = 0;
                        $dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE total > 0 and category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ";
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {
                            $to_quantity = $dt_view_row["quantity"];
                        }

                        $quantity_per = ($quantity * 100) / $to_quantity;

                        $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row_rs_tmprs["trans_rec_id"];
                        db();
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {
                            $vendor_pay += $dt_view_row["estimated_cost"];
                        }

                        $gross_profit_val = $invoice_amt - $vendor_pay;
                        $profit_val = ($quantity_per * $gross_profit_val) / 100;
                        $profit_val_org = $profit_val_org + str_replace(",", "", number_format(($quantity_per * $gross_profit_val) / 100, 0));
                        //echo "profit_val | " . $headtxt . " | " . $rowemp["initials"] . " | " . number_format($profit_val, 0) .  "|" . $profit_val_org . "<br>";
                        $tot_profit = $tot_profit + $profit_val;

                        if ($invoice_amt != 0) {
                            if ($invoice_amt_ind != 0) {
                                $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";
                            } else {
                                $profit_val_per = "0%";
                            }
                        } else {
                            $profit_val_p = 0;
                        }
                        $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                        $profit_val = "$" . number_format($profit_val, 0);

                        $summtd_dealcnt = $summtd_dealcnt + 1;
                        if ($po_flg == "yes") {
                        } else {
                            $lisoftrans .= "<tr>
						<td bgColor='#E4EAEB'>" . $rowemp["initials"] . "</td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
						<td bgColor='#E4EAEB'>" . $quantity . "</td>
						<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
                        <td bgColor='#E4EAEB' align='right'>$" . number_format(floatval(str_replace(",", "", $total)), 0) . "</td>
						<td bgColor='#E4EAEB' $profit_val_str align='right'>" . $profit_val . "</td>
						<td bgColor='#E4EAEB' $profit_val_str >" . $profit_val_per . "</td>
						</tr>";
                        }
                    }
                    $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
                }
            }

            if ($summtd_SUMPO > 0) {
                $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_profit, 0) . "</td><td bgColor='#ABC5DF'>&nbsp;</td></tr>";
            }
            $lisoftrans .= "</table></span>";

            $MGArray = array();
            $summ_ttly = 0;
            $tot_profit = 0;
            $profit_val_org_ttly = 0;
            if ($ttylyesno == "ttylyes") {
                $start_Date = strtotime('-1 year', strtotime($start_Dt));
                if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
                    $end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));
                } else {
                    $end_Date = strtotime('-1 year', strtotime($end_Dt));
                }

                $start_Dt_ttly = Date('Y-m-d', $start_Date);
                $end_Dt_ttly = Date('Y-m-d', $end_Date);

                if ($str_box_list_ids != "") {

                    $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'>";
                    $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Supplier</td><td class='txtstyle_color'>Customer</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Quantity</td><td class='txtstyle_color'>Price</td><td class='txtstyle_color'>G.Profit</td><td class='txtstyle_color'>Profit</td><td class='txtstyle_color'>Margin</td></tr>";

                    $row_no = 0;
                    $tmp_trans_id = "";
                    $vendor_b2b_rescue = 0;
                    $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt_ttly . "' and '" . $end_Dt_ttly . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");
                    while ($row_rs_tmprs = array_shift($qry)) {

                        if ($row_rs_tmprs["trans_rec_id"] != $tmp_trans_id) {
                            $row_no    = 0;
                        } else {
                            $row_no    = $row_no + 1;
                        }

                        $box_qry = "SELECT * FROM loop_boxes WHERE id = " . $row_rs_tmprs["box_id"];
                        $box_res = db_query($box_qry);
                        $boxdesc = "";
                        while ($box_row = array_shift($box_res)) {

                            $boxdesc = $box_row["blength"] . " ";
                            if ($box_row["blength_frac"] != "")
                                $boxdesc .= $box_row["blength_frac"] . " ";
                            $boxdesc .= "x " . $box_row["bwidth"] . " ";
                            if ($box_row["bwidth_frac"] != "")
                                $boxdesc .= $box_row["bwidth_frac"] . " ";
                            $boxdesc .= "x " . $box_row["bdepth"] . " ";
                            if ($box_row["bdepth_frac"] != "")
                                $boxdesc .= $box_row["bdepth_frac"] . " ";
                            $boxdesc .= $box_row["bdescription"];
                            $vendor_b2b_rescue = $box_row["vendor_b2b_rescue"];
                        }


                        $price = 0;
                        $total = 0;
                        $quantity = 0;
                        $invoice_amt = 0;
                        $box_desc = "";
                        $invoice_amt_ind = 0;

                        $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and loop_box_id = '" . $row_rs_tmprs["box_id"] . "'");
                        while ($row_rs_data_main = array_shift($qry_box_main)) {
                            $quantity = $quantity + $row_rs_data_main["quantity"];
                            $price = $row_rs_data_main["price"];
                            $total = $total + str_replace(",", "", $row_rs_data_main["total"]);
                            $box_desc = $row_rs_data_main['description'];
                            $invoice_amt_ind = $invoice_amt_ind + $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                        }

                        if ($quantity > 0) {

                            $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ");
                            while ($row_rs_data_main = array_shift($qry_box_main)) {
                                $invoice_amt += $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                            }

                            $gr_total = str_replace(",", "", $total);

                            // $summ_ttly = $summ_ttly + floatval(str_replace(",", "", number_format($gr_total, 0)));
                            $summ_ttly = $summ_ttly + floatval(str_replace(",", "", number_format((float) $gr_total, 0)));
                            $b2bid = 0;
                            $company_name = "";
                            $wid = 0;
                            $inv_number = "";
                            $double_checked = 0;
                            $virtual_inventory_trans_id = 0;
                            $virtual_inventory_company_id = 0;
                            $q1 = "SELECT loop_warehouse.b2bid, inv_number , inv_amount, loop_warehouse.id as wid, virtual_inventory_company_id, virtual_inventory_trans_id, double_checked, company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id where loop_transaction_buyer.id = " . $row_rs_tmprs["trans_rec_id"];
                            $query = db_query($q1);
                            while ($fetch = array_shift($query)) {
                                $b2bid = $fetch['b2bid'];
                                $wid = $fetch['wid'];
                                $double_checked = $fetch['double_checked'];
                                $company_name = $fetch['company_name'];
                                $inv_number = $fetch['inv_number'];
                                $inv_amount = $fetch["inv_amount"];
                                $virtual_inventory_trans_id = $fetch['virtual_inventory_trans_id'];
                                $virtual_inventory_company_id = $fetch['virtual_inventory_company_id'];
                            }

                            $finalpaid_amt = 0;
                            $inv_amount = $inv_amount ?? "";

                            $invoice_amt = 0;
                            if ($finalpaid_amt > 0) {
                                $invoice_amt = str_replace(",", "", $finalpaid_amt);
                            }
                            if ($finalpaid_amt == 0 && isset($inv_amount) > 0) {
                                $invoice_amt = str_replace(",", "", $inv_amount);
                            }
                            if ($finalpaid_amt == 0 && isset($inv_amount) == 0 && $row_rs_tmprs["loop_inv_amount"] > 0) {
                                $invoice_amt = str_replace(",", "", $row_rs_tmprs["loop_inv_amount"]);
                            }

                            $nickname = get_nickname_val($company_name, $b2bid);
                            $nickname_supplier = "";

                            $supplier_b2bid = 0;
                            $supplier_wid = 0;
                            $supplier_company_name = "";
                            if ($virtual_inventory_trans_id != -1) {
                                $q1 = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, company_name FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id where loop_transaction.id = " . $virtual_inventory_trans_id;
                                $query = db_query($q1);
                                while ($fetch = array_shift($query)) {
                                    $supplier_b2bid = $fetch['b2bid'];
                                    $supplier_wid = $fetch['wid'];
                                    $supplier_company_name = $fetch['company_name'];
                                }

                                $nickname_supplier = get_nickname_val($supplier_company_name, $supplier_b2bid);

                                $supp_nm = $virtual_inventory_trans_id . "-" . $nickname_supplier;
                            } else {
                                $virtual_inventory_trans_id = "";
                                $supp_nm = "";

                                $q1_supp = "SELECT * FROM loop_warehouse where id = " . $vendor_b2b_rescue;
                                db();
                                $query_supp = db_query($q1_supp);
                                while ($fetch_supp = array_shift($query_supp)) {
                                    $supp_nm = get_nickname_val($fetch_supp['company_name'], $fetch_supp['b2bid']);

                                    $supplier_b2bid = $fetch_supp['b2bid'];
                                    $supplier_wid = $fetch_supp['id'];
                                    $supplier_company_name = $fetch_supp['company_name'];
                                }
                            }

                            $vendor_pay = 0;
                            $profit_val = 0;
                            $profit_val_per = 0;
                            $profit_val_str = "";
                            if ($double_checked == 0) {
                                $profit_val_str = "style='color:red;'";
                            }

                            $to_quantity = 0;
                            $dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE total > 0 and category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ";
                            $dt_view_res = db_query($dt_view_qry);
                            while ($dt_view_row = array_shift($dt_view_res)) {
                                $to_quantity = $dt_view_row["quantity"];
                            }

                            $quantity_per = ($quantity * 100) / $to_quantity;

                            if ($po_flg != "yes") {
                                $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row_rs_tmprs["trans_rec_id"];
                                db();
                                $dt_view_res = db_query($dt_view_qry);
                                while ($dt_view_row = array_shift($dt_view_res)) {
                                    $vendor_pay += $dt_view_row["estimated_cost"];
                                }
                            }

                            $invoice_amt = is_numeric($invoice_amt) ? floatval($invoice_amt) : 0;
                            $gross_profit_val = $invoice_amt - $vendor_pay;
                            $profit_val = ($quantity_per * $gross_profit_val) / 100;
                            $tot_profit = $tot_profit + str_replace(",", "", number_format($profit_val, 0));

                            //$profit_val_per = number_format((($profit_val * 100)/$invoice_amt),2) . "%";

                            $profit_val_p = 0;
                            if ($invoice_amt != 0) {
                                $profit_val_p = ($gross_profit_val * 100) / $invoice_amt;
                            }
                            $profit_val_org_ttly = $profit_val_org_ttly + str_replace(",", "", number_format(($quantity_per * $gross_profit_val) / 100, 0));

                            //$profit_val_per = number_format(($profit_val_p * 100)/$quantity_per,2) . "%";
                            $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                            $profit_val = "$" . number_format($profit_val, 0);

                            if ($po_flg == "yes") {
                            } else {
                                $lisoftrans_ttly .= "<tr>
							<td bgColor='#E4EAEB'>" . $rowemp["initials"] . "</td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
							<td bgColor='#E4EAEB'>" . $quantity . "</td>
							<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
                            <td bgColor='#E4EAEB' align='right'>$" . number_format(floatval(str_replace(",", "", $total)), 0) . "</td>
							<td bgColor='#E4EAEB' $profit_val_str align='right'>" . $profit_val . "</td>
							<td bgColor='#E4EAEB' $profit_val_str >" . $profit_val_per . "</td>
							</tr>";
                            }
                        }
                        $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
                    }

                    if ($summtd_SUMPO > 0) {
                        $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_profit, 0) . "</td><td bgColor='#ABC5DF'>&nbsp;</td></tr>";
                    }
                    $lisoftrans_ttly .= "</table></span>";
                }
            }
            //For TTLY
            $MGArray = array();
            $rev_lastyr_tilldt = 0;
            $tot_profit = 0;
            $profit_val_org_rev_lastyr = 0;
            if ($headtxt == "LAST YEAR") {
                $dt_year_value_1 = date('Y') - 1;
                $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
                $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');

                if ($str_box_list_ids != "") {
                    $lisoftrans_lastyr = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'>";
                    $lisoftrans_lastyr .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";

                    $row_no = 0;
                    $tmp_trans_id = 0;
                    $vendor_b2b_rescue = 0;
                    $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");
                    while ($row_rs_tmprs = array_shift($qry)) {

                        if ($row_rs_tmprs["trans_rec_id"] != $tmp_trans_id) {
                            $row_no    = 0;
                        } else {
                            $row_no    = $row_no + 1;
                        }

                        $box_qry = "SELECT * FROM loop_boxes WHERE id = " . $row_rs_tmprs["box_id"];
                        $box_res = db_query($box_qry);
                        $boxdesc = "";
                        while ($box_row = array_shift($box_res)) {

                            $boxdesc = $box_row["blength"] . " ";
                            if ($box_row["blength_frac"] != "")
                                $boxdesc .= $box_row["blength_frac"] . " ";
                            $boxdesc .= "x " . $box_row["bwidth"] . " ";
                            if ($box_row["bwidth_frac"] != "")
                                $boxdesc .= $box_row["bwidth_frac"] . " ";
                            $boxdesc .= "x " . $box_row["bdepth"] . " ";
                            if ($box_row["bdepth_frac"] != "")
                                $boxdesc .= $box_row["bdepth_frac"] . " ";
                            $boxdesc .= $box_row["bdescription"];
                            $vendor_b2b_rescue = $box_row["vendor_b2b_rescue"];
                        }

                        $price = 0;
                        $total = 0;
                        $quantity = 0;
                        $invoice_amt = 0;
                        $box_desc = "";
                        $invoice_amt_ind = 0;

                        $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and loop_box_id = '" . $row_rs_tmprs["box_id"] . "'");
                        while ($row_rs_data_main = array_shift($qry_box_main)) {
                            $quantity = $quantity + $row_rs_data_main["quantity"];
                            $price = $row_rs_data_main["price"];
                            $total = $total + str_replace(",", "", $row_rs_data_main["total"]);
                            $box_desc = $row_rs_data_main['description'];
                            $invoice_amt_ind = $invoice_amt_ind + $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                        }

                        if ($quantity > 0) {
                            $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ");
                            while ($row_rs_data_main = array_shift($qry_box_main)) {
                                $invoice_amt += $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                            }

                            $gr_total = str_replace(",", "", $total);

                            $rev_lastyr_tilldt = $rev_lastyr_tilldt + $gr_total;

                            $b2bid = 0;
                            $company_name = "";
                            $wid = 0;
                            $inv_number = "";
                            $double_checked = 0;
                            $virtual_inventory_trans_id = 0;
                            $virtual_inventory_company_id = 0;
                            $q1 = "SELECT loop_warehouse.b2bid, inv_number , inv_amount, loop_warehouse.id as wid, virtual_inventory_company_id, virtual_inventory_trans_id, double_checked, company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id where loop_transaction_buyer.id = " . $row_rs_tmprs["trans_rec_id"];
                            $query = db_query($q1);
                            while ($fetch = array_shift($query)) {
                                $b2bid = $fetch['b2bid'];
                                $wid = $fetch['wid'];
                                $double_checked = $fetch['double_checked'];
                                $company_name = $fetch['company_name'];
                                $inv_number = $fetch['inv_number'];
                                $inv_amount = $fetch["inv_amount"];
                                $virtual_inventory_trans_id = $fetch['virtual_inventory_trans_id'];
                                $virtual_inventory_company_id = $fetch['virtual_inventory_company_id'];
                            }

                            $finalpaid_amt = 0;
                            $inv_amount = $inv_amount ?? "";

                            $invoice_amt = 0;
                            if ($finalpaid_amt > 0) {
                                $invoice_amt = str_replace(",", "", $finalpaid_amt);
                            }
                            if ($finalpaid_amt == 0 && isset($inv_amount) > 0) {
                                $invoice_amt = str_replace(",", "", $inv_amount);
                            }
                            if ($finalpaid_amt == 0 && isset($inv_amount) == 0 && $row_rs_tmprs["loop_inv_amount"] > 0) {
                                $invoice_amt = str_replace(",", "", $row_rs_tmprs["loop_inv_amount"]);
                            }

                            $nickname = get_nickname_val($company_name, $b2bid);
                            $nickname_supplier = "";

                            $supplier_b2bid = 0;
                            $supplier_wid = 0;
                            $supplier_company_name = "";
                            if ($virtual_inventory_trans_id != -1) {
                                $q1 = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, company_name FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id where loop_transaction.id = " . $virtual_inventory_trans_id;
                                $query = db_query($q1);
                                while ($fetch = array_shift($query)) {
                                    $supplier_b2bid = $fetch['b2bid'];
                                    $supplier_wid = $fetch['wid'];
                                    $supplier_company_name = $fetch['company_name'];
                                }

                                $nickname_supplier = get_nickname_val($supplier_company_name, $supplier_b2bid);

                                $supp_nm = $virtual_inventory_trans_id . "-" . $nickname_supplier;
                            } else {
                                $virtual_inventory_trans_id = "";
                                $supp_nm = "";

                                $q1_supp = "SELECT * FROM loop_warehouse where id = " . $vendor_b2b_rescue;
                                db();
                                $query_supp = db_query($q1_supp);
                                while ($fetch_supp = array_shift($query_supp)) {
                                    $supp_nm = get_nickname_val($fetch_supp['company_name'], $fetch_supp['b2bid']);

                                    $supplier_b2bid = $fetch_supp['b2bid'];
                                    $supplier_wid = $fetch_supp['id'];
                                    $supplier_company_name = $fetch_supp['company_name'];
                                }
                            }

                            $vendor_pay = 0;
                            $profit_val = 0;
                            $profit_val_per = 0;
                            $profit_val_str = "";
                            if ($double_checked == 0) {
                                $profit_val_str = "style='color:red;'";
                            }

                            $to_quantity = 0;
                            $dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE total > 0 and category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ";
                            $dt_view_res = db_query($dt_view_qry);
                            while ($dt_view_row = array_shift($dt_view_res)) {
                                $to_quantity = $dt_view_row["quantity"];
                            }

                            $quantity_per = ($quantity * 100) / $to_quantity;

                            $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row_rs_tmprs["trans_rec_id"];
                            db();
                            $dt_view_res = db_query($dt_view_qry);
                            while ($dt_view_row = array_shift($dt_view_res)) {
                                $vendor_pay += $dt_view_row["estimated_cost"];
                            }
                            $invoice_amt = floatval($invoice_amt);  // Ensure it's a float
                            $vendor_pay = floatval($vendor_pay);    // Ensure it's a float
                            $gross_profit_val = $invoice_amt - $vendor_pay;
                            $profit_val = ($quantity_per * $gross_profit_val) / 100;
                            $tot_profit = $tot_profit + $profit_val;

                            //$profit_val_per = number_format((($profit_val * 100)/$invoice_amt),2) . "%";

                            $profit_val_p = $gross_profit_val * 100 / $invoice_amt;
                            $profit_val_org_rev_lastyr = $profit_val_org_rev_lastyr + str_replace(",", "", number_format(($quantity_per * $gross_profit_val) / 100, 0));

                            //$profit_val_per = number_format(($profit_val_p * 100)/$quantity_per,2) . "%";
                            $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                            $profit_val = "$" . number_format($profit_val, 0);


                            if ($po_flg == "yes") {
                                //$lisoftrans_lastyr .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=". $wid ."&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_view'>". $row_rs_tmprs["trans_rec_id"] . "</a></td><td bgColor='#E4EAEB'>" . $inv_number . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"],0) . "</td></tr>";
                            } else {
                                $lisoftrans_lastyr .= "<tr>
							<td bgColor='#E4EAEB'>" . $rowemp["initials"] . "</td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
							<td bgColor='#E4EAEB'>" . $quantity . "</td>
							<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
                            <td bgColor='#E4EAEB' align='right'>$" . number_format(floatval(str_replace(",", "", $total)), 0) . "</td>
							<td bgColor='#E4EAEB' $profit_val_str align='right'>" . $profit_val . "</td>
							<td bgColor='#E4EAEB' $profit_val_str >" . $profit_val_per . "</td>
							</tr>";
                            }
                        }
                        $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
                    }

                    if ($rev_lastyr_tilldt > 0) {
                        $lisoftrans_lastyr .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($rev_lastyr_tilldt, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_profit, 0) . "</td><td bgColor='#ABC5DF'>&nbsp;</td></tr>";
                    }
                    $lisoftrans_lastyr .= "</table></span>";
                }
            }

            if ($headtxt == "LAST TO LAST YEAR") {
                $monthly_qtd = isset($quota_in_st_en);
            }
            if ($summtd_SUMPO >= $monthly_qtd) {
                $color = "green";
            } elseif ($summtd_SUMPO < $monthly_qtd) {
                $color = "red";
            } else {
                $color = "black";
            };
            if ($monthly_qtd == 0) {
                $color = "black";
            }

            $show_data = "yes";
            if ($headtxt == "B2B Leaderboard") {
                if ($profit_val_org == 0) {
                    $show_data = "no";
                }
            }

            if ($show_data == "yes") {
                $MGArray[] = array(
                    'name' => $rowemp["name"], 'quote_amount' => $quote_amount, 'emp_email' => $rowemp["email"], 'b2bempid' => $rowemp["b2b_id"], 'name_initial' => $rowemp["initials"], 'empid' => $rowemp["id"], 'start_Dt' => $start_Dt, 'end_Dt' => $end_Dt,
                    'deal_count' => $summtd_dealcnt, 'quota' => isset($quota_in_st_en), 'profit_val_org' => $profit_val_org,
                    'quotatodate' => $monthly_qtd, 'po_entered' => $summtd_SUMPO, 'ttly' => $profit_val_org_ttly, 'percent_val' => $color,
                    'rev_lastyr_tilldt' => $profit_val_org_rev_lastyr, 'lisoftrans' => $lisoftrans, 'lisoftrans_ttly' => isset($lisoftrans_ttly), 'lisoftrans_lastyr' => isset($lisoftrans_lastyr)
                );
            }
        }

        //var_dump($MGArray);
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
                    $MGArraysort_I[] = isset($MGArraytmp['companyID']);
                }
                array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
            }
        } else {
            if ($po_flg == "yes") {
                $MGArray = $_SESSION['sortarrayn'];
                $MGArraysort_I = array();

                foreach ($MGArray as $MGArraytmp) {
                    $MGArraysort_I[] = $MGArraytmp['quote_amount'];
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
            $summtd_SUMPO = $MGArraytmp2["profit_val_org"]; //$MGArraytmp2["po_entered"];
            $summtd_ttly = $MGArraytmp2["ttly"];
            //$monthly_percentage = $MGArraytmp2["percent_val"];

            $str_box_list_ids = "";
            $str_box_list_transids = "";
            //$qry = db_query("Select id from loop_boxes where owner = " . $MGArraytmp2["empid"]);
            $qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id FROM loop_invoice_items WHERE box_item_founder_emp_id = " . $MGArraytmp2["empid"]);
            while ($row_rs_tmprs = array_shift($qry)) {
                $str_box_list_ids .= $row_rs_tmprs["id"] . ",";
                $str_box_list_transids .= $row_rs_tmprs["trans_rec_id"] . ",";
            }
            if ($str_box_list_ids != "") {
                $str_box_list_ids = substr($str_box_list_ids, 0, strlen($str_box_list_ids) - 1);
            }
            if ($str_box_list_transids != "") {
                $str_box_list_transids = substr($str_box_list_transids, 0, strlen($str_box_list_transids) - 1);
            }

            //if ($monthly_percentage >= 100 ) { $color_y = "green"; } elseif ($monthly_percentage >= 80 ) { $color_y = "E0B003"; } else { $color_y = "B03030"; };
            $color_y = $MGArraytmp2["percent_val"];

            if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && ($po_flg == "no")) {
                //for the Emp wise order list - View detail list
                $summtd_SUMPO = 0;
                $summtd_dealcnt = 0;
                if ($str_box_list_ids != "") {
                    $row_no = 0;
                    $tmp_trans_id = "";
                    $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id FROM loop_bol_tracking inner join loop_invoice_details on 
				loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id 
				where loop_invoice_details.trans_rec_id in (" . $str_box_list_transids . ") and loop_invoice_details.timestamp between '" . $MGArraytmp2["start_Dt"] . "' AND '" . $MGArraytmp2["end_Dt"] . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");
                    while ($row_rs_tmprs = array_shift($qry)) {

                        if ($row_rs_tmprs["trans_rec_id"] != $tmp_trans_id) {
                            $row_no    = 0;
                        } else {
                            $row_no    = $row_no + 1;
                        }

                        $box_qry = "SELECT * FROM loop_boxes WHERE id = " . $row_rs_tmprs["box_id"];
                        $box_res = db_query($box_qry);
                        $boxdesc = "";
                        while ($box_row = array_shift($box_res)) {

                            $boxdesc = round($box_row["blength"]) . " ";
                            if ($box_row["blength_frac"] != "")
                                $boxdesc .= $box_row["blength_frac"] . " ";
                            $boxdesc .= "x " . round($box_row["bwidth"]) . " ";
                            if ($box_row["bwidth_frac"] != "")
                                $boxdesc .= $box_row["bwidth_frac"] . " ";
                            $boxdesc .= "x " . round($box_row["bdepth"]) . " ";
                            if ($box_row["bdepth_frac"] != "")
                                $boxdesc .= $box_row["bdepth_frac"] . " ";
                            $boxdesc .= $box_row["bdescription"];
                        }

                        $price = 0;
                        $total = 0;
                        //$qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and description = '" . str_replace("'", "\'" , $boxdesc) . "' ");
                        $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and loop_box_id = '" . $row_rs_tmprs["box_id"] . "'");
                        while ($row_rs_data_main = array_shift($qry_box_main)) {
                            $quantity = isset($quantity) + $row_rs_data_main["quantity"];
                            $price = $row_rs_data_main["price"];
                            $total = $total + str_replace(",", "", $row_rs_data_main["total"]);
                        }

                        $gr_total = str_replace(",", "", $total);

                        $summtd_SUMPO = $summtd_SUMPO + $gr_total;
                        $summtd_dealcnt = $summtd_dealcnt + 1;

                        $b2bid = 0;
                        $company_name = "";
                        $wid = 0;
                        $inv_number = "";
                        $po_date = "";
                        $q1 = "SELECT loop_warehouse.b2bid, inv_number ,po_date, loop_warehouse.id as wid, company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id where loop_transaction_buyer.id = " . $row_rs_tmprs["trans_rec_id"];
                        $query = db_query($q1);
                        while ($fetch = array_shift($query)) {
                            $b2bid = $fetch['b2bid'];
                            $wid = $fetch['wid'];
                            $company_name = $fetch['company_name'];
                            $inv_number = $fetch['inv_number'];
                            $po_date = $fetch['po_date'];
                        }

                        $nickname = get_nickname_val($company_name, $b2bid);

                        $gr_total_float = floatval($gr_total);
                        $lisoftrans_detail_list .= "<tr><td bgColor='$tbl_color'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_view'>" . $row_rs_tmprs["trans_rec_id"] . "</a></td><td bgColor='$tbl_color'>" . $nickname . "</td></td><td bgColor='$tbl_color'>" . $po_date . "</td><td bgColor='$tbl_color'>" . $name . "</td><td bgColor='$tbl_color' align='right'>$" . number_format($gr_total_float, 0) . "</td></tr>";

                        $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
                    }
                }
                //for the Emp wise order list - View detail list
            }

            if ($headtxt == "B2B Leaderboard") {
                echo "<tr><td bgColor='$tbl_color' align ='left'>" . $name . "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($monthly_qtd_TD, 0);
                echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color_y . "'>";
                echo "<a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
                echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
                echo "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color_y . "'>";
                if ($monthly_qtd_TD > 0) {
                    echo number_format(($summtd_SUMPO / $monthly_qtd_TD) * 100, 2) . "%";
                }
                echo "</font></td>";
                echo "</tr>";
            } else if ($po_flg == "yes") {

                db();
                $first_time_rec = 0;
                $result_crm = db_query("SELECT count(id) as cnt FROM loop_transaction WHERE id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and employee LIKE '" . $MGArraytmp2["name_initial"] . "' and loop_transaction.ignore < 1 AND transaction_date between '" . $MGArraytmp2["start_Dt"] . "' AND '" . $MGArraytmp2["end_Dt"] . " 23:59:59'");
                while ($rowemp_crm = array_shift($result_crm)) {
                    $first_time_rec = $first_time_rec + $rowemp_crm["cnt"];
                }
                $tot_first_time_rec = isset($tot_first_time_rec) + $first_time_rec;

                db_b2b();
                $sql7 = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $MGArraytmp2["name_initial"] . "' AND  timestamp BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                $result_crm = db_query($sql7);
                $contact_act_ph1 = 0;
                $contact_act_tmp = 0;
                $eml_list = "";
                while ($rowemp_crm = array_shift($result_crm)) {
                    $eml_list .= $rowemp_crm["EmailID"] . ", ";
                    if ($rowemp_crm["type"] ==  "phone") {
                        $contact_act_ph1 = $contact_act_ph1 + 1;
                    }
                    if ($rowemp_crm["type"] ==  "email") {
                        $contact_act_tmp = $contact_act_tmp + 1;
                    }
                }

                $lead_tmp = 0;
                $result_crm = db_query("Select count(ID) as cnt from companyInfo where dateCreated BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and landing_pg_enter_by = '" . $MGArraytmp2["b2bempid"] . "'");
                while ($rowemp_crm = array_shift($result_crm)) {
                    $lead_tmp = $lead_tmp + $rowemp_crm["cnt"];
                }
                $tot_lead_tmp = isset($tot_lead_tmp) + $lead_tmp;

                db_email();
                $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and fromadd = '" . $MGArraytmp2["emp_email"] . "'");
                while ($rowemp_crm = array_shift($result_crm)) {
                    $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                }
                $tot_quota_contact = isset($tot_quota_contact) + $contact_act_tmp;
                $tot_quota_contact_ph = isset($tot_quota_contact_ph) + $contact_act_ph1;

                db_b2b();
                $no_of_pos_cnt = 0;
                $sql7 = "SELECT count(companyID) as cnt FROM quote WHERE quoteType = 'PO' and rep LIKE '" . $MGArraytmp2["b2bempid"] . "' AND qstatus !=2 AND quoteDate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                $result_crm = db_query($sql7);
                while ($rowemp_crm = array_shift($result_crm)) {
                    $no_of_pos_cnt = $rowemp_crm["cnt"];
                }
                $tot_quota_quotes = isset($tot_quota_quotes) + $no_of_pos_cnt;

                $tot_quote_amount = isset($tot_quote_amount) + $MGArraytmp2['quote_amount'];
                db();

                $email_color_code = "";
                $email_color_code2 = "";
                $contact_color_code = "";
                $contact_color_code2 = "";
                if ($headtxt == "LAST WEEK" && $po_flg == "yes") {
                    $week_val = 5;
                }

                if ($headtxt == "THIS WEEK" && $po_flg == "yes") {
                    $week_val = date('w');
                }
                if (isset($week_val) == 1) {
                    if ($contact_act_tmp >= 20) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 20) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 20) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 20) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 2) {
                    if ($contact_act_tmp >= 40) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 40) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 40) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 40) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 3) {
                    if ($contact_act_tmp >= 60) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 60) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 60) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 60) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 4) {
                    if ($contact_act_tmp >= 80) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 80) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 80) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 80) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) >= 5) {
                    if ($contact_act_tmp >= 100) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 100) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 100) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 100) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }

                echo "<tr><td bgColor='$tbl_color' align ='left'>" . $name . "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_purchasing_show_list.php?showlead=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "'>";
                echo number_format($lead_tmp, 0);
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "'>";
                echo $email_color_code . " " . number_format($contact_act_tmp, 0) . " " . $email_color_code2;
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&phone=y&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "'>";
                echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y&b2bempid=" . $MGArraytmp2["b2bempid"] . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
                echo number_format($no_of_pos_cnt, 0);
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_purchasing_show_list.php?showfirsttimerec=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "'>";
                echo number_format($first_time_rec, 0);
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_purchasing_show_list.php?quote_amount=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "'>$";
                echo number_format($MGArraytmp2['quote_amount'], 0);
                echo "</a></td>";
                echo "</tr>";
            } else {
                echo "<tr><td bgColor='$tbl_color' align ='left'>" . $name . "</td><td bgColor='$tbl_color' align = right>";
                echo number_format($monthly_deal_qtd, 0);
                if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format((float)$monthly_qtd, 0);
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format($monthly_qtd_TD, 0);
                } else {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format(floatval($monthly_qtd), 0);
                }

                if ($headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                } else {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                }

                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if ($summtd_SUMPO >= $monthly_qtd) {
                        $color = "green";
                    } elseif ($summtd_SUMPO < $monthly_qtd) {
                        $color = "red";
                    } else {
                        $color = "black";
                    };
                }
                $color_y_new = "black";
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    $monthly_qtd = $monthly_qtd ?? 0;
                    if ($monthly_qtd != 0 && ($summtd_SUMPO * 100 / $monthly_qtd) >= 100) {
                        $color_y_new = "green";
                    } elseif ($monthly_qtd != 0 && $summtd_SUMPO > 0 && $monthly_qtd > 0 && ($summtd_SUMPO * 100 / $monthly_qtd) < 100) {
                        $color_y_new = "red";
                    } elseif ($summtd_SUMPO > 0 && $monthly_qtd == 0) {
                        $color_y_new = "green";
                    } elseif ($summtd_SUMPO == 0 && $monthly_qtd > 0) {
                        $color_y_new = "red";
                    } else {
                        $color_y_new = "black";
                    };
                } else {
                    if (($summtd_SUMPO * 100 / $monthly_qtd_TD) >= 100) {
                        $color_y_new = "green";
                    } elseif (($summtd_SUMPO * 100 / $monthly_qtd_TD) < 100 && $summtd_SUMPO > 0 && $monthly_qtd_TD > 0) {
                        $color_y_new = "red";
                    } elseif ($summtd_SUMPO > 0 && $monthly_qtd_TD == 0) {
                        $color_y_new = "green";
                    } elseif ($summtd_SUMPO == 0 && $monthly_qtd_TD > 0) {
                        $color_y_new = "red";
                    } else {
                        $color_y_new = "black";
                    };
                }

                echo "<a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y_new . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
                echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
                echo "</td>";
                if ($headtxt == "LAST YEAR") {
                    echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($MGArraytmp2["rev_lastyr_tilldt"], 0);
                    echo "</font></td>";
                }
                echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color_y_new . "'>";
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if ($summtd_SUMPO > 0 && $monthly_qtd > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd, 2) . "%";
                    }
                } else {
                    if ($summtd_SUMPO > 0 && $monthly_qtd_TD > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd_TD, 2) . "%";
                    }
                }
                echo "</font></td>";
                if ($ttylyesno == "ttylyes") {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a href='#' onclick='load_div(77" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summtd_ttly, 0) . "</font></a>";
                    echo "<span id='77" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_ttly"] . "</span>";
                    echo "</td>";
                }
                echo "</tr>";
            }

            //$monthly_qtd = number_format($monthly_qtd,0);
            //$summtd_SUMPO = number_format($summtd_SUMPO,0);
            $monthly_deal_qtd = number_format($monthly_deal_qtd, 0);

            $tot_quota_mtd = $tot_quota_mtd + $monthly_qtd;
            $tot_quota_mtd_TD = isset($tot_quota_mtd_TD) + $monthly_qtd_TD;
            $tot_quotaactual_mtd = $tot_quotaactual_mtd + $summtd_SUMPO;
            $tot_quota_deal_mtd = $tot_quota_deal_mtd + $monthly_deal_qtd;
            $tot_rev_lastyr_tilldt = $tot_rev_lastyr_tilldt + $MGArraytmp2["rev_lastyr_tilldt"];
        }
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
            $sql_ovdata = "SELECT quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
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
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $sql_ovdata = "SELECT quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value;
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov = $quota_ov + $rowemp_ovdata["quota"];
            }
        }

        if ($po_flg == "yes") {
            $tot_lead_tmp = $tot_lead_tmp ?? "";
            $tot_quota_contact = $tot_quota_contact ?? "";
            $tot_quota_contact_ph = $tot_quota_contact_ph ?? "";
            $tot_quota_quotes = $tot_quota_quotes ?? "";
            $tot_first_time_rec = $tot_first_time_rec ?? "";
            $tot_quote_amount = $tot_quote_amount ?? "";

            echo "<tr><td bgColor='$tbl_color' align ='right'><strong>Total</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_lead_tmp, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_quota_contact, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_quota_contact_ph, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_quota_quotes, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_first_time_rec, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>$";
            echo number_format($tot_quote_amount, 0);
            echo "</strong></td>";
        }
        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" && $po_flg != "yes") {
            echo "<tr><td>";
            echo "<span id='" . isset($div_id_emp_list) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_detail_list . "</table></span>";
            echo "</td></tr>";
        }
        echo "</table><br>";

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

                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_overall_purchasing_gp where b2borb2c = 'b2bpgp' and quota_year = " . $dt_year_value . " order by quota_month");
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

        $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'>";
        if ($po_flg == "yes") {
            $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        } else {
            $lisoftrans .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Supplier</td><td class='txtstyle_color'>Customer</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Quantity</td><td class='txtstyle_color'>Price</td><td class='txtstyle_color'>G.Profit</td><td class='txtstyle_color'>Profit</td><td class='txtstyle_color'>Margin</td></tr>";
        }

        $emp_id = "";
        $qry = db_query("SELECT id FROM loop_employees WHERE purchasing_leaderboard = 1 ORDER BY quota DESC");
        while ($row_rs_tmprs = array_shift($qry)) {
            $emp_id .= $row_rs_tmprs["id"] . ",";
        }
        if ($emp_id != "") {
            $emp_id = substr($emp_id, 0, strlen($emp_id) - 1);
        }

        $str_box_list_ids = "";
        //$qry = db_query("Select id from loop_boxes where owner in(" . $emp_id . ")");
        $qry = db_query("SELECT distinct(loop_box_id) AS id FROM loop_invoice_items WHERE box_item_founder_emp_id in(" . $emp_id . ")");
        while ($row_rs_tmprs = array_shift($qry)) {
            $str_box_list_ids .= $row_rs_tmprs["id"] . ",";
        }
        if ($str_box_list_ids != "") {
            $str_box_list_ids = substr($str_box_list_ids, 0, strlen($str_box_list_ids) - 1);
        }

        $summtd_SUMPO = 0;
        $summtd_dealcnt = 0;
        $tot_profit = 0;
        if ($str_box_list_ids != "") {
            $row_no = 0;
            $tmp_trans_id = "";
            $vendor_b2b_rescue = 0;
            $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");
            //echo "Select box_id, qty, loop_bol_tracking.trans_rec_id FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and box_id in (" . $str_box_list_ids . ") <br>";
            while ($row_rs_tmprs = array_shift($qry)) {

                if ($row_rs_tmprs["trans_rec_id"] != $tmp_trans_id) {
                    $row_no    = 0;
                } else {
                    $row_no    = $row_no + 1;
                }

                $box_qry = "SELECT * FROM loop_boxes WHERE id = " . $row_rs_tmprs["box_id"];
                $box_res = db_query($box_qry);
                $boxdesc = "";
                while ($box_row = array_shift($box_res)) {

                    $boxdesc = $box_row["blength"] . " ";
                    if ($box_row["blength_frac"] != "")
                        $boxdesc .= $box_row["blength_frac"] . " ";
                    $boxdesc .= "x " . $box_row["bwidth"] . " ";
                    if ($box_row["bwidth_frac"] != "")
                        $boxdesc .= $box_row["bwidth_frac"] . " ";
                    $boxdesc .= "x " . $box_row["bdepth"] . " ";
                    if ($box_row["bdepth_frac"] != "")
                        $boxdesc .= $box_row["bdepth_frac"] . " ";
                    $boxdesc .= $box_row["bdescription"];
                    $vendor_b2b_rescue = $box_row["vendor_b2b_rescue"];
                }

                $price = 0;
                $total = 0;
                $quantity = 0;
                $invoice_amt = 0;
                $invoice_amt_ind = 0;
                $box_desc = "";
                //$qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and description = '" . str_replace("'", "\'" , $boxdesc) . "' ");
                $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and loop_box_id = '" . $row_rs_tmprs["box_id"] . "'");
                while ($row_rs_data_main = array_shift($qry_box_main)) {
                    $quantity = $quantity + $row_rs_data_main["quantity"];
                    $price = $row_rs_data_main["price"];
                    $total = $total + str_replace(",", "", $row_rs_data_main["total"]);
                    $box_desc = $row_rs_data_main['description'];
                    $invoice_amt_ind = $invoice_amt_ind + $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                }

                if ($quantity > 0) {
                    $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ");
                    while ($row_rs_data_main = array_shift($qry_box_main)) {
                        $invoice_amt += $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                    }

                    $gr_total = str_replace(",", "", $total);

                    $summtd_SUMPO = $summtd_SUMPO + $gr_total;

                    $b2bid = 0;
                    $company_name = "";
                    $wid = 0;
                    $inv_number = "";
                    $double_checked = 0;
                    $virtual_inventory_trans_id = 0;
                    $virtual_inventory_company_id = 0;
                    $q1 = "SELECT loop_warehouse.b2bid, inv_number , inv_amount, loop_warehouse.id as wid, virtual_inventory_company_id, virtual_inventory_trans_id, double_checked, company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id where loop_transaction_buyer.id = " . $row_rs_tmprs["trans_rec_id"];
                    $query = db_query($q1);
                    while ($fetch = array_shift($query)) {
                        $b2bid = $fetch['b2bid'];
                        $wid = $fetch['wid'];
                        $double_checked = $fetch['double_checked'];
                        $company_name = $fetch['company_name'];
                        $inv_number = $fetch['inv_number'];
                        $inv_amount = $fetch["inv_amount"];
                        $virtual_inventory_trans_id = $fetch['virtual_inventory_trans_id'];
                        $virtual_inventory_company_id = $fetch['virtual_inventory_company_id'];
                    }

                    $finalpaid_amt = 0;
                    $inv_amount = $inv_amount ?? 0;

                    $invoice_amt = 0;
                    if ($finalpaid_amt > 0) {
                        $invoice_amt = str_replace(",", "", $finalpaid_amt);
                    }
                    if ($finalpaid_amt == 0 && isset($inv_amount) > 0) {
                        $invoice_amt = str_replace(",", "", $inv_amount);
                    }
                    if ($finalpaid_amt == 0 && isset($inv_amount) == 0 && $row_rs_tmprs["loop_inv_amount"] > 0) {
                        $invoice_amt = str_replace(",", "", $row_rs_tmprs["loop_inv_amount"]);
                    }



                    $nickname = get_nickname_val($company_name, $b2bid);
                    $nickname_supplier = "";

                    $supplier_b2bid = 0;
                    $supplier_wid = 0;
                    $supplier_company_name = "";
                    if ($virtual_inventory_trans_id != -1) {
                        $q1 = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, company_name FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id where loop_transaction.id = " . $virtual_inventory_trans_id;
                        $query = db_query($q1);
                        while ($fetch = array_shift($query)) {
                            $supplier_b2bid = $fetch['b2bid'];
                            $supplier_wid = $fetch['wid'];
                            $supplier_company_name = $fetch['company_name'];
                        }

                        $nickname_supplier = get_nickname_val($supplier_company_name, $supplier_b2bid);

                        $supp_nm = $virtual_inventory_trans_id . "-" . $nickname_supplier;
                    } else {
                        $virtual_inventory_trans_id = "";
                        $supp_nm = "";

                        $q1_supp = "SELECT * FROM loop_warehouse where id = " . $vendor_b2b_rescue;
                        db();
                        $query_supp = db_query($q1_supp);
                        while ($fetch_supp = array_shift($query_supp)) {
                            $supp_nm = get_nickname_val($fetch_supp['company_name'], $fetch_supp['b2bid']);

                            $supplier_b2bid = $fetch_supp['b2bid'];
                            $supplier_wid = $fetch_supp['id'];
                            $supplier_company_name = $fetch_supp['company_name'];
                        }
                    }

                    //$box_desc = ""; 
                    $emp_id = "";
                    $q1 = "SELECT bdescription, owner FROM loop_boxes where id = " . $row_rs_tmprs["box_id"];
                    $query = db_query($q1);
                    while ($fetch = array_shift($query)) {
                        //$box_desc = $fetch['bdescription'];
                        $emp_id = $fetch['owner'];
                    }

                    $emp_name = "";
                    if ($emp_id != "") {
                        $q1 = "SELECT initials FROM loop_employees where id = " . $emp_id;
                        $query = db_query($q1);
                        while ($fetch = array_shift($query)) {
                            $emp_name = $fetch['initials'];
                        }
                    } else {
                        $emp_name = "Operations";
                    }

                    $vendor_pay = 0;
                    $profit_val = "TBD";
                    $profit_val_per = "TBD";
                    if ($double_checked == 1) {
                        $to_quantity = 0;
                        $dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE total > 0 and category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ";
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {
                            $to_quantity = $dt_view_row["quantity"];
                        }

                        $quantity_per = ($quantity * 100) / $to_quantity;

                        //
                        $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row_rs_tmprs["trans_rec_id"];
                        db();
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {
                            $vendor_pay += $dt_view_row["estimated_cost"];
                        }

                        $gross_profit_val = $invoice_amt - $vendor_pay;
                        $profit_val = ($quantity_per * $gross_profit_val) / 100;
                        $tot_profit = $tot_profit + $profit_val;

                        //$profit_val_per = number_format((($profit_val * 100)/$invoice_amt),2) . "%";

                        if ($invoice_amt != 0) {
                            $profit_val_p = ($gross_profit_val * 100) / $invoice_amt;
                        }

                        //$profit_val_per = number_format(($profit_val_p * 100)/$quantity_per,2) . "%";
                        $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                        $profit_val = "$" . number_format($profit_val, 0);
                    }

                    $summtd_dealcnt = $summtd_dealcnt + 1;
                    if ($po_flg == "yes") {
                    } else {
                        $lisoftrans .= "<tr>
						<td bgColor='#E4EAEB'>" . $emp_name . "</td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
						<td bgColor='#E4EAEB'>" . $quantity . "</td>
						<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
                        <td bgColor='#E4EAEB' align='right'>$" . number_format((float) str_replace(",", "", $total), 0) . "</td>
						<td bgColor='#E4EAEB' align='right'>" . $profit_val . "</td>
						<td bgColor='#E4EAEB'>" . $profit_val_per . "</td>
						</tr>";
                    }
                }
                $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
            }
        }

        if ($summtd_SUMPO > 0) {
            $tot_quotaactual_mtd = $summtd_SUMPO;
            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_profit, 0) . "</td><td bgColor='#ABC5DF'>&nbsp;</td></tr>";
        }
        $lisoftrans .= "</table></span>";

        //This Time Last Year TTLY
        $summ_ttly = 0;
        $tot_quotaactual_mtd_ttly = 0;
        $tot_profit = 0;
        if ($ttylyesno == "ttylyes") {
            $start_Date = strtotime('-1 year', strtotime($start_Dt));
            if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
                $end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));
            } else {
                $end_Date = strtotime('-1 year', strtotime($end_Dt));
            }

            $start_Dt_ttly = Date('Y-m-d', $start_Date);
            $end_Dt_ttly = Date('Y-m-d', $end_Date);

            $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'>";
            $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Supplier</td><td class='txtstyle_color'>Customer</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Quantity</td><td class='txtstyle_color'>Price</td><td class='txtstyle_color'>G.Profit</td><td class='txtstyle_color'>Profit</td><td class='txtstyle_color'>Margin</td></tr>";

            if ($str_box_list_ids != "") {
                $row_no = 0;
                $tmp_trans_id = "";
                $vendor_b2b_rescue = 0;
                $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt_ttly . "' and '" . $end_Dt_ttly . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");
                while ($row_rs_tmprs = array_shift($qry)) {

                    if ($row_rs_tmprs["trans_rec_id"] != $tmp_trans_id) {
                        $row_no    = 0;
                    } else {
                        $row_no    = $row_no + 1;
                    }

                    $box_qry = "SELECT * FROM loop_boxes WHERE id = " . $row_rs_tmprs["box_id"];
                    $box_res = db_query($box_qry);
                    $boxdesc = "";
                    while ($box_row = array_shift($box_res)) {

                        $boxdesc = $box_row["blength"] . " ";
                        if ($box_row["blength_frac"] != "")
                            $boxdesc .= $box_row["blength_frac"] . " ";
                        $boxdesc .= "x " . $box_row["bwidth"] . " ";
                        if ($box_row["bwidth_frac"] != "")
                            $boxdesc .= $box_row["bwidth_frac"] . " ";
                        $boxdesc .= "x " . $box_row["bdepth"] . " ";
                        if ($box_row["bdepth_frac"] != "")
                            $boxdesc .= $box_row["bdepth_frac"] . " ";
                        $boxdesc .= $box_row["bdescription"];
                        $vendor_b2b_rescue = $box_row["vendor_b2b_rescue"];
                    }

                    $price = 0;
                    $total = 0;
                    $quantity = 0;
                    $invoice_amt = 0;
                    $box_desc = "";
                    $invoice_amt_ind = 0;
                    //$qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and description = '" . str_replace("'", "\'" , $boxdesc) . "' ");
                    $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and loop_box_id = '" . $row_rs_tmprs["box_id"] . "'");
                    while ($row_rs_data_main = array_shift($qry_box_main)) {
                        $quantity = $quantity + $row_rs_data_main["quantity"];
                        $price = $row_rs_data_main["price"];
                        $total = $total + str_replace(",", "", $row_rs_data_main["total"]);
                        $box_desc = $row_rs_data_main['description'];
                        $invoice_amt_ind = $invoice_amt_ind + $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                    }

                    $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ");
                    while ($row_rs_data_main = array_shift($qry_box_main)) {
                        $invoice_amt += $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                    }

                    $gr_total = str_replace(",", "", $total);

                    $summ_ttly = $summ_ttly + $gr_total;

                    $b2bid = 0;
                    $company_name = "";
                    $wid = 0;
                    $inv_number = "";
                    $double_checked = 0;
                    $virtual_inventory_trans_id = 0;
                    $virtual_inventory_company_id = 0;
                    $q1 = "SELECT loop_warehouse.b2bid, inv_number , inv_amount, loop_warehouse.id as wid, virtual_inventory_company_id, virtual_inventory_trans_id, double_checked, company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id where loop_transaction_buyer.id = " . $row_rs_tmprs["trans_rec_id"];
                    $query = db_query($q1);
                    while ($fetch = array_shift($query)) {
                        $b2bid = $fetch['b2bid'];
                        $wid = $fetch['wid'];
                        $double_checked = $fetch['double_checked'];
                        $company_name = $fetch['company_name'];
                        $inv_number = $fetch['inv_number'];
                        $inv_amount = $fetch["inv_amount"];
                        $virtual_inventory_trans_id = $fetch['virtual_inventory_trans_id'];
                        $virtual_inventory_company_id = $fetch['virtual_inventory_company_id'];
                    }

                    if ($invoice_amt == 0) {
                        $invoice_amt = isset($inv_amount);
                    }

                    $nickname = get_nickname_val($company_name, $b2bid);
                    $nickname_supplier = "";

                    $supplier_b2bid = 0;
                    $supplier_wid = 0;
                    $supplier_company_name = "";
                    if ($virtual_inventory_trans_id != -1) {
                        $q1 = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, company_name FROM loop_transaction inner join loop_warehouse on loop_warehouse.id = loop_transaction.warehouse_id where loop_transaction.id = " . $virtual_inventory_trans_id;
                        $query = db_query($q1);
                        while ($fetch = array_shift($query)) {
                            $supplier_b2bid = $fetch['b2bid'];
                            $supplier_wid = $fetch['wid'];
                            $supplier_company_name = $fetch['company_name'];
                        }

                        $nickname_supplier = get_nickname_val($supplier_company_name, $supplier_b2bid);

                        $supp_nm = $virtual_inventory_trans_id . "-" . $nickname_supplier;
                    } else {
                        $virtual_inventory_trans_id = "";
                        $supp_nm = "";

                        $q1_supp = "SELECT * FROM loop_warehouse where id = " . $vendor_b2b_rescue;
                        db();
                        $query_supp = db_query($q1_supp);
                        while ($fetch_supp = array_shift($query_supp)) {
                            $supp_nm = get_nickname_val($fetch_supp['company_name'], $fetch_supp['b2bid']);

                            $supplier_b2bid = $fetch_supp['b2bid'];
                            $supplier_wid = $fetch_supp['id'];
                            $supplier_company_name = $fetch_supp['company_name'];
                        }
                    }

                    $vendor_pay = 0;
                    $profit_val = "TBD";
                    $profit_val_per = "TBD";
                    if ($double_checked == 1) {
                        $to_quantity = 0;
                        $dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE total > 0 and category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ";
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {
                            $to_quantity = $dt_view_row["quantity"];
                        }

                        $quantity_per = ($quantity * 100) / $to_quantity;

                        $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row_rs_tmprs["trans_rec_id"];
                        db();
                        $dt_view_res = db_query($dt_view_qry);
                        while ($dt_view_row = array_shift($dt_view_res)) {
                            $vendor_pay += $dt_view_row["estimated_cost"];
                        }

                        $profit_val = $invoice_amt - $vendor_pay;
                        $tot_profit = $tot_profit + $profit_val;

                        //$profit_val_per = number_format((($profit_val * 100)/$invoice_amt),2) . "%";

                        $profit_val_p = (($profit_val * 100) / $invoice_amt);

                        //$profit_val_per = number_format(($profit_val_p * 100)/$quantity_per,2) . "%";
                        $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                        $profit_val = "$" . number_format($profit_val, 0);
                    }

                    if ($po_flg == "yes") {
                    } else {
                        $lisoftrans_ttly .= "<tr>
                        <td bgColor='#E4EAEB'>" . isset($rowemp["initials"]) ? isset($rowemp["initials"]) : "" . "</td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
						<td bgColor='#E4EAEB'>" . $quantity . "</td>
						<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
                        <td bgColor='#E4EAEB' align='right'>$" . number_format(floatval(str_replace(",", "", $total)), 0) . "</td>
						<td bgColor='#E4EAEB' align='right'>" . $profit_val . "</td>
						<td bgColor='#E4EAEB'>" . $profit_val_per . "</td>
						</tr>";
                    }

                    $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
                }
            }

            if ($summ_ttly > 0) {
                $tot_quotaactual_mtd_ttly = $summ_ttly;
                $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_profit, 0) . "</td><td bgColor='#ABC5DF'>&nbsp;</td></tr>";
            }
            $lisoftrans_ttly .= "</table></span>";
        }
        //This Time Last Year TTLY

        $rev_lastyr_tilldt = 0;
        if ($headtxt == "LAST YEAR") {
            $dt_year_value_1 = date('Y') - 1;
            $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
            $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');

            if ($str_box_list_ids != "") {
                $row_no = 0;
                $tmp_trans_id = "";
                $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt_lasty . "' and '" . $end_Dt_lasty . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");
                while ($row_rs_tmprs = array_shift($qry)) {

                    if ($row_rs_tmprs["trans_rec_id"] != $tmp_trans_id) {
                        $row_no    = 0;
                    } else {
                        $row_no    = $row_no + 1;
                    }

                    $box_qry = "SELECT * FROM loop_boxes WHERE id = " . $row_rs_tmprs["box_id"];
                    $box_res = db_query($box_qry);
                    $boxdesc = "";
                    while ($box_row = array_shift($box_res)) {

                        $boxdesc = $box_row["blength"] . " ";
                        if ($box_row["blength_frac"] != "")
                            $boxdesc .= $box_row["blength_frac"] . " ";
                        $boxdesc .= "x " . $box_row["bwidth"] . " ";
                        if ($box_row["bwidth_frac"] != "")
                            $boxdesc .= $box_row["bwidth_frac"] . " ";
                        $boxdesc .= "x " . $box_row["bdepth"] . " ";
                        if ($box_row["bdepth_frac"] != "")
                            $boxdesc .= $box_row["bdepth_frac"] . " ";
                        $boxdesc .= $box_row["bdescription"];
                    }

                    $price = 0;
                    $total = 0;

                    $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and loop_box_id = '" . $row_rs_tmprs["box_id"] . "'");
                    while ($row_rs_data_main = array_shift($qry_box_main)) {
                        $quantity = isset($quantity) + $row_rs_data_main["quantity"];
                        $price = $row_rs_data_main["price"];
                        $total = $total + str_replace(",", "", $row_rs_data_main["total"]);
                    }

                    $gr_total = str_replace(",", "", $total);

                    $rev_lastyr_tilldt = $rev_lastyr_tilldt + $gr_total;

                    $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
                }
            }
        }

        //for the B2b op team calc
        $global_tot_quota_deal_mtd = $tot_quota_deal_mtd;
        $global_quota_ov = $quota_ov;
        $global_monthly_qtd = isset($monthly_qtd);
        $global_tot_quotaactual_mtd = $tot_quotaactual_mtd;
        $global_unqid = $unqid;
        $global_empid = $MGArraytmp2["empid"];
        $global_lisoftrans = $lisoftrans;
        $global_rev_lastyr_tilldt = $rev_lastyr_tilldt;
        $global_summ_ttly = $summ_ttly;
        $global_lisoftrans_ttly = isset($lisoftrans_ttly);
        //echo "</table>";
    }

    function leadertbl(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        bool $tilltoday,
        int $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        bool $po_flg,
        string $unqid,
        bool $ttylyesno,
        bool $in_dt_range
    ): void {

        global $global_tot_quota_deal_mtd;
        global $global_quota_ov;
        global $global_monthly_qtd;
        global $global_tot_quotaactual_mtd;
        global $global_unqid;
        global $global_empid;
        global $global_empid;
        global $global_rev_lastyr_tilldt;
        global $global_summ_ttly;
        global $global_lisoftrans_ttly;

        global $global_ucbzwtot_quota_deal_mtd;
        global $global_ucbzwquota_ov;
        global $global_ucbzwmonthly_qtd;
        global $global_ucbzwtot_quotaactual_mtd;
        global $global_ucbzwrev_lastyr_tilldt_n;
        global $global_ucbzwunqid;
        global $global_ucbzwempid;
        global $global_ucbzwrev_lastyr_tilldt;
        global $global_ucbzwsumm_ttly;
        global $global_ucbzwlisoftrans_ttly;

        db();

        $lisoftrans_detail_list = "<span style='width:1300px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'><tr style='height:50px;'>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Transaction ID</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='200px'><u>Company</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>PO Date</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Employee</u></th>";
        $lisoftrans_detail_list .= "<th width='100px' bgColor='$tbl_head_color' align=center><u>PO Amount</u></th>";
        $lisoftrans_detail_list .= "</tr>";

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999111";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] Activity Tracker THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999222";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] Activity Tracker LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS MONTH" && $po_flg == "yes") {
            $div_id_emp_list = "999441";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] Activity Tracker THIS MONTH [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST MONTH" && $po_flg == "yes") {
            $div_id_emp_list = "999442";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] Activity Tracker LAST MONTH [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS QUARTER" && $po_flg == "yes") {
            $div_id_emp_list = "999443";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] Activity Tracker THIS QUARTER [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST QUARTER" && $po_flg == "yes") {
            $div_id_emp_list = "999445";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] Activity Tracker LAST QUARTER [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS YEAR" && $po_flg == "yes") {
            $div_id_emp_list = "999446";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] Activity Tracker THIS YEAR [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST YEAR" && $po_flg == "yes") {
            $div_id_emp_list = "999447";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] Activity Tracker LAST YEAR [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999333";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999444";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sales Reps] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650' id='table9' class='tablesorter'>";
        echo "<thead>";
        if ($headtxt == "B2B Leaderboard") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>% of G.Profit</u></th>";
            echo "	</tr>";
        } elseif ($po_flg == "yes") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='250px'><u>Employee</u></th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Leads</u></th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Emails</u>
			<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
			<span class='tooltiptext'>Green is >= 20 Avg Emails/Day Else Red</span></div>
		</th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Calls</u>
			<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
			<span class='tooltiptext'>Green is >= 20 Avg Calls/Day Else Red</span></div>
		</th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Demand Entries</u></th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Quote Requests</u></th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Quotes</u></th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Deals</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>1st Time Customers</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Bookings (PO Amount)</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>TTLY</u></th>";
            echo "	</tr>";
        } else {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Deals</u></th>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            }
            if ($headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit to Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            }
            if ($headtxt == "LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit To Date</u></th>";
            }
            echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>%</u></th>";
            if ($ttylyesno == "ttylyes") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>TTLY</u></th>";
            }
            echo "	</tr>";
        }
        echo "</thead>";
        echo "<tbody>";

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
        //dashboard_view = 'Sales'
        if ($headtxt == "B2B Leaderboard") {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees ";
        } else {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees WHERE  (leaderboard = 1 or purchasing_leaderboard = 1) and status = 'Active'";
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
        }
        $emp_initials_list = rtrim($emp_initials_list, ",");
        $emp_b2bid_list = rtrim($emp_b2bid_list, ",");
        $emp_id_list = rtrim($emp_id_list, ",");
        $emp_eml_list = rtrim($emp_eml_list, ",");
        if ($po_flg == "yes") {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE leaderboard = 1 and status = 'Active' union 
		SELECT 0 as id, 'Other' as name, 'OT' as initials, '' as email, 0 as b2b_id, 0 leaderboard FROM loop_employees WHERE id = 1";
        } else if ($headtxt == "B2B Leaderboard") {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees";
        } else {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE leaderboard = 1 and status = 'Active'";
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
            db();
            $result_empq = db_query("Select quota_year, quota_month from employee_quota_gprofit where emp_id = " . $rowemp["id"] . " order by quota_year, quota_month limit 1");
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
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select sum(quota) as sumquota, sum(deal_quota) as deal_quota from employee_quota_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value);
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
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                db();
                if ($current_qtr == 1) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                    }
                }
                if ($current_qtr == 2) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                    }
                }
                if ($current_qtr == 3) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                    }
                }
                if ($current_qtr == 4) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
                        $days_today = 1 + (int)dateDiff($todays_dt, date('Y-m-01'));
                        $days_in_month = 1 + (int)dateDiff(date('Y-m-t'), date('Y-m-01'));
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
                $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
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
                    $monthly_qtd = (isset($days_today) * isset($quota)) / 365;
                } else {
                    $monthly_qtd = $quota_mtd;
                }
            }

            $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
            if ($po_flg == "yes") {

                $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
            } else {

                $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
            }
            if ($po_flg == "yes") {
                if ($rowemp["leaderboard"] == 0) {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee LIKE '" . $rowemp["initials"] . "'  and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                }
            } else {
                if ($tilltoday == "Y") {
                    if ($rowemp["leaderboard"] == 0) {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                    }
                } else {
                    if ($rowemp["leaderboard"] == 0 && $headtxt != "B2B Leaderboard") {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                    }
                }
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
                if ($rowemp["leaderboard"] == 0) {
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

                $estimated_cost = 0;
                if ($po_flg != "yes") {
                    $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				AND loop_transaction_buyer.UCBZeroWaste_flg = 0 and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                    db();
                    $resB2bCogs = db_query($qryB2bCogs);

                    while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                        $estimated_cost = str_replace(",", "", number_format($resB2bCogs_row['estimated_cost'], 0));
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

                $sr_no = isset($sr_no) + 1;

                if ($po_flg == "yes") {
                    $summtd_SUMPO_activity = $summtd_SUMPO_activity + str_replace(",", "", number_format($summtd["inv_amount"], 0));

                    $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";

                    $lisoftrans_for_total .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='left'>" . $summtd["po_date"] . "</td><td bgColor='#E4EAEB' align='left'>" . $rowemp["name"] . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";
                } else {

                    $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
                }
            }

            if ($summtd_SUMPO > 0) {
                if ($po_flg == "yes") {

                    $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO_activity, 0) . "</td></tr>";
                } else {

                    $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
                }
            }
            $lisoftrans .= "</table></span>";

            //For TTLY
            $summ_ttly = 0;
            if ($ttylyesno == "ttylyes") {
                $start_Date = strtotime('-1 year', strtotime($start_Dt));
                if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
                    $end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));
                } else {
                    $end_Date = strtotime('-1 year', strtotime($end_Dt));
                }

                $start_Dt_ttly = Date('Y-m-d', $start_Date);
                $end_Dt_ttly = Date('Y-m-d', $end_Date);

                $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                if ($po_flg == "yes") {
                    $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
                } else {
                    $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
                }

                if ($tilltoday == "Y" && $po_flg != "yes") {
                    if ($rowemp["leaderboard"] == 0) {
                        $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    }
                } else if ($po_flg == "yes") {
                    if ($rowemp["leaderboard"] == 0) {
                        $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_transaction_buyer.po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_transaction_buyer.po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    }
                } else {
                    if ($rowemp["leaderboard"] == 0) {
                        $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
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

                    $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				AND loop_transaction_buyer.UCBZeroWaste_flg = 0 and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                    db();
                    $resB2bCogs = db_query($qryB2bCogs);

                    $estimated_cost = 0;
                    while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                        $estimated_cost = str_replace(",", "", number_format($resB2bCogs_row['estimated_cost'], 0));
                    }

                    $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                    if ($po_flg == "yes") {
                        $summ_ttly = $summ_ttly + str_replace(",", "", number_format(($summtd["inv_amount"]), 0));
                        $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($summtd["inv_amount"]), 0) . "</td></tr>";
                    } else {
                        $summ_ttly = $summ_ttly + ($inv_amt_totake - $estimated_cost);
                        $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
                    }
                }

                if ($summ_ttly > 0) {
                    if ($po_flg == "yes") {
                        $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td></tr>";
                    } else {
                        $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td></tr>";
                    }
                }
                $lisoftrans_ttly .= "</table></span>";
            }
            //For TTLY

            $rev_lastyr_tilldt = 0;
            if ($headtxt == "LAST YEAR") {
                $dt_year_value_1 = date('Y') - 1;
                $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
                $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');

                $lisoftrans_lastyear = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                $lisoftrans_lastyear .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";

                if ($rowemp["leaderboard"] == 0) {
                    $sqlmtd = "SELECT inv_number, loop_warehouse.warehouse_name, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT inv_number, loop_warehouse.warehouse_name, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'B2B' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
                }
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

                    $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                    $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				AND loop_transaction_buyer.UCBZeroWaste_flg = 0 and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                    db();
                    $resB2bCogs = db_query($qryB2bCogs);

                    $estimated_cost = 0;
                    while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                        $estimated_cost = str_replace(",", "", number_format($resB2bCogs_row['estimated_cost'], 0));
                    }
                    $rev_lastyr_tilldt = $rev_lastyr_tilldt + ($inv_amt_totake - $estimated_cost);

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
            } elseif ($summtd_SUMPO < $monthly_qtd) {
                $color = "red";
            } else {
                $color = "black";
            };
            if ($monthly_qtd == 0) {
                $color = "black";
            }

            $add_entry = "yes";
            if ($headtxt == "B2B Leaderboard") {
                if ($summtd_SUMPO == 0) {
                    $add_entry = "no";
                }
            }

            if ($rowemp["leaderboard"] == 0) {
                $summtd_SUMPO_tweek = -99999;
            } else {
                $summtd_SUMPO_tweek = $summtd_SUMPO_activity;
            }

            if ($add_entry == "yes") {
                $MGArray[] = array(
                    'name' => $rowemp["name"], 'name_initial' => $rowemp["initials"], 'emp_initials_list' => $emp_initials_list, 'emp_b2bid_list' => $emp_b2bid_list,  'emp_id_list' => $emp_id_list,
                    'emp_email' => $rowemp["email"], 'b2bempid' => $rowemp["b2b_id"], 'leaderboard' => $rowemp["leaderboard"], 'summtd_SUMPO_activity' => $summtd_SUMPO_activity,
                    'empid' => $rowemp["id"], 'start_Dt' => $start_Dt, 'end_Dt' => $end_Dt, 'deal_count' => $summtd_dealcnt, 'quota' => isset($quota_in_st_en),
                    'quotatodate' => $monthly_qtd, 'po_entered' => $summtd_SUMPO, 'po_entered_other_tweek' => $summtd_SUMPO_tweek, 'ttly' => $summ_ttly, 'percent_val' => $color, 'rev_lastyr_tilldt' => $rev_lastyr_tilldt, 'lisoftrans' => $lisoftrans,
                    'lisoftrans_ttly' => isset($lisoftrans_ttly), 'lisoftrans_lastyear' => isset($lisoftrans_lastyear), 'lisoftrans_for_total' => $lisoftrans_for_total
                );
            }
        }

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
                    $MGArraysort_I[] = isset($MGArraytmp['companyID']);
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
            if ($po_flg == "yes") {
                $summtd_SUMPO = $MGArraytmp2["summtd_SUMPO_activity"];
            } else {
                $summtd_SUMPO = $MGArraytmp2["po_entered"];
            }
            $summtd_ttly = $MGArraytmp2["ttly"];
            //$monthly_percentage = $MGArraytmp2["percent_val"];

            //if ($monthly_percentage >= 100 ) { $color_y = "green"; } elseif ($monthly_percentage >= 80 ) { $color_y = "E0B003"; } else { $color_y = "B03030"; };
            $color_y = $MGArraytmp2["percent_val"];


            if ($headtxt == "B2B Leaderboard") {
                echo "<tr><td bgColor='$tbl_color' align ='left'>" . $name . "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($monthly_qtd_TD, 0);
                echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color_y . "'>";
                echo "<a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
                echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
                echo "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color_y . "'>";
                if ($monthly_qtd_TD > 0) {
                    echo number_format(($summtd_SUMPO / $monthly_qtd_TD) * 100, 2) . "%";
                }
                echo "</font></td>";
                echo "</tr>";
            } else if ($po_flg == "yes") {

                $first_time_rec = 0;
                if ($MGArraytmp2["leaderboard"] == 0) {
                    db();
                    $result_crm = db_query("SELECT count(id) as cnt FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee not in (" . $MGArraytmp2["emp_initials_list"] . ") and loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $MGArraytmp2["start_Dt"] . "' AND '" . $MGArraytmp2["end_Dt"] . " 23:59:59'");
                } else {
                    db();
                    $result_crm = db_query("SELECT count(id) as cnt FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee LIKE '" . $MGArraytmp2["name_initial"] . "' and loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $MGArraytmp2["start_Dt"] . "' AND '" . $MGArraytmp2["end_Dt"] . " 23:59:59'");
                }
                while ($rowemp_crm = array_shift($result_crm)) {
                    $first_time_rec = $first_time_rec + $rowemp_crm["cnt"];
                }
                $tot_first_time_rec = isset($tot_first_time_rec) + $first_time_rec;

                $quote_req_cnt = 0;
                if ($MGArraytmp2["leaderboard"] == 0) {
                    db();
                    $result_crm = db_query("Select count(quote_request_tracker_id) as cnt from quote_request_tracker where date_submitted BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and quote_req_submitted_by not in (" . $MGArraytmp2["emp_initials_list"] . ")");
                } else {
                    db();
                    $result_crm = db_query("Select count(quote_request_tracker_id) as cnt from quote_request_tracker where date_submitted BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and quote_req_submitted_by = '" . $MGArraytmp2["name_initial"] . "'");
                }
                while ($rowemp_crm = array_shift($result_crm)) {
                    $quote_req_cnt = $quote_req_cnt + $rowemp_crm["cnt"];
                }
                $tot_quote_req_cnt = isset($tot_quote_req_cnt) + $quote_req_cnt;

                //Demand entry
                $demand_entry_tmp = 0;
                if ($MGArraytmp2["leaderboard"] == 0) {
                    $result_crm = db_query("Select count(quote_id) as cnt from quote_request where quote_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and user_initials not in (" . $MGArraytmp2["emp_initials_list"] . ") ");
                } else {
                    $result_crm = db_query("Select count(quote_id) as cnt from quote_request where quote_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and user_initials = '" . $MGArraytmp2["name_initial"] . "'");
                }
                while ($rowemp_crm = array_shift($result_crm)) {
                    $demand_entry_tmp = $demand_entry_tmp + $rowemp_crm["cnt"];
                }
                $tot_demand_entry_tmp = isset($tot_demand_entry_tmp) + $demand_entry_tmp;

                $contact_act_ph1 = 0;
                $contact_act_tmp = 0;
                $eml_list = "";
                if ($MGArraytmp2["leaderboard"] == 0) {
                    //$sql7 = "SELECT ID, type, EmailID FROM CRM WHERE  duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE not in (" . $MGArraytmp2["emp_initials_list"] . ") AND  timestamp BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                } else {
                    $sql7 = "SELECT ID, type, EmailID FROM CRM WHERE  duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $MGArraytmp2["name_initial"] . "' AND  timestamp BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                    db_b2b();
                    $result_crm = db_query($sql7);
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $eml_list .= $rowemp_crm["EmailID"] . ", ";
                        if ($rowemp_crm["type"] ==  "phone") {
                            $contact_act_ph1 = $contact_act_ph1 + 1;
                        }
                        if ($rowemp_crm["type"] ==  "email") {
                            $contact_act_tmp = $contact_act_tmp + 1;
                        }
                    }
                }

                $lead_tmp = 0;
                if ($MGArraytmp2["leaderboard"] == 0) {
                    //$result_crm = db_query("Select count(ID) as cnt from companyInfo where dateCreated BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and landing_pg_enter_by not in (" . $MGArraytmp2["emp_b2bid_list"] . ")");
                } else {
                    $result_crm = db_query("Select count(ID) as cnt from companyInfo where dateCreated BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and landing_pg_enter_by = '" . $MGArraytmp2["b2bempid"] . "'");
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $lead_tmp = $lead_tmp + $rowemp_crm["cnt"];
                    }
                }
                $tot_lead_tmp = isset($tot_lead_tmp) + $lead_tmp;

                if ($MGArraytmp2["leaderboard"] == 0) {
                    //$result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and fromadd not in (" . $emp_eml_list . ")", db_email() );
                } else {
                    db_email();
                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and fromadd = '" . $MGArraytmp2["emp_email"] . "'");

                    while ($rowemp_crm = array_shift($result_crm)) {
                        $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                    }
                }
                $tot_quota_contact = isset($tot_quota_contact) + $contact_act_tmp;
                $tot_quota_contact_ph = isset($tot_quota_contact_ph) + $contact_act_ph1;

                $quotes_sent = 0;
                if ($MGArraytmp2["leaderboard"] == 0) {
                    $sql7 = "SELECT * FROM quote WHERE rep not in ($emp_b2bid_list) AND qstatus !=2 AND quoteDate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                } else {
                    $sql7 = "SELECT * FROM quote WHERE rep LIKE '" . $MGArraytmp2["b2bempid"] . "' AND  qstatus !=2 AND quoteDate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                }
                db_b2b();
                $result_new = db_query($sql7);
                $quotes_sent = tep_db_num_rows($result_new);

                $tot_quota_quotes = isset($tot_quota_quotes) + $quotes_sent;

                $email_color_code = "";
                $email_color_code2 = "";
                $contact_color_code = "";
                $contact_color_code2 = "";

                if ($headtxt == "LAST WEEK" && $po_flg == "yes") {
                    $week_val = 5;
                }

                if ($headtxt == "THIS WEEK" && $po_flg == "yes") {
                    $week_val = date('w');
                }
                if (isset($week_val) == 1) {
                    if ($contact_act_tmp >= 20) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 20) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 20) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 20) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 2) {
                    if ($contact_act_tmp >= 40) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 40) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 40) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 40) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 3) {
                    if ($contact_act_tmp >= 60) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 60) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 60) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 60) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 4) {
                    if ($contact_act_tmp >= 80) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 80) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 80) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 80) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) >= 5) {
                    if ($contact_act_tmp >= 100) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 100) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 100) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 100) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }

                db();
                /*if($summtd_SUMPO>0)
            {*/
                $in_other_flg = "";
                if ($MGArraytmp2["leaderboard"] == 0) {
                    $in_other_flg = "&other_flg=yes";
                }

                echo "<tr><td bgColor='$tbl_color' align ='left'>" . $name . "</td>";
                if ($MGArraytmp2["leaderboard"] == 0) {
                    echo "<td bgColor='$tbl_color' align = 'right'>&nbsp";
                    echo "</td>";
                    echo "<td bgColor='$tbl_color' align = 'right'>&nbsp";
                    echo "</td>";
                    echo "<td bgColor='$tbl_color' align = 'right'>&nbsp";
                    echo "</a></td>";
                } else {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a target='_blank' href='report_show_list.php?showlead=yes" . $in_other_flg . "&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "'>";
                    echo number_format($lead_tmp, 0);
                    echo "</a></td>";
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter" . $in_other_flg . "&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "'>";
                    echo $email_color_code . " " . number_format($contact_act_tmp, 0) . " " . $email_color_code2;
                    echo "</a></td>";
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter" . $in_other_flg . "&phone=y&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "'>";
                    echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
                    echo "</a></td>";
                }
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_show_list.php?showquote_req=demand_entry" . $in_other_flg . "&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "'>";
                echo number_format($demand_entry_tmp, 0);
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_show_list.php?showquote_req=yes" . $in_other_flg . "&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "'>";
                echo number_format($quote_req_cnt, 0);
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y" . $in_other_flg . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
                echo number_format($quotes_sent, 0);
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo number_format($monthly_deal_qtd, 0);
                echo "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_show_list.php?showfirsttimerec=yes" . $in_other_flg . "&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "'>";
                echo number_format($first_time_rec, 0);
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'><a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'>$" . number_format($summtd_SUMPO, 0) . "</a>";
                echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
                echo "</td>";

                //if ($headtxt != "THIS WEEK" && $headtxt != "LAST WEEK"){
                $lisoftrans_detail_list .= $MGArraytmp2["lisoftrans_for_total"];
                //}	

                if ($ttylyesno == "ttylyes") {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a href='#' onclick='load_div(77" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summtd_ttly, 0) . "</font></a>";
                    echo "<span id='77" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_ttly"] . "</span>";
                    echo "</td>";
                }
                echo "</tr>";
                //}

            } else {
                echo "<tr><td bgColor='$tbl_color' align ='left'>" . $name . "</td><td bgColor='$tbl_color' align = right>";
                echo number_format($monthly_deal_qtd, 0);
                if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format(floatval($monthly_qtd), 0);
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format($monthly_qtd_TD, 0);
                } else {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format(floatval($monthly_qtd), 0);
                }

                if ($headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                } else {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                }

                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if ($summtd_SUMPO >= $monthly_qtd) {
                        $color = "green";
                    } elseif ($summtd_SUMPO < $monthly_qtd) {
                        $color = "red";
                    } else {
                        $color = "black";
                    };
                }
                $color_y_new = "black";
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    $monthly_qtd = $monthly_qtd ?? "";
                    if ($monthly_qtd != 0 && (($summtd_SUMPO * 100) / $monthly_qtd) >= 100) {
                        $color_y_new = "green";
                    } elseif (($summtd_SUMPO * 100 / $monthly_qtd) < 100 && $summtd_SUMPO > 0 && $monthly_qtd > 0) {
                        $color_y_new = "red";
                    } elseif ($summtd_SUMPO > 0 && $monthly_qtd == 0) {
                        $color_y_new = "green";
                    } elseif ($summtd_SUMPO == 0 && $monthly_qtd > 0) {
                        $color_y_new = "red";
                    } else {
                        $color_y_new = "black";
                    };
                } else {
                    if (($summtd_SUMPO * 100 / $monthly_qtd_TD) >= 100) {
                        $color_y_new = "green";
                    } elseif (($summtd_SUMPO * 100 / $monthly_qtd_TD) < 100 && $summtd_SUMPO > 0 && $monthly_qtd_TD > 0) {
                        $color_y_new = "red";
                    } elseif ($summtd_SUMPO > 0 && $monthly_qtd_TD == 0) {
                        $color_y_new = "green";
                    } elseif ($summtd_SUMPO == 0 && $monthly_qtd_TD > 0) {
                        $color_y_new = "red";
                    } else {
                        $color_y_new = "black";
                    };
                }

                echo "<a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y_new . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
                echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
                echo "</td>";
                if ($headtxt == "LAST YEAR") {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a href='#' onclick='load_div(66" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($MGArraytmp2["rev_lastyr_tilldt"], 0) . "</font></a>";
                    echo "<span id='66" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_lastyear"] . "</span>";
                    echo "</td>";
                }
                echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color_y_new . "'>";
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if ($summtd_SUMPO > 0 && $monthly_qtd > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd, 2) . "%";
                    }
                } else {
                    if ($summtd_SUMPO > 0 && $monthly_qtd_TD > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd_TD, 2) . "%";
                    }
                }
                echo "</font></td>";
                if ($ttylyesno == "ttylyes") {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a href='#' onclick='load_div(77" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summtd_ttly, 0) . "</font></a>";
                    echo "<span id='77" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_ttly"] . "</span>";
                    echo "</td>";
                }
                echo "</tr>";
            }

            $monthly_deal_qtd = number_format($monthly_deal_qtd, 0);

            $tot_quota_mtd = $tot_quota_mtd + $monthly_qtd;
            $tot_quota_mtd_TD = isset($tot_quota_mtd_TD) + $monthly_qtd_TD;
            $tot_quotaactual_mtd = $tot_quotaactual_mtd + str_replace(",", "", number_format($summtd_SUMPO, 0));
            $tot_quota_deal_mtd = $tot_quota_deal_mtd + $monthly_deal_qtd;
            $tot_rev_lastyr_tilldt = $tot_rev_lastyr_tilldt + str_replace(",", "", number_format($MGArraytmp2["rev_lastyr_tilldt"], 0));
            $tot_summtd_ttly = $tot_summtd_ttly + str_replace(",", "", number_format($summtd_ttly, 0));
        }
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
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
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
                $newsel = "Select quota_month, quota , deal_quota, quota_year  FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value;
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov = $quota_ov + $rowemp_ovdata["quota"];
            }
        }

        if ($po_flg == "yes") {
            $tot_lead_tmp = $tot_lead_tmp ?? "";
            $tot_quota_contact = $tot_quota_contact ?? "";
            $tot_quota_contact_ph = $tot_quota_contact_ph ?? "";
            $tot_demand_entry_tmp = $tot_demand_entry_tmp ?? "";
            $tot_quote_req_cnt = $tot_quote_req_cnt ?? "";
            $tot_quota_quotes = $tot_quota_quotes ?? "";
            $tot_quota_deal_mtd = $tot_quota_deal_mtd ?? "";
            $tot_first_time_rec = $tot_first_time_rec ?? "";
            echo "<tr><td bgColor='$tbl_color' align ='right'><strong>Total</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_lead_tmp, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_quota_contact, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_quota_contact_ph, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_demand_entry_tmp, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_quote_req_cnt, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_quota_quotes, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_quota_deal_mtd, 0);
            echo "</strong></td>";
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo number_format($tot_first_time_rec, 0);
            echo "</strong></td>";

            $lisoftrans_detail_list .=  "<tr><td bgColor='$tbl_color' colspan='4'><b>Total</b></td><td bgColor='$tbl_color' align='right'><b>$" . number_format($tot_quotaactual_mtd, 0) . "</b></td></tr>";

            echo "<td bgColor='$tbl_color' align = 'right'><strong>$" . number_format($tot_quotaactual_mtd, 0) . "<span id='" . isset($div_id_emp_list) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_detail_list . "</table></span></td>";
            if ($ttylyesno == "ttylyes") {
                echo "<td bgColor='$tbl_color' align = 'right'><strong>$" . number_format($tot_summtd_ttly, 0) . "</td>";
            }
            echo "</tr>";
        }
        //if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" && $po_flg != "yes"){
        if ($po_flg == "yes") {
            //echo "<tr><td>";
            //echo "<span id='". $div_id_emp_list . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_detail_list . "</table></span>";
            //echo "</td></tr>";
        }
        echo "</table><br>";

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
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $result_empq = db_query("Select quota_month, quota, deal_quota FROM employee_quota_overall_sales_gp WHERE b2borb2c = 'b2bgp' and quota_year = " . $dt_year_value . " order by quota_month");
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

            $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
        } else {

            $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
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

            $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
			FROM loop_transaction_buyer 
			LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
			INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
			WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
			AND loop_transaction_buyer.UCBZeroWaste_flg = 0 and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            db();
            $resB2bCogs = db_query($qryB2bCogs);

            $estimated_cost = 0;
            while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                $estimated_cost = str_replace(",", "", number_format($resB2bCogs_row['estimated_cost'], 0));
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


                $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";
            } else {

                $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
            }
        }
        if ($summtd_SUMPO > 0) {
            $tot_quotaactual_mtd = $summtd_SUMPO;

            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
        }
        $lisoftrans .= "</table></span>";

        //This Time Last Year TTLY
        $summ_ttly = 0;
        $tot_quotaactual_mtd_ttly = 0;
        if ($ttylyesno == "ttylyes") {
            $start_Date = strtotime('-1 year', strtotime($start_Dt));
            if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
                $end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));
            } else {
                $end_Date = strtotime('-1 year', strtotime($end_Dt));
            }

            $start_Dt_ttly = Date('Y-m-d', $start_Date);
            $end_Dt_ttly = Date('Y-m-d', $end_Date);

            $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
            $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
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

                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				AND loop_transaction_buyer.UCBZeroWaste_flg = 0 and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                db();
                $resB2bCogs = db_query($qryB2bCogs);

                $estimated_cost = 0;
                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", number_format($resB2bCogs_row['estimated_cost'], 0));
                }
                $summ_ttly = $summ_ttly + ($inv_amt_totake - $estimated_cost);

                $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
            }
            if ($summ_ttly > 0) {
                $tot_quotaactual_mtd_ttly = $summ_ttly;
                $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td></tr>";
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

                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'B2B' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				AND loop_transaction_buyer.UCBZeroWaste_flg = 0 and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                db();
                $resB2bCogs = db_query($qryB2bCogs);

                $estimated_cost = 0;
                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", number_format($resB2bCogs_row['estimated_cost'], 0));
                }
                $rev_lastyr_tilldt = $rev_lastyr_tilldt + ($inv_amt_totake - $estimated_cost);
            }
            //$tot_quotaactual_mtd = $rev_lastyr_tilldt;
        }

        if ($headtxt == "LAST TO LAST YEAR") {
            //$monthly_qtd = $quota_in_st_en;
        }

        if ($po_flg == "no") {
            leadertbl_purchasing($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid, $ttylyesno, $in_dt_range);
        }

        if ($headtxt != "THIS WEEK" && $headtxt != "LAST WEEK") {
            leadertbl_ucbzw($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid, $ttylyesno, $in_dt_range);
        }

        //for the B2b op team calc
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else if ($po_flg == "yes") {
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[UCB] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650' id='table9' class='tablesorter'>";
        echo "<thead>";

        if ($headtxt == "B2B Leaderboard") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Department</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>% of G.Profit</u></th>";
            echo "	</tr>";
        } else if ($po_flg == "yes") {
        } else {

            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Department</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Deals</u></th>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            }
            if ($headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit to Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            }
            if ($headtxt == "LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit To Date</u></th>";
            }
            echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>%</u></th>";
            if ($ttylyesno == "ttylyes") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>TTLY</u></th>";
            }
            echo "	</tr>";
        }
        echo "</thead>";

        if ($headtxt == "B2B Leaderboard") {
            echo "<tr><td bgColor='$tbl_color' align ='left'>B2B</td>";
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

            echo "<td bgColor='$tbl_color' align = 'right'><a href='#' onclick='load_div(919" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
            echo "<span id='919" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans . "</span></td>";

            echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>" . number_format(($tot_quotaactual_mtd / $quota_ov) * 100, 2);
            echo "%</font></td>";

            echo "</tr>";
        } else if ($po_flg == "yes") {
            echo "</table>";
        } else {

            echo "<tr><td bgColor='$tbl_color' align ='left'>B2B</td><td bgColor='$tbl_color' align = right>";
            echo number_format($tot_quota_deal_mtd, 0);
            echo "</td><td bgColor='$tbl_color' align = 'right'>";
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
                } elseif ($tot_quotaactual_mtd < $quota_ov) {
                    $color = "red";
                } else {
                    $color = "black";
                };
                echo "$" . number_format($quota_ov, 0);
            }
            echo "</strong></td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

            echo "<a href='#' onclick='load_div(99" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
            echo "<span id='99" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans . "</span>";

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
            if ($ttylyesno == "ttylyes") {
                echo "<td bgColor='$tbl_color' align = 'right'>";

                echo "<a href='#' onclick='load_div(88" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summ_ttly, 0) . "</font></a>";
                echo "<span id='88" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . isset($lisoftrans_ttly) . "</span>";

                echo "</font></td>";
            }

            echo "</tr>";

            //echo number_format($tot_quotaactual_mtd*100/$monthly_qtd,2);
        }

        global $global_final_tot_deal_cnt;
        global $global_final_tot_quota;
        global $global_final_tot_quota_to_dt;
        global $global_final_tot_rev;
        global $global_final_tot_rev_ttly;
        global $global_ucbzwrev_lastyr_tilldt;

        $global_final_tot_deal_cnt = $tot_quota_deal_mtd;
        $global_final_tot_quota = $quota_ov;
        $global_final_tot_quota_to_dt = $monthly_qtd;
        $global_final_tot_rev = $tot_quotaactual_mtd;
        $global_final_tot_rev_ttly = $summ_ttly;
        $global_ucbzwrev_lastyr_tilldt = $rev_lastyr_tilldt;
    }

    //for UCBZW
    function leadertbl_ucbzw(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        bool $tilltoday,
        int $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        bool $po_flg,
        string $unqid,
        bool $ttylyesno,
        bool $in_dt_range
    ): void {

        global $global_ucbzwtot_quota_deal_mtd;
        global $global_ucbzwquota_ov;
        global $global_ucbzwmonthly_qtd;
        global $global_ucbzwtot_quotaactual_mtd;
        global $global_ucbzwunqid;
        global $global_ucbzwempid;
        global $global_ucbzwempid;
        global $global_ucbzwrev_lastyr_tilldt;
        global $global_ucbzwsumm_ttly;
        global $global_ucbzlisoftrans;
        global $global_ucbzwlisoftrans_ttly;
        global $global_ucbzwrev_lastyr_tilldt_n;

        db();

        $lisoftrans_detail_list = "<span style='width:1300px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'><tr style='height:50px;'>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Transaction ID</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='200px'><u>Company</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>PO Date</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Employee</u></th>";
        $lisoftrans_detail_list .= "<th width='100px' bgColor='$tbl_head_color' align=center><u>PO Amount</u></th>";
        $lisoftrans_detail_list .= "</tr>";

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[UCBZW Reps] TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[UCBZW Reps] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650' id='table9' class='tablesorter'>";
        echo "<thead>";
        if ($headtxt == "B2B Leaderboard") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Share</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>% of Share</u></th>";
            echo "	</tr>";
        } else {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Deals</u></th>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            }
            if ($headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Share to Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Share</u></th>";
            }
            if ($headtxt == "LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Share To Date</u></th>";
            }
            echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>%</u></th>";
            if ($ttylyesno == "ttylyes") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>TTLY</u></th>";
            }
            echo "	</tr>";
        }
        echo "</thead>";
        echo "<tbody>";

        $tot_quota = 0;
        $tot_quotaytd = 0;
        $tot_quotaactual = 0;
        $tot_quota_mtd = 0;
        $tot_quota_deal_mtd = 0;
        $tot_quotaactual_mtd = 0;
        $quota_one_day = 0;
        $lisoftrans_tot = "";

        $ucbzw_lisoftrans = "";
        $ucbzw_lisoftrans_lastyear = "";
        $ucbzw_lisoftrans_ttly = "";
        $ucbzw_popupRevenueContent = "";

        $dt_year_value = date('Y', strtotime($start_Dt));
        $dt_month_value = date('m', strtotime($start_Dt));
        $current_year_value = date('Y');

        $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));
        $sql = "SELECT * FROM loop_employees WHERE ucbzw_leaderboard = 1 and status = 'Active' ORDER BY quota DESC";
        //quota > 0 and 
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {
            $quota = 0;
            $quotadate = "";
            $deal_quota = 0;
            $monthly_qtd = 0;
            db();
            $result_empq = db_query("Select quota_year, quota_month from employee_quota_ucbzw_share where emp_id = " . $rowemp["id"] . " order by quota_year, quota_month limit 1");
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
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_ucbzw_share where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select sum(quota) as sumquota, sum(deal_quota) as deal_quota from employee_quota_ucbzw_share where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value);
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


            if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
                $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
                db();
                if ($current_qtr == 1) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_ucbzw_share where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                    }
                }
                if ($current_qtr == 2) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_ucbzw_share where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                    }
                }
                if ($current_qtr == 3) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_ucbzw_share where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                    }
                }
                if ($current_qtr == 4) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_ucbzw_share where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
                $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_ucbzw_share where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
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
                $quota_in_st_en = isset($quota);

                if ($headtxt == "LAST YEAR") {
                    $monthly_qtd = (isset($days_today) * isset($quota)) / 365;
                } else {
                    $monthly_qtd = $quota_mtd;
                }
            }

            $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
            //$lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";
            $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
		<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Date of Invoice</td>
		<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Share Amount</td></tr>";
            //<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>

            if ($tilltoday == "Y") {
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.inv_date_of, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.inv_date_of, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_date_of, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
            }
            if ($headtxt == "THIS YEAR") {
                //echo $sqlmtd . "<br>";
            }
            //echo $sqlmtd . "<br>";
            $resultmtd = db_query($sqlmtd);
            $summtd_SUMPO = 0;
            $summtd_dealcnt = 0;
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

                if ($finalpaid_amt <> 0) {
                    $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] <> 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] <> 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
                }
                //echo "inv_amt_totake = " . $inv_amt_totake . " " . $summtd["id"] . "<br>";

                $estimated_cost = 0;
                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
			FROM loop_transaction_buyer 
			LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
			INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
			WHERE loop_transaction_buyer.Leaderboard = 'UCBZW' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
			and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                //AND loop_transaction_buyer.UCBZeroWaste_flg = 0 echo $qryB2bCogs . "<br>";
                db();
                $resB2bCogs = db_query($qryB2bCogs);

                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", number_format($resB2bCogs_row['estimated_cost'], 0));
                }

                $summtd_SUMPO = $summtd_SUMPO + str_replace(",", "", number_format($inv_amt_totake - $estimated_cost, 0));



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

                $sr_no = isset($sr_no) + 1;


                $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
			<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
			<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $summtd["inv_date_of"] . "</td>
			<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";

                $ucbzw_lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
			<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
			<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $summtd["inv_date_of"] . "</td>
			<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
                //<td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
            }

            if ($summtd_SUMPO > 0) {
                //$lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO,0) . "</td></tr>";
                $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
            }
            $lisoftrans .= "</table></span>";


            $summ_ttly = 0;
            if ($ttylyesno == "ttylyes") {
                $start_Date = strtotime('-1 year', strtotime($start_Dt));
                if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
                    $end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));
                } else {
                    $end_Date = strtotime('-1 year', strtotime($end_Dt));
                }

                $start_Dt_ttly = Date('Y-m-d', $start_Date);
                $end_Dt_ttly = Date('Y-m-d', $end_Date);

                $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

                if ($tilltoday == "Y") {
                    $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                }
                //echo $sqlmtd . "<br>";
                $resultmtd = db_query($sqlmtd);
                while ($summtd = array_shift($resultmtd)) {
                    $invoice_amt = 0;
                    $inv_amt_totake = 0;
                    $inv_qry = "SELECT quantity, price FROM loop_invoice_items WHERE trans_rec_id = '" . $summtd["id"] . "' ORDER BY id ASC";
                    db();
                    $inv_res = db_query($inv_qry);
                    while ($inv_row = array_shift($inv_res)) {
                        $invoice_amt += $inv_row["quantity"] * $inv_row["price"];
                    }
                    if ($invoice_amt == 0) {
                        $invoice_amt = $summtd["invsent_amt"];
                    }

                    $vendor_pay = 0;
                    $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $summtd["id"];
                    db();
                    $dt_view_res = db_query($dt_view_qry);
                    $num_rows = tep_db_num_rows($dt_view_res);
                    if ($num_rows > 0) {
                        while ($dt_view_row = array_shift($dt_view_res)) {
                            $vendor_pay += $dt_view_row["estimated_cost"];
                        }
                    }

                    $profit_val = $invoice_amt - $vendor_pay;
                    $inv_amt_totake = str_replace(",", "", number_format($profit_val, 0));
                    $summ_ttly = $summ_ttly + $inv_amt_totake;

                    $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                    $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(floatval($inv_amt_totake), 0) . "</td></tr>";
                    $ucbzw_lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(floatval($inv_amt_totake), 0) . "</td></tr>";
                }

                if ($summ_ttly > 0) {
                    $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td></tr>";
                }
                $lisoftrans_ttly .= "</table></span>";
            }
            //For TTLY

            $rev_lastyr_tilldt = 0;
            if ($headtxt == "LAST YEAR") {
                $dt_year_value_1 = date('Y') - 1;
                $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
                $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');

                $lisoftrans_lastyear = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                $lisoftrans_lastyear .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

                $sqlmtd = "SELECT inv_number, loop_warehouse.warehouse_name, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";

                $resultmtd = db_query($sqlmtd);
                while ($summtd = array_shift($resultmtd)) {
                    $invoice_amt = 0;
                    $inv_amt_totake = 0;
                    $inv_qry = "SELECT quantity, price FROM loop_invoice_items WHERE trans_rec_id = '" . $summtd["id"] . "' ORDER BY id ASC";
                    db();
                    $inv_res = db_query($inv_qry);
                    while ($inv_row = array_shift($inv_res)) {
                        $invoice_amt += $inv_row["quantity"] * $inv_row["price"];
                    }
                    if ($invoice_amt == 0) {
                        $invoice_amt = $summtd["invsent_amt"];
                    }

                    $vendor_pay = 0;
                    $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $summtd["id"];
                    db();
                    $dt_view_res = db_query($dt_view_qry);
                    $num_rows = tep_db_num_rows($dt_view_res);
                    if ($num_rows > 0) {
                        while ($dt_view_row = array_shift($dt_view_res)) {
                            $vendor_pay += $dt_view_row["estimated_cost"];
                        }
                    }

                    $profit_val = $invoice_amt - $vendor_pay;
                    $inv_amt_totake = str_replace(",", "", number_format($profit_val, 0));

                    $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                    $rev_lastyr_tilldt = $rev_lastyr_tilldt + $inv_amt_totake;

                    $lisoftrans_lastyear .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(floatval($inv_amt_totake), 0) . "</td></tr>";
                    $ucbzw_lisoftrans_lastyear .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(floatval($inv_amt_totake), 0) . "</td></tr>";
                }
                if ($rev_lastyr_tilldt > 0) {
                    $lisoftrans_lastyear .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($rev_lastyr_tilldt, 0) . "</td></tr>";
                }
                $lisoftrans_lastyear .= "</table></span>";
            }

            //echo "summtd_SUMPO: " . $headtxt . " " . $summtd_SUMPO. " " . $monthly_qtd  . "<br>";
            if ($headtxt == "LAST TO LAST YEAR") {
                $monthly_qtd = isset($quota_in_st_en);
            }
            if ($summtd_SUMPO >= $monthly_qtd) {
                $color = "green";
            } elseif ($summtd_SUMPO < $monthly_qtd) {
                $color = "red";
            } else {
                $color = "black";
            };
            if ($monthly_qtd == 0) {
                $color = "black";
            }
            $MGArray[] = array(
                'name' => $rowemp["name"], 'name_initial' => $rowemp["initials"], 'emp_email' => $rowemp["email"], 'b2bempid' => $rowemp["b2b_id"], 'empid' => $rowemp["id"], 'start_Dt' => $start_Dt, 'end_Dt' => $end_Dt, 'deal_count' => $summtd_dealcnt, 'quota' => isset($quota_in_st_en),
                'quotatodate' => $monthly_qtd, 'creditAmount' => isset($creditAmount), 'po_entered' => $summtd_SUMPO, 'ttly' => $summ_ttly, 'percent_val' => $color, 'rev_lastyr_tilldt' => $rev_lastyr_tilldt, 'lisoftrans' => $lisoftrans . "<br>" . isset($popupRevenueContent), 'lisoftrans_ttly' => $lisoftrans_ttly, 'lisoftrans_lastyear' => isset($lisoftrans_lastyear),
                'ucbzw_lisoftrans' => $ucbzw_lisoftrans, 'ucbzw_popupRevenueContent' => isset($ucbzw_popupRevenueContent), 'ucbzw_lisoftrans_ttly' => isset($ucbzw_lisoftrans_ttly), 'ucbzw_lisoftrans_lastyear' => $ucbzw_lisoftrans_lastyear
            );
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
                    $MGArraysort_I[] = isset($MGArraytmp['companyID']);
                }
                array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
            }
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
        $tot_quota_deal_mtd = 0;
        $tot_rev_lastyr_tilldt = 0;
        $summtd_ttly_tot = 0;
        foreach ($MGArray as $MGArraytmp2) {

            $name = $MGArraytmp2["name"];
            $monthly_deal_qtd = $MGArraytmp2["deal_count"];
            $monthly_qtd = $MGArraytmp2["quota"];
            $monthly_qtd_TD = $MGArraytmp2["quotatodate"];
            $summtd_SUMPO = $MGArraytmp2["po_entered"];
            $summtd_ttly = $MGArraytmp2["ttly"];
            //$monthly_percentage = $MGArraytmp2["percent_val"];

            //if ($monthly_percentage >= 100 ) { $color_y = "green"; } elseif ($monthly_percentage >= 80 ) { $color_y = "E0B003"; } else { $color_y = "B03030"; };
            $color_y = $MGArraytmp2["percent_val"];

            if ($headtxt == "B2B Leaderboard") {
                echo "<tr><td bgColor='$tbl_color' align ='left'>" . $name . "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($monthly_qtd_TD, 0);
                echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color_y . "'>";
                echo "<a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
                echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
                echo "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color_y . "'>";
                echo number_format(($summtd_SUMPO / $monthly_qtd_TD) * 100, 2);
                echo "%</font></td>";
                echo "</tr>";
            } else {
                echo "<tr><td bgColor='$tbl_color' align ='left'>" . $name . "</td><td bgColor='$tbl_color' align = right>";
                echo number_format($monthly_deal_qtd, 0);
                if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    if (is_numeric($monthly_qtd)) {
                        echo "$" . number_format($monthly_qtd, 0);
                    }
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format($monthly_qtd_TD, 0);
                } else {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format($monthly_qtd, 0);
                }

                if ($headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                } else {
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                }

                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if ($summtd_SUMPO >= $monthly_qtd) {
                        $color = "green";
                    } elseif ($summtd_SUMPO < $monthly_qtd) {
                        $color = "red";
                    } else {
                        $color = "black";
                    };
                }
                $color_y_new = "black";
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if (($summtd_SUMPO * 100 / $monthly_qtd) >= 100) {
                        $color_y_new = "green";
                    } elseif (($summtd_SUMPO * 100 / $monthly_qtd) < 100 && $summtd_SUMPO > 0 && $monthly_qtd > 0) {
                        $color_y_new = "red";
                    } elseif ($summtd_SUMPO > 0 && $monthly_qtd == 0) {
                        $color_y_new = "green";
                    } elseif ($summtd_SUMPO == 0 && $monthly_qtd > 0) {
                        $color_y_new = "red";
                    } else {
                        $color_y_new = "black";
                    };
                } else {
                    if (($summtd_SUMPO * 100 / $monthly_qtd_TD) >= 100) {
                        $color_y_new = "green";
                    } elseif (($summtd_SUMPO * 100 / $monthly_qtd_TD) < 100 && $summtd_SUMPO > 0 && $monthly_qtd_TD > 0) {
                        $color_y_new = "red";
                    } elseif ($summtd_SUMPO > 0 && $monthly_qtd_TD == 0) {
                        $color_y_new = "green";
                    } elseif (($summtd_SUMPO == 0 || $summtd_SUMPO <= 0) && $monthly_qtd_TD > 0) {
                        $color_y_new = "red";
                    } else {
                        $color_y_new = "black";
                    };
                }

                echo "<a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y_new . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
                echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
                echo "</td>";
                if ($headtxt == "LAST YEAR") {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a href='#' onclick='load_div(66" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($MGArraytmp2["rev_lastyr_tilldt"], 0) . "</font></a>";
                    echo "<span id='66" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_lastyear"] . "</span>";
                    echo "</td>";
                }
                echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color_y_new . "'>";
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if ($summtd_SUMPO > 0 && $monthly_qtd > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd, 2) . "%";
                    }
                } else {
                    if ($summtd_SUMPO > 0 && $monthly_qtd_TD > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd_TD, 2) . "%";
                    }
                }
                echo "</font></td>";
                if ($ttylyesno == "ttylyes") {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a href='#' onclick='load_div(77" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summtd_ttly, 0) . "</font></a>";
                    echo "<span id='77" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_ttly"] . "</span>";
                    echo "</td>";
                }
                echo "</tr>";

                $summtd_ttly_tot = $summtd_ttly_tot + $summtd_ttly;
            }

            //$monthly_qtd = number_format($monthly_qtd,0);
            //$summtd_SUMPO = number_format($summtd_SUMPO,0);
            $monthly_deal_qtd = number_format($monthly_deal_qtd, 0);

            $monthly_qtd = $monthly_qtd ?? 0;
            $tot_quota_mtd = $tot_quota_mtd + round($monthly_qtd, 0);
            $tot_quota_mtd_TD = isset($tot_quota_mtd_TD) + round($monthly_qtd_TD, 0);
            $tot_quotaactual_mtd = $tot_quotaactual_mtd + round($summtd_SUMPO, 0);
            $tot_quota_deal_mtd = $tot_quota_deal_mtd + round($monthly_deal_qtd, 0);
            $tot_rev_lastyr_tilldt = $tot_rev_lastyr_tilldt + round($MGArraytmp2["rev_lastyr_tilldt"], 0);

            $tot_creditAmount = isset($tot_creditAmount) + round($MGArraytmp2["creditAmount"], 0);

            $ucbzw_lisoftrans_details = $MGArraytmp2["ucbzw_lisoftrans"];
            $ucbzw_popupRevenueContent = $MGArraytmp2["ucbzw_popupRevenueContent"];
            $ucbzw_lisoftrans_lastyear_details = $MGArraytmp2["ucbzw_lisoftrans_lastyear"];
            $ucbzw_lisoftrans_ttly_details = $MGArraytmp2["ucbzw_lisoftrans_ttly"];
        }

        echo "</table>";

        //For UCBZW Rep
        if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            if ($tot_quotaactual_mtd >= $tot_quota_mtd) {
                $color = "green";
            } elseif ($tot_quotaactual_mtd < $tot_quota_mtd && $tot_quotaactual_mtd > 0) {
                $color = "red";
            } else {
                $color = "black";
            };
        }
        $color_y_new = "black";
        if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            if (($tot_quotaactual_mtd * 100 / $tot_quota_mtd) >= 100) {
                $color_y_new = "green";
            } elseif (($tot_quotaactual_mtd * 100 / $tot_quota_mtd) < 100 && $tot_quotaactual_mtd > 0 && $tot_quota_mtd > 0) {
                $color_y_new = "red";
            } elseif ($tot_quotaactual_mtd > 0 && $tot_quota_mtd == 0) {
                $color_y_new = "green";
            } elseif ($tot_quotaactual_mtd == 0 && $tot_quota_mtd > 0) {
                $color_y_new = "red";
            } else {
                $color_y_new = "black";
            };
        } else {
            if (($tot_quotaactual_mtd * 100 / isset($tot_quota_mtd_TD)) >= 100) {
                $color_y_new = "green";
            } elseif (($tot_quotaactual_mtd * 100 / isset($tot_quota_mtd_TD)) < 100 && $tot_quotaactual_mtd > 0 && isset($tot_quota_mtd_TD) > 0) {
                $color_y_new = "red";
            } elseif ($tot_quotaactual_mtd > 0 && isset($tot_quota_mtd_TD) == 0) {
                $color_y_new = "green";
            } elseif ($tot_quotaactual_mtd == 0 && isset($tot_quota_mtd_TD) > 0) {
                $color_y_new = "red";
            } else {
                $color_y_new = "black";
            };
        }

        $ucbzw_lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $ucbzw_lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

        $ucbzw_lisoftrans .= isset($ucbzw_lisoftrans_details);

        if ($tot_quotaactual_mtd > 0) {
            $ucbzw_lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_quotaactual_mtd, 0) . "</td></tr>";
        }
        $ucbzw_lisoftrans .= "</table></span>";

        $ucbzw_popupRevenueContent_lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $ucbzw_popupRevenueContent_lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

        $ucbzw_popupRevenueContent_lisoftrans .= $ucbzw_popupRevenueContent;

        if (isset($tot_creditAmount) > 0) {
            $ucbzw_popupRevenueContent_lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_creditAmount, 0) . "</td></tr>";
        }
        $ucbzw_popupRevenueContent_lisoftrans .= "</table></span>";

        if ($headtxt == "LAST YEAR") {
            $ucbzw_lisoftrans_lastyear = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
            $ucbzw_lisoftrans_lastyear .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

            $ucbzw_lisoftrans_lastyear .= $ucbzw_lisoftrans_lastyear_details;
            $MGArraytmp2 = $MGArraytmp2 ?? [];
            if ($MGArraytmp2["rev_lastyr_tilldt"] > 0) {
                $ucbzw_lisoftrans_lastyear .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($MGArraytmp2["rev_lastyr_tilldt"], 0) . "</td></tr>";
            }
            $ucbzw_lisoftrans_lastyear .= "</table></span>";
        }

        if ($ttylyesno == "ttylyes") {
            $ucbzw_lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
            $ucbzw_lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

            $ucbzw_lisoftrans_ttly .= isset($ucbzw_lisoftrans_ttly_details);

            if ($summtd_ttly_tot > 0) {
                $ucbzw_lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_ttly_tot, 0) . "</td></tr>";
            }
            $ucbzw_lisoftrans_ttly .= "</table></span>";
        }
        //For UCBZW Rep	

        if ($tot_quota_mtd != 0) {
            $tot_monthper = 100 * $tot_quotaactual_mtd / $tot_quota_mtd;
        }
        if ($tot_monthper >= 100) {
            $color = "green";
        } elseif ($tot_monthper >= 80) {
            $color = "red";
        } else {
            $color = "black";
        };

        $quota_ov = 0;
        if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
            $sql_ovdata = "SELECT quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
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
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall_ucbzw_share where  quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $sql_ovdata = "SELECT quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value;
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov = $quota_ov + $rowemp_ovdata["quota"];
            }
        }


        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" && $po_flg != "yes") {
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
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " order by quota_month");
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
        //$lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";
        $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
		<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
		<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Share Amount</td></tr>";

        if ($tilltoday == "Y") {
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        } else {
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
        }
        if ($headtxt == "THIS YEAR") {
            //echo $sqlmtd . "<br>";
        }
        $resultmtd = db_query($sqlmtd);
        $summtd_SUMPO = 0;
        $summtd_dealcnt = 0;
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
            if ($finalpaid_amt <> 0) {
                $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] <> 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] <> 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
            }

            $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
			FROM loop_transaction_buyer 
			LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
			INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
			WHERE loop_transaction_buyer.Leaderboard = 'UCBZW' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
			and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            db();
            $resB2bCogs = db_query($qryB2bCogs);

            $estimated_cost = 0;
            while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                $estimated_cost = str_replace(",", "", number_format($resB2bCogs_row['estimated_cost'], 0));
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


            $lisoftrans .= "<tr><td bgColor='#E4EAEB'>" . isset($sr_no) . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
			<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
			<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
			<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
        }
        if ($summtd_SUMPO > 0) {
            $tot_quotaactual_mtd = $summtd_SUMPO;
            //$lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO,0) . "</td></tr>";
            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
        }
        $lisoftrans .= "</table></span>";

        //This Time Last Year TTLY
        $summ_ttly = 0;
        $tot_quotaactual_mtd_ttly = 0;
        if ($ttylyesno == "ttylyes") {
            $start_Date = strtotime('-1 year', strtotime($start_Dt));
            if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
                $end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));
            } else {
                $end_Date = strtotime('-1 year', strtotime($end_Dt));
            }

            $start_Dt_ttly = Date('Y-m-d', $start_Date);
            $end_Dt_ttly = Date('Y-m-d', $end_Date);

            $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
            $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";
            if ($tilltoday == "Y") {
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
            }

            $resultmtd = db_query($sqlmtd);
            while ($summtd = array_shift($resultmtd)) {
                $nickname = "";
                if ($summtd["b2bid"] > 0) {
                    db_b2b();
                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $summtd["b2bid"];
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
                    db();
                } else {
                    $nickname = $summtd["warehouse_name"];
                }

                $finalpaid_amt = 0;


                $inv_amt_totake = 0;
                if ($finalpaid_amt <> 0) {
                    $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] <> 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] <> 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
                }

                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'UCBZW' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                db();
                $resB2bCogs = db_query($qryB2bCogs);

                $estimated_cost = 0;
                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", number_format($resB2bCogs_row['estimated_cost'], 0));
                }
                $summ_ttly = $summ_ttly + str_replace(",", "", number_format(($inv_amt_totake - $estimated_cost), 0));

                $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
            }
            if ($summ_ttly > 0) {
                $tot_quotaactual_mtd_ttly = $summ_ttly;
                $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td></tr>";
            }
            $lisoftrans_ttly .= "</table></span>";
        }
        //This Time Last Year TTLY

        $rev_lastyr_tilldt = 0;
        if ($headtxt == "LAST YEAR") {
            $dt_year_value_1 = date('Y') - 1;
            $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
            $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total <> 0  and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
            $resultmtd = db_query($sqlmtd);
            while ($summtd = array_shift($resultmtd)) {

                $invoice_amt = 0;
                $inv_amt_totake = 0;
                $inv_qry = "SELECT quantity, price FROM loop_invoice_items WHERE trans_rec_id = '" . $summtd["id"] . "' ORDER BY id ASC";
                db();
                $inv_res = db_query($inv_qry);
                while ($inv_row = array_shift($inv_res)) {
                    $invoice_amt += $inv_row["quantity"] * $inv_row["price"];
                }
                if ($invoice_amt == 0) {
                    $invoice_amt = $summtd["invsent_amt"];
                }

                $vendor_pay = 0;
                $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $summtd["id"];
                db();
                $dt_view_res = db_query($dt_view_qry);
                $num_rows = tep_db_num_rows($dt_view_res);
                if ($num_rows > 0) {
                    while ($dt_view_row = array_shift($dt_view_res)) {
                        $vendor_pay += $dt_view_row["estimated_cost"];
                    }
                }

                $profit_val = $invoice_amt - $vendor_pay;
                $inv_amt_totake = $profit_val;

                $rev_lastyr_tilldt = $rev_lastyr_tilldt + $inv_amt_totake;
            }
            //$tot_quotaactual_mtd = $rev_lastyr_tilldt;
        }

        if ($headtxt == "LAST TO LAST YEAR") {
            //$monthly_qtd = $quota_in_st_en;
        }

        //for the B2b op team calc
        $MGArraytmp2 = $MGArraytmp2 ?? [];
        $global_ucbzwtot_quota_deal_mtd = $tot_quota_deal_mtd;
        $global_ucbzwquota_ov = $quota_ov;
        $global_ucbzwmonthly_qtd = isset($monthly_qtd);
        $global_ucbzwtot_quotaactual_mtd = $tot_quotaactual_mtd;
        $global_ucbzwunqid = $unqid;
        $global_ucbzwempid = $MGArraytmp2["empid"];
        $global_ucbzlisoftrans = $lisoftrans;
        $global_ucbzwrev_lastyr_tilldt_n = $rev_lastyr_tilldt;
        $global_ucbzwsumm_ttly = $summ_ttly;
        $global_ucbzwlisoftrans_ttly = isset($lisoftrans_ttly);

        //if ($headtxt != "B2B Leaderboard"){	

        //for the UCBZW total
        echo "<br/><br/><table cellSpacing='1' cellPadding='1' border='0' width='650'>";
        echo "	<tr>";
        echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[UCB] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650' id='table9' class='tablesorter'>";
        echo "<thead>";

        if ($headtxt == "B2B Leaderboard") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Department</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Deals</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Share</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>% of Share</u></th>";
            echo "	</tr>";
        } else if ($po_flg == "yes") {
        } else {

            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Department</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Deals</u></th>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            }
            if ($headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Share to Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Share</u></th>";
            }
            if ($headtxt == "LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Share To Date</u></th>";
            }
            echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>%</u></th>";
            if ($ttylyesno == "ttylyes") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>TTLY</u></th>";
            }
            echo "	</tr>";
        }
        echo "</thead>";



        $global_ucbzwtot_quotaactual_mtd = $global_ucbzwtot_quotaactual_mtd + isset($creditAmount);
        //	$global_ucbzwtot_quotaactual_mtd = $global_ucbzwtot_quotaactual_mtd + $creditAmount_sort;
        $global_ucbzwtot_quota_deal_mtd = $global_ucbzwtot_quota_deal_mtd + isset($totalCnt);

        echo "<tr><td bgColor='$tbl_color' align ='left'>UCBZW</td>";
        if ($headtxt == "GMI Leaderboard") {
        } else {
            echo "<td bgColor='$tbl_color' align = right>" . number_format($global_ucbzwtot_quota_deal_mtd, 0) . "</td>";
        }

        echo "<td bgColor='$tbl_color' align = 'right'>";
        if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
            echo "$" . number_format($global_ucbzwquota_ov, 0);
            echo "</td><td bgColor='$tbl_color' align = 'right'>";
            echo "$" . number_format(floatval($global_ucbzwmonthly_qtd), 0);
            if ($global_ucbzwtot_quotaactual_mtd >= $global_ucbzwmonthly_qtd) {
                $color = "green";
            } elseif ($global_ucbzwtot_quotaactual_mtd < $global_ucbzwmonthly_qtd && $global_ucbzwtot_quotaactual_mtd > 0) {
                $color = "red";
            } else {
                $color = "black";
            };
        } else {
            if ($global_ucbzwtot_quotaactual_mtd >= $global_ucbzwquota_ov) {
                $color = "green";
            } elseif ($global_ucbzwtot_quotaactual_mtd < $global_ucbzwquota_ov && $global_ucbzwtot_quotaactual_mtd > 0) {
                $color = "red";
            } else {
                $color = "black";
            };
            echo "$" . number_format($global_ucbzwquota_ov, 0);
        }
        echo "</strong></td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

        echo "<a href='#' onclick='load_div(99" . $global_ucbzwunqid . "88); return false;'><font color='" . $color . "'>$" . number_format($global_ucbzwtot_quotaactual_mtd, 0) . "</font></a>";
        echo "<span id='99" . $global_ucbzwunqid . "88' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $global_ucbzlisoftrans . "<br><br>" . isset($popupRevenueContent) . "<br></span>";

        echo "</font></td>";
        if ($headtxt == "LAST YEAR") {
            echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($global_ucbzwrev_lastyr_tilldt_n, 0);
            echo "</font></td>";
        }
        echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";
        if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            echo number_format($global_ucbzwtot_quotaactual_mtd * 100 / $global_ucbzwquota_ov, 2);
        } else if ($headtxt == "GMI Leaderboard") {
            echo number_format(($global_ucbzwtot_quotaactual_mtd / $global_ucbzwquota_ov) * 100, 2);
        } else {
            if ($global_ucbzwmonthly_qtd != 0) {
                echo number_format($global_ucbzwtot_quotaactual_mtd * 100 / $global_ucbzwmonthly_qtd, 2);
            }
        }
        echo "%</font></td>";
        if ($ttylyesno == "ttylyes") {
            echo "<td bgColor='$tbl_color' align = 'right'>";

            echo "<a href='#' onclick='load_div(88" . $global_ucbzwunqid . "88); return false;'><font color=black>$" . number_format($global_ucbzwsumm_ttly + isset($summ_ttly_ucbzw_ttly) ? isset($summ_ttly_ucbzw_ttly) : 0, 0) . "</font></a>";
            echo "<span id='88" . $global_ucbzwunqid . "88' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $global_ucbzwlisoftrans_ttly . "<br><br>" . isset($popupRevenueContent_ttly) . "</span>";

            echo "</font></td>";
        }
        echo "</tr>";
        echo "</table>";
        //}		
    }

    function B2cperday(string $start_Dt, string $end_Dt): void
    {
        $revenue = 0;
        $sqlmtd = "SELECT sum(value) AS revenue FROM orders_total where class = 'ot_total' and orders_id in (Select orders_id from orders WHERE customers_name <> '' and date_purchased BETWEEN '" . Date("Y-m-d", strtotime($start_Dt)) . "'  AND '" . Date("Y-m-d", strtotime($end_Dt)) . " 23:59:59')";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $revenue = $summtd["revenue"];
        }

        $sqlmtd = "SELECT SUM(discount_value) AS revenue FROM gift_certificate_to_orders where orders_id > 0 and entry_date BETWEEN '" . Date("Y-m-d", strtotime($start_Dt)) . "'  AND '" . Date("Y-m-d", strtotime($end_Dt)) . " 23:59:59'";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $revenue = $revenue + $summtd["revenue"];
        }

        $order_cnt = 0;
        $sqlmtd = "SELECT count(orders_id) AS order_cnt FROM orders WHERE customers_name <> '' and date_purchased BETWEEN '" . Date("Y-m-d", strtotime($start_Dt)) . "'  AND '" . Date("Y-m-d", strtotime($end_Dt)) . " 23:59:59'";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $order_cnt = $summtd["order_cnt"];
        }

        echo "<th align='center' bgColor='#ABC5DF'>&nbsp;</th><th align='center' bgColor='#ABC5DF'>Number of Orders</th><th align='center' bgColor='#ABC5DF'> Revenue </th></tr><tr>";

        echo "<td align='center' bgColor='#E4EAEB'>B2C</td><td align='center' bgColor='#E4EAEB'>" . $order_cnt . "</td><td align='center' bgColor='#E4EAEB'>$" . number_format($revenue, 2) . "</td>";
    }


    function B2ctbl_new_daterange(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        bool $tilltoday,
        int $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        bool $po_flg,
        string $unqid
    ): void {

        $dt_year_value = date('Y', strtotime($start_Dt));
        $dt_month_value = date('m', strtotime($start_Dt));
        $current_year_value = date('Y');

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='520'>";
        echo "	<tr>";
        echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2C] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='520' id='table9' class='tablesorter'>";
        echo "<thead>";
        echo "	<tr style='height:50px;'>";
        echo "		<th align='left' bgColor='$tbl_head_color' width='220px'><u>Employee</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Orders Quota</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Orders</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>G.Profit Quota</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
        echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>%</u></th>";
        echo "	</tr>";
        echo "</thead>";
        echo "<tbody>";

        $order_cnt = 0;
        $sqlmtd = "SELECT count(orders_id) AS order_cnt FROM orders WHERE customers_name <> '' and date_purchased BETWEEN '" . Date("Y-m-d", strtotime($start_Dt)) . "'  AND '" . Date("Y-m-d", strtotime($end_Dt)) . " 23:59:59'";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $order_cnt = $summtd["order_cnt"];
        }


        $quota_ov = 0;
        $order_quota_ov = 0;
        $begin = new DateTime($start_Dt);
        $end   = new DateTime($end_Dt);
        $currentdate  = new DateTime(date("Y-m-d"));
        $quota = 0;
        $quota_to_date = 0;
        for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
            $start_Dt_tmp = $datecnt->format("Y-m-d");
            $quota_mtd = 0;
            $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall where b2borb2c = 'b2c' and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
            db();
            $result_empq = db_query($newsel);
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota_one_day = $rowemp_empq["quota"] / date('t', strtotime($start_Dt_tmp));
                $quota = $quota + $quota_one_day;
                $quota_one_day = $rowemp_empq["deal_quota"] / date('t', strtotime($start_Dt_tmp));
                $order_quota_ov = $order_quota_ov + $quota_one_day;
                //echo "week quota: " . $quota . "<br>";
            }
        }
        $quota_ov = $quota;

        $quota = 0;
        $sqlmtd = "SELECT sum(value) AS revenue FROM orders_total where class = 'ot_total' and orders_id in (Select orders_id from orders WHERE customers_name <> '' and date_purchased BETWEEN '" . date(date('Y', strtotime($start_Dt)) - 1 . "-" . date('m', strtotime($start_Dt)) . "-" . date('d', strtotime($start_Dt))) . "' AND '" . date(date('Y', strtotime($end_Dt)) - 1 . "-" . date('m', strtotime($end_Dt)) . "-" . date('d', strtotime($end_Dt))) . " 23:59:59')";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $quota = $summtd["revenue"];
        }
        $sqlmtd = "SELECT SUM(discount_value) AS revenue FROM gift_certificate_to_orders where orders_id > 0 and entry_date BETWEEN '" . date(date('Y', strtotime($start_Dt)) - 1 . "-" . date('m', strtotime($start_Dt)) . "-" . date('d', strtotime($start_Dt))) . "' AND '" . date(date('Y', strtotime($end_Dt)) - 1 . "-" . date('m', strtotime($end_Dt)) . "-" . date('d', strtotime($end_Dt))) . " 23:59:59'";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $quota = $quota + $summtd["revenue"];
        }

        $quota_to_dt = 0;
        $sqlmtd = "SELECT sum(value) AS revenue FROM orders_total where class = 'ot_total' and orders_id in (Select orders_id from orders WHERE customers_name <> '' and date_purchased BETWEEN '" . date(date('Y', strtotime($start_Dt)) - 1 . "-" . date('m', strtotime($start_Dt)) . "-" . date('d', strtotime($start_Dt))) . "' AND '" . date(date('Y', strtotime($end_Dt)) - 1 . "-" . date('m') . "-" . date('d')) . " 23:59:59')";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $quota_to_dt = $summtd["revenue"];
        }
        $sqlmtd = "SELECT SUM(discount_value) AS revenue FROM gift_certificate_to_orders where orders_id > 0 and entry_date BETWEEN '" . date(date('Y', strtotime($start_Dt)) - 1 . "-" . date('m', strtotime($start_Dt)) . "-" . date('d', strtotime($start_Dt))) . "' AND '" . date(date('Y', strtotime($end_Dt)) - 1 . "-" . date('m') . "-" . date('d')) . " 23:59:59'";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $quota_to_dt = $quota_to_dt + $summtd["revenue"];
        }

        $revenue = 0;
        $sqlmtd = "SELECT sum(value) AS revenue FROM orders_total where class = 'ot_total' and orders_id in (Select orders_id from orders WHERE customers_name <> '' and date_purchased BETWEEN '" . Date("Y-m-d", strtotime($start_Dt)) . "'  AND '" . Date("Y-m-d", strtotime($end_Dt)) . " 23:59:59')";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $revenue = $summtd["revenue"];
        }

        $sqlmtd = "SELECT SUM(discount_value) AS revenue FROM gift_certificate_to_orders where orders_id > 0 and entry_date BETWEEN '" . Date("Y-m-d", strtotime($start_Dt)) . "'  AND '" . Date("Y-m-d", strtotime($end_Dt)) . " 23:59:59'";
        $resultmtd = db_query($sqlmtd);
        while ($summtd = array_shift($resultmtd)) {
            $revenue = $revenue + $summtd["revenue"];
        }

        $rev_lastyr_tilldt = 0;
        if ($headtxt == "LAST YEAR") {
            $dt_year_value_1 = date('Y') - 1;
            $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
            $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');
            $sqlmtd = "SELECT sum(value) AS revenue FROM orders_total where class = 'ot_total' and orders_id in (Select orders_id from orders WHERE customers_name <> '' and date_purchased BETWEEN '" . Date("Y-m-d", strtotime($start_Dt_lasty)) . "'  AND '" . Date("Y-m-d", strtotime($end_Dt_lasty)) . " 23:59:59')";
            $resultmtd = db_query($sqlmtd);
            while ($summtd = array_shift($resultmtd)) {
                $rev_lastyr_tilldt = $summtd["revenue"];
            }

            $sqlmtd = "SELECT SUM(discount_value) AS revenue FROM gift_certificate_to_orders where orders_id > 0 and entry_date BETWEEN '" . Date("Y-m-d", strtotime($start_Dt_lasty)) . "'  AND '" . Date("Y-m-d", strtotime($end_Dt_lasty)) . " 23:59:59'";
            $resultmtd = db_query($sqlmtd);
            while ($summtd = array_shift($resultmtd)) {
                $rev_lastyr_tilldt = $rev_lastyr_tilldt + $summtd["revenue"];
            }
        }

        echo "<tr><td bgColor='$tbl_color' align ='left'>B2C</td>";
        echo "<td bgColor='$tbl_color' align ='right'>" . number_format($order_quota_ov, 0) . "</td>";
        echo "<td bgColor='$tbl_color' align = right>";
        echo number_format($order_cnt, 0);
        if ($revenue >= $quota_ov) {
            $color_y = "green";
        } elseif ($revenue < $quota_ov && $revenue > 0) {
            $color_y = "red";
        } else {
            $color_y = "black";
        };
        echo "</td><td bgColor='$tbl_color' align = 'right'>";
        echo "$" . number_format($quota_ov, 0);
        echo "</td><td bgColor='$tbl_color' align = 'right'>";
        echo "<font color='" . $color_y . "'>$" . number_format($revenue, 0) . "</font>";
        echo "</td>";
        echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color_y . "'>";
        echo number_format($revenue * 100 / $quota_ov, 2);
        echo "%</font></td>";
        echo "</tr>";

        echo "</tbody>";
        echo "</table>";
    }




    function getCurrentQuarter(int $timestamp = null): int
    {
        if (!$timestamp) $timestamp = time();
        $day = date('n', $timestamp);
        $quarter = (int) ceil($day / 3);
        return $quarter;
    }

    function getPreviousQuarter(int $timestamp = null): int
    {
        if (!$timestamp) $timestamp = time();
        $quarter = getCurrentQuarter($timestamp) - 1;
        if ($quarter < 1) {
            $quarter = 4;
        }
        return $quarter;
    }


    ?>

    <table width="100%">
        <tr>
            <td width="80%">
                <!-- Load the page by default with old logic - do not apply date range-->
                <table border="0">
                    <tr>
                        <td colspan="5" align="left">
                            <form method="get" name="rpt_leaderboard"
                                action="report_daily_chart_mgmt_ucbzw_gprofit_org.php">
                                <table border="0">
                                    <tr>
                                        <td>Date Range Selector:</td>
                                        <td>
                                            From:
                                            <input type="text" name="date_from" id="date_from" size="10"
                                                value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>">
                                            <a href="#"
                                                onclick="cal2xx.select(document.rpt_leaderboard.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;"
                                                name="dtanchor2xx" id="dtanchor2xx"><img border="0"
                                                    src="images/calendar.jpg"></a>
                                            <div ID="listdiv"
                                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                            </div>
                                            To:
                                            <input type="text" name="date_to" id="date_to" size="10"
                                                value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>">
                                            <a href="#"
                                                onclick="cal2xx.select(document.rpt_leaderboard.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;"
                                                name="dtanchor3xx" id="dtanchor3xx"><img border="0"
                                                    src="images/calendar.jpg"></a>
                                            <div ID="listdiv"
                                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                            </div>
                                        </td>
                                        <td>
                                            <input type=submit value="Run Report">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="5" align="left">
                            <table border="0" cellSpacing="1" cellPadding="1">
                                <tr>
                                    <td align="left" colspan="15" bgColor='#FFCC66'
                                        style="font-size: 10px;font-family: Arial, Helvetica, sans-serif;color: #333333;">
                                        <strong>Report Links</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank" href='report_daily_chart_mgmt_ver2.php' target="_blank">B2B
                                            Leaderboard Report</a>
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        &nbsp;
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank" href='report_mgmt_closed_deal_pipeline_summary.php'
                                            target="_blank">Closed Deal pipeline Summary</a>
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        &nbsp;
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank" href='report_mgmt_new_deal_spins.php' target="_blank">New
                                            Deal Spins</a>
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        &nbsp;
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank" href='report_mgmt_activity_tracking.php'
                                            target="_blank">Activity Tracking</a>
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        &nbsp;
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank" href='report_mgmt_sales_assignments.php'
                                            target="_blank">Sales Assignments</a>
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        &nbsp;
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank" href='report_mgmt_rescue_assignments.php'
                                            target="_blank">Rescue Assignments</a>
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        &nbsp;
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank" href='report_mgmt_other_assignments.php'
                                            target="_blank">Other Assignments</a>
                                    </td>
                                </tr>
                            </table>
                            <br>
                        </td>
                    </tr>

                    <?php
                    $in_dt_range = "no";
                    if ($_GET["date_from"] != "" && $_GET["date_to"] != "") {
                        $date_from_val = date("Y-m-d", strtotime($_GET["date_from"]));
                        $date_to_val = date("Y-m-d", strtotime($_GET["date_to"]));
                        $in_dt_range = "yes";
                    }

                    if ($in_dt_range == "no") {
                    ?>
                    <tr>
                        <td colspan="5">
                            <table>
                                <?php
                                    $global_tot_quota_deal_mtd = 0;
                                    $global_quota_ov = 0;
                                    $global_monthly_qtd = 0;
                                    $global_tot_quotaactual_mtd = 0;
                                    $global_unqid = 0;
                                    $global_empid = 0;
                                    $global_empid = 0;
                                    $global_rev_lastyr_tilldt = 0;
                                    $global_summ_ttly = 0;
                                    $global_lisoftrans_ttly = 0;

                                    $global_ucbzwtot_quota_deal_mtd = 0;
                                    $global_ucbzwquota_ov = 0;
                                    $global_ucbzwmonthly_qtd = 0;
                                    $global_ucbzwtot_quotaactual_mtd = 0;
                                    $global_ucbzwunqid = 0;
                                    $global_ucbzwempid = 0;
                                    $global_ucbzwempid = 0;
                                    $global_ucbzwrev_lastyr_tilldt = 0;
                                    $global_ucbzwsumm_ttly = 0;
                                    $global_ucbzwlisoftrans_ttly = 0;

                                    if ($in_dt_range != "yes") {
                                        $time = strtotime(Date('Y-m-d'));
                                    } else {
                                        $time = strtotime($date_from_val);
                                    }

                                    /*if (date('l',$time) != "Friday") {
						$st_friday = strtotime('last friday', $time);
					} else {
						$st_friday = $time;
					}
					*/
                                    if (date('l', $time) != "Sunday") {
                                        $st_friday = strtotime('last sunday', $time);
                                    } else {
                                        $st_friday = $time;
                                    }

                                    $st_friday_last = strtotime('-7 days', $st_friday);
                                    $st_thursday_last = strtotime('+6 days', $st_friday_last);
                                    $st_thursday = strtotime('+6 days', $st_friday);

                                    $st_date_this_week = Date('Y-m-d', $st_friday);
                                    $end_date_this_week = Date('Y-m-d', $st_thursday);

                                    $last_yr = $last_yr ?? "";

                                    $st_date_last_y = Date($last_yr . '-m-d', $st_friday);
                                    $end_date_last_y = Date($last_yr . '-m-d', $st_thursday);

                                    $st_friday_last = Date('Y-m-d', $st_friday_last);
                                    $st_thursday_last = Date('Y-m-d', $st_thursday_last);

                                    $unqid = 1;
                                    ?>

                                <tr>
                                    <td>
                                        <?php
                                            if ($in_dt_range != "yes") {
                                                $st_date = Date('Y-m-01');
                                                $end_date = Date('Y-m-t');

                                                //$st_lastmonth = date('Y-m-01', strtotime('last month'));
                                                //$end_lastmonth = date('Y-m-t', strtotime('last month'));
                                                $st_lastmonth = date("Y-n-j", strtotime("first day of previous month"));
                                                $end_lastmonth = date("Y-n-j", strtotime("last day of previous month"));

                                                $st_lastmonth_lastyr = date($last_yr . '-m-01', strtotime($st_date));
                                                $end_lastmonth_lastyr = date($last_yr . '-m-t', strtotime($end_date));
                                            } else {
                                                $date_from_val = $date_from_val ?? "";
                                                if (!empty($date_from_val)) {
                                                    $st_date = Date('Y-m-01', strtotime($date_from_val));
                                                    $end_date = Date('Y-m-t', strtotime($date_from_val));
                                                }

                                                $st_lastmonth = date('Y-m-01', strtotime($date_from_val . ' last month'));
                                                $end_lastmonth = date('Y-m-t', strtotime($date_from_val . ' last month'));

                                                $st_lastmonth_lastyr = date($last_yr . '-m-01', strtotime($st_date));
                                                $end_lastmonth_lastyr = date($last_yr . '-m-t', strtotime($end_date));
                                            }

                                            $unqid = $unqid + 1;
                                            $this_month_st_dt = $st_date;
                                            $this_month_end_dt = $end_date;

                                            ?>

                                        <?php

                                            // leadertbl_ucbzw($st_date, $end_date, "THIS MONTH", 'Y', 'Y', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes", $in_dt_range);
                                            leadertbl_ucbzw(
                                                $st_date,          // $start_Dt (string)
                                                $end_date,         // $end_Dt (string)
                                                "THIS MONTH",      // $headtxt (string)
                                                true,              // $tilltoday (bool) - assuming 'Y' means true
                                                (int) date('Y'),   // $currentyr (int) - current year as integer
                                                '#FFFAD0',         // $tbl_head_color (string)
                                                '#FFFAD0',         // $tbl_color (string)
                                                false,             // $po_flg (bool) - assuming 'no' means false
                                                (string) $unqid,   // $unqid (string) - convert $unqid to string
                                                false,             // $ttylyesno (bool) - assuming 'ttylyes' means false
                                                (bool) $in_dt_range // $in_dt_range (bool) - convert $in_dt_range to boolean
                                            );

                                            ?>
                                    </td>
                                    <td width="50">&nbsp;</td>
                                    <td>
                                        <?php
                                            $last_month_st_dt = $st_lastmonth;
                                            $last_month_end_dt = $end_lastmonth;
                                            $unqid = $unqid + 1;
                                            // leadertbl_ucbzw($st_lastmonth, $end_lastmonth, "LAST MONTH", 'N', 'N', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes", $in_dt_range);
                                            leadertbl_ucbzw(
                                                $st_lastmonth,     // $start_Dt (string)
                                                $end_lastmonth,    // $end_Dt (string)
                                                "LAST MONTH",      // $headtxt (string)
                                                false,             // $tilltoday (bool) - assuming 'N' means false
                                                (int) date('Y'),         // $currentyr (int) - current year as integer
                                                '#FFFAD0',         // $tbl_head_color (string)
                                                '#FFFAD0',         // $tbl_color (string)
                                                false,             // $po_flg (bool) - assuming 'no' means false
                                                (string) $unqid,   // $unqid (string) - convert $unqid to string
                                                false,             // $ttylyesno (bool) - assuming 'ttylyes' means false
                                                (bool) $in_dt_range // $in_dt_range (bool) - convert $in_dt_range to boolean
                                            );


                                            ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php

                                            if ($in_dt_range != "yes") {
                                                $quarter = getCurrentQuarter();
                                                $year = date('Y');
                                            } else {
                                                $date_from_val = $date_from_val ?? "";

                                                $quarter = getCurrentQuarter(strtotime($date_from_val));
                                                $year = date('Y', strtotime($date_from_val));
                                            }
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

                                            if ($in_dt_range != "yes") {
                                                $quarter = getPreviousQuarter();
                                                $year = date('Y');
                                            } else {
                                                $date_from_val = $date_from_val ?? "";

                                                $quarter = getPreviousQuarter(strtotime($date_from_val));
                                                $year = date('Y', strtotime($date_from_val));
                                            }
                                            $st_lastqtr_n = new DateTime($year . '-' . ($quarter * 3 - 2) . '-1');
                                            //Get first day of first month of next quarter
                                            $endMonth = $quarter * 3 + 1;
                                            if ($endMonth > 12) {
                                                $endMonth = 1;
                                                $year++;
                                            }
                                            $end_lastqtr_n = new DateTime($year . '-' . $endMonth . '-1');

                                            //Subtract 1 second to get last day of prior month
                                            $end_lastqtr_n->sub(new DateInterval('PT1S'));

                                            //$st_lastqtr = $st_lastqtr_n->format('Y-m-d');
                                            //$end_lastqtr = $end_lastqtr_n->format('Y-m-d');
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
                                            $last_yr = $last_yr ?? "";
                                            $st_lastqtr_lastyr = date($last_yr . '-m-01', strtotime($st_date));
                                            $end_lastqtr_lastyr = date($last_yr . '-m-t', strtotime($end_date));

                                            $unqid = $unqid + 1;

                                            $this_qtr_st_dt = $st_date;
                                            $this_qtr_end_dt = $end_date;
                                            ?>

                                        <?php
                                            //leadertbl_ucbzw($st_date, $end_date, "THIS QUARTER", 'Y', 'Y', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes", $in_dt_range);
                                            leadertbl_ucbzw(
                                                $st_date,         // $start_Dt (string)
                                                $end_date,        // $end_Dt (string)
                                                "THIS QUARTER",   // $headtxt (string)
                                                true,             // $tilltoday (bool) - assuming 'Y' means true
                                                (int) date('Y'),        // $currentyr (int) - current year as integer
                                                '#FFF0F0',        // $tbl_head_color (string)
                                                '#FFF0F0',        // $tbl_color (string)
                                                false,            // $po_flg (bool) - assuming 'no' means false
                                                (string) $unqid,  // $unqid (string) - convert $unqid to string
                                                false,            // $ttylyesno (bool) - assuming 'ttylyes' means false
                                                (bool) $in_dt_range // $in_dt_range (bool) - convert $in_dt_range to boolean
                                            );
                                            ?>
                                    </td>
                                    <td width="50">&nbsp;</td>
                                    <td>
                                        <?php
                                            $unqid = $unqid + 1;
                                            $st_lastqtr = $st_lastqtr ?? "";
                                            $end_lastqtr = $end_lastqtr ?? "";
                                            $last_qtr_st_dt = $st_lastqtr;
                                            $last_qtr_end_dt = $end_lastqtr;

                                            //leadertbl_ucbzw($st_lastqtr, $end_lastqtr, "LAST QUARTER", 'N', 'N', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes", $in_dt_range);
                                            leadertbl_ucbzw(
                                                $st_lastqtr,      // $start_Dt (string)
                                                $end_lastqtr,     // $end_Dt (string)
                                                "LAST QUARTER",   // $headtxt (string)
                                                false,            // $tilltoday (bool) - assuming 'N' means false
                                                (int) date('Y'),        // $currentyr (int) - current year as integer
                                                '#FFF0F0',        // $tbl_head_color (string)
                                                '#FFF0F0',        // $tbl_color (string)
                                                false,            // $po_flg (bool) - assuming 'no' means false
                                                (string) $unqid,  // $unqid (string) - convert $unqid to string
                                                false,            // $ttylyesno (bool) - assuming 'ttylyes' means false
                                                (bool) $in_dt_range // $in_dt_range (bool) - convert $in_dt_range to boolean
                                            );

                                            ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php
                                            if ($in_dt_range != "yes") {
                                                $st_date = Date('Y-01-01');
                                                $end_date = Date('Y-12-31');

                                                $st_lastyr = date('Y-01-01', strtotime('-1 year'));
                                                $end_lastyr = date('Y-12-31', strtotime('-1 year'));

                                                $st_lastyr_lastyr = date('Y-01-01', strtotime('-2 year'));
                                                $end_lastyr_lastyr = date('Y-12-31', strtotime('-2 year'));
                                            } else {
                                                $date_from_val = $date_from_val ?? "";
                                                $st_date = Date('Y-01-01', strtotime($date_from_val ?? ""));
                                                $end_date = Date('Y-12-31', strtotime($date_from_val ?? ""));

                                                $st_lastyr = date('Y-01-01', strtotime($date_from_val . ' -1 year'));
                                                $end_lastyr = date('Y-12-31', strtotime($date_from_val . ' -1 year'));

                                                $st_lastyr_lastyr = date('Y-01-01', strtotime($date_from_val . ' -2 year'));
                                                $end_lastyr_lastyr = date('Y-12-31', strtotime($date_from_val . ' -2 year'));
                                            }

                                            $this_yr_st_dt = $st_date;
                                            $this_yr_end_dt = $end_date;
                                            ?>

                                        <?php
                                            $unqid = $unqid + 1;
                                            //leadertbl_ucbzw($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylno", $in_dt_range);
                                            leadertbl_ucbzw(
                                                $st_date,         // $start_Dt (string)
                                                $end_date,        // $end_Dt (string)
                                                "THIS YEAR",      // $headtxt (string)
                                                true,             // $tilltoday (bool) - assuming 'Y' means true
                                                (int) date('Y'),  // $currentyr (int) - current year as integer
                                                '#EEE8CD',        // $tbl_head_color (string)
                                                '#EEE8CD',        // $tbl_color (string)
                                                false,            // $po_flg (bool) - assuming 'no' means false
                                                (string) $unqid,  // $unqid (string) - convert $unqid to string
                                                false,            // $ttylyesno (bool) - assuming 'ttylno' means false
                                                (bool) $in_dt_range // $in_dt_range (bool) - convert $in_dt_range to boolean
                                            );


                                            ?>
                                    </td>
                                    <td width="50">&nbsp;</td>
                                    <td>
                                        <?php
                                            $last_yr_st_dt = $st_lastyr;
                                            $last_yr_end_dt = $end_lastyr;
                                            $unqid = $unqid + 1;
                                            //leadertbl_ucbzw($st_lastyr, $end_lastyr, "LAST YEAR", 'N', 'N', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylno", $in_dt_range);
                                            leadertbl_ucbzw(
                                                $st_lastyr,       // $start_Dt (string)
                                                $end_lastyr,      // $end_Dt (string)
                                                "LAST YEAR",      // $headtxt (string)
                                                false,            // $tilltoday (bool) - assuming 'N' means false
                                                0,                // $currentyr (int) - assuming 'N' means 0
                                                '#EEE8CD',        // $tbl_head_color (string)
                                                '#EEE8CD',        // $tbl_color (string)
                                                false,            // $po_flg (bool) - assuming 'no' means false
                                                (string) $unqid,  // $unqid (string) - convert $unqid to string
                                                false,            // $ttylyesno (bool) - assuming 'ttylno' means false
                                                (bool) $in_dt_range // $in_dt_range (bool) - convert $in_dt_range to boolean
                                            );

                                            ?>
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>
                </table>
            </td>

            <td valign="top">

            </td>
        </tr>
    </table>

    <br /><br />

    <?php if ($in_dt_range == "yes") { ?>
    <br /><br />
    <table border="0">
        <tr>
            <td style="font-size:16pt;" colspan="3"><strong>Activity Tracking</strong></td>
        </tr>
        <tr>
            <td valign="top">
                <table cellSpacing="1" cellPadding="1" border="0" width="600" id="table14" class="tablesorter">
                    <thead>
                        <tr>
                            <th width='100px' bgColor='#ABC5DF'><u>Employee</u></th>
                            <th width='100px' bgColor='#ABC5DF'>&nbsp;</th>
                            <th width='60px' bgColor='#ABC5DF' align="center"><u>Today</u></th>
                            <th width='60px' bgColor='#ABC5DF' align="center"><u>Yesterday</u></th>
                            <th width='60px' bgColor='#ABC5DF' align="center"><u>Last 7</u></th>
                            <th width='60px' bgColor='#ABC5DF' align="center"><u>Last 30</u></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $contact_act_30 = 0;
                            $contact_act_7 = 0;
                            $contact_act_y = 0;
                            $contact_act_t = 0;
                            $contact_act_ph_30 = 0;
                            $contact_act_ph_7 = 0;
                            $contact_act_ph_y = 0;
                            $contact_act_ph_t = 0;
                            $quotes_sent_30 = 0;
                            $quotes_sent_7 = 0;
                            $quotes_sent_y = 0;
                            $quotes_sent_t = 0;
                            $tot30 = 0;
                            $tot7 = 0;
                            $toty = 0;
                            $totT = 0;

                            $sql = "SELECT id, b2b_id ,name, initials as EMPLOYEE, email FROM loop_employees WHERE status = 'Active' and leaderboard = 1 ORDER BY quota DESC";
                            db();
                            $result = db_query($sql);
                            while ($rowemp = array_shift($result)) {

                                echo "<tr><td rowspan='3' bgColor='#E4EAEB' width='100px'>" . $rowemp["name"] . "</td>";

                                echo "<td bgColor='#E4EAEB' width='100px'>Emails</td>";


                                if ($in_dt_range != "yes") {
                                    $sqlT = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp >= CURDATE()";
                                } else {
                                    $sqlT = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp = " . isset($date_from_val);
                                }
                                db_b2b();
                                $result_crm = db_query($sqlT);
                                $contact_act_ph1 = 0;
                                $contact_act_tmp = 0;
                                $eml_list = "";
                                while ($rowemp_crm = array_shift($result_crm)) {
                                    if ($rowemp_crm["type"] ==  "phone") {
                                        $contact_act_ph1 = $contact_act_ph1 + 1;
                                    }
                                    if ($rowemp_crm["type"] ==  "email") {
                                        $contact_act_tmp = $contact_act_tmp + 1;
                                    }
                                    $eml_list .= $rowemp_crm["EmailID"] . ", ";
                                }

                                db_email();
                                if ($in_dt_range != "yes") {
                                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate >= CURDATE() and fromadd = '" . $rowemp["email"] . "'");
                                } else {
                                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate = '" . isset($date_from_val) . "' and fromadd = '" . $rowemp["email"] . "'");
                                }
                                while ($rowemp_crm = array_shift($result_crm)) {
                                    $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                                }
                                $date_from_val = $date_from_val ?? "";
                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=T&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_tmp . "</a></td>";

                                $contact_act_t += $contact_act_tmp;
                                $contact_act_ph_t += $contact_act_ph1;

                                if ($in_dt_range != "yes") {
                                    $sqlY = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND";
                                } else {
                                    $date_from_val = $date_from_val ?? "";
                                    $sqlY = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));
                                }

                                db_b2b();
                                $result_crm = db_query($sqlY);
                                $contact_act_tmp = 0;
                                $contact_act_ph2 = 0;
                                $eml_list = "";
                                while ($rowemp_crm = array_shift($result_crm)) {
                                    $eml_list .= $rowemp_crm["EmailID"] . ", ";
                                    if ($rowemp_crm["type"] ==  "phone") {
                                        $contact_act_ph2 = $contact_act_ph2 + 1;
                                    }
                                    if ($rowemp_crm["type"] ==  "email") {
                                        $contact_act_tmp = $contact_act_tmp + 1;
                                    }
                                }

                                db_email();
                                if ($in_dt_range != "yes") {
                                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and fromadd = '" . $rowemp["email"] . "'");
                                } else {
                                    $date_from_val = $date_from_val ?? "";
                                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd = '" . $rowemp["email"] . "'");
                                }
                                while ($rowemp_crm = array_shift($result_crm)) {
                                    $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                                }
                                $date_from_val = $date_from_val ?? "";
                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=Y&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_tmp . "</a></td>";
                                $contact_act_y += $contact_act_tmp;
                                $contact_act_ph_y += $contact_act_ph2;

                                if ($in_dt_range != "yes") {
                                    $sql7 = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE()";
                                } else {
                                    $date_from_val = $date_from_val ?? "";
                                    $sql7 = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));
                                }
                                //$result_crm = db_query($sql7 );
                                //echo "<a target='_blank' href='report_daily_chart.php?CRMday=7&crmemp=" . $rowemp["EMPLOYEE"] . "'>" .  tep_db_num_rows($result7) . "</a>";
                                //$contact_act_7 += tep_db_num_rows($result7);

                                db_b2b();
                                $result_crm = db_query($sql7);
                                $contact_act_tmp = 0;
                                $contact_act_ph3 = 0;
                                $eml_list = "";
                                while ($rowemp_crm = array_shift($result_crm)) {
                                    $eml_list .= $rowemp_crm["EmailID"] . ", ";
                                    if ($rowemp_crm["type"] ==  "phone") {
                                        $contact_act_ph3 = $contact_act_ph3 + 1;
                                    }
                                    if ($rowemp_crm["type"] ==  "email") {
                                        $contact_act_tmp = $contact_act_tmp + 1;
                                    }
                                }

                                db_email();
                                if ($in_dt_range != "yes") {
                                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and fromadd = '" . $rowemp["email"] . "'");
                                } else {
                                    $date_from_val = $date_from_val ?? "";
                                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd = '" . $rowemp["email"] . "'");
                                }
                                while ($rowemp_crm = array_shift($result_crm)) {
                                    $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                                }
                                $date_from_val = $date_from_val ?? "";
                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=7&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_tmp . "</a></td>";
                                $contact_act_7 += $contact_act_tmp;
                                $contact_act_ph_7 += $contact_act_ph3;

                                if ($in_dt_range != "yes") {
                                    $sql30 = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE()";
                                } else {
                                    $date_from_val = $date_from_val ?? "";
                                    $sql30 = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));
                                }


                                db_b2b();
                                $result_crm = db_query($sql30);
                                $contact_act_tmp = 0;
                                $contact_act_ph4 = 0;
                                $eml_list = "";
                                while ($rowemp_crm = array_shift($result_crm)) {
                                    $eml_list .= $rowemp_crm["EmailID"] . ", ";
                                    if ($rowemp_crm["type"] ==  "phone") {
                                        $contact_act_ph4 = $contact_act_ph4 + 1;
                                    }
                                    if ($rowemp_crm["type"] ==  "email") {
                                        $contact_act_tmp = $contact_act_tmp + 1;
                                    }
                                }

                                db_email();
                                if ($in_dt_range != "yes") {
                                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and fromadd = '" . $rowemp["email"] . "'");
                                } else {
                                    $date_from_val = $date_from_val ?? "";
                                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd = '" . $rowemp["email"] . "'");
                                }
                                while ($rowemp_crm = array_shift($result_crm)) {
                                    $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                                }
                                $date_from_val = $date_from_val ?? "";
                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=30&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_tmp . "</a>";
                                echo "</td></tr>";
                                $contact_act_30 += $contact_act_tmp;
                                $contact_act_ph_30 += $contact_act_ph4;
                                $date_from_val = $date_from_val ?? "";
                                echo "<tr>";

                                echo "<td bgColor='#E4EAEB' width='100px'>Calls</td>";

                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=T&phone=y&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_ph1 . "</a></td>";

                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=Y&phone=y&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_ph2 . "</a></td>";

                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=7&phone=y&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_ph3 . "</a></td>";

                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=30&phone=y&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_ph4  . "</a>";
                                echo "</td></tr>";



                                echo "<tr><td bgColor='#E4EAEB' width='100px'>1st Time Customers</td>";

                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                if ($in_dt_range != "yes") {
                                    $sqlT = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate >= CURDATE()";
                                } else {
                                    $sqlT = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate = " . $date_from_val;
                                }
                                $result_new = db_query($sqlT);
                                $b2bempid = $b2bempid ?? "";
                                $date_from_val = $date_from_val ?? "";
                                echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=Today'>" . tep_db_num_rows($result_new) . "</a>";
                                $quotes_sent_t += tep_db_num_rows($result_new);
                                echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                                if ($in_dt_range != "yes") {
                                    $sqlY = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND";
                                } else {
                                    $date_from_val = $date_from_val ?? "";
                                    $sqlY = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate BETWEEN " . Date("Y-m-d 23:59:00", strtotime($date_from_val . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -1 days"));
                                }
                                $result_new = db_query($sqlY);
                                $b2bempid = $b2bempid ?? "";
                                $date_from_val = $date_from_val ?? "";
                                echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=yesterday'>" . tep_db_num_rows($result_new) . "</a>";
                                $quotes_sent_y += tep_db_num_rows($result_new);
                                echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                                if ($in_dt_range != "yes") {
                                    $sql7 = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE()";
                                } else {
                                    $date_from_val = $date_from_val ?? "";
                                    $sql7 = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));;
                                }
                                $result_new = db_query($sql7);
                                $b2bempid = $b2bempid ?? "";
                                $date_from_val = $date_from_val ?? "";
                                echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=7days'>" . tep_db_num_rows($result_new) . "</a>";
                                $quotes_sent_7 += tep_db_num_rows($result_new);
                                echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                if ($in_dt_range != "yes") {
                                    $sql30 = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE()";
                                } else {
                                    $date_from_val = $date_from_val ?? "";
                                    if (isset($date_from_val) && isset($b2bempid)) {
                                        $date_from_val = date("Y-m-d", strtotime($date_from_val)); // Ensure $date_from_val is a valid date string
                                        $sql30 = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND qstatus !=2 AND quoteDate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));
                                    }
                                }
                                $result_new = db_query($sql30);
                                if (isset($date_from_val) && isset($b2bempid)) {
                                    echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=month'>" . tep_db_num_rows($result_new) . "</a>";
                                }
                                $quotes_sent_30 += tep_db_num_rows($result_new);
                                echo "</td>";

                                //$sql = "SELECT DISTINCT(po_employee) FROM loop_transaction_buyer WHERE transaction_date BETWEEN SYSDATE() - INTERVAL 365 DAY AND SYSDATE() ORDER BY po_employee ASC";
                                //$result = db_query($sql );
                                //while ($rowemp = array_shift($result)) {

                                echo "</tr>";
                            }

                            echo "<tr><td bgColor='#E4EAEB' rowspan='3' align=center><b>Total</td><td bgColor='#E4EAEB' width='100px'>Emails</td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $contact_act_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $contact_act_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $contact_act_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                            echo "</strong></td><td bgColor='#E4EAEB' rowspan='3' align=center><b>Total</td><td bgColor='#E4EAEB' width='100px'>Calls</td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $contact_act_ph_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $contact_act_ph_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $contact_act_ph_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $contact_act_ph_30 . " </strong></td></tr><tr><td bgColor='#E4EAEB' width='100px'>Quotes Sent</td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $quotes_sent_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $quotes_sent_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $quotes_sent_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                            echo $quotes_sent_30 . " </strong></td>";
                            echo "</tr>";

                            echo "</tbody>";

                            ?>
                </table>

            </td>

        </tr>
    </table>
    <?php } ?>

    <?php } else {
                        //In date range
?>

    <?php

                        //leadertbl_ucbzw($date_from_val, $date_to_val, "B2B Leaderboard", 'N', 'N', '#ABC5DF', '#E4EAEB', 'no', isset($unqid), "ttylno"); 
                        leadertbl_ucbzw(
                            $date_from_val,    // $start_Dt (string)
                            $date_to_val,      // $end_Dt (string)
                            "B2B Leaderboard", // $headtxt (string)
                            false,             // $tilltoday (bool) - assuming 'N' means false
                            0,                 // $currentyr (int) - assuming 'N' means 0
                            '#ABC5DF',         // $tbl_head_color (string)
                            '#E4EAEB',         // $tbl_color (string)
                            false,             // $po_flg (bool) - assuming 'no' means false
                            isset($unqid) ? (string) $unqid : '', // $unqid (string) - converting boolean to string if set
                            false              // $ttylyesno (bool) - assuming 'ttylno' means false
                        );


    ?>


    <?php } ?>

</body>

</html>