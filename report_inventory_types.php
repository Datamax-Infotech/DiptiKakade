<?php
session_start();

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Report Inventory</title>
    <style>
    .style12 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color:
            #333333;
        text-align: right;
    }

    .style12left {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color:
            #333333;
        text-align: left;
    }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <style type="text/css">
    .black_overlay {
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: gray;
        z-index: 1001;
        -moz-opacity: 0.8;
        opacity: .80;
        filter: alpha(opacity=80);
    }

    .white_content {
        display: none;
        position: absolute;
        top: 5%;
        left: 10%;
        width: 60%;
        height: 90%;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
    }
    </style>
    <script>
    function displayucbinv(colid, sortflg) {
        alert(colid);
        document.getElementById("div_ucbinv_w").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //alert(xmlhttp.responseText);
                document.getElementById("div_ucbinv_w").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "inventory_displayucbinv_new.php?colid=" + colid + "&sortflg=" + sortflg, true);
        xmlhttp.send();
    }

    function f_getPosition(e_elemRef, s_coord) {
        var n_pos = 0,
            n_offset,
            e_elem = e_elemRef;

        while (e_elem) {
            n_offset = e_elem["offset" + s_coord];
            n_pos += n_offset;
            e_elem = e_elem.offsetParent;
        }

        e_elem = e_elemRef;
        while (e_elem != document.body) {
            n_offset = e_elem["scroll" + s_coord];
            if (n_offset && e_elem.style.overflow == 'scroll')
                n_pos -= n_offset;
            e_elem = e_elem.parentNode;
        }
        return n_pos;
    }

    function displayactualpallet(boxid) {
        document.getElementById("light").innerHTML =
            "<br/><div style='text-align: center;'>Loading .....<img src='images/wait_animated.gif' /></div>";
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        var selectobject = document.getElementById("actual_pos" + boxid);
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        n_top = n_top - 200;
        document.getElementById('light').style.left = n_left + 'px';
        document.getElementById('light').style.top = n_top + 100 + 'px';

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> <br><hr>" +
                    xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "report_inventory.php?inventory_id=" + boxid + "&action=run", true);
        xmlhttp.send();
    }
    </script>
</head>

<body>
    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>
    <?php
    function format_state(string $input, string $format = ''): ?string
    {
        if (!$input || empty($input))
            return null;

        $states = array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
        );

        foreach ($states as $abbr => $name) {
            if (preg_match("/\b($name)\b/", ucwords(strtolower($input)), $match)) {
                if ('abbr' == $format) {
                    return $abbr;
                } else return $name;
            } elseif (preg_match("/\b($abbr)\b/", strtoupper($input), $match)) {
                if ('abbr' == $format) {
                    return $abbr;
                } else return $name;
            }
        }
        return null;
    }
    /////////////////////////////////////////// NEW INVENTORY SALES ORDER VALUES
    ?>
    <!--------------------------NEW INVENTORY ---------------------------------------------->

    <div id="tempval_focus" name="tempval_focus"></div>
    <div id="tempval1" name="tempval1">
    </div>

    <div id="tempval" name="tempval">

        <?php
        $bg = "#f4f4f4";
        ?>
        <?php
        $style12_val = "style12";    ?>
        <table cellSpacing="1" cellPadding="1" border="0" width="1000">

            <tr align="middle">
                <td colspan="13" class="style24" style="height: 16px"><strong>UCB Inventory Report </strong>
                </td>
            </tr>

            <tr>
                <td colspan="13">
                    <div id="div_ucbinv_w" name="div_ucbinv_w">

                        <?php
                        if (!isset($_REQUEST["sort"])) {
                            $x = 0;
                            $newflg = "no";
                            $preordercnt = 1;
                            $box_type_str_arr = array("'Gaylord','GaylordUCB', 'PresoldGaylord', 'Loop'", "'LoopShipping','Box','Boxnonucb','Presold','Medium','Large','Xlarge','Boxnonucb'", "'SupersackUCB','SupersacknonUCB'", "'DrumBarrelUCB','DrumBarrelnonUCB'", "'PalletsUCB','PalletsnonUCB'", "'Recycling','Other','Waste-to-Energy'");
                            $box_type_cnt = 0;
                            foreach ($box_type_str_arr as $box_type_str_arr_tmp) {
                                $box_type_cnt = $box_type_cnt + 1;

                                if ($box_type_cnt == 1) {
                                    $box_type = "Gaylord";
                                }
                                if ($box_type_cnt == 2) {
                                    $box_type = "Shipping Boxes";
                                }
                                if ($box_type_cnt == 3) {
                                    $box_type = "Supersacks";
                                }
                                if ($box_type_cnt == 4) {
                                    $box_type = "Drums/Barrels/IBCs";
                                }
                                if ($box_type_cnt == 5) {
                                    $box_type = "Pallets";
                                }
                                if ($box_type_cnt == 6) {
                                    $box_type = "Recycling+Other";
                                }

                                //Display nonucbboxes
                                if (isset($box_type) == "Gaylord") {
                                    $box_query = "inventory.gaylord=1 ";
                                }
                                if (isset($box_type) == "Shipping Boxes") {
                                    $box_query = "inventory.box_type = 'Boxnonucb' ";
                                }
                                if (isset($box_type) == "Supersacks") {
                                    $box_query = "inventory.box_type = 'SupersacknonUCB' ";
                                }
                                if (isset($box_type) == "Drums/Barrels/IBCs") {
                                    $box_query = "inventory.box_type = 'DrumBarrelnonUCB' ";
                                }
                                if (isset($box_type) == "Pallets") {
                                    $box_query = "inventory.box_type = 'PalletsnonUCB' ";
                                }

                                //
                                if (isset($box_query)) {
                                    $sql = "SELECT *,  inventory.id AS I, inventory.lengthInch AS L, inventory.widthInch AS W, inventory.depthInch AS D, inventory.notes AS N, inventory.date AS DT, vendors.name AS VN, inventory.vendor AS V, inventory.cubicFeet FROM inventory INNER JOIN vendors ON inventory.vendor = vendors.id WHERE $box_query and inventory.id not in (Select inv_id from tmp_inventory_list_set2) AND inventory.Active LIKE 'A' AND inventory.availability != 0 AND inventory.availability != -4 AND inventory.availability != -2 AND inventory.availability != -3.5 group by inventory.id ORDER BY inventory.depthInch ASC, inventory.description ASC";
                                }
                                //echo $sql . "<br>";
                                db_b2b();
                                $sql = $sql ?? '';
                                $dt_view_res = db_query($sql);

                                while ($inv = array_shift($dt_view_res)) {

                                    $loopsql = "SELECT * FROM loop_boxes WHERE b2b_id = " . $inv["I"];
                                    db();
                                    $loop_res = db_query($loopsql);
                                    $loop = array_shift($loop_res);
                                    if ($x == 0) {
                                        $x = 1;
                                        $bg = "#e4e4e4";
                                    } else {
                                        $x = 0;
                                        $bg = "#f4f4f4";
                                    }
                                    $tipStr = "";
                                    if ($inv["shape_rect"] == "1")
                                        $tipStr = $tipStr . " Rec ";
                                    if ($inv["shape_oct"] == "1")
                                        $tipStr = $tipStr . " Oct ";
                                    if ($inv["wall_2"] == "1")
                                        $tipStr = $tipStr . " 2W ";
                                    if ($inv["wall_3"] == "1")
                                        $tipStr = $tipStr . " 3W ";
                                    if ($inv["wall_4"] == "1")
                                        $tipStr = $tipStr . " 4W ";
                                    if ($inv["wall_5"] == "1")
                                        $tipStr = $tipStr . " 5W ";
                                    if ($inv["top_nolid"] == "1")
                                        $tipStr = $tipStr . " No Top,";
                                    if ($inv["top_partial"] == "1")
                                        $tipStr = $tipStr . " Flange Top, ";
                                    if ($inv["top_full"] == "1")
                                        $tipStr = $tipStr . " FFT, ";
                                    if ($inv["top_hinged"] == "1")
                                        $tipStr = $tipStr . " Hinge Top, ";
                                    if ($inv["top_remove"] == "1")
                                        $tipStr = $tipStr . " Lid Top, ";
                                    if ($inv["bottom_no"] == "1")
                                        $tipStr = $tipStr . " No Bottom";
                                    if ($inv["bottom_partial"] == "1")
                                        $tipStr = $tipStr . " PB w/o SS";
                                    if ($inv["bottom_partialsheet"] == "1")
                                        $tipStr = $tipStr . " PB w/ SS";
                                    if ($inv["bottom_fullflap"] == "1")
                                        $tipStr = $tipStr . " FFB";
                                    if ($inv["bottom_interlocking"] == "1")
                                        $tipStr = $tipStr . " FB";
                                    if ($inv["bottom_tray"] == "1")
                                        $tipStr = $tipStr . " Tray Bottom";
                                    if ($inv["vents_no"] == "1")
                                        $tipStr = $tipStr . "";
                                    if ($inv["vents_yes"] == "1")
                                        $tipStr = $tipStr . ", Vents";
                                    //
                                    //
                                    $bpallet_qty = 0;
                                    $boxes_per_trailer = 0;
                                    $qry = "select sku, bpallet_qty, boxes_per_trailer, bwall from loop_boxes where id=" . $loop["id"];
                                    db();
                                    $dt_view = db_query($qry);
                                    while ($sku_val = array_shift($dt_view)) {
                                        $sku = $sku_val['sku'];
                                        $bpallet_qty = $sku_val['bpallet_qty'];
                                        $boxes_per_trailer = $sku_val['boxes_per_trailer'];
                                        $bwall = $sku_val['bwall'];
                                    }

                                    $b2b_ulineDollar = round($inv["ulineDollar"]);
                                    $b2b_ulineCents = $inv["ulineCents"];
                                    $b2b_fob = $b2b_ulineDollar + $b2b_ulineCents;
                                    $b2b_fob = number_format($b2b_fob, 2);

                                    $b2b_costDollar = round($inv["costDollar"]);
                                    $b2b_costCents = $inv["costCents"];
                                    $b2b_cost = $b2b_costDollar + $b2b_costCents;
                                    $b2b_cost = number_format($b2b_cost, 2);

                                    $dt_so_item1 = "SELECT loop_salesorders.qty AS sumqty FROM loop_salesorders ";
                                    $dt_so_item1 .= " INNER JOIN loop_warehouse ON loop_salesorders.warehouse_id = loop_warehouse.id ";
                                    $dt_so_item1 .= " INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
                                    $dt_so_item1 .= " WHERE loop_salesorders.box_id = " . $inv["loops_id"] . " and loop_transaction_buyer.bol_create = 0 order by loop_salesorders.trans_rec_id asc";
                                    $sales_order_qty_new = 0;
                                    db();
                                    $dt_res_so_item1 = db_query($dt_so_item1);
                                    while ($so_item_row1 = array_shift($dt_res_so_item1)) {
                                        if ($so_item_row1["sumqty"] > 0) {
                                            $sales_order_qty_new = $so_item_row1["sumqty"];
                                        }
                                    }
                                    //
                                    $st = $inv["location_state"];
                                    $format = '';
                                    $location_state = format_state($st, $format);
                                    //
                                    $final_after_po = $inv["after_actual_inventory"];

                                    if ($inv["availability"] == "3") {
                                        $final_actual = "<b>" . $inv["actual_inventory"] . "</b>";
                                    } else {
                                        $final_actual = $inv["actual_inventory"];
                                    }
                                    if ($sales_order_qty_new > 0) {
                                        if ($inv["availability"] == "3") {
                                            $final_actual = "<font color='blue'><b>" . $inv["actual_inventory"] . "</b></font>";
                                        } else {
                                            $final_actual = "<font color='blue'>" . $inv["actual_inventory"] . "</font>";
                                        }
                                    } else {
                                        if ($inv["availability"] == "3") {
                                            $final_actual = "<b>" . $inv["actual_inventory"] . "</b>";
                                        } else {
                                            $final_actual = $inv["actual_inventory"];
                                        }
                                    }
                                    //
                                    if (trim($inv["actual_inventory"]) > 0) {
                                        $cuft = number_format(($inv["L"] * $inv["W"] * $inv["D"]) / 1728, 2);

                                        $b2b_burst = $inv["burst"];

                                        if ($box_type_cnt == 1) {
                                            $gy[] = array('b2bid' => $inv["I"], 'ect' => $b2b_burst, 'actual' => $inv["actual_inventory"], 'final_actual' => $final_actual, 'bloops_id' => $inv["loops_id"], 'bvendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'bavailability' => $inv["availability"], 'non_after_val' => $inv["after_actual_inventory"], 'final_after_po' => $final_after_po, 'blength' => $inv["L"], 'bwidth' => $inv["W"], 'bheight' => $inv["D"], 'cuft' => $cuft, 'bwall' => isset($bwall), 'bdescription' => $inv["description"], 'per_pallet' => $bpallet_qty, 'per_trailer' => $boxes_per_trailer, 'blocation_state' => $location_state, 'bfob' => $b2b_fob, 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'bty' => 'non-UCB');
                                        }
                                        if ($box_type_cnt == 2) {
                                            $sb[] = array('b2bid' => $inv["I"], 'ect' => $b2b_burst, 'actual' => $inv["actual_inventory"], 'final_actual' => $final_actual, 'bloops_id' => $inv["loops_id"], 'bvendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'bavailability' => $inv["availability"], 'final_after_po' => $final_after_po, 'non_after_val' => $inv["after_actual_inventory"], 'blength' => $inv["L"], 'bwidth' => $inv["W"], 'bheight' => $inv["D"], 'cuft' => $cuft, 'bwall' => isset($bwall), 'bdescription' => $inv["description"], 'per_pallet' => $bpallet_qty, 'per_trailer' => $boxes_per_trailer, 'blocation_state' => $location_state, 'bfob' => $b2b_fob, 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'bty' => 'non-UCB');
                                        }
                                        if ($box_type_cnt == 3) {
                                            $sup[] = array('b2bid' => $inv["I"], 'ect' => $b2b_burst, 'actual' => $inv["actual_inventory"], 'final_actual' => $final_actual, 'bloops_id' => $inv["loops_id"], 'bvendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'bavailability' => $inv["availability"], 'non_after_val' => $inv["after_actual_inventory"], 'final_after_po' => $final_after_po, 'blength' => $inv["L"], 'bwidth' => $inv["W"], 'bheight' => $inv["D"], 'cuft' => $cuft, 'bwall' => isset($bwall), 'bdescription' => $inv["description"], 'per_pallet' => $bpallet_qty, 'per_trailer' => $boxes_per_trailer, 'blocation_state' => $location_state, 'bfob' => $b2b_fob, 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'bty' => 'non-UCB');
                                        }
                                        if ($box_type_cnt == 4) {
                                            $dbi[] = array('b2bid' => $inv["I"], 'ect' => $b2b_burst, 'actual' => $inv["actual_inventory"], 'final_actual' => $final_actual, 'bloops_id' => $inv["loops_id"], 'bvendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'bavailability' => $inv["availability"], 'non_after_val' => $inv["after_actual_inventory"], 'final_after_po' => $final_after_po, 'blength' => $inv["L"], 'bwidth' => $inv["W"], 'bheight' => $inv["D"], 'cuft' => $cuft, 'bwall' => isset($bwall), 'bdescription' => $inv["description"], 'per_pallet' => $bpallet_qty, 'per_trailer' => $boxes_per_trailer, 'blocation_state' => $location_state, 'bfob' => $b2b_fob, 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $inv["vendor_b2b_rescue"], 'bty' => 'non-UCB');
                                        }
                                    }
                                } //end while nonucb boxes
                                //UCB Boxes
                                $dt_view_qry = "SELECT *, LWH, CONVERT(trim(SUBSTRING_INDEX(`LWH`, 'x', -1)) ,UNSIGNED INTEGER) AS `ht` from tmp_inventory_list_set2 where type_ofbox in (" . $box_type_str_arr_tmp . ") order by ht, description";
                                //echo $dt_view_qry;
                                //order by warehouse, type_ofbox, Description
                                db_b2b();
                                $dt_view_res = db_query($dt_view_qry);

                                // if ($num_rows > 0) 
                                $tmpwarenm = "";
                                $tmp_noofpallet = 0;
                                $ware_house_boxdraw = "";
                                $vendor_b2b_rescue = 0;
                                $b2b_burst = "";
                                while ($dt_view_row = array_shift($dt_view_res)) {
                                    $location_city = "";
                                    $location_state = "";
                                    $location_zip = "";
                                    $dt_view_qry_1 = "SELECT cubicFeet, bwall, location_city, location_state, location_zip, burst from inventory where ID = " . $dt_view_row["inv_id"];
                                    db_b2b();
                                    $dt_view_res_1 = db_query($dt_view_qry_1);
                                    while ($dt_view_row_1 = array_shift($dt_view_res_1)) {

                                        $b2b_burst = $dt_view_row_1["burst"];
                                        $location_city = $dt_view_row_1["location_city"];
                                        $st = $dt_view_row_1["location_state"];
                                        $format = '';
                                        $location_state = format_state($st, $format);
                                        $location_zip = $dt_view_row_1["location_zip"];
                                        $vendor_b2b_rescue = $dt_view_row_1["vendor_b2b_rescue"];
                                        //
                                        //$cubicFeet = $dt_view_row_1["cubicFeet"];

                                        $wall = $dt_view_row_1["bwall"];
                                    }

                                    $b2b_fob = $dt_view_row["min_fob"];
                                    $b2b_cost = $dt_view_row["cost"];
                                    $vendor_name = $dt_view_row["vendor"];

                                    $sales_order_qty = $dt_view_row["sales_order_qty"];
                                    $lastmonth_val = $dt_view_row["lastmonthqty"];

                                    $reccnt = 0;
                                    if ($sales_order_qty > 0) {
                                        $reccnt = $sales_order_qty;
                                    }

                                    $preorder_txt = "";
                                    $preorder_txt2 = "";

                                    if ($reccnt > 0) {
                                        $preorder_txt = "<u>";
                                        $preorder_txt2 = "</u>";
                                    }

                                    if (($dt_view_row["actual"] >= $dt_view_row["per_trailer"]) && ($dt_view_row["per_trailer"] > 0)) {
                                        $bg = "yellow";
                                    }

                                    $pallet_val = 0;
                                    $pallet_val_afterpo = 0;
                                    $actual_po = $dt_view_row["actual"] - $sales_order_qty;

                                    if ($dt_view_row["per_pallet"] > 0) {
                                        $pallet_val = number_format($dt_view_row["actual"] / $dt_view_row["per_pallet"], 1, '.', '');
                                        $pallet_val_afterpo = number_format($actual_po / $dt_view_row["per_pallet"], 1, '.', '');
                                    }

                                    $pallet_space_per = "";

                                    if ($pallet_val > 0) {
                                        $tmppos_1 = strpos($pallet_val, '.');
                                        if ($tmppos_1 != false) {
                                            if (intval(substr($pallet_val, strpos($pallet_val, '.') + 1, 1)) > 0) {
                                                $pallet_val_temp = $pallet_val;
                                                $pallet_val = " (" . $pallet_val_temp . ")";
                                            } else {
                                                $pallet_val_format = number_format(floatval($pallet_val), 0);
                                                $pallet_val = " (" . $pallet_val_format . ")";
                                            }
                                        } else {
                                            $pallet_val_format = number_format(floatval($pallet_val), 0);
                                            $pallet_val = " (" . $pallet_val_format . ")";
                                        }
                                    } else {
                                        $pallet_val = "";
                                    }

                                    if ($pallet_val_afterpo > 0) {
                                        $tmppos_1 = strpos($pallet_val_afterpo, '.');
                                        if ($tmppos_1 != false) {
                                            if (intval(substr($pallet_val_afterpo, strpos($pallet_val_afterpo, '.') + 1, 1)) > 0) {
                                                $pallet_val_afterpo_temp = $pallet_val_afterpo;
                                                $pallet_val_afterpo = " (" . $pallet_val_afterpo_temp . ")";
                                            } else {
                                                $pallet_val_afterpo_format = number_format(floatval($pallet_val_afterpo), 0);
                                                $pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
                                            }
                                        } else {
                                            $pallet_val_afterpo_format = number_format(floatval($pallet_val_afterpo), 0);
                                            $pallet_val_afterpo = " (" . $pallet_val_afterpo_format . ")";
                                        }
                                    } else {
                                        $pallet_val_afterpo = "";
                                    }

                                    $pallet_space_per = "";

                                    $ware_house_boxdraw = $dt_view_row["warehouse"];
                                    //
                                    //
                                    $bsize = explode("x", $dt_view_row["LWH"]);
                                    $length = $bsize[0];
                                    $width = $bsize[1];
                                    $height = $bsize[2];

                                    $length = floatval($length);
                                    $width = floatval($width);
                                    $height = floatval($height);
                                    $cubicFeet = number_format(($length * $width * $height) / 1728, 2);

                                    //
                                    //define variable actual with color code
                                    if ($dt_view_row["actual"] < 0) {
                                        $final_actual = "<font color='red'>" . number_format($dt_view_row["actual"]) . $pallet_val . "</font>";
                                    } else {
                                        $final_actual = "<font color='green'>" . number_format($dt_view_row["actual"]) . $pallet_val . "</font>";
                                    }
                                    //Define variable after po 
                                    if ($actual_po < 0) {
                                        $final_after_po = "<font color='blue'>" . $preorder_txt . number_format($actual_po) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
                                    } else {
                                        $final_after_po = "<font color='green'>" . $preorder_txt . number_format($actual_po) . $pallet_val_afterpo . $preorder_txt2 . "</font>";
                                    }

                                    if (trim($dt_view_row["actual"]) > 0) {
                                        $b2b_burst = isset($inv["burst"]);

                                        if ($box_type_cnt == 1) {
                                            $gy[] = array('b2bid' => $dt_view_row["inv_id"], 'ect' => $b2b_burst, 'actual' => $dt_view_row["actual"], 'final_actual' => $final_actual, 'bloops_id' => $dt_view_row["trans_id"], 'bvendor_b2b_rescue' => $dt_view_row["wid"], 'bavailability' => '-', 'non_after_val' => $actual_po, 'final_after_po' => $final_after_po, 'blength' => $length, 'bwidth' => $width, 'bheight' => $height, 'cuft' => $cubicFeet, 'bwall' => isset($wall), 'bdescription' => $dt_view_row["Description"], 'per_pallet' => $dt_view_row["per_pallet"], 'per_trailer' => $dt_view_row["per_trailer"], 'blocation_state' => $location_state, 'bfob' => $dt_view_row["min_fob"], 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $vendor_b2b_rescue, 'bty' => 'UCB');
                                        }
                                        if ($box_type_cnt == 2) {
                                            $sb[] = array('b2bid' => $dt_view_row["inv_id"], 'ect' => $b2b_burst, 'actual' => $dt_view_row["actual"], 'final_actual' => $final_actual, 'bloops_id' => $dt_view_row["trans_id"], 'bvendor_b2b_rescue' => $dt_view_row["wid"], 'bavailability' => '-', 'non_after_val' => $actual_po, 'final_after_po' => $final_after_po, 'blength' => $length, 'bwidth' => $width, 'bheight' => $height, 'cuft' => $cubicFeet, 'bwall' => isset($wall), 'bdescription' => $dt_view_row["Description"], 'per_pallet' => $dt_view_row["per_pallet"], 'per_trailer' => $dt_view_row["per_trailer"], 'blocation_state' => $location_state, 'bfob' => $dt_view_row["min_fob"], 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $vendor_b2b_rescue, 'bty' => 'UCB');
                                        }
                                        if ($box_type_cnt == 3) {
                                            $sup[] = array('b2bid' => $dt_view_row["inv_id"], 'ect' => $b2b_burst,  'actual' => $dt_view_row["actual"], 'final_actual' => $final_actual, 'bloops_id' => $dt_view_row["trans_id"], 'bvendor_b2b_rescue' => $dt_view_row["wid"], 'bavailability' => '-', 'non_after_val' => $actual_po, 'final_after_po' => $final_after_po, 'blength' => $length, 'bwidth' => $width, 'bheight' => $height, 'cuft' => $cubicFeet, 'bwall' => isset($wall), 'bdescription' => $dt_view_row["Description"], 'per_pallet' => $dt_view_row["per_pallet"], 'per_trailer' => $dt_view_row["per_trailer"], 'blocation_state' => $location_state, 'bfob' => $dt_view_row["min_fob"], 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $vendor_b2b_rescue, 'bty' => 'UCB');
                                        }
                                        if ($box_type_cnt == 4) {
                                            $dbi[] = array('b2bid' => $dt_view_row["inv_id"], 'ect' => $b2b_burst, 'actual' => $dt_view_row["actual"], 'final_actual' => $final_actual, 'bloops_id' => $dt_view_row["trans_id"], 'bvendor_b2b_rescue' => $dt_view_row["wid"], 'bavailability' => '-', 'non_after_val' => $actual_po, 'final_after_po' => $final_after_po, 'blength' => $length, 'bwidth' => $width, 'bheight' => $height, 'cuft' => $cubicFeet, 'bwall' => isset($wall), 'bdescription' => $dt_view_row["Description"], 'per_pallet' => $dt_view_row["per_pallet"], 'per_trailer' => $dt_view_row["per_trailer"], 'blocation_state' => $location_state, 'bfob' => $dt_view_row["min_fob"], 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $vendor_b2b_rescue, 'bty' => 'UCB');
                                        }
                                        if ($box_type_cnt == 5) {
                                            $pal[] = array('b2bid' => $dt_view_row["inv_id"], 'ect' => $b2b_burst, 'actual' => $dt_view_row["actual"], 'final_actual' => $final_actual, 'bloops_id' => $dt_view_row["trans_id"], 'bvendor_b2b_rescue' => $dt_view_row["wid"], 'bavailability' => '-', 'non_after_val' => $actual_po, 'final_after_po' => $final_after_po, 'blength' => $length, 'bwidth' => $width, 'bheight' => $height, 'cuft' => $cubicFeet, 'bwall' => isset($wall), 'bdescription' => $dt_view_row["Description"], 'per_pallet' => $dt_view_row["per_pallet"], 'per_trailer' => $dt_view_row["per_trailer"], 'blocation_state' => $location_state, 'bfob' => $dt_view_row["min_fob"], 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $vendor_b2b_rescue, 'bty' => 'UCB');
                                        }
                                        if ($box_type_cnt == 6) {
                                            $other[] = array('b2bid' => $dt_view_row["inv_id"], 'ect' => $b2b_burst, 'actual' => $dt_view_row["actual"], 'final_actual' => $final_actual, 'bloops_id' => $dt_view_row["trans_id"], 'bvendor_b2b_rescue' => $dt_view_row["wid"], 'bavailability' => '-', 'non_after_val' => $actual_po, 'final_after_po' => $final_after_po, 'blength' => $length, 'bwidth' => $width, 'bheight' => $height, 'cuft' => $cubicFeet, 'bwall' => isset($wall), 'bdescription' => $dt_view_row["Description"], 'per_pallet' => $dt_view_row["per_pallet"], 'per_trailer' => $dt_view_row["per_trailer"], 'blocation_state' => $location_state, 'bfob' => $dt_view_row["min_fob"], 'bsales_order_qty_new' => isset($bsales_order_qty_new), 'vendor_b2b_rescue' => $vendor_b2b_rescue, 'bty' => 'UCB');
                                        }
                                    }
                                }
                                $_SESSION['sortarraygy'] = isset($gy);
                                $_SESSION['sortarraysb'] = isset($sb);
                                $_SESSION['sortarraysup'] = isset($sup);
                                $_SESSION['sortarraydbi'] = isset($dbi);
                                $_SESSION['sortarraypal'] = isset($pal);
                                $_SESSION['sortarrayother'] = isset($other);
                            }
                        }
                        ?>
                        <table cellSpacing="1" cellPadding="5" border="0" width="1400">
                            <?php
                            //Display result
                            $bg = "#f4f4f4";
                            $style12_val = "style12";
                            //
                            $sorturl = "report_inventory_types.php?display=d";
                            //
                            $x = 0;
                            $box_name_arr = array('gy', 'sb', 'sup', 'dbi', 'pal', 'other');
                            foreach ($box_name_arr as $box_name) {
                                //
                                if ($box_name == "gy") {
                                    $boxtype = "Gaylord";
                                }
                                if ($box_name == "sb") {
                                    $boxtype = "Shipping Boxes";
                                }
                                if ($box_name == "sup") {
                                    $boxtype = "Supersacks";
                                }
                                if ($box_name == "dbi") {
                                    $boxtype = "Drums/Barrels/IBCs";
                                }
                                if ($box_name == "pal") {
                                    $boxtype = "Pallets";
                                }
                                if ($box_name == "other") {
                                    $boxtype = "Recycling+Other";
                                }

                                //
                                $MGarray = $_SESSION['sortarray' . $box_name];
                                $MGArraysort_I = array();
                                foreach ($MGarray as $MGArraytmp) {
                                    //$MGArraysort_I[] = $MGArraytmp['bheight'];
                                    $MGArraysort_I[] = $MGArraytmp['blocation_state'];
                                }
                                array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                //

                                if ($_REQUEST['sort'] == "b2bid") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['b2bid'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }

                                if ($_REQUEST['sort'] == "actual") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['actual'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "after_po") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['non_after_val'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "length") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['blength'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "wdth") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['bwidth'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "height") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['bheight'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "cuft") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['cuft'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "bwall") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['bwall'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "descp") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['bdescription'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "per_pal") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['per_pallet'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "per_truck") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['per_trailer'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "ship_from") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['blocation_state'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }
                                if ($_REQUEST['sort'] == "min_fob") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['bfob'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }

                                if ($_REQUEST['sort'] == "ect") {
                                    $MGArraysort_I = array();
                                    foreach ($MGarray as $MGArraytmp) {
                                        $MGArraysort_I[] = $MGArraytmp['ect'];
                                    }

                                    if ($_REQUEST['sort_order_pre'] == "ASC") {
                                        array_multisort($MGArraysort_I, SORT_ASC, $MGarray);
                                    }
                                    if ($_REQUEST['sort_order_pre'] == "DESC") {
                                        array_multisort($MGArraysort_I, SORT_DESC, $MGarray);
                                    }
                                }

                                //
                            ?>
                            <tr vAlign="center">
                                <td colspan="16" bgColor="#FF9900" height="20" align="center"><b>
                                        <?php echo isset($boxtype); ?>
                                    </b></td>
                            </tr>

                            <tr vAlign="center" style="white-space: nowrap;">

                                <td bgColor="#D8D8D8" class="style12"><b>Actual&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=actual"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=actual"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
                                </td>



                                <td bgColor="#D8D8D8" class="style12left"><b>Length&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=length"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=length"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
                                </td>
                                <td bgColor="#D8D8D8" class="style12left">X</td>
                                <td bgColor="#D8D8D8" class="style12left"><b>Width&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=wdth"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=wdth"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
                                </td>
                                <td bgColor="#D8D8D8" class="style12left">X</td>
                                <td bgColor="#D8D8D8" class="style12left"><b>Height&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=height"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=height"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
                                </td>
                                <td bgColor="#D8D8D8" class="style12" width="50px;"><b>Cu.Ft.&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=cuft"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=cuft"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
                                </td>
                                <td bgColor="#D8D8D8" class="style12"><b>Walls&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=bwall"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=bwall"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
                                </td>
                                <td bgColor="#D8D8D8" class="style12"><b>ECT&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=ect"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=ect"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
                                </td>

                                <td bgColor="#D8D8D8" class="style12" width="150px;"><b>Description&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=descp"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=descp"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
                                </td>

                                <td bgColor="#D8D8D8" class="style12"><b>B2B ID&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=b2bid"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=b2bid"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
                                </td>

                                <td bgColor="#D8D8D8" class="style12" width="70px"><b>Per Pallet&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=per_pal"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=per_pal"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
                                </td>

                                <td bgColor="#D8D8D8" class="style12" width="70px;"><b>Per Truckload&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=per_tuck"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=per_truck"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
                                </td>
                                <td bgColor="#D8D8D8" class="style12" width="100px">
                                    <font size=1><b>Ship From&nbsp;<a
                                                href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=ship_from"><img
                                                    src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                                href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=ship_from"><img
                                                    src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
                                    </font>
                                </td>
                                <td bgColor="#D8D8D8" class="style12"><b>FOB-Origin Price&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=min_fob"><img
                                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a
                                            href="<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=min_fob"><img
                                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
                                </td>
                            </tr>
                            <?php
                                //
                                $count_arry = 0;
                                foreach ($MGarray as $MGArraytmp2) {
                                    if ($MGArraytmp2["bty"] == "UCB") {
                                        if (($MGArraytmp2["actual"] >= $MGArraytmp2["per_trailer"]) && ($MGArraytmp2["per_trailer"] > 0)) {
                                            $bg = "yellow";
                                        }

                                        $boxdescription = $MGArraytmp2["bdescription"];
                                    } else {
                                        if ($MGArraytmp2["bavailability"] == "3") {
                                            $boxdescription = "<strong>" . $MGArraytmp2["bdescription"] . "</strong>";
                                        } else {
                                            $boxdescription = $MGArraytmp2["bdescription"];
                                        }
                                    }
                                ?>
                            <tr vAlign="center">
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $MGArraytmp2["actual"]; ?>
                                </td>
                                <td bgColor="<?php echo $bg; ?>" style="text-align: center!important;"
                                    class="<?php echo $style12_val; ?>">
                                    <?php echo $MGArraytmp2["blength"]; ?>
                                </td>
                                <td bgColor="<?php echo $bg; ?>" class="style12left">x</td>
                                <td bgColor="<?php echo $bg; ?>" style="text-align: center!important;"
                                    class="<?php echo $style12_val; ?>">
                                    <?php echo $MGArraytmp2["bwidth"]; ?>
                                </td>
                                <td bgColor="<?php echo $bg; ?>" class="style12left">x</td>
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>"
                                    style="text-align: center!important;">
                                    <?php echo $MGArraytmp2["bheight"]; ?>
                                </td>

                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $MGArraytmp2["cuft"]; ?>
                                </td>

                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $MGArraytmp2["bwall"]; ?>
                                </td>

                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $MGArraytmp2["ect"]; ?>
                                </td>
                                <!-- <a target="_blank" href='manage_box_b2bloop.php?id=<?php echo $MGArraytmp2["bloops_id"]; ?>&proc=View' id='box_div_main<?php echo $MGArraytmp2["bloops_id"]; ?>' > -->
                                <td bgColor="<?php echo $bg; ?>" class="style12left">
                                    <?php echo $boxdescription; ?>
                                </td>

                                <td bgColor="<?php echo $bg; ?>" style="text-align: center!important;"
                                    class="<?php echo $style12_val; ?>">
                                    <?php echo $MGArraytmp2["b2bid"]; ?>
                                </td>

                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo $MGArraytmp2["per_pallet"]; ?>
                                </td>
                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
                                    <?php echo number_format($MGArraytmp2["per_trailer"], 0); ?>
                                </td>

                                <td bgColor="<?php echo $bg; ?>" class="style12left">
                                    <?php echo $MGArraytmp2["blocation_state"]; ?>
                                </td>

                                <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">$
                                    <?php echo number_format($MGArraytmp2["bfob"], 2); ?>
                                </td>
                            </tr>
                            <?php
                                    if ($x == 0) {
                                        $x = 1;
                                        $bg = "#e4e4e4";
                                    } else {
                                        $x = 0;
                                        $bg = "#f4f4f4";
                                    }
                                    $count_arry = $count_arry + 1;
                                }
                                ?>
                            <tr vAlign="center">
                                <td colspan="16" align="center">&nbsp;</td>
                            </tr>

                            <?php

                            }

                            ?>
                            <tr align="middle">
                                <td colspan="13" style="height: 16px;background-color: #FFF;"></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <?php
    echo "<input type='hidden' id='inventory_preord_totctl' value='" . (isset($preordercnt) ? $preordercnt : "") . "' />";
    ?>
</body>

</html>