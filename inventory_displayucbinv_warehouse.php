<style>
.style12left {
    font-family: Arial, Helvetica, sans-serif;
    font-size: x-small;
    color:
        #333333;
    text-align: left;
}

.style12 {
    font-family: Arial, Helvetica, sans-serif;
    font-size: x-small;
    color:
        #333333;
    text-align: right;
}
</style>
<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

function timestamp_to_date(string $d): string
{
    $da = explode(" ", $d);
    $dp = explode("-", $da[0]);
    return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
}

function timestamp_to_datetime(string $d): string
{

    $da = explode(" ", $d);
    $dp = explode("-", $da[0]);
    $dh = explode(":", $da[1]);

    $x = $dp[1] . "/" . $dp[2] . "/" . $dp[0];

    if ($dh[0] == 12) {
        $x = $x . " " . ((int)$dh[0] - 0) . ":" . $dh[1] . "PM CT";
    } elseif ($dh[0] == 0) {
        $x = $x . " 12:" . $dh[1] . "AM CT";
    } elseif ($dh[0] > 12) {
        $x = $x . " " . ((int)$dh[0] - 12) . ":" . $dh[1] . "PM CT";
    } else {
        $x = $x . " " . ($dh[0]) . ":" . $dh[1] . "AM CT";
    }

    return $x;
}

$style12_val = "style12";
$bg = "#f4f4f4";
$lastmonth_qry = "SELECT box_id, sum(boxgood) as sumboxgood from loop_inventory where boxgood >0 and ";
$lastmonth_qry .= " UNIX_TIMESTAMP(add_date) >= " .  strtotime('today - 30 days') . " AND UNIX_TIMESTAMP(add_date) <= " . strtotime(date("m/d/Y")) . " group by box_id";
db();
$dt_res_so = db_query($lastmonth_qry);
while ($so_row = array_shift($dt_res_so)) {
    $lastmonth_qry_array[$so_row["box_id"]] = $so_row["sumboxgood"];
}

?>

<table cellSpacing="1" cellPadding="1" border="0" width="1200">
    <tr vAlign="center" style="white-space: nowrap;">
        <td bgColor="#e4e4e4" class="style12"><b>Actual&nbsp;<a href="#" onclick="displayucbinv(1,1);"><img
                        src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="#"
                    onclick="displayucbinv(1,2)"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
        </td>

        <td bgColor="#e4e4e4" class="style12"><b>After PO&nbsp;<a href="#" onclick="displayucbinv(2,1);"><img
                        src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="#"
                    onclick="displayucbinv(2,2)"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
        </td>

        <td bgColor="#e4e4e4" class="style12left"><b>Length&nbsp;<a href="#" onclick="displayucbinv(7,1);"><img
                        src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="#"
                    onclick="displayucbinv(7,2)"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
            </font>
        </td>
        <td bgColor="#e4e4e4" class="style12left">X</td>
        <td bgColor="#e4e4e4" class="style12left"><b>Width&nbsp;<a href="#" onclick="displayucbinv(16,1);"><img
                        src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="#"
                    onclick="displayucbinv(16,2)"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
            </font>
        </td>
        <td bgColor="#e4e4e4" class="style12left">X</td>
        <td bgColor="#e4e4e4" class="style12left"><b>Height&nbsp;<a href="#" onclick="displayucbinv(17,1);"><img
                        src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="#"
                    onclick="displayucbinv(17,2)"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
            </font>
        </td>

        <!--<td bgColor="#e4e4e4" class="style12"><b>Last Month Qty&nbsp;<a href="#" onclick="displayucbinv(3,1);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="#" onclick="displayucbinv(3,2)" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></b></td>  
-->
        <td bgColor="#e4e4e4" class="style12"><b>Walls&nbsp;<a href="#" onclick="displayucbinv(4,1);"><img
                        src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="#"
                    onclick="displayucbinv(4,2)"><img src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
        </td>
        <td bgColor="#e4e4e4" class="style12" width="150px;"><b>Description&nbsp;<a href="#"
                    onclick="displayucbinv(8,1);"><img src="images/sort_asc.jpg" width="5px;"
                        height="10px;"></a>&nbsp;<a href="#" onclick="displayucbinv(8,2)"><img
                        src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
        </td>

        <td bgColor="#e4e4e4" class="style12" width="70px"><b>Per Pallet&nbsp;<a href="#"
                    onclick="displayucbinv(10,1);"><img src="images/sort_asc.jpg" width="5px;"
                        height="10px;"></a>&nbsp;<a href="#" onclick="displayucbinv(10,2)"><img
                        src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
        </td>

        <td bgColor="#e4e4e4" class="style12" width="70px;"><b>Per Truckload&nbsp;<a href="#"
                    onclick="displayucbinv(11,1);"><img src="images/sort_asc.jpg" width="5px;"
                        height="10px;"></a>&nbsp;<a href="#" onclick="displayucbinv(11,2)"><img
                        src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b>
        </td>

        <td bgColor="#e4e4e4" class="style12" width="100px">
            <font size=1><b>Vendor&nbsp;<a href="#" onclick="displayucbinv(5,1)"><img src="images/sort_asc.jpg"
                            width="5px;" height="10px;"></a>&nbsp;<a href="#" onclick="displayucbinv(5,2);"><img
                            src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
        </td>

        <!--<td bgColor="#e4e4e4" class="style12" width="100px;"><b>Type&nbsp;<a href="#" onclick="displayucbinv(6,1);" ><img src="images/sort_asc.jpg" width="5px;" height="10px;"></a>&nbsp;<a href="#" onclick="displayucbinv(6,2)" ><img src="images/sort_desc.jpg"  width="5px;" height="10px;"></a></b></font></td>-->

        <td bgColor="#e4e4e4" class="style12" width="100px;"><b>Work as a Kit Box&nbsp;<a href="#"
                    onclick="displayucbinv(14,1)"><img src="images/sort_asc.jpg" width="5px;"
                        height="10px;"></a>&nbsp;<a href="#" onclick="displayucbinv(14,2)"><img
                        src="images/sort_desc.jpg" width="5px;" height="10px;"></a></b></font>
        </td>

    </tr>
    <?php

    $x = 0;
    $MGArray = array();
    $MGArraysort = array();
    $sql_txt = " order by type_ofbox ASC, ht ASC ";
    if ($_REQUEST["colid"] == 1) {
        if ($_REQUEST["sortflg"] == 1) {
            $sql_txt = " ORDER BY actual Asc ";
        }
        if ($_REQUEST["sortflg"] == 2) {
            $sql_txt = " ORDER BY actual DESC ";
        }
    }
    if ($_REQUEST["colid"] == 2) {
        $MGArray = array();
    }
    if ($_REQUEST["colid"] == 7) {
        $MGArray = array();
    }
    if ($_REQUEST["colid"] == 16) {
        $MGArray = array();
    }
    if ($_REQUEST["colid"] == 17) {
        $MGArray = array();
    }
    if ($_REQUEST["colid"] == 4) {
        if ($_REQUEST["sortflg"] == 1) {
            $sql_txt = " ORDER BY box_wall Asc ";
        }
        if ($_REQUEST["sortflg"] == 2) {
            $sql_txt = " ORDER BY box_wall DESC ";
        }
    }
    if ($_REQUEST["colid"] == 8) {
        if ($_REQUEST["sortflg"] == 1) {
            $sql_txt = " ORDER BY Description Asc ";
        }
        if ($_REQUEST["sortflg"] == 2) {
            $sql_txt = " ORDER BY Description DESC ";
        }
    }
    if ($_REQUEST["colid"] == 10) {
        if ($_REQUEST["sortflg"] == 1) {
            $sql_txt = " ORDER BY per_pallet Asc ";
        }
        if ($_REQUEST["sortflg"] == 2) {
            $sql_txt = " ORDER BY per_pallet DESC ";
        }
    }
    if ($_REQUEST["colid"] == 11) {
        if ($_REQUEST["sortflg"] == 1) {
            $sql_txt = " ORDER BY per_trailer Asc ";
        }
        if ($_REQUEST["sortflg"] == 2) {
            $sql_txt = " ORDER BY per_trailer DESC ";
        }
    }
    if ($_REQUEST["colid"] == 9) {
        if ($_REQUEST["sortflg"] == 1) {
            $sql_txt = " ORDER BY vendor Asc ";
        }
        if ($_REQUEST["sortflg"] == 2) {
            $sql_txt = " ORDER BY vendor DESC ";
        }
    }
    if ($_REQUEST["colid"] == 5) {
        if ($_REQUEST["sortflg"] == 1) {
            $sql_txt = " ORDER BY SKU Asc ";
        }
        if ($_REQUEST["sortflg"] == 2) {
            $sql_txt = " ORDER BY SKU DESC ";
        }
    }
    if ($_REQUEST["colid"] == 14) {
        if ($_REQUEST["sortflg"] == 1) {
            $sql_txt = " ORDER BY work_as_kit_box Asc ";
        }
        if ($_REQUEST["sortflg"] == 2) {
            $sql_txt = " ORDER BY work_as_kit_box DESC ";
        }
    }
    $warehouseid_selected = $_REQUEST["wid"];
    $dt_view_qry = "SELECT *,LWH,SUBSTRING_INDEX(`LWH`, 'x', -1) AS `ht` from tmp_inventory_list_set2_condition2 where wid = " .  $warehouseid_selected . $sql_txt;
    //order by warehouse, type_ofbox, Description
    db_b2b();
    $dt_view_res = db_query($dt_view_qry);

    // $num_rows = mysql_num_rows($dt_view_res);
    // if ($num_rows > 0) 
    $preordercnt = 1;
    $newflg = "no";
    $tmpwarenm = "";
    $tmp_noofpallet = 0;
    $ware_house_boxdraw = "";
    while ($dt_view_row = array_shift($dt_view_res)) {

        $location_city = "";
        $location_state = "";
        $location_zip = "";
        $dt_view_qry_1 = "SELECT location_city, location_state, location_zip from inventory where ID = " . $dt_view_row["inv_id"];
        db_b2b();
        $dt_view_res_1 = db_query($dt_view_qry_1);
        while ($dt_view_row_1 = array_shift($dt_view_res_1)) {
            $location_city = $dt_view_row_1["location_city"];
            $location_state = $dt_view_row_1["location_state"];
            $location_zip = $dt_view_row_1["location_zip"];
        }

        if ($newflg == "no") {
            $newflg = "yes";
    ?><tr>
        <td colspan="13" align="center">Sync on: <?php echo $dt_view_row["updated_on"]; ?></td>
    </tr><?php
                }

                $b2b_fob = $dt_view_row["min_fob"];
                $b2b_cost = $dt_view_row["cost"];
                $vendor_name = $dt_view_row["vendor"];

                $sales_order_qty = $dt_view_row["sales_order_qty"];

                //if ($dt_view_row["actual"] != 0 OR $dt_view_row["actual"] - $sales_order_qty !=0 )
                //	{
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

                if ($ware_house_boxdraw != $dt_view_row["warehouse"]) {
                    ?><tr>
        <td colspan="13">
            <hr
                style="  border: 0; height: 1px;background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));">
        </td>
    </tr>
    <?php
                }
                $ware_house_boxdraw = $dt_view_row["warehouse"];
                //
                //
                $bsize = explode("x", $dt_view_row["LWH"]);
                $length = $bsize[0];
                $width = $bsize[1];
                $height = $bsize[2];
                $actual_inventory = $dt_view_row["actual"] . $pallet_val;
                $after_actual_inventory = $actual_po . $pallet_val_afterpo;
                //

                if (!isset($_REQUEST["sortflg"])) {
        ?>
    <tr vAlign="center">
        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
            <a href='javascript:void();' id='actual_pos<?php echo $dt_view_row["trans_id"]; ?>'
                onclick="displayactualpallet(<?php echo $dt_view_row["trans_id"]; ?>);">
                <?php
                        if ($dt_view_row["actual"] < 0) {

                        ?>

                <font color="red"><?php echo number_format($dt_view_row["actual"]) . $pallet_val; ?></font>
                <?php      } else { ?>
                <font color="green"><?php echo number_format($dt_view_row["actual"]) . $pallet_val; ?>
                </font>
                <?php } ?>
            </a>
        </td>
        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
            <?php
                    if ($actual_po < 0) { ?>
            <div onclick="display_preoder_sel(<?php echo $preordercnt; ?>, <?php echo $reccnt; ?>, <?php echo $dt_view_row["trans_id"]; ?>, <?php echo $dt_view_row["wid"]; ?>)"
                style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial">
                <font color="blue">
                    <?php echo $preorder_txt; ?><?php
                                                            echo number_format($actual_po) . $pallet_val_afterpo; ?><?php echo $preorder_txt2; ?>
                </font>
            </div>
            <?php     } else { ?>
            <div onclick="display_preoder_sel(<?php echo $preordercnt; ?>, <?php echo $reccnt; ?>, <?php echo $dt_view_row["trans_id"]; ?>, <?php echo $dt_view_row["wid"]; ?>)"
                style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial">
                <font color="green"><?php echo $preorder_txt; ?><?php
                                                                            echo number_format($actual_po) . $pallet_val_afterpo;
                                                                            ?></font><?php echo $preorder_txt2; ?>
            </div> <?php } ?>
        </td>
        <td bgColor="<?php echo $bg; ?>" style="text-align: center!important;" class="<?php echo $style12_val; ?>">
            <?php echo $length; ?>
        </td>
        <td bgColor="<?php echo $bg; ?>" class="style12left">X</td>
        <td bgColor="<?php echo $bg; ?>" style="text-align: center!important;" class="<?php echo $style12_val; ?>">
            <?php echo $width; ?>
        </td>
        <td bgColor="<?php echo $bg; ?>" class="style12left">X</td>
        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>" style="text-align: center!important;">
            <?php echo $height; ?>
        </td>

        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>"><?php echo $dt_view_row["box_wall"]; ?>
        </td>

        <td bgColor="<?php echo $bg; ?>" class="style12left"><a target="_blank"
                href='manage_box_b2bloop.php?id=<?php echo $dt_view_row["trans_id"]; ?>&proc=View'
                id='box_div_main<?php echo $dt_view_row["trans_id"]; ?>'><?php echo $dt_view_row["Description"]; ?></a>
        </td>

        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>"><?php echo $dt_view_row["per_pallet"]; ?>
        </td>
        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
            <?php echo number_format($dt_view_row["per_trailer"], 0); ?></td>

        <td bgColor="<?php echo $bg; ?>" class="style12left"><?php echo $dt_view_row["vendor"]; ?></td>

        <!--  <td bgColor="<? //=$bg;
                                    ?>" class="<?php //echo $style12_val; 
                                                ?>" ><?php //echo $dt_view_row["type_ofbox"]; 
                                                        ?></td>-->

        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
            <?php echo $dt_view_row["work_as_kit_box"]; ?></td>

    </tr>
    <?php

                    if ($x == 0) {
                        $x = 1;
                        $bg = "#e4e4e4";
                    } else {
                        $x = 0;
                        $bg = "#f4f4f4";
                    }

                    if ($reccnt > 0) { ?>
    <tr id='inventory_preord_top_<?php echo $preordercnt; ?>' align="middle" style="display:none;">
        <td>&nbsp;</td>
        <td colspan="13"
            style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
            <div id="inventory_preord_middle_div_<?php echo $preordercnt; ?>"></div>
        </td>
    </tr>
    <?php     }
                }
        ?>



    <?php if ($reccnt > 0) { ?>
    <?php
            $preordercnt = $preordercnt + 1;
        }
        $MGArray[] = array(
            'actual_inventory' => $actual_inventory, 'after_actual_inventory' => $after_actual_inventory, 'actual_inv_num' => $dt_view_row["actual"], 'pallet_val' => $pallet_val, 'after_po_num' => $actual_po, 'after_pallet_val' => $pallet_val_afterpo,
            'length_inv' => trim($length), 'width_inv' => trim($width), 'height_inv' => trim($height), 'vendor' => $dt_view_row["vendor"],
            'box_wall' => $dt_view_row["box_wall"], 'description' => $dt_view_row["Description"],
            'sku' => $dt_view_row["SKU"], 'per_pallet' => $dt_view_row["per_pallet"], 'boxes_per_trailer' => $dt_view_row["per_trailer"], 'flyer' => $dt_view_row["flyer"],
            'trans_id' => $dt_view_row["trans_id"], 'work_as_kit_box' => $dt_view_row["work_as_kit_box"], 'wid' => $dt_view_row["wid"], 'preordercnt' => $preordercnt, 'reccnt' => $reccnt, 'preorder_txt' => $preorder_txt, 'preorder_txt2' => $preorder_txt2
        );

        //}

    }
    if ($_REQUEST["colid"] == 1) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['actual_inv_num'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
    }

    if ($_REQUEST["colid"] == 2) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['after_po_num'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
    }

    if ($_REQUEST["colid"] == 7) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['length_inv'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
    }

    if ($_REQUEST["colid"] == 16) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['width_inv'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
    }
    if ($_REQUEST["colid"] == 17) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['height_inv'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
    }
    if ($_REQUEST["colid"] == 4) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['box_wall'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
    }
    if ($_REQUEST["colid"] == 8) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['description'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
        }
    }
    if ($_REQUEST["colid"] == 10) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['per_pallet'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
    }
    if ($_REQUEST["colid"] == 11) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['boxes_per_trailer'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_NUMERIC, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_NUMERIC, $MGArray);
        }
    }
    if ($_REQUEST["colid"] == 5) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['vendor'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
        }
    }
    if ($_REQUEST["colid"] == 14) {
        foreach ($MGArray as $MGArraytmp) {
            $MGArraysort[] = $MGArraytmp['work_as_kit_box'];
        }
        if ($_REQUEST["sortflg"] == 1) {
            array_multisort($MGArraysort, SORT_ASC, SORT_STRING, $MGArray);
        } else {
            array_multisort($MGArraysort, SORT_DESC, SORT_STRING, $MGArray);
        }
    }
    //

    //if ($_REQUEST["colid"] == 2 || $_REQUEST["colid"] == 7 || $_REQUEST["colid"] == 16 || $_REQUEST["colid"] == 17) {
    $bg = "#f4f4f4";
    $x = 0;
    foreach ($MGArray as $MGArraytmp2) {

        if (($MGArraytmp2["actual_inv_num"] >= $MGArraytmp2["boxes_per_trailer"]) && ($MGArraytmp2["boxes_per_trailer"] > 0)) {
            $bg = "yellow";
        }/*else{
			$bg = "#f4f4f4";
		}*/

        ?>
    <tr vAlign="center">
        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
            <a href='javascript:void();' id='actual_pos<?php echo $MGArraytmp2["trans_id"]; ?>'
                onclick="displayactualpallet(<?php echo $MGArraytmp2["trans_id"]; ?>);">
                <?php
                    if ($MGArraytmp2["actual_inv_num"] < 0) {

                    ?>

                <font color="red">
                    <?php echo number_format($MGArraytmp2["actual_inv_num"]) . $MGArraytmp2["pallet_val"]; ?></font>
                <?php      } else { ?>
                <font color="green">
                    <?php echo number_format($MGArraytmp2["actual_inv_num"]) . $MGArraytmp2["pallet_val"]; ?>
                </font>
                <?php } ?>
            </a>
        </td>
        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
            <?php
                if ($MGArraytmp2["after_po_num"] < 0) { ?>
            <div onclick="display_preoder_sel(<?php echo $MGArraytmp2["preordercnt"]; ?>, <?php echo $MGArraytmp2["reccnt"]; ?>, <?php echo $MGArraytmp2["trans_id"]; ?>, <?php echo $MGArraytmp2["wid"]; ?>)"
                style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial">
                <font color="blue">
                    <?php echo $MGArraytmp2["preorder_txt"]; ?><?php
                                                                        echo number_format($MGArraytmp2["after_po_num"]) . $MGArraytmp2["after_pallet_val"]; ?><?php echo $MGArraytmp2["preorder_txt"]; ?>
                </font>
            </div>
            <?php     } else { ?>
            <div onclick="display_preoder_sel(<?php echo $MGArraytmp2["preordercnt"]; ?>, <?php echo $MGArraytmp2["reccnt"]; ?>, <?php echo $MGArraytmp2["trans_id"]; ?>, <?php echo $MGArraytmp2["wid"]; ?>)"
                style="FONT-WEIGHT: bold;FONT-SIZE: 8pt;COLOR: 006600; FONT-FAMILY: Arial">
                <font color="green"><?php echo $MGArraytmp2["preorder_txt"]; ?><?php
                                                                                        echo number_format($MGArraytmp2["after_po_num"]) . $MGArraytmp2["after_pallet_val"];
                                                                                        ?></font>
                <?php echo $MGArraytmp2["preorder_txt2"]; ?>
            </div> <?php } ?>
        </td>
        <td bgColor="<?php echo $bg; ?>" style="text-align: center!important;" class="<?php echo $style12_val; ?>">
            <?php echo $MGArraytmp2["length_inv"]; ?>
        </td>
        <td bgColor="<?php echo $bg; ?>" class="style12left">X</td>
        <td bgColor="<?php echo $bg; ?>" style="text-align: center!important;" class="<?php echo $style12_val; ?>">
            <?php echo $MGArraytmp2["width_inv"]; ?>
        </td>
        <td bgColor="<?php echo $bg; ?>" class="style12left">X</td>
        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>" style="text-align: center!important;">
            <?php echo $MGArraytmp2["height_inv"]; ?>
        </td>

        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>"><?php echo $MGArraytmp2["box_wall"]; ?>
        </td>

        <td bgColor="<?php echo $bg; ?>" class="style12left"><a target="_blank"
                href='manage_box_b2bloop.php?id=<?php echo $MGArraytmp2["trans_id"]; ?>&proc=View'
                id='box_div_main<?php echo $MGArraytmp2["trans_id"]; ?>'><?php echo $MGArraytmp2["description"]; ?></a>
        </td>

        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>"><?php echo $MGArraytmp2["per_pallet"]; ?>
        </td>
        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>">
            <?php echo number_format($MGArraytmp2["boxes_per_trailer"], 0); ?></td>

        <td bgColor="<?php echo $bg; ?>" class="style12left"><?php echo $MGArraytmp2["vendor"]; ?></td>

        <td bgColor="<?php echo $bg; ?>" class="<?php echo $style12_val; ?>" align="left">
            <?php echo $MGArraytmp2["work_as_kit_box"]; ?></td>

    </tr>
    <?php

        if ($x == 0) {
            $x = 1;
            $bg = "#e4e4e4";
        } else {
            $x = 0;
            $bg = "#f4f4f4";
        }

        if ($MGArraytmp2["reccnt"] > 0) { ?>
    <tr id='inventory_preord_top_<?php echo $MGArraytmp2["preordercnt"]; ?>' align="middle" style="display:none;">
        <td>&nbsp;</td>
        <td colspan="13"
            style="font-size:xx-small; font-family: Arial, Helvetica, sans-serif; background-color: #FAFCDF; height: 16px">
            <div id="inventory_preord_middle_div_<?php echo $MGArraytmp2["preordercnt"]; ?>"></div>
        </td>
    </tr>

    <?php
        }
    }
    //
    ?>
</table>