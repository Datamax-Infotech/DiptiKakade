<!DOCTYPE HTML>

<html>

<head>
    <title>Order Issue Picture Upload</title>
</head>

<body>

    <?php

	echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";

	// $_GET VARIABLES
	foreach ($_GET as $a => $b) {
		$$a = $b;
	}

	// $_POST VARIABLES
	foreach ($_POST as $a => $b) {
		$$a = $b;
	}
	/*------------------------------------------------
END GLOBALS OFF SUPPORT
------------------------------------------------*/

	echo "<Font Face='arial' size='2'>";

	$sql_debug_mode = 0;

	error_reporting(E_WARNING | E_PARSE);

	//SET THESE VARIABLES TO CUSTOMIZE YOUR PAGE
	$thispage	= "orderissue_picture_save.php"; //SET THIS TO THE NAME OF THIS FILE
	$pagevars	= ""; //INSERT ANY "GET" VARIABLES HERE...

	$allowedit		= "yes"; //SET TO "no" IF YOU WANT TO DISABLE EDITING
	$allowaddnew	= "yes"; // SET TO "no" IF YOU WANT TO DISABLE NEW RECORDS
	$allowview		= "no"; //SET TO "no" IF YOU WANT TO DISABLE VIEWING RECORDS
	$allowdelete	= "yes"; //SET TO "no" IF YOU WANT TO DISABLE DELETING RECORDS

	$addl_select_crit = "order by blength, bwidth, bdepth"; //ADDL CRITERIA FOR SQL STATEMENTS (ADD/UPD/DEL).
	$addl_update_crit = ""; //ADDITIONAL CRITERIA FOR UPDATE STATEMENTS.
	$addl_insert_crit = ""; //ADDITIONAL CRITERIA FOR INSERT STATEMENTS.
	$addl_insert_values = ""; //ADDITIONAL VALUES FOR INSERT STATEMENTS.

	if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
		$addslash = "no";
	} else {
		$addslash = "yes";
	}

	require("../mainfunctions/database.php");
	require("../mainfunctions/general-functions.php");



	$img_qry = "Select * from order_issue_pictures Where trans_id = " . $_REQUEST["rec_id"] . " ORDER by id ASC";
	db();
	$img_res1 = db_query($img_qry);
	if (tep_db_num_rows($img_res1) > 0) {
		$flg = "added";
	} else {
		$flg = "new";
	}
	//

	db();
	$sql_ord = db_query("SELECT virtual_inventory_company_id, virtual_inventory_trans_id FROM loop_transaction_buyer WHERE virtual_inventory_trans_id > 0 and loop_transaction_buyer.id = '" . $_REQUEST["rec_id"] . "'");

	if (tep_db_num_rows($sql_ord) > 0) {

		while ($data_row = array_shift($sql_ord)) {
			$sales_tansid = $data_row["id"];
			$virtual_inventory_company_id = $data_row["virtual_inventory_company_id"];
			$virtual_inventory_trans_id = $data_row["virtual_inventory_trans_id"];
		}
	}
	if (isset($virtual_inventory_company_id) > 0) {


		db();
		$sql_comp = db_query("SELECT loop_warehouse.b2bid FROM loop_warehouse WHERE loop_warehouse.id = '" . isset($virtual_inventory_company_id) . "'");
		while ($com_row = array_shift($sql_comp)) {
			$purchasing_company_id = $com_row["b2bid"];
		}
	}
	//
	$uploadNeed = 1;
	$fileuploaded_flg = "no";

	$sql = "SELECT * FROM tblvariable where variablename = 'upload_file_type_attachment'";
	$filetype = "jpg,jpeg,tif,tiff,png,gif";
	db();
	$result = db_query($sql);
	while ($myrowsel = array_shift($result)) {
		$filetype = $myrowsel["variablevalue"];
	}
	$allow_ext = explode(",", $filetype);

	$filesuploaded = 0;
	if (!empty($_FILES)) {
		foreach ($_FILES['File']['tmp_name'] as $index => $tmpName) {
			if (!empty($_FILES['File']['error'][$index])) {
			} else {
				if (!empty($tmpName) && is_uploaded_file($tmpName)) {
					$ext = pathinfo($_FILES["File"]["name"][$index], PATHINFO_EXTENSION);
					if (in_array(strtolower($ext), $allow_ext)) {
						$fn = mt_rand() . "_" . FixString($_FILES["File"]['name'][$index]);

						move_uploaded_file($tmpName, "orderissuepic/" . $fn); // move to new location perhaps?

						$sql = "INSERT INTO order_issue_pictures ( trans_id, companyid, order_img, added_date_on, img_added_by, virtual_comp_id, virtual_trans_id ) VALUES ('" . $_REQUEST["rec_id"] . "', '" . $_REQUEST["comp_id"] . "', '" . $fn . "', '" . Date('Y-m-d H-2:i:s') . "', '" . $_COOKIE['userinitials'] . "', '" . isset($virtual_inventory_company_id) . "', '" . isset($virtual_inventory_trans_id) . "')";
						//echo $sql;
						db();
						$result = db_query($sql);
						//
						if (empty($result)) {
							$filesuploaded = 1;
						}

						//
					} else {
						echo "<font color=red>" . $_FILES["File"]["name"] . " file not uploaded, this file type is restricted.</font>";
						echo "<script>alert('" . $_FILES["File"]["name"] . " file not uploaded, this file type is restricted.');</script>";
					}
				}
			}
		}
	}
	if ($filesuploaded == 1) {
		if ($flg == "new") {

			db();
			$trans_query = db_query('Update loop_transaction_buyer set order_issue_pictures = 1, order_issue_pic_on= "' . date("m/d/Y H:i:s") . '", order_issue_pic_by="' . $_COOKIE['userinitials'] . '" where id = ?', array("i"), array($_REQUEST["rec_id"]));
			//
			$msg_trans = "System generated log - 'Order Issue Pictures' have been uploaded on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "<br>";

			$query1 = db_query('Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) select ?, ?, ?, ?, ?', array("i", "s", "i", "s", "s"), array($_REQUEST["warehouse_id"], $_REQUEST["rec_type"], $_REQUEST["rec_id"], $msg_trans, $_COOKIE['employeeid']));
		} else {

			$msg_trans = "System generated log - 'Order Issue Pictures' - Additional new images have been uploaded on " . date("m/d/Y H:i:s") . " by " . $_COOKIE['userinitials'] . "<br>";

			$query1 = db_query('Insert into loop_transaction_notes (company_id, rec_type, rec_id, message, employee_id) select ?, ?, ?, ?, ?', array("i", "s", "i", "s", "s"), array($_REQUEST["warehouse_id"], $_REQUEST["rec_type"], $_REQUEST["rec_id"], $msg_trans, $_COOKIE['employeeid']));
		}
		//
	}
	//redirect("viewCompany.php?ID=".$_REQUEST["companyID"]);
	$newurl = $_SERVER['HTTP_REFERER'] . "&editedattach=yes";
	redirect($newurl);

	?>