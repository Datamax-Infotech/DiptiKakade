<?php
// ini_set("display_errors", "1");
// error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);

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
        for ($x = 0; $x < count($files); $x++) {
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

if ($_REQUEST["hidden_sendemail"] == "inemailmode") {
    //
    $current_date = date('Y-m-d');

    //$eml_msg = $_REQUEST["hidden_reply_eml"]; 
    $eml_msg = $_REQUEST['emlbody'];

    //$message = nl2br($eml_msg);
    $message = stripslashes($eml_msg);

    //$message = preg_replace ( "/'/", "'", $message);

    $filesarray = array();

    $tmppos_1 = strpos($_REQUEST["txtemailattch"], "|");
    if ($tmppos_1 != false) {
        $splidata = explode("|", $_REQUEST["txtemailattch"]);

        for ($inv_count = 0; $inv_count <= count($splidata); $inv_count++) {
            if ($splidata[$inv_count] != "") {
                $filesarray[] = $splidata[$inv_count];
            }
        }
    } else {
        if ($_REQUEST["txtemailattch"] != "") {
            $filesarray[0] = $_REQUEST["txtemailattch"];
        }
    }

    $emp_name = "UsedCardboardBoxes.com";
    $from_eml = "";
    $emp_phoneext = "";
    $emp_email = "";
    $user_qry = "SELECT name, title, email, phoneext from loop_employees where initials = '" . $_COOKIE['userinitials'] . "'";
    db();
    $user_res = db_query($user_qry);
    while ($user_res_data = array_shift($user_res)) {
        $emp_name = $user_res_data["name"];
        $emp_email = $user_res_data["email"];
    }

    $from_eml = "AR@UsedCardboardBoxes.com";
    $resp =    sendemail_withattachment_byphpemail_new($filesarray, "/home/usedcardboardbox/public_html/ucbloop/water_payment_proof/", $_REQUEST["txtemailto"], $_REQUEST["txtemailcc"], $_REQUEST["txtemailbcc"], $from_eml, "UCBZeroWaste", $from_eml, $_REQUEST["txtemailsubject"], $message);

    //
    //Start translog save

    // function make_insert_query($table_name, $arr_data)
    // {
    //     $fieldname = "";
    //     $fieldvalue = "";
    //     foreach ($arr_data as $fldname => $fldval) {
    //         $fieldname = ($fieldname == "") ? $fldname : $fieldname . ',' . $fldname;
    //         $fieldvalue = ($fieldvalue == "") ? "'" . formatdata($fldval) . "'" : $fieldvalue . ",'" . formatdata($fldval) . "'";
    //     }
    //     $query1 = "INSERT INTO " . $table_name . " ($fieldname) VALUES($fieldvalue)";
    //     return $query1;
    // }


    // function formatdata($data)
    // {
    //     return addslashes(trim($data));
    // }

    //
    if ($_REQUEST["summaryrep"] == 1) {
        $user_qry = "SELECT id from loop_employees where initials = '" . $_COOKIE['userinitials'] . "'";
        db();
        $user_res = db_query($user_qry);
        while ($user_res_data = array_shift($user_res)) {
            $userid = $user_res_data["id"];
        }

        $ar_sql1 = "Select *,loop_warehouse.id as warehouse_id,water_vendors_receivable_contact.id as receivable_id, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid, water_transaction.id as transid 
				from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
				left join water_vendors_receivable_contact on water_transaction.vendor_id=water_vendors_receivable_contact.water_vendor_id
				where water_transaction.id=" . $_REQUEST["rec_id"];

        //$ar_sql1 = "SELECT * FROM loop_ar_reptemp_new where userid = " . $userid . " and loop_id=".$_REQUEST["warehouse_id"];
        db();
        $ar_result = db_query($ar_sql1);
        while ($ar_row = array_shift($ar_result)) {
            $company_name = $ar_row['company_name'];
            //
            $warehouse_name = $ar_row["company_name"];
            $b2bid = $ar_row["company_id"];

            $b2bid = 0;
            $sql_transnotes = "SELECT b2bid FROM loop_warehouse where id = " . $_REQUEST["warehouse_id"];
            db();
            $result_transnotes = db_query($sql_transnotes);
            while ($myrowsel_transnotes = array_shift($result_transnotes)) {
                $b2bid = $myrowsel_transnotes["b2bid"];
            }

            if ($_REQUEST["emlbody"] != "") {
                $sql = "UPDATE companyInfo SET last_date = '" . date("Y-m-d") . "' WHERE ID = " . $b2bid;
                db_b2b();
                $result = db_query($sql);
            }
        }
    }

    //End translog save
    exit;
}
?>


<html>

<head>
    <title></title>

    <style type="text/css">
    .templ_option {
        display: none;
    }
    </style>

</head>

<body>
    <br>
    <form name="templ_email" id="templ_email" action="open_template_vendors_AR.php" method="post"
        ENCTYPE="multipart/form-data">
        <table width="800px" align="center">
            <tr>
                <td style="border: 1px solid #575757;">
                    <table width="100%">
                        <tr>
                            <td bgcolor="#575757" colspan="2" style="padding: 5px;">
                                <font color="#FFFFFF">Select template:&nbsp;&nbsp;&nbsp;</font>
                                <select name="ag_template" id="ag_template" onChange="select_email_templ()">
                                    <option value="-1">Select Template</option>
                                    <option value="1">Due Soon</option>
                                    <option value="2">Past Due</option>
                                    <option value="3">Severely Past Due</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" height="8px"></td>


                        <tr id="tmpl_info">
                            <td colspan="2">
                                <?php
                                $userid = 0;
                                $user_qry = "SELECT id from loop_employees where initials = '" . $_COOKIE['userinitials'] . "'";
                                db();
                                $user_res = db_query($user_qry);
                                while ($user_res_data = array_shift($user_res)) {
                                    $userid = $user_res_data["id"];
                                }

                                $inv_terms = "";
                                $user_qry = "SELECT vendor_terms from water_vendors where id = '" . $_REQUEST['vendor_id'] . "'";
                                db();
                                $user_res = db_query($user_qry);
                                while ($user_res_data = array_shift($user_res)) {
                                    $inv_terms = $user_res_data["vendor_terms"];
                                }


                                $sql = "SELECT * FROM water_ar_email_template";
                                db();
                                $result = db_query($sql);
                                while ($row = array_shift($result)) {
                                    $ag_subject = $row['template_subject'];
                                    $template_body = $row['template_body'];
                                ?>

                                <div id="show_templ<?php echo $row['id'] ?>" class="templ_option">
                                    <?php
                                        $ar_sql = "Select *,loop_warehouse.id as warehouse_id,water_vendors_receivable_contact.id as receivable_id, sum(amount) as amt, loop_warehouse.company_name, loop_warehouse.b2bid, water_transaction.id as transid 
				from water_transaction inner join loop_warehouse on loop_warehouse.id = water_transaction.company_id 
				left join water_vendors_receivable_contact on water_transaction.vendor_id=water_vendors_receivable_contact.water_vendor_id
				where water_transaction.id=" . $_REQUEST["transid"];

                                        //echo $ar_sql . "<br>";
                                        db();
                                        $ar_result = db_query($ar_sql);
                                        $ar_row = array_shift($ar_result);
                                        $company_name = $ar_row["company_name"];
                                        $b2bid = $ar_row["company_id"];
                                        $contact_name = $ar_row['company_contact'];
                                        $get_company_nickname = getnickname($rows["company_name"], $rows["b2bid"]);
                                        $get_company_full_name = $ar_row["Name"] . " - " . $ar_row["description"] . " - " . $ar_row['city'] . ", " . $ar_row['state'] . " " . $ar_row['zipcode'];
                                        $inv_number = $ar_row['invoice_number'];
                                        //$inv_terms=$ar_row['vendor_terms'];
                                        $inv_date = $ar_row['invoice_due_date'];
                                        $attachment = $ar_row["payment_proof_file"];
                                        $due_date_tmp = "";
                                        if ($inv_terms == 'Net 10' || $inv_terms == 'Net10') {
                                            $due_date_tmp = date('m/d/Y', strtotime($inv_date . "+ 10 days"));
                                        }
                                        if ($inv_terms == 'Net 15' || $inv_terms == 'Net15') {
                                            $due_date_tmp = date('m/d/Y', strtotime($inv_date . "+ 15 days"));
                                        }
                                        if ($inv_terms == 'Net 30' || $inv_terms == 'Net30') {
                                            $due_date_tmp = date('m/d/Y', strtotime($inv_date . "+ 30 days"));
                                        }
                                        if ($inv_terms == 'Net 45' || $inv_terms == 'Net45') {
                                            $due_date_tmp = date('m/d/Y', strtotime($inv_date . "+ 45 days"));
                                        }
                                        if ($inv_terms == 'Net 60' || $inv_terms == 'Net60') {
                                            $due_date_tmp = date('m/d/Y', strtotime($inv_date . "+ 60 days"));
                                        }

                                        $due_date = "";
                                        if ($due_date_tmp != "") {
                                            $due_date = $due_date . $due_date_tmp . ", ";
                                        }

                                        if ($due_date != "") {
                                            $due_date = substr($due_date, 0, strlen($due_date) - 1);
                                        }

                                        /*if ($inv_number != ""){
					$inv_number = substr($inv_number, 0 , strlen($inv_number)-1);
				}
				*/
                                        $rec_id = 0;
                                        if (isset($_REQUEST["transid"])) {
                                            $rec_id = $_REQUEST["transid"];
                                        }


                                        $acc_owner = $ar_row["Name"];
                                        //$acc_owner_eml = $ar_row["Email"]; 
                                        $acc_owner_eml = $ar_row["company_email"];
                                        $companyid = $ar_row["company_id"];
                                        $sellto_name = $ar_row["company_contact"];
                                        $sellto_eml = $ar_row["company_email"];

                                        $billto_name = $ar_row["receivable_contact_name"];
                                        $billto_ph = $ar_row["receivable_main_phone"];
                                        //echo "SELECT receivable_email from water_vendors_receivable_contact where water_vendor_id='".$_REQUEST['vendor_id']."'";
                                        db();
                                        $receivable_email_qry = db_query("SELECT payable_email from water_vendors_payable_contact where water_vendor_id='" . $_REQUEST['vendor_id'] . "'");

                                        $billto_eml = "";
                                        if (tep_db_num_rows($receivable_email_qry) > 0) {
                                            $billto_eml_array = [];
                                            while ($res = array_shift($receivable_email_qry)) {
                                                $billto_eml_array[] = $res['payable_email'];
                                            }
                                            $billto_eml = implode(";", $billto_eml_array);
                                        }
                                        //$billto_eml = $ar_row["payable_email"] . ";";

                                        $ar_insert_data = array(
                                            '[Company Name]' => $get_company_nickname,
                                            '[Primary Bill To Contact]' => $contact_name, '[Inv Number]' => $inv_number,
                                            '[Invoice Number]' => '<strong>' . $inv_number . '</strong>', '[Due Date]' => '<strong>' . $due_date . '</strong>'
                                        );

                                        ?>
                                    <table width="100%">
                                        <tr>
                                            <td width="10%" align="right">To:</td>
                                            <td width="90%"> <input size=60 type="text"
                                                    id="txtemailto<?php echo $row['id'] ?>" name="txtemailto"
                                                    value="<?php echo $billto_eml; ?>">&nbsp;<font size=1>(Use ; to
                                                    separate multiple email address)</font>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="10%" align="right">Cc:</td>
                                            <td width="90%"> <input size=60 type="text"
                                                    id="txtemailcc<?php echo $row['id'] ?>" name="txtemailcc"
                                                    value="AR@UCBZeroWaste.com; BobbieRamcharran@UCBZeroWaste.com">&nbsp;
                                                <font size=1>(Use ; to separate multiple email address)</font>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td width="10%" align="right">Bcc:</td>
                                            <td width="90%"> <input size=60 type="text"
                                                    id="txtemailbcc<?php echo $row['id'] ?>" name="txtemailbcc"
                                                    value="">&nbsp;<font size=1>(Use ; to separate multiple email
                                                    address)</font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%" align="right">Subject:</td>
                                            <td width="90%"><input size=90 type="text"
                                                    id="txtemailsubject<?php echo $row['id'] ?>" name="txtemailsubject"
                                                    value="<?php echo str_replace_assoc($ar_insert_data, $ag_subject); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="10%" align="right">Attachment:</td>
                                            <td width="90%">
                                                <?php if ($_REQUEST["summaryrep"] == 1) {
                                                        $splidata = explode("|", $attachment);

                                                        for ($inv_count = 0; $inv_count <= count($splidata); $inv_count++) { ?>
                                                <a style="color:#0000FF;" target="_blank"
                                                    href="./water_payment_proof/<?php echo preg_replace("/'/", "\'", $splidata[$inv_count]); ?>"><?php echo $splidata[$inv_count]; ?></a><br>
                                                <?php }

                                                        ?>
                                                <input type="hidden" id="txtemailattch<?php echo $row['id'] ?>"
                                                    name="txtemailattch" value="<?php echo $attachment; ?>">

                                                <?php } else { ?>
                                                <input type="hidden" id="txtemailattch<?php echo $row['id'] ?>"
                                                    name="txtemailattch" value="<?php echo $attachment; ?>">
                                                <a style="color:#0000FF;" target="_blank"
                                                    href="./water_payment_proof/<?php echo preg_replace("/'/", "\'", $attachment); ?>"><?php echo $attachment; ?></a>
                                                <?php } ?>
                                            </td>
                                        </tr>

                                        <?php
                                            $emp_name = "";
                                            $emp_title = "";
                                            $emp_phoneext = "";
                                            $emp_email = "";
                                            $user_qry = "SELECT name, title, email, phoneext from loop_employees where initials = '" . $_COOKIE['userinitials'] . "'";
                                            db();
                                            $user_res = db_query($user_qry);
                                            while ($user_res_data = array_shift($user_res)) {
                                                $emp_name = $user_res_data["name"];
                                                $emp_title = $user_res_data["title"];
                                                $emp_email = $user_res_data["email"];
                                            }
                                            //
                                            /*$signature = "<br><br><span style='font-size:16px; font-family:Calibri, sans-serif; color:#76923C'>";
						$signature .= $emp_name . "<br>";
						$signature .= $emp_title . "</span>";
						$signature .= "<br>";
						$signature .= "<span style='font-size:16.0pt; font-family:Impact,sans-serif; color:#76923C'>UsedCardboardBoxes.com</span><br>";
						$signature .= "<b><span style='font-size:11.0pt; font-family:Calibri, sans-serif; color:#76923C'>North America's cheapest, easiest and most earth-friendly way to get boxes for<br />";
						$signature .= "PACKING, MOVING, SHIPPING AND STORAGE!</span></b><br>";
						$signature .= "<span style='font-size:16px; font-family:Calibri, sans-serif; color:#76923C'></span><br>";
						$signature .= "<span style='font-size:16px; font-family:Calibri, sans-serif; color:#76923C'>Headquarters: 4032 Wilshire Blvd., Suite #402, Los Angeles, CA 90010</span><br>";
						$signature .= "<span style='font-size:16px; font-family:Calibri, sans-serif; color:#76923C'>323-724-2500 " . $emp_phoneext. "; </span>&nbsp;";
						$signature .= "<span style='font-size:16px; font-family:Calibri, sans-serif; color:#76923C'>Fax: 323-315-4194; </span><br>";
						$signature .= "<span style='font-size:16px; font-family:Calibri, sans-serif; color:#76923C'><a href='mailto:" . $emp_email . "'><span style='color:blue'>" . $emp_email . " </span></a></span><br>";
						$signature .= "<span style='font-size:16px; font-family:Calibri, sans-serif; color:#76923C'><a href='www.UsedCardboardBoxes.com' title='blocked::www.UsedCardboardBoxes.com'><span style='font-size:16px;font-family:Calibri, sans-serif;color:blue'>www.UsedCardboardBoxes.com</span></a></span><br>";
						$signature .= "<span style='font-size:16px; font-family:Calibri, sans-serif; color:#76923C'>Thanks for keeping the trees in the forest and the boxes off our streets!</span><br><br>";
						$signature .= "<span style='font-size:16px; font-family:Calibri, sans-serif; color:#76923C'>How can I improve? Please tell our <a href='mailto:CEO@UsedCardboardBoxes.com'><span style='color:blue'>CEO@UsedCardboardBoxes.com</a></span><br>";
						$signature .= "<br><br>";*/

                                            $signature = "<table cellspacing='10'><tr><td style='border-right: 2px solid #66381C;'><img src='https://www.ucbzerowaste.com/images/logo2.png'></td>";
                                            $signature .= "<td><p style='font-family: Calibri; font-size:12;color:#538135'><b><u>$emp_name</u><br>$emp_title</b><br>";
                                            $signature .= "Used Cardboard Boxes, Inc. (UCB)</p>";
                                            $signature .= "<span style='font-family: Calibri; font-size:12; color:#66381C'>4032 Wilshire Blvd STE 402<br>Los Angeles, CA 90010<br>";
                                            $signature .= "323-724-2500 $emp_phoneext<br><a href='mailto:" . $emp_email . "'><span style='color:blue'>" . $emp_email . " </span></a><br><br>";
                                            $signature .= "How can we improve?  Please tell our CEO@UsedCardboardBoxes.com</span>";
                                            $signature .= "</td></tr></table>";

                                            ?>
                                        <tr>
                                            <td width="10%" align="right" valign="top">Body:</td>
                                            <td width="250px" id="bodytxt">

                                                <?php
                                                    $eml_confirmation = str_replace_assoc($ar_insert_data, $template_body) . $signature;
                                                    require_once('fckeditor_new/fckeditor.php');
                                                    $FCKeditor = new FCKeditor('txtemailbody' . $row['id']);
                                                    $FCKeditor->BasePath = 'fckeditor_new/';
                                                    $FCKeditor->Value = $eml_confirmation;
                                                    $FCKeditor->Height = 400;
                                                    $FCKeditor->Width = 800;
                                                    $FCKeditor->Create();
                                                    ?>

                                                <div style="height:15px;">&nbsp;</div>
                                                <input type="button" name="send_quote_email" id="send_quote_email"
                                                    value="Submit"
                                                    onclick="btnsendclick_templ(<?php echo $row['id'] ?>)">

                                                <input type="hidden" name="companyid" id="companyid"
                                                    value="<?php echo $companyid ?>">
                                                <input type="hidden" name="show" id="show"
                                                    value="<?php echo $_REQUEST["show"] ?>">
                                                <input type="hidden" name="hidden_reply_eml"
                                                    id="hidden_reply_eml<?php echo $row['id'] ?>" value="" />
                                                <input type="hidden" name="hidden_sendemail"
                                                    id="hidden_sendemail<?php echo $row['id'] ?>" value="inemailmode">
                                                <input type="hidden" name="warehouse_id_e"
                                                    id="warehouse_id_e<?php echo $row['id']; ?>"
                                                    value="<?php echo $_REQUEST['warehouse_id']; ?>">
                                                <input type="hidden" name="rec_id_e"
                                                    id="rec_id_e<?php echo $row['id']; ?>"
                                                    value="<?php echo $rec_id; ?>">

                                                <input type="hidden" name="summaryrep" id="summaryrep"
                                                    value="<?php echo $_REQUEST["summaryrep"]; ?>">


                                            </td>
                                        </tr>
                                    </table>
                                    <?php

                                        /*} */ ?>

                                </div>
                                <?php }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
    <?php

    // function getnickname($warehouse_name, $b2bid){

    // 	$nickname = "";
    // 	if ($b2bid > 0) {

    // 	 $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = " . $b2bid;
    // 	 $result_comp = db_query($sql,db_b2b() );

    // 	 while ($row_comp = array_shift($result_comp)) {

    // 		 $tmppos_1 = strpos($row_comp["company"], "-");
    // 		 if ($tmppos_1 != false)
    // 		 {

    // 			 $nickname = $row_comp["company"];
    // 		 }else {
    // 			 if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "" ) 
    // 			 {
    // 				 $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"] ;
    // 			 }else { $nickname = $row_comp["company"]; }
    // 		 }
    // 	 }
    //  }else {

    // 	 $nickname = $warehouse_name;
    //  }

    //  return $nickname;
    // }

    /*---------- Function to replace the string -----------*/
    function str_replace_assoc(array $replace, string $subject): string
    {
        return str_replace(array_keys($replace), array_values($replace), $subject);
    }

    /*---------- End Function to replace the string -----------*/

    ?>
    <script>
    initSample();
    </script>
</body>

</html>