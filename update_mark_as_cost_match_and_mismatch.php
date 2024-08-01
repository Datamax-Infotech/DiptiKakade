<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


$assignedto = "";
$fn = "";

function get_employee_initial(int $emp_id): string
{

    $query_emp = "SELECT * FROM loop_employees where id=" . $emp_id;
    db();
    $res_query_emp = db_query($query_emp);
    $emp_nm = "";
    while ($res = array_shift($res_query_emp)) {
        $emp_nm = $res["initials"];
    }
    return $emp_nm;
}


$mailsendto = "Operations@UsedCardboardBoxes.com";
//$mailsendto = "prasad.brid@mooneem.com";
$from_email = "accounting@usedcardboardboxes.com";
$main_id = "";
$trans_id = "";

if ($_POST['mark_action'] == 1) {

    $mail_subject = "Vendor Cost - Marked As Cost Mismatch for Transaction#";

    $main_id = $_POST['mark_as_cost_match_and_mismatch_id'];
    $trans_id = $_POST['mark_as_cost_match_and_mismatch_transaction_id'];

    $account_owner_email = "";
    $comp_b2bid = 0;
    db();
    $sql_x = "Select b2bid from loop_warehouse inner join loop_transaction_buyer on loop_transaction_buyer.warehouse_id = loop_warehouse.id Where loop_transaction_buyer.id = '" . $trans_id . "'";
    $dt_view_res_n = db_query($sql_x);
    while ($row_forb2b = array_shift($dt_view_res_n)) {
        $comp_b2bid = $row_forb2b["b2bid"];
    }

    db_b2b();
    $sql_x = "Select assignedto from companyInfo Where ID = '" . $comp_b2bid . "'";
    $dt_view_res_n = db_query($sql_x);
    while ($row_forb2b = array_shift($dt_view_res_n)) {
        $assignedto = $row_forb2b["assignedto"];
    }

    $sql_x = "SELECT email FROM employees where employeeID=" . $assignedto;
    $dt_view_res_n = db_query($sql_x);
    while ($row_forb2b = array_shift($dt_view_res_n)) {
        $account_owner_email = $row_forb2b["email"];
    }

    $note = $_POST['mark_as_cost_match_and_mismatch_notes'];
    $employee_id = $_COOKIE['employeeid'];
    $fileupload_path = "cost_match_mismatch_verdor_files/";
    $vendor_file = $_FILES["vendor_file"]["name"];

    $fn = "";
    db();
    $uploadNeed = 1;
    $fileuploaded_flg = "no";
    $filetype = "pdf,PDF,jpg,JPG,jpeg,JPEG";
    $sql = "SELECT * FROM tblvariable where variablename = 'cost_match_mismatch_verdor_file'";

    db();
    $result = db_query($sql);
    while ($myrowsel = array_shift($result)) {
        $filetype = $myrowsel["variablevalue"];
    }
    $allow_ext = explode(",", $filetype);
    if (!empty($_FILES)) {
        foreach ($_FILES['vendor_file']['tmp_name'] as $index => $tmpName) {
            if (!empty($_FILES['vendor_file']['error'][$index])) {
            } else {
                if (!empty($tmpName) && is_uploaded_file($tmpName)) {
                    $ext = pathinfo($_FILES["vendor_file"]["name"][$index], PATHINFO_EXTENSION);
                    if (in_array(strtolower($ext), $allow_ext)) {
                        $chk_if_file_pdf = "no";
                        if ($_FILES["vendor_file"]["type"][$index] == "application/pdf") {
                            $chk_if_file_pdf = "yes";
                        }

                        if ($chk_if_file_pdf == "no") {
                            //check if its image file
                            if (!getimagesize($tmpName)) {
                                echo "<font color=red>" . $_FILES["vendor_file"]["name"] . " file not uploaded, this file type is restricted.</font>";
                                echo "<script>alert('" . $_FILES["vendor_file"]["name"] . " file not uploaded, this file type is restricted.');</script>";
                                redirect($_SERVER['HTTP_REFERER']);
                            }
                        }

                        $blacklist = array(".php", ".phtml", ".php3", ".php5", ".php4", ".js", ".shtml", ".pl", ".py");
                        foreach ($blacklist as $file) {
                            if (preg_match("/$file\$/i", $tmpName)) {
                                echo "<font color=red>" . $tmpName . " file not uploaded, this file type is restricted.</font>";
                                echo "<script>alert('" . $tmpName . " file not uploaded, this file type is restricted.');</script>";
                                redirect($_SERVER['HTTP_REFERER']);
                            }
                        }
                        $fn = mt_rand() . "_" . FixString($_FILES["vendor_file"]['name'][$index]);
                        move_uploaded_file($tmpName, $fileupload_path . $fn);
                    } else {
                        echo "<font color=red>" . $_FILES["File"]["name"] . " file not uploaded, this file type is restricted.</font>";
                        echo "<script>alert('" . $_FILES["File"]["name"] . " file not uploaded, this file type is restricted.');</script>";
                    }
                }
            }
        }
    }

    // To update loop_transaction_buyer_payments  table	
    $sql_str = " mark_cost_mismatch_flag = '1', mark_as_cost_mismatch_amount = '" . $_POST['mark_as_cost_mismatch_amount'] . "', mark_cost_mismatch_by = '" . $_COOKIE['employeeid'] . "', mark_cost_mismatch_note = '" . $note . "', mark_cost_mismatch_file = '" . $fn . "', mark_cost_mismatch_date ='" . date("Y-m-d H:i:s") . "'";
    $sql_transaction_buyer_payments = "UPDATE loop_transaction_buyer_payments SET $sql_str WHERE id = '" . $main_id . "'";
    $result = db_query($sql_transaction_buyer_payments);

    // To update loop_transaction_buyer  table	
    $sql_transaction_buyer = "UPDATE loop_transaction_buyer SET mark_cost_mismatch_flag = '1' WHERE id = '" . $trans_id . "'";
    $result1 = db_query($sql_transaction_buyer);
    $typename = "";
    $box_typename = "";
    $expected_cost = "";
    $freight_broker = "";
    $user_nm = "";
    $entry_dt = "";
    $notes = "";
    $entry_dt_cost = "";
    $notes_cost = "";
    $vender_file = "";
    $getOldData = db_query("SELECT * FROM loop_transaction_buyer_payments WHERE id ='" . $main_id . "'");
    while ($rowsOldData = array_shift($getOldData)) {
        $box_typename = $rowsOldData['box_type'];
        $expected_cost = $rowsOldData['estimated_cost'];
        $entry_dt = $rowsOldData['date'];
        $notes    = $rowsOldData['notes'];
        $entry_dt_cost = $rowsOldData['mark_cost_mismatch_date'];
        $notes_cost    = $rowsOldData['mark_cost_mismatch_note'];
        $vender_file = $rowsOldData['mark_cost_mismatch_file'];
        $dt_view_qry2 = "SELECT name from files_companies where id = " . $rowsOldData["company_id"];
        db();
        $dt_view_res2 = db_query($dt_view_qry2);
        while ($dt_view_row2 = array_shift($dt_view_res2)) {
            $freight_broker = $dt_view_row2["name"];
        }
        $user_nm = get_employee_initial($rowsOldData["employee_id"]);
        $cost_user_nm = get_employee_initial($rowsOldData["mark_cost_mismatch_by"]);

        $getOldData1 = db_query("SELECT typename FROM loop_vendor_type WHERE id ='" . $rowsOldData['typeid'] . "'");
        while ($rowsOldData1 = array_shift($getOldData1)) {
            $typename = $rowsOldData1["typename"];
        }
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

    $eml_confirmation .= "<div style='padding:20px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";

    $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >TRANSACTION # " . $trans_id . "</a> </span>
		<br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#000000;\" ><a href='viewCompany.php?ID=" . isset($company_id) . "&show=transactions&warehouse_id=" .
        isset($warehouse_id) . "&rec_type=Supplier&proc=View&searchcrit=&id=" . isset($warehouse_id) . "&rec_id=" . $_REQUEST['rec_id'] . "&display=buyer_invoice'>" . get_nickname_val('', $company_id) . "</a></div>
		<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Vendor Cost mismatch has been updated in loops</div></td></tr>";

    $eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Cost details</span>
		<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Type:</strong> " . $typename . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Box Type:</strong> " . $box_typename . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Expected Costs:</strong> $" . $expected_cost . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Vendor Company:</strong> " . $freight_broker . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Employee:</strong> " . $user_nm . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Date:</strong> " . $entry_dt . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Notes:</strong> " . $notes . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Vendor Company:</strong> " . $freight_broker . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Cost Mismatch Note By:</strong> " . isset($cost_user_nm) . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Cost Mismatch Note:</strong> " . $notes_cost . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Updated On:</strong> " . $entry_dt_cost . "</div>
		
		<br><br></td></tr>";

    $eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";
    $filesarray[0] = $fn;

    if (empty($fn)) {
        sendemail_php_function(null, "", $mailsendto, $account_owner_email, '', $from_email, $from_email, $from_email, $mail_subject . $trans_id, $eml_confirmation);
    } else {
        sendemail_php_function($filesarray, $fileupload_path, $mailsendto, $account_owner_email, '', $from_email, $from_email, $from_email, $mail_subject . $trans_id, $eml_confirmation);
    }
} else if ($_POST['mark_action'] == 2) {
    $mail_subject = "Vendor Cost - Unmarked As Cost Mismatch for Transaction#";

    $main_id = $_POST['unmark_as_cost_match_and_mismatch_id'];
    $trans_id = $_POST['unmark_as_cost_match_and_mismatch_transaction_id'];
    $note = $_POST['unmark_as_cost_match_and_mismatch_notes'];
    $employee_id = $_COOKIE['employeeid'];

    db();
    // To update loop_transaction_buyer_payments  table	
    $sql_str = " mark_cost_mismatch_flag = '2', unmark_cost_mismatch_by = '" . $_COOKIE['employeeid'] . "', unmark_cost_mismatch_note = '" . $note . "', unmark_cost_mismatch_date ='" . date("Y-m-d H:i:s") . "'";
    $sql_transaction_buyer_payments = "UPDATE loop_transaction_buyer_payments SET $sql_str WHERE id = '" . $main_id . "'";
    $result = db_query($sql_transaction_buyer_payments);

    // To update loop_transaction_buyer  table	
    $sql_transaction_buyer = "UPDATE loop_transaction_buyer SET mark_cost_mismatch_flag = '2' WHERE id = '" . $trans_id . "'";
    $result1 = db_query($sql_transaction_buyer);

    $typename = "";
    $box_typename = "";
    $expected_cost = "";
    $freight_broker = "";
    $user_nm = "";
    $entry_dt = "";
    $notes = "";
    $entry_dt_cost = "";
    $notes_cost = "";
    $cost_user_nm = "";
    $getOldData = db_query("SELECT * FROM loop_transaction_buyer_payments WHERE id ='" . $main_id . "'");
    while ($rowsOldData = array_shift($getOldData)) {
        $box_typename = $rowsOldData['box_type'];
        $expected_cost = $rowsOldData['estimated_cost'];
        $entry_dt = $rowsOldData['date'];
        $notes    = $rowsOldData['notes'];
        $entry_dt_cost = date('m/d/Y', strtotime($rowsOldData['unmark_cost_mismatch_date'])) . " at " . date('h:i:s', strtotime($rowsOldData['unmark_cost_mismatch_date']));
        $notes_cost    = $rowsOldData['unmark_cost_mismatch_note'];
        $dt_view_qry2 = "SELECT name from files_companies where id = " . $rowsOldData["company_id"];
        db();
        $dt_view_res2 = db_query($dt_view_qry2);
        while ($dt_view_row2 = array_shift($dt_view_res2)) {
            $freight_broker = $dt_view_row2["name"];
        }
        $user_nm = get_employee_initial($rowsOldData["employee_id"]);
        $cost_user_nm = get_employee_initial($rowsOldData["unmark_cost_mismatch_by"]);
        $getOldData1 = db_query("SELECT typename FROM loop_vendor_type WHERE id ='" . $rowsOldData['typeid'] . "'");
        while ($rowsOldData1 = array_shift($getOldData1)) {
            $typename = $rowsOldData1["typename"];
        }
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

    $eml_confirmation .= "<div style='padding:20px;' align='center'><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"width:100%;border-collapse:collapse;max-width:100%\"><tbody><tr><td style='padding:0cm 0cm 0cm 0cm'><table border='0' cellspacing='0' cellpadding='0' style=\"border-collapse:collapse;\">";

    $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >TRANSACTION # " . $trans_id . "</a> </span>
		<br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#000000;\" ><a href='viewCompany.php?ID=" . isset($company_id) . "&show=transactions&warehouse_id=" . isset($warehouse_id) . "&rec_type=Supplier&proc=View&searchcrit=&id=" . isset($warehouse_id) . "&rec_id=" . $_REQUEST['rec_id'] . "&display=buyer_invoice'>" . get_nickname_val('', $company_id) . "</a></div>
		<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >Vendor Cost has been updated in loops</div></td></tr>";

    $eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Cost details</span>
		<br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Type:</strong> " . $typename . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Box Type:</strong> " . $box_typename . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Expected Costs:</strong> $" . $expected_cost . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Vendor Company:</strong> " . $freight_broker . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Employee:</strong> " . $user_nm . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Date:</strong> " . $entry_dt . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Notes:</strong> " . $notes . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Vendor Company:</strong> " . $freight_broker . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Unmark Cost Mismatch By:</strong> " . $cost_user_nm . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Unmark Cost Mismatch Note:</strong> " . $notes_cost . "</div>
		<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Updated On:</strong> " . $entry_dt_cost . "</div>
		
		<br><br></td></tr>";

    $eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";
    $filesarray[0] = $fn;

    sendemail_php_function(null, "", $mailsendto, '', '', $from_email, $from_email, $from_email, $mail_subject . $trans_id, $eml_confirmation);
}

redirect($_SERVER['HTTP_REFERER']);
