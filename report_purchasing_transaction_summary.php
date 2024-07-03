<?php


session_start();

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";


// function timestamp_to_date($d)
// {
//     $da = explode(" ", $d);
//     $dp = explode("/", $da[0]);
//     return $dp[0] . "/" . $dp[1] . "/" . $dp[2];
// }

// function getnickname($warehouse_name, $b2bid)
// {
//     $nickname = "";
//     if ($b2bid > 0) {
//         db_b2b();
//         $sql = "SELECT nickname, company, shipCity, shipState FROM companyInfo where ID = ?";
//         $result_comp = db_query($sql, array("i"), array($b2bid));
//         while ($row_comp = array_shift($result_comp)) {
//             if ($row_comp["nickname"] != "") {
//                 $nickname = $row_comp["nickname"];
//             } else {
//                 $tmppos_1 = strpos($row_comp["company"], "-");
//                 if ($tmppos_1 != false) {
//                     $nickname = $row_comp["company"];
//                 } else {
//                     if ($row_comp["shipCity"] <> "" || $row_comp["shipState"] <> "") {
//                         $nickname = $row_comp["company"] . " - " . $row_comp["shipCity"] . ", " . $row_comp["shipState"];
//                     } else {
//                         $nickname = $row_comp["company"];
//                     }
//                 }
//             }
//         }
//         db();
//     } else {
//         $nickname = $warehouse_name;
//     }

//     return $nickname;
// }


?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Purchasing Transaction Summary Report</title>
    <link rel="stylesheet" type="text/css" href="css/new_header-dashboard.css" />
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
</head>

<SCRIPT LANGUAGE="JavaScript" SRC="inc/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="inc/general.js"></SCRIPT>
<script LANGUAGE="JavaScript">
document.write(getCalendarStyles());
</script>
<script LANGUAGE="JavaScript">
//var cal1xx = new CalendarPopup("list_div");
//cal1xx.showNavigationDropdowns();
var cal2xx = new CalendarPopup("listdiv");
cal2xx.showNavigationDropdowns();

function loadmainpg() {

    var date_from = document.getElementById('date_from').value;
    var date_to = document.getElementById('date_to').value;
    var qutse = document.getElementById('recycling').checked;
    var qutpo = document.getElementById('quotespo').checked;
    var date4 = document.getElementById('date_tomorrow').value;
    var dformat1 = "yyyy-MM-dd";
    var dformat2 = "yyyy-MM-dd";


    if (date_from != "" && date_to != "") {
        var chkdate1 = compareDates(date_from, dformat1, date_to, dformat2);
        if (chkdate1 != 0) {
            alert("'To Date' must be greater then 'From Date'");
            return false;
        }

        if (chkdate1 == 0) {

            document.purchasetrfrm.submit();
            return true;
        }

    }

}

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


function show_file_inviewer_pos(filename, formtype, ctrlnm) {
    var filename2 = "https://loops.usedcardboardboxes.com/" + filename;

    var selectobject = document.getElementById(ctrlnm);
    var n_left = f_getPosition(selectobject, 'Left');
    var n_top = f_getPosition(selectobject, 'Top');


    document.getElementById("light").innerHTML =
        "<a href='javascript:void(0)' onclick=document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'>Close</a> &nbsp;<center>" +
        formtype + "</center><br/> <embed src='" + filename2 + "' width='800' height='800'>";
    document.getElementById('light').style.left = 400 + 'px';
    document.getElementById('light').style.top = (n_top + 10) + 'px';
    document.getElementById('light').style.display = 'block';

}
</script>

<style>
.outer-container {
    width: 100%;
    margin: 0 auto;
}

.container {
    padding: 10px;

}

.content {
    margin: 0 auto;
    width: 100%;
    display: grid;
}

.txtstyle_color {
    font-family: arial;
    font-size: 13;
    font-weight: 700;
    height: 16px;
    background: #d6d6d6;
    color: #333333;
    text-align: center;
}

.datarow tr:hover td {
    background-color: #FFFFFF;
}

.rowstyle {
    padding: 0 5px;
    background-color: #EFEFEF;
}

.center {
    text-align: center;
}

.left {
    text-align: left;
}

.right {
    text-align: right;
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
    z-index: 99;
    overflow: auto;
}

.main_data_css {
    margin: 0 auto;
    width: 100%;
    height: auto;
    clear: both !important;
    padding-top: 35px;
    margin-left: 10px;
    margin-right: 10px;
}

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
    height: 90%;
    padding: 16px;
    border: 1px solid gray;
    background-color: white;
    z-index: 1002;
    overflow: auto;
}
</style>


<body>

    <?php include("inc/header.php"); ?>
    <div class="main_data_css">
        <div id="light" class="white_content"></div>

        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">Purchasing Transaction Summary Report</div>
            &nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                <span class="tooltiptext">This report shows the user all B2B purchasing transactions within a range and
                    filter.</span>
            </div>
        </div>

        <?php
        if (!isset($_GET['date_from'])) {
            $_REQUEST['date_from']    = date("Y-m-d", strtotime("-1 week"));
            $_REQUEST['date_to']    = date("Y-m-d", strtotime("now"));
            $_GET['recycling'] = 1;
        }
        ?>
        <div class="container">
            <div class="content">
                <form method="get" name="purchasetrfrm" id="purchasetrfrm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table>
                        <tr>
                            <td style="white-space: nowrap;">
                                <div id="showcal">
                                    Date from:
                                    <input type="text" name="date_from" id="date_from" size="8"
                                        value="<?php echo isset($_REQUEST['date_from']) ? $_REQUEST['date_from'] : ''; ?>">
                                    <a href="#"
                                        onclick="cal2xx.select(document.purchasetrfrm.date_from,'dtanchor2xx','yyyy-MM-dd'); return false;"
                                        name="dtanchor2xx" id="dtanchor2xx"><img border="0"
                                            src="images/calendar.jpg"></a>
                                    <div ID="listdiv"
                                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div id="showcal">
                                    &emsp;To:
                                    <input type="text" name="date_to" id="date_to" size="8"
                                        value="<?php echo isset($_REQUEST['date_to']) ? $_REQUEST['date_to'] : ''; ?>">
                                    <a href="#"
                                        onclick="cal2xx.select(document.purchasetrfrm.date_to,'dtanchor3xx','yyyy-MM-dd'); return false;"
                                        name="dtanchor3xx" id="dtanchor3xx"><img border="0"
                                            src="images/calendar.jpg"></a>
                                    <div ID="listdiv"
                                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                                    </div>

                                </div>
                            </td>
                            <td>
                                <div style="padding:0 20px;">
                                    <input type="checkbox" name="recycling" id="recycling" value="1"
                                        <?php echo (($_GET['recycling'] == 1) ? 'checked' : ''); ?>>
                                    <label for "recycling"> Include Recycling Transactions?</label>
                                </div>
                            </td>
                            <td>
                                <div style="padding:0 20px;">
                                    <select name="transacFilter" id="transacFilter">
                                        <option value="0">Select</option>
                                        <option value="1" <?php if ($_REQUEST["transacFilter"] == "1") {
                                                                echo " selected ";
                                                            } ?>>Transaction Created</option>
                                        <option value="2" <?php if ($_REQUEST["transacFilter"] == "2") {
                                                                echo " selected ";
                                                            } ?>>Pickup Date</option>
                                        <option value="3" <?php if ($_REQUEST["transacFilter"] == "3") {
                                                                echo " selected ";
                                                            } ?>>Sort Report Date</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input type="hidden" id="reprun" name="reprun" value="yes">
                                <input type="hidden" id="date_tomorrow"
                                    value="<?php echo date("Y-m-j", strtotime("+1 day")); ?>">
                                <input type="submit" value="Run Report" onClick="javascript: return loadmainpg()">
                </form>
                </td>
                </tr>
                </table>


            </div><br>
            <div class="content">
                <?php
                $sorturl = "report_purchasing_transaction_summary.php?date_from=" . $_REQUEST["date_from"] . "&date_to=" . $_REQUEST["date_to"] . "&recycling=" . $_REQUEST['recycling'] . "&transacFilter=" . $_REQUEST['transacFilter'];

                ?>

                <table class="datarow" cellSpacing='1' cellPadding='1' border='0'>
                    <thead>
                        <tr>
                            <th width="100%" bgcolor="#C0CDDA" align="center" colspan="9">
                                <font face="Arial, Helvetica, sans-serif">Purchase Transactions</font>
                            </th>
                        </tr>
                        <tr>

                            <th class='txtstyle_color' width='5%'>ID &nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=rid'><img
                                        src='images/sort_asc.png' width='6px' height='12px'></a>&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=rid'><img
                                        src='images/sort_desc.png' width='6px' height='12px'></a>
                            </th>

                            <th class='txtstyle_color' width='21%'>Company Name &nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=cname'><img
                                        src='images/sort_asc.png' width='6px' height='12px'></a>&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=cname'><img
                                        src='images/sort_desc.png' width='6px' height='12px'></a>
                            </th>

                            <th class='txtstyle_color' width='12%'>Transaction Date&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=transdate'><img
                                        src='images/sort_asc.png' width='6px' height='12px'></a>&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=transdate'><img
                                        src='images/sort_desc.png' width='6px' height='12px'></a>
                            </th>
                            <th class='txtstyle_color' width='12%'>Pickup Warehouse&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=puwarehouse'><img
                                        src='images/sort_asc.png' width='6px' height='12px'></a>&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=puwarehouse'><img
                                        src='images/sort_desc.png' width='6px' height='12px'></a>
                            </th>

                            <th class='txtstyle_color' width='10%'>Trailer&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=trailer'><img
                                        src='images/sort_asc.png' width='6px' height='12px'></a>&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=trailer'><img
                                        src='images/sort_desc.png' width='6px' height='12px'></a>
                            </th>
                            <th class='txtstyle_color' width='10%'>Sort Report&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=file'><img
                                        src='images/sort_asc.png' width='6px' height='12px'></a>&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=file'><img
                                        src='images/sort_desc.png' width='6px' height='12px'></a>

                            </th>
                            <th class='txtstyle_color' width='10%'>Pickup Date&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=pudate'><img
                                        src='images/sort_asc.png' width='6px' height='12px'></a>&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=pudate'><img
                                        src='images/sort_desc.png' width='6px' height='12px'></a>
                            </th>

                            <th class='txtstyle_color' width='10%'>Sort Date&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=srtdate'><img
                                        src='images/sort_asc.png' width='6px' height='12px'></a>&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=srtdate'><img
                                        src='images/sort_desc.png' width='6px' height='12px'></a>
                            </th>
                            <th class='txtstyle_color' width='10%'>Employee&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=ASC&sort=emply'><img
                                        src='images/sort_asc.png' width='6px' height='12px'></a>&nbsp;
                                <a href='<?php echo $sorturl; ?>&sort_order_pre=DESC&sort=emply'><img
                                        src='images/sort_desc.png' width='6px' height='12px'></a>
                            </th>



                        </tr>
                    </thead>

                    <?php
                    if (isset($_REQUEST["sort"])) {
                        $MGArray = $_SESSION['sortarrayn'];

                        if ($_GET['sort'] == "rid") {
                            $MGArraysort_I = array();
                            foreach ($MGArray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['ID'];
                            }
                            if ($_GET['sort_order_pre'] == "ASC") {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                            }
                            if ($_GET['sort_order_pre'] == "DESC") {
                                array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                            }
                        }

                        if ($_GET['sort'] == "cname") {
                            $MGArraysort_I = array();
                            foreach ($MGArray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['company_name'];
                            }
                            if ($_GET['sort_order_pre'] == "ASC") {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                            }
                            if ($_GET['sort_order_pre'] == "DESC") {
                                array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                            }
                        }
                        if ($_GET['sort'] == "transdate") {
                            $MGArraysort_I = array();
                            foreach ($MGArray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['stdate'];
                            }
                            if ($_GET['sort_order_pre'] == "ASC") {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                            }
                            if ($_GET['sort_order_pre'] == "DESC") {
                                array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                            }
                        }

                        if ($_GET['sort'] == "puwarehouse") {
                            $MGArraysort_I = array();
                            foreach ($MGArray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['pickup_warehouse'];
                            }
                            if ($_GET['sort_order_pre'] == "ASC") {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                            }
                            if ($_GET['sort_order_pre'] == "DESC") {
                                array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                            }
                        }

                        if ($_GET['sort'] == "trailer") {
                            $MGArraysort_I = array();
                            foreach ($MGArray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['trailer'];
                            }
                            if ($_GET['sort_order_pre'] == "ASC") {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                            }
                            if ($_GET['sort_order_pre'] == "DESC") {
                                array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                            }
                        }

                        if ($_GET['sort'] == "file") {
                            $MGArraysort_I = array();
                            foreach ($MGArray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['file'];
                            }
                            if ($_GET['sort_order_pre'] == "ASC") {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                            }
                            if ($_GET['sort_order_pre'] == "DESC") {
                                array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                            }
                        }

                        if ($_GET['sort'] == "pudate") {
                            $MGArraysort_I = array();
                            foreach ($MGArray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['pudate'];
                            }
                            if ($_GET['sort_order_pre'] == "ASC") {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_NUMERIC, $MGArray);
                            }
                            if ($_GET['sort_order_pre'] == "DESC") {
                                array_multisort($MGArraysort_I, SORT_DESC, SORT_NUMERIC, $MGArray);
                            }
                        }

                        if ($_GET['sort'] == "srtdate") {
                            $MGArraysort_I = array();
                            foreach ($MGArray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['bodate'];
                            }
                            if ($_GET['sort_order_pre'] == "ASC") {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                            }
                            if ($_GET['sort_order_pre'] == "DESC") {
                                array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                            }
                        }
                        if ($_GET['sort'] == "emply") {
                            $MGArraysort_I = array();
                            foreach ($MGArray as $MGArraytmp) {
                                $MGArraysort_I[] = $MGArraytmp['employee'];
                            }
                            if ($_GET['sort_order_pre'] == "ASC") {
                                array_multisort($MGArraysort_I, SORT_ASC, SORT_STRING, $MGArray);
                            }
                            if ($_GET['sort_order_pre'] == "DESC") {
                                array_multisort($MGArraysort_I, SORT_DESC, SORT_STRING, $MGArray);
                            }
                        }
                        foreach ($MGArray as $MGArraytmp2) {
                            if ($MGArraytmp2["compid"] != "") {
                                $activeflg_str = "";
                                if ($MGArraytmp2["active"] == 0) {
                                    $activeflg_str = "<font face='arial' size='2' color='red'><b>&nbsp;INACTIVE</b><font>";
                                }

                    ?>
                    <tr>
                        <td class='rowstyle center'><a target="_blank"
                                href="viewCompany.php?ID=<?php echo $MGArraytmp2['compid']; ?>&show=transactions&warehouse_id=<?php echo $MGArraytmp2['warehouse_id']; ?>&proc=View&searchcrit=&rec_type=Manufacturer&id=<?php echo $MGArraytmp2['warehouse_id']; ?>&rec_id=<?php echo $MGArraytmp2['ID']; ?>&display="><?php echo $MGArraytmp2['ID']; ?></a>
                        </td>
                        <td class='rowstyle'><a target='_blank'
                                href='viewCompany.php?ID=<?php echo $MGArraytmp2['compid']; ?>&show=&warehouse_id=&rec_type=&proc=&searchcrit=&id=&rec_id=&display='><?php echo $MGArraytmp2["company_name"] . " " . $activeflg_str; ?></a>
                        </td>
                        <td class='rowstyle center'><?php echo $MGArraytmp2['stdate']; ?></td>
                        <td class='rowstyle center'><?php echo $MGArraytmp2[""]; ?></td>
                        <td class='rowstyle center'><?php echo $MGArraytmp2['trailer']; ?></td>
                        <td class='rowstyle center'><a href='javascript:void(0);'
                                id='quotespdfs<?php echo $MGArraytmp2["ID"] ?>'
                                onclick='show_file_inviewer_pos("files/<?php echo $MGArraytmp2["file"]; ?>", "Purchasing", "quotespdfs<?php echo $MGArraytmp2["ID"]; ?>"); return false;'><?php echo $MGArraytmp2['file']; ?></a>
                        </td>
                        <td class='rowstyle center'><?php echo $MGArraytmp2['pudate']; ?></td>
                        <td class='rowstyle center'><?php echo $MGArraytmp2['bodate']; ?></td>
                        <td class='rowstyle'><?php echo $MGArraytmp2['employee']; ?></td>



                    </tr>
                    <?php
                            } else {
                            ?>
                    <tr>
                        <td class='rowstyle center' colspan='9'>
                            <?php echo '<i><font color="red">No Record found for given quote' . $_GET['quotenumber'] . '</font></i>'; ?>
                        </td>
                    </tr>

                    <?php
                            }
                        }
                    } else {


                        if (isset($_GET["reprun"]) && $_GET["reprun"] == "yes") {
                            $_SESSION['sortarrayn'] = $MGArray = "";
                            $start_Dt = $_GET["date_from"];
                            $end_Dt = $_GET["date_to"];

                            if (isset($_GET['transacFilter'])) {
                                if ($_GET['transacFilter'] == 0 || $_GET['transacFilter'] == 1) {
                                    $searchstring = " str_to_date(loop_transaction.transaction_date,'%Y-%m-%d') BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . "' AND ";
                                }
                                if ($_GET['transacFilter'] == 2) {
                                    $searchstring = " str_to_date(loop_transaction.pr_pickupdate,'%m/%d/%Y') BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . "' AND ";
                                }

                                if ($_GET['transacFilter'] == 3) {
                                    $searchstring = " str_to_date(loop_transaction.bol_sort_date,'%m/%d/%Y') BETWEEN '" . $start_Dt . "' AND '" . $end_Dt . "' AND ";
                                }
                            }
                            $searchstring = $searchstring ?? "";
                            if (isset($_GET['recycling']) && $_GET['recycling'] == 1) {
                                $searchstring .= " loop_transaction.pr_recycling = 1 AND";
                            }

                            $sql = "SELECT loop_warehouse.company_name AS B,  loop_warehouse.b2bid, loop_warehouse.Active,";
                            $sql .= " loop_transaction.warehouse_id AS D, loop_transaction.usr_amount AS F, loop_transaction.transaction_date, ";
                            $sql .= " loop_transaction.pa_warehouse, loop_transaction.freight_note_flg, loop_transaction.pr_requestdate AS G, ";
                            $sql .= " loop_transaction.pr_pickupdate AS H , loop_transaction.id AS I, loop_transaction.pr_date AS J, ";
                            $sql .= " loop_transaction.usr_file, loop_transaction.sort_entered, loop_transaction.bol_sort_employee,";
                            $sql .= " loop_transaction.cp_employee, loop_transaction.pa_employee, loop_transaction.bol_date,";
                            $sql .= " loop_transaction.pr_trailer, loop_transaction.pr_employee, loop_transaction.dt_employee,";
                            $sql .= " loop_transaction.bol_employee, loop_transaction.pmt_entered ";
                            $sql .= " FROM loop_transaction INNER JOIN loop_warehouse ON loop_transaction.warehouse_id = loop_warehouse.id ";
                            $sql .= " where " . $searchstring;
                            $sql .= " loop_transaction.ignore = 0 ORDER BY loop_transaction.id";

                            db();
                            $result = db_query($sql);
                            if (!empty($result)) {
                                while ($row = array_shift($result)) {

                                    $company_name = getnickname($row["B"], $row["b2bid"]);
                                    $activeflg_str = "";
                                    if ($row["Active"] == 0) {
                                        $activeflg_str = "<font face='arial' size='2' color='red'><b>&nbsp;INACTIVE</b><font>";
                                    }

                                    $pa_warehouse_val = "";
                                    if ($row["pa_warehouse"] != "") {
                                        $sqlin = db_query("SELECT company_name from loop_warehouse where id = '" . $row["pa_warehouse"] . "'");
                                        while ($row_child = array_shift($sqlin)) {
                                            $pa_warehouse_val = $row_child["company_name"];
                                        }
                                    }


                                ?>

                    <tr>

                        <td class='rowstyle center'>
                            <a target="_blank"
                                href="viewCompany.php?ID=<?php echo $row['b2bid']; ?>&show=transactions&warehouse_id=<?php echo $row['D']; ?>&proc=View&searchcrit=&rec_type=Manufacturer&id=<?php echo $row['D']; ?>&rec_id=<?php echo $row['I']; ?>&display="><?php echo $row['I']; ?></a>
                        </td>
                        <td class='rowstyle'><a target='_blank'
                                href='viewCompany.php?ID=<?php echo $row['b2bid']; ?>&show=&warehouse_id=&rec_type=&proc=&searchcrit=&id=&rec_id=&display='><?php echo $company_name . " " . $activeflg_str; ?></a>
                        </td>
                        <td class='rowstyle center'><?php echo date("m/d/Y", strtotime($row['transaction_date'])); ?>
                        </td>
                        <td class='rowstyle center'><?php echo $pa_warehouse_val; ?></td>
                        <td class='rowstyle center'><?php echo $row['pr_trailer']; ?></td>
                        <td class='rowstyle center'><a href='javascript:void(0);' id='quotespdfs<?php echo $row["I"] ?>'
                                onclick='show_file_inviewer_pos("files/<?php echo $row["usr_file"]; ?>", "Purchasing", "quotespdfs<?php echo $row["I"]; ?>"); return false;'><?php echo $row['usr_file']; ?></a>
                        </td>
                        <td class='rowstyle center'><?php echo $row['H']; ?></td>
                        <td class='rowstyle center'><?php echo $row['bol_date']; ?></td>
                        <td class='rowstyle'><?php echo $row['cp_employee']; ?></td>
                    </tr>

                    <?php
                                    $MGArray[] = array(
                                        'compid' => $row["b2bid"], 'warehouse_id' => $row["D"], 'ID' => $row["I"],
                                        'company_name' => $company_name, 'active' => $row["Active"], 'file' => $row["usr_file"],
                                        'pickup_request' => $row["G"], 'pickup_warehouse' => $pa_warehouse_val, 'trailer' => $row['pr_trailer'],
                                        'stdate' => date("m/d/Y", strtotime($row["transaction_date"])), 'pudate' => $row["H"],
                                        'bodate' => $row["bol_date"], 'employee' => $row['cp_employee']
                                    );
                                }
                                $_SESSION['sortarrayn'] = $MGArray;
                            } else {
                                ?>
                    <tr>
                        <td class='rowstyle center' colspan=9>
                            <?php echo '<i><font color="red">No Record found between given dates.</font></i>'; ?>
                        </td>
                    </tr>
                    <?php
                            }
                        }
                    }

                    ?>

                </table></br>
                <?php
                $summary_array = $_SESSION['sortarrayn'];
                $warehousepi = array_count_values(array_column($summary_array, 'pickup_warehouse'));
                $stdate = array_count_values(array_column($summary_array, 'stdate'));
                $pudate = array_count_values(array_column($summary_array, 'pudate'));
                $sumcompany = array_count_values(array_column($summary_array, 'company_name'));
                ?>
                <table style="margin-top:50px;" cellSpacing='1' cellPadding='1' border='0' width="100%">
                    <thead>
                        <tr>
                            <th bgcolor="#C0CDDA" align="center" colspan="3">
                                <font face="Arial, Helvetica, sans-serif">Summary Information of Report</font>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="vertical-align: top;">
                                <table class="datarow" cellSpacing='1' cellPadding='1' border='0' width="100%">
                                    <tr>
                                        <th width="50%" class="txtstyle_color">Warehouse</th>
                                        <th width="50%" class="txtstyle_color">Total Count</th>
                                    </tr>
                                    <?php
                                    if (!empty($warehousepi)) {
                                        foreach ($warehousepi as $key => $val) {
                                    ?>
                                    <tr>
                                        <td class='rowstyle'><?php echo $key ?></td>
                                        <td class='rowstyle center'><?php echo $val ?></td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </td>
                            <td style="vertical-align: top;">
                                <table class="datarow" cellSpacing='1' cellPadding='1' border='0' width="100%">
                                    <tr>
                                        <th width="50%" class="txtstyle_color">Transaction Date</th>
                                        <th width="50%" class="txtstyle_color">Total Count</th>
                                    </tr>

                                    <?php
                                    if (!empty($stdate)) {
                                        foreach ($stdate as $key => $val) {
                                    ?>
                                    <tr>
                                        <td class='rowstyle'><?php echo $key ?></td>
                                        <td class='rowstyle center'><?php echo $val ?></td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>


                                </table>
                            </td>
                            <td style="vertical-align: top;">
                                <table class="datarow" cellSpacing='1' cellPadding='1' border='0' width="100%">
                                    <tr>
                                        <th width="50%" class="txtstyle_color">Pickup Date</th>
                                        <th width="50%" class="txtstyle_color">Total Count</th>
                                    </tr>

                                    <?php
                                    if (!empty($pudate)) {
                                        foreach ($pudate as $key => $val) {
                                    ?>
                                    <tr>
                                        <td class='rowstyle'><?php echo $key ?></td>
                                        <td class='rowstyle center'><?php echo $val ?></td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='3'>
                                <table class="datarow" cellSpacing='1' cellPadding='1' border='0' width="100%">
                                    <tr>
                                        <th width="15%" class="txtstyle_color" align="center">Sr. no.</th>
                                        <th width="50%" class="txtstyle_color" align="center">Company Name</th>
                                        <th width="35%" class="txtstyle_color" align="center">Count</th>
                                    </tr>

                                    <?php
                                    $i = $i ?? 0;
                                    if (!empty(ksort($sumcompany))) {
                                        foreach ($sumcompany as $key => $val) {
                                    ?>
                                    <tr>
                                        <td class='rowstyle center'><?php echo ++$i; ?></td>
                                        <td class='rowstyle'><?php echo $key ?></td>
                                        <td class='rowstyle center'><?php echo $val ?></td>
                                    </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </table>
                            </td>

                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</body>

</html>