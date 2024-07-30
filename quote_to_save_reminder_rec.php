<?php
// ini_set("display_errors", "1");
// error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);

require("inc/header_session.php");
require_once("../mainfunctions/database.php");

require_once("../mainfunctions/general-functions.php");

define('EMAIL_LINEFEED', 'LF');
define('EMAIL_TRANSPORT', 'sendmail');
define('EMAIL_USE_HTML', 'true');

// function FixString($strtofix)
// {
//     if (get_magic_quotes_gpc()) {
//         $addslash = "no";
//     } else {
//         $addslash = "yes";
//     }
//     if ($addslash == "yes") {
//         $strtofix = addslashes($strtofix);
//     }
//     $strtofix = ereg_replace("<", "&#60;", $strtofix);
//     $strtofix = ereg_replace("'", "&#39;", $strtofix);
//     $strtofix = ereg_replace("(\n)", "<BR>", $strtofix);
//     return $strtofix;
// }

$query = "SELECT * FROM tblvariable WHERE variablename='Quote_reminder'";
db();
$dt_view_res3 = db_query($query);
$send_eml_days = 0;
while ($objQuote = array_shift($dt_view_res3)) {
    $send_eml_days = $objQuote["variablevalue"];
}

$eml_msg = $_REQUEST["hidden_reply_eml"];
$message = nl2br($eml_msg);
$message = preg_replace("/'/", "\'", $message);

$current_date = date('Y-m-d');
$email_2days_status = date('Y-m-d', strtotime($current_date . ' + ' . $send_eml_days . ' days'));

$qry = "insert into quote_reminder (quote_id,quote_status,quote_date,quote_email_body,unq_quote_id,reminder_to,reminder_cc,reminder_subject,email_2days_status,companyid,attachment)";
$qry .= " values(" . $_REQUEST["reminder_quote_id"] . ",'" . $_REQUEST["reminder_quote_status"] . "','" . date('Y-m-d') . "','" . $message . "'," . $_REQUEST["reminder_unq_quote_id"] . ",'" . $_REQUEST["txtemailto"] . "','" . $_REQUEST["txtemailcc"] . "','" . preg_replace("/'/", "\'", $_REQUEST["txtemailsubject"]) . "','" . $email_2days_status . "'," . $_REQUEST["companyid"] . ",'" . $_REQUEST["txtemailattch"] . "')";
//echo $qry;
//$res = db_query($qry,db_b2b() );

//header("Location:viewCompany.php?ID=".$_REQUEST["companyid"]."&proc=View&searchcrit=&show=".$_REQUEST['show']."&rec_type=".$_REQUEST['rec_type']);


function sendemail_attachment_phpemail(
    array $files,
    string $path,
    string $mailto,
    string $scc,
    string $sbcc,
    string $from_mail,
    string $from_name,
    string $replyto,
    string $subject,
    string $message,
    string $from_eml_pwd
): void {
    require 'phpmailer/PHPMailerAutoload.php';
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->Port       = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth   = true;
    $mail->Username = "ucbemail@usedcardboardboxes.com";
    $mail->Password = "NewPwd@233Pop";
    $mail->SetFrom($from_mail, $from_name);

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

    for ($x = 0; $x < count($files); $x++) {
        $mail->addAttachment($path . $files[$x]);
    }

    $mail->IsHTML(true);

    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    if (!$mail->send()) {
        echo 'emailerror';
    } else {
        echo 'emailsend';
    }
}

//$eml_msg = $_REQUEST["hidden_reply_eml"]; 
$eml_msg = $_POST['editor1'];

//$message = nl2br($eml_msg);
$message = stripslashes($eml_msg);

//$message = preg_replace ( "/'/", "'", $message);

$filesarray = array();
$filesarray[0] = $_REQUEST["txtemailattch"];
//$filesarray[1] = "UsedCardboardBoxes_Quote_1255656.pdf";

$emp_name = "UsedCardboardBoxes.com";
$from_eml = "";
$emp_phoneext = "";
$emp_email = "";
$emp_email_pwd = "";
$user_qry = "SELECT name, title, email, phoneext from loop_employees where initials = '" . $_COOKIE['userinitials'] . "'";
db();
$user_res = db_query($user_qry);
while ($user_res_data = array_shift($user_res)) {
    $emp_name = $user_res_data["name"];
    $emp_email = $user_res_data["email"];

    /*$emp_email_pwd_2 = "";
		$tmppos_1 = strpos($emp_name, " ");
		if ($tmppos_1 != false)
		{
			$emp_email_pwd_2 = strtoupper(substr(trim($emp_name),$tmppos_1+1,1));
		}*/
}
if ($emp_email == "") {
    $from_eml = "freight@usedcardboardboxes.com";
} else {
    $from_eml = $emp_email;
}

//$resp = sendemail_phpmailer("yes", $_REQUEST["txtemailto"], $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], $_REQUEST["txtemailbcc"], "freight@usedcardboardboxes.com", "UsedCardboardBoxes.com", $_REQUEST["txtemailsubject"], $message);
//$resp =	sendemail_attachment_new($filesarray, "/home/usedcardboardbox/public_html/ucbloop/quotes/", $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], $_REQUEST["txtemailbcc"], $from_eml, $emp_name, $from_eml, $_REQUEST["txtemailsubject"], $message);
$resp =    sendemail_attachment_phpemail($filesarray, "/home/usedcardboardbox/public_html/ucbloop/quotes/", $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], $_REQUEST["txtemailbcc"], $from_eml, $emp_name, $from_eml, $_REQUEST["txtemailsubject"], $message, $emp_email_pwd);

echo "<script type=\"text/javascript\">";
echo "window.location.href=\"viewCompany.php?ID=" . $_REQUEST["companyid"] . "\";";
echo "</script>";
echo "<noscript>";
echo "<meta http-equiv=\"refresh\" content=\"0;url=viewCompany.php?ID=" . $_REQUEST["companyid"] . "\" />";
echo "</noscript>";
exit;