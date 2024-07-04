<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();
?>
<title>B2B Supplier Online Portal Login Usage Summary Report</title>
<link rel='stylesheet' type='text/css' href='one_style.css'>
<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap"
    rel="stylesheet">

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
    if (document.getElementById('date_from').value != "" && document.getElementById('date_to').value != "") {
        document.rpt_leaderboard.action = "report_supplier_dash_summary.php";
    } else {
        alert("Please select date From/To.");
        return false;
    }
}
</script>

<style>
.newtxttheam_withdot {
    font-family: Arial;
    font-size: 12px;
    padding: 4px;
    background-color: #EFEEE7;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>

<?php

$todaysDt = date('Y-m-d');
if (isset($_GET["date_from"]) && !empty($_GET["date_from"])) {
    $date_from =  date("Y-m-d", strtotime($_GET["date_from"]));
} else {
    $date_from =  date('Y-m-d', strtotime($todaysDt . " -1 month"));
}

if (isset($_GET["date_to"]) && !empty($_GET["date_to"])) {
    $date_to =  date("Y-m-d", strtotime($_GET["date_to"]));
} else {
    $date_to =  date('Y-m-d', strtotime($todaysDt));;
}

?>

<?php

include("inc/header.php");

?>
<div class="main_data_css">

    <div class="dashboard_heading" style="float: left;">
        <div style="float: left;">
            B2B Supplier Online Portal Login Usage Summary Report
        </div>
        &nbsp;<div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
            <span class="tooltiptext">This report shows the user all logins into the B2B Supplier Online Portals within
                a date range.</span>
        </div><br>
        <div style="height: 13px;">&nbsp;</div>
    </div>

    <form method="get" name="rpt_leaderboard" action="report_supplier_dash_summary.php">
        <table>
            <tr>
                <td>Please select Date Range </td>
                <td>
                    From:
                    <input type="text" name="date_from" id="date_from" size="10"
                        value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : date("m/d/Y", strtotime($date_from)); ?>">
                    <a href="#"
                        onclick="cal2xx.select(document.rpt_leaderboard.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;"
                        name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
                    <div ID="listdiv"
                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
                    To:
                    <input type="text" name="date_to" id="date_to" size="10"
                        value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : date("m/d/Y", strtotime($date_to)); ?>">
                    <a href="#"
                        onclick="cal2xx.select(document.rpt_leaderboard.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;"
                        name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
                    <div ID="listdiv"
                        STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
                </td>
                <td>
                    <input type=submit value="Run Report" onClick="javascript: return  loadmainpg()">
                </td>
            </tr>
        </table>
    </form>

    <table width="80%" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td class="style24" colspan=6 style="height: 16px" align="middle"><strong>Supplier Dashboard log</strong>
            </td>
        </tr>
        <tr>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
                <strong>Login ID</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
                <strong>Company Name</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
                <strong>Contact Name</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
                <strong>Log in Date Time</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
                <strong>Employee Created</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
                <strong>Login Count</strong>
            </td>
        </tr>
        <?php
        $dt_view_qry = "SELECT supplier_dash_usermaster.companyid FROM supplier_dash_usermaster GROUP BY supplier_dash_usermaster.companyid ORDER BY supplier_dash_usermaster.loginid DESC ";
        //echo $dt_view_qry;
        $dt_view_res = db_query($dt_view_qry);
        //echo "<pre>"; print_r($dt_view_res); echo "</pre>";
        $row = 1;
        while ($dt_view_row = array_shift($dt_view_res)) {
            if ($row % 2 == 0) {
                $rowClr = '#e4e4e4';
            } else {
                $rowClr = '#d4d4d4';
            }

            $getCompNm = get_nickname_val('', $dt_view_row["companyid"]);
            db_b2b();
            $getCompContact = db_query("SELECT contact FROM companyInfo where ID = " . $dt_view_row['companyid']);
            $rowCompContact = array_shift($getCompContact);
            db();
            $getCompLoginIds = db_query("SELECT loginid FROM supplier_dash_usermaster WHERE companyid = " . $dt_view_row["companyid"]);
            $newCompArr = array();
            while ($rowsCompLoginIds = array_shift($getCompLoginIds)) {
                $newCompArr[] = $rowsCompLoginIds['loginid'];
            }
            $getLoginDt = "SELECT clientdashboard_user_log.*, supplier_dash_usermaster.loginid, supplier_dash_usermaster.user_name FROM clientdashboard_user_log INNER JOIN supplier_dash_usermaster ON supplier_dash_usermaster.loginid = clientdashboard_user_log.userid WHERE clientdashboard_user_log.userid IN (" . implode(',', $newCompArr) . ") AND clientdashboard_user_log.login_datetime BETWEEN '" . $date_from . " 00:00:00' AND '" . $date_to . " 23:59:59' ORDER BY clientdashboard_user_log.unqid DESC LIMIT 1";
            db();
            $resLoginDt = db_query($getLoginDt);
            $rowLoginDt = array_shift($resLoginDt);
        ?>

        <tr bgcolor="<?php echo $rowClr; ?>">
            <td class="style12">
                <?php echo $rowLoginDt["loginid"]; ?>
            </td>
            <td class="style12" style="text-align:left !important;"><a
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["companyid"]; ?>"
                    target="_blank">
                    <?php echo $getCompNm; ?>
                </a></td>
            <td class="style12" style="text-align:left !important;">
                <?php echo $rowCompContact["contact"]; ?>
            </td>
            <td class="style12">
                <?php echo $rowLoginDt["login_datetime"]; ?>
            </td>
            <td class="style12">
                <?php echo $rowLoginDt["user_name"]; ?>
            </td>
            <td class="style12">
                <?php

                    db();
                    $getLoginDt = db_query("SELECT count(clientdashboard_user_log.unqid) AS totalCount FROM clientdashboard_user_log  WHERE clientdashboard_user_log.userid IN (" . implode(',', $newCompArr) . ") AND clientdashboard_user_log.login_datetime BETWEEN '" . $date_from . " 00:00:00' AND '" . $date_to . " 23:59:59'");
                    echo $getLoginDt[0]['totalCount']; ?>
            </td>
        </tr>
        <?php

            $row++;
        }

        ?>
    </table>
</div>