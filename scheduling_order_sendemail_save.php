<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


define('EMAIL_LINEFEED', 'LF');
define('EMAIL_TRANSPORT', 'sendmail');
define('EMAIL_USE_HTML', 'true');

function sendemail_phpmailer(string $hasattachment_flg, string $mailto_name, string $mailto, string $scc, string $sbcc, string $from_mail, string $from_name, string $subject, string $eml_message): void
{
    // Function code goes here


    if (!class_exists('PHPMailer')) {

        require('phpmailer/class.phpmailer.php');
    }

    if (!class_exists('SMTP')) {

        require('phpmailer/class.smtp.php');
    }



    //Create a new PHPMailer instance

    $mail = new PHPMailer;

    //Set who the message is to be sent from

    $mail->setFrom($from_mail, $from_name);

    //Set an alternative reply-to address

    $mail->addReplyTo($from_mail, $from_name);

    //Set who the message is to be sent to

    $pos = strpos($mailto, ";");
    if ($pos === false) {
        $mail->addAddress($mailto, $mailto);
    } else {
        $arr_selemlid = explode(";", $mailto);
        foreach ($arr_selemlid as $arr_selemlid_tmp) {
            if ($arr_selemlid_tmp != "") {
                $mail->addAddress($arr_selemlid_tmp, $arr_selemlid_tmp);
            }
        }
    }

    $pos = strpos($scc, ";");
    if ($pos === false) {
        $mail->AddCC($scc, $scc);
    } else {
        $arr_selemlid = explode(";", $scc);
        foreach ($arr_selemlid as $arr_selemlid_tmp) {
            if ($arr_selemlid_tmp != "") {
                $mail->AddCC($arr_selemlid_tmp, $arr_selemlid_tmp);
            }
        }
    }

    $pos = strpos($sbcc, ";");
    if ($pos === false) {
        $mail->AddBCC($sbcc, $sbcc);
    } else {
        $arr_selemlid = explode(";", $sbcc);
        foreach ($arr_selemlid as $arr_selemlid_tmp) {
            if ($arr_selemlid_tmp != "") {
                $mail->AddBCC($arr_selemlid_tmp, $arr_selemlid_tmp);
            }
        }
    }


    $mail->Subject = $subject;

    //Read an HTML message body from an external file, convert referenced images to embedded,

    //convert HTML into a basic plain-text alternative body

    $mail->msgHTML($eml_message);

    //Attach an image file

    //$mail->addAttachment('images/phpmailer_mini.png');



    //send the message, check for errors

    if (!$mail->send()) {

        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {

        echo "Message sent!";
    }
}

$eml_msg = $_REQUEST["hidden_reply_eml"];

//$message = nl2br($eml_msg);
$message = stripslashes($eml_msg);

//$message = preg_replace ( "/'/", "'", $message);

$resp = sendemail_phpmailer("no", $_REQUEST["txtemailto"], $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], $_REQUEST["txtemailbcc"], "freight@usedcardboardboxes.com", "UCB Freight Team", $_REQUEST["txtemailsubject"], $message);

$sql = "INSERT INTO loop_transaction_buyer_scheduleeml (trans_rec_id, ship_email, ship_to_name, email_sendby, email_sendon) VALUES ( '" . $_REQUEST["rec_id"] . "', '" . $_REQUEST["txtemailto"] . "', '" . $_REQUEST["txtemailto"] . "', '" . $_COOKIE["userinitials"] . "', '" . date("Y-m-d H:i:s") . "')";
db();
$result_crm = db_query($sql);

echo "<script type=\"text/javascript\">";
echo "window.location.href=\"viewCompany.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_ship\";";
echo "</script>";
echo "<noscript>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=viewCompany.php?ID=" . $_REQUEST["ID"] . '&warehouse_id=' . $_REQUEST["warehouse_id"] .  "&rec_id=" . $_REQUEST["rec_id"] . "&rec_type=" . $_REQUEST["rec_type"] . "&show=transactions&proc=View&searchcrit=&display=buyer_ship\" />";
echo "</noscript>";
exit;