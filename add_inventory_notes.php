<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();

//echo $_REQUEST["message"];
//feed($_REQUEST["message"],$_COOKIE["employeeid"],0);
$qry = "INSERT INTO loop_inventory_notes SET notes = ?";
$res_newtrans = db_query($qry, array("s"), array($_REQUEST["notes"]));

redirect("dashboardnew.php?show=inventory_new");