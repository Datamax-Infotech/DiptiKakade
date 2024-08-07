<?php
// ini_set("display_errors", "1");
// error_reporting(E_ERROR);
session_start();
require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";



if (isset($_SESSION['loginid']) && $_SESSION['loginid'] > 0) {
    if (isset($_SESSION['waterUserLoginId']) && $_SESSION['waterUserLoginId'] > 0) {
        waterUserVisitedTo($_SESSION['waterUserLoginId'], 'dashboard');
    }



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>UCBZeroWaste</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700"
        rel="stylesheet">

    <link rel="shortcut icon" href="images/ucbzerowaste-icon.png" type="image/png">
    <link href="css/homenew.css" rel="stylesheet">
    <link href="css/header-footer.css" rel="stylesheet">
    <link href="css/home-table.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <!--Menu =========================================================================================================================-->
    <link rel="stylesheet" href="menu/demo.css">
    <link rel="stylesheet" href="menu/navigation-icons.css">
    <link rel="stylesheet" href="menu/slicknav/slicknav.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <!--End Menu ====================================================================================================================-->


    <script language="javascript">
    function openInNewTab(url) {
        var win = window.open(url, '_blank');
        win.focus();
    }

    function showdetailrep(supplierid, parent_comp_id, start_date, end_date) {
        openInNewTab("detailed-waste-report.php?warehouse_id=" + supplierid + "&parent_comp_id=" + parent_comp_id +
            "&date_from=" + start_date + "&date_to=" + end_date + "&child_loc=all_loc");
    }

    function repsummaryinton() {
        document.getElementById('repsummarytype').value = "yes";
        document.frmrepsummary.submit();
    }

    function repsummaryinlbs() {
        document.getElementById('repsummarytype').value = "";
        document.frmrepsummary.submit();
    }

    function welcomescreen() {
        //var modal = document.getElementById("myModal");
        //modal.style.display = "block";
    }

    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";

        if (document.getElementById("alertmsg_chk").checked) {

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    alert("Welcome message will not be shown again.");
                }
            }

            xmlhttp.open("GET", "update_alert_msg.php", true);
            xmlhttp.send();
        }
    }
    </script>

    <!-- Facebook Pixel Code -->
    <script>
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window, document, 'script',
        'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1109377375928443');
    fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=1109377375928443&ev=PageView
	&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->
    <style type="text/css">
    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        padding-top: 250px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 30%;
        font-size: 16px;
        font-weight: normal;
        font-family: 'Fira Sans Condensed', sans-serif;
    }

    .lnkLetsgo {
        background-color: #199319;
        color: white;
        padding: 5px 15px;
    }

    .smallerFont {
        font-size: 12px;
        font-weight: normal;
        padding-top: 8px;
        font-family: 'Fira Sans Condensed', sans-serif;
    }

    @media screen and (max-width: 550px) {
        .financial_circle_small_font {
            font-size: 12px !important;
        }

        .counter-container ul li {
            width: 100% !Important;
        }
    }
    </style>

</head>
<?php

    ?>
<!-- The welcome screen Modal start-->
<?php if ($_COOKIE['donotshowmsg'] == "yes") {
    } else {
    ?>

<?php } ?>
<!-- The welcome screen Modal start onload="welcomescreen()"-->

<body>


    <?php
        //	require ("../../securedata/config_prod_mysqli.php");
        //require("../securedata/main-enc-class.php");



        db();
        error_reporting(0);
        function getnickname_warehouse(string $warehouse_name, int $loopid): string
        {
            $nickname = "";
            if ($loopid > 0) {
                db_b2b();
                $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where loopid = ?";
                $result_comp = db_query($sql, array("i"), array($loopid));
                while ($row_comp = array_shift($result_comp)) {
                    if ($row_comp["nickname"] != "") {
                        $nickname = $row_comp["nickname"];
                    } else {
                        $tmppos_1 = strpos($row_comp["company"], "-");
                        if ($tmppos_1 != false) {
                            $nickname = $row_comp["company"];
                        } else {
                            if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
                                $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
                            } else {
                                $nickname = $row_comp["company"];
                            }
                        }
                    }
                }
                db();
            } else {
                $nickname = $warehouse_name;
            }

            return $nickname;
        }

        $companyid = 0;
        $warehouse_id = 0;
        $company_name = "";
        $company_logo = "blank-logo.jpg";
        $parent_comp_flg = 0;
        $warehouse_id_cronjobs = 0;
        $main_company_name = "";
        $sql = "SELECT companyid, parent_comp_flg FROM supplierdashboard_usermaster WHERE loginid=? and activate_deactivate = 1";
        //echo $sql . "<br>";
        $result = db_query($sql, array("i"), array($_SESSION['loginid']));
        while ($myrowsel = array_shift($result)) {
            $parent_comp_flg = $myrowsel['parent_comp_flg'];

            if ($parent_comp_flg == 1) {
                $child_found = "no";
                db_b2b();
                $sql1 = "select ID from companyInfo where parent_comp_id= ? and parent_child ='Child' and haveNeed = 'Have Boxes'";
                $result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
                while ($myrowsel1 = array_shift($result1)) {
                    $child_found = "yes";
                }
                db();
                if ($child_found == "no") {
                    $parent_comp_flg = 0;
                }
            }

            $sql1 = "SELECT logo_image FROM supplierdashboard_details WHERE companyid=? ";
            $result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
            while ($myrowsel1 = array_shift($result1)) {
                if ($myrowsel1["logo_image"] != "") {
                    $company_logo = $myrowsel1["logo_image"];
                }
            }

            $sql1 = "SELECT id, company_name FROM loop_warehouse WHERE b2bid=? ";
            $result1 = db_query($sql1, array("i"), array($myrowsel["companyid"]));
            while ($myrowsel1 = array_shift($result1)) {
                $warehouse_id = $myrowsel1["id"];
                $company_name = $myrowsel1["company_name"] . "'s";
                $main_company_name = getnickname_warehouse($myrowsel1["company_name"], $warehouse_id);
            }

            if ($parent_comp_flg == 1 && $warehouse_id == 0) {
                $warehouse_id_cronjobs = $myrowsel["companyid"];
            } else {
                $warehouse_id_cronjobs = $warehouse_id;
            }

            $companyid = $myrowsel["companyid"];
        }

        if (isset($_REQUEST["inv_rep_yr"])) {
            $selected_yr = $_REQUEST["inv_rep_yr"];
        } else {
            $selected_yr = date("Y");
        }

        $st_date = date($selected_yr . "-01-01");
        $end_date = date($selected_yr . "-m-d");

        $total_cost = 0;
        $high_pay_vendor = 0;
        $costly_vendor = 0;
        $high_pay_vendor_val = "";
        $costly_vendor_val = "";
        $tree_saved = "";
        $best_finance_impact = "";
        $best_finance_impact_val = "";
        $best_landfil_diversion = "";
        $best_landfil_diversion_val = "";
        $lowest_finance_impact_val = "";
        $lowest_landfil_diversion_val = "";
        $lowest_finance_impact = "";
        $lowest_landfil_diversion = "";
        $sql1 = "SELECT * FROM water_cron_fordash WHERE warehouse_id = ? and data_year = ?";
        $result1 = db_query($sql1, array("i", "i"), array($warehouse_id_cronjobs, $selected_yr));
        while ($myrowsel1 = array_shift($result1)) {
            $total_cost = $myrowsel1["waste_financial"];
            $high_pay_vendor = $myrowsel1["high_pay_vendor"];

            $high_pay_vendor_val = number_format($myrowsel1["high_pay_vendor_val"], 2);
            $costly_vendor_val = number_format($myrowsel1["costly_vendor_val"], 2);

            $costly_vendor = $myrowsel1["costly_vendor"];
            $tree_saved = number_format($myrowsel1["tree_saved"], 0);

            $best_finance_impact = getnickname_warehouse("", $myrowsel1["best_finance_impact"]);
            $best_finance_impact_val = $myrowsel1["best_finance_impact_val"];
            $best_landfil_diversion = getnickname_warehouse("", $myrowsel1["best_landfil_diversion"]);
            $best_landfil_diversion_val = $myrowsel1["best_landfil_diversion_val"];
            $lowest_finance_impact_val = $myrowsel1["lowest_finance_impact_val"];
            $lowest_landfil_diversion_val = $myrowsel1["lowest_landfil_diversion_val"];
            $lowest_finance_impact = getnickname_warehouse("", $myrowsel1["lowest_finance_impact"]);
            $lowest_landfil_diversion = getnickname_warehouse("", $myrowsel1["lowest_landfil_diversion"]);
        }

        if ($high_pay_vendor == 0 && $high_pay_vendor_val != 0) {
            $high_pay_vendor_img = "ucb-logo.png";
            $high_pay_vendor_nm = "UsedCardboardBoxes";
        } else {
            $high_pay_vendor_img = "";
            $high_pay_vendor_nm = "";
            $sql1 = "SELECT * FROM water_vendors WHERE id = ?";
            $result1 = db_query($sql1, array("i"), array($high_pay_vendor));
            while ($myrowsel1 = array_shift($result1)) {
                if ($myrowsel1["logo_image"] != "") {
                    $high_pay_vendor_img = $myrowsel1["logo_image"];
                } else {
                    $high_pay_vendor_img = "blank-logo.jpg";
                }
                $high_pay_vendor_nm = $myrowsel1["Name"];
            }
        }

        if ($costly_vendor == 0 && $costly_vendor_val != 0) {
            $costly_vendor_img = "ucb-logo.png";
            $costly_vendor_nm = "UsedCardboardBoxes";
        } else {
            $costly_vendor_img = "";
            $costly_vendor_nm = "";
            $sql1 = "SELECT * FROM water_vendors WHERE id = ?";
            $result1 = db_query($sql1, array("i"), array($costly_vendor));
            while ($myrowsel1 = array_shift($result1)) {
                if ($myrowsel1["logo_image"] != "") {
                    $costly_vendor_img = $myrowsel1["logo_image"];
                } else {
                    $costly_vendor_img = "blank-logo.jpg";
                }
                $costly_vendor_nm = $myrowsel1["Name"];
            }
        }
        $_SESSION['pgname'] = "home";
        ?>
    <?php require("mainfunctions/top-header.php");    ?>

    <div class="year-container">
        <div class="sub-year-container">
            <form name="frmselyear" id="frmselyear" action="dashboard.php" method="post">
                <h4>Viewing:
                    <select id="inv_rep_yr" name="inv_rep_yr">
                        <?php
                            for ($i = 2017, $n = Date("Y") + 1; $i < $n; $i++) {
                            ?>
                        <option value="<?php echo  $i ?>" <?php if ($selected_yr == $i) {
                                                                        echo " selected ";
                                                                    } ?>><?php echo  $i ?></option>
                        <?php
                            }
                            ?>
                    </select>
                    &nbsp;<input type="submit" class="logout-button" value="Submit" name="btnrepyear" id="btnrepyear">
                </h4>
            </form>
        </div>
    </div>

    <?php
        //For Summary of Waste Processing(YTD)
        $res1 = db_query("Select * from water_cron_summary_rep where warehouse_id = '" . $warehouse_id_cronjobs . "' and data_year = '$selected_yr'");
        while ($row_mtd1 = array_shift($res1)) {
            $outlet_tot[] = array('outlet' => $row_mtd1["outlet"], 'tot' => $row_mtd1["weight_tot"], 'perc' => $row_mtd1["perc_val"] . "%", 'totval' => $row_mtd1["amount_tot"]);

            $sumtot = $row_mtd1["sumtot_weight"];
            $totalval_tot = $row_mtd1["sumtot_amount"];
            $othar_charges = $row_mtd1["other_charges"];
        }

        $total_cost = isset($totalval_tot) + isset($othar_charges);
        ?>
    <div class="slider-container">
        <div class="banner-image">
            <div class="counter-container">
                <ul>
                    <li class="financial-circle">
                        <?php //$total_cost_length=strlen(strval((int)$total_cost)); 
                            ?>

                        <?php if ($total_cost > 0) { ?>
                        <div class="counter-number-text numbergreen financial_circle_small_font">+
                            <?php echo number_format($total_cost, 2); ?>
                        </div>
                        <?php } elseif ($total_cost == 0) { ?>
                        <div class="counter-number-text number financial_circle_small_font">
                            <?php echo number_format($total_cost, 2); ?>
                        </div>
                        <?php } else { ?>
                        <div class="counter-number-text numberred financial_circle_small_font">
                            <?php echo number_format($total_cost, 2); ?>
                        </div>
                        <?php } ?>
                        <div class="counter-title-text financial_circle_small_font">Net Financial Spend (YTD)
                            <div class="tooltip">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                <span class="tooltiptext">
                                    Net Financial Spend refers to the year-to-date net financial spend from your waste
                                    stream. <br>
                                    Net Financial Spend = (Revenue from waste and recycling vendors rebates -
                                    Transportation and additional fees) - (costs waste and recycling vendors disposal
                                    charges - Transportation and additional fees).<br>
                                    If the Net Financial Spend is a positive and a green number, this represents a net
                                    credit or net rebate to your company. <br>
                                    If the Net Financial Spend is a negative and a red number, this represents a net
                                    cost or expense to your company.<br>
                                </span>
                            </div>
                        </div>
                    </li>

                    <li class="landfill-circle">
                        <div id="divlandfilldiversion" class="counter-number-text per financial_circle_small_font">
                        </div>
                        <div class="counter-title-text tooltip financial_circle_small_font">Current Landfill Diversion
                            (YTD)
                            <div class="tooltip">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                <span class="tooltiptext">
                                    Landfill diversion is the process of diverting waste from landfills. UCB's
                                    definition of landfill diversion includes the following methods:<br>
                                    <span class="reuse">REUSE</span><br>
                                    <span class="recycling">RECYCLING (INCLUDING COMPOSTING, ANIMAL FEED, AND ANAEROBIC
                                        DIGESTION)</span><br>
                                    <span class="waste">WASTE TO ENERGY</span>
                                </span>
                            </div>
                        </div>
                    </li>

                    <li class="trees-circle">
                        <div id="divtreesaved" class="counter-number-text number1 financial_circle_small_font">
                            <?php echo $tree_saved; ?>
                        </div>
                        <div class="counter-title-text tooltip financial_circle_small_font">Estimated Trees Saved (YTD)
                            <div class="tooltip">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                <span class="tooltiptext">
                                    Preventing 1 ton of paper waste saves between 15 and 17 mature trees. The estimate
                                    is based on the amount of fiber based materials, such as office paper and cardboard
                                    boxes, directed to the reuse and recycling waste streams.
                                    Source for the calculation can be found at: <a
                                        href="https://archive.epa.gov/epawaste/conserve/smm/wastewise/web/html/factoid.html"
                                        target="_blank">Link</a>
                                </span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="mid-container">
        <div class="mid-container1">

            <div class="container1">
                <h1>Landfill Diversion (YTD)</h1>
                <iframe src="water-ytd-pie-chart-dash.php?selected_yr=<?php echo $selected_yr; ?>&warehouse_id=<?php echo $warehouse_id_cronjobs; ?>&start_date=<?php echo date($selected_yr . "
                    -01-01"); ?>&end_date=
                    <?php echo date($selected_yr . "-m-d"); ?>" frameborder="0" width="100%" height="420px">
                </iframe>
            </div>

            <?php


                $weight_str = "(Ib)";
                if ($_REQUEST["repsummarytype"] == "yes") {
                    $weight_str = "(Tons)";
                }
                ?>
            <div class="container2">
                <h1>Summary of Waste Processing (YTD)</h1>
                <div id="no-more-tables">
                    <table>
                        <tr>
                            <th width="31%" valign="top">SUMMARY Per Process</th>
                            <th width="23%" valign="top">Total Weight
                                <?php echo $weight_str; ?>
                            </th>
                            <th width="20%" valign="top">% Waste Stream</th>
                            <th width="26%" valign="top">Total Amount ($)</th>
                        </tr>

                        <?php
                            $divlandfilldiversion = "";
                            $total_weight = 0;
                            foreach ($outlet_tot as $outlet_tottmp) {
                                $bg_color = "";
                                if ($outlet_tottmp['outlet'] == "Landfill") {
                                    $bg_color = "#df0000";
                                }
                                if ($outlet_tottmp['outlet'] == "Incineration (No Energy Recovery)") {
                                    $bg_color = "#cc6511";
                                }
                                if ($outlet_tottmp['outlet'] == "Waste To Energy") {
                                    $bg_color = "#ffb813";
                                }
                                if ($outlet_tottmp['outlet'] == "Recycling") {
                                    $bg_color = "#00b0f0";
                                }
                                if ($outlet_tottmp['outlet'] == "Reuse") {
                                    $bg_color = "#1cc700";
                                }

                                if ($outlet_tottmp['outlet'] == "Waste To Energy" || $outlet_tottmp['outlet'] == "Reuse" || $outlet_tottmp['outlet'] == "Recycling") {
                                    $divlandfilldiversion = $divlandfilldiversion + floatval($outlet_tottmp['perc']);
                                }
                                if ($_REQUEST["repsummarytype"] == "yes") {
                                    $total_weight = $total_weight + $outlet_tottmp['tot'] / 2000;
                                } else {
                                    $total_weight = $total_weight + $outlet_tottmp['tot'];
                                }
                            ?>
                        <tr>
                            <?php if ($outlet_tottmp['outlet'] == "Incineration (No Energy Recovery)") { ?>
                            <td class='td-leftalign' style='color:<?php echo $bg_color; ?>;'>Incineration</td>
                            <?php } else { ?>
                            <td class='td-leftalign' style='color:<?php echo $bg_color; ?>;'>
                                <?php echo $outlet_tottmp['outlet']; ?>
                            </td>
                            <?php } ?>
                            <?php if ($_REQUEST["repsummarytype"] == "yes") { ?>
                            <td>
                                <?php echo number_format($outlet_tottmp['tot'] / 2000, 2); ?>
                            </td>
                            <td>
                                <?php echo $outlet_tottmp['perc']; ?>
                            </td>
                            <?php } else { ?>
                            <td>
                                <?php echo number_format($outlet_tottmp['tot'], 0); ?>
                            </td>
                            <td>
                                <?php echo $outlet_tottmp['perc']; ?>
                            </td>
                            <?php } ?>

                            <?php if ($outlet_tottmp['totval'] < 0) { ?>
                            <td class='red1'>$
                                <?php echo number_format($outlet_tottmp['totval'], 2); ?>
                            </td>
                            <?php } else { ?>
                            <td>$
                                <?php echo number_format($outlet_tottmp['totval'], 2); ?>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php
                            }

                            if ($divlandfilldiversion > 100) {
                                $divlandfilldiversion = 100;
                            }
                            $divlandfilldiversion = round($divlandfilldiversion, 2);
                            ?>
                        <tr>
                            <td colspan="3" class='td-footer'>TOTAL WEIGHT
                                <?php echo $weight_str; ?>
                            </td>
                            <?php if ($_REQUEST["repsummarytype"] == "yes") { ?>
                            <?php if ($total_weight < 0) { ?>
                            <td class='td-footer1 red1'>
                                <?php echo number_format($total_weight, 2); ?>
                            </td>
                            <?php } else { ?>
                            <td class='td-footer1'>
                                <?php echo number_format($total_weight, 2); ?>
                            </td>
                            <?php } ?>
                            <?php } else { ?>
                            <?php if ($total_weight < 0) { ?>
                            <td class='td-footer1 red1'>
                                <?php echo number_format($total_weight, 0); ?>
                            </td>
                            <?php } else { ?>
                            <td class='td-footer1'>
                                <?php echo number_format($total_weight, 0); ?>
                            </td>
                            <?php } ?>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td colspan="3" class='td-footer'>TOTAL AMOUNT ($)</td>
                            <?php if ($totalval_tot < 0) { ?>
                            <td class='td-footer1 red1'>$
                                <?php echo number_format($totalval_tot, 2); ?>
                            </td>
                            <?php } else { ?>
                            <td class='td-footer1'>$
                                <?php echo number_format($totalval_tot, 2); ?>
                            </td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td colspan="3" class="td-footer">TOTAL ADDITIONAL FEES</td>
                            <td class="td-footer1 red1">$
                                <?php echo number_format($othar_charges, 2); ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="td-footer">GRAND TOTAL </td>
                            <?php if (($totalval_tot + isset($othar_charges)) < 0) { ?>
                            <td class="td-footer1 red1">$
                                <?php echo number_format($totalval_tot + $othar_charges, 2); ?>
                            </td>
                            <?php } else { ?>
                            <td class="td-footer1">$
                                <?php echo number_format($totalval_tot + $othar_charges, 2); ?>
                            </td>
                            <?php } ?>
                        </tr>
                    </table>
                </div>

                <form name="frmrepsummary" id="frmrepsummary" action="dashboard.php" method="post">
                    <input type="hidden" value="<?php echo $selected_yr; ?>" name="inv_rep_yr" id="inv_rep_yr" />
                    <?php if ($_REQUEST["repsummarytype"] == "yes") { ?>
                    <div class="column-white"><input type="submit" class="logout-button1"
                            value="Display Summary in Pounds" name="btnsummaryton" id="btnsummaryton"
                            onclick="repsummaryinlbs()"></div>
                    <div class="column-white"><input type="button" class="logout-button1"
                            value="See Full Detailed Report" onclick="showdetailrep(<?php echo $warehouse_id; ?>, <?php echo $companyid; ?>, '<?php echo date("
                            01/01" . $selected_yr); ?>', '
                        <?php echo date("12/31/" . $selected_yr); ?>')">
                    </div>

                    <input type="hidden" value="" name="repsummarytype" id="repsummarytype" />
                    <?php } else { ?>
                    <div class="column-white">
                        <input type="submit" class="logout-button1" value="Display Summary in Tons" name="btnsummaryton"
                            id="btnsummaryton" onclick="repsummaryinton()">
                    </div>
                    <div class="column-white"><input type="button" class="logout-button1"
                            value="See Full Detailed Report" onclick="showdetailrep(<?php echo $warehouse_id; ?>, <?php echo $companyid; ?>, '<?php echo date("
                            01/01/" . $selected_yr); ?>', '
                        <?php echo date("12/31/" . $selected_yr); ?>')">
                    </div>

                    <input type="hidden" value="" name="repsummarytype" id="repsummarytype" />
                    <?php } ?>
                </form>

            </div>

            <script language="JavaScript">
            var landfilldiversion = <?php echo json_encode($divlandfilldiversion); ?>;
            document.getElementById("divlandfilldiversion").innerHTML = landfilldiversion + "%";
            </script>
            <div class="main_last_col">
                <?php if ($parent_comp_flg == 1) { ?>
                <div class="container3">
                    <h1>Best Performing Locations(YTD)</h1>
                    <div class="container4">
                        <?php
                                //&& $best_finance_impact_val > 0
                                if ($best_finance_impact != "") { ?>
                        <br>
                        <div class="performing-location">
                            <?php echo "<span style='color:#1cc700;'>Best Financial Impact: " . $best_finance_impact; ?>
                            :<br> $
                            <?php echo number_format($best_finance_impact_val, 2) . "</span>"; ?>
                        </div><br><br><br>
                        <?php
                                } else {
                                    echo "<div class='performing-location'><span style='color:#1cc700;'>Best Financial Impact:</span></div><br><br><br>";
                                }
                                ?>
                        <?php if ($best_landfil_diversion != "" && $best_landfil_diversion_val > 0) { ?>
                        <div class="performing-location">
                            <?php echo "<span style='color:#1cc700;'>Best Landfill Diversion: " . $best_landfil_diversion; ?>:<br>
                            <?php echo $best_landfil_diversion_val . "%</span>"; ?>
                        </div><br>
                        <?php } else {
                                    echo "<div class='performing-location'><span style='color:#1cc700;'>Best Landfill Diversion:</span></div>";
                                }
                                ?>
                    </div>
                </div>


                <div class="container5">
                    <h1>Worst Performing Locations(YTD)</h1>
                    <div class="costly-vendor">
                        <?php
                                //$lowest_finance_impact_val > 0
                                if ($lowest_finance_impact != "") { ?>
                        <br>
                        <div class="performing-location">
                            <?php echo "<span style='color:#df0000;'>Lowest Financial Impact: " . $lowest_finance_impact; ?>:<br>
                            $
                            <?php echo number_format($lowest_finance_impact_val, 2) . "</span>"; ?>
                        </div><br><br><br>
                        <?php } else {
                                    echo "<div class='performing-location'><span style='color:#df0000;'>Lowest Financial Impact:</span></div><br><br><br>";
                                }
                                ?>
                        <?php if ($lowest_landfil_diversion != "" && $lowest_landfil_diversion_val > 0) { ?>
                        <div class="performing-location bottom_m">
                            <?php echo "<span style='color:#df0000;'>Lower Landfill Diversion: " . $lowest_landfil_diversion; ?>:<br>
                            <?php echo $lowest_landfil_diversion_val . "%</span>"; ?>
                        </div>
                        <?php } else {
                                    echo "<div class='performing-location bottom_m'><span style='color:#df0000;'>Lower Landfill Diversion:</span></div>";
                                }
                                ?>
                    </div>
                </div>

                <?php } else { ?>
                <div class="container3">
                    <h1>Highest Paying Vendor (YTD)</h1>
                    <div class="container4">
                        <div class="costly-vendor-img">
                            <?php if ($high_pay_vendor_nm != "" && $high_pay_vendor_val > 0) {
                                        echo $high_pay_vendor_nm; ?><br>
                            <div class="img_vendor">
                                <img src="https://loops.usedcardboardboxes.com/vendor_logo_images/<?php echo $high_pay_vendor_img; ?>"
                                    width="100" height="100" style="object-fit: contain;" alt="" />
                            </div>
                            <div class="Highestpayfig">$
                                <?php echo $high_pay_vendor_val; ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="container5">
                    <h1>Most Costly Vendor (YTD)</h1>
                    <div class="costly-vendor">
                        <div class="costly-vendor-img">
                            <?php if ($costly_vendor_nm != "") {
                                        echo $costly_vendor_nm; ?><br>
                            <div class="img_vendor">
                                <img src="https://loops.usedcardboardboxes.com/vendor_logo_images/<?php echo $costly_vendor_img; ?>"
                                    width="100" height="100" style="object-fit: contain;" alt="" />
                            </div>
                            <div class="Costlyvendorfig">$
                                <?php echo $costly_vendor_val; ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="footer">
        <?php require("mainfunctions/footerContent.php");    ?>
    </div>


    <!-- JavaScript Libraries -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/superfish/superfish.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <!-- Contact Form JavaScript File -->

    <!-- Template Main Javascript File -->

    <script src="js/main.js"></script>
    <script src="menu/slicknav/jquery.slicknav.min.js"></script>

    <script>
    $(function() {
        $('.menu-navigation-icons').slicknav();

        //alert($(window).width());  
        //alert($(document).width());		
    });
    </script>

</body>

</html>
<?php

} else {
    echo "<script type=\"text/javascript\">";
    echo "window.location.href=\"index.php" . "\";";
    echo "</script>";
    echo "<noscript>";
    echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php" . "\" />";
    echo "</noscript>";
    exit;
}
?>