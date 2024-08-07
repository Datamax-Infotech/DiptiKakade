<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

//$vnumrows = $_REQUEST['vnumrows'];
if (isset($_REQUEST['edit_report']) && $_REQUEST['edit_report'] == "yes") {
    $filetype = "jpg,jpeg,gif,png,PNG,JPG,JPEG,pdf,PDF";
    $allow_ext = explode(",", $filetype);
    $data = [];
    $updated = 0;
    if (isset($_REQUEST['paid_by']) && ($_REQUEST['paid_by'] != "") && ($_REQUEST['paid_by'] != " ")) {
        if (isset($_REQUEST["dueMailByAR"]) && $_REQUEST["dueMailByAR"] != '') {
            $vendorEmail = $_REQUEST["hdnInvcVendorEmail"];
            if ($_REQUEST["dueMailByAR"] == 'soon') {
                $eml_message = "Due Soon";
            } else {
                $eml_message = "Past";
            }
            //Vendor Mail Send Code 
            // sendemail("No", $mailto, $scc, $sbcc, $from_mail, $from_name, $subject, $eml_message);
        }

        if (!empty($_FILES['payment_proof_file'])) {
            $payment_proof_files = "";
            $payment_proof_name = "";
            foreach ($_FILES['payment_proof_file']['tmp_name'] as $index => $tmpName) {
                if (!empty($tmpName) && is_uploaded_file($tmpName)) {
                    $ext = pathinfo($_FILES["payment_proof_file"]["name"][$index], PATHINFO_EXTENSION);
                    if (in_array(strtolower($ext), $allow_ext)) {
                        $attachfile_nm_tmp = date("Y-m-d hms") . "_" . preg_replace("/'/", "\'", $_FILES['payment_proof_file']['name'][$index]);
                        $payment_proof_files = $payment_proof_files . $attachfile_nm_tmp . "|";
                        move_uploaded_file($tmpName, "water_payment_proof/" . $attachfile_nm_tmp);
                    }
                }
            }
        }
        $payment_proof_files = $payment_proof_files ?? "";
        $tmppos_1 = strpos($payment_proof_files, "|");
        if ($tmppos_1 != false) {
            if (isset($payment_proof_name) != "") {
                $payment_proof_name = $payment_proof_name . "|" . substr($payment_proof_files, 0, strlen($payment_proof_files) - 1);
            } else {
                $payment_proof_name = substr($payment_proof_files, 0, strlen($payment_proof_files) - 1);
            }
        }
        $payment_proof_name = $payment_proof_name ?? "";
        if (isset($payment_proof_name) != "") {
            $payment_proof_name = preg_replace("/'/", "\'", $payment_proof_name);
        }
        $vendor_payment_log_notes = str_replace("'", "\'", $_REQUEST['vendor_payment_log_notes']);
        $update_made_payment_str = isset($_REQUEST['made_payment']) && $_REQUEST['made_payment'] == "made_payment = 1" ? "" : "";
        $sqlUpdtVendrPayRpt = "UPDATE water_transaction SET last_edited = '" . date("Y-m-d H:i:s") . "', vendor_payment_log_notes = '" . $vendor_payment_log_notes . "' $update_made_payment_str, paid_by = '" . $_REQUEST['paid_by'] . "', paid_date = '" . $_REQUEST['paid_date'] . "',";
        if ($_REQUEST["vendorpagename"] == 'UCBZeroWaste_Vendors_AR.php') {
            $sqlUpdtVendrPayRpt .= " ar_status = '" . $_REQUEST['ar_status'] . "',";
        }
        if (isset($payment_proof_name) != '') {
            $sqlUpdtVendrPayRpt .= " payment_proof_file = '" . preg_replace("/'/", "\'", $payment_proof_name) . "', ";
        }
        $sqlUpdtVendrPayRpt .= " payment_method_new = '" . $_REQUEST['payment_method_new'] . "' WHERE invoice_number = '" . $_REQUEST['hdnInvcNo'] . "' AND vendor_id = '" . $_REQUEST['hdnvendrId'] . "' and id = '" . $_REQUEST['hdnWatrTrnstnId'] . "'";
        db();
        $result_sort = db_query($sqlUpdtVendrPayRpt);
        //echo $sqlUpdtVendrPayRpt;
        //echo "INSERT INTO water_transaction_log_notes (`date`,`trans_id`,`employee_id`,`message`) VALUES('" . date("Y-m-d H:i:s") . "', '" . $_REQUEST['hdnWatrTrnstnId'] . "', '" . $_COOKIE['b2b_id'] . "', '".$vendor_payment_log_notes."')";
        $insert_log_notes = db_query("INSERT INTO water_transaction_log_notes (`date`,`trans_id`,`employee_id`,`message`) VALUES('" . date("Y-m-d H:i:s") . "', '" . $_REQUEST['hdnWatrTrnstnId'] . "', '" . $_COOKIE['b2b_id'] . "', '" . $vendor_payment_log_notes . "')");

        $get_updated_data_sql = db_query("SELECT made_payment,paid_by,paid_date,payment_method, payment_method_new, payment_proof_file,ar_status,vendor_payment_log_notes,last_edited FROM water_transaction WHERE invoice_number = '" . $_REQUEST['hdnInvcNo'] . "' AND vendor_id = '" . $_REQUEST['hdnvendrId'] . "' and id = '" . $_REQUEST['hdnWatrTrnstnId'] . "'");
        while ($row = array_shift($get_updated_data_sql)) {
            $data['made_payment'] = $row['made_payment'];
            $data['paid_by'] = $row['paid_by'];
            $data['paid_date'] = $row['paid_date'];
            $data['payment_method'] = $row['payment_method'];
            $data['payment_method_new'] = $row['payment_method_new'];
            $data['payment_proof_file'] = $row['payment_proof_file'];
            $data['ar_status'] = ucfirst($row['ar_status']);
            $data['vendor_payment_log_notes'] = $row['vendor_payment_log_notes'];
            $data['last_edited'] = $row['last_edited'];
            //$data['row_id']=$i;
        }
        $updated = 1;
    }
    echo json_encode(array('updated' => $updated, 'data' => $data));
} else if (isset($_REQUEST['get_all_notes']) && $_REQUEST['get_all_notes'] == "yes") {
    $vendor_id_comm = $_REQUEST["vendor_id"];
    $data_str = "";
    if ($_REQUEST['type'] == "payable") {

        db();
        $special_notes_qry = db_query("SELECT payable_notes, payable_contact_name,id from water_vendors_payable_contact where water_vendor_id='$vendor_id_comm' AND payable_notes!='' ORDER BY created_on DESC");
        $data_str .= "<table class='notes_tbl'><tr><th>Payable Contact Id</th><th>Payable Name</th><th>Payable Notes</th></tr>";
        if (tep_db_num_rows($special_notes_qry) > 0) {
            while ($res = array_shift($special_notes_qry)) {
                $data_str .= "<tr><td>" . $res['id'] . "</td><td style='white-space:nowrap'>" . $res['payable_contact_name'] . "</td><td>" . $res['payable_notes'] . "</td></tr>";
            }
        } else {
            $data_str .= "<tr><td colspan='3' style='color:red'>No Data Found</td></tr>";
        }
        $data_str .= "</table><br>";
    } else {

        db();
        $special_notes_qry = db_query("SELECT receivable_notes, receivable_contact_name,id from water_vendors_receivable_contact where water_vendor_id='$vendor_id_comm' AND receivable_notes!='' ORDER BY created_on DESC");
        $data_str .= "<table class='notes_tbl'><tr><th>Receivable Contact Id</th><th>Receivable Name</th><th>Receivable Notes</th></tr>";
        if (tep_db_num_rows($special_notes_qry) > 0) {
            while ($res = array_shift($special_notes_qry)) {
                $data_str .= "<tr><td>" . $res['id'] . "</td><td style='white-space:nowrap'>" . $res['receivable_contact_name'] . "</td><td>" . $res['receivable_notes'] . "</td></tr>";
            }
        } else {
            $data_str .= "<tr><td colspan='3' style='color:red'>No Data Found</td></tr>";
        }
        $data_str .= "</table><br>";
    }
    echo $data_str;
} else if (isset($_REQUEST['get_all_log_notes']) && $_REQUEST['get_all_log_notes'] == "yes") {
    $transid = $_REQUEST["transid"];
    $data_str = "";
    db();
    $log_notes_qry = db_query("SELECT wt.date, wt.message,le.initials from water_transaction_log_notes as wt JOIN loop_employees as le ON le.b2b_id = wt.employee_id  where wt.trans_id='$transid' ORDER BY date DESC");
    if ($_REQUEST['type'] == "date") {
        $data_str .= "<table class='notes_tbl'><tr><th>Date</th><th>Notes By</th></tr>";
        if (tep_db_num_rows($log_notes_qry) > 0) {
            while ($res = array_shift($log_notes_qry)) {
                $data_str .= "<tr><td style='white-space:nowrap'>" . $res['date'] . "</td><td>" . $res['initials'] . "</td></tr>";
            }
        } else {
            $data_str .= "<tr><td colspan='3' style='color:red'>No Data Found</td></tr>";
        }
        $data_str .= "</table><br>";
    } else {
        $data_str .= "<table class='notes_tbl'><tr><th>Date</th><th>Message</th><th>Notes By</th></tr>";
        if (tep_db_num_rows($log_notes_qry) > 0) {
            while ($res = array_shift($log_notes_qry)) {
                $data_str .= "<tr><td style='white-space:nowrap'>" . $res['date'] . "</td><td>" . $res['message'] . "</td><td>" . $res['initials'] . "</td></tr>";
            }
        } else {
            $data_str .= "<tr><td colspan='3' style='color:red'>No Data Found</td></tr>";
        }
        $data_str .= "</table><br>";
    }
    echo $data_str;
}
if (isset($_REQUEST['send_invoice']) && $_REQUEST['send_invoice'] == 1) {
    $transid = $_REQUEST['transid'];
    db();
    $select_scan_reports = db_query("SELECT scan_report from water_transaction where id = " . $transid);
    $files = array();
    $response = 0;
    if (tep_db_num_rows($select_scan_reports) > 0) {
        while ($rows = array_shift($select_scan_reports)) {
            $tmppos_1 = strpos($rows["scan_report"], "|");
            if ($tmppos_1 != false) {
                $elements = explode("|", $rows["scan_report"]);
                for ($i = 0; $i < count($elements); $i++) {
                    $files[] = $elements[$i];
                }
            } else {
                $files[] = $rows['scan_report'];
            }
        }
        //print_r($files);
        //UCBZeroWaste@bill.com
        $res = sendemail_php_function($files, "/home/usedcardboardbox/public_html/ucbloop/water_scanreport/", "UCBZeroWaste@bill.com", "", "", "ucbemail@usedcardboardboxes.com", "Operations Usedcardboardboxes", "ucbemail@usedcardboardboxes.com", "UCB A/P Aging Invoice", "UCB A/P Aging Invoice are attached.");
        if ($res == "emailsend") {
            if ($_REQUEST['flg'] == 1) {
                $update_invoice_sent_flg = db_query("UPDATE water_transaction set send_invoice_flg = 1 , invoice_sent_on = '" . date("Y-m-d H:i:s") . "', invoice_sent_by = '" . $_COOKIE['b2b_id'] . "' where id = " . $transid);
            }
            $response = 1;
        }
    }
    echo $response;
}
