<?php


if ($_REQUEST["no_sess"] == "yes") {
} else {
    //require("inc/header_session.php");
}

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();

// set_time_limit(0);
// ini_set('memory_limit', '-1');

?>

<!DOCTYPE HTML>

<html>

<head>
    <title>Pallet Leaderboard Report</title>

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

    function expand_activity_tracker(start_Dt, end_Dt, headtxt, tilltoday, currentyr, tbl_head_color, tbl_color, po_flg,
        unqid, ttylyesno, in_dt_range) {
        /*if(document.getElementById("table_activity_tracker").style.display == "none")
        {
        	document.getElementById("table_activity_tracker").style.display = "block";
        }
        else
        {
        	document.getElementById("table_activity_tracker").style.display = "none";
        }*/

        document.getElementById("table_b2b_activity_tracker_daily_avg").style.display = "block";
        document.getElementById("table_b2b_activity_tracker_daily_avg").innerHTML =
            "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("table_b2b_activity_tracker_daily_avg").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_daily_chart_mgmt_gprofit_activity_tracker_daily_avg.php?in_dt_range=" +
            encodeURIComponent(in_dt_range) + "&start_Dt=" + encodeURIComponent(start_Dt) + "&end_Dt=" +
            encodeURIComponent(end_Dt) + "&headtxt=" + encodeURIComponent(headtxt) + "&tilltoday=" +
            encodeURIComponent(tilltoday) + "&currentyr=" + encodeURIComponent(currentyr) + "&tbl_head_color=" +
            encodeURIComponent(tbl_head_color) + "&tbl_color=" + encodeURIComponent(tbl_color) + "&po_flg=" +
            encodeURIComponent(po_flg) + "&unqid=" + encodeURIComponent(unqid) + "&ttylyesno=" + encodeURIComponent(
                ttylyesno), true);
        xmlhttp.send();

    }

    function collapse_activity_tracker() {
        //document.getElementById("table_activity_tracker").style.display = "none";
        document.getElementById("table_b2b_activity_tracker_daily_avg").style.display = "none";

    }

    function expand_b2b_activity_tracker(start_Dt, end_Dt, headtxt, tilltoday, currentyr, tbl_head_color, tbl_color,
        po_flg, unqid, ttylyesno, in_dt_range) {
        /*if(document.getElementById("table_b2b_activity_tracker").style.display == "none")
        {
        	document.getElementById("table_b2b_activity_tracker").style.display = "block";
        }
        else
        {
        	document.getElementById("table_b2b_activity_tracker").style.display = "none";
        }*/
        document.getElementById("table_b2b_activity_tracker").style.display = "block";
        document.getElementById("table_b2b_activity_tracker").innerHTML =
            "<br/><br/>Loading .....<img src='https://loops.usedcardboardboxes.com/images/wait_animated.gif' />";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("table_b2b_activity_tracker").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_daily_chart_mgmt_activity_tracker_pallet.php?in_dt_range=" + encodeURIComponent(
                in_dt_range) + "&start_Dt=" + encodeURIComponent(start_Dt) + "&end_Dt=" + encodeURIComponent(
                end_Dt) + "&headtxt=" + encodeURIComponent(headtxt) + "&tilltoday=" + encodeURIComponent(
                tilltoday) +
            "&currentyr=" + encodeURIComponent(currentyr) + "&tbl_head_color=" + encodeURIComponent(
                tbl_head_color) + "&tbl_color=" + encodeURIComponent(tbl_color) + "&po_flg=" + encodeURIComponent(
                po_flg) + "&unqid=" + encodeURIComponent(unqid) + "&ttylyesno=" + encodeURIComponent(ttylyesno),
            true);
        xmlhttp.send();
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
    <?php
    function b2ctbl(string $start_Dt, string $end_Dt)
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

    // function leadertbl_purchasing($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid, $ttylyesno, $in_dt_range, $activity_tracker_flg = "no")
    function leadertbl_purchasing(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        string $tilltoday,
        string $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        string $po_flg,
        string $unqid,
        string $ttylyesno,
        string $in_dt_range,
        string $activity_tracker_flg = "no"
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

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999111";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] PO ENTERED THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999222";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] PO ENTERED LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999333";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999444";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750' id='table9' class='tablesorter' >";
        echo "<thead>";
        if ($headtxt == "PALLET Leaderboard") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Employee</u></th>";
            echo "		<th align='center' bgColor='$tbl_head_color' width='100px'><u>Revenue</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>G.Profit Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            echo "		<th width='100px' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' bgColor='$tbl_head_color' align=center><u>% of Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Profit Margin</u></th>";
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
            echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Revenue</u></th>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>G.Profit Quota</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>G.Profit Quota</u></th>";
            }
            if ($headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit to Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            }
            if ($headtxt == "LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit To Date</u></th>";
            }
            echo "<th width='90px' bgColor='$tbl_head_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align=center><u>% of Quota</u></th>";
            echo "<th width='90px' bgColor='$tbl_head_color' align=center><u>Profit Margin</u></th>";

            if ($ttylyesno == "ttylyes") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit TTLY</u></th>";
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


        if ($headtxt == "PALLET Leaderboard") {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees where (purchasing_leaderboard_pallet = 1) and status = 'Active'";
        } else {
            if ($activity_tracker_flg == "yes") {
                $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees WHERE  activity_tracker_flg_purchasing_pallet = 1 and status = 'Active'";
            } else {
                $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees WHERE  (purchasing_leaderboard_pallet = 1) and status = 'Active'";
            }
        }
        //echo $sql . "<br>";
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

        $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));
        if ($in_dt_range == "yes" || $headtxt == "PALLET Leaderboard") {
            $sql = "SELECT * FROM loop_employees where (purchasing_leaderboard_pallet = 1) and status = 'Active' ORDER BY quota DESC";
        } else {
            if ($activity_tracker_flg == "yes") {
                $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE activity_tracker_flg_purchasing_pallet = 1 and status = 'Active' union 
			SELECT 0 as id, 'Other' as name, 'OT' as initials, '' as email, 0 as b2b_id, 0 leaderboard FROM loop_employees WHERE id = 1";
            } else {
                $sql = "SELECT * FROM loop_employees WHERE purchasing_leaderboard_pallet = 1 ORDER BY quota DESC";
            }
        }
        //echo $sql . "<br>";
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {
            $quota = 0;
            $quotadate = "";
            $deal_quota = 0;
            $monthly_qtd = 0;
            db();
            $result_empq = db_query("Select quota_year, quota_month from employee_quota_pallet_source_gprofit where emp_id = " . $rowemp["id"] . " order by quota_year, quota_month limit 1");
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

            if ($headtxt == "PALLET Leaderboard") {
                $begin = new DateTime($start_Dt);
                $end   = new DateTime($end_Dt);
                $quota = 0;
                for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
                    $start_Dt_tmp = $datecnt->format("Y-m-d");
                    $quota_mtd = 0;
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_pallet_source_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select sum(quota) as sumquota, sum(deal_quota) as deal_quota from employee_quota_pallet_source_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value);
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
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_pallet_source_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_pallet_source_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                    }
                }
                if ($current_qtr == 2) {

                    db();
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_pallet_source_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                    }
                }
                if ($current_qtr == 3) {
                    db();
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_pallet_source_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                    }
                }
                if ($current_qtr == 4) {
                    db();
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_pallet_source_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
                    $days_today = "";
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
                $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_pallet_source_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
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
                if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                    $qry = db_query("Select sum(quote_total) as quoteamount from quote where quoteType = 'PO' and quoteDate between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and rep not in (" . $emp_b2bid_list . ")");
                    //echo "Select sum(quote_total) as quoteamount from quote where quoteType = 'PO' and quoteDate between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and rep not in (" . $emp_b2bid_list . ") <br>";
                } else {
                    $qry = db_query("Select sum(quote_total) as quoteamount from quote where quoteType = 'PO' and quoteDate between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and rep = '" . $rowemp["b2b_id"] . "'");
                    //echo "Select sum(quote_total) as quoteamount from quote where quoteType = 'PO' and quoteDate between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and rep = '" . $rowemp["b2b_id"] . "' <br>";
                }
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
            //$qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id FROM loop_invoice_items WHERE box_item_founder_emp_id=". $rowemp["id"]);
            $qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id FROM loop_invoice_items inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_invoice_items.trans_rec_id 
		WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and box_item_founder_emp_id=" . $rowemp["id"]);

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
            //echo $headtxt . "<br>" . $rowemp["id"] . " | " . $qry . " ". $str_box_list_ids . "<br>";
            $tot_profit = 0;
            if ($str_box_list_ids != "") {
                $row_no = 0;
                $tmp_trans_id = "";
                $vendor_b2b_rescue = 0;
                $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_invoice_details.total as loop_inv_amount FROM loop_bol_tracking inner join 
			loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id inner join loop_transaction_buyer on 
			loop_transaction_buyer.id = loop_bol_tracking.trans_rec_id where loop_transaction_buyer.ignore = 0 and loop_invoice_details.trans_rec_id in (" . $str_box_list_transids . ")
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

                        $b2bid = 0;
                        $company_name = "";
                        $wid = 0;
                        $inv_number = "";
                        $double_checked = 0;
                        $virtual_inventory_trans_id = 0;
                        $virtual_inventory_company_id = 0;
                        $q1 = "SELECT loop_warehouse.b2bid, inv_number , inv_amount, loop_warehouse.id as wid, virtual_inventory_company_id, virtual_inventory_trans_id, double_checked, company_name 
					FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id 
					where loop_transaction_buyer.id = " . $row_rs_tmprs["trans_rec_id"];
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
                        if ($finalpaid_amt == 0 && $inv_amount > 0) {
                            $invoice_amt = str_replace(",", "", $inv_amount);
                        }
                        if ($finalpaid_amt == 0 && isset($inv_amount) == 0 && $row_rs_tmprs["loop_inv_amount"] > 0) {
                            $invoice_amt = str_replace(",", "", $row_rs_tmprs["loop_inv_amount"]);
                        }

                        //echo $row_rs_tmprs["trans_rec_id"] . " finalpaid_amt - " . $finalpaid_amt . " inv_amount - " . $inv_amount . " row_rs_tmprsinv_amount - " . $row_rs_tmprs["loop_inv_amount"] . "<br>";

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
                        //echo "profit_val | " . $headtxt .  " | " . $virtual_inventory_trans_id . " | " . $rowemp["initials"] . " | " . number_format($profit_val, 0) .  "|" . $invoice_amt .  "|" . $vendor_pay . "<br>";
                        $tot_profit = $tot_profit + $profit_val;

                        // $profit_val_p = (($gross_profit_val * 100) / $invoice_amt);
                        if (is_numeric($invoice_amt) && $invoice_amt != 0) {
                            $profit_val_p = (($gross_profit_val * 100) / $invoice_amt);
                        }
                        $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                        $profit_val = "$" . number_format($profit_val, 0);

                        $summtd_dealcnt = $summtd_dealcnt + 1;
                        if ($po_flg == "yes") {
                            //	$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=". $wid ."&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_view'>". $row_rs_tmprs["trans_rec_id"] . "</a></td><td bgColor='#E4EAEB'>" . $inv_number . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"],0) . "</td></tr>";
                        } else {
                            $lisoftrans .= "<tr>
						<td bgColor='#E4EAEB'>" . $rowemp["initials"] . "</td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
						<td bgColor='#E4EAEB'>" . $quantity . "</td>
<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
<td bgColor='#E4EAEB' align='right'>$" . number_format((float)str_replace(",", "", $total), 0) . "</td>

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

            //For TTLY
            $summ_ttly = 0;
            $tot_profit = 0;
            $profit_val_org_ttly = 0;
            if ($ttylyesno == "ttylyes") {
                $start_Date = strtotime('-1 year', strtotime($start_Dt));
                //if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
                //	$end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));		
                //}else{
                $end_Date = strtotime('-1 year', strtotime($end_Dt));
                //}

                $start_Dt_ttly = Date('Y-m-d', $start_Date);
                $end_Dt_ttly = Date('Y-m-d', $end_Date);

                if ($str_box_list_ids != "") {

                    $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'>";
                    $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Supplier</td><td class='txtstyle_color'>Customer</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Quantity</td><td class='txtstyle_color'>Price</td><td class='txtstyle_color'>G.Profit</td><td class='txtstyle_color'>Profit</td><td class='txtstyle_color'>Margin</td></tr>";

                    $row_no = 0;
                    $tmp_trans_id = "";
                    $vendor_b2b_rescue = 0;
                    $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_invoice_details.total as loop_inv_amount FROM loop_bol_tracking inner join loop_invoice_details 
				on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt_ttly . "' and '" . $end_Dt_ttly . " 23:59:59' 
				and loop_invoice_details.trans_rec_id in (" . $str_box_list_transids . ") and box_id in (" . $str_box_list_ids . ")");
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

                        if ($quantity > 0) {

                            $qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ");
                            while ($row_rs_data_main = array_shift($qry_box_main)) {
                                $invoice_amt += $row_rs_data_main["quantity"] * $row_rs_data_main["price"];
                            }

                            $gr_total = str_replace(",", "", $total);

                            $summ_ttly = $summ_ttly + str_replace(",", "", number_format($gr_total, 0));

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

                            if ($po_flg != "yes") {

                                db();
                                $dt_view_qry = "SELECT *, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $row_rs_tmprs["trans_rec_id"];
                                $dt_view_res = db_query($dt_view_qry);
                                while ($dt_view_row = array_shift($dt_view_res)) {
                                    $vendor_pay += $dt_view_row["estimated_cost"];
                                }
                            }

                            $gross_profit_val = $invoice_amt - $vendor_pay;
                            $profit_val = ($quantity_per * $gross_profit_val) / 100;
                            $tot_profit = $tot_profit + str_replace(",", "", number_format($profit_val, 0));

                            //$profit_val_per = number_format((($profit_val * 100)/$invoice_amt),2) . "%";

                            $profit_val_p = (($gross_profit_val * 100) / $invoice_amt);
                            $profit_val_org_ttly = $profit_val_org_ttly + str_replace(",", "", number_format(($quantity_per * $gross_profit_val) / 100, 0));

                            //$profit_val_per = number_format(($profit_val_p * 100)/$quantity_per,2) . "%";
                            $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                            $profit_val = "$" . number_format($profit_val, 0);

                            if ($po_flg == "yes") {
                                //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=". $wid ."&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_view'>". $row_rs_tmprs["trans_rec_id"] . "</a></td><td bgColor='#E4EAEB'>" . $inv_number . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"],0) . "</td></tr>";
                            } else {
                                $lisoftrans_ttly .= "<tr>
							<td bgColor='#E4EAEB'>" . $rowemp["initials"] . "</td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
							<td bgColor='#E4EAEB'>" . $quantity . "</td>
							<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
							<td bgColor='#E4EAEB' align='right'>$" . number_format(str_replace(",", "", $total), 0) . "</td>
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
                    $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_invoice_details.total as loop_inv_amount FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = 
				loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'  and loop_invoice_details.trans_rec_id in (" . $str_box_list_transids . ") and box_id in (" . $str_box_list_ids . ")");
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
                            $tot_profit = $tot_profit + $profit_val;

                            //$profit_val_per = number_format((($profit_val * 100)/$invoice_amt),2) . "%";

                            $profit_val_p = (($gross_profit_val * 100) / $invoice_amt);
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
							<td bgColor='#E4EAEB' align='right'>$" . number_format(str_replace(",", "", $total), 0) . "</td>
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
            if ($profit_val_org >= $monthly_qtd) {
                $color = "green";
            } elseif (($profit_val_org < $monthly_qtd && $profit_val_org > 0) || $profit_val_org < 0) {
                $color = "red";
            } else {
                $color = "black";
            };
            //commented as per team chat 25Jul2022 if ($monthly_qtd == 0) { $color = "black";}

            $show_data = "yes";
            if ($headtxt == "PALLET Leaderboard") {
                if ($profit_val_org == 0) {
                    $show_data = "no";
                }
            }

            if ($show_data == "yes") {

                if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                    $quote_amount_tweek = -99999;
                } else {
                    $quote_amount_tweek = $quote_amount;
                }

                $MGArray[] = array(
                    'name' => $rowemp["name"], 'quote_amount' => $quote_amount, 'emp_email' => $rowemp["email"], 'b2bempid' => $rowemp["b2b_id"], 'name_initial' => $rowemp["initials"],
                    'emp_initials_list' => $emp_initials_list, 'emp_b2bid_list' => $emp_b2bid_list,  'emp_id_list' => $emp_id_list, 'emp_eml_list' => $emp_eml_list,
                    'empid' => $rowemp["id"], 'start_Dt' => $start_Dt, 'end_Dt' => $end_Dt, 'leaderboard' => $rowemp["leaderboard"],
                    'deal_count' => $summtd_dealcnt, 'quota' => isset($quota_in_st_en), 'profit_val_org' => $profit_val_org, 'quote_amount_tweek' => $quote_amount_tweek,
                    'quotatodate' => $monthly_qtd, 'po_entered' => $summtd_SUMPO, 'ttly' => $profit_val_org_ttly, 'percent_val' => $color,
                    'rev_lastyr_tilldt' => $profit_val_org_rev_lastyr, 'lisoftrans' => $lisoftrans, 'lisoftrans_ttly' => isset($lisoftrans_ttly), 'lisoftrans_lastyr' => isset($lisoftrans_lastyr)
                );
            }
        }

        //var_dump($MGArray);

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
                $MGArraysort_I[] = $MGArraytmp['quote_amount_tweek'];
            }
            array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
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
            $sales_revenue = $MGArraytmp2["po_entered"];
            $summtd_ttly = $MGArraytmp2["ttly"];
            //if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0){
            //	$quote_amount = $MGArraytmp2['quote_amount_tweek'];
            //}else{	
            $quote_amount = $MGArraytmp2['quote_amount'];
            //}
            //$monthly_percentage = $MGArraytmp2["percent_val"];

            $str_box_list_ids = "";
            $str_box_list_transids = "";
            //$qry = db_query("Select id from loop_boxes where owner = " . $MGArraytmp2["empid"]);
            //$qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id FROM loop_invoice_items WHERE box_item_founder_emp_id=". $MGArraytmp2["empid"]);
            $qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id FROM loop_invoice_items inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_invoice_items.trans_rec_id 
		WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and box_item_founder_emp_id=" . $MGArraytmp2["empid"]);

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
                    $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_invoice_details.total as loop_inv_amount FROM loop_bol_tracking 
				inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id 
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

                        $lisoftrans_detail_list .= "<tr><td bgColor='$tbl_color'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_view'>" . $row_rs_tmprs["trans_rec_id"] . "</a></td><td bgColor='$tbl_color'>" . $nickname . "</td></td><td bgColor='$tbl_color'>" . $po_date . "</td><td bgColor='$tbl_color'>" . $name . "</td><td bgColor='$tbl_color' align='right'>$" . number_format($gr_total, 0) . "</td></tr>";

                        $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
                    }
                }
                //for the Emp wise order list - View detail list
            }

            if ($headtxt == "PALLET Leaderboard") {
                echo "<tr><td bgColor='$tbl_color' width='260px' align ='left'>" . $name . "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($sales_revenue, 0);
                echo "</td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($monthly_qtd_TD, 0);
                echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color_y . "'>";
                echo "<a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
                echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
                echo "</td>";
                echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color_y . "'>";
                if ($monthly_qtd_TD > 0) {
                    echo number_format(($summtd_SUMPO / $monthly_qtd_TD) * 100, 2) . "%";
                }
                echo "</font></td>";

                $per_val = number_format(str_replace(",", "", number_format($summtd_SUMPO, 0)) * 100 / str_replace(",", "", number_format($sales_revenue, 0)), 2);
                if ($per_val >= 20) {
                    $per_val_color = "green";
                } else {
                    $per_val_color = "red";
                }

                if ($summtd_SUMPO > 0 && $sales_revenue > 0) {
                    echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $per_val_color . "'>" . $per_val . "%</font></td>";
                } else {
                    echo "<td bgColor='$tbl_color' align = 'right'></td>";
                }
                echo "</tr>";
            } else if ($po_flg == "yes") {
                $in_other_flg = "";
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    $in_other_flg = "&other_flg=yes";
                }

                db();
                $first_time_rec = 0;
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    $result_crm = db_query("SELECT 1 as cnt FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
					inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
					WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
					and loop_boxes.owner not in (" . $emp_id_list . ") AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between '" . $MGArraytmp2["start_Dt"] . "' AND '" . $MGArraytmp2["end_Dt"] . " 23:59:59' group by loop_boxes_sort.trans_rec_id");
                } else {
                    $result_crm = db_query("SELECT 1 as cnt FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
					inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
					WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
					and loop_boxes.owner = '" . $MGArraytmp2["empid"] . "' AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between '" . $MGArraytmp2["start_Dt"] . "' AND '" . $MGArraytmp2["end_Dt"] . " 23:59:59' group by loop_boxes_sort.trans_rec_id");
                }

                while ($rowemp_crm = array_shift($result_crm)) {
                    $first_time_rec = $first_time_rec + $rowemp_crm["cnt"];
                }
                $tot_first_time_rec = isset($tot_first_time_rec) + $first_time_rec;

                db_b2b();
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    //$sql7 = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE not in (" . $MGArraytmp2["emp_initials_list"] . ") AND  timestamp BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                } else {
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
                }

                $lead_tmp = 0;
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    //$result_crm = db_query("Select count(ID) as cnt from companyInfo where dateCreated BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and landing_pg_enter_by not in (" . $MGArraytmp2["emp_b2bid_list"] . ")");
                } else {
                    $result_crm = db_query("Select count(ID) as cnt from companyInfo where dateCreated BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and landing_pg_enter_by = '" . $MGArraytmp2["b2bempid"] . "'");
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $lead_tmp = $lead_tmp + $rowemp_crm["cnt"];
                    }
                    $tot_lead_tmp = isset($tot_lead_tmp) + $lead_tmp;
                }

                db_email();
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    //$result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and fromadd not in (" . $emp_eml_list . ")");
                } else {
                    $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and fromadd = '" . $MGArraytmp2["emp_email"] . "'");
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $contact_act_tmp = isset($contact_act_tmp) + $rowemp_crm["cnt"];
                    }
                    $tot_quota_contact = isset($tot_quota_contact) + isset($contact_act_tmp);
                    $tot_quota_contact_ph = isset($tot_quota_contact_ph) + isset($contact_act_ph1);
                }

                db_b2b();
                $no_of_pos_cnt = 0;
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    $sql7 = "SELECT count(companyID) as cnt FROM quote WHERE quoteType = 'PO' and rep not in (" . $MGArraytmp2["emp_b2bid_list"] . ") AND qstatus !=2 AND quoteDate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                } else {
                    $sql7 = "SELECT count(companyID) as cnt FROM quote WHERE quoteType = 'PO' and rep LIKE '" . $MGArraytmp2["b2bempid"] . "' AND qstatus !=2 AND quoteDate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                }
                db_b2b();
                $result_crm = db_query($sql7);
                while ($rowemp_crm = array_shift($result_crm)) {
                    $no_of_pos_cnt = $rowemp_crm["cnt"];
                }
                $tot_quota_quotes = isset($tot_quota_quotes) + $no_of_pos_cnt;

                $tot_quote_amount = isset($tot_quote_amount) + $quote_amount;
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
                    if (isset($contact_act_tmp) >= 20) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 20) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if (isset($contact_act_ph1) >= 20) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 20) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 2) {
                    if (isset($contact_act_tmp) >= 40) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 40) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if (isset($contact_act_ph1) >= 40) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 40) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 3) {
                    if (isset($contact_act_tmp) >= 60) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 60) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if (isset($contact_act_ph1) >= 60) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 60) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 4) {
                    if (isset($contact_act_tmp) >= 80) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 80) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if (isset($contact_act_ph1) >= 80) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 80) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) >= 5) {
                    if (isset($contact_act_tmp) >= 100) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 100) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if (isset($contact_act_ph1) >= 100) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 100) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }

                echo "<tr><td bgColor='$tbl_color' width='260px' align ='left'>" . $name . "</td>";
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    echo "<td bgColor='$tbl_color' align = 'right'>&nbsp;";
                    echo "</td>";
                    echo "<td bgColor='$tbl_color' align = 'right'>&nbsp;";
                    echo "</td>";
                    echo "<td bgColor='$tbl_color' align = 'right'>&nbsp;";
                    echo "</td>";
                } else {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a target='_blank' href='report_purchasing_show_list.php?showlead=yes" . $in_other_flg . "&purchasing=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "'>";
                    echo number_format($lead_tmp, 0);
                    echo "</a></td>";
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a target='_blank' href='report_daily_chart_crm_list.php?purchasing=yes&CRMday=poenter" . $in_other_flg . "&purchasing=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "'>";
                    echo $email_color_code . " " . number_format($contact_act_tmp, 0) . " " . $email_color_code2;
                    echo "</a></td>";
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter" . $in_other_flg . "&purchasing=yes&phone=y&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "'>";
                    echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
                    echo "</a></td>";
                }
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y" . $in_other_flg . "&purchasing=yes&b2bempid=" . $MGArraytmp2["b2bempid"] . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
                echo number_format($no_of_pos_cnt, 0);
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_purchasing_show_list.php?showfirsttimerec=yes" . $in_other_flg . "&purchasing=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "'>";
                echo number_format($first_time_rec, 0);
                echo "</a></td>";
                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a target='_blank' href='report_purchasing_show_list.php?quote_amount=yes" . $in_other_flg . "&purchasing=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $MGArraytmp2["name_initial"] . "&b2bempid=" . $MGArraytmp2["b2bempid"] . "'>$";
                echo number_format($quote_amount, 0);
                echo "</a></td>";
                echo "</tr>";
            } else {
                echo "<tr><td bgColor='$tbl_color' width='260px' align ='left'>" . $name . "</td>";
                echo "<td bgColor='$tbl_color' align ='right'>$" . number_format($sales_revenue, 0) . "</td>";

                if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format($monthly_qtd, 0);
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format($monthly_qtd_TD, 0);
                } else {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
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
                    } elseif ($summtd_SUMPO < $monthly_qtd && $summtd_SUMPO > 0) {
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
                    } elseif (($summtd_SUMPO == 0 && $monthly_qtd > 0) || $summtd_SUMPO < 0) {
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
                    } elseif (($summtd_SUMPO == 0 && $monthly_qtd_TD > 0) || $summtd_SUMPO < 0) {
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
                echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color_y_new . "'>";
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if ($monthly_qtd > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd, 2) . "%";
                    }
                } else {
                    if ($monthly_qtd_TD > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd_TD, 2) . "%";
                    }
                }
                echo "</font></td>";

                $per_val = number_format(str_replace(",", "", number_format($summtd_SUMPO, 0)) * 100 / str_replace(",", "", number_format($sales_revenue, 0)), 2);
                if ($per_val >= 20) {
                    $per_val_color = "green";
                } else {
                    $per_val_color = "red";
                }

                if ($summtd_SUMPO > 0 && $sales_revenue > 0) {
                    echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $per_val_color . "'>" . $per_val . "%</font></td>";
                } else {
                    echo "<td bgColor='$tbl_color' align = 'right'></td>";
                }

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

        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "PALLET Leaderboard") {
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

        if ($headtxt == "PALLET Leaderboard") {
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
                $days_today = "";
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
                            $monthly_qtd_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

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
        $qry = db_query("SELECT id FROM loop_employees WHERE purchasing_leaderboard_pallet = 1 ORDER BY quota DESC");
        while ($row_rs_tmprs = array_shift($qry)) {
            $emp_id .= $row_rs_tmprs["id"] . ",";
        }
        if ($emp_id != "") {
            $emp_id = substr($emp_id, 0, strlen($emp_id) - 1);
        }

        $str_box_list_ids = "";
        //$qry = db_query("Select id from loop_boxes where owner in(" . $emp_id . ")");
        //$qry = db_query("SELECT distinct(loop_box_id) AS id FROM loop_invoice_items WHERE box_item_founder_emp_id in (" . $emp_id . ")");
        $qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id FROM loop_invoice_items inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_invoice_items.trans_rec_id 
		WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and box_item_founder_emp_id in (" . $emp_id . ")");

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
            $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_invoice_details.total as loop_inv_amount FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");
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

                    //echo $row_rs_tmprs["trans_rec_id"] . " finalpaid_amt - " . $finalpaid_amt . " inv_amount - " . $inv_amount . " row_rs_tmprsinv_amount - " . $row_rs_tmprs["loop_inv_amount"] . "<br>";

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

                        $profit_val_p = (($gross_profit_val * 100) / $invoice_amt);

                        //$profit_val_per = number_format(($profit_val_p * 100)/$quantity_per,2) . "%";
                        $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                        $profit_val = "$" . number_format($profit_val, 0);

                        //echo $row_rs_tmprs["trans_rec_id"] . " - invoice_amt - " . $invoice_amt . " - invoice_amt_ind - $invoice_amt_ind - vendor_pay - " . $vendor_pay . " profit_val - " . $profit_val . " <br>";
                        //echo "quantity_per - " . $quantity_per . " profit_val_p - " . $profit_val_p . "<br>";
                    }

                    $summtd_dealcnt = $summtd_dealcnt + 1;
                    if ($po_flg == "yes") {
                        //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=". $wid ."&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_view'>". $row_rs_tmprs["trans_rec_id"] . "</a></td><td bgColor='#E4EAEB'>" . $inv_number . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"],0) . "</td></tr>";
                    } else {
                        $lisoftrans .= "<tr>
						<td bgColor='#E4EAEB'>" . $emp_name . "</td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
						<td bgColor='#E4EAEB'>" . $quantity . "</td>
						<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
						<td bgColor='#E4EAEB' align='right'>$" . number_format(str_replace(",", "", $total), 0) . "</td>
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
            //if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
            //	$end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));		
            //}else{
            $end_Date = strtotime('-1 year', strtotime($end_Dt));
            //}

            $start_Dt_ttly = Date('Y-m-d', $start_Date);
            $end_Dt_ttly = Date('Y-m-d', $end_Date);

            $lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'>";
            $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Supplier</td><td class='txtstyle_color'>Customer</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Quantity</td><td class='txtstyle_color'>Price</td><td class='txtstyle_color'>G.Profit</td><td class='txtstyle_color'>Profit</td><td class='txtstyle_color'>Margin</td></tr>";

            if ($str_box_list_ids != "") {
                $row_no = 0;
                $tmp_trans_id = "";
                $vendor_b2b_rescue = 0;
                $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_invoice_details.total as loop_inv_amount FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt_ttly . "' and '" . $end_Dt_ttly . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");
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
                        //$lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=". $wid ."&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_view'>". $row_rs_tmprs["trans_rec_id"] . "</a></td><td bgColor='#E4EAEB'>" . $inv_number . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"],0) . "</td></tr>";
                    } else {
                        $lisoftrans_ttly .= "<tr>
						<td bgColor='#E4EAEB'>" . $rowemp["initials"] . "</td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $supp_nm . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "-" . $nickname . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
						<td bgColor='#E4EAEB'>" . $quantity . "</td>
						<td bgColor='#E4EAEB'>" . number_format($price, 2) . "</td>
						<td bgColor='#E4EAEB' align='right'>$" . number_format(str_replace(",", "", $total), 0) . "</td>
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
                $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_invoice_details.total as loop_inv_amount FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id where loop_invoice_details.timestamp between '" . $start_Dt_lasty . "' and '" . $end_Dt_lasty . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");
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
                    //$qry_box_main = db_query("Select * from loop_invoice_items where trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " and description = '" . str_replace("'", "\'" , $boxdesc) . "' ");
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
        $global_monthly_qtd = $monthly_qtd;
        $global_tot_quotaactual_mtd = $tot_quotaactual_mtd;
        $global_unqid = $unqid;
        $global_empid = $MGArraytmp2["empid"];
        $global_lisoftrans = $lisoftrans;
        $global_rev_lastyr_tilldt = $rev_lastyr_tilldt;
        $global_summ_ttly = isset($summ_ttly);
        $global_lisoftrans_ttly = isset($lisoftrans_ttly);
        //echo "</table>";
    }

    function leadertbl(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        string $tilltoday,
        string $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        string $po_flg,
        string $unqid,
        string $ttylyesno,
        $in_dt_range,
        string $activity_tracker_flg = "no"
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

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999111";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999222";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS MONTH" && $po_flg == "yes") {
            $div_id_emp_list = "999441";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker THIS MONTH [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST MONTH" && $po_flg == "yes") {
            $div_id_emp_list = "999442";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker LAST MONTH [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS QUARTER" && $po_flg == "yes") {
            $div_id_emp_list = "999443";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker THIS QUARTER [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST QUARTER" && $po_flg == "yes") {
            $div_id_emp_list = "999445";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker LAST QUARTER [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS YEAR" && $po_flg == "yes") {
            $div_id_emp_list = "999446";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker THIS YEAR [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST YEAR" && $po_flg == "yes") {
            $div_id_emp_list = "999447";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker LAST YEAR [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999333";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999444";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750' id='table9' class='tablesorter'>";
        echo "<thead>";
        if ($headtxt == "PALLET Leaderboard") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Employee</u></th>";
            echo "		<th align='center' bgColor='$tbl_head_color' width='100px'><u>Revenue</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>G.Profit Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            echo "		<th width='100px' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' bgColor='$tbl_head_color' align=center><u>% of Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Profit Margin</u></th>";
            echo "	</tr>";
        } elseif ($po_flg == "yes") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='250px'><u>Employee</u></th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Leads</u></th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Emails</u>
			<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
			<span class='tooltiptext'>Green is >= 15 Avg Emails/Day Else Red</span></div>
		</th>";
            echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Calls</u>
			<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
			<span class='tooltiptext'>Green is >= 15 Avg Calls/Day Else Red</span></div>
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
            echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Revenue</u></th>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>G.Profit Quota</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>G.Profit Quota</u></th>";
            }
            if ($headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit to Date</u></th>";
            } else {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit</u></th>";
            }

            if ($headtxt == "LAST YEAR") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit To Date</u></th>";
            }
            echo "		<th width='90px' bgColor='$tbl_head_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align=center><u>% of Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Profit Margin</u></th>";
            if ($ttylyesno == "ttylyes") {
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit TTLY</u></th>";
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
        if ($headtxt == "PALLET Leaderboard") {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees where (pallet_leaderboard = 1) and status = 'Active'";
        } else {
            if ($activity_tracker_flg == "yes") {
                $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees WHERE  activity_tracker_flg_pallet = 1 and status = 'Active'";
            } else {
                $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees WHERE  (pallet_leaderboard = 1) and status = 'Active'";
            }
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
        if ($activity_tracker_flg == "yes") {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE activity_tracker_flg_pallet = 1 and status = 'Active' union 
		SELECT 0 as id, 'Other' as name, 'OT' as initials, '' as email, 0 as b2b_id, 0 leaderboard FROM loop_employees WHERE id = 1";
        } elseif ($po_flg == "yes") {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE pallet_leaderboard = 1 and status = 'Active' union 
		SELECT 0 as id, 'Other' as name, 'OT' as initials, '' as email, 0 as b2b_id, 0 leaderboard FROM loop_employees WHERE id = 1";
        } else if ($headtxt == "PALLET Leaderboard") {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees where (pallet_leaderboard = 1) and status = 'Active' ";
        } else {
            $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE pallet_leaderboard = 1 and status = 'Active'";
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
            $result_empq = db_query("Select quota_year, quota_month from employee_quota_pallet_sale_gprofit where emp_id = " . $rowemp["id"] . " order by quota_year, quota_month limit 1");
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

            if ($headtxt == "PALLET Leaderboard") {
                $begin = new DateTime($start_Dt);
                $end   = new DateTime($end_Dt);
                $quota = 0;
                for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
                    $start_Dt_tmp = $datecnt->format("Y-m-d");
                    $quota_mtd = 0;
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_pallet_sale_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select sum(quota) as sumquota, sum(deal_quota) as deal_quota from employee_quota_pallet_sale_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value);
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
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_pallet_sale_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_pallet_sale_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                    }
                }
                if ($current_qtr == 2) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_pallet_sale_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                    }
                }
                if ($current_qtr == 3) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_pallet_sale_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                    }
                }
                if ($current_qtr == 4) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_pallet_sale_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
                    $days_today = "";
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
                $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_pallet_sale_gprofit where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
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
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee LIKE '" . $rowemp["initials"] . "'  and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                }
            } else {
                if ($tilltoday == "Y") {
                    if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt,  loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                    }
                } else {
                    if ($rowemp["leaderboard"] == 0 && $headtxt != "PALLET Leaderboard" && $rowemp["id"] == 0) {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
                    }
                }
            }

            //if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
            //	if ($rowemp["leaderboard"] == 0){
            //		$sqlmtd = "SELECT loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
            //	}else{
            //		$sqlmtd = "SELECT loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee LIKE '" . $rowemp["initials"] . "'  and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
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
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
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
				<td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td><td bgColor='#E4EAEB' align='right'>" . number_format(($inv_amt_totake - $estimated_cost) * 100 / str_replace(",", "", number_format($inv_amt_totake, 0)), 2) . "%</td></tr>";
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
				<td bgColor='#ABC5DF' align='right'>" . number_format($summtd_SUMPO * 100 / str_replace(",", "", number_format($summtd_SUMPO_sale_rev, 0)), 2) . "%</td></tr>";
                }
            }
            $lisoftrans .= "</table></span>";

            //For TTLY
            $summ_ttly = 0;
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

                if ($tilltoday == "Y" && $po_flg != "yes") {
                    if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    }
                } else if ($po_flg == "yes") {
                    if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_transaction_buyer.po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND transaction_date BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_transaction_buyer.po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND transaction_date BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    }
                } else {
                    if ($rowemp["leaderboard"] == 0 && $rowemp["id"] == 0) {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    } else {
                        $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                    }
                }
                //echo $headtxt . " | " . $sqlmtd . "<br>";
                $resultmtd = db_query($sqlmtd);
                while ($summtd = array_shift($resultmtd)) {
                    $finalpaid_amt = 0;
                    /*$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where trans_rec_id = " . $summtd["id"], db()  );
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
                    $summ_ttly_sales_rev = $summ_ttly_sales_rev + $inv_amt_totake;

                    $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
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
                        $summ_ttly = $summ_ttly + ($inv_amt_totake - $estimated_cost);

                        $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
					<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
					<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
					<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
					<td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td><td bgColor='#E4EAEB' align='right'>" . number_format(($inv_amt_totake - $estimated_cost) * 100 / str_replace(",", "", number_format($inv_amt_totake, 0)), 2) . "%</td></tr>";

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
					<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_sales_rev, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td>
					<td bgColor='#ABC5DF' align='right'>" . number_format($summ_ttly * 100 / str_replace(",", "", number_format($summ_ttly_sales_rev, 0)), 2) . "%</td></tr>";

                        //$lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_sales_rev,0) . "</td>
                        //<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly,0) . "</td>
                        //<td bgColor='#ABC5DF' align='right'>" . number_format(str_replace(",", "" ,number_format($summ_ttly,0))*100/str_replace(",", "" ,number_format($summ_ttly_sales_rev,0)),2) . "%</td>
                        //</tr>";
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

                if ($rowemp["leaderboard"] == 0 && $rowemp["id"]) {
                    $sqlmtd = "SELECT inv_number, loop_warehouse.warehouse_name, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee not in (" . $emp_initials_list . ") and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT inv_number, loop_warehouse.warehouse_name, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
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
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                    db();
                    $resB2bCogs = db_query($qryB2bCogs);

                    $estimated_cost = 0;
                    while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                        $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
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
            } elseif (($summtd_SUMPO < $monthly_qtd && $summtd_SUMPO > 0) || $summtd_SUMPO < 0) {
                $color = "red";
            } else {
                $color = "black";
            };
            //commented as per team chat 25Jul2022  if ($monthly_qtd == 0) { $color = "black";}

            $add_entry = "yes";
            if ($headtxt == "PALLET Leaderboard") {
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
                    'quotatodate' => $monthly_qtd, 'po_entered' => $summtd_SUMPO, 'summtd_SUMPO_sale_rev' => $summtd_SUMPO_sale_rev, 'po_entered_other_tweek' => $summtd_SUMPO_tweek, 'ttly' => $summ_ttly, 'percent_val' => $color, 'rev_lastyr_tilldt' => $rev_lastyr_tilldt, 'lisoftrans' => $lisoftrans,
                    'lisoftrans_ttly' => $lisoftrans_ttly, 'lisoftrans_lastyear' => $lisoftrans_lastyear, 'lisoftrans_for_total' => $lisoftrans_for_total
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
            $summtd_SUMPO_sale_rev = $MGArraytmp2["summtd_SUMPO_sale_rev"];
            if ($po_flg == "yes") {
                $summtd_SUMPO = $MGArraytmp2["summtd_SUMPO_activity"];
            } else {
                $summtd_SUMPO = $MGArraytmp2["po_entered"];
            }
            $summtd_ttly = $MGArraytmp2["ttly"];
            //$monthly_percentage = $MGArraytmp2["percent_val"];

            //if ($monthly_percentage >= 100 ) { $color_y = "green"; } elseif ($monthly_percentage >= 80 ) { $color_y = "E0B003"; } else { $color_y = "B03030"; };
            $color_y = $MGArraytmp2["percent_val"];


            if ($headtxt == "PALLET Leaderboard") {
                echo "<tr><td bgColor='$tbl_color' width='260px' align ='left'>" . $name . "</td>";
                echo "<td bgColor='$tbl_color' align ='right'>$" . number_format($summtd_SUMPO_sale_rev, 0) . "</td>";

                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($monthly_qtd_TD, 0);
                echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color_y . "'>";
                echo "<a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
                echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
                echo "</td>";
                echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align='right'><font color='" . $color_y . "'>";
                if ($monthly_qtd_TD > 0) {
                    echo number_format(($summtd_SUMPO / $monthly_qtd_TD) * 100, 2) . "%";
                }
                echo "</font></td>";

                $profit_mrg = "";
                if ($summtd_SUMPO_sale_rev > 0 && $summtd_SUMPO > 0) {
                    $profit_mrg = number_format(str_replace(",", "", number_format($summtd_SUMPO, 0)) * 100 / str_replace(",", "", number_format($summtd_SUMPO_sale_rev, 0)), 2);
                }
                if ($profit_mrg >= 20) {
                    $profit_mrg_color = "green";
                } else {
                    $profit_mrg_color = "red";
                };

                if ($profit_mrg <> "") {
                    echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $profit_mrg_color . "'>" . number_format($profit_mrg, 2) . "%</td>";
                } else {
                    echo "<td bgColor='$tbl_color' align = 'right'></td>";
                }

                echo "</tr>";
            } else if ($po_flg == "yes") {

                $first_time_rec = 0;
                db();
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    $result_crm = db_query("SELECT count(id) as cnt FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee not in (" . $MGArraytmp2["emp_initials_list"] . ") and loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $MGArraytmp2["start_Dt"] . "' AND '" . $MGArraytmp2["end_Dt"] . " 23:59:59'");
                } else {
                    $result_crm = db_query("SELECT count(id) as cnt FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee LIKE '" . $MGArraytmp2["name_initial"] . "' and loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $MGArraytmp2["start_Dt"] . "' AND '" . $MGArraytmp2["end_Dt"] . " 23:59:59'");
                }
                while ($rowemp_crm = array_shift($result_crm)) {
                    $first_time_rec = $first_time_rec + $rowemp_crm["cnt"];
                }
                $tot_first_time_rec = isset($tot_first_time_rec) + $first_time_rec;

                $quote_req_cnt = 0;
                db();
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    $result_crm = db_query("Select count(quote_request_tracker_id) as cnt from quote_request_tracker where date_submitted BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and quote_req_submitted_by not in (" . $MGArraytmp2["emp_initials_list"] . ")");
                } else {
                    $result_crm = db_query("Select count(quote_request_tracker_id) as cnt from quote_request_tracker where date_submitted BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and quote_req_submitted_by = '" . $MGArraytmp2["name_initial"] . "'");
                }
                while ($rowemp_crm = array_shift($result_crm)) {
                    $quote_req_cnt = $quote_req_cnt + $rowemp_crm["cnt"];
                }
                $tot_quote_req_cnt = isset($tot_quote_req_cnt) + $quote_req_cnt;

                //Demand entry
                $demand_entry_tmp = 0;
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    $result_crm = db_query("Select count(quote_id) as cnt from quote_request where quote_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and user_initials not in (" . $MGArraytmp2["emp_initials_list"] . ") ");
                    //echo "Select count(quote_id) as cnt from quote_request where quote_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and user_initials not in (" . $MGArraytmp2["emp_initials_list"] . ") ";
                } else {
                    $result_crm = db_query("Select count(quote_id) as cnt from quote_request where quote_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and user_initials = '" . $MGArraytmp2["name_initial"] . "'");
                    //echo "Select count(quote_id) as cnt from quote_request where quote_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and user_initials = '" . $MGArraytmp2["name_initial"] . "' <br>";
                }
                while ($rowemp_crm = array_shift($result_crm)) {
                    $demand_entry_tmp = $demand_entry_tmp + $rowemp_crm["cnt"];
                }
                $tot_demand_entry_tmp = isset($tot_demand_entry_tmp) + $demand_entry_tmp;

                $contact_act_ph1 = 0;
                $contact_act_tmp = 0;
                $eml_list = "";
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
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
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    //$result_crm = db_query("Select count(ID) as cnt from companyInfo where dateCreated BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and landing_pg_enter_by not in (" . $MGArraytmp2["emp_b2bid_list"] . ")");
                } else {
                    $result_crm = db_query("Select count(ID) as cnt from companyInfo where dateCreated BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and landing_pg_enter_by = '" . $MGArraytmp2["b2bempid"] . "'");
                    while ($rowemp_crm = array_shift($result_crm)) {
                        $lead_tmp = $lead_tmp + $rowemp_crm["cnt"];
                    }
                }
                $tot_lead_tmp = isset($tot_lead_tmp) + $lead_tmp;

                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
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
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
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
                //changed from 20 to 15
                if (isset($week_val) == 1) {
                    if ($contact_act_tmp >= 15) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 15) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 15) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 15) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 2) {
                    if ($contact_act_tmp >= 30) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 30) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 30) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 30) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 3) {
                    if ($contact_act_tmp >= 45) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 45) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 45) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 45) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }
                if (isset($week_val) == 4) {
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
                if (isset($week_val) >= 5) {
                    if ($contact_act_tmp >= 75) {
                        $email_color_code = "<font color=green>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_tmp < 75) {
                        $email_color_code = "<font color=red>";
                        $email_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 >= 75) {
                        $contact_color_code = "<font color=green>";
                        $contact_color_code2 = "</font>";
                    }
                    if ($contact_act_ph1 < 75) {
                        $contact_color_code = "<font color=red>";
                        $contact_color_code2 = "</font>";
                    }
                }

                db();
                /*if($summtd_SUMPO>0)
            {*/
                $in_other_flg = "";
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
                    $in_other_flg = "&other_flg=yes";
                }

                echo "<tr><td bgColor='$tbl_color' width='260px' align ='left'>" . $name . "</td>";
                if ($MGArraytmp2["leaderboard"] == 0 && $MGArraytmp2["empid"] == 0) {
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
                echo "<tr><td bgColor='$tbl_color' width='260px' align ='left'>" . $name . "</td>";

                echo "<td bgColor='$tbl_color' align = right>$" . number_format($summtd_SUMPO_sale_rev, 0) . "</td>";

                if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format($monthly_qtd, 0);
                    echo "</td><td bgColor='$tbl_color' align = 'right'>";
                    echo "$" . number_format($monthly_qtd_TD, 0);
                } else {
                    echo "<td bgColor='$tbl_color' align = 'right'>";
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
                    } elseif ($summtd_SUMPO < $monthly_qtd && $summtd_SUMPO > 0) {
                        $color = "red";
                    } else {
                        $color = "black";
                    };
                }
                $color_y_new = "black";
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if (($summtd_SUMPO * 100 / $monthly_qtd) >= 100) {
                        $color_y_new = "green";
                    } elseif ((($summtd_SUMPO * 100 / $monthly_qtd) < 100 && $monthly_qtd > 0) || $summtd_SUMPO < 0) {
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
                    } elseif ((($summtd_SUMPO * 100 / $monthly_qtd_TD) < 100 && $monthly_qtd_TD > 0) || $summtd_SUMPO < 0) {
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
                echo "<td style='border-right-style:solid; border-right-width: thin; border-right-color: black;' bgColor='$tbl_color' align = 'right'><font color='" . $color_y_new . "'> ";
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    if ($monthly_qtd > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd, 2) . "%";
                    }
                } else {
                    if ($monthly_qtd_TD > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd_TD, 2) . "%";
                    }
                }
                echo "</font></td>";

                $profit_mrg = "";
                if ($summtd_SUMPO_sale_rev > 0) {
                    $profit_mrg = ($summtd_SUMPO * 100 / $summtd_SUMPO_sale_rev);
                }
                if ($profit_mrg >= 20) {
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
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov = $rowemp_ovdata["quota"];
            }
        }

        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "PALLET Leaderboard") {
            $begin = new DateTime($start_Dt);
            $end   = new DateTime($end_Dt);
            $currentdate  = new DateTime(date("Y-m-d"));
            $quota = 0;
            $quota_to_date = 0;
            for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
                $start_Dt_tmp = $datecnt->format("Y-m-d");
                $quota_mtd = 0;
                $newsel = "Select quota_month, quota , deal_quota, quota_year  FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value;
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov = $quota_ov + $rowemp_ovdata["quota"];
            }
        }

        if ($po_flg == "yes") {
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

            echo "<td bgColor='$tbl_color' align = 'right'><strong>$" . number_format($tot_quotaactual_mtd, 0) . "<span id='" . $div_id_emp_list . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_detail_list . "</table></span></td>";
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

        if ($headtxt == "PALLET Leaderboard") {
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
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
                }
            }

            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = isset($quota) + $rowemp_empq["quota"];
                $days_today = "";
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
            $result_empq = db_query("Select quota_month, quota, deal_quota FROM employee_quota_overall_pallet_gprofit WHERE quota_year = " . $dt_year_value . " order by quota_month");
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
            //$lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
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
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        } else {
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        }

        if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1 and Leaderboard = 'PALLETS' AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
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

            $revenue_sales_b2b = $revenue_sales_b2b + $inv_amt_totake;

            $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
			FROM loop_transaction_buyer 
			LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
			INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
			WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
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
				<td bgColor='#E4EAEB' align='right'>" . number_format(str_replace(",", "", number_format(($inv_amt_totake - $estimated_cost), 0)) * 100 / str_replace(",", "", number_format($inv_amt_totake, 0)), 0) . "%</td>
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
			<td bgColor='#ABC5DF' align='right'>" . number_format(str_replace(",", "", number_format($summtd_SUMPO, 0)) * 100 / str_replace(",", "", number_format($revenue_sales_b2b, 0)), 0) . "%</td>
			
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
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
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
                $summ_ttly_sales_rev = $summ_ttly_sales_rev + $inv_amt_totake;

                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
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
                //$lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $summtd["warehouse_id"] ."&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>". $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td>
                //<td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake,0) . "</td>
                //<td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost),0) . "</td>
                //<td bgColor='#E4EAEB' align='right'>" . number_format(str_replace(",", "", number_format(($inv_amt_totake - $estimated_cost),0))*100/str_replace(",", "", number_format($inv_amt_totake,0)),0) . "%</td>
                //</tr>";

                $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
				<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
				<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td>
				<td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td><td bgColor='#E4EAEB' align='right'>" . number_format(($inv_amt_totake - $estimated_cost) * 100 / str_replace(",", "", number_format($inv_amt_totake, 0)), 2) . "%</td></tr>";

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
				<td bgColor='#ABC5DF' align='right'>" . number_format($summ_ttly * 100 / str_replace(",", "", number_format($summ_ttly_sales_rev, 0)), 2) . "%</td></tr>";
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
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0  and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
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
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
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

        if ($headtxt == "LAST TO LAST YEAR") {
            //$monthly_qtd = $quota_in_st_en;
        }

        if ($po_flg == "no") {
            leadertbl_purchasing($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid, $ttylyesno, $in_dt_range, $activity_tracker_flg);
        }

        if ($headtxt != "THIS WEEK" && $headtxt != "LAST WEEK") {
            //leadertbl_ucbzw($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid , $ttylyesno, $in_dt_range);
        }

        //for the B2b op team calc
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else if ($po_flg == "yes") {
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[UCB] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750' id='table9' class='tablesorter'>";

        //For PALLET Revenue calculation
        $quota_ov_b2b_rev = 0;
        if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov_b2b_rev = $rowemp_ovdata["quota"];
            }
        }

        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "PALLET Leaderboard") {
            $begin = new DateTime($start_Dt);
            $end   = new DateTime($end_Dt);
            $currentdate  = new DateTime(date("Y-m-d"));
            $quota = 0;
            $quota_to_date = 0;
            for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
                $start_Dt_tmp = $datecnt->format("Y-m-d");
                $quota_mtd = 0;
                $newsel = "Select quota_month, quota , deal_quota, quota_year  FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
            }
            $quota_mtd = 0;
            $donot_add = "";
            $days_in_month = 30;
            $quota = 0;
            $dt_month_value_1 = date('m');
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = $quota + $rowemp_empq["quota"];
            }

            $quota_ov_b2b_rev = $quota;
        }

        if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value;
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

        if ($headtxt == "PALLET Leaderboard") {
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
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_month in(1,2,3) order by quota_month");

                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
                }
            }

            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = isset($quota) + $rowemp_empq["quota"];
                $days_today = "";
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
                            $monthly_qtd_b2b_rev_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

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
            if ($headtxt == "LAST YEAR") {
                $dt_year_value_tmp = date("Y");
                $dt_year_value_tmp = $dt_year_value_tmp - 1;
                $result_empq = db_query("Select quota_month, quota, deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value_tmp . " order by quota_month");
            } else {
                $result_empq = db_query("Select quota_month, quota, deal_quota FROM employee_quota_overall_pallet_sale WHERE quota_year = " . $dt_year_value . " order by quota_month");
            }
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

        //This is for B2B table PALLET Revenue	
        if ($headtxt == "PALLET Leaderboard") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Department</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Actual</u></th>";
            echo "		<th width='100px' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' bgColor='$tbl_head_color' align=center><u>% of Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Profit Margin</u></th>";
            echo "	</tr>";

            //For PALLET Revenue calculation
            echo "<tr><td bgColor='$tbl_color' align ='left'>PALLET Revenue</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "$" . number_format($quota_ov_b2b_rev, 0);
            if (($revenue_sales_b2b >= $quota_ov_b2b_rev) && ($revenue_sales_b2b > 0 && $quota_ov_b2b_rev > 0)) {
                $color = "green";
            } elseif (($revenue_sales_b2b < $quota_ov_b2b_rev && $revenue_sales_b2b > 0) || ($revenue_sales_b2b < 0)  || ($revenue_sales_b2b < $quota_ov_b2b_rev && $quota_ov_b2b_rev > 0)) {
                $color = "red";
            } else {
                $color = "black";
            };
            echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";
            echo "<a href='#' onclick='load_div(789" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color . "'>$" . number_format($revenue_sales_b2b, 0) . "</font></a>";
            echo "<span id='789" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_b2b_rev . "</span>";
            echo "</font></td>";
            echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>";
            if (($revenue_sales_b2b > 0 && $quota_ov_b2b_rev > 0) || ($revenue_sales_b2b == 0 && $quota_ov_b2b_rev > 0)) {
                echo number_format($revenue_sales_b2b * 100 / $quota_ov_b2b_rev, 2) . "%";
            }
            echo "</font></td>";

            $per_val = "";
            if ($tot_quotaactual_mtd > 0 && $revenue_sales_b2b > 0) {
                $per_val = number_format(str_replace(",", "", number_format($tot_quotaactual_mtd, 0)) * 100 / str_replace(",", "", number_format($revenue_sales_b2b, 0)), 2);
                if ($per_val >= 20) {
                    $per_val_color = "green";
                } else {
                    $per_val_color = "red";
                }
            }

            echo "<td bgColor='$tbl_color' align='right'><font color='" . isset($per_val_color) . "'>";
            if ($revenue_sales_b2b > 0) {
                echo $per_val . "%";
            }
            echo "</td>";
            echo "</tr>";
        } else if ($po_flg == "yes") {
        } else {

            if ($headtxt == "LAST YEAR") {
                echo "	<tr style='height:50px;'>";
                echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Department</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Actual</u></th>";
                echo "		<th width='90px' bgColor='$tbl_head_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align=center><u>% of Quota</u></th>";
                echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Actual To Date</u></th>";
                echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>Profit Margin</u></th>";
                echo "	</tr>";
            } else {

                echo "	<tr style='height:50px;'>";
                echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Department</u></th>";
                if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                    echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
                    echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";
                } else {
                    echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
                }
                if ($headtxt == "TODAY LAST YEAR" || $headtxt == "THIS WEEK LAST YEAR" || $headtxt == "THIS QUARTER LAST YEAR" || $headtxt == "THIS MONTH LAST YEAR") {
                    echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Quota to Date</u></th>";
                } else {
                    echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Actual</u></th>";
                }
                if ($headtxt == "LAST YEAR") {
                    echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Quota To Date</u></th>";
                }
                echo "		<th width='90px' bgColor='$tbl_head_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align=center><u>% of Quota</u></th>";
                echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>Profit Margin</u></th>";

                if ($ttylyesno == "ttylyes") {
                    echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>TTLY</u></th>";
                }
                echo "	</tr>";
            }

            echo "<tr><td bgColor='$tbl_color' align ='left'>PALLET Revenue</td>";
            echo "<td bgColor='$tbl_color' align ='right'>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "$" . number_format($quota_ov_b2b_rev, 0);
                echo "</td><td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($monthly_qtd_b2b_rev, 0);
                if ($revenue_sales_b2b >= $monthly_qtd_b2b_rev) {
                    $color = "green";
                } elseif (($revenue_sales_b2b < $monthly_qtd_b2b_rev && $revenue_sales_b2b > 0) || ($revenue_sales_b2b < 0) || ($revenue_sales_b2b < $monthly_qtd_b2b_rev && $monthly_qtd_b2b_rev > 0)) {
                    $color = "red";
                } else {
                    $color = "black";
                };
            } else {
                if (($revenue_sales_b2b >= $quota_ov_b2b_rev)  && ($revenue_sales_b2b > 0 && $quota_ov_b2b_rev > 0)) {
                    $color = "green";
                } elseif (($revenue_sales_b2b < $quota_ov_b2b_rev && $revenue_sales_b2b > 0) || ($revenue_sales_b2b < 0) || ($revenue_sales_b2b < $quota_ov_b2b_rev && $quota_ov_b2b_rev > 0)) {
                    $color = "red";
                } else {
                    $color = "black";
                };
                echo "$" . number_format($quota_ov_b2b_rev, 0);
            }
            echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

            echo "<a href='#' onclick='load_div(789" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color . "'>$" . number_format($revenue_sales_b2b, 0) . "</font></a>";
            echo "<span id='789" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_b2b_rev . "</span>";

            echo "</font></td>";
            echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>";
            if (($revenue_sales_b2b > 0 && ($quota_ov_b2b_rev > 0 || $monthly_qtd_b2b_rev > 0)) || ($revenue_sales_b2b == 0 && ($quota_ov_b2b_rev > 0 || $monthly_qtd_b2b_rev > 0))) {
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    echo number_format($revenue_sales_b2b * 100 / $quota_ov_b2b_rev, 2);
                } else {
                    //echo number_format($revenue_sales_b2b*100/$tot_quota_mtd_TD,2);
                    echo number_format($revenue_sales_b2b * 100 / $monthly_qtd_b2b_rev, 2);
                }
                echo "%";
            }
            echo "</font></td>";

            if ($headtxt == "LAST YEAR") {
                echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($sales_rev_lastyr_tilldt, 0);
                echo "</font></td>";
            }

            if ($tot_quotaactual_mtd > 0 && $revenue_sales_b2b > 0) {
                $per_val = number_format(str_replace(",", "", number_format($tot_quotaactual_mtd, 0)) * 100 / str_replace(",", "", number_format($revenue_sales_b2b, 0)), 2);
                if ($per_val >= 20) {
                    $per_val_color = "green";
                } else {
                    $per_val_color = "red";
                }
            }

            echo "<td bgColor='$tbl_color' align='right'><font color='" . isset($per_val_color) . "'>";
            if ($tot_quotaactual_mtd > 0 && $revenue_sales_b2b > 0) {
                echo isset($per_val) . "%";
            }
            echo "</td>";

            if ($ttylyesno == "ttylyes") {
                echo "<td bgColor='$tbl_color' align = 'right'>";

                echo "<a href='#' onclick='load_div(887" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summ_ttly_sales_rev, 0) . "</font></a>";
                echo "<span id='887" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . isset($lisoftrans_ttly_b2b_rev) . "</span>";

                echo "</font></td>";
            }
            echo "</tr>";
            //This is for B2B table PALLET Revenue	
        }

        //This is for B2B STRETCH table PALLET Revenue	calculation
        $quota_ov_b2b_rev = 0;
        if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov_b2b_rev = $rowemp_ovdata["quota"];
            }
        }

        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "PALLET Leaderboard") {
            $begin = new DateTime($start_Dt);
            $end   = new DateTime($end_Dt);
            $currentdate  = new DateTime(date("Y-m-d"));
            $quota = 0;
            $quota_to_date = 0;
            for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
                $start_Dt_tmp = $datecnt->format("Y-m-d");
                $quota_mtd = 0;
                $newsel = "Select quota_month, quota , deal_quota, quota_year  FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
            }
            $quota_mtd = 0;
            $donot_add = "";
            $days_in_month = 30;
            $quota = 0;
            $dt_month_value_1 = date('m');
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = $quota + $rowemp_empq["quota"];
            }

            $quota_ov_b2b_rev = $quota;
        }

        if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value;
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

        if ($headtxt == "PALLET Leaderboard") {
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
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
                }
            }

            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = isset($quota) + $rowemp_empq["quota"];
                $days_today = "";
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
                            $monthly_qtd_b2b_rev_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

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
            $result_empq = db_query("Select quota_month, quota, deal_quota FROM employee_quota_overall_pallet_sale_stretch WHERE quota_year = " . $dt_year_value . " order by quota_month");
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
        //This is for B2B STRETCH table PALLET Revenue	calculation

        //This is for B2B STRETCH table PALLET Revenue	
        if ($headtxt == "PALLET Leaderboard") {
            echo "<tr><td bgColor='$tbl_color' align ='left'>PALLET Revenue STRETCH</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "$" . number_format($quota_ov_b2b_rev, 0);
            if (($revenue_sales_b2b >= $quota_ov_b2b_rev) && ($revenue_sales_b2b > 0 && $quota_ov_b2b_rev > 0)) {
                $color = "green";
            } elseif (($revenue_sales_b2b < $quota_ov_b2b_rev && $revenue_sales_b2b > 0) || ($revenue_sales_b2b < 0) || ($revenue_sales_b2b < $quota_ov_b2b_rev && $quota_ov_b2b_rev > 0)) {
                $color = "red";
            } else {
                $color = "black";
            };
            echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

            echo "<a href='#' onclick='load_div(787" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color . "'>$" . number_format($revenue_sales_b2b, 0) . "</font></a>";
            echo "<span id='787" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_b2b_rev . "</span>";

            echo "</font></td>";
            echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>";
            if ($quota_ov_b2b_rev > 0) {
                echo number_format($revenue_sales_b2b * 100 / $quota_ov_b2b_rev, 2) . "%";
            }
            echo "</font></td>";

            //$per_val = number_format(str_replace(",", "", number_format($revenue_sales_b2b,0))*100/str_replace(",", "", number_format($revenue_sales_b2b,0)),2);
            //if ($per_val >= 30) { $per_val_color = "green"; } else {$per_val_color = "red"; }

            echo "<td bgColor='$tbl_color' align='right'>";
            //if ($revenue_sales_b2b > 0 && $revenue_sales_b2b > 0){
            //	echo "<font color='" . $per_val_color . "'>" .  $per_val . "%";
            //}	
            echo "</td>";
            echo "</tr>";

            echo "<tr><td bgColor='$tbl_color' colspan=7><hr></td></tr>";
        } else if ($po_flg == "yes") {
        } else {
            echo "<tr><td bgColor='$tbl_color' align ='left'>PALLET Revenue STRETCH</td>";
            echo "<td bgColor='$tbl_color' align ='right'>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "$" . number_format($quota_ov_b2b_rev, 0);
                echo "</td><td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($monthly_qtd_b2b_rev, 0);
                if ($revenue_sales_b2b >= isset($monthly_qtd_b2b_rev)) {
                    $color = "green";
                } elseif (($revenue_sales_b2b < $monthly_qtd_b2b_rev && $revenue_sales_b2b > 0) || ($revenue_sales_b2b < 0)  || ($revenue_sales_b2b < $monthly_qtd_b2b_rev && $monthly_qtd_b2b_rev > 0)) {
                    $color = "red";
                } else {
                    $color = "black";
                };
            } else {
                if (($revenue_sales_b2b >= $quota_ov_b2b_rev)  && ($revenue_sales_b2b > 0 && $quota_ov_b2b_rev > 0)) {
                    $color = "green";
                } elseif (($revenue_sales_b2b < $quota_ov_b2b_rev && $revenue_sales_b2b > 0) || ($revenue_sales_b2b < 0) || ($revenue_sales_b2b < $quota_ov_b2b_rev && $quota_ov_b2b_rev > 0)) {
                    $color = "red";
                } else {
                    $color = "black";
                };
                echo "$" . number_format($quota_ov_b2b_rev, 0);
            }
            echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

            echo "<a href='#' onclick='load_div(787" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color . "'>$" . number_format($revenue_sales_b2b, 0) . "</font></a>";
            echo "<span id='787" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_b2b_rev . "</span>";

            echo "</font></td>";
            echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>";
            if ((isset($monthly_qtd_b2b_rev) > 0 || $quota_ov_b2b_rev > 0)) {
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    echo number_format($revenue_sales_b2b * 100 / $quota_ov_b2b_rev, 2);
                } else {
                    //echo number_format($revenue_sales_b2b*100/$tot_quota_mtd_TD,2);
                    echo number_format($revenue_sales_b2b * 100 / $monthly_qtd_b2b_rev, 2);
                }
                echo "%";
            }
            echo "</font></td>";

            if ($headtxt == "LAST YEAR") {
                //echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($rev_lastyr_tilldt,0);
                //echo "</font></td>";
                echo "<td bgColor='$tbl_color' align='right'>&nbsp;</td>";
            }
            //$per_val = number_format(str_replace(",", "", number_format($revenue_sales_b2b,0))*100/str_replace(",", "", number_format($revenue_sales_b2b,0)),2);
            //if ($per_val >= 30) { $per_val_color = "green"; } else {$per_val_color = "red"; }

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
        }


        if ($headtxt == "PALLET Leaderboard") {
            echo "<tr><td bgColor='$tbl_color' align ='left'>PALLET Gross Profit</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "$" . number_format($quota_ov, 0);
            echo "</td>";
            if (($tot_quotaactual_mtd >= $quota_ov) && ($tot_quotaactual_mtd > 0 && $quota_ov > 0)) {
                $color = "green";
            } elseif (($tot_quotaactual_mtd < $quota_ov && $tot_quotaactual_mtd > 0) || ($tot_quotaactual_mtd < 0) || ($tot_quotaactual_mtd < $quota_ov && $quota_ov > 0)) {
                $color = "red";
            } else {
                $color = "black";
            };

            echo "<td bgColor='$tbl_color' align = 'right'><a href='#' onclick='load_div(919" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
            echo "<span id='919" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans . "</span></td>";

            echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align='right'><font color='" . $color . "'>" . number_format(($tot_quotaactual_mtd / $quota_ov) * 100, 2);
            echo "%</font></td>";

            $per_val = "";
            if ($tot_quotaactual_mtd > 0 && $revenue_sales_b2b > 0) {
                $per_val = number_format(str_replace(",", "", number_format($tot_quotaactual_mtd, 0)) * 100 / str_replace(",", "", number_format($revenue_sales_b2b, 0)), 2);
                if ($per_val >= 20) {
                    $per_val_color = "green";
                } else {
                    $per_val_color = "red";
                }
            }

            if ($revenue_sales_b2b > 0) {
                echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $per_val_color . "'>" . $per_val . "%</font></td>";
            } else {
                echo "<td bgColor='$tbl_color' align = 'right'></td>";
            }

            echo "</tr>";
        } else if ($po_flg == "yes") {
            echo "</table>";
        } else {

            //echo number_format($tot_quota_deal_mtd, 0);

            echo "<tr><td bgColor='$tbl_color' align ='left'>PALLET Gross Profit</td>";
            echo "<td bgColor='$tbl_color' align ='right'>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "$" . number_format($quota_ov, 0);
                echo "</td><td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($monthly_qtd, 0);
                if ($tot_quotaactual_mtd >= $monthly_qtd) {
                    $color = "green";
                } elseif (($tot_quotaactual_mtd < $monthly_qtd && $tot_quotaactual_mtd > 0) || ($tot_quotaactual_mtd < 0) || ($tot_quotaactual_mtd < $monthly_qtd && $monthly_qtd > 0)) {
                    $color = "red";
                } else {
                    $color = "black";
                };
            } else {
                if (($tot_quotaactual_mtd >= $quota_ov)  && ($tot_quotaactual_mtd > 0 && $quota_ov > 0)) {
                    $color = "green";
                } elseif (($tot_quotaactual_mtd < $quota_ov && $tot_quotaactual_mtd > 0) || ($tot_quotaactual_mtd < 0) || ($tot_quotaactual_mtd < $quota_ov && $quota_ov > 0)) {
                    $color = "red";
                } else {
                    $color = "black";
                };
                echo "$" . number_format($quota_ov, 0);
            }
            echo "</td><td bgColor='$tbl_color' align = 'right'><font color='" . $color . "'>";

            echo "<a href='#' onclick='load_div(9911" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
            echo "<span id='9911" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans . "</span>";

            echo "</font></td>";
            echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>";
            if (($quota_ov > 0 || $monthly_qtd > 0)) {
                if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                    echo number_format($tot_quotaactual_mtd * 100 / $quota_ov, 2);
                } else {
                    //echo number_format($tot_quotaactual_mtd*100/$tot_quota_mtd_TD,2);
                    echo number_format($tot_quotaactual_mtd * 100 / $monthly_qtd, 2);
                }
                echo "%";
            }
            echo "</font></td>";

            if ($headtxt == "LAST YEAR") {
                echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($rev_lastyr_tilldt, 0);
                echo "</font></td>";
            }

            $per_val = number_format(str_replace(",", "", number_format($tot_quotaactual_mtd, 0)) * 100 / str_replace(",", "", number_format($revenue_sales_b2b, 0)), 2);
            if ($per_val >= 20) {
                $per_val_color = "green";
            } else {
                $per_val_color = "red";
            }

            echo "<td bgColor='$tbl_color' align='right'><font color='" . $per_val_color . "'>";
            if ($revenue_sales_b2b > 0) {
                echo $per_val . "%";
            }
            echo "</td>";

            if ($ttylyesno == "ttylyes") {
                echo "<td bgColor='$tbl_color' align = 'right'>";

                echo "<a href='#' onclick='load_div(88" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summ_ttly, 0) . "</font></a>";
                echo "<span id='88" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_ttly . "</span>";

                echo "</font></td>";
            }
            echo "</tr>";
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
        string $tilltoday,
        string $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        string $po_flg,
        string $unqid,
        string $ttylyesno,
        $in_dt_range
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

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[UCBZW Reps] TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[UCBZW Reps] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750' id='table9' class='tablesorter'>";
        echo "<thead>";
        if ($headtxt == "PALLET Leaderboard") {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Employee</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Share</u></th>";
            echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>% of Share</u></th>";
            echo "	</tr>";
        } else {
            echo "	<tr style='height:50px;'>";
            echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Employee</u></th>";
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

            if ($headtxt == "PALLET Leaderboard") {
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
                    $days_today = "";
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
                $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_ucbzw_share where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
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

            $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
            $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";
            if ($tilltoday == "Y") {
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
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
                $summtd_SUMPO = $summtd_SUMPO + $inv_amt_totake;

                $summtd_dealcnt = $summtd_dealcnt + 1;
                $lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                $ucbzw_lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
            }

            if ($summtd_SUMPO > 0) {
                $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
            }
            $lisoftrans .= "</table></span>";

            //UCBZW - Vendor Payments
            $popupRevenueContent = "";
            $popupRevenueContent .= "<table cellSpacing='1' cellPadding='1' border='0' width='780'> ";
            $popupRevenueContent .= "<tr><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Vendor Name</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";

            $selData = "SELECT water_transaction.id as rec_id, vendor_id, water_transaction.make_receive_payment, water_transaction.made_payment, 
		water_transaction.vendor_credit, water_transaction.invoice_number, water_transaction.company_id, water_transaction.amount  
		FROM water_transaction WHERE transaction_date BETWEEN '" . $start_Dt . " 00:00:00' AND '" . $end_Dt . " 23:59:59' 
		and repor_entry_emp = '" . $rowemp["initials"] . "' and make_receive_payment = 1 and made_payment = 1 and water_transaction.amount > 0 ";
            //echo $selData . "<br>";
            db();
            $resData = db_query($selData);
            $totalCnt = tep_db_num_rows($resData);
            $creditAmount = 0;
            while ($rowData = array_shift($resData)) {
                $creditAmount = $creditAmount + $rowData['amount'];

                $vendor_name = "";
                $q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '" . $rowData["vendor_id"]  . "'";
                db();
                $query = db_query($q1);
                while ($fetch = array_shift($query)) {
                    $vendor_name = $fetch['Name'];
                }
                db_b2b();
                $getCompDtls = db_query("SELECT ID, nickname FROM companyInfo WHERE loopid = " . $rowData['company_id']);
                $rowCompDtls = array_shift($getCompDtls);

                //echo "Inv - " . $rowData['invoice_number'] . " " . number_format($rowData['amount'],0) . "<br>";

                db();
                $popupRevenueContent .= "<tr><td><a target='_blank' href='https://loops.usedcardboardboxes.com/viewCompany_func_water-mysqli.php?ID=" . $rowCompDtls["ID"] . "&show=watertransactions&company_id=" . $rowData["company_id"] . "&rec_type=&proc=View&searchcrit=&id=" . $rowData["company_id"] . "&b2bid=" . $rowCompDtls["ID"] . "&rec_id=" . $rowData["rec_id"] . "&display=water_sort#watersort'>" . $rowData['invoice_number'] . "</a></td><td>" . $vendor_name . "</td><td>" . $rowCompDtls['nickname'] . "</td><td align='right'>$" . number_format($rowData['amount'], 0) . "</td></tr>";
                $ucbzw_popupRevenueContent .= "<tr><td><a target='_blank' href='https://loops.usedcardboardboxes.com/viewCompany_func_water-mysqli.php?ID=" . $rowCompDtls["ID"] . "&show=watertransactions&company_id=" . $rowData["company_id"] . "&rec_type=&proc=View&searchcrit=&id=" . $rowData["company_id"] . "&b2bid=" . $rowCompDtls["ID"] . "&rec_id=" . $rowData["rec_id"] . "&display=water_sort#watersort'>" . $rowData['invoice_number'] . "</a></td><td>" . $vendor_name . "</td><td>" . $rowCompDtls['nickname'] . "</td><td align='right'>$" . number_format($rowData['amount'], 0) . "</td></tr>";
            }
            $summtd_SUMPO = $summtd_SUMPO + $creditAmount;

            $popupRevenueContent .= "<tr bgColor='#ABC5DF' ><td >&nbsp;</td><td >&nbsp;</td><td>&nbsp;</td><td align='right'>$" . number_format($creditAmount, 0) . "</td></tr>";
            $popupRevenueContent .= "</table>";
            //UCBZW - Vendor Payments

            //For TTLY
            $summ_ttly = 0;
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
                $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

                if ($tilltoday == "Y") {
                    $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
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
                    $inv_amt_totake = $profit_val;
                    $summ_ttly = $summ_ttly + $inv_amt_totake;

                    $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                    $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                    $ucbzw_lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
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

                $sqlmtd = "SELECT inv_number, loop_warehouse.warehouse_name, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
                //$sqlmtd = "SELECT SUM(loop_invoice_details.total) AS SUMPO, count(loop_invoice_details.total) as dealcnt FROM loop_transaction_buyer inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND loop_invoice_details.timestamp BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
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

                    $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                    $rev_lastyr_tilldt = $rev_lastyr_tilldt + $inv_amt_totake;

                    $lisoftrans_lastyear .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                    $ucbzw_lisoftrans_lastyear .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
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
            } elseif (($summtd_SUMPO < $monthly_qtd && $summtd_SUMPO > 0) || $summtd_SUMPO < 0) {
                $color = "red";
            } else {
                $color = "black";
            };
            //commented as per team chat 25Jul2022 if ($monthly_qtd == 0) { $color = "black";}
            $MGArray[] = array(
                'name' => $rowemp["name"], 'name_initial' => $rowemp["initials"], 'emp_email' => $rowemp["email"], 'b2bempid' => $rowemp["b2b_id"], 'empid' => $rowemp["id"], 'start_Dt' => $start_Dt, 'end_Dt' => $end_Dt, 'deal_count' => $summtd_dealcnt, 'quota' => isset($quota_in_st_en),
                'quotatodate' => $monthly_qtd, 'creditAmount' => $creditAmount, 'po_entered' => $summtd_SUMPO, 'ttly' => $summ_ttly, 'percent_val' => $color, 'rev_lastyr_tilldt' => $rev_lastyr_tilldt, 'lisoftrans' => $lisoftrans . "<br>" . $popupRevenueContent, 'lisoftrans_ttly' => $lisoftrans_ttly, 'lisoftrans_lastyear' => isset($lisoftrans_lastyear),
                'ucbzw_lisoftrans' => $ucbzw_lisoftrans, 'ucbzw_popupRevenueContent' => $ucbzw_popupRevenueContent, 'ucbzw_lisoftrans_ttly' => $ucbzw_lisoftrans_ttly, 'ucbzw_lisoftrans_lastyear' => $ucbzw_lisoftrans_lastyear
            );
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

            if ($headtxt == "PALLET Leaderboard") {
                echo "<tr><td bgColor='$tbl_color' width='260px' align ='left'>" . $name . "</td>";
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
                    echo "$" . number_format($monthly_qtd, 0);
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
                    } elseif (($summtd_SUMPO < $monthly_qtd && $summtd_SUMPO > 0) || $summtd_SUMPO < 0) {
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
                    } elseif (($summtd_SUMPO == 0 && $monthly_qtd > 0) || $summtd_SUMPO < 0) {
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
                    } elseif (($summtd_SUMPO == 0 && $monthly_qtd_TD > 0) || $summtd_SUMPO < 0) {
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
                    if ($monthly_qtd > 0) {
                        echo number_format($summtd_SUMPO * 100 / $monthly_qtd, 2) . "%";
                    }
                } else {
                    if ($monthly_qtd_TD > 0) {
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
        //For UCBZW Rep
        echo "<tr ><td style='border-bottom:1px solid black' colspan='7'></td></tr>";
        echo "<tr><td bgColor='$tbl_color' align ='left'><strong>Total</strong></td>";
        if ($headtxt != "PALLET Leaderboard") {
            echo "<td bgColor='$tbl_color' align = right><strong>" . number_format($tot_quota_deal_mtd, 0) . "</strong></td>";
        }
        if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo "$" . number_format($tot_quota_mtd, 0) . "</strong>";
            echo "</td><td bgColor='$tbl_color' align = 'right'><strong>";
            echo "$" . number_format($tot_quota_mtd_TD, 0) . "</strong>";
        } elseif ($headtxt == "PALLET Leaderboard") {
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo "$" . number_format($tot_quota_mtd_TD, 0) . "</strong></td>";
        } else {
            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo "$" . number_format($tot_quota_mtd, 0) . "</strong>";
        }

        if ($headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            echo "</td><td bgColor='$tbl_color' align = 'right'><strong>";
        } else {
            echo "</td><td bgColor='$tbl_color' align = 'right'><strong>";
        }

        if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            if ($tot_quotaactual_mtd >= $tot_quota_mtd) {
                $color = "green";
            } elseif (($tot_quotaactual_mtd < $tot_quota_mtd && $tot_quotaactual_mtd > 0) || $tot_quotaactual_mtd < 0) {
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
            } elseif (($tot_quotaactual_mtd == 0 && $tot_quota_mtd > 0) || $tot_quotaactual_mtd < 0) {
                $color_y_new = "red";
            } else {
                $color_y_new = "black";
            };
        } else {
            if (($tot_quotaactual_mtd * 100 / $tot_quota_mtd_TD) >= 100) {
                $color_y_new = "green";
            } elseif (($tot_quotaactual_mtd * 100 / $tot_quota_mtd_TD) < 100 && $tot_quotaactual_mtd > 0 && $tot_quota_mtd_TD > 0) {
                $color_y_new = "red";
            } elseif ($tot_quotaactual_mtd > 0 && $tot_quota_mtd_TD == 0) {
                $color_y_new = "green";
            } elseif (($tot_quotaactual_mtd == 0 && $tot_quota_mtd_TD > 0) || $tot_quotaactual_mtd < 0) {
                $color_y_new = "red";
            } else {
                $color_y_new = "black";
            };
        }

        $ucbzw_lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $ucbzw_lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

        $ucbzw_lisoftrans .= $ucbzw_lisoftrans_details;

        if ($tot_quotaactual_mtd > 0) {
            $ucbzw_lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_quotaactual_mtd, 0) . "</td></tr>";
        }
        $ucbzw_lisoftrans .= "</table></span>";

        $ucbzw_popupRevenueContent_lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $ucbzw_popupRevenueContent_lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

        $ucbzw_popupRevenueContent_lisoftrans .= $ucbzw_popupRevenueContent;

        if ($tot_creditAmount > 0) {
            $ucbzw_popupRevenueContent_lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_creditAmount, 0) . "</td></tr>";
        }
        $ucbzw_popupRevenueContent_lisoftrans .= "</table></span>";


        echo "<a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "99); return false;'><font color='" . $color_y_new . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
        echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "99' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $ucbzw_lisoftrans . "<br>" . $ucbzw_popupRevenueContent_lisoftrans . "</span>";
        echo "</strong></td>";
        if ($headtxt == "LAST YEAR") {
            $ucbzw_lisoftrans_lastyear = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
            $ucbzw_lisoftrans_lastyear .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

            $ucbzw_lisoftrans_lastyear .= $ucbzw_lisoftrans_lastyear_details;

            if ($MGArraytmp2["rev_lastyr_tilldt"] > 0) {
                $ucbzw_lisoftrans_lastyear .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($MGArraytmp2["rev_lastyr_tilldt"], 0) . "</td></tr>";
            }
            $ucbzw_lisoftrans_lastyear .= "</table></span>";

            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo "<a href='#' onclick='load_div(9966" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($MGArraytmp2["rev_lastyr_tilldt"], 0) . "</font></a>";
            echo "<span id='9966" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $ucbzw_lisoftrans_lastyear . "</span>";
            echo "</strong></td>";
        }
        echo "<td bgColor='$tbl_color' align = 'right'><font color='" . $color_y_new . "'><strong>";
        if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            if ($tot_quotaactual_mtd > 0 && $tot_quota_mtd > 0) {
                echo number_format($tot_quotaactual_mtd * 100 / $tot_quota_mtd, 2) . "%";
            }
        } elseif ($headtxt == "PALLET Leaderboard") {
            if ($tot_quotaactual_mtd > 0 && $tot_quota_mtd_TD > 0) {
                echo number_format($tot_quotaactual_mtd * 100 / $tot_quota_mtd_TD, 2) . "%";
            }
        } else {
            if ($tot_quotaactual_mtd > 0 && $tot_quota_mtd_TD > 0) {
                echo number_format($tot_quotaactual_mtd * 100 / $tot_quota_mtd_TD, 2) . "%";
            }
        }
        echo "</strong></font></td>";
        if ($ttylyesno == "ttylyes") {
            $ucbzw_lisoftrans_ttly = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
            $ucbzw_lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Share Amount</td></tr>";

            $ucbzw_lisoftrans_ttly .= $ucbzw_lisoftrans_ttly_details;

            if ($summtd_ttly_tot > 0) {
                $ucbzw_lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_ttly_tot, 0) . "</td></tr>";
            }
            $ucbzw_lisoftrans_ttly .= "</table></span>";

            echo "<td bgColor='$tbl_color' align = 'right'><strong>";
            echo "<a href='#' onclick='load_div(9977" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summtd_ttly_tot, 0) . "</font></a>";
            echo "<span id='9977" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $ucbzw_lisoftrans_ttly . "</span>";
            echo "</strong></td>";
        }
        echo "</tr>";
        //For UCBZW Rep	


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
            $sql_ovdata = "SELECT quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov = $rowemp_ovdata["quota"];
            }
        }

        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "PALLET Leaderboard") {
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

        if ($headtxt == "PALLET Leaderboard") {
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
            $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_overall_ucbzw_share where  quota_year = " . $dt_year_value . " order by quota_month");
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
        $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
        if ($tilltoday == "Y") {
            $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        } else {
            $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
            $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
        }
        if ($headtxt == "THIS YEAR") {
            //echo $sqlmtd . "<br>";
        }
        $resultmtd = db_query($sqlmtd);
        $summtd_SUMPO = 0;
        $summtd_dealcnt = 0;
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
			WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
			and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            db();
            $resB2bCogs = db_query($qryB2bCogs);

            $estimated_cost = 0;
            while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
            }
            $summtd_SUMPO = $summtd_SUMPO + ($inv_amt_totake - $estimated_cost);

            $summtd_dealcnt = $summtd_dealcnt + 1;
            $lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
        }
        if ($summtd_SUMPO > 0) {
            $tot_quotaactual_mtd = $summtd_SUMPO;
            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
        }
        $lisoftrans .= "</table></span>";

        //This Time Last Year TTLY
        $summ_ttly = 0;
        $tot_quotaactual_mtd_ttly = 0;
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
            $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
            if ($tilltoday == "Y") {
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
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
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                db();
                $resB2bCogs = db_query($qryB2bCogs);

                $estimated_cost = 0;
                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
                }
                $summ_ttly = $summ_ttly + ($inv_amt_totake - $estimated_cost);

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
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0  and Leaderboard = 'UCBZW' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
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

        $global_ucbzwtot_quota_deal_mtd = $tot_quota_deal_mtd;
        $global_ucbzwquota_ov = $quota_ov;
        $global_ucbzwmonthly_qtd = $monthly_qtd;
        $global_ucbzwtot_quotaactual_mtd = $tot_quotaactual_mtd;
        $global_ucbzwunqid = $unqid;
        $global_ucbzwempid = $MGArraytmp2["empid"];
        $global_ucbzlisoftrans = $lisoftrans;
        $global_ucbzwrev_lastyr_tilldt_n = $rev_lastyr_tilldt;
        $global_ucbzwsumm_ttly = $summ_ttly;
        $global_ucbzwlisoftrans_ttly = $lisoftrans_ttly;
    }


    //for GMI
    function leadertbl_GMI(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        string $tilltoday,
        string $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        string $po_flg,
        string $unqid,
        string $ttylyesno,
        $in_dt_range
    ): void {

        global $global_ucbzwtot_quota_deal_mtd;
        global $global_ucbzwquota_ov;
        global $global_ucbzwmonthly_qtd;
        global $global_ucbzwtot_quotaactual_mtd;
        global $global_ucbzwrev_lastyr_tilldt_n;
        global $global_ucbzwunqid;
        global $global_ucbzwempid;
        global $global_ucbzlisoftrans;
        global $global_ucbzwrev_lastyr_tilldt;
        global $global_ucbzwsumm_ttly;
        global $global_ucbzwlisoftrans_ttly;

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

        $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));

        $tot_quota_mtd = 0;
        $tot_quotaactual_mtd = 0;
        $tot_quota_deal_mtd = 0;
        $tot_rev_lastyr_tilldt = 0;

        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") {
            //for the Emp wise order list - View detail list
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.po_date, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'GMI' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
            //echo $sqlmtd . "<br>";
            $resultmtd = db_query($sqlmtd);
            $summtd_SUMPO = 0;
            $summtd_dealcnt = 0;
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
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                db();
                $resB2bCogs = db_query($qryB2bCogs);

                $estimated_cost = 0;
                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
                }
                $summtd_SUMPO = $summtd_SUMPO + ($inv_amt_totake - $estimated_cost);

                $summtd_dealcnt = $summtd_dealcnt + 1;
                $lisoftrans_detail_list = $lisoftrans_detail_list ?? "";
                $lisoftrans_detail_list .= "<tr><td bgColor='$tbl_color'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td><td bgColor='$tbl_color'>" . $nickname . "</td></td><td bgColor='$tbl_color'>" . $summtd["po_date"] . "</td><td bgColor='$tbl_color'>" . isset($name) . "</td><td bgColor='$tbl_color' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";
            }
            //for the Emp wise order list - View detail list
        }

        if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            if ($summtd_SUMPO >= isset($monthly_qtd)) {
                $color = "green";
            } elseif (($summtd_SUMPO < isset($monthly_qtd) && $summtd_SUMPO > 0) || $summtd_SUMPO < 0) {
                $color = "red";
            } else {
                $color = "black";
            };
        }
        $color_y_new = "black";
        if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
            if (($summtd_SUMPO * 100 / isset($monthly_qtd)) >= 100) {
                $color_y_new = "green";
            } elseif ((isset($summtd_SUMPO) * 100 / isset($monthly_qtd)) < 100 && isset($summtd_SUMPO) > 0 && isset($monthly_qtd) > 0) {
                $color_y_new = "red";
            } elseif ($summtd_SUMPO > 0 && isset($monthly_qtd) == 0) {
                $color_y_new = "green";
            } elseif ((isset($summtd_SUMPO) == 0 && isset($monthly_qtd) > 0) || isset($summtd_SUMPO) < 0) {
                $color_y_new = "red";
            } else {
                $color_y_new = "black";
            };
        } else {
            if ((isset($summtd_SUMPO) * 100 / isset($monthly_qtd_TD)) >= 100) {
                $color_y_new = "green";
            } elseif ((isset($summtd_SUMPO) * 100 / isset($monthly_qtd_TD)) < 100 && isset($summtd_SUMPO) > 0 && isset($monthly_qtd_TD) > 0) {
                $color_y_new = "red";
            } elseif (isset($summtd_SUMPO) > 0 && isset($monthly_qtd_TD) == 0) {
                $color_y_new = "green";
            } elseif ((isset($summtd_SUMPO) == 0 && isset($monthly_qtd_TD) > 0) || isset($summtd_SUMPO) < 0) {
                $color_y_new = "red";
            } else {
                $color_y_new = "black";
            };
        }
        $monthly_deal_qtd = isset($monthly_deal_qtd) ?? 0;
        $monthly_deal_qtd = number_format($monthly_deal_qtd, 0);

        $tot_quota_mtd = $tot_quota_mtd + isset($monthly_qtd);
        $tot_quota_mtd_TD = isset($tot_quota_mtd_TD) + isset($monthly_qtd_TD);
        $tot_quotaactual_mtd = $tot_quotaactual_mtd + $summtd_SUMPO;
        $tot_quota_deal_mtd = $tot_quota_deal_mtd + $monthly_deal_qtd;

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
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_GMI WHERE quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov = $rowemp_ovdata["quota"];
            }
        }

        if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" || $headtxt == "PALLET Leaderboard") {
            $begin = new DateTime($start_Dt);
            $end   = new DateTime($end_Dt);
            $currentdate  = new DateTime(date("Y-m-d"));
            $quota = 0;
            $quota_to_date = 0;
            for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
                $start_Dt_tmp = $datecnt->format("Y-m-d");
                $quota_mtd = 0;
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall_GMI where quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_GMI where quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_GMI where quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_GMI where quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_GMI where quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_GMI WHERE quota_year = " . $dt_year_value;
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

        if ($headtxt == "PALLET Leaderboard") {
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
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_GMI where quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_GMI where quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_GMI where quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_GMI where quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
                }
            }

            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = isset($quota) + $rowemp_empq["quota"];
                $days_today = "";
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
                            $monthly_qtd_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

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
            $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_overall_GMI where quota_year = " . $dt_year_value . " order by quota_month");
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

        $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        if ($po_flg == "yes") {
            $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        } else {
            $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
        }
        if ($tilltoday == "Y") {
            $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'GMI' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' ";
        } else {
            $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'GMI' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        }

        if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
            $sqlmtd = "SELECT loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1 and Leaderboard = 'GMI' AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
            $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'GMI' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
        }
        if ($headtxt == "THIS YEAR") {
        }

        //echo $sqlmtd . "<br>";
        $resultmtd = db_query($sqlmtd);
        $summtd_SUMPO = 0;
        $summtd_dealcnt = 0;
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
			WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
			and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
            db();
            $resB2bCogs = db_query($qryB2bCogs);

            $estimated_cost = 0;
            while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
            }
            $summtd_SUMPO = $summtd_SUMPO + ($inv_amt_totake - $estimated_cost);

            $summtd_dealcnt = $summtd_dealcnt + 1;
            if ($po_flg == "yes") {
                $lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"], 0) . "</td></tr>";
            } else {
                $lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_invoice'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(($inv_amt_totake - $estimated_cost), 0) . "</td></tr>";
            }
        }
        if ($summtd_SUMPO > 0) {
            $tot_quotaactual_mtd = $summtd_SUMPO;
            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
        }
        $lisoftrans .= "</table></span>";

        //This Time Last Year TTLY
        $summ_ttly = 0;
        $tot_quotaactual_mtd_ttly = 0;
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
            $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
            if ($tilltoday == "Y") {
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'GMI' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'GMI' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
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
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
				and loop_transaction_buyer.ignore = 0 group by loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
                db();
                $resB2bCogs = db_query($qryB2bCogs);

                $estimated_cost = 0;
                while ($resB2bCogs_row = array_shift($resB2bCogs)) {
                    $estimated_cost = str_replace(",", "", $resB2bCogs_row['estimated_cost']);
                }
                $summ_ttly = $summ_ttly + ($inv_amt_totake - $estimated_cost);

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
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0  and Leaderboard = 'GMI' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
            //echo $sqlmtd;
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
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
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
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall_GMI where quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
        }



        global $global_final_tot_deal_cnt;
        global $global_final_tot_quota;
        global $global_final_tot_quota_to_dt;
        global $global_final_tot_rev;
        global $global_final_tot_rev_ttly;
        global $global_ucbzwrev_lastyr_tilldt;

        $global_final_tot_deal_cnt = $global_final_tot_deal_cnt + $summtd_dealcnt;
        $global_final_tot_quota = $global_final_tot_quota + $quota_ov;
        $global_final_tot_quota_to_dt = $global_final_tot_quota_to_dt + isset($monthly_qtd);
        $global_final_tot_rev = $global_final_tot_rev + $tot_quotaactual_mtd;
        $global_final_tot_rev_ttly = $global_final_tot_rev_ttly + $summ_ttly;
        $global_ucbzwrev_lastyr_tilldt = $global_ucbzwrev_lastyr_tilldt + $rev_lastyr_tilldt;

        /*UCBZW vendor payments STARTS*/

        /****************************************/
        $popupRevenueContent = "";
        $popupRevenueContent .= "<table cellSpacing='1' cellPadding='1' border='0' width='780'> ";
        $popupRevenueContent .= "<tr><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Vendor Name</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";

        if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
            $selData = "SELECT water_transaction.id as rec_id, vendor_id, water_transaction.make_receive_payment, water_transaction.made_payment, 
			water_transaction.vendor_credit, water_transaction.invoice_number, water_transaction.company_id, water_transaction.amount  
			FROM water_transaction WHERE transaction_date BETWEEN '" . $start_Dt . " 00:00:00' AND '" . $end_Dt . " 23:59:59' 
			and make_receive_payment = 1 and made_payment = 1 and water_transaction.amount > 0 ";
            //echo $selData . "<br>";
            db();
            $resData = db_query($selData);
            $totalCnt = tep_db_num_rows($resData);
            $creditAmount = 0;
            while ($rowData = array_shift($resData)) {
                $creditAmount = $creditAmount + $rowData['amount'];

                $vendor_name = "";
                $q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '" . $rowData["vendor_id"]  . "'";
                db();
                $query = db_query($q1);
                while ($fetch = array_shift($query)) {
                    $vendor_name = $fetch['Name'];
                }
                db_b2b();
                $getCompDtls = db_query("SELECT ID, nickname FROM companyInfo WHERE loopid = " . $rowData['company_id']);
                $rowCompDtls = array_shift($getCompDtls);

                $popupRevenueContent .= "<tr><td><a target='_blank' href='https://loops.usedcardboardboxes.com/viewCompany_func_water-mysqli.php?ID=" . $rowCompDtls["ID"] . "&show=watertransactions&company_id=" . $rowData["company_id"] . "&rec_type=&proc=View&searchcrit=&id=" . $rowData["company_id"] . "&b2bid=" . $rowCompDtls["ID"] . "&rec_id=" . $rowData["rec_id"] . "&display=water_sort#watersort'>" . $rowData['invoice_number'] . "</a></td><td>" . $vendor_name . "</td><td>" . $rowCompDtls['nickname'] . "</td><td align='right'>$" . number_format($rowData['amount'], 0) . "</td></tr>";
            }
        }

        if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR") {
            if ($headtxt == "LAST YEAR") {
                $currentYear = date("Y", strtotime("-1 year"));
            } else {
                $currentYear = date("Y");
            }
            $selData = "SELECT water_transaction.id as rec_id, vendor_id, water_transaction.make_receive_payment, water_transaction.made_payment, water_transaction.vendor_credit, water_transaction.invoice_number, water_transaction.company_id, water_transaction.amount FROM water_transaction WHERE year(transaction_date)='" . $currentYear . "' and make_receive_payment = 1 and made_payment = 1 and water_transaction.amount > 0 ";
            //echo $selData;
            db();
            $resData = db_query($selData);
            $totalCnt = tep_db_num_rows($resData);
            $creditAmount = 0;
            while ($rowData = array_shift($resData)) {
                $creditAmount = $creditAmount + $rowData['amount'];

                $vendor_name = "";
                $q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '" . $rowData["vendor_id"]  . "'";
                db();
                $query = db_query($q1);
                while ($fetch = array_shift($query)) {
                    $vendor_name = $fetch['Name'];
                }
                db_b2b();
                $getCompDtls = db_query("SELECT ID, nickname FROM companyInfo WHERE loopid = " . $rowData['company_id']);
                $rowCompDtls = array_shift($getCompDtls);
                $popupRevenueContent .= "<tr><td><a target='_blank' href='https://loops.usedcardboardboxes.com/viewCompany_func_water-mysqli.php?ID=" . $rowCompDtls["ID"] . "&show=watertransactions&company_id=" . $rowData["company_id"] . "&rec_type=&proc=View&searchcrit=&id=" . $rowData["company_id"] . "&b2bid=" . $rowCompDtls["ID"] . "&rec_id=" . $rowData["rec_id"] . "&display=water_sort#watersort'>" . $rowData['invoice_number'] . "</a></td><td>" . $vendor_name . "</td><td>" . $rowCompDtls['nickname'] . "</td><td align='right'>$" . number_format($rowData['amount'], 0) . "</td></tr>";
            }
        }

        if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
            if ($headtxt == "THIS QUARTER LAST YEAR") {
                $end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));
                $start_Date = strtotime('-1 year', strtotime($start_Dt));
            } else {
                $end_Date = strtotime('Y', strtotime($end_Dt));
                $start_Date = strtotime('Y', strtotime($start_Dt));
            }
            $start_Dt_ttly = Date('Y-m-d', $start_Date);
            $end_Dt_ttly = Date('Y-m-d', $end_Date);

            $selData = "SELECT water_transaction.id as rec_id, vendor_id, water_transaction.make_receive_payment, water_transaction.made_payment, water_transaction.vendor_credit, water_transaction.invoice_number, water_transaction.company_id, water_transaction.amount  FROM water_transaction WHERE transaction_date BETWEEN '" . $start_Dt_ttly . " 00:00:00' AND '" . $end_Dt_ttly . " 23:59:59' and make_receive_payment = 1 and made_payment = 1 AND water_transaction.amount > 0 ";
            //echo $selData;
            db();
            $resData = db_query($selData);
            $totalCnt = tep_db_num_rows($resData);
            $creditAmount = 0;
            while ($rowData = array_shift($resData)) {
                $creditAmount = $creditAmount + $rowData['amount'];

                $vendor_name = "";
                $q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '" . $rowData["vendor_id"]  . "'";
                db();
                $query = db_query($q1);
                while ($fetch = array_shift($query)) {
                    $vendor_name = $fetch['Name'];
                }
                db_b2b();
                $getCompDtls = db_query("SELECT ID, nickname FROM companyInfo WHERE loopid = " . $rowData['company_id']);
                $rowCompDtls = array_shift($getCompDtls);
                $popupRevenueContent .= "<tr><td><a target='_blank' href='https://loops.usedcardboardboxes.com/viewCompany_func_water-mysqli.php?ID=" . $rowCompDtls["ID"] . "&show=watertransactions&company_id=" . $rowData["company_id"] . "&rec_type=&proc=View&searchcrit=&id=" . $rowData["company_id"] . "&b2bid=" . $rowCompDtls["ID"] . "&rec_id=" . $rowData["rec_id"] . "&display=water_sort#watersort'>" . $rowData['invoice_number'] . "</a></td><td>" . $vendor_name . "</td><td>" . $rowCompDtls['nickname'] . "</td><td align='right'>$" . number_format($rowData['amount'], 0) . "</td></tr>";
            }
        }

        $popupRevenueContent .= "<tr bgColor='#ABC5DF' ><td >&nbsp;</td><td >&nbsp;</td><td>&nbsp;</td><td align='right'>$" . number_format($creditAmount, 0) . "</td></tr>";
        $popupRevenueContent .= "</table>";


        $summ_ttly_ucbzw_ttly = 0;
        $popupRevenueContent_ttly = "";
        if ($ttylyesno == "ttylyes") {
            $start_Date = strtotime('-1 year', strtotime($start_Dt));
            //if ($headtxt == "THIS WEEK" || $headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER") {
            //	$end_Date = strtotime('-1 year', strtotime(date("Y-m-d")));		
            //}else{
            $end_Date = strtotime('-1 year', strtotime($end_Dt));
            //}

            $start_Dt_ttly = Date('Y-m-d', $start_Date);
            $end_Dt_ttly = Date('Y-m-d', $end_Date);

            $popupRevenueContent_ttly = "";
            $popupRevenueContent_ttly .= "<table cellSpacing='1' cellPadding='1' border='0' width='780'> ";
            $popupRevenueContent_ttly .= "<tr><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Vendor Name</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>G.Profit Amount</td></tr>";
            $selData = "SELECT water_transaction.id as rec_id, vendor_id, water_transaction.make_receive_payment, water_transaction.made_payment, 
			water_transaction.vendor_credit, water_transaction.invoice_number, water_transaction.company_id, water_transaction.amount  
			FROM water_transaction WHERE transaction_date BETWEEN '" . $start_Dt_ttly . " 00:00:00' AND '" . $end_Dt_ttly . " 23:59:59' 
			and make_receive_payment = 1 and made_payment = 1 and water_transaction.amount > 0 ";
            //echo $selData . "<br>";
            db();
            $resData = db_query($selData);
            $summ_ttly_ucbzw_ttly = 0;
            while ($rowData = array_shift($resData)) {
                $summ_ttly_ucbzw_ttly = $summ_ttly_ucbzw_ttly + $rowData['amount'];

                $vendor_name = "";
                $q1 = "SELECT * FROM water_vendors where active_flg = 1 and id = '" . $rowData["vendor_id"]  . "'";
                db();
                $query = db_query($q1);
                while ($fetch = array_shift($query)) {
                    $vendor_name = $fetch['Name'];
                }
                db_b2b();
                $getCompDtls = db_query("SELECT ID, nickname FROM companyInfo WHERE loopid = " . $rowData['company_id']);
                $rowCompDtls = array_shift($getCompDtls);

                $popupRevenueContent_ttly .= "<tr><td><a target='_blank' href='https://loops.usedcardboardboxes.com/viewCompany_func_water-mysqli.php?ID=" . $rowCompDtls["ID"] . "&show=watertransactions&company_id=" . $rowData["company_id"] . "&rec_type=&proc=View&searchcrit=&id=" . $rowData["company_id"] . "&b2bid=" . $rowCompDtls["ID"] . "&rec_id=" . $rowData["rec_id"] . "&display=water_sort#watersort'>" . $rowData['invoice_number'] . "</a></td><td>" . $vendor_name . "</td><td>" . $rowCompDtls['nickname'] . "</td><td align='right'>$" . number_format($rowData['amount'], 0) . "</td></tr>";
            }

            $popupRevenueContent_ttly .= "<tr bgColor='#ABC5DF' ><td >&nbsp;</td><td >&nbsp;</td><td>&nbsp;</td><td align='right'>$" . number_format($summ_ttly_ucbzw_ttly, 0) . "</td></tr>";
            $popupRevenueContent_ttly .= "</table>";
        }
        //End TTLY
        /************************************************/

        //for the UCbZw
        $global_ucbzwtot_quotaactual_mtd = $global_ucbzwtot_quotaactual_mtd + $creditAmount;
        //	$global_ucbzwtot_quotaactual_mtd = $global_ucbzwtot_quotaactual_mtd + $creditAmount_sort;
        $global_ucbzwtot_quota_deal_mtd = $global_ucbzwtot_quota_deal_mtd + $totalCnt;


        if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
            $sql_ovdata1 = "SELECT quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
            db();
            $result_ovdata1 = db_query($sql_ovdata1);
            while ($rowemp_ovdata1 = array_shift($result_ovdata1)) {
                $quota_ov1 = $rowemp_ovdata1["quota"];
            }
        }
        if ($headtxt == "THIS QUARTER" || $headtxt == "LAST QUARTER" || $headtxt == "THIS QUARTER LAST YEAR") {
            $current_qtr = ceil(date('n', strtotime($start_Dt)) / 3);
            db();
            if ($current_qtr == 1) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
            }
            $quota_mtd = 0;
            $donot_add = "";
            $days_in_month = 30;
            $quota = 0;
            $dt_month_value_1 = date('m');
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = $quota + $rowemp_empq["quota"];
            }

            $quota_ov1 = $quota;
        }
        if ($headtxt == "THIS YEAR" || $headtxt == "LAST YEAR" || $headtxt == "LAST TO LAST YEAR") {
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value;
            db();
            $result_ovdata = db_query($sql_ovdata);
            while ($rowemp_ovdata = array_shift($result_ovdata)) {
                $quota_ov1 = $quota_ov1 + $rowemp_ovdata["quota"];
            }
        }
        /*--------------------------------------------*/

        /*=============================================*/
        if ($headtxt == "THIS MONTH" || $headtxt == "LAST MONTH") {
            $quota = $quota_ov1;
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
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
                }
            }

            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = isset($quota) + $rowemp_empq["quota"];
                $days_today = "";
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
                            $monthly_qtd_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

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
            $result_empq = db_query("Select quota_month, quota, deal_quota FROM employee_quota_overall_purchasing_gp WHERE b2borb2c = 'UCBZW_vendor_payments' and quota_year = " . $dt_year_value . " order by quota_month");
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = isset($quota) + $rowemp_empq["quota"];

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
        /*=============================================*/

        $rev_lastyr_tilldt = 0;
        if ($headtxt == "LAST YEAR") {
            $dt_year_value_1 = date('Y') - 1;
            $end_Dt_lasty = Date($dt_year_value_1 . '-' . date('m') . '-' . date('d'));
            $start_Dt_lasty = Date($dt_year_value_1 . '-01-01');
            $selData = "SELECT water_transaction.make_receive_payment, water_transaction.made_payment, water_transaction.vendor_credit, water_transaction.invoice_number, water_transaction.company_id, water_transaction.amount  FROM water_transaction WHERE transaction_date BETWEEN '" . $start_Dt_lasty . " 00:00:00'  AND '" . $end_Dt_lasty . " 23:59:59' AND water_transaction.make_receive_payment = 1 AND water_transaction.made_payment = 1 AND water_transaction.amount > 0 ";
            //echo $selData;
            $resData = db_query($selData);
            while ($rowData = array_shift($resData)) {
                $rev_lastyr_tilldt = $rev_lastyr_tilldt + $rowData['amount'];
            }
        }

        //	echo "<br />".$totalCnt."  /  ".$quota_ov1. " / ".$monthly_qtd." / ".$creditAmount;

        $global_final_tot_deal_cnt = $global_final_tot_deal_cnt + $global_ucbzwtot_quota_deal_mtd;
        $global_final_tot_quota = $global_final_tot_quota + $global_ucbzwquota_ov;
        $global_final_tot_quota_to_dt = $global_final_tot_quota_to_dt + $global_ucbzwmonthly_qtd;
        $global_final_tot_rev = $global_final_tot_rev + $global_ucbzwtot_quotaactual_mtd;
        $global_final_tot_rev_ttly = $global_final_tot_rev_ttly + $global_ucbzwsumm_ttly;
        $global_ucbzwrev_lastyr_tilldt = $global_ucbzwrev_lastyr_tilldt + $global_ucbzwrev_lastyr_tilldt_n + $rev_lastyr_tilldt;

        $unqid = $unqid + 1;
        leadertbl_stretch($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid, $ttylyesno);


        echo "</table>";
    }

    // New function for Stretch
    function leadertbl_stretch(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        string $tilltoday,
        string $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        string $po_flg,
        string $unqid,
        string $ttylyesno,
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
        $quota_one_day = 0;
        $lisoftrans_tot = "";

        $dt_year_value = date('Y', strtotime($start_Dt));
        $dt_month_value = date('m', strtotime($start_Dt));
        $current_year_value = date('Y');

        $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));
        if ($activity_tracker_flg == "yes") {
            $sql = "SELECT * FROM loop_employees WHERE activity_tracker_flg_pallet = 1 and status = 'Active' ORDER BY quota DESC";
        } else {
            $sql = "SELECT * FROM loop_employees WHERE leaderboard = 1 and status = 'Active' ORDER BY quota DESC";
        }
        //quota > 0 and 
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {
            $quota = 0;
            $quotadate = "";
            $deal_quota = 0;
            $monthly_qtd = 0;
            //if ($current_year_value != $dt_year_value) {
            //$result_empq = db_query("Select quota_month from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month limit 1", db() );
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

            if ($headtxt == "PALLET Leaderboard") {
                $begin = new DateTime($start_Dt);
                $end   = new DateTime($end_Dt);
                $quota = 0;
                for ($datecnt = $begin; $datecnt <= $end; $datecnt->modify('+1 day')) {
                    $start_Dt_tmp = $datecnt->format("Y-m-d");
                    $quota_mtd = 0;
                    $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                db();

                if ($current_qtr == 1) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                    }
                }
                if ($current_qtr == 2) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                    }
                }
                if ($current_qtr == 3) {
                    $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                    if ($headtxt == "THIS QUARTER LAST YEAR") {
                        $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                    } else {
                        $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                    }
                }
                if ($current_qtr == 4) {
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
                    $days_today = "";
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
                $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
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

            $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
            if ($po_flg == "yes") {
                $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
            } else {

                $lisoftrans .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
			<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
			<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";
            }
            if ($tilltoday == "Y") {

                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
            } else {

                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
            }

            if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, 
			loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE po_employee LIKE '" . $rowemp["initials"] . "'  and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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

                $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
            }
            if ($headtxt == "THIS YEAR") {
                //echo $sqlmtd . "<br>";
            }
            //echo $sqlmtd . "<br>";
            $sr_no = 0;
            $resultmtd = db_query($sqlmtd);
            $summtd_SUMPO = 0;
            $summtd_dealcnt = 0;
            while ($summtd = array_shift($resultmtd)) {
                //if ($summtd["SUMPO"] > 0) {
                //	$summtd_SUMPO = $summtd["SUMPO"];
                //}
                //$summtd_dealcnt = $summtd["dealcnt"];
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
                    $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]) - str_replace(",", "", $finalpaid_amt_discount);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
                }
                //echo "Po tot4: " .  $rowemp["initials"] . " " . 	$inv_amt_totake . "<br>";		

                //echo "F" . $finalpaid_amt . " " . $summtd["invsent_amt"] . " " . $summtd["inv_amount"] . "<br>";
                //echo $inv_amt_totake . "<br>";
                $summtd_SUMPO = $summtd_SUMPO + str_replace(",", "", number_format($inv_amt_totake, 0));

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
				<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";

                    //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=". $summtd["warehouse_id"] ."&rec_id=" . $summtd["id"] . "&display=buyer_payment'>". $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake,0) . "</td></tr>";
                }
            }

            if ($summtd_SUMPO > 0) {
                //if ($po_flg == "yes"){
                $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td></tr>";
                //}else{
                //	$lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO,0) . "</td></tr>";
                //}			
            }
            $lisoftrans .= "</table></span>";

            //For TTLY
            $summ_ttly = 0;
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
                $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";

                if ($tilltoday == "Y") {
                    $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                } else {
                    $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
                }
                //echo $sqlmtd . "<br>";
                $resultmtd = db_query($sqlmtd);
                while ($summtd = array_shift($resultmtd)) {
                    $finalpaid_amt = 0;
                    $finalpaid_amt_discount = 0;
                    /*$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
				//$result_finalpmt = db_query("Select loop_buyer_payments.amount as amt, method from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
				while ($summtd_finalpmt = array_shift($result_finalpmt)) {
					//if ($summtd_finalpmt["method"] == 'Discount' || $summtd_finalpmt["method"] == 'Write-off'){
						$finalpaid_amt_discount = $summtd_finalpmt["amt"];
					//}else {				
					//	$finalpaid_amt = $finalpaid_amt + $summtd_finalpmt["amt"];
					//}	
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
                        $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]) - str_replace(",", "", $finalpaid_amt_discount);
                    }
                    if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                        $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
                    }

                    $summ_ttly = $summ_ttly + str_replace(",", "", number_format($inv_amt_totake, 0));

                    $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                    $lisoftrans_ttly .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                }

                if ($summ_ttly > 0) {
                    $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td></tr>";
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
                $lisoftrans_lastyear .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue Amount</td></tr>";

                $sqlmtd = "SELECT inv_number, loop_warehouse.warehouse_name, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
                //$sqlmtd = "SELECT SUM(loop_invoice_details.total) AS SUMPO, count(loop_invoice_details.total) as dealcnt FROM loop_transaction_buyer inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id WHERE loop_invoice_details.total > 0 and po_employee LIKE '" . $rowemp["initials"] . "' AND loop_transaction_buyer.ignore < 1 AND loop_invoice_details.timestamp BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
                $resultmtd = db_query($sqlmtd);
                while ($summtd = array_shift($resultmtd)) {
                    //if ($summtd["SUMPO"] > 0) {
                    //$rev_lastyr_tilldt = $summtd["SUMPO"];
                    //}

                    $finalpaid_amt = 0;
                    $finalpaid_amt_discount = 0;
                    /*$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
				//$result_finalpmt = db_query("Select loop_buyer_payments.amount as amt, method from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
				while ($summtd_finalpmt = array_shift($result_finalpmt)) {
					//if ($summtd_finalpmt["method"] == 'Discount' || $summtd_finalpmt["method"] == 'Write-off'){
						$finalpaid_amt_discount = $summtd_finalpmt["amt"];
					//}else {				
					//	$finalpaid_amt = $finalpaid_amt + $summtd_finalpmt["amt"];
					//}	
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

                    $inv_amt_totake = 0;
                    if ($finalpaid_amt > 0) {
                        $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
                    }
                    if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                        $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]) - str_replace(",", "", $finalpaid_amt_discount);
                    }
                    if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                        $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
                    }
                    $nickname = get_nickname_val($summtd["warehouse_name"], $summtd["b2bid"]);

                    $rev_lastyr_tilldt = $rev_lastyr_tilldt + str_replace(",", "", number_format($inv_amt_totake, 0));

                    $lisoftrans_lastyear .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_payment'>" . $summtd["id"] . "</a></td><td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' >" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amt_totake, 0) . "</td></tr>";
                }
                if ($rev_lastyr_tilldt > 0) {
                    $lisoftrans_lastyear .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($rev_lastyr_tilldt, 0) . "</td></tr>";
                }
                $lisoftrans_lastyear .= "</table></span>";
            }

            //echo "$" . number_format($sumytd["SUMPO"],0);
            //$monthly_actual = 0 + $summtd_SUMPO;
            //$monthly_percentage = 100* ( $summtd_SUMPO/($quota*$dim/$quota_days));
            //echo "summtd_SUMPO: " . $headtxt . " " . $summtd_SUMPO. " " . $monthly_qtd  . "<br>";
            if ($headtxt == "LAST TO LAST YEAR") {
                $monthly_qtd = $quota_in_st_en;
            }
            if ($summtd_SUMPO >= $monthly_qtd) {
                $color = "green";
            } elseif ($summtd_SUMPO < $monthly_qtd) {
                $color = "red";
            } else {
                $color = "black";
            };
            if ($monthly_qtd == 0 && $summtd_SUMPO == 0) {
                $color = "black";
            }
            if ($monthly_qtd == 0 && $summtd_SUMPO > 0) {
                $color = "red";
            }

            $MGArray[] = array(
                'name' => $rowemp["name"], 'name_initial' => $rowemp["initials"], 'emp_email' => $rowemp["email"], 'b2bempid' => $rowemp["b2b_id"], 'empid' => $rowemp["id"], 'start_Dt' => $start_Dt, 'end_Dt' => $end_Dt, 'deal_count' => $summtd_dealcnt, 'quota' => $quota_in_st_en,
                'quotatodate' => $monthly_qtd, 'po_entered' => $summtd_SUMPO, 'ttly' => $summ_ttly, 'percent_val' => $color, 'rev_lastyr_tilldt' => $rev_lastyr_tilldt, 'lisoftrans' => $lisoftrans, 'lisoftrans_ttly' => $lisoftrans_ttly, 'lisoftrans_lastyear' => $lisoftrans_lastyear
            );
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
                    $MGArraysort_I[] = $MGArraytmp['companyID'];
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
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value . "";
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
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $sql_ovdata = "SELECT quota FROM employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value;
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

        if ($headtxt == "PALLET Leaderboard") {
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
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");

                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                $result_empq = db_query("Select quota_month, quota , deal_quota from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-10-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-10-01")));
                }
            }

            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = isset($quota) + $rowemp_empq["quota"];
                $days_today = "";
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
                            $monthly_qtd_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

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
            $result_empq = db_query("Select quota_month, quota, deal_quota from employee_quota_overall_pallet_gprofit_stretch WHERE quota_year = " . $dt_year_value . " order by quota_month");
            while ($rowemp_empq = array_shift($result_empq)) {
                $quota = isset($quota) + $rowemp_empq["quota"];
                //$deal_quota = $rowemp_empq["deal_quota"];
                $days_today = "";
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
                            $monthly_qtd_1 = ($days_today * $rowemp_empq["quota"]) / $days_in_month;

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
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        } else {
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
        }

        if (($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK") && $po_flg == "yes") {
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, po_poorderamount as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1 and Leaderboard = 'PALLETS' AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
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
            $sqlmtd = "SELECT loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt, loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dtn . "'  AND '" . $end_Dtn . " 23:59:59'";
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
            /*$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
			//$result_finalpmt = db_query("Select loop_buyer_payments.amount as amt, method from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
			while ($summtd_finalpmt = array_shift($result_finalpmt)) {
				//if ($summtd_finalpmt["method"] == 'Discount' || $summtd_finalpmt["method"] == 'Write-off'){
					$finalpaid_amt_discount = $summtd_finalpmt["amt"];
				//}else {				
				//	$finalpaid_amt = $finalpaid_amt + $summtd_finalpmt["amt"];
				//}	
			}*/

            $inv_amt_totake = 0;
            if ($finalpaid_amt > 0) {
                $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]) - str_replace(",", "", $finalpaid_amt_discount);
            }
            if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
            }
            $revenue_sales_b2b_str = $revenue_sales_b2b_str + $inv_amt_totake;

            $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
			FROM loop_transaction_buyer 
			LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
			INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
			WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
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
				<td bgColor='#E4EAEB' align='right'>" . number_format(str_replace(",", "", number_format(($inv_amt_totake - $estimated_cost), 0)) * 100 / str_replace(",", "", number_format($inv_amt_totake, 0)), 0) . "%</td>
				</tr>";
            }
        }
        if ($summtd_SUMPO > 0) {
            $tot_quotaactual_mtd = $summtd_SUMPO;
            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
			<td bgColor='#ABC5DF' align='right'>$" . number_format($revenue_sales_b2b_str, 0) . "</td>
			<td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td>
			<td bgColor='#ABC5DF' align='right'>" . number_format(str_replace(",", "", number_format($summtd_SUMPO, 0)) * 100 / str_replace(",", "", number_format($revenue_sales_b2b_str, 0)), 0) . "%</td>
			
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
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
            } else {
                $sqlmtd = "SELECT loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0 and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_ttly . "' AND '" . $end_Dt_ttly . " 23:59:59'";
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
                /*$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
				//$result_finalpmt = db_query("Select loop_buyer_payments.amount as amt, method from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
				while ($summtd_finalpmt = array_shift($result_finalpmt)) {
					//if ($summtd_finalpmt["method"] == 'Discount' || $summtd_finalpmt["method"] == 'Write-off'){
						$finalpaid_amt_discount = $summtd_finalpmt["amt"];
					//}else {				
					//	$finalpaid_amt = $finalpaid_amt + $summtd_finalpmt["amt"];
					//}	
				}*/

                $inv_amt_totake = 0;
                if ($finalpaid_amt > 0) {
                    $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]) - str_replace(",", "", $finalpaid_amt_discount);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
                }
                $summ_ttly_sales_rev = $summ_ttly_sales_rev + $inv_amt_totake;

                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
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
				<td bgColor='#E4EAEB' align='right'>" . number_format(str_replace(",", "", number_format(($inv_amt_totake - $estimated_cost), 0)) * 100 / str_replace(",", "", number_format($inv_amt_totake, 0)), 0) . "%</td>
				</tr>";
            }
            if ($summ_ttly > 0) {
                $tot_quotaactual_mtd_ttly = $summ_ttly;
                $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
				<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly_sales_rev, 0) . "</td>
				<td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td>
				<td bgColor='#ABC5DF' align='right'>" . number_format(str_replace(",", "", number_format($summ_ttly, 0)) * 100 / str_replace(",", "", number_format($summ_ttly_sales_rev, 0)), 0) . "%</td>
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
            $sqlmtd = "SELECT loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_invoice_details.total > 0  and Leaderboard = 'PALLETS' AND loop_transaction_buyer.ignore < 1 AND (case when inv_date_of is NULL or inv_date_of = '' then loop_invoice_details.timestamp else STR_TO_DATE(inv_date_of, '%m/%d/%Y') end) BETWEEN '" . $start_Dt_lasty . "'  AND '" . $end_Dt_lasty . " 23:59:59'";
            $resultmtd = db_query($sqlmtd);
            while ($summtd = array_shift($resultmtd)) {
                $finalpaid_amt = 0;
                $finalpaid_amt_discount = 0;
                /*$result_finalpmt = db_query("Select ROUND(sum(loop_buyer_payments.amount),2) as amt from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
				//$result_finalpmt = db_query("Select loop_buyer_payments.amount as amt, method from loop_buyer_payments where method in ('Discount', 'Write-off') and trans_rec_id = " . $summtd["id"]  );
				while ($summtd_finalpmt = array_shift($result_finalpmt)) {
					//if ($summtd_finalpmt["method"] == 'Discount' || $summtd_finalpmt["method"] == 'Write-off'){
						$finalpaid_amt_discount = $summtd_finalpmt["amt"];
					//}else {				
					//	$finalpaid_amt = $finalpaid_amt + $summtd_finalpmt["amt"];
					//}	
				}*/

                $inv_amt_totake = 0;
                if ($finalpaid_amt > 0) {
                    $inv_amt_totake = str_replace(",", "", $finalpaid_amt);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] > 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["invsent_amt"]) - str_replace(",", "", $finalpaid_amt_discount);
                }
                if ($finalpaid_amt == 0 && $summtd["invsent_amt"] == 0 && $summtd["inv_amount"] > 0) {
                    $inv_amt_totake = str_replace(",", "", $summtd["inv_amount"]);
                }

                $qryB2bCogs = "SELECT loop_invoice_details.timestamp, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_transaction_buyer.UCBZeroWaste_flg, loop_transaction_buyer.inv_date_of, sum(loop_transaction_buyer_payments.estimated_cost) as estimated_cost, loop_transaction_buyer.Leaderboard 
				FROM loop_transaction_buyer 
				LEFT JOIN loop_invoice_details ON loop_invoice_details.trans_rec_id = loop_transaction_buyer.id
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer_payments.transaction_buyer_id = loop_transaction_buyer.id 
				WHERE loop_transaction_buyer.Leaderboard = 'PALLETS' and loop_transaction_buyer.id = '" . $summtd["id"] . "'  
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
            echo "<tr><td bgColor='$tbl_color' align ='left'>PALLET Gross Profit STRETCH</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "$" . number_format($quota_ov, 0);
            echo "</td>";
            if (($tot_quotaactual_mtd >= $quota_ov)  && ($tot_quotaactual_mtd > 0 && $quota_ov > 0)) {
                $color = "green";
            } elseif (($tot_quotaactual_mtd < $quota_ov)  || ($tot_quotaactual_mtd < $quota_ov && $quota_ov > 0)) {
                $color = "red";
            } else {
                $color = "black";
            };

            echo "<td bgColor='$tbl_color' align = 'right'><a href='#' onclick='load_div(919" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color . "'>$" . number_format($tot_quotaactual_mtd, 0) . "</font></a>";
            echo "<span id='919" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans . "</span></td>";

            echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color . "'>" . number_format(($tot_quotaactual_mtd / $quota_ov) * 100, 2);
            echo "%</font></td>";

            $per_val = number_format(str_replace(",", "", number_format($tot_quotaactual_mtd, 0)) * 100 / str_replace(",", "", number_format($revenue_sales_b2b_str, 0)), 2);
            if ($per_val >= 20) {
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
            echo "<tr><td bgColor='$tbl_color' align ='left'>PALLET Gross Profit STRETCH</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR") {
                echo "$" . number_format($quota_ov, 0);
                echo "</td><td bgColor='$tbl_color' align = 'right'>";
                echo "$" . number_format($monthly_qtd, 0);
                if ($tot_quotaactual_mtd >= $monthly_qtd) {
                    $color = "green";
                } elseif (($tot_quotaactual_mtd < $monthly_qtd && $tot_quotaactual_mtd > 0)  || ($tot_quotaactual_mtd < $monthly_qtd && $monthly_qtd > 0)) {
                    $color = "red";
                } else {
                    $color = "black";
                };
            } else {
                if (($tot_quotaactual_mtd >= $quota_ov) && ($tot_quotaactual_mtd > 0 && $quota_ov > 0)) {
                    $color = "green";
                } elseif (($tot_quotaactual_mtd < $quota_ov) || ($tot_quotaactual_mtd < $quota_ov && $quota_ov > 0)) {
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
            echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align='right'><font color='" . $color . "'>";
            if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                if (($tot_quotaactual_mtd > 0 && ($quota_ov > 0)) || ($tot_quotaactual_mtd == 0 && ($quota_ov > 0))) {
                    echo number_format($tot_quotaactual_mtd * 100 / $quota_ov, 2) . "%";
                }
            } else {
                //echo number_format($tot_quotaactual_mtd*100/$tot_quota_mtd_TD,2);
                if (($tot_quotaactual_mtd > 0 && ($monthly_qtd > 0)) || ($tot_quotaactual_mtd == 0 && ($monthly_qtd > 0))) {
                    echo number_format($tot_quotaactual_mtd * 100 / $monthly_qtd, 2) . "%";
                }
            }
            echo "</font></td>";

            echo "<td bgColor='$tbl_color' align='right'>&nbsp;</td>";

            if ($headtxt == "LAST YEAR") {
                //echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($rev_lastyr_tilldt,0);
                //echo "</font></td>";
                echo "<td bgColor='$tbl_color' align='right'>&nbsp;</td>";
            }

            if ($ttylyesno == "ttylyes") {
                echo "<td bgColor='$tbl_color' align = 'right'>&nbsp;";


                echo "</font></td>";
            }

            echo "</tr>";
        }
    }
    //leadertbl_stretch function ends here


    function activity_tracker(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        string $tilltoday,
        string $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        string $po_flg,
        string $unqid,
        string $ttylyesno
    ): void {
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
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else if ($headtxt == "Search" && $po_flg == "yes") {
            $div_id_emp_list = "999112";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999111";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999222";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] Activity Tracker LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999333";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999444";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] <a href='#' onclick='load_div(" . $div_id_emp_list . "); return false;'>View Detail List</a></strong></td>";
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sales Reps] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650' id='table9' class='tablesorter'>";
        echo "<thead>";
        echo "	<tr style='height:50px;'>";
        echo "		<th align='left' bgColor='$tbl_head_color' width='250px'><u>Employee</u></th>";
        echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Leads</u></th>";
        echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Emails</u>
		<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
		<span class='tooltiptext'>Green is >= 15 Avg Emails/Day Else Red</span></div>
	</th>";
        echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Calls</u>
		<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
		<span class='tooltiptext'>Green is >= 15 Avg Calls/Day Else Red</span></div>
	</th>";
        echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Demand Entries</u></th>";
        echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Quote Requests</u></th>";
        echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Quotes</u></th>";
        echo "		<th width='50px' bgColor='$tbl_head_color' align='center'><u>Deals</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>1st Time Customers</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>PO Amount</u></th>";
        echo "	</tr>";

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

        $tot_lead_tmp = 0;
        $tot_first_time_rec = 0;
        $tot_quote_req_cnt = 0;
        $tot_demand_entry_tmp = 0;
        $tot_quota_contact = 0;
        $tot_quota_contact_ph = 0;
        $tot_quota_quotes = 0;
        $tot_quota_deal_mtd = 0;

        $dt_year_value = date('Y', strtotime($start_Dt));
        $dt_month_value = date('m', strtotime($start_Dt));
        $current_year_value = date('Y');

        $tot_lead_tmp = 0;
        $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));
        $employee_id_list = "";
        //$sql = "SELECT * FROM loop_employees WHERE activity_tracker_flg_pallet = 1 and status = 'Active' ORDER BY quota DESC";
        $sql_emp = "SELECT id, activity_tracker_flg, activity_tracker_flg_purchasing, activity_tracker_flg_purchasing_pallet FROM loop_employees WHERE status = 'Active' ORDER BY quota DESC";
        db();
        $result_emp = db_query($sql_emp);
        while ($rowemp = array_shift($result_emp)) {

            db_b2b();
            db_query("Update employees set activity_tracker_flg = '" . $rowemp["activity_tracker_flg"] . "' , 
		activity_tracker_flg_purchasing = '" . $rowemp["activity_tracker_flg_purchasing"] . "', 
		activity_tracker_flg_purchasing_pallet = '" . $rowemp["activity_tracker_flg_purchasing_pallet"] . "'
		where loopID = '" . $rowemp["id"] . "'");

            db();
        }

        //$sql = "(SELECT sum(employee_all_activity_details.sales_po_amunt) as sumsales_po_amunt , employee_all_activity_details.employee_id as id, employees.activity_tracker_flg, employees.status, employees.name, employees.initials, employees.employeeID as b2b_id 
        //FROM employee_all_activity_details left join employees on employees.loopID = employee_all_activity_details.employee_id
        //WHERE (employee_all_activity_details.entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59') 
        //and activity_tracker_flg_pallet = 1 and status = 'Active' group by employee_id )
        //union (SELECT -1 as sumsales_po_amunt, -1, 1, 'Active', 'Other', 'Other', -1)
        //ORDER BY (sumsales_po_amunt) DESC";

        /*$sql = "SELECT sum(employee_all_activity_details.sales_po_amunt) as sumsales_po_amunt , employee_all_activity_details.employee_id as id, employees.activity_tracker_flg, employees.status, employees.name, employees.initials, employees.employeeID as b2b_id 
	FROM employee_all_activity_details left join employees on employees.loopID = employee_all_activity_details.employee_id
	WHERE (employee_all_activity_details.entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59') 
	and activity_tracker_flg_pallet = 1 and status = 'Active' group by employee_id 
	ORDER BY (sumsales_po_amunt) DESC";*/

        $sql = "SELECT count(*) as cnt, sum(round(loop_transaction_buyer.po_poorderamount,0)) as sumsales_po_amunt , loop_employees.id, 
	loop_employees.activity_tracker_flg_pallet, loop_employees.status, loop_employees.name, loop_employees.initials, loop_employees.b2b_id
	FROM loop_transaction_buyer right join loop_employees on loop_employees.initials = loop_transaction_buyer.po_employee
	WHERE (loop_transaction_buyer.transaction_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59') and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.Leaderboard = 'PALLETS'
	and loop_employees.activity_tracker_flg_pallet = 1 and loop_employees.status = 'Active' group by loop_employees.id 
	ORDER BY (sumsales_po_amunt) DESC";

        //echo $sql . "<br>";
        $emp_id_list_str = "";
        db();
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {
            //on employee_all_activity_details.employee_id = loop_employees.id

            $emp_id_list_str .= $rowemp["id"] . ",";
            $lead_tmp = 0;
            $contact_act_tmp = 0;
            $contact_act_ph1 = 0;
            $demand_entry_tmp = 0;
            $quote_req_cnt = 0;
            $quotes_sent = 0;
            $monthly_deal_qtd = 0;
            $first_time_rec = 0;
            $summtd_SUMPO = 0;
            db_b2b();
            $result_crm = db_query("Select sum(leads) as leads, sum(daily_touches) as daily_touches, sum(daily_quotes) as daily_quotes, sum(daily_deals) as daily_deals, 
		sum(email_sent) as email_sent, sum(calls_made) as calls_made, sum(demand_entries) as demand_entries, sum(quote_requests) as quote_requests, 
		sum(first_time_customer) as first_time_customer, sum(sales_po_amunt) as sales_po_amunt, sum(first_time_supplier) as first_time_supplier, sum(purchase_orders) as purchase_orders, 
		sum(po_total) as po_total
		from employee_all_activity_details where entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and employee_id = '" . $rowemp["id"] . "'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $lead_tmp = $rowemp_crm["leads"];
                $contact_act_tmp = $rowemp_crm["email_sent"];
                $contact_act_ph1 = $rowemp_crm["calls_made"];
                $demand_entry_tmp = $rowemp_crm["demand_entries"];
                $quote_req_cnt = $rowemp_crm["quote_requests"];
                $quotes_sent = $rowemp_crm["daily_quotes"];
                //$monthly_deal_qtd = $rowemp_crm["daily_deals"] ;
                //$first_time_rec = $rowemp_crm["first_time_customer"] ;
                //$summtd_SUMPO = $rowemp_crm["sales_po_amunt"] ;
            }
            $summtd_SUMPO = $rowemp["sumsales_po_amunt"];
            $monthly_deal_qtd = $rowemp["cnt"];
            db();
            $result_crm = db_query("SELECT id, transaction_date FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) 
		and po_employee LIKE '" . $rowemp["initials"] . "' and loop_transaction_buyer.leaderboard = 'PALLETS' and loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $first_time_rec = $first_time_rec + 1;
            }

            /*$sqlmtd = "SELECT count(*) as cnt, sum(round(loop_transaction_buyer.po_poorderamount,0)) as po_poorderamount
		FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id 
		inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE 
		po_employee LIKE '" . $rowemp["initials"] . "' and loop_transaction_buyer.ignore < 1 and Leaderboard = 'PALLETS' 
		AND transaction_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'";
		$result_crm = db_query($sqlmtd, db());
		while ($rowemp_crm = array_shift($result_crm)) {
			$summtd_SUMPO = $rowemp_crm["po_poorderamount"];
			$monthly_deal_qtd = $rowemp_crm["cnt"];
		}
		*/

            if ($rowemp["id"] == -1) {
                $emp_name = "Other";
                $emp_initials = "Other";
                $emp_b2b_id = -1;
                $lead_tmp = "";
                $contact_act_tmp = "";
                $contact_act_ph1 = "";
            } else {
                $emp_name = $rowemp["name"];
                $emp_initials = $rowemp["initials"];
                $emp_b2b_id = $rowemp["b2b_id"];
            }

            db();
            $tot_lead_tmp = $tot_lead_tmp + $lead_tmp;
            $tot_first_time_rec = $tot_first_time_rec + $first_time_rec;
            $tot_quote_req_cnt = $tot_quote_req_cnt + $quote_req_cnt;
            $tot_demand_entry_tmp = $tot_demand_entry_tmp + $demand_entry_tmp;
            $tot_quota_contact = $tot_quota_contact + $contact_act_tmp;
            $tot_quota_contact_ph = $tot_quota_contact_ph + $contact_act_ph1;
            $tot_quota_quotes = $tot_quota_quotes + $quotes_sent;

            $tot_quota_deal_mtd = $tot_quota_deal_mtd + $monthly_deal_qtd;
            $tot_quotaactual_mtd = $tot_quotaactual_mtd + $summtd_SUMPO;

            $lisoftrans = "";
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
            //changed form 20 to 15
            if (isset($week_val) == 1) {
                if ($contact_act_tmp >= 15) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 15) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 15) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 15) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 2) {
                if ($contact_act_tmp >= 30) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 30) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 30) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 30) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 3) {
                if ($contact_act_tmp >= 45) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 45) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 45) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 45) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 4) {
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
            if (isset($week_val) >= 5) {
                if ($contact_act_tmp >= 75) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 75) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 75) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 75) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }

            echo "<tr><td bgColor='$tbl_color' align ='left'>" . $emp_name . "</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?showlead=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $rowemp["b2b_id"] . "'>";
            echo number_format($lead_tmp, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo $email_color_code . " " . number_format($contact_act_tmp, 0) . " " . $email_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&phone=y&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?showquote_req=demand_entry&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo number_format($demand_entry_tmp, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?showquote_req=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>";
            echo number_format($quote_req_cnt, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y&b2bempid=" . $emp_b2b_id . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
            echo number_format($quotes_sent, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo number_format($monthly_deal_qtd, 0);
            echo "</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?showfirsttimerec=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>";
            echo number_format($first_time_rec, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?pallet=yes&showsalespoamt=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>$";
            echo number_format($summtd_SUMPO, 0);
            echo "</a></td>";
            echo "</tr>";
        }

        //For employee who doesn't have any record from main query
        if ($emp_id_list_str != "") {
            $emp_id_list_str = substr($emp_id_list_str, 0, strlen($emp_id_list_str) - 1);
            $sql = "SELECT 0 as cnt, 0 as sumsales_po_amunt , loop_employees.id, 
		loop_employees.activity_tracker_flg_pallet, loop_employees.status, loop_employees.name, loop_employees.initials, loop_employees.b2b_id
		FROM loop_employees 
		WHERE loop_employees.activity_tracker_flg_pallet = 1 and loop_employees.status = 'Active' and id not in ($emp_id_list_str)
		ORDER BY loop_employees.name ";
        } else {
            $sql = "SELECT 0 as cnt, 0 as sumsales_po_amunt , loop_employees.id, 
		loop_employees.activity_tracker_flg_pallet, loop_employees.status, loop_employees.name, loop_employees.initials, loop_employees.b2b_id
		FROM loop_employees 
		WHERE loop_employees.activity_tracker_flg_pallet = 1 and loop_employees.status = 'Active' 
		ORDER BY loop_employees.name ";
        }
        //echo $sql . "<br>";
        db();
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {

            $lead_tmp = 0;
            $contact_act_tmp = 0;
            $contact_act_ph1 = 0;
            $demand_entry_tmp = 0;
            $quote_req_cnt = 0;
            $quotes_sent = 0;
            $monthly_deal_qtd = 0;
            $first_time_rec = 0;
            $summtd_SUMPO = 0;
            db_b2b();
            $result_crm = db_query("Select sum(leads) as leads, sum(daily_touches) as daily_touches, sum(daily_quotes) as daily_quotes, sum(daily_deals) as daily_deals, 
		sum(email_sent) as email_sent, sum(calls_made) as calls_made, sum(demand_entries) as demand_entries, sum(quote_requests) as quote_requests, 
		sum(first_time_customer) as first_time_customer, sum(sales_po_amunt) as sales_po_amunt, sum(first_time_supplier) as first_time_supplier, sum(purchase_orders) as purchase_orders, 
		sum(po_total) as po_total
		from employee_all_activity_details where entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and employee_id = '" . $rowemp["id"] . "'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $lead_tmp = $rowemp_crm["leads"];
                $contact_act_tmp = $rowemp_crm["email_sent"];
                $contact_act_ph1 = $rowemp_crm["calls_made"];
                $demand_entry_tmp = $rowemp_crm["demand_entries"];
                $quote_req_cnt = $rowemp_crm["quote_requests"];
                $quotes_sent = $rowemp_crm["daily_quotes"];
                //$monthly_deal_qtd = $rowemp_crm["daily_deals"] ;
                //$first_time_rec = $rowemp_crm["first_time_customer"] ;
                //$summtd_SUMPO = $rowemp_crm["sales_po_amunt"] ;
            }
            $summtd_SUMPO = $rowemp["sumsales_po_amunt"];
            $monthly_deal_qtd = $rowemp["cnt"];
            db();
            $result_crm = db_query("SELECT id, transaction_date FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) 
		and po_employee LIKE '" . $rowemp["initials"] . "' and loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $first_time_rec = $first_time_rec + 1;
            }

            if ($rowemp["id"] == -1) {
                $emp_name = "Other";
                $emp_initials = "Other";
                $emp_b2b_id = -1;
                $lead_tmp = "";
                $contact_act_tmp = "";
                $contact_act_ph1 = "";
            } else {
                $emp_name = $rowemp["name"];
                $emp_initials = $rowemp["initials"];
                $emp_b2b_id = $rowemp["b2b_id"];
            }

            db();
            $tot_lead_tmp = $tot_lead_tmp + $lead_tmp;
            $tot_first_time_rec = $tot_first_time_rec + $first_time_rec;
            $tot_quote_req_cnt = $tot_quote_req_cnt + $quote_req_cnt;
            $tot_demand_entry_tmp = $tot_demand_entry_tmp + $demand_entry_tmp;
            $tot_quota_contact = $tot_quota_contact + $contact_act_tmp;
            $tot_quota_contact_ph = $tot_quota_contact_ph + $contact_act_ph1;
            $tot_quota_quotes = $tot_quota_quotes + $quotes_sent;

            $tot_quota_deal_mtd = $tot_quota_deal_mtd + $monthly_deal_qtd;
            $tot_quotaactual_mtd = $tot_quotaactual_mtd + $summtd_SUMPO;

            $lisoftrans = "";
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
            //changed form 20 to 15
            if (isset($week_val) == 1) {
                if ($contact_act_tmp >= 15) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 15) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 15) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 15) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 2) {
                if ($contact_act_tmp >= 30) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 30) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 30) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 30) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 3) {
                if ($contact_act_tmp >= 45) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 45) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 45) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 45) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 4) {
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
            if (isset($week_val) >= 5) {
                if ($contact_act_tmp >= 75) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 75) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 75) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 75) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }

            echo "<tr><td bgColor='$tbl_color' align ='left'>" . $emp_name . "</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?showlead=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $rowemp["b2b_id"] . "'>";
            echo number_format($lead_tmp, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo $email_color_code . " " . number_format($contact_act_tmp, 0) . " " . $email_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&phone=y&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?showquote_req=demand_entry&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo number_format($demand_entry_tmp, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?showquote_req=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>";
            echo number_format($quote_req_cnt, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y&b2bempid=" . $emp_b2b_id . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
            echo number_format($quotes_sent, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo number_format($monthly_deal_qtd, 0);
            echo "</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?showfirsttimerec=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>";
            echo number_format($first_time_rec, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_list.php?pallet=yes&showsalespoamt=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>$";
            echo number_format($summtd_SUMPO, 0);
            echo "</a></td>";
            echo "</tr>";
        }
        //For employee who doesn't have any record from main query

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
        echo "<td bgColor='$tbl_color' align = 'right'><strong>$" . number_format($tot_quotaactual_mtd, 0);
        echo "</td></tr>";

        echo "</tbody>";
        echo "</table>";

        activity_tracker_purchasing($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid, $ttylyesno, "yes");
    }

    function activity_tracker_purchasing(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        string $tilltoday,
        string $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        string $po_flg,
        string $unqid,
        string $ttylyesno,
        string $activity_tracker_flg = "no"
    ): void {
        db();

        echo "<br><table cellSpacing='1' cellPadding='1' border='0' width='650'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else if ($headtxt == "Search" && $po_flg == "yes") {
            $div_id_emp_list = "999112";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] PO ENTERED [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999111";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] PO ENTERED THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999222";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] PO ENTERED LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999333";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999444";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[PALLET Sourcing Reps] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='650' id='table9' class='tablesorter'>";
        echo "<thead>";
        echo "	<tr style='height:50px;'>";
        echo "		<th align='left' bgColor='$tbl_head_color' width='200px'><u>Employee</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Leads</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Emails</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Calls</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Purchase Orders</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>1st Time Supplier</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>PO Totals</u></th>";
        echo "	</tr>";
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

        $tot_lead_tmp = 0;
        $tot_first_time_rec = 0;
        $tot_quote_req_cnt = 0;
        $tot_demand_entry_tmp = 0;
        $tot_quota_contact = 0;
        $tot_quota_contact_ph = 0;
        $tot_po_total = 0;
        $tot_quota_quotes = 0;

        $dt_year_value = date('Y', strtotime($start_Dt));
        $dt_month_value = date('m', strtotime($start_Dt));
        $current_year_value = date('Y');

        $tot_lead_tmp = 0;
        $tot_quote_amount = 0;
        $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));
        //$sql = "SELECT * FROM loop_employees WHERE activity_tracker_flg_purchasing_pallet = 1 and status = 'Active' ORDER BY quota DESC";

        //(employee_all_activity_details.entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59') 
        //$sql = "(SELECT sum(employee_all_activity_details.po_total) as sumpo_total, employee_all_activity_details.employee_id as id, employees.activity_tracker_flg_purchasing_pallet, employees.status, employees.name, employees.initials, employees.employeeID as b2b_id FROM employee_all_activity_details left join employees on employees.loopID = employee_all_activity_details.employee_id
        //WHERE (employee_all_activity_details.entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59')
        //and activity_tracker_flg_purchasing_pallet = 1 and status = 'Active'  group by employee_id )
        //union (SELECT -1 as sumpo_total, -2, 1, 'Active', 'Other', 'Other', -2)
        //ORDER BY sumpo_total DESC";

        /*$sql = "SELECT sum(employee_all_activity_details.po_total) as sumpo_total, employee_all_activity_details.employee_id as id, 
	employees.activity_tracker_flg_purchasing_pallet, employees.status, employees.name, employees.initials, employees.employeeID as b2b_id FROM employee_all_activity_details left join employees on employees.loopID = employee_all_activity_details.employee_id
	WHERE (employee_all_activity_details.entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59')
	and activity_tracker_flg_purchasing_pallet = 1 and status = 'Active'  group by employee_id
	ORDER BY sumpo_total DESC";*/

        $sql = "SELECT count(*) as cnt, sum(round(quote_total,0)) as sumpo_total , employees.loopID as id, 
	employees.activity_tracker_flg_purchasing_pallet, employees.status, employees.name, employees.initials, employees.employeeID as b2b_id
	FROM quote right join employees on employees.employeeID = quote.rep
	WHERE (quoteDate BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59') 
	and employees.activity_tracker_flg_purchasing_pallet = 1 and employees.status = 'Active' group by employees.loopID 
	ORDER BY (sumpo_total) DESC";

        //(employee_all_activity_details.employee_id = -2)
        //echo $sql . "<br>";
        $emp_id_list_str = "";
        $emp_loop_id_list_str = "";
        db_b2b();
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {

            $emp_id_list_str .= $rowemp["b2b_id"] . ",";
            $emp_loop_id_list_str .= $rowemp["id"] . ",";

            $lead_tmp = 0;
            $contact_act_tmp = 0;
            $contact_act_ph1 = 0;
            $demand_entry_tmp = 0;
            $quote_req_cnt = 0;
            $purchase_orders = 0;
            $monthly_deal_qtd = 0;
            $first_time_rec = 0;
            $po_total = 0;
            db_b2b();

            $result_crm = db_query("Select sum(leads) as leads, 
		sum(email_sent) as email_sent, sum(calls_made) as calls_made, sum(quote_requests) as quote_requests, 
		sum(first_time_customer) as first_time_customer, sum(sales_po_amunt) as sales_po_amunt, sum(first_time_supplier) as first_time_supplier, 
		sum(purchase_orders) as purchase_orders,  sum(po_total) as po_total
		from employee_all_activity_details where entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and employee_id = '" . $rowemp["id"] . "'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $lead_tmp = $rowemp_crm["leads"];
                $contact_act_tmp = $rowemp_crm["email_sent"];
                $contact_act_ph1 = $rowemp_crm["calls_made"];
                $demand_entry_tmp = $rowemp_crm["demand_entries"];
                $quote_req_cnt = $rowemp_crm["quote_requests"];
                //$purchase_orders = $rowemp_crm["purchase_orders"] ;

                //$first_time_rec = $rowemp_crm["first_time_supplier"] ;
                //$po_total = $rowemp_crm["po_total"] ;
            }
            $po_total = $rowemp["sumpo_total"];
            $purchase_orders = $rowemp["cnt"];

            db();
            $result_crm = db_query("SELECT 1 as cnt, STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') as sort_date FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
		inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
		WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
		and loop_boxes.owner = '" . $rowemp["id"] . "' AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' group by loop_boxes_sort.trans_rec_id");
            while ($rowemp_crm = array_shift($result_crm)) {
                $first_time_rec = $first_time_rec + $rowemp_crm["cnt"];
            }

            /*$dt_view_qry = "Select count(*) as cnt, sum(quote_total) as quote_total from quote where quoteType = 'PO' 
		and quoteDate between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and rep = '" . $rowemp["b2b_id"] . "'";
		$result_crm = db_query($dt_view_qry);
		while ($rowemp_crm = array_shift($result_crm)) {
			$po_total = $rowemp_crm["quote_total"];
			$purchase_orders = $rowemp_crm["cnt"];
		}		*/

            if ($rowemp["id"] == -2) {
                $emp_name = "Other";
                $emp_initials = "Other";
                $emp_b2b_id = -2;
                $lead_tmp = "";
                $contact_act_tmp = "";
                $contact_act_ph1 = "";
            } else {
                $emp_name = $rowemp["name"];
                $emp_initials = $rowemp["initials"];
                $emp_b2b_id = $rowemp["b2b_id"];
            }

            db();
            $tot_lead_tmp = $tot_lead_tmp + $lead_tmp;
            $tot_first_time_rec = $tot_first_time_rec + $first_time_rec;
            $tot_quote_req_cnt = $tot_quote_req_cnt + $quote_req_cnt;
            $tot_demand_entry_tmp = $tot_demand_entry_tmp + $demand_entry_tmp;
            $tot_quota_contact = $tot_quota_contact + $contact_act_tmp;
            $tot_quota_contact_ph = $tot_quota_contact_ph + $contact_act_ph1;
            $tot_quota_quotes = $tot_quota_quotes + $purchase_orders;
            $tot_quote_amount = $tot_quote_amount + $po_total;

            $lisoftrans = "";
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
            //changed form 20 to 15
            if (isset($week_val) == 1) {
                if ($contact_act_tmp >= 15) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 15) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 15) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 15) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 2) {
                if ($contact_act_tmp >= 30) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 30) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 30) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 30) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 3) {
                if ($contact_act_tmp >= 45) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 45) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 45) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 45) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 4) {
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
            if (isset($week_val) >= 5) {
                if ($contact_act_tmp >= 75) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 75) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 75) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 75) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }

            if ($rowemp["id"] == -2) {
                $emp_name = "Other";
                $emp_initials = "Other";
                $emp_b2b_id = -2;
            } else {
                $emp_name = $rowemp["name"];
                $emp_initials = $rowemp["initials"];
                $emp_b2b_id = $rowemp["b2b_id"];
            }

            echo "<tr><td bgColor='$tbl_color' align ='left'>" . $emp_name . "</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_purchasing_show_list.php?showlead=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $rowemp["b2b_id"] . "'>";
            echo number_format($lead_tmp, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo $email_color_code . " " . number_format($contact_act_tmp, 0) . " " . $email_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&phone=y&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y&b2bempid=" . $emp_b2b_id . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
            echo number_format($purchase_orders, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_purchasing_show_list.php?showfirsttimerec=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>";
            echo number_format($first_time_rec, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_purchasing_show_list.php?quote_amount=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>$";
            echo number_format($po_total, 0);
            echo "</a></td>";
            echo "</tr>";
        }

        //For emp not found in first query
        $emp_id_list_str = trim($emp_id_list_str);
        if ($emp_id_list_str != "") {
            $emp_id_list_str = substr($emp_id_list_str, 0, strlen($emp_id_list_str) - 1);
        }
        $emp_loop_id_list_str = trim($emp_loop_id_list_str);
        if ($emp_loop_id_list_str != "") {
            $emp_loop_id_list_str = substr($emp_loop_id_list_str, 0, strlen($emp_loop_id_list_str) - 1);
        }


        if ($emp_id_list_str != "") {
            $sql = "SELECT 0 as cnt, 0 as sumpo_total , employees.loopID as id, 
		employees.activity_tracker_flg_purchasing_pallet, employees.status, employees.name, employees.initials, employees.employeeID as b2b_id
		FROM employees 
		WHERE employees.activity_tracker_flg_purchasing_pallet = 1 and employees.status = 'Active' and employees.employeeID not in ($emp_id_list_str) 
		group by employees.loopID ORDER BY name";
        } else {
            $sql = "SELECT 0 as cnt, 0 as sumpo_total , employees.loopID as id, 
		employees.activity_tracker_flg_purchasing_pallet, employees.status, employees.name, employees.initials, employees.employeeID as b2b_id
		FROM employees 
		WHERE employees.activity_tracker_flg_purchasing_pallet = 1 and employees.status = 'Active' 
		group by employees.loopID ORDER BY name";
        }


        //(employee_all_activity_details.employee_id = -2)
        //echo $sql . "<br>";
        db_b2b();
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {

            $lead_tmp = 0;
            $contact_act_tmp = 0;
            $contact_act_ph1 = 0;
            $demand_entry_tmp = 0;
            $quote_req_cnt = 0;
            $purchase_orders = 0;
            $monthly_deal_qtd = 0;
            $first_time_rec = 0;
            $po_total = 0;
            db_b2b();

            $result_crm = db_query("Select sum(leads) as leads, 
		sum(email_sent) as email_sent, sum(calls_made) as calls_made, sum(quote_requests) as quote_requests, 
		sum(first_time_customer) as first_time_customer, sum(sales_po_amunt) as sales_po_amunt, sum(first_time_supplier) as first_time_supplier, 
		sum(purchase_orders) as purchase_orders,  sum(po_total) as po_total
		from employee_all_activity_details where entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and employee_id = '" . $rowemp["id"] . "'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $lead_tmp = $rowemp_crm["leads"];
                $contact_act_tmp = $rowemp_crm["email_sent"];
                $contact_act_ph1 = $rowemp_crm["calls_made"];
                $demand_entry_tmp = $rowemp_crm["demand_entries"];
                $quote_req_cnt = $rowemp_crm["quote_requests"];
                //$purchase_orders = $rowemp_crm["purchase_orders"] ;

                //$first_time_rec = $rowemp_crm["first_time_supplier"] ;
                //$po_total = $rowemp_crm["po_total"] ;
            }
            $po_total = $rowemp["sumpo_total"];
            $purchase_orders = $rowemp["cnt"];

            db();
            $result_crm = db_query("SELECT 1 as cnt, STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') as sort_date FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
		inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
		WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
		and loop_boxes.owner = '" . $rowemp["id"] . "' AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' group by loop_boxes_sort.trans_rec_id");
            while ($rowemp_crm = array_shift($result_crm)) {
                $first_time_rec = $first_time_rec + $rowemp_crm["cnt"];
            }

            /*$dt_view_qry = "Select count(*) as cnt, sum(quote_total) as quote_total from quote where quoteType = 'PO' 
		and quoteDate between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and rep = '" . $rowemp["b2b_id"] . "'";
		$result_crm = db_query($dt_view_qry);
		while ($rowemp_crm = array_shift($result_crm)) {
			$po_total = $rowemp_crm["quote_total"];
			$purchase_orders = $rowemp_crm["cnt"];
		}		*/

            if ($rowemp["id"] == -2) {
                $emp_name = "Other";
                $emp_initials = "Other";
                $emp_b2b_id = -2;
                $lead_tmp = "";
                $contact_act_tmp = "";
                $contact_act_ph1 = "";
            } else {
                $emp_name = $rowemp["name"];
                $emp_initials = $rowemp["initials"];
                $emp_b2b_id = $rowemp["b2b_id"];
            }

            db();
            $tot_lead_tmp = $tot_lead_tmp + $lead_tmp;
            $tot_first_time_rec = $tot_first_time_rec + $first_time_rec;
            $tot_quote_req_cnt = $tot_quote_req_cnt + $quote_req_cnt;
            $tot_demand_entry_tmp = $tot_demand_entry_tmp + $demand_entry_tmp;
            $tot_quota_contact = $tot_quota_contact + $contact_act_tmp;
            $tot_quota_contact_ph = $tot_quota_contact_ph + $contact_act_ph1;
            $tot_quota_quotes = $tot_quota_quotes + $purchase_orders;
            $tot_quote_amount = $tot_quote_amount + $po_total;

            $lisoftrans = "";
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
            //changed form 20 to 15
            if (isset($week_val) == 1) {
                if ($contact_act_tmp >= 15) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 15) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 15) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 15) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 2) {
                if ($contact_act_tmp >= 30) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 30) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 30) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 30) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 3) {
                if ($contact_act_tmp >= 45) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 45) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 45) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 45) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }
            if (isset($week_val) == 4) {
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
            if (isset($week_val) >= 5) {
                if ($contact_act_tmp >= 75) {
                    $email_color_code = "<font color=green>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_tmp < 75) {
                    $email_color_code = "<font color=red>";
                    $email_color_code2 = "</font>";
                }
                if ($contact_act_ph1 >= 75) {
                    $contact_color_code = "<font color=green>";
                    $contact_color_code2 = "</font>";
                }
                if ($contact_act_ph1 < 75) {
                    $contact_color_code = "<font color=red>";
                    $contact_color_code2 = "</font>";
                }
            }

            if ($rowemp["id"] == -2) {
                $emp_name = "Other";
                $emp_initials = "Other";
                $emp_b2b_id = -2;
            } else {
                $emp_name = $rowemp["name"];
                $emp_initials = $rowemp["initials"];
                $emp_b2b_id = $rowemp["b2b_id"];
            }

            echo "<tr><td bgColor='$tbl_color' align ='left'>" . $emp_name . "</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_purchasing_show_list.php?showlead=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $rowemp["b2b_id"] . "'>";
            echo number_format($lead_tmp, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo $email_color_code . " " . number_format($contact_act_tmp, 0) . " " . $email_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=poenter&phone=y&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "'>";
            echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y&b2bempid=" . $emp_b2b_id . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
            echo number_format($purchase_orders, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_purchasing_show_list.php?showfirsttimerec=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>";
            echo number_format($first_time_rec, 0);
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_purchasing_show_list.php?quote_amount=yes&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $emp_initials . "&b2bempid=" . $emp_b2b_id . "'>$";
            echo number_format($po_total, 0);
            echo "</a></td>";
            echo "</tr>";
        }

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

        echo "</tbody>";
        echo "</table>";
    }

    //For new 3 tables - Avg values Activity Tracker - Daily Averages
    // function activity_tracker_daily_averages($start_Dt, $end_Dt, $headtxt, $tilltoday, $currentyr, $tbl_head_color, $tbl_color, $po_flg, $unqid, $ttylyesno)

    function activity_tracker_daily_averages(
        string $start_Dt,
        string $end_Dt,
        string $headtxt,
        string $tilltoday,
        string|int $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        string $po_flg,
        string $unqid,
        string $ttylyesno
    ): void {

        db();

        $lisoftrans_detail_list = "<span style='width:1300px;'><table cellSpacing='1' cellPadding='1' border='0'><tr style='height:50px;'>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Transaction ID</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='200px'><u>Company</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>PO Date</u></th>";
        $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Employee</u></th>";
        $lisoftrans_detail_list .= "<th width='100px' bgColor='$tbl_head_color' align=center><u>PO Amount</u></th>";
        $lisoftrans_detail_list .= "</tr>";

        $div_id_emp_list = "999111";

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='1000' id='table9' class='tablesorter' >";
        echo "<thead>";

        echo "	<tr style='height:50px;'>";
        echo "		<th align='left' bgColor='$tbl_head_color' width='200px'>&nbsp;</th>";
        echo "		<th bgColor='$tbl_head_color' colspan='3' align='center'>Last Week</th>";
        echo "		<th bgColor='$tbl_head_color' colspan='3' align='center'>Trailing 30 Days</th>";
        echo "		<th bgColor='$tbl_head_color' colspan='3' align='center'>Year to Date (YTD)</th>";
        echo "	</tr>";
        echo "	<tr style='height:50px;'>";
        echo "		<th align='left' bgColor='$tbl_head_color' width='200px'><u>Employee</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Avg Daily Touches</u>
		<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
		<span class='tooltiptext'>Green is >= 40 Avg Touches/Day (Calls+Emails) Touches Else Red</span></div>
	</th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Avg Daily Quotes
		<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
		<span class='tooltiptext'>Green is >= 5 Avg Quotes/Day Else Red</span></div>
	</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Avg Daily Deals</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Avg Daily Touches
		<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
		<span class='tooltiptext'>Green is >= 40 Avg Touches/Day (Calls+Emails) Touches Else Red</span></div>
	</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Avg Daily Quotes</u>
		<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
		<span class='tooltiptext'>Green is >= 5 Avg Quotes/Day Else Red</span></div>
	</th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Avg Daily Deals</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Avg Daily Touches</u>
		<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
		<span class='tooltiptext'>Green is >= 40 Avg Touches/Day (Calls+Emails) Touches Else Red</span></div>
	</th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Avg Daily Quotes</u>
		<div class='tooltip'><i class='fa fa-info-circle' aria-hidden='true'></i>
		<span class='tooltiptext'>Green is >= 5 Avg Quotes/Day Else Red</span></div>
	</th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Avg Daily Deals</u></th>";
        echo "	</tr>";
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

        $dt_year_value = date('Y', strtotime($start_Dt));
        $dt_month_value = date('m', strtotime($start_Dt));
        $current_year_value = date('Y');

        $tot_avg_daily_touch1 = 0;
        $tot_avg_daily_touch2 = 0;
        $tot_avg_daily_touch3 = 0;
        $tot_avg_daily_quotes1 = 0;
        $tot_avg_daily_quotes2 = 0;
        $tot_avg_daily_quotes3 = 0;
        $tot_avg_daily_deal1 = 0;
        $tot_avg_daily_dea2 = 0;
        $tot_avg_daily_dea3 = 0;

        $days_this_year = floor((strtotime(DATE("Y-m-d")) - strtotime(DATE("Y-01-01"))) / (60 * 60 * 24));
        $sql = "SELECT * FROM loop_employees WHERE activity_tracker_flg_pallet = 1 and status = 'Active' ORDER BY quota DESC";
        //quota > 0 and 
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {
            $name = $rowemp["name"];

            //To calculate the contacts this week
            $time_rep = strtotime(Date('Y-m-d'));

            if (date('l', $time_rep) != "Sunday") {
                $st_friday_rep = strtotime('last sunday', $time_rep);
            } else {
                $st_friday_rep = $time_rep;
            }

            $st_friday_last = strtotime('-7 days', $st_friday_rep);
            $st_thursday_last = strtotime('+6 days', $st_friday_last);

            $st_friday_last = Date('Y-m-d', $st_friday_last);
            $st_thursday_last = Date('Y-m-d', $st_thursday_last);

            $start_Dt = $st_friday_last;
            $end_Dt = $st_thursday_last;



            $contact_act_ph1 = 0;
            $contact_act_tmp = 0;
            $eml_list = "";
            $summtd_SUMPO = 0;
            $summtd_dealcnt = 0;
            db_b2b();
            $result_crm = db_query("Select sum(daily_touches) as cnt, sum(daily_quotes) as daily_quotes, sum(daily_deals) as daily_deals from employee_all_activity_details where entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and employee_id = '" . $rowemp["id"] . "'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $contact_act_ph1 = $rowemp_crm["cnt"];
                $quotes_sent = $rowemp_crm["daily_quotes"];
                $summtd_dealcnt = $rowemp_crm["daily_deals"];
            }
            if ($contact_act_ph1 > 0) {
                $contact_act_ph1 = $contact_act_ph1 / 5;
            }

            $tot_quota_contact = isset($tot_quota_contact) + $contact_act_tmp;
            $tot_avg_daily_touch1 = $tot_avg_daily_touch1 + $contact_act_ph1;



            if (isset($quotes_sent) > 0) {
                $quotes_sent = $quotes_sent / 5;
            }

            $tot_avg_daily_quotes1 = $tot_avg_daily_quotes1 + $quotes_sent;


            if ($summtd_dealcnt > 0) {
                $summtd_dealcnt = $summtd_dealcnt / 5;
            }
            $tot_avg_daily_deal1 = $tot_avg_daily_deal1 + $summtd_dealcnt;

            //deal cnt			

            //color codes
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
            if ($quotes_sent >= 5) {
                $email_color_code = "<font color=green>";
                $email_color_code2 = "</font>";
            } else {
                $email_color_code = "<font color=red>";
                $email_color_code2 = "</font>";
            }
            //changed >= 40 to >= 30
            if ($contact_act_ph1 >= 30) {
                $contact_color_code = "<font color=green>";
                $contact_color_code2 = "</font>";
            } else {
                $contact_color_code = "<font color=red>";
                $contact_color_code2 = "</font>";
            }

            db();

            echo "<tr><td bgColor='$tbl_color' width='260px' align ='left'>" . $name . "</td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=activity_tracker_daily_averages_daily_touch&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $rowemp["initials"] . "'>";
            echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y&b2bempid=" . $rowemp["b2b_id"] . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
            echo $email_color_code . " " . number_format($quotes_sent, 0) . " " . $email_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo number_format($summtd_dealcnt, 0);
            echo "</td>";

            //To calculate For Trailing 30 Days
            $time_rep = strtotime(Date('Y-m-d'));

            $st_friday_last = strtotime('-30 days', $time_rep);
            $st_thursday_last = strtotime('-1 day', $time_rep);

            $st_friday_last = Date('Y-m-d', $st_friday_last);
            $st_thursday_last = Date('Y-m-d', $st_thursday_last);

            $start_Dt = $st_friday_last;
            $end_Dt = $st_thursday_last;

            $no_of_weekdays = 0;
            $startTimestamp = strtotime($start_Dt);
            $endTimestamp = strtotime($end_Dt);
            for ($i = $startTimestamp; $i <= $endTimestamp; $i = $i + (60 * 60 * 24)) {
                if (date("N", $i) <= 5) $no_of_weekdays = $no_of_weekdays + 1;
            }



            $contact_act_ph1 = 0;
            $contact_act_tmp = 0;
            $eml_list = "";
            $summtd_SUMPO = 0;
            $summtd_dealcnt = 0;
            db_b2b();
            $result_crm = db_query("Select sum(daily_touches) as cnt, sum(daily_quotes) as daily_quotes, sum(daily_deals) as daily_deals from employee_all_activity_details where entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and employee_id = '" . $rowemp["id"] . "'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $contact_act_ph1 = $rowemp_crm["cnt"];
                $quotes_sent = $rowemp_crm["daily_quotes"];
                $summtd_dealcnt = $rowemp_crm["daily_deals"];
            }
            if ($contact_act_ph1 > 0) {
                $contact_act_ph1 = $contact_act_ph1 / $no_of_weekdays;
            }

            $tot_quota_contact = $tot_quota_contact + $contact_act_tmp;
            $tot_avg_daily_touch2 = $tot_avg_daily_touch2 + $contact_act_ph1;



            if ($quotes_sent > 0) {
                $quotes_sent = $quotes_sent / $no_of_weekdays;
            }

            $tot_avg_daily_quotes2 = $tot_avg_daily_quotes2 + $quotes_sent;


            if ($summtd_dealcnt > 0) {
                $summtd_dealcnt = $summtd_dealcnt / $no_of_weekdays;
            }
            $tot_avg_daily_dea2 = $tot_avg_daily_dea2 + $summtd_dealcnt;
            //deal cnt			

            //color codes
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
            if ($quotes_sent >= 5) {
                $email_color_code = "<font color=green>";
                $email_color_code2 = "</font>";
            } else {
                $email_color_code = "<font color=red>";
                $email_color_code2 = "</font>";
            }
            if ($contact_act_ph1 >= 30) {
                $contact_color_code = "<font color=green>";
                $contact_color_code2 = "</font>";
            } else {
                $contact_color_code = "<font color=red>";
                $contact_color_code2 = "</font>";
            }

            db();

            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=activity_tracker_daily_averages_daily_touch&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $rowemp["initials"] . "'>";
            echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y&b2bempid=" . $rowemp["b2b_id"] . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
            echo $email_color_code . " " . number_format($quotes_sent, 0) . " " . $email_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo number_format($summtd_dealcnt, 0);
            echo "</td>";
            //For Trailing 30 Days

            //To calculate Year to Date (YTD)
            $time_rep = strtotime(Date('Y-m-d'));

            $st_friday_last = strtotime(Date('Y-1-1'));
            $st_thursday_last = $time_rep;

            $st_friday_last = Date('Y-m-d', $st_friday_last);
            $st_thursday_last = Date('Y-m-d', $st_thursday_last);

            $start_Dt = $st_friday_last;
            $end_Dt = $st_thursday_last;

            $no_of_weekdays = 0;
            $startTimestamp = strtotime($start_Dt);
            $endTimestamp = strtotime($end_Dt);
            for ($i = $startTimestamp; $i <= $endTimestamp; $i = $i + (60 * 60 * 24)) {
                if (date("N", $i) <= 5) $no_of_weekdays = $no_of_weekdays + 1;
            }



            $contact_act_ph1 = 0;
            $contact_act_tmp = 0;
            $eml_list = "";
            $summtd_SUMPO = 0;
            $summtd_dealcnt = 0;
            db_b2b();
            $result_crm = db_query("Select sum(daily_touches) as cnt, sum(daily_quotes) as daily_quotes, sum(daily_deals) as daily_deals from employee_all_activity_details where entry_date BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . " 23:59:59' and employee_id = '" . $rowemp["id"] . "'");
            while ($rowemp_crm = array_shift($result_crm)) {
                $contact_act_ph1 = $rowemp_crm["cnt"];
                $quotes_sent = $rowemp_crm["daily_quotes"];
                $summtd_dealcnt = $rowemp_crm["daily_deals"];
            }

            if ($contact_act_ph1 > 0) {
                $contact_act_ph1 = $contact_act_ph1 / $no_of_weekdays;
            }

            $tot_quota_contact = $tot_quota_contact + $contact_act_tmp;
            $tot_avg_daily_touch3 = $tot_avg_daily_touch3 + $contact_act_ph1;



            if ($quotes_sent > 0) {
                $quotes_sent = $quotes_sent / $no_of_weekdays;
            }

            $tot_avg_daily_quotes3 = $tot_avg_daily_quotes3 + $quotes_sent;


            if ($summtd_dealcnt > 0) {
                $summtd_dealcnt = $summtd_dealcnt / $no_of_weekdays;
            }
            $tot_avg_daily_dea3 = $tot_avg_daily_dea3 + $summtd_dealcnt;
            //deal cnt			

            //color codes
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
            if ($quotes_sent >= 5) {
                $email_color_code = "<font color=green>";
                $email_color_code2 = "</font>";
            } else {
                $email_color_code = "<font color=red>";
                $email_color_code2 = "</font>";
            }
            if ($contact_act_ph1 >= 30) {
                $contact_color_code = "<font color=green>";
                $contact_color_code2 = "</font>";
            } else {
                $contact_color_code = "<font color=red>";
                $contact_color_code2 = "</font>";
            }

            db();

            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=activity_tracker_daily_averages_daily_touch&date_from_val=$start_Dt&date_to_val=$end_Dt&crmemp=" . $rowemp["initials"] . "'>";
            echo $contact_color_code . " " . number_format($contact_act_ph1, 0) . " " . $contact_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "<a target='_blank' href='report_show_open_quotes.php?poenter=y&b2bempid=" . $rowemp["b2b_id"] . "&date_from_val=$start_Dt&date_to_val=$end_Dt'>";
            echo $email_color_code . " " . number_format($quotes_sent, 0) . " " . $email_color_code2;
            echo "</a></td>";
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo number_format($summtd_dealcnt, 0);
            echo "</td>";
            echo "</tr>";
            //For Year to Date (YTD)

            $summtd_dealcnt = number_format($summtd_dealcnt, 0);
        }

        echo "<tr><td bgColor='$tbl_color' align ='right'><strong>Total</strong></td>";
        echo "<td bgColor='$tbl_color' align = 'right'><strong>";
        echo number_format($tot_avg_daily_touch1, 0);
        echo "</strong></td>";
        echo "<td bgColor='$tbl_color' align = 'right'><strong>";
        echo number_format($tot_avg_daily_quotes1, 0);
        echo "</strong></td>";
        echo "<td bgColor='$tbl_color' align = 'right'><strong>";
        echo number_format($tot_avg_daily_deal1, 0);
        echo "</strong></td>";
        echo "<td bgColor='$tbl_color' align = 'right'><strong>";
        echo number_format($tot_avg_daily_touch2, 0);
        echo "</strong></td>";
        echo "<td bgColor='$tbl_color' align = 'right'><strong>";
        echo number_format($tot_avg_daily_quotes2, 0);
        echo "</strong></td>";
        echo "<td bgColor='$tbl_color' align = 'right'><strong>";
        echo number_format($tot_avg_daily_dea2, 0);
        echo "</strong></td>";
        echo "<td bgColor='$tbl_color' align = 'right'><strong>";
        echo number_format($tot_avg_daily_touch3, 0);
        echo "</strong></td>";
        echo "<td bgColor='$tbl_color' align = 'right'><strong>";
        echo number_format($tot_avg_daily_quotes3, 0);
        echo "</strong></td>";
        echo "<td bgColor='$tbl_color' align = 'right'><strong>";
        echo number_format($tot_avg_daily_dea3, 0);
        echo "</strong></td>";
        echo "</td></tr>";
        echo "</strong>";
        echo "</table><br>";
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
        string $tilltoday,
        string $currentyr,
        string $tbl_head_color,
        string $tbl_color,
        string $po_flg,
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
        echo "		<th align='left' bgColor='$tbl_head_color' width='260px'><u>Employee</u></th>";
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
            $newsel = "Select quota_month, quota , deal_quota, quota_year  from employee_quota_overall_pallet_sale where quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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


    function getCurrentQuarter(int|false $timestamp = false): int
    {
        if (!$timestamp) $timestamp = time();
        $day = date('n', $timestamp);
        $quarter = (int)ceil($day / 3);
        return $quarter;
    }

    function getPreviousQuarter(int|false $timestamp = false): int
    {
        if (!$timestamp) $timestamp = time();
        //$quarter = getCurrentQuarter($timestamp) - 1;
        $quarter = getCurrentQuarter($timestamp);
        if ($quarter < 0) {
            $quarter = 4;
        }
        return $quarter;
    }

    if (isset($called_from_activity) == "") {
    ?>
    <br />
    <table border="0">
        <tr>
            <td width="700px" align="center" style="font-size:24pt;">
                <div class="dashboard_heading" style="float: left;">
                    <div style="float: left;">Pallet Leaderboard Report</div>
                    <div style="float: left;">&nbsp;
                        <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                            <span class="tooltiptext">This report shows the user UCB gross profit data for all
                                departments, itemized by employee and time frame, including a date range selector a the
                                top. This report also has child reports to be able to see all transactions and what step
                                in the process they are, the number of new deals closed by each rep, and an account
                                ownership breakdown.</span>
                        </div>
                    </div>
                    <div style="height: 13px;">&nbsp;</div>
                </div>
            </td>
            <td width="200px" align="right"><img src="images/image001.jpg" width="70" height="70" /></td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="80%">
                <!-- Load the page by default with old logic - do not apply date range-->
                <table border="0">
                    <tr>
                        <td colspan="5" align="left">
                            <form method="get" name="rpt_leaderboard" action="report_daily_chart_mgmt_pallet_org.php">
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
                                        &nbsp;
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank"
                                            href='report_mgmt_closed_deal_pipeline_summary.php?pallet_flg=yes'
                                            target="_blank">Closed Deal pipeline Summary</a>
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        &nbsp;
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank" href='report_mgmt_new_deal_spins.php?pallet_flg=yes'
                                            target="_blank">New Deal Spins</a>
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        &nbsp;
                                    </td>
                                    <td align="left" bgColor='#e4e4e4'
                                        style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
                                        <a target="_blank" href='report_mgmt_activity_tracking.php?pallet_flg=yes'
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
                        } else {
                            $date_from_val = "";
                        }

                        if ($in_dt_range == "no") {
                        ?>
                    <tr valign="top">
                        <td width="520" valign="top" id="div_who_closed_deal">
                            <table width="520" cellSpacing="1" cellPadding="1" border="0">
                                <tr>
                                    <td class="txtstyle_color" align=center><strong>WHO CLOSED A DEAL TODAY?</strong>
                                    </td>
                                </tr>
                            </table>
                            <table width="520" cellSpacing="1" cellPadding="1" border="0" id="table8"
                                class="tablesorter">
                                <thead>
                                    <tr>
                                        <th bgColor='#E4EAEB' align=center width="300"><u>Company</u></th>
                                        <th bgColor='#E4EAEB' align="center"><u>Planned Delivery Date</u></th>
                                        <th bgColor='#E4EAEB' align=center><u>Employee</u></th>
                                        <th bgColor='#E4EAEB' align=center><u>PO Amount</u></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                            //$sql = "SELECT *, loop_warehouse.b2bid, loop_warehouse.warehouse_name, loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1 and (po_date <= DATE_FORMAT(curdate() , '%m/%d/%Y') and po_date >= '05/11/2016')";  
                                            if ($in_dt_range != "yes") {
                                                $sql = "SELECT *, loop_warehouse.b2bid, loop_warehouse.warehouse_name, loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1  and Leaderboard = 'PALLETS' and po_date = DATE_FORMAT(curdate() , '%m/%d/%Y')";
                                            } else {
                                                $sql = "SELECT *, loop_warehouse.b2bid, loop_warehouse.warehouse_name, loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1  and Leaderboard = 'PALLETS' and po_date = DATE_FORMAT('$date_from_val' , '%m/%d/%Y')";
                                            }
                                            //echo $sql . "<br>";
                                            $po_poorderamount_tot = 0;
                                            $result = db_query($sql);
                                            while ($row = array_shift($result)) {
                                                $nickname = "";
                                                if ($row["b2bid"] > 0) {
                                                    db_b2b();
                                                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $row["b2bid"];
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
                                                    $nickname = $row["warehouse_name"];
                                                }

                                                $po_delivery_dt = "";
                                                if ($row["po_delivery_dt"] != "") {
                                                    $po_delivery_dt = date("m/d/Y", strtotime($row["po_delivery_dt"]));
                                                }

                                                if ($row["po_poorderamount"] > 0) {
                                                    echo "<tr><td bgColor='#E4EAEB' align='left' width='240px'><a target='_blank' href='http://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["I"] . "&display=buyer_view'>" . $nickname . "</a></td>
						<td width='20px' align='left' bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td width='20px' align='left'  bgColor='#E4EAEB'>" . $row["po_employee"] . "</td><td width='40px' align='right' bgColor='#E4EAEB'>$" . number_format($row["po_poorderamount"], 2) . "</td></tr>";
                                                }
                                                $po_poorderamount_tot = $po_poorderamount_tot + $row["po_poorderamount"];
                                            }
                                            echo "</tbody>";
                                            if ($po_poorderamount_tot > 0) {
                                                echo "<tr><td bgColor='#E4EAEB'>&nbsp;</td><td bgColor='#E4EAEB'>&nbsp;</td><td bgColor='#E4EAEB'><b>Total</b></td><td align='right' bgColor='#E4EAEB'><b>$" . number_format($po_poorderamount_tot, 2) . "</b></td></tr>";
                                            }

                                            ?>
                            </table>

                            <script>
                            if (window.XMLHttpRequest) {
                                xmlhttp = new XMLHttpRequest();
                            } else {
                                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                            xmlhttp.onreadystatechange = function() {
                                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                    document.getElementById("div_who_closed_deal").innerHTML = xmlhttp.responseText;
                                }
                            }

                            xmlhttp.open("GET", "report_daily_chart_mgmt_who_close_deal_today_pallet.php", true);
                            xmlhttp.send();
                            </script>
                        </td>
                        <td width="50px">&nbsp;</td>

                        <td width="520" valign="top">
                            <table width="520" cellSpacing="1" cellPadding="1" border="0">
                                <tr>
                                    <td class="txtstyle_color" align=center><strong>WHO CLOSED A DEAL YESTERDAY</strong>
                                    </td>
                                </tr>
                            </table>
                            <table width="520" cellSpacing="1" cellPadding="1" border="0" id="table8"
                                class="tablesorter">
                                <thead>
                                    <tr>
                                        <th bgColor='#E4EAEB' align=center width="300"><u>Company</u></th>
                                        <th bgColor='#E4EAEB' align="center"><u>Planned Delivery Date</u></th>
                                        <th bgColor='#E4EAEB' align=center><u>Employee</u></th>
                                        <th bgColor='#E4EAEB' align=center><u>PO Amount</u></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                            if ($in_dt_range != "yes") {
                                                if (date('w') == 1) {
                                                    $yesterday =  date('Y-m-d', strtotime("-3 day"));
                                                }
                                                if (date('w') == 0 || date('w') == 2 || date('w') == 3 || date('w') == 4 || date('w') == 5 || date('w') == 6) {
                                                    $yesterday =  date('Y-m-d', strtotime("-1 day"));
                                                }
                                            } else {
                                                if (date('w', strtotime($date_from_val)) == 1) {
                                                    $yesterday =  date('Y-m-d', strtotime($date_from_val . " -3 day"));
                                                }
                                                if (date('w', strtotime($date_from_val)) == 0 || date('w', strtotime($date_from_val)) == 2 || date('w', strtotime($date_from_val)) == 3 || date('w', strtotime($date_from_val)) == 4 || date('w', strtotime($date_from_val)) == 5 || date('w', strtotime($date_from_val)) == 6) {
                                                    $yesterday =  date('Y-m-d', strtotime($date_from_val . " -1 day"));
                                                }
                                            }

                                            $sql = "SELECT *, loop_warehouse.b2bid, loop_warehouse.warehouse_name, loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1  and Leaderboard = 'PALLETS' and po_date = DATE_FORMAT('$yesterday' , '%m/%d/%Y')";
                                            //echo $sql . "<br>";
                                            $po_poorderamount_tot = 0;
                                            $result = db_query($sql);
                                            while ($row = array_shift($result)) {
                                                $nickname = "";
                                                if ($row["b2bid"] > 0) {
                                                    db_b2b();
                                                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $row["b2bid"];
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
                                                    $nickname = $row["warehouse_name"];
                                                }

                                                $po_delivery_dt = "";
                                                if ($row["po_delivery_dt"] != "") {
                                                    $po_delivery_dt = date("m/d/Y", strtotime($row["po_delivery_dt"]));
                                                }

                                                if ($row["po_poorderamount"] > 0) {
                                                    echo "<tr><td bgColor='#E4EAEB' align='left' width='240px'><a href='http://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["I"] . "&display=buyer_view'>" . $nickname . "</a></td>
						<td width='20px' align='left' bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td width='20px' align='left'  bgColor='#E4EAEB'>" . $row["po_employee"] . "</td><td width='40px' align='right' bgColor='#E4EAEB'>$" . number_format($row["po_poorderamount"], 2) . "</td></tr>";
                                                }
                                                $po_poorderamount_tot = $po_poorderamount_tot + $row["po_poorderamount"];
                                            }
                                            echo "</tbody>";
                                            if ($po_poorderamount_tot > 0) {
                                                echo "<tr><td bgColor='#E4EAEB'>&nbsp;</td><td bgColor='#E4EAEB'>&nbsp;</td><td bgColor='#E4EAEB'><b>Total</b></td><td align='right' bgColor='#E4EAEB'><b>$" . number_format($po_poorderamount_tot, 2) . "</b></td></tr>";
                                            }

                                            ?>
                            </table>
                        </td>

                    </tr>

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

                                        $st_date = Date('Y-m-d', $st_friday);
                                        $end_date = Date('Y-m-d', $st_thursday);

                                        /*$last_year = date("Y")-1;
					$start_Dt_ttly = Date($last_year. '-m-d', $st_friday);
					$end_Dt_ttly = Date($last_year . '-m-d', $st_thursday);*/

                                        $st_date_last_y = Date($last_yr . '-m-d', $st_friday);
                                        $end_date_last_y = Date($last_yr . '-m-d', $st_thursday);

                                        $st_friday_last = Date('Y-m-d', $st_friday_last);
                                        $st_thursday_last = Date('Y-m-d', $st_thursday_last);

                                        $unqid = 1;
                                        ?>

                                <!-- Activity Tracker -->
                                <tr>
                                    <td colspan="3" align="center">
                                        <?php
                                                activity_tracker_daily_averages($st_date, $end_date, "THIS WEEK", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'yes', $unqid, "ttylno");
                                                ?>



                                        <div id="table_b2b_activity_tracker_daily_avg" style="display:none;">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" align="center">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td colspan="3" align="center">

                                        <table>
                                            <tr>
                                                <td>
                                                    <?php
                                                            activity_tracker($st_date, $end_date, "THIS WEEK", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'yes', $unqid, "ttylno", "yes");
                                                            ?>
                                                </td>
                                                <td width="50">&nbsp;</td>
                                                <td>
                                                    <?php
                                                            $unqid = $unqid + 1;
                                                            activity_tracker($st_friday_last, $st_thursday_last, "LAST WEEK", 'N', 'N', '#ABC5DF', '#E4EAEB', 'yes', $unqid, "ttylno", "yes");
                                                            ?>
                                                </td>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" align="center">&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="3" align="center">
                            <span align="center" style="background:#ABC5DF">
                                <strong>Activity Tracker&nbsp;<a href='javascript:void(0);'
                                        onclick="expand_b2b_activity_tracker('<?php echo $st_date; ?>', '<?php echo $end_date ?>', 'THIS WEEK', 'Y', 'Y','#ABC5DF', '#E4EAEB', 'yes', <?php echo $unqid; ?>, 'ttylno', '<?php echo $in_dt_range; ?>')"
                                        )>Expand/</a>
                                    <a href='javascript:void(0);'
                                        onclick='collapse_b2b_activity_tracker();'>Collapse</a>
                                </strong>
                            </span>

                            <div id="table_b2b_activity_tracker" style="display:none;">
                            </div>

                        </td>
                    </tr>



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

                                        $st_date = Date('Y-m-01', strtotime($date_from_val));
                                        $end_date = Date('Y-m-t', strtotime($date_from_val));

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
                                    leadertbl($st_date, $end_date, "THIS MONTH", 'Y', 'Y', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes", $in_dt_range);

                                    leadertbl_GMI($st_date, $end_date, "THIS MONTH", 'Y', 'Y', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes", $in_dt_range);

                                    ?>
                        </td>
                        <td width="50">&nbsp;</td>
                        <td>
                            <?php

                                    $last_month_st_dt = $st_lastmonth;
                                    $last_month_end_dt = $end_lastmonth;
                                    $unqid = $unqid + 1;
                                    leadertbl($st_lastmonth, $end_lastmonth, "LAST MONTH", 'N', 'N', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes", $in_dt_range);

                                    leadertbl_GMI($st_lastmonth, $end_lastmonth, "LAST MONTH", 'N', 'N', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes", $in_dt_range);
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
                                        $quarter = getCurrentQuarter($date_from_val);
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
                                        $quarter = getPreviousQuarter($date_from_val);
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

                                    $st_lastqtr_lastyr = date($last_yr . '-m-01', strtotime($st_date));
                                    $end_lastqtr_lastyr = date($last_yr . '-m-t', strtotime($end_date));

                                    $unqid = $unqid + 1;

                                    $this_qtr_st_dt = $st_date;
                                    $this_qtr_end_dt = $end_date;
                                    ?>

                            <?php leadertbl($st_date, $end_date, "THIS QUARTER", 'Y', 'Y', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes", $in_dt_range);

                                    leadertbl_GMI($st_date, $end_date, "THIS QUARTER", 'Y', 'Y', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes", $in_dt_range);
                                    ?>
                        </td>
                        <td width="50">&nbsp;</td>
                        <td>
                            <?php
                                    $unqid = $unqid + 1;
                                    $last_qtr_st_dt = $st_lastqtr;
                                    $last_qtr_end_dt = $end_lastqtr;
                                    leadertbl($st_lastqtr, $end_lastqtr, "LAST QUARTER", 'N', 'N', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes", $in_dt_range);

                                    leadertbl_GMI($st_lastqtr, $end_lastqtr, "LAST QUARTER", 'N', 'N', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes", $in_dt_range);
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
                                        $st_date = Date('Y-01-01', strtotime($date_from_val));
                                        $end_date = Date('Y-12-31', strtotime($date_from_val));

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
                                    leadertbl($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylno", $in_dt_range);

                                    leadertbl_GMI($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylno", $in_dt_range);
                                    ?>
                        </td>
                        <td width="50">&nbsp;</td>
                        <td>
                            <?php
                                    $last_yr_st_dt = $st_lastyr;
                                    $last_yr_end_dt = $end_lastyr;
                                    $unqid = $unqid + 1;
                                    leadertbl($st_lastyr, $end_lastyr, "LAST YEAR", 'N', 'N', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylno", $in_dt_range);

                                    leadertbl_GMI($st_lastyr, $end_lastyr, "LAST YEAR", 'N', 'N', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylno", $in_dt_range);
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
                                        $sqlT = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp = " . $date_from_val;
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
                                        $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate = '" . $date_from_val . "' and fromadd = '" . $rowemp["email"] . "'");
                                    }
                                    while ($rowemp_crm = array_shift($result_crm)) {
                                        $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                                    }

                                    echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                    echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=T&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_tmp . "</a></td>";

                                    $contact_act_t += $contact_act_tmp;
                                    $contact_act_ph_t += $contact_act_ph1;

                                    if ($in_dt_range != "yes") {
                                        $sqlY = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND";
                                    } else {
                                        $sqlY = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));
                                    }
                                    //$result_crm = db_query($sqlY );
                                    //echo "<a target='_blank' href='report_daily_chart.php?CRMday=Y&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . tep_db_num_rows($resultY) . "</a>";
                                    //$contact_act_y += tep_db_num_rows($resultY);
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
                                        $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd = '" . $rowemp["email"] . "'");
                                    }
                                    while ($rowemp_crm = array_shift($result_crm)) {
                                        $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                                    }

                                    echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                    echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=Y&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_tmp . "</a></td>";
                                    $contact_act_y += $contact_act_tmp;
                                    $contact_act_ph_y += $contact_act_ph2;

                                    if ($in_dt_range != "yes") {
                                        $sql7 = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE()";
                                    } else {
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
                                        $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd = '" . $rowemp["email"] . "'");
                                    }
                                    while ($rowemp_crm = array_shift($result_crm)) {
                                        $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                                    }

                                    echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                    echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=7&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_tmp . "</a></td>";
                                    $contact_act_7 += $contact_act_tmp;
                                    $contact_act_ph_7 += $contact_act_ph3;

                                    if ($in_dt_range != "yes") {
                                        $sql30 = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE()";
                                    } else {
                                        $sql30 = "SELECT ID, type, EmailID FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND  timestamp BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));
                                    }
                                    //$result_crm = db_query($sql30 );
                                    //echo "<a target='_blank' href='report_daily_chart.php?CRMday=30&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . tep_db_num_rows($result30) . "</a>";
                                    //$contact_act_30 += tep_db_num_rows($result30);

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
                                        $result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . " and fromadd = '" . $rowemp["email"] . "'");
                                    }
                                    while ($rowemp_crm = array_shift($result_crm)) {
                                        $contact_act_tmp = $contact_act_tmp + $rowemp_crm["cnt"];
                                    }

                                    echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                    echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=30&in_dt_range=$in_dt_range&date_from_val=$date_from_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_tmp . "</a>";
                                    echo "</td></tr>";
                                    $contact_act_30 += $contact_act_tmp;
                                    $contact_act_ph_30 += $contact_act_ph4;

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

                                    //$sql = "SELECT DISTINCT(employees.name), rep FROM quote INNER JOIN employees ON employees.employeeID = quote.rep WHERE rep NOT LIKE '' AND quoteDate BETWEEN SYSDATE() - INTERVAL 30 DAY AND SYSDATE() ORDER BY employees.name ASC";
                                    //$result = db_query($sql );
                                    //while ($rowemp = array_shift($result)) {

                                    echo "<tr><td bgColor='#E4EAEB' width='100px'>1st Time Customers</td>";

                                    echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                    if ($in_dt_range != "yes") {
                                        $sqlT = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate >= CURDATE()";
                                    } else {
                                        $sqlT = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate = " . $date_from_val;
                                    }
                                    $result_new = db_query($sqlT);
                                    echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=Today'>" . tep_db_num_rows($result_new) . "</a>";
                                    $quotes_sent_t += tep_db_num_rows($result_new);
                                    echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                                    if ($in_dt_range != "yes") {
                                        $sqlY = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND";
                                    } else {
                                        $sqlY = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate BETWEEN " . Date("Y-m-d 23:59:00", strtotime($date_from_val . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -1 days"));
                                    }
                                    $result_new = db_query($sqlY);
                                    echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=yesterday'>" . tep_db_num_rows($result_new) . "</a>";
                                    $quotes_sent_y += tep_db_num_rows($result_new);
                                    echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                                    if ($in_dt_range != "yes") {
                                        $sql7 = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE()";
                                    } else {
                                        $sql7 = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND  qstatus !=2 AND quoteDate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));;
                                    }
                                    $result_new = db_query($sql7);
                                    echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=7days'>" . tep_db_num_rows($result_new) . "</a>";
                                    $quotes_sent_7 += tep_db_num_rows($result_new);
                                    echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                                    if ($in_dt_range != "yes") {
                                        $sql30 = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE()";
                                    } else {
                                        $sql30 = "SELECT * FROM quote WHERE rep LIKE '" . isset($b2bempid) . "' AND qstatus !=2 AND quoteDate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));
                                    }
                                    $result_new = db_query($sql30);
                                    echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=month'>" . tep_db_num_rows($result_new) . "</a>";
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

                            // leadertbl($date_from_val, $date_to_val, "PALLET Leaderboard", 'N', 'N', '#ABC5DF', '#E4EAEB', 'no', $unqid, "ttylno"); 
                            leadertbl($date_from_val, $date_to_val, "PALLET Leaderboard", 'N', 'N', '#ABC5DF', '#E4EAEB', 'no', $unqid, "ttylno", $in_dt_range, $activity_tracker_flg);

        ?>

    <?php
                            //B2ctbl_new_daterange($date_from_val, $date_to_val , "B2C Leaderboard", 'Y', 'Y','#FFF2D0', '#FFF2D0','no',$unqid);

                            //B2ctbl_new($date_from_val, $date_to_val, "B2C Leaderboard", 'Y', 'Y','#ABC5DF', '#E4EAEB','no',$unqid, "ttylno");

                            //   leadertbl_GMI($date_from_val, $date_to_val, "GMI Leaderboard", 'N', 'N', '#ABC5DF', '#E4EAEB', 'no', $unqid, "ttylno");
                            leadertbl_GMI($date_from_val, $date_to_val, "GMI Leaderboard", 'N', 'N', '#ABC5DF', '#E4EAEB', 'no', $unqid, "ttylno", $in_dt_range);



        ?>
    <br />
    <table>
        <tr>
            <td>
                <?
                            $unqid = $unqid + 1;
                            activity_tracker($date_from_val, $date_to_val, "Search", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'yes', $unqid, "ttylno", "yes");
                    ?>
            </td>
        </tr>
    </table>

    <br />
    <br />

    <table width="900" cellSpacing="1" cellPadding="1" border="0">
        <tr>
            <td colspan=4 class="txtstyle_color" align=center><strong>WHO CLOSED A DEAL Between
                    <?php echo $date_from_val; ?> -
                    <?php echo $date_to_val; ?>
                </strong></td>
        </tr>
    </table>
    <table width="900" cellSpacing="1" cellPadding="1" border="0" id="table3" class="tablesorter">
        <thead>
            <tr>
                <th align=center bgColor='#E4EAEB'><u>Company</u></th>
                <th align=center bgColor='#E4EAEB'><u>Employee</u></th>
                <th align=center bgColor='#E4EAEB'><u>Transaction Date</u></th>
                <th align=center bgColor='#E4EAEB'><u>PO Amount</u></th>
                <th align=center bgColor='#E4EAEB'><u>Original Planned Delivery Date</u></th>
                <th align=center bgColor='#E4EAEB'><u>Planned Delivery Date</u></th>
                <th align=center bgColor='#E4EAEB'><u>Actual Delivery Date</u></th>
            </tr>
        </thead>
        <tbody>
            <?php
                            db();
                            //$sql = "SELECT *, loop_warehouse.warehouse_name, loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1 and po_date = DATE_FORMAT(curdate() , '%m/%d/%Y')";
                            $sql = "SELECT *, loop_warehouse.b2bid , loop_warehouse.warehouse_name, loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE 
			loop_transaction_buyer.ignore < 1  and Leaderboard = 'PALLETS' and ";
                            //$sql .= " UNIX_TIMESTAMP(str_to_date(loop_transaction_buyer.po_date, '%m/%d/%Y')) >= " . strtotime($_GET["date_from"]) . " and UNIX_TIMESTAMP(str_to_date(loop_transaction_buyer.po_date, '%m/%d/%Y')) <= " . strtotime($_GET["date_to"]) . " order by po_date"; 
                            $sql .= " transaction_date BETWEEN '" . date("Y-m-d", strtotime($_GET["date_from"])) . "' AND '" . date("Y-m-d", strtotime($_GET["date_to"])) . " 23:59:59' order by transaction_date";

                            //echo $sql . "<br>";
                            $po_poorderamount_tot = 0;
                            $result = db_query($sql);
                            while ($row = array_shift($result)) {
                                $nickname = "";
                                if ($row["b2bid"] > 0) {
                                    db_b2b();
                                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $row["b2bid"];
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
                                    $nickname = $row["warehouse_name"];
                                }

                                $org_delivery_dt = $recent_delivery_dt = $actual_delivery_dt = "";
                                if ($row['po_delivery_dt'] != "") {
                                    $h_qry = "select planned_delivery_dt from planned_delivery_date_history where trans_id=" . $row["I"] . " order by id ASC limit 1";
                                    $h_res = db_query($h_qry);
                                    $cnt_rw1 = tep_db_num_rows($h_res);
                                    if ($cnt_rw1 > 0) {
                                        while ($row1 = array_shift($h_res)) {
                                            if ($row1["planned_delivery_dt"] != "0000-00-00" && $row1["planned_delivery_dt"] != "1969-12-31") {
                                                $org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
                                            }
                                        }
                                    } else {
                                        $org_delivery_dt = date('m/d/Y', strtotime($row['po_delivery_dt']));
                                    }

                                    $recent_delivery_dt = date('m/d/Y', strtotime($row['po_delivery_dt']));

                                    $sql = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $row["I"];
                                    $sql_res = db_query($sql);
                                    $cnt_rw2 = tep_db_num_rows($sql_res);
                                    if ($cnt_rw2 > 0) {
                                        while ($row2 = array_shift($sql_res)) {
                                            $actual_delivery_dt = date('m/d/Y', strtotime($row2["bol_shipment_received_date"]));
                                        }
                                    }
                                }

                                echo "<tr><td bgColor='#E4EAEB' align='left' ><a target='_blank' href='http://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["I"] . "&display=buyer_view'>" . $nickname . "</a></td>
				<td align='left'  bgColor='#E4EAEB'>" . $row["po_employee"] . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($row["transaction_date"])) . "</td>
				<td align='right' bgColor='#E4EAEB'>$" . number_format($row["po_poorderamount"], 2) . "</td><td bgColor='#E4EAEB' align='center'>" . $org_delivery_dt . "</td>
				<td bgColor='#E4EAEB' align='center'>" . $recent_delivery_dt . "</td><td bgColor='#E4EAEB' align='center'>" . $actual_delivery_dt . "</td></tr>";

                                $po_poorderamount_tot = $po_poorderamount_tot + str_replace(",", "", number_format($row["po_poorderamount"], 2));
                            }
                            echo "</tbody>";
                            if ($po_poorderamount_tot > 0) {
                                echo "<tr><td bgColor='#E4EAEB' colspan='2'>&nbsp;</td><td bgColor='#E4EAEB'><b>Total</b></td><td align='right' bgColor='#E4EAEB'><b>$" . number_format($po_poorderamount_tot, 2) . "</b></td>
				<td bgColor='#E4EAEB' colspan='3'>&nbsp;</td></tr>";
                            }

                ?>
    </table>
    <br /><br />

    <table border="0" width="900">
        <tr>
            <td colspan="3" style="font-size:16pt;"><strong>Transactions Between
                    <?php echo $date_from_val; ?> -
                    <?php echo $date_to_val; ?>
                </strong></td>
        </tr>
        <tr>
            <td width="300" valign="top">
                <table width="300" cellSpacing="1" cellPadding="1" border="0">
                    <tr>
                        <td colspan="2" class="txtstyle_color" align="center"><strong>SHIPPED DEALS</strong></td>
                    </tr>
                </table>
                <table width="300" cellSpacing="1" cellPadding="1" border="0" id="table4" class="tablesorter">
                    <thead>
                        <tr>
                            <th align="center" bgColor='#E4EAEB'><u>Company</u></th>
                            <th align="center" bgColor='#E4EAEB'><u>PO Date</u></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT *, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_bol_files INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id INNER JOIN loop_warehouse ON loop_warehouse.id = loop_bol_files.warehouse_id ";
                            $sql .= " where loop_transaction_buyer.ignore = 0  and Leaderboard = 'PALLETS' and UNIX_TIMESTAMP(str_to_date(bol_shipped_date, '%m/%d/%Y')) >= " . strtotime($_GET["date_from"]) . " and UNIX_TIMESTAMP(str_to_date(bol_shipped_date, '%m/%d/%Y')) <= " . strtotime($_GET["date_to"]) . " order by bol_shipped_date";

                            $result = db_query($sql);
                            while ($row = array_shift($result)) {
                                $nickname = "";
                                if ($row["b2bid"] > 0) {
                                    db_b2b();
                                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $row["b2bid"];
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
                                    $nickname = $row["warehouse_name"];
                                }

                                echo "<tr><td bgColor='#E4EAEB'><a href='http://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["trans_rec_id"] . "&display=buyer_ship'>" . $nickname . "</a>";
                                if ($row["bol_shipment_received"] == 1) {
                                    echo " (and delivered)";
                                }
                                echo "</td><td bgColor='#E4EAEB'>" . $row["bol_shipped_date"] . "</td> </tr>";
                            }
                            ?>
                    </tbody>
                </table>

            </td>

            <td width="300" valign="top">
                <table width="300" cellSpacing="1" cellPadding="1" border="0">
                    <tr>
                        <td class="txtstyle_color" align="center"><strong>STILL ON THE ROAD</strong></td>
                    </tr>
                </table>
                <table width="300" cellSpacing="1" cellPadding="1" border="0" id="table5" class="tablesorter">
                    <thead>
                        <tr>
                            <th align="center" bgColor='#E4EAEB'><u>Company</u></th>
                            <th align="center" bgColor='#E4EAEB'><u>PO Date</u></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT *, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_bol_files INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id INNER JOIN loop_warehouse ON loop_warehouse.id = loop_bol_files.warehouse_id WHERE loop_transaction_buyer.ignore = 0 and bol_shipped = 1 and bol_shipment_received = 0 AND trans_rec_id > 1000  and Leaderboard = 'PALLETS' AND ";
                            $sql .= " UNIX_TIMESTAMP(str_to_date(bol_shipped_date, '%m/%d/%Y')) >= " . strtotime($_GET["date_from"]) . " and UNIX_TIMESTAMP(str_to_date(bol_shipped_date, '%m/%d/%Y')) <= " . strtotime($_GET["date_to"]) . " order by bol_shipped_date";

                            $result = db_query($sql);
                            while ($row = array_shift($result)) {
                                $nickname = "";
                                if ($row["b2bid"] > 0) {
                                    db_b2b();
                                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $row["b2bid"];
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
                                    $nickname = $row["warehouse_name"];
                                }

                                echo "<tr><td bgColor='#E4EAEB'> <a href='http://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["trans_rec_id"] . "&display=buyer_ship'>" . $nickname . "</a></td>";
                                echo "<td bgColor='#E4EAEB'>" . $row["bol_shipped_date"] . "</td> </tr>";
                            } ?>
                    </tbody>
                </table>

            </td>

            <td width="300" valign="top">
                <table width="300" cellSpacing="1" cellPadding="1" border="0">
                    <tr>
                        <td class="txtstyle_color" align="center"><strong>DELIVERED DEALS</strong></td>
                    </tr>
                </table>
                <table width="300" cellSpacing="1" cellPadding="1" border="0" id="table6" class="tablesorter">
                    <thead>
                        <tr>
                            <th align="center" bgColor='#E4EAEB'><u>Company</u></th>
                            <th align="center" bgColor='#E4EAEB'><u>PO Date</u></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT *, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_bol_files INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id INNER JOIN loop_warehouse ON loop_warehouse.id = loop_bol_files.warehouse_id WHERE Leaderboard = 'PALLETS' and ";
                            $sql .= " loop_transaction_buyer.ignore = 0 and UNIX_TIMESTAMP(str_to_date(bol_shipment_received_date, '%m/%d/%Y')) >= " . strtotime($_GET["date_from"]) . " and UNIX_TIMESTAMP(str_to_date(bol_shipment_received_date, '%m/%d/%Y')) <= " . strtotime($_GET["date_to"]) . " order by bol_shipment_received_date";

                            $result = db_query($sql);
                            while ($row = array_shift($result)) {
                                $nickname = "";
                                if ($row["b2bid"] > 0) {
                                    db_b2b();
                                    $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $row["b2bid"];
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
                                    $nickname = $row["warehouse_name"];
                                }

                                echo "<tr><td bgColor='#E4EAEB'><a href='http://loops.usedcardboardboxes.com/search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["trans_rec_id"] . "&display=buyer_received'>" . $nickname . "</a></td>";
                                echo "<td bgColor='#E4EAEB'>" . $row["bol_shipment_received_date"] . "</td> </tr>";
                            } ?>
                    </tbody>
                </table>

            </td>

        </tr>
    </table>

    <br /><br />
    <?php
                            //$unqid = 9744;

                            //activity_tracker( Date('Y-m-d', strtotime($_GET["date_from"])),  Date('Y-m-d', strtotime($_GET["date_to"])) , "Activity Tracking", 'Y', 'Y','#ABC5DF', '#E4EAEB', 'yes', $unqid , "ttylno", "yes"); 
                        }
                    }
?>

</body>

</html>