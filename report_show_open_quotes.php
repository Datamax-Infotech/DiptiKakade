<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

function timestamp_to_date(string $d): string
{
    $da = explode(" ", $d);
    $dp = explode("-", $da[0]);
    return $dp[1] . "/" . $dp[2] . "/" . $dp[0];
}
$dt_view_qry = "";
$emp_initials_list = '';
$emp_b2bid_list = '';
$emp_initials_list_str = '';
$emp_b2bid_list_str = '';

if ($_REQUEST["other_flg"] == "yes") {

    db();
    $sql = "SELECT id, name, initials, email, b2b_id, leaderboard  FROM loop_employees WHERE  (leaderboard = 1 or purchasing_leaderboard = 1) and status = 'Active'";
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
    <title>Show Open Quotes</title>
</head>

<body>

    <?php
    $quotes_archive_date = "";
    $query = "SELECT variablevalue FROM tblvariable where variablename = 'quotes_archive_date'";
    db();
    $dt_view_res3 = db_query($query);
    while ($objQuote = array_shift($dt_view_res3)) {
        $quotes_archive_date = $objQuote["variablevalue"];
    }
    ?>
    <form method="post" action="updateQuoteStatus2.php">
        <table>
            <tr>
                <td class="style24" colspan=5 style="height: 16px" align="middle"><strong>OPEN QUOTES</strong></td>
            </tr>
            <tr>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Sr. No.</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Date</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Company
                        Nickname</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Rep</strong></td>
                <td bgColor="#ABC5DF" class="style12" style="height: 16px" align="middle"><strong>Amount</strong></td>
            </tr>
            <?php

            if ($_REQUEST["other_flg"] == "yes") {
                if ($_REQUEST["flg"] == "Today") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND qstatus !=2 AND quoteDate >= CURDATE() ORDER BY quoteDate DESC";
                    } else {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND  qstatus !=2 AND quoteDate = '" . $_REQUEST["date_from_val"] . "' ORDER BY quoteDate DESC";
                    }
                }

                if ($_REQUEST["flg"] == "yesterday") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND ORDER BY quoteDate DESC";
                    } else {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"] . " -1 days")) . "' ORDER BY quoteDate DESC";
                    }
                }

                if ($_REQUEST["flg"] == "7days") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() ORDER BY quoteDate DESC";
                    } else {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -7 days")) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY quoteDate DESC";
                    }
                }

                if ($_REQUEST["flg"] == "b2bl") {
                    $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY quoteDate DESC";
                }

                if ($_REQUEST["flg"] == "month") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() ORDER BY quoteDate DESC";
                    } else {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -30 days")) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY quoteDate DESC";
                    }
                }
                if ($_REQUEST["poenter"] == "y") {
                    $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep $emp_b2bid_list_str AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . "")) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY quoteDate DESC";
                }
            } else {
                if ($_REQUEST["flg"] == "Today") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND qstatus !=2 AND quoteDate >= CURDATE() ORDER BY quoteDate DESC";
                    } else {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND  qstatus !=2 AND quoteDate = '" . $_REQUEST["date_from_val"] . "' ORDER BY quoteDate DESC";
                    }
                }

                if ($_REQUEST["flg"] == "yesterday") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE() - INTERVAL 1 SECOND ORDER BY quoteDate DESC";
                    } else {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 23:59:00", strtotime($_REQUEST["date_from_val"] . " -1 days")) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"] . " -1 days")) . "' ORDER BY quoteDate DESC";
                    }
                }

                if ($_REQUEST["flg"] == "7days") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 7 DAY AND SYSDATE() ORDER BY quoteDate DESC";
                    } else {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -7 days")) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY quoteDate DESC";
                    }
                }

                if ($_REQUEST["flg"] == "b2bl") {
                    $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"])) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY quoteDate DESC";
                }

                if ($_REQUEST["flg"] == "month") {
                    if ($_REQUEST["in_dt_range"] != "yes") {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND qstatus !=2 AND quoteDate BETWEEN CURDATE() - INTERVAL 30 DAY AND SYSDATE() ORDER BY quoteDate DESC";
                    } else {
                        $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . " -30 days")) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY quoteDate DESC";
                    }
                }
                if ($_REQUEST["poenter"] == "y") {
                    $dt_view_qry = "SELECT companyID, quote.ID AS I, quoteDate, qstatus, quote.rep AS R, employees.name AS E, filename, quoteType, quote_total, company FROM quote INNER JOIN companyInfo on quote.companyID  = companyInfo.ID INNER JOIN employees ON quote.rep = employees.employeeID WHERE quote.rep LIKE '" . $_REQUEST["b2bempid"] . "' AND  qstatus !=2 AND quoteDate BETWEEN '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_from_val"] . "")) . "' AND '" . Date("Y-m-d 00:00:00", strtotime($_REQUEST["date_to_val"])) . "' ORDER BY quoteDate DESC";
                }
            }

            //echo $dt_view_qry;
            $srno = 0;
            db_b2b();
            $dt_view_res = db_query($dt_view_qry);
            while ($dt_view_row = array_shift($dt_view_res)) {
                $srno = $srno + 1;
            ?>
            <tr>

                <td bgColor="#E4EAEB" class="style12">
                    <?php echo $srno; ?>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <?php echo timestamp_to_date($dt_view_row["quoteDate"]); ?>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <a target="_blank"
                        href="http://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["companyID"]; ?>"><?php echo get_nickname_val($dt_view_row["company"], $dt_view_row["b2bid"]); ?></a>
                </td>
                <td bgColor="#E4EAEB" class="style12">
                    <?php echo $dt_view_row["E"]; ?>
                </td>

                <td bgColor="#E4EAEB" class="style12">
                    <?php
                        if ($dt_view_row["filename"] != "") { ?>
                    <?php
                            $archeive_date = new DateTime(date("Y-m-d", strtotime($quotes_archive_date)));
                            $quote_date = new DateTime(date("Y-m-d", strtotime($dt_view_row["quoteDate"])));

                            if ($quote_date < $archeive_date) {
                            ?>
                    <a target="_blank"
                        href='https://usedcardboardboxes.sharepoint.com/:b:/r/sites/LoopsCRMEmailAttachments/Shared%20Documents/quotes/before_oct_18_2022/<?php echo $dt_view_row["filename"]; ?>'>
                        <?php
                            } else {
                                ?>
                        <a target="_blank"
                            href="http://loops.usedcardboardboxes.com/quotes/<?php echo $dt_view_row["filename"]; ?>">
                            <?php
                                }
                                    ?>

                            <?php
                            } elseif ($dt_view_row["quoteType"] == "Quote") {
                                ?>
                            <a target="_blank"
                                href="http://loops.usedcardboardboxes.com/fullquote.php?ID=<?php echo $dt_view_row["I"] ?>">
                                <?php } elseif ($dt_view_row["quoteType"] == "Quote Select") {

                                    ?>
                                <a target="_blank"
                                    href="http://b2b.usedcardboardboxes.com/b2b5/quoteselect.asp?ID=<?php echo $dt_view_row["I"] ?>">
                                    <?php } ?>
                                    <?php echo number_format($dt_view_row["quote_total"], 2); ?></a>
                </td>

            </tr>
            <?php
            }    //while loop
            ?>

        </table>
    </form>