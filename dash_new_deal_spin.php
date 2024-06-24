<?php

session_start();
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>New Deal Spin</title>
    <style type="text/css">
    span.infotxt:hover {
        text-decoration: none;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt span {
        position: absolute;
        left: -9999px;
        margin: 20px 0 0 0px;
        padding: 3px 3px 3px 3px;
        z-index: 6;
    }

    span.infotxt:hover span {
        left: 12%;
        background: #ffffff;
    }

    span.infotxt span {
        position: absolute;
        left: -9999px;
        margin: 1px 0 0 0px;
        padding: 0px 3px 3px 3px;
        border-style: solid;
        border-color: black;
        border-width: 1px;
    }

    span.infotxt:hover span {
        margin: 1px 0 0 170px;
        background: #ffffff;
        z-index: 6;
    }

    .style12_new1 {
        font-size: small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: left;
    }

    .style12_new_top {
        font-size: small;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #FF9900;
        text-align: center;
    }

    .style12_new_center {
        font-size: small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: center;
    }

    .style12_new2 {
        font-size: small;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
        text-align: right;
    }

    .txtstyle_color {
        font-family: arial;
        font-size: 12;
        height: 16px;
        background: #ABC5DF;
    }

    .header_td_style {
        font-family: arial;
        font-size: 12;
        height: 16px;
        background: #ABC5DF;
    }

    .white_content_search {
        display: none;
        position: absolute;
        padding: 5px;
        border: 1px solid black;
        background-color: #FFF8C6;
        z-index: 1002;
        overflow: auto;
        color: black;
    }

    .black_overlay {
        display: none;
        position: absolute;
    }

    .white_content {
        display: none;
        position: absolute;
        padding: 5px;
        border: 2px solid black;
        background-color: white;
        overflow: auto;
        height: 600px;
        width: 850px;
        z-index: 1002;
        margin: 0px 0 0 0px;
        padding: 10px 10px 10px 10px;
        border-color: black;
        border-width: 2px;
        overflow: auto;
    }
    </style>
    <script>
    function load_div_spin(id) {
        var element = document.getElementById(id); //replace elementId with your element's Id.
        var rect = element.getBoundingClientRect();
        var elementLeft, elementTop; //x and y
        var scrollTop = document.documentElement.scrollTop ?
            document.documentElement.scrollTop : document.body.scrollTop;
        var scrollLeft = document.documentElement.scrollLeft ?
            document.documentElement.scrollLeft : document.body.scrollLeft;
        elementTop = rect.top + scrollTop;
        elementLeft = rect.left + scrollLeft;

        document.getElementById("light").innerHTML = document.getElementById(id).innerHTML;
        document.getElementById('light').style.display = 'block';
        document.getElementById('fade').style.display = 'block';

        document.getElementById('light').style.left = '100px';
        document.getElementById('light').style.top = elementTop + 100 + 'px';
    }


    function close_div() {
        document.getElementById('light').style.display = 'none';
    }
    </script>
</head>

<body>
    <div id="light" class="white_content"></div>
    <div id="fade" class="black_overlay"></div>
    <table>
        <tr>
            <td width="200" bgColor='#ABC5DF'><strong><u>Employee</u></strong></td>
            <?php

            db();
            $initials = $_REQUEST["initial"];
            if ($_REQUEST["dashboardview"] != "") {
                $dashboard_view = $_REQUEST["dashboardview"];
            } else {
                $dashboard_view = "Rescue";
            }

            for ($newdeal_month_s = 11; $newdeal_month_s > 0; $newdeal_month_s--) {
                $month_arr_s[] = date("m/1/Y", strtotime(date('m/01/Y') . " -$newdeal_month_s months"));
            ?>
            <td bgColor='#ABC5DF' width="80"><strong><u>
                        <center><?php echo date("m/Y", strtotime(date('Y-m-01') . " -$newdeal_month_s months")); ?>
                        </center>
                    </u></strong></td>
            <?php }    ?>
            <?php
            $month_arr_s[] = date("m/1/Y");
            ?>
            <td bgColor='#ABC5DF' width="80"><strong><u>
                        <center><?php echo date("m/Y", strtotime(date('Y-m-01'))); ?></center>
                    </u></strong></td>
            <td bgColor='#ABC5DF' width="80"><strong><u>
                        <center>Total</center>
                    </u></strong></td>
        </tr>
        <?php

        $tot_cnt_thismonth_s = 0;
        $tot_cnt_lastmonth_s = 0;
        $month_arr_cnt_s = array();
        $tot_cnt_thismonth_all_s = 0;
        $tot_cnt_month_all_s = 0;
        $amt_s_all_less = 0;
        $tot_cnt_month_s1 = 0;
        $tot_cnt_month_s2 = 0;
        $tot_cnt_month_s3 = 0;
        $tot_cnt_month_s4 = 0;
        $tot_cnt_month_s5 = 0;
        $tot_cnt_month_s6 = 0;
        $tot_cnt_month_s7 = 0;
        $tot_cnt_month_s8 = 0;
        $tot_cnt_month_s9 = 0;
        $tot_cnt_month_s10 = 0;
        $tot_cnt_month_s11 = 0;
        $tot_cnt_month_s12 = 0;
        $lisofdetails_s1 = "";
        $lisofdetails_s2 = "";
        $lisofdetails_s3 = "";
        $lisofdetails_s4 = "";
        $lisofdetails_s5 = "";
        $lisofdetails_s6 = "";
        $lisofdetails_s7 = "";
        $lisofdetails_s8 = "";
        $lisofdetails_s9 = "";
        $lisofdetails_s10 = "";
        $lisofdetails_s11 = "";
        $lisofdetails_s12 = "";
        $lisofdetails_s13 = "";
        $lisofdetails_s14 = "";
        $lisofdetails_s15 = "";

        $lisofdetails_s1 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s1 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s2 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s2 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s3 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s3 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s4 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s4 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s5 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s5 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s6 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s6 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s7 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s7 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s8 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s8 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s9 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s9 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s10 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s10 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s11 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s11 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s12 = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s12 .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        //
        $lisofdetails_s1_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s1_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s2_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s2_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s3_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s3_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s4_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s4_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s5_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s5_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s6_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s6_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s7_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s7_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s8_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s8_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s9_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s9_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s10_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s10_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s11_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s11_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails_s12_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails_s12_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
        //
        //
        $lisofdetails1all_last_s = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails1all_last_s .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        $lisofdetails1all_last_s_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
        $lisofdetails1all_last_s_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

        //if ($dashboard_view == "Operations" || $dashboard_view == "Executive" ){
        if ($dashboard_view == "Operations" || $dashboard_view == "Executive" || $dashboard_view == "Rescue") {
            $sql = "SELECT * FROM loop_employees WHERE status='Active' AND dashboard_view= 'Rescue'";
        } else {
            //$sql = "SELECT * FROM loop_employees WHERE initials = '" . $initials . "' and dashboard_view= 'Rescue'";
            $sql = "SELECT * FROM loop_employees WHERE initials = '" . $initials . "'";
        }
        //echo $sql;
        db();
        $result = db_query($sql);
        while ($rowemp = array_shift($result)) {
            $initials = $rowemp["initials"];
            $total_bonus_all = 0;
        ?>
        <tr>
            <td bgColor='#E4EAEB'><?php echo $rowemp["name"]; ?></td>

            <?php

                $tot_cnt_month_s = 0;
                $tot_cnt_month_all_s = 0;
                $amt_s_all_less = 0;
                $amt_s_all = 0;

                $lisofdetails1all_s = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                $lisofdetails1all_s .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

                $lisofdetails1all_s_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                $lisofdetails1all_s_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";

                foreach ($month_arr_s as $tmp_mpnth) {
                    $tot_cnt_thismonth_s = 0;
                    $total_bonus = 0;
                    $tot_cnt_month_s = $tot_cnt_month_s + 1;

                    $tmpnewdt = date("m/t/Y", strtotime(date('Y', strtotime($tmp_mpnth)) . "-" . date('m', strtotime($tmp_mpnth)) . "-01"));

                    $lisofdetails_s = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                    $lisofdetails_s .= "<tr><td class='txtstyle_color' colspan=3><b>New Deals >= $2,000</b></td></tr>";
                    $lisofdetails_s .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
                    //
                    $lisofdetails_s_less = "<span style='width:800px;'><table cellSpacing='1' cellPadding='1' border='0' width='780'>";
                    $lisofdetails_s_less .= "<tr><td class='txtstyle_color' colspan=3><b>New Deals < $2,000 (Not Part of Spin Calculation)</b></td></tr>";
                    $lisofdetails_s_less .= "<tr><td class='txtstyle_color'>LOOP ID</td><td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>PO Amount</td></tr>";
                    //

                    $dt_view_qry = "SELECT distinct loop_transaction.id, loop_transaction.Total_revenue, loop_transaction.sort_entered, loop_transaction.warehouse_id, loop_warehouse.b2bid, loop_warehouse.warehouse_name FROM loop_transaction INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction.warehouse_id where `ignore` = 0 and sort_entered = 1 and `Total_revenue` != 0 and  `employee` = '$initials' and (loop_transaction.pa_date between '" . $tmp_mpnth . "' and '" . $tmpnewdt . "') order by `Total_revenue` DESC";
                    $amt_s = 0;
                    $first_rec_cnt_s = 0;
                    $first_rec_cnt_spin = 0;
                    $amt_s_less = 0;
                    //echo $dt_view_qry;

                    db();
                    $dt_view_res = db_query($dt_view_qry);
                    while ($dt_view_row = array_shift($dt_view_res)) {

                        if ($dt_view_row["Total_revenue"] > 0) {
                            $inv_amount = $dt_view_row["Total_revenue"];
                        }

                        $fdq = "SELECT id AS I FROM loop_transaction WHERE warehouse_id = " . $dt_view_row["warehouse_id"] . " and `ignore` = 0 and Total_revenue != 0 and employee = '$initials' ORDER BY I ASC LIMIT 0,1";
                        db();
                        $fd_res = db_query($fdq);
                        $first_deal_s = 0;
                        while ($fd_row = array_shift($fd_res)) {
                            if ($fd_row["I"] == $dt_view_row["id"]) {
                                $first_deal_s = 1;
                                break;
                            }
                        }
                        //


                        if ($first_deal_s == 1) {
                            //
                            if (isset($inv_amount) >= 2000) {
                                //$amt_s = $amt_s + $dt_view_row["SUMPO"];
                                $first_rec_cnt_spin = $first_rec_cnt_spin + 1;
                            }
                            $tot_onroad_s = isset($tot_onroad_s) + isset($inv_amount);
                            $first_rec_cnt_s = $first_rec_cnt_s + 1;
                            $tot_cnt_thismonth_s = $tot_cnt_thismonth_s + 1;
                            $tot_cnt_thismonth_all_s = $tot_cnt_thismonth_all_s + 1;
                            $tot_cnt_month_all_s = $tot_cnt_month_all_s + 1;

                            //$nickname = get_nickname_val($row["warehouse_name"], $row["b2bid"]);
                            $nickname = get_nickname_val($dt_view_row["warehouse_name"], $dt_view_row["b2bid"]);
                            $inv_amount = $inv_amount ?? 0;
                            if (isset($inv_amount) >= 2000) {
                                $lisofdetails_s .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";

                                $lisofdetails1all_s .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";

                                $lisofdetails1all_last_s .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                //
                                $amt_s = $amt_s + $inv_amount;
                                $amt_s_all = $amt_s_all + $inv_amount;
                                $amt_s_lastall = isset($amt_s_lastall) + $inv_amount;
                            } else {
                                $lisofdetails_s_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                $lisofdetails1all_s_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";

                                $lisofdetails1all_last_s_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";

                                //
                                $amt_s_less = $amt_s_less + $inv_amount;
                                $amt_s_all_less = $amt_s_all_less + $inv_amount;
                                $amt_s_lastall_less = isset($amt_s_lastall_less) + $inv_amount;
                            }

                            if ($tot_cnt_month_s == 1) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s1 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount1 = isset($month_total_amount1) + $inv_amount;
                                } else {
                                    $lisofdetails_s1_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount1_less = isset($month_total_amount1_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 2) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s2 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount2 = isset($month_total_amount2) + $inv_amount;
                                } else {
                                    $lisofdetails_s2_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount2_less = isset($month_total_amount2_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 3) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s3 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount3 = isset($month_total_amount3) + $inv_amount;
                                } else {
                                    $lisofdetails_s3_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount3_less = isset($month_total_amount3_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 4) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s4 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount4 = isset($month_total_amount4) + $inv_amount;
                                } else {
                                    $lisofdetails_s4_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount4_less = isset($month_total_amount4_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 5) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s5 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount5 = isset($month_total_amount5) + $inv_amount;
                                } else {
                                    $lisofdetails_s5_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    //
                                    $month_total_amount5_less = isset($month_total_amount5_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 6) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s6 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount6 = isset($month_total_amount6) + $inv_amount;
                                } else {
                                    $lisofdetails_s6_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount6_less = isset($month_total_amount6_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 7) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s7 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount7 = isset($month_total_amount7) + $inv_amount;
                                } else {
                                    $lisofdetails_s7_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount7_less = isset($month_total_amount7_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 8) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s8 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount8 = isset($month_total_amount8) + $inv_amount;
                                } else {
                                    $lisofdetails_s8_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount8_less = isset($month_total_amount8_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 9) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s9 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount9 = isset($month_total_amount9) + $inv_amount;
                                } else {
                                    $lisofdetails_s9_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount9_less = isset($month_total_amount9_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 10) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s10 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount10 = isset($month_total_amount10) + $inv_amount;
                                } else {
                                    $lisofdetails_s10_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount10_less = isset($month_total_amount10_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 11) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s11 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount11 = isset($month_total_amount11) + $inv_amount;
                                } else {
                                    $lisofdetails_s11_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount11_less = isset($month_total_amount11_less) + $inv_amount;
                                }
                            }
                            if ($tot_cnt_month_s == 12) {
                                if ($inv_amount >= 2000) {
                                    $lisofdetails_s12 .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount12 = isset($month_total_amount12) + $inv_amount;
                                } else {
                                    $lisofdetails_s12_less .= "<tr><td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $dt_view_row["b2bid"] . "&show=transactions&warehouse_id=" . $dt_view_row["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $dt_view_row["warehouse_id"] . "&rec_id=" . $dt_view_row["id"] . "&display=buyer_payment'>" . $dt_view_row["id"] . "</a></td><td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format($inv_amount, 0) . "</td></tr>";
                                    $month_total_amount12_less = isset($month_total_amount12_less) + $inv_amount;
                                }
                            }
                        }
                    }

                    $bonus = ($first_rec_cnt_spin - $first_rec_cnt_spin % 2) / 2;
                    //
                    if ($amt_s > 0) {
                        $lisofdetails_s .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s, 0) . "</td></tr>";
                    }
                    if ($amt_s_less > 0) {
                        //
                        $lisofdetails_s_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_less, 0) . "</td></tr>";
                    }
                    $lisofdetails_s .= "</table></span>";
                    $lisofdetails_s_less .= "</table></span>";
                    $unqid = isset($unqid) + 1;
                    //
                    $total_bonus_all = $bonus + $total_bonus_all;
                    $total_bonus = $bonus + $total_bonus;
                    //
                    echo "<td bgColor='#E4EAEB' align='right'><a href='#' onclick='load_div_spin(" . $unqid . $rowemp["id"] . "); return false;'>" . $bonus . "&nbsp;&nbsp;<strong>(" . $first_rec_cnt_s . ")</strong></font></a>"; //$first_rec_cnt_s
                    if ($amt_s <= 0) {
                        $lisofdetails_s = "";
                    }
                    if ($amt_s_less <= 0) {
                        $lisofdetails_s_less = "";
                    }
                    echo "<span id='" . $unqid . $rowemp["id"] . "' style='display:none;'><a href='#' onclick='close_div(); return false;'>Close</a>" . $lisofdetails_s . "<br>" . $lisofdetails_s_less . "</span></td>";

                    if ($tot_cnt_month_s == 1) {
                        $tot_cnt_month_s1 = $tot_cnt_month_s1 + $tot_cnt_thismonth_s;
                        $total_month_bonus1 = $total_bonus + isset($total_month_bonus1);
                    }
                    if ($tot_cnt_month_s == 2) {
                        $tot_cnt_month_s2 = $tot_cnt_month_s2 + $tot_cnt_thismonth_s;
                        $total_month_bonus2 = $total_bonus + isset($total_month_bonus2);
                    }
                    if ($tot_cnt_month_s == 3) {
                        $tot_cnt_month_s3 = $tot_cnt_month_s3 + $tot_cnt_thismonth_s;
                        $total_month_bonus3 = $total_bonus + isset($total_month_bonus3);
                    }
                    if ($tot_cnt_month_s == 4) {
                        $tot_cnt_month_s4 = $tot_cnt_month_s4 + $tot_cnt_thismonth_s;
                        $total_month_bonus4 = $total_bonus + isset($total_month_bonus4);
                    }
                    if ($tot_cnt_month_s == 5) {
                        $tot_cnt_month_s5 = $tot_cnt_month_s5 + $tot_cnt_thismonth_s;
                        $total_month_bonus5 = $total_bonus + isset($total_month_bonus5);
                    }
                    if ($tot_cnt_month_s == 6) {
                        $tot_cnt_month_s6 = $tot_cnt_month_s6 + $tot_cnt_thismonth_s;
                        $total_month_bonus6 = $total_bonus + isset($total_month_bonus6);
                    }
                    if ($tot_cnt_month_s == 7) {
                        $tot_cnt_month_s7 = $tot_cnt_month_s7 + $tot_cnt_thismonth_s;
                        $total_month_bonus7 = $total_bonus + isset($total_month_bonus7);
                    }
                    if ($tot_cnt_month_s == 8) {
                        $tot_cnt_month_s8 = $tot_cnt_month_s8 + $tot_cnt_thismonth_s;
                        $total_month_bonus8 = $total_bonus + isset($total_month_bonus8);
                    }
                    if ($tot_cnt_month_s == 9) {
                        $tot_cnt_month_s9 = $tot_cnt_month_s9 + $tot_cnt_thismonth_s;
                        $total_month_bonus9 = $total_bonus + isset($total_month_bonus9);
                    }
                    if ($tot_cnt_month_s == 10) {
                        $tot_cnt_month_s10 = $tot_cnt_month_s10 + $tot_cnt_thismonth_s;
                        $total_month_bonus10 = $total_bonus + isset($total_month_bonus10);
                    }
                    if ($tot_cnt_month_s == 11) {
                        $tot_cnt_month_s11 = $tot_cnt_month_s11 + $tot_cnt_thismonth_s;
                        $total_month_bonus11 = $total_bonus + isset($total_month_bonus11);
                    }
                    if ($tot_cnt_month_s == 12) {
                        $tot_cnt_month_s12 = $tot_cnt_month_s12 + $tot_cnt_thismonth_s;
                        $total_month_bonus12 = $total_bonus + isset($total_month_bonus12);
                    }
                }

                if ($amt_s_all > 0) {
                    $lisofdetails1all_s .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_all, 0) . "</td></tr>";
                }
                if ($amt_s_all_less > 0) {
                    //
                    $lisofdetails1all_s_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_all_less, 0) . "</td></tr>";
                }

                $lisofdetails1all_s .= "</table></span>";
                $lisofdetails1all_s_less .= "</table></span>";
                ?>

            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(999888<?php echo $rowemp["id"]; ?>); return false;'><strong><?php echo number_format($total_bonus_all, 0) . "&nbsp;&nbsp;(" . $tot_cnt_month_all_s . ")"; //$tot_cnt_month_all_s 
                                                                                                                                                    ?></strong>
                    </font></a>
                <span id='999888<?php echo $rowemp["id"]; ?>' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails1all_s; ?><br><?php echo $lisofdetails1all_s_less; ?></span>
            </td>

            <?php
        }

            ?>
        </tr>
        <?php

            if ($dashboard_view == "Operations" || $dashboard_view == "Executive" || $dashboard_view == "Rescue") {

                if (isset($month_total_amount1) > 0) {
                    $month_total_amount1 = $month_total_amount1 ?? 0;
                    $lisofdetails_s1 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount1, 0) . "</td></tr>";
                }
                if (isset($month_total_amount1_less) > 0) {
                    $month_total_amount1_less = $month_total_amount1_less ?? 0;
                    $lisofdetails_s1_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount1_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount2) > 0) {
                    $month_total_amount2 = $month_total_amount2 ?? 0;
                    $lisofdetails_s2 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount2, 0) . "</td></tr>";
                }
                if (isset($month_total_amount2_less) > 0) {
                    $month_total_amount2_less = $month_total_amount2_less ?? 0;
                    $lisofdetails_s2_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount2_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount3) > 0) {
                    $month_total_amount3 = $month_total_amount3 ?? 0;
                    $lisofdetails_s3 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount3, 0) . "</td></tr>";
                }
                if (isset($month_total_amount3_less) > 0) {
                    $month_total_amount3_less = $month_total_amount3_less ?? 0;
                    $lisofdetails_s3_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount3_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount4) > 0) {
                    $month_total_amount4 = $month_total_amount4 ?? 0;
                    $lisofdetails_s4 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount4, 0) . "</td></tr>";
                }
                if (isset($month_total_amount4_less) > 0) {
                    $month_total_amount4_less = $month_total_amount4_less ?? 0;
                    $lisofdetails_s4_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount4_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount5) > 0) {
                    $month_total_amount5 = $month_total_amount5 ?? 0;
                    $lisofdetails_s5 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount5, 0) . "</td></tr>";
                }
                if (isset($month_total_amount5_less) > 0) {
                    $month_total_amount5_less = $month_total_amount5_less ?? 0;
                    $lisofdetails_s5_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount5_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount6) > 0) {
                    $month_total_amount6 = $month_total_amount6 ?? 0;
                    $lisofdetails_s6 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount6, 0) . "</td></tr>";
                }
                if (isset($month_total_amount6_less) > 0) {
                    $month_total_amount6_less = $month_total_amount6_less ?? 0;
                    $lisofdetails_s6_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount6_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount7) > 0) {
                    $month_total_amount7 = $month_total_amount7 ?? 0;
                    $lisofdetails_s7 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount7, 0) . "</td></tr>";
                }
                if (isset($month_total_amount7_less) > 0) {
                    $month_total_amount7_less = $month_total_amount7_less ?? 0;
                    $lisofdetails_s7_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount7_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount8) > 0) {
                    $month_total_amount8 = $month_total_amount8 ?? 0;
                    $lisofdetails_s8 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount8, 0) . "</td></tr>";
                }
                if (isset($month_total_amount8_less) > 0) {
                    $month_total_amount8_less = $month_total_amount8_less ?? 0;
                    $lisofdetails_s8_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount8_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount9) > 0) {
                    $month_total_amount9 = $month_total_amount9 ?? 0;
                    $lisofdetails_s9 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount9, 0) . "</td></tr>";
                }
                if (isset($month_total_amount9_less) > 0) {
                    $month_total_amount9_less = $month_total_amount9_less ?? 0;
                    $lisofdetails_s9_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount9_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount10) > 0) {
                    $month_total_amount10 = $month_total_amount10 ?? 0;
                    $lisofdetails_s10 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount10, 0) . "</td></tr>";
                }
                if (isset($month_total_amount10_less) > 0) {
                    $month_total_amount10_less = $month_total_amount10_less ?? 0;
                    $lisofdetails_s10_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount10_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount11) > 0) {
                    $month_total_amount11 = $month_total_amount11 ?? 0;
                    $lisofdetails_s11 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount11, 0) . "</td></tr>";
                }
                if (isset($month_total_amount11_less) > 0) {
                    $month_total_amount11_less = $month_total_amount11_less ?? 0;
                    $lisofdetails_s11_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount11_less, 0) . "</td></tr>";
                }
                if (isset($month_total_amount12) > 0) {
                    $month_total_amount12 = $month_total_amount12 ?? 0;
                    $lisofdetails_s12 .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount12, 0) . "</td></tr>";
                }
                if (isset($month_total_amount12_less) > 0) {
                    $month_total_amount12_less = $month_total_amount12_less ?? 0;
                    $lisofdetails_s12_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($month_total_amount12_less, 0) . "</td></tr>";
                }

                $lisofdetails_s1 .= "</table></span>";
                $lisofdetails_s2 .= "</table></span>";
                $lisofdetails_s3 .= "</table></span>";
                $lisofdetails_s4 .= "</table></span>";
                $lisofdetails_s5 .= "</table></span>";
                $lisofdetails_s6 .= "</table></span>";
                $lisofdetails_s7 .= "</table></span>";
                $lisofdetails_s8 .= "</table></span>";
                $lisofdetails_s9 .= "</table></span>";
                $lisofdetails_s10 .= "</table></span>";
                $lisofdetails_s11 .= "</table></span>";
                $lisofdetails_s12 .= "</table></span>";
                //
                $lisofdetails_s1_less .= "</table></span>";
                $lisofdetails_s2_less .= "</table></span>";
                $lisofdetails_s3_less .= "</table></span>";
                $lisofdetails_s4_less .= "</table></span>";
                $lisofdetails_s5_less .= "</table></span>";
                $lisofdetails_s6_less .= "</table></span>";
                $lisofdetails_s7_less .= "</table></span>";
                $lisofdetails_s8_less .= "</table></span>";
                $lisofdetails_s9_less .= "</table></span>";
                $lisofdetails_s10_less .= "</table></span>";
                $lisofdetails_s11_less .= "</table></span>";
                $lisofdetails_s12_less .= "</table></span>";
                //
            ?>
        <tr>
            <td bgColor='#E4EAEB' align="right"><strong>Total</strong></td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988888); return false;'><strong><?php echo isset($total_month_bonus1) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s1, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988888' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s1; ?><br><?php echo $lisofdetails_s1_less; ?></span>
            </td>

            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988881); return false;'><strong><?php echo isset($total_month_bonus2) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s2, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988881' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s2; ?><br><?php echo $lisofdetails_s2_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988882); return false;'><strong><?php echo isset($total_month_bonus3) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s3, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988882' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s3; ?><br><?php echo $lisofdetails_s3_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988883); return false;'><strong><?php echo isset($total_month_bonus4) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s4, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988883' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s4; ?><br><?php echo $lisofdetails_s4_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988884); return false;'><strong><?php echo isset($total_month_bonus5) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s5, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988884' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s5; ?><br><?php echo $lisofdetails_s5_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988885); return false;'><strong><?php echo isset($total_month_bonus6) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s6, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988885' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s6; ?><br><?php echo $lisofdetails_s6_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988886); return false;'><strong><?php echo isset($total_month_bonus7) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s7, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988886' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s7; ?><br><?php echo $lisofdetails_s7_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988887); return false;'><strong><?php echo isset($total_month_bonus8) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s8, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988887' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s8; ?><br><?php echo $lisofdetails_s8_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988818); return false;'><strong><?php echo isset($total_month_bonus9) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s9, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988818' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s9; ?><br><?php echo $lisofdetails_s9_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988828); return false;'><strong><?php echo isset($total_month_bonus10) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s10, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988828' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s10; ?><br><?php echo $lisofdetails_s10_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988838); return false;'><strong><?php echo isset($total_month_bonus11) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s11, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988838' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s11; ?><br><?php echo $lisofdetails_s11_less; ?></span>
            </td>
            <td bgColor='#E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(988848); return false;'><strong><?php echo isset($total_month_bonus12) . "&nbsp;&nbsp;(" . number_format($tot_cnt_month_s12, 0) . ")"; ?></strong>
                    </font></a>
                <span id='988848' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails_s12; ?><br><?php echo $lisofdetails_s12_less; ?></span><br>
            </td>

            <?php
                    //

                    if (isset($amt_s_lastall) > 0) {
                        $amt_s_lastall = $amt_s_lastall ?? 0;
                        $lisofdetails1all_last_s .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_lastall, 0) . "</td></tr>";
                    }
                    if (isset($amt_s_lastall_less) > 0) {
                        $amt_s_lastall_less = $amt_s_lastall_less ?? 0;
                        $lisofdetails1all_last_s_less .= "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'>$" . number_format($amt_s_lastall_less, 0) . "</td></tr>";
                    }
                    //
                    $lisofdetails1all_last_s .= "</table></span>";
                    $lisofdetails1all_last_s_less .= "</table></span>";
                    //
                    $total_spins_all_month = isset($total_month_bonus1) + isset($total_month_bonus2) + isset($total_month_bonus3) + isset($total_month_bonus4) + isset($total_month_bonus5) + isset($total_month_bonus6) + isset($total_month_bonus7) + isset($total_month_bonus8) + isset($total_month_bonus9) + isset($total_month_bonus10) + isset($total_month_bonus11) + isset($total_month_bonus12);
                    ?>

            <td bgColor='##E4EAEB' align='right'><a href='#'
                    onclick='load_div_spin(997988); return false;'><strong><?php echo $total_spins_all_month . "&nbsp;&nbsp;(" . number_format($tot_cnt_thismonth_all_s, 0) . ")"; ?></strong>
                    </font></a>
                <span id='997988' style='display:none;'><a href='#'
                        onclick='close_div(); return false;'>Close</a><?php echo $lisofdetails1all_last_s; ?><br><?php echo  $lisofdetails1all_last_s_less; ?><br></span>
            </td>
        </tr>
        <?php

            } ?>

    </table>
</body>

</html>