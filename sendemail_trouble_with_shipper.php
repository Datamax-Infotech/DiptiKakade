<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


$rec_id = $_REQUEST["rec_id"];

db();
$po_employee = "";
$sql = "SELECT po_employee from loop_transaction_buyer where id = ?";
$result_comp = db_query($sql, array("i"), array($rec_id));
while ($row_comp = array_shift($result_comp)) {
    $po_employee = $row_comp["po_employee"];
}

db_b2b();
$sellto_eml = "";
$acc_owner = "";
$acc_owner_eml = "";
$selltoeml = "";
$shipemail = "";
$selltoname = "";
$shiptoname = "";
$result_crm = db_query("Select * from companyInfo Where ID = " . $_REQUEST["compid"]);
$company_name = "";
$to_eml_crm = "";
$sellto_name = "";
while ($myrowsel_main = array_shift($result_crm)) {
    $companyName = $myrowsel_main["company"];
    $sellto_name = $myrowsel_main["contact"];
    $sellto_eml = $myrowsel_main["email"];

    $selltoeml = $myrowsel_main["selltoeml"];
    $shipemail = $myrowsel_main["shipemail"];
    $selltoname = $myrowsel_main["contact"];
    $shiptoname = $myrowsel_main["shipContact"];

    if ($selltoeml != "") {
        $shiptosellto_flg = "sellto1";
        $shiptosellto_id = $_REQUEST['ID'];
    }
    if ($shipemail != "") {
        $shiptosellto_flg = "shipto";
        $shiptosellto_id = $_REQUEST['ID'];
    }

    $sql = "SELECT * FROM b2bsellto where companyid = " . $_REQUEST['compid'] . " order by selltoid";
    $result = db_query($sql);
    while ($myrowsel = array_shift($result)) {
        if ($myrowsel["email"] != "") {
            $eml_rec_found = "n";
            foreach ($eml_array as $eml_array_tmp) {
                if ($eml_array_tmp['eml'] == $myrowsel["email"]) {
                    $eml_rec_found = "y";
                    break;
                }
            }

            if ($eml_rec_found == "n") {
                $shiptosellto_flg = "sellto2";
                $shiptosellto_id = $myrowsel["selltoid"];
            }
        }
    }
    //
    //---Shipping info
    //
    $shippinginfo = "";
    if (trim($myrowsel_main["shipContact"]) == "") {
    } else {
        $shippinginfo = $myrowsel_main["shipContact"];
    }
    $shippinginfo .= "<br>" . $companyName;

    if (trim($myrowsel_main["shipAddress"]) == "") {
    } else {
        $shippinginfo .= "<br>" . $myrowsel_main["shipAddress"];
    }

    if ($myrowsel_main["shipAddress2"] != "") {
        $shippinginfo .= "<br>" . $myrowsel_main["shipAddress2"];
    }
    if (trim($myrowsel_main["shipCity"] . $myrowsel_main["shipState"] . $myrowsel_main["shipZip"]) == "") {
    } else {
        $shippinginfo .= "<br>" . $myrowsel_main["shipCity"] . ", " . $myrowsel_main["shipState"] . " " . $myrowsel_main["shipZip"];
    }
    if (trim($myrowsel_main["shipPhone"]) == "") {
    } else {
        $shippinginfo .= "<br>" . $myrowsel_main["shipPhone"];
    }

    $shippinginfo .= "<br><a href='mailto:" . $myrowsel_main["shipemail"] . "'>" . $myrowsel_main["shipemail"] . "</a>";

    if ($myrowsel_main["shipping_receiving_hours"] == "") {
        $shippinginfo .= "<br>Shipping/Receiving Hours: <font color=red>NEED TO CONFIRM</font>";
    } else {
        $shippinginfo .= "<br>Shipping/Receiving Hours: " . $myrowsel_main["shipping_receiving_hours"];
    }

    //Billing info---------------------------------------------------------
    $billinginfo = "";
    $billto_name = "";
    $billto_ph = "";
    $billto_eml = "";
    db_b2b();
    $result_n = db_query("Select * from b2bbillto Where companyid = " . $_REQUEST["compid"] . " order by billtoid limit 1");
    while ($myrowsel_n = array_shift($result_n)) {
        $billto_name = $myrowsel_n["name"];
        $billto_ph = $myrowsel_n["mainphone"];
        $billto_eml = $myrowsel_n["email"];

        if (trim($billto_name) == "") {
        } else {
            $billinginfo = $billto_name;
        }
        $billinginfo .= "<br>" . $companyName;

        if (trim($myrowsel_n["address"]) == "") {
        } else {
            $billinginfo .= "<br>" . $myrowsel_n["address"];
        }

        if ($myrowsel_n["address2"] != "") {
            $billinginfo .= "<br>" . $myrowsel_n["address2"];
        }
        if (trim($myrowsel_n["city"] . $myrowsel_n["state"] . $myrowsel_n["zipcode"]) == "") {
        } else {
            $billinginfo .= "<br>" . $myrowsel_n["city"] . ", " . $myrowsel_n["state"] . " " . $myrowsel_n["zipcode"];
        }
        if (trim($myrowsel_n["mainphone"]) == "") {
        } else {
            $billinginfo .= "<br>" . $myrowsel_n["mainphone"];
        }

        $billinginfo .= "<br><a href='mailto:" . $billto_eml . "'>" . $billto_eml . "</a>";
    }
    //

    //if ($myrowsel_main["selltoeml"] != ""){
    //	$sellto_eml .= "," . $myrowsel_main["selltoeml"];
    //}

    db_b2b();
    $result_n = db_query("Select name, email from employees Where employeeID = " . $myrowsel_main["assignedto"]);
    while ($myrowsel_n = array_shift($result_n)) {
        $acc_owner = $myrowsel_n["name"];
        $acc_owner_eml = $myrowsel_n["email"];
    }

    if ($po_employee != "") {
        db();
        $sql = "SELECT email FROM loop_employees WHERE status='Active' and initials = ?";
        $result_comp = db_query($sql, array("s"), array($po_employee));
        while ($row_comp = array_shift($result_comp)) {
            $acc_owner_eml = $row_comp["email"];
        }
    }

    //---------------------------------------------------------------------------------------------------------
    $dt_view_qry = "SELECT * from loop_transaction_buyer WHERE id = '" .  $rec_id . "' AND po_file != ''";
    //echo $dt_view_qry;
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
        //
        $query = "SELECT * FROM quote WHERE ID=" . $quote_id . " ORDER BY ID DESC";
        db_b2b();
        $dt_view_res3 = db_query($query);
        $objQStatus = array_shift($dt_view_res3);

        $quot_sele_str = " ";
        if ($objQStatus["quoteType"] == "Quote Select") {
            $quot_sele_str = " and manual_flg = 1 ";
        }
        //
        $order_summary = "<table width='95%' class='ordertbl' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">";
        $order_summary .= "<tr><td colspan=2 align='left' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Item</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Qty</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Price</td><td align='right' style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#3b3838;\">Total</td></tr>";

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
                $order_summary .= "<td align='right'  style='white-space: nowrap;'>" . $b2b_online_order["product_qty"] . "</td><td align='right' style='white-space: nowrap;'>$" . $b2b_online_order["product_unitprice"] . "</td><td  align='right'  style='white-space: nowrap;'>$" . number_format($b2b_online_order["product_total"], 2) . "</td></tr>";
            }
        } else {
            $fsql = "SELECT item_id, quantity, item, description, quote_price, total FROM quote_to_item where quote_id = " . $quote_id . " $quot_sele_str ORDER BY sort_order ASC";
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
                //echo $objQuote["inventoryID"]."<br>";
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


                            /*if ($lbrow['bpic_1'] != '' && $lbrow['bpic_2'] != ''){
									$fly_txt = "<br><div style='width:150px;height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_1' style='width:75px;height:60;pxobject-fit: none;' width='75' height='60'/><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_2' style='width:75px;height:60px;object-fit: none;' width='75' height='60'/></div><br>";
								}	

								if ($lbrow['bpic_3'] != '' && $lbrow['bpic_4'] != ''){
									$fly_txt .= "<div style='width:150px; height:60px; float:left; margin:1px;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_3' style='width:75px;height:60px;object-fit: none;' width='75' height='60'/><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_4' style='width:75px;height:60px;object-fit: none;' width='75' height='60'/></div><br>";
								}
								if ($lbrow['bpic_3'] != '' && $lbrow['bpic_4'] == ''){
									$fly_txt .= "<div style='width:75px;height:60px; float:left;'><img alt='' src='https://loops.usedcardboardboxes.com/boxpics/$bpic_3' style='width:75px;height:60px;pxobject-fit: none;' width='75' height='60'/></div><br>";
								}*/
                        }
                    }
                    //
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
                $order_summary .= "<td align='right'  style='white-space: nowrap;'>" . $qty . "</td><td align='right' style='white-space: nowrap;'>$" . $price . "</td><td  align='right'  style='white-space: nowrap;'>$" . $prtotal . "</td></tr>";
            }
        }


        //$order_summary.="<tr style='border-top:1px solid #a6a6a6;'><td style='border-top:1px solid #a6a6a6; padding:5px 0px; white-space: nowrap;' colspan=4 align='right'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:400;\">Subtotal</span></td> <td align='right' style='border-top:1px solid #a6a6a6; padding:5px 0px;'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:400;\">$".number_format($subtotal,2)."</span></td></tr>";

        if ($dt_view_row["customerpickup_ucbdelivering_flg"] == 2 && $delivery_cost == 0) {
            $order_summary .= "<tr><td colspan=4 align='right' style='border-bottom:1px solid #a6a6a6; padding:5px 0px;'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:400;\">Shipping</span></td><td align='right' style='border-bottom:1px solid #a6a6a6; padding:5px 0px;'><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:14pt;color:#3b3838; font-weight:400;\">Included</span></td></tr>";
        }

        $order_summary .= "<tr><td colspan=4 align='right'><span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\">Total</span></td>
				<td align='right'><span style=\"color:#3b3838; font-family:'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:16pt;font-weight:600;\">$" . number_format($subtotal, 2) . "</span></td></tr>";

        $cc_fees = 0;
        if ($payment_method == 'Credit Card') {
            if ($subtotal > 0) {
                $cc_fees = $subtotal * 0.03;
            }
            $finalTotal = $subtotal + $cc_fees;
            $order_summary .= "<tr><td width='20%' style='white-space: nowrap;'>&nbsp;</td><td align='left' width='500px' style='width:502px;'></td>";
            $order_summary .= "<td align='right'  style='white-space: nowrap;'>&nbsp;</td><td align='right' style='white-space: nowrap;'>Convenience Fee (3%)<br>Total if Paid by Credit Card</td><td  align='right'  style='white-space: nowrap;'>$" . number_format($cc_fees, 2) . "<br>$" . number_format($finalTotal, 2) . "</td></tr>";
        }
        $order_summary .= "</table>";
    } //End if quote ID
    else {
        $order_summary = "";
    }
    //echo $order_summary;

    if ($dt_view_row["customerpickup_ucbdelivering_flg"] == 1) {
        $shipping_method = "Customer Pick-Up";
    }
    if ($dt_view_row["customerpickup_ucbdelivering_flg"] == 2) {
        $shipping_method = "UCB will deliver, 3rd party";
    }
    //

    /*$sql1 = "SELECT * FROM b2bsellto where companyid = " . $_REQUEST['compid'] . " order by selltoid";
		$result_n = db_query($sql1, db_b2b() );
		while ($myrowsel_n = array_shift($result_n)){
			if ($myrowsel_n["email"] != "") {
				$sellto_eml .= "," . $myrowsel_n["email"];
			}
		}*/

    //new code----------------------------------------------------------------------------------------------------------------------
    //

    $eml_confirmation = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
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

    $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER #" . $_REQUEST["rec_id"] . " (PO " . $pono . ") </span><br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Possible fulfillment delay </div></td></tr>";

    $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\"><p>
		We anticipate a delay on your purchase and want to inform you of that immediately. Our team is going to investigate the delay and will be contacting you with further details on when to expect your delivery.
		</p><p>
		In the used box industry, these unanticipated delays can sometimes happen. Re-selling used boxes often requires our suppliers to forecast how many boxes they are going to unpack during a specific period of time, and sometimes those forecasts are impacted by unforeseen circumstances. An example of this would be a plant slowing down production due to machine maintenance. Regardless of those reasons, 
		our team is going to get to the bottom of it and provide you either a revised delivery date or we’ll work directly with your sales rep (" . $acc_owner . ") to offer another solution.
		</p></div></td></tr>";

    if ($order_summary != "") {
        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:17pt;color:#3b3838;\">Order Summary</div><br></td></tr>";

        $eml_confirmation .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">" . $order_summary . "</div></td></tr>";
    }

    $eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Address</span>
		<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $shippinginfo . "</span>
		<br><br></td></tr>";

    $eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Billing Information</span>
		<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $billinginfo . "</span>
		<br><br></td></tr>";

    if (isset($shipping_method) != "") {
        $eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Shipping Method</span>
			<br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">" . isset($shipping_method) . "</span>
			<br><br></td></tr>";
    }

    $eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Payment Method</span>
		<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Purchase Order (PO) #" . $pono . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080; margin-top:5px;\">Credit Terms: " . $credit_term . "</div>
		<br><br></td></tr>";

    $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:3px;\">*UCB will not be held liable for mis-shipments due to inaccurate information prior to order shipping.</div></td></tr>";

    $eml_confirmation .= "<tr><td><br><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#3b3838;\">Logistics Disclaimer</div></td></tr>";

    $eml_confirmation .= "<tr><td><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:5px;\"><p>IF YOU ARE RECEIVING A DELIVERY FROM UCB, you will need a loading dock and forklift to unload the trailer.</p>
		<p>IF YOU ARE PICKING UP FROM UCB, you will need a dock-height truck or trailer.</p>
		<p>If you do not have the items listed above, and you have not done so already, please advise right away so alternative arrangements can be made (additional fees may apply).</p>
		<p>In the meantime, and as always, please feel free to contact UCB's Operations Team, or your sales rep " . $acc_owner . " anytime, if you have any questions or concerns.</p>
		<p>Thank you again for Order #" . $rec_id . " and the opportunity to work with you!</p></div></td></tr>";

    $signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
    $signature .= "<td><p style='font-size:13pt;color:#538135'>";
    $signature .= "<u>National Operations Team</u></br>UsedCardboardBoxes (UCB)</p>";
    $signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
    $signature .= "323-724-2500 x709<br><br>";
    $signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
    $signature .= "</td></tr></table>";

    $eml_confirmation .= "<br><br><tr><td>" . $signature . "</td></tr>";
    $eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";




?>

<form name="email_reminder_sch_p4" id="email_reminder_sch_p4" action="sendemail_trouble_with_shipper_save.php"
    method="post">
    <div align="right">
        <a href='javascript:void(0)' style='text-decoration:none;'
            onclick="document.getElementById('light_reminder').style.display='none';">
            <font color="black" size="2"><b>Close</b></font>
        </a>
    </div>

    <table width="100%">
        <tr>
            <td width="10%">To:</td>
            <?php if (($sellto_eml == $myrowsel_main["shipemail"]) && ($sellto_eml != "" && $myrowsel_main["shipemail"] != "")) {
                    $sellto_eml = $sellto_eml;
                } else {
                    $sellto_eml = $sellto_eml . ";" . $myrowsel_main["shipemail"];
                }
                ?>
            <td width="90%"> <input size=60 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $sellto_eml; ?>">&nbsp;<font size=1>(Use ; to separate multiple email address)
                </font>
            </td>
        </tr>

        <tr>
            <td width="10%">Cc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $acc_owner_eml; ?><?php if ($acc_owner_eml != isset($emp_email)) {
                                                                                                                                        echo " ;" . isset($emp_email);
                                                                                                                                    } ?>">&nbsp;<font size=1>(Use ;
                    to separate multiple email address)</font>
            </td>
        </tr>

        <tr>
            <td width="10%">Bcc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailbcc" name="txtemailbcc"
                    value="Operations@UsedCardboardBoxes.com">&nbsp;<font size=1>(Use ; to separate multiple email
                    address)</font>
            </td>
        </tr>

        <tr>
            <td width="10%">Subject:</td>
            <td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject"
                    value="UsedCardboardBoxes Order #<?php echo $_REQUEST["rec_id"]; ?> Has Possible Delays"></td>
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
                <div style="height:15px;">&nbsp;</div>
                <input type="button" name="send_quote_email" id="send_quote_email" value="Submit"
                    onclick="btnsendemlclick_eml_p4()">

                <input type="hidden" name="delivery_date" id="delivery_date"
                    value="<?php echo $_REQUEST["delv_dt"]; ?>" />
                <input type="hidden" name="ID" id="ID" value="<?php echo $_REQUEST["compid"]; ?>" />
                <input type="hidden" name="warehouse_id" id="warehouse_id"
                    value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
                <input type="hidden" name="rec_type" id="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />

                <input type="hidden" name="rec_id" id="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
                <input type="hidden" name="hidden_reply_eml" id="hidden_reply_eml" value="" />
                <input type="hidden" name="hidden_sendemail" id="hidden_sendemail" value="inemailmode">
                <?php if (isset($_REQUEST['pickupDockAppointment']) && $_REQUEST['pickupDockAppointment'] == 'yes') {
                    ?>
                <input type="hidden" name="hdnPickupDockAppointment" id="hdnPickupDockAppointment"
                    value="<?php echo $_REQUEST['pickupDockAppointment']; ?>">
                <?php } ?>
            </td>
        </tr>

    </table>
</form>

<?php

}

?>