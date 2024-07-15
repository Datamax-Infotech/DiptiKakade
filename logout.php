<?php

session_start();
if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {

	$_SESSION['loginid'] = 0;
	$_SESSION['companyid'] = 0;
} else {
	echo "<script type=\"text/javascript\">";
	echo "window.location.href=" . "\";";
	echo "</script>";
	echo "<noscript>";
	echo "<meta http-equiv=\"refresh\" content=\"0;url=" . "\" />";
	echo "</noscript>";
	exit;
}