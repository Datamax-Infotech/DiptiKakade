<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

// set_time_limit(0);  
// ini_set('memory_limit', '-1');

// ini_set("display_errors", "1");
// error_reporting(E_ALL);

db();

$url = 'https://loops.usedcardboardboxes.com/report_daily_chart_mgmt_gprofit.php?no_sess=yes';
$ch = curl_init();
$timeout = 500;
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$data = curl_exec($ch);
curl_close($ch);

echo $data;
$rep_content = $data;


$qry = "Update reports_cache set report_name = 'report_daily_chart_mgmt_gprofit', report_cache_str = '" . str_replace("'", "\'", $rep_content) . "', sync_time = '" . date("Y-m-d H:i:s") . "' where report_name = 'report_daily_chart_mgmt_gprofit'";

db();
$rec_ttly_data = db_query($qry);

if ($_REQUEST["showrep"] == "yes") {
    $returnurl = "report_daily_chart_mgmt_ver2.php";

    echo "<script type=\"text/javascript\">";
    echo "window.location.href=\"" . $returnurl . "\";";
    echo "</script>";
    echo "<noscript>";
    echo "<meta http-equiv=\"refresh\" content=\"0;url=\"" . $returnurl . "\" />";
    echo "</noscript>";
    exit;
}