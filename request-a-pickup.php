<?php
session_start();

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {
	if (isset($_SESSION['waterUserLoginId']) && $_SESSION['waterUserLoginId'] > 0) {
		waterUserVisitedTo($_SESSION['waterUserLoginId'], 'request-a-pickup');
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
    <link href="css/request-a-pickup-form.css" rel="stylesheet">
    <link href="css/request-a-pickup-form1.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">

    <!--Menu =========================================================================================================================-->
    <link rel="stylesheet" href="menu/demo.css">
    <link rel="stylesheet" href="menu/navigation-icons.css">
    <link rel="stylesheet" href="menu/slicknav/slicknav.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans+Condensed:200,300,400,500,600,700,800"
        rel="stylesheet">

    <script language="javascript">
    function update_cart() {
        var total = 0;
        var order_total;
        var item_total;

        var weightitems = document.getElementsByName('weight[]');
        for (var i = 0; i < weightitems.length; i++) {
            item_total = weightitems[i].value;
            total = total + item_total * 1;
        }

        order_total = document.getElementById("order_total")
        document.getElementById("order_total").value = total.toFixed(0)

        item_total = 0;
        total = 0;
        var weightitems = document.getElementsByName('count[]');
        for (var i = 0; i < weightitems.length; i++) {
            item_total = weightitems[i].value;
            total = total + item_total * 1;
        }

        order_total = document.getElementById("order_count_total")
        document.getElementById("order_count_total").value = total.toFixed(0)

    }

    function chk_inputs() {
        var work_email = document.getElementById("work_email").value;
        work_email = work_email.trim();
        if (work_email == "") {
            document.getElementById('btnGenerateTrailerBOL').style.display = 'block';
            alert("Please enter a valid email.");
            document.getElementById("work_email").focus();
            return false;
        }

        if (work_email != "") {
            if (!(validateEmail(work_email))) {
                document.getElementById('btnGenerateTrailerBOL').style.display = 'block';
                alert("Please enter valid email.");

                document.getElementById("work_email").focus();
                return false;
            }
        }

        if (document.getElementById("commodity").value == "") {
            document.getElementById('btnGenerateTrailerBOL').style.display = 'block';
            alert("Please select the Commodity/Container.");
            document.getElementById("commodity").focus();
            return false;
        }
        if (document.getElementById("pickup_date").value == "") {
            document.getElementById('btnGenerateTrailerBOL').style.display = 'block';
            alert("Please enter Pickup Date.");
            document.getElementById("pickup_date").focus();
            return false;
        }
        if (document.getElementById("fullname").value == "") {
            document.getElementById('btnGenerateTrailerBOL').style.display = 'block';
            alert("Please enter Your Name.");
            document.getElementById("fullname").focus();
            return false;
        }

        if (document.getElementById("commodity").value != "" && document.getElementById("pickup_date").value != "" &&
            document.getElementById("fullname").value != "" && work_email != "") {
            document.getElementById('btnGenerateTrailerBOL').style.display = 'none';

            document.getElementById('display_loading').style.display = 'block';
            selectobject = document.getElementById("btnGenerateTrailerBOL");
            n_left = f_getPosition(selectobject, 'Left');
            n_top = f_getPosition(selectobject, 'Top');
            //alert(selectobject+" / "+n_left+" / "+n_top);
            document.getElementById('display_loading').style.left = (n_left + 250) + 'px';
            document.getElementById('display_loading').style.top = n_top - 100 + 'px';

            document.form1.submit();
        }
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

    function validateEmail(email) {
        const re =
            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
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
    <style type="text/css">
    #display_loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, 50%);
        display: none;
    }
    </style>

</head>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <div id="display_loading"><img src="images/wait_animated.gif" height="100px" width="100px"></div>
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
		$_SESSION['pgname'] = "request-a-pickup";
		?>
    <?php require("mainfunctions/top-header.php");

		$water_pick_up = "";
		$query1 = "SELECT water_pick_up FROM supplier_commodity_details where companyid= ?";
		$res1 = db_query($query1, array("i"), array($companyid));
		while ($row1 = array_shift($res1)) {
			$water_pick_up = $row1["water_pick_up"];
		}

		?>

    <!--Page title display here-->
    <div class="top-title">
        <h1>Request a Pickup &nbsp; <i class="fa fa-truck fa-flip-horizontal"></i></h1>
    </div>
    <!--End Page title-->
    <!--Main Content-->
    <div class="main-inner-container">
        <div class="inner-container1">
            <!--Code for Most recent pickup -->
            <div class="sub-title-container">
                <h2>Most recent pickups</h2>
            </div>
            <!--End sub-title-container-->
            <div id="no-more-tables">
                <table>
                    <!--Table titles here-->
                    <tr>
                        <th width="13%">Date Request</th>
                        <th width="15%">Commodity/Container</th>
                        <?php if ($water_pick_up == 1) { ?>
                        <th width="18%">Status</th>
                        <?php } ?>
                        <th width="5%">Pick-up Date</th>
                        <th width="18%" class="text-align1">Trailer #</th>
                        <th width="10%" class="text-align1">Dock #</th>
                        <th width="18%" class="text-align1">Requested By</th>
                        <th width="10%" class="text-align1">Bill of Lading</th>
                        <th width="10%" class="text-align1">Other Files</th>
                    </tr>
                    <!--End Table titles here-->
                    <tbody>
                        <!--Table Valures here-->
                        <?php
							$query = "SELECT 'ucb' as transtype, commodity, pa_date,pr_requestdate, pa_pickupdate,pr_trailer, pr_dock, pr_requestby, bol_filename, pr_requestdate_php, pr_pickupdate  FROM loop_transaction WHERE warehouse_id = ? 
						union 
						SELECT 'water' as transtype, commodity, pa_date,pr_requestdate, pa_pickupdate,pr_trailer, pr_dock,pr_requestby, bol_filename, pr_requestdate_php, pr_pickupdate FROM water_loop_transaction WHERE warehouse_id = ?
						ORDER BY pr_requestdate_php DESC limit 5";
							//AND sort_entered = 1
							//echo $query . " " . $warehouse_id . "<br>";
							$res = db_query($query, array("i", "i"), array($warehouse_id, $warehouse_id));
							while ($row = array_shift($res)) {
								$commodity = "";
								$bol_format = "";
								$query1 = "SELECT commodity, water_pick_up, bol_format FROM supplier_commodity_details where id= ?";
								$res1 = db_query($query1, array("i"), array($row["commodity"]));
								while ($row1 = array_shift($res1)) {
									$commodity = $row1["commodity"];
									$bol_format = $row1["bol_format"];
								}

								if ($row["transtype"] == "ucb") {
									$commodity = "Boxes";
								}

								$req_status = "Requested";
								if ($water_pick_up == 1 && $row["pa_date"] != "") {
									$req_status = "Pickup Confirmed";
								}
							?>
                        <tr>
                            <td>
                                <?php if ($row["pr_requestdate"] != "") {
											echo date('m-d-Y', strtotime($row["pr_requestdate"]));
										} ?>
                            </td>
                            <td class="text-align-left">
                                <?php echo $commodity; ?>
                            </td>
                            <?php if ($water_pick_up == 1) { ?>
                            <td class="text-align-left">
                                <?php echo $req_status; ?>
                            </td>
                            <?php } ?>

                            <td>
                                <?php if ($row["pr_pickupdate"] != "") {
											echo date('m/d/Y', strtotime($row["pr_pickupdate"]));
										} ?>
                            </td>
                            <td class="text-align-left">
                                <?php echo $row["pr_trailer"]; ?>
                            </td>
                            <td class="text-align1">
                                <?php echo $row["pr_dock"]; ?>
                            </td>
                            <td class="text-align1">
                                <?php echo $row["pr_requestby"]; ?></td>
                            </td>
                            <td class="text-align1">
                                <?php
										if ($row["bol_filename"] != "") {
											echo "<a target=_blank href='https://loops.usedcardboardboxes.com/files/" . $row["bol_filename"] . "'><img src='images/bill-icon.jpg' /></a>";
										}
										?>
                            </td>

                            <td class="text-align1">
                                <?php
										if ($bol_format	== "califiamauser") {
											echo "<a target=_blank href='https://www.ucbzerowaste.com/bolfiles/Mauser Form.xlsx'>Excel</a>";
										}
										if ($bol_format == "Southbend") {
											//echo "<a target=_blank href='https://www.ucbzerowaste.com/bolfiles/Mauser Form for the WATER PickUp Requests.pdf'>PDF</a>";
											echo "<a target=_blank href='https://www.ucbzerowaste.com/bolfiles/Mauser Form.xlsx'>Excel</a>";
										}
										?>
                            </td>
                        </tr>
                        <?php } ?>
                        <!--End Table Valures here-->
                    </tbody>
                </table>
            </div>
            <!--End Table-->
            <!--End Code for Most recent pickup -->


            <!--Start Code for Request new pickup -->
            <div class="sub-title-container">
                <h2>Request new pickup</h2>
                <h5>Trailer Information</h5>
            </div>
            <form method="post" name="form1" id="form1" action="pickuprequest_submit.php">
                <input type="hidden" id="companyid" name="companyid" value="<?php echo  $companyid ?>">
                <input type="hidden" id="warehouse_id" name="warehouse_id" value="<?php echo  $warehouse_id; ?>">

                <div id="enquiry_form-container">
                    <div id="enquiry_form">
                        <div class="form_field">Trailer Number:</div>
                        <div class="text_field">
                            <input name="trailer_no" type="text" class="form_text" id="trailer_no">
                        </div>
                    </div>

                    <div id="enquiry_form">
                        <div class="form_field">Dock Number:</div>
                        <div class="text_field">
                            <input name="dock" type="text" class="form_text" id="dock">
                        </div>
                    </div>

                    <div id="enquiry_form">
                        <div class="form_field">
                            <font color=red>*</font>Commodity or Container:
                        </div>
                        <div class="text_field">
                            <select id="commodity" name="commodity" class="form_text">
                                <option value=""></option>
                                <?php
									$query = " SELECT * FROM supplier_commodity_details where companyid = ? order by commodity";
									$res = db_query($query, array("i"), array($companyid));
									while ($row = array_shift($res)) {
									?>
                                <option value="<?php echo  $row["id"] ?>"><?php echo  $row["commodity"] ?></option>

                                <?php
									}
									?>
                            </select>

                        </div>
                    </div>

                    <div id="enquiry_form">
                        <div class="form_field">
                            <font color=red>*</font>Pickup Date:
                        </div>
                        <div class="text_field">
                            <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
                            <script LANGUAGE="JavaScript">
                            document.write(getCalendarStyles());
                            </script>
                            <script LANGUAGE="JavaScript">
                            var cal0xx = new CalendarPopup("listdiv");
                            cal0xx.showNavigationDropdowns();
                            </script>
                            <?php
								$pickup_date = isset($_REQUEST["pickup_date"]) ? strtotime($_REQUEST["pickup_date"]) : strtotime(date('m/d/Y'));
								?>

                            <input type="text" class="form_text" name="pickup_date" id="pickup_date"
                                value="<?php echo (isset($_REQUEST["pickup_date"]) && $_REQUEST["pickup_date"] != "") ? date('m/d/Y', $pickup_date) : date('m/d/Y') ?>">
                            <a style="float: left" href="#"
                                onclick="cal0xx.select(document.form1.pickup_date,'anchor0xx','MM/dd/yyyy'); return false;"
                                name="anchor0xx" id="anchor0xx">
                                <img src="images/calendar.png" alt="calendar" />
                            </a>
                            <div ID="listdiv"
                                STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                            </div>

                        </div>
                    </div>

                    <div id="enquiry_form">
                        <div class="form_field">
                            <font color=red>*</font>Your Name:
                        </div>
                        <div class="text_field">
                            <input type="text" class="form_text" name="fullname" id="fullname" />
                        </div>
                    </div>
                    <div id="enquiry_form">
                        <div class="form_field">Your Work Phone Number:</div>
                        <div class="text_field">
                            <input type="text" class="form_text" name="work_phone" id="work_phone" />
                        </div>
                    </div>
                    <div id="enquiry_form">
                        <div class="form_field">
                            <font color=red>*</font>Your Work email:
                        </div>
                        <div class="text_field">
                            <input type="text" class="form_text" name="work_email" id="work_email" />
                        </div>
                    </div>
                    <div id="enquiry_form">
                        <div class="form_field">Comments:</div>
                        <div class="text_field">
                            <textarea class="form_text" name="comments" id="comments"></textarea>
                        </div>
                    </div>

                </div>

                <div class="sub-title-container">
                    <h5>Item Information</h5>
                    <p>Please fill-in information for items being shipped, and enter weight amounts if known</p>
                </div>

                <div id="no-more-tables1">
                    <div id="enquiry_form-container1">

                        <div id="enquiry_form1">
                            <div class="form_field1"></div>
                            <div class="text_field_heading">COUNT</div>
                            <div class="text_field_heading">WEIGHT</div>
                        </div>

                        <?php
							//$i = 0;
							$noofctrls = 0;
							$qry_2 = "SELECT * from supplier_item_info WHERE displayflg = 1 and companyid = ? ";
							$res_2 = db_query($qry_2, array("i"), array($companyid));
							while ($fetch_data_2 = array_shift($res_2)) {
								$noofctrls = $noofctrls + 1;
							?>

                        <div id="enquiry_form1">
                            <input type="hidden" name="item[]" id="item[]" value="<?php echo $fetch_data_2['item']; ?>">
                            <div class="form_field1"><?php echo $fetch_data_2['item']; ?></div>
                            <div class="text_field1"><input type="text" class="form_text1" name="count[]" id="count[]"
                                    onchange="update_cart()"></div>
                            <div class="text_field2"><input type="text" class="form_text1" name="weight[]" id="weight[]"
                                    onchange="update_cart()"></div>
                        </div>

                        <?php
							}
							?>

                        <div id="enquiry_form1">
                            <div class="form_field1"><strong>TOTAL</strong></div>
                            <div class="text_field1 final_total"><input class="form_text1" name="order_count_total"
                                    id="order_count_total"></div>
                            <div class="text_field2 final_total"><input class="form_text1" name="order_total"
                                    id="order_total"></div>
                            <input name="noofctrls" id="noofctrls" type="hidden" value="<?php echo $noofctrls; ?>">
                        </div>

                        <div id="enquiry_form">
                            <div class="buttons1"><input type="button" class="logout-button2" id="btnGenerateTrailerBOL"
                                    onclick="return chk_inputs();" value="Request Pickup & Generate BOL"></div>
                        </div>
                    </div>
                </div>
            </form>


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