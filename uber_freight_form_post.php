<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


?>


<!DOCTYPE HTML>

<html>

<head>
    <title>Uber Freight</title>
    <link rel="stylesheet" href="sorter/style_rep.css" />

    <script>
    function showquote() {
        document.getElementById('uberfreightquote').style.display = "block";
    }

    function update_quote(cart_itemID) {
        parent.document.getElementById('shipfinal' + cart_itemID).value = document.getElementById('quote_amount')
            .innerHTML;
        parent.calcualteprofitloss(this.form, cart_itemID);
    }
    </script>
</head>

<body>

    <?php


    $url = 'https://login.uber.com/oauth/v2/token';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=HIpNTYMMUZbj_KGeBqLST9D1FCZ6bT2B&client_secret=YFjURNQqmTqXRFdTOlyEUpba0Q3Rvq0ftcQ_ujFe&grant_type=client_credentials&scope=freight.loads");

    // In real life you should use something like:
    // curl_setopt($ch, CURLOPT_POSTFIELDS, 
    //          http_build_query(array('postvar1' => 'value1')));

    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $res_Data = curl_exec($ch);

    $jsonData = json_decode($res_Data, true);

    curl_close($ch);

    $client_auth_code = "";
    if (isset($jsonData["code"])) {
        echo "<font color=red>Error: " . $jsonData["code"] . " " . $jsonData["message"] . " </font><br>";
        print_r(isset($quote_req));
        exit;
    } else {

        //"{"access_token":"JA.VUNmGAAAAAAAEgASAAAABwAIAAwAAAAAAAAAEgAAAAAAAAHQAAAAFAAAAAAADgAQAAQAAAAIAAwAAAAOAAAApAAAABwAAAAEAAAAEAAAADzmoONAboovssJm-_-brEyAAAAA1N5aTk7mKqW3eGCnnIwPVAPboKd4kmu08ebl4hCkrDM2hAqO41XyWrYNzDN1Y71BAkIgY5lOsoZRiPg6_bSsd0wFuTEaPgDpjcFJA94rCFO0kRsv2KV_R25bXP-wt8DdJgBtSQiNNrKRuMtmPacgCO6W-vxHlFWW3VDeN8qsDzkMAAAA1lWENOfWpive5TfoJAAAAGIwZDg1ODAzLTM4YTAtNDJiMy04MDZlLTdhNGNmOGUxOTZlZQ","token_type":"Bearer","expires_in":2592000,"scope":"freight.loads"}
        $client_auth_code = $jsonData["access_token"];
    }

    //$client_auth_code = "JA.VUNmGAAAAAAAEgASAAAABwAIAAwAAAAAAAAAEgAAAAAAAAHQAAAAFAAAAAAADgAQAAQAAAAIAAwAAAAOAAAApAAAABwAAAAEAAAAEAAAAF1v-KBzt_RHPWfuZpLLzJKAAAAAOmRS7oTw8tcO92LAT0xpy28Nn-DgmJPwjyVqATR6s9rM-aCFhaGR5SUp3X3xd8Qr-8KBmIOL9iO8yl8rj32eM_RK8dDiqO-3G2QKLGmDj4ZDbkCa43AxBQomYMeZqW24v2_kGIpSo6nKJZ9Ds5eRjTANzc-sA7CitaQeG8BM0lMMAAAAnpiT2_QALshUGPpCJAAAAGIwZDg1ODAzLTM4YTAtNDJiMy04MDZlLTdhNGNmOGUxOTZlZQ";

    //$url = 'https://sandbox-api.uber.com/v1/freight/loads/quotes';
    $url = 'https://api.uber.com/v1/freight/loads/quotes';

    $cart_itemID = 100389;
    $compid = 28833;

    $warehouseship_from_id = $_REQUEST["uber_freight_warehouseship_from"];
    $compid = $_REQUEST["uber_freight_ID"];

    $bweight = 0;
    $vendor_b2b_rescue = 0;
    $vendor_b2b_rescue_b2bid = 0;
    $box_weight = 0;
    $pickup_nm = "";
    $pickup_add1 = "";
    $pickup_add2 = "";
    $pickup_city = "";
    $pickup_state = "";
    $pickup_zip = "";
    $dropoff_nm = "";
    $dropoff_add1 = "";
    $dropoff_add2 = "";
    $box_id = 0;
    $box_inv_id = 0;
    $dropoff_city = "";
    $dropoff_state = "";
    $dropoff_zip = "";
    $box_description = "";

    //case to handle UCB warehouse (HA, ML, HV, HK Trans) 
    $rec_found_sorting_w = "no";

    db();
    $dt_view_res_data = db_query("Select * from loop_warehouse where rec_type = 'Sorting' and id = " . $warehouseship_from_id);
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

    if ($rec_found_sorting_w == "no") {

        db_b2b();
        $dt_view_res_data = db_query("Select * from companyInfo where loopid = " . $warehouseship_from_id);
        while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
            $vendor_b2b_rescue_b2bid = $myrowsel_b2b["loopid"];
            $pickup_nm = strval(get_nickname_val($myrowsel_b2b["company"], $myrowsel_b2b["ID"]));
            $pickup_add1 = strval($myrowsel_b2b["shipAddress"]);
            $pickup_add2 = strval($myrowsel_b2b["shipAddress2"]);
            $pickup_city = strval($myrowsel_b2b["shipCity"]);
            $pickup_state = strval($myrowsel_b2b["shipState"]);
            $pickup_zip = substr(strval($myrowsel_b2b["shipZip"]), 0, 5);
        }
    }
    $box_weight = $_REQUEST["txt_weight_uber_freight"];

    db_b2b();
    $dt_view_res_data = db_query("Select * from companyInfo where ID = " . $compid);
    while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
        $dropoff_nm = strval(get_nickname_val($myrowsel_b2b["company"], $compid));
        $dropoff_add1 = strval($myrowsel_b2b["shipAddress"]);
        $dropoff_add2 = strval($myrowsel_b2b["shipAddress2"]);
        $dropoff_city = strval($myrowsel_b2b["shipCity"]);
        $dropoff_state = strval($myrowsel_b2b["shipState"]);
        $dropoff_zip = substr(strval($myrowsel_b2b["shipZip"]), 0, 5);
    }

    $donotprocess = "no";

    if ($pickup_nm == "") {
        $pickup_nm = "Facility pickup - " . $vendor_b2b_rescue_b2bid;
    }
    if ($dropoff_nm == "") {
        $dropoff_nm = "Facility dropoff - " . $compid;
    }

    if (
        $pickup_add1 == "" || $pickup_city == "" || $pickup_state == "" || $pickup_zip == "" ||
        $dropoff_add1 == "" || $dropoff_city == "" || $dropoff_state == "" || $dropoff_zip == ""
    ) {
        $donotprocess = "yes";
        echo "<font color=red>Pickup/Drop off address1/city/state/zip is blank, process terminated.</font>";
    }

    if ($client_auth_code == "") {
        $donotprocess = "yes";
        echo "<font color=red>Uber Authentication token is empty, process terminated.</font>";
    }

    if ($donotprocess == "no") {

        //Calculate the distance in miles
        //echo "pickup_zip - " . $pickup_zip . " Dropoff_zip - "  . $dropoff_zip . "<br>";
        $transit_time = 0;
        if ($pickup_zip != "" && $dropoff_zip != "") {
            $tmppos_1 = strpos($pickup_zip, " ");
            if ($tmppos_1 != false) {
                //$tmp_zipval = substr($row["location_zip"], 0, $tmppos_1);
                $tmp_zipval = str_replace(" ", "", $pickup_zip);
                $zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
            } else {
                $zipStr = "Select * from ZipCodes WHERE zip = '" . intval($pickup_zip) . "'";
            }

            db_b2b();
            $res4 = db_query($zipStr);
            $objShipZip = array_shift($res4);

            $shipLat = $objShipZip["latitude"];
            $shipLong = $objShipZip["longitude"];

            $location_zip = $dropoff_zip;

            //$zipStr= "Select * from ZipCodes WHERE zip = " . remove_non_numeric($objInvmatch["location"]);
            $zipStr = "";
            $tmppos_1 = strpos($location_zip, " ");
            if ($tmppos_1 != false) {
                //$tmp_zipval = substr($row["location_zip"], 0, $tmppos_1);
                $tmp_zipval = str_replace(" ", "", $location_zip);
                $zipStr = "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
            } else {
                $zipStr = "Select * from ZipCodes WHERE zip = '" . intval($location_zip) . "'";
            }

            db_b2b();
            $dt_view_res4 = db_query($zipStr);
            while ($ziploc = array_shift($dt_view_res4)) {
                $locLat = $ziploc["latitude"];

                $locLong = $ziploc["longitude"];
            }

            $distLat = ($shipLat - isset($locLat)) * 3.141592653 / 180;
            $distLong = ($shipLong - isset($locLong)) * 3.141592653 / 180;

            $distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos(isset($locLat) * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);

            $distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
            $distance = (int) (6371 * $distC * .621371192);
            //echo "Distance: ". $distance . " miles<BR>";

            //$transit_time = ceil($distance/500);
            $transit_time = ceil($distance / 700);

            //echo $transit_time . "<br>";
        }

        if ($transit_time == 0) {
            $transit_time = 1;
        }

        $freight_str = "";
        $quote_amount_tot = 0;
        $ifany_err = "";

        /*for ($tmpcnt = 1; $tmpcnt <= 7; $tmpcnt++) {
		
			if ($tmpcnt == 1){
				$date = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
				$datenxt = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
				$interval = new DateInterval('P0DT4H');
				$date->add($interval);
				$starttime = $date->format('U');
			}else{
				$date = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
				$datenxt = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
				$interval = new DateInterval('P' . ($tmpcnt - 1) . 'DT1H');
				$date->add($interval);
				$starttime = $date->format('U');
			}			
			//echo $date->format('m/d/Y') . "<br>";

			if ($tmpcnt == 1){
				$interval = new DateInterval('P0DT4H');
			}else{
				$interval = new DateInterval('P' . ($tmpcnt - 1) . 'DT1H');
			}			
			$datenxt->add($interval);
			$endtime = $datenxt->format('U');
			//echo $datenxt->format('m/d/Y') . "<br>";
			$starttime = floatval($starttime);
			$endtime = floatval($endtime);

			if ($tmpcnt == 1){
				$date2 = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
				$interval = new DateInterval('P' . ($transit_time). 'DT1H');
				$date2->add($interval);
				$starttime_2 = $date2->format('U');
			}else{
				$date2 = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
				$interval = new DateInterval('P' . ($transit_time + ($tmpcnt - 1)). 'DT1H');
				$date2->add($interval);
				$starttime_2 = $date2->format('U');
			}	
			//echo $date2->format('m/d/Y') . "<br>";

			if ($tmpcnt == 1){
				$date3 = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
				$interval = new DateInterval('P' . ($transit_time) . 'DT1H');
				$date3->add($interval);
				$endtime_2 = $date3->format('U');
			}else{
				$date3 = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
				$interval = new DateInterval('P' . ($transit_time + ($tmpcnt - 1)) . 'DT1H');
				$date3->add($interval);
				$endtime_2 = $date3->format('U');
			}	
			//echo $date3->format('m/d/Y') . "<br><br>";
			*/
        for ($tmpcnt = 1; $tmpcnt <= 1; $tmpcnt++) {

            $date = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
            $datenxt = new DateTime($_REQUEST["uber_freight_pickupdate"]); // format: MM/DD/YYYY
            $interval = new DateInterval('P0DT5H');
            $date->add($interval);
            $starttime = $date->format('U');

            $interval = new DateInterval('P0DT5H');
            $datenxt->add($interval);
            $endtime = $datenxt->format('U');

            $starttime = floatval($starttime);
            $endtime = floatval($endtime);

            echo "From date - " . $date->format('m/d/Y') . "<br>";

            $tmpcnt = 2;
            $date2 = new DateTime($_REQUEST["uber_freight_pickupdate_to"]);
            $date3 = new DateTime($_REQUEST["uber_freight_pickupdate_to"]);
            $interval2 = new DateInterval('P0DT5H');
            $date2->add($interval2);
            $date3->add($interval2);

            echo "To date - " . $date2->format('m/d/Y') . "<br>";

            $starttime_2 = $date2->format('U');
            $endtime_2 = $date3->format('U');
            $starttime_2 = floatval($starttime_2);
            $endtime_2 = floatval($endtime_2);

            $vendor_b2b_rescue_b2bid = strval($vendor_b2b_rescue_b2bid);
            $compid = strval($compid);

            $uber_qote_id = 0;

            db_b2b();
            $dt_view_res_data = db_query("Select max(unqid) as maxunqid from quoting_uber_freight_data");
            while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
                $uber_qote_id = $myrowsel_b2b["maxunqid"];
                $uber_qote_id = $uber_qote_id + 1;
            }

            //echo "Starttime - " . $starttime, ' - End_time_utc - ' . $endtime . "<br>";
            //echo "Starttime - " . $starttime_2, ' - End_time_utc - ' . $endtime_2 . "<br>";

            $stoparr = array(
                array(
                    'sequence_number' => 1, 'type' => 'PICKUP', 'mode' => 'LIVE', 'facility' => array(
                        'facility_id' => $vendor_b2b_rescue_b2bid, 'name' => $pickup_nm,
                        'address' => array('line1' => $pickup_add1, 'line2' => $pickup_add2, 'city' => $pickup_city,  'principal_subdivision' => $pickup_state, 'postal_code' => $pickup_zip, 'country' => 'USA')
                    ),
                    'appointment' => array('status' => 'NEEDED', 'start_time_utc' => $starttime, 'end_time_utc' => $endtime)
                ),
                array(
                    'sequence_number' => 2, 'type' => 'DROPOFF', 'mode' => 'LIVE',
                    'facility' => array(
                        'facility_id' => $compid, 'name' => $dropoff_nm,
                        'address' => array('line1' => $dropoff_add1, 'line2' => $dropoff_add2, 'city' => $dropoff_city,  'principal_subdivision' => $dropoff_state, 'postal_code' => $dropoff_zip, 'country' => 'USA')
                    ),
                    'appointment' => array('status' => 'NEEDED', 'start_time_utc' => $starttime_2, 'end_time_utc' => $endtime_2)
                )
            );

            //USEDCARDBOARDBOXES
            $quote_req = array(
                'quote_id' => 'UCB_Qutoe_id' . $uber_qote_id, 'customer_id' => 'USEDCARDBOARDBOXES',
                'requirements' => array('vehicle_type' => 'DRY', 'weight' => array('amount' => floatval($box_weight), 'unit' => 'LB')),
                'stops' => $stoparr, 'quote_type' => ''
            );

            //LHR_ONLY

            $ch = curl_init();
            //print_r($quote_req);

            $json = json_encode($quote_req);
            //var_dump($json);

            //echo "<br>";

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

            // Returns the data/output as a string instead of raw data
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //Set your auth headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $client_auth_code
            ));

            $data = curl_exec($ch);

            $jsonData = json_decode($data, true);
            curl_close($ch);

            //var_dump($jsonData);
            //array(6) { ["status"]=> string(6) "ACCEPT" ["uber_quote_uuid"]=> string(36) "36d6cd29-4274-4021-9a86-f5dd750fbf1b" ["price"]=> array(2) { ["amount"]=> int(40700) ["currency_code"]=> string(3) "USD" } ["expiration_time_utc"]=> string(10) "1588423434" ["notes"]=> string(0) "" ["uber_quote_id"]=> string(10) "1470517115" } 
            //$jsonData = '{ ["status"]=> string(6) "ACCEPT" ["uber_quote_uuid"]=> string(36) "36d6cd29-4274-4021-9a86-f5dd750fbf1b" ["price"]=> array(2) { ["amount"]=> int(40700) ["currency_code"]=> string(3) "USD" } ["expiration_time_utc"]=> string(10) "1588423434" ["notes"]=> string(0) "" ["uber_quote_id"]=> string(10) "1470517115" }';

            if (isset($jsonData["code"])) {
                echo "<font color=red>Error: " . $jsonData["code"] . " " . $jsonData["message"] . " </font><br>";
                //print_r($quote_req);
                $ifany_err = "yes";
                break;
            } else {
                // $dateInLocal = date("m/d/Y H:i:s", gmdate(intval($jsonData["expiration_time_utc"], 10)));
                $expirationTimeUtc = intval($jsonData["expiration_time_utc"], 10);
                $dateInLocal = date("m/d/Y H:i:s", $expirationTimeUtc);

                $quote_amount = $jsonData["price"]["amount"] / 100;
                $quote_amount_tot = $quote_amount_tot + $quote_amount;

                $freight_str .=  "<br>Response from Uber Freight " .
                    "<br>Quote ID: " . $jsonData["uber_quote_id"] .
                    "<br>Start Date: " . $date->format('m/d/Y') .
                    "<br>End Date: " . $date2->format('m/d/Y') .
                    "<br>Quote status: " . $jsonData["status"] .
                    "<br><b>Quote amount: $" . number_format($quote_amount, 2) . "</b>" .
                    "<br>Expiration Time: " . $dateInLocal .
                    "<br>Notes : " . $jsonData["notes"] .
                    "<br>";

                $res_ins_qry = "Insert into quoting_uber_freight_data (company_id, cart_item_id, box_id, box_inv_id, pickup_zip, dropoff_zip, box_weight, uber_quote_id, uber_quote_status,
				uber_quote_amount, uber_quote_exp_time, uber_quote_uuid, uber_quote_note, pickup_comp_id, pickup_nm, pickup_add1, pickup_add2, pickup_city, pickup_state, pickup_starttime,
				pickup_endtime, dropoff_nm, dropoff_add1, dropoff_add2, dropoff_city, dropoff_state, dropoff_starttime, dropoff_endtime, 
				transit_time, process_date_time, posted_on_sec_bubble, trans_rec_id ) select '" . $compid . "',
				'" . $cart_itemID . "', '" . $box_id . "', '" . $box_inv_id . "', '" . $pickup_zip . "', '" . $dropoff_zip . "', '" . $box_weight . "', '" . $jsonData["uber_quote_id"] . "', '" . $jsonData["status"] . "',
				'" . $quote_amount . "', '" . $jsonData["expiration_time_utc"] . "', '" . $jsonData["uber_quote_uuid"] . "', '" . $jsonData["notes"] . "', 
				'" . $vendor_b2b_rescue_b2bid . "', '" . str_replace("'", "\'", $pickup_nm) . "', '" . str_replace("'", "\'", $pickup_add1) . "', '" . str_replace("'", "\'", $pickup_add2) . "', '" . str_replace("'", "\'", $pickup_city) . "', '" . str_replace("'", "\'", $pickup_state) . "', '" . $starttime . "',
				'" . $endtime . "', '" . str_replace("'", "\'", $dropoff_nm) . "', '" . str_replace("'", "\'", $dropoff_add1) . "', '" . str_replace("'", "\'", $dropoff_add2) . "', '" . str_replace("'", "\'", $dropoff_city) . "', '" . str_replace("'", "\'", $dropoff_state) . "',
				'" . $starttime_2 . "', '" . $endtime_2 . "', '" . $transit_time . "', '" . date("Y-m-d H:i:s") . "', 1, '" . $_REQUEST["uber_freight_rec_id"] . "'";

                //echo $res_ins_qry . "<br>";
                db_b2b();
                db_query($res_ins_qry);
            }
        }

        if ($ifany_err == "") {
            //$avg_quote_amount_tot = number_format(($quote_amount_tot/7)*1.01,2);
            $avg_quote_amount_tot = number_format($quote_amount_tot * 1.01, 2);

    ?>

    <!-- <table style="width: 540px" cellspacing="1" cellpadding="1" border="0">
					<tr bgcolor="#e4e4e4">
						<td colspan="2">&nbsp;</td>
					</tr>	
					<tr bgcolor="#e4e4e4">
						<td class="style1" height="13" align="left">
							Uber Freight Quoted Amount :
						</td>
						<td class="style1" height="13" align="left">
							$<b><?php echo $avg_quote_amount_tot; ?></b>
							<?php

                            echo "<div id='quote_amount' style='display:none;'>" . $avg_quote_amount_tot . "</div> " .
                                "<br><br><div onclick='showquote()' ><u>Click here to see details of Quote</u></div> " .
                                "<div id='uberfreightquote' style='display:none;'>Quote for '" . $box_description . "' <br> Pickup :" .
                                $pickup_nm . " " . $pickup_add1 . " " . $pickup_add2 . " " . $pickup_city . "," . $pickup_state . " " .    $pickup_zip . " <br>" .
                                "<br>Drop off :" .
                                $dropoff_nm . " " . $dropoff_add1 . " " . $dropoff_add2 . " " . $dropoff_city . "," . $dropoff_state . " " . $dropoff_zip . " <br>" .
                                "<br>Box weight (in Lb): " . $box_weight .
                                $freight_str . "</div>";
                            ?>
						</td>
					</tr>			
			</table>  -->
    <br><br>

    <?php

        }
        //
    }
    db();
    $box_id = "";
    $strQuery = "Select box_id from loop_salesorders where trans_rec_id = ? ";
    $dt_view_res = db_query($strQuery, array("i"), array($_REQUEST["uber_freight_rec_id"]));
    while ($rs_row = array_shift($dt_view_res)) {
        $box_id = $box_id . $rs_row["box_id"] . ",";
    }
    if ($box_id != "") {
        $box_id = substr($box_id, 0, strlen($box_id) - 1);
    }
    ?>
    <table cellSpacing="1" cellPadding="1" border="0" style="width: 500px">
        <tr bgColor="#e4e4e4">
            <td id="uberfreighttopheading" bgColor="#fb8a8a" colSpan="2" align="center">
                <font size=1>Response from Uber Freight</font>
            </td>
        </tr>

        <table id="tbluberfreightoffer" cellSpacing="1" cellPadding="1" border="0" style="width: 500px">
            <?php

            db_b2b();
            if ($box_id != "") {
                $strQuery = "Select * from quoting_uber_freight_data where quoting_uber_freight_data.company_id = " . $_REQUEST["uber_freight_ID"] . " and box_id in (" . $box_id . ")
					union Select * from quoting_uber_freight_data where company_id = " . $_REQUEST["uber_freight_ID"] . " and posted_on_sec_bubble = 1 and trans_rec_id = " . $_REQUEST["uber_freight_rec_id"]  . " order by pickup_starttime asc";
            } else {
                $strQuery = "Select * from quoting_uber_freight_data where company_id = " . $_REQUEST["uber_freight_ID"] . " and posted_on_sec_bubble = 1 and trans_rec_id = " . $_REQUEST["uber_freight_rec_id"]  . " order by pickup_starttime asc";
            }

            $uber_load_id_flg = "no";
            $dt_view_res = db_query($strQuery);
            while ($myrowsel_b2b = array_shift($dt_view_res)) {
                $uber_quote_uuid = $myrowsel_b2b["uber_quote_uuid"];
                $uber_quote_id = $myrowsel_b2b["uber_quote_id"];

                if ($myrowsel_b2b["uber_load_id"] != "") {
                    $uber_load_id_flg = "yes";
                    $bg_color = "#b6ddbf";
                } else {
                    $bg_color = "#e4e4e4";
                }
            ?>
            <tr bgColor="#e4e4e4">
                <td colspan="2">&nbsp;</td>
            </tr>

            <tr bgColor="<?php echo $bg_color; ?>">
                <td height="13" class="style1" align="left" width="100px">
                    <?php if ($myrowsel_b2b["posted_on_sec_bubble"] == 1) { ?>
                    Quoted Amount:
                    <?php } else { ?>
                    Quoted Amount:
                    <?php } ?>
                </td>
                <td height="13" class="style1" align="left">
                    $<b><?php echo number_format($myrowsel_b2b["uber_quote_amount"], 0); ?></b>
                </td>
            </tr>

            <?php
                if ($myrowsel_b2b["uber_load_id"] != "") {
                ?>
            <tr bgColor="<?php echo $bg_color; ?>">
                <td height="13" class="style1" align="left">
                    Tender details:
                </td>
                <td height="13" class="style1" align="left">
                    <?php
                            echo "<b>Uploaded on - " . date("m/d/Y H:i:s", strtotime($myrowsel_b2b["uber_load_uploaded_on"])) . " Uber_load_id - " . $myrowsel_b2b["uber_load_id"] . "</b>";
                            ?>
                </td>
            </tr>
            <?php

                }

                ?>

            <tr bgColor="<?php echo $bg_color; ?>">
                <td height="13" class="style1" align="left">
                    Pick Up Date:
                </td>
                <td height="13" class="style1" align="left">
                    <?php
                        // . " - " . date("m/d/Y H:i:s" , gmdate($myrowsel_b2b["pickup_endtime"]))
                        echo date("m/d/Y", strtotime($myrowsel_b2b["pickup_starttime"])) . " (" . date("l", strtotime($myrowsel_b2b["pickup_starttime"])) . ")";
                        ?>
                </td>
            </tr>

            <tr bgColor="<?php echo $bg_color; ?>">
                <td height="13" class="style1" align="left">
                    Drop off Date:
                </td>
                <td height="13" class="style1" align="left">
                    <?php //echo date("m/d/Y H:i:s" , gmdate($myrowsel_b2b["dropoff_starttime"])) . " - " . date("m/d/Y H:i:s" , gmdate($myrowsel_b2b["dropoff_endtime"])); 
                        echo date("m/d/Y", strtotime($myrowsel_b2b["dropoff_starttime"])) . " (" . date("l", strtotime($myrowsel_b2b["dropoff_starttime"])) . ")";
                        ?>
                </td>
            </tr>

            <tr bgColor="<?php echo $bg_color; ?>">
                <td height="13" class="style1" align="left">
                    Quoted ID:
                </td>
                <td height="13" class="style1" align="left">
                    <?php echo $myrowsel_b2b["uber_quote_id"]; ?>
                </td>
            </tr>
            <tr bgColor="<?php echo $bg_color; ?>">
                <td height="13" class="style1" align="left">
                    Quoted Status:
                </td>
                <td height="13" class="style1" align="left">
                    <?php
                        $hour1 = 0;
                        $hour2 = 0;
                        if ($myrowsel_b2b["process_date_time"] != "") {
                            $date1 = $myrowsel_b2b["process_date_time"];
                            $datetimeObj1 = new DateTime($date1);
                            $datetimeObj2 = new DateTime();
                            $interval = $datetimeObj1->diff($datetimeObj2);

                            if ($interval->format('%a') > 0) {
                                $hour1 = intval($interval->format('%a')) * 24;
                            }
                            if ($interval->format('%h') > 0) {
                                $hour2 = $interval->format('%h');
                            }
                        }

                        if (
                            trim($myrowsel_b2b["uber_quote_status"]) == "ACCEPT" && (($hour1 + $hour2) < 24) && ($myrowsel_b2b["uber_load_id"] == "")
                            && ($uber_load_id_flg == "no")
                        ) {
                        ?>
                    <b>QUOTED<?php //echo $myrowsel_b2b["uber_quote_status"]; 
                                        ?></b>
                    <input type="button" name="btn_uber_tender" id="btn_uber_tender" value="Tender"
                        onclick="uber_tender(<?php echo $myrowsel_b2b["unqid"]; ?>)" />
                    <?php } else {
                            if ($myrowsel_b2b["uber_load_id"] != "") {
                            ?>
                    <b>Tendered<?php echo " " . date("m/d/Y H:i:s", strtotime($myrowsel_b2b["uber_load_uploaded_on"])); ?></b>
                    <?php

                            } else {

                            ?>
                    <b>Quote Expired<?php //echo $myrowsel_b2b["uber_quote_status"]; 
                                                ?></b>
                    <?php         }
                        } ?>
                </td>
            </tr>

            <tr bgColor="<?php echo $bg_color; ?>">
                <td height="13" class="style1" align="left">
                    Quoted Date/Time:
                </td>
                <td height="13" class="style1" align="left">
                    <?php echo date("m/d/Y H:i:s", strtotime($myrowsel_b2b["process_date_time"])); ?>
                </td>
            </tr>

            <?php

            }

            db();
            ?>
        </table>

    </table>

</body>

</html>