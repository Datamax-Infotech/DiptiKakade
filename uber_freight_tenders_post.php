<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


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

//$url = 'https://sandbox-api.uber.com/v1/freight/loads/tenders';
$url = 'https://api.uber.com/v1/freight/loads/tenders';

//$cart_itemID = 84596;
//$compid = 81689;
//$quote_unqid = 1827;

$quote_unqid = $_REQUEST["unqid"];
$cart_itemID = 0;
$compid = 0;

$trans_rec_id = $_REQUEST["uber_freight_rec_id"];

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
$uber_quote_uuid = "";
$uber_quote_id = "";
$dropoff_city = "";
$dropoff_state = "";
$dropoff_zip = "";
$box_description = "";
$uber_quote_amount = 0;
$pickup_starttime = "";
$pickup_endtime = "";
$dropoff_starttime = "";
$dropoff_endtime = "";
$quantity_per_pallet = 0;

db_b2b();

$dt_view_res_data = db_query("Select * from quoting_uber_freight_data where unqid = ? ", array("i"), array($quote_unqid));
while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
    $compid = $myrowsel_b2b["company_id"];
    $cart_itemID = $myrowsel_b2b["cart_item_id"];

    $uber_quote_uuid = $myrowsel_b2b["uber_quote_uuid"];
    $uber_quote_id = $myrowsel_b2b["uber_quote_id"];

    $pickup_nm = $myrowsel_b2b["pickup_nm"];
    $pickup_add1 = $myrowsel_b2b["pickup_add1"];
    $pickup_add2 = $myrowsel_b2b["pickup_add2"];
    $pickup_city = $myrowsel_b2b["pickup_city"];
    $pickup_state = $myrowsel_b2b["pickup_state"];
    $pickup_zip = $myrowsel_b2b["pickup_zip"];
    $pickup_starttime = $myrowsel_b2b["pickup_starttime"];
    $pickup_endtime = $myrowsel_b2b["pickup_endtime"];
    $dropoff_nm = $myrowsel_b2b["dropoff_nm"];
    $dropoff_add1 = $myrowsel_b2b["dropoff_add1"];
    $dropoff_add2 = $myrowsel_b2b["dropoff_add2"];
    $dropoff_city = $myrowsel_b2b["dropoff_city"];
    $dropoff_state = $myrowsel_b2b["dropoff_state"];
    $dropoff_zip = $myrowsel_b2b["dropoff_zip"];
    $dropoff_starttime = $myrowsel_b2b["dropoff_starttime"];
    $dropoff_endtime = $myrowsel_b2b["dropoff_endtime"];
    $box_weight = floatval($myrowsel_b2b["box_weight"]);
    $uber_quote_amount = $myrowsel_b2b["uber_quote_amount"];
    $vendor_b2b_rescue_b2bid = $myrowsel_b2b["pickup_comp_id"];
}

$shipping_receiving_hours_txt = "";
if ($vendor_b2b_rescue_b2bid > 0) {
    $get_wh = "SELECT shipping_receiving_hours FROM companyInfo WHERE loopid = '" . $vendor_b2b_rescue_b2bid . "'";
    db_b2b();
    $get_wh_res = db_query($get_wh);
    while ($the_wh = array_shift($get_wh_res)) {
        if ($the_wh["shipping_receiving_hours"] != "") {
            $shipping_receiving_hours_txt = "Shipper Hours/Notes: " . $the_wh["shipping_receiving_hours"];
        }
    }
}

if ($compid > 0) {

    $get_wh = "SELECT shipping_receiving_hours FROM companyInfo WHERE ID = '" . $compid . "'";
    db_b2b();
    $get_wh_res = db_query($get_wh);
    while ($the_wh = array_shift($get_wh_res)) {
        if ($the_wh["shipping_receiving_hours"] != "") {
            $shipping_receiving_hours_txt .= " Delivery Hours/Notes: " . $the_wh["shipping_receiving_hours"];
        }
    }
}


$cart_item_qry = "Select * from boxes Where ID = " . $cart_itemID;
db_b2b();
$dt_view_res = db_query($cart_item_qry);
while ($cart_item_row = array_shift($dt_view_res)) {
    if ($cart_item_row["inventoryID"] > 0) {
        $sql_data = "Select * from inventory Where ID = " . $cart_item_row["inventoryID"];
    } else {
        $sql_data = "Select * from inventory Where ID = " . $cart_item_row["box_id"];
    }

    db_b2b();
    $dt_view_res_data = db_query($sql_data);
    while ($myrowsel_b2b = array_shift($dt_view_res_data)) {
        $quantity_per_pallet = $myrowsel_b2b["quantity_per_pallet"];
        $vendor_b2b_rescue = $myrowsel_b2b["vendor_b2b_rescue"];
        $box_description = $myrowsel_b2b["description"];
    }
}

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
    //$transit_time = floor($distance/700);
    $transit_time = ceil($distance / 700);

    //echo $transit_time . "<br>";
}

//$quote_req = array('quote_id'=>'UCB_TEST_QUOTE_1','customer_id'=>'USEDCARDBOARDBOXES','requirements'=> array('vehicle_type'=> 'DRY','weight'=>array('amount'=> 4300.0,'unit'=> 'LB')));

$freight_str = "";
$quote_amount_tot = 0;
$ifany_err = "";

for ($tmpcnt = 1; $tmpcnt <= 1; $tmpcnt++) {

    $vendor_b2b_rescue_b2bid = strval($vendor_b2b_rescue_b2bid);
    $compid = strval($compid);

    $starttime = floatval($pickup_starttime);
    $endtime = floatval($pickup_endtime);
    $starttime_2 = floatval($dropoff_starttime);
    $endtime_2 = floatval($dropoff_endtime);

    $trans_rec_id = strval($trans_rec_id);

    $items_array = array(array(
        'purchase_order_id' => $trans_rec_id, 'package_count' => array('count' => floatval($quantity_per_pallet), 'type' => 'PALLET'),
        'weight' => array('amount' => $box_weight, 'unit' => 'LB'), 'name' => $box_description
    ));

    $stoparr = array(
        array(
            'sequence_number' => 1, 'type' => 'PICKUP', 'mode' => 'LIVE', 'facility' => array(
                'facility_id' => $vendor_b2b_rescue_b2bid, 'name' => $pickup_nm,
                'address' => array('line1' => $pickup_add1, 'line2' => $pickup_add2, 'city' => $pickup_city,  'principal_subdivision' => $pickup_state, 'postal_code' => $pickup_zip, 'country' => 'USA')
            ),
            'appointment' => array('status' => 'NEEDED', 'start_time_utc' => $starttime, 'end_time_utc' => $endtime), 'items' => $items_array
        ),
        array(
            'sequence_number' => 2, 'type' => 'DROPOFF', 'mode' => 'LIVE',
            'facility' => array(
                'facility_id' => $compid, 'name' => $dropoff_nm,
                'address' => array('line1' => $dropoff_add1, 'line2' => $dropoff_add2, 'city' => $dropoff_city,  'principal_subdivision' => $dropoff_state, 'postal_code' => $dropoff_zip, 'country' => 'USA')
            ),
            'notes' => $shipping_receiving_hours_txt,
            'appointment' => array('status' => 'NEEDED', 'start_time_utc' => $starttime_2, 'end_time_utc' => $endtime_2), 'items' => $items_array
        )
    );

    //'suggested_price' => array('amount'=> $box_weight,'currency_code'=> 'USD'),
    //USEDCARDBOARDBOXES
    $quote_req = array(
        'type' => 'REGULAR', 'load_id' => $trans_rec_id, 'customer_id' => 'USEDCARDBOARDBOXES',
        'uber_quote_uuid' => $uber_quote_uuid, 'uber_quote_id' => $uber_quote_id,
        'requirements' => array('vehicle_type' => 'DRY', 'weight' => array('amount' => $box_weight, 'unit' => 'LB')),
        'stops' => $stoparr
    );

    //LHR_ONLY

    $ch = curl_init();
    //print_r($quote_req);

    $json = json_encode($quote_req);
    //var_dump($json);
    //echo "<br>";
    //exit;

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

    //array(2) { ["uber_load_id"]=> string(10) "3127864131" ["uber_load_uuid"]=> string(36) "0ccf1535-1653-40b3-b364-16f0f9d0d05e" } 		

    if (isset($jsonData["code"])) {
        echo "<font color=red>Error: " . $jsonData["code"] . " " . $jsonData["message"] . " </font><br>";
        print_r($quote_req);
        $ifany_err = "yes";
        break;
    } else {

        $freight_str .=  "Order has been Tender, response from Uber Freight " .
            "  Uber Load ID: " . $jsonData["uber_load_id"] .
            "  Uber Load UUID: " . $jsonData["uber_load_uuid"] .
            " ";

        echo $freight_str;

        $res_ins_qry = "Update quoting_uber_freight_data set trans_rec_id= '" . $_REQUEST["uber_freight_rec_id"] . "', uber_load_id = '" . $jsonData["uber_load_id"] . "' , uber_load_uuid = '" . $jsonData["uber_load_uuid"] . "', uber_load_uploaded_on = '" . date("Y-m-d H:i:s") . "' where unqid = " . $quote_unqid;            //echo $res_ins_qry . "<br>";
        db_b2b();
        db_query($res_ins_qry);

        $uber_str = "https://www.uber.com/freight/platform/share/" . $jsonData["uber_load_uuid"];

        //$uber_str = str_replace("'", "\'" , $uber_str);

        db();
        $rec_found = "no";
        $strQuery = "Select trans_rec_id from loop_transaction_buyer_freightview where trans_rec_id = ? ";
        $dt_view_res = db_query($strQuery, array("i"), array($_REQUEST["uber_freight_rec_id"]));
        while ($rs_row = array_shift($dt_view_res)) {
            $rec_found = "yes";
        }

        if ($rec_found == "no") {
            $qry = "Insert into loop_transaction_buyer_freightview SET trans_rec_id = " . $_REQUEST["uber_freight_rec_id"] . ", broker_id = 1711, booked_delivery_cost = '" . $uber_quote_amount . "', link = '" . $uber_str . "' , employeeid = " . $_COOKIE["employeeid"];
        } else {
            $qry = "Update loop_transaction_buyer_freightview SET broker_id = 1711, booked_delivery_cost = '" . $uber_quote_amount . "', link = '" . $uber_str . "' , employeeid = " . $_COOKIE["employeeid"] . " where trans_rec_id = '" . $_REQUEST["uber_freight_rec_id"] . "'";
        }
        $result2 = db_query($qry);
    }
}
//