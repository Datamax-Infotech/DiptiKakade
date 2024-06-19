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
    // Code to send mail
    require 'phpmailer/PHPMailerAutoload.php';

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = "ucbemail@usedcardboardboxes.com";
    $mail->Password = "#UCBgrn4652";
    $mail->setFrom($from_mail, $from_name);
    $mail->addReplyTo($replyto, $from_name);

    if ($mailto !== "") {
        $cc_flg = false;
        if (strpos($mailto, ",") !== false) {
            $cc_ids = explode(",", $mailto);
            foreach ($cc_ids as $cc_ids_tmp) {
                if ($cc_ids_tmp !== "") {
                    $mail->addAddress($cc_ids_tmp);
                    $cc_flg = true;
                }
            }
        } elseif (strpos($mailto, ";") !== false) {
            $cc_ids = explode(";", $mailto);
            foreach ($cc_ids as $cc_ids_tmp) {
                if ($cc_ids_tmp !== "") {
                    $mail->addAddress($cc_ids_tmp);
                    $cc_flg = true;
                }
            }
        } else {
            $mail->addAddress($mailto);
        }
    }

    if ($sbcc !== "") {
        if (strpos($sbcc, ",") !== false) {
            $cc_ids = explode(",", $sbcc);
            foreach ($cc_ids as $cc_ids_tmp) {
                if ($cc_ids_tmp !== "") {
                    $mail->addBCC($cc_ids_tmp);
                }
            }
        } elseif (strpos($sbcc, ";") !== false) {
            $cc_ids = explode(";", $sbcc);
            foreach ($cc_ids as $cc_ids_tmp) {
                if ($cc_ids_tmp !== "") {
                    $mail->addBCC($cc_ids_tmp);
                }
            }
        } else {
            $mail->addBCC($sbcc);
        }
    }

    if ($scc !== "") {
        if (strpos($scc, ",") !== false) {
            $cc_ids = explode(",", $scc);
            foreach ($cc_ids as $cc_ids_tmp) {
                if ($cc_ids_tmp !== "") {
                    $mail->addCC($cc_ids_tmp);
                }
            }
        } elseif (strpos($scc, ";") !== false) {
            $cc_ids = explode(";", $scc);
            foreach ($cc_ids as $cc_ids_tmp) {
                if ($cc_ids_tmp !== "") {
                    $mail->addCC($cc_ids_tmp);
                }
            }
        } else {
            $mail->addCC($scc);
        }
    }

    if ($files !== null) {
        foreach ($files as $file) {
            $mail->addAttachment($path . $file);
        }
    }

    $mail->isHTML(true);
    $mail->Encoding = 'base64';
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message);

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

//$resp = sendemail_phpmailer("no", $_REQUEST["txtemailto"], $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], $_REQUEST["txtemailbcc"], "Freight@UsedCardboardBoxes.com", "UCB Freight Team", $_REQUEST["txtemailsubject"], $message);
$resp = sendemail_withattachment_byphpemail_new([], "", $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], $_REQUEST["txtemailbcc"], "ucbemail@usedcardboardboxes.com", "UCB Freight Team", "freight@UsedCardboardBoxes.com", $_REQUEST["txtemailsubject"], $message);

db();
$getdata = db_query("Insert into loop_transaction_buyer_ship_eml_data(trans_rec_id, freight_assigned_email_flg, freight_assigned_email_sendby, freight_assigned_email_sendon) select " . $_REQUEST["rec_id"] . ", 1, '" . $_COOKIE['userinitials'] . "', '" . date("Y-m-d H:i:s") . "'");

echo "<script type=\"text/javascript\">";
echo "window.location.href=\"viewCompany.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_ship\";";
echo "</script>";
echo "<noscript>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=viewCompany.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_ship\" />";
echo "</noscript>";
exit;