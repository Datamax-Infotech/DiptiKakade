<?php

require "../mainfunctions/database.php";
require "../mainfunctions/general-functions.php";

db();

?>
<title>B2B Online Customer Portal Logins Summary Report</title>
<LINK rel='stylesheet' type='text/css' href='one_style.css'>
<link rel='stylesheet' type='text/css' href='css/ucb_common_style.css'>
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,400;0,600;1,300;1,400&display=swap" rel="stylesheet">

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
            document.rpt_leaderboard.action = "report_client_dash_summary.php";
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
            B2B Customer Online Portal Company wise Summary Report

            <div class="tooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>
                <span class="tooltiptext">This report allows the user to see all logins created for the B2B Online
                    Customer Portals.</span>
            </div><br>
        </div>
    </div>

    <form method="get" name="rpt_leaderboard" action="report_client_dash_summary.php">
        <table>
            <tr>
                <td>Please select Date Range </td>
                <td>
                    From:
                    <input type="text" name="date_from" id="date_from" size="10" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : date("m/d/Y", strtotime($date_from)); ?>">
                    <a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_from,'dtanchor2xx','MM/dd/yyyy'); return false;" name="dtanchor2xx" id="dtanchor2xx"><img border="0" src="images/calendar.jpg"></a>
                    <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
                    </div>
                    To:
                    <input type="text" name="date_to" id="date_to" size="10" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : date("m/d/Y", strtotime($date_to)); ?>">
                    <a href="#" onclick="cal2xx.select(document.rpt_leaderboard.date_to,'dtanchor3xx','MM/dd/yyyy'); return false;" name="dtanchor3xx" id="dtanchor3xx"><img border="0" src="images/calendar.jpg"></a>
                    <div ID="listdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;">
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
            <td class="style24" colspan="6" style="height: 16px" align="middle">
                <strong>Client Dashboard log</strong>
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
                <strong>Login User Name</strong>
            </td>
            <td bgColor="#e4e4e4" class="style12" style="height: 16px" align="middle">
                <strong>Frequency</strong>
            </td>
        </tr>
        <?php
        $dt_view_qry = "SELECT clientdashboard_usermaster.companyid FROM clientdashboard_usermaster GROUP BY clientdashboard_usermaster.companyid ORDER BY clientdashboard_usermaster.loginid DESC ";
        //echo $dt_view_qry;
        $dt_view_res = db_query($dt_view_qry);

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
            $getCompLoginIds = db_query("SELECT loginid FROM clientdashboard_usermaster WHERE companyid = " . $dt_view_row["companyid"]);
            $newCompArr = array();
            while ($rowsCompLoginIds = array_shift($getCompLoginIds)) {
                $newCompArr[] = $rowsCompLoginIds['loginid'];
            }


            $loginid = "<font color='#000055'>Never Logged In</font>";
            $login_datetime = "";
            $user_name = "";
            $data_found = "no";
            $back_door_login = 0;
            $rcount1 = $drow1 = 0;
            $rcount2 = $drow2 = 0;
            $resLoginDt = $resLoginBk = $newtmp = array();

            $getLoginDt = "SELECT clientdashboard_user_log.*, clientdashboard_usermaster.loginid, clientdashboard_usermaster.user_name FROM clientdashboard_user_log INNER JOIN clientdashboard_usermaster ON clientdashboard_usermaster.loginid = clientdashboard_user_log.userid WHERE clientdashboard_user_log.userid IN (" . implode(',', $newCompArr) . ") AND clientdashboard_user_log.login_datetime BETWEEN '" . $date_from . " 00:00:00' AND '" . $date_to . " 23:59:59' ORDER BY clientdashboard_user_log.unqid DESC";
            db();
            $resLoginDt = db_query($getLoginDt);
            $drow1 = tep_db_num_rows($resLoginDt);
            $rcount1 = count($resLoginDt);
            $newtmp[0] = $resLoginDt[0];
            $newtmp[0]['totalCount'] = $rcount1;
            $resLoginDt = $newtmp;

            $getLoginDt = "SELECT * FROM clientdashboard_user_log WHERE back_door_compid = '" . $dt_view_row["companyid"] . "' AND login_datetime BETWEEN '" . $date_from . " 00:00:00' AND '" . $date_to . " 23:59:59' ORDER BY unqid DESC";
            db();
            $resLoginBk = db_query($getLoginDt);
            $drow2 = tep_db_num_rows($resLoginBk);
            $rcount2 = count($resLoginBk);
            $newtmp = "";
            $newtmp[0] = $resLoginBk[0];
            $newtmp[0]['totalCount'] = $rcount2;
            $resLoginBk = $newtmp;


            if ($drow2 > 0) {
                $resLoginBk[0]['loginid'] = "0";
                $resLoginBk[0]['user_name'] = "";
            }
            $resLoginRw = array();
            $toval = "0";
            if ($drow1 > 0 && $drow2 > 0) {
                if ($resLoginDt[0]['unqid'] > $resLoginBk[0]['unqid']) {
                    $resLoginMg = array_merge($resLoginDt, $resLoginBk);
                } else {
                    $resLoginMg = array_merge($resLoginBk, $resLoginDt);
                }
                $toval = array_sum(array_column($resLoginMg, 'totalCount')) + 1;
                $resLoginRw[] = array_shift($resLoginMg);
            } elseif ($drow1 > 0 && $drow2 == 0) {
                $resLoginRw = $resLoginDt;
            } elseif ($drow1 == 0 && $drow2 > 0) {
                $resLoginRw = $resLoginBk;
            } else {
                $data_found = "no";
            }

            while ($rowLoginDt = array_shift($resLoginRw)) {

                $data_found = "yes";
                if ($rowLoginDt["userid"] == 0) {
                    $loginid = "<font color='#005599'>Back door</font>";
                } else {
                    $loginid = $rowLoginDt["userid"];
                }
                $login_datetime = $rowLoginDt["login_datetime"];
                $user_name = $rowLoginDt["user_name"];
        ?>

                <tr bgcolor="<?php echo $rowClr; ?>">

                    <td class="style12"><?php echo $loginid; ?> </td>
                    <td class="style12" style="text-align:left !important;"><a href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["companyid"]; ?>" target="_blank"><?php echo $getCompNm; ?></a></td>
                    <td class="style12" style="text-align:left !important;"><?php echo $rowCompContact["contact"]; ?></td>
                    <td class="style12"><?php echo $login_datetime; ?></td>
                    <td class="style12"> <?php echo $user_name; ?> </td>
                    <td class="style12">
                        <?php
                        if ($drow1 > 0 && $drow2 > 0) {
                            echo $toval;
                        } else {
                            echo $rowLoginDt['totalCount'];
                        }
                        ?>
                    </td>
                </tr>

            <?php
            }

            if ($data_found == "no") {
            ?>
                <tr bgcolor="<?php echo $rowClr; ?>">
                    <td class="style12"> <?php echo $loginid; ?> </td>
                    <td class="style12" style="text-align:left !important;"><a href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["companyid"]; ?>" target="_blank"><?php echo $getCompNm; ?></a></td>
                    <td class="style12" style="text-align:left !important;"><?php echo $rowCompContact["contact"]; ?></td>
                    <td class="style12"><?php echo $login_datetime; ?></td>
                    <td class="style12"> <?php echo $user_name; ?> </td>
                    <td class="style12"></td>
                </tr>
        <?php
            }

            /*
		$loginid = "Never Logged In"; $login_datetime = ""; $user_name = ""; $data_found = "no"; $back_door_login = 0;
		$getLoginDt = "SELECT clientdashboard_user_log.*, clientdashboard_usermaster.loginid, clientdashboard_usermaster.user_name FROM clientdashboard_user_log INNER JOIN clientdashboard_usermaster ON clientdashboard_usermaster.loginid = clientdashboard_user_log.userid WHERE clientdashboard_user_log.userid IN (".implode(',', $newCompArr).") AND clientdashboard_user_log.login_datetime BETWEEN '".$date_from." 00:00:00' AND '".$date_to." 23:59:59' ORDER BY clientdashboard_user_log.unqid DESC";
		$resLoginDt = db_query($getLoginDt, db());
		while($rowLoginDt = array_shift($resLoginDt)){
			$data_found = "yes";
			$loginid = $rowLoginDt["loginid"];
			$login_datetime = $rowLoginDt["login_datetime"];
			$user_name = $rowLoginDt["user_name"];
			$back_door_login = $rowLoginDt["back_door_login"];
			?>

        <tr bgcolor="<?php echo $rowClr;?>">
            <td class="style12"> <?php  echo $loginid;?> </td>
            <td class="style12" style="text-align:left !important;"><a
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["companyid"];?>"
                    target="_blank"><?php echo $getCompNm;?></a></td>
            <td class="style12" style="text-align:left !important;"><?php echo $rowCompContact["contact"];?></td>
            <td class="style12"><?php echo $login_datetime;?></td>
            <td class="style12"> <?php echo $user_name;?> + <?php echo $rowLoginDt["unqid"];?> </td>
            <!-- <td class="style12" > 
					<?php 
					//$getLoginDt = db_query("SELECT count(clientdashboard_user_log.unqid) AS totalCount FROM clientdashboard_user_log  WHERE clientdashboard_user_log.userid IN (".implode(',', $newCompArr).") AND clientdashboard_user_log.login_datetime BETWEEN '".$date_from." 00:00:00' AND '".$date_to." 23:59:59'", db()); 
					//echo $getLoginDt[0]['totalCount'];?> 
				</td> -->
        </tr>

        <?
		}
		
		$loginid = "Never Logged In"; $login_datetime = ""; $user_name = ""; $back_door_login = 0;
		$getLoginDt = "SELECT clientdashboard_user_log.* FROM clientdashboard_user_log WHERE back_door_compid = '" . $dt_view_row["companyid"] . "' AND clientdashboard_user_log.login_datetime BETWEEN '".$date_from." 00:00:00' AND '".$date_to." 23:59:59' ORDER BY clientdashboard_user_log.unqid DESC";
		$resLoginDt = db_query($getLoginDt, db());
		while($rowLoginDt = array_shift($resLoginDt)){
			$loginid = "Back door";
			$login_datetime = $rowLoginDt["login_datetime"];
			$user_name = "";
			?>

        <tr bgcolor="<?php echo $rowClr;?>">
            <td class="style12"> <?php  echo $loginid;?> </td>
            <td class="style12" style="text-align:left !important;"><a
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["companyid"];?>"
                    target="_blank"><?php echo $getCompNm;?></a></td>
            <td class="style12" style="text-align:left !important;"><?php echo $rowCompContact["contact"];?></td>
            <td class="style12"><?php echo $login_datetime;?></td>
            <td class="style12"> <?php echo $user_name;?> </td>
            <!-- <td class="style12" > 
					<?php 
					//$getLoginDt = db_query("SELECT count(clientdashboard_user_log.unqid) AS totalCount FROM clientdashboard_user_log  WHERE clientdashboard_user_log.userid IN (".implode(',', $newCompArr).") AND clientdashboard_user_log.login_datetime BETWEEN '".$date_from." 00:00:00' AND '".$date_to." 23:59:59'", db()); 
					//echo $getLoginDt[0]['totalCount'];?> 
				</td> -->
        </tr>

        <?
		}
		
		if ($data_found == "no"){ ?>
        <tr bgcolor="<?php echo $rowClr;?>">
            <td class="style12"> <?php  echo $loginid;?> </td>
            <td class="style12" style="text-align:left !important;"><a
                    href="https://loops.usedcardboardboxes.com/viewCompany.php?ID=<?php echo $dt_view_row["companyid"];?>"
                    target="_blank"><?php echo $getCompNm;?></a></td>
            <td class="style12" style="text-align:left !important;"><?php echo $rowCompContact["contact"];?></td>
            <td class="style12"><?php echo $login_datetime;?></td>
            <td class="style12"> <?php echo $user_name;?> </td>
        </tr>
        <?}
		*/
            $row++;
        }
        ?>
    </table>
</div>