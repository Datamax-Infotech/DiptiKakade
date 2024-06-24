<?php


require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();

function get_dataa(string $url): string
{
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
    return $data;
}

$url = 'https://loops.usedcardboardboxes.com/dashboardnew.php?show=inventory_new_org&no_sess=yes';
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

//echo $data;
$rep_content = $data;
$rep_content = str_replace("\'", "'", $rep_content);
$rep_content = str_replace("'", "\'", $rep_content);

$qry = "Update reports_cache set report_name = 'dash_inventory_new', report_cache_str = '" . $rep_content . "', sync_time = '" . date("Y-m-d H:i:s") . "' where report_name = 'dash_inventory_new'";
//$qry = "Update reports_cache set report_name = 'dash_inventory_new', report_cache_str = '" . $rep_content . "', sync_time = '". date("Y-m-d H:i:s") . "' where report_name = 'dash_inventory_new'";

db();
$rec_ttly_data = db_query($qry);

if ($_REQUEST["showrep"] == "yes") {
    $returnurl = "https://loops.usedcardboardboxes.com/dashboardnew.php?show=inventory_new_org";

    echo "<script type=\"text/javascript\">";
    echo "window.location.href=\"" . $returnurl . "\";";
    echo "</script>";
    echo "<noscript>";
    echo "<meta http-equiv=\"refresh\" content=\"0;url=\"" . $returnurl . "\" />";
    echo "</noscript>";
    exit;
}