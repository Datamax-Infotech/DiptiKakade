<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";

db();
$getCarrierDtls = db_query("SELECT * FROM loop_freightvendor WHERE id = " . $_REQUEST['DockCarrier']);
$rowCarrierDtls = array_shift($getCarrierDtls);

$carrierName = $rowCarrierDtls['company_name'];
$companyEmail = $rowCarrierDtls['company_email'];

echo $carrierName . "~" . $companyEmail;