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

<table border="0">
    <tr>
        <td valign="top">
            <table cellSpacing="1" cellPadding="1" border="0" width="700" id="table14" class="tablesorter">
                <thead>
                    <tr>
                        <th width='100px' bgColor='#ABC5DF'><u>Employee</u></th>
                        <th width='200px' bgColor='#ABC5DF'>&nbsp;</th>
                        <th width='60px' bgColor='#ABC5DF' align="center"><u>Today</u></th>
                        <th width='60px' bgColor='#ABC5DF' align="center"><u>Yesterday</u></th>
                        <th width='60px' bgColor='#ABC5DF' align="center"><u>Last 7</u></th>
                        <th width='60px' bgColor='#ABC5DF' align="center"><u>Last 30</u></th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    $tot30 = 0;
                    $tot7 = 0;
                    $toty = 0;
                    $totT = 0;

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
                    $lead_30 = 0;
                    $lead_7 = 0;
                    $lead_y = 0;
                    $lead_t = 0;
                    $quote_requests_30 = 0;
                    $quote_requests_7 = 0;
                    $quote_requests_y = 0;
                    $quote_requests_t = 0;
                    $demand_entries_30 = 0;
                    $demand_entries_7 = 0;
                    $demand_entries_y = 0;
                    $demand_entries_t = 0;
                    $fst_time_cust_30 = 0;
                    $fst_time_cust_7 = 0;
                    $fst_time_cust_y = 0;
                    $fst_time_cust_t = 0;

                    if ($dashboard_view == "Operations" || $dashboard_view == "Executive") {
                        $sql = "SELECT * FROM report_activity_tracking ORDER BY unqid";
                    } else {
                        $sql = "SELECT * FROM report_activity_tracking where emp_initials = '" . $initials . "' ORDER BY unqid";
                    }
                    echo " ";

                    db_b2b();
                    $result = db_query($sql);
                    while ($rowemp = array_shift($result)) {
                        $b2bempid = 0;
                        $sql = "SELECT employeeID FROM employees where loopID = '" . $rowemp["emp_id"] . "'";
                        $result_t = db_query($sql);
                        while ($rowtmp = array_shift($result_t)) {
                            $b2bempid = $rowtmp["employeeID"];
                        }

                        echo "<tr><td rowspan='7' bgColor='#E4EAEB' width='100px'>" . $rowemp["emp_name"] . "</td>";

                        echo "<td bgColor='#E4EAEB' width='200px'>Leads</td>";

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["lead_today"];

                        echo "<a target='_blank' href='report_show_list.php?showlead=yes&date_flg=T&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $lead_t += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["lead_yesterday"];

                        echo "<a target='_blank' href='report_show_list.php?showlead=yes&date_flg=Y&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $lead_y += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["lead_last7"];

                        echo "<a target='_blank' href='report_show_list.php?showlead=yes&date_flg=7&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $lead_7 += $contact_act_tmp;

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["lead_last30"];

                        echo "<a target='_blank' href='report_show_list.php?showlead=yes&date_flg=30&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $lead_30 += $contact_act_tmp;

                        echo "</td></tr>";

                        echo "<tr>";
                        echo "<td bgColor='#E4EAEB' width='200px'>Emails</td>";

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["contact_today"];

                        echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=T&in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&crmemp=" . $rowemp["emp_initials"] . "'>" . $contact_act_tmp . "</a>";
                        $contact_act_t += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["contact_yesterday"];

                        echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=Y&in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&crmemp=" . $rowemp["emp_initials"] . "'>" . $contact_act_tmp . "</a>";
                        $contact_act_y += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["contact_last7"];

                        echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=7&in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&crmemp=" . $rowemp["emp_initials"] . "'>" . $contact_act_tmp . "</a>";
                        $contact_act_7 += $contact_act_tmp;

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["contact_last30"];

                        echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=30&in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&crmemp=" . $rowemp["emp_initials"] . "'>" . $contact_act_tmp . "</a>";
                        $contact_act_30 += $contact_act_tmp;

                        echo "</td></tr>";

                        echo "<tr>";
                        echo "<td bgColor='#E4EAEB' width='200px'>Calls</td>";

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["contact_today_phone"];

                        echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=T&phone=y&in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&crmemp=" . $rowemp["emp_initials"] . "'>" . $contact_act_tmp . "</a>";
                        $contact_act_ph_t += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["contact_yesterday_phone"];

                        echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=Y&phone=y&in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&crmemp=" . $rowemp["emp_initials"] . "'>" . $contact_act_tmp . "</a>";
                        $contact_act_ph_y += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["contact_last7_phone"];

                        echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=7&phone=y&in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&crmemp=" . $rowemp["emp_initials"] . "'>" . $contact_act_tmp . "</a>";
                        $contact_act_ph_7 += $contact_act_tmp;

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["contact_last30_phone"];

                        echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=30&phone=y&in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&crmemp=" . $rowemp["emp_initials"] . "'>" . $contact_act_tmp . "</a>";
                        $contact_act_ph_30 += $contact_act_tmp;

                        echo "</td></tr>";

                        //Demand Entries
                        echo "<tr>";
                        echo "<td bgColor='#E4EAEB' width='200px'>Demand Entries</td>";

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["demand_entries_today"];

                        echo "<a target='_blank' href='report_show_list.php?showquote_req=demand_entry&date_flg=T&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $demand_entries_t += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["demand_entries_yesterday"];

                        echo "<a target='_blank' href='report_show_list.php?showquote_req=demand_entry&date_flg=Y&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $demand_entries_y += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["demand_entries_last7"];

                        echo "<a target='_blank' href='report_show_list.php?showquote_req=demand_entry&date_flg=7&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $demand_entries_7 += $contact_act_tmp;

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["demand_entries_last30"];

                        echo "<a target='_blank' href='report_show_list.php?showquote_req=demand_entry&date_flg=30&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $demand_entries_30 += $contact_act_tmp;

                        echo "</td></tr>";

                        //Quote Requests		
                        echo "<tr>";
                        echo "<td bgColor='#E4EAEB' width='200px'>Quote Requests</td>";

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["quote_requests_today"];

                        echo "<a target='_blank' href='report_show_list.php?showquote_req=yes&date_flg=T&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $quote_requests_t += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["quote_requests_yesterday"];

                        echo "<a target='_blank' href='report_show_list.php?showquote_req=yes&date_flg=Y&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $quote_requests_y += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["quote_requests_last7"];

                        echo "<a target='_blank' href='report_show_list.php?showquote_req=yes&date_flg=7&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $quote_requests_7 += $contact_act_tmp;

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["quote_requests_last30"];

                        echo "<a target='_blank' href='report_show_list.php?showquote_req=yes&date_flg=30&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $quote_requests_30 += $contact_act_tmp;

                        echo "</td></tr>";

                        echo "<tr><td bgColor='#E4EAEB' width='200px'>Quotes Sent</td>";

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";

                        echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&b2bempid=$b2bempid&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&flg=Today'>" . $rowemp["quotes_today"] . "</a>";
                        $quotes_sent_t += $rowemp["quotes_today"];
                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&b2bempid=$b2bempid&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&flg=yesterday'>" . $rowemp["quotes_yesterday"] . "</a>";
                        $quotes_sent_y += $rowemp["quotes_yesterday"];
                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&b2bempid=" . (isset($b2bempid) ? $b2bempid : "") . "&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&flg=7days'>" . $rowemp["quotes_last7"] . "</a>";
                        $quotes_sent_7 += $rowemp["quotes_last7"];
                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";

                        echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=" . (isset($in_dt_range) ? $in_dt_range : "") . "&b2bempid=$b2bempid&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&flg=month'>" . $rowemp["quotes_last30"] . "</a>";
                        $quotes_sent_30 += $rowemp["quotes_last30"];
                        echo "</td>";

                        echo "</tr>";

                        //1st time 
                        echo "<tr>";
                        echo "<td bgColor='#E4EAEB' width='200px'>1st Time Customers</td>";

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["fst_time_cust_today"];

                        echo "<a target='_blank' href='report_show_list.php?showfirsttimerec=yes&date_flg=T&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $fst_time_cust_t += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["fst_time_cust_yesterday"];

                        echo "<a target='_blank' href='report_show_list.php?showfirsttimerec=yes&date_flg=Y&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $fst_time_cust_y += $contact_act_tmp;

                        echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["fst_time_cust_last7"];

                        echo "<a target='_blank' href='report_show_list.php?showfirsttimerec=yes&date_flg=7&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $fst_time_cust_7 += $contact_act_tmp;

                        echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
                        $contact_act_tmp = $rowemp["fst_time_cust_last30"];
                        echo "<a target='_blank' href='report_show_list.php?showfirsttimerec=yes&date_flg=30&date_from_val=" . (isset($date_from_val) ? $date_from_val : "") . "&date_to_val=" . (isset($end_Dt) ? $end_Dt : "") . "&crmemp=" . $rowemp["emp_initials"] . "&b2bempid=" . $b2bempid . "'>" . $contact_act_tmp . "</a>";
                        $fst_time_cust_30 += $contact_act_tmp;

                        echo "</td></tr>";
                    }

                    if ($dashboard_view == "Operations" || $dashboard_view == "Executive") {
                        echo "<tr><td bgColor='#E4EAEB' rowspan='7' align=center><b>Total</td><td bgColor='#E4EAEB' width='200px'>Leads</td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $lead_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $lead_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $lead_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $lead_30 . " </strong></td></tr><tr><td bgColor='#E4EAEB' width='200px'>Emails</td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $contact_act_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $contact_act_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $contact_act_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $contact_act_30 . " </strong></td></tr><tr><td bgColor='#E4EAEB' width='200px'>Calls</td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $contact_act_ph_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $contact_act_ph_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $contact_act_ph_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $contact_act_ph_30 . " </strong></td></tr><tr><td bgColor='#E4EAEB' width='200px'>Demand Entries</td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $demand_entries_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $demand_entries_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $demand_entries_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $demand_entries_30 . " </strong></td></tr><tr><td bgColor='#E4EAEB' width='200px'>Quote Requests</td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $quote_requests_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $quote_requests_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $quote_requests_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $quote_requests_30 . " </strong></td></tr><tr><td bgColor='#E4EAEB' width='200px'>Quotes Sent</td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $quotes_sent_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $quotes_sent_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $quotes_sent_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $quotes_sent_30 . " </strong></td></tr><tr><td bgColor='#E4EAEB' width='200px'>1st Time Customers</td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $fst_time_cust_t . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $fst_time_cust_y . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $fst_time_cust_7 . " </strong></td><td bgColor='#E4EAEB' align = right><strong>";
                        echo $fst_time_cust_30 . " </strong></td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";

                    ?>
            </table>

        </td>

    </tr>
</table>