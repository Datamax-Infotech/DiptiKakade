<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

function timestamp_to_date(string $d): string
{
    $da = explode(" ", $d);
    $dp = explode("-", $da[0]);
    return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
}
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";
$dt_view_res = "";
$emp_initials_list = '';
$emp_b2bid_list = '';
$emp_id_list = '';
$emp_initials_list_str = '';
$emp_b2bid_list_str = '';
$emp_id_list_str = '';
if ($_REQUEST["other_flg"] == "yes") {
    db();
    $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE activity_tracker_flg_purchasing = 1 and status = 'Active'";
    $result = db_query($sql);
    while ($rowemp = array_shift($result)) {
        $emp_initials_list .= "'" . $rowemp["initials"] . "',";
        $emp_b2bid_list .= "'" . $rowemp["b2b_id"] . "',";
        $emp_id_list .= "'" . $rowemp["id"] . "',";
    }
    $emp_initials_list = rtrim($emp_initials_list, ",");
    $emp_b2bid_list = rtrim($emp_b2bid_list, ",");
    $emp_id_list = rtrim($emp_id_list, ",");

    $emp_initials_list_str = " not in (" . $emp_initials_list . ")";
    $emp_b2bid_list_str = " not in (" . $emp_b2bid_list . ")";
    $emp_id_list_str = " not in (" . $emp_id_list . ")";
}

$empid = 0;
$emp_view_qry = "select id from loop_employees where b2b_id = '" . $_REQUEST["b2bempid"] . "'";
db();
$emp_dt_view_res = db_query($emp_view_qry);
while ($emp_dtViewRow = array_shift($emp_dt_view_res)) {
    $empid = $emp_dtViewRow['id'];
}
?>
<html>

<head>
    <title>List Report</title>
</head>

<body>
    <?php
    if (isset($_REQUEST['firstTimeSupplier']) && $_REQUEST['firstTimeSupplier'] == "yes") {
    ?>
    <table>
        <tr>
            <td class="style24" colspan=4 style="height: 16px" align="middle"><strong>Deals entered which were the 1st
                    transaction for a company record</strong></td>
        </tr>
        <tr>
            <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company</strong></td>
        </tr>
        <?php
            $dtViewQry = "SELECT loop_transaction.id, loop_transaction.warehouse_id, loop_transaction.employee, loop_warehouse.b2bid FROM loop_transaction INNER JOIN loop_warehouse ON loop_warehouse.id = loop_transaction.warehouse_id WHERE loop_transaction.id IN (SELECT min(id) FROM loop_transaction GROUP BY warehouse_id) AND loop_transaction.ignore < 1 AND loop_transaction.transaction_date BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59'";
            //echo $dtViewQry;
            db();
            $dtViewRes = db_query($dtViewQry);
            $arrCompIds = array();
            while ($dtViewRow = array_shift($dtViewRes)) {
                $arrCompIds[] = $dtViewRow['b2bid'];
            }
            if (!empty($arrCompIds)) {
                $nickname = "";
                $b2b_id = 0;
                $dt_view_qry_child = "SELECT  companyInfo.ID, companyInfo.loopid, companyInfo.assignedto, companyInfo.company FROM companyInfo LEFT JOIN employees ON employees.employeeID= companyInfo.assignedto WHERE ID IN (" . implode(',', $arrCompIds) . ") AND companyInfo.assignedto = '" . $_REQUEST["b2bempid"] . "' ";
                db_b2b();
                $dt_view_res_child = db_query($dt_view_qry_child);
                while ($dt_view_row_child = array_shift($dt_view_res_child)) {
                    $nickname = get_nickname_val($dt_view_row_child["company"], $dt_view_row_child["ID"]);
                    $b2b_id = $dt_view_row_child["ID"];
            ?>
        <tr>
            <td bgColor="#E4EAEB" class="style12">
                <a target="_blank"
                    href="viewCompany-purchasing.php?ID=<?php echo $b2b_id; ?>&proc=View&searchcrit=&show=transactions&rec_type=Manufacturer"><?php echo $nickname; ?></a>
            </td>
        </tr>
        <?php
                }
            }
            ?>
    </table>
    <?php
    } else {
    ?>

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
                            $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo left JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated >= CURDATE() and landing_pg_enter_by $emp_b2bid_list_str";
                        } else if ($_REQUEST["date_flg"] == "Y") {
                            $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo left JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND and landing_pg_enter_by $emp_b2bid_list_str";
                        } else if ($_REQUEST["date_flg"] == "7") {
                            $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo left JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() and landing_pg_enter_by $emp_b2bid_list_str";
                        } else if ($_REQUEST["date_flg"] == "30") {
                            $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo left JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() and landing_pg_enter_by $emp_b2bid_list_str";
                        } else {
                            $dt_view_qry = "Select ID, company, employees.name as empname from companyInfo left JOIN employees ON companyInfo.landing_pg_enter_by = employees.employeeID where dateCreated BETWEEN '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' and landing_pg_enter_by $emp_b2bid_list_str";
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
                    //echo $dt_view_qry . "<br>";
                    db_b2b();
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
                    db();
                    $dt_view_res = db_query($dt_view_qry);
                }

                if ($_REQUEST["showfirsttimerec"] == "yes") {
                    ?>
            <tr>
                <td class="style24" colspan=4 style="height: 16px" align="middle"><strong>
                        Deals entered which were the 1st transaction for a company record</strong></td>
            </tr>
            <tr>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Transaction
                        ID</strong></td>
            </tr>
            <?php


                    if ($_REQUEST["other_flg"] == "yes") {
                        if ($_REQUEST["date_flg"] == "T") {
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner $emp_id_list_str AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') >= CURDATE() group by loop_boxes_sort.trans_rec_id";
                        } else if ($_REQUEST["date_flg"] == "Y") {
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner $emp_id_list_str AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND group by loop_boxes_sort.trans_rec_id";
                        } else if ($_REQUEST["date_flg"] == "7") {
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner $emp_id_list_str AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between CURDATE() - INTERVAL 7 DAY AND SYSDATE() group by loop_boxes_sort.trans_rec_id";
                        } else if ($_REQUEST["date_flg"] == "30") {
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner $emp_id_list_str AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') CURDATE() - INTERVAL 30 DAY AND SYSDATE() group by loop_boxes_sort.trans_rec_id";
                        } else {
                            //$dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
                            //and loop_boxes_sort.employee = '" . $_REQUEST["crmemp"] . "' AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' group by loop_boxes_sort.trans_rec_id";

                            //and loop_boxes.owner = '" . $_REQUEST["b2bempid"] . "'
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner $emp_id_list_str and STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' group by loop_boxes_sort.trans_rec_id";
                        }
                    } else {
                        if ($_REQUEST["date_flg"] == "T") {
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner = '" . $empid . "' AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') >= CURDATE() group by loop_boxes_sort.trans_rec_id";
                        } else if ($_REQUEST["date_flg"] == "Y") {
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner = '" . $empid . "' AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND group by loop_boxes_sort.trans_rec_id";
                        } else if ($_REQUEST["date_flg"] == "7") {
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner = '" . $empid . "' AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between CURDATE() - INTERVAL 7 DAY AND SYSDATE() group by loop_boxes_sort.trans_rec_id";
                        } else if ($_REQUEST["date_flg"] == "30") {
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner = '" . $empid . "' AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') CURDATE() - INTERVAL 30 DAY AND SYSDATE() group by loop_boxes_sort.trans_rec_id";
                        } else {
                            //$dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
                            //and loop_boxes_sort.employee = '" . $_REQUEST["crmemp"] . "' AND STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' group by loop_boxes_sort.trans_rec_id";

                            //and loop_boxes.owner = '" . $_REQUEST["b2bempid"] . "'
                            $dt_view_qry = "SELECT loop_transaction.id as loopid, loop_transaction.warehouse_id FROM loop_transaction inner join loop_boxes_sort on loop_boxes_sort.trans_rec_id = loop_transaction.id 
							inner join loop_boxes on loop_boxes.id = loop_boxes_sort.box_id
							WHERE loop_transaction.id in (SELECT min(id) FROM loop_transaction group by warehouse_id) and loop_transaction.ignore < 1 
							and loop_boxes.owner = '" . $empid . "' and STR_TO_DATE(loop_boxes_sort.sort_date, '%m/%d/%Y') between '" . $_REQUEST["date_from_val"] . "' AND '" . $_REQUEST["date_to_val"] . " 23:59:59' group by loop_boxes_sort.trans_rec_id";
                        }
                    }
                    //echo $dt_view_qry . "<br>";
                    db();
                    $dt_view_res = db_query($dt_view_qry);
                }

                if ($_REQUEST["quote_amount"] == "yes") {
                    ?>
            <tr>
                <td class="style24" colspan=5 style="height: 16px" align="middle"><strong>Total of Purchase Orders
                        created amounts</strong></td>
            </tr>
            <tr>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Sr No.</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>PO #</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>PO Amount</strong>
                </td>
            </tr>
            <?php
                    if ($_REQUEST["crmemp"] == "Other") {
                        $emp_id_list = "";
                        $emp_initials_list = "";
                        $emp_b2bid_list = "";
                        $emp_email_list = "";
                        $sql_emp = "SELECT * FROM loop_employees WHERE activity_tracker_flg_purchasing = 1 and status = 'Active' ORDER BY quota DESC";
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



                        $dt_view_qry = "Select ID, companyID as company_id, round(quote_total,0) as quote_total from quote where quoteType = 'PO' 
						and quoteDate between '" . $_REQUEST["date_from_val"] . "' and '" . $_REQUEST["date_to_val"] . " 23:59:59' and rep not in ($emp_b2bid_list)";
                    } else {
                        $dt_view_qry = "Select ID, companyID as company_id, quote_total from quote where quoteType = 'PO' and quoteDate between '" . $_REQUEST["date_from_val"] . "' and '" . $_REQUEST["date_to_val"] . " 23:59:59' and rep = '" . $_REQUEST["b2bempid"] . "'";
                    }
                    //echo $dt_view_qry . "<br>";
                    db_b2b();
                    $dt_view_res = db_query($dt_view_qry);
                }

                $quote_total = 0;
                $sr_no = 0;
                //echo $dt_view_qry . "<br>";
                //echo "<pre>"; print_r($dt_view_res); echo "</pre>";
                while ($dt_view_row = array_shift($dt_view_res)) {

                    if ($_REQUEST["showlead"] == "yes") {
                    ?>
            <tr>
                <td bgColor="#E4EAEB" class="style12">
                    <a target="_blank"
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["ID"]; ?>"><?php echo get_nickname_val($dt_view_row["company"], $dt_view_row["ID"]); ?></a>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <?php echo $dt_view_row["empname"]; ?>
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
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["company_id"]; ?>"><?php echo $nickname; ?></a>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <?php echo $dt_view_row["quote_request_tracker_id"]; ?>
                </td>
            </tr>
            <?php
                    }

                    if ($_REQUEST["quote_amount"] == "yes") {
                        $nickname = "";
                        $dt_view_qry_child = "Select company, ID from companyInfo where ID = '" . $dt_view_row["company_id"] . "'";
                        db_b2b();
                        $dt_view_res_child = db_query($dt_view_qry_child);
                        while ($dt_view_row_child = array_shift($dt_view_res_child)) {
                            $nickname = get_nickname_val($dt_view_row_child["company"], $dt_view_row_child["ID"]);
                        }

                        $quote_total = $quote_total + str_replace(",", "", number_format($dt_view_row["quote_total"], 0));
                        db();
                        $sr_no = $sr_no + 1;
                    ?>
            <tr>
                <td bgColor="#E4EAEB" class="style12" align="right">
                    <?php echo $sr_no; ?>
                </td>
                <td bgColor="#E4EAEB" class="style12" align="right">
                    <?php echo $dt_view_row["ID"] + 3770; ?>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <a target="_blank"
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["company_id"]; ?>"><?php echo $nickname; ?></a>
                </td>
                <td bgColor="#E4EAEB" class="style12" align="right">
                    $<?php echo  number_format($dt_view_row["quote_total"], 0); ?>
                </td>
            </tr>
            <?php
                    }

                    if ($_REQUEST["showfirsttimerec"] == "yes") {


                        $nickname = "";
                        $b2b_id = 0;
                        $dt_view_qry_child = "SELECT company, ID FROM companyInfo WHERE loopid = '" . $dt_view_row["warehouse_id"] . "'";
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
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $b2b_id; ?>"><?php echo $nickname; ?></a>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <?php echo $dt_view_row["loopid"]; ?>
                </td>
            </tr>
            <?php
                    }
                }

                if ($_REQUEST["quote_amount"] == "yes") { ?>
            <tr>
                <td bgColor="#E4EAEB" class="style12">&nbsp;
                </td>
                <td bgColor="#E4EAEB" class="style12">&nbsp;
                </td>
                <td bgColor="#E4EAEB" class="style12" align="right">
                    <b>Total:</b>
                </td>
                <td bgColor="#E4EAEB" class="style12" align="right">
                    <b>$<?php echo  number_format($quote_total, 0); ?></b>
                </td>
            </tr>
            <?php
                }
                ?>

        </table>
    </form>
    <?php
    }
    ?>