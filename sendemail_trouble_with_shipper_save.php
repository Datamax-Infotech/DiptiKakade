<?php


require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

define('EMAIL_LINEFEED', 'LF');
define('EMAIL_TRANSPORT', 'sendmail');
define('EMAIL_USE_HTML', 'true');

function sendemail_withattachment_byphpemail_new(
    array $files, // Array for file attachments
    string $path, // Path to the files
    string $mailto, // Recipient email
    string $scc, // CC recipients
    string $sbcc, // BCC recipients
    string $from_mail, // Sender email
    string $from_name, // Sender name
    string $replyto, // Reply-to email
    string $subject, // Email subject
    string $message // Email message body
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
    $mail->addReplyTo($replyto, $from_name);

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

$eml_msg = $_REQUEST["hidden_reply_eml"];

//$message = nl2br($eml_msg);
$message = stripslashes($eml_msg);

//$message = preg_replace ( "/'/", "'", $message);

//$resp = sendemail_phpmailer("no", $_REQUEST["txtemailto"], $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], $_REQUEST["txtemailbcc"], "operations@usedcardboardboxes.com", "UCB Operations Team", $_REQUEST["txtemailsubject"], $message);
$resp = sendemail_withattachment_byphpemail_new([], "", $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], $_REQUEST["txtemailbcc"], "ucbemail@usedcardboardboxes.com", "UCB Operations Team", "Operations@usedcardboardboxes.com", $_REQUEST["txtemailsubject"], $message);

$sql = "INSERT INTO loop_transaction_buyer_poeml (trans_rec_id, trouble_with_shipper_email_sendby, trouble_with_shipper_email_sendon) VALUES ( '" . $_REQUEST["rec_id"] . "', '" . $_COOKIE["userinitials"] . "', '" . date("Y-m-d H:i:s") . "')";

db();
$result_crm = db_query($sql);

$msg_trans = "System generated log - Action taken on 'Mark as Fulfillment Issue' on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "<br>";
$msg_trans .= "Action : 'Mark as Fulfillment Issue'.";
$msg_trans .= "<br>Notes: System Marked as Fulfillment Issue due to delay with shipper";

db();
$query1 = db_query("Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) select " . $_REQUEST["warehouse_id"] . " , 'Supplier', " . $_REQUEST["rec_id"] . ", '" . str_replace("'", "\'", $msg_trans) . "' , " . $_COOKIE['employeeid']);

db();
$query1 = db_query("Update loop_transaction_buyer set fulfillment_issue = 1 where id = " . $_REQUEST["rec_id"]);

$strQuery_upd = "Insert into loop_transaction_buyer_fulfillment_issue (trans_id, fulfillment_issue_start_date_time, fulfillment_issue_start_done_by, fulfillment_issue_start_notes) ";
$strQuery_upd .= " select '" . $_REQUEST["rec_id"] . "', '" . date("Y-m-d H:i:s") . "', '" . $_COOKIE['userinitials'] . "', 'System Marked as Fulfillment Issue due to delay with shipper'";

db();
$data_upd = db_query($strQuery_upd);

/*From 2nd bubble remove all values from the PICKUP DOCK APPOINTMENT start */
if (isset($_REQUEST['hdnPickupDockAppointment']) && $_REQUEST['hdnPickupDockAppointment'] == 'yes') {

    db();
    $result = db_query("DELETE FROM loop_transaction_freight WHERE trans_rec_id=" . $_REQUEST["rec_id"]);
}
/*From 2nd bubble remove all values from the PICKUP DOCK APPOINTMENT ends */
if (isset($_REQUEST['hdnPickupDockAppointment']) && $_REQUEST['hdnPickupDockAppointment'] == 'yes') {
    //header("Location: " . $_REQUEST['hdnActualLink']);
    echo "<script type=\"text/javascript\">";
    echo "window.location.href=\"loop_shipbubble_freight_booking.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_view\";";
    echo "</script>";
    echo "<noscript>";
    echo "<meta http-equiv=\"refresh\" content=\"0;url=loop_shipbubble_freight_booking.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_view\" />";
    echo "</noscript>";
    exit;
} else {
    echo "<script type=\"text/javascript\">";
    echo "window.location.href=\"viewCompany.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_view\";";
    echo "</script>";
    echo "<noscript>";
    echo "<meta http-equiv=\"refresh\" content=\"0;url=viewCompany.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_view\" />";
    echo "</noscript>";
    exit;
}