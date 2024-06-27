<?php

require("../mainfunctions/database.php");
require("../mainfunctions/general-functions.php");

db();

?>
<!DOCTYPE HTML>

<html>

<head>
    <title>Other Account Type Ownership Summary Report - UCB Sales Review Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
        rel="stylesheet">
    <LINK rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
    <meta http-equiv="refresh" content="1800" />
    <link rel="stylesheet" href="sorter/style_rep.css" />
    <style type="text/css">
    .txtstyle_color {
        font-family: arial;
        font-size: 12;
        height: 16px;
        background: #ABC5DF;
    }
    </style>

</head>

<body>
    <?php

    include("inc/header.php");

    ?>

    <div class="main_data_css">

        <div class="dashboard_heading" style="float: left;">
            <div style="float: left;">
                Other Account Type Ownership Summary Report

                <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltiptext">This report shows the user all B2B records in the system not classified as
                        B2B Sales or B2B Purchasing records, summarized by account owner.</span>
                </div><br>
            </div>
        </div>
        <table border="0">

            <tr>
                <td valign="top">
                    <table cellSpacing="1" cellPadding="1" border="0" width="1170">
                        <tr>
                            <td class="txtstyle_color" align="center" style="font-size:14pt;"><strong>Other
                                    Assignments</strong></td>
                        </tr>
                    </table>
                    <table cellSpacing="1" cellPadding="1" border="0" width="1170" id="table15" class="tablesorter">
                        <thead>
                            <tr>
                                <th width="170px" bgColor='#E4EAEB'><u>Employee</u></th>
                                <th width='100px' bgColor='#E4EAEB' align="center"><u>B2C Relationship</u></th>
                                <th width='100px' bgColor='#E4EAEB' align="center"><u>Other</u></th>
                                <th width='60px' bgColor='#E4EAEB' align="center"><u>Trash</u></th>
                                <th width='80px' bgColor='#E4EAEB' align="center"><u>Total</u></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            db_b2b();
                            $grandtot = 0;
                            $emp_list = "";
                            $col1 = 0;
                            $col2 = 0;
                            $col3 = 0;
                            $col4 = 0;

                            $sql = "SELECT assignedto, employeeID, name, count(assignedto) FROM companyInfo inner join employees on employees.employeeID = companyInfo.assignedto where companyInfo.status IN (52, 33, 31) group by companyInfo.assignedto having count(assignedto) > 0 order by count(assignedto) desc";
                            $resulte = db_query($sql);

                            while ($rowemp = array_shift($resulte)) {

                                $emp_list .= $rowemp["employeeID"] . ",";

                                $tot = 0;
                                $tmp1 = $tmp2 = $tmp3 = $tmp4 = 0;
                                $sql = "SELECT  status, count(ID) as cnt FROM companyInfo WHERE assignedto = " . $rowemp["employeeID"] . " AND status IN (52, 33, 31) GROUP BY `status`";
                                $result = db_query($sql);
                                $array = array_combine(array_column($result, 'status'), array_column($result, 'cnt'));

                                if (array_key_exists('52', $array)) {
                                    $tmp1 = $array['52'];
                                }
                                if (array_key_exists('33', $array)) {
                                    $tmp2 = $array['33'];
                                }
                                if (array_key_exists('31', $array)) {
                                    $tmp3 = $array['31'];
                                }
                                $col1 = $col1 + $tmp1;
                                echo "<tr><td bgColor='#E4EAEB'>" . $rowemp["name"] . "</td><td bgColor='#E4EAEB' align=right><a target='_blank' href='report_show_assignments.php?rep_name=other_assignments&show=status&statusid=52&eid=" . $rowemp["employeeID"] . "'>" . $tmp1 . "</a></td>";
                                echo "<td bgColor='#E4EAEB' align=right>";
                                $col2 = $col2 + $tmp2;
                                echo "<a target='_blank' href='report_show_assignments.php?rep_name=other_assignments&show=status&statusid=33&eid=" . $rowemp["employeeID"] . "'>" . $tmp2 . "</a>";
                                echo "</td><td bgColor='#E4EAEB' align=right>";
                                $col3 = $col3 + $tmp3;
                                echo "<a target='_blank' href='report_show_assignments.php?rep_name=other_assignments&show=status&statusid=31&eid=" . $rowemp["employeeID"] . "'>" . $tmp3 . "</a>";
                                echo "</td><td bgColor='#E4EAEB' align=right style='border-left: 1px solid #000;'>";
                                $tmp4 = $tmp1 + $tmp2 + $tmp3;
                                $col4 = $col4 + $tmp4;
                                echo "<a target='_blank' href='report_show_assignments.php?rep_name=other_assignments&show=status&statusid=52,33,31&eid=" . $rowemp["employeeID"] . "'>" . $tmp4 . "</a>";
                                echo "</td>";

                                echo "</tr>";
                            }

                            echo "</tbody>";

                            $unassign_col4 = $unassign_col3 = $unassign_col2 = $unassign_col1 = 0;
                            $sql = "SELECT `status`, COUNT(ID) as cnt FROM companyInfo WHERE assignedto = '' AND status IN (52, 33, 31) GROUP BY status";
                            db_b2b();
                            $result = db_query($sql);
                            $array = array_combine(array_column($result, 'status'), array_column($result, 'cnt'));
                            //echo "<pre>"; print_r($array); echo "</pre>";
                            if (array_key_exists('52', $array)) {
                                $unassign_col1 = $unassign_col1 + $array['52'];
                            }
                            if (array_key_exists('33', $array)) {
                                $unassign_col2 = $unassign_col2 + $array['33'];
                            }
                            if (array_key_exists('31', $array)) {
                                $unassign_col3 = $unassign_col3 + $array['31'];
                            }
                            $unassign_col4 = ($unassign_col1 + $unassign_col2 + $unassign_col3);

                            echo "<tr><td bgColor='#E4EAEB'><b>Unassigned</b></td>
			<td bgColor='#E4EAEB' align=right><a target='_blank' href='report_show_assignments.php?show=status&statusid=52&eid='>" . $unassign_col1 . "</a></td>";
                            echo "<td bgColor='#E4EAEB' align=right>";

                            echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=33&eid='>" . $unassign_col2 . "</a>";
                            echo "</td><td bgColor='#E4EAEB' align=right>";

                            echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=31&eid='>" . $unassign_col3 . "</a>";
                            echo "</td><td bgColor='#E4EAEB' align=right style='border-left: 1px solid #000;'>";

                            echo "<a target='_blank' href='report_show_assignments.php?show=status&statusid=52,33,31&eid='>" . $unassign_col4 . "</a>";
                            echo "</td>";

                            echo "</tr>";

                            $emp_list = rtrim($emp_list, ",");
                            $sorturl = "report_show_assignments_new.php?show=status&eid=" . $emp_list . "&statusid=";

                            echo "<tr><td bgColor='#E4EAEB' align=right><b>Total</b></td><td bgColor='#E4EAEB' align=right><strong>";
                            echo "<a target='_blank' href='" . $sorturl . "52'>" . ($col1 + $unassign_col1) . "</a>";
                            echo "</strong></td><td bgColor='#E4EAEB' align=right><strong>";
                            echo "<a target='_blank' href='" . $sorturl . "33'>" . ($col2 + $unassign_col2) . "</a>";
                            echo "</strong></td><td bgColor='#E4EAEB' align=right><strong>";
                            echo "<a target='_blank' href='" . $sorturl . "31'>" . ($col3 + $unassign_col3) . "</a>";
                            echo "</strong></td><td bgColor='#E4EAEB' align=right style='border-left: 1px solid #000;'><strong>";
                            echo "<a target='_blank' href='" . $sorturl . "52,33,31'>" . ($col4 + $unassign_col4) . "</a>";
                            echo "</strong></td>";

                            echo "</tr>";

                            ?>
                    </table>

                </td>

            </tr>

            <tr>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td valign="top">
                    <table cellSpacing="1" cellPadding="1" border="0" width="1170">
                        <?php

                        $overall_cnt = 0;
                        db_b2b();
                        $sql = "SELECT count(*) as cnt FROM companyInfo WHERE status = 0 and active = 1";
                        $result_m = db_query($sql);
                        while ($rowemp_m = array_shift($result_m)) {
                            $overall_cnt = $overall_cnt + $rowemp_m["cnt"];
                        }

                        ?>
                        <tr>
                            <td class="txtstyle_color" align="center" style="font-size:14pt;"><strong>Unassigned - <a
                                        href="report_daily_chart_unassigned.php" target="_blank">
                                        <?php

                                        echo $overall_cnt;

                                        ?>
                                    </a></strong></td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>

    </div>
</body>

</html>