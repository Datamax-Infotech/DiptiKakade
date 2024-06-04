<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");



define('EMAIL_LINEFEED', 'LF');
define('EMAIL_TRANSPORT', 'sendmail');
define('EMAIL_USE_HTML', 'true');

function sendemail_withattachment_byphpemail_new(array $files, string $path, string $mailto, string $scc, string $sbcc, string $from_mail, string $from_name, string $replyto, string $subject, string $message): string
{
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
	$mail->SetFrom($from_mail, $from_name);

	//
	if ($mailto != "") {
		$cc_flg = "";
		$tmppos_1 = strpos($mailto, ",");
		if ($tmppos_1 != false) {
			$cc_ids = explode(",", $mailto);

			foreach ($cc_ids as $cc_ids_tmp) {
				if ($cc_ids_tmp != "") {
					$mail->addAddress($cc_ids_tmp);
					$cc_flg = "y";
				}
			}
		}

		$tmppos_1 = strpos($mailto, ";");
		if ($tmppos_1 != false) {
			$cc_flg = "";
			$cc_ids1 = explode(";", $mailto);

			foreach ($cc_ids1 as $cc_ids_tmp2) {
				if ($cc_ids_tmp2 != "") {
					$mail->addAddress($cc_ids_tmp2);
					$cc_flg = "y";
				}
			}
		}

		if ($cc_flg == "") {
			$mail->addAddress($mailto, $mailto);
		}
	}

	if ($sbcc != "") {
		$cc_flg = "";

		$tmppos_1 = strpos($sbcc, ",");
		if ($tmppos_1 != false) {
			$cc_ids = explode(",", $sbcc);
			foreach ($cc_ids as $cc_ids_tmp) {
				if ($cc_ids_tmp != "") {
					$mail->AddBCC($cc_ids_tmp);
					$cc_flg = "y";
				}
			}
		}

		$tmppos_1 = strpos($sbcc, ";");
		if ($tmppos_1 != false) {
			$cc_flg = "";
			$cc_ids1 = explode(";", $sbcc);
			foreach ($cc_ids1 as $cc_ids_tmp2) {
				if ($cc_ids_tmp2 != "") {
					$mail->AddBCC($cc_ids_tmp2);
					$cc_flg = "y";
				}
			}
		}

		if ($cc_flg == "") {
			$mail->AddBCC($sbcc, $sbcc);
		}
	}

	if ($scc != "") {
		$cc_flg = "";
		$tmppos_1 = strpos($scc, ",");
		if ($tmppos_1 != false) {
			$cc_ids = explode(",", $scc);

			foreach ($cc_ids as $cc_ids_tmp) {
				if ($cc_ids_tmp != "") {
					$mail->AddCC($cc_ids_tmp);
					$cc_flg = "y";
				}
			}
		}

		$tmppos_1 = strpos($scc, ";");
		if ($tmppos_1 != false) {
			$cc_flg = "";
			$cc_ids1 = explode(";", $scc);
			foreach ($cc_ids1 as $cc_ids_tmp2) {
				if ($cc_ids_tmp2 != "") {
					$mail->AddCC($cc_ids_tmp2);

					$cc_flg = "y";
				}
			}
		}

		if ($cc_flg == "") {
			$mail->AddCC($scc, $scc);
		}
	}
	if ($files != "null") {
		for ($x = 0; $x < tep_db_num_rows($files); $x++) {
			$mail->addAttachment($path . $files[$x]);
		}
	}


	$mail->IsHTML(true);
	$mail->Encoding = 'base64';
	$mail->CharSet = "UTF-8";
	$mail->Subject = $subject;
	$mail->Body    = $message;
	$mail->AltBody = $message;
	if (!$mail->send()) {
		return 'emailerror';
	} else {
		return 'emailsend';
	}
}



function formatdata(string $data): string
{
	return addslashes(trim($data));
}


srand((int)(microtime(true) * 1000000));

$random_number = rand();


$sql = "UPDATE companyInfo SET last_date = '" . date("Y-m-d") . "' WHERE ID = " . $_REQUEST["b2bid"];
$result = db_query($sql);
//echo $sql;
$arr_data = array(
	'company_id' => formatdata($_REQUEST["company_id"]),
	'rec_type' => formatdata($_REQUEST["rec_type"]),
	'rec_id' => formatdata($_REQUEST["rec_id"]),
	'message' => formatdata($_REQUEST["message"]),
	'employee_id' => formatdata($_REQUEST["employee_id"])
);

$query1 = make_insert_query('loop_transaction_notes', $arr_data);
//echo $query1;
db();
db_query($query1);



// Whenever a transaction log entry is entered, the system automatically emails the account owner......

//echo $sql_acc;
//echo $myrow ['email']."**<br><br>";

$x = "Select company, assignedto, loopid from companyInfo Where ID = " . $_REQUEST["b2bid"];
db_b2b();
$dt_view_res = db_query($x);
$row = array_shift($dt_view_res);


//echo $x."<br>*/*<br>";
$warehouse_id = $row["loopid"];

$sql_acc = "SELECT email FROM employees";
$sql_acc .= " WHERE employees.status='Active' and employeeID=" . $row["assignedto"];
db_b2b();
$result1 = db_query($sql_acc);
$myrow = array_shift($result1);

$to =  $myrow['email'];

$sql = "SELECT message,date,loop_employees.name FROM loop_transaction_notes";
$sql .= " INNER JOIN loop_employees ON loop_transaction_notes.employee_id = loop_employees.id";
$sql .= " WHERE loop_transaction_notes.company_id = " .  $_REQUEST['company_id'] . " AND";
$sql .= " loop_transaction_notes.rec_id = " . $_REQUEST['rec_id'] . " order by loop_transaction_notes.id desc";
//echo $sql."<br><br>";
db();
$result = db_query($sql);

$po_employee = "";
$sql = "SELECT po_employee from loop_transaction_buyer where id = ?";
$result_comp = db_query($sql, array("i"), array($_REQUEST['rec_id']));
while ($row_comp = array_shift($result_comp)) {
	$po_employee = $row_comp["po_employee"];
}

if ($po_employee != "") {
	$sql = "SELECT email FROM loop_employees WHERE status='Active' and initials = ?";
	$result_comp = db_query($sql, array("s"), array($po_employee));
	while ($row_comp = array_shift($result_comp)) {
		$to = $row_comp["email"];
	}
}

//
$eml_confirmation = "<html><head></head><body bgcolor='#E7F5C2'><table border=1 align='center' cellpadding='0' bgcolor='#E7F5C2'><tr><td colspan='2'><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td width='23' valign='top'><p> </p></td><td width='650'><br>";
$eml_confirmation .= "<p align='center'><img src='https://www.usedcardboardboxes.com/images/email-top-part1.jpg'></p>";
$eml_confirmation .= "<p style='font-family: Calibri;'>Dear " . isset($sellto_name) . ",";
$eml_confirmation .= "<br><br>UsedCardboardBoxes.com (UCB) wants to thank you for your order! Your order number is <b>#" . isset($rec_id) . ".</b> <br></p>";
$eml_confirmation .= "<p style='font-family: Calibri;'>UCB's National Operations Team is currently allocating the specific inventory against this order. The next steps will include preparing your order for shipment and booking the freight for pickup and delivery. To ensure a smooth transaction, we'll be communicating with you every step of the way!<br></p>";
$eml_confirmation .= "<p style='font-family: Calibri;'><b>Please <u>confirm or provide</u> the required information below</b>, to ensure a prompt and accurate delivery of your order, as <b>any missing information (shipping or billing) could cause a delay in your delivery</b> and we want to do everything in our power to avoid that.<br></p>";
$eml_confirmation .= "<p style='font-family: Calibri;'><b>Shipping Info:</b><br></p>";
$eml_confirmation .= "<p style='font-family: Calibri;'>Shipping/Receiving Address: " . isset($myrowsel_main["shipAddress"]) . ", " . isset($myrowsel_main["shipAddress2"]) . ", " . isset($myrowsel_main["shipCity"]) . ", " . isset($myrowsel_main["shipState"]) . ", " . isset($myrowsel_main["shipZip"]) . "<br>";
$eml_confirmation .= "<p style='font-family: Calibri;'>Shipping/Receiving Contact Name: " . isset($myrowsel_main["shipContact"]) . "<br>Shipping/Receiving Contact Phone:  " . isset($myrowsel_main["shipPhone"]) . "<br>";
$eml_confirmation .= "Shipping/Receiving Contact Email Address: " . isset($myrowsel_main["shipemail"]) . "<br>";
$eml_confirmation .= "Shipping/Receiving Hours: " . isset($myrowsel_main["shipping_receiving_hours"]) . "</p>";
$eml_confirmation .= "<p style='font-family: Calibri;'><b>Billing Info:</b><br></p>";
$eml_confirmation .= "<p style='font-family: Calibri;'>Billing Contact Name: " . isset($billto_name) . "<br>";
$eml_confirmation .= "Billing Contact Phone:  " . isset($billto_ph) . "<br>";
$eml_confirmation .= "Billing Contact Email Address: " . isset($billto_eml) . "</p>";
$eml_confirmation .= "<p style='font-family: Calibri;'><b>Logistics Notes:</b><br></p>";
$eml_confirmation .= "<p style='font-family: Calibri;'>IF YOU ARE RECEIVING A DELIVERY FROM UCB, you will need a loading dock and forklift to unload the trailer.  </p>";
$eml_confirmation .= "<p style='font-family: Calibri;'>IF YOU ARE PICKING UP FROM UCB, you will need a dock-height truck or trailer.</p>";
$eml_confirmation .= "<p style='font-family: Calibri;'>If you do not have the items listed above, and you have not done so already, please advise right away so alternative arrangements can be made (additional fees may apply).<br></p>";
$eml_confirmation .= "<p style='font-family: Calibri;'>In the meantime and as always, please feel free to contact UCB's Operations Team, or your National Account Manager " . isset($acc_owner) . " anytime, if you have any questions or concerns.<br></p>";
$eml_confirmation .= "<p style='font-family: Calibri;'>Thank you again for Order <b>#" . isset($rec_id) . "</b> and the opportunity to work with you! <br></p>";

$eml_confirmation .= "<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C;'><img src='https://www.ucbzerowaste.com/images/logo2.png'></td>";
$eml_confirmation .= "<td><p style='font-family: Calibri; font-size:12;color:#538135'><u>National Operations Team</u><br>";
$eml_confirmation .= "Used Cardboard Boxes, Inc. (UCB)</p>";
$eml_confirmation .= "<span style='font-family: Calibri; font-size:12; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
$eml_confirmation .= "1-888-BOXES-88<br><br>";
//$eml_confirmation .= "<img src='https://www.ucbzerowaste.com/images/ucblogoside.jpg'><br>";
$eml_confirmation .= "How can we improve?  Please tell our CEO@UsedCardboardBoxes.com</span>";
$eml_confirmation .= "</td></tr></table>";

$eml_confirmation .= "</td></tr><tr><td colspan='2'><p align='center'><img width='650' height='87' src='https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";

//echo $eml_confirmation;
//
$tdno = 0;
$str_email = "";
$str_email = "<html><head></head><body bgcolor='#E7F5C2'><table border=0 align='center' cellpadding='0' width='700px' bgcolor='#E7F5C2'><tr><td ><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td><br>";
$str_email .= "<table width='700px' cellSpacing='1' cellPadding='3'><tr><th colspan=4>TRANSACTION LOG UPDATES</th></tr>";

$str_email .= "<tr><td bgColor='#98bcdf' colspan=3><font face='Arial, Helvetica, sans-serif' size='1'><strong>Company Name: <a href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=" . $_REQUEST['b2bid'] . "&warehouse_id=" . $warehouse_id . "&show=transactions&rec_type=" . $_REQUEST["rec_type"] . "&proc=View&searchcrit=&id= " . $warehouse_id . "&rec_id=" . $_REQUEST['rec_id'] . "&display=buyer_view'><font face='Arial, Helvetica, sans-serif' size='1'>" . $row['company'] . "</strong></font></a></td></tr>";

$str_email .= "<tr><td bgColor='#ABC5DF'><font face='Arial, Helvetica, sans-serif' size='1'><strong>Date/Time<strong></font></td><td bgColor='#ABC5DF'><font face='Arial, Helvetica, sans-serif' size='1'><strong>Employee</strong></font></td><td bgColor='#ABC5DF'><font face='Arial, Helvetica, sans-serif' size='1'><strong>Notes</strong></font></td></tr>";
while ($myrowsel = array_shift($result)) {

	$the_log_date = $myrowsel["date"];
	$yearz = substr("$the_log_date", 0, 4);
	$monthz = substr("$the_log_date", 4, 2);
	$dayz = substr("$the_log_date", 6, 2);
	//
	$tdno = $tdno + 1;
	if ($tdno == 1) {
		$tdbgcolor = "#d1cfce";
	} else {
		$tdbgcolor = "#e4e4e4";
	}
	//
	//$str_email = "<b>Transaction Log Update</b>:<br>";
	$str_email .= "<tr><td bgColor='" . $tdbgcolor . "'><font face='Arial, Helvetica, sans-serif' size='1'>" . $the_log_date . "</font></td><td bgColor='" . $tdbgcolor . "'><font face='Arial, Helvetica, sans-serif' size='1'>" . $myrowsel['name'] . "</font></td>";

	//$str_email.="<td bgColor='".$tdbgcolor."'><a href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=".$_REQUEST['b2bid']."&warehouse_id=" . $warehouse_id . "&show=transactions&rec_type=". $_REQUEST["rec_type"] . "&proc=View&searchcrit=&id= " . $warehouse_id . "&rec_id=". $_REQUEST['rec_id'] ."&display=buyer_view'><font face='Arial, Helvetica, sans-serif' size='1'>" . $row['company'] . "</font></a></td>";

	$str_email .= "<td bgColor='" . $tdbgcolor . "'><font face='Arial, Helvetica, sans-serif' size='1'>" . $myrowsel['message'] . "</font></td><tr><tr><td height='7px' colspan=4></td></tr>";


	/*$str_email.= "Company name: ". "<a href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=".$_REQUEST['b2bid']."&warehouse_id=" . $warehouse_id . "&show=transactions&rec_type=". $_REQUEST["rec_type"] . "&proc=View&searchcrit=&id= " . $warehouse_id . "&rec_id=". $_REQUEST['rec_id'] ."&display=buyer_view'>" . $row['company'] . "</a><br>";
	$str_email.= "Log Entered By: ".$myrowsel['name']."<br><br/>";
	$str_email.= "Transaction log note: ".$myrowsel['message']."<br>";
	$str_email.= "Transaction log date/time: ".$the_log_date ."<br><br>";*/
}
$str_email .= "</table></td></tr><tr><td><p align='center'><img width='650' height='87' src='https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";
/*echo $str_email;
exit();*/
//
$mailheadersadmin = "From: UsedCardboardBoxes.com <operations@usedcardboardboxes.com>\n";
$mailheadersadmin .= "MIME-Version: 1.0\r\n";
$mailheadersadmin .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

$filearr = array();

if ($to != "") {
	//$emlstatus = sendemail_attachment_new1(null, "", $to, "", "", "operations@usedcardboardboxes.com","Admin UCB", "", "Transaction Log Update for " . $row['company'] . " - " . $_REQUEST['rec_id'] , $str_email );
	//$emlstatus = sendemail_withattachment_byphpemail_new(null, "", $to, "", "", "ucbemail@usedcardboardboxes.com", "Admin UCB", "operations@usedcardboardboxes.com", "Transaction Log Update for " . $row['company'] . " - " . $_REQUEST['rec_id'], $str_email);
	$emlstatus = sendemail_withattachment_byphpemail_new([], "", $to, "", "", "ucbemail@usedcardboardboxes.com", "Admin UCB", "operations@usedcardboardboxes.com", "Transaction Log Update for " . $row['company'] . " - " . $_REQUEST['rec_id'], $str_email);
}
//	echo " emlstatus - " . $emlstatus;

redirect($_SERVER['HTTP_REFERER']);

//redirect("viewCompany.php?ID=". $_REQUEST["company_id"] . "&show=transactions&warehouse_id=". $_REQUEST["company_id"] . "&rec_type=". $_REQUEST["rec_type"] . "&proc=View&searchcrit=&id=". $_REQUEST["company_id"] . "&rec_id=". $_REQUEST["rec_id"] . "&display=buyer_view");