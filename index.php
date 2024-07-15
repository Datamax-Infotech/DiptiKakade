<?php


session_start();

if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=\"dashboard.php" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=dashboard.php" . "\" />";
	echo "</noscript>";
	exit;
}

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();

$_SESSION['pgname'] = "";

$loginchk = "";
if (isset($_REQUEST["txtemail"])) {
	$rec_found = "no";

	if (isset($_REQUEST['redirect']) && $_REQUEST['redirect'] == 'yes') {
		$eml = base64_decode($_REQUEST["txtemail"]);
		$pwd = base64_decode($_REQUEST["txtpassword"]);
	} else {
		$eml = $_REQUEST["txtemail"];
		$pwd = $_REQUEST["txtpassword"];
	}

	$sql = "SELECT loginid, companyid, loginkey FROM supplierdashboard_usermaster WHERE user_name=? and password=? and activate_deactivate = 1";
	$result = db_query($sql, array("s", "s"), array($eml, $pwd));
	while ($myrowsel = array_shift($result)) {
		$rec_found = "yes";

		$warehouse_id = 0;
		$sql1 = "SELECT id FROM loop_warehouse WHERE b2bid=? ";
		$result1 = db_query($sql1, array("i"), array($myrowsel["company_id"]));
		while ($myrowsel1 = array_shift($result1)) {
			$warehouse_id = $myrowsel1["id"];
		}

		session_start();
		$_SESSION['loginid'] = $myrowsel["loginid"];
		$_SESSION['companyid'] = $myrowsel["company_id"];

		/*echo "<script type=\"text/javascript\">";
			echo "window.location.href=\"dashboard.php" . "\";";
			echo "</script>";
			echo "<noscript>";
			echo "<meta http-equiv=\"refresh\" content=\"0;url=dashboard.php" . "\" />";
			echo "</noscript>"; exit;*/
	}

	if ($rec_found == "yes") {
		$machineIP = $_SERVER['REMOTE_ADDR'];
		$perdayNoOfLogin = 1;
		db();
		$selUserDt = db_query("SELECT id, perday_no_of_login FROM water_login_user WHERE user_id = " . $_SESSION['loginid'] . " AND record_date BETWEEN '" . date('Y-m-d') . " 00:00:00' AND '" . date('Y-m-d') . " 23:59:59' AND machine_ip = '" . $machineIP . "'");
		$rowUserDt = array_shift($selUserDt);
		if (!empty($rowUserDt)) {
			$waterUserLoginId = $rowUserDt['id'];
			$perdayNoOfLogin = $rowUserDt['perday_no_of_login'] + 1;
			$updatedDate = date('Y-m-d h:i:s');
			//echo "UPDATE water_login_user SET perday_no_of_login = ".$perdayNoOfLogin.", updated_date = '".$updatedDate."' WHERE id = ".$rowUserDt['id'];
			db();
			db_query("UPDATE water_login_user SET perday_no_of_login = " . $perdayNoOfLogin . ", updated_date = '" . $updatedDate . "' WHERE id = " . $rowUserDt['id']);
		} else {
			$recordDate = date('Y-m-d h:i:s');
			//echo "INSERT INTO water_login_user(user_id, machine_ip, record_date,  perday_no_of_login) VALUES(".$_SESSION['loginid'].", '".$machineIP."', '".$recordDate."', ".$perdayNoOfLogin.")";
			db();
			db_query("INSERT INTO water_login_user(user_id, machine_ip, record_date,  perday_no_of_login) VALUES(" . $_SESSION['loginid'] . ", '" . $machineIP . "', '" . $recordDate . "', " . $perdayNoOfLogin . ")");
			$waterUserLoginId = tep_db_insert_id();
		}

		$_SESSION['waterUserLoginId'] = $waterUserLoginId;

		$browserDetails = $_SERVER['HTTP_USER_AGENT'];
		db();
		db_query("INSERT INTO water_login_user_visit_to(water_login_user_id, machine_ip, browser_details, visit_to, record_date) VALUES(" . $waterUserLoginId . ", '" . $machineIP . "', '" . $browserDetails . "', 'dashboard', '" . date('Y-m-d h:i:s') . "')");


		echo "<script type=\"text/javascript\">";
		echo "window.location.href=\"dashboard.php" . "\";";
		echo "</script>";
		echo "<noscript>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=dashboard.php" . "\" />";
		echo "</noscript>";
		exit;
	}

	if ($rec_found == "no") {
		$loginchk = "1";
	}
}

define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';