<?php


require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


$miles_from = "";
$ship_from2 = "";
$length = "";
$width = "";
$depth = "";
$b2b_fob = "";
$expected_loads_per_mo = "";
// $inv_id_list = "";
$locLat = "";
$shipLat = "";
$locLong = "";
$shipLong = "";
$shipfrom_city = "";
$shipfrom_state = "";
$shipfrom_zip = "";
$next_load_available_date = "";
$qty_avail = "";
$estimated_next_load = "";

if ($_REQUEST["upd_action"] == "1") {

    $x = "SELECT * FROM companyInfo WHERE ID = " . $_REQUEST["compId"];
    db_b2b();
    $dt_view_res = db_query($x);
    while ($row = array_shift($dt_view_res)) {
        //if((remove_non_numeric($row["shipZip"])) !="")
        if (($row["shipZip"]) != "") {
            $tmp_zipval = "";
            $tmp_zipval = str_replace(" ", "", $row["shipZip"]);
            if ($row["shipcountry"] == "Canada") {
                $zipShipStr = "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
            } elseif (($row["shipcountry"]) == "Mexico") {
                $zipShipStr = "SELECT * FROM zipcodes_mexico LIMIT 1";
            } else {
                $zipShipStr = "SELECT * FROM ZipCodes WHERE zip = '" . intval($row["shipZip"]) . "'";
            }

            db_b2b();
            $dt_view_res = db_query($zipShipStr);
            while ($zip = array_shift($dt_view_res)) {
                $shipLat = $zip["latitude"];
                $shipLong = $zip["longitude"];
            }
        }
    }


    $selInvDt = "SELECT *,inventory.id AS I FROM inventory WHERE id = " . $_REQUEST["favB2bId"];

    db_b2b();
    $resInvDt = db_query($selInvDt);
    $rowInvDt = array_shift($resInvDt);


    $bpallet_qty = 0;
    $boxes_per_trailer = 0;
    $box_type = "";
    $loop_id = 0;
    $qry_sku = "SELECT id, sku, bpallet_qty, boxes_per_trailer, type FROM loop_boxes WHERE b2b_id=" . $rowInvDt["I"];
    $sku = "";
    db();
    $dt_view_sku = db_query($qry_sku);
    while ($sku_val = array_shift($dt_view_sku)) {
        $loop_id = $sku_val['id'];
        $sku = $sku_val['sku'];
        $bpallet_qty = $sku_val['bpallet_qty'];
        $boxes_per_trailer = $sku_val['boxes_per_trailer'];
        $box_type = $sku_val['type'];
    }
    if ($rowInvDt["location_country"] == "Canada") {
        $tmp_zipval = str_replace(" ", "", $rowInvDt["location_zip"]);
        $zipStr = "SELECT * FROM zipcodes_canada WHERE zip = '" . $tmp_zipval . "'";
    } elseif (($rowInvDt["location_country"]) == "Mexico") {
        $zipStr = "SELECT * FROM zipcodes_mexico LIMIT 1";
    } else {
        $zipStr = "SELECT * FROM ZipCodes WHERE zip = '" . intval($rowInvDt["location_zip"]) . "'";
    }

    if ($rowInvDt["location_zip"] != "") {
        if ($rowInvDt["availability"] != "-3.5") {
            $inv_id_list .= $rowInvDt["I"] . ",";
        }

        db_b2b();
        $dt_view_res3 = db_query($zipStr);
        while ($ziploc = array_shift($dt_view_res3)) {
            $locLat = $ziploc["latitude"];
            $locLong = $ziploc["longitude"];
        }
        //	echo $locLong;
        $distLat = ($shipLat - $locLat) * 3.141592653 / 180;
        $distLong = ($shipLong - $locLong) * 3.141592653 / 180;

        $distA = Sin($distLat / 2) * Sin($distLat / 2) + Cos($shipLat * 3.14159 / 180) * Cos($locLat * 3.14159 / 180) * Sin($distLong / 2) * Sin($distLong / 2);
        //echo $inv["I"] . " " . $distA . "p <br/>"; 
        $distC = 2 * atan2(sqrt($distA), sqrt(1 - $distA));

        //To get the Actual PO, After PO
        $rec_found_box = "n";
        $actual_val = 0;
        $after_po_val = 0;
        $last_month_qty = 0;
        $pallet_val = "";
        $pallet_val_afterpo = "";
        $tmp_noofpallet = 0;
        $ware_house_boxdraw = "";
        $preorder_txt = "";
        $preorder_txt2 = "";
        $box_warehouse_id = 0;

        $qry_loc = "SELECT id, box_warehouse_id,vendor_b2b_rescue, box_warehouse_id, next_load_available_date FROM loop_boxes WHERE b2b_id=" . $_REQUEST["favB2bId"];
        db();
        $dt_view = db_query($qry_loc);
        while ($loc_res = array_shift($dt_view)) {
            $box_warehouse_id = $loc_res["box_warehouse_id"];
            $next_load_available_date = $loc_res["next_load_available_date"];

            if ($loc_res["box_warehouse_id"] == "238") {

                $vendor_b2b_rescue_id = $loc_res["vendor_b2b_rescue"];
                $get_loc_qry = "SELECT * FROM companyInfo WHERE loopid = " . $vendor_b2b_rescue_id;

                db_b2b();
                $get_loc_res = db_query($get_loc_qry);
                $loc_row = array_shift($get_loc_res);
                $shipfrom_city = $loc_row["shipCity"];
                $shipfrom_state = $loc_row["shipState"];
                $shipfrom_zip = $loc_row["shipZip"];
            } else {

                $vendor_b2b_rescue_id = $loc_res["box_warehouse_id"];
                $get_loc_qry = "SELECT * from loop_warehouse WHERE id ='" . $vendor_b2b_rescue_id . "'";
                db();
                $get_loc_res = db_query($get_loc_qry);
                $loc_row = array_shift($get_loc_res);
                $shipfrom_city = $loc_row["company_city"];
                $shipfrom_state = $loc_row["company_state"];
                $shipfrom_zip = $loc_row["company_zip"];
            }
        }

        $ship_from  = $shipfrom_city . ", " . $shipfrom_state . " " . $shipfrom_zip;
        $ship_from2 = $shipfrom_state;

        $after_po_val_tmp = 0;
        $dt_view_qry = "SELECT * FROM tmp_inventory_list_set2 WHERE trans_id = " . $rowInvDt["loops_id"] . " ORDER BY warehouse, type_ofbox, Description";
        db_b2b();
        $dt_view_res_box = db_query($dt_view_qry);
        while ($dt_view_res_box_data = array_shift($dt_view_res_box)) {
            $rec_found_box = "y";
            $actual_val = $dt_view_res_box_data["actual"];
            $after_po_val_tmp = $dt_view_res_box_data["afterpo"];
            $last_month_qty = $dt_view_res_box_data["lastmonthqty"];
        }
        if ($rec_found_box == "n") {
            $actual_val = isset($inv["actual_inventory"]);
            $after_po_val = isset($inv["after_actual_inventory"]);
            $last_month_qty = isset($inv["lastmonthqty"]);
        }

        if ($box_warehouse_id == 238) {
            $after_po_val = isset($inv["after_actual_inventory"]);
        } else {
            //if ($rec_found_box == "n"){
            //	$after_po_val = $inv["after_actual_inventory"];
            //}else{
            $after_po_val = $after_po_val_tmp;
            //}	
        }

        if ($after_po_val < 0) {
            $qty_avail =  "<font color=blue>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
        } else if ($after_po_val >= $boxes_per_trailer) {
            $qty_avail =  "<font color=green>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
        } else {
            $qty_avail =  "<font color=black>" . number_format($after_po_val, 0) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
        }

        if ($rowInvDt["lead_time"] <= 1) {
            $lead_time = "Next Day";
        } else {
            $lead_time = $rowInvDt["lead_time"] . " Days";
        }
        $estimated_next_load = "";
        $b2bstatuscolor = "";
        if ($box_warehouse_id == 238 && ($next_load_available_date != "" && $next_load_available_date != "0000-00-00")) {
            if ($rowInvDt["expected_loads_per_mo"] == 0) {
                $expected_loads_per_mo = "<font color=red> 0 </font>";
            } else {
                $expected_loads_per_mo = $rowInvDt["expected_loads_per_mo"];
            }
        }
        $b2b_status = $rowInvDt["b2b_status"];
        $st_query = "SELECT * FROM b2b_box_status WHERE status_key='" . $b2b_status . "'";
        db();
        $st_res = db_query($st_query);
        $st_row = array_shift($st_res);
        $b2bstatus_name = $st_row["box_status"];
        if ($st_row["status_key"] == "1.0" || $st_row["status_key"] == "1.1" || $st_row["status_key"] == "1.2") {
            $b2bstatuscolor = "green";
        } elseif ($st_row["status_key"] == "2.0" || $st_row["status_key"] == "2.1" || $st_row["status_key"] == "2.2") {
            $b2bstatuscolor = "orange";
        }
        if ($rowInvDt["box_urgent"] == 1) {
            $b2bstatuscolor = "red";
            $b2bstatus_name = "URGENT";
        }

        if (
            $rowInvDt["buy_now_load_can_ship_in"] == '<font color=red> Ask Purch Rep </font>' ||
            $rowInvDt["buy_now_load_can_ship_in"] == '<font color=red>Ask Purch Rep</font>'
        ) {
            $estimated_next_load = '<font color=red>Ask Rep</font>';
        } else {
            $estimated_next_load = $rowInvDt["buy_now_load_can_ship_in"];
        }

        $b2b_ulineDollar = round($rowInvDt["ulineDollar"]);
        $b2b_ulineCents = $rowInvDt["ulineCents"];
        $b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
        $b2b_fob = "$" . number_format($b2b_fob, 2);

        if ($rowInvDt["uniform_mixed_load"] == "Mixed") {
            $blength = $rowInvDt["blength_min"] . " - " . $rowInvDt["blength_max"];
            $bwidth = $rowInvDt["bwidth_min"] . " - " . $rowInvDt["bwidth_max"];
            $bdepth = $rowInvDt["bheight_min"] . " - " . $rowInvDt["bheight_max"];
        } else {
            $blength = $rowInvDt["lengthInch"];
            $bwidth = $rowInvDt["widthInch"];
            $bdepth = $rowInvDt["depthInch"];
        }
        $blength_frac = 0;
        $bwidth_frac = 0;
        $bdepth_frac = 0;

        $length = $blength;
        $width = $bwidth;
        $depth = $bdepth;

        if ($rowInvDt["lengthFraction"] != "") {
            $arr_length = explode("/", $rowInvDt["lengthFraction"]);
            if (tep_db_num_rows($arr_length) > 0) {
                $blength_frac = intval($arr_length[0]) / intval($arr_length[1]);
                $length = floatval($blength + $blength_frac);
            }
        }
        if ($rowInvDt["widthFraction"] != "") {
            $arr_width = explode("/", $rowInvDt["widthFraction"]);
            if (tep_db_num_rows($arr_width) > 0) {
                $bwidth_frac = intval($arr_width[0]) / intval($arr_width[1]);
                $width = floatval($bwidth + $bwidth_frac);
            }
        }

        if ($rowInvDt["depthFraction"] != "") {
            $arr_depth = explode("/", $rowInvDt["depthFraction"]);
            if (tep_db_num_rows($arr_depth) > 0) {
                $bdepth_frac = intval($arr_depth[0]) / intval($arr_depth[1]);
                $depth = floatval($bdepth + $bdepth_frac);
            }
        }
        $miles_from = (int) (6371 * $distC * .621371192);
    }

    $type = '';

    db();
    $resBoxType = db_query("SELECT type FROM loop_boxes WHERE b2b_id = " . $_REQUEST['favB2bId']);
    $rowBoxType = array_shift($resBoxType);
    if (in_array(strtolower(trim($rowBoxType['type'])), array_map('strtolower', array("Gaylord", "GaylordUCB", "Loop", "PresoldGaylord")))) {
        $type = 'g';
    } elseif (in_array(strtolower(trim($rowBoxType['type'])), array_map('strtolower', array("Medium", "Large", "Xlarge", "LoopShipping", "Box", "Boxnonucb", "Presold")))) {
        $type = 'sb';
    } elseif (in_array(strtolower(trim($rowBoxType['type'])), array_map('strtolower', array("SupersackUCB", "SupersacknonUCB")))) {
        $type = 'sup';
    } elseif (in_array(strtolower(trim($rowBoxType['type'])), array_map('strtolower', array("PalletsUCB", "PalletsnonUCB")))) {
        $type = 'pal';
    }

    $fav_qty_avail = $qty_avail;
    $fav_estimated_next_load = $estimated_next_load;
    $fav_expected_loads_per_mo = $expected_loads_per_mo;
    $fav_boxes_per_trailer = $boxes_per_trailer;
    $fav_fob = $b2b_fob;
    $fav_bl = $length;
    $fav_bw = $width;
    $fav_bh = $depth;
    $fav_walls = $rowInvDt["bwall"];
    $fav_desc = $rowInvDt["description"];
    $fav_shipfrom = $ship_from2;
    $boxtype = $type;
    $fav_miles = $miles_from;



    $sql = "SELECT hide_b2bid FROM clientdash_hide_items WHERE compid = " . $_REQUEST["compId"] . " AND hide_b2bid = " . $_REQUEST["favB2bId"];

    $rec_found = "no";

    db();
    $boxes_query = db_query($sql);
    while ($boxes_data = array_shift($boxes_query)) {
        $rec_found = "yes";
    }

    if ($rec_found == "no") {
        $qry = "INSERT INTO `clientdash_hide_items` (`compid`,`hide_b2bid`, `hide_qty_avail`, `hide_estimated_next_load`, `hide_expected_loads_per_mo`, `hide_boxes_per_trailer`, `hide_fob`, `hide_bl`, `hide_bw`, `hide_bh`, `hide_walls`, `hide_desc`, `hide_shipfrom`, `boxtype`, `hide_miles`, hideItems) VALUES ('" . $_REQUEST["compId"] . "','" . $_REQUEST["favB2bId"] . "', '" . $fav_qty_avail . "', '" . $fav_estimated_next_load . "', '" . $fav_expected_loads_per_mo . "', '" . $fav_boxes_per_trailer . "', '" . $fav_fob . "', '" . $fav_bl . "', '" . $fav_bw . "', '" . $fav_bh . "', '" . $fav_walls . "', '" . $fav_desc . "', '" . $fav_shipfrom . "', '" . $boxtype . "', '" . $fav_miles . "', 1 )";
        db();
        $res = db_query($qry);
        echo "done";
    } else {
        $qry = "UPDATE clientdash_hide_items SET hide_qty_avail='" . $fav_qty_avail . "', hide_estimated_next_load = '" . $fav_estimated_next_load . "', hide_expected_loads_per_mo = '" . $fav_expected_loads_per_mo . "', hide_boxes_per_trailer = '" . $fav_boxes_per_trailer . "', hide_fob = '" . $fav_fob . "', hide_bl = '" . $fav_bl . "', hide_bw ='" . $fav_bw . "', hide_bh = '" . $fav_bh . "', hide_walls = '" . $fav_walls . "', hide_desc = '" . $fav_desc . "', hide_shipfrom = '" . $fav_shipfrom . "', boxtype = '" . $boxtype . "', hide_miles = '" . $fav_miles . "', hideItems = 1 WHERE compid = " . $_REQUEST["compId"] . " AND hide_b2bid = " . $_REQUEST["favB2bId"];
        db();
        $res = db_query($qry);
        echo "done";
    }
}

if ($_REQUEST["upd_action"] == "2") {

    $sql = "DELETE FROM clientdash_hide_items WHERE compid = " . $_REQUEST["compId"] . " AND hide_b2bid = " . $_REQUEST["favB2bId"];
    db();
    db_query($sql);
    echo "done";
}