<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

function string_to_date(string $a, string $b): string
{
    $start = explode("/", $a);
    $start_date = "0000-00-00 00:00:01";
    if ($start[2] != "") {
        if ($start[2] == 11 || $start[2] == 10) $start[2] = "20" . $start["2"];
        $start_date = $start[2] . "-" . $start[0] . "-" . $start[1] . " " . $b;
    }
    return $start_date;
}


$today = date("m/d/Y");
$today_date = date("Y-m-d");
$today_crm = date("Ymd");
$warehouse_id = $_POST["warehouse_id"];
$id = $_POST["id"];
$rec_type = $_POST["rec_type"];
$user = $_COOKIE['userinitials'];
$employee = $user;
$bol_date = $today;
$trans_rec_id = $_POST["rec_id"];
$count = $_REQUEST["count"];

//to get the location warehouse and boxid which are affected for Dashboard inventory update
$tmp_array = array();

$sql_remove = "DELETE FROM loop_bol_tracking WHERE trans_rec_id = " . $trans_rec_id;
db();
$result_remove = db_query($sql_remove);

$sql_remove = "DELETE FROM loop_bol_files WHERE trans_rec_id = " . $trans_rec_id;
db();
$result_remove = db_query($sql_remove);

$sql_remove = "DELETE FROM loop_inventory WHERE in_out = 1 AND trans_rec_id = " . $trans_rec_id;
db();
$result_remove = db_query($sql_remove);

$sql = "UPDATE loop_transaction_buyer SET bol_create = 0, bol_upload_file = '', bol_date = '', bol_signed_uploaded = '0', bol_sent = '0', bol_received = '0' WHERE id = '" . $trans_rec_id . "'";
db();
$result = db_query($sql);

for ($i = 0; $i < $count; $i++) {
    $sql_sort = "INSERT INTO loop_bol_tracking (add_shipfrom_info1, add_shipfrom_info2, add_shipfrom_info3, box_id, qty, pallets, warehouse_id, location_warehouse_id, 
	trans_rec_id, quantity1, pallet1, weight1, description1, quantity2, pallet2, weight2, description2, quantity3, pallet3, 
	weight3, description3, quantity4, pallet4, weight4, description4, bol_pickupdate, bol_STL1, bol_STL2, bol_STL3, 
	bol_STL4, trailer_no, bol_pickup_time, bol_payment, bol_freight_vendor, bol_freight_biller, bol_instructions, 
	bol_date, bol_employee, add_shipp_info, add_shipp_info1, add_shipp_info2, add_shipp_info3, add_shipp_info4, bol_class, carrier_name, seal_no_box) VALUES ('" . preg_replace("/'/", "\'", $_POST["shipfroml1"]) . "', 
	'" . preg_replace("/'/", "\'", $_POST["shipfroml2"]) . "', '" . preg_replace("/'/", "\'", $_POST["shipfroml3"]) . "', '" . $_POST["box_id"][$i] . "', 
	'" . $_POST["qty"][$i] . "', '" . $_POST["pallets"][$i] . "', '" . $warehouse_id . "', '" . $_POST["location_warehouse_id"][$i] . "', '" . $trans_rec_id . "', '" . $_POST["quantity1"] . "', '" . $_POST["pallet1"] . "', '" . $_POST["weight1"] . "', '" . preg_replace("/'/", "\'", $_POST["description1"]) . "', '" . $_POST["quantity2"] . "', '" . $_POST["pallet2"] . "', '" . $_POST["weight2"] . "', '" . preg_replace("/'/", "\'", $_POST["description2"]) . "', '" . $_POST["quantity3"] . "', '" . $_POST["pallet3"] . "', '" . $_POST["weight3"] . "', '" . preg_replace("/'/", "\'", $_POST["description3"]) . "', '" . $_POST["quantity4"] . "', '" . $_POST["pallet4"] . "', '" . $_POST["weight4"] . "', '" . preg_replace("/'/", "\'", $_POST["description4"]) . "', '" . $_POST["bol_pickupdate"] . "', '" . preg_replace("/'/", "\'", $_POST["stl1"]) . "', '"  . preg_replace("/'/", "\'", $_POST["stl2"]) . "', '"  . preg_replace("/'/", "\'", $_POST["stl3"]) . "', '"  . preg_replace("/'/", "\'", $_POST["stl4"]) . "', '"  . $_POST["trailer_number"] . "', '" . $_POST["bol_pickup_time"] . "', '" . $_POST["bol_payment"] . "', 0, '" . $_POST["bol_freight_biller"] . "', '" . preg_replace("/'/", "\'", $_POST["bol_instructions"]) . "', '" . date("m/d/Y H:i:s") . "', '" . $employee . "', '" . $_POST["add_shipp_info"][$i] . "', '" . preg_replace("/'/", "\'", $_POST["add_shipp_info1"]) . "', '" . preg_replace("/'/", "\'", $_POST["add_shipp_info2"]) . "', '" . preg_replace("/'/", "\'", $_POST["add_shipp_info3"]) . "', '" . preg_replace("/'/", "\'", $_POST["add_shipp_info4"]) . "', '" . preg_replace("/'/", "\'", $_POST["bol_class"]) . "', '" . str_replace("'", "\'", $_POST["carriername"]) . "', '" . $_POST["seal_no_box"] . "')";

    db();
    $result_sort = db_query($sql_sort);

    $sql_sort = "INSERT INTO loop_inventory (add_date, box_id, warehouse_id, trans_rec_id, in_out, boxgood, employee) VALUES ('" . $today_date . "', '" . $_POST["box_id"][$i] . "', '" . $_POST["location_warehouse_id"][$i] . "', '" . $trans_rec_id . "', '1', '-" . $_POST["qty"][$i] . "', '" . $employee . "')";
    db();
    $result_sort = db_query($sql_sort);

    $tmp_array[] = array('location_warehouse_id' => $_POST["location_warehouse_id"][$i], 'box_id' => $_POST["box_id"][$i]);

    //#662 Come up with how we can STORE the ACTUAL value of UCB INVENTORY (new field on existing database table)
    $actual_qty_calculated = 0;
    $qry = "SELECT SUM(loop_inventory.boxgood) AS sumboxgood from loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id  where box_id = '" . $_POST["box_id"][$i] . "'";
    db();
    $dt_view_res = db_query($qry);
    while ($data_row = array_shift($dt_view_res)) {
        $actual_qty_calculated = $data_row["sumboxgood"];
    }

    $sql_sort = "Update inventory set actual_qty_calculated = '" . $actual_qty_calculated . "' where loops_id = '" . $_POST["box_id"][$i] . "'";
    db_b2b();
    $result_sort = db_query($sql_sort);

    $sql_sort = "Update loop_boxes set actual_qty_calculated = '" . $actual_qty_calculated . "' where id = '" . $_POST["box_id"][$i] . "'";
    db();
    $result_sort = db_query($sql_sort);
    //#662 Come up with how we can STORE the ACTUAL value of UCB INVENTORY (new field on existing database table)

}


if ($count == 0) {
    $sql_sort = "INSERT INTO loop_bol_tracking (add_shipfrom_info1, add_shipfrom_info2, add_shipfrom_info3, box_id, qty, pallets, warehouse_id, location_warehouse_id, trans_rec_id, quantity1, pallet1, weight1, description1, quantity2, pallet2, weight2, description2, quantity3, pallet3, weight3, description3, quantity4, pallet4, weight4, description4, bol_pickupdate, bol_STL1, bol_STL2, bol_STL3, bol_STL4, trailer_no, bol_pickup_time, bol_payment, bol_freight_vendor, bol_freight_biller, bol_instructions, bol_date, bol_employee, add_shipp_info, add_shipp_info1, add_shipp_info2, add_shipp_info3, add_shipp_info4, bol_class, carrier_name, seal_no_box) VALUES ('" . preg_replace("/'/", "\'", $_POST["shipfroml1"]) . "', '" . preg_replace("/'/", "\'", $_POST["shipfroml2"]) . "', '" . preg_replace("/'/", "\'", $_POST["shipfroml3"]) . "', '" . $_POST["box_id"][$i] . "', '" . $_POST["qty"][$i] . "', '" . $_POST["pallets"][$i] . "', '" . $warehouse_id . "', '71', '" . $trans_rec_id . "', '" . $_POST["quantity1"] . "', '" . $_POST["pallet1"] . "', '" . $_POST["weight1"] . "', '" . preg_replace("/'/", "\'", $_POST["description1"]) . "', '" . $_POST["quantity2"] . "', '" . $_POST["pallet2"] . "', '" . $_POST["weight2"] . "', '" . preg_replace("/'/", "\'", $_POST["description2"]) . "', '" . $_POST["quantity3"] . "', '" . $_POST["pallet3"] . "', '" . $_POST["weight3"] . "', '" . preg_replace("/'/", "\'", $_POST["description3"]) . "', '" . $_POST["quantity4"] . "', '" . $_POST["pallet4"] . "', '" . $_POST["weight4"] . "', '" . preg_replace("/'/", "\'", $_POST["description4"]) . "', '" . $_POST["bol_pickupdate"] . "', '" . preg_replace("/'/", "\'", $_POST["stl1"]) . "', '"  . preg_replace("/'/", "\'", $_POST["stl2"]) . "', '"  . preg_replace("/'/", "\'", $_POST["stl3"]) . "', '"  . preg_replace("/'/", "\'", $_POST["stl4"]) . "', '"  . $_POST["trailer_number"] . "', '" . $_POST["bol_pickup_time"] . "', '" . $_POST["bol_payment"] . "', 0, '" . $_POST["bol_freight_biller"] . "', '" . preg_replace("/'/", "\'", $_POST["bol_instructions"]) . "', '" . date("m/d/Y H:i:s") . "', '" . $employee . "', '" . preg_replace("/'/", "\'", $_POST["add_shipp_info"][$i]) . "', '" . preg_replace("/'/", "\'", $_POST["add_shipp_info1"]) . "', '" . preg_replace("/'/", "\'", $_POST["add_shipp_info2"]) . "', '" . preg_replace("/'/", "\'", $_POST["add_shipp_info3"]) . "', '" . preg_replace("/'/", "\'", $_POST["add_shipp_info4"]) . "', '" . preg_replace("/'/", "\'", $_POST["bol_class"]) . "', '" . str_replace("'", "\'", $_POST["carriername"]) . "', '" . $_POST["seal_no_box"] . "')";

    db();
    $result_sort = db_query($sql_sort);

    $loc_warehouse_id = 71;
} else {
    $loc_warehouse_id = $_POST["location_warehouse_id"][0];
}

$filequery = "INSERT INTO loop_bol_files (file_name) VALUES ('0')";
$fileresult = db_query($filequery);


$sql_bols = "SELECT * FROM loop_bol_files ORDER BY id DESC";
db();
$result_bols = db_query($sql_bols);
$bols_row = array_shift($result_bols);

$bol_number = $bols_row["id"];


$sql = "UPDATE loop_transaction_buyer SET bol_create = 1 WHERE id = '" . $trans_rec_id . "'";
db();
$result = db_query($sql);

foreach ($tmp_array as $tmp_array2) {
    $dt_so_item = "Select unqid from loop_boxes_warehouse_qty where location_warehouse_id = " . $tmp_array2["location_warehouse_id"] . " and box_id = " . $tmp_array2["box_id"];
    db();
    $dt_res_so_item = db_query($dt_so_item);
    $reccnt = tep_db_num_rows($dt_res_so_item);

    $dt_so_item = "SELECT sum(qty) as sumqty FROM loop_salesorders ";
    $dt_so_item .= " inner join loop_transaction_buyer on loop_transaction_buyer.id = loop_salesorders.trans_rec_id ";
    $dt_so_item .= " left outer join loop_bol_files on loop_bol_files.trans_rec_id = loop_salesorders.trans_rec_id ";
    $dt_so_item .= " where location_warehouse_id = " . $tmp_array2["location_warehouse_id"] . " and box_id = " . $tmp_array2["box_id"] . " and loop_transaction_buyer.bol_create = 0";
    //echo $dt_so_item . "<br>";
    db();
    $dt_res_so_item = db_query($dt_so_item);
    $recfound = "no";
    while ($so_item_row = array_shift($dt_res_so_item)) {
        $recfound = "yes";
        if ($so_item_row["sumqty"] > 0) {
            $tmp_sumqty = $so_item_row["sumqty"];
        } else {
            $tmp_sumqty = 0;
        }
        if ($reccnt > 0) {
            $ins_qry = "Update loop_boxes_warehouse_qty set qty = " . $tmp_sumqty . " where location_warehouse_id = " . $tmp_array2["location_warehouse_id"] . " and box_id = " . $tmp_array2["box_id"];
        } else {
            $ins_qry = "Insert into loop_boxes_warehouse_qty (location_warehouse_id, box_id, qty) values (" . $tmp_array2["location_warehouse_id"] . "," . $tmp_array2["box_id"] . "," . $tmp_sumqty . ")";
        }
        //echo $ins_qry . "<br>";
        db();
        db_query($ins_qry);
    }
    if ($recfound == "no") {
        if ($reccnt > 0) {
            $ins_qry = "Update loop_boxes_warehouse_qty set qty = 0 where location_warehouse_id = " . $tmp_array2["location_warehouse_id"] . " and box_id = " . $tmp_array2["box_id"];
        } else {
            $ins_qry = "Insert into loop_boxes_warehouse_qty (location_warehouse_id, box_id, qty) values (" . $tmp_array2["location_warehouse_id"] . "," . $tmp_array2["box_id"] . ", 0)";
        }
        //echo "sec: " . $ins_qry . "<br>";
        db();
        db_query($ins_qry);
    }
}

$virtual_inventory_trans_id = $_REQUEST["virtual_inventory_trans_id"];

if ($virtual_inventory_trans_id > 0) {

    db();
    $result_sort = db_query("DELETE FROM loop_boxes_sort WHERE trans_rec_id = " . $virtual_inventory_trans_id);
    db();
    $result_sort = db_query("DELETE FROM loop_inventory WHERE in_out = 0 AND trans_rec_id = " . $virtual_inventory_trans_id);
    for ($i = 0; $i < $count; $i++) {
        $sql_sort = "INSERT INTO loop_boxes_sort(sort_date, warehouse_id, trans_rec_id, box_id, boxgood, boxbad, sort_boxgoodvalue, sort_boxbadvalue, boxscrap, boxnotes, employee ) VALUES ( '" . $today . "', '" . $_POST["location_warehouse_id"][$i] . "', '" . $virtual_inventory_trans_id . "', '" . $_POST["box_id"][$i] . "', '" . $_POST["qty"][$i] . "', '" . "0" . "', '" . $_POST["cost"][$i] . "', '" . "0" . "', '" . "0" . "', '" . "Entered via BOL" . "', '" . $employee . "')";
        db();
        $result_sort = db_query($sql_sort);

        $ins_id = tep_db_insert_id();

        $sql_inv = "INSERT INTO loop_inventory  ( add_date, warehouse_id, sort_id, trans_rec_id, box_id, boxgood, boxbad, inventory_boxgoodvalue, inventory_boxbadvalue, employee ) VALUES ( '" . $today_date . "', '" . $_POST["location_warehouse_id"][$i] . "', '" . $ins_id . "', '" . $virtual_inventory_trans_id . "', '" . $_POST["box_id"][$i] . "', '" . $_POST["qty"][$i] . "', '" . "0" . "', '" . $_POST["cost"][$i] . "', '" . "0" . "', '" . $employee . "')";
        db();
        $result_inv = db_query($sql_inv);

        //#662 Come up with how we can STORE the ACTUAL value of UCB INVENTORY (new field on existing database table)
        $actual_qty_calculated = 0;
        $qry = "SELECT SUM(loop_inventory.boxgood) AS sumboxgood from loop_inventory INNER JOIN loop_warehouse ON loop_inventory.warehouse_id = loop_warehouse.id INNER JOIN loop_boxes ON loop_inventory.box_id = loop_boxes.id  where box_id = '" . $_POST["box_id"][$i] . "'";
        db();
        $dt_view_res = db_query($qry);
        while ($data_row = array_shift($dt_view_res)) {
            $actual_qty_calculated = $data_row["sumboxgood"];
        }

        $sql_sort = "Update inventory set actual_qty_calculated = '" . $actual_qty_calculated . "' where loops_id = '" . $_POST["box_id"][$i] . "'";
        db_b2b();
        $result_sort = db_query($sql_sort);

        $sql_sort = "Update loop_boxes set actual_qty_calculated = '" . $actual_qty_calculated . "' where id = '" . $_POST["box_id"][$i] . "'";
        db();
        $result_sort = db_query($sql_sort);
        //#662 Come up with how we can STORE the ACTUAL value of UCB INVENTORY (new field on existing database table)
    }

    $sql = "UPDATE loop_transaction SET sort_entered = 1 WHERE id = '" . $virtual_inventory_trans_id . "'";
    db();
    $result = db_query($sql);

    $sql_q = "SELECT pr_date FROM loop_transaction where id = '" . $virtual_inventory_trans_id . "'";
    $rec_found = "n";
    db();
    $result_q = db_query($sql_q);
    while ($myrowsel = array_shift($result_q)) {
        if ($myrowsel["pr_date"] == "") {
            $rec_found = "y";
            $sql = "UPDATE loop_transaction SET pr_requestby = '', pr_requestdate = '" . $today . "', pr_requestdate_php = '" . string_to_date($today, "00:00:00") . "', pr_pickupdate = '" . $_POST["bol_pickupdate"] . "', pr_dock = '', pr_trailer = '" . $_POST["trailer_number"] . "', pr_employee = '" . $employee . "', pr_date = '" . $today . "' WHERE id = '" . $virtual_inventory_trans_id . "'";
            db();
            $result = db_query($sql);
        }
    }

    $sql = "UPDATE loop_transaction SET pa_vendor = '" . $_REQUEST["bol_freight_vendor"] . "', pa_warehouse = '238', pa_pickupdate = '" . $_POST["bol_pickupdate"] . "', pa_publicnotes = '', pa_internalnotes = 'Created via the buyer BOL', pa_employee = '" . $employee . "', pa_date = '" . $today . "' WHERE id = '" . $virtual_inventory_trans_id . "'";

    db();
    $result = db_query($sql);
}

$message = "<strong>Note for Transaction # ";
$message .=  $trans_rec_id;
$message .= "</strong>: ";
$message .=  $employee;
$message .= " entered a BOL on ";
$message .= $bol_date;

$thepdf = "<html>
<head>
<meta http-equiv=Content-Type content=\"text/html; charset=windows-1252\">
<meta name=Generator content=\"Microsoft Word 12 (filtered)\">
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:Wingdings;
	panose-1:5 0 0 0 0 0 0 0 0 0;}
@font-face
	{font-family:\"Cambria Math\";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:Tahoma;
	panose-1:2 11 6 4 3 5 4 4 2 4;}
	/* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
h1
	{margin-top:0in;
	margin-right:0in;
	margin-bottom:4.0pt;
	margin-left:0in;
	text-align:center;
	font-size:10.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	text-transform:uppercase;}
h2
	{margin-top:0in;
	margin-right:0in;
	margin-bottom:4.0pt;
	margin-left:0in;
	text-align:right;
	font-size:10.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:normal;}
h3
	{margin-top:0in;
	margin-right:0in;
	margin-bottom:4.0pt;
	margin-left:0in;
	font-size:10.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:normal;}
h4
	{margin-top:3.0pt;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:0in;
	margin-bottom:.0001pt;
	text-align:center;
	page-break-after:avoid;
	font-size:10.0pt;
	font-family:\"Arial\",\"sans-serif\";}
h5
	{margin-top:3.0pt;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:0in;
	margin-bottom:.0001pt;
	page-break-after:avoid;
	font-size:10.0pt;
	font-family:\"Arial\",\"sans-serif\";}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{margin:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
p.SectionTitle, li.SectionTitle, div.SectionTitle
	{mso-style-name:\"Section Title\";
	margin:0in;
	margin-bottom:.0001pt;
	text-align:center;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	text-transform:uppercase;
	font-weight:bold;}
p.FinePrint, li.FinePrint, div.FinePrint
	{mso-style-name:\"Fine Print\";
	mso-style-link:\"Fine Print Char\";
	margin:0in;
	margin-bottom:.0001pt;
	font-size:6.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
span.FinePrintChar
	{mso-style-name:\"Fine Print Char\";
	mso-style-link:\"Fine Print\";
	font-family:\"Tahoma\",\"sans-serif\";}
p.Centered, li.Centered, div.Centered
	{mso-style-name:Centered;
	margin:0in;
	margin-bottom:.0001pt;
	text-align:center;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
p.Bold, li.Bold, div.Bold
	{mso-style-name:Bold;
	mso-style-link:\"Bold Char\";
	margin:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
p.CheckBox, li.CheckBox, div.CheckBox
	{mso-style-name:\"Check Box\";
	mso-style-link:\"Check Box Char\";
	margin:0in;
	margin-bottom:.0001pt;
	font-size:10.0pt;
	font-family:Wingdings;
	color:#333333;}
span.CheckBoxChar
	{mso-style-name:\"Check Box Char\";
	mso-style-link:\"Check Box\";
	font-family:Wingdings;
	color:#333333;}
p.LightGreylines, li.LightGreylines, div.LightGreylines
	{mso-style-name:\"Light Grey lines\";
	mso-style-link:\"Light Grey lines Char Char\";
	margin:0in;
	margin-bottom:.0001pt;
	font-size:6.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	color:#999999;}
span.LightGreylinesCharChar
	{mso-style-name:\"Light Grey lines Char Char\";
	mso-style-link:\"Light Grey lines\";
	font-family:\"Tahoma\",\"sans-serif\";
	color:#999999;}
span.BoldChar
	{mso-style-name:\"Bold Char\";
	mso-style-link:Bold;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
p.Terms, li.Terms, div.Terms
	{mso-style-name:Terms;
	margin-top:2.0pt;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";}
p.ShipperSignature, li.ShipperSignature, div.ShipperSignature
	{mso-style-name:\"Shipper Signature\";
	mso-style-link:\"Shipper Signature Char\";
	margin-top:2.0pt;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:0in;
	margin-bottom:.0001pt;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
span.ShipperSignatureChar
	{mso-style-name:\"Shipper Signature Char\";
	mso-style-link:\"Shipper Signature\";
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
p.BarCode, li.BarCode, div.BarCode
	{mso-style-name:\"Bar Code\";
	margin-top:4.0pt;
	margin-right:0in;
	margin-bottom:4.0pt;
	margin-left:0in;
	text-align:center;
	font-size:12.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	color:gray;
	text-transform:uppercase;
	font-weight:bold;}
p.BoldCentered, li.BoldCentered, div.BoldCentered
	{mso-style-name:\"Bold Centered\";
	margin:0in;
	margin-bottom:.0001pt;
	text-align:center;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
p.Signatureheading, li.Signatureheading, div.Signatureheading
	{mso-style-name:\"Signature heading\";
	margin-top:0in;
	margin-right:0in;
	margin-bottom:6.0pt;
	margin-left:0in;
	font-size:8.0pt;
	font-family:\"Tahoma\",\"sans-serif\";
	font-weight:bold;}
-->
</style>

</head>

<body lang=EN-US>

<div class=Section1>

<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 align=left
 width=732 style='width:549.0pt;border-collapse:collapse;border:none;
 margin-left:7.1pt;margin-right:7.1pt'>
<tr style='height:23.05pt'>
 <td colspan=2 style='width:66.00pt;border-bottom:1.0pt solid black;
  padding:.7pt 4.3pt .7pt 4.3pt;height:23.05pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><img src=\"image001new.jpg\" width='70px' height='77px'></span></p>
  </td>
  <td colspan=3 style='width:76.05pt;border-bottom:1.0pt solid black;
  padding:.7pt 4.3pt .7pt 4.3pt;height:23.05pt'>
  <h3>Date: " . $_POST["bol_pickupdate"] . "</h3>
  </td>
  <td colspan=11 style='border-bottom:
  1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;height:23.05pt'>
  <h1>Bill of Lading - Short Form - Not Negotiable</h1>
  </td>
  <td colspan=2 style='border-bottom:1.0pt solid black;
  padding:.7pt 4.3pt .7pt 4.3pt;height:23.05pt'>
  <h2>Page 1 of 1</h2>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=365 colspan=9 style='width:273.4pt;border:1.0pt solid black;
  border-top:none;background:#E6E6E6;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=SectionTitle>Ship From</p>
  </td>
  <td width=367 colspan=9 style='width:275.6pt;border-right:1.0pt solid black;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Bold>Bill of Lading Number: " . $bol_number . "-" . $trans_rec_id . "</p>
  </td>
 </tr>
 <tr style='height:8.8pt'>
  <td width=365 colspan=9 valign=bottom style='width:273.4pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p><p class=MsoNormal>";
$total_boxes = 0;
$total_weight = 0;
$total_box_weight = 0;

$thepdf .= $_POST["shipfroml1"] . "<BR>" . $_POST["shipfroml2"] . "<BR>" . $_POST["shipfroml3"] . "</p>
  </td>
  <td width=367 colspan=9 style='width:275.6pt;border-top:none;border-left:
  none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:
  2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=BarCode>Bar Code Space</p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=365 colspan=9 style='width:273.4pt;border:1.0pt solid black;
  border-top:none;background:#E6E6E6;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=SectionTitle>Ship To</p>
  </td>
  <td width=367 colspan=9 style='width:275.6pt;border-right:1.0pt solid black;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Bold>Carrier Name: </p><p class=MsoNormal>";

$thepdf .= $_POST["carriername"] . "</p></td>
 </tr>
 <tr style='height:8.8pt'>
  <td width=365 colspan=9 valign=top style='width:273.4pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p>
  <p class=MsoNormal>" . $_POST["stl1"] . "<BR>" . $_POST["stl2"] . "<BR>" . $_POST["stl3"] . "<BR>" . $_POST["stl4"] . "</p>
  </td>
  <td width=367 colspan=9 valign=top style='width:275.6pt;border-top:none;
  border-left:none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;
  padding:2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=MsoNormal>Trailer number:" . $_POST["trailer_number"] . "</p>
  <p class=MsoNormal>Seal #:" . $_POST["seal_no_box"] . "</p>
  <p class=MsoNormal>Class:" . $_POST["bol_class"] . "</p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=365 colspan=9 style='width:273.4pt;border:1.0pt solid black;
  border-top:none;background:#E6E6E6;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=SectionTitle>Third Party Freight Charges Bill to:</p>
  </td>
  <td width=367 colspan=9 style='width:275.6pt;border-right:1.0pt solid black;
  padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Bold>SCAC:</p>
  </td>
 </tr>
 <tr style='height:24.45pt'>
  <td width=365 colspan=9 valign=top style='width:273.4pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:24.45pt'>";
if ($_POST["bol_freight_biller"] == 298) {
    $thepdf .= "<p class=MsoNormal>Consignee/Receiver responsible for all freight charges</p>";
} else {
    $sql_freightbiller = "SELECT * FROM loop_freightvendor WHERE id = '" . $_POST["bol_freight_biller"] . "'";
    db();
    $result_freightbiller = db_query($sql_freightbiller);
    $freightbiller_row = array_shift($result_freightbiller);
    $thepdf .= "<p class=MsoNormal>" . $freightbiller_row["company_name"] . "</p>
	  <p class=MsoNormal>" . $freightbiller_row["company_address1"] . $freightbiller_row["company_address2"] . "</p>
	  <p class=MsoNormal>" . $freightbiller_row["company_city"] . ", " . $freightbiller_row["company_state"] . " " . $freightbiller_row["company_zip"] . "</p>
	  <p class=MsoNormal>" . $freightbiller_row["company_phone"] . "</p>";
}

$thepdf .= "</td>
  <td width=367 colspan=9 valign=top style='width:275.6pt;border-top:none;
  border-left:none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;
  padding:2.15pt 4.3pt 2.15pt 4.3pt;height:24.45pt'>
  <p class=MsoNormal>Pro Number:</p>
  <p class=BarCode>Bar Code Space</p>
  <p class=MsoNormal>&nbsp;</p>
  </td>
 </tr>
 <tr style='height:8.8pt'>
  <td width=365 colspan=9 rowspan=2 valign=top style='width:273.4pt;border:
  1.0pt solid black;border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;
  height:8.8pt'>
  <p class=Bold>Special Instructions: </p>
  <p class=MsoNormal>" . $_POST["bol_instructions"] . "</p>
  </td>
  <td width=367 colspan=9 valign=top style='width:275.6pt;border-top:none;
  border-left:none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;
  padding:.7pt 4.3pt .7pt 4.3pt;height:8.8pt'>
  <p class=Bold>Freight Charge Terms <span class=FinePrintChar><span
  style='font-size:6.0pt'>(Freight charges are prepaid unless marked otherwise):</span></span></p>
  <p class=Terms>Prepaid <input type=checkbox> Collect <input type=checkbox> 3rd Party <input type=checkbox></p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=367 colspan=9 style='width:275.6pt;border-top:none;border-left:
  none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:
  2.9pt 4.3pt 2.15pt 4.3pt;height:.2in'>
  <p class=MsoNormal><input type=checkbox> Master bill of lading
  with attached underlying bills of lading.</p>
  </td>
 </tr>
 <tr style='height:.2in'>
  <td width=732 colspan=18 style='width:549.0pt;border:1.0pt solid black;
  border-top:none;background:#E6E6E6;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=SectionTitle>Customer Order Information</p>
  </td>
 </tr>
 <tr style='height:.15in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt 2.15pt 4.3pt;height:.15in'>
  <p class=Bold>Customer Order No.</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered>Unit</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered># of Packages</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered>Type</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered>Weight</p>
  </td>
  <td width=48 colspan=2 style='width:28.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered>Pallet/Slip<br>
  (circle one)</p>
  </td>
  <td width=90 colspan=5 style='width:70.1pt;border-top:none;border-left:
  none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:
  .7pt 4.3pt 2.15pt 4.3pt;height:.15in'>
  <p class=Bold>Additional Shipper Information</p>
  </td>
 </tr>";
$total_boxes = 0;
$total_box_weight = 0;
$total_weight = 0;
for ($i = 0; $i < $count; $i++) {
    $sql_box = "SELECT * FROM loop_boxes WHERE id = " . $_POST["box_id"][$i];
    db();
    $result_box = db_query($sql_box);
    $box_row = array_shift($result_box);

    if ($_POST["qty"][$i] > 0) {
        $thepdf .= "<tr style='height:.2in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>";

        //$thepdf .= $box_row["sku"]."</p></td>

        if ($box_row["isbox"] == 'Y') $thepdf .= $box_row["blength"] . " " . $box_row["blength_frac"] . " x " . $box_row["bwidth"] . " " . $box_row["bwidth_frac"] . " x " . $box_row["bdepth"] . " " . $box_row["bdepth_frac"] . " ";
        $thepdf .= $box_row["bdescription"] . "</p></td>
	
	<td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>";
        if ($_POST["pallets"][$i] > 0) {
            if ($_POST["pallets"][$i] == 1) {
                $thepdf .= $_POST["pallets"][$i] . " Pallet";
            } else {
                $thepdf .= $_POST["pallets"][$i] . " Pallets";
            }
            $total_pallets += $_POST["pallets"][$i];
        }

        $pdtype = "";

        if ($box_row["type"] == "Medium") {
            $pdtype = 'Shipping Boxes';
        }
        if ($box_row["type"] == "Large") {
            $pdtype = 'Shipping Boxes';
        }
        if ($box_row["type"] == "Xlarge") {
            $pdtype = 'Shipping Boxes';
        }
        if ($box_row["type"] == "Gaylord") {
            $pdtype = 'Gaylord Totes';
        }
        if ($box_row["type"] == "GaylordUCB") {
            $pdtype = 'Gaylord Totes';
        }
        if ($box_row["type"] == "Loop") {
            $pdtype = 'Gaylord Totes';
        }
        if ($box_row["type"] == "PresoldGaylord") {
            $pdtype = 'Gaylord Totes';
        }
        if ($box_row["type"] == "LoopShipping") {
            $pdtype = 'Shipping Boxes';
        }
        if ($box_row["type"] == "Box") {
            $pdtype = 'Shipping Boxes';
        }
        if ($box_row["type"] == "Boxnonucb") {
            $pdtype = 'Shipping Boxes';
        }
        if ($box_row["type"] == "Presold") {
            $pdtype = 'Shipping Boxes';
        }
        if ($box_row["type"] == "Recycling") {
            $pdtype = 'Recycling';
        }
        if ($box_row["type"] == "SupersackUCB") {
            $pdtype = 'Supersacks';
        }
        if ($box_row["type"] == "SupersacknonUCB") {
            $pdtype = 'Supersacks';
        }
        if ($box_row["type"] == "DrumBarrelUCB") {
            $pdtype = 'Drums';
        }
        if ($box_row["type"] == "DrumBarrelnonUCB") {
            $pdtype = 'Drums';
        }
        if ($box_row["type"] == "PalletsUCB") {
            $pdtype = 'Pallets';
        }
        if ($box_row["type"] == "PalletsnonUCB") {
            $pdtype = 'Pallets';
        }
        if ($box_row["type"] == "Waste-to-Energy") {
            $pdtype = 'Waste to Energy';
        }
        if ($box_row["type"] == "Other") {
            $pdtype = 'Other';
        }
        $thepdf .= "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["qty"][$i], 0) . "</p>
  </td>
	<td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . $pdtype . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format(($_POST["qty"][$i] * $box_row["bweight"] + 40 * $_POST["pallets"][$i]), 0) . "</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Y</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>N</p>
  </td>
  <td width=90 colspan=5 style='width:70.1pt;border-top:none;border-left:
  none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["add_shipp_info"][$i] . "</p>
  </td>
 </tr>";
    }
    $total_boxes += $_POST["qty"][$i];
    $total_box_weight += ($_POST["qty"][$i] * $box_row["bweight"]);
    $total_weight += ($_POST["qty"][$i] * $box_row["bweight"] + 40 * $_POST["pallets"][$i]);
}
if ($_POST["quantity1"] . $_POST["pallet1"] . $_POST["weight1"] . $_POST["description1"] != "") {
    $thepdf .= "<tr style='height:.2in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["description1"] . "</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . $_POST["pallet1"] . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity1"], 0) . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal></p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["weight1"], 0) . "</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Y</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>N</p>
  </td>
  <td width=241 colspan=5 style='width:181.1pt;border-top:none;border-left:
  none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["add_shipp_info1"] . "</p>
  </td>
 </tr>";
}
if ($_POST["quantity2"] . $_POST["pallet2"] . $_POST["weight2"] . $_POST["description2"] != "") {
    $thepdf .= "<tr style='height:.2in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["description2"] . "</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . $_POST["pallet2"] . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity2"], 0) . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal></p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["weight2"], 0) . "</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Y</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>N</p>
  </td>
  <td width=241 colspan=5 style='width:181.1pt;border-top:none;border-left:
  none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["add_shipp_info2"] . "</p>
  </td>
 </tr>";
}
if ($_POST["quantity3"] . $_POST["pallet3"] . $_POST["weight3"] . $_POST["description3"] != "") {
    $thepdf .= "<tr style='height:.2in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["description3"] . "</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . $_POST["pallet3"] . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity3"], 0) . "</p>
  </td>
   <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal></p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["weight3"], 0) . "</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Y</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>N</p>
  </td>
  <td width=90 colspan=5 style='width:70.1pt;border-top:none;border-left:
  none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["add_shipp_info3"] . "</p>
  </td>
 </tr>";
}
if ($_POST["quantity4"] . $_POST["pallet4"] . $_POST["weight4"] . $_POST["description4"] != "") {
    $thepdf .= "<tr style='height:.2in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["description4"] . "</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . $_POST["pallet4"] . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity4"], 0) . "</p>
  </td>
   <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal></p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["weight4"], 0) . "</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>Y</p>
  </td>
  <td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
  1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=Centered>N</p>
  </td>
  <td width=90 colspan=5 style='width:70.1pt;border-top:none;border-left:
  none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:
  .7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["add_shipp_info4"] . "</p>
  </td>
 </tr>";
}

$total_pallets += $_POST["pallet1"] + $_POST["pallet2"] + $_POST["pallet3"] + $_POST["pallet4"];
$total_weight += $_POST["weight1"] + $_POST["weight2"] + $_POST["weight3"] + $_POST["weight4"];
$total_box_weight += $_POST["weight1"] + $_POST["weight2"] + $_POST["weight3"] + $_POST["weight4"];

$total_boxes += $_POST["quantity1"] + $_POST["quantity2"] + $_POST["quantity3"] + $_POST["quantity4"];



$thepdf .= "<tr style='height:.2in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Bold>Grand Total</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>";

if ($total_pallets == 1) {
    $thepdf .= "<p class=MsoNormal>" . number_format($total_pallets, 0) . " Pallet</p>";
} else {
    $thepdf .= "<p class=MsoNormal>" . number_format($total_pallets, 0) . " Pallets</p>";
}

$thepdf .= "</td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($total_boxes, 0) . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal></p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($total_weight, 0) . "</p>
  </td>
  <td width=319 colspan=7 style='width:239.6pt;border-top:none;border-left:
  none;border-bottom:1.0pt solid black;border-right:1.0pt solid black;background:
  #F3F3F3;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>&nbsp;</p>
  </td>
 </tr>
 <tr style='height:.15in'>
  <td width=732 colspan=18 style='width:549.0pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.15in'>
  <p class=BoldCentered>Note: Liability limitation for loss or damage in this
  shipment may be applicable. See 49 USC § 14706(c)(1)(A) and (B).</p>
  </td>
 </tr>
 <tr style='height:.15in'>
  <td width=732 colspan=18 style='width:549.0pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.15in'>
  <p class=MsoNormal>Received, subject to individually determined rates or contracts that have been agreed upon in writing between the carrier and shipper, if applicable, otherwise to the rates, classifications, and rules that have been established by the carrier and are available to the shipper, on request, and to all applicable state and federal regulations.</p>
  </td>
 </tr>
 <tr style='height:.15in'>
  <td width=183 colspan=5 valign='top' style='width:137.25pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.15in'>
 <p class=Signatureheading style='margin-bottom:4pt;font-size:13px;'>Shipper</p>
 <p>&nbsp;</p>
  <p class=MsoNormal>Printed Name: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>______________________________________ </span></span></p>
  <p>&nbsp;</p>
  <p class=MsoNormal>Signature: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>___________________________________________ </span></span></p>
  <p>&nbsp;</p>
  <p class=MsoNormal>Date: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>__________________________________________________ </span></span></p>
  <p>&nbsp;</p>
  <p class=MsoNormal>Time: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>__________________________________________________ </span></span></p>
 <div style='height:8px;'>&nbsp;</div>
  <p class=FinePrint>This is to certify that the above named materials are
  properly classified, packaged, marked, and labeled, and are in proper
  condition for transportation according to the applicable regulations of the
  DOT.</p>
  </td>
  <td width=123 colspan=3 valign='top' style='width:87.25pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.15in'>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p class=Bold>Trailer Loaded By</p>
  <p class=MsoNormal><input type=checkbox> Shipper</p>
  <p class=MsoNormal><input type=checkbox> Carrier</p>
  <br>
  <p class=Bold>Trailer Counted By</p>
  <p class=MsoNormal><input type=checkbox> Shipper</p>
  <p class=MsoNormal><input type=checkbox> Carrier</p>
  <br>
  <p class=Bold>Load Locked?</p>
  <p class=MsoNormal><input type=checkbox> Yes</p>
  <p class=MsoNormal><input type=checkbox> No</p>
  </td>
  <td width=190 colspan=6 valign='top' style='width:125.25pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.15in'>
  <p class=Bold style='padding-bottom:7px;font-size:13px;'>Carrier</p><p>&nbsp;</p>
  <p class=MsoNormal>Printed Name: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>______________________________________ </span></span></p>
  <p>&nbsp;</p>
  <p class=MsoNormal>Signature: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>___________________________________________ </span></span></p>
  <p>&nbsp;</p>
  <p class=MsoNormal>Date: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>__________________________________________________ </span></span></p>
  <p>&nbsp;</p>
  <p class=MsoNormal>Time: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>__________________________________________________ </span></span></p>
  <p>&nbsp;</p>
  
   <p class=FinePrint>Carrier acknowledges receipt of packages and required placards. Carrier certifies emergency response information was made available and/or carrier has the DOT emergency response guidebook or equivalent documentation in the vehicle. Property described above is received in good order, except as noted.</p>
  </td>
  <td width=100 colspan=4 valign='top' style='width:80.25pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.15in'>
   <p class=Bold style='padding-bottom:7px;font-size:13px;'>Receiver</p><p>&nbsp;</p>
   <p class=MsoNormal>Printed Name: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>______________________________________ </span></span></p>
  <p>&nbsp;</p>
  <p class=MsoNormal>Signature: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>___________________________________________ </span></span></p>
  <p>&nbsp;</p>
  <p class=MsoNormal>Date: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>__________________________________________________ </span></span></p>
  <p>&nbsp;</p>
  <p class=MsoNormal>Time: <span
  class=LightGreylinesCharChar><span style='font-size:6.0pt'>__________________________________________________ </span></span></p>
  <p>&nbsp;</p>
  
   <p class=FinePrint>Receiver acknowledges receipt of packages. Carrier certifies property is received in good order, except as noted.</p>
  </td>
 </tr>

 <tr height=0>
  <td width=37 style='border:none'></td>
  <td width=55 style='border:none'></td>
  <td width=37 style='border:none'></td>
  <td width=55 style='border:none'></td>
  <td width=47 style='border:none'></td>
  <td width=44 style='border:none'></td>
  <td width=7 style='border:none'></td>
  <td width=42 style='border:none'></td>
  <td width=41 style='border:none'></td>
  <td width=30 style='border:none'></td>
  <td width=18 style='border:none'></td>
  <td width=36 style='border:none'></td>
  <td width=42 style='border:none'></td>
  <td width=15 style='border:none'></td>
  <td width=56 style='border:none'></td>
  <td width=44 style='border:none'></td>
  <td width=63 style='border:none'></td>
  <td width=63 style='border:none'></td>
 </tr>
</table>

<p class=MsoNormal>&nbsp;</p>

</div>

</body>

</html>
";

/*include('class.ezpdf.php');
$pdf =& new Cezpdf();
$pdf->selectFont('./fonts/Helvetica');
//$pdf->ezText($thepdf,10);
$pdf->ezText(html_entity_decode($thepdf),'');
$pdfcode = $pdf->output();

*/

$data = ob_get_clean();
/*include("mpdf/mpdf.php");
$mpdf=new mPDF('en','Letter','10','arial', 15,15,16,16,9,9); 
$mpdf->useOnlyCoreFonts = false;
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 1;	// 1 or 0 - whether to indent the first level of a list
$mpdf->WriteHTML($thepdf, 0);
*/


require_once 'mpdf_new/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4', 'font_size' => 16, 'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 16,
    'margin_bottom' => 16,
    'margin_header' => 9,
    'margin_footer' => 9
]);

$mpdf->SetDisplayMode('fullpage');

$stylesheet = file_get_contents('assets/mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet, 1);    // The parameter 1 tells that this is css/style only and no 
$mpdf->WriteHTML($thepdf);

echo "1";
$dir = 'bol';
//save the file
if (!file_exists($dir)) {
    mkdir($dir, 0777);
}
echo "2";
//$fname = tempnam($dir.'/','PDF_').'.pdf';
$fname = tempnam($dir . '/', 'BOL_' . $trans_rec_id . '_') . '.pdf';
$mpdf->Output($fname, 'F');


$file_name = basename($fname);

echo $fname . "3<br>" . isset($sql_sort);

foreach ($_POST as $field_name => $fld_val) {
    echo $field_name . " - " . $fld_val . "<BR>";
}

srand((int) microtime() * 1000000);
$random_number = rand();


$filequery = "UPDATE loop_bol_files SET bo_date = '" . date("m/d/Y H:i:s") . "', warehouse_id = '" . $warehouse_id . "', trans_rec_id = '" . $trans_rec_id . "', employee = '" . $employee . "', rand_value = '" . $random_number . "', file_name = '" . $file_name . "' WHERE id = " . $bol_number;
$fileresult = db_query($filequery);

echo "A";
db();
$tree_query = db_query("SELECT * FROM tree_counter_b2b");
$tree = array_shift($tree_query);

$count = $tree["trees_saved"];

$new_total = $count + $total_box_weight * 0.0085;

db();
$tree_query = db_query("UPDATE tree_counter_b2b SET trees_saved=" . $new_total . " WHERE tree_index=0");

redirect($_SERVER['HTTP_REFERER']);
//redirect('search_results.php?id=' . $_POST["warehouse_id"] . '&proc=View&searchcrit=&rec_id=' . $_POST["rec_id"] . '&display=buyer_ship&rec_type=' . $_POST["rec_type"]);