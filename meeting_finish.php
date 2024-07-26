<?php


require("../mainfunctions/database.php"); 
require("../mainfunctions/general-functions.php");

$upsql = "UPDATE `meeting_timer` SET `end_time` ='". date('Y-m-d H:i:s') . "', `meeting_flg` = '1' , `meeting_end_by` = '". $_COOKIE['employeeid'] ."' WHERE id='". $_REQUEST["id"] ."'";

db_project_mgmt();
$result = db_query($upsql);