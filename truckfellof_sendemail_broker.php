<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

if ($_REQUEST["compid"] != "") {
?>

<?php


    $loc_warehouse_id = 0;
    $sales_order_box_id = 0;
    $box_weight_sales_o = 0;
    db();
    $result_n = db_query("SELECT box_id, location_warehouse_id FROM loop_salesorders WHERE trans_rec_id = " . $_REQUEST["rec_id"]);
    while ($myrowsel_n = array_shift($result_n)) {
        $sales_order_box_id = $myrowsel_n["box_id"];
        $loc_warehouse_id = $myrowsel_n["location_warehouse_id"];
    }

    $get_wh = "SELECT vendor_b2b_rescue FROM loop_boxes where id = " . $sales_order_box_id;
    db();
    $get_wh_res = db_query($get_wh);
    while ($the_wh = array_shift($get_wh_res)) {
        $vendor_b2b_rescue = $the_wh["vendor_b2b_rescue"];
    }

    $po_employee = "";
    $sql = "SELECT po_employee from loop_transaction_buyer where id = ?";
    $result_comp = db_query($sql, array("i"), array($_REQUEST["rec_id"]));
    while ($row_comp = array_shift($result_comp)) {
        $po_employee = $row_comp["po_employee"];
    }

    $all_freight_brk_emls = "";
    $get_wh = "SELECT company_email FROM loop_freightvendor where company_email <> '' group by company_email";

    db();
    $get_wh_res = db_query($get_wh);
    while ($the_wh = array_shift($get_wh_res)) {
        $all_freight_brk_emls .= $the_wh["company_email"] . ",";
    }

    $sellto_eml = "";
    $acc_owner = "";
    $acc_owner_eml = "";
    $shipto_name = "";
    $shipto_email = "";
    $shipCity = "";
    $shipState = "";
    $shipZip = "";
    $company_name = "";
    $to_eml_crm = "";
    $sellto_name = "";

    db_b2b();
    $result_crm = db_query("Select * from companyInfo Where ID = " . $_REQUEST["compid"]);
    while ($myrowsel_main = array_shift($result_crm)) {
        $shipto_name = $myrowsel_main["shipContact"];
        $shipto_email = $myrowsel_main["shipemail"];

        $shipCity = $myrowsel_main["shipCity"];
        $shipState = $myrowsel_main["shipState"];
        $shipZip = $myrowsel_main["shipZip"];

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

        $book_pickupdate = "";
        db();
        $result_n = db_query("Select date from loop_transaction_freight where trans_rec_id = " . $_REQUEST["rec_id"]);

        while ($myrowsel_n = array_shift($result_n)) {
            $book_pickupdate = $myrowsel_n["date"];
        }

        $booked_delivery_cost = "";

        db();
        $result_n = db_query("Select booked_delivery_cost from loop_transaction_buyer_freightview where trans_rec_id = " . $_REQUEST["rec_id"]);
        while ($myrowsel_n = array_shift($result_n)) {
            $booked_delivery_cost = $myrowsel_n["booked_delivery_cost"];
        }

        $loc_warehouse_id_tmp = $loc_warehouse_id;
        if ($loc_warehouse_id == 238) {
            $loc_warehouse_id_tmp = isset($vendor_b2b_rescue);
        }

        if ($loc_warehouse_id > 0 && $loc_warehouse_id != 238) {
            $loc_warehouse_id_tmp = $loc_warehouse_id;
        }

        $pickup_city = "";
        $pickup_state = "";
        $pickup_zip = "";
        db_b2b();
        $dt_view_res_data = db_query("Select loopid, company, ID, shipAddress, shipAddress2, shipCity, shipState,shipZip from companyInfo where loopid = " . $loc_warehouse_id_tmp);
        while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
            $vendor_b2b_rescue_b2bid = $myrowsel_b2b["loopid"];
            $pickup_nm = strval(get_nickname_val($myrowsel_b2b["company"], $myrowsel_b2b["ID"]));
            $pickup_add1 = strval($myrowsel_b2b["shipAddress"]);
            $pickup_add2 = strval($myrowsel_b2b["shipAddress2"]);
            $pickup_city = strval($myrowsel_b2b["shipCity"]);
            $pickup_state = strval($myrowsel_b2b["shipState"]);
            $pickup_zip = substr(strval($myrowsel_b2b["shipZip"]), 0, 5);
        }

        if (isset($rec_found_sorting_w) == "no") {

            db();
            $dt_view_res_data = db_query("Select * from loop_warehouse where rec_type = 'Sorting' and id = " . $loc_warehouse_id_tmp);
            while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
                $rec_found_sorting_w = "yes";
                $vendor_b2b_rescue_b2bid = $myrowsel_b2b["id"];
                $pickup_nm = $myrowsel_b2b["company_name"];
                $pickup_add1 = strval($myrowsel_b2b["warehouse_address1"]);
                $pickup_add2 = strval($myrowsel_b2b["warehouse_address2"]);
                $pickup_city = strval($myrowsel_b2b["warehouse_city"]);
                $pickup_state = strval($myrowsel_b2b["warehouse_state"]);
                $pickup_zip = substr(strval($myrowsel_b2b["warehouse_zip"]), 0, 5);
            }
        }

        db();
        $dt_view_qry = db_query("SELECT po_ponumber from loop_transaction_buyer WHERE id = '" . $_REQUEST["rec_id"] . "' AND po_file != ''");

        $dt_view_row = array_shift($dt_view_qry);
        $pono = $dt_view_row["po_ponumber"];

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

        $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER #" . $_REQUEST["rec_id"] . " (PO " . $pono . ") </span>
		<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >UCB Needs a Lane Recovery!</div></td></tr>";

        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\"><p>UsedCardboardBoxes.com (UCB) has a lane scheduled going 
		from $pickup_city, $pickup_state $pickup_zip to $shipCity, $shipState $shipZip on '" . date("m/d/Y", strtotime($book_pickupdate)) . "', but the 
		<font color=red>TRUCK HAS FALLEN OFF!</font></p></div></td></tr>";

        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">UCB needs to recover this lane as quickly as possible. 
		If you have a carrier <b>IN HAND</b> available for <b>$" . number_format($booked_delivery_cost) . " or less</b> that would be willing to take this load 
		<b>IMMEDIATELY</b>, please reply to this e-mail or call 323.724.2500 x5.</div></td></tr>";

        $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px;\">Thank you and we appreciate your work on keeping UCB Customers Happy!</div></td></tr>";

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

<form name="email_reminder_sch_p2" id="email_reminder_sch_p2" action="truckfellof_sendemail_save.php" method="post">
    <div align="right">
        <a href='javascript:void(0)' style='text-decoration:none;'
            onclick="document.getElementById('light_reminder').style.display='none';">
            <font color="black" size="2"><b>Close</b></font>
        </a>
    </div>

    <table>
        <tr>
            <td width="10%">To:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailto" name="txtemailto"
                    value="freight@usedcardboardboxes.com"></td>
        </tr>

        <tr>
            <td width="10%">Cc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailcc" name="txtemailcc" value=""></td>
        </tr>

        <tr>
            <td width="10%">Bcc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailbcc" name="txtemailbcc"
                    value="<?php echo $all_freight_brk_emls; ?>"></td>
        </tr>

        <tr>
            <td width="10%">Subject:</td>
            <td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject"
                    value="UsedCardboardBoxes Order #<?php echo $_REQUEST["rec_id"]; ?>, Need Lane Recovery!"></td>
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
                    onclick="btnsendeml_truck_felloff()">

                <input type="hidden" name="ID" id="ID" value="<?php echo $_REQUEST["compid"]; ?>" />
                <input type="hidden" name="warehouse_id" id="warehouse_id"
                    value="<?php echo $_REQUEST["warehouse_id"]; ?>" />
                <input type="hidden" name="rec_type" id="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>" />

                <input type="hidden" name="customer_flg" id="customer_flg" value="2" />

                <input type="hidden" name="rec_id" id="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>" />
                <input type="hidden" name="hidden_reply_eml" id="hidden_reply_eml" value="" />
                <input type="hidden" name="hidden_sendemail" id="hidden_sendemail" value="inemailmode">

            </td>
        </tr>

    </table>
</form>

<?php

    }
}

?>