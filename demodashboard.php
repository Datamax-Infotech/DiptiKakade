<?php
require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");
?>
<!DOCTYPE html>
<html>
<head>
	<title>UsedCardboardBoxes.com - Demo Dashboard</title>
	<style type="text/css">
		.style7 {
			font-size: x-small;
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

		.style12center {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			text-align: center;
		}

		.style12right {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			text-align: right;
		}

		.style12left {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			text-align: left;
		}

		.style13 {
			font-family: Arial, Helvetica, sans-serif;
		}

		.style14 {
			font-size: x-small;
		}

		.style15 {
			font-size: x-small;
		}

		.style16 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			background-color: #99FF99;
		}

		.style17 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: x-small;
			color: #333333;
			background-color: #99FF99;
		}

		select,
		input {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			color: #000000;
			font-weight: normal;
		}
	</style>
</head>
<script language="JavaScript">
	function FormCheck() {
		if (document.BOLForm.trailer_no.value == "" |
			document.BOLForm.dock.value == "" |
			document.BOLForm.fullname.value == "") {
			alert("Please Complete All Field.\n Need help? Call 1-888-BOXES-88");
			return false;
		}
	}
</SCRIPT>
<script type="text/javascript">
	function update_cart() {
		var x
		var total = 0
		var order_total
		for (x = 1; x <= 10; x++) {
			item_total = document.getElementById("weight_" + x)
			total = total + item_total.value * 1
		}
		order_total = document.getElementById("order_total")
		document.getElementById("order_total").value = total.toFixed(0)
	}
</script>

<body>




	<!---- TABLE TO FORMAT ----------->
	<table>
		<tr>
			<td>
				<img height="80" width="80" src="images/demo.jpg">
			</td>
			<td class="text_align_center" colspan="3">
				<span class="font_face_Ariel font_size_5">
					<b>UsedCardboardBoxes.com<br></b>
					Dashboard Report for:<br>
					<b><i>Demo Company</i></b>
					</i>
			</td>
			<td colspan="20" class="text_align_right">
				<img src="new_interface_help.gif">
			</td>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td class="table_valign_top">


				<!--------------------- BEGIN BOL REQUEST ----------------------------------------------->


				<FORM METHOD="POST" ACTION="BOLpickupsubmit.php">

					<table class="tbl_align_left table_w_450">
						<tr class="text_align_center">
							<td colSpan="10" class="style7">
								<b>Request a Pickup WITH a Bill of Lading (BOL)</b>
							</td>
						</tr>
						<TR>
							<TD class="style17" colspan="3">
								<p class="text_align_center">
									<span class="font_face_Arial">
										<b>
											<span class="font_size_2 ">Trailer Information</span>
										</b>
										<span class="font_size_2">
										</span>
									</span>
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4 text_align_right" colspan="2">
								<b>Trailer Number:</span></b>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<input name="trailer_no" id="trailer_no" size=20 style="float: left">
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4" colspan="2" style="text-align: right">
								<b>Dock:</b>
							</TD>
							<TD class="style12left bg_color_e4e4e4">
								<select name="dock">
									<option value="">--- Please Select ---
									<option value="B">Dock 1
									<option value="C">Dock 2
								</select>
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4" colspan="2" style="text-align: right">
								<b>Seal Number:</b>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<input name="seal_no" size=20 style="float: left"></span>
								<span class="font_size_2 font_face_Arial"> </span>
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4" colspan="2" style="text-align: right">
								<b>Your Name:</b>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR">
								<span class="font_face_Arial">
									<input name="fullname" size=20 style="float: left">
								</span>
								<span class="font_size_2 font_face_Arial"> </span>
							</TD>
						</TR>


						<TR>
							<TD class="style17" colspan="3">
								<p class="text_align_center">
									<span class="font_face_Arial">
										<b>
											<span class="font_size_2 ">Item Information</span>
										</b>
										<span class="font_size_2">
										</span>
									</span>
							</TD>
						</TR>
						<tr>
							<td colspan="3" class="style17 text_align_center">
								<span class="font_size_1">Please check the items below if they are being
									shipped and enter weights below if known.</span>
							</td>
						</tr>
						<TR>
							<TD class="style12center bg_color_e4e4e4">
								<b>Check
							</TD>
							<TD class="style12center bg_color_e4e4e4">
								<b>
									Weight</b>
							</TD>
							<TD class="style12center bg_color_e4e4e4">
								<b>
									Item </b>
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_1" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_1" size=10 style="float: left" id="weight_1" onchange="update_cart()">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<span class="font_size_2 font_face_Arial">
									Pallets of Flattened Boxes<input type="hidden" name="item_1" value="Pallets of Flattened Boxes">
								</span>
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_2" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_2" size=10 style="float: left" id="weight_2" onchange="update_cart()">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<font face="Arial" size="2">Gaylord Boxes Flattened</span><input type="hidden" name="item_2" value="Gaylord Totes">
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_3" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_3" size=10 style="float: left" id="weight_3" onchange="update_cart()">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<font face="Arial" size="2">Gaylord Boxes with Loose Boxes </span><input type="hidden" name="item_3" value="Bales of Supersacks">
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_4" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_4" size=10 style="float: left" id="weight_4" onchange="update_cart()">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<font face="Arial" size="2">Bales OCC</span><input type="hidden" name="item_4" value="Bales of Supersacks">
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_5" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_5" size=10 style="float: left" id="weight_5" onchange="update_cart()">
								</span>
								<font size="2" face="Arial" </span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<font face="Arial" size="2">Bales of Supersacks </span><input type="hidden" name="item_5" value="Bales of OCC">
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_6" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_6" size=10 style="float: left" id="weight_6" onchange="update_cart()">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<font face="Arial" size="2">Bales of Plastic </span><input type="hidden" name="item_6" value="Bales of Plastic">
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_7" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_7" size=10 style="float: left" id="weight_7" onchange="update_cart()">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<span class="font_size_2 font_face_Arial">
									<input type=text size="55" value="Other: Check box &amp; replace text with item description" name="item_7">
								</span>
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_8" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_8" size=10 style="float: left" id="weight_8" onchange="update_cart()">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<span class="font_size_2 font_face_Arial">
									<input type=text size="55" value="Other: Check box &amp; replace text with item description" name="item_8">
								</span>
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_9" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_9" size=10 style="float: left" id="weight_9" onchange="update_cart()">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<span class="font_size_2 font_face_Arial">
									<input type=text size="55" value="Other: Check box &amp; replace text with item description" name="item_9">
								</span>
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"><input name="check_10" type="checkbox" value="1"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="weight_10" size=10 style="float: left" id="weight_10" onchange="update_cart()">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<span class="font_size_2 font_face_Arial">
									<input type=text size="55" value="Other: Check box &amp; replace text with item description" name="item_10">
								</span>
							</TD>
						</TR>
						<TR>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<b>
										<span class="font_size_2"></span>
									</b>
									
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4">
								<span class="font_face_Arial">
									<input name="order_total" size=10 style="float: left">
								</span>
								<span class="font_size_2 font_face_Arial">
								</span>
							</TD>
							<TD class="style12 bg_color_e4e4e4 TBL_ROW_HDR" style="text-align: left">
								<span class="font_size_2 font_face_Arial"><strong>Total Weight</strong></span>
							</TD>
						</TR>
						<TR>
							<TD class="style12center bg_color_e4e4e4" colspan="3">
								<input type=submit value="Generate Bill of Lading and Reqest Trailer">
							</TD>
						</TR>
					</table>
				</form>


				<!---------- END BOL ------------------->

			</td>
			<td class="width_100px">
				&nbsp;
			</td>
			<td class="table_valign_top">


				<!--------------- REQUESTED TABLE ---------------->
				<table cellSpacing="1" cellPadding="1" class="border_0">

					<tr class="text_align_center">
						<td colSpan="10" class="style7">
							<b>View Pickups TO BE PROCESSED</b>
						</td>
					</tr>
					<tr>
						<td style="width: 150" class="style17 text_align_center">
							<b>DATE REQUEST</b>
						</td>
						<td style="width: 150" class="style17 text_align_center">
							<b>TRAILER #</b>
						</td>
						<td class="style5 width_100px text_align_center">
							<b>DOCK</b>
						</td>
						<td class="style5 width_100px text_align_center">
							<b>BOL</b>
						</td>
						<td style="width: 150" class="style16 text_align_center">
							<b>REQUESTED BY</b>
						</td>
					</tr>


					<?php
					$query = "SELECT * FROM loop_transaction WHERE warehouse_id = 92 AND pa_employee LIKE '' AND cp_employee LIKE '' ORDER BY ID ASC";
					db();
					$res = db_query($query);
					while ($row = array_shift($res)) {

					?>
						<tr class="table_valign_middle">
							<td class="style3 bg_color_e4e4e4 text_align_center">
								<?php echo date('m-d-Y'); ?></td>
							<td class="style3 bg_color_e4e4e4 text_align_center">
								<?php echo $row["pr_trailer"]; ?>
							</td>
							<td class="style3 bg_color_e4e4e4 text_align_center">
								<?php echo $row["pr_dock"]; ?>
							</td>
							<td class="style3 bg_color_e4e4e4 text_align_center">
								<?php
								if ($row["bol_filename"] != "")
									echo "<a href=files/" . $row["bol_filename"] . ">View BOL</a>";
								?>
							</td>
							<td class="style3 bg_color_e4e4e4">
								<?php echo $row["pr_requestby"]; ?></td>
			</td>
		</tr>
	<?php
					}
	?>
	</table>
	<!--------------- END REQUESTED TABLE ---------------->
	<br>
	<!--------------- BEGIN IN PROCESS TABLE ---------------->
	<table cellSpacing="1" cellPadding="1" class="border_0">

		<tr class="text_align_center">
			<td colSpan="10" class="style7">
				<b>View Trailers IN PROCESS</b>
			</td>
		</tr>
		<tr>
			<td style="width: 150" class="style17 text_align_center">
				<b>DATE REQUEST</b>
			</td>
			<td style="width: 150" class="style17 text_align_center">
				<b>TRAILER #</b>
			</td>
			<td class="style5 width_100px text_align_center">
				<b>DOCK</b>
			</td>
			<td style="width: 150" class="style16 text_align_center">
				<b>REQUESTED BY</b>
			</td>
		</tr>


		<?php
		$query = "SELECT * FROM loop_transaction WHERE warehouse_id = 92 AND (pa_employee NOT LIKE '' OR bol_employee NOT LIKE '') AND sort_entered = 0 ORDER BY ID ASC";
		db();
		$res = db_query($query);
		while ($row = array_shift($res)) {

		?>
			<tr class="table_valign_middle">
				<td class="style3 bg_color_e4e4e4 text_align_center">
					<?php echo date('m-d-Y'); ?></td>
				<td class="style3 bg_color_e4e4e4 text_align_center">
					<?php echo $row["dt_trailer"]; ?>
				</td>
				<td class="style3 bg_color_e4e4e4 text_align_center">
					<?php echo $row["pr_dock"]; ?>
				</td>
				<td class="style3 bg_color_e4e4e4">
					<?php echo $row["pr_requestby"]; ?></td>
				</td>
			</tr>
		<?php
		}
		?>
	</table>
	<!--------------- END IN PROCESS TABLE ---------------->

	<br>
	<!------------------------- BEGIN PROCESSED TRAILERS ----------------------->
	<form name="rptSearch" action="demotrailerreport.php" method="GET" target="_blank">
		<input type="hidden" name="action" value="run">
		<span class="style2">


			<span class="style13"><span class="style15">

					<table cellSpacing="1" cellPadding="1" class="width_550px border_0">

						<tr class="text_align_center">
							<td colSpan="10" class="style7">
								<b>View Trailers ALREADY PROCESSED (will appear in a new window)</b>
							</td>
						</tr>
						<tr class="text_align_center">
							<td colSpan="10" class="style17">



								<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
								<script LANGUAGE="JavaScript">
									document.write(getCalendarStyles());
								</script>
								<script LANGUAGE="JavaScript">
									var cal1xx = new CalendarPopup("listdiv");
									cal1xx.showNavigationDropdowns();
									var cal2xx = new CalendarPopup("listdiv");
									cal2xx.showNavigationDropdowns();
								</script>
								<?php
								$start_date = isset($_REQUEST["start_date"]) ? strtotime($_REQUEST["start_date"]) : strtotime(date('m/d/Y'));
								$end_date = isset($_REQUEST["end_date"]) ? strtotime($_REQUEST["end_date"]) : strtotime(date('m/d/Y'));
								?>


								<input type="text" name="start_date" size="11" value="<?php echo (isset($_REQUEST["start_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $start_date) : date('m/01/Y') ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;" name="anchor1xx" id="anchor1xx"><img class="border_0" src="images/calendar.jpg"></a>
								<span class="font_family_AHS color_333333 font_size_1">and:
									<input type="text" name="end_date" size="11" value="<?php echo (isset($_REQUEST["end_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $end_date) : date('m/d/Y') ?>"> <a href="#" onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;" name="anchor2xx" id="anchor2xx"><img class="border_0" src="images/calendar.jpg"></a>
									<input type=radio <?php if ($_REQUEST["reportview"] == "1" || $_REQUEST["reportview"] == "") {
															echo "checked";
														} ?> name="reportview" value="1">Show By Weight
									<input type=radio name="reportview" <?php if ($_REQUEST["reportview"] == "0") {
																			echo "checked";
																		} ?> value="0"> Show By Trailer
							</td>
						</tr>
						<tr>
							<TD class="style12center bg_color_e4e4e4">
								&nbsp; <input type="submit" value="Search">
								<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;"></div>
							</td>
						</tr>
					</table>
	</form>

	<!------------------ END PROCESSED TRAILERS -------------------->
	<!------------------------- BEGIN FILES----------------------->
	<form name="berrySearch" action="viewfile.php" method="POST" target="_blank">
		<span class="style2">


			<span class="style13"><span class="style15">

					<table cellSpacing="1" cellPadding="1" class="width_550px border_0">

						<tr class="text_align_center">
							<td colSpan="10" class="style7">
								<b>View Related Files</b>
							</td>
						</tr>
						<tr align="left">
							<td colSpan="10" class="style17">

								<select name="filename" onchange="company(this.value)">
									<option value="0"></option>
									<?php
									$query = "SELECT * FROM loop_files WHERE warehouse_id=15 ORDER BY date DESC";
									db();
									$res = db_query($query);

									while ($row = array_shift($res)) {
										echo "<option value='" . $row["filename"] . "'>" . $row["memo"] . "</option>";
									}
									?>
								</select> <input type=submit value="View File">
							</td>
						</tr>
					</table>
	</form>
	<!------------------ END FILES-------------------->
	<br>

	</td>
	<td class="width_100px">
		&nbsp;
	</td>
	<td class="table_valign_top">
		<!--------------------- BEGIN TRAILER SEARCH  ----------------------------------------------->
		<form name="trlSearch" action="trailersearchresults.php" method="POST" target="_blank">
			<input type=hidden name=action value="run">
			<table cellSpacing="1" cellPadding="1" border="0" width="300">
				<tr class="text_align_center">
					<td colSpan="10" class="style7">
						<b>TRAILER SEARCH</b>
					</td>
				</tr>
				<tr>
					<TD class="style12center bg_color_e4e4e4">
						<input type=text name="trailer_no" size="20"> <input type=submit value="Search">
					</td>
				</tr>

			</table>
		</form>
		<!--------------------- END TRAILER SEARCH  ----------------------------------------------->
		<BR>
		<!--------------------- BEGIN QUICK LINKS  ----------------------------------------------->
		<!----------------------- END QUICK LINKS ------------>
	</td>
	</tr>
	</table>





	<!------------- McCormick Trailer Report ---------------->

	<?php


	if ($_REQUEST["action"] == 'run') {

		$start_date = date('Ymd', $start_date);
		$end_date = date('Ymd', $end_date + 86400);

		if ($start_date > $end_date) {
			echo "<font size=4 color=red>Error: End Date before Start Date</span>";
		}

	?>

		<table width=1400>
			<tr>
				<td>

					<input type=hidden name="reportview" value="<?php echo $_REQUEST["reportview"]; ?>">
					<input type=hidden name="start_date" value="<?php echo $_REQUEST["start_date"]; ?>">
					<input type=hidden name="end_date" value="<?php echo $_REQUEST["end_date"]; ?>">
					<input type=hidden name="action" value="run">
					<table cellSpacing="1" cellPadding="1" width="550" border="0">

						<tr class="text_align_center">
							<td colSpan="10" class="style7">
								<b>McCORMICK TRAILER REPORT FROM <?php echo $_REQUEST["start_date"]; ?> - <?php echo $_REQUEST["end_date"]; ?></b>
							</td>
						</tr>
						<tr>
							<td style="width: 150" class="style17 text_align_center">
								<b>DATE REQUEST</b></span>
							</td>
							<td style="width: 100" class="style17 text_align_center">
								<b>TRAILER #</b></span>
							</td>
							<td style="width: 50" class="style17 text_align_center">
								<b>DOCK</b></span>
							</td>
							<td class="style5" style="width: 150" align="center">
								<b>REQUESTED BY</b>
							</td>
							<td align="middle" style="width: 100" class="style16" align="center">
								<b>VALUE</b>
							</td>
							<td align="middle" style="width: 100" class="style16" align="center">
								<b>STATUS</b>
							</td>
						</tr>


						<?php
						$query = "SELECT * FROM loop_transaction WHERE warehouse_id = 15 AND";
						if ($_REQUEST["start_date"] != "") {
							$query .= " pr_requestdate BETWEEN '" . $_REQUEST["start_date"] . "'";
						}
						if ($_REQUEST["end_date"] != "") {
							$query .= " AND '" . $_REQUEST["end_date"] . "' ORDER BY pr_requestdate DESC";
						}

						$grandtotal = 0;
						db();
						$res = db_query($query);
						while ($row = array_shift($res)) {

						?>
							<tr class="table_valign_middle">
								<td class="style3 bg_color_e4e4e4 text_align_center">
									<?php echo date('m-d-Y', strtotime($row["pr_requestdate"])); ?></td>
								<td class="style3 bg_color_e4e4e4 text_align_center">
									<a href="http://loops.usedcardboardboxes.com/mccormickdashboard.php?action=run&start_date=<?php echo htmlspecialchars($_REQUEST["start_date"]); ?>&end_date=<?php echo $_REQUEST["end_date"]; ?>&reportview=<?php echo $_REQUEST["reportview"]; ?>&trailer=<?php echo $row["id"]; ?>&trlsub=1"><?php echo $row["dt_trailer"]; ?></a>
								</td>
								<td class="style3 bg_color_e4e4e4 text_align_center">
									<?php echo $row["pr_dock"]; ?></td>
								<td class="style3 bg_color_e4e4e4">
									<?php echo $row["pr_requestby"]; ?></td>
								<td bgColor="#e4e4e4" class="style3" align="right">

									<?php
									$gbw = 0;
									$vob = 0;

									$dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_boxes_sort.trans_rec_id = " . $row["id"];
									db();
									$dt_view_res = db_query($dt_view_qry);

									while ($dt_view_row = array_shift($dt_view_res)) {
										if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
											$gbw += $dt_view_row["bweight"] * $dt_view_row["boxgood"];
											$vob += $dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"];
										}
									}

									$voo = 0;

									$dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $row["id"] . " AND loop_boxes.isbox LIKE 'N'";
									db();
									$dt_view_res = db_query($dt_view_qry);

									while ($dt_view_row = array_shift($dt_view_res)) {
										if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
											$voo += $dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"];
										}
									}
									?>
									$<?php
										$grandtotal += number_format($vob + $voo + $row["othercharge"] + $row["freightcharge"], 2);
										echo number_format($vob + $voo + $row["othercharge"] + $row["freightcharge"], 2); ?>



								</td>
								<td class="style3 bg_color_e4e4e4 text_align_center">
									<?php
									if ($row["pmt_entered"] != 0) {
										echo "Paid";
									} elseif ($row["sort_entered"] == 1) {
										echo "Sorted";
									} elseif ($row["pa_employee"] != "") {
										echo "In Process";
									} else {
										echo "Requested";
									}
									?>
								</td>
							</tr>
						<?php
						}
						?>
						<tr>
							<td bgColor="#e4e4e4" class="style3" colspan="4" align="right">
								<b>TOTAL</b></span>
							</td>
							<td bgColor="#e4e4e4" class="style3" align="right">
								<b><?php echo $grandtotal; ?></b></span>
							</td>
							<td class="style3 bg_color_e4e4e4">
							</td>
						</tr>
					</table>
				</td>
				<td class="table_valign_top">
				<?php
			}

			if (isset($_REQUEST["trailer"]) && $_REQUEST["trailer"] > 0) {
				$dt_view_qry = "SELECT * FROM loop_transaction WHERE id = " . $_REQUEST["trailer"];
				db();
				$dt_view_res = db_query($dt_view_qry);

				$dt_view_trl_row = array_shift($dt_view_res)
				?>
					<table cellSpacing="1" cellPadding="1" border="0" width="800">
						<tr class="text_align_center">
							<td class="style7" colspan="10" style="height: 16px"><strong>SORT REPORT FOR TRAILER #<?php echo $dt_view_trl_row["pr_trailer"]; ?></strong></td>
						</tr>
						<tr class="text_align_center">
							<td bgColor="88EEEE" colspan="10" class="style17"><strong>BOXES</strong></td>
						</tr>
						<tr class="table_valign_middle">
							<TD class="style12 bg_color_e4e4e4">Good Boxes</td>
							<TD class="style12 bg_color_e4e4e4">Bad Boxes</td>
							<td bgColor="#e4e4e4" width="350" class="style12">Description</td>
							<TD class="style12 bg_color_e4e4e4">Box Weight</td>
							<TD class="style12 bg_color_e4e4e4">Value Per Box</td>
							<TD class="style12 bg_color_e4e4e4">Value of Boxes</td>
						</tr>
						<?php
						$gb = 0;
						$bb = 0;
						$gbw = 0;
						$vob = 0;


						$dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes.isbox LIKE 'Y' AND loop_boxes_sort.trans_rec_id = " . $_REQUEST["trailer"];
						db();
						$dt_view_res = db_query($dt_view_qry);

						while ($dt_view_row = array_shift($dt_view_res)) {

							if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
						?>
								<tr>
									<td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["boxgood"]; ?></td>
									<td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["boxbad"]; ?></td>
									<td class="style12left bg_color_e4e4e4">
										<?php echo $dt_view_row["blength"]; ?> <?php echo $dt_view_row["blength_frac"]; ?> x
										<?php echo $dt_view_row["bwidth"]; ?> <?php echo $dt_view_row["bwidth_frac"]; ?> x
										<?php echo $dt_view_row["bdepth"]; ?> <?php echo $dt_view_row["bdepth_frac"]; ?>
										<?php echo $dt_view_row["bdescription"]; ?></td>
									<td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["bweight"]; ?></td>
									<td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["sort_boxgoodvalue"]; ?></td>
									<td bgColor="#e4e4e4" class="style12right"><?php echo number_format($dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"], 2); ?></td>
								</tr>


						<?php
								$gb += $dt_view_row["boxgood"];
								$bb += $dt_view_row["boxbad"];
								$gbw += $dt_view_row["bweight"] * $dt_view_row["boxgood"];
								$vob += $dt_view_row["sort_boxgoodvalue"] * $dt_view_row["boxgood"];
							}
						} ?>

						<tr>
							<td bgColor="#e4e4e4" class="style12right"><strong><?php echo $gb; ?></strong></td>
							<td bgColor="#e4e4e4" class="style12right"><strong><?php echo $bb; ?></strong></td>
							<TD class="style12 bg_color_e4e4e4"><strong>BOX TOTALS</strong></td>
							<td bgColor="#e4e4e4" class="style12right"><strong><?php echo number_format($gbw, 2); ?></strong></td>
							<TD class="style12 bg_color_e4e4e4"> </td>
							<td bgColor="#e4e4e4" class="style12right"><strong>$<?php echo number_format($vob, 2); ?></strong></td>
						</tr>

						<tr class="text_align_center">
							<td bgColor="88EEEE" colspan="10" class="style17"><strong>OTHER ITEMS</strong></td>
						</tr>
						<tr class="table_valign_middle">
							<td bgColor="#e4e4e4" colspan="2" class="style12">Quantity</td>
							<td class="style12left bg_color_e4e4e4">Description</td>
							<td bgColor="#e4e4e4" class="style12right">Units</td>
							<td bgColor="#e4e4e4" class="style12right">Value Per Unit</td>
							<td bgColor="#e4e4e4" class="style12right">Total Value</td>
						</tr>
						<?php


						$voo = 0;


						$dt_view_qry = "SELECT * FROM loop_boxes_sort INNER JOIN loop_boxes ON loop_boxes_sort.box_id = loop_boxes.id WHERE loop_boxes_sort.trans_rec_id = " . $_REQUEST["trailer"] . " AND loop_boxes.isbox LIKE 'N'";
						db();
						$dt_view_res = db_query($dt_view_qry);

						while ($dt_view_row = array_shift($dt_view_res)) {

							if ($dt_view_row["boxgood"] > 0 || $dt_view_row["boxbad"] > 0) {
						?>
								<tr>
									<td bgColor="#e4e4e4" colspan="2" class="style12right"><?php echo $dt_view_row["boxgood"]; ?></td>
									<td class="style12left bg_color_e4e4e4">
										<?php echo $dt_view_row["bdescription"]; ?></td>
									<td bgColor="#e4e4e4" class="style12right"><?php echo $dt_view_row["bunit"]; ?></td>
									<td bgColor="#e4e4e4" class="style12right"><?php echo number_format($dt_view_row["sort_boxgoodvalue"], 3); ?></td>
									<td bgColor="#e4e4e4" class="style12right"><?php echo number_format($dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"], 2); ?></td>
								</tr>


						<?php
								$voo += $dt_view_row["boxgood"] * $dt_view_row["sort_boxgoodvalue"];
							}
						} ?>

						<tr>

							<td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>OTHER ITEM TOTALS</strong></td>

							<td bgColor="#e4e4e4" class="style12right"><strong>$<?php echo number_format($voo, 2); ?></strong></td>
						</tr>
						<tr class="text_align_center">
							<td bgColor="88EEEE" colspan="10" class="style17"><strong>TOTALS</strong></td>
						</tr>
						<tr>
							<td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>GROSS EARNINGS</strong></td>
							<td bgColor="#e4e4e4" class="style12right"><strong>$<?php echo number_format($vob + $voo, 2); ?></strong></td>
						</tr>
						<?php if ($dt_view_trl_row["othercharge"] != 0) { ?>
							<tr>
								<td bgColor="#e4e4e4" colspan="5" class="style12right"><strong><?php echo $dt_view_trl_row["otherdetails"]; ?></strong></td>
								<td bgColor="#e4e4e4" class="style12right"><strong>$<?php echo number_format($dt_view_trl_row["othercharge"], 2); ?></strong></td>
							</tr>
						<?php } ?>
						<tr>
							<td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>FREIGHT</strong></td>
							<td bgColor="#e4e4e4" class="style12right"><strong>$<?php echo number_format($dt_view_trl_row["freightcharge"], 2); ?></strong></td>
						</tr>
						<tr>
							<td bgColor="#e4e4e4" colspan="5" class="style12right"><strong>TOTAL EARNED</strong></td>
							<td bgColor="#e4e4e4" class="style12right"><strong>$<?php echo number_format($vob + $voo + $dt_view_trl_row["othercharge"] + $dt_view_trl_row["freightcharge"], 2); ?></strong></td>
						</tr>
					<?php } ?>


				</td>
			</tr>
		</table>
</body>

</html>