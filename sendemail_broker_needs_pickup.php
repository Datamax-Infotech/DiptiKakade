<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

// set_time_limit(0);
// ini_set('memory_limit', '-1');

db();
$po_employee = "";
$sql = "SELECT po_employee from loop_transaction_buyer where id = ?";
$result_comp = db_query($sql, array("i"), array($_REQUEST["rec_id"]));
while ($row_comp = array_shift($result_comp)) {
    $po_employee = $row_comp["po_employee"];
}

$sellto_eml = "";
$acc_owner = "";
$acc_owner_eml = "";
$shipto_name = "";
$shipto_email = "";
$link_purchasing_acc_owner_eml = "";
$link_purchasing_acc_owner_ucbzw_eml = "";
$pickup_date = "";
$pickup_date_2 = "";
db_b2b();
$result_crm = db_query("Select * from companyInfo Where ID = " . $_REQUEST["compid"]);
$company_name = "";
$to_eml_crm = "";
$sellto_name = "";
while ($myrowsel_main = array_shift($result_crm)) {
    $shipto_name = $myrowsel_main["shipContact"];
    $shipto_email = $myrowsel_main["shipemail"];
    $companyName = $myrowsel_main["company"];

    $dock_pickup_date = "";

    db();
    $result_n = db_query("Select date from loop_transaction_freight where trans_rec_id = " . $_REQUEST["rec_id"]);
    while ($myrowsel_n = array_shift($result_n)) {
        if ($myrowsel_n["date"] != "") {
            $dock_pickup_date = date('m/d/Y', strtotime($myrowsel_n["date"]));
            $pickup_date = "for " . date("m/d/Y", strtotime($myrowsel_n["date"]));
            $pickup_date_2 = "on " . date("m/d", strtotime($myrowsel_n["date"]));
        }
    }

    $emp_name = '';
    $emp_title = '';
    $emp_phoneext = '';
    $emp_email = '';

    db();
    $result_n = db_query("Select name, email, phoneext, title from loop_employees Where b2b_id = " . $myrowsel_main["assignedto"]);
    while ($myrowsel_n = array_shift($result_n)) {
        $acc_owner = $myrowsel_n["name"];
        $acc_owner_eml = $myrowsel_n["email"];

        $emp_name = $myrowsel_n["name"];
        $emp_title = $myrowsel_n["title"];
        $emp_phoneext = $myrowsel_n["phoneext"];
        //$emp_email = $myrowsel_n["email"]; 
    }

    $sellto_name = $myrowsel_main["contact"];
    $sellto_eml = $myrowsel_main["email"];

    if ($po_employee != "") {
        $sql = "SELECT email FROM loop_employees WHERE status='Active' and initials = ?";
        $result_comp = db_query($sql, array("s"), array($po_employee));
        while ($row_comp = array_shift($result_comp)) {
            $acc_owner_eml = $row_comp["email"];
        }
    }

    $fr_name = "";
    $fr_contact = "";
    $fr_ph = "";
    $fr_eml = "";

    db();
    $result_n = db_query("Select loop_freightvendor.* from loop_transaction_buyer_freightview inner join loop_freightvendor on loop_freightvendor.id = loop_transaction_buyer_freightview.broker_id Where trans_rec_id = " . $_REQUEST["rec_id"]);
    while ($myrowsel_n = array_shift($result_n)) {
        $fr_name = $myrowsel_n["company_name"];
        $fr_contact = $myrowsel_n["company_contact"];
        $fr_ph = $myrowsel_n["company_phone"];
        $fr_eml = $myrowsel_n["company_email"];
    }
    $brokerInfo = "";
    $brokerInfo = $fr_name;
    if ($fr_contact != "") {
        $brokerInfo .= "<br>" . $fr_contact;
    }
    if ($fr_ph != "") {
        $brokerInfo .= "<br>" . $fr_ph;
    }
    if ($fr_eml != "") {
        $brokerInfo .= "<br><a href='mailto:" . $fr_eml . "'>" . $fr_eml . "</a>";
    }

    $sales_order_box_id = 0;

    db();
    $result_n = db_query("SELECT * FROM loop_salesorders WHERE trans_rec_id = " . $_REQUEST["rec_id"]);
    while ($myrowsel_n = array_shift($result_n)) {
        $loc_warehouse_id = $myrowsel_n["location_warehouse_id"];
        $sales_order_box_id = $myrowsel_n["box_id"];
    }

    $get_wh = "SELECT vendor_b2b_rescue FROM loop_boxes where id = " . $sales_order_box_id;

    db();
    $get_wh_res = db_query($get_wh);
    while ($the_wh = array_shift($get_wh_res)) {
        $vendor_b2b_rescue = $the_wh["vendor_b2b_rescue"];
    }

    if (isset($loc_warehouse_id) == 238) {
        $loc_warehouse_id = isset($vendor_b2b_rescue);
    }
    $warehouse_name = "";
    $warehouse_address = "";
    $warehouse_eml = "";
    $warehouse_phone = "";
    $warehouse_calendly_link = "";
    $sets_own_dock_appointment = "";
    $warehouse_contact = "";

    db();
    $result_n = db_query("SELECT * FROM loop_warehouse WHERE id = " . isset($loc_warehouse_id));
    //echo "SELECT * FROM loop_warehouse WHERE id = " . $loc_warehouse_id . "<br>";
    while ($myrowsel_n = array_shift($result_n)) {
        $sets_own_dock_appointment = $myrowsel_n["sets_own_dock_appointment"];
        $warehouse_contact = $myrowsel_n["warehouse_contact"];

        $warehouse_name = $myrowsel_n["warehouse_name"];
        $warehouse_address = $myrowsel_n["warehouse_address1"] . " " . $myrowsel_n["warehouse_address2"] . ", " . $myrowsel_n["warehouse_city"] . ", " . $myrowsel_n["warehouse_state"] . " " . $myrowsel_n["warehouse_zip"];
        $warehouse_eml = $myrowsel_n["warehouse_contact_email"];
        $warehouse_phone = $myrowsel_n["warehouse_contact_phone"];
        $warehouse_calendly_link = $myrowsel_n["calendly_link"];
    }

    $virtual_inventory_company_id = 0;
    $virtual_inventory_trans_id = 0;
    $po_ponumber = "";


    db();
    $result_n1 = db_query("Select po_ponumber, virtual_inventory_company_id, virtual_inventory_trans_id from loop_transaction_buyer where id = '" . $_REQUEST["rec_id"] . "'");
    while ($myrowsel_n1 = array_shift($result_n1)) {
        $po_ponumber = $myrowsel_n1["po_ponumber"];
        $virtual_inventory_company_id = $myrowsel_n1["virtual_inventory_company_id"];
        $virtual_inventory_trans_id = $myrowsel_n1["virtual_inventory_trans_id"];
    }

    if ($virtual_inventory_company_id > 0) {

        db_b2b();
        $result_crm1 = db_query("Select ucbzw_account_owner, assignedto from companyInfo Where loopid = '" . $virtual_inventory_company_id . "'");
        while ($myrowsel_main1 = array_shift($result_crm1)) {

            db_b2b();
            $result_n = db_query("Select name, email from employees Where employeeID = '" . $myrowsel_main1["assignedto"] . "'");
            while ($myrowsel_n = array_shift($result_n)) {
                $link_purchasing_acc_owner_nm = $myrowsel_n["name"];
                $link_purchasing_acc_owner_eml = ";" . $myrowsel_n["email"];
            }

            if ($myrowsel_main1["ucbzw_account_owner"] != "") {

                db_b2b();
                $result_n = db_query("Select name, email from employees Where employeeID = '" . $myrowsel_main1["ucbzw_account_owner"] . "'");
                while ($myrowsel_n = array_shift($result_n)) {
                    $link_purchasing_acc_owner_ucbzw_nm = $myrowsel_n["name"];
                    $link_purchasing_acc_owner_ucbzw_eml = ";" . $myrowsel_n["email"];
                }
            }
        }
    }

    db_b2b();

    if ($sets_own_dock_appointment == "yes" && $warehouse_calendly_link != "") {
    } else {
        if ($virtual_inventory_trans_id > 0) {

            $warehouse_id = 0;
            db();
            $result_n = db_query("Select warehouse_id from loop_transaction where id = " . $virtual_inventory_trans_id);
            while ($myrowsel_n = array_shift($result_n)) {
                $warehouse_id = $myrowsel_n["warehouse_id"];
            }

            db_b2b();
            $result_n = db_query("Select * from companyInfo where loopid = " . $warehouse_id);
            while ($myrowsel_n = array_shift($result_n)) {
                $warehouse_name = $myrowsel_n["company"];
                $warehouse_contact = $myrowsel_n["shipContact"];
                $warehouse_address = $myrowsel_n["shipAddress"] . " " . $myrowsel_n["shipAddress2"] . ", " . $myrowsel_n["shipCity"] . ", " . $myrowsel_n["shipState"] . " " . $myrowsel_n["shipZip"];
                $warehouse_phone = $myrowsel_n["shipPhone"];
                $warehouse_eml = $myrowsel_n["shipemail"];
            }
        }
    }

    $shipping_receiving_hours = "";
    /*******Pickup Address start**********/
    if ($sets_own_dock_appointment == "yes" && $warehouse_calendly_link != "") {
        if (!empty($loc_warehouse_id) && $loc_warehouse_id > 0) {

            db();
            $result_n = db_query("SELECT * FROM loop_warehouse WHERE id = " . $loc_warehouse_id);
            while ($myrowsel_n = array_shift($result_n)) {
                $sets_own_dock_appointment = $myrowsel_n["sets_own_dock_appointment"];
                $warehouse_contact = $myrowsel_n["warehouse_contact"];

                $warehouse_name = $myrowsel_n["warehouse_name"];
                $warehouse_address = $myrowsel_n["warehouse_address1"] . " " . $myrowsel_n["warehouse_address2"];
                $warehouse_city = $myrowsel_n["warehouse_city"];
                $warehouse_state = $myrowsel_n["warehouse_state"];
                $warehouse_zip = $myrowsel_n["warehouse_zip"];
                $warehouse_eml = $myrowsel_n["warehouse_contact_email"];
                $warehouse_phone = $myrowsel_n["warehouse_contact_phone"];
                $warehouse_calendly_link = $myrowsel_n["calendly_link"];
            }
        }
    } else {
        if ($virtual_inventory_trans_id > 0) {

            $warehouse_id = 0;
            db();
            $result_n = db_query("Select warehouse_id from loop_transaction where id = " . $virtual_inventory_trans_id);
            while ($myrowsel_n = array_shift($result_n)) {
                $warehouse_id = $myrowsel_n["warehouse_id"];
            }

            db_b2b();
            $result_n = db_query("Select * from companyInfo where loopid = " . $warehouse_id);
            while ($myrowsel_n = array_shift($result_n)) {
                $warehouse_name = $myrowsel_n["company"];
                $warehouse_contact = $myrowsel_n["shipContact"];
                $warehouse_address = $myrowsel_n["shipAddress"] . " " . $myrowsel_n["shipAddress2"];
                $warehouse_city = $myrowsel_n["shipCity"];
                $warehouse_state = $myrowsel_n["shipState"];
                $warehouse_zip = $myrowsel_n["shipZip"];
                $warehouse_phone = $myrowsel_n["shipPhone"];
                $warehouse_eml = $myrowsel_n["shipemail"];
                $shipping_receiving_hours = $myrowsel_n["shipping_receiving_hours"];
            }
        }
    }
    $pickupAddress = "";
    if (trim($warehouse_contact) == "") {
    } else {
        $pickupAddress = $warehouse_contact;
    }
    if (trim($warehouse_name) == "") {
    } else {
        $pickupAddress .= "<br>" . $warehouse_name;
    }
    if (trim($warehouse_address) == "") {
    } else {
        $pickupAddress .= "<br>" . $warehouse_address;
    }
    if (trim(isset($warehouse_city) . isset($warehouse_state) . isset($warehouse_zip)) == "") {
    } else {
        $pickupAddress .= "<br>" . isset($warehouse_city) . ", " . isset($warehouse_state) . " " . isset($warehouse_zip);
    }
    if (trim($warehouse_phone) == "") {
    } else {
        $pickupAddress .= "<br>" . $warehouse_phone;
    }
    $pickupAddress .= "<br><a href='mailto:" . $warehouse_eml . "'>" . $warehouse_eml . "</a>";
    if ($shipping_receiving_hours != "") {
        $pickupAddress .= "<br>Shipping/Receiving Hours: " . $shipping_receiving_hours;
    }
    /*******Pickup Address end**********/


    /****Order summary starts *****/
    $dt_view_qry = "SELECT * from loop_transaction_buyer WHERE id = '" .  $_REQUEST["rec_id"] . "' AND po_file != ''";
    db();
    $result = db_query($dt_view_qry);
    $dt_view_row = array_shift($result);
    $payment_method = $dt_view_row["po_payment_method"];
    $pono = $dt_view_row["po_ponumber"];
    $credit_term = $dt_view_row["po_poterm"];
    $quote_id = $dt_view_row["quote_number"];
    $online_order = $dt_view_row["online_order"];

    $order_summary = "";
    if ($quote_id > 0) {
        $query = "SELECT * FROM quote WHERE ID=" . $quote_id . " ORDER BY ID DESC";
        db_b2b();
        $dt_view_res3 = db_query($query);
        $objQStatus = array_shift($dt_view_res3);
        $quot_sele_str = " ";
        if ($objQStatus["quoteType"] == "Quote Select") {
            $quot_sele_str = " and manual_flg = 1 ";
        }

        $order_summary = "<table width='95%' class='ordertbl' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">";
        $order_summary .= "<tr><td colspan=2 align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Item</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Qty</td></tr>";

        $delivery_cost = 0;
        $subtotal = 0;
        if ($online_order <> "" && ($objQStatus["quoteType"] == "Quote Select")) {
            $fsql = "SELECT * FROM b2becommerce_order_item_details where order_item_id = " . $online_order . " ORDER BY product_name_id";
            db();
            $fresult = db_query($fsql);
            while ($b2b_online_order = array_shift($fresult)) {

                db();
                $lb_res = db_query("SELECT * from loop_boxes WHERE id = '" . $b2b_online_order["box_id"] . "'");
                while ($lbrow = array_shift($lb_res)) {
                    $bpic_1 = $lbrow['bpic_1'];
                    $bpic_2 = $lbrow['bpic_2'];
                    $bpic_3 = $lbrow['bpic_3'];
                    $bpic_4 = $lbrow['bpic_4'];

                    if (file_exists('boxpics_thumbnail/' . $bpic_1)) {
                        $fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics_thumbnail/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/>
							</div><br>";
                    } else {
                        if ($lbrow['bpic_1'] != '') {
                            $fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/></div><br>";
                        }
                    }
                }

                $subtotal = $subtotal + $b2b_online_order["product_total"];

                $order_summary .= "<tr><td width='7%' style='width:7%; white-space: nowrap;'>" . isset($fly_txt) . "</td><td align='left' width='500px' style='width:502px;'>" . $b2b_online_order["product_name"] . "</td>";
                $order_summary .= "<td align='right'  style='white-space: nowrap;'>" . $b2b_online_order["product_qty"] . "</td></tr>";
            }
        } else {

            $fsql = "SELECT item_id, quantity, item, description, quote_price, total FROM quote_to_item where quote_id = " . $quote_id . "  $quot_sele_str ORDER BY sort_order ASC";
            db_b2b();
            $fresult = db_query($fsql);
            while ($bx = array_shift($fresult)) {
                $fly_txt = "";
                $bsize = "";
                $quote_counts = isset($quote_counts) + 1;
                $boxsql = "select * from boxes where ID = '" . $bx["item_id"] . "'";
                db_b2b();
                $itemSql = db_query($boxsql);

                $item = array_shift($itemSql);
                $boxsql = "select * from boxes where ID = '" . $bx["item_id"] . "'";
                db_b2b();
                $itemSql = db_query($boxsql);
                $item = array_shift($itemSql);

                if ($item["inventoryID"] > -1) {
                    $lbq = "";
                    if ($item['inventoryID'] == 0) {
                        if ($item["box_id"] > 0) {
                            $lbq = "SELECT * from loop_boxes WHERE b2b_id = " . $item["box_id"];
                        }
                    } else {
                        $lbq = "SELECT * from loop_boxes WHERE b2b_id = " . $item["inventoryID"];
                    }
                    if ($lbq != "") {

                        db();
                        $lb_res = db_query($lbq);
                        while ($lbrow = array_shift($lb_res)) {
                            $bpic_1 = $lbrow['bpic_1'];
                            $bpic_2 = $lbrow['bpic_2'];
                            $bpic_3 = $lbrow['bpic_3'];
                            $bpic_4 = $lbrow['bpic_4'];

                            if (file_exists('boxpics_thumbnail/' . $bpic_1)) {
                                $fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics_thumbnail/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/>
									</div><br>";
                            } else {
                                if ($lbrow['bpic_1'] != '') {
                                    $fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/></div><br>";
                                }
                            }
                        }
                    }
                }
                if ($bx['quantity'] > 0) {
                    $qty             = $bx['quantity'];
                    $description     = $bx['description'];
                    $price             = number_format($bx['quote_price'], 2);
                    $prtotal         = number_format($bx['quote_price'] * $bx['quantity'], 2);
                    $subtotal         = $subtotal + $bx['quote_price'] * $bx['quantity'];
                } else {
                    $qty = $item['quantity'];
                    $description = $item['description'];
                    $price = number_format($item['salePrice'], 2);
                    $prtotal = number_format($item['salePrice'] * $item['quantity'], 2);
                    $subtotal = $subtotal + $item['salePrice'] * $item['quantity'];
                }

                if ($bx["item"] == "Delivery") {
                    $delivery_cost = $bx["total"];
                    if ($delivery_cost > 0) {
                        $description = $description . " (Shipping included)";
                    }
                }

                $order_summary .= "<tr><td width='7%' style='width:7%; white-space: nowrap;'>" . $fly_txt . "</td><td align='left' width='500px' style='width:502px;'>" . $description . "</td>";
                $order_summary .= "<td align='right'  style='white-space: nowrap;'>" . $qty . "</td></tr>";
            }
        }

        $order_summary .= "</table>";
    } else {
        $order_summary = "";
    }
    /****Order summary ends *****/

    $eml_confirmation = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
			<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'><style>
			@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');
			</style><style scoped>
			.tablestyle {
			   width:800px;
			}
			table.ordertbl tr td{
				padding:4px;
			}
			@media only screen and (max-width: 768px) {
				.tablestyle {
				   width:98%;
				}
			}
		</style></head><body style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";
    $eml_confirmation .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";

    $eml_confirmation .= "<tr><td><a href='https://www.usedcardboardboxes.com/'><img src='https://www.ucbzerowaste.com/images/logo2.png' alt='moving boxes'></a></td></tr>";
    $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER #" . $_REQUEST["rec_id"] . " (PO " . $pono . ") </span><br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Please arrange order pickup $pickup_date</div></td></tr>";

    if ($sets_own_dock_appointment == "yes" && $warehouse_calendly_link != "") {

        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>" . $fr_contact . " at " . $fr_name . ", meet " . $warehouse_contact . " at our " . $warehouse_name . " facility.</p></div></td></tr>";
        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>Please arrange a dock appointment (via the link) to pick up your order $pickup_date_2 and confirm the day/time with us here at UsedCardboardBoxes. If you have questions or concerns, please reply all to this email.</p></div></td></tr>";
        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p><a style=\"text-decoration: 'none';background-color: #EEEEEE;   color: #333333; padding: 2px 6px 2px 6px; border-top: 1px solid #CCCCCC; border-right: 1px solid #333333; border-bottom: 1px solid #333333; border-left: 1px solid #CCCCCC; \" href='" . $warehouse_calendly_link . "'>Set Dock Appointment</a></p></div></td></tr>";
    } else {

        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>" . $fr_contact . " at " . $fr_name . ",  meet " . $warehouse_contact . " at our " . $warehouse_name . " facility! </p></div></td></tr>";
        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>Please arrange a dock appointment (day + time) to pick up our order and confirm the day/time with us here at UsedCardboardBoxes. A reply all email works best to keep everyone informed.</p></div></td></tr>";
    }

    $eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Broker Information</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080;\">" . $brokerInfo . "</span>
			<br><br></td></tr>";
    $eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Pickup Address</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080;\">" . $pickupAddress . "</span>
			<br><br></td></tr>";

    if ($order_summary != "") {
        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Order Summary</div><br></td></tr>";

        $eml_confirmation .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">" . $order_summary . "</div></td></tr>";
    }


    $signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
    $signature .= "<td><p style='font-size:13pt;color:#538135'>";
    $signature .= "<u>National Operations Team</u></br>UsedCardboardBoxes (UCB)</p>";
    $signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
    $signature .= "323.724.2500 x709 <br><br>";
    $signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
    $signature .= "</td></tr></table>";
    $eml_confirmation .= "<br><br><tr><td>" . $signature . "</td></tr>";
    $eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";

?>

<form name="email_reminder_sch_p3" id="email_reminder_sch_p3" action="sendemail_broker_needs_pickup_save.php"
    method="post">
    <div align="right">
        <a href='javascript:void(0)' style='text-decoration:none;'
            onclick="document.getElementById('light_reminder').style.display='none';">
            <font color="black" size="2"><b>Close</b></font>
        </a>
    </div>

    <table>
        <tr>
            <td width="10%">To:</td>
            <?php if ($sets_own_dock_appointment == "yes" && $warehouse_calendly_link != "") { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $fr_eml; ?>;<?php echo $warehouse_eml; ?>"></td>
            <?php } else { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $fr_eml; ?>;<?php echo $warehouse_eml; ?>"></td>
            <?php } ?>
        </tr>

        <tr>
            <td width="10%">Cc:</td>
            <td width="90%"> <input size=100 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $acc_owner_eml; ?><?php echo $link_purchasing_acc_owner_eml; ?><?php echo $link_purchasing_acc_owner_ucbzw_eml; ?><?php if ($acc_owner_eml != $emp_email) {
                                                                                                                                                                                                                                        echo ";" . $emp_email;
                                                                                                                                                                                                                                    } ?>">
            </td>
        </tr>

        <tr>
            <td width="10%">Bcc:</td>
            <td width="90%"> <input size=100 type="text" id="txtemailbcc" name="txtemailbcc"
                    value="Freight@UsedCardboardBoxes.com"></td>
        </tr>

        <tr>
            <td width="10%">Subject:</td>
            <?php if ($dock_pickup_date != "") { ?>
            <td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject"
                    value="UsedCardboardBoxes Order #<?php echo $_REQUEST["rec_id"]; ?> Pick-up Dock Appointment Needed for <?php echo $dock_pickup_date; ?>">
            </td>
            <?php } else { ?>
            <td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject"
                    value="UsedCardboardBoxes Order #<?php echo $_REQUEST["rec_id"]; ?> Pick-up Dock Appointment Needed">
            </td>
            <?php } ?>

        </tr>

        <tr>
            <td valign="top" width="10%">Body:</td>
            <td width="1000px" id="bodytxt">
                <?php

                    require_once('fckeditor_new/fckeditor.php');
                    $FCKeditor = new FCKeditor('txtemailbody');
                    $FCKeditor->BasePath = 'fckeditor_new/';
                    $FCKeditor->Value = $eml_confirmation;
                    $FCKeditor->Height = 600;
                    $FCKeditor->Width = 1000;
                    $FCKeditor->Create();
                    ?>
                <div style="heighr:15px;">&nbsp;</div>
                <input type="button" name="send_quote_email" id="send_quote_email" value="Submit"
                    onclick="btnsendemlclick_eml_p3()">

                <input type="hidden" name="ID" id="ID" value="<?php echo $_REQUEST["compid"]; ?>" />
                <input type="hidden" name="warehouse_id" id="warehouse_id"
                    value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
                <input type="hidden" name="rec_type" id="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />

                <input type="hidden" name="rec_id" id="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
                <input type="hidden" name="hidden_reply_eml" id="hidden_reply_eml" value="" />
                <input type="hidden" name="hidden_sendemail" id="hidden_sendemail" value="inemailmode">

            </td>
        </tr>

    </table>
</form>

<?php

}

?>