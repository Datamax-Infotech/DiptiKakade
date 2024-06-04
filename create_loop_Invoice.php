<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

include 'PDFMerger/PDFMerger.php';

?>

<style type="text/css">
.input-color {
    width: 40px;
    height: 40px;
    display: inline-block;
    background-color: #ccc;
}

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

.white_content_reminder {
    display: none;
    position: absolute;
    top: 10%;
    left: 10%;
    width: 50%;
    height: 85%;
    padding: 16px;
    border: 1px solid gray;
    background-color: white;
    z-index: 1002;
    overflow: auto;
    box-shadow: 8px 8px 5px #888888;
}
</style>

<?php

function formatdata(string $data): string
{
	return addslashes(trim($data));
}

function timestamp_to_datetime(string $d): string
{
	$da = explode(" ", $d);
	$dp = explode("-", $da[0]);
	$dh = explode(":", $da[1]);

	$x = $dp[1] . "/" . $dp[2] . "/" . $dp[0];

	$hour = intval($dh[0]);
	$minute = $dh[1];
	$period = ($hour >= 12) ? "PM" : "AM";
	$hour = ($hour > 12) ? $hour - 12 : $hour;
	$hour = ($hour == 0) ? 12 : $hour;

	$x .= " " . $hour . ":" . $minute . $period . " CT";

	return $x;
}


$who_from = "";
$inv_file_name = "";
$parent_child_compid = "";
$term_days = "";
$inv_prefix = "";
$bill_to_contact = "";
$bill_to_add = "";
$bill_to_add2 = "";
$bill_to_city = "";
$bill_to_state = "";
$bill_to_zip = "";
$bill_to_phone = "";
$bill_to_email = "";

//echo $a;
$companyID = $_REQUEST["companyID"];
$rec_id = $_REQUEST["rec_id"];

db();
$dt_sellto = db_query("Select lock_trans, lock_trans_on from loop_invoice_number where unqid = 1");
while ($row_data = array_shift($dt_sellto)) {
	if ($row_data["lock_trans"] == 1) {
		echo "<font size=16 color=red>Invoice number table is locked on " . $row_data["lock_trans_on"] . ", please try after some time.</font><br>";
		exit;
	}
}

db();
$dt_sellto = db_query("Update loop_invoice_number set lock_trans = 1, lock_trans_on = '" . date("Y-m-d H:i:s") . "' where unqid = 1");

$b = "Select * from companyInfo Where ID = '" . $companyID . "'";
//echo $b;
db_b2b();
$csql = db_query($b);
$fet = array_shift($csql);
/*objCo*/


db_b2b();
$dt_sellto = db_query("Select * from b2bbillto where companyid = '" . $companyID . "'  order by billtoid limit 1");
while ($row_sellto = array_shift($dt_sellto)) {
	$bill_to_contact = $row_sellto["name"];
	$bill_to_add = $row_sellto["address"];
	$bill_to_add2 = $row_sellto["address2"];
	$bill_to_city = $row_sellto["city"];
	$bill_to_state = $row_sellto["state"];
	$bill_to_zip = $row_sellto["zipcode"];
	$bill_to_phone = $row_sellto["mainphone"];
	$bill_to_email = $row_sellto["email"];
}

$total_revenue = 0;
$warehouse_id = 0;
$po_ponumber = "";
db();
$dt_sellto = db_query("Select total_revenue, warehouse_id, po_ponumber from loop_transaction_buyer where id = '" . $rec_id . "' ");
while ($row_sellto = array_shift($dt_sellto)) {
	$total_revenue = $row_sellto["total_revenue"];
	$warehouse_id = $row_sellto["warehouse_id"];
	//$po_ponumber = $row_sellto["po_ponumber"];
}

$invoice_re_entry = 0;
$last_process_inv_no = "";
$qry = "SELECT trans_rec_id, invoice_no FROM loop_invoice_creation_history where trans_rec_id = '" . $rec_id . "' ";
db();
$dt_view_res = db_query($qry);
while ($data_row = array_shift($dt_view_res)) {
	$invoice_re_entry = 1;
	$last_process_inv_no = $data_row["invoice_no"];
}

$inv_number = "";

db();
$dt_sellto = db_query("Select invoice_number from loop_invoice_number where unqid = 1");
while ($row_data = array_shift($dt_sellto)) {
	$inv_number = $row_data["invoice_number"];
	if ($invoice_re_entry == 0) {
		$inv_number = $inv_number + 1;
	}
}

if ($invoice_re_entry == 1) {
	$inv_number = preg_replace("/[^0-9.]/", "", $last_process_inv_no);
}

if ($_REQUEST["txt_who_from"] == "UsedCardboardBoxes") {
	$inv_prefix = "LPB";
	db();
	$dt_sellto = db_query("Select * from loop_invoice_items WHERE trans_rec_id = " . $rec_id . " order by id limit 1");
	while ($row_data = array_shift($dt_sellto)) {
		if ($row_data['division_id'] == 3 || $row_data['division_id'] == 4 || $row_data['division_id'] == 5) {
			$inv_prefix = "LPL";
		}
	}
}
if ($_REQUEST["txt_who_from"] == "UCBZeroWaste") {
	$inv_prefix = "ZW";
}

$sql_1 = "Update loop_invoice_details set timestamp = '" . date("Y-m-d H:i:s", strtotime($_REQUEST["invoice_date"])) . "' where trans_rec_id = '" . $rec_id . "'";
db();
db_query($sql_1);

$sql_1 = "Select * from loop_invoice_details Where trans_rec_id = '" . $rec_id . "'";
db();
$res_loop_invoice_details = db_query($sql_1);
$inv_row = array_shift($res_loop_invoice_details);

$po_ponumber = $inv_row["PO"];

$subtotal = 0;
$taxable = 0;
$nooflines = 0;

$thepdf = "<html xmlns:v=\"urn:schemas-microsoft-com:vml\"
xmlns:o=\"urn:schemas-microsoft-com:office:office\"
xmlns:w=\"urn:schemas-microsoft-com:office:word\"
xmlns:st1=\"urn:schemas-microsoft-com:office:smarttags\"
xmlns=\"http://www.w3.org/TR/REC-html40\">

<head>
<meta http-equiv=Content-Type content=\"text/html; charset=windows-1252\">
<meta name=ProgId content=FrontPage.Editor.Document>
<meta name=Generator content=\"Microsoft FrontPage 6.0\">
<meta name=Originator content=\"Microsoft Word 10\">
<link rel=File-List href=\"fullQuote_files/filelist.xml\">

<title> </title>
<o:SmartTagType namespaceuri=\"urn:schemas-microsoft-com:office:smarttags\"
 name=\"PostalCode\"/>
<o:SmartTagType namespaceuri=\"urn:schemas-microsoft-com:office:smarttags\"
 name=\"State\"/>
<o:SmartTagType namespaceuri=\"urn:schemas-microsoft-com:office:smarttags\"
 name=\"City\"/>
<o:SmartTagType namespaceuri=\"urn:schemas-microsoft-com:office:smarttags\"
 name=\"place\"/>
<o:SmartTagType namespaceuri=\"urn:schemas-microsoft-com:office:smarttags\"
 name=\"Street\"/>
<o:SmartTagType namespaceuri=\"urn:schemas-microsoft-com:office:smarttags\"
 name=\"address\"/>
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>David</o:Author>
  <o:Template>Normal</o:Template>
  <o:LastAuthor>David</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>34</o:TotalTime>
  <o:Created>2006-12-26T21:32:00Z</o:Created>
  <o:LastSaved>2006-12-26T21:32:00Z</o:LastSaved>
  <o:Pages>1</o:Pages>
  <o:Words>243</o:Words>
  <o:Characters>1389</o:Characters>
  <o:Company> </o:Company>
  <o:Lines>11</o:Lines>
  <o:Paragraphs>3</o:Paragraphs>
  <o:CharactersWithSpaces>1629</o:CharactersWithSpaces>
  <o:Version>10.2625</o:Version>
 </o:DocumentProperties>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:SpellingState>Clean</w:SpellingState>
  <w:GrammarState>Clean</w:GrammarState>
  <w:Compatibility>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
  </w:Compatibility>
  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
 </w:WordDocument>
</xml><![endif]--><!--[if !mso]><object
 classid=\"clsid:38481807-CA0E-42D2-BF39-B33AF135CC4D\" id=ieooui></object>
<style>
st1\:*{behavior:url(#ieooui) }
</style>
<![endif]-->
<style>
<!--
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-parent:\"\";
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:\"Times New Roman\";
	mso-fareast-font-family:\"Times New Roman\"; margin-left:0in; margin-right:0in; margin-top:0in}
span.SpellE
	{mso-style-name:\"\";
	mso-spl-e:yes}
span.GramE
	{mso-style-name:\"\";
	mso-gram-e:yes}
@page Section1
	{size:8.5in 11.0in;
	margin:.5in .5in .5in .5in;
	mso-header-margin:.5in;
	mso-footer-margin:.5in;
	mso-paper-source:0;}
div.Section1
	{page:Section1;}
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:\"Table Normal\";
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-noshow:yes;
	mso-style-parent:\"\";
	mso-padding-alt:0in 5.4pt 0in 5.4pt;
	mso-para-margin:0in;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:\"Times New Roman\"}
table.MsoTableGrid
	{mso-style-name:\"Table Grid\";
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;1.0pt solid gray;
	border:1.0pt solid windowtext;
	mso-border-alt: .5pt solid windowtext;
	mso-padding-alt:0in 5.4pt 0in 5.4pt;
	mso-border-insideh:.5pt solid windowtext;
	mso-border-insidev:.5pt solid windowtext;
	mso-para-margin:0in;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:\"Times New Roman\";}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:shapelayout v:ext=\"edit\">
  <o:idmap v:ext=\"edit\" data=\"1\"/>
 </o:shapelayout></xml><![endif]-->
<!--[if gte mso 9]>
<xml><o:shapedefaults v:ext=\"edit\" spidmax=\"1027\"/>
</xml><![endif]-->
</head>

<body lang=EN-US style='tab-interval:.5in'>
<div style='text-align:center'><a href='https://www.UsedCardboardBoxes.com'>www.UsedCardboardBoxes.com</a></div>
<br><br>
<table class=MsoTableGrid border=0 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;mso-yfti-tbllook:480;mso-padding-alt:0in 5.4pt 0in 5.4pt; border-collapse:collapse;border:none;mso-border-alt:.5pt solid windowtext;
 mso-yfti-tbllook:480;mso-padding-alt:0in 5.4pt 0in 5.4pt;mso-border-insideh:
 .5pt solid windowtext;mso-border-insidev:.5pt solid windowtext'>
 <tr style='mso-yfti-irow:0'>
  <td width=140 rowspan=6 valign=top style='width:104.7pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><img src=\"image001new.jpg\"></span></p>
  </td>
  <td width=179 valign=top style='width:134.5pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:12.0pt;font-family:Arial'>UsedCardboardBoxes<o:p></o:p></span></p>
  </td>
  <td width=240 valign=top style='width:180.2pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:12.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=175 colspan=2 rowspan=2 valign=middle align=center style='width:131.4pt;border:1.0pt solid ;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><b style='mso-bidi-font-weight:normal'><span
  style='font-family:Arial'>INVOICE<o:p></o:p></span></b></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1'>
  <td width=179 valign=top style='width:134.5pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><st1:Street><st1:address><span style='font-size:12.0pt;
    font-family:Arial'>4032 Wilshire Blvd. Suite #402</span></st1:address></st1:Street><span
  style='font-size:10.0pt;font-family:Arial'><o:p></o:p></span></p>
  </td>
  <td width=240 valign=top style='width:180.2pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:2'>
  <td width=179 valign=top style='width:134.5pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><st1:place><st1:City><span style='font-size:12.0pt;
    font-family:Arial'>Los Angeles</span></st1:City><span style='font-size:12.0pt;
   font-family:Arial'>, </span><st1:State><span style='font-size:12.0pt;
    font-family:Arial'>CA</span></st1:State><span style='font-size:12.0pt;
   font-family:Arial'> </span><st1:PostalCode><span style='font-size:12.0pt;
    font-family:Arial'>90010</span></st1:PostalCode></st1:place><span
  style='font-size:10.0pt;font-family:Arial'><o:p></o:p></span></p>
  </td>
  <td width=240 valign=top style='width:180.2pt;border:none;border-right:1.0pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=96 valign=middle align=center style='width:1.0in;border:1.0pt solid ;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Date<o:p></o:p></span></p>
  </td>
  <td width=79 valign=middle align=center style='width:59.4pt;border:1.0pt solid ;
  border-left:none;mso-border-left-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Invoice #<o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:3'>
  <td width=179 valign=top style='width:134.5pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:12.0pt;font-family:Arial'>1-888-BOXES-88<o:p></o:p></span></p>
  </td>
  <td width=240 valign=top style='width:180.2pt;border:none;border-right:1.0pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=96 valign=middle style='text-align:center; width:1.0in;border:1.0pt solid ;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center;'><span
  style='text-align:center; font-size:10.0pt;font-family:Arial;'>"; ?>
<?php $da =  date_parse($inv_row["timestamp"]);
$thepdf .= $da["month"] . "/" . $da["day"] . "/" . $da["year"];
$thepdf .= "</span></p>
  </td>
  <td width=79 valign=middle style='text-align:center;width:59.4pt;border:1pt solid;
  mso-border-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center;'><span
  style='text-align:center; font-size:10.0pt;font-family:Arial;'>" . $inv_prefix . $inv_number . "</span></p>
	
  </td>
 </tr>
 
 
  <tr style='mso-yfti-irow:3'>
  <td width=179 valign=top style='width:134.5pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'>&nbsp;<o:p></o:p></span></p>
  </td>
  <td>";

if ($_REQUEST["chk_paid_inv"] == 1) {
	$thepdf .= "<p class=MsoNormal align=center style='text-align:center'><span
		style='font-size:18.0pt;font-family:Arial;font-weight:bold;'><o:p>PAID</o:p></span></p>";
}
$thepdf .= "</td>
  <td width=240 valign=top style='width:180.2pt;padding:0in 5.4pt 0in 5.4pt' colspan='2'>
   <p class=MsoNormal><b style='mso-bidi-font-weight:normal'><span
  style='font-family:Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
 </tr>";

$thepdf .= "</tr>
 
  <tr style='mso-yfti-irow:3'>
  <td width=179 valign=top style='width:134.5pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:12.0pt;font-family:Arial'>&nbsp;<o:p></o:p></span></p>
  </td>
  <td width=240 valign=top style='width:180.2pt;border:none;border-right:1.0pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=96 valign=middle style='text-align:center; width:1.0in;border:1.0pt solid ;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center;'>
  <b style='text-align:center; mso-bidi-font-weight:normal'><span
  style='text-align:center; font-family:Arial'>Order ID<o:p></o:p></span></b></p>
  </td>
  <td width=79 valign=middle style='text-align:center; width:59.4pt;border:1pt solid;
  mso-border-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center;'><span
  style='text-align:center; font-size:10.0pt;font-family:Arial;'>" . $rec_id . "</span></p>
	
  </td>
 </tr>
 
 <tr style='mso-yfti-irow:4'>
  <td width=179 valign=top style='width:134.5pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'>&nbsp;<o:p></o:p></span></p>
  </td>
  <td width=240 valign=top style='width:180.2pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=96 valign=top style='width:1.0in;border:none;mso-border-top-alt:
  .5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=79 valign=top style='width:59.4pt;border:none;mso-border-top-alt:
  .5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:5;mso-yfti-lastrow:yes'>
  <td width=179 valign=top style='width:134.5pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=240 valign=top style='width:180.2pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=96 valign=top style='width:1.0in;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
  <td width=79 valign=top style='width:59.4pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
  </td>
 </tr>
</table>
<p class=MsoNormal><span style='font-size:5.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>

<table class=MsoTableGrid cellspacing=0 cellpadding=0
 style='margin-left:23.4pt;border-collapse:collapse;border:none;mso-border-alt:
 .5pt solid windowtext;mso-yfti-tbllook:480;mso-padding-alt:0in 5.4pt 0in 5.4pt;'>
 <tr style='mso-yfti-irow:0'>
  <td align=center width=310 style='width:310.55pt;border:1.0pt solid ;border-top:1.0pt solid ;border-bottom:1.0pt solid ;mso-border-alt:
  .5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span style='font-size:10.0pt;font-family:
  Arial'>Bill To<o:p></o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  <td align=center width=310 style='width:310.55pt;border:1.0pt solid ;border-top:1.0pt solid ;border-bottom:1.0pt solid ;mso-border-alt:
  .5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span style='font-size:10.0pt;font-family:
  Arial'>Ship To<o:p></o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1'>
  <td width=310 style='width:310.55pt;border-top:none;border-left:1.0pt solid ;
  border-bottom:none;border-right:1.0pt solid ;mso-border-top-alt:
  .5pt solid windowtext;mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:
  .5pt solid windowtext;mso-border-right-alt:.5pt solid windowtext;padding:
  0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" .  $bill_to_contact . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  <td width=310 style='width:310.35pt;border:none;border-left:1.0pt solid ;border-right:1.0pt solid ;
  mso-border-left-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" .  $fet['shipContact'] . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:2'>
  <td width=310 style='width:310.55pt;border-top:none;border-left:1.0pt solid ;
  border-bottom:none;border-right:1.0pt solid ;mso-border-left-alt:
  .5pt solid windowtext;mso-border-right-alt:.5pt solid windowtext;padding:
  0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $fet['company'] . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  <td width=310 style='width:310.35pt;border:none;border-left:1.0pt solid ;border-right:1.0pt solid ;
  mso-border-left-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $fet['company'] . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>

 </tr>
 <tr style='mso-yfti-irow:3'>
  <td width=310 style='width:310.55pt;border-top:none;border-left:1.0pt solid ;
  border-bottom:none;border-right:1.0pt solid ;mso-border-left-alt:
  .5pt solid windowtext;mso-border-right-alt:.5pt solid windowtext;padding:
  0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $bill_to_add . "&nbsp;"; ?>

<?php if ($bill_to_add2 != "") $thepdf .= $bill_to_add2; ?>
<?php $thepdf .= "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  <td width=310 style='width:310.35pt;border:none;border-left:1.0pt solid ;border-right:1.0pt solid ;
  mso-border-left-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $fet['shipAddress'] . "&nbsp;"; ?><?php if ($fet['shipAddress2'] != "0") $thepdf .= $fet['shipAddress2']; ?>
<?php $thepdf .= "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:4'>
  <td width=310 style='width:310.55pt;border-top:none;border-left:1.0pt solid ;
  border-bottom:none;border-right:1.0pt solid ;mso-border-left-alt:
  .5pt solid windowtext;mso-border-right-alt:.5pt solid windowtext;padding:
  0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $bill_to_city . ", " . $bill_to_state . " " . $bill_to_zip . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  <td width=310 style='width:310.35pt;border:none;border-left:1.0pt solid ;border-right:1.0pt solid ;
  mso-border-left-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $fet['shipCity'] . ", " . $fet['shipState'] . " " . $fet['shipZip'] . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  </td>
 </tr>
 <tr style='mso-yfti-irow:5'>
  <td width=310 style='width:310.5pt;border:none;border-left:1.0pt solid ;border-right:1.0pt solid ;
  mso-border-left-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $bill_to_phone  . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  <td width=310 style='width:310.35pt;border:none;border-left:1.0pt solid ;border-right:1.0pt solid ;
  mso-border-left-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $fet['shipPhone'] . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:6;mso-yfti-lastrow:yes'>
  <td width=310 style='width:310.55pt;border:none;border-bottom:1.0pt solid ;border-left:1.0pt solid ;border-right:1.0pt solid ;
  mso-border-left-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $bill_to_email . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
  <td width=310 style='width:310.35pt;border:none;border-bottom:1.0pt solid ;border-left:1.0pt solid ;border-right:1.0pt solid ;
  mso-border-left-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-right-alt:.5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal><span style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" . $fet['shipemail'] . "</o:p></span></p>
  </td>
  <td width=20 style='width:20pt;'>
  <p class=MsoNormal align=center style='text-align:center;'><b
  style='mso-bidi-font-weight:normal'><span style='font-size:10.0pt;font-family:
  Arial'><o:p>&nbsp;</o:p></span></b></p>
  </td>
 </tr>
</table> 

<p class=MsoNormal><span style='font-size:5.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
<br>
<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;border:none;mso-border-alt:.5pt solid windowtext;
 mso-yfti-tbllook:480;mso-padding-alt:0in 5.4pt 0in 5.4pt;mso-border-insideh:
 .5pt solid windowtext;mso-border-insidev:.5pt solid windowtext'>
 <tr style='mso-yfti-irow:0'>
  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;mso-border-alt:
  .5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>P.O. Number</span></p>
  </td>
  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Terms<o:p></o:p></span></p>
  </td>
  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Representative<o:p></o:p></span></p>
  </td>
  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Ship Date<o:p></o:p></span></p>
  </td>
  <td align=center width=157 style='width:117.4pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Via<o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1;mso-yfti-lastrow:yes;height:19.65pt'>
  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;border-top:
  none;mso-border-top-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" .  stripslashes($inv_row["PO"]) . "</o:p></span></p>
  </td>
  <td align=center width=156 style='width:117.35pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid windowtext;border-right:1.0pt solid windowtext;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" .  $inv_row["terms"] . "</o:p></span></p>
  </td>
  <td align=center width=156 style='width:117.35pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid windowtext;border-right:1.0pt solid windowtext;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>"; ?>
<?php

$thepdf .= $inv_row["rep"];
?>
<?php $thepdf .= "</span></p>
  </td>
  <td align=center width=156 style='width:117.35pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid windowtext;border-right:1.0pt solid windowtext;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp; ";

$thepdf .= $inv_row["shipdate"];

?>
<?php $thepdf .= "</o:p></span></p>
  </td>
  <td align=center width=157 style='width:117.4pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid windowtext;border-right:1.0pt solid windowtext;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>&nbsp;" . $inv_row['via'] . "</span></p>
  </td>
 </tr>
</table>
<br>
<p class=MsoNormal><span style='font-size:9.0pt;font-family:Arial'><o:p>&nbsp;</o:p><o:p>&nbsp;</o:p></span></p>

<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
 style='border-collapse:collapse;border:none;mso-border-alt:.5pt solid windowtext;
 mso-yfti-tbllook:480;mso-padding-alt:0in 5.4pt 0in 5.4pt;mso-border-insideh:
 .5pt solid windowtext;mso-border-insidev:.5pt solid windowtext'>
 <tr style='mso-yfti-irow:0'>
  <td align=center width=100 style='width:52.35pt;border:1.0pt solid windowtext;mso-border-alt:
  .5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Quantity</span></p>
  </td>
  <td align=center width=100 style='width:92.35pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Item<o:p></o:p></span></p>
  </td>
  <td align=center width=420 style='width:306.8pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Description<o:p></o:p></span></p>
  </td>
  <td align=center width=80 style='width:62.35pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Price<o:p></o:p></span></p>
  </td>
  <td align=center width=80 style='width:62.4pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>Total<o:p></o:p></span></p>
  </td>
 </tr>"; ?>
<?php
$nooflines = 0;
$c = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $rec_id . " ORDER BY id ASC";
db();
$boxsql = db_query($c);
$x = 0;
while ($bx = array_shift($boxsql)) {
	$x = $x + 1;

	$category = "";
	$inv_qry1 = "SELECT * FROM category_master WHERE category_id = " . $bx['category_id'];
	db();
	$inv_res1 = db_query($inv_qry1);
	while ($inv_row1 = array_shift($inv_res1)) {
		$category = $inv_row1["category"];
	}
?>
<?php
	$thepdf .= "<tr style='mso-yfti-irow:1;mso-yfti-lastrow:yes;height:19.65pt'>
  <td align=center width=100 style='width:52.35pt;border:1.0pt solid windowtext;border-top:
  none;mso-border-top-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" .  $bx['quantity'] . "</o:p></span></p>
  </td>
  <td align=center width=100 style='width:92.35pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid windowtext;border-right:1.0pt solid windowtext;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'><o:p>&nbsp;" .  $category . "</o:p></span></p>
  </td>
  <td align=center width=420 style='width:306.8pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid windowtext;border-right:1.0pt solid windowtext;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=left style='text-align:left'><span
  style='font-size:10.0pt;font-family:Arial'>"; ?>
<?php

	$thepdf .= $bx['description'];
	?>
<?php $thepdf .= "</span></p>
  </td>
  <td align=center width=80 style='width:62.35pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid windowtext;border-right:1.0pt solid windowtext;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'><o:p>$" . number_format($bx['price'], 2) . "</o:p></span></p>
  </td>
  <td align=center width=80 style='width:62.4pt;border-top:none;border-left:none;
  border-bottom:1.0pt solid windowtext;border-right:1.0pt solid windowtext;
  mso-border-top-alt:.5pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;
  mso-border-alt:.5pt solid windowtext;padding:0in 3.4pt 0in 3.4pt;height:19.65pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>$"; ?>
<?php
	if ($bx["total"] == 0) {
		$thepdf .= number_format($bx["quantity"] * $bx["price"], 2);
		$subtotal = $subtotal + str_replace(",", "", strval($bx['price'] * $bx['quantity']));
	} else {
		$thepdf .= $bx["total"];
		$subtotal = $subtotal + str_replace(",", "", $bx["total"]);
	}
	?>
<?php
	$thepdf .= "</o:p></span></p>
  </td>
 </tr>"; ?>
<?php }
?>

<?php
$line_counter = 19 - intval($x);
while ($x < ($line_counter)) {

	$thepdf .= "  <tr style='mso-yfti-irow:0'>
  <td align=center width=100 style='width:52.35pt;border:1.0pt solid windowtext;mso-border-alt:
  .5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>&nbsp;</span></p>
  </td>
  <td align=center width=100 style='width:92.35pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>&nbsp;<o:p></o:p></span></p>
  </td>
  <td align=center width=420 style='width:306.8pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>&nbsp;<o:p></o:p></span></p>
  </td>
  <td align=center width=80 style='width:62.35pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>&nbsp;<o:p></o:p></span></p>
  </td>
  <td align=center width=80 style='width:62.4pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>&nbsp;<o:p></o:p></span></p>
  </td>
 </tr>";

	$x++;
}
$thepdf .= "

<tr style='mso-yfti-irow:0'>
  <td align=left width=620 colspan='3' style='width:52.35pt;border:none;padding:0in 5.4pt 0in 5.4pt'>
<br>
	<table class=MsoTableGrid border=1 cellspacing=0 cellpadding=0
	 style='border-collapse:collapse;border:none;mso-border-alt:.5pt solid windowtext;
	 mso-yfti-tbllook:480;mso-padding-alt:0in 5.4pt 0in 5.4pt;mso-border-insideh:
	 .5pt solid windowtext;mso-border-insidev:.5pt solid windowtext'>

	 <tr style='mso-yfti-irow:0'>
	  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;mso-border-alt:
	  .5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
	  <p class=MsoNormal align=center style='text-align:center'><span
	  style='font-size:10.0pt;font-family:Arial'>Phone #</span></p>
	  </td>
	  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;border-left:
	  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
	  padding:0in 5.4pt 0in 5.4pt'>
	  <p class=MsoNormal align=center style='text-align:center'><span
	  style='font-size:10.0pt;font-family:Arial'>Fax #<o:p></o:p></span></p>
	  </td>
	  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;border-left:
	  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
	  padding:0in 5.4pt 0in 5.4pt'>
	  <p class=MsoNormal align=center style='text-align:center'><span
	  style='font-size:10.0pt;font-family:Arial'>Email<o:p></o:p></span></p>
	  </td>
	 
	 </tr>

	 <tr style='mso-yfti-irow:0'>
	  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;mso-border-alt:
	  .5pt solid windowtext;padding:0in 5.4pt 0in 5.4pt'>
	  <p class=MsoNormal align=center style='text-align:center'><span
	  style='font-size:10.0pt;font-family:Arial'>1-888-BOXES-88 x 741</span></p>
	  </td>
	  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;border-left:
	  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
	  padding:0in 5.4pt 0in 5.4pt'>
	  <p class=MsoNormal align=center style='text-align:center'><span
	  style='font-size:10.0pt;font-family:Arial'>323-315-4194<o:p></o:p></span></p>
	  </td>
	  <td align=center width=156 style='width:117.35pt;border:1.0pt solid windowtext;border-left:
	  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
	  padding:0in 5.4pt 0in 5.4pt'>
	  <p class=MsoNormal align=center style='text-align:center'><span
	  style='font-size:10.0pt;font-family:Arial'>AR@usedcardboardboxes.com<o:p></o:p></span></p>
	  </td>
	 </tr>
	 
	</table>  
  
  </td>
  <td align=center width=80 style='width:62.35pt;border:1.0pt solid windowtext;border-left:1.0pt solid windowtext;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'><b>Total</b><o:p></o:p></span></p>
  </td>
  <td align=center width=80 style='width:62.4pt;border:1.0pt solid windowtext;border-left:
  none;mso-border-left-alt:.5pt solid windowtext;mso-border-alt:.5pt solid windowtext;
  padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:10.0pt;font-family:Arial'>$" . number_format($subtotal + $taxable * isset($g_salestax), 2) . "<o:p></o:p></span></p>
  </td>
 </tr>
</table> 

<p class=MsoNormal><span style='font-size:9.0pt;font-family:Arial'><o:p>&nbsp;</o:p><o:p>&nbsp;</o:p></span></p>


<p class=MsoNormal><span style='font-size:2.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
<p class=MsoNormal><span style='font-size:2.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>
<br><br>
<p class=MsoNormal align=center style='text-align:center'><span style='font-size:8pt;font-family:Arial;color:green'>UCB is more than just boxes!</span></p>
<p class=MsoNormal align=center style='text-align:center'><span style='font-size:10pt;font-family:Arial;color:green'>We have <b>SUPER SACKS, LINERS, PALLETS, SLIP SHEETS, SHRINK and TAPE</b>.</span></p>
<p class=MsoNormal align=center style='text-align:center'><span style='font-size:8pt;font-family:Arial;color:green'>Ask your Account Manager for pricing and save!</span></p>

<p class=MsoNormal><span style='font-size:5.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=center style='text-align:center'><span style='font-size:7pt;font-family:Arial;color:green'>UsedCardboardBoxes (UCB) expects payment within the terms offered, so please process this invoice accordingly. UCB reserves the right to charge1.5% interest PER MONTH, on the total amount due, up to 10%, for any invoice that ages 30 days past it's due date.</span></p>

<p class=MsoNormal><span style='font-size:5.0pt;font-family:Arial'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=center style='text-align:center'><i><span style='font-size:9.0pt;font-family:Arial;color:green'>Save Time, Save Money, Save <span class=SpellE>Trees<span class=GramE>!<sup>TM</sup></span></span><sup><o:p></o:p></sup></span></i></p>
<p class=MsoNormal align=center style='text-align:center'><b><span style='font-size:9.0pt;font-family:Arial;color:green'>www.UsedCardboardBoxes.com </span></b></p>

</body>
</html>";

$fname_no_path = "";

if ($invoice_re_entry == 1) {
	$fname = "invoice_files/" . "UsedCardboardBoxes_Invoice_" . $inv_prefix . $inv_number . "R.pdf";
	$fname_no_path = "UsedCardboardBoxes_Invoice_" . $inv_prefix . $inv_number . "R.pdf";
} else {
	$fname = "invoice_files/" . "UsedCardboardBoxes_Invoice_" . $inv_prefix . $inv_number . ".pdf";
	$fname_no_path = "UsedCardboardBoxes_Invoice_" . $inv_prefix . $inv_number . ".pdf";
}


function url_get_contents(string $Url): string
{
	if (!function_exists('curl_init')) {
		die('CURL is not installed!');
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $Url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);

	return $output;
}


require_once 'mpdf_new/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf([
	'mode' => 'utf-8',
	'format' => 'A4-P', 'font_size' => 16, 'margin_left' => 10,
	'orientation' => 'P',
	'margin_right' => 10,
	'margin_top' => 16,
	'margin_bottom' => 16,
	'margin_header' => 9,
	'margin_footer' => 9
]);

$mpdf->SetDisplayMode('fullpage');

//$stylesheet = url_get_contents('assets/mpdfstyletables.css');
$stylesheet = "";
$mpdf->WriteHTML($stylesheet, 1);	// The parameter 1 tells that this is css/style only and no 
$mpdf->WriteHTML($thepdf);
srand((float) microtime() * 1000000);

$random_number = rand();

//$mpdf->Output($fname,'F'); 
$mpdf->Output($fname, 'F');

db();
db_query("Update loop_transaction_buyer set invoice_re_entry_allowed = 0, loop_invoice_paid_flg = '" . $_REQUEST["chk_paid_inv"] . "', invoice_created_in_loop = 1, who_from = '" . $_REQUEST["txt_who_from"] . "', loop_qb_invoice_no = '" . $inv_prefix . $inv_number . "', invoice_no_qb_name = '" . $fname_no_path . "', invoice_no_qb_by = '" . $_COOKIE['userinitials'] . "', invoice_no_qb_dt = '" . date("Y-m-d H:i:s") . "' where id = '" . $_REQUEST["rec_id"] . "'");


db();
db_query("Insert into loop_invoice_creation_history	set trans_rec_id = '" . $rec_id . "', invoice_no = '" . $inv_prefix . $inv_number . "', invoice_file_name = '" . $fname_no_path . "', created_by = '" . $_COOKIE['userinitials'] . "', created_on = '" . date("Y-m-d H:i:s") . "'");

if ($invoice_re_entry == 0) {
	//db_query("Update tblvariable set variablevalue = '". $inv_number . "' where variablename = 'invoice_number'", db() );
	db();
	db_query("Update loop_invoice_number set invoice_number = '" . $inv_number . "' where unqid = 1");
}

//table is unlock
db();
$dt_sellto = db_query("Update loop_invoice_number set lock_trans = 0 where unqid = 1");


//Add notes
$notes_str = "System generated log - Invoice created in Loops on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'];
$dt_notes_qry = "Insert into loop_transaction_notes (company_id, rec_id, rec_type, employee_id, message) select '" . $warehouse_id . "','" . $rec_id . "', 'Supplier', '" . $_COOKIE['employeeid'] . "', '" . str_replace("'", "\'", $notes_str) . "'";
db();
$dt_res_notes = db_query($dt_notes_qry);



$file_createdflg = "yes";

if ($_REQUEST['online_invoicing'] == "None" || $_REQUEST['online_invoicing'] == "") {
	$sellto_eml = "";
	$acc_owner = "";
	$acc_owner_eml = "";
	$shipto_name = "";
	$shipto_email = "";
	db_b2b();
	$result_crm = db_query("Select * from companyInfo Where ID = " . $_REQUEST["companyID"]);
	$company_name = "";
	$to_eml_crm = "";
	$sellto_name = "";
	while ($myrowsel_main = array_shift($result_crm)) {
		$shipto_name = $myrowsel_main["shipContact"];
		$shipto_email = $myrowsel_main["shipemail"];

		$sellto_name = $myrowsel_main["contact"];
		$sellto_eml = $myrowsel_main["email"];
		db_b2b();
		$result_n = db_query("Select name, email from employees Where employeeID = " . $myrowsel_main["assignedto"]);
		while ($myrowsel_n = array_shift($result_n)) {
			$acc_owner = $myrowsel_n["name"];
			$acc_owner_eml = $myrowsel_n["email"];
		}

		$billto_name = "";
		$billto_ph = "";

		db_b2b();
		$result_n = db_query("Select * from b2bbillto Where companyid = " . $_REQUEST["companyID"] . " order by billtoid limit 1");
		while ($myrowsel_n = array_shift($result_n)) {
			$billto_name = $myrowsel_n["name"];
			$billto_ph = $myrowsel_n["mainphone"];
		}

		$billto_eml = "";
		db_b2b();
		$result_n = db_query("Select * from b2bbillto Where companyid = " . $_REQUEST["companyID"] . " order by billtoid");
		while ($myrowsel_n = array_shift($result_n)) {
			$billto_eml .= $myrowsel_n["email"] . ",";
		}
		if ($billto_eml != "") {
			$billto_eml = substr($billto_eml, 0, strlen($billto_eml) - 1);
		}

		$inv_date = date("m/d/Y", strtotime($inv_row["timestamp"]));

		$credit_term = $inv_row["terms"];
		if ($inv_row["terms"] == 'Net 10') {
			$term_days = 10;
		}

		if ($inv_row["terms"] == 'Net 15') {
			$term_days = 15;
		}

		if ($inv_row["terms"] == 'Net 30') {
			$term_days = 30;
		}

		if ($inv_row["terms"] == 'Net 45') {
			$term_days = 45;
		}

		if ($inv_row["terms"] == 'Net 60') {
			$term_days = 60;
		}

		if ($inv_row["terms"] == 'Net 75') {
			$term_days = 75;
		}

		if ($inv_row["terms"] == 'Net 90') {
			$term_days = 90;
		}

		if ($inv_row["terms"] == 'Net 120') {
			$term_days = 120;
		}

		if ($inv_row["terms"] == 'Net 120 EOM +1' || $inv_row["terms"] == "Net 120 EOM  1") {
			$date_30_11 = date('m/d/Y', strtotime($inv_date . "+ 120 days"));
			$date_30_1 = date('m/d/Y', strtotime($date_30_11 . ' first day of +1 month'));

			$notes_date = new DateTime($inv_date);
			$curr_date = new DateTime($date_30_1);
			$term_days = $curr_date->diff($notes_date)->days;
		}
		if ($inv_row["terms"] == 'Net 30 EOM +1' || $inv_row["terms"] == "Net30EOM+1") {
			$date_30_11 = date('m/d/Y', strtotime($inv_date . "+ 30 days"));
			$date_30_1 = date('m/d/Y', strtotime($date_30_11 . ' first day of +1 month'));

			$notes_date = new DateTime($inv_date);
			$curr_date = new DateTime($date_30_1);
			$term_days = $curr_date->diff($notes_date)->days;
		}
		if ($inv_row["terms"] == 'Net 45 EOM +1' || $inv_row["terms"] == "Net 45 EOM +1") {
			$date_30_11 = date('m/d/Y', strtotime($inv_date . "+ 45 days"));
			$date_30_1 = date('m/d/Y', strtotime($date_30_11 . ' first day of +1 month'));

			$notes_date = new DateTime($inv_date);
			$curr_date = new DateTime($date_30_1);
			$term_days = $curr_date->diff($notes_date)->days;
		}

		if ($inv_row["terms"] == '1% 10 Net 30' || $inv_row["terms"] == '1% 15 Net 30') {
			$term_days = 30;
		}

		if (($inv_row["terms"] == 'Due On Receipt') || ($inv_row["terms"] == 'PrePaid') || ($inv_row["terms"] == 'Other-See Notes')) {
			$term_days = 0;
		}


		//$inv_date_dt = new DateTime($inv_row["timestamp"]);
		//$curr_date = new DateTime();
		//$due_date_days = $curr_date->diff($inv_date_dt)->days;	

		$inv_date_dt = Date($inv_row["timestamp"]);

		$due_date1 = strtotime("+ " . $term_days . " days", strtotime($inv_date_dt));
		$due_date = date("m/d/Y", $due_date1);

		$inv_info = "<b>Invoice #:</b> " . $inv_prefix . $inv_number . "<br>";
		$inv_info .= "<b>Invoice Amount:</b> $" . number_format($subtotal + $taxable * isset($g_salestax), 2) . "<br>";
		$inv_info .= "<b>Invoice Date:</b> " . $inv_date . "<br>";
		$inv_info .= "<b>Credit Terms:</b> " . $credit_term . "<br>";
		$inv_info .= "<b>Due Date:</b> " . $due_date . "<br>";

		$order_no = $rec_id;
		if ($po_ponumber != "") {
			$order_no = $rec_id . " (PO " . $po_ponumber . ")";
		}

		//Send Internal Email
		$eml_confirmation = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
			<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'><style>
			@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');
			</style><style scoped>
			.tablestyle {
			   width:800px;
			}
			table.ordertbl tr td{
				padding:4px;
			}
			@media only screen and (max-width: 768px) {
				.tablestyle {
				   width:98%;
				}
			}
			</style></head><body style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";

		$eml_confirmation .= "<div style='padding:20px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";

		$eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER # " . $rec_id . "</a> (PO " . $po_ponumber . ") </span>
			<br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#000000;\" ><a href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=" . $companyID . "&show=transactions&warehouse_id=" . $warehouse_id . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . $rec_id . "&display=buyer_payment'>" . get_nickname_val('', $companyID) . "</a></div>
			<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Request to create invoice in QuickBooks</div></td></tr>";

		$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">Mooneem Bookkeeping Team, please create the matching invoice in QuickBooks and upload it into loops. Please follow all transaction log and bookkeeper notes within the transaction.
			</div>	</td></tr>";

		$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Invoice details</span>
			<br>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Invoice #:</strong> " . $inv_prefix . $inv_number . "</div>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Invoice Amount:</strong> $" . number_format(str_replace(",", "", $total_revenue), 2) . "</div>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Invoice Date:</strong> " . $inv_date . "</div>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Credit Terms:</strong> " . $credit_term . "</div>
			<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Bookkeeper Notes:</strong> " . $inv_row["bookkeeper"] . "</div>
			<br><br></td></tr>";

		$eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";

		$from_email = "accounting@usedcardboardboxes.com";
		$to_email = "bk@mooneem.com";
		$cc_email = "AR@UsedCardboardBoxes.com";

		//$to_email = "prasad@extractinfo.com";
		//$cc_email = "";
		sendemail_php_function(null, '', $to_email, $cc_email, '', $from_email, $from_email, $from_email, "Invoice " . $inv_prefix . $inv_number . " Created in Loops for Order #" . $rec_id, $eml_confirmation);
		//Send Internal Email		

		$eml_confirmation = "<html style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"><head><link rel='preconnect' href='https://fonts.gstatic.com'>
			<link href='https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap' rel='stylesheet'><style>
			@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600&display=swap');
			</style><style scoped>
			.tablestyle {
			   width:800px;
			}
			table.ordertbl tr td{
				padding:4px;
			}
			@media only screen and (max-width: 768px) {
				.tablestyle {
				   width:98%;
				}
			}
			</style></head><body style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'!important;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">";

		$eml_confirmation .= "<div style='padding:5px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";

		$eml_confirmation .= "<tr><td><a href='https://www.usedcardboardboxes.com/'><img src='https://www.ucbzerowaste.com/images/logo2.png' alt='moving boxes'></a></td></tr>";

		$eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER #" . $order_no . "</span><br><br>
			<div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >We sincerely appreciate your business!</div></td></tr>";
		//&#128512;
		$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">Your transaction is complete and attached is your invoice. 
			Please make arrangements to pay on time within your agreed upon terms. Any invoices paid after the due date may result in additional fees.</div>
			</td></tr>";

		$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\">" . $inv_info . "</span>
			<br><br></td></tr>";

		$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:12pt;color:#808080;\"><b>Accepted Payment Methods:</b> ETF, ACH, wire transfer, check and credit card.</span>
			<br><br></td></tr>";

		$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:10pt;color:#767171; margin-top:3px;\">Thank you again for Order #" . $rec_id . " and the opportunity to work with you!</div></td></tr>";

		$signature = "<br><table cellspacing='10'><tr><td style='border-right: 2px solid #66381C; padding-right:10px;'><a href=' https://www.usedcardboardboxes.com/' target='_blank'><img src='https://www.ucbzerowaste.com/images/logo2.png'></a></td>";
		$signature .= "<td><p style='font-size:13pt;color:#538135'>";
		$signature .= "<u>Accounts Receivable Team</u><br>UsedCardboardBoxes (UCB)</p>";
		$signature .= "<span style='font-family: Montserrat, sans-serif; font-size:12pt; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
		$signature .= "323-724-2500 x741<br><br>";
		$signature .= "How can we improve?  Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'>CEO@UsedCardboardBoxes.com</a></span>";
		$signature .= "</td></tr></table>";

		$eml_confirmation .= "<br><br><tr><td>" . $signature . "</td></tr>";
		$eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";

		//

?>
<form name="email_inv_frm" id="email_inv_frm" action="loop_invoice_send_email.php" method="post"
    onSubmit="return formCheck_eml(this);">

    <table>
        <tr>
            <td width="10%">To:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailto" name="txtemailto"
                    value="<?php echo $billto_eml; ?>"></td>
        </tr>

        <tr>
            <td width="10%">Cc:</td>
            <td width="90%"> <input size=60 type="text" id="txtemailcc" name="txtemailcc"
                    value="accounting@usedcardboardboxes.com;AR@usedcardboardboxes.com"></td>
        </tr>

        <tr>
            <td width="10%">Subject:</td>
            <td width="90%"><input size=90 type="text" id="txtemailsubject" name="txtemailsubject"
                    value="UsedCardboardBoxes Invoice <?php echo $inv_prefix . $inv_number; ?>"></td>
        </tr>
        <tr>
            <td width="10%">Invoice:</td>
            <td width="90%"><a target='_blank' href="<?php echo $fname ?>">
                    <?php echo $fname_no_path; ?>
                </a></td>
        </tr>

        <tr>
            <td valign="top" width="10%">Body:</td>
            <td width="1000px" id="bodytxt">
                <?php


						require_once("richtexteditornew/include_rte.php");
						$rte = new RichTextEditor();
						$rte->Text = $eml_confirmation;
						$rte->Name = "Editor";
						$rte->ID = "Editor_emailmess";
						$rte->Width = "800px";
						$rte->Height = "300px";
						$rte->Skin = "nocolor";
						$rte->ToolbarItems = "{bold,italic,underline,linethrough} {insertorderedlist,insertunorderedlist} {justifyleft,justifycenter,justifyright,justifyfull} {forecolor,backcolor} {fontname,fontsize}";
						$rte->MvcInit();
						echo $rte->GetString();

						//onclick="btnsendemlclick_inv_eml()"
						?>
                <div style="height:15px;">&nbsp;</div>
                <input type="submit" name="send_quote_email" id="send_quote_email" value="Submit">

                <input type="hidden" name="ID" id="ID" value="<?php echo  $_REQUEST["companyID"]; ?>" />
                <input type="hidden" name="warehouse_id" id="warehouse_id"
                    value="<?php echo  $_REQUEST["warehouse_id"]; ?>" />

                <input type="hidden" name="rec_id" id="rec_id" value="<?php echo  $_REQUEST["rec_id"]; ?>" />
                <input type="hidden" name="hidden_reply_eml" id="hidden_reply_eml" value="" />
                <input type="hidden" name="hidden_sendemail" id="hidden_sendemail" value="inemailmode">

            </td>
        </tr>

    </table>
</form>
<?php
	}
} else {

	$parent_comp_id = 0;
	$online_invoicing_parent = "";
	$dt_view_qry = "SELECT parent_comp_id from companyInfo WHERE ID=" . $_REQUEST["companyID"];
	db_b2b();
	$dt_view_res = db_query($dt_view_qry);
	while ($dt_view_row = array_shift($dt_view_res)) {
		$parent_child_compid = $dt_view_row["parent_comp_id"];
	}

	if ($parent_child_compid > 0) {
		$dt_view_qry = "SELECT online_invoicing from companyInfo WHERE ID = " . $parent_child_compid;
		db_b2b();
		$dt_view_res = db_query($dt_view_qry);
		while ($dt_view_row = array_shift($dt_view_res)) {
			$online_invoicing_parent = $dt_view_row["online_invoicing"];
		}
	}

	$sql_onlineinv = "SELECT online_invoicing FROM companyInfo WHERE ID = " . $_REQUEST["companyID"];
	db_b2b();
	$rec_onlineinv = db_query($sql_onlineinv);
	$rec_onlineinvrow = array_shift($rec_onlineinv);

	if ($online_invoicing_parent != "" && ($rec_onlineinvrow["online_invoicing"] == "None" || $rec_onlineinvrow["online_invoicing"] == "")) {
		$online_invoicing = $online_invoicing_parent;
	} else {
		$online_invoicing = $rec_onlineinvrow["online_invoicing"];
	}

	if ($online_invoicing == "None" || $online_invoicing == "") {
	?>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
    <tr align="middle">
        <?php if ($inv_file_name == "") { ?>
        <td bgColor="#fb8a8a" colSpan="2">
            <?php } else { ?>
        <td bgColor="#99FF99" colSpan="2">
            <?php } ?>
            <font size="1">Re-Send Email&nbsp;</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 100px" class="style1">
            <font size="1">Invoice File</font>
            </font>
        </td>
        <td align="left" height="13" style="width: 235px" class="style1">
            <?php
					echo $inv_file_name;
					?>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 100px" class="style1">
            <font size="1">Who From?</font>
            </font>
        </td>
        <td align="left" height="13" style="width: 235px" class="style1">
            <?php
					echo $who_from;
					?>
        </td>
    </tr>

    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 100px" class="style1">
            <font size="1">Put PAID on invoice?</font>
            </font>
        </td>
        <td align="left" height="13" style="width: 235px" class="style1">
            <?php

					if (isset($loop_invoice_paid_flg) == 1) {
						echo "Yes";
					} else {
						echo "No";
					}
					?>
        </td>
    </tr>
    <tr>
        <td colspan=2 align=center>
            <font size="1">
                <input type="button" name="btnsend_inv_eml" id="btnsend_inv_eml" value="Re-Send Email"
                    onclick="send_invoice_eml_only(<?php echo $_REQUEST['rec_id']; ?>, <?php echo $_REQUEST['ID']; ?>, <?php echo $_REQUEST['warehouse_id']; ?>)" />
            </font>
        </td>
    </tr>
</table>
<?php 		} //end if online inv
	else {

	?>
<div id="main_d">
    <?php

			$inv_date_org = "";
			$qry = "SELECT  timestamp FROM loop_invoice_details where trans_rec_id = " . $_REQUEST['rec_id'];
			db();
			$dt_view_res = db_query($qry);
			while ($data_row = array_shift($dt_view_res)) {
				$inv_date_org = date("m/d/Y", strtotime($data_row["timestamp"]));
			}

			$inv_qry = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $_REQUEST['rec_id'];
			$inv_res = db_query($inv_qry);
			$inv_rec = tep_db_num_rows($inv_res);
			if ($inv_rec > 0) {
				$inv_file_name = "";
				$who_from = "";
				$qry = "SELECT invoice_no_qb_name, who_from FROM loop_transaction_buyer where id = " . $_REQUEST['rec_id'];
				$dt_view_res = db_query($qry);
				while ($data_row = array_shift($dt_view_res)) {
					$who_from = $data_row["who_from"];
					if ($data_row["invoice_no_qb_name"] != "") {
						$inv_file_name = "<a target='_blank' href='invoice_files/" . $data_row["invoice_no_qb_name"] . "'>" . $data_row["invoice_no_qb_name"] . "</a>";
					}
				}
				//
				$sql_onlineinv = "SELECT online_inv_confirmed, online_inv_confirmed_by, online_inv_confirmed_on FROM loop_transaction_buyer WHERE id = " . $_REQUEST['rec_id'];
				db();
				$rec_onlineinv = db_query($sql_onlineinv);
				$rec_onlineinvrow = array_shift($rec_onlineinv);
				$online_inv_confirmed = $rec_onlineinvrow["online_inv_confirmed"];
				$online_inv_confirmed_by = $rec_onlineinvrow["online_inv_confirmed_by"];
				$online_inv_confirmed_on = $rec_onlineinvrow["online_inv_confirmed_on"];
			?>
    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px">
        <tr align="middle">
            <?php if ($inv_file_name == "") { ?>
            <td bgColor="#fb8a8a" colSpan="2">
                <?php } else { ?>
            <td bgColor="#99FF99" colSpan="2">
                <?php } ?>
                <font size="1">Customer Online Invoicing&nbsp;</font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Invoice File</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <?php
							echo $inv_file_name;
							?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Who From?</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <?php
							echo $who_from;
							?>
            </td>
        </tr>

        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1">
                <font size="1">Put PAID on invoice?</font>
                </font>
            </td>
            <td align="left" height="13" style="width: 235px" class="style1">
                <?php
							if (isset($loop_invoice_paid_flg) == 1) {
								echo "Yes";
							} else {
								echo "No";
							}
							?>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td colspan=2 align=left class="style1">
                <font size="1">
                    <font color="red">Online Invoicing -
                        <?php echo $online_invoicing; ?>, no need to send Email.
                    </font>
            </td>
        </tr>
    </table>
    <br>
    <table cellSpacing="1" cellPadding="1" border="0" style="width: 300px" id="cust_online_inv_tbl">
        <tr align="middle">
            <?php if ($online_inv_confirmed == "0") { ?>
            <td bgColor="#fb8a8a" colSpan="2">
                <?php } else { ?>
            <td bgColor="#99FF99" colSpan="2">
                <?php } ?>
                <font size="1">Online Invoicing Customer System&nbsp;</font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">
            <td height="13" style="width: 100px" class="style1" colSpan="2" align="center">

                <?php
							if ($online_inv_confirmed == 0) {
							?>
                Click on the Confirm button to ensure that the invoice is uploaded in the Customer Online Invoicing
                System. <br>
                <form>
                    <input type="button" name="customer_onlineinv_btn" id="customer_onlineinv_btn" value="Confirm"
                        onclick="customer_onlineinv_confirmed(1, <?php echo $_REQUEST['rec_id']; ?>, <?php echo $_REQUEST['ID']; ?>, <?php echo $_REQUEST['warehouse_id']; ?>)">
                </form>
                <?php
							}
							if ($online_inv_confirmed == 1) {
							?>
                <font size="1">Confirmed by:
                    <?php echo $online_inv_confirmed_by; ?> on date:
                    <?php echo date("m/d/Y", strtotime($online_inv_confirmed_on));; ?>
                </font>
                <?php
							}
							?>

            </td>
        </tr>

    </table>


    <?php


			}
			?>
</div>
<?php

	}
}
?>