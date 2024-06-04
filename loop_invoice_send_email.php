<?php



require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

define('EMAIL_LINEFEED', 'LF');
define('EMAIL_TRANSPORT', 'sendmail');
define('EMAIL_USE_HTML', 'true');

function sendemail_withattachment_byphpemail_new(
	array $files,
	string $path,
	string $mailto,
	string $scc,
	string $sbcc,
	string $from_mail,
	string $from_name,
	string $replyto,
	string $subject,
	string $message
): string {
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

//$eml_msg = $_REQUEST["hidden_reply_eml"]; 
$eml_msg = $_REQUEST["Editor_emailmess"];

//$message = nl2br($eml_msg);
$message = stripslashes($eml_msg);

$file_name = "";
$user_qry = "SELECT invoice_no_qb_name from loop_transaction_buyer WHERE id = '" . $_REQUEST["rec_id"] . "'";
db();
$user_res = db_query($user_qry);
while ($user_res_data = array_shift($user_res)) {
	$file_name = $user_res_data["invoice_no_qb_name"];
}
$filesarray = array();
$filesarray[0] = $file_name;

$resp =	sendemail_withattachment_byphpemail_new($filesarray, "/home/usedcardboardbox/public_html/ucbloop/invoice_files/", $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], '', "AR@UsedCardboardBoxes.com", "UCB Accounts Receivable", "AR@UsedCardboardBoxes.com", $_REQUEST["txtemailsubject"], $message);

if ($resp == "emailerror") {
	$user_qry = "Insert into loop_transaction_notes (company_id, rec_id, rec_type, employee_id, message) select '" . $_REQUEST["warehouse_id"] . "', '" . $_REQUEST["rec_id"] . "', 'Supplier', '" . $_COOKIE['employeeid'] . "', 'System generated log - Invoice Email not sent, there was error on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials']  . "'";
	db();
	$user_res = db_query($user_qry);
} else {
	$user_qry = "Insert into loop_transaction_notes (company_id, rec_id, rec_type, employee_id, message) select '" . $_REQUEST["warehouse_id"] . "', '" . $_REQUEST["rec_id"] . "', 'Supplier', '" . $_COOKIE['employeeid'] . "', 'System generated log - Invoice Email sent to " . $_REQUEST["txtemailto"] . " on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "'";
	db();
	$user_res = db_query($user_qry);
}

echo "<script type=\"text/javascript\">";
echo "window.location.href=\"viewCompany.php?ID=" . $_REQUEST["ID"] . "&show=transactions&warehouse_id=" . $_REQUEST["warehouse_id"] . "&id=" . $_REQUEST["warehouse_id"] . "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=Supplier&proc=View&searchcrit=&display=buyer_payment\";";
echo "</script>";
echo "<noscript>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=viewCompany_func1_transaction.php?ID=" . $_REQUEST["ID"] . "&show=transactions&warehouse_id=" . $_REQUEST["warehouse_id"] . "&id=" . $_REQUEST["warehouse_id"] . "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=Supplier&proc=View&searchcrit=&display=buyer_payment\" />";
echo "</noscript>";
exit;