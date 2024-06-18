<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

?>


<!DOCTYPE HTML>

<html>

<head>
    <title>Enterprise TMS</title>
    <link rel="stylesheet" href="sorter/style_rep.css" />
</head>

<body>

    <?php

    if (isset($_POST["btntmsid"])) {

        $get_wh = "Update loop_enterprise_tms_data set quote_id = " . $_POST["txtid"] . " where unqid = " . $_POST["newins_id"];

        db();
        $get_wh_res = db_query($get_wh);

        echo "<script type=\"text/javascript\">";
        echo "window.location.href=\"loop_shipbubble_pickup_or_ucb_delivering.php?ID=" . $_REQUEST['ID'] . "&show=transactions&warehouse_id=" . $_POST["warehouse_id"] . "&rec_id=" . $_POST["rec_id"] . "&rec_type=Supplier&proc=View&searchcrit=&display=buyer_ship\";";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv=\"refresh\" content=\"0;url=loop_shipbubble_pickup_or_ucb_delivering.php?ID=" . $_REQUEST['ID'] . "&show=transactions&warehouse_id=" . $_POST["warehouse_id"] . "&rec_id=" . $_POST["rec_id"] . "&rec_type=Supplier&proc=View&searchcrit=&display=buyer_ship\" />";
        echo "</noscript>";
        exit;
    }

    if (isset($_POST["shipment_nm"])) {


        $authId = "115247";
        $authKey = "1592489054";
        $verno = "2.0";


        $get_wh = "SELECT shipping_receiving_hours FROM companyInfo WHERE ID = " . $_POST["ID"];
        db_b2b();
        $get_wh_res = db_query($get_wh);
        while ($the_wh = array_shift($get_wh_res)) {
        }

        $get_wh = "Insert into loop_enterprise_tms_data (trans_rec_id, shipment_name, ship_from, pickup_date, spl_instruction, add_info_equ, enter_by, enterd_on, shipment_option, pickupdate_to, delivery_date, accessorials_other, deadline_date) ";
        $get_wh .= " select " . $_POST["rec_id"] . ", '" . preg_replace("/'/", "\'", $_POST["shipment_nm"]) . "', " .  preg_replace("/'/", "\'", $_POST["warehouseship_from"]) . ", '" . date("Y-m-d", strtotime($_POST["enttms_pickupdate"])) . "', ";
        $get_wh .= " '" . preg_replace("/'/", "\'", $_POST["sp_inst"]) . "', '" .  preg_replace("/'/", "\'", $_POST["equipment_lst"]) . "', '" . $_COOKIE['userinitials'] . "', '" . date("Y-m-d H:i:s") . "', '" . $_POST["shipment_option"] . "', ";
        $get_wh .= " '" . $_POST["enttms_pickupdate_to"] . "', '" .  $_POST["enttms_deliverydate"] . "', '" . $_POST['accessorials_other'] . "', '" . $_POST['deadline_date'] . "'";

        db();
        $get_wh_res = db_query($get_wh);
        $newins_id = tep_db_insert_id();

        if ($_POST["deadline_date"] != "") {
            if (strtotime($_POST["deadline_date"]) < strtotime($_POST["enttms_deliverydate"])) {
                $end_date = date($_POST["enttms_deliverydate"], strtotime('+1 day'));
            } else {
                $end_date = date("m/d/Y", strtotime($_POST["deadline_date"]));
            }
            $end_date .= " " . $_POST["deadline_time_in_hour"] . ":00 AM";
        }

        $quote_number = 0;
        $freight_cost = 0;
        $po_poorderamount = 0;
        $dtt_view_qry = "SELECT quote_number, po_freight, po_poorderamount from loop_transaction_buyer WHERE id = " . $_POST["rec_id"];
        $dtt_view_res1 = db_query($dtt_view_qry);
        while ($dtt_view_res = array_shift($dtt_view_res1)) {
            $freight_cost = $dtt_view_res["po_freight"];
            $po_poorderamount = $dtt_view_res["po_poorderamount"];
        }
        $min_fob = number_format(($po_poorderamount - $freight_cost), 2);

        $mainxml = "<FREIGHTVIEW>
		<SESSION_LIST>
		<SESSION id=''>
		<SHIPMENT type='live'>
		<END_DATE>" . isset($end_date) . "</END_DATE>
		<LABEL>" . $_POST["shipment_nm"] . "</LABEL>
		<DELIVERY_DATE>" . $_POST["enttms_deliverydate"] . "</DELIVERY_DATE>
		<READY_DATE>" . $_POST["enttms_pickupdate"] . "</READY_DATE>
		<MAX_PICKUP_DATE>" . $_POST["enttms_pickupdate_to"] . "</MAX_PICKUP_DATE>
		<REFERENCE_ID>" . $_POST["rec_id"] . "</REFERENCE_ID>
		<DECLARED_VALUE>" . $min_fob . "</DECLARED_VALUE>
		<BOL_NUMBER>" . $_POST["rec_id"] . "</BOL_NUMBER>
		<SHIPMENT_REFERENCE>" . $_POST["rec_id"] . "</SHIPMENT_REFERENCE>
		<SPECIAL_INSTRUCTIONS>" . $_POST["sp_inst"] . "</SPECIAL_INSTRUCTIONS>
		<USERID>115247</USERID>		
		<INTEGRATION_REFERENCE_NUMBER>" . $_POST["rec_id"] . "</INTEGRATION_REFERENCE_NUMBER>
		<LOCATIONS>";
        $shipping_receiving_hours_txt = "";
        $get_wh = "SELECT * FROM loop_warehouse WHERE (rec_type LIKE 'sorting' or rec_type LIKE 'Manufacturer') AND Active=1 and id = " . $_POST["warehouseship_from"] . " ORDER BY company_name ASC";

        db();
        $get_wh_res = db_query($get_wh);
        while ($the_wh = array_shift($get_wh_res)) {
            $tmp_ph = str_replace("-", "", trim($the_wh["warehouse_contact_phone"]));
            $tmp_ph = str_replace(".", "", $tmp_ph);
            $tmp_ph = str_replace(" ", "", $tmp_ph);
            $tmp_ph = str_replace(")", "", $tmp_ph);
            $tmp_ph = str_replace("(", "", $tmp_ph);
            $tmp_ph = substr($tmp_ph, 0, 10);

            $shipping_receiving_hours_txt = "Shipper Hours/Notes: " . trim($the_wh["dock_details"]);

            $mainxml .=    "<LOCATION sequence='0' type='Origin'>
				<NAME>UsedCardboardBoxes.com</NAME>				
				<STREET_ADDRESS>" . $the_wh["warehouse_address1"] . "</STREET_ADDRESS>
				<STREET_ADDRESS2>" . $the_wh["warehouse_address2"] . "</STREET_ADDRESS2>
				<CITY>" . $the_wh["warehouse_city"] . "</CITY>
				<STATE>" . $the_wh["warehouse_state"] . "</STATE>
				<POSTAL_CODE>" . $the_wh["warehouse_zip"] . "</POSTAL_CODE>
				<APPOINTMENT required='true' scheduled='false'>
					<APPOINTMENT_TIME></APPOINTMENT_TIME>
				</APPOINTMENT>
				<CONTACT>
					<COMPANY>UsedCardboardBoxes.com</COMPANY>
					<NAME>" . $the_wh["warehouse_contact"] . "</NAME>
					<PHONE>" . $tmp_ph . "</PHONE>
					<EMAIL_ADDRESS>freight@UsedCardboardBoxes.com</EMAIL_ADDRESS>
				</CONTACT>
				<FACILITY type='BusinessWithDock'>
					<ARRIVAL_NOTIFICATION></ARRIVAL_NOTIFICATION>
					<INSIDE_PICKUP_DELIVERY></INSIDE_PICKUP_DELIVERY>
					<LIFTGATE_REQUIRED></LIFTGATE_REQUIRED>
				</FACILITY>
			</LOCATION>";
        }
        $get_wh = "SELECT company, shipContact, shipAddress, shipAddress2, shipCity, shipState, shipZip, shipPhone, email, shipping_receiving_hours FROM companyInfo WHERE ID = " . $_POST["ID"];

        db_b2b();
        $get_wh_res = db_query($get_wh);
        while ($the_wh = array_shift($get_wh_res)) {
            $tmp_ph = str_replace("-", "", trim($the_wh["shipPhone"]));
            $tmp_ph = str_replace(".", "", $tmp_ph);
            $tmp_ph = str_replace(" ", "", $tmp_ph);
            $tmp_ph = str_replace(")", "", $tmp_ph);
            $tmp_ph = str_replace("(", "", $tmp_ph);
            $tmp_ph = substr($tmp_ph, 0, 10);

            if ($the_wh["shipping_receiving_hours"] != "") {
                $shipping_receiving_hours_txt .= " Delivery Hours/Notes: " . $the_wh["shipping_receiving_hours"];
            }

            $mainxml .=    "<LOCATION sequence='2' type='Destination'>
				<NAME>" . $the_wh["shipContact"] . "</NAME>
				<STREET_ADDRESS>" . $the_wh["shipAddress"] . "</STREET_ADDRESS>
				<STREET_ADDRESS2>" . $the_wh["shipAddress2"] . "</STREET_ADDRESS2>
				<CITY>" . $the_wh["shipCity"] . "</CITY>
				<STATE>" . $the_wh["shipState"] . "</STATE>
				<POSTAL_CODE>" . $the_wh["shipZip"] . "</POSTAL_CODE>
				<SPECIAL_INSTRUCTIONS>" . $shipping_receiving_hours_txt . "</SPECIAL_INSTRUCTIONS>
				<APPOINTMENT required='true' scheduled='false'>
					<APPOINTMENT_TIME></APPOINTMENT_TIME>
				</APPOINTMENT>
				<CONTACT>
					<COMPANY>" . $the_wh["company"] . "</COMPANY>
					<NAME>" . $the_wh["shipContact"] . "</NAME>
					<PHONE>" . $tmp_ph . "</PHONE>
					<EMAIL_ADDRESS>" . $the_wh["email"] . "</EMAIL_ADDRESS>
				</CONTACT>
				<FACILITY type='BusinessWithDock'>
					<ARRIVAL_NOTIFICATION></ARRIVAL_NOTIFICATION>
					<INSIDE_PICKUP_DELIVERY></INSIDE_PICKUP_DELIVERY>
					<LIFTGATE_REQUIRED></LIFTGATE_REQUIRED>
				</FACILITY>
			</LOCATION>";
        }
        $mainxml .=    "</LOCATIONS>
			<PRODUCTS>";
        for ($ctlcnt = 0; $ctlcnt < $_POST["prod_cnt"]; $ctlcnt++) {
            if ($_POST["txt_qty"][$ctlcnt] > 0) {
                $get_wh = "Insert into loop_enterprise_tms_trans_data (tms_trans_id, pallets, weight, length, width, height, product) ";
                $get_wh .= " select " . $newins_id . ", '" . $_POST["txt_qty"][$ctlcnt] . "', " . $_POST["txt_weight"][$ctlcnt] . ", '" . $_POST["txt_length"][$ctlcnt] . "', ";
                $get_wh .= " '" . $_POST["txt_width"][$ctlcnt] . "', '" . $_POST["txt_height"][$ctlcnt] . "', '" . $_POST["txt_qty"][$ctlcnt] . "'";

                db();
                $get_wh_res = db_query($get_wh);

                $PACKAGING_TYPE = "pallet";
                if ($_POST["txt_product"][$ctlcnt] == "Gaylord Totes") {
                    $PACKAGING_TYPE = "gaylord";
                }
                if ($_POST["txt_product"][$ctlcnt] == "Shipping Boxes") {
                    $PACKAGING_TYPE = "box";
                }
                if ($_POST["txt_product"][$ctlcnt] == "Supersacks") {
                    $PACKAGING_TYPE = "bale";
                }
                if ($_POST["txt_product"][$ctlcnt] == "Drums") {
                    $PACKAGING_TYPE = "drum";
                }
                if ($_POST["txt_product"][$ctlcnt] == "Pallets") {
                    $PACKAGING_TYPE = "pallet";
                }

                $mainxml .=    "<PRODUCT>
						<PACKAGING_TYPE>" . $PACKAGING_TYPE . "</PACKAGING_TYPE>
						<QUANTITY>" . $_POST["txt_qty"][$ctlcnt] . "</QUANTITY>
						<DESCRIPTION>" . $_POST["txt_product"][$ctlcnt] . "</DESCRIPTION>
						<WEIGHT>" . $_POST["txt_weight"][$ctlcnt] . "</WEIGHT>
						<LENGTH>" . $_POST["txt_length"][$ctlcnt] . "</LENGTH>
						<WIDTH>" . $_POST["txt_width"][$ctlcnt] . "</WIDTH>
						<HEIGHT>" . $_POST["txt_height"][$ctlcnt] . "</HEIGHT>
						<FREIGHT_CLASS>125</FREIGHT_CLASS>
						<NMFC_NUMBER></NMFC_NUMBER>
						<SAID_TO_CONTAIN></SAID_TO_CONTAIN>
						<IS_HAZMAT>false</IS_HAZMAT>
					</PRODUCT>";
            }
        }
        $mainxml .=    "</PRODUCTS>
		<CONTACT_ADDRESSES />
		<DISTANCE>0</DISTANCE>
		<ACCESSORIALS>
			<SHIPMENT_ACCESSORIAL>
				<SPECIAL_REQUIREMENTS subcategory='2'>straps required</SPECIAL_REQUIREMENTS>
			</SHIPMENT_ACCESSORIAL>		
		</ACCESSORIALS>";
        $get_wh = "SELECT equipment_class, equipment_type, equipment_class2, equipment_type2 FROM ent_tms_equipment_master WHERE equipment_id = " . $_POST["equipment_lst"];

        db();
        $get_wh_res = db_query($get_wh);
        while ($the_wh = array_shift($get_wh_res)) {
            $mainxml .=    "<MODE_LIST>
					<MODE>
						<CLASS>" . $the_wh["equipment_class"] . "</CLASS>
						<EQUIPMENT_LIST>
							<EQUIPMENT>" . $the_wh["equipment_type"] . "</EQUIPMENT>
						</EQUIPMENT_LIST>
					</MODE>";
            if ($the_wh["equipment_class2"] != "") {
                $mainxml .=    "<MODE>
							<CLASS>" . $the_wh["equipment_class2"] . "</CLASS>
							<EQUIPMENT_LIST>
								<EQUIPMENT>" . $the_wh["equipment_type2"] . "</EQUIPMENT>
							</EQUIPMENT_LIST>
							</MODE>";
            }
            $mainxml .=    "</MODE_LIST>";
        }
        $mainxml .=    "</SHIPMENT>
		</SESSION>
		</SESSION_LIST>
		</FREIGHTVIEW>";

    ?>
    <font size="1"><b>Step 1</b> - TMS system doesn't take the input directly. This is intermediate page, click on
        'Submit to Enterprise TMS' button.</font>
    <?php if ($_POST["shipment_option"] == "fulltruck") { ?>
    <form target="_blank" action='https://www.enterprisetms.com/ws/ShipmentIntegration.asmx/PostEditShipment'
        name="frmenttmspost" method="POST">
        <?php } else { ?>
        <form target="_blank" action='https://www.enterprisetms.com/ws/ShipmentIntegration.asmx/PostEditLTLShipment'
            name="frmenttmspost" method="POST">
            <?php }  ?>
            <input type="hidden" name="authId" value="<?php echo $authId; ?>">
            <input type="hidden" name="authKey" value="<?php echo $authKey; ?>">
            <input type="hidden" name="VersionNumber" value="<?php echo $verno; ?>">

            <table cellspacing="0" cellpadding="4" frame="box" bordercolor="#dcdcdc" rules="none"
                style="border-collapse: collapse;">
                <tr>
                    <td class="frmHeader" background="#dcdcdc" style="border-right: 2px solid white;">
                        <font size="1">Parameter</font>
                    </td>
                    <td class="frmHeader" background="#dcdcdc">
                        <font size="1">Value</font>
                    </td>
                </tr>

                <tr>
                    <td class="frmText" style="color: #000000; font-weight: normal;">
                        <font size="1">TMS file:</font>
                    </td>
                    <td><input class="frmInput" type="text" size="50" name="ShipmentList" style="font-size: 8pt;"
                            value="<?php echo $mainxml; ?>"></td>
                </tr>

                <tr>
                    <td></td>
                    <td align="right"> <input type="submit" value="Submit to Enterprise TMS" class="button"></td>
                </tr>
            </table>
        </form>
        <br />
        <font size="1"><b>Step 2</b> - After 'Submit to Enterprise TMS' button is clicked and program will open new page
            in new tab/window and following message is displayed.<br>
            '&lt;SESSION_LIST&gt; &lt;SESSION id="XXXXXXX"&gt; &lt;LABEL&gt; XXXXXXX &lt;LABEL&gt;
            &lt;SESSION_ID&gt; XXXXXXX &lt;SESSION_ID&gt;
            &lt;CONFIRMATION&gt; Shipment has been posted successfully &lt;CONFIRMATION&gt;
            &lt;SESSION&gt;
            &lt;SESSION_LIST&gt;'</font>
        <br><br>

        <font size="1"><b>Step 3</b> - Copy the SESSION_ID number from new tab/window and past in the 'TMS SESSION_ID'
            input box and click on 'Update TMS SESSION_ID' button.</font>
        <form action='ent_tms_post.php' name="frmuploadtmsid" method="POST">
            <input type=hidden name="ID" value="<?php echo $_REQUEST["ID"]; ?>">
            <input type=hidden name="warehouse_id" value="<?php echo $_REQUEST["warehouse_id"]; ?>">
            <input type=hidden name="rec_type" value="<?php echo $_REQUEST["rec_type"]; ?>">
            <input type=hidden name="rec_id" value="<?php echo $_REQUEST["rec_id"]; ?>">
            <input type=hidden name="newins_id" value="<?php echo $newins_id; ?>">

            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <font size="1">TMS SESSION_ID:</font>
                    </td>
                    <td><input type="text" size="15" name="txtid" id="txtid" value=""></td>
                </tr>

                <tr>
                    <td></td>
                    <td align="right"> <input type="submit" value="Update TMS SESSION_ID" style="font-size: 8pt;"
                            name="btntmsid" id="btntmsid"></td>
                </tr>
            </table>
        </form>


        <!--
			After the Data is submitted, <a href="<?php echo $_SERVER['HTTP_REFERER'] . "?ID=" . $_POST["ID"] . "&warehouse_id=" . $_POST["warehouse_id"] . "&rec_id=" . $_POST["rec_id"] . "&rec_type=" . $_POST["rec_type"]; ?>">click here</a> to Go Back.
		-->

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <script type="text/javascript">
        $(document).ready(function() {
            document.frmenttmspost.submit();
        });
        </script>


        <?php

        }
            ?>

</body>

</html>