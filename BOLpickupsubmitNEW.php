<?php
// require ("inc/header_session.php");

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
//

// Start Processing Function
//
define('EMAIL_LINEFEED', 'LF');
define('EMAIL_TRANSPORT', 'sendmail');
define('EMAIL_USE_HTML', 'true');

//
/*	$arrInsert = $_POST;
//print_r($_POST);
//exit;
	foreach($arrRem as $eleRM)
	{
		unset($arrInsert[$eleRM]);
	}
	//------------------------------------------------
	$Query = "INSERT INTO loop_mccormick SET added_on=now(), ";
	foreach($arrInsert as $key=>$value)
	{
		$tmppos_1 = strpos($key, "GMI_");
		$tmppos_2 = strpos($key, "ddlist");
		if ($tmppos_1 === false && $tmppos_2 === false)
		{
			$Query.= "$key='$value', ";
		}	
	}
	$Query = substr($Query, 0, -2);
*/
ob_start();

$carrier = $_POST["carrier"];
if ($_REQUEST["warehouse_id"] == 191 && $_REQUEST["dock"] == 16) { // If a DAP recycling trailer

    $carrier = "World Recycling";
}

$pud = date('m/d/Y');
if ($_POST["pickup_date"] != "") {
    $pud = $_POST["pickup_date"];
}

srand((int) microtime() * 1000000);
$random_number = rand();

$warehouse_id = $_REQUEST["warehouse_id"];
if (trim($warehouse_id) == "" || $warehouse_id == 0) {
    echo "<font color=red>Warehouse id is blank, process terminated.</font>";
    exit;
}

$Query = "INSERT INTO loop_mccormick SET added_on=now(), carrier='" . str_replace("'", "\'", $_POST["carrier"]) . "', warehouse_id='" . str_replace("'", "\'", $_POST["warehouse_id"]) . "', return_url='" . str_replace("'", "\'", $_POST["return_url"]) . "', ";
$Query .= " destination_id='" . str_replace("'", "\'", $_POST["destination_id"]) . "', logo='" . str_replace("'", "\'", $_POST["logo"]) . "', trailer_no='" . str_replace("'", "\'", $_POST["trailer_no"]) . "', dock='" . str_replace("'", "\'", $_POST["dock"]) . "', fullname='" . str_replace("'", "\'", $_POST["fullname"]) . "', pickup_date='" . str_replace("'", "\'", $_POST["pickup_date"]) . "', ";
$Query .= " check_1='" . str_replace("'", "\'", $_POST["check_1"]) . "', count_1='" . str_replace("'", "\'", $_POST["count_1"]) . "', weight_1='" . str_replace("'", "\'", $_POST["weight_1"]) . "', item_1='" . str_replace("'", "\'", $_POST["item_1"]) . "', check_2='" . str_replace("'", "\'", $_POST["check_2"]) . "', count_2='" . str_replace("'", "\'", $_POST["count_2"]) . "', ";
$Query .= " weight_2='" . str_replace("'", "\'", $_POST["weight_2"]) . "', item_2='" . str_replace("'", "\'", $_POST["item_2"]) . "', count_3='" . str_replace("'", "\'", $_POST["count_3"]) . "', weight_3='" . str_replace("'", "\'", $_POST["weight_3"]) . "', item_3='" . str_replace("'", "\'", $_POST["item_3"]) . "', count_4='" . str_replace("'", "\'", $_POST["count_4"]) . "', weight_4='" . str_replace("'", "\'", $_POST["weight_4"]) . "', ";
$Query .= " item_4='" . str_replace("'", "\'", $_POST["item_4"]) . "', count_5='" . str_replace("'", "\'", $_POST["count_5"]) . "', weight_5='" . str_replace("'", "\'", $_POST["weight_5"]) . "', item_5='" . str_replace("'", "\'", $_POST["item_5"]) . "', count_6='" . str_replace("'", "\'", $_POST["count_6"]) . "', weight_6='" . str_replace("'", "\'", $_POST["weight_6"]) . "', item_6='" . str_replace("'", "\'", $_POST["item_6"]) . "', ";
$Query .= " count_7='" . str_replace("'", "\'", $_POST["count_7"]) . "', weight_7='" . str_replace("'", "\'", $_POST["weight_7"]) . "', item_7='" . str_replace("'", "\'", $_POST["item_7"]) . "', count_8='" . str_replace("'", "\'", $_POST["count_8"]) . "', weight_8='" . str_replace("'", "\'", $_POST["weight_8"]) . "', item_8='" . str_replace("'", "\'", $_POST["item_8"]) . "', count_total='" . str_replace("'", "\'", $_POST["count_total"]) . "', ";
$Query .= " order_total='" . str_replace("'", "\'", $_POST["order_total"]) . "'";

db();
db_query($Query);
$bol_number = tep_db_insert_id();

$logo = $_REQUEST["logo"];
$rec_type = "Manufacturer";
$trailer = $_REQUEST["trailer_no"];
$pr_requestedby = $_REQUEST["fullname"];
$seal_no = $_REQUEST["seal_no"];
$trailer = $_REQUEST["trailer_no"];
$dock = $_REQUEST["dock"];
$pa_warehouse = $_REQUEST["pa_warehouseid"];
$today = date('m/d/Y h:i a');

if ($_POST["return_url"] == "dashboard_GMIMW_24567437942414566484234.php") {
    if ($_REQUEST["destination_id"] == 8) {
        $dock = "OCC Dock";
    }
    if ($_REQUEST["destination_id"] == 9) {
        $dock = "Gaylord Dock";
    }
}

$qry_newtrans = "INSERT INTO loop_transaction SET pa_warehouse = '" . $pa_warehouse . "', bol_filename = 'systembol" . $random_number . ".pdf', pr_requestdate_php = '" . date('Y-m-d H:i:s') . "', warehouse_id = '" . $warehouse_id . "', rec_type = 'Manufacturer', start_date = '" . $today . "', trans_type = 'Seller', tran_status = 'Pickup', employee = 'Portal', dt_trailer = '" . str_replace("'", "\'", $trailer) . "', pr_requestby = '" . str_replace("'", "\'", $pr_requestedby) . "', pr_requestdate = '" . date('m/d/Y') . "', pr_date = '" . date('m/d/Y') . "', pr_pickupdate = '" . $pud . "', pr_dock = '" . $dock . "', GMI_Order = '" . str_replace("'", "\'", $_POST["GMI_Order"]) . "', GMI_Delivery = '" . str_replace("'", "\'", $_POST["GMI_Delivery"]) . "', GMI_Shipment = '" . str_replace("'", "\'", $_POST["GMI_Shipment"]) . "', pr_seal = '" . str_replace("'", "\'", $seal_no) . "', pr_trailer = '" . str_replace("'", "\'", $trailer) . "', pr_employee = 'System'";
db();
$res_newtrans = db_query($qry_newtrans);
$newid = tep_db_insert_id();

?>

<html>

<head>
    <style>
    <!--
    /* Font Definitions */
    @font-face {
        font-family: Wingdings;
        panose-1: 5 0 0 0 0 0 0 0 0 0;
    }

    @font-face {
        font-family: "Cambria Math";
        panose-1: 2 4 5 3 5 4 6 3 2 4;
    }

    @font-face {
        font-family: Tahoma;
        panose-1: 2 11 6 4 3 5 4 4 2 4;
    }

    /* Style Definitions */
    p.MsoNormal,
    li.MsoNormal,
    div.MsoNormal {
        margin: 0in;
        margin-bottom: .0001pt;
        font-size: 8.0pt;
        font-family: "Tahoma", "sans-serif";
    }

    h1 {
        margin-top: 0in;
        margin-right: 0in;
        margin-bottom: 4.0pt;
        margin-left: 0in;
        text-align: center;
        font-size: 10.0pt;
        font-family: "Tahoma", "sans-serif";
        text-transform: uppercase;
    }

    h2 {
        margin-top: 0in;
        margin-right: 0in;
        margin-bottom: 4.0pt;
        margin-left: 0in;
        text-align: right;
        font-size: 10.0pt;
        font-family: "Tahoma", "sans-serif";
        font-weight: normal;
    }

    h3 {
        margin-top: 0in;
        margin-right: 0in;
        margin-bottom: 4.0pt;
        margin-left: 0in;
        font-size: 10.0pt;
        font-family: "Tahoma", "sans-serif";
        font-weight: normal;
    }

    h4 {
        margin-top: 3.0pt;
        margin-right: 0in;
        margin-bottom: 0in;
        margin-left: 0in;
        margin-bottom: .0001pt;
        text-align: center;
        page-break-after: avoid;
        font-size: 10.0pt;
        font-family: "Arial", "sans-serif";
    }

    h5 {
        margin-top: 3.0pt;
        margin-right: 0in;
        margin-bottom: 0in;
        margin-left: 0in;
        margin-bottom: .0001pt;
        page-break-after: avoid;
        font-size: 10.0pt;
        font-family: "Arial", "sans-serif";
    }

    p.MsoAcetate,
    li.MsoAcetate,
    div.MsoAcetate {
        margin: 0in;
        margin-bottom: .0001pt;
        font-size: 8.0pt;
        font-family: "Tahoma", "sans-serif";
    }

    p.SectionTitle,
    li.SectionTitle,
    div.SectionTitle {
        mso-style-name: "Section Title";
        margin: 0in;
        margin-bottom: .0001pt;
        text-align: center;
        font-size: 8.0pt;
        font-family: "Tahoma", "sans-serif";
        text-transform: uppercase;
        font-weight: bold;
    }

    p.FinePrint,
    li.FinePrint,
    div.FinePrint {
        mso-style-name: "Fine Print";
        mso-style-link: "Fine Print Char";
        margin: 0in;
        margin-bottom: .0001pt;
        font-size: 6.0pt;
        font-family: "Tahoma", "sans-serif";
    }

    span.FinePrintChar {
        mso-style-name: "Fine Print Char";
        mso-style-link: "Fine Print";
        font-family: "Tahoma", "sans-serif";
    }

    p.Centered,
    li.Centered,
    div.Centered {
        mso-style-name: Centered;
        margin: 0in;
        margin-bottom: .0001pt;
        text-align: center;
        font-size: 8.0pt;
        font-family: "Tahoma", "sans-serif";
    }

    p.Bold,
    li.Bold,
    div.Bold {
        mso-style-name: Bold;
        mso-style-link: "Bold Char";
        margin: 0in;
        margin-bottom: .0001pt;
        font-size: 8.0pt;
        font-family: "Tahoma", "sans-serif";
        font-weight: bold;
    }

    p.CheckBox,
    li.CheckBox,
    div.CheckBox {
        mso-style-name: "Check Box";
        mso-style-link: "Check Box Char";
        margin: 0in;
        margin-bottom: .0001pt;
        font-size: 10.0pt;
        font-family: Wingdings;
        color: #333333;
    }

    span.CheckBoxChar {
        mso-style-name: "Check Box Char";
        mso-style-link: "Check Box";
        font-family: Wingdings;
        color: #333333;
    }

    p.LightGreylines,
    li.LightGreylines,
    div.LightGreylines {
        mso-style-name: "Light Grey lines";
        mso-style-link: "Light Grey lines Char Char";
        margin: 0in;
        margin-bottom: .0001pt;
        font-size: 6.0pt;
        font-family: "Tahoma", "sans-serif";
        color: #999999;
    }

    span.LightGreylinesCharChar {
        mso-style-name: "Light Grey lines Char Char";
        mso-style-link: "Light Grey lines";
        font-family: "Tahoma", "sans-serif";
        color: #999999;
    }

    span.BoldChar {
        mso-style-name: "Bold Char";
        mso-style-link: Bold;
        font-family: "Tahoma", "sans-serif";
        font-weight: bold;
    }

    p.Terms,
    li.Terms,
    div.Terms {
        mso-style-name: Terms;
        margin-top: 2.0pt;
        margin-right: 0in;
        margin-bottom: 0in;
        margin-left: 0in;
        margin-bottom: .0001pt;
        font-size: 8.0pt;
        font-family: "Tahoma", "sans-serif";
    }

    p.ShipperSignature,
    li.ShipperSignature,
    div.ShipperSignature {
        mso-style-name: "Shipper Signature";
        mso-style-link: "Shipper Signature Char";
        margin-top: 2.0pt;
        margin-right: 0in;
        margin-bottom: 0in;
        margin-left: 0in;
        margin-bottom: .0001pt;
        font-size: 8.0pt;
        font-family: "Tahoma", "sans-serif";
        font-weight: bold;
    }

    span.ShipperSignatureChar {
        mso-style-name: "Shipper Signature Char";
        mso-style-link: "Shipper Signature";
        font-family: "Tahoma", "sans-serif";
        font-weight: bold;
    }

    p.BarCode,
    li.BarCode,
    div.BarCode {
        mso-style-name: "Bar Code";
        margin-top: 4.0pt;
        margin-right: 0in;
        margin-bottom: 4.0pt;
        margin-left: 0in;
        text-align: center;
        font-size: 12.0pt;
        font-family: "Tahoma", "sans-serif";
        color: grey;
        text-transform: uppercase;
        font-weight: bold;
    }

    p.BoldCentered,
    li.BoldCentered,
    div.BoldCentered {
        mso-style-name: "Bold Centered";
        margin: 0in;
        margin-bottom: .0001pt;
        text-align: center;
        font-size: 8.0pt;
        font-family: "Tahoma", "sans-serif";
        font-weight: bold;
    }

    p.Signatureheading,
    li.Signatureheading,
    div.Signatureheading {
        mso-style-name: "Signature heading";
        margin-top: 0in;
        margin-right: 0in;
        margin-bottom: 6.0pt;
        margin-left: 0in;
        font-size: 8.0pt;
        font-family: "Tahoma", "sans-serif";
        font-weight: bold;
    }

    </style></head><body lang=EN-US><div class=Section1><table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 align=left width=732 style='width:549.0pt;border-collapse:collapse;border:none;
margin-left:7.1pt;
    margin-right:7.1pt'>
<tr style='height:23.05pt'><td colspan=2 style='width:66.00pt;border:none;border:1.0pt solid black;
padding:.7pt 4.3pt .7pt 4.3pt;
    height:23.05pt'> 
<p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><img src='image001new.jpg'width='70px'height='77px'></span></p></td><td colspan=3 style='width:76.05pt;border:none;border:1.0pt solid black;
padding:.7pt 4.3pt .7pt 4.3pt;
    height:23.05pt'>
<h3>Date: <?php echo $pud;
    ?></h3></td><td colspan=11 style='border:none;border:
1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:23.05pt'>
<h1>Bill of Lading - Short Form - Not Negotiable</h1></td><td colspan=2 style='border:none;border:1.0pt solid black;
padding:.7pt 4.3pt .7pt 4.3pt;
    height:23.05pt'>
<h2>Page 1 of 1</h2></td></tr><tr style='height:.2in'><td width=365 colspan=9 style='width:273.4pt;border:1.0pt solid black;
border-top:none;
    background:#E6E6E6;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=SectionTitle>Ship From</p></td><td width=367 colspan=9 style='width:275.6pt;border:none;border-right:1.0pt solid black; border-bottom:1.0pt solid black; 
padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Bold>Bill of Lading Number: <?php echo $newid;
    ?><font color="red"></font></p></td></tr><tr style='height:8.8pt'><td width=365 colspan=9 valign=bottom style='width:273.4pt;border:1.0pt solid black;
border-top:none;
    padding:2.15pt 4.3pt 2.15pt 4.3pt;
    height:8.8pt'>
<?php $dt_view_qry="SELECT * from loop_warehouse WHERE id ='". $_REQUEST["warehouse_id"] . "'";
    db();
    $dt_view_res=db_query($dt_view_qry);

    while ($dt_view_row=array_shift($dt_view_res)) {
        $warehouse_name=$dt_view_row["warehouse_name"];
        ?><p class=MsoNormal><?php echo $dt_view_row["warehouse_name"];
        ?></p><p class=MsoNormal><?php echo $dt_view_row["warehouse_address1"];
        ?></p><p class=MsoNormal><?php echo $dt_view_row["warehouse_address2"];
        ?></p><p class=MsoNormal><?php echo $dt_view_row["warehouse_city"];
        ?>,
        <?php echo $dt_view_row["warehouse_state"];
        ?><?php echo $dt_view_row["warehouse_zip"];
        ?></p><p class=MsoNormal><?php echo $dt_view_row["warehouse_contact_phone"];
        ?></p><?php
    }

    ?></td><td width=367 colspan=9 style='width:275.6pt;border-top:none;border-left:
none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding: 2.15pt 4.3pt 2.15pt 4.3pt;
    height:8.8pt'>
<p class=BarCode>Bar Code Space</p></td></tr><tr style='height:.2in'><td width=365 colspan=9 style='width:273.4pt;border:1.0pt solid black;
border-top:none;
    background:#E6E6E6;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=SectionTitle>Ship To</p></td><td width=367 colspan=9 style='width:275.6pt;border:none;border-right:1.0pt solid black; border-bottom:1.0pt solid black; 
padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Bold>Carrier Name: <?php echo $carrier;
    ?></p><p class=MsoNormal></p></td></tr><tr style='height:8.8pt'><td width=365 colspan=9 valign=top style='width:273.4pt;border:1.0pt solid black;
border-top:none;
    padding:2.15pt 4.3pt 2.15pt 4.3pt;
    height:8.8pt'>
<?php $dt_view_qry="SELECT * from loop_mccormick_dock WHERE flag_value = 'C'";

    if ($_REQUEST["warehouse_id"]==191 && $_REQUEST["dock"]==16) {
        // If a DAP recycling trailer
        $dt_view_qry="SELECT * from loop_mccormick_dock WHERE flag_value = 'D'";
    }

    if ($_REQUEST["destination_id"] > 0) {
        // If destination_id set

        if ($_REQUEST["destination_id"]==9) {
            $dt_view_qry="SELECT * from loop_mccormick_dock WHERE id = 30";
        }

        else {
            $dt_view_qry="SELECT * from loop_mccormick_dock WHERE id = ". $_REQUEST["destination_id"];
        }
    }

    db();
    $dt_view_res=db_query($dt_view_qry);

    while ($dt_view_row=array_shift($dt_view_res)) {
        ?><p class=MsoNormal><?php echo $dt_view_row["warehouse"];
        ?></p><p class=MsoNormal><?php echo $dt_view_row["address_one"];
        ?></p><p class=MsoNormal><?php echo $dt_view_row["address_two"];
        ?></p><p class=MsoNormal><?php echo $dt_view_row["city"];
        ?>,
        <?php echo $dt_view_row["state"];
        ?><?php echo $dt_view_row["zip"];
        ?></p><p class=MsoNormal><?php echo $dt_view_row["phone"];
        ?></p><?php
    }

    ?></td><td width=367 colspan=9 valign=top style='width:275.6pt;border-top:none;
border-left:none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:2.15pt 4.3pt 2.15pt 4.3pt;
    height:8.8pt'>
<p class=MsoNormal>Trailer number: <?php echo $_POST["trailer_no"];
    ?></p><p class=MsoNormal>Seal number(s): <?php echo $_POST["seal_no"];
    ?></p></td></tr><tr style='height:.2in'><td width=365 colspan=9 style='width:273.4pt;border:1.0pt solid black;
border-top:none;
    background:#E6E6E6;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=SectionTitle>Third Party Freight Charges Bill to</p></td><td width=367 colspan=9 style='width:275.6pt;border:none;border-right:1.0pt solid black;  border-bottom:1.0pt solid black; 
padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Bold>SCAC:</p></td></tr><tr style='height:24.45pt'><td width=365 colspan=9 valign=top style='width:273.4pt;border:1.0pt solid black;
border-top:none;
    padding:2.15pt 4.3pt 2.15pt 4.3pt;
    height:24.45pt'>
</td><td width=367 colspan=9 valign=top style='width:275.6pt;border-top:none;
border-left:none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:2.15pt 4.3pt 2.15pt 4.3pt;
    height:24.45pt'>
<p class=MsoNormal>Pro Number:</p><p class=BarCode>Bar Code Space</p><p class=MsoNormal>&nbsp;
    </p></td></tr><tr style='height:8.8pt'><td width=365 colspan=9 rowspan=2 valign=top style='width:273.4pt;border:
1.0pt solid black;
    border-top:none;
    padding:2.15pt 4.3pt 2.15pt 4.3pt;
    height:8.8pt'>
<p class=Bold>Special Instructions:</p><p class=MsoNormal></p></td><td width=367 colspan=9 valign=top style='width:275.6pt;border-top:none;
border-left:none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:8.8pt'>
<p class=Bold>Freight Charge Terms <span class=FinePrintChar><span style='font-size:6.0pt'>(Freight charges are prepaid unless marked otherwise):</span></span></p><p class=Terms>Prepaid <input type="checkbox">Collect <span class=CheckBoxChar><input type="checkbox"></span>3rd Party <input type="checkbox"></p></td></tr><tr style='height:.2in'><td width=367 colspan=9 style='width:275.6pt;border-top:none;border-left:
none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding: 2.9pt 4.3pt 2.15pt 4.3pt;
    height:.2in'>
<p class=MsoNormal><input type="checkbox">Master bill of lading with attached underlying bills of lading.</p></td></tr><tr style='height:.2in'><td width=732 colspan=18 style='width:549.0pt;border:1.0pt solid black;
border-top:none;
    background:#E6E6E6;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=SectionTitle>Customer Order Information</p></td></tr><tr style='height:.15in'><td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
border-top:none;
    padding:.7pt 4.3pt 2.15pt 4.3pt;
    height:.15in'>
<p class=Bold>Customer Order No.</p></td><td width=22 style='width:11.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt 2.15pt 4.3pt;
    height:.15in'>
<p class=Centered>Unit</p></td><td width=22 style='width:11.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt 2.15pt 4.3pt;
    height:.15in'>
<p class=Centered>Type</p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt 2.15pt 4.3pt;
    height:.15in'>
<p class=Centered># of Packages</p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt 2.15pt 4.3pt;
    height:.15in'>
<p class=Centered>Weight</p></td><td width=48 colspan=2 style='width:28.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt 2.15pt 4.3pt;
    height:.15in'>
<p class=Centered>Pallet/Slip<br>(circle one)</p></td><td width=90 colspan=5 style='width:70.1pt;border-top:none;border-left:
none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding: .7pt 4.3pt 2.15pt 4.3pt;
    height:.15in'>
<p class=Bold>Additional Shipper Information</p></td></tr>";
<tr style='height:.2in'><td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
border-top:none;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=22 style='width:11.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Centered>Y</p></td><td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Centered>N</p></td><td width=90 colspan=5 style='width:70.1pt;border-top:none;border-left:
none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding: .7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td></tr><tr style='height:.2in'><td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
border-top:none;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=22 style='width:11.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Centered>Y</p></td><td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Centered>N</p></td><td width=90 colspan=5 style='width:70.1pt;border-top:none;border-left:
none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding: .7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td></tr><tr style='height:.2in'><td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
border-top:none;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=22 style='width:11.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Centered>Y</p></td><td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Centered>N</p></td><td width=90 colspan=5 style='width:70.1pt;border-top:none;border-left:
none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding: .7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td></tr><tr style='height:.2in'><td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
border-top:none;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=22 style='width:11.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=32 style='width:21.5pt;border-top:none;border-left:none;
border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td><td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Centered>Y</p></td><td width=26 style='width:17.0pt;border-top:none;border-left:none;border-bottom:
1.0pt solid black;
    border-right:1.0pt solid black;
    padding:.7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=Centered>N</p></td><td width=90 colspan=5 style='width:70.1pt;border-top:none;border-left:
none;
    border-bottom:1.0pt solid black;
    border-right:1.0pt solid black;
    padding: .7pt 4.3pt .7pt 4.3pt;
    height:.2in'>
<p class=MsoNormal>&nbsp;
    </p></td></tr>< !-- No time to make this dynamic. Hard Coding Values
    -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_1"] == 1) {
        $weight = $_POST["weight_1"];
        $item = $_POST["item_1"];
        $count = $_POST["count_1"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>
    <!-- No time to make this dynamic.  Hard Coding Values -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_2"] == 1) {
        $weight = $_POST["weight_2"];
        $item = $_POST["item_2"];
        $count = $_POST["count_2"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>

    <!-- No time to make this dynamic.  Hard Coding Values -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_3"] == 1) {
        $weight = $_POST["weight_3"];
        $item = $_POST["item_3"];
        $count = $_POST["count_3"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>
    <!-- No time to make this dynamic.  Hard Coding Values -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_4"] == 1) {
        $weight = $_POST["weight_4"];
        $item = $_POST["item_4"];
        $count = $_POST["count_4"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>
    <!-- No time to make this dynamic.  Hard Coding Values -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_5"] == 1) {
        $weight = $_POST["weight_5"];
        $item = $_POST["item_5"];
        $count = $_POST["count_5"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>
    <!-- No time to make this dynamic.  Hard Coding Values -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_6"] == 1) {
        $weight = $_POST["weight_6"];
        $item = $_POST["item_6"];
        $count = $_POST["count_6"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>
    <!-- No time to make this dynamic.  Hard Coding Values -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_7"] == 1) {
        $weight = $_POST["weight_7"];
        $item = $_POST["item_7"];
        $count = $_POST["count_7"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>
    <!-- No time to make this dynamic.  Hard Coding Values -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_8"] == 1) {
        $weight = $_POST["weight_8"];
        $item = $_POST["item_8"];
        $count = $_POST["count_8"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>
    <!-- No time to make this dynamic.  Hard Coding Values -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_9"] == 1) {
        $weight = $_POST["weight_9"];
        $item = $_POST["item_9"];
        $count = $_POST["count_9"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>
    <!-- No time to make this dynamic.  Hard Coding Values -->
    <!-- Row 1 From form -->
    <?php
    if ($_POST["check_10"] == 1) {
        $weight = $_POST["weight_10"];
        $item = $_POST["item_10"];
        $count = $_POST["count_10"];
    ?>
    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=MsoNormal><?php echo $item; ?></p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal><?php echo $count; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $weight; ?></p>
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
            <p class=MsoNormal>&nbsp;</p>
        </td>
    </tr>
    <?php } ?>



    <tr style='height:.2in'>
        <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
            <p class=Bold>Grand Total</p>
        </td>
        <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $_REQUEST["count_total"]; ?></p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;</p>
        </td>
        <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
            <p class=MsoNormal>&nbsp;<?php echo $_REQUEST["order_total"]; ?></p>
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
            <p class=MsoNormal>Received, subject to individually determined rates or contracts that have been agreed
                upon in writing between the carrier and shipper, if applicable, otherwise to the rates, classifications,
                and rules that have been established by the carrier and are available to the shipper, on request, and to
                all applicable state and federal regulations.</p>
        </td>
    </tr>
    <tr style='height:.15in'>
        <td width=183 colspan=5 valign='top' style='width:137.25pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.15in'>
            <p class=Signatureheading style='margin-bottom:4pt;font-size:13px;'>Shipper</p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Printed Name: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>______________________________________ </span></span></p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Signature: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>___________________________________________ </span></span></p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Date: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>__________________________________________________ </span></span></p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Time: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>__________________________________________________ </span></span></p>
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
            <p class=Bold style='padding-bottom:7px;font-size:13px;'>Carrier</p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Printed Name: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>______________________________________ </span></span></p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Signature: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>___________________________________________ </span></span></p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Date: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>__________________________________________________ </span></span></p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Time: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>__________________________________________________ </span></span></p>
            <p>&nbsp;</p>

            <p class=FinePrint>Carrier acknowledges receipt of packages and required placards. Carrier certifies
                emergency response information was made available and/or carrier has the DOT emergency response
                guidebook or equivalent documentation in the vehicle. Property described above is received in good
                order, except as noted.</p>
        </td>
        <td width=100 colspan=4 valign='top' style='width:80.25pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:.15in'>
            <p class=Bold style='padding-bottom:7px;font-size:13px;'>Receiver</p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Printed Name: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>______________________________________ </span></span></p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Signature: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>___________________________________________ </span></span></p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Date: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>__________________________________________________ </span></span></p>
            <p>&nbsp;</p>
            <p class=MsoNormal>Time: <span class=LightGreylinesCharChar><span
                        style='font-size:6.0pt'>__________________________________________________ </span></span></p>
            <p>&nbsp;</p>

            <p class=FinePrint>Receiver acknowledges receipt of packages. Carrier certifies property is received in good
                order, except as noted.</p>
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
<?php
//Create PDF file
$data = ob_get_clean();
/*include("mpdf/mpdf.php");
$mpdf=new mPDF('en','Letter','10','arial', 15,15,16,16,9,9); 
$mpdf->useOnlyCoreFonts = false;
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 1;	// 1 or 0 - whether to indent the first level of a list
$mpdf->WriteHTML($data, 0);
$mpdf->AddPage();
$mpdf->WriteHTML($data, 0);
$mpdf->AddPage();
$mpdf->WriteHTML($data, 0);

//$mpdf->Output('files/bol.pdf','F'); 
//$mpdf->Output('files/bol.pdf','I'); 
$mpdf->Output('files/systembol'.$random_number.'.pdf','F'); */
//
require_once 'mpdf_new/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4', 'font_size' => 15, 'margin_left' => 7,
    'margin_right' => 7,
    'margin_top' => 16,
    'margin_bottom' => 16,
    'margin_header' => 9,
    'margin_footer' => 9
]);

$mpdf->SetDisplayMode('fullpage');

$stylesheet = file_get_contents('assets/mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet, 1);    // The parameter 1 tells that this is css/style only and no 
$mpdf->WriteHTML($data);
//
$mpdf->Output('files/systembol' . $random_number . '.pdf', 'F');
//header("Location: files/bol.pdf");

//exit();
//Send email
//
//Code to send mail
require 'phpmailer/PHPMailerAutoload.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.office365.com';
$mail->Port       = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth   = true;
$mail->Username = "ucbemail@usedcardboardboxes.com";
$mail->Password = "#UCBgrn4652";
$mail->SetFrom("ucbemail@usedcardboardboxes.com", "UsedCardboardBoxes.com");
//
//
$from_email = "freight@UsedCardboardBoxes.com";
$to_email = "trailerpickup@usedcardboardboxes.com";
$sql = "SELECT * from email_config where unqid = 1";
db();
$result_n = db_query($sql);
while ($myrowsel_n = array_shift($result_n)) {
    $from_email = $myrowsel_n["from_email"];
    $to_email = $myrowsel_n["to_email"];
}

$str_email = "<html><head></head><body bgcolor=\"#E7F5C2\"><table align=\"center\" cellpadding=\"0\"><tr><td><p align=\"center\"><a href=\"http://www.usedcardboardboxes.com/index.php\"><img width=\"650\" height=\"166\" src=\"https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg\"></a></p></td></tr><tr><td><p align=\"left\"><font face=\"arial\" size=\"2\">";
//	$str_email.= "Dear UCB,<br><br>";
$str_email .= "This email is to confirm that " . $_POST["fullname"] . " of " . isset($warehouse_name) . " has requested a trailer pickup. Details below:<br><br>";
$str_email .= "Transaction ID:  " . $newid . " <br>";
$str_email .= "Freight Hauler:  " . $carrier . " <br>";
$str_email .= "Dock #:  " . $_POST["dock"] . " <br>";
$str_email .= "Trailer #:  " . $_POST["trailer_no"] . " <br>";
$str_email .= "Seal #:  " . $_POST["seal_no"] . " <br>";
$str_email .= "Notes:  <b>" . $_POST["comments"] . "</b> <br><br>";
$str_email .= "<b>" . $carrier . " - Please reply-all to this email confirming pickup and pickup details.</b><br><br>If you have any questions, please reply to this email or contact our freight department at 888-BOXES-88 x5 <br><br>";

/*
	
	$str_email.= "Best Regards,<br>";
	$str_email.= "UsedCardboardBoxes.com Freight Team<br>";
	$str_email.= "<a href=mailto:freight@usedcardboardboxes>freight@usedcardboardboxes.com</a><br>";
	$str_email.= "888-BOXES-88 x5<br>";
	$str_email.= "Hours: M-F 8:00AM - 4:30PM ET<br>";
	$str_email.= "</font></td></tr><tr><td><p align=\"center\"><img width=\"650\" height=\"87\" src=\"https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg\"></p></td></tr></table></body></html>";
*/

$str_email .= "</font></td></tr></table><br><br>";

$signature = "<table align='center' cellspacing='10'><tr><td style='border-right: 2px solid #66381C;'><img src='https://www.ucbzerowaste.com/images/logo2.png'></td>";
$signature .= "<td><p style='font-family: Calibri; font-size:12;color:#538135'>";
$signature .= "Used Cardboard Boxes, Inc. (UCB)</p>";
$signature .= "<span style='font-family: Calibri; font-size:12; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
$signature .= "323-724-2500<br><br>";
$signature .= "How can we improve?  Please tell our CEO@UsedCardboardBoxes.com</span>";
$signature .= "</td></tr></table>";
$str_email .= $signature . "</body></html>";

//$recipient = "davidkrasnow@usedcardboardboxes.com, trailerpickup@usedcardboardboxes.com, davidrodriguez@usedcardboardboxes.com,zacfratkin@usedcardboardboxes.com, freight@usedcardboardboxes.com";
$recipient = $to_email;
/*$_REQUEST["warehouse_id"] = 191;
$_REQUEST["dock"] = 16;*/
//$_REQUEST["destination_id"]=15;
$view_qry = "SELECT * from loop_mccormick_dock WHERE flag_value = 'C'";
if ($_REQUEST["warehouse_id"] == 191 && $_REQUEST["dock"] == 16) { // If a DAP recycling trailer

    $view_qry = "SELECT * from loop_mccormick_dock WHERE flag_value = 'D'";
}
if ($_REQUEST["destination_id"] > 0) { // If destination_id set

    if ($_REQUEST["destination_id"] == 9) {
        $view_qry = "SELECT * from loop_mccormick_dock WHERE id = 30";
    } else {
        $view_qry = "SELECT * from loop_mccormick_dock WHERE id = " . $_REQUEST["destination_id"];
    }
}

db();
$result = db_query($view_qry);
while ($myrowsel = array_shift($result)) {
    if ($myrowsel["email_address"] != "") {
        $recipient = $recipient . "," . $myrowsel["email_address"];
    }
}

if ($_REQUEST["warehouse_id"] == 1473 || $_REQUEST["warehouse_id"] == 1472 || $_REQUEST["warehouse_id"] == 1972) { // If a DAP recycling trailer
    $recipient = "Freight_GPS@UsedCardboardBoxes.com";
}

if ($_REQUEST["warehouse_id"] == 2449) { // If a DAP recycling trailer
    if ($recipient != "") {
        $recipient = $recipient . "," .    "hvleads@usedcardboardboxes.com";
    }
}

if ($_REQUEST["warehouse_id"] == 3703) { // If a DAP recycling trailer
    if ($recipient != "") {
        $recipient = $recipient . "," .    "freight_sharkeys@usedcardboardboxes.com";
    } else {
        $recipient = "freight_sharkeys@usedcardboardboxes.com";
    }
}

//$recipient = "prasad.brid@gmail.com";

//echo $recipient;
//send to multiple address
$addresses = explode(',', $recipient);
foreach ($addresses as $address) {
    $mail->AddAddress($address, $address);
}
//
//$mail->addAddress("spa332357@gmail.com", "spa332357@gmail.com");
//$recipient = "prasad.brid@gmail.com";
//if ($_REQUEST["warehouse_id"] == 738) {
//$warehouse_name = "General Mills Inc. - Vineland - NJ";
//$warehouse_name = "";
//$subject = "General Mills Inc Vineland NJ has Requested a Trailer Pickup.";
//	$subject = "Client has Requested a Trailer Pickup.";
//}else{
//	$subject = $warehouse_name . " has Requested a Trailer Pickup";
//}

$subject = isset($warehouse_name) . " has requested a trailer pickup on " . $_REQUEST["pickup_date"];
/*$mailheadersadmin = "From: UsedCardboardBoxes.com <" . $from_email . ">\n";
$mailheadersadmin.= "MIME-Version: 1.0\r\n";
$mailheadersadmin.= "Content-Type: text/html; charset=ISO-8859-1\r\n";
*/
$fname = "systembol" . $random_number . ".pdf";
$fileatt_name = "files/systembol" . $random_number . ".pdf";
$mail->addAttachment($fileatt_name);
$mail->IsHTML(true);
$mail->Encoding = 'base64';
$mail->CharSet = "UTF-8";
$mail->Subject = $subject;
$mail->Body    = $str_email;
$mail->AltBody = $str_email;
if (!$mail->send()) {
    echo "Email Error";
} else {

    //
    //End send email function


    /*
$warehouse_id = $_REQUEST["warehouse_id"];
$logo = $_REQUEST["logo"];
$rec_type = "Manufacturer";
$trailer = $_REQUEST["trailer_no"];
$pr_requestedby = $_REQUEST["fullname"];
$seal_no = $_REQUEST["seal_no"];
$trailer = $_REQUEST["trailer_no"];
$dock = $_REQUEST["dock"];
$today = date('m/d/Y h:i a'); 

$qry_newtrans = "INSERT INTO loop_transaction SET bol_filename = 'systembol". $random_number .".pdf', pr_requestdate_php = '" . date('Y-m-d H:i:s') . "', warehouse_id = '" . $warehouse_id . "', rec_type = 'Manufacturer', start_date = '" . $today . "', trans_type = 'Seller', tran_status = 'Pickup', employee = 'System', dt_trailer = '". $trailer ."', pr_requestby = '" . $pr_requestedby . "', pr_requestdate = '" . date('m/d/Y') . "', pr_date = '" . date('m/d/Y') . "', pr_pickupdate = '" . $pud . "', pr_dock = '" . $dock . "', pr_seal = '" . $seal_no . "', pr_trailer = '" . $trailer . "', pr_employee = 'System'";
$res_newtrans = db_query($qry_newtrans,db() );

*/

?>
<img src="images/demo.jpg"><br><br>
<font face="Arial" color="#009900">Dear <?php echo $_REQUEST["fullname"]; ?>,<br>
    Your Trailer Pickup request for trailer #<?php echo $_REQUEST["trailer_no"] ?> <?php if ($_REQUEST["seal_no"] != "") {
                                                                                            echo " with seal #" . $_REQUEST["seal_no"];
                                                                                        } ?>
    <?php if ($_REQUEST["dock"] != "") {
            echo "from Dock " . $_REQUEST["dock"];
        } ?> has been submitted
    successfully!<br><br>
    <a href="files/systembol<?php echo $random_number ?>.pdf" target=_blank>CLICK HERE TO VIEW BOL</a><br><br><a
        href="<?php echo $_REQUEST["return_url"] ?>">Please click here to return. </a>
    <br><br>
    <?php
}
    ?>