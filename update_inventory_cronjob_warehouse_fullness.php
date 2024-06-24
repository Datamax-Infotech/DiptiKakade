<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


db_b2b();
db_query("delete from tmp_inventory_list_warehouse_fullness");

$wh_names_qry = "SELECT id, company_name from loop_warehouse where rec_type = 'Sorting' and Active = 1 order by company_name";
db();
$wh_names_res = db_query($wh_names_qry);
while ($data_wh_names = array_shift($wh_names_res)) {

    $dt_view_qry = "SELECT loop_boxes.bpallet_qty, loop_boxes.work_as_kit_box, loop_boxes.flyer, loop_boxes.boxes_per_trailer, loop_boxes.id AS I, loop_boxes.b2b_id AS B2BID, SUM(loop_inventory.boxgood) AS A, loop_warehouse.company_name AS B, loop_boxes.bdescription AS C, loop_boxes.blength AS L, loop_boxes.blength_frac AS LF, loop_boxes.bwidth AS W, loop_boxes.bwidth_frac AS WF, loop_boxes.bdepth AS D, loop_boxes.bdepth_frac as DF, loop_boxes.work_as_kit_box as kb, loop_boxes.bwall AS WALL, loop_boxes.bstrength AS ST, loop_boxes.isbox as ISBOX, loop_boxes.type as TYPE, loop_warehouse.id AS wid, loop_warehouse.pallet_space, loop_boxes.sku as SKU FROM loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id where loop_boxes.inactive = 0 and loop_warehouse.id = '" . $data_wh_names["id"] . "' GROUP BY loop_warehouse.warehouse_name, loop_inventory.box_id ORDER BY loop_warehouse.warehouse_name, loop_boxes.type, loop_boxes.blength, loop_boxes.bwidth, loop_boxes.bdepth,loop_boxes.bdescription";
    db();
    $dt_view_res = db_query($dt_view_qry);

    $preordercnt = 1;
    $tmpwarenm = "";
    $tmp_noofpallet = 0;
    $ware_house_boxdraw = "";
    while ($dt_view_row = array_shift($dt_view_res)) {
        //
        $bid = $dt_view_row["I"];
        $type = $dt_view_row["TYPE"];
        $blength = $dt_view_row["L"];
        $blength_frac = $dt_view_row["LF"];
        $tmppos_1 = strpos($blength_frac, '/');
        if ($tmppos_1 != false) {
            $blength_frac_tmp = explode("/", $blength_frac);
            $blength = $blength + intval($blength_frac_tmp[0]) / intval($blength_frac_tmp[1]);
        }

        $bwidth = $dt_view_row["W"];
        $bwidth_frac = $dt_view_row["WF"];
        $tmppos_1 = strpos($bwidth_frac, '/');
        if ($tmppos_1 != false) {
            $bwidth_frac_tmp = explode("/", $bwidth_frac);
            $bwidth = $bwidth + intval($bwidth_frac_tmp[0]) / intval($bwidth_frac_tmp[1]);
        }
        $bdepth = $dt_view_row["D"];
        $bdepth_frac = $dt_view_row["DF"];
        $tmppos_1 = strpos($bdepth_frac, '/');
        if ($tmppos_1 != false) {
            $bdepth_frac_tmp = explode("/", $bdepth_frac);
            $bdepth = $bdepth + intval($bdepth_frac_tmp[0]) / intval($bdepth_frac_tmp[1]);
        }

        //
        $kit_box =  $dt_view_row["work_as_kit_box"];

        $b2b_fob = 0;
        $b2b_cost = 0;
        $vendor_name = "";
        $inv_id = 0;
        $qry = "select vendors.name AS VN, inventory.ID as invid, inventory.vendor AS V, ulineDollar, ulineCents, costDollar, costCents from inventory INNER JOIN vendors ON inventory.vendor = vendors.id where loops_id=" . $dt_view_row["I"];
        db_b2b();
        $dt_view = db_query($qry);
        while ($sku_val = array_shift($dt_view)) {
            $inv_id = $sku_val["invid"];
            $vendor_name = $sku_val["VN"];
            $b2b_ulineDollar = round($sku_val["ulineDollar"]);
            $b2b_ulineCents = $sku_val["ulineCents"];
            $b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
            $b2b_fob = number_format($b2b_fob, 2);

            $b2b_costDollar = round($sku_val["costDollar"]);
            $b2b_costCents = $sku_val["costCents"];
            $b2b_cost = $b2b_costDollar + $b2b_costCents;
            $b2b_cost = number_format($b2b_cost, 2);
        }

        $date_dt = new DateTime(); //Today
        $dateMinus12 = $date_dt->modify("-12 months"); // Last day 12 months ago

        $sales_order_qty = 0;
        $dt_so_item = "SELECT sum(qty) as sumqty FROM loop_salesorders ";
        $dt_so_item .= " inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
        $dt_so_item .= " where (STR_TO_DATE(loop_salesorders.so_date, '%m/%d/%Y') between '" . $dateMinus12->format('Y-m-d') . "' and '" . date("Y-m-d") . "') and location_warehouse_id = " . $dt_view_row["wid"] . " and box_id = " . $dt_view_row["I"] . " and loop_transaction_buyer.ignore = 0 ";

        //echo "Qry 1 - " . $dt_so_item . "<br>";
        db();
        $dt_res_so_item = db_query($dt_so_item);

        while ($so_item_row = array_shift($dt_res_so_item)) {
            if ($so_item_row["sumqty"] > 0) {
                $sales_order_qty = $so_item_row["sumqty"];
            } else {
                $sales_order_qty = 0;
            }
        }

        if ($sales_order_qty == 0) {
            //Check in Purchasing side
            $dt_so_item = "SELECT sum(boxgood) as sumqty FROM loop_boxes_sort ";
            $dt_so_item .= " inner join loop_transaction on loop_transaction.id = loop_boxes_sort.trans_rec_id ";
            $dt_so_item .= " where (STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between '" . $dateMinus12->format('Y-m-d') . "' and '" . date("Y-m-d") . "') and loop_boxes_sort.warehouse_id = " . $dt_view_row["wid"] . " and box_id = " . $dt_view_row["I"] . " and loop_transaction.ignore = 0 ";

            //echo "Qry 2 - " . $dt_so_item . "<br>";
            db();
            $dt_res_so_item = db_query($dt_so_item);

            while ($so_item_row = array_shift($dt_res_so_item)) {
                if ($so_item_row["sumqty"] > 0) {
                    $sales_order_qty = $so_item_row["sumqty"];
                } else {
                    $sales_order_qty = 0;
                }
            }
        }
        //echo "Qty - " . $sales_order_qty . "<br>";

        if ($sales_order_qty != 0) {
            $sales_order_qty = 0;
            $dt_so_item = "SELECT sum(qty) as sumqty FROM loop_salesorders ";
            $dt_so_item .= " inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
            $dt_so_item .= " where (STR_TO_DATE(loop_salesorders.so_date, '%m/%d/%Y') between '" . $dateMinus12->format('Y-m-d') . "' and '" . date("Y-m-d") . "') and location_warehouse_id = " . $dt_view_row["wid"] . " and box_id = " . $dt_view_row["I"] . " and loop_transaction_buyer.bol_create = 0 and loop_transaction_buyer.Preorder=0 and loop_transaction_buyer.ignore = 0 ";

            //echo $dt_so_item . "<br>";
            db();
            $dt_res_so_item = db_query($dt_so_item);

            while ($so_item_row = array_shift($dt_res_so_item)) {
                if ($so_item_row["sumqty"] > 0) {
                    $sales_order_qty = $so_item_row["sumqty"];
                } else {
                    $sales_order_qty = 0;
                }
            }

            $lastmonth_val = 0;
            $lastmonth_val = isset($lastmonth_qry_array[$dt_view_row["I"]]);

            $reccnt = 0;
            if ($sales_order_qty > 0) {
                $reccnt = $sales_order_qty;
            }

            if (($dt_view_row["A"] >= $dt_view_row["boxes_per_trailer"]) && ($dt_view_row["boxes_per_trailer"] > 0)) {
                $bg = "yellow";
            }

            $pallet_val = 0;
            $pallet_val_afterpo = 0;
            //$actual_po = $dt_view_row["A"] - $inv_array[$dt_view_row["wid"]][$dt_view_row["I"]]; 
            $actual_po = $dt_view_row["A"] - $sales_order_qty;

            $pallet_space_per = "";
            if ($dt_view_row["bpallet_qty"] > 0) {
                $tmp_bpallet_qty = $dt_view_row["bpallet_qty"];
            } else {
                $tmp_bpallet_qty = 0;
            }

            $qry_upd = "Insert into tmp_inventory_list_warehouse_fullness (`actual`, `warehouse`, `vendor`, `type_ofbox`, `per_pallet`, `updated_on`, trans_id, sales_order_qty, wid, inv_id) ";
            $qry_upd .= " select '" . $dt_view_row["A"] . "', '" . $dt_view_row["B"] . "', '" . str_replace("'", '', $vendor_name) . "', '" . $dt_view_row["TYPE"];
            $qry_upd .= "', '" . $tmp_bpallet_qty . "' ";
            $qry_upd .= ", '" . date("Y-m-d H:i:s") . "', " . $dt_view_row["I"] . ", " . $sales_order_qty . ", " . $dt_view_row["wid"] . ", " . $inv_id . "";
            echo $qry_upd . "<br>";
            db_b2b();
            $dt_res_so = db_query($qry_upd);
        }
    }

    echo "Cron job process - " . $data_wh_names["company_name"] . "<br>";
}

$datewtime = date("F j, Y, g:i a");

$ddw_sql = "UPDATE tblvariable SET variablevalue = '$datewtime' where variablename = 'warehouse_fullness_run_time'";
db();
$ddw_sql_result = db_query($ddw_sql);