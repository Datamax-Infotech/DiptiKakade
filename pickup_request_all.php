<?php
require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();



?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Pick Up Requests</title>
	<style>
		.table_style {}

		.display_maintitle {
			font-size: 13px;
			padding: 3px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			background: #98bcdf;
			border: 1px solid #FFFFFF;
			/*white-space:nowrap;*/
		}

		.display_title {
			font-size: 12px;
			padding: 3px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			background: #ABC5DF;
			/*white-space:nowrap;*/
		}

		.display_table {
			font-size: 11px;
			padding: 3px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
			background: #EBEBEB;
			border: 1px solid #FBFBFB;
		}
	</style>

	<LINK rel='stylesheet' type='text/css' href='one_style.css'>
	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>

<body>
	<?php include("inc/header.php"); ?>
	<div class="main_data_css">
		<div class="dashboard_heading" style="float: left;">
			<div style="float: left;">Pick Up Requests
				<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
					<span class="tooltiptext">
						Pick Up Requests
					</span>
				</div>

				<div style="height: 13px;">&nbsp;</div>
			</div>
		</div>
		<table cellpadding=0 cellspacing=0 width="60%" class="table_style">
			<!--<tr>
		<td colspan="5" align="center"><h3>Pick Up Requests</h3></td>	
		</tr>-->
			<tr style="border-bottom: 1px solid #FFFFFF;">
				<td class="display_maintitle">
					Pick-up Date Request
				</td>
				<td class="display_maintitle">
					Pick-up Date
				</td>
				<td class="display_maintitle">
					Commodity
				</td>
				<td class="display_maintitle">
					Trailer #
				</td>
				<td class="display_maintitle">
					Pickup Status
				</td>
				<td class="display_maintitle">
					Pickup Status
				</td>
				<td class="display_maintitle">
					Pick-up Date
				</td>
				<td class="display_maintitle">
					Pick-up Date Confirmed By
				</td>

			</tr>
			<?php
			//
			$open = "<img src=\"images/circle_open.gif\" border=\"0\">";
			$half = "<img src=\"images/circle__partial.gif\" border=\"0\">";
			$full = "<img src=\"images/complete.jpg\" border=\"0\">";
			//
			$main_sql = "Select loop_warehouse.id as wid,water_loop_transaction.pr_requestdate, water_loop_transaction.id as rec_id, loop_warehouse.company_name, loop_warehouse.b2bid from water_loop_transaction inner join loop_warehouse on loop_warehouse.id = water_loop_transaction.warehouse_id where (water_loop_transaction.pr_requestdate <> '' or water_loop_transaction.pr_requestdate is not null) and loop_warehouse.rec_type = 'Manufacturer'group by warehouse_id";
			db();
			$data_res = db_query($main_sql);
			while ($data = array_shift($data_res)) {
			?>
				<tr>
					<td colspan="8" class="display_title">
						<?php echo get_nickname_val($data["company_name"], $data["b2bid"]); ?>
					</td>
				</tr>

				<?php
				$main_sql1 = "Select * from water_loop_transaction left join supplier_commodity_details on water_loop_transaction.commodity = supplier_commodity_details.id where (water_loop_transaction.pr_requestdate <> '' or water_loop_transaction.pr_requestdate is not null) and warehouse_id='" . $data["wid"] . "'";
				db();
				$data_res1 = db_query($main_sql1);
				while ($tranlist = array_shift($data_res1)) {
					$confirmby = "";
					$confirmemail = "";
					if ($tranlist["pickup_date_confirmed_by"] != '') {
						$confirmby = $tranlist["pickup_date_confirmed_by"];
						$confirmemail = $tranlist["confirmed_by_email"];
					} else {
						$empinit = $tranlist["pa_employee"];
						db_b2b();
						$sql = db_query("SELECT name, email FROM employees WHERE initials = '" . $empinit . "'");
						if (!empty($sql)) {
							$emprow = array_shift($sql);
							$confirmby = $emprow["name"];
							$confirmemail = $emprow["email"];
						}
					}
				?>
					<tr>
						<td width="150px" class="display_table">
							<a href="viewCompany_func_water-mysqli.php?ID=<?php echo $data["b2bid"] ?>&show=watertransactions&warehouse_id=<?php echo $data["wid"]; ?>&b2bid=<?php echo $data["b2bid"]; ?>&rec_id=<?php echo $tranlist["id"]; ?>&rec_type=Manufacturer&proc=View&searchcrit=&display=water_sort" target="_blank">
								<?php echo date("Y-m-d H:m:i", strtotime($tranlist["pr_requestdate_php"])); ?>
							</a>
						</td>
						<td width="150px" class="display_table">
							<?php echo $tranlist["pr_pickupdate"]; ?>
						</td>
						<td width="150px" class="display_table">
							<?php echo $tranlist["commodity"]; ?>
						</td>
						<td width="150px" class="display_table">
							<?php echo $tranlist["dt_trailer"]; ?>
						</td>
						<td width="150px" class="display_table">
							<?php

							if ($tranlist["pa_pickupdate"] != "") {
								echo $full;
							} else {
								echo $open;
							}
							?>
						</td>
						<td width="150px" class="display_table">
							<?php echo $tranlist["pa_pickupdate"]; ?>
						</td>
						<td width="150px" class="display_table">
							<?php echo $confirmby; ?>
						</td>
						<td width="150px" class="display_table">
							<?php echo $confirmemail; ?>
						</td>
					</tr>

			<?php
				}
			}
			?>
		</table>
	</div>
</body>

</html>