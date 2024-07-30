<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

// function encrypt_password($txt){
// 	$key = "1sw54@$sa$offj";
// 	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
// 	$iv = openssl_random_pseudo_bytes($ivlen);
// 	$ciphertext_raw = openssl_encrypt($txt, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
// 	$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
// 	$ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
// 	return $ciphertext;
// }
$box_vents = $box_vents ?? "";
$type = $type ?? "";
$box_grade = $box_grade ?? "";
$box_shape = $box_shape ?? "";
$box_uniformity = $box_uniformity ?? "";
$enter_zipcode = "";
$final_filter_str = "";
$loop_boxes_final_filter_str = "";
if (isset($_REQUEST["all_type_data"]) && isset($_REQUEST["all_type_data"]) != "") {
	$type_val = $_REQUEST["all_type_data"];
	if (in_array('1', $type_val)) {
		$type = " AND (inventory.box_sub_type = 1";
	}
	if (in_array('2', $type_val)) {
		if ($type == "") {
			$type = " AND (inventory.box_sub_type = 4";
		} else {
			$type .= " OR inventory.box_sub_type = 4";
		}
	}
	if (in_array('3', $type_val)) {
		if ($type == "") {
			$type = " AND (inventory.box_sub_type = 5";
		} else {
			$type .= " OR inventory.box_sub_type = 5";
		}
	}
	if (in_array('4', $type_val)) {
		if ($type == "") {
			$type = " AND (inventory.box_sub_type = 6";
		} else {
			$type .= " OR inventory.box_sub_type = 6";
		}
	}
	$final_filter_str .= $type . ")";
}

if (isset($_REQUEST["all_uniformity_data"]) && isset($_REQUEST["all_uniformity_data"]) != "") {
	$filter_cnt = 0;
	$box_uniformity_val = $_REQUEST["all_uniformity_data"];
	if (in_array('1', $box_uniformity_val)) {
		$box_uniformity = " AND (inventory.uniform_mixed_load = 'Uniform'";
		$filter_cnt = 1;
	}
	if (in_array('2', $box_uniformity_val)) {
		if ($filter_cnt == 1) {
			$box_uniformity .= " OR inventory.uniform_mixed_load = 'Mixed'";
		} else {
			$box_uniformity .= " AND ( inventory.uniform_mixed_load = 'Mixed'";
		}
	}
	$final_filter_str .= $box_uniformity . ")";
}

if (isset($_REQUEST["all_shape_data"]) && isset($_REQUEST["all_shape_data"]) != "") {
	$box_shape_val = $_REQUEST["all_shape_data"];
	if (in_array('1', $box_shape_val)) {
		$box_shape = " AND (inventory.shape_rect = 1";
	}
	if (in_array('2', $box_shape_val)) {
		if ($box_shape == "") {
			$box_shape = " AND (inventory.shape_oct = 1";
		} else {
			$box_shape .= " OR inventory.shape_oct = 1";
		}
	}
	$final_filter_str .= $box_shape . ")";
}

$sql_wall = "";
if (isset($_REQUEST["all_thickness_data"]) && $_REQUEST["all_thickness_data"] != "") {
	$wall_val = $_REQUEST["all_thickness_data"];
	for ($i = 0; $i < count($wall_val); $i++) {
		$wall = $wall_val[$i];
		if ($sql_wall == "") {
			if ($wall == 1) {
				$sql_wall = " AND ((inventory.bwall >= 1 and inventory.bwall <= 2) ";
			} else if ($wall == 2) {
				$sql_wall = " AND ((inventory.bwall >= 3 and inventory.bwall <= 4) ";
			} else if ($wall == 3) {
				$sql_wall = " AND ((inventory.bwall >= 5) ";
			}
			if (count($wall_val) == 1) {
				$sql_wall = $sql_wall . ") ";
			}
		} else {
			if ($wall == 1) {
				$sql_wall .= " OR (inventory.bwall >= 1 and inventory.bwall <= 2) ";
			} else if ($wall == 2) {
				$sql_wall .= " OR (inventory.bwall >= 3 and inventory.bwall <= 4) ";
			} else if ($wall == 3) {
				$sql_wall .= " OR (inventory.bwall >= 5) ";
			}

			//$sql_wall = $sql_wall . " OR inventory.bwall = ". $wall;
		}
	}
	if ($sql_wall != "" && count($wall_val) > 1) {
		$sql_wall = $sql_wall . ") ";
	}
	$final_filter_str .= $sql_wall;
}

if (isset($_REQUEST["all_top_data"]) && $_REQUEST["all_top_data"] != "") {
	$top_config = "";
	$top_config_val = $_REQUEST["all_top_data"];
	$top_config_val_3 = "";
	$top_config_val_4 = "";
	if (in_array('3', $top_config_val)) {
		$top_config_val_3 = "yes";
	}
	if (in_array('4', $top_config_val)) {
		$top_config_val_4 = "yes";
	}

	if (in_array('1', $top_config_val)) {
		if ($top_config_val_3 == "yes" || $top_config_val_4 == "yes") {
			$top_config = " AND (inventory.top_nolid = 1";
		} else {
			$top_config = " AND inventory.top_nolid = 1";
		}
	}

	if (in_array('2', $top_config_val)) {
		if ($top_config == "") {
			if ($top_config_val_3 == "yes" || $top_config_val_4 == "yes") {
				$top_config = " AND (inventory.top_nolid = 1";
			} else {
				$top_config = " AND inventory.top_nolid = 1";
			}
		}
	}

	if (in_array('3', $top_config_val)) {
		if ($top_config == "") {
			if (in_array('4', $top_config_val)) {
				$top_config = " AND ( inventory.top_partial = 1";
			} else {
				$top_config = " AND inventory.top_partial = 1";
			}
		} else {
			if (in_array('4', $top_config_val)) {
				$top_config = $top_config . " OR inventory.top_partial = 1";
			} else {
				$top_config = $top_config . " OR inventory.top_partial = 1) ";
			}
		}
	}

	if (in_array('4', $top_config_val)) {
		if ($top_config == "") {
			$top_config = " AND inventory.top_full = 1";
		} else {
			$top_config = $top_config . " OR inventory.top_full = 1) ";
		}
	}
	$final_filter_str .= $top_config;
}

if (isset($_REQUEST["all_bottom_data"]) && $_REQUEST["all_bottom_data"] != "") {
	$bottom_config = "";
	$bottom_config_val = $_REQUEST["all_bottom_data"];

	if (in_array('1', $bottom_config_val)) {
		if (in_array('2', $bottom_config_val) || in_array('3', $bottom_config_val) || in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
			$bottom_config = " AND ( inventory.bottom_no = 1";
		} else {
			$bottom_config = " AND inventory.bottom_no = 1";
		}
	}

	if (in_array('2', $bottom_config_val)) {
		if ($bottom_config == "") {
			if (in_array('3', $bottom_config_val) || in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
				$bottom_config = " AND (inventory.bottom_partial = 1";
			} else {
				$bottom_config = " AND inventory.bottom_partial = 1";
			}
		} else {
			if (in_array('3', $bottom_config_val) || in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
				$bottom_config = $bottom_config . " OR inventory.bottom_partial = 1 ";
			} else {
				$bottom_config = $bottom_config . " OR inventory.bottom_partial = 1) ";
			}
		}
	}

	if (in_array('3', $bottom_config_val)) {
		if ($bottom_config == "") {
			if (in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
				$bottom_config = " AND (inventory.bottom_tray = 1";
			} else {
				$bottom_config = " AND inventory.bottom_tray = 1";
			}
		} else {
			if (in_array('4', $bottom_config_val) || in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
				$bottom_config = $bottom_config . " OR inventory.bottom_tray = 1";
			} else {
				$bottom_config = $bottom_config . " OR inventory.bottom_tray = 1) ";
			}
		}
	}

	if (in_array('4', $bottom_config_val)) {
		if ($bottom_config == "") {
			if (in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
				$bottom_config = " AND (inventory.bottom_fullflap = 1";
			} else {
				$bottom_config = " AND inventory.bottom_fullflap = 1";
			}
		} else {
			if (in_array('5', $bottom_config_val) || in_array('6', $bottom_config_val)) {
				$bottom_config = $bottom_config . " OR inventory.bottom_fullflap = 1";
			} else {
				$bottom_config = $bottom_config . " OR inventory.bottom_fullflap = 1) ";
			}
		}
	}
	if (in_array('5', $bottom_config_val)) {
		if ($bottom_config == "") {
			if (in_array('6', $bottom_config_val)) {
				$bottom_config = " AND (inventory.bottom_partialsheet = 1";
			} else {
				$bottom_config = " AND inventory.bottom_partialsheet = 1";
			}
		} else {
			if (in_array('6', $bottom_config_val)) {
				$bottom_config = $bottom_config . " OR inventory.bottom_partialsheet = 1 ";
			} else {
				$bottom_config = $bottom_config . " OR inventory.bottom_partialsheet = 1) ";
			}
		}
	}

	if (in_array('6', $bottom_config_val)) {
		if ($bottom_config == "") {
			$bottom_config = " AND inventory.bottom_flat = 1";
		} else {
			$bottom_config = $bottom_config . " OR inventory.bottom_flat = 1)";
		}
	}
	$final_filter_str .= $bottom_config;
}

if (isset($_REQUEST["all_vents_data"]) && isset($_REQUEST["all_vents_data"]) != "") {
	$box_vents_val = $_REQUEST["all_vents_data"];
	if (in_array('1', $box_vents_val)) {
		$box_vents = " AND (inventory.vents_yes = 1";
	}
	if (in_array('2', $box_vents_val)) {
		if ($box_vents == "") {
			$box_vents = " AND (inventory.vents_no = 1";
		} else {
			$box_vents .= " OR inventory.vents_no = 1";
		}
	}
	$final_filter_str .= $box_vents . ")";
}

if (isset($_REQUEST["all_grade_data"]) && isset($_REQUEST["all_grade_data"]) != "") {
	$box_grade_val = $_REQUEST["all_grade_data"];
	if (in_array('1', $box_grade_val)) {
		$box_grade = " AND (inventory.grade = 'A'";
	}
	if (in_array('2', $box_grade_val)) {
		if ($box_grade == "") {
			$box_grade = " AND (inventory.grade = 'B'";
		} else {
			$box_grade .= " OR inventory.grade = 'B'";
		}
	}
	if (in_array('3', $box_grade_val)) {
		if ($box_grade == "") {
			$box_grade = " AND (inventory.grade = 'C'";
		} else {
			$box_grade .= " OR inventory.grade = 'C'";
		}
	}
	$final_filter_str .= $box_grade . ")";
}

if (isset($_REQUEST["min_price_each"]) || isset($_REQUEST['max_price_each'])) {
	if ($_REQUEST["min_price_each"] != 0.00 || $_REQUEST["max_price_each"] != 99.99) {
		$final_filter_str .= " AND ((ulineDollar + if(ulineCents > 0, ulineCents, 0)) >= " . $_REQUEST["min_price_each"] . " 
			and (ulineDollar + if(ulineCents > 0, ulineCents, 0)) <= " . $_REQUEST["max_price_each"] . ")";
	}
}

if (isset($_REQUEST["min_length"]) || isset($_REQUEST['max_length'])) {
	if ($_REQUEST["min_length"] != 0 || $_REQUEST["max_length"] != 99) {
		$final_filter_str .= " AND ((lengthInch + if(lengthFraction > 0, CAST(SUBSTRING_INDEX(lengthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(lengthFraction, '/', -1) AS DECIMAL(10,2)), 0)) >= " . $_REQUEST["min_length"] . " 
			and (lengthInch + if(lengthFraction > 0, CAST(SUBSTRING_INDEX(lengthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(lengthFraction, '/', -1) AS DECIMAL(10,2)), 0)) <= " . $_REQUEST["max_length"] . ")";
	}
}

if (isset($_REQUEST["min_width"]) || isset($_REQUEST['max_width'])) {
	if ($_REQUEST["min_width"] != 0 || $_REQUEST["max_width"] != 99) {
		$final_filter_str .= " AND ((widthInch + if(widthFraction > 0, CAST(SUBSTRING_INDEX(widthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(widthFraction, '/', -1) AS DECIMAL(10,2)), 0)) >= " . $_REQUEST["min_width"] . " 
			and (widthInch + if(widthFraction > 0, CAST(SUBSTRING_INDEX(widthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(widthFraction, '/', -1) AS DECIMAL(10,2)), 0)) <= " . $_REQUEST["max_width"] . ")";
	}
}

if (isset($_REQUEST["min_height"]) || isset($_REQUEST['max_height'])) {
	if ($_REQUEST["min_height"] != 0 || $_REQUEST["max_height"] != 99) {
		//$final_filter_str.= " AND (CONVERT(SUBSTRING_INDEX(LWH, 'x', -1), UNSIGNED INTEGER) >= ". $_REQUEST["min_height"] ." AND CONVERT(SUBSTRING_INDEX(LWH, 'x', -1), UNSIGNED INTEGER) <= ".$_REQUEST["max_height"]." )" ;

		$final_filter_str .= " AND ((depthInch + if(depthFraction > 0, CAST(SUBSTRING_INDEX(depthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(depthFraction, '/', -1) AS DECIMAL(10,2)), 0)) >= " . $_REQUEST["min_height"] . " 
			and (depthInch + if(depthFraction > 0, CAST(SUBSTRING_INDEX(depthFraction, '/', 1) AS DECIMAL(10,2)) / CAST(SUBSTRING_INDEX(depthFraction, '/', -1) AS DECIMAL(10,2)), 0)) <= " . $_REQUEST["max_height"] . ")";
	}
}

if (isset($_REQUEST["min_cubic_footage"]) || isset($_REQUEST['max_cubic_footage'])) {
	if ($_REQUEST["min_cubic_footage"] != 0.00 || $_REQUEST["max_cubic_footage"] != 99.99) {
		$final_filter_str .= " AND cubicFeet >= " . $_REQUEST["min_cubic_footage"] . " AND cubicFeet <= " . $_REQUEST["max_cubic_footage"] . "";
	}
}

$dropoff_add1 = strval($_REQUEST["txtaddress"]);
$dropoff_add2 = strval($_REQUEST["txtaddress2"]);
$dropoff_city = strval($_REQUEST["txtcity"]);
$dropoff_state = strval($_REQUEST["txtstate"]);
$dropoff_country = $_REQUEST["txtcountry"];
if (strtolower($_REQUEST["txtcountry"]) == "usa") {
	$dropoff_zip = substr(strval($_REQUEST["txtzipcode"]), 0, 5);
} else {
	$dropoff_zip = $_REQUEST["txtzipcode"];
}

// if(isset($_REQUEST['vents']) && $_REQUEST['vents'] == 1){
// 	$final_filter_str.= " AND inventory.vents_yes=1";
// }
if (isset($_REQUEST['include_sold_out_items']) && $_REQUEST['include_sold_out_items'] == 1) {
	//$final_filter_str.= " AND inventory.quantity_available > 0";
}
if (isset($_REQUEST['include_presold_and_loops']) && $_REQUEST['include_presold_and_loops'] == 1) {
	// $final_filter_str.= " AND inventory.box_type IN('Gaylord','GaylordUCB', 'Loop','PresoldGaylord')";	
}


if (isset($_REQUEST['ltl_allowed']) && $_REQUEST['ltl_allowed'] == 1) {
	$final_filter_str .= " AND inventory.ship_ltl=1";
}
if (isset($_REQUEST['customer_pickup_allowed']) && $_REQUEST['customer_pickup_allowed'] == 1) {
	$final_filter_str .= " AND inventory.customer_pickup_allowed=1";
}
if (isset($_REQUEST['urgent_clearance']) && $_REQUEST['urgent_clearance'] == 1) {
	$final_filter_str .= " AND inventory.box_urgent=1";
}
if (isset($_REQUEST['ect_burst']) && $_REQUEST['ect_burst'] != "") {
	switch ($_REQUEST['ect_burst']) {
		case '1':
			// Light Duty (< 32 ECT or 200# Burst)
			$final_filter_str .= " AND ((inventory.ect_val < 32 AND inventory.burst = 'ECT') OR (inventory.burst_val < 200 AND inventory.burst = 'Burst'))";
			break;
		case '2':
			// Standard (>= 32 ECT or 200# Burst)
			$final_filter_str .= " AND ((inventory.ect_val >= 32 AND inventory.burst = 'ECT') OR (inventory.burst_val >= 200 AND inventory.burst = 'Burst'))";
			break;
		case '3':
			// Heavy Duty (>= 44 ECT or 275# Burst)
			$final_filter_str .= " AND ((inventory.ect_val >= 44 AND inventory.burst = 'ECT') OR (inventory.burst_val >= 275 AND inventory.burst = 'Burst'))";
			break;
		case '4':
			// Super Heavy Duty (>= 48 ECT or 275# Burst)
			$final_filter_str .= " AND ((inventory.ect_val >= 48 AND inventory.burst = 'ECT') OR (inventory.burst_val >= 275 AND inventory.burst = 'Burst'))";
			break;
	}
}

if (isset($_REQUEST["all_printing_data"]) && $_REQUEST["all_printing_data"] != "") {
	$printing_filter_cnt = 0;
	$printing_filter_or_val = "";
	$final_filter_str .= " and ( ";
	if (in_array('1', $_REQUEST["all_printing_data"])) {
		$final_filter_str .= " inventory.printing = 'Printing'";
		$printing_filter_cnt = 1;
	}
	if (in_array('2', $_REQUEST["all_printing_data"])) {
		if ($printing_filter_cnt == 1) {
			$printing_filter_or_val = " OR ";
		}
		$final_filter_str .= " $printing_filter_or_val inventory.printing = 'Plain'";
	}
	$final_filter_str .= " ) ";
}


if (isset($_REQUEST["all_work_as_a_kit_box_data"]) && $_REQUEST["all_work_as_a_kit_box_data"] != "") {
	$kit_filter_cnt = 0;
	$loop_boxes_final_filter_str .= " AND (";
	if (in_array('1', $_REQUEST["all_work_as_a_kit_box_data"])) {
		$loop_boxes_final_filter_str .= " work_as_kit_box = 'Medium'";
		$kit_filter_cnt = 1;
	}
	if (in_array('2', $_REQUEST["all_work_as_a_kit_box_data"])) {
		$kit_filter_or_val = " ";
		if ($kit_filter_cnt == 1) {
			$kit_filter_or_val = " OR ";
		}

		$loop_boxes_final_filter_str .= " $kit_filter_or_val work_as_kit_box = 'Large'";
		$kit_filter_cnt = 1;
	}
	if (in_array('3', $_REQUEST["all_work_as_a_kit_box_data"])) {
		$kit_filter_or_val = " ";
		if ($kit_filter_cnt == 1) {
			$kit_filter_or_val = " OR ";
		}
		$loop_boxes_final_filter_str .= " $kit_filter_or_val work_as_kit_box = 'Large (Fold at Seam)'";
		$kit_filter_cnt = 1;
	}
	if (in_array('4', $_REQUEST["all_work_as_a_kit_box_data"])) {
		$kit_filter_or_val = " ";
		if ($kit_filter_cnt == 1) {
			$kit_filter_or_val = " OR ";
		}
		$loop_boxes_final_filter_str .= " $kit_filter_or_val work_as_kit_box = 'X-Large'";
		$kit_filter_cnt = 1;
	}
	if (in_array('5', $_REQUEST["all_work_as_a_kit_box_data"])) {
		$kit_filter_or_val = " ";
		if ($kit_filter_cnt == 1) {
			$kit_filter_or_val = " OR ";
		}
		$loop_boxes_final_filter_str .= " $kit_filter_or_val work_as_kit_box = 'X-Large (Fold at Seam)'";
		$kit_filter_cnt = 1;
	}
	$loop_boxes_final_filter_str .= " ) ";
}

$box_status_filter = "";
$availalesort = "";
$availale_selectval = "";
if (isset($_REQUEST['available'])) {
	$availale_selectval = $_REQUEST['available'];
	switch ($_REQUEST['available']) {
		case 'quantities':
			$availalesort = " ,`inventory`.`quantity_available`";
			break;
		case "actual";
			$availalesort = " ,`inventory`.`actual_inventory`";
			break;
		case "frequency";
			$availalesort = " ,`inventory`.`expected_loads_per_mo`";
			break;
	}
}

$sql_fil = "";

if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Gaylord") == 0) {
	if (isset($_REQUEST['box_subtype']) &&  (strcasecmp($_REQUEST["box_subtype"], "all") != 0	 && $_REQUEST['box_subtype'] != "")) {
		$box_subtype = $box_subtype ?? "";
		$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";
	} else {
		if (isset($_REQUEST['all_include_presold_and_loops_data'])) {
			$all_include_presold_and_loops_data = implode("','", $_REQUEST['all_include_presold_and_loops_data']);
			$sql_fil .= " AND inventory.box_type IN ('$all_include_presold_and_loops_data')";
		} else {
			$sql_fil .= " AND inventory.box_type IN('Gaylord','GaylordUCB', 'Loop','PresoldGaylord')";
		}
	}
} else if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Pallets") == 0) {
	if (isset($_REQUEST['box_subtype']) && (strcasecmp($_REQUEST["box_subtype"], "all") == -1 && $_REQUEST['box_subtype'] != "")) {
		$box_subtype = $box_subtype ?? "";
		$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";
	} else {
		$sql_fil .= " AND inventory.box_type IN ('PalletsUCB','PalletsnonUCB')";
	}
} else if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Shipping") == 0) {
	if (isset($_REQUEST['box_subtype']) && (strcasecmp($_REQUEST["box_subtype"], "all") == -1 && $_REQUEST['box_subtype'] != "")) {
		$box_subtype = $box_subtype ?? "";
		$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";
	} else {
		if (isset($_REQUEST['all_include_presold_and_loops_data'])) {
			$all_include_presold_and_loops_data = $_REQUEST['all_include_presold_and_loops_data'];

			$sql_fil .= " AND inventory.box_type IN(";
			$Shippingboxtype = "";
			foreach ($all_include_presold_and_loops_data as $boxtype) {
				if ($boxtype == "ShippingKit") {
					$Shippingboxtype .= "'Medium','Large','Xlarge',";
				} else {
					$Shippingboxtype .= "'$boxtype',";
				}
			}
			if ($Shippingboxtype != "") {
				$Shippingboxtype = substr($Shippingboxtype, 0, strlen($Shippingboxtype) - 1);
			}
			$sql_fil .= $Shippingboxtype . " ) ";
		} else {
			$sql_fil .= " AND inventory.box_type IN ('Box','Boxnonucb','Medium','Large','Xlarge','Boxnonucb')"; //'LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'	
		}
	}
} else if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Supersacks") == 0) {
	if (isset($_REQUEST['box_subtype']) && (strcasecmp($_REQUEST["box_subtype"], "all") == -1 && $_REQUEST['box_subtype'] != "")) {
		$box_subtype = $box_subtype ?? "";
		$sql_fil .= " AND inventory.box_type IN ('$box_subtype')";
	} else {
		$sql_fil .= " AND inventory.box_type IN ('SupersackUCB','SupersacknonUCB','Supersacks')";
	}
}

$shipLat = "";
$shipLong = "";
$enter_zipcode = $_REQUEST['txtzipcode'];

if ($enter_zipcode != "") {
	$tmp_zipval = "";
	$tmp_zipval = str_replace(" ", "", $enter_zipcode);
	//if($country == "Canada" )
	//{ 	
	//	$zipShipStr= "Select * from zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
	//}elseif(($country) == "Mexico" ){
	//	$zipShipStr= "Select * from zipcodes_mexico limit 1";
	//}else {
	$zipShipStr = "Select latitude, longitude from ZipCodes WHERE zip = '" . intval($enter_zipcode) . "'";
	//}
	//echo $zipShipStr . "<br>";
	db_b2b();
	$zip_view_res = db_query($zipShipStr);
	while ($ziprec = array_shift($zip_view_res)) {
		$shipLat = $ziprec["latitude"];
		$shipLong = $ziprec["longitude"];
	}
}
//echo "shipLat " . $enter_zipcode . $shipLat . " = " . $shipLong . "<br>";
//exit;

$warehouse_innerjoin_sql = "";
if (isset($_REQUEST['warehouse']) && $_REQUEST['warehouse'] != "all") {
	//$warehouse_innerjoin_sql = "INNER JOIN tmp_inventory_list_set2 ON tmp_inventory_list_set2.trans_id = inventory.loops_id";
	//$final_filter_str.= " AND tmp_inventory_list_set2.wid = '" . $_REQUEST['warehouse'] . "' ";

	$final_filter_str .= " AND inventory.box_warehouse_id = '" . $_REQUEST['warehouse'] . "' ";
}

$box_tag_str = "";
if (isset($_REQUEST['box_tag'])) {
	$box_tag_str = implode(',', $_REQUEST['box_tag']);
	$final_filter_str .= " AND inventory.tag IN ('$box_tag_str')";
}

$b2b_status_str = '';
if (isset($_REQUEST["all_status_data"]) && $_REQUEST["all_status_data"] != "") {
	if (count($_REQUEST["all_status_data"]) != 3) {
		$status_filter_cnt = 0;
		$status_filter_or_val = "";
		$b2b_status_str .= " AND ( ";
		if (in_array('1', $_REQUEST["all_status_data"])) {
			$b2b_status_str .= " inventory.b2b_status=1.0 or inventory.b2b_status=1.1 or inventory.b2b_status=1.2 ";
			$status_filter_cnt = 1;
		}
		if (in_array('2', $_REQUEST["all_status_data"])) {
			if ($status_filter_cnt == 1) {
				$status_filter_or_val = " OR ";
			}
			$b2b_status_str .= " $status_filter_or_val inventory.b2b_status=2.0 or inventory.b2b_status=2.1 or inventory.b2b_status=2.2 or inventory.b2b_status=2.3 or inventory.b2b_status=2.4 ";
			//Added inventory.b2b_status=2.3 or inventory.b2b_status=2.4 to match the warehouse inv
			$status_filter_cnt = 1;
		}
		if (in_array('3', $_REQUEST["all_status_data"])) {
			if ($status_filter_cnt == 1) {
				$status_filter_or_val = " OR ";
			}
			$b2b_status_str .= " $status_filter_or_val inventory.b2b_status=2.5 or inventory.b2b_status=2.6 or inventory.b2b_status=2.7 or inventory.b2b_status=2.8 or inventory.b2b_status=2.9 ";
		}
		$b2b_status_str .= " ) ";
	}
}

//$b2b_status_str = " and (b2b_status=1.0 or b2b_status=1.1 or b2b_status=1.2 or b2b_status=2.0 or b2b_status=2.1 or b2b_status=2.2 or b2b_status=2.8)";
// $b2b_status_str = " and (inventory.b2b_status=1.0 or inventory.b2b_status=1.1 or inventory.b2b_status=1.2)";

$lastmonth_qry_array = array();
$lastmonth_qry = "SELECT box_id, sum(boxgood) as sumboxgood from loop_inventory where boxgood >0 and ";
$lastmonth_qry .= " UNIX_TIMESTAMP(add_date) >= " .  strtotime('today - 30 days') . " AND UNIX_TIMESTAMP(add_date) <= " . strtotime(date("m/d/Y")) . " group by box_id";
db();
$dt_res_so = db_query($lastmonth_qry);
while ($so_row = array_shift($dt_res_so)) {
	$lastmonth_qry_array[$so_row["box_id"]] = $so_row["sumboxgood"];
}

$dt_view_qry = "SELECT *, inventory.b2b_status as invb2b_status , inventory.id as b2b_id, inventory.loops_id, inventory.location_city, inventory.location_state, inventory.location_zip, inventory.bwall,
	inventory.expected_loads_per_mo, inventory.vendor, inventory.lengthInch, inventory.widthInch, inventory.depthInch, inventory.vendor_b2b_rescue,inventory.ship_ltl,
	inventory.material, inventory.customer_pickup_allowed, inventory.uniform_mixed_load, inventory.lead_time, inventory.box_sub_type, inventory.bwall_max,
	inventory.bwall_min,inventory.box_type,inventory.date, inventory.system_description, inventory.quantity_per_pallet, inventory.quantity, inventory.additional_description_text, inventory.location_zip_latitude, inventory.buy_now_load_can_ship_in,
	inventory.location_zip_longitude from inventory $warehouse_innerjoin_sql WHERE inventory.Active LIKE 'A' $b2b_status_str $sql_fil $final_filter_str order by inventory.b2b_status $availalesort asc";
// and actual_qty_calculated > 0 
//and ID = 5267 
//echo $dt_view_qry . "<br>";
//exit;
db_b2b();
$dt_view_res = db_query($dt_view_qry);
$products_array = array();
$final_product_array = array();
$supplier_name = "";
$load_av_after_po =	0;
$query_count = 0;
while ($dt_view_row = array_shift($dt_view_res)) {

	// $colorvalueQty = $dt_view_row["quantity_available"];
	$ftl_qty = $dt_view_row["quantity"];
	$nickname = "";
	if ($dt_view_row["nickname"] != "") {
		$nickname = $dt_view_row["nickname"];
	}

	$description = "";
	$box_sub_type = "";
	$q1 = "SELECT sub_type_name FROM loop_boxes_sub_type_master where unqid = '" . $dt_view_row["box_sub_type"] . "'";
	db();
	$query = db_query($q1);
	while ($fetch = array_shift($query)) {
		$box_sub_type = $fetch['sub_type_name'];
	}

	$box_type_txt = "";
	if ($dt_view_row["box_type"] == 'Gaylord' || $dt_view_row["box_type"] == 'GaylordUCB' || $dt_view_row["box_type"] == 'Loop' || $dt_view_row["box_type"] == 'PresoldGaylord') {
		$box_type_txt = "Gaylord";
	}
	if (
		$dt_view_row["box_type"] == 'Box' || $dt_view_row["box_type"] == 'Boxnonucb' || $dt_view_row["box_type"] == 'Presold' || $dt_view_row["box_type"] == 'Medium' || $dt_view_row["box_type"] == 'Large'
		|| $dt_view_row["box_type"] == 'Xlarge' || $dt_view_row["box_type"] == 'Boxnonucb'
	) {
		$box_type_txt = "Shipping Boxes";
	}
	if ($dt_view_row["box_type"] == 'PalletsUCB' || $dt_view_row["box_type"] == 'PalletsnonUCB') {
		$box_type_txt = "Pallets";
	}
	if ($dt_view_row["box_type"] == 'SupersackUCB' || $dt_view_row["box_type"] == 'SupersacknonUCB' || $dt_view_row["box_type"] == 'Supersacks') {
		$box_type_txt = "Supersacks";
	}
	if ($dt_view_row["box_type"] == 'DrumBarrelUCB' || $dt_view_row["box_type"] == 'DrumBarrelnonUCB') {
		$box_type_txt = "Drums/Barrels/IBCs";
	}
	if ($dt_view_row["box_type"] == 'Recycling' || $dt_view_row["box_type"] == 'Other' || $dt_view_row["box_type"] == 'Waste-to-Energy') {
		$box_type_txt = "Recycling+Other";
	}

	$box_sub_type_str = $box_sub_type == "" ? "" : " - $box_sub_type";
	if ($dt_view_row["uniform_mixed_load"] == "Uniform") {
		if (isset($_REQUEST['view_type']) && $_REQUEST['view_type'] == "grid_view") {
			$description = ucfirst($box_type_txt) . ": " . $dt_view_row["bwall"] . "ply" . $box_sub_type_str . "  <br>(ID " . $dt_view_row["ID"] . ")";
		} else {
			$description = ucfirst($box_type_txt) . ": " . $dt_view_row["bwall"] . "ply" . $box_sub_type_str . " (ID " . $dt_view_row["ID"] . ")";
		}
	} else {
		$wall_str = "";
		if ($dt_view_row["bwall_min"] == $dt_view_row["bwall_max"]) {
			$wall_str = $dt_view_row["bwall_min"];
		} else {
			$wall_str = $dt_view_row["bwall_min"] . "-" . $dt_view_row["bwall_max"];
		}
		if (isset($_REQUEST['view_type']) && $_REQUEST['view_type'] == "grid_view") {
			$description = ucfirst($box_type_txt) . ": " . $wall_str . "ply" . $box_sub_type_str . " <br>(ID " . $dt_view_row["ID"] . ")";
		} else {
			$description = ucfirst($box_type_txt) . ": " . $wall_str . "ply" . $box_sub_type_str . " (ID " . $dt_view_row["ID"] . ")";
		}
	}

	$system_description = $dt_view_row["system_description"] . " " . number_format($dt_view_row["quantity_per_pallet"]) . "/pallet, " . number_format($ftl_qty) . "/load " . $dt_view_row["additional_description_text"];

	$ship_from = "";
	if ($dt_view_row["location_state"] != "") {
		$get_loc_qry = "Select state from state_master where state_code ='" . $dt_view_row["location_state"] . "'";
		db();
		$get_loc_res = db_query($get_loc_qry);
		$loc_row = array_shift($get_loc_res);
		$ship_from = $loc_row["state"];
	}

	$added_on = $dt_view_row["date"];
	$ltl = $dt_view_row["ship_ltl"] == 1 ? "Yes" : "No";
	$customer_pickup_allowed = $dt_view_row["customer_pickup_allowed"] == 1 ? "Yes" : "No";
	$updated_on = "";
	$img = "";
	$loads = " - ";
	$vendor_b2b_rescue = $dt_view_row["vendor_b2b_rescue"];
	$supplier_owner = "";
	$first_load_can_ship_in = "";
	$vendor_id = $dt_view_row["vendor"];
	$b2b_status = $dt_view_row["invb2b_status"];
	$b2b_id = $dt_view_row["b2b_id"];
	$lead_time_for_FTL = $dt_view_row["buy_now_load_can_ship_in"];
	$shipFromLocation = $dt_view_row["location"];
	$actual_qty = $dt_view_row["actual_qty_calculated"];

	$lead_time_days = 0;
	if ($lead_time_for_FTL != "") {
		$lead_time_for_FTL_org = $lead_time_for_FTL;
		$tmppos_1 = strpos($lead_time_for_FTL_org, 'Now');
		if ($tmppos_1 != false) {
			$lead_time_days = 0;
		}

		$tmppos_1 = strpos($lead_time_for_FTL_org, 'Never (sell the');
		if ($tmppos_1 != false) {
			$lead_time_days = $dt_view_row["lead_time"];
		}

		$lead_time_for_FTL_org = str_replace("<font color=green>", "", $lead_time_for_FTL_org);
		$lead_time_for_FTL_org = str_replace("</font>", "", $lead_time_for_FTL_org);

		$lead_time_arr = explode(" ", $lead_time_for_FTL_org, 2);

		if ($lead_time_arr[1] == 'Weeks') {
			$lead_time_days = (int)$lead_time_arr[0] * 7;
		}
		if ($lead_time_arr[1] == 'Day') {
			$lead_time_days = $lead_time_arr[0];
		}
		if ($lead_time_arr[1] == 'Days') {
			$lead_time_days = $lead_time_arr[0];
		}
	}

	$pickup_cdata_allowed = 'No';
	if ($dt_view_row["customer_pickup_allowed"] == 1) {
		$pickup_cdata_allowed = 'Yes';
	}

	$st_query = "Select * from b2b_box_status where status_key='" . $b2b_status . "' $box_status_filter";
	db();
	$st_res = db_query($st_query);
	$st_row = array_shift($st_res);
	$status_org = $st_row["box_status"];
	$status = $st_row["box_status"];
	if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
		$status = "<font color='green'>" . $st_row["box_status"] . "</font></td>";
	} elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
		$status = "<font color='orange'>" . $st_row["box_status"] . "</font></td>";
	}
	if ($st_row["box_urgent"] == 1) {
		$status = "<font color='red'>URGENT</font></td>";
	}

	$loop_id = $dt_view_row["loops_id"];
	$qry_sku = "select id, box_warehouse_id, sku, bpallet_qty, boxes_per_trailer, type, ship_ltl, customer_pickup_allowed, uniform_mixed_load,
		bpic_1, flyer_notes,last_modified_date, after_po, expected_loads_per_mo, blength, bwidth, bdepth, blength_frac, bwidth_frac, bdepth_frac, blength_min, blength_max,  
		bwidth_min, bwidth_max, bheight_min, bheight_max from loop_boxes where b2b_id=" . $dt_view_row["b2b_id"] . " $loop_boxes_final_filter_str";
	//echo $qry_sku . "<br>";

	$sku = "";
	$flyer_notes = "";
	$loop_data_found = "no";
	db();
	$dt_view_sku = db_query($qry_sku);
	$total_no_of_loads = 0;
	$box_warehouse_id = 0;
	$frequency = 0;
	$blength = $bwidth = $bdepth = $bcubicfootage = 0;
	while ($sku_val = array_shift($dt_view_sku)) {
		$loop_data_found = "yes";

		// Dimension sorting 
		$uniform_mixed_load = $sku_val["uniform_mixed_load"];

		if ($uniform_mixed_load != "Mixed") {

			$blength = $sku_val["blength"];
			$blength = preg_replace("(\n)", "<BR>", $blength);
			$bwidth = $sku_val["bwidth"];
			$bwidth = preg_replace("(\n)", "<BR>", $bwidth);
			$bheight = $sku_val["bdepth"];
			$bheight = preg_replace("(\n)", "<BR>", $bheight);

			$blength_frac = $sku_val["blength_frac"];
			$blength_frac = preg_replace("(\n)", "<BR>", $blength_frac);
			$bwidth_frac = $sku_val["bwidth_frac"];
			$bwidth_frac = preg_replace("(\n)", "<BR>", $bwidth_frac);
			$bdepth_frac = $sku_val["bdepth_frac"];
			$bdepth_frac = preg_replace("(\n)", "<BR>", $bdepth_frac);

			if ($blength_frac != "") {
				$frac = explode("/", $blength_frac);
				$numerator = $frac[0];
				$denominator = $frac[1];
				$box_length = $blength + (float)$numerator / (float)$denominator;
			} else {
				$frac = "";
				$box_length = $blength;
			}
			//box width fraction
			if ($bwidth_frac != "") {
				$frac = explode("/", $bwidth_frac);
				$numerator = $frac[0];
				$denominator = $frac[1];
				$box_width = $bwidth + (float)$numerator / (float)$denominator;
			} else {
				$frac = "";
				$box_width = $bwidth;
			}

			//box height fraction
			if ($bdepth_frac != "") {
				$frac = explode("/", $bdepth_frac);
				$numerator = $frac[0];
				$denominator = $frac[1];
				$box_height = $bheight + (float)$numerator / (float)$denominator;
			} else {
				$frac = "";
				$box_height = $bheight;
			}

			$blength = $box_length;
			$bwidth = $box_width;
			$bdepth = $box_height;

			$bcubicfootage = (($blength + isset($blength_frac_conv)) * ($bwidth + isset($bwidth_frac_conv)) * ($bheight + isset($bdepth_frac_conv))) / 1728;
			// $bcubicfootage = number_format($bcubicfootage, 2);
		} else {

			$blength = $sku_val["blength_max"];
			$bwidth = $sku_val["bwidth_max"];
			$bdepth = $sku_val["bheight_max"];

			$blength_min = $sku_val["blength_min"];
			$blength_min = preg_replace("(\n)", "<BR>", $blength_min);
			$blength_max = $sku_val["blength_max"];
			$blength_max = preg_replace("(\n)", "<BR>", $blength_max);
			$bwidth_min = $sku_val["bwidth_min"];
			$bwidth_min = preg_replace("(\n)", "<BR>", $bwidth_min);
			$bwidth_max = $sku_val["bwidth_max"];
			$bwidth_max = preg_replace("(\n)", "<BR>", $bwidth_max);
			$bheight_min = $sku_val["bheight_min"];
			$bheight_min = preg_replace("(\n)", "<BR>", $bheight_min);
			$bheight_max = $sku_val["bheight_max"];
			$bheight_max = preg_replace("(\n)", "<BR>", $bheight_max);

			$bcubicfootage_min = (($blength_min) * ($bwidth_min) * ($bheight_min)) / 1728;
			$item_bcubicfootage_min = number_format($bcubicfootage_min, 2);
			$bcubicfootage_max = (($blength_max) * ($bwidth_max) * ($bheight_max)) / 1728;
			$item_bcubicfootage_max = number_format($bcubicfootage_max, 2);
			$bcubicfootage = $item_bcubicfootage_min . " - " . $item_bcubicfootage_max;
		}

		//$img = "../boxpics_thumbnail/".$sku_val['bpic_1'];
		$img = $sku_val['bpic_1'];

		$boxes_per_trailer = $sku_val['boxes_per_trailer'];
		$flyer_notes = $sku_val['flyer_notes'];
		$last_modified_date = $sku_val['last_modified_date'];

		$loop_boxes_txtafterPo = $sku_val['after_po'];
		$box_warehouse_id = $sku_val['box_warehouse_id'];

		if ($box_warehouse_id == 238) {
			$frequency = number_format($sku_val['expected_loads_per_mo'], 0);
		} else {
			$frequency = number_format($lastmonth_qry_array[$loop_id], 0);
		}

		$rec_found_box = "n";
		$after_po_val_tmp = 0;



		$date_dt = new DateTime(); //Today
		$dateMinus12 = $date_dt->modify("-12 months"); // Last day 12 months ago

		$sales_order_qty = 0;
		$dt_so_item = "SELECT sum(qty) as sumqty FROM loop_salesorders ";
		$dt_so_item .= " inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
		$dt_so_item .= " where (STR_TO_DATE(loop_salesorders.so_date, '%m/%d/%Y') between '" . $dateMinus12->format('Y-m-d') . "' and '" . date("Y-m-d") . "') ";
		if (isset($_REQUEST['warehouse']) && $_REQUEST['warehouse'] != "all") {
			$dt_so_item .= " and location_warehouse_id = '" . $_REQUEST['warehouse'] . "' ";
		}
		$dt_so_item .= " and box_id = " . $loop_id . " and loop_transaction_buyer.bol_create = 0 and loop_transaction_buyer.Preorder=0 and loop_transaction_buyer.ignore = 0 ";
		db();
		$dt_res_so_item = db_query($dt_so_item);

		while ($so_item_row = array_shift($dt_res_so_item)) {
			if ($so_item_row["sumqty"] > 0) {
				$sales_order_qty = $so_item_row["sumqty"];
			} else {
				$sales_order_qty = 0;
			}
		}

		$actual_po = $dt_view_row["actual_qty_calculated"] - $sales_order_qty;

		if (isset($per_trailer) > 0) {
			$load_av_after_po = round(($actual_po / $per_trailer) * 100, 2);
		}

		//$txt_after_po = $rec_found_box == "n" ? $loop_boxes_txtafterPo :  $afterpo;

		$reccnt = 0;
		if ($sales_order_qty > 0) {
			$reccnt = $sales_order_qty;
		}

		if ($availale_selectval == "actual") {
			$txt_after_po = $dt_view_row["actual_qty_calculated"] - $sales_order_qty;
		} else {
			$txt_after_po = $dt_view_row["actual_qty_calculated"];
		}

		$percent_per_load = 0;
		if ($ftl_qty > 0) {
			$percent_per_load = ((float)str_replace(",", "", $txt_after_po) / (float)str_replace(",", "", $ftl_qty)) * 100;
		}

		$expected_loads_per_mo_to_display = round($txt_after_po / $boxes_per_trailer, 2);

		$qtynumbervalue = 0;
		if ($txt_after_po == 0 && $expected_loads_per_mo_to_display == 0) {
			$colorvalueQty = "<font color='black'>" . number_format($txt_after_po, 0) . "</font></td>";
			$qtynumbervalue = str_replace(",", "", number_format($txt_after_po, 0));
		} else if ($txt_after_po >= $boxes_per_trailer) {
			$colorvalueQty = "<font color='green'>" . number_format($txt_after_po, 0) . "</font></td>";
			$qtynumbervalue = str_replace(",", "", number_format($txt_after_po, 0));
		} else {
			$colorvalueQty = "<font color='black'>" . number_format($txt_after_po, 0) . "</font></td>";
			$qtynumbervalue = str_replace(",", "", number_format($txt_after_po, 0));
		}

		$qtynumbervalueorg = $txt_after_po;

		$to_show_rec_main1 = "";
		$to_show_rec_main2 = "y";
		$to_show_rec_main3 = "y";
		$to_show_rec_main4 = "y";
		$to_show_rec_main5 = "y";
		if ($txt_after_po > 0) {
			$to_show_rec_main1 = "y";
		}

		$td_bg = "";
		if (($actual_qty >= $boxes_per_trailer) && ($boxes_per_trailer > 0)) {
			$td_bg = "yellow";
		}


		//Filter by 'Qty Avail', default is unchecked, if user checks box, then adjust the default from 'Qty Avail' > 0 to no filter instead.
		if (isset($_REQUEST['include_sold_out_items']) && $_REQUEST['include_sold_out_items'] == 1) {
			$to_show_rec_main1 = "y";
		}

		if (isset($_REQUEST['include_FTL_Rdy_Now_Only']) && $_REQUEST['include_FTL_Rdy_Now_Only'] == 1) {
			$to_show_rec_main3 = "";
			if (($txt_after_po >= $boxes_per_trailer) && ($txt_after_po > 0 && $boxes_per_trailer > 0)) {
				$to_show_rec_main3 = "y";
			}
		}

		if ($last_modified_date != "") {
			$days = number_format((strtotime(date("Y-m-d")) - strtotime($last_modified_date)) / (60 * 60 * 24));
			$updated_on = date("d-m-Y", strtotime($last_modified_date)) . " ( " . $days . " days ago)";
			if (isset($_REQUEST['view_type']) && $_REQUEST['view_type'] == "list_view") {
				$updated_on = date("d-m-Y", strtotime($last_modified_date)) . " <br> ( " . $days . " days ago)";
			}
		}

		// Desciption Hover Start

		$b_urgent = "No";
		$contracted = "No";
		$prepay = "No";
		$ship_ltl = "No";
		if ($dt_view_row["box_urgent"] == 1) {
			$b_urgent = "Yes";
		}
		if ($dt_view_row["contracted"] == 1) {
			$contracted = "Yes";
		}
		if ($dt_view_row["prepay"] == 1) {
			$prepay = "Yes";
		}
		if ($dt_view_row["ship_ltl"] == 1) {
			$ship_ltl = "Yes";
		}

		$ownername = "";
		if ($vendor_b2b_rescue > 0) {

			$q1 = "SELECT id, company_name, b2bid FROM loop_warehouse where id = $vendor_b2b_rescue";
			db();
			$query = db_query($q1);
			while ($fetch = array_shift($query)) {
				$supplier_name = get_nickname_val($fetch['company_name'], $fetch["b2bid"]);

				$comqry = "select *,employees.name as empname from companyInfo inner join employees on employees.employeeID=companyInfo.assignedto where employees.status='Active' and companyInfo.id=" . $fetch["b2bid"];
				db_b2b();
				$comres = db_query($comqry);
				while ($comrow = array_shift($comres)) {
					$ownername = $comrow["initials"];
				}
			}
		}

		$b2b_ulineDollar = round($dt_view_row["ulineDollar"]);
		$b2b_ulineCents = $dt_view_row["ulineCents"];
		$b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
		$b2b_fob = "$" . number_format($b2b_fob, 2);

		$b2b_costDollar = round($dt_view_row["costDollar"]);
		$b2b_costCents = $dt_view_row["costCents"];
		$b2b_cost = $b2b_costDollar + $b2b_costCents;
		$b2b_cost = "$" . number_format($b2b_cost, 2);

		$ship_cdata_ltl = $sku_val['ship_ltl'] == 1 ? 'Y' : '';
		$customer_pickup_allowed = $sku_val['customer_pickup_allowed'] == 1 ? 'Y' : '';

		$tipStr = "<b>Notes:</b> " . $dt_view_row['notes'] . "<br>";
		if ($dt_view_row['date'] != "0000-00-00") {
			$tipStr .= "<b>Notes Date:</b> " . date("m/d/Y", strtotime($dt_view_row['date'])) . "<br>";
		} else {
			$tipStr .= "<b>Notes Date:</b> <br>";
		}
		$tipStr .= "<b>Urgent:</b> " . $b_urgent . "<br>";
		$tipStr .= "<b>Contracted:</b> " . $contracted . "<br>";
		$tipStr .= "<b>Prepay:</b> " . $prepay . "<br>";
		$tipStr .= "<b>Can Ship LTL?</b> " . $ship_ltl . "<br>";

		$tipStr .= "<b>Qty Avail:</b> " . number_format($txt_after_po, 0) . "<br>";
		$tipStr .= "<b>Lead Time for FTL:</b> " . $lead_time_for_FTL . "<br>";
		$tipStr .= "<b>Qty Available, Next 3 Months:</b> " . $dt_view_row["expected_loads_per_mo"] . "<br>";
		$tipStr .= "<b>B2B Status:</b> " . $status_org . "<br>";
		$tipStr .= "<b>Supplier Relationship Owner:</b> " . $ownername . "<br>";
		$tipStr .= "<b>B2B ID#:</b> " . $dt_view_row["b2b_id"] . "<br>";
		$tipStr .= "<b>Description:</b> " . $dt_view_row["description"] . "<br>";
		$tipStr .= "<b>Supplier:</b> " .  $supplier_name . "<br>";
		$tipStr .= "<b>Ship From:</b> " . $ship_from . "<br>";
		$tipStr .= "<b>Miles From:</b> " . isset($miles_from) . "<br>";
		$tipStr .= "<b>Per Pallet:</b> " . $sku_val['bpallet_qty'] . "<br>";
		$tipStr .= "<b>Per Truckload:</b> " . $sku_val['boxes_per_trailer'] . "<br>";
		$tipStr .= "<b>Min FOB:</b> " . $b2b_fob . "<br>";
		$tipStr .= "<b>B2B Cost:</b> " . $b2b_cost . "<br>";
		$tipStr .= "<b>Ship Ltl:</b> " . $ship_cdata_ltl . "<br>";
		$tipStr .= "<b>Custome Pickup:</b> " . $pickup_cdata_allowed . "<br>";

		$description_hover_notes = str_replace("'", "\'", $tipStr);
		$description_hover_notes = str_replace('"', " inch ", $tipStr);
		// $description_hover_notes = str_replace("'", "'" , $tipStr);

		// Desciption Hover End

		// To get the Shipsinweek
		$no_of_loads = 0;
		$shipsinweek = "";
		$to_show_rec = "";
		$total_no_of_loads = 0;
		if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 5) {
			$to_show_rec = "";
			$next_2_week_date = date("Y-m-d", strtotime("+2 week"));
			$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
				and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
			//echo $dt_view_qry . "<br>";
			db();
			$dt_view_res_box = db_query($dt_view_qry);
			while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
				if ($dt_view_res_box_data["trans_rec_id"] == 0) {
					$no_of_loads = $no_of_loads + 1;
					$to_show_rec = "y";
				}
				$total_no_of_loads = $total_no_of_loads + 1;

				if ($no_of_loads == 1) {
					$now_date = time();
					$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
					$datediff = $next_load_date - $now_date;
					$shipsinweek_org = round($datediff / (60 * 60 * 24));
					//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
					if (isset($inv["lead_time"]) > $shipsinweek_org) {
						$shipsinweekval = isset($inv["lead_time"]);
					} else {
						$shipsinweekval = $shipsinweek_org;
					}
					if ($shipsinweekval == 0) {
						$shipsinweekval = 1;
					}
					if ($shipsinweekval >= 10) {
						$shipsinweek = round($shipsinweekval / 7) . " weeks";
					}
					if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
						$shipsinweek = $shipsinweekval . " days";
					}
					if ($shipsinweekval == 1) {
						$shipsinweek = $shipsinweekval . " day";
					}
				}
			}
		}

		// Can ship in 4 weeks
		else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 10) {
			$to_show_rec = "";
			$next_4_week_date = date("Y-m-d", strtotime("+4 week"));
			$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
				and (load_available_date <= '" . $next_4_week_date . "') order by load_available_date";
			//echo $dt_view_qry . "<br>";
			db();
			$dt_view_res_box = db_query($dt_view_qry);
			while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
				if ($dt_view_res_box_data["trans_rec_id"] == 0) {
					$no_of_loads = $no_of_loads + 1;
					$to_show_rec = "y";
				}
				$total_no_of_loads = $total_no_of_loads + 1;

				if ($no_of_loads == 1) {
					$now_date = time();
					$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
					$datediff = $next_load_date - $now_date;
					$shipsinweek_org = round($datediff / (60 * 60 * 24));
					//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
					if (isset($inv["lead_time"]) > $shipsinweek_org) {
						$shipsinweekval = isset($inv["lead_time"]);
					} else {
						$shipsinweekval = $shipsinweek_org;
					}
					if ($shipsinweekval == 0) {
						$shipsinweekval = 1;
					}
					if ($shipsinweekval >= 10) {
						$shipsinweek = round($shipsinweekval / 7) . " weeks";
					}
					if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
						$shipsinweek = $shipsinweekval . " days";
					}
					if ($shipsinweekval == 1) {
						$shipsinweek = $shipsinweekval . " day";
					}
				}
			}
		}
		//Ready to ship whenever
		else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 6) {
			$to_show_rec = "y";
		}
		//Can ship next month a date range of the 1st day of next month to last day of next month 
		else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 7) {
			$to_show_rec = "";
			$next_month_date = date("Y-m-t");
			$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
				and (load_available_date <= '" . $next_month_date . "')
				order by load_available_date";
			//echo $dt_view_qry . "<br>";
			db();
			$dt_view_res_box = db_query($dt_view_qry);
			while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
				if ($dt_view_res_box_data["trans_rec_id"] == 0) {
					$no_of_loads = $no_of_loads + 1;
					$to_show_rec = "y";
				}
				$total_no_of_loads = $total_no_of_loads + 1;

				if ($no_of_loads == 1) {
					$now_date = time();
					$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
					$datediff = $next_load_date - $now_date;
					$shipsinweek_org = round($datediff / (60 * 60 * 24));
					//echo $inv["lead_time"] . " | " . $dt_view_res_box_data["load_available_date"] . " | " . $shipsinweek_org . " <br>";
					if (isset($inv["lead_time"]) > $shipsinweek_org) {
						$shipsinweekval = isset($inv["lead_time"]);
					} else {
						$shipsinweekval = $shipsinweek_org;
					}
					if ($shipsinweekval == 0) {
						$shipsinweekval = 1;
					}
					if ($shipsinweekval >= 10) {
						$shipsinweek = round($shipsinweekval / 7) . " weeks";
					}
					if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
						$shipsinweek = $shipsinweekval . " days";
					}
					if ($shipsinweekval == 1) {
						$shipsinweek = $shipsinweekval . " day";
					}
				}
			}
			//echo "in step 7 " . $to_show_rec . "<br>";	
		}



		//Enter ship by date = Take user input of 1 date
		else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 9 && $_REQUEST["selected_date"] != '') {
			$to_show_rec = "";
			$next_month_date = date("Y-m-d", strtotime($_REQUEST["selected_date"]));
			$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
				and load_available_date <= '" . $next_month_date . "' order by load_available_date";
			db();
			$dt_view_res_box = db_query($dt_view_qry);
			while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
				if ($dt_view_res_box_data["trans_rec_id"] == 0) {
					$no_of_loads = $no_of_loads + 1;
					$to_show_rec = "y";
				}
				$total_no_of_loads = $total_no_of_loads + 1;

				if ($no_of_loads == 1) {
					$now_date = time();
					$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
					$datediff = $next_load_date - $now_date;
					$shipsinweek_org = round($datediff / (60 * 60 * 24));
					if (isset($inv["lead_time"]) > $shipsinweek_org) {
						$shipsinweekval = isset($inv["lead_time"]);
					} else {
						$shipsinweekval = $shipsinweek_org;
					}
					if ($shipsinweekval == 0) {
						$shipsinweekval = 1;
					}
					if ($shipsinweekval >= 10) {
						$shipsinweek = round($shipsinweekval / 7) . " weeks";
					}
					if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
						$shipsinweek = $shipsinweekval . " days";
					}
					if ($shipsinweekval == 1) {
						$shipsinweek = $shipsinweekval . " day";
					}
				}
			}
		}

		// Ready Now 
		else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] == 4) {
			$next_2_week_date = date("Y-m-d", strtotime("+3 day"));
			$to_show_rec = "";
			$dt_view_qry = "SELECT load_available_date, trans_rec_id  from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
				and (load_available_date <= '" . $next_2_week_date . "') order by load_available_date";
			db();
			$dt_view_res_box = db_query($dt_view_qry);
			while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
				if ($dt_view_res_box_data["trans_rec_id"] == 0) {
					$no_of_loads = $no_of_loads + 1;
					$to_show_rec = "y";
				}
				$total_no_of_loads = $total_no_of_loads + 1;

				if ($no_of_loads == 1) {
					$now_date = time();
					$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
					$datediff = $next_load_date - $now_date;
					$shipsinweek_org = round($datediff / (60 * 60 * 24));
					if (isset($inv["lead_time"]) > $shipsinweek_org) {
						$shipsinweekval = isset($inv["lead_time"]);
					} else {
						$shipsinweekval = $shipsinweek_org;
					}
					if ($shipsinweekval == 0) {
						$shipsinweekval = 1;
					}
					if ($shipsinweekval >= 10) {
						$shipsinweek = round($shipsinweekval / 7) . " weeks";
					}
					if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
						$shipsinweek = $shipsinweekval . " days";
					}
					if ($shipsinweekval == 1) {
						$shipsinweek = $shipsinweekval . " day";
					}
				}
			}
		} else {
			$to_show_rec = "";
			$dt_view_qry = "SELECT load_available_date, trans_rec_id from loop_next_load_available_history where inv_loop_id =" . $loop_id . " and inactive_delete_flg = 0 
				order by load_available_date";
			db();
			$dt_view_res_box = db_query($dt_view_qry);
			while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
				if ($dt_view_res_box_data["trans_rec_id"] == 0) {
					$no_of_loads = $no_of_loads + 1;
					$to_show_rec = "y";
				}
				$total_no_of_loads = $total_no_of_loads + 1;

				if ($no_of_loads == 1) {
					$now_date = time();
					$next_load_date = strtotime($dt_view_res_box_data["load_available_date"]);
					$datediff = $next_load_date - $now_date;
					$shipsinweek_org = round($datediff / (60 * 60 * 24));
					if ((isset($inv["lead_time"]) > $shipsinweek_org) || ($shipsinweek_org < 0)) {
						$shipsinweekval = isset($inv["lead_time"]);
					} else {
						$shipsinweekval = $shipsinweek_org;
					}

					if ($shipsinweekval == 0) {
						$shipsinweekval = 1;
					}

					if ($shipsinweekval >= 10) {
						$shipsinweek = round($shipsinweekval / 7) . " weeks";
					}
					if ($shipsinweekval >= 2 && $shipsinweekval < 10) {
						$shipsinweek = $shipsinweekval . " days";
					}
					if ($shipsinweekval == 1) {
						$shipsinweek = $shipsinweekval . " day";
					}
				}
			}
		}
		$no_of_loads_str = "";
		$first_load_can_ship_in = $shipsinweek;
		if ($total_no_of_loads == 1) {
			$no_of_loads_str = " Load";
		}
		if ($total_no_of_loads > 1) {
			$no_of_loads_str = " Loads";
		}
		$loads = $no_of_loads . " of " . $total_no_of_loads . "" . $no_of_loads_str;
	}


	$companyID = "";


	if (isset($_REQUEST["all_work_as_a_kit_box_data"]) && $_REQUEST["all_work_as_a_kit_box_data"] != "") {
		$to_show_rec_main4 = "n";
		if ($loop_data_found == "yes") {
			$to_show_rec_main4 = "y";
		}
	}



	if (isset($_REQUEST["box_type"]) && strcasecmp($_REQUEST["box_type"], "Shipping") == 0) {
		$to_show_rec = "y";
	}

	//&& $_REQUEST["timing"] != ""
	// if ($to_show_rec_main1 == "y" && $to_show_rec_main2 == "y" && $to_show_rec_main3 == "y" && $to_show_rec_main4 == "y" && $to_show_rec_main5 == "y") {
	if (isset($to_show_rec) == "y" && isset($to_show_rec_main1) == "y" && isset($to_show_rec_main2) == "y" && isset($to_show_rec_main3) == "y" && isset($to_show_rec_main4) == "y") {
		if ($dt_view_row["location_zip"] != "" && $enter_zipcode != "") {
			$locLat = $dt_view_row["location_zip_latitude"];
			$locLong = $dt_view_row["location_zip_longitude"];

			$distLat = ($shipLat - $locLat) * 3.141592653 / 180;
			$distLong = ($shipLong - $locLong) * 3.141592653 / 180;

			$distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
			$distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));
		}

		$miles_from = (int) (6371 * isset($distC) * .621371192);
		//echo "miles_from = " . $miles_from . "<br>";

		if ($miles_from <= 250) {
			$miles_away_color = "green";
		}
		if (($miles_from <= 550) && ($miles_from > 250)) {
			$miles_away_color = "#FF9933";
		}
		if (($miles_from > 550)) {
			$miles_away_color = "red";
		}

		if ($enter_zipcode == "") {
			$miles = "<font color='red'>Enter Zipcode</font>";
		} else {
			$miles = "<font color='$miles_away_color'>" . $miles_from . " mi away</font>";
		}

		$b2b_ulineDollar = round($dt_view_row["ulineDollar"]);
		$b2b_ulineCents = $dt_view_row["ulineCents"];
		if ($b2b_ulineDollar != "" || $b2b_ulineCents != "") {
			$price = "$" . number_format($b2b_ulineDollar + $b2b_ulineCents, 2);
		} else {
			$price = "$0.00";
		}
		$minfob = str_replace(",", "", $price);
		$minfob = str_replace("$", "", $minfob);

		$frequency_ftl = 0;
		if ($ftl_qty > 0) {
			$frequency_ftl = (float)str_replace(",", "", $frequency) / (float)str_replace(",", "", $ftl_qty);
		}

		if ($_REQUEST["min_price"] == 0 && $_REQUEST["max_price"] == 500 && $_REQUEST["timing"] == "") {
			$products_array[] = array(
				'ftl_qty' => $ftl_qty,
				'colorvalueQty' => isset($colorvalueQty),
				'qtynumbervalue' => isset($qtynumbervalue),
				'qtynumbervalueorg' => isset($qtynumbervalueorg),
				'img' => $img,
				'lead_time_days' => $lead_time_days,
				'description' => $description,
				'status' => $status,
				'ship_from' => $ship_from,
				'system_description' => $system_description,
				'flyer_notes' => $flyer_notes,
				'lead_time_of_FTL' => $lead_time_for_FTL,
				'distance' => $miles,
				'distance_sort' => $miles_from,
				'ltl' => $ltl,
				'td_bg' => isset($td_bg),
				'customer_pickup' => $customer_pickup_allowed,
				'supplier_owner' => $supplier_owner,
				'updated_on' => $updated_on,
				'price' => $price,
				'loads' => $loads,
				'shipsinweekval' => isset($shipsinweekval),
				'first_load_can_ship_in' => $first_load_can_ship_in,
				'b2b_id' => $b2b_id,
				'loop_id' => $loop_id,
				'box_warehouse_id' => $box_warehouse_id,
				'companyID' => $companyID,
				'minfob' => $minfob,
				'txtaddress' => $dropoff_add1,
				'txtaddress2' => $dropoff_add2,
				'txtcity' => $dropoff_city,
				'txtstate' => $dropoff_state,
				'txtcountry' => $dropoff_country,
				'txtzipcode' => $dropoff_zip,
				'added_on' => $added_on,
				'supplier_name' => $supplier_name,
				'shipFromLocation' => $shipFromLocation,
				'percent_per_load' => number_format($percent_per_load, 0),
				'actual_qty' => $actual_qty,
				'actual_po' => isset($actual_po),
				'load_av_after_po' => $load_av_after_po,
				'frequency' => $frequency,
				'frequency_sort' => str_replace(",", "", $frequency),
				'frequency_ftl' => $frequency_ftl,
				'vendor_b2b_rescue' => $vendor_b2b_rescue,
				'reccnt' => isset($reccnt),
				'description_hover_notes' => isset($description_hover_notes),
				'nickname' => $nickname,
				'length' => $blength,
				'width' => $bwidth,
				'depth' => $bdepth,
				'cubicfootage' => $bcubicfootage,
				'loop_id_encrypt_str' => urlencode(encrypt_password($loop_id)),
			);
		} else {
			if (isset($_REQUEST["min_price"]) && $_REQUEST["min_price"] != 0 || isset($_REQUEST['max_price']) && $_REQUEST["max_price"] != 500) {
				if ($minfob >= $_REQUEST["min_price"] && $minfob <= $_REQUEST["max_price"]) {
					$products_array[] = array(
						'ftl_qty' => $ftl_qty,
						'colorvalueQty' => isset($colorvalueQty),
						'qtynumbervalue' => isset($qtynumbervalue),
						'qtynumbervalueorg' => isset($qtynumbervalueorg),
						'img' => $img,
						'lead_time_days' => $lead_time_days,
						'description' => $description,
						'status' => $status,
						'ship_from' => $ship_from,
						'system_description' => $system_description,
						'flyer_notes' => $flyer_notes,
						'lead_time_of_FTL' => $lead_time_for_FTL,
						'distance' => $miles,
						'distance_sort' => $miles_from,
						'ltl' => $ltl,
						'td_bg' => isset($td_bg),
						'customer_pickup' => $customer_pickup_allowed,
						'supplier_owner' => $supplier_owner,
						'updated_on' => $updated_on,
						'price' => $price,
						'loads' => $loads,
						'shipsinweekval' => isset($shipsinweekval),
						'first_load_can_ship_in' => $first_load_can_ship_in,
						'b2b_id' => $b2b_id,
						'loop_id' => $loop_id,
						'box_warehouse_id' => $box_warehouse_id,
						'companyID' => $companyID,
						'minfob' => $minfob,
						'txtaddress' => $dropoff_add1,
						'txtaddress2' => $dropoff_add2,
						'txtcity' => $dropoff_city,
						'txtstate' => $dropoff_state,
						'txtcountry' => $dropoff_country,
						'txtzipcode' => $dropoff_zip,
						'added_on' => $added_on,
						'supplier_name' => $supplier_name,
						'shipFromLocation' => $shipFromLocation,
						'percent_per_load' => number_format($percent_per_load, 0),
						'actual_qty' => $actual_qty,
						'actual_po' => isset($actual_po),
						'load_av_after_po' => $load_av_after_po,
						'frequency' => $frequency,
						'frequency_sort' => str_replace(",", "", $frequency),
						'frequency_ftl' => $frequency_ftl,
						'vendor_b2b_rescue' => $vendor_b2b_rescue,
						'reccnt' => isset($reccnt),
						'description_hover_notes' => isset($description_hover_notes),
						'nickname' => $nickname,
						'length' => $blength,
						'width' => $bwidth,
						'depth' => $bdepth,
						'cubicfootage' => $bcubicfootage,
						'QTYfunction' => display_preoder_sel($query_count, $reccnt, $loop_id, $box_warehouse_id),
						'loop_id_encrypt_str' => urlencode(encrypt_password($loop_id)),
					);
				}
			} else if (isset($_REQUEST["timing"]) && $_REQUEST["timing"] != "") {
				/*if($to_show_rec=='y'){ */
				$products_array[] = array(
					'ftl_qty' => $ftl_qty,
					'colorvalueQty' => isset($colorvalueQty),
					'qtynumbervalue' => isset($qtynumbervalue),
					'qtynumbervalueorg' => isset($qtynumbervalueorg),
					'img' => $img,
					'lead_time_days' => $lead_time_days,
					'description' => $description,
					'status' => $status,
					'ship_from' => $ship_from,
					'system_description' => $system_description,
					'flyer_notes' => $flyer_notes,
					'lead_time_of_FTL' => $lead_time_for_FTL,
					'distance' => $miles,
					'distance_sort' => $miles_from,
					'ltl' => $ltl,
					'td_bg' => isset($td_bg),
					'customer_pickup' => $customer_pickup_allowed,
					'supplier_owner' => $supplier_owner,
					'updated_on' => $updated_on,
					'price' => $price,
					'loads' => $loads,
					'shipsinweekval' => isset($shipsinweekval),
					'first_load_can_ship_in' => $first_load_can_ship_in,
					'b2b_id' => $b2b_id,
					'loop_id' => $loop_id,
					'box_warehouse_id' => $box_warehouse_id,
					'companyID' => $companyID,
					'minfob' => $minfob,
					'txtaddress' => $dropoff_add1,
					'txtaddress2' => $dropoff_add2,
					'txtcity' => $dropoff_city,
					'txtstate' => $dropoff_state,
					'txtcountry' => $dropoff_country,
					'txtzipcode' => $dropoff_zip,
					'added_on' => $added_on,
					'supplier_name' => $supplier_name,
					'shipFromLocation' => $shipFromLocation,
					'percent_per_load' => number_format($percent_per_load, 0),
					'actual_qty' => $actual_qty,
					'actual_po' => isset($actual_po),
					'load_av_after_po' => $load_av_after_po,
					'frequency' => $frequency,
					'frequency_sort' => str_replace(",", "", $frequency),
					'frequency_ftl' => $frequency_ftl,
					'vendor_b2b_rescue' => $vendor_b2b_rescue,
					'reccnt' => isset($reccnt),
					'description_hover_notes' => $description_hover_notes,
					'nickname' => $nickname,
					'length' => $blength,
					'width' => $bwidth,
					'depth' => $bdepth,
					'cubicfootage' => $bcubicfootage,
					'loop_id_encrypt_str' => urlencode(encrypt_password($loop_id)),
				);
				/*}*/
			}
		}
	}
	$query_count++;
	// if($query_count > 3){
	// 	break;
	// }

}

//Sort By
if (isset($_REQUEST['sort_by']) && $_REQUEST['sort_by'] != "") {
	if ($_REQUEST['sort_by'] == 'low-high') {;
		$key_values = array_column($products_array, 'minfob');
		array_multisort($key_values, SORT_ASC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'high-low') {
		$key_values = array_column($products_array, 'minfob');
		array_multisort($key_values, SORT_DESC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'nearest') {
		$key_values = array_column($products_array, 'distance_sort');
		array_multisort($key_values, SORT_ASC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'furthest') {
		$key_values = array_column($products_array, 'distance_sort');
		array_multisort($key_values, SORT_DESC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'freq-most-least') {
		$key_values = array_column($products_array, 'frequency_sort');
		array_multisort($key_values, SORT_DESC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'freq-least-most') {
		$key_values = array_column($products_array, 'frequency_sort');
		array_multisort($key_values, SORT_ASC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'qty-most-least') {
		$key_values = array_column($products_array, 'qtynumbervalueorg');
		array_multisort($key_values, SORT_DESC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'qty-least-most') {
		$key_values = array_column($products_array, 'qtynumbervalueorg');
		array_multisort($key_values, SORT_ASC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'leadtime-soonest-latest') {
		$key_values = array_column($products_array, 'lead_time_days');
		array_multisort($key_values, SORT_ASC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'leadtime-latest-soonest') {
		$key_values = array_column($products_array, 'lead_time_days');
		array_multisort($key_values, SORT_DESC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'length-short-long') {
		$key_values = array_column($products_array, 'length');
		array_multisort($key_values, SORT_ASC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'length-long-short') {
		$key_values = array_column($products_array, 'length');
		array_multisort($key_values, SORT_DESC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'width-short-long') {
		$key_values = array_column($products_array, 'width');
		array_multisort($key_values, SORT_ASC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'width-long-short') {
		$key_values = array_column($products_array, 'width');
		array_multisort($key_values, SORT_DESC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'height-short-tall') {
		$key_values = array_column($products_array, 'depth');
		array_multisort($key_values, SORT_ASC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'height-tall-short') {
		$key_values = array_column($products_array, 'depth');
		array_multisort($key_values, SORT_DESC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'cu-small-big') {
		$key_values = array_column($products_array, 'cubicfootage');
		array_multisort($key_values, SORT_ASC, SORT_NUMERIC, $products_array);
	} else if ($_REQUEST['sort_by'] == 'cu-big-small') {
		$key_values = array_column($products_array, 'cubicfootage');
		array_multisort($key_values, SORT_DESC, SORT_NUMERIC, $products_array);
	}
} else {
	//$key_values = array_column($products_array, 'added_on'); 
	//array_multisort($key_values, SORT_ASC, $products_array);
}


//Pagination
// $no_of_product_per_page=15;
// if(isset($_REQUEST['active_page_id'])){
// 	$start_index=0;
// 	if($_REQUEST['active_page_id']!=1){
// 		$start_index=($_REQUEST['active_page_id']-1)*$no_of_product_per_page;
// 	}
// }
// $no_of_pages=ceil(count($products_array)/$no_of_product_per_page);
$final_product_array = array_slice($products_array, $start_index, $no_of_product_per_page);
//Final Response Of Products/ Inventory
$final_res = array(
	// 'no_of_pages'=>$no_of_pages,
	'data' => $final_product_array,
	'total_items' => count($products_array),
);

echo json_encode($final_res);
