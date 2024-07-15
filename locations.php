<?php

// ini_set('memory_limit', '-1');
session_start();

if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>UCBZeroWaste</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700"
        rel="stylesheet">
    <link rel="shortcut icon" href="images/logo.jpg" type="image/jpg">

    <link href="css/header-footer.css" rel="stylesheet">
    <link href="css/inner.css" rel="stylesheet">
    <link href="css/inner-table.css" rel="stylesheet">

    <link href="css/pickups-in-process-form.css" rel="stylesheet">
    <link href="css/vendor-reports-table-new.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <!--Menu =========================================================================================================================-->
    <link rel="stylesheet" href="menu/demo.css">
    <link rel="stylesheet" href="menu/navigation-icons.css">
    <link rel="stylesheet" href="menu/slicknav/slicknav.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">

    <script language="javascript">
    function display_pdf(val) {
        document.getElementById('fileview').innerHTML = "<embed src='" + val + "' width='200' height='300'>";
    }

    function callchildash(compid, selected_yr) {
        var win = window.open("dashboard-child.php?childwarehouseid=" + compid + "&selected_yr=" + selected_yr,
            '_blank');
        win.focus();
    }

    function f_getPosition(e_elemRef, s_coord) {
        var n_pos = 0,
            n_offset,
            e_elem = e_elemRef;

        while (e_elem) {
            n_offset = e_elem["offset" + s_coord];
            n_pos += n_offset;
            e_elem = e_elem.offsetParent;
        }

        e_elem = e_elemRef;
        while (e_elem != document.body) {
            n_offset = e_elem["scroll" + s_coord];
            if (n_offset && e_elem.style.overflow == 'scroll')
                n_pos -= n_offset;
            e_elem = e_elem.parentNode;
        }
        return n_pos;
    }

    function supplierdash_edit(loginid, id) {
        var chknewval = 0;
        if (document.getElementById('supplierdash_flg' + loginid).checked) {
            var chknewval = 1;
        }

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == 1) {
                    alert("User flag updated.");
                }
            }
        }

        xmlhttp.open("GET", "supplierdashboard_edituser.php?chkval=" + chknewval + "&loginid=" + loginid +
            "&companyid=" + id, true);
        xmlhttp.send();
    }


    function show_userlist(warehouse_id, comp_id) {
        var selectobject = document.getElementById("showuser" + warehouse_id);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_window').style.display = 'block';
        document.getElementById('light_window').style.left = n_left + 50 + 'px';
        document.getElementById('light_window').style.top = n_top + 10 + 'px';
        document.getElementById('light_window').style.width = 650 + 'px';

        document.getElementById("light_window").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_window").innerHTML =
                    "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_window').style.display='none';>Close</a><br>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "water_show_user_list.php?warehouse_id=" + warehouse_id + "&comp_id=" + comp_id, true);
        xmlhttp.send();
    }

    function showpiechart(warehouse_id, parent_comp_id, start_date, end_date) {

        var selectobject = document.getElementById("piechart" + warehouse_id);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light_window').style.display = 'block';
        document.getElementById('light_window').style.left = n_left + 50 + 'px';
        document.getElementById('light_window').style.top = n_top + 10 + 'px';

        document.getElementById("light_window").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light_window").innerHTML =
                    "<a style='cursor:pointer;' href='javascript:void(0)' style='text-decoration:none;color:black' onclick=document.getElementById('light_window').style.display='none';>Close</a><br>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "locations_pie_chart.php?warehouse_id=" + warehouse_id + "&parent_comp_id=" +
            parent_comp_id + "&start_date=" + start_date + "&end_date=" + end_date, true);
        xmlhttp.send();
    }
    </script>

    </script>
    <script>
    function sort_location_data(colid, sortflg) {
        document.getElementById("div_loc_sort").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_loc_sort").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "sort_location_data.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }
    </script>

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

    <style>
    .white_content {
        display: none;
        position: absolute;
        padding: 5px;
        border: 2px solid black;
        background-color: white;
        left: 100px;
        top: 100px;
        width: 550px;
        height: 500px;
        z-index: 1002;
        overflow: auto;
    }
    </style>

</head>

<body>

    <div id="light_window" class="white_content"> </div>
    <?php
		require "../mainfunctions/database.php";
		require "../mainfunctions/general-functions.php";

		db();

		// function getnickname($warehouse_name, $b2bid)
		// {
		// 	$nickname = "";
		// 	if ($b2bid > 0) {
		// 		db_b2b();
		// 		$sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = ?";
		// 		$result_comp = db_query($sql, array("i"), array($b2bid));
		// 		while ($row_comp = array_shift($result_comp)) {
		// 			if ($row_comp["nickname"] != "") {
		// 				$nickname = $row_comp["nickname"];
		// 			} else {
		// 				$tmppos_1 = strpos($row_comp["company"], "-");
		// 				if ($tmppos_1 != false) {
		// 					$nickname = $row_comp["company"];
		// 				} else {
		// 					if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
		// 						$nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
		// 					} else {
		// 						$nickname = $row_comp["company"];
		// 					}
		// 				}
		// 			}
		// 		}
		// 		db();
		// 	} else {
		// 		$nickname = $warehouse_name;
		// 	}

		// 	return $nickname;
		// }

		$companyid = 0;
		$warehouse_id = 0;
		$company_name = "";
		$company_logo = "";
		$parent_comp_flg = 0;
		$sql = "SELECT companyid, parent_comp_flg FROM supplierdashboard_usermaster WHERE loginid=? and activate_deactivate = 1";
		//echo $sql . "<br>";
		$result = db_query($sql, array("i"), array($_SESSION['loginid']));
		while ($myrowsel = array_shift($result)) {
			$cp = $myrowsel["companyid"];
			$parent_comp_flg = $myrowsel['parent_comp_flg'];
			$sql1 = "SELECT logo_image FROM supplierdashboard_details WHERE companyid=? ";
			$result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
			while ($myrowsel1 = array_shift($result1)) {
				$company_logo = $myrowsel1["logo_image"];
			}

			$sql1 = "SELECT id, company_name FROM loop_warehouse WHERE b2bid=? ";
			$result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
			while ($myrowsel1 = array_shift($result1)) {
				$warehouse_id = $myrowsel1["id"];

				$company_name = $myrowsel1["company_name"] . "'s";
			}

			$companyid = $myrowsel["companyid"];
		}
		$_SESSION['pgname'] = "locations";
		?>

    <?php
		require("mainfunctions/top-header.php");	?>

    <div class="top-title">
        <div class="top-title0">
            <!--<div class="top-title1">Vendor Reports &nbsp; <i class="fa fa-list-alt"></i></div>
	<div class="top-title2">The report below is a tracking tool showing waste vendor invoices that have been submitted to Used Cardboard Boxes, Inc., and which invoices still need to be turned in to keep W.A.T.E.R. data and reports updated. This tracking tool gives an overview of what constitutes the foundation of W.A.T.E.R. data and reports, and provides a sense of its accuracy.</div>-->
        </div>
    </div>

    <div class="main-inner-container">
        <div class="inner-container1">

            <form name="rptGraph" id="rptGraph" action="" method="POST">
                <input type="hidden" name="companyid" id="companyid" value="" />
                <div class="sub-title-container">
                    <h3>Total Locations: <span id="tot_location"></span></h3>

                    <?php
						$selected_yr = Date("Y");
						$selected_month = Date("m") - 1;
						if (isset($_REQUEST["inv_rep_yr"])) {
							$selected_yr = $_REQUEST["inv_rep_yr"];
							$selected_month = 12;
						}
						$_SESSION["inv_rep_yr"] = $selected_yr;

						?>

                    <h4>Viewing:
                        <select id="inv_rep_yr" name="inv_rep_yr" class="form-select1">
                            <?php

								for ($i = 2017, $n = Date("Y") + 1; $i < $n; $i++) {
								?>
                            <option value="<?php echo $i ?>" <?php if ($selected_yr == $i) {
																			echo " selected ";
																		} ?>><?php echo $i ?></option>
                            <?php
								}
								?>
                        </select>
                        &nbsp;<input $company_name_city=$cityrow["city"] ? trim($cityrow["city"]) : '' ;pyear"
                            onclick="showrep()">
                    </h4>

                </div>

                <div>
                    <div id="div_loc_sort" name="div_loc_sort">
                        <table class="loc_table" role="table">
                            <thead role="rowgroup">
                                <tr role="row">
                                    <th role="columnheader">
                                        <div class="loctxt">Company Name</div>
                                        <div class="locimg"><a
                                                href="locations.php?comp_name=asc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_asc.jpg" width="8px;" height="16px;"></a>&nbsp;<a
                                                href="locations.php?comp_name=desc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_desc.jpg" width="8px;" height="16px;"></a></div>
                                    </th>
                                    <?php if ($companyid == 181871) { ?>
                                    <th role="columnheader">
                                        <div class="loctxt">Location ID</div>
                                        <div class="locimg"><a
                                                href="locations.php?comp_location=asc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_asc.jpg" width="8px;" height="16px;"></a>&nbsp;<a
                                                href="locations.php?comp_location=desc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_desc.jpg" width="8px;" height="16px;"></a></div>
                                    </th>
                                    <th role="columnheader">
                                        <div class="loctxt">City, State</div>
                                        <div class="locimg"><a
                                                href="locations.php?comp_city=asc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_asc.jpg" width="8px;" height="16px;"></a>&nbsp;<a
                                                href="locations.php?comp_city=desc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_desc.jpg" width="8px;" height="16px;"></a></div>
                                    </th>
                                    <?php } ?>
                                    <th role="columnheader">
                                        Landfill Diversion Pie Chart (YTD)
                                    </th>
                                    <th valign="top" role="columnheader">
                                        <div class="loctxt">Landfill Diversion (YTD)</div>
                                        <div class="locimg"><a
                                                href="locations.php?landfilldiversion=asc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_asc.jpg" width="8px;" height="16px;"></a>&nbsp;<a
                                                href="locations.php?landfilldiversion=desc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_desc.jpg" width="8px;" height="16px;"></a></div>
                                    </th>
                                    <th valign="top" role="columnheader">
                                        <div class="loctxt">Net Financial Spend (YTD)</div>
                                        <div class="locimg"><a
                                                href="locations.php?netfinance=asc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_asc.jpg" width="8px;" height="16px;"></a>&nbsp;
                                            <a
                                                href="locations.php?netfinance=desc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_desc.jpg" width="8px;" height="16px;"></a>
                                        </div>
                                    </th>
                                    <th valign="top" role="columnheader">
                                        <div class="loctxt">Missing Water Invoices </div>
                                        <div class="locimg"><a
                                                href="locations.php?pastreport=asc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_asc.jpg" width="8px;" height="16px;"></a>&nbsp;<a
                                                href="locations.php?pastreport=desc&sort=y&inv_rep_yr=<?php echo $selected_yr; ?>"><img
                                                    src="images/sort_desc.jpg" width="8px;" height="16px;"></a></div>
                                    </th>
                                    <th role="columnheader">

                                    </th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup">
                                <?php
									//
									$st_date = date($selected_yr . "-01-01");
									if ($selected_yr == date("Y")) {
										$end_date = date($selected_yr . "-m-d");
									} else {
										$end_date = date($selected_yr . "-12-31");
									}

									//
									if ($parent_comp_flg == 1) {
										//echo $cp;
										db_b2b();
										$sumtot = 0;
										$vcsql = "select ID, loopid, parent_child, parent_comp_id from companyInfo where parent_comp_id=" . isset($cp) . " and haveNeed = 'Have Boxes'";
										$vcresult = db_query($vcsql);
										$allid = "";
										while ($vcrow = array_shift($vcresult)) {
											$ch_id = $vcrow["ID"];
											//if ($vcrow["loopid"] > 0) {
											$allid .= $ch_id . ",";
											//}	
										}

										if ($allid != "") {
											$allid = substr($allid, 0, strlen($allid) - 1);
										}

										db();
										$outlet_array = array("Reuse", "Recycling", "Waste To Energy");
										//
										$domain_array = array("ucbzerowaste", "usedcardboardboxes", "ucbenvironmental", "ucbpalletsollutions");

										$last_outlet = "";
										$tot_location = 0;

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
										//
										db_b2b();
										$sql1 = "SELECT ID, loopid, company from companyInfo WHERE ID IN ($allid) order by nickname ASC";

										$result1 = db_query($sql1);
										/*$sql1 = "SELECT id, company_name FROM loop_warehouse WHERE b2bid =? " ;
		$result1 = db_query($sql1, array("i") , array($ch_id));*/
										while ($myrowsel1 = array_shift($result1)) {
											$warehouse_id = $myrowsel1["loopid"];
											$cp = $myrowsel1["ID"];
											$cmp_id = $myrowsel1["ID"];
											$company_name = getnickname($myrowsel1["company"], $myrowsel1["ID"]);

											$user_exist_flg = "no";
											$query_mtd1 = "SELECT supplier_email from supplierdashboard_usermaster where companyid = '" . $cmp_id . "'";
											$res1 = db_query($query_mtd1);
											while ($row_vendor = array_shift($res1)) {
												$user_eml_exist_flg = "no";
												foreach ($domain_array as $domain_array_data) {
													$position = strpos(strtolower($row_vendor["supplier_email"]), $domain_array_data);
													if ($position != false) {
														$user_eml_exist_flg = "yes";
													}
												}

												if ($user_eml_exist_flg == "no") {
													$user_exist_flg = "yes";
												}
											}

											$total_cost_parent = 0;
											$landfill_diversion = 0;


											db();


											$query_mtd1 = "SELECT waste_financial, landfill_diversion, inv_past_due from water_cron_parent_location where warehouse_id = " . $warehouse_id . " and data_year = " . $selected_yr;
											$res1 = db_query($query_mtd1);
											while ($row_vendor = array_shift($res1)) {
												$total_cost_parent = $row_vendor["waste_financial"];
												$landfill_diversion = $row_vendor["landfill_diversion"];
												$pastdue = $row_vendor["inv_past_due"];
											}

											$tot_location = $tot_location + 1;
											//



											$company_name_name = "";
											$company_name_location = "";
											$company_name_city = "";
											if ($companyid == 181871) {

												$company_name_arr_last = explode("(Location ID", $company_name);
												$company_name_arr_loc = explode(")", $company_name_arr_last[1]);

												$company_name_name = trim($company_name_arr_last[0]);
												$company_name_location = trim($company_name_arr_loc[0]);
												//$company_name_location = rtrim($company_name_location, ")");
												//$company_name_city = ltrim(rtrim($company_name_arr_loc[1]), " -");
											} else {
												$company_name_name = $company_name;
											}

											db_b2b();
											$sqlcity = "SELECT city, state FROM companyInfo WHERE ID = '" . $cmp_id . "' ";
											$rescity = db_query($sqlcity);
											while ($cityrow = array_shift($rescity)) {
												if ($cityrow["city"] != "" && $cityrow["state"] != "") {
													$company_name_city = trim($cityrow["city"]  . ", " . $cityrow["state"]);
												} else if ($cityrow["city"] = "" && $cityrow["state"] != "") {
													$company_name_city ==  trim($cityrow["state"]);
												} else if ($cityrow["city"] != "" && $cityrow["state"] == "") {
													//$company_name_city = trim($cityrow["city"]);
													$company_name_city = isset($cityrow["city"]) && is_string($cityrow["city"]) ? trim($cityrow["city"]) : '';
												}
											}
											db();

											//For Diversion report
											$MGArray_parent_child_data[] = array('warehouse_id' => $warehouse_id, 'company_name' => $company_name, 'netfinacne_amt' => $total_cost_parent, 'landfill_diversion' => $landfill_diversion, 'pastdue' => isset($pastdue), 'company_name_name' => $company_name_name, 'company_name_location' => $company_name_location, 'company_name_city' => $company_name_city);

											if (!isset($_REQUEST["sort"])) {

												//echo "data_found - " . $data_found . "<br>";
									?>

                                <tr role="row">
                                    <td role="cell">
                                        <strong><?php echo $company_name_name; ?></strong>
                                        <?php if ($user_exist_flg == "yes") { ?>
                                        <div id="showuser<?php echo $warehouse_id; ?>"
                                            onclick="show_userlist(<?php echo $warehouse_id; ?> , <?php echo $cmp_id; ?>)">
                                            <u>View Users</u>
                                        </div>
                                        <?php } ?>
                                    </td>
                                    <?php if ($companyid == 181871) { ?>
                                    <td style="width:180px;" role="cell">
                                        <strong><?php echo $company_name_location; ?></strong>
                                    </td>
                                    <td role="cell">
                                        <strong><?php echo $company_name_city; ?></strong>
                                    </td>
                                    <?php } ?>
                                    <td role="cell" align="center" style="text-align: center;">
                                        <div id="piechart<?php echo $warehouse_id; ?>"
                                            onclick="showpiechart(<?php echo $warehouse_id; ?> , <?php echo $cp; ?>, '<?php echo $st_date; ?>', '<?php echo $end_date; ?>')">
                                            <u>View Pie Chart</u>
                                        </div>

                                        <?php if (isset($data_found) == "y") { ?>
                                        <!--<iframe src="water-ytd-pie-chart-loc.php?warehouse_id=<?php echo $warehouse_id; ?>&parent_comp_id=<?php echo $cp; ?>&start_date=<?php echo $st_date; ?>&end_date=<?php echo $end_date; ?>" frameborder="0" class="loc_iframe"> 
					</iframe>-->
                                        <?php } ?>
                                    </td>
                                    <td align="center" style="text-align:center;" role="cell">
                                        <?php
														if ($landfill_diversion < 80) {
														?>
                                        <span class="location_red_txt">
                                            <?php

														} else {
															?>
                                            <span class="location_green_txt">
                                                <?php
															}
																?>

                                                <?php echo number_format($landfill_diversion, 2); ?>%
                                            </span>
                                    </td>
                                    <td align="center" style="text-align:center;" role="cell">
                                        <?php
														if ($total_cost_parent < 0) {
														?>
                                        <span class="location_red_txt">
                                            <?php

														} else {
															?>
                                            <span class="location_green_txt">
                                                <?php
															}
																?>

                                                $<?php echo number_format($total_cost_parent, 2); ?>
                                            </span>
                                    </td>

                                    <td align="center" style="text-align:center;" role="cell">
                                        <span class="location_red_txt"><?php echo isset($pastdue); ?></span>
                                    </td>
                                    <td style="text-align:center;" role="cell">
                                        <input type="button" class="dash-button1" value="Go to Dashboard"
                                            onclick="callchildash(<?php echo $warehouse_id ?>, <?php echo $selected_yr ?>)">
                                    </td>
                                </tr>

                                <?php
											}
										}

										?>
                                <script>
                                document.getElementById("tot_location").innerHTML = <?php echo $tot_location ?>
                                </script>
                                <?php
										if (isset($_REQUEST["sort"])) {
											// print_r($MGArray_parent_child_data);	
											foreach ($MGArray_parent_child_data as $key => $row) {
												$vc_array_netfinacne_amt[$key] = $row['netfinacne_amt'];
												$vc_array_landfill_diversion[$key] = $row['landfill_diversion'];
												$vc_array_warehouse_id[$key] = $row['warehouse_id'];
												$vc_array_pastdue[$key] = $row['pastdue'];
												$vc_array_cname[$key] = $row['company_name_name'];
												$vc_array_location[$key] = $row['company_name_location'];
												$vc_array_city[$key] = $row['company_name_city'];
											}

											if ($_REQUEST["netfinance"] == "asc") {
												array_multisort($vc_array_netfinacne_amt, SORT_ASC, $MGArray_parent_child_data); //, $vc_array_name, SORT_DESC
											} elseif ($_REQUEST["netfinance"] == "desc") {
												array_multisort($vc_array_netfinacne_amt, SORT_DESC, $MGArray_parent_child_data); //, $vc_array_name, SORT_DESC
											} elseif ($_REQUEST["landfilldiversion"] == "asc") {
												array_multisort($vc_array_landfill_diversion, SORT_ASC, $MGArray_parent_child_data); //, $vc_array_name, 
											} elseif ($_REQUEST["landfilldiversion"] == "desc") {
												array_multisort($vc_array_landfill_diversion, SORT_DESC, $MGArray_parent_child_data); //, $vc_array_name, 
												//print_r($MGArray_parent_child_data);
											} elseif ($_REQUEST["pastreport"] == "asc") {
												array_multisort($vc_array_pastdue, SORT_ASC, $MGArray_parent_child_data); //, $vc_array_name, 
											} elseif ($_REQUEST["pastreport"] == "desc") {
												array_multisort($vc_array_pastdue, SORT_DESC, $MGArray_parent_child_data); //, $vc_array_name, 
												//print_r($MGArray_parent_child_data);
											} elseif ($_REQUEST["comp_name"] == "asc") {
												array_multisort($vc_array_cname, SORT_ASC, $MGArray_parent_child_data); //, $vc_array_name, 
											} elseif ($_REQUEST["comp_name"] == "desc") {
												array_multisort($vc_array_cname, SORT_DESC, $MGArray_parent_child_data); //, $vc_array_name, 
											} elseif ($_REQUEST["comp_location"] == "asc") {
												array_multisort($vc_array_location, SORT_ASC, $MGArray_parent_child_data); //, $vc_array_name, 
											} elseif ($_REQUEST["comp_location"] == "desc") {
												array_multisort($vc_array_location, SORT_DESC, $MGArray_parent_child_data); //, $vc_array_name, 
											} elseif ($_REQUEST["comp_city"] == "asc") {
												array_multisort($vc_array_city, SORT_ASC, $MGArray_parent_child_data); //, $vc_array_name, 
											} elseif ($_REQUEST["comp_city"] == "desc") {
												array_multisort($vc_array_city, SORT_DESC, $MGArray_parent_child_data); //, $vc_array_name, 
											}

											//  print_r($MGArray_parent_child_data);	 comp_name comp_city
											foreach ($MGArray_parent_child_data as $MGArraytmp2) { ?>
                                <tr role="row">
                                    <td role="cell">
                                        <strong>
                                            <?php echo $MGArraytmp2["company_name_name"]; ?></strong>
                                    </td>
                                    <?php if ($companyid == 181871) { ?>
                                    <td style="width:180px;" role="cell">
                                        <strong><?php echo $MGArraytmp2["company_name_location"]; ?></strong>
                                    </td>
                                    <td role="cell">
                                        <strong><?php echo $MGArraytmp2["company_name_city"]; ?></strong>
                                    </td>
                                    <?php } ?>
                                    <td role="cell">
                                        <div id="piechart<?php echo $warehouse_id; ?>"
                                            onclick="showpiechart(<?php echo $MGArraytmp2["warehouse_id"]; ?> , <?php echo isset($cp); ?>, '<?php echo $st_date; ?>', '<?php echo $end_date; ?>')">
                                            <u>View Pie Chart</u>
                                        </div>

                                        <!-- <iframe src="water-ytd-pie-chart-loc.php?warehouse_id=<?php echo $MGArraytmp2["warehouse_id"]; ?>&parent_comp_id=<?php echo isset($cp); ?>&start_date=<?php echo $st_date; ?>&end_date=<?php echo $end_date; ?>" frameborder="0" class="loc_iframe"> 
						</iframe>	-->
                                    </td>
                                    <td align="center" style="text-align:center;" role="cell">
                                        <span class="location_green_txt">
                                            <?php echo number_format($MGArraytmp2["landfill_diversion"], 2); ?>%</span>
                                    </td>
                                    <td align="center" style="text-align:center;" role="cell">
                                        <?php
														if ($MGArraytmp2["netfinacne_amt"] < 0) {
														?>
                                        <span class="location_red_txt">
                                            <?php

														} else {
															?>
                                            <span class="location_green_txt">
                                                <?php
															}
																?>


                                                $<?php echo number_format($MGArraytmp2["netfinacne_amt"], 2); ?></span>
                                    </td>
                                    <td align="center" style="text-align:center;" role="cell">
                                        <span class="location_green_txt"><?php echo $MGArraytmp2["pastdue"]; ?></span>
                                    </td>
                                    <td style="text-align:center;" role="cell">
                                        <input type="button" class="dash-button1" value="Go to Dashboard" onclick="">
                                    </td>
                                </tr>

                                <?php
											}
										}
										//
									}

									?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>


            <?php
				?>


            <div class="footer1">
                <?php require("mainfunctions/footerContent.php");	?>
            </div>

        </div>
    </div>





    <!-- JavaScript Libraries -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/superfish/superfish.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <!-- Contact Form JavaScript File -->

    <!-- Template Main Javascript File -->

    <script src="js/main.js"></script>
    <script src="menu/slicknav/jquery.slicknav.min.js"></script>

    <script>
    $(function() {
        $('.menu-navigation-icons').slicknav();
    });
    </script>

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