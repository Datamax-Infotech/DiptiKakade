	<div id="table_loop_paidbubble_noinv">

	    Loading .....<img src='images/wait_animated.gif' />

	</div>

	<LINK rel='stylesheet' type='text/css' href='one_style_mrg.css'>



	<!-- To set the No Invoice-->

	<?php


	require("../mainfunctions/database.php");
	require("../mainfunctions/general-functions.php");



	db();



	if (isset($_REQUEST["updatenoinvoice"])) {

		if ($_REQUEST["noinvoice"] == "yes") {

			$strQuery = "UPDATE loop_transaction_buyer SET no_invoice=1, no_invoice_flgupdate_by = '" . $_COOKIE['userinitials'] . "' , no_invoice_flgupdate_dt = '" . Date('Y-m-d H:i:s') . "' WHERE id=" . $_REQUEST["rec_id"];
		} else {

			$strQuery = "UPDATE loop_transaction_buyer SET no_invoice=0, no_invoice_flgupdate_by = '' , no_invoice_flgupdate_dt = '' WHERE id=" . $_REQUEST["rec_id"];
		}

		$r = db_query($strQuery);



		$msg_trans = "System generated log - ";

		if ($_REQUEST["noinvoice"] == "yes") {

			$msg_trans .= "Action : 'Mark as no invoice'.";
		} else {

			$msg_trans .= "Action : 'UnMark as no invoice'";
		}

		$msg_trans .= "<br>" . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "<br>";



		$query1 = db_query('Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) select ?, ?, ?, ?, ?', array("i", "s", "i", "s", "s"), array($_REQUEST["warehouse_id"], 'Supplier', $_REQUEST["rec_id"], str_replace("'", "\'", $msg_trans), $_COOKIE['employeeid']));



		send_transactionlog_email($_REQUEST["warehouse_id"], $_REQUEST["rec_id"], "Supplier", "buyer_view");



	?>

	<script>
parent.getlatest_notes(<?php echo $_REQUEST["rec_id"] ?>, <?php echo $_REQUEST["warehouse_id"]; ?>);
	</script>

	<?php



	}



	$noinv_val = 0;

	$sql_noinv = "SELECT no_invoice FROM loop_transaction_buyer WHERE id = " . $_REQUEST['rec_id'];

	$rec_noinv = db_query($sql_noinv);

	while ($rec_noinvrow = array_shift($rec_noinv)) {

		$noinv_val = $rec_noinvrow["no_invoice"];



	?>

	<table>
	    <tr>
	        <td>

	            <?php

					if ($noinv_val == 0) { ?>

	            <form METHOD="POST" action="loop_paidbubble_noinv.php">

	                <input type="hidden" name="rec_id" value="<?php echo $_REQUEST['rec_id']; ?>">

	                <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST['warehouse_id']; ?>">

	                <input type="hidden" name="noinvoice" value="yes">



	                <input type="submit" style="cursor:pointer;" id="updatenoinvoice" name="updatenoinvoice"
	                    value="Mark as No Invoice" />

	            </form>
	        </td>

	        <?php } else { ?>

	        <td>
	            <form METHOD="POST" action="loop_paidbubble_noinv.php">

	                <input type="hidden" name="rec_id" value="<?php echo $_REQUEST['rec_id']; ?>">

	                <input type="hidden" name="warehouse_id" value="<?php echo $_REQUEST['warehouse_id']; ?>">

	                <input type="hidden" name="noinvoice" value="no">



	                <input type="submit" style="cursor:pointer;" id="updatenoinvoice" name="updatenoinvoice"
	                    value="UnMark as No Invoice" />

	            </form>

	            <?php } ?>

	        </td>
	    </tr>

	</table>

	<?php } ?>

	<!-- To set the No Invoice-->



	<script>
document.getElementById("table_loop_paidbubble_noinv").style.display = "none";
	</script>