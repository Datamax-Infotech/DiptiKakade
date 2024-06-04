<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

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

	if ($dh[0] == 12) {
		$x = $x . " " . (intval($dh[0]) - 0) . ":" . $dh[1] . "PM CT";
	} elseif ($dh[0] == 0) {
		$x = $x . " 12:" . $dh[1] . "AM CT";
	} elseif ($dh[0] > 12) {
		$x = $x . " " . (intval($dh[0]) - 12) . ":" . $dh[1] . "PM CT";
	} else {
		$x = $x . " " . ($dh[0]) . ":" . $dh[1] . "AM CT";
	}

	return $x;
}


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
$loop_qb_invoice_no = "";
db();
$dt_sellto = db_query("Select total_revenue, warehouse_id, po_ponumber, loop_qb_invoice_no from loop_transaction_buyer where id = '" . $rec_id . "' ");
while ($row_sellto = array_shift($dt_sellto)) {
	$total_revenue = $row_sellto["total_revenue"];
	$warehouse_id = $row_sellto["warehouse_id"];
	//$po_ponumber = $row_sellto["po_ponumber"];
	$loop_qb_invoice_no = $row_sellto["loop_qb_invoice_no"];
}


$inv_number = $loop_qb_invoice_no;

$sql_1 = "Select * from loop_invoice_details Where trans_rec_id = '" . $rec_id . "'";
db();
$res_loop_invoice_details = db_query($sql_1);
$inv_row = array_shift($res_loop_invoice_details);

$subtotal = 0;
$taxable = 0;
$nooflines = 0;

$po_ponumber = $inv_row["PO"];

$c = "SELECT * FROM loop_invoice_items WHERE trans_rec_id = " . $rec_id . " ORDER BY id ASC";
db();
$boxsql = db_query($c);
$x = 0;
while ($bx = array_shift($boxsql)) {

	if ($bx["total"] == 0) {
		$subtotal = $subtotal + str_replace(",", "", strval($bx['price'] * $bx['quantity']));
	} else {
		$subtotal = $subtotal + str_replace(",", "", $bx["total"]);
	}
}

$fname_no_path = "";

if (isset($invoice_re_entry) == 1) {
	$fname = "invoice_files/" . "UsedCardboardBoxes_Invoice_" . $inv_number . "R.pdf";
	$fname_no_path = "UsedCardboardBoxes_Invoice_" . $inv_number . "R.pdf";
} else {
	$fname = "invoice_files/" . "UsedCardboardBoxes_Invoice_" . $inv_number . ".pdf";
	$fname_no_path = "UsedCardboardBoxes_Invoice_" . $inv_number . ".pdf";
}


$file_createdflg = "yes";

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
		if ($myrowsel_n["email"] != "") {
			$billto_eml .= $myrowsel_n["email"] . ",";
		}
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

	$due_date1 = strtotime("+ " . isset($term_days) . " days", strtotime($inv_date_dt));
	$due_date = date("m/d/Y", $due_date1);

	$inv_info = "<b>Invoice #:</b> " . $inv_number . "<br>";
	$inv_info .= "<b>Invoice Amount:</b> $" . number_format($subtotal + $taxable * isset($g_salestax), 2) . "<br>";
	$inv_info .= "<b>Invoice Date:</b> " . $inv_date . "<br>";
	$inv_info .= "<b>Credit Terms:</b> " . $credit_term . "<br>";
	$inv_info .= "<b>Due Date:</b> " . $due_date . "<br>";

	$order_no = $rec_id;
	if ($po_ponumber != "") {
		$order_no = $rec_id . " (PO " . $po_ponumber . ")";
	}
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

	$eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER #" . $order_no . "</span><br>
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
                    value="UsedCardboardBoxes Invoice <?php echo isset($inv_prefix) . $inv_number; ?>"></td>
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

?>