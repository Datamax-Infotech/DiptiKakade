<div id="table_loop_paidbubble_inv_sent">
    Loading .....<img src='images/wait_animated.gif' />
</div>

<LINK rel='stylesheet' type='text/css' href='one_style_mrg.css'>
<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
<script LANGUAGE="JavaScript">
document.write(getCalendarStyles());
</script>
<script LANGUAGE="JavaScript">
var cal2xx = new CalendarPopup("listdiv");
cal2xx.showNavigationDropdowns();
</script>

<script>
function FormCheckInv() {
    var thefilename = document.SortReport.file.value;
    var filelength = parseInt(thefilename.length) - 3;
    var fileext = thefilename.substring(filelength, filelength + 3);
    if (document.SortReport.inv_amount.value == "") {
        alert('Please enter an amount.');
        return false;
    } else if (fileext.toLowerCase() != "pdf") {
        alert("You can only upload PDF file.");
        return false;
    }
}
</script>

<!-- To set the Invoice Status-->
<?php

require("inc/header_session.php");
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


$tmp_filename = "";
db();

if (isset($_REQUEST["btnUpdateinvsent"])) {

    srand((int)(microtime(true) * 1000000));
    //srand((float) microtime() * 1000000);
    $random_number = rand();

    // Start Processing Function
    $fileuploaded_flg = "no";
    $sql = "SELECT * FROM tblvariable where variablename = 'upload_file_type_inv'";
    $filetype = "pdf,PDF";
    $result = db_query($sql);
    while ($myrowsel = array_shift($result)) {
        $filetype = $myrowsel["variablevalue"];
    }
    $allow_ext = explode(",", $filetype);

    if ($_FILES["file"]["size"] < 10000000) {
        $tmp_filename = $_FILES["file"]["name"];
        $tmp_filename = preg_replace("/'/", "", $tmp_filename);
        $tmp_filename = stripslashes($tmp_filename);

        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        } else {

            $ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);

            if (in_array(strtolower($ext), $allow_ext)) {
                $fileuploaded_flg = "yes";
                if (file_exists("files/" . $random_number . "_" . $tmp_filename)) {
                    echo $tmp_filename . " already exists. ";
                } else {
                    move_uploaded_file(
                        $_FILES["file"]["tmp_name"],
                        "files/" . $random_number . "_" . $tmp_filename
                    );
                }
            } else {
                $fileuploaded_flg = "err";
                echo "<font color=red>" . $_FILES["file"]["name"] . " file not uploaded, this file type is restricted.</font>";
            }
        }
    } else {
        echo "Invalid file";
    }


    if ($fileuploaded_flg != "err") {
        $today = date("m/d/Y");
        $today_crm = date("Ymd");
        $warehouse_id = $_POST["warehouse_id"];
        $id = $_POST["ID"];
        $rec_type = $_POST["rec_type"];
        $user = $_COOKIE['userinitials'];
        $inv_file = $_POST["file"];
        $inv_number = $_POST["inv_number"];
        $inv_amount = $_POST["inv_amount"];
        $inv_employee = $user;
        $inv_date_of = $_POST["inv_date_of"];
        $inv_date = $today;
        $rec_id = $_POST["rec_id"];
        $recipient = $_POST["recipient"];

        $sql = "UPDATE loop_transaction_buyer SET inv_file = '" . $random_number . "_" . $tmp_filename . "', inv_employee = '" . $inv_employee . "', inv_number = '" . $inv_number . "', inv_amount = '" . $inv_amount . "', total_revenue = '" . $inv_amount . "', inv_date_of = '" . $inv_date_of . "', inv_date = '" . $inv_date . "', inv_entered = 1 WHERE id = '" . $rec_id . "'";
        $result = db_query($sql);

        $sql = "Insert into loop_transaction_buyer_inv_sent (trans_rec_id, inv_file, inv_employee, inv_number, inv_amount, inv_date_of, inv_date) select '" . $rec_id . "', '" . $random_number . "_" . $tmp_filename . "', '" . $inv_employee . "', '" . $inv_number . "', '" . $inv_amount . "', '" . date("Y-m-d", strtotime($inv_date_of)) . "', '" . date("Y-m-d H:i:s") . "'";
        $result = db_query($sql);

        //To record the updation in the Invoice values for P&L report
        $sql = "INSERT INTO rep_p_and_l_affect_amt_history ( transaction_buyer_id, company_id, employee_id, entry_date, inv_amount, inv_date, change_value, updation_notes, add_del_upd_flg) 
			VALUES ('" . $rec_id . "', '" . $id . "', '" . $_COOKIE['employeeid']  . "', '" . date("Y-m-d H:i:s") . "', '" . str_replace(",", "", $inv_amount) . "',
			'" .  date("Y-m-d", strtotime($inv_date_of)) . "', '', 'Added from \'INVOICE SENT\' table.', 1)";
        db();
        $resp = db_query($sql);

        $message = "<strong>Note for Transaction # ";
        $message .=  $rec_id;
        $message .= "</strong>: ";
        $message .=  $inv_employee;
        $message .= " Uploaded an Invoice: ";
        $message .= $tmp_filename;

        $sql_crm = "INSERT INTO loop_crm  ( warehouse_id, message_date, employee, comm_type, message) VALUES ( '" . $warehouse_id . "', '" . $today_crm . "', '" . $inv_employee . "', '5', '" . $message . "')";

        $result_crm = db_query($sql_crm);

        $sql_1 = "Select * from loop_invoice_details Where trans_rec_id = '" . $rec_id . "'";
        db();
        $res_loop_invoice_details = db_query($sql_1);
        while ($inv_row = array_shift($res_loop_invoice_details)) {



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

            $eml_confirmation .= "<tr><td style=\"width:100%%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#a6a6a6;\"><br><span style=\"font-size:12pt;color:#a6a6a6;\" >ORDER # " . $rec_id . "</a> (PO " . isset($po_ponumber) . ") </span>
				<br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:16pt;color:#000000;\" ><a href='http://loops.usedcardboardboxes.com/viewCompany.php?ID=" . $id . "&show=transactions&warehouse_id=" . $warehouse_id . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $warehouse_id . "&rec_id=" . $rec_id . "&display=buyer_payment'>" . get_nickname_val('', $id) . "</a></div>
				<br><br><div style=\"width:100%;font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:19pt;color:#000000;\" >QuickBooks invoice uploaded</div></td></tr>";

            $eml_confirmation .= "<tr><td><br><div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif';font-size:12pt;color:#767171; margin-top:3px; margin-bottom:3px;\">
				UCB Accounts Receivable Team, the matching invoice from QuickBooks has been uploaded. 
				</div>	</td></tr>";

            $eml_confirmation .= "<tr><td><br><span style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:17pt;color:#3b3838;\">Invoice details</span>
				<br>
				<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Invoice #:</strong> " . $inv_number . "</div>
				
                <div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Invoice Amount:</strong> $" . number_format((float) str_replace(",", "", $inv_row["total"]), 2) . "</div>

				<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Invoice Date:</strong> " . date("m/d/Y", strtotime($inv_row["timestamp"])) . "</div>
				<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Credit Terms:</strong> " . $inv_row["terms"] . "</div>
				<div style=\"font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, 'sans-serif'; font-size:13pt;color:#808080; margin-top:5px;\"><strong>Bookkeeper Notes:</strong> " . $inv_row["bookkeeper"] . "</div>
				<br><br></td></tr>";

            $eml_confirmation .= "</table></td></tr></tbody></table></div></body></html>";

            $from_email = "accounting@usedcardboardboxes.com";
            $to_email = "AR@UsedCardboardBoxes.com";

            //$to_email = "prasad@extractinfo.com";
            sendemail_php_function(null, '', $to_email, '', '', $from_email, $from_email, $from_email, "QuickBooks Invoice " . $inv_number . " Uploaded for Order #" . $rec_id, $eml_confirmation);
        }
    }
}
?>


<?php


$insent_rec_found = "n";
$inv_number = "";
$dt_view_qry = "SELECT * from loop_transaction_buyer WHERE id = '" . $_REQUEST['rec_id'] . "' AND inv_file != ''";
$dt_view_res = db_query($dt_view_qry);
while ($dt_view_row = array_shift($dt_view_res)) {
    $insent_rec_found = "y";
    $inv_number = $dt_view_row["loop_qb_invoice_no"];
?>
<br>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 550px" id="table11">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="5" height="19">
            <font size="1">SENT INVOICES</font>
        </td>
    </tr>


    <tr bgColor="#e4e4e4">

        <td height="13" class="style1">
            <p align="center">Invoice File
        </td>
        <td height="13" class="style1">
            <p align="center">Invoice Number
        </td>
        <td height="13" class="style1">
            <p align="center">Amount
        </td>
        <td height="13" class="style1">
            <p align="center">Date of Invoice
        </td>
        <td height="13" class="style1">
            <p align="center">Entry Date
        </td>
    </tr>

    <tr bgColor="#e4e4e4">
        <td height="10" style="width: 246px" class="style1" align="center">
            <p align="left">
                <Font Face='arial' size='2'>
                    <a style="color:#0000FF;" target="_blank"
                        href="files/<?php echo preg_replace("/'/", "\'", $dt_view_row["inv_file"]); ?>">View File:
                        <?php echo $dt_view_row["inv_file"]; ?></a>
        </td>
        </font>
        </font>
        </font>


        <Font size='2'>

            <td align="center" height="10" style="width: 98px" class="style1">
                <?php echo $dt_view_row["inv_number"]; ?></td>
            <td align="center" height="10" style="width: 98px" class="style1">


                <p align="right">

                    <?php $the_amount = $dt_view_row["inv_amount"]; ?>
                    $<?php echo number_format($dt_view_row["inv_amount"], 2); ?>
            </td>

            <td align="center" height="10" style="width: 98px" class="style1">
                <p align="right">
                    <?php if ($dt_view_row["inv_date_of"] != "") {
                            echo date("m/d/Y", strtotime($dt_view_row["inv_date_of"])) . " CT";
                        }
                        ?>
            </td>


            <Font Face='arial' size='2'>


                <td align="center" height="10" style="width: 110px" class="style1">

                    <?php echo date("m/d/Y H:i:s", strtotime($dt_view_row["inv_date"])) . " CT"; ?> -
                    <?php echo $dt_view_row["inv_employee"]; ?>
            </font>
            </td>
    </tr>
    </font>
    <Font Face='arial' size='2'>
</table>

<?php } ?>

<br>
<table cellSpacing="1" cellPadding="1" border="0" style="width: 550px" id="table11">
    <tr align="middle">
        <td bgColor="#99FF99" colSpan="5" height="19">
            <font size="1">SENT INVOICES (History)</font>
        </td>
    </tr>

    <tr bgColor="#e4e4e4">

        <td height="13" class="style1">
            <p align="center">Invoice File
        </td>
        <td height="13" class="style1">
            <p align="center">Invoice Number
        </td>
        <td height="13" class="style1">
            <p align="center">Amount
        </td>
        <td height="13" class="style1">
            <p align="center">Date of Invoice
        </td>
        <td height="13" class="style1">
            <p align="center">Entry Date
        </td>
    </tr>

    <?php


    $dt_view_qry = "SELECT * from loop_transaction_buyer_inv_sent WHERE trans_rec_id = '" . $_REQUEST['rec_id'] . "' order by unqid";
    $dt_view_res = db_query($dt_view_qry);
    while ($dt_view_row = array_shift($dt_view_res)) {

    ?>

    <tr bgColor="#e4e4e4">
        <td height="10" style="width: 246px" class="style1" align="center">
            <p align="left">
                <Font Face='arial' size='2'>
                    <a style="color:#0000FF;" target="_blank"
                        href="files/<?php echo preg_replace("/'/", "\'", $dt_view_row["inv_file"]); ?>">View File:
                        <?php echo $dt_view_row["inv_file"]; ?></a>
        </td>
        </font>
        </font>
        </font>


        <Font size='2'>

            <td align="center" height="10" style="width: 98px" class="style1">
                <?php echo $dt_view_row["inv_number"]; ?></td>
            <td align="center" height="10" style="width: 98px" class="style1">


                <p align="right">

                    <?php $the_amount = $dt_view_row["inv_amount"]; ?>
                    $<?php echo number_format($dt_view_row["inv_amount"], 2); ?>
            </td>

            <td align="center" height="10" style="width: 98px" class="style1">
                <p align="right">
                    <?php if ($dt_view_row["inv_date_of"] != "") {
                            echo date("m/d/Y", strtotime($dt_view_row["inv_date_of"])) . " CT";
                        }
                        ?>
            </td>


            <Font Face='arial' size='2'>


                <td align="center" height="10" style="width: 110px" class="style1">

                    <?php echo date("m/d/Y H:i:s", strtotime($dt_view_row["inv_date"])) . " CT"; ?> -
                    <?php echo $dt_view_row["inv_employee"]; ?>
            </font>
            </td>
    </tr>
    </font>
    <Font Face='arial' size='2'>

        <?php } ?>
</table>

<br>
<form action="loop_paidbubble_inv_sent.php" method="post" encType="multipart/form-data" name="SortReport"
    onSubmit="return FormCheckInv()">
    <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST['warehouse_id']; ?>" />
    <input type="hidden" name="rec_type" value="Supplier" />
    <input type="hidden" name="recipient" value="<?php echo isset($warehouse_contact_email); ?>" />
    <input type="hidden" value="<?php echo $_COOKIE['userinitials'] ?>" name="employee" />
    <input type="hidden" name="rec_id" value="<?php echo $_REQUEST['rec_id']; ?>" />
    <input type="hidden" name="ID" value="<?php echo $_REQUEST['ID']; ?>" />
    <input type="hidden" name="inv_number" id="inv_number" value="<?php echo $inv_number; ?>" />

    <table cellSpacing="1" cellPadding="1" border="0" style="width: 550px" id="table11">
        <tr align="middle">
            <?php if ($insent_rec_found == "y") { ?>
            <td bgColor="#99FF99" colSpan="4" height="19">
                <?php } else { ?>
            <td bgColor="#fb8a8a" colSpan="4" height="19">
                <?php } ?>
                <font size="1">INVOICE SENT</font>
            </td>
        </tr>
        <tr bgColor="#e4e4e4">

            <td height="13" class="style1">
                <p align="center">Invoice File
            </td>
            <td height="13" class="style1">
                <p align="center">Invoice Number
            </td>
            <td height="13" class="style1">
                <p align="center">Amount
            </td>
            <td height="13" class="style1">
                <p align="center">Date of Invoice
            </td>
        </tr>
        </font>
        </font>
        </font>

        <Font size='2'>

            <tr bgColor="#e4e4e4">

                <td align="center" height="13" class="style1">
                    <input type=file name="file" size="10">&nbsp;

                </td>

                <td align="center" height="13" class="style1">
                    <input size="5" type=text name="inv_number">
                </td>
                <td align="center" height="13" class="style1" width="15%">
                    $<input size="5" type=text name="inv_amount"></td>
                <td height="13" class="style1" align="left">
                    <input type=text name="inv_date_of" size="8"> <a style="color:#0000FF;" href="#"
                        onclick="cal2xx.select(document.SortReport.inv_date_of,'anchor2xx','MM/dd/yyyy'); return false;"
                        name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>
                    <div ID="listdiv"
                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
                </td>
            </tr>
            <tr bgColor="#e4e4e4">
                <td align="center" height="13" colspan=4 class="style1">
                    <input type="submit" value="Upload" id="btnUpdateinvsent" name="btnUpdateinvsent"
                        style="cursor:pointer;">
                </td>
            </tr>
    </table>
</form>
<br>
<script>
document.getElementById("table_loop_paidbubble_inv_sent").style.display = "none";
</script>