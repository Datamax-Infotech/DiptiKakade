<?php
ini_set("display_errors", "1");

error_reporting(E_ALL);

?>

<style type="text/css">
.main_data_css {
    margin: 0 auto;
    width: 100%;
    height: auto;
    clear: both !important;
    padding-top: 35px;
    margin-left: 10px;
    margin-right: 10px;
}

.black_overlay {
    display: none;
    position: absolute;
    top: 0%;
    left: 0%;
    width: 100%;
    height: 100%;
    background-color: gray;
    z-index: 1001;
    -moz-opacity: 0.8;
    opacity: .80;
    filter: alpha(opacity=80);
}

.white_content {
    display: none;
    position: absolute;
    top: 5%;
    left: 10%;
    width: 60%;
    height: 90%;
    padding: 16px;
    border: 1px solid gray;
    background-color: white;
    z-index: 1002;
    overflow: auto;
}
</style>
<title>UCBZW Share Review Report</title>
<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

db();
?>

<?php include("inc/header.php"); ?>
<br><br>
<div class="main_data_css">
    <?php

    $rec_found = "no";
    $user_qry = "SELECT id from loop_employees where level = 2 and initials = '" . $_COOKIE['userinitials'] . "'";
    db();
    $user_res = db_query($user_qry);
    while ($user_row = array_shift($user_res)) {
        $rec_found = "yes";
    }

    $query = "SELECT report_name, report_cache_str, sync_time from reports_cache where report_name = 'report_daily_chart_mgmt_ucbzw_gprofit'";
    $res = db_query($query);
    while ($row = array_shift($res)) {
        echo "<span style='font-size:14pt;'><i>Data last updated: " . timeAgo(date("m/d/Y H:i:s", strtotime($row["sync_time"]))) . " (updated once a day at night time)</i></span>";

        if ($rec_found == "yes") {
            echo "&nbsp;&nbsp;<a href='report_daily_chart_mgmt_ucbzw_gprofit_org.php'>Click here to get latest report output</a>";
            echo "&nbsp;&nbsp;OR&nbsp;&nbsp;<a href='cron_save_reports_cache_ucbzw_gprofit.php?showrep=yes'>Re-Run the Cron Job</a>";
        }

        echo $row["report_cache_str"];
    }

    ?>
</div>