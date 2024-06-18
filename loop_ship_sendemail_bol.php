<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");



if ($_REQUEST["compid"] != "") {

    $sql3ud = "UPDATE loop_bol_files SET bol_received = '1', bol_received_employee = '" . $_COOKIE['userinitials'] . "',  bol_received_date = '" .  date("m/d/Y H:i:s") . "' WHERE trans_rec_id = " . $_REQUEST["rec_id"];
    db();
    $result3ud = db_query($sql3ud);

    $contact_nm = "";
    $contact_email = "";
    $po_ponumber = "";
    $link_purchasing_acc_owner_nm = "";
    $link_purchasing_acc_owner_eml = "";
    $link_purchasing_acc_owner_ucbzw_eml = "";
    $virtual_inventory_company_id = 0;
    $virtual_inventory_trans_id = 0;
    $so_view_qry = "SELECT * FROM loop_transaction_buyer WHERE id = '" .  $_REQUEST['rec_id'] . "'";
    db();
    $so_view_res = db_query($so_view_qry);
    while ($trans_buyer_row = array_shift($so_view_res)) {
        $virtual_inventory_company_id = $trans_buyer_row["virtual_inventory_company_id"];
        $virtual_inventory_trans_id = $trans_buyer_row["virtual_inventory_trans_id"];
        $po_ponumber = $trans_buyer_row["po_ponumber"];
    }

    if ($virtual_inventory_company_id > 0) {

        db_b2b();
        $result_crm = db_query("Select ucbzw_account_owner, assignedto, shipemail, shipContact from companyInfo Where loopid = " . $virtual_inventory_company_id);
        while ($myrowsel_main = array_shift($result_crm)) {
            $contact_nm = $myrowsel_main["shipContact"];
            $contact_email = $myrowsel_main["shipemail"];

            db_b2b();
            $result_n = db_query("Select name, email from employees Where employeeID = '" . $myrowsel_main["assignedto"] . "'");
            while ($myrowsel_n = array_shift($result_n)) {
                $link_purchasing_acc_owner_nm = $myrowsel_n["name"];
                $link_purchasing_acc_owner_eml = ";" . $myrowsel_n["email"];
            }

            if ($myrowsel_main["ucbzw_account_owner"] != "") {

                db_b2b();
                $result_n = db_query("Select name, email from employees Where employeeID = '" . $myrowsel_main["ucbzw_account_owner"] . "'");
                while ($myrowsel_n = array_shift($result_n)) {
                    $link_purchasing_acc_owner_ucbzw_nm = $myrowsel_n["name"];
                    $link_purchasing_acc_owner_ucbzw_eml = ";" . $myrowsel_n["email"];
                }
            }
        }
    }

    db();
    $po_employee = "";
    $sql = "SELECT po_employee from loop_transaction_buyer where id = ?";
    $result_comp = db_query($sql, array("i"), array($_REQUEST['rec_id']));
    while ($row_comp = array_shift($result_comp)) {
        $po_employee = $row_comp["po_employee"];
    }

    $sellto_eml = "";
    $acc_owner = "";
    $acc_owner_eml = "";
    $shipto_name = "";
    $shipto_email = "";
    $shipto_email_org = "";
    $shipto_phone = "";
    db_b2b();
    $result_crm = db_query("Select * from companyInfo Where ID = " . $_REQUEST["compid"]);
    $company_name = "";
    $to_eml_crm = "";
    $sellto_name = "";
    while ($myrowsel_main = array_shift($result_crm)) {
        $shipto_name = $myrowsel_main["shipContact"];
        $shipto_email = $myrowsel_main["shipemail"];
        $shipto_email_org = $myrowsel_main["shipemail"];
        $shipto_phone = $myrowsel_main["shipPhone"];

        $sellto_name = $myrowsel_main["contact"];
        $sellto_eml = $myrowsel_main["email"];
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

        $billto_name = "";
        $billto_ph = "";
        $billto_eml = "";

        db_b2b();
        $result_n = db_query("Select * from b2bbillto Where companyid = " . $_REQUEST["compid"] . " order by billtoid limit 1");
        while ($myrowsel_n = array_shift($result_n)) {
            $billto_name = $myrowsel_n["name"];
            $billto_ph = $myrowsel_n["mainphone"];
            $billto_eml = $myrowsel_n["email"];
        }

        db();
        $result_n = db_query("SELECT * FROM loop_salesorders WHERE trans_rec_id = " . $_REQUEST["rec_id"]);
        while ($myrowsel_n = array_shift($result_n)) {
            $loc_warehouse_id = $myrowsel_n["location_warehouse_id"];
        }

        $warehouse_name = "";
        $warehouse_address = "";
        $warehouse_eml = "";
        $warehouse_phone = "";
        $warehouse_calendly_link = "";
        $warehouse_contact = "";
        $sets_own_dock_appointment = "";

        db();
        $result_n = db_query("SELECT * FROM loop_warehouse WHERE id = '" . isset($loc_warehouse_id) . "'");

        while ($myrowsel_n = array_shift($result_n)) {
            $sets_own_dock_appointment = $myrowsel_n["sets_own_dock_appointment"];
            $warehouse_contact = $myrowsel_n["company_contact"];

            $warehouse_name = $myrowsel_n["warehouse_name"];
            $warehouse_address = $myrowsel_n["warehouse_address1"] . " " . $myrowsel_n["warehouse_address2"] . ", " . $myrowsel_n["warehouse_city"] . ", " . $myrowsel_n["warehouse_state"] . " " . $myrowsel_n["warehouse_zip"];
            $warehouse_calendly_link = $myrowsel_n["calendly_link"];
            if ($_REQUEST["pickup_or_ucb_delivering"] == 1) {
                if ($sets_own_dock_appointment == "yes" && $warehouse_calendly_link != "") {
                } else {
                    //$shipto_email = $myrowsel_n["warehouse_contact_email"]; 
                    //$shipto_phone = $myrowsel_n["warehouse_contact_phone"]; 
                    //$shipto_name = $myrowsel_n["warehouse_contact"]; 
                }
            }
            if ($_REQUEST["pickup_or_ucb_delivering"] == 2) {
                $shipto_name = $myrowsel_n["warehouse_contact"];
                $shipto_email = $myrowsel_n["warehouse_contact_email"];
                $shipto_phone = $myrowsel_n["warehouse_contact_phone"];
            }
        }

        $bol_freight_biller = 0;

        db();
        $result_n = db_query("Select bol_freight_biller from loop_bol_tracking Where trans_rec_id = " . $_REQUEST["rec_id"]);
        while ($myrowsel_n = array_shift($result_n)) {
            $bol_freight_biller = $myrowsel_n["bol_freight_biller"];
        }

        $fr_name = "";
        $fr_contact = "";
        $fr_ph = "";
        $fr_eml = "";

        db();
        $result_n = db_query("Select loop_freightvendor.* from loop_freightvendor where id = " . $bol_freight_biller);
        while ($myrowsel_n = array_shift($result_n)) {
            $fr_name = $myrowsel_n["company_name"];
            $fr_contact = $myrowsel_n["company_contact"];
            $fr_ph = $myrowsel_n["company_phone"];
            $fr_eml = $myrowsel_n["company_email"];
        }
    }

    if ($_REQUEST["pickup_or_ucb_delivering"] == 1) {
        /*if ($sets_own_dock_appointment == "yes" && $warehouse_calendly_link != ""){
				$eml_confirmation = "<html><head></head><body bgcolor='#E7F5C2'><table align='center' cellpadding='0' bgcolor='#E7F5C2'><tr><td colspan='2'><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td width='23' valign='top'><p> </p></td><td width='650'><br>";
				$eml_confirmation .= "<p align='center'><img src='https://loops.usedcardboardboxes.com/images/email-top-part3.jpg'></p>";

				$eml_confirmation .= "<p style='font-family: Calibri;'>Dear " . $shipto_name;
				if ($sellto_name != "" && ($sellto_name != $shipto_name)){
					$eml_confirmation .= " (copy to " . $sellto_name . "),";
				}else{ $eml_confirmation .= ","; }
				
				$eml_confirmation .= "<p style='font-family: Calibri;'>For <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b>, the Bill of Lading (BOL) PDF file is attached for your convenience as it was just created at our facility. No action is required, this message is FYI only, we'll confirm when the trailer has left our facility once it has.</p>";

				$eml_confirmation .= "<p style='font-family: Calibri;'>Thank you again for your support in fulfilling <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b> and the opportunity to work with you!<br></p>";
				
				$eml_confirmation .= "<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C;'><img src='https://www.ucbzerowaste.com/images/logo2.png'></td>";
				$eml_confirmation .= "<td><p style='font-family: Calibri; font-size:12;color:#538135'><u>National Freight Team</u><br>";
				$eml_confirmation .= "Used Cardboard Boxes, Inc. (UCB)</p>";
				$eml_confirmation .= "<span style='font-family: Calibri; font-size:12; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
				$eml_confirmation .= "323.724.2500 x5<br><br>";
				$eml_confirmation .= "How can we improve?  Please tell our CEO@UsedCardboardBoxes.com</span>";
				$eml_confirmation .= "</td></tr></table>";

				$eml_confirmation .= "</td></tr><tr><td colspan='2'><p align='center'><img width='650' height='87' src='https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";
			}else{
				$eml_confirmation = "<html><head></head><body bgcolor='#E7F5C2'><table align='center' cellpadding='0' bgcolor='#E7F5C2'><tr><td colspan='2'><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td width='23' valign='top'><p> </p></td><td width='650'><br>";
				$eml_confirmation .= "<p align='center'><img src='https://loops.usedcardboardboxes.com/images/email-top-part3.jpg'></p>";
				if ($virtual_inventory_company_id > 0){ 
					$eml_confirmation .= "<p style='font-family: Calibri;'>Dear " . $contact_nm	;
				}else{
					$eml_confirmation .= "<p style='font-family: Calibri;'>Dear " . $shipto_name ;
				}				
				$eml_confirmation .= ",";

				$eml_confirmation .= "<p style='font-family: Calibri;'>For <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b>, the Bill of Lading (BOL) PDF file is attached. Please print three (3) copies for this order. All 3 copies will need to be signed by the Shipper (you) and the Carrier (driver) in the bottom left and right corners, respectively. You will keep one (1) signed copy, the driver will get two (2) signed copies. Upon the driver leaving, UsedCardboardBoxes.com (UCB) will require the signed BOL to be scanned and e-mailed to us for our records.</p>";

				$eml_confirmation .= "<p style='font-family: Calibri;'>As previously mentioned, $shipto_name will be arranging pick up. They are copied on this e-mail and you should have been contacted by them already regarding getting pick-up arrangements made. If you have any questions or haven't heard from $shipto_name, please reply all to this email and allow us to assist you.</p>";

				$eml_confirmation .= "<p style='font-family: Calibri;'>Contact Name: " . $shipto_name . "</p>";
				$eml_confirmation .= "<p style='font-family: Calibri;'>Phone: " . $shipto_phone . "</p>";
				$eml_confirmation .= "<p style='font-family: Calibri;'>Email: " . $shipto_email . "</p>";
				
				$eml_confirmation .= "<p style='font-family: Calibri;'><b>Reminder: You are responsible for having a dock and forklift to load this order.</b></p>";

				$eml_confirmation .= "<p style='font-family: Calibri;'>If you do not have a dock and forklift to load your order, <b>please let us know immediately</b> and we will make other arrangements for this pickup.</p>";
					
				$eml_confirmation .= "<p style='font-family: Calibri;'>Thank you again for your support in fulfilling <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b> and the opportunity to work with you!<br></p>";
				
				$eml_confirmation .= "<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C;'><img src='https://www.ucbzerowaste.com/images/logo2.png'></td>";
				$eml_confirmation .= "<td><p style='font-family: Calibri; font-size:12;color:#538135'><u>National Freight Team</u><br>";
				$eml_confirmation .= "Used Cardboard Boxes, Inc. (UCB)</p>";
				$eml_confirmation .= "<span style='font-family: Calibri; font-size:12; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
				$eml_confirmation .= "323.724.2500 x5<br><br>";
				$eml_confirmation .= "How can we improve?  Please tell our CEO@UsedCardboardBoxes.com</span>";
				$eml_confirmation .= "</td></tr></table>";

				$eml_confirmation .= "</td></tr><tr><td colspan='2'><p align='center'><img width='650' height='87' src='https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";
			}*/
        if (isset($sets_own_dock_appointment) == "yes" && isset($warehouse_calendly_link) != "") {
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

            $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER #" . $_REQUEST["rec_id"] . " (PO " . $po_ponumber . ") </span>
				<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Bill of Lading (BOL) for our pickup</div></td></tr>";

            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>The attached BOL needs to be used for our upcoming pickup. Print 3 copies: 1 for the delivery customer, a 2nd for the driver’s records, and a 3rd for your records (and to email back to UCB).</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>
				It is imperative that you load <b>exactly</b> what the BOL states to load. If there is any discrepancy of what is being loaded and what is printed on the BOL, that must be escalated to our team <b>PRIOR</b> to the trailer leaving. We will need to adjust the BOL and send you a revised copy <b>PRIOR</b> to the trailer leaving. Changes made with a pencil/pen will not be acceptable.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>The bottom section will need filled out appropriately as well after loading with signatures (shipper/carrier). We will need a copy of the signed BOL emailed to us after loading.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">If you have any questions, please let us know prior to pickup.</div></td></tr>";

            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">Broker Information</div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">
				" . isset($fr_contact) . "</br>" . isset($fr_name) . "</br>" . isset($fr_ph) . "</br>" . isset($fr_eml) . "</div></td></tr>";

            $signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
            $signature .= "<td><p style='font-size:13pt;color:#538135'>";
            $signature .= "<u>National Operations Team</u></br>UsedCardboardBoxes (UCB)</p>";
            $signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
            $signature .= "323.724.2500 x709 <br><br>";
            $signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
            $signature .= "</td></tr></table>";
            $eml_confirmation .= "<br><br><tr><td>" . $signature . "</td></tr>";
            $eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";
        } else {
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

            $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER #" . $_REQUEST["rec_id"] . " (PO " . $po_ponumber . ") </span>
				<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Bill of Lading (BOL) for our pickup</div></td></tr>";

            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>The attached BOL needs to be used for our upcoming pickup. Print 3 copies: 1 for the delivery customer, a 2nd for the driver’s records, and a 3rd for your records (and to email back to UCB).</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>It is imperative that you load <b>exactly</b> what the BOL states to load. If there is any discrepancy of what is being loaded and what is printed on the BOL, that must be escalated to our team <b>PRIOR</b> to the trailer leaving. We will need to adjust the BOL and send you a revised copy <b>PRIOR</b> to the trailer leaving. Changes made with a pencil/pen will not be acceptable.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>The bottom section will need filled out appropriately as well after loading with signatures (shipper/carrier). We will need a copy of the signed BOL emailed to us after loading.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>If you have any questions, please let us know prior to pickup.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>Broker Information</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">
				<p>" . isset($fr_contact) . "</br>" . isset($fr_name) . "</br>" . isset($fr_ph) . "</br>" . isset($fr_eml) . "</p></div></td></tr>";

            $signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
            $signature .= "<td><p style='font-size:13pt;color:#538135'>";
            $signature .= "<u>National Operations Team</u></br>UsedCardboardBoxes (UCB)</p>";
            $signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
            $signature .= "323.724.2500 x709 <br><br>";
            $signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
            $signature .= "</td></tr></table>";
            $eml_confirmation .= "<br><br><tr><td>" . $signature . "</td></tr>";
            $eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";
        }
    }

    if ($_REQUEST["pickup_or_ucb_delivering"] == 2) {

        /*$uber_load_uuid = ""; $uber_str = "";
			//check for Uber Freight
			$result_n = db_query("Select uber_load_uuid from quoting_uber_freight_data where uber_load_uuid <> '' and trans_rec_id = " . $_REQUEST["rec_id"], db_b2b() );
			while ($myrowsel_n = array_shift($result_n)) {
				$uber_load_uuid = $myrowsel_n["uber_load_uuid"];
				$uber_str = "<p style='font-family: Calibri;'><a href='https://www.uber.com/freight/platform/share/" . $uber_load_uuid . "'>Track Your Shipment</a> with UberFreight</p>";
			}*/

        if (isset($sets_own_dock_appointment) == "yes" && isset($warehouse_calendly_link) != "") {
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

            $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER #" . $_REQUEST["rec_id"] . " (PO " . $po_ponumber . ") </span>
				<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Bill of Lading (BOL) for our pickup</div></td></tr>";

            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>The attached BOL needs to be used for our upcoming pickup. Print 3 copies: 1 for the delivery customer, a 2nd for the driver’s records, and a 3rd for your records (and to email back to UCB).</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>
				It is imperative that you load <b>exactly</b> what the BOL states to load. If there is any discrepancy of what is being loaded and what is printed on the BOL, that must be escalated to our team <b>PRIOR</b> to the trailer leaving. We will need to adjust the BOL and send you a revised copy <b>PRIOR</b> to the trailer leaving. Changes made with a pencil/pen will not be acceptable.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>The bottom section will need filled out appropriately as well after loading with signatures (shipper/carrier). We will need a copy of the signed BOL emailed to us after loading.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">If you have any questions, please let us know prior to pickup.</div></td></tr>";

            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">Broker Information</div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">
				" . isset($fr_contact) . "</br>" . isset($fr_name) . "</br>" . isset($fr_ph) . "</br>" . isset($fr_eml) . "</div></td></tr>";

            $signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
            $signature .= "<td><p style='font-size:13pt;color:#538135'>";
            $signature .= "<u>National Operations Team</u></br>UsedCardboardBoxes (UCB)</p>";
            $signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
            $signature .= "323.724.2500 x709 <br><br>";
            $signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
            $signature .= "</td></tr></table>";
            $eml_confirmation .= "<br><br><tr><td>" . $signature . "</td></tr>";
            $eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";
        } else {
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

            $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER #" . $_REQUEST["rec_id"] . " (PO " . $po_ponumber . ") </span>
				<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Bill of Lading (BOL) for our pickup</div></td></tr>";

            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>The attached BOL needs to be used for our upcoming pickup. Print 3 copies: 1 for the delivery customer, a 2nd for the driver’s records, and a 3rd for your records (and to email back to UCB).</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>It is imperative that you load <b>exactly</b> what the BOL states to load. If there is any discrepancy of what is being loaded and what is printed on the BOL, that must be escalated to our team <b>PRIOR</b> to the trailer leaving. We will need to adjust the BOL and send you a revised copy <b>PRIOR</b> to the trailer leaving. Changes made with a pencil/pen will not be acceptable.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>The bottom section will need filled out appropriately as well after loading with signatures (shipper/carrier). We will need a copy of the signed BOL emailed to us after loading.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>If you have any questions, please let us know prior to pickup.</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>Broker Information</p></div></td></tr>";
            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">
				<p>" . isset($fr_contact) . "</br>" . isset($fr_name) . "</br>" . isset($fr_ph) . "</br>" . isset($fr_eml) . "</p></div></td></tr>";

            $signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
            $signature .= "<td><p style='font-size:13pt;color:#538135'>";
            $signature .= "<u>National Operations Team</u></br>UsedCardboardBoxes (UCB)</p>";
            $signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
            $signature .= "323.724.2500 x709 <br><br>";
            $signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
            $signature .= "</td></tr></table>";
            $eml_confirmation .= "<br><br><tr><td>" . $signature . "</td></tr>";
            $eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";
        }
    }

?>

<form name="email_reminder_sch_p2" id="email_reminder_sch_p2" action="loop_ship_sendemail_bol_save.php" method="post">
    <div align="right">
        <a href='javascript:void(0)' style='text-decoration:none;'
            onclick="document.getElementById('light_reminder').style.display='none';">
            <font color="black" size="2"><b>Close</b></font>
        </a>
    </div>

    <table>
        <tr>
            <td width="10%">To:</td>
            <?php if ($_REQUEST["pickup_or_ucb_delivering"] == 1) {
                    if (isset($sets_own_dock_appointment) == "yes" && isset($warehouse_calendly_link) != "") { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $shipto_email; ?>"></td>
            <?php     } else {
                    ?>
            <?php if ($virtual_inventory_company_id > 0) { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $contact_email; ?>"></td>
            <?php } else { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $shipto_email; ?>"></td>
            <?php } ?>
            <?php }
                } ?>

            <?php if ($_REQUEST["pickup_or_ucb_delivering"] == 2) { ?>
            <?php if (isset($sets_own_dock_appointment) == "yes" && isset($warehouse_calendly_link) != "") { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo isset($fr_eml); ?>"></td>
            <?php } else {
                        if ($virtual_inventory_company_id > 0) {    ?>
            <td width="90%"> <input size=100 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $contact_email; ?>"></td>
            <?php } else { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $shipto_email; ?>"></td>
            <?php } ?>
            <?php     }
                } ?>
        </tr>

        <tr>
            <td width="10%">Cc:</td>
            <?php if ($_REQUEST["pickup_or_ucb_delivering"] == 1) { ?>
            <?php if (isset($sets_own_dock_appointment) == "" && isset($warehouse_calendly_link) == "") { ?>
            <?php if ($virtual_inventory_company_id > 0) {    ?>
            <td width="90%"> <input size=100 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $shipto_email_org; ?>;<?php echo $acc_owner_eml; ?>"></td>
            <?php } else { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $shipto_email; ?>;<?php echo $acc_owner_eml; ?>"></td>
            <?php } ?>
            <?php } else { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $acc_owner_eml; ?>"></td>
            <?php }  ?>
            <?php }  ?>

            <?php if ($_REQUEST["pickup_or_ucb_delivering"] == 2) {
                    if (isset($fr_eml) != "") {
                        $fr_eml .= isset($fr_eml) . ";";
                    }
                ?>
            <?php if (isset($sets_own_dock_appointment) == "yes" && isset($warehouse_calendly_link) != "") { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $acc_owner_eml; ?> <?php echo $link_purchasing_acc_owner_eml; ?><?php echo $link_purchasing_acc_owner_ucbzw_eml; ?>">
            </td>
            <?php } else { ?>
            <td width="90%"> <input size=100 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echoemfr_eml; ?><?php echo $acc_owner_eml; ?><?php echo $link_purchasing_acc_owner_eml; ?><?php echo $link_purchasing_acc_owner_ucbzw_eml; ?>">
            </td>
            <?php }  ?>
            <?php }  ?>
        </tr>

        <tr>
            <td width="10%">Bcc:</td>
            <td width="90%"> <input size=100 type="text" id="txtemailbcc" name="txtemailbcc"
                    value="freight@UsedCardboardBoxes.com"></td>
        </tr>

        <tr>
            <td width="10%">Subject:</td>
            <td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject"
                    value="UsedCardboardBoxes Order #<?php echo $_REQUEST["rec_id"]; ?> Bill of Lading (BOL) Attached">
            </td>
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
                    onclick="btnsendemlclick_eml_p2()">

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