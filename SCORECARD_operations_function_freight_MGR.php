<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


db();
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <title>SCORECARD: Freight Department | UsedCardboardBoxes</title>
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

    table.datatable {
        border-collapse: collapse;
        background: #FFF;
        width: 30%;
    }

    /*table thead {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background: #FFF;
  display: table;
  table-layout: fixed;
  border: solid 1px #000;
}*/
    table.datatable tbody {
        margin-top: 24px;
    }

    table.datatable {
        border: 1px solid white;
    }

    table.datatable tr td,
    table.datatable tr th {
        height: 20px;
        border: 1px solid white;
        padding-left: 5px;
    }

    table.datatable tr:nth-child(even) td {
        background-color: #e4e4e4;
    }

    table.datatable tr:nth-child(odd) td {
        background-color: #F5F5F5;
    }
    </style>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
    <SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
    <script LANGUAGE="JavaScript">
    document.write(getCalendarStyles());
    </script>
    <script LANGUAGE="JavaScript">
    var cal2xx = new CalendarPopup("listdiv");
    cal2xx.showNavigationDropdowns();
    var cal3xx = new CalendarPopup("listdiv");
    cal3xx.showNavigationDropdowns();
    </script>
    <style type="text/css">
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
        height: 70%;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
    }

    .white_content_details {
        display: none;
        position: absolute;
        top: 0%;
        left: 10%;
        width: 50%;
        height: auto;
        padding: 16px;
        border: 1px solid gray;
        background-color: white;
        z-index: 1002;
        overflow: auto;
        box-shadow: 8px 8px 5px #888888;
    }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
    function f_getPosition(e_elemRef, s_coord) {
        var n_pos = 0,
            n_offset,
            e_elem = e_elemRef;
        while (e_elem) {
            n_offset = e_elem["offset" + s_coord];
            n_pos += n_offset;
            e_elem = e_elem.offsetParent;
        }
        e_elem = e_elemRef;
        while (e_elem != document.body) {
            n_offset = e_elem["scroll" + s_coord];
            if (n_offset && e_elem.style.overflow == 'scroll')
                n_pos -= n_offset;
            e_elem = e_elem.parentNode;
        }
        return n_pos;
    }

    function show_fb_spent_details() {
        var selectobject = document.getElementById("fb_spent_div");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.left = n_left - 200 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
        //
        var date_from_val = document.getElementById("date_from").value;
        var date_to_val = document.getElementById("date_to").value;
        //
        //
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
            }

        }
        xmlhttp.open("GET", "freight_show.php?date_from_val=" + date_from_val + "&date_to_val=" + date_to_val +
            "&showquotedata=1", true);
        xmlhttp.send();
    }

    function show_fb_saved_details() {
        var selectobject = document.getElementById("fb_saved_div");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.left = n_left - 200 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
        //
        var date_from_val = document.getElementById("date_from").value;
        var date_to_val = document.getElementById("date_to").value;
        //
        //
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
            }

        }
        xmlhttp.open("GET", "freight_show.php?date_from_val=" + date_from_val + "&date_to_val=" + date_to_val +
            "&showquotedata=2", true);
        xmlhttp.send();
    }
    //
    function show_dropoff_details() {
        var selectobject = document.getElementById("dropoff_div");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.left = n_left - 200 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
        //
        var date_from_val = document.getElementById("date_from").value;
        var date_to_val = document.getElementById("date_to").value;
        //
        //
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
            }

        }
        xmlhttp.open("GET", "dropoff_show.php?date_from_val=" + date_from_val + "&date_to_val=" + date_to_val +
            "&showquotedata=dropoff", true);
        xmlhttp.send();
    }
    //
    function show_additional_fr_details() {
        var selectobject = document.getElementById("additional_fr_div");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.left = n_left - 200 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
        //
        var date_from_val = document.getElementById("date_from").value;
        var date_to_val = document.getElementById("date_to").value;
        //
        //
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
            }

        }
        xmlhttp.open("GET", "freight_mgr_popup.php?date_from_val=" + date_from_val + "&date_to_val=" + date_to_val +
            "&showquotedata=add_fr", true);
        xmlhttp.send();
    }

    //
    function show_lane_booked_details() {
        var selectobject = document.getElementById("lane_booked_div");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.left = n_left - 200 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
        //
        var date_from_val = document.getElementById("date_from").value;
        var date_to_val = document.getElementById("date_to").value;
        //
        //
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
            }

        }
        xmlhttp.open("GET", "freight_mgr_popup.php?date_from_val=" + date_from_val + "&date_to_val=" + date_to_val +
            "&showquotedata=booked_lane", true);
        xmlhttp.send();
    }

    function show_lane_shipped_details() {
        var selectobject = document.getElementById("lane_shipped_div");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.left = n_left - 200 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
        //
        var date_from_val = document.getElementById("date_from").value;
        var date_to_val = document.getElementById("date_to").value;
        //
        //
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
            }

        }
        xmlhttp.open("GET", "freight_mgr_popup.php?date_from_val=" + date_from_val + "&date_to_val=" + date_to_val +
            "&showquotedata=lane_shipped", true);
        xmlhttp.send();
    }

    function show_lane_delivered_details() {
        var selectobject = document.getElementById("lane_delivered_div");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.left = n_left - 200 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
        //
        var date_from_val = document.getElementById("date_from").value;
        var date_to_val = document.getElementById("date_to").value;
        //
        //
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
            }

        }

        xmlhttp.open("GET", "freight_mgr_popup.php?date_from_val=" + date_from_val + "&date_to_val=" + date_to_val +
            "&showquotedata=lane_delivered", true);
        xmlhttp.send();
    }

    function show_uber_freight_per() {
        var selectobject = document.getElementById("uber_freight_per");
        var n_left = f_getPosition(selectobject, 'Left');
        var n_top = f_getPosition(selectobject, 'Top');
        document.getElementById('light').style.left = n_left - 200 + 'px';
        document.getElementById('light').style.top = n_top + 10 + 'px';
        //
        var date_from_val = document.getElementById("date_from").value;
        var date_to_val = document.getElementById("date_to").value;
        //
        //
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("light").innerHTML =
                    "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center></center><br/>" +
                    xmlhttp.responseText;
                document.getElementById('light').style.display = 'block';
            }

        }
        xmlhttp.open("GET", "freight_mgr_popup.php?date_from_val=" + date_from_val + "&date_to_val=" + date_to_val +
            "&showquotedata=uber_freight_per", true);
        xmlhttp.send();
    }
    </script>

    <LINK rel='stylesheet' type='text/css' href='one_style.css'>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>

<body>

    <?php include("inc/header.php"); ?>

    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">SCORECARD: Freight Department
                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">
                        This scorecard shows the user the data for the freight department in a date range.
                    </span>
                </div>

                <div style="height: 13px;">&nbsp;</div>
            </div>
        </div>
        <div id="light" class="white_content"></div>
        <div id="fade" class="black_overlay"></div>
        <?php

        $time = strtotime(Date('Y-m-d'));
        $st_friday = $time;
        $st_friday_last = date('m/d/Y', strtotime('-6 days', $st_friday));

        $st_thursday_last = Date('m/d/Y');
        //$st_friday_last = '01/01/2019';
        //Find default dates
        $previous_week = strtotime("-1 week +1 day");

        $start_week = strtotime("last sunday midnight", $previous_week);
        $end_week = strtotime("next saturday", $start_week);

        $start_week = date("Y-m-d", $start_week);
        $end_week = date("Y-m-d", $end_week);
        $in_dt_range = "no";
        if ($_REQUEST["date_from"] != "" && $_REQUEST["date_to"] != "") {
            $date_from_val = date("Y-m-d", strtotime($_REQUEST["date_from"]));
            $date_to_val_org = date("Y-m-d", strtotime($_REQUEST["date_to"]));
            $date_to_val = date("Y-m-d", strtotime('+1 day', strtotime($_REQUEST["date_to"])));
            $in_dt_range = "yes";
            //
        } else {
            $in_dt_range = "no";
            $date_from_val = $start_week;
            $date_to_val_org = $end_week;
            $date_to_val = $end_week;
        }
        ?>
        <!--<h3>SCORECARD: Operations Function - Freight MGR</h3>-->
        <form method="post" name="sales_func" action="SCORECARD_operations_function_freight_MGR.php">
            <table border="0">
                <tr>
                    <td>Date Range Selector:</td>
                    <td>
                        From:
                        <input type="text" name="date_from" id="date_from" size="10"
                            value="<?php echo isset($_POST['date_from']) ? $_POST['date_from'] : date("m/d/Y", strtotime($date_from_val)); ?>">
                        <a href="#"
                            onclick="cal2xx.select(document.sales_func.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;"
                            name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
                        <div ID="listdiv"
                            STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                        </div>
                        To:
                        <input type="text" name="date_to" id="date_to" size="10"
                            value="<?php echo isset($_POST['date_to']) ? $_POST['date_to'] : date("m/d/Y", strtotime($date_to_val_org)); ?>">
                        <a href="#"
                            onclick="cal3xx.select(document.sales_func.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;"
                            name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
                    </td>


                    <td>
                        <input type="submit" name="btntool" value="Submit" />
                        <input type="hidden" name="sale_pgpost" id="sale_pgpost" value="" />
                    </td>
                </tr>
            </table>
        </form>
        <table cellSpacing="1" cellPadding="1" border="0" class="datatable">
            <tr>
                <th style="width: 200" class="style17" align="center">
                    <b>Measurables</b>
                </th>
                <th style="width: 190" class="style17" align="center">
                    <b>Number</b>
                </th>
            </tr>

            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    # of Lanes Booked &gt; Freight Budget
                </td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php

                    $fb_cnt_spent = 0;
                    $fb_cnt_saved = 0;

                    $sql = "SELECT sum(loop_transaction_buyer_freightview.booked_delivery_cost) as actual_amount, loop_transaction_buyer.po_freight AS freight_budget from loop_transaction_buyer_freightview INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_transaction_buyer_freightview.trans_rec_id WHERE loop_transaction_buyer.ignore = 0 and loop_transaction_buyer_freightview.dt between '" . $date_from_val . "' AND '" . $date_to_val . "' group by trans_rec_id";

                    //echo $dt_view_qry;
                    db();
                    $dt_view_res = db_query($sql);
                    while ($fb_rec = array_shift($dt_view_res)) {
                        if ($fb_rec["freight_budget"] > 0) {

                            if ($fb_rec["actual_amount"] > $fb_rec["freight_budget"]) {
                                $fb_cnt_spent = $fb_cnt_spent + 1;
                                $freight_budget_spent = isset($freight_budget_spent) + ($fb_rec["freight_budget"] - $fb_rec["actual_amount"]);
                            }
                            if ($fb_rec["actual_amount"] <= $fb_rec["freight_budget"]) {
                                $fb_cnt_saved = $fb_cnt_saved + 1;
                                $freight_budget_saved = isset($freight_budget_saved) + ($fb_rec["freight_budget"] - $fb_rec["actual_amount"]);
                            }
                            $freight_budget = isset($freight_budget) + $fb_rec["freight_budget"];
                            $total_freight_actual_spent = isset($total_freight_actual_spent) + $fb_rec["actual_amount"] + $fb_rec["tender_lane_additional_freight_costs"];
                        }
                    }
                    ?>

                    <a href='#' id='fb_spent_div' onclick="show_fb_spent_details(); return false;">
                        <?php echo "" . $fb_cnt_spent; ?>
                    </a>
                </td>
            </tr>

            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    Total Amount Spent Over Freight Budgets on Lanes Booked </td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php //echo $freight_rec["total_sum"];
                    if (isset($freight_budget_spent) < 0) {
                        echo "<span style='color:#FF0000'>";
                    } else {
                        echo "<span style='color:#000000'>";
                    }
                    $freight_budget_spent = $freight_budget_spent ?? 0;
                    echo "$" . number_format($freight_budget_spent, 2) . "</span>";
                    ?>
                </td>
            </tr>

            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left"># of Lanes Booked &lt;= Freight
                    Budget</td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <a href='#' id='fb_saved_div' onclick="show_fb_saved_details(); return false;">
                        <?php echo $fb_cnt_saved; ?>
                    </a>
                </td>
            </tr>

            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    Total Amount Saved Under Freight Budgets on Lanes Booked</td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php //echo $freight_rec["total_sum"]; 
                    if (isset($freight_budget_saved) > 0) {
                        echo "<span style='color:#2BB004'>";
                    } else {
                        echo "<span style='color:#000000'>";
                    }
                    $freight_budget_saved = $freight_budget_saved ?? 0;
                    echo "$" . number_format($freight_budget_saved, 2) . "</span>";
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    # of Trucks that "Dropped Off"</td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php

                    $droppedoff_qry = "Select * From loop_transaction_buyer_truck_felloff INNER JOIN loop_transaction_buyer on loop_transaction_buyer.id=loop_transaction_buyer_truck_felloff.trans_rec_id where `ignore` = 0 and (customer_flg=1 or customer_flg=2) and (email_sendon >='" . $date_from_val . "') AND (email_sendon <= '" . $date_to_val . " 23:59:59')";

                    $droppedoff_res = db_query($droppedoff_qry);
                    $droppedoff_num = tep_db_num_rows($droppedoff_res);
                    ?>
                    <a href='#' id='dropoff_div' onclick="show_dropoff_details(); return false;">
                        <?php

                        echo $droppedoff_num;

                        ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left"># of Instances of Additional
                    Charges (TONU, Detention, etc)
                </td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php

                    $additional_freight_cnt = 0;
                    //$dt_view_qry = "SELECT * FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.ignore = 0 And (transaction_date BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "') GROUP BY loop_transaction_buyer.id";
                    $dt_view_qry = "SELECT sum(loop_transaction_buyer_payments.estimated_cost)as actual_amount, loop_transaction_buyer_payments.id AS A , loop_transaction_buyer.po_freight AS freight_budget from loop_transaction_buyer  
				INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer.id = loop_transaction_buyer_payments.transaction_buyer_id 
				left JOIN files_companies ON loop_transaction_buyer_payments.company_id = files_companies.id  
				left JOIN loop_employees ON loop_transaction_buyer_payments.employee_id=loop_employees.id 
				WHERE loop_transaction_buyer_payments.typeid in (13) and loop_transaction_buyer_payments.date between '" . $date_from_val . "' AND '" . $date_to_val . "' group by transaction_buyer_id";
                    //echo $dt_view_qry;
                    db();
                    $dt_view_res = db_query($dt_view_qry);
                    while ($fb_rec = array_shift($dt_view_res)) {
                        $additional_freight_cnt = $additional_freight_cnt + 1;
                        $additional_freight_total = isset($additional_freight_total) + $fb_rec["actual_amount"];
                    }
                    if ($additional_freight_cnt > 0) {
                    ?>
                    <a href='#' id='additional_fr_div' onclick="show_additional_fr_details(); return false;">
                        <?php
                            echo $additional_freight_cnt;
                            ?>
                    </a>
                    <?php
                    } else {
                        echo $additional_freight_cnt;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    Total Cost of All Additional Charges</td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php //echo $freight_rec["total_sum"]; 
                    $additional_freight_total = isset($additional_freight_total) * -1;
                    //
                    if ($additional_freight_total < 0) {
                        echo "<span style='color:#FF0000'>";
                    } else {
                        echo "<span style='color:#2BB004'>";
                    }
                    echo "$" . number_format($additional_freight_total, 2) . "</span>";
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    Total Lanes Booked</td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php
                    $freight_booked = 0;
                    //$fsql = "SELECT *,loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.ignore = 0  and (tender_lane_ignore = 0 or loop_transaction_buyer.id in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id)) and (ops_delivery_date >='". $date_from_val ."') AND (ops_delivery_date <= '". $date_to_val . " 23:59:59') GROUP BY loop_transaction_buyer.id";
                    $fsql = "SELECT * FROM loop_transaction_buyer_freightview INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_transaction_buyer_freightview.trans_rec_id WHERE loop_transaction_buyer.ignore = 0  and (dt >='" . $date_from_val . "') AND (dt <= '" . $date_to_val . " 23:59:59') GROUP BY loop_transaction_buyer.id";
                    db();
                    $dt_view_new = db_query($fsql);

                    $freight_booked = tep_db_num_rows($dt_view_new);

                    if ($freight_booked > 0) {
                    ?>
                    <a href='#' id='lane_booked_div' onclick="show_lane_booked_details(); return false;">
                        <?php
                            echo $freight_booked;
                            ?>
                    </a>
                    <?php
                    } else {
                        echo $freight_booked;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    Total Lanes Shipped (Picked Up)</td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php
                    $date_from_val1 = date("m/d/Y", strtotime($date_from_val));
                    $date_to_val1 = date("m/d/Y", strtotime($date_to_val));

                    $ship_qry = "SELECT * FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id WHERE loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.ignore = 0 and (STR_TO_DATE(loop_bol_files.bol_shipped_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "  23:59:59')";
                    //echo $ship_qry;
                    //$ship_qry="select * from loop_transaction_buyer left join loop_bol_files on loop_transaction_buyer.id=loop_bol_files.trans_rec_id  where loop_transaction_buyer.shipped = '1' and loop_bol_files.bol_shipped = '1' and (STR_TO_DATE(bol_shipped_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "  23:59:59')";
                    //echo $ship_qry;
                    db();
                    $ship_res = db_query($ship_qry);

                    $freight_shipped = tep_db_num_rows($ship_res);

                    if ($freight_shipped > 0) {
                    ?>
                    <a href='#' id='lane_shipped_div' onclick="show_lane_shipped_details(); return false;">
                        <?php
                            echo $freight_shipped;
                            ?>
                    </a>
                    <?php
                    } else {
                        echo $freight_shipped;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    Total Lanes Delivered</td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php
                    $so_view_qry = "SELECT * FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                    $so_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
                    $so_view_qry .= "WHERE loop_bol_files.bol_shipment_received = 1 and bol_create = 1 and so_entered = 1 and loop_transaction_buyer.shipped = 1 and loop_transaction_buyer.UCBZeroWaste_flg = 0
				and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 and loop_transaction_buyer.good_to_ship = 1 and loop_transaction_buyer.no_invoice = 0 
				And (STR_TO_DATE(loop_bol_files.bol_shipment_received_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "') GROUP BY loop_transaction_buyer.id";

                    //$so_view_qry = "SELECT * from loop_bol_files WHERE bol_shipped = 1 And bol_shipment_received<>0 And (STR_TO_DATE(bol_shipment_received_date, '%m/%d/%Y') BETWEEN '" . $date_from_val . "' AND '" . $date_to_val . "')";
                    //echo $so_view_qry;
                    db();
                    $so_view_res = db_query($so_view_qry);
                    $freight_delivered = tep_db_num_rows($so_view_res);
                    if ($freight_delivered > 0) {
                    ?>
                    <a href='#' id='lane_delivered_div' onclick="show_lane_delivered_details(); return false;">
                        <?php
                            echo $freight_delivered;
                            ?>
                    </a>
                    <?php
                    } else {
                        echo $freight_delivered;
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    % of Lanes Booked with Uber Freight
                </td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php
                    $u_freight = 0;
                    $fsql = "SELECT *,loop_transaction_buyer.id AS I FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id WHERE loop_transaction_buyer.ignore = 0  and (tender_lane_ignore = 0 or loop_transaction_buyer.id in (select trans_rec_id from loop_transaction_buyer_freightview group by trans_rec_id)) and (ops_delivery_date >='" . $date_from_val . "') AND (ops_delivery_date <= '" . $date_to_val . " 23:59:59') GROUP BY loop_transaction_buyer.id";
                    //echo $fsql;
                    //
                    db();
                    $dt_view_new = db_query($fsql);
                    while ($fb_row = array_shift($dt_view_new)) {
                        $uf_query = "select trans_rec_id, broker_id from loop_transaction_buyer_freightview where broker_id=1711 and trans_rec_id=" . $fb_row["I"];
                        db();
                        $uf_view_new = db_query($uf_query);
                        while ($uf_row = array_shift($uf_view_new)) {
                            //echo $uf_row["trans_rec_id"];
                            $uf_freight = isset($uf_freight) + 1;
                        }
                    }
                    //$u_freight=tep_db_num_rows($dt_view_new);
                    $percent_uber_freight = (isset($uf_freight) * 100) / $freight_booked;
                    //echo $uf_freight;
                    ?>
                    <!--<a href='#' id='uber_freight_per' onclick="show_uber_freight_per(); return false;">-->
                    <?php
                    echo number_format($percent_uber_freight, 2) . "%";
                    ?>
                    <!--</a>-->

                </td>
            </tr>
            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    Total Freight Budget </td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php
                    //$dt_view_qry = "SELECT tender_lane_additional_freight_costs, ops_delivery_date, booked_delivery_cost, freight_booked_delivery_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_freight,  loop_transaction_buyer.inv_amount AS F FROM loop_transaction_buyer INNER JOIN loop_warehouse ON loop_transaction_buyer.warehouse_id = loop_warehouse.id ";
                    //$dt_view_qry .= "INNER JOIN loop_bol_files ON loop_transaction_buyer.id = loop_bol_files.trans_rec_id ";
                    //$dt_view_qry .= "WHERE loop_bol_files.bol_shipment_received = 1 and loop_bol_files.bol_shipment_followup = 0 and bol_create = 1 and so_entered = 1 and loop_transaction_buyer.shipped = 1 AND inv_entered = 0 and loop_transaction_buyer.Preorder = 0 and loop_transaction_buyer.ignore = 0 GROUP BY loop_transaction_buyer.id ORDER BY loop_transaction_buyer.id";

                    //$dt_view_qry = "SELECT loop_transaction_buyer.po_freight AS freight_budget from loop_transaction_buyer INNER JOIN loop_transaction_buyer_payments ON loop_transaction_buyer.id = loop_transaction_buyer_payments.transaction_buyer_id where  loop_transaction_buyer_payments.typeid in (2,13) and loop_transaction_buyer_payments.date between '" . $date_from_val. "' AND '" . $date_to_val. "' group by transaction_buyer_id";

                    $dt_view_qry = "SELECT loop_transaction_buyer.po_freight AS freight_budget FROM loop_transaction_buyer_freightview INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_transaction_buyer_freightview.trans_rec_id WHERE loop_transaction_buyer.ignore = 0 and (dt >='" . $date_from_val . "') AND (dt <= '" . $date_to_val . " 23:59:59') GROUP BY loop_transaction_buyer.id";
                    //echo $dt_view_qry;
                    //
                    $freight_budget  = 0;
                    db();
                    $f_view_res = db_query($dt_view_qry);
                    while ($f_row = array_shift($f_view_res)) {
                        $freight_budget = $freight_budget + $f_row["freight_budget"];
                    }
                    echo "$" . number_format($freight_budget);
                    //
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    Total Freight Actual Spent</td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php
                    $total_freight_actual_spent = $total_freight_actual_spent ?? 0;
                    echo "$" . number_format($total_freight_actual_spent);
                    //
                    $dt_view_qry = "SELECT sum(loop_transaction_buyer_freightview.booked_delivery_cost) AS booked_delivery_cost FROM loop_transaction_buyer_freightview INNER JOIN loop_transaction_buyer ON loop_transaction_buyer.id = loop_transaction_buyer_freightview.trans_rec_id WHERE loop_transaction_buyer.ignore = 0 and (dt >='" . $date_from_val . "') AND (dt <= '" . $date_to_val . " 23:59:59') GROUP BY trans_rec_id";
                    //echo $dt_view_qry;
                    //
                    $total_freight_actual_spent  = 0;
                    db();
                    $f_view_res = db_query($dt_view_qry);
                    while ($f_row = array_shift($f_view_res)) {
                        $total_freight_actual_spent = $total_freight_actual_spent + $f_row["booked_delivery_cost"];
                    }
                    //echo "$".number_format($total_freight_actual_spent);

                    ?>
                </td>
            </tr>

            <tr>
                <td style="width: 200" bgcolor="#e4e4e4" class="style3" align="left">
                    Total Freight Difference</td>
                <td style="width: 190" bgcolor="#e4e4e4" class="style3" align="center">
                    <?php
                    echo "$" . number_format($freight_budget - $total_freight_actual_spent);
                    ?>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>