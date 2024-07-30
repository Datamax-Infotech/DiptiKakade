<?php
/*
Name: Z-Cynthia report - B2B Leaderboard
Page created On: 
Last Modified On: 07-31-14
Last Modified By: Mooneem team
Change History:
Date           By      Description
=======================================================================================================
07-31-14       MNM     Added the total row in both the leaderboard
=======================================================================================================
*/


set_time_limit(0);
ini_set('memory_limit', '-1');

if ($_REQUEST["no_sess"] == "yes") {
} else {
	require("inc/header_session.php");
}
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

include_once('ucb_leaderboard_activity_tracker.php');
include_once('ucb_leaderboard_activity_tracker_daily_averages.php');
include_once('ucb_leaderboard_b2ctbl.php');
include_once('ucb_leaderboard_b2cperday_newdaterange.php');
include_once('ucb_leaderboard_leadertbl_purchasing.php');
include_once('ucb_leaderboard_leadertbl.php');
include_once('ucb_leaderboard_leadertbl_pallet.php');
include_once('ucb_leaderboard_leadertbl_ucbzw.php');
include_once('ucb_leaderboard_leadertbl_gmi.php');
include_once('ucb_leaderboard_leadertbl_stretch.php');

db();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>
	<title>UCB Leaderboard</title>

	<meta http-equiv="refresh" content="1800" />
	<link rel="stylesheet" href="sorter/style_rep.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel='stylesheet' type='text/css' href='css/ucb_leaderboard_mgmt_ver2_org.css'>
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

		function expand_activity_tracker(start_Dt, end_Dt, headtxt, tilltoday, currentyr, tbl_head_color, tbl_color, po_flg, unqid, ttylyesno, in_dt_range) {

			document.getElementById("table_b2b_activity_tracker_daily_avg").style.display = "block";
			document.getElementById("table_b2b_activity_tracker_daily_avg").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

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

			xmlhttp.open("GET", "report_daily_chart_mgmt_activity_tracker_daily_avg.php?in_dt_range=" + encodeURIComponent(in_dt_range) + "&start_Dt=" + encodeURIComponent(start_Dt) + "&end_Dt=" + encodeURIComponent(end_Dt) + "&headtxt=" + encodeURIComponent(headtxt) + "&tilltoday=" + encodeURIComponent(tilltoday) + "&currentyr=" + encodeURIComponent(currentyr) + "&tbl_head_color=" + encodeURIComponent(tbl_head_color) + "&tbl_color=" + encodeURIComponent(tbl_color) + "&po_flg=" + encodeURIComponent(po_flg) + "&unqid=" + encodeURIComponent(unqid) + "&ttylyesno=" + encodeURIComponent(ttylyesno), true);
			xmlhttp.send();

		}

		function collapse_activity_tracker() {
			//document.getElementById("table_activity_tracker").style.display = "none";
			document.getElementById("table_b2b_activity_tracker_daily_avg").style.display = "none";
		}

		function expand_b2b_activity_tracker(start_Dt, end_Dt, headtxt, tilltoday, currentyr, tbl_head_color, tbl_color, po_flg, unqid, ttylyesno, in_dt_range) {
			document.getElementById("table_b2b_activity_tracker").style.display = "block";
			document.getElementById("table_b2b_activity_tracker").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

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

			xmlhttp.open("GET", "report_daily_chart_mgmt_activity_tracker.php?in_dt_range=" + encodeURIComponent(in_dt_range) + "&start_Dt=" + encodeURIComponent(start_Dt) + "&end_Dt=" + encodeURIComponent(end_Dt) + "&headtxt=" + encodeURIComponent(headtxt) + "&tilltoday=" + encodeURIComponent(tilltoday) + "&currentyr=" + encodeURIComponent(currentyr) + "&tbl_head_color=" + encodeURIComponent(tbl_head_color) + "&tbl_color=" + encodeURIComponent(tbl_color) + "&po_flg=" + encodeURIComponent(po_flg) + "&unqid=" + encodeURIComponent(unqid) + "&ttylyesno=" + encodeURIComponent(ttylyesno), true);
			xmlhttp.send();
		}

		function collapse_b2b_activity_tracker() {
			document.getElementById("table_b2b_activity_tracker").style.display = "none";
		}

		function load_div(id) {
			
			var element = document.getElementById(id); //replace elementId with your element's Id.
			var rect = element.getBoundingClientRect();
			var elementLeft, elementTop; //x and y
			var scrollTop = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;
			var scrollLeft = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft;
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

	<div id="light" class="white_content"></div>
	<div id="fade" class="black_overlay"></div>

	<?php


	if ($called_from_activity == "") {
	?>
		<br />

		<table border="0">
			<tr>
				<td width="700px" align="center" style="font-size:24pt;">
					<div class="dashboard_heading" style="float: left;">
						<div style="float: left;">UCB Leaderboard</div>
						<div style="float: left;">&nbsp;
							<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
								<span class="tooltiptext">This report shows the user UCB sales data for all departments, including a date range selector a the top.</span>
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
					<table border="0">
						<tr>
							<td colspan="5" align="left">
								<form method="get" name="rpt_leaderboard" action="report_daily_chart_mgmt_ver2_org.php">
									<table border="0">
										<tr>
											<td>Date Range Selector:</td>
											<td>
												From:
												<input type="text" name="date_from" id="date_from" size="10" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>">
												<a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
												<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
												To:
												<input type="text" name="date_to" id="date_to" size="10" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>">
												<a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
												<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>
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
										<td align="left" colspan="15" bgColor='#FFCC66' style="font-size: 10px;font-family: Arial, Helvetica, sans-serif;color: #333333;"><strong>Report Links</strong></td>
									</tr>
									<tr>
										<td align="left" bgColor='#e4e4e4' style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
											<a target="_blank" href='report_daily_chart_mgmt_ver2.php' target="_blank">B2B Leaderboard Report</a>
										</td>
										<td align="left" bgColor='#e4e4e4' style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
											&nbsp;
										</td>
										<td align="left" bgColor='#e4e4e4' style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
											<a target="_blank" href='report_daily_chart_mgmt_ucbzw_gprofit.php' target="_blank">UCBZW Leaderboard Report</a>
										</td>
										<td align="left" bgColor='#e4e4e4' style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
											&nbsp;
										</td>
										<td align="left" bgColor='#e4e4e4' style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;">
											<a target="_blank" href='report_daily_chart_mgmt_pallet.php' target="_blank">PALLET Leaderboard Report</a>
										</td>

									</tr>
								</table>
							</td>
						</tr>
						<?php
						$in_dt_range = "no";
						$date_from_val = date('Y-m-d');
						$date_to_val = date('Y-m-t');

						if ($_GET["date_from"] != "" && $_GET["date_to"] != "") {
							$date_from_val = date("Y-m-d", strtotime($_GET["date_from"]));
							$date_to_val = date("Y-m-d", strtotime($_GET["date_to"]));
							$in_dt_range = "yes";
						}

						if ($in_dt_range == "no") {
						?>
							<tr>
								<td colspan="5">
									<table id="maintbl">
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
										$last_yr = Date('Y', strtotime('-1 year'));
										
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

												<?php leadertbl($st_date, $end_date, "THIS MONTH", 'Y', 'Y', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes");

												B2ctbl_new($st_date, $end_date, "THIS MONTH", 'Y', 'Y', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes");

												leadertbl_GMI($st_date, $end_date, "THIS MONTH", 'Y', 'Y', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes");

												//leadertbl_stretch($st_date, $end_date, "THIS MONTH", 'Y', 'Y','#FFFAD0', '#FFFAD0','no',$unqid, "ttylyes");
												?>
											</td>
											<td width="50">&nbsp;</td>
											<td>
												<?php

												$last_month_st_dt = $st_lastmonth;
												$last_month_end_dt = $end_lastmonth;
												$unqid = $unqid + 1;
												leadertbl($st_lastmonth, $end_lastmonth, "LAST MONTH", 'N', 'N', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes");

												B2ctbl_new($st_lastmonth, $end_lastmonth, "LAST MONTH", 'N', 'N', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes");

												leadertbl_GMI($st_lastmonth, $end_lastmonth, "LAST MONTH", 'N', 'N', '#FFFAD0', '#FFFAD0', 'no', $unqid, "ttylyes");

												//leadertbl_stretch($st_lastmonth, $end_lastmonth, "LAST MONTH", 'N', 'N','#FFFAD0', '#FFFAD0','no',$unqid, "ttylyes");
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
												function getCurrentQuarter(mixed $timestamp = false): float
												{
													if (!$timestamp) $timestamp = time();
													$day = date('n', $timestamp);
													$quarter = ceil($day / 3);
													return $quarter;
												}

												function getPreviousQuarter(mixed $timestamp = false): int
												{
													if (!$timestamp) $timestamp = time();
													//$quarter = getCurrentQuarter($timestamp) - 1;
													$quarter = getCurrentQuarter($timestamp);
													if ($quarter < 0) {
														$quarter = 4;
													}
													return $quarter;
												}

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

												<?php leadertbl($st_date, $end_date, "THIS QUARTER", 'Y', 'Y', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes");
												B2ctbl_new($st_date, $end_date, "THIS QUARTER", 'Y', 'Y', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes");
												leadertbl_GMI($st_date, $end_date, "THIS QUARTER", 'Y', 'Y', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes");

												//leadertbl_stretch($st_date, $end_date, "THIS QUARTER", 'Y', 'Y','#FFF0F0', '#FFF0F0','no',$unqid, "ttylyes");
												?>
											</td>
											<td width="50">&nbsp;</td>
											<td>
												<?php
												$unqid = $unqid + 1;
												$last_qtr_st_dt = $st_lastqtr;
												$last_qtr_end_dt = $end_lastqtr;

												leadertbl($st_lastqtr, $end_lastqtr, "LAST QUARTER", 'N', 'N', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes");
												B2ctbl_new($st_lastqtr, $end_lastqtr, "LAST QUARTER", 'N', 'N', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes");
												leadertbl_GMI($st_lastqtr, $end_lastqtr, "LAST QUARTER", 'N', 'N', '#FFF0F0', '#FFF0F0', 'no', $unqid, "ttylyes");

												//leadertbl_stretch($st_lastqtr, $end_lastqtr, "LAST QUARTER", 'N', 'N','#FFF0F0', '#FFF0F0','no',$unqid, "ttylyes");
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
												leadertbl($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylyes");
												B2ctbl_new($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylyes");
												leadertbl_GMI($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylyes");
												//leadertbl_stretch($st_date, $end_date, "THIS YEAR", 'Y', 'Y', '#EEE8CD', '#EEE8CD','no' , $unqid, "ttylno");
												?>
											</td>
											<td width="50">&nbsp;</td>
											<td>
												<?php
												$last_yr_st_dt = $st_lastyr;
												$last_yr_end_dt = $end_lastyr;
												$unqid = $unqid + 1;
												leadertbl($st_lastyr, $end_lastyr, "LAST YEAR", 'N', 'N', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylyes");
												B2ctbl_new($st_lastyr, $end_lastyr, "LAST YEAR", 'N', 'N', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylyes");
												leadertbl_GMI($st_lastyr, $end_lastyr, "LAST YEAR", 'N', 'N', '#EEE8CD', '#EEE8CD', 'no', $unqid, "ttylyes");
												//leadertbl_stretch($st_lastyr, $end_lastyr, "LAST YEAR", 'N', 'N','#EEE8CD', '#EEE8CD','no',$unqid , "ttylno");
												?>
											</td>
										</tr>

									</table>

								</td>
							</tr>
					</table>
				</td>

				<td valign="top">
					<table border="0" cellSpacing="1" cellPadding="1">
						<tr>
							<td align="left" bgColor='#FFCC66' style="font-size: 10px;font-family: Arial, Helvetica, sans-serif;color: #333333;"><strong>Is This a Friday Meeting? If so, Click The Appropriate Link Below</strong></td>
						</tr>
						<tr>
							<td align="left" bgColor='#e4e4e4' style="font-family: Arial, Helvetica, sans-serif;font-size: 10px;color: #333333;text-align: left;"><a href='report_daily_chart_child.php?flg=week' target="_blank">Weekly Review (Any Friday Which is Not Second Friday of a Month)</a></td>
						</tr>
						<tr>
							<td align="left" bgColor='#e4e4e4' style="font-family: Arial, Helvetica, sans-serif;font-size: 10px; color: #333333;text-align: left;"><a href='report_daily_chart_child.php?flg=month' target="_blank">Monthly Review (Second Friday of a Month)</a></td>
						</tr>
						<tr>
							<td align="left" bgColor='#e4e4e4' style="font-family: Arial, Helvetica, sans-serif;font-size: 10px; color: #333333;text-align: left;"><a href='report_daily_chart_child.php?flg=quarter' target="_blank">Quarterly Review (Second Friday of a New Quarter)</a></td>
						</tr>
						<tr>
							<td align="left" bgColor='#e4e4e4' style="font-family: Arial, Helvetica, sans-serif;font-size: 10px; color: #333333;text-align: left;"><a href='report_daily_chart_child.php?flg=annual' target="_blank">Annual Review (Second Friday of a New Year)</a></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
					</table>
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
								db();

								$sql = "SELECT id, b2b_id ,name, initials as EMPLOYEE, email FROM loop_employees WHERE status = 'Active' and leaderboard = 1 ORDER BY quota DESC";
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

									echo "<tr><td bgColor='#E4EAEB' width='100px'>1st Time Customers</td>";

									echo "<td bgColor='#E4EAEB' align='right' width='60px'>";

									db_b2b();
									if ($in_dt_range != "yes") {
										$sqlT = "SELECT * FROM quote WHERE rep LIKE '" . $b2bempid . "' AND  qstatus !=2 AND quoteDate >= CURDATE()";
									} else {
										$sqlT = "SELECT * FROM quote WHERE rep LIKE '" . $b2bempid . "' AND  qstatus !=2 AND quoteDate = " . $date_from_val;
									}
									$result_new = db_query($sqlT);
									echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=Today'>" . tep_db_num_rows($result_new) . "</a>";
									$quotes_sent_t += tep_db_num_rows($result_new);
									echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
									if ($in_dt_range != "yes") {
										$sqlY = "SELECT * FROM quote WHERE rep LIKE '" . $b2bempid . "' AND  qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND";
									} else {
										$sqlY = "SELECT * FROM quote WHERE rep LIKE '" . $b2bempid . "' AND  qstatus !=2 AND quoteDate BETWEEN " . Date("Y-m-d 23:59:00", strtotime($date_from_val . " -1 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -1 days"));
									}
									$result_new = db_query($sqlY);
									echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=yesterday'>" . tep_db_num_rows($result_new) . "</a>";
									$quotes_sent_y += tep_db_num_rows($result_new);
									echo "</td><td bgColor='#E4EAEB' align='right' width='60px'>";
									if ($in_dt_range != "yes") {
										$sql7 = "SELECT * FROM quote WHERE rep LIKE '" . $b2bempid . "' AND  qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE()";
									} else {
										$sql7 = "SELECT * FROM quote WHERE rep LIKE '" . $b2bempid . "' AND  qstatus !=2 AND quoteDate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -7 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));;
									}
									$result_new = db_query($sql7);
									echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=7days'>" . tep_db_num_rows($result_new) . "</a>";
									$quotes_sent_7 += tep_db_num_rows($result_new);
									echo "<td bgColor='#E4EAEB' align='right' width='60px'>";
									if ($in_dt_range != "yes") {
										$sql30 = "SELECT * FROM quote WHERE rep LIKE '" . $b2bempid . "' AND qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE()";
									} else {
										$sql30 = "SELECT * FROM quote WHERE rep LIKE '" . $b2bempid . "' AND qstatus !=2 AND quoteDate BETWEEN " . Date("Y-m-d 00:00:00", strtotime($date_from_val . " -30 days")) . " AND " . Date("Y-m-d 00:00:00", strtotime($date_from_val));
									}
									$result_new = db_query($sql30);
									echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&flg=month'>" . tep_db_num_rows($result_new) . "</a>";
									$quotes_sent_30 += tep_db_num_rows($result_new);
									echo "</td>";

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
		$unqid = $unqid + 1;
							//In date range
	?>

		<?php leadertbl($date_from_val, $date_to_val, "B2B Leaderboard", 'N', 'N', '#ABC5DF', '#E4EAEB', 'no', $unqid, "ttylno"); ?>

		<?php
				//B2ctbl_new_daterange($date_from_val, $date_to_val , "B2C Leaderboard", 'Y', 'Y','#FFF2D0', '#FFF2D0','no',$unqid);

				B2ctbl_new($date_from_val, $date_to_val, "B2C Leaderboard", 'Y', 'Y', '#ABC5DF', '#E4EAEB', 'no', $unqid, "ttylno");

				leadertbl_GMI($date_from_val, $date_to_val, "GMI Leaderboard", 'N', 'N', '#ABC5DF', '#E4EAEB', 'no', $unqid, "ttylno");

				//leadertbl_stretch($date_from_val, $date_to_val, "B2B Leaderboard", 'N', 'N', '#ABC5DF', '#E4EAEB','no' , $unqid, "ttylno");

				exit;
		?>
		<br />
		<br />

		<table width="900" cellSpacing="1" cellPadding="1" border="0">
			<tr>
				<td colspan=4 class="txtstyle_color" align=center><strong>WHO CLOSED A DEAL Between <?php echo $date_from_val; ?> - <?php echo $date_to_val; ?></strong></td>
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
							$sql = "SELECT *, loop_warehouse.b2bid , loop_warehouse.warehouse_name, loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE loop_transaction_buyer.ignore < 1  and Leaderboard = 'B2B' and ";
							$sql .= " transaction_date BETWEEN '" . date("Y-m-d", strtotime($_GET["date_from"])) . "' and '" . date("Y-m-d", strtotime($_GET["date_to"])) . " 23:59:59' order by transaction_date";

							//echo $sql . "<br>";

							$po_poorderamount_tot = 0;
							$result = db_query($sql);
							while ($row = array_shift($result)) {
								$nickname = "";
								if ($row["b2bid"] > 0) {
									db_b2b();
									$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = ?";
									$result_comp = db_query($sql, array("i"), array($row['b2bid']));
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
											$org_delivery_dt = date('m/d/Y', strtotime($row1["planned_delivery_dt"]));
										}
									} else {
										$org_delivery_dt = date('m/d/Y', strtotime($row['po_delivery_dt']));
									}

									$recent_delivery_dt = date('m/d/Y', strtotime($row['po_delivery_dt']));

									$sql = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = ?";
									$sql_res = db_query($sql, array("i"), array($row['I']));
									$cnt_rw2 = tep_db_num_rows($sql_res);
									if ($cnt_rw2 > 0) {
										while ($row2 = array_shift($sql_res)) {
											$actual_delivery_dt = date('m/d/Y', strtotime($row2["bol_shipment_received_date"]));
										}
									}
								}

								echo "<tr><td bgColor='#E4EAEB' align='left' ><a target='_blank' href='search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["I"] . "&display=buyer_view'>" . $nickname . "</a></td>
				<td align='left'  bgColor='#E4EAEB'>" . $row["po_employee"] . "</td><td bgColor='#E4EAEB'>" . date("m/d/Y", strtotime($row["transaction_date"])) . "</td>
				<td align='right' bgColor='#E4EAEB'>$" . number_format($row["po_poorderamount"], 2) . "</td><td bgColor='#E4EAEB' align='center'>" . $org_delivery_dt . "</td>
				<td bgColor='#E4EAEB' align='center'>" . $recent_delivery_dt . "</td><td bgColor='#E4EAEB' align='center'>" . $actual_delivery_dt . "</td></tr>";
								$po_poorderamount_tot = $po_poorderamount_tot + $row["po_poorderamount"];
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
				<td colspan="3" style="font-size:16pt;"><strong>Transactions Between <?php echo $date_from_val; ?> - <?php echo $date_to_val; ?></strong></td>
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
							$sql .= " where loop_transaction_buyer.ignore = 0  and Leaderboard = 'B2B' and UNIX_TIMESTAMP(str_to_date(bol_shipped_date, '%m/%d/%Y')) >= " . strtotime($_GET["date_from"]) . " and UNIX_TIMESTAMP(str_to_date(bol_shipped_date, '%m/%d/%Y')) <= " . strtotime($_GET["date_to"]) . " order by bol_shipped_date";

							$result = db_query($sql);
							while ($row = array_shift($result)) {
								$nickname = "";
								if ($row["b2bid"] > 0) {
									db_b2b();
									$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = ?";
									$result_comp = db_query($sql, array("i"), array($row['b2bid']));
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

								echo "<tr><td bgColor='#E4EAEB'><a href='search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["trans_rec_id"] . "&display=buyer_ship'>" . $nickname . "</a>";
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
							$sql = "SELECT *, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_bol_files INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id INNER JOIN loop_warehouse ON loop_warehouse.id = loop_bol_files.warehouse_id WHERE loop_transaction_buyer.ignore = 0 and bol_shipped = 1 and bol_shipment_received = 0 AND trans_rec_id > 1000  and Leaderboard = 'B2B' AND ";
							$sql .= " UNIX_TIMESTAMP(str_to_date(bol_shipped_date, '%m/%d/%Y')) >= " . strtotime($_GET["date_from"]) . " and UNIX_TIMESTAMP(str_to_date(bol_shipped_date, '%m/%d/%Y')) <= " . strtotime($_GET["date_to"]) . " order by bol_shipped_date";

							$result = db_query($sql);
							while ($row = array_shift($result)) {
								$nickname = "";
								if ($row["b2bid"] > 0) {
									db_b2b();
									$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = ?";
									$result_comp = db_query($sql, array("i"), array($row['b2bid']));
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

								echo "<tr><td bgColor='#E4EAEB'> <a href='search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["trans_rec_id"] . "&display=buyer_ship'>" . $nickname . "</a></td>";
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
							$sql = "SELECT *, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_bol_files INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id INNER JOIN loop_warehouse ON loop_warehouse.id = loop_bol_files.warehouse_id WHERE Leaderboard = 'B2B' and ";
							$sql .= " loop_transaction_buyer.ignore = 0 and UNIX_TIMESTAMP(str_to_date(bol_shipment_received_date, '%m/%d/%Y')) >= " . strtotime($_GET["date_from"]) . " and UNIX_TIMESTAMP(str_to_date(bol_shipment_received_date, '%m/%d/%Y')) <= " . strtotime($_GET["date_to"]) . " order by bol_shipment_received_date";

							$result = db_query($sql);
							while ($row = array_shift($result)) {
								$nickname = "";
								if ($row["b2bid"] > 0) {
									db_b2b();
									$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = ?";
									$result_comp = db_query($sql, array("i"), array($row['b2bid']));
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

								echo "<tr><td bgColor='#E4EAEB'><a href='search_results.php?warehouse_id=" . $row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $row["warehouse_id"] . "&rec_id=" . $row["trans_rec_id"] . "&display=buyer_received'>" . $nickname . "</a></td>";
								echo "<td bgColor='#E4EAEB'>" . $row["bol_shipment_received_date"] . "</td> </tr>";
							} ?>
						</tbody>
					</table>

				</td>

			</tr>
		</table>

		<br /><br />

		<table border="0">
			<tr>
				<td style="font-size:16pt;" colspan="3"><strong>Activity Tracking <?php echo $date_from_val; ?> - <?php echo $date_to_val; ?></strong></td>
			</tr>
			<tr>
				<td valign="top">
					<table cellSpacing="1" cellPadding="1" border="0" width="600" id="table14" class="tablesorter">
						<thead>
							<tr>
								<th width='170px' bgColor='#ABC5DF' align="center"><u>Employee</u></th>
								<th width='50px' bgColor='#ABC5DF' align="center"><u>Leads</u></th>
								<th width='50px' bgColor='#ABC5DF' align="center"><u>Emails</u></th>
								<th width='50px' bgColor='#ABC5DF' align="center"><u>Calls</u></th>
								<th width='50px' bgColor='#ABC5DF' align="center"><u>Quote Requests</u></th>
								<th width='50px' bgColor='#ABC5DF' align="center"><u>Quotes Sent</u></th>

								<th width='50px' bgColor='#ABC5DF' align="center"><u>Deals</u></th>
								<th width='50px' bgColor='#ABC5DF' align="center"><u>1st Time Customers</u></th>

							</tr>
						</thead>
						<tbody>
							<?php
							$contact_act_30 = 0;
							$contact_act_7 = 0;
							$contact_act_y = 0;
							$contact_act_t = 0;
							$contact_act_30_all = 0;
							$quotes_sent_30 = 0;
							$quotes_sent_7 = 0;
							$quotes_sent_y = 0;
							$quotes_sent_t = 0;
							$contact_act_ph1 = 0;
							$tot30 = 0;
							$tot7 = 0;
							$toty = 0;
							$totT = 0;
							$contact_act_ph_all = 0;

							db();

							$sql = "SELECT id, b2b_id ,name, email, initials as EMPLOYEE FROM loop_employees WHERE status = 'Active' and leaderboard = 1 ORDER BY quota DESC";
							$result = db_query($sql);
							while ($rowemp = array_shift($result)) {
								echo "<tr><td bgColor='#E4EAEB' width='100px'>" . $rowemp["name"] . "</td>";

								db_b2b();
								$contact_act_ph1 = 0;
								$contact_act_30 = 0;
								$sql30 = "SELECT * FROM CRM WHERE duplicate_added_by_system = 0 and type in ('phone', 'email') and EMPLOYEE LIKE '" . $rowemp["EMPLOYEE"] . "' AND timestamp BETWEEN '" . $date_from_val . " 00:00:00' AND '" . $date_to_val . " 00:00:00'";
								$result30 = db_query($sql30);
								while ($rowemp_crm = array_shift($result30)) {
									if ($rowemp_crm["type"] ==  "phone") {
										$contact_act_ph1 = $contact_act_ph1 + 1;
									}
									if ($rowemp_crm["type"] ==  "email") {
										$contact_act_30 = $contact_act_30 + 1;
									}
								}

								db_email();
								$contact_act_tmp = 0;
								$result_crm = db_query("Select count(unqid) as cnt from tblemail where emaildate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($date_from_val)) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($date_to_val)) . "' and fromadd = '" . $rowemp["email"] . "'");
								while ($rowemp_crm = array_shift($result_crm)) {
									$contact_act_tmp = $rowemp_crm["cnt"];
								}
								$contact_act_tmp =  $contact_act_tmp + $contact_act_30;
								$contact_act_30_all = $contact_act_30_all + $contact_act_tmp;
								$contact_act_ph_all = $contact_act_ph_all + $contact_act_ph1;

								echo "<td bgColor='#E4EAEB' align='right'>";
								echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=b2bl&in_dt_range=$in_dt_range&date_from_val=$date_from_val&date_to_val=$date_to_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_tmp . "</a>";
								echo "</td>";
								echo "<td bgColor='#E4EAEB' align='right'>";
								echo "<a target='_blank' href='report_daily_chart_crm_list.php?CRMday=b2bl&phone=y&&in_dt_range=$in_dt_range&date_from_val=$date_from_val&date_to_val=$date_to_val&crmemp=" . $rowemp["EMPLOYEE"] . "'>" . $contact_act_ph1 . "</a>";
								echo "</td>";

								$b2bempid = 0;
								db_b2b();
								$sql = "SELECT employeeID FROM employees where employeeID = ?";
								$result_t = db_query($sql, array("i"), array($rowemp['b2b_id']));
								while ($rowtmp = array_shift($result_t)) {
									$b2bempid = $rowtmp["employeeID"];
								}

								echo "<td bgColor='#E4EAEB' align='right' >";
								$sql30 = "SELECT * FROM quote WHERE rep LIKE '" . $b2bempid . "' AND qstatus !=2 AND quoteDate BETWEEN '" . $date_from_val . " 00:00:00' AND '" . $date_to_val . " 00:00:00'";
								$result30 = db_query($sql30);
								echo "<a target='_blank' href='report_show_open_quotes.php?in_dt_range=$in_dt_range&b2bempid=$b2bempid&date_from_val=$date_from_val&date_to_val=$date_to_val&flg=b2bl'>" . tep_db_num_rows($result30) . "</a>";
								//echo tep_db_num_rows($result30);
								$quotes_sent_30 += tep_db_num_rows($result30);
								echo "</td>";

								echo "</tr>";
							}

							echo "<tr><td bgColor='#E4EAEB' align = right><b>Total</td><td bgColor='#E4EAEB' align = right><b>";
							echo $contact_act_30_all . " </b></td><td bgColor='#E4EAEB' align = right><b>" . $contact_act_ph_all . " </b></td><td bgColor='#E4EAEB' align = right><b>";
							echo $quotes_sent_30 . " </b></td>";
							echo "</tr>";

							echo "</tbody>";

							?>
					</table>

				</td>

			</tr>
		</table>

<?php }
					}
?>

</body>

</html>