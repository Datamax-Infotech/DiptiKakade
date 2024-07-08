<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

?>

<?php

$update_flg = $_REQUEST["update_flg"];
if ($update_flg == 1) {
    $note_date = date("Y-m-d h:i:s");
    $wid = $_REQUEST["rec_id"];
    $rec_id = $_REQUEST["wid"];
    $empid = $_REQUEST["empid"];
    $message = str_replace("'", "\'", ($_REQUEST["last_note"]));
    $rec_type = $_REQUEST["rec_type"];
    //

    //
    $query = "INSERT INTO loop_transaction_notes (date, company_id, rec_type, rec_id, message, employee_id) VALUES('" . $note_date . "', " . $wid . ", '" . $rec_type . "', " . $rec_id . ", '" . $message . "', " . $empid . ")";
    db();
    $result = db_query($query);
    if ($result) {
        $id = tep_db_insert_id();
        $nquery = "select * from loop_transaction_notes where id=" . $id;
        db();
        $nresult = db_query($nquery);
        $nrow = array_shift($nresult);
?>

<form name="trans_info">
    <input type="hidden" name="trans_note" id="trans_note" value="<?php echo $nrow["message"]; ?>">
    <input type="hidden" name="trans_date" id="trans_date" value="<?php echo $nrow["date"]; ?>">
</form>

<?php

        $assignedto = 0;
        $comp_b2bid = 0;
        $acc_owner_email = "";
        $notes_company = "";
        $sql = "SELECT b2bid, warehouse_name from loop_warehouse where id = ?";
        $result_comp = db_query($sql, array("i"), array($_REQUEST["wid"]));
        while ($row_comp = array_shift($result_comp)) {
            $comp_b2bid = $row_comp["b2bid"];
            $notes_company = get_nickname_val($row_comp["warehouse_name"], $comp_b2bid);
        }

        db_b2b();
        $sql = "SELECT assignedto from companyInfo where ID = ?";
        $result_comp = db_query($sql, array("i"), array($comp_b2bid));
        while ($row_comp = array_shift($result_comp)) {
            $assignedto = $row_comp["assignedto"];
        }

        $sql = "SELECT email FROM employees WHERE employees.status='Active' and employeeID = ?";
        $result_comp = db_query($sql, array("i"), array($assignedto));
        while ($row_comp = array_shift($result_comp)) {
            $acc_owner_email = $row_comp["email"];
        }

        if ($acc_owner_email != "") {
            db();
            $notes_email_tobe_sent = "no";

            $sql = "SELECT message,date,loop_employees.name FROM loop_transaction_notes";
            $sql .= " INNER JOIN loop_employees ON loop_transaction_notes.employee_id = loop_employees.id";
            $sql .= " WHERE loop_transaction_notes.company_id = " .  $_REQUEST["wid"] . " AND";
            $sql .= " loop_transaction_notes.rec_id = " . $_REQUEST["rec_id"];
            //echo $sql."<br><br>";
            db();
            $result = db_query($sql);

            $tdno = 0;
            $str_email = "";
            $str_email = "<html><head></head><body bgcolor='#E7F5C2'><table border=0 align='center' cellpadding='0' width='700px' bgcolor='#E7F5C2'><tr><td ><p align='center'><img width='650' height='166' src='https://loops.usedcardboardboxes.com/images/ucb-banner1.jpg'></p></td></tr><tr><td><br>";
            $str_email .= "<table width='700px' cellSpacing='1' cellPadding='3'><tr><th colspan=3>TRANSACTION LOG UPDATES</th></tr>";

            $str_email .= "<tr><td bgColor='#98bcdf' colspan=3><a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=" . $comp_b2bid . "&warehouse_id=" . $_REQUEST["wid"] . "&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id= " . $_REQUEST["wid"] . "&rec_id=" . $_REQUEST["rec_id"] . "&display=buyer_view'>" . $notes_company . "</font></a></td></tr>";

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
                $str_email .= "<tr><td bgColor='" . $tdbgcolor . "'><font face='Arial, Helvetica, sans-serif' size='1'>" . $the_log_date . "</font></td><td bgColor='" . $tdbgcolor . "'><font face='Arial, Helvetica, sans-serif' size='1'>" . $myrowsel['name'] . "</font></td>";

                //$str_email.="<td bgColor='".$tdbgcolor."'><a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=".$comp_b2bid."&warehouse_id=" . $_REQUEST["wid"] . "&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id= " . $_REQUEST["wid"] . "&rec_id=". $_REQUEST["rec_id"] ."&display=buyer_view'>" . $notes_company . "</font></a></td>";

                $str_email .= "<td bgColor='" . $tdbgcolor . "'><font face='Arial, Helvetica, sans-serif' size='1'>" . $myrowsel['message'] . "</font></td><tr><tr><td height='7px' colspan=4></td></tr>";

                /*$str_email = "<b>Transaction Log Update</b>:<br>";
				$str_email.= "Company name: ". "<a href='https://loops.usedcardboardboxes.com/viewCompany.php?ID=".$comp_b2bid."&warehouse_id=" . $_REQUEST["wid"] . "&show=transactions&rec_type=Supplier&proc=View&searchcrit=&id= " . $_REQUEST["wid"] . "&rec_id=". $_REQUEST["rec_id"] ."&display=buyer_view'>" . $notes_company . "</a><br>";
				$str_email.= "Log Entered By: ".$myrowsel['name']."<br><br/>";
				$str_email.= "Transaction log note: ".$myrowsel['message']."<br>";
				$str_email.= "Transaction log date/time: ".$the_log_date ."<br><br>";*/
            }
            $str_email .= "</table></td></tr><tr><td><p align='center'><img width='650' height='87' src='https://loops.usedcardboardboxes.com/images/ucb-footer1.jpg'></p></td></tr><tr><td width='23'><p>&nbsp; </p></td><td width='682'><p>&nbsp; </p></td></tr></table></body></html>";

            //$emlstatus = sendemail_attachment_new(null, "", $acc_owner_email, "", "", "operations@usedcardboardboxes.com","Admin UCB", "", "Transaction Log Update for " . $notes_company . " - " . $_REQUEST['rec_id'] , $str_email );
            $emlstatus = sendemail_php_function(null, '', $acc_owner_email, "", "", "ucbemail@usedcardboardboxes.com", "Operations Usedcardboardboxes", "operations@usedcardboardboxes.com", "Transaction Log Update for " . $notes_company . " - " . $_REQUEST['rec_id'], $str_email);
        }
    }
}
?>