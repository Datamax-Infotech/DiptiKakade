<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";
require("inc/functions_mysqli.php");

$eid = $_REQUEST["eid"];
if (isset($_REQUEST["viewin"])) {
    $viewin = $_REQUEST["viewin"];
    $viewin_arr = explode(",", $viewin);
} else {

    $x = "SELECT * from loop_employees WHERE b2b_id = '" . $eid . "'";
    db();
    $viewres = db_query($x);
    $row = array_shift($viewres);
    $tmp_view = $row['views'];
    $tmp_view = str_replace(",31", "", $tmp_view);
    //
    $viewin_arr = explode(",", $tmp_view);
}

$show_number = $_REQUEST["show_number"];

showStatusesDashboard($viewin_arr, $eid, $show_number, "today");