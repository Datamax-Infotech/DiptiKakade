<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

?>

<?php

$completed_flg = $_REQUEST["completed_flg"];

if ($completed_flg == 1) {
    $so_compl_date = date("Y-m-d h:i:s");
    $rec_id = $_REQUEST["rec_id"];
    $wid = $_REQUEST["wid"];
    $sql = "UPDATE loop_transaction_buyer SET special_order_complete = 1, special_order_complete_date= '" . $so_compl_date . "' WHERE id = '" . $wid . "'";
    db();
    $result = db_query($sql);
    if (empty($result)) {
        //
        $note_date = date("Y-m-d h:i:s");
        $empid = $_COOKIE['employeeid'];
        if ($_REQUEST["updatefrom"] == "report_so") {
            $updatefrom = "from Special Order Summary Report.";
        } else {
            $updatefrom = "from Warehouse dashboards.";
        }
        $message = "System generated log - Special Order instructions completed on " . $note_date . " by " . $_COOKIE['userinitials'] . " " . $updatefrom;
        $rec_type = "Supplier";
        //
        $query = "INSERT INTO loop_transaction_notes (date, company_id, rec_type, rec_id, message, employee_id) VALUES('" . $note_date . "', " . $rec_id . ", '" . $rec_type . "', " . $wid . ", '" . $message . "', " . $empid . ")";
        db();
        $result_nt = db_query($query);
        if ($result_nt) {
        }
        //
        echo "completed";
    }
}
if ($completed_flg == 0) {
    $rec_id = $_REQUEST["rec_id"];
    $wid = $_REQUEST["wid"];
    $sql = "UPDATE loop_transaction_buyer SET special_order_complete = 0, special_order_complete_date= '' WHERE id = '" . $wid . "'";
    db();
    $result = db_query($sql);
    if (empty($result)) {
        //
        $note_date = date("Y-m-d h:i:s");
        $empid = $_COOKIE['employeeid'];
        if ($$_REQUEST["updatefrom"] == "report_so") {
            $updatefrom = "from Special Order Summary Report.";
        } else {
            $updatefrom = "from Warehouse dashboards.";
        }
        $message = "System generated log - Undo Special Order instructions completed on " . $note_date . " " . $_COOKIE['userinitials'] . " " . $updatefrom;
        //
        $rec_type = "Supplier";
        //

        $query = "INSERT INTO loop_transaction_notes (date, company_id, rec_type, rec_id, message, employee_id) VALUES('" . $note_date . "', " . $rec_id . ", '" . $rec_type . "', " . $wid . ", '" . $message . "', " . $empid . ")";
        db();
        $result_nt = db_query($query);
        if ($result_nt) {
        }
        //
        echo "undo completed";
    }
}

?>