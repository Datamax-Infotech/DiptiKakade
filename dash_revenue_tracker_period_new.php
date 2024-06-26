<style>
a {
    font-size: 10pt !important;
}
</style>

<?php


require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


$initials = $_REQUEST['initials'];
$dashboard_view = $_REQUEST['dashboard_view'];
//$initials = "CG";

$emp_filter = " and loop_transaction_buyer.po_employee = '$initials'";
if ($dashboard_view == "Operations" || $dashboard_view == "Executive") {
    $emp_filter = "";
}

$dashboard_view = "Operations";
$sql = "SELECT purchasing_leaderboard, dashboard_view FROM loop_employees WHERE purchasing_leaderboard = 1 and initials = '" . $initials . "'";
db();
$result = db_query($sql);
while ($rowemp = array_shift($result)) {
    $dashboard_view = "";
}

$sql = "SELECT purchasing_leaderboard, dashboard_view FROM loop_employees WHERE initials = '" . $initials . "'";
db();
$result = db_query($sql);
while ($rowemp = array_shift($result)) {
    if ($rowemp["dashboard_view"] == "Pallet Sourcing") {
        $dashboard_view = "Pallet Sourcing";
    }
}

$unqid = 1;
?>
<table cellSpacing="1" cellPadding="1" border="0" width="800">
    <?php if ($dashboard_view == "Operations" || $dashboard_view == "Executive") { ?>

    <?php

        $st_date = Date('Y-01-01');
        $end_date = Date('Y-12-31');
        $currentyr = (int)date('Y');
        $unqid = $unqid + 1;
        //leadertbl_purchasing($st_date, $end_date, true, true, true, '#ABC5DF', '#E4EAEB', false, (string)$unqid, 2022, "", "Year", "All");
        leadertbl_purchasing(
            $st_date,           // Assuming $st_date is a string (formatted date)
            $end_date,          // Assuming $end_date is a string (formatted date)
            "",      // Replacing true with an actual string for $headtxt
            true,               // Correct boolean value for $tilltoday
            $currentyr,               // Replacing true with the actual year as an integer for $currentyr
            '#ABC5DF',          // Correct string value for $tbl_head_color
            '#E4EAEB',          // Correct string value for $tbl_color
            false,              // Correct boolean value for $po_flg
            (string)$unqid,     // Correct string value for $unqid
            true,               // Replacing 2022 with a boolean value for $ttylyesno
            true,               // Replacing "" with a boolean value for $in_dt_range
            'Year',             // Correct string value for $timeperiod
            'All'               // Correct string value for $empfilter
        );

        // function getCurrentQuarter(int $timestamp = null): int
        // {
        //     if (!$timestamp) $timestamp = time();
        //     $day = date('n', $timestamp);
        //     $quarter = ceil($day / 3);
        //     return $quarter;
        // }
        function getCurrentQuarter(int $timestamp = null): int
        {
            if (!$timestamp) $timestamp = time();
            $month = date('n', $timestamp);
            $quarter = ceil($month / 3);
            return (int)$quarter;  // Cast the result to int
        }

        // function getPreviousQuarter($timestamp = false): int
        // {
        //     if (!$timestamp) $timestamp = time();
        //     //$quarter = getCurrentQuarter($timestamp) - 1;
        //     $quarter = getCurrentQuarter($timestamp);
        //     if ($quarter < 0) {
        //         $quarter = 4;
        //     }
        //     return $quarter;
        // }
        function getPreviousQuarter(?int $timestamp = null): int
        {
            if (!$timestamp) $timestamp = time();
            $quarter = getCurrentQuarter($timestamp) - 1;
            if ($quarter < 1) {
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
        $currentyr = $currentyr ?? '';
        $unqid = $unqid + 1;
        // leadertbl_purchasing($st_date, $end_date, "THIS QUARTER", true, true, '#ABC5DF', '#E4EAEB', false, (int)$unqid, true, false, "Quarter", "All");
        leadertbl_purchasing(
            $st_date,           // Assuming this is a string representing start date
            $end_date,          // Assuming this is a string representing end date
            "THIS QUARTER",     // Assuming this is a string for header text
            true,               // Boolean value for $tilltoday
            (int)$currentyr,    // Cast to int for $currentyr
            '#ABC5DF',          // String color code for $tbl_head_color
            '#E4EAEB',          // String color code for $tbl_color
            false,              // Boolean value for $po_flg
            (string)$unqid,     // Cast to string for $unqid
            true,               // Boolean value for $ttylyesno
            false,              // Boolean value for $in_dt_range
            "Quarter",          // String value for $timeperiod
            "All"               // String value for $empfilter
        );

        $st_date = Date('Y-m-01');
        $end_date = Date('Y-m-t');


        $unqid = $unqid + 1;
        leadertbl_purchasing($st_date, $end_date, "THIS MONTH", true, 1, '#ABC5DF', '#E4EAEB', false, (string)$unqid, true, false, "Month", "All");

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
    } else { ?>
    <tr>
        <td class="header_td_style" align="center"><strong>Time Period</strong></td>
        <td class="header_td_style" align="center"><strong>Revenue Quota</strong></td>
        <td class="header_td_style" align="center"><strong>Quota To Date</strong></td>
        <td class="header_td_style" align="center"><strong>Revenue</strong></td>
        <td class="header_td_style" align="center"><strong>% of Quota</strong></td>
        <td class="header_td_style" align="center"><strong>G.Profit</strong></td>
        <td class="header_td_style" align="center"><strong>Profit Margin</strong></td>
        <td class="header_td_style" align="center"><strong>Renenue TRLY</strong></td>
        <td class="header_td_style" align="center"><strong>G.Profit TRLY</strong></td>
        <!-- <td class="header_td_style" align="center"><strong>How Far From Quota?</strong></td> -->
    </tr>
    <?php


        $st_date = Date('Y-01-01');
        $end_date = Date('Y-12-31');
        $year = (int)date('Y');
        $unqid = 1;
        //leadertbl_purchasing($st_date, $end_date, "THIS YEAR", true, true, '#ABC5DF', '#E4EAEB', false, (string)$unqid, true, false, "This Year", $initials);
        leadertbl_purchasing(
            $st_date,           // Assuming this is a string representing start date
            $end_date,          // Assuming this is a string representing end date
            "THIS YEAR",        // Assuming this is a string for header text
            true,               // Boolean value for $tilltoday
            $year,        // Correct way to get the current year as an integer
            '#ABC5DF',          // String color code for $tbl_head_color
            '#E4EAEB',          // String color code for $tbl_color
            false,              // Boolean value for $po_flg
            (string)$unqid,     // Cast to string for $unqid
            true,               // Boolean value for $ttylyesno
            false,              // Boolean value for $in_dt_range
            "This Year",        // String value for $timeperiod
            $initials           // Assuming $initials is a variable holding a string value
        );


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
        $currentyr = (int)date('Y');
        leadertbl_purchasing($st_date, $end_date, "THIS QUARTER", true, $currentyr, '#ABC5DF', '#E4EAEB', false, (string)$unqid, true, false, "This Quarter", $initials);

        $st_date = Date('Y-m-01');
        $end_date = Date('Y-m-t');

        $unqid = $unqid + 1;
        leadertbl_purchasing($st_date, $end_date, "THIS MONTH", true, 1, '#ABC5DF', '#E4EAEB', false, (string)$unqid, true, false, "Month", "All", $initials);

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

        leadertbl_purchasing($st_date, $end_date, "LAST YEAR", true, 1, '#ABC5DF', '#E4EAEB', false, (string)$unqid, true, false, "Last Year", $initials);

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
        leadertbl_purchasing($st_lastqtr, $end_lastqtr, "LAST QUARTER", true, 1, '#ABC5DF', '#E4EAEB', false, (string)$unqid, true, false, "Last Quarter", $initials);

        $st_lastmonth = date("Y-n-j", strtotime("first day of previous month"));
        $end_lastmonth = date("Y-n-j", strtotime("last day of previous month"));

        $st_lastmonth_lastyr = date($last_yr . '-m-01', strtotime($st_date));
        $end_lastmonth_lastyr = date($last_yr . '-m-t', strtotime($end_date));

        $unqid = $unqid + 1;
        leadertbl_purchasing($st_lastmonth, $end_lastmonth, "LAST MONTH", true, 1, '#ABC5DF', '#E4EAEB', false, (string)$unqid, true, false, "Last Month", $initials);
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
                    onclick="ex_dash_po_enter_rescue('<?php echo $st_date; ?>', '<?php echo $end_date; ?>', 'po_this', '<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
                / <a href="javascript:void(0);" onclick="colp_dash_po_enter_rescue('po_this');">Collapse</a>
            </td>
            <td>&nbsp;</td>
            <td align='center' class='txtstyle_color' style="background:<?php echo isset($tbl_head_color); ?>">
                <strong>PO
                    Entered last week [
                    <?php
                    $st_friday_last = $st_friday_last ?? '';
                    $st_thursday_last = $st_thursday_last ?? '';
                    echo Date("m/d/Y", strtotime($st_friday_last)) . " - " . Date("m/d/Y", strtotime($st_thursday_last)); ?>]
                </strong>&nbsp;&nbsp;<a href="javascript:void(0);"
                    onclick="ex_dash_po_enter_rescue('<?php echo isset($st_friday_last); ?>', '<?php echo isset($st_thursday_last); ?>', 'po_last', '<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
                / <a href="javascript:void(0);" onclick="colp_dash_po_enter_rescue('po_last');">Collapse</a>
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

<?php

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
    bool $in_dt_range,
    string $timeperiod,
    string $empfilter,
    string $activity_tracker_flg = "no"
): void {


    db();

    $lisoftrans_detail_list = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'><tr style='height:50px;'>";
    $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Transaction ID</u></th>";
    $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='200px'><u>Company</u></th>";
    $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>PO Date</u></th>";
    $lisoftrans_detail_list .= "<th align='left' bgColor='$tbl_head_color' width='50px'><u>Employee</u></th>";
    $lisoftrans_detail_list .= "<th width='100px' bgColor='$tbl_head_color' align=center><u>PO Amount</u></th>";
    $lisoftrans_detail_list .= "</tr>";

    if ($empfilter == "All") {

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750'>";
        echo "	<tr>";
        if ($headtxt == "LAST TO LAST YEAR") {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sourcing Reps] TWO YEARS AGO [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999111";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sourcing Reps] PO ENTERED THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "yes") {
            $div_id_emp_list = "999222";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sourcing Reps] PO ENTERED LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "THIS WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999333";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sourcing Reps] THIS WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else if ($headtxt == "LAST WEEK" && $po_flg == "no") {
            $div_id_emp_list = "999444";
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sourcing Reps] LAST WEEK [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "] </strong></td>";
        } else {
            echo "		<td align='center' class='txtstyle_color' style='background:$tbl_head_color'><strong>[B2B Sourcing Reps] " .  $headtxt . " [" . Date("m/d/Y", strtotime($start_Dt)) . " - " . Date("m/d/Y", strtotime($end_Dt)) . "]</strong></td>";
        }
        echo "	</tr>";
        echo "</table>";
        echo "<table cellSpacing='1' cellPadding='1' border='0' width='750' id='table9' class='tablesorter' >";
        echo "<thead>";

        echo "	<tr style='height:50px;'>";
        echo "		<th align='left' bgColor='$tbl_head_color' width='100px'><u>Employee</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Revenue Quota</u></th>";
        if ($headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER" || $headtxt == "THIS YEAR") {
            echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Quota To Date</u></th>";
        }
        echo "		<th width='100px' bgColor='$tbl_head_color' align='center'><u>Revenue</u></th>";
        echo "      <th width='90px' bgColor='$tbl_head_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align=center><u>% of Quota</u></th>";
        echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G. Profit</u></th>";


        echo "		<th width='90px' bgColor='$tbl_head_color' align=center><u>Profit Margin</u></th>";

        //if ($ttylyesno == "ttylyes" || $headtxt == "THIS YEAR") {
        echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>Revenue TRLY</u></th>";
        //echo "		<th width='100px' bgColor='$tbl_head_color' align=center><u>G.Profit TRLY</u></th>";
        //}	
        echo "	</tr>";

        echo "</thead>";
        echo "<tbody>";
    }

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


    if ($empfilter == "All") {
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard, dashboard_view FROM loop_employees WHERE purchasing_leaderboard = 1 and status = 'Active'";
    } else {
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard, dashboard_view FROM loop_employees WHERE initials = '" . $empfilter . "'";
    }

    //echo $sql . "<br>";
    $dashboard_view = 'Sales';
    $emp_initials_list = '';
    $emp_b2bid_list = '';
    $emp_id_list = '';
    $emp_eml_list = '';
    $result = db_query($sql);
    while ($rowemp = array_shift($result)) {
        $dashboard_view = $rowemp["dashboard_view"];
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

    if ($empfilter == "All") {
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees WHERE  purchasing_leaderboard = 1 and status = 'Active'";
    } else {
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard FROM loop_employees WHERE initials = '" . $empfilter . "'";
    }

    $employee_quota_tbl = " employee_quota_purchasing ";
    $Leaderboard_filter = "Leaderboard = 'B2B'";
    if ($dashboard_view == "Sales") {
        $employee_quota_tbl = " employee_quota_purchasing ";
        $Leaderboard_filter = "Leaderboard = 'B2B'";
    }

    if ($dashboard_view == "Pallet Sourcing") {
        $employee_quota_tbl = " employee_quota_pallet_source_gprofit ";
        $Leaderboard_filter = "Leaderboard = 'PALLETS'";
    }
    //echo $sql . "<br>";
    $result = db_query($sql);
    while ($rowemp = array_shift($result)) {
        $quota = 0;
        $quotadate = "";
        $deal_quota = 0;
        $monthly_qtd = 0;

        db();
        $result_empq = db_query("Select quota_year, quota_month from $employee_quota_tbl where emp_id = " . $rowemp["id"] . " order by quota_year, quota_month limit 1");
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
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from $employee_quota_tbl where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
            $result_empq = db_query("Select sum(quota) as sumquota, sum(deal_quota) as deal_quota from $employee_quota_tbl where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month = " . $dt_month_value);
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
                $newsel = "Select quota_month, quota , deal_quota, quota_year  from $employee_quota_tbl where emp_id = " . $rowemp["id"] . " and quota_year = " . $datecnt->format("Y") . " and quota_month = " . $datecnt->format("m");
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
                $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_tbl where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(1,2,3) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-01-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-01-01")));
                }
            }
            if ($current_qtr == 2) {

                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_tbl where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(4,5,6) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-04-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-04-01")));
                }
            }
            if ($current_qtr == 3) {
                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_tbl where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(7,8,9) order by quota_month");
                if ($headtxt == "THIS QUARTER LAST YEAR") {
                    $date_qtr = date('m/d/Y', strtotime(date($dt_year_value . "-07-01")));
                } else {
                    $date_qtr = date('m/d/Y', strtotime(date("Y-07-01")));
                }
            }
            if ($current_qtr == 4) {
                db();
                $result_empq = db_query("Select quota_month, quota , deal_quota from $employee_quota_tbl where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " and quota_month in(10,11,12) order by quota_month");
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
            $result_empq = db_query("Select quota_month, quota, deal_quota from $employee_quota_tbl where emp_id = " . $rowemp["id"] . " and quota_year = " . $dt_year_value . " order by quota_month");
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

        $lisoftrans = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='1000'>";
        if ($po_flg == "yes") {
            $lisoftrans .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Invoice Number</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        } else {
            //$lisoftrans .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Supplier</td><td class='txtstyle_color'>Customer</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Quantity</td><td class='txtstyle_color'>Price</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>Profit</td><td class='txtstyle_color'>Margin</td></tr>";

            $lisoftrans .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Supplier Transaction Number</td><td class='txtstyle_color'>Supplier Name</td><td class='txtstyle_color'>Customer Transaction Number</td>
			<td class='txtstyle_color'>Customer Name</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Quantity</td><td class='txtstyle_color'>Price</td><td class='txtstyle_color'>Revenue</td>
			<td class='txtstyle_color'>Profit</td><td class='txtstyle_color'>Margin</td></tr>";
        }

        if ($headtxt == "THIS YEAR") {
            //echo $sqlmtd . "<br>";
        }

        $quote_amount = 0;

        $str_box_list_ids = "";
        $summtd_SUMPO = 0;
        $summtd_dealcnt = 0;
        $profit_val_org = 0;
        $str_box_list_transids = "";
        //$qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id FROM loop_invoice_items WHERE box_item_founder_emp_id=". $rowemp["id"]);
        $qry = db_query("SELECT distinct(loop_box_id) AS id, trans_rec_id FROM loop_invoice_items inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_invoice_items.trans_rec_id 
		WHERE loop_transaction_buyer.Leaderboard = 'B2B' and box_item_founder_emp_id=" . $rowemp["id"]);
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
            $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_invoice_details.total as loop_inv_amount 
			FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id 
			inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_bol_tracking.trans_rec_id 
			where loop_transaction_buyer.ignore = 0 and loop_invoice_details.trans_rec_id in (" . $str_box_list_transids . ") and loop_invoice_details.timestamp between '" . $start_Dt . "' and '" . $end_Dt . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");

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
                        $inv_amount = $inv_amount ?? '';
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

                        db();
                        $q1_supp = "SELECT * FROM loop_warehouse where id = " . $vendor_b2b_rescue;
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
                    //$dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE total > 0 and category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " "; 
                    $dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ";
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

                    $tot_profit = $tot_profit + $profit_val;

                    if ($invoice_amt != 0 && is_numeric($invoice_amt)) {
                        $profit_val_p = $gross_profit_val * 100 / $invoice_amt;
                    }
                    $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                    $profit_val = "$" . number_format($profit_val, 0);

                    $summtd_dealcnt = $summtd_dealcnt + 1;
                    if ($po_flg == "yes") {
                    } else {
                        $lisoftrans .= "<tr>
						<td bgColor='#E4EAEB'>" . $rowemp["initials"] . "</td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $virtual_inventory_trans_id . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer'>" . $supp_nm . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "</a></td>
						<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier'>" . $nickname . "</a></td>
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
            $lisoftrans .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summtd_SUMPO, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_profit, 0) . "</td><td bgColor='#ABC5DF'>&nbsp;</td></tr>";
        }
        $lisoftrans .= "</table></span>";

        //For TTLY
        $summ_ttly = 0;
        $summ_ttly_g_profit = 0;
        $tot_profit = 0;
        $profit_val_org_ttly = 0;
        $profit_val_org_ttly_gprofit = 0;
        if ($ttylyesno == "ttylyes" || $headtxt == "THIS YEAR") {
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
                $lisoftrans_ttly .= "<tr><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Supplier Transaction Number</td><td class='txtstyle_color'>Supplier Name</td>
				<td class='txtstyle_color'>Customer Transaction Number</td>
				<td class='txtstyle_color'>Customer Name</td><td class='txtstyle_color'>Description</td><td class='txtstyle_color'>Quantity</td><td class='txtstyle_color'>Price</td>
				<td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>Profit</td><td class='txtstyle_color'>Margin</td></tr>";

                $lisoftrans_ttly_g_profit = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='820'>";
                $lisoftrans_ttly_g_profit .= "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
				<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
				<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Revenue</td><td class='txtstyle_color'>G.Profit Amount</td><td class='txtstyle_color'>Profit Margin</td></tr>";

                $row_no = 0;
                $tmp_trans_id = "";
                $vendor_b2b_rescue = 0;
                $sr_no = 0;


                $qry = db_query("Select box_id, qty, loop_bol_tracking.trans_rec_id, loop_transaction_buyer.po_delivery_dt,
				loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount,
				loop_invoice_details.total as loop_inv_amount 
				FROM loop_bol_tracking inner join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_bol_tracking.trans_rec_id 
				inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_bol_tracking.trans_rec_id 
				where loop_transaction_buyer.ignore = 0 and loop_invoice_details.trans_rec_id in (" . $str_box_list_transids . ") and loop_invoice_details.timestamp between '" . $start_Dt_ttly . "' and '" . $end_Dt_ttly . " 23:59:59' and box_id in (" . $str_box_list_ids . ")");

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

                        $summ_ttly = $summ_ttly + floatval(str_replace(",", "", $gr_total));

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
                        if ($finalpaid_amt == 0 && $inv_amount > 0) {
                            $inv_amount = $inv_amount ?? "";
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

                            db();
                            $q1_supp = "SELECT * FROM loop_warehouse where id = " . $vendor_b2b_rescue;
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
                        //$dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE total > 0 and category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " "; 
                        $dt_view_qry = "SELECT sum(quantity) as quantity FROM loop_invoice_items WHERE category_id <> 7 and trans_rec_id = " . $row_rs_tmprs["trans_rec_id"] . " ";
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
                                $vendor_pay += str_replace(",", "", $dt_view_row["estimated_cost"]);
                            }
                        }

                        $po_delivery_dt = "";
                        if ($row_rs_tmprs["po_delivery_dt"] != "") {
                            $po_delivery_dt = date("m/d/Y", strtotime($row_rs_tmprs["po_delivery_dt"]));
                        }

                        $actual_delivery_date = "";
                        $sql = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $row_rs_tmprs["trans_rec_id"];
                        $sql_res = db_query($sql);
                        while ($row = array_shift($sql_res)) {
                            $actual_delivery_date = $row["bol_shipment_received_date"];
                        }

                        $gross_profit_val_gprofit = $invoice_amt - $vendor_pay;

                        $gross_profit_val = $invoice_amt;

                        $profit_val = ($quantity_per * $gross_profit_val_gprofit) / 100;
                        $tot_profit = $tot_profit + str_replace(",", "", number_format($profit_val, 0));

                        //$profit_val_per = number_format((($profit_val * 100)/$invoice_amt),2) . "%";

                        $profit_val_p = (($gross_profit_val * 100) / $invoice_amt);
                        $profit_val_org_ttly = $profit_val_org_ttly + str_replace(",", "", number_format(($quantity_per * $gross_profit_val) / 100, 0));

                        $profit_val_org_ttly_gprofit = $profit_val_org_ttly_gprofit + str_replace(",", "", number_format(($quantity_per * $gross_profit_val_gprofit) / 100, 0));

                        //$profit_val_per = number_format(($profit_val_p * 100)/$quantity_per,2) . "%";
                        $profit_val_per = number_format(($profit_val * 100) / $invoice_amt_ind, 2) . "%";

                        $profit_val = "$" . number_format($profit_val, 0);

                        $summ_ttly_g_profit = $summ_ttly_g_profit + ($invoice_amt - $vendor_pay);

                        $sr_no = $sr_no + 1;
                        if ($po_flg == "yes") {
                            //$lisoftrans .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=". $wid ."&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_view'>". $row_rs_tmprs["trans_rec_id"] . "</a></td><td bgColor='#E4EAEB'>" . $inv_number . "</td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($summtd["inv_amount"],0) . "</td></tr>";
                        } else {
                            $lisoftrans_ttly .= "<tr>
							<td bgColor='#E4EAEB'>" . $rowemp["initials"] . "</td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $supplier_wid . "&rec_id=" . $virtual_inventory_trans_id . "&display=seller_sort'>" . $virtual_inventory_trans_id . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $supplier_b2bid . "&show=transactions&warehouse_id=" . $supplier_wid . "&rec_type=Manufacturer'>" . $supp_nm . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_invoice'>" . $row_rs_tmprs["trans_rec_id"] . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier'>" . $nickname . "</a></td>
							<td bgColor='#E4EAEB'><a target='_blank' href='manage_box_b2bloop.php?id=" . $row_rs_tmprs["box_id"] . "&proc=View'>" . $box_desc . "</a></td>
							<td bgColor='#E4EAEB'>" . $quantity . "</td>
                            <td bgColor='#E4EAEB'>" . number_format(floatval($price), 2) . "</td>
                            <td bgColor='#E4EAEB' align='right'>$" . number_format(floatval(str_replace(",", "", $total)), 0) . "</td>
							<td bgColor='#E4EAEB' $profit_val_str align='right'>" . $profit_val . "</td>
							<td bgColor='#E4EAEB' $profit_val_str >" . $profit_val_per . "</td>
							</tr>";

                            $lisoftrans_ttly_g_profit .= "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $row_rs_tmprs["po_employee"] . "</td>
							<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $row_rs_tmprs["b2bid"] . "&show=transactions&warehouse_id=" . $row_rs_tmprs["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row_rs_tmprs["warehouse_id"] . "&rec_id=" . $row_rs_tmprs["id"] . "&display=buyer_view'>" . $row_rs_tmprs["id"] . "</a></td>
							<td bgColor='#E4EAEB'>" . $row_rs_tmprs["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
                            <td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . isset($industry_nm) . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format(floatval($invoice_amt), 0) . "</td>
                            <td bgColor='#E4EAEB' align='right'>$" . number_format($invoice_amt - $vendor_pay, 0) . "</td><td bgColor='#E4EAEB' align='right'>" . number_format(($invoice_amt - $vendor_pay) * 100 / floatval(str_replace(",", "", number_format($invoice_amt, 0))), 2) . "%</td></tr>";
                        }
                    }
                    $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
                }

                if ($summtd_SUMPO > 0) {
                    $lisoftrans_ttly .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
					<td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($summ_ttly, 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format($tot_profit, 0) . "</td><td bgColor='#ABC5DF'>&nbsp;</td></tr>";
                }
                $lisoftrans_ttly .= "</table></span>";

                if ($summ_ttly_g_profit > 0) {
                    $lisoftrans_ttly_g_profit .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td>
                    <td bgColor='#ABC5DF' align='right'>$" . number_format(floatval($summ_ttly), 0) . "</td><td bgColor='#ABC5DF' align='right'>$" . number_format(floatval($tot_profit), 0) . "</td>
                    <td bgColor='#ABC5DF' align='right'>" . number_format($summ_ttly_g_profit * 100 / floatval(str_replace(",", "", number_format($summ_ttly, 0))), 2) . "%</td></tr>";
                }
                $lisoftrans_ttly_g_profit .= "</table></span>";
            }
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
        if ($headtxt == "B2B Leaderboard") {
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
                'empid' => $rowemp["id"], 'start_Dt' => $start_Dt, 'end_Dt' => $end_Dt, 'leaderboard' => $rowemp["leaderboard"], 'summ_ttly_g_profit' => isset($summ_ttly_g_profit), 'lisoftrans_ttly_g_profit' => isset($lisoftrans_ttly_g_profit),
                'deal_count' => $summtd_dealcnt, 'quota' => isset($quota_in_st_en), 'profit_val_org' => $profit_val_org, 'quote_amount_tweek' => $quote_amount_tweek,
                'quotatodate' => $monthly_qtd, 'po_entered' => $summtd_SUMPO, 'ttly' => $profit_val_org_ttly, 'profit_val_org_ttly_gprofit' => $profit_val_org_ttly_gprofit, 'percent_val' => $color,
                'rev_lastyr_tilldt' => isset($profit_val_org_rev_lastyr), 'lisoftrans' => $lisoftrans, 'lisoftrans_ttly' => isset($lisoftrans_ttly), 'lisoftrans_lastyr' => isset($lisoftrans_lastyr)
            );
        }
    }

    //var_dump($MGArray);

    $_SESSION['sortarrayn'] = isset($MGArray);

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


            $MGArray = $MGArray ?? [];
            foreach ($MGArray as $MGArraytmp) {
                if (isset($MGArraytmp['companyID'])) {
                    $MGArraysort_I[] = $MGArraytmp['companyID'];
                }
            }

            // $MGArraysort_I = $MGArraysort_I ?? [];
            // $sort_order = $sort_order ?? SORT_ASC;
            // $sort_type = $sort_type ?? SORT_NUMERIC;
            // $MGArray = $MGArray ?? [];
            array_multisort($MGArraysort_I, $sort_order, $sort_type, $MGArray);
        }
    } else if ($po_flg == "yes") {
        $MGArray = $_SESSION['sortarrayn'];
        $MGArraysort_I = array();

        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort_I[] = $MGArraytmp['quote_amount_tweek'];
        }
        $sort_order = SORT_DESC;
        $sort_type = SORT_NUMERIC;
        array_multisort($MGArraysort_I, $sort_order, $sort_type, $MGArray);
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
            // $MGArraysort_I = $MGArraysort_I ?? [];
            // $MGArray = $MGArray ?? [];
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
        $summ_ttly_g_profit = $MGArraytmp2["profit_val_org_ttly_gprofit"];

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
		WHERE loop_transaction_buyer.Leaderboard = 'B2B' and box_item_founder_emp_id=" . $MGArraytmp2["empid"]);
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

                    $lisoftrans_detail_list .= "<tr><td bgColor='$tbl_color'><a target='_blank' href='viewCompany.php?ID=" . $b2bid . "&show=transactions&warehouse_id=" . $wid . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $wid . "&rec_id=" . $row_rs_tmprs["trans_rec_id"] . "&display=buyer_view'>" . $row_rs_tmprs["trans_rec_id"] . "</a></td><td bgColor='$tbl_color'>" . $nickname . "</td></td><td bgColor='$tbl_color'>" . $po_date . "</td><td bgColor='$tbl_color'>" . $name . "</td><td bgColor='$tbl_color' align='right'>$" . number_format(floatval($gr_total), 0) . "</td></tr>";

                    $tmp_trans_id = $row_rs_tmprs["trans_rec_id"];
                }
            }
            //for the Emp wise order list - View detail list
        }

        if ($headtxt == "B2B Leaderboard") {
        } else {
            if ($empfilter == "All") {
                echo "<tr><td bgColor='$tbl_color' width='100px' align ='left'>" . $name . "</td>";
            } else {
                echo "<tr><td bgColor='$tbl_color' width='100px' align ='left'>" . $timeperiod . "</td>";
            }
            //echo "<tr><td bgColor='$tbl_color' width='260px' align ='left'>" . $name . "</td>";

            //if ($currentyr == "Y" && $headtxt != "TODAY" && $headtxt != "LAST TO LAST YEAR")  {
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "$" . number_format($monthly_qtd, 0);
            echo "</td>";
            //if ($headtxt == "THIS MONTH" || $headtxt == "THIS QUARTER" || $headtxt == "THIS YEAR" ) {	
            echo "<td bgColor='$tbl_color' align = 'right'>";
            echo "$" . number_format($monthly_qtd_TD, 0);
            echo "</td>";
            //}
            //}else{
            //	echo "<td bgColor='$tbl_color' align = 'right'>";
            //	echo "$" . number_format($monthly_qtd,0);
            //}

            if ($sales_revenue >= $monthly_qtd && $monthly_qtd != 0) {
                $revenue_color = "green";
            } elseif ($sales_revenue > 0 && $monthly_qtd == 0) {
                $revenue_color = "green";
            } elseif ($sales_revenue < $monthly_qtd) {
                $revenue_color = "red";
            } elseif ($monthly_qtd == 0) {
                $revenue_color = "black";
            }

            echo "<td bgColor='$tbl_color' align = 'right'><a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . isset($revenue_color) . "'>$" . number_format($sales_revenue, 0) . "</font></a>";
            echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
            echo "</td>";

            //echo "<td bgColor='$tbl_color' align ='right'>$" . number_format($sales_revenue,0) . "</td>";

            //if ($headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR"){
            //	echo "</td><td bgColor='$tbl_color' align = 'right'>";
            //}else{
            //	echo "</td><td bgColor='$tbl_color' align = 'right'>";
            //}			

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
                if (($sales_revenue * 100 / $monthly_qtd) >= 100) {
                    $color_y_new = "green";
                } elseif (($sales_revenue * 100 / $monthly_qtd) < 100 && $sales_revenue > 0 && $monthly_qtd > 0) {
                    $color_y_new = "red";
                } elseif ($sales_revenue > 0 && $monthly_qtd == 0) {
                    $color_y_new = "green";
                } elseif (($sales_revenue == 0 && $monthly_qtd > 0) || $sales_revenue < 0) {
                    $color_y_new = "red";
                } else {
                    $color_y_new = "black";
                };
            } else {
                if (($sales_revenue * 100 / $monthly_qtd_TD) >= 100) {
                    $color_y_new = "green";
                } elseif (($sales_revenue * 100 / $monthly_qtd_TD) < 100 && $sales_revenue > 0 && $monthly_qtd_TD > 0) {
                    $color_y_new = "red";
                } elseif ($sales_revenue > 0 && $monthly_qtd_TD == 0) {
                    $color_y_new = "green";
                } elseif (($sales_revenue == 0 && $monthly_qtd_TD > 0) || $sales_revenue < 0) {
                    $color_y_new = "red";
                } else {
                    $color_y_new = "black";
                };
            }

            echo "<td bgColor='$tbl_color' style='border-right-style:solid; border-right-width: thin; border-right-color: black;' align = 'right'><font color='" . $color_y_new . "'>";
            if ($headtxt == "LAST WEEK" || $headtxt == "LAST MONTH" || $headtxt == "LAST QUARTER" || $headtxt == "LAST YEAR") {
                if ($sales_revenue > 0 && $monthly_qtd > 0) {
                    echo number_format($sales_revenue * 100 / $monthly_qtd, 2) . "%";
                }
            } else {
                if ($sales_revenue > 0 && $monthly_qtd_TD > 0) {
                    echo number_format($sales_revenue * 100 / $monthly_qtd_TD, 2) . "%";
                }
            }
            echo "</font></td>";

            echo "<td bgColor='$tbl_color' align = 'right'><a href='#' onclick='load_div(" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color='" . $color_y_new . "'>$" . number_format($summtd_SUMPO, 0) . "</font></a>";
            echo "<span id='" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans"] . "</span>";
            echo "</td>";

            if ($headtxt == "LAST YEAR") {
                //echo "<td bgColor='$tbl_color' align = 'right'>$" . number_format($MGArraytmp2["rev_lastyr_tilldt"],0);
                //echo "</font></td>";
            }

            $per_val = number_format((float)str_replace(",", "", number_format($summtd_SUMPO, 0)) * 100 / (float)str_replace(",", "", number_format($sales_revenue, 0)), 2);
            if ($per_val >= 30) {
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

                echo "<td bgColor='$tbl_color' align = 'right'>";
                echo "<a href='#' onclick='load_div(177" . $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summ_ttly_g_profit, 0) . "</font></a>";
                echo "<span id='177" . $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_ttly"] . "</span>";
                echo "</td>";

                //	echo "<td bgColor='$tbl_color' align = 'right'>";
                //	echo "<a href='#' onclick='load_div(277". $unqid . $MGArraytmp2["empid"] . "); return false;'><font color=black>$" . number_format($summtd_ttly_gprofit,0) . "</font></a>";
                //	echo "<span id='277". $unqid . $MGArraytmp2["empid"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $MGArraytmp2["lisoftrans_ttly"] . "</span>";
                //	echo "</td>";
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

    if ($headtxt == "THIS WEEK" || $headtxt == "LAST WEEK" && $po_flg != "yes") {
        echo "<tr><td>";
        echo "<span id='" . isset($div_id_emp_list) . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisoftrans_detail_list . "</table></span>";
        echo "</td></tr>";
    }

    if ($empfilter == "All") {
        echo "</table><br>";
    }
}
?>