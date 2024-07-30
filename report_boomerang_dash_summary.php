<?php

require("inc/header_session.php");
require_once("../mainfunctions/database.php");

require_once("../mainfunctions/general-functions.php");

db();

?>
<title>Boomerang Portal Logins Summary Report</title>
<LINK rel='stylesheet' type='text/css' href='one_style.css'>
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
        document.rpt_leaderboard.action = "report_boomerang_dash_summary.php";
    } else {
        alert("Please select date From/To.");
        return false;
    }
}
</script>

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

<?php include("inc/header.php"); ?>
<div class="main_data_css">
    <div class="dashboard_heading" style="float: left;">
        <div style="float: left;">
            Boomerang Portal User wise Summary Report

            <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                <span class="tooltiptext">This report allows the user to see all logins created for the B2B Online
                    Customer Portals.</span>
            </div><br>
        </div>
    </div>

    <form method="get" name="rpt_leaderboard" action="report_boomerang_dash_summary.php">
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

    <table width="600px" border="0" cellspacing="1" cellpadding="1">
        <tr>
            <td class="style24" colspan="5" style="height: 16px" align="middle">
                <strong>Boomerang Dashboard log</strong>
            </td>
        </tr>
        <tr>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px; width:50px" align="middle">
                <strong>Login ID</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px;width:200px" align="middle">
                <strong>Login User Name</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px;width:200px" align="middle">
                <strong>User Name</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px;width:200px" align="middle">
                <strong>User IP Address</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px;width:150px" align="middle">
                <strong>Log in Date Time</strong>
            </td>
        </tr>
        <?php

        $loginid = "<font color='#000055'>Never Logged In</font>";
        $login_datetime = "";
        $user_name = "";
        $data_found = "no";
        $back_door_login = 0;
        $rcount1 = $drow1 = 0;
        $rcount2 = $drow2 = 0;
        $resLoginDt = $resLoginBk = $newtmp = array();
        $row = 1;

        db();
        $getLoginDt = "SELECT boomerang_user_log.*, boomerang_usermaster.user_email, boomerang_usermaster.user_name, boomerang_usermaster.user_last_name FROM boomerang_user_log 
	inner join boomerang_usermaster on boomerang_usermaster.loginid = boomerang_user_log.userid 
	WHERE boomerang_user_log.login_datetime BETWEEN '" . $date_from . " 00:00:00' AND '" . $date_to . " 23:59:59' ORDER BY boomerang_user_log.unqid DESC";
        $resLoginBk = db_query($getLoginDt);
        while ($rowLoginDt = array_shift($resLoginBk)) {

            if ($row % 2 == 0) {
                $rowClr = '#e4e4e4';
            } else {
                $rowClr = '#d4d4d4';
            }

            $data_found = "yes";
            if ($rowLoginDt["userid"] == 0) {
                $loginid = "<font color='#005599'>Back door</font>";
            } else {
                $loginid = $rowLoginDt["userid"];
            }
            $login_datetime = $rowLoginDt["login_datetime"];
            $user_name = $rowLoginDt["user_name"] . " " . $rowLoginDt["user_last_name"];
        ?>

        <tr bgcolor="<?php echo  $rowClr; ?>">

            <td class="style12">
                <?php echo $loginid; ?>
            </td>
            <td class="style12">
                <?php echo $rowLoginDt["user_email"]; ?>
            </td>
            <td class="style12">
                <?php echo $user_name; ?>
            </td>
            <td class="style12">
                <?php echo $rowLoginDt["ipaddress"]; ?>
            </td>
            <td class="style12">
                <?php echo $login_datetime; ?>
            </td>
        </tr>

        <?php
            $row++;
        }


        ?>
    </table>
</div>