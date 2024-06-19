<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


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

    $dock_pickup_date = "";
    db();
    $result_n = db_query("Select date from loop_transaction_freight where trans_rec_id = " . $_REQUEST["rec_id"]);
    while ($myrowsel_n = array_shift($result_n)) {

        if ($myrowsel_n["date"] != "") {
            $dock_pickup_date = date('m/d/Y', strtotime($myrowsel_n["date"]));
        }
    }

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
    $result_n = db_query("SELECT * FROM loop_warehouse WHERE id = " . isset($loc_warehouse_id));

    while ($myrowsel_n = array_shift($result_n)) {
        $sets_own_dock_appointment = $myrowsel_n["sets_own_dock_appointment"];
        $warehouse_contact = $myrowsel_n["warehouse_contact"];

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

    $fr_name = "";
    $fr_contact = "";
    $fr_ph = "";
    $fr_eml = "";

    //$result_n = db_query("Select loop_freightvendor.* from loop_transaction_buyer inner join loop_freightvendor on loop_freightvendor.id = loop_transaction_buyer.broker_id where id = " . $_REQUEST["rec_id"], db() );

    db();
    $result_n = db_query("Select loop_freightvendor.* from loop_transaction_buyer_freightview inner join loop_freightvendor on loop_freightvendor.id = loop_transaction_buyer_freightview.broker_id where trans_rec_id = " . $_REQUEST["rec_id"]);
    while ($myrowsel_n = array_shift($result_n)) {
        $fr_name = $myrowsel_n["company_name"];
        $fr_contact = $myrowsel_n["company_contact"];
        $fr_ph = $myrowsel_n["company_phone"];
        $fr_eml = $myrowsel_n["company_email"];
    }

    if ($sets_own_dock_appointment == "yes" && $warehouse_calendly_link != "") {
        $eml_confirmation2 = "<html><head></head><body bgcolor='#E7F5C2'><table align='center' bgcolor='#E7F5C2' cellpadding='0'><tr><td colspan='2'><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td width='23' valign='top'><p> </p></td><td width='650'><br>";
        $eml_confirmation2 .= "<p align='center'><img src='https://loops.usedcardboardboxes.com/images/email-top-part3.jpg'></p>";

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Dear " . $shipto_name;
        if ($sellto_name != "" && ($sellto_name != $shipto_name)) {
            $eml_confirmation2 .= " (copy to " . $sellto_name . "),";
        } else {
            $eml_confirmation2 .= ",";
        }

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>For <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b>, the inventory has been allocated and is ready to ship. This transaction has been handed over to the National Freight Department to get your pickup and delivery booked.</p>";

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>As stated on the signed quote/purchase order, you will be arranging pickup and delivery on your own. The next step in this process is to setup a dock appointment with our warehouse.</p>";

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Please click <a href='" . $warehouse_calendly_link . "'>here</a> to schedule your dock appointment (which is required).</p>";

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Pickup Address: " . $warehouse_name . " " . $warehouse_address . "</p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Pickup Contact Details: " . $warehouse_eml . ", " . $warehouse_phone . "</p>";

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Please note:<br></p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri; padding-left:80px;'><b>You will need a dock height truck/trailer</b> to ensure loading in a timely manner.<br></p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri; padding-left:80px;'>If you do not have a dock height truck/trailer, <b>please let us know immediately</b> and we will make other arrangements for your pick up (additional costs may apply).<br></p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Thank you again for your <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b> and the opportunity to work with you! <br></p>";

        $eml_confirmation2 .= "<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C;'><img src='https://www.ucbzerowaste.com/images/logo2.png'></td>";
        $eml_confirmation2 .= "<td><p style='font-family: Calibri; font-size:12;color:#538135'><u>National Freight Team</u><br>";
        $eml_confirmation2 .= "Used Cardboard Boxes, Inc. (UCB)</p>";
        $eml_confirmation2 .= "<span style='font-family: Calibri; font-size:12; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
        $eml_confirmation2 .= "323.724.2500 x5<br><br>";
        //$eml_confirmation2 .= "<img src='https://www.ucbzerowaste.com/images/ucblogoside.jpg'><br>";
        $eml_confirmation2 .= "How can we improve?  Please tell our CEO@UsedCardboardBoxes.com</span>";
        $eml_confirmation2 .= "</td></tr></table>";
        $eml_confirmation2 .= "</td></tr><tr><td colspan='2'><p align='center'><img width='650' height='87' src='https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";
    } else {
        $eml_confirmation2 = "<html><head></head><body bgcolor='#E7F5C2'><table align='center' bgcolor='#E7F5C2' cellpadding='0'><tr><td colspan='2'><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td width='23' valign='top'><p> </p></td><td width='650'><br>";
        $eml_confirmation2 .= "<p align='center'><img src='https://loops.usedcardboardboxes.com/images/email-top-part3.jpg'></p>";

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Dear " . $shipto_name;
        if ($sellto_name != "" && ($sellto_name != $shipto_name)) {
            $eml_confirmation2 .= " (copy to " . $sellto_name . "),";
        } else {
            $eml_confirmation2 .= ",";
        }

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>For <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b>, the inventory has been allocated and is ready to ship. This transaction has been handed over to the National Freight Department to get your pickup and delivery booked.</p>";

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>As stated on the signed quote/purchase order, you will be arranging pickup and delivery on your own. The next step in this process is to setup a dock appointment with our $warehouse_name facility.</p>";

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Contact Name: " . $warehouse_contact . "</p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Facility Name: " . $warehouse_name . "</p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Pickup Address: " . $warehouse_address . "</p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Pickup Contact Details: " . $warehouse_eml . ", " . $warehouse_phone . "</p>";

        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Please note:<br></p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri; padding-left:80px;'><b>You will need a dock height truck/trailer</b> to ensure loading in a timely manner.<br></p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri; padding-left:80px;'>If you do not have a dock height truck/trailer, <b>please let us know immediately</b> and we will make other arrangements for your pick up (additional costs may apply).<br></p>";
        $eml_confirmation2 .= "<p style='font-family: Calibri;'>Thank you again for <b>Order #" . $_REQUEST["rec_id"] . " (PO #" . $po_ponumber . ")</b> and the opportunity to work with you! <br></p>";

        $eml_confirmation2 .= "<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C;'><img src='https://www.ucbzerowaste.com/images/logo2.png'></td>";
        $eml_confirmation2 .= "<td><p style='font-family: Calibri; font-size:12;color:#538135'><u>National Freight Team</u><br>";
        $eml_confirmation2 .= "Used Cardboard Boxes, Inc. (UCB)</p>";
        $eml_confirmation2 .= "<span style='font-family: Calibri; font-size:12; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
        $eml_confirmation2 .= "323.724.2500 x5<br><br>";
        //$eml_confirmation2 .= "<img src='https://www.ucbzerowaste.com/images/ucblogoside.jpg'><br>";
        $eml_confirmation2 .= "How can we improve?  Please tell our CEO@UsedCardboardBoxes.com</span>";
        $eml_confirmation2 .= "</td></tr></table>";
        $eml_confirmation2 .= "</td></tr><tr><td colspan='2'><p align='center'><img width='650' height='87' src='https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";
    }

?>

<form name="email_reminder_sch_p3" id="email_reminder_sch_p3" action="sendemail_broker_needs_pickup_cust_save.php"
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
                    value="<?php echo $shipto_email; ?>;<?php echo $warehouse_eml; ?>"></td>
        </tr>

        <tr>
            <td width="10%">Cc:</td>
            <?php if ($sets_own_dock_appointment == "yes" && $warehouse_calendly_link != "") { ?>
            <td width="90%"> <input size=60 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $acc_owner_eml; ?>;<?php echo $sellto_eml; ?>"></td>
            <?php } else { ?>
            <td width="90%"> <input size=60 type="text" id="txtemailcc" name="txtemailcc"
                    value="<?php echo $acc_owner_eml; ?>;"></td>
            <?php }  ?>
        </tr>

        <tr>
            <td width="10%">Bcc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailbcc" name="txtemailbcc"
                    value="freight@usedcardboardboxes.com"></td>
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
                    $FCKeditor->BasePath = '/fckeditor_new/';
                    $FCKeditor->Value = $eml_confirmation2;
                    $FCKeditor->Height = 600;
                    $FCKeditor->Width = 1000;
                    $FCKeditor->Create();
                    ?>
                <div style="height:15px;">&nbsp;</div>
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