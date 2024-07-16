<?php
session_start();

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {
	if (isset($_SESSION['waterUserLoginId']) && $_SESSION['waterUserLoginId'] > 0) {
		waterUserVisitedTo($_SESSION['waterUserLoginId'], 'pickups-in-process');
	}

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
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <!--Menu =========================================================================================================================-->
    <link rel="stylesheet" href="menu/demo.css">
    <link rel="stylesheet" href="menu/navigation-icons.css">
    <link rel="stylesheet" href="menu/slicknav/slicknav.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">

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
    </style>

    <script language="javascript">
    function openInNewTab(url) {
        var win = window.open(url, '_blank');
        win.focus();
    }

    function runreport_trailer() {
        stdt = document.getElementById('start_date').value;
        enddt = document.getElementById('end_date').value;

        openInNewTab("processedtrailerreport.php?start_date=" + stdt + "&end_date=" + enddt);
    }

    function runreport_byweight() {
        stdt = document.getElementById('start_date').value;
        enddt = document.getElementById('end_date').value;

        openInNewTab("processedtrailerreportweights.php?start_date=" + stdt + "&end_date=" + enddt);
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


</head>

<body>
    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>

    <?php
		db();

		$companyid = 0;
		$warehouse_id = 0;
		$company_name = "";
		$company_logo = "";
		$sql = "SELECT companyid FROM supplierdashboard_usermaster WHERE loginid=? and activate_deactivate = 1";
		//echo $sql . "<br>";
		$result = db_query($sql, array("i"), array($_SESSION['loginid']));
		while ($myrowsel = array_shift($result)) {

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

		//db_b2b();
		$_SESSION['pgname'] = "pickups-in-process";
		?>

    <?php require("mainfunctions/top-header.php");

		$water_pick_up = "";
		$query1 = "SELECT water_pick_up FROM supplier_commodity_details where water_pick_up = 1 and companyid= ?";
		$res1 = db_query($query1, array("i"), array($companyid));
		while ($row1 = array_shift($res1)) {
			$water_pick_up = $row1["water_pick_up"];
		}

		?>


    <div class="top-title">
        <h1>Pickups in Process &nbsp; <i class="fa fa-search"></i></h1>
    </div>

    <div class="main-inner-container">
        <div class="inner-container1">


            <!-- New section start  -->
            <div class="sub-title-container">
                <h3>PICKUP REQUESTED AND NOT ACKNOWLEDGED</h3>
            </div>

            <div id="no-more-tables">
                <table>
                    <thead>
                        <tr>
                            <th width="10%">Date Request</th>
                            <th width="10%">Commodity</th>
                            <?php if ($water_pick_up == 1) { ?>
                            <th width="10%">Status</th>
                            <?php } ?>
                            <th width="10%" class="text-align1">Trailer #</th>
                            <th width="10%" class="text-align1">Dock #</th>
                            <th width="10%" class="text-align1">Requested By</th>
                            <th width="10%" class="text-align1">Pick-up Date</th>
                            <th width="10%" class="text-align1">Pick-up Date Confirmed By</th>
                            <th width="10%" class="text-align1">Confirmed By Email</th>
                            <th width="10%" class="text-align1">Bill of Lading</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php


							$main_sql1 = "SELECT 'water' as transtype, loop_warehouse.id AS wid, pr_trailer, water_loop_transaction.id AS rec_id, loop_warehouse.company_name, loop_warehouse.b2bid, water_loop_transaction.commodity, water_loop_transaction.pr_requestdate, water_loop_transaction.dt_trailer, water_loop_transaction.pr_dock, water_loop_transaction.pr_requestby, water_loop_transaction.bol_filename  
			FROM water_loop_transaction INNER JOIN loop_warehouse ON loop_warehouse.id = water_loop_transaction.warehouse_id 
			WHERE warehouse_id = " . $warehouse_id . " and (water_loop_transaction.pa_pickupdate = '' OR water_loop_transaction.pa_pickupdate is NULL) 
			AND loop_warehouse.rec_type = 'Manufacturer'";
							db();
							$data_res1 = db_query($main_sql1);
							//echo $main_sql1 . "<br>";
							$pr_notcomfirm = tep_db_num_rows($data_res1);


							while ($row = array_shift($data_res1)) {
								$commodity = "";
								$water_pick_up = 0;
								$query1 = "SELECT commodity, bol_format, water_pick_up FROM supplier_commodity_details where id= ?";
								$res1 = db_query($query1, array("i"), array($row["commodity"]));
								while ($row1 = array_shift($res1)) {
									$commodity = $row1["commodity"];
									$bol_format = $row1["bol_format"];
									$water_pick_up = $row1["water_pick_up"];
								}

								if ($water_pick_up == 1) {
									$req_status = "Requested";
									if ($water_pick_up == 1 && $row["pa_date"] != "") {
										$req_status = "Pickup Confirmed";
									}

							?>
                        <tr>
                            <td><?php if ($row["pr_requestdate"] != "") {
												echo date('m-d-Y', strtotime($row["pr_requestdate"]));
											} ?></td>
                            <td class="text-align-left"><?php echo $commodity; ?></td>
                            <?php if ($water_pick_up == 1) { ?>
                            <td class="text-align-left"><?php echo $req_status; ?></td>
                            <?php } ?>
                            <td class="text-align-left"><?php echo $row["pr_trailer"]; ?></td>
                            <td class="text-align-left"><?php echo $row["pr_dock"]; ?></td>
                            <td class="text-align-left"><?php echo $row["pr_requestby"]; ?></td>
                            <td class="text-align-left">&nbsp;</td>
                            <td class="text-align-left">&nbsp;</td>
                            <td class="text-align-left">&nbsp;</td>
                            <td class="text-align1">
                                <?php
											if ($row["bol_filename"] != "") {
												echo "<a target=_blank href='https://loops.usedcardboardboxes.com/files/" . $row["bol_filename"] . "'><img src='images/bill-icon.jpg' /></a>";
											}
											if (isset($bol_format)	== "califiamauser") {
												echo "<br><a target=_blank href='https://www.ucbzerowaste.com/bolfiles/Mauser Form.xlsx'>Excel</a>";
											}
											if (isset($bol_format) == "Southbend") {
												echo "<br><a target=_blank href='https://www.ucbzerowaste.com/bolfiles/Mauser Form for the WATER PickUp Requests.pdf'>PDF</a>";
											}
											?>
                            </td>
                        </tr>
                        <?php
								}
							}
							?>
                    </tbody>
                </table>
            </div>

            <div class="sub-title-container">
                <h3>PICKUP ACKNOWLEDGED</h3>
            </div>
            <div id="no-more-tables">
                <table>
                    <thead>
                        <tr>
                            <th width="10%">Date Request</th>
                            <th width="10%">Commodity</th>
                            <?php if ($water_pick_up == 1) { ?>
                            <th width="10%">Status</th>
                            <?php } ?>
                            <th width="10%" class="text-align1">Trailer #</th>
                            <th width="10%" class="text-align1">Dock #</th>
                            <th width="10%" class="text-align1">Requested By</th>
                            <th width="10%" class="text-align1">Pick-up Date</th>
                            <th width="10%" class="text-align1">Pick-up Date Confirmed By</th>
                            <th width="10%" class="text-align1">Confirmed By Email</th>
                            <th width="10%" class="text-align1">Bill of Lading</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php


							$main_sql_conf = "SELECT 'water' as transtype, pr_trailer, loop_warehouse.id AS wid, water_loop_transaction.id AS rec_id, loop_warehouse.company_name, loop_warehouse.b2bid, 
			water_loop_transaction.commodity, water_loop_transaction.pr_requestdate, water_loop_transaction.dt_trailer, water_loop_transaction.pr_dock, 
			water_loop_transaction.pr_requestby, water_loop_transaction.cp_date, water_loop_transaction.pa_pickupdate, water_loop_transaction.pickup_date_confirmed_by, 
			water_loop_transaction.pa_employee, water_loop_transaction.confirmed_by_email, water_loop_transaction.bol_filename 
			FROM water_loop_transaction INNER JOIN loop_warehouse ON loop_warehouse.id = water_loop_transaction.warehouse_id 
			WHERE warehouse_id = " . $warehouse_id . " and (((water_loop_transaction.pa_pickupdate <> '' OR 
			water_loop_transaction.cnfmPickup = 'Confirmed') AND (water_loop_transaction.cp_date = '' OR 
			water_loop_transaction.cp_date is NULL) ) OR (water_loop_transaction.cp_date <> '')) AND loop_warehouse.rec_type = 'Manufacturer' order by water_loop_transaction.id desc limit 60 ";
							//order by rec_id DESC";
							//echo $main_sql_conf . "<br>";
							db();
							$data_res1 = db_query($main_sql_conf);
							//echo "<pre>"; print_r($data_res1); echo "</pre>";
							$pr_notcomfirm = tep_db_num_rows($data_res1);

							while ($row = array_shift($data_res1)) {
								$commodity = "";
								$query1 = "SELECT commodity, water_pick_up FROM supplier_commodity_details where id='" . $row["commodity"] . "'";
								db();
								$res1 = db_query($query1);

								$water_pick_up = 0;
								while ($row1 = array_shift($res1)) {
									$commodity = $row1["commodity"];
									$water_pick_up = $row1["water_pick_up"];
								}

								if ($water_pick_up == 1) {
									$req_status = "Confirmed";
									if ($row["cp_date"] != "") {
										//$req_status = "completed";
										$req_status = "Confirmed";
									}
									$confirmby = "";
									$confirmemail = "";
									if ($row["pickup_date_confirmed_by"] != '') {
										$confirmby = $row["pickup_date_confirmed_by"];
										$confirmemail = $row["confirmed_by_email"];
									} else {
										$empinit = $row["pa_employee"];
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
                            <td><?php if ($row["pr_requestdate"] != "") {
												echo date('m-d-Y', strtotime($row["pr_requestdate"]));
											} ?></td>
                            <td class="text-align-left"><?php echo $commodity; ?></td>
                            <?php if ($water_pick_up == 1) { ?>
                            <td class="text-align-left"><?php echo $req_status; ?></td>
                            <?php } ?>
                            <td class="text-align-left"><?php echo $row["pr_trailer"]; ?></td>
                            <td class="text-align-left"><?php echo $row["pr_dock"]; ?></td>
                            <td class="text-align-left"><?php echo $row["pr_requestby"]; ?></td>
                            <td class="text-align-left"><?php echo $row["pa_pickupdate"]; ?></td>
                            <td class="text-align-left"><?php echo $confirmby; ?></td>
                            <td class="text-align-left"><?php echo $confirmemail; ?></td>
                            <td class="text-align1">
                                <?php
											if ($row["bol_filename"] != "")
												echo "<a target=_blank href='https://loops.usedcardboardboxes.com/files/" . $row["bol_filename"] . "'><img src='images/bill-icon.jpg' /></a>";
											?>
                            </td>
                        </tr>
                        <?php
								}
							}
							?>
                    </tbody>
                </table>
            </div>
            <!-- 
<div class="sub-title-container">	<h3>PICKUP COMPLETED</h3></div>
<div  id="no-more-tables">
	<table>
			<thead>
			  <tr>
				<th width="18%">Date Request</th>
				<th width="18%">Commodity</th>
				<?php if ($water_pick_up == 1) { ?>
					<th width="18%">Status</th>
				<?php } ?>	
				<th width="18%" class="text-align1">Trailer #</th>
				<th width="18%" class="text-align1" >Dock #</th>
				<th width="18%" class="text-align1">Requested By</th>
				<th width="18%" class="text-align1">Bill of Lading</th>
			  </tr>
			</thead>
			<tbody>
			<?php

			$main_sql_compl = "SELECT 'ucb' as transtype, pr_trailer, loop_warehouse.id AS wid, loop_transaction.id AS rec_id, loop_warehouse.company_name, loop_warehouse.b2bid, loop_transaction.commodity, loop_transaction.pr_requestdate, loop_transaction.dt_trailer, 
			loop_transaction.pr_dock, loop_transaction.pr_requestby, loop_transaction.bol_filename  
			FROM loop_transaction INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction.warehouse_id 
			WHERE warehouse_id = " . $warehouse_id . " and (loop_transaction.cp_date <> '' AND loop_transaction.pa_pickupdate <> '')
			AND loop_warehouse.rec_type = 'Manufacturer'
			union 
			SELECT 'water' as transtype, pr_trailer, loop_warehouse.id AS wid, water_loop_transaction.id AS rec_id, loop_warehouse.company_name, loop_warehouse.b2bid, water_loop_transaction.commodity, 
			water_loop_transaction.pr_requestdate, water_loop_transaction.dt_trailer, water_loop_transaction.pr_dock, water_loop_transaction.pr_requestby, water_loop_transaction.bol_filename 
			FROM water_loop_transaction INNER JOIN loop_warehouse ON loop_warehouse.id = water_loop_transaction.warehouse_id 
			WHERE warehouse_id = " . $warehouse_id . " and (water_loop_transaction.cp_date <> '' AND water_loop_transaction.pa_pickupdate <> '') 
			AND loop_warehouse.rec_type = 'Manufacturer'";
			db();
			$data_res1 = db_query($main_sql_compl);
			//echo "<pre>"; print_r($data_res1); echo "</pre>";
			$pr_notcomfirm = tep_db_num_rows($data_res1);


			while ($row = array_shift($data_res1)) {
				$commodity = "";
				$query1 = "SELECT commodity FROM supplier_commodity_details where id= ?";
				$res1 = db_query($query1, array("i"), array($row["commodity"]));
				while ($row1 = array_shift($res1)) {
					$commodity = $row1["commodity"];
				}

				$req_status = "Completed";
				if ($water_pick_up == 1 && $row["pa_date"] != "") {
					//$req_status = "Pickup Confirmed";
				}

			?>
				<tr>
				  <td><?php if ($row["pr_requestdate"] != "") {
							echo date('m-d-Y', strtotime($row["pr_requestdate"]));
						} ?></td>
				  <td class="text-align-left"><?php echo $commodity; ?></td>
				  <?php if ($water_pick_up == 1) { ?>
						<td class="text-align-left"><?php echo $req_status; ?></td>
				  <?php } ?>	
				  <td class="text-align-left"><?php echo $row["pr_trailer"]; ?></td>
				  <td class="text-align-left"><?php echo $row["pr_dock"]; ?></td>
				  <td class="text-align-left"><?php echo $row["pr_requestby"]; ?></td>
				  <td class="text-align1">
					<?php
					if ($row["bol_filename"] != "")
						//echo "<a target=_blank href='https://loops.usedcardboardboxes.com/files/".$row["bol_filename"]."'><img src='images/bill-icon.jpg' /></a>";
					?>
				  </td>
				</tr>
				<?php
		}
				?>
			</tbody>
    </table>
</div> 
-->
            <!-- New section End -->
            <div class="sub-title-container">
                <h3>UCB SORTED TRAILERS REPORT</h3>
            </div>

            <div id="no-more-tables1new">
                <div id="enquiry_form-container1">
                    <form method="post" role="form" name="form1" id="form1" class="form-horizontal" action="">

                        <div id="enquiry_form1">
                            <div class="text_field1">
                                <p>From</p>
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

                                <input name="start_date" type="text" class="form_text1" id="start_date"
                                    value="<?php echo (isset($_REQUEST["start_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $start_date) : date('m/01/Y') ?>">
                                <a style="float: left" href="#"
                                    onclick="cal1xx.select(document.form1.start_date,'anchor0xx','MM/dd/yyyy'); return false;"
                                    name="anchor0xx" id="anchor0xx">
                                    <img src="images/calendar.png" alt="calendar" />
                                </a>
                            </div>

                            <div class="text_field1">
                                <p>To</p>
                                <input type="text" class="form_text1" id="end_date" name="end_date"
                                    value="<?php echo (isset($_REQUEST["end_date"]) && $_REQUEST["start_date"] != "") ? date('m/d/Y', $end_date) : date('m/d/Y') ?>">
                                <a style="float: left" href="#"
                                    onclick="cal2xx.select(document.form1.end_date,'anchor1xx','MM/dd/yyyy'); return false;"
                                    name="anchor1xx" id="anchor1xx">
                                    <img src="images/calendar.png" alt="calendar" />
                                </a>
                            </div>
                            <div class="text_field2"><strong>
                                    <p>Shown by</p>
                                </strong>
                                <input type="button" class="logout-button4" value="Weight"
                                    onclick="runreport_byweight()">
                                <input type="button" class="logout-button4" value="Trailer"
                                    onclick="runreport_trailer()">
                            </div>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>
                        </div>

                    </form>
                </div>
            </div>


            <?php //}
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