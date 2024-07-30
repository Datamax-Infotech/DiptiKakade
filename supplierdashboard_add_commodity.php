<?php

require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();

$email_address_txt = str_replace(array("\n", "\t", "\r"), '', $_REQUEST['email_address_txt']);

$sql = "insert into supplier_commodity_details (companyid, commodity, dock_value, carrier_value, shipto_warehouse, shipto_address_one, shipto_address_two, shipto_city, shipto_state, shipto_zip, phone, email_address, water_pick_up, waste_stream, bol_format ) values ";
$sql .= "('" . $_REQUEST['companyid'] . "', '" . $_REQUEST['commodity_txt'] . "', '" . $_REQUEST['dock_txt'] . "', '" . $_REQUEST['carrier_value_txt'] . "', '" . $_REQUEST['shipto_warehouse_txt'] . "', '" . $_REQUEST['shipto_address_one_txt'] . "', '" . $_REQUEST['shipto_address_two_txt'] . "', ";
$sql .= " '" . $_REQUEST['shipto_city_txt'] . "', '" . $_REQUEST['shipto_state_txt'] . "', '" . $_REQUEST['shipto_zip_txt'] . "', '" . $_REQUEST['phone_txt'] . "', '" . $email_address_txt . "', '" . $_REQUEST['chk_water_pick_up'] . "', '" . $_REQUEST['waste_stream'] . "', '" . $_REQUEST['ddBOLFormat'] . "')";

$result = db_query($sql);

echo "<script type=\"text/javascript\">";
echo "window.location.href=\"water_supplierdashboard_setup.php?ID=" . $_REQUEST["companyid"] . "\";";
echo "</script>";
echo "<noscript>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=water_supplierdashboard_setup.php?ID=" . $_REQUEST["companyid"] . "\" />";
echo "</noscript>";
exit;
