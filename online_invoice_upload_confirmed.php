<?php
session_start();

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

$sql = "";


if ((isset($_REQUEST["confrimbtn"])) && ($_REQUEST["confrimbtn"] == "1")) {
	$date_log = date("Y-m-d H:i:s");
	$trans_rec_id = $_REQUEST["trans_rec_id"];
	$compid = $_REQUEST["compid"];
	$warehouseid = $_REQUEST["warehouseid"];
	$confirmed_flg = $_REQUEST["confirmed_flg"];

	if ($confirmed_flg == "1") {
		$sql = "UPDATE loop_transaction_buyer SET online_inv_confirmed = '" . $confirmed_flg . "',  online_inv_confirmed_by = '" . $_COOKIE['userinitials'] . "', online_inv_confirmed_on = '" . $date_log . "' WHERE id = '" . $trans_rec_id . "'";
	}
	if ($confirmed_flg == "0") {
		$sql = "UPDATE loop_transaction_buyer SET online_inv_confirmed = '" . $confirmed_flg . "',  online_inv_confirmed_by = '', online_inv_confirmed_on = '' WHERE id = '" . $trans_rec_id . "'";
	}
	db();
	$result = db_query($sql);



	$sql_onlineinv = "SELECT online_inv_confirmed, online_inv_confirmed_by, online_inv_confirmed_on FROM loop_transaction_buyer WHERE id = " . $trans_rec_id;
	db();
	$rec_onlineinv = db_query($sql_onlineinv);
	$rec_onlineinvrow = array_shift($rec_onlineinv);
	$online_inv_confirmed = $rec_onlineinvrow["online_inv_confirmed"];
	$online_inv_confirmed_by = $rec_onlineinvrow["online_inv_confirmed_by"];
	$online_inv_confirmed_on = $rec_onlineinvrow["online_inv_confirmed_on"];
?>

<table cellSpacing="1" cellPadding="1" border="0" style="width: 300px" id="cust_online_inv_tbl">
    <tr align="middle">
        <?php if ($online_inv_confirmed == "0") { ?>
        <td bgColor="#fb8a8a" colSpan="2">
            <?php } else { ?>
        <td bgColor="#99FF99" colSpan="2">
            <?php } ?>
            <font size="1">Online Invoicing Customer System&nbsp;</font>
        </td>
    </tr>
    <tr bgColor="#e4e4e4">
        <td height="13" style="width: 100px" class="style1" colSpan="2" align="center">


            <?php
				if ($online_inv_confirmed == 0) {
				?>
            Click on the Confirm button to ensure that the invoice is uploaded in the Customer Online Invoicing System.
            <br>
            <form>
                <input type="button" name="customer_onlineinv_btn" id="customer_onlineinv_btn" value="Confirm"
                    onclick="customer_onlineinv_confirmed(1, <?php echo $trans_rec_id; ?>, <?php echo $compid; ?>, <?php echo $warehouseid; ?>)">
            </form>
            <?php
				}
				if ($online_inv_confirmed == 1) {
				?>
            <font size="1">Confirmed by: <?php echo $online_inv_confirmed_by; ?> on date:
                <?php echo date("m/d/Y", strtotime($online_inv_confirmed_on)); ?></font>
            <br>
            <form>
                <input type="button" name="customer_onlineinv_btn" id="customer_onlineinv_btn" value="Undo"
                    onclick="customer_onlineinv_confirmed_undo(0, <?php echo $trans_rec_id; ?>, <?php echo $compid; ?>, <?php echo $warehouseid; ?>)">
            </form>
            <?php
				}
				?>

        </td>
    </tr>

</table>
<?php

}
?>