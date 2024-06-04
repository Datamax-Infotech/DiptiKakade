<div id="table_paid_bubble_inv_to_accounting_upd">
    Loading .....<img src='images/wait_animated.gif' />
</div>

<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

$today = date("m/d/Y");
$today_crm = date("Ymd");
$warehouse_id = $_POST["warehouse_id"];

$rec_id = $_POST["rec_id"];
$ID = $_POST["ID"];

//echo $warehouse_id."***".$rec_id;

//Check if loop_transaction_buyer_payments has any records, as in edit mode it will add duplicate record
$rec_found = "no";


$inv_qry = "SELECT trans_rec_id FROM loop_invoice_items WHERE trans_rec_id = '" . $rec_id . "'";
db();
$inv_res = db_query($inv_qry);
$inv_rec = tep_db_num_rows($inv_res);
if ($inv_rec > 0) {

	db();
	db_query("Update loop_transaction_buyer set invoice_re_entry = 1 where id = " . $rec_id);
}
//In all cases this flag is set so that "Create Invoice and Send Email"
db();
db_query("Update loop_transaction_buyer set invoice_re_entry_allowed = 1 where id = " . $rec_id);

$sql = "DELETE FROM loop_invoice_items WHERE trans_rec_id = '" . $rec_id . "'";
$result = db_query($sql);

$sql = "DELETE FROM loop_invoice_details WHERE trans_rec_id = '" . $rec_id . "'";
$result = db_query($sql);

//commented the code as data was getting lost
//$sql = "DELETE FROM loop_transaction_buyer_payments WHERE typeid = 1 and transaction_buyer_id = '" . $rec_id . "'";
//$result = db_query($sql,db() );


$invoice_amt = 0;
for ($i = 1; $i <= $_REQUEST["items"] + 2; $i++) {
	$q = "quantity" . $i;
	$c = "cost" . $i;
	$sku = "sku" . $i;
	$d = "description" . $i;
	$tot_cost = "totalcost" . $i;
	$division = "division" . $i;
	$category = "category" . $i;
	$btype = "btype" . $i;
	$boxid = "boxid" . $i;

	$sql_b2b = "SELECT owner FROM loop_boxes WHERE id = '" . $_REQUEST[$boxid] . "'";
	db();
	$result_b2b = db_query($sql_b2b);
	$box_item_founder = 0;
	while ($myrowsel_b2b = array_shift($result_b2b)) {
		$box_item_founder = $myrowsel_b2b["owner"];
	}

	$sql = "INSERT INTO loop_invoice_items  ( quantity, description, sku, price, trans_rec_id, total, division_id, category_id, loop_box_id, item_box_type, box_item_founder_emp_id) VALUES ( '" . $_REQUEST[$q] . "', '" . preg_replace("/'/", "\'", $_REQUEST[$d]) . "', '" . preg_replace("/'/", "\'", $_REQUEST[$sku]) . "', '" . $_REQUEST[$c] . "', '" . $rec_id . "', '" . $_REQUEST[$tot_cost] . "', '" . $_REQUEST[$division] . "', '" . $_REQUEST[$category] . "', '" . $_REQUEST[$boxid] . "', '" . $_REQUEST[$btype] . "', '" . $box_item_founder . "')";
	//echo $sql;
	if ($_REQUEST[$q] > 0) {
		$invoice_amt += $_REQUEST[$q] * $_REQUEST[$c];

		db();
		db_query($sql);
	}
}

$sql = "INSERT INTO loop_invoice_details  ( PO, terms, rep, shipdate, via, google, bookkeeper, customer, employee, trans_rec_id, total, invoice_entry_date) VALUES ( '" . $_REQUEST["PO"] . "', '" . $_REQUEST["terms"] . "', '" . $_REQUEST["rep"] . "', '" . $_REQUEST["shipdate"] . "', '" . $_REQUEST["via"] . "', '" . $_REQUEST["google"] . "', '" . preg_replace("/'/", "\'", $_REQUEST["bookkeepernotes"]) . "', '" . preg_replace("/'/", "\'", $_REQUEST["customermessage"]) . "', '" . $_COOKIE["userinitials"]  . "', '" . $rec_id . "', '" . str_replace(",", "", $_REQUEST["grandtotal"]) . "', '" . date("Y-m-d H:i:s") . "')";

db();
db_query($sql);

//added the code to update the invoice_paid flag
$dt_view_qry = "SELECT * from loop_buyer_payments WHERE trans_rec_id LIKE '" . $rec_id . "'";
$paid_amount = 0;
$dt_view_res = db_query($dt_view_qry);
while ($dt_view_row = array_shift($dt_view_res)) {
	$paid_amount += $dt_view_row["amount"];
}

db();
db_query("Update loop_transaction_buyer set invoice_paid = 0, total_revenue = '" . str_replace(",", "", $_REQUEST["grandtotal"]) . "' where id = " . $rec_id);
if ($paid_amount > 0 && $invoice_amt > 0) {
	if ($paid_amount >= $invoice_amt) {
		db_query("Update loop_transaction_buyer set invoice_paid = 1 where id = " . $rec_id);
	}
}

//To record the updation in the Invoice values for P&L report
$sql = "INSERT INTO rep_p_and_l_affect_amt_history ( transaction_buyer_id, company_id, employee_id, entry_date, inv_amount, inv_date, change_value, updation_notes, add_del_upd_flg) 
	VALUES ('" . $rec_id . "', '" . $ID . "', '" . $_COOKIE['employeeid']  . "', '" . date("Y-m-d H:i:s") . "', '" . str_replace(",", "", $_REQUEST["grandtotal"]) . "',
	'" . date("Y-m-d") . "', '', 'Added from \'Enter Invoice Details\' table.', 1)";
$resp = db_query($sql);

// function make_insert_query($table_name, $arr_data)
// {
// 	$fieldname = "";
// 	$fieldvalue = "";
// 	foreach ($arr_data as $fldname => $fldval) {
// 		$fieldname = ($fieldname == "") ? $fldname : $fieldname . ',' . $fldname;
// 		$fieldvalue = ($fieldvalue == "") ? "'" . formatdata($fldval) . "'" : $fieldvalue . ",'" . formatdata($fldval) . "'";
// 	}
// 	$query1 = "INSERT INTO " . $table_name . " ($fieldname) VALUES($fieldvalue)";
// 	return $query1;
// }


function formatdata(string $data): string
{
	return addslashes(trim($data));
}


$arr_data = array(
	'company_id' => $warehouse_id,
	'rec_type' => "Supplier",
	'rec_id' => $rec_id,
	'message' => formatdata("System generated log - Invoice Send to Accounting on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials']),
	'employee_id' => formatdata($_COOKIE['employeeid'])
);

$query1 = make_insert_query('loop_transaction_notes', $arr_data);
//db_query($query1 ,db() );	

//
send_transactionlog_email($warehouse_id, $rec_id, "Supplier", "buyer_view");
//

if ($_POST["updatecrm"] == "yes") {


	$message = "<strong>Note for Transaction # ";
	$message .=  $rec_id;
	$message .= "</strong>: ";
	$message .=  isset($dt_employee);
	$message .= " edited Drop Trailer on ";
	$message .= isset($dt_date);
	$message .= ". Old Vendor = ";
	$message .= $_POST["dt_vendor_old"];
	$message .= ".  Old Trailer No. = ";
	$message .= $_POST["dt_trailer_old"];
	$message .= ".  Sumbitted by ";
	$message .= $_POST["dt_employee_old"];
	$message .= " on ";
	$message .= $_POST["dt_date_old"];
	$message .= ".";

	$to = isset($com_email);
}

$Cc_used = "n";
$sql = "SELECT * from loop_transaction_buyer_cc where transaction_id <> '' and trans_rec_id = " . $rec_id;
db();
$result_n = db_query($sql);
while ($myrowsel_n = array_shift($result_n)) {
	$Cc_used = "y";
}

$UCBZeroWaste_flg = "";
$sql = "SELECT UCBZeroWaste_flg from loop_transaction_buyer where id = '" . $rec_id . "'";
db();
$result_n = db_query($sql);
while ($myrowsel_n = array_shift($result_n)) {
	if ($myrowsel_n["UCBZeroWaste_flg"] == 1) {
		$UCBZeroWaste_flg = "y";
	}
}

$inv_date_from_senttbl = "";
if ($UCBZeroWaste_flg == "y") {
	$dt_view_qry = "SELECT inv_date_of from loop_transaction_buyer_inv_sent WHERE trans_rec_id = '" . $rec_id . "' order by unqid asc limit 1";
	$dt_view_res = db_query($dt_view_qry);
	while ($dt_view_row = array_shift($dt_view_res)) {
		if ($dt_view_row["inv_date_of"] != "") {
			$inv_date_from_senttbl = date("m/d/y", strtotime($dt_view_row["inv_date_of"]));
		}
	}
}


$pono = $_REQUEST["PO"];

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

$eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER # " . $rec_id . "</a> (PO " . $pono . ") </span>
	<br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#000000;\" ><a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=" . $ID . "&show=transactions&warehouse_id=" . $warehouse_id . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . $rec_id . "&display=buyer_payment'>" . get_nickname_val('', $ID) . "</a></div>
	<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Request to create invoice in loops</div></td></tr>";

$eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">UCB Accounts Receivable Team, please create the requested invoice in loops and email it to the customer (if applicable).
	<br><br>	If this is a prepaid order by credit card, be sure the funds are captured before creating the invoice.
	</div>	</td></tr>";

$eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Invoice details</span>
    <br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Invoice Amount:</strong> $" . number_format((float)str_replace(",", "", $_REQUEST["grandtotal"]), 2) . "</div>";

if ($inv_date_from_senttbl != "") {
	$eml_confirmation .= "<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Invoice Date:</strong> " . $inv_date_from_senttbl . "</div>";
} else {
	$eml_confirmation .= "<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Invoice Date:</strong> " . date("m/d/Y") . "</div>";
}
$eml_confirmation .= "<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Credit Terms:</strong> " . $_REQUEST["terms"] . "</div>
	<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Bookkeeper Notes:</strong> " . $_REQUEST["bookkeepernotes"] . "</div>
	<br><br></td></tr>";

$eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";


$from_email = "accounting@usedcardboardboxes.com";
$to_email = "invoice@usedcardboardboxes.com,AR@UsedCardboardBoxes.com";
$sql = "SELECT * from email_config where unqid = 2";
db();
$result_n = db_query($sql);
while ($myrowsel_n = array_shift($result_n)) {
	$from_email = $myrowsel_n["from_email"];
	$to_email = $myrowsel_n["to_email"];
}

//$to_email = "prasad@extractinfo.com";

$bcc_email = "";
sendemail_php_function(null, '', $to_email, '', $bcc_email, $from_email, $from_email, $from_email, "Invoice Requested for Order #" . $rec_id, $eml_confirmation);

//$mailheadersadmin = "From: Loops System <" . $from_email . ">\n";

$today = date("Y-m-d");

$msg_trans = "System generated log - Invoice Details entered by " . $_COOKIE['userinitials'] . " on " . date("m/d/Y H:i:s");
db();
$res = db_query("Insert into loop_transaction_notes(company_id, rec_type, rec_id, message, employee_id) select '" . $warehouse_id . "', 'Supplier' , '" . $rec_id . "', '" . $msg_trans . "', '" . $_COOKIE['employeeid'] . "'");

//echo "Insert into loop_transaction_notes(company_id, rec_type, rec_id, message, employee_id) select '" . $warehouse_id . "', 'Supplier' , '" . $rec_id . "', '" . $msg_trans . "', '" . $_COOKIE['employeeid'] . "' <br>";	

//
send_transactionlog_email($warehouse_id, $rec_id, "Supplier", "buyer_view");
//
if ($rec_found == "no") {

	$sum = 0;
	for ($i = 1; $i <= $_REQUEST["items"]; $i++) {
		$q = "quantity" . $i;
		if ($_REQUEST[$q] <> "") {

			$box_idtmp = "boxid" . $i;
			$b2b_id = $_REQUEST[$box_idtmp];

			$sql_b2b = "SELECT b2b_id FROM loop_boxes WHERE id = '" . $b2b_id . "'";
			db();
			$result_b2b = db_query($sql_b2b);
			$b2b_boxid = 0;
			while ($myrowsel_b2b = array_shift($result_b2b)) {
				$b2b_boxid = $myrowsel_b2b["b2b_id"];
			}

			$sql_b2b = "SELECT costDollar, costCents, vendor, box_type FROM inventory WHERE ID = '" . $b2b_boxid . "'";
			db_b2b();
			$result_b2b = db_query($sql_b2b);

			$b2b_vendor = 0;
			$b2b_costDollar = 0;
			$b2b_costCents = 0;
			$b2b_cost = 0;
			$btype = "";
			while ($myrowsel_b2b = array_shift($result_b2b)) {
				$b2b_costDollar = round($myrowsel_b2b["costDollar"]);
				$b2b_costCents = $myrowsel_b2b["costCents"];
				$b2b_vendor = $myrowsel_b2b["vendor"];
				$box_type = $myrowsel_b2b["box_type"];

				/*if(preg_match("/(Gaylord|Loop|PresoldGaylord)/", $box_type)){
						$btype = "Gaylord";
					}elseif(preg_match("/(Medium|Large|Xlarge|Boxnonucb|Box|Presold|Shipping)/", $box_type)){
						$btype = "Shipping Box";
					}elseif(preg_match("/(PalletsUCB|PalletsnonUCB)/", $box_type)){
						$btype = "Pallets";
					}elseif(preg_match("/(SupersackUCB|SupersacknonUCB|Supersacks')/", $box_type)){
						$btype = "Supersacks";
					}elseif(preg_match("/(Recycling)/", $box_type)){
						$btype = "Recycling";
					}else{
						$btype = "Other";
					}*/

				if ($box_type == "Gaylord" || $box_type == "GaylordUCB" || $box_type == "Loop" || $box_type == "PresoldGaylord") {
					$btype = "Gaylord";
				}
				if (
					$box_type == "LoopShipping" || $box_type == "Box" || $box_type == "Boxnonucb" || $box_type == "Presold"
					|| $box_type == "Medium" || $box_type == "Large" || $box_type == "Xlarge"
				) {
					$btype = "Shipping Box";
				}
				if ($box_type == "SupersackUCB" || $box_type == "SupersacknonUCB") {
					$btype = "Supersacks";
				}
				if ($box_type == "PalletsUCB" || $box_type == "PalletsnonUCB") {
					$btype = "Pallets";
				}
				if ($box_type == "DrumBarrelUCB" || $box_type == "DrumBarrelnonUCB") {
					$btype = "Drums/Barrels/IBCs";
				}
				if ($box_type == "Recycling") {
					$btype = "Recycling";
				}
				if ($box_type == "Other") {
					$btype = "Other";
				}
			}

			$b2b_cost = $b2b_costDollar + $b2b_costCents;

			$q1 = "SELECT Name FROM vendors where id = $b2b_vendor";
			db_b2b();
			$query = db_query($q1);
			$vendor_name = "";
			while ($fetch = array_shift($query)) {
				$vendor_name = $fetch['Name'];
			}

			$qry = "select id from files_companies where name = '" . trim($vendor_name) . "'";
			db();
			$inv_rec = db_query($qry);
			$vendor_id = 0;
			while ($inv_row1 = array_shift($inv_rec)) {
				$vendor_id = $inv_row1['id'];
			}

			if ($vendor_id > 0) {
				$company_id = $vendor_id;
			} else {
				$cquery = "INSERT INTO files_companies (name) VALUES ( '" . $vendor_name . "')";
				db();
				$cresult = db_query($cquery);
				$company_id = tep_db_insert_id();
			}

			$amt_txt = $_REQUEST[$q] . " (Quantity of boxes) * " . $b2b_cost . " (B2B cost)";
			$amt = $_REQUEST[$q] * $b2b_cost;
			//$sum = $amt+$sum;

			$vendor_pay_rec_found = "no";
			$sql = "SELECT transaction_buyer_id FROM loop_transaction_buyer_payments WHERE transaction_buyer_id = " . $rec_id . " and box_loop_id = '" . $b2b_id . "'";
			db();
			$result = db_query($sql);
			while ($myrowsel = array_shift($result)) {
				$vendor_pay_rec_found = "yes";
			}

			$boxtypeID = 1;
			if (isset($btype)) {
				$boxtypeSqlQuery = "SELECT id,boxtype from loop_vendor_type where boxtype = '$btype'";
				db();
				$boxtypeQueryAllData = db_query($boxtypeSqlQuery);
				if (!empty($boxtypeQueryAllData)) {
					foreach ($boxtypeQueryAllData as $boxtypeData) {
						$boxtypeID = $boxtypeData['id'];
					}
				} else {
					$boxtypeID = 10; // 10 means "Other"
				}
			}

			if ($vendor_pay_rec_found == "no") {
				$sql = "INSERT INTO loop_transaction_buyer_payments (`transaction_buyer_id` ,`company_id` , `typeid`, `box_type`, `employee_id` ,`date` ,`estimated_cost` ,`status` ,`notes` ,`notes2`, box_loop_id ) VALUES ( ";
				$sql .= "'" . $rec_id . "', '" . $company_id . "', '" . $boxtypeID . "' , '" . $btype . "','" . $_COOKIE['employeeid'] . "', '" . $today . "', '" . $amt . "', 5, '" . $amt_txt . "', '', '" . $b2b_id . "')";
				db();
				$result = db_query($sql);
			} else {
				$sql = "Update loop_transaction_buyer_payments set `company_id` = '" . $company_id . "', `typeid` = '" . $boxtypeID . "', `box_type` = '" . $btype . "', 
					`employee_id` = '" . $_COOKIE['employeeid'] . "',`date` = '" . $today . "' ,`estimated_cost` = '" . $amt . "',`status` = 5 ,`notes` = '" . $amt_txt . "' ,`notes2` = ''  ";
				$sql .= " where transaction_buyer_id = '" . $rec_id . "' and box_loop_id = '" . $b2b_id . "'";
				db();
				$result = db_query($sql);
			}

			//To record the updation in the Invoice values for P&L report
			// $sql_ins = "INSERT INTO rep_p_and_l_affect_amt_history ( transaction_buyer_id, company_id, employee_id, entry_date, inv_amount, inv_date, change_value, updation_notes, add_del_upd_flg) 
			// 	VALUES ('" . $rec_id . "', '" . $ID . "', '" . $_COOKIE['employeeid']  . "', '" . date("Y-m-d H:i:s") . "', '" . str_replace(",", "", $amt) . "',
			// 	'" .  date("Y-m-d") . "', 'Invoice Request 1', 'Added from \'Invoice Request\' table.', 1)";
			$sql_ins = "INSERT INTO rep_p_and_l_affect_amt_history (transaction_buyer_id, company_id, employee_id, entry_date, inv_amount, inv_date, change_value, updation_notes, add_del_upd_flg) 
            VALUES ('" . $rec_id . "', '" . $ID . "', '" . $_COOKIE['employeeid']  . "', '" . date("Y-m-d H:i:s") . "', '" . str_replace(",", "", (string)$amt) . "',
            '" .  date("Y-m-d") . "', 'Invoice Request 1', 'Added from \'Invoice Request\' table.', 1)";

			db();
			$resp = db_query($sql_ins);
		}
	}
}


?>
<script>
parent.getlatest_notes(<?php echo $rec_id; ?>, <?php echo $warehouse_id; ?>);
</script>


<?php
$new_url = str_replace("invoice_edit=true", "", $_SERVER['HTTP_REFERER']);

redirect($new_url);
?>

<script>
document.getElementById("table_paid_bubble_inv_to_accounting_upd").style.display = "none";
</script>