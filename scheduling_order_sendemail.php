<?php



require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


if ($_REQUEST["compid"] != "") {

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
    db_b2b();
    $result_crm = db_query("Select * from companyInfo Where ID = " . $_REQUEST["compid"]);
    $company_name = "";
    $to_eml_crm = "";
    $sellto_name = "";
    while ($myrowsel_main = array_shift($result_crm)) {
        $shipto_name = $myrowsel_main["shipContact"];
        $shipto_email = $myrowsel_main["shipemail"];

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
        $sets_own_dock_appointment = "";
        $warehouse_contact = "";

        db();
        $result_n = db_query("SELECT * FROM loop_warehouse WHERE id = '" . isset($loc_warehouse_id) . "'");
        while ($myrowsel_n = array_shift($result_n)) {
            $sets_own_dock_appointment = $myrowsel_n["sets_own_dock_appointment"];
            $warehouse_contact = $myrowsel_n["company_contact"];
            $warehouse_name = $myrowsel_n["warehouse_name"];
            $warehouse_address = $myrowsel_n["warehouse_address1"] . " " . $myrowsel_n["warehouse_address2"] . ", " . $myrowsel_n["warehouse_city"] . ", " . $myrowsel_n["warehouse_state"] . " " . $myrowsel_n["warehouse_zip"];
            $warehouse_eml = $myrowsel_n["warehouse_contact_email"];
            $warehouse_phone = $myrowsel_n["warehouse_contact_phone"];
            $warehouse_calendly_link = $myrowsel_n["calendly_link"];
        }

        $virtual_inventory_trans_id = 0;
        $po_ponumber = "";

        db();
        $result_n = db_query("Select virtual_inventory_trans_id, po_ponumber from loop_transaction_buyer where id = " . $_REQUEST["rec_id"]);
        while ($myrowsel_n = array_shift($result_n)) {
            $virtual_inventory_trans_id = $myrowsel_n["virtual_inventory_trans_id"];
            $po_ponumber = $myrowsel_n["po_ponumber"];
        }
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

        //if ($sets_own_dock_appointment == "yes" && $warehouse_calendly_link != ""){
        $eml_confirmation = "<html><head></head><body bgcolor='#E7F5C2'><table align='center' cellpadding='0' bgcolor='#E7F5C2'><tr><td colspan='2'><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td width='23' valign='top'><p> </p></td><td width='650'><br>";
        $eml_confirmation .= "<p align='center'><img src='https://loops.usedcardboardboxes.com/images/email-top-part2.jpg'></p>";
        $eml_confirmation .= "<p style='font-family: Calibri;'>Dear " . $shipto_name;
        if ($sellto_name != "" && ($sellto_name != $shipto_name)) {
            $eml_confirmation .= " (copy to " . $sellto_name . "),";
        } else {
            $eml_confirmation .= ",";
        }

        $eml_confirmation .= "<br><br>For <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b>, the inventory has been allocated and is ready to ship. This transaction has been handed over to the National Freight Department to get your pickup and delivery booked.<br>";
        $eml_confirmation .= "<br>As stated on the signed quote/purchase Order, UsedCardboardBoxes.com (UCB) will be providing delivery on this order. As soon as a trucking company has been selected, our contracted freight broker will reach out to your warehouse to make arrangements and schedule a dock appointment (if required). If you have any questions, please reply to this email and allow us to assist you.<br>";
        $eml_confirmation .= "<p style='font-family: Calibri;'>Please note:<br></p>";
        $eml_confirmation .= "<p style='font-family: Calibri; padding-left:80px;'><b>You are responsible for having a dock and forklift to unload your order</b> and ensure truck is unloaded in a timely manner.<br></p>";
        $eml_confirmation .= "<p style='font-family: Calibri; padding-left:80px;'>If you do not have a dock and forklift to unload your order, <b>please let us know immediately</b> and we will make other arrangements for your delivery (additional costs may apply).<br></p>";
        $eml_confirmation .= "<p style='font-family: Calibri;'>Thank you again for <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b> and the opportunity to work with you! <br></p>";

        $eml_confirmation .= "<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C;'><img src='https://www.ucbzerowaste.com/images/logo2.png'></td>";
        $eml_confirmation .= "<td><p style='font-family: Calibri; font-size:12;color:#538135'><u>National Operations Team</u><br>";
        $eml_confirmation .= "Used Cardboard Boxes, Inc. (UCB)</p>";
        $eml_confirmation .= "<span style='font-family: Calibri; font-size:12; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
        $eml_confirmation .= "1-888-BOXES-88<br><br>";
        //$eml_confirmation .= "<img src='https://www.ucbzerowaste.com/images/ucblogoside.jpg'><br>";
        $eml_confirmation .= "How can we improve?  Please tell our CEO@UsedCardboardBoxes.com</span>";
        $eml_confirmation .= "</td></tr></table>";

        $eml_confirmation .= "</td></tr><tr><td colspan='2'><p align='center'><img width='650' height='87' src='https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";
        /*}else{
				$eml_confirmation = "<html><head></head><body bgcolor='#E7F5C2'><table align='center' cellpadding='0' bgcolor='#E7F5C2'><tr><td colspan='2'><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td width='23' valign='top'><p> </p></td><td width='650'><br>";
				$eml_confirmation .= "<p align='center'><img src='https://loops.usedcardboardboxes.com/images/email-top-part3.jpg'></p>";
				$eml_confirmation .= "<p style='font-family: Calibri;'>Dear " . $shipto_name ;
				if ($sellto_name != "" && ($sellto_name != $shipto_name)){
					$eml_confirmation .= " (copy to " . $sellto_name . "),";
				}else{
					$eml_confirmation .= ",";
				}
				$eml_confirmation .= "<p style='font-family: Calibri;'><b>Order #" . $_REQUEST["rec_id"] . "</b> is ready for pickup at our $warehouse_name facility. Please be sure to begin to make arrangements to pickup the load.</p>";
				
				$eml_confirmation .= "<p style='font-family: Calibri;'>Contact Name: " . $warehouse_contact . "</p>";
				$eml_confirmation .= "<p style='font-family: Calibri;'>Facility Name: " . $warehouse_name . "</p>";
				$eml_confirmation .= "<p style='font-family: Calibri;'>Pickup Address: " . $warehouse_address . "</p>";
				$eml_confirmation .= "<p style='font-family: Calibri;'>Pickup Contact Details: " . $warehouse_eml . ", " . $warehouse_phone . "</p>";
				
				$eml_confirmation .= "<p style='font-family: Calibri;'>Thank you again for your <b>Order #" . $_REQUEST["rec_id"] . "</b> and the opportunity to work with you! <br></p>";
				
				$eml_confirmation .= "<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C;'><img src='https://www.ucbzerowaste.com/images/logo2.png'></td>";
				$eml_confirmation .= "<td><p style='font-family: Calibri; font-size:12;color:#538135'><u>National Freight Team</u><br>";
				$eml_confirmation .= "Used Cardboard Boxes, Inc. (UCB)</p>";
				$eml_confirmation .= "<span style='font-family: Calibri; font-size:12; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
				$eml_confirmation .= "323.724.2500 x5<br><br>";
				//$eml_confirmation .= "<img src='https://www.ucbzerowaste.com/images/ucblogoside.jpg'><br>";
				$eml_confirmation .= "How can we improve?  Please tell our CEO@UsedCardboardBoxes.com</span>";
				$eml_confirmation .= "</td></tr></table>";

				$eml_confirmation .= "</td></tr><tr><td colspan='2'><p align='center'><img width='650' height='87' src='https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";
			}*/


?>

<form name="email_reminder_sch_p2" id="email_reminder_sch_p2" action="scheduling_order_sendemail_save.php"
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
            <td width="90%"> <input size=60 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $myrowsel_main["shipemail"]; ?>"></td>
        </tr>

        <tr>
            <td width="10%">Cc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $sellto_eml; ?>;<?php echo $acc_owner_eml; ?>"></td>
        </tr>

        <tr>
            <td width="10%">Bcc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailbcc" name="txtemailbcc"
                    value="freight@UsedCardboardBoxes.com"></td>
        </tr>

        <tr>
            <td width="10%">Subject:</td>
            <td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject"
                    value="UsedCardboardBoxes Order #<?php echo $_REQUEST["rec_id"]; ?> is Booking Freight"></td>
        </tr>

        <tr>
            <td valign="top" width="10%">Body:</td>
            <td width="1000px" id="bodytxt">
                <?php

                        require_once('fckeditor_new/fckeditor.php');
                        $FCKeditor = new FCKeditor('txtemailbody');
                        $FCKeditor->BasePath = '/fckeditor_new/';
                        $FCKeditor->Value = $eml_confirmation;
                        $FCKeditor->Height = 600;
                        $FCKeditor->Width = 800;
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
}

?>