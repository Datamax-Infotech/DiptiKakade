<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

function timestamp_to_date(string $d): string
{
    $da = explode(" ", $d);
    $dp = explode("-", $da[0]);
    return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
}
$dt_view_res = "";
$emp_initials_list = '';
$emp_b2bid_list = '';
$emp_initials_list_str = '';
$emp_b2bid_list_str = '';
if ($_REQUEST["other_flg"] == "yes") {
    db();
    if ($_REQUEST["purchasing"] == "yes") {
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE activity_tracker_flg_4= 1 and status = 'Active'";
    } else {
        $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE  activity_tracker_flg = 1 and status = 'Active'";
    }
    $result = db_query($sql);
    while ($rowemp = array_shift($result)) {
        $emp_initials_list .= "'" . $rowemp["initials"] . "',";
        $emp_b2bid_list .= "'" . $rowemp["b2b_id"] . "',";
    }
    $emp_initials_list = rtrim($emp_initials_list, ",");
    $emp_b2bid_list = rtrim($emp_b2bid_list, ",");

    $emp_initials_list_str = " not in (" . $emp_initials_list . ")";
    $emp_b2bid_list_str = " not in (" . $emp_b2bid_list . ")";
}

?>
<html>

<head>
    <title>List Report</title>
</head>

<body>

    <form method="post" action="updateQuoteStatus2.php">
        <table>
            <?php
            if ($_REQUEST["showlead"] == "yes") {
            ?>
            <tr>
                <td class="style24" colspan=4 style="height: 16px" align="middle"><strong>Leads entered in any of the
                        internal landing pages</strong></td>
            </tr>
            <tr>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Rep</strong></td>
            </tr>
            <?php
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["date_flg"] == "T") {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated >= CURDATE() and landing_pg_enter_by $emp_b2bid_list_str";
                    } else if ($_REQUEST["date_flg"] == "Y") {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and landing_pg_enter_by $emp_b2bid_list_str";
                    } else if ($_REQUEST["date_flg"] == "7") {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and landing_pg_enter_by $emp_b2bid_list_str";
                    } else if ($_REQUEST["date_flg"] == "30") {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and landing_pg_enter_by $emp_b2bid_list_str";
                    } else {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' and landing_pg_enter_by $emp_b2bid_list_str";
                    }
                } else {
                    if ($_REQUEST["date_flg"] == "T") {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated >= CURDATE() and landing_pg_enter_by = '" . $_REQUEST["b2bempid"] . "'";
                    } else if ($_REQUEST["date_flg"] == "Y") {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and landing_pg_enter_by = '" . $_REQUEST["b2bempid"] . "'";
                    } else if ($_REQUEST["date_flg"] == "7") {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and landing_pg_enter_by = '" . $_REQUEST["b2bempid"] . "'";
                    } else if ($_REQUEST["date_flg"] == "30") {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and landing_pg_enter_by = '" . $_REQUEST["b2bempid"] . "'";
                    } else {
                        $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo INNER JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' and landing_pg_enter_by = '" . $_REQUEST["b2bempid"] . "'";
                    }
                }
                db_b2b();
                $dt_view_res = db_query($dt_view_qry);
            }

            if ($_REQUEST["showquote_req"] == "demand_entry") {
                ?>
            <tr>
                <td class="style24" colspan=4 style="height: 16px" align="middle"><strong>Demand Entries</strong></td>
            </tr>
            <tr>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Demand Entry
                        ID</strong></td>
            </tr>
            <?php
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["date_flg"] == "T") {
                        $dt_view_qry = "Select * from quote_request where quote_date >= CURDATE() and user_initials $emp_initials_list_str";
                    } else if ($_REQUEST["date_flg"] == "Y") {
                        $dt_view_qry = "Select * from quote_request where quote_date BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and user_initials $emp_initials_list_str";
                    } else if ($_REQUEST["date_flg"] == "7") {
                        $dt_view_qry = "Select * from quote_request where quote_date BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and user_initials $emp_initials_list_str";
                    } else if ($_REQUEST["date_flg"] == "30") {
                        $dt_view_qry = "Select * from quote_request where quote_date BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and user_initials $emp_initials_list_str";
                    } else {
                        $dt_view_qry = "Select * from quote_request where quote_date BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . "' and user_initials $emp_initials_list_str";
                    }
                } else {
                    if ($_REQUEST["date_flg"] == "T") {
                        $dt_view_qry = "Select * from quote_request where quote_date >= CURDATE() and user_initials = '" . $_REQUEST["crmemp"] . "'";
                    } else if ($_REQUEST["date_flg"] == "Y") {
                        $dt_view_qry = "Select * from quote_request where quote_date BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and user_initials = '" . $_REQUEST["crmemp"] . "'";
                    } else if ($_REQUEST["date_flg"] == "7") {
                        $dt_view_qry = "Select * from quote_request where quote_date BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and user_initials = '" . $_REQUEST["crmemp"] . "'";
                    } else if ($_REQUEST["date_flg"] == "30") {
                        $dt_view_qry = "Select * from quote_request where quote_date BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and user_initials = '" . $_REQUEST["crmemp"] . "'";
                    } else {
                        $dt_view_qry = "Select * from quote_request where quote_date BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . "' and user_initials = '" . $_REQUEST["crmemp"] . "'";
                    }
                }
                db();
                $dt_view_res = db_query($dt_view_qry);
            }

            if ($_REQUEST["showquote_req"] == "yes") {
                ?>
            <tr>
                <td class="style24" colspan=4 style="height: 16px" align="middle"><strong>Number of Quote Request
                        Entries entered in the "Quote Request Tracker" on viewCompany in quoting view</strong></td>
            </tr>
            <tr>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Quote Request
                        ID</strong></td>
            </tr>
            <?php
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["date_flg"] == "T") {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted >= CURDATE() and quote_req_submitted_by $emp_initials_list_str";
                    } else if ($_REQUEST["date_flg"] == "Y") {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and quote_req_submitted_by $emp_initials_list_str";
                    } else if ($_REQUEST["date_flg"] == "7") {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and quote_req_submitted_by $emp_initials_list_str";
                    } else if ($_REQUEST["date_flg"] == "30") {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and quote_req_submitted_by $emp_initials_list_str";
                    } else {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' and quote_req_submitted_by $emp_initials_list_str";
                    }
                } else {
                    if ($_REQUEST["date_flg"] == "T") {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted >= CURDATE() and quote_req_submitted_by = '" . $_REQUEST["crmemp"] . "'";
                    } else if ($_REQUEST["date_flg"] == "Y") {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and quote_req_submitted_by = '" . $_REQUEST["crmemp"] . "'";
                    } else if ($_REQUEST["date_flg"] == "7") {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and quote_req_submitted_by = '" . $_REQUEST["crmemp"] . "'";
                    } else if ($_REQUEST["date_flg"] == "30") {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and quote_req_submitted_by = '" . $_REQUEST["crmemp"] . "'";
                    } else {
                        $dt_view_qry = "Select company_id, quote_request_tracker_id from quote_request_tracker where date_submitted BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' and quote_req_submitted_by = '" . $_REQUEST["crmemp"] . "'";
                    }
                }
                db();
                $dt_view_res = db_query($dt_view_qry);
            }

            if ($_REQUEST["showfirsttimerec"] == "yes") {
                ?>
            <tr>
                <td class="style24" colspan=4 style="height: 16px" align="middle"><strong>Deals entered which were the
                        1st transaction for a company record</strong></td>
            </tr>
            <tr>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Transaction
                        ID</strong></td>
            </tr>
            <?php
                if ($_REQUEST["other_flg"] == "yes") {
                    if ($_REQUEST["date_flg"] == "T") {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee $emp_initials_list_str and loop_transaction_buyer.ignore < 1 AND transaction_date >= CURDATE()";
                    } else if ($_REQUEST["date_flg"] == "Y") {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee $emp_initials_list_str and loop_transaction_buyer.ignore < 1 AND transaction_date between CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND";
                    } else if ($_REQUEST["date_flg"] == "7") {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee $emp_initials_list_str and loop_transaction_buyer.ignore < 1 AND transaction_date between CURDATE() - INTERVAL 7 DAY AND SYSDATE()";
                    } else if ($_REQUEST["date_flg"] == "30") {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee $emp_initials_list_str and loop_transaction_buyer.ignore < 1 AND transaction_date between CURDATE() - INTERVAL 30 DAY AND SYSDATE()";
                    } else {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee $emp_initials_list_str and loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59'";
                    }
                } else {
                    if ($_REQUEST["date_flg"] == "T") {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee LIKE '" . $_REQUEST["crmemp"] . "' and loop_transaction_buyer.ignore < 1 AND transaction_date >= CURDATE()";
                    } else if ($_REQUEST["date_flg"] == "Y") {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee LIKE '" . $_REQUEST["crmemp"] . "' and loop_transaction_buyer.ignore < 1 AND transaction_date between CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND";
                    } else if ($_REQUEST["date_flg"] == "7") {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee LIKE '" . $_REQUEST["crmemp"] . "' and loop_transaction_buyer.ignore < 1 AND transaction_date between CURDATE() - INTERVAL 7 DAY AND SYSDATE()";
                    } else if ($_REQUEST["date_flg"] == "30") {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee LIKE '" . $_REQUEST["crmemp"] . "' and loop_transaction_buyer.ignore < 1 AND transaction_date between CURDATE() - INTERVAL 30 DAY AND SYSDATE()";
                    } else {
                        $dt_view_qry = "SELECT loop_transaction_buyer.id as loopid, loop_transaction_buyer.warehouse_id FROM loop_transaction_buyer WHERE id in (SELECT min(id) FROM loop_transaction_buyer group by warehouse_id) and po_employee LIKE '" . $_REQUEST["crmemp"] . "' and loop_transaction_buyer.ignore < 1 AND transaction_date between '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59'";
                    }
                }
                //echo $dt_view_qry . "<br>";
                db();
                $dt_view_res = db_query($dt_view_qry);
            }

            //echo $dt_view_qry;
            while ($dt_view_row = array_shift($dt_view_res)) {

                if ($_REQUEST["showlead"] == "yes") {
                ?>
            <tr>
                <td bgColor="#E4EAEB" class="style12">
                    <a target="_blank"
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo  $dt_view_row["ID"]; ?>">
                        <?php echo get_nickname_val($dt_view_row["company"], $dt_view_row["ID"]); ?>
                    </a>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <?php echo  $dt_view_row["empname"]; ?>
                </td>
            </tr>
            <?php
                }

                if ($_REQUEST["showquote_req"] == "demand_entry") {
                    $nickname = "";
                    $dt_view_qry_child = "Select company, ID from companyInfo where ID = '" . $dt_view_row["companyID"] . "'";
                    db_b2b();
                    $dt_view_res_child = db_query($dt_view_qry_child);
                    while ($dt_view_row_child = array_shift($dt_view_res_child)) {
                        $nickname = get_nickname_val($dt_view_row_child["company"], $dt_view_row_child["ID"]);
                    }

                    db();
                ?>
            <tr>
                <td bgColor="#E4EAEB" class="style12">
                    <a target="_blank"
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo  $dt_view_row["companyID"]; ?>">
                        <?php echo $nickname; ?>
                    </a>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <?php echo  $dt_view_row["quote_id"]; ?>
                </td>
            </tr>
            <?php
                }

                if ($_REQUEST["showquote_req"] == "yes") {
                    $nickname = "";
                    $dt_view_qry_child = "Select company, ID from companyInfo where ID = '" . $dt_view_row["company_id"] . "'";
                    db_b2b();
                    $dt_view_res_child = db_query($dt_view_qry_child);
                    while ($dt_view_row_child = array_shift($dt_view_res_child)) {
                        $nickname = get_nickname_val($dt_view_row_child["company"], $dt_view_row_child["ID"]);
                    }

                    db();
                ?>
            <tr>
                <td bgColor="#E4EAEB" class="style12">
                    <a target="_blank"
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo  $dt_view_row["company_id"]; ?>">
                        <?php echo $nickname; ?>
                    </a>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <?php echo  $dt_view_row["quote_request_tracker_id"]; ?>
                </td>
            </tr>
            <?php
                }

                if ($_REQUEST["showfirsttimerec"] == "yes") {
                    $nickname = "";
                    $b2b_id = 0;
                    $dt_view_qry_child = "Select company, ID from companyInfo where loopid = '" . $dt_view_row["warehouse_id"] . "'";
                    db_b2b();
                    $dt_view_res_child = db_query($dt_view_qry_child);
                    while ($dt_view_row_child = array_shift($dt_view_res_child)) {
                        $nickname = get_nickname_val($dt_view_row_child["company"], $dt_view_row_child["ID"]);
                        $b2b_id = $dt_view_row_child["ID"];
                    }

                    db();
                ?>
            <tr>
                <td bgColor="#E4EAEB" class="style12">
                    <a target="_blank"
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo  $b2b_id; ?>">
                        <?php echo $nickname; ?>
                    </a>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <?php echo  $dt_view_row["loopid"]; ?>
                </td>
            </tr>
            <?php
                }
            }
            ?>

        </table>
    </form>

    <style type="text/css">
    /*Tooltip style*/
    .tooltip {
        position: relative;
        display: inline-block;

    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 250px;
        background-color: #464646;
        color: #fff;
        text-align: left;
        border-radius: 6px;
        padding: 5px 7px;
        position: absolute;
        z-index: 1;
        top: -5px;
        left: 110%;
        /*white-space: nowrap;*/
        font-size: 12px;
        font-family: Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important;
    }

    .tooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 35%;
        right: 100%;
        margin-top: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent black transparent transparent;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }

    .fa-info-circle {
        font-size: 9px;
        color: #767676;
    }

    .txtstyle_color {
        font-family: arial;
        font-size: 12;
        height: 16px;
        background: #ABC5DF;
    }

    .txtstyle {
        font-family: arial;
        font-size: 12;
    }

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
        left: 45%;
        background: #ffffff;
    }

    span.infotxt span {
        position: absolute;
        left: -9999px;
        margin: 0px 0 0 0px;
        padding: 3px 3px 3px 3px;
        border-style: solid;
        border-color: black;
        border-width: 1px;
    }

    span.infotxt:hover span {
        margin: 18px 0 0 170px;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt_freight:hover {
        text-decoration: none;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt_freight span {
        position: absolute;
        left: -9999px;
        margin: 20px 0 0 0px;
        padding: 3px 3px 3px 3px;
        z-index: 6;
    }

    span.infotxt_freight:hover span {
        left: 0%;
        background: #ffffff;
    }

    span.infotxt_freight span {
        position: absolute;
        width: 850px;
        overflow: auto;
        height: 300px;
        left: -9999px;
        margin: 0px 0 0 0px;
        padding: 10px 10px 10px 10px;
        border-style: solid;
        border-color: white;
        border-width: 50px;
    }

    span.infotxt_freight:hover span {
        margin: 5px 0 0 50px;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt_freight2:hover {
        text-decoration: none;
        background: #ffffff;
        z-index: 6;
    }

    span.infotxt_freight2 span {
        position: absolute;
        left: -9999px;
        margin: 20px 0 0 0px;
        padding: 3px 3px 3px 3px;
        z-index: 6;
    }

    span.infotxt_freight2:hover span {
        left: 0%;
        background: #ffffff;
    }

    span.infotxt_freight2 span {
        position: absolute;
        width: 850px;
        overflow: auto;
        height: 300px;
        left: -9999px;
        margin: 0px 0 0 0px;
        padding: 10px 10px 10px 10px;
        border-style: solid;
        border-color: white;
        border-width: 50px;
    }

    span.infotxt_freight2:hover span {
        margin: 5px 0 0 500px;
        background: #ffffff;
        z-index: 6;
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
        width: 1000px;
        z-index: 1002;
        margin: 0px 0 0 0px;
        padding: 10px 10px 10px 10px;
        border-color: black;
        border-width: 2px;
        overflow: auto;
    }
    </style>

    <?php

    if ($_REQUEST["showsalespoamt"] == "yes") {
        $leader_str = " and Leaderboard = 'B2B' ";
        if ($_REQUEST["pallet"] == "yes") {
            $leader_str = " and Leaderboard = 'PALLETS' ";
        }

        if ($_REQUEST["crmemp"] == "Other") {
            $emp_id_list = "";
            $emp_initials_list = "";
            $emp_b2bid_list = "";
            $emp_email_list = "";
            $sql_emp = "SELECT * FROM loop_employees WHERE activity_tracker_flg = 1 and status = 'Active' ORDER BY quota DESC";
            db();
            $result_emp = db_query($sql_emp);
            while ($rowemp = array_shift($result_emp)) {
                $emp_initials_list .= "'" . $rowemp["initials"] . "',";
                $emp_id_list .= $rowemp["id"] . ",";
                $emp_b2bid_list .= $rowemp["b2b_id"] . ",";
                $emp_email_list .= "'" . $rowemp["email"] . "',";
            }
            if ($emp_b2bid_list != "") {
                $emp_b2bid_list = substr($emp_b2bid_list, 0, strlen($emp_b2bid_list) - 1);
                $emp_id_list = substr($emp_id_list, 0, strlen($emp_id_list) - 1);
                $emp_initials_list = substr($emp_initials_list, 0, strlen($emp_initials_list) - 1);
                $emp_email_list = substr($emp_email_list, 0, strlen($emp_email_list) - 1);
            }

            $emp_initials_list1 = "";
            $sql_emp = "SELECT employees.initials FROM employee_all_activity_details inner join employees on employees.loopID = employee_all_activity_details.employee_id
			WHERE employee_all_activity_details.entry_date BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' 
			and employee_all_activity_details.employee_id not in ($emp_id_list) group by employee_all_activity_details.employee_id";
            //echo $sql_emp . "<br>";
            db_b2b();
            $result_emp = db_query($sql_emp);
            while ($rowemp = array_shift($result_emp)) {
                $emp_initials_list1 .= "'" . $rowemp["initials"] . "',";
            }
            if ($emp_initials_list1 != "") {
                $emp_initials_list1 = substr($emp_initials_list1, 0, strlen($emp_initials_list1) - 1);
            }

            $sqlmtd = "SELECT loop_transaction_buyer.transaction_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, 
			loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid 
			FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id 
			inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE 
			po_employee in ($emp_initials_list1) and loop_transaction_buyer.ignore < 1 $leader_str
			AND transaction_date BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59'";

            //echo $sqlmtd . "<br>";

        } else {
            $sqlmtd = "SELECT loop_transaction_buyer.transaction_date, loop_transaction_buyer.po_poorderamount, loop_transaction_buyer.po_employee, loop_transaction_buyer.po_delivery_dt, loop_transaction_buyer.po_date, loop_transaction_buyer.inv_number, 
			loop_transaction_buyer.inv_amount as invsent_amt , loop_invoice_details.total as inv_amount, loop_transaction_buyer.id, loop_transaction_buyer.warehouse_id, loop_warehouse.b2bid 
			FROM loop_transaction_buyer left join loop_invoice_details on loop_invoice_details.trans_rec_id = loop_transaction_buyer.id 
			inner join loop_warehouse on loop_warehouse.id = loop_transaction_buyer.warehouse_id WHERE 
			po_employee LIKE '" . $_REQUEST["crmemp"] . "' and loop_transaction_buyer.ignore < 1 $leader_str
			AND transaction_date BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59'";

            //echo $sqlmtd . "<br>";	
        }

        echo "<table cellSpacing='1' cellPadding='1' border='0' width='820'>";

        echo "<tr><td class='txtstyle_color'>#</td><td class='txtstyle_color'>Employee</td><td class='txtstyle_color'>Loops ID</td>
		<td class='txtstyle_color'>Invoice #</td><td class='txtstyle_color'>Planned Delivery Date</td><td class='txtstyle_color'>Actual Delivery Date</td>
		<td class='txtstyle_color'>Company Nickname</td><td class='txtstyle_color'>Industry</td><td class='txtstyle_color'>Amount</td></tr>";
        db();
        $resultmtd = db_query($sqlmtd);
        $summtd_SUMPO = 0;
        $summtd_dealcnt = 0;
        $summtd_SUMPO_activity = 0;
        $sr_no = 0;
        $summtd_SUMPO_sale_rev = 0;
        while ($summtd = array_shift($resultmtd)) {
            $nickname = "";
            $industry_nm = "";
            $industry_id = "";
            if ($summtd["b2bid"] > 0) {
                db_b2b();
                $sql = "SELECT nickname, company, shipCity, shipState, industry_id FROM companyInfo where ID = " . $summtd["b2bid"];
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_id = $row_comp["industry_id"];
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

                $sql = "SELECT industry FROM industry_master where industry_id = '" . $industry_id . "'";
                $result_comp = db_query($sql);
                while ($row_comp = array_shift($result_comp)) {
                    $industry_nm = $row_comp["industry"];
                }
                db();
            } else {
                $nickname = $summtd["warehouse_name"];
            }

            $finalpaid_amt = 0;


            $inv_amt_totake = 0;

            $inv_amt_totake = str_replace(",", "", $summtd["po_poorderamount"]);

            // $summtd_SUMPO_sale_rev = $summtd_SUMPO_sale_rev + str_replace(",", "", number_format($inv_amt_totake, 0));
            $summtd_SUMPO_sale_rev = $summtd_SUMPO_sale_rev + str_replace(",", "", number_format((float)$inv_amt_totake, 0));

            $summtd_dealcnt = $summtd_dealcnt + 1;
            $po_delivery_dt = "";
            if ($summtd["po_delivery_dt"] != "") {
                $po_delivery_dt = date("m/d/Y", strtotime($summtd["po_delivery_dt"]));
            }

            $actual_delivery_date = "";
            $sql = "SELECT bol_shipment_received_date FROM loop_bol_files WHERE trans_rec_id = " . $summtd["id"];
            $sql_res = db_query($sql);
            while ($row = array_shift($sql_res)) {
                $actual_delivery_date = $row["bol_shipment_received_date"];
            }

            $sr_no = $sr_no + 1;
            // . " " . $summtd["transaction_date"]
            echo "<tr><td bgColor='#E4EAEB'>" . $sr_no . "</td><td bgColor='#E4EAEB'>" . $summtd["po_employee"] . "</td>
			<td bgColor='#E4EAEB'><a target='_blank' href='viewCompany.php?ID=" . $summtd["b2bid"] . "&show=transactions&warehouse_id=" . $summtd["warehouse_id"] . "&rec_type=Supplier&proc=View&searchcrit=&id=" . $summtd["warehouse_id"] . "&rec_id=" . $summtd["id"] . "&display=buyer_view'>" . $summtd["id"] . "</a></td>
			<td bgColor='#E4EAEB'>" . $summtd["inv_number"] . "</td><td bgColor='#E4EAEB'>" . $po_delivery_dt . "</td><td bgColor='#E4EAEB'>" . $actual_delivery_date . "</td>
			<td bgColor='#E4EAEB'>" . $nickname . "</td><td bgColor='#E4EAEB'>" . $industry_nm . "</td><td bgColor='#E4EAEB' align='right'>$" . number_format((float)$inv_amt_totake, 0) . "</td></tr>";
        }

        if ($summtd_SUMPO_sale_rev > 0) {
            echo "<tr><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF'>&nbsp;</td><td bgColor='#ABC5DF' align='right'><b>Total:</b></td>
			<td bgColor='#ABC5DF' align='right'><b>$" . number_format($summtd_SUMPO_sale_rev, 0) . "</b></td></tr>";
        }
        echo "</table>";
    }
    ?>