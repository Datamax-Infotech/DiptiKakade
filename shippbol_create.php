<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


$total_pallets = $total_pallets ?? "";
$count = $count ?? "";
$today = date("m/d/Y");
$today_date = date("Y-m-d");
$today_crm = date("Ymd");
$user = $_COOKIE['userinitials'];
$bol_date = $today;
//to get the location warehouse and boxid which are affected for Dashboard inventory update
$tmp_array = array();


$message = "<strong>Note for Transaction # ";
$message .=  isset($trans_rec_id);
$message .= "</strong>: ";
$message .=  isset($employee);
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
  <td colspan=11 style='border:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;height:23.05pt'>
  <h1>Bill of Lading – Short Form – Not Negotiable</h1>
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
  <p class=Bold>Bill of Lading Number: " . $_POST["bol_number"] . "</p>
  </td>
 </tr>
 <tr style='height:8.8pt'>
  <td width=365 colspan=9 valign=bottom style='width:273.4pt;border:1.0pt solid black;
  border-top:none;padding:2.15pt 4.3pt 2.15pt 4.3pt;height:8.8pt'>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p>
  <p class=MsoNormal></p>
  <p class=MsoNormal>" . $_POST["ship_from1"] . "<BR>" . $_POST["ship_from2"] . "<BR>" . $_POST["ship_from3"] . "<BR>" . $_POST["ship_from4"] . "</p>
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
  <p class=Bold>Carrier Name: </p>
  <p class=MsoNormal>" . $_POST["carrier_name"] . "</p>
	</td>
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
  <p class=MsoNormal>Trailer number: " . $_POST["trailer_number"] . "</p>
  <p class=MsoNormal>Serial number(s): " . $_POST["serial_no"] . "</p>
  <p class=MsoNormal>Class: " . $_POST["class"] . "</p>
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

$sql_freightbiller = "SELECT * FROM loop_freightvendor WHERE id = '" . $_POST["bol_freight_biller"] . "'";
db();
$result_freightbiller = db_query($sql_freightbiller);
$freightbiller_row = array_shift($result_freightbiller);
$thepdf .= "<p class=MsoNormal>" . $freightbiller_row["company_name"] . "</p>
  <p class=MsoNormal>" . $freightbiller_row["company_address1"] . $freightbiller_row["company_address2"] . "</p>
  <p class=MsoNormal>" . $freightbiller_row["company_city"] . ", " . $freightbiller_row["company_state"] . " " . $freightbiller_row["company_zip"] . "</p>
  <p class=MsoNormal>" . $freightbiller_row["company_phone"] . "</p>
  </td>
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
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered>Type</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt 2.15pt 4.3pt;
  height:.15in'>
  <p class=Centered># of Packages</p>
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
  $sql_box = "SELECT * FROM loop_boxes WHERE id = '" . $_POST["box_id"][$i] . "'";
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
      $thepdf .= $_POST["pallets"][$i];
      $total_pallets += $_POST["pallets"][$i];
    }
    $thepdf .= "</p>
  </td>
	<td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>Pallets</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["qty"][$i], 0) . "</p>
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
  <p class=MsoNormal>Pallets</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity1"], 0) . "</p>
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
  <p class=MsoNormal>Pallets</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity2"], 0) . "</p>
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
  <p class=MsoNormal>Pallets</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity3"], 0) . "</p>
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
  <p class=MsoNormal>Pallets</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity4"], 0) . "</p>
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
if ($_POST["quantity5"] . $_POST["pallet5"] . $_POST["weight5"] . $_POST["description5"] != "") {
  $thepdf .= "<tr style='height:.2in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["description5"] . "</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . $_POST["pallet5"] . "</p>
  </td>
   <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>Pallets</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity5"], 0) . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["weight5"], 0) . "</p>
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
  <p class=MsoNormal>" . $_POST["add_shipp_info5"] . "</p>
  </td>
 </tr>";
}
if ($_POST["quantity6"] . $_POST["pallet6"] . $_POST["weight6"] . $_POST["description6"] != "") {
  $thepdf .= "<tr style='height:.2in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=MsoNormal>" . $_POST["description6"] . "</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . $_POST["pallet6"] . "</p>
  </td>
   <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>Pallets</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["quantity6"], 0) . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($_POST["weight6"], 0) . "</p>
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
  <p class=MsoNormal>" . $_POST["add_shipp_info6"] . "</p>
  </td>
 </tr>";
}

$total_pallets += $_POST["pallet1"] + $_POST["pallet2"] + $_POST["pallet3"] + $_POST["pallet4"] + $_POST["pallet5"] + $_POST["pallet6"];
$total_weight += $_POST["weight1"] + $_POST["weight2"] + $_POST["weight3"] + $_POST["weight4"] + $_POST["weight5"] + $_POST["weight6"];
$total_box_weight += $_POST["weight1"] + $_POST["weight2"] + $_POST["weight3"] + $_POST["weight4"] + $_POST["weight5"] + $_POST["weight6"];

$total_boxes += $_POST["quantity1"] + $_POST["quantity2"] + $_POST["quantity3"] + $_POST["quantity4"] + $_POST["quantity5"] + $_POST["quantity6"];



$thepdf .= "<tr style='height:.2in'>
  <td width=190 colspan=7 style='width:120.4pt;border:1.0pt solid black;
  border-top:none;padding:.7pt 4.3pt .7pt 4.3pt;height:.2in'>
  <p class=Bold>Grand Total</p>
  </td>
  <td width=22 style='width:11.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($total_pallets, 0) . "</p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal></p>
  </td>
  <td width=32 style='width:21.5pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid black;border-right:1.0pt solid black;padding:.7pt 4.3pt .7pt 4.3pt;
  height:.2in'>
  <p class=MsoNormal>" . number_format($total_boxes, 0) . "</p>
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

//
$data = ob_get_clean();
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

//echo "1";
$dir = 'manual_bol';
//save the file
if (!file_exists($dir)) {
  mkdir($dir, 0777);
}
//echo "2";
$fname = tempnam($dir . '/', 'PDF_') . '.pdf';
$mpdf->Output($fname, 'F');
//$mpdf->Output($fname,'I'); 

$file_name = basename($fname);
//echo $file_name;
?>
<script type="text/javascript">
//window.open('manual_bol/<?php echo $file_name; ?>','_blank');
//
</script>

<?php

redirect("manual_bol/" . $file_name);

//echo $fname . "3<br>" . $sql_sort;

?>