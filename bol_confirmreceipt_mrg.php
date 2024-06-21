<?php


require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");


header("Refresh: 300; URL=\"" . huntvalleywarehousepage() . "\""); // redirect in 5 seconds
//require ("inc/header_session.php");

?>

<!DOCTYPE HTML>

<html>

<head>
    <script type="text/javascript">
    <!--
    function confirmationRequest(a, b, c) {
        var answer = confirm("Request Pickup of Trailer #" + a + "?")
        if (answer) {
            window.location = "<?php echo  huntvalleywarehousepage() ?>?action=request&req_id=" + b + "&trailer_no=" +
                a +
                "&dock=" + c;
        } else {
            alert("Request Cancelled")
        }
    }

    function confirmationDelivery(a, b, c) {
        var answer = confirm("Confirm Delivery of Trailer #" + a + " to UCB warehouse?")
        if (answer) {
            window.location = "<?php echo  huntvalleywarehousepage() ?>?action=confirm&conf_id=" + b + "&trailer_no=" +
                a +
                "&dock=" + c;
        } else {
            alert("Request Cancelled")
        }
    }
    //
    -->
    </script>

    <title>McCormick - Dashboard</title>




    <style type="text/css">
    .style7 {
        font-size: x-small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        background-color: #FFCC66;
    }

    .style5 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        text-align: center;
        background-color: #99FF99;
    }

    .style6 {
        text-align: center;
        background-color: #99FF99;
    }

    .style2 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
    }

    .style3 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
    }

    .style8 {
        text-align: left;
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
    }

    .style11 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: center;
    }

    .style10 {
        text-align: left;
    }

    .style12 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: right;
    }

    .style12center {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: center;
    }

    .style12right {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: right;
    }

    .style12left {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        text-align: left;
    }

    .style13 {
        font-family: Arial, Helvetica, sans-serif;
    }

    .style14 {
        font-size: x-small;
    }

    .style15 {
        font-size: x-small;
    }

    .style16 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        background-color: #99FF99;
    }

    .style17 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        color: #333333;
        background-color: #99FF99;
    }

    select,
    input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000000;
        font-weight: normal;
    }
    </style>


</head>

<body>

    <?php

    echo "A";

    $sql3ud = "UPDATE loop_bol_files SET bol_received = '1', bol_received_employee = '" . $_REQUEST['userinitials'] . "',  bol_received_date = '" .  date("m/d/Y H:i:s") . "' WHERE id = " . $_REQUEST["bol_id"];

    db();
    $result3ud = db_query($sql3ud);


    //$sql3ud = "UPDATE loop_transaction SET cp_notes = 'Delivery Confirmed via Warehouse Dashboard', cp_employee = 'UCB-HV', cp_date = '".date("m/d/Y")."' WHERE id = ". $_REQUEST["conf_id"];
    //$result3ud = db_query($sql3ud,db() );

    echo "B";
    echo "<br>Location: " . $_REQUEST["location"];
    echo "<br>initials: " . $_REQUEST["userinitials"];
    echo "<br>ID: " . $_REQUEST["bol_id"];

    /*
if ($_REQUEST["location"]=="loops") {
redirect( 'http://loops.usedcardboardboxes.com/search_results.php?warehouse_id='.$_REQUEST["warehouse_id"].'&rec_type=Supplier&proc=View&searchcrit=&id='.$_REQUEST["warehouse_id"].'&rec_id='.$_REQUEST["rec_id"].'&display=buyer_ship' ) ;
}
if ($_REQUEST["location"]=="warehouse") {
redirect($_SERVER['HTTP_REFERER']);
}*/

    redirect($_SERVER['HTTP_REFERER']);

    echo "C";

    ?>

</body>

</html>