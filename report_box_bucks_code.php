<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

?>
<!DOCTYPE HTML>

<html>

<head>
    <title>B2C Box Bucks Code Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>

    <style type="text/css">
    .style7 {
        font-size: xx-small;
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

    .style13 {
        font-family: Arial, Helvetica, sans-serif;
    }

    .style14 {
        font-size: x-small;
    }

    .style15 {
        font-size: small;
    }

    .style16 {
        font-family: Arial, Helvetica, sans-serif;
        font-size: x-small;
        background-color: #99FF99;
    }

    .style17 {
        background-color: #99FF99;
    }

    select,
    input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10px;
        color: #000000;
        font-weight: normal;
    }
    </style>


</head>

<body>

    <?php include("inc/header.php"); ?>

    <div class="main_data_css">
        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">B2C Box Bucks Code Report</div>
            &nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                <span class="tooltiptext">This report shows the user all B2C orders which were placed with a box bucks
                    code within a date range.</span>
            </div>
            <div style="height: 13px;">&nbsp;</div>
        </div>

        <?php
        // echo "<LINK rel='stylesheet' type='text/css' href='one_style.css' >";
        ?>
        <BR>

        <form name="rptSearch" action="report_box_bucks_code.php" method="GET">
            <input type="hidden" name="action" value="run">

            <!-- 
<span class="style2">
<a href="index.php">Home</a></span><br>
<br>
-->
            <span class="style13"><span class="style15">

                    <br />
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        Box Bucks Code report
                </span><span class="style14"><span class="style15">




                        <SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
                        <script LANGUAGE="JavaScript">
                        document.write(getCalendarStyles());
                        </script>
                        <script LANGUAGE="JavaScript">
                        var cal1xx = new CalendarPopup("listdiv");
                        cal1xx.showNavigationDropdowns();
                        var cal2xx = new CalendarPopup("listdiv");
                        cal2xx.showNavigationDropdowns();
                        </script>
                        <?php
                        $start_date = isset($_GET["start_date"]) ? strtotime($_GET["start_date"]) : strtotime(date('Y-m-d'));
                        $end_date = isset($_GET["end_date"]) ? strtotime($_GET["end_date"]) : strtotime(date('Y-m-d'));
                        ?>

                        <font face="Arial, Helvetica, sans-serif" color="#333333" size="1"> from: <input type="text"
                                name="start_date" size="11"
                                value="<?php echo (isset($_GET["start_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $start_date) : "" ?>">
                            <a href="#"
                                onclick="cal1xx.select(document.rptSearch.start_date,'anchor1xx','MM/dd/yyyy'); return false;"
                                name="anchor1xx" id="anchor1xx"><img border="0" src="images/calendar.jpg"></a>
                            <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">to: <input type="text"
                                    name="end_date" size="11"
                                    value="<?php echo (isset($_GET["end_date"]) && $_GET["start_date"] != "") ? date('m/d/Y', $end_date) : "" ?>">
                                <a href="#"
                                    onclick="cal1xx.select(document.rptSearch.end_date,'anchor2xx','MM/dd/yyyy'); return false;"
                                    name="anchor2xx" id="anchor2xx"><img border="0" src="images/calendar.jpg"></a>


                                &nbsp; <input type="submit" value="Search">
        </form>
        <div ID="listdiv"
            STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></div>

        <br></span></span></span><br>
        <br />

        <?php

        if (isset($_GET["action"])) {
            if ($_GET["action"] == 'run') {
                $start_date = date('Y-m-d', $start_date);
                $end_date = date('Y-m-d', $end_date);

                if ($start_date > $end_date) {
                    echo "<font size=20>Nice Try, David - You thought I would not catch an error where the start date comes after the end date.</font>";
                }

        ?>

        <table cellSpacing="1" cellPadding="1" width="40%" border="0">
            <tr align="middle">
                <td colSpan="4" class="style7">
                    BOX BUCKS CODE REPORT</td>
            </tr>
            <tr>

                <td style="width: 20%" class="style17">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        BOX BUCKS CODE
                        <a
                            href="report_box_bucks_code.php?action=run&start_date=<?php echo $start_date ?>&end_date=<?php echo $end_date ?>&sortfld=boxbuckrec.title&sort_pre_order=ASC"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>
                        <a
                            href="report_box_bucks_code.php?action=run&start_date=<?php echo $start_date ?>&end_date=<?php echo $end_date ?>&sortfld=boxbuckrec.title&sort_pre_order=DESC"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                    </font>
                </td>
                <td style="width: 10%" class="style17">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        TOTAL ORDERS
                        <a
                            href="report_box_bucks_code.php?action=run&start_date=<?php echo $start_date ?>&end_date=<?php echo $end_date ?>&sortfld=cnt&sort_pre_order=ASC"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>
                        <a
                            href="report_box_bucks_code.php?action=run&start_date=<?php echo $start_date ?>&end_date=<?php echo $end_date ?>&sortfld=cnt&sort_pre_order=DESC"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                    </font>
                </td>
                <td class="style5" style="width: 10%">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        REVENUE
                        <a
                            href="report_box_bucks_code.php?action=run&start_date=<?php echo $start_date ?>&end_date=<?php echo $end_date ?>&sortfld=tot&sort_pre_order=ASC"><img
                                src="images/sort_asc.jpg" width="5px;" height="10px;"></a>
                        <a
                            href="report_box_bucks_code.php?action=run&start_date=<?php echo $start_date ?>&end_date=<?php echo $end_date ?>&sortfld=tot&sort_pre_order=DESC"><img
                                src="images/sort_desc.jpg" width="5px;" height="10px;"></a>
                    </font>
                </td>
            </tr>

            <?php

                    $queryfinal = "SELECT boxbuckrec.title, count(ord.orders_id) cnt, sum(ord.value) tot FROM orders_total ord";
                    $queryfinal .= " inner join (SELECT orders_id, title FROM orders_total o where title like '%Box Bucks Code%') as boxbuckrec ";
                    $queryfinal .= " on ord.orders_id = boxbuckrec.orders_id ";
                    $queryfinal .= " inner join orders on ord.orders_id = orders.orders_id where ord.class = 'ot_total' ";
                    if ($_GET["start_date"] != "") {
                        $queryfinal .= " AND orders.date_purchased>='$start_date'";
                    }
                    if ($_GET["end_date"] != "") {
                        $queryfinal .= " AND orders.date_purchased<='$end_date'";
                    }

                    $queryfinal .= " group by boxbuckrec.title ";
                    if (isset($_GET["sortfld"])) {
                        $queryfinal .= " order by " . $_GET["sortfld"] . " " . $_GET["sort_pre_order"];
                    } else {
                        $queryfinal .= " order by boxbuckrec.title";
                    }

                    //echo $queryfinal;
                    $tmpfreight = "";

                    db();
                    $res = db_query($queryfinal);
                    while ($row = array_shift($res)) {
                        $tmp_boxbuckcd = preg_replace("/Box Bucks Code /", "", $row["title"]);
                        $tmp_boxbuckcd = preg_replace("/:/", "", $tmp_boxbuckcd);
                    ?>
            <tr vAlign="center">
                <td bgColor="#e4e4e4" style="width: 20%; height: 22px;" class="style3">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $tmp_boxbuckcd; ?></a>
                </td>
                <td bgColor="#e4e4e4" style="width: 10%; height: 22px;" align="right" class="style3">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo $row["cnt"]; ?>
                </td>
                <td bgColor="#e4e4e4" style="width: 10%; height: 22px;" align="right" class="style3">
                    <font face="Arial, Helvetica, sans-serif" color="#333333" size="1">
                        <?php echo "$" . round($row["tot"], 2); ?>
                </td>
            </tr>
            <?php
                    }
                }
            }
            ?>
        </table>
    </div>
</body>

</html>