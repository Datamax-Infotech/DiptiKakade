<?php

require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();

$pageheading = "";
$main_sql = "";
$condt = "";
$display = "";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pickup Request</title>
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
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal4xx = new CalendarPopup("listdiv");
    cal4xx.showNavigationDropdowns();
    </script>

    <LINK rel='stylesheet' type='text/css' href='one_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <script>
    function validateFrm(e) {
        var name = document.getElementById("confirmed_by" + e).value;
        var email = document.getElementById("confirmed_byemail" + e).value;

        if (name.trim() === "") {
            alert("Please entry your name.");

            return false;
        }

        if (email.trim() === "") {
            alert("Please enter your email.");
            return false;
        }

        return true;
    }
    </script>
</head>

<body>
    <?php include("inc/header.php"); ?>
    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">

            <?php
			if (isset($_REQUEST["show"]) && $_REQUEST["show"] == "notconfirmed") {

				$pageheading = "Pick Ups Requested and Not Confirmed";
			} ?>
            <?php
			if (isset($_REQUEST["show"]) && $_REQUEST["show"] == "confirmed") {

				//$pageheading="Pick Up Requested and Confirmed";
				$pageheading = "Pick-up Confirmed";
			} ?>
            <?php if (isset($_REQUEST["show"]) && $_REQUEST["show"] == "completed") {

				$pageheading = "Pickups Completed";
			} ?>

            <div style="float: left;"><?php echo $pageheading; ?>
                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">
                        <?php echo $pageheading; ?>
                    </span>
                </div>

                <div style="height: 13px;">&nbsp;</div>
            </div>
        </div>
        <table cellpadding=0 cellspacing=0 width="1200px" class="table_style">


            <?php

			$open = "<img src=\"images/circle_open.gif\" border=\"0\">";
			$half = "<img src=\"images/circle__partial.gif\" border=\"0\">";
			$full = "<img src=\"images/complete.jpg\" border=\"0\">";


			if (isset($_REQUEST["show"]) && $_REQUEST["show"] == "all") {
				$main_sql = "Select loop_warehouse.id as wid,water_loop_transaction.pr_requestdate, water_loop_transaction.id as rec_id, loop_warehouse.company_name, loop_warehouse.b2bid from water_loop_transaction inner join loop_warehouse on loop_warehouse.id = water_loop_transaction.warehouse_id where (water_loop_transaction.pr_requestdate <> '' or water_loop_transaction.pr_requestdate is not null) and loop_warehouse.rec_type = 'Manufacturer' group by warehouse_id";
				$display = 'water_sort';
			}

			if (isset($_REQUEST["show"]) && $_REQUEST["show"] == "notconfirmed") {

				$main_sql = "Select loop_warehouse.id as wid, water_loop_transaction.id as rec_id, loop_warehouse.company_name, loop_warehouse.b2bid, water_loop_transaction.pa_pickupdate from water_loop_transaction inner join loop_warehouse on loop_warehouse.id = water_loop_transaction.warehouse_id where (water_loop_transaction.pa_pickupdate = '' or water_loop_transaction.pa_pickupdate is null) and loop_warehouse.rec_type = 'Manufacturer' $swhere_condition";
				$display = 'water_sort_pickup#watersort';
			}

			if (isset($_REQUEST["show"]) && $_REQUEST["show"] == "confirmed") {
				$main_sql = "Select loop_warehouse.id as wid, water_loop_transaction.id as rec_id, water_loop_transaction.pa_pickupdate, loop_warehouse.company_name, loop_warehouse.b2bid from water_loop_transaction inner join loop_warehouse on loop_warehouse.id = water_loop_transaction.warehouse_id where ((water_loop_transaction.pa_pickupdate <> '' or water_loop_transaction.cnfmPickup = 'Confirmed' ) and (water_loop_transaction.cp_date = '' or water_loop_transaction.cp_date is null) ) OR (water_loop_transaction.cp_date <> '' and water_loop_transaction.pa_pickupdate <> '') and loop_warehouse.rec_type = 'Manufacturer'";
				$display = 'water_sort_pickup#watersort';
			}



			if (isset($_REQUEST["show"]) && $_REQUEST["show"] == "completed") {
				$main_sql = "Select loop_warehouse.id as wid, water_loop_transaction.id as rec_id, loop_warehouse.company_name, loop_warehouse.b2bid from water_loop_transaction inner join loop_warehouse on loop_warehouse.id = water_loop_transaction.warehouse_id where (water_loop_transaction.cp_date <> '' and water_loop_transaction.pa_pickupdate <> '') and loop_warehouse.rec_type = 'Manufacturer' $swhere_condition";
				$display = 'water_sort_pickup#watersort';
			}


			$no_rows = 0;

			$data_res = db_query($main_sql);

			$i = 0;
			while ($data = array_shift($data_res)) {

				$i++;
				echo '<script type="text/JavaScript"> 
				var cal4xx' . $i . ' = new CalendarPopup("listdiv");
				cal4xx' . $i . '.showNavigationDropdowns();
				</script>';
				$todisplay_data = "yes";
				if ($todisplay_data == "yes") {

					if (isset($_REQUEST["only_conf"])) {
						$condt .= " and double_chk_confirm=1";
					} else {
					}
					if ((isset($_REQUEST["date_from"])) && isset($_REQUEST["date_to"])) {
						if ($_REQUEST["date_from"] != "" && $_REQUEST["date_to"] != "") {
							//
							$date_from = date("Y-m-d", strtotime($_REQUEST["date_from"]));
							$date_to = date("Y-m-d", strtotime($_REQUEST["date_to"]));
							//
							$condt .= " and (transaction_date >= '" . $date_from . "' and transaction_date <= '" . $date_to . "')";
						}
					} else {
					}

					$get_trans_sql = "SELECT * FROM water_loop_transaction left join supplier_commodity_details on water_loop_transaction.commodity = supplier_commodity_details.id WHERE water_loop_transaction.id = '" . $data["rec_id"] . "' and warehouse_id = '" . $data["wid"] . "' " . $condt;
					db();
					$trans_result = db_query($get_trans_sql);
					$trans_num = tep_db_num_rows($trans_result);
					if ($trans_num > 0) {
			?>
            <tr valign="middle" id="tbl_div">
                <td colspan="4" class="display_title"><a target="_blank"
                        href="viewCompany.php?ID=<?php echo $data["b2bid"]; ?>&proc=View&searchcrit=&show=watertransactions&rec_type=Manufacturer"><?php echo get_nickname_val($data["company_name"], $data["b2bid"]); ?></a>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table width="100%" cellpadding="3" cellspacing="1">

                        <tr class="display_maintitle">
                            <td width="150px">Pick-up Date Request</td>
                            <td width="150px">Pick-up Date</td>
                            <td width="150px">Commodity</td>
                            <td width="150px">Trailer #</td>
                            <td width="150px">Pickup Status</td>
                            <td width="150px">Pick-up Date</td>
                            <td width="150px">Pick-up Date Confirmed By</td>
                            <td width="150px">Confirmed By Email</td>
                            <?php if ((isset($_REQUEST["show"]) && $_REQUEST["show"] == "notconfirmed") || (isset($_REQUEST["show"]) && $_REQUEST["show"] == "confirmed")) { ?>
                            <td width="150px">Update Status</td>
                            <?php } ?>
                        </tr>
                        <?php
									$open = "<img src=\"images/circle_open.gif\" border=\"0\">";
									$half = "<img src=\"images/circle__partial.gif\" border=\"0\">";
									$full = "<img src=\"images/complete.jpg\" border=\"0\">";
									while ($tranlist = array_shift($trans_result)) {
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
                                <a href="viewCompany_func_water-mysqli.php?ID=<?php echo $data["b2bid"] ?>&show=watertransactions&warehouse_id=<?php echo $data["wid"]; ?>&b2bid=<?php echo $data["b2bid"]; ?>&rec_id=<?php echo $tranlist["id"]; ?>&rec_type=Manufacturer&proc=View&searchcrit=&display=<?php echo $display; ?>"
                                    target="_blank">
                                    <?php echo date("Y-m-d H:m:i", strtotime($tranlist["pr_requestdate_php"])); ?>
                                </a>
                            </td>
                            <td width="150px" class="display_table">
                                <?php echo $tranlist["pr_pickupdate"]; ?>
                            </td>
                            <td width="150px" class="display_table">
                                <?php echo $tranlist["commodity"]; ?>
                            </td>
                            <td width="140px" class="display_table">
                                <?php echo $tranlist["dt_trailer"]; ?>
                            </td>
                            <td width="150px" class="display_table">
                                <?php
												//if (($tranlist["reported_in_water"] == 1)){		 echo $full; 			
												//} elseif ($tranlist["pa_pickupdate"] != "") {			echo $half; 	water_loop_transaction.cp_date	
												//} else { 			echo $open; 		}

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
                            <?php if (isset($_REQUEST["show"]) && $_REQUEST["show"] == "notconfirmed") { ?>
                            <td width="150px" class="display_table">
                                <form action="addstatusfrompikupreq.php" method="post" name="frmPA<?php echo $i; ?>"
                                    onsubmit="return validateFrm(<?php echo $i; ?>)">
                                    <input type="hidden" name="rec_id" value="<?php echo $data["rec_id"]; ?>" />
                                    <input type="hidden" name="updateStatus" value="notconfirmed" />
                                    <table border="0" style="width: 444px">
                                        <tr bgColor="#e4e4e4">
                                            <td height="13" style="width: 86px" class="style1" align="right">Pickup Date
                                            </td>
                                            <td height="13" class="style1" align="left" colspan="2">
                                                <input type=text name="pa_pickupdate"
                                                    value="<?php echo $data["pa_pickupdate"]; ?>" size="11">
                                                <a href="#"
                                                    onclick="cal4xx<?php echo $i; ?>.select(document.frmPA<?php echo $i; ?>.pa_pickupdate,'anchor4xx<?php echo $i; ?>','MM/dd/yyyy'); return false;"
                                                    name="anchor4xx<?php echo $i; ?>" id="anchor4xx<?php echo $i; ?>">
                                                    <img border="0" src="images/calendar.jpg">
                                                </a>
                                                <div ID="listdiv"
                                                    STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr bgColor="#e4e4e4">
                                            <td height="13" style="width: 150px" class="style1" align="right">Pick-up
                                                Date Confirmed by*</td>
                                            <td height="13" class="style1" align="left" colspan="2">
                                                <input size="20" class="" value="" type="text" name="confirmed_by"
                                                    id="confirmed_by<?php echo $i; ?>" />
                                            </td>
                                        </tr>
                                        <tr bgColor="#e4e4e4">
                                            <td height="13" style="width: 150px" class="style1" align="right">Confirmed
                                                by Email*</td>
                                            <td height="13" class="style1" align="left" colspan="2">
                                                <input size="20" class="" value="" type="email" name="confirmed_byemail"
                                                    id="confirmed_byemail<?php echo $i; ?>" />
                                            </td>
                                        </tr>
                                        <tr bgColor="#e4e4e4">
                                            <td height="13" class="style1" align="center" colspan="2"><input type=submit
                                                    value="Submit" style="cursor:pointer;"></td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                            <?php } elseif (isset($_REQUEST["show"]) && $_REQUEST["show"] == "confirmed") { ?>
                            <td width="150px" class="display_table">
                                <?php if ($tranlist["cp_date"] != '') {
													} else {  ?>
                                <form action="addstatusfrompikupreq.php" method="post" name="frmPA<?php echo $i; ?>">
                                    <input type="hidden" name="rec_id" value="<?php echo $data["rec_id"]; ?>" />
                                    <input type="hidden" name="updateStatus" value="confirmed" />
                                    <table border="0" style="width: 444px">
                                        <tr bgColor="#e4e4e4">
                                            <td height="13" style="width: 150px" class="style1" align="right">Notes</td>
                                            <td height="13" class="style1" align="left" colspan="2">
                                                <textarea
                                                    name="cp_notes"><?php echo $tranlist["cp_notes"]; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr bgColor="#e4e4e4">
                                            <td height="13" class="style1" align="center" colspan="2"><input type=submit
                                                    value="Submit" style="cursor:pointer;"></td>
                                        </tr>
                                    </table>
                                </form>
                                <?php } ?>
                            </td>

                            <?php } ?>
                        </tr>

                        <?php
									}
									?>
                    </table>

                </td>
            </tr>
            <?php
					}
				}
			}

			?>
        </table>
</body>

</html>