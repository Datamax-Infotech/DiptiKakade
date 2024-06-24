<style>
	select.select_box_fix_width {
		width: 12%;
		overflow-x: auto;
	}
</style>
<?php


// ini_set("display_errors", "1");

// error_reporting(E_ERROR);

session_start();
if ($_REQUEST["no_sess"] == "yes") {
} else {
	//require("inc/header_session.php");
}


require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
require("inc/functions_mysqli.php");
// require("function-dashboard-newlinks.php");
require("leadertbl_sales_quota_history.php");

$box_type = "";
$filter_availability = "";
function Warehouse_Fullness_Cal(int $warehouseid_selected): float
{
	$space_taken_by_item_final = 0;

	$box_type_str_arr = array("'Gaylord','GaylordUCB', 'PresoldGaylord', 'Loop'", "'LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'", "'SupersackUCB','SupersacknonUCB'", "'DrumBarrelUCB','DrumBarrelnonUCB'", "'PalletsUCB','PalletsnonUCB'", "'Recycling','Other','Waste-to-Energy'");
	foreach ($box_type_str_arr as $box_type_str_arr_tmp) {

		if ($warehouseid_selected == 18 || $warehouseid_selected == 556 || $warehouseid_selected == 925 || $warehouseid_selected == 2563 || $warehouseid_selected == 1115) {
			$dt_view_qry = "SELECT *,LWH, CONVERT(trim(SUBSTRING_INDEX(`LWH`, 'x', -1)) ,UNSIGNED INTEGER) AS `ht` from tmp_inventory_list_set2_condition2 where wid = " .  $warehouseid_selected . " and type_ofbox in (" . $box_type_str_arr_tmp . ") order by ht, description";
			db_b2b();
			$dt_view_res = db_query($dt_view_qry);

			$tmpwarenm = "";
			$tmp_noofpallet = 0;
			$ware_house_boxdraw = "";
			while ($dt_view_row = array_shift($dt_view_res)) {

				$boxqry = "select bpallet_l, bpallet_w, bpallet_h from loop_boxes where id=" . $dt_view_row["trans_id"];
				//echo $boxqry;
				$cubic_footage_strapped_pallet = 0;
				$space_taken_by_item = 0;
				db();
				$boxres = db_query($boxqry);
				while ($boxrow = array_shift($boxres)) {
					//Calculate strapped_pallets_inv
					if ($dt_view_row["actual"] > 0) {
						$strapped_pallets_inv = $dt_view_row["actual"] / $dt_view_row["per_pallet"];
					} else {
						$strapped_pallets_inv = 0;
					}
					$bpallet_l = $boxrow["bpallet_l"];
					$bpallet_w = $boxrow["bpallet_w"];
					$bpallet_h = $boxrow["bpallet_h"];

					$cubic_footage_strapped_pallet = ($bpallet_l * $bpallet_w * $bpallet_h) / 1728;

					$space_taken_by_item = $strapped_pallets_inv * $cubic_footage_strapped_pallet;
				}

				$space_taken_by_item_final = $space_taken_by_item_final + $space_taken_by_item;
			}
		} else {
			$dt_view_qry = "SELECT * from tmp_inventory_list_warehouse_fullness where wid = '" .  $warehouseid_selected . "' and type_ofbox in (" . $box_type_str_arr_tmp . ")";
			db_b2b();
			$dt_view_res = db_query($dt_view_qry);

			$tmpwarenm = "";
			$tmp_noofpallet = 0;
			$ware_house_boxdraw = "";
			while ($dt_view_row = array_shift($dt_view_res)) {

				$boxqry = "select bpallet_l, bpallet_w, bpallet_h from loop_boxes where id=" . $dt_view_row["trans_id"];
				//echo $boxqry;
				$cubic_footage_strapped_pallet = 0;
				$space_taken_by_item = 0;
				db();
				$boxres = db_query($boxqry);
				while ($boxrow = array_shift($boxres)) {
					//Calculate strapped_pallets_inv
					if ($dt_view_row["actual"] > 0 && $dt_view_row["per_pallet"] > 0) {
						$strapped_pallets_inv = $dt_view_row["actual"] / $dt_view_row["per_pallet"];
					} else {
						$strapped_pallets_inv = 0;
					}
					$bpallet_l = $boxrow["bpallet_l"];
					$bpallet_w = $boxrow["bpallet_w"];
					$bpallet_h = $boxrow["bpallet_h"];

					$cubic_footage_strapped_pallet = ($bpallet_l * $bpallet_w * $bpallet_h) / 1728;

					$space_taken_by_item = $strapped_pallets_inv * $cubic_footage_strapped_pallet;
				}

				$space_taken_by_item_final = $space_taken_by_item_final + $space_taken_by_item;
			}
		}
	}

	$pallet_space = 0;
	$boxqry = "select id, pallet_space from loop_warehouse where id=" . $warehouseid_selected;
	db();
	$boxres = db_query($boxqry);
	while ($boxrow = array_shift($boxres)) {
		$pallet_space = $boxrow["pallet_space"];
	}

	if ($space_taken_by_item_final != 0 && $space_taken_by_item_final != "inf") {
		$wh_fullness = ($space_taken_by_item_final / $pallet_space) * 100;
		$calc = $wh_fullness;
	} else {
		$calc = 0;
	}

	return $calc;
}

$box_type = "";
function getPopupContent(int $warehouseid): string
{
	$retRes = '';
	$retRes .= '<table><tr bgcolor="#ff9900" ><td colspan="13" align="center">Full Loads Available</td></tr>';
	$x = 0;
	$newflg = "no";
	$preordercnt = 1;
	$box_type_str_arr = array("'Gaylord','GaylordUCB', 'PresoldGaylord', 'Loop'", "'LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'", "'SupersackUCB','SupersacknonUCB'", "'DrumBarrelUCB','DrumBarrelnonUCB'", "'PalletsUCB','PalletsnonUCB'", "'Recycling','Other','Waste-to-Energy'");
	$box_type_cnt = 0;
	$retRes .= '<tr bgcolor="#edb558">	
	<td>Rep</td>
	<td>Orders</td>
	<td>Qty Avail Now</td>
	<td>Expected # Loads/ Month</td>
	<td>Next Load Avail Date</td>
	<td>Buy Now, Load Can Ship In</td>
	<td>Per Truckload</td>
	<td>Min. Fob</td>
	<td>B2B ID</td>
	<td>Supplier</td>
	<td>Description</td>
	<td>B2B Status</td>
	<td>Notes</td>
	</tr>';
	foreach ($box_type_str_arr as $box_type_str_arr_tmp) {
		$box_type_cnt = $box_type_cnt + 1;
		if ($box_type_cnt == 1) {
			$box_type = "Gaylord";
		}
		if ($box_type_cnt == 2) {
			$box_type = "Shipping Boxes";
		}
		if ($box_type_cnt == 3) {
			$box_type = "Supersacks";
		}
		if ($box_type_cnt == 4) {
			$box_type = "Drums/Barrels/IBCs";
		}
		if ($box_type_cnt == 5) {
			$box_type = "Pallets";
		}
		if ($box_type_cnt == 6) {
			$box_type = "Recycling+Other";
		}
		$retRes .= '<tr bgcolor="#edb558" align="center">
			<td colspan="13"><b>' . strtoupper($box_type) . '</b></td>
		</tr>';
		$warehouseid_selected = $warehouseid;
		if ($warehouseid_selected == 18 || $warehouseid_selected == 556 || $warehouseid_selected == 925 || $warehouseid_selected == 2563 || $warehouseid_selected == 1115) {
			$dt_view_qry = "SELECT *,LWH, CONVERT(trim(SUBSTRING_INDEX(`LWH`, 'x', -1)) ,UNSIGNED INTEGER) AS `ht` 
		from tmp_inventory_list_set2_condition2 
		where wid = " .  $warehouseid_selected . " 
		and actual >= per_trailer
		and per_trailer > 0
		and type_ofbox in (" . $box_type_str_arr_tmp . ") 
		order by ht, description";
		} else {
			$dt_view_qry = "SELECT * 
		from tmp_inventory_list_warehouse_fullness 
		where wid = " .  $warehouseid_selected . " 
		and actual >= per_trailer
		and per_trailer > 0
		and type_ofbox in (" . $box_type_str_arr_tmp . ") 
		";
		}
		db_b2b();
		$dt_view_res = db_query($dt_view_qry);
		$tmpwarenm = "";
		$tmp_noofpallet = 0;
		$ware_house_boxdraw = "";
		$num_rows = tep_db_num_rows($dt_view_res);
		$w_h_cnt = 0;
		while ($dt_view_row = array_shift($dt_view_res)) {
			//echo $num_rows ."nyn <pre>"; print_r($dt_view_row); echo "</pre>";
			$b2b_cost = $dt_view_row["cost"];
			$vendor_name = $dt_view_row["vendor"];
			$sales_order_qty = $dt_view_row["sales_order_qty"];

			/**Get b2b Id depends on trans_id**/
			$sqlGetB2b = "SELECT * FROM loop_boxes WHERE id = '" . $dt_view_row["trans_id"] . "'";
			db();
			$sqlGetB2bRes = db_query($sqlGetB2b);
			while ($sqlGetB2bRow = array_shift($sqlGetB2bRes)) {
				$b2b_id                 = $sqlGetB2bRow["b2b_id"];
				$txt_after_po           = $sqlGetB2bRow["after_po"];
				$boxes_per_trailer      = $sqlGetB2bRow["boxes_per_trailer"];
				$expectedLoadsPerMo     = $sqlGetB2bRow["expected_loads_per_mo"];
				$lead_time              = $sqlGetB2bRow["lead_time"];
				$nextLoadAvailableDate  = $sqlGetB2bRow["next_load_available_date"];
				$rec_found_box = "n";
				$after_po_val_tmp = 0;
				$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $dt_view_row["trans_id"] . " order by warehouse, type_ofbox, Description";
				db_b2b();
				$dt_view_res_box = db_query($dt_view_qry);
				while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
					$rec_found_box = "y";
					$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
				}
				db();
				if ($rec_found_box == "n") {
					//$txt_after_po = $myrow["after_po"];
					$txt_after_po = $sqlGetB2bRow["after_po"];
				} else {
					$txt_after_po = $after_po_val_tmp;
				}
				/**Buy Now, Load Can Ship In**/
				if ($nextLoadAvailableDate != "" && $nextLoadAvailableDate != "0000-00-00") {
					$now_date = time(); // or your date as well
					$next_load_date = strtotime($nextLoadAvailableDate);
					$datediff = $next_load_date - $now_date;
					$no_of_loaddays = round($datediff / (60 * 60 * 24));

					if ($no_of_loaddays < $lead_time) {
						if ($lead_time > 1) {
							$estimated_next_load = "<font color=green>" . $lead_time . " Days</font>";
						} else {
							$estimated_next_load = "<font color=green>" . $lead_time . " Day</font>";
						}
					} else {
						if ($no_of_loaddays == -0) {
							$estimated_next_load = "<font color=green>0 Day</font>";
						} else {
							$estimated_next_load = "<font color=green>" . $no_of_loaddays . " Days</font>";
						}
					}
				} else {
					if ($txt_after_po >= $boxes_per_trailer) {
						if ($lead_time == 0) {
							$estimated_next_load = "<font color=green>Now</font>";
						}

						if ($lead_time >= 1) {
							$estimated_next_load = "<font color=green>" . $lead_time . " Day</font>";
						}
					} else {
						if (($expectedLoadsPerMo <= 0) && ($txt_after_po < $boxes_per_trailer)) {
							$estimated_next_load = "<font color=red>Never (sell the " . $txt_after_po . ")</font>";
						} else {
							$estimated_next_load = ceil((((($txt_after_po / $boxes_per_trailer) * -1) + 1) / $expectedLoadsPerMo) * 4) . " Weeks";
						}
					}
				}

				$estimated_next_load = $sqlGetB2bRow["buy_now_load_can_ship_in"];
			}

			$sql_b2b = "SELECT * FROM inventory WHERE ID = " . isset($b2b_id);
			db_b2b();
			$result_b2b = db_query($sql_b2b);
			$myrowsel_b2b = array_shift($result_b2b);
			$supplier_owner     = $myrowsel_b2b["supplier_owner"];
			$b2b_status         = $myrowsel_b2b["b2b_status"];
			$vendor_b2b_rescue  = $myrowsel_b2b["vendor_b2b_rescue"];
			$notes              = $myrowsel_b2b["notes"];
			$b2b_ulineDollar   = round($myrowsel_b2b["ulineDollar"]);
			$b2b_ulineCents    = $myrowsel_b2b["ulineCents"];
			$b2b_fob            = $b2b_ulineDollar + $b2b_ulineCents;
			$b2b_fob            = "$" . number_format($b2b_fob, 2);

			$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
			db();
			$query = db_query($q1);
			while ($fetch = array_shift($query)) {
				$supplier = get_nickname_val($fetch['company_name'], $fetch["b2bid"]);
			}
			$viewLink = "";
			$viewLink_str = "";
			if ($vendor_b2b_rescue > 0) {
				$dt_so_item = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
				$dt_so_item .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
				$dt_so_item .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
				$dt_so_item .= " WHERE loop_salesorders.box_id = " . $dt_view_row["trans_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
				db();
				$dt_res_so_item = db_query($dt_so_item);
				while ($so_item_row = array_shift($dt_res_so_item)) {
					if ($so_item_row["sumqty"] > 0) {
						$sales_order_qty = $so_item_row["sumqty"];
					}
				}
				if ($sales_order_qty > 0) {
					$w_h_cnt = $w_h_cnt + 1;

					$viewLink = "<a href='#' onclick='display_preoder_sel(" . $w_h_cnt . ", " . $dt_view_row["trans_id"] . ", 1); return false;'>View</a>";
					$viewLink_str = "<tr><td colspan='13'><div id='div_warehouse_fullness" . $w_h_cnt . "'></div></td></tr>";
				}
			}

			$comqry1 = "select employeeID,employees.name as empname, employees.initials from employees where employeeID='" . $supplier_owner . "'";
			db_b2b();
			$comres1 = db_query($comqry1);
			while ($comrow1 = array_shift($comres1)) {
				$supplier_owner_name = $comrow1["initials"];
			}

			$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
			db();
			$st_res = db_query($st_query);
			$st_row = array_shift($st_res);

			if ($num_rows > 0) {
				$retRes .= '<tr bgcolor="#e4e4e4">    
				<td>' . isset($supplier_owner_name) . '</td>
				<td>' . $viewLink . '</td>
				<td>' . isset($txt_after_po) . '</td>
				<td>' . isset($expectedLoadsPerMo) . '</td>
				<td>' . isset($nextLoadAvailableDate) . '</td>
				<td>' . isset($estimated_next_load) . '</td>
				<td>' . number_format($dt_view_row["per_trailer"], 0) . '</td>
				<td>' . $b2b_fob . '</td>
				<td>' . isset($b2b_id) . '</td>
				<td>' . isset($supplier) . '</td>
				<td><a target="_blank" href="manage_box_b2bloop.php?id=' . $dt_view_row["trans_id"] . '&proc=View">' . $dt_view_row["Description"] . '</a></td>
				<td>' . $st_row["box_status"] . '</td>
				<td>' . $notes . '</td>
			</tr>' . $viewLink_str;
			} else {
				$retRes .= '<tr><td colspan="13">No records</td></tr>';
			}
		}
	}
	$retRes .= '</table>';
	return $retRes;
}

if ($_REQUEST["show"] == "inventory_new") {
?>
	<style type="text/css">
		.main_data_css {
			margin: 0 auto;
			width: 100%;
			height: auto;
			clear: both !important;
			padding-top: 35px;
			margin-left: 10px;
			margin-right: 10px;
		}

		.black_overlay {
			display: none;
			position: absolute;
			top: 0%;
			left: 0%;
			width: 100%;
			height: 100%;
			background-color: gray;
			z-index: 1001;
			-moz-opacity: 0.8;
			opacity: .80;
			filter: alpha(opacity=80);
		}

		.white_content {
			display: none;
			position: absolute;
			top: 5%;
			left: 10%;
			width: 60%;
			height: 90%;
			padding: 16px;
			border: 1px solid gray;
			background-color: white;
			z-index: 1002;
			overflow: auto;
		}
	</style>
	<title>Dashboard</title>

	<?php include("inc/header.php"); ?>
	<br><br>
	<div class="main_data_css">
		<?php
		db();
		$query = "SELECT report_name, report_cache_str, sync_time from reports_cache where report_name = 'dash_inventory_new'";
		$res = db_query($query);
		while ($row = array_shift($res)) {
			echo "<span style='font-size:14pt;'><i>Data last updated: " . timeAgo(date("m/d/Y H:i:s", strtotime($row["sync_time"]))) . " (updates every 5 min)</i></span>";

			echo "&nbsp;&nbsp;<a href='dashboardnew.php?show=inventory_new_org&no_sess=no'>Click here to get latest report output</a>";
			echo "&nbsp;&nbsp;OR&nbsp;&nbsp;<a href='cron_dash_inventory.php?showrep=yes'>Re-Run the Cron Job</a>";

			echo $row["report_cache_str"];
		}
		?>
	</div>
<?php
	exit;
}

$wid = "";
$transid = "";
$eid = $_COOKIE['b2b_id']; // this is the b2b.employees ID number, not the loop_employees ID number
if ($eid == 0) {
	$eid = 35;
}
if ($eid == 22) {
	$eid = 39;
}
if ($eid == 160) {
	$eid = 39;
}
//$eid=9 of WS 35 - Zac; 39 Jb
$flag_assignto_viewby = 0; //= 1 means in assignto mode and = 0 means in assign to and viewable mode
$sql = "SELECT flag_assignto_viewby FROM employees where employeeID = '" . $eid . "'";
db_b2b();
$result = db_query($sql);
while ($myrowsel = array_shift($result)) {
	$flag_assignto_viewby = $myrowsel["flag_assignto_viewby"];
}

$flag_assignto_viewby_str = "";
if ($flag_assignto_viewby == 0) {
	$flag_assignto_viewby_str = " OR companyInfo.viewable1=" . $eid . " OR companyInfo.viewable2=" . $eid . " OR companyInfo.viewable3=" . $eid . " OR companyInfo.viewable4=" . $eid . " ";
}

$x = "SELECT * from loop_employees WHERE b2b_id = '" . $eid . "'";
db();
$viewres = db_query($x);
$row = array_shift($viewres);
$tmp_view = $row['views'];
if ($_REQUEST["show"] == "search") {
	if (isset($_REQUEST["chktrash"])) {
		if ($_REQUEST["chktrash"] != "on") {
			$tmp_view = str_replace(",31", "", $tmp_view);
		}
	} else {
		$tmp_view = str_replace(",31", "", $tmp_view);
	}
}
//
$viewin = $pieces = explode(",", $tmp_view);
$initials = $row['initials'];
$user_lvl = $row['level'];
$name = $row['name'];
$commission = $row['commission'];
$dashboard_view = $row['dashboard_view'];
//$viewin = Array (6,47,48,38,3,32,36,51,32,50,43,3,51,24,56,36); //B2B Statuses
$show_number = 250; //number of records to show.
//
$getaccessqry = "SELECT commission_report_access from loop_employees WHERE initials = '" . $initials . "'";
db();
$getaccess = db_query($getaccessqry);
$getaccess_row = array_shift($getaccess);
$commission_access = $getaccess_row["commission_report_access"];

//
if ($_REQUEST["show"] == "search") {
	$searchcrit = $_REQUEST["searchterm"];
	//
	$search_res_sales_str = " ";
	$search_res_sales_loop_str = " ";
	if ($_REQUEST["search_res_sales"] == "Sales") {
		$search_res_sales_str = " and haveNeed = 'Need Boxes' ";
		$search_res_sales_loop_str = " and bs_status = 'Buyer' ";
	}
	if ($_REQUEST["search_res_sales"] == "Rescue") {
		$search_res_sales_str = " and haveNeed = 'Have Boxes' ";
		$search_res_sales_loop_str = " and bs_status = 'Seller' ";
	}

	$chkparentonly = " ";
	if (isset($_REQUEST["chkparentonly"])) {
		if ($_REQUEST["chkparentonly"] == "on") {
			$chkparentonly = " and parent_child = 'Parent' ";
		}
	}
	if ($searchcrit != "" && $_REQUEST["fastsrch"] == true) {
		$x = "Select  companyInfo.id AS I from companyInfo where companyInfo.status IN ( " . showarrays($viewin) .  " ) ";
		//
		if ($_REQUEST["search_by"] != "any") {
			//
			if ($_REQUEST["search_by"] == "companyname") {
				$arrFields = array("nickname", "company", "comp_abbrv");
			}
			if ($_REQUEST["search_by"] == "compcityzip") {
				//echo "IN city zip";
				$arrFields = array("shipCity", "shipZip");
				//$x = $x . " AND companyInfo.name LIKE '" . str_replace("'", "\'" , $_REQUEST["searchterm"]) . "'";
			}
			if ($_REQUEST["search_by"] == "companid") {
				$arrFields = array("ID");
				//$x = $x . " AND companyInfo.ID LIKE '" . str_replace("'", "\'" , $_REQUEST["searchterm"]) . "'";
			}
			if (!empty($arrFields)) {
				if ($_REQUEST["andor"] == "exactmatch") {
					$i = 1;
					$x = $x . " AND ( ";
					foreach ($arrFields as $nm) {
						if ($i == 1) {;
						} else {
							$x = $x . " OR ";
						}
						$x = $x . " companyInfo." . $nm . " = '" . str_replace("'", "\'", $_REQUEST["searchterm"]) . "'";
						$i++;
					}
					$x = $x . " ) ";
				} else {
					$st = explode(' ', $_REQUEST["searchterm"]);
					$x = $x . " AND ( ";
					foreach ($st as $sti) {
						$i = 1;
						$x = $x . " ( ";
						foreach ($arrFields as $nm) {

							if ($i == 1) {;
							} else {
								$x = $x . " OR ";
							}
							$x = $x . " companyInfo." . $nm . " LIKE '%" . str_replace("'", "\'", $sti) . "%'";
							$i++;
						}
						$x = $x . " ) " . $_REQUEST["andor"] . " ";
					}

					if ($_REQUEST["andor"] == "AND") {
						$x = $x . " TRUE ) ";
					} else {
						$x = $x . " FALSE ) ";
					}

					if ($_REQUEST["state"] != "ALL")
						$x = $x . " AND companyInfo.state LIKE '" . $_REQUEST["state"] . "' ";
				}
				$x = $x . $search_res_sales_str . $chkparentonly;
				//
				$x = $x . " GROUP BY companyInfo.id ";
				//echo "<br>".$x."<br>";
				db_b2b();
				$data_res = db_query($x);
				//
				$comp_rows_num = tep_db_num_rows($data_res);
				//echo $comp_rows_num;
				if ($comp_data = array_shift($data_res)) {
					$b2b_comp_id = $comp_data["I"];
				}
				//
				if ($comp_rows_num == 1) {
					$redirect_url = "viewCompany.php?ID=" . isset($b2b_comp_id);
					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					//echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=\"javascript:window.open('" . $redirect_url . "','_blank');\">";
					echo "</noscript>";
					exit;
				} else {
					$redirect_url = "viewCompany.php?ID=" . isset($b2b_comp_id);
				}
			} //End arrayfield
			//
			if ($_REQUEST["search_by"] == "transpoquoteinvid") {
				if ($_REQUEST["search_res_sales"] == "" || $_REQUEST["search_res_sales"] == "Sales") {
					$sql = "SELECT id, inv_number, po_ponumber, warehouse_id, loop_qb_invoice_no, sent_to_supplier_po_no FROM loop_transaction_buyer WHERE id = '" . str_replace("'", "\'", $searchcrit) . "' OR inv_number = '" . str_replace("'", "\'", $searchcrit) . "' OR loop_qb_invoice_no = '" . str_replace("'", "\'", $searchcrit) . "' OR po_ponumber = '" . str_replace("'", "\'", $searchcrit) . "' OR sent_to_supplier_po_no = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					$loop_id_flg = "no";
					$inv_no_flg = "no";
					$po_no_flg = "no";
					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
						if ($searchcrit == $transid) {
							$loop_id_flg = "yes";
						}
						if ($searchcrit == $myrowsel["inv_number"]) {
							$inv_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["loop_qb_invoice_no"]) {
							$inv_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["po_ponumber"]) {
							$po_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["sent_to_supplier_po_no"]) {
							$sent_supplier_po_no_flg = "yes";
						}
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . isset($wid) . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && isset($transid) > 0) {
							if ($myrowsel["bs_status"] == "Buyer") {
								if ($loop_id_flg == "yes" || $po_no_flg == "yes" || isset($sent_supplier_po_no_flg) == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_view";
								}
								if ($inv_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_payment";
								}
							}

							if ($myrowsel["bs_status"] == "Seller") {
								$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_ship";
							}
						}
						//
						if (isset($redirect_url) != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . isset($redirect_url) . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" .
								isset($redirect_url) . "\" />";
							echo "</noscript>";
							exit;
						}
					}

					$sql = "SELECT * FROM loop_transaction WHERE id = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . isset($wid) . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && isset($transid) > 0) {
							$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=seller_view";
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if (isset($redirect_url) != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . isset($redirect_url) . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" .
								isset($redirect_url) . "\" />";
							echo "</noscript>";
							exit;
						}
					}
				}
				//
				//For search based on Quote
				$compid = 0;
				$quote_id = "";
				$sql = "SELECT companyID, ID FROM quote WHERE (ID+3770) = '" . str_replace("'", "\'", $searchcrit) . "'  or poNumber='" . str_replace("'", "\'", $searchcrit) . "' " . $search_res_sales_str . $chkparentonly;
				db_b2b();
				$result = db_query($sql);
				$loop_reccnt = tep_db_num_rows($result);
				if ($myrowsel = array_shift($result)) {
					$compid = $myrowsel["companyID"];
					$quote_id = $myrowsel["ID"];
				}

				if ($loop_reccnt == 1) {
					$redirect_url = "viewCompany.php?ID=" . $compid . "&quoteid=" . $quote_id;

					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "</noscript>";
					exit;
				}
			} //End transaction ID, PO ID, INV ID
			//
			if ($_REQUEST["search_by"] == "transpoquoteinvid") {
				if ($_REQUEST["andor"] == "exactmatch") {
					$ship_to_str = "companyInfo.shipContact= '" . $_REQUEST["searchterm"] . "' OR companyInfo.shipemail = '" . $_REQUEST["searchterm"] . "' OR companyInfo.shipPhone ='" . $_REQUEST["searchterm"] . "' OR companyInfo.shipMobileno ='" . $_REQUEST["searchterm"] . "' OR companyInfo.shipto_main_line_ph = '" . $_REQUEST["searchterm"] . "'";
				} else {
					$ship_to_str = "companyInfo.shipContact LIKE '%" . $_REQUEST["searchterm"] . "%' OR companyInfo.shipemail LIKE '%" . $_REQUEST["searchterm"] . "%' OR companyInfo.shipPhone LIKE '%" . $_REQUEST["searchterm"] . "%' OR companyInfo.shipMobileno LIKE '%" . $_REQUEST["searchterm"] . "%' OR companyInfo.shipto_main_line_ph LIKE '%" . $_REQUEST["searchterm"] . "%'";
				}
				$shipqry = "select ID from companyInfo where " . $ship_to_str . $search_res_sales_str . $chkparentonly . " Group by companyInfo.id ";
				db_b2b();
				$ship_data_res = db_query($shipqry);
				//
				$contact_reccnt = tep_db_num_rows($ship_data_res);
				while ($shipdata = array_shift($ship_data_res)) {
					$b2b_contact_comp_id .= $shipdata["ID"];
				}
				if ($contact_reccnt == 1) {
					$redirect_url = "viewCompany.php?ID=" . isset($b2b_contact_comp_id);

					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "</noscript>";
					exit;
				}
				//
			}
			if ($searchcrit == trim($searchcrit) && strpos($searchcrit, ' ') !== false) {
			} else {
				if ($_REQUEST["chkwater_inv"] == true) {
					//For search based on Quote
					$compid = 0;
					$warehouse_id = 0;
					$sql = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, water_transaction.id as rec_id  from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id WHERE invoice_number = '" . $searchcrit . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);
					if ($myrowsel = array_shift($result)) {
						$compid = $myrowsel["b2bid"];
						$warehouse_id = $myrowsel["wid"];
						$rec_id = $myrowsel["rec_id"];
					}
					//
					if ($loop_reccnt == 1) {
						$redirect_url = "viewCompany-purchasing.php?ID=" . $compid . "&show=watertransactions&company_id=" . $warehouse_id . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . isset($rec_id) . "&display=water_sort";

						echo "<script type=\"text/javascript\">";
						echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
						echo "</script>";
						echo "<noscript>";
						echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
						echo "</noscript>";
						exit;
					}
				} //end water is true
				//
				//sales / purchasing dd
				if ($_REQUEST["search_res_sales"] == "" || $_REQUEST["search_res_sales"] == "Sales") {
					//$sql = "SELECT * FROM loop_transaction_buyer WHERE id = '" .str_replace("'", "\'" , $searchcrit) . "' OR inv_number = '" .str_replace("'", "\'" , $searchcrit) . "' OR po_ponumber = '" .str_replace("'", "\'" , $searchcrit) . "'";
					$sql = "SELECT id, inv_number, po_ponumber, warehouse_id, loop_qb_invoice_no FROM loop_transaction_buyer WHERE id = '" . str_replace("'", "\'", $searchcrit) . "' OR inv_number = '" . str_replace("'", "\'", $searchcrit) . "' OR loop_qb_invoice_no = '" . str_replace("'", "\'", $searchcrit) . "' OR po_ponumber = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					$loop_id_flg = "no";
					$inv_no_flg = "no";
					$po_no_flg = "no";
					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
						if ($searchcrit == $transid) {
							$loop_id_flg = "yes";
						}
						if ($searchcrit == $myrowsel["inv_number"]) {
							$inv_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["loop_qb_invoice_no"]) {
							$inv_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["po_ponumber"]) {
							$po_no_flg = "yes";
						}
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . isset($wid) . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && isset($transid) > 0) {
							if ($myrowsel["bs_status"] == "Buyer") {
								if ($loop_id_flg == "yes" || $po_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_view";
								}
								if ($inv_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_payment";
								}
							}

							if ($myrowsel["bs_status"] == "Seller") {
								$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_ship";
							}
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if (isset($redirect_url) != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . isset($redirect_url) . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" .
								isset($redirect_url) . "\" />";
							echo "</noscript>";
							exit;
						}
					}

					$sql = "SELECT * FROM loop_transaction WHERE id = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . isset($wid) . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && $transid > 0) {
							$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=seller_view";
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if (isset($redirect_url) != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . isset($redirect_url) . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" .
								isset($redirect_url) . "\" />";
							echo "</noscript>";
							exit;
						}
					}
				} //End sales / purchasing dd
				//
				//For search based on Quote
				$compid = 0;
				$quote_id = "";
				$sql = "SELECT companyID, ID FROM quote WHERE (ID+3770) = '" . str_replace("'", "\'", $searchcrit) . "'";
				db_b2b();
				$result = db_query($sql);
				$loop_reccnt = tep_db_num_rows($result);
				if ($myrowsel = array_shift($result)) {
					$compid = $myrowsel["companyID"];
					$quote_id = $myrowsel["ID"];
				}

				if ($loop_reccnt == 1) {
					$redirect_url = "viewCompany.php?ID=" . $compid . "&quoteid=" . $quote_id;

					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "</noscript>";
					exit;
				}
				//
			} //end else searchit
			//
			if ($_REQUEST["andor"] == "exactmatch") {
				$sql = "SELECT * FROM loop_warehouse WHERE (
				 company_name = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_name = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_city = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_state = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_contact = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 company_email = '" . str_replace("'", "\'", $searchcrit) . "' ) $search_res_sales_loop_str";
			} else {
				$sql = "SELECT * FROM loop_warehouse WHERE (
				 company_name like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_name like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_city like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_state like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_contact like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 company_email like '%" . str_replace("'", "\'", $searchcrit) . "%' ) $search_res_sales_loop_str";
			}
			db();
			$result = db_query($sql);
			$loop_reccnt = tep_db_num_rows($result);
			if ($myrowsel = array_shift($result)) {
				if ($loop_reccnt == 1) {
					if ($transid > 0) {
						if ($myrowsel["bs_status"] == "Buyer") {
							$redirect_url = "search_results.php?id=" . $myrowsel["id"] . "&proc=View&searchcrit=&rec_type=&rec_id=$transid&display=buyer_view";
						}

						if ($myrowsel["bs_status"] == "Seller") {
							$redirect_url = "search_results.php?id=" . $myrowsel["id"] . "&proc=View&searchcrit=&rec_type=&rec_id=$transid&display=buyer_ship";
						}
					} else {
						if ($myrowsel["b2bid"] > 0) {
							$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
						}
					}
				}
			}

			if (isset($redirect_url) != "") {
				echo "<script type=\"text/javascript\">";
				echo "window.location.href=\"" . isset($redirect_url) . "\",\"_blank\";";
				echo "</script>";
				echo "<noscript>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=" .
					isset($redirect_url) . "\" />";
				echo "</noscript>";
				exit;
			}
			//

		} else {
			//
			if ($searchcrit == trim($searchcrit) && strpos($searchcrit, ' ') !== false) {
			} else {
				if ($_REQUEST["chkwater_inv"] == true) {
					//For search based on Quote
					$compid = 0;
					$warehouse_id = 0;
					$sql = "SELECT loop_warehouse.b2bid, loop_warehouse.id as wid, water_transaction.id as rec_id  from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id WHERE invoice_number = '" . $searchcrit . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);
					if ($myrowsel = array_shift($result)) {
						$compid = $myrowsel["b2bid"];
						$warehouse_id = $myrowsel["wid"];
						$rec_id = $myrowsel["rec_id"];
					}
					//
					if ($loop_reccnt == 1) {
						$redirect_url = "viewCompany-purchasing.php?ID=" . $compid . "&show=watertransactions&company_id=" . $warehouse_id . "&rec_type=Manufacturer&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . isset($rec_id) . "&display=water_sort";

						echo "<script type=\"text/javascript\">";
						echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
						echo "</script>";
						echo "<noscript>";
						echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
						echo "</noscript>";
						exit;
					}
				} //end water is true
				//
				//sales / purchasing dd
				if ($_REQUEST["search_res_sales"] == "" || $_REQUEST["search_res_sales"] == "Sales") {
					$sql = "SELECT id, inv_number, po_ponumber, warehouse_id, loop_qb_invoice_no FROM loop_transaction_buyer WHERE id = '" . str_replace("'", "\'", $searchcrit) . "' OR inv_number = '" . str_replace("'", "\'", $searchcrit) . "' OR loop_qb_invoice_no = '" . str_replace("'", "\'", $searchcrit) . "' OR po_ponumber = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query($sql);
					$loop_reccnt = tep_db_num_rows($result);

					$loop_id_flg = "no";
					$inv_no_flg = "no";
					$po_no_flg = "no";
					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
						if ($searchcrit == $transid) {
							$loop_id_flg = "yes";
						}
						if ($searchcrit == $myrowsel["inv_number"]) {
							$inv_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["loop_qb_invoice_no"]) {
							$inv_no_flg = "yes";
						}
						if ($searchcrit == $myrowsel["po_ponumber"]) {
							$po_no_flg = "yes";
						}
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . $wid . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && $transid > 0) {
							if ($myrowsel["bs_status"] == "Buyer") {
								if ($loop_id_flg == "yes" || $po_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_view";
								}
								if ($inv_no_flg == "yes") {
									$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_payment";
								}
							}

							if ($myrowsel["bs_status"] == "Seller") {
								$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=buyer_ship";
							}
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if (isset($redirect_url) != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . isset($redirect_url) . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" .
								isset($redirect_url) . "\" />";
							echo "</noscript>";
							exit;
						}
					}

					$sql = "SELECT * FROM loop_transaction WHERE id = '" . str_replace("'", "\'", $searchcrit) . "'";
					db();
					$result = db_query(
						$sql
					);
					$loop_reccnt = tep_db_num_rows($result);

					while ($myrowsel = array_shift($result)) {
						$wid = $myrowsel["warehouse_id"];
						$transid = $myrowsel["id"];
					}

					$sql = "SELECT * FROM loop_warehouse WHERE id = '" . isset($wid) . "'";
					//	echo $sql;
					$redirect_true = "no";
					db();
					$result = db_query($sql);
					if ($myrowsel = array_shift($result)) {
						if ($loop_reccnt == 1 && $transid > 0) {
							$redirect_url = "search_results.php?id=$wid&proc=View&searchcrit=$searchcrit&rec_type=" . $myrowsel["rec_type"] . "&rec_id=$transid&display=seller_view";
						} else {
							if ($myrowsel["b2bid"] > 0) {
								$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
							}
						}

						if (isset($redirect_url) != "") {
							echo "<script type=\"text/javascript\">";
							echo "window.location.href=\"" . isset($redirect_url) . "\",\"_blank\";";
							echo "</script>";
							echo "<noscript>";
							echo "<meta http-equiv=\"refresh\" content=\"0;url=" .
								isset($redirect_url) . "\" />";
							echo "</noscript>";
							exit;
						}
					}
				} //End sales / purchasing dd
				//
				//For search based on Quote
				$compid = 0;
				$quote_id = "";
				$sql = "SELECT companyID, ID FROM quote WHERE (ID+3770) = '" . str_replace("'", "\'", $searchcrit) . "'";
				db_b2b();
				$result = db_query($sql);
				$loop_reccnt = tep_db_num_rows($result);
				if ($myrowsel = array_shift($result)) {
					$compid = $myrowsel["companyID"];
					$quote_id = $myrowsel["ID"];
				}

				if ($loop_reccnt == 1) {
					$redirect_url = "viewCompany.php?ID=" . $compid . "&quoteid=" . $quote_id;

					echo "<script type=\"text/javascript\">";
					echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
					echo "</script>";
					echo "<noscript>";
					echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
					echo "</noscript>";
					exit;
				}
				//
			} //end else
			//For search based company name
			$compid = 0;
			$quote_id = "";
			if ($_REQUEST["andor"] == "exactmatch") {
				$sql = "SELECT ID FROM companyInfo WHERE nickname = '" . str_replace("'", "\'", $searchcrit) . "' $search_res_sales_str $chkparentonly or contact = '" . str_replace("'", "\'", $searchcrit) . "' or email = '" . str_replace("'", "\'", $searchcrit) . "' or phone = '" . str_replace("'", "\'", $searchcrit) . "' or company = '" . str_replace("'", "\'", $searchcrit) . "'  or comp_abbrv = '" . str_replace("'", "\'", $searchcrit) . "'";
			} else {
				$sql = "SELECT ID FROM companyInfo WHERE nickname like '%" . str_replace("'", "\'", $searchcrit) . "%' $search_res_sales_str $chkparentonly or contact like '%" . str_replace("'", "\'", $searchcrit) . "%' or company like '%" . str_replace("'", "\'", $searchcrit) . "%' or comp_abbrv like '%" . str_replace("'", "\'", $searchcrit) . "%'";
			}
			db_b2b();
			$result = db_query($sql);
			$loop_reccnt = tep_db_num_rows($result);
			if ($myrowsel = array_shift($result)) {
				$compid = $myrowsel["ID"];
			}

			if ($loop_reccnt == 1) {
				$redirect_url = "viewCompany.php?ID=" . $compid;
				echo "<script type=\"text/javascript\">";
				echo "window.location.href=\"" . $redirect_url . "\",\"_blank\";";
				echo "</script>";
				echo "<noscript>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirect_url . "\" />";
				echo "</noscript>";
				exit;
			}

			if ($_REQUEST["andor"] == "exactmatch") {
				$sql = "SELECT * FROM loop_warehouse WHERE (
				 company_name = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_name = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_city = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_state = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 warehouse_contact = '" . str_replace("'", "\'", $searchcrit) . "' OR 
				 company_email = '" . str_replace("'", "\'", $searchcrit) . "' ) $search_res_sales_loop_str";
			} else {
				$sql = "SELECT * FROM loop_warehouse WHERE (
				 company_name like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_name like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_city like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_state like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 warehouse_contact like '%" . str_replace("'", "\'", $searchcrit) . "%' OR 
				 company_email like '%" . str_replace("'", "\'", $searchcrit) . "%' ) $search_res_sales_loop_str";
			}
			db();
			$result = db_query($sql);
			$loop_reccnt = tep_db_num_rows($result);
			if ($myrowsel = array_shift($result)) {
				if ($loop_reccnt == 1) {
					if ($transid > 0) {
						if ($myrowsel["bs_status"] == "Buyer") {
							$redirect_url = "search_results.php?id=" . $myrowsel["id"] . "&proc=View&searchcrit=&rec_type=&rec_id=$transid&display=buyer_view";
						}

						if ($myrowsel["bs_status"] == "Seller") {
							$redirect_url = "search_results.php?id=" . $myrowsel["id"] . "&proc=View&searchcrit=&rec_type=&rec_id=$transid&display=buyer_ship";
						}
					} else {
						if ($myrowsel["b2bid"] > 0) {
							$redirect_url = "viewCompany.php?ID=" . $myrowsel["b2bid"];
						}
					}
				}
			}

			if (isset($redirect_url) != "") {
				echo "<script type=\"text/javascript\">";
				echo "window.location.href=\"" . isset($redirect_url) . "\",\"_blank\";";
				echo "</script>";
				echo "<noscript>";
				echo "<meta http-equiv=\"refresh\" content=\"0;url=" .
					isset($redirect_url) . "\" />";
				echo "</noscript>";
				exit;
			}
		}
	}
} //End search

?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title><?php echo $initials; ?> - Dashboard</title>

	<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">
	<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
	<script LANGUAGE="JavaScript">
		document.write(getCalendarStyles());
	</script>
	<script LANGUAGE="JavaScript">
		var cal2xx = new CalendarPopup("listdiv");
		cal2xx.showNavigationDropdowns();
	</script>

	<?php
	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
	?>
	<script type="text/javascript">
		function showcontact_details(compid, search_keyword) {
			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					selectobject = document.getElementById("com_contact" + compid);
					n_left = f_getPosition(selectobject, 'Left');
					n_top = f_getPosition(selectobject, 'Top');

					document.getElementById("light_todo").innerHTML =
						"<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
						xmlhttp.responseText;
					document.getElementById('light_todo').style.display = 'block';

					document.getElementById('light_todo').style.left = (n_left + 50) + 'px';
					document.getElementById('light_todo').style.top = n_top - 40 + 'px';
					document.getElementById('light_todo').style.width = 400 + 'px';
				}
			}

			xmlhttp.open("GET", "dashboard-search-contact.php?compid=" + compid + "&search_keyword=" + encodeURIComponent(
				search_keyword), true);
			xmlhttp.send();
		}

		function inv_summary(inv_summ_text_id, top_header_flg) {
			selectobject = document.getElementById("inv_summ_div");

			n_left = f_getPosition(selectobject, 'Left');
			n_top = f_getPosition(selectobject, 'Top');

			if (top_header_flg == 1) {
				var tabl_head =
					"<tr vAlign='left'>	<td bgColor='#e4e4e4' class='style12'><b>Actual</b></td> <td bgColor='#e4e4e4' class='style12'><b>After PO</b></td>";
				tabl_head = tabl_head +
					"<td bgColor='#e4e4e4' class='style12'><b>Last Month Quantity</b></td> <td bgColor='#e4e4e4' class='style12'><b>Availability</b></td>";
				tabl_head = tabl_head +
					"<td bgColor='#e4e4e4' class='style12' ><font size=1><b>Account Owner</b></font></td><td bgColor='#e4e4e4' class='style12' ><font size=1><b>Supplier</b></font></td><td bgColor='#e4e4e4' class='style12' ><font size=1><b>Ship From</b></font></td>";
				tabl_head = tabl_head +
					"<td bgColor='#e4e4e4' class='style12' width='100px;'><b>LxWxH</b></font></td><td bgColor='#e4e4e4' class='style12left' ><b>Description</b></font></td>";
				tabl_head = tabl_head +
					"<td bgColor='#e4e4e4' class='style12' width='150px;'><b>SKU</b></font></td><td bgColor='#e4e4e4' class='style12' ><b>Per Pallet</b></td>";
				tabl_head = tabl_head +
					"<td bgColor='#e4e4e4' class='style12' ><b>Per Trailer&nbsp;</b></td><td bgColor='#e4e4e4' class='style12' width='70px;'><b>Min FOB&nbsp;</b></td>";
				tabl_head = tabl_head +
					"<td bgColor='#e4e4e4' class='style12' width='70px;'><b>Cost&nbsp;</b></td><td bgColor='#e4e4e4' class='style12' ><b>Update</b></td>";
				tabl_head = tabl_head + "<td bgColor='#e4e4e4'class='style12left' ><b>Notes</b></td></tr>";
			} else {
				var tabl_head = "<tr ><td bgColor='#e4e4e4' class='style12'>";
				tabl_head = tabl_head + "Transaction ID</td><td bgColor='#e4e4e4' class='style12'>";
				tabl_head = tabl_head + "Company Name</td></tr>";

			}

			document.getElementById("light_todo").innerHTML =
				"<a href='javascript:void(0)' onclick=document.getElementById('light_todo').style.display='none';document.getElementById('fade_todo').style.display='none'>Close</a> &nbsp;<center></center><br/><table cellspacing='1' cellpadding='1' border='0'>" +
				tabl_head + document.getElementById(inv_summ_text_id).value + "</table>";
			document.getElementById('light_todo').style.display = 'block';

			document.getElementById('light_todo').style.left = (n_left - 100) + 'px';
			document.getElementById('light_todo').style.top = n_top - 250 + 'px';
			document.getElementById('light_todo').style.width = 1200 + 'px';

			document.getElementById("inv_summ_div").focus();
		}

		function showdealinprocess(emp_list_selected, sort_order_pre, sort) {
			document.getElementById("divdealinprocess").innerHTML =
				"<br/><br/>Loading .....<img src='images/wait_animated.gif' />";

			if (window.XMLHttpRequest) {
				xmlhttp = new XMLHttpRequest();
			} else {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("divdealinprocess").innerHTML = xmlhttp.responseText;
				}
			}

			xmlhttp.open("GET",
				"loop_index_load_tables_dashboard.php?dashboardflg=yes&tablenm=all_inbound&sort_order_pre=" +
				sort_order_pre + "&sort=" + sort + "&emp_list_selected=" + emp_list_selected, true);
			xmlhttp.send();
		}





		function close_div() {
			document.getElementById('light').style.display = 'none';
		}

		function display_preoder_sel(tmpcnt, reccnt, box_id, wid) {

			if (reccnt > 0) {

				if (document.getElementById('inventory_preord_org_top_' + tmpcnt).style.display == 'table-row') {
					document.getElementById('inventory_preord_org_top_' + tmpcnt).style.display = 'none';
				} else {
					document.getElementById('inventory_preord_org_top_' + tmpcnt).style.display = 'table-row';
				}

				document.getElementById("inventory_preord_org_middle_div_" + tmpcnt).innerHTML =
					"<br><br>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("inventory_preord_org_middle_div_" + tmpcnt).innerHTML = xmlhttp
							.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_preorder_childtable.php?box_id=" + box_id + "&wid=" + wid, true);
				xmlhttp.send();

			}
		}



		function f_getPosition(e_elemRef, s_coord) {
			var n_pos = 0,
				n_offset,
				//e_elem = selectobject;
				e_elem = e_elemRef;
			while (e_elem) {
				n_offset = e_elem["offset" + s_coord];
				n_pos += n_offset;
				e_elem = e_elem.offsetParent;

			}
			e_elem = e_elemRef;
			//e_elem = selectobject;
			while (e_elem != document.body) {
				n_offset = e_elem["windows" + s_coord];
				if (n_offset && e_elem.style.overflow == 'windows')
					n_pos -= n_offset;
				e_elem = e_elem.parentNode;
			}

			return n_pos;

		}
	</script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<style>
		.onlytext-link {
			FONT-WEIGHT: bold;
			FONT-SIZE: 8pt;
			COLOR: 006600;
			FONT-FAMILY: Arial;
		}

		table.newlinks tr:nth-child(even) {
			background-color: #e4e4e4;
		}

		table.newlinks tr:nth-child(odd) {
			background-color: #F7F7F7;
		}

		table.newlinks tr td.style12 {
			text-align: left !important;
		}

		.style24 {
			font-size: 14px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
		}

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

		.main_data_css {
			margin: 0 auto;
			width: 100%;
			height: auto;
			clear: both !important;
			padding-top: 35px;
			margin-left: 10px;
			margin-right: 10px;
		}

		.dashboard_heading {
			margin-top: 20px;
			width: 100%;
			font-size: 24px;
			/*font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif!important;*/
			font-family: 'Titillium Web', sans-serif;
			font-weight: 600;
		}

		.newtxttheam_withdot {
			font-family: Arial, Helvetica, sans-serif;
			font-size: xx-small;
			padding: 4px;
			background-color: #e4e4e4;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.newtxttheam_withdot_light {
			font-family: Arial, Helvetica, sans-serif;
			font-size: xx-small;
			padding: 4px;
			background-color: #f4f5ef;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.newtxttheam_withdot_red {
			font-family: Arial, Helvetica, sans-serif;
			font-size: xx-small;
			padding: 4px;
			background-color: red;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.highlight_row {
			background-color: #df2f2f;
		}

		.rec_row {
			background-color: #e4e4e4;
		}

		.viewable_txt {
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
			font-size: 13px;
			color: #666666;
		}

		.viewable_frm {
			border: 1px solid #E0E0E0;
			padding: 0px 10px 4px 10px;
			border-radius: 7px;
		}

		.viewable_dd_style {
			border: 1px solid #ccc !important;
			font-size: 12px;
		}

		.viewable_button {
			background-color: #D4D4D4;
			border: none;
			color: #464646;
			padding: 2px 10px;
			text-decoration: none;
			margin: 4px 2px;
			cursor: pointer;
			border: 1px solid #4E4E4E;
			font-size: 12px;
			font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
		}
	</style>
	<style type="text/css">
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
			left: 12%;
			background: #ffffff;
		}

		span.infotxt span {
			position: absolute;
			left: -9999px;
			margin: 1px 0 0 0px;
			padding: 0px 3px 3px 3px;
			border-style: solid;
			border-color: black;
			border-width: 1px;
		}

		span.infotxt:hover span {
			margin: 1px 0 0 170px;
			background: #ffffff;
			z-index: 6;
		}

		.style12_new1 {
			font-size: small;
			font-family: Arial, Helvetica, sans-serif;
			color: #333333;
			text-align: left;
		}

		.style12_new_top {
			font-size: small;
			font-family: Arial, Helvetica, sans-serif;
			background-color: #FF9900;
			text-align: center;
		}

		.style12_new_center {
			font-size: small;
			font-family: Arial, Helvetica, sans-serif;
			color: #333333;
			text-align: center;
		}

		.style12_new2 {
			font-size: small;
			font-family: Arial, Helvetica, sans-serif;
			color: #333333;
			text-align: right;
		}

		.txtstyle_color {
			font-family: arial;
			font-size: 12;
			height: 16px;
			background: #ABC5DF;
		}

		.header_td_style {
			font-family: arial;
			font-size: 12;
			height: 16px;
			background: #ABC5DF;
		}

		.white_content_search {
			display: none;
			position: absolute;
			padding: 5px;
			border: 1px solid black;
			background-color: #FFF8C6;
			z-index: 1002;
			overflow: auto;
			color: black;
			border-radius: 8px;
			padding: 5px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}

		.black_overlay {
			display: none;
			position: absolute;
		}

		.white_content {
			display: none;
			position: absolute;
			border: 1px solid #909090;
			background-color: white;
			overflow: auto;
			height: 600px;
			width: 850px;
			z-index: 999999;
			margin: 0px 0 0 0px;
			padding: 10px 10px 10px 10px;
			border-color: black;
			/*border-width:2px;*/
			overflow: auto;
			border-radius: 8px;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		}
	</style>

	<script type="text/javascript">
		function ex_emp_status(viewin, eid, show_number, dtrange) {
			var display_div = "StatusesDashboard_div";

			if (document.getElementById(display_div).innerHTML == "") {
				document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

				if (document.getElementById(display_div).style.display == "none") {
					document.getElementById(display_div).style.display = "block";
				} else {
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}

					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("span_emp_status").style.display = "none";
							document.getElementById(display_div).innerHTML = xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("POST", "dash_emp_status.php?viewin=" + viewin + "&eid=" + eid + "&show_number=" +
					show_number + "&dtrange=" + dtrange, true);
				//xmlhttp.open("POST","showStatusesDashboard.php?viewin="+viewin+"&eid="+eid+"&show_number="+show_number+"&dtrange="+dtrange,true);
				xmlhttp.send();
			} else {
				document.getElementById("span_emp_status").style.display = "none";
				document.getElementById(display_div).style.display = "block";
			}
		}

		function colp_emp_status() {
			var display_div = "StatusesDashboard_div";
			document.getElementById(display_div).style.display = "none";
			document.getElementById("span_emp_status").style.display = "block";
		}


		function ex_today_snapshot(initials, dashboard_view) {
			var display_div = "ex_today_snapshot_div";

			if (document.getElementById(display_div).innerHTML == "") {
				document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

				if (document.getElementById(display_div).style.display == "none") {
					document.getElementById(display_div).style.display = "block";
				} else {
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}

					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("span_today_snapshot").style.display = "none";
							document.getElementById(display_div).innerHTML = xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("POST", "dash_today_snapshot.php?initials=" + initials + "&dashboard_view=" + dashboard_view,
					true);
				xmlhttp.send();
			} else {
				document.getElementById("span_close_deal_pipline").style.display = "none";
				document.getElementById(display_div).style.display = "block";
			}
		}

		function colp_today_snapshot() {
			var display_div = "ex_today_snapshot_div";
			document.getElementById(display_div).style.display = "none";
			document.getElementById("span_today_snapshot").style.display = "block";
		}


		function ex_activity_tracking(initials, dashboard_view) {
			var display_div = "activity_tracking_div";

			if (document.getElementById(display_div).innerHTML == "") {
				document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

				if (document.getElementById(display_div).style.display == "none") {
					document.getElementById(display_div).style.display = "block";
				} else {
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}

					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("span_activity_tracking").style.display = "none";
							document.getElementById(display_div).innerHTML = xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("POST", "dash_activity_tracking.php?initials=" + initials + "&dashboard_view=" +
					dashboard_view, true);
				xmlhttp.send();
			} else {
				document.getElementById("span_activity_tracking").style.display = "none";
				document.getElementById(display_div).style.display = "block";
			}
		}

		function colp_activity_tracking() {
			var display_div = "activity_tracking_div";
			document.getElementById(display_div).style.display = "none";
			document.getElementById("span_activity_tracking").style.display = "block";
		}


		function ex_close_deal_pipline(initials, dashboard_view) {
			var display_div = "close_deal_pipline_div";

			if (document.getElementById(display_div).innerHTML == "") {
				document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

				if (document.getElementById(display_div).style.display == "none") {
					document.getElementById(display_div).style.display = "block";
				} else {
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}

					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("span_close_deal_pipline").style.display = "none";
							document.getElementById(display_div).innerHTML = xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("POST", "dash_closed_deal_pipeline.php?initials=" + initials + "&dashboard_view=" +
					dashboard_view, true);
				xmlhttp.send();
			} else {
				document.getElementById("span_close_deal_pipline").style.display = "none";
				document.getElementById(display_div).style.display = "block";
			}
		}

		function colp_close_deal_pipline() {
			var display_div = "close_deal_pipline_div";
			document.getElementById(display_div).style.display = "none";
			document.getElementById("span_close_deal_pipline").style.display = "block";
		}

		function ex_close_deal_pipline_sourcing(initials, dashboard_view) {
			var display_div = "close_deal_pipline_div";

			if (document.getElementById(display_div).innerHTML == "") {
				document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

				if (document.getElementById(display_div).style.display == "none") {
					document.getElementById(display_div).style.display = "block";
				} else {
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}

					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("span_close_deal_pipline").style.display = "none";
							document.getElementById(display_div).innerHTML = xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("POST", "dash_closed_deal_pipeline_sourcing.php?initials=" + initials + "&dashboard_view=" +
					dashboard_view, true);
				xmlhttp.send();
			} else {
				document.getElementById("span_close_deal_pipline").style.display = "none";
				document.getElementById(display_div).style.display = "block";
			}
		}

		function colp_close_deal_pipline_sourcing() {
			var display_div = "close_deal_pipline_div";
			document.getElementById(display_div).style.display = "none";
			document.getElementById("span_close_deal_pipline").style.display = "block";
		}

		function ex_rev_tracker(initials, dashboard_view) {
			var display_div = "rev_tracker_div";

			if (document.getElementById(display_div).innerHTML == "") {
				document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

				if (document.getElementById(display_div).style.display == "none") {
					document.getElementById(display_div).style.display = "block";
				} else {
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}

					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("span_rev_tracker").style.display = "none";
							document.getElementById(display_div).innerHTML = xmlhttp.responseText;
						}
					}
				}

				if (dashboard_view == "Rescue") {
					xmlhttp.open("POST", "dash_revenue_tracker_period_new.php?initials=" + initials + "&dashboard_view=" +
						dashboard_view, true);
				} else {
					//xmlhttp.open("POST","dash_revenue_tracker.php?initials="+initials+"&dashboard_view="+dashboard_view,true);
					xmlhttp.open("POST", "dash_revenue_tracker_new.php?initials=" + initials + "&dashboard_view=" +
						dashboard_view, true);
				}
				xmlhttp.send();
			} else {
				document.getElementById("span_rev_tracker").style.display = "none";
				document.getElementById(display_div).style.display = "block";
			}
		}

		function colp_rev_tracker() {
			var display_div = "rev_tracker_div";
			document.getElementById(display_div).style.display = "none";
			document.getElementById("span_rev_tracker").style.display = "block";
		}


		//New Deal Spin expand and collaps
		function ex_dash_deal_spin(emp_initial, dashboardview) {
			//alert(dashboardview);
			var display_div = "deal_spin_display";
			if (document.getElementById(display_div).innerHTML == "") {
				document.getElementById(display_div).innerHTML = "<br/>Loading .....<img src='images/wait_animated.gif' />";

				if (document.getElementById(display_div).style.display == "none") {
					document.getElementById(display_div).style.display = "block";
				} else {
					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}

					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							document.getElementById("hide_tr_spin").style.display = "none";
							document.getElementById(display_div).innerHTML = xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("POST", "dash_new_deal_spin.php?initial=" + emp_initial + "&dashboardview=" + dashboardview,
					true);
				xmlhttp.send();
			} else {
				document.getElementById("hide_tr_spin").style.display = "none";
				document.getElementById(display_div).style.display = "block";
			}
		}

		function colp_dash_deal_spin() {
			var display_div = "deal_spin_display";
			document.getElementById(display_div).style.display = "none";
			document.getElementById("hide_tr_spin").style.display = "block";
		}
	</script>
</head>

<body>
	<script type="text/javascript" src="wz_tooltip.js"></script>
	<div id="light_todo" class="white_content"></div>
	<div id="fade_todo" class="black_overlay"></div>
	<?php
	//------------------------------------------------------------------------------
	//New match inventory
	function showinventory_fordashboard_invmatch_new(string|int $g_timing, string|int $sort_g_tool, string|int $sort_g_view): void
	{
	?>
		<script>
			function displayboxdata_invnew(colid, sortflg, box_type_cnt) {
				document.getElementById("btype" + box_type_cnt).innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				//
				var sort_g_view = document.getElementById("sort_g_view").value;
				var sort_g_tool = document.getElementById("sort_g_tool").value;
				var g_timing = document.getElementById("g_timing").value;
				//
				var fld = document.getElementById('search_tag');
				var values = [];
				for (var i = 0; i < fld.options.length; i++) {
					if (fld.options[i].selected) {
						values.push(fld.options[i].value);
					}
				}
				//

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						//alert(xmlhttp.responseText);
						document.getElementById("btype" + box_type_cnt).innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "dashboard_inv_sort.php?colid=" + colid + "&sortflg=" + sortflg + "&sort_g_view=" +
					sort_g_view + "&sort_g_tool=" + sort_g_tool + "&g_timing=" + g_timing + "&box_type_cnt=" +
					box_type_cnt + "&search_tag=" + values, true);
				xmlhttp.send();
			}

			function display_preoder_sel(tmpcnt, box_id, warehouse_fullness_flg = 0) {
				if (warehouse_fullness_flg == 0) {
					if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') {
						document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'none';
					} else {
						document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'table-row';
					}

					document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
						"<br><br>Loading .....<img src='images/wait_animated.gif' />";
				} else {
					document.getElementById("div_warehouse_fullness" + tmpcnt).innerHTML =
						"<br><br>Loading .....<img src='images/wait_animated.gif' />";
				}

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						if (warehouse_fullness_flg == 0) {
							document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp
								.responseText;
						} else {
							document.getElementById("div_warehouse_fullness" + tmpcnt).innerHTML = xmlhttp.responseText;
						}
					}
				}

				xmlhttp.open("GET", "dashboard_inv_qtyavail.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt, true);
				xmlhttp.send();
			}
		</script>
		<style>
			.popup_qty {
				text-decoration: underline;
				cursor: pointer;
			}

			#loadingDiv {
				position: absolute;
				;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background-color: #000;
			}
		</style>
		<?php
		if (isset($_REQUEST["g_timing"])) {
			$g_timing = $_REQUEST["g_timing"];
		} else {
			$g_timing = $g_timing;
		}
		if (isset($_REQUEST["sort_g_tool"])) {
			$sort_g_tool = $_REQUEST["sort_g_tool"];
		} else {
			$sort_g_tool = $sort_g_tool;
		}
		if (isset($_REQUEST["sort_g_view"])) {
			$sort_g_view = $_REQUEST["sort_g_view"];
		} else {
			$sort_g_view = $sort_g_view;
		}

		//Tag filter
		$filter_tag = "";
		if (isset($_REQUEST["search_tag"]) && ($_REQUEST["search_tag"] != "")) {
			// Retrieving each selected option 
			$total_tag = tep_db_num_rows($_REQUEST["search_tag"]);
			if ($total_tag >= 1) {
				$search_tag_val = "";
				foreach ($_REQUEST["search_tag"] as $tag_val) {
					$search_tag_val .= " tag like '%$tag_val%' or ";
				}
				$search_tags = rtrim($search_tag_val, "or ");

				$filter_tag = " and (" . $search_tags . ")";
			}
		}

		if (isset($_REQUEST["search_tag"]) && ($_REQUEST["search_tag"] == "")) {
			$search_tags = "";
			$filter_tag = "";
		}
		//echo $filter_tag."--in top<br>";

		?>
		<script language="JavaScript" SRC="inc/CalendarPopup.js"></script>
		<script language="JavaScript" SRC="inc/general.js"></script>
		<script language="JavaScript">
			document.write(getCalendarStyles());
		</script>
		<script language="JavaScript">
			var cal1xx = new CalendarPopup("listdiv");
			cal1xx.showNavigationDropdowns();
		</script>
		<script>
			function remove_product_fun(cnt) {
				document.getElementById("inv_child_div" + cnt).innerHTML = "";
			}


			function showfilter_option(cnt) {
				var str = document.getElementById("filter_column" + cnt).value;

				if (str.length == 0) {
					return;
				} else {
					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
						if (this.readyState == 4 && this.status == 200) {
							document.getElementById("filter_sub_option" + cnt).innerHTML = this.responseText;
						}
					};
					xmlhttp.open("POST", "getfilter_sub_options.php?op=" + str + "&cnt=" + cnt, true);
					xmlhttp.send();
				}
			}
		</script>
		<script src="jQuery/jquery-2.1.3.min.js" type="text/javascript"></script>
		<script>
			function dynamic_Select(sort) {
				var skillsSelect = document.getElementById('dropdown');
				var selectedText = skillsSelect.options[skillsSelect.selectedIndex].value;
				document.getElementById("temp").value = selectedText;
			}

			function displaynonucbgaylord(colid, sortflg) {
				document.getElementById("div_noninv_gaylord").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_gaylord").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbgaylord.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displayurgentbox(colid, sortflg, cnt) {
				document.getElementById("ug_box").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				//alert(colid);
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("ug_box").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displayurgentbox.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displayucbinv(colid, sortflg) {
				document.getElementById("div_ucbinv").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_ucbinv").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displayucbinv.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displaynonucbshipping(colid, sortflg) {
				document.getElementById("div_noninv_shipping").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_shipping").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbshipping.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displaynonucbsupersack(colid, sortflg) {
				document.getElementById("div_noninv_supersack").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_supersack").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbsupersack.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displaynonucbdrumBarrel(colid, sortflg) {
				document.getElementById("div_noninv_drumBarrel").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_drumBarrel").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbdrumBarrel.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function displaynonucbpallets(colid, sortflg) {
				document.getElementById("div_noninv_pallets").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_noninv_pallets").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displaynonucbpallets.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}

			function sort_Select(warehouseid) {
				var Selectval = document.getElementById('sort_by_order');
				var order_type = Selectval.options[Selectval.selectedIndex].text;


				if (document.getElementById("dropdown").value == "") {
					alert("Please Select the field.");
				} else {
					document.getElementById("tempval_focus").focus();

					document.getElementById("tempval").style.display = "none";
					document.getElementById("tempval1").innerHTML =
						"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

					if (window.XMLHttpRequest) {
						xmlhttp = new XMLHttpRequest();
					} else {
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					}

					xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							if (order_type != "") {
								document.getElementById("tempval1").innerHTML = xmlhttp.responseText;
							}
						}
					}

					xmlhttp.open("GET", "pre_order_sort.php?warehouseid=" + warehouseid + "&selectedgrpid_inedit=" + document
						.getElementById("temp").value + "&sort_order=" + order_type, true);
					xmlhttp.send();
				}
			}





			function displayflyer(boxid, flyernm) {
				document.getElementById("light").innerHTML =
					"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" +
					flyernm + "' width='700' height='800'>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_fly_div" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 'px';

			}

			function displayflyer_main(boxid, flyernm) {
				document.getElementById("light").innerHTML =
					"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" +
					flyernm + "' width='700' height='800'>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_fly_div_main" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

			}

			function displayactualpallet(boxid) {
				document.getElementById("light").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("actual_pos" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_top = n_top - 200;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML =
							"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
							xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "report_inventory.php?inventory_id=" + boxid + "&action=run", true);
				xmlhttp.send();
			}

			function displayboxdata(boxid) {
				document.getElementById("light").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_div" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;

				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML =
							"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
							xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
				xmlhttp.send();
			}

			function displayboxdata_main(boxid) {
				document.getElementById("light").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_div_main" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;

				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML =
							"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
							xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
				xmlhttp.send();
			}

			function display_orders_data(tmpcnt, box_id, wid) {
				if (document.getElementById('inventory_preord_top_u' + tmpcnt).style.display == 'table-row') {
					document.getElementById('inventory_preord_top_u' + tmpcnt).style.display = 'none';
				} else {
					document.getElementById('inventory_preord_top_u' + tmpcnt).style.display = 'table-row';
				}

				document.getElementById("inventory_preord_middle_div_u" + tmpcnt).innerHTML =
					"<br><br>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("inventory_preord_middle_div_u" + tmpcnt).innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "gaylordstatus_childtable.php?box_id=" + box_id + "&wid=" + wid + "&tmpcnt=" + tmpcnt,
					true);
				xmlhttp.send();
			}


			function savetranslog(warehouse_id, transid, tmpcnt, box_id) {
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						alert("Data saved.");
						document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
					}
				}

				logdetail = document.getElementById("trans_notes" + transid + tmpcnt).value;
				opsdate = document.getElementById("ops_delivery_date" + transid + tmpcnt).value;

				xmlhttp.open("GET", "gaylordstatus_savetranslog.php?box_id=" + box_id + "&tmpcnt=" + tmpcnt + "&warehouse_id=" +
					warehouse_id + "&transid=" + transid + "&logdetail=" + logdetail + "&opsdate=" + opsdate, true);
				xmlhttp.send();
			}

			function inv_warehouse_list() {
				var chklist_sel = document.getElementById('inv_warehouse');
				var inv_warehouse = "";
				var opts = [],
					opt;
				len = chklist_sel.options.length;
				for (var i = 0; i < len; i++) {
					opt = chklist_sel.options[i];
					if (opt.selected) {
						inv_warehouse = inv_warehouse + opt.value + ",";
					}
				}

				if (inv_warehouse != "") {
					inv_warehouse = inv_warehouse.substring(0, inv_warehouse.length - 1);
				}

				var opts = [],
					opt;
				var inv_boxtype = "";
				var chklist_sel = document.getElementById('inv_boxtype');
				len = chklist_sel.options.length;
				for (var i = 0; i < len; i++) {
					opt = chklist_sel.options[i];
					if (opt.selected) {
						inv_boxtype = inv_boxtype + opt.value + ",";
					}
				}
				if (inv_boxtype != "") {
					inv_boxtype = inv_boxtype.substring(0, inv_boxtype.length - 1);
				}

				document.getElementById("tempval_focus").focus();

				document.getElementById("tempval").style.display = "none";
				document.getElementById("tempval1").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("tempval1").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inv_warehouse_lst.php?warehouse_id_lst=" + inv_warehouse + "&boxtype_lst=" + inv_boxtype,
					true);
				xmlhttp.send();

			}

			function new_inventory_filter() {
				document.getElementById("new_inv").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				//
				var sort_g_view = document.getElementById("sort_g_view").value;
				var sort_g_tool = document.getElementById("sort_g_tool").value;
				var g_timing = document.getElementById("g_timing").value;
				//
				var fld = document.getElementById('search_tag');
				var values = [];
				if (fld) {
					for (var i = 0; i < fld.options.length; i++) {
						if (fld.options[i].selected) {
							values.push(fld.options[i].value);
						}
					}
				}
				//

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						//alert(xmlhttp.responseText);
						document.getElementById("new_inv").innerHTML = xmlhttp.responseText;
					}
				}

				//"&search_tag=" + values
				xmlhttp.open("GET", "display_filter_inventory.php?sort_g_view=" + sort_g_view + "&sort_g_tool=" + sort_g_tool +
					"&g_timing=" + g_timing + "&search_tag=" + values, true);
				xmlhttp.send();
			}

			function load_div_wh_fullness(id) {
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
				document.getElementById('fade').style.display = 'block';
				document.getElementById('light').style.width = "80%";
				document.getElementById('light').style.left = '100px';
				document.getElementById('light').style.top = elementTop + 100 + 'px';
			}


			function close_div_wh_fullness() {
				document.getElementById('light').style.display = 'none';
			}
		</script>
		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
		</div>

		<a href='dashboardnew.php?show=inventory_cron'>Go Back to Old Inventory Version</a><br />
		<a href='javascript:void();' id='show_map1' onclick="displaymap()">Show Map with Boxes</a><br />
		<a target="_blank" href='report_inbound_inventory_summary.php?warehouse_id=0'>Inbound Inventory Summary</a><br />
		<!--<a target="_blank" href='report_inventory_types.php'>Inventory Report</a><br/>--><br />
		<?php

		?>

		<table style="width:30%" border="0">
			<tbody>
				<tr bgcolor="#ff9900">
					<td colspan="2" align="center"><b>Warehouse Fullness</b></td>
				</tr>
				<tr bgcolor="#ff9900">
					<td style="width:33%"><b>Facility</b></td>
					<td style="width:11%"><b>Fullness</b></td>
				</tr>
				<?php
				$wh_names_qry = "SELECT id, company_name from loop_warehouse where rec_type = 'Sorting' and Active = 1 order by company_name";
				db();
				$wh_names_res = db_query($wh_names_qry);
				while ($data_wh_names = array_shift($wh_names_res)) {
					$w_fullness_val = Warehouse_Fullness_Cal($data_wh_names["id"]);
					if ($w_fullness_val != 0) {
				?>
						<tr bgcolor="#e4e4e4">
							<td><?php echo $data_wh_names["company_name"]; ?></td>
							<td><?php $data_wh_names_id = $data_wh_names['id']; ?>
								<a href="#" onclick="load_div_wh_fullness('wh_fullness<?php echo $data_wh_names_id; ?>'); return false;">
									<?php
									if ($w_fullness_val >= 80) {
										echo '<span style="color: red;">' . number_format($w_fullness_val, 0) . '%';
									} else {
										echo '<span >' . number_format($w_fullness_val, 0) . '%';
									}
									?></a>
								<span id="wh_fullness<?php echo $data_wh_names["id"]; ?>" style="display: none;"><a href="#" onclick="close_div_wh_fullness(); return false; ">Close</a>
									<?php echo getPopupContent($data_wh_names["id"]); ?></span>
							</td>
						</tr>
				<?php
					}
				}
				?>
				<tr>
					<td bgColor="#e4e4e4" class="style12" colspan="2">
						<a href="update_inventory_cronjob_warehouse_fullness.php">Manually Update </a>
						<div class="tooltip">
							<i class="fa fa-info-circle" aria-hidden="true"></i>
							<span class="tooltiptext">Cron job runs at 2:20 am CT</span>
						</div>
					</td>
				</tr>
				<?php
				$sql = "SELECT * FROM tblvariable where variablename = 'warehouse_fullness_run_time'";
				db();
				$result = db_query($sql);
				while ($myrowsel = array_shift($result)) { ?>
					<tr vAlign="center">
						<td colspan="2" bgColor="#e4e4e4" class="style12" style="height: 16px">
							Last Update: <?php echo timeAgo($myrowsel["variablevalue"]); ?>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>

		<br><br>
		<table cellSpacing="1" cellPadding="1" border="0" width="1200">
			<tr align="middle">
				<td colspan="12" class="style24" style="height: 16px"><strong>INVENTORY NOTES</strong> <a href="updateinventorynotes.php">Edit</a></td>
			</tr>
			<tr vAlign="left">
				<td colspan=12>
					<?php
					$sql = "SELECT * FROM loop_inventory_notes ORDER BY dt DESC LIMIT 0,1";
					db();
					$res = db_query($sql);
					$row = array_shift($res);
					echo $row["notes"];
					?>
				</td>
			</tr>
			<tr align="middle">
				<td colspan="12">
					<img src="images/usa_map_territories.png" width="500px" height="350px" style="object-fit: cover;" />
				</td>
			</tr>
		</table>

		<table width="1400">
			<tr align="middle">
				<div id="light" class="white_content"></div>
				<div id="fade" class="black_overlay"></div>
				<td colspan="16" class="display_maintitle" style="height: 18px"><strong>Urgent Boxes</strong></td>
			</tr>
			<tr>
				<td colspan="16">
					<?php
					$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory WHERE  inventory.Active LIKE 'A' AND  box_urgent=1 ORDER BY inventory.availability DESC";

					//echo $box_query;
					db_b2b();
					$act_inv_res = db_query($box_query);
					//echo tep_db_num_rows($act_inv_res)."<br>";
					if (tep_db_num_rows($act_inv_res) > 0) {
					?>
					<?php

						$urgent_mgArray = array();
						//$inv_id_list = "";
						while ($inv = array_shift($act_inv_res)) {
							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$minfob = $b2b_fob;
							$b2b_fob = "$" . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2bcost = $b2b_cost;
							$b2b_cost = "$" . number_format($b2b_cost, 2);

							//
							$b2b_notes = $inv["N"];
							$b2b_notes_date = $inv["DT"];
							//
							$bpallet_qty = 0;
							$boxes_per_trailer = 0;
							$box_type = "";
							$loop_id = 0;
							$boxgoodvalue = 0;
							$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type, bwall, boxgoodvalue from loop_boxes where b2b_id=" . $inv["I"];
							//echo $qry_sku."<br>";
							$sku = "";
							db();
							$dt_view_sku = db_query($qry_sku);
							while ($sku_val = array_shift($dt_view_sku)) {
								$loop_id = $sku_val['id'];
								$sku = $sku_val['sku'];
								$bpallet_qty = $sku_val['bpallet_qty'];
								$boxes_per_trailer = $sku_val['boxes_per_trailer'];
								$box_type = $sku_val['type'];
								$box_wall = $sku_val['bwall'];
								$boxgoodvalue = $sku_val['boxgoodvalue'];
							}
							if ($inv["location_zip"] != "") {
								if ($inv["availability"] != "-3.5") {
									$inv_id_list .= $inv["I"] . ",";
								}
								//To get the Actual PO, After PO
								$rec_found_box = "n";
								$actual_val = 0;
								$after_po_val = 0;
								$last_month_qty = 0;
								$pallet_val = "";
								$pallet_val_afterpo = "";
								$actual_qty_calculated = "";
								$tmp_noofpallet = 0;
								$ware_house_boxdraw = "";
								$preorder_txt = "";
								$preorder_txt2 = "";
								$box_warehouse_id = 0;
								$next_load_available_date = "";
								//
								$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id, actual_qty_calculated, next_load_available_date from loop_boxes where b2b_id=" . $inv["I"];
								db();
								$dt_view = db_query($qry_loc);
								while ($loc_res = array_shift($dt_view)) {
									$territory = "";
									$box_warehouse_id = $loc_res["box_warehouse_id"];
									$next_load_available_date = $loc_res["next_load_available_date"];
									$actual_qty_calculated = $loc_res["actual_qty_calculated"];

									if ($loc_res["box_warehouse_id"] == "238") {
										$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
										$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
										db_b2b();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["shipCity"];
										$shipfrom_state = $loc_row["shipState"];
										$shipfrom_zip = $loc_row["shipZip"];
										//

										$territory = $loc_row["territory"];
										if ($territory == "Canada East") {
											$territory_sort = 1;
										}
										if ($territory == "East") {
											$territory_sort = 2;
										}
										if ($territory == "South") {
											$territory_sort = 3;
										}
										if ($territory == "Midwest") {
											$territory_sort = 4;
										}
										if ($territory == "North Central") {
											$territory_sort = 5;
										}
										if ($territory == "South Central") {
											$territory_sort = 6;
										}
										if ($territory == "Canada West") {
											$territory_sort = 7;
										}
										if ($territory == "Pacific Northwest") {
											$territory_sort = 8;
										}
										if ($territory == "West") {
											$territory_sort = 9;
										}
										if ($territory == "Canada") {
											$territory_sort = 10;
										}
										if ($territory == "Mexico") {
											$territory_sort = 11;
										}
										//

									} else {

										$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
										$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
										db();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["company_city"];
										$shipfrom_state = $loc_row["company_state"];
										$shipfrom_zip = $loc_row["company_zip"];
										//
										//
										$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
										$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
										$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
										$midwest = array('MI', 'OH', 'IN', 'KY');
										$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
										$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
										$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
										$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
										$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
										$canada = array();
										$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
										$territory_sort = 99;
										if (in_array($shipfrom_state, $canada_east, TRUE)) {
											$territory = "Canada East";
											$territory_sort = 1;
										} elseif (in_array($shipfrom_state, $east, TRUE)) {
											$territory = "East";
											$territory_sort = 2;
										} elseif (in_array($shipfrom_state, $south, TRUE)) {
											$territory = "South";
											$territory_sort = 3;
										} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
											$territory = "Midwest";
											$territory_sort = 4;
										} else if (in_array($shipfrom_state, $north_central, TRUE)) {
											$territory = "North Central";
											$territory_sort = 5;
										} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
											$territory = "South Central";
											$territory_sort = 6;
										} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
											$territory = "Canada West";
											$territory_sort = 7;
										} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
											$territory = "Pacific Northwest";
											$territory_sort = 8;
										} elseif (in_array($shipfrom_state, $west, TRUE)) {
											$territory = "West";
											$territory_sort = 9;
										} elseif (in_array($shipfrom_state, $canada, TRUE)) {
											$territory = "Canada";
											$territory_sort = 10;
										} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
											$territory = "Mexico";
											$territory_sort = 11;
										}
									}
								}
								$ship_from  = isset($shipfrom_city) . ", " . isset($shipfrom_state) . " " . isset($shipfrom_zip);
								$ship_from2 = isset($shipfrom_state);
								//Find territory
								//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
								//
								$after_po_val_tmp = 0;
								$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
								db_b2b();
								$dt_view_res_box = db_query($dt_view_qry);
								while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
									$rec_found_box = "y";
									$actual_val = $dt_view_res_box_data["actual"];
									$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
									$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
									//
								}
								if ($rec_found_box == "n") {
									$actual_val = $inv["actual_inventory"];
									$after_po_val = $inv["after_actual_inventory"];
									$last_month_qty = $inv["lastmonthqty"];
								}

								if ($box_warehouse_id == 238) {
									$after_po_val = $inv["after_actual_inventory"];
								} else {
									$after_po_val = $after_po_val_tmp;
								}
								//	echo "after_po_val - " . $after_po_val . " - " . $box_warehouse_id . " - " . $after_po_val_tmp . "<br>";				

								//Updated as per new actual_qty_calculated field
								//$after_po_val =	$actual_qty_calculated;
								$to_show_rec = "y";

								if ($g_timing == 2) {
									$to_show_rec = "";
									if ($after_po_val >= $boxes_per_trailer) {
										$to_show_rec = "y";
									}
								}

								//if ($sort_g_tool == 2){
								//	$to_show_rec = "y";	
								//}

								if ($to_show_rec == "y") {
									//account owner
									if ($inv["vendor_b2b_rescue"] > 0) {

										$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
										$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
										db();
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									}
									//
									$vender_nm = "";
									if ($inv["vendor_b2b_rescue"] != "") {
										$q1 = "SELECT * FROM loop_warehouse where id = " . $inv["vendor_b2b_rescue"];
										db();
										$v_query = db_query($q1);
										while ($v_fetch = array_shift($v_query)) {
											$supplier_id = $v_fetch["b2bid"];
											$vender_nm = getnickname($v_fetch['company_name'], $v_fetch["b2bid"]);
											//$vender_nm = $v_fetch['company_name'];
											db_b2b();
											$com_qry = db_query("select * from companyInfo where ID='" . $v_fetch["b2bid"] . "'");
											$com_row = array_shift($com_qry);
										}
									}
									//
									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"];
									}

									$estimated_next_load = "";
									$b2bstatuscolor = "";
									if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
										//$next_load_available_date = $b2b_inv_row["next_load_available_date"];
										//echo "next_load_available_date - " . $inv["I"] . " " . $next_load_available_date . " " . $inv["lead_time"] . "<br>";

										//
										$now_date = time(); // or your date as well
										$next_load_date = strtotime($next_load_available_date);
										$datediff = $next_load_date - $now_date;
										$no_of_loaddays = round($datediff / (60 * 60 * 24));
										//echo $no_of_loaddays;
										if ($no_of_loaddays < $lead_time) {
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
											} else {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
											}
										} else {
											$estimated_next_load = "<font color=green>" . $no_of_loaddays . " Days</font>";
										}
										//
									} else {
										if ($after_po_val >= $boxes_per_trailer) {
											if ($inv["lead_time"] == 0) {
												$estimated_next_load = "<font color=green>Now</font>";
											}
											if ($inv["lead_time"] == 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
											}
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
											}
										} else {
											if (($inv["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
												$estimated_next_load = "<font color=red>Never (sell the " . $after_po_val . ")</font>";
											} else {
												// logic changed by Zac
												$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $inv["expected_loads_per_mo"]) * 4) . " Weeks";
											}
										}

										if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0) {
											$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
										}

										if ($inv["expected_loads_per_mo"] == 0) {
											$expected_loads_per_mo = "<font color=red>0</font>";
										} else {
											$expected_loads_per_mo = $inv["expected_loads_per_mo"];
										}
									}
									//							

									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"] . " Days";
									}

									if ($inv["expected_loads_per_mo"] == 0) {
										$expected_loads_per_mo = "<font color=red>0</font>";
									} else {
										$expected_loads_per_mo = $inv["expected_loads_per_mo"];
									}
									//
									$b2b_status = $inv["b2b_status"];

									$estimated_next_load = $inv["buy_now_load_can_ship_in"];

									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									db();
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_name = $st_row["box_status"];
									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
									}
									//
									if ($inv["box_urgent"] == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_name = "URGENT";
									}
									//
									if ($inv["uniform_mixed_load"] == "Mixed") {
										$blength = $inv["blength_min"] . " - " . $inv["blength_max"];
										$bwidth = $inv["bwidth_min"] . " - " . $inv["bwidth_max"];
										$bdepth = $inv["bheight_min"] . " - " . $inv["bheight_max"];
									} else {
										$blength = $inv["lengthInch"];
										$bwidth = $inv["widthInch"];
										$bdepth = $inv["depthInch"];
									}
									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;
									//
									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;

									if ($inv["lengthFraction"] != "") {
										$arr_length = explode("/", $inv["lengthFraction"]);
										if (tep_db_num_rows($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($inv["widthFraction"] != "") {
										$arr_width = explode("/", $inv["widthFraction"]);
										if (tep_db_num_rows($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}
									if ($inv["depthFraction"] != "") {
										$arr_depth = explode("/", $inv["depthFraction"]);
										if (tep_db_num_rows($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}
									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if ($inv["box_urgent"] == 1) {
										$b_urgent = "Yes";
									}
									if ($inv["contracted"] == 1) {
										$contracted = "Yes";
									}
									if ($inv["prepay"] == 1) {
										$prepay = "Yes";
									}
									if ($inv["ship_ltl"] == 1) {
										$ship_ltl = "Yes";
									}
									//$tipStr = "Loops ID#: " . $loop_id . "<br>";
									$tipStr = "<b>Notes:</b> " . $inv["N"] . "<br>";
									if ($inv["DT"] != "0000-00-00") {
										$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($inv["DT"])) . "<br>";
									} else {
										$tipStr .= "<b>Notes Date:</b> <br>";
									}
									$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
									$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
									$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
									$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

									$tipStr .= "<b>Qty Avail:</b> " . $after_po_val . "<br>";
									$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $estimated_next_load . "<br>";
									$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $inv["expected_loads_per_mo"] . "<br>";
									$tipStr .= "<b>B2B Status:</b> " . $b2bstatus_name . "<br>";
									$tipStr .= "<b>Supplier Relationship Owner:</b> " . isset($ownername) . "<br>";
									$tipStr .= "<b>B2B ID#:</b> " . $inv["I"] . "<br>";
									$tipStr .= "<b>Description:</b> " . $inv["description"] . "<br>";
									$tipStr .= "<b>Supplier:</b> " .  $vender_nm . "<br>";
									$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
									$tipStr .= "<b>Territory:</b> " . isset($territory) . "<br>";
									$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty . "<br>";
									$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer . "<br>";
									$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
									$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
									//

									//Get data in array
									$urgent_mgArray[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'territory_sort' => isset($territory_sort));
									//	
								} //end $to_show_rec == "y"
							} //end if ($inv["location_zip"] != "")	
							//
						} //End while $inv
					}
					?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1" class="basic_style">
						<?php
						$x = 0;
						$boxtype_cnt = 0;
						//
						$MGarray = $urgent_mgArray;
						$MGArraysort_I = array();
						$MGArraysort_II = array();
						$MGArraysort_III = array();
						foreach ($MGarray as $MGArraytmp) {
							$MGArraysort_I[] = $MGArraytmp['territory_sort'];
							$MGArraysort_II[] = $MGArraytmp['vendor_nm'];
							$MGArraysort_III[] = $MGArraytmp['depth'];
						}
						array_multisort($MGArraysort_I, SORT_ASC, $MGArraysort_II, SORT_ASC, $MGArraysort_III, SORT_ASC, $MGarray);
						//
						//print_r($MGarray);
						$total_rec = tep_db_num_rows($MGarray);
						if ($total_rec > 0) {
							$boxtype_cnt = 0;
						?>
							<tr>
								<td class="display_maintitle" align="center">Active Inventory Items - Urgent Boxes</td>
							</tr>
							<tr>
								<td>
									<div id="ug_box">
										<table width="100%" cellspacing="1" cellpadding="2">
											<tr>
												<td class='display_title'>Qty Avail&nbsp;<a href="javascript:void();" onclick="displayurgentbox(1,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(1,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="80px">Buy Now, Load Can Ship In&nbsp;<a href="javascript:void();" onclick="displayurgentbox(2,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(2,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayurgentbox(3,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(3,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayurgentbox(4,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(4,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayurgentbox(19,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(19,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>MIN FOB&nbsp;<a href="javascript:void();" onclick="displayurgentbox(5,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(5,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayurgentbox(6,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(6,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayurgentbox(7,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(7,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B Status&nbsp;<a href="javascript:void();" onclick="displayurgentbox(8,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(8,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayurgentbox(9,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(9,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayurgentbox(10,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(10,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayurgentbox(11,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(11,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayurgentbox(12,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(12,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayurgentbox(13,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(13,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Supplier&nbsp;<a href="javascript:void();" onclick="displayurgentbox(14,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(14,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="72px">Ship From&nbsp;<a href="javascript:void();" onclick="displayurgentbox(15,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(15,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="70px">Rep&nbsp;<a href="javascript:void();" onclick="displayurgentbox(16,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(16,2,<?php echo isset($box_type_cnt); ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Sales Team Notes&nbsp;<a href="javascript:void();" onclick="displayurgentbox(17,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(17,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Last Notes Date&nbsp;<a href="javascript:void();" onclick="displayurgentbox(18,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(18,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
											</tr>
											<?php
											$count_arry = 0;
											$count = 0;
											$row_cnt = 0;
											foreach ($MGarray as $MGArraytmp2) {
												//
												$count = $count + 1;
												if (isset($MGArraytmp2["binv"]) == "nonucb") {
													$binv = "";
												}
												if (isset($MGArraytmp2["binv"]) == "ucbown") {
													$binv = "<b>UCB Owned Inventory </b><br>";
												}
												//
												$tipStr = "<b>Notes:</b> " . $MGArraytmp2["b2b_notes"] . "<br>";
												if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
													$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"])) . "<br>";
												} else {
													$tipStr .= "<b>Notes Date:</b> <br>";
												}
												$tipStr .= "<b>Urgent:</b> " . $MGArraytmp2["b_urgent"] . "<br>";
												$tipStr .= "<b>Contracted:</b> " . $MGArraytmp2["contracted"] . "<br>";
												$tipStr .= "<b>Prepay:</b> " . $MGArraytmp2["prepay"] . "<br>";
												$tipStr .= "<b>Can Ship LTL?</b> " . $MGArraytmp2["ship_ltl"] . "<br>";

												$tipStr .= "<b>Qty Avail:</b> " . $MGArraytmp2["after_po_val"] . "<br>";
												$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $MGArraytmp2["estimated_next_load"] . "<br>";
												$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $MGArraytmp2["expected_loads_per_mo"] . "<br>";
												$tipStr .= "<b>B2B Status:</b> " . $MGArraytmp2["b2bstatus_name"] . "<br>";
												$tipStr .= "<b>Supplier Relationship Owner:</b> " . $MGArraytmp2["ownername"] . "<br>";
												$tipStr .= "<b>B2B ID#:</b> " . $MGArraytmp2["b2bid"] . "<br>";
												$tipStr .= "<b>Description:</b> " . $MGArraytmp2["description"] . "<br>";
												$tipStr .= "<b>Supplier:</b> " .  $MGArraytmp2["vendor_nm"] . "<br>";
												$tipStr .= "<b>Ship From:</b> " . $MGArraytmp2["ship_from"] . "<br>";
												$tipStr .= "<b>Territory:</b> " . $MGArraytmp2["territory"] . "<br>";
												$tipStr .= "<b>Per Pallet:</b> " . $MGArraytmp2["bpallet_qty"] . "<br>";
												$tipStr .= "<b>Per Truckload:</b> " . $MGArraytmp2["boxes_per_trailer"] . "<br>";
												$tipStr .= "<b>Min FOB:</b> " . $MGArraytmp2["b2b_fob"] . "<br>";
												$tipStr .= "<b>B2B Cost:</b> " . $MGArraytmp2["b2b_cost"] . "<br>";
												$tipStr .= isset($binv);
												//
												if ($row_cnt == 0) {
													$display_table_css = "display_table";
													$row_cnt = 1;
												} else {
													$row_cnt = 0;
													$display_table_css = "display_table_alt";
												}
												//
												$loopid = get_loop_box_id($MGArraytmp2["b2bid"]);
												$vendornme = $MGArraytmp2["vendor_nm"];

												//
												$sales_order_qty = 0;
												if ($MGArraytmp2["vendor_b2b_rescue_id"] > 0) {
													$dt_so_item = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
													$dt_so_item .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
													$dt_so_item .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
													$dt_so_item .= " WHERE loop_salesorders.box_id = " . $loopid . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
													db();
													$dt_res_so_item = db_query($dt_so_item);
													while ($so_item_row = array_shift($dt_res_so_item)) {
														if ($so_item_row["sumqty"] > 0) {
															$sales_order_qty = $so_item_row["sumqty"];
														}
													}
												}
												$tmpTDstr = "<tr  >";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
												if ($MGArraytmp2["after_po_val"] < 0) {

													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_orders_data($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
													$tmpTDstr =  $tmpTDstr . "<div";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_orders_data($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else {
													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_orders_data($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												}
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"], 2) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><font color='" . $MGArraytmp2["b2bstatuscolor"] . "'>" . $MGArraytmp2["b2bstatus_name"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . "<a target='_blank' href='manage_box_b2bloop.php?id=" . get_loop_box_id($MGArraytmp2["b2bid"]) . "&proc=View&'";
												$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";

												//echo " >" ;
												$tmpTDstr =  $tmpTDstr . " >";

												$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><a target='_blank' href='viewCompany.php?ID=" . $MGArraytmp2["supplier_id"] . "'>" . $MGArraytmp2["vendor_nm"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ownername"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
												if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
													$tmpTDstr =  $tmpTDstr . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"]));
												}
												$tmpTDstr =  $tmpTDstr . "</td>";

												$tmpTDstr =  $tmpTDstr . "</tr>";
												//
												$tmpTDstr =  $tmpTDstr . "<tr id='inventory_preord_top_u" . $count . "' align='middle' style='display:none;'>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td colspan='16'>
															<div id='inventory_preord_middle_div_u" . $count . "'></div>		
													</td></tr>";
												echo $tmpTDstr;
											}
											?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td height="10px"></td>
							</tr>
						<?php
						}
						?>
					</table>
					<!--End Urgent boxes table-->
					<br>
				</td>
			</tr>
		</table>

		<!-- Box High Value Opportunity-->
		<table width="1400">
			<tr align="middle">
				<div id="light" class="white_content"></div>
				<div id="fade" class="black_overlay"></div>
				<td colspan="16" class="display_maintitle" style="height: 18px"><strong>High Value Opportunity</strong></td>
			</tr>
			<tr>
				<td colspan="16">
					<?php
					$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory 
					WHERE  inventory.Active LIKE 'A' AND  high_value_target=1 ORDER BY inventory.availability DESC";
					//echo $box_query;
					db_b2b();
					$act_inv_res = db_query($box_query);
					//echo tep_db_num_rows($act_inv_res)."<br>";
					if (tep_db_num_rows($act_inv_res) > 0) {
					?>
					<?php
						while ($inv = array_shift($act_inv_res)) {
							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$minfob = $b2b_fob;
							$b2b_fob = "$" . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2bcost = $b2b_cost;
							$b2b_cost = "$" . number_format($b2b_cost, 2);
							//
							$b2b_notes = $inv["N"];
							$b2b_notes_date = $inv["DT"];
							//
							$bpallet_qty = 0;
							$boxes_per_trailer = 0;
							$box_type = "";
							$loop_id = 0;
							$boxgoodvalue = 0;
							$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type, bwall, boxgoodvalue from loop_boxes where b2b_id=" . $inv["I"];
							//echo $qry_sku."<br>";
							$sku = "";
							db();
							$dt_view_sku = db_query($qry_sku);
							while ($sku_val = array_shift($dt_view_sku)) {
								$loop_id = $sku_val['id'];
								$sku = $sku_val['sku'];
								$bpallet_qty = $sku_val['bpallet_qty'];
								$boxes_per_trailer = $sku_val['boxes_per_trailer'];
								$box_type = $sku_val['type'];
								$box_wall = $sku_val['bwall'];
								$boxgoodvalue = $sku_val['boxgoodvalue'];
							}
							if ($inv["location_zip"] != "") {
								if ($inv["availability"] != "-3.5") {
									$inv_id_list .= $inv["I"] . ",";
								}
								//To get the Actual PO, After PO
								$rec_found_box = "n";
								$actual_val = 0;
								$after_po_val = 0;
								$last_month_qty = 0;
								$pallet_val = "";
								$pallet_val_afterpo = "";
								$actual_qty_calculated = "";
								$tmp_noofpallet = 0;
								$ware_house_boxdraw = "";
								$preorder_txt = "";
								$preorder_txt2 = "";
								$box_warehouse_id = 0;
								$next_load_available_date = "";
								//
								$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id, actual_qty_calculated, next_load_available_date from loop_boxes where b2b_id=" . $inv["I"];
								db();
								$dt_view = db_query($qry_loc);
								while ($loc_res = array_shift($dt_view)) {
									$territory = "";
									$box_warehouse_id = $loc_res["box_warehouse_id"];
									$next_load_available_date = $loc_res["next_load_available_date"];
									$actual_qty_calculated = $loc_res["actual_qty_calculated"];

									if ($loc_res["box_warehouse_id"] == "238") {
										$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
										$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
										db_b2b();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["shipCity"];
										$shipfrom_state = $loc_row["shipState"];
										$shipfrom_zip = $loc_row["shipZip"];
										//

										$territory = $loc_row["territory"];
										if ($territory == "Canada East") {
											$territory_sort = 1;
										}
										if ($territory == "East") {
											$territory_sort = 2;
										}
										if ($territory == "South") {
											$territory_sort = 3;
										}
										if ($territory == "Midwest") {
											$territory_sort = 4;
										}
										if ($territory == "North Central") {
											$territory_sort = 5;
										}
										if ($territory == "South Central") {
											$territory_sort = 6;
										}
										if ($territory == "Canada West") {
											$territory_sort = 7;
										}
										if ($territory == "Pacific Northwest") {
											$territory_sort = 8;
										}
										if ($territory == "West") {
											$territory_sort = 9;
										}
										if ($territory == "Canada") {
											$territory_sort = 10;
										}
										if ($territory == "Mexico") {
											$territory_sort = 11;
										}
										//

									} else {

										$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
										$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
										db();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["company_city"];
										$shipfrom_state = $loc_row["company_state"];
										$shipfrom_zip = $loc_row["company_zip"];
										//
										//
										$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
										$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
										$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
										$midwest = array('MI', 'OH', 'IN', 'KY');
										$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
										$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
										$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
										$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
										$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
										$canada = array();
										$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
										$territory_sort = 99;
										if (in_array($shipfrom_state, $canada_east, TRUE)) {
											$territory = "Canada East";
											$territory_sort = 1;
										} elseif (in_array($shipfrom_state, $east, TRUE)) {
											$territory = "East";
											$territory_sort = 2;
										} elseif (in_array($shipfrom_state, $south, TRUE)) {
											$territory = "South";
											$territory_sort = 3;
										} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
											$territory = "Midwest";
											$territory_sort = 4;
										} else if (in_array($shipfrom_state, $north_central, TRUE)) {
											$territory = "North Central";
											$territory_sort = 5;
										} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
											$territory = "South Central";
											$territory_sort = 6;
										} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
											$territory = "Canada West";
											$territory_sort = 7;
										} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
											$territory = "Pacific Northwest";
											$territory_sort = 8;
										} elseif (in_array($shipfrom_state, $west, TRUE)) {
											$territory = "West";
											$territory_sort = 9;
										} elseif (in_array($shipfrom_state, $canada, TRUE)) {
											$territory = "Canada";
											$territory_sort = 10;
										} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
											$territory = "Mexico";
											$territory_sort = 11;
										}
									}
								}
								$ship_from  = isset($shipfrom_city) . ", " . isset($shipfrom_state) . " " . isset($shipfrom_zip);
								$ship_from2 = isset($shipfrom_state);
								//Find territory
								//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico
								//
								$after_po_val_tmp = 0;
								$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
								db_b2b();
								$dt_view_res_box = db_query($dt_view_qry);
								while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
									$rec_found_box = "y";
									$actual_val = $dt_view_res_box_data["actual"];
									$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
									$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
									//
								}
								if ($rec_found_box == "n") {
									$actual_val = $inv["actual_inventory"];
									$after_po_val = $inv["after_actual_inventory"];
									$last_month_qty = $inv["lastmonthqty"];
								}

								if ($box_warehouse_id == 238) {
									$after_po_val = $inv["after_actual_inventory"];
								} else {
									$after_po_val = $after_po_val_tmp;
								}

								$to_show_rec = "y";

								if ($g_timing == 2) {
									$to_show_rec = "";
									if ($after_po_val >= $boxes_per_trailer) {
										$to_show_rec = "y";
									}
								}

								if ($to_show_rec == "y") {
									//account owner
									if ($inv["vendor_b2b_rescue"] > 0) {

										$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
										$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
										db();
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									}
									//
									$vender_nm = "";
									if ($inv["vendor_b2b_rescue"] != "") {
										$q1 = "SELECT * FROM loop_warehouse where id = " . $inv["vendor_b2b_rescue"];
										db();
										$v_query = db_query($q1);
										while ($v_fetch = array_shift($v_query)) {
											$supplier_id = $v_fetch["b2bid"];
											$vender_nm = getnickname($v_fetch['company_name'], $v_fetch["b2bid"]);
											//$vender_nm = $v_fetch['company_name'];
											db_b2b();
											$com_qry = db_query("select * from companyInfo where ID='" . $v_fetch["b2bid"] . "'");
											$com_row = array_shift($com_qry);
										}
									}
									//
									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"];
									}

									$estimated_next_load = "";
									$b2bstatuscolor = "";
									if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
										//$next_load_available_date = $b2b_inv_row["next_load_available_date"];
										//echo "next_load_available_date - " . $inv["I"] . " " . $next_load_available_date . " " . $inv["lead_time"] . "<br>";
										$now_date = time(); // or your date as well
										$next_load_date = strtotime($next_load_available_date);
										$datediff = $next_load_date - $now_date;
										$no_of_loaddays = round($datediff / (60 * 60 * 24));
										//echo $no_of_loaddays;
										if ($no_of_loaddays < $lead_time) {
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
											} else {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
											}
										} else {
											$estimated_next_load = "<font color=green>" . $no_of_loaddays . " Days</font>";
										}
										//
									} else {
										if ($after_po_val >= $boxes_per_trailer) {
											if ($inv["lead_time"] == 0) {
												$estimated_next_load = "<font color=green>Now</font>";
											}
											if ($inv["lead_time"] == 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
											}
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
											}
										} else {
											if (($inv["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
												$estimated_next_load = "<font color=red>Never (sell the " . $after_po_val . ")</font>";
											} else {
												// logic changed by Zac
												$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $inv["expected_loads_per_mo"]) * 4) . " Weeks";
											}
										}

										if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0) {
											$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
										}

										if ($inv["expected_loads_per_mo"] == 0) {
											$expected_loads_per_mo = "<font color=red>0</font>";
										} else {
											$expected_loads_per_mo = $inv["expected_loads_per_mo"];
										}
									}
									//							

									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"] . " Days";
									}

									if ($inv["expected_loads_per_mo"] == 0) {
										$expected_loads_per_mo = "<font color=red>0</font>";
									} else {
										$expected_loads_per_mo = $inv["expected_loads_per_mo"];
									}
									//
									$b2b_status = $inv["b2b_status"];

									$estimated_next_load = $inv["buy_now_load_can_ship_in"];

									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									db();
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_name = $st_row["box_status"];
									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
									}
									//
									if ($inv["box_urgent"] == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_name = "URGENT";
									}
									//
									if ($inv["uniform_mixed_load"] == "Mixed") {
										$blength = $inv["blength_min"] . " - " . $inv["blength_max"];
										$bwidth = $inv["bwidth_min"] . " - " . $inv["bwidth_max"];
										$bdepth = $inv["bheight_min"] . " - " . $inv["bheight_max"];
									} else {
										$blength = $inv["lengthInch"];
										$bwidth = $inv["widthInch"];
										$bdepth = $inv["depthInch"];
									}
									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;
									//
									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;

									if ($inv["lengthFraction"] != "") {
										$arr_length = explode("/", $inv["lengthFraction"]);
										if (tep_db_num_rows($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($inv["widthFraction"] != "") {
										$arr_width = explode("/", $inv["widthFraction"]);
										if (tep_db_num_rows($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}
									if ($inv["depthFraction"] != "") {
										$arr_depth = explode("/", $inv["depthFraction"]);
										if (tep_db_num_rows($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}
									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if ($inv["box_urgent"] == 1) {
										$b_urgent = "Yes";
									}
									if ($inv["contracted"] == 1) {
										$contracted = "Yes";
									}
									if ($inv["prepay"] == 1) {
										$prepay = "Yes";
									}
									if ($inv["ship_ltl"] == 1) {
										$ship_ltl = "Yes";
									}
									//$tipStr = "Loops ID#: " . $loop_id . "<br>";
									$tipStr = "<b>Notes:</b> " . $inv["N"] . "<br>";
									if ($inv["DT"] != "0000-00-00") {
										$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($inv["DT"])) . "<br>";
									} else {
										$tipStr .= "<b>Notes Date:</b> <br>";
									}
									$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
									$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
									$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
									$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

									$tipStr .= "<b>Qty Avail:</b> " . $after_po_val . "<br>";
									$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $estimated_next_load . "<br>";
									$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $inv["expected_loads_per_mo"] . "<br>";
									$tipStr .= "<b>B2B Status:</b> " . $b2bstatus_name . "<br>";
									$tipStr .= "<b>Supplier Relationship Owner:</b> " . isset($ownername) . "<br>";
									$tipStr .= "<b>B2B ID#:</b> " . $inv["I"] . "<br>";
									$tipStr .= "<b>Description:</b> " . $inv["description"] . "<br>";
									$tipStr .= "<b>Supplier:</b> " .  $vender_nm . "<br>";
									$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
									$tipStr .= "<b>Territory:</b> " . isset($territory) . "<br>";
									$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty . "<br>";
									$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer . "<br>";
									$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
									$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
									//

									//Get data in array
									$high_value_target_mgArray[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'territory_sort' => isset($territory_sort));
									//	
								} //end $to_show_rec == "y"
							} //end if ($inv["location_zip"] != "")	
							//
						} //End while $inv
					}
					?>
					<table width="100%" border="0" cellspacing="1" cellpadding="1" class="basic_style">
						<?php
						$x = 0;
						$boxtype_cnt = 0;
						//
						$MGarray = isset($high_value_target_mgArray);
						$MGArraysort_I = array();
						$MGArraysort_II = array();
						$MGArraysort_III = array();

						if (is_iterable($MGarray)) {
							foreach ($MGarray as $MGArraytmp) {
								// Your code here

								$MGArraysort_I[] = $MGArraytmp['territory_sort'];
								$MGArraysort_II[] = $MGArraytmp['vendor_nm'];
								$MGArraysort_III[] = $MGArraytmp['depth'];
							}
						}
						array_multisort($MGArraysort_I, SORT_ASC, $MGArraysort_II, SORT_ASC, $MGArraysort_III, SORT_ASC, $MGarray);
						//
						//print_r($MGarray);
						if (is_array($MGarray)) {
							$total_rec = tep_db_num_rows($MGarray);
						}
						if ($total_rec > 0) {
							$boxtype_cnt = 0;
						?>
							<tr>
								<td class="display_maintitle" align="center">Active Inventory Items - High Value Opportunity
								</td>
							</tr>
							<tr>
								<td>
									<div id="ug_box">
										<table width="100%" cellspacing="1" cellpadding="2">
											<tr>
												<td class='display_title'>Qty Avail&nbsp;<a href="javascript:void();" onclick="displayurgentbox(1,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(1,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="80px">Buy Now, Load Can Ship In&nbsp;<a href="javascript:void();" onclick="displayurgentbox(2,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(2,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayurgentbox(3,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(3,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayurgentbox(4,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(4,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayurgentbox(19,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(19,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>MIN FOB&nbsp;<a href="javascript:void();" onclick="displayurgentbox(5,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(5,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayurgentbox(6,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(6,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayurgentbox(7,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(7,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B Status&nbsp;<a href="javascript:void();" onclick="displayurgentbox(8,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(8,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayurgentbox(9,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(9,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayurgentbox(10,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(10,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayurgentbox(11,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(11,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayurgentbox(12,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(12,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayurgentbox(13,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(13,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Supplier&nbsp;<a href="javascript:void();" onclick="displayurgentbox(14,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(14,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="72px">Ship From&nbsp;<a href="javascript:void();" onclick="displayurgentbox(15,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(15,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="70px">Rep&nbsp;<a href="javascript:void();" onclick="displayurgentbox(16,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(16,2,<?php echo isset($box_type_cnt); ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Sales Team Notes&nbsp;<a href="javascript:void();" onclick="displayurgentbox(17,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(17,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Last Notes Date&nbsp;<a href="javascript:void();" onclick="displayurgentbox(18,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(18,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
											</tr>
											<?php
											$count_arry = 0;
											$count = 0;
											$row_cnt = 0;
											if (is_iterable($MGarray)) {
												foreach ($MGarray as $MGArraytmp2) {
													// Your existing code inside the foreach loop

													//
													$count = $count + 1;
													if (isset($MGArraytmp2["binv"]) == "nonucb") {
														$binv = "";
													}
													if (isset($MGArraytmp2["binv"]) == "ucbown") {
														$binv = "<b>UCB Owned Inventory </b><br>";
													}
													//
													$tipStr = "<b>Notes:</b> " . $MGArraytmp2["b2b_notes"] . "<br>";
													if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
														$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"])) . "<br>";
													} else {
														$tipStr .= "<b>Notes Date:</b> <br>";
													}
													$tipStr .= "<b>Urgent:</b> " . $MGArraytmp2["b_urgent"] . "<br>";
													$tipStr .= "<b>Contracted:</b> " . $MGArraytmp2["contracted"] . "<br>";
													$tipStr .= "<b>Prepay:</b> " . $MGArraytmp2["prepay"] . "<br>";
													$tipStr .= "<b>Can Ship LTL?</b> " . $MGArraytmp2["ship_ltl"] . "<br>";

													$tipStr .= "<b>Qty Avail:</b> " . $MGArraytmp2["after_po_val"] . "<br>";
													$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $MGArraytmp2["estimated_next_load"] . "<br>";
													$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $MGArraytmp2["expected_loads_per_mo"] . "<br>";
													$tipStr .= "<b>B2B Status:</b> " . $MGArraytmp2["b2bstatus_name"] . "<br>";
													$tipStr .= "<b>Supplier Relationship Owner:</b> " . $MGArraytmp2["ownername"] . "<br>";
													$tipStr .= "<b>B2B ID#:</b> " . $MGArraytmp2["b2bid"] . "<br>";
													$tipStr .= "<b>Description:</b> " . $MGArraytmp2["description"] . "<br>";
													$tipStr .= "<b>Supplier:</b> " .  $MGArraytmp2["vendor_nm"] . "<br>";
													$tipStr .= "<b>Ship From:</b> " . $MGArraytmp2["ship_from"] . "<br>";
													$tipStr .= "<b>Territory:</b> " . $MGArraytmp2["territory"] . "<br>";
													$tipStr .= "<b>Per Pallet:</b> " . $MGArraytmp2["bpallet_qty"] . "<br>";
													$tipStr .= "<b>Per Truckload:</b> " . $MGArraytmp2["boxes_per_trailer"] . "<br>";
													$tipStr .= "<b>Min FOB:</b> " . $MGArraytmp2["b2b_fob"] . "<br>";
													$tipStr .= "<b>B2B Cost:</b> " . $MGArraytmp2["b2b_cost"] . "<br>";
													$tipStr .= isset($binv);
													//
													if ($row_cnt == 0) {
														$display_table_css = "display_table";
														$row_cnt = 1;
													} else {
														$row_cnt = 0;
														$display_table_css = "display_table_alt";
													}
													//
													$loopid = get_loop_box_id($MGArraytmp2["b2bid"]);
													$vendornme = $MGArraytmp2["vendor_nm"];

													//
													$sales_order_qty = 0;
													if ($MGArraytmp2["vendor_b2b_rescue_id"] > 0) {
														$dt_so_item = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
														$dt_so_item .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
														$dt_so_item .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
														$dt_so_item .= " WHERE loop_salesorders.box_id = " . $loopid . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
														db();
														$dt_res_so_item = db_query($dt_so_item);
														while ($so_item_row = array_shift($dt_res_so_item)) {
															if ($so_item_row["sumqty"] > 0) {
																$sales_order_qty = $so_item_row["sumqty"];
															}
														}
													}
													$tmpTDstr = "<tr  >";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
													if ($MGArraytmp2["after_po_val"] < 0) {

														$tmpTDstr =  $tmpTDstr . "<div ";
														if ($sales_order_qty > 0) {
															$tmpTDstr =  $tmpTDstr . " onclick='display_orders_data($count, $loopid)'  class='popup_qty'";
														}
														$tmpTDstr =  $tmpTDstr . "><font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
													} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
														$tmpTDstr =  $tmpTDstr . "<div";
														if ($sales_order_qty > 0) {
															$tmpTDstr =  $tmpTDstr . " onclick='display_orders_data($count, $loopid)'  class='popup_qty'";
														}
														$tmpTDstr =  $tmpTDstr . "><font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
													} else {
														$tmpTDstr =  $tmpTDstr . "<div ";
														if ($sales_order_qty > 0) {
															$tmpTDstr =  $tmpTDstr . " onclick='display_orders_data($count, $loopid)'  class='popup_qty'";
														}
														$tmpTDstr =  $tmpTDstr . "><font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
													}
													//
													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"], 2) . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><font color='" . $MGArraytmp2["b2bstatuscolor"] . "'>" . $MGArraytmp2["b2bstatus_name"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

													$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

													$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
													//
													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . "<a target='_blank' href='manage_box_b2bloop.php?id=" . get_loop_box_id($MGArraytmp2["b2bid"]) . "&proc=View&'";
													$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";

													//echo " >" ;
													$tmpTDstr =  $tmpTDstr . " >";

													$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</a></td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><a target='_blank' href='viewCompany.php?ID=" . $MGArraytmp2["supplier_id"] . "'>" . $MGArraytmp2["vendor_nm"] . "</a></td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ownername"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

													$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
													if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
														$tmpTDstr =  $tmpTDstr . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"]));
													}
													$tmpTDstr =  $tmpTDstr . "</td>";

													$tmpTDstr =  $tmpTDstr . "</tr>";
													//
													$tmpTDstr =  $tmpTDstr . "<tr id='inventory_preord_top_u" . $count . "' align='middle' style='display:none;'>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td colspan='16'>
															<div id='inventory_preord_middle_div_u" . $count . "'></div>		
													</td></tr>";
													echo $tmpTDstr;
												}
											}

											?>
										</table>
									</div>
								</td>
							</tr>
							<tr>
								<td height="10px"></td>
							</tr>
						<?php
						}
						?>
					</table>

					<br>
				</td>
			</tr>
		</table>
		<!-- Box High Value Opportunity -->

		<link rel="stylesheet" type="text/css" href="css/newstylechange.css" />
		<form>
			<table class="basic_style" width="100%" cellspacing="2" cellpadding="2" border="0">
				<tr>
					<td class="display_maintitle" valign="middle">
						Timing : <select class="basic_style select_box_fix_width" name="g_timing" id="g_timing">
							<option value="1" <?php if ($g_timing == "1") {
													echo "selected";
												} ?>>Rdy Now + Presell</option>
							<option value="2" <?php if ($g_timing == "2") {
													echo "selected";
												} ?>>FTL Rdy Now ONLY</option>
						</select>
						&nbsp;&nbsp;
						Status : <select class="basic_style select_box_fix_width" name="sort_g_tool" id="sort_g_tool">
							<option value="1" <?php if ($sort_g_tool == "2") {
													echo "selected";
												} ?>>Available to Sell</option>
							<option value="2" <?php if ($sort_g_tool == "2") {
													echo "selected";
												} ?>>Available to Sell + Potential to Get</option>
						</select>
						&nbsp;&nbsp;
						View: <select class="basic_style select_box_fix_width" name="sort_g_view" id="sort_g_view">
							<option value="1" <?php if ($sort_g_view == "2") {
													echo "selected";
												} ?>>UCB View</option>
							<option value="2" <?php if ($sort_g_view == "2") {
													echo "selected";
												} ?>>Customer Facing View</option>
						</select>
						&nbsp;
						Subtypes:<select name="search_subtype[]" id="search_subtype" multiple size=4 class="form_component select_box_fix_width">
							<?php
							$subtype_qry = "select * from loop_boxes_sub_type_master where active_flg=1 and box_type = 'Gaylord' order by display_order ASC";
							db();
							$subtype_res = db_query($subtype_qry);
							$ssubtype = $_REQUEST["search_subtype"];
							while ($subtype_row = array_shift($subtype_res)) {
							?>
								<option <?php echo  in_array($subtype_row["unqid"], $ssubtype) ? 'selected' : '' ?> value="<?php echo $subtype_row["unqid"]; ?>"><?php echo $subtype_row["sub_type_name"]; ?>
								</option>
							<?php
							}
							?>
						</select>
						&nbsp;

						<?php
						$total_tag = tep_db_num_rows($_REQUEST["search_tag"]);
						if ($total_tag > 1) {
							$stag = $_REQUEST["search_tag"];
						}
						if ($total_tag == 1) {
							$stag = $_REQUEST["search_tag"];
						}
						//print_r($stag); 
						?>

						Tag: <select name="search_tag[]" id="search_tag" multiple size=4 class="form_component select_box_fix_width">
							<?php
							$tagqry = "select * from loop_inv_tags order by freq_used desc";
							db();
							$tagres = db_query($tagqry);
							while ($tagrow = array_shift($tagres)) {
							?>
								<option <?php echo  in_array($tagrow["id"], isset($stag) ? (array)$stag : []) ? 'selected' : '' ?> value="<?php echo $tagrow["id"]; ?>"><?php echo $tagrow["tags"]; ?></option>
							<?php
							}
							?>
						</select>
						<?php
						$total_subtype = tep_db_num_rows($_REQUEST["search_subtype"]);
						if ($total_subtype > 1) {
							$ssubtype = $_REQUEST["search_subtype"];
						}
						if ($total_subtype == 1) {
							$ssubtype = $_REQUEST["search_subtype"];
						}
						//print_r($stag); 
						?>

						&nbsp;&nbsp;<input type="button" name="box_filter" id="box_filter" value="Filter" onClick="new_inventory_filter(); return false;">
					</td>
				</tr>
				<?php

				?>
			</table>
		</form>
		<div id="new_inv">
			<?php
			//$main_box_types=array("Gaylord","Shipping Boxes", "Supersacks", "Pallets", "Drums/Barrels/IBCs" );
			if (!isset($_REQUEST["sort"])) {
				$gy = array();
				$sb = array();
				$pal = array();
				$sup = array();
				$dbi = array();
				$recy = array();
				$_SESSION['sortarraygy'] = "";
				$_SESSION['sortarraysb'] = "";
				$_SESSION['sortarraysup'] = "";
				$_SESSION['sortarraydbi'] = "";
				$_SESSION['sortarraypal'] = "";
				$_SESSION['sortarrayrecy'] = "";
				//
				$x = 0;
				$newflg = "no";
				$preordercnt = 1;
				$box_type_str_arr = array("'Gaylord','GaylordUCB', 'PresoldGaylord'", "'Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'", "'PalletsUCB','PalletsnonUCB'", "'SupersackUCB','SupersacknonUCB','Supersacks'", "'DrumBarrelUCB','DrumBarrelnonUCB'");
				$box_type_cnt = 0;
				foreach ($box_type_str_arr as $box_type_str_arr_tmp) {
					//
					$box_type_cnt = $box_type_cnt + 1;

					if ($box_type_cnt == 1) {
						$box_type = "Gaylord";
					}
					if ($box_type_cnt == 2) {
						$box_type = "Shipping Boxes";
					}
					if ($box_type_cnt == 3) {
						$box_type = "Pallets";
					}
					if ($box_type_cnt == 4) {
						$box_type = "Supersacks";
					}
					if ($box_type_cnt == 5) {
						$box_type = "Drums/Barrels/IBCs";
					}
					//
					if ($sort_g_tool == 1) {
						$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (" . $box_type_str_arr_tmp . ")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) AND inventory.Active LIKE 'A' " . $filter_tag . " ORDER BY inventory.availability DESC";
					}
					if ($sort_g_tool == 2) {
						$box_query = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT FROM inventory  WHERE (inventory.box_type in (" . $box_type_str_arr_tmp . ")) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) AND inventory.Active LIKE 'A' " . $filter_tag . " ORDER BY inventory.availability DESC";
					}
					//
					//echo $box_query ."<br>";
					db_b2b();
					$act_inv_res = db_query($box_query);
					//echo tep_db_num_rows($act_inv_res)."<br>";
					if (tep_db_num_rows($act_inv_res) > 0) {
			?>
			<?php
						while ($inv = array_shift($act_inv_res)) {
							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$minfob = $b2b_fob;
							$b2b_fob = "$" . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2bcost = $b2b_cost;
							$b2b_cost = "$" . number_format($b2b_cost, 2);
							//
							$b2b_notes = $inv["N"];
							$b2b_notes_date = $inv["DT"];
							//
							$bpallet_qty = 0;
							$boxes_per_trailer = 0;
							$box_type = "";
							$loop_id = 0;
							$boxgoodvalue = 0;
							$actual_qty_calculated = "";
							$qry_sku = "select id, sku, bpallet_qty, boxes_per_trailer, type, bwall, boxgoodvalue, actual_qty_calculated from loop_boxes where b2b_id=" . $inv["I"];
							//echo $qry_sku."<br>";
							$sku = "";
							db();
							$dt_view_sku = db_query($qry_sku);
							while ($sku_val = array_shift($dt_view_sku)) {
								$loop_id = $sku_val['id'];
								$sku = $sku_val['sku'];
								$bpallet_qty = $sku_val['bpallet_qty'];
								$boxes_per_trailer = $sku_val['boxes_per_trailer'];
								$box_type = $sku_val['type'];
								$box_wall = $sku_val['bwall'];
								$boxgoodvalue = $sku_val['boxgoodvalue'];
								$actual_qty_calculated = $sku_val['actual_qty_calculated'];
							}
							if ($inv["location_zip"] != "") {
								if ($inv["availability"] != "-3.5") {
									$inv_id_list .= $inv["I"] . ",";
								}
								//To get the Actual PO, After PO
								$rec_found_box = "n";
								$actual_val = 0;
								$after_po_val = 0;
								$last_month_qty = 0;
								$pallet_val = "";
								$pallet_val_afterpo = "";
								$tmp_noofpallet = 0;
								$ware_house_boxdraw = "";
								$preorder_txt = "";
								$preorder_txt2 = "";
								$box_warehouse_id = 0;
								$next_load_available_date = "";
								//
								$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id, next_load_available_date, actual_qty_calculated from loop_boxes where b2b_id=" . $inv["I"];
								db();
								$dt_view = db_query($qry_loc);
								while ($loc_res = array_shift($dt_view)) {
									$territory = "";
									$box_warehouse_id = $loc_res["box_warehouse_id"];
									$next_load_available_date = $loc_res["next_load_available_date"];

									if ($loc_res["box_warehouse_id"] == "238") {
										$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
										$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
										db_b2b();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["shipCity"];
										$shipfrom_state = $loc_row["shipState"];
										$shipfrom_zip = $loc_row["shipZip"];
										//
										$territory = $loc_row["territory"];
										if ($territory == "Canada East") {
											$territory_sort = 1;
										}
										if ($territory == "East") {
											$territory_sort = 2;
										}
										if ($territory == "South") {
											$territory_sort = 3;
										}
										if ($territory == "Midwest") {
											$territory_sort = 4;
										}
										if ($territory == "North Central") {
											$territory_sort = 5;
										}
										if ($territory == "South Central") {
											$territory_sort = 6;
										}
										if ($territory == "Canada West") {
											$territory_sort = 7;
										}
										if ($territory == "Pacific Northwest") {
											$territory_sort = 8;
										}
										if ($territory == "West") {
											$territory_sort = 9;
										}
										if ($territory == "Canada") {
											$territory_sort = 10;
										}
										if ($territory == "Mexico") {
											$territory_sort = 11;
										}
										//
									} else {

										$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
										$get_loc_qry = "Select * from loop_warehouse where id ='" . $vendor_b2b_rescue_id . "'";
										db();
										$get_loc_res = db_query($get_loc_qry);
										$loc_row = array_shift($get_loc_res);
										$shipfrom_city = $loc_row["company_city"];
										$shipfrom_state = $loc_row["company_state"];
										$shipfrom_zip = $loc_row["company_zip"];
										//
										//
										//Find territory
										//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico

										$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
										$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
										$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
										$midwest = array('MI', 'OH', 'IN', 'KY');
										$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
										$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
										$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
										$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
										$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
										$canada = array();
										$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
										$territory_sort = 99;
										if (in_array($shipfrom_state, $canada_east, TRUE)) {
											$territory = "Canada East";
											$territory_sort = 1;
										} elseif (in_array($shipfrom_state, $east, TRUE)) {
											$territory = "East";
											$territory_sort = 2;
										} elseif (in_array($shipfrom_state, $south, TRUE)) {
											$territory = "South";
											$territory_sort = 3;
										} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
											$territory = "Midwest";
											$territory_sort = 4;
										} else if (in_array($shipfrom_state, $north_central, TRUE)) {
											$territory = "North Central";
											$territory_sort = 5;
										} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
											$territory = "South Central";
											$territory_sort = 6;
										} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
											$territory = "Canada West";
											$territory_sort = 7;
										} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
											$territory = "Pacific Northwest";
											$territory_sort = 8;
										} elseif (in_array($shipfrom_state, $west, TRUE)) {
											$territory = "West";
											$territory_sort = 9;
										} elseif (in_array($shipfrom_state, $canada, TRUE)) {
											$territory = "Canada";
											$territory_sort = 10;
										} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
											$territory = "Mexico";
											$territory_sort = 11;
										}
									}
								}
								$ship_from  = isset($shipfrom_city) . ", " . isset($shipfrom_state) . " " . isset($shipfrom_zip);
								$ship_from2 = isset($shipfrom_state);

								//
								$after_po_val_tmp = 0;
								$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where trans_id = " . $inv["loops_id"] . " order by warehouse, type_ofbox, Description";
								db_b2b();
								$dt_view_res_box = db_query($dt_view_qry);
								while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
									$rec_found_box = "y";
									$actual_val = $dt_view_res_box_data["actual"];
									$after_po_val_tmp = $dt_view_res_box_data["afterpo"];
									$last_month_qty = $dt_view_res_box_data["lastmonthqty"];
									//
								}
								if ($rec_found_box == "n") {
									$actual_val = $inv["actual_inventory"];
									$after_po_val = $inv["after_actual_inventory"];
									$last_month_qty = $inv["lastmonthqty"];
								}

								if ($box_warehouse_id == 238) {
									$after_po_val = $inv["after_actual_inventory"];
								} else {
									$after_po_val = $after_po_val_tmp;
								}
								//$after_po_val = $actual_qty_calculated;

								$to_show_rec = "y";

								if ($g_timing == 2) {
									$to_show_rec = "";
									if ($after_po_val >= $boxes_per_trailer) {
										$to_show_rec = "y";
									}
								}
								//if ($sort_g_tool == 2){
								//	$to_show_rec = "y";	
								//}

								if ($to_show_rec == "y") {
									//account owner
									if ($inv["vendor_b2b_rescue"] > 0) {

										$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
										$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
										db();
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									}
									//
									$vender_nm = "";
									if ($inv["vendor_b2b_rescue"] != "") {
										$q1 = "SELECT * FROM loop_warehouse where id = " . $inv["vendor_b2b_rescue"];
										db();
										$v_query = db_query($q1);
										while ($v_fetch = array_shift($v_query)) {
											$supplier_id = $v_fetch["b2bid"];
											$vender_nm = getnickname($v_fetch['company_name'], $v_fetch["b2bid"]);
											//$vender_nm = $v_fetch['company_name'];
											db_b2b();
											$com_qry = db_query("select * from companyInfo where ID='" . $v_fetch["b2bid"] . "'");
											$com_row = array_shift($com_qry);
										}
									}

									//
									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"];
									}

									$estimated_next_load = "";
									$b2bstatuscolor = "";
									if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
										//$next_load_available_date = $b2b_inv_row["next_load_available_date"];
										//echo "next_load_available_date - " . $inv["I"] . " " . $next_load_available_date . " " . $inv["lead_time"] . "<br>";

										//
										$now_date = time(); // or your date as well
										$next_load_date = strtotime($next_load_available_date);
										$datediff = $next_load_date - $now_date;
										$no_of_loaddays = round($datediff / (60 * 60 * 24));
										//echo $no_of_loaddays;
										if ($no_of_loaddays < $lead_time) {
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
											} else {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
											}
										} else {
											$estimated_next_load = "<font color=green>" . $no_of_loaddays . " Days</font>";
										}
										//
									} else {
										if ($after_po_val >= $boxes_per_trailer) {
											if ($inv["lead_time"] == 0) {
												$estimated_next_load = "<font color=green>Now</font>";
											}
											if ($inv["lead_time"] == 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Day</font>";
											}
											if ($inv["lead_time"] > 1) {
												$estimated_next_load = "<font color=green>" . $inv["lead_time"] . " Days</font>";
											}
										} else {
											if (($inv["expected_loads_per_mo"] <= 0) && ($after_po_val < $boxes_per_trailer)) {
												$estimated_next_load = "<font color=red>Never (sell the " . $after_po_val . ")</font>";
											} else {
												// logic changed by Zac
												$estimated_next_load = ceil((((($after_po_val / $boxes_per_trailer) * -1) + 1) / $inv["expected_loads_per_mo"]) * 4) . " Weeks";
											}
										}

										if ($after_po_val == 0 && $inv["expected_loads_per_mo"] == 0) {
											$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
										}

										if ($inv["expected_loads_per_mo"] == 0) {
											$expected_loads_per_mo = "<font color=red>0</font>";
										} else {
											$expected_loads_per_mo = $inv["expected_loads_per_mo"];
										}
									}
									//							

									if ($inv["lead_time"] <= 1) {
										$lead_time = "Next Day";
									} else {
										$lead_time = $inv["lead_time"] . " Days";
									}

									if ($inv["expected_loads_per_mo"] == 0) {
										$expected_loads_per_mo = "<font color=red>0</font>";
									} else {
										$expected_loads_per_mo = $inv["expected_loads_per_mo"];
									}
									//
									$b2b_status = $inv["b2b_status"];

									$estimated_next_load = $inv["buy_now_load_can_ship_in"];

									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									db();
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_name = $st_row["box_status"];
									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
									}
									//
									if ($inv["box_urgent"] == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_name = "URGENT";
									}
									//
									if ($inv["uniform_mixed_load"] == "Mixed") {
										$blength = $inv["blength_min"] . " - " . $inv["blength_max"];
										$bwidth = $inv["bwidth_min"] . " - " . $inv["bwidth_max"];
										$bdepth = $inv["bheight_min"] . " - " . $inv["bheight_max"];
									} else {
										$blength = $inv["lengthInch"];
										$bwidth = $inv["widthInch"];
										$bdepth = $inv["depthInch"];
									}
									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;
									//

									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;

									if ($inv["lengthFraction"] != "") {
										$arr_length = explode("/", $inv["lengthFraction"]);
										if (tep_db_num_rows($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if ($inv["widthFraction"] != "") {
										$arr_width = explode("/", $inv["widthFraction"]);
										if (tep_db_num_rows($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}
									if ($inv["depthFraction"] != "") {
										$arr_depth = explode("/", $inv["depthFraction"]);
										if (tep_db_num_rows($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}
									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if ($inv["box_urgent"] == 1) {
										$b_urgent = "Yes";
									}
									if ($inv["contracted"] == 1) {
										$contracted = "Yes";
									}
									if ($inv["prepay"] == 1) {
										$prepay = "Yes";
									}
									if ($inv["ship_ltl"] == 1) {
										$ship_ltl = "Yes";
									}
									//$tipStr = "Loops ID#: " . $loop_id . "<br>";
									$tipStr = "<b>Notes:</b> " . $inv["N"] . "<br>";
									if ($inv["DT"] != "0000-00-00") {
										$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($inv["DT"])) . "<br>";
									} else {
										$tipStr .= "<b>Notes Date:</b> <br>";
									}
									$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
									$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
									$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
									$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

									$tipStr .= "<b>Qty Avail:</b> " . $after_po_val . "<br>";
									$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $estimated_next_load . "<br>";
									$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $inv["expected_loads_per_mo"] . "<br>";
									$tipStr .= "<b>B2B Status:</b> " . $b2bstatus_name . "<br>";
									$tipStr .= "<b>Supplier Relationship Owner:</b> " . isset($ownername) . "<br>";
									$tipStr .= "<b>B2B ID#:</b> " . $inv["I"] . "<br>";
									$tipStr .= "<b>Description:</b> " . $inv["description"] . "<br>";
									$tipStr .= "<b>Supplier:</b> " .  $vender_nm . "<br>";
									$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
									$tipStr .= "<b>Territory:</b> " . isset($territory) . "<br>";
									$tipStr .= "<b>Per Pallet:</b> " . $bpallet_qty . "<br>";
									$tipStr .= "<b>Per Truckload:</b> " . $boxes_per_trailer . "<br>";
									$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
									$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
									//

									//Get data in array
									if ($box_type_cnt == 1) {
										$gy[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => isset($territory_sort));
									}
									if ($box_type_cnt == 2) {
										$sb[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => isset($territory_sort));
									}
									if ($box_type_cnt == 3) {
										$pal[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => isset($territory_sort));
									}
									if ($box_type_cnt == 4) {
										$sup[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => isset($territory_sort));
									}
									if ($box_type_cnt == 5) {
										$dbi[] = array('boxgoodvalue' => $boxgoodvalue, 'after_po_val' => $after_po_val, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo,  'b2b_fob' => $b2b_fob, 'b2bid' => $inv["I"], 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_name, 'b2bstatuscolor' => $b2bstatuscolor, 'length' => $length, 'width' => $width, 'depth' => $depth, 'description' =>  $inv["description"], 'vendor_nm' => $vender_nm, 'ship_from' => $ship_from, 'ship_from2' => $ship_from2, 'ownername' => isset($ownername), 'b2b_notes' => $inv["N"], 'b2b_notes_date' => $inv["DT"], 'box_wall' => isset($box_wall), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'bpallet_qty' => $bpallet_qty, 'vendor_b2b_rescue_id' => isset($vendor_b2b_rescue_id), 'binv' => 'nonucb', 'territory_sort' => isset($territory_sort));
									}
									//	
								} //end $to_show_rec == "y"
							} //end if ($inv["location_zip"] != "")	
							//
						} //End while $inv
					} //End check num rows>0

					//Ucbowned
					if ($sort_g_tool == 1) {
						$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2) order by warehouse, type_ofbox, Description";
					}
					if ($sort_g_tool == 2) {
						$dt_view_qry = "SELECT * from tmp_inventory_list_set2 where wid <> 238 and (type_ofbox in ($box_type_str_arr_tmp)) and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2) order by warehouse, type_ofbox, Description";
					}
					//echo $dt_view_qry;
					db_b2b();
					$dt_view_res = db_query($dt_view_qry);
					$tmpwarenm = "";
					$tmp_noofpallet = 0;
					$ware_house_boxdraw = "";
					while ($dt_view_row = array_shift($dt_view_res)) {
						$b2bid_tmp = 0;
						$boxes_per_trailer_tmp = 0;
						$bpallet_qty_tmp = 0;
						$vendor_id = 0;
						$vendor_b2b_rescue_id = 0;
						$actual_qty_calculated = "";
						$qry_loopbox = "select b2b_id, boxes_per_trailer, bpallet_qty, vendor, b2b_status, box_warehouse_id, expected_loads_per_mo, actual_qty_calculated from loop_boxes where id=" . $dt_view_row["trans_id"];
						db();
						$dt_view_loopbox = db_query($qry_loopbox);
						while ($rs_loopbox = array_shift($dt_view_loopbox)) {
							$b2bid_tmp = $rs_loopbox['b2b_id'];
							$boxes_per_trailer_tmp = $rs_loopbox['boxes_per_trailer'];
							$bpallet_qty_tmp = $rs_loopbox['bpallet_qty'];
							$vendor_id = $rs_loopbox['vendor'];
							$vendor_b2b_rescue_id = $rs_loopbox['box_warehouse_id'];
							$actual_qty_calculated = $rs_loopbox['actual_qty_calculated'];
						}

						$inv_availability = "";
						$distC = 0;
						$inv_notes = "";
						$inv_notes_dt = "";

						$inv_qry = "SELECT * from inventory where ID = " . $b2bid_tmp . " " . $filter_tag;
						db_b2b();
						$dt_view_inv_res = db_query($inv_qry);
						while ($dt_view_row_inv = array_shift($dt_view_inv_res)) {
							$inv_notes = $dt_view_row_inv["notes"];
							$inv_notes_dt = $dt_view_row_inv["date"];
							$location_city = $dt_view_row_inv["location_city"];
							$location_state = $dt_view_row_inv["location_state"];
							$location_zip = $dt_view_row_inv["location_zip"];
							$vendor_b2b_rescue = $dt_view_row_inv["vendor_b2b_rescue"];
							$vendor_id = $dt_view_row_inv["vendor"];

							if (isset($inv["lead_time"]) <= 1) {
								$lead_time = "Next Day";
							} else {
								$lead_time = $dt_view_row_inv["lead_time"] . " Days";
							}
							//
							$b2bstatus = $dt_view_row_inv['b2bstatus'];
							$expected_loads_permo = $dt_view_row_inv['expected_loads_permo'];

							//account owner
							if ($vendor_b2b_rescue > 0) {
								$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
								db();
								$query = db_query($q1);
								while ($fetch = array_shift($query)) {
									$supplier_id = $fetch["b2bid"];
									$vender_name = getnickname($fetch['company_name'], $fetch["b2bid"]);
									//
									$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.ID=" . $fetch["b2bid"];
									db_b2b();
									$comres = db_query($comqry);
									while ($comrow = array_shift($comres)) {
										$ownername = $comrow["initials"];
									}
								}
							}
							$tmp_zipval = "";
							$tmppos_1 = strpos($dt_view_row_inv["location_zip"], " ");
							if ($tmppos_1 != false) {
								$tmp_zipval = str_replace(" ", "", $dt_view_row_inv["location_zip"]);
								$zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
							} else {
								$zipStr = "Select * from ZipCodes WHERE zip = '" . intval($dt_view_row_inv["location_zip"]) . "'";
							}
							if ($dt_view_row_inv["location_zip"] != "") {
								db_b2b();
								$dt_view_res3 = db_query($zipStr);
								while ($ziploc = array_shift($dt_view_res3)) {
									$locLat = $ziploc["latitude"];

									$locLong = $ziploc["longitude"];
								}
							}
						}
						$minfob = $dt_view_row["min_fob"];
						$b2bcost = $dt_view_row["b2b_cost"];
						$b2b_fob = "$" . number_format($dt_view_row["min_fob"], 2);
						$b2b_cost = "$" . number_format($dt_view_row["cost"], 2);

						$sales_order_qty = $dt_view_row["sales_order_qty"];

						if (($dt_view_row["actual"] != 0) or ($dt_view_row["actual"] - $sales_order_qty != 0)) {
							$lastmonth_val = $dt_view_row["lastmonthqty"];

							$reccnt = 0;
							if ($sales_order_qty > 0) {
								$reccnt = $sales_order_qty;
							}

							$preorder_txt = "";
							$preorder_txt2 = "";

							if ($reccnt > 0) {
								$preorder_txt = "<u>";
								$preorder_txt2 = "</u>";
							}

							if (($dt_view_row["actual"] >= $boxes_per_trailer_tmp) && ($boxes_per_trailer_tmp > 0)) {
								$bg = "yellow";
							}

							$pallet_val = 0;
							$pallet_val_afterpo = 0;
							$actual_po_tmp = $dt_view_row["actual"] - $sales_order_qty;

							if ($bpallet_qty_tmp > 0) {
								$pallet_val = number_format($dt_view_row["actual"] / $bpallet_qty_tmp, 1, '.', '');
								$pallet_val_afterpo = number_format($actual_po_tmp / $bpallet_qty_tmp, 1, '.', '');
							}

							$to_show_rec1 = "y";

							if ($to_show_rec1 == "y") {
								$pallet_space_per = "";

								if ($pallet_val > 0) {
									$tmppos_1 = strpos($pallet_val, '.');
									if ($tmppos_1 != false) {
										if (intval(substr($pallet_val, strpos($pallet_val, '.') + 1, 1)) > 0) {
											$pallet_val_temp = $pallet_val;
											$pallet_val = " (" . $pallet_val_temp . ")";
										} else {
											$pallet_val_format = number_format(floatval($pallet_val), 0);
											$pallet_val = " (" . $pallet_val_format . ")";
										}
									} else {
										$pallet_val_format = number_format(floatval($pallet_val), 0);
										$pallet_val = " (" . $pallet_val_format . ")";
									}
								} else {
									$pallet_val = "";
								}

								if ($pallet_val_afterpo > 0) {
									//reg_format = '/^\d+(?:,\d+)*$/';
									$tmppos_1 = strpos($pallet_val_afterpo, '.');
									if ($tmppos_1 != false) {
										if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo, '.') + 1, 1)) > 0) {
											$pallet_val_afterpo_temp = $pallet_val_afterpo;
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
										} else {
											$pallet_val_afterpo_format = number_format(floatval($pallet_val_afterpo), 0);
											$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
										}
									} else {
										$pallet_val_afterpo_format = number_format(floatval($pallet_val_afterpo), 0);
										$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
									}
								} else {
									$pallet_val_afterpo = "";
								}
								//
								if ($vendor_b2b_rescue_id == 238) {
									$actual_po = isset($dt_view_row_inv["after_actual_inventory"]);
								} else {
									$actual_po = $actual_po_tmp;
								}
								//$actual_po = $actual_qty_calculated;
								//
								$to_show_rec = "y";
								if ($g_timing == 2) {
									$to_show_rec = "";
									if ($actual_po >= $boxes_per_trailer_tmp) {
										$to_show_rec = "y";
									}
								}

								//if ($sort_g_tool == 2){
								//	$to_show_rec = "y";	
								//}
								//
								if ($to_show_rec == "y") {

									if ($actual_po >= $boxes_per_trailer_tmp) {
										//=IF(B4>0,"NOW",ROUNDUP(((((B4/R4)*-1)+1)/D4)*4,0))

										if (isset($dt_view_row_inv["lead_time"]) == 0) {
											$estimated_next_load = "<font color=green>Now</font>";
										}

										if (isset($dt_view_row_inv["lead_time"]) == 1) {
											$estimated_next_load = "<font color=green>" . isset($dt_view_row_inv["lead_time"]) . " Day</font>";
										}
										if (isset($dt_view_row_inv["lead_time"]) > 1) {
											$estimated_next_load = "<font color=green>" . isset($dt_view_row_inv["lead_time"]) . " Days</font>";
										}
									} else {
										if ((isset($dt_view_row_inv["expected_loads_per_mo"]) <= 0) && ($actual_po < $boxes_per_trailer_tmp)) {
											$estimated_next_load = "<font color=red>Never (sell the " . $actual_po . ")</font>";
										} else {
											$estimated_next_load = ceil((((($actual_po / $boxes_per_trailer_tmp) * -1) + 1) / isset($dt_view_row_inv["expected_loads_per_mo"])) * 4) . " Weeks";
										}
									}

									if ($actual_po == 0 && isset($dt_view_row_inv["expected_loads_per_mo"]) == 0) {
										$estimated_next_load = "<font color=red>Ask Purch Rep</font>";
									}

									if (isset($dt_view_row_inv["expected_loads_per_mo"]) == 0) {
										$expected_loads_per_mo = "<font color=red>0</font>";
									} else {
										$expected_loads_per_mo = isset($dt_view_row_inv["expected_loads_per_mo"]);
									}

									$blength = isset($dt_view_row_inv["lengthInch"]);
									$bwidth = isset($dt_view_row_inv["widthInch"]);
									$bdepth = isset($dt_view_row_inv["depthInch"]);
									$blength_frac = 0;
									$bwidth_frac = 0;
									$bdepth_frac = 0;

									$length = $blength;
									$width = $bwidth;
									$depth = $bdepth;

									if (isset($dt_view_row_inv["lengthFraction"]) != "") {
										$arr_length = isset($dt_view_row_inv["lengthFraction"]) ? explode("/", $dt_view_row_inv["lengthFraction"]) : array();
										if (tep_db_num_rows($arr_length) > 0) {
											$blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
											$length = floatval($blength + $blength_frac);
										}
									}
									if (isset($dt_view_row_inv["widthFraction"]) != "") {
										if (isset($dt_view_row_inv["widthFraction"])) {
											$arr_width = explode("/", $dt_view_row_inv["widthFraction"]);
										} else {
											$arr_width = array();
										}
										if (tep_db_num_rows($arr_width) > 0) {
											$bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
											$width = floatval($bwidth + $bwidth_frac);
										}
									}

									if (isset($dt_view_row_inv["depthFraction"]) != "") {
										if (isset($dt_view_row_inv["depthFraction"])) {
											$arr_depth = explode("/", $dt_view_row_inv["depthFraction"]);
										} else {
											$arr_depth = array();
										}
										if (tep_db_num_rows($arr_depth) > 0) {
											$bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
											$depth = floatval($bdepth + $bdepth_frac);
										}
									}

									//
									$estimated_next_load = isset($dt_view_row_inv["buy_now_load_can_ship_in"]);

									$b2b_status = $dt_view_row["b2b_status"];

									$st_query = "select * from b2b_box_status where status_key='" . $b2b_status . "'";
									//echo $st_query;
									db();
									$st_res = db_query($st_query);
									$st_row = array_shift($st_res);
									$b2bstatus_nametmp = $st_row["box_status"];

									if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
										$b2bstatuscolor = "green";
									} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
										$b2bstatuscolor = "orange";
									}

									if (isset($dt_view_row_inv["box_urgent"]) == 1) {
										$b2bstatuscolor = "red";
										$b2bstatus_nametmp = "URGENT";
									}

									//
									$qry_loc = "select id, box_warehouse_id,vendor_b2b_rescue, boxgoodvalue from loop_boxes where b2b_id=" . $dt_view_row["trans_id"];
									db();
									$dt_view = db_query($qry_loc);
									while ($loc_res = array_shift($dt_view)) {
										$territory = "";
										$boxgoodvalue = $loc_res["boxgoodvalue"];
										if ($loc_res["box_warehouse_id"] == "238") {
											$vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
											$get_loc_qry = "Select * from companyInfo where loopid = " . $vendor_b2b_rescue_id;
											db_b2b();
											$get_loc_res = db_query($get_loc_qry);
											$loc_row = array_shift($get_loc_res);
											$shipfrom_city = $loc_row["shipCity"];
											$shipfrom_state = $loc_row["shipState"];
											$shipfrom_zip = $loc_row["shipZip"];
											//
											$territory = $loc_row["territory"];
											//
											if ($territory == "Canada East") {
												$territory_sort = 1;
											}
											if ($territory == "East") {
												$territory_sort = 2;
											}
											if ($territory == "South") {
												$territory_sort = 3;
											}
											if ($territory == "Midwest") {
												$territory_sort = 4;
											}
											if ($territory == "North Central") {
												$territory_sort = 5;
											}
											if ($territory == "South Central") {
												$territory_sort = 6;
											}
											if ($territory == "Canada West") {
												$territory_sort = 7;
											}
											if ($territory == "Pacific Northwest") {
												$territory_sort = 8;
											}
											if ($territory == "West") {
												$territory_sort = 9;
											}
											if ($territory == "Canada") {
												$territory_sort = 10;
											}
											if ($territory == "Mexico") {
												$territory_sort = 11;
											}
										} else {

											$vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
											$get_loc_qry = "Select * from loop_warehouse where id = '" . $vendor_b2b_rescue_id . "'";
											db();
											$get_loc_res = db_query($get_loc_qry);
											$loc_row = array_shift($get_loc_res);
											$shipfrom_city = $loc_row["company_city"];
											$shipfrom_state = $loc_row["company_state"];
											$shipfrom_zip = $loc_row["company_zip"];
											//
											//
											//Find territory
											//Canada East, East, South, Midwest, North Central, South Central, Canada West, Pacific Northwest, West, Canada, Mexico

											$canada_east = array('NB', 'NF', 'NS', 'ON', 'PE', 'QC');
											$east = array('ME', 'NH', 'VT', 'MA', 'RI', 'CT', 'NY', 'PA', 'MD', 'VA', 'WV', 'NJ', 'DC', 'DE'); //14
											$south = array('NC', 'SC', 'GA', 'AL', 'MS', 'TN', 'FL');
											$midwest = array('MI', 'OH', 'IN', 'KY');
											$north_central = array('ND', 'SD', 'NE', 'MN', 'IA', 'IL', 'WI');
											$south_central = array('LA', 'AR', 'MO', 'TX', 'OK', 'KS', 'CO', 'NM');
											$canada_west = array('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT');
											$pacific_northwest = array('WA', 'OR', 'ID', 'MT', 'WY', 'AK');
											$west = array('CA', 'NV', 'UT', 'AZ', 'HI');
											$canada = array();
											$mexico = array('AG', 'BS', 'CH', 'CL', 'CM', 'CO', 'CS', 'DF', 'DG', 'GR', 'GT', 'HG', 'JA', 'ME', 'MI', 'MO', 'NA', 'NL', 'OA', 'PB', 'QE', 'QR', 'SI', 'SL', 'SO', 'TB', 'TL', 'TM', 'VE', 'ZA');
											$territory_sort = 99;
											if (in_array($shipfrom_state, $canada_east, TRUE)) {
												$territory = "Canada East";
												$territory_sort = 1;
											} elseif (in_array($shipfrom_state, $east, TRUE)) {
												$territory = "East";
												$territory_sort = 2;
											} elseif (in_array($shipfrom_state, $south, TRUE)) {
												$territory = "South";
												$territory_sort = 3;
											} elseif (in_array($shipfrom_state, $midwest, TRUE)) {
												$territory = "Midwest";
												$territory_sort = 4;
											} else if (in_array($shipfrom_state, $north_central, TRUE)) {
												$territory = "North Central";
												$territory_sort = 5;
											} elseif (in_array($shipfrom_state, $south_central, TRUE)) {
												$territory = "South Central";
												$territory_sort = 6;
											} elseif (in_array($shipfrom_state, $canada_west, TRUE)) {
												$territory = "Canada West";
												$territory_sort = 7;
											} elseif (in_array($shipfrom_state, $pacific_northwest, TRUE)) {
												$territory = " Pacific Northwest";
												$territory_sort = 8;
											} elseif (in_array($shipfrom_state, $west, TRUE)) {
												$territory = "West";
												$territory_sort = 9;
											} elseif (in_array($shipfrom_state, $canada, TRUE)) {
												$territory = "Canada";
												$territory_sort = 10;
											} elseif (in_array($shipfrom_state, $mexico, TRUE)) {
												$territory = "Mexico";
												$territory_sort = 11;
											}
										}
									}
									$ship_from_tmp  = isset($shipfrom_city) . ", " . isset($shipfrom_state) . " " . isset($shipfrom_zip);
									$ship_from2_tmp = isset($shipfrom_state);
									//

									//
									//
									$b_urgent = "No";
									$contracted = "No";
									$prepay = "No";
									$ship_ltl = "No";
									if (isset($dt_view_row_inv["box_urgent"]) == 1) {
										$b_urgent = "Yes";
									}
									if (isset($dt_view_row_inv["contracted"]) == 1) {
										$contracted = "Yes";
									}
									if (isset($dt_view_row_inv["prepay"]) == 1) {
										$prepay = "Yes";
									}
									if (isset($dt_view_row_inv["ship_ltl"]) == 1) {
										$ship_ltl = "Yes";
									}

									//
									$btemp = str_replace(' ', '', $dt_view_row["LWH"]);
									$boxsize = explode("x", $btemp);
									//Ucb owned data
									//echo $box_type_cnt."<br>";
									if ($box_type_cnt == 1) {
										$gy[] = array('boxgoodvalue' => isset($boxgoodvalue), 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => "testN " . $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => isset($dt_view_row_inv["bwall"]), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => isset($territory_sort));
									}
									if ($box_type_cnt == 2) {
										$sb[] = array('boxgoodvalue' => isset($boxgoodvalue), 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => "testN " . $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => isset($dt_view_row_inv["bwall"]), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => isset($territory_sort));
									}
									if ($box_type_cnt == 3) {
										$pal[] = array('boxgoodvalue' => isset($boxgoodvalue), 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => "testN " . $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => isset($dt_view_row_inv["bwall"]), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => isset($territory_sort));
									}
									if ($box_type_cnt == 4) {
										$sup[] = array('boxgoodvalue' => isset($boxgoodvalue), 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => "testN " . $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => isset($dt_view_row_inv["bwall"]), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => isset($territory_sort));
									}
									if ($box_type_cnt == 5) {
										$dbi[] = array('boxgoodvalue' => isset($boxgoodvalue), 'after_po_val' => $actual_po, 'pallet_val_afterpo' => $pallet_val_afterpo, 'boxes_per_trailer' => $boxes_per_trailer_tmp, 'preorder_txt2' => $preorder_txt2, 'estimated_next_load' => $estimated_next_load, 'expected_loads_per_mo' => $expected_loads_per_mo, 'b2b_fob' => $b2b_fob, 'b2bid' => $b2bid_tmp, 'territory' => isset($territory), 'b2bstatus_name' => $b2bstatus_nametmp, 'b2bstatuscolor' => isset($b2bstatuscolor), 'length' => $boxsize[0], 'width' => $boxsize[1], 'depth' => $boxsize[2], 'description' => "testN " . $dt_view_row["Description"], 'vendor_nm' => isset($vender_name), 'ship_from' => $ship_from_tmp, 'ship_from2' => $ship_from2_tmp, 'ownername' => isset($ownername), 'b2b_notes' => $inv_notes, 'b2b_notes_date' => $inv_notes_dt, 'box_wall' => isset($dt_view_row_inv["bwall"]), 'b_urgent' => $b_urgent, 'contracted' => $contracted, 'prepay' => $prepay, 'ship_ltl' => $ship_ltl, 'supplier_id' => isset($supplier_id), 'b2b_cost' => $b2b_cost, 'minfob' => $minfob,  'b2bcost' => $b2bcost, 'vendor_b2b_rescue_id' => $vendor_b2b_rescue_id, 'bpallet_qty' => $bpallet_qty_tmp, 'binv' => 'ucbown', 'territory_sort' => isset($territory_sort));
									}
									//
									//$pallet_space_per = "";

									//----------------------------------------------------------------
								} //end if ($to_show_rec == "y")
							} //End if ($to_show_rec1 == "y")	

						} //if (($dt_view_row["actual"] != 0) OR ($dt_view_row["actual"] - $sales_order_qty !=0 )
					} //while ($dt_view_row
					$_SESSION['sortarraygy'] = $gy;
					$_SESSION['sortarraysb'] = $sb;
					$_SESSION['sortarraysup'] = $sup;
					$_SESSION['sortarraydbi'] = $dbi;
					$_SESSION['sortarraypal'] = $pal;
					//}									
				} //foreach array loop
			}
			//
			?>
			<table width="100%" border="0" cellspacing="1" cellpadding="1" class="basic_style">
				<?php
				$x = 0;
				$boxtype_cnt = 0;
				$sorturl = "dashboardnew.php?show=inventory_new&sort_g_view=" . $sort_g_view . "&sort_g_tool=" . $sort_g_tool . "&g_timing=" . $g_timing;
				$box_name_arr = array('gy', 'sb', 'pal', 'sup', 'dbi');
				foreach ($box_name_arr as $box_name) {
					//
					if ($box_name == "gy") {
						$boxtype = "Gaylord";
						$boxtype_cnt = 1;
					}
					if ($box_name == "sb") {
						$boxtype = "Shipping Boxes";
						$boxtype_cnt = 2;
					}
					if ($box_name == "pal") {
						$boxtype = "Pallets";
						$boxtype_cnt = 3;
					}
					if ($box_name == "sup") {
						$boxtype = "Supersacks";
						$boxtype_cnt = 4;
					}
					if ($box_name == "dbi") {
						$boxtype = "Drums/Barrels/IBCs";
						$boxtype_cnt = 5;
					}

					//
					$MGarray = $_SESSION['sortarray' . $box_name];
					$MGArraysort_I = array();
					$MGArraysort_II = array();
					$MGArraysort_III = array();
					foreach ($MGarray as $MGArraytmp) {
						$MGArraysort_I[] = $MGArraytmp['territory_sort'];
						$MGArraysort_II[] = $MGArraytmp['vendor_nm'];
						$MGArraysort_III[] = $MGArraytmp['depth'];
					}
					//print_r($MGarray)."<br>";
					array_multisort($MGArraysort_I, SORT_ASC, $MGArraysort_II, SORT_ASC, $MGArraysort_III, SORT_ASC, $MGarray);
					//
					//print_r($MGarray);
					$total_rec = tep_db_num_rows($MGarray);
					if ($total_rec > 0) {

				?>
						<tr>
							<td class="display_maintitle" align="center">Active Inventory Items - <?php echo isset($boxtype); ?>
							</td>
						</tr>
						<tr>
							<td>
								<div id="btype<?php echo $boxtype_cnt; ?>">
									<table width="100%" cellspacing="1" cellpadding="2">
										<?php if ((isset($sort_g_view)) && ($sort_g_view == "1")) { ?>
											<tr>
												<td class='display_title'>Qty Avail&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="80px">Buy Now, Load Can Ship In&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>MIN FOB&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B Status&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(8,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(8,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Supplier&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(14,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(14,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="72px">Ship From&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="70px">Rep&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(16,1,<?php echo isset($boxtype_cnt); ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(16,2,<?php echo $box_type_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Sales Team Notes&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(17,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(17,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Last Notes Date&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(18,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(18,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
											</tr>
										<?php
										}
										if ((isset($sort_g_view)) && ($sort_g_view == "2")) {
										?>
											<tr>
												<td class='display_title'>Qty Avail<a href="javascript:void();" onclick="displayboxdata_invnew(1,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(1,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title' width="80px">Buy Now, Load Can Ship In&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(2,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Exp #<br>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(3,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Per<br>TL&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(4,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Cost&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(19,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>FOB Origin Price/Unit&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(5,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>B2B ID&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(6,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Territory&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(7,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>L&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(9,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>W&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(10,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>x</td>

												<td align="center" class='display_title'>H&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(11,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td align="center" class='display_title'>Walls&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(12,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Description&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(13,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>

												<td class='display_title'>Ship From&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,1,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayboxdata_invnew(15,2,<?php echo $boxtype_cnt; ?>);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></td>
											</tr>

										<?php
										}
										?>
										<?php
										$count_arry = 0;
										$count = 0;
										$row_cnt = 0;
										foreach ($MGarray as $MGArraytmp2) {
											//
											$count = $count + 1;
											if ($MGArraytmp2["binv"] == "nonucb") {
												$binv = "";
											}
											if ($MGArraytmp2["binv"] == "ucbown") {
												$binv = "<b>UCB Owned Inventory </b><br>";
											}
											//
											$tipStr = "<b>Notes:</b> " . $MGArraytmp2["b2b_notes"] . "<br>";
											if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
												$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"])) . "<br>";
											} else {
												$tipStr .= "<b>Notes Date:</b> <br>";
											}
											$tipStr .= "<b>Urgent:</b> " . $MGArraytmp2["b_urgent"] . "<br>";
											$tipStr .= "<b>Contracted:</b> " . $MGArraytmp2["contracted"] . "<br>";
											$tipStr .= "<b>Prepay:</b> " . $MGArraytmp2["prepay"] . "<br>";
											$tipStr .= "<b>Can Ship LTL?</b> " . $MGArraytmp2["ship_ltl"] . "<br>";

											$tipStr .= "<b>Qty Avail:</b> " . $MGArraytmp2["after_po_val"] . "<br>";
											$tipStr .= "<b>Buy Now, Load Can Ship In:</b> " . $MGArraytmp2["estimated_next_load"] . "<br>";
											$tipStr .= "<b>Expected # of Loads/Mo:</b> " . $MGArraytmp2["expected_loads_per_mo"] . "<br>";
											$tipStr .= "<b>B2B Status:</b> " . $MGArraytmp2["b2bstatus_name"] . "<br>";
											$tipStr .= "<b>Supplier Relationship Owner:</b> " . $MGArraytmp2["ownername"] . "<br>";
											$tipStr .= "<b>B2B ID#:</b> " . $MGArraytmp2["b2bid"] . "<br>";
											$tipStr .= "<b>Description:</b> " . $MGArraytmp2["description"] . "<br>";
											$tipStr .= "<b>Supplier:</b> " .  $MGArraytmp2["vendor_nm"] . "<br>";
											$tipStr .= "<b>Ship From:</b> " . $MGArraytmp2["ship_from"] . "<br>";
											$tipStr .= "<b>Territory:</b> " . $MGArraytmp2["territory"] . "<br>";
											$tipStr .= "<b>Per Pallet:</b> " . $MGArraytmp2["bpallet_qty"] . "<br>";
											$tipStr .= "<b>Per Truckload:</b> " . $MGArraytmp2["boxes_per_trailer"] . "<br>";
											$tipStr .= "<b>Min FOB:</b> " . $MGArraytmp2["b2b_fob"] . "<br>";
											$tipStr .= "<b>B2B Cost:</b> " . $MGArraytmp2["b2b_cost"] . "<br>";
											$tipStr .= isset($binv);
											//
											if ($row_cnt == 0) {
												$display_table_css = "display_table";
												$row_cnt = 1;
											} else {
												$row_cnt = 0;
												$display_table_css = "display_table_alt";
											}
											//
											$loopid = get_loop_box_id($MGArraytmp2["b2bid"]);
											$vendornme = $MGArraytmp2["vendor_nm"];

											//
											$sales_order_qty = 0;
											if ($MGArraytmp2["vendor_b2b_rescue_id"] > 0) {
												$dt_so_item = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
												$dt_so_item .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
												$dt_so_item .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
												$dt_so_item .= " WHERE loop_salesorders.box_id = " . $loopid . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
												db();
												$dt_res_so_item = db_query($dt_so_item);
												while ($so_item_row = array_shift($dt_res_so_item)) {
													if ($so_item_row["sumqty"] > 0) {
														$sales_order_qty = $so_item_row["sumqty"];
													}
												}
											}
											//
											if ((isset($sort_g_view)) && ($sort_g_view == "1")) {
												$tmpTDstr = "<tr  >";

												$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
												if ($MGArraytmp2["after_po_val"] < 0) {

													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
													$tmpTDstr =  $tmpTDstr . "<div";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												} else {
													$tmpTDstr =  $tmpTDstr . "<div ";
													if ($sales_order_qty > 0) {
														$tmpTDstr =  $tmpTDstr . " onclick='display_preoder_sel($count, $loopid)'  class='popup_qty'";
													}
													$tmpTDstr =  $tmpTDstr . "><font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</div></td>";
												}
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"], 2) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><font color='" . $MGArraytmp2["b2bstatuscolor"] . "'>" . $MGArraytmp2["b2bstatus_name"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . "<a target='_blank' href='manage_box_b2bloop.php?id=" . get_loop_box_id($MGArraytmp2["b2bid"]) . "&proc=View&'";
												$tmpTDstr =  $tmpTDstr . " onmouseover=\"Tip('" . str_replace("'", "\'", $tipStr) . "')\" onmouseout=\"UnTip()\"";

												//echo " >" ;
												$tmpTDstr =  $tmpTDstr . " >";

												$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'><a target='_blank' href='viewCompany.php?ID=" . $MGArraytmp2["supplier_id"] . "'>" . $MGArraytmp2["vendor_nm"] . "</a></td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ownername"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";
												if ($MGArraytmp2["b2b_notes_date"] != "0000-00-00") {
													$tmpTDstr =  $tmpTDstr . date("m/d/Y", strtotime($MGArraytmp2["b2b_notes_date"]));
												}
												$tmpTDstr =  $tmpTDstr . "</td>";

												$tmpTDstr =  $tmpTDstr . "</tr>";
												//
												$tmpTDstr =  $tmpTDstr . "<tr id='inventory_preord_top_" . $count . "' align='middle' style='display:none;'>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td colspan='16'>
															<div id='inventory_preord_middle_div_" . $count . "'></div>		
													</td></tr>";
											}
											if ((isset($sort_g_view)) && ($sort_g_view == "2")) {
												$tmpTDstr = "<tr  >";

												$tmpTDstr =  $tmpTDstr . "<td  class='$display_table_css'>";
												if ($MGArraytmp2["after_po_val"] < 0) {
													$tmpTDstr =  $tmpTDstr . "<font color='blue'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
												} else if ($MGArraytmp2["after_po_val"] >= $MGArraytmp2["boxes_per_trailer"]) {
													$tmpTDstr =  $tmpTDstr . "<font color='green'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
												} else {
													$tmpTDstr =  $tmpTDstr . "<font color='black'>" . number_format($MGArraytmp2["after_po_val"], 0) . $MGArraytmp2["pallet_val_afterpo"] . $MGArraytmp2["preorder_txt2"] . "</td>";
												}
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["estimated_next_load"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["expected_loads_per_mo"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . number_format($MGArraytmp2["boxes_per_trailer"], 0) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >$" . number_format($MGArraytmp2["boxgoodvalue"], 2) . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2b_fob"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css' >" . $MGArraytmp2["b2bid"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["territory"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css' width='40px'>" . $MGArraytmp2["length"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["width"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center' class='$display_table_css'> x </td>";

												$tmpTDstr =  $tmpTDstr . "<td  align='center'  class='$display_table_css' width='40px'>" . $MGArraytmp2["depth"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["box_wall"] . "</td>";
												//
												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>";

												$tmpTDstr =  $tmpTDstr . $MGArraytmp2["description"] . "</td>";

												/*$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["vendor_nm"] . "</td>";*/

												$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["ship_from2"] . "</td>";

												//$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes"] . "</td>";

												//$tmpTDstr =  $tmpTDstr . "<td class='$display_table_css'>" . $MGArraytmp2["b2b_notes_date"] . "</td>";

												$tmpTDstr =  $tmpTDstr . "</tr>";
											}
											echo isset($tmpTDstr);
										}
										?>
									</table>
								</div>
							</td>
						</tr>
						<tr>
							<td height="10px"></td>
						</tr>
				<?php
					}
				}
				?>
			</table>
		</div>
	<?php
		//
	}
	//End New match inventory
	//-----------------------------------------------------------------------------
	?>
	<?php
	function showinventory_fordashboard_invnew(int $warehouseid_selected): void
	{

		echo "<script type=\"text/javascript\">";
		echo "function display_preoder() {";
		echo " var totcnt = document.getElementById('inventory_preord_totctl').value;";

		echo " for (var tmpcnt = 1; tmpcnt < totcnt; tmpcnt++) {";
		echo " if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') ";
		echo " { document.getElementById('inventory_preord_top_' + tmpcnt).style.display='none'; } else {";
		echo "  document.getElementById('inventory_preord_top_' + tmpcnt).style.display='table-row'; } ";

		echo " if (document.getElementById('inventory_preord_top2_' + tmpcnt).style.display == 'table-row') ";
		echo " { document.getElementById('inventory_preord_top2_' + tmpcnt).style.display='none'; } else {";
		echo "  document.getElementById('inventory_preord_top2_' + tmpcnt).style.display='table-row'; } ";

		echo " if (document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display == 'table-row') ";
		echo " { document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display='none'; } else {";
		echo "  document.getElementById('inventory_preord_bottom_' + tmpcnt).style.display='table-row'; } ";

		echo " var totcnt_child = document.getElementById('inventory_preord_bottom_hd'+ tmpcnt).value;";

		echo " for (var tmpcnt_n = 1; tmpcnt_n < totcnt_child; tmpcnt_n++) {";
		echo " if (document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display == 'table-row') ";
		echo " { document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display='none'; } else {";
		echo "  document.getElementById('inventory_preord_' + tmpcnt + '_' + tmpcnt_n).style.display='table-row'; } ";
		echo "}";

		echo "}";
		echo "}";

		echo "</script>";
	?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<style type="text/css">
			.black_overlay {
				display: none;
				position: absolute;
				top: 0%;
				left: 0%;
				width: 100%;
				height: 100%;
				background-color: gray;
				z-index: 1001;
				-moz-opacity: 0.8;
				opacity: .80;
				filter: alpha(opacity=80);
			}

			.white_content {
				display: none;
				position: absolute;
				top: 5%;
				left: 10%;
				width: 60%;
				height: 90%;
				padding: 16px;
				border: 1px solid gray;
				background-color: white;
				z-index: 1002;
				overflow: auto;
			}

			.popup_qty {
				text-decoration: underline;
				cursor: pointer;
			}
		</style>
		<!--New Inventory Search option Jquery-->
		<script>
			function add_product_fun() {
				var cnt = document.getElementById("prod_cnt").value;
				var chkcondition = document.getElementById("filter_andorcondition" + cnt).value;
				var filtercol = document.getElementById("filter_column" + cnt).value;
				if (filtercol != "-" && chkcondition == "") {
					alert("Please select Condition");
					return false;
				}
				cnt = Number(cnt) + 1;

				var sstr = "";
				sstr = "<table style='font-size:8pt;' id='inv_child_div" + cnt +
					"'><tr><td>Select table column:</td><td><select style='font-size:8pt;' name='filter_column[]' id='filter_column" +
					cnt + "' onChange='showfilter_option(" + cnt +
					")'><option value=''>Select Option</option><option value='Box Type'>Box Type</option><option value='State'>Location State</option><option value='No. of Wall'>No. of Wall</option><option value='ucbwarehouse'>Warehouse</option><option value='Actual'>Actual</option><option value='After PO'>After PO</option><option value='Last Month Quantity'>Last Month Quantity</option><option value='Availability'>Availability</option><option value='Vendor'>Vendor</option><option value='Ship From'>Ship From</option><option value='Length'>Box Length</option><option value='Width'>Box Width</option><option value='Height'>Box Height</option><option value='Description'>Description</option><option value='SKU'>SKU</option><option value='Per Pallet'>Per Pallet</option><option value='Per Trailer'>Per Trailer</option><option value='Min FOB'>Min FOB</option><option value='Cost'>Cost</option></select></td><td><select style='font-size:8pt;' id='filter_compare_condition" +
					cnt +
					"' name='filter_compare_condition[]'><option value='='>=</option><option value='>'>></option><option value='<'><</option></select></td><td><div id='filter_sub_option" +
					cnt +
					"'><input style='font-size:8pt;' type='input' id='filter_inp' value='' /></div></td><td><select style='font-size:8pt;' id='filter_andorcondition" +
					cnt +
					"' name='filter_andorcondition[]'><option value=''>Select</option><option value='And'>And</option><option value='Or'>Or</option></select><input style='font-size:8pt;' type='button' name='btn_remove' value='X' onclick='remove_product_fun(" +
					cnt + ")'></td></tr></table></div></div>";

				var divctl = document.getElementById("inv_main_div");
				divctl.insertAdjacentHTML('beforeend', sstr);

				document.getElementById("prod_cnt").value = cnt;
			}
		</script>
		<script src="jQuery/jquery-2.1.3.min.js" type="text/javascript"></script>
		<script>
			function displayurgentbox(colid, sortflg) {
				document.getElementById("div_urgent_box").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				//alert(colid);
				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("div_urgent_box").innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "inventory_displayurgentbox.php?colid=" + colid + "&sortflg=" + sortflg, true);
				xmlhttp.send();
			}









			function displayflyer(boxid, flyernm) {
				document.getElementById("light").innerHTML =
					"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" +
					flyernm + "' width='700' height='800'>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_fly_div" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 'px';

			}

			function displayflyer_main(boxid, flyernm) {
				document.getElementById("light").innerHTML =
					"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr><embed src='boxpics/" +
					flyernm + "' width='700' height='800'>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_fly_div_main" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

			}

			function displayactualpallet(boxid) {
				document.getElementById("light").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("actual_pos" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_top = n_top - 200;
				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML =
							"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
							xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "report_inventory.php?inventory_id=" + boxid + "&action=run", true);
				xmlhttp.send();
			}

			function displayboxdata(boxid) {
				document.getElementById("light").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_div" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;

				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML =
							"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
							xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
				xmlhttp.send();
			}

			function displayboxdata_main(boxid) {
				document.getElementById("light").innerHTML =
					"<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
				document.getElementById('light').style.display = 'block';
				document.getElementById('fade').style.display = 'block';

				var selectobject = document.getElementById("box_div_main" + boxid);
				var n_left = f_getPosition(selectobject, 'Left');
				var n_top = f_getPosition(selectobject, 'Top');
				n_left = n_left - 350;
				n_top = n_top - 200;

				document.getElementById('light').style.left = n_left + 'px';
				document.getElementById('light').style.top = n_top + 20 + 'px';

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("light").innerHTML =
							"<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
							xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "manage_box_b2bloop.php?id=" + boxid + "&proc=View&", true);
				xmlhttp.send();
			}

			function display_orders_data(tmpcnt, box_id, wid) {
				if (document.getElementById('inventory_preord_top_' + tmpcnt).style.display == 'table-row') {
					document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'none';
				} else {
					document.getElementById('inventory_preord_top_' + tmpcnt).style.display = 'table-row';
				}

				document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML =
					"<br><br>Loading .....<img src='images/wait_animated.gif' />";

				if (window.XMLHttpRequest) {
					xmlhttp = new XMLHttpRequest();
				} else {
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("inventory_preord_middle_div_" + tmpcnt).innerHTML = xmlhttp.responseText;
					}
				}

				xmlhttp.open("GET", "gaylordstatus_childtable.php?box_id=" + box_id + "&wid=" + wid + "&tmpcnt=" + tmpcnt,
					true);
				xmlhttp.send();
			}
		</script>

		<style>
			.style12_new {
				font-size: x-small;
				font-family: Arial, Helvetica, sans-serif;
			}

			.style12 {
				font-size: x-small;
				font-family: Arial, Helvetica, sans-serif;
			}
		</style>
		<?php

		/////////////////////////////////////////// NEW INVENTORY SALES ORDER VALUES
		?>
		<!--------------------------NEW INVENTORY ---------------------------------------------->
		<script language="JavaScript" SRC="inc/CalendarPopup.js"></script>
		<script language="JavaScript" SRC="inc/general.js"></script>
		<script language="JavaScript">
			document.write(getCalendarStyles());
		</script>
		<script language="JavaScript">
			var cal1xx = new CalendarPopup("listdiv");
			cal1xx.showNavigationDropdowns();
		</script>

		<div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
		</div>

		<a href='dashboardnew.php?show=inventory'>Go Back to Old Inventory Version</a><br />
		<a href='dashboardnew.php?show=inventory_filter'>Inventory For Sales Rep</a><br />
		<a href='javascript:void();' id='show_map1' onclick="displaymap()">Show Map with Boxes</a><br />
		<a target="_blank" href='report_inbound_inventory_summary.php?warehouse_id=0'>Inbound Inventory Summary</a><br />
		<a target="_blank" href='report_inventory_types.php'>Inventory Report</a><br /><br />

		<table cellSpacing="1" cellPadding="1" border="0" width="1200">
			<tr align="middle">
				<td colspan="12" class="style24" style="height: 16px"><strong>INVENTORY NOTES</strong> <a href="updateinventorynotes.php">Edit</a></td>
			</tr>
			<tr vAlign="left">
				<td colspan=12>
					<?php
					$sql = "SELECT * FROM loop_inventory_notes ORDER BY dt DESC LIMIT 0,1";
					db();
					$res = db_query($sql);
					$row = array_shift($res);
					echo $row["notes"];
					?>
					<br />
				</td>
			</tr>

			<?php

			$no_of_urgent_load = 0;
			$no_of_urgent_load_str = "";
			$no_of_urgent_load_val = 0;
			?>
			<tr>
				<td>
					<table width="1400">
						<tr align="middle">
							<div id="light" class="white_content"></div>
							<div id="fade" class="black_overlay"></div>
							<td colspan="16" class="style24" style="height: 18px"><strong>Urgent Boxes</strong></td>
						</tr>
						<tr>
							<td colspan="16">
								<div id="div_urgent_box" name="div_urgent_box">
									<table cellSpacing="1" cellPadding="1" border="0" width="1200">
										<tr vAlign="left">

											<td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();" onclick="displayurgentbox(2,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(2,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

											<td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayurgentbox(3,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(3,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

											<td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();" onclick="displayurgentbox(4,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(4,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

											<td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();" onclick="displayurgentbox(16,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(16,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

											<td bgColor="#e4e4e4" class="style12">
												<font size=1><b>Account Owner&nbsp;<a href="javascript:void();" onclick="displayurgentbox(17,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(17,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
											</td>

											<td bgColor="#e4e4e4" class="style12">
												<font size=1><b>Supplier&nbsp;<a href="javascript:void();" onclick="displayurgentbox(5,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(5,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
											</td>

											<td bgColor="#e4e4e4" class="style12">
												<font size=1><b>Ship From&nbsp;<a href="javascript:void();" onclick="displayurgentbox(15,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(15,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
											</td>

											<td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();" onclick="displayurgentbox(6,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(6,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
											</td>

											<td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();" onclick="displayurgentbox(7,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(7,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
											</td>

											<td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();" onclick="displayurgentbox(9,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(9,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

											<td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();" onclick="displayurgentbox(10,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(10,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

											<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();" onclick="displayurgentbox(11,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(11,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

											<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();" onclick="displayurgentbox(12,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(12,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

											<td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();" onclick="displayurgentbox(13,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(13,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

											<td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();" onclick="displayurgentbox(14,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayurgentbox(14,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>
										</tr>
										<br>
										<!--Urgent Boxes table here-->
										<?php
										$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE  inventory.Active LIKE 'A' AND  box_urgent=1 ORDER BY inventory.availability DESC";
										//AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5
										//echo $sql . "<br>";
										db_b2b();
										$dt_view_res = db_query($sql);
										$x = 1;
										$no_of_full_load = 0;
										$count_arry = 0;
										$no_of_full_load_str_ucb_inv_av = "";
										$no_of_full_load_str_ucb_inv_av_str = "";
										$no_of_red_on_page_str = "";
										$no_of_inv_item_note_date = "";
										$no_of_inv_item_note_date_str = "";
										$no_of_full_load_str = "";
										$no_of_full_load_str_ucb_inv = "";
										$no_of_full_load_str_ucb_inv_str = "";
										$no_of_red_on_page = "";
										$tot_value_full_load = 0;
										while ($inv = array_shift($dt_view_res)) {

											$vendor_name = "";

											//account owner
											if ($inv["vendor_b2b_rescue"] > 0) {

												$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
												$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
												db();
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											} else {
												$vendor_b2b_rescue = $inv["V"];
												$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
												db_b2b();
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = $fetch["Name"];

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											}

											$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
											db();
											$loop_res = db_query($loopsql);

											$loop = array_shift($loop_res);

											if ($x == 0) {
												$x = 1;
												$bg = "#e4e4e4";
											} else {
												$x = 0;
												$bg = "#f4f4f4";
											}
											$tipStr = "";

											if ($inv["shape_rect"] == "1")
												$tipStr = $tipStr . " Rec ";
											if ($inv["shape_oct"] == "1")
												$tipStr = $tipStr . " Oct ";
											if ($inv["wall_2"] == "1")
												$tipStr = $tipStr . " 2W ";
											if ($inv["wall_3"] == "1")
												$tipStr = $tipStr . " 3W ";
											if ($inv["wall_4"] == "1")
												$tipStr = $tipStr . " 4W ";
											if ($inv["wall_5"] == "1")
												$tipStr = $tipStr . " 5W ";
											//
											if ($inv["top_nolid"] == "1")
												$tipStr = $tipStr . " No Top,";
											if ($inv["top_partial"] == "1")
												$tipStr = $tipStr . " Flange Top, ";
											if ($inv["top_full"] == "1")
												$tipStr = $tipStr . " FFT, ";
											if ($inv["top_hinged"] == "1")
												$tipStr = $tipStr . " Hinge Top, ";
											if ($inv["top_remove"] == "1")
												$tipStr = $tipStr . " Lid Top, ";
											if ($inv["bottom_no"] == "1")
												$tipStr = $tipStr . " No Bottom";
											if ($inv["bottom_partial"] == "1")
												$tipStr = $tipStr . " PB w/o SS";
											if ($inv["bottom_partialsheet"] == "1")
												$tipStr = $tipStr . " PB w/ SS";
											if ($inv["bottom_fullflap"] == "1")
												$tipStr = $tipStr . " FFB";
											if ($inv["bottom_interlocking"] == "1")
												$tipStr = $tipStr . " FB";
											if ($inv["bottom_tray"] == "1")
												$tipStr = $tipStr . " Tray Bottom";
											if ($inv["vents_no"] == "1")
												$tipStr = $tipStr . "";
											if ($inv["vents_yes"] == "1")
												$tipStr = $tipStr . ", Vents";
										?>

											<?php
											$b2b_status = "";
											db();
											$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
											while ($so_item_row1 = array_shift($dt_res_so_item1)) {
												$b2b_status = $so_item_row1["box_status"];
											}

											$bpallet_qty = 0;
											$boxes_per_trailer = 0;
											$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
											db();
											$dt_view = db_query($qry);
											while ($sku_val = array_shift($dt_view)) {
												$sku = $sku_val['sku'];
												$bpallet_qty = $sku_val['bpallet_qty'];
												$boxes_per_trailer = $sku_val['boxes_per_trailer'];
											}

											$b2b_ulineDollar = round($inv["ulineDollar"]);
											$b2b_ulineCents = $inv["ulineCents"];
											$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
											$b2b_fob = number_format($b2b_fob, 2);

											$b2b_costDollar = round($inv["costDollar"]);
											$b2b_costCents = $inv["costCents"];
											$b2b_cost = $b2b_costDollar + $b2b_costCents;
											$b2b_cost = number_format($b2b_cost, 2);

											$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
											$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
											$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
											$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
											$sales_order_qty_new = 0;
											db();
											$dt_res_so_item1 = db_query($dt_so_item1);
											while ($so_item_row1 = array_shift($dt_res_so_item1)) {
												if ($so_item_row1["sumqty"] > 0) {
													$sales_order_qty_new = $so_item_row1["sumqty"];
												}
											}
											?>
											<tr vAlign="center">
												<?php if ($sales_order_qty_new > 0) { ?>
													<td bgColor="<?php echo $bg; ?>" class="style12">
														<font color='blue' size=1>
															<div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
																<u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
															</div>
														</font>
													</td>
												<?php } else { ?>
													<td bgColor="<?php echo $bg; ?>" class="style12">
														<font size=1>
															<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
														</font>
													</td>
												<?php } ?>

												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1>
														<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?>
													</font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12">
													<?php if ($inv["availability"] == "3") echo "<b>"; ?>
													<?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
													<?php if ($inv["availability"] == "2") echo "Available Now"; ?>
													<?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
													<?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
													<?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
													<?php if ($inv["availability"] == "-1") echo "Presell"; ?>
													<?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
													<?php if ($inv["availability"] == "-3") echo "Potential"; ?>
													<?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<font size=1><?php echo isset($ownername); ?></font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<font size=1>
														<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
													</font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<font size=1>
														<?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
													</font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1>
														<?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<font size=1>
														<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a target="_blank" href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View' id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a>
													</font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo $bpallet_qty; ?></font>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo "$" . $b2b_fob; ?></font>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo "$" . $b2b_cost; ?></font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>' onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
												<?php } ?></td>
											</tr>

											<?php
											$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $inv['actual_inventory'] . "</font></td>";
											if ($sales_order_qty_new > 0) {
												$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
												if ($inv['availability'] == '3') $inv_row .= "<b>";
												$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
											} else {
												$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
												if ($inv['availability'] == '3') $inv_row .= "<b>";
												$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
											}

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' >";
											if ($inv['availability'] == '3') $inv_row .= '<b>';
											if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
											if ($inv['availability'] == '2') $inv_row .= 'Available Now';
											if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
											if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
											if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
											if ($inv['availability'] == '-1') $inv_row .= 'Presell';
											if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
											if ($inv['availability'] == '-3') $inv_row .= 'Potential';
											if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
											$inv_row .= "</td>  ";
											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $vendor_name . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob  . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
											$inv_row .= "<td bgColor='$bg' class='style12left' >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											if ($loop['id'] < 0) {
												$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
											} else {
												$inv_row .= $inv['N'] . "</a>";
											}
											$inv_row .= "</td>";
											$inv_row .= "</tr>";
											$no_of_urgent_load = $no_of_urgent_load + 1;
											$no_of_urgent_load_str .= $inv_row;

											$no_of_urgent_load_val = $no_of_urgent_load_val + floor($inv["actual_inventory"] / $boxes_per_trailer);

											//&& ($boxes_per_trailer >= $inv["actual_inventory"])
											if ($inv["actual_inventory"] > 0) {
												if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
													$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
													$no_of_full_load_str .= $inv_row;
												}

												$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * floatval($b2b_fob));
											}

											if ($inv["actual_inventory"] > 0) {
												$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
												$no_of_full_load_str_ucb_inv_str .= $inv_row;
											}

											if ($inv["after_actual_inventory"] > 0) {
												$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
												$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
											}

											if ($inv["actual_inventory"] < 0) {
												$no_of_red_on_page = $no_of_red_on_page + 1;
												$no_of_red_on_page_str .= $inv_row;
											}

											$notes_date = new DateTime($inv["DT"]);
											$curr_date = new DateTime();

											$notes_date_diff = $curr_date->diff($notes_date)->days;
											if ($notes_date_diff > 7) {
												$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
												$no_of_inv_item_note_date_str .= $inv_row;
											}
											?>
											<tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td colspan="14" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
													<div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
												</td>
											</tr>

										<?php
											$count_arry = $count_arry + 1;
										}
										?>

									</table>
								</div>
								<!--End Urgent boxes table-->
								<br>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?php $prod_cnt = 1; ?>
			<tr align="middle">
				<td colspan="12" class="style24" style="height: 16px">
					<form action="dashboardnew.php" name="frmnewinventory" id="frmnewinventory">
						<input type="hidden" value="inventory_cron" name="show" id="show" />
						<table>
							<tr>
								<td>
									<?php if ($_REQUEST["filter_btn"] == "Apply Filter") {
										$filter_type = $_REQUEST["filter_type"];
										$filter_availability = $_REQUEST["filter_availability"];
										$min_height = $_REQUEST["min_height"];
										$max_height = $_REQUEST["max_height"];

										$min_thickness = $_REQUEST["min_thickness"];

										$min_fob = $_REQUEST["min_fob"];
										$chkterritory = $_REQUEST["chkterritory"];
									?>
										<table id="inv_child_div" width="300px" style="font-size:8pt;">
											<tr>
												<td>Type:</td>
												<td width="5px;">&nbsp;</td>
												<td colspan="3">
													<select style="font-size:8pt;" name="filter_type" id="filter_type">
														<option value="All">All types</option>
														<option value="Gaylords" <?php if ($filter_type == "Gaylords") {
																						echo " selected ";
																					} ?>>Gaylords</option>
														<option value="Shipping Boxes" <?php if ($filter_type == "Shipping Boxes") {
																							echo " selected ";
																						} ?>>Shipping Boxes</option>
														<option value="Supersacks" <?php if ($filter_type == "Supersacks") {
																						echo " selected ";
																					} ?>>Supersacks</option>
														<option value="DrumsBarrelsIBCs" <?php if ($filter_type == "DrumsBarrelsIBCs") {
																								echo " selected ";
																							} ?>>Drums/Barrels/IBCs</option>
														<option value="Pallets" <?php if ($filter_type == "Pallets") {
																					echo " selected ";
																				} ?>>Pallets</option>
														<option value="Recycling" <?php if ($filter_type == "Recycling") {
																						echo " selected ";
																					} ?>>Recycling</option>
													</select>
												</td>
											</tr>
											<tr>
												<td>Availability:</td>
												<td width="5px;">&nbsp;</td>
												<td colspan="3">
													<select style="font-size:8pt;" name="filter_availability" id="filter_availability">
														<option value="All">All</option>
														<option value="truckloadonly" <?php if ($_REQUEST["filter_availability"] == "truckloadonly") {
																							echo " selected ";
																						} ?>>>= Truckload Only</option>
														<option value="anyavailableboxes" <?php if ($_REQUEST["filter_availability"] == "anyavailableboxes") {
																								echo " selected ";
																							} ?>>Any Available Boxes Only</option>
													</select>
												</td>
											</tr>
											<tr>
												<td>Height:</td>
												<td width="5px;">&nbsp;</td>
												<td>
													<input type="input" id="min_height" name="min_height" value="<?php echo $min_height; ?>" style="font-size:8pt;" />
												</td>
												<td>
													To
												</td>
												<td>
													<input type="input" id="max_height" name="max_height" value="<?php echo $max_height; ?>" style="font-size:8pt;" />
												</td>
											</tr>
											<tr>
												<td>Min Thickness:</td>
												<td width="5px;">&nbsp;</td>
												<td colspan="3">
													<select style="font-size:8pt;" name="min_thickness" id="min_thickness">
														<option value="Any" <?php if ($min_thickness == "Any") {
																				echo " selected ";
																			} ?>>Any</option>
														<option value="2ply" <?php if ($min_thickness == "2ply") {
																					echo " selected ";
																				} ?>>2ply or more</option>
														<option value="3ply" <?php if ($min_thickness == "3ply") {
																					echo " selected ";
																				} ?>>3ply or more</option>
														<option value="4ply" <?php if ($min_thickness == "4ply") {
																					echo " selected ";
																				} ?>>4ply or more</option>
														<option value="5ply" <?php if ($min_thickness == "5ply") {
																					echo " selected ";
																				} ?>>5ply or more</option>
														<option value="6ply" <?php if ($min_thickness == "6ply") {
																					echo " selected ";
																				} ?>>6ply or more</option>
														<option value="7ply" <?php if ($min_thickness == "7ply") {
																					echo " selected ";
																				} ?>>7ply or more</option>
														<option value="8ply" <?php if ($min_thickness == "8ply") {
																					echo " selected ";
																				} ?>>8ply or more</option>
														<option value="9ply" <?php if ($min_thickness == "9ply") {
																					echo " selected ";
																				} ?>>9ply or more</option>
														<option value="10ply" <?php if ($min_thickness == "10ply") {
																					echo " selected ";
																				} ?>>10ply or more</option>
													</select>
												</td>
											</tr>

											<tr>
												<td>Max FOB:</td>
												<td width="5px;">$</td>
												<td colspan="3">
													<input type="input" id="min_fob" name="min_fob" value="<?php echo $min_fob; ?>" style="font-size:8pt;" />
												</td>
											</tr>
										</table>

										<table width="" style="font-size:8pt;">
											<!-- Mexico<input type="checkbox" id="_REQUEST["chkterritory"]" name="_REQUEST["chkterritory"]" value="mexico_reg" <?php if ($_REQUEST["chkterritory"] == "mexico_reg") {
																																									echo " checked ";
																																								} ?>/> -->
											<tr>
												<td>Territory:</td>
												<td width="5px;">&nbsp;</td>
												<td colspan="3">
													<input type="checkbox" id="chkterritory_canada_east" name="chkterritory_canada_east" value="canada_east" <?php if ($_REQUEST["chkterritory_canada_east"] == "canada_east") {
																																									echo " checked ";
																																								} ?> />Canada East
													<input type="checkbox" id="chkterritoryeast_reg" name="chkterritoryeast_reg" value="east_reg" <?php if ($_REQUEST["chkterritoryeast_reg"] == "east_reg") {
																																						echo " checked ";
																																					} ?> />East
													<input type="checkbox" id="chkterritorysouth_reg" name="chkterritorysouth_reg" value="south_reg" <?php if ($_REQUEST["chkterritorysouth_reg"] == "south_reg") {
																																							echo " checked ";
																																						} ?> />South
													<input type="checkbox" id="chkterritorymidwest_reg" name="chkterritorymidwest_reg" value="midwest_reg" <?php if ($_REQUEST["chkterritorymidwest_reg"] == "midwest_reg") {
																																								echo " checked ";
																																							} ?> />Midwest
													<input type="checkbox" id="chkterritorynorthcenteral_reg" name="chkterritorynorthcenteral_reg" value="northcenteral_reg" <?php if ($_REQUEST["chkterritorynorthcenteral_reg"] == "northcenteral_reg") {
																																													echo " checked ";
																																												} ?> />North Central
													<input type="checkbox" id="chkterritorysouthcenteral_reg" name="chkterritorysouthcenteral_reg" value="southcenteral_reg" <?php if ($_REQUEST["chkterritorysouthcenteral_reg"] == "southcenteral_reg") {
																																													echo " checked ";
																																												} ?> />South Central

													<input type="checkbox" id="chkterritory_canada_west" name="chkterritory_canada_west" value="canada_west" <?php if ($_REQUEST["chkterritory_canada_west"] == "canada_west") {
																																									echo " checked ";
																																								} ?> />Canada West

													<input type="checkbox" id="chkterritorypacific_reg" name="chkterritorypacific_reg" value="pacific_reg" <?php if ($_REQUEST["chkterritorypacific_reg"] == "pacific_reg") {
																																								echo " checked ";
																																							} ?> />Pacific Northwest
													<input type="checkbox" id="chkterritorywestern_reg" name="chkterritorywestern_reg" value="western_reg" <?php if ($_REQUEST["chkterritorywestern_reg"] == "western_reg") {
																																								echo " checked ";
																																							} ?> />Western

													<input type="checkbox" id="chkterritorymexico_reg" name="chkterritorymexico_reg" value="mexico_reg" <?php if ($_REQUEST["chkterritorymexico_reg"] == "mexico_reg") {
																																							echo " checked ";
																																						} ?> />Mexico

													<!-- <input type="checkbox" id="chkterritoryother_reg" name="chkterritoryother_reg" value="other_reg" <?php if ($_REQUEST["chkterritoryother_reg"] == "other_reg") {
																																								echo " checked ";
																																							} ?>/>Other -->

													<input type="hidden" name="prod_cnt" id="prod_cnt" value="<?php echo $prod_cnt; ?>">
													<input type="submit" id="filter_btn" name="filter_btn" style="font-size:8pt;" value="Apply Filter" />
												</td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td width="5px;">&nbsp;</td>
												<td colspan="3">
													<a target="_blank" href="gaylordstatus.php">Edit Non-Inventory</a>
												</td>
											</tr>
										</table>

										<?php
										$prod_cnt = 0; ?>
										<div id="inv_main_div">
										</div>
									<?php } else { ?>
										<div id="inv_main_div">
											<table id="inv_child_div" width="300px" style="font-size:8pt;">
												<tr>
													<td>Type:</td>
													<td width="5px;">&nbsp;</td>
													<td colspan="3">
														<select style="font-size:8pt;" name="filter_type" id="filter_type">
															<option value="All">All types</option>
															<option value="Gaylords">Gaylords</option>
															<option value="Shipping Boxes">Shipping Boxes</option>
															<option value="Supersacks">Supersacks</option>
															<option value="DrumsBarrelsIBCs">Drums/Barrels/IBCs</option>
															<option value="Pallets">Pallets</option>
															<option value="Recycling">Recycling</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Availability:</td>
													<td width="5px;">&nbsp;</td>
													<td colspan="3">
														<select style="font-size:8pt;" name="filter_availability" id="filter_availability">
															<option value="All">All</option>
															<option value="truckloadonly">>= Truckload Only</option>
															<option value="anyavailableboxes">Any Available Boxes Only</option>
														</select>
													</td>
												</tr>
												<tr>
													<td>Height:</td>
													<td width="5px;">&nbsp;</td>
													<td>
														<input type="input" id="min_height" name="min_height" value="0" style="font-size:8pt;" />
													</td>
													<td>
														To
													</td>
													<td>
														<input type="input" id="max_height" name="max_height" value="100" style="font-size:8pt;" />
													</td>
												</tr>
												<tr>
													<td>Min Thickness:</td>
													<td width="5px;">&nbsp;</td>
													<td colspan="3">
														<select style="font-size:8pt;" name="min_thickness" id="min_thickness">
															<option value="Any">Any</option>
															<option value="2ply">2ply or more</option>
															<option value="3ply">3ply or more</option>
															<option value="4ply">4ply or more</option>
															<option value="5ply">5ply or more</option>
															<option value="6ply">6ply or more</option>
															<option value="7ply">7ply or more</option>
															<option value="8ply">8ply or more</option>
															<option value="9ply">9ply or more</option>
															<option value="10ply">10ply or more</option>
														</select>
													</td>
												</tr>

												<tr>
													<td>Max FOB:</td>
													<td width="5px;">$</td>
													<td colspan="3">
														<input type="input" id="min_fob" name="min_fob" value="100.00" style="font-size:8pt;" />
													</td>
												</tr>
											</table>

											<table width="" style="font-size:8pt;">
												<tr>
													<td>Territory:</td>
													<td width="5px;">&nbsp;</td>
													<td colspan="3">
														<input type="checkbox" id="chkterritory_canada_east" name="chkterritory_canada_east" value="canada_east" checked />Canada
														East
														<input type="checkbox" id="chkterritoryeast_reg" name="chkterritoryeast_reg" value="east_reg" checked />East
														<input type="checkbox" id="chkterritorysouth_reg" name="chkterritorysouth_reg" value="south_reg" checked />South
														<input type="checkbox" id="chkterritorymidwest_reg" name="chkterritorymidwest_reg" value="midwest_reg" checked />Midwest
														<input type="checkbox" id="chkterritorynorthcenteral_reg" name="chkterritorynorthcenteral_reg" value="northcenteral_reg" checked />North Central
														<input type="checkbox" id="chkterritorysouthcenteral_reg" name="chkterritorysouthcenteral_reg" value="southcenteral_reg" checked />South Central

														<input type="checkbox" id="chkterritory_canada_west" name="chkterritory_canada_west" value="canada_west" checked />Canada
														West

														<input type="checkbox" id="chkterritorypacific_reg" name="chkterritorypacific_reg" value="pacific_reg" checked />Pacific
														Northwest
														<input type="checkbox" id="chkterritorywestern_reg" name="chkterritorywestern_reg" value="western_reg" checked />Western

														<input type="checkbox" id="chkterritorymexico_reg" name="chkterritorymexico_reg" value="mexico_reg" checked />Mexico

														<!-- <input type="checkbox" id="chkterritoryother_reg" name="chkterritoryother_reg" value="other_reg" checked />Other -->

														<input type="hidden" name="prod_cnt" id="prod_cnt" value="<?php echo $prod_cnt; ?>">
														<input type="submit" id="filter_btn" name="filter_btn" style="font-size:8pt;" value="Apply Filter" />

													</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td width="5px;">&nbsp;</td>
													<td colspan="3">
														<a target="_blank" href="gaylordstatus.php">Edit Non-Inventory</a>
													</td>
												</tr>
											</table>
							</tr>
							</div>
						<?php } ?>
				</td>
			</tr>
		</table>
		</form>
		</td>
		</tr>
		<?php

		//To chk the condition
		if ($_REQUEST["filter_btn"] == "Apply Filter") {
			$BoxType_where = "";
			$State_where = "";
			$no_of_wall_where = "";
			$actual_where = "";
			$after_po_where = "";
			$last_month_qty_where = "";
			$availability_where = "";
			$vendor_where = "";
			$box_length_where = "";
			$box_width_where = "";
			$box_height_where = "";
			$description_where = "";
			$sku_where = "";
			$per_pallet_where = "";
			$per_trailer_where = "";
			$min_FOB_where = "";
			$cost_where = "";

			$BoxType_where_ucbq = "";
			$State_where_ucbq = "";
			$no_of_wall_where_ucbq = "";
			$actual_where_ucbq = "";
			$after_po_where_ucbq = "";
			$last_month_qty_where_ucbq = "";
			$availability_where_ucbq = "";
			$vendor_where_ucbq = "";
			$box_length_where_ucbq = "";
			$box_width_where_ucbq = "";
			$box_height_where_ucbq = "";
			$description_where_ucbq = "";
			$sku_where_ucbq = "";
			$per_pallet_where_ucbq = "";
			$per_trailer_where_ucbq = "";
			$min_FOB_where_ucbq = "";
			$cost_where_ucbq = "";

			$main_new_where_condition = "";

			$filter_type = $_REQUEST["filter_type"];
			$filter_availability = $_REQUEST["filter_availability"];
			$min_height = $_REQUEST["min_height"];
			$max_height = $_REQUEST["max_height"];

			$min_thickness = $_REQUEST["min_thickness"];

			$min_fob = $_REQUEST["min_fob"];
			$chkterritory = $_REQUEST["chkterritory"];

			if ($filter_type == "All") {
				$BoxType_where = "";
				$BoxType_where_ucbq = "";
			}
			if ($filter_type == "Gaylords") {
				$BoxType_where = " and box_type in ('Gaylord', 'GaylordUCB')";
				$BoxType_where_ucbq = " and type_ofbox in ('Gaylord', 'GaylordUCB')";
			}
			if ($filter_type == "Shipping Boxes") {
				$BoxType_where = " and box_type in ('Medium', 'Large', 'Xlarge', 'Box', 'Boxnonucb', 'Presold' )";
				$BoxType_where_ucbq = " and type_ofbox in ('Medium', 'Large', 'Xlarge', 'Box', 'Boxnonucb', 'Presold')";
			}
			if ($filter_type == "Supersacks") {
				$BoxType_where = " and box_type in ('SupersackUCB', 'SupersacknonUCB')";
				$BoxType_where_ucbq = " and type_ofbox in ('SupersackUCB', 'SupersacknonUCB')";
			}
			if ($filter_type == "DrumsBarrelsIBCs") {
				$BoxType_where = " and box_type in ('DrumBarrelUCB', 'DrumBarrelnonUCB')";
				$BoxType_where_ucbq = " and type_ofbox in ('DrumBarrelUCB', 'DrumBarrelnonUCB')";
			}
			if ($filter_type == "Pallets") {
				$BoxType_where = " and box_type in ('PalletsUCB', 'PalletsnonUCB')";
				$BoxType_where_ucbq = " and type_ofbox in ('PalletsUCB', 'PalletsnonUCB')";
			}
			if ($filter_type == "Recycling") {
				$BoxType_where = " and box_type in ('Recycling')";
				$BoxType_where_ucbq = " and type_ofbox in ('Recycling')";
			}

			if ($min_height > 0) {
				$box_height_where .= " and inventory.depthInch >= " . $min_height;
				$box_height_where_ucbq .= " and inventory.depthInch >= " . $min_height;
			}

			if ($max_height > 0) {
				$box_height_where .= " and inventory.depthInch <= " . $max_height;
				$box_height_where_ucbq .= " and inventory.depthInch <= " . $max_height;
			}

			if ($min_thickness == "Any") {
				$box_width_where .= "";
				$box_width_where_ucbq .= "";
			}
			if ($min_thickness == "2ply") {
				$box_width_where .= " and bwall >= 2";
				$box_width_where_ucbq .= " and inventory.bwall >= 2";
			}
			if ($min_thickness == "3ply") {
				$box_width_where .= " and bwall >= 3";
				$box_width_where_ucbq .= " and inventory.bwall >= 3";
			}
			if ($min_thickness == "4ply") {
				$box_width_where .= " and bwall >= 4";
				$box_width_where_ucbq .= " and inventory.bwall >= 4";
			}
			if ($min_thickness == "5ply") {
				$box_width_where .= " and bwall >= 5";
				$box_width_where_ucbq .= " and inventory.bwall >= 5";
			}
			if ($min_thickness == "6ply") {
				$box_width_where .= " and bwall >= 6";
				$box_width_where_ucbq .= " and inventory.bwall >= 6";
			}
			if ($min_thickness == "7ply") {
				$box_width_where .= " and bwall >= 7";
				$box_width_where_ucbq .= " and inventory.bwall >= 7";
			}
			if ($min_thickness == "8ply") {
				$box_width_where .= " and bwall >= 8";
				$box_width_where_ucbq .= " and inventory.bwall >= 8";
			}
			if ($min_thickness == "9ply") {
				$box_width_where .= " and bwall >= 9";
				$box_width_where_ucbq .= " and inventory.bwall >= 9";
			}
			if ($min_thickness == "10ply") {
				$box_width_where .= " and bwall >= 10";
				$box_width_where_ucbq .= " and inventory.bwall >= 10";
			}

			if ($min_fob > 0) {
				$min_FOB_where .= " and (ulineDollar + ulineCents) <= " . $min_fob;
				$min_FOB_where_ucbq .= " and min_fob <= " . $min_fob;
			}

			//$chkterritory
			$State_where1 = "";
			$State_where_ucbq1 = "";
			$State_where2 = "";
			$State_where_ucbq2 = "";
			$State_where3 = "";
			$State_where_ucbq3 = "";
			$State_where4 = "";
			$State_where_ucbq4 = "";
			$State_where5 = "";
			$State_where_ucbq5 = "";
			$State_where6 = "";
			$State_where_ucbq6 = "";
			$State_where7 = "";
			$State_where_ucbq7 = "";
			$State_where8 = "";
			$State_where_ucbq8 = "";
			$State_where9 = "";
			$State_where_ucbq9 = "";
			$State_where10 = "";
			$State_where_ucbq10 = "";
			if ($_REQUEST["chkterritoryeast_reg"] == "east_reg") {
				$State_where1 .= " location_country = 'USA' and location_state in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV','NJ','DC','DE')";
				$State_where_ucbq1 .= " location_country = 'USA' and inventory.location_state in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV','NJ','DC','DE') ";
			}
			if ($_REQUEST["chkterritorysouth_reg"] == "south_reg") {
				$State_where2 .= " location_country = 'USA' and location_state in ('NC','SC','GA','AL','MS','TN','FL')";
				$State_where_ucbq2 .= " location_country = 'USA' and inventory.location_state in ('NC','SC','GA','AL','MS','TN','FL') ";
			}
			if ($_REQUEST["chkterritorymidwest_reg"] == "midwest_reg") {
				$State_where3 .= " location_country = 'USA' and location_state in ('MI','OH','IN','KY')";
				$State_where_ucbq3 .= " location_country = 'USA' and inventory.location_state in ('MI','OH','IN','KY') ";
			}
			if ($_REQUEST["chkterritorynorthcenteral_reg"] == "northcenteral_reg") {
				$State_where4 .= " location_country = 'USA' and location_state in ('ND','SD','NE','MN','IA','IL','WI')";
				$State_where_ucbq4 .= " location_country = 'USA' and inventory.location_state in ('ND','SD','NE','MN','IA','IL','WI') ";
			}
			if ($_REQUEST["chkterritorysouthcenteral_reg"] == "southcenteral_reg") {
				$State_where5 .= " location_country = 'USA' and location_state in ('LA','AR','MO','TX','OK','KS','CO','NM')";
				$State_where_ucbq5 .= " location_country = 'USA' and inventory.location_state in ('LA','AR','MO','TX','OK','KS','CO','NM') ";
			}
			if ($_REQUEST["chkterritorypacific_reg"] == "pacific_reg") {
				$State_where6 .= " location_country = 'USA' and location_state in ('WA','OR','ID','MT','WY','AK')";
				$State_where_ucbq6 .= " location_country = 'USA' and  inventory.location_state in ('WA','OR','ID','MT','WY','AK') ";
			}
			if ($_REQUEST["chkterritorywestern_reg"] == "western_reg") {
				$State_where7 .= " location_country = 'USA' and location_state in ('CA','NV','UT','AZ','HI')";
				$State_where_ucbq7 .= " location_country = 'USA' and inventory.location_state in ('CA','NV','UT','AZ','HI') ";
			}

			if ($_REQUEST["chkterritory_canada_east"] == "canada_east") {
				$State_where8 .= " location_country = 'Canada' and location_state in ('NB', 'NF', 'NS','ON', 'PE', 'QC')";
				$State_where_ucbq8 .= " location_country = 'Canada' and inventory.location_state in ('NB', 'NF', 'NS','ON', 'PE', 'QC') ";
			}
			if ($_REQUEST["chkterritory_canada_west"] == "canada_west") {
				$State_where9 .= " location_country = 'Canada' and location_state in ('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT' )";
				$State_where_ucbq9 .= " location_country = 'Canada' and inventory.location_state in ('AB', 'BC', 'MB', 'NT', 'NU', 'SK', 'YT') ";
			}

			if ($_REQUEST["chkterritorymexico_reg"] == "mexico_reg") {
				$State_where10 .= " location_country = 'Mexico' and location_state in ('AG','BS','CH','CL','CM','CO','CS','DF','DG','GR','GT','HG','JA','ME','MI','MO','NA','NL','OA','PB','QE','QR','SI','SL','SO','TB','TL','TM','VE','ZA') ";
				$State_where_ucbq10 .= " location_country = 'Mexico' and inventory.location_state in ('AG','BS','CH','CL','CM','CO','CS','DF','DG','GR','GT','HG','JA','ME','MI','MO','NA','NL','OA','PB','QE','QR','SI','SL','SO','TB','TL','TM','VE','ZA') ";
			}
			if ($_REQUEST["chkterritoryother_reg"] == "other_reg") {
				//$State_where10 .= " location_state not in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV','NC','SC','GA','AL','MS','TN','MI','OH','IN','KY','ND','SD','NE','MN','IA','IL','WI','LA','AR','MO','TX','OK','KS','CO','NM','WA','OR','ID','MT','WY','AK','CA','NV','UT','AZ','HI','AB','BC','LB','MB','NB','NF','NS','NU','NW','ON','PE','QC','SK','YU') ";
				//$State_where_ucbq10 .= " inventory.location_state not in ('ME','NH','VT','MA','RI','CT','NY','PA','MD','VA','WV','NC','SC','GA','AL','MS','TN','MI','OH','IN','KY','ND','SD','NE','MN','IA','IL','WI','LA','AR','MO','TX','OK','KS','CO','NM','WA','OR','ID','MT','WY','AK','CA','NV','UT','AZ','HI','AB','BC','LB','MB','NB','NF','NS','NU','NW','ON','PE','QC','SK','YU') ";
			}

			if ($State_where1 != "") {
				$State_where .= $State_where1 . " or ";
			}
			if ($State_where2 != "") {
				$State_where .= $State_where2 . " or ";
			}
			if ($State_where3 != "") {
				$State_where .= $State_where3 . " or ";
			}
			if ($State_where4 != "") {
				$State_where .= $State_where4 . " or ";
			}
			if ($State_where5 != "") {
				$State_where .= $State_where5 . " or ";
			}
			if ($State_where6 != "") {
				$State_where .= $State_where6 . " or ";
			}
			if ($State_where7 != "") {
				$State_where .= $State_where7 . " or ";
			}
			if ($State_where8 != "") {
				$State_where .= $State_where8 . " or ";
			}
			if ($State_where9 != "") {
				$State_where .= $State_where9 . " or ";
			}
			if ($State_where10 != "") {
				$State_where .= $State_where10 . " or ";
			}

			if ($State_where != "") {
				$State_where = substr($State_where, 0, strlen($State_where) - 3);
			}

			$State_where_main = "";
			if (
				$State_where1 != "" || $State_where2 != ""  || $State_where3 != "" || $State_where4 != "" || $State_where5 != ""
				|| $State_where6 != "" || $State_where7 != "" || $State_where8 != "" || $State_where9 != "" || $State_where10 != ""
			) {
				$State_where_main = " and ( " . $State_where . ") ";
			}

			if ($State_where_ucbq1 != "") {
				$State_where_ucbq .= $State_where_ucbq1 . " or ";
			}
			if ($State_where_ucbq2 != "") {
				$State_where_ucbq .= $State_where_ucbq2 . " or ";
			}
			if ($State_where_ucbq3 != "") {
				$State_where_ucbq .= $State_where_ucbq3 . " or ";
			}
			if ($State_where_ucbq4 != "") {
				$State_where_ucbq .= $State_where_ucbq4 . " or ";
			}
			if ($State_where_ucbq5 != "") {
				$State_where_ucbq .= $State_where_ucbq5 . " or ";
			}
			if ($State_where_ucbq6 != "") {
				$State_where_ucbq .= $State_where_ucbq6 . " or ";
			}
			if ($State_where_ucbq7 != "") {
				$State_where_ucbq .= $State_where_ucbq7 . " or ";
			}
			if ($State_where_ucbq8 != "") {
				$State_where_ucbq .= $State_where_ucbq8 . " or ";
			}
			if ($State_where_ucbq9 != "") {
				$State_where_ucbq .= $State_where_ucbq9 . " or ";
			}
			if ($State_where_ucbq10 != "") {
				$State_where_ucbq .= $State_where_ucbq10 . " or ";
			}

			if ($State_where_ucbq != "") {
				$State_where_ucbq = substr($State_where_ucbq, 0, strlen($State_where_ucbq) - 3);
			}

			$State_where_ucbq_main = "";
			if (
				$State_where_ucbq1 != "" || $State_where_ucbq2 != ""  || $State_where_ucbq3 != "" || $State_where_ucbq4 != "" || $State_where_ucbq5 != ""
				|| $State_where_ucbq6 != "" || $State_where_ucbq7 != "" || $State_where_ucbq8 != "" || $State_where_ucbq9 != "" || $State_where_ucbq10 != ""
			) {
				$State_where_ucbq_main = " and ( " . $State_where_ucbq . ") ";
			}

			/*echo $BoxType_where . " " . $State_where . " " . $no_of_wall_where . " " . $actual_where . " " . $after_po_where . " " . $last_month_qty_where . " ";
			echo $availability_where . " " . $vendor_where . " " .  $box_length_where . " " .  $box_width_where . " ";
			echo $box_height_where . " " .  $description_where . " " .  $sku_where . " " .  $per_pallet_where . " " .  $per_trailer_where . " " .  $min_FOB_where . " " .  $cost_where . "<br>";   
			*/
		}
		?>
		<tr align="middle">
			<td colspan="12" style="height: 16px">&nbsp;</td>
		</tr>


		<?php
		$top_head_flg = "no";
		$top_head_flg_output = "no";
		$x = 0;
		$count_arry = 0;

		function removeandor(string $mainstr_data): string
		{
			if (trim(substr($mainstr_data, strlen($mainstr_data) - 5, 6)) == "And)") {
				$mainstr_data = substr($mainstr_data, 0, strlen($mainstr_data) - 5) . ") And ";
			}
			if (trim(substr($mainstr_data, strlen($mainstr_data), -5)) == "Or)") {
				$mainstr_data = substr($mainstr_data, 0, strlen($mainstr_data) - 4) . ") Or ";
			}

			return $mainstr_data;
		}

		//$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.gaylord=1 AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -3.5 ORDER BY inventory.availability DESC, vendors.name ASC";
		$main_qry_and = "";
		if (
			isset($BoxType_where) != "" || isset($State_where) != "" || isset($availability_where) != "" || isset($box_width_where) != "" ||
			isset($box_height_where) != "" || isset($min_FOB_where) != "" || isset($State_where) != ""
		) {
			//$main_qry_and = "and ";
		}

		//$main_new_where_condition = trim($main_qry_and . " " . $BoxType_where . " " . $State_where . " " . $no_of_wall_where . " " . $actual_where . " " . $after_po_where . " " . $last_month_qty_where . " " . $availability_where . " " . $vendor_where . " " . $box_length_where . " " . $box_width_where . " " . $box_height_where . " " . $description_where . " " . $sku_where . " " . $per_pallet_where . " " . $per_trailer_where . " " . $min_FOB_where . " " . $cost_where);
		$main_new_where_condition_sub = isset($BoxType_where) . " " . isset($availability_where) . " " . isset($box_width_where) . " " . isset($box_height_where) . " " . isset($min_FOB_where) . " " . isset($State_where_main);
		$main_new_where_condition = trim($main_qry_and . " " . $main_new_where_condition_sub);

		if (trim(substr($main_new_where_condition, strlen($main_new_where_condition) - 5, 6)) == "And)") {
			$main_new_where_condition = substr($main_new_where_condition, 0, strlen($main_new_where_condition) - 5) . ")";
		}
		if (trim(substr($main_new_where_condition, strlen($main_new_where_condition), -5)) == "Or)") {
			$main_new_where_condition = substr($main_new_where_condition, 0, strlen($main_new_where_condition) - 4) . ")";
		}

		//$main_new_where_condition_ucbq = trim($ucbwarehouse_where_ucbq . " " . $BoxType_where_ucbq . " " . $State_where_ucbq . " " . $no_of_wall_where_ucbq . " " . $actual_where_ucbq . " " . $after_po_where_ucbq . " " . $last_month_qty_where_ucbq . " " . $availability_where_ucbq . " " . $vendor_where_ucbq . " " . $box_length_where_ucbq . " " . $box_width_where_ucbq . " " . $box_height_where_ucbq . " " . $description_where_ucbq . " " . $sku_where_ucbq . " " . $per_pallet_where_ucbq . " " . $per_trailer_where_ucbq . " " . $min_FOB_where_ucbq . " " . $cost_where_ucbq);
		$main_new_where_ucbq_condition_sub = isset($BoxType_where_ucbq) . " " . isset($availability_where_ucbq) . " " . isset($box_width_where_ucbq) . " " . isset($box_height_where_ucbq) . " " . isset($min_FOB_where_ucbq) . " " . isset($State_where_ucbq_main);
		//$main_new_where_condition_ucbq = trim($main_qry_and . " " . $main_new_where_ucbq_condition_sub );
		$main_new_where_condition_ucbq = trim($main_new_where_ucbq_condition_sub);

		if ($main_new_where_condition_ucbq != "") {
			if (trim(substr($main_new_where_condition_ucbq, 0, 3)) == "and") {
				$main_new_where_condition_ucbq = " where " . substr($main_new_where_condition_ucbq, 3);
			} else {
				$main_new_where_condition_ucbq = " where " . $main_new_where_condition_ucbq;
			}
		}
		if (trim(substr($main_new_where_condition_ucbq, strlen($main_new_where_condition_ucbq) - 5, 6)) == "And") {
			$main_new_where_condition_ucbq = substr($main_new_where_condition_ucbq, 0, strlen($main_new_where_condition_ucbq) - 5) . ")";
		}
		if (trim(substr($main_new_where_condition_ucbq, strlen($main_new_where_condition_ucbq), -5)) == "Or") {
			$main_new_where_condition_ucbq = substr($main_new_where_condition_ucbq, 0, strlen($main_new_where_condition_ucbq) - 4)  . ")";
		}

		$no_of_full_load = 0;
		$tot_value_full_load = 0;
		$tot_load_available = 0;
		$tot_load_available_val = 0;
		$no_of_red_on_page = 0;
		$no_of_trans_no_delv_date = 0;
		$no_of_trans_plann_del_pass = 0;
		$no_of_inv_item_note_date = 0;

		$no_of_full_load_str = "";
		$tot_value_full_load_str = "";
		$tot_load_available_str = "";
		$tot_load_available_val_str = "";
		$no_of_red_on_page_str = "";
		$no_of_trans_no_delv_date_str = "";
		$no_of_trans_plann_del_pass_str = "";
		$no_of_inv_item_note_date_str = "";

		$no_of_full_load_str_ucb_inv = 0;
		$no_of_full_load_str_ucb_inv_str = "";
		$no_of_full_load_str_ucb_inv_av = "";
		$no_of_full_load_str_ucb_inv_av_str = "";

		//INNER JOIN vendors ON inventory.vendor = vendors.id
		$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.gaylord=1 AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
		//echo $sql . "<br>";
		db_b2b();
		$dt_view_res = db_query($sql);

		while ($inv = array_shift($dt_view_res)) {
			$vendor_name = "";
			//account owner
			if ($inv["vendor_b2b_rescue"] > 0) {

				$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
				$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
				db();
				$query = db_query($q1);
				while ($fetch = array_shift($query)) {
					$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

					$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
					db_b2b();
					$comres = db_query($comqry);
					while ($comrow = array_shift($comres)) {
						$ownername = $comrow["initials"];
					}
				}
			} else {
				$vendor_b2b_rescue = $inv["V"];
				$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
				db_b2b();
				$query = db_query($q1);
				while ($fetch = array_shift($query)) {
					$vendor_name = $fetch["Name"];

					$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
					db_b2b();
					$comres = db_query($comqry);
					while ($comrow = array_shift($comres)) {
						$ownername = $comrow["initials"];
					}
				}
			}
			//
			$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
			db();
			$loop_res = db_query($loopsql);

			$loop = array_shift($loop_res);

			if ($x == 0) {
				$x = 1;
				$bg = "#e4e4e4";
			} else {
				$x = 0;
				$bg = "#f4f4f4";
			}
			$tipStr = "";

			if ($inv["shape_rect"] == "1")

				$tipStr = $tipStr . " Rec ";



			if ($inv["shape_oct"] == "1")

				$tipStr = $tipStr . " Oct ";



			if ($inv["wall_2"] == "1")

				$tipStr = $tipStr . " 2W ";



			if ($inv["wall_3"] == "1")

				$tipStr = $tipStr . " 3W ";



			if ($inv["wall_4"] == "1")

				$tipStr = $tipStr . " 4W ";



			if ($inv["wall_5"] == "1")

				$tipStr = $tipStr . " 5W ";



			if ($inv["top_nolid"] == "1")

				$tipStr = $tipStr . " No Top,";



			if ($inv["top_partial"] == "1")

				$tipStr = $tipStr . " Flange Top, ";



			if ($inv["top_full"] == "1")

				$tipStr = $tipStr . " FFT, ";



			if ($inv["top_hinged"] == "1")

				$tipStr = $tipStr . " Hinge Top, ";



			if ($inv["top_remove"] == "1")

				$tipStr = $tipStr . " Lid Top, ";



			if ($inv["bottom_no"] == "1")

				$tipStr = $tipStr . " No Bottom";



			if ($inv["bottom_partial"] == "1")

				$tipStr = $tipStr . " PB w/o SS";



			if ($inv["bottom_partialsheet"] == "1")

				$tipStr = $tipStr . " PB w/ SS";



			if ($inv["bottom_fullflap"] == "1")

				$tipStr = $tipStr . " FFB";



			if ($inv["bottom_interlocking"] == "1")

				$tipStr = $tipStr . " FB";



			if ($inv["bottom_tray"] == "1")

				$tipStr = $tipStr . " Tray Bottom";



			if ($inv["vents_no"] == "1")

				$tipStr = $tipStr . "";



			if ($inv["vents_yes"] == "1")

				$tipStr = $tipStr . ", Vents";



		?>

			<?php
			$bpallet_qty = 0;
			$boxes_per_trailer = 0;
			$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
			db();
			$dt_view = db_query($qry);
			while ($sku_val = array_shift($dt_view)) {
				$sku = $sku_val['sku'];
				$bpallet_qty = $sku_val['bpallet_qty'];
				$boxes_per_trailer = $sku_val['boxes_per_trailer'];
			}

			$b2b_ulineDollar = round($inv["ulineDollar"]);
			$b2b_ulineCents = $inv["ulineCents"];
			$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
			$b2b_fob = number_format($b2b_fob, 2);

			$b2b_costDollar = round($inv["costDollar"]);
			$b2b_costCents = $inv["costCents"];
			$b2b_cost = $b2b_costDollar + $b2b_costCents;
			$b2b_cost = number_format($b2b_cost, 2);

			$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
			$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
			$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
			$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
			$sales_order_qty_new = 0;
			db();
			$dt_res_so_item1 = db_query($dt_so_item1);
			while ($so_item_row1 = array_shift($dt_res_so_item1)) {
				if ($so_item_row1["sumqty"] > 0) {
					$sales_order_qty_new = $so_item_row1["sumqty"];
				}
			}

			$b2b_status = "";
			db();
			$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
			while ($so_item_row1 = array_shift($dt_res_so_item1)) {
				$b2b_status = $so_item_row1["box_status"];
			}

			$to_show_data = "no";
			if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
				if (isset($filter_availability) == "truckloadonly") {
					if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
						$to_show_data = "yes";
					}
				}
				if (isset($filter_availability) == "anyavailableboxes") {
					if ($inv["after_actual_inventory"] > 0) {
						$to_show_data = "yes";
					}
				}
			} else {
				$to_show_data = "yes";
			}

			if ($to_show_data == "yes") {
				$top_head_flg_output = "yes";
				if ($top_head_flg == "no") {

					$top_head_flg = "yes";
			?>

					<tr align="middle">
						<div id="light" class="white_content"></div>
						<div id="fade" class="black_overlay"></div>
						<td colspan="12" class="style24" style="height: 16px"><strong>Non-UCB Gaylord Totes Inventory</strong></td>
					</tr>

					<tr>
						<td colspan="14">
							<div id="div_noninv_gaylord" name="div_noninv_gaylord">
								<table cellSpacing="1" cellPadding="1" border="0" width="1200">
									<tr vAlign="left">

										<td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(2,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(2,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

										<td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(3,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(3,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

										<td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(4,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(4,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

										<td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(16,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(16,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

										<td bgColor="#e4e4e4" class="style12">
											<font size=1><b>Account Owner&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(17,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(17,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
										</td>

										<td bgColor="#e4e4e4" class="style12">
											<font size=1><b>Supplier&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(5,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(5,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
										</td>

										<td bgColor="#e4e4e4" class="style12">
											<font size=1><b>Ship From&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(15,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(15,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
										</td>

										<td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(6,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(6,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
										</td>

										<td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(7,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(7,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
										</td>

										<td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(9,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(9,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

										<td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(10,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(10,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

										<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(11,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(11,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

										<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(12,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(12,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

										<td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(13,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(13,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

										<td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(14,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbgaylord(14,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>
									</tr>
								<?php } ?>


								<tr vAlign="center">

									<?php if ($sales_order_qty_new > 0) { ?>
										<td bgColor="<?php echo $bg; ?>" class="style12">
											<font color='blue' size=1>
												<div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
													<u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
												</div>
											</font>
										</td>
									<?php } else { ?>
										<td bgColor="<?php echo $bg; ?>" class="style12">
											<font size=1>
												<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
											</font>
										</td>
									<?php } ?>

									<td bgColor="<?php echo $bg; ?>" class="style12">
										<font size=1>
											<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?>
										</font>
									</td>

									<td bgColor="<?php echo $bg; ?>" class="style12">
										<?php if ($inv["availability"] == "3") echo "<b>"; ?>
										<?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
										<?php if ($inv["availability"] == "2") echo "Available Now"; ?>
										<?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
										<?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
										<?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
										<?php if ($inv["availability"] == "-1") echo "Presell"; ?>
										<?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
										<?php if ($inv["availability"] == "-3") echo "Potential"; ?>
										<?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
									</td>

									<td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

									<td bgColor="<?php echo $bg; ?>" class="style12left">
										<font size=1><?php echo isset($ownername); ?></font>
									</td>

									<td bgColor="<?php echo $bg; ?>" class="style12left">
										<font size=1>
											<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
										</font>
									</td>

									<td bgColor="<?php echo $bg; ?>" class="style12left">
										<font size=1>
											<?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
										</font>
									</td>

									<td bgColor="<?php echo $bg; ?>" class="style12">
										<font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
									</td>
									<td bgColor="<?php echo $bg; ?>" class="style12left">
										<font size=1>
											<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a target="_blank" href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View' id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
									</td>

									<td bgColor="<?php echo $bg; ?>" class="style12">
										<font size=1><?php echo $bpallet_qty; ?></font>
									</td>
									<td bgColor="<?php echo $bg; ?>" class="style12">
										<font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
									</td>
									<td bgColor="<?php echo $bg; ?>" class="style12">
										<font size=1><?php echo "$" . $b2b_fob; ?></font>
									</td>
									<td bgColor="<?php echo $bg; ?>" class="style12">
										<font size=1><?php echo "$" . $b2b_cost; ?></font>
									</td>

									<td bgColor="<?php echo $bg; ?>" class="style12left">
										<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
									</td>
									<td bgColor="<?php echo $bg; ?>" class="style12left">
										<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>' onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
									<?php } ?></td>
								</tr>

								<?php
								$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= $inv['actual_inventory'] . "</font></td>";
								if ($sales_order_qty_new > 0) {
									$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
									if ($inv['availability'] == '3') $inv_row .= "<b>";
									$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
								} else {
									$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
									if ($inv['availability'] == '3') $inv_row .= "<b>";
									$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
								}

								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12' >";
								if ($inv['availability'] == '3') $inv_row .= '<b>';
								if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
								if ($inv['availability'] == '2') $inv_row .= 'Available Now';
								if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
								if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
								if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
								if ($inv['availability'] == '-1') $inv_row .= 'Presell';
								if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
								if ($inv['availability'] == '-3') $inv_row .= 'Potential';
								if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
								$inv_row .= "</td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
								$inv_row .= isset($ownername) . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= $vendor_name . "</a></font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob  . "</font></td>";
								$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

								$inv_row .= "<td bgColor='$bg' class='style12left' >";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
								$inv_row .= "<td bgColor='$bg' class='style12left' >";
								if ($inv['availability'] == '3') $inv_row .= "<b>";
								if ($loop['id'] < 0) {
									$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
								} else {
									$inv_row .= $inv['N'] . "</a>";
								}
								$inv_row .= "</td>";
								$inv_row .= "</tr>";

								if ($inv["after_actual_inventory"] > 0) {
									if ($inv["availability"] == "3") {
										if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
											//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
											//$no_of_urgent_load_str .= $inv_row;
										}
									}
								}

								//&& ($boxes_per_trailer >= $inv["actual_inventory"])
								if ($inv["actual_inventory"] > 0) {
									if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
										$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
										$no_of_full_load_str .= $inv_row;
									}

									$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * floatval($b2b_fob));
								}

								if ($inv["actual_inventory"] > 0) {
									$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
									$no_of_full_load_str_ucb_inv_str .= $inv_row;
								}

								if ($inv["after_actual_inventory"] > 0) {
									$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
									$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
								}

								//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
								if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
									if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
										if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
											$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
											$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * floatval($b2b_fob);

											$tot_load_available_str .= $inv_row;
										}
									}
								}

								if ($inv["actual_inventory"] < 0) {
									$no_of_red_on_page = $no_of_red_on_page + 1;
									$no_of_red_on_page_str .= $inv_row;
								}

								$notes_date = new DateTime($inv["DT"]);
								$curr_date = new DateTime();

								$notes_date_diff = $curr_date->diff($notes_date)->days;
								if ($notes_date_diff > 7) {
									$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
									$no_of_inv_item_note_date_str .= $inv_row;
								}

								?>
								<tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan="14" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
										<div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
									</td>
								</tr>

							<?php
							$count_arry = $count_arry + 1;
						}
					}

					if ($top_head_flg_output == "yes") {
							?>
								</table>
							</div>
						</td>
					</tr>

					<tr align="middle">
						<td>&nbsp;</td>
					</tr>
				<?php } ?>

				<?php $top_head_flg = "no";
				$top_head_flg_output = "no"; ?>

				<?php
				$x = 0;

				//$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.box_type = 'Boxnonucb' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC, vendors.name ASC";
				$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.box_type = 'Boxnonucb' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
				//echo $sql . "<br>";
				db_b2b();
				$dt_view_res = db_query($sql);

				while ($inv = array_shift($dt_view_res)) {
					$vendor_name = "";
					//account owner
					if ($inv["vendor_b2b_rescue"] > 0) {

						$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
						$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
						db();
						$query = db_query($q1);
						while ($fetch = array_shift($query)) {
							$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

							$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
							db_b2b();
							$comres = db_query($comqry);
							while ($comrow = array_shift($comres)) {
								$ownername = $comrow["initials"];
							}
						}
					} else {
						$vendor_b2b_rescue = $inv["V"];
						$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
						db_b2b();
						$query = db_query($q1);
						while ($fetch = array_shift($query)) {
							$vendor_name = $fetch["Name"];

							$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
							db_b2b();
							$comres = db_query($comqry);
							while ($comrow = array_shift($comres)) {
								$ownername = $comrow["initials"];
							}
						}
					}
					//

					$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
					db();
					$loop_res = db_query($loopsql);
					$loop = array_shift($loop_res);
					if ($x == 0) {
						$x = 1;
						$bg = "#e4e4e4";
					} else {
						$x = 0;
						$bg = "#f4f4f4";
					}
					$tipStr = "";

					if ($inv["shape_rect"] == "1")
						$tipStr = $tipStr . " Rec ";
					if ($inv["shape_oct"] == "1")

						$tipStr = $tipStr . " Oct ";
					if ($inv["wall_2"] == "1")

						$tipStr = $tipStr . " 2W ";
					if ($inv["wall_3"] == "1")

						$tipStr = $tipStr . " 3W ";
					if ($inv["wall_4"] == "1")

						$tipStr = $tipStr . " 4W ";
					if ($inv["wall_5"] == "1")

						$tipStr = $tipStr . " 5W ";
					if ($inv["top_nolid"] == "1")

						$tipStr = $tipStr . " No Top,";
					if ($inv["top_partial"] == "1")

						$tipStr = $tipStr . " Flange Top, ";
					if ($inv["top_full"] == "1")

						$tipStr = $tipStr . " FFT, ";
					if ($inv["top_hinged"] == "1")

						$tipStr = $tipStr . " Hinge Top, ";
					if ($inv["top_remove"] == "1")

						$tipStr = $tipStr . " Lid Top, ";
					if ($inv["bottom_no"] == "1")
						$tipStr = $tipStr . " No Bottom";
					if ($inv["bottom_partial"] == "1")

						$tipStr = $tipStr . " PB w/o SS";
					if ($inv["bottom_partialsheet"] == "1")

						$tipStr = $tipStr . " PB w/ SS";
					if ($inv["bottom_fullflap"] == "1")

						$tipStr = $tipStr . " FFB";
					if ($inv["bottom_interlocking"] == "1")
						$tipStr = $tipStr . " FB";
					if ($inv["bottom_tray"] == "1")
						$tipStr = $tipStr . " Tray Bottom";
					if ($inv["vents_no"] == "1")
						$tipStr = $tipStr . "";
					if ($inv["vents_yes"] == "1")
						$tipStr = $tipStr . ", Vents";
				?>

					<?php
					$bpallet_qty = 0;
					$boxes_per_trailer = 0;
					$work_as_kit_box = 0;
					$qry = "select sku, bpallet_qty, boxes_per_trailer,work_as_kit_box  from loop_boxes where id=" . $loop["id"];
					db();
					$dt_view = db_query($qry);
					while ($sku_val = array_shift($dt_view)) {
						$sku = $sku_val['sku'];
						$bpallet_qty = $sku_val['bpallet_qty'];
						$boxes_per_trailer = $sku_val['boxes_per_trailer'];
						$work_as_kit_box = $sku_val['work_as_kit_box'];
					}

					$b2b_ulineDollar = round($inv["ulineDollar"]);
					$b2b_ulineCents = $inv["ulineCents"];
					$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
					$b2b_fob = "$ " . number_format($b2b_fob, 2);

					$b2b_costDollar = round($inv["costDollar"]);
					$b2b_costCents = $inv["costCents"];
					$b2b_cost = $b2b_costDollar + $b2b_costCents;
					$b2b_cost = "$ " . number_format($b2b_cost, 2);

					$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
					$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
					$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
					$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
					$sales_order_qty_new = 0;
					db();
					$dt_res_so_item1 = db_query($dt_so_item1);
					while ($so_item_row1 = array_shift($dt_res_so_item1)) {
						if ($so_item_row1["sumqty"] > 0) {
							$sales_order_qty_new = $so_item_row1["sumqty"];
						}
					}

					$to_show_data = "no";
					if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
						if (isset($filter_availability) == "truckloadonly") {
							if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
								$to_show_data = "yes";
							}
						}
						if (isset($filter_availability) == "anyavailableboxes") {
							if ($inv["after_actual_inventory"] > 0) {
								$to_show_data = "yes";
							}
						}
					} else {
						$to_show_data = "yes";
					}

					if ($to_show_data == "yes") {

						$b2b_status = "";
						db();
						$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
						while ($so_item_row1 = array_shift($dt_res_so_item1)) {
							$b2b_status = $so_item_row1["box_status"];
						}

						$top_head_flg_output = "yes";
						if ($top_head_flg == "no") {

							$top_head_flg = "yes";
					?>
							<tr align="middle">
								<td colspan="13" class="style24" style="height: 16px"><strong>Non-UCB Shipping Boxes Inventory</strong></td>
							</tr>
							<tr>
								<td colspan="13">
									<div id="div_noninv_shipping" name="div_noninv_shipping">
										<table cellSpacing="1" cellPadding="1" border="0" width="1200">
											<tr vAlign="left">


												<td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(2,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(2,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

												<td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(3,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(3,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

												<td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(4,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(4,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

												<td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(16,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(16,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

												<td bgColor="#e4e4e4" class="style12">
													<font size=1><b>Account Owner&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(17,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(17,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
												</td>

												<td bgColor="#e4e4e4" class="style12">
													<font size=1><b>Supplier&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(5,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(5,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
												</td>

												<td bgColor="#e4e4e4" class="style12">
													<font size=1><b>Ship From&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(15,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(15,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
												</td>

												<td bgColor="#e4e4e4" class="style12">
													<font size=1><b>Work as a Kit box?&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(16,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(16,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
												</td>

												<td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(6,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(6,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
												</td>

												<td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(7,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(7,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
												</td>

												<td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(9,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(9,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

												<td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(10,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(10,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

												<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(11,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(11,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

												<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(12,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(12,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

												<td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(13,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(13,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

												<td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(14,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbshipping(14,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>
											</tr>

											<tr vAlign="left">
												<td colspan=15>
												<?php } ?>

											<tr vAlign="center">

												<?php if ($sales_order_qty_new > 0) { ?>
													<td bgColor="<?php echo $bg; ?>" class="style12">
														<font color='blue' size=1>
															<div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
																<u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
															</div>
														</font>
													</td>
												<?php } else { ?>
													<td bgColor="<?php echo $bg; ?>" class="style12">
														<font size=1>
															<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
														</font>
													</td>
												<?php } ?>

												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1>
														<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?>
													</font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12">
													<?php if ($inv["availability"] == "3") echo "<b>"; ?>
													<?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
													<?php if ($inv["availability"] == "2") echo "Available Now"; ?>
													<?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
													<?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
													<?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
													<?php if ($inv["availability"] == "-1") echo "Presell"; ?>
													<?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
													<?php if ($inv["availability"] == "-3") echo "Potential"; ?>
													<?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<font size=1><?php echo isset($ownername); ?></font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<font size=1>
														<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
													</font>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<font size=1>
														<?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
													</font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo $work_as_kit_box; ?></font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<font size=1>
														<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a target="_blank" href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View' id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo $bpallet_qty; ?></font>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo $b2b_fob; ?></font>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12">
													<font size=1><?php echo $b2b_cost; ?></font>
												</td>

												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
												</td>
												<td bgColor="<?php echo $bg; ?>" class="style12left">
													<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>' onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
												<?php } ?></td>

											<tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td colspan="14" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
													<div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
												</td>
											</tr>

											<?php
											$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $inv['actual_inventory'] . "</font></td>";

											if ($sales_order_qty_new > 0) {
												$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
												if ($inv['availability'] == '3') $inv_row .= "<b>";
												$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
											} else {
												$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
												if ($inv['availability'] == '3') $inv_row .= "<b>";
												$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
											}

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' >";
											if ($inv['availability'] == '3') $inv_row .= '<b>';
											if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
											if ($inv['availability'] == '2') $inv_row .= 'Available Now';
											if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
											if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
											if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
											if ($inv['availability'] == '-1') $inv_row .= 'Presell';
											if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
											if ($inv['availability'] == '-3') $inv_row .= 'Potential';
											if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
											$inv_row .= "</td>  ";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											$inv_row .= isset($ownername) . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= $vendor_name . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob . "</font></td>";
											$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

											$inv_row .= "<td bgColor='$bg' class='style12left' >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
											$inv_row .= "<td bgColor='$bg' class='style12left' >";
											if ($inv['availability'] == '3') $inv_row .= "<b>";
											if ($loop['id'] < 0) {
												$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
											} else {
												$inv_row .= $inv['N'] . "</a>";
											}
											$inv_row .= "</td>";
											$inv_row .= "</tr>";

											if ($inv["after_actual_inventory"] > 0) {
												if ($inv["availability"] == "3") {
													if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
														//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
														//$no_of_urgent_load_str .= $inv_row;
													}
												}
											}
											//&& ($boxes_per_trailer >= $inv["actual_inventory"])
											if ($inv["actual_inventory"] > 0) {
												if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
													$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
													$no_of_full_load_str .= $inv_row;
												}

												$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * floatval($b2b_fob));
											}

											if ($inv["actual_inventory"] > 0) {
												$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
												$no_of_full_load_str_ucb_inv_str .= $inv_row;
											}

											if ($inv["after_actual_inventory"] > 0) {
												$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
												$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
											}

											//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
											if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
												if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
													if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
														$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
														$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * floatval($b2b_fob);

														$tot_load_available_str .= $inv_row;
													}
												}
											}

											if ($inv["actual_inventory"] < 0) {
												$no_of_red_on_page = $no_of_red_on_page + 1;
												$no_of_red_on_page_str .= $inv_row;
											}

											$notes_date = new DateTime($inv["DT"]);
											$curr_date = new DateTime();

											$notes_date_diff = $curr_date->diff($notes_date)->days;
											if ($notes_date_diff > 7) {
												$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
												$no_of_inv_item_note_date_str .= $inv_row;
											}
											?>

										<?php
										$count_arry = $count_arry + 1;
									}
								}

								if ($top_head_flg_output == "yes") {
										?>
										</table>
									</div>
								</td>
							</tr>

							<tr align="middle">
								<td>&nbsp;</td>
							</tr>
						<?php }
								$top_head_flg = "no";
								$top_head_flg_output = "no";
						?>

						<?php
						$x = 0;

						//$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.box_type = 'SupersacknonUCB' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC, vendors.name ASC";
						$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.box_type = 'SupersacknonUCB' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
						//echo $sql . "<br>";
						db_b2b();
						$dt_view_res = db_query($sql);

						while ($inv = array_shift($dt_view_res)) {
							$vendor_name = "";
							//account owner
							if ($inv["vendor_b2b_rescue"] > 0) {

								$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
								$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
								db();
								$query = db_query($q1);
								while ($fetch = array_shift($query)) {
									$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

									$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
									db_b2b();
									$comres = db_query($comqry);
									while ($comrow = array_shift($comres)) {
										$ownername = $comrow["initials"];
									}
								}
							} else {
								$vendor_b2b_rescue = $inv["V"];
								$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
								db_b2b();
								$query = db_query($q1);
								while ($fetch = array_shift($query)) {
									$vendor_name = $fetch["Name"];

									$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
									db_b2b();
									$comres = db_query($comqry);
									while ($comrow = array_shift($comres)) {
										$ownername = $comrow["initials"];
									}
								}
							}
							//
							$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
							db();
							$loop_res = db_query($loopsql);

							$loop = array_shift($loop_res);

							if ($x == 0) {
								$x = 1;
								$bg = "#e4e4e4";
							} else {
								$x = 0;
								$bg = "#f4f4f4";
							}
							$tipStr = "";

							if ($inv["shape_rect"] == "1")
								$tipStr = $tipStr . " Rec ";

							if ($inv["shape_oct"] == "1")
								$tipStr = $tipStr . " Oct ";

							if ($inv["wall_2"] == "1")
								$tipStr = $tipStr . " 2W ";

							if ($inv["wall_3"] == "1")
								$tipStr = $tipStr . " 3W ";

							if ($inv["wall_4"] == "1")
								$tipStr = $tipStr . " 4W ";

							if ($inv["wall_5"] == "1")
								$tipStr = $tipStr . " 5W ";

							if ($inv["top_nolid"] == "1")
								$tipStr = $tipStr . " No Top,";

							if ($inv["top_partial"] == "1")
								$tipStr = $tipStr . " Flange Top, ";

							if ($inv["top_full"] == "1")
								$tipStr = $tipStr . " FFT, ";

							if ($inv["top_hinged"] == "1")
								$tipStr = $tipStr . " Hinge Top, ";

							if ($inv["top_remove"] == "1")
								$tipStr = $tipStr . " Lid Top, ";

							if ($inv["bottom_no"] == "1")
								$tipStr = $tipStr . " No Bottom";

							if ($inv["bottom_partial"] == "1")
								$tipStr = $tipStr . " PB w/o SS";

							if ($inv["bottom_partialsheet"] == "1")
								$tipStr = $tipStr . " PB w/ SS";

							if ($inv["bottom_fullflap"] == "1")
								$tipStr = $tipStr . " FFB";

							if ($inv["bottom_interlocking"] == "1")
								$tipStr = $tipStr . " FB";

							if ($inv["bottom_tray"] == "1")
								$tipStr = $tipStr . " Tray Bottom";

							if ($inv["vents_no"] == "1")
								$tipStr = $tipStr . "";

							if ($inv["vents_yes"] == "1")
								$tipStr = $tipStr . ", Vents";
						?>

							<?php
							$bpallet_qty = 0;
							$boxes_per_trailer = 0;
							$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
							db();
							$dt_view = db_query($qry);
							while ($sku_val = array_shift($dt_view)) {
								$sku = $sku_val['sku'];
								$bpallet_qty = $sku_val['bpallet_qty'];
								$boxes_per_trailer = $sku_val['boxes_per_trailer'];
							}

							$b2b_ulineDollar = round($inv["ulineDollar"]);
							$b2b_ulineCents = $inv["ulineCents"];
							$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
							$b2b_fob = "$ " . number_format($b2b_fob, 2);

							$b2b_costDollar = round($inv["costDollar"]);
							$b2b_costCents = $inv["costCents"];
							$b2b_cost = $b2b_costDollar + $b2b_costCents;
							$b2b_cost = "$ " . number_format($b2b_cost, 2);

							$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
							$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
							$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
							$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
							$sales_order_qty_new = 0;
							db();
							$dt_res_so_item1 = db_query($dt_so_item1);
							while ($so_item_row1 = array_shift($dt_res_so_item1)) {
								if ($so_item_row1["sumqty"] > 0) {
									$sales_order_qty_new = $so_item_row1["sumqty"];
								}
							}

							$to_show_data = "no";
							if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
								if (isset($filter_availability) == "truckloadonly") {
									if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
										$to_show_data = "yes";
									}
								}
								if (isset($filter_availability) == "anyavailableboxes") {
									if ($inv["after_actual_inventory"] > 0) {
										$to_show_data = "yes";
									}
								}
							} else {
								$to_show_data = "yes";
							}

							if ($to_show_data == "yes") {

								$b2b_status = "";
								db();
								$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
								while ($so_item_row1 = array_shift($dt_res_so_item1)) {
									$b2b_status = $so_item_row1["box_status"];
								}

								$top_head_flg_output = "yes";
								if ($top_head_flg == "no") {

									$top_head_flg = "yes";
							?>
									<tr align="middle">
										<td colspan="13" class="style24" style="height: 16px"><strong>Non-UCB Supersack Inventory</strong></td>
									</tr>
									<tr>
										<td colspan="13">
											<div id="div_noninv_supersack" name="div_noninv_supersack">
												<table cellSpacing="1" cellPadding="1" border="0" width="1200">
													<tr vAlign="left">
														<td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(2,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(2,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

														<td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(3,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(3,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

														<td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(4,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(4,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

														<td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(16,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(16,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

														<td bgColor="#e4e4e4" class="style12">
															<font size=1><b>Account Owner&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(17,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(17,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
														</td>

														<td bgColor="#e4e4e4" class="style12">
															<font size=1><b>Supplier&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(5,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(5,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
														</td>

														<td bgColor="#e4e4e4" class="style12">
															<font size=1><b>Ship From&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(15,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(15,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
														</td>

														<td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(6,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(6,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
														</td>

														<td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(7,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(7,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
														</td>

														<td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(9,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(9,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

														<td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(10,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(10,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

														<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(11,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(11,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

														<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(12,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(12,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

														<td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(13,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(13,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

														<td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(14,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbsupersack(14,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>
													</tr>

													<tr vAlign="left">
														<td colspan=15>
														<?php } ?>

													<tr vAlign="center">

														<?php if ($sales_order_qty_new > 0) { ?>
															<td bgColor="<?php echo $bg; ?>" class="style12">
																<font color='blue' size=1>
																	<div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
																		<u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
																	</div>
																</font>
															</td>
														<?php } else { ?>
															<td bgColor="<?php echo $bg; ?>" class="style12">
																<font size=1>
																	<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
																</font>
															</td>
														<?php } ?>

														<td bgColor="<?php echo $bg; ?>" class="style12">
															<font size=1>
																<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?></a>
															</font>
														</td>

														<td bgColor="<?php echo $bg; ?>" class="style12">
															<?php if ($inv["availability"] == "3") echo "<b>"; ?>
															<?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
															<?php if ($inv["availability"] == "2") echo "Available Now"; ?>
															<?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
															<?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
															<?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
															<?php if ($inv["availability"] == "-1") echo "Presell"; ?>
															<?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
															<?php if ($inv["availability"] == "-3") echo "Potential"; ?>
															<?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
														</td>

														<td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

														<td bgColor="<?php echo $bg; ?>" class="style12left">
															<font size=1><?php echo isset($ownername); ?></font>
														</td>

														<td bgColor="<?php echo $bg; ?>" class="style12left">
															<font size=1>
																<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
															</font>
														</td>
														<td bgColor="<?php echo $bg; ?>" class="style12left">
															<font size=1>
																<?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
															</font>
														</td>

														<td bgColor="<?php echo $bg; ?>" class="style12">
															<font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
														</td>
														<td bgColor="<?php echo $bg; ?>" class="style12left">
															<font size=1>
																<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a target="_blank" href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View' id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
														</td>

														<td bgColor="<?php echo $bg; ?>" class="style12">
															<font size=1><?php echo $bpallet_qty; ?></font>
														</td>
														<td bgColor="<?php echo $bg; ?>" class="style12">
															<font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
														</td>
														<td bgColor="<?php echo $bg; ?>" class="style12">
															<font size=1><?php echo $b2b_fob; ?></font>
														</td>
														<td bgColor="<?php echo $bg; ?>" class="style12">
															<font size=1><?php echo $b2b_cost; ?></font>
														</td>

														<td bgColor="<?php echo $bg; ?>" class="style12left">
															<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
														</td>
														<td bgColor="<?php echo $bg; ?>" class="style12left">
															<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>' onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
														<?php } ?></td>

													<tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
														<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td colspan="14" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
															<div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
														</td>
													</tr>

													<?php
													$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= $inv['actual_inventory'] . "</font></td>";

													if ($sales_order_qty_new > 0) {
														$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
														if ($inv['availability'] == '3') $inv_row .= "<b>";
														$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
													} else {
														$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
														if ($inv['availability'] == '3') $inv_row .= "<b>";
														$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
													}

													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12' >";
													if ($inv['availability'] == '3') $inv_row .= '<b>';
													if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
													if ($inv['availability'] == '2') $inv_row .= 'Available Now';
													if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
													if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
													if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
													if ($inv['availability'] == '-1') $inv_row .= 'Presell';
													if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
													if ($inv['availability'] == '-3') $inv_row .= 'Potential';
													if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
													$inv_row .= "</td>  ";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
													$inv_row .= isset($ownername) . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= $vendor_name . "</a></font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob . "</font></td>";
													$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

													$inv_row .= "<td bgColor='$bg' class='style12left' >";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
													$inv_row .= "<td bgColor='$bg' class='style12left' >";
													if ($inv['availability'] == '3') $inv_row .= "<b>";
													if ($loop['id'] < 0) {
														$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
													} else {
														$inv_row .= $inv['N'] . "</a>";
													}
													$inv_row .= "</td>";
													$inv_row .= "</tr>";

													if ($inv["after_actual_inventory"] > 0) {
														if ($inv["availability"] == "3") {
															if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																//$no_of_urgent_load_str .= $inv_row;
															}
														}
													}
													//&& ($boxes_per_trailer >= $inv["actual_inventory"])
													if ($inv["actual_inventory"] > 0) {
														if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
															$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
															$no_of_full_load_str .= $inv_row;
														}

														$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * floatval($b2b_fob));
													}

													if ($inv["actual_inventory"] > 0) {
														$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
														$no_of_full_load_str_ucb_inv_str .= $inv_row;
													}

													if ($inv["after_actual_inventory"] > 0) {
														$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
														$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
													}

													//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
													if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
														if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
															if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * floatval($b2b_fob);

																$tot_load_available_str .= $inv_row;
															}
														}
													}

													if ($inv["actual_inventory"] < 0) {
														$no_of_red_on_page = $no_of_red_on_page + 1;
														$no_of_red_on_page_str .= $inv_row;
													}

													$notes_date = new DateTime($inv["DT"]);
													$curr_date = new DateTime();

													$notes_date_diff = $curr_date->diff($notes_date)->days;
													if ($notes_date_diff > 7) {
														$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
														$no_of_inv_item_note_date_str .= $inv_row;
													}
													?>
												<?php
												$count_arry = $count_arry + 1;
											}
										}

										if ($top_head_flg_output == "yes") {
												?>
												</table>
											</div>
										</td>
									</tr>

									<tr align="middle">
										<td>&nbsp;</td>
									</tr>
								<?php }

										$top_head_flg = "no";
										$top_head_flg_output = "no";
								?>


								<?php
								$x = 0;

								//$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.box_type = 'DrumBarrelnonUCB' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC, vendors.name ASC";
								$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory where inventory.box_type = 'DrumBarrelnonUCB' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
								//echo $sql . "<br>";
								db_b2b();
								$dt_view_res = db_query($sql);

								while ($inv = array_shift($dt_view_res)) {
									$vendor_name = "";
									//account owner
									if ($inv["vendor_b2b_rescue"] > 0) {

										$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
										$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
										db();
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									} else {
										$vendor_b2b_rescue = $inv["V"];
										$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
										db_b2b();
										$query = db_query($q1);
										while ($fetch = array_shift($query)) {
											$vendor_name = $fetch["Name"];

											$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
											db_b2b();
											$comres = db_query($comqry);
											while ($comrow = array_shift($comres)) {
												$ownername = $comrow["initials"];
											}
										}
									}
									$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
									db();
									$loop_res = db_query($loopsql);

									$loop = array_shift($loop_res);
									if ($x == 0) {
										$x = 1;
										$bg = "#e4e4e4";
									} else {
										$x = 0;
										$bg = "#f4f4f4";
									}
									$tipStr = "";
									if ($inv["shape_rect"] == "1")
										$tipStr = $tipStr . " Rec ";

									if ($inv["shape_oct"] == "1")
										$tipStr = $tipStr . " Oct ";

									if ($inv["wall_2"] == "1")
										$tipStr = $tipStr . " 2W ";

									if ($inv["wall_3"] == "1")
										$tipStr = $tipStr . " 3W ";

									if ($inv["wall_4"] == "1")
										$tipStr = $tipStr . " 4W ";

									if ($inv["wall_5"] == "1")
										$tipStr = $tipStr . " 5W ";

									if ($inv["top_nolid"] == "1")
										$tipStr = $tipStr . " No Top,";

									if ($inv["top_partial"] == "1")
										$tipStr = $tipStr . " Flange Top, ";

									if ($inv["top_full"] == "1")
										$tipStr = $tipStr . " FFT, ";

									if ($inv["top_hinged"] == "1")
										$tipStr = $tipStr . " Hinge Top, ";

									if ($inv["top_remove"] == "1")
										$tipStr = $tipStr . " Lid Top, ";

									if ($inv["bottom_no"] == "1")
										$tipStr = $tipStr . " No Bottom";

									if ($inv["bottom_partial"] == "1")
										$tipStr = $tipStr . " PB w/o SS";

									if ($inv["bottom_partialsheet"] == "1")
										$tipStr = $tipStr . " PB w/ SS";

									if ($inv["bottom_fullflap"] == "1")
										$tipStr = $tipStr . " FFB";

									if ($inv["bottom_interlocking"] == "1")
										$tipStr = $tipStr . " FB";

									if ($inv["bottom_tray"] == "1")
										$tipStr = $tipStr . " Tray Bottom";

									if ($inv["vents_no"] == "1")
										$tipStr = $tipStr . "";

									if ($inv["vents_yes"] == "1")

										$tipStr = $tipStr . ", Vents";
								?>

									<?php
									$bpallet_qty = 0;
									$boxes_per_trailer = 0;
									$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
									db();
									$dt_view = db_query($qry);
									while ($sku_val = array_shift($dt_view)) {
										$sku = $sku_val['sku'];
										$bpallet_qty = $sku_val['bpallet_qty'];
										$boxes_per_trailer = $sku_val['boxes_per_trailer'];
									}

									$b2b_ulineDollar = round($inv["ulineDollar"]);
									$b2b_ulineCents = $inv["ulineCents"];
									$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
									$b2b_fob = "$ " . number_format($b2b_fob, 2);

									$b2b_costDollar = round($inv["costDollar"]);
									$b2b_costCents = $inv["costCents"];
									$b2b_cost = $b2b_costDollar + $b2b_costCents;
									$b2b_cost = "$ " . number_format($b2b_cost, 2);

									$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
									$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
									$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
									$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
									$sales_order_qty_new = 0;
									db();
									$dt_res_so_item1 = db_query($dt_so_item1);
									while ($so_item_row1 = array_shift($dt_res_so_item1)) {
										if ($so_item_row1["sumqty"] > 0) {
											$sales_order_qty_new = $so_item_row1["sumqty"];
										}
									}

									$to_show_data = "no";
									if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
										if (isset($filter_availability) == "truckloadonly") {
											if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
												$to_show_data = "yes";
											}
										}
										if (isset($filter_availability) == "anyavailableboxes") {
											if ($inv["after_actual_inventory"] > 0) {
												$to_show_data = "yes";
											}
										}
									} else {
										$to_show_data = "yes";
									}

									if ($to_show_data == "yes") {

										$b2b_status = "";
										db();
										$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
										while ($so_item_row1 = array_shift($dt_res_so_item1)) {
											$b2b_status = $so_item_row1["box_status"];
										}

										$top_head_flg_output = "yes";
										if ($top_head_flg == "no") {

											$top_head_flg = "yes";
									?>

											<tr align="middle">
												<td colspan="13" class="style24" style="height: 16px"><strong>Non-UCB Drum/Barrel Inventory</strong></td>
											</tr>
											<tr>
												<td colspan="13">
													<div id="div_noninv_drumBarrel" name="div_noninv_drumBarrel">
														<table cellSpacing="1" cellPadding="1" border="0" width="1200">
															<tr vAlign="left">

																<td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(2,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(2,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																<td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(3,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(3,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																<td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(4,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(4,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																<td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(16,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(16,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																<td bgColor="#e4e4e4" class="style12">
																	<font size=1><b>Account Owner&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(17,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(17,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																</td>

																<td bgColor="#e4e4e4" class="style12">
																	<font size=1><b>Supplier&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(5,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(5,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																</td>

																<td bgColor="#e4e4e4" class="style12">
																	<font size=1><b>Ship From&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(15,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(15,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																</td>

																<td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(6,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(6,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																</td>

																<td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(7,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(7,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																</td>

																<td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(9,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(9,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																<td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(10,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(10,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(11,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(11,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(12,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(12,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																<td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(13,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(13,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																<td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(14,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbdrumBarrel(14,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>
															</tr>

															<tr vAlign="left">
																<td colspan=15>
																<?php } ?>

															<tr vAlign="center">


																<?php if ($sales_order_qty_new > 0) { ?>
																	<td bgColor="<?php echo $bg; ?>" class="style12">
																		<font color='blue' size=1>
																			<div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
																				<u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
																			</div>
																		</font>
																	</td>
																<?php } else { ?>
																	<td bgColor="<?php echo $bg; ?>" class="style12">
																		<font size=1>
																			<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
																		</font>
																	</td>
																<?php } ?>

																<td bgColor="<?php echo $bg; ?>" class="style12">
																	<font size=1>
																		<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["expected_loads_per_mo"]; ?>
																	</font>
																</td>

																<td bgColor="<?php echo $bg; ?>" class="style12">
																	<?php if ($inv["availability"] == "3") echo "<b>"; ?>
																	<?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
																	<?php if ($inv["availability"] == "2") echo "Available Now"; ?>
																	<?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
																	<?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
																	<?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
																	<?php if ($inv["availability"] == "-1") echo "Presell"; ?>
																	<?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
																	<?php if ($inv["availability"] == "-3") echo "Potential"; ?>
																	<?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
																</td>

																<td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

																<td bgColor="<?php echo $bg; ?>" class="style12left">
																	<font size=1><?php echo isset($ownername); ?></font>
																</td>

																<td bgColor="<?php echo $bg; ?>" class="style12left">
																	<font size=1>
																		<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
																	</font>
																</td>
																<td bgColor="<?php echo $bg; ?>" class="style12left">
																	<font size=1>
																		<?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
																	</font>
																</td>

																<td bgColor="<?php echo $bg; ?>" class="style12">
																	<font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
																</td>
																<td bgColor="<?php echo $bg; ?>" class="style12left">
																	<font size=1>
																		<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a target="_blank" href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View' id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
																</td>

																<td bgColor="<?php echo $bg; ?>" class="style12">
																	<font size=1><?php echo $bpallet_qty; ?></font>
																</td>
																<td bgColor="<?php echo $bg; ?>" class="style12">
																	<font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
																</td>
																<td bgColor="<?php echo $bg; ?>" class="style12">
																	<font size=1><?php echo $b2b_fob; ?></font>
																</td>
																<td bgColor="<?php echo $bg; ?>" class="style12">
																	<font size=1><?php echo $b2b_cost; ?></font>
																</td>

																<td bgColor="<?php echo $bg; ?>" class="style12left">
																	<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
																</td>
																<td bgColor="<?php echo $bg; ?>" class="style12left">
																	<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>' onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
																<?php } ?></td>

															<tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
																<td>&nbsp;</td>
																<td>&nbsp;</td>
																<td colspan="14" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
																	<div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
																</td>
															</tr>

															<?php
															$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= $inv['actual_inventory'] . "</font></td>";

															if ($sales_order_qty_new > 0) {
																$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
																if ($inv['availability'] == '3') $inv_row .= "<b>";
																$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
															} else {
																$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
																if ($inv['availability'] == '3') $inv_row .= "<b>";
																$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
															}

															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12' >";
															if ($inv['availability'] == '3') $inv_row .= '<b>';
															if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
															if ($inv['availability'] == '2') $inv_row .= 'Available Now';
															if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
															if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
															if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
															if ($inv['availability'] == '-1') $inv_row .= 'Presell';
															if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
															if ($inv['availability'] == '-3') $inv_row .= 'Potential';
															if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
															$inv_row .= "</td>  ";


															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
															$inv_row .= isset($ownername) . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= $vendor_name . "</a></font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . isset($sku) . "</a></font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob . "</font></td>";
															$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

															$inv_row .= "<td bgColor='$bg' class='style12left' >";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
															$inv_row .= "<td bgColor='$bg' class='style12left' >";
															if ($inv['availability'] == '3') $inv_row .= "<b>";
															if ($loop['id'] < 0) {
																$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
															} else {
																$inv_row .= $inv['N'] . "</a>";
															}
															$inv_row .= "</td>";
															$inv_row .= "</tr>";

															if ($inv["after_actual_inventory"] > 0) {
																if ($inv["availability"] == "3") {
																	if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																		//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																		//$no_of_urgent_load_str .= $inv_row;
																	}
																}
															}
															//&& ($boxes_per_trailer >= $inv["actual_inventory"])
															if ($inv["actual_inventory"] > 0) {
																if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
																	$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
																	$no_of_full_load_str .= $inv_row;
																}

																$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * floatval($b2b_fob));
															}

															if ($inv["actual_inventory"] > 0) {
																$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
																$no_of_full_load_str_ucb_inv_str .= $inv_row;
															}

															if ($inv["after_actual_inventory"] > 0) {
																$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
																$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
															}

															//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
															if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
																if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
																	if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																		$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																		$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * floatval($b2b_fob);

																		$tot_load_available_str .= $inv_row;
																	}
																}
															}

															if ($inv["actual_inventory"] < 0) {
																$no_of_red_on_page = $no_of_red_on_page + 1;
																$no_of_red_on_page_str .= $inv_row;
															}

															$notes_date = new DateTime($inv["DT"]);
															$curr_date = new DateTime();

															$notes_date_diff = $curr_date->diff($notes_date)->days;
															if ($notes_date_diff > 7) {
																$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
																$no_of_inv_item_note_date_str .= $inv_row;
															}
															?>

														<?php
														$count_arry = $count_arry + 1;
													}
												}

												if ($top_head_flg_output == "yes") {
														?>
														</table>
													</div>
												</td>
											</tr>

											<tr align="middle">
												<td>&nbsp;</td>
											</tr>
										<?php }
												$top_head_flg = "no";
												$top_head_flg_output = "no";
										?>
										<?php
										$x = 0;

										//$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE inventory.box_type = 'PalletsnonUCB' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC, vendors.name ASC";
										$sql = "SELECT *, inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, inventory.vendor AS V FROM inventory WHERE inventory.box_type = 'PalletsnonUCB' AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 $main_new_where_condition ORDER BY inventory.availability DESC";
										//echo $sql . "<br>";
										db_b2b();
										$dt_view_res = db_query($sql);

										while ($inv = array_shift($dt_view_res)) {
											$vendor_name = "";
											$ownername = "";
											//account owner
											if ($inv["vendor_b2b_rescue"] > 0) {

												$vendor_b2b_rescue = $inv["vendor_b2b_rescue"];
												$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
												db();
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											} else {
												$vendor_b2b_rescue = $inv["V"];
												$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
												db_b2b();
												$query = db_query($q1);
												while ($fetch = array_shift($query)) {
													$vendor_name = $fetch["Name"];

													$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
													db_b2b();
													$comres = db_query($comqry);
													while ($comrow = array_shift($comres)) {
														$ownername = $comrow["initials"];
													}
												}
											}
											//
											$loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
											db();
											$loop_res = db_query($loopsql);

											$loop = array_shift($loop_res);

											if ($x == 0) {
												$x = 1;
												$bg = "#e4e4e4";
											} else {
												$x = 0;
												$bg = "#f4f4f4";
											}
											$tipStr = "";

											if ($inv["shape_rect"] == "1")
												$tipStr = $tipStr . " Rec ";

											if ($inv["shape_oct"] == "1")
												$tipStr = $tipStr . " Oct ";

											if ($inv["wall_2"] == "1")
												$tipStr = $tipStr . " 2W ";

											if ($inv["wall_3"] == "1")
												$tipStr = $tipStr . " 3W ";

											if ($inv["wall_4"] == "1")
												$tipStr = $tipStr . " 4W ";

											if ($inv["wall_5"] == "1")
												$tipStr = $tipStr . " 5W ";

											if ($inv["top_nolid"] == "1")
												$tipStr = $tipStr . " No Top,";

											if ($inv["top_partial"] == "1")
												$tipStr = $tipStr . " Flange Top, ";

											if ($inv["top_full"] == "1")
												$tipStr = $tipStr . " FFT, ";

											if ($inv["top_hinged"] == "1")
												$tipStr = $tipStr . " Hinge Top, ";

											if ($inv["top_remove"] == "1")
												$tipStr = $tipStr . " Lid Top, ";

											if ($inv["bottom_no"] == "1")
												$tipStr = $tipStr . " No Bottom";

											if ($inv["bottom_partial"] == "1")
												$tipStr = $tipStr . " PB w/o SS";

											if ($inv["bottom_partialsheet"] == "1")
												$tipStr = $tipStr . " PB w/ SS";

											if ($inv["bottom_fullflap"] == "1")
												$tipStr = $tipStr . " FFB";

											if ($inv["bottom_interlocking"] == "1")
												$tipStr = $tipStr . " FB";

											if ($inv["bottom_tray"] == "1")
												$tipStr = $tipStr . " Tray Bottom";

											if ($inv["vents_no"] == "1")
												$tipStr = $tipStr . "";

											if ($inv["vents_yes"] == "1")
												$tipStr = $tipStr . ", Vents";
										?>

											<?php
											$bpallet_qty = 0;
											$boxes_per_trailer = 0;
											$qry = "select sku, bpallet_qty, boxes_per_trailer from loop_boxes where id=" . $loop["id"];
											db();
											$dt_view = db_query($qry);
											$sku = "";
											while ($sku_val = array_shift($dt_view)) {
												$sku = $sku_val['sku'];
												$bpallet_qty = $sku_val['bpallet_qty'];
												$boxes_per_trailer = $sku_val['boxes_per_trailer'];
											}

											$b2b_ulineDollar = round($inv["ulineDollar"]);
											$b2b_ulineCents = $inv["ulineCents"];
											$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
											$b2b_fob = "$ " . number_format($b2b_fob, 2);

											$b2b_costDollar = round($inv["costDollar"]);
											$b2b_costCents = $inv["costCents"];
											$b2b_cost = $b2b_costDollar + $b2b_costCents;
											$b2b_cost = "$ " . number_format($b2b_cost, 2);

											$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
											$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
											$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
											$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
											$sales_order_qty_new = 0;
											db();
											$dt_res_so_item1 = db_query($dt_so_item1);
											while ($so_item_row1 = array_shift($dt_res_so_item1)) {
												if ($so_item_row1["sumqty"] > 0) {
													$sales_order_qty_new = $so_item_row1["sumqty"];
												}
											}

											$to_show_data = "no";
											if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
												if (isset($filter_availability) == "truckloadonly") {
													if (($inv["after_actual_inventory"] >= $boxes_per_trailer) || ($inv["availability"] == 3) || ($inv["availability"] == 2.5)) {
														$to_show_data = "yes";
													}
												}
												if (isset($filter_availability) == "anyavailableboxes") {
													if ($inv["after_actual_inventory"] > 0) {
														$to_show_data = "yes";
													}
												}
											} else {
												$to_show_data = "yes";
											}

											if ($to_show_data == "yes") {

												$b2b_status = "";
												db();
												$dt_res_so_item1 = db_query("select * from b2b_box_status where status_key='" . $inv["b2b_status"] . "'");
												while ($so_item_row1 = array_shift($dt_res_so_item1)) {
													$b2b_status = $so_item_row1["box_status"];
												}

												$top_head_flg_output = "yes";
												if ($top_head_flg == "no") {
													$top_head_flg = "yes";
											?>

													<tr align="middle">
														<td colspan="13" class="style24" style="height: 16px"><strong>Non-UCB Pallets Inventory</strong></td>
													</tr>
													<tr>
														<td colspan="13">
															<div id="div_noninv_pallets" name="div_noninv_pallets">
																<table cellSpacing="1" cellPadding="1" border="0" width="1200">
																	<tr vAlign="left">
																		<td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(2,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(2,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		<td bgColor="#e4e4e4" class="style12"><b>Last Month Quantity&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(3,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(3,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		<td bgColor="#e4e4e4" class="style12"><b>Availability&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(4,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(4,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		<td bgColor="#e4e4e4" class="style12"><b>B2B Status&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(16,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(16,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		<td bgColor="#e4e4e4" class="style12">
																			<font size=1><b>Account Owner&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(17,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(17,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																		</td>

																		<td bgColor="#e4e4e4" class="style12">
																			<font size=1><b>Supplier&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(5,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(5,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																		</td>

																		<td bgColor="#e4e4e4" class="style12">
																			<font size=1><b>Ship From&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(15,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(15,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																		</td>

																		<td bgColor="#e4e4e4" class="style12" width="100px;"><b>LxWxH&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(6,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(6,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																		</td>

																		<td bgColor="#e4e4e4" class="style12left"><b>Description&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(7,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(7,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																		</td>

																		<td bgColor="#e4e4e4" class="style12"><b>Per Pallet&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(9,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(9,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		<td bgColor="#e4e4e4" class="style12"><b>Per Trailer&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(10,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(10,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Min FOB&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(11,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(11,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Cost&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(12,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(12,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		<td bgColor="#e4e4e4" class="style12"><b>Update&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(13,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(13,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		<td bgColor="#e4e4e4" class="style12left"><b>Notes&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(14,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displaynonucbpallets(14,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>
																	</tr>

																	<tr vAlign="left">
																		<td colspan=15>
																		<?php } ?>
																	<tr vAlign="center">
																		<?php if ($sales_order_qty_new > 0) { ?>
																			<td bgColor="<?php echo $bg; ?>" class="style12">
																				<font color='blue' size=1>
																					<div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $inv["loops_id"]; ?>, <?php echo $inv["vendor_b2b_rescue"]; ?>)">
																						<u><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?></u>
																					</div>
																				</font>
																			</td>
																		<?php } else { ?>
																			<td bgColor="<?php echo $bg; ?>" class="style12">
																				<font size=1>
																					<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["after_actual_inventory"]; ?>
																				</font>
																			</td>
																		<?php } ?>

																		<td bgColor="<?php echo $bg; ?>" class="style12">
																			<font size=1>
																				<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $inv["lastmonthqty"]; ?></a>
																			</font>
																		</td>

																		<td bgColor="<?php echo $bg; ?>" class="style12">
																			<?php if ($inv["availability"] == "3") echo "<b>"; ?>
																			<?php if ($inv["availability"] == "3") echo "Available Now & Urgent"; ?>
																			<?php if ($inv["availability"] == "2") echo "Available Now"; ?>
																			<?php if ($inv["availability"] == "1") echo "Available Soon"; ?>
																			<?php if ($inv["availability"] == "2.5") echo "Available >= 1TL"; ?>
																			<?php if ($inv["availability"] == "2.15") echo "Available < 1TL"; ?>
																			<?php if ($inv["availability"] == "-1") echo "Presell"; ?>
																			<?php if ($inv["availability"] == "-2") echo "Active by Unavailable"; ?>
																			<?php if ($inv["availability"] == "-3") echo "Potential"; ?>
																			<?php if ($inv["availability"] == "-3.5") echo "Check Loops"; ?>
																		</td>

																		<td bgColor="<?php echo $bg; ?>" class="style12"><?php echo $b2b_status; ?></td>

																		<td bgColor="<?php echo $bg; ?>" class="style12left">
																			<font size=1><?php echo $ownername; ?></font>
																		</td>

																		<td bgColor="<?php echo $bg; ?>" class="style12left">
																			<font size=1>
																				<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><?php echo $vendor_name; ?></a>
																			</font>
																		</td>
																		<td bgColor="<?php echo $bg; ?>" class="style12left">
																			<font size=1>
																				<?php echo $inv["location_city"] . ", " . $inv["location_state"] . " " . $inv["location_zip"]; ?>
																			</font>
																		</td>

																		<td bgColor="<?php echo $bg; ?>" class="style12">
																			<font size=1><?php echo $inv["L"] . " x " . $inv["W"] . " x " . $inv["D"]; ?></font>
																		</td>
																		<td bgColor="<?php echo $bg; ?>" class="style12left">
																			<font size=1>
																				<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["availability"] == "3") echo "<b>"; ?><a target="_blank" href='manage_box_b2bloop.php?id=<?php echo $loop["id"]; ?>&proc=View' id='box_div<?php echo $loop["id"]; ?>'><?php echo $inv["description"]; ?></a></font>
																		</td>

																		<td bgColor="<?php echo $bg; ?>" class="style12">
																			<font size=1><?php echo $bpallet_qty; ?></font>
																		</td>
																		<td bgColor="<?php echo $bg; ?>" class="style12">
																			<font size=1><?php echo number_format($boxes_per_trailer, 0); ?></font>
																		</td>
																		<td bgColor="<?php echo $bg; ?>" class="style12">
																			<font size=1><?php echo $b2b_fob; ?></font>
																		</td>
																		<td bgColor="<?php echo $bg; ?>" class="style12">
																			<font size=1><?php echo $b2b_cost; ?></font>
																		</td>

																		<td bgColor="<?php echo $bg; ?>" class="style12left">
																			<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($inv["DT"] != "") echo timestamp_to_date($inv["DT"]); ?>
																		</td>
																		<td bgColor="<?php echo $bg; ?>" class="style12left">
																			<?php if ($inv["availability"] == "3") echo "<b>"; ?><?php if ($loop["id"] < 0) { ?><a href='javascript:void();' id='box_div<?php echo $loop["id"]; ?>' onclick="displayboxdata(<?php echo $loop["id"]; ?>);"><?php echo $inv["N"]; ?></a><?php } else { ?><?php echo $inv["N"]; ?></a>
																		<?php } ?></td>

																	<tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
																		<td>&nbsp;</td>
																		<td>&nbsp;</td>
																		<td colspan="14" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
																			<div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
																		</td>
																	</tr>
																	<?php
																	$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'><font size=1 >";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= $inv['actual_inventory'] . "</font></td>";

																	if ($sales_order_qty_new > 0) {
																		$inv_row .= "<td bgColor='$bg' class='style12' ><font color='blue' size=1><div onclick='display_orders_data(" . $count_arry . "," . $inv['loops_id'] . "," . $inv['vendor_b2b_rescue'] . ")'><u>";
																		if ($inv['availability'] == '3') $inv_row .= "<b>";
																		$inv_row .= $inv['after_actual_inventory'] . "</u></div></font></td>";
																	} else {
																		$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
																		if ($inv['availability'] == '3') $inv_row .= "<b>";
																		$inv_row .= $inv['after_actual_inventory'] . "</font></td>";
																	}

																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= $inv['expected_loads_per_mo'] . "</font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12' >";
																	if ($inv['availability'] == '3') $inv_row .= '<b>';
																	if ($inv['availability'] == '3') $inv_row .= 'Available Now & Urgent';
																	if ($inv['availability'] == '2') $inv_row .= 'Available Now';
																	if ($inv['availability'] == '1') $inv_row .= 'Available Soon';
																	if ($inv['availability'] == '2.5') $inv_row .= 'Available >= 1TL';
																	if ($inv['availability'] == '2.15') $inv_row .= 'Available < 1TL';
																	if ($inv['availability'] == '-1') $inv_row .= 'Presell';
																	if ($inv['availability'] == '-2') $inv_row .= 'Active by Unavailable';
																	if ($inv['availability'] == '-3') $inv_row .= 'Potential';
																	if ($inv['availability'] == '-3.5') $inv_row .= 'Check Loops';
																	$inv_row .= "</td>  ";
																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
																	$inv_row .= "<b>";
																	$inv_row .= $ownername . "</a></font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= $vendor_name . "</a></font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>" . $inv['location_city'] . ", " . $inv['location_state'] . " " . $inv['location_zip'] . "</font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $inv['L'] . " x " . $inv['W'] . " x " . $inv['D'] . "</font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $loop['id'] . "&proc=View' id='box_div" . $loop['id'] . "' >" . $inv['description'] . "</a></font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' ><font size=1>";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	$inv_row .= "<a target='_blank' href='boxpics/" . $loop['flyer'] . "' id='box_fly_div" . $loop['id'] . "' >" . $sku . "</a></font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . $bpallet_qty . "</font></td>";
																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>" . number_format($boxes_per_trailer, 0) . "</font></td>";
																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_fob . "</font></td>";
																	$inv_row .= "<td bgColor='$bg' class='style12' ><font size=1>$" . $b2b_cost . "</font></td>";

																	$inv_row .= "<td bgColor='$bg' class='style12left' >";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	if ($inv['DT'] != '') $inv_row .= timestamp_to_date($inv['DT']) . "</td>";
																	$inv_row .= "<td bgColor='$bg' class='style12left' >";
																	if ($inv['availability'] == '3') $inv_row .= "<b>";
																	if ($loop['id'] < 0) {
																		$inv_row .= "<a href='javascript:void();' id='box_div" . $loop['id'] . "' onclick='displayboxdata(" . $loop['id'] . ")'>" . $inv['N'] . "</a>";
																	} else {
																		$inv_row .= $inv['N'] . "</a>";
																	}
																	$inv_row .= "</td>";
																	$inv_row .= "</tr>";

																	if ($inv["after_actual_inventory"] > 0) {
																		if ($inv["availability"] == "3") {
																			if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																				//$no_of_urgent_load = $no_of_urgent_load + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																				//$no_of_urgent_load_str .= $inv_row;
																			}
																		}
																	}
																	//&& ($boxes_per_trailer >= $inv["actual_inventory"])
																	if ($inv["actual_inventory"] > 0) {
																		if (floor($inv["actual_inventory"] / $boxes_per_trailer) > 0) {
																			$no_of_full_load = $no_of_full_load + floor($inv["actual_inventory"] / $boxes_per_trailer);
																			$no_of_full_load_str .= $inv_row;
																		}

																		$tot_value_full_load = $tot_value_full_load + ((floor($inv["actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * (float)$b2b_fob);
																	}

																	if ($inv["actual_inventory"] > 0) {
																		$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($inv["actual_inventory"] * $b2b_fob);
																		$no_of_full_load_str_ucb_inv_str .= $inv_row;
																	}

																	if ($inv["after_actual_inventory"] > 0) {
																		$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($inv["after_actual_inventory"] * $b2b_fob);
																		$no_of_full_load_str_ucb_inv_av_str .= $inv_row;
																	}

																	//&& ($boxes_per_trailer >= $inv["after_actual_inventory"])
																	if (!($inv["availability"] == "-3" || $inv["availability"] == "1" || $inv["availability"] == "-1")) {
																		if (($inv["after_actual_inventory"] > 0) && ($boxes_per_trailer > 0)) {
																			if (floor($inv["after_actual_inventory"] / $boxes_per_trailer) > 0) {
																				$tot_load_available = $tot_load_available + floor($inv["after_actual_inventory"] / $boxes_per_trailer);
																				//$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * $b2b_fob;
																				$tot_load_available_val = $tot_load_available_val + (floor($inv["after_actual_inventory"] / $boxes_per_trailer)) * $boxes_per_trailer * (float)$b2b_fob;
																				$tot_load_available_str .= $inv_row;
																			}
																		}
																	}

																	if ($inv["actual_inventory"] < 0) {
																		$no_of_red_on_page = $no_of_red_on_page + 1;
																		$no_of_red_on_page_str .= $inv_row;
																	}

																	$notes_date = new DateTime($inv["DT"]);
																	$curr_date = new DateTime();

																	$notes_date_diff = $curr_date->diff($notes_date)->days;
																	if ($notes_date_diff > 7) {
																		$no_of_inv_item_note_date = $no_of_inv_item_note_date + 1;
																		$no_of_inv_item_note_date_str .= $inv_row;
																	}
																	?>

																<?php
																$count_arry = $count_arry + 1;
															}
														}

														if ($top_head_flg_output == "yes") {
																?>
																</table>
															</div>
														</td>
													</tr>
												<?php } ?>

												</table>

												<div id="tempval_focus" name="tempval_focus"></div>
												<div id="tempval1" name="tempval1">
												</div>

												<div id="tempval" name="tempval">

													<?php

													$inv_row = "</table><br><br><table cellSpacing='1' cellPadding='1' border='0' width='1200' >
													<tr vAlign='center'>
													
														<td bgColor='#e4e4e4' class='style12'><b>Actual</b></td>  
														
														<td bgColor='#e4e4e4' class='style12'><b>After PO</b></td>  
														
														<td bgColor='#e4e4e4' class='style12'><b>Last Month Qty</b></td>  

														<td bgColor='#e4e4e4' class='style12'><b>Warehouse</b></td>  
														
														<td bgColor='#e4e4e4' class='style12' width='100px'><font size=1><b>Supplier</b></font></td>	  

														<td bgColor='#e4e4e4' class='style12' width='100px'><font size=1><b>Ship From</b></font></td>	  

														<td bgColor='#e4e4e4' class='style12' width='100px;'><b>Type</b></font></td>	 

														<td bgColor='#e4e4e4' class='style12left' ><b>LxWxH</b></font></td>	  

														<td bgColor='#e4e4e4' class='style12' width='150px;'><b>Description</b></font></td>	 

														<td bgColor='#e4e4e4' class='style12' ><b>SKU</b></td>

														<td bgColor='#e4e4e4' class='style12' width='70px'><b>Per Pallet</b></td>

														<td bgColor='#e4e4e4' class='style12' width='70px;'><b>Per Trailer</b></td>
														
														<td bgColor='#e4e4e4' class='style12' ><b>Min FOB</b></td>

														<td bgColor='#e4e4e4' class='style12' width='70px'><b>Cost</b></td>

													</tr>";
													$no_of_full_load_str .= $inv_row;
													$no_of_urgent_load_str .= $inv_row;
													$tot_load_available_str .= $inv_row;
													$no_of_red_on_page_str .= $inv_row;

													$bg = "#f4f4f4";
													$style12_val = "style12";
													$style12left = "style12left";
													?>
													<table cellSpacing="1" cellPadding="1" border="0" width="1200">
														<tr align="middle">
															<td colspan="13" class="style24" style="height: 16px"><strong>UCB Owned Inventory</strong>
															</td>
														</tr>
														<tr>
															<td colspan="13">
																<div id="div_ucbinv" name="div_ucbinv">
																	<table cellSpacing="1" cellPadding="1" border="0" width="1200">
																		<tr vAlign="center">

																			<td bgColor="#e4e4e4" class="style12"><b>Actual&nbsp;<a href="javascript:void();" onclick="displayucbinv(1,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(1,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																			<td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="javascript:void();" onclick="displayucbinv(2,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(2,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																			<td bgColor="#e4e4e4" class="style12"><b>Loads/Mo&nbsp;<a href="javascript:void();" onclick="displayucbinv(3,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(3,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																			<td bgColor="#e4e4e4" class="style12"><b>Warehouse&nbsp;<a href="javascript:void();" onclick="displayucbinv(4,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(4,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																			<td bgColor="#e4e4e4" class="style12" width="100px">
																				<font size=1><b>Supplier&nbsp;<a href="javascript:void();" onclick="displayucbinv(5,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(5,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																			</td>

																			<td bgColor="#e4e4e4" class="style12" width="100px">
																				<font size=1><b>Ship From&nbsp;<a href="javascript:void();" onclick="displayucbinv(15,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(15,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																			</td>

																			<td bgColor="#e4e4e4" class="style12" width="100px;"><b>Worked as a kit box?&nbsp;<a href="javascript:void();" onclick="displayucbinv(16,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(16,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																			</td>

																			<td bgColor="#e4e4e4" class="style12" width="100px;"><b>Type&nbsp;<a href="javascript:void();" onclick="displayucbinv(6,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(6,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																			</td>

																			<td bgColor="#e4e4e4" class="style12left"><b>LxWxH&nbsp;<a href="javascript:void();" onclick="displayucbinv(7,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(7,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																			</td>

																			<td bgColor="#e4e4e4" class="style12" width="150px;"><b>Description&nbsp;<a href="javascript:void();" onclick="displayucbinv(8,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(8,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
																			</td>

																			<td bgColor="#e4e4e4" class="style12" width="70px"><b>Per Pallet&nbsp;<a href="javascript:void();" onclick="displayucbinv(10,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(10,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																			<td bgColor="#e4e4e4" class="style12" width="70px;"><b>Per Trailer&nbsp;<a href="javascript:void();" onclick="displayucbinv(11,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(11,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																			<td bgColor="#e4e4e4" class="style12"><b>Min FOB&nbsp;<a href="javascript:void();" onclick="displayucbinv(12,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(12,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																			<td bgColor="#e4e4e4" class="style12" width="70px"><b>Cost&nbsp;<a href="javascript:void();" onclick="displayucbinv(13,1);"><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="javascript:void();" onclick="displayucbinv(13,2);"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></td>

																		</tr>
																		<?php

																		$dt_view_qry = "SELECT * from tmp_inventory_list_set2 $main_new_where_condition_ucbq order by warehouse, type_ofbox, tmp_inventory_list_set2.Description";
																		//echo $dt_view_qry . "<br>";
																		db_b2b();
																		$dt_view_res = db_query($dt_view_qry);

																		// $num_rows = tep_db_num_rows($dt_view_res);
																		// if ($num_rows > 0) 
																		$preordercnt = 1;
																		$newflg = "no";
																		$tmpwarenm = "";
																		$tmp_noofpallet = 0;
																		$ware_house_boxdraw = "";
																		while ($dt_view_row = array_shift($dt_view_res)) {

																			$location_city = "";
																			$location_state = "";
																			$location_zip = "";
																			$loops_id = 0;
																			$vendor_b2b_rescue = 0;
																			$bwall = "";
																			$vendor_id = 0;
																			$expected_loads_per_mo = "";
																			$box_length = "";
																			$box_width = "";
																			$box_height = "";
																			$vendor_name = "";
																			//$dt_view_qry_1 = "SELECT loops_id, location_city, location_state, location_zip, bwall, expected_loads_per_mo, vendor, lengthInch, widthInch, depthInch, vendor_b2b_rescue from inventory where ID = " . $dt_view_row["inv_id"];
																			$dt_view_qry_1 = "SELECT loops_id, location_city, location_state, location_zip, bwall, expected_loads_per_mo, vendor, lengthInch, widthInch, depthInch, vendor_b2b_rescue from inventory where loops_id = " . $dt_view_row["trans_id"];
																			db_b2b();
																			$dt_view_res_1 = db_query($dt_view_qry_1);
																			while ($dt_view_row_1 = array_shift($dt_view_res_1)) {
																				$location_city = $dt_view_row_1["location_city"];
																				$location_state = $dt_view_row_1["location_state"];
																				$location_zip = $dt_view_row_1["location_zip"];
																				$loops_id = $dt_view_row_1["loops_id"];
																				$vendor_b2b_rescue = $dt_view_row_1["vendor_b2b_rescue"];
																				$bwall = $dt_view_row_1["bwall"];
																				$vendor_id = $dt_view_row_1["vendor"];

																				$expected_loads_per_mo = $dt_view_row_1["expected_loads_per_mo"];

																				$box_length = $dt_view_row_1["lengthInch"];
																				$box_width = $dt_view_row_1["widthInch"];
																				$box_height = $dt_view_row_1["depthInch"];
																			}
																			//account owner

																			if ($vendor_b2b_rescue > 0) {

																				$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
																				db();
																				$query = db_query($q1);
																				while ($fetch = array_shift($query)) {
																					$vendor_name = getnickname($fetch["company_name"], $fetch["b2bid"]);

																					$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.ID=" . $fetch["b2bid"];
																					db_b2b();
																					$comres = db_query($comqry);
																					while ($comrow = array_shift($comres)) {
																						$ownername = $comrow["initials"];
																					}
																				}
																			} else {
																				$vendor_b2b_rescue = $vendor_id;
																				$q1 = "SELECT * FROM vendors where id = $vendor_b2b_rescue";
																				db_b2b();
																				$query = db_query($q1);
																				while ($fetch = array_shift($query)) {
																					$vendor_name = $fetch["Name"];

																					$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
																					db_b2b();
																					$comres = db_query($comqry);
																					while ($comrow = array_shift($comres)) {
																						$ownername = $comrow["initials"];
																					}
																				}
																			}
																			//
																			$actual_qty_calculated = "";
																			$dt_view_qry_k = "SELECT work_as_kit_box, actual_qty_calculated from loop_boxes where id = " . $dt_view_row["trans_id"];
																			db();
																			$dt_view_res_k = db_query($dt_view_qry_k);
																			$work_as_kit_box = "";
																			while ($dt_view_row_k = array_shift($dt_view_res_k)) {
																				$work_as_kit_box = $dt_view_row_k["work_as_kit_box"];
																				$actual_qty_calculated = $dt_view_row_k["actual_qty_calculated"];
																			}

																			if ($newflg == "no") {
																				$newflg = "yes";
																		?><tr>
																					<td colspan="13" align="center">Sync on:
																						<?php echo timeAgo($dt_view_row["updated_on"]); ?></td>
																				</tr><?php
																					}

																					$b2b_fob = $dt_view_row["min_fob"];
																					$b2b_cost = $dt_view_row["cost"];
																					//$vendor_name= $dt_view_row["vendor"];

																					$sales_order_qty = $dt_view_row["sales_order_qty"];

																					if ($actual_qty_calculated != 0 or $actual_qty_calculated - $sales_order_qty != 0) {
																						$lastmonth_val = $expected_loads_per_mo;

																						$reccnt = 0;
																						if ($sales_order_qty > 0) {
																							$reccnt = $sales_order_qty;
																						}

																						$preorder_txt = "";
																						$preorder_txt2 = "";

																						if ($reccnt > 0) {
																							$preorder_txt = "<u>";
																							$preorder_txt2 = "</u>";
																						}

																						if (($actual_qty_calculated >= $dt_view_row["per_trailer"]) && ($dt_view_row["per_trailer"] > 0)) {
																							$bg = "yellow";
																						}

																						$pallet_val = 0;
																						$pallet_val_afterpo = 0;
																						$actual_po = $actual_qty_calculated - $sales_order_qty;

																						if ($dt_view_row["per_pallet"] > 0) {
																							$pallet_val = number_format($actual_qty_calculated / $dt_view_row["per_pallet"], 1, '.', '');
																							$pallet_val_afterpo = number_format($actual_po / $dt_view_row["per_pallet"], 1, '.', '');
																						}

																						$pallet_space_per = "";

																						if ($pallet_val > 0) {
																							$tmppos_1 = strpos($pallet_val, '.');
																							if ($tmppos_1 != false) {
																								if (intval(substr($pallet_val, strpos($pallet_val, '.') + 1, 1)) > 0) {
																									$pallet_val_temp = $pallet_val;
																									$pallet_val = " (" . $pallet_val_temp . ")";
																								} else {
																									$pallet_val_format = number_format((float)$pallet_val, 0);
																									$pallet_val = " (" . $pallet_val_format . ")";
																								}
																							} else {
																								$pallet_val_format = number_format((float)$pallet_val, 0);
																								$pallet_val = " (" . $pallet_val_format . ")";
																							}
																						} else {
																							$pallet_val = "";
																						}

																						if ($pallet_val_afterpo > 0) {
																							$tmppos_1 = strpos($pallet_val_afterpo, '.');
																							if ($tmppos_1 != false) {
																								if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo, '.') + 1, 1)) > 0) {
																									$pallet_val_afterpo_temp = $pallet_val_afterpo;
																									$pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
																								} else {
																									$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo, 0);
																									$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
																								}
																							} else {
																								$pallet_val_afterpo_format = number_format((float)$pallet_val_afterpo, 0);
																								$pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
																							}
																						} else {
																							$pallet_val_afterpo = "";
																						}

																						$pallet_space_per = "";

																						$to_show_data = "no";
																						if (isset($filter_availability) != "All" && isset($filter_availability) != "") {
																							if (isset($filter_availability) == "truckloadonly") {
																								if ($actual_po >= $dt_view_row["per_trailer"]) {
																									$to_show_data = "yes";
																								}
																							}
																							if (isset($filter_availability) == "anyavailableboxes") {
																								if ($actual_po > 0) {
																									$to_show_data = "yes";
																								}
																							}
																						} else {
																							$to_show_data = "yes";
																						}

																						if ($to_show_data == "yes") {

																							if ($ware_house_boxdraw != $dt_view_row["warehouse"]) {
																						?><tr>
																							<td colspan="13">
																								<hr style="  border: 0; height: 1px;background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));">
																							</td>
																						</tr><?php
																							}
																							$ware_house_boxdraw = $dt_view_row["warehouse"];

																							$dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
																							$dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
																							$dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
																							$dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $loops_id . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
																							$sales_order_qty_new = 0;
																							db();
																							$dt_res_so_item1 = db_query($dt_so_item1);
																							while ($so_item_row1 = array_shift($dt_res_so_item1)) {
																								if ($so_item_row1["sumqty"] > 0) {
																									$sales_order_qty_new = $so_item_row1["sumqty"];
																								}
																							}

																								?>
																					<tr vAlign="center">
																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							<a href='javascript:void();' id='actual_pos<?php echo $dt_view_row["trans_id"]; ?>' onclick="displayactualpallet(<?php echo $dt_view_row["trans_id"]; ?>);">
																								<?php
																								if ($actual_qty_calculated < 0) { ?>
																									<font color="red"><?php echo $actual_qty_calculated . $pallet_val; ?></font>
																								<?php 	 } else { ?>
																									<font color="green"><?php echo $actual_qty_calculated . $pallet_val; ?></font>
																								<?php } ?>
																							</a>
																						</td>
																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							<?php
																							if ($actual_po < 0) { ?>
																								<div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $loops_id; ?>, <?php echo $vendor_b2b_rescue; ?>)" style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial">
																									<font color="blue"><?php echo $preorder_txt; ?><?php
																																					echo $actual_po . $pallet_val_afterpo; ?><?php echo $preorder_txt2; ?></font>
																								</div>
																							<?php 	} else { ?>
																								<div onclick="display_orders_data(<?php echo $count_arry; ?>, <?php echo $loops_id; ?>, <?php echo $vendor_b2b_rescue; ?>)" style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial">
																									<font color="green"><?php echo $preorder_txt; ?><?php
																																					echo $actual_po . $pallet_val_afterpo;
																																					?></font><?php echo $preorder_txt2; ?>
																								</div> <?php } ?>
																						</td>
																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							<?php echo $expected_loads_per_mo; ?></td>
																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							<?php echo $dt_view_row["warehouse"]; ?></td>
																						<td bgColor="<?php echo $bg; ?>" class="style12left"><?php echo $vendor_name; ?></td>
																						<td bgColor="<?php echo $bg; ?>" class="style12left">
																							<?php echo $location_city . ", " . $location_state . " " . $location_zip; ?></td>

																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							<?php echo $work_as_kit_box; ?></td>

																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							<?php echo $dt_view_row["type_ofbox"]; ?></td>

																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							<?php echo $dt_view_row["LWH"]; ?></td>
																						<td bgColor="<?php echo $bg; ?>" class="style12left"><a target="_blank" href='manage_box_b2bloop.php?id=<?php echo $dt_view_row["trans_id"]; ?>&proc=View' id='box_div_main<?php echo $dt_view_row["trans_id"]; ?>'><?php echo $dt_view_row["Description"]; ?></a>
																						</td>

																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							<?php echo $dt_view_row["per_pallet"]; ?></td>
																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							<?php echo number_format($dt_view_row["per_trailer"], 0); ?></td>
																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							$<?php echo  number_format($dt_view_row["min_fob"], 2); ?></td>
																						<td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
																							$<?php echo  number_format($dt_view_row["cost"], 2); ?></td>

																						<?php
																							$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'>";
																							$inv_row .= "<a href='javascript:void();' id='actual_pos" . $dt_view_row["trans_id"] . "' onclick='displayactualpallet(" . $dt_view_row["trans_id"] . ")'>";
																							//. $pallet_val
																							if ($actual_qty_calculated < 0) {
																								$inv_row .= "<font color='red'>" . $actual_qty_calculated . "</font>";
																							} else {
																								$inv_row .= "<font color='green'>" . $actual_qty_calculated . "</font>";
																							}
																							$inv_row .= "</a></td>";

																							$inv_row .= "<td bgColor='$bg' class='$style12_val' >";
																							if ($actual_po < 0) {
																								$inv_row .= "<div onclick='display_orders_data(" . $count_arry . "," . $loops_id . "," . $vendor_b2b_rescue . ")' style='FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial'><font color='blue'>" . $preorder_txt;
																								$inv_row .= $actual_po . $pallet_val_afterpo . $preorder_txt2 . "</font></div>";
																							} else {
																								$inv_row .= "<div onclick='display_orders_data(" . $count_arry . "," . $loops_id . "," . $vendor_b2b_rescue . ")' style='FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial'><font color='green'>" . $preorder_txt;
																								$inv_row .= $actual_po . $pallet_val_afterpo . "</font>" . $preorder_txt2 . "</div>";
																							}
																							$inv_row .= "</td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12_val'>" . $expected_loads_per_mo . "</td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12_val'>" . $dt_view_row["warehouse"] . "</td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12left'><font size=1>" . $vendor_name . "</font></td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12left'><font size=1>" . $location_city . ", " . $location_state . " " . $location_zip . "</font></td>";

																							$inv_row .= "<td bgColor='$bg' class='$style12_val' >" . $dt_view_row["type_ofbox"] . "</td>";

																							$inv_row .= "<td bgColor='$bg' class='$style12_val'>" . $dt_view_row["LWH"] . "</td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12left' ><a target='_blank' href='manage_box_b2bloop.php?id=" . $dt_view_row["trans_id"] . "&proc=View' id='box_div_main" . $dt_view_row["trans_id"] . "'>" . $dt_view_row["Description"] . "</a></td>";
																							$inv_row .= "<td bgColor='$bg' class='$style12left' ><a target='_blank' href='boxpics/" . $dt_view_row["flyer"] . "' id='box_fly_div_main" . $dt_view_row["trans_id"] . "'>" . $dt_view_row["SKU"] . "</a></td>";

																							$inv_row .= " <td bgColor='$bg' class='$style12_val' >" . $dt_view_row["per_pallet"] . "</td>";
																							$inv_row .= " <td bgColor='$bg' class='$style12_val' >" . number_format($dt_view_row["per_trailer"], 0) . "</td>";
																							$inv_row .= " <td bgColor='$bg' class='$style12_val' >$" .  number_format($dt_view_row["min_fob"], 2) . "</td>";
																							$inv_row .= "</tr>";
																							//$inv_row .= " <td bgColor='$bg' class='$style12_val' >" .  $actual_po . "</td>";

																							if ($dt_view_row["type_ofbox"] != "Recycling") {
																								//&& $dt_view_row["per_trailer"] >= $actual_qty_calculated
																								if ($actual_qty_calculated > 0) {

																									if (floor($actual_qty_calculated / $dt_view_row["per_trailer"]) > 0) {
																										$no_of_full_load = $no_of_full_load + floor($actual_qty_calculated / $dt_view_row["per_trailer"]);
																										$no_of_full_load_str .= $inv_row;
																										$tot_value_full_load = $tot_value_full_load + ((floor($actual_qty_calculated / $dt_view_row["per_trailer"])) * $dt_view_row["per_trailer"] * $dt_view_row["min_fob"]);
																									}
																								}

																								//&& ($dt_view_row["per_trailer"] >= $actual_po)

																								if (!($dt_view_row["type_ofbox"] == "LoopShipping" || $dt_view_row["type_ofbox"] == "Loop")) {
																									if (($actual_po > 0) && ($dt_view_row["per_trailer"] > 0)) {
																										if (floor($actual_po / $dt_view_row["per_trailer"]) > 0) {
																											$tot_load_available = $tot_load_available + floor($actual_po / $dt_view_row["per_trailer"]);
																											$tot_load_available_val = $tot_load_available_val + (floor($actual_po / $dt_view_row["per_trailer"])) * $dt_view_row["per_trailer"] * $dt_view_row["min_fob"];

																											$tot_load_available_str .= $inv_row;
																										}
																									}
																								}

																								if ($actual_qty_calculated > 0) {
																									$no_of_full_load_str_ucb_inv = $no_of_full_load_str_ucb_inv + ($actual_qty_calculated * $dt_view_row["min_fob"]);
																									$no_of_full_load_str_ucb_inv_str .= $inv_row;
																								}

																								if ($actual_po > 0) {
																									$no_of_full_load_str_ucb_inv_av = $no_of_full_load_str_ucb_inv_av + ($actual_po * $dt_view_row["min_fob"]);
																									$no_of_full_load_str_ucb_inv_av_str .= $inv_row . " <td bgColor='$bg' class='$style12_val' >" .  $no_of_full_load_str_ucb_inv_av . "</td>";
																								}
																							}

																							if ($actual_qty_calculated < 0) {
																								$no_of_red_on_page = $no_of_red_on_page + 1;
																								$no_of_red_on_page_str .= $inv_row;
																							}

																						?>

																					</tr>
																					<?php
																							if ($x == 0) {
																								$x = 1;
																								$bg = "#e4e4e4";
																							} else {
																								$x = 0;
																								$bg = "#f4f4f4";
																							}

																							if ($reccnt > 0) { ?>
																						<tr id='inventory_preord_org_top_<?php echo $preordercnt; ?>' align="middle" style="display:none;">
																							<td>&nbsp;</td>
																							<td colspan="14" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
																								<div id="inventory_preord_org_middle_div_<?php echo $preordercnt; ?>"></div>
																							</td>
																						</tr>

																					<?php 	 }
																					?>

																					<tr id='inventory_preord_top_<?php echo $count_arry; ?>' align="middle" style="display:none;">
																						<td>&nbsp;</td>
																						<td>&nbsp;</td>
																						<td colspan="14" style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
																							<div id="inventory_preord_middle_div_<?php echo $count_arry; ?>"></div>
																						</td>
																					</tr>

																					<?php if ($reccnt > 0) { ?>
																		<?php
																								$preordercnt = $preordercnt + 1;
																							}

																							$count_arry = $count_arry + 1;
																						}
																					}
																				}
																		?>
																	</table>
																	<div id="inv_summ_div"></div>
																	<table cellspacing="1" cellpadding="1" border="0">
																		<tr>
																			<td class="style12_new_top" colspan="2">Inventory Summary
																				<input type="hidden" id="no_of_urgent_load_str" name="no_of_urgent_load_str" value="<?php echo str_replace('"', "'", $no_of_urgent_load_str); ?>" />
																				<input type="hidden" id="no_of_full_load_str" name="no_of_full_load_str" value="<?php echo str_replace('"', "'", $no_of_full_load_str); ?>" />
																				<input type="hidden" id="no_of_full_load_str_ucb_inv_str" name="no_of_full_load_str_ucb_inv_str" value="<?php echo str_replace('"', "'", $no_of_full_load_str_ucb_inv_str); ?>" />
																				<input type="hidden" id="no_of_full_load_str_ucb_inv_av_str" name="no_of_full_load_str_ucb_inv_av_str" value="<?php echo str_replace('"', "'", $no_of_full_load_str_ucb_inv_av_str); ?>" />
																				<input type="hidden" id="tot_load_available_str" name="tot_load_available_str" value="<?php echo str_replace('"', "'", $tot_load_available_str); ?>" />
																				<input type="hidden" id="no_of_red_on_page_str" name="no_of_red_on_page_str" value="<?php echo str_replace('"', "'", $no_of_red_on_page_str); ?>" />
																			</td>
																		</tr>

																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4"># of Urgent Loads</td>
																			<td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)" onclick="return inv_summary('no_of_urgent_load_str', 1);"><?php echo $no_of_urgent_load_val; ?></a>
																			</td>
																		</tr>
																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4">Total # of Full Loads at UCB</td>
																			<td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)" onclick="return inv_summary('no_of_full_load_str', 1);"><?php echo $no_of_full_load; ?></a>
																			</td>
																		</tr>
																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4">Total Est. Value of Full Loads at UCB</td>
																			<td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)" onclick="return inv_summary('no_of_full_load_str', 1);">$<?php echo number_format($tot_value_full_load, 2); ?></a>
																			</td>
																		</tr>
																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4">Total Est. Value of ALL UCB Inventory</td>
																			<td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)" onclick="return inv_summary('no_of_full_load_str_ucb_inv_str', 1);">$<?php echo number_format($no_of_full_load_str_ucb_inv, 2); ?></a>
																			</td>
																		</tr>
																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4">Total # of Full Loads Available</td>
																			<td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)" onclick="return inv_summary('tot_load_available_str', 1);"><?php echo $tot_load_available; ?></a>
																			</td>
																		</tr>
																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4">Total Est. Value of Full Loads Available at
																				UCB</td>
																			<td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)" onclick="return inv_summary('tot_load_available_str', 1);">$<?php echo number_format($tot_load_available_val, 2); ?></a>
																			</td>
																		</tr>
																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4">Total Est. Value of ALL UCB Available
																				Inventory</td>
																			<td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)" onclick="return inv_summary('no_of_full_load_str_ucb_inv_av_str', 1);">$<?php echo number_format($no_of_full_load_str_ucb_inv_av, 2); ?></a>
																			</td>
																		</tr>

																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4"># of Red Numbers on Page</td>
																			<td class="style12_new2" bgcolor="#f4f4f4"><a href="javascript:void(0)" onclick="return inv_summary('no_of_red_on_page_str', 1);"><?php echo $no_of_red_on_page; ?></a>
																			</td>
																		</tr>
																		<?php
																		$inv_row = "<tr vAlign='center'><td bgColor='$bg' colspan=2 class='style12'>";
																		$inv_row .= "Transactions list where No Ops Delivery Date and which are not Shipped</td>";
																		$inv_row .= "</tr>";
																		$no_of_trans_no_delv_date_str .= $inv_row;

																		$Gn_corporate_list = "504, 1076, 1073, 532, 1074, 1089, ";
																		$Gn_corporate_list .= "3287,718,787,2019,447,1073,1076,504,1639,532,738,694,3002,1072,1074,1077,1089,1238,616,2114,3126,2901,2902,2898,2899,2900,3010,2915,2904,2917,2905,2906,2907,2908,2909,2910,2912,2913,2914,3003,3129,";
																		$Gn_corporate_list .= "616, 718, 1089, 2596, 694, 1073, ";
																		$Gn_corporate_list .= " 3287,718,787,2019,447,1073,1076,504,1639,532,738,694,3002,1072,1074,1077,1089,1238,616,2114,3126,2901,2902,2898,2899,2900,3010,2915,2904,2917,2905,2906,2907,2908,2909,2910,2912,2913,2914,3003,3129";

																		$dt_so_item1 = "SELECT loop_transaction_buyer.id as I, loop_transaction_buyer.*, loop_warehouse.b2bid, loop_warehouse.company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id ";
																		$dt_so_item1 .= " where loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.recycling_flg = 0 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.warehouse_id not in ($Gn_corporate_list) and (loop_transaction_buyer.ops_delivery_date = '' or loop_transaction_buyer.ops_delivery_date is null)";
																		//echo $dt_so_item1;
																		db();
																		$dt_res_so_item1 = db_query($dt_so_item1);
																		while ($so_item_row1 = array_shift($dt_res_so_item1)) {
																			$no_of_trans_no_delv_date = $no_of_trans_no_delv_date + 1;

																			$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'>";
																			$inv_row .= $so_item_row1["I"] . "</td>";
																			$inv_row .= "<td bgColor='$bg' class='style12'>";
																			$inv_row .= "<a target='_blank' href='viewCompany.php?ID=" . $so_item_row1["b2bid"] . "&show=transactions&warehouse_id=" . $so_item_row1["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $so_item_row1["warehouse_id"] . "&rec_id=" . $so_item_row1["I"] . "&display=buyer_view'>" . getnickname($so_item_row1["company_name"], $so_item_row1["b2bid"]);
																			$inv_row .= "</a></td></tr>";

																			$no_of_trans_no_delv_date_str .= $inv_row;
																		}
																		?>
																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4"># of transactions, No Ops Delivery Date</td>
																			<td class="style12_new2" bgcolor="#f4f4f4">
																				<input type="hidden" id="no_of_trans_no_delv_date_str" name="no_of_trans_no_delv_date_str" value="<?php echo $no_of_trans_no_delv_date_str; ?>" />
																				<a href="javascript:void(0)" onclick="return inv_summary('no_of_trans_no_delv_date_str', 0);"><?php echo $no_of_trans_no_delv_date; ?></a>
																			</td>
																		</tr>
																		<?php
																		$inv_row = "<tr vAlign='center'><td bgColor='$bg' colspan=2 class='style12'>";
																		$inv_row .= "Transactions list where Planned Delivery Date Passed and which are not Shipped</td>";
																		$inv_row .= "</tr>";
																		$no_of_trans_plann_del_pass_str .= $inv_row;

																		$dt_so_item1 = "SELECT loop_transaction_buyer.id as I, loop_transaction_buyer.*, loop_warehouse.b2bid, loop_warehouse.company_name FROM loop_transaction_buyer inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id ";
																		$dt_so_item1 .= " where loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.recycling_flg = 0 and loop_transaction_buyer.shipped = 0 and loop_transaction_buyer.warehouse_id not in ($Gn_corporate_list) and po_delivery_dt < '" . date("Y-m-d") . "'";
																		db();
																		$dt_res_so_item1 = db_query($dt_so_item1);
																		while ($so_item_row1 = array_shift($dt_res_so_item1)) {
																			$no_of_trans_plann_del_pass = $no_of_trans_plann_del_pass + 1;

																			$inv_row = "<tr vAlign='center'><td bgColor='$bg' class='style12'>";
																			$inv_row .= $so_item_row1["I"] . "</td>";
																			$inv_row .= "<td bgColor='$bg' class='style12'>";
																			$inv_row .= "<a target='_blank' href='viewCompany.php?ID=" . $so_item_row1["b2bid"] . "&show=transactions&warehouse_id=" . $so_item_row1["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $so_item_row1["warehouse_id"] . "&rec_id=" . $so_item_row1["I"] . "&display=buyer_view'>" . getnickname($so_item_row1["company_name"], $so_item_row1["b2bid"]);
																			$inv_row .= "</a></td></tr>";

																			$no_of_trans_plann_del_pass_str .= $inv_row;
																		}
																		?>
																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4"># of transactions, Planned Delivery Date
																				Passed</td>
																			<td class="style12_new2" bgcolor="#f4f4f4">
																				<input type="hidden" id="no_of_trans_plann_del_pass_str" name="no_of_trans_plann_del_pass_str" value="<?php echo $no_of_trans_plann_del_pass_str; ?>" />
																				<a href="javascript:void(0)" onclick="return inv_summary('no_of_trans_plann_del_pass_str', 0);"><?php echo $no_of_trans_plann_del_pass; ?></a>
																			</td>
																		</tr>

																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4"># of Inventory Items, Note Date > 1 week</td>
																			<td class="style12_new2" bgcolor="#f4f4f4">
																				<input type="hidden" id="no_of_inv_item_note_date_str" name="no_of_inv_item_note_date_str" value="<?php echo htmlentities($no_of_inv_item_note_date_str); ?>" />
																				<!-- <input type="hidden" id="no_of_inv_item_note_date_str" name="no_of_inv_item_note_date_str" value="" />-->
																				<a href="javascript:void(0)" onclick="return inv_summary('no_of_inv_item_note_date_str', 1);"><?php echo $no_of_inv_item_note_date; ?></a>
																			</td>
																		</tr>

																		<?php
																		$inv_row = "<tr vAlign='center'><td bgColor='$bg' colspan=2 class='style12'>";
																		$inv_row .= "List of inventory items NOT completed</td>";
																		$inv_row .= "</tr>";
																		//$no_of_inv_not_complete_str .= $inv_row;
																		$no_of_inv_not_complete_str = $inv_row;
																		$no_of_inv_not_complete = 0;

																		$dt_so_item1 = "Select b2b_id, id from loop_boxes ";
																		$dt_so_item1 .= " where inactive = 0 and entry_confirmed_log <> 'Yes'";
																		db();
																		$dt_res_so_item1 = db_query($dt_so_item1);
																		while ($so_item_row1 = array_shift($dt_res_so_item1)) {
																			$no_of_inv_not_complete = $no_of_inv_not_complete + 1;

																			$inv_row = "<tr vAlign='center'>";
																			$inv_row .= "<td bgColor='$bg' class='style12'>";
																			$inv_row .= "<a target='_blank' href='manage_box_b2bloop.php?id=" . $so_item_row1["id"] . "&proc=View&'>" . $so_item_row1["id"];
																			$inv_row .= "</a></td></tr>";

																			$no_of_inv_not_complete_str .= $inv_row;
																		}
																		?>
																		<tr>
																			<td class="style12_new1" bgcolor="#f4f4f4"># of inventory items NOT completed</td>
																			<td class="style12_new2" bgcolor="#f4f4f4">
																				<input type="hidden" id="no_of_inv_not_complete_str" name="no_of_inv_not_complete_str" value="<?php echo htmlentities($no_of_inv_not_complete_str); ?>" />
																				<a href="javascript:void(0)" onclick="return inv_summary('no_of_inv_not_complete_str', 2);"><?php echo $no_of_inv_not_complete; ?></a>
																			</td>
																		</tr>
																	</table>
																</div>
															</td>
														</tr>
													</table>
												</div>

											<?php
											echo "<input type='hidden' id='inventory_preord_totctl' value='$preordercnt' />";
										}
											?>

											<div id="light" class="white_content">
											</div>
											<div id="fade" class="black_overlay"></div>

											<?php if ($_REQUEST["show"] != "inventory_new_org" && $_REQUEST["no_sess"] != "yes") { ?>
												<div>
													<?php include("inc/header.php"); ?>
												</div>
											<?php } ?>


											<?php if ($_REQUEST["show"] != "inventory_new_org" && $_REQUEST["no_sess"] != "yes") { ?>
												<div class="main_data_css">
												<?php } ?>
												<div class="dashboard_heading" style="float: left;">
													<div style="float: left;">
														B2B Dashboard Homepage
													</div>
												</div>


												<div style="height: 20px;">&nbsp;</div>

												<table border="0" width="1600">
													<tr>
														<td></td>
														<td align=left height=10>

														</td>
													</tr>

													<?php if ($_REQUEST["show"] == "search") {  ?>
														<tr>
															<td colspan=2>

																<?php
																showStatusesDashboard_search($viewin, $eid);
																?>
															</td>
														</tr>
													<?php } else { ?>

														<tr style="padding-bottom:1px">
															<td width=200 valign=top style="padding-bottom:1px" border=1px>
																<font size=2>
																	<!------------------ Begin To-do -------->
																	<?php
																	$super_user = "";
																	$sql = "SELECT level FROM loop_employees where initials = '" . $_COOKIE["userinitials"] . "'";
																	db();
																	$result = db_query($sql);
																	while ($rowemp = array_shift($result)) {
																		$super_user = $rowemp["level"];
																	}

																	if ($super_user == 2) { ?>
																		<a href="dashboard_management_v1.php">CheckBOX</a><br><br>
																	<?php
																	}

																	$x = "Select assign_to from todolist where assign_to = '" . $_COOKIE['userinitials'] . "' and status = 1";
																	db();
																	$oldfollowup = db_query($x);
																	$task_due = tep_db_num_rows($oldfollowup);

																	$x = "Select assign_to from todolist where assign_to = '" . $_COOKIE['userinitials'] . "' and status = 1 and due_date = '" . date("Y-m-d") . "'";
																	db();
																	$oldfollowup = db_query($x);
																	$task_due_today = tep_db_num_rows($oldfollowup);

																	$x = "Select assign_to from todolist where assign_to = '" . $_COOKIE['userinitials'] . "' and status = 1 and due_date < '" . date("Y-m-d") . "'";
																	db();
																	$oldfollowup = db_query($x);
																	$task_due_pastdue = tep_db_num_rows($oldfollowup);
																	?>
																	<a href="dashboardnew_todo.php"><?php echo "Tasks (<font color=red>" . $task_due_pastdue . "</font>, <font color=#4b9952>" . $task_due_today . "</font>, <font color=black>" . $task_due . "</font>"; ?>)</a><br><br>
																	<!------------------ End To-do -------->

																	<!------------------ Opportunity -------->
																	<a href="dashboardnew_opportunity.php">Opportunities</a><br><br>
																	<!------------------ Opportunity -------->


																	<a href="dashboardnew_account_pipeline.php"><b>Account Pipeline</b></a><br><br>
																	<?php if ($user_lvl == 2 || $_COOKIE['b2b_id'] == 22) { ?>
																		<a href="dashboardnew_account_pipeline_all.php"><b>All Accounts Pipeline (MGR
																				View)</b></a><br><br>
																	<?php } ?>

																	<a href="dashboard_inventory_v3.php">All Inventory Available to Sell v3.0</a><br><br>

																	<a href="dashboard_sales_quotas.php?initials=<?php echo urlencode($row['initials']); ?>">Quota
																		History</a><br><br>
																	<!-- <a href="">Sales Quota History</a><br><br> -->
																	<a href="dashboard_commissions.php">Commissions</a><br><br>

																	<a href="function-dashboard-newlinks.php"><b>Old Dashboard Links</b></a><br><br>

																</font>
															</td>

															<!------------------------- Begin Large Window ------------------------------>

															<td width=1200 valign=top>

																<div id="divdealinprocess">
																</div>

																<?php
																if ($_REQUEST["limit"] == "all") {

																	$show_number = 0;
																}

																if ($_REQUEST["show"] == "searchbox") {
																	//searchbox("dashboardnew.php",$eid); 
																} elseif ($_REQUEST["show"] == "status") {
																	//echo "here" . $_REQUEST["statusid"];

																	$arr = array($_REQUEST["statusid"]);
																	showStatusesDashboard($arr, $eid, $show_number, "all");
																} elseif ($_REQUEST["show"] == "status_water") {
																	$arr = array($_REQUEST["statusid"]);
																	showStatusesDashboard($arr, $eid, $show_number, "all", 1);
																} elseif ($_REQUEST["show"] == "followups") {

																	showStatusesDashboard($viewin, $eid, $show_number, $_REQUEST["period"]);
																} elseif ($_REQUEST["show"] == "todo") {

																	showtodolist();
																} elseif ($_REQUEST["show"] == "opportunity") {

																	include_once("dashboard_opportunity.php");
																} elseif ($_REQUEST["show"] == "specialops") {
																	$arr = array(58);
																	showStatusesDashboard($viewin, $eid, $show_number, $_REQUEST["period"]);
																} elseif ($_REQUEST["show"] == "unassigned") {
																	echo "<a href='report_show_unassign_lead.php' target='_blank'>Click here to View All</a><br><br>";
																	//$arr = array(6,38,42,32,3,51,56,36);
																	$arr = array(38, 32, 3, 51, 56);
																	showStatusesDashboard($arr, $eid, 0, 'all');
																} elseif ($_REQUEST["show"] == "search") {
																	showStatusesDashboard_search($viewin, $eid);

																?><br><?php

																	} elseif ($_REQUEST["show"] == "feed") {
																		showfeed(-1);
																	} elseif ($_REQUEST["show"] == "openquotes") {
																		showopenquotes($eid);
																	} elseif ($_REQUEST["show"] == "customers") {
																		showCustomerList();
																	} elseif ($_REQUEST["show"] == "inventory") {
																		showinventory_fordashboard_new(0);
																	} elseif ($_REQUEST["show"] == "inventory_cron") {
																		//showinventory_fordashboard_cron_withview(0); 
																		showinventory_fordashboard_invnew(0);
																	} elseif ($_REQUEST["show"] == "inventory_filter") {
																		showinventory_fordashboard_selected(0);
																	} elseif ($_REQUEST["show"] == "inventory_new_org") {
																		showinventory_fordashboard_invmatch_new(1, 1, 1);
																	} elseif ($_REQUEST["show"] == "inventory_v3") {

																		showinventory_v3_new(1, 1, 1);
																	} elseif ($_REQUEST["show"] == "oldinventory") {
																		showmap2();
																		echo "<br>";
																		echo "<a href='showmap_all_entry.php'>Show Map with all Boxes</a>";
																		echo "<br><br>";
																		showinventory_fordashboard(0);
																	} elseif ($_REQUEST["show"] == "inventory_old") {
																		showmap2();
																		echo "<br>";
																		echo "<a href='showmap_all_entry.php'>Show Map with all Boxes</a>";
																		echo "<br><br>";
																		showinventory(0);
																	} elseif ($_REQUEST["show"] == "olderthan3months") {
																		showolderthan3months();
																	} elseif ($_REQUEST["show"] == "links") {
																		//useful_links(); 
																		useful_links_new2021();
																	} elseif ($_REQUEST["show"] == "contacts") {
																		showcontacts($initials);
																	} elseif ($_REQUEST["show"] == "freightcalendar") {
																		?>
																	<iframe src="https://docs.google.com/spreadsheet/ccc?key=0Akv0bNDB5PrkdElxYm1hNVN6TGhERU5rY3R1cjVSaGc#gid=23" width="1400" height="1000"></iframe>
																<?php

																	} elseif ($_REQUEST["show"] == "orderissues") {

																		$sort_order_pre = "ASC";
																		if ($_GET['sort_order_pre'] == "ASC") {
																			$sort_order_pre = "DESC";
																		} else {
																			$sort_order_pre = "ASC";
																		}
																?>

																	<table>
																		<tr>
																			<td class="style24" colspan=19 style="height: 16px" align="middle"><strong>ORDER
																					ISSUES</strong></td>
																		</tr>
																		<tr>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=ID&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>ID</strong></a>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=company_name&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Company</strong></a>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=last_note_text&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Last
																						Note</strong></a></th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=po_upload_date&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>PO
																						Upload Date</strong></a></th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=po_delivery_dt&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Planned
																						Delivery Date</strong></a></th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=source&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Source</strong></a>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=quantity&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Quantity</strong></a>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=ship_date&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Ship
																						Date</strong></a></th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=last_action&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Last
																						Action</strong></a></th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=next_action&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Next
																						Action</strong></a></th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
																				<strong>Order</strong>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
																				<strong>Ship</strong>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
																				<strong>Delivery</strong>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
																				<strong>Pay</strong>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" width=35 align="middle">
																				<strong>Vendor</strong>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=invoice_amount&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Invoiced
																						Amount</strong></a></th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=balance&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Balance</strong></a>
																			</th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><a href='dashboardnew.php?show=orderissues&sort=invoice_age&sort_order_pre=<?php echo $sort_order_pre; ?>'><strong>Invoice
																						Age</strong></a></th>
																			<th bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle"><strong>Remove
																					from List</strong></th>
																		</tr>
																		<?php
																		$dt_view_qry = "SELECT loop_transaction_buyer.no_invoice, loop_transaction_buyer.po_delivery,loop_transaction_buyer.po_delivery_dt,  loop_warehouse.b2bid, loop_warehouse.Active, loop_warehouse.company_name AS B, loop_transaction_buyer.warehouse_id AS D, loop_transaction_buyer.inv_amount AS F, loop_transaction_buyer.so_entered AS G, loop_transaction_buyer.po_date AS H , loop_transaction_buyer.id AS I, loop_transaction_buyer.inv_date_of AS J FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.po_employee = '$initials' and loop_transaction_buyer.order_issue = 1 and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";
																		db();
																		$dt_view_res = db_query($dt_view_qry);
																		$MGArray = array();
																		while ($dt_view_row = array_shift($dt_view_res)) {

																			$activeflg_str = "";
																			if ($dt_view_row["Active"] == 0) {
																				$activeflg_str = "<font face='arial' size='2' color='red'><b>&nbsp;INACTIVE</b><font>";
																			}

																			//This is the payment Info for the Customer paying UCB
																			$payments_sql = "SELECT SUM(loop_buyer_payments.amount) AS A FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
																			db();
																			$payment_qry = db_query($payments_sql);
																			$payment = array_shift($payment_qry);

																			//This is the payment info for UCB paying the related vendors
																			$vendor_sql = "SELECT COUNT(loop_transaction_buyer_payments.id) AS A, MIN(loop_transaction_buyer_payments.status) AS B, MAX(loop_transaction_buyer_payments.status) AS C FROM loop_transaction_buyer_payments WHERE loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
																			$vendor_qry = db_query($vendor_sql);
																			$vendor = array_shift($vendor_qry);

																			//Info about Shipment
																			$bol_file_qry = "SELECT * FROM loop_bol_files WHERE trans_rec_id LIKE '" . $dt_view_row["I"] . "' ORDER BY id DESC";
																			$bol_file_res = db_query($bol_file_qry);
																			$bol_file_row = array_shift($bol_file_res);

																			$fbooksql = "SELECT * FROM loop_transaction_freight WHERE trans_rec_id=" . $dt_view_row["I"];
																			$fbookresult = db_query($fbooksql);
																			$freightbooking = array_shift($fbookresult);

																			//Last tansaction Note
																			$sql_ln = "SELECT * FROM loop_transaction_notes WHERE loop_transaction_notes.company_id = " . $dt_view_row["D"] . " and loop_transaction_notes.rec_id = " . $dt_view_row["I"] . " ORDER BY id DESC LIMIT 0,1";
																			$result_ln = db_query($sql_ln);
																			$last_note = array_shift($result_ln);


																			$last_note_text = $last_note["message"];
																			$last_note_date = $last_note["date"];
																			if ($dt_view_row["po_delivery_dt"] == "") {
																				$Planned_delivery_date = $dt_view_row["po_delivery"];
																			} else {
																				$Planned_delivery_date = date("m/d/Y", strtotime($dt_view_row["po_delivery_dt"]));
																			}

																			$vendors_paid = 0; //Are the vendors paid
																			$vendors_entered = 0; //Has a vendor transaction been entered?
																			$invoice_paid = 0; //Have they paid their invoice?
																			$invoice_entered = 0; //Has the inovice been entered
																			$signed_customer_bol = 0; 	//Customer Signed BOL Uploaded
																			$courtesy_followup = 0; 	//Courtesy Follow Up Made
																			$delivered = 0; 	//Delivered
																			$signed_driver_bol = 0; 	//BOL Signed By Driver
																			$shipped = 0; 	//Shipped
																			$bol_received = 0; 	//BOL Received @ WH
																			$bol_sent = 0; 	//BOL Sent to WH"
																			$bol_created = 0; 	//BOL Created
																			$freight_booked = 0; //freight booked
																			$sales_order = 0;   // Sales Order entered
																			$po_uploaded = 0;  //po uploaded 

																			//Are all the vendors paid?
																			if ($vendor["B"] == 2 && $vendor["C"] == 2) {
																				$vendors_paid = 1;
																			}

																			//Have we entered a vendor transaction?
																			if ($vendor["A"] > 0) {
																				$vendors_entered = 1;
																			}

																			//Have they paid their invoice?
																			if (number_format($dt_view_row["F"], 2) == number_format($payment["A"], 2) && $dt_view_row["F"] != "") {
																				$invoice_paid = 1;
																			}
																			if ($dt_view_row["no_invoice"] == 1) {
																				$invoice_paid = 1;
																			}

																			//Has an invoice amount been entered?
																			if ($dt_view_row["F"] > 0) {
																				$invoice_entered = 1;
																			}

																			if ($bol_file_row["bol_shipment_signed_customer_file_name"] != "") {
																				$signed_customer_bol = 1;
																			}	//Customer Signed BOL Uploaded
																			if ($bol_file_row["bol_shipment_followup"] > 0) {
																				$courtesy_followup = 1;
																			}	//Courtesy Follow Up Made
																			if ($bol_file_row["bol_shipment_received"] > 0) {
																				$delivered = 1;
																			}	//Delivered
																			if ($bol_file_row["bol_signed_file_name"] != "") {
																				$signed_driver_bol = 1;
																			}	//BOL Signed By Driver
																			if ($bol_file_row["bol_shipped"] > 0) {
																				$shipped = 1;
																			}	//Shipped
																			if ($bol_file_row["bol_received"] > 0) {
																				$bol_received = 1;
																			}	//BOL Received @ WH
																			if ($bol_file_row["bol_sent"] > 0) {
																				$bol_sent = 1;
																			}	//BOL Sent to WH"
																			if ($bol_file_row["id"] > 0) {
																				$bol_created = 1;
																			}	//BOL Created

																			if ($freightbooking["id"] > 0) {
																				$freight_booked = 1;
																			} //freight booked

																			if (($dt_view_row["G"] == 1)) {
																				$sales_order = 1;
																			} //sales order created
																			if ($dt_view_row["H"] != "") {
																				$po_uploaded = 1;
																			} //po uploaded 


																			$boxsource = "";
																			$box_qry = "SELECT loop_transaction_buyer_payments.id AS A , loop_transaction_buyer_payments.status AS B, files_companies.name AS C from loop_transaction_buyer_payments INNER JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  INNER JOIN loop_vendor_type ON loop_transaction_buyer_payments.typeid = loop_vendor_type.id  WHERE loop_transaction_buyer_payments.typeid = 1 AND loop_transaction_buyer_payments.transaction_buyer_id = " . $dt_view_row["I"];
																			db();
																			$box_res = db_query($box_qry);
																			while ($box_row = array_shift($box_res)) {
																				$boxsource = $box_row["C"];
																			}
																			//echo $box_qry;
																			$start_t = "";
																			$end_time = "";
																			$invoice_age = 0;
																			$balance = 0;
																			$next_action_str = "";
																			$last_action_str = "";
																			$dt_view_row2 = array('A' => "", 'Q1' => "", 'Q2' => "", 'Q3' => "");
																			if ($shipped == 0) {

																				$dt_view_qry2 = "SELECT SUM(loop_bol_tracking.qty) AS A, loop_bol_tracking.bol_STL1 AS B, loop_bol_tracking.trans_rec_id AS C, loop_bol_tracking.warehouse_id AS D, loop_bol_tracking.bol_pickupdate AS E, loop_bol_tracking.quantity1 AS Q1, loop_bol_tracking.quantity2 AS Q2, loop_bol_tracking.quantity3 AS Q3 FROM loop_bol_tracking WHERE loop_bol_tracking.trans_rec_id = " . $dt_view_row["I"];
																				db();
																				$dt_view_res2 = db_query($dt_view_qry2);
																				$dt_view_row2 = array_shift($dt_view_res2);

																				if ($invoice_paid == 1) {
																					if ($vendors_paid == 1) {
																						$last_action_str = "Vendors Paid";
																					} elseif ($vendors_entered == 1) {
																						$last_action_str = "Vendors Invoiced";
																					} else {
																						$last_action_str = "Customer Paid";
																					}
																				} elseif ($invoice_entered == 1) {
																					$last_action_str = "Customer Invoiced";
																				} elseif ($signed_customer_bol == 1) {
																					$last_action_str = "Customer Signed BOL";
																				} elseif ($courtesy_followup == 1) {
																					$last_action_str = "Courtesy Followup Made";
																				} elseif ($delivered == 1) {
																					$last_action_str = "Delivered";
																				} elseif ($signed_driver_bol == 1) {
																					$last_action_str = "Shipped - Driver Signed";
																				} elseif ($shipped == 1) {
																					$last_action_str = "Shipped";
																				} elseif ($bol_received == 1) {
																					$last_action_str = "BOL @ Warehouse";
																				} elseif ($bol_sent == 1) {
																					$last_action_str = "BOL Sent to Warehouse";
																				} elseif ($bol_created == 1) {
																					$last_action_str = "BOL Created";
																				} elseif ($freight_booked == 1) {
																					$last_action_str = "Freight Booked";
																				} elseif ($sales_order == 1) {
																					$last_action_str = "Sales Order Entered";
																				} elseif ($po_uploaded == 1) {
																					$last_action_str = "PO Uploaded";
																				}


																				if ($invoice_paid == 1) {
																					if ($vendors_paid == 1) {
																						$next_action_str = "Complete";
																					} elseif ($vendors_entered == 1) {
																						$next_action_str = "Pay Vendor";
																					} else {
																						$next_action_str = "Enter Vendor Invoices";
																					}
																				} elseif ($invoice_entered == 1) {
																					$next_action_str = "Customer to Pay";
																				} elseif ($signed_customer_bol == 1) {
																					$next_action_str = "Invoice Customer";
																				} elseif ($courtesy_followup == 1) {
																					$next_action_str = "Invoice Customer";
																				} elseif ($delivered == 1) {
																					$next_action_str = "Send Courtesy Folllow-up";
																				} elseif ($signed_driver_bol == 1) {
																					$next_action_str = "Confirm Delivery";
																				} elseif ($shipped == 1) {
																					$next_action_str = "Upload Signed BOL";
																				} elseif ($bol_received == 1) {
																					$next_action_str = "Ready to Ship";
																				} elseif ($bol_sent == 1) {
																					$next_action_str = "Confirm BOL Receipt @ Warehouse";
																				} elseif ($bol_created == 1) {
																					$next_action_str = "Send BOL to Warehouse";
																				} elseif ($freight_booked == 1) {
																					$next_action_str = "Create BOL";
																				} elseif ($sales_order == 1) {
																					$next_action_str = "Book Freight";
																				} elseif ($po_uploaded == 1) {
																					$next_action_str = "Enter Sales Order";
																				}

																				$dt_view_qry3 = "SELECT SUM(amount) AS PAID FROM loop_buyer_payments WHERE trans_rec_id = " . $dt_view_row["I"];
																				db();
																				$dt_view_res3 = db_query($dt_view_qry3);
																				$dt_view_row3 = array_shift($dt_view_res3);
																				$balance = number_format(($dt_view_row["F"] - $dt_view_row3["PAID"]), 2);

																				$start_t = strtotime($dt_view_row["J"]);
																				$end_time =  strtotime("now");
																				$invoice_age = number_format(($end_time - $start_t) / (3600 * 24), 0);
																			}	//if paid
																			$sort_warehouse_id = $dt_view_row["D"];
																			$sort_id = $dt_view_row["I"];
																			//$sort_company_name = $dt_view_row["B"];
																			$sort_company_name = getnickname($dt_view_row["B"], $dt_view_row["b2bid"]);
																			$sort_last_note = strtolower($last_note["message"]);
																			$sort_last_note_dt = $last_note["date"];
																			$sort_po_delivery_dt = $Planned_delivery_date;
																			$sort_source = strtolower($boxsource);
																			$sort_quantity =  ($dt_view_row2["A"] + $dt_view_row2["Q1"] + $dt_view_row2["Q2"] + $dt_view_row2["Q3"]);
																			$sort_ship_date = $dt_view_row2["E"];
																			$sort_last_action = $last_action_str;
																			$sort_next_action = $next_action_str;
																			$sort_invoice_amount = number_format($dt_view_row["F"], 2);
																			$sort_balance = $balance;
																			$sort_invoice_age = $invoice_age;
																			$sort_flag = $activeflg_str;

																			$MGArray[] = array(
																				'warehouse_id' => $sort_warehouse_id, 'ID' => $sort_id, 'company_name' => $sort_company_name, 'last_note_text' => $sort_last_note, 'po_upload_date' => $sort_last_note_dt, 'po_delivery_dt' => $sort_po_delivery_dt, 'source' => $sort_source, 'quantity' => $sort_quantity, 'ship_date' => $sort_ship_date, 'last_action' => $sort_last_action, 'next_action' => $sort_next_action, 'invoice_amount' => $sort_invoice_amount, 'balance' => $sort_balance, 'invoice_age' => $sort_invoice_age, 'active' => $sort_flag,
																				'sales_order' => $sales_order, 'po_uploaded' => $po_uploaded, 'shipped' => $shipped, 'bol_created' => $bol_created, 'courtesy_followup' => $courtesy_followup,
																				'delivered' => $delivered, 'invoice_paid' => $invoice_paid, 'invoice_entered' => $invoice_entered, 'vendors_paid' => $vendors_paid, 'vendors_entered' => $vendors_entered
																			);
																		}

																		if ($_GET['sort'] == "ID" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_I = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_I[] = $MGArraytmp['ID'];
																			}
																			array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
																		}
																		if ($_GET['sort'] == "ID" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_I = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_I[] = $MGArraytmp['ID'];
																			}
																			array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
																		}
																		//////
																		if ($_GET['sort'] == "company_name" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_B = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_B[] = $MGArraytmp['company_name'];
																			}
																			array_multisort($MGArraysort_B, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "company_name" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_B = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_B[] = $MGArraytmp['company_name'];
																			}
																			array_multisort($MGArraysort_B, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		//////////
																		if ($_GET['sort'] == "last_note_text" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_C = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_C[] = $MGArraytmp['last_note_text'];
																			}
																			array_multisort($MGArraysort_C, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "last_note_text" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_C = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_C[] = $MGArraytmp['last_note_text'];
																			}

																			array_multisort($MGArraysort_C, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////
																		if ($_GET['sort'] == "po_upload_date" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_D = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_D[] = $MGArraytmp['po_upload_date'];
																			}
																			array_multisort($MGArraysort_D, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "po_upload_date" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_D = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_D[] = $MGArraytmp['po_upload_date'];
																			}
																			array_multisort($MGArraysort_D, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////
																		if ($_GET['sort'] == "po_delivery_dt" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_E = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_E[] = $MGArraytmp['po_delivery_dt'];
																			}
																			array_multisort($MGArraysort_E, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "po_delivery_dt" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_E = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_E[] = $MGArraytmp['po_delivery_dt'];
																			}
																			array_multisort($MGArraysort_E, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		/////////
																		if ($_GET['sort'] == "source" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_F = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_F[] = $MGArraytmp['source'];
																			}
																			array_multisort($MGArraysort_F, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "source" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_F = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_F[] = $MGArraytmp['source'];
																			}
																			array_multisort($MGArraysort_F, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		////////		
																		if ($_GET['sort'] == "quantity" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_G = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_G[] = $MGArraytmp['quantity'];
																			}
																			array_multisort($MGArraysort_G, SORT_ASC, SORT_NUMERIC, $MGArray);
																		}
																		if ($_GET['sort'] == "quantity" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_G = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_G[] = $MGArraytmp['quantity'];
																			}
																			array_multisort($MGArraysort_G, SORT_DESC, SORT_NUMERIC, $MGArray);
																		}
																		//////////
																		if ($_GET['sort'] == "ship_date" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_H = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_H[] = $MGArraytmp['ship_date'];
																			}
																			array_multisort($MGArraysort_H, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "ship_date" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_H = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_H[] = $MGArraytmp['ship_date'];
																			}
																			array_multisort($MGArraysort_H, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		//////////			
																		if ($_GET['sort'] == "last_action" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_J = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_J[] = $MGArraytmp['last_action'];
																			}
																			array_multisort($MGArraysort_J, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "last_action" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_J = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_J[] = $MGArraytmp['last_action'];
																			}
																			array_multisort($MGArraysort_J, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////////
																		if ($_GET['sort'] == "next_action" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_K = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_K[] = $MGArraytmp['next_action'];
																			}
																			array_multisort($MGArraysort_K, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "next_action" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_K = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_K[] = $MGArraytmp['next_action'];
																			}
																			array_multisort($MGArraysort_K, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////
																		if ($_GET['sort'] == "invoice_amount" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_L = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_L[] = $MGArraytmp['invoice_amount'];
																			}
																			array_multisort($MGArraysort_L, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "invoice_amount" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_L = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_L[] = $MGArraytmp['invoice_amount'];
																			}
																			array_multisort($MGArraysort_L, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		////////
																		if ($_GET['sort'] == "balance" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_M = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_M[] = $MGArraytmp['balance'];
																			}
																			array_multisort($MGArraysort_M, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "balance" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_M = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_M[] = $MGArraytmp['balance'];
																			}
																			array_multisort($MGArraysort_M, SORT_DESC, SORT_STRING, $MGArray);
																		}
																		///////
																		if ($_GET['sort'] == "invoice_age" && $_GET['sort_order_pre'] == "ASC") {
																			$MGArraysort_N = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_N[] = $MGArraytmp['invoice_age'];
																			}
																			array_multisort($MGArraysort_N, SORT_ASC, SORT_STRING, $MGArray);
																		}
																		if ($_GET['sort'] == "invoice_age" && $_GET['sort_order_pre'] == "DESC") {
																			$MGArraysort_N = array();

																			foreach ($MGArray as $MGArraytmp) {
																				$MGArraysort_N[] = $MGArraytmp['invoice_age'];
																			}
																			array_multisort($MGArraysort_N, SORT_DESC, SORT_STRING, $MGArray);
																		}

																		$MGArraysort_warehouse_id = array();

																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_warehouse_id[] = $MGArraytmp['warehouse_id'];
																		}

																		$MGArraysort_active = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_MGArraysort_active[] = $MGArraytmp['active'];
																		}

																		$MGArraysort_sales_order = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_sales_order[] = $MGArraytmp['sales_order'];
																		}

																		$MGArraysort_po_uploaded = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_po_uploaded[] = $MGArraytmp['po_uploaded'];
																		}
																		$MGArraysort_shipped = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_shipped[] = $MGArraytmp['shipped'];
																		}
																		$MGArraysort_bol_created = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_bol_created[] = $MGArraytmp['bol_created'];
																		}
																		$MGArraysort_courtesy_followup = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_courtesy_followup[] = $MGArraytmp['courtesy_followup'];
																		}
																		$MGArraysort_delivered = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_delivered[] = $MGArraytmp['delivered'];
																		}
																		$MGArraysort_invoice_paid = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_invoice_paid[] = $MGArraytmp['invoice_paid'];
																		}
																		$MGArraysort_invoice_entered = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_invoice_entered[] = $MGArraytmp['invoice_entered'];
																		}
																		$MGArraysort_vendors_paid = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_vendors_paid[] = $MGArraytmp['vendors_paid'];
																		}
																		$MGArraysort_vendors_entered = array();
																		foreach ($MGArray as $MGArraytmp) {
																			$MGArraysort_vendors_entered[] = $MGArraytmp['vendors_entered'];
																		}

																		foreach ($MGArray as $MGArraytmp2) { ?>

																			<tr>

																				<td bgColor="#e4e4e4" class="style12">
																					<?php echo $MGArraytmp2['ID']; ?>
																				</td>
																				<td bgColor="#e4e4e4" class="style12">
																					<p align="center">
																						<span class="infotxt"><u><a href="search_results.php?id=<?php echo $MGArraytmp2['warehouse_id']; ?>&proc=View&searchcrit=&rec_type=Supplier&page=0"><?php echo $MGArraytmp2["company_name"] . $MGArraytmp2["active"]; ?></a></u>
																							<span style="width:570px;">
																								<table cellSpacing="1" cellPadding="1" border="0" width="570">
																									<tr align="middle">
																										<td class="style7" colspan="3" style="height: 16px"><strong>SALE
																												ORDER DETAILS FOR ORDER ID:
																												<?php echo $MGArraytmp2['ID']; ?></strong></td>
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
																									db();
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
																												<Font Face='arial' size='1'>
																													<?php
																													$get_wh = "SELECT warehouse_name, b2bid FROM loop_warehouse WHERE id = " . $boxes["location_warehouse_id"];
																													db();
																													$get_wh_res = db_query($get_wh);
																													while ($the_wh = array_shift($get_wh_res)) {
																														echo getnickname($the_wh["warehouse_name"], $the_wh["b2bid"]);
																													}
																													?>
																											</td>

																											<td align="left" height="13" style="width: 578px" class="style1">
																												<?php if ($boxes["I"] == "Y") { ?>
																													<font size="1" Face="arial"><?php echo $boxes["blength"]; ?>
																														<?php echo $boxes["blength_frac"]; ?> x
																														<?php echo $boxes["bwidth"]; ?>
																														<?php echo $boxes["bwidth_frac"]; ?> x
																														<?php echo $boxes["bdepth"]; ?>
																														<?php echo $boxes["bdepth_frac"]; ?>
																														<?php echo $boxes["bdescription"]; ?></font>
																												<?php } else { ?>
																													<font size="1" Face="arial">
																														<?php echo $boxes["bdescription"]; ?></font>
																												<?php } ?>
																											</td>
																										</tr>
																									<?php } ?>

																									<?php
																									$soqry = "Select * From loop_salesorders_manual WHERE trans_rec_id = " . $MGArraytmp2['ID'];
																									db();
																									$get_sales_order2 = db_query($soqry);
																									while ($boxes2 = array_shift($get_sales_order2)) {
																									?>
																										<tr bgColor="#e4e4e4">
																											<td height="13" class="style1" align="right">
																												<Font Face='arial' size='1'><?php echo $boxes2["qty"]; ?>
																											</td>
																											<td height="13" class="style1" align="right">&nbsp;</td>

																											<td align="left" height="13" style="width: 578px" class="style1" colspan=2>
																												<font size="1" Face="arial">
																													&nbsp;&nbsp;<?php echo $boxes2["description"]; ?></font>
																											</td>
																										</tr>
																									<?php 	}	?>
																								</table>
																							</span>
																						</span>
																					</p>
																				</td>
																				<td bgColor="#e4e4e4" class="style12">
																					<?php echo ucfirst($MGArraytmp2['last_note_text']); ?>
																				</td>
																				<td bgColor="#e4e4e4" class="style12">
																					<?php echo $MGArraytmp2['po_upload_date']; ?>
																				</td>
																				<td bgColor="#e4e4e4" class="style12">
																					<?php echo $MGArraytmp2['po_delivery_dt']; ?>
																				</td>
																				<td bgColor="#e4e4e4" class="style12">
																					<?php echo ucfirst($MGArraytmp2['source']); ?>
																				</td>
																				<td bgColor="#e4e4e4" class="style12">
																					<?php echo $MGArraytmp2['quantity']; ?>
																				</td>
																				<td bgColor="#e4e4e4" class="style12">
																					<?php echo $MGArraytmp2['ship_date']; ?>
																				</td>
																				<!---- Last Action ------->
																				<td bgColor="#e4e4e4" class="style12">
																					<?php echo $MGArraytmp2["last_action"]; ?>
																				</td>
																				<!---- Next Action ------->
																				<td bgColor="#e4e4e4" class="style12">
																					<?php echo $MGArraytmp2["next_action"]; ?>
																				</td>

																				<?php

																				$open = "<img src=\"images/circle_open.gif\" border=\"0\">";
																				$half = "<img src=\"images/circle__partial.gif\" border=\"0\">";
																				$full = "<img src=\"images/complete.jpg\" border=\"0\">";

																				?>

																				<!------------- ORDERED ---------->
																				<td bgColor="#e4e4e4" class="style12" align="center">
																					<a href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_view">
																						<?php
																						if ($MGArraytmp2["sales_order"] == 1) {
																							echo $full;
																						} elseif ($MGArraytmp2["po_uploaded"] == 1) {
																							echo $half;
																						} else {
																							echo $open;
																						} ?>
																					</a>
																				</td>

																				<!------------- SHIPPED ---------->

																				<td bgColor="#e4e4e4" class="style12" align="center">
																					<a href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_ship">
																						<?php

																						if ($MGArraytmp2["shipped"] == 1) {
																							echo $full;
																						} elseif ($MGArraytmp2["bol_created"] == 1) {
																							echo $half;
																						} else {
																							echo $open;
																						} ?></a>
																				</td>

																				<!------------- RECEIVED ---------->
																				<td bgColor="#e4e4e4" class="style12" align="center">
																					<a href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_received">
																						<?php
																						if ($MGArraytmp2["courtesy_followup"] == 1) {
																							echo $full;
																						} elseif ($MGArraytmp2["delivered"] == 1) {
																							echo $half;
																						} else {
																							echo $open;
																						}

																						?></a>
																				</td>

																				<!------------- PAY ---------->
																				<td bgColor="#e4e4e4" class="style12" align="center">
																					<center>
																						<a href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_payment">
																							<?php

																							if ($MGArraytmp2["invoice_paid"] == 1) {
																								echo $full;
																							} elseif ($MGArraytmp2["invoice_entered"] == 1) {
																								echo $half;
																							} else {
																								echo $open;
																							} ?></a>
																					</center>
																				</td>

																				<!------------- VENDOR ---------->
																				<td bgColor="#e4e4e4" class="style12" align="center">
																					<center>
																						<a href="search_results.php?warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_type=Supplier&proc=View&searchcrit=&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display=buyer_invoice">
																							<?php

																							if ($MGArraytmp2["vendors_paid"] == 1) {
																								echo $full;
																							} elseif ($MGArraytmp2["vendors_entered"] == 1) {
																								echo $half;
																							} else {
																								echo $open;
																							} ?></a>
																					</center>
																				</td>

															</td>

															<td bgColor="#e4e4e4" class="style12">
																<?php echo $MGArraytmp2["invoice_amount"]; ?>
															</td>

															<td bgColor="#e4e4e4" class="style12">
																<?php echo $MGArraytmp2["balance"]; ?>
															</td>
															<?php
																			if ($MGArraytmp2["invoice_age"] > 30 && $MGArraytmp2["invoice_age"] < 1000) {
															?>
																<td bgColor="#ff0000" class="style12">
																	<?php echo $MGArraytmp2["invoice_age"]; ?>
																</td>
															<?php
																			} elseif (number_format(($end_time - $start_t) / (3600 * 24000), 0) > 10) {
															?>
																<td bgColor="#e4e4e4" class="style12">&nbsp;

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
															<td bgColor="#e4e4e4" class="style12">
																<input type=button onclick="confirmationIgnore('<?php echo $MGArraytmp2["company_name"]; ?>','<?php echo $MGArraytmp2['ID']; ?>')" value="X">
															</td>
														</tr>
													<?php
																		}	//loop
													?>

												</table>
											<?php
																	} elseif ($_REQUEST["show"] == "preshipped") {
											?>
												<script>
													showdealinprocess(<?php echo "'" . $_COOKIE["userinitials"] . "'" ?>, 'ASC', '');
												</script>
											<?php
																	} else {
																		//searchbox("dashboardnew.php",$eid); 
											?>
												<?php

																		show_counts();
																		if ($dashboard_view == "None") {
																		} else {
																			employee_leaderboard("dashboardnew.php", $eid, $initials, $dashboard_view, $name);
																		}
												?><br />

												<table cellSpacing="1" cellPadding="1" border="0" width="1000">
													<tr>
														<div id="span_emp_status">
															<td bgColor='#ABC5DF' align="center" colspan="14"><strong>Employee - Status wise company
																	list</strong>&nbsp;&nbsp;
																<a href="javascript:void(0);" onclick="ex_emp_status('<?php echo $tmp_view; ?>', '<?php echo $eid; ?>', '<?php echo $show_number; ?>', 'today');">Expand</a>
																/<a href="javascript:void(0);" onclick="colp_emp_status();">Collapse</a>
															</td>
														</div>
													</tr>
													<tr>
														<td>
															<div id="StatusesDashboard_div"></div>
														</td>
													</tr>
												</table>
											<?php

																	}

											?>
											</td>
											</tr>

										<?php } //Added for search bar in separate tr
										?>
										</table>




										<?php


										function show_counts(): void
										{
										?>
											<style>
												.boxcounter_title {
													font-size: 13px;
													font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
													background: #d3f1c9;
													text-align: center;
												}

												.boxcounter_val {
													font-size: 12px;
													font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
													background: #f3f3f3;
													text-align: center;
												}
											</style>

											<?php
											//
											//Code for box counter
											$box_cnt_qry = "select * from loop_box_counter";
											db();
											$boxcnt_res = db_query($box_cnt_qry);
											$boxcnt_rows = array_shift($boxcnt_res);
											//if(tep_db_num_rows($boxcnt_res)>0)
											//{

											$sb_qty = $boxcnt_rows["sb_qty"] + $boxcnt_rows["b2c_shipping_box_qty"];

											$greensave_sql		= 	"SELECT tree_counter.trees_saved as saveone, tree_counter_b2b.trees_saved as save2 FROM tree_counter,tree_counter_b2b;";
											$greensave_query 	= 	db_query($greensave_sql);
											$greensave_result 	= 	array_shift($greensave_query);

											$save1 				= 	$greensave_result['saveone'];
											$save2 				= 	$greensave_result['save2'];
											$dTotalSave			=	$save1 + $save2;
											$dTotalSave			=	number_format($dTotalSave, 0, '.', ',');

											?>
											<table cellpadding="3" cellspacing="1" width="30%">
												<tr>
													<td colspan="5" class="boxcounter_title" align="center">
														<b>Since 2010, UCB Has Processed...</b>
													</td>
												</tr>
												<tr>
													<td class="boxcounter_title">Gaylords</td>
													<td class="boxcounter_title">Shipping Boxes</td>
													<td class="boxcounter_title">Pallets</td>
													<td class="boxcounter_title">Supersacks</td>
													<td class="boxcounter_title">Trees Saved</td>
												</tr>
												<tr>
													<td class="boxcounter_val"><?php echo number_format($boxcnt_rows["gy_qty"]); ?></td>
													<td class="boxcounter_val"><?php echo number_format($sb_qty); ?></td>
													<td class="boxcounter_val"><?php echo number_format($boxcnt_rows["pal_qty"]); ?></td>
													<td class="boxcounter_val"><?php echo number_format($boxcnt_rows["sup_qty"]); ?></td>
													<td class="boxcounter_val"><?php echo $dTotalSave; ?></td>

												</tr>

											</table>
											<br>
										<?php
										}

										function employee_leaderboard(string $url, int $eid, string $initials, string $dashboard_view, string $name): void
										{
										?>

											<br>
											<?php
											//}
											//End Code for box counter
											//--------------------------------------------------------------------

											//
											$emp_filter = " and loop_transaction_buyer.po_employee = '$initials'";
											if ($dashboard_view == "Operations" || $dashboard_view == "Executive") {
												$emp_filter = "";
											}

											if ($dashboard_view == "Pallet Sourcing") {
												$dashboard_view = "Rescue";
											}
											?>

											<table cellSpacing="1" cellPadding="1" border="0" width="1000">
												<?php if ($dashboard_view != "Rescue") { ?>
													<tr>
														<div id="span_today_snapshot">
															<td bgColor='#ABC5DF' align="center" colspan="14"><strong>Today's Snapshot</strong>&nbsp;&nbsp;
																<a href="javascript:void(0);" onclick="ex_today_snapshot('<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
																/<a href="javascript:void(0);" onclick="colp_today_snapshot();">Collapse</a>
															</td>
														</div>
													</tr>
													<tr>
														<td>
															<div id="ex_today_snapshot_div"></div>
														</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
													</tr>

													<tr>
														<div id="span_close_deal_pipline">
															<td bgColor='#ABC5DF' align="center" colspan="14"><strong>Closed Deal Pipeline</strong>&nbsp;&nbsp;
																<a href="javascript:void(0);" onclick="ex_close_deal_pipline('<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
																/<a href="javascript:void(0);" onclick="colp_close_deal_pipline();">Collapse</a>
															</td>
														</div>
													</tr>
													<tr>
														<td>
															<div id="close_deal_pipline_div"></div>
														</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
													</tr>
												<?php } ?>

												<?php if ($dashboard_view == "Rescue") { ?>
													<tr>
														<div id="span_close_deal_pipline">
															<td bgColor='#ABC5DF' align="center" colspan="14"><strong>Closed Deal Pipeline</strong>&nbsp;&nbsp;
																<a href="javascript:void(0);" onclick="ex_close_deal_pipline_sourcing('<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
																/<a href="javascript:void(0);" onclick="colp_close_deal_pipline_sourcing();">Collapse</a>
															</td>
														</div>
													</tr>
													<tr>
														<td>
															<div id="close_deal_pipline_div"></div>
														</td>
													</tr>
													<tr>
														<td>&nbsp;</td>
													</tr>
												<?php } ?>

												<tr>
													<div id="span_rev_tracker">
														<td bgColor='#ABC5DF' align="center" colspan="14"><strong>Revenue Quota Incentive
																Tracker</strong>&nbsp;&nbsp;
															<a href="javascript:void(0);" onclick="ex_rev_tracker('<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
															/<a href="javascript:void(0);" onclick="colp_rev_tracker();">Collapse</a>
														</td>
													</div>
												</tr>
												<tr>
													<td>
														<div id="rev_tracker_div"></div>
													</td>
												</tr>

												<?php if ($dashboard_view != "Rescue") { ?>
													<tr>
														<td>&nbsp;</td>
													</tr>

													<tr>
														<div id="hide_tr_spin">
															<td bgColor='#ABC5DF' align="center" colspan="14"><strong>New Deal Spins (No. of Deals >= 2,000 and
																	2 New Deals = 1 Spin)</strong>&nbsp;&nbsp;
																<a href="javascript:void(0);" onclick="ex_dash_deal_spin('<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
																/<a href="javascript:void(0);" onclick="colp_dash_deal_spin();">Collapse</a>
															</td>
														</div>
													</tr>
													<tr>
														<td>
															<div id="deal_spin_display"></div>
														</td>
													</tr>
												<?php } ?>
											</table>

											<br /><br />

											<table cellSpacing="1" cellPadding="1" border="0" width="1000">
												<tr>
													<div id="span_activity_tracking">
														<td bgColor='#ABC5DF' align="center" colspan="14"><strong>Activity Tracking</strong>&nbsp;&nbsp;
															<a href="javascript:void(0);" onclick="ex_activity_tracking('<?php echo $initials; ?>', '<?php echo $dashboard_view; ?>');">Expand</a>
															/<a href="javascript:void(0);" onclick="colp_activity_tracking();">Collapse</a>
														</td>
													</div>
												</tr>
												<tr>
													<td>
														<div id="activity_tracking_div"></div>
													</td>
												</tr>
											</table>

											<br /><br />

										<?php
										}

										?>

										<!------------------------ END NEW DASHBOARD ------------>
										<?php
										function showCustomerList(): void
										{

											if ($_REQUEST["so"] == "A") {
												$so = "D";
											} else {
												$so = "A";
											}

										?>
											<div><i>Note: Please wait until you see <font color="red">"END OF REPORT"</font> at the bottom of the report,
													before using the sort option.</i></div>
											<table>
												<tr>
													<td width="5%" bgcolor="#D9F2FF">
														<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=dt&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">DATE</a>
														</font>
													</td>
													<td width="5%" bgcolor="#D9F2FF">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=age&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">AGE</a>
														</font>
													</td>
													<td width="10%" bgcolor="#D9F2FF" align="center">
														<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=contact&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">CONTACT</a>
														</font>
													</td>
													<td width="21%" bgcolor="#D9F2FF">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=cname&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">COMPANY
																NAME</a></font>
													</td>
													<td width="21%" bgcolor="#D9F2FF">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=status&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"] . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">STATUS</a>
														</font>
													</td>
													<td width="8%" bgcolor="#D9F2FF">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333">PHONE</font>
													</td>
													<td bgcolor="#D9F2FF" align="center">
														<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=city&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">CITY</a>
														</font>
													</td>
													<td bgcolor="#D9F2FF" align="center">
														<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=state&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">STATE</a>
														</font>
													</td>
													<td bgcolor="#D9F2FF" align="center">
														<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=zip&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">ZIP</a>
														</font>
													</td>
													<td bgcolor="#D9F2FF" align="center">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=ns&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Next
																Step</a></font>
													</td>
													<td bgcolor="#D9F2FF" align="center">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=lc&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Last<br>Communication</a>
														</font>
													</td>
													<td bgcolor="#D9F2FF" align="center">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nd&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Next
																Communication</font>
													</td>

													<td bgcolor="#D9F2FF" align="center">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=nooftrans&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">#
																of transactions</font>
													</td>
													<td bgcolor="#D9F2FF" align="center">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=totrev&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Total
																Revenue</font>
													</td>
													<td bgcolor="#D9F2FF" align="center">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=totprofit&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Total
																Profit</font>
													</td>
													<td bgcolor="#D9F2FF" align="center">
														<font size="1" face="Arial, Helvetica, sans-serif" color="#333333"><a href="<?php echo htmlentities($_SERVER['PHP_SELF'] . "?sk=profitmargin&so=" . $so . "&show=" . $_REQUEST["show"] . "&statusid=" . $_REQUEST["statusid"]  . "&searchterm=" . $_REQUEST["searchterm"] . "&andor=" . $_REQUEST["andor"] . "&state=" . $_REQUEST["state"]); ?>">Profit
																margin</font>
													</td>
												</tr>

												<?php
												$sql = "Select companyInfo.id AS I, companyInfo.status, companyInfo.last_contact_date, companyInfo.loopid AS LID, companyInfo.contact AS C,  companyInfo.dateCreated AS D,  companyInfo.company AS CO, companyInfo.nickname AS NN, companyInfo.phone AS PH,  companyInfo.city AS CI,  companyInfo.state AS ST,  companyInfo.zip AS ZI, companyInfo.next_step AS NS, companyInfo.last_date AS LD, companyInfo.next_date AS ND, employees.initials AS EI from companyInfo LEFT OUTER JOIN employees ON companyInfo.assignedto = employees.employeeID Where companyInfo.assignedto = " . $_COOKIE['b2b_id'] . " and companyInfo.loopid > 0 and companyInfo.active = 1 ";
												$sql = $sql . " GROUP BY companyInfo.id ";
												//echo "<br/>" . $sql . "<br/><br/>";
												db_b2b();
												$data_res = db_query($sql);
												$MGArray = array();
												while ($data = array_shift($data_res)) {
													$sqlmtd = "SELECT SUM(inv_amount) AS totrevenue, count(loop_transaction_buyer.id) as nooftrans FROM loop_transaction_buyer WHERE loop_transaction_buyer.shipped = 1 AND inv_entered = 1 AND commission_paid = 1 and loop_transaction_buyer.ignore < 1 AND loop_transaction_buyer.warehouse_id = " . $data["LID"];
													db();
													$data_1 = db_query($sqlmtd);
													$totrevenue = 0;
													$nooftrans = 0;
													while ($datars_new = array_shift($data_1)) {
														$totrevenue = $datars_new["totrevenue"];
														$nooftrans = $datars_new["nooftrans"];
													}

													$sqlmtd = "SELECT SUM(inv_amount) AS SUMPO FROM loop_transaction_buyer WHERE shipped = 1 and inv_entered = 1 and commission_paid = 1 and loop_transaction_buyer.ignore < 1 AND loop_transaction_buyer.warehouse_id = " . $data["LID"];
													db();
													$resultmtd = db_query($sqlmtd);
													$summtd_1 = 0;
													while ($summtd = array_shift($resultmtd)) {
														$summtd_1 = $summtd["SUMPO"];
													}

													$sqlmtd = "SELECT SUM(amount) AS sum_amt from loop_transaction_buyer_payments INNER JOIN loop_transaction_buyer ON loop_transaction_buyer_payments.transaction_buyer_id=loop_transaction_buyer.id WHERE loop_transaction_buyer.ignore < 1 and shipped = 1 and inv_entered = 1 and commission_paid = 1 and loop_transaction_buyer.warehouse_id = " . $data["LID"];
													db();
													$resultmtd = db_query($sqlmtd);
													$summtd_SUMPO = 0;
													$emp_yr_grossprf_tot = 0;
													$profit_margin = 0;
													while ($summtd = array_shift($resultmtd)) {
														if ($summtd_1 > $summtd["sum_amt"]) {
															$summtd_SUMPO = $summtd_1 - $summtd["sum_amt"];
															$emp_yr_grossprf_tot = $emp_yr_grossprf_tot + $summtd_SUMPO;
														}
														$profit_margin = ($summtd_SUMPO * 100) / $summtd_1;
														$profit_margin = number_format($profit_margin, 2) . "%";
													}

													$tmp_msg_dt = date('Y-m-d', strtotime($data['last_contact_date']));

													$status_name = "";
													$qry = "select name from status where id=" . $data['status'];
													db_b2b();
													$dt_view_res = db_query($qry);
													while ($myrow = array_shift($dt_view_res)) {
														$status_name = $myrow['name'];
													}

													$MGArray[] = array(
														'dateval' => $data["D"], 'status' => $status_name, 'age' => $data["D"], 'contact' => $data["C"], 'LID' => $data["LID"], 'I' => $data["I"],
														'company' => $data["CO"], 'companynn' => $data["NN"], 'phone' => $data["PH"], 'city' => $data["CI"],
														'state' => $data["ST"], 'zip' => $data["ZI"], 'nextstep' => $data["NS"], 'lastcomm' => $tmp_msg_dt, 'nextcomm' => $data["ND"],
														'assgnto' => $data["EI"], 'nooftrans' => $nooftrans, 'totrev' => $totrevenue, 'totprofit' => $emp_yr_grossprf_tot, 'profitmargin' => $profit_margin
													);
												}

												//$_SESSION['sortarrayn'] = $MGArray;

												$sort_order_pre = "ASC";
												$sort_order_arrtxt = "SORT_ASC";
												if ($_REQUEST["so"] != "") {
													if ($_REQUEST["so"] == "A") {
														$sort_order_pre = " ASC";
														$sort_order_arrtxt = "SORT_ASC";
													} else {
														$sort_order_pre = " DESC";
														$sort_order_arrtxt = "SORT_DESC";
													}
												} else {
													$sort_order_pre = " DESC";
													$sort_order_arrtxt = "SORT_DESC";
												}

												if (isset($_REQUEST["sk"])) {
													//$MGArray = $_SESSION['sortarrayn'];

													$MGArraysort_I = array();

													if ($_REQUEST["sk"] == "dt" || $_REQUEST["sk"] == "age") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['dateval'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "contact") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['contact'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "status") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['status'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "cname") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['company'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "phone") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['phone'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "city") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['city'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "state") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['state'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "zip") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['zip'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "ns") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['nextstep'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "lc") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['lastcomm'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "nd") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['nextcomm'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "ei") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['assgnto'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "nooftrans") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['nooftrans'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "totrev") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['totrev'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "totprofit") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['totprofit'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
														}
													}
													if ($_REQUEST["sk"] == "profitmargin") {
														foreach ($MGArray as $MGArraytmp) {
															$MGArraysort_I[] = $MGArraytmp['profitmargin'];
														}
														if ($sort_order_arrtxt == "SORT_ASC") {
															array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
														} else {
															array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
														}
													}
												} else {

													//$MGArray = $_SESSION['sortarrayn'];
													$MGArraysort_I = array();

													foreach ($MGArray as $MGArraytmp) {
														$MGArraysort_I[] = $MGArraytmp['dateval'];
													}
													array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
												}

												foreach ($MGArray as $MGArraytmp2) {
												?>
													<tr valign="middle">
														<td width="5%" bgcolor="#E4E4E4">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo  timestamp_to_datetime($MGArraytmp2["dateval"]);
																																												?></font>
														</td>
														<td width="5%" bgcolor="#E4E4E4">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo date_diff_new($MGArraytmp2["dateval"], "NOW");
																																												?> Days</font>
														</td>
														<td width="10%" bgcolor="#E4E4E4" align="center">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["contact"] ?></font>
														</td>
														<td width="21%" bgcolor="#E4E4E4"><a href="viewCompany.php?ID=<?php echo $MGArraytmp2["I"] ?>">
																<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																	<?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php if ($MGArraytmp2["companynn"] != "") echo $MGArraytmp2["companynn"];
																														else echo $MGArraytmp2["company"] ?><?php if ($MGArraytmp2["LID"] > 0) echo "</b>"; ?>
																</font>
															</a></td>
														<td width="10%" bgcolor="#E4E4E4" align="left">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php echo $MGArraytmp2["status"]; ?></font>
														</td>
														<td width="3%" bgcolor="#E4E4E4">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["phone"] ?></font>
														</td>
														<td width="10%" bgcolor="#E4E4E4" align="center">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["city"] ?></font>
														</td>
														<td width="5%" bgcolor="#E4E4E4" align="center">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["state"] ?></font>
														</td>
														<td width="5%" bgcolor="#E4E4E4" align="center">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["zip"] ?></font>
														</td>
														<td width="15%" bgcolor="#E4E4E4" align="center">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php echo $MGArraytmp2["nextstep"] ?></font>
														</td>
														<td width="10%" bgcolor="#E4E4E4" align="center">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php if ($MGArraytmp2["lastcomm"] != "") echo date('m/d/Y', strtotime($MGArraytmp2["lastcomm"])); ?>
														<td width="10%" <?php if ($MGArraytmp2["nextcomm"] == date('Y-m-d')) { ?> bgcolor="#00FF00" <?php } elseif ($MGArraytmp2["nextcomm"] < date('Y-m-d') && $MGArraytmp2["nextcomm"] != "") { ?> bgcolor="#FF0000" <?php } else { ?> bgcolor="#E4E4E4" <?php } ?> align="center">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php if ($MGArraytmp2["LID"] > 0) echo "<b>"; ?><?php if ($MGArraytmp2["nextcomm"] != "") echo date('m/d/Y', strtotime($MGArraytmp2["nextcomm"])); ?>
															</font>
														</td>
														<td bgcolor="#E4E4E4" align="center">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php echo $MGArraytmp2["nooftrans"] ?></font>
														</td>
														<td bgcolor="#E4E4E4" align="right">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																$<?php echo number_format($MGArraytmp2["totrev"], 2) ?></font>
														</td>
														<td bgcolor="#E4E4E4" align="right">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																$<?php echo number_format($MGArraytmp2["totprofit"], 2) ?></font>
														</td>
														<td bgcolor="#E4E4E4" align="center">
															<font face="Arial, Helvetica, sans-serif" size="1" color="#333333">
																<?php echo $MGArraytmp2["profitmargin"] ?></font>
														</td>
													</tr>

												<?php
												} ?>
											</table>

											<div><i>
													<font color="red">"END OF REPORT"</font>
												</i></div>
										<?php
										}


										?>


										<?php
										tep_db_close();
										?>

</body>

</html>