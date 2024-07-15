<?php

session_start();
// error_reporting(0);
// set_time_limit(0);

// ini_set('memory_limit', '-1');
if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>UCBZeroWaste</title>
    <!-- <link href="css/header-footer-rep.css" rel="stylesheet"> -->
    <link href="css/header-footer-rep_minified.css" rel="stylesheet">
    <!-- <link href="css/detailed-waste-report-table.css" rel="stylesheet"> -->
    <link href="css/detailed-waste-report-table_minified.css" rel="stylesheet">
    <!-- <link href="css/detailed-waste-report-table1.css" rel="stylesheet"> -->
    <link href="css/detailed-waste-report-table1_minified.css" rel="stylesheet">
    <!-- <link href="css/detailed-waste-report-reuse-table.css" rel="stylesheet"> -->
    <link href="css/detailed-waste-report-reuse-table_minified.css" rel="stylesheet">
    <link rel="shortcut icon" href="images/logo.jpg" type="image/jpg">

    <link href="https://fonts.googleapis.com/css?family=Fira+Sans+Condensed" rel="stylesheet">

    <style type="text/css">
    .black_overlay {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: gray;
        z-index: 1001;
        -moz-opacity: .8;
        opacity: .8
    }

    .white_content {
        display: none;
        position: absolute;
        padding: 5px;
        border: 2px solid #000;
        background-color: #fff;
        z-index: 1002;
        overflow: auto
    }
    </style>

    <SCRIPT TYPE="text/javascript">
    function f_getPosition(o, e) {
        for (var r, t = 0, f = o; f;) t += r = f["offset" + e], f = f.offsetParent;
        for (f = o; f != document.body;)(r = f["scroll" + e]) && "scroll" == f.style.overflow && (t -= r), f = f
            .parentNode;
        return t
    }

    function showadd_fee_remark(t, e, n, d, l) {
        document.getElementById("light").innerHTML = "<br/><br/>Loading .....<img src='images/wait_animated.gif' />",
            document.getElementById("light").style.display = "block", window.XMLHttpRequest ? xmlhttp =
            new XMLHttpRequest : xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"), xmlhttp.onreadystatechange =
            function() {
                4 == xmlhttp.readyState && 200 == xmlhttp.status && (document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a><br>" +
                    xmlhttp.responseText, selectobject = document.getElementById("remarkid" + t + e), n_left =
                    f_getPosition(selectobject, "Left"), n_top = f_getPosition(selectobject, "Top"), document
                    .getElementById("light").style.left = n_left + 20 + "px", document.getElementById("light").style
                    .top = n_top + 10 + "px")
            }, xmlhttp.open("POST", "water_show_fee_remarks.php?vendorid=" + t + "&addfeeid=" + e + "&warehouseid=" +
                n + "&startdt=" + d + "&enddt=" + l, !0), xmlhttp.send()
    }

    function showlocation_box_detail(divid) {
        selectobject = document.getElementById("location_box_detail" + divid);
        n_left = f_getPosition(selectobject, 'Left');
        n_top = f_getPosition(selectobject, 'Top');

        document.getElementById("light").style.left = n_left + 10 + "px";
        document.getElementById("light").style.top = n_top + 10 + "px";
        document.getElementById("light").innerHTML =
            "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';>Close</a>" +
            document.getElementById("locationdiv" + divid).innerHTML;

        document.getElementById("light").style.display = "block";
    }

    function loadpage() {
        document.frmrep.submit()
    }
    </SCRIPT>

    <!-- Facebook Pixel Code -->
    <script>
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1109377375928443');
    fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=1109377375928443&ev=PageView
	&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->


</head>

<body>

    <div id="light" class="white_content">
    </div>

    <div id="fade" class="black_overlay"></div>

    <?php
		require "../mainfunctions/database.php";
		require "../mainfunctions/general-functions.php";

		db();

		$companyid = 0;
		$in_parent_comp_flg = "no";
		$has_multiple_location = "no";
		//$warehouse_id = 0; 
		$company_name = "";
		$company_logo = "";
		$sql = "SELECT companyid, parent_comp_flg FROM supplierdashboard_usermaster WHERE loginid=? and activate_deactivate = 1";
		//echo $sql . "<br>";
		$result = db_query($sql, array("i"), array($_SESSION['loginid']));
		while ($myrowsel = array_shift($result)) {
			$sql1 = "SELECT logo_image FROM supplierdashboard_details WHERE companyid=? ";
			$result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
			while ($myrowsel1 = array_shift($result1)) {
				$company_logo = $myrowsel1["logo_image"];
			}

			//$warehouse_id=$_GET["warehouse_id"];
			$company_id = $_GET["parent_comp_id"];
			$company_id1 = $_GET["parent_comp_id"];
			$wcsql = "select id from loop_warehouse where b2bid=" . $company_id1;
			$wresult = db_query($wcsql);
			$wrow = array_shift($wresult);
			$wid = $wrow["id"];
			if (isset($_REQUEST["child_loc"])) {
				if ($_REQUEST["child_loc"] == "all_loc") {   //User select only all location

					if ($myrowsel["parent_comp_flg"] == 1) {
						$in_parent_comp_flg = "yes";
					}

					db_b2b();
					$vcsql = "select ID, loopid, parent_child, parent_comp_id from companyInfo where haveNeed = 'Have Boxes' and parent_comp_id=" . $company_id . " and loopid<>0";
					$vcresult = db_query($vcsql);
					while ($vcrow = array_shift($vcresult)) {
						$ch_id = $vcrow["ID"];
						$company_id .= "," . $ch_id;
					}
					db();
					$ct = 0;
					$sql1 = "SELECT id, b2bid, company_name FROM loop_warehouse WHERE b2bid IN ($company_id)";
					$result1 = db_query($sql1);
					$rNum = tep_db_num_rows($result1);
					$rnum1 = $rNum - 1;
					while ($myrowsel1 = array_shift($result1)) {
						$warehouse_id .= $myrowsel1["id"];
						$cmp_id = $myrowsel1["b2bid"];
						$ct = $ct + 1;
						if ($rNum == $ct) {
						} else {
							$warehouse_id .= ",";
						}
					}
					$parent_warehouse = $_REQUEST["warehouse_id"];
					$parent_companyid = $_REQUEST["parent_comp_id"];
				} elseif ($_REQUEST["child_loc"] != "null" && $_REQUEST["child_loc"] != "all_loc") {

					$resAllLoc = $resOthrLoc = array();
					$arrLoc = explode(',', $_REQUEST["child_loc"]);
					if (!empty(in_array('all_loc', $arrLoc))) {
						//get all loc company name
						db_b2b();
						$vcsql = "select ID, loopid, parent_child, parent_comp_id from companyInfo where haveNeed = 'Have Boxes' and parent_comp_id=" . $company_id . " and loopid<>0";
						$vcresult = db_query($vcsql);
						while ($vcrow = array_shift($vcresult)) {
							$ch_id = $vcrow["ID"];
							$company_id .= "," . $ch_id;
						}
						db();
						$ct = 0;
						$sql1 = "SELECT id, b2bid, company_name FROM loop_warehouse WHERE b2bid IN ($company_id)";
						$resAllLoc = db_query($sql1);
						//remove all_loc from selected child_loc
						$strChildLoc = str_replace('all_loc,', '', $_REQUEST["child_loc"]);
						$company_id .= "," . $strChildLoc;
					} else {
						$company_id .= "," . $_REQUEST["child_loc"];
					}
					//Get other locations company name
					db();
					$ct = 0;
					$sql1 = "SELECT id, b2bid, company_name FROM loop_warehouse WHERE b2bid IN ($company_id)";
					$resOthrLoc = db_query($sql1);

					//merge both company name array
					$result1 = array_merge($resAllLoc, $resOthrLoc);
					$rNum = tep_db_num_rows($result1);
					$rnum1 = $rNum - 1;
					while ($myrowsel1 = array_shift($result1)) {
						$warehouse_id .= $myrowsel1["id"];
						$cmp_id = $myrowsel1["b2bid"];
						$ct = $ct + 1;
						if ($rNum == $ct) {
						} else {
							$warehouse_id .= ",";
						}
					}
					$parent_warehouse = $_REQUEST["warehouse_id"];
					$parent_companyid = $_REQUEST["parent_comp_id"];
				} elseif ($_REQUEST["child_loc"] == "null") {
					//echo "<br />".$_REQUEST["child_loc"]."Break333"; exit();
					$company_id = $_GET["parent_comp_id"];
					$sql1 = "SELECT id, company_name FROM loop_warehouse WHERE b2bid=? ";
					$result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
					while ($myrowsel1 = array_shift($result1)) {
						$warehouse_id = $myrowsel1["id"];
						$company_name = $myrowsel1["company_name"] . "'s";
					}
					$parent_warehouse = $_REQUEST["warehouse_id"];
					$parent_companyid = $_REQUEST["parent_comp_id"];
				}
			}
			//$get_warehouse=$_GET["warehouse_id"];
		}

		$st_date = date("Y-m-d", strtotime($_GET["date_from"]));
		$end_date = date("Y-m-d", strtotime($_GET["date_to"]));

		$sql = "SELECT * FROM clientdashboard_globalvar WHERE variable_name = 'tollfree_no'";
		$ucb_tollfree_no = "";
		$ucb_off_no = "";
		$result = db_query($sql);
		while ($rq = array_shift($result)) {
			$ucb_tollfree_no = $rq["variable_value"];
		}

		$sql = "SELECT * FROM supplierdashboard_globalvar WHERE variable_name = 'office_no'";
		$result = db_query($sql);
		while ($rq = array_shift($result)) {
			$ucb_off_no = $rq["variable_value"];
		}
		//echo $_GET["warehouse_id"];
		if (isset($wid) != 0) {
			$sql = "SELECT b2bid FROM loop_warehouse WHERE id =" . $wid;
			$compid = 0;
			$result = db_query($sql);
			while ($rq = array_shift($result)) {
				$compid = $rq["b2bid"];
			}
		} else {
			$compid = $_GET["parent_comp_id"];
		}


		$sql = "SELECT * FROM supplierdashboard_details WHERE companyid = " . $compid;
		$supplier_logofile = "";
		$supplier_account_mgr = 0;
		$result = db_query($sql);
		while ($rq = array_shift($result)) {
			$supplier_account_mgr = $rq["accountmanager_empid"];
			$supplier_logofile = $rq["logo_image"];
		}
		db_b2b();
		$sql = "SELECT * FROM employees WHERE employeeID = " . $supplier_account_mgr;
		$supplier_account_mgr_name = "";
		$supplier_account_mgr_eml = "";
		$supplier_account_mgr_initiails = "";
		$result = db_query($sql);
		while ($rq = array_shift($result)) {
			$supplier_account_mgr_name = $rq["name"];
			$supplier_account_mgr_initiails = $rq["initials"];
			$supplier_account_mgr_eml = $rq["email"];
		}
		$comp_nm = "";
		$comp_city = "";
		$comp_state = "";
		$comp_nm_display = "";
		if ($_GET["child_loc"] == "all_loc" || $_GET["child_loc"] == "null") {
			$sql = "SELECT company, city, state FROM companyInfo WHERE ID = " . $_GET["parent_comp_id"];
			$result = db_query($sql);
			while ($rq = array_shift($result)) {
				$comp_nm = $rq["company"];
				$comp_city = $rq["city"];
				$comp_state = $rq["state"];
				$comp_nm_display = $comp_nm . "-" . $comp_city . ", " . $comp_state;
			}
		} elseif ($_GET["child_loc"] != "null" && $_GET["child_loc"] != "all_loc") {
			$arrLoc = explode(',', $_GET["child_loc"]);
			if (!empty(in_array('all_loc', $arrLoc))) {
				$_GET["child_loc"] = str_replace('all_loc,', '', $_GET["child_loc"]);
			} else {
				$_GET["child_loc"] = $_GET["child_loc"];
			}
			$sql = "SELECT company, city, state FROM companyInfo WHERE ID in (" . $_GET["child_loc"]  . ")";
			$result = db_query($sql);

			$cntRes = count($result);
			$i = 1;
			while ($rq = array_shift($result)) {
				$comp_nm = $rq["company"];
				$comp_city = $rq["city"];
				$comp_state = $rq["state"];
				$comp_nm_display .= $comp_nm . "-" . $comp_city . ", " . $comp_state . " ";
				if ($cntRes != $i) {
					if ($cntRes > $cntRes - $i) {
						$comp_nm_display .= " | ";
					}
				}
				$i++;
			}
		}
		//echo "<br>".$_GET["warehouse_id"].$comp_nm;
		db();

		$supplier_account_mgr_img = "";
		$sql = "Select phoneext, Headshot from loop_employees where b2b_id = $supplier_account_mgr";
		$supplier_account_mgr_phoneext = "";
		$result = db_query($sql);
		while ($rq = array_shift($result)) {
			$supplier_account_mgr_phoneext = $rq["phoneext"];
			$supplier_account_mgr_img = $rq["Headshot"];
		}

		?>
    <div id="wrapper">
        <div id="header">
            <div class="logo"><img src="images/UCB-logo.jpg" /></div>

            <div class="rightheader">
                <h2>Need Help? Call Us <span class="green">1-888-BOXES-88</span></h2>
                <img src="https://loops.usedcardboardboxes.com/images/employees/<?php echo $supplier_account_mgr_img; ?>"
                    alt="" width="69" height="69" class="photoimg" />
                <p>Account Manager : <?php echo $supplier_account_mgr_name; ?><br>
                    Email: <?php echo $supplier_account_mgr_eml; ?><br>
                    Office Number: <?php echo $ucb_off_no; ?><br>
                    Office Number ext: <?php echo $supplier_account_mgr_phoneext; ?> </p>
                <div class="button" align="left">Toll Free Number: <?php echo $ucb_tollfree_no; ?>

                </div>
            </div>
        </div>

        <div id="detailed-tables">
            <?php //echo "sdfsdf".$company_id; echo "<br>sdfsdf".$warehouse_id;
				?>

            <form id="frmrep" name="frmrep" action="detailed-waste-report.php" method="get">
                <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?php echo isset($warehouse_id); ?>">
                <input type="hidden" name="parent_comp_id" id="parent_comp_id"
                    value="<?php echo $_GET["parent_comp_id"]; ?>">
                <input type="hidden" name="child_loc" id="child_loc" value="<?php echo $_REQUEST["child_loc"]; ?>">
                <input type="hidden" name="date_from" id="date_from" value="<?php echo $_REQUEST["date_from"]; ?>">
                <input type="hidden" name="date_to" id="date_to" value="<?php echo $_REQUEST["date_to"]; ?>">
                <div class="header-title">
                    <div class="title-text-main">
                        <span class="column-grey1"><?php echo $comp_nm_display; ?>
                            <?php //echo $warehouse_id;
								?>
                        </span><br>
                        DETAILED WASTE REPORT<br>
                        <span
                            class="column-grey1"><?php echo Date("m/d/Y", strtotime($st_date)) . " - " . Date("m/d/Y", strtotime($end_date)); ?></span>
                    </div>

                    <div class="dropdown-menu">
                        <div class="dropdown-menu-main">
                            <div class="select">
                                <select name="rep_inweight" id="rep_inweight" onchange="loadpage()">
                                    <option value="lb" <?php if (!isset($_REQUEST["rep_inweight"])) {
																echo " selected ";
															} ?>>Display Report in Pounds</option>
                                    <option value="ton" <?php if ($_REQUEST["rep_inweight"] == "ton") {
																echo " selected ";
															} ?>>Display Report in Tons</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <?php
			$loop_trans_rec_found = "no";
			$query_mtd = "Select id from loop_transaction where warehouse_id IN (" . $warehouse_id . ") and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' limit 1";
			$result = db_query($query_mtd);
			while ($row = array_shift($result)) {
				$loop_trans_rec_found = "yes";
			}
			$qryRes = $qryRes1 = array();

			if ($loop_trans_rec_found == "yes") {

				db();
				$qryRes = db_query("SELECT loop_boxes_sort.box_id, loop_transaction.warehouse_id, loop_boxes.isbox, loop_boxes.type, loop_boxes.bwall, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth, loop_boxes.blength_frac, loop_boxes.bwidth_frac, loop_boxes.bdepth_frac, loop_boxes.vendor, loop_boxes.bdescription, sort_boxgoodvalue,  loop_boxes_sort.boxgood, loop_boxes_sort.sort_boxgoodvalue,loop_boxes_sort.boxgood, loop_boxes.bweight, loop_transaction.pr_requestdate_php, boxbad FROM loop_boxes_sort INNER JOIN loop_transaction ON loop_transaction.id = loop_boxes_sort.trans_rec_id INNER JOIN loop_boxes ON loop_boxes.id = loop_boxes_sort.box_id WHERE loop_transaction.warehouse_id IN (" . isset($warehouse_id) . ") AND loop_transaction.pr_requestdate_php BETWEEN '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59'");
			}
			$mainqry = "SELECT water_boxes_report_data.id as mainid, warehouse_id, invoice_date, water_inventory.Outlet, weight_in_pound, avg_price_per_pound, total_value, unit_count, vendor_id, water_boxes_report_data.*, water_inventory.* FROM water_boxes_report_data INNER JOIN water_inventory ON water_boxes_report_data.box_id = water_inventory.ID INNER JOIN water_transaction ON water_boxes_report_data.trans_rec_id = water_transaction.id INNER JOIN water_vendors ON water_transaction.vendor_id = water_vendors.id WHERE water_vendors.id <> 844 AND warehouse_id IN (" . isset($warehouse_id) . ") AND (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ORDER BY water_vendors.Name, water_boxes_report_data.box_id";

			db();
			$qryRes1 = db_query($mainqry);
			//echo $mainqry . "<br />";

			$outlet_array = array("Reuse", "Recycling", "Waste To Energy", "Incineration (No Energy Recovery)", "Landfill");
			$sumtot = 0;

			//$result = db_query("SELECT sum(weight_in_pound) as sumweight FROM water_boxes_report_data inner join water_transaction on water_transaction.id = water_boxes_report_data.trans_rec_id where outlet <> '' and warehouse_id = " . $_GET["warehouse_id"] . " and invoice_date between '" . $st_date . "' and '" . $end_date . "'");
			$query_mtd  = "SELECT sum(weight_in_pound) as sumweight, water_inventory.Outlet, water_inventory.tree_saved_per_ton from water_boxes_report_data inner join water_inventory on water_boxes_report_data.box_id = water_inventory.ID ";
			$query_mtd .= " inner join water_transaction on water_boxes_report_data.trans_rec_id = water_transaction.id inner join water_vendors on water_transaction.vendor_id = water_vendors.id WHERE water_vendors.id <> 844 and warehouse_id IN (" . isset($warehouse_id) . ") and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') ";
			$query_mtd .= " group by water_transaction.vendor_id, water_boxes_report_data.box_id order by water_vendors.Name, water_boxes_report_data.box_id";
			//echo $query_mtd;							
			$result = db_query($query_mtd);
			while ($row = array_shift($result)) {
				$sumtot = $sumtot + $row["sumweight"];
			}
			//echo "First " . $sumtot . "<br>";

			if ($loop_trans_rec_found == "yes") {
				$query_mtd1 = "SELECT loop_boxes.vendor, loop_boxes.bdescription, sum(sort_boxgoodvalue) as valueach, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as totamt, sum(loop_boxes_sort.boxgood)*loop_boxes.bweight as sumweight from loop_boxes_sort inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id  ";
				$query_mtd1 .= " inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id  ";
				//$query_mtd1 .= " WHERE loop_transaction.warehouse_id = " . $_GET["warehouse_id"] . " and (STR_TO_DATE(sort_date,'%m/%d/%Y') between str_to_date('" . date("m/d/Y", strtotime($st_date)) ."', '%m/%d/%Y') AND str_to_date('" . date("m/d/Y", strtotime($end_date)) . "', '%m/%d/%Y')) group by loop_boxes.vendor, loop_boxes.bdescription";
				$query_mtd1 .= " WHERE loop_transaction.warehouse_id IN ($warehouse_id) and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_boxes.vendor, loop_boxes.bdescription";
				//echo $query_mtd1 . "<br>";	
				$res1 = db_query($query_mtd1);
				while ($row_mtd1 = array_shift($res1)) {
					$sumtot = $sumtot + $row_mtd1["sumweight"];
				}
			}

			//echo "sec " . $sumtot . "<br>";
			$weight_str = "(lb)";
			if ($_REQUEST["rep_inweight"] == "ton") {
				$sumtot = $sumtot / 2000;
				$weight_str = "(Tons)";
			}

			$totalval_tot = 0;
			$weightval_tot = 0;
			$display_flg1 = "n";
			$display_flg2 = "n";
			$display_flg3 = "n";
			$arrlength = count($outlet_array);
			for ($arrycnt = 0; $arrycnt < $arrlength; $arrycnt++) {
				if ($outlet_array[$arrycnt] == "Reuse") {
					$bgcolor_td = "#92D050";
					$class_nm = "reuse";
					$class_nm1 = "";
				}
				if ($outlet_array[$arrycnt] == "Recycling") {
					$bgcolor_td = "#95B3D7";
					$class_nm = "recycling";
					$class_nm1 = "recycling-header";
				}
				if ($outlet_array[$arrycnt] == "Waste To Energy") {
					$bgcolor_td = "#FFFF00";
					$class_nm = "waste";
					$class_nm1 = "waste-header";
				}
				if ($outlet_array[$arrycnt] == "Landfill") {
					$bgcolor_td = "#df0000";
					$class_nm = "landfill";
					$class_nm1 = "landfill-header";
				}
				if ($outlet_array[$arrycnt] == "Incineration (No Energy Recovery)") {
					$bgcolor_td = "#cc6511";
					$class_nm = "incineration";
					$class_nm1 = "incineration-header";
				}
				//
				//
				$num_flag = 1;
				//
				$newArrQryRes1 = array();
				foreach ($qryRes1 as $qryRes1K => $qryRes1V) {
					if ($qryRes1V['invoice_date'] >= $st_date && $qryRes1V['invoice_date'] <= $end_date . ' 23:59:59') {
						if ($qryRes1V["Outlet"] == $outlet_array[$arrycnt]) {
							$newArrQryRes1[] = $qryRes1V;
						}
					}
				}
				//echo "<pre> newArrQryRes1 -";print_r($newArrQryRes1) ; echo "</pre>";
				$newArrQryRes1_1 = $newArrQryRes1_2 = $newArrQryRes1_3 = array();
				foreach ($newArrQryRes1 as $newArrQryRes1k => $newArrQryRes1v) {
					$newArrQryRes1_1[$newArrQryRes1v['vendor_id']][$newArrQryRes1v['box_id']][] = $newArrQryRes1v;
				}
				//echo "<pre> newArrQryRes1_1 -";print_r($newArrQryRes1_1) ; echo "</pre>";
				foreach ($newArrQryRes1_1 as $newArrQryRes1_1K => $newArrQryRes1_1V) {
					$i = 0;
					foreach ($newArrQryRes1_1V as $newArrQryRes1_1VK1 => $newArrQryRes1_1V1) {
						$weightval = $valueeachval = $totalval = $itemcount =  0;
						foreach ($newArrQryRes1_1V1 as $newArrQryRes1_1V1K => $newArrQryRes1_1V1V) {

							foreach ($newArrQryRes1_1V1V as $newArrQryRes1_1V1VKey => $newArrQryRes1_1V1VVal) {
								$weightval 	= array_sum(array_column($newArrQryRes1_1V1, 'weight_in_pound'));
								$valueeachval 	= array_sum(array_column($newArrQryRes1_1V1, 'avg_price_per_pound'));
								$totalval 	= array_sum(array_column($newArrQryRes1_1V1, 'total_value'));
								$itemcount 	= array_sum(array_column($newArrQryRes1_1V1, 'unit_count'));

								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['weightval'] = $weightval;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['valueeachval'] = $valueeachval;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['totalval'] = $totalval;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['itemcount'] = $itemcount;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['vendor_id'] = $newArrQryRes1_1V1V['vendor_id'];
								$newArrQryRes1_2[$newArrQryRes1_1K][$i][$newArrQryRes1_1V1VKey] = $newArrQryRes1_1V1VVal;
							}
						}
						$i++;
					}
				}
				$newArrQryRes1_2 = $newArrQryRes1_2;
				//echo "<br /><br /><pre>newArrQryRes1_2 -".$arrcount;print_r($newArrQryRes1_2) ; echo "</pre>";
				foreach ($newArrQryRes1_2 as $newArrQryRes1_2K => $newArrQryRes1_2V) {
					$newArrQryRes1_3[] = $newArrQryRes1_2V;
				}
				$arrcount = count($newArrQryRes1_3);
				$newArrQryRes1_4 = array();
				for ($arr = 0; $arr < $arrcount; $arr++) {
					$newArrQryRes1_4 = array_merge($newArrQryRes1_4, $newArrQryRes1_3[$arr]);
				}
				//echo "<br /><br /><pre>newArrQryRes1_4 -";print_r($newArrQryRes1_4) ; echo "</pre>";
				$resnum = tep_db_num_rows($newArrQryRes1_4);


				//echo "<br /> --------------------Recycling----------------------<br />";
				if ($outlet_array[$arrycnt] == "Recycling") {
					$boxgoodsum = 0;
					$sumweight = 0;
					$newArrQryRes = array();
					//echo "<pre> qryRes -";print_r($qryRes) ; echo "</pre>";
					foreach ($qryRes as $qryResK => $qryResV) {
						if ($qryResV['pr_requestdate_php'] >= $st_date . ' 00:00:00' && $qryResV['pr_requestdate_php'] <= $end_date . ' 23:59:59') {
							if ($qryResV["isbox"] == 'N' && $qryResV["type"]  != 'Waste-to-Energy') {
								$newArrQryRes[] = $qryResV;
							}
						}
					}
					//echo "<pre> newArrQryRes -";print_r($newArrQryRes) ; echo "</pre>";
					$newArrQryRes_1 = $newArrQryRes1_2 = $newArrQryRes1_3 = array();
					foreach ($newArrQryRes as $newArrQryResk => $newArrQryResv) {
						$newArrQryRes_1[$newArrQryResv['vendor']][$newArrQryResv['bdescription']][] = $newArrQryResv;
					}
					//echo "<pre> newArrQryRes_1 -";print_r($newArrQryRes_1) ; echo "</pre>";
					//$newArrQryRes_2 = array(); 
					$boxgoodsum = $sumweight = $valueach = $totamt = $itemcount =  0;
					//$i = 0;
					foreach ($newArrQryRes_1 as $newArrQryRes1_1K => $newArrQryRes1_1V) {
						$i = 0;
						foreach ($newArrQryRes1_1V as $newArrQryRes1_1VK1 => $newArrQryRes1_1V1) {
							$weightval = $valueeachval = $totalval = $itemcount =  0;
							$totamt = 0;
							foreach ($newArrQryRes1_1V1 as $newArrQryRes1_1V1K => $newArrQryRes1_1V1V) {
								foreach ($newArrQryRes1_1V1V as $newArrQryRes1_1V1VKey => $newArrQryRes1_1V1VVal) {
									$newArrQryRes1_2[$newArrQryRes1_1K][$i][$newArrQryRes1_1V1VKey] = $newArrQryRes1_1V1VVal;
								}
								$boxgoodsum = array_sum(array_column($newArrQryRes1_1V1, 'boxgood'));
								$bweight 	= array_unique(array_column($newArrQryRes1_1V1, 'bweight'));
								$sumweight 	= $boxgoodsum * $bweight[0];
								$valueach 	= array_sum(array_column($newArrQryRes1_1V1, 'sort_boxgoodvalue'));
								$totamtPr 	= $newArrQryRes1_1V1V['boxgood'] * $newArrQryRes1_1V1V['sort_boxgoodvalue'];
								$totamt 	= $totamt + $totamtPr;
								$boxGBsum 	= $newArrQryRes1_1V1V['boxgood'] + $newArrQryRes1_1V1V['boxbad'];
								$itemcount 	= $itemcount + $boxGBsum;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['sumweight'] = $sumweight;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['valueach'] = $valueach;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['totamt'] = $totamt;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['itemcount'] = $itemcount;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['warehouse_id'] = $newArrQryRes1_1V1V['warehouse_id'];
							}
							$i++;
						}
					}
					$newArrQryRes1_2 = $newArrQryRes1_2;
					foreach ($newArrQryRes1_2 as $newArrQryRes1_2K => $newArrQryRes1_2V) {
						$newArrQryRes1_3[] = $newArrQryRes1_2V;
					}
					$arrcount = count($newArrQryRes1_3);
					$newArrQryRes1_4 = array();
					for ($arr = 0; $arr < $arrcount; $arr++) {
						$newArrQryRes1_4 = array_merge($newArrQryRes1_4, $newArrQryRes1_3[$arr]);
					}

					$numrows = tep_db_num_rows($newArrQryRes1_4);
					if ($numrows == 0) {
						$num_flag = 0;
					} else {
						$num_flag = 1;
					}
				}
				//echo "<br /> --------------------Reuse----------------------<br />";
				if ($outlet_array[$arrycnt] == "Reuse") {
					$boxgoodsum = 0;
					$sumweight = 0;
					$newArrQryRes = array();
					//echo "<pre> qryRes -";print_r($qryRes) ; echo "</pre>";
					foreach ($qryRes as $qryResK => $qryResV) {
						if ($qryResV['pr_requestdate_php'] >= $st_date . ' 00:00:00' && $qryResV['pr_requestdate_php'] <= $end_date . ' 23:59:59') {
							if ($qryResV["isbox"] == 'Y') {
								$newArrQryRes[] = $qryResV;
							}
						}
					}
					//echo "<pre> newArrQryRes -";print_r($newArrQryRes) ; echo "</pre>";
					$newArrQryRes_1 = $newArrQryRes1_2 = $newArrQryRes1_3 = array();
					foreach ($newArrQryRes as $newArrQryResk => $newArrQryResv) {
						$newArrQryRes_1[$newArrQryResv['vendor']][$newArrQryResv['bdescription']][] = $newArrQryResv;
					}
					//echo "<pre> newArrQryRes_1 -";print_r($newArrQryRes_1) ; echo "</pre>";
					//$newArrQryRes_2 = array(); 
					$boxgoodsum = $sumweight = $valueach = $totamt = $itemcount =  0;
					//$i = 0;
					foreach ($newArrQryRes_1 as $newArrQryRes1_1K => $newArrQryRes1_1V) {
						$i = 0;
						foreach ($newArrQryRes1_1V as $newArrQryRes1_1VK1 => $newArrQryRes1_1V1) {
							$weightval = $valueeachval = $totalval = $itemcount =  0;
							$totamt = 0;
							foreach ($newArrQryRes1_1V1 as $newArrQryRes1_1V1K => $newArrQryRes1_1V1V) {
								foreach ($newArrQryRes1_1V1V as $newArrQryRes1_1V1VKey => $newArrQryRes1_1V1VVal) {
									$newArrQryRes1_2[$newArrQryRes1_1K][$i][$newArrQryRes1_1V1VKey] = $newArrQryRes1_1V1VVal;
								}
								$boxgoodsum = array_sum(array_column($newArrQryRes1_1V1, 'boxgood'));
								$bweight 	= array_unique(array_column($newArrQryRes1_1V1, 'bweight'));
								$sumweight 	= $boxgoodsum * $bweight[0];
								$valueach 	= array_sum(array_column($newArrQryRes1_1V1, 'sort_boxgoodvalue'));
								$totamtPr 	= $newArrQryRes1_1V1V['boxgood'] * $newArrQryRes1_1V1V['sort_boxgoodvalue'];
								$totamt 	= $totamt + $totamtPr;
								$boxGBsum 	= $newArrQryRes1_1V1V['boxgood'] + $newArrQryRes1_1V1V['boxbad'];
								$itemcount 	= $itemcount + $boxGBsum;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['sumweight'] = $sumweight;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['valueach'] = $valueach;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['totamt'] = $totamt;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['itemcount'] = $itemcount;
							}
							$i++;
						}
					}
					$newArrQryRes1_2 = $newArrQryRes1_2;
					foreach ($newArrQryRes1_2 as $newArrQryRes1_2K => $newArrQryRes1_2V) {
						$newArrQryRes1_3[] = $newArrQryRes1_2V;
					}
					$arrcount = count($newArrQryRes1_3);
					$newArrQryRes1_4 = array();
					for ($arr = 0; $arr < $arrcount; $arr++) {
						$newArrQryRes1_4 = array_merge($newArrQryRes1_4, $newArrQryRes1_3[$arr]);
					}

					$numrows = tep_db_num_rows($newArrQryRes1_4);
					if ($numrows == 0) {
						$num_flag = 0;
					} else {
						$num_flag = 1;
					}
				}
				//echo "<br /> ------------------Waste To Energy------------------------<br />";
				if ($outlet_array[$arrycnt] == "Waste To Energy") {
					$boxgoodsum = 0;
					$sumweight = 0;
					$newArrQryRes = array();
					//echo "<pre> qryRes -";print_r($qryRes) ; echo "</pre>";
					foreach ($qryRes as $qryResK => $qryResV) {
						if ($qryResV['pr_requestdate_php'] >= $st_date . ' 00:00:00' && $qryResV['pr_requestdate_php'] <= $end_date . ' 23:59:59') {
							if ($qryResV["type"] == 'Waste-to-Energy') {
								$newArrQryRes[] = $qryResV;
							}
						}
					}
					//echo "<pre> newArrQryRes -";print_r($newArrQryRes) ; echo "</pre>";
					$newArrQryRes_1 = $newArrQryRes1_2 = $newArrQryRes1_3 = array();
					foreach ($newArrQryRes as $newArrQryResk => $newArrQryResv) {
						$newArrQryRes_1[$newArrQryResv['vendor']][$newArrQryResv['bdescription']][] = $newArrQryResv;
					}
					//echo "<pre> newArrQryRes_1 -";print_r($newArrQryRes_1) ; echo "</pre>";
					//$newArrQryRes_2 = array(); 
					$boxgoodsum = $sumweight = $valueach = $totamt = $itemcount =  0;
					//$i = 0;
					foreach ($newArrQryRes_1 as $newArrQryRes1_1K => $newArrQryRes1_1V) {
						$i = 0;
						foreach ($newArrQryRes1_1V as $newArrQryRes1_1VK1 => $newArrQryRes1_1V1) {
							$weightval = $valueeachval = $totalval = $itemcount =  0;
							$totamt = 0;
							foreach ($newArrQryRes1_1V1 as $newArrQryRes1_1V1K => $newArrQryRes1_1V1V) {
								foreach ($newArrQryRes1_1V1V as $newArrQryRes1_1V1VKey => $newArrQryRes1_1V1VVal) {
									$newArrQryRes1_2[$newArrQryRes1_1K][$i][$newArrQryRes1_1V1VKey] = $newArrQryRes1_1V1VVal;
								}
								$boxgoodsum = array_sum(array_column($newArrQryRes1_1V1, 'boxgood'));
								$bweight 	= array_unique(array_column($newArrQryRes1_1V1, 'bweight'));
								$sumweight 	= $boxgoodsum * $bweight[0];
								$valueach 	= array_sum(array_column($newArrQryRes1_1V1, 'sort_boxgoodvalue'));
								$totamtPr 	= $newArrQryRes1_1V1V['boxgood'] * $newArrQryRes1_1V1V['sort_boxgoodvalue'];
								$totamt 	= $totamt + $totamtPr;
								$boxGBsum 	= $newArrQryRes1_1V1V['boxgood'] + $newArrQryRes1_1V1V['boxbad'];
								$itemcount 	= $itemcount + $boxGBsum;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['sumweight'] = $sumweight;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['valueach'] = $valueach;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['totamt'] = $totamt;
								$newArrQryRes1_2[$newArrQryRes1_1K][$i]['itemcount'] = $itemcount;
							}
							$i++;
						}
					}
					$newArrQryRes1_2 = $newArrQryRes1_2;
					foreach ($newArrQryRes1_2 as $newArrQryRes1_2K => $newArrQryRes1_2V) {
						$newArrQryRes1_3[] = $newArrQryRes1_2V;
					}
					$arrcount = count($newArrQryRes1_3);
					$newArrQryRes1_4 = array();
					for ($arr = 0; $arr < $arrcount; $arr++) {
						$newArrQryRes1_4 = array_merge($newArrQryRes1_4, $newArrQryRes1_3[$arr]);
					}

					$Ruse_tot = 0;
					$numrows = tep_db_num_rows($newArrQryRes1_4);
					if ($numrows == 0) {
						$num_flag = 0;
					} else {
						$num_flag = 1;
					}
				}
				//echo "<br />11111111111111111111111111111111</br>";
				if ($resnum == 0 && $num_flag == 0) {
					$outlet_tot[] = array('outlet' => $outlet_array[$arrycnt], 'tot' => "0.00", 'perc' => "0.00%", 'totval' => "0.00");
				} elseif (($outlet_array[$arrycnt] == "Incineration (No Energy Recovery)") && $resnum == 0) {
					$outlet_tot[] = array('outlet' => $outlet_array[$arrycnt], 'tot' => "0.00", 'perc' => "0.00%", 'totval' => "0.00");
				} elseif (($outlet_array[$arrycnt] == "Landfill") && $resnum == 0) {
					$outlet_tot[] = array('outlet' => $outlet_array[$arrycnt], 'tot' => "0.00", 'perc' => "0.00%", 'totval' => "0.00");
				} else {

			?>
        <div id="reuse-tables">
            <div class="header-title1">
                <div class="<?php echo isset($class_nm); ?>"><?php echo $outlet_array[$arrycnt]; ?></div>
            </div>

            <table width="100%" border="0">
                <tbody>
                    <?php
								if ($outlet_array[$arrycnt] == "Reuse") {
									$location_col_str = "";
									if ($in_parent_comp_flg == "yes") {
										$location_col_str = "<th width='14%' class='txt-left'>Location</th>";
									}
								?>
                    <tr>
                        <?php echo $location_col_str; ?>
                        <th width="23%" class="txt-left">Vendor</th>
                        <th width="36%" class="txt-left">Material</th>
                        <th width="7%">Count </th>
                        <th width="7%">Weight <?php echo $weight_str; ?></th>
                        <th width="7%">% Waste Stream</th>
                        <th width="10%">Average Price ($)/<?php echo $weight_str; ?></th>
                        <th width="10%">Total Amount</th>
                    </tr>
                    <?php } else {
									$location_col_str = "";
									if ($in_parent_comp_flg == "yes") {
										$location_col_str = "<th width='14%' class='$class_nm1'>Location</th>";
									}
								?>
                    <tr>
                        <?php echo $location_col_str; ?>
                        <td width="23%" class="<?php echo $class_nm1; ?> txt-left">Vendor</td>
                        <td width="36%" class="<?php echo $class_nm1; ?> txt-left">Material</td>
                        <td width="7%" class="<?php echo isset($class_nm1); ?> txt-left">Count</td>
                        <td width="7%" class="<?php echo isset($class_nm1); ?>">Weight <?php echo $weight_str; ?></td>
                        <td width="7%" class="<?php echo isset($class_nm1); ?>">% Waste Stream</td>
                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Average Price
                            ($)/<?php echo $weight_str; ?>
                        </td>
                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Total Amount</td>
                    </tr>

                    <?php 		}


								$last_outlet = "";
								$cntval = 0;
								$weightval = 0;
								$valueeachval = 0;
								$totalval = 0;
								$cntval_tot = 0;
								$valueeachval_tot = 0;
								$weight_tot = 0;
								$amt_tot = 0;
								$count_tot = 0;
								$tot_show = "y";
								$trans_rec_id_list = "";

								if ($outlet_array[$arrycnt] == "Recycling") {

									if ($display_flg1 == "n") {
										$boxgoodsum = 0;
										$sumweight = 0;
										$newArrQryRes = array();
										foreach ($qryRes as $qryResK => $qryResV) {
											if ($qryResV['pr_requestdate_php'] >= $st_date . ' 00:00:00' && $qryResV['pr_requestdate_php'] <= $end_date . ' 23:59:59') {
												if ($qryResV["isbox"] == 'N' && $qryResV["type"] != 'Waste-to-Energy') {
													$newArrQryRes[] = $qryResV;
												}
											}
										}
										//echo "<pre> newArrQryRes -";print_r($newArrQryRes) ; echo "</pre>";
										$newArrQryRes_1 = $newArrQryRes1_2 = $newArrQryRes1_3 = array();
										foreach ($newArrQryRes as $newArrQryResk => $newArrQryResv) {
											$newArrQryRes_1[$newArrQryResv['vendor']][$newArrQryResv['bdescription']][] = $newArrQryResv;
										}
										//echo "<pre> newArrQryRes_1 -";print_r($newArrQryRes_1) ; echo "</pre>";
										//$newArrQryRes_2 = array(); 
										$boxgoodsum = $sumweight = $valueach = $totamt = $itemcount =  0;
										//$i = 0;
										foreach ($newArrQryRes_1 as $newArrQryRes1_1K => $newArrQryRes1_1V) {
											$i = 0;
											foreach ($newArrQryRes1_1V as $newArrQryRes1_1VK1 => $newArrQryRes1_1V1) {
												$weightval = $valueeachval = $totalval = $itemcount =  0;
												$totamt = 0;
												foreach ($newArrQryRes1_1V1 as $newArrQryRes1_1V1K => $newArrQryRes1_1V1V) {
													foreach ($newArrQryRes1_1V1V as $newArrQryRes1_1V1VKey => $newArrQryRes1_1V1VVal) {
														$newArrQryRes1_2[$newArrQryRes1_1K][$i][$newArrQryRes1_1V1VKey] = $newArrQryRes1_1V1VVal;
													}
													$boxgoodsum = array_sum(array_column($newArrQryRes1_1V1, 'boxgood'));
													$bweight 	= array_unique(array_column($newArrQryRes1_1V1, 'bweight'));
													$sumweight 	= $boxgoodsum * $bweight[0];
													$valueach 	= array_sum(array_column($newArrQryRes1_1V1, 'sort_boxgoodvalue'));
													$totamtPr 	= $newArrQryRes1_1V1V['boxgood'] * $newArrQryRes1_1V1V['sort_boxgoodvalue'];
													$totamt 	= $totamt + $totamtPr;
													$boxGBsum 	= $newArrQryRes1_1V1V['boxgood'] + $newArrQryRes1_1V1V['boxbad'];
													$itemcount 	= $itemcount + $boxGBsum;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['sumweight'] = $sumweight;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['valueach'] = $valueach;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['totamt'] = $totamt;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['itemcount'] = $itemcount;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['warehouse_id'] = $newArrQryRes1_1V1V['warehouse_id'];
												}
												$i++;
											}
										}
										$newArrQryRes1_2 = $newArrQryRes1_2;
										foreach ($newArrQryRes1_2 as $newArrQryRes1_2K => $newArrQryRes1_2V) {
											$newArrQryRes1_3[] = $newArrQryRes1_2V;
										}
										$arrcount = count($newArrQryRes1_3);
										$newArrQryRes1_4 = array();
										for ($arr = 0; $arr < $arrcount; $arr++) {
											$newArrQryRes1_4 = array_merge($newArrQryRes1_4, $newArrQryRes1_3[$arr]);
										}

										$Recycling_tot = 0;
										while ($row_mtd1 = array_shift($newArrQryRes1_4)) {
											//if ($row_mtd1["totamt"] > 0)
											//{
											//$Recycling_tot = $Recycling_tot + $row_mtd1['sumweight'];
											//if ($row_mtd1['sumweight'] > 0){
											if ($_REQUEST["rep_inweight"] == "ton") {
												$weightval = $row_mtd1['sumweight'] / 2000;
											} else {
												$weightval = $row_mtd1['sumweight'];
											}
											//} 
											//if ($Recycling_tot > 0 && $display_flg1 == "n"){
											$display_flg1 = "y";
											//$weightval = $Recycling_tot;

											$weight_tot = $weight_tot + $weightval;
											$weightval_tot = $weightval_tot + $weightval;

											$totalval_tot = $totalval_tot + $row_mtd1["totamt"];
											$amt_tot = $amt_tot + $row_mtd1["totamt"];

											$vendor_name = "UsedCardboardBoxes";
											/*$res_child = db_query("Select Name from vendors where id = " . $row_mtd1["vendor"] , db_b2b());
									while($row_child = array_shift($res_child))
									{								
										$vendor_name = $row_child["Name"];
									}*/

											//For the multiple case
											$multiple_location_cnt = 0;
											$location_nm = "";
											$multiple_tabl_str = "";
											$weightval_multiple = 0;
											$weightval_multiple_tot = 0;
											$total_val_to = 0;
											if ($in_parent_comp_flg == "yes") {
												$child_sql = "SELECT loop_transaction.warehouse_id, loop_boxes.isbox, loop_boxes.type, loop_boxes.bwall, loop_boxes.blength, loop_boxes.bwidth, 
										loop_boxes.bdepth, loop_boxes.blength_frac, loop_boxes.bwidth_frac, loop_boxes.bdepth_frac, loop_boxes.vendor, loop_boxes.bdescription, 
										sort_boxgoodvalue,  loop_boxes_sort.boxgood, loop_boxes_sort.sort_boxgoodvalue, loop_boxes.bweight, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as total_value,
										sum(loop_boxes_sort.boxgood * loop_boxes.bweight) as weightval_multiple, loop_transaction.pr_requestdate_php, boxbad, sum(loop_boxes_sort.boxgood+loop_boxes_sort.boxbad) as unit_count FROM loop_boxes_sort INNER JOIN loop_transaction ON loop_transaction.id = loop_boxes_sort.trans_rec_id 
										INNER JOIN loop_boxes ON loop_boxes.id = loop_boxes_sort.box_id WHERE loop_transaction.warehouse_id IN (" . isset($warehouse_id) . ") and loop_boxes.id = " . $row_mtd1["box_id"] . "
										and loop_boxes_sort.boxgood > 0 AND loop_transaction.pr_requestdate_php BETWEEN '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_transaction.warehouse_id";

												db();
												$res_child = db_query($child_sql);
												//echo $child_sql . "<br>";

												while ($row_child = array_shift($res_child)) {
													if ($_REQUEST["rep_inweight"] == "ton") {
														$weightval_multiple	= $row_child['weightval_multiple'] / 2000;
													} else {
														$weightval_multiple	= $row_child['weightval_multiple'];
													}
													$weightval_multiple_tot = $weightval_multiple_tot + str_replace(",", "", number_format($weightval_multiple, 2));

													$location_nm = getnickname_warehouse_new('', $row_child["warehouse_id"]);
													$multiple_location_cnt = $multiple_location_cnt + 1;

													$multiple_tabl_str .= "<tr><td class='txt-left' >$location_nm</td><td class='txt-left' >UsedCardboardBoxes</td>
											<td class='txt-left' >" . $row_child['bdescription'] . "</td>";
													if ($outlet_array[$arrycnt] == 'Reuse') {
														if ($row_child['unit_count'] > 0) {
															//$count_tot = $count_tot + $row_child['unit_count'];

															$multiple_tabl_str .= "<td class='txt-right'>" . number_format($row_child['unit_count'], 0) . "</td>";
														} else {
															$multiple_tabl_str .= "<td >&nbsp;</td>";
														}
														$multiple_tabl_str .= "<td class='txt-right'>" . number_format($weightval_multiple, 2) . "</td>";
													} else {
														$multiple_tabl_str .= "<td colspan='2' class='txt-right'>" . number_format($weightval_multiple, 2) . "</td>";
													}

													if ($sumtot > 0) {
														$multiple_tabl_str .= "<td class='txt-right'>" . number_format(($weightval_multiple / $sumtot) * 100, 2) . '%' . "</td>";
													} else {
														$multiple_tabl_str .= "<td >&nbsp;</td>";
													}

													if ($weightval_multiple > 0) {
														$multiple_tabl_str .= "<td class='txt-right'>$" . number_format(($row_child['total_value'] / $weightval_multiple), 2) . "</td>";
													} else {
														$multiple_tabl_str .= "<td >&nbsp;</td>";
													}
													$multiple_tabl_str .= "<td class='txt-right'>$" . number_format($row_child['total_value'], 2) . "</td>";
													$total_val_to = $total_val_to + str_replace(",", "", number_format($row_child['total_value'], 2));

													$multiple_tabl_str .= "</tr>";

													db();
												}
												if ($total_val_to < 0) {
													$multiple_tabl_str .= "<tr><td colspan='3' class='txt-right'><b>Total:</b></td><td class='txt-right' colspan='2'><b>" . number_format($weightval_multiple_tot, 2) . "</b></td><td >&nbsp;</td><td >&nbsp;</td><td class='txt-align'><b>$" . number_format($total_val_to, 2) . "</b></td></tr>";
												} else {
													$multiple_tabl_str .= "<tr><td colspan='3' class='txt-right'><b>Total:</b></td><td class='txt-right' colspan='2'><b>" . number_format($weightval_multiple_tot, 2) . "</b></td><td >&nbsp;</td><td >&nbsp;</td><td class='txt-right'><b>$" . number_format($total_val_to, 2) . "</b></td></tr>";
												}

												if ($location_nm == "") {
													$location_nm = getnickname_warehouse_new('', $row_mtd1["warehouse_id"]);
												}
											}
											//For the Multiple case				

									?>
                    <tr>

                        <?php if ($in_parent_comp_flg == "yes") { ?>
                        <td class="txt-left">
                            <?php if ($multiple_location_cnt > 1) { ?>
                            <a href='#' id="location_box_detail<?php echo $row_mtd1["box_id"]; ?>"
                                onclick='showlocation_box_detail(<?php echo $row_mtd1["box_id"]; ?>); return false;'>
                                Multiple</a>
                            <div id="locationdiv<?php echo $row_mtd1["box_id"]; ?>" style="display:none;">
                                <table>
                                    <?php
																	if ($outlet_array[$arrycnt] == "Reuse") {
																		$location_col_str = "";
																		if ($in_parent_comp_flg == "yes") {
																			$location_col_str = "<th width='14%' class='txt-left'>Location</th>";
																		}
																	?>
                                    <tr>
                                        <?php echo $location_col_str; ?>
                                        <th width="23%" class="txt-left">Vendor</th>
                                        <th width="36%" class="txt-left">Material</th>
                                        <th width="7%">Count </th>
                                        <th width="7%">Weight <?php echo $weight_str; ?></th>
                                        <th width="7%">% Waste Stream</th>
                                        <th width="10%">Average Price ($)/<?php echo $weight_str; ?></th>
                                        <th width="10%">Total Amount</th>
                                    </tr>
                                    <?php } else {
																		$location_col_str = "";
																		if ($in_parent_comp_flg == "yes") {
																			$location_col_str = "<th width='14%' class='$class_nm1'>Location</th>";
																		}
																	?>
                                    <tr>
                                        <?php echo $location_col_str; ?>
                                        <td width="23%" class="<?php echo isset($class_nm1); ?> txt-left">Vendor</td>
                                        <td width="36%" class="<?php echo isset($class_nm1); ?> txt-left">Material</td>
                                        <td colspan="2" class="<?php echo isset($class_nm1); ?>" width="14%">Weight
                                            <?php echo $weight_str; ?></td>
                                        <td width="7%" class="<?php echo isset($class_nm1); ?>">% Waste Stream</td>
                                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Average Price
                                            ($)/<?php echo $weight_str; ?></td>
                                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Total Amount</td>
                                    </tr>

                                    <?php } ?>
                                    <?php echo $multiple_tabl_str; ?>

                                </table>
                            </div>
                            <?php } else { ?>
                            <?php echo $location_nm; ?>
                            <?php } ?>
                        </td>
                        <?php } ?>

                        <td class="txt-left"><?php echo $vendor_name; ?></td>
                        <td class="txt-left">
                            <?php echo $row_mtd1["bdescription"]; ?>
                        </td>

                        <?php if ($row_mtd1["itemcount"] > 0) { ?>
                        <td><?php echo number_format($row_mtd1["itemcount"], 0); ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>

                        <?php if ($_REQUEST["rep_inweight"] == "ton") { ?>
                        <td colspan="2"><?php echo number_format($weightval, 2); ?></td>
                        <?php } else { ?>
                        <td colspan="2"><?php echo number_format($weightval, 0); ?></td>
                        <?php } ?>
                        <?php
												if ($sumtot > 0) { ?>
                        <td><?php echo number_format(($weightval / $sumtot) * 100, 2) . "%"; ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>
                        <?php
												//$row_mtd1["valueach"]
												if ($weightval > 0) { ?>
                        <td>$<?php echo number_format($row_mtd1["totamt"] / $weightval, 2); ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>

                        <?php if ($row_mtd1["totamt"] < 0) { ?>
                        <td>$<?php echo number_format($row_mtd1["totamt"], 2); ?></td>
                        <?php } else { ?>
                        <td>$<?php echo number_format($row_mtd1["totamt"], 2); ?></td>
                        <?php } ?>
                    </tr>
                    <?php
											//}
										}
									}
								} else if ($outlet_array[$arrycnt] == "Reuse") {

									if ($display_flg2 == "n") {
										$boxgoodsum = 0;
										$sumweight = 0;
										$newArrQryRes = array();
										foreach ($qryRes as $qryResK => $qryResV) {
											if ($qryResV['pr_requestdate_php'] >= $st_date . ' 00:00:00' && $qryResV['pr_requestdate_php'] <= $end_date . ' 23:59:59') {
												if ($qryResV["isbox"] == 'Y') {
													$newArrQryRes[] = $qryResV;
												}
											}
										}
										//echo "<pre> newArrQryRes -";print_r($newArrQryRes) ; echo "</pre>";
										$newArrQryRes_1 = $newArrQryRes1_2 = $newArrQryRes1_3 = array();
										foreach ($newArrQryRes as $newArrQryResk => $newArrQryResv) {
											$newArrQryRes_1[$newArrQryResv['vendor']][$newArrQryResv['bdescription']][] = $newArrQryResv;
										}
										//echo "<pre> newArrQryRes_1 -";print_r($newArrQryRes_1) ; echo "</pre>";
										//$newArrQryRes_2 = array(); 
										$boxgoodsum = $sumweight = $valueach = $totamt = $itemcount =  0;
										//$i = 0;
										foreach ($newArrQryRes_1 as $newArrQryRes1_1K => $newArrQryRes1_1V) {
											$i = 0;
											foreach ($newArrQryRes1_1V as $newArrQryRes1_1VK1 => $newArrQryRes1_1V1) {
												$weightval = $valueeachval = $totalval = $itemcount =  0;
												$totamt = 0;
												foreach ($newArrQryRes1_1V1 as $newArrQryRes1_1V1K => $newArrQryRes1_1V1V) {
													foreach ($newArrQryRes1_1V1V as $newArrQryRes1_1V1VKey => $newArrQryRes1_1V1VVal) {
														$newArrQryRes1_2[$newArrQryRes1_1K][$i][$newArrQryRes1_1V1VKey] = $newArrQryRes1_1V1VVal;
													}
													$boxgoodsum = array_sum(array_column($newArrQryRes1_1V1, 'boxgood'));
													$bweight 	= array_unique(array_column($newArrQryRes1_1V1, 'bweight'));
													$sumweight 	= $boxgoodsum * $bweight[0];
													$valueach 	= array_sum(array_column($newArrQryRes1_1V1, 'sort_boxgoodvalue'));
													$totamtPr 	= $newArrQryRes1_1V1V['boxgood'] * $newArrQryRes1_1V1V['sort_boxgoodvalue'];
													$totamt 	= $totamt + $totamtPr;
													$boxGBsum 	= $newArrQryRes1_1V1V['boxgood'] + $newArrQryRes1_1V1V['boxbad'];
													$itemcount 	= $itemcount + $boxGBsum;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['sumweight'] = $sumweight;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['valueach'] = $valueach;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['totamt'] = $totamt;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['itemcount'] = $itemcount;
												}
												$i++;
											}
										}
										$newArrQryRes1_2 = $newArrQryRes1_2;
										foreach ($newArrQryRes1_2 as $newArrQryRes1_2K => $newArrQryRes1_2V) {
											$newArrQryRes1_3[] = $newArrQryRes1_2V;
										}
										$arrcount = count($newArrQryRes1_3);
										$newArrQryRes1_4 = array();
										for ($arr = 0; $arr < $arrcount; $arr++) {
											$newArrQryRes1_4 = array_merge($newArrQryRes1_4, $newArrQryRes1_3[$arr]);
										}
										//echo "<br /><br /><pre>newArrQryRes1_4 -";print_r($newArrQryRes1_4) ; echo "</pre>";
										//***

										$Ruse_tot = 0;
										while ($row_mtd1 = array_shift($newArrQryRes1_4)) {
											//if ($row_mtd1["totamt"] > 0)
											//{
											//$Ruse_tot = $Ruse_tot + $row_mtd1['sumweight'];
											//if ($row_mtd1['sumweight'] > 0){
											if ($_REQUEST["rep_inweight"] == "ton") {
												$weightval = $row_mtd1['sumweight'] / 2000;
											} else {
												$weightval = $row_mtd1['sumweight'];
											}
											//echo "sumweight = " . $row_mtd1['sumweight'] . "<br>";

											$display_flg2 = "y";

											//$weightval = $Ruse_tot;

											$weight_tot = $weight_tot + $weightval;
											$weightval_tot = $weightval_tot + $weightval;

											$totalval_tot = $totalval_tot + $row_mtd1["totamt"];
											$amt_tot = $amt_tot + $row_mtd1["totamt"];

											$vendor_name = "UsedCardboardBoxes";
											/*$res_child = db_query("Select Name from vendors where id = " . $row_mtd1["vendor"] , db_b2b());
									while($row_child = array_shift($res_child))
									{								
										$vendor_name = $row_child["Name"];
									}*/
											$count_tot = $count_tot + $row_mtd1["itemcount"];

											if ($weightval > 0 || $row_mtd1["itemcount"] > 0) {
												//For the multiple case
												$multiple_location_cnt = 0;
												$location_nm = "";
												$multiple_tabl_str = "";
												$weightval_multiple = 0;
												$weightval_multiple_tot = 0;
												$total_val_to = 0;
												if ($in_parent_comp_flg == "yes") {
													$child_sql = "SELECT loop_transaction.warehouse_id, loop_boxes.isbox, loop_boxes.type, loop_boxes.bwall, loop_boxes.blength, loop_boxes.bwidth, 
											loop_boxes.bdepth, loop_boxes.blength_frac, loop_boxes.bwidth_frac, loop_boxes.bdepth_frac, loop_boxes.vendor, loop_boxes.bdescription, 
											sort_boxgoodvalue,  loop_boxes_sort.boxgood, loop_boxes_sort.sort_boxgoodvalue, loop_boxes.bweight, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as total_value,
											sum(loop_boxes_sort.boxgood * loop_boxes.bweight) as weightval_multiple, loop_transaction.pr_requestdate_php, boxbad, sum(loop_boxes_sort.boxgood+loop_boxes_sort.boxbad) as unit_count FROM loop_boxes_sort INNER JOIN loop_transaction ON loop_transaction.id = loop_boxes_sort.trans_rec_id 
											INNER JOIN loop_boxes ON loop_boxes.id = loop_boxes_sort.box_id WHERE loop_transaction.warehouse_id IN (" . isset($warehouse_id) . ") and loop_boxes.id = " . $row_mtd1["box_id"] . "
											and loop_boxes_sort.boxgood > 0 AND loop_transaction.pr_requestdate_php BETWEEN '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_transaction.warehouse_id";
													db();
													$res_child = db_query($child_sql);
													//echo $child_sql . "<br>";

													while ($row_child = array_shift($res_child)) {
														if ($_REQUEST["rep_inweight"] == "ton") {
															$weightval_multiple	= $row_child['weightval_multiple'] / 2000;
														} else {
															$weightval_multiple	= $row_child['weightval_multiple'];
														}
														$weightval_multiple_tot = $weightval_multiple_tot + str_replace(",", "", number_format($weightval_multiple, 2));

														$location_nm = getnickname_warehouse_new('', $row_child["warehouse_id"]);
														$multiple_location_cnt = $multiple_location_cnt + 1;

														$multiple_tabl_str .= "<tr><td class='txt-left' >$location_nm</td><td class='txt-left' >UsedCardboardBoxes</td>
												<td class='txt-left' >" . $row_child['bdescription'] . "</td>";
														if ($outlet_array[$arrycnt] == 'Reuse') {
															if ($row_child['unit_count'] > 0) {
																//$count_tot = $count_tot + $row_child['unit_count'];

																$multiple_tabl_str .= "<td class='txt-right'>" . number_format($row_child['unit_count'], 0) . "</td>";
															} else {
																$multiple_tabl_str .= "<td >&nbsp;</td>";
															}
															$multiple_tabl_str .= "<td class='txt-right'>" . number_format($weightval_multiple, 2) . "</td>";
														} else {
															$multiple_tabl_str .= "<td colspan='2' class='txt-right'>" . number_format($weightval_multiple, 2) . "</td>";
														}

														if ($sumtot > 0) {
															$multiple_tabl_str .= "<td class='txt-right'>" . number_format(($weightval_multiple / $sumtot) * 100, 2) . '%' . "</td>";
														} else {
															$multiple_tabl_str .= "<td >&nbsp;</td>";
														}

														if ($weightval_multiple > 0) {
															$multiple_tabl_str .= "<td class='txt-right'>$" . number_format(($row_child['total_value'] / $weightval_multiple), 2) . "</td>";
														} else {
															$multiple_tabl_str .= "<td >&nbsp;</td>";
														}
														$multiple_tabl_str .= "<td class='txt-right'>$" . number_format($row_child['total_value'], 2) . "</td>";
														$total_val_to = $total_val_to + str_replace(",", "", number_format($row_child['total_value'], 2));

														$multiple_tabl_str .= "</tr>";

														db();
													}
													if ($total_val_to < 0) {
														$multiple_tabl_str .= "<tr><td colspan='3' class='txt-right'><b>Total:</b></td><td class='txt-right' colspan='2'><b>" . number_format($weightval_multiple_tot, 2) . "</b></td><td >&nbsp;</td><td >&nbsp;</td><td class='txt-align'><b>$" . number_format($total_val_to, 2) . "</b></td></tr>";
													} else {
														$multiple_tabl_str .= "<tr><td colspan='3' class='txt-right'><b>Total:</b></td><td class='txt-right' colspan='2'><b>" . number_format($weightval_multiple_tot, 2) . "</b></td><td >&nbsp;</td><td >&nbsp;</td><td class='txt-right'><b>$" . number_format($total_val_to, 2) . "</b></td></tr>";
													}

													if ($location_nm == "") {
														$location_nm = getnickname_warehouse_new('', $row_mtd1["warehouse_id"]);
													}
												}
												//For the Multiple case				

											?>
                    <tr>

                        <?php if ($in_parent_comp_flg == "yes") { ?>
                        <td class="txt-left">
                            <?php if ($multiple_location_cnt > 1) { ?>
                            <a href='#' id="location_box_detail<?php echo $row_mtd1["box_id"]; ?>"
                                onclick='showlocation_box_detail(<?php echo $row_mtd1["box_id"]; ?>); return false;'>
                                Multiple</a>
                            <div id="locationdiv<?php echo $row_mtd1["box_id"]; ?>" style="display:none;">
                                <table>
                                    <?php
																		if ($outlet_array[$arrycnt] == "Reuse") {
																			$location_col_str = "";
																			if ($in_parent_comp_flg == "yes") {
																				$location_col_str = "<th width='14%' class='txt-left'>Location</th>";
																			}
																		?>
                                    <tr>
                                        <?php echo $location_col_str; ?>
                                        <th width="23%" class="txt-left">Vendor</th>
                                        <th width="36%" class="txt-left">Material</th>
                                        <th width="7%">Count </th>
                                        <th width="7%">Weight <?php echo $weight_str; ?></th>
                                        <th width="7%">% Waste Stream</th>
                                        <th width="10%">Average Price ($)/<?php echo $weight_str; ?></th>
                                        <th width="10%">Total Amount</th>
                                    </tr>
                                    <?php } else {
																			$location_col_str = "";
																			if ($in_parent_comp_flg == "yes") {
																				$location_col_str = "<th width='14%' class='$class_nm1'>Location</th>";
																			}
																		?>
                                    <tr>
                                        <?php echo $location_col_str; ?>
                                        <td width="23%" class="<?php echo isset($class_nm1); ?> txt-left">Vendor</td>
                                        <td width="36%" class="<?php echo isset($class_nm1); ?> txt-left">Material</td>
                                        <td colspan="2" class="<?php echo isset($class_nm1); ?>" width="14%">Weight
                                            <?php echo $weight_str; ?></td>
                                        <td width="7%" class="<?php echo isset($class_nm1); ?>">% Waste Stream</td>
                                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Average Price
                                            ($)/<?php echo $weight_str; ?></td>
                                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Total Amount</td>
                                    </tr>

                                    <?php } ?>
                                    <?php echo $multiple_tabl_str; ?>

                                </table>
                            </div>
                            <?php } else { ?>
                            <?php echo $location_nm; ?>
                            <?php } ?>
                        </td>
                        <?php } ?>

                        <td class="txt-left"><?php echo $vendor_name; ?></td>
                        <td class="txt-left">
                            <?php if ($row_mtd1["blength"] != "1" && $row_mtd1["bwidth"] != "1") { ?>
                            <?php echo $row_mtd1["blength"]; ?> <?php echo $row_mtd1["blength_frac"]; ?> x
                            <?php echo $row_mtd1["bwidth"]; ?> <?php echo $row_mtd1["bwidth_frac"]; ?> x
                            <?php echo $row_mtd1["bdepth"]; ?> <?php echo $row_mtd1["bdepth_frac"]; ?>
                            <?php
															if ($row_mtd1["bwall"] > 1) {
																echo " " . $row_mtd1["bwall"] . "-Wall ";
															}
															?>
                            <?php } ?>
                            <?php echo $row_mtd1["bdescription"]; ?>
                        </td>
                        <?php if ($row_mtd1["itemcount"] > 0) { ?>
                        <td><?php echo number_format($row_mtd1["itemcount"], 0); ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>

                        <?php if ($_REQUEST["rep_inweight"] == "ton") { ?>
                        <td><?php echo number_format($weightval, 2); ?></td>
                        <?php } else { ?>
                        <td><?php echo number_format($weightval, 0); ?></td>
                        <?php } ?>
                        <?php
													if ($sumtot > 0) { ?>
                        <td><?php echo number_format(($weightval / $sumtot) * 100, 2) . "%"; ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>
                        <?php
													//$row_mtd1["valueach"]
													if ($weightval > 0) { ?>
                        <td>$<?php echo number_format($row_mtd1["totamt"] / $weightval, 2); ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>

                        <?php if ($row_mtd1["totamt"] < 0) { ?>
                        <td>$<?php echo number_format($row_mtd1["totamt"], 2); ?></td>
                        <?php } else { ?>
                        <td>$<?php echo number_format($row_mtd1["totamt"], 2); ?></td>
                        <?php } ?>
                    </tr>
                    <?php
											}
											//}
										}
									}
								} else if ($outlet_array[$arrycnt] == "Waste To Energy") {

									if ($display_flg3 == "n") {
										$boxgoodsum = 0;
										$sumweight = 0;
										$newArrQryRes = array();
										foreach ($qryRes as $qryResK => $qryResV) {
											if ($qryResV['pr_requestdate_php'] >= $st_date . ' 00:00:00' && $qryResV['pr_requestdate_php'] <= $end_date . ' 23:59:59') {
												if ($qryResV["type"] == 'Waste-to-Energy') {
													$newArrQryRes[] = $qryResV;
												}
											}
										}
										//echo "<pre> newArrQryRes -";print_r($newArrQryRes) ; echo "</pre>";
										$newArrQryRes_1 = $newArrQryRes1_2 = $newArrQryRes1_3 = array();
										foreach ($newArrQryRes as $newArrQryResk => $newArrQryResv) {
											$newArrQryRes_1[$newArrQryResv['vendor']][$newArrQryResv['bdescription']][] = $newArrQryResv;
										}
										//echo "<pre> newArrQryRes_1 -";print_r($newArrQryRes_1) ; echo "</pre>";
										//$newArrQryRes_2 = array(); 
										$boxgoodsum = $sumweight = $valueach = $totamt = $itemcount =  0;
										//$i = 0;
										foreach ($newArrQryRes_1 as $newArrQryRes1_1K => $newArrQryRes1_1V) {
											$i = 0;
											foreach ($newArrQryRes1_1V as $newArrQryRes1_1VK1 => $newArrQryRes1_1V1) {
												$weightval = $valueeachval = $totalval = $itemcount =  0;
												$totamt = 0;
												foreach ($newArrQryRes1_1V1 as $newArrQryRes1_1V1K => $newArrQryRes1_1V1V) {
													foreach ($newArrQryRes1_1V1V as $newArrQryRes1_1V1VKey => $newArrQryRes1_1V1VVal) {
														$newArrQryRes1_2[$newArrQryRes1_1K][$i][$newArrQryRes1_1V1VKey] = $newArrQryRes1_1V1VVal;
													}
													$boxgoodsum = array_sum(array_column($newArrQryRes1_1V1, 'boxgood'));
													$bweight 	= array_unique(array_column($newArrQryRes1_1V1, 'bweight'));
													$sumweight 	= $boxgoodsum * $bweight[0];
													$valueach 	= array_sum(array_column($newArrQryRes1_1V1, 'sort_boxgoodvalue'));
													$totamtPr 	= $newArrQryRes1_1V1V['boxgood'] * $newArrQryRes1_1V1V['sort_boxgoodvalue'];
													$totamt 	= $totamt + $totamtPr;
													$boxGBsum 	= $newArrQryRes1_1V1V['boxgood'] + $newArrQryRes1_1V1V['boxbad'];
													$itemcount 	= $itemcount + $boxGBsum;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['sumweight'] = $sumweight;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['valueach'] = $valueach;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['totamt'] = $totamt;
													$newArrQryRes1_2[$newArrQryRes1_1K][$i]['itemcount'] = $itemcount;
												}
												$i++;
											}
										}
										$newArrQryRes1_2 = $newArrQryRes1_2;
										foreach ($newArrQryRes1_2 as $newArrQryRes1_2K => $newArrQryRes1_2V) {
											$newArrQryRes1_3[] = $newArrQryRes1_2V;
										}
										$arrcount = count($newArrQryRes1_3);
										$newArrQryRes1_4 = array();
										for ($arr = 0; $arr < $arrcount; $arr++) {
											$newArrQryRes1_4 = array_merge($newArrQryRes1_4, $newArrQryRes1_3[$arr]);
										}
										//echo "<br /><br /><pre>newArrQryRes1_4 -";print_r($newArrQryRes1_4) ; echo "</pre>";
										//***

										$Ruse_tot = 0;
										while ($row_mtd1 = array_shift($newArrQryRes1_4)) {
											//if ($row_mtd1["totamt"] > 0)
											//{
											//$Ruse_tot = $Ruse_tot + $row_mtd1['sumweight'];
											//if ($row_mtd1['sumweight'] > 0){
											if ($_REQUEST["rep_inweight"] == "ton") {
												$weightval = $row_mtd1['sumweight'] / 2000;
											} else {
												$weightval = $row_mtd1['sumweight'];
											}

											$display_flg3 = "y";

											//$weightval = $Ruse_tot;

											$weight_tot = $weight_tot + $weightval;
											$weightval_tot = $weightval_tot + $weightval;

											$totalval_tot = $totalval_tot + $row_mtd1["totamt"];
											$amt_tot = $amt_tot + $row_mtd1["totamt"];

											$vendor_name = "UsedCardboardBoxes";
											/*$res_child = db_query("Select Name from vendors where id = " . $row_mtd1["vendor"] , db_b2b());
									while($row_child = array_shift($res_child))
									{								
										$vendor_name = $row_child["Name"];
									}*/
											$count_tot = $count_tot + $row_mtd1["itemcount"];

											//For the multiple case
											$multiple_location_cnt = 0;
											$location_nm = "";
											$multiple_tabl_str = "";
											$weightval_multiple = 0;
											$weightval_multiple_tot = 0;
											$total_val_to = 0;
											if ($in_parent_comp_flg == "yes") {
												$child_sql = "SELECT loop_transaction.warehouse_id, loop_boxes.isbox, loop_boxes.type, loop_boxes.bwall, loop_boxes.blength, loop_boxes.bwidth, 
										loop_boxes.bdepth, loop_boxes.blength_frac, loop_boxes.bwidth_frac, loop_boxes.bdepth_frac, loop_boxes.vendor, loop_boxes.bdescription, 
										sort_boxgoodvalue,  loop_boxes_sort.boxgood, loop_boxes_sort.sort_boxgoodvalue, loop_boxes.bweight, sum(loop_boxes_sort.boxgood * loop_boxes_sort.sort_boxgoodvalue) as total_value,
										sum(loop_boxes_sort.boxgood * loop_boxes.bweight) as weightval_multiple, loop_transaction.pr_requestdate_php, boxbad, sum(loop_boxes_sort.boxgood+loop_boxes_sort.boxbad) as unit_count FROM loop_boxes_sort INNER JOIN loop_transaction ON loop_transaction.id = loop_boxes_sort.trans_rec_id 
										INNER JOIN loop_boxes ON loop_boxes.id = loop_boxes_sort.box_id WHERE loop_transaction.warehouse_id IN (" . isset($warehouse_id) . ") and loop_boxes.id = " . $row_mtd1["box_id"] . "
										and loop_boxes_sort.boxgood > 0 AND loop_transaction.pr_requestdate_php BETWEEN '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59' group by loop_transaction.warehouse_id";
												db();
												$res_child = db_query($child_sql);
												//echo $child_sql . "<br>";

												while ($row_child = array_shift($res_child)) {
													if ($_REQUEST["rep_inweight"] == "ton") {
														$weightval_multiple	= $row_child['weightval_multiple'] / 2000;
													} else {
														$weightval_multiple	= $row_child['weightval_multiple'];
													}
													$weightval_multiple_tot = $weightval_multiple_tot + str_replace(",", "", number_format($weightval_multiple, 2));

													$location_nm = getnickname_warehouse_new('', $row_child["warehouse_id"]);
													$multiple_location_cnt = $multiple_location_cnt + 1;

													$multiple_tabl_str .= "<tr><td class='txt-left' >$location_nm</td><td class='txt-left' >UsedCardboardBoxes</td>
											<td class='txt-left' >" . $row_child['bdescription'] . "</td>";
													if ($outlet_array[$arrycnt] == 'Reuse') {
														if ($row_child['unit_count'] > 0) {
															//$count_tot = $count_tot + $row_child['unit_count'];

															$multiple_tabl_str .= "<td class='txt-right'>" . number_format($row_child['unit_count'], 0) . "</td>";
														} else {
															$multiple_tabl_str .= "<td >&nbsp;</td>";
														}
														$multiple_tabl_str .= "<td class='txt-right'>" . number_format($weightval_multiple, 2) . "</td>";
													} else {
														$multiple_tabl_str .= "<td colspan='2' class='txt-right'>" . number_format($weightval_multiple, 2) . "</td>";
													}

													if ($sumtot > 0) {
														$multiple_tabl_str .= "<td class='txt-right'>" . number_format(($weightval_multiple / $sumtot) * 100, 2) . '%' . "</td>";
													} else {
														$multiple_tabl_str .= "<td >&nbsp;</td>";
													}

													if ($weightval_multiple > 0) {
														$multiple_tabl_str .= "<td class='txt-right'>$" . number_format(($row_child['total_value'] / $weightval_multiple), 2) . "</td>";
													} else {
														$multiple_tabl_str .= "<td >&nbsp;</td>";
													}
													$multiple_tabl_str .= "<td class='txt-right'>$" . number_format($row_child['total_value'], 2) . "</td>";
													$total_val_to = $total_val_to + str_replace(",", "", number_format($row_child['total_value'], 2));

													$multiple_tabl_str .= "</tr>";

													db();
												}
												if ($total_val_to < 0) {
													$multiple_tabl_str .= "<tr><td colspan='3' class='txt-right'><b>Total:</b></td><td class='txt-right' colspan='2'><b>" . number_format($weightval_multiple_tot, 2) . "</b></td><td >&nbsp;</td><td >&nbsp;</td><td class='txt-align'><b>$" . number_format($total_val_to, 2) . "</b></td></tr>";
												} else {
													$multiple_tabl_str .= "<tr><td colspan='3' class='txt-right'><b>Total:</b></td><td class='txt-right' colspan='2'><b>" . number_format($weightval_multiple_tot, 2) . "</b></td><td >&nbsp;</td><td >&nbsp;</td><td class='txt-right'><b>$" . number_format($total_val_to, 2) . "</b></td></tr>";
												}

												if ($location_nm == "") {
													$location_nm = getnickname_warehouse_new('', $row_mtd1["warehouse_id"]);
												}
											}
											//For the Multiple case				

											?>
                    <tr>

                        <?php if ($in_parent_comp_flg == "yes") { ?>
                        <td class="txt-left">
                            <?php if ($multiple_location_cnt > 1) { ?>
                            <a href='#' id="location_box_detail<?php echo $row_mtd1["box_id"]; ?>"
                                onclick='showlocation_box_detail(<?php echo $row_mtd1["box_id"]; ?>); return false;'>
                                Multiple</a>
                            <div id="locationdiv<?php echo $row_mtd1["box_id"]; ?>" style="display:none;">
                                <table>
                                    <?php
																	if ($outlet_array[$arrycnt] == "Reuse") {
																		$location_col_str = "";
																		if ($in_parent_comp_flg == "yes") {
																			$location_col_str = "<th width='14%' class='txt-left'>Location</th>";
																		}
																	?>
                                    <tr>
                                        <?php echo $location_col_str; ?>
                                        <th width="23%" class="txt-left">Vendor</th>
                                        <th width="36%" class="txt-left">Material</th>
                                        <th width="7%">Count </th>
                                        <th width="7%">Weight <?php echo $weight_str; ?></th>
                                        <th width="7%">% Waste Stream</th>
                                        <th width="10%">Average Price ($)/<?php echo $weight_str; ?></th>
                                        <th width="10%">Total Amount</th>
                                    </tr>
                                    <?php } else {
																		$location_col_str = "";
																		if ($in_parent_comp_flg == "yes") {
																			$location_col_str = "<th width='14%' class='$class_nm1'>Location</th>";
																		}
																	?>
                                    <tr>
                                        <?php echo $location_col_str; ?>
                                        <td width="23%" class="<?php echo isset($class_nm1); ?> txt-left">Vendor</td>
                                        <td width="36%" class="<?php echo isset($class_nm1); ?> txt-left">Material</td>
                                        <td colspan="2" class="<?php echo isset($class_nm1); ?>" width="14%">Weight
                                            <?php echo $weight_str; ?></td>
                                        <td width="7%" class="<?php echo isset($class_nm1); ?>">% Waste Stream</td>
                                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Average Price
                                            ($)/<?php echo $weight_str; ?></td>
                                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Total Amount</td>
                                    </tr>

                                    <?php } ?>
                                    <?php echo $multiple_tabl_str; ?>

                                </table>
                            </div>
                            <?php } else { ?>
                            <?php echo $location_nm; ?>
                            <?php } ?>
                        </td>
                        <?php } ?>

                        <td class="txt-left"><?php echo $vendor_name; ?></td>
                        <td class="txt-left">
                            <?php if ($row_mtd1["blength"] != "1" && $row_mtd1["bwidth"] != "1") { ?>
                            <?php echo $row_mtd1["blength"]; ?> <?php echo $row_mtd1["blength_frac"]; ?> x
                            <?php echo $row_mtd1["bwidth"]; ?> <?php echo $row_mtd1["bwidth_frac"]; ?> x
                            <?php echo $row_mtd1["bdepth"]; ?> <?php echo $row_mtd1["bdepth_frac"]; ?>
                            <?php
														if ($row_mtd1["bwall"] > 1) {
															echo " " . $row_mtd1["bwall"] . "-Wall ";
														}
														?>
                            <?php } ?>
                            <?php echo $row_mtd1["bdescription"]; ?>
                        </td>
                        <?php if ($row_mtd1["itemcount"] > 0) { ?>
                        <td><?php echo number_format($row_mtd1["itemcount"], 0); ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>

                        <td><?php echo number_format($weightval, 2); ?></td>
                        <?php
												if ($sumtot > 0) { ?>
                        <td><?php echo number_format(($weightval / $sumtot) * 100, 2) . "%"; ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>
                        <?php
												//$row_mtd1["valueach"]
												if ($weightval > 0) { ?>
                        <td>$<?php echo number_format($row_mtd1["totamt"] / $weightval, 2); ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>

                        <?php if ($row_mtd1["totamt"] < 0) { ?>
                        <td>$<?php echo number_format($row_mtd1["totamt"], 2); ?></td>
                        <?php } else { ?>
                        <td>$<?php echo number_format($row_mtd1["totamt"], 2); ?></td>
                        <?php } ?>
                    </tr>
                    <?php
											//}
										}
									}
								}

								$newArrQryRes1 = array();
								$weightvalnew = 0;
								foreach ($qryRes1 as $qryRes1K => $qryRes1V) {
									if ($qryRes1V['invoice_date'] >= $st_date && $qryRes1V['invoice_date'] <= $end_date . ' 23:59:59') {
										if ($qryRes1V["Outlet"] == $outlet_array[$arrycnt]) {
											$newArrQryRes1[] = $qryRes1V;
										}
									}
								}
								//echo "<pre> newArrQryRes1 -";print_r($newArrQryRes1) ; echo "</pre>";
								$newArrQryRes1_1 = $newArrQryRes1_2 = $newArrQryRes1_3 = array();
								foreach ($newArrQryRes1 as $newArrQryRes1k => $newArrQryRes1v) {
									$newArrQryRes1_1[$newArrQryRes1v['vendor_id']][$newArrQryRes1v['box_id']][] = $newArrQryRes1v;
								}
								//echo "<pre> newArrQryRes1_1 -";print_r($newArrQryRes1_1) ; echo "</pre>";
								foreach ($newArrQryRes1_1 as $newArrQryRes1_1K => $newArrQryRes1_1V) {
									$i = 0;
									foreach ($newArrQryRes1_1V as $newArrQryRes1_1VK1 => $newArrQryRes1_1V1) {
										$weightval = $valueeachval = $totalval = $itemcount = $weightvalorg = $poundpergallon_value = $weightUnit = 0;
										$AmountUnitEquivalent = "";
										$rec_id = "";




										foreach ($newArrQryRes1_1V1 as $newArrQryRes1_1V1K => $newArrQryRes1_1V1V) {
											$weightvalnew = 0;
											$itemcount_org = 0;
											$weight_org = 0;
											$poundpergallon_value_org = 0;

											//echo "Org Data = " . var_dump($newArrQryRes1_1V1V) . "<br>";
											$itemcount_org = $newArrQryRes1_1V1V["unit_count"];
											$weight_org = $newArrQryRes1_1V1V["weight"];
											$poundpergallon_value_org = $newArrQryRes1_1V1V["poundpergallon_value"];

											foreach ($newArrQryRes1_1V1V as $newArrQryRes1_1V1VKey => $newArrQryRes1_1V1VVal) {
												$weightval 	= array_sum(array_column($newArrQryRes1_1V1, 'weight_in_pound'));
												$valueeachval 	= array_sum(array_column($newArrQryRes1_1V1, 'avg_price_per_pound'));
												$totalval 	= array_sum(array_column($newArrQryRes1_1V1, 'total_value'));
												$itemcount 	= array_sum(array_column($newArrQryRes1_1V1, 'unit_count'));



												if ($newArrQryRes1_1V1VKey == "mainid") {
													//echo $newArrQryRes1_1V1VVal . "<br>";
													$rec_id = $newArrQryRes1_1V1VVal;
												}

												if ($newArrQryRes1_1V1VKey == "AmountUnitEquivalent") {
													$AmountUnitEquivalent = $newArrQryRes1_1V1VVal;

													if ($newArrQryRes1_1V1VVal == 'Gallon') {
														if ($itemcount_org > 0) {
															//$weightvalnew = (str_replace(",", "", $weight_org) * str_replace(",", "", $itemcount_org)) * str_replace(",", "", $poundpergallon_value_org);
															$weightvalnew = ((float)str_replace(",", "", $weight_org) * (float)str_replace(",", "", $itemcount_org)) * (float)str_replace(",", "", $poundpergallon_value_org);
														} else {
															//	$weightvalnew = str_replace(",", "", $weight_org) * str_replace(",", "", $poundpergallon_value_org);
															$weightvalnew = (float)str_replace(",", "", $weight_org) * (float)str_replace(",", "", $poundpergallon_value_org);
														}
													} else {
														$weightval 	= array_sum(array_column($newArrQryRes1_1V1, 'weight_in_pound'));
													}
													$weightval = $weightval + $weightvalnew;
													//echo "weightval " . $weightval . " | " . $weightvalnew . " | " . $weight_org . " | " . $itemcount_org . " | " . $poundpergallon_value_org . "<br>";
												}



												$newArrQryRes1_2[$newArrQryRes1_1K][$i]['weightval'] = $weightval;
												//$newArrQryRes1_2[$newArrQryRes1_1K][$i]['weightvalorg'] = $weightvalorg;
												$newArrQryRes1_2[$newArrQryRes1_1K][$i]['AmountUnitEquivalent'] = $AmountUnitEquivalent;
												$newArrQryRes1_2[$newArrQryRes1_1K][$i]['rec_id'] = $rec_id;
												//$newArrQryRes1_2[$newArrQryRes1_1K][$i]['poundpergallon_value'] = $poundpergallon_value;
												//$newArrQryRes1_2[$newArrQryRes1_1K][$i]['weightUnit'] = $weightUnit;
												$newArrQryRes1_2[$newArrQryRes1_1K][$i]['valueeachval'] = $valueeachval;
												$newArrQryRes1_2[$newArrQryRes1_1K][$i]['totalval'] = $totalval;
												$newArrQryRes1_2[$newArrQryRes1_1K][$i]['itemcount'] = $itemcount;
												$newArrQryRes1_2[$newArrQryRes1_1K][$i]['vendor_id'] = $newArrQryRes1_1V1V['vendor_id'];
												$newArrQryRes1_2[$newArrQryRes1_1K][$i]['warehouse_id'] = $newArrQryRes1_1V1V['warehouse_id'];
												$newArrQryRes1_2[$newArrQryRes1_1K][$i][$newArrQryRes1_1V1VKey] = $newArrQryRes1_1V1VVal;
											}
										}
										$i++;
									}
								}
								$newArrQryRes1_2 = $newArrQryRes1_2;
								//echo "<br /><br /><pre>newArrQryRes1_2 -".$arrcount;print_r($newArrQryRes1_2) ; echo "</pre>";
								foreach ($newArrQryRes1_2 as $newArrQryRes1_2K => $newArrQryRes1_2V) {
									$newArrQryRes1_3[] = $newArrQryRes1_2V;
								}
								$arrcount = count($newArrQryRes1_3);
								$newArrQryRes1_4 = array();
								for ($arr = 0; $arr < $arrcount; $arr++) {
									$newArrQryRes1_4 = array_merge($newArrQryRes1_4, $newArrQryRes1_3[$arr]);
								}

								while ($row_mtd = array_shift($newArrQryRes1_4)) {/**/

									if ($_REQUEST["rep_inweight"] == "ton") {
										$weightval = $row_mtd['weightval'] / 2000;
									} else {
										$weightval = $row_mtd["weightval"];
									}
									//}

									$vendor_name = "";
									$res_child = db_query("Select Name from water_vendors where id = " . $row_mtd["vendor_id"]);
									while ($row_child = array_shift($res_child)) {
										$vendor_name = $row_child["Name"];
									}
									$weight_tot = $weight_tot + $weightval;
									$weightval_tot = $weightval_tot + $weightval;

									if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull") {
										//echo "In the negative values <br>";
										$totalval_tot = $totalval_tot - $row_mtd["totalval"];
										$amt_tot = $amt_tot - $row_mtd["totalval"];
									} else {
										$totalval_tot = $totalval_tot + $row_mtd["totalval"];
										$amt_tot = $amt_tot + $row_mtd["totalval"];
									}

									$multiple_location_cnt = 0;
									$location_nm = "";
									$multiple_tabl_str = "";
									$weightval_multiple = 0;
									$weightval_multiple_tot = 0;
									$total_val_to = 0;
									if ($in_parent_comp_flg == "yes") {

										db();
										$res_child = db_query("SELECT water_vendors.id, warehouse_id, loop_warehouse.b2bid, loop_warehouse.company_name, 
						water_inventory.description, unit_count, sum(weight_in_pound) as weight_in_pound, avg_price_per_pound, sum(total_value) as total_value, water_boxes_report_data.CostOrRevenuePerUnit, water_boxes_report_data.CostOrRevenuePerItem, water_boxes_report_data.CostOrRevenuePerPull
						FROM water_boxes_report_data INNER JOIN water_inventory ON water_boxes_report_data.box_id = water_inventory.ID 
						INNER JOIN water_transaction ON water_boxes_report_data.trans_rec_id = water_transaction.id
						INNER JOIN loop_warehouse ON loop_warehouse.id = water_boxes_report_data.warehouse_id
						INNER JOIN water_vendors  ON water_transaction.vendor_id = water_vendors.id WHERE water_vendors.id = " . $row_mtd["vendor_id"] . " and water_boxes_report_data.box_id = " . $row_mtd["box_id"] . "
						and water_vendors.id <> 844 AND warehouse_id IN (" . isset($warehouse_id) . ") AND (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by warehouse_id");
										while ($row_child = array_shift($res_child)) {
											if ($_REQUEST["rep_inweight"] == "ton") {
												$weightval_multiple = $row_child['weight_in_pound'] / 2000;
											} else {
												$weightval_multiple = $row_child["weight_in_pound"];
											}
											$weightval_multiple_tot = $weightval_multiple_tot + str_replace(",", "", number_format($weightval_multiple, 2));

											$location_nm = getnickname_warehouse_new($row_child["company_name"], $row_child["warehouse_id"]);
											$multiple_location_cnt = $multiple_location_cnt + 1;

											$multiple_tabl_str .= "<tr><td class='txt-left' >$location_nm</td><td class='txt-left' >$vendor_name</td>
							<td class='txt-left' >" . $row_child['description'] . "</td>";
											if ($outlet_array[$arrycnt] == 'Reuse') {
												if ($row_child['unit_count'] > 0) {
													//$count_tot = $count_tot + $row_child['unit_count'];

													$multiple_tabl_str .= "<td class='txt-right'>" . number_format($row_child['unit_count'], 0) . "</td>";
												} else {
													$multiple_tabl_str .= "<td >&nbsp;</td>";
												}
												$multiple_tabl_str .= "<td class='txt-right'>" . number_format($weightval_multiple, 2) . "</td>";
											} else {
												$multiple_tabl_str .= "<td colspan='2' class='txt-right'>" . number_format($weightval_multiple, 2) . "</td>";
											}

											if ($sumtot > 0) {
												$multiple_tabl_str .= "<td class='txt-right'>" . number_format(($weightval_multiple / $sumtot) * 100, 2) . '%' . "</td>";
											} else {
												$multiple_tabl_str .= "<td >&nbsp;</td>";
											}

											if ($row_child['CostOrRevenuePerUnit'] == 'Cost Per Unit' || $row_child['CostOrRevenuePerItem'] == 'Cost Per Item' || $row_child['CostOrRevenuePerPull'] == 'Cost Per Pull') {
												if ($weightval_multiple > 0) {
													$multiple_tabl_str .= "<td class='txt-align'>$-" . number_format(($row_child['total_value'] / $weightval_multiple), 2) . "</td>";
												} else {
													$multiple_tabl_str .= "<td >&nbsp;</td>";
												}
												$multiple_tabl_str .= "<td class='txt-align'>$-" . number_format($row_child['total_value'], 2) . "</td>";
												$total_val_to = $total_val_to - str_replace(",", "", number_format($row_child['total_value'], 2));
											} else {

												if ($weightval_multiple > 0) {
													$multiple_tabl_str .= "<td class='txt-right'>$" . number_format(($row_child['total_value'] / $weightval_multiple), 2) . "</td>";
												} else {
													$multiple_tabl_str .= "<td >&nbsp;</td>";
												}
												$multiple_tabl_str .= "<td class='txt-right'>$" . number_format($row_child['total_value'], 2) . "</td>";
												$total_val_to = $total_val_to + str_replace(",", "", number_format($row_child['total_value'], 2));
											}

											$multiple_tabl_str .= "</tr>";

											db();
										}
										if ($total_val_to < 0) {
											$multiple_tabl_str .= "<tr><td colspan='3' class='txt-right'><b>Total:</b></td><td class='txt-right' colspan='2'><b>" . number_format($weightval_multiple_tot, 2) . "</b></td><td >&nbsp;</td><td >&nbsp;</td><td class='txt-align'><b>$" . number_format($total_val_to, 2) . "</b></td></tr>";
										} else {
											$multiple_tabl_str .= "<tr><td colspan='3' class='txt-right'><b>Total:</b></td><td class='txt-right' colspan='2'><b>" . number_format($weightval_multiple_tot, 2) . "</b></td><td >&nbsp;</td><td >&nbsp;</td><td class='txt-right'><b>$" . number_format($total_val_to, 2) . "</b></td></tr>";
										}

										if ($location_nm == "") {
											$location_nm = getnickname_warehouse_new('', $row_mtd["warehouse_id"]);
										}
									}
									?>
                    <tr>
                        <?php if ($in_parent_comp_flg == "yes") { ?>
                        <td class="txt-left">
                            <?php if ($multiple_location_cnt > 1) { ?>
                            <a href='#'
                                id="location_box_detail<?php echo $row_mtd["vendor_id"] . $row_mtd["box_id"]; ?>"
                                onclick='showlocation_box_detail(<?php echo $row_mtd["vendor_id"] . $row_mtd["box_id"]; ?>); return false;'>
                                Multiple</a>
                            <div id="locationdiv<?php echo $row_mtd["vendor_id"] . $row_mtd["box_id"]; ?>"
                                style="display:none;">
                                <table>
                                    <?php
															if ($outlet_array[$arrycnt] == "Reuse") {
																$location_col_str = "";
																if ($in_parent_comp_flg == "yes") {
																	$location_col_str = "<th width='14%' class='txt-left'>Location</th>";
																}
															?>
                                    <tr>
                                        <?php echo $location_col_str; ?>
                                        <th width="23%" class="txt-left">Vendor</th>
                                        <th width="36%" class="txt-left">Material</th>
                                        <th width="7%">Count </th>
                                        <th width="7%">Weight <?php echo $weight_str; ?></th>
                                        <th width="7%">% Waste Stream</th>
                                        <th width="10%">Average Price ($)/<?php echo $weight_str; ?></th>
                                        <th width="10%">Total Amount</th>
                                    </tr>
                                    <?php } else {
																$location_col_str = "";
																if ($in_parent_comp_flg == "yes") {
																	$location_col_str = "<th width='14%' class='$class_nm1'>Location</th>";
																}
															?>
                                    <tr>
                                        <?php echo $location_col_str; ?>
                                        <td width="23%" class="<?php echo isset($class_nm1); ?> txt-left">Vendor</td>
                                        <td width="36%" class="<?php echo isset($class_nm1); ?> txt-left">Material</td>
                                        <td colspan="2" class="<?php echo isset($class_nm1); ?>" width="14%">Weight
                                            <?php echo $weight_str; ?></td>
                                        <td width="7%" class="<?php echo isset($class_nm1); ?>">% Waste Stream</td>
                                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Average Price
                                            ($)/<?php echo $weight_str; ?></td>
                                        <td width="10%" class="<?php echo isset($class_nm1); ?>">Total Amount</td>
                                    </tr>

                                    <?php } ?>
                                    <?php echo $multiple_tabl_str; ?>

                                </table>
                            </div>
                            <?php } else { ?>
                            <?php echo $location_nm; ?>
                            <?php } ?>
                        </td>
                        <?php } ?>

                        <td class="txt-left"><?php echo $vendor_name; ?></td>
                        <td class="txt-left"><?php echo $row_mtd["description"]; ?></td>
                        <?php if ($row_mtd["itemcount"] > 0) {
											$count_tot = $count_tot + $row_mtd["itemcount"];
										?>
                        <td><?php echo number_format($row_mtd["itemcount"], 0); ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>
                        <?php if ($outlet_array[$arrycnt] == "Reuse") { ?>
                        <td><?php echo number_format($weightval, 2); ?></td>
                        <?php } else { ?>
                        <?php if ($_REQUEST["rep_inweight"] == "ton") { ?>
                        <td><?php echo number_format($weightval, 2); ?></td>
                        <?php } else { ?>
                        <td><?php echo number_format($weightval, 0); ?></td>
                        <?php } ?>
                        <?php } ?>
                        <?php
										if ($sumtot > 0) { ?>
                        <td><?php echo number_format(($weightval / $sumtot) * 100, 2) . "%"; ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>
                        <?php
										//if (($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item") && $row_mtd["WeightorNumberofPulls"] != "By Weight"){ 
										if ($row_mtd["CostOrRevenuePerUnit"] == "Cost Per Unit" || $row_mtd["CostOrRevenuePerItem"] == "Cost Per Item" || $row_mtd["CostOrRevenuePerPull"] == "Cost Per Pull") {
										?>
                        <?php
											if ($weightval > 0) { ?>
                        <td class="txt-align">$-<?php echo number_format(($row_mtd["totalval"] / $weightval), 2); ?>
                        </td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>
                        <td class="txt-align">$-<?php echo number_format($row_mtd["totalval"], 2); ?></td>
                        <?php } else { ?>
                        <?php
											if ($weightval > 0) { ?>
                        <td>$<?php echo number_format(($row_mtd["totalval"] / $weightval), 2); ?></td>
                        <?php } else { ?>
                        <td>&nbsp;</td>
                        <?php } ?>

                        <td>$<?php echo number_format($row_mtd["totalval"], 2); ?></td>
                        <?php } ?>
                    </tr>
                    <?php
								}
								//echo $outlet_array[$arrycnt] . " = " . $weight_tot . " | " . $sumtot . "<br>";
								$outlet_tot[] = array('outlet' => $outlet_array[$arrycnt], 'tot' => $weight_tot, 'perc' => number_format(($weight_tot / $sumtot) * 100, 2) . "%", 'totval' => $amt_tot);

								?>
                    <tr>
                        <?php if ($in_parent_comp_flg == "yes") { ?>
                        <td class="txt-left reuse-td-footer">&nbsp;</td>
                        <?php } ?>
                        <td class="txt-left reuse-td-footer">&nbsp;</td>
                        <td class="txt-left reuse-td-footer"><strong>Total
                                <?php echo $outlet_array[$arrycnt]; ?></strong></td>

                        <td class="reuse-td-footer"><?php echo number_format($count_tot, 0); ?></td>
                        <td class="reuse-td-footer"><?php echo number_format($weight_tot, 0); ?></td>
                        <?php
									if ($sumtot > 0) { ?>
                        <td class="reuse-td-footer"><?php echo number_format(($weight_tot / $sumtot) * 100, 2) . "%"; ?>
                        </td>
                        <?php } else { ?>
                        <td class="reuse-td-footer">&nbsp;</td>
                        <?php } ?>

                        <td class="reuse-td-footer">&nbsp;</td>
                        <?php if ($amt_tot < 0) { ?>
                        <td class="reuse-td-footer reuse-red">$<?php echo number_format($amt_tot, 2); ?></td>
                        <?php } else { ?>
                        <td class="reuse-td-footer">$<?php echo number_format($amt_tot, 2); ?></td>
                        <?php } ?>

                    </tr>
                </tbody>
            </table>

        </div>

        <?php
				}
			}
			?>
        <div id="reuse-tables">
            <table width="100%" border="0">
                <tbody>
                    <tr>
                        <td class='txt-left reuse-td-footer-main'>&nbsp;</td>
                        <td class='reuse-td-footer-main'>MATERIALS GRAND TOTAL</td>
                        <td class='txt-left reuse-td-footer-main'>&nbsp;</td>
                        <td colspan="2" class='reuse-td-footer-main'><?php echo number_format($weightval_tot, 2); ?>
                        </td>
                        <td class='reuse-td-footer-main'>&nbsp;</td>
                        <?php if ($totalval_tot < 0) { ?>
                        <td class='reuse-td-footer-main red'>$<?php echo number_format($totalval_tot, 2); ?></td>
                        <?php } else { ?>
                        <td class='reuse-td-footer-main'>$<?php echo number_format($totalval_tot, 2); ?></td>
                        <?php } ?>
                    </tr>

                </tbody>
            </table>
        </div>

        <div id="detailed-tables">
            <div class="header-title">
                <div class="title-text">Additional Fees</div>
            </div>
            <table>
                <tbody>
                    <?php
						$query_mtd = "SELECT water_transaction.company_id, water_vendors.Name as Vendorname, water_transaction.vendor_id, water_trans_addfees.add_fees_id, water_additional_fees.additional_fees_display, water_trans_addfees.id as addfeeid, sum(water_trans_addfees.add_fees * water_trans_addfees.add_fees_occurance) as addfees from water_transaction ";
						$query_mtd .= " inner join water_vendors on water_transaction.vendor_id = water_vendors.id ";
						$query_mtd .= " inner join water_trans_addfees on water_trans_addfees.trans_id = water_transaction.id ";
						$query_mtd .= " left join water_additional_fees on water_trans_addfees.add_fees_id = water_additional_fees.id ";
						$query_mtd .= " WHERE water_vendors.id <> 844 and company_id IN ($warehouse_id) and (invoice_date >= '" . $st_date . "' AND invoice_date <= '" . $end_date . " 23:59:59') group by water_vendors.Name, water_additional_fees.additional_fees_display";
						//echo $query_mtd ."<br>";
						$othar_charges = 0;
						$vendor_nm = "";
						$add_fee_tot = 0;
						$first_rec = "n";
						$fees = 0;
						$res = db_query($query_mtd);
						while ($row_mtd = array_shift($res)) {
							$othar_charges = $othar_charges - $row_mtd["addfees"];
							//echo "Vendor : " . $vendor_nm . " " . $row_mtd["Vendorname"] . "<br>"; 

							if ($vendor_nm != $row_mtd["Vendorname"] && $first_rec == "y") {
								if ($add_fee_tot > 0) {
						?>
                    <tr>
                        <td class="total"><strong>&nbsp;</strong></td>
                        <td class="total"><strong>Total Amount</strong></td>
                        <td colspan="2" class="total"><strong>&nbsp;</strong></td>
                        <td class="total"><strong>&nbsp;</strong></td>
                        <td class="total"><strong>&nbsp;</strong></td>
                        <td class="total txt-align">-$<?php echo number_format($add_fee_tot, 2); ?></td>
                    </tr>

                    <?php
									$add_fee_tot = 0;
								}
							}

							$add_fee_tot = $add_fee_tot + $row_mtd["addfees"];

							if ($row_mtd["addfees"] > 0) {
								?>
                    <tr>
                        <td><?php echo $row_mtd["Vendorname"]; ?></td>
                        <td><a href='#'
                                id="remarkid<?php echo $row_mtd["vendor_id"]; ?><?php echo $row_mtd["add_fees_id"]; ?>"
                                onclick='showadd_fee_remark(<?php echo $row_mtd["vendor_id"]; ?>, <?php echo $row_mtd["add_fees_id"]; ?>, <?php echo $row_mtd["company_id"]; ?>, <?php echo chr(34) . $st_date . chr(34); ?>, <?php echo chr(34) . $end_date . chr(34); ?>); return false;'><?php echo $row_mtd["additional_fees_display"]; ?></a>
                        </td>
                        <td colspan="2"><strong>&nbsp;</strong></td>
                        <td><strong>&nbsp;</strong></td>
                        <td><strong>&nbsp;</strong></td>
                        <td class="txt-align">-$<?php echo number_format($row_mtd["addfees"], 2); ?></td>
                    </tr>
                    <?php
							}
							$first_rec = "y";
							$vendor_nm = $row_mtd["Vendorname"];
						}
						?>
                    <tr>
                        <td class="total">&nbsp;</td>
                        <td class="total"><strong>Total Amount</strong></td>
                        <td colspan="2" class="total"><strong>&nbsp;</strong></td>
                        <td class="total"><strong>&nbsp;</strong></td>
                        <td class="total"><strong>&nbsp;</strong></td>
                        <td class="total txt-align">-$<?php echo number_format($add_fee_tot, 2); ?></td>
                    </tr>

                    <tr>
                        <td colspan="7">&nbsp;</td>
                    </tr>

                    <?php
						if ($loop_trans_rec_found == "yes") {
							$query_mtd1 = "SELECT distinct loop_transaction.id , loop_transaction.freightcharge as freightcharge, loop_transaction.othercharge as othercharge from loop_transaction inner join loop_boxes_sort on loop_transaction.id = loop_boxes_sort.trans_rec_id ";
							//$query_mtd1 .= " WHERE loop_transaction.warehouse_id = " . $_GET["warehouse_id"] . " and (STR_TO_DATE(sort_date,'%m/%d/%Y') between str_to_date('" . date("m/d/Y", strtotime($st_date)) ."', '%m/%d/%Y') AND str_to_date('" . date("m/d/Y", strtotime($end_date)) . "', '%m/%d/%Y'))";
							$query_mtd1 .= " WHERE loop_transaction.warehouse_id IN ($warehouse_id) and loop_transaction.pr_requestdate_php between '" . $st_date . " 00:00:00' AND '" . $end_date . " 23:59:59'";

							//echo $query_mtd1 . "<br>";
							$Recycling_tot = 0;
							$ucb_item_otherchrg = 0;
							$fr_charges = 0;
							$oth_charges = 0;
							$res1 = db_query($query_mtd1);
							while ($row_mtd1 = array_shift($res1)) {
								//if ($row_mtd1["freightcharge"] > 0) {

								$fr_charges = $fr_charges + $row_mtd1["freightcharge"];
								$ucb_item_otherchrg = $ucb_item_otherchrg + $row_mtd1["freightcharge"];
								$othar_charges = $othar_charges + $row_mtd1["freightcharge"];

								$vendor_name = "UsedCardboardBoxes";
								/*$res_child = db_query("Select Name from vendors where id = " . $row_mtd1["vendor"] , db_b2b());
							while($row_child = array_shift($res_child))
							{								
								$vendor_name = $row_child["Name"];
							}*/
								//}

								//if ($row_mtd1["othercharge"] > 0) {
								$oth_charges = $oth_charges + $row_mtd1["othercharge"];
								$othar_charges = $othar_charges + $row_mtd1["othercharge"];
								$ucb_item_otherchrg = $ucb_item_otherchrg + $row_mtd1["othercharge"];
								$vendor_name = "UsedCardboardBoxes";
								/*$res_child = db_query("Select Name from vendors where id = " . $row_mtd1["vendor"] , db_b2b());
							while($row_child = array_shift($res_child))
							{								
								$vendor_name = $row_child["Name"];
							}*/
								//}
							}
							if ($fr_charges <> 0) {
						?>
                    <tr>
                        <td><?php echo isset($vendor_name); ?></td>
                        <td><?php echo "UCB - Freight Charges"; ?></td>
                        <td colspan="2"><strong>&nbsp;</strong></td>
                        <td><strong>&nbsp;</strong></td>
                        <td><strong>&nbsp;</strong></td>
                        <td class="txt-align">$<?php echo number_format($fr_charges, 2); ?></td>
                    </tr>
                    <?php
							}

							if ($oth_charges <> 0) {
							?>
                    <tr>
                        <td><?php echo isset($vendor_name); ?></td>
                        <td><?php echo "UCB - Other Charges"; ?></td>
                        <td colspan="2"><strong>&nbsp;</strong></td>
                        <td><strong>&nbsp;</strong></td>
                        <td><strong>&nbsp;</strong></td>
                        <td class="txt-align">$<?php echo number_format($oth_charges, 2); ?></td>
                    </tr>
                    <?php
							}
							if ($ucb_item_otherchrg > 0) {
							?>
                    <tr>
                        <td class="total">&nbsp;</td>
                        <td class="total"><strong>Total Amount</strong></td>
                        <td colspan="2" class="total"><strong>&nbsp;</strong></td>
                        <td class="total"><strong>&nbsp;</strong></td>
                        <td class="total"><strong>&nbsp;</strong></td>
                        <td class="total txt-align">-$<?php echo number_format($ucb_item_otherchrg, 2); ?></td>
                    </tr>
                    <?php }
						}
						?>
                    <tr>
                        <td class="total-add">&nbsp;</td>
                        <td class="total-add">TOTAL ADDITIONAL FEES</td>
                        <td colspan="2" class="total-add"><strong>&nbsp;</strong></td>
                        <td class="total-add"><strong>&nbsp;</strong></td>
                        <td class="total-add"><strong>&nbsp;</strong></td>
                        <td class="total-add txt-align red">-$<?php echo number_format($othar_charges, 2); ?></td>
                    </tr>
                    <tr>
                        <td class="total-add">&nbsp;</td>
                        <td class="total-add">GRAND TOTAL</td>
                        <td colspan="2" class="total-add"><strong>&nbsp;</strong></td>
                        <td class="total-add"><strong>&nbsp;</strong></td>
                        <td class="total-add"><strong>&nbsp;</strong></td>
                        <?php
							if ($othar_charges < 0) {
								if (($totalval_tot + $othar_charges) < 0) { ?>
                        <td class="total-add txt-align red1">
                            <b>$<?php echo number_format($totalval_tot + $othar_charges, 2); ?></b>
                        </td>
                        <?php } else { ?>
                        <td class="total-add txt-align">
                            <b>$<?php echo number_format($totalval_tot + $othar_charges, 2); ?></b>
                        </td>
                        <?php }
							} else {
								if (($totalval_tot - $othar_charges) < 0) { ?>
                        <td class="total-add txt-align red1">
                            <b>$<?php echo number_format($totalval_tot - $othar_charges, 2); ?></b>
                        </td>
                        <?php } else { ?>
                        <td class="total-add txt-align">
                            <b>$<?php echo number_format($totalval_tot - $othar_charges, 2); ?></b>
                        </td>
                        <?php }
							}
							?>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="no-more-tables">
            <table>
                <tr>
                    <th width="29%">
                        Process</th>
                    <th width="25%">&nbsp;</th>
                    <th width="10%">Total Weight <?php echo $weight_str; ?></th>
                    <th width="14%">% Waste Stream</th>
                    <th width="10%">&nbsp;</th>
                    <th width="14%" class="txt-right">Total Amount ($)</th>
                </tr>

                <?php
					foreach ($outlet_tot as $outlet_tottmp) {
						$bg_color = "";
						if ($outlet_tottmp['outlet'] == "Landfill") {
							$bg_color = "red";
						}
						if ($outlet_tottmp['outlet'] == "Incineration (No Energy Recovery)") {
							$bg_color = "brown";
						}
						if ($outlet_tottmp['outlet'] == "Waste To Energy") {
							$bg_color = "org";
						}
						if ($outlet_tottmp['outlet'] == "Recycling") {
							$bg_color = "blue";
						}
						if ($outlet_tottmp['outlet'] == "Reuse") {
							$bg_color = "green";
						}
					?>
                <tr>
                    <td class='<?php echo $bg_color; ?>'><?php echo $outlet_tottmp['outlet']; ?></td>
                    <td>&nbsp;</td>
                    <td class="txt-right"><?php echo number_format($outlet_tottmp['tot'], 2); ?></td>
                    <td class="txt-right"><?php echo $outlet_tottmp['perc']; ?></td>
                    <td>&nbsp;</td>
                    <?php if ($outlet_tottmp['totval'] < 0) { ?>
                    <td class="txt-right red1">$<?php echo number_format($outlet_tottmp['totval'], 2); ?></td>
                    <?php } else { ?>
                    <td class="txt-right">$<?php echo number_format($outlet_tottmp['totval'], 2); ?></td>
                    <?php } ?>

                </tr>
                <?php
					}
					?>
                <tr>
                    <td colspan="5" class="td-footer">TOTAL </td>
                    <?php if ($totalval_tot < 0) { ?>
                    <td class="td-footer red1">$<?php echo number_format($totalval_tot, 2); ?></td>
                    <?php } else { ?>
                    <td class="td-footer">$<?php echo number_format($totalval_tot, 2); ?></td>
                    <?php } ?>
                </tr>
                <tr>
                    <td colspan="5" class="td-footer">TOTAL ADDITIONAL FEES</td>
                    <td class="td-footer red1">-$<?php echo number_format($othar_charges, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="5" class="td-footer">GRAND TOTAL </td>
                    <?php
						if ($othar_charges < 0) {
							if (($totalval_tot + $othar_charges) < 0) { ?>
                    <td class="td-footer red1"><b>$<?php echo number_format($totalval_tot + $othar_charges, 2); ?></b>
                    </td>
                    <?php } else { ?>
                    <td class="td-footer"><b>$<?php echo number_format($totalval_tot + $othar_charges, 2); ?></b></td>
                    <?php }
						} else {
							if (($totalval_tot - $othar_charges) < 0) { ?>
                    <td class="td-footer red1"><b>$<?php echo number_format($totalval_tot - $othar_charges, 2); ?></b>
                    </td>
                    <?php } else { ?>
                    <td class="td-footer"><b>$<?php echo number_format($totalval_tot - $othar_charges, 2); ?></b></td>
                    <?php }
						}
						?>
                </tr>

            </table>
        </div>

    </div>

    <div id="footer">
        <div class="footer-txt">
            <div class="footer-main">
                ©<?php echo date("Y"); ?> UCBZeroWaste, LLC. – 4032 Wilshire Blvd #402, Los Angeles, CA, 90010 –
                Toll-Free: 1-888-BOXES-88; Phone: 323-724-2500; Fax: 323-315-4194</div>
            <div class="footer-main space-left">&nbsp;</div>
            <div class="footer-main space-left">&nbsp;</div>
        </div>
    </div>
</body>

</html>
<?php

} else {
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"index.php" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php" . "\" />";
	echo "</noscript>";
	exit;
}
?>