<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

foreach ($_REQUEST["quote_id"] as $key => $value) {


    $strQuery = "UPDATE quote SET qstatus='" . $_REQUEST["quote_status"][$key] . "' WHERE ID=" . $value;


    db_b2b();
    db_query($strQuery);
}

redirect($_SERVER['HTTP_REFERER']);